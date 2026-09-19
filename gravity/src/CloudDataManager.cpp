#include "CloudDataManager.h"
#include "UserConfigs.h"
#include "WiFiConfigManager.h"
#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ArduinoJson.h>
#include <time.h>
#include <sys/time.h>
#include <esp_wifi.h>

struct WiFiCandidate {
    String ssid;
    String pass;
};

static WiFiCandidate candidates[16];
static int totalCandidates = 0;
static int currentCandidateIdx = 0;
static unsigned long candidateAttemptTime = 0;

static unsigned long lastUploadTime = 0;
static unsigned long lastWiFiCheckTime = 0;
static bool isNtpSynchronized = false;
static bool wasConnected = false;

// รับเวลาปัจจุบันในรูปแบบ Unix Epoch Time (วินาที)
unsigned long CloudDataManager_getEpochTime() {
    time_t now;
    time(&now);
    return (unsigned long)now;
}

// รับเวลาปัจจุบันในรูปแบบสตริง "YYYY-MM-DD HH:MM:SS" (เขตเวลา UTC+7 ประเทศไทย)
String CloudDataManager_getFormattedTime() {
    time_t now;
    time(&now);
    struct tm timeinfo;
    if (!localtime_r(&now, &timeinfo) || now < 100000) {
        return "N/A";
    }
    char buf[25];
    strftime(buf, sizeof(buf), "%Y-%m-%d %H:%M:%S", &timeinfo);
    return String(buf);
}

// รับสตริงวันที่ เช่น "12/09/2026"
String CloudDataManager_getDateString() {
    time_t now;
    time(&now);
    struct tm timeinfo;
    if (!localtime_r(&now, &timeinfo) || now < 100000) {
        return "--/--/----";
    }
    char buf[16];
    strftime(buf, sizeof(buf), "%d/%m/%Y", &timeinfo);
    return String(buf);
}

// รับสตริงเวลา เช่น "11:20:45"
String CloudDataManager_getTimeString() {
    time_t now;
    time(&now);
    struct tm timeinfo;
    if (!localtime_r(&now, &timeinfo) || now < 100000) {
        return "--:--:--";
    }
    char buf[12];
    strftime(buf, sizeof(buf), "%H:%M:%S", &timeinfo);
    return String(buf);
}

bool CloudDataManager_isConnected() {
    return (WiFi.status() == WL_CONNECTED);
}

// ตรวจสอบและประมวลผลคำสั่งตั้งเวลาผ่าน Serial (USB Time Sync จากเครื่องคอมพิวเตอร์)
void CloudDataManager_checkSerialTimeSync() {
    while (Serial.available() > 0) {
        String line = Serial.readStringUntil('\n');
        line.trim();
        if (line.startsWith("TIME:") || line.startsWith("SET_TIME:")) {
            int idx = line.indexOf(':');
            if (idx >= 0) {
                unsigned long long epoch = strtoull(line.substring(idx + 1).c_str(), NULL, 10);
                if (epoch > 1700000000ULL) { // Valid timestamp (> ปี 2023)
                    struct timeval tv;
                    tv.tv_sec = (time_t)epoch;
                    tv.tv_usec = 0;
                    settimeofday(&tv, NULL);
                    isNtpSynchronized = true;
                    Serial.printf("[CloudData] Time Synced via USB Serial: %s (UTC+7)\n", CloudDataManager_getFormattedTime().c_str());
                }
            }
        }
    }
}

static void addCandidate(const String &ssid, const String &pass) {
    if (ssid.length() == 0 || ssid == "YOUR_WIFI_SSID") return;
    for (int i = 0; i < totalCandidates; i++) {
        if (candidates[i].ssid == ssid && candidates[i].pass == pass) return;
    }
    if (totalCandidates < 16) {
        candidates[totalCandidates].ssid = ssid;
        candidates[totalCandidates].pass = pass;
        totalCandidates++;
    }
}

