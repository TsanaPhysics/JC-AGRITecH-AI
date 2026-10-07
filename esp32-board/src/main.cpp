#include <Arduino.h>
#include <freertos/FreeRTOS.h>
#include <freertos/task.h>
#include <freertos/semphr.h>
#include "PinConfigs.h"
#include "UserConfigs.h"
#include "AgriSensors.h"
#include "DisplayManager.h"
#include "CloudDataManager.h"
#include "SDCardManager.h"
#include "WiFiConfigManager.h"

/**
 * ============================================================================
 * สถาปัตยกรรม Multi-Task (ปรับปรุงเพื่อให้ทัชสกรีนตอบสนองแบบเรียลไทม์)
 * ----------------------------------------------------------------------------
 *  Core 1 | loop()      : UI Task  - ประมวลผลคิวทัช + วาดหน้าจอ + บันทึก SD (ไม่มีงานบล็อกเครือข่าย)
 *  Core 1 | touchPoll   : อ่านทัช FT6336U ทุก 8 ms (ใน DisplayManager) -> ไม่พลาดการแตะแม้กำลังวาดจอ
 *  Core 0 | netTask     : WebServer (/relay /mode), Captive Portal, Serial CLI - ตอบสนองคำสั่งรีเลย์ทันที
 *  Core 0 | sensorTask  : อ่านเซนเซอร์ + ควบคุมอัตโนมัติ + ส่ง HTTP Telemetry (บล็อกได้โดยไม่กระทบ UI)
 * ============================================================================
 */

// ตัวแปรจับเวลาในแต่ละ Task
static unsigned long lastSensorReadTime = 0;
static unsigned long lastDashboardPrintTime = 0;

// สถานะการทำงานของอุปกรณ์ควบคุมในแปลง (อ่าน/เขียนข้าม Task ได้ เพราะเป็น bool 1 ไบต์)
bool isPumpActive = false;
bool isMistingActive = false;

// คำขอเปิด/ปิด Captive Portal จากหน้าจอสัมผัส (UI -> netTask) 0=ไม่มี 1=เปิด 2=ปิด
volatile int g_portalRequest = 0;

// Telemetry ที่แชร์ระหว่าง sensorTask (ผู้เขียน) และ UI Task (ผู้อ่าน)
static SemaphoreHandle_t telemetryMutex = nullptr;
static FarmSensorTelemetry sharedTelemetry;
static volatile uint32_t sharedTelemetrySeq = 0;
static FarmSensorTelemetry uiTelemetry;
static uint32_t uiTelemetrySeq = 0;

// ประกาศฟังก์ชันล่วงหน้า (Forward Declaration)
void executeAgronomyControl(const FarmSensorTelemetry &data);
static void netTask(void *arg);
static void sensorTask(void *arg);

static void publishTelemetry(const FarmSensorTelemetry &t) {
    if (telemetryMutex && xSemaphoreTake(telemetryMutex, pdMS_TO_TICKS(20)) == pdTRUE) {
        sharedTelemetry = t;
        sharedTelemetrySeq = sharedTelemetrySeq + 1;
        xSemaphoreGive(telemetryMutex);
    }
}

