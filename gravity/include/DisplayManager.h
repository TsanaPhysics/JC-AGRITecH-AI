#pragma once
#include <Arduino.h>
#include <LovyanGFX.hpp>
#include "AgriSensors.h"

/**
 * ============================================================================
 * LGFX Driver Configuration for ATD3.5-S3 (ST7796 SPI + Capacitive Touch)
 * ============================================================================
 */
class LGFX_ATD35 : public lgfx::LGFX_Device {
    lgfx::Panel_ST7796  _panel_instance;
    lgfx::Bus_SPI       _bus_instance;
    lgfx::Light_PWM     _light_instance;
    lgfx::Touch_FT5x06  _touch_instance;

public:
    LGFX_ATD35(void) {
        {
            auto cfg = _bus_instance.config();
            cfg.spi_host = SPI2_HOST; // FSPI / SPI2
            cfg.spi_mode = 0;
            cfg.freq_write = 40000000;
            cfg.freq_read  = 16000000;
            cfg.pin_sclk = 12;
            cfg.pin_mosi = 11;
            cfg.pin_miso = 13;
            cfg.pin_dc   = 21;
            _bus_instance.config(cfg);
            _panel_instance.setBus(&_bus_instance);
        }
        {
            auto cfg = _panel_instance.config();
            cfg.pin_cs   = 10;
            cfg.pin_rst  = 14;
            cfg.pin_busy = -1;
            cfg.panel_width  = 320;
            cfg.panel_height = 480;
            cfg.offset_x = 0;
            cfg.offset_y = 0;
            cfg.bus_shared = true;
            cfg.invert = true; // เปิด Invert สีสำหรับจอ ST7796 IPS เพื่อให้สีดำมืดสนิทและสีถูกต้องตามมาตรฐาน
            _panel_instance.config(cfg);
        }
        {
            auto cfg = _light_instance.config();
            cfg.pin_bl = 3; // ขา Backlight ของ ATD3.5-S3 คือ GPIO3
            cfg.invert = false;
            cfg.freq   = 44100;
            cfg.pwm_channel = 7;
            _light_instance.config(cfg);
            _panel_instance.setLight(&_light_instance);
        }
        {
            auto cfg = _touch_instance.config();
            cfg.x_min      = 0;
            cfg.x_max      = 319;
            cfg.y_min      = 0;
            cfg.y_max      = 479;
            cfg.pin_int    = -1;
            cfg.pin_rst    = -1;
            cfg.bus_shared = false;
            cfg.offset_rotation = 0;
            cfg.i2c_port   = 1;     // Wire1 สำหรับทัชสกรีน FT6336U
            cfg.i2c_addr   = 0x38;  // FT6336U I2C Address
            cfg.pin_sda    = 15;    // GPIO 15
            cfg.pin_scl    = 16;    // GPIO 16
            cfg.freq       = 400000;
            _touch_instance.config(cfg);
            _panel_instance.setTouch(&_touch_instance);
        }
        setPanel(&_panel_instance);
    }
};

enum DisplayPage {
    PAGE_OVERVIEW     = 0,
    PAGE_BIG_NUMBERS  = 1,
    PAGE_GRAPHS       = 2,
    PAGE_RELAYS       = 3,
    PAGE_WIFI_SETUP   = 4,
    PAGE_DETAIL_AIR   = 5,
    PAGE_DETAIL_LIGHT = 6,
    PAGE_DETAIL_SOIL1 = 7,
    PAGE_DETAIL_SOIL7 = 8
};

enum DisplayLanguage {
    LANG_TH = 0, // ภาษาไทย
    LANG_EN = 1, // English
    LANG_ZH = 2  // 中文
};

void DisplayManager_init();
void DisplayManager_update(const FarmSensorTelemetry &data, bool pumpState, bool mistingState);
bool DisplayManager_getTouch(int32_t *x, int32_t *y);
void DisplayManager_handleTouch(bool &pumpState, bool &mistingState);
void DisplayManager_setPage(DisplayPage page);
DisplayPage DisplayManager_getPage();
void DisplayManager_setLanguage(DisplayLanguage lang);
DisplayLanguage DisplayManager_getLanguage();
void DisplayManager_toggleLanguage();
void DisplayManager_scroll(int deltaY);
int DisplayManager_getScrollY();
bool DisplayManager_hasPageChanged();
