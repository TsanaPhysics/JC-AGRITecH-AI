<?php
/**
 * Edge xAI : 4DAgriEnvi REST API Backend
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
$gs_config_file = $data_dir . '/google_sheets_config.json';
$line_config_file = $data_dir . '/line_config.json';

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

// =========================================================================
// GOOGLE SHEETS SYNC ENGINE HELPERS
// =========================================================================
function get_google_sheets_config($file_path) {
    if (file_exists($file_path)) {
        $data = @json_decode(file_get_contents($file_path), true);
        if (is_array($data)) {
            return array_merge([
                'enabled' => false,
                'webhook_url' => '',
                'sheet_url' => '',
                'auto_sync_on_register' => true,
                'last_sync' => null,
                'last_sync_count' => 0,
                'last_status' => 'standby'
            ], $data);
        }
    }
    return [
        'enabled' => false,
        'webhook_url' => '',
        'sheet_url' => '',
        'auto_sync_on_register' => true,
        'last_sync' => null,
        'last_sync_count' => 0,
        'last_status' => 'standby'
    ];
}

function save_google_sheets_config($file_path, $cfg) {
    return @file_put_contents($file_path, json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function sync_to_google_sheets($payload, $config_file) {
    $cfg = get_google_sheets_config($config_file);
    if (empty($cfg['enabled']) || empty($cfg['webhook_url'])) {
        return ['status' => 'skipped', 'message' => 'Google Sheets sync is disabled or webhook URL is empty'];
    }

    $url = trim($cfg['webhook_url']);
    $data_string = json_encode($payload, JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Google Apps Script redirects via 302
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);          // Max 4s to never block user
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    ]);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        $cfg['last_status'] = 'error: ' . $curl_err;
        save_google_sheets_config($config_file, $cfg);
        return ['status' => 'error', 'message' => $curl_err];
    }

    $res_json = @json_decode($result, true);
    $cfg['last_sync'] = date('Y-m-d H:i:s');
    $cfg['last_status'] = 'success (HTTP ' . $http_code . ')';
    save_google_sheets_config($config_file, $cfg);

    return is_array($res_json) ? $res_json : ['status' => 'success', 'raw' => $result, 'http_code' => $http_code];
}

// =========================================================================
// LINE NOTIFICATION ENGINE (LINE Official Account / Messaging API & LINE Notify)
// =========================================================================
function get_line_config($file_path) {
    if (file_exists($file_path)) {
        $data = @json_decode(file_get_contents($file_path), true);
        if (is_array($data)) {
            return array_merge([
                'enabled' => false,
                'channel_access_token' => '',
                'target_id' => '', // User ID or Group ID (leave empty for broadcast)
                'notify_mode' => 'messaging_api', // messaging_api or line_notify
                'auto_notify_on_register' => true,
                'last_status' => 'standby (กรุณาระบุ Channel Access Token)',
                'last_sent' => null
            ], $data);
        }
    }
    return [
        'enabled' => false,
        'channel_access_token' => '',
        'target_id' => '',
        'notify_mode' => 'messaging_api',
        'auto_notify_on_register' => true,
        'last_status' => 'standby (กรุณาระบุ Channel Access Token)',
        'last_sent' => null
    ];
}

function save_line_config($file_path, $cfg) {
    return @file_put_contents($file_path, json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function send_line_registration_notify($p, $config_file) {
    $cfg = get_line_config($config_file);
    if (empty($cfg['enabled']) || empty($cfg['channel_access_token'])) {
        return ['status' => 'skipped', 'message' => 'LINE Notification is disabled or token is empty'];
    }

    $token = trim($cfg['channel_access_token']);
    $target_id = trim($cfg['target_id'] ?? '');
    $mode = $cfg['notify_mode'] ?? 'messaging_api';

    $prefix = $p['prefix'] ?? '';
    $fullname = $p['fullname'] ?? '';
    $target_group = $p['target_group'] ?? '-';
    $org = $p['organization'] ?? '-';
    $phone = $p['phone'] ?? '-';
    $email = $p['email'] ?? '-';
    $track = $p['project_track'] ?? '-';
    $time = date('d/m/Y H:i:s');

    $msg = "📢 มีผู้ลงทะเบียนใหม่!\n";
    $msg .= "โครงการ: Edge xAI : 4DAgriEnvi\n";
    $msg .= "-----------------------------\n";
    $msg .= "👤 ชื่อ-สกุล: {$prefix} {$fullname}\n";
    $msg .= "🏷️ กลุ่มเป้าหมาย: {$target_group}\n";
    $msg .= "🏢 สังกัด: {$org}\n";
    $msg .= "📞 เบอร์โทร: {$phone}\n";
    $msg .= "✉️ อีเมล: {$email}\n";
    $msg .= "🎯 แทร็กโครงงาน: {$track}\n";
    $msg .= "⏰ เวลา: {$time} น.\n";
    $msg .= "-----------------------------\n";
    $msg .= "🌐 ตรวจสอบรายชื่อทั้งหมด: https://scicenter.rbru.ac.th/edge-xaiot-agrienvi/pages/student_list.php";

    if ($mode === 'line_notify') {
        // LINE Notify API (Legacy)
        $ch = curl_init('https://notify-api.line.me/api/notify');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['message' => "\n" . $msg]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/x-www-form-urlencoded'
        ]);
    } else {
        // LINE Messaging API (Official Standard - LINE Official Account)
        $endpoint = !empty($target_id) 
            ? 'https://api.line.me/v2/bot/message/push' 
            : 'https://api.line.me/v2/bot/message/broadcast';
        
        $body = [
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $msg
                ]
            ]
        ];
        if (!empty($target_id)) {
            $body['to'] = $target_id;
        }

        $json_data = json_encode($body, JSON_UNESCAPED_UNICODE);
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    $cfg['last_sent'] = date('Y-m-d H:i:s');
    if ($curl_err) {
        $cfg['last_status'] = 'error: ' . $curl_err;
        save_line_config($config_file, $cfg);
        return ['status' => 'error', 'message' => $curl_err];
    }

    $cfg['last_status'] = ($http_code === 200) ? 'success (HTTP 200)' : "warning (HTTP {$http_code})";
    save_line_config($config_file, $cfg);

    return ['status' => ($http_code === 200 ? 'success' : 'failed'), 'http_code' => $http_code, 'raw' => $result];
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

        // Seed announcements if empty
        $a_count = $db->querySingle("SELECT COUNT(*) FROM announcements");
        if ($a_count == 0) {
            $seed_a = [
                ['เปิดรับสมัครเข้าร่วมโครงการอบรมเชิงปฏิบัติการ Edge xAIoT : 4D-AgriEnvi 2026', 'ขอเชิญนักเรียน ครู และเกษตรกรเข้าร่วมโครงการอบรมเชิงปฏิบัติการ ปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอทีเพื่อการเกษตรและสิ่งแวดล้อม (Edge xAIoT : 4D-AgriEnvi) วันที่ 17 - 19 ธันวาคม 2569 ณ ห้องประชุมปะการัง อาคาร 9 คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี รับจำนวนจำกัด 30 ท่าน ฟรีตลอดหลักสูตร', 'รับสมัคร', 'emerald', 1],
                ['กำหนดการรายงานตัวและรับชุดอุปกรณ์บอร์ดทดลอง ESP32-S3 ATD3.5', 'ผู้ผ่านการคัดเลือกสามารถนำบัตรประชาชนหรือบัตรนักเรียนมารายงานตัว ณ ห้องประชุมปะการัง อาคาร 9 คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี ในวันที่ 17 ธันวาคม 2569 ตั้งแต่เวลา 08.00 - 09.00 น.', 'กำหนดการ', 'cyan', 1],
                ['การจัดแสดงและนำเสนอผลงาน Capstone Mini-Projects 6 แทร็ก', 'ร่วมรับฟังการ Pitching โครงงานนวัตกรรมสิ่งแวดล้อมและเกษตรดิจิทัล วันที่ 19 ธันวาคม 2569 ชิงทุนการศึกษาและโล่รางวัลจากคณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี', 'รางวัล', 'amber', 1]
            ];
            $ins_a = $db->prepare("INSERT INTO announcements (title, content, badge, badge_color, is_active) VALUES (?, ?, ?, ?, ?)");
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

        // Evaluations Table
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

        $ev_cnt = $db->querySingle("SELECT COUNT(*) FROM evaluations");
        if ($ev_cnt == 0 && file_exists($data_dir . '/evaluations.json')) {
            $ev_json = json_decode(file_get_contents($data_dir . '/evaluations.json'), true);
            if (is_array($ev_json) && count($ev_json) > 0) {
                $ins_ev = $db->prepare("INSERT INTO evaluations (id, participant_id, fullname, organization, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, avg_score, comment, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($ev_json as $ev) {
                    $ins_ev->bindValue(1, $ev['id'] ?? null, SQLITE3_INTEGER);
                    $ins_ev->bindValue(2, $ev['participant_id'] ?? 0, SQLITE3_INTEGER);
                    $ins_ev->bindValue(3, $ev['fullname'] ?? '', SQLITE3_TEXT);
                    $ins_ev->bindValue(4, $ev['organization'] ?? '', SQLITE3_TEXT);
                    for ($k = 1; $k <= 10; $k++) {
                        $ins_ev->bindValue($k + 4, $ev["q$k"] ?? 5, SQLITE3_INTEGER);
                    }
                    $ins_ev->bindValue(15, floatval($ev['avg_score'] ?? 5.0), SQLITE3_FLOAT);
                    $ins_ev->bindValue(16, $ev['comment'] ?? '', SQLITE3_TEXT);
                    $ins_ev->bindValue(17, $ev['created_at'] ?? date('Y-m-d H:i:s'), SQLITE3_TEXT);
                    @$ins_ev->execute();
                }
            }
        }

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
    
    // Check if we have received a direct POST from ESP32 recently (< 30 seconds)
    $last_direct_time = isset($state['board']['last_direct_post_time']) ? intval($state['board']['last_direct_post_time']) : 0;
    $is_direct_live = (time() - $last_direct_time) < 30;

    if ($is_direct_live) {
        $state['board']['cloud_status'] = 'ESP32 DIRECT (ONLINE)';
        $state['board']['data_source'] = 'DIRECT_ESP32_PUSH (' . ($state['board']['ip_address'] ?? '192.168.0.111') . ')';
        $state['board']['is_live'] = true;
    } else {
        // Fallback: Query Cloud Telemetry Hub (http://14.207.141.164:8000) only when direct push is not active
        $now_micro = microtime(true);
        $cloud_rate_file = $data_dir . '/last_cloud_sync.txt';
        $last_sync_time = file_exists($cloud_rate_file) ? floatval(file_get_contents($cloud_rate_file)) : 0;
        
        if (($now_micro - $last_sync_time) >= 1.0) {
            file_put_contents($cloud_rate_file, strval($now_micro));
            
            $cloud_url = 'http://14.207.141.164:8000/api/telemetry/latest';
            $ctx = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 1.5,
                    'header' => "User-Agent: Edge-xAI-4DAgriEnvi-Sync/1.0\r\nAccept: application/json\r\n"
                ]
            ]);
            
            $raw_cloud = @file_get_contents($cloud_url, false, $ctx);
            if ($raw_cloud !== false) {
                $cloud_data = json_decode($raw_cloud, true);
                if (is_array($cloud_data) && isset($cloud_data['id'])) {
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
    $input = !empty($input_json) ? $input_json : $_POST;
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

        // Auto-sync to Google Sheet if enabled
        $gs_payload = [
            'id' => $new_id,
            'created_at' => date('Y-m-d H:i:s'),
            'prefix' => $prefix,
            'fullname' => $fullname,
            'target_group' => $target_group,
            'organization' => $organization,
            'phone' => $phone,
            'email' => $email,
            'project_track' => $project_track,
            'pre_score' => $pre_score,
            'post_score' => $post_score,
            'status' => $status
        ];
        $gs_cfg = get_google_sheets_config($gs_config_file);
        if (!empty($gs_cfg['enabled']) && !empty($gs_cfg['auto_sync_on_register'])) {
            sync_to_google_sheets($gs_payload, $gs_config_file);
        }

        // Auto-notify to LINE if enabled
        $line_cfg = get_line_config($line_config_file);
        if (!empty($line_cfg['enabled']) && !empty($line_cfg['auto_notify_on_register'])) {
            send_line_registration_notify($gs_payload, $line_config_file);
        }
        
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

        // Auto-sync to Google Sheet if enabled
        $gs_cfg = get_google_sheets_config($gs_config_file);
        if (!empty($gs_cfg['enabled']) && !empty($gs_cfg['auto_sync_on_register'])) {
            sync_to_google_sheets($new_record, $gs_config_file);
        }

        // Auto-notify to LINE if enabled
        $line_cfg = get_line_config($line_config_file);
        if (!empty($line_cfg['enabled']) && !empty($line_cfg['auto_notify_on_register'])) {
            send_line_registration_notify($new_record, $line_config_file);
        }

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

// 8.8 CLEAR ALL PARTICIPANTS (POST ?action=clear_all_participants)
if ($action === 'clear_all_participants') {
    if ($db) {
        $db->exec("DELETE FROM participants");
        $db->exec("DELETE FROM sqlite_sequence WHERE name='participants'");
        sync_participants_json($db, $json_backup);
        echo json_encode(['status' => 'success', 'message' => 'ล้างข้อมูลผู้ลงทะเบียนทั้งหมดเรียบร้อยแล้ว (0 รายการ)']);
        exit();
    } else {
        @file_put_contents($json_backup, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'ล้างข้อมูลผู้ลงทะเบียนทั้งหมดเรียบร้อยแล้ว']);
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
        'cert_ref_prefix' => trim($input['cert_ref_prefix'] ?? 'Edge xAI : 4DAgriEnvi'),
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

// =========================================================================
// 12. SCHEDULES (GET / POST ?action=get_schedules, save_schedule, delete_schedule)
// =========================================================================
if ($action === 'get_schedules') {
    $file = $data_dir . '/schedules.json';
    $schedules = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
    echo json_encode(['status' => 'success', 'schedules' => $schedules]);
    exit();
}

if ($action === 'save_schedule') {
    $file = $data_dir . '/schedules.json';
    $schedules = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $id = !empty($input['id']) ? (string)$input['id'] : 'sch_' . time() . '_' . rand(100, 999);
    $days = isset($input['days']) ? (is_array($input['days']) ? $input['days'] : explode(',', (string)$input['days'])) : [0,1,2,3,4,5,6];
    $days = array_values(array_map('intval', (array)$days));
    $item = [
        'id' => $id,
        'relay' => intval($input['relay'] ?? 1),
        'time' => (string)($input['time'] ?? '06:00'),
        'days' => $days,
        'duration' => intval($input['duration'] ?? 0),
        'action' => (string)($input['action_type'] ?? ($input['action'] ?? 'on')),
        'enabled' => !empty($input['enabled']) && ($input['enabled'] == '1' || $input['enabled'] === true || $input['enabled'] === 'true') ? 1 : 0,
        'name' => (string)($input['name'] ?? ''),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    $found = false;
    foreach ($schedules as &$s) {
        if (($s['id'] ?? '') === $id) {
            $s = $item;
            $found = true;
            break;
        }
    }
    unset($s);
    if (!$found) $schedules[] = $item;
    @file_put_contents($file, json_encode($schedules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success', 'message' => 'บันทึกตารางเวลาเรียบร้อยแล้ว', 'schedule' => $item]);
    exit();
}

if ($action === 'delete_schedule') {
    $file = $data_dir . '/schedules.json';
    $schedules = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
    $id = (string)($_REQUEST['id'] ?? ($input_json['id'] ?? ''));
    $schedules = array_values(array_filter($schedules, function($s) use ($id) {
        return ($s['id'] ?? '') !== $id;
    }));
    @file_put_contents($file, json_encode($schedules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success', 'message' => 'ลบตารางเวลาเรียบร้อยแล้ว']);
    exit();
}

// =========================================================================
// 13. AUDIT LOG (GET ?action=get_audit)
// =========================================================================
if ($action === 'get_audit') {
    $file = $data_dir . '/audit_log.json';
    $limit = intval($_GET['limit'] ?? 80);
    $log = file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
    if (empty($log)) {
        $log = [
            ['time' => date('Y-m-d H:i:s'), 'type' => 'system', 'detail' => 'ระบบ HandySense LEQs เริ่มทำงาน', 'src' => 'server']
        ];
    }
    $log = array_slice($log, 0, $limit);
    echo json_encode(['status' => 'success', 'log' => $log]);
    exit();
}

// =========================================================================
// 14. ALERTS & PROFILES (GET ?action=get_alerts, get_profiles, set_profile)
// =========================================================================
if ($action === 'get_alerts') {
    $alerts = [];
    echo json_encode(['status' => 'success', 'alerts' => $alerts]);
    exit();
}

if ($action === 'get_profiles') {
    $profiles = [
        'general' => [
            'id' => 'general',
            'name' => 'พืชทั่วไป',
            'note' => 'เกณฑ์มาตรฐานโรงเรือนอัจฉริยะ',
            'thresholds' => [
                'temp' => ['min' => 20, 'max' => 35],
                'humidity' => ['min' => 40, 'max' => 90],
                'soil_moisture' => ['min' => 30, 'max' => 80],
                'vpd' => ['min' => 0.4, 'max' => 1.6],
                'soil_ph' => ['min' => 5.5, 'max' => 7.5]
            ]
        ],
        'durian' => [
            'id' => 'durian',
            'name' => 'ทุเรียน (Durian)',
            'note' => 'ควบคุมความชื้นดินและ VPD แม่นยำ',
            'thresholds' => [
                'temp' => ['min' => 24, 'max' => 34],
                'humidity' => ['min' => 65, 'max' => 85],
                'soil_moisture' => ['min' => 45, 'max' => 70],
                'vpd' => ['min' => 0.8, 'max' => 1.3],
                'soil_ph' => ['min' => 5.5, 'max' => 6.5]
            ]
        ],
        'melon' => [
            'id' => 'melon',
            'name' => 'เมลอน (Melon)',
            'note' => 'ควบคุมอุณหภูมิและความชื้นสัมพัทธ์',
            'thresholds' => [
                'temp' => ['min' => 22, 'max' => 32],
                'humidity' => ['min' => 55, 'max' => 75],
                'soil_moisture' => ['min' => 40, 'max' => 65],
                'vpd' => ['min' => 0.9, 'max' => 1.4],
                'soil_ph' => ['min' => 6.0, 'max' => 6.8]
            ]
        ],
        'hydro' => [
            'id' => 'hydro',
            'name' => 'ผักไฮโดรโปนิกส์ (Hydroponics)',
            'note' => 'ผักสลัด กรีนโอ๊ค เรดโอ๊ค',
            'thresholds' => [
                'temp' => ['min' => 18, 'max' => 28],
                'humidity' => ['min' => 50, 'max' => 75],
                'soil_moisture' => ['min' => 50, 'max' => 85],
                'vpd' => ['min' => 0.6, 'max' => 1.2],
                'soil_ph' => ['min' => 5.8, 'max' => 6.5]
            ]
        ]
    ];
    $active_file = $data_dir . '/active_profile.txt';
    $active = file_exists($active_file) ? trim(file_get_contents($active_file)) : 'general';
    echo json_encode(['status' => 'success', 'active' => $active, 'profiles' => $profiles]);
    exit();
}

if ($action === 'set_profile') {
    $id = trim($_REQUEST['id'] ?? ($input_json['id'] ?? 'general'));
    $active_file = $data_dir . '/active_profile.txt';
    @file_put_contents($active_file, $id);
    echo json_encode(['status' => 'success', 'message' => 'บันทึกโปรไฟล์พืชแล้ว', 'active' => $id]);
    exit();
}

// =========================================================================
// GOOGLE SHEETS ACTIONS
// =========================================================================
if ($action === 'get_google_sheets_config') {
    $cfg = get_google_sheets_config($gs_config_file);
    echo json_encode(['status' => 'success', 'config' => $cfg]);
    exit();
}

if ($action === 'save_google_sheets_config') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $cfg = get_google_sheets_config($gs_config_file);
    
    if (isset($input['enabled'])) $cfg['enabled'] = !empty($input['enabled']);
    if (isset($input['webhook_url'])) $cfg['webhook_url'] = trim($input['webhook_url']);
    if (isset($input['sheet_url'])) $cfg['sheet_url'] = trim($input['sheet_url']);
    if (isset($input['auto_sync_on_register'])) $cfg['auto_sync_on_register'] = !empty($input['auto_sync_on_register']);
    
    save_google_sheets_config($gs_config_file, $cfg);
    echo json_encode(['status' => 'success', 'message' => 'บันทึกการตั้งค่า Google Sheets สำเร็จ', 'config' => $cfg]);
    exit();
}

// 8.10.2 LINE NOTIFICATION CONFIG & TEST
if ($action === 'get_line_config') {
    $cfg = get_line_config($line_config_file);
    echo json_encode(['status' => 'success', 'config' => $cfg]);
    exit();
}

if ($action === 'save_line_config') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $cfg = get_line_config($line_config_file);

    if (isset($input['enabled'])) $cfg['enabled'] = !empty($input['enabled']);
    if (isset($input['channel_access_token'])) $cfg['channel_access_token'] = trim($input['channel_access_token']);
    if (isset($input['target_id'])) $cfg['target_id'] = trim($input['target_id']);
    if (isset($input['notify_mode'])) $cfg['notify_mode'] = trim($input['notify_mode']);
    if (isset($input['auto_notify_on_register'])) $cfg['auto_notify_on_register'] = !empty($input['auto_notify_on_register']);

    save_line_config($line_config_file, $cfg);
    echo json_encode(['status' => 'success', 'message' => 'บันทึกการตั้งค่า LINE สำเร็จ', 'config' => $cfg]);
    exit();
}

if ($action === 'test_line_notify') {
    $test_participant = [
        'prefix' => 'นาย',
        'fullname' => 'ทดสอบระบบ แจ้งเตือนไลน์',
        'target_group' => 'ครูและบุคลากรทางการศึกษา',
        'organization' => 'มหาวิทยาลัยราชภัฏรำไพพรรณี',
        'phone' => '093-742-2654',
        'email' => 'chewa.t@rbru.ac.th',
        'project_track' => 'Track A — Smart Agriculture & Sensor Hub'
    ];
    $res = send_line_registration_notify($test_participant, $line_config_file);
    echo json_encode($res);
    exit();
}

// 8.10.3 RAG DATA BUNDLE FOR AI BOT (GET / POST ?action=rag_bundle)
if ($action === 'rag_bundle') {
    // 1. Participants count & list
    $participants = [];
    if ($db) {
        $res = $db->query("SELECT id, prefix, fullname, organization, target_group, project_track, status, created_at FROM participants ORDER BY id DESC");
        if ($res) {
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) $participants[] = $row;
        }
    }
    if (empty($participants) && file_exists($json_backup)) {
        $participants = json_decode(file_get_contents($json_backup), true) ?: [];
    }

    // 2. Announcements
    $announcements = [];
    if ($db) {
        $res = $db->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 5");
        if ($res) {
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) $announcements[] = $row;
        }
    }

    // 3. Telemetry latest reading
    $telemetry_latest = null;
    if ($db) {
        $res = $db->query("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT 1");
        if ($res) {
            $telemetry_latest = $res->fetchArray(SQLITE3_ASSOC);
        }
    }

    // 4. Evaluations stats
    $eval_avg = 4.85;
    $eval_count = 0;
    if ($db) {
        $eval_count = $db->querySingle("SELECT COUNT(*) FROM evaluations") ?: 0;
        $eval_avg = $db->querySingle("SELECT AVG(avg_score) FROM evaluations") ?: 4.85;
    }

    $bundle = [
        'success' => true,
        'project' => [
            'title' => 'Edge xAI : 4DAgriEnvi',
            'full_title' => 'โครงการฝึกอบรมเชิงปฏิบัติการปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอทีเพื่อการเกษตรและสิ่งแวดล้อม 4 มิติ (Edge xAI : 4DAgriEnvi)',
            'institution' => 'คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี',
            'coordinator' => 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา',
            'phone' => '093-742-2654',
            'email' => 'chewa.t@rbru.ac.th',
            'date_range' => 'ธันวาคม 2569 - มกราคม 2570',
            'venue' => 'ศูนย์วิทยาศาสตร์และเทคโนโลยี / ห้องปฏิบัติการคอมพิวเตอร์และนวัตกรรม มรภ.รำไพพรรณี',
            'line_oa' => '@888qrcgj (Edge-xAI)'
        ],
        'kpi' => [
            'total_registered' => count($participants),
            'target_participants' => 40,
            'completion_percent' => count($participants) >= 40 ? 100 : round((count($participants) / 40) * 100, 1),
            'satisfaction_score' => round($eval_avg, 2),
            'evaluations_count' => $eval_count
        ],
        'participants' => $participants,
        'announcements' => $announcements,
        'telemetry_latest' => $telemetry_latest,
        'modules' => [
            ['id' => 1, 'name' => 'Module 1: Fundamental IoT & Microcontroller', 'desc' => 'สถาปัตยกรรม ESP32-S3 Dual-Core, GPIO, ADC, PWM และระบบการเชื่อมต่อ Wi-Fi / BLE'],
            ['id' => 2, 'name' => 'Module 2: Agricultural Sensor Hub & Industrial RS485', 'desc' => 'การเชื่อมต่อเซ็นเซอร์วัดดิน NPK 7-in-1, pH, ความชื้น, อุณหภูมิ และ Modbus RTU Protocol'],
            ['id' => 3, 'name' => 'Module 3: Edge AI & Machine Learning on Embedded', 'desc' => 'โมเดลทำนายการดูดซับธาตุอาหารและประเมินสภาวะแวดล้อมด้วย Edge TinyML'],
            ['id' => 4, 'name' => 'Module 4: 4D Agri-Environmental Data Modeling', 'desc' => 'การจำลองมิติสิ่งแวดล้อม 4 มิติ (อุณหภูมิ, ความชื้น, แสง, สารอาหารดิน)'],
            ['id' => 5, 'name' => 'Module 5: Smart Actuators & Relay Automation', 'desc' => 'ระบบควบคุมปั๊มน้ำ, วาล์วไฟฟ้า, พัดลมระบายอากาศ และไฟช่วยสังเคราะห์แสง'],
            ['id' => 6, 'name' => 'Module 6: Cloud Telemetry & Google Sheets Real-time', 'desc' => 'การสตรีมข้อมูลขึ้นคลาวด์, แจ้งเตือนผ่าน LINE Messaging API และ Google Sheets Sync'],
            ['id' => 7, 'name' => 'Module 7: Capstone AIoT Prototype & Pitching', 'desc' => 'การบูรณาการโครงงานเกษตรอัจฉริยะ 4 แทร็ก และการนำเสนอผลงานระดับมืออาชีพ']
        ],
        'tracks' => [
            'Track A' => 'Smart Agriculture & Sensor Hub (ระบบสถานีตรวจวัดและควบคุมโรงเรือนเกษตรอัจฉริยะ)',
            'Track B' => 'Microclimate & Bio-Environment (ระบบเฝ้าระวังจุลภูมิอากาศและชีวสิ่งแวดล้อมพืช)',
            'Track C' => 'Soil & Fertilizer AI (ระบบ AI วิเคราะห์สุขภาพดิน ธาตุอาหาร NPK และคำนวณปุ๋ยแม่นยำ)',
            'Track D' => 'Smart Vision & Edge AI (ระบบคอมพิวเตอร์วิชันและ AI จำแนกโรคพืชบนกล้อง ESP32-CAM)'
        ]
    ];

    echo json_encode($bundle, JSON_UNESCAPED_UNICODE);
    exit();
}

// 8.11 GET ALL DATABASE TABLES (GET / POST ?action=get_all_database_tables)
if ($action === 'get_all_database_tables') {
    // 1. Participants
    $participants = [];
    if ($db) {
        $res = $db->query("SELECT * FROM participants ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $participants[] = $row;
    } else {
        $participants = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
    }

    // 2. Evaluations
    $evaluations = [];
    if ($db) {
        $res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $evaluations[] = $row;
    }
    if (empty($evaluations) && file_exists($data_dir . '/evaluations.json')) {
        $evaluations = json_decode(file_get_contents($data_dir . '/evaluations.json'), true) ?: [];
    }

    // 3. Telemetry Logs (Recent 500 logs)
    $telemetry_logs = [];
    if ($db) {
        $res = $db->query("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT 500");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $telemetry_logs[] = $row;
    }

    // 4. Relay States
    $relay_states = [];
    if ($db) {
        $res = $db->query("SELECT * FROM relay_states ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $relay_states[] = $row;
    }
    if (empty($relay_states)) {
        $relay_states = [
            ['id' => 1, 'name' => 'ปั๊มน้ำหลักแปลงทุเรียน (Main Pump)', 'state' => 0, 'gpio' => 4, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'วาล์วม่านหมอกลดอุณหภูมิ (Misting Valve)', 'state' => 0, 'gpio' => 5, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'พัดลมระบายอากาศโรงเรือน (Exhaust Fan)', 'state' => 0, 'gpio' => 6, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'หลอดไฟส่องสว่างแปลงพืช (Grow Light)', 'state' => 0, 'gpio' => 7, 'updated_at' => date('Y-m-d H:i:s')],
        ];
    }

    // 5. Announcements
    $announcements = [];
    if ($db) {
        $res = $db->query("SELECT * FROM announcements ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $announcements[] = $row;
    }
    if (empty($announcements) && file_exists($data_dir . '/announcements.json')) {
        $announcements = json_decode(file_get_contents($data_dir . '/announcements.json'), true) ?: [];
    }

    // 6. Current Real-Time Telemetry
    $current_telemetry = get_current_state($telemetry_file, $default_state);

    echo json_encode([
        'status' => 'success',
        'timestamp' => date('Y-m-d H:i:s'),
        'tables' => [
            'participants' => $participants,
            'evaluations' => $evaluations,
            'telemetry_logs' => $telemetry_logs,
            'relay_states' => $relay_states,
            'announcements' => $announcements,
            'current_telemetry' => $current_telemetry
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($action === 'sync_all_to_google_sheets') {
    $cfg = get_google_sheets_config($gs_config_file);
    if (empty($cfg['webhook_url'])) {
        echo json_encode(['status' => 'error', 'message' => 'ยังไม่ได้ระบุ Webhook URL ของ Google Apps Script']);
        exit();
    }
    
    // 1. Gather all participants
    $participants = [];
    if ($db) {
        $res = $db->query("SELECT * FROM participants ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $participants[] = $row;
    } else {
        $participants = file_exists($json_backup) ? json_decode(file_get_contents($json_backup), true) : [];
    }

    // 2. Gather Evaluations
    $evaluations = [];
    if ($db) {
        $res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $evaluations[] = $row;
    }
    if (empty($evaluations) && file_exists($data_dir . '/evaluations.json')) {
        $evaluations = json_decode(file_get_contents($data_dir . '/evaluations.json'), true) ?: [];
    }

    // 3. Gather Telemetry Logs (Recent 500)
    $telemetry_logs = [];
    if ($db) {
        $res = $db->query("SELECT * FROM telemetry_logs ORDER BY id DESC LIMIT 500");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $telemetry_logs[] = $row;
    }

    // 4. Gather Relay States
    $relay_states = [];
    if ($db) {
        $res = $db->query("SELECT * FROM relay_states ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $relay_states[] = $row;
    }
    if (empty($relay_states)) {
        $relay_states = [
            ['id' => 1, 'name' => 'ปั๊มน้ำหลักแปลงทุเรียน (Main Pump)', 'state' => 0, 'gpio' => 4, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'วาล์วม่านหมอกลดอุณหภูมิ (Misting Valve)', 'state' => 0, 'gpio' => 5, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'พัดลมระบายอากาศโรงเรือน (Exhaust Fan)', 'state' => 0, 'gpio' => 6, 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'หลอดไฟส่องสว่างแปลงพืช (Grow Light)', 'state' => 0, 'gpio' => 7, 'updated_at' => date('Y-m-d H:i:s')],
        ];
    }

    // 5. Gather Announcements
    $announcements = [];
    if ($db) {
        $res = $db->query("SELECT * FROM announcements ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) $announcements[] = $row;
    }
    if (empty($announcements) && file_exists($data_dir . '/announcements.json')) {
        $announcements = json_decode(file_get_contents($data_dir . '/announcements.json'), true) ?: [];
    }

    // 6. Current Real-Time Telemetry
    $current_telemetry = get_current_state($telemetry_file, $default_state);

    $payload = [
        'action' => 'sync_all_databases',
        'timestamp' => date('Y-m-d H:i:s'),
        'tables' => [
            'participants' => $participants,
            'evaluations' => $evaluations,
            'telemetry_logs' => $telemetry_logs,
            'relay_states' => $relay_states,
            'announcements' => $announcements,
            'current_telemetry' => $current_telemetry
        ],
        // Backward compatibility
        'count' => count($participants),
        'participants' => $participants
    ];

    $sync_res = sync_to_google_sheets($payload, $gs_config_file);

    $cfg['last_sync'] = date('Y-m-d H:i:s');
    $cfg['last_sync_count'] = count($participants);
    save_google_sheets_config($gs_config_file, $cfg);

    echo json_encode([
        'status' => 'success',
        'message' => 'ซิงก์ข้อมูลทุกฐานข้อมูล (' . count($participants) . ' ผู้ลงทะเบียน, ' . count($evaluations) . ' ผลประเมิน, ' . count($telemetry_logs) . ' บันทึก IoT, ' . count($announcements) . ' ประกาศ) ขึ้น Google Sheets สำเร็จ',
        'synced_count' => count($participants),
        'remote_result' => $sync_res
    ]);
    exit();
}

if ($action === 'test_google_sheets_webhook') {
    $input = !empty($input_json) ? $input_json : $_POST;
    $url = trim($input['webhook_url'] ?? '');
    if (empty($url)) {
        $cfg = get_google_sheets_config($gs_config_file);
        $url = $cfg['webhook_url'] ?? '';
    }

    if (empty($url)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอก Webhook URL เพื่อทดสอบ']);
        exit();
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo json_encode(['status' => 'error', 'message' => 'การเชื่อมต่อล้มเหลว: ' . $err]);
        exit();
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'ติดต่อ Google Apps Script สำเร็จ (HTTP ' . $http_code . ')',
        'response' => $res
    ]);
    exit();
}


// ============================================================================
// 9. SITE CONTENT & GENERAL CMS SETTINGS
// ============================================================================

// 9.1 GET SITE CONTENT (GET / POST ?action=get_site_content)
if ($action === 'get_site_content') {
    $content_file = $data_dir . '/site_content.json';
    if (!file_exists($content_file)) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์ site_content.json']);
        exit();
    }
    $content = json_decode(file_get_contents($content_file), true);
    echo json_encode([
        'status' => 'success',
        'data' => $content,
        'updated_at' => date('Y-m-d H:i:s', filemtime($content_file))
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 9.2 SAVE SITE CONTENT (POST ?action=save_site_content)
if ($action === 'save_site_content') {
    $content_file = $data_dir . '/site_content.json';
    $input = !empty($input_json) ? $input_json : $_POST;
    
    $content = $input['content'] ?? $input;
    if (is_string($content)) {
        $content = json_decode($content, true);
    }
    
    if (empty($content) || !is_array($content)) {
        echo json_encode(['status' => 'error', 'message' => 'รูปแบบข้อมูลไม่ถูกต้อง']);
        exit();
    }
    
    // Remove action parameter if passed inside content
    unset($content['action']);
    
    $json_str = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (file_put_contents($content_file, $json_str) !== false) {
        @chmod($content_file, 0666);
        echo json_encode([
            'status' => 'success',
            'message' => 'บันทึกเนื้อหาหน้าเว็บเรียบร้อยแล้ว',
            'data' => $content
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเขียนไฟล์ site_content.json ได้ ตรวจสอบสิทธิ์การเข้าถึงไฟล์']);
    }
    exit();
}

// ============================================================================
// 10. DATABASE CRUD MANAGER (SQLITE3 DYNAMIC TABLE INSPECTOR & ROW ACTIONS)
// ============================================================================

$allowed_tables = ['participants', 'announcements', 'evaluations', 'telemetry_logs', 'relay_states'];

// 10.1 GET ALL TABLES WITH SCHEMA & STATS (GET ?action=db_get_tables)
if ($action === 'db_get_tables') {
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล SQLite ได้']);
        exit();
    }

    $tables_info = [];
    foreach ($allowed_tables as $tbl) {
        // Count rows
        $cnt_res = $db->querySingle("SELECT COUNT(*) FROM `{$tbl}`");
        $count = $cnt_res !== false ? intval($cnt_res) : 0;

        // Get Schema columns
        $cols = [];
        $res = $db->query("PRAGMA table_info(`{$tbl}`)");
        while ($col = $res->fetchArray(SQLITE3_ASSOC)) {
            $cols[] = [
                'cid' => $col['cid'],
                'name' => $col['name'],
                'type' => $col['type'],
                'notnull' => (bool)$col['notnull'],
                'dflt_value' => $col['dflt_value'],
                'pk' => (bool)$col['pk']
            ];
        }

        $tables_info[$tbl] = [
            'name' => $tbl,
            'row_count' => $count,
            'columns' => $cols
        ];
    }

    echo json_encode([
        'status' => 'success',
        'database_file' => basename($db_file),
        'tables' => $tables_info
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 10.2 GET TABLE ROWS WITH SEARCH, SORT & PAGINATION (GET / POST ?action=db_get_table_rows)
if ($action === 'db_get_table_rows') {
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล SQLite ได้']);
        exit();
    }

    $input = !empty($input_json) ? $input_json : $_REQUEST;
    $tbl = trim($input['table'] ?? 'participants');
    if (!in_array($tbl, $allowed_tables, true)) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่อนุญาตให้เข้าถึงตาราง: ' . htmlspecialchars($tbl)]);
        exit();
    }

    $search = trim($input['search'] ?? '');
    $limit = isset($input['limit']) ? max(1, min(200, intval($input['limit']))) : 50;
    $offset = isset($input['offset']) ? max(0, intval($input['offset'])) : 0;
    $order_by = trim($input['order_by'] ?? 'id');
    $order_dir = strtoupper(trim($input['order_dir'] ?? 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

    // Fetch column schema
    $cols = [];
    $col_names = [];
    $text_cols = [];
    $res = $db->query("PRAGMA table_info(`{$tbl}`)");
    while ($col = $res->fetchArray(SQLITE3_ASSOC)) {
        $cols[] = $col;
        $col_names[] = $col['name'];
        if (stripos($col['type'], 'TEXT') !== false || stripos($col['type'], 'CHAR') !== false) {
            $text_cols[] = $col['name'];
        }
    }

    if (!in_array($order_by, $col_names, true)) {
        $order_by = 'id';
    }

    // Build WHERE clause for search
    $where_sql = "";
    if ($search !== '') {
        $search_escaped = SQLite3::escapeString($search);
        $search_clauses = [];
        foreach ($col_names as $c) {
            $search_clauses[] = "`{$c}` LIKE '%{$search_escaped}%'";
        }
        if (!empty($search_clauses)) {
            $where_sql = "WHERE (" . implode(" OR ", $search_clauses) . ")";
        }
    }

    // Count filtered rows
    $total_count = $db->querySingle("SELECT COUNT(*) FROM `{$tbl}` {$where_sql}");
    $total_count = $total_count !== false ? intval($total_count) : 0;

    // Fetch rows
    $sql = "SELECT * FROM `{$tbl}` {$where_sql} ORDER BY `{$order_by}` {$order_dir} LIMIT {$limit} OFFSET {$offset}";
    $rows = [];
    $res = $db->query($sql);
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'table' => $tbl,
        'total_count' => $total_count,
        'limit' => $limit,
        'offset' => $offset,
        'order_by' => $order_by,
        'order_dir' => $order_dir,
        'columns' => $cols,
        'rows' => $rows
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 10.3 SAVE ROW (INSERT OR UPDATE) (POST ?action=db_save_row)
if ($action === 'db_save_row') {
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล SQLite ได้']);
        exit();
    }

    $input = !empty($input_json) ? $input_json : $_POST;
    $tbl = trim($input['table'] ?? '');
    if (!in_array($tbl, $allowed_tables, true)) {
        echo json_encode(['status' => 'error', 'message' => 'ตารางไม่ถูกต้อง: ' . htmlspecialchars($tbl)]);
        exit();
    }

    $row_data = $input['data'] ?? [];
    if (is_string($row_data)) {
        $row_data = json_decode($row_data, true);
    }

    if (empty($row_data) || !is_array($row_data)) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่มีข้อมูลสำหรับบันทึก']);
        exit();
    }

    // Get table schema
    $col_types = [];
    $res = $db->query("PRAGMA table_info(`{$tbl}`)");
    while ($col = $res->fetchArray(SQLITE3_ASSOC)) {
        $col_types[$col['name']] = strtoupper($col['type']);
    }

    $id = isset($row_data['id']) && $row_data['id'] !== '' ? intval($row_data['id']) : null;
    $is_update = false;

    if ($id !== null) {
        // Check if row exists
        $exists = $db->querySingle("SELECT id FROM `{$tbl}` WHERE id = {$id}");
        if ($exists) {
            $is_update = true;
        }
    }

    if ($is_update) {
        // UPDATE
        $set_parts = [];
        $stmt_params = [];
        foreach ($row_data as $key => $val) {
            if ($key === 'id') continue;
            if (!isset($col_types[$key])) continue; // Skip columns not in schema

            $set_parts[] = "`{$key}` = :{$key}";
            $stmt_params[":{$key}"] = $val;
        }

        if (empty($set_parts)) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่มีคอลัมน์ที่เปลี่ยนแปลง']);
            exit();
        }

        $sql = "UPDATE `{$tbl}` SET " . implode(", ", $set_parts) . " WHERE id = :target_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':target_id', $id, SQLITE3_INTEGER);
        foreach ($stmt_params as $param => $val) {
            $stmt->bindValue($param, $val);
        }
        $result = $stmt->execute();
        if ($result) {
            // Trigger JSON sync
            if ($tbl === 'participants') sync_participants_json($db, $json_backup);
            if ($tbl === 'announcements') sync_announcements_json($db, $data_dir . '/announcements.json');
            if ($tbl === 'evaluations') {
                $eval_res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
                $evs = [];
                while ($ev = $eval_res->fetchArray(SQLITE3_ASSOC)) $evs[] = $ev;
                file_put_contents($data_dir . '/evaluations.json', json_encode($evs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'อัปเดตข้อมูลระเบียน ID #' . $id . ' ในตาราง ' . $tbl . ' สำเร็จ',
                'id' => $id,
                'table' => $tbl
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปเดต: ' . $db->lastErrorMsg()]);
        }
    } else {
        // INSERT
        $cols = [];
        $placeholders = [];
        $stmt_params = [];
        foreach ($row_data as $key => $val) {
            if ($key === 'id' && ($val === null || $val === '')) continue;
            if (!isset($col_types[$key])) continue;

            $cols[] = "`{$key}`";
            $placeholders[] = ":{$key}";
            $stmt_params[":{$key}"] = $val;
        }

        if (empty($cols)) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่มีคอลัมน์ที่ระบุ']);
            exit();
        }

        $sql = "INSERT INTO `{$tbl}` (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $stmt = $db->prepare($sql);
        foreach ($stmt_params as $param => $val) {
            $stmt->bindValue($param, $val);
        }
        $result = $stmt->execute();
        if ($result) {
            $new_id = $db->lastInsertRowID();
            // Trigger JSON sync
            if ($tbl === 'participants') sync_participants_json($db, $json_backup);
            if ($tbl === 'announcements') sync_announcements_json($db, $data_dir . '/announcements.json');
            if ($tbl === 'evaluations') {
                $eval_res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
                $evs = [];
                while ($ev = $eval_res->fetchArray(SQLITE3_ASSOC)) $evs[] = $ev;
                file_put_contents($data_dir . '/evaluations.json', json_encode($evs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'เพิ่มข้อมูลระเบียนใหม่ (ID #' . $new_id . ') ในตาราง ' . $tbl . ' สำเร็จ',
                'id' => $new_id,
                'table' => $tbl
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . $db->lastErrorMsg()]);
        }
    }
    exit();
}

// 10.4 DELETE ROW (POST ?action=db_delete_row)
if ($action === 'db_delete_row') {
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล SQLite ได้']);
        exit();
    }

    $input = !empty($input_json) ? $input_json : $_POST;
    $tbl = trim($input['table'] ?? '');
    $id = isset($input['id']) ? intval($input['id']) : 0;

    if (!in_array($tbl, $allowed_tables, true)) {
        echo json_encode(['status' => 'error', 'message' => 'ตารางไม่ถูกต้อง: ' . htmlspecialchars($tbl)]);
        exit();
    }

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาระบุ ID ระเบียนที่ต้องการลบ']);
        exit();
    }

    $stmt = $db->prepare("DELETE FROM `{$tbl}` WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $res = $stmt->execute();

    if ($res) {
        if ($tbl === 'participants') sync_participants_json($db, $json_backup);
        if ($tbl === 'announcements') sync_announcements_json($db, $data_dir . '/announcements.json');
        if ($tbl === 'evaluations') {
            $eval_res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
            $evs = [];
            while ($ev = $eval_res->fetchArray(SQLITE3_ASSOC)) $evs[] = $ev;
            file_put_contents($data_dir . '/evaluations.json', json_encode($evs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'ลบข้อมูล ID #' . $id . ' จากตาราง ' . $tbl . ' เรียบร้อยแล้ว',
            'deleted_id' => $id,
            'table' => $tbl
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้: ' . $db->lastErrorMsg()]);
    }
    exit();
}

// 10.5 EXECUTE SAFE SQL QUERY (POST ?action=db_execute_sql)
if ($action === 'db_execute_sql') {
    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล SQLite ได้']);
        exit();
    }

    $input = !empty($input_json) ? $input_json : $_POST;
    $sql = trim($input['sql'] ?? '');

    if (empty($sql)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาระบุคำสั่ง SQL']);
        exit();
    }

    $is_select = preg_match('/^\s*(SELECT|PRAGMA|EXPLAIN)\s+/i', $sql);
    if ($is_select) {
        $res = $db->query($sql);
        if ($res === false) {
            echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $db->lastErrorMsg()]);
            exit();
        }
        $rows = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
            if (count($rows) >= 200) break; // Limit 200 rows for display
        }
        echo json_encode([
            'status' => 'success',
            'type' => 'SELECT',
            'count' => count($rows),
            'rows' => $rows
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $exec_res = $db->exec($sql);
        if ($exec_res === false) {
            echo json_encode(['status' => 'error', 'message' => 'SQL Exec Error: ' . $db->lastErrorMsg()]);
            exit();
        }
        $changes = $db->changes();
        // Sync JSON backups
        sync_participants_json($db, $json_backup);
        sync_announcements_json($db, $data_dir . '/announcements.json');

        echo json_encode([
            'status' => 'success',
            'type' => 'EXECUTE',
            'changes' => $changes,
            'message' => 'ดำเนินการคำสั่ง SQL สำเร็จ (' . $changes . ' แถวที่ได้รับผลกระทบ)'
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}

// Fallback
echo json_encode(['status' => 'error', 'message' => 'Invalid action specified']);



