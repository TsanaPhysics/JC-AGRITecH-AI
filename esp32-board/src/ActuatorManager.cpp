#include "ActuatorManager.h"
#include <WiFi.h>
#include <WebServer.h>
#include <ArduinoJson.h>

static WebServer actuatorServer(8500);
static bool isServerStarted = false;

static ActuatorStatus status = {
    false, false, false, false,
    ACTUATOR_MODE_AUTO,
    0,
    "INIT"
};

// ส่ง Header CORS สำหรับเบราว์เซอร์
static void sendCorsHeaders() {
    actuatorServer.sendHeader("Access-Control-Allow-Origin", "*");
    actuatorServer.sendHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
    actuatorServer.sendHeader("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With");
}

static void handleOptions() {
    sendCorsHeaders();
    actuatorServer.send(204);
}

static void handleStatus() {
    sendCorsHeaders();
    StaticJsonDocument<256> doc;
    doc["status"] = "success";
    doc["mode"] = ActuatorManager_getModeName();
    JsonObject r = doc.createNestedObject("relays");
    r["1"] = status.relay1 ? 1 : 0;
    r["2"] = status.relay2 ? 1 : 0;
    r["3"] = status.relay3 ? 1 : 0;
    r["4"] = status.relay4 ? 1 : 0;
    doc["last_source"] = status.lastCommandSource;
    doc["uptime_sec"] = millis() / 1000;

    String resp;
    serializeJson(doc, resp);
    actuatorServer.send(200, "application/json; charset=utf-8", resp);
}

static void handleRelay() {
    sendCorsHeaders();

    String idStr = "";
    if (actuatorServer.hasArg("id")) idStr = actuatorServer.arg("id");
    else if (actuatorServer.hasArg("ch")) idStr = actuatorServer.arg("ch");
    else if (actuatorServer.hasArg("relay")) idStr = actuatorServer.arg("relay");

    String stateStr = "";
    if (actuatorServer.hasArg("state")) stateStr = actuatorServer.arg("state");
    else if (actuatorServer.hasArg("val")) stateStr = actuatorServer.arg("val");

    uint8_t id = idStr.toInt();
    if (id < 1 || id > 4) {
        actuatorServer.send(400, "application/json", "{\"status\":\"error\",\"message\":\"id must be 1, 2, 3 or 4\"}");
        return;
    }

    bool newState = false;
    stateStr.toLowerCase();
    if (stateStr == "1" || stateStr == "true" || stateStr == "on") {
        newState = true;
    } else if (stateStr == "0" || stateStr == "false" || stateStr == "off") {
        newState = false;
    } else if (stateStr == "toggle") {
        newState = !ActuatorManager_getRelay(id);
    } else {
        // ค่าเริ่มต้นเมื่อไม่ระบุคือ Toggle
        newState = !ActuatorManager_getRelay(id);
    }

    // หากมีการสั่งเปิด/ปิดเฉพาะกิจ ให้สลับเข้าโหมด MANUAL ชั่วคราวเพื่อให้คำสั่งคงอยู่
    if (actuatorServer.hasArg("force_manual") || status.mode == ACTUATOR_MODE_AUTO) {
        status.mode = ACTUATOR_MODE_MANUAL;
    }

    ActuatorManager_setRelay(id, newState, "DIRECT_HTTP_8500");

    StaticJsonDocument<256> doc;
    doc["status"] = "success";
    doc["id"] = id;
    doc["state"] = newState ? 1 : 0;
    doc["mode"] = ActuatorManager_getModeName();
    doc["message"] = String("Relay O") + id + " switched to " + (newState ? "ON" : "OFF");

    String resp;
    serializeJson(doc, resp);
    actuatorServer.send(200, "application/json; charset=utf-8", resp);
}

static void handleMode() {
    sendCorsHeaders();

    String m = "";
    if (actuatorServer.hasArg("mode")) m = actuatorServer.arg("mode");
    else if (actuatorServer.hasArg("val")) m = actuatorServer.arg("val");

    if (m.length() > 0) {
        ActuatorManager_setModeByName(m, "DIRECT_HTTP_8500");
    }

    StaticJsonDocument<128> doc;
    doc["status"] = "success";
    doc["mode"] = ActuatorManager_getModeName();
    doc["message"] = String("Control Mode switched to ") + ActuatorManager_getModeName();

    String resp;
    serializeJson(doc, resp);
    actuatorServer.send(200, "application/json; charset=utf-8", resp);
}

