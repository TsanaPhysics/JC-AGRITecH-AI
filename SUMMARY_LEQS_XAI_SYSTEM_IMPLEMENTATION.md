# รายงานสรุปผลการพัฒนาระบบและการดำเนินงานเชิงปฏิบัติการ
## โครงการพัฒนาระบบนิเวศดิจิทัลสมาร์ทฟาร์ม Edge xAI : LEQs-4D-AgriEnvi
**ผู้วิจัยและผู้รับผิดชอบโครงการ:** ผศ.ดร.ชีวะ ทัศนา  
**หน่วยงาน:** สาขาวิชาฟิสิกส์ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU)  
**วันที่บันทึกผลการปฏิบัติการ:** 24 ตุลาคม 2569  

---

## 1. บทสรุปผู้บริหาร (Executive Summary)

การดำเนินงานในรอบนี้ครอบคลุมการปรับปรุงโครงสร้างสถาปัตยกรรมทั้งระบบ (Full-Stack IoT & AI Ecosystem) ตั้งแต่ระดับฮาร์ดแวร์บอร์ดไมโครคอนโทรลเลอร์ ESP32-S3, แบ็กเอนด์ API และฐานข้อมูลบนเซิร์ฟเวอร์คลาวด์สาธารณะ, การรีแบรนด์ระบบหน้าเว็บแดชบอร์ด, การปรับปรุงและคอมไพล์แอปพลิเคชันมือถือ Flutter รองรับทั้ง Android (.apk) และ iOS (.ipa/PWA), การเปิดศูนย์ดาวน์โหลดแอปบนเว็บเซิร์ฟเวอร์จริง ตลอดจนการจัดการ Git Version Control ซิงก์ขึ้น GitHub ทั้ง 2 องค์กรหลักอย่างสมบูรณ์

---

## 2. รายละเอียดผลการดำเนินงานจำแนกตามส่วนระบบ

### 2.1 ด้านฮาร์ดแวร์และเฟิร์มแวร์บอร์ด ESP32-S3 ATD3.5
- **การกำหนดค่าจุดเชื่อมต่อ Telemetry ใหม่:**
  - แก้ไขไฟล์ [`UserConfigs.h`](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/esp32-board/include/UserConfigs.h) ปรับปลายทาง `DASHBOARD_URL` จากเซิร์ฟเวอร์เดิมมาเป็น:
    ```cpp
    #define DASHBOARD_URL "http://14.207.141.164/edge-xaiot-agrienvi/api/api.php?action=update_telemetry"
    ```
- **การคอมไพล์และการแฟลชเฟิร์มแวร์:**
  - คอมไพล์ซอร์สโค้ดเฟิร์มแวร์ผ่าน PlatformIO CLI สำเร็จสมบูรณ์ (RAM: 18.2%, Flash: 38.6%)
  - ทำการอัปโหลดเฟิร์มแวร์ผ่านพอร์ตซีเรียล `/dev/cu.usbserial-210` ลงบนบอร์ด ESP32-S3 สำเร็จ 100%
  - บอร์ดเชื่อมต่อเครือข่าย Wi-Fi (`JC_Home`) และส่งข้อมูลเซนเซอร์ 7-in-1, สภาพแวดล้อม 4D และสถานะรีเลย์ขึ้นเซิร์ฟเวอร์ด้วยสถานะ **HTTP 200 OK** ต่อเนื่องทุกรอบเวลา

---

### 2.2 ด้านเซิร์ฟเวอร์คลาวด์และ API Backend (14.207.141.164)
- **การแก้ไขปัญหา Disk Space & Logs:**
  - แก้ไขปัญหาพื้นที่จัดเก็บข้อมูลบนเซิร์ฟเวอร์เต็ม (Disk 100% Full) โดยการเคลียร์ Systemd Journals และ APT Cache คืนพื้นที่ว่างให้เซิร์ฟเวอร์กว่า 750 MB
- **การแก้ไขข้อผิดพลาดของซอร์สโค้ด PHP:**
  - แก้ไข Syntax Error (วงเล็บปีกกาเกิน `}` ที่บรรทัด 932) ในไฟล์ [`api.php`](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/api/api.php) และอัปโหลดไปยัง `/var/www/html/edge-xaiot-agrienvi/api/api.php`
