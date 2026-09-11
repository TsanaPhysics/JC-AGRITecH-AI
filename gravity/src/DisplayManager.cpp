#include "DisplayManager.h"
#include "WiFiConfigManager.h"
#include "PinConfigs.h"
#include "ThaiFontVLW.h"
#include <WiFi.h>

static LGFX_ATD35 lcd;

// สีสำหรับ UI Design (RGB565)
#define COLOR_BG          0x0841 // ดำอมน้ำเงินเข้ม
#define COLOR_CARD_BG     0x18E3 // การ์ดพื้นหลังสีเทาเข้ม
#define COLOR_HEADER      0x02E7 // เขียวมรกตเข้ม
#define COLOR_TEXT_DIM    0x9CD3 // สีเทาอ่อน
#define COLOR_TEXT_VAL    0xFFFF // ขาว
#define COLOR_ACCENT      0x3E7C // เขียวสด
#define COLOR_CYAN        0x277E // ฟ้าสว่าง
#define COLOR_YELLOW      0xFDE0 // เหลืองสว่าง/ทอง
#define COLOR_WARN        0xFA08 // ส้ม/แดง
#define COLOR_ACTIVE_TAB  0x07E0 // นีออนเขียวแท็บแอคทีฟ
#define COLOR_INACT_TAB   0x18C3 // เทาเข้มแท็บปกติ

// ตัวแปรควบคุมการแสดงผลและหน้าจอ
static DisplayPage currentPage = PAGE_OVERVIEW;
static bool pageChanged = true;
static unsigned long lastTouchTime = 0;

// บิตแมปภาษาไทย "ชีวะ  ทัศนา" (ความกว้าง 84 x สูง 18 พิกเซล)
const uint16_t THAI_NAME_W = 84;
const uint16_t THAI_NAME_H = 18;
const uint8_t thai_name_bitmap[] = {
    0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
    0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
    0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
    0x00, 0x20, 0x00, 0x00, 0x00, 0x00, 0x07, 0x00, 0x00, 0x00, 0x00,
    0x03, 0xE0, 0x00, 0x00, 0x00, 0x00, 0x05, 0x20, 0x00, 0x00, 0x00,
    0x07, 0xE0, 0x00, 0x00, 0x00, 0x00, 0x03, 0xC0, 0x00, 0x00, 0x00,
    0x00, 0x20, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
    0x00, 0x40, 0x00, 0x00, 0x00, 0x00, 0x00, 0x03, 0x00, 0x00, 0x00,
    0x19, 0x87, 0x83, 0x80, 0x00, 0x00, 0xE6, 0x3C, 0x71, 0x1E, 0x00,
    0x34, 0x88, 0xC2, 0xA0, 0x00, 0x00, 0xAA, 0x42, 0x51, 0x23, 0x00,
    0x2C, 0x80, 0x43, 0xC0, 0x00, 0x00, 0x6A, 0x5E, 0x71, 0x01, 0x00,
    0x3C, 0x80, 0x40, 0x00, 0x00, 0x00, 0x32, 0x56, 0x11, 0x01, 0x00,
    0x04, 0x80, 0x43, 0x80, 0x00, 0x00, 0x32, 0x6E, 0x11, 0x01, 0x00,
    0x04, 0x80, 0xC2, 0xA0, 0x00, 0x00, 0x32, 0x22, 0x17, 0x01, 0x00,
    0x04, 0x81, 0x42, 0xA0, 0x00, 0x00, 0x22, 0x22, 0x1F, 0x01, 0x00,
    0x0F, 0x81, 0xC1, 0xC0, 0x00, 0x00, 0x22, 0x22, 0x13, 0x01, 0x00,
    0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
    0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
};

// Circular Ring Buffer สำหรับบันทึกประวัติค่าเซนเซอร์ 60 จุดสำหรับพล็อตกราฟ
#define HISTORY_LEN 60
struct SensorHistoryPoint {
    float soilSurface;
    float soilDeep;
    float solarRad;
    float airTemp;
};
static SensorHistoryPoint history[HISTORY_LEN];
static int historyCount = 0;
static int historyHead = 0;

static void addHistoryPoint(float sSurface, float sDeep, float sRad, float aTemp) {
    history[historyHead].soilSurface = sSurface;
    history[historyHead].soilDeep = sDeep;
    history[historyHead].solarRad = sRad;
    history[historyHead].airTemp = aTemp;
    historyHead = (historyHead + 1) % HISTORY_LEN;
    if (historyCount < HISTORY_LEN) {
        historyCount++;
    }
}

static void drawTopNavBar();
static DisplayLanguage currentLanguage = LANG_TH;

void DisplayManager_setLanguage(DisplayLanguage lang) {
    if (currentLanguage != lang) {
        currentLanguage = lang;
        if (currentLanguage == LANG_TH) {
            lcd.loadFont(thai_font_vlw);
        } else if (currentLanguage == LANG_ZH) {
            lcd.unloadFont();
            lcd.setFont(&fonts::efontCN_14);
        } else {
            lcd.unloadFont();
            lcd.setFont(&fonts::Font2);
        }
        pageChanged = true;
        if (currentPage <= PAGE_WIFI_SETUP) {
            drawTopNavBar();
        }
    }
}

DisplayLanguage DisplayManager_getLanguage() {
    return currentLanguage;
}

void DisplayManager_toggleLanguage() {
    DisplayLanguage nextLang = (currentLanguage == LANG_TH) ? LANG_EN : ((currentLanguage == LANG_EN) ? LANG_ZH : LANG_TH);
    DisplayManager_setLanguage(nextLang);
}

static const char* L_STR(const char *th, const char *en, const char *zh) {
    if (currentLanguage == LANG_TH) return th;
    if (currentLanguage == LANG_ZH) return zh;
    return en;
}

static void drawTopNavBar();
static void drawDetailHeader(const char *th_title, const char *en_title, const char *zh_title, uint16_t accentCol);

static int32_t scrollOffsetY = 0;
static int32_t maxScrollY = 0;

void DisplayManager_setPage(DisplayPage page) {
    if (currentPage != page) {
        currentPage = page;
        pageChanged = true;
        scrollOffsetY = 0;
        if (currentPage >= PAGE_DETAIL_AIR && currentPage <= PAGE_DETAIL_SOIL7) {
            maxScrollY = (currentPage == PAGE_DETAIL_SOIL7) ? 270 : 220;
        } else {
            maxScrollY = 0;
        }
    }
}

DisplayPage DisplayManager_getPage() {
    return currentPage;
}

void DisplayManager_scroll(int deltaY) {
    scrollOffsetY += deltaY;
    if (scrollOffsetY < 0) scrollOffsetY = 0;
    if (scrollOffsetY > maxScrollY) scrollOffsetY = maxScrollY;
    pageChanged = true;
}

int DisplayManager_getScrollY() {
    return scrollOffsetY;
}

// ============================================================================
// แถบเลื่อนสไลด์บาร์แนวตั้งแบบโมเดิร์น (Modern Vertical Scrollbar)
// ============================================================================
static void drawScrollBar(int scrollY, int maxScroll, int viewTop, int viewHeight, uint16_t accentCol) {
    if (maxScroll <= 0) return;
    int barX = 472;
    int barW = 6;
    int trackH = viewHeight - 8;
    int trackY = viewTop + 4;

    // รางเลื่อน Track สีเข้ม
    lcd.fillRoundRect(barX, trackY, barW, trackH, 3, 0x18C3);

    // ก้อนเลื่อน Thumb (คำนวณตามสัดส่วน)
    int thumbH = max(32, trackH * trackH / (trackH + maxScroll));
    int thumbY = trackY + (scrollY * (trackH - thumbH) / maxScroll);
    lcd.fillRoundRect(barX, thumbY, barW, thumbH, 3, accentCol);
    lcd.drawRoundRect(barX, thumbY, barW, thumbH, 3, 0xFFFF);
}

// ============================================================================
// SVG-Style Vector Graphic Icons (ไอคอนกราฟิกเวกเตอร์คมชัดแทนข้อความ)
// ============================================================================

// 1. ไอคอนเทอร์โมมิเตอร์ (อุณหภูมิ / Temperature) 🌡️
static void drawIconThermometer(int x, int y, uint16_t col) {
    lcd.drawRoundRect(x + 2, y, 6, 12, 2, 0xFFFF);
    lcd.fillCircle(x + 5, y + 13, 5, col);
    lcd.drawCircle(x + 5, y + 13, 5, 0xFFFF);
    lcd.fillRect(x + 4, y + 4, 2, 8, col);
}

// 2. ไอคอนหยดน้ำ (ความชื้น / Humidity) 💧
static void drawIconDroplet(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 6, y + 10, 5, col);
    lcd.fillTriangle(x + 1, y + 9, x + 11, y + 9, x + 6, y + 2, col);
    lcd.drawPixel(x + 4, y + 8, 0xFFFF);
    lcd.drawPixel(x + 4, y + 9, 0xFFFF);
}

// 3. ไอคอนดวงอาทิตย์ (รังสีดวงอาทิตย์ / Solar Radiation) ☀️
static void drawIconSun(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 7, y + 7, 4, col);
    lcd.drawCircle(x + 7, y + 7, 4, 0xFFFF);
    lcd.drawFastVLine(x + 7, y, 3, col);
    lcd.drawFastVLine(x + 7, y + 11, 3, col);
    lcd.drawFastHLine(x, y + 7, 3, col);
    lcd.drawFastHLine(x + 11, y + 7, 3, col);
    lcd.drawLine(x + 2, y + 2, x + 4, y + 4, col);
    lcd.drawLine(x + 12, y + 12, x + 10, y + 10, col);
    lcd.drawLine(x + 12, y + 2, x + 10, y + 4, col);
    lcd.drawLine(x + 2, y + 12, x + 4, y + 10, col);
}

// 4. ไอคอนหลอดไฟ (ความสว่าง / Illuminance Lux) 💡
static void drawIconBulb(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 6, y + 6, 5, col);
    lcd.fillRect(x + 4, y + 11, 4, 3, 0xC618);
    lcd.drawPixel(x + 6, y + 1, col);
    lcd.drawPixel(x + 1, y + 4, col);
    lcd.drawPixel(x + 11, y + 4, col);
}

// 5. ไอคอนต้นกล้าผิวดิน (ความชื้นดินตื้น / Soil Moisture) 🌱
static void drawIconSprout(int x, int y, uint16_t col) {
    lcd.drawFastHLine(x, y + 14, 14, 0x9CD3); // ผิวดิน
    lcd.drawFastVLine(x + 6, y + 5, 9, 0x07E0); // ก้าน
    lcd.fillTriangle(x + 6, y + 8, x + 6, y + 5, x, y + 5, col); // ใบซ้าย
    lcd.fillTriangle(x + 6, y + 7, x + 6, y + 4, x + 12, y + 4, 0x07E0); // ใบขวา
}

// 6. ไอคอนขวดเคมีดิน (NPK & Soil Chemistry) 🧪
static void drawIconFlask(int x, int y, uint16_t col) {
    lcd.drawRect(x + 4, y, 4, 4, 0xFFFF);
    lcd.fillTriangle(x + 6, y + 4, x, y + 14, x + 12, y + 14, col);
    lcd.drawTriangle(x + 6, y + 4, x, y + 14, x + 12, y + 14, 0xFFFF);
    lcd.drawPixel(x + 4, y + 11, 0x07E0); // เม็ด N
    lcd.drawPixel(x + 6, y + 12, 0x07FF); // เม็ด P
    lcd.drawPixel(x + 8, y + 11, 0xFFE0); // เม็ด K
}

// ============================================================================
// Big Vector Graphic Icons (ไอคอนสัญลักษณ์ขนาดใหญ่สำหรับโหมดเน้นตัวเลข)
// ============================================================================

// 1. ไอคอนเทอร์โมมิเตอร์ขนาดใหญ่ 🌡️
static void drawBigIconThermometer(int x, int y, uint16_t col) {
    lcd.drawRoundRect(x + 4, y, 10, 20, 4, 0xFFFF);
    lcd.fillCircle(x + 9, y + 22, 9, col);
    lcd.drawCircle(x + 9, y + 22, 9, 0xFFFF);
    lcd.fillRect(x + 7, y + 6, 4, 14, col);
}

// 2. ไอคอนหยดน้ำขนาดใหญ่ 💧
static void drawBigIconDroplet(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 10, y + 16, 9, col);
    lcd.fillTriangle(x + 2, y + 14, x + 18, y + 14, x + 10, y + 2, col);
    lcd.drawCircle(x + 10, y + 16, 9, 0xFFFF);
    lcd.drawPixel(x + 6, y + 12, 0xFFFF);
    lcd.drawPixel(x + 7, y + 13, 0xFFFF);
    lcd.drawPixel(x + 6, y + 14, 0xFFFF);
}

// 3. ไอคอนดวงอาทิตย์ขนาดใหญ่ ☀️
static void drawBigIconSun(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 13, y + 13, 7, col);
    lcd.drawCircle(x + 13, y + 13, 7, 0xFFFF);
    lcd.drawFastVLine(x + 13, y, 5, col);
    lcd.drawFastVLine(x + 13, y + 21, 5, col);
    lcd.drawFastHLine(x, y + 13, 5, col);
    lcd.drawFastHLine(x + 21, y + 13, 5, col);
    lcd.drawLine(x + 3, y + 3, x + 7, y + 7, col);
    lcd.drawLine(x + 23, y + 23, x + 19, y + 19, col);
    lcd.drawLine(x + 23, y + 3, x + 19, y + 7, col);
    lcd.drawLine(x + 3, y + 23, x + 7, y + 19, col);
}

// 4. ไอคอนหลอดไฟขนาดใหญ่ 💡
static void drawBigIconBulb(int x, int y, uint16_t col) {
    lcd.fillCircle(x + 10, y + 10, 8, col);
    lcd.drawCircle(x + 10, y + 10, 8, 0xFFFF);
    lcd.fillRect(x + 7, y + 18, 6, 5, 0xC618);
    lcd.drawRect(x + 7, y + 18, 6, 5, 0xFFFF);
    lcd.drawPixel(x + 10, y + 2, 0xFFFF);
    lcd.drawPixel(x + 3, y + 6, col);
    lcd.drawPixel(x + 17, y + 6, col);
}

// 5. ไอคอนต้นกล้าผิวดินขนาดใหญ่ 🌱
static void drawBigIconSprout(int x, int y, uint16_t col) {
    lcd.drawFastHLine(x, y + 24, 24, 0x9CD3); // ผิวดิน
    lcd.drawFastVLine(x + 11, y + 8, 16, 0x07E0); // ลำต้น
    lcd.fillTriangle(x + 11, y + 14, x + 11, y + 8, x + 2, y + 8, col); // ใบซ้าย
    lcd.drawTriangle(x + 11, y + 14, x + 11, y + 8, x + 2, y + 8, 0xFFFF);
    lcd.fillTriangle(x + 11, y + 12, x + 11, y + 6, x + 20, y + 6, 0x07E0); // ใบขวา
    lcd.drawTriangle(x + 11, y + 12, x + 11, y + 6, x + 20, y + 6, 0xFFFF);
}

// 6. ไอคอนขวดเคมีดินขนาดใหญ่ 🧪
static void drawBigIconFlask(int x, int y, uint16_t col) {
    lcd.drawRect(x + 7, y, 6, 6, 0xFFFF);
    lcd.fillTriangle(x + 10, y + 6, x, y + 24, x + 20, y + 24, col);
    lcd.drawTriangle(x + 10, y + 6, x, y + 24, x + 20, y + 24, 0xFFFF);
    lcd.fillCircle(x + 7, y + 18, 2, 0x07E0); // เม็ด N
    lcd.fillCircle(x + 10, y + 20, 2, 0x07FF); // เม็ด P
    lcd.fillCircle(x + 13, y + 18, 2, 0xFFE0); // เม็ด K
}

// ฟังก์ชันวาดหน่วย W/m² โดยให้เลข 2 เป็นตัวยก (Superscript)
static void drawUnitW_m2(int x, int y, uint16_t col, uint16_t bg) {
    lcd.setTextColor(col, bg);
    lcd.drawString("W/m", x, y, &fonts::Font2);
    int wmWidth = lcd.textWidth("W/m", &fonts::Font2);
    lcd.drawString("2", x + wmWidth + 1, y - 4, &fonts::Font0);
}

