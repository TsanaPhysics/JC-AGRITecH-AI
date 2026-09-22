#!/usr/bin/env python3
"""
สร้างภาพตารางแถบสีมาตรฐาน NPK และ pH ดิน ตามข้อมูลในตารางที่ 2.3 ของ ch02_literature_review.md
ตรงตามรหัสสีดิจิทัล (HEX) และช่วงค่าเชิงปริมาณ 100%
"""

from PIL import Image, ImageDraw, ImageFont

# กำหนดขนาดภาพความละเอียดสูง (2000 x 1350 พิกเซล)
width, height = 2000, 1380
img = Image.new('RGB', (width, height), color=(15, 23, 42)) # สีพื้นหลังเทาเข้มวิทยาศาสตร์ #0f172a
draw = ImageDraw.Draw(img)

# โหลดฟอนต์ Thonburi จาก macOS
font_title = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 36, index=1)
font_subtitle = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 22, index=0)
font_header = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 26, index=1)
font_swatch_tier = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 20, index=1)
font_swatch_range = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 19, index=0)
font_swatch_hex = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 17, index=1)
font_method = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 18, index=0)
font_footer = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 18, index=0)

# ส่วนหัวเรื่อง
draw.rounded_rectangle([40, 30, width - 40, 130], radius=12, fill=(30, 41, 59), outline=(56, 189, 248), width=2)
draw.text((65, 45), "เกณฑ์มาตรฐานแถบสีเทียบเคียงและช่วงความเข้มข้นเชิงปริมาณของธาตุอาหารในดิน NPK และ pH", font=font_title, fill=(56, 189, 248))
draw.text((65, 92), "อ้างอิงตามตารางที่ 2.3 รายงานวิจัย มรภ.รำไพพรรณี มาตรฐานกรมพัฒนาที่ดิน (LDD Thailand) และ SSSA / USDA", font=font_subtitle, fill=(148, 163, 184))

# ฟังก์ชันแปลง HEX เป็น RGB
def hex_to_rgb(hex_str):
    hex_str = hex_str.lstrip('#')
    return tuple(int(hex_str[i:i+2], 16) for i in (0, 2, 4))

# ตรวจสอบสีตัวอักษรให้อ่านง่ายบนพื้นสี (Contrast ratio)
def get_contrast_color(rgb):
    luminance = (0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2]) / 255
    return (0, 0, 0) if luminance > 0.55 else (255, 255, 255)

# ข้อมูลทั้ง 4 พารามิเตอร์ตามตารางที่ 2.3
sections = [
    {
        "title": "1. ความเป็นกรด-ด่างของดิน (Soil pH)",
        "method": "วิธีอินดิเคเตอร์สากล LDD / USDA Standard (Universal Indicator)",
        "items": [
            {"tier": "กรดจัดรุนแรง", "range": "< 4.5 pH", "hex": "#e63946"},
            {"tier": "กรดจัด", "range": "4.5 - 5.2 pH", "hex": "#f4a261"},
            {"tier": "กรดปานกลาง", "range": "5.3 - 6.0 pH", "hex": "#e9c46a"},
            {"tier": "กรดเล็กน้อย", "range": "6.1 - 6.8 pH", "hex": "#a7c957"},
            {"tier": "เป็นกลาง (เหมาะสม)", "range": "6.9 - 7.5 pH", "hex": "#2a9d8f"},
            {"tier": "ด่างปานกลาง", "range": "7.6 - 8.4 pH", "hex": "#457b9d"},
            {"tier": "ด่างรุนแรง", "range": "> 8.4 pH", "hex": "#1d3557"}
        ]
    },
    {
        "title": "2. ไนโตรเจนที่ใช้ประโยชน์ได้ (Available Nitrogen - NO3-N)",
        "method": "วิธีเทียบสีย้อมเอโซ LDD / LaMotte STH (Griess Reagent Azo Dye)",
        "items": [
            {"tier": "ต่ำมาก (Depleted)", "range": "< 10 mg/kg", "hex": "#fefae0"},
            {"tier": "ต่ำ (Deficient)", "range": "10 - 25 mg/kg", "hex": "#f4a261"},
            {"tier": "ปานกลาง (Adequate)", "range": "26 - 50 mg/kg", "hex": "#e76f51"},
            {"tier": "สูง (Sufficient)", "range": "51 - 80 mg/kg", "hex": "#d62828"},
            {"tier": "สูงมาก (Surplus)", "range": "> 80 mg/kg", "hex": "#7209b7"}
        ]
    },
    {
        "title": "3. ฟอสฟอรัสที่เป็นประโยชน์ (Available Phosphorus)",
        "method": "วิธีโมลิบดินัมบลู LDD Bray II / SSSA (Molybdenum Blue Complex)",
        "items": [
            {"tier": "ต่ำมาก (Depleted)", "range": "< 5 mg/kg", "hex": "#faf0ca"},
            {"tier": "ต่ำ (Deficient)", "range": "5 - 15 mg/kg", "hex": "#a2d2ff"},
            {"tier": "ปานกลาง (Adequate)", "range": "16 - 30 mg/kg", "hex": "#3a86ff"},
            {"tier": "สูง (Sufficient)", "range": "31 - 60 mg/kg", "hex": "#003049"},
            {"tier": "สูงมาก (Surplus)", "range": "> 60 mg/kg", "hex": "#03045e"}
        ]
    },
    {
        "title": "4. โพแทสเซียมที่แลกเปลี่ยนได้ (Exchangeable Potassium)",
        "method": "วิธีตกตะกอนโซเดียมเตตระฟีนิลโบรอน LDD / SSSA (Na-Tetraphenylboron Turbidity)",
        "items": [
            {"tier": "ต่ำมาก (Depleted)", "range": "< 40 mg/kg", "hex": "#edf2f4"},
            {"tier": "ต่ำ (Deficient)", "range": "40 - 80 mg/kg", "hex": "#ffd166"},
            {"tier": "ปานกลาง (Adequate)", "range": "81 - 150 mg/kg", "hex": "#f3722c"},
            {"tier": "สูง (Sufficient)", "range": "151 - 250 mg/kg", "hex": "#d90429"},
            {"tier": "สูงมาก (Surplus)", "range": "> 250 mg/kg", "hex": "#6a040f"}
        ]
    }
]

