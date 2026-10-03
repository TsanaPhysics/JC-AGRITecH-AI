<?php
/**
 * LEQs-xAI REST API Backend
 * Stores and retrieves participants, test scores, and project groups.
 * SQLite3 Database Engine with JSON fallback.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$db_file = __DIR__ . '/../data/leqs_xai.db';
$json_backup = __DIR__ . '/../data/participants.json';

// Ensure data folder exists
if (!is_dir(__DIR__ . '/../data')) {
    mkdir(__DIR__ . '/../data', 0777, true);
}

// Initialize SQLite3
$db = null;
if (class_exists('SQLite3')) {
    try {
        $db = new SQLite3($db_file);
        $db->exec("CREATE TABLE IF NOT EXISTS participants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            prefix TEXT,
            fullname TEXT NOT NULL,
            target_group TEXT NOT NULL,
            organization TEXT,
            phone TEXT,
            email TEXT,
            project_track TEXT,
            pre_score INTEGER DEFAULT 0,
            post_score INTEGER DEFAULT 0,
            status TEXT DEFAULT 'registered',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    } catch (Exception $e) {
        $db = null;
    }
}

$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

// 1. GET /api/api.php?action=list
if ($action === 'list') {
    if ($db) {
        $results = $db->query("SELECT * FROM participants ORDER BY id DESC");
        $data = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit();
    } else {
        $data = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
        echo json_encode(['status' => 'success', 'data' => $data ?: []]);
        exit();
    }
}

// 2. POST /api/api.php (Register Participant)
if ($action === 'register') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }

    $fullname = trim($input['fullname'] ?? '');
    $prefix = trim($input['prefix'] ?? 'นาย');
    $target_group = trim($input['target_group'] ?? 'นักเรียนมัธยมศึกษาตอนปลาย');
    $organization = trim($input['organization'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $email = trim($input['email'] ?? '');
    $project_track = trim($input['project_track'] ?? 'Track A — Smart Agriculture');

    if (empty($fullname)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อ-นามสกุล']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track) VALUES (:prefix, :fullname, :target_group, :organization, :phone, :email, :project_track)");
        $stmt->bindValue(':prefix', $prefix, SQLITE3_TEXT);
        $stmt->bindValue(':fullname', $fullname, SQLITE3_TEXT);
        $stmt->bindValue(':target_group', $target_group, SQLITE3_TEXT);
        $stmt->bindValue(':organization', $organization, SQLITE3_TEXT);
        $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':project_track', $project_track, SQLITE3_TEXT);
        $stmt->execute();
        $new_id = $db->lastInsertRowID();
        echo json_encode(['status' => 'success', 'message' => 'ลงทะเบียนสำเร็จ!', 'id' => $new_id]);
        exit();
    } else {
        $current = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
        $new_record = [
            'id' => count($current) + 1,
            'prefix' => $prefix,
            'fullname' => $fullname,
            'target_group' => $target_group,
            'organization' => $organization,
            'phone' => $phone,
            'email' => $email,
            'project_track' => $project_track,
            'pre_score' => 0,
            'post_score' => 0,
            'status' => 'registered',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $current[] = $new_record;
        file_put_contents($json_backup, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'ลงทะเบียนสำเร็จ!', 'id' => $new_record['id']]);
        exit();
    }
}

// 3. POST /api/api.php?action=save_test (Save Pre/Post Test Score)
if ($action === 'save_test') {
    $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $id = intval($input['id'] ?? 0);
    $type = $input['type'] ?? 'pre'; // 'pre' or 'post'
    $score = intval($input['score'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid participant ID']);
        exit();
    }

    if ($db) {
        $col = ($type === 'post') ? 'post_score' : 'pre_score';
        $stmt = $db->prepare("UPDATE participants SET {$col} = :score WHERE id = :id");
        $stmt->bindValue(':score', $score, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'บันทึกคะแนนเรียบร้อย']);
        exit();
    } else {
        echo json_encode(['status' => 'success', 'message' => 'บันทึกคะแนนเรียบร้อย (Mock)']);
        exit();
    }
}

// Fallback
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