// วาดแท็บด้านบนขนาดใหญ่ 5 แท็บ + ปุ่มสลับภาษา (TH / EN / 中文)
static void drawTopNavBar() {
    struct TabInfo {
        int x;
        int w;
    };
    TabInfo tabs[5] = {
        {2, 78},
        {82, 78},
        {162, 78},
        {242, 78},
        {322, 78}
    };

    const char *tab_names_th[5] = {"1.ภาพรวม", "2.ข้อมูล", "3.กราฟ", "4.รีเลย์", "5.ตั้งค่า"};
    const char *tab_names_en[5] = {"1.HOME", "2.DATA", "3.GRAPH", "4.RELAY", "5.SETUP"};
    const char *tab_names_zh[5] = {"1.主页", "2.数据", "3.图表", "4.继电器", "5.设置"};

    // สีเฉพาะของแต่ละแท็บ (active: สีสด, inactive: สีเข้มหม่น)
    const uint16_t tab_active_colors[5]  = {0x07FF, 0x07E0, 0xFD20, 0xF81F, 0xFFE0}; // cyan, green, orange, magenta, yellow
    const uint16_t tab_dim_colors[5]     = {0x0410, 0x0320, 0x3800, 0x5004, 0x3180}; // dimmed versions
    const uint16_t tab_border_colors[5]  = {0x07FF, 0x07E0, 0xFD20, 0xF81F, 0xFFE0};

    for (int i = 0; i < 5; i++) {
        bool isActive = (currentPage == (DisplayPage)i);
        uint16_t bg     = isActive ? tab_active_colors[i] : tab_dim_colors[i];
        uint16_t border = tab_border_colors[i];
        uint16_t textCol = isActive ? 0x0000 : tab_active_colors[i];

        lcd.fillRoundRect(tabs[i].x, 2, tabs[i].w, 30, 5, bg);
        lcd.drawRoundRect(tabs[i].x, 2, tabs[i].w, 30, 5, border);

        const char *label = (currentLanguage == LANG_TH) ? tab_names_th[i] : ((currentLanguage == LANG_ZH) ? tab_names_zh[i] : tab_names_en[i]);
        lcd.setTextColor(textCol, bg);
        lcd.setTextDatum(textdatum_t::middle_center);
        lcd.drawString(label, tabs[i].x + (tabs[i].w / 2), 2 + 15);
        lcd.setTextDatum(textdatum_t::top_left);
    }

    // ปุ่มสลับ 3 ภาษา (TH / EN / 中文) ที่ x: 404, w: 74 พร้อมไอคอนลูกโลก 🌐
    const char *lang_chip = (currentLanguage == LANG_TH) ? "ไทย" : ((currentLanguage == LANG_ZH) ? "中文" : "ENG");
    uint16_t chip_border = (currentLanguage == LANG_TH) ? COLOR_ACCENT : ((currentLanguage == LANG_ZH) ? COLOR_YELLOW : COLOR_CYAN);
    lcd.fillRoundRect(404, 2, 74, 30, 5, 0x18E3);
    lcd.drawRoundRect(404, 2, 74, 30, 5, chip_border);

    // วาดไอคอนลูกโลก 🌐 หน้าชื่อภาษา
    lcd.drawCircle(416, 17, 5, chip_border);
    lcd.drawFastHLine(411, 17, 11, chip_border);
    lcd.drawFastVLine(416, 12, 11, chip_border);

    lcd.setTextColor(chip_border, 0x18E3);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(lang_chip, 404 + 44, 2 + 15);
    lcd.setTextDatum(textdatum_t::top_left);
}

// แถบหัวด้านบนสำหรับหน้าจอแสดงรายละเอียดเซนเซอร์เดี่ยว (Multi-Language)
static void drawDetailHeader(const char *th_title, const char *en_title, const char *zh_title, uint16_t accentCol) {
    const char *title = L_STR(th_title, en_title, zh_title);
    const char *back_lbl = L_STR("<ย้อน", "<BACK", "<返回");
    const char *next_lbl = L_STR("ถัดไป>", "NEXT>", "下一>");
    const char *lang_chip = (currentLanguage == LANG_TH) ? "ไทย" : ((currentLanguage == LANG_ZH) ? "中文" : "ENG");

    // 1. ปุ่ม [ < BACK ]
    lcd.fillRoundRect(4, 2, 74, 30, 5, 0x18C3);
    lcd.drawRoundRect(4, 2, 74, 30, 5, 0xFFFF);
    lcd.setTextColor(0xFFFF, 0x18C3);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(back_lbl, 4 + 37, 2 + 15);

    // 2. ชื่อเซนเซอร์ตรงกลาง
    lcd.fillRoundRect(84, 2, 240, 30, 5, COLOR_CARD_BG);
    lcd.drawRoundRect(84, 2, 240, 30, 5, accentCol);
    lcd.setTextColor(accentCol, COLOR_CARD_BG);
    lcd.drawString(title, 84 + 120, 2 + 15);

    // 3. ปุ่ม [ NEXT > ]
    lcd.fillRoundRect(330, 2, 74, 30, 5, 0x028A);
    lcd.drawRoundRect(330, 2, 74, 30, 5, COLOR_CYAN);
    lcd.setTextColor(COLOR_CYAN, 0x028A);
    lcd.drawString(next_lbl, 330 + 37, 2 + 15);

    // 4. ปุ่มภาษา [ TH/EN/中 ]
    uint16_t chip_border = (currentLanguage == LANG_TH) ? COLOR_ACCENT : ((currentLanguage == LANG_ZH) ? COLOR_YELLOW : COLOR_CYAN);
    lcd.fillRoundRect(408, 2, 68, 30, 5, 0x18E3);
    lcd.drawRoundRect(408, 2, 68, 30, 5, chip_border);
    lcd.setTextColor(chip_border, 0x18E3);
    lcd.drawString(lang_chip, 408 + 34, 2 + 15);
    lcd.setTextDatum(textdatum_t::top_left);
}

// ============================================================================
// SPLASH SCREEN - JC AgriTech + AI: Neural Plant Nexus (Option 2)
// ระบบเกษตรอัจฉริยะและการเรียนรู้เชิงลึก (Deep Learning + Living Plant + Precision Sensors)
// ============================================================================
static void drawSplashScreen() {
    const int cx = 240;
    const int cy = 76; // Nexus center (AI brain + sprout)
    const uint16_t goldC = 0xFDE0; // RBRU Gold
    const uint16_t SBGC  = 0x0020; // Deep Obsidian Dark Background

    lcd.fillScreen(SBGC);

    // ─────────────────────────────────────────────────────────────
    // Phase 1: Deep Tech Awakening (Ambient Halo & Background Synapses)
    // ─────────────────────────────────────────────────────────────
    // Soft concentric ambient aura rings around the nexus
    lcd.drawCircle(cx, cy, 78, 0x08A2);
    lcd.drawCircle(cx, cy, 64, 0x0903);
    lcd.drawCircle(cx, cy, 50, 0x0964);

    // Faint neural nodes & connection lines in the background
    int bgNodes[][2] = {
        {60, 40}, {110, 30}, {370, 30}, {420, 45},
        {45, 95}, {100, 110}, {380, 105}, {435, 90},
        {75, 140}, {405, 145}
    };
    for (int i = 0; i < 10; i++) {
        lcd.fillCircle(bgNodes[i][0], bgNodes[i][1], 2, 0x0273);
    }
    // Faint connection lines between background nodes
    lcd.drawLine(60, 40, 110, 30, 0x0162);
    lcd.drawLine(110, 30, 100, 110, 0x0162);
    lcd.drawLine(60, 40, 45, 95, 0x0162);
    lcd.drawLine(45, 95, 75, 140, 0x0162);
    lcd.drawLine(370, 30, 420, 45, 0x0162);
    lcd.drawLine(370, 30, 380, 105, 0x0162);
    lcd.drawLine(420, 45, 435, 90, 0x0162);
    lcd.drawLine(435, 90, 405, 145, 0x0162);

    // ─────────────────────────────────────────────────────────────
    // Phase 2: Holographic Orbital Telemetry & Precision Sensor Nodes
    // ─────────────────────────────────────────────────────────────
    // Orbit 1 (inner): rx=96, ry=30
    for (int deg = 0; deg < 360; deg += 10) {
        float rad = deg * 0.0174533f;
        int ox = cx + (int)(96 * cos(rad));
        int oy = cy + 6 + (int)(30 * sin(rad));
        if (deg % 30 == 0) lcd.drawPixel(ox, oy, 0x04F8);
        else lcd.drawPixel(ox, oy, 0x01C6);
    }
    // Orbit 2 (outer): rx=124, ry=38
    for (int deg = 0; deg < 360; deg += 12) {
        float rad = deg * 0.0174533f;
        int ox = cx + (int)(124 * cos(rad));
        int oy = cy + 6 + (int)(38 * sin(rad));
        if (deg % 24 == 0) lcd.drawPixel(ox, oy, 0x0374);
        else lcd.drawPixel(ox, oy, 0x0103);
    }

    // Floating Sensor Telemetry Badges (4 Precision Farm Parameters)
    // 1. TEMP (Upper Left)
    lcd.fillRoundRect(134, 40, 56, 15, 3, 0x0164);
    lcd.drawRoundRect(134, 40, 56, 15, 3, 0x0478);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.setTextColor(0x07FF, 0x0164);
    lcd.drawString("24.5'C", 162, 48, &fonts::Font0);
    lcd.drawLine(190, 48, 208, 62, 0x0375); // line to brain

    // 2. HUM (Upper Right)
    lcd.fillRoundRect(290, 40, 56, 15, 3, 0x0164);
    lcd.drawRoundRect(290, 40, 56, 15, 3, 0x0478);
    lcd.setTextColor(0x07E0, 0x0164);
    lcd.drawString("HUM 68%", 318, 48, &fonts::Font0);
    lcd.drawLine(290, 48, 272, 62, 0x0375);

    // 3. SOIL (Lower Right)
    lcd.fillRoundRect(306, 92, 60, 15, 3, 0x0164);
    lcd.drawRoundRect(306, 92, 60, 15, 3, 0x0478);
    lcd.setTextColor(0x3E7C, 0x0164);
    lcd.drawString("SOIL 72%", 336, 100, &fonts::Font0);
    lcd.drawLine(306, 100, 270, 92, 0x0375);

    // 4. VPD (Lower Left)
    lcd.fillRoundRect(114, 92, 60, 15, 3, 0x0164);
    lcd.drawRoundRect(114, 92, 60, 15, 3, 0x0478);
    lcd.setTextColor(0xFDE0, 0x0164);
    lcd.drawString("VPD 1.12", 144, 100, &fonts::Font0);
    lcd.drawLine(174, 100, 210, 92, 0x0375);

    delay(70);

    // ─────────────────────────────────────────────────────────────
    // Phase 3: AI Brain & Synaptic Circuit Nexus (Cerebral Cortex)
    // ─────────────────────────────────────────────────────────────
    // Brain Base / Chip Socket
    const int by = cy + 18; // y ≈ 94
    lcd.fillRoundRect(cx - 16, by - 6, 32, 16, 3, 0x0287);
    lcd.drawRoundRect(cx - 16, by - 6, 32, 16, 3, 0x07FF);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.setTextColor(0xFFFF, 0x0287);
    lcd.drawString("AI", cx, by + 1, &fonts::Font0);

    // Left & Right Hemisphere Synapse Circuit Branches
    // Left hemisphere circuit paths:
    lcd.drawLine(cx - 16, by, cx - 32, by - 4, 0x07FF);
    lcd.drawLine(cx - 32, by - 4, cx - 36, by - 16, 0x07FF);
    lcd.drawLine(cx - 36, by - 16, cx - 26, by - 24, 0x067A);
    lcd.drawLine(cx - 26, by - 24, cx - 12, by - 20, 0x07E0);

    lcd.drawLine(cx - 24, by - 8, cx - 14, by - 14, 0x067A);
    lcd.drawLine(cx - 32, by - 4, cx - 44, by, 0x067A);

    // Right hemisphere circuit paths:
    lcd.drawLine(cx + 16, by, cx + 32, by - 4, 0x07FF);
    lcd.drawLine(cx + 32, by - 4, cx + 36, by - 16, 0x07FF);
    lcd.drawLine(cx + 36, by - 16, cx + 26, by - 24, 0x067A);
    lcd.drawLine(cx + 26, by - 24, cx + 12, by - 20, 0x07E0);

    lcd.drawLine(cx + 24, by - 8, cx + 14, by - 14, 0x067A);
    lcd.drawLine(cx + 32, by - 4, cx + 44, by, 0x067A);

    // Synapse Nodes / Micro-vias (dots)
    int synNodes[][2] = {
        {cx - 44, by}, {cx - 36, by - 16}, {cx - 26, by - 24}, {cx - 12, by - 20},
        {cx + 44, by}, {cx + 36, by - 16}, {cx + 26, by - 24}, {cx + 12, by - 20},
        {cx - 24, by - 8}, {cx + 24, by - 8}
    };
    for (int i = 0; i < 10; i++) {
        lcd.fillCircle(synNodes[i][0], synNodes[i][1], 3, 0x07FF);
        lcd.fillCircle(synNodes[i][0], synNodes[i][1], 1, 0xFFFF);
    }
    delay(80);

    // ─────────────────────────────────────────────────────────────
    // Phase 4: Living Sprout (Growing from AI Core)
    // ─────────────────────────────────────────────────────────────
    // Central Stem rising from AI core to canopy
    for (int sy = by - 6; sy >= cy - 36; sy -= 2) {
        lcd.drawFastVLine(cx - 1, sy, 3, 0x07E0);
        lcd.drawFastVLine(cx,     sy, 3, 0xFFFF);
        lcd.drawFastVLine(cx + 1, sy, 3, 0x07E0);
    }

    // Left Leaf (Bio-Emerald leaf rising up-left)
    for (int t = 0; t <= 12; t++) {
        float f = t / 12.0f;
        int lx = cx - 2 - (int)(30 * sin(f * 1.57f));
        int ly = cy - 10 - (int)(38 * f);
        int thick = (int)(9 * sin(f * 3.14159f));
        lcd.drawLine(lx - thick, ly, lx + thick, ly, 0x05E0 + (t * 0x0040));
    }
    // Left leaf outer contour & glowing tip
    lcd.drawLine(cx - 2, cy - 10, cx - 18, cy - 32, 0x07E0);
    lcd.drawLine(cx - 18, cy - 32, cx - 32, cy - 48, 0x07FF);
    lcd.drawLine(cx - 32, cy - 48, cx - 18, cy - 44, 0x07E0);
    lcd.drawLine(cx - 18, cy - 44, cx - 2, cy - 38, 0x07E0);
    // Left leaf circuit vein
    lcd.drawLine(cx - 4, cy - 18, cx - 24, cy - 38, 0x07FF);
    lcd.fillCircle(cx - 24, cy - 38, 2, 0xFFFF);

    // Right Leaf (Golden-Emerald leaf rising up-right)
    for (int t = 0; t <= 12; t++) {
        float f = t / 12.0f;
        int rx = cx + 2 + (int)(34 * sin(f * 1.57f));
        int ry = cy - 14 - (int)(40 * f);
        int thick = (int)(10 * sin(f * 3.14159f));
        lcd.drawLine(rx - thick, ry, rx + thick, ry, 0x27E0 + (t * 0x0800));
    }
    // Right leaf outer contour & glowing tip
    lcd.drawLine(cx + 2, cy - 14, cx + 20, cy - 36, 0x07E0);
    lcd.drawLine(cx + 20, cy - 36, cx + 36, cy - 54, 0xFDE0);
    lcd.drawLine(cx + 36, cy - 54, cx + 20, cy - 48, 0x07E0);
    lcd.drawLine(cx + 20, cy - 48, cx + 2, cy - 42, 0x07E0);
    // Right leaf circuit vein
    lcd.drawLine(cx + 4, cy - 22, cx + 26, cy - 44, 0x07FF);
    lcd.fillCircle(cx + 26, cy - 44, 2, 0xFFFF);

    // Radiating Golden Seed Sparkle at top
    lcd.fillCircle(cx, cy - 42, 3, 0xFFFF);
    lcd.drawCircle(cx, cy - 42, 5, 0xFDE0);
    delay(80);

    // ─────────────────────────────────────────────────────────────
    // Phase 5: Branding - "JC AgriTech + AI"
    // ─────────────────────────────────────────────────────────────
    lcd.setTextDatum(textdatum_t::middle_center);
    
    // Glowing shadow layers for 3D neon presence
    lcd.setTextColor(0x01A6, SBGC);
    lcd.drawString("JC AgriTech + AI", cx + 2, 147, &fonts::Font4);
    lcd.drawString("JC AgriTech + AI", cx - 1, 145, &fonts::Font4);
    
    // Crisp face with dual-color accents
    lcd.setTextDatum(textdatum_t::middle_right);
    lcd.setTextColor(0x07FF, SBGC);
    lcd.drawString("JC AgriTech ", cx + 18, 146, &fonts::Font4);
    lcd.setTextDatum(textdatum_t::middle_left);
    lcd.setTextColor(0x07E0, SBGC);
    lcd.drawString("+ AI", cx + 18, 146, &fonts::Font4);
    delay(80);

    // ─────────────────────────────────────────────────────────────
    // Phase 6: Thai Subtitle & Technology Pillars
    // ─────────────────────────────────────────────────────────────
    lcd.loadFont(thai_font_vlw);
    lcd.setTextDatum(textdatum_t::middle_center);
    
    // Subtitle in Thai: "ระบบเกษตรอัจฉริยะและการเรียนรู้เชิงลึก"
    lcd.setTextColor(0x0182, SBGC);
    lcd.drawString("ระบบเกษตรอัจฉริยะและการเรียนรู้เชิงลึก", cx + 1, 177); // shadow
    lcd.setTextColor(0xFFFF, SBGC);
    lcd.drawString("ระบบเกษตรอัจฉริยะและการเรียนรู้เชิงลึก", cx, 176);     // crisp white
    delay(80);

    // Tech Pillars: "Digital | IoT | Smart Farm | Edge AI"
    lcd.setTextColor(0x3DFF, SBGC);
    lcd.drawString("Digital  |  IoT  |  Smart Farm  |  Edge AI", cx, 204, &fonts::Font2);
    delay(80);

    // ─────────────────────────────────────────────────────────────
    // Phase 7: Academic Attribution (Authors & University)
    // ─────────────────────────────────────────────────────────────
    lcd.loadFont(thai_font_vlw);
    // Co-Authors (White)
    lcd.setTextColor(0xFFFF, SBGC);
    lcd.drawString("ผศ.ดร.จิรภัทร จันทมาลี   |   ผศ.ดร.ชีวะ ทัศนา", cx, 234);
    delay(70);

    // University (RBRU Gold)
    lcd.setTextColor(goldC, SBGC);
    lcd.drawString("มหาวิทยาลัยราชภัฏรำไพพรรณี", cx, 258);
    delay(80);

    // ─────────────────────────────────────────────────────────────
    // Phase 8: Ultra-Thin Glowing Laser Progress Bar
    // ─────────────────────────────────────────────────────────────
    const int bx = 50, by_bar = 292, bw = 380, bh = 4;
    lcd.fillRoundRect(bx, by_bar, bw, bh, 2, 0x10A2); // track

    // Laser progress sweep
    for (int p = 0; p <= bw; p += 10) {
        uint16_t col = (p < bw / 2) ? 0x07FF : 0x07E0;
        lcd.fillRoundRect(bx, by_bar, p, bh, 2, col);
        
        // Travelling white laser flare
        if (p > 4 && p < bw) {
            lcd.drawFastVLine(bx + p, by_bar - 2, bh + 4, 0xFFFF);
            lcd.drawFastVLine(bx + p + 1, by_bar - 1, bh + 2, 0xFFFF);
        }
        delay(22);
    }
    // Clean finish
    lcd.fillRoundRect(bx, by_bar, bw, bh, 2, 0x07E0);
    delay(150);
}

