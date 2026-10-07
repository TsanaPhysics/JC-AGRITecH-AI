#include "WiFiConfigManager.h"
#include "UserConfigs.h"
#include <WiFi.h>
#include <WebServer.h>
#include <DNSServer.h>
#include <Preferences.h>
#include <esp_wifi.h>

static Preferences prefs;
static WebServer server(80);
static DNSServer dnsServer;
static bool portalActive = false;

static String storedSSID = "";
static String storedPass = "";
static String storedServerUrl = "";
static String storedDeviceId = "ESP32-S3-ATD35";

static const byte DNS_PORT = 53;
static IPAddress apIP(192, 168, 4, 1);
static IPAddress netMsk(255, 255, 255, 0);

void WiFiConfigManager_init() {
    prefs.begin("agri_wifi", false);
    storedSSID      = prefs.getString("ssid", "");
    storedPass      = prefs.getString("pass", "");
    storedServerUrl = prefs.getString("server_url", "");
    storedDeviceId  = prefs.getString("device_id", "ESP32-S3-ATD35");
    prefs.end();

    if (storedServerUrl.length() == 0) {
#if defined(CUSTOM_SERVER_URL)
        storedServerUrl = CUSTOM_SERVER_URL;
#elif defined(LOCAL_SERVER_URL)
        storedServerUrl = LOCAL_SERVER_URL;
#endif
    }

    if (storedSSID.length() > 0) {
        Serial.printf("[WiFiConfig] Found stored NVS credentials: SSID='%s' | Server='%s' | Device='%s'\n",
                      storedSSID.c_str(), storedServerUrl.c_str(), storedDeviceId.c_str());
    } else {
        Serial.println("[WiFiConfig] No stored Wi-Fi credentials found in NVS Flash. Ready for provisioning.");
    }
}

String WiFiConfigManager_getSSID() {
    return storedSSID;
}

String WiFiConfigManager_getPassword() {
    return storedPass;
}

String WiFiConfigManager_getServerURL() {
    return storedServerUrl;
}

String WiFiConfigManager_getDeviceID() {
    return storedDeviceId;
}

bool WiFiConfigManager_hasStoredCredentials() {
    return (storedSSID.length() > 0);
}

void WiFiConfigManager_saveCredentials(const String &ssid, const String &pass, const String &serverUrl, const String &deviceId) {
    prefs.begin("agri_wifi", false);
    prefs.putString("ssid", ssid);
    prefs.putString("pass", pass);
    if (serverUrl.length() > 0) {
        prefs.putString("server_url", serverUrl);
        storedServerUrl = serverUrl;
    }
    if (deviceId.length() > 0) {
        prefs.putString("device_id", deviceId);
        storedDeviceId = deviceId;
    }
    prefs.end();

    storedSSID = ssid;
    storedPass = pass;

    Serial.printf("[WiFiConfig] Saved to NVS: SSID='%s', Server='%s', Device='%s'\n",
                  ssid.c_str(), storedServerUrl.c_str(), storedDeviceId.c_str());
}

void WiFiConfigManager_setServerURL(const String &url) {
    prefs.begin("agri_wifi", false);
    prefs.putString("server_url", url);
    prefs.end();
    storedServerUrl = url;
    Serial.printf("[WiFiConfig] Server URL updated in NVS: '%s'\n", url.c_str());
}

void WiFiConfigManager_setDeviceID(const String &id) {
    prefs.begin("agri_wifi", false);
    prefs.putString("device_id", id);
    prefs.end();
    storedDeviceId = id;
    Serial.printf("[WiFiConfig] Device ID updated in NVS: '%s'\n", id.c_str());
}

void WiFiConfigManager_resetCredentials() {
    prefs.begin("agri_wifi", false);
    prefs.clear();
    prefs.end();
    storedSSID = "";
    storedPass = "";
    storedServerUrl = "";
    storedDeviceId = "ESP32-S3-ATD35";
    Serial.println("[WiFiConfig] NVS Flash credentials erased successfully!");
}

