#pragma once
#include <Arduino.h>
#include "UserConfigs.h"
#include "SoilNeuralCalibrator.h"

/**
 * ============================================================================
 * Agricultural Sensor Data Structures
 * โครงสร้างข้อมูลสำหรับจัดเก็บค่าที่อ่านได้จากเซนเซอร์เกษตรกรรม
 * ============================================================================
 */

// ข้อมูลสภาพอากาศ (Microclimate)
struct AirEnvironmentData {
    float temperature;      // อุณหภูมิอากาศ (°C)
    float humidity;         // ความชื้นสัมพัทธ์ในอากาศ (%RH)
    float dewPoint;         // จุดน้ำค้าง Dew Point (°C)
    float vpd;              // แรงดึงระเหยน้ำ Vapor Pressure Deficit (kPa)
    bool isConnected;       // สถานะการเชื่อมต่อเซนเซอร์ SHT45
};

// ข้อมูลแสงแดดและการสังเคราะห์แสง
struct LightEnvironmentData {
    float lux;              // ความเข้มแสง (Lux)
    float kLux;             // ความเข้มแสง (kLux)
    float solarRadiation;   // รังสีดวงอาทิตย์โดยประมาณ (W/m²)
    bool isConnected;       // สถานะการเชื่อมต่อโดมตะวัน
};

// ข้อมูลความชื้นและกรด-ด่างผิวดิน (Surface Soil Telemetry: Soil Stick + Surface pH)
struct SoilStickData {
    uint16_t rawAdc;        // ค่าสัญญาณดิบความชื้นจาก ADC A1 (0 - 4095)
    float moisture;         // ความชื้นในดินคำนวณเป็นเปอร์เซ็นต์ (0 - 100%)
    float rawPhVoltage;     // แรงดันไฟฟ้าแอนะล็อกของ pH จาก ADC A2 (V)
    float ph;               // ค่าความเป็นกรด-ด่างผิวดินที่ชดเชยอุณหภูมิเนิร์นสต์แล้ว
    bool isConnected;       // สถานะเซนเซอร์ความชื้น
    bool isPhConnected;     // สถานะการเชื่อมต่อหัววัด pH ผิวดิน
};

// ข้อมูลดินเชิงลึก 7 พารามิเตอร์ จาก Soil Multi-parameter Sensor (RS485 Modbus RTU)
struct SoilMultiParamData {
    float moisture;         // ความชื้นดิน (% หรือ 0.1%)
    float temperature;      // อุณหภูมิดิน (°C)
    float ec;               // สภาพนำไฟฟ้าในดิน / ความเค็ม (µS/cm)
    float ph;               // ค่าความเป็นกรด-ด่างของดิน (pH)
    float nitrogen;         // ปริมาณไนโตรเจนที่ใช้ประโยชน์ได้ N (mg/kg หรือ mg/L)
    float phosphorus;       // ปริมาณฟอสฟอรัสที่ใช้ประโยชน์ได้ P (mg/kg หรือ mg/L)
    float potassium;        // ปริมาณโพแทสเซียมที่ใช้ประโยชน์ได้ K (mg/kg หรือ mg/L)
    bool isConnected;       // สถานะการสื่อสาร Modbus RTU
    uint32_t readErrorCount;// สถิติข้อผิดพลาดในการอ่าน
};

// ข้อมูลรวมทั้งหมดของระบบฟาร์ม
struct FarmSensorTelemetry {
    AirEnvironmentData   air;
    LightEnvironmentData light;
    SoilStickData        soilStick;
    SoilMultiParamData   soil7in1;
    SoilAICalibratedData aiCalibrated;  // Deep Learning TinyML Calibrated Values
    uint32_t             timestamp;
};

/**
 * ============================================================================
 * Function Prototypes
 * ============================================================================
 */
void AgriSensors_init();
void AgriSensors_update();

const FarmSensorTelemetry& AgriSensors_getTelemetry();

// การแปลงหน่วยและการวิเคราะห์สภาวะการเจริญเติบโตของพืช
float AgriSensors_calculateDewPoint(float temp, float humidity);
float AgriSensors_calculateVPD(float temp, float humidity);
float AgriSensors_estimateSolarRadiation(float lux);

// แสดงรายงานสถิติผ่านทาง Serial
void AgriSensors_printDashboard();
