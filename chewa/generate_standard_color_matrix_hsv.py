import os
import colorsys
from PIL import Image, ImageDraw, ImageFont

def hex_to_rgb(hex_str):
    hex_str = hex_str.lstrip('#')
    return tuple(int(hex_str[i:i+2], 16) for i in (0, 2, 4))

def rgb_to_hsv_str(r, g, b):
    h, s, v = colorsys.rgb_to_hsv(r / 255.0, g / 255.0, b / 255.0)
    h_deg = round(h * 360)
    s_pct = round(s * 100)
    v_pct = round(v * 100)
    return f"HSV: {h_deg}°, {s_pct}%, {v_pct}%"

def get_contrast_color(hex_str):
    r, g, b = hex_to_rgb(hex_str)
    luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255
    return (20, 25, 35) if luminance > 0.6 else (255, 255, 255)

data = [
    {
        "title": "1. ความเป็นกรด-ด่างของดิน (Soil pH) — Universal Indicator Scale (7 ระดับ)",
        "unit": "pH",
        "tiers": [
            {"label": "กรดจัดรุนแรง", "range": "< 4.5", "hex": "#e63946"},
            {"label": "กรดจัด", "range": "4.5 - 5.2", "hex": "#f4a261"},
            {"label": "กรดปานกลาง", "range": "5.3 - 6.0", "hex": "#e9c46a"},
            {"label": "กรดเล็กน้อย", "range": "6.1 - 6.8", "hex": "#a7c957"},
            {"label": "เป็นกลาง (เหมาะสม)", "range": "6.9 - 7.5", "hex": "#2a9d8f"},
            {"label": "ด่างปานกลาง", "range": "7.6 - 8.4", "hex": "#457b9d"},
            {"label": "ด่างรุนแรง", "range": "> 8.4", "hex": "#1d3557"},
        ]
    },
    {
        "title": "2. ไนโตรเจนที่เป็นประโยชน์ (Available N: NO3-N) — Griess Reaction Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            {"label": "ต่ำมาก", "range": "< 10", "hex": "#fefae0"},
            {"label": "ต่ำ", "range": "10 - 25", "hex": "#f4a261"},
            {"label": "ปานกลาง (เหมาะสม)", "range": "26 - 50", "hex": "#e76f51"},
            {"label": "สูง", "range": "51 - 80", "hex": "#d62828"},
            {"label": "สูงมาก", "range": "> 80", "hex": "#7209b7"},
        ]
    },
    {
        "title": "3. ฟอสฟอรัสที่เป็นประโยชน์ (Available P) — Bray II / Molybdenum Blue Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            {"label": "ต่ำมาก", "range": "< 5", "hex": "#faf0ca"},
            {"label": "ต่ำ", "range": "5 - 15", "hex": "#a2d2ff"},
            {"label": "ปานกลาง (เหมาะสม)", "range": "16 - 30", "hex": "#3a86ff"},
            {"label": "สูง", "range": "31 - 60", "hex": "#003049"},
            {"label": "สูงมาก", "range": "> 60", "hex": "#03045e"},
        ]
    },
    {
        "title": "4. โพแทสเซียมที่แลกเปลี่ยนได้ (Exchangeable K) — Cobaltinitrite Turbidity Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            {"label": "ต่ำมาก", "range": "< 40", "hex": "#edf2f4"},
            {"label": "ต่ำ", "range": "40 - 80", "hex": "#ffd166"},
            {"label": "ปานกลาง (เหมาะสม)", "range": "81 - 150", "hex": "#f3722c"},
            {"label": "สูง", "range": "151 - 250", "hex": "#d90429"},
            {"label": "สูงมาก", "range": "> 250", "hex": "#6a040f"},
        ]
    }
]

W, H = 2200, 1600
img = Image.new("RGB", (W, H), (11, 17, 33))
draw = ImageDraw.Draw(img)

font_path = "/System/Library/Fonts/Supplemental/Thonburi.ttc"
if not os.path.exists(font_path):
    font_path = "/System/Library/Fonts/Thonburi.ttc"

title_font = ImageFont.truetype(font_path, 40)
sub_font = ImageFont.truetype(font_path, 26)
sec_font = ImageFont.truetype(font_path, 30)
swatch_title_font = ImageFont.truetype(font_path, 21)
swatch_val_font = ImageFont.truetype(font_path, 20)
swatch_hex_font = ImageFont.truetype(font_path, 18)
swatch_hsv_font = ImageFont.truetype(font_path, 17)
footer_font = ImageFont.truetype(font_path, 21)

# Header
draw.rectangle([0, 0, W, 175], fill=(16, 24, 48))
draw.line([(0, 175), (W, 175)], fill=(34, 211, 238), width=3)
draw.text((60, 28), "แถบสีมาตรฐานอ้างอิงสำหรับการวิเคราะห์ดินด้วยการประมวลผลภาพถ่าย (RGB & HSV Dual-Color Space)", fill=(240, 249, 255), font=title_font)
draw.text((60, 85), "อ้างอิงเกณฑ์มาตรฐานตารางที่ 2.3 และข้อกำหนดสีดิจิทัล (HEX / RGB / HSV) สำหรับการชดเชยแสงและปัญญาประดิษฐ์", fill=(148, 163, 184), font=sub_font)
draw.text((60, 125), "โครงการวิจัยระบบวิเคราะห์ดินดิจิทัลและตรวจวัดธาตุอาหารพืชภาคสนาม มหาวิทยาลัยราชภัฏรำไพพรรณี", fill=(56, 189, 248), font=sub_font)