# ตำแหน่งเรนเดอร์แต่ละพารามิเตอร์
start_y = 155
section_height = 280

for s_idx, sec in enumerate(sections):
    y = start_y + (s_idx * section_height)
    
    # กรอบพื้นหลังของหมวด
    draw.rounded_rectangle([40, y, width - 40, y + 260], radius=10, fill=(30, 41, 59), outline=(51, 65, 85), width=1)
    
    # หัวข้อและวิธีทดสอบ
    draw.text((65, y + 15), sec["title"], font=font_header, fill=(248, 250, 252))
    draw.text((650, y + 18), sec["method"], font=font_method, fill=(148, 163, 184))
    
    # ชิปสี (Color Swatches)
    num_items = len(sec["items"])
    total_w = width - 130
    gap = 14
    chip_w = (total_w - (gap * (num_items - 1))) / num_items
    chip_h = 175
    
    for i, item in enumerate(sec["items"]):
        chip_x = 65 + i * (chip_w + gap)
        chip_y = y + 60
        rgb = hex_to_rgb(item["hex"])
        text_color = get_contrast_color(rgb)
        
        # กล่องสีหลัก
        draw.rounded_rectangle([chip_x, chip_y, chip_x + chip_w, chip_y + 110], radius=8, fill=rgb, outline=(255, 255, 255), width=1)
        
        # ตัวอักษรแสดงรหัส HEX ด้านในชิปสี
        draw.text((chip_x + 12, chip_y + 75), item["hex"].upper(), font=font_swatch_hex, fill=text_color)
        
        # กรอบข้อมูลระดับคุณภาพและช่วงค่า ด้านล่างชิปสี
        draw.rounded_rectangle([chip_x, chip_y + 118, chip_x + chip_w, chip_y + chip_h], radius=6, fill=(15, 23, 42), outline=(71, 85, 105), width=1)
        
        # ระดับคุณภาพ
        draw.text((chip_x + 10, chip_y + 123), item["tier"], font=font_swatch_tier, fill=(241, 245, 249))
        # ช่วงค่าเชิงปริมาณ
        draw.text((chip_x + 10, chip_y + 148), item["range"], font=font_swatch_range, fill=(56, 189, 248))

# ส่วนท้ายภาพ (Footer)
draw.text((65, height - 45), "มหาวิทยาลัยราชภัฏรำไพพรรณี | โครงการวิจัยชุดวิเคราะห์ NPK และค่า pH ในดินด้วยปัญญาประดิษฐ์ ประจำปีงบประมาณ พ.ศ. 2569", font=font_footer, fill=(100, 116, 139))

# บันทึกไฟล์ภาพลงในโฟลเดอร์ figures ทั้งสองแห่ง
out_chewa = '/Users/chewathassana/Desktop/handysense/chewa/figures/fig_standard_color_matrix_ch02.png'
out_thanapat = '/Users/chewathassana/Desktop/handysense/thanapat_research/figures/fig_standard_color_matrix_ch02.png'
out_artifact = '/Users/chewathassana/.gemini/antigravity-ide/brain/3d397c47-6133-4cfa-9041-316c2cf5feda/fig_standard_color_matrix_ch02.png'

img.save(out_chewa, 'PNG', quality=100)
img.save(out_thanapat, 'PNG', quality=100)
img.save(out_artifact, 'PNG', quality=100)

print("Generated standard color matrix image successfully:")
print("1.", out_chewa)
print("2.", out_thanapat)
print("3.", out_artifact)
