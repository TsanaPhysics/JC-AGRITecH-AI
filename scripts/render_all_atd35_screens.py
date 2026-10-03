#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
LEQs-AgriEnvi-xAI - Master High-Resolution Digital Screen Graphics Generator
Renders ultra-crisp, beautiful, modern cyber-agritech UI screen mockups
for all 10 ATD3.5-S3 screens + Master PR Composite Showcase.
"""

import os, math, random
from PIL import Image, ImageDraw, ImageFont, ImageFilter

W, H = 960, 640 # 2x Retina resolution of 480x320

# Fonts setup
FONT_THAI = "/System/Library/Fonts/Supplemental/SukhumvitSet.ttc"
FONT_ENG = "/System/Library/Fonts/HelveticaNeue.ttc"
FONT_MONO = "/System/Library/Fonts/Menlo.ttc"

try:
    f_huge = ImageFont.truetype(FONT_THAI, 46, index=4)
    f_title = ImageFont.truetype(FONT_THAI, 26, index=4)
    f_subtitle = ImageFont.truetype(FONT_THAI, 20, index=1)
    f_card_h = ImageFont.truetype(FONT_THAI, 22, index=4)
    f_val_xl = ImageFont.truetype(FONT_THAI, 38, index=4)
    f_val_lg = ImageFont.truetype(FONT_THAI, 28, index=4)
    f_body = ImageFont.truetype(FONT_THAI, 19, index=1)
    f_body_b = ImageFont.truetype(FONT_THAI, 19, index=4)
    f_small = ImageFont.truetype(FONT_THAI, 15, index=1)
    f_badge = ImageFont.truetype(FONT_THAI, 16, index=4)
    f_mono = ImageFont.truetype(FONT_MONO, 16)
except Exception:
    f_huge = f_title = f_subtitle = f_card_h = f_val_xl = f_val_lg = f_body = f_body_b = f_small = f_badge = f_mono = ImageFont.load_default()

# Theme Colors
BG_OBSIDIAN = (8, 14, 28)
CARD_BG = (18, 28, 48)
CYAN_NEON = (0, 229, 255)
EMERALD_NEON = (0, 230, 118)
GOLD_NEON = (255, 214, 0)
ORANGE_NEON = (255, 145, 0)
VIOLET_NEON = (187, 134, 252)
TEXT_WHITE = (255, 255, 255)
TEXT_DIM = (148, 163, 184)
TEXT_MUTED = (100, 116, 139)

def draw_header_bar(draw, page_title="", is_overview=False):
    # Top Header Background
    draw.rectangle([0, 0, W, 76], fill=(12, 20, 38))
    draw.line([0, 76, W, 76], fill=(30, 45, 75), width=2)

    # LEQs Emblem (Neon Cyan 'L' with Emerald accent)
    lx, ly = 24, 22
    draw.rectangle([lx, ly, lx + 8, ly + 28], fill=CYAN_NEON)
    draw.rectangle([lx, ly + 22, lx + 26, ly + 28], fill=EMERALD_NEON)
    draw.ellipse([lx + 24, ly + 22, lx + 30, ly + 28], fill=EMERALD_NEON)

    # Brand Title: LEQs-AgriEnvi-xAI
    bx, by = 64, 23
    draw.text((bx, by), "LEQs", font=f_title, fill=CYAN_NEON)
    bx += 68
    draw.text((bx, by), "AgriEnvi", font=f_title, fill=EMERALD_NEON)
    bx += 86

    # xAI Violet Badge
    draw.rounded_rectangle([bx, by + 2, bx + 56, by + 32], radius=8, fill=(80, 20, 120), outline=(220, 50, 240), width=2)
    draw.text((bx + 28, by + 17), "xAI", font=f_badge, fill=TEXT_WHITE, anchor="mm")

    # Real-Time Capsule
    cx, cy, cw, ch = 416, 12, 336, 52
    draw.rounded_rectangle([cx, cy, cx + cw, cy + ch], radius=26, fill=(16, 26, 48), outline=EMERALD_NEON, width=2)
    draw.text((cx + cw // 2, cy + ch // 2), "12/10/26 14:32:45", font=f_card_h, fill=CYAN_NEON, anchor="mm")

    # Wi-Fi Icon & Signal
    wx, wy = 780, 48
    draw.ellipse([wx - 4, wy - 4, wx + 4, wy + 4], fill=EMERALD_NEON)
    draw.arc([wx - 10, wy - 10, wx + 10, wy + 10], start=225, end=315, fill=EMERALD_NEON, width=2)
    draw.arc([wx - 18, wy - 18, wx + 18, wy + 18], start=225, end=315, fill=EMERALD_NEON, width=2)
    draw.arc([wx - 26, wy - 26, wx + 26, wy + 26], start=225, end=315, fill=EMERALD_NEON, width=2)

    # Lang button
    draw.rounded_rectangle([826, 14, 936, 62], radius=10, fill=(20, 36, 60), outline=(0, 229, 255), width=2)
    draw.text((881, 38), "🇹🇭 TH", font=f_body_b, fill=TEXT_WHITE, anchor="mm")

def draw_bottom_nav(draw, active_tab=0):
    ny = H - 64
    draw.rectangle([0, ny, W, H], fill=(12, 20, 38))
    draw.line([0, ny, W, ny], fill=(30, 45, 75), width=2)
    tabs = [
        ("ภาพรวม", "OVERVIEW"),
        ("ตัวเลขใหญ่", "TELEMETRY"),
        ("กราฟแนวโน้ม", "GRAPHS"),
        ("ควบคุมรีเลย์", "RELAYS"),
        ("ตั้งค่า Wi-Fi", "WIFI")
    ]
    tw = W // len(tabs)
    for i, (th, en) in enumerate(tabs):
        tx = i * tw
        is_active = (i == active_tab)
        bg = (24, 40, 72) if is_active else (14, 22, 40)
        border = CYAN_NEON if is_active else (25, 38, 65)
        text_col = CYAN_NEON if is_active else TEXT_DIM
        draw.rounded_rectangle([tx + 8, ny + 8, tx + tw - 8, H - 8], radius=8, fill=bg, outline=border, width=2 if is_active else 1)
        draw.text((tx + tw // 2, ny + 36), th, font=f_body_b if is_active else f_body, fill=text_col, anchor="mm")

def draw_detail_header(draw, title_th, accent_col):
    draw.rectangle([0, 0, W, 76], fill=(12, 20, 38))
    draw.line([0, 76, W, 76], fill=(30, 45, 75), width=2)
    draw.rounded_rectangle([16, 12, 156, 64], radius=10, fill=(24, 34, 56), outline=(255, 255, 255), width=2)
    draw.text((86, 38), "< ภาพรวม", font=f_body_b, fill=TEXT_WHITE, anchor="mm")

    draw.rounded_rectangle([172, 12, 672, 64], radius=10, fill=(16, 26, 48), outline=accent_col, width=2)
    draw.text((422, 38), title_th, font=f_card_h, fill=accent_col, anchor="mm")

    draw.rounded_rectangle([688, 12, 828, 64], radius=10, fill=(10, 42, 69), outline=CYAN_NEON, width=2)
    draw.text((758, 38), "ถัดไป >", font=f_body_b, fill=CYAN_NEON, anchor="mm")

    draw.rounded_rectangle([844, 12, 944, 64], radius=10, fill=(20, 40, 60), outline=EMERALD_NEON, width=2)
    draw.text((894, 38), "ไทย", font=f_body_b, fill=EMERALD_NEON, anchor="mm")

def draw_progress(draw, x, y, w, h, pct, col):
    draw.rounded_rectangle([x, y, x + w, y + h], radius=h // 2, fill=(16, 26, 48), outline=(40, 55, 85), width=2)
    pw = max(0, min(w - 4, int((w - 4) * pct)))
    if pw > h - 4:
        draw.rounded_rectangle([x + 2, y + 2, x + 2 + pw, y + h - 2], radius=(h - 4) // 2, fill=col)

def draw_badge(draw, x, y, text, bg, fg=TEXT_WHITE, w=60, h=28):
    draw.rounded_rectangle([x, y, x + w, y + h], radius=6, fill=bg)
    draw.text((x + w // 2, y + h // 2), text, font=f_badge, fill=fg, anchor="mm")
    return x + w + 10

# -------------------------------------------------------------
# SCREEN 00: SPLASH SCREEN (Neural Plant Nexus)
# -------------------------------------------------------------
def render_screen_00_splash():
    img = Image.new("RGB", (W, H), (2, 5, 12))
    draw = ImageDraw.Draw(img)

    # Ambient Hexagonal Cyber Grid Background
    for x in range(0, W, 80):
        for y in range(0, H, 80):
            draw.ellipse([x - 1, y - 1, x + 1, y + 1], fill=(20, 45, 80))

    # Center Glowing Rings
    cx, cy = W // 2, H // 2 - 50
    for r in range(160, 40, -20):
        draw.ellipse([cx - r, cy - r, cx + r, cy + r], outline=(10, 50, 90), width=1)
    draw.ellipse([cx - 80, cy - 80, cx + 80, cy + 80], outline=(0, 180, 240), width=3)
    draw.ellipse([cx - 50, cy - 50, cx + 50, cy + 50], outline=(0, 230, 118), width=2)

    # Plant Sprout in Center
    draw.line([cx, cy + 30, cx, cy - 10], fill=EMERALD_NEON, width=5)
    draw.arc([cx - 30, cy - 35, cx + 10, cy + 5], 180, 360, fill=EMERALD_NEON, width=4)
    draw.arc([cx - 10, cy - 45, cx + 30, cy - 5], 180, 360, fill=CYAN_NEON, width=4)
    draw.ellipse([cx - 6, cy - 16, cx + 6, cy - 4], fill=GOLD_NEON)

    # Big Branding Title: LEQs-AgriEnvi-xAI
    draw.text((cx + 3, cy + 123), "LEQs-AgriEnvi-xAI", font=f_huge, fill=(0, 40, 80), anchor="mm") # shadow
    draw.text((cx, cy + 120), "LEQs-AgriEnvi-xAI", font=f_huge, fill=CYAN_NEON, anchor="mm")
    
    # Subtitle in Thai
    draw.text((cx, cy + 175), "ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม", font=f_title, fill=TEXT_WHITE, anchor="mm")
    draw.text((cx, cy + 215), "TinyML On-Device Neural Edge Calibrator • Dual-Depth Soil Sensing", font=f_subtitle, fill=GOLD_NEON, anchor="mm")

    # Bottom Pillars Badge Ribbon
    ribbon_y = H - 65
    draw.rectangle([0, ribbon_y, W, H], fill=(8, 16, 32))
    draw.line([0, ribbon_y, W, ribbon_y], fill=CYAN_NEON, width=2)
    draw.text((cx, ribbon_y + 32), "ATD3.5-S3 Masterclass System • Handysense IoT • RBRU Research", font=f_card_h, fill=TEXT_DIM, anchor="mm")
    return img

# -------------------------------------------------------------
# SCREEN 01: OVERVIEW DASHBOARD (4 Stations)
# -------------------------------------------------------------
def render_screen_01_overview():
    img = Image.new("RGB", (W, H), BG_OBSIDIAN)
    draw = ImageDraw.Draw(img)
    draw_header_bar(draw, is_overview=True)
    draw_bottom_nav(draw, active_tab=0)

    # 4 Cards Geometry
    cw, ch = 450, 226
    c1 = (20, 92, 20 + cw, 92 + ch)
    c2 = (490, 92, 490 + cw, 92 + ch)
    c3 = (20, 334, 20 + cw, 334 + ch)
    c4 = (490, 334, 490 + cw, 334 + ch)

    # Card 1: Microclimate Air & VPD (SHT45)
    draw.rounded_rectangle(c1, radius=16, fill=CARD_BG, outline=CYAN_NEON, width=3)
    draw.text((c1[0] + 20, c1[1] + 16), "สภาพอากาศรอบแปลง (SHT45)", font=f_card_h, fill=CYAN_NEON)
    draw.text((c1[0] + 20, c1[1] + 62), "28.5 °C", font=f_val_xl, fill=TEXT_WHITE)
    draw.text((c1[0] + 240, c1[1] + 62), "82.4 %", font=f_val_xl, fill=CYAN_NEON)
    draw.text((c1[0] + 20, c1[1] + 120), "VPD: 0.68 kPa (ปกติ-สมดุล)", font=f_body_b, fill=EMERALD_NEON)
    draw_progress(draw, c1[0] + 20, c1[1] + 155, cw - 40, 16, 0.68 / 2.0, EMERALD_NEON)
    draw.text((c1[0] + 20, c1[1] + 185), "Dew Point: 25.1 °C  |  Margin: 3.4 °C", font=f_small, fill=TEXT_MUTED)

    # Card 2: Solar Dome (BH1750)
    draw.rounded_rectangle(c2, radius=16, fill=CARD_BG, outline=GOLD_NEON, width=3)
    draw.text((c2[0] + 20, c2[1] + 16), "ความเข้มแสงโดมตะวัน (BH1750)", font=f_card_h, fill=GOLD_NEON)
    draw.text((c2[0] + 20, c2[1] + 62), "48,500 Lux", font=f_val_xl, fill=TEXT_WHITE)
    draw.text((c2[0] + 20, c2[1] + 120), "Solar Rad: 384 W/m²  (48.5 kLux)", font=f_body_b, fill=GOLD_NEON)
    draw_progress(draw, c2[0] + 20, c2[1] + 155, cw - 40, 16, 48500 / 65535, GOLD_NEON)
    draw.text((c2[0] + 20, c2[1] + 185), "PAR: 785 µmol/m²·s  |  DLI: 28.4 mol/m²·d", font=f_small, fill=TEXT_MUTED)

    # Card 3: Soil Stick Surface (0-10 cm)
    draw.rounded_rectangle(c3, radius=16, fill=CARD_BG, outline=EMERALD_NEON, width=3)
    draw.text((c3[0] + 20, c3[1] + 16), "ความชื้นดินชั้นตื้น (Soil Stick 0-10 cm)", font=f_card_h, fill=EMERALD_NEON)
    draw.text((c3[0] + 20, c3[1] + 62), "38.2 %", font=f_val_xl, fill=EMERALD_NEON)
    draw.text((c3[0] + 240, c3[1] + 62), "pH 6.5", font=f_val_xl, fill=CYAN_NEON)
    draw.text((c3[0] + 20, c3[1] + 120), "สถานะ: ชุ่มชื้นเหมาะสม • ไม่ต้องรดน้ำ", font=f_body_b, fill=EMERALD_NEON)
    draw_progress(draw, c3[0] + 20, c3[1] + 155, cw - 40, 16, 0.382, EMERALD_NEON)
    draw.text((c3[0] + 20, c3[1] + 185), "ADC A1: 2410 mV  |  Surface pH Probe: Ready", font=f_small, fill=TEXT_MUTED)

    # Card 4: Soil 7-in-1 & TinyML (Deep Rootzone)
    draw.rounded_rectangle(c4, radius=16, fill=CARD_BG, outline=VIOLET_NEON, width=3)
    draw.text((c4[0] + 20, c4[1] + 16), "ธาตุอาหารเขตรากลึก & TinyML AI", font=f_card_h, fill=VIOLET_NEON)
    draw.text((c4[0] + 20, c4[1] + 62), "Moist 35.8%", font=f_val_lg, fill=TEXT_WHITE)
    draw.text((c4[0] + 240, c4[1] + 62), "pH 6.82", font=f_val_lg, fill=EMERALD_NEON)
    draw.text((c4[0] + 20, c4[1] + 115), "AI NPK: N: 48.5  |  P: 22.1  |  K: 65.4 mg/kg", font=f_body_b, fill=GOLD_NEON)
    draw.text((c4[0] + 20, c4[1] + 150), "EC: 480 µS/cm  |  Soil Temp: 27.8 °C", font=f_body, fill=TEXT_WHITE)
    draw.text((c4[0] + 20, c4[1] + 185), "TinyML Confidence: 92.4% • Latency: < 0.12 ms", font=f_small, fill=CYAN_NEON)
    return img

# -------------------------------------------------------------
# SCREEN 02: BIG NUMBERS TELEMETRY
# -------------------------------------------------------------
def render_screen_02_big_numbers():
    img = Image.new("RGB", (W, H), BG_OBSIDIAN)
    draw = ImageDraw.Draw(img)
    draw_header_bar(draw)
    draw_bottom_nav(draw, active_tab=1)

    cards = [
        ("อุณหภูมิอากาศ SHT45", "28.5", "°C", (255, 145, 0), 20, 92),
        ("ความชื้นอากาศ RH%", "82.4", "%", CYAN_NEON, 490, 92),
        ("ความชื้นดินชั้นตื้น", "38.2", "%", EMERALD_NEON, 20, 334),
        ("กรด-ด่างดิน AI pH", "6.82", "pH", GOLD_NEON, 490, 334)
    ]
    for title, val, unit, col, x, y in cards:
        draw.rounded_rectangle([x, y, x + 450, y + 226], radius=16, fill=CARD_BG, outline=col, width=3)
        draw.text((x + 24, y + 20), title, font=f_card_h, fill=col)
        draw.text((x + 40, y + 78), val, font=ImageFont.truetype(FONT_THAI, 76, index=4), fill=TEXT_WHITE)
        draw.text((x + 320, y + 115), unit, font=f_val_xl, fill=col)
        draw.text((x + 24, y + 185), "สถานะ: เซนเซอร์ปกติ • บันทึกเรียลไทม์", font=f_body, fill=TEXT_MUTED)
    return img

# -------------------------------------------------------------
# SCREEN 03: REAL-TIME GRAPHS
# -------------------------------------------------------------
def render_screen_03_graphs():
    img = Image.new("RGB", (W, H), BG_OBSIDIAN)
    draw = ImageDraw.Draw(img)
    draw_header_bar(draw)
    draw_bottom_nav(draw, active_tab=2)

    # Graph Container Card
    gx, gy, gw, gh = 20, 92, 920, 468
    draw.rounded_rectangle([gx, gy, gx + gw, gy + gh], radius=16, fill=CARD_BG, outline=CYAN_NEON, width=2)
    draw.text((gx + 24, gy + 16), "กราฟอนุกรมเวลาเรียลไทม์ (Live Microclimate & Soil Dynamics 60 วินาที)", font=f_card_h, fill=CYAN_NEON)

    # Legend
    lx = gx + 40
    legends = [("ความชื้นดินชั้นตื้น (Stick)", EMERALD_NEON), ("ความชื้นดินลึก (7in1)", CYAN_NEON), ("อุณหภูมิอากาศ (SHT45)", ORANGE_NEON), ("แสงอาทิตย์ (BH1750)", GOLD_NEON)]
    for name, col in legends:
        draw.rectangle([lx, gy + 56, lx + 20, gy + 66], fill=col)
        draw.text((lx + 28, gy + 50), name, font=f_small, fill=TEXT_WHITE)
        lx += 220

    # Grid Area
    plot_x, plot_y, plot_w, plot_h = gx + 40, gy + 90, gw - 80, gh - 120
    draw.rectangle([plot_x, plot_y, plot_x + plot_w, plot_y + plot_h], fill=(10, 16, 30), outline=(30, 45, 75))

    for py in range(plot_y, plot_y + plot_h + 1, plot_h // 4):
        draw.line([plot_x, py, plot_x + plot_w, py], fill=(20, 32, 54), width=1)
    for px in range(plot_x, plot_x + plot_w + 1, plot_w // 6):
        draw.line([px, plot_y, px, plot_y + plot_h], fill=(20, 32, 54), width=1)

    # Plot wave lines
    random.seed(42)
    points_stick = []
    points_deep = []
    points_temp = []
    for i in range(60):
        xx = plot_x + int(i * (plot_w / 59))
        y1 = plot_y + plot_h - int((35 + 5 * math.sin(i * 0.15) + random.uniform(-1, 1)) / 100 * plot_h)
        y2 = plot_y + plot_h - int((42 + 2 * math.cos(i * 0.1) + random.uniform(-0.5, 0.5)) / 100 * plot_h)
        y3 = plot_y + plot_h - int((28 + 3 * math.sin(i * 0.2) + random.uniform(-0.8, 0.8)) / 50 * plot_h)
        points_stick.append((xx, y1))
        points_deep.append((xx, y2))
        points_temp.append((xx, y3))

    for i in range(len(points_stick) - 1):
        draw.line([points_stick[i], points_stick[i+1]], fill=EMERALD_NEON, width=3)
        draw.line([points_deep[i], points_deep[i+1]], fill=CYAN_NEON, width=3)
        draw.line([points_temp[i], points_temp[i+1]], fill=ORANGE_NEON, width=2)

    return img

# -------------------------------------------------------------
# SCREEN 04: RELAYS CONTROL CENTER
# -------------------------------------------------------------
def render_screen_04_relays():
    img = Image.new("RGB", (W, H), BG_OBSIDIAN)
    draw = ImageDraw.Draw(img)
    draw_header_bar(draw)
    draw_bottom_nav(draw, active_tab=3)

    # 2 Relay Big Cards
    relays = [
        ("รีเลย์ช่อง 1: ปั๊มน้ำหลัก (Drip Irrigation Pump)", "เปิดทำงาน (ON)", True, EMERALD_NEON, 20, 92),
        ("รีเลย์ช่อง 2: ระบบพ่นหมอก (Misting / Fogger)", "ปิดพัก (OFF)", False, (100, 116, 139), 490, 92)
    ]
    for name, st, is_on, col, x, y in relays:
        draw.rounded_rectangle([x, y, x + 450, y + 468], radius=16, fill=CARD_BG, outline=col, width=3)
        draw.text((x + 24, y + 24), name, font=f_card_h, fill=CYAN_NEON)
        
        # State Indicator
        st_bg = (10, 60, 35) if is_on else (40, 20, 20)
        draw.rounded_rectangle([x + 24, y + 70, x + 426, y + 160], radius=12, fill=st_bg, outline=col, width=2)
        draw.text((x + 225, y + 115), st, font=f_val_xl, fill=col, anchor="mm")

        # Mode toggles
        draw.text((x + 24, y + 185), "โหมดการทำงาน: อัตโนมัติด้วย AI ปฐพีวิทยา", font=f_body_b, fill=TEXT_WHITE)
        draw.text((x + 24, y + 220), "• สั่งเปิดเมื่อความชื้นผิวดิน < 30%", font=f_body, fill=TEXT_DIM)
        draw.text((x + 24, y + 250), "• สั่งปิดเมื่อความชื้นผิวดิน > 45%", font=f_body, fill=TEXT_DIM)
        draw.text((x + 24, y + 280), "• ป้องกันปั๊มทำงานแห้ง (Dry-Run Protection)", font=f_body, fill=TEXT_DIM)

        # Control Buttons
        draw.rounded_rectangle([x + 24, y + 340, x + 215, y + 410], radius=10, fill=(0, 180, 90), outline=EMERALD_NEON, width=2)
        draw.text((x + 119, y + 375), "เปิดปั๊ม (MANUAL)", font=f_body_b, fill=TEXT_WHITE, anchor="mm")

        draw.rounded_rectangle([x + 235, y + 340, x + 426, y + 410], radius=10, fill=(180, 40, 40), outline=(255, 100, 100), width=2)
        draw.text((x + 330, y + 375), "ปิดปั๊ม (STOP)", font=f_body_b, fill=TEXT_WHITE, anchor="mm")

        draw.text((x + 225, y + 440), "อัตราการไหลสะสม: 1,450 ลิตร / วัน", font=f_small, fill=GOLD_NEON, anchor="mm")
    return img

# -------------------------------------------------------------
# SCREEN 05: WIFI SETUP & CAPTIVE PORTAL
# -------------------------------------------------------------
def render_screen_05_wifi():
    img = Image.new("RGB", (W, H), BG_OBSIDIAN)
    draw = ImageDraw.Draw(img)
    draw_header_bar(draw)
    draw_bottom_nav(draw, active_tab=4)

    # QR Code Box (Left)
    qx, qy, qw, qh = 40, 110, 320, 320
    draw.rounded_rectangle([qx, qy, qx + qw, qy + qh], radius=16, fill=(255, 255, 255), outline=CYAN_NEON, width=3)
    
    # Draw simulated crisp QR code
    random.seed(1234)
    for row in range(16):
        for col in range(16):
            if (row < 4 and col < 4) or (row < 4 and col > 11) or (row > 11 and col < 4):
                draw.rectangle([qx + 24 + col * 17, qy + 24 + row * 17, qx + 24 + (col + 1) * 17 - 2, qy + 24 + (row + 1) * 17 - 2], fill=(0, 0, 0))
            elif random.random() > 0.45:
                draw.rectangle([qx + 24 + col * 17, qy + 24 + row * 17, qx + 24 + (col + 1) * 17 - 2, qy + 24 + (row + 1) * 17 - 2], fill=(0, 0, 0))
    draw.text((qx + qw // 2, qy + qh + 30), "http://192.168.4.1", font=f_card_h, fill=CYAN_NEON, anchor="mm")

    # Instruction Panel (Right)
    ix, iy, iw, ih = 400, 110, 520, 440
    draw.rounded_rectangle([ix, iy, ix + iw, iy + ih], radius=16, fill=CARD_BG, outline=EMERALD_NEON, width=2)
    draw.text((ix + 24, iy + 24), "ขั้นตอนการตั้งค่าง่ายๆ ผ่านมือถือ:", font=f_card_h, fill=EMERALD_NEON)

    steps = [
        "1. สแกน QR Code ด้านซ้ายด้วยกล้องมือถือ",
        "2. หรือเชื่อมต่อ Wi-Fi ชื่อ: LEQs-AgriEnvi-Setup",
        "3. เปิดเบราว์เซอร์แล้วไปที่: 192.168.4.1",
        "4. เลือกเครือข่าย Wi-Fi ฟาร์มและใส่รหัสผ่าน",
        "5. กดบันทึก -> บอร์ด ATD3.5-S3 จะออนไลน์ทันที!"
    ]
    for idx, s in enumerate(steps):
        col = GOLD_NEON if idx == 4 else TEXT_WHITE
        draw.text((ix + 24, iy + 75 + idx * 45), s, font=f_body_b if idx in [1, 4] else f_body, fill=col)

    # Status Alert
    draw.rounded_rectangle([ix + 24, iy + 330, ix + iw - 24, iy + 410], radius=10, fill=(10, 36, 60), outline=CYAN_NEON, width=2)
    draw.text((ix + iw // 2, iy + 370), "สถานะปัจจุบัน: ออนไลน์เรียบร้อย (IP: 192.168.1.145)", font=f_body_b, fill=CYAN_NEON, anchor="mm")
    return img

# -------------------------------------------------------------
# SCREEN 06: SHT45 DETAIL (Air & VPD)
# -------------------------------------------------------------
def render_screen_06_sht45():
    from render_crisp_ui import render_screen_sht45
    return render_screen_sht45()

# -------------------------------------------------------------
# SCREEN 07: BH1750 DETAIL (Solar Dome & PAR)
# -------------------------------------------------------------
def render_screen_07_bh1750():
    from render_crisp_ui import render_screen_bh1750
    return render_screen_bh1750()

# -------------------------------------------------------------
# SCREEN 08: SOIL STICK DETAIL (Surface Moisture & pH)
# -------------------------------------------------------------
def render_screen_08_soil_stick():
    from render_crisp_ui import render_screen_soil_stick
    return render_screen_soil_stick()

# -------------------------------------------------------------
# SCREEN 09: SOIL 7-IN-1 DETAIL (Deep Rootzone & TinyML)
# -------------------------------------------------------------
def render_screen_09_soil_7in1():
    from render_crisp_ui import render_screen_soil_7in1
    return render_screen_soil_7in1()

# -------------------------------------------------------------
# MASTER COMPOSITE: 3000 x 2000 ULTRA-HD PR SHOWCASE POSTER
# -------------------------------------------------------------
def render_master_pr_poster(screens):
    CW, CH = 3200, 2160
    poster = Image.new("RGB", (CW, CH), (4, 8, 18))
    draw = ImageDraw.Draw(poster)

    # Futuristic Header Ribbon
    draw.rectangle([0, 0, CW, 180], fill=(10, 18, 36))
    draw.line([0, 180, CW, 180], fill=CYAN_NEON, width=4)

    # Big Poster Title
    draw.text((CW // 2, 60), "LEQs-AgriEnvi-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม", font=ImageFont.truetype(FONT_THAI, 54, index=4), fill=CYAN_NEON, anchor="mm")
    draw.text((CW // 2, 125), "ระบบสถานีตรวจวัดและพยากรณ์ฟิสิกส์เกษตรแม่นยำ 2 ระดับความลึก ผสาน On-Device TinyML Edge AI บนบอร์ด ATD3.5-S3", font=ImageFont.truetype(FONT_THAI, 30, index=1), fill=GOLD_NEON, anchor="mm")

    # 3x2 Grid for Primary Screens (Overview, Big Numbers, Graphs, SHT45, Soil Stick, Soil 7-in-1)
    grid_screens = [
        (screens[1], "1. หน้าจอหลักภาพรวม 4 สถานี (Overview Dashboard)"),
        (screens[0], "2. สแปลชสกรีนเปิดเครื่อง (Neural Plant Nexus)"),
        (screens[2], "3. โหมดตัวเลขใหญ่ภาคสนาม (Big Numbers Telemetry)"),
        (screens[3], "4. กราฟแนวโน้มพลวัตสิ่งแวดล้อม (Real-time Analytics)"),
        (screens[6], "5. สถานี SHT45: บรรยากาศและฟิสิกส์ VPD ป้องกันโรครา"),
        (screens[9], "6. สถานี 7-in-1 & TinyML: ชดเชย NPK และ pH เชิงลึก")
    ]

    gw, gh = 960, 640
    xs = [100, 1120, 2140]
    ys = [240, 960]

    idx = 0
    for row in range(2):
        for col in range(3):
            if idx < len(grid_screens):
                simg, cap = grid_screens[idx]
                px = xs[col]
                py = ys[row]
                # Outer glow frame
                draw.rounded_rectangle([px - 8, py - 8, px + gw + 8, py + gh + 8], radius=20, fill=(16, 26, 48), outline=CYAN_NEON if idx == 0 else GOLD_NEON, width=3)
                poster.paste(simg, (px, py))
                # Caption bar
                draw.rounded_rectangle([px, py + gh - 52, px + gw, py + gh], radius=10, fill=(10, 16, 32))
                draw.text((px + 24, py + gh - 26), cap, font=ImageFont.truetype(FONT_THAI, 22, index=4), fill=CYAN_NEON if idx == 0 else GOLD_NEON, anchor="lm")
                idx += 1

    # Bottom Tech Specs Ribbon
    by = CH - 420
    draw.rectangle([0, by, CW, CH], fill=(8, 14, 28))
    draw.line([0, by, CW, by], fill=EMERALD_NEON, width=4)

    # 4 Pillars of Project
    pillars = [
        ("1. DUAL-DEPTH SENSING", "วัดคุณสมบัติดิน 2 ระดับความลึก (ผิวดิน 0-10 cm + รากลึก 10-30 cm) สอดคล้องกับพฤติกรรมการดูดซึมน้ำของรากพืชเศรษฐกิจ"),
        ("2. ON-DEVICE TINYML AI", "โมเดล Neural Network Calibrator (549 พารามิเตอร์) รันบนชิป ESP32-S3 ด้วยความเร็ว < 0.12 ms ไร้การกระตุก ไม่ต้องพึ่งพาระบบคลาวด์"),
        ("3. BIOPHYSICS & VPD", "คำนวณแรงดึงระเหยน้ำ (Vapor Pressure Deficit) ตามมาตรฐาน FAO-56 พยากรณ์ความเสี่ยงโรคพืชและโรคเชื้อราล่วงหน้า 4-8 ชั่วโมง"),
        ("4. INDUSTRIAL CLOUD IOT", "เชื่อมต่อ HandySense Cloud / MQTT / REST API อัปเดตข้อมูลทุก 5 วินาที พร้อม Captive Portal Wi-Fi สำหรับเกษตรกรยุค 4.0")
    ]
    pw = (CW - 200) // 4
    for i, (title, desc) in enumerate(pillars):
        tx = 100 + i * pw
        draw.rounded_rectangle([tx + 10, by + 40, tx + pw - 10, CH - 60], radius=14, fill=(14, 24, 44), outline=CYAN_NEON if i == 0 else (40, 60, 95), width=2)
        draw.text((tx + 28, by + 75), title, font=ImageFont.truetype(FONT_THAI, 24, index=4), fill=EMERALD_NEON)
        
        # Word wrap desc
        words = desc
        draw.text((tx + 28, by + 125), words[:42], font=ImageFont.truetype(FONT_THAI, 18, index=1), fill=TEXT_WHITE)
        draw.text((tx + 28, by + 155), words[42:84], font=ImageFont.truetype(FONT_THAI, 18, index=1), fill=TEXT_WHITE)
        draw.text((tx + 28, by + 185), words[84:126], font=ImageFont.truetype(FONT_THAI, 18, index=1), fill=TEXT_WHITE)
        draw.text((tx + 28, by + 215), words[126:168], font=ImageFont.truetype(FONT_THAI, 18, index=1), fill=TEXT_WHITE)

    return poster


def main():
    print(">>> Generating Master Suite of High-Resolution Screens for ATD3.5-S3...")
    out_dir = "gallery/atd35_screens"
    os.makedirs(out_dir, exist_ok=True)
    os.makedirs("latex_book/figures", exist_ok=True)

    screens = [
        render_screen_00_splash(),
        render_screen_01_overview(),
        render_screen_02_big_numbers(),
        render_screen_03_graphs(),
        render_screen_04_relays(),
        render_screen_05_wifi(),
        render_screen_06_sht45(),
        render_screen_07_bh1750(),
        render_screen_08_soil_stick(),
        render_screen_09_soil_7in1()
    ]

    filenames = [
        "00_splash_nexus.png",
        "01_overview_dashboard.png",
        "02_big_numbers.png",
        "03_realtime_graphs.png",
        "04_relay_control.png",
        "05_wifi_captive_portal.png",
        "06_sht45_air_vpd.png",
        "07_bh1750_solar_par.png",
        "08_soil_stick_surface.png",
        "09_soil_7in1_tinyml.png"
    ]

    for img, fn in zip(screens, filenames):
        p = os.path.join(out_dir, fn)
        img.save(p, quality=95)
        print(f"  [✓] Saved: {p}")

    # Copy to root and latex_book for direct access
    screens[1].save("smart_farm_ui_overview.jpg", quality=95)
    screens[1].save("latex_book/figures/smart_farm_ui_overview.jpg", quality=95)

    # Master PR Poster
    print(">>> Rendering Ultra-HD Master PR Showcase Poster (3200x2160)...")
    poster = render_master_pr_poster(screens)
    poster.save(os.path.join(out_dir, "master_pr_showcase_poster.jpg"), quality=95)
    poster.save("leqs_agrisci_master_pr_showcase.jpg", quality=95)
    poster.save("latex_book/figures/leqs_agrisci_master_pr_showcase.jpg", quality=95)
    print(">>> All 10 Screens + Master PR Poster Generated Successfully!")

if __name__ == "__main__":
    main()