void CloudDataManager_init() {
    Serial.println("\n[CloudData] Initializing Deterministic Multi-Candidate Wi-Fi Engine...");

    WiFi.mode(WIFI_STA);
    delay(100);
    WiFi.setSleep(false);

    // กำหนดรหัสประเทศเป็นประเทศไทย (TH: Channels 1 - 13) และเปิดกำลังส่งสูงสุด
    wifi_country_t country = { .cc = "TH", .schan = 1, .nchan = 13, .max_tx_power = 20, .policy = WIFI_COUNTRY_POLICY_AUTO };
    esp_wifi_set_country(&country);
    WiFi.setTxPower(WIFI_POWER_19_5dBm);

    // ลงทะเบียน Event Callback เพื่อดูเหตุการณ์เชื่อมต่อสด
    WiFi.onEvent([](WiFiEvent_t event, WiFiEventInfo_t info) {
        if (event == ARDUINO_EVENT_WIFI_STA_GOT_IP) {
            Serial.printf(">>> [WiFi-Event] STA_GOT_IP! IP: %s, GW: %s\n",
                          IPAddress(info.got_ip.ip_info.ip.addr).toString().c_str(),
                          IPAddress(info.got_ip.ip_info.gw.addr).toString().c_str());
        } else if (event == ARDUINO_EVENT_WIFI_STA_DISCONNECTED) {
            Serial.printf("    [WiFi-Event] STA_DISCONNECTED (Reason: %d)\n", info.wifi_sta_disconnected.reason);
        }
    });

    // 1. นำเข้าค่าหลักจาก UserConfigs.h เป็นอันดับ 1 เสมอ
    String cleanSsid = String(WIFI_SSID); cleanSsid.trim();
    String cleanPass = String(WIFI_PASSWORD); cleanPass.trim();
    addCandidate(cleanSsid, cleanPass);
    addCandidate("JC_Home", "JChome2023");
    addCandidate("JC_Home", "JCHome2023");
    addCandidate("JChome", "JChome2023");
    addCandidate("JChome", "JCHome2023");
    addCandidate("true_home2G_01C", cleanPass);
    addCandidate("true_home2G_01C", "JChome2023");
    addCandidate("JC iPhone", cleanPass);
    addCandidate("TsanaC", cleanPass);
    addCandidate("TsanaPhysiK", cleanPass);

    // 2. โหลดค่า Wi-Fi จาก NVS Flash
    WiFiConfigManager_init();
    if (WiFiConfigManager_hasStoredCredentials()) {
        String nvsSsid = WiFiConfigManager_getSSID(); nvsSsid.trim();
        String nvsPass = WiFiConfigManager_getPassword(); nvsPass.trim();
        addCandidate(nvsSsid, nvsPass);
        Serial.printf("  [+] Registered Stored NVS Wi-Fi (trimmed): '%s'\n", nvsSsid.c_str());
    }

    // 3. สแกนเครือข่าย Wi-Fi 2.4GHz รอบตัวแบบเต็มรูปแบบ
    Serial.println("\n[CloudData] Scanning 2.4GHz Wi-Fi networks (channels 1-13)...");
    int nScan = WiFi.scanNetworks(false, true); // blocking scan, show hidden
    if (nScan < 0) {
        Serial.printf("[CloudData] Scan returned code: %d (Scanning failed or busy)\n", nScan);
    } else {
        Serial.printf("[CloudData] Scan finished! Found %d networks:\n", nScan);
        for (int i = 0; i < nScan; i++) {
            String sc = WiFi.SSID(i);
            Serial.printf("   - [%2d] SSID: '%s' | Ch: %2d | RSSI: %3d dBm\n",
                          i + 1, sc.c_str(), WiFi.channel(i), WiFi.RSSI(i));
            String lower = sc;
            lower.toLowerCase();
            if (lower.indexOf("jc") >= 0 || lower.indexOf("home") >= 0 || lower.indexOf("tsana") >= 0) {
                addCandidate(sc, cleanPass);
                addCandidate(sc, "JChome2023");
                addCandidate(sc, "JCHome2023");
            }
        }
    }

    Serial.printf("  [+] Registered %d candidate credential pairs for auto-rotation:\n", totalCandidates);
    for (int i = 0; i < totalCandidates; i++) {
        Serial.printf("      [%d] SSID: '%s' | Pass: '%s'\n", i + 1, candidates[i].ssid.c_str(), candidates[i].pass.c_str());
    }

    // เริ่มต้นเชื่อมต่อตัวเลือกแรกทันที
    if (totalCandidates > 0) {
        currentCandidateIdx = 0;
        Serial.printf("  [+] Starting initial connection to candidate #1 ('%s')...\n", candidates[0].ssid.c_str());
        WiFi.begin(candidates[0].ssid.c_str(), candidates[0].pass.c_str());
        candidateAttemptTime = millis();
    }

    // กำหนด NTP Time Server (GMT+7 คือ 7 * 3600 วินาที)
    configTime(7 * 3600, 0, "pool.ntp.org", "time.nist.gov", "time.google.com");
}