void DisplayManager_init() {
    Serial.println("[Display] Initializing 3.5\" IPS ST7796 LCD Display...");

    pinMode(LCD_BL_PIN, OUTPUT);
    digitalWrite(LCD_BL_PIN, HIGH);

    lcd.init();
    lcd.setRotation(1); // แนวนอน 480x320
    lcd.setBrightness(255);
    digitalWrite(LCD_BL_PIN, HIGH);
    lcd.fillScreen(0x0820);

    // โหลดฟอนต์ไทยก่อนแสดง splash (ใช้สำหรับชื่อผู้พัฒนา)
    lcd.loadFont(thai_font_vlw);

    // แสดง splash screen JC AGRITecH+AI
    drawSplashScreen();

    // เคลียร์ splash → เตรียมหน้าภาพรวม
    lcd.fillScreen(COLOR_BG);

    // โหลดฟอนต์ตามภาษาที่ตั้งค่าไว้
    if (currentLanguage == LANG_TH) {
        // Thai font already loaded
    } else if (currentLanguage == LANG_ZH) {
        lcd.unloadFont();
        lcd.setFont(&fonts::efontCN_14);
    } else {
        lcd.unloadFont();
        lcd.setFont(&fonts::Font2);
    }

    if (currentPage != PAGE_OVERVIEW && currentPage != PAGE_BIG_NUMBERS) {
        drawTopNavBar();
    }

    Serial.println("[Display] Display initialized successfully! Multi-Screen Touch UI (TH/EN/ZH) is READY.");
}

// ============================================================================
// แถบหัวด้านบนสำหรับหน้าภาพรวม (JC AgriTech+AI Branding + Wi-Fi Status + ปุ่มภาษา)
// ============================================================================
static void drawOverviewTopHeader() {
    // พื้นหลังแถบ Header ดำออบซิเดียนหรู ไร้เส้นรบกวนตา
    lcd.fillRect(0, 0, 480, 38, 0x0862);

    // โลโก้ JC โมโนแกรมสไตล์ Modern Tech:
    // ตัว J โค้งสีเขียวมรกต (0x15D3)
    // ตัว C โค้งสีฟ้าสว่าง (0x07FF)
    int jx = 16, jy = 12;
    // J: ก้านแนวตั้งและส่วนโค้งล่าง
    lcd.fillRect(jx + 9, jy + 2, 4, 11, 0x15D3);
    lcd.fillCircle(jx + 6, jy + 13, 5, 0x15D3);
    lcd.fillCircle(jx + 6, jy + 13, 2, 0x0862); // cutout
    lcd.fillRect(jx + 2, jy + 8, 4, 5, 0x15D3);

    // C: วงโค้งขวาสีฟ้าสว่าง
    lcd.fillCircle(jx + 18, jy + 10, 7, 0x07FF);
    lcd.fillCircle(jx + 18, jy + 10, 3, 0x0862); // cutout
    lcd.fillRect(jx + 20, jy + 7, 6, 6, 0x0862); // open mouth of C

    // ข้อความ "JC-AGRITecH +AI   เวอร์ชัน 1.0"
    lcd.loadFont(thai_font_vlw);
    lcd.setTextDatum(textdatum_t::middle_left);
    int curX = 44;
    // 1. JC-AGRITecH +AI
    lcd.setTextColor(0xFFFF, 0x0862);
    lcd.drawString("JC-AGRITecH +AI", curX, 19);
    curX += lcd.textWidth("JC-AGRITecH +AI") + 12;

    // 2. เวอร์ชัน 1.0 (สีทอง RBRU Gold)
    lcd.setTextColor(0xFDE0, 0x0862);
    lcd.drawString("เวอร์ชัน 1.0", curX, 19);

    // ไอคอน Wi-Fi: คลื่น 3 ระดับสีเขียวนีออน + จุดศูนย์กลาง
    bool wifiOk = (WiFi.status() == WL_CONNECTED);
    uint16_t wifiCol = wifiOk ? 0x15D3 : 0x52AA;
    int wx = 392, wy = 26;
    lcd.fillCircle(wx, wy, 2, wifiCol);
    lcd.fillArc(wx, wy, 5, 7, 225, 315, wifiCol);
    lcd.fillArc(wx, wy, 9, 11, 225, 315, wifiCol);
    lcd.fillArc(wx, wy, 13, 15, 225, 315, wifiCol);

    // ปุ่มสลับภาษาทรงแคปซูลมนสีดำ [ 🇹🇭 TH ]
    int px = 416, py = 7, pw = 56, ph = 26;
    lcd.fillRoundRect(px, py, pw, ph, 13, 0x18E3);
    lcd.drawRoundRect(px, py, pw, ph, 13, 0x39E7);

    // ตราธงชาติไทยทรงกลมด้านซ้ายของแคปซูล
    int fx = px + 12, fy = py + 13, fr = 7;
    lcd.fillCircle(fx, fy, fr, 0xD800); // พื้นแดง
    lcd.fillRect(fx - fr + 1, fy - 4, (fr * 2) - 2, 8, 0xFFFF); // แถบขาว
    lcd.fillRect(fx - fr + 1, fy - 2, (fr * 2) - 2, 4, 0x0017); // แถบน้ำเงิน
    lcd.drawCircle(fx, fy, fr, 0x39E7);

    // ตัวอักษร "TH" สีขาว
    lcd.setTextColor(0xFFFF, 0x18E3);
    lcd.setTextDatum(textdatum_t::middle_left);
    lcd.drawString("TH", px + 25, py + 13, &fonts::Font2);
    lcd.setTextDatum(textdatum_t::top_left);
}

// ============================================================================
// แถบปุ่มนำทาง 4 ปุ่มด้านล่างสำหรับหน้าภาพรวม (Bottom Navigation Bar)
// ============================================================================
static void drawOverviewBottomNav() {
    const char *lbl_home  = L_STR("ภาพรวม", "HOME", "主页");
    const char *lbl_graph = L_STR("ข้อมูล/กราฟ", "GRAPH", "图表");
    const char *lbl_relay = L_STR("รีเลย์", "RELAYS", "继电器");
    const char *lbl_setup = L_STR("ตั้งค่า", "SETUP", "设置");

    // ปุ่ม 1: ภาพรวม (Active: พื้นเขียวมรกต 0x0320, ขอบเขียวสด 0x15D3, ตัวหนังสือขาว)
    lcd.fillRoundRect(10, 274, 106, 38, 8, 0x0320);
    lcd.drawRoundRect(10, 274, 106, 38, 8, 0x15D3);
    lcd.setTextColor(0xFFFF, 0x0320);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(lbl_home, 10 + 53, 274 + 19);

    // ปุ่ม 2: ข้อมูล/กราฟ (พื้น 0x0926, ขอบ 0x0471, ตัวหนังสือฟ้าอ่อน)
    lcd.fillRoundRect(126, 274, 106, 38, 8, 0x0926);
    lcd.drawRoundRect(126, 274, 106, 38, 8, 0x0471);
    lcd.setTextColor(0x9E7F, 0x0926);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(lbl_graph, 126 + 53, 274 + 19);

    // ปุ่ม 3: รีเลย์ (พื้น 0x28A3, ขอบ 0xB887, ตัวหนังสือกุหลาบอ่อน)
    lcd.fillRoundRect(242, 274, 106, 38, 8, 0x28A3);
    lcd.drawRoundRect(242, 274, 106, 38, 8, 0xB887);
    lcd.setTextColor(0xFCD7, 0x28A3);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(lbl_relay, 242 + 53, 274 + 19);

    // ปุ่ม 4: ตั้งค่า (พื้น 0x2902, ขอบ 0xB2A1, ตัวหนังสืออำพันอ่อน)
    lcd.fillRoundRect(358, 274, 106, 38, 8, 0x2902);
    lcd.drawRoundRect(358, 274, 106, 38, 8, 0xB2A1);
    lcd.setTextColor(0xFDE8, 0x2902);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(lbl_setup, 358 + 53, 274 + 19);

    lcd.setTextDatum(textdatum_t::top_left);
}

