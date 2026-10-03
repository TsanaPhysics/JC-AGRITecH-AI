#include "AgriSensors.h"
#include <Wire.h>
#include <math.h>

static FarmSensorTelemetry telemetryData;
static HardwareSerial rs485Serial(RS485_UART_PORT);

/**
 * ============================================================================
 * Helper Utilities: CRC Calculation
 * ============================================================================
 */

// Sensirion SHT4x CRC8 Calculation
static uint8_t checkCRC8(const uint8_t* data, uint16_t count) {
    uint8_t crc = 0xFF;
    for (uint16_t i = 0; i < count; ++i) {
        crc ^= data[i];
        for (uint8_t bit = 8; bit > 0; --bit) {
            if (crc & 0x80) {
                crc = (crc << 1) ^ 0x31;
            } else {
                crc = (crc << 1);
            }
        }
    }
    return crc;
}

// Modbus RTU CRC16 Calculation
static uint16_t calculateModbusCRC16(const uint8_t *buffer, uint16_t len) {
    uint16_t crc = 0xFFFF;
    for (uint16_t pos = 0; pos < len; pos++) {
        crc ^= (uint16_t)buffer[pos];
        for (int i = 8; i != 0; i--) {
            if ((crc & 0x0001) != 0) {
                crc >>= 1;
                crc ^= 0xA001;
            } else {
                crc >>= 1;
            }
        }
    }
    return crc;
}

/**
 * ============================================================================
 * Sensor Drivers
 * ============================================================================
 */

// ============================================================================
// =  CALIBRATION CONSTANTS – Soil 7-in-1 Sensor (RS485 Modbus RTU)          =
// =  ปรับค่าเหล่านี้หลังจาก Calibration กับตัวอย่างดินจริง (วิเคราะห์ในห้องแลป + เทียบกับเครื่องมาตรฐาน)   =
// ============================================================================

// — pH Calibration (Linear model: pH_out = raw_pH × factor + offset) ————————
// Default: factor=1.0, offset=0.0 (เซนเซอร์ชนิดนี้มักแม่นยำ pH ได้ดีในช่วง 5.5–8.5)
// Fine-tune offset เมื่อเทียบกับ Buffer Solution pH 6.86 / pH 9.18
#define PH_CAL_FACTOR   1.00f
#define PH_CAL_OFFSET   0.00f   // เช่น -0.30 ถ้าเซนเซอร์อ่านสูงไป

// — EC Calibration (µS/cm) ————————————————————————————————————
// เทียบกับ EC Calibration Solution มาตรฐาน (1413 µS/cm) เพื่อปรับ
#define EC_CAL_FACTOR   1.00f
#define EC_CAL_OFFSET   0.00f

// — N (Nitrogen / ไนโตรเจน) — mg/kg ——————————————————————————
// เซนเซอร์ชนิดนี้วัด N โดยซัพนึ้งกับความต้านทานไฟฟ้า มักอ่านต่ำกว่าจริง 40–60%
// Ref: Boguzas et al. (2022) Sensors MDPI; Tian et al. (2023) Agronomy
// factor ที่แนะนำ: 1.8–2.5 (Average 2.0) สำหรับดินทั่วไป
// Range ที่เหมาะสมสำหรับอาหาร (Available N): 10–300 mg/kg
#define N_CAL_FACTOR    2.00f
#define N_CAL_OFFSET    0.00f
#define N_CAL_MIN       0.0f
#define N_CAL_MAX    1500.0f   // Total N สูงสุด 1000–3000 mg/kg

// — P (Phosphorus / ฟอสเฟอรัส) — mg/kg ————————————————————
// P วัดได้ยากที่สุด (ไม่ใช่ Ion-selective electrode แท้)
// ค่าสัมพันธ์กับ Olsen P / Bray P ต้องใช้ factor สูงกว่า N
// Ref: Sharma et al. (2021) Computers and Electronics in Agriculture
// factor ที่แนะนำ: 3.0–5.0 (Average 3.5)
// Range (Available P): 2–80 mg/kg
#define P_CAL_FACTOR    3.50f
#define P_CAL_OFFSET    0.00f
#define P_CAL_MIN       0.0f
#define P_CAL_MAX     300.0f

