<?php
/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - Unified RESTful API Gateway
 * ============================================================================
 * Handles multi-board discovery, telemetry ingestion, actuator control,
 * and customizable dashboard layouts.
 * ============================================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/db.php';
$db = getFleetDatabase();

// Ingest input
$rawInput = file_get_contents('php://input');
$inputJson = json_decode($rawInput, true) ?? [];
$action = $_GET['action'] ?? ($_POST['action'] ?? ($inputJson['action'] ?? 'get_boards'));

try {
    switch ($action) {

        // 1. Get all boards with latest telemetry
        case 'get_boards':
            $stmt = $db->query("
                SELECT b.*, 
                       t.temp_c, t.humidity_pct, t.vpd_kpa, t.dew_point_c, t.solar_lux, t.solar_wm2,
                       t.raw_soil_ph, t.raw_soil_moisture, t.raw_soil_ec, t.raw_soil_temp, t.raw_n, t.raw_p, t.raw_k,
                       t.stick_moisture, t.stick_ph, t.stick_adc,
                       t.ai_calibrated_ph, t.ai_fused_moisture, t.ai_avail_n, t.ai_avail_p, t.ai_avail_k,
                       t.ai_confidence, t.ai_agronomy_alert,
                       t.relay1_state, t.relay2_state, t.relay3_state, t.relay4_state,
                       t.updated_at AS telemetry_updated_at
                FROM boards b
                LEFT JOIN telemetry_latest t ON b.id = t.board_id
                ORDER BY b.id ASC
            ");
            $boards = $stmt->fetchAll();

            // Load live hardware telemetry from /leqs-workshop for the Master Demonstration Node
            $masterJsonFile = __DIR__ . '/../../leqs-workshop/data/telemetry_state.json';
            $masterLiveState = null;
            if (file_exists($masterJsonFile)) {
                $masterLiveState = json_decode(@file_get_contents($masterJsonFile), true);
            }

            foreach ($boards as &$b) {
                $isMaster = !empty($b['is_master']) || ($b['board_code'] ?? '') === 'MASTER-LEQS' || intval($b['id']) === 1;
                $b['is_master'] = $isMaster ? 1 : 0;
                $b['demo_url'] = '../leqs-workshop/dashboard/index.php';
                $b['mobile_url'] = '../leqs-workshop/mobile/index.php';
                $b['code'] = $b['board_code'] ?? ('ESP32-NODE-' . str_pad($b['id'], 2, '0', STR_PAD_LEFT));
                $b['relay_1'] = intval($b['relay1_state'] ?? 0);
                $b['relay_2'] = intval($b['relay2_state'] ?? 0);
                $b['relay_3'] = intval($b['relay3_state'] ?? 0);
                $b['relay_4'] = intval($b['relay4_state'] ?? 0);
                $b['telemetry'] = [
                    'air_temp' => isset($b['temp_c']) ? floatval($b['temp_c']) : 28.5,
                    'air_hum' => isset($b['humidity_pct']) ? floatval($b['humidity_pct']) : 65.0,
                    'vpd' => isset($b['vpd_kpa']) ? floatval($b['vpd_kpa']) : 1.25,
                    'dew_point' => isset($b['dew_point_c']) ? floatval($b['dew_point_c']) : 21.0,
                    'solar_radiation' => isset($b['solar_wm2']) ? floatval($b['solar_wm2']) : 270.0,
                    'lux' => isset($b['solar_lux']) ? floatval($b['solar_lux']) : 34000,
                    'soil_ph' => isset($b['raw_soil_ph']) ? floatval($b['raw_soil_ph']) : 8.5,
                    'soil_moisture' => isset($b['raw_soil_moisture']) ? floatval($b['raw_soil_moisture']) : 2.6,
                    'soil_ec' => isset($b['raw_soil_ec']) ? floatval($b['raw_soil_ec']) : 0.42,
                    'soil_temp' => isset($b['raw_soil_temp']) ? floatval($b['raw_soil_temp']) : 27.5,
                    'soil_n' => isset($b['raw_n']) ? floatval($b['raw_n']) : 0.0,
                    'soil_p' => isset($b['raw_p']) ? floatval($b['raw_p']) : 0.0,
                    'soil_k' => isset($b['raw_k']) ? floatval($b['raw_k']) : 0.0,
                    'surface_ph' => isset($b['stick_ph']) ? floatval($b['stick_ph']) : 3.03,
                    'surface_moist' => isset($b['stick_moisture']) ? floatval($b['stick_moisture']) : 60.7,
                    'ai_calibrated_ph' => isset($b['ai_calibrated_ph']) ? floatval($b['ai_calibrated_ph']) : 9.44,
                    'ai_nitrogen' => isset($b['ai_avail_n']) ? floatval($b['ai_avail_n']) : 21.8,
                    'ai_phosphorus' => isset($b['ai_avail_p']) ? floatval($b['ai_avail_p']) : 13.9,
                    'ai_potassium' => isset($b['ai_avail_k']) ? floatval($b['ai_avail_k']) : 8.0,
                    'ai_confidence' => isset($b['ai_confidence']) ? floatval($b['ai_confidence']) : 77.6,
                    'ai_alert' => $b['ai_agronomy_alert'] ?? '',
                    'rssi' => intval($b['rssi'] ?? -65),
                    'timestamp' => $b['telemetry_updated_at'] ?? date('Y-m-d H:i:s')
                ];

                // Override with real live hardware data if Master Node
                if ($isMaster && $masterLiveState && isset($masterLiveState['sensors'])) {
                    $ls = $masterLiveState['sensors'];
                    $lai = $masterLiveState['ai_calibrated'] ?? [];
                    $lrelays = $masterLiveState['relays'] ?? [];

                    $b['relay_1'] = intval($lrelays['1']['state'] ?? $b['relay_1']);
                    $b['relay_2'] = intval($lrelays['2']['state'] ?? $b['relay_2']);
                    $b['relay_3'] = intval($lrelays['3']['state'] ?? $b['relay_3']);
                    $b['relay_4'] = intval($lrelays['4']['state'] ?? $b['relay_4']);

                    $b['telemetry']['air_temp'] = round(floatval($ls['temperature'] ?? 24.4), 2);
                    $b['telemetry']['air_hum'] = round(floatval($ls['humidity'] ?? 65.7), 2);
                    $b['telemetry']['vpd'] = round(floatval($ls['vpd'] ?? 1.05), 2);
                    $b['telemetry']['dew_point'] = round(floatval($ls['dew_point'] ?? 17.6), 2);
                    $b['telemetry']['solar_radiation'] = round(floatval($ls['solar_radiation'] ?? 0.28), 2);
                    $b['telemetry']['lux'] = round(floatval($ls['par_lux'] ?? 35.8), 1);
                    $b['telemetry']['soil_ph'] = round(floatval($ls['soil_ph'] ?? 7.6), 2);
                    $b['telemetry']['soil_moisture'] = round(floatval($ls['soil_moisture'] ?? 0.0), 1);
                    $b['telemetry']['soil_ec'] = round(floatval($ls['soil_ec'] ?? 0.0), 1);
                    $b['telemetry']['soil_temp'] = round(floatval($ls['soil_temperature'] ?? 24.4), 1);
                    $b['telemetry']['soil_n'] = round(floatval($ls['nitrogen'] ?? 0.0), 1);
                    $b['telemetry']['soil_p'] = round(floatval($ls['phosphorus'] ?? 0.0), 1);
                    $b['telemetry']['soil_k'] = round(floatval($ls['potassium'] ?? 0.0), 1);
                    $b['telemetry']['surface_ph'] = round(floatval($ls['soil_stick_ph'] ?? 3.2), 2);
                    $b['telemetry']['surface_moist'] = round(floatval($ls['soil_stick_moisture'] ?? 60.7), 1);

                    $b['telemetry']['ai_calibrated_ph'] = round(floatval($lai['ph'] ?? 8.36), 2);
                    $b['telemetry']['ai_nitrogen'] = round(floatval($lai['nitrogen'] ?? 17.4), 1);
                    $b['telemetry']['ai_phosphorus'] = round(floatval($lai['phosphorus'] ?? 11.3), 1);
                    $b['telemetry']['ai_potassium'] = round(floatval($lai['potassium'] ?? 9.6), 1);
                    
                    $rawConf = floatval($lai['confidence'] ?? 0.745);
                    $b['telemetry']['ai_confidence'] = round($rawConf <= 1.0 ? ($rawConf * 100) : $rawConf, 1);
                    $b['telemetry']['ai_alert'] = 'บอร์ดสาธิตหลักทำงานปกติ • สตรีมมิ่งข้อมูลสดจากฮาร์ดแวร์ ESP32-S3 ATD3.5';
                    $b['telemetry']['timestamp'] = $masterLiveState['board']['telemetry_timestamp'] ?? date('Y-m-d H:i:s');
                }
            }
            unset($b);

            echo json_encode([
                'status' => 'success',
                'count' => count($boards),
                'boards' => $boards,
                'server_time' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;

        // 2. Add New Board
        case 'add_board':
            $name = trim($inputJson['name'] ?? $_POST['name'] ?? '');
            $code = trim($inputJson['board_code'] ?? $_POST['board_code'] ?? '');
            $zone = trim($inputJson['zone'] ?? $_POST['zone'] ?? 'แปลงทั่วไป');
            $ip = trim($inputJson['ip_address'] ?? $_POST['ip_address'] ?? '192.168.0.100');
            $port = intval($inputJson['port'] ?? $_POST['port'] ?? 8500);
            $model = trim($inputJson['model'] ?? $_POST['model'] ?? 'ESP32-S3 ATD3.5');
            $mac = trim($inputJson['mac_address'] ?? $_POST['mac_address'] ?? '');

            if (empty($name)) {
                throw new Exception('กรุณาระบุชื่อบอร์ด');
            }
            if (empty($code)) {
                $code = 'ESP32-' . strtoupper(substr(md5(uniqid()), 0, 6));
            }

            $stmt = $db->prepare("
                INSERT INTO boards (board_code, name, zone, ip_address, port, mac_address, model, status)
                VALUES (:code, :name, :zone, :ip, :port, :mac, :model, 'online')
            ");
            $stmt->execute([
                ':code' => $code,
                ':name' => $name,
                ':zone' => $zone,
                ':ip' => $ip,
                ':port' => $port,
                ':mac' => $mac,
                ':model' => $model
            ]);
            $newId = $db->lastInsertId();

            // Create initial telemetry placeholder
            $db->prepare("
                INSERT INTO telemetry_latest (board_id, temp_c, humidity_pct, vpd_kpa, raw_soil_ph, raw_soil_moisture, ai_calibrated_ph, ai_fused_moisture)
                VALUES (:bid, 28.0, 65.0, 1.20, 6.5, 50.0, 6.6, 50.0)
            ")->execute([':bid' => $newId]);

            echo json_encode([
                'status' => 'success',
                'message' => "เพิ่มบอร์ด {$name} ({$code}) สำเร็จ",
                'board_id' => $newId
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 3. Edit Existing Board
        case 'edit_board':
            $id = intval($inputJson['id'] ?? $_POST['id'] ?? 0);
            $name = trim($inputJson['name'] ?? $_POST['name'] ?? '');
            $zone = trim($inputJson['zone'] ?? $_POST['zone'] ?? '');
            $ip = trim($inputJson['ip_address'] ?? $_POST['ip_address'] ?? '');
            $port = intval($inputJson['port'] ?? $_POST['port'] ?? 8500);

            if ($id <= 0 || empty($name)) {
                throw new Exception('ข้อมูลบอร์ดไม่ถูกต้อง');
            }

            $stmt = $db->prepare("
                UPDATE boards 
                SET name = :name, zone = :zone, ip_address = :ip, port = :port, last_seen = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $stmt->execute([
                ':name' => $name,
                ':zone' => $zone,
                ':ip' => $ip,
                ':port' => $port,
                ':id' => $id
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => "แก้ไขข้อมูลบอร์ด ID: {$id} สำเร็จ"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 4. Delete Board
        case 'delete_board':
            $id = intval($inputJson['id'] ?? $_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('รหัสบอร์ดไม่ถูกต้อง');
            }
            $stmt = $db->prepare("DELETE FROM boards WHERE id = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode([
                'status' => 'success',
                'message' => "ลบบอร์ด ID: {$id} สำเร็จ"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 4.1 Update Board Sensor Configuration (Per-group sensor customization)
        case 'update_board_sensors':
            $id = intval($inputJson['id'] ?? $inputJson['board_id'] ?? $_POST['id'] ?? $_POST['board_id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('รหัสบอร์ดไม่ถูกต้อง');
            }

            $hasSht45 = isset($inputJson['has_sht45']) ? intval($inputJson['has_sht45']) : 1;
            $hasSoil7in1 = isset($inputJson['has_soil_7in1']) ? intval($inputJson['has_soil_7in1']) : 1;
            $hasSoilStick = isset($inputJson['has_soil_stick']) ? intval($inputJson['has_soil_stick']) : 1;
            $hasBh1750 = isset($inputJson['has_bh1750']) ? intval($inputJson['has_bh1750']) : 1;
            $hasCamera = isset($inputJson['has_camera']) ? intval($inputJson['has_camera']) : 1;
            $hasAiNpk = isset($inputJson['has_ai_npk']) ? intval($inputJson['has_ai_npk']) : 1;
            $hasVitalSigns = isset($inputJson['has_vital_signs']) ? intval($inputJson['has_vital_signs']) : 0;
            $preset = trim($inputJson['sensor_preset'] ?? $inputJson['preset'] ?? $_POST['sensor_preset'] ?? $_POST['preset'] ?? 'custom');
            $notes = trim($inputJson['notes'] ?? $_POST['notes'] ?? '');

            $stmt = $db->prepare("
                UPDATE boards 
                SET has_sht45 = :has_sht45,
                    has_soil_7in1 = :has_soil_7in1,
                    has_soil_stick = :has_soil_stick,
                    has_bh1750 = :has_bh1750,
                    has_camera = :has_camera,
                    has_ai_npk = :has_ai_npk,
                    has_vital_signs = :has_vital_signs,
                    sensor_preset = :sensor_preset,
                    notes = :notes,
                    last_seen = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $stmt->execute([
                ':has_sht45' => $hasSht45,
                ':has_soil_7in1' => $hasSoil7in1,
                ':has_soil_stick' => $hasSoilStick,
                ':has_bh1750' => $hasBh1750,
                ':has_camera' => $hasCamera,
                ':has_ai_npk' => $hasAiNpk,
                ':has_vital_signs' => $hasVitalSigns,
                ':sensor_preset' => $preset,
                ':notes' => $notes,
                ':id' => $id
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => "บันทึกการตั้งค่าเซนเซอร์สำหรับบอร์ด ID: {$id} สำเร็จ",
                'board_id' => $id,
                'sensor_preset' => $preset
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 4.2 Update Full Board Settings (Admin Quick Edit)
        case 'update_board_full':
            $id = intval($inputJson['id'] ?? $inputJson['board_id'] ?? $_POST['id'] ?? $_POST['board_id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('รหัสบอร์ดไม่ถูกต้อง');
            }

            $name = trim($inputJson['name'] ?? $_POST['name'] ?? '');
            $code = trim($inputJson['board_code'] ?? $_POST['board_code'] ?? '');
            $zone = trim($inputJson['zone'] ?? $_POST['zone'] ?? '');
            $ip = trim($inputJson['ip_address'] ?? $_POST['ip_address'] ?? '');
            $port = intval($inputJson['port'] ?? $_POST['port'] ?? 8500);
            $status = trim($inputJson['status'] ?? $_POST['status'] ?? 'online');
            $hasSht45 = isset($inputJson['has_sht45']) ? intval($inputJson['has_sht45']) : 1;
            $hasSoil7in1 = isset($inputJson['has_soil_7in1']) ? intval($inputJson['has_soil_7in1']) : 1;
            $hasSoilStick = isset($inputJson['has_soil_stick']) ? intval($inputJson['has_soil_stick']) : 1;
            $hasBh1750 = isset($inputJson['has_bh1750']) ? intval($inputJson['has_bh1750']) : 1;
            $hasCamera = isset($inputJson['has_camera']) ? intval($inputJson['has_camera']) : 1;
            $hasAiNpk = isset($inputJson['has_ai_npk']) ? intval($inputJson['has_ai_npk']) : 1;
            $hasVitalSigns = isset($inputJson['has_vital_signs']) ? intval($inputJson['has_vital_signs']) : 0;
            $preset = trim($inputJson['sensor_preset'] ?? $_POST['sensor_preset'] ?? 'custom');
            $notes = trim($inputJson['notes'] ?? $_POST['notes'] ?? '');

            $stmt = $db->prepare("
                UPDATE boards 
                SET name = :name,
                    board_code = :board_code,
                    zone = :zone,
                    ip_address = :ip,
                    port = :port,
                    status = :status,
                    has_sht45 = :has_sht45,
                    has_soil_7in1 = :has_soil_7in1,
                    has_soil_stick = :has_soil_stick,
                    has_bh1750 = :has_bh1750,
                    has_camera = :has_camera,
                    has_ai_npk = :has_ai_npk,
                    has_vital_signs = :has_vital_signs,
                    sensor_preset = :sensor_preset,
                    notes = :notes,
                    last_seen = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $stmt->execute([
                ':name' => $name,
                ':board_code' => $code,
                ':zone' => $zone,
                ':ip' => $ip,
                ':port' => $port,
                ':status' => $status,
                ':has_sht45' => $hasSht45,
                ':has_soil_7in1' => $hasSoil7in1,
                ':has_soil_stick' => $hasSoilStick,
                ':has_bh1750' => $hasBh1750,
                ':has_camera' => $hasCamera,
                ':has_ai_npk' => $hasAiNpk,
                ':has_vital_signs' => $hasVitalSigns,
                ':sensor_preset' => $preset,
                ':notes' => $notes,
                ':id' => $id
            ]);

            echo json_encode([
                'status' => 'success',
                'message' => "บันทึกข้อมูลบอร์ด ID: {$id} สำเร็จ"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 4.3 Batch Apply Sensor Preset to Multiple Boards
        case 'batch_apply_preset':
            $boardIds = $inputJson['board_ids'] ?? [];
            $preset = trim($inputJson['preset'] ?? 'full');

            if (empty($boardIds)) {
                // Apply to all non-master boards if empty
                $stmtAll = $db->query("SELECT id FROM boards WHERE is_master = 0");
                $boardIds = $stmtAll->fetchAll(PDO::FETCH_COLUMN);
            }

            // Define preset configs
            $config = [
                'has_sht45' => 1,
                'has_soil_7in1' => 1,
                'has_soil_stick' => 1,
                'has_bh1750' => 1,
                'has_camera' => 1,
                'has_ai_npk' => 1,
                'has_vital_signs' => 0
            ];

            if ($preset === 'soil_npk') {
                // Focus on soil chemistry & physics
                $config = ['has_sht45' => 0, 'has_soil_7in1' => 1, 'has_soil_stick' => 1, 'has_bh1750' => 0, 'has_camera' => 0, 'has_ai_npk' => 1, 'has_vital_signs' => 0];
            } elseif ($preset === 'greenhouse') {
                // Microclimate: SHT45 + BH1750 + Soil Stick
                $config = ['has_sht45' => 1, 'has_soil_7in1' => 0, 'has_soil_stick' => 1, 'has_bh1750' => 1, 'has_camera' => 0, 'has_ai_npk' => 0, 'has_vital_signs' => 0];
            } elseif ($preset === 'basic') {
                // Minimalist basic telemetry
                $config = ['has_sht45' => 1, 'has_soil_7in1' => 0, 'has_soil_stick' => 1, 'has_bh1750' => 0, 'has_camera' => 0, 'has_ai_npk' => 0, 'has_vital_signs' => 0];
            } elseif ($preset === 'health_iot') {
                // Health bio-sensor + climate
                $config = ['has_sht45' => 1, 'has_soil_7in1' => 0, 'has_soil_stick' => 0, 'has_bh1750' => 0, 'has_camera' => 0, 'has_ai_npk' => 0, 'has_vital_signs' => 1];
            }

            $updateStmt = $db->prepare("
                UPDATE boards 
                SET has_sht45 = :has_sht45,
                    has_soil_7in1 = :has_soil_7in1,
                    has_soil_stick = :has_soil_stick,
                    has_bh1750 = :has_bh1750,
                    has_camera = :has_camera,
                    has_ai_npk = :has_ai_npk,
                    has_vital_signs = :has_vital_signs,
                    sensor_preset = :preset,
                    last_seen = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            foreach ($boardIds as $bId) {
                $updateStmt->execute([
                    ':has_sht45' => $config['has_sht45'],
                    ':has_soil_7in1' => $config['has_soil_7in1'],
                    ':has_soil_stick' => $config['has_soil_stick'],
                    ':has_bh1750' => $config['has_bh1750'],
                    ':has_camera' => $config['has_camera'],
                    ':has_ai_npk' => $config['has_ai_npk'],
                    ':has_vital_signs' => $config['has_vital_signs'],
                    ':preset' => $preset,
                    ':id' => intval($bId)
                ]);
            }

            echo json_encode([
                'status' => 'success',
                'message' => "นำชุดเซนเซอร์ '{$preset}' ไปใช้กับ " . count($boardIds) . " บอร์ดสำเร็จ",
                'preset' => $preset,
                'affected_count' => count($boardIds)
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 4.4 Reset Fleet to 16 Workshop Nodes (Default Factory State)
        case 'reset_workshop_boards':
            $seedFile = __DIR__ . '/../config/seed_workshop.php';
            if (file_exists($seedFile)) {
                // Execute seed logic
                require_once $seedFile;
            }
            echo json_encode([
                'status' => 'success',
                'message' => "รีเซ็ตบอร์ด 16 กลุ่มตามมาตรฐานกิจกรรมอบรมเรียบร้อยแล้ว"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 5. Get Single Board Telemetry Detail (Detailed Raw vs Edge AI)
        case 'get_board_detail':
            $id = intval($_GET['id'] ?? ($inputJson['id'] ?? 1));
            $stmtB = $db->prepare("SELECT * FROM boards WHERE id = :id");
            $stmtB->execute([':id' => $id]);
            $board = $stmtB->fetch();

            if (!$board) {
                throw new Exception("ไม่พบบอร์ด ID {$id}");
            }

            $stmtT = $db->prepare("SELECT * FROM telemetry_latest WHERE board_id = :id");
            $stmtT->execute([':id' => $id]);
            $telemetry = $stmtT->fetch();

            $isMaster = !empty($board['is_master']) || ($board['board_code'] ?? '') === 'MASTER-LEQS' || intval($id) === 1;
            if ($isMaster) {
                $board['is_master'] = 1;
                $masterJsonFile = __DIR__ . '/../../leqs-workshop/data/telemetry_state.json';
                if (file_exists($masterJsonFile)) {
                    $masterLive = json_decode(@file_get_contents($masterJsonFile), true);
                    if ($masterLive && isset($masterLive['sensors'])) {
                        $s = $masterLive['sensors'];
                        $ai = $masterLive['ai_calibrated'] ?? [];
                        $relays = $masterLive['relays'] ?? [];
                        $telemetry['temp_c'] = floatval($s['temperature'] ?? $telemetry['temp_c']);
                        $telemetry['humidity_pct'] = floatval($s['humidity'] ?? $telemetry['humidity_pct']);
                        $telemetry['vpd_kpa'] = floatval($s['vpd'] ?? $telemetry['vpd_kpa']);
                        $telemetry['dew_point_c'] = floatval($s['dew_point'] ?? $telemetry['dew_point_c']);
                        $telemetry['solar_lux'] = floatval($s['par_lux'] ?? $telemetry['solar_lux']);
                        $telemetry['solar_wm2'] = floatval($s['solar_radiation'] ?? $telemetry['solar_wm2']);
                        $telemetry['raw_soil_ph'] = floatval($s['soil_ph'] ?? $telemetry['raw_soil_ph']);
                        $telemetry['raw_soil_moisture'] = floatval($s['soil_moisture'] ?? $telemetry['raw_soil_moisture']);
                        $telemetry['raw_soil_ec'] = floatval($s['soil_ec'] ?? $telemetry['raw_soil_ec']);
                        $telemetry['raw_soil_temp'] = floatval($s['soil_temperature'] ?? $telemetry['raw_soil_temp']);
                        $telemetry['stick_moisture'] = floatval($s['soil_stick_moisture'] ?? $telemetry['stick_moisture']);
                        $telemetry['stick_ph'] = floatval($s['soil_stick_ph'] ?? $telemetry['stick_ph']);
                        $telemetry['stick_adc'] = intval($s['soil_stick_adc'] ?? $telemetry['stick_adc']);
                        $telemetry['ai_calibrated_ph'] = floatval($ai['ph'] ?? $telemetry['ai_calibrated_ph']);
                        $telemetry['ai_fused_moisture'] = floatval($ai['moisture'] ?? $telemetry['ai_fused_moisture']);
                        $telemetry['ai_avail_n'] = floatval($ai['nitrogen'] ?? $telemetry['ai_avail_n']);
                        $telemetry['ai_avail_p'] = floatval($ai['phosphorus'] ?? $telemetry['ai_avail_p']);
                        $telemetry['ai_avail_k'] = floatval($ai['potassium'] ?? $telemetry['ai_avail_k']);
                        $telemetry['ai_confidence'] = floatval($ai['confidence'] ?? $telemetry['ai_confidence']);
                        $telemetry['relay1_state'] = intval($relays['1']['state'] ?? $telemetry['relay1_state']);
                        $telemetry['relay2_state'] = intval($relays['2']['state'] ?? $telemetry['relay2_state']);
                        $telemetry['relay3_state'] = intval($relays['3']['state'] ?? $telemetry['relay3_state']);
                        $telemetry['relay4_state'] = intval($relays['4']['state'] ?? $telemetry['relay4_state']);
                    }
                }
            }

            echo json_encode([
                'status' => 'success',
                'board' => $board,
                'telemetry' => $telemetry
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;

        // 6. Control Relay / Actuator
        case 'control_actuator':
            $boardId = intval($inputJson['board_id'] ?? $_POST['board_id'] ?? 1);
            $relayId = intval($inputJson['relay_id'] ?? $_POST['relay_id'] ?? 1);
            $state = intval($inputJson['state'] ?? $_POST['state'] ?? 0);

            if ($relayId < 1 || $relayId > 4) {
                throw new Exception('Relay ID ต้องอยู่ระหว่าง 1-4');
            }

            $column = "relay{$relayId}_state";
            $stmt = $db->prepare("UPDATE telemetry_latest SET {$column} = :st, updated_at = CURRENT_TIMESTAMP WHERE board_id = :bid");
            $stmt->execute([':st' => $state, ':bid' => $boardId]);

            // Log action
            $db->prepare("
                INSERT INTO actuator_logs (board_id, relay_id, command, triggered_by)
                VALUES (:bid, :rid, :cmd, 'manual_ui')
            ")->execute([
                ':bid' => $boardId,
                ':rid' => $relayId,
                ':cmd' => $state ? 'ON' : 'OFF'
            ]);

            // Attempt direct hardware push if live
            $stmtB = $db->prepare("SELECT ip_address, port FROM boards WHERE id = :bid");
            $stmtB->execute([':bid' => $boardId]);
            $b = $stmtB->fetch();
            $hwSync = 'simulated';

            // Sync with Master leqs-workshop telemetry_state.json
            $masterJsonFile = __DIR__ . '/../../leqs-workshop/data/telemetry_state.json';
            if (file_exists($masterJsonFile)) {
                $mState = json_decode(@file_get_contents($masterJsonFile), true);
                if (is_array($mState) && isset($mState['relays'][strval($relayId)])) {
                    $mState['relays'][strval($relayId)]['state'] = $state;
                    $mState['control_mode'] = 'manual';
                    @file_put_contents($masterJsonFile, json_encode($mState, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            }

            if ($b && !empty($b['ip_address'])) {
                $targetUrl = "http://{$b['ip_address']}:{$b['port']}/relay?id={$relayId}&state={$state}";
                $ctx = stream_context_create(['http' => ['timeout' => 0.6]]);
                @file_get_contents($targetUrl, false, $ctx);
                $hwSync = "sent_to_{$b['ip_address']}";
            }

            echo json_encode([
                'status' => 'success',
                'board_id' => $boardId,
                'relay_id' => $relayId,
                'state' => $state,
                'hardware_sync' => $hwSync,
                'message' => "สั่งการ Relay {$relayId} เป็น " . ($state ? 'ON' : 'OFF') . " สำเร็จ"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 7. Broadcast Command to ALL Boards
        case 'broadcast_actuators':
            $relayId = intval($inputJson['relay_id'] ?? $_POST['relay_id'] ?? 1);
            $state = intval($inputJson['state'] ?? $_POST['state'] ?? 0);

            $col = "relay{$relayId}_state";
            $db->exec("UPDATE telemetry_latest SET {$col} = {$state}, updated_at = CURRENT_TIMESTAMP");

            echo json_encode([
                'status' => 'success',
                'relay_id' => $relayId,
                'state' => $state,
                'message' => "กระจายคำสั่ง Relay {$relayId} เป็น " . ($state ? 'เปิด (ON)' : 'ปิด (OFF)') . " ทุกบอร์ดในเครือข่ายสำเร็จ"
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 8. Ingest/Push Telemetry from ESP32 or Mock Feeder
        case 'push_telemetry':
            $boardId = intval($inputJson['board_id'] ?? $_POST['board_id'] ?? 0);
            $boardCode = trim($inputJson['board_code'] ?? $_POST['board_code'] ?? '');

            if ($boardId <= 0 && !empty($boardCode)) {
                $stmt = $db->prepare("SELECT id FROM boards WHERE board_code = :c");
                $stmt->execute([':c' => $boardCode]);
                $boardId = $stmt->fetchColumn() ?: 0;
            }
            if ($boardId <= 0) $boardId = 1;

            $s = $inputJson['sensors'] ?? ($inputJson['telemetry'] ?? $inputJson);
            $ai = $inputJson['ai_calibrated'] ?? ($s['ai_calibrated'] ?? []);

            $temp = floatval($s['temperature'] ?? ($s['temp_c'] ?? ($s['air_temp'] ?? 28.5)));
            $hum = floatval($s['humidity'] ?? ($s['humidity_pct'] ?? ($s['air_hum'] ?? 65.0)));
            $vpd = floatval($s['vpd'] ?? ($s['vpd_kpa'] ?? 1.25));
            $dew = floatval($s['dew_point'] ?? ($s['dew_point_c'] ?? 21.0));
            $lux = floatval($s['par_lux'] ?? ($s['solar_lux'] ?? ($s['lux'] ?? 34000.0)));
            $solar = floatval($s['solar_radiation'] ?? ($s['solar_wm2'] ?? 270.0));
            $rPh = floatval($s['soil_ph'] ?? ($s['raw_soil_ph'] ?? 8.5));
            $rM = floatval($s['soil_moisture'] ?? ($s['raw_soil_moisture'] ?? 2.6));
            $rEc = floatval($s['soil_ec'] ?? ($s['raw_soil_ec'] ?? 0.42));
            $rTemp = floatval($s['soil_temperature'] ?? ($s['raw_soil_temp'] ?? 27.5));
            $rN = floatval($s['nitrogen'] ?? ($s['raw_n'] ?? ($s['soil_n'] ?? 0.0)));
            $rP = floatval($s['phosphorus'] ?? ($s['raw_p'] ?? ($s['soil_p'] ?? 0.0)));
            $rK = floatval($s['potassium'] ?? ($s['raw_k'] ?? ($s['soil_k'] ?? 0.0)));

            $stM = floatval($s['soil_stick_moisture'] ?? ($s['stick_moisture'] ?? ($s['surface_moist'] ?? 20.5)));
            $stPh = floatval($s['soil_stick_ph'] ?? ($s['stick_ph'] ?? ($s['surface_ph'] ?? 3.03)));
            $stAdc = intval($s['soil_stick_adc'] ?? ($s['stick_adc'] ?? 2000));

            $aiPh = floatval($ai['ph'] ?? ($ai['ai_calibrated_ph'] ?? ($s['ai_calibrated_ph'] ?? ($rPh + 0.94))));
            $aiM = floatval($ai['moisture'] ?? ($ai['ai_fused_moisture'] ?? ($s['ai_fused_moisture'] ?? $stM)));
            $aiN = floatval($ai['nitrogen'] ?? ($ai['ai_avail_n'] ?? ($s['ai_nitrogen'] ?? 21.8)));
            $aiP = floatval($ai['phosphorus'] ?? ($ai['ai_avail_p'] ?? ($s['ai_phosphorus'] ?? 13.9)));
            $aiK = floatval($ai['potassium'] ?? ($ai['ai_avail_k'] ?? ($s['ai_potassium'] ?? 8.0)));
            $aiConf = floatval($ai['confidence'] ?? ($ai['ai_confidence'] ?? ($s['ai_confidence'] ?? 77.6)));
            $aiAlert = trim($inputJson['ai_agronomy_alert'] ?? ($s['ai_alert'] ?? 'สภาวะการเจริญเติบโตปกติ'));

            $stmt = $db->prepare("
                INSERT INTO telemetry_latest (
                    board_id, temp_c, humidity_pct, vpd_kpa, dew_point_c, solar_lux, solar_wm2,
                    raw_soil_ph, raw_soil_moisture, raw_soil_ec, raw_soil_temp, raw_n, raw_p, raw_k,
                    stick_moisture, stick_ph, stick_adc,
                    ai_calibrated_ph, ai_fused_moisture, ai_avail_n, ai_avail_p, ai_avail_k,
                    ai_confidence, ai_agronomy_alert, updated_at
                ) VALUES (
                    :bid, :t, :h, :vpd, :dew, :lux, :sol,
                    :rph, :rm, :rec, :rtemp, :rn, :rp, :rk,
                    :stm, :stph, :stadc,
                    :aiph, :aim, :ain, :aip, :aik,
                    :aiconf, :alert, CURRENT_TIMESTAMP
                )
                ON CONFLICT(board_id) DO UPDATE SET
                    temp_c = excluded.temp_c,
                    humidity_pct = excluded.humidity_pct,
                    vpd_kpa = excluded.vpd_kpa,
                    dew_point_c = excluded.dew_point_c,
                    solar_lux = excluded.solar_lux,
                    solar_wm2 = excluded.solar_wm2,
                    raw_soil_ph = excluded.raw_soil_ph,
                    raw_soil_moisture = excluded.raw_soil_moisture,
                    raw_soil_ec = excluded.raw_soil_ec,
                    raw_soil_temp = excluded.raw_soil_temp,
                    raw_n = excluded.raw_n,
                    raw_p = excluded.raw_p,
                    raw_k = excluded.raw_k,
                    stick_moisture = excluded.stick_moisture,
                    stick_ph = excluded.stick_ph,
                    stick_adc = excluded.stick_adc,
                    ai_calibrated_ph = excluded.ai_calibrated_ph,
                    ai_fused_moisture = excluded.ai_fused_moisture,
                    ai_avail_n = excluded.ai_avail_n,
                    ai_avail_p = excluded.ai_avail_p,
                    ai_avail_k = excluded.ai_avail_k,
                    ai_confidence = excluded.ai_confidence,
                    ai_agronomy_alert = excluded.ai_agronomy_alert,
                    updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([
                ':bid' => $boardId, ':t' => $temp, ':h' => $hum, ':vpd' => $vpd, ':dew' => $dew, ':lux' => $lux, ':sol' => $solar,
                ':rph' => $rPh, ':rm' => $rM, ':rec' => $rEc, ':rtemp' => $rTemp, ':rn' => $rN, ':rp' => $rP, ':rk' => $rK,
                ':stm' => $stM, ':stph' => $stPh, ':stadc' => $stAdc,
                ':aiph' => $aiPh, ':aim' => $aiM, ':ain' => $aiN, ':aip' => $aiP, ':aik' => $aiK,
                ':aiconf' => $aiConf, ':alert' => $aiAlert
            ]);

            // Update board status & last seen
            $db->prepare("UPDATE boards SET status = 'online', last_seen = CURRENT_TIMESTAMP WHERE id = :bid")->execute([':bid' => $boardId]);

            echo json_encode(['status' => 'success', 'message' => "Telemetry ingested for Board {$boardId}"]);
            break;

        // 9. Save Layout & Widget Customization
        case 'save_layout':
            $preset = trim($inputJson['preset'] ?? $_POST['preset'] ?? 'default');
            $activeBoard = intval($inputJson['active_board_id'] ?? $_POST['active_board_id'] ?? 1);
            $viewMode = trim($inputJson['view_mode'] ?? $_POST['view_mode'] ?? 'focus');
            $cols = intval($inputJson['grid_columns'] ?? $_POST['grid_columns'] ?? 4);
            $widgets = is_array($inputJson['visible_widgets'] ?? null) 
                ? json_encode($inputJson['visible_widgets'], JSON_UNESCAPED_UNICODE) 
                : ($_POST['visible_widgets'] ?? '[]');

            $stmt = $db->prepare("
                INSERT INTO dashboard_layouts (user_preset, active_board_id, view_mode, visible_widgets, grid_columns, updated_at)
                VALUES (:p, :ab, :vm, :w, :c, CURRENT_TIMESTAMP)
                ON CONFLICT(user_preset) DO UPDATE SET
                    active_board_id = excluded.active_board_id,
                    view_mode = excluded.view_mode,
                    visible_widgets = excluded.visible_widgets,
                    grid_columns = excluded.grid_columns,
                    updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([
                ':p' => $preset,
                ':ab' => $activeBoard,
                ':vm' => $viewMode,
                ':w' => $widgets,
                ':c' => $cols
            ]);

            echo json_encode(['status' => 'success', 'message' => 'บันทึกการจัดแต่งหน้าจอสำเร็จ']);
            break;

        // 10. Get Layout
        case 'get_layout':
            $preset = trim($_GET['preset'] ?? ($inputJson['preset'] ?? 'default'));
            $stmt = $db->prepare("SELECT * FROM dashboard_layouts WHERE user_preset = :p");
            $stmt->execute([':p' => $preset]);
            $layout = $stmt->fetch();

            if (!$layout) {
                $layout = [
                    'user_preset' => 'default',
                    'active_board_id' => 1,
                    'view_mode' => 'focus',
                    'visible_widgets' => json_encode([
                        'weather_microclimate', 'vpd_transpiration', 'soil_7in1_root',
                        'surface_soil_stick', 'solar_radiation', 'ai_npk_calibration',
                        'ai_sensor_comparison', 'relays_control', 'trend_charts'
                    ]),
                    'grid_columns' => 4
                ];
            }

            $layout['visible_widgets'] = json_decode($layout['visible_widgets'], true) ?: [];

            echo json_encode([
                'status' => 'success',
                'layout' => $layout
            ], JSON_UNESCAPED_UNICODE);
            break;

        default:
            throw new Exception("ไม่รู้จักคำสั่ง action '{$action}'");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
