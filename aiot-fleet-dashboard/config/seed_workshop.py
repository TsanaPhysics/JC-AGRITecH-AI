import sqlite3
import os

db_path = "/Applications/XAMPP/xamppfiles/htdocs/handysense/aiot-fleet-dashboard/database/fleet.db"
conn = sqlite3.connect(db_path)
cur = conn.cursor()

# Ensure is_master column exists
cur.execute("PRAGMA table_info(boards)")
columns = [col[1] for col in cur.fetchall()]
if "is_master" not in columns:
    cur.execute("ALTER TABLE boards ADD COLUMN is_master BOOLEAN DEFAULT 0")

# Clear old boards and telemetry to reseed clean 16 workshop boards
cur.execute("DELETE FROM boards")
cur.execute("DELETE FROM telemetry_latest")

boards_data = [
    # 1. Master Demonstration Node
    (
        1, "MASTER-LEQS", "⭐ บอร์ดสาธิตหลักวิทยากร (LEQs-xAI Instructor Board)",
        "ห้องอบรม - เวทีกลาง (Main Stage)", "192.168.0.111", 8500, "48:27:E2:B4:8A:1C",
        "ESP32-S3 ATD3.5 Pro", "online", -62, "v3.2.4-EdgeAI-Pro", 1, 1, 1, 1, 1, 1
    ),
    # 2. Workshop Group 1
    (
        2, "ESP32-GRP-01", "กลุ่ม 1 (โต๊ะ 1): แปลงทุเรียนภูเขาไฟ (Volcanic Durian)",
        "Zone A - โต๊ะปฏิบัติการ 1", "192.168.0.121", 8500, "48:27:E2:B4:8A:01",
        "ESP32-S3 ATD3.5", "online", -68, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 3. Workshop Group 2
    (
        3, "ESP32-GRP-02", "กลุ่ม 2 (โต๊ะ 2): โรงเรือนมังคุดอินทรีย์ (Organic Mangosteen)",
        "Zone A - โต๊ะปฏิบัติการ 2", "192.168.0.122", 8500, "48:27:E2:B4:8A:02",
        "ESP32-S3 ATD3.5", "online", -70, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 4. Workshop Group 3
    (
        4, "ESP32-GRP-03", "กลุ่ม 3 (โต๊ะ 3): แปลงเงาะโรงเรียน AI (Smart Rambutan)",
        "Zone A - โต๊ะปฏิบัติการ 3", "192.168.0.123", 8500, "48:27:E2:B4:8A:03",
        "ESP32-S3 ATD3.5", "online", -65, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 5. Workshop Group 4
    (
        5, "ESP32-GRP-04", "กลุ่ม 4 (โต๊ะ 4): แปลงสละสุมาลีอัจฉริยะ (Smart Zalacca)",
        "Zone A - โต๊ะปฏิบัติการ 4", "192.168.0.124", 8500, "48:27:E2:B4:8A:04",
        "ESP32-S3 ATD3.5", "online", -72, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 6. Workshop Group 5
    (
        6, "ESP32-GRP-05", "กลุ่ม 5 (โต๊ะ 5): สวนลองกองและพืชแซม (Longkong Microclimate)",
        "Zone A - โต๊ะปฏิบัติการ 5", "192.168.0.125", 8500, "48:27:E2:B4:8A:05",
        "ESP32-S3 ATD3.5", "online", -69, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 7. Workshop Group 6
    (
        7, "ESP32-GRP-06", "กลุ่ม 6 (โต๊ะ 6): เมล่อนญี่ปุ่นไร้ดิน (Hydro-Melon Polyhouse)",
        "Zone B - โต๊ะปฏิบัติการ 6", "192.168.0.126", 8500, "48:27:E2:B4:8A:06",
        "ESP32-S3 ATD3.5", "online", -64, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 8. Workshop Group 7
    (
        8, "ESP32-GRP-07", "กลุ่ม 7 (โต๊ะ 7): ผักสลัดไฮโดรโปนิกส์แนวตั้ง (Vertical Hydro)",
        "Zone B - โต๊ะปฏิบัติการ 7", "192.168.0.127", 8500, "48:27:E2:B4:8A:07",
        "ESP32-S3 ATD3.5", "online", -67, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 9. Workshop Group 8
    (
        9, "ESP32-GRP-08", "กลุ่ม 8 (โต๊ะ 8): สตรอว์เบอร์รีควบคุม VPD (VPD Strawberry)",
        "Zone B - โต๊ะปฏิบัติการ 8", "192.168.0.128", 8500, "48:27:E2:B4:8A:08",
        "ESP32-S3 ATD3.5", "online", -71, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 10. Workshop Group 9
    (
        10, "ESP32-GRP-09", "กลุ่ม 9 (โต๊ะ 9): โรงเพาะเห็ดถั่งเช่า Cleanroom (Cordyceps Vault)",
        "Zone B - โต๊ะปฏิบัติการ 9", "192.168.0.129", 8500, "48:27:E2:B4:8A:09",
        "ESP32-S3 ATD3.5", "online", -66, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 11. Workshop Group 10
    (
        11, "ESP32-GRP-10", "กลุ่ม 10 (โต๊ะ 10): กล้วยไม้ตัดดอกส่งออก (Export Orchid Canopy)",
        "Zone B - โต๊ะปฏิบัติการ 10", "192.168.0.130", 8500, "48:27:E2:B4:8A:0A",
        "ESP32-S3 ATD3.5", "online", -73, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 12. Workshop Group 11
    (
        12, "ESP32-GRP-11", "กลุ่ม 11 (โต๊ะ 11): พริกไทยพันธุ์ซีลอนเมืองจันท์ (Pepper Microclimate)",
        "Zone C - โต๊ะปฏิบัติการ 11", "192.168.0.131", 8500, "48:27:E2:B4:8A:0B",
        "ESP32-S3 ATD3.5", "online", -68, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 13. Workshop Group 12
    (
        13, "ESP32-GRP-12", "กลุ่ม 12 (โต๊ะ 12): โกโก้พรีเมียมเมืองจันท์ (Craft Cacao Grove)",
        "Zone C - โต๊ะปฏิบัติการ 12", "192.168.0.132", 8500, "48:27:E2:B4:8A:0C",
        "ESP32-S3 ATD3.5", "online", -70, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 14. Workshop Group 13
    (
        14, "ESP32-GRP-13", "กลุ่ม 13 (โต๊ะ 13): กัญชงการแพทย์ IoT (Medical Hemp Vault)",
        "Zone C - โต๊ะปฏิบัติการ 13", "192.168.0.133", 8500, "48:27:E2:B4:8A:0D",
        "ESP32-S3 ATD3.5", "online", -65, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 15. Workshop Group 14
    (
        15, "ESP32-GRP-14", "กลุ่ม 14 (โต๊ะ 14): สมุนไพรฟ้าทะลายโจร (Andrographis Precision)",
        "Zone C - โต๊ะปฏิบัติการ 14", "192.168.0.134", 8500, "48:27:E2:B4:8A:0E",
        "ESP32-S3 ATD3.5", "online", -69, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
    # 16. Workshop Group 15
    (
        16, "ESP32-GRP-15", "กลุ่ม 15 (โต๊ะ 15): กาแฟโรบัสต้าเขาสอยดาว (Soi Dao Robusta)",
        "Zone C - โต๊ะปฏิบัติการ 15", "192.168.0.135", 8500, "48:27:E2:B4:8A:0F",
        "ESP32-S3 ATD3.5", "online", -67, "v3.2.4-EdgeAI", 1, 1, 1, 1, 1, 0
    ),
]

for b in boards_data:
    cur.execute("""
        INSERT INTO boards (
            id, board_code, name, zone, ip_address, port, mac_address,
            model, status, rssi, firmware_version, has_camera, has_soil_7in1,
            has_soil_stick, has_sht45, has_bh1750, is_master
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    """, b)

telemetry_data = [
    # 1. Master Board (seeded with current live data)
    (1, 24.4, 65.7, 1.05, 17.6, 35.8, 0.28, 7.6, 0.0, 0.0, 24.4, 0.0, 0.0, 0.0, 60.7, 3.2, 2031, 8.36, 17.7, 17.4, 11.3, 9.6, 0.745, 'บอร์ดสาธิตหลักทำงานปกติ • สตรีมมิ่งข้อมูลสดจากฮาร์ดแวร์ ESP32-S3 ATD3.5'),
    # 2. Group 1
    (2, 25.8, 58.4, 1.35, 16.8, 1250.0, 9.8, 8.2, 2.8, 0.45, 26.5, 0.0, 0.0, 0.0, 62.0, 3.1, 2025, 9.15, 21.0, 22.0, 14.0, 8.5, 0.812, 'พบความชัน pH ข้ามชั้นดิน • ดินเขตรากแห้ง แนะนำเปิดวาล์วโซลินอยด์'),
    # 3. Group 2
    (3, 27.2, 70.5, 1.02, 21.2, 980.0, 7.6, 6.4, 52.0, 1.10, 27.0, 35.0, 18.0, 120.0, 65.0, 6.2, 1980, 6.55, 53.0, 38.0, 20.0, 125.0, 0.925, 'สภาวะมังคุดสมบูรณ์ ความชื้นและสารอาหารเหมาะสม'),
    # 4. Group 3
    (4, 28.5, 66.0, 1.28, 21.5, 1420.0, 11.2, 6.8, 48.0, 0.88, 27.8, 28.0, 15.0, 95.0, 58.0, 6.5, 2010, 6.90, 49.0, 31.0, 16.5, 98.0, 0.880, 'การคายน้ำสมบูรณ์ แสงแดดจัด แนะนำตรวจระดับการให้น้ำรอบบ่าย'),
    # 5. Group 4
    (5, 26.4, 75.0, 0.85, 21.6, 820.0, 6.4, 5.9, 68.0, 1.25, 25.8, 42.0, 24.0, 155.0, 70.0, 5.8, 1940, 6.05, 69.0, 45.0, 26.0, 160.0, 0.940, 'สละสุมาลีเติบโตดี ดินมีความชื้นเหมาะสมกับระบบราก'),
    # 6. Group 5
    (6, 26.0, 72.0, 0.95, 20.5, 750.0, 5.8, 6.2, 60.0, 0.95, 26.0, 30.0, 16.0, 110.0, 63.0, 6.0, 1990, 6.30, 61.0, 33.0, 18.0, 115.0, 0.910, 'แปลงพืชแซมสภาวะอากาศปกติ อุณหภูมิอยู่ในเกณฑ์ดี'),
    # 7. Group 6
    (7, 24.8, 62.5, 1.15, 17.1, 1650.0, 13.0, 6.5, 78.0, 1.85, 24.5, 110.0, 32.0, 195.0, 75.0, 6.4, 1920, 6.50, 80.0, 112.0, 34.0, 200.0, 0.970, 'สารละลายเมล่อน EC ปกติ อัตรา NPK เหมาะกับการทำผล'),
    # 8. Group 7
    (8, 23.5, 68.0, 0.92, 17.2, 1100.0, 8.5, 6.0, 85.0, 1.65, 23.8, 95.0, 28.0, 180.0, 82.0, 5.9, 1890, 6.10, 86.0, 98.0, 30.0, 185.0, 0.982, 'ผักสลัดไฮโดรโปนิกส์ค่าสมบูรณ์ ไร้ภาวะใบไหม้ Tipburn'),
    # 9. Group 8
    (9, 21.0, 78.0, 0.55, 17.0, 650.0, 5.0, 5.8, 72.0, 1.40, 22.0, 50.0, 25.0, 140.0, 74.0, 5.7, 1950, 5.85, 73.0, 52.0, 27.0, 145.0, 0.955, 'VPD ต่ำเล็กน้อย แนะนำระบายอากาศในโรงเรือนสตรอว์เบอร์รี'),
    # 10. Group 9
    (10, 19.5, 88.0, 0.28, 17.4, 180.0, 1.4, 6.8, 92.0, 0.65, 20.0, 20.0, 12.0, 45.0, 90.0, 6.6, 1850, 6.85, 93.0, 22.0, 13.0, 48.0, 0.995, 'ห้องเพาะเห็ด Cleanroom ค่าความชื้นสัมพัทธ์สูงสมบูรณ์ 88%'),
    # 11. Group 10
    (11, 27.0, 74.0, 0.92, 21.9, 1350.0, 10.5, 6.1, 65.0, 1.30, 26.5, 60.0, 30.0, 160.0, 68.0, 6.0, 1960, 6.20, 66.0, 62.0, 32.0, 165.0, 0.930, 'กล้วยไม้ได้รับแสงพรางเหมาะสม ปริมาณความชื้นสมดุล'),
    # 12. Group 11
    (12, 28.2, 59.0, 1.55, 19.3, 1550.0, 12.2, 6.3, 44.0, 0.95, 27.5, 38.0, 19.0, 115.0, 52.0, 6.2, 2030, 6.45, 46.0, 40.0, 21.0, 120.0, 0.895, 'พริกไทยจันท์ VPD 1.55 kPa จัดว่าคายน้ำสูง แนะนำเริ่มพ่นหมอก'),
    # 13. Group 12
    (13, 27.5, 65.0, 1.25, 20.2, 1200.0, 9.4, 6.6, 50.0, 0.85, 27.0, 32.0, 17.0, 105.0, 59.0, 6.5, 2000, 6.70, 52.0, 34.0, 18.5, 110.0, 0.915, 'แปลงโกโก้สภาวะปกติ การพัฒนาของฝักสมบูรณ์'),
    # 14. Group 13
    (14, 25.0, 60.0, 1.27, 16.7, 1800.0, 14.0, 6.4, 62.0, 1.95, 24.8, 85.0, 40.0, 210.0, 64.0, 6.3, 1970, 6.45, 63.0, 88.0, 42.0, 215.0, 0.965, 'โรงเรือนกัญชงค่าความเข้มแสงและธาตุอาหารระดับพรีเมียม'),
    # 15. Group 14
    (15, 26.8, 64.0, 1.25, 19.4, 1300.0, 10.2, 6.7, 46.0, 0.90, 26.8, 25.0, 14.0, 85.0, 55.0, 6.6, 2020, 6.80, 48.0, 27.0, 15.0, 90.0, 0.905, 'แปลงฟ้าทะลายโจรสารแอนโดรกราโฟไลด์สะสมตัวดี'),
    # 16. Group 15
    (16, 24.5, 76.0, 0.74, 20.0, 950.0, 7.4, 5.7, 58.0, 0.75, 25.0, 30.0, 16.0, 95.0, 62.0, 5.6, 2010, 5.80, 60.0, 32.0, 17.5, 100.0, 0.920, 'กาแฟโรบัสต้าบนพื้นที่สูง อุณหภูมิเย็นสบาย ความชื้นดี'),
]

for t in telemetry_data:
    cur.execute("""
        INSERT INTO telemetry_latest (
            board_id, temp_c, humidity_pct, vpd_kpa, dew_point_c, solar_lux, solar_wm2,
            raw_soil_ph, raw_soil_moisture, raw_soil_ec, raw_soil_temp, raw_n, raw_p, raw_k,
            stick_moisture, stick_ph, stick_adc,
            ai_calibrated_ph, ai_fused_moisture, ai_avail_n, ai_avail_p, ai_avail_k,
            ai_confidence, ai_agronomy_alert
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?
        )
    """, t)

# Update layout default preset
cur.execute("""
    INSERT OR REPLACE INTO dashboard_layouts (id, user_preset, active_board_id, view_mode, visible_widgets, grid_columns)
    VALUES (1, 'default', 1, 'focus', '["weather_microclimate","vpd_transpiration","soil_7in1_root","surface_soil_stick","solar_radiation","ai_npk_calibration","relays_control","trend_charts"]', 4)
""")

conn.commit()
conn.close()
print("Successfully seeded 1 Master Board + 15 Workshop Group Boards!")
