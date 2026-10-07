-- ============================================================================
-- AIoT Multi-Board Fleet Management & Customizable IoT Dashboard
-- SQLite3 Database Schema DDL
-- ============================================================================

CREATE TABLE IF NOT EXISTS boards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    board_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    zone VARCHAR(50) NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    port INTEGER DEFAULT 8500,
    mac_address VARCHAR(30),
    model VARCHAR(50) DEFAULT 'ESP32-S3 ATD3.5',
    status VARCHAR(20) DEFAULT 'online',
    rssi INTEGER DEFAULT -75,
    firmware_version VARCHAR(30) DEFAULT 'v3.2.4-EdgeAI',
    has_camera BOOLEAN DEFAULT 1,
    has_soil_7in1 BOOLEAN DEFAULT 1,
    has_soil_stick BOOLEAN DEFAULT 1,
    has_sht45 BOOLEAN DEFAULT 1,
    has_bh1750 BOOLEAN DEFAULT 1,
    is_master BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_seen DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS telemetry_latest (
    board_id INTEGER PRIMARY KEY,
    temp_c REAL DEFAULT 28.5,
    humidity_pct REAL DEFAULT 65.0,
    vpd_kpa REAL DEFAULT 1.25,
    dew_point_c REAL DEFAULT 21.0,
    solar_lux REAL DEFAULT 1200.0,
    solar_wm2 REAL DEFAULT 9.5,
    raw_soil_ph REAL DEFAULT 8.2,
    raw_soil_moisture REAL DEFAULT 25.0,
    raw_soil_ec REAL DEFAULT 120.0,
    raw_soil_temp REAL DEFAULT 27.5,
    raw_n REAL DEFAULT 0.0,
    raw_p REAL DEFAULT 0.0,
    raw_k REAL DEFAULT 0.0,
    stick_moisture REAL DEFAULT 61.5,
    stick_ph REAL DEFAULT 3.0,
    stick_adc INTEGER DEFAULT 2030,
    ai_calibrated_ph REAL DEFAULT 9.14,
    ai_fused_moisture REAL DEFAULT 20.5,
    ai_avail_n REAL DEFAULT 14.7,
    ai_avail_p REAL DEFAULT 12.8,
    ai_avail_k REAL DEFAULT 3.9,
    ai_confidence REAL DEFAULT 0.776,
    ai_agronomy_alert TEXT DEFAULT 'สภาวะปกติ',
    vision_detection TEXT DEFAULT 'Healthy Leaf (98.4%)',
    relay1_state INTEGER DEFAULT 0,
    relay2_state INTEGER DEFAULT 0,
    relay3_state INTEGER DEFAULT 0,
    relay4_state INTEGER DEFAULT 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(board_id) REFERENCES boards(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS dashboard_layouts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_preset VARCHAR(50) UNIQUE DEFAULT 'default',
    active_board_id INTEGER DEFAULT 1,
    view_mode VARCHAR(30) DEFAULT 'focus',
    visible_widgets TEXT,
    grid_columns INTEGER DEFAULT 4,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS actuator_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    board_id INTEGER,
    relay_id INTEGER,
    command VARCHAR(20),
    triggered_by VARCHAR(50) DEFAULT 'manual',
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(board_id) REFERENCES boards(id) ON DELETE CASCADE
);

-- Seed 1 Master Demonstration Board + 15 Workshop Group Boards
INSERT OR IGNORE INTO boards (id, board_code, name, zone, ip_address, port, mac_address, model, status, rssi, firmware_version, is_master)
VALUES 
(1, 'MASTER-LEQS', '⭐ บอร์ดสาธิตหลักวิทยากร (LEQs-xAI Instructor Board)', 'ห้องอบรม - เวทีกลาง (Main Stage)', '192.168.0.111', 8500, '48:27:E2:B4:8A:1C', 'ESP32-S3 ATD3.5 Pro', 'online', -62, 'v3.2.4-EdgeAI-Pro', 1),
(2, 'ESP32-GRP-01', 'กลุ่ม 1 (โต๊ะ 1): แปลงทุเรียนภูเขาไฟ (Volcanic Durian)', 'Zone A - โต๊ะปฏิบัติการ 1', '192.168.0.121', 8500, '48:27:E2:B4:8A:01', 'ESP32-S3 ATD3.5', 'online', -68, 'v3.2.4-EdgeAI', 0),
(3, 'ESP32-GRP-02', 'กลุ่ม 2 (โต๊ะ 2): โรงเรือนมังคุดอินทรีย์ (Organic Mangosteen)', 'Zone A - โต๊ะปฏิบัติการ 2', '192.168.0.122', 8500, '48:27:E2:B4:8A:02', 'ESP32-S3 ATD3.5', 'online', -70, 'v3.2.4-EdgeAI', 0),
(4, 'ESP32-GRP-03', 'กลุ่ม 3 (โต๊ะ 3): แปลงเงาะโรงเรียน AI (Smart Rambutan)', 'Zone A - โต๊ะปฏิบัติการ 3', '192.168.0.123', 8500, '48:27:E2:B4:8A:03', 'ESP32-S3 ATD3.5', 'online', -65, 'v3.2.4-EdgeAI', 0),
(5, 'ESP32-GRP-04', 'กลุ่ม 4 (โต๊ะ 4): แปลงสละสุมาลีอัจฉริยะ (Smart Zalacca)', 'Zone A - โต๊ะปฏิบัติการ 4', '192.168.0.124', 8500, '48:27:E2:B4:8A:04', 'ESP32-S3 ATD3.5', 'online', -72, 'v3.2.4-EdgeAI', 0),
(6, 'ESP32-GRP-05', 'กลุ่ม 5 (โต๊ะ 5): สวนลองกองและพืชแซม (Longkong Microclimate)', 'Zone A - โต๊ะปฏิบัติการ 5', '192.168.0.125', 8500, '48:27:E2:B4:8A:05', 'ESP32-S3 ATD3.5', 'online', -69, 'v3.2.4-EdgeAI', 0),
(7, 'ESP32-GRP-06', 'กลุ่ม 6 (โต๊ะ 6): เมล่อนญี่ปุ่นไร้ดิน (Hydro-Melon Polyhouse)', 'Zone B - โต๊ะปฏิบัติการ 6', '192.168.0.126', 8500, '48:27:E2:B4:8A:06', 'ESP32-S3 ATD3.5', 'online', -64, 'v3.2.4-EdgeAI', 0),
(8, 'ESP32-GRP-07', 'กลุ่ม 7 (โต๊ะ 7): ผักสลัดไฮโดรโปนิกส์แนวตั้ง (Vertical Hydro)', 'Zone B - โต๊ะปฏิบัติการ 7', '192.168.0.127', 8500, '48:27:E2:B4:8A:07', 'ESP32-S3 ATD3.5', 'online', -67, 'v3.2.4-EdgeAI', 0),
(9, 'ESP32-GRP-08', 'กลุ่ม 8 (โต๊ะ 8): สตรอว์เบอร์รีควบคุม VPD (VPD Strawberry)', 'Zone B - โต๊ะปฏิบัติการ 8', '192.168.0.128', 8500, '48:27:E2:B4:8A:08', 'ESP32-S3 ATD3.5', 'online', -71, 'v3.2.4-EdgeAI', 0),
(10, 'ESP32-GRP-09', 'กลุ่ม 9 (โต๊ะ 9): โรงเพาะเห็ดถั่งเช่า Cleanroom (Cordyceps Vault)', 'Zone B - โต๊ะปฏิบัติการ 9', '192.168.0.129', 8500, '48:27:E2:B4:8A:09', 'ESP32-S3 ATD3.5', 'online', -66, 'v3.2.4-EdgeAI', 0),
(11, 'ESP32-GRP-10', 'กลุ่ม 10 (โต๊ะ 10): กล้วยไม้ตัดดอกส่งออก (Export Orchid)', 'Zone B - โต๊ะปฏิบัติการ 10', '192.168.0.130', 8500, '48:27:E2:B4:8A:0A', 'ESP32-S3 ATD3.5', 'online', -73, 'v3.2.4-EdgeAI', 0),
(12, 'ESP32-GRP-11', 'กลุ่ม 11 (โต๊ะ 11): พริกไทยพันธุ์ซีลอนเมืองจันท์ (Pepper Microclimate)', 'Zone C - โต๊ะปฏิบัติการ 11', '192.168.0.131', 8500, '48:27:E2:B4:8A:0B', 'ESP32-S3 ATD3.5', 'online', -68, 'v3.2.4-EdgeAI', 0),
(13, 'ESP32-GRP-12', 'กลุ่ม 12 (โต๊ะ 12): โกโก้พรีเมียมเมืองจันท์ (Craft Cacao Grove)', 'Zone C - โต๊ะปฏิบัติการ 12', '192.168.0.132', 8500, '48:27:E2:B4:8A:0C', 'ESP32-S3 ATD3.5', 'online', -70, 'v3.2.4-EdgeAI', 0),
(14, 'ESP32-GRP-13', 'กลุ่ม 13 (โต๊ะ 13): กัญชงการแพทย์ IoT (Medical Hemp Vault)', 'Zone C - โต๊ะปฏิบัติการ 13', '192.168.0.133', 8500, '48:27:E2:B4:8A:0D', 'ESP32-S3 ATD3.5', 'online', -65, 'v3.2.4-EdgeAI', 0),
(15, 'ESP32-GRP-14', 'กลุ่ม 14 (โต๊ะ 14): สมุนไพรฟ้าทะลายโจร (Andrographis Precision)', 'Zone C - โต๊ะปฏิบัติการ 14', '192.168.0.134', 8500, '48:27:E2:B4:8A:0E', 'ESP32-S3 ATD3.5', 'online', -69, 'v3.2.4-EdgeAI', 0),
(16, 'ESP32-GRP-15', 'กลุ่ม 15 (โต๊ะ 15): กาแฟโรบัสต้าเขาสอยดาว (Soi Dao Robusta)', 'Zone C - โต๊ะปฏิบัติการ 15', '192.168.0.135', 8500, '48:27:E2:B4:8A:0F', 'ESP32-S3 ATD3.5', 'online', -67, 'v3.2.4-EdgeAI', 0);

-- Seed Default Layout
INSERT OR IGNORE INTO dashboard_layouts (id, user_preset, active_board_id, view_mode, visible_widgets, grid_columns)
VALUES (1, 'default', 1, 'focus', '["weather_microclimate","vpd_transpiration","soil_7in1_root","surface_soil_stick","solar_radiation","ai_npk_calibration","relays_control","trend_charts"]', 4);