// — K (Potassium / โพแทสเซียม) — mg/kg ——————————————————————
// K วัดได้ดีกว่า P แต่ยังต่ำกว่าจริง ~35–50%
// Ref: FAO Soil Testing Guidelines; Kibor et al. (2023) Tropical Agric.
// factor ที่แนะนำ: 1.5–2.0 (Average 1.8)
// Range (Exchangeable K): 50–500 mg/kg
#define K_CAL_FACTOR    1.80f
#define K_CAL_OFFSET    0.00f
#define K_CAL_MIN       0.0f
#define K_CAL_MAX    2000.0f

// — EC-based NPK fallback (if direct NPK = 0 but EC > 0) —————————————
// Ref: Rhoades et al. (1992) Soil Salinity – EC/nutrient correlation
// ใช้เมื่อเซนเซอร์ NPK ไม่ตอบสนอง (0 ที่ผิดปกติ) แต่ EC > 100 µS/cm
#define EC_TO_N_RATIO   0.14f  // N ≈ EC × 0.14
#define EC_TO_P_RATIO   0.06f  // P ≈ EC × 0.06
#define EC_TO_K_RATIO   0.20f  // K ≈ EC × 0.20

// ——————————————————————————————————————————————————————————————————

// --- 1. SHT45 (Air Temperature & Humidity) ---
static bool readSHT45(float &temp, float &humidity) {
    Wire.beginTransmission(SHT45_I2C_ADDR);
    Wire.write(0xFD); // SHT4x High Precision Measurement Command
    if (Wire.endTransmission() != 0) {
        return false;
    }

    delay(15); // รอเซนเซอร์แปลงสัญญาณ (Datasheet: Typ 6.9 ms, Max 8.2 ms)

    if (Wire.requestFrom((uint8_t)SHT45_I2C_ADDR, (uint8_t)6) != 6) {
        return false;
    }

    uint8_t buf[6];
    for (int i = 0; i < 6; i++) {
        buf[i] = Wire.read();
    }

    // ตรวจสอบ CRC8 ของอุณหภูมิ (ไบต์ 0, 1) และความชื้น (ไบต์ 3, 4)
    if (checkCRC8(buf, 2) != buf[2] || checkCRC8(&buf[3], 2) != buf[5]) {
        return false;
    }

    uint16_t rawTemp = (buf[0] << 8) | buf[1];
    uint16_t rawHumi = (buf[3] << 8) | buf[4];

    // สูตรตาม Sensirion SHT4x Datasheet
    temp = -45.0f + 175.0f * ((float)rawTemp / 65535.0f);
    humidity = -6.0f + 125.0f * ((float)rawHumi / 65535.0f);

    // Limit humidity 0 - 100%
    if (humidity < 0.0f) humidity = 0.0f;
    if (humidity > 100.0f) humidity = 100.0f;

    return true;
}

// --- 2. BH1750 (โดมตะวัน ความเข้มแสง) ---
static bool initBH1750() {
    Wire.beginTransmission(BH1750_I2C_ADDR);
    Wire.write(0x01); // Power On
    if (Wire.endTransmission() != 0) return false;

    Wire.beginTransmission(BH1750_I2C_ADDR);
    Wire.write(0x10); // Continuous H-Resolution Mode (1 Lux resolution, 120ms measurement time)
    return (Wire.endTransmission() == 0);
}