void WiFiConfigManager_scanNetworksAndPrint() {
    Serial.println("\n[WiFiConfig] ========================================================");
    WiFi.scanDelete();
    if (WiFi.status() != WL_CONNECTED) {
        WiFi.disconnect(false);
        delay(50);
    }
    int16_t n = WiFi.scanNetworks(false, false, false, 250);
    if (n < 0) {
        delay(150);
        WiFi.scanDelete();
        n = WiFi.scanNetworks(false, false, false, 300);
    }
    if (n < 0) {
        Serial.printf("[WiFiConfig] Scan failed or Wi-Fi engine busy (Code: %d)\n", n);
    } else if (n == 0) {
        Serial.println("[WiFiConfig] No Wi-Fi networks found in range.");
    } else {
        Serial.printf("[WiFiConfig] Scan finished! Found %d networks:\n", n);
        Serial.println("  ------------------------------------------------------------------");
        Serial.printf("  %-4s | %-28s | %-4s | %-8s | %s\n", "No.", "SSID", "Ch", "RSSI", "Security");
        Serial.println("  ------------------------------------------------------------------");
        for (int i = 0; i < n; i++) {
            const char* sec = "OPEN";
            wifi_auth_mode_t auth = WiFi.encryptionType(i);
            if (auth == WIFI_AUTH_WPA2_PSK) sec = "WPA2";
            else if (auth == WIFI_AUTH_WPA_WPA2_PSK) sec = "WPA/WPA2";
            else if (auth == WIFI_AUTH_WPA3_PSK) sec = "WPA3";
            else if (auth != WIFI_AUTH_OPEN) sec = "PROTECTED";

            Serial.printf("  [%2d] | %-28s | %2d   | %4d dBm | %s\n",
                          i + 1, WiFi.SSID(i).c_str(), WiFi.channel(i), WiFi.RSSI(i), sec);
        }
        Serial.println("  ------------------------------------------------------------------");
    }
    Serial.println("[WiFiConfig] ========================================================\n");
}