static void handleAllOff() {
    sendCorsHeaders();
    ActuatorManager_allOff("DIRECT_HTTP_8500");
    actuatorServer.send(200, "application/json", "{\"status\":\"success\",\"message\":\"All relays turned OFF\"}");
}

void ActuatorManager_init() {
    pinMode(RELAY_1_PIN, OUTPUT);
    pinMode(RELAY_2_PIN, OUTPUT);
    pinMode(RELAY_3_PIN, OUTPUT);
    pinMode(RELAY_4_PIN, OUTPUT);

    digitalWrite(RELAY_1_PIN, LOW);
    digitalWrite(RELAY_2_PIN, LOW);
    digitalWrite(RELAY_3_PIN, LOW);
    digitalWrite(RELAY_4_PIN, LOW);

    status.relay1 = false;
    status.relay2 = false;
    status.relay3 = false;
    status.relay4 = false;
    status.mode = ACTUATOR_MODE_AUTO;
    status.lastCommandSource = "INIT";

    Serial.println("[Actuator] Relays initialized (O1: GPIO39, O2: GPIO38, O3: GPIO7, O4: GPIO6) -> All OFF");
}

void ActuatorManager_loop() {
    // ตรวจสอบการเปิด WebServer บนพอร์ต 8500 เมื่อเชื่อมต่อ Wi-Fi
    if (WiFi.status() == WL_CONNECTED) {
        if (!isServerStarted) {
            actuatorServer.on("/relay", HTTP_GET, handleRelay);
            actuatorServer.on("/relay", HTTP_POST, handleRelay);
            actuatorServer.on("/relay", HTTP_OPTIONS, handleOptions);

            actuatorServer.on("/mode", HTTP_GET, handleMode);
            actuatorServer.on("/mode", HTTP_POST, handleMode);
            actuatorServer.on("/mode", HTTP_OPTIONS, handleOptions);

            actuatorServer.on("/status", HTTP_GET, handleStatus);
            actuatorServer.on("/status", HTTP_OPTIONS, handleOptions);

            actuatorServer.on("/all_off", HTTP_GET, handleAllOff);
            actuatorServer.on("/all_off", HTTP_OPTIONS, handleOptions);

            actuatorServer.on("/", HTTP_GET, handleStatus);
            actuatorServer.on("/", HTTP_OPTIONS, handleOptions);

            actuatorServer.enableCORS(true);
            actuatorServer.begin();
            isServerStarted = true;
            Serial.printf(">>> [Actuator] Direct Hardware Control REST Server listening on http://%s:8500/\n",
                          WiFi.localIP().toString().c_str());
        }
        actuatorServer.handleClient();
    } else {
        if (isServerStarted) {
            actuatorServer.stop();
            isServerStarted = false;
        }
    }
}

void ActuatorManager_setRelay(uint8_t id, bool state, const char* source) {
    status.lastCommandTime = millis();
    status.lastCommandSource = String(source);

    switch (id) {
        case 1:
            digitalWrite(RELAY_1_PIN, state ? HIGH : LOW);
            status.relay1 = state;
            Serial.printf(">>> [Actuator] Relay O1 (Pump 1, GPIO 39) -> %s (Source: %s)\n", state ? "ON" : "OFF", source);
            break;
        case 2:
            digitalWrite(RELAY_2_PIN, state ? HIGH : LOW);
            status.relay2 = state;
            Serial.printf(">>> [Actuator] Relay O2 (Pump 2/Solenoid, GPIO 38) -> %s (Source: %s)\n", state ? "ON" : "OFF", source);
            break;
        case 3:
            digitalWrite(RELAY_3_PIN, state ? HIGH : LOW);
            status.relay3 = state;
            Serial.printf(">>> [Actuator] Relay O3 (Surface Valve, GPIO 7) -> %s (Source: %s)\n", state ? "ON" : "OFF", source);
            break;
        case 4:
            digitalWrite(RELAY_4_PIN, state ? HIGH : LOW);
            status.relay4 = state;
            Serial.printf(">>> [Actuator] Relay O4 (Misting, GPIO 6) -> %s (Source: %s)\n", state ? "ON" : "OFF", source);
            break;
        default:
            break;
    }
}

