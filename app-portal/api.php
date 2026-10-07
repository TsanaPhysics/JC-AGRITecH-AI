<?php
/**
 * LEQs AIoT Application & Resource Portal - REST API
 * Handles: Registration, App Catalog, Token Verification, Analytics & CSV Export
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$dbPath = __DIR__ . '/database/app_portal.db';

if (!file_exists($dbPath)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database not found. Please run setup_db.php first.']);
    exit;
}

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'get_apps';

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

switch ($action) {
    case 'get_apps':
        try {
            $stmt = $db->query("SELECT * FROM apps ORDER BY 
                CASE id 
                    WHEN 'handysense' THEN 1 
                    WHEN 'durianleaf' THEN 2 
                    WHEN 'smarthealth' THEN 3 
                    WHEN 'objectdetect' THEN 4 
                    WHEN 'workshop_kit' THEN 5 
                    ELSE 6 
                END");
            $apps = $stmt->fetchAll();

            foreach ($apps as &$app) {
                $app['highlights'] = json_decode($app['highlights'] ?? '[]', true);
                $app['size_formatted'] = ($app['filesize_bytes'] > 1048576) 
                    ? number_format($app['filesize_bytes'] / (1024 * 1024), 1) . ' MB'
                    : number_format($app['filesize_bytes'] / 1024, 1) . ' KB';
            }

            // Global stats
            $totalDownloads = (int)$db->query("SELECT COALESCE(SUM(download_count), 0) FROM apps")->fetchColumn();
            $totalRegistered = (int)$db->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
            $totalDownloadLogs = (int)$db->query("SELECT COUNT(*) FROM download_logs")->fetchColumn();

            echo json_encode([
                'status' => 'success',
                'apps' => $apps,
                'stats' => [
                    'total_downloads' => $totalDownloads,
                    'total_registered' => $totalRegistered,
                    'total_download_logs' => $totalDownloadLogs,
                    'server_time' => date('Y-m-d H:i:s')
                ]
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $fullName = trim($input['full_name'] ?? '');
        $org = trim($input['organization'] ?? '');
        $group = trim($input['workshop_group'] ?? 'กลุ่มที่ 1');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $purpose = trim($input['purpose'] ?? 'เพื่อการอบรมเชิงปฏิบัติการ Smart AgriPhysics');
        $notes = trim($input['notes'] ?? '');

        if (empty($fullName) || empty($org) || empty($phone)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลที่จำเป็น: ชื่อ-นามสกุล, สังกัด/หน่วยงาน และเบอร์โทรศัพท์'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            // Check if phone or email already registered
            $checkStmt = $db->prepare("SELECT * FROM registrations WHERE phone = :phone OR (email != '' AND email = :email) LIMIT 1");
            $checkStmt->execute([':phone' => $phone, ':email' => $email]);
            $existing = $checkStmt->fetch();

            $ip = getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            if ($existing) {
                // Update existing record
                $updateStmt = $db->prepare("UPDATE registrations SET 
                    full_name = :full_name,
                    organization = :organization,
                    workshop_group = :workshop_group,
                    purpose = :purpose,
                    notes = :notes,
                    last_active = CURRENT_TIMESTAMP
                    WHERE id = :id");
                $updateStmt->execute([
                    ':full_name' => $fullName,
                    ':organization' => $org,
                    ':workshop_group' => $group,
                    ':purpose' => $purpose,
                    ':notes' => $notes,
                    ':id' => $existing['id']
                ]);

                $regToken = $existing['reg_token'];
                $isNew = false;
            } else {
                // Generate token
                $regToken = 'REG-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
                $insertStmt = $db->prepare("INSERT INTO registrations (
                    reg_token, full_name, organization, workshop_group, email, phone, purpose, notes, ip_address, user_agent
                ) VALUES (
                    :reg_token, :full_name, :organization, :workshop_group, :email, :phone, :purpose, :notes, :ip_address, :user_agent
                )");
                $insertStmt->execute([
                    ':reg_token' => $regToken,
                    ':full_name' => $fullName,
                    ':organization' => $org,
                    ':workshop_group' => $group,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':purpose' => $purpose,
                    ':notes' => $notes,
                    ':ip_address' => $ip,
                    ':user_agent' => $userAgent
                ]);
                $isNew = true;
            }

            echo json_encode([
                'status' => 'success',
                'is_new' => $isNew,
                'reg_token' => $regToken,
                'participant' => [
                    'full_name' => $fullName,
                    'organization' => $org,
                    'workshop_group' => $group,
                    'email' => $email,
                    'phone' => $phone,
                    'purpose' => $purpose
                ],
                'message' => 'ลงทะเบียนสำเร็จ สามารถดาวน์โหลดไฟล์ได้ทันที'
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'verify_token':
        $token = trim($_GET['token'] ?? $_POST['token'] ?? '');
        if (empty($token)) {
            echo json_encode(['status' => 'invalid', 'valid' => false]);
            exit;
        }

        try {
            $stmt = $db->prepare("SELECT id, reg_token, full_name, organization, workshop_group, email, phone, purpose, download_count_user, created_at FROM registrations WHERE reg_token = :token LIMIT 1");
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetch();

            if ($user) {
                echo json_encode([
                    'status' => 'success',
                    'valid' => true,
                    'user' => $user
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['status' => 'invalid', 'valid' => false]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'get_stats':
        try {
            // Group breakdown
            $groupStats = $db->query("
                SELECT workshop_group, COUNT(*) as participant_count, SUM(download_count_user) as total_downloads
                FROM registrations 
                GROUP BY workshop_group 
                ORDER BY 
                    CASE 
                        WHEN workshop_group LIKE 'กลุ่มที่%' THEN CAST(SUBSTR(workshop_group, 9) AS INTEGER)
                        ELSE 99 
                    END
            ")->fetchAll();

            // Recent downloads
            $recentLogs = $db->query("
                SELECT d.id, d.app_name, d.downloaded_at, d.ip_address, r.full_name, r.organization, r.workshop_group
                FROM download_logs d
                LEFT JOIN registrations r ON d.reg_id = r.id
                ORDER BY d.downloaded_at DESC LIMIT 15
            ")->fetchAll();

            // Purpose breakdown
            $purposeStats = $db->query("
                SELECT purpose, COUNT(*) as count 
                FROM registrations 
                GROUP BY purpose 
                ORDER BY count DESC
            ")->fetchAll();

            // App download breakdown
            $appStats = $db->query("SELECT id, name, download_count, badge_label, badge_color FROM apps ORDER BY download_count DESC")->fetchAll();

            echo json_encode([
                'status' => 'success',
                'groups' => $groupStats,
                'recent_logs' => $recentLogs,
                'purposes' => $purposeStats,
                'apps' => $appStats
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'export_registrations_csv':
        try {
            $stmt = $db->query("SELECT id, reg_token, full_name, organization, workshop_group, email, phone, purpose, download_count_user, ip_address, created_at, last_active FROM registrations ORDER BY id ASC");
            $rows = $stmt->fetchAll();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="leqs_workshop_registrations_' . date('Ymd_His') . '.csv"');

            // UTF-8 BOM for Thai text in Excel
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Registration Token', 'ชื่อ-นามสกุล', 'สังกัด/หน่วยงาน', 'กลุ่มอบรม', 'อีเมล', 'เบอร์โทรศัพท์', 'วัตถุประสงค์', 'ยอดดาวน์โหลด (ครั้ง)', 'IP Address', 'วันที่ลงทะเบียน', 'ใช้งานล่าสุด']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['id'],
                    $r['reg_token'],
                    $r['full_name'],
                    $r['organization'],
                    $r['workshop_group'],
                    $r['email'],
                    $r['phone'],
                    $r['purpose'],
                    $r['download_count_user'],
                    $r['ip_address'],
                    $r['created_at'],
                    $r['last_active']
                ]);
            }
            fclose($out);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error exporting CSV: " . $e->getMessage();
            exit;
        }
        break;

    case 'export_downloads_csv':
        try {
            $stmt = $db->query("
                SELECT d.id, d.downloaded_at, d.app_id, d.app_name, d.filename, d.ip_address, 
                       r.full_name, r.organization, r.workshop_group, r.email, r.phone, r.purpose
                FROM download_logs d
                LEFT JOIN registrations r ON d.reg_id = r.id
                ORDER BY d.id DESC
            ");
            $rows = $stmt->fetchAll();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="leqs_workshop_download_logs_' . date('Ymd_His') . '.csv"');

            // UTF-8 BOM for Thai in Excel
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'วัน-เวลาดาวน์โหลด', 'รหัสแอป', 'ชื่อแอปพลิเคชัน', 'ชื่อไฟล์', 'IP Address', 'ผู้ดาวน์โหลด', 'หน่วยงาน', 'กลุ่มอบรม', 'อีเมล', 'เบอร์โทร', 'วัตถุประสงค์']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['id'],
                    $r['downloaded_at'],
                    $r['app_id'],
                    $r['app_name'],
                    $r['filename'],
                    $r['ip_address'],
                    $r['full_name'] ?? 'ไม่ระบุ',
                    $r['organization'] ?? 'ไม่ระบุ',
                    $r['workshop_group'] ?? 'ไม่ระบุ',
                    $r['email'] ?? '-',
                    $r['phone'] ?? '-',
                    $r['purpose'] ?? '-'
                ]);
            }
            fclose($out);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error exporting CSV: " . $e->getMessage();
            exit;
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