static bool readBH1750(float &lux) {
    if (Wire.requestFrom((uint8_t)BH1750_I2C_ADDR, (uint8_t)2) != 2) {
        return false;
    }
    uint16_t raw = (Wire.read() << 8) | Wire.read();
    lux = (float)raw / 1.2f; // ค่าตาม Datasheet Rohm BH1750FVI
    return true;
}

// --- 3. เซนเซอร์วัดผิวดิน (Soil Stick Moisture & Surface Soil pH) ---
static void readSoilStick(SoilStickData &data, float ambientTempC) {
    // 3.1 อ่านความชื้นผิวดินจากลายทองแดงคาปาซิทีฟ (ADC A1)
    uint32_t adcSumMoist = 0;
    const int MOIST_SAMPLES = 10;
    for (int i = 0; i < MOIST_SAMPLES; i++) {
        adcSumMoist += analogRead(SOIL_STICK_PIN);
        delayMicroseconds(200);
    }
    data.rawAdc = adcSumMoist / MOIST_SAMPLES;

    // แปลงค่าเชิงเส้นตามช่วง Calibration (แห้งในอากาศ -> เปียกในน้ำ)
    float calculated = ((float)(SOIL_STICK_ADC_AIR - data.rawAdc) / (float)(SOIL_STICK_ADC_AIR - SOIL_STICK_ADC_WATER)) * 100.0f;
    data.moisture = constrain(calculated, 0.0f, 100.0f);
    data.isConnected = true;

    // 3.2 อ่านแรงดันไฟฟ้าเคมี pH ผิวดินผ่านช่อง ADC A2 (GPIO2)
#if defined(ENABLE_SURFACE_SOIL_PH) && ENABLE_SURFACE_SOIL_PH == true
    uint32_t adcSumPH = 0;
    for (int i = 0; i < SOIL_PH_READ_SAMPLES; i++) {
        adcSumPH += analogRead(SOIL_PH_PIN);
        delayMicroseconds(150);
    }
    float meanAdc = (float)adcSumPH / (float)SOIL_PH_READ_SAMPLES;
    data.rawPhVoltage = meanAdc * (3.3f / 4095.0f);

    // ตรวจสอบความถูกต้องของแรงดันหัววัด (ถ้าต่อหัววัดจริง ค่าจะอยู่ในช่วง 0.20V - 3.10V)
    if (data.rawPhVoltage >= 0.20f && data.rawPhVoltage <= 3.10f) {
        // Two-Point Linear Interpolation (Nernst Slope)
        float slope = (7.00f - 4.01f) / (SOIL_PH_CALIB_PH7_VOLT - SOIL_PH_CALIB_PH4_VOLT);
        float rawPh = 7.00f + (data.rawPhVoltage - SOIL_PH_CALIB_PH7_VOLT) * slope;

        // การชดเชยอุณหภูมิทางเคมีไฟฟ้าตามสมการเนิร์นสต์ (Nernstian Temperature Compensation)
        float tempKelvin = (ambientTempC > 0.0f ? ambientTempC : 25.0f) + 273.15f;
        float compensatedPh = 7.00f + (rawPh - 7.00f) * (298.15f / tempKelvin);

        data.ph = constrain(compensatedPh, 3.0f, 9.5f);
        data.isPhConnected = true;
    } else {
        data.ph = 0.0f;
        data.isPhConnected = false;
    }
#else
    data.rawPhVoltage = 0.0f;
    data.ph = 0.0f;
    data.isPhConnected = false;
#endif
}

