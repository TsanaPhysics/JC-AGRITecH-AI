#pragma once
#include "SensorSupport.h"
#include "PinConfigs.h"

/**
 * ============================================================================
 * User Sensor Configurations (UserConfigs.h)
 * กำหนดค่าการใช้งานเซนเซอร์ตามฮาร์ดแวร์จริงของผู้ใช้
 * ============================================================================
 */

// 1. อุณหภูมิและความชื้นสัมพัทธ์ในอากาศ (Air Temperature & Relative Humidity)
// ตัวอย่าง: โพรบ SHT45 (Sensirion SHT45 ปลอกกรองโลหะ sintered metal)
#define TEMP_HUMID_SENSOR           SHT45
#define SHT45_I2C_ADDR              0x44

// 2. ความเข้มแสง (Light Intensity)
// ตัวอย่าง: เซนเซอร์โดมตะวันกันน้ำ (Dome Light Sensor) ผ่านบัส I2C ชิป BH1750
#define LIGHT_SENSOR                BH1750
#define BH1750_I2C_ADDR             0x23    // ขา ADDR ต่อ GND = 0x23 (ค่ามาตรฐาน), ต่อ VCC = 0x5C

// 3. เซนเซอร์วัดความชื้นในดินตัวเดี่ยว (Soil Stick เกษตรไทย IoT)
// ทำงานด้วยหลักการวัดความจุไฟฟ้า (Capacitive) ป้องกันปัญหาการกัดกร่อนของโพรบ
#define SOIL_SENSOR                 ANALOG_SOIL_STICK
#define SOIL_STICK_PIN              SOIL_STICK_ADC_PIN // ขา A1_PIN (GPIO1 บน Farm1 Shield)

// ค่าคาลิเบรต ADC (ESP32-S3 ADC 12-bit: 0 - 4095) สำหรับแปลงเป็นความชื้น 0 - 100%
// แนะนำ: วัดค่าดิบในอากาศแห้ง (Air/Dry) และในน้ำเปล่า (Water/Wet)
#define SOIL_STICK_ADC_AIR          2950    // ค่า ADC ขณะหัววัดอยู่ในอากาศแห้ง (ความชื้น 0%)
#define SOIL_STICK_ADC_WATER        1450    // ค่า ADC ขณะหัววัดจุ่มในน้ำ (ความชื้น 100%)

// 4. เซนเซอร์วัดคุณสมบัติดินรอบด้าน (Soil Multi-parameter 7-in-1: RS485 Modbus RTU)
// รุ่นที่ใช้งาน: SN-3002-TR-ECTHNPKPH-N01
// สายไฟ: น้ำตาล=VCC(12V), ดำ=GND, เหลือง=485-A, น้ำเงิน=485-B
// วัดค่า: Moisture, Soil Temp, EC (ความเค็ม), pH (กรด-ด่าง), N (ไนโตรเจน), P (ฟอสฟอรัส), K (โพแทสเซียม)
#define SOIL_7IN1_SENSOR            RS485_SOIL_7IN1
#define SOIL_7IN1_SLAVE_ID          1       // Slave ID / Station Address (โรงงานมักตั้งเป็น 1 หรือ 2)
#define SOIL_7IN1_BAUDRATE          4800    // Baudrate มาตรฐานจากโรงงานรุ่น SN-3002 คือ 4800 (หรือ 9600)

// ช่วงเวลาในการอ่านค่าเซนเซอร์ (มิลลิวินาที)
#define SENSOR_READ_INTERVAL_MS     2000    // อ่านค่าทุกๆ 2 วินาที
#define DISPLAY_UPDATE_INTERVAL_MS  1000    // อัปเดตหน้าจอทุกๆ 1 วินาที
#define MODBUS_QUERY_DELAY_MS       50      // หน่วงเวลาสลับโพลลิ่ง Modbus เพื่อลดสัญญาณสะท้อนในบัส

// ============================================================================
// 5. การตั้งค่า Wi-Fi, Google Firebase และ Custom Python Server (FastAPI + Streamlit)
// ============================================================================
#define WIFI_SSID                   "JC_Home"              // Wi-Fi SSID (2.4GHz)
#define WIFI_PASSWORD               "JChome2023"           // รหัสผ่าน Wi-Fi (Capital H)

// 5.1 ตัวเลือกส่งเข้า Custom Server ของตนเอง (Python FastAPI + SQLite/PostgreSQL)
#define ENABLE_CUSTOM_SERVER        true
// กำหนดชี้ IP ปลายทางมายังเครื่อง Mac ในวง Wi-Fi เดียวกัน (192.168.0.120:8000)
#define CUSTOM_SERVER_URL           "http://192.168.0.120:8000/api/telemetry"

// 5.2 ตัวเลือกส่งเข้า Google Firebase Realtime Database
#define ENABLE_FIREBASE             false
#define FIREBASE_HOST               "your-project-default-rtdb.asia-southeast1.firebasedatabase.app"
#define FIREBASE_AUTH               ""

// ช่วงเวลาในการส่งข้อมูลขึ้น Server / Firebase (มิลลิวินาที) เช่น 15000 = ทุก 15 วินาที
#define CLOUD_UPLOAD_INTERVAL_MS    15000


