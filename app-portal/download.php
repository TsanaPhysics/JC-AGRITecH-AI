<?php
/**
 * LEQs AIoT Application & Resource Portal - Secure Downloader
 * Validates participant registration, increments download counters, and streams APK / Bundle files.
 */

// Disable output buffering and time limits for large downloads
@ini_set('max_execution_time', '600');
@ini_set('memory_limit', '512M');

$dbPath = __DIR__ . '/database/app_portal.db';
$filesDir = __DIR__ . '/files';

$appId = trim($_GET['app'] ?? $_GET['id'] ?? '');
$token = trim($_GET['token'] ?? $_COOKIE['leqs_reg_token'] ?? '');

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

if (empty($appId)) {
    header("Location: index.php?error=no_app_specified");
    exit;
}

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 1. Verify App
    $appStmt = $db->prepare("SELECT * FROM apps WHERE id = :id LIMIT 1");
    $appStmt->execute([':id' => $appId]);
    $app = $appStmt->fetch();

    if (!$app) {
        die("<h3>ไม่พบแอปพลิเคชันที่ต้องการ (Invalid App ID)</h3><p><a href='index.php'>กลับสู่หน้าหลัก</a></p>");
    }

    // 2. Verify Registration Token
    $reg = null;
    if (!empty($token)) {
        $regStmt = $db->prepare("SELECT * FROM registrations WHERE reg_token = :token LIMIT 1");
        $regStmt->execute([':token' => $token]);
        $reg = $regStmt->fetch();
    }

    if (!$reg) {
        // Redirect to portal with registration prompt
        header("Location: index.php?require_reg=1&app=" . urlencode($appId));
        exit;
    }

    // 3. Verify File Exists
    $filePath = $filesDir . '/' . $app['filename'];
    if (!file_exists($filePath)) {
        die("<h3>ไฟล์ระบบยังไม่พร้อมใช้งาน (File Not Found on Server)</h3><p>ไฟล์ " . htmlspecialchars($app['filename']) . " กำลังจัดเตรียม กรุณาติดต่อผู้ดูแลระบบ</p><p><a href='index.php'>กลับสู่หน้าหลัก</a></p>");
    }

    // 4. Log Download & Increment Counters
    $ip = getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $logStmt = $db->prepare("
        INSERT INTO download_logs (reg_id, reg_token, app_id, app_name, filename, ip_address, user_agent)
        VALUES (:reg_id, :reg_token, :app_id, :app_name, :filename, :ip_address, :user_agent)
    ");
    $logStmt->execute([
        ':reg_id' => $reg['id'],
        ':reg_token' => $token,
        ':app_id' => $app['id'],
        ':app_name' => $app['name'],
        ':filename' => $app['filename'],
        ':ip_address' => $ip,
        ':user_agent' => $userAgent
    ]);

    // Increment app counter
    $db->prepare("UPDATE apps SET download_count = download_count + 1 WHERE id = :id")
       ->execute([':id' => $app['id']]);

    // Increment user counter
    $db->prepare("UPDATE registrations SET download_count_user = download_count_user + 1, last_active = CURRENT_TIMESTAMP WHERE id = :id")
       ->execute([':id' => $reg['id']]);

    // 5. Send Headers & Stream File
    $filesize = filesize($filePath);
    $ext = strtolower(pathinfo($app['filename'], PATHINFO_EXTENSION));

    $contentType = ($ext === 'apk') 
        ? 'application/vnd.android.package-archive' 
        : (($ext === 'zip') ? 'application/zip' : 'application/octet-stream');

    // Clean output buffer before file transmission
    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . basename($app['filename']) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . $filesize);

    // Stream in chunks of 1MB for reliability
    $handle = fopen($filePath, 'rb');
    if ($handle === false) {
        die("Cannot read file");
    }

    while (!feof($handle)) {
        echo fread($handle, 1024 * 1024);
        flush();
    }
    fclose($handle);
    exit;

} catch (Exception $e) {
    die("Download Error: " . htmlspecialchars($e->getMessage()));
}
