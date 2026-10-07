#pragma once
#include <Arduino.h>

/**
 * ============================================================================
 * WiFiConfigManager: Zero-Recompile Wi-Fi Provisioning Engine
 * ============================================================================
 * รองรับการบันทึก Wi-Fi ลง Flash (NVS Preferences) และรัน SoftAP Web Portal
 * ให้เกษตรกรสแกน QR Code จากหน้าจอแล้วตั้งค่าผ่านมือถือได้ทันที
 */

void WiFiConfigManager_init();
String WiFiConfigManager_getSSID();
String WiFiConfigManager_getPassword();
String WiFiConfigManager_getServerURL();
String WiFiConfigManager_getDeviceID();
bool WiFiConfigManager_hasStoredCredentials();
void WiFiConfigManager_saveCredentials(const String &ssid, const String &pass, const String &serverUrl = "", const String &deviceId = "");
void WiFiConfigManager_setServerURL(const String &url);
void WiFiConfigManager_setDeviceID(const String &id);
void WiFiConfigManager_resetCredentials();

bool WiFiConfigManager_isPortalActive();
void WiFiConfigManager_startPortal();
void WiFiConfigManager_stopPortal();
void WiFiConfigManager_loop();

void WiFiConfigManager_scanNetworksAndPrint();
bool WiFiConfigManager_processSerialCommand(const String &cmd);
