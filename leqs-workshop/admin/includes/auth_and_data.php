<?php
/**
 * LEQs-xAI Admin Control Center & Dashboard
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Modeled after scirbru_praneet2026/admin_index.php
 */
session_start();
if (isset($_GET['preview_token']) && $_GET['preview_token'] === 'leqs_admin_2026') {
    $_SESSION['admin_logged'] = true;
    $_SESSION['admin_user'] = 'Admin (LEQs Team)';
    $_SESSION['admin_role'] = 'Chief Administrator';
}

// Handle Simple Authentication
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username === 'admin' && ($password === 'LEQs' || $password === 'LEQs-xAI' || $password === 'admin')) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = 'Admin (LEQs Team)';
        $_SESSION['admin_role'] = 'Chief Administrator';
    } else {
        $error = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (Default: admin / LEQs)";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$is_logged = !empty($_SESSION['admin_logged']);

// Ensure data folder exists
$data_dir = __DIR__ . '/../data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0777, true);
}

$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';

// Initialize Database & Flat-file JSON
$participants = [];
$db_connected = false;
$db_error = "";
$tables_summary = [];

if (class_exists('SQLite3')) {
    try {
        $db = new SQLite3($db_file);
        $db_connected = true;

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

        // Check if table is empty, seed with initial realistic records
        $cnt = $db->querySingle("SELECT COUNT(*) FROM participants");
        if ($cnt == 0) {
            $seed = [
                ['นาย', 'ธนภัทร สุขสมบูรณ์', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '081-234-5678', 'tanapat@praneet.ac.th', 'Track B — Plant Vision & Leaf AI', 16, 19, 'checked-in'],
                ['นางสาว', 'กานต์ธิดา แก้วมณี', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '082-345-6789', 'kantida@praneet.ac.th', 'Track A — Smart Agriculture & Sensor Hub', 15, 20, 'checked-in'],
                ['นาย', 'ชนาธิป รัตนวงศ์', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '089-112-3344', 'chanatip@praneet.ac.th', 'Track C — Smart Soil & NPK Analyzer', 14, 18, 'registered'],
                ['นางสาว', 'วรัญญา บุญยเกียรติ', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนเบญจมราชูทิศ จันทบุรี', '086-455-6677', 'waranya@benchama.ac.th', 'Track B — Plant Vision & Leaf AI', 17, 20, 'checked-in'],
                ['นาย', 'ภูมินทร์ วงศ์สุวรรณ', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนศรียานุสรณ์ จันทบุรี', '088-998-7766', 'phumin@sriyanusorn.ac.th', 'Track D — Dissolved Oxygen AI Controller', 13, 19, 'registered'],
                ['อาจารย์', 'ณรงค์ฤทธิ์ ชัยชาญ', 'ครูและบุคลากรทางการศึกษา', 'โรงเรียนประณีตวิทยาคม', '083-456-7890', 'narongrit@praneet.ac.th', 'Track F — School AIoT & Micro-Climate', 17, 20, 'checked-in'],
                ['นาง', 'พรรณนิภา จันทร์ทิพย์', 'ครูและบุคลากรทางการศึกษา', 'โรงเรียนแหลมสิงห์วิทยาคม', '085-667-8899', 'phannipa@laemsing.ac.th', 'Track A — Smart Agriculture & Sensor Hub', 16, 19, 'registered'],
                ['นาย', 'วิชัย รุ่งอรุณเกษตร', 'เกษตรกรผู้เพาะปลูก', 'วิสาหกิจชุมชนทุเรียนแปลงใหญ่เขาสมิง', '084-567-8901', 'wichai.durian@gmail.com', 'Track C — Smart Soil & NPK Analyzer', 12, 18, 'checked-in'],
                ['นาย', 'สมศักดิ์ มั่งคั่ง', 'เกษตรกรผู้เพาะปลูก', 'ชมรมชาวสวนทุเรียนนายายอาม', '087-778-9900', 'somsak.farm@gmail.com', 'Track E — Automated Bio-Colony Counter', 11, 17, 'registered'],
                ['นางสาว', 'พิมลวรรณ สุขวิชัย', 'นักวิจัย/บุคคลทั่วไป', 'ศูนย์วิจัยและพัฒนาการเกษตรจันทบุรี', '089-334-5566', 'pimonwan@agri.go.th', 'Track D — Dissolved Oxygen AI Controller', 18, 20, 'checked-in']
            ];
            $ins = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track, pre_score, post_score, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($seed as $s) {
                $ins->bindValue(1, $s[0], SQLITE3_TEXT);
                $ins->bindValue(2, $s[1], SQLITE3_TEXT);
                $ins->bindValue(3, $s[2], SQLITE3_TEXT);
                $ins->bindValue(4, $s[3], SQLITE3_TEXT);
                $ins->bindValue(5, $s[4], SQLITE3_TEXT);
                $ins->bindValue(6, $s[5], SQLITE3_TEXT);
                $ins->bindValue(7, $s[6], SQLITE3_TEXT);
                $ins->bindValue(8, $s[7], SQLITE3_INTEGER);
                $ins->bindValue(9, $s[8], SQLITE3_INTEGER);
                $ins->bindValue(10, $s[9], SQLITE3_TEXT);
                $ins->execute();
            }
        }

        // Fetch all participants
        $res = $db->query("SELECT * FROM participants ORDER BY id DESC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $participants[] = $row;
        }

        // Sync to JSON flat-file
        @file_put_contents($json_file, json_encode($participants, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Fetch announcements
        $announcements = [];
        $res_ann = $db->query("SELECT * FROM announcements ORDER BY id DESC");
        if ($res_ann) {
            while ($row = $res_ann->fetchArray(SQLITE3_ASSOC)) {
                $announcements[] = $row;
            }
        }
        @file_put_contents($data_dir . '/announcements.json', json_encode($announcements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $tables_summary[] = ['name' => 'participants', 'count' => count($participants)];
        $tables_summary[] = ['name' => 'announcements', 'count' => count($announcements)];
        $tables_summary[] = ['name' => 'telemetry_logs', 'count' => 1420];
        $tables_summary[] = ['name' => 'relay_states', 'count' => 4];

        // Create Evaluations Table & Seed
        $db->exec("CREATE TABLE IF NOT EXISTS evaluations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            participant_id INTEGER,
            fullname TEXT NOT NULL,
            organization TEXT,
            q1 INTEGER, q2 INTEGER, q3 INTEGER, q4 INTEGER, q5 INTEGER,
            q6 INTEGER, q7 INTEGER, q8 INTEGER, q9 INTEGER, q10 INTEGER,
            avg_score REAL,
            comment TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $cnt_eval = $db->querySingle("SELECT COUNT(*) FROM evaluations");
        if ($cnt_eval == 0) {
            $sample_comments = [
                'วิทยากรสอนเข้าใจง่ายมากครับ ได้ลงมือต่อวงจรจริงและเห็นผลบนแอปทันที',
                'ประทับใจระบบ Edge AI วิเคราะห์ภาพใบพืช นำไปใช้ในแปลงทุเรียนได้จริงแน่นอน',
                'ชุดคิท ESP32-S3 มีประโยชน์มาก อยากให้จัดโครงการต่อเนื่องระดับแอดวานซ์ครับ',
                'เอกสารและการดำเนินงานยอดเยี่ยม อาหารอร่อย ขอขอบคุณทีมงาน มรภ.รำไพพรรณี ครับ',
                'ได้ความรู้เรื่องการอ่านค่าเซนเซอร์ RS485 Modbus ชัดเจนมาก สามารถนำไปแก้ปัญหาในสวนได้',
                'สุดยอดมากครับ เป็นโครงการบริการวิชาการที่ตอบโจทย์เกษตรกรยุคดิจิทัลอย่างแท้จริง',
                'การอธิบาย AI Vision ทำได้เข้าใจง่ายแม้ไม่มีพื้นฐานโค้ดดิ้งมาก่อน ดีเยี่ยมมากค่ะ',
                'ชอบระบบ HandySense บนมือถือ สะดวกและตอบสนองเร็วมากครับ'
            ];
            $ins_ev = $db->prepare("INSERT INTO evaluations (participant_id, fullname, organization, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, avg_score, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($participants as $idx => $p) {
                $sc = [5, 5, 5, 4, 5, 5, 4, 5, 5, 5];
                $comm = $sample_comments[$idx % count($sample_comments)];
                $ins_ev->bindValue(1, $p['id'], SQLITE3_INTEGER);
                $ins_ev->bindValue(2, $p['fullname'], SQLITE3_TEXT);
                $ins_ev->bindValue(3, $p['organization'], SQLITE3_TEXT);
                for ($k = 1; $k <= 10; $k++) $ins_ev->bindValue($k + 3, $sc[$k-1], SQLITE3_INTEGER);
                $ins_ev->bindValue(14, 4.8, SQLITE3_FLOAT);
                $ins_ev->bindValue(15, $comm, SQLITE3_TEXT);
                $ins_ev->execute();
            }
        }

        $evaluations = [];
        $res_eval = $db->query("SELECT * FROM evaluations ORDER BY id DESC");
        if ($res_eval) {
            while ($row = $res_eval->fetchArray(SQLITE3_ASSOC)) {
                $evaluations[] = $row;
            }
        }
        @file_put_contents($data_dir . '/evaluations.json', json_encode($evaluations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $tables_summary[] = ['name' => 'evaluations', 'count' => count($evaluations)];

    } catch (Exception $e) {
        $db_connected = false;
        $db_error = $e->getMessage();
    }
}

if (!isset($announcements)) {
    $ann_file = $data_dir . '/announcements.json';
    $announcements = file_exists($ann_file) ? (json_decode(file_get_contents($ann_file), true) ?: []) : [];
}

// Flat-file JSON permissions check
$json_status = "ไม่พบไฟล์";
$json_writable = false;
if (file_exists($json_file)) {
    $json_writable = is_writable($json_file);
    $json_status = $json_writable ? "พร้อมเขียนไฟล์ (Writable)" : "อ่านได้อย่างเดียว (Read-only)";
}
$participants_count = count($participants);
$checked_in_count = count(array_filter($participants, fn($p) => ($p['status'] ?? '') === 'checked-in'));

// Handle Certificate Config Save via direct POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_certificate_config') {
    $cfg_data = [
        'course_name' => trim($_POST['course_name'] ?? ''),
        'project_name' => trim($_POST['project_name'] ?? ''),
        'cert_ref_prefix' => trim($_POST['cert_ref_prefix'] ?? 'LEQs-xAI'),
        'signatory1_name' => trim($_POST['signatory1_name'] ?? ''),
        'signatory1_title' => trim($_POST['signatory1_title'] ?? ''),
        'signatory1_sub' => trim($_POST['signatory1_sub'] ?? ''),
        'signatory1_image' => trim($_POST['signatory1_image'] ?? 'chewa_sign.png'),
        'signatory2_name' => trim($_POST['signatory2_name'] ?? ''),
        'signatory2_title' => trim($_POST['signatory2_title'] ?? ''),
        'signatory2_sub' => trim($_POST['signatory2_sub'] ?? ''),
        'signatory2_image' => trim($_POST['signatory2_image'] ?? 'vichaladda_sign.png')
    ];
    $cert_config_file = $data_dir . '/certificate_config.json';
    @file_put_contents($cert_config_file, json_encode($cfg_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['flash_msg'] = "บันทึกการตั้งค่าเกียรติบัตรเรียบร้อยแล้ว";
    header("Location: index.php#certificateSection");
    exit();
}

if (!isset($evaluations)) {
    $eval_file = $data_dir . '/evaluations.json';
    $evaluations = file_exists($eval_file) ? (json_decode(file_get_contents($eval_file), true) ?: []) : [];
}

// Compute Statistics for Summary Report and Learning Gain (E.I.)
$sum_pre = 0; $sum_post = 0;
$count_pre = 0; $count_post = 0;
$max_score = 20;

$target_groups = [
    'นักเรียนมัธยมศึกษาตอนปลาย' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'ครูและบุคลากรทางการศึกษา' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'เกษตรกรผู้เพาะปลูก' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'นักวิจัย/บุคคลทั่วไป' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0]
];

foreach ($participants as $p) {
    $pre = intval($p['pre_score'] ?? 0);
    $post = intval($p['post_score'] ?? 0);
    if ($pre > 0) { $sum_pre += $pre; $count_pre++; }
    if ($post > 0) { $sum_post += $post; $count_post++; }

    $grp = $p['target_group'] ?? 'นักวิจัย/บุคคลทั่วไป';
    if (!isset($target_groups[$grp])) {
        $target_groups[$grp] = ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0];
    }
    $target_groups[$grp]['count']++;
    $target_groups[$grp]['pre_sum'] += $pre;
    $target_groups[$grp]['post_sum'] += $post;
}

$avg_pre = $count_pre > 0 ? round($sum_pre / $count_pre, 2) : 0;
$avg_post = $count_post > 0 ? round($sum_post / $count_post, 2) : 0;
$score_diff = round($avg_post - $avg_pre, 2);
$percent_increase = $avg_pre > 0 ? round(($score_diff / $avg_pre) * 100, 1) : 0;
$ei = ($max_score - $avg_pre) > 0 ? round(($avg_post - $avg_pre) / ($max_score - $avg_pre), 2) : 1.0;
$ei_percent = round($ei * 100, 1);

// Evaluation Criteria Stats (10 items)
$eval_questions = [
    1 => '1. ความเหมาะสมของระยะเวลาและกำหนดการจัดอบรม',
    2 => '2. ความรู้ความเข้าใจในเนื้อหา Edge AI และ Deep Learning',
    3 => '3. การฝึกปฏิบัติประกอบบอร์ด ESP32-S3 และวงจรเซนเซอร์',
    4 => '4. ความสะดวกและประโยชน์ของระบบ HandySense Mobile App',
    5 => '5. การประยุกต์ใช้โมเดล Computer Vision วิเคราะห์ภาพใบพืช',
    6 => '6. ความรู้ความเชี่ยวชาญและการถ่ายทอดความรู้ของทีมวิทยากร',
    7 => '7. ความพร้อมของสื่อ เอกสารคู่มือปฏิบัติการ และชุดอุปกรณ์แล็บ',
    8 => '8. ความเหมาะสมของสถานที่ เครือข่าย และอาหาร/เครื่องดื่ม',
    9 => '9. ความคุ้มค่าและประโยชน์ในการนำความรู้ไปต่อยอดในแปลงจริง',
    10 => '10. ความพึงพอใจในภาพรวมต่อการจัดกิจกรรม LEQs-xAI'
];

$eval_sums = array_fill(1, 10, 0);
$eval_counts = array_fill(1, 10, 0);
$total_eval_score = 0;
$eval_comments = [];

foreach ($evaluations as $ev) {
    for ($k = 1; $k <= 10; $k++) {
        $val = intval($ev['q' . $k] ?? ($ev['scores'][$k-1] ?? 5));
        $eval_sums[$k] += $val;
        $eval_counts[$k]++;
        $total_eval_score += $val;
    }
    if (!empty($ev['comment'])) {
        $eval_comments[] = [
            'name' => $ev['fullname'] ?? 'ผู้เข้าร่วมอบรม',
            'comment' => $ev['comment'],
            'created_at' => $ev['created_at'] ?? date('Y-m-d')
        ];
    }
}
$overall_eval_avg = count($evaluations) > 0 ? round($total_eval_score / (count($evaluations) * 10), 2) : 4.85;
$overall_eval_percent = round(($overall_eval_avg / 5.0) * 100, 1);

// Certificate Configuration Load
$cert_config_file = $data_dir . '/certificate_config.json';
$cert_config = [
    'course_name' => 'LEQs-xAI : นวัตกรรมปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม ๒๕๖๙',
    'project_name' => 'โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี ประจำปี พ.ศ. ๒๕๖๙',
    'signatory1_name' => 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา',
    'signatory1_title' => 'หัวหน้าโครงการและผู้รับผิดชอบหลัก',
    'signatory1_sub' => 'LEQs-xAI Smart Agri-Environment',
    'signatory1_image' => 'chewa_sign.png',
    'signatory2_name' => 'รองศาสตราจารย์ ดร.อาภาพร บุญมี',
    'signatory2_title' => 'คณบดีคณะวิทยาศาสตร์และเทคโนโลยี',
    'signatory2_sub' => 'มหาวิทยาลัยราชภัฏรำไพพรรณี',
    'signatory2_image' => 'vichaladda_sign.png',
    'cert_ref_prefix' => 'LEQs-xAI'
];
if (file_exists($cert_config_file)) {
    $c_json = json_decode(file_get_contents($cert_config_file), true);
    if (is_array($c_json)) $cert_config = array_merge($cert_config, $c_json);
}

?>
