#pragma once
#include <Arduino.h>

/**
 * ============================================================================
 * ATD3.5-S3 & Farm1 Shield Pin Definitions
 * อ้างอิงสถาปัตยกรรมบอร์ด ATD3.5-S3 (ESP32-S3) ร่วมกับ ATD3.5-S3 Farm1 Shield
 * ============================================================================
 */

// --- I2C Bus สำหรับเซนเซอร์ SHT45 (Air Temp/Humidity) และ BH1750 (โดมตะวัน) ---
// สลับพินตามสายเซนเซอร์จริง: สายสีเหลือง = SDA (GPIO9), สายสีเขียว = SCL (GPIO8)
#define I2C_SDA_PIN         9       // GPIO9 (SDA: สายสีเหลือง)
#define I2C_SCL_PIN         8       // GPIO8 (SCL: สายสีเขียว)
#define I2C_CLOCK_SPEED     10000   // 10 kHz (Standard Mode เพื่อเสถียรภาพสายยาวในแปลง)

// --- RS485 Modbus RTU Bus สำหรับเซนเซอร์ดิน Multi-parameter 7-in-1 (NPK/pH/EC) ---
// ภายในบอร์ดเชื่อมผ่านชิปแปลงสัญญาณ RS485 Transceiver (MAX485/SP3485)
#define RS485_UART_PORT     2       // ใช้ HardwareSerial 2
#define RS485_RX_PIN        41      // GPIO41 -> RO (Receiver Output ของ RS485)
#define RS485_TX_PIN        40      // GPIO40 -> DI (Driver Input ของ RS485)

// --- Analog Soil Moisture Input สำหรับ Soil Stick เกษตรไทย IoT ---
#define SOIL_STICK_ADC_PIN  1       // GPIO1 (ช่อง A1 บนบอร์ด Farm1 Shield)

// --- Relay Control Outputs (พอร์ต O1, O2, O3, O4 บนบอร์ด Farm1 Shield) ---
#define RELAY_1_PIN         39      // O1: Pump 1
#define RELAY_2_PIN         38      // O2: Pump 2 / Solenoid Valve
#define RELAY_3_PIN         7       // O3: Light / Fan
#define RELAY_4_PIN         6       // O4: Misting System

// --- Touchscreen & LCD ST7796 (บอร์ด ATD3.5-S3) ---
#define LCD_BL_PIN          3       // Backlight Control Pin (GPIO3)
#define LCD_CS_PIN          10      // Chip Select
#define LCD_DC_PIN          21      // Data/Command
#define LCD_RST_PIN         14      // Reset
#define SPI_SCK_PIN         12      // SPI Clock
#define SPI_MOSI_PIN        11      // SPI MOSI
#define SPI_MISO_PIN        13      // SPI MISO
