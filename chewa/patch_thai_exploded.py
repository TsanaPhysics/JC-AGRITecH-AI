#!/usr/bin/env python3
"""
ปรับแต่งข้อความบนภาพกระจายชิ้นส่วน (Exploded View) เป็นภาษาไทย 100%
"""

from PIL import Image, ImageDraw, ImageFont

src_path = '/Users/chewathassana/.gemini/antigravity-ide/brain/3d397c47-6133-4cfa-9041-316c2cf5feda/dark_chamber_exploded_1789997942495.jpg'
dst_path = '/Users/chewathassana/Desktop/handysense/chewa/figures/fig_dark_chamber_exploded_thai_100percent.jpg'

img = Image.open(src_path).convert('RGB')
draw = ImageDraw.Draw(img)

font_bold_md = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 18, index=1)
font_reg_sm  = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 15, index=0)

white = (255, 255, 255)
black = (20, 20, 20)

# 1. กลบและแทนที่ Smartphone ด้านซ้ายบน
draw.rectangle([250, 110, 420, 150], fill=white)
draw.text((255, 120), "สมาร์ทโฟน / โมดูลกล้อง", font=font_bold_md, fill=black)

# 2. กลบและแทนที่ EVA foam seal ด้านซ้าย
draw.rectangle([250, 180, 420, 225], fill=white)
draw.text((255, 190), "ปะเก็นยางโฟม EVA กันแสง", font=font_bold_md, fill=black)

# 3. กลบและแทนที่ EVA foam seal ring ด้านขวา
draw.rectangle([630, 180, 880, 225], fill=white)
draw.text((635, 190), "ซีลขอบเลนส์กล้องแนบสนิท 100%", font=font_bold_md, fill=black)

# 4. กลบและแทนที่ 3D-printed black PETG top lid ด้านขวา
draw.rectangle([630, 335, 950, 385], fill=white)
draw.text((635, 342), "ฝาบนพร้อมท่อโฟกัส 50 มม.", font=font_bold_md, fill=black)
draw.text((635, 365), "(ขึ้นรูปด้วย Black PETG)", font=font_reg_sm, fill=black)

# 5. กลบและแทนที่ 45° multi-wavelength LED array... ด้านขวา
draw.rectangle([630, 460, 1000, 525], fill=white)
draw.text((635, 465), "วงแหวน LED Array แสงตกกระทบ 45°", font=font_bold_md, fill=black)
draw.text((635, 492), "(6 แถบความยาวคลื่น 465 - 940 nm)", font=font_reg_sm, fill=black)

# 6. กลบและแทนที่ PETG chamber box ด้านขวา
draw.rectangle([630, 625, 920, 675], fill=white)
draw.text((635, 632), "ตัวกล่องหลักควบคุมสภาพแสง", font=font_bold_md, fill=black)
draw.text((635, 655), "(ขนาด 120 × 80 × 65 มม.)", font=font_reg_sm, fill=black)

# 7. กลบและแทนที่ Baffles ด้านซ้าย
draw.rectangle([270, 630, 410, 675], fill=white)
draw.text((275, 640), "ผนังดักแสงภายใน", font=font_bold_md, fill=black)

# 8. กลบและแทนที่ Acrylic soil sample cup... ด้านซ้ายล่าง
draw.rectangle([140, 720, 400, 770], fill=white)
draw.text((145, 725), "ถาดดินอะคริลิกสีดำ", font=font_bold_md, fill=black)
draw.text((145, 748), "Ø 35 มม. ลึก 8 มม. (7.5 cm³)", font=font_reg_sm, fill=black)

# 9. กลบและแทนที่ ข้อความขวาล่าง
draw.rectangle([630, 690, 1100, 750], fill=white)
draw.text((635, 700), "แบบจำลองสามมิติกล่องวัดแบบทึบแสง (Optical Dark Chamber)", font=font_bold_md, fill=black)
draw.text((635, 725), "โครงการวิจัยชุดวิเคราะห์ N-P ดินแบบพกพา มรภ.รำไพพรรณี", font=font_reg_sm, fill=black)

# บันทึกภาพ
img.save(dst_path, 'JPEG', quality=98)
print("Saved 100% Thai exploded diagram to", dst_path)