// ============================================================================
// หน้าที่ 1: ภาพรวมสถานะแปลง (Overview & Health Dashboard) - Masterpiece Layout
// 100% Matching with smart_farm_ui_overview.jpg
// ============================================================================
static void drawPageOverview(const FarmSensorTelemetry &data, bool pumpState, bool mistingState) {
    char buf[64];

    if (pageChanged) {
        lcd.fillRect(0, 0, 480, 320, 0x0862); // พื้นหลังรวมสีดำออบซิเดียนหรู
        drawOverviewTopHeader();

        // -------------------------------------------------------------
        // การ์ด 1: สภาพอากาศ Microclimate Weather (x: 10, y: 42, w: 224, h: 114)
        // -------------------------------------------------------------
        lcd.fillRoundRect(10, 42, 224, 114, 10, 0x10E4);
        lcd.drawRoundRect(10, 42, 224, 114, 10, 0x2255); // ขอบเทาอมฟ้าหม่น
        lcd.setTextColor(0xDEFB, 0x10E4);
        lcd.drawString("Microclimate Weather", 20, 52, &fonts::Font2);

        // -------------------------------------------------------------
        // การ์ด 2: แสงอาทิตย์ Solar Dome (x: 246, y: 42, w: 224, h: 114)
        // -------------------------------------------------------------
        lcd.fillRoundRect(246, 42, 224, 114, 10, 0x10E4);
        lcd.drawRoundRect(246, 42, 224, 114, 10, 0x3A2A); // ขอบเทาอมส้มหม่น
        lcd.setTextColor(0xDEFB, 0x10E4);
        lcd.drawString("Solar Dome", 258, 52, &fonts::Font2);

        // ดวงอาทิตย์สีทองเรืองแสง (Hollow ring + 8 rays)
        int cx = 292, cy = 104;
        lcd.fillCircle(cx, cy, 14, 0xFD20);
        lcd.fillCircle(cx, cy, 9, 0x10E4); // cutout ตรงกลาง
        // 8 รังสีรอบวง
        lcd.fillRect(cx - 1, cy - 24, 3, 7, 0xFD20);
        lcd.fillRect(cx - 1, cy + 17, 3, 7, 0xFD20);
        lcd.fillRect(cx - 24, cy - 1, 7, 3, 0xFD20);
        lcd.fillRect(cx + 17, cy - 1, 7, 3, 0xFD20);
        lcd.drawLine(cx - 12, cy - 12, cx - 17, cy - 17, 0xFD20);
        lcd.drawLine(cx - 11, cy - 12, cx - 16, cy - 17, 0xFD20);
        lcd.drawLine(cx + 12, cy - 12, cx + 17, cy - 17, 0xFD20);
        lcd.drawLine(cx + 11, cy - 12, cx + 16, cy - 17, 0xFD20);
        lcd.drawLine(cx - 12, cy + 12, cx - 17, cy + 17, 0xFD20);
        lcd.drawLine(cx - 11, cy + 12, cx - 16, cy + 17, 0xFD20);
        lcd.drawLine(cx + 12, cy + 12, cx + 17, cy + 17, 0xFD20);
        lcd.drawLine(cx + 11, cy + 12, cx + 16, cy + 17, 0xFD20);

        // -------------------------------------------------------------
        // การ์ด 3: ดินตื้น Soil Moisture (x: 10, y: 164, w: 224, h: 102)
        // -------------------------------------------------------------
        lcd.fillRoundRect(10, 164, 224, 102, 10, 0x10E4);
        lcd.drawRoundRect(10, 164, 224, 102, 10, 0x1A4F);
        lcd.setTextColor(0xDEFB, 0x10E4);
        lcd.drawString("Soil Moisture", 20, 174, &fonts::Font2);

        // ไอคอนต้นกล้าผิวดินสไตล์มินิมอลโมเดิร์น
        int sx = 26, sy = 212;
        lcd.fillArc(sx + 14, sy + 30, 9, 11, 210, 330, 0x07FF); // คลื่นผิวดิน
        lcd.fillRect(sx + 13, sy + 14, 3, 14, 0x15D3); // ก้าน
        lcd.fillCircle(sx + 7, sy + 18, 5, 0x15D3);
        lcd.fillCircle(sx + 21, sy + 14, 5, 0x15D3);

        // -------------------------------------------------------------
        // การ์ด 4: ดินลึก Deep Soil NPK & pH (x: 246, y: 164, w: 224, h: 102)
        // -------------------------------------------------------------
        lcd.fillRoundRect(246, 164, 224, 102, 10, 0x10E4);
        lcd.drawRoundRect(246, 164, 224, 102, 10, 0x3A2A);
        lcd.setTextColor(0xDEFB, 0x10E4);
        lcd.drawString("Deep Soil NPK & pH", 258, 174, &fonts::Font2);

        // ป้ายสัญลักษณ์ AI-Edge แสดงว่าใช้ TinyML ชดเชยอุณหภูมิและความชื้นแล้ว
        lcd.fillRoundRect(396, 172, 64, 16, 4, 0x0320);
        lcd.drawRoundRect(396, 172, 64, 16, 4, 0x07E0);
        lcd.setTextColor(0x07E0, 0x0320);
        lcd.setTextDatum(textdatum_t::middle_center);
        lcd.drawString("AI-Edge", 396 + 32, 172 + 8, &fonts::Font0);
        lcd.setTextDatum(textdatum_t::top_left);

        // แถบปุ่มนำทาง 4 ปุ่มด้านล่าง
        drawOverviewBottomNav();

        pageChanged = false;
    }

    // ========================================================================
    // อัปเดตข้อมูลการ์ด 1: Microclimate Weather
    // ========================================================================
    // เม็ดแคปซูล VPD ด้านขวาบนของการ์ด 1
    lcd.fillRoundRect(152, 48, 74, 18, 9, 0x2187);
    lcd.drawRoundRect(152, 48, 74, 18, 9, 0x3A4E);
    snprintf(buf, sizeof(buf), "VPD %.2f kPa", data.air.vpd);
    lcd.setTextColor(0xDEFB, 0x2187);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(buf, 152 + 37, 48 + 9, &fonts::Font0);
    lcd.setTextDatum(textdatum_t::top_left);

    // ตัวเลขอุณหภูมิ (ซ้าย) e.g. 28.5 °C
    lcd.fillRect(18, 78, 104, 52, 0x10E4);
    if (data.air.isConnected) {
        snprintf(buf, sizeof(buf), "%.1f", data.air.temperature);
        lcd.setTextColor(0xFFFF, 0x10E4);
        lcd.drawString(buf, 20, 80, &fonts::Font4);
        int tW = lcd.textWidth(buf, &fonts::Font4);
        // สัญลักษณ์องศาเซลเซียส °C
        lcd.drawCircle(20 + tW + 5, 84, 3, 0x8CD7);
        lcd.setTextColor(0x8CD7, 0x10E4);
        lcd.drawString("C", 20 + tW + 11, 80, &fonts::Font2);
    } else {
        lcd.setTextColor(0xFA08, 0x10E4);
        lcd.drawString("--.-", 20, 80, &fonts::Font4);
    }

    // ตัวเลขความชื้นสัมพัทธ์ (ขวา) e.g. 68 %RH
    lcd.fillRect(124, 78, 106, 52, 0x10E4);
    if (data.air.isConnected) {
        snprintf(buf, sizeof(buf), "%.0f", data.air.humidity);
        lcd.setTextColor(0xFFFF, 0x10E4);
        lcd.drawString(buf, 134, 80, &fonts::Font4);
        int hW = lcd.textWidth(buf, &fonts::Font4);
        lcd.setTextColor(0x8CD7, 0x10E4);
        lcd.drawString("%RH", 134 + hW + 4, 94, &fonts::Font2);
    } else {
        lcd.setTextColor(0xFA08, 0x10E4);
        lcd.drawString("--", 134, 80, &fonts::Font4);
    }

    // ========================================================================
    // อัปเดตข้อมูลการ์ด 2: Solar Dome
    // ========================================================================
    // แถวที่ 1: kLux e.g. 52.4 kLux
    lcd.fillRect(340, 72, 126, 32, 0x10E4);
    if (data.light.isConnected) {
        snprintf(buf, sizeof(buf), "%.1f", data.light.lux / 1000.0f);
        lcd.setTextColor(0xFFFF, 0x10E4);
        lcd.drawString(buf, 342, 74, &fonts::Font4);
        int lW = lcd.textWidth(buf, &fonts::Font4);
        lcd.setTextColor(0xDEFB, 0x10E4);
        lcd.drawString("kLux", 342 + lW + 4, 78, &fonts::Font2);
    } else {
        lcd.setTextColor(0xFA08, 0x10E4);
        lcd.drawString("--.-", 342, 74, &fonts::Font4);
    }

    // แถวที่ 2: W/m² e.g. 414 W/m²
    lcd.fillRect(340, 106, 126, 36, 0x10E4);
    if (data.light.isConnected) {
        snprintf(buf, sizeof(buf), "%.0f", data.light.solarRadiation);
        lcd.setTextColor(0xFFFF, 0x10E4);
        lcd.drawString(buf, 350, 110, &fonts::Font4);
        int sW = lcd.textWidth(buf, &fonts::Font4);
        drawUnitW_m2(350 + sW + 4, 112, 0xDEFB, 0x10E4);
    } else {
        lcd.setTextColor(0xFA08, 0x10E4);
        lcd.drawString("---", 350, 110, &fonts::Font4);
    }

    // ========================================================================
    // อัปเดตข้อมูลการ์ด 3: Soil Moisture (Semi-Circular Arc Radial Gauge)
    // ========================================================================
    int gcx = 140, gcy = 248;
    // เคลียร์พื้นที่เกจและตัวเลข
    lcd.fillRect(66, 194, 164, 68, 0x10E4);

    // แททร็กโค้งพื้นหลัง 180 องศา (หงายขึ้นด้านบน 180 ถึง 360 องศา)
    lcd.fillArc(gcx, gcy, 36, 43, 180, 360, 0x2146);

    // แททร็กความคืบหน้าแบบเรเดียลตามค่าความชื้น
    float m = constrain(data.soilStick.moisture, 0.0f, 100.0f);
    float sweepAngle = (m / 100.0f) * 180.0f;
    float endAngle = 180.0f + sweepAngle;
    if (sweepAngle > 1.0f) {
        // ส่วนโค้งเรเดียลสีเขียวมรกต/ฟ้าสดใส
        lcd.fillArc(gcx, gcy, 36, 43, 180.0f, endAngle, 0x15D3);
        // ฝาปิดปลายกลมที่ 180 องศา
        lcd.fillCircle(gcx - 39, gcy, 3, 0x15D3);
        // ฝาปิดปลายกลมที่จุดสิ้นสุด
        float rad = (endAngle * 3.14159265f) / 180.0f;
        lcd.fillCircle(gcx + (int)(39.5f * cosf(rad)), gcy + (int)(39.5f * sinf(rad)), 3, 0x15D3);
    }

    // ค่าตัวเลขใหญ่ตรงกลางเกจ e.g. 42.5 %
    snprintf(buf, sizeof(buf), "%.1f", data.soilStick.moisture);
    lcd.setTextColor(0xFFFF, 0x10E4);
    lcd.setTextDatum(textdatum_t::middle_right);
    lcd.drawString(buf, gcx + 18, gcy - 8, &fonts::Font4);
    lcd.setTextColor(0x07FF, 0x10E4);
    lcd.setTextDatum(textdatum_t::middle_left);
    lcd.drawString("%", gcx + 22, gcy - 4, &fonts::Font2);
    lcd.setTextDatum(textdatum_t::top_left);

    // ========================================================================
    // อัปเดตข้อมูลการ์ด 4: Deep Soil NPK & pH
    // ========================================================================
    // แถวที่ 1: pH (ชดเชยด้วยโมเดล AI) และ EC
    lcd.fillRect(256, 192, 210, 30, 0x10E4);
    lcd.setTextColor(0x07E0, 0x10E4);
    lcd.drawString("pH", 256, 197, &fonts::Font2);
    snprintf(buf, sizeof(buf), "%.1f", (data.aiCalibrated.ph > 0.1f) ? data.aiCalibrated.ph : data.soil7in1.ph);
    lcd.setTextColor(0xFFFF, 0x10E4);
    lcd.drawString(buf, 282, 194, &fonts::Font4);

    lcd.setTextColor(0x9CD3, 0x10E4);
    lcd.drawString("EC", 338, 197, &fonts::Font2);
    snprintf(buf, sizeof(buf), "%.0f", data.soil7in1.ec);
    lcd.setTextColor(0xFFFF, 0x10E4);
    lcd.drawString(buf, 364, 194, &fonts::Font4);
    int ecW = lcd.textWidth(buf, &fonts::Font4);
    lcd.setTextColor(0x7BEF, 0x10E4);
    lcd.drawString("uS/cm", 364 + ecW + 3, 202, &fonts::Font0);

    // แถวที่ 2: แคปซูลเม็ดยาสามสี N, P, K (ค่าคำนวณจาก Deep Learning TinyML) + mg/kg
    lcd.fillRect(252, 224, 214, 28, 0x10E4);
    // แคปซูล N (ม่วงน้ำเงิน)
    lcd.fillRoundRect(254, 226, 46, 22, 11, 0x633C);
    lcd.setTextColor(0xFFFF, 0x633C);
    lcd.setTextDatum(textdatum_t::middle_center);
    int dispN = (data.aiCalibrated.nitrogen > 0.1f) ? (int)roundf(data.aiCalibrated.nitrogen) : (int)data.soil7in1.nitrogen;
    snprintf(buf, sizeof(buf), "N %d", dispN);
    lcd.drawString(buf, 254 + 23, 226 + 11);

    // แคปซูล P (เขียวมรกต)
    lcd.fillRoundRect(306, 226, 46, 22, 11, 0x15D3);
    lcd.setTextColor(0xFFFF, 0x15D3);
    int dispP = (data.aiCalibrated.phosphorus > 0.1f) ? (int)roundf(data.aiCalibrated.phosphorus) : (int)data.soil7in1.phosphorus;
    snprintf(buf, sizeof(buf), "P %d", dispP);
    lcd.drawString(buf, 306 + 23, 226 + 11);

    // แคปซูล K (ส้มทอง)
    lcd.fillRoundRect(358, 226, 52, 22, 11, 0xFC64);
    lcd.setTextColor(0xFFFF, 0xFC64);
    int dispK = (data.aiCalibrated.potassium > 0.1f) ? (int)roundf(data.aiCalibrated.potassium) : (int)data.soil7in1.potassium;
    snprintf(buf, sizeof(buf), "K %d", dispK);
    lcd.drawString(buf, 358 + 26, 226 + 11);

    // หน่วย mg/kg
    lcd.setTextColor(0x8410, 0x10E4);
    lcd.setTextDatum(textdatum_t::middle_left);
    lcd.drawString("mg/kg", 416, 226 + 11, &fonts::Font0);
    lcd.setTextDatum(textdatum_t::top_left);
}

static void drawPageBigNumbers(const FarmSensorTelemetry &data) {
    drawPageOverview(data, false, false);
}


// ============================================================================
// แถบปุ่มนำทางด้านล่างของหน้ารายละเอียดเซนเซอร์ (Bottom Nav Bar)
// ============================================================================
static void drawDetailBottomNav(int sy, DisplayPage nextPage, int customBaseY = 436) {
    int btnY = customBaseY - sy;
    // 1. ปุ่ม [< ย้อนกลับหน้าแรก]
    lcd.fillRoundRect(14, btnY, 216, 36, 6, 0x18C3);
    lcd.drawRoundRect(14, btnY, 216, 36, 6, 0xFFFF);
    lcd.setTextColor(0xFFFF, 0x18C3);
    lcd.setTextDatum(textdatum_t::middle_center);
    lcd.drawString(L_STR("< ย้อนกลับหน้าแรก", "< BACK TO HOME", "< 返回主页"), 14 + 108, btnY + 18);

    // 2. ปุ่ม [เซนเซอร์ถัดไป >]
    lcd.fillRoundRect(238, btnY, 216, 36, 6, 0x028A);
    lcd.drawRoundRect(238, btnY, 216, 36, 6, COLOR_CYAN);
    lcd.setTextColor(COLOR_CYAN, 0x028A);
    lcd.drawString(L_STR("เซนเซอร์ถัดไป >", "NEXT SENSOR >", "下一传感器 >"), 238 + 108, btnY + 18);
    lcd.setTextDatum(textdatum_t::top_left);
}

// ============================================================================
// หน้าต่างย่อยที่ 1: รายละเอียดเซนเซอร์ SHT45 (Air Microclimate & VPD Physics)
// ============================================================================
static void drawPageDetailAir(const FarmSensorTelemetry &data) {
    char buf[80];
    int sy = scrollOffsetY;

    lcd.fillRect(0, 36, 468, 284, COLOR_BG);
    drawDetailHeader("SHT45: บรรยากาศ & ฟิสิกส์ VPD", "SHT45: AIR & VPD PHYSICS", "SHT45: 空气微气候与VPD", COLOR_ACCENT);

    // กำหนดพื้นที่ Clip Rect ให้เลื่อนเนื้อหาโดยไม่ล้นทับ Header
    lcd.setClipRect(0, 36, 468, 282);

    // --- การ์ด 1: ข้อมูลเซนเซอร์สด & เกจวัด (Real-time Gauge) ---
    int c1_y = 42 - sy;
    lcd.fillRoundRect(10, c1_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c1_y, 448, 126, 6, COLOR_ACCENT);
    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_ACCENT, COLOR_CARD_BG);
    lcd.drawString(L_STR("[SHT45] อุณหภูมิและความชื้นสัมพัทธ์ (Sensirion CMOSens)",
                         "[SHT45] TEMPERATURE & RELATIVE HUMIDITY (Sensirion)",
                         "[SHT45] 空气温湿度监测 (Sensirion CMOSens)"), 20, c1_y + 8);

    // บรรทัดตัวเลขหลัก: 🌡️ Temp & 💧 Humidity
    drawIconThermometer(20, c1_y + 32, 0xFD20);
    float fahrenheit = (data.air.temperature * 1.8f) + 32.0f;
    snprintf(buf, sizeof(buf), "%.1f C (%.1f F)", data.air.temperature, fahrenheit);
    lcd.setTextColor(0xFD20, COLOR_CARD_BG);
    lcd.drawString(buf, 36, c1_y + 32);

    drawIconDroplet(230, c1_y + 32, 0x3DFF);
    snprintf(buf, sizeof(buf), "%.1f %%RH", data.air.humidity);
    lcd.setTextColor(0x3DFF, COLOR_CARD_BG);
    lcd.drawString(buf, 246, c1_y + 32);

    // จุดน้ำค้าง Dew Point & ระยะห่างจุดน้ำค้าง
    float dewMargin = data.air.temperature - data.air.dewPoint;
    snprintf(buf, sizeof(buf), "Dew Point: %.1f C | Dew Margin: %.1f C", data.air.dewPoint, dewMargin);
    lcd.setTextColor(0x87F0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c1_y + 60);

    // บาร์กราฟแสดงความชื้นอากาศ (0 - 100%)
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("เกจวัดความชื้น:", "Humidity Gauge:", "湿度刻度:"), 20, c1_y + 84);
    int barW = constrain((int)(data.air.humidity * 3.0f), 0, 300);
    lcd.drawRoundRect(130, c1_y + 84, 310, 14, 3, 0x4208);
    lcd.fillRect(132, c1_y + 86, barW, 10, (data.air.humidity > 80.0f) ? 0xFD20 : 0x07E0);
    lcd.fillRect(132 + barW, c1_y + 86, 306 - barW, 10, 0x0841);

    snprintf(buf, sizeof(buf), "VPsat: %.2f kPa | VPact: %.2f kPa",
             0.61078f * exp((17.27f * data.air.temperature) / (data.air.temperature + 237.3f)),
             (0.61078f * exp((17.27f * data.air.temperature) / (data.air.temperature + 237.3f))) * (data.air.humidity / 100.0f));
    lcd.drawString(buf, 20, c1_y + 106);

    // --- การ์ด 2: คำแนะนำสำหรับเกษตรกร & การคายน้ำ (Agronomic Advice) ---
    int c2_y = 176 - sy;
    lcd.fillRoundRect(10, c2_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c2_y, 448, 126, 6, 0x07E0);
    lcd.setTextColor(0x07E0, COLOR_CARD_BG);
    lcd.drawString(L_STR("คำแนะนำสำหรับเกษตรกร (VPD & สุขภาพพืช):",
                         "AGRONOMIC ADVICE (VPD & TRANSPIRATION):",
                         "农学指导 (VPD 与植物蒸腾健康):"), 20, c2_y + 8);

    snprintf(buf, sizeof(buf), "VPD: %.2f kPa", data.air.vpd);
    lcd.setTextColor(0xFFE0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c2_y + 30);

    if (data.air.vpd >= 0.8f && data.air.vpd <= 1.2f) {
        lcd.fillRoundRect(140, c2_y + 28, 290, 20, 3, 0x0320);
        lcd.drawRoundRect(140, c2_y + 28, 290, 20, 3, 0x07E0);
        lcd.setTextColor(0x07E0, 0x0320);
        lcd.drawString(L_STR("[ สภาวะเหมาะสมสูงสุด ]", "[ Optimal Air Flow ]", "[ 气候适宜 气孔正常 ]"), 146, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ปากใบเปิดเต็มที่ การสังเคราะห์แสงและดูดซึมปุ๋ยดีเยี่ยม",
                             "Advice: Stomata fully open, maximum nutrient & water uptake.",
                             "指导建议: 气孔充分张开，养分与水分输送效率最佳。"), 20, c2_y + 56);
    } else if (data.air.vpd < 0.8f) {
        lcd.fillRoundRect(140, c2_y + 28, 290, 20, 3, 0x3180);
        lcd.drawRoundRect(140, c2_y + 28, 290, 20, 3, 0xFD20);
        lcd.setTextColor(0xFD20, 0x3180);
        lcd.drawString(L_STR("[ ความชื้นสูงเกินไป ]", "[ High Humidity Alert ]", "[ 湿度过高 谨防霉菌 ]"), 146, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: พืชคายน้ำไม่ออก เสี่ยงเชื้อราและขาดแคลเซียม ควรระบายอากาศ",
                             "Advice: Stifled transpiration; risk of fungus & Ca deficiency. Ventilate!",
                             "指导建议: 蒸腾受阻，谨防真菌病害及缺钙，建议通风降湿。"), 20, c2_y + 56);
    } else {
        lcd.fillRoundRect(140, c2_y + 28, 290, 20, 3, 0x3800);
        lcd.drawRoundRect(140, c2_y + 28, 290, 20, 3, COLOR_WARN);
        lcd.setTextColor(COLOR_WARN, 0x3800);
        lcd.drawString(L_STR("[ อากาศแห้ง พืชเครียด ]", "[ Dry Air Stress Alert ]", "[ 气候干燥 蒸腾胁迫 ]"), 146, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ปากใบปิด พืชเหี่ยวเฉา ควรเปิดระบบพ่นหมอกเพิ่มความชื้น",
                             "Advice: Stomata closing to save water; engage misting system!",
                             "指导建议: 气孔紧闭防失水，建议开启喷雾增加湿度。"), 20, c2_y + 56);
    }

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("พ่นหมอกอัตโนมัติ: จะเริ่มทำงานเมื่อ Temp > 35 C หรือ RH < 70%",
                         "Auto Misting triggers when Temp > 35 C or RH < 70%",
                         "智能喷雾联动: 当气温 > 35℃ 或湿度 < 70% 自动启动"), 20, c2_y + 84);
    lcd.drawString(L_STR("ระยะห่างจุดน้ำค้างต่ำกว่า 2 C: มีความเสี่ยงเกิดหยดน้ำเกาะใบ",
                         "Dew margin < 2 C indicates high risk of condensation on leaves.",
                         "露点温差低于 2℃ 时，叶片表面极易结露，需加强通风。"), 20, c2_y + 104);

    // --- การ์ด 3: ข้อมูลทางเทคนิคและฮาร์ดแวร์ I2C (Hardware Diagnostics) ---
    int c3_y = 310 - sy;
    lcd.fillRoundRect(10, c3_y, 448, 118, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c3_y, 448, 118, 6, 0x4208);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("ข้อมูลทางเทคนิค & ฮาร์ดแวร์ I2C:", "TECHNICAL SPECS & I2C HARDWARE:", "技术参数与 I2C 硬件通信:"), 20, c3_y + 8);

    lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
    lcd.drawString("Pinout: SDA=GPIO9 (Yellow), SCL=GPIO8 (Green) | I2C Addr: 0x44", 20, c3_y + 30);
    lcd.drawString(L_STR("ความถี่บัส: 10 kHz ฟิลเตอร์ฮาร์ดแวร์ลดสัญญาณรบกวน | ออนไลน์",
                         "Bus Frequency: 10 kHz Noise-Immune Filter | ONLINE",
                         "总线频率: 10 kHz 硬件抗干扰滤波 | 在线正常"), 20, c3_y + 50);
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString("Precision: +/-0.1 C (Temperature), +/-1.5 %RH (Humidity)", 20, c3_y + 70);
    lcd.drawString(L_STR("การประมวลผล: คำนวณ VPD และ Dew Point ตามมาตรฐาน FAO-56",
                         "Calculations: Real-time VPD & Dew Point by FAO-56 Penman",
                         "算法标准: 采用联合国粮农组织 FAO-56 标准计算"), 20, c3_y + 90);

    // --- แถบปุ่มนำทางด้านล่าง ---
    drawDetailBottomNav(sy, PAGE_DETAIL_LIGHT);

    // ปิด Clip Rect และวาดสไลด์บาร์
    lcd.clearClipRect();
    drawScrollBar(scrollOffsetY, maxScrollY, 38, 280, COLOR_ACCENT);
    pageChanged = false;
}

