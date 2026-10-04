#include "SDCardManager.h"
#include "CloudDataManager.h"
#include "PinConfigs.h"
#include <SPI.h>
#include <SD.h>
#include <FS.h>
#include <WiFi.h>

static bool isMounted = false;
static int activeCsPin = -1;
static uint32_t recordCount = 0;
static uint64_t cardSizeMB = 0;

static const char *CSV_FILENAME = "/telemetry_data.csv";
static const char *CSV_HEADER = "timestamp,datetime,air_temp_c,humidity_pct,dew_point_c,vpd_kpa,light_lux,solar_rad_wm2,soil_stick_adc,soil_stick_moist_pct,soil_stick_ph,soil_7in1_moist_pct,soil_7in1_temp_c,soil_7in1_ec_uscm,soil_7in1_ph,n_mgkg,p_mgkg,k_mgkg,ai_n_mgkg,ai_p_mgkg,ai_k_mgkg,ai_ph,ai_moist_pct,ai_confidence,pump_state,misting_state,ip_address\n";

void SDCardManager_init() {
    Serial.println("\n[SDCard] Initializing Micro-SD Card Subsystem...");

    // รายการพิน CS ที่มักใช้บนบอร์ดตระกูล ESP32-S3 ATD3.5
    const int candidateCs[] = { 4, 5, 42, 47 };
    const int numCandidates = sizeof(candidateCs) / sizeof(candidateCs[0]);

    for (int i = 0; i < numCandidates; i++) {
        int cs = candidateCs[i];
        Serial.printf("[SDCard] Probing SD Card on SPI (SCK:12, MOSI:11, MISO:13, CS:%d)...\n", cs);
        pinMode(cs, OUTPUT);
        digitalWrite(cs, HIGH);

        if (SD.begin(cs, SPI, 20000000)) {
            isMounted = true;
            activeCsPin = cs;
            cardSizeMB = SD.cardSize() / (1024 * 1024);
            uint8_t cardType = SD.cardType();
            const char *typeStr = (cardType == CARD_MMC) ? "MMC" :
                                  (cardType == CARD_SD) ? "SDSC" :
                                  (cardType == CARD_SDHC) ? "SDHC/SDXC" : "UNKNOWN";

            Serial.printf(">>> [SDCard] Micro-SD MOUNTED SUCCESSFULLY!\n");
            Serial.printf("    CS Pin    : GPIO %d\n", activeCsPin);
            Serial.printf("    Card Type : %s\n", typeStr);
            Serial.printf("    Capacity  : %llu MB\n", cardSizeMB);
            break;
        }
        delay(50);
    }

    if (!isMounted) {
        Serial.println("[SDCard] [!] No Micro-SD Card detected on candidate CS pins (4, 5, 42, 47).");
        Serial.println("[SDCard] [i] System is hot-plug ready: insert Micro-SD anytime.");
        return;
    }

    // ตรวจสอบหรือสร้างไฟล์ CSV พร้อม Header
    if (!SD.exists(CSV_FILENAME)) {
        File file = SD.open(CSV_FILENAME, FILE_WRITE);
        if (file) {
            file.print(CSV_HEADER);
            file.flush();
            file.close();
            Serial.printf("[SDCard] Created fresh telemetry log file: %s [OK]\n", CSV_FILENAME);
        } else {
            Serial.printf("[SDCard] [!] Failed to create %s on SD Card\n", CSV_FILENAME);
        }
    } else {
        // นับจำนวนบรรทัดเดิมที่มีอยู่ในไฟล์
        File file = SD.open(CSV_FILENAME, FILE_READ);
        if (file) {
            uint32_t lines = 0;
            while (file.available()) {
                if (file.read() == '\n') lines++;
            }
            file.close();
            if (lines > 0) recordCount = lines - 1; // ลบ header ออก 1 บรรทัด
            Serial.printf("[SDCard] Found existing %s with %u records logged\n", CSV_FILENAME, recordCount);
        }
    }
}

bool SDCardManager_isMounted() {
    return isMounted;
}

int SDCardManager_getCsPin() {
    return activeCsPin;
}

uint32_t SDCardManager_getRecordCount() {
    return recordCount;
}

uint64_t SDCardManager_getCardSizeMB() {
    return cardSizeMB;
}

bool SDCardManager_log(const FarmSensorTelemetry &telemetry, bool pumpState, bool mistingState) {
    if (!isMounted) {
        // ลอง Auto-mount เผื่อเพิ่งเสียบการ์ด
        static unsigned long lastRetry = 0;
        if (millis() - lastRetry > 30000) {
            lastRetry = millis();
            SDCardManager_init();
        }
        if (!isMounted) return false;
    }

    File file = SD.open(CSV_FILENAME, FILE_APPEND);
    if (!file) {
        Serial.println("[SDCard] [!] Failed to open file for appending");
        return false;
    }

    unsigned long epoch = CloudDataManager_getEpochTime();
    String dt = CloudDataManager_getFormattedTime();
    String ip = WiFi.status() == WL_CONNECTED ? WiFi.localIP().toString() : "0.0.0.0";

    // สร้างบรรทัด CSV
    char buf[512];
    snprintf(buf, sizeof(buf),
        "%lu,\"%s\",%.2f,%.2f,%.2f,%.2f,%.1f,%.2f,%d,%.1f,%.2f,%.1f,%.1f,%.0f,%.2f,%.1f,%.1f,%.1f,%.1f,%.1f,%.1f,%.2f,%.1f,%.3f,%d,%d,\"%s\"\n",
        epoch,
        dt.c_str(),
        telemetry.air.temperature,
        telemetry.air.humidity,
        telemetry.air.dewPoint,
        telemetry.air.vpd,
        telemetry.light.lux,
        telemetry.light.solarRadiation,
        telemetry.soilStick.rawAdc,
        telemetry.soilStick.moisture,
        telemetry.soilStick.ph,
        telemetry.soil7in1.moisture,
        telemetry.soil7in1.temperature,
        telemetry.soil7in1.ec,
        telemetry.soil7in1.ph,
        telemetry.soil7in1.nitrogen,
        telemetry.soil7in1.phosphorus,
        telemetry.soil7in1.potassium,
        telemetry.aiCalibrated.nitrogen,
        telemetry.aiCalibrated.phosphorus,
        telemetry.aiCalibrated.potassium,
        telemetry.aiCalibrated.ph,
        telemetry.aiCalibrated.moisture,
        telemetry.aiCalibrated.confidence,
        pumpState ? 1 : 0,
        mistingState ? 1 : 0,
        ip.c_str()
    );

    file.print(buf);
    file.flush();
    file.close();

    recordCount++;
    Serial.printf("[SDCard] >>> Appended record #%u to %s [OK]\n", recordCount, CSV_FILENAME);
    return true;
}
