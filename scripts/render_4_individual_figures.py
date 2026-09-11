#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
JC-AGRITecH2026 - Standalone Presentation Figures Generator
Renders 4 separate, ultra-premium, high-resolution showcase graphics
with hardware bezel frame, tech metadata, and agronomic callouts.
"""

import os
from PIL import Image, ImageDraw, ImageFont

# Screen size
SW, SH = 960, 640

# Overall Canvas for Standalone Presentation Figures
CW, CH = 1280, 1060

# Fonts
FONT_REGULAR = "/System/Library/Fonts/Supplemental/SukhumvitSet.ttc"
FONT_BOLD = "/System/Library/Fonts/Supplemental/SukhumvitSet.ttc"

try:
    font_showcase_title = ImageFont.truetype(FONT_BOLD, 30, index=4)
    font_showcase_sub = ImageFont.truetype(FONT_REGULAR, 18, index=1)
    font_panel_title = ImageFont.truetype(FONT_BOLD, 19, index=4)
    font_panel_text = ImageFont.truetype(FONT_REGULAR, 16, index=1)
    font_panel_code = ImageFont.truetype(FONT_BOLD, 15, index=4)
    font_badge_cat = ImageFont.truetype(FONT_BOLD, 15, index=4)
except Exception:
    font_showcase_title = ImageFont.load_default()
    font_showcase_sub = ImageFont.load_default()
    font_panel_title = ImageFont.load_default()
    font_panel_text = ImageFont.load_default()
    font_panel_code = ImageFont.load_default()
    font_badge_cat = ImageFont.load_default()


def create_standalone_showcase(
    screen_img_path,
    output_path,
    sensor_num,
    sensor_name_en,
    sensor_name_th,
    accent_color,
    theme_bg_tint,
    panel_items
):
    """
    Renders a dedicated standalone presentation graphic with:
    1. Top Title & Category Ribbon
    2. Bezel Hardware Frame around the 960x640 screen
    3. Bottom 3-Column Technical Infographic Card
    """
    canvas = Image.new("RGB", (CW, CH), (4, 8, 18))
    draw = ImageDraw.Draw(canvas)

    # Ambient Top Glow Gradient (subtle)
    for i in range(120):
        alpha = int(25 * (1.0 - i / 120.0))
        r = int(accent_color[0] * alpha / 255)
        g = int(accent_color[1] * alpha / 255)
        b = int(accent_color[2] * alpha / 255)
        draw.line([(0, i), (CW, i)], fill=(r + 4, g + 8, b + 18))

    # --- 1. Top Title Header ---
    # Category Pill
    badge_text = f"JC AGRITecH + AI  |  SENSOR FIGURE {sensor_num}"
    bw, bh = 340, 32
    draw.rounded_rectangle([40, 26, 40 + bw, 26 + bh], radius=8, fill=(16, 26, 46), outline=accent_color, width=2)
    draw.text((40 + bw // 2, 26 + bh // 2), badge_text, font=font_badge_cat, fill=accent_color, anchor="mm")

    # Main Title
    draw.text((40, 72), f"{sensor_name_en} : {sensor_name_th}", font=font_showcase_title, fill=(255, 255, 255))
    draw.text((40, 114), "ระบบตรวจสอบและวิเคราะห์ฟิสิกส์เกษตรแม่นยำ พร้อมการชดเชยสัญญาณรบกวนด้วย TinyML Edge AI บนจอ IPS 3.5 นิ้ว", font=font_showcase_sub, fill=(148, 163, 184))

    # Status live dot
    draw.ellipse([CW - 180, 40, CW - 164, 56], fill=(0, 230, 118))
    draw.text((CW - 150, 48), "HARDWARE ACTIVE", font=font_panel_code, fill=(0, 230, 118), anchor="lm")

    # --- 2. Bezel Hardware Frame for Screen ---
    # Screen center
    bx = (CW - SW) // 2  # 160
    by = 156

    # Outer device chassis (Controller Box texture)
    chassis_pad = 18
    draw.rounded_rectangle(
        [bx - chassis_pad, by - chassis_pad, bx + SW + chassis_pad, by + SH + chassis_pad],
        radius=20,
        fill=(14, 20, 32),
        outline=(50, 65, 88),
        width=3
    )

    # Metallic / Bezel Inner Rim
    inner_pad = 6
    draw.rounded_rectangle(
        [bx - inner_pad, by - inner_pad, bx + SW + inner_pad, by + SH + inner_pad],
        radius=14,
        fill=(8, 12, 20),
        outline=accent_color,
        width=2
    )

    # Screws on 4 corners of chassis
    screw_dist = 8
    screws = [
        (bx - chassis_pad + screw_dist, by - chassis_pad + screw_dist),
        (bx + SW + chassis_pad - screw_dist, by - chassis_pad + screw_dist),
        (bx - chassis_pad + screw_dist, by + SH + chassis_pad - screw_dist),
        (bx + SW + chassis_pad - screw_dist, by + SH + chassis_pad - screw_dist)
    ]
    for sx, sy in screws:
        draw.ellipse([sx - 4, sy - 4, sx + 4, sy + 4], fill=(70, 85, 110), outline=(30, 40, 55), width=1)
        draw.line([(sx - 3, sy), (sx + 3, sy)], fill=(120, 135, 160), width=1)

    # Paste Screen Image
    screen_img = Image.open(screen_img_path)
    canvas.paste(screen_img, (bx, by))

    # --- 3. Bottom 3-Column Infographic Cards ---
    info_y = by + SH + chassis_pad + 18
    info_h = 196
    col_w = (CW - 80 - 32) // 3  # (1200 - 32) / 3 = 389 px
    col_gap = 16

    for i, item in enumerate(panel_items):
        cx = 40 + i * (col_w + col_gap)
        # Card container
        draw.rounded_rectangle(
            [cx, info_y, cx + col_w, info_y + info_h],
            radius=12,
            fill=(10, 18, 34),
            outline=(40, 56, 82),
            width=2
        )

        # Top Accent Header Stripe
        draw.rounded_rectangle([cx, info_y, cx + col_w, info_y + 40], radius=10, fill=theme_bg_tint)
        draw.text((cx + 16, info_y + 20), item["title"], font=font_panel_title, fill=accent_color, anchor="lm")

        # Key details list
        text_y = info_y + 54
        for line in item["lines"]:
            draw.text((cx + 16, text_y), line, font=font_panel_text, fill=(226, 232, 240))
            text_y += 28

        # Footer badge or highlight
        if "badge" in item:
            draw.rounded_rectangle([cx + 16, info_y + info_h - 36, cx + col_w - 16, info_y + info_h - 12], radius=6, fill=(16, 28, 48))
            draw.text((cx + 24, info_y + info_h - 24), item["badge"], font=font_panel_code, fill=accent_color, anchor="lm")

    # Save output
    canvas.save(output_path, quality=95)
    print(f">>> Saved: {output_path}")


def main():
    print(">>> Generating 4 Standalone Presentation Figures...")
    os.makedirs("latex_book/figures", exist_ok=True)

    # =========================================================================
    # Figure 1: SOIL STICK (Topsoil Moisture & Capacitive ADC)
    # =========================================================================
    create_standalone_showcase(
        screen_img_path="latex_book/figures/ui_screen_soil_stick.png",
        output_path="latex_book/figures/figure_sensor_01_soil_stick.jpg",
        sensor_num="01",
        sensor_name_en="SOIL STICK CAPACITIVE",
        sensor_name_th="เซนเซอร์วัดความชื้นผิวดินชั้นตื้น (0 - 10 ซม.)",
        accent_color=(0, 229, 255),
        theme_bg_tint=(10, 36, 56),
        panel_items=[
            {
                "title": "1. ฮาร์ดแวร์และการเชื่อมต่อ",
                "lines": [
                    "• พอร์ตบอร์ด: ESP32-S3 Analog Port A1",
                    "• ขาเชื่อมต่อ: GPIO 1 (12-bit SAR ADC)",
                    "• แรงดันเอาต์พุต: 0.0 - 3.3 V (Analog)",
                    "• วัสดุแผ่น: ทองแดงคาปาซิทีฟเคลือบกันสนิม"
                ],
                "badge": "BUS: SAR ADC | VOLTAGE: 3.3V"
            },
            {
                "title": "2. การสอบเทียบและฟิสิกส์ดิน",
                "lines": [
                    "• จุดแห้ง (Dry Air): 3280 ADC",
                    "• จุดอิ่มตัวน้ำ (Wet Water): 1320 ADC",
                    "• ชั้นดินเป้าหมาย: ผิวดิน 0 - 10 ซม.",
                    "• การวัด: ค่าความจุไฟฟ้าความถี่สูง (HF)"
                ],
                "badge": "CALIBRATION: 2-POINT LINEAR"
            },
            {
                "title": "3. กลยุทธ์การให้น้ำอัตโนมัติ",
                "lines": [
                    "• เกณฑ์วิกฤต: < 40.0% เริ่มเปิดปั๊มรดน้ำ",
                    "• เกณฑ์ตัดน้ำ: >= 65.0% หยุดจ่ายน้ำ",
                    "• ป้องกันปั๊มไหม้: ตัดฉุกเฉินภายใน 5 นาที",
                    "• ระบบควบคุม: ฮิสเทอรีซิส (Hysteresis)"
                ],
                "badge": "ACTION: AUTO HYSTERESIS PUMP"
            }
        ]
    )

    # =========================================================================
    # Figure 2: SOIL 7-IN-1 (Root Zone & TinyML Edge AI)
    # =========================================================================
    create_standalone_showcase(
        screen_img_path="latex_book/figures/ui_screen_soil_7in1.png",
        output_path="latex_book/figures/figure_sensor_02_soil_7in1.jpg",
        sensor_num="02",
        sensor_name_en="SOIL 7-IN-1 + TinyML AI",
        sensor_name_th="เซนเซอร์เขตรากลึกและโครงข่ายประสาทเทียม TinyML",
        accent_color=(0, 230, 118),
        theme_bg_tint=(12, 42, 28),
        panel_items=[
            {
                "title": "1. สัญญาณ RS485 Modbus RTU",
                "lines": [
                    "• พอร์ตสื่อสาร: UART2 (TX=40, RX=41)",
                    "• มาตรฐาน: Modbus RTU (Slave ID: 0x01)",
                    "• ความเร็วบัส: 9600-8-N-1 (CRC16 Check)",
                    "• หัววัด: สเตนเลสสตีลเกรดการแพทย์ 316L"
                ],
                "badge": "BUS: RS485 RTU | PROBE: 316L"
            },
            {
                "title": "2. TinyML Neural Calibrator",
                "lines": [
                    "• สถาปัตยกรรม: MLP 10 -> 16 -> 16 -> 5",
                    "• ปัญหาที่แก้: ตัดสัญญาณรบกวนอุณหภูมิ/ชื้น",
                    "• พารามิเตอร์ AI: AI-N, AI-P, AI-K, AI-pH",
                    "• ความชื้นจริง: Decoupled True Moisture"
                ],
                "badge": "AI MODEL: MLP ZERO-HEAP ON ESP32-S3"
            },
            {
                "title": "3. คำแนะนำการจัดการธาตุอาหาร",
                "lines": [
                    "• ค่า pH วิกฤต: < 5.5 (กรด) / > 7.5 (ด่าง)",
                    "• ธาตุอาหารหลัก: Total Available NPK",
                    "• ความเค็ม EC: เตือนดินเค็มหาก > 2000 µS",
                    "• คำแนะนำ: เติมปุ๋ยอินทรีย์/โดโลไมท์ตรงจุด"
                ],
                "badge": "AGRONOMY: PRECISION FERTILIZER"
            }
        ]
    )

    # =========================================================================
    # Figure 3: SHT45 (Air Microclimate & VPD Physics)
    # =========================================================================
    create_standalone_showcase(
        screen_img_path="latex_book/figures/ui_screen_sht45.png",
        output_path="latex_book/figures/figure_sensor_03_sht45.jpg",
        sensor_num="03",
        sensor_name_en="SENSIRION SHT45 & VPD",
        sensor_name_th="สภาพบรรยากาศ อุณหภูมิ ความชื้น & ฟิสิกส์ VPD",
        accent_color=(0, 229, 255),
        theme_bg_tint=(10, 36, 56),
        panel_items=[
            {
                "title": "1. สเปกฮาร์ดแวร์ I2C SHT45",
                "lines": [
                    "• พอร์ตบัส: I2C (SDA=GPIO9, SCL=GPIO8)",
                    "• ความเร็วบัส: 10 kHz ฮาร์ดแวร์ฟิลเตอร์กรอง",
                    "• I2C Address: 0x44 (Sensirion SHT45)",
                    "• ความแม่นยำ: ±0.1 °C, ±1.5 %RH ระดับแล็บ"
                ],
                "badge": "BUS: I2C 10kHz | SENSOR: SHT45"
            },
            {
                "title": "2. การคำนวณฟิสิกส์บรรยากาศ",
                "lines": [
                    "• สมการไอน้ำอิ่มตัว: Tetens / FAO-56",
                    "• ดัชนี VPD: VPsat - VPact (kPa)",
                    "• จุดน้ำค้าง Dew Point: Magnus-Tetens",
                    "• Dew Margin: เตือนการเกิดหยดน้ำเกาะใบ"
                ],
                "badge": "PHYSICS: FAO-56 PENMAN & TETENS"
            },
            {
                "title": "3. ระบบระบายอากาศและพ่นหมอก",
                "lines": [
                    "• สภาวะสมบูรณ์: VPD 0.8 - 1.2 kPa ปากใบเปิด",
                    "• ความชื้นสูง: VPD < 0.8 kPa ระบายอากาศ",
                    "• อากาศแห้งจัด: VPD > 1.2 kPa พ่นหมอกทันที",
                    "• กลยุทธ์พ่นหมอก: Temp > 35°C หรือ RH < 70%"
                ],
                "badge": "CONTROL: TRANSPIRATION OPTIMIZER"
            }
        ]
    )

    # =========================================================================
    # Figure 4: BH1750 (Sun Dome Solar Radiation & PAR)
    # =========================================================================
    create_standalone_showcase(
        screen_img_path="latex_book/figures/ui_screen_bh1750.png",
        output_path="latex_book/figures/figure_sensor_04_bh1750.jpg",
        sensor_num="04",
        sensor_name_en="BH1750 SUN DOME RADIOMETER",
        sensor_name_th="โดมตะวัน 360°, ฟลักซ์รังสีแสงอาทิตย์ & PAR",
        accent_color=(255, 214, 0),
        theme_bg_tint=(48, 40, 10),
        panel_items=[
            {
                "title": "1. ข้อมูลชิปเซนเซอร์แสง BH1750",
                "lines": [
                    "• ชิปเซนเซอร์: ROHM BH1750FVI 16-bit",
                    "• พอร์ตบัส: I2C (SDA=GPIO9, SCL=GPIO8)",
                    "• I2C Address: 0x23 (โหมด High-Resolution)",
                    "• ช่วงการวัด: 1 - 65,535 Lux (กว้างพิเศษ)"
                ],
                "badge": "BUS: I2C 0x23 | CHIP: ROHM 16-BIT"
            },
            {
                "title": "2. ออปติกและการแปลงรังสี",
                "lines": [
                    "• ตัวรับแสง: อะคริลิกทรงโดม 360° กันน้ำ IP65",
                    "• แผ่นกระจายแสง: Cosine Diffuser ชดเชยมุม",
                    "• Optical Calibration: 1 Lux = 0.0079 W/m²",
                    "• สัดส่วนสุริยะ: เทียบค่าคงที่สุริยะ 1361 W/m²"
                ],
                "badge": "OPTICS: 360° COSINE DIFFUSER IP65"
            },
            {
                "title": "3. โฟโตไบโอโลยี PAR & DLI",
                "lines": [
                    "• สังเคราะห์แสง: PAR (PPFD) ~ 2.1 × W/m²",
                    "• สภาวะแสง: แดดร่ม (<500 lx) สู่แดดจัดมาก",
                    "• ดัชนี DLI: โมลแสงต่อวันเพื่อผลผลิตสูงสุด",
                    "• การจัดการ: ควบคุมการกางสแลนพรางแสง 50%"
                ],
                "badge": "PHOTOBIOLOGY: PAR / PPFD / DLI"
            }
        ]
    )

    # Copy to project root for easy access
    os.system("cp latex_book/figures/figure_sensor_*.jpg .")
    print(">>> All 4 Standalone Presentation Figures generated and copied to workspace root successfully!")


if __name__ == "__main__":
    main()