// ============================================================================
// หน้าต่างย่อยที่ 2: รายละเอียดเซนเซอร์โดมตะวัน BH1750 (Solar Radiation Physics)
// ============================================================================
static void drawPageDetailLight(const FarmSensorTelemetry &data) {
    char buf[80];
    int sy = scrollOffsetY;

    lcd.fillRect(0, 36, 468, 284, COLOR_BG);
    drawDetailHeader("BH1750: รังสีแสงอาทิตย์โดมตะวัน", "BH1750: SUN DOME SOLAR RADIATION", "BH1750: 太阳穹顶光合辐射", COLOR_YELLOW);

    lcd.setClipRect(0, 36, 468, 282);

    // --- การ์ด 1: พลังงานรังสีดวงอาทิตย์หลัก (Hero Metric) ---
    int c1_y = 42 - sy;
    lcd.fillRoundRect(10, c1_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c1_y, 448, 126, 6, COLOR_YELLOW);
    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_YELLOW, COLOR_CARD_BG);
    lcd.drawString(L_STR("[BH1750] ฟลักซ์รังสีดวงอาทิตย์โดมตะวัน (Solar Radiation)",
                         "[BH1750] SUN DOME SOLAR RADIATION FLUX",
                         "[BH1750] 太阳穹顶光合辐射通量密度"), 20, c1_y + 8);

    drawIconSun(20, c1_y + 32, 0xFFE0);
    snprintf(buf, sizeof(buf), "%.1f", data.light.solarRadiation);
    lcd.setTextColor(0xFFE0, COLOR_CARD_BG);
    lcd.drawString(buf, 38, c1_y + 32);
    int sRadW = lcd.textWidth(buf);
    drawUnitW_m2(38 + sRadW + 3, c1_y + 32, 0xFFE0, COLOR_CARD_BG);

    drawIconBulb(230, c1_y + 32, 0xFF30);
    snprintf(buf, sizeof(buf), "%.2f kLux (%.0f Lux)", data.light.lux / 1000.0f, data.light.lux);
    lcd.setTextColor(0xFF30, COLOR_CARD_BG);
    lcd.drawString(buf, 246, c1_y + 32);

    float solarConstRatio = (data.light.solarRadiation / 1361.0f) * 100.0f;
    snprintf(buf, sizeof(buf), "%s: %.1f %% (จาก 1361 W/m2 Max)", L_STR("สัดส่วนคงที่สุริยะ", "Solar Constant Ratio", "太阳能常数占比"), solarConstRatio);
    lcd.setTextColor(0x87F0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c1_y + 60);

    // แถบกราฟความเข้มแสง (0 - 1000 W/m2)
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("ระดับรังสีดวงอาทิตย์:", "Solar Intensity:", "辐射强度:"), 20, c1_y + 84);
    int radBarW = constrain((int)(data.light.solarRadiation * 290.0f / 1000.0f), 0, 290);
    lcd.drawRoundRect(140, c1_y + 84, 300, 14, 3, 0x4208);
    lcd.fillRect(142, c1_y + 86, radBarW, 10, 0xFDE0);
    lcd.fillRect(142 + radBarW, c1_y + 86, 296 - radBarW, 10, 0x0841);

    snprintf(buf, sizeof(buf), "Optical Calibration: 1 Lux = 0.0079 W/m2 (Cosine Filtered)");
    lcd.drawString(buf, 20, c1_y + 106);

    // --- การ์ด 2: คำแนะนำการสังเคราะห์แสง & PAR (Photobiology Advice) ---
    int c2_y = 176 - sy;
    lcd.fillRoundRect(10, c2_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c2_y, 448, 126, 6, 0x07E0);
    lcd.setTextColor(0x07E0, COLOR_CARD_BG);
    lcd.drawString(L_STR("คำแนะนำการสังเคราะห์แสง (PAR & DLI):",
                         "AGRONOMIC ADVICE (PAR & DAILY LIGHT INTEGRAL):",
                         "光生物学指导 (PAR 光合辐射与日积光照):"), 20, c2_y + 8);

    float estPAR = data.light.solarRadiation * 2.1f;
    snprintf(buf, sizeof(buf), "PAR (PPFD): ~%.0f umol/(m2*s)", estPAR);
    lcd.setTextColor(0xFFE0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c2_y + 30);

    if (data.light.lux < 500.0f) {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x1104);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0x541F);
        lcd.setTextColor(0x541F, 0x1104);
        lcd.drawString(L_STR("[ แดดร่ม พืชพักตัว ]", "[ Shade / Resting ]", "[ 遮阴阴天 植物休眠 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: แสงน้อยเกินไป พืชหยุดสร้างแป้งและน้ำตาล ไม่ควรให้น้ำมาก",
                             "Advice: Low photon flux; photosynthesis halted. Conserve water.",
                             "指导建议: 光照不足，光合速率极低，植物休眠，避免多浇水。"), 20, c2_y + 56);
    } else if (data.light.lux < 30000.0f) {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x0320);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0x07E0);
        lcd.setTextColor(0x07E0, 0x0320);
        lcd.drawString(L_STR("[ แดดพอเหมาะ สมบูรณ์ ]", "[ Optimal Sunlight ]", "[ 光照充足 光合优良 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: โดมตะวันรับแสงเหมาะสม พืชสังเคราะห์แสงสะสมผลผลิตได้เต็มที่",
                             "Advice: Optimal photons for crop biomass and sugar accumulation.",
                             "指导建议: 光合有效辐射极佳，光合效率处于黄金峰值。"), 20, c2_y + 56);
    } else {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x3180);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0xFFE0);
        lcd.setTextColor(0xFFE0, 0x3180);
        lcd.drawString(L_STR("[ แดดจัดมาก ระวังใบไหม้ ]", "[ Intense Sun Alert ]", "[ 强光暴晒 谨防灼伤 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: แดดเข้มข้นจัด แนะนำกางสแลนพรางแสง 50% หรือเปิดพ่นหมอกลดความร้อน",
                             "Advice: Intense photon stress! Deploy 50% shade cloth or mist.",
                             "指导建议: 强光伴随高温，建议拉上50%遮阳网或喷雾降温。"), 20, c2_y + 56);
    }

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("ดัชนี DLI: คำนวณปริมาณโมลของแสงต่อตารางเมตรต่อวันสำหรับพืชผล",
                         "Daily Light Integral (DLI) gauges total photon energy received per day.",
                         "DLI 日累积光效: 决定作物的最终产量与开花挂果品质。"), 20, c2_y + 84);
    lcd.drawString(L_STR("โดมตะวัน: ตัวรับแสงทรงโดมรับแสงได้ทุกทิศทาง 360 องศา",
                         "Sun Dome: 360-degree all-weather optical dome diffuser.",
                         "穹顶设计: 全天候 360 度半球全景采光漫反射。"), 20, c2_y + 104);

    // --- การ์ด 3: ข้อมูลทางเทคนิคและฮาร์ดแวร์ I2C ---
    int c3_y = 310 - sy;
    lcd.fillRoundRect(10, c3_y, 448, 118, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c3_y, 448, 118, 6, 0x4208);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("ข้อมูลทางเทคนิค & เซนเซอร์ BH1750:", "TECHNICAL SPECS & SENSOR BUS:", "技术规格与传感器总线:"), 20, c3_y + 8);

    lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
    lcd.drawString("Pinout: SDA=GPIO9, SCL=GPIO8 | I2C Addr: 0x23", 20, c3_y + 30);
    lcd.drawString(L_STR("ชิปเซนเซอร์: ROHM BH1750FVI 16-bit Ambient Light Sensor",
                         "Sensor IC: ROHM BH1750FVI 16-bit Ambient Light Sensor",
                         "传感器芯片: 罗姆 ROHM BH1750FVI 16位高精度光敏芯片"), 20, c3_y + 50);
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString("Dynamic Range: 1 - 65,535 Lux | High-Resolution Mode (1 Lux)", 20, c3_y + 70);
    lcd.drawString(L_STR("การป้องกัน: โดมอะคริลิกกันน้ำกันละอองฝนมาตรฐาน IP65",
                         "Enclosure: Weatherproof IP65 optical acrylic dome diffuser",
                         "防护等级: IP65 级全天候防水防尘光学穹顶"), 20, c3_y + 90);

    drawDetailBottomNav(sy, PAGE_DETAIL_SOIL1);

    lcd.clearClipRect();
    drawScrollBar(scrollOffsetY, maxScrollY, 38, 280, COLOR_YELLOW);
    pageChanged = false;
}

// ============================================================================
// หน้าต่างย่อยที่ 3: รายละเอียด Soil Stick (Surface Moisture & ADC Calibration)
// ============================================================================
static void drawPageDetailSoil1(const FarmSensorTelemetry &data) {
    char buf[80];
    int sy = scrollOffsetY;

    lcd.fillRect(0, 36, 468, 284, COLOR_BG);
    drawDetailHeader("SOIL STICK: ดินตื้น 0-10ซม.", "SOIL STICK: SURFACE MOISTURE", "SOIL STICK: 表层土壤湿度 0-10cm", COLOR_CYAN);

    lcd.setClipRect(0, 36, 468, 282);

    // --- การ์ด 1: ความชื้นผิวดินสด & เกจวัด (Real-time Gauge) ---
    int c1_y = 42 - sy;
    lcd.fillRoundRect(10, c1_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c1_y, 448, 126, 6, COLOR_CYAN);
    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("[Soil Stick] ความชื้นผิวดินชั้นตื้น (0 - 10 ซม.)",
                         "[Soil Stick] TOPSOIL SURFACE MOISTURE (0 - 10 CM)",
                         "[Soil Stick] 表层土壤湿度检测 (0 - 10 厘米)"), 20, c1_y + 8);

    drawIconSprout(20, c1_y + 32, 0x3DFF);
    snprintf(buf, sizeof(buf), "%.1f %% (%s)", data.soilStick.moisture, L_STR("ความชื้นผิวดิน", "Moisture", "湿度"));
    lcd.setTextColor(0x3DFF, COLOR_CARD_BG);
    lcd.drawString(buf, 38, c1_y + 32);

    float volts = (data.soilStick.rawAdc * 3.3f) / 4095.0f;
    snprintf(buf, sizeof(buf), "Analog: %.2f V | Raw ADC: %d", volts, data.soilStick.rawAdc);
    lcd.setTextColor(0xFD20, COLOR_CARD_BG);
    lcd.drawString(buf, 230, c1_y + 32);

    snprintf(buf, sizeof(buf), "%s: %s", L_STR("เกณฑ์วิกฤต", "Trigger Level", "浇水阈值"), "< 40.0% [เริ่มรดน้ำ]");
    lcd.setTextColor(0x87F0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c1_y + 60);

    // บาร์กราฟความชื้น
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("เกจระดับความชื้น:", "Moisture Gauge:", "土壤湿度刻度:"), 20, c1_y + 84);
    int barW = constrain((int)(data.soilStick.moisture * 3.0f), 0, 300);
    lcd.drawRoundRect(130, c1_y + 84, 310, 14, 3, 0x4208);
    lcd.fillRect(132, c1_y + 86, barW, 10, (data.soilStick.moisture < 40.0f) ? COLOR_WARN : 0x07E0);
    lcd.fillRect(132 + barW, c1_y + 86, 306 - barW, 10, 0x0841);

    snprintf(buf, sizeof(buf), "Dry Calibration: 3280 ADC | Wet Calibration: 1320 ADC");
    lcd.drawString(buf, 20, c1_y + 106);

    // --- การ์ด 2: คำแนะนำการให้น้ำ & ระบบอัตโนมัติ (Agronomic Advice) ---
    int c2_y = 176 - sy;
    lcd.fillRoundRect(10, c2_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c2_y, 448, 126, 6, 0x07E0);
    lcd.setTextColor(0x07E0, COLOR_CARD_BG);
    lcd.drawString(L_STR("คำแนะนำการให้น้ำ & กลยุทธ์อัตโนมัติ:",
                         "AGRONOMIC ADVICE (IRRIGATION & AUTOMATION):",
                         "灌溉指导与自动化控制策略:"), 20, c2_y + 8);

    if (data.soilStick.moisture < 40.0f) {
        lcd.fillRoundRect(20, c2_y + 28, 220, 20, 3, 0x3800);
        lcd.drawRoundRect(20, c2_y + 28, 220, 20, 3, COLOR_WARN);
        lcd.setTextColor(COLOR_WARN, 0x3800);
        lcd.drawString(L_STR("[ ดินแห้งแล้ง: แนะนำรดน้ำ ]", "[ DRY SOIL: Water Now ]", "[ 土壤干旱: 建议浇水 ]"), 26, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ดินชั้นตื้นสูญเสียความชื้นสูง ระบบเริ่มรดน้ำอัตโนมัติ",
                             "Advice: Topsoil depleted below threshold; automated pump activated.",
                             "指导建议: 表层土壤严重缺水，智能自控系统已激活水泵灌溉。"), 20, c2_y + 56);
    } else if (data.soilStick.moisture <= 70.0f) {
        lcd.fillRoundRect(20, c2_y + 28, 220, 20, 3, 0x0320);
        lcd.drawRoundRect(20, c2_y + 28, 220, 20, 3, 0x07E0);
        lcd.setTextColor(0x07E0, 0x0320);
        lcd.drawString(L_STR("[ ชุ่มชื้นดี (งดการรดน้ำ) ]", "[ OPTIMAL MOISTURE: OK ]", "[ 水分适宜 (暂缓浇水) ]"), 26, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: รากพืชดูดซึมน้ำและปุ๋ยได้สะดวก งดการให้น้ำเพิ่มเพื่อประหยัดน้ำ",
                             "Advice: Ideal root moisture; maintain water hold to conserve resources.",
                             "指导建议: 水分充足透气良好，暂缓浇水以节约水资源。"), 20, c2_y + 56);
    } else {
        lcd.fillRoundRect(20, c2_y + 28, 220, 20, 3, 0x0214);
        lcd.drawRoundRect(20, c2_y + 28, 220, 20, 3, COLOR_CYAN);
        lcd.setTextColor(COLOR_CYAN, 0x0214);
        lcd.drawString(L_STR("[ ดินแฉะเกินไป: ระวังรากเน่า ]", "[ WATERLOGGED ALERT ]", "[ 土壤过湿: 谨防沤根 ]"), 26, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ดินอิ่มตัวด้วยน้ำมากเกินไป เสี่ยงรากขาดอากาศ ควรพรวนดินระบายน้ำ",
                             "Advice: Saturated soil reduces root oxygen. Hold watering immediately!",
                             "指导建议: 水分过饱和导致根系缺氧，立即停止灌溉并通风透气。"), 20, c2_y + 56);
    }

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("ระบบอัตโนมัติ: รดน้ำเมื่อ < 40.0% --> ตัดน้ำเมื่อถึง 65.0%",
                         "Automation Rule: Water ON < 40.0% --> OFF at >= 65.0% (Hysteresis)",
                         "智能滞回自控: 湿度低于 40% 启动 --> 达到 65% 停止"), 20, c2_y + 84);
    lcd.drawString(L_STR("ระบบความปลอดภัย: ตัดการทำงานปั๊มฉุกเฉินภายใน 5 นาที ป้องกันปั๊มไหม้",
                         "Safety Feature: 5-Minute maximum continuous run protects pump motor.",
                         "安全保障机制: 5分钟连续运行强制保护关断，防止水泵干烧。"), 20, c2_y + 104);

    // --- การ์ด 3: ข้อมูลฮาร์ดแวร์และการสอบเทียบ ADC ---
    int c3_y = 310 - sy;
    lcd.fillRoundRect(10, c3_y, 448, 118, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c3_y, 448, 118, 6, 0x4208);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("ข้อมูลฮาร์ดแวร์ & เซนเซอร์คาปาซิทีฟ:", "HARDWARE SPECS & CAPACITIVE SENSING:", "硬件参数与电容感应原理:"), 20, c3_y + 8);

    lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
    lcd.drawString("Pinout: ESP32-S3 Port A1 (GPIO 1) | 12-bit ADC (0 - 4095)", 20, c3_y + 30);
    lcd.drawString(L_STR("หลักการทำงาน: แผ่นทองแดงวัดความจุไฟฟ้า ไร้การกัดกร่อนจากกระแสตรง",
                         "Working Principle: High-frequency capacitance (Corrosion-Free)",
                         "感应原理: 高频交变电容测量，无直流电解腐蚀，寿命长"), 20, c3_y + 50);
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString("Calibration Map: Dry Air = 3280 ADC | 100% Water = 1320 ADC", 20, c3_y + 70);
    lcd.drawString(L_STR("ชั้นดิน: ตรวจจับชั้นผิวดิน 0-10 ซม. ซึ่งเป็นด่านแรกของการระเหยน้ำ",
                         "Topsoil Layer: Monitors 0-10 cm zone where solar evaporation peaks.",
                         "监测区位: 专注 0-10cm 表层土壤，此处水分蒸发最快。"), 20, c3_y + 90);

    drawDetailBottomNav(sy, PAGE_DETAIL_SOIL7);

    lcd.clearClipRect();
    drawScrollBar(scrollOffsetY, maxScrollY, 38, 280, COLOR_CYAN);
    pageChanged = false;
}