static String buildHtmlPage() {
    String defaultLocalServer = "";
    String defaultCloudServer = "";
#if defined(LOCAL_SERVER_URL)
    defaultLocalServer = LOCAL_SERVER_URL;
#endif
#if defined(CUSTOM_SERVER_URL)
    defaultCloudServer = CUSTOM_SERVER_URL;
#endif

    String html = "<!DOCTYPE html><html lang='th'><head>";
    html += "<meta charset='utf-8'>";
    html += "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    html += "<title>LEQs-AgriEnvi-xAI Wi-Fi & Dashboard Provisioning</title>";
    html += "<style>";
    html += "* {box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;}";
    html += "body {background: linear-gradient(135deg, #070c18, #0f172a); color: #f8fafc; margin: 0; padding: 16px; min-height: 100vh;}";
    html += ".container {max-width: 480px; margin: 0 auto; background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(12px); border-radius: 20px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.6); border: 1px solid rgba(56, 189, 248, 0.2);}";
    html += ".header {text-align: center; border-bottom: 1px solid #334155; padding-bottom: 16px; margin-bottom: 20px;}";
    html += "h1 {font-size: 22px; color: #38bdf8; margin: 0 0 6px 0; display: flex; align-items: center; justify-content: center; gap: 8px;}";
    html += ".sub {font-size: 13px; color: #94a3b8; margin: 0;}";
    html += ".badge {display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: #0284c7; color: #fff; margin-top: 8px;}";
    html += ".scan-bar {display: flex; justify-content: space-between; align-items: center; margin-top: 14px; margin-bottom: 6px;}";
    html += "label {font-size: 13px; font-weight: 600; color: #cbd5e1;}";
    html += "input, select {width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid #475569; background: #090e1a; color: #f8fafc; font-size: 14px; outline: none; transition: all 0.2s;}";
    html += "input:focus, select:focus {border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);}";
    html += ".btn-scan {background: #0284c7; color: #fff; border: 1px solid #38bdf8; border-radius: 8px; padding: 6px 12px; font-size: 12px; cursor: pointer; font-weight: bold; transition: all 0.2s;}";
    html += ".btn-scan:hover {background: #0369a1;}";
    html += ".btn-scan:disabled {background: #334155; color: #94a3b8; border-color: #475569; cursor: not-allowed;}";
    html += ".hint-box {background: rgba(14, 165, 233, 0.1); border: 1px solid rgba(14, 165, 233, 0.3); border-radius: 8px; padding: 8px 12px; font-size: 11px; color: #38bdf8; margin-top: 6px; margin-bottom: 12px;}";
    html += ".quick-tags {display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px;}";
    html += ".tag {font-size: 11px; background: #1e293b; border: 1px solid #475569; padding: 4px 8px; border-radius: 6px; color: #38bdf8; cursor: pointer;}";
    html += ".tag:hover {background: #334155;}";
    html += ".btn-submit {width: 100%; margin-top: 24px; padding: 14px; background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);}";
    html += ".btn-submit:hover {background: linear-gradient(135deg, #0369a1, #1d4ed8);}";
    html += ".status-card {background: #090e1a; border-radius: 12px; padding: 12px; margin-top: 20px; font-size: 12px; color: #94a3b8; border: 1px solid #1e293b;}";
    html += ".status-row {display: flex; justify-content: space-between; margin-bottom: 4px;}";
    html += ".footer {text-align: center; font-size: 11px; color: #64748b; margin-top: 16px;}";
    html += "</style>";
    html += "<script>";
    html += "function rescan() {";
    html += "  const btn = document.getElementById('btnScan');";
    html += "  const sel = document.getElementById('ssid_select');";
    html += "  btn.innerText = '⏳ กำลังสแกน...'; btn.disabled = true;";
    html += "  sel.innerHTML = '<option value=\"\">⏳ กำลังค้นหาเครือข่าย Wi-Fi 2.4GHz รอบตัว (2-3 วินาที)...</option>';";
    html += "  fetch('/scan').then(r=>r.json()).then(data=>{";
    html += "    sel.innerHTML = '';";
    html += "    if (!Array.isArray(data) || data.length === 0) {";
    html += "      const o = document.createElement('option');";
    html += "      o.value = ''; o.text = '⚠️ ไม่พบสัญญาณ Wi-Fi (กรุณากดสแกนใหม่ หรือพิมพ์ชื่อ SSID เองด้านล่าง)';";
    html += "      sel.appendChild(o);";
    html += "      btn.innerText = '⚠️ ลองสแกนใหม่'; btn.disabled = false;";
    html += "    } else {";
    html += "      const defOpt = document.createElement('option');";
    html += "      defOpt.value = ''; defOpt.text = '-- แตะเพื่อเลือก Wi-Fi (พบ ' + data.length + ' เครือข่าย) --';";
    html += "      sel.appendChild(defOpt);";
    html += "      data.forEach(item => {";
    html += "        if(!item.ssid) return;";
    html += "        const opt = document.createElement('option');";
    html += "        opt.value = item.ssid;";
    html += "        const q = item.rssi >= -60 ? '📶 ยอดเยี่ยม' : (item.rssi >= -75 ? '📶 ปานกลาง' : '⚠️ อ่อน');";
    html += "        const lock = item.auth ? '🔒' : '🔓';";
    html += "        opt.text = lock + ' ' + item.ssid + ' (' + item.rssi + ' dBm | ' + q + ')';";
    html += "        sel.appendChild(opt);";
    html += "      });";
    html += "      btn.innerText = '✅ พบ ' + data.length + ' เครือข่าย';";
    html += "      setTimeout(()=>{ btn.innerText = '🔄 สแกนใหม่'; btn.disabled = false; }, 2000);";
    html += "    }";
    html += "    const custom = document.createElement('option');";
    html += "    custom.value = '__custom__'; custom.text = '✏️ -- ระบุชื่อ Wi-Fi เอง (Custom) --';";
    html += "    sel.appendChild(custom);";
    html += "  }).catch(e=>{";
    html += "    sel.innerHTML = '<option value=\"\">❌ สแกนไม่สำเร็จ (กรุณากดลองใหม่อีกครั้ง หรือพิมพ์ชื่อด้านล่าง)</option>';";
    html += "    btn.innerText = '❌ ลองสแกนใหม่'; btn.disabled = false;";
    html += "  });";
    html += "}";
    html += "function onSelectSSID(val) {";
    html += "  if(val && val !== '__custom__') {";
    html += "    document.getElementById('ssid').value = val;";
    html += "    document.getElementById('password').focus();";
    html += "  }";
    html += "}";
    html += "function fillServer(url) { document.getElementById('server_url').value = url; }";
    html += "window.addEventListener('DOMContentLoaded', ()=>{ setTimeout(rescan, 400); });";
    html += "</script>";
    html += "</head><body>";
    html += "<div class='container'>";
    html += "<div class='header'>";
    html += "<h1>🌿 LEQs-AgriEnvi-xAI</h1>";
    html += "<p class='sub'>ศูนย์ตั้งค่า Wi-Fi และแดชบอร์ดเกษตรอัจฉริยะ (All-in-One Provisioning)</p>";
    html += "<span class='badge'>ATD3.5-S3 • Real-time Telemetry Engine</span>";
    html += "</div>";

    html += "<form action='/save' method='POST'>";
    
    // Wi-Fi Selection
    html += "<div class='scan-bar'>";
    html += "<label>เลือกเครือข่าย Wi-Fi 2.4GHz:</label>";
    html += "<button type='button' id='btnScan' class='btn-scan' onclick='rescan()'>🔍 สแกนหา Wi-Fi</button>";
    html += "</div>";
    html += "<select id='ssid_select' onchange='onSelectSSID(this.value)'>";
    html += "<option value=''>⏳ กำลังเตรียมระบบสแกน Wi-Fi...</option>";
    html += "<option value='__custom__'>✏️ -- ระบุชื่อ Wi-Fi เอง (Custom) --</option>";
    html += "</select>";
    html += "<div class='hint-box'>💡 ระบบจะสแกนหา Wi-Fi 2.4GHz อัตโนมัติเมื่อเปิดหน้านี้ หากยังไม่พบให้กดปุ่ม <b>สแกนหา Wi-Fi</b> อีกครั้ง</div>";

    html += "<label for='ssid' style='display:block; margin-top:12px; margin-bottom:6px;'>ชื่อ Wi-Fi (SSID):</label>";
    html += "<input type='text' id='ssid' name='ssid' value='" + storedSSID + "' placeholder='เช่น MyFarm_WiFi หรือ Hotspot' required>";

    html += "<label for='password' style='display:block; margin-top:12px; margin-bottom:6px;'>รหัสผ่าน Wi-Fi (Password):</label>";
    html += "<input type='password' id='password' name='password' value='" + storedPass + "' placeholder='เว้นว่างไว้หากเป็นเครือข่ายไม่มีรหัสผ่าน'>";

    // Server API URL
    html += "<label for='server_url' style='display:block; margin-top:14px; margin-bottom:6px;'>Dashboard / Server API URL ปลายทาง:</label>";
    html += "<input type='text' id='server_url' name='server_url' value='" + storedServerUrl + "' placeholder='http://10.100.2.179/... หรือ Cloud Hub'>";
    html += "<div class='quick-tags'>";
    if (defaultLocalServer.length() > 0) {
        html += "<span class='tag' onclick=\"fillServer('" + defaultLocalServer + "')\">🏠 Local XAMPP API (" + defaultLocalServer.substring(7, defaultLocalServer.indexOf('/', 7)) + ")</span>";
    }
    if (defaultCloudServer.length() > 0) {
        html += "<span class='tag' onclick=\"fillServer('" + defaultCloudServer + "')\">☁️ Cloud Telemetry Hub</span>";
    }
    html += "</div>";

    // Device ID
    html += "<label for='device_id' style='display:block; margin-top:14px; margin-bottom:6px;'>รหัสประจำอุปกรณ์ (Device ID):</label>";
    html += "<input type='text' id='device_id' name='device_id' value='" + storedDeviceId + "' placeholder='ESP32-S3-ATD35'>";

    html += "<button type='submit' class='btn-submit'>💾 บันทึกและเชื่อมต่อทันที</button>";
    html += "</form>";

    // Status Card
    html += "<div class='status-card'>";
    html += "<div class='status-row'><span>MAC Address:</span><b>" + WiFi.macAddress() + "</b></div>";
    html += "<div class='status-row'><span>Hotspot IP:</span><b>192.168.4.1</b></div>";
    html += "<div class='status-row'><span>Free Heap:</span><b>" + String(ESP.getFreeHeap() / 1024) + " KB</b></div>";
    html += "<div class='status-row'><span>Flash Size:</span><b>" + String(ESP.getFlashChipSize() / (1024*1024)) + " MB</b></div>";
    html += "</div>";

    html += "<div class='footer'>โครงการนวัตกรรมเกษตรดิจิทัลและสิ่งแวดล้อม LEQs-xAI<br>มหาวิทยาลัยราชภัฏรำไพพรรณี</div>";
    html += "</div></body></html>";

    return html;
}

