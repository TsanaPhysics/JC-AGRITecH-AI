#pragma once
#include <Arduino.h>
#include "AgriSensors.h"

/**
 * ============================================================================
 * CloudDataManager: Wi-Fi, NTP Time Sync & Firebase Realtime Database Logger
 * สำหรับส่งข้อมูลเซนเซอร์การเกษตรเข้าสู่ฐานข้อมูล เพื่อนำไปวิเคราะห์และฝึก AI
 * ============================================================================
 */

// เริ่มต้นโมดูล Wi-Fi และ NTP Time Client (Non-blocking)
void CloudDataManager_init();

// ตรวจสอบสถานะการเชื่อมต่อ Wi-Fi และส่งข้อมูล Telemetry ขึ้น Firebase ตามรอบเวลา
void CloudDataManager_update(const FarmSensorTelemetry &telemetry, bool pumpState, bool mistingState);

// ตรวจสอบว่า Wi-Fi เชื่อมต่อสำเร็จหรือไม่
bool CloudDataManager_isConnected();

// รับค่าเวลาปัจจุบันในรูปแบบสตริง "YYYY-MM-DD HH:MM:SS" (UTC+7)
String CloudDataManager_getFormattedTime();

// รับสตริงวันที่ เช่น "12/09/2026" หรือ "12 ก.ย. 69"
String CloudDataManager_getDateString();

// รับสตริงเวลา เช่น "11:20:45"
String CloudDataManager_getTimeString();

// รับค่า Unix Timestamp ปัจจุบัน (วินาที)
unsigned long CloudDataManager_getEpochTime();
