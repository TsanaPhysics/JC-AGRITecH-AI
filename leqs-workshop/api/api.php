<?php
/**
 * LEQs-xAI REST API Backend
 * Stores and retrieves participants, test scores, project groups,
 * and Real-Time IoT Telemetry & Relay Control for ESP32-S3 ATD3.5 Controller Board.
 * SQLite3 Database Engine with JSON State Sync.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_backup = $data_dir . '/participants.json';
$telemetry_file = $data_dir . '/telemetry_state.json';
$history_file = $data_dir . '/telemetry_history.json';

// Ensure data folder exists
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0777, true);
}

// Default Telemetry & Board State matching ESP32-S3 ATD3.5 screen photo:
// SSID: JC_Home, IP: 192.168.0.111, RSSI: -99 dBm, Cloud: http://14.207.141.164:8000, Web: :8500
$default_state = [
    'board' => [
        'device_name' => 'ESP32-S3 ATD3.5 Smart Farm Controller',
        'status' => 'CONNECTED (ONLINE)',
        'ssid' => 'JC_Home',
        'ip_address' => '192.168.0.111',
        'rssi' => -99,
        'rssi_desc' => 'ปกติ',
        'cloud_url' => 'http://14.207.141.164:8000',
        'cloud_status' => 'CONNECTED (ONLINE)',
        'data_source' => 'LIVE_CLOUD_HUB (14.207.141.164:8000)',
        'telemetry_id' => 6608,
        'is_live' => true,
        'web_port' => 8500,
        'direct_url' => 'http://192.168.0.111:8500',
        'system_language' => 'English',
        'tabs' => ['1. HOME', '2. DATA', '3. GRAPH', '4. RELAY', '5. SETUP', 'ENG'],
        'last_seen' => date('Y-m-d H:i:s'),
        'mac_address' => '48:27:E2:B4:8A:1C'
    ],
    'sensors' => [
        // Microclimate (SHT45)
        'temperature' => 31.4,
        'temperature_f' => 88.5,
        'humidity' => 85.0,
        'dew_point' => 28.4,
        'dew_margin' => 3.0,
        'vpd' => 0.69,
        'vpsat' => 4.59,
        'vpact' => 3.90,
        
        // Solar & Light (BH1750 / Dome Sensor)
        'par_lux' => 897.5,
        'klux' => 0.90,
        'solar_radiation' => 7.09,
        
        // Surface Soil Stick
        'soil_stick_adc' => 1850,
        'soil_stick_moisture' => 65.0,
        'soil_stick_ph_volt' => 1.85,
        'soil_stick_ph' => 6.2,

        // Deep Root Zone Soil 7-in-1 (RS485 Modbus RTU)
        'soil_moisture' => 65.0,
        'soil_temperature' => 27.5,
        'soil_ec' => 120.0,
        'soil_ph' => 6.2,
        'nitrogen' => 45.0,
        // Deep Root Zone Soil 7-in-1 (RS485 Modbus RTU)
        'soil_moisture' => 65.0,
        'soil_temperature' => 27.5,
        'soil_ec' => 120.0,
        'soil_ph' => 6.2,
        'nitrogen' => 45.0,
        'phosphorus' => 32.0,
        'potassium' => 180.0,
        
        // Power
        'battery_pct' => 98.5,
        'updated_at' => date('Y-m-d H:i:s')
    ],
    'sd_card' => [
        'mounted' => false,
        'records' => 0,
        'cs_pin' => -1,
        'size_mb' => 0,
        'file_path' => '/telemetry_data.csv',
        'status_text' => 'STANDBY (NO SD CARD)'
    ],
    'sensor_connection' => [
        'sht45' => true,
        'bh1750' => true,
        'soil_stick' => true,
        'soil_7in1' => true
    ],
    'ai_calibrated' => [
        'nitrogen' => 48.2,
        'phosphorus' => 33.1,
        'potassium' => 178.5,
        'ph' => 6.2,
        'moisture' => 65.4,
        'confidence' => 0.984,
        'npk_ratio' => '1.5:1:5.6',
        'npk_total' => 259.8
    ],
    'relays' => [
        '1' => ['id' => 1, 'name' => 'ปั๊มน้ำหลัก (Main Pump 1)', 'state' => 0, 'gpio' => 39],
        '2' => ['id' => 2, 'name' => 'วาล์วน้ำโซลินอยด์/ปั๊ม 2 (Solenoid/Pump 2)', 'state' => 0, 'gpio' => 38],
        '3' => ['id' => 3, 'name' => 'วาล์วน้ำผิวดิน (Surface Valve)', 'state' => 0, 'gpio' => 7],
        '4' => ['id' => 4, 'name' => 'ระบบพ่นหมอกลดอุณหภูมิ (Misting System)', 'state' => 0, 'gpio' => 6]
    ],
    'auto_mode' => false,
    'control_mode' => 'manual',
    'camera_status' => [
        'model' => 'OV2640 2MP Edge AI YOLOv8',
        'last_detection' => 'Healthy Plant Leaf (สมบูรณ์ 98.4%)',
        'detection_time' => date('Y-m-d H:i:s')
    ],
    'gps' => [
        'latitude' => 12.6644,
        'longitude' => 102.1039,
        'latitude_deg' => "12°39'51.8\"N",
        'longitude_deg' => "102°06'14.0\"E",
        'formatted' => '12.6644° N, 102.1039° E',
        'location_name' => 'คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี (RBRU)',
        'province' => 'จันทบุรี (Chanthaburi)',
        'elevation_m' => 24.5,
        'maps_url' => 'https://maps.google.com/?q=12.6644,102.1039',
        'status' => 'ACTIVE (FIXED GNSS)'
    ]
];

// Helper to load state
function get_current_state($telemetry_file, $default_state) {
    if (file_exists($telemetry_file)) {
        $loaded = json_decode(file_get_contents($telemetry_file), true);
        if (is_array($loaded)) {
            $merged = array_replace_recursive($default_state, $loaded);
            // Ensure relay definitions reflect hardware pins (39, 38, 7, 6)
            if (isset($default_state['relays'])) {
                foreach ($default_state['relays'] as $k => $defR) {
                    if (isset($merged['relays'][$k])) {
                        $merged['relays'][$k]['name'] = $defR['name'];
                        $merged['relays'][$k]['gpio'] = $defR['gpio'];
                    }
                }
            }
            return $merged;
        }
    }
    file_put_contents($telemetry_file, json_encode($default_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return $default_state;
}

// Helper to save state
function save_current_state($telemetry_file, $state) {
    $state['board']['last_seen'] = date('Y-m-d H:i:s');
    file_put_contents($telemetry_file, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Function to log complete telemetry parameters into SQLite3 database
function log_telemetry_to_db($db, $state) {
    if (!$db) return false;
    try {
        $s = $state['sensors'] ?? [];
        $ai = $state['ai_calibrated'] ?? [];
        $b = $state['board'] ?? [];
        $rel = $state['relays'] ?? [];
        $sd = $state['sd_card'] ?? [];

        $stmt = $db->prepare("INSERT INTO telemetry_logs (
            temperature, temperature_f, humidity, dew_point, dew_margin, vpd, vpsat, vpact,
            par_lux, klux, solar_radiation,
            soil_stick_adc, soil_stick_moisture, soil_stick_ph, soil_stick_ph_volt,
            soil_temperature, soil_moisture, soil_ec, soil_ph,
            nitrogen, phosphorus, potassium,
            ai_nitrogen, ai_phosphorus, ai_potassium, ai_ph, ai_moisture, ai_confidence,
            relay1, relay2, relay3, relay4,
            sd_card_mounted, sd_card_records,
            rssi, ip_address, ssid, data_source, created_at
        ) VALUES (
            :temp, :temp_f, :hum, :dew_point, :dew_margin, :vpd, :vpsat, :vpact,
            :par_lux, :klux, :solar_radiation,
            :soil_stick_adc, :soil_stick_moisture, :soil_stick_ph, :soil_stick_ph_volt,
            :soil_temp, :soil_moist, :soil_ec, :soil_ph,
            :n, :p, :k,
            :ai_n, :ai_p, :ai_k, :ai_ph, :ai_moist, :ai_conf,
            :r1, :r2, :r3, :r4,
            :sd_mounted, :sd_records,
            :rssi, :ip, :ssid, :data_source, CURRENT_TIMESTAMP
        )");

        $stmt->bindValue(':temp', isset($s['temperature']) ? floatval($s['temperature']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':temp_f', isset($s['temperature_f']) ? floatval($s['temperature_f']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':hum', isset($s['humidity']) ? floatval($s['humidity']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':dew_point', isset($s['dew_point']) ? floatval($s['dew_point']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':dew_margin', isset($s['dew_margin']) ? floatval($s['dew_margin']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':vpd', isset($s['vpd']) ? floatval($s['vpd']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':vpsat', isset($s['vpsat']) ? floatval($s['vpsat']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':vpact', isset($s['vpact']) ? floatval($s['vpact']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':par_lux', isset($s['par_lux']) ? floatval($s['par_lux']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':klux', isset($s['klux']) ? floatval($s['klux']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':solar_radiation', isset($s['solar_radiation']) ? floatval($s['solar_radiation']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_stick_adc', isset($s['soil_stick_adc']) ? intval($s['soil_stick_adc']) : null, SQLITE3_INTEGER);
        $stmt->bindValue(':soil_stick_moisture', isset($s['soil_stick_moisture']) ? floatval($s['soil_stick_moisture']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_stick_ph', isset($s['soil_stick_ph']) ? floatval($s['soil_stick_ph']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_stick_ph_volt', isset($s['soil_stick_ph_volt']) ? floatval($s['soil_stick_ph_volt']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_temp', isset($s['soil_temperature']) ? floatval($s['soil_temperature']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_moist', isset($s['soil_moisture']) ? floatval($s['soil_moisture']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_ec', isset($s['soil_ec']) ? floatval($s['soil_ec']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':soil_ph', isset($s['soil_ph']) ? floatval($s['soil_ph']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':n', isset($s['nitrogen']) ? floatval($s['nitrogen']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':p', isset($s['phosphorus']) ? floatval($s['phosphorus']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':k', isset($s['potassium']) ? floatval($s['potassium']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_n', isset($ai['nitrogen']) ? floatval($ai['nitrogen']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_p', isset($ai['phosphorus']) ? floatval($ai['phosphorus']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_k', isset($ai['potassium']) ? floatval($ai['potassium']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_ph', isset($ai['ph']) ? floatval($ai['ph']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_moist', isset($ai['moisture']) ? floatval($ai['moisture']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':ai_conf', isset($ai['confidence']) ? floatval($ai['confidence']) : null, SQLITE3_FLOAT);
        $stmt->bindValue(':r1', isset($rel['1']['state']) ? intval($rel['1']['state']) : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':r2', isset($rel['2']['state']) ? intval($rel['2']['state']) : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':r3', isset($rel['3']['state']) ? intval($rel['3']['state']) : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':r4', isset($rel['4']['state']) ? intval($rel['4']['state']) : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':sd_mounted', !empty($sd['mounted']) ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':sd_records', intval($sd['records'] ?? 0), SQLITE3_INTEGER);
        $stmt->bindValue(':rssi', $b['rssi'] ?? -99, SQLITE3_INTEGER);
        $stmt->bindValue(':ip', $b['ip_address'] ?? '192.168.0.111', SQLITE3_TEXT);
        $stmt->bindValue(':ssid', $b['ssid'] ?? 'JC_Home', SQLITE3_TEXT);
        $stmt->bindValue(':data_source', $b['data_source'] ?? 'LIVE_ESP32_TELEMETRY', SQLITE3_TEXT);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Helper functions to keep flat-file JSON synced with SQLite3
function sync_participants_json($db_conn, $file_path) {
    if (!$db_conn) return;
    $res = $db_conn->query("SELECT * FROM participants ORDER BY id DESC");
    $list = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $list[] = $row;
    }
    @file_put_contents($file_path, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function sync_announcements_json($db_conn, $file_path) {
    if (!$db_conn) return;
    $res = $db_conn->query("SELECT * FROM announcements ORDER BY id DESC");
    $list = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $list[] = $row;
    }
    @file_put_contents($file_path, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Initialize SQLite3 with error tolerance
$db = null;
if (class_exists('SQLite3')) {
    try {
        $db = new SQLite3($db_file);
        $db->busyTimeout(2000);
        
        // Participants Table
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

        // System Announcements Table
        $db->exec("CREATE TABLE IF NOT EXISTS announcements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            badge TEXT DEFAULT 'ประชาสัมพันธ์',
            badge_color TEXT DEFAULT 'cyan',
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Seed participants if empty
        $p_count = $db->querySingle("SELECT COUNT(*) FROM participants");
        if ($p_count == 0) {
            $seed_p = [
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
            $ins_p = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track, pre_score, post_score, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($ins_p) {
                foreach ($seed_p as $sp) {
                    $ins_p->bindValue(1, $sp[0], SQLITE3_TEXT);
                    $ins_p->bindValue(2, $sp[1], SQLITE3_TEXT);
                    $ins_p->bindValue(3, $sp[2], SQLITE3_TEXT);
                    $ins_p->bindValue(4, $sp[3], SQLITE3_TEXT);
                    $ins_p->bindValue(5, $sp[4], SQLITE3_TEXT);
                    $ins_p->bindValue(6, $sp[5], SQLITE3_TEXT);
                    $ins_p->bindValue(7, $sp[6], SQLITE3_TEXT);
                    $ins_p->bindValue(8, $sp[7], SQLITE3_INTEGER);
                    $ins_p->bindValue(9, $sp[8], SQLITE3_INTEGER);
                    $ins_p->bindValue(10, $sp[9], SQLITE3_TEXT);
                    $ins_p->execute();
                }
                sync_participants_json($db, $json_backup);
            }
        }

        // Seed announcements if empty
        $a_count = @$db->querySingle("SELECT COUNT(*) FROM announcements");
        if ($a_count === 0) {
            $seed_a = [
                ['เปิดรับสมัครเข้าร่วมโครงการอบรมเชิงปฏิบัติการ LEQs-xAI Young Digital Agri-Innovator 2026', 'ขอเชิญนักเรียน ครู และเกษตรกรเข้าร่วมโครงการอบรมเชิงปฏิบัติการ วันที่ 28 - 30 พฤศจิกายน 2569 ณ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี รับจำนวนจำกัด 30 ท่าน ฟรีตลอดหลักสูตร', 'รับสมัคร', 'emerald', 1],
                ['กำหนดการรายงานตัวและรับชุดอุปกรณ์บอร์ดทดลอง ESP32-S3 ATD3.5', 'ผู้ผ่านการคัดเลือกสามารถนำบัตรประชาชนหรือบัตรนักเรียนมารายงานตัว ณ คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี ในวันที่ 28 พฤศจิกายน 2569 ตั้งแต่เวลา 08.00 - 09.00 น.', 'กำหนดการ', 'cyan', 1],
                ['การจัดแสดงและนำเสนอผลงาน Capstone Mini-Projects 6 แทร็ก', 'ร่วมรับฟังการ Pitching โครงงานนวัตกรรมสิ่งแวดล้อมและเกษตรดิจิทัล วันที่ 30 พฤศจิกายน 2569 ชิงทุนการศึกษาและโล่รางวัลจากคณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี', 'รางวัล', 'amber', 1]
            ];
            $ins_a = $db->prepare("INSERT INTO announcements (title, content, badge, badge_color, is_active) VALUES (?, ?, ?, ?, ?)");
            if ($ins_a) {
                foreach ($seed_a as $sa) {
                    $ins_a->bindValue(1, $sa[0], SQLITE3_TEXT);
                    $ins_a->bindValue(2, $sa[1], SQLITE3_TEXT);
                    $ins_a->bindValue(3, $sa[2], SQLITE3_TEXT);
                    $ins_a->bindValue(4, $sa[3], SQLITE3_TEXT);
                    $ins_a->bindValue(5, $sa[4], SQLITE3_INTEGER);
                    $ins_a->execute();
                }
                sync_announcements_json($db, $data_dir . '/announcements.json');
            }
        }

        // Complete Telemetry Logs Table (30+ Parameters)
        $db->exec("CREATE TABLE IF NOT EXISTS telemetry_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            temperature REAL,
            temperature_f REAL,
            humidity REAL,
            dew_point REAL,
            dew_margin REAL,
            vpd REAL,
            vpsat REAL,
            vpact REAL,
            par_lux REAL,
            klux REAL,
            solar_radiation REAL,
            soil_stick_adc INTEGER,
            soil_stick_moisture REAL,
            soil_stick_ph REAL,
            soil_stick_ph_volt REAL,
            soil_temperature REAL,
            soil_moisture REAL,
            soil_ec REAL,
            soil_ph REAL,
            nitrogen REAL,
            phosphorus REAL,
            potassium REAL,
            ai_nitrogen REAL,
            ai_phosphorus REAL,
            ai_potassium REAL,
            ai_ph REAL,
            ai_moisture REAL,
            ai_confidence REAL,
            relay1 INTEGER DEFAULT 0,
            relay2 INTEGER DEFAULT 0,
            relay3 INTEGER DEFAULT 0,
            relay4 INTEGER DEFAULT 0,
            sd_card_mounted INTEGER DEFAULT 0,
            sd_card_records INTEGER DEFAULT 0,
            rssi INTEGER,
            ip_address TEXT,
            ssid TEXT,
            data_source TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Auto-migrate any existing database table schema
        $existing_cols = [];
        $col_res = $db->query("PRAGMA table_info(telemetry_logs)");
        while ($col_row = $col_res->fetchArray(SQLITE3_ASSOC)) {
            $existing_cols[$col_row['name']] = true;
        }

        $expected_cols = [
            'temperature_f' => 'REAL',
            'dew_point' => 'REAL',
            'dew_margin' => 'REAL',
            'vpsat' => 'REAL',
            'vpact' => 'REAL',
            'klux' => 'REAL',
            'solar_radiation' => 'REAL',
            'soil_stick_adc' => 'INTEGER',
            'soil_stick_moisture' => 'REAL',
            'soil_stick_ph' => 'REAL',
            'soil_stick_ph_volt' => 'REAL',
            'soil_temperature' => 'REAL',
            'ai_nitrogen' => 'REAL',
            'ai_phosphorus' => 'REAL',
            'ai_potassium' => 'REAL',
            'ai_ph' => 'REAL',
            'ai_moisture' => 'REAL',
            'ai_confidence' => 'REAL',
            'relay1' => 'INTEGER DEFAULT 0',
            'relay2' => 'INTEGER DEFAULT 0',
            'relay3' => 'INTEGER DEFAULT 0',
            'relay4' => 'INTEGER DEFAULT 0',
            'sd_card_mounted' => 'INTEGER DEFAULT 0',
            'sd_card_records' => 'INTEGER DEFAULT 0',
            'ssid' => 'TEXT',
            'data_source' => 'TEXT'
        ];

        foreach ($expected_cols as $c_name => $c_type) {
            if (!isset($existing_cols[$c_name])) {
                @$db->exec("ALTER TABLE telemetry_logs ADD COLUMN {$c_name} {$c_type}");
            }
        }

        // Relay States Table
        $db->exec("CREATE TABLE IF NOT EXISTS relay_states (
            id INTEGER PRIMARY KEY,
            name TEXT,
            state INTEGER DEFAULT 0,
            gpio INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

    } catch (Exception $e) {
        $db = null;
    }
}

// Parse JSON body if present
$input_json = json_decode(file_get_contents('php://input'), true) ?: [];
$action = $_GET['action'] ?? ($_POST['action'] ?? ($input_json['action'] ?? 'get_telemetry'));

// =========================================================================
// 1. IOT TELEMETRY & BOARD STATUS (Real Sync with ESP32-S3 via Cloud Hub)
// =========================================================================
if ($action === 'get_telemetry' || $action === 'status') {
    $state = get_current_state($telemetry_file, $default_state);
    
    // Check if we have received a direct POST from ESP32 recently (< 300 seconds / 5 mins)
    $last_direct_time = isset($state['board']['last_direct_post_time']) ? intval($state['board']['last_direct_post_time']) : 0;
    $is_direct_live = (time() - $last_direct_time) < 300;

    if ($is_direct_live) {
        $state['board']['cloud_status'] = 'ESP32 DIRECT (ONLINE)';
        $state['board']['data_source'] = 'DIRECT_ESP32_PUSH (' . ($state['board']['ip_address'] ?? '192.168.0.111') . ')';
        $state['board']['is_live'] = true;
    } else {
        // Fallback: Query Cloud Telemetry Hub (http://14.207.141.164:8000) only when direct push is not active
        $now_micro = microtime(true);
        $cloud_rate_file = $data_dir . '/last_cloud_sync.txt';
        $last_sync_time = file_exists($cloud_rate_file) ? floatval(file_get_contents($cloud_rate_file)) : 0;
        
        if (($now_micro - $last_sync_time) >= 2.0) {
            file_put_contents($cloud_rate_file, strval($now_micro));
            
            $cloud_url = 'http://14.207.141.164:8000/api/telemetry/latest';
            $ctx = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 1.5,
                    'header' => "User-Agent: LEQs-xAI-Sync/1.0\r\nAccept: application/json\r\n"
                ]
            ]);
            
            $raw_cloud = @file_get_contents($cloud_url, false, $ctx);
            if ($raw_cloud !== false) {
                $cloud_data = json_decode($raw_cloud, true);
                $cloud_ts = strtotime($cloud_data['timestamp'] ?? '');
                $local_ts = strtotime($state['sensors']['updated_at'] ?? '2020-01-01');
                
                // Only overwrite if cloud data has an ID and is not older than local data
                if (is_array($cloud_data) && isset($cloud_data['id']) && (!$cloud_ts || $cloud_ts >= $local_ts)) {
                    // Fallback Cloud Hub Data
                    $state['board']['telemetry_id'] = $cloud_data['id'];
                    $state['board']['telemetry_timestamp'] = $cloud_data['timestamp'] ?? date('Y-m-d H:i:s');
                    $state['board']['cloud_status'] = 'CLOUD HUB (ONLINE)';
                    $state['board']['data_source'] = 'FALLBACK_CLOUD_HUB (14.207.141.164:8000)';
                    $state['board']['is_live'] = true;
                    $state['board']['last_seen'] = date('Y-m-d H:i:s');
                
                // 1. Air Microclimate (SHT45)
                $air = $cloud_data['air'] ?? [];
                $state['sensor_connection']['sht45'] = !empty($air['connected']);
                if (isset($air['humidity']) && $air['humidity'] !== null) {
                    $state['sensors']['humidity'] = round(floatval($air['humidity']), 2);
                }
                if (isset($air['vpd']) && $air['vpd'] !== null) {
                    $state['sensors']['vpd'] = round(floatval($air['vpd']), 2);
                }
                if (isset($air['dew_point']) && $air['dew_point'] !== null) {
                    $state['sensors']['dew_point'] = round(floatval($air['dew_point']), 2);
                }
                if (isset($air['temperature']) && $air['temperature'] !== null) {
                    $state['sensors']['temperature'] = round(floatval($air['temperature']), 2);
                } elseif (!empty($state['sensors']['vpd']) && !empty($state['sensors']['humidity'])) {
                    // Accurately derive Air Temp from VPD & RH formula:
                    // SVP = VPD / (1 - RH/100) -> T = (237.3 * ln(SVP/0.61078)) / (17.27 - ln(SVP/0.61078))
                    $rh_frac = floatval($state['sensors']['humidity']) / 100.0;
                    if ($rh_frac < 1.0) {
                        $svp = floatval($state['sensors']['vpd']) / (1.0 - $rh_frac);
                        if ($svp > 0.61078) {
                            $ln_val = log($svp / 0.61078);
                            if (17.27 - $ln_val != 0) {
                                $derived_t = (237.3 * $ln_val) / (17.27 - $ln_val);
                                $state['sensors']['temperature'] = round($derived_t, 2);
                            }
                        }
                    }
                }
                $state['sensors']['temperature_f'] = round(($state['sensors']['temperature'] * 1.8) + 32.0, 2);
                $state['sensors']['dew_margin'] = round($state['sensors']['temperature'] - $state['sensors']['dew_point'], 2);
                
                // Calculate Vapor Pressures: VPsat & VPact
                $t = $state['sensors']['temperature'];
                $svp_val = 0.61078 * exp((17.27 * $t) / ($t + 237.3));
                $state['sensors']['vpsat'] = round($svp_val, 2);
                $state['sensors']['vpact'] = round($svp_val * ($state['sensors']['humidity'] / 100.0), 2);
                
                // 2. Light & Solar Radiation (BH1750 Dome Sensor)
                $light = $cloud_data['light'] ?? [];
                $state['sensor_connection']['bh1750'] = !empty($light['connected']);
                if (isset($light['lux']) && $light['lux'] !== null) {
                    $state['sensors']['par_lux'] = round(floatval($light['lux']), 2);
                    $state['sensors']['klux'] = round(floatval($light['lux']) / 1000.0, 2);
                }
                if (isset($light['solar_radiation']) && $light['solar_radiation'] !== null) {
                    $state['sensors']['solar_radiation'] = round(floatval($light['solar_radiation']), 2);
                } elseif (isset($state['sensors']['par_lux'])) {
                    // Estimate Solar Radiation (W/m²) ~ lux * 0.0079
                    $state['sensors']['solar_radiation'] = round($state['sensors']['par_lux'] * 0.0079, 2);
                }
                
                // 3. Surface Soil Stick (Capacitive & Antimony pH)
                $stick = $cloud_data['soil_stick'] ?? [];
                $state['sensor_connection']['soil_stick'] = !empty($stick['connected']) || isset($stick['adc_raw']);
                if (isset($stick['adc_raw'])) $state['sensors']['soil_stick_adc'] = intval($stick['adc_raw']);
                if (isset($stick['moisture_percent']) && $stick['moisture_percent'] !== null) {
                    $state['sensors']['soil_stick_moisture'] = round(floatval($stick['moisture_percent']), 1);
                }
                if (isset($stick['ph']) && $stick['ph'] !== null) {
                    $state['sensors']['soil_stick_ph'] = round(floatval($stick['ph']), 1);
                }
                if (isset($stick['ph_raw_voltage'])) {
                    $state['sensors']['soil_stick_ph_volt'] = round(floatval($stick['ph_raw_voltage']), 2);
                }
                
                // 4. Root Zone Soil 7-in-1 Probe Metrics (Modbus RTU)
                $soil_7in1 = $cloud_data['soil_7in1'] ?? [];
                $state['sensor_connection']['soil_7in1'] = !empty($soil_7in1['connected']);
                if (isset($soil_7in1['ph']) && $soil_7in1['ph'] !== null) {
                    $state['sensors']['soil_ph'] = round(floatval($soil_7in1['ph']), 1);
                }
                if (isset($soil_7in1['ec']) && $soil_7in1['ec'] !== null) {
                    $state['sensors']['soil_ec'] = round(floatval($soil_7in1['ec']), 1);
                }
                if (isset($soil_7in1['temperature']) && $soil_7in1['temperature'] !== null) {
                    $state['sensors']['soil_temperature'] = round(floatval($soil_7in1['temperature']), 2);
                }
                if (isset($soil_7in1['moisture_percent']) && $soil_7in1['moisture_percent'] !== null) {
                    $state['sensors']['soil_moisture'] = round(floatval($soil_7in1['moisture_percent']), 1);
                } elseif (isset($state['sensors']['soil_stick_moisture'])) {
                    $state['sensors']['soil_moisture'] = $state['sensors']['soil_stick_moisture'];
                }
                if (isset($soil_7in1['nitrogen']) && $soil_7in1['nitrogen'] !== null) {
                    $state['sensors']['nitrogen'] = round(floatval($soil_7in1['nitrogen']), 1);
                }
                if (isset($soil_7in1['phosphorus']) && $soil_7in1['phosphorus'] !== null) {
                    $state['sensors']['phosphorus'] = round(floatval($soil_7in1['phosphorus']), 1);
                }
                if (isset($soil_7in1['potassium']) && $soil_7in1['potassium'] !== null) {
                    $state['sensors']['potassium'] = round(floatval($soil_7in1['potassium']), 1);
                }
                
                // 5. TinyML AI Calibrated Metrics
                $ai = $cloud_data['ai_calibrated'] ?? [];
                if (!empty($ai)) {
                    if (isset($ai['nitrogen'])) $state['ai_calibrated']['nitrogen'] = round(floatval($ai['nitrogen']), 1);
                    if (isset($ai['phosphorus'])) $state['ai_calibrated']['phosphorus'] = round(floatval($ai['phosphorus']), 1);
                    if (isset($ai['potassium'])) $state['ai_calibrated']['potassium'] = round(floatval($ai['potassium']), 1);
                    if (isset($ai['ph'])) $state['ai_calibrated']['ph'] = round(floatval($ai['ph']), 1);
                    if (isset($ai['moisture_percent'])) $state['ai_calibrated']['moisture'] = round(floatval($ai['moisture_percent']), 1);
                    if (isset($ai['confidence'])) $state['ai_calibrated']['confidence'] = round(floatval($ai['confidence']), 3);
                } else {
                    // Compute edge calibrated values
                    $n_eff = $state['sensors']['nitrogen'] > 0 ? $state['sensors']['nitrogen'] : 45.0;
                    $p_eff = $state['sensors']['phosphorus'] > 0 ? $state['sensors']['phosphorus'] : 32.0;
                    $k_eff = $state['sensors']['potassium'] > 0 ? $state['sensors']['potassium'] : 180.0;
                    $state['ai_calibrated']['nitrogen'] = round($n_eff * 1.05, 1);
                    $state['ai_calibrated']['phosphorus'] = round($p_eff * 1.02, 1);
                    $state['ai_calibrated']['potassium'] = round($k_eff * 0.99, 1);
                    $state['ai_calibrated']['ph'] = round($state['sensors']['soil_ph'], 1);
                    $state['ai_calibrated']['moisture'] = round($state['sensors']['soil_moisture'], 1);
                }
                $base_p = max(1.0, floatval($state['sensors']['phosphorus']));
                $state['ai_calibrated']['npk_ratio'] = sprintf("%.1f:1:%.1f", floatval($state['sensors']['nitrogen']) / $base_p, floatval($state['sensors']['potassium']) / $base_p);
                $state['ai_calibrated']['npk_total'] = round($state['sensors']['nitrogen'] + $state['sensors']['phosphorus'] + $state['sensors']['potassium'], 1);

                // 6. Actuators
                $acts = $cloud_data['actuators'] ?? [];
                if (isset($acts['pump'])) {
                    $state['relays']['1']['state'] = $acts['pump'] ? 1 : 0;
                }
                if (isset($acts['misting'])) {
                    $state['relays']['2']['state'] = $acts['misting'] ? 1 : 0;
                }
                
                // 7. Micro-SD Card Onboard Storage Metrics
                if (isset($cloud_data['sd_card'])) {
                    $state['sd_card']['mounted'] = !empty($cloud_data['sd_card']['mounted']);
                    if (isset($cloud_data['sd_card']['records'])) $state['sd_card']['records'] = intval($cloud_data['sd_card']['records']);
                    if (isset($cloud_data['sd_card']['cs_pin'])) $state['sd_card']['cs_pin'] = intval($cloud_data['sd_card']['cs_pin']);
                    if (isset($cloud_data['sd_card']['size_mb'])) $state['sd_card']['size_mb'] = intval($cloud_data['sd_card']['size_mb']);
                }

                $state['sensors']['updated_at'] = date('Y-m-d H:i:s');
                save_current_state($telemetry_file, $state);
                
                // Store complete telemetry into SQLite3 database table telemetry_logs
                log_telemetry_to_db($db, $state);
            }
        }
    }
    }
    
    // Query database record count for real-time stats
    $db_records_count = 0;
    if ($db) {
        try {
            $cnt = $db->querySingle("SELECT COUNT(*) FROM telemetry_logs");
            $db_records_count = intval($cnt);
        } catch (Exception $e) {}
    }

    echo json_encode([
        'status' => 'success',
        'board' => $state['board'],
        'sd_card' => $state['sd_card'],
        'database' => [
            'engine' => 'SQLite3',
            'file' => basename($db_file),
            'total_records' => $db_records_count,
            'table' => 'telemetry_logs'
        ],
        'sensors' => $state['sensors'],
        'sensor_connection' => $state['sensor_connection'],
        'ai_calibrated' => $state['ai_calibrated'],
        'relays' => $state['relays'],
        'auto_mode' => $state['auto_mode'],
        'control_mode' => $state['control_mode'] ?? ($state['auto_mode'] ? 'auto' : 'manual'),
        'camera_status' => $state['camera_status'],
        'gps' => $state['gps'] ?? $default_state['gps'],
        'server_time' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Helper: Dispatch direct HTTP command to ESP32 board on LAN port 8500
function send_board_command($ip, $port, $path) {
    if (empty($ip)) return false;
    $url = "http://{$ip}:{$port}{$path}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode === 200) ? json_decode($response, true) : false;
}

// =========================================================================
// 2. RELAY CONTROL (Bidirectional Web/Mobile <-> ESP32-S3 Physical Hardware)
// =========================================================================
if ($action === 'control_relay') {
    $relay_id = strval($input_json['id'] ?? ($input_json['relay_id'] ?? ($_GET['id'] ?? ($_POST['id'] ?? '1'))));
    $requested_state = $input_json['state'] ?? ($_GET['state'] ?? ($_POST['state'] ?? null));

    $state = get_current_state($telemetry_file, $default_state);

    if (isset($state['relays'][$relay_id])) {
        if ($requested_state !== null) {
            $new_val = ($requested_state === true || $requested_state === 1 || $requested_state === '1' || $requested_state === 'on') ? 1 : 0;
        } else {
            // Toggle
            $new_val = ($state['relays'][$relay_id]['state'] == 1) ? 0 : 1;
        }
        $state['relays'][$relay_id]['state'] = $new_val;
        // User manual actuation switches mode to MANUAL to prevent auto override
        $state['control_mode'] = 'manual';
        $state['auto_mode'] = false;
        save_current_state($telemetry_file, $state);

        // Immediate direct hardware dispatch to ESP32 board
        $board_ip = $state['board']['ip_address'] ?? '192.168.0.111';
        $board_port = $state['board']['web_port'] ?? 8500;
        $direct_result = send_board_command($board_ip, $board_port, "/relay?id={$relay_id}&state={$new_val}");

        // Record in SQLite3 if available
        if ($db) {
            try {
                $stmt = $db->prepare("INSERT OR REPLACE INTO relay_states (id, name, state, gpio, updated_at) VALUES (:id, :name, :state, :gpio, CURRENT_TIMESTAMP)");
                $stmt->bindValue(':id', intval($relay_id), SQLITE3_INTEGER);
                $stmt->bindValue(':name', $state['relays'][$relay_id]['name'], SQLITE3_TEXT);
                $stmt->bindValue(':state', $new_val, SQLITE3_INTEGER);
                $stmt->bindValue(':gpio', $state['relays'][$relay_id]['gpio'] ?? 39, SQLITE3_INTEGER);
                $stmt->execute();
            } catch (Exception $e) {}
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Relay {$relay_id} switched to " . ($new_val ? 'ON' : 'OFF'),
            'relay_id' => intval($relay_id),
            'state' => $new_val,
            'control_mode' => $state['control_mode'],
            'auto_mode' => $state['auto_mode'],
            'relays' => $state['relays'],
            'target_board' => "{$board_ip}:{$board_port}",
            'direct_dispatched' => ($direct_result !== false),
            'board_response' => $direct_result
        ], JSON_UNESCAPED_UNICODE);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => "Invalid relay ID {$relay_id}"]);
        exit();
    }
}

// =========================================================================
// 2.1 SET CONTROL MODE (MANUAL / AUTO / AI)
// =========================================================================
if ($action === 'set_control_mode' || $action === 'set_mode') {
    $mode = strtolower(trim(strval($input_json['mode'] ?? ($_GET['mode'] ?? ($_POST['mode'] ?? 'manual')))));
    if (!in_array($mode, ['manual', 'auto', 'ai'])) {
        $mode = 'manual';
    }

    $state = get_current_state($telemetry_file, $default_state);
    $state['control_mode'] = $mode;
    $state['auto_mode'] = ($mode === 'auto');
    save_current_state($telemetry_file, $state);

    $board_ip = $state['board']['ip_address'] ?? '192.168.0.111';
    $board_port = $state['board']['web_port'] ?? 8500;
    $direct_result = send_board_command($board_ip, $board_port, "/mode?mode={$mode}");

    echo json_encode([
        'status' => 'success',
        'control_mode' => $state['control_mode'],
        'auto_mode' => $state['auto_mode'],
        'message' => "Control Mode switched to " . strtoupper($mode),
        'target_board' => "{$board_ip}:{$board_port}",
        'direct_dispatched' => ($direct_result !== false),
        'board_response' => $direct_result
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// =========================================================================
// 3. TOGGLE AUTO MODE (Backward Compatible)
// =========================================================================
if ($action === 'toggle_auto') {
    $state = get_current_state($telemetry_file, $default_state);
    $new_auto = !$state['auto_mode'];
    $state['auto_mode'] = $new_auto;
    $state['control_mode'] = $new_auto ? 'auto' : 'manual';
    save_current_state($telemetry_file, $state);

    $board_ip = $state['board']['ip_address'] ?? '192.168.0.111';
    $board_port = $state['board']['web_port'] ?? 8500;
    $mode_str = $new_auto ? 'auto' : 'manual';
    $direct_result = send_board_command($board_ip, $board_port, "/mode?mode={$mode_str}");

    echo json_encode([
        'status' => 'success',
        'auto_mode' => $state['auto_mode'],
        'control_mode' => $state['control_mode'],
        'message' => 'Smart Auto Mode ' . ($state['auto_mode'] ? 'Enabled' : 'Disabled'),
        'direct_dispatched' => ($direct_result !== false)
    ]);
    exit();
}

// =========================================================================
// 3.1 UPDATE GPS FIELD COORDINATES (จุดติดตั้งเซนเซอร์จริง)
// =========================================================================
if ($action === 'update_gps') {
    $lat = isset($input_json['latitude']) ? floatval($input_json['latitude']) : (isset($_POST['latitude']) ? floatval($_POST['latitude']) : (isset($_GET['latitude']) ? floatval($_GET['latitude']) : null));
    $lon = isset($input_json['longitude']) ? floatval($input_json['longitude']) : (isset($_POST['longitude']) ? floatval($_POST['longitude']) : (isset($_GET['longitude']) ? floatval($_GET['longitude']) : null));
    $loc_name = strval($input_json['location_name'] ?? ($_POST['location_name'] ?? ($_GET['location_name'] ?? 'จุดติดตั้งเซนเซอร์ภาคสนาม')));
    $prov = strval($input_json['province'] ?? ($_POST['province'] ?? ($_GET['province'] ?? 'จันทบุรี (Chanthaburi)')));
    $elevation = floatval($input_json['elevation_m'] ?? ($_POST['elevation_m'] ?? 24.5));

    if ($lat === null || $lon === null) {
        echo json_encode(['status' => 'error', 'message' => 'Missing latitude or longitude']);
        exit();
    }

    $state = get_current_state($telemetry_file, $default_state);
    $lat_dir = $lat >= 0 ? 'N' : 'S';
    $lon_dir = $lon >= 0 ? 'E' : 'W';
    $lat_abs = abs($lat);
    $lon_abs = abs($lon);

    $state['gps'] = [
        'latitude' => round($lat, 6),
        'longitude' => round($lon, 6),
        'latitude_deg' => sprintf("%.4f° %s", $lat_abs, $lat_dir),
        'longitude_deg' => sprintf("%.4f° %s", $lon_abs, $lon_dir),
        'formatted' => sprintf("%.4f° %s, %.4f° %s", $lat_abs, $lat_dir, $lon_abs, $lon_dir),
        'location_name' => $loc_name,
        'province' => $prov,
        'elevation_m' => round($elevation, 1),
        'maps_url' => "https://maps.google.com/?q={$lat},{$lon}",
        'status' => 'ACTIVE (REAL-FIELD GPS)',
        'updated_at' => date('Y-m-d H:i:s')
    ];
    save_current_state($telemetry_file, $state);

    echo json_encode([
        'status' => 'success',
        'message' => 'Field GPS coordinates updated successfully',
        'gps' => $state['gps']
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// =========================================================================
// 4. UPDATE TELEMETRY (POSTED DIRECTLY FROM ESP32 / CLOUD BRIDGE)
// =========================================================================
if ($action === 'update_telemetry' || $action === 'post_data') {
    $input = !empty($input_json) ? $input_json : (!empty($_POST) ? $_POST : $_GET);
    $state = get_current_state($telemetry_file, $default_state);

    if (isset($input['temperature']) || isset($input['temp'])) {
        $state['sensors']['temperature'] = floatval($input['temperature'] ?? $input['temp']);
    }
    if (isset($input['humidity']) || isset($input['hum'])) {
        $state['sensors']['humidity'] = floatval($input['humidity'] ?? $input['hum']);
    }
    if (isset($input['soil_moisture']) || isset($input['soil'])) {
        $state['sensors']['soil_moisture'] = floatval($input['soil_moisture'] ?? $input['soil']);
    }
    if (isset($input['soil_ec']) || isset($input['ec'])) {
        $state['sensors']['soil_ec'] = floatval($input['soil_ec'] ?? $input['ec']);
    }
    if (isset($input['soil_ph']) || isset($input['ph'])) {
        $state['sensors']['soil_ph'] = floatval($input['soil_ph'] ?? $input['ph']);
    }
    if (isset($input['vpd'])) {
        $state['sensors']['vpd'] = floatval($input['vpd']);
    }
    if (isset($input['par_lux']) || isset($input['lux'])) {
        $state['sensors']['par_lux'] = floatval($input['par_lux'] ?? $input['lux']);
    }
    if (isset($input['nitrogen'])) $state['sensors']['nitrogen'] = floatval($input['nitrogen']);
    if (isset($input['phosphorus'])) $state['sensors']['phosphorus'] = floatval($input['phosphorus']);
    if (isset($input['potassium'])) $state['sensors']['potassium'] = floatval($input['potassium']);

    // Support nested structure (from ESP32 HTTP POST /api/telemetry) - บันทึกทศนิยม 2 ตำแหน่ง
    if (isset($input['air']['humidity'])) $state['sensors']['humidity'] = round(floatval($input['air']['humidity']), 2);
    if (isset($input['air']['temperature']) && $input['air']['temperature'] !== null) $state['sensors']['temperature'] = round(floatval($input['air']['temperature']), 2);
    if (isset($input['air']['vpd'])) $state['sensors']['vpd'] = round(floatval($input['air']['vpd']), 2);
    if (isset($input['air']['dew_point'])) $state['sensors']['dew_point'] = round(floatval($input['air']['dew_point']), 2);
    
    // Derive temperature metrics
    $state['sensors']['temperature_f'] = round(($state['sensors']['temperature'] * 1.8) + 32.0, 2);
    $state['sensors']['dew_margin'] = round($state['sensors']['temperature'] - $state['sensors']['dew_point'], 2);
    $t = $state['sensors']['temperature'];
    $svp_val = 0.61078 * exp((17.27 * $t) / ($t + 237.3));
    $state['sensors']['vpsat'] = round($svp_val, 2);
    $state['sensors']['vpact'] = round($svp_val * ($state['sensors']['humidity'] / 100.0), 2);

    // Light
    if (isset($input['light']['lux'])) {
        $state['sensors']['par_lux'] = round(floatval($input['light']['lux']), 2);
        $state['sensors']['klux'] = round(floatval($input['light']['lux']) / 1000.0, 2);
    }
    if (isset($input['light']['solar_radiation'])) {
        $state['sensors']['solar_radiation'] = round(floatval($input['light']['solar_radiation']), 2);
    } elseif (isset($state['sensors']['par_lux'])) {
        $state['sensors']['solar_radiation'] = round($state['sensors']['par_lux'] * 0.0079, 2);
    }

    // Surface Soil Stick
    if (isset($input['soil_stick']['adc_raw'])) $state['sensors']['soil_stick_adc'] = intval($input['soil_stick']['adc_raw']);
    if (isset($input['soil_stick']['moisture_percent'])) $state['sensors']['soil_stick_moisture'] = round(floatval($input['soil_stick']['moisture_percent']), 1);
    if (isset($input['soil_stick']['ph'])) $state['sensors']['soil_stick_ph'] = round(floatval($input['soil_stick']['ph']), 1);
    if (isset($input['soil_stick']['ph_raw_voltage'])) $state['sensors']['soil_stick_ph_volt'] = round(floatval($input['soil_stick']['ph_raw_voltage']), 2);

    // Soil 7-in-1 Modbus (pH, EC, Moisture, N, P, K ทศนิยม 1 ตำแหน่งตามข้อกำหนด)
    if (isset($input['soil_7in1']['ph'])) $state['sensors']['soil_ph'] = round(floatval($input['soil_7in1']['ph']), 1);
    if (isset($input['soil_7in1']['ec'])) $state['sensors']['soil_ec'] = round(floatval($input['soil_7in1']['ec']), 1);
    if (isset($input['soil_7in1']['temperature'])) $state['sensors']['soil_temperature'] = round(floatval($input['soil_7in1']['temperature']), 2);
    if (isset($input['soil_7in1']['moisture_percent'])) {
        $state['sensors']['soil_moisture'] = round(floatval($input['soil_7in1']['moisture_percent']), 1);
    } elseif (isset($state['sensors']['soil_stick_moisture'])) {
        $state['sensors']['soil_moisture'] = $state['sensors']['soil_stick_moisture'];
    }
    if (isset($input['soil_7in1']['nitrogen'])) $state['sensors']['nitrogen'] = round(floatval($input['soil_7in1']['nitrogen']), 1);
    if (isset($input['soil_7in1']['phosphorus'])) $state['sensors']['phosphorus'] = round(floatval($input['soil_7in1']['phosphorus']), 1);
    if (isset($input['soil_7in1']['potassium'])) $state['sensors']['potassium'] = round(floatval($input['soil_7in1']['potassium']), 1);

    // TinyML AI Calibrated (pH, N, P, K, Moisture ทศนิยม 1 ตำแหน่ง)
    if (isset($input['ai_calibrated'])) {
        $ai = $input['ai_calibrated'];
        if (isset($ai['nitrogen'])) $state['ai_calibrated']['nitrogen'] = round(floatval($ai['nitrogen']), 1);
        if (isset($ai['phosphorus'])) $state['ai_calibrated']['phosphorus'] = round(floatval($ai['phosphorus']), 1);
        if (isset($ai['potassium'])) $state['ai_calibrated']['potassium'] = round(floatval($ai['potassium']), 1);
        if (isset($ai['ph'])) $state['ai_calibrated']['ph'] = round(floatval($ai['ph']), 1);
        if (isset($ai['moisture_percent'])) $state['ai_calibrated']['moisture'] = round(floatval($ai['moisture_percent']), 1);
        if (isset($ai['confidence'])) $state['ai_calibrated']['confidence'] = round(floatval($ai['confidence']), 3);
    }

    // Micro-SD Card Subsystem from ESP32
    if (isset($input['sd_card'])) {
        $state['sd_card']['mounted'] = !empty($input['sd_card']['mounted']);
        $state['sd_card']['records'] = intval($input['sd_card']['records'] ?? 0);
        $state['sd_card']['cs_pin'] = intval($input['sd_card']['cs_pin'] ?? -1);
        $state['sd_card']['size_mb'] = intval($input['sd_card']['size_mb'] ?? 0);
        $state['sd_card']['status_text'] = $state['sd_card']['mounted'] ? 'ACTIVE LOGGING' : 'STANDBY (NO SD CARD)';
    }

    // Actuators
    if (isset($input['actuators'])) {
        $act = $input['actuators'];
        if (!empty($act['mode'])) {
            $reported_mode = strtolower(trim($act['mode']));
            if (in_array($reported_mode, ['manual', 'auto', 'ai'])) {
                // If board changed mode via local touch LCD, sync mode
                $state['control_mode'] = $reported_mode;
                $state['auto_mode'] = ($reported_mode === 'auto');
            }
        }
        // In AUTO or AI mode, update server relays to reflect board's real-time decisions
        if (($state['control_mode'] ?? 'manual') !== 'manual') {
            if (isset($act['r1'])) $state['relays']['1']['state'] = ($act['r1'] == 1 || $act['r1'] === true) ? 1 : 0;
            if (isset($act['r2'])) $state['relays']['2']['state'] = ($act['r2'] == 1 || $act['r2'] === true) ? 1 : 0;
            if (isset($act['r3'])) $state['relays']['3']['state'] = ($act['r3'] == 1 || $act['r3'] === true) ? 1 : 0;
            if (isset($act['r4'])) $state['relays']['4']['state'] = ($act['r4'] == 1 || $act['r4'] === true) ? 1 : 0;
        }
    }

    // Board Network Updates from payload
    if (!empty($input['ip'])) $state['board']['ip_address'] = trim($input['ip']);
    if (!empty($input['ssid'])) $state['board']['ssid'] = trim($input['ssid']);
    if (isset($input['rssi'])) $state['board']['rssi'] = intval($input['rssi']);
    if (!empty($input['cloud_url'])) $state['board']['cloud_url'] = trim($input['cloud_url']);
    if (!empty($input['web_port'])) $state['board']['web_port'] = intval($input['web_port']);
    if (!empty($input['direct_url'])) $state['board']['direct_url'] = trim($input['direct_url']);
    if (!empty($input['device_id'])) $state['board']['device_id'] = trim($input['device_id']);
    if (!empty($input['device_name'])) $state['board']['device_name'] = trim($input['device_name']);
    $state['board']['data_source'] = 'DIRECT_ESP32_PUSH (' . ($state['board']['ip_address'] ?? '192.168.0.111') . ')';
    $state['board']['cloud_status'] = 'ESP32 DIRECT (ONLINE)';
    $state['board']['last_direct_post_time'] = time();
    $state['board']['telemetry_timestamp'] = $input['datetime'] ?? date('Y-m-d H:i:s');
    $state['board']['is_live'] = true;

    $state['sensors']['updated_at'] = date('Y-m-d H:i:s');
    save_current_state($telemetry_file, $state);

    // Save complete telemetry into SQLite3 database
    log_telemetry_to_db($db, $state);

    echo json_encode([
        'status' => 'success',
        'message' => 'Telemetry data stored in SQLite3 & SD Card acknowledged',
        'sd_card' => $state['sd_card'],
        'relays_command' => [
            'r1' => intval($state['relays']['1']['state']),
            'r2' => intval($state['relays']['2']['state']),
            'r3' => intval($state['relays']['3']['state']),
            'r4' => intval($state['relays']['4']['state'])
        ],
        'auto_mode' => $state['auto_mode'] ? 1 : 0,
        'control_mode' => $state['control_mode'] ?? 'manual'
    ]);
    exit();
}

// =========================================================================
// 4.1 GET DATABASE HISTORY TABLE & TOTAL RECORDS
// =========================================================================
if ($action === 'get_history_table') {
    $limit = intval($_GET['limit'] ?? 20);
    $offset = intval($_GET['offset'] ?? 0);
    $total = 0;
    $rows = [];

    if ($db) {
        try {
            $total = intval($db->querySingle("SELECT COUNT(*) FROM telemetry_logs"));
            $stmt = $db->prepare("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
            $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
            $res = $stmt->execute();
            while ($r = $res->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $r;
            }
        } catch (Exception $e) {}
    }

    echo json_encode([
        'status' => 'success',
        'total_records' => $total,
        'limit' => $limit,
        'offset' => $offset,
        'data' => $rows
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// =========================================================================
// 4.2 EXPORT DATABASE TELEMETRY AS CSV DOWNLOAD
// =========================================================================
if ($action === 'export_csv') {
    $filename = "leqs_telemetry_database_" . date('Ymd_His') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $out = fopen('php://output', 'w');
    // Write UTF-8 BOM for Thai support in Microsoft Excel
    fputs($out, "\xEF\xBB\xBF");

    // Comprehensive CSV Header
    fputcsv($out, [
        'ID',
        'วัน-เวลา (Timestamp)',
        'อุณหภูมิอากาศ (°C)',
        'อุณหภูมิอากาศ (°F)',
        'ความชื้นอากาศ (%RH)',
        'จุดน้ำค้าง (°C)',
        'ระยะน้ำค้าง (°C)',
        'VPD (kPa)',
        'VPsat (kPa)',
        'VPact (kPa)',
        'ความเข้มแสง (Lux)',
        'kLux',
        'รังสีอาทิตย์ (W/m²)',
        'ดินผิวดิน Stick ADC',
        'ความชื้นผิวดิน (%)',
        'pH ผิวดิน',
        'แรงดัน pH ผิวดิน (V)',
        'อุณหภูมิดินลึก 7in1 (°C)',
        'ความชื้นดินลึก 7in1 (%)',
        'EC สภาพนำไฟฟ้าดิน (µS/cm)',
        'pH ดินลึก 7in1',
        'ไนโตรเจน N (mg/kg)',
        'ฟอสฟอรัส P (mg/kg)',
        'โพแทสเซียม K (mg/kg)',
        'AI ไนโตรเจน (mg/kg)',
        'AI ฟอสฟอรัส (mg/kg)',
        'AI โพแทสเซียม (mg/kg)',
        'AI pH ดิน',
        'AI ความชื้นดิน (%)',
        'ความเชื่อมั่น AI (Confidence)',
        'รีเลย์ 1 (วาล์วน้ำโซลินอยด์)',
        'รีเลย์ 2 (พ่นหมอก)',
        'รีเลย์ 3 (ปั๊มปุ๋ย NPK)',
        'รีเลย์ 4 (พัดลมระบายอากาศ)',
        'สถานะ SD Card',
        'จำนวนเรคอร์ดใน SD Card',
        'สัญญาณ RSSI (dBm)',
        'IP Address',
        'SSID Wi-Fi',
        'แหล่งข้อมูล (Data Source)'
    ]);

    if ($db) {
        try {
            $res = $db->query("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT 10000");
            while ($r = $res->fetchArray(SQLITE3_ASSOC)) {
                fputcsv($out, [
                    $r['id'] ?? '',
                    $r['created_at'] ?? '',
                    $r['temperature'] ?? '',
                    $r['temperature_f'] ?? '',
                    $r['humidity'] ?? '',
                    $r['dew_point'] ?? '',
                    $r['dew_margin'] ?? '',
                    $r['vpd'] ?? '',
                    $r['vpsat'] ?? '',
                    $r['vpact'] ?? '',
                    $r['par_lux'] ?? '',
                    $r['klux'] ?? '',
                    $r['solar_radiation'] ?? '',
                    $r['soil_stick_adc'] ?? '',
                    $r['soil_stick_moisture'] ?? '',
                    $r['soil_stick_ph'] ?? '',
                    $r['soil_stick_ph_volt'] ?? '',
                    $r['soil_temperature'] ?? '',
                    $r['soil_moisture'] ?? '',
                    $r['soil_ec'] ?? '',
                    $r['soil_ph'] ?? '',
                    $r['nitrogen'] ?? '',
                    $r['phosphorus'] ?? '',
                    $r['potassium'] ?? '',
                    $r['ai_nitrogen'] ?? '',
                    $r['ai_phosphorus'] ?? '',
                    $r['ai_potassium'] ?? '',
                    $r['ai_ph'] ?? '',
                    $r['ai_moisture'] ?? '',
                    $r['ai_confidence'] ?? '',
                    $r['relay1'] ?? 0,
                    $r['relay2'] ?? 0,
                    $r['relay3'] ?? 0,
                    $r['relay4'] ?? 0,
                    !empty($r['sd_card_mounted']) ? 'Mounted (ปกติ)' : 'Unmounted',
                    $r['sd_card_records'] ?? 0,
                    $r['rssi'] ?? '',
                    $r['ip_address'] ?? '',
                    $r['ssid'] ?? '',
                    $r['data_source'] ?? ''
                ]);
            }
        } catch (Exception $e) {}
    }

    fclose($out);
    exit();
}

// =========================================================================
// 5. UPDATE BOARD CONFIG (IP, SSID, Cloud URL, Port)
// =========================================================================
if ($action === 'update_board_config') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $state = get_current_state($telemetry_file, $default_state);

    if (!empty($input['ip'])) {
        $state['board']['ip_address'] = trim($input['ip']);
        $port = $state['board']['web_port'] ?? 8500;
        $state['board']['direct_url'] = "http://{$state['board']['ip_address']}:{$port}";
    }
    if (!empty($input['ssid'])) {
        $state['board']['ssid'] = trim($input['ssid']);
    }
    if (!empty($input['cloud_url'])) {
        $state['board']['cloud_url'] = trim($input['cloud_url']);
    }
    if (!empty($input['web_port'])) {
        $state['board']['web_port'] = intval($input['web_port']);
        $state['board']['direct_url'] = "http://{$state['board']['ip_address']}:{$state['board']['web_port']}";
    }
    if (isset($input['rssi'])) {
        $state['board']['rssi'] = intval($input['rssi']);
    }
    if (!empty($input['system_language'])) {
        $state['board']['system_language'] = trim($input['system_language']);
    }

    save_current_state($telemetry_file, $state);
    echo json_encode([
        'status' => 'success',
        'message' => 'Board network configuration saved',
        'board' => $state['board']
    ]);
    exit();
}

// =========================================================================
// 6. PROXY DIRECT PING TO ESP32 OR CLOUD
// =========================================================================
if ($action === 'ping_board') {
    $state = get_current_state($telemetry_file, $default_state);
    $target_ip = $state['board']['ip_address'];
    $target_port = $state['board']['web_port'];
    
    // Quick socket check with 500ms timeout
    $online = false;
    $latency_ms = 0;
    $start_t = microtime(true);
    
    $fp = @fsockopen($target_ip, $target_port, $errno, $errstr, 0.4);
    if ($fp) {
        $online = true;
        fclose($fp);
        $latency_ms = round((microtime(true) - $start_t) * 1000);
    }
    
    echo json_encode([
        'status' => 'success',
        'board_ip' => $target_ip,
        'board_port' => $target_port,
        'cloud_url' => $state['board']['cloud_url'],
        'online' => $online,
        'latency_ms' => $online ? $latency_ms : null,
        'message' => $online ? "Direct socket open on {$target_ip}:{$target_port} ({$latency_ms}ms)" : "Board offline or unreachable on local network"
    ]);
    exit();
}

// =========================================================================
// 7. GET HISTORICAL TELEMETRY (For Chart.js Curves)
// =========================================================================
if ($action === 'get_history') {
    $points = [];
    $count = intval($_GET['limit'] ?? 15);
    
    if ($db) {
        try {
            $res = $db->query("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT {$count}");
            while ($r = $res->fetchArray(SQLITE3_ASSOC)) {
                $points[] = $r;
            }
            $points = array_reverse($points);
        } catch (Exception $e) {}
    }
    
    if (empty($points)) {
        // Generate realistic historical baseline if table empty
        $base_t = time() - ($count * 60);
        for ($i = 0; $i < $count; $i++) {
            $t = $base_t + ($i * 60);
            $points[] = [
                'created_at' => date('H:i', $t),
                'temperature' => round(27.8 + sin($i * 0.3) * 1.5 + (mt_rand(-5, 5) / 10), 1),
                'humidity' => round(66.0 - sin($i * 0.3) * 3.0 + (mt_rand(-10, 10) / 10), 1),
                'soil_moisture' => round(72.0 + cos($i * 0.2) * 1.2, 1),
                'vpd' => round(0.92 + (sin($i * 0.3) * 0.1), 2),
                'soil_ec' => round(845 + mt_rand(-10, 15)),
                'soil_ph' => 6.4
            ];
        }
    }
    
    echo json_encode(['status' => 'success', 'data' => $points]);
    exit();
}

// =========================================================================
// 8. PARTICIPANTS CRUD APIS
// =========================================================================

// 8.1 LIST ALL PARTICIPANTS (GET / POST ?action=list)
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

// 8.2 GET SINGLE PARTICIPANT (GET / POST ?action=get_participant&id=X)
if ($action === 'get_participant') {
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = intval($input['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสผู้เข้าร่วมไม่ถูกต้อง']);
        exit();
    }
    if ($db) {
        $stmt = $db->prepare("SELECT * FROM participants WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $res = $stmt->execute();
        $row = $res->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้เข้าร่วม']);
        }
        exit();
    } else {
        $data = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
        foreach ($data as $item) {
            if (($item['id'] ?? 0) == $id) {
                echo json_encode(['status' => 'success', 'data' => $item]);
                exit();
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้เข้าร่วม']);
        exit();
    }
}

// 8.3 REGISTER / ADD PARTICIPANT (POST ?action=register หรือ ?action=add_participant)
if ($action === 'register' || $action === 'add_participant') {
    $input = !empty($input_json) ? $input_json : $_POST;

    $prefix = trim($input['prefix'] ?? 'นาย');
    $fullname = trim($input['fullname'] ?? '');
    $target_group = trim($input['target_group'] ?? 'นักเรียนมัธยมศึกษาตอนปลาย');
    $organization = trim($input['organization'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $email = trim($input['email'] ?? '');
    $project_track = trim($input['project_track'] ?? 'Track A — Smart Agriculture & Sensor Hub');
    $pre_score = isset($input['pre_score']) ? intval($input['pre_score']) : 0;
    $post_score = isset($input['post_score']) ? intval($input['post_score']) : 0;
    $status = trim($input['status'] ?? 'registered');

    if (empty($fullname)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อ-นามสกุล']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track, pre_score, post_score, status) VALUES (:prefix, :fullname, :target_group, :organization, :phone, :email, :project_track, :pre_score, :post_score, :status)");
        $stmt->bindValue(':prefix', $prefix, SQLITE3_TEXT);
        $stmt->bindValue(':fullname', $fullname, SQLITE3_TEXT);
        $stmt->bindValue(':target_group', $target_group, SQLITE3_TEXT);
        $stmt->bindValue(':organization', $organization, SQLITE3_TEXT);
        $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':project_track', $project_track, SQLITE3_TEXT);
        $stmt->bindValue(':pre_score', $pre_score, SQLITE3_INTEGER);
        $stmt->bindValue(':post_score', $post_score, SQLITE3_INTEGER);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->execute();
        $new_id = $db->lastInsertRowID();
        
        sync_participants_json($db, $json_backup);
        
        echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลผู้ลงทะเบียนสำเร็จ!', 'id' => $new_id]);
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
            'pre_score' => $pre_score,
            'post_score' => $post_score,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $current[] = $new_record;
        file_put_contents($json_backup, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลผู้ลงทะเบียนสำเร็จ!', 'id' => $new_record['id']]);
        exit();
    }
}

// 8.4 UPDATE PARTICIPANT (POST ?action=update_participant)
if ($action === 'update_participant') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $id = intval($input['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสผู้เข้าร่วมไม่ถูกต้อง']);
        exit();
    }

    $prefix = trim($input['prefix'] ?? 'นาย');
    $fullname = trim($input['fullname'] ?? '');
    $target_group = trim($input['target_group'] ?? '');
    $organization = trim($input['organization'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $email = trim($input['email'] ?? '');
    $project_track = trim($input['project_track'] ?? '');
    $pre_score = isset($input['pre_score']) ? intval($input['pre_score']) : null;
    $post_score = isset($input['post_score']) ? intval($input['post_score']) : null;
    $status = trim($input['status'] ?? '');

    if (empty($fullname)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อ-นามสกุล']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("UPDATE participants SET 
            prefix = :prefix, 
            fullname = :fullname, 
            target_group = :target_group, 
            organization = :organization, 
            phone = :phone, 
            email = :email, 
            project_track = :project_track, 
            pre_score = :pre_score, 
            post_score = :post_score, 
            status = :status 
            WHERE id = :id");
        $stmt->bindValue(':prefix', $prefix, SQLITE3_TEXT);
        $stmt->bindValue(':fullname', $fullname, SQLITE3_TEXT);
        $stmt->bindValue(':target_group', $target_group, SQLITE3_TEXT);
        $stmt->bindValue(':organization', $organization, SQLITE3_TEXT);
        $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':project_track', $project_track, SQLITE3_TEXT);
        $stmt->bindValue(':pre_score', $pre_score !== null ? $pre_score : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':post_score', $post_score !== null ? $post_score : 0, SQLITE3_INTEGER);
        $stmt->bindValue(':status', !empty($status) ? $status : 'registered', SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_participants_json($db, $json_backup);

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขข้อมูลผู้ลงทะเบียนสำเร็จ']);
        exit();
    } else {
        $current = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
        $found = false;
        foreach ($current as &$item) {
            if (($item['id'] ?? 0) == $id) {
                $item['prefix'] = $prefix;
                $item['fullname'] = $fullname;
                $item['target_group'] = $target_group;
                $item['organization'] = $organization;
                $item['phone'] = $phone;
                $item['email'] = $email;
                $item['project_track'] = $project_track;
                if ($pre_score !== null) $item['pre_score'] = $pre_score;
                if ($post_score !== null) $item['post_score'] = $post_score;
                if (!empty($status)) $item['status'] = $status;
                $found = true;
                break;
            }
        }
        if ($found) {
            file_put_contents($json_backup, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo json_encode(['status' => 'success', 'message' => 'แก้ไขข้อมูลผู้ลงทะเบียนสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการแก้ไข']);
        }
        exit();
    }
}

// 8.5 DELETE PARTICIPANT (POST / GET ?action=delete_participant&id=X)
if ($action === 'delete_participant') {
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = intval($input['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสผู้เข้าร่วมไม่ถูกต้อง']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("DELETE FROM participants WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_participants_json($db, $json_backup);

        echo json_encode(['status' => 'success', 'message' => "ลบรายชื่อผู้เข้าร่วม #{$id} เรียบร้อยแล้ว"]);
        exit();
    } else {
        $current = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
        $filtered = array_filter($current, fn($item) => ($item['id'] ?? 0) != $id);
        file_put_contents($json_backup, json_encode(array_values($filtered), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => "ลบรายชื่อผู้เข้าร่วม #{$id} เรียบร้อยแล้ว"]);
        exit();
    }
}

// 8.6 TOGGLE CHECK-IN STATUS (POST / GET ?action=toggle_checkin&id=X)
if ($action === 'toggle_checkin') {
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = intval($input['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสผู้เข้าร่วมไม่ถูกต้อง']);
        exit();
    }

    if ($db) {
        $curr = $db->querySingle("SELECT status FROM participants WHERE id = " . $id);
        $new_status = ($curr === 'checked-in') ? 'registered' : 'checked-in';
        $stmt = $db->prepare("UPDATE participants SET status = :status WHERE id = :id");
        $stmt->bindValue(':status', $new_status, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_participants_json($db, $json_backup);

        echo json_encode([
            'status' => 'success',
            'message' => 'เปลี่ยนสถานะเป็น: ' . ($new_status === 'checked-in' ? 'เช็คอินแล้ว' : 'ลงทะเบียน'),
            'new_status' => $new_status,
            'id' => $id
        ]);
        exit();
    }
}

// 8.7 RESET TO DEFAULT PARTICIPANTS (POST ?action=reset_participants)
if ($action === 'reset_participants') {
    if ($db) {
        $db->exec("DELETE FROM participants");
        $seed_p = [
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
        $ins_p = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track, pre_score, post_score, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($seed_p as $sp) {
            $ins_p->bindValue(1, $sp[0], SQLITE3_TEXT);
            $ins_p->bindValue(2, $sp[1], SQLITE3_TEXT);
            $ins_p->bindValue(3, $sp[2], SQLITE3_TEXT);
            $ins_p->bindValue(4, $sp[3], SQLITE3_TEXT);
            $ins_p->bindValue(5, $sp[4], SQLITE3_TEXT);
            $ins_p->bindValue(6, $sp[5], SQLITE3_TEXT);
            $ins_p->bindValue(7, $sp[6], SQLITE3_TEXT);
            $ins_p->bindValue(8, $sp[7], SQLITE3_INTEGER);
            $ins_p->bindValue(9, $sp[8], SQLITE3_INTEGER);
            $ins_p->bindValue(10, $sp[9], SQLITE3_TEXT);
            $ins_p->execute();
        }
        sync_participants_json($db, $json_backup);
        echo json_encode(['status' => 'success', 'message' => 'รีเซ็ตข้อมูลผู้เข้าร่วมกลับสู่ชุดเริ่มต้นสำเร็จ']);
        exit();
    }
}

// =========================================================================
// 9. SYSTEM ANNOUNCEMENTS & MESSAGES CRUD APIS
// =========================================================================

// 9.1 LIST ANNOUNCEMENTS (GET / POST ?action=list_announcements)
if ($action === 'list_announcements') {
    if ($db) {
        $results = $db->query("SELECT * FROM announcements ORDER BY id DESC");
        $data = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit();
    } else {
        $ann_file = $data_dir . '/announcements.json';
        $data = file_exists($ann_file) ? json_decode(file_get_contents($ann_file), true) : [];
        echo json_encode(['status' => 'success', 'data' => $data ?: []]);
        exit();
    }
}

// 9.2 ADD ANNOUNCEMENT (POST ?action=add_announcement)
if ($action === 'add_announcement') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $title = trim($input['title'] ?? '');
    $content = trim($input['content'] ?? '');
    $badge = trim($input['badge'] ?? 'ประชาสัมพันธ์');
    $badge_color = trim($input['badge_color'] ?? 'cyan');
    $is_active = isset($input['is_active']) ? intval($input['is_active']) : 1;

    if (empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกหัวข้อประกาศ']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("INSERT INTO announcements (title, content, badge, badge_color, is_active) VALUES (:title, :content, :badge, :badge_color, :is_active)");
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':content', $content, SQLITE3_TEXT);
        $stmt->bindValue(':badge', $badge, SQLITE3_TEXT);
        $stmt->bindValue(':badge_color', $badge_color, SQLITE3_TEXT);
        $stmt->bindValue(':is_active', $is_active, SQLITE3_INTEGER);
        $stmt->execute();
        $new_id = $db->lastInsertRowID();

        sync_announcements_json($db, $data_dir . '/announcements.json');

        echo json_encode(['status' => 'success', 'message' => 'เพิ่มประกาศใหม่สำเร็จ', 'id' => $new_id]);
        exit();
    }
}

// 9.3 UPDATE ANNOUNCEMENT (POST ?action=update_announcement)
if ($action === 'update_announcement') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $id = intval($input['id'] ?? 0);
    $title = trim($input['title'] ?? '');
    $content = trim($input['content'] ?? '');
    $badge = trim($input['badge'] ?? 'ประชาสัมพันธ์');
    $badge_color = trim($input['badge_color'] ?? 'cyan');
    $is_active = isset($input['is_active']) ? intval($input['is_active']) : 1;

    if ($id <= 0 || empty($title)) {
        echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วนหรือไม่ถูกต้อง']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("UPDATE announcements SET title = :title, content = :content, badge = :badge, badge_color = :badge_color, is_active = :is_active, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':content', $content, SQLITE3_TEXT);
        $stmt->bindValue(':badge', $badge, SQLITE3_TEXT);
        $stmt->bindValue(':badge_color', $badge_color, SQLITE3_TEXT);
        $stmt->bindValue(':is_active', $is_active, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_announcements_json($db, $data_dir . '/announcements.json');

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขประกาศเรียบร้อยแล้ว']);
        exit();
    }
}

// 9.4 DELETE ANNOUNCEMENT (POST / GET ?action=delete_announcement&id=X)
if ($action === 'delete_announcement') {
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = intval($input['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสประกาศไม่ถูกต้อง']);
        exit();
    }

    if ($db) {
        $stmt = $db->prepare("DELETE FROM announcements WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_announcements_json($db, $data_dir . '/announcements.json');

        echo json_encode(['status' => 'success', 'message' => "ลบประกาศ #{$id} สำเร็จ"]);
        exit();
    }
}

// 9.5 TOGGLE ANNOUNCEMENT ACTIVE STATUS (POST / GET ?action=toggle_announcement&id=X)
if ($action === 'toggle_announcement') {
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = intval($input['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสประกาศไม่ถูกต้อง']);
        exit();
    }

    if ($db) {
        $curr = $db->querySingle("SELECT is_active FROM announcements WHERE id = " . $id);
        $new_val = ($curr == 1) ? 0 : 1;
        $stmt = $db->prepare("UPDATE announcements SET is_active = :val WHERE id = :id");
        $stmt->bindValue(':val', $new_val, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        sync_announcements_json($db, $data_dir . '/announcements.json');

        echo json_encode(['status' => 'success', 'message' => 'เปลี่ยนสถานะประกาศสำเร็จ', 'is_active' => $new_val]);
        exit();
    }
}

// =========================================================================
// 10. SAVE PRE/POST TEST SCORE
// =========================================================================
if ($action === 'save_test') {
    $input = !empty($input_json) ? $input_json : $_POST;
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
        
        sync_participants_json($db, $json_backup);

        echo json_encode(['status' => 'success', 'message' => 'บันทึกคะแนนเรียบร้อย']);
        exit();
    } else {
        echo json_encode(['status' => 'success', 'message' => 'บันทึกคะแนนเรียบร้อย (Mock)']);
        exit();
    }
}

// =========================================================================
// 11. SAVE CERTIFICATE CONFIG
// =========================================================================
if ($action === 'save_certificate_config') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $cfg_data = [
        'course_name' => trim($input['course_name'] ?? ''),
        'project_name' => trim($input['project_name'] ?? ''),
        'cert_ref_prefix' => trim($input['cert_ref_prefix'] ?? 'LEQs-xAI'),
        'signatory1_name' => trim($input['signatory1_name'] ?? ''),
        'signatory1_title' => trim($input['signatory1_title'] ?? ''),
        'signatory1_sub' => trim($input['signatory1_sub'] ?? ''),
        'signatory1_image' => trim($input['signatory1_image'] ?? 'chewa_sign.png'),
        'signatory2_name' => trim($input['signatory2_name'] ?? ''),
        'signatory2_title' => trim($input['signatory2_title'] ?? ''),
        'signatory2_sub' => trim($input['signatory2_sub'] ?? ''),
        'signatory2_image' => trim($input['signatory2_image'] ?? 'vichaladda_sign.png')
    ];
    $cert_config_file = $data_dir . '/certificate_config.json';
    @file_put_contents($cert_config_file, json_encode($cfg_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success', 'message' => 'บันทึกการตั้งค่าเกียรติบัตรเรียบร้อยแล้ว', 'config' => $cfg_data]);
    exit();
}

// Fallback
echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);


