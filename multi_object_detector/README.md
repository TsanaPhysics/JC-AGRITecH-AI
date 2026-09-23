# Multi-Object AI Detector (แอปพลิเคชันตรวจจับวัตถุหลายชนิดเรียลไทม์)

แอปพลิเคชันสมาร์ทโฟนสำหรับการตรวจจับและแสดงชนิดของวัตถุหลายๆ ชนิดพร้อมกัน (**Real-Time Multi-Object Detection**) แบบเรียลไทม์ พร้อมระบุตำแหน่งกรอบ Bounding Box, ป้ายชื่อ 2 ภาษา (ไทย-อังกฤษ), หมวดหมู่วัตถุ, และระดับความเชื่อมั่น (**Confidence Score %**)

พัฒนาโดยใช้เทคโนโลยี **Flutter (Dart) + TFLite + Edge Vision Real-Time Engine**

---

## 🌟 คุณสมบัติเด่น (Key Features)

1. **Multi-Object Detection แบบเรียลไทม์**:
   - สามารถระบุและติดตามตำแหน่งของวัตถุหลายชนิดพร้อมกันในเฟรมภาพสดจากกล้องสมาร์ทโฟน
   - รองรับวัตถุมาตรฐาน **COCO 80 Class Labels** (บุคคล, รถยนต์, รถจักรยานยนต์, สัตว์เลี้ยง, โทรศัพท์มือถือ, แล็ปท็อป, ขวดน้ำ, แก้วกาแฟ, เก้าอี้, ต้นไม้กระถาง ฯลฯ)
2. **Confidence Score & Level Badges**:
   - คำนวณและแสดงค่าความเชื่อมั่นเป็นเปอร์เซ็นต์ เช่น `96.4%`
   - ป้ายกำกับระดับความแม่นยำแบ่งตามแถบสี (เขียว = สูงมาก >85%, ฟ้า = สูง >70%, เหลือง = ปานกลาง >50%, แดง = ต่ำ)
3. **Cyber HUD & Neon Bounding Boxes**:
   - กรอบ Bounding Box นีออนเรืองแสง แยกสีตามประเภทของวัตถุอย่างชัดเจน
   - มุมเล็งเป้าหมายสไตล์ Cyberpunk HUD
4. **Interactive Control & Settings**:
   - **Confidence Threshold Slider**: ปรับเกณฑ์ความเชื่อมั่นขั้นต่ำ (10% - 95%) เพื่อกรองวัตถุที่ไม่มั่นใจออก
   - **Max Detections Slider**: จำกัดจำนวนวัตถุสูงสุดที่จะแสดงพร้อมกัน (1 - 20 ชิ้น)
   - **NMS IoU Slider**: ควบคุมการตัดกรอบซ้อนทับ (Non-Maximum Suppression)
   - **Category Filter Chips**: เลือกแสดงเฉพาะหมวดหมู่วัตถุที่สนใจ (All, People, Vehicles, Animals, Food, Electronics, Furniture, etc.)
5. **Camera Controls & Snapshot**:
   - สลับกล้องหน้า - กล้องหลัง (Switch Front / Back Camera)
   - เปิด-ปิดไฟฉาย (Torch / Flashlight)
   - ปุ่มพัก/เริ่มตรวจจับ (Pause / Resume)
   - ปุ่มถ่ายภาพบันทึกผล (Snapshot) พร้อมตารางสรุปรายการวัตถุและความเชื่อมั่น

---

## 📁 โครงสร้างโปรเจกต์ (Project Structure)

```text
multi_object_detector/
├── assets/
│   ├── labels/
│   │   └── labels_coco.json                 # พจนานุกรม 80 คลาสภาษาไทย-อังกฤษ และหมวดหมู่
│   └── models/                              # โฟลเดอร์สำหรับโมเดล TFLite
├── lib/
│   ├── main.dart                            # จุดเริ่มต้นแอปและการตั้งค่า MultiProvider
│   ├── models/
│   │   ├── detected_object.dart             # โครงสร้างข้อมูล DetectedObject, หมวดหมู่, สี, พิกัด
│   │   └── detection_statistics.dart        # ข้อมูลสถิติ FPS, Latency ms, จำนวนวัตถุ
│   ├── services/
│   │   ├── camera_service.dart              # ควบคุมกล้องสมาร์ทโฟน, Stream เฟรมภาพ, ไฟฉาย, สลับกล้อง
│   │   ├── label_service.dart               # จัดการโหลดและค้นหาป้ายชื่อวัตถุ 80 ชนิด
│   │   └── object_detection_service.dart    # สมองกล AI วิเคราะห์ภาพ, NMS, คำนวณค่าสถิติ
│   └── ui/
│       ├── theme/
│       │   └── app_theme.dart               # ระบบธีม Cyber-Tech Dark UI
│       ├── widgets/
│       │   ├── bounding_box_painter.dart    # ระบบเรนเดอร์กรอบวัตถุและป้ายกำกับเรียลไทม์
│       │   ├── category_filter_chips.dart   # แถบเลือกหมวดหมู่วัตถุ
│       │   ├── detection_control_sheet.dart # แผงตั้งค่าเกณฑ์ความเชื่อมั่นและ NMS
│       │   ├── detection_hud_overlay.dart   # แถบแสดงสถานะ FPS, เวลาประมวลผล, ปุ่มควบคุมกล้อง
│       │   └── detected_objects_summary_list.dart # การ์ดสรุปรายการวัตถุที่ตรวจพบพร้อมกัน
│       └── screens/
│           └── live_detector_screen.dart    # หน้าจอหลักแสดงผลกล้องตรวจจับสด
└── test/
    ├── object_detection_test.dart           # การทดสอบ Unit Test พิกัดและระดับความเชื่อมั่น
    └── widget_test.dart                     # การทดสอบ Smoke Test ระบบแอปพลิเคชัน
```

---

## 🚀 วิธีการทดสอบและรันแอปพลิเคชัน

### 1. ติดตั้ง Dependencies
```bash
cd multi_object_detector
flutter pub get
```

### 2. รันแอปพลิเคชันบนสมาร์ทโฟน (Android / iOS / Web / macOS)
```bash
# ตรวจสอบอุปกรณ์ที่เชื่อมต่อ
flutter devices

# รันบนอุปกรณ์ที่ต้องการ
flutter run
```

### 3. รัน Unit Tests
```bash
flutter test
```
