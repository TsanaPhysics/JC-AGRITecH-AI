#include <Arduino.h>
#include "PinConfigs.h"
#include "UserConfigs.h"
#include "AgriSensors.h"
#include "DisplayManager.h"
#include "CloudDataManager.h"
#include "WiFiConfigManager.h"

// ตัวแปรจับเวลาการอ่านเซนเซอร์และประมวลผล
static unsigned long lastSensorReadTime = 0;
static unsigned long lastDashboardPrintTime = 0;
static unsigned long lastDisplayUpdateTime = 0;

// สถานะการทำงานของอุปกรณ์ควบคุมในแปลง
bool isPumpActive = false;
bool isMistingActive = false;

void setup() {
    // 1. เริ่มต้น Serial Console เพื่อดูข้อมูลทดสอบ
    Serial.begin(115200);
    delay(1000);

    Serial.println("\n\n=======================================================");
    Serial.println("  AGRICULTURAL IOT CONTROLLER FOR ATD3.5-S3 (GRAVITY)  ");
    Serial.println("  Smart Farm Firmware with SHT45, Light, Soil & NPK/pH ");
    Serial.println("=======================================================");

    // 2. เริ่มต้นหน้าจอแสดงผล 3.5 นิ้ว TFT ทันที
    DisplayManager_init();

    // 3. กำหนดโหมดขา Relay
    pinMode(RELAY_1_PIN, OUTPUT);
    pinMode(RELAY_2_PIN, OUTPUT);
    pinMode(RELAY_3_PIN, OUTPUT);
    pinMode(RELAY_4_PIN, OUTPUT);
    digitalWrite(RELAY_1_PIN, LOW); // ปิดปั๊มน้ำ
    digitalWrite(RELAY_2_PIN, LOW);
    digitalWrite(RELAY_3_PIN, LOW);
    digitalWrite(RELAY_4_PIN, LOW);

    // 4. เริ่มต้นระบบเซนเซอร์ทั้งหมด
    AgriSensors_init();

    // 5. เริ่มต้นระบบเชื่อมต่อ Wi-Fi และส่งข้อมูล Cloud Data Logger (Non-blocking)
    CloudDataManager_init();

    Serial.println("[System] System setup completed successfully. Starting telemetry loop...\n");
}

/**
 * ============================================================================
 * Smart Agriculture Automation Logic (กฎการควบคุมอัตโนมัติอัจฉริยะ)
 * ============================================================================
 */
void executeAgronomyControl(const FarmSensorTelemetry &data) {
    // กฎที่ 1: ระบบรดน้ำอัจฉริยะตามความชื้นในดิน (Soil Moisture Hysteresis Control)
    // ใช้ค่าเฉลี่ยความชื้นระหว่าง Soil Stick และ Soil 7-in-1 หรือเลือกตัวใดตัวหนึ่ง
    float currentSoilMoisture = data.soilStick.moisture;
    if (data.soil7in1.isConnected) {
        // หากเซนเซอร์ 7-in-1 เชื่อมต่ออยู่ ให้นำมาคิดถ่วงน้ำหนักร่วมกันเพื่อความแม่นยำสูงสุด
        currentSoilMoisture = (data.soilStick.moisture * 0.5f) + (data.soil7in1.moisture * 0.5f);
    }

    if (currentSoilMoisture < 40.0f && !isPumpActive) {
        // ดินแห้งเกินไป (ต่ำกว่า 40%) -> สั่งเปิดปั๊มน้ำรดน้ำ
        digitalWrite(RELAY_1_PIN, HIGH);
        isPumpActive = true;
        Serial.println("\n>>> [ACTION] ดินแห้ง (Moisture < 40%) -> สั่งเปิดปั๊มน้ำ (Relay 1 ON)");
    } else if (currentSoilMoisture >= 65.0f && isPumpActive) {
        // ดินชุ่มชื้นเพียงพอแล้ว (แตะ 65%) -> สั่งปิดปั๊มน้ำ
        digitalWrite(RELAY_1_PIN, LOW);
        isPumpActive = false;
        Serial.println("\n>>> [ACTION] ดินชุ่มชื้นพอเหมาะ (Moisture >= 65%) -> สั่งปิดปั๊มน้ำ (Relay 1 OFF)");
    }

    // กฎที่ 2: ระบบลดความร้อนโรงเรือนด้วยการพ่นหมอก (Evaporative Cooling)
    // สั่งเปิดพ่นหมอกเมื่ออุณหภูมิอากาศ > 35.0 °C และความชื้นสัมพัทธ์ < 70%
    if (data.air.isConnected) {
        if (data.air.temperature > 35.0f && data.air.humidity < 70.0f && !isMistingActive) {
            digitalWrite(RELAY_3_PIN, HIGH);
            isMistingActive = true;
            Serial.println(">>> [ACTION] อากาศร้อนจัด (Temp > 35°C) -> สั่งเปิดระบบพ่นหมอก (Relay 3 ON)");
        } else if ((data.air.temperature <= 32.0f || data.air.humidity >= 85.0f) && isMistingActive) {
            digitalWrite(RELAY_3_PIN, LOW);
            isMistingActive = false;
            Serial.println(">>> [ACTION] อุณหภูมิลดลงปกติ -> สั่งปิดระบบพ่นหมอก (Relay 3 OFF)");
        }
    }
}

void loop() {
    // 0. ตรวจจับและตอบสนองการกดสัมผัสหน้าจอทัชสกรีน (Capacitive Touch FT6336U) ทันที
    DisplayManager_handleTouch(isPumpActive, isMistingActive);

    // หากมีการเปลี่ยนหน้าจอ หรือเลื่อนสไลด์หน้าจอ ให้รีเฟรชหน้าจอทันที ไม่ต้องรอรอบ 2 วินาที
    if (DisplayManager_hasPageChanged()) {
        DisplayManager_update(AgriSensors_getTelemetry(), isPumpActive, isMistingActive);
    }

    // ประมวลผล SoftAP Captive Portal DNS & WebServer หากกำลังเปิดโหมดตั้งค่า
    WiFiConfigManager_loop();

    unsigned long currentMillis = millis();

    // 1. อ่านค่าจากเซนเซอร์ทุกตัวตามรอบเวลา (ทุกๆ 2 วินาที)
    if (currentMillis - lastSensorReadTime >= SENSOR_READ_INTERVAL_MS) {
        lastSensorReadTime = currentMillis;

        // อัปเดตข้อมูลเซนเซอร์ทั้งหมด
        AgriSensors_update();

        // นำข้อมูลไปประมวลผลระบบควบคุมอัตโนมัติ
        const FarmSensorTelemetry &telemetry = AgriSensors_getTelemetry();
        executeAgronomyControl(telemetry);

        // อัปเดตหน้าจอ LCD 3.5 นิ้วทันทีที่มีข้อมูลใหม่
        DisplayManager_update(telemetry, isPumpActive, isMistingActive);

        // ส่งข้อมูลขึ้น Firebase Realtime Database (ประมวลผลอัตโนมัติแบบ Non-blocking)
        CloudDataManager_update(telemetry, isPumpActive, isMistingActive);
    }

    // 2. แสดงผล Dashboard และสถานะออกทาง Serial ทุกๆ 3 วินาที
    if (currentMillis - lastDashboardPrintTime >= 3000) {
        lastDashboardPrintTime = currentMillis;
        AgriSensors_printDashboard();
    }
}