start_y = 205
row_height = 320

for sec_idx, sec in enumerate(data):
    y = start_y + sec_idx * row_height
    
    # Section Card Background
    draw.rounded_rectangle([45, y, W - 45, y + row_height - 25], radius=16, fill=(19, 29, 56), outline=(42, 60, 102), width=1)
    
    # Indicator bar
    draw.rounded_rectangle([60, y + 18, 68, y + 54], radius=4, fill=(34, 211, 238))
    draw.text((80, y + 18), sec["title"], fill=(224, 242, 254), font=sec_font)
    
    tiers = sec["tiers"]
    n_tiers = len(tiers)
    
    avail_w = W - 140
    gap = 16
    swatch_w = (avail_w - (n_tiers - 1) * gap) / n_tiers
    swatch_h = 195
    swatch_top = y + 70
    
    for i, t in enumerate(tiers):
        sx = 70 + i * (swatch_w + gap)
        sy = swatch_top
        
        rgb = hex_to_rgb(t["hex"])
        hsv_text = rgb_to_hsv_str(rgb[0], rgb[1], rgb[2])
        contrast = get_contrast_color(t["hex"])
        
        # Color Box
        draw.rounded_rectangle([sx, sy, sx + swatch_w, sy + swatch_h], radius=12, fill=rgb, outline=(255, 255, 255, 70), width=1)
        
        # Badge on swatch
        label_text = t["label"]
        range_text = f"{t['range']} {sec['unit']}"
        hex_text = f"HEX: {t['hex'].upper()}"
        rgb_text = f"RGB: {rgb[0]}, {rgb[1], rgb[2]}"
        
        # Draw text inside swatch with shadow or high-contrast
        # Tier Label
        bbox1 = swatch_title_font.getbbox(label_text)
        w1 = bbox1[2] - bbox1[1]
        draw.text((sx + (swatch_w - w1) / 2 - 10, sy + 18), label_text, fill=contrast, font=swatch_title_font)
        
        # Range
        bbox2 = swatch_val_font.getbbox(range_text)
        w2 = bbox2[2] - bbox2[0]
        draw.text((sx + (swatch_w - w2) / 2, sy + 58), range_text, fill=contrast, font=swatch_val_font)
        
        # Divider line inside swatch
        line_color = (contrast[0], contrast[1], contrast[2], 120)
        draw.line([(sx + 15, sy + 95), (sx + swatch_w - 15, sy + 95)], fill=contrast, width=1)
        
        # HEX code
        bbox3 = swatch_hex_font.getbbox(hex_text)
        w3 = bbox3[2] - bbox3[0]
        draw.text((sx + (swatch_w - w3) / 2, sy + 105), hex_text, fill=contrast, font=swatch_hex_font)
        
        # RGB Code
        bbox_rgb = swatch_hex_font.getbbox(f"RGB: {rgb[0]}, {rgb[1]}, {rgb[2]}")
        w_rgb = bbox_rgb[2] - bbox_rgb[0]
        draw.text((sx + (swatch_w - w_rgb) / 2, sy + 132), f"RGB: {rgb[0]}, {rgb[1]}, {rgb[2]}", fill=contrast, font=swatch_hex_font)

        # HSV Code
        bbox4 = swatch_hsv_font.getbbox(hsv_text)
        w4 = bbox4[2] - bbox4[0]
        draw.text((sx + (swatch_w - w4) / 2, sy + 158), hsv_text, fill=contrast, font=swatch_hsv_font)

# Footer
draw.rectangle([0, H - 75, W, H], fill=(16, 24, 48))
draw.line([(0, H - 75), (W, H - 75)], fill=(42, 60, 102), width=1)
draw.text((60, H - 50), "สถาปัตยกรรมการประมวลผลสี: การแปลงค่าจาก RGB เป็น HSV ช่วยขจัดอิทธิพลของความเข้มแสงแวดล้อม (V) โดยอาศัยค่าเนื้อสี (H) และความอิ่มตัวสี (S) ในการเทียบค่าเคมีของดิน", fill=(148, 163, 184), font=footer_font)

# Save
out_png1 = "/Users/chewathassana/Desktop/handysense/chewa/figures/fig_standard_color_matrix_rgb_hsv.png"
out_png2 = "/Users/chewathassana/Desktop/handysense/thanapat_research/figures/fig_standard_color_matrix_rgb_hsv.png"
out_artifact = "/Users/chewathassana/.gemini/antigravity-ide/brain/3d397c47-6133-4cfa-9041-316c2cf5feda/fig_standard_color_matrix_rgb_hsv.png"

img.save(out_png1, dpi=(300, 300))
img.save(out_png2, dpi=(300, 300))
img.save(out_artifact, dpi=(300, 300))
print("IMAGES GENERATED SUCCESSFULLY")