static void sendTelemetryToFirebase(const FarmSensorTelemetry &telemetry, bool pumpState, bool mistingState) {
    if (WiFi.status() != WL_CONNECTED) {
        return;
    }

    if (String(FIREBASE_HOST).indexOf("your-project") >= 0 || strlen(FIREBASE_HOST) == 0) {
        // ยังไม่ได้ตั้งชื่อโฮสต์ Firebase จริง
        return;
    }

    // สร้างเอกสาร JSON ด้วย ArduinoJson
    StaticJsonDocument<1024> doc;
    unsigned long epoch = CloudDataManager_getEpochTime();
    doc["timestamp"] = epoch;
    doc["datetime"]  = CloudDataManager_getFormattedTime();

    // ข้อมูลสภาพอากาศ SHT45
    JsonObject air = doc.createNestedObject("air");
    air["temperature"] = round(telemetry.air.temperature * 100.0f) / 100.0f;
    air["humidity"]    = round(telemetry.air.humidity * 100.0f) / 100.0f;
    air["dew_point"]   = round(telemetry.air.dewPoint * 100.0f) / 100.0f;
    air["vpd"]         = round(telemetry.air.vpd * 100.0f) / 100.0f;
    air["connected"]   = telemetry.air.isConnected;

    // ข้อมูลความเข้มแสง โดมตะวัน BH1750
    JsonObject light = doc.createNestedObject("light");
    light["lux"]             = round(telemetry.light.lux * 10.0f) / 10.0f;
    light["klux"]            = round(telemetry.light.kLux * 100.0f) / 100.0f;
    light["solar_radiation"] = round(telemetry.light.solarRadiation * 100.0f) / 100.0f;
    light["connected"]       = telemetry.light.isConnected;

    // ข้อมูลความชื้นและกรด-ด่างผิวดิน Soil Stick (ผิวดิน 0-10 ซม.)
    JsonObject soilStick = doc.createNestedObject("soil_stick");
    soilStick["adc_raw"]          = telemetry.soilStick.rawAdc;
    soilStick["moisture_percent"] = round(telemetry.soilStick.moisture * 10.0f) / 10.0f;
    soilStick["ph"]               = round(telemetry.soilStick.ph * 100.0f) / 100.0f;
    soilStick["ph_raw_voltage"]   = round(telemetry.soilStick.rawPhVoltage * 1000.0f) / 1000.0f;
    soilStick["ph_connected"]     = telemetry.soilStick.isPhConnected;

    // ข้อมูลคุณสมบัติดินเชิงลึก 7-in-1 Modbus RTU (เขตรากพืช)
    JsonObject soil7in1 = doc.createNestedObject("soil_7in1");
    soil7in1["moisture_percent"] = round(telemetry.soil7in1.moisture * 10.0f) / 10.0f;
    soil7in1["temperature"]      = round(telemetry.soil7in1.temperature * 10.0f) / 10.0f;
    soil7in1["ec"]               = round(telemetry.soil7in1.ec);
    soil7in1["ph"]               = round(telemetry.soil7in1.ph * 100.0f) / 100.0f;
    soil7in1["nitrogen"]         = round(telemetry.soil7in1.nitrogen);
    soil7in1["phosphorus"]       = round(telemetry.soil7in1.phosphorus);
    soil7in1["potassium"]        = round(telemetry.soil7in1.potassium);
    soil7in1["connected"]        = telemetry.soil7in1.isConnected;

    // ข้อมูลชดเชยด้วยโครงข่ายประสาทเทียม TinyML Deep Learning บนบอร์ด
    JsonObject ai = doc.createNestedObject("ai_calibrated");
    ai["nitrogen"]         = round(telemetry.aiCalibrated.nitrogen * 10.0f) / 10.0f;
    ai["phosphorus"]       = round(telemetry.aiCalibrated.phosphorus * 10.0f) / 10.0f;
    ai["potassium"]        = round(telemetry.aiCalibrated.potassium * 10.0f) / 10.0f;
    ai["ph"]               = round(telemetry.aiCalibrated.ph * 100.0f) / 100.0f;
    ai["moisture_percent"] = round(telemetry.aiCalibrated.moisture * 10.0f) / 10.0f;
    ai["confidence"]       = telemetry.aiCalibrated.confidence;

    // สถานะอุปกรณ์สั่งการ (Actuators) สำหรับเป็นตัวแปรควบคุม (Control Variable) ในโมเดล AI
    JsonObject actuators = doc.createNestedObject("actuators");
    actuators["pump"]    = pumpState;
    actuators["misting"] = mistingState;

    String jsonPayload;
    serializeJson(doc, jsonPayload);

    // 1. ส่งข้อมูลเข้า Custom Python FastAPI Server ของตนเอง
#if defined(ENABLE_CUSTOM_SERVER) && ENABLE_CUSTOM_SERVER == true
    if (String(CUSTOM_SERVER_URL).startsWith("http")) {
        HTTPClient http;
        if (http.begin(CUSTOM_SERVER_URL)) {
            http.addHeader("Content-Type", "application/json");
            http.setTimeout(3000); // Timeout สั้น 3 วินาที เพื่อไม่ให้ระบบหน่วง
            int httpCode = http.POST(jsonPayload);
            if (httpCode == HTTP_CODE_OK || httpCode == 200) {
                Serial.printf("[CloudData] >>> Sent Telemetry to Custom FastAPI Server [OK] (Code: %d)\n", httpCode);
            } else {
                Serial.printf("[CloudData] [!] Custom Server POST returned: %d\n", httpCode);
            }
            http.end();
        }
    }
#endif

    // 2. ส่งข้อมูลเข้า Google Firebase Realtime Database
#if defined(ENABLE_FIREBASE) && ENABLE_FIREBASE == true
    if (String(FIREBASE_HOST).indexOf("your-project") < 0 && strlen(FIREBASE_HOST) > 0) {
        WiFiClientSecure client;
        client.setInsecure(); // ข้ามการตรวจสอบ Root CA เพื่อให้ทำงานได้รวดเร็ว

        HTTPClient https;
        String host = String(FIREBASE_HOST);
        if (!host.startsWith("http://") && !host.startsWith("https://")) {
            host = "https://" + host;
        }

        String authParam = "";
        if (strlen(FIREBASE_AUTH) > 0) {
            authParam = "?auth=" + String(FIREBASE_AUTH);
        }

        // POST ข้อมูลเข้า /telemetry.json
        String timeSeriesUrl = host + "/telemetry.json" + authParam;
        if (https.begin(client, timeSeriesUrl)) {
            https.addHeader("Content-Type", "application/json");
            https.setTimeout(3000);
            int httpCode = https.POST(jsonPayload);
            if (httpCode == HTTP_CODE_OK || httpCode == 200) {
                Serial.printf("[CloudData] >>> Sent Telemetry to Firebase Time-Series [OK] (Code: %d)\n", httpCode);
            }
            https.end();
        }

        // PUT ข้อมูลเข้า /latest.json
        String latestUrl = host + "/latest.json" + authParam;
        if (https.begin(client, latestUrl)) {
            https.addHeader("Content-Type", "application/json");
            https.setTimeout(3000);
            https.PUT(jsonPayload);
            https.end();
        }
    }
#endif
}

