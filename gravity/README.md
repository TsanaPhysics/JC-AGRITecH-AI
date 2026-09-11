# JC-AGRITecH2026: ระบบควบคุมและตรวจวัดสภาพแวดล้อมทางการเกษตรอัจฉริยะ (Smart Agriculture IoT)
## บอร์ดควบคุม ATD3.5-S3 (ESP32-S3) + ATD3.5-S3 Farm1 Shield

ชุดซอฟต์แวร์และเฟิร์มแวร์นี้ได้รับการออกแบบและพัฒนาขึ้นโดยเฉพาะสำหรับเชื่อมต่อและประมวลผลข้อมูลจากเซ็นเซอร์ทางการเกษตรครบวงจร ครอบคลุมทั้งสภาพอากาศ แสงสว่าง และคุณสมบัติของดินเชิงลึก:

1. **อุณหภูมิและความชื้นสัมพัทธ์ในอากาศ (Air Temp & RH)** — เซนเซอร์ **Sensirion SHT45** (โพรบสแตนเลสกรองฝุ่นและละอองน้ำ Sintered Metal)
2. **ความเข้มแสงสว่างและรังสีดวงอาทิตย์ (Light & Solar Radiation)** — เซนเซอร์ **โดมตะวันกันน้ำ (BH1750)**
3. **ความชื้นในดินตัวเดี่ยว (Soil Moisture)** — เซนเซอร์ **Soil Stick เกษตรไทย IoT** (Capacitive Analog 0-3.3V)
4. **คุณสมบัติดินเชิงลึกรอบด้าน (Soil 7-in-1)** — เซนเซอร์ **Soil Multi-parameter Sensor** ผ่านบัสอุตสาหกรรม **RS485 Modbus RTU** (วัดความชื้น, อุณหภูมิดิน, EC ความเค็ม, pH กรด-ด่าง, และธาตุอาหาร N-P-K)

---

## 1. โครงสร้างไฟล์ในโปรเจกต์ (`gravity/`)

```
gravity/
├── platformio.ini         # การตั้งค่า PlatformIO สำหรับ ESP32-S3 (16MB Flash, PSRAM)
├── WIRING_DIAGRAM.md      # คู่มือการต่อสายไฟของเซนเซอร์ทุกตัวอย่างละเอียด
├── README.md              # คำอธิบายภาพรวมและคู่มือการใช้งาน
├── include/
│   ├── PinConfigs.h       # กำหนด Pinout (I2C SDA/SCL, RS485 RX/TX, ADC, Relay)
│   ├── SensorSupport.h    # รหัสระบุประเภทเซนเซอร์ (Sensor Model IDs)
│   ├── UserConfigs.h      # จุดตั้งค่าและสวิตช์เลือกเซนเซอร์ / ค่า Calibrate
│   └── AgriSensors.h      # สถาปัตยกรรมข้อมูล (Data Structs) และคำนวณค่าดัชนีทางพืช (VPD, DewPoint)
└── src/
    ├── AgriSensors.cpp    # ไดรเวอร์อ่านค่า I2C SHT45, BH1750, ADC, และ RS485 Modbus RTU
    └── main.cpp           # วงรอบการทำงานหลัก (Telemetry Acquisition & Smart Automation Rules)
```

---

## 2. คุณสมบัติเด่นของเฟิร์มแวร์ (Technical Highlights)

- **I2C Non-blocking Driver พร้อม CRC8 Validation:** อ่านค่าจากชิป Sensirion SHT45 โดยตรวจสอบความถูกต้องของไบต์ข้อมูลด้วยอัลกอริทึม CRC8 ตามมาตรฐานโรงงาน ป้องกันข้อมูลเพี้ยนจากสายไฟยาว
- **Multi-sampling Digital Filter:** อ่านค่าสัญญาณอนาล็อกจากเซนเซอร์ Soil Stick เกษตรไทย IoT แบบ Oversampling 10 ตัวอย่าง เพื่อตัดสัญญาณรบกวน (Noise/Spikes)
- **Modbus RTU Engine พร้อม CRC16:** สื่อสารกับเซ็นเซอร์วัดดิน 7-in-1 ด้วย Hardware UART2 (GPIO40/GPIO41) พร้อมตรวจสอบผลรวมตรวจสอบ CRC16 Polynomial 0xA001 อย่างเข้มงวด และมีระบบ Flush Buffer อัตโนมัติป้องกันบัสค้าง
- **Agronomy Scientific Metrics:** คำนวณค่าทางปฐพีวิทยาและอุตุนิยมวิทยาการเกษตรเพิ่มเติมอัตโนมัติ:
  - **จุดน้ำค้าง (Dew Point):** ประเมินความเสี่ยงของการเกิดหยดน้ำเกาะใบพืช
  - **แรงดึงระเหยน้ำ (Vapor Pressure Deficit - VPD):** ดัชนีบอกการคายน้ำและการเปิดปากใบของพืช (Optimal: 0.8 - 1.2 kPa)
  - **รังสีดวงอาทิตย์ (Estimated Solar Radiation):** แปลงจาก Lux เป็น W/m² สำหรับการวิเคราะห์การสะสมพลังงานแสงของแปลง
