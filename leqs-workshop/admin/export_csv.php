<?php
/**
 * LEQs-xAI Admin - CSV Export Utility
 * ส่งออกข้อมูลผู้เข้าร่วม คะแนน Pre/Post-test และผลการประเมิน
 * Modeled after scirbru_praneet2026/sys_export_csv.php
 */
session_start();

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leqs_xai_workshop_data_' . date('Y-m-d_His') . '.csv');

$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Microsoft Excel compatibility in Thai
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header Column Names
fputcsv($output, [
    'ลำดับ', 
    'รหัส (ID)', 
    'คำนำหน้า', 
    'ชื่อ-นามสกุล', 
    'กลุ่มเป้าหมาย', 
    'หน่วยงาน/สถานศึกษา', 
    'เบอร์โทรศัพท์', 
    'อีเมล', 
    'แทร็กปฏิบัติการ', 
    'คะแนน Pre-test', 
    'คะแนน Post-test', 
    'พัฒนาการ (Gain %)', 
    'สถานะการเช็คอิน', 
    'วันที่ลงทะเบียน'
]);

// Fetch Data
$participants = [];
if (class_exists('SQLite3') && file_exists($db_file)) {
    try {
        $db = new SQLite3($db_file);
        $res = $db->query("SELECT * FROM participants ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $participants[] = $row;
        }
    } catch (Exception $e) {}
}

if (empty($participants) && file_exists($json_file)) {
    $participants = json_decode(file_get_contents($json_file), true) ?: [];
    usort($participants, fn($a, $b) => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));
}

$i = 1;
foreach ($participants as $p) {
    $pre = intval($p['pre_score'] ?? 0);
    $post = intval($p['post_score'] ?? 0);
    $max_score = 20; // Full score is 20
    $gain = ($max_score - $pre) > 0 ? round((($post - $pre) / ($max_score - $pre)) * 100, 1) . '%' : '100%';

    fputcsv($output, [
        $i++,
        $p['id'] ?? '',
        $p['prefix'] ?? '',
        $p['fullname'] ?? '',
        $p['target_group'] ?? '',
        $p['organization'] ?? '',
        $p['phone'] ?? '',
        $p['email'] ?? '',
        $p['project_track'] ?? '',
        $pre,
        $post,
        $gain,
        ($p['status'] ?? '') === 'checked-in' ? 'เช็คอินแล้ว' : 'ลงทะเบียนแล้ว',
        $p['created_at'] ?? ''
    ]);
}

fclose($output);
exit();
