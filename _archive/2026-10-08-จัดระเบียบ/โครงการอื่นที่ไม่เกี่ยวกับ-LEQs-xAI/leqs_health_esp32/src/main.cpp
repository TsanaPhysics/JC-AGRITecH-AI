/**
 * ============================================================================
 * LEQs AgriSci xAI — Health Step Tracker ESP32 Firmware
 * Version: 1.0.0
 * Board: ESP32-S3 (ATD3.5-S3) or ESP32 DevKit
 * Sensor: MPU6050 (Accelerometer + Gyroscope via I2C)
 * 
 * ฟีเจอร์:
 *   ✅ นับก้าว (Step Counting) ด้วย Peak-Detection Algorithm พร้อม Low-pass Filter
 *   ✅ คำนวณแคลอรี (MET-based Calorie Estimation)
 *   ✅ คำนวณระยะทาง (Distance via Stride Length)
 *   ✅ ตรวจจับกิจกรรม (Activity Detection: Idle, Walking, Running)
 *   ✅ แจ้งเตือนเมื่อนิ่งนานเกิน 30 นาที (Sedentary Alert via Buzzer)
 *   ✅ ส่งข้อมูลผ่าน Wi-Fi HTTP ทุก 1 วินาที (ให้ Flutter app ดึงข้อมูล)
 *   ✅ แสดง Dashboard ผ่าน Serial Monitor
 * 
 * การต่อวงจร MPU6050:
 *   MPU6050 VCC → ESP32 3.3V
 *   MPU6050 GND → ESP32 GND
 *   MPU6050 SDA → GPIO8 (ESP32-S3) หรือ GPIO21 (ESP32)
 *   MPU6050 SCL → GPIO9 (ESP32-S3) หรือ GPIO22 (ESP32)
 *   MPU6050 AD0 → GND (I2C address = 0x68)
 *
 * Protocol สำหรับ Flutter App (HTTP GET):
 *   GET http://<ESP32_IP>:8080/health
 *   Response JSON: {
 *     "steps": 1234,
 *     "calories": 56.7,
 *     "distance_km": 0.93,
 *     "activity": "walking",
 *     "accel_magnitude": 1.02,
 *     "sedentary_min": 5,
 *     "uptime_min": 45
 *   }
 * ============================================================================
 */

#include <Arduino.h>
#include <Wire.h>
#include <WiFi.h>
#include <WebServer.h>
#include <ArduinoJson.h>
#include "Config.h"

// ============================================================================
// MPU6050 Register Map (Manual I2C — ไม่ต้องใช้ Library เพิ่ม)
// ============================================================================
#define MPU6050_REG_PWR_MGMT_1    0x6B
#define MPU6050_REG_ACCEL_XOUT_H  0x3B
#define MPU6050_REG_GYRO_XOUT_H   0x43
#define MPU6050_REG_CONFIG        0x1A
#define MPU6050_REG_SMPLRT_DIV    0x19
#define MPU6050_ACCEL_SCALE       16384.0f  // ±2g range → LSB/g

// ============================================================================
// Global State
// ============================================================================
struct HealthMetrics {
  uint32_t steps         = 0;
  float    calories      = 0.0f;
  float    distanceKm    = 0.0f;
  float    accelMag      = 0.0f;   // g (smoothed)
  float    weightKg      = DEFAULT_WEIGHT_KG;
  String   activity      = "idle"; // idle, walking, running
  uint32_t sedentaryMin  = 0;
  uint32_t uptimeMin     = 0;
  uint32_t lastStepMs    = 0;
  uint32_t sedentaryCheckMs = 0;
  uint32_t sedentaryStepsSnapshot = 0;
};

static HealthMetrics metrics;
static bool stepHigh = false;      // สถานะ Peak Detection
static float smoothedMag = 1.0f;   // Low-pass filtered magnitude

WebServer httpServer(HTTP_SERVER_PORT);

// ============================================================================
// MPU6050 Low-level I2C Functions
// ============================================================================
bool mpu6050_write(uint8_t reg, uint8_t value) {
  Wire.beginTransmission(MPU6050_ADDR);
  Wire.write(reg);
  Wire.write(value);
  return Wire.endTransmission() == 0;
}

struct RawAccel { int16_t x, y, z; };

