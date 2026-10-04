#pragma once
#include <Arduino.h>
#include "AgriSensors.h"

/**
 * ============================================================================
 * SDCardManager - Onboard Micro-SD Telemetry Data Logger
 * สำหรับบอร์ด ESP32-S3 ATD3.5 บันทึกข้อมูลเซนเซอร์ลง Micro-SD Card
 * รูปแบบไฟล์ CSV มาตรฐาน พร้อม Auto-Probe Chip Select (CS) Pin
 * ============================================================================
 */

void SDCardManager_init();
bool SDCardManager_isMounted();
int  SDCardManager_getCsPin();
uint32_t SDCardManager_getRecordCount();
uint64_t SDCardManager_getCardSizeMB();
bool SDCardManager_log(const FarmSensorTelemetry &telemetry, bool pumpState, bool mistingState);