- **การกำหนดสิทธิ์การเขียนข้อมูล (Permissions):**
  - ตั้งค่าสิทธิ์โฟลเดอร์ `/var/www/html/edge-xaiot-agrienvi/data` ให้สิทธิ์ `www-data:www-data` และโหมด `chmod 777` ทำให้เซิร์ฟเวอร์สามารถบันทึก `telemetry_state.json` และฐานข้อมูล SQLite ได้อย่างราบรื่น ข้อมูลระหว่างหน้าจอ ESP32 และ Web Dashboard ตรงกัน 100%

---

### 2.3 ด้านการรีแบรนด์ระบบสู่ "Edge xAI : LEQs-4D-AgriEnvi"
- **การปรับปรุงหน้าเว็บ Web Dashboard & Mobile Web:**
  - ปรับเปลี่ยนข้อความที่เป็น `Smart Farm AIoT` หรือ `LEQsxAI` ให้เป็นชื่อทางการ:
    **`Edge xAI : LEQs-4D-AgriEnvi`** ในทุกส่วนของระบบ:
    - [dashboard/components/qr_portal.php](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/dashboard/components/qr_portal.php)
    - [dashboard/components/navbar.php](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/dashboard/components/navbar.php)
    - [mobile/components/navbar.php](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/mobile/components/navbar.php)
    - [mobile/components/qr_portal.php](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/mobile/components/qr_portal.php)
    - แผงควบคุม Admin Panel และส่วนเอกสารแนบโครงการ

---

### 2.4 ด้านแอปพลิเคชันมือถือ Flutter (handysense_flutter)
- **การปรับปรุงและรีแบรนด์ตัวแอป:**
  - `pubspec.yaml`: กำหนดคำอธิบายโครงการเป็น `"HandySense Edge xAI : LEQs-4D-AgriEnvi — ผศ.ดร.ชีวะ ทัศนา, RBRU"`
  - `AndroidManifest.xml`: กำหนดชื่อแอปที่ปรากฏบนจอมือถือเป็น `android:label="Edge xAI LEQs-4D"`
  - `Info.plist (iOS)`: กำหนด `CFBundleDisplayName` เป็น `Edge xAI LEQs-4D`
  - `main.dart`: ปรับหัวบาร์และ Navigation Title เป็น `Edge xAI : LEQs-4D`
  - `settings_screen.dart` & `dashboard_screen.dart`: อัปเดตข้อความเครดิตและสถานะการเชื่อมต่อ
- **การแก้ไข Dependency & ความเข้ากันได้ของ Dart SDK:**
  - ปรับปรุงข้อจำกัด SDK ใน `pubspec.yaml` เป็น `sdk: '>=3.3.0 <4.0.0'` ให้เข้ากับ Dart SDK 3.7.0 ในเครื่อง
  - จัดการเวอร์ชันแพ็กเกจ `fl_chart: ^0.70.2`, `google_fonts: ^6.2.1`, `intl: ^0.20.2`, `flutter_lints: ^5.0.0`
  - ปรับจูนโค้ด [relay_control_card.dart](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/handysense_flutter/lib/presentation/widgets/relay_control_card.dart) ให้ใช้พารามิเตอร์ `activeColor` ตามมาตรฐาน Flutter Switch
  - ลบโค้ด Unused Method ใน [dashboard_screen.dart](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/handysense_flutter/lib/presentation/screens/dashboard_screen.dart) และแก้ไข `mounted` check
  - ผลการรัน `flutter analyze`: **No issues found! (0 warnings, 0 errors)**

---

### 2.5 ด้านการคอมไพล์และการเผยแพร่แอปพลิเคชัน (Mobile Distribution)
- **การบิลด์แพ็กเกจสำหรับ Android:**
  - ดำเนินการ `flutter build apk --release` ได้ผลลัพธ์เป็นไฟล์ Release APK ขนาด **28.6 MB**
- **การบิลด์แพ็กเกจสำหรับ iOS:**
  - ปรับปรุง Xcode Target Integrity ใน `Podfile` และ `project.pbxproj` รองรับ iOS 15.0+
  - ปรับปรุง `AppDelegate.swift` และ `SceneDelegate.swift` เป็นไปตามมาตรฐาน UIKit + Flutter Engine
  - ดำเนินการ `flutter build ios --release --no-codesign` และแพ็กเกจ `Runner.app` เป็นไฟล์ **IPA Payload** ขนาด **15.4 MB**