- **Smart Agronomy Decision Automation:** มีอัลกอริทึม Hysteresis คอยควบคุมปั๊มน้ำ (Relay 1) และระบบลดความร้อนพ่นหมอก (Relay 3) อัตโนมัติตามความต้องการของพืช

---

## 3. ขั้นตอนการติดตั้งและใช้งาน

### วิธีที่ 1: รันด้วย VS Code + PlatformIO (แนะนำ)
1. เปิดโปรแกรม VS Code
2. ติดตั้ง Extension: **PlatformIO IDE**
3. เลือก **File > Open Folder...** แล้วเปิดโฟลเดอร์ `/Users/chewathassana/Desktop/handysense/gravity`
4. เสียบสาย USB-C เข้าที่ช่อง **Upload** ของบอร์ด ATD3.5-S3
5. เสียบอะแดปเตอร์ **12V 2A** เข้าที่แจ็คไฟของบอร์ด Farm1 Shield
6. กดปุ่ม **Build** (เครื่องหมายถูก) และ **Upload** (ลูกศรขวา) ที่แถบด้านล่างของ PlatformIO
7. เปิด **Serial Monitor** ที่ Baudrate `115200` จะเห็นตารางแสดงผลค่าเซนเซอร์ทุกตัวแบบเรียลไทม์

### วิธีที่ 2: รันด้วย Arduino IDE
1. เปิดโปรแกรม Arduino IDE
2. เลือกบอร์ดเป็น **ESP32S3 Dev Module**
   - Flash Size: **16MB (128Mb)**
   - Partition Scheme: **16M Flash (3MB APP/9.9MB FATFS)**
   - PSRAM: **OPI PSRAM**
3. คัดลอกโฟลเดอร์ `include` และ `src` ไปเปิดในโปรเจกต์
4. ติดตั้ง Library เสริม: `ModbusMaster`, `BH1750`
5. กด Compile และ Upload ลงบอร์ดได้ทันที

---

## 4. การปรับแต่งค่าการสอบเทียบ (Calibration Guide)

หากต้องการความแม่นยำสูงสุดสำหรับ **Soil Stick เกษตรไทย IoT** ในเนื้อดินของแปลงจริง ให้ปรับค่าในไฟล์ `include/UserConfigs.h`:

```cpp
#define SOIL_STICK_ADC_AIR    2950    // ค่า ADC ขณะหัววัดอยู่ในอากาศแห้ง (0% RH)
#define SOIL_STICK_ADC_WATER  1450    // ค่า ADC ขณะหัววัดจุ่มลงในน้ำเปล่า (100% RH)
```

โดยผู้ใช้สามารถดูค่า ADC ดิบได้จาก Serial Monitor ของโปรแกรม แล้วนำมาแทนค่าทั้งสองบรรทัดนี้ได้ทันที

---

## 5. การนำไปเชื่อมต่อเข้ากับ HandySense Dashboard

หากต้องการนำโค้ดไปรวมเข้ากับระบบ HandySense เดิม:
- ซอร์สโค้ดในโมดูล `AgriSensors.cpp` และ `AgriSensors.h` สามารถนำไปเรียกใช้ในฟังก์ชัน `Sensor_getSoilEC()`, `Sensor_getSoilPH()`, `Sensor_getSoilN()`, `Sensor_getSoilP()`, `Sensor_getSoilK()` ของเฟิร์มแวร์ HandySense ได้ทันที
- ข้อมูลพารามิเตอร์ทั้งหมดจะถูกรวมเข้ากับ NETPIE MQTT Shadow Topic เพื่อแสดงผลบนแดชบอร์ดและแอปพลิเคชันมือถือ
