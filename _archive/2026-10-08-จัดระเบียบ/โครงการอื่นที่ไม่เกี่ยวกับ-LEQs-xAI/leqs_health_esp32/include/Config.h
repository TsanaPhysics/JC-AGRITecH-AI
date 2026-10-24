// ============================================================================
// LEQs AgriSci xAI — Health Step Tracker ESP32
// ไฟล์: include/Config.h
// ============================================================================
#pragma once
#include <Arduino.h>

// Wi-Fi
#define WIFI_SSID       "YourSSID"
#define WIFI_PASSWORD   "YourPassword"
#define HTTP_SERVER_PORT  8080

// I2C Pins (MPU6050)
#if defined(CONFIG_IDF_TARGET_ESP32S3)
  #define I2C_SDA_PIN   8
  #define I2C_SCL_PIN   9
#else
  #define I2C_SDA_PIN   21
  #define I2C_SCL_PIN   22
#endif
#define MPU6050_ADDR        0x68
#define MPU6050_SAMPLE_RATE_MS 10

// Step Detection
#define STEP_THRESHOLD_HIGH   1.3f
#define STEP_THRESHOLD_LOW    0.8f
#define MIN_STEP_INTERVAL_MS  300
#define SMOOTHING_ALPHA       0.3f

// Sedentary Alert
#define SEDENTARY_ALERT_MIN   30
#define BUZZER_PIN            5
#define LED_PIN               2

// Broadcast
#define BROADCAST_INTERVAL_MS  1000

// Calorie
#define DEFAULT_WEIGHT_KG   60.0f
#define STRIDE_LENGTH_M     0.75f
#define WALKING_MET         3.5f
#define RUNNING_MET         9.8f