static void handleRoot() {
    server.send(200, "text/html", buildHtmlPage());
}

static void handleScanJson() {
    Serial.println("[WiFiConfig] Client triggered Wi-Fi scan via /scan...");

    // 1. ล้างแคชการสแกนเก่า
    WiFi.scanDelete();
    delay(50);

    // 2. หยุดการเชื่อมต่อ STA ชั่วคราว เพื่อปลดล็อกวิทยุ 2.4GHz
    if (WiFi.status() != WL_CONNECTED) {
        WiFi.disconnect(false);
        delay(60);
    }

    // 3. สแกน Wi-Fi คลื่น 2.4GHz ครบทุกแชนแนล (Channels 1 - 13)
    // async=false, show_hidden=false, passive=false, max_ms_per_chan=250
    int16_t n = WiFi.scanNetworks(false, false, false, 250);
    if (n < 0) {
        Serial.printf("[WiFiConfig] Scan busy/retry (%d)...\n", n);
        delay(150);
        WiFi.scanDelete();
        n = WiFi.scanNetworks(false, false, false, 300);
    }

    Serial.printf("[WiFiConfig] Scan complete! Raw networks: %d\n", n > 0 ? n : 0);

    String json = "[";
    if (n > 0) {
        int count = 0;
        for (int i = 0; i < n; i++) {
            String ssid = WiFi.SSID(i);
            ssid.trim();
            if (ssid.length() == 0) continue;

            if (count > 0) json += ",";
            json += "{\"ssid\":\"" + ssid + "\",";
            json += "\"rssi\":" + String(WiFi.RSSI(i)) + ",";
            json += "\"channel\":" + String(WiFi.channel(i)) + ",";
            json += "\"auth\":" + String(WiFi.encryptionType(i) != WIFI_AUTH_OPEN ? 1 : 0) + "}";
            count++;
        }
    }
    json += "]";

    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.sendHeader("Cache-Control", "no-cache, no-store, must-revalidate");
    server.send(200, "application/json", json);
}