- **การสร้าง Web Download Portal บนเซิร์ฟเวอร์จริง:**
  - สร้างหน้าพอร์ทัลดาวน์โหลด [download/index.php](file:///Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/edge-xaiot-agrienvi/download/index.php) ดีไซน์สไตล์ Cyber Obsidian Glassmorphism
  - อัปโหลดไฟล์ติดตั้งขึ้นบนเซิร์ฟเวอร์ `14.207.141.164` พร้อมสิทธิ์เข้าถึง:
    1. `Edge_xAI_LEQs-4D_AgriEnvi_v1.0.apk` (28.6 MB)
    2. `Edge_xAI_LEQs-4D_AgriEnvi_v1.0.ipa` (15.4 MB)
    3. `app-release.apk` และ `app-release.ipa` (Symlink/Copy สำรอง)
  - ทดสอบผ่าน curl ตรวจสอบค่าตอบรับ **HTTP 200 OK** พร้อมรองรับการดาวน์โหลดโดยตรงผ่านเบราว์เซอร์หรือสแกนผ่าน QR Code

---

### 2.6 ด้านการซิงก์ข้อมูลขึ้น GitHub (Multi-Remote Version Control)
- ตรวจสอบและตั้งค่า Git Remote ทั้ง 2 ช่องทาง:
  1. `origin`: `git@github.com:TsanaPhysics/JC-AGRITecH-AI.git` (ผ่าน SSH Key ของ `TsanaPhysics`)
  2. `tsanaphy2023`: `https://github.com/Tsanaphy2023/JC-AGRITecH2026.git`
- ดำเนินการ Commit รหัส `48ad103` บันทึกการอัปเดตระบบทั้งหมด
- ดำเนินการ Push ขึ้นสู่ Branch `main` ของทั้ง 2 Repositories สำเร็จสมบูรณ์ โดยไม่นำไฟล์ไบนารีขนาดใหญ่เข้า git (กำหนดผ่าน `.gitignore`) ทำให้ repository โหลดเร็วและเป็นไปตามระเบียบของ GitHub

---

## 3. สรุปรายการลิงก์และทรัพยากรระบบที่พร้อมใช้งานทันที

| บริการ / ระบบ | URL / ข้อมูลการเข้าถึง | สถานะ |
|---|---|---|
| **ศูนย์ดาวน์โหลดแอปมือถือ** | [http://14.207.141.164/edge-xaiot-agrienvi/download/](http://14.207.141.164/edge-xaiot-agrienvi/download/) | ออนไลน์ (HTTP 200) |
| **ดาวน์โหลด APK โดยตรง** | [http://14.207.141.164/edge-xaiot-agrienvi/download/Edge_xAI_LEQs-4D_AgriEnvi_v1.0.apk](http://14.207.141.164/edge-xaiot-agrienvi/download/Edge_xAI_LEQs-4D_AgriEnvi_v1.0.apk) | ออนไลน์ (28.6 MB) |
| **ดาวน์โหลด IPA โดยตรง** | [http://14.207.141.164/edge-xaiot-agrienvi/download/Edge_xAI_LEQs-4D_AgriEnvi_v1.0.ipa](http://14.207.141.164/edge-xaiot-agrienvi/download/Edge_xAI_LEQs-4D_AgriEnvi_v1.0.ipa) | ออนไลน์ (15.4 MB) |
| **เว็บแอปมือถือ PWA** | [http://14.207.141.164/edge-xaiot-agrienvi/mobile/index.php](http://14.207.141.164/edge-xaiot-agrienvi/mobile/index.php) | ออนไลน์ (Add to Home Screen ได้) |
| **Web Dashboard หลัก** | [http://14.207.141.164/edge-xaiot-agrienvi/dashboard/index.php](http://14.207.141.164/edge-xaiot-agrienvi/dashboard/index.php) | ออนไลน์ (ESP32 Live Sync) |
| **GitHub Repo 1 (TsanaPhysics)** | `git@github.com:TsanaPhysics/JC-AGRITecH-AI.git` | อัปเดตล่าสุด (Commit 48ad103) |
| **GitHub Repo 2 (Tsanaphy2023)** | `https://github.com/Tsanaphy2023/JC-AGRITecH2026.git` | อัปเดตล่าสุด (Commit 48ad103) |