void setup() {
    // 1. เริ่มต้น Serial Console เพื่อดูข้อมูลทดสอบ
    Serial.begin(115200);
    delay(200);

    Serial.println("\n\n=======================================================");
    Serial.println("  AGRICULTURAL IOT CONTROLLER FOR ATD3.5-S3 (GRAVITY)  ");
    Serial.println("  Smart Farm Firmware with SHT45, Light, Soil & NPK/pH ");
    Serial.println("=======================================================");

    // 2. เริ่มต้นหน้าจอแสดงผล 3.5 นิ้ว TFT ทันที (แสดง Splash Screen + 10%)
    DisplayManager_init();

    // 3. กำหนดโหมดขา Relay (25%)
    DisplayManager_showBootProgress("ตรวจสอบและตั้งค่ารีเลย์ควบคุม...", 25);
    pinMode(RELAY_1_PIN, OUTPUT);
    pinMode(RELAY_2_PIN, OUTPUT);
    pinMode(RELAY_3_PIN, OUTPUT);
    pinMode(RELAY_4_PIN, OUTPUT);
    digitalWrite(RELAY_1_PIN, LOW); // ปิดปั๊มน้ำ
    digitalWrite(RELAY_2_PIN, LOW);
    digitalWrite(RELAY_3_PIN, LOW);
    digitalWrite(RELAY_4_PIN, LOW);

    // 4. เริ่มต้นระบบเซนเซอร์ทั้งหมด (45%)
    DisplayManager_showBootProgress("เชื่อมต่อ I2C SHT45, โดมตะวัน และ Modbus 7-in-1...", 45);
    AgriSensors_init();

    // 5. เริ่มต้นระบบ Micro-SD Card Logging บันทึกข้อมูลลงการ์ดในตัวบอร์ด (65%)
    DisplayManager_showBootProgress("ตรวจสอบ Micro-SD Card และพาร์ติชัน NVS...", 65);
    SDCardManager_init();

    // 6. เริ่มต้นระบบเชื่อมต่อ Wi-Fi และส่งข้อมูล Cloud Data Logger (85%)
    DisplayManager_showBootProgress("ค้นหาและเชื่อมต่อเครือข่าย Wi-Fi...", 85);
    CloudDataManager_init();

    // 7. อ่านค่าข้อมูลเซนเซอร์รอบแรกทันที (95%)
    DisplayManager_showBootProgress("อ่านข้อมูลโทรมาตรและประเมินผล TinyML AI...", 95);
    AgriSensors_update();

    // 8. สลับเข้าสู่หน้าหลักทันทีพร้อมข้อมูลเซนเซอร์รอบแรก (100% ไร้จอดำ)
    const FarmSensorTelemetry &initTelemetry = AgriSensors_getTelemetry();
    executeAgronomyControl(initTelemetry);
    DisplayManager_finishBoot(initTelemetry, isPumpActive, isMistingActive);

    // 9. เตรียมข้อมูลตั้งต้นให้ UI แล้วแยกงานหนักไปทำงานบน Core 0
    telemetryMutex = xSemaphoreCreateMutex();
    uiTelemetry = initTelemetry;
    publishTelemetry(initTelemetry);
    uiTelemetrySeq = sharedTelemetrySeq;
    lastSensorReadTime = millis();

    xTaskCreatePinnedToCore(netTask,    "netTask",    8192,  nullptr, 2, nullptr, 0);
    xTaskCreatePinnedToCore(sensorTask, "sensorTask", 16384, nullptr, 1, nullptr, 0);

    Serial.println("[System] System setup completed successfully. Starting multi-task telemetry loop...\n");
}


/**
 * ============================================================================
 * Smart Agriculture Automation Logic (กฎการควบคุมอัตโนมัติอัจฉริยะ)
 * ============================================================================
 */
