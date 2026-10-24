# คู่มือวิศวกรรมการเชื่อมต่อสื่อสาร IoT ข้ามเครือข่ายฉบับสมบูรณ์ (Cross-Network IoT Communication Master Guide)
### การสั่งงาน ควบคุม และมอนิเตอร์บอร์ด ESP32 ข้ามเครือข่ายต่างวง (Mobile Hotspot vs. Campus/Home Wi-Fi) สำหรับผู้เริ่มต้น

---

## 1. บทนำและทำความเข้าใจปัญหา (The Problem & Core Concept)

### 1.1 สถานการณ์จริงที่มักพบเจอ
* **บอร์ด ESP32:** วางอยู่ในแปลงเกษตร โรงเรือน หรือห้องแล็บ เชื่อมต่ออินเทอร์เน็ตผ่าน **Mobile Hotspot ของสมาร์ทโฟน** (หรือซิมเราเตอร์ 4G/5G)
* **คอมพิวเตอร์ / สมาร์ทโฟน:** เปิดหน้าจอแดชบอร์ด (Web Dashboard) โดยเชื่อมต่อผ่าน **Wi-Fi ของบ้าน หรือ Wi-Fi ของมหาวิทยาลัย/หน่วยงาน**

### 1.2 ทำไมถึงสั่งงานผ่าน Local IP (192.168.x.x) ตรงๆ ไม่ได้?
1. **คนละวงแลน (Different Local Subnet):** มือถือ Hotspot มักจะแจก IP วง `192.168.43.x` ให้ ESP32 ในขณะที่ Wi-Fi บ้าน/มหาวิทยาลัย จะแจกวง `192.168.1.x` หรือ `10.x.x.x`
2. **ติดกำแพงไฟและระบบ NAT (Firewall / CGNAT):** เครือข่ายมือถือ (4G/5G) และมหาวิทยาลัยมีระบบป้องกันความปลอดภัยอย่างเข้มงวด ทำให้อุปกรณ์ภายนอกไม่สามารถมองเห็นหรือส่งสัญญาณเจาะทะลุเข้ามาหา ESP32 ได้โดยตรง
3. **ระบบ Client Isolation (AP Isolation):** ในมหาวิทยาลัย แม้จะต่อ Wi-Fi ตัวเดียวกัน ระบบมักจะบล็อกไม่ให้อุปกรณ์สองเครื่องคุยกันเองเพื่อความปลอดภัย

### 1.3 หัวใจของทางออก: "การใช้ตัวกลางบนคลาวด์ (Cloud Intermediary)"
วิธีแก้ปัญหาคือ **ไม่ต้องให้อุปกรณ์ทั้งสองคุยกันเองตรงๆ** แต่ให้อุปกรณ์ทั้งสองวิ่งออกไปหา **"เซิร์ฟเวอร์ตัวกลางบนอินเทอร์เน็ต"** ที่มีที่อยู่ชัดเจน (Public Server)

```
┌─────────────────────────────────┐                    ┌─────────────────────────────────┐
│     ESP32 (เกาะ Hotspot มือถือ)   │                    │   คอมพิวเตอร์ (เกาะ Wi-Fi มหาลัย)  │
│      - ส่งข้อมูลเซนเซอร์           │                    │      - ดูแดชบอร์ด มอนิเตอร์กราฟ   │
│      - รับคำสั่งเปิด-ปิดรีเลย์     │                    │      - กดปุ่มเปิด-ปิดปั๊มน้ำ     │
└───────────────┬─────────────────┘                    └────────────────┬────────────────┘
                │                                                       │
         (วิ่งออกเน็ต)                                            (วิ่งออกเน็ต)
                │                                                       │
                ▼                                                       ▼
        ┌───────────────────────────────────────────────────────────────────────┐
        │                     เซิร์ฟเวอร์ตัวกลาง (Cloud Server)                 │
        │               ทำหน้าที่รับฝากข้อมูล และเป็นสะพานส่งต่อคำสั่ง            │
        │   [วิธีที่ 1: MQTT Broker]  [วิธีที่ 2: Firebase]  [วิธีที่ 3: Tunnel]  │
        └───────────────────────────────────────────────────────────────────────┘
```

---