RawAccel mpu6050_readAccel() {
  Wire.beginTransmission(MPU6050_ADDR);
  Wire.write(MPU6050_REG_ACCEL_XOUT_H);
  Wire.endTransmission(false);
  Wire.requestFrom(MPU6050_ADDR, (uint8_t)6);
  RawAccel raw;
  raw.x = (Wire.read() << 8) | Wire.read();
  raw.y = (Wire.read() << 8) | Wire.read();
  raw.z = (Wire.read() << 8) | Wire.read();
  return raw;
}

bool mpu6050_init() {
  // ปลุก MPU6050 จากโหมด Sleep
  if (!mpu6050_write(MPU6050_REG_PWR_MGMT_1, 0x00)) return false;
  delay(100);
  // ตั้ง Digital Low-Pass Filter Level 3 (~44Hz bandwidth)
  mpu6050_write(MPU6050_REG_CONFIG, 0x03);
  // ตั้ง Sample Rate = 100Hz (SMPLRT_DIV = 9 → 1000/(1+9) = 100Hz)
  mpu6050_write(MPU6050_REG_SMPLRT_DIV, 0x09);
  return true;
}

// ============================================================================
// Step Detection — Threshold-based Peak Detector + Low-pass Filter
// ============================================================================
void processStepDetection(float rawMag) {
  // Low-pass filter ลด noise ความถี่สูง
  smoothedMag = SMOOTHING_ALPHA * rawMag + (1.0f - SMOOTHING_ALPHA) * smoothedMag;
  metrics.accelMag = smoothedMag;

  uint32_t now = millis();

  // Peak Detection: ตรวจจับจุดสูงสุดของ acceleration spike
  if (!stepHigh && smoothedMag > STEP_THRESHOLD_HIGH) {
    stepHigh = true;
  } else if (stepHigh && smoothedMag < STEP_THRESHOLD_LOW) {
    stepHigh = false;
    // ตรวจสอบ Debounce — ป้องกันนับซ้ำเร็วเกินไป
    if ((now - metrics.lastStepMs) > MIN_STEP_INTERVAL_MS) {
      metrics.lastStepMs = now;
      metrics.steps++;

      // คำนวณระยะทาง
      metrics.distanceKm = (metrics.steps * STRIDE_LENGTH_M) / 1000.0f;

      // ตรวจจับกิจกรรมจาก Step Rate
      uint32_t stepInterval = now - metrics.lastStepMs;
      if (stepInterval < 400) {
        metrics.activity = "running";
      } else {
        metrics.activity = "walking";
      }
    }
  }

  // ตรวจสอบว่ายังนิ่งอยู่ไหม (Idle Detection)
  if (smoothedMag > 0.95f && smoothedMag < 1.05f && 
      (now - metrics.lastStepMs) > 5000) {
    metrics.activity = "idle";
  }
}

// ============================================================================
// Calorie Calculation (MET-based)
// ============================================================================
void updateCalories() {
  float met = (metrics.activity == "running") ? RUNNING_MET : WALKING_MET;
  // kcal = MET × weight_kg × time_hours
  // ประมาณ: ทุก 1 ก้าว ≈ 0.04 kcal สำหรับน้ำหนัก 60 kg
  metrics.calories = metrics.steps * (met * metrics.weightKg / 3600.0f * 0.9f);
}

// ============================================================================
// Sedentary Alert — เตือนเมื่อนิ่งนานเกิน SEDENTARY_ALERT_MIN นาที
// ============================================================================
void checkSedentaryAlert() {
  uint32_t now = millis();
  if (now - metrics.sedentaryCheckMs >= 60000UL) {  // ตรวจทุก 1 นาที
    metrics.sedentaryCheckMs = now;

    uint32_t stepsDelta = metrics.steps - metrics.sedentaryStepsSnapshot;
    if (stepsDelta < 10) {
      // ขยับน้อยมาก → เพิ่มนาที sedentary
      metrics.sedentaryMin++;
    } else {
      // ขยับได้ → รีเซ็ต sedentary timer
      metrics.sedentaryMin = 0;
    }
    metrics.sedentaryStepsSnapshot = metrics.steps;
  }

  // เตือนด้วย Buzzer เมื่อนิ่งนานเกินกำหนด
  if (metrics.sedentaryMin >= SEDENTARY_ALERT_MIN) {
    // Buzz pattern: 3 beeps
    for (int i = 0; i < 3; i++) {
      digitalWrite(BUZZER_PIN, HIGH);
      delay(200);
      digitalWrite(BUZZER_PIN, LOW);
      delay(150);
    }
    metrics.sedentaryMin = 0;  // รีเซ็ตหลังแจ้งเตือน
    Serial.println("[ALERT] ⚠️ นิ่งนานเกิน 30 นาที! ลุกขึ้นขยับร่างกายด้วยนะครับ!");
  }
}

