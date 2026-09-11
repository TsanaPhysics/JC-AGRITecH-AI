#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
JC-AGRITecH2026 - High-Resolution Digital Screen Graphics Generator
Renders ultra-crisp, beautiful, modern cyber-agritech UI screen mockups
for the 4 sensor detail views and a unified showcase graphic.
"""

import os
from PIL import Image, ImageDraw, ImageFont

# Canvas dimensions (2x Retina scale of 480x320: 960x640)
W, H = 960, 640

# Fonts
FONT_REGULAR = "/System/Library/Fonts/Supplemental/SukhumvitSet.ttc"
FONT_BOLD = "/System/Library/Fonts/Supplemental/SukhumvitSet.ttc"
FONT_MONO = "/System/Library/Fonts/Menlo.ttc"

try:
    font_header = ImageFont.truetype(FONT_BOLD, 22, index=4) # SemiBold
    font_title = ImageFont.truetype(FONT_BOLD, 24, index=4)
    font_card_title = ImageFont.truetype(FONT_BOLD, 22, index=4)
    font_val_large = ImageFont.truetype(FONT_BOLD, 26, index=4)
    font_body = ImageFont.truetype(FONT_REGULAR, 19, index=1)
    font_body_bold = ImageFont.truetype(FONT_BOLD, 19, index=4)
    font_dim = ImageFont.truetype(FONT_REGULAR, 17, index=1)
    font_badge = ImageFont.truetype(FONT_BOLD, 18, index=4)
    font_ai_badge = ImageFont.truetype(FONT_BOLD, 17, index=4)
except Exception:
    font_header = ImageFont.load_default()
    font_title = ImageFont.load_default()
    font_card_title = ImageFont.load_default()
    font_val_large = ImageFont.load_default()
    font_body = ImageFont.load_default()
    font_body_bold = ImageFont.load_default()
    font_dim = ImageFont.load_default()
    font_badge = ImageFont.load_default()
    font_ai_badge = ImageFont.load_default()


def draw_header(draw, title_text, accent_color):
    """Draw top navigation header bar (y: 4 to 68)"""
    # Back button [< ย้อน]
    draw.rounded_rectangle([8, 6, 148, 66], radius=10, fill=(24, 34, 56), outline=(255, 255, 255), width=2)
    draw.text((78, 36), "< ย้อน", font=font_header, fill=(255, 255, 255), anchor="mm")

    # Center Title Box
    draw.rounded_rectangle([158, 6, 672, 66], radius=10, fill=(12, 20, 39), outline=accent_color, width=2)
    draw.text((415, 36), title_text, font=font_title, fill=accent_color, anchor="mm")

    # Next button [ถัดไป >]
    draw.rounded_rectangle([682, 6, 822, 66], radius=10, fill=(10, 42, 69), outline=(0, 229, 255), width=2)
    draw.text((752, 36), "ถัดไป >", font=font_header, fill=(0, 229, 255), anchor="mm")

    # Lang button [ไทย]
    draw.rounded_rectangle([832, 6, 952, 66], radius=10, fill=(20, 40, 60), outline=(0, 230, 118), width=2)
    draw.text((892, 36), "ไทย", font=font_header, fill=(0, 230, 118), anchor="mm")


def draw_progress_bar(draw, x, y, w, h, progress, fill_color, track_color=(16, 26, 48), border_color=(60, 75, 105)):
    """Draw sleek modern rounded progress bar with glowing fill"""
    draw.rounded_rectangle([x, y, x + w, y + h], radius=h//2, fill=track_color, outline=border_color, width=2)
    pw = max(0, min(w - 4, int((w - 4) * progress)))
    if pw > h - 4:
        draw.rounded_rectangle([x + 2, y + 2, x + 2 + pw, y + h - 2], radius=(h - 4)//2, fill=fill_color)
    elif pw > 0:
        draw.rectangle([x + 2, y + 2, x + 2 + pw, y + h - 2], fill=fill_color)


def draw_badge_tag(draw, x, y, text, fill_color, text_color=(255, 255, 255), w=44, h=26):
    """Draw a compact cyber pill tag for metric indicators"""
    draw.rounded_rectangle([x, y, x + w, y + h], radius=6, fill=fill_color)
    draw.text((x + w // 2, y + h // 2), text, font=font_ai_badge, fill=text_color, anchor="mm")
    return x + w + 10


def render_screen_sht45():
    """Render SHT45 Air Microclimate & VPD Physics Screen"""
    img = Image.new("RGB", (W, H), (3, 7, 18))
    draw = ImageDraw.Draw(img)

    draw_header(draw, "SHT45: บรรยากาศ & VPD", (0, 229, 255))

    # --- Card 1: Real-time Telemetry (y: 80 to 348) ---
    c1_y = 80
    draw.rounded_rectangle([20, c1_y, 940, c1_y + 268], radius=12, fill=(12, 20, 39), outline=(0, 229, 255), width=2)
    draw.text((40, c1_y + 16), "[SHT45] อุณหภูมิและความชื้นสัมพัทธ์ (Sensirion CMOSens)", font=font_card_title, fill=(0, 229, 255))

    # Temp & Humidity with sleek cyber tags
    tx = draw_badge_tag(draw, 40, c1_y + 58, "TEMP", (180, 70, 20), (255, 220, 200), w=62, h=28)
    draw.text((tx, c1_y + 58), "27.3 °C  (81.2 °F)", font=font_val_large, fill=(255, 153, 51))

    hx = draw_badge_tag(draw, 500, c1_y + 58, "RH%", (10, 100, 160), (200, 240, 255), w=54, h=28)
    draw.text((hx, c1_y + 58), "88.1 %RH", font=font_val_large, fill=(61, 204, 255))

    # Dew Point & Margin
    draw.text((40, c1_y + 108), "Dew Point: 25.2 °C   |   Dew Margin: 2.1 °C", font=font_body_bold, fill=(135, 240, 160))

    # Gauge Label & Value
    draw.text((40, c1_y + 150), "เกจระดับความชื้นอากาศ:", font=font_body, fill=(148, 163, 184))
    draw.text((920, c1_y + 150), "88.1 % (สูง)", font=font_body_bold, fill=(255, 170, 0), anchor="ra")

    # Full Width Bar
    draw_progress_bar(draw, 40, c1_y + 184, 880, 22, 0.881, fill_color=(255, 170, 0))

    # VPsat & VPact
    draw.text((40, c1_y + 224), "VPsat: 3.64 kPa   |   VPact: 3.21 kPa   |   FAO-56 Penman Equation", font=font_dim, fill=(100, 116, 139))

    # --- Card 2: Agronomic Advice & VPD (y: 364 to 624) ---
    c2_y = 364
    draw.rounded_rectangle([20, c2_y, 940, c2_y + 260], radius=12, fill=(12, 20, 39), outline=(0, 230, 118), width=2)
    draw.text((40, c2_y + 16), "คำแนะนำสำหรับเกษตรกร (VPD & สุขภาพพืช):", font=font_card_title, fill=(0, 230, 118))

    # Metric & Status Badge
    draw.text((40, c2_y + 58), "VPD: 0.43 kPa", font=font_val_large, fill=(255, 235, 59))

    # Status Pill (Right side)
    badge_x, badge_y, badge_w, badge_h = 440, c2_y + 54, 480, 40
    draw.rounded_rectangle([badge_x, badge_y, badge_x + badge_w, badge_y + badge_h], radius=8, fill=(60, 30, 10), outline=(255, 153, 51), width=2)
    draw.text((badge_x + badge_w // 2, badge_y + badge_h // 2), "[ สภาวะ: ความชื้นสูงเกินไป เสี่ยงเชื้อรา ]", font=font_badge, fill=(255, 170, 51), anchor="mm")

    # Advice details
    draw.text((40, c2_y + 110), "คำแนะนำ: พืชคายน้ำไม่ออก เสี่ยงเกิดเชื้อราและขาดแคลเซียม ควรเปิดระบายอากาศ", font=font_body, fill=(241, 245, 249))
    draw.text((40, c2_y + 148), "พ่นหมอกอัตโนมัติ: จะเริ่มทำงานเมื่อ Temp > 35 °C หรือ RH < 70%", font=font_dim, fill=(148, 163, 184))
    draw.text((40, c2_y + 184), "ระยะห่างจุดน้ำค้าง < 2 °C: มีความเสี่ยงเกิดหยดน้ำเกาะใบพืช แนะนำเร่งพัดลมกวนอากาศ", font=font_dim, fill=(255, 180, 80))
    draw.text((40, c2_y + 220), "I2C Telemetry: SDA=GPIO9, SCL=GPIO8 | Sensirion SHT45 (Accuracy: ±0.1°C, ±1.5%RH)", font=font_dim, fill=(100, 116, 139))

    return img


def render_screen_bh1750():
    """Render BH1750 Sun Dome Solar Radiation Screen"""
    img = Image.new("RGB", (W, H), (3, 7, 18))
    draw = ImageDraw.Draw(img)

    draw_header(draw, "BH1750: รังสีแสงโดมตะวัน", (255, 214, 0))

    # --- Card 1: Solar Radiation Telemetry (y: 80 to 348) ---
    c1_y = 80
    draw.rounded_rectangle([20, c1_y, 940, c1_y + 268], radius=12, fill=(12, 20, 39), outline=(255, 214, 0), width=2)
    draw.text((40, c1_y + 16), "[BH1750] ฟลักซ์รังสีดวงอาทิตย์โดมตะวัน (Solar Flux Density)", font=font_card_title, fill=(255, 214, 0))

    # Solar Rad & Lux
    rx = draw_badge_tag(draw, 40, c1_y + 58, "SOLAR", (150, 110, 0), (255, 255, 200), w=68, h=28)
    draw.text((rx, c1_y + 58), "0.2 W/m²", font=font_val_large, fill=(255, 235, 59))

    lx = draw_badge_tag(draw, 460, c1_y + 58, "LUX", (120, 120, 10), (255, 255, 200), w=54, h=28)
    draw.text((lx, c1_y + 58), "0.03 kLux  (28 Lux)", font=font_val_large, fill=(255, 255, 100))

    # Solar Constant Ratio
    draw.text((40, c1_y + 108), "สัดส่วนคงที่สุริยะ: 0.0 %   (จาก 1361 W/m² Max Solar Constant)", font=font_body_bold, fill=(135, 240, 160))

    # Gauge
    draw.text((40, c1_y + 150), "ระดับรังสีดวงอาทิตย์ (0 - 1000 W/m²):", font=font_body, fill=(148, 163, 184))
    draw.text((920, c1_y + 150), "0.2 W/m²", font=font_body_bold, fill=(255, 214, 0), anchor="ra")

    draw_progress_bar(draw, 40, c1_y + 184, 880, 22, 0.02, fill_color=(255, 214, 0))

    draw.text((40, c1_y + 224), "Optical Calibration: 1 Lux = 0.0079 W/m²   |   Cosine Diffuser 360° All-weather", font=font_dim, fill=(100, 116, 139))

    # --- Card 2: Photobiology Advice (y: 364 to 624) ---
    c2_y = 364
    draw.rounded_rectangle([20, c2_y, 940, c2_y + 260], radius=12, fill=(12, 20, 39), outline=(0, 230, 118), width=2)
    draw.text((40, c2_y + 16), "คำแนะนำการสังเคราะห์แสง (PAR & DLI Photobiology):", font=font_card_title, fill=(0, 230, 118))

    draw.text((40, c2_y + 58), "PAR: ~0 µmol/(m²·s)", font=font_val_large, fill=(255, 235, 59))

    # Status Pill
    badge_x, badge_y, badge_w, badge_h = 440, c2_y + 54, 480, 40
    draw.rounded_rectangle([badge_x, badge_y, badge_x + badge_w, badge_y + badge_h], radius=8, fill=(20, 30, 50), outline=(100, 149, 237), width=2)
    draw.text((badge_x + badge_w // 2, badge_y + badge_h // 2), "[ แดดร่ม / พืชพักตัวยามค่ำคืน ]", font=font_badge, fill=(130, 180, 255), anchor="mm")

    draw.text((40, c2_y + 110), "คำแนะนำ: แสงน้อยเกินไป พืชหยุดสร้างแป้งและน้ำตาล ไม่ควรให้น้ำหรือปุ๋ยมากเกินไป", font=font_body, fill=(241, 245, 249))
    draw.text((40, c2_y + 148), "ดัชนี DLI: ปริมาณโมลของแสงต่อวัน ช่วยประเมินพลังงานสะสมเพื่อการติดดอกออกผล", font=font_dim, fill=(148, 163, 184))
    draw.text((40, c2_y + 184), "โดมตะวัน: ตัวรับแสงทรงโดมโค้งรับรังสีรอบทิศทาง 360 องศา ชดเชยมุมตกกระทบของแสงอาทิตย์", font=font_dim, fill=(148, 163, 184))
    draw.text((40, c2_y + 220), "Sensor Hardware: ROHM BH1750FVI 16-bit | I2C Addr: 0x23 | ฝาครอบ IP65 Optical Grade", font=font_dim, fill=(100, 116, 139))

    return img


def render_screen_soil_stick():
    """Render Soil Stick Surface Moisture Screen"""
    img = Image.new("RGB", (W, H), (3, 7, 18))
    draw = ImageDraw.Draw(img)

    draw_header(draw, "SOIL STICK: ดินตื้น 0-10ซม.", (0, 229, 255))

    # --- Card 1: Topsoil Telemetry (y: 80 to 348) ---
    c1_y = 80
    draw.rounded_rectangle([20, c1_y, 940, c1_y + 268], radius=12, fill=(12, 20, 39), outline=(0, 229, 255), width=2)
    draw.text((40, c1_y + 16), "[Soil Stick] ความชื้นผิวดินชั้นตื้น (0 - 10 ซม.)", font=font_card_title, fill=(0, 229, 255))

    # Moisture & Voltage
    mx = draw_badge_tag(draw, 40, c1_y + 58, "SOIL", (10, 120, 100), (200, 255, 240), w=58, h=28)
    draw.text((mx, c1_y + 58), "60.8 % (ผิวดิน)", font=font_val_large, fill=(61, 204, 255))

    ax = draw_badge_tag(draw, 460, c1_y + 58, "ADC", (160, 80, 10), (255, 230, 200), w=54, h=28)
    draw.text((ax, c1_y + 58), "1.64 V  |  Raw ADC: 3280", font=font_val_large, fill=(255, 153, 51))

    # Trigger level
    draw.text((40, c1_y + 108), "เกณฑ์วิกฤตรดน้ำ: < 40.0 %   [ระบบเตรียมพร้อมจ่ายน้ำอัตโนมัติ]", font=font_body_bold, fill=(135, 240, 160))

    # Gauge
    draw.text((40, c1_y + 150), "เกจระดับความชื้นผิวดิน (0 - 100%):", font=font_body, fill=(148, 163, 184))
    draw.text((920, c1_y + 150), "60.8 % (ชุ่มชื้น)", font=font_body_bold, fill=(0, 230, 118), anchor="ra")

    draw_progress_bar(draw, 40, c1_y + 184, 880, 22, 0.608, fill_color=(0, 230, 118))

    draw.text((40, c1_y + 224), "Dry Calibration: 3280 ADC   |   Wet Calibration: 1320 ADC   |   Capacitive Sensing", font=font_dim, fill=(100, 116, 139))

    # --- Card 2: Agronomic Irrigation Strategy (y: 364 to 624) ---
    c2_y = 364
    draw.rounded_rectangle([20, c2_y, 940, c2_y + 260], radius=12, fill=(12, 20, 39), outline=(0, 230, 118), width=2)
    draw.text((40, c2_y + 16), "คำแนะนำการให้น้ำ & กลยุทธ์อัตโนมัติ:", font=font_card_title, fill=(0, 230, 118))

    draw.text((40, c2_y + 58), "สถานะผิวดิน:", font=font_val_large, fill=(255, 235, 59))

    # Status Pill
    badge_x, badge_y, badge_w, badge_h = 240, c2_y + 54, 680, 40
    draw.rounded_rectangle([badge_x, badge_y, badge_x + badge_w, badge_y + badge_h], radius=8, fill=(10, 40, 20), outline=(0, 230, 118), width=2)
    draw.text((badge_x + badge_w // 2, badge_y + badge_h // 2), "[ ชุ่มชื้นดี (งดการรดน้ำเพื่อประหยัดทรัพยากร) ]", font=font_badge, fill=(0, 230, 118), anchor="mm")

    draw.text((40, c2_y + 110), "คำแนะนำ: รากพืชดูดซึมน้ำและปุ๋ยได้สะดวก งดการให้น้ำเพิ่มเพื่อป้องกันดินแน่นทึบ", font=font_body, fill=(241, 245, 249))
    draw.text((40, c2_y + 148), "ระบบอัตโนมัติ: รดน้ำเมื่อ < 40.0% --> ตัดน้ำเมื่อถึง 65.0% (ระบบฮิสเทอรีซิส)", font=font_dim, fill=(148, 163, 184))
    draw.text((40, c2_y + 184), "ระบบความปลอดภัย: ตัดการทำงานปั๊มฉุกเฉินภายใน 5 นาที ป้องกันมอเตอร์ปั๊มเสียหาย", font=font_dim, fill=(255, 180, 80))
    draw.text((40, c2_y + 220), "Hardware: ESP32-S3 Port A1 (GPIO1) | 12-bit SAR ADC | แผ่นทองแดงทนการกัดกร่อน", font=font_dim, fill=(100, 116, 139))

    return img


def render_screen_soil_7in1():
    """Render Soil 7-in-1 Root Zone & TinyML AI Neural Calibrated Screen"""
    img = Image.new("RGB", (W, H), (3, 7, 18))
    draw = ImageDraw.Draw(img)

    draw_header(draw, "SOIL 7-IN-1: เขตราก & NPK", (0, 230, 118))

    # --- Card 1: Root Zone & TinyML Edge AI (y: 80 to 420) ---
    c1_y = 80
    draw.rounded_rectangle([20, c1_y, 940, c1_y + 340], radius=12, fill=(12, 20, 39), outline=(0, 230, 118), width=2)
    draw.text((40, c1_y + 16), "[Soil 7-in-1] คุณสมบัติเขตรากพืชลึก (Root Zone Physics)", font=font_card_title, fill=(0, 230, 118))

    # Row 1: Moisture, Temp, pH with tags
    rx = draw_badge_tag(draw, 40, c1_y + 52, "ROOT", (10, 80, 150), (200, 230, 255), w=56, h=26)
    draw.text((rx, c1_y + 54), "ชื้นราก: 0.0 %", font=font_body_bold, fill=(61, 204, 255))

    tx = draw_badge_tag(draw, 340, c1_y + 52, "TEMP", (160, 70, 20), (255, 220, 200), w=56, h=26)
    draw.text((tx, c1_y + 54), "ดิน: 27.2 °C", font=font_body_bold, fill=(255, 153, 51))

    px = draw_badge_tag(draw, 640, c1_y + 52, "pH", (140, 90, 10), (255, 240, 200), w=40, h=26)
    draw.text((px, c1_y + 54), "9.00 (ด่าง)", font=font_body_bold, fill=(255, 180, 50))

    # Row 2: EC, TDS, Depth
    draw.text((40, c1_y + 92), "EC: 0 µS/cm   |   TDS: 0 ppm   |   ระดับความลึกเขตราก: 10 - 30 ซม.", font=font_body, fill=(255, 235, 59))

    # Row 3: Raw NPK chips
    draw.text((40, c1_y + 130), "NPK ดิบ (หัววัดเซนเซอร์):", font=font_dim, fill=(148, 163, 184))
    chips = [("N: 0", 280, (24, 34, 56)), ("P: 0", 380, (10, 48, 30)), ("K: 0", 480, (50, 30, 10))]
    for label, cx, fillc in chips:
        draw.rounded_rectangle([cx, c1_y + 126, cx + 80, c1_y + 158], radius=6, fill=fillc, outline=(100, 116, 139), width=1)
        draw.text((cx + 40, c1_y + 142), label, font=font_body_bold, fill=(255, 255, 255), anchor="mm")
    draw.text((580, c1_y + 132), "mg/kg", font=font_dim, fill=(148, 163, 184))

    # --- Cyber Card: TinyML Edge AI Calibrated ---
    ai_box_y = c1_y + 172
    draw.rounded_rectangle([36, ai_box_y, 924, ai_box_y + 152], radius=10, fill=(4, 24, 40), outline=(0, 229, 255), width=2)
    draw_badge_tag(draw, 56, ai_box_y + 14, "AI", (0, 180, 216), (255, 255, 255), w=36, h=24)
    draw.text((102, ai_box_y + 14), "TinyML Edge AI Calibrated (Neural Denoised & Decoupled)", font=font_card_title, fill=(0, 229, 255))

    # 4 AI Pills: N, P, K, pH
    ai_pills = [
        ("AI-N: 38.9", 56, 180, (30, 20, 70), (124, 77, 255)),
        ("AI-P: 17.6", 256, 180, (10, 50, 30), (0, 230, 118)),
        ("AI-K: 0.0", 456, 180, (60, 40, 10), (255, 170, 0)),
        ("AI-pH: 9.50", 656, 240, (60, 20, 20), (255, 82, 82)),
    ]
    for label, px, pw, bgc, brdc in ai_pills:
        draw.rounded_rectangle([px, ai_box_y + 52, px + pw, ai_box_y + 94], radius=8, fill=bgc, outline=brdc, width=2)
        draw.text((px + pw // 2, ai_box_y + 73), label, font=font_ai_badge, fill=(255, 255, 255), anchor="mm")

    draw.text((56, ai_box_y + 112), "True Moist (AI): 20.2 %   (ชดเชยอุณหภูมิและความชื้นแม่นยำ ไร้การเบี่ยงเบนของสัญญาณ)", font=font_body_bold, fill=(0, 230, 118))

    # --- Card 2: Agronomic Advice & Fertilizer (y: 436 to 624) ---
    c2_y = 436
    draw.rounded_rectangle([20, c2_y, 940, c2_y + 188], radius=12, fill=(12, 20, 39), outline=(255, 214, 0), width=2)
    draw.text((40, c2_y + 14), "คำแนะนำการใส่ปุ๋ย & ปรับปรุงสภาพดิน:", font=font_card_title, fill=(255, 214, 0))

    draw.text((40, c2_y + 50), "Total Available NPK: 56.5 mg/kg", font=font_body_bold, fill=(255, 235, 59))

    # Status Pill
    badge_x, badge_y, badge_w, badge_h = 420, c2_y + 44, 500, 38
    draw.rounded_rectangle([badge_x, badge_y, badge_x + badge_w, badge_y + badge_h], radius=8, fill=(60, 30, 10), outline=(255, 153, 51), width=2)
    draw.text((badge_x + badge_w // 2, badge_y + badge_h // 2), "[ ดินเป็นด่าง: พืชเสี่ยงขาดธาตุเหล็กและสังกะสี ]", font=font_badge, fill=(255, 170, 51), anchor="mm")

    draw.text((40, c2_y + 96), "คำแนะนำ: ดินด่างจัด แนะนำเติมปุ๋ยอินทรีย์/กรดฮิวมัส และให้ปุ๋ยธาตุอาหารรองเสริมทางใบ", font=font_body, fill=(241, 245, 249))
    draw.text((40, c2_y + 130), "RS485 MODBUS RTU: UART2 (TX=GPIO40, RX=GPIO41) | Baud: 9600-8-N-1 | Slave: 0x01 | Probe: 316L", font=font_dim, fill=(100, 116, 139))

    return img


def render_composite_showcase(img1, img2, img3, img4):
    """Render 2x2 Showcase Master Image (2040 x 1440) with Cyber Frames and Callouts"""
    sw, sh = 960, 640
    cw, ch = 2040, 1440
    comp = Image.new("RGB", (cw, ch), (2, 6, 14))
    draw = ImageDraw.Draw(comp)

    # Main Showcase Title Header
    try:
        font_main_title = ImageFont.truetype(FONT_BOLD, 38, index=4)
        font_sub_title = ImageFont.truetype(FONT_REGULAR, 22, index=1)
        font_label = ImageFont.truetype(FONT_BOLD, 20, index=4)
    except Exception:
        font_main_title = ImageFont.load_default()
        font_sub_title = ImageFont.load_default()
        font_label = ImageFont.load_default()

    draw.text((cw // 2, 40), "JC AGRITecH + AI: SMART SENSOR TELEMETRY & DISPLAY OVERVIEW", font=font_main_title, fill=(0, 229, 255), anchor="mm")
    draw.text((cw // 2, 80), "ระบบตรวจวัดและวิเคราะห์ฟิสิกส์เกษตรแม่นยำ พร้อมการชดเชยสัญญาณรบกวนด้วย TinyML Edge AI บนจอ IPS 3.5 นิ้ว", font=font_sub_title, fill=(203, 213, 225), anchor="mm")

    # Grid Placement (Top-Left, Top-Right, Bottom-Left, Bottom-Right)
    positions = [
        (40, 120, img3, "1. SOIL STICK: เซนเซอร์ความชื้นผิวดินชั้นตื้น (0-10 ซม.)", (0, 229, 255)),
        (1040, 120, img4, "2. SOIL 7-IN-1: เซนเซอร์เขตรากลึก & ปัญญาประดิษฐ์ TinyML Edge AI", (0, 230, 118)),
        (40, 780, img1, "3. SHT45: สภาพบรรยากาศ, อุณหภูมิ, ความชื้น & ฟิสิกส์ VPD", (0, 229, 255)),
        (1040, 780, img2, "4. BH1750: โดมตะวัน 360°, ฟลักซ์รังสีแสงอาทิตย์ & โฟโตไบโอโลยี PAR", (255, 214, 0))
    ]

    for gx, gy, simg, caption, col in positions:
        # Outer Glowing Frame
        draw.rounded_rectangle([gx - 6, gy - 6, gx + sw + 6, gy + sh + 6], radius=16, fill=(8, 14, 28), outline=col, width=3)
        # Paste Screen
        comp.paste(simg, (gx, gy))
        # Label below
        draw.rounded_rectangle([gx, gy + sh - 44, gx + sw, gy + sh], radius=8, fill=(10, 18, 36))
        draw.text((gx + 20, gy + sh - 22), caption, font=font_label, fill=col, anchor="lm")

    return comp


def main():
    print(">>> Rendering 4K/Retina Digital Screens for JC-AGRITecH2026...")
    os.makedirs("latex_book/figures", exist_ok=True)

    img_sht45 = render_screen_sht45()
    img_bh1750 = render_screen_bh1750()
    img_soil_stick = render_screen_soil_stick()
    img_soil_7in1 = render_screen_soil_7in1()

    # Save individual screens
    img_sht45.save("latex_book/figures/ui_screen_sht45.png", quality=95)
    img_bh1750.save("latex_book/figures/ui_screen_bh1750.png", quality=95)
    img_soil_stick.save("latex_book/figures/ui_screen_soil_stick.png", quality=95)
    img_soil_7in1.save("latex_book/figures/ui_screen_soil_7in1.png", quality=95)

    img_sht45.save("ui_screen_sht45.png", quality=95)
    img_bh1750.save("ui_screen_bh1750.png", quality=95)
    img_soil_stick.save("ui_screen_soil_stick.png", quality=95)
    img_soil_7in1.save("ui_screen_soil_7in1.png", quality=95)
    print(">>> Individual screens saved successfully!")

    # Render & Save Composite Showcase
    showcase = render_composite_showcase(img_sht45, img_bh1750, img_soil_stick, img_soil_7in1)
    showcase.save("latex_book/figures/smart_farm_sensor_details_showcase.jpg", quality=95)
    showcase.save("smart_farm_sensor_details_showcase.jpg", quality=95)
    print(">>> Composite Master Showcase saved successfully to smart_farm_sensor_details_showcase.jpg!")


if __name__ == "__main__":
    main()