bool ActuatorManager_toggleRelay(uint8_t id, const char* source) {
    bool current = ActuatorManager_getRelay(id);
    ActuatorManager_setRelay(id, !current, source);
    return !current;
}

bool ActuatorManager_getRelay(uint8_t id) {
    switch (id) {
        case 1: return status.relay1;
        case 2: return status.relay2;
        case 3: return status.relay3;
        case 4: return status.relay4;
        default: return false;
    }
}

void ActuatorManager_allOff(const char* source) {
    digitalWrite(RELAY_1_PIN, LOW);
    digitalWrite(RELAY_2_PIN, LOW);
    digitalWrite(RELAY_3_PIN, LOW);
    digitalWrite(RELAY_4_PIN, LOW);
    status.relay1 = false;
    status.relay2 = false;
    status.relay3 = false;
    status.relay4 = false;
    status.lastCommandSource = String(source);
    Serial.printf(">>> [Actuator] All Relays Turned OFF (Source: %s)\n", source);
}

void ActuatorManager_setMode(ActuatorControlMode mode, const char* source) {
    status.mode = mode;
    Serial.printf(">>> [Actuator] Control Mode Changed to: %s (Source: %s)\n", ActuatorManager_getModeName().c_str(), source);
}

void ActuatorManager_setModeByName(const String &modeName, const char* source) {
    String m = modeName;
    m.toLowerCase();
    if (m == "manual") {
        ActuatorManager_setMode(ACTUATOR_MODE_MANUAL, source);
    } else if (m == "ai") {
        ActuatorManager_setMode(ACTUATOR_MODE_AI, source);
    } else {
        ActuatorManager_setMode(ACTUATOR_MODE_AUTO, source);
    }
}

ActuatorControlMode ActuatorManager_getMode() {
    return status.mode;
}

String ActuatorManager_getModeName() {
    switch (status.mode) {
        case ACTUATOR_MODE_MANUAL: return "MANUAL";
        case ACTUATOR_MODE_AI:     return "AI";
        case ACTUATOR_MODE_AUTO:
        default:                   return "AUTO";
    }
}

void ActuatorManager_applyServerCommands(int r1, int r2, int r3, int r4, int autoMode, const String &controlModeStr) {
    // ปรับเปลี่ยนโหมดตาม Server หากมีการระบุ
    if (controlModeStr.length() > 0) {
        ActuatorManager_setModeByName(controlModeStr, "CLOUD_SYNC");
    } else {
        if (autoMode == 0 && status.mode != ACTUATOR_MODE_MANUAL) {
            status.mode = ACTUATOR_MODE_MANUAL;
        } else if (autoMode == 1 && status.mode == ACTUATOR_MODE_MANUAL) {
            status.mode = ACTUATOR_MODE_AUTO;
        }
    }

    // หากอยู่ในโหมด MANUAL หรือมีการสั่งตรงจาก Dashboard ให้ซิงก์สถานะรีเลย์ให้ตรงกับ Server ทันที
    if (status.mode == ACTUATOR_MODE_MANUAL) {
        if (status.relay1 != (r1 == 1)) ActuatorManager_setRelay(1, (r1 == 1), "CLOUD_POLL");
        if (status.relay2 != (r2 == 1)) ActuatorManager_setRelay(2, (r2 == 1), "CLOUD_POLL");
        if (status.relay3 != (r3 == 1)) ActuatorManager_setRelay(3, (r3 == 1), "CLOUD_POLL");
        if (status.relay4 != (r4 == 1)) ActuatorManager_setRelay(4, (r4 == 1), "CLOUD_POLL");
    }
}

ActuatorStatus ActuatorManager_getStatus() {
    return status;
}