// ============================================================================
// หน้าต่างย่อยที่ 4: รายละเอียด Soil 7-in-1 (Root Zone & NPK Macronutrients)
// ============================================================================
static void drawPageDetailSoil7(const FarmSensorTelemetry &data) {
    char buf[80];
    int sy = scrollOffsetY;

    lcd.fillRect(0, 36, 468, 284, COLOR_BG);
    drawDetailHeader("SOIL 7-IN-1: เขตราก & ธาตุอาหาร NPK", "SOIL 7-IN-1: ROOT ZONE & NPK", "SOIL 7合1: 根系深层与 NPK 养分", 0x07E0);

    lcd.setClipRect(0, 36, 468, 282);

    // --- การ์ด 1: ข้อมูลเขตรากพืชลึก (Root Zone Metrics) ---
    int c1_y = 42 - sy;
    lcd.fillRoundRect(10, c1_y, 448, 186, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c1_y, 448, 186, 6, 0x07E0);
    lcd.setTextSize(1);
    lcd.setTextColor(0x07E0, COLOR_CARD_BG);
    lcd.drawString(L_STR("[Soil 7-in-1] คุณสมบัติเขตรากพืชลึก (Root Zone Physics)",
                         "[Soil 7-in-1] ROOT ZONE MULTI-PHYSICS TELEMETRY",
                         "[Soil 7合1] 根系深层多参数物理遥测"), 20, c1_y + 8);

    drawIconFlask(20, c1_y + 32, 0xF41F);
    snprintf(buf, sizeof(buf), "%s: %.1f %%", L_STR("ชื้นราก", "Moist", "根区湿度"), data.soil7in1.moisture);
    lcd.setTextColor(0x3DFF, COLOR_CARD_BG);
    lcd.drawString(buf, 38, c1_y + 32);

    drawIconThermometer(170, c1_y + 32, 0xFD20);
    snprintf(buf, sizeof(buf), "%s: %.1f C", L_STR("อุณหภูมิดิน", "Temp", "土壤温度"), data.soil7in1.temperature);
    lcd.setTextColor(0xFD20, COLOR_CARD_BG);
    lcd.drawString(buf, 186, c1_y + 32);

    snprintf(buf, sizeof(buf), "pH: %.2f", data.soil7in1.ph);
    lcd.setTextColor(0xEA80, COLOR_CARD_BG);
    lcd.drawString(buf, 320, c1_y + 32);

    snprintf(buf, sizeof(buf), "EC: %.0f uS/cm | TDS: %.0f ppm", data.soil7in1.ec, data.soil7in1.ec * 0.64f);
    lcd.setTextColor(0xFDE0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c1_y + 60);

    // ธาตุอาหาร NPK ดิบจากหัววัด
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("ธาตุอาหารหลัก NPK ดิบ (มก./กก.):", "Raw Sensor NPK (mg/kg):", "传感器原始 NPK (mg/kg):"), 20, c1_y + 84);

    snprintf(buf, sizeof(buf), "N: %.0f", data.soil7in1.nitrogen);
    lcd.setTextColor(0x9CD3, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c1_y + 104);

    snprintf(buf, sizeof(buf), "P: %.0f", data.soil7in1.phosphorus);
    lcd.setTextColor(0x9CD3, COLOR_CARD_BG);
    lcd.drawString(buf, 150, c1_y + 104);

    snprintf(buf, sizeof(buf), "K: %.0f", data.soil7in1.potassium);
    lcd.setTextColor(0x9CD3, COLOR_CARD_BG);
    lcd.drawString(buf, 270, c1_y + 104);

    // เส้นคั่นใน Card 1
    lcd.drawFastHLine(20, c1_y + 124, 428, 0x2124);

    // กล่องไฮไลต์ TinyML Edge AI Calibrated (ชดเชยอุณหภูมิและความชื้น)
    lcd.fillRoundRect(20, c1_y + 128, 428, 48, 4, 0x0182);
    lcd.drawRoundRect(20, c1_y + 128, 428, 48, 4, 0x07E0);
    lcd.setTextColor(0x07E0, 0x0182);
    lcd.drawString(L_STR("[TinyML Edge AI] ชดเชยอุณหภูมิและความชื้น (Neural Calibrated):",
                         "[TinyML Edge AI] Temp & Moisture Decoupled Telemetry:",
                         "[TinyML 边缘AI] 实时温湿度神经网络去噪校准:"), 26, c1_y + 132, &fonts::Font0);

    snprintf(buf, sizeof(buf), "AI-N: %.1f | AI-P: %.1f | AI-K: %.1f | AI-pH: %.2f | True Moist: %.1f%%",
             data.aiCalibrated.nitrogen,
             data.aiCalibrated.phosphorus,
             data.aiCalibrated.potassium,
             data.aiCalibrated.ph,
             data.aiCalibrated.moisture);
    lcd.setTextColor(0xFFFF, 0x0182);
    lcd.drawString(buf, 26, c1_y + 152, &fonts::Font0);

    // --- การ์ด 2: ธาตุอาหารหลัก NPK & คำแนะนำการใส่ปุ๋ย (Agronomic Advice) ---
    int c2_y = 238 - sy;
    lcd.fillRoundRect(10, c2_y, 448, 126, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c2_y, 448, 126, 6, COLOR_YELLOW);
    lcd.setTextColor(COLOR_YELLOW, COLOR_CARD_BG);
    lcd.drawString(L_STR("คำแนะนำการใส่ปุ๋ย & ปรับปรุงสภาพดิน:",
                         "AGRONOMIC ADVICE (FERTILIZER & pH SOIL MANAGEMENT):",
                         "土壤改良与施肥指导:"), 20, c2_y + 8);

    float totalNPK = data.aiCalibrated.nitrogen + data.aiCalibrated.phosphorus + data.aiCalibrated.potassium;
    snprintf(buf, sizeof(buf), "Total Available NPK (AI): %.0f mg/kg", totalNPK);
    lcd.setTextColor(0xFFE0, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c2_y + 30);

    if (data.aiCalibrated.ph < 5.5f) {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x3180);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0xFD20);
        lcd.setTextColor(0xFD20, 0x3180);
        lcd.drawString(L_STR("[ ดินกรดจัด: เสี่ยงขาดฟอสฟอรัส ]", "[ ACID SOIL ALERT ]", "[ 酸性土: 磷素易被固定 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: pH ต่ำกว่า 5.5 พืชดูดฟอสฟอรัสไม่ได้ แนะนำใส่ปูนขาว/โดโลไมท์",
                             "Advice: Low pH locks phosphorus. Apply agricultural lime/dolomite.",
                             "指导建议: pH 低于 5.5 导致磷被固化，建议撒施生石灰或白云石粉调节。"), 20, c2_y + 56);
    } else if (data.aiCalibrated.ph > 7.5f) {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x3180);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0xFD20);
        lcd.setTextColor(0xFD20, 0x3180);
        lcd.drawString(L_STR("[ ดินเป็นด่าง: ขาดจุลธาตุ ]", "[ ALKALINE SOIL ALERT ]", "[ 碱性土: 谨防缺微量元素 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ดินด่าง พืชขาดธาตุเหล็กและสังกะสี แนะนำเติมปุ๋ยอินทรีย์/ฮิวมัส",
                             "Advice: Alkaline soil restricts Fe & Zn uptake. Apply organic compost.",
                             "指导建议: 偏碱性土壤易引发缺铁缺锌，建议施用腐殖酸有机肥改良。"), 20, c2_y + 56);
    } else if (totalNPK < 100.0f) {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x3800);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, COLOR_WARN);
        lcd.setTextColor(COLOR_WARN, 0x3800);
        lcd.drawString(L_STR("[ สารอาหารต่ำ: แนะนำเติมปุ๋ย ]", "[ LOW NPK: Fertilize ]", "[ 养分匮乏: 建议补肥 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ปริมาณธาตุอาหารหลักไม่เพียงพอ แนะนำเติมปุ๋ยสูตรเสมอ หรือน้ำหมัก",
                             "Advice: Nutrient reserve depleted. Apply balanced NPK fertilizer.",
                             "指导建议: 大量元素储备不足，建议按需追施平衡复合肥或水溶肥。"), 20, c2_y + 56);
    } else {
        lcd.fillRoundRect(220, c2_y + 28, 210, 20, 3, 0x0320);
        lcd.drawRoundRect(220, c2_y + 28, 210, 20, 3, 0x07E0);
        lcd.setTextColor(0x07E0, 0x0320);
        lcd.drawString(L_STR("[ ดินสมบูรณ์: ธาตุอาหารพร้อม ]", "[ OPTIMAL FERTILITY: OK ]", "[ 土壤肥沃: 养分充足 ]"), 226, c2_y + 32);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("คำแนะนำ: ดินมีความสมบูรณ์สูง ค่า pH และความเค็ม EC เหมาะสมกับการเติบโต",
                             "Advice: High soil fertility with optimal pH and EC conductivity balance.",
                             "指导建议: 土壤理化性质优良，养分储备充足，维持常规管理即可。"), 20, c2_y + 56);
    }

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(L_STR("ความเค็ม EC: ถ้าเกิน 2000 uS/cm ระวังดินเค็ม พืชจะดูดน้ำลำบาก",
                         "EC Conductivity: High EC (>2000 uS/cm) risks salt stress to rootlets.",
                         "EC 电导率警示: 若超过 2000 uS/cm 谨防盐渍化导致根系脱水。"), 20, c2_y + 84);
    lcd.drawString(L_STR("สัดส่วน N-P-K: ช่วยตัดสินใจในการให้ปุ๋ยตามช่วงวัยของพืช",
                         "NPK ratio informs precise fertigation schedule matching growth stages.",
                         "NPK 营养比例可为精准水肥一体化提供科学决策依据。"), 20, c2_y + 104);

    // --- การ์ด 3: ข้อมูลสัญญาณอุตสาหกรรม RS485 MODBUS RTU ---
    int c3_y = 374 - sy;
    lcd.fillRoundRect(10, c3_y, 448, 110, 6, COLOR_CARD_BG);
    lcd.drawRoundRect(10, c3_y, 448, 110, 6, 0x4208);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("ข้อมูลสัญญาณอุตสาหกรรม RS485 MODBUS RTU:",
                         "INDUSTRIAL RS485 MODBUS RTU TELEMETRY:",
                         "工业级 RS485 MODBUS RTU 通信遥测:"), 20, c3_y + 8);

    lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
    lcd.drawString("UART2 Port: TX=GPIO40, RX=GPIO41 | Baud 9600-8-N-1", 20, c3_y + 28);
    lcd.drawString("Modbus Protocol: Slave ID 0x01 | Function 0x03 (Holding Regs)", 20, c3_y + 48);

    snprintf(buf, sizeof(buf), "%s: %s | CRC16 Checksum: %s",
             L_STR("สถานะ", "Status", "状态"),
             data.soil7in1.isConnected ? L_STR("ออนไลน์ (ปกติ)", "ONLINE (OK)", "在线 (正常)") : L_STR("ขาดการเชื่อมต่อ", "DISCONNECTED", "断开连接"),
             (data.soil7in1.readErrorCount == 0) ? "PASS" : "RETRY");
    lcd.setTextColor(data.soil7in1.isConnected ? 0x07E0 : COLOR_WARN, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c3_y + 68);

    snprintf(buf, sizeof(buf), "Error Packet Count: %u | Modbus Probe Material: 316L Stainless Steel", data.soil7in1.readErrorCount);
    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString(buf, 20, c3_y + 88);

    drawDetailBottomNav(sy, PAGE_DETAIL_AIR, 494);

    lcd.clearClipRect();
    drawScrollBar(scrollOffsetY, maxScrollY, 38, 280, 0x07E0);
    pageChanged = false;
}

// ============================================================================
// หน้าที่ 2: กราฟแนวโน้มอนุกรมเวลาสด (Live Trend & Sparkline Graphs)
// ============================================================================
static void drawPageGraphs(const FarmSensorTelemetry &data) {
    char buf[64];

    if (pageChanged) {
        lcd.fillRect(0, 36, 480, 284, COLOR_BG);
        pageChanged = false;
    }

    // หัวข้อหน้า
    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_YELLOW, COLOR_BG);
    lcd.drawString(L_STR("กราฟแนวโน้มข้อมูลสด (60 จุดล่าสุด)", "LIVE TREND GRAPHS (LAST 60 POINTS)", "实时历史趋势图 (最近60个数据点)"), 12, 40);

    // กราฟ 1: ความชื้นดิน
    const int g1_x = 35;
    const int g1_y = 54;
    const int g1_w = 430;
    const int g1_h = 100;

    lcd.fillRoundRect(g1_x - 5, g1_y - 2, g1_w + 10, g1_h + 18, 4, COLOR_CARD_BG);
    lcd.drawRoundRect(g1_x - 5, g1_y - 2, g1_w + 10, g1_h + 18, 4, 0x31A6);

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString("100%", 4, g1_y);
    lcd.drawString(" 50%", 4, g1_y + (g1_h / 2) - 4);
    lcd.drawString("  0%", 4, g1_y + g1_h - 8);

    lcd.drawLine(g1_x, g1_y, g1_x + g1_w, g1_y, 0x2104);
    lcd.drawLine(g1_x, g1_y + (g1_h / 2), g1_x + g1_w, g1_y + (g1_h / 2), 0x2104);
    lcd.drawLine(g1_x, g1_y + g1_h, g1_x + g1_w, g1_y + g1_h, 0x4208);

    int dangerY = g1_y + g1_h - (int)(0.40f * g1_h);
    for (int dx = g1_x; dx < g1_x + g1_w; dx += 8) {
        lcd.drawLine(dx, dangerY, dx + 4, dangerY, COLOR_WARN);
    }

    if (historyCount >= 2) {
        float stepX = (float)g1_w / (float)(HISTORY_LEN - 1);
        int startIdx = (historyHead - historyCount + HISTORY_LEN) % HISTORY_LEN;

        for (int i = 0; i < historyCount - 1; i++) {
            int idx1 = (startIdx + i) % HISTORY_LEN;
            int idx2 = (startIdx + i + 1) % HISTORY_LEN;

            int x1 = g1_x + (int)(i * stepX);
            int x2 = g1_x + (int)((i + 1) * stepX);

            int y1_surf = g1_y + g1_h - constrain((int)(history[idx1].soilSurface * g1_h / 100.0f), 0, g1_h);
            int y2_surf = g1_y + g1_h - constrain((int)(history[idx2].soilSurface * g1_h / 100.0f), 0, g1_h);
            lcd.drawLine(x1, y1_surf, x2, y2_surf, 0x07E0);

            int y1_deep = g1_y + g1_h - constrain((int)(history[idx1].soilDeep * g1_h / 100.0f), 0, g1_h);
            int y2_deep = g1_y + g1_h - constrain((int)(history[idx2].soilDeep * g1_h / 100.0f), 0, g1_h);
            lcd.drawLine(x1, y1_deep, x2, y2_deep, COLOR_CYAN);
        }
    }

    lcd.setTextSize(1);
    lcd.setTextColor(0x07E0, COLOR_CARD_BG);
    lcd.drawString(L_STR("- ดินตื้น (Stick)", "- Surface (Stick)", "- 表层土壤 (Stick)"), g1_x + 8, g1_y + g1_h + 3);
    lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
    lcd.drawString(L_STR("- ดินลึก (7-in-1)", "- Deep Root (7-in-1)", "- 深层根系 (7合1)"), g1_x + 135, g1_y + g1_h + 3);
    lcd.setTextColor(COLOR_WARN, COLOR_CARD_BG);
    lcd.drawString(L_STR("--- วิกฤต (<40%)", "--- Danger (<40%)", "--- 缺水警戒 (<40%)"), g1_x + 280, g1_y + g1_h + 3);

    // กราฟ 2: รังสีดวงอาทิตย์
    const int g2_x = 35;
    const int g2_y = 180;
    const int g2_w = 430;
    const int g2_h = 90;

    lcd.fillRoundRect(g2_x - 5, g2_y - 2, g2_w + 10, g2_h + 18, 4, COLOR_CARD_BG);
    lcd.drawRoundRect(g2_x - 5, g2_y - 2, g2_w + 10, g2_h + 18, 4, 0x31A6);

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
    lcd.drawString("1000", 2, g2_y);
    lcd.drawString(" 500", 2, g2_y + (g2_h / 2) - 4);
    lcd.drawString("   0", 2, g2_y + g2_h - 8);

    lcd.drawLine(g2_x, g2_y, g2_x + g2_w, g2_y, 0x2104);
    lcd.drawLine(g2_x, g2_y + (g2_h / 2), g2_x + g2_w, g2_y + (g2_h / 2), 0x2104);
    lcd.drawLine(g2_x, g2_y + g2_h, g2_x + g2_w, g2_y + g2_h, 0x4208);

    if (historyCount >= 2) {
        float stepX = (float)g2_w / (float)(HISTORY_LEN - 1);
        int startIdx = (historyHead - historyCount + HISTORY_LEN) % HISTORY_LEN;

        for (int i = 0; i < historyCount - 1; i++) {
            int idx1 = (startIdx + i) % HISTORY_LEN;
            int idx2 = (startIdx + i + 1) % HISTORY_LEN;

            int x1 = g2_x + (int)(i * stepX);
            int x2 = g2_x + (int)((i + 1) * stepX);

            int y1_rad = g2_y + g2_h - constrain((int)(history[idx1].solarRad * g2_h / 1000.0f), 0, g2_h);
            int y2_rad = g2_y + g2_h - constrain((int)(history[idx2].solarRad * g2_h / 1000.0f), 0, g2_h);
            lcd.drawLine(x1, y1_rad, x2, y2_rad, COLOR_YELLOW);
        }
    }

    snprintf(buf, sizeof(buf), "%s: %.1f W/m2 | %s: %.1f C",
             L_STR("รังสี", "Solar", "光合辐射"), data.light.solarRadiation,
             L_STR("อุณหภูมิอากาศ", "Air Temp", "气温"), data.air.temperature);
    lcd.setTextColor(COLOR_YELLOW, COLOR_CARD_BG);
    lcd.drawString(buf, g2_x + 8, g2_y + g2_h + 3);

    lcd.setTextColor(COLOR_TEXT_DIM, COLOR_BG);
    lcd.drawString(L_STR("แตะแท็บด้านบนเพื่อสลับหน้าจอ", "TAP TOP TABS TO SWITCH PAGES", "点击顶部标签切换页面"), 130, 305);
}

