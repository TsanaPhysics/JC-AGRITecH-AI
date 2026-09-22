#!/usr/bin/env python3
"""
ปรับแต่งและแทนที่ข้อความภาษาอังกฤษที่เหลือในภาพด้วยข้อความภาษาไทยอย่างสมบูรณ์ 100%
ใช้ฟอนต์ Thonburi Bold คมชัด สวยงาม ตามหลักวิชาการ
"""

from PIL import Image, ImageDraw, ImageFont

src_path = '/Users/chewathassana/.gemini/antigravity-ide/brain/3d397c47-6133-4cfa-9041-316c2cf5feda/dark_chamber_thai_1789998216969.jpg'
dst_path = '/Users/chewathassana/Desktop/handysense/chewa/figures/fig_dark_chamber_thai_100percent.jpg'

img = Image.open(src_path).convert('RGB')
draw = ImageDraw.Draw(img)

# ฟอนต์ภาษาไทย
font_bold_lg = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 21, index=1)
font_bold_md = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 18, index=1)
font_reg_sm  = ImageFont.truetype('/System/Library/Fonts/Supplemental/Thonburi.ttc', 16, index=0)

white = (255, 255, 255)
black = (20, 20, 20)

# 1. กลบและเขียนทับข้อความซ้ายบน: 1. MATTE BLACK ANTI-REFLECTIVE INNER LINING (<1% REFLECTANCE)
# ขอบเขต x: 50 ถึง 270, y: 410 ถึง 510
draw.rectangle([45, 415, 275, 515], fill=white)
draw.text((50, 425), "1. ผิวเคลือบดูดกลืนแสง", font=font_bold_lg, fill=black)
draw.text((50, 455), "สีดำด้านภายในกล่อง", font=font_bold_md, fill=black)
draw.text((50, 482), "(ค่าสะท้อนผิวน้อยกว่า 1%)", font=font_reg_sm, fill=black)

# 2. กลบและเขียนทับข้อความซ้ายกลาง: 3. ROUND BLACK ACRYLIC SOIL SAMPLE HOLDER...
# ขอบเขต x: 50 ถึง 290, y: 530 ถึง 630
draw.rectangle([45, 530, 290, 630], fill=white)
draw.text((50, 538), "3. ถาดบรรจุตัวอย่างดิน", font=font_bold_lg, fill=black)
draw.text((50, 568), "อะคริลิกสีดำ Ø 35 มม.", font=font_bold_md, fill=black)
draw.text((50, 595), "ลึก 8 มม. (ดินเรียบ 7.5 cm³)", font=font_reg_sm, fill=black)

# 3. กลบและเขียนทับข้อความซ้ายล่าง: 2. INTERLOCKING LIGHT-TRAP BAFFLES AND LABYRINTH SEAL...
# ขอบเขต x: 50 ถึง 370, y: 640 ถึง 730
draw.rectangle([45, 640, 375, 730], fill=white)
draw.text((50, 645), "2. แผ่นกั้นดักแสงแบบเขี้ยวซ้อน", font=font_bold_lg, fill=black)
draw.text((50, 675), "Labyrinth Seal สองชั้นรอบลิ้นชัก", font=font_bold_md, fill=black)
draw.text((50, 702), "(ตัดแสงรบกวนภายนอก 99.94%)", font=font_reg_sm, fill=black)

# 4. กลบและเขียนทับข้อความขวาล่าง: 1. INTERLOCKING LIGHT-TRAP BAFFLES AND ULTRA-FLAT...
# ขอบเขต x: 1045 ถึง 1335, y: 580 ถึง 680
draw.rectangle([1045, 580, 1340, 680], fill=white)
draw.text((1050, 590), "เขี้ยวดักแสงแนบสนิท", font=font_bold_lg, fill=black)
draw.text((1050, 620), "และผิวดูดซับแสงพิเศษ", font=font_bold_md, fill=black)
draw.text((1050, 648), "ภายในห้องมืดตรวจวัด", font=font_reg_sm, fill=black)

# บันทึกภาพ
img.save(dst_path, 'JPEG', quality=98)
print("Saved 100% Thai cutaway diagram to", dst_path)