void CloudDataManager_update(const FarmSensorTelemetry &telemetry, bool pumpState, bool mistingState) {
    unsigned long currentMillis = millis();

    // หากเชื่อมต่อ Wi-Fi อยู่แล้ว
    if (WiFi.status() == WL_CONNECTED) {
        if (!wasConnected) {
            wasConnected = true;
            Serial.printf("\n=======================================================\n");
            Serial.printf(">>> [CloudData] Wi-Fi CONNECTED SUCCESSFUL!\n");
            Serial.printf("    Connected SSID : %s\n", WiFi.SSID().c_str());
            Serial.printf("    IP Address     : %s\n", WiFi.localIP().toString().c_str());
            Serial.printf("    Signal (RSSI)  : %d dBm\n", WiFi.RSSI());
            Serial.printf("=======================================================\n\n");
        }
        if (!isNtpSynchronized) {
            time_t now;
            time(&now);
            if (now > 100000) {
                isNtpSynchronized = true;
                Serial.printf("[CloudData] NTP Time Synced: %s (UTC+7)\n", CloudDataManager_getFormattedTime().c_str());
            }
        }

        // ตรวจสอบรอบเวลาการอัปโหลดข้อมูล
        if (currentMillis - lastUploadTime >= CLOUD_UPLOAD_INTERVAL_MS) {
            lastUploadTime = currentMillis;
            sendTelemetryToFirebase(telemetry, pumpState, mistingState);
        }
    } else {
        // สถานะยังไม่เชื่อมต่อ -> ให้เวลา 14 วินาทีต่อ candidate ในการทำ 4-way handshake
        if (wasConnected) {
            wasConnected = false;
            Serial.println("[CloudData] Wi-Fi Connection Lost! Starting auto-recovery...");
            candidateAttemptTime = currentMillis;
        }

        if (totalCandidates > 0 && (currentMillis - candidateAttemptTime >= 14000)) {
            candidateAttemptTime = currentMillis;
            currentCandidateIdx = (currentCandidateIdx + 1) % totalCandidates;
            const WiFiCandidate &c = candidates[currentCandidateIdx];
            Serial.printf("[CloudData] Rotating to candidate [%d/%d]: SSID='%s' | Pass='%s'...\n",
                          currentCandidateIdx + 1, totalCandidates, c.ssid.c_str(), c.pass.c_str());
            WiFi.disconnect(false);
            delay(100);
            WiFi.begin(c.ssid.c_str(), c.pass.c_str());
        }
    }
}