// ============================================================================
// HTTP Server — Flutter App ดึงข้อมูลจาก GET /health
// ============================================================================
void handleHealthEndpoint() {
  StaticJsonDocument<512> doc;
  doc["steps"]          = metrics.steps;
  doc["calories"]       = serialized(String(metrics.calories, 1));
  doc["distance_km"]    = serialized(String(metrics.distanceKm, 2));
  doc["activity"]       = metrics.activity;
  doc["accel_magnitude"]= serialized(String(metrics.accelMag, 3));
  doc["sedentary_min"]  = metrics.sedentaryMin;
  doc["uptime_min"]     = metrics.uptimeMin;
  doc["weight_kg"]      = metrics.weightKg;

  String response;
  serializeJson(doc, response);

  httpServer.sendHeader("Access-Control-Allow-Origin", "*");
  httpServer.send(200, "application/json", response);
}

void handleResetEndpoint() {
  metrics.steps        = 0;
  metrics.calories     = 0.0f;
  metrics.distanceKm   = 0.0f;
  metrics.sedentaryMin = 0;
  metrics.activity     = "idle";
  httpServer.send(200, "application/json", "{\"status\":\"reset_ok\"}");
  Serial.println("[System] รีเซ็ตข้อมูลทั้งหมดเป็น 0");
}

void handleSetWeightEndpoint() {
  if (httpServer.hasArg("kg")) {
    metrics.weightKg = httpServer.arg("kg").toFloat();
    httpServer.send(200, "application/json", "{\"status\":\"weight_updated\"}");
    Serial.printf("[System] ตั้งค่าน้ำหนักใหม่: %.1f kg\n", metrics.weightKg);
  } else {
    httpServer.send(400, "application/json", "{\"error\":\"missing kg param\"}");
  }
}

