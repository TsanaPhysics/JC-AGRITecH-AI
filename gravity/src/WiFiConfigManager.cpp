#include "WiFiConfigManager.h"
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

static const byte DNS_PORT = 53;
static IPAddress apIP(192, 168, 4, 1);
static IPAddress netMsk(255, 255, 255, 0);

void WiFiConfigManager_init() {
    prefs.begin("agri_wifi", false);
    storedSSID = prefs.getString("ssid", "");
    storedPass = prefs.getString("pass", "");
    prefs.end();

    if (storedSSID.length() > 0) {
        Serial.printf("[WiFiConfig] Found stored credentials in NVS: SSID='%s'\n", storedSSID.c_str());
    } else {
        Serial.println("[WiFiConfig] No stored credentials found in NVS Flash.");
    }
}

String WiFiConfigManager_getSSID() {
    return storedSSID;
}

String WiFiConfigManager_getPassword() {
    return storedPass;
}

bool WiFiConfigManager_hasStoredCredentials() {
    return (storedSSID.length() > 0);
}

void WiFiConfigManager_saveCredentials(const String &ssid, const String &pass) {
    prefs.begin("agri_wifi", false);
    prefs.putString("ssid", ssid);
    prefs.putString("pass", pass);
    prefs.end();
    storedSSID = ssid;
    storedPass = pass;
    Serial.printf("[WiFiConfig] Successfully saved credentials to NVS: SSID='%s'\n", ssid.c_str());
}

static String buildHtmlPage() {
    // สแกน Wi-Fi รอบตัว
    int n = WiFi.scanNetworks();
    String options = "";
    for (int i = 0; i < n; ++i) {
        String s = WiFi.SSID(i);
        if (s.length() > 0) {
            options += "<option value='" + s + "'>" + s + " (" + String(WiFi.RSSI(i)) + " dBm)</option>";
        }
    }

    String html = "<!DOCTYPE html><html><head>";
    html += "<meta charset='utf-8'>";
    html += "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    html += "<title>JC-AgriTech Wi-Fi Setup</title>";
    html += "<style>";
    html += "body{font-family:sans-serif;background:#0b1120;color:#f8fafc;margin:0;padding:20px;}";
    html += ".card{max-width:400px;margin:20px auto;background:#1e293b;padding:24px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.5);border:1px solid #334155;}";
    html += "h2{color:#38bdf8;margin-top:0;font-size:22px;text-align:center;}";
    html += "p{font-size:14px;color:#94a3b8;line-height:1.5;text-align:center;}";
    html += "label{display:block;margin-top:14px;font-size:13px;color:#cbd5e1;font-weight:bold;}";
    html += "input,select{width:100%;padding:12px;margin-top:6px;border-radius:8px;border:1px solid #475569;background:#0f172a;color:#fff;box-sizing:border-box;font-size:15px;}";
    html += "button{width:100%;margin-top:24px;padding:14px;background:linear-gradient(135deg,#0284c7,#025ce2);color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:bold;cursor:pointer;}";
    html += ".badge{display:inline-block;padding:4px 10px;border-radius:20px;background:#0284c7;color:#fff;font-size:12px;font-weight:bold;}";
    html += "</style></head><body>";
    html += "<div class='card'>";
    html += "<h2>🌿 JC -AgriTech + AI</h2>";
    html += "<p><span class='badge'>ATD3.5-S3 Smart Farm</span><br>ตั้งค่าเชื่อมต่อ Wi-Fi สำหรับแปลงเกษตร</p>";
    html += "<form action='/save' method='POST'>";
    html += "<label for='ssid_select'>เลือกเครือข่าย Wi-Fi 2.4GHz:</label>";
    html += "<select id='ssid_select' name='ssid_select' onchange=\"if(this.value!='__custom__'){document.getElementById('ssid').value=this.value;}\">";
    html += "<option value=''>-- แตะเพื่อเลือก Wi-Fi --</option>";
    html += options;
    html += "<option value='__custom__'>-- ระบุชื่อ Wi-Fi เอง (Custom) --</option>";
    html += "</select>";
    html += "<label for='ssid'>ชื่อ Wi-Fi (SSID):</label>";
    html += "<input type='text' id='ssid' name='ssid' value='" + storedSSID + "' required>";
    html += "<label for='password'>รหัสผ่าน Wi-Fi (Password):</label>";
    html += "<input type='password' id='password' name='password' value='" + storedPass + "'>";
    html += "<button type='submit'>💾 บันทึกและเริ่มเชื่อมต่อทันที</button>";
    html += "</form>";
    html += "<p style='margin-top:20px;font-size:12px;color:#64748b;'>ผศ.ดร.ชีวะ ทัศนา • มรภ.รำไพพรรณี</p>";
    html += "</div></body></html>";

    return html;
}

static void handleRoot() {
    server.send(200, "text/html", buildHtmlPage());
}

static void handleSave() {
    String newSsid = server.arg("ssid");
    String newPass = server.arg("password");

    if (newSsid.length() > 0) {
        WiFiConfigManager_saveCredentials(newSsid, newPass);

        String res = "<!DOCTYPE html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        res += "<style>body{background:#0b1120;color:#fff;font-family:sans-serif;padding:30px;text-align:center;}";
        res += ".box{max-width:400px;margin:auto;background:#1e293b;padding:30px;border-radius:16px;border:1px solid #22c55e;}";
        res += "h2{color:#22c55e;}</style></head><body>";
        res += "<div class='box'>";
        res += "<h2>✅ บันทึกข้อมูลสำเร็จ!</h2>";
        res += "<p>กำลังเชื่อมต่อ Wi-Fi: <b>" + newSsid + "</b></p>";
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

    Serial.println("\n[WiFiConfig] Starting SoftAP Captive Portal: 'JC-AgriTech-Setup'...");

    WiFi.mode(WIFI_AP_STA);
    WiFi.softAPConfig(apIP, apIP, netMsk);
    WiFi.softAP("JC-AgriTech-Setup", ""); // Open AP ไม่มีรหัสผ่านเพื่อให้เกษตรกรเชื่อมต่อง่ายที่สุด

    dnsServer.setErrorReplyCode(DNSReplyCode::NoError);
    dnsServer.start(DNS_PORT, "*", apIP);

    server.on("/", handleRoot);
    server.on("/save", HTTP_POST, handleSave);
    server.onNotFound(handleRoot); // Redirect all requests to setup page
    server.begin();

    portalActive = true;
    Serial.printf("[WiFiConfig] SoftAP is READY! IP: %s (Connect to 'JC-AgriTech-Setup')\n", apIP.toString().c_str());
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
