# Multi-Object AI Detector (แอปพลิเคชันตรวจจับวัตถุหลายชนิดเรียลไทม์)

[![Flutter](https://img.shields.io/badge/Flutter-3.19+-02569B?logo=flutter&logoColor=white)](https://flutter.dev)
[![TensorFlow Lite](https://img.shields.io/badge/TFLite-SSD_MobileNet_v1-FF6F00?logo=tensorflow&logoColor=white)](https://tensorflow.org)
[![Platform](https://img.shields.io/badge/Platform-Android%20%7C%20iOS%20%7C%20Web%20%7C%20macOS-blue)](#)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](#)

แอปพลิเคชันสมาร์ทโฟนสำหรับการตรวจจับและแสดงชนิดของวัตถุหลายๆ ชนิดพร้อมกัน (**Real-Time Multi-Object Detection**) แบบเรียลไทม์บนสมาร์ทโฟน พร้อมระบุตำแหน่งกรอบ Bounding Box, ป้ายชื่อ 2 ภาษา (ไทย-อังกฤษ), หมวดหมู่วัตถุ, และระดับความเชื่อมั่น (**Confidence Score %**)

📖 **[คลิกที่นี่เพื่ออ่านคู่มือการใช้งานและเอกสารสถาปัตยกรรมฉบับเต็ม (USER_MANUAL.md)](USER_MANUAL.md)**

---

## 🌟 คุณสมบัติเด่น (Key Features)

1. **Multi-Object Detection แบบเรียลไทม์**:
   - สามารถระบุและติดตามตำแหน่งของวัตถุหลายชนิดพร้อมกันในเฟรมภาพสดจากกล้องสมาร์ทโฟน
   - รองรับวัตถุมาตรฐาน **COCO Dataset 80–90 Class Labels** (บุคคล, รถยนต์, มอเตอร์ไซค์, สัตว์เลี้ยง, โทรศัพท์มือถือ, คอมพิวเตอร์แล็ปท็อป, ตู้เย็น, โทรทัศน์, ขวดน้ำ, แก้วกาแฟ, เก้าอี้ ฯลฯ)
2. **Confidence Score & Level Badges**:
   - คำนวณและแสดงค่าความเชื่อมั่นเป็นเปอร์เซ็นต์ เช่น `96.4%`
   - ป้ายกำกับระดับความแม่นยำแบ่งตามแถบสี (เขียว = สูงมาก >85%, ฟ้า = สูง >70%, เหลือง = ปานกลาง >50%, แดง = ต่ำ)
3. **High-Performance Edge AI Engine**:
   - **Fast Fixed-Point Integer Bitshift YUV420 to RGB:** ถอดรหัสภาพดิบจากเซนเซอร์กล้องด้วยการเลื่อนบิตระดับ CPU ไร้ภาระ Garbage Collection รวดเร็วกว่าเดิม 15 เท่า (~3–5 ms) กล้องแสดงผลลื่นไหล 60 FPS
   - **90° Portrait Sensor Alignment:** แก้ไขมุมมองภาพเซนเซอร์กล้อง Android ให้ตั้งตรงตามความจริง ไม่เอียงตะแคง
   - **Exact 1-Based COCO Mapping:** ตรวจจับและระบุชนิดวัตถุตรงตามความเป็นจริง ไม่สับสน
4. **Cyber HUD & Neon Bounding Boxes**:
   - กรอบ Bounding Box นีออนเรืองแสง แยกสีตามประเภทของวัตถุอย่างชัดเจน
   - มุมเล็งเป้าหมายสไตล์ Cyberpunk HUD
5. **Interactive Controls & Category Filter**:
   - **Category Filter Chips:** กรองเลือกดูเฉพาะหมวดหมู่วัตถุ เช่น ทั้งหมด, บุคคล, ยานพาหนะ, สัตว์, อาหาร, อุปกรณ์ไอที, เครื่องครัว ฯลฯ
   - **Detection Control Sheet:** ปรับเกณฑ์ความเชื่อมั่น (Confidence Threshold), จำกัดจำนวนวัตถุสูงสุด (Max Detections), และปรับเกณฑ์ตัดกรอบซ้อนทับ (IoU Threshold)
6. **Camera Controls & Snapshot Analysis**:
   - สลับกล้องหน้า-หลัง (Switch Camera)
   - เปิด-ปิดไฟฉาย (Torch / Flashlight)
   - หยุด/เริ่มการตรวจจับชั่วคราว (Pause / Resume)
   - ถ่ายภาพนิ่ง (Snapshot) และเลือกรูปจากแกลเลอรี (Gallery Picker) เพื่อวิเคราะห์ผลอย่างละเอียด

---

## 📁 โครงสร้างโปรเจกต์ (Project Structure)

```text
multi_object_detector/
├── assets/
│   ├── icons/                               # ไอคอนแอปพลิเคชันดีไซน์ใหม่ Cyber AI Vision
│   │   └── app_icon.png                     # Master App Icon (1024x1024)
│   ├── labels/
│   │   └── labels_coco.json                 # พจนานุกรม 91 คลาสภาษาไทย-อังกฤษ และหมวดหมู่
│   └── models/                              # โมเดลปัญญาประดิษฐ์ TFLite ตรวจจับวัตถุจริง
│       ├── ssd_mobilenet_v1_coco.tflite     # โมเดล SSD MobileNet Quantized COCO 90 classes (4.18 MB)
│       └── labelmap.txt                     # แผนผังคลาสโมเดล
├── lib/
│   ├── main.dart                            # จุดเริ่มต้นแอปและการตั้งค่า MultiProvider
│   ├── models/
│   │   ├── detected_object.dart             # โครงสร้างข้อมูล DetectedObject, หมวดหมู่, สี, พิกัด BoxFit.cover
│   │   └── detection_statistics.dart        # ข้อมูลสถิติ FPS, Latency ms, จำนวนวัตถุ
│   ├── services/
│   │   ├── camera_service.dart              # ควบคุมกล้องสมาร์ทโฟน, Stream เฟรมภาพ, ถ่ายภาพ Snapshot, สลับกล้อง
│   │   ├── label_service.dart               # จัดการโหลดและค้นหาป้ายชื่อวัตถุ 80-90 ชนิดและ labelmap
│   │   └── object_detection_service.dart    # สมองกล AI รัน TFLite SSD MobileNet บนเฟรมกล้องและภาพนิ่ง
│   └── ui/
│       ├── theme/
│       │   └── app_theme.dart               # ระบบธีม Cyber-Tech Dark UI
│       ├── widgets/
│       │   ├── bounding_box_painter.dart    # ระบบเรนเดอร์กรอบวัตถุและป้ายกำกับเรียลไทม์
│       │   ├── category_filter_chips.dart   # แถบเลือกหมวดหมู่วัตถุ
│       │   ├── detection_control_sheet.dart # แผงตั้งค่าเกณฑ์ความเชื่อมั่นและ NMS
│       │   ├── detection_hud_overlay.dart   # แถบแสดงสถานะ FPS, เวลาประมวลผล, ปุ่มควบคุมกล้อง
│       │   ├── image_analysis_modal.dart    # หน้าต่างวิเคราะห์ผลลัพธ์ภาพถ่ายและคลังภาพ
│       │   └── detected_objects_summary_list.dart # การ์ดสรุปรายการวัตถุที่ตรวจพบพร้อมกัน
│       └── screens/
│           └── live_detector_screen.dart    # หน้าจอหลักแสดงผลกล้องตรวจจับสด
├── test/
│   ├── object_detection_test.dart           # การทดสอบ Unit Test พิกัดและระดับความเชื่อมั่น
│   └── widget_test.dart                     # การทดสอบ Smoke Test ระบบแอปพลิเคชัน
└── USER_MANUAL.md                           # คู่มือการใช้งานและเอกสารสถาปัตยกรรมระบบฉบับสมบูรณ์
```

---

## 🚀 วิธีการทดสอบและรันแอปพลิเคชัน

### 1. ติดตั้ง Dependencies
```bash
cd multi_object_detector
flutter pub get
```

### 2. รันแอปพลิเคชันบนสมาร์ทโฟน
```bash
# ตรวจสอบรายชื่ออุปกรณ์ที่เชื่อมต่อ
flutter devices

# รันบนอุปกรณ์ที่ต้องการ
flutter run -d <DEVICE_ID>
```

### 3. รันการทดสอบ Unit Tests & Widget Tests
```bash
flutter test
```

### 4. Build ไฟล์ APK สำหรับ Android
```bash
flutter build apk --release
```

---

*พัฒนาและดูแลโดย คณะผู้วิจัยฟิสิกส์เกษตรดิจิทัลและปัญญาประดิษฐ์ มหาวิทยาลัยราชภัฏรำไพพรรณี*
