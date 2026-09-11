#pragma once

/**
 * ============================================================================
 * Sensor Type Identifiers (SensorSupport.h)
 * กำหนดรหัสระบุชนิดเซนเซอร์แต่ละกลุ่มสำหรับระบบ Smart Agriculture
 * ============================================================================
 */

#define SENSOR_NONE                 (0)

// 1. Temperature & Humidity Sensors (อากาศ)
#define SHT20                       (11)
#define SHT30                       (12)
#define SHT45                       (13) // Sensirion SHT45 (เกรดความแม่นยำสูงสุด ±1.0% RH, ±0.1°C)
#define ATS_TH                      (14) // RS485 Temp & Humidity
#define XY_MD02                     (15) // RS485 Industrial Temp & Humidity

// 2. Light Sensors (ความเข้มแสง)
#define BH1750                      (31) // โดมตะวัน I2C (ชิป BH1750FVI ช่วง 0 - 65,535 Lux)
#define ATS_LUX                     (32) // โดมตะวัน RS485 Modbus
#define ANALOG_LIGHT                (33) // โดมตะวันแบบแรงดันอนาล็อก 0-3.3V

// 3. Soil Moisture Sensors (ความชื้นดินเดี่ยว)
#define ANALOG_SOIL_STICK           (21) // เซนเซอร์ Soil Stick เกษตรไทย IoT (Capacitive Analog 0-3.3V)
#define RS485_SOIL_SENSOR           (22) // เซนเซอร์วัดความชื้นดิน RS485 Modbus

// 4. Soil Multi-parameter Sensor (ดิน 7-in-1 รวม NPK, pH, EC, Temp, Moisture)
#define RS485_SOIL_7IN1             (41) // Soil Multi-parameter Sensor (Modbus RTU Holding Registers)
