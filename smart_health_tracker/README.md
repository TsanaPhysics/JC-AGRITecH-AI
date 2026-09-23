# Smart Health Step & Movement Tracker (แอปพลิเคชันสุขภาพ นับก้าวและวิเคราะห์การขยับร่างกาย)

แอปพลิเคชันสมาร์ทโฟนสำหรับการตรวจนับก้าว (**Step Counting**) และวิเคราะห์การขยับร่างกาย (**Physical Movement & Motion Tracking**) แบบเรียลไทม์ พร้อมการคำนวณชีวสถิติ (แคลอรี, ระยะทาง, เวลาที่มีการเคลื่อนไหว) และวงแหวนแสดงเป้าหมายสุขภาพประจำวัน

พัฒนาด้วย **Flutter (Dart) + Sensors Plus + Physics-Informed Step Peak Detection Algorithm**

---

## 🌟 คุณสมบัติเด่น (Key Features)

1. **ระบบนับก้าวอัจฉริยะ (Physics-Informed Step Counter)**:
   - ดึงข้อมูลจากเซ็นเซอร์ความเร่ง 3 แกน ($X, Y, Z$) ของสมาร์ทโฟน
   - คำนวณเวกเตอร์ความเร่งลัพธ์แบบหักล้างแรงโน้มถ่วง พร้อมอัลกอริทึม **Dynamic Peak Detection with Refractory Window** ป้องกันการนับก้าวผิดพลาดจากการนั่งเขย่ามือ
2. **เกจวัดแรงขยับร่างกายสด (Real-Time Motion Intensity Gauge)**:
   - แสดงระดับความเข้มข้นของการเคลื่อนไหวร่างกายแบบเรียลไทม์ (0% - 100%) พร้อมแสดงสถานะเซ็นเซอร์ Live
3. **การจำแนกประเภทกิจกรรม (Activity Classification)**:
   - 🧘‍♂️ **อยู่นิ่ง / พักผ่อน (Stationary)**
   - 🚶‍♂️ **กำลังเดิน (Walking)**
   - 🏃‍♂️ **กำลังวิ่ง (Running)**
   - 🏋️‍♂️ **ขยับร่างกายต่อเนื่อง (Active Motion)**
4. **การ์ดสรุปชีวสถิติ 3 มิติ**:
   - 🔥 **แคลอรีที่เผาผลาญ (kcal)**
   - 📍 **ระยะทางเดิน/วิ่ง (กม.)**
   - ⏱️ **เวลาที่ร่างกายขยับต่อเนื่อง (นาที)**
5. **วงแหวนความคืบหน้า (Activity Progress Ring)**:
   - วงแหวนนีออนมรกตแสดงเปอร์เซ็นต์ความคืบหน้าเทียบกับเป้าหมายประจำวัน (เช่น 6,540 / 10,000 ก้าว = 65%)
6. **แผนภูมิแท่งกิจกรรม 24 ชั่วโมง**:
   - บันทึกและแสดงสถิติก้าวเดินในแต่ละช่วงเวลาของวันตั้งแต่ 00:00 - 23:00 น.
7. **ระบบตั้งค่าเป้าหมายและโหมดจำลองเดิน**:
   - ปรับตั้งเป้าหมายก้าวต่อวันได้อิสระ (6,000, 8,000, 10,000, 12,000, 15,000 ก้าว)
   - มีปุ่มจำลองการเดินต่อเนื่อง (Live Simulation) และปุ่ม +1 ก้าวสำหรับการทดสอบและสาธิต

---

## 📁 โครงสร้างโปรเจกต์ (Project Structure)

```text
smart_health_tracker/
├── lib/
│   ├── main.dart                               # Entrypoint & การตั้งค่า MultiProvider
│   ├── models/
│   │   ├── activity_type.dart                  # ประเภทการเคลื่อนไหว (เดิน, วิ่ง, อยู่นิ่ง) และค่า MET
│   │   └── daily_health_metric.dart            # โมเดลข้อมูลก้าว, แคลอรี, ระยะทาง, สถิติรายชั่วโมง
│   ├── services/
│   │   ├── movement_sensor_service.dart        # ตรวจจับความเร่ง Accelerometer, อัลกอริทึมนับก้าว
│   │   └── health_storage_service.dart         # บันทึกประวัติก้าวประจำวันลง SharedPreferences
│   └── ui/
│       ├── theme/
│       │   └── health_theme.dart               # ธีม Energetic Dark Sport UI
│       ├── widgets/
│       │   ├── activity_ring_progress.dart     # วงแหวนความคืบหน้าและตัวเลขนับก้าวขนาดใหญ่
│       │   ├── live_motion_gauge.dart          # เกจวัดระดับความแรงของการขยับตัวสด
│       │   ├── metric_stat_cards.dart          # การ์ดแสดงผล แคลอรี, ระยะทาง, เวลาแอคทีฟ
│       │   ├── hourly_activity_chart.dart      # แผนภูมิแท่งสถิติตลอด 24 ชั่วโมง
│       │   └── goal_setting_dialog.dart        # ไดอะล็อกปรับตั้งเป้าหมายประจำวัน
│       └── screens/
│           └── home_tracker_screen.dart        # หน้าหลักติดตามสุขภาพสด
└── test/
    ├── step_detection_algorithm_test.dart      # Unit test สำหรับการคำนวณและจำแนกกิจกรรม
    └── widget_test.dart                        # Smoke test สำหรับแอปพลิเคชัน
```

---

## 🚀 วิธีการทดสอบและรันแอปพลิเคชัน

### 1. ติดตั้ง Dependencies
```bash
cd smart_health_tracker
flutter pub get
```

### 2. รันแอปพลิเคชันบนสมาร์ทโฟน
```bash
flutter run
```

### 3. รัน Unit Tests
```bash
flutter test
```