static void handleStatusJson() {
    String json = "{";
    json += "\"status\":\"" + String(WiFi.status() == WL_CONNECTED ? "CONNECTED" : "DISCONNECTED") + "\",";
    json += "\"ip\":\"" + WiFi.localIP().toString() + "\",";
    json += "\"mac\":\"" + WiFi.macAddress() + "\",";
    json += "\"ssid\":\"" + storedSSID + "\",";
    json += "\"rssi\":" + String(WiFi.RSSI()) + ",";
    json += "\"server_url\":\"" + storedServerUrl + "\",";
    json += "\"device_id\":\"" + storedDeviceId + "\",";
    json += "\"free_heap\":" + String(ESP.getFreeHeap());
    json += "}";
    server.send(200, "application/json", json);
}

static void handleSave() {
    String newSsid      = server.arg("ssid");
    String newPass      = server.arg("password");
    String newServerUrl = server.arg("server_url");
    String newDeviceId  = server.arg("device_id");

    if (newSsid.length() > 0) {
        WiFiConfigManager_saveCredentials(newSsid, newPass, newServerUrl, newDeviceId);

        String res = "<!DOCTYPE html><html lang='th'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        res += "<style>body{background:#070c18;color:#fff;font-family:sans-serif;padding:30px;text-align:center;}";
        res += ".box{max-width:440px;margin:auto;background:#1e293b;padding:32px;border-radius:20px;border:1px solid #22c55e;box-shadow:0 15px 35px rgba(0,0,0,0.5);}";
        res += "h2{color:#22c55e;margin-top:0;} p{color:#94a3b8;font-size:14px;}</style></head><body>";
        res += "<div class='box'>";
        res += "<h2>✅ บันทึกการตั้งค่าสำเร็จ!</h2>";
        res += "<p>กำลังเชื่อมต่อเครือข่าย Wi-Fi: <b>" + newSsid + "</b></p>";
        if (newServerUrl.length() > 0) {
            res += "<p>Dashboard Server: <b>" + newServerUrl + "</b></p>";
        }
        res += "<p>บอร์ดจะรีบูตเข้าสู่โหมดทำงานปกติภายใน 3 วินาที...</p>";
        res += "</div></body></html>";
        server.send(200, "text/html", res);

        delay(2000);
        ESP.restart();
    } else {
        server.send(400, "text/plain", "Error: SSID cannot be empty");
    }
}

bool WiFiConfigManager_isPortalActive() {
    return portalActive;
}

void WiFiConfigManager_startPortal() {
    if (portalActive) return;

    Serial.println("\n[WiFiConfig] Starting SoftAP Captive Portal: 'LEQs-AgriEnvi-Setup'...");

    WiFi.mode(WIFI_AP_STA);
    WiFi.softAPConfig(apIP, apIP, netMsk);
    WiFi.softAP("LEQs-AgriEnvi-Setup", ""); // Open AP เพื่อให้ผู้ใช้เชื่อมต่อง่ายที่สุด

    dnsServer.setErrorReplyCode(DNSReplyCode::NoError);
    dnsServer.start(DNS_PORT, "*", apIP);

    server.on("/", handleRoot);
    server.on("/scan", HTTP_GET, handleScanJson);
    server.on("/status", HTTP_GET, handleStatusJson);
    server.on("/save", HTTP_POST, handleSave);
    server.onNotFound(handleRoot); // Redirect all requests to setup page (Captive Portal)
    server.begin();

    portalActive = true;
    Serial.printf("[WiFiConfig] >>> SoftAP is READY! Connect to 'LEQs-AgriEnvi-Setup' -> http://192.168.4.1\n");
}

void WiFiConfigManager_stopPortal() {
    if (!portalActive) return;
    server.stop();
    dnsServer.stop();
    WiFi.softAPdisconnect(true);
    WiFi.mode(WIFI_STA);
    portalActive = false;
    Serial.println("[WiFiConfig] SoftAP Captive Portal stopped.");
}

void WiFiConfigManager_loop() {
    if (portalActive) {
        dnsServer.processNextRequest();
        server.handleClient();
    }
}

bool WiFiConfigManager_processSerialCommand(const String &cmd) {
    String c = cmd;
    c.trim();
    if (c.length() == 0) return false;

    if (c.equalsIgnoreCase("HELP") || c.equalsIgnoreCase("?")) {
        Serial.println("\n=======================================================");
        Serial.println("  ESP32-S3 SMART FARM ALL-CHANNEL SERIAL COMMANDS     ");
        Serial.println("=======================================================");
        Serial.println("  SCAN                      - Scan 2.4GHz Wi-Fi networks");
        Serial.println("  SET_WIFI <ssid> <pass>    - Save Wi-Fi credentials & connect");
        Serial.println("  SET_SERVER <url>          - Set Dashboard / Server API URL");
        Serial.println("  SET_DEVICE <id>           - Set Device ID");
        Serial.println("  START_PORTAL              - Turn on SoftAP Captive Portal");
        Serial.println("  STOP_PORTAL               - Turn off SoftAP Captive Portal");
        Serial.println("  STATUS                    - Show full system & network status");
        Serial.println("  RESET_CONFIG              - Clear all stored NVS settings");
        Serial.println("  REBOOT                    - Restart ESP32 controller");
        Serial.println("  TIME:<epoch>              - Sync system clock with Unix Epoch");
        Serial.println("=======================================================\n");
        return true;
    }

    if (c.equalsIgnoreCase("SCAN") || c.equalsIgnoreCase("WIFI_SCAN")) {
        WiFiConfigManager_scanNetworksAndPrint();
        return true;
    }

    if (c.startsWith("SET_WIFI ") || c.startsWith("SET_WIFI:")) {
        int spaceIdx = c.indexOf(' ');
        if (spaceIdx < 0) spaceIdx = c.indexOf(':');
        String rest = c.substring(spaceIdx + 1);
        rest.trim();
        int sepIdx = rest.indexOf(' ');
        if (sepIdx < 0) sepIdx = rest.indexOf(':');
        String s = (sepIdx >= 0) ? rest.substring(0, sepIdx) : rest;
        String p = (sepIdx >= 0) ? rest.substring(sepIdx + 1) : "";
        s.trim();
        p.trim();
        if (s.length() > 0) {
            WiFiConfigManager_saveCredentials(s, p, storedServerUrl, storedDeviceId);
            Serial.printf("[CLI] Wi-Fi set to SSID='%s'. Restarting connection...\n", s.c_str());
            WiFi.disconnect();
            delay(100);
            WiFi.begin(s.c_str(), p.c_str());
        }
        return true;
    }

    if (c.startsWith("SET_SERVER ") || c.startsWith("SET_SERVER:")) {
        int idx = (c.indexOf(' ') >= 0) ? c.indexOf(' ') : c.indexOf(':');
        String url = c.substring(idx + 1);
        url.trim();
        if (url.length() > 0) {
            WiFiConfigManager_setServerURL(url);
            Serial.printf("[CLI] Dashboard URL set to: '%s'\n", url.c_str());
        }
        return true;
    }

    if (c.startsWith("SET_DEVICE ") || c.startsWith("SET_DEVICE:")) {
        int idx = (c.indexOf(' ') >= 0) ? c.indexOf(' ') : c.indexOf(':');
        String dev = c.substring(idx + 1);
        dev.trim();
        if (dev.length() > 0) {
            WiFiConfigManager_setDeviceID(dev);
            Serial.printf("[CLI] Device ID set to: '%s'\n", dev.c_str());
        }
        return true;
    }

    if (c.equalsIgnoreCase("START_PORTAL") || c.equalsIgnoreCase("PORTAL_START")) {
        WiFiConfigManager_startPortal();
        return true;
    }

    if (c.equalsIgnoreCase("STOP_PORTAL") || c.equalsIgnoreCase("PORTAL_STOP")) {
        WiFiConfigManager_stopPortal();
        return true;
    }

    if (c.equalsIgnoreCase("STATUS")) {
        Serial.println("\n-------------------------------------------------------");
        Serial.println(">>> ESP32-S3 REAL-TIME SYSTEM & TELEMETRY STATUS");
        Serial.println("-------------------------------------------------------");
        Serial.printf("  Wi-Fi Status   : %s\n", (WiFi.status() == WL_CONNECTED) ? "ONLINE (CONNECTED)" : "OFFLINE");
        Serial.printf("  Connected SSID : %s\n", WiFi.SSID().c_str());
        Serial.printf("  IP Address     : %s\n", WiFi.localIP().toString().c_str());
        Serial.printf("  MAC Address    : %s\n", WiFi.macAddress().c_str());
        Serial.printf("  Signal (RSSI)  : %d dBm\n", WiFi.RSSI());
        Serial.printf("  Dashboard URL  : %s\n", storedServerUrl.c_str());
        Serial.printf("  Device ID      : %s\n", storedDeviceId.c_str());
        Serial.printf("  SoftAP Portal  : %s\n", portalActive ? "ACTIVE (192.168.4.1)" : "STOPPED");
        Serial.printf("  Free Heap      : %d KB\n", ESP.getFreeHeap() / 1024);
        Serial.printf("  Free PSRAM     : %d KB\n", ESP.getFreePsram() / 1024);
        Serial.println("-------------------------------------------------------\n");
        return true;
    }

    if (c.equalsIgnoreCase("RESET_CONFIG") || c.equalsIgnoreCase("RESET_WIFI")) {
        WiFiConfigManager_resetCredentials();
        return true;
    }

    if (c.equalsIgnoreCase("REBOOT") || c.equalsIgnoreCase("RESTART")) {
        Serial.println("[CLI] Rebooting ESP32 controller...");
        delay(500);
        ESP.restart();
        return true;
    }

    return false;
}