void executeAgronomyControl(const FarmSensorTelemetry &data) {
    // หากระบบไม่ได้อยู่ในโหมด AUTO (เช่น อยู่ในโหมด MANUAL ที่สั่งจาก Dashboard หรือหน้าจอ)
    // ให้ระงับการทำงานอัตโนมัติ เพื่อไม่ให้ไปเขียนทับคำสั่งของผู้ใช้
    if (CloudDataManager_getControlMode() != "auto") {
        return;
    }

    // กฎที่ 1: ระบบรดน้ำอัจฉริยะตามความชื้นในดิน (Soil Moisture Hysteresis Control)
    // ใช้ค่าเฉลี่ยความชื้นระหว่าง Soil Stick และ Soil 7-in-1 หรือเลือกตัวใดตัวหนึ่ง
    float currentSoilMoisture = data.soilStick.moisture;
    if (data.soil7in1.isConnected) {
        // หากเซนเซอร์ 7-in-1 เชื่อมต่ออยู่ ให้นำมาคิดถ่วงน้ำหนักร่วมกันเพื่อความแม่นยำสูงสุด
        currentSoilMoisture = (data.soilStick.moisture * 0.5f) + (data.soil7in1.moisture * 0.5f);
    }

    if (currentSoilMoisture < 40.0f && !isPumpActive) {
        // ดินแห้งเกินไป (ต่ำกว่า 40%) -> สั่งเปิดปั๊มน้ำรดน้ำ
        digitalWrite(RELAY_1_PIN, HIGH);
        isPumpActive = true;
        Serial.println("\n>>> [AUTO-ACTION] ดินแห้ง (Moisture < 40%) -> สั่งเปิดปั๊มน้ำ (Relay 1 ON)");
    } else if (currentSoilMoisture >= 65.0f && isPumpActive) {
        // ดินชุ่มชื้นเพียงพอแล้ว (แตะ 65%) -> สั่งปิดปั๊มน้ำ
        digitalWrite(RELAY_1_PIN, LOW);
        isPumpActive = false;
        Serial.println("\n>>> [AUTO-ACTION] ดินชุ่มชื้นพอเหมาะ (Moisture >= 65%) -> สั่งปิดปั๊มน้ำ (Relay 1 OFF)");
    }

    // กฎที่ 2: ระบบลดความร้อนโรงเรือนด้วยการพ่นหมอก (Evaporative Cooling)
    // สั่งเปิดพ่นหมอกเมื่ออุณหภูมิอากาศ > 35.0 °C และความชื้นสัมพัทธ์ < 70%
    if (data.air.isConnected) {
        if (data.air.temperature > 35.0f && data.air.humidity < 70.0f && !isMistingActive) {
            digitalWrite(RELAY_4_PIN, HIGH);
            isMistingActive = true;
            Serial.println(">>> [AUTO-ACTION] อากาศร้อนจัด (Temp > 35°C) -> สั่งเปิดระบบพ่นหมอก (Relay 4 ON)");
        } else if ((data.air.temperature <= 32.0f || data.air.humidity >= 85.0f) && isMistingActive) {
            digitalWrite(RELAY_4_PIN, LOW);
            isMistingActive = false;
            Serial.println(">>> [AUTO-ACTION] อุณหภูมิลดลงปกติ -> สั่งปิดระบบพ่นหมอก (Relay 4 OFF)");
        }
    }

    // กฎที่ 3: ตรวจสอบและบริหารจัดการค่าความเป็นกรด-ด่างของดิน (Soil pH Management & Liming Rule)
    // สำหรับไม้ผลเศรษฐกิจภาคตะวันออก (เช่น ทุเรียน มังคุด) ช่วง pH ที่เหมาะสมคือ 5.5 - 6.5
    float effectivePh = (data.soilStick.isPhConnected && data.soilStick.ph > 3.0f) ? 
                        data.soilStick.ph : data.aiCalibrated.ph;

    // ตรวจสอบความสอดคล้องระหว่างผิวดินชั้นตื้น (0-10 ซม.) กับเขตรากลึก (15-30 ซม.)
    if (data.soilStick.isPhConnected && data.soil7in1.isConnected) {
        float phDiff = fabs(data.soilStick.ph - data.soil7in1.ph);
        if (phDiff > 1.2f) {
            Serial.printf("[AGRONOMY WARNING] พบความชัน pH ข้ามชั้นดิน (ผิวดิน A2: %.2f vs รากลึก: %.2f, Diff: %.2f) ส่อการตกค้างของปุ๋ยเคมีผิวดิน\n",
                          data.soilStick.ph, data.soil7in1.ph, phDiff);
        }
    }

    if (effectivePh < 5.0f && effectivePh > 3.0f) {
        Serial.printf("[AGRONOMY ALERT] ดินมีความเป็นกรดรุนแรง (pH = %.2f < 5.0) ฟอสฟอรัสถูกตรึง เสี่ยงรากเน่า แนะนำปรับปรุงด้วยโดโลไมต์/ปูนขาว\n", effectivePh);
    } else if (effectivePh > 7.5f) {
        Serial.printf("[AGRONOMY ALERT] ดินมีความเป็นด่างจัด (pH = %.2f > 7.5) พืชเสี่ยงต่อการขาดธาตุเหล็ก สังกะสี และแมงกานีส\n", effectivePh);
    }
}

// ============================================================================
// netTask (Core 0): บริการเครือข่ายภายในบอร์ด ตอบสนองเร็วโดยไม่รอการอ่านเซนเซอร์
// ============================================================================
static void netTask(void *arg) {
    for (;;) {
        // คำขอจากหน้าจอสัมผัส: เปิด/ปิด SoftAP Captive Portal (ทำใน Task นี้เพื่อความปลอดภัยของ WebServer)
        int req = g_portalRequest;
        if (req != 0) {
            g_portalRequest = 0;
            if (req == 1) WiFiConfigManager_startPortal();
            else if (req == 2) WiFiConfigManager_stopPortal();
        }

        // ประมวลผล SoftAP Captive Portal DNS & WebServer (พอร์ต 80 / 8500: /relay /mode)
        WiFiConfigManager_loop();

        // ซิงค์เวลา/คำสั่ง CLI ผ่าน USB Serial
        CloudDataManager_checkSerialTimeSync();

        vTaskDelay(pdMS_TO_TICKS(2));
    }
}