// ============================================================================
// หน้าที่ 3: แผงควบคุมรีเลย์ & ระบบอัตโนมัติ (Relay Control & Smart Automation)
// ============================================================================
static void drawPageRelays(bool pumpState, bool mistingState) {
    if (pageChanged) {
        lcd.fillRect(0, 36, 480, 284, COLOR_BG);
        pageChanged = false;
    }

    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_ACCENT, COLOR_BG);
    lcd.drawString(L_STR("แผงควบคุมสวิตช์รีเลย์ & ระบบอัตโนมัติ", "MANUAL & AUTOMATIC RELAY CONTROL", "继电器手动与智能自控面板"), 14, 42);

    int r1_bg = pumpState ? 0x0320 : COLOR_CARD_BG;
    int r1_border = pumpState ? 0x07E0 : COLOR_TEXT_DIM;
    lcd.fillRoundRect(14, 60, 218, 70, 6, r1_bg);
    lcd.drawRoundRect(14, 60, 218, 70, 6, r1_border);
    lcd.setTextSize(1);
    lcd.setTextColor(pumpState ? 0x07E0 : COLOR_TEXT_VAL, r1_bg);
    lcd.drawString(pumpState ? L_STR("ปั๊มน้ำ 1: เปิด [ทำงาน]", "PUMP 1: ON [RUNNING]", "水泵 1: 开启 [运行中]") : L_STR("ปั๊มน้ำ 1: ปิด [สแตนด์บาย]", "PUMP 1: OFF [STANDBY]", "水泵 1: 关闭 [待机]"), 24, 72);
    lcd.setTextColor(COLOR_TEXT_DIM, r1_bg);
    lcd.drawString("Relay O1 (GPIO 39) | Safety Cutoff 5m", 24, 98);

    bool r2_state = (digitalRead(RELAY_2_PIN) == HIGH);
    int r2_bg = r2_state ? 0x0320 : COLOR_CARD_BG;
    int r2_border = r2_state ? 0x07E0 : COLOR_TEXT_DIM;
    lcd.fillRoundRect(248, 60, 218, 70, 6, r2_bg);
    lcd.drawRoundRect(248, 60, 218, 70, 6, r2_border);
    lcd.setTextColor(r2_state ? 0x07E0 : COLOR_TEXT_VAL, r2_bg);
    lcd.drawString(r2_state ? L_STR("ปั๊มน้ำ 2: เปิด [ทำงาน]", "PUMP 2: ON [RUNNING]", "水泵 2: 开启 [运行中]") : L_STR("ปั๊มน้ำ 2: ปิด [สแตนด์บาย]", "PUMP 2: OFF [STANDBY]", "水泵 2: 关闭 [待机]"), 258, 72);
    lcd.setTextColor(COLOR_TEXT_DIM, r2_bg);
    lcd.drawString("Relay O2 (GPIO 38) | Solenoid Valve", 258, 98);

    bool r3_state = (digitalRead(RELAY_3_PIN) == HIGH);
    int r3_bg = r3_state ? 0x0320 : COLOR_CARD_BG;
    int r3_border = r3_state ? 0x07E0 : COLOR_TEXT_DIM;
    lcd.fillRoundRect(14, 140, 218, 70, 6, r3_bg);
    lcd.drawRoundRect(14, 140, 218, 70, 6, r3_border);
    lcd.setTextColor(r3_state ? 0x07E0 : COLOR_TEXT_VAL, r3_bg);
    lcd.drawString(r3_state ? L_STR("วาล์วน้ำ: เปิด [ไหล]", "VALVE: ON [OPEN]", "电磁阀: 开启 [通水]") : L_STR("วาล์วน้ำ: ปิด [ปิดสนิท]", "VALVE: OFF [CLOSED]", "电磁阀: 关闭 [切断]"), 24, 152);
    lcd.setTextColor(COLOR_TEXT_DIM, r3_bg);
    lcd.drawString("Relay O3 (GPIO 7)  | Surface Valve", 24, 178);

    int r4_bg = mistingState ? 0x0320 : COLOR_CARD_BG;
    int r4_border = mistingState ? 0x07E0 : COLOR_TEXT_DIM;
    lcd.fillRoundRect(248, 140, 218, 70, 6, r4_bg);
    lcd.drawRoundRect(248, 140, 218, 70, 6, r4_border);
    lcd.setTextColor(mistingState ? 0x07E0 : COLOR_TEXT_VAL, r4_bg);
    lcd.drawString(mistingState ? L_STR("พ่นหมอก: เปิด [ทำงาน]", "MISTING: ON [ACTIVE]", "喷雾: 开启 [降温中]") : L_STR("พ่นหมอก: ปิด [สแตนด์บาย]", "MISTING: OFF [STANDBY]", "喷雾: 关闭 [待机]"), 258, 152);
    lcd.setTextColor(COLOR_TEXT_DIM, r4_bg);
    lcd.drawString("Relay O4 (GPIO 6)  | Misting Cooler", 258, 178);

    lcd.fillRoundRect(14, 222, 452, 60, 6, 0x0184);
    lcd.drawRoundRect(14, 222, 452, 60, 6, COLOR_CYAN);
    lcd.setTextSize(1);
    lcd.setTextColor(COLOR_CYAN, 0x0184);
    lcd.drawString(L_STR("เงื่อนไขการทำงานอัตโนมัติ (Smart Rules):", "SMART AUTOMATION RULES:", "智能自控联动规则:"), 24, 230);
    lcd.setTextColor(COLOR_TEXT_VAL, 0x0184);
    lcd.drawString(L_STR("1. ปั๊มน้ำ: เปิดเมื่อดิน < 40%, ปิดเมื่อดิน >= 65%", "1. Water Pump: Auto ON < 40%, OFF >= 65%", "1. 水泵: 土壤湿度<40%启动，>=65%关闭"), 24, 246);
    lcd.drawString(L_STR("2. พ่นหมอก: เปิดเมื่อ อุณหภูมิ > 35C และ ความชื้น < 70%", "2. Misting: Auto ON Temp > 35C & RH < 70%", "2. 喷雾: 气温>35℃且空气湿度<70%启动"), 24, 262);

    lcd.setTextColor(COLOR_YELLOW, COLOR_BG);
    lcd.drawString(L_STR("แตะที่กล่องเพื่อสลับสถานะเปิด/ปิด", "TAP BOX TO TOGGLE RELAY STATE", "点击方块切换开关状态"), 135, 298);
}

// ============================================================================
// หน้าที่ 4: ข้อมูลระบบ & จัดการ Wi-Fi อัจฉริยะ (Smart Wi-Fi Setup)
// ============================================================================
static void drawPageWiFiSetup() {
    char buf[64];

    if (pageChanged) {
        lcd.fillRect(0, 36, 480, 284, COLOR_BG);
        pageChanged = false;
    }

    bool isPortal = WiFiConfigManager_isPortalActive();

    if (!isPortal) {
        lcd.setTextSize(1);
        lcd.setTextColor(COLOR_YELLOW, COLOR_BG);
        lcd.drawString(L_STR("สถานะเครือข่าย & จัดการระบบ", "NETWORK STATUS & SYSTEM SETTINGS", "网络状态与系统设置"), 14, 42);

        // การ์ดแสดงสถานะเครือข่าย IP / SSID / Cloud (y: 56, h: 122)
        lcd.fillRoundRect(14, 56, 452, 122, 6, COLOR_CARD_BG);
        lcd.drawRoundRect(14, 56, 452, 122, 6, COLOR_ACCENT);

        lcd.setTextSize(1);
        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);

        if (WiFi.status() == WL_CONNECTED) {
            lcd.setTextColor(0x07E0, COLOR_CARD_BG);
            lcd.drawString(L_STR("สถานะ: เชื่อมต่อแล้ว (ออนไลน์)", "Status: CONNECTED (ONLINE)", "状态: 已连接 (在线)"), 28, 68);

            lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
            snprintf(buf, sizeof(buf), "SSID: %s", WiFi.SSID().c_str());
            lcd.drawString(buf, 28, 88);

            snprintf(buf, sizeof(buf), "IP Address: %s", WiFi.localIP().toString().c_str());
            lcd.drawString(buf, 28, 108);

            snprintf(buf, sizeof(buf), "%s: %d dBm (ปกติ)", L_STR("ระดับสัญญาณ", "Signal RSSI", "信号强度"), WiFi.RSSI());
            lcd.drawString(buf, 28, 128);

            lcd.setTextColor(COLOR_CYAN, COLOR_CARD_BG);
            snprintf(buf, sizeof(buf), "Cloud: http://14.207.141.164:8000 | Web: :8501");
            lcd.drawString(buf, 28, 148);
        } else {
            lcd.setTextColor(COLOR_WARN, COLOR_CARD_BG);
            lcd.drawString(L_STR("สถานะ: รอการเชื่อมต่อ Wi-Fi...", "Status: WAITING FOR WI-FI...", "状态: 等待 Wi-Fi 连接..."), 28, 70);

            lcd.setTextColor(COLOR_TEXT_DIM, COLOR_CARD_BG);
            snprintf(buf, sizeof(buf), "%s: %s", L_STR("SSID ล่าสุด", "Saved SSID", "已保存 Wi-Fi"), WiFiConfigManager_getSSID().c_str());
            lcd.drawString(buf, 28, 96);

            lcd.drawString(L_STR("เปลี่ยน Wi-Fi แตะปุ่มด้านล่างเพื่อตั้งค่าผ่านมือถือ", "To change Wi-Fi, tap button below for Phone Setup", "更改 Wi-Fi 请点击下方按钮通过手机设置"), 28, 124);
        }

        // ==========================================
        // เมนูเลือกภาษาแสดงผล 3 ภาษา (Deliberate 3-Button Language Selector)
        // ==========================================
        lcd.setTextSize(1);
        lcd.setTextColor(COLOR_YELLOW, COLOR_BG);
        lcd.drawString(L_STR("เลือกภาษาเมนู / SYSTEM LANGUAGE:", "SELECT SYSTEM LANGUAGE:", "选择系统语言:"), 20, 184);

        // 1. ปุ่มภาษาไทย (x: 20, y: 202, w: 136, h: 34)
        bool isTh = (currentLanguage == LANG_TH);
        lcd.fillRoundRect(20, 202, 136, 34, 6, isTh ? 0x05E0 : COLOR_CARD_BG);
        lcd.drawRoundRect(20, 202, 136, 34, 6, isTh ? 0xFFFF : 0x4A69);
        lcd.setTextSize(1);
        lcd.setTextColor(isTh ? 0xFFFF : COLOR_TEXT_VAL, isTh ? 0x05E0 : COLOR_CARD_BG);
        lcd.setTextDatum(textdatum_t::middle_center);
        lcd.drawString(isTh ? "[*] ภาษาไทย" : "ภาษาไทย", 20 + 68, 202 + 17);

        // 2. ปุ่ม English (x: 172, y: 202, w: 136, h: 34)
        bool isEn = (currentLanguage == LANG_EN);
        lcd.fillRoundRect(172, 202, 136, 34, 6, isEn ? COLOR_CYAN : COLOR_CARD_BG);
        lcd.drawRoundRect(172, 202, 136, 34, 6, isEn ? 0xFFFF : 0x4A69);
        lcd.setTextColor(isEn ? 0x0000 : COLOR_TEXT_VAL, isEn ? COLOR_CYAN : COLOR_CARD_BG);
        lcd.drawString(isEn ? "[*] English" : "English", 172 + 68, 202 + 17);

        // 3. ปุ่ม 中文 (x: 324, y: 202, w: 136, h: 34)
        bool isZh = (currentLanguage == LANG_ZH);
        lcd.fillRoundRect(324, 202, 136, 34, 6, isZh ? COLOR_YELLOW : COLOR_CARD_BG);
        lcd.drawRoundRect(324, 202, 136, 34, 6, isZh ? 0xFFFF : 0x4A69);
        lcd.setTextColor(isZh ? 0x0000 : COLOR_TEXT_VAL, isZh ? COLOR_YELLOW : COLOR_CARD_BG);
        lcd.drawString(isZh ? "[*] 中文" : "中文", 324 + 68, 202 + 17);

        lcd.setTextDatum(textdatum_t::top_left);

        // ปุ่มเปิด SoftAP Mobile WiFi Setup (x: 20, y: 244, w: 440, h: 38)
        lcd.fillRoundRect(20, 244, 440, 38, 6, 0x028A);
        lcd.drawRoundRect(20, 244, 440, 38, 6, 0x05BF);
        lcd.setTextSize(1);
        lcd.setTextColor(0xFFFF, 0x028A);
        lcd.setTextDatum(textdatum_t::middle_center);
        lcd.drawString(L_STR("ตั้งค่า WI-FI ผ่านมือถือ / QR CODE", "SETUP WI-FI VIA PHONE / QR CODE", "通过手机扫码设置 WI-FI"), 20 + 220, 244 + 19);
        lcd.setTextDatum(textdatum_t::top_left);

        lcd.setTextSize(1);
        lcd.setTextColor(COLOR_TEXT_DIM, COLOR_BG);
        lcd.drawString(L_STR("สแกน QR หรือต่อ SoftAP เพื่อตั้งค่า Wi-Fi โดยไม่ต้องใช้คอมพิวเตอร์", "Scan QR or connect to SoftAP to setup without PC", "手机扫描二维码或连接热点即可快捷配置，无需电脑"), 30, 290);
    } else {
        lcd.setTextSize(1);
        lcd.setTextColor(COLOR_YELLOW, COLOR_BG);
        lcd.drawString(L_STR("ตั้งค่า WI-FI ผ่านมือถือ (สแกน QR CODE)", "MOBILE WI-FI SETUP (SCAN QR CODE)", "手机扫码设置 WI-FI (请扫码)"), 14, 42);

        lcd.qrcode("http://192.168.4.1", 20, 60, 160);

        lcd.fillRoundRect(194, 60, 272, 160, 6, COLOR_CARD_BG);
        lcd.drawRoundRect(194, 60, 272, 160, 6, COLOR_CYAN);

        lcd.setTextSize(1);
        lcd.setTextColor(0x07E0, COLOR_CARD_BG);
        lcd.drawString(L_STR("ขั้นตอนการตั้งค่าง่ายๆ:", "Quick Setup Steps:", "简易设置步骤:"), 206, 70);

        lcd.setTextColor(COLOR_TEXT_VAL, COLOR_CARD_BG);
        lcd.drawString(L_STR("1. สแกน QR Code ด้านซ้ายด้วยมือถือ", "1. Scan QR Code on left with phone", "1. 使用手机扫描左侧二维码"), 206, 92);
        lcd.drawString(L_STR("2. หรือต่อ Wi-Fi: JC-AgriTech-Setup", "2. Or connect to: JC-AgriTech-Setup", "2. 或连接 Wi-Fi: JC-AgriTech-Setup"), 206, 114);
        lcd.drawString(L_STR("3. เปิดเบราว์เซอร์: 192.168.4.1", "3. Open browser: 192.168.4.1", "3. 浏览器访问: 192.168.4.1"), 206, 136);
        lcd.drawString(L_STR("4. เลือก Wi-Fi และใส่รหัสผ่าน", "4. Select Wi-Fi & enter password", "4. 选择您的 Wi-Fi 并输入密码"), 206, 158);
        lcd.setTextColor(COLOR_YELLOW, COLOR_CARD_BG);
        lcd.drawString(L_STR("5. กดบันทึก -> บอร์ดจะต่อเน็ตทันที!", "5. Tap Save -> Board reconnects!", "5. 点击保存，设备自动联网！"), 206, 180);

        lcd.fillRoundRect(194, 230, 272, 44, 6, 0x8000);
        lcd.drawRoundRect(194, 230, 272, 44, 6, COLOR_WARN);
        lcd.setTextSize(1);
        lcd.setTextColor(0xFFFF, 0x8000);
        lcd.setTextDatum(textdatum_t::middle_center);
        lcd.drawString(L_STR("[ ยกเลิก / ออกจากโหมดตั้งค่า ]", "[ CANCEL / EXIT SETUP MODE ]", "[ 取消 / 退出设置模式 ]"), 194 + 136, 230 + 22);
        lcd.setTextDatum(textdatum_t::top_left);
    }
}