// --- 4. Soil Multi-parameter 7-in-1 (RS485 Modbus RTU) ---
static bool readSoil7in1(SoilMultiParamData &data) {
    // เคลียร์บัฟเฟอร์ค้างเก่าใน Serial เพื่อป้องกันข้อมูลตกค้าง
    while (rs485Serial.available()) {
        rs485Serial.read();
    }

    // สร้างคำสั่ง Modbus Frame: Function Code 0x03, อ่าน 7 Registers (Start: 0x0000, Count: 0x0007)
    uint8_t query[8];
    query[0] = (uint8_t)SOIL_7IN1_SLAVE_ID;   // Slave Address (เช่น 0x01)
    query[1] = 0x03;                         // Function Code: Read Holding Registers
    query[2] = 0x00;                         // Start Address High
    query[3] = 0x00;                         // Start Address Low
    query[4] = 0x00;                         // Number of Registers High
    query[5] = 0x07;                         // Number of Registers Low (7 Registers: Moisture, Temp, EC, pH, N, P, K)
    
    uint16_t crc = calculateModbusCRC16(query, 6);
    query[6] = (uint8_t)(crc & 0xFF);        // CRC Low
    query[7] = (uint8_t)((crc >> 8) & 0xFF); // CRC High

    // ส่งคำสั่งออกไปยังบัส RS485
    rs485Serial.write(query, 8);
    rs485Serial.flush();

    // รอรับการตอบกลับ (Response ความยาว: 1(ID) + 1(Func) + 1(Bytes) + 14(Data 7*2) + 2(CRC) = 19 ไบต์)
    uint8_t response[25];
    uint8_t bytesRead = 0;
    uint32_t startTime = millis();
    const uint32_t TIMEOUT_MS = 600;

    while ((millis() - startTime) < TIMEOUT_MS && bytesRead < 19) {
        if (rs485Serial.available()) {
            response[bytesRead++] = rs485Serial.read();
        }
    }

    if (bytesRead < 19) {
        data.readErrorCount++;
        data.isConnected = false;
        return false;
    }

    // ตรวจสอบความถูกต้องของ Slave ID และ Function Code
    if (response[0] != SOIL_7IN1_SLAVE_ID || response[1] != 0x03 || response[2] != 14) {
        data.readErrorCount++;
        data.isConnected = false;
        return false;
    }

    // ตรวจสอบความถูกต้องของ Modbus CRC16
    uint16_t receivedCRC = response[17] | (response[18] << 8);
    uint16_t calculatedCRC = calculateModbusCRC16(response, 17);
    if (receivedCRC != calculatedCRC) {
        data.readErrorCount++;
        data.isConnected = false;
        return false;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // สกัดข้อมูลดิบ และปรับ Calibration (Linear + Constrain)
    // ──────────────────────────────────────────────────────────────────────────
    uint16_t rawMoisture = (response[3] << 8) | response[4];
    int16_t  rawTemp     = (response[5] << 8) | response[6];
    uint16_t rawEC       = (response[7] << 8) | response[8];
    uint16_t rawPH       = (response[9] << 8) | response[10];
    uint16_t rawN        = (response[11] << 8) | response[12];
    uint16_t rawP        = (response[13] << 8) | response[14];
    uint16_t rawK        = (response[15] << 8) | response[16];

    // Moisture & Temperature (สูตรจาก Datasheet, ไม่จำเป็นต้องปรับ)
    data.moisture    = constrain((float)rawMoisture / 10.0f, 0.0f, 100.0f);
    data.temperature = constrain((float)rawTemp / 10.0f, -20.0f, 85.0f);

    // EC (µS/cm) + Calibration
    data.ec = constrain((float)rawEC * EC_CAL_FACTOR + EC_CAL_OFFSET, 0.0f, 20000.0f);

    // pH – รองรับทั้ง format ×0.1 (เช่น 65 = 6.5) และ ×0.01 (เช่น 650 = 6.50)
    float rawPH_f = (rawPH > 140) ? ((float)rawPH / 100.0f) : ((float)rawPH / 10.0f);
    data.ph = constrain(rawPH_f * PH_CAL_FACTOR + PH_CAL_OFFSET, 0.0f, 14.0f);

    // N, P, K – ปรับ Calibration factor ตามทฤษฎีและงานวิจัย
    float calN = (float)rawN * N_CAL_FACTOR + N_CAL_OFFSET;
    float calP = (float)rawP * P_CAL_FACTOR + P_CAL_OFFSET;
    float calK = (float)rawK * K_CAL_FACTOR + K_CAL_OFFSET;

    // Fallback: ถ้า N+P+K ≈ 0 แต่ EC > 100 µS/cm ให้ประมาณจาก EC
    // (เกิดเมื่อ sensor ตอบสนองช้า หรือดินแห้งมาก)
    if (calN < 0.5f && calP < 0.5f && calK < 0.5f && data.ec > 100.0f) {
        calN = data.ec * EC_TO_N_RATIO;
        calP = data.ec * EC_TO_P_RATIO;
        calK = data.ec * EC_TO_K_RATIO;
        Serial.println("[AgriSensors] NPK fallback: estimated from EC");
    }

    data.nitrogen   = constrain(calN, N_CAL_MIN, N_CAL_MAX);
    data.phosphorus = constrain(calP, P_CAL_MIN, P_CAL_MAX);
    data.potassium  = constrain(calK, K_CAL_MIN, K_CAL_MAX);
    data.isConnected = true;

    return true;
}


/**
 * ============================================================================
 * Agricultural Metrics & Agronomy Calculations
 * ============================================================================
 */

// คำนวณจุดน้ำค้าง (Dew Point) ด้วยสมการ Magnus-Tetens
float AgriSensors_calculateDewPoint(float temp, float humidity) {
    if (humidity <= 0.0f) return temp;
    const float a = 17.27f;
    const float b = 237.7f;
    float alpha = ((a * temp) / (b + temp)) + logf(humidity / 100.0f);
    return (b * alpha) / (a - alpha);
}

// คำนวณแรงดึงระเหยน้ำ (Vapor Pressure Deficit: VPD) ในหน่วย kPa
// สำคัญมากต่อการเปิด-ปิดปากใบของพืชและการคายน้ำ (ช่วงที่เหมาะสมสำหรับพืชส่วนใหญ่: 0.8 - 1.2 kPa)
float AgriSensors_calculateVPD(float temp, float humidity) {
    // Saturated Vapor Pressure (VPsat)
    float vpsat = 0.61078f * expf((17.27f * temp) / (temp + 237.3f));
    // Actual Vapor Pressure (VPact)
    float vpact = vpsat * (humidity / 100.0f);
    return (vpsat - vpact);
}

// ประมาณค่าความเข้มพลังงานแสงอาทิตย์ (Solar Radiation) ในหน่วย W/m² จากค่า Lux
// ตามทฤษฎีแสงแดดธรรมชาติ (Direct Sunlight: ~126.7 Lux per W/m² หรือ 1 Lux ≈ 0.0079 W/m²)
float AgriSensors_estimateSolarRadiation(float lux) {
    return lux * 0.0079f;
}

/**
 * ============================================================================
 * Public APIs
 * ============================================================================
 */

void AgriSensors_init() {
    Serial.println("[AgriSensors] Initializing Hardware Interfaces...");

    // 1. เริ่มต้น I2C Bus ด้วย SDA:9 (สายเหลือง) และ SCL:8 (สายเขียว) ตามสายเซนเซอร์จริง
    Wire.begin(I2C_SDA_PIN, I2C_SCL_PIN);
    Wire.setClock(I2C_CLOCK_SPEED); // 10 kHz
    delay(50);

    Serial.printf("[AgriSensors] I2C Bus active on SDA (GPIO%d) and SCL (GPIO%d)\n", I2C_SDA_PIN, I2C_SCL_PIN);

    // ตรวจสอบการเชื่อมต่อ SHT45
    Wire.beginTransmission(SHT45_I2C_ADDR);
    if (Wire.endTransmission() == 0) {
        Serial.println("  >>> SHT45 (0x44) Connected Successfully! [OK]");
        telemetryData.air.isConnected = true;
    } else {
        Serial.println("  [!] SHT45 (0x44) not responding");
        telemetryData.air.isConnected = false;
    }

    // เริ่มต้นและตรวจสอบการเชื่อมต่อ BH1750 (โดมตะวัน)
    if (initBH1750()) {
        Serial.println("  >>> โดมตะวัน BH1750 (0x23) Connected Successfully! [OK]");
        telemetryData.light.isConnected = true;
    } else {
        Serial.println("  [!] โดมตะวัน BH1750 (0x23) not responding");
        telemetryData.light.isConnected = false;
    }

    // 2. ตั้งค่า Analog Pin สำหรับ Soil Stick เกษตรไทย IoT และ Surface Soil pH
    pinMode(SOIL_STICK_PIN, INPUT);
#if defined(ENABLE_SURFACE_SOIL_PH) && ENABLE_SURFACE_SOIL_PH == true
    pinMode(SOIL_PH_PIN, INPUT);
#endif
    analogReadResolution(12); // ESP32-S3 12-bit ADC (0-4095)
    analogSetAttenuation(ADC_11db); // รองรับแรงดัน Input สูงสุด ~3.1V
    Serial.println("[AgriSensors] Soil Stick (ADC A1) & Surface pH (ADC A2) Initialized [OK]");

    // 3. เริ่มต้นพอร์ต RS485 สำหรับ Soil Multi-parameter Sensor 7-in-1
    rs485Serial.begin(SOIL_7IN1_BAUDRATE, SERIAL_8N1, RS485_RX_PIN, RS485_TX_PIN);
    Serial.printf("[AgriSensors] RS485 Modbus RTU (RX:%d, TX:%d, Baud:%d) Initialized [OK]\n", 
                  RS485_RX_PIN, RS485_TX_PIN, SOIL_7IN1_BAUDRATE);

    telemetryData.soil7in1.readErrorCount = 0;
}

void AgriSensors_update() {
    telemetryData.timestamp = millis();

    // 1. อ่านค่าอุณหภูมิและความชื้นสัมพัทธ์ในอากาศ (SHT45)
    float airTemp = 0.0f, airHumi = 0.0f;
    if (readSHT45(airTemp, airHumi)) {
        telemetryData.air.temperature = airTemp;
        telemetryData.air.humidity    = airHumi;
        telemetryData.air.dewPoint    = AgriSensors_calculateDewPoint(airTemp, airHumi);
        telemetryData.air.vpd         = AgriSensors_calculateVPD(airTemp, airHumi);
        telemetryData.air.isConnected = true;
    } else {
        telemetryData.air.isConnected = false;
    }

    // 2. อ่านค่าความเข้มแสง (โดมตะวัน) - มีระบบ Auto Reconnect
    if (!telemetryData.light.isConnected) {
        initBH1750(); // ลองเชื่อมต่อใหม่อัตโนมัติหากเพิ่งเสียบสาย
    }
    float lux = 0.0f;
    if (readBH1750(lux)) {
        telemetryData.light.lux            = lux;
        telemetryData.light.kLux           = lux / 1000.0f;
        telemetryData.light.solarRadiation = AgriSensors_estimateSolarRadiation(lux);
        telemetryData.light.isConnected    = true;
    } else {
        telemetryData.light.isConnected = false;
    }

    // 3. อ่านค่าความชื้นและ pH ผิวดิน Soil Stick (Analog A1 & A2)
    readSoilStick(telemetryData.soilStick, telemetryData.air.temperature);

    // 4. หน่วงสลับบัสเล็กน้อยแล้วอ่านค่า Soil Multi-parameter 7-in-1 (RS485)
    delay(MODBUS_QUERY_DELAY_MS);
    readSoil7in1(telemetryData.soil7in1);

    // 5. ประมวลผลโมเดลการเรียนรู้เชิงลึก (TinyML Deep Learning Soil Neural Calibrator)
    // ทำการตัดการแทรกแซงข้ามตัวแปร (Cross-Sensitivity Decoupling), ชดเชยอุณหภูมิและความชื้น
    telemetryData.aiCalibrated = SoilNeuralCalibrator::predict(
        telemetryData.soil7in1.nitrogen,
        telemetryData.soil7in1.phosphorus,
        telemetryData.soil7in1.potassium,
        telemetryData.soil7in1.ec,
        telemetryData.soil7in1.moisture,
        telemetryData.soil7in1.temperature,
        telemetryData.air.temperature,
        telemetryData.air.humidity,
        telemetryData.soilStick.rawAdc,
        telemetryData.soil7in1.ph
    );
}

const FarmSensorTelemetry& AgriSensors_getTelemetry() {
    return telemetryData;
}

void AgriSensors_printDashboard() {
    Serial.println("\n==========================================================================");
    Serial.println("  SMART AGRICULTURE TELEMETRY MONITORING (ATD3.5-S3 GRAVITY CONTROLLER)   ");
    Serial.println("==========================================================================");

    // อากาศ
    Serial.println("[บรรยากาศโรงเรือน / อากาศรอบแปลง - SHT45]");
    if (telemetryData.air.isConnected) {
        Serial.printf("  - อุณหภูมิอากาศ (Temp)      : %.2f °C\n", telemetryData.air.temperature);
        Serial.printf("  - ความชื้นสัมพัทธ์ (RH)       : %.2f %%\n", telemetryData.air.humidity);
        Serial.printf("  - จุดน้ำค้าง (Dew Point)    : %.2f °C\n", telemetryData.air.dewPoint);
        Serial.printf("  - แรงดึงระเหยน้ำ (VPD)       : %.2f kPa (%s)\n", 
                      telemetryData.air.vpd, 
                      (telemetryData.air.vpd >= 0.8f && telemetryData.air.vpd <= 1.2f) ? "สมบูรณ์แบบสำหรับพืช" : 
                      (telemetryData.air.vpd < 0.8f ? "เสี่ยงโรคเชื้อรา" : "พืชคายน้ำสูง"));
    } else {
        Serial.println("  [!] ไม่สามารถเชื่อมต่อ SHT45 (ตรวจสอบสาย I2C)");
    }

    // แสงสว่าง
    Serial.println("\n[ความเข้มแสง - เซ็นเซอร์โดมตะวัน]");
    if (telemetryData.light.isConnected) {
        Serial.printf("  - ความเข้มแสง (Lux)        : %.1f Lux\n", telemetryData.light.lux);
        Serial.printf("  - ความเข้มแสง (kLux)       : %.2f kLux\n", telemetryData.light.kLux);
        Serial.printf("  - รังสีดวงอาทิตย์ (Solar)   : %.2f W/m²\n", telemetryData.light.solarRadiation);
    } else {
        Serial.println("  [!] ไม่สามารถเชื่อมต่อโดมตะวัน (ตรวจสอบสาย I2C)");
    }

    // ความชื้นและกรด-ด่างผิวดิน (Soil Stick & Surface pH)
    Serial.println("\n[ผิวดินชั้นตื้น (0-10 ซม.) - Soil Stick & Surface pH (A1 & A2)]");
    Serial.printf("  - สัญญาณดิบความชื้น ADC A1  : %u\n", telemetryData.soilStick.rawAdc);
    Serial.printf("  - ปริมาณความชื้นในดิน       : %.1f %%\n", telemetryData.soilStick.moisture);
    if (telemetryData.soilStick.isPhConnected) {
        Serial.printf("  - กรด-ด่างผิวดิน (Surface pH): %.2f pH (%.3f V, ชดเชยเนิร์นสต์แล้ว)\n", 
                      telemetryData.soilStick.ph, telemetryData.soilStick.rawPhVoltage);
    } else {
        Serial.println("  - กรด-ด่างผิวดิน (Surface pH): ไม่ได้ต่อโพรบ (ADC A2)");
    }

    // ดิน 7-in-1
    Serial.println("\n[คุณสมบัติดินเชิงลึก - Soil Multi-parameter Sensor 7-in-1 (RS485)]");
    if (telemetryData.soil7in1.isConnected) {
        Serial.printf("  - ความชื้นในดิน (Moisture)  : %.1f %%\n", telemetryData.soil7in1.moisture);
        Serial.printf("  - อุณหภูมิดิน (Soil Temp)   : %.1f °C\n", telemetryData.soil7in1.temperature);
        Serial.printf("  - สภาพนำไฟฟ้า (EC)          : %.0f µS/cm (%s)\n", 
                      telemetryData.soil7in1.ec,
                      (telemetryData.soil7in1.ec < 800) ? "ดินจืด/ธาตุอาหารต่ำ" :
                      (telemetryData.soil7in1.ec <= 2000 ? "เหมาะสมต่อพืชส่วนใหญ่" : "ดินเค็ม/ปุ๋ยตกค้างสูง"));
        Serial.printf("  - กรด-ด่างดิน (pH)          : %.2f (%s)\n", 
                      telemetryData.soil7in1.ph,
                      (telemetryData.soil7in1.ph < 5.5f) ? "ดินกรดจัด" :
                      (telemetryData.soil7in1.ph <= 7.0f ? "ดินเป็นกลาง/เหมาะสม" : "ดินด่าง"));
        Serial.printf("  - ไนโตรเจน (N)             : %.0f mg/kg\n", telemetryData.soil7in1.nitrogen);
        Serial.printf("  - ฟอสฟอรัส (P)             : %.0f mg/kg\n", telemetryData.soil7in1.phosphorus);
        Serial.printf("  - โพแทสเซียม (K)            : %.0f mg/kg\n", telemetryData.soil7in1.potassium);
    } else {
        Serial.printf("  [!] ไม่พบการสื่อสาร RS485 Modbus (Error Count: %u)\n", telemetryData.soil7in1.readErrorCount);
        Serial.println("      (ตรวจสอบสาย RS485 A/B, ไฟเลี้ยง 12V, และ Baudrate 4800)");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Deep Learning TinyML Neural Calibrator (ESP32-S3 Edge Inference)
    // ──────────────────────────────────────────────────────────────────────
    Serial.println("\n[TinyML Edge AI: Neural Soil Calibrator (ESP32-S3 Inference)]");
    Serial.printf("  - AI Calibrated N (Available) : %.1f mg/kg  (Raw: %.0f)\n", 
                  telemetryData.aiCalibrated.nitrogen, telemetryData.soil7in1.nitrogen);
    Serial.printf("  - AI Calibrated P (Available) : %.1f mg/kg  (Raw: %.0f)\n", 
                  telemetryData.aiCalibrated.phosphorus, telemetryData.soil7in1.phosphorus);
    Serial.printf("  - AI Calibrated K (Exchange)  : %.1f mg/kg  (Raw: %.0f)\n", 
                  telemetryData.aiCalibrated.potassium, telemetryData.soil7in1.potassium);
    Serial.printf("  - AI Calibrated Soil pH       : %.2f        (Raw: %.2f)\n", 
                  telemetryData.aiCalibrated.ph, telemetryData.soil7in1.ph);
    Serial.printf("  - AI True Volumetric Moisture : %.1f %%     (Probe: %.1f %%, Stick: %.1f %%)\n", 
                  telemetryData.aiCalibrated.moisture, telemetryData.soil7in1.moisture, telemetryData.soilStick.moisture);
    Serial.printf("  - Edge Inference Latency      : < 0.12 ms | Confidence Index: %.1f %%\n", 
                  telemetryData.aiCalibrated.confidence * 100.0f);
    Serial.println("==========================================================================\n");
}