// ============================================================================
// sensorTask (Core 0): อ่านเซนเซอร์ -> ส่งต่อให้ UI ทันที -> ควบคุมอัตโนมัติ -> อัปโหลด Cloud
// ============================================================================
static void sensorTask(void *arg) {
    for (;;) {
        unsigned long currentMillis = millis();

        // 1. อ่านค่าจากเซนเซอร์ทุกตัวตามรอบเวลา (ทุกๆ 2 วินาที)
        if (currentMillis - lastSensorReadTime >= SENSOR_READ_INTERVAL_MS) {
            lastSensorReadTime = currentMillis;

            AgriSensors_update();

            // สำเนาข้อมูลเพื่อความปลอดภัยข้าม Task แล้วแจ้ง UI ให้วาดทันที (ไม่รอการอัปโหลด Cloud)
            FarmSensorTelemetry snapshot = AgriSensors_getTelemetry();
            publishTelemetry(snapshot);

            executeAgronomyControl(snapshot);

            // ส่งข้อมูลขึ้น Cloud Telemetry Hub และ Local Web Server (อาจบล็อกได้หลายร้อย ms)
            CloudDataManager_update(snapshot, isPumpActive, isMistingActive);
        }

        // 2. แสดงผล Dashboard และสถานะออกทาง Serial ทุกๆ 3 วินาที
        if (currentMillis - lastDashboardPrintTime >= 3000) {
            lastDashboardPrintTime = currentMillis;
            AgriSensors_printDashboard();
        }

        vTaskDelay(pdMS_TO_TICKS(10));
    }
}

// ============================================================================
// loop() = UI Task (Core 1): ทัช + วาดจอ เท่านั้น
// ============================================================================
void loop() {
    // 0. ประมวลผลเหตุการณ์ทัชที่ touchPoll เก็บไว้ในคิว (สลับหน้า/สลับรีเลย์ทันที)
    DisplayManager_handleTouch(isPumpActive, isMistingActive);

    // 1. ตรวจสถานะภายนอกที่กระทบหน้าจอ (รีเลย์ที่ถูกสั่งจากเว็บ, Wi-Fi/Portal เปลี่ยนสถานะ)
    bool externalChanged = DisplayManager_syncExternalState();

    // 2. รับ Telemetry ล่าสุดจาก sensorTask (ถ้ามี)
    bool fresh = false;
    if (sharedTelemetrySeq != uiTelemetrySeq && telemetryMutex &&
        xSemaphoreTake(telemetryMutex, pdMS_TO_TICKS(5)) == pdTRUE) {
        uiTelemetry = sharedTelemetry;
        uiTelemetrySeq = sharedTelemetrySeq;
        xSemaphoreGive(telemetryMutex);
        fresh = true;
    }

    unsigned long nowMs = millis();

    // บันทึกจุดกราฟทุก 2 วินาที (คงสเกลเวลาของกราฟ 60 จุดเดิม แม้อ่านเซนเซอร์ทุก 1 วินาที)
    static unsigned long lastRecordMs = 0;
    if (fresh && (nowMs - lastRecordMs >= 2000)) {
        lastRecordMs = nowMs;
        DisplayManager_recordSample(uiTelemetry);
    }

    // 3. วาดหน้าจอเฉพาะเมื่อมีเหตุให้เปลี่ยน (ข้อมูลใหม่ / เปลี่ยนหน้า / เลื่อนสกรอลล์ / สถานะรีเลย์เปลี่ยน)
    //    หน้ากราฟและหน้ารายละเอียดวาดทับทั้งพื้นที่ จึงจำกัดรอบรีเฟรชข้อมูลที่ ~2 วินาที เพื่อไม่ให้จอกะพริบ
    DisplayPage curPage = DisplayManager_getPage();
    bool heavyPage = (curPage == PAGE_GRAPHS) || (curPage >= PAGE_DETAIL_AIR);
    static unsigned long lastHeavyDrawMs = 0;
    bool dataRedraw = fresh && (!heavyPage || (nowMs - lastHeavyDrawMs >= 1900));

    if (dataRedraw || externalChanged || DisplayManager_hasPageChanged()) {
        lastHeavyDrawMs = nowMs;
        DisplayManager_update(uiTelemetry, isPumpActive, isMistingActive);
    }

    // 4. บันทึกลง Micro-SD ทุก 2 วินาทีหลังวาดจอเสร็จ (SD ใช้บัส SPI เดียวกับจอ จึงต้องทำใน UI Task เท่านั้น)
    static unsigned long lastSdLogMs = 0;
    if (fresh && (millis() - lastSdLogMs >= 2000)) {
        lastSdLogMs = millis();
        SDCardManager_log(uiTelemetry, isPumpActive, isMistingActive);
    }

    delay(1);
}
