import os
from PIL import Image, ImageDraw, ImageFont

base_path = '/Users/chewathassana/Desktop/handysense/assets/images/atd35/05_wifi_captive_portal.png'
qr_path = '/Users/chewathassana/Desktop/handysense/assets/images/qr_leqs-agri-workshop.png'
out_path = '/Users/chewathassana/Desktop/handysense/assets/images/atd35/11_qr_portal_screen.png'

im = Image.open(base_path).convert('RGBA')

# Fonts
font_thai_title = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 26)
font_thai_bold = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 20)
font_thai_reg = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 19)
font_thai_small = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 16)
font_mono_small = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 15)

draw = ImageDraw.Draw(im)

# 1. Update top capsule date/time:
draw.rectangle([420, 16, 745, 62], fill=(6, 15, 29, 255))
draw.rounded_rectangle([415, 12, 750, 65], radius=26, outline=(0, 240, 255, 200), width=3)

text_top = "2026.10.04 วันอาทิตย์"
bbox = font_thai_bold.getbbox(text_top)
tw = bbox[2] - bbox[0]
draw.text(((415 + 750 - tw) / 2, 23), text_top, font=font_thai_bold, fill=(0, 240, 255, 255))

# 2. Left side: Replace QR code with the new user QR image (qr_leqs-agri-workshop.png)
draw.rectangle([30, 95, 370, 530], fill=(6, 15, 29, 255))

qr_card_x1, qr_card_y1 = 40, 105
qr_card_w, qr_card_h = 320, 320
draw.rounded_rectangle([qr_card_x1, qr_card_y1, qr_card_x1 + qr_card_w, qr_card_y1 + qr_card_h], radius=24, fill=(255, 255, 255, 255), outline=(0, 240, 255, 255), width=4)

# Load and paste new QR code
qr_img = Image.open(qr_path).convert('RGBA')
qr_img = qr_img.resize((290, 290), Image.Resampling.LANCZOS)
im.paste(qr_img, (qr_card_x1 + 15, qr_card_y1 + 15), qr_img)

# URL text below QR
url_text = "https://scicenter.rbru.ac.th/leqs-workshop/index.php"
bbox_url = font_mono_small.getbbox(url_text)
tw_url = bbox_url[2] - bbox_url[0]
draw.text(((qr_card_x1 + qr_card_w/2 - tw_url/2), 442), url_text, font=font_mono_small, fill=(0, 240, 255, 255))

scan_text = "สแกนเพื่อเข้าสู่ LEQs-xAI Portal"
bbox_scan = font_thai_small.getbbox(scan_text)
tw_scan = bbox_scan[2] - bbox_scan[0]
draw.text(((qr_card_x1 + qr_card_w/2 - tw_scan/2), 475), scan_text, font=font_thai_small, fill=(52, 211, 153, 255))

# 3. Right side: Replace instruction card with updated info
draw.rectangle([395, 100, 930, 560], fill=(6, 15, 29, 255))
draw.rounded_rectangle([400, 105, 920, 550], radius=22, fill=(10, 20, 38, 240), outline=(16, 185, 129, 200), width=3)

# Inside right card header:
draw.text((430, 128), "ระบบพอร์ทัลยืนยันตัวตน & QR Code", font=font_thai_title, fill=(52, 211, 153, 255))

items = [
    ("วันที่ประทับ :", "2026.10.04 วันอาทิตย์", (245, 245, 245, 255)),
    ("เวลาบันทึก :", "09:03 น.", (250, 204, 21, 255)),
    ("ผู้รับผิดชอบ :", "ชีวะ ทัศนา", (52, 211, 153, 255)),
    ("ลิงก์โครงการ :", "https://scicenter.rbru.ac.th/leqs-workshop/index.php", (56, 189, 248, 255)),
    ("แพลตฟอร์ม :", "ESP32-S3 ATD3.5 Smart Touch", (203, 213, 225, 255)),
    ("สังกัด :", "คณะวิทย์ฯ มรภ.รำไพพรรณี", (148, 163, 184, 255)),
]

y_offset = 180
for label, val, color in items:
    # Bullet dot
    draw.ellipse([435, y_offset + 8, 443, y_offset + 16], fill=(16, 185, 129, 255))
    draw.text((455, y_offset), label, font=font_thai_bold, fill=(148, 163, 184, 255))
    draw.text((590, y_offset), val, font=font_thai_reg, fill=color)
    y_offset += 42

# Bottom status box in right card
draw.rounded_rectangle([425, 458, 895, 528], radius=14, fill=(6, 15, 29, 230), outline=(0, 240, 255, 180), width=2)
status_str = "สถานะ: ออนไลน์เรียบร้อย (Sync Cloud Portal)"
bbox_st = font_thai_bold.getbbox(status_str)
tw_st = bbox_st[2] - bbox_st[0]
draw.text(((425 + 895 - tw_st) / 2, 478), status_str, font=font_thai_bold, fill=(0, 240, 255, 255))

# Save output to both destination paths
im.save(out_path, format='PNG')
# Also save as jpg
im_rgb = im.convert('RGB')
im_rgb.save('/Users/chewathassana/Desktop/handysense/assets/images/atd35/11_qr_portal_screen.jpg', format='JPEG', quality=95)

# Also copy to leqs-workshop assets
import shutil
shutil.copy(out_path, '/Users/chewathassana/Desktop/handysense/leqs-workshop/assets/images/atd35/11_qr_portal_screen.png')
shutil.copy('/Users/chewathassana/Desktop/handysense/assets/images/atd35/11_qr_portal_screen.jpg', '/Users/chewathassana/Desktop/handysense/leqs-workshop/assets/images/atd35/11_qr_portal_screen.jpg')

print("Successfully regenerated Screen 11:", out_path)