## สารบัญเนื้อหาในคู่มือ
1. [วิธีที่ 1: การใช้ Cloud MQTT Broker (มาตรฐานสากล รวดเร็ว Real-time สูงสุด)](#วิธีที่-1-cloud-mqtt-broker)
2. [วิธีที่ 2: การใช้ Google Firebase Realtime Database (ทำง่าย มีประวัติย้อนหลัง)](#วิธีที่-2-google-firebase-realtime-database)
3. [วิธีที่ 3: การรัน Dashboard บนคอมตัวเอง (XAMPP/Localhost) ร่วมกับ Cloudflare Tunnel](#วิธีที่-3-cloudflare-tunnel-กับ-localhost)
4. [ตารางเปรียบเทียบข้อดี-ข้อจำกัด และคำแนะนำการเลือกใช้งาน](#ตารางเปรียบเทียบและข้อเสนอแนะ)
5. [ปัญหาที่พบบ่อยและการแก้ไข (Troubleshooting & FAQs)](#ปัญหาที่พบบ่อยและการแก้ไข)

---

<a name="วิธีที่-1-cloud-mqtt-broker"></a>
## 2. วิธีที่ 1: การใช้ Cloud MQTT Broker (มาตรฐานสากล Real-time สูงสุด)

MQTT (Message Queuing Telemetry Transport) เปรียบเสมือน **"ตู้ไปรษณีย์กลาง"**
* **Topic:** คือชื่อกล่องจดหมาย เช่น `farm1/sensor/dht` หรือ `farm1/relay1/cmd`
* **Publish (ส่งจดหมาย):** ESP32 ส่งค่าอุณหภูมิเข้าไปหย่อนไว้ในตู้
* **Subscribe (เปิดอ่านจดหมาย):** แดชบอร์ดเฝ้าดูตู้ พอมีจดหมายใหม่เข้ามา ก็เปิดอ่านและอัปเดตหน้าจอทันที (และแดชบอร์ดก็หย่อนจดหมายสั่งเปิดไฟ แล้ว ESP32 เปิดอ่านเพื่อทำงานได้เช่นกัน)

### ขั้นตอนที่ 1.1: แหล่งให้บริการ Cloud MQTT Broker ฟรีที่ใช้งานได้จริงในปัจจุบัน

> [!WARNING]
> **การเปลี่ยนแปลงสำคัญของ HiveMQ Cloud:**
> * ทาง HiveMQ ได้เริ่ม**ยกเลิกแพ็กเกจ Serverless ฟรี** และหน้าจอจะแสดงเฉพาะแพ็กเกจ **"Starter" ($249/เดือน หรือประมาณ 8,500 บาท/เดือน)** พร้อมข้อความเตือนว่า *“Starter creation is temporarily unavailable. We're at capacity...”*
> * **ห้ามจ่ายเงินและห้ามกด Starter เด็ดขาด!** 

เพื่อให้นักศึกษา อาจารย์ และผู้เริ่มต้นสามารถต่อระบบได้ทันทีโดยไม่ต้องเสียค่าใช้จ่าย เรามี **2 ทางเลือกที่ดีที่สุดและฟรี 100%** ดังนี้:

---

#### 🌟 ทางเลือก A: ใช้ Public Test Broker (ง่ายที่สุด เริ่มได้ใน 1 นาที ไม่ต้องสมัครสมาชิกใดๆ)
HiveMQ และ EMQX มีเซิร์ฟเวอร์ทดสอบสาธารณะบนอินเทอร์เน็ตเปิดให้ใช้งานฟรีตลอดเวลา เหมาะสำหรับผู้เริ่มต้นและโครงงานวิจัย:
* **Broker Host:** `broker.hivemq.com` (หรือ `broker.emqx.io`)
* **Port สำหรับ ESP32:** `1883` (ไม่ต้องใช้ SSL, ไม่ต้องใช้ Username/Password)
* **Port สำหรับ Dashboard หน้าเว็บ:** `8000` (ผ่าน WebSockets URL: `ws://broker.hivemq.com:8000/mqtt`)
* **ข้อควรปฏิบัติ:** เนื่องจากเป็นเซิร์ฟเวอร์สาธารณะ ให้ตั้งชื่อ Topic ให้มีเอกลักษณ์เฉพาะตัว เพื่อไม่ให้ซ้ำกับผู้อื่น เช่น `rbru_farm_lab_01/sensor/data`

---

#### 🔒 ทางเลือก B: ใช้ EMQX Cloud Serverless (เซิร์ฟเวอร์ส่วนตัว ปลอดภัย มีรหัสผ่าน ฟรีตลอดชีพ)
หากต้องการความปลอดภัย มี Username/Password ป้องกันคนอื่นเห็นข้อมูล:
1. เข้าไปที่: **[https://cloud.emqx.com/](https://cloud.emqx.com/)**
2. สมัครสมาชิกด้วยอีเมล (Sign Up Free)
3. เลือกสร้าง **"Serverless Deployment"**
   * **โควตาฟรี:** ให้ฟรี 1,000,000 session minutes และรับส่งข้อมูลฟรี 1 GB ต่อเดือนตลอดชีพ
   * **ตั้งค่าจำกัดงบ (Spend Limit):** ตั้งค่าเป็น **$0.00** ใน Account Settings เพื่อให้ระบบตัดเมื่อหมดโควตา ป้องกันการคิดเงิน 100%
4. ไปที่เมนู **Authentication** เพื่อสร้าง Username และ Password
5. จะได้ Broker Host สำหรับใส่ในโค้ดทันที เช่น `xxxx.emqxsl.com` พอร์ต `1883` หรือ SSL `8883`

---

### ขั้นตอนที่ 1.2: โค้ดสำหรับบอร์ด ESP32 (Arduino IDE / PlatformIO)
ติดตั้งไลบรารีใน Arduino IDE (Sketch -> Include Library -> Manage Libraries):
* `PubSubClient` โดย Nick O'Leary
* `ArduinoJson` โดย Benoit Blanchon

```cpp
#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>

// --- 1. ตั้งค่า Wi-Fi (Hotspot มือถือ) ---
const char* ssid = "YOUR_HOTSPOT_NAME";       // ชื่อ Hotspot มือถือ
const char* password = "YOUR_HOTSPOT_PASS";   // รหัสผ่าน Hotspot

// --- 2. ข้อมูล Public MQTT Broker (ใช้งานได้ทันที ไม่ต้องสมัครสมาชิก ฟรี 100%) ---
const char* mqtt_server = "broker.hivemq.com"; // หรือใช้ "broker.emqx.io"
const int mqtt_port = 1883;

// Topics สำหรับส่งและรับข้อมูล (แนะนำให้เปลี่ยนคำนำหน้าให้เป็นชื่อเฉพาะของตนเอง)
const char* topic_sensor = "my_rbru_farm/sensor/data";
const char* topic_relay_cmd = "my_rbru_farm/relay/command";

// ขาควบคุมรีเลย์ / หลอดไฟ LED
const int RELAY_PIN = 2; // LED Onboard หรือต่อขารีเลย์จริง

WiFiClient espClient;
PubSubClient client(espClient);

unsigned long lastSend = 0;

// ฟังก์ชันเมื่อมีข้อความสั่งงานส่งมาจากแดชบอร์ด (Callback)
void callback(char* topic, byte* payload, unsigned int length) {
  String message = "";
  for (int i = 0; i < length; i++) {
    message += (char)payload[i];
  }
  Serial.print("ได้รับคำสั่งจาก Dashboard [Topic: ");
  Serial.print(topic);
  Serial.print("]: ");
  Serial.println(message);

  // ตรวจสอบคำสั่งเปิด-ปิด
  if (String(topic) == topic_relay_cmd) {
    if (message == "ON") {
      digitalWrite(RELAY_PIN, HIGH);
      Serial.println(">> สั่งเปิดอุปกรณ์ (RELAY ON) สำเร็จ!");
    } else if (message == "OFF") {
      digitalWrite(RELAY_PIN, LOW);
      Serial.println(">> สั่งปิดอุปกรณ์ (RELAY OFF) สำเร็จ!");
    }
  }
}

// ฟังก์ชันเชื่อมต่อ MQTT
void reconnectMQTT() {
  while (!client.connected()) {
    Serial.print("กำลังเชื่อมต่อไปยัง Public MQTT Broker...");
    String clientId = "ESP32_Client_" + String(random(0xffff), HEX);
    // เชื่อมต่อโดยไม่ต้องใช้ Username/Password
    if (client.connect(clientId.c_str())) {
      Serial.println(" เชื่อมต่อสำเร็จ!");
      // ติดตามฟังหัวข้อคำสั่งเปิด-ปิด
      client.subscribe(topic_relay_cmd);
      Serial.print("กำลังติดตาม Topic: ");
      Serial.println(topic_relay_cmd);
    } else {
      Serial.print("ล้มเหลว, รหัสข้อผิดพลาด = ");
      Serial.print(client.state());
      Serial.println(" รออีก 5 วินาทีเพื่อเชื่อมต่อใหม่...");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, LOW);

  // เชื่อมต่อ Wi-Fi จาก Hotspot มือถือ
  Serial.print("กำลังเชื่อมต่อ Hotspot: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWi-Fi เชื่อมต่อสำเร็จแล้ว!");

  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
}

void loop() {
  if (!client.connected()) {
    reconnectMQTT();
  }
  client.loop(); // ดูแลการรับข้อมูลตลอดเวลา

  // ส่งข้อมูลเซนเซอร์จำลองขึ้นคลาวด์ทุกๆ 5 วินาที
  unsigned long now = millis();
  if (now - lastSend > 5000) {
    lastSend = now;

    // จำลองค่าเซนเซอร์ (หรือเปลี่ยนเป็นค่าอ่านจาก DHT22, SHT45, Soil Sensor จริง)
    float temp = 28.5 + (random(-10, 10) / 10.0);
    float humid = 75.0 + (random(-15, 15) / 10.0);
    int soil_moisture = random(40, 80);

    // แพ็กข้อมูลเป็น JSON
    StaticJsonDocument<200> doc;
    doc["temperature"] = temp;
    doc["humidity"] = humid;
    doc["soil"] = soil_moisture;

    char buffer[256];
    serializeJson(doc, buffer);

    // ส่ง (Publish) ขึ้น Broker
    client.publish(topic_sensor, buffer);
    Serial.print("ส่งค่าเซนเซอร์ขึ้นคลาวด์: ");
    Serial.println(buffer);
  }
}
```

---

### ขั้นตอนที่ 1.3: โค้ดหน้าเว็บแดชบอร์ด (เปิดบนคอมพิวเตอร์ที่ต่อ Wi-Fi มหาลัย/บ้าน)
สร้างไฟล์ชื่อ `dashboard_mqtt.html` ไว้บนคอมพิวเตอร์ของคุณ แล้วดับเบิลคลิกเปิดด้วยเบราว์เซอร์ Chrome / Edge ได้ทันที โดยไม่ต้องติดตั้งเซิร์ฟเวอร์ใดๆ

```html
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Smart Farm Cross-Network Dashboard (MQTT)</title>
  <!-- โหลดไลบรารี MQTT.js ผ่าน CDN -->
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #0f172a;
      color: #f8fafc;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px;
    }
    .card {
      background: #1e293b;
      border-radius: 16px;
      padding: 24px;
      width: 420px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
      border: 1px solid #334155;
      margin-bottom: 20px;
    }
    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: bold;
    }
    .connected { background: #166534; color: #4ade80; }
    .disconnected { background: #991b1b; color: #f87171; }
    .sensor-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin: 20px 0;
    }
    .sensor-box {
      background: #0f172a;
      padding: 15px;
      border-radius: 12px;
      text-align: center;
    }
    .sensor-val {
      font-size: 28px;
      font-weight: bold;
      color: #38bdf8;
      margin-top: 5px;
    }
    .btn-group {
      display: flex;
      gap: 10px;
    }
    button {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s;
    }
    .btn-on { background: #22c55e; color: #fff; }
    .btn-off { background: #ef4444; color: #fff; }
    button:hover { opacity: 0.85; transform: scale(0.98); }
  </style>
</head>
<body>

  <div class="card">
    <h2>🌾 AIoT Smart Farm Control</h2>
    <p>สถานะการเชื่อมต่อ: <span id="connStatus" class="status-badge disconnected">กำลังเชื่อมต่อ...</span></p>

    <!-- กล่องแสดงค่าเซนเซอร์ -->
    <div class="sensor-grid">
      <div class="sensor-box">
        <div>อุณหภูมิ (°C)</div>
        <div id="tempVal" class="sensor-val">--</div>
      </div>
      <div class="sensor-box">
        <div>ความชื้นอากาศ (%)</div>
        <div id="humidVal" class="sensor-val">--</div>
      </div>
    </div>
    <div class="sensor-box" style="margin-bottom: 20px;">
      <div>ความชื้นในดิน (%)</div>
      <div id="soilVal" class="sensor-val" style="color: #4ade80;">--</div>
    </div>

    <!-- ปุ่มสั่งงานรีเลย์ข้ามเครือข่าย -->
    <h3>⚡ สั่งงานอุปกรณ์ระยะไกล</h3>
    <div class="btn-group">
      <button class="btn-on" onclick="sendControl('ON')">เปิดไฟ / ปั๊มน้ำ (ON)</button>
      <button class="btn-off" onclick="sendControl('OFF')">ปิดอุปกรณ์ (OFF)</button>
    </div>
  </div>

  <script>
    // --- ตั้งค่าเชื่อมต่อ Public Broker (เริ่มใช้งานได้ทันที ไม่ต้องสมัครสมาชิก) ---
    const brokerUrl   = "ws://broker.hivemq.com:8000/mqtt"; // หรือ "ws://broker.emqx.io:8083/mqtt"
    const topicSensor = "my_rbru_farm/sensor/data";
    const topicRelay  = "my_rbru_farm/relay/command";

    // เชื่อมต่อผ่าน WebSocket
    const client = mqtt.connect(brokerUrl, {
      clean: true,
      connectTimeout: 4000
    });

    const statusElem = document.getElementById("connStatus");

    client.on('connect', () => {
      statusElem.textContent = "ออนไลน์ (Online)";
      statusElem.className = "status-badge connected";
      console.log("MQTT Connected!");
      // ติดตามข้อมูลเซนเซอร์
      client.subscribe(topicSensor);
    });

    client.on('close', () => {
      statusElem.textContent = "ออฟไลน์ (Offline)";
      statusElem.className = "status-badge disconnected";
    });

    // เมื่อมีข้อความเซนเซอร์ส่งมาจาก ESP32
    client.on('message', (topic, message) => {
      if (topic === topicSensor) {
        try {
          const data = JSON.parse(message.toString());
          document.getElementById('tempVal').textContent = data.temperature.toFixed(1);
          document.getElementById('humidVal').textContent = data.humidity.toFixed(1);
          document.getElementById('soilVal').textContent = data.soil + "%";
        } catch (e) {
          console.error("JSON Parse Error", e);
        }
      }
    });

    // ฟังก์ชันสั่งงานเมื่อกดปุ่ม
    function sendControl(cmd) {
      if (client.connected) {
        client.publish(topicRelay, cmd);
        alert("ส่งคำสั่ง: " + cmd + " ไปยังบอร์ด ESP32 เรียบร้อย");
      } else {
        alert("ยังไม่ได้เชื่อมต่อกับ Cloud Broker!");
      }
    }
  </script>
</body>
</html>
```

---

<a name="วิธีที่-2-google-firebase-realtime-database"></a>
## 3. วิธีที่ 2: การใช้ Google Firebase Realtime Database (ทำง่าย มีประวัติย้อนหลัง)

Google Firebase คือฐานข้อมูลบนคลาวด์ของ Google ข้อดีคือใช้งานง่ายมาก หน้าจอคอมพิวเตอร์และ ESP32 ต่างเชื่อมต่อเข้ากับฐานข้อมูลก้อนเดียวกัน หากค่าตัวแปรในฐานข้อมูลถูกเปลี่ยน ทั่วโลกจะรับรู้การเปลี่ยนแปลงพร้อมกันทันที

### ขั้นตอนที่ 2.1: สร้างฐานข้อมูล Firebase Realtime Database
1. ไปที่เว็บไซต์ [https://console.firebase.google.com/](https://console.firebase.google.com/) แล้วล็อกอินด้วย Gmail
2. คลิก **"Add project"** -> ตั้งชื่อโปรเจกต์ เช่น `my-esp32-farm`
3. ในเมนูด้านซ้าย เลือก **Build -> Realtime Database** -> กด **Create Database**
4. เลือกโซน (เช่น Singapore หรือ United States)
5. ในแท็บ **Rules** (กฎความปลอดภัยสำหรับทดสอบ):
   ```json
   {
     "rules": {
       ".read": true,
       ".write": true
     }
   }
   ```
   *(หมายเหตุ: กด Publish เพื่อให้บันทึกและอ่านข้อมูลได้โดยไม่ต้องล็อกอิน)*
6. คัดลอก **Database URL** มาใช้งาน เช่น `https://my-esp32-farm-default-rtdb.asia-southeast1.firebasedatabase.app/`

---

### ขั้นตอนที่ 2.2: โค้ดสำหรับ ESP32 (ใช้ Firebase ESP Client)
ติดตั้งไลบรารี `Firebase ESP32 Client` โดย Mobizt ผ่าน Arduino Library Manager

```cpp
#include <WiFi.h>
#include <FirebaseESP32.h>

#define WIFI_SSID "YOUR_HOTSPOT_NAME"
#define WIFI_PASSWORD "YOUR_HOTSPOT_PASS"

// URL ฐานข้อมูล Firebase ของคุณ (ห้ามมี https:// และเครื่องหมาย / ท้ายสุด)
#define FIREBASE_HOST "my-esp32-farm-default-rtdb.asia-southeast1.firebasedatabase.app"
// ใส่ Web API Key หรือ Database Secret
#define FIREBASE_AUTH "YOUR_DATABASE_SECRET_OR_API_KEY"

const int RELAY_PIN = 2;

FirebaseData fbdo;
FirebaseAuth auth;
FirebaseConfig config;

unsigned long lastSend = 0;

void setup() {
  Serial.begin(115200);
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, LOW);

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected!");

  config.host = FIREBASE_HOST;
  config.signer.tokens.legacy_token = FIREBASE_AUTH;

  Firebase.begin(&config, &auth);
  Firebase.reconnectWiFi(true);
}

void loop() {
  // 1. อ่านสถานะคำสั่งควบคุมสวิตช์จาก Firebase (/controls/relay)
  if (Firebase.getBool(fbdo, "/controls/relay")) {
    bool relayState = fbdo.boolData();
    digitalWrite(RELAY_PIN, relayState ? HIGH : LOW);
  }

  // 2. อัปเดตข้อมูลเซนเซอร์ขึ้น Firebase ทุกๆ 5 วินาที (/sensors)
  if (millis() - lastSend > 5000) {
    lastSend = millis();

    float temp = 29.2;
    float humid = 78.0;

    FirebaseJson json;
    json.set("temperature", temp);
    json.set("humidity", humid);
    json.set("timestamp", millis());

    if (Firebase.setJSON(fbdo, "/sensors/current", json)) {
      Serial.println("อัปเดตข้อมูลขึ้น Firebase สำเร็จ!");
    } else {
      Serial.println("บันทึกข้อมูลไม่สำเร็จ: " + fbdo.errorReason());
    }
  }
}
```

---

### ขั้นตอนที่ 2.3: หน้าแดชบอร์ด HTML เชื่อมต่อ Firebase
บันทึกเป็นไฟล์ `dashboard_firebase.html` เปิดในบราวเซอร์:
```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Firebase Realtime Dashboard</title>
  <!-- โหลด Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
</head>
<body style="font-family:sans-serif; text-align:center; padding:40px; background:#f0fdf4;">
  <h2>🌱 Smart Agriculture Firebase Live Dashboard</h2>
  <div style="background:#fff; width:300px; margin:auto; padding:20px; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
    <h3>อุณหภูมิ: <span id="temp" style="color:#0284c7;">--</span> °C</h3>
    <h3>ความชื้น: <span id="humid" style="color:#16a34a;">--</span> %</h3>
    <hr>
    <h3>ควบคุมปั๊มน้ำ</h3>
    <button onclick="setRelay(true)" style="background:#16a34a; color:#fff; padding:10px 20px; font-size:16px; border:none; border-radius:8px; cursor:pointer;">เปิด (ON)</button>
    <button onclick="setRelay(false)" style="background:#dc2626; color:#fff; padding:10px 20px; font-size:16px; border:none; border-radius:8px; cursor:pointer;">ปิด (OFF)</button>
  </div>

  <script>
    // คอนฟิกจาก Firebase Console ของคุณ
    const firebaseConfig = {
      databaseURL: "https://my-esp32-farm-default-rtdb.asia-southeast1.firebasedatabase.app"
    };
    firebase.initializeApp(firebaseConfig);
    const database = firebase.database();

    // ฟังค่าเซนเซอร์แบบ Real-time
    database.ref('/sensors/current').on('value', (snapshot) => {
      const data = snapshot.val();
      if (data) {
        document.getElementById('temp').innerText = data.temperature;
        document.getElementById('humid').innerText = data.humidity;
      }
    });

    // ฟังก์ชันสั่งงานสวิตช์
    function setRelay(state) {
      database.ref('/controls/relay').set(state);
    }
  </script>
</body>
</html>
```

---

<a name="วิธีที่-3-cloudflare-tunnel-กับ-localhost"></a>
## 4. วิธีที่ 3: การรัน Dashboard บนคอมตัวเอง (XAMPP/Localhost) ร่วมกับ Cloudflare Tunnel

หากท่านมีระบบเว็บเซิร์ฟเวอร์อยู่แล้ว เช่น พัฒนาบน **XAMPP (PHP/MySQL)** หรือ **Python FastAPI/Streamlit** ในคอมพิวเตอร์:
* ปัญหาคือ ESP32 ที่เกาะ Hotspot ไม่สามารถเรียก `http://192.168.1.xxx` ของคอมพิวเตอร์ได้
* **วิธีแก้:** เราจะใช้ **Cloudflare Tunnel (เครื่องมือฟรีระดับโลก)** สร้างอุโมงค์ส่ง URL สาธารณะ (Public HTTPS) ทะลวงกำแพงไฟออกมาให้ ESP32 เรียกใช้งานได้

```
[ ESP32 บน Hotspot ] ─── ยิง HTTP POST ───> [ https://my-farm.trycloudflare.com ]
                                                              │
                                                        (อุโมงค์ทะลุ NAT)
                                                              ▼
                                                   [ คอมพิวเตอร์ของคุณ: localhost:80 ]
```

### ขั้นตอนที่ 3.1: ดาวน์โหลดและเปิดอุโมงค์ Cloudflare Tunnel (ฟรี 100% ไม่ต้องสมัครสมาชิก)
1. **สำหรับ Windows:** ดาวน์โหลดโปรแกรม `cloudflared.exe` จาก [Cloudflare Downloads](https://github.com/cloudflare/cloudflared/releases)
2. **สำหรับ macOS:** ติดตั้งผ่าน Terminal โดยพิมพ์:
   ```bash
   brew install cloudflared
   ```
3. เปิด Command Prompt หรือ Terminal แล้วสั่งรันเชื่อมต่อไปยังพอร์ตเว็บของคุณ (สมมุติว่า XAMPP อยู่ที่ Port 80):
   ```bash
   cloudflared tunnel --url http://localhost:80
   ```
4. คุณจะได้ URL สาธารณะแบบ HTTPS ทันที เช่น:
   ```text
   https://random-words-1234.trycloudflare.com
   ```
   *(ทุกคนบนโลก รวมถึง ESP32 สามารถเข้าถึงเว็บในคอมคุณผ่าน URL นี้ได้ทันที โดยไม่ต้องตั้งค่า Router ใดๆ)*

---

### ขั้นตอนที่ 3.2: สร้างไฟล์ PHP รับข้อมูลใน XAMPP (`api_sensor.php`)
บันทึกไว้ในโฟลเดอร์เว็บ เช่น `htdocs/handysense/api_sensor.php`:
```php
<?php
header("Content-Type: application/json; charset=UTF-8");

// อ่านข้อมูล JSON ที่ ESP32 ส่งมา
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if ($data) {
    // บันทึกลงไฟล์หรือฐานข้อมูล MySQL
    file_put_contents("sensor_data.json", json_encode($data));
    
    // ตอบกลับสถานะ พร้อมส่งคำสั่งกลับไปหา ESP32
    echo json_encode([
        "status" => "success",
        "relay_command" => "ON" // หรือ "OFF" ตามที่ตั้งค่าไว้
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No data"]);
}
?>
```

---

### ขั้นตอนที่ 3.3: โค้ด ESP32 ส่ง HTTP POST เข้าหา Cloudflare Tunnel
```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "YOUR_HOTSPOT_NAME";
const char* password = "YOUR_HOTSPOT_PASS";

// นำ URL ที่ได้จาก Cloudflare Tunnel มาใส่ที่นี่
const char* serverUrl = "https://random-words-1234.trycloudflare.com/handysense/api_sensor.php";

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");

    // เตรียมข้อมูลเซนเซอร์
    StaticJsonDocument<200> doc;
    doc["temperature"] = 30.5;
    doc["humidity"] = 65.2;
    String requestBody;
    serializeJson(doc, requestBody);

    // ส่ง POST Request
    int httpResponseCode = http.POST(requestBody);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Server Response: " + response);

      // แกะคำสั่งควบคุมกลับมาจาก Server
      StaticJsonDocument<200> resDoc;
      deserializeJson(resDoc, response);
      const char* cmd = resDoc["relay_command"];
      Serial.printf("Command received: %s\n", cmd);
    } else {
      Serial.printf("Error occurred: %d\n", httpResponseCode);
    }
    http.end();
  }

  delay(5000); // ส่งทุก 5 วินาที
}
```

---

<a name="ตารางเปรียบเทียบและข้อเสนอแนะ"></a>
## 5. ตารางเปรียบเทียบข้อดี-ข้อจำกัด และคำแนะนำการเลือกใช้งาน

| คุณสมบัติ | วิธีที่ 1: MQTT Cloud Broker | วิธีที่ 2: Firebase Realtime DB | วิธีที่ 3: Localhost + Tunnel |
| :--- | :--- | :--- | :--- |
| **ความเร็วตอบสนอง (Latency)** | ⚡ เร็วมาก (< 50-100 ms) | ⚡ เร็วดี (< 200 ms) | ⏳ ปานกลาง (ตามรอบ HTTP Polling) |
| **การใช้ Data เน็ตมือถือ** | 🟢 ประหยัดที่สุด (หัว Header เล็กมาก) | 🟡 ปานกลาง | 🔴 เปลืองที่สุด (HTTP Headers ใหญ่) |
| **ความยากง่ายในการเริ่ม** | 🟡 ปานกลาง (ต้องเข้าใจ Pub/Sub) | 🟢 ง่ายมาก (มี SDK สำเร็จรูป) | 🟢 ง่ายที่สุดถ้ามีเว็บเดิมอยู่แล้ว |
| **การเก็บบันทึกประวัติย้อนหลัง** | ต้องต่อฐานข้อมูลเพิ่มเอง | 🟢 มีในตัว (บันทึกเป็น JSON Tree) | 🟢 บันทึกลง MySQL/SQLite ได้ทันที |
| **ความเหมาะสม** | **ระบบสมาร์ทฟาร์มจริง, สั่งปั๊มน้ำทันที** | **แอปมือถือ, มอนิเตอร์กราฟย้อนหลัง** | **ทดสอบชั่วคราว, งานวิจัยในคอมตัวเอง** |

---

<a name="ปัญหาที่พบบ่อยและการแก้ไข"></a>
## 6. ปัญหาที่พบบ่อยและการแก้ไข (Troubleshooting & FAQs)

### Q1: ทำไมต่อ Wi-Fi Hotspot มือถือแล้ว ESP32 มองไม่เห็นสัญญาณ หรือต่อไม่ติด?
* **สาเหตุ:** สมาร์ทโฟนรุ่นใหม่ส่วนใหญ่ปล่อย Hotspot ย่าน **5 GHz** เป็นค่าเริ่มต้น แต่ชิป ESP32 ส่วนใหญ่รองรับเฉพาะย่าน **2.4 GHz** เท่านั้น
* **วิธีแก้:** เข้าไปที่การตั้งค่า Hotspot ในโทรศัพท์มือถือ แล้วเปิดตัวเลือก **"Maximize Compatibility"** (iPhone) หรือเปลี่ยน AP Band เป็น **"2.4 GHz Band"** (Android)

### Q2: Wi-Fi มหาวิทยาลัยบล็อกพอร์ต MQTT พอร์ต 1883 ใช่หรือไม่?
* **สาเหตุ:** ใช่ เครือข่ายสถาบันส่วนใหญ่มักจะบล็อกพอร์ตแปลกๆ
* **วิธีแก้:** ให้ใช้ **Secure MQTT (พอร์ต 8883)** หรือผ่าน **WebSockets (พอร์ต 8884 หรือ 443)** ซึ่งเป็นพอร์ต HTTPS ปกติ มหาวิทยาลัยจะไม่บล็อก

### Q3: สัญญาณ Hotspot หลุดบ่อย หรือเดินห่างแล้วระบบค้าง
* **วิธีแก้:** ในโค้ด ESP32 ต้องใส่ระบบตรวจสอบการเชื่อมต่อซ้ำ (Reconnection Loop) เสมอ เช่น:
  ```cpp
  if (WiFi.status() != WL_CONNECTED) {
      WiFi.reconnect();
  }
  ```

### Q4: เจอหน้าจอ "How to deploy your broker / Requires Docker" หรือรันแล้วติด "ERROR! HiveMQ requires at least Java version 21" ต้องแก้อย่างไร?
* **สาเหตุ:** คุณกำลังอยู่ในหน้า **"Self-managed broker"** ซึ่งเป็นการดาวน์โหลด HiveMQ มารันเองในคอมพิวเตอร์ ซึ่งจะติดปัญหาเรื่องต้องมี Docker หรือ Java 21 และที่สำคัญคือ *ยังเป็น Localhost ที่ ESP32 ข้ามเน็ตมาหาไม่ได้*
* **วิธีแก้:** ไม่ต้องติดตั้ง Docker หรือ Java ในเครื่อง ให้เปิดเว็บไปที่ **[https://console.hivemq.cloud/](https://console.hivemq.cloud/)** แล้วเลือกแพ็กเกจ **"Serverless" (Free)** จะได้เซิร์ฟเวอร์บนอินเทอร์เน็ตจริงทันทีโดยไม่ต้องรันโปรแกรมใดๆ ในคอมพิวเตอร์

### Q5: ทำไม ESP32 หรือแดชบอร์ดขึ้นสถานะว่า "Connection Refused" หรือเชื่อมต่อ MQTT ไม่ติด?
* **สาเหตุ:** ยังไม่ได้สร้างบัญชีผู้ใช้ในหน้าคลาวด์ หรือระบุ Username/Password ไม่ถูกต้อง
* **วิธีแก้:** ในหน้า HiveMQ Cloud Console ให้เข้าไปที่แท็บ **"Access Management"** -> กด **"Add Credentials"** -> สร้าง Username และ Password ใหม่ แล้วนำค่านั้นไปใส่ในโค้ดให้ตรงกันทุกตัวอักษร

---
*จัดทำขึ้นสำหรับโครงการพัฒนาระบบ AIoT & Smart Farm โดย สาขาวิชาฟิสิกส์ และ สาขาวิชาจุลชีววิทยา มหาวิทยาลัยราชภัฏรำไพพรรณี*