// ============================================================================
// Setup
// ============================================================================
void setup() {
  Serial.begin(115200);
  delay(500);
  Serial.println("\n============================================");
  Serial.println("  LEQs AgriSci xAI — Health Step Tracker");
  Serial.println("  ESP32 Firmware v1.0.0");
  Serial.println("============================================\n");

  // ตั้งค่า GPIO
  pinMode(LED_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(LED_PIN, LOW);
  digitalWrite(BUZZER_PIN, LOW);

  // เริ่มต้น I2C
  Wire.begin(I2C_SDA_PIN, I2C_SCL_PIN);
  Wire.setClock(100000);  // 100kHz Standard Mode
  Serial.printf("[I2C] SDA=GPIO%d, SCL=GPIO%d\n", I2C_SDA_PIN, I2C_SCL_PIN);

  // เริ่มต้น MPU6050
  Serial.print("[MPU6050] Initializing... ");
  if (mpu6050_init()) {
    Serial.println("✅ OK");
    // Blink LED 2 ครั้ง = เซนเซอร์พร้อม
    for (int i = 0; i < 2; i++) {
      digitalWrite(LED_PIN, HIGH); delay(150);
      digitalWrite(LED_PIN, LOW);  delay(150);
    }
  } else {
    Serial.println("❌ ไม่พบ MPU6050 ตรวจสอบการต่อสาย I2C!");
    // Blink LED เร็ว = Error
    while (true) {
      digitalWrite(LED_PIN, HIGH); delay(100);
      digitalWrite(LED_PIN, LOW);  delay(100);
    }
  }

  // เชื่อมต่อ Wi-Fi
  Serial.printf("[WiFi] กำลังเชื่อมต่อ '%s'", WIFI_SSID);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  int wifiAttempts = 0;
  while (WiFi.status() != WL_CONNECTED && wifiAttempts < 30) {
    delay(500);
    Serial.print(".");
    wifiAttempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("\n[WiFi] ✅ เชื่อมต่อสำเร็จ! IP: %s\n", WiFi.localIP().toString().c_str());
    Serial.printf("[HTTP] Flutter App ดึงข้อมูลได้ที่:\n");
    Serial.printf("  GET http://%s:%d/health\n", WiFi.localIP().toString().c_str(), HTTP_SERVER_PORT);
    Serial.printf("  GET http://%s:%d/reset\n",  WiFi.localIP().toString().c_str(), HTTP_SERVER_PORT);
    Serial.printf("  GET http://%s:%d/set_weight?kg=65\n", WiFi.localIP().toString().c_str(), HTTP_SERVER_PORT);
  } else {
    Serial.println("\n[WiFi] ⚠️ ไม่สามารถเชื่อมต่อ Wi-Fi — ทำงานแบบ Offline");
  }

  // ตั้งค่า HTTP Server Endpoints
  httpServer.on("/health",     HTTP_GET, handleHealthEndpoint);
  httpServer.on("/reset",      HTTP_GET, handleResetEndpoint);
  httpServer.on("/set_weight", HTTP_GET, handleSetWeightEndpoint);
  httpServer.begin();

  metrics.sedentaryCheckMs = millis();
  Serial.println("\n[System] ✅ พร้อมนับก้าวแล้ว! เริ่มเดินได้เลย...\n");
}

// ============================================================================
// Loop
// ============================================================================
unsigned long lastSensorMs   = 0;
unsigned long lastBroadcastMs= 0;
unsigned long lastStatsMs    = 0;
unsigned long startMs        = millis();

void loop() {
  // 1. อ่านค่า MPU6050 ทุก 10ms (100Hz)
  unsigned long now = millis();
  if (now - lastSensorMs >= MPU6050_SAMPLE_RATE_MS) {
    lastSensorMs = now;

    RawAccel raw = mpu6050_readAccel();
    float ax = raw.x / MPU6050_ACCEL_SCALE;
    float ay = raw.y / MPU6050_ACCEL_SCALE;
    float az = raw.z / MPU6050_ACCEL_SCALE;

    // คำนวณ Vector Magnitude |A| = √(ax² + ay² + az²)
    float mag = sqrtf(ax*ax + ay*ay + az*az);

    // ตรวจจับก้าว
    processStepDetection(mag);

    // คำนวณแคลอรี
    updateCalories();

    // LED กระพริบตาม Activity
    if (metrics.activity == "running") {
      digitalWrite(LED_PIN, (now % 200 < 100) ? HIGH : LOW);
    } else if (metrics.activity == "walking") {
      digitalWrite(LED_PIN, (now % 500 < 250) ? HIGH : LOW);
    } else {
      digitalWrite(LED_PIN, LOW);
    }
  }

  // 2. ตรวจสอบ Sedentary Alert ทุก 1 นาที
  checkSedentaryAlert();

  // 3. อัปเดต Uptime (นาที)
  metrics.uptimeMin = (millis() - startMs) / 60000UL;

  // 4. Handle HTTP requests จาก Flutter App
  httpServer.handleClient();

  // 5. แสดง Dashboard ผ่าน Serial Monitor ทุก 5 วินาที
  if (millis() - lastStatsMs >= 5000) {
    lastStatsMs = millis();
    Serial.println("┌─────────────────────────────────────┐");
    Serial.printf( "│ 👟 Steps:      %6lu ก้าว           │\n", (unsigned long)metrics.steps);
    Serial.printf( "│ 🔥 Calories:   %6.1f kcal           │\n", metrics.calories);
    Serial.printf( "│ 📍 Distance:   %6.2f km             │\n", metrics.distanceKm);
    Serial.printf( "│ 🏃 Activity:   %s              │\n", metrics.activity.c_str());
    Serial.printf( "│ 📡 Accel:      %6.3f g              │\n", metrics.accelMag);
    Serial.printf( "│ ⏱️ Sedentary:  %3lu นาที             │\n", (unsigned long)metrics.sedentaryMin);
    Serial.printf( "│ ⌚ Uptime:     %3lu นาที             │\n", (unsigned long)metrics.uptimeMin);
    Serial.println("└─────────────────────────────────────┘");
  }
}