void DisplayManager_update(const FarmSensorTelemetry &data, bool pumpState, bool mistingState) {
    // บันทึกข้อมูลลง Ring Buffer เสมอเพื่อพล็อตกราฟ
    addHistoryPoint(data.soilStick.moisture,
                    data.soil7in1.isConnected ? data.soil7in1.moisture : data.soilStick.moisture,
                    data.light.solarRadiation,
                    data.air.temperature);

    // วาดแถบแท็บด้านบนเสมอเมื่ออยู่ในหน้าย่อยที่ใช้ Top Nav
    if (pageChanged && currentPage <= PAGE_WIFI_SETUP) {
        if (currentPage != PAGE_OVERVIEW && currentPage != PAGE_BIG_NUMBERS) {
            drawTopNavBar();
        }
    }

    // วาดหน้าจอตามแท็บหรือหน้าต่างย่อยที่เลือก
    switch (currentPage) {
        case PAGE_OVERVIEW:
            drawPageOverview(data, pumpState, mistingState);  // หน้าภาพรวมสถานะแปลง (Masterpiece Layout)
            break;
        case PAGE_BIG_NUMBERS:
            drawPageOverview(data, pumpState, mistingState);  // หน้าภาพรวมสถานะแปลง (Masterpiece Layout)
            break;
        case PAGE_GRAPHS:
            drawPageGraphs(data);
            break;
        case PAGE_RELAYS:
            drawPageRelays(pumpState, mistingState);
            break;
        case PAGE_WIFI_SETUP:
            drawPageWiFiSetup();
            break;
        case PAGE_DETAIL_AIR:
            drawPageDetailAir(data);
            break;
        case PAGE_DETAIL_LIGHT:
            drawPageDetailLight(data);
            break;
        case PAGE_DETAIL_SOIL1:
            drawPageDetailSoil1(data);
            break;
        case PAGE_DETAIL_SOIL7:
            drawPageDetailSoil7(data);
            break;
    }
}

bool DisplayManager_hasPageChanged() {
    return pageChanged;
}

bool DisplayManager_getTouch(int32_t *x, int32_t *y) {
    return lcd.getTouch(x, y);
}

void DisplayManager_handleTouch(bool &pumpState, bool &mistingState) {
    static bool isTouching = false;
    static int32_t touchStartX = 0, touchStartY = 0;
    static int32_t lastTouchY = 0;
    static int32_t totalDragY = 0;
    static bool isDragging = false;
    static unsigned long lastTouchTime = 0;

    int32_t tx, ty;
    if (lcd.getTouch(&tx, &ty)) {
        if (!isTouching) {
            // จุดเริ่มต้นของการสัมผัส (Touch Down)
            isTouching = true;
            touchStartX = tx;
            touchStartY = ty;
            lastTouchY = ty;
            totalDragY = 0;
            isDragging = false;
        } else {
            // กำลังลากนิ้ว (Touch Move / Dragging)
            if (currentPage >= PAGE_DETAIL_AIR && currentPage <= PAGE_DETAIL_SOIL7) {
                if (ty > 36) {
                    int diffY = ty - lastTouchY;
                    totalDragY += abs(diffY);
                    if (totalDragY > 6) {
                        isDragging = true;
                        if (diffY != 0) {
                            DisplayManager_scroll(-diffY);
                        }
                    }
                    lastTouchY = ty;
                }
            }
        }
        return;
    }

    // เมื่อปล่อยนิ้ว (Touch Up / Released)
    if (isTouching) {
        isTouching = false;

        // หากเป็นการลากเลื่อนหน้าจอ (Drag) ไม่ต้องนับว่าเป็นการแตะปุ่ม (Tap)
        if (isDragging || totalDragY >= 12) {
            return;
        }

        // เป็นการแตะกดเลือก (Clean Tap)
        unsigned long now = millis();
        if (now - lastTouchTime < 220) {
            return; // Debounce 220ms
        }
        lastTouchTime = now;

        int tapX = touchStartX;
        int tapY = touchStartY;

        Serial.printf(">>> [Touch-Tap] Screen Tapped at X: %d, Y: %d (Current Page: %d)\n", tapX, tapY, currentPage);

        // =========================================================
        // A. หากอยู่ในหน้าย่อยแสดงรายละเอียดเซนเซอร์ (Pages 4..7)
        // =========================================================
        if (currentPage >= PAGE_DETAIL_AIR && currentPage <= PAGE_DETAIL_SOIL7) {
            // 1. ปุ่ม [< BACK] บน Header (x: 2..80, y: 2..46) -> กลับสู่หน้าภาพรวม Home
            if (tapX >= 2 && tapX <= 80 && tapY <= 46) {
                Serial.println(">>> [Nav-Detail] Back to Overview Home");
                DisplayManager_setPage(PAGE_OVERVIEW);
                drawTopNavBar();
                return;
            }
            // 2. ปุ่ม [NEXT >] บน Header (x: 325..405, y: 2..46) -> วนดูเซนเซอร์ถัดไป
            else if (tapX >= 325 && tapX <= 405 && tapY <= 46) {
                DisplayPage nextPage = (currentPage == PAGE_DETAIL_SOIL7) ? PAGE_DETAIL_AIR : (DisplayPage)(currentPage + 1);
                Serial.printf(">>> [Nav-Detail] Cycling to next sensor page: %d\n", nextPage);
                DisplayManager_setPage(nextPage);
                return;
            }
            // 3. ปุ่มภาษา [ไทย/ENG/中文] บน Header (x: 406..478, y: 2..46) -> สลับภาษาทันที
            else if (tapX >= 406 && tapX <= 478 && tapY <= 46) {
                Serial.println(">>> [Nav-Detail] Toggle Language in Detail View!");
                DisplayManager_toggleLanguage();
                return;
            }
            // 4. แตะที่แถบสไลด์บาร์ฝั่งขวาสุด (x >= 468) -> เลื่อนขึ้นหรือลง
            else if (tapX >= 468) {
                if (tapY < 160) {
                    DisplayManager_scroll(-60);
                } else {
                    DisplayManager_scroll(60);
                }
                return;
            }
            // 5. แตะปุ่มนำทางด้านล่างของเนื้อหา (Bottom Nav Bar)
            int contentY = tapY + scrollOffsetY;
            if (contentY >= 430 && contentY <= 550) {
                // ปุ่ม [< ย้อนกลับหน้าแรก] (x: 14..230)
                if (tapX >= 14 && tapX <= 230) {
                    Serial.println(">>> [Detail-BottomNav] Back to Home");
                    DisplayManager_setPage(PAGE_OVERVIEW);
                    drawTopNavBar();
                    return;
                }
                // ปุ่ม [เซนเซอร์ถัดไป >] (x: 238..454)
                else if (tapX >= 238 && tapX <= 454) {
                    DisplayPage nextPage = (currentPage == PAGE_DETAIL_SOIL7) ? PAGE_DETAIL_AIR : (DisplayPage)(currentPage + 1);
                    Serial.printf(">>> [Detail-BottomNav] Next sensor: %d\n", nextPage);
                    DisplayManager_setPage(nextPage);
                    return;
                }
            }
            return;
        }

        // =========================================================
        // B. ตรวจจับการแตะแท็บ Navigation ด้านบนในหน้าย่อย (เมื่อไม่ใช่หน้า Overview)
        // =========================================================
        if (currentPage != PAGE_OVERVIEW && currentPage != PAGE_BIG_NUMBERS) {
            if (tapY <= 46) {
                if (tapX < 80) {
                    DisplayManager_setPage(PAGE_OVERVIEW);
                    Serial.println(">>> [Nav] Switched to Tab 1: HOME/OVERVIEW");
                } else if (tapX >= 80 && tapX < 160) {
                    DisplayManager_setPage(PAGE_BIG_NUMBERS);
                    Serial.println(">>> [Nav] Switched to Tab 2: BIG NUMBERS");
                } else if (tapX >= 160 && tapX < 240) {
                    DisplayManager_setPage(PAGE_GRAPHS);
                    Serial.println(">>> [Nav] Switched to Tab 3: GRAPH");
                } else if (tapX >= 240 && tapX < 320) {
                    DisplayManager_setPage(PAGE_RELAYS);
                    Serial.println(">>> [Nav] Switched to Tab 4: RELAY");
                } else if (tapX >= 320 && tapX < 402) {
                    DisplayManager_setPage(PAGE_WIFI_SETUP);
                    Serial.println(">>> [Nav] Switched to Tab 5: SETTINGS/WIFI");
                } else if (tapX >= 402) {
                    Serial.println(">>> [Nav] Clicked Language Menu Button -> Toggling Language!");
                    DisplayManager_toggleLanguage();
                    drawTopNavBar();
                    return;
                }
                drawTopNavBar();
                return;
            }
        }

        // =========================================================
        // C. ตรวจจับการสัมผัสตามหน้าจอที่เปิดอยู่
        // =========================================================
        if (currentPage == PAGE_OVERVIEW || currentPage == PAGE_BIG_NUMBERS) {
            // 1. ปุ่มเปลี่ยนภาษาบน Header (x: 380..478, y <= 34)
            if (tapX >= 380 && tapX <= 478 && tapY <= 34) {
                Serial.println(">>> [Overview] Clicked Language Switcher!");
                DisplayManager_toggleLanguage();
                return;
            }

            // 2. แตะการ์ด 1: สภาพอากาศ SHT45 (x: 8..236, y: 36..150)
            if (tapX >= 8 && tapX <= 236 && tapY >= 36 && tapY <= 150) {
                Serial.println(">>> [DrillDown] Opening SHT45 Detailed Air & VPD Screen!");
                DisplayManager_setPage(PAGE_DETAIL_AIR);
                return;
            }
            // 3. แตะการ์ด 2: แสงแดด & รังสี BH1750 (x: 244..472, y: 36..150)
            else if (tapX >= 244 && tapX <= 472 && tapY >= 36 && tapY <= 150) {
                Serial.println(">>> [DrillDown] Opening BH1750 Detailed Solar Radiation Screen!");
                DisplayManager_setPage(PAGE_DETAIL_LIGHT);
                return;
            }
            // 4. แตะการ์ด 3: ดินตื้น Soil Stick (x: 8..236, y: 154..268)
            else if (tapX >= 8 && tapX <= 236 && tapY >= 154 && tapY <= 268) {
                Serial.println(">>> [DrillDown] Opening Soil Stick Detailed Moisture Screen!");
                DisplayManager_setPage(PAGE_DETAIL_SOIL1);
                return;
            }
            // 5. แตะการ์ด 4: ดินลึก 7-in-1 NPK & pH (x: 244..472, y: 154..268)
            else if (tapX >= 244 && tapX <= 472 && tapY >= 154 && tapY <= 268) {
                Serial.println(">>> [DrillDown] Opening 7-in-1 Detailed Soil & NPK Screen!");
                DisplayManager_setPage(PAGE_DETAIL_SOIL7);
                return;
            }

            // 6. แตะ 4 ปุ่มนำทางหลักที่ Bottom Nav Bar (y >= 270)
            else if (tapY >= 270) {
                // ปุ่ม 1: ภาพรวม (x: 8..118)
                if (tapX >= 8 && tapX <= 118) {
                    Serial.println(">>> [BottomNav] Already on HOME/OVERVIEW");
                    pageChanged = true;
                    return;
                }
                // ปุ่ม 2: กราฟ (x: 126..236)
                else if (tapX >= 126 && tapX <= 236) {
                    Serial.println(">>> [BottomNav] Switching to GRAPHS");
                    DisplayManager_setPage(PAGE_GRAPHS);
                    return;
                }
                // ปุ่ม 3: รีเลย์ (x: 244..354)
                else if (tapX >= 244 && tapX <= 354) {
                    Serial.println(">>> [BottomNav] Switching to RELAYS");
                    DisplayManager_setPage(PAGE_RELAYS);
                    return;
                }
                // ปุ่ม 4: ตั้งค่า (x: 362..472)
                else if (tapX >= 362 && tapX <= 472) {
                    Serial.println(">>> [BottomNav] Switching to SETTINGS");
                    DisplayManager_setPage(PAGE_WIFI_SETUP);
                    return;
                }
            }
        }
        else if (currentPage == PAGE_RELAYS) {
            // กล่องที่ 1: PUMP 1 (x: 14..232, y: 60..130)
            if (tapX >= 14 && tapX <= 232 && tapY >= 60 && tapY <= 130) {
                pumpState = !pumpState;
                digitalWrite(RELAY_1_PIN, pumpState ? HIGH : LOW);
                Serial.printf(">>> [Relay-Page] PUMP 1 -> %s\n", pumpState ? "ON" : "OFF");
                pageChanged = true;
            }
            // กล่องที่ 2: PUMP 2 (x: 248..466, y: 60..130)
            else if (tapX >= 248 && tapX <= 466 && tapY >= 60 && tapY <= 130) {
                bool s = (digitalRead(RELAY_2_PIN) == HIGH);
                digitalWrite(RELAY_2_PIN, s ? LOW : HIGH);
                Serial.printf(">>> [Relay-Page] PUMP 2 -> %s\n", !s ? "ON" : "OFF");
                pageChanged = true;
            }
            // กล่องที่ 3: VALVE (x: 14..232, y: 140..210)
            else if (tapX >= 14 && tapX <= 232 && tapY >= 140 && tapY <= 210) {
                bool s = (digitalRead(RELAY_3_PIN) == HIGH);
                digitalWrite(RELAY_3_PIN, s ? LOW : HIGH);
                Serial.printf(">>> [Relay-Page] VALVE -> %s\n", !s ? "ON" : "OFF");
                pageChanged = true;
            }
            // กล่องที่ 4: MISTING (x: 248..466, y: 140..210)
            else if (tapX >= 248 && tapX <= 466 && tapY >= 140 && tapY <= 210) {
                mistingState = !mistingState;
                digitalWrite(RELAY_4_PIN, mistingState ? HIGH : LOW);
                Serial.printf(">>> [Relay-Page] MISTING -> %s\n", mistingState ? "ON" : "OFF");
                pageChanged = true;
            }
        }
        else if (currentPage == PAGE_WIFI_SETUP) {
            bool isPortal = WiFiConfigManager_isPortalActive();
            if (!isPortal) {
                // เมนูเลือกภาษา 3 ภาษา (y: 198..240)
                if (tapY >= 198 && tapY <= 240) {
                    if (tapX >= 20 && tapX <= 156) {
                        Serial.println(">>> [Lang-Select] User selected: THAI");
                        DisplayManager_setLanguage(LANG_TH);
                        pageChanged = true;
                    } else if (tapX >= 172 && tapX <= 308) {
                        Serial.println(">>> [Lang-Select] User selected: ENGLISH");
                        DisplayManager_setLanguage(LANG_EN);
                        pageChanged = true;
                    } else if (tapX >= 324 && tapX <= 460) {
                        Serial.println(">>> [Lang-Select] User selected: CHINESE");
                        DisplayManager_setLanguage(LANG_ZH);
                        pageChanged = true;
                    }
                }
                // ปุ่มเปิด SoftAP Mobile WiFi Setup (y: 242..286, x: 20..460)
                else if (tapY >= 242 && tapY <= 286 && tapX >= 20 && tapX <= 460) {
                    Serial.println(">>> [WiFi-Page] Starting SoftAP Captive Portal by touch!");
                    WiFiConfigManager_startPortal();
                    pageChanged = true;
                }
            } else {
                if (tapY >= 225 && tapY <= 275 && tapX >= 190 && tapX <= 470) {
                    Serial.println(">>> [WiFi-Page] Stopping SoftAP Captive Portal by touch!");
                    WiFiConfigManager_stopPortal();
                    pageChanged = true;
                }
            }
        }
    }
}
