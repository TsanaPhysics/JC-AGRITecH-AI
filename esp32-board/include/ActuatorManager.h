#pragma once
#include <Arduino.h>
#include "PinConfigs.h"

/**
 * ============================================================================
 * ActuatorManager: ระบบบริหารจัดการรีเลย์และการสั่งการฮาร์ดแวร์จริง 4 ช่อง
 * พร้อมเซิร์ฟเวอร์ควบคุม REST API บนพอร์ต 8500 (Direct LAN Control)
 * รองรับ 3 โหมด: MANUAL (ผู้ใช้ควบคุม 100%), AUTO (Rule-based), AI (Predictive)
 * ============================================================================
 */

enum ActuatorControlMode {
    ACTUATOR_MODE_MANUAL = 0, // สั่งการด้วยตนเองผ่าน Dashboard / Mobile / Touchscreen
    ACTUATOR_MODE_AUTO   = 1, // ทำงานตามเกณฑ์เงื่อนไข If-Then ทางปฐพีวิทยา
    ACTUATOR_MODE_AI     = 2  // สมองกล TinyML วิเคราะห์สภาวะแวดล้อม 9 มิติและสั่งการพยากรณ์
};

struct ActuatorStatus {
    bool relay1; // O1: Pump 1 (GPIO 39)
    bool relay2; // O2: Pump 2 / Solenoid (GPIO 38)
    bool relay3; // O3: Valve (GPIO 7)
    bool relay4; // O4: Misting (GPIO 6)
    ActuatorControlMode mode;
    unsigned long lastCommandTime;
    String lastCommandSource;
};

// เริ่มต้นขา GPIO ของรีเลย์และระบบ Actuator
void ActuatorManager_init();

// เริ่มต้นและประมวลผล HTTP WebServer บนพอร์ต 8500 (เรียกใน loop)
void ActuatorManager_loop();

// สั่งเปิด/ปิดรีเลย์รายช่อง (id: 1..4, state: true=ON, false=OFF)
void ActuatorManager_setRelay(uint8_t id, bool state, const char* source = "MANUAL");

// สลับสถานะรีเลย์ (Toggle)
bool ActuatorManager_toggleRelay(uint8_t id, const char* source = "TOUCH");

// อ่านสถานะรีเลย์ (id: 1..4)
bool ActuatorManager_getRelay(uint8_t id);

// ปิดรีเลย์ทั้งหมด (Emergency Stop / Safe Idle)
void ActuatorManager_allOff(const char* source = "EMERGENCY");

// กำหนดโหมดการควบคุม (MANUAL, AUTO, AI)
void ActuatorManager_setMode(ActuatorControlMode mode, const char* source = "MODE_SWITCH");
void ActuatorManager_setModeByName(const String &modeName, const char* source = "API");

// อ่านโหมดการควบคุมปัจจุบัน
ActuatorControlMode ActuatorManager_getMode();
String ActuatorManager_getModeName();

// ประมวลผลคำสั่งที่ได้รับตอบกลับจาก Server Telemetry API (Bidirectional Polling Sync)
void ActuatorManager_applyServerCommands(int r1, int r2, int r3, int r4, int autoMode, const String &controlModeStr);

// รับข้อมูลสรุปสถานะ Actuator ทั้งหมด
ActuatorStatus ActuatorManager_getStatus();
