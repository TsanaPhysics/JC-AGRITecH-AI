#!/usr/bin/env python3
"""
สคริปต์สร้างไฟล์ 3D STL สำหรับโครงสร้างกล่องวัดแบบทึบแสง (Optical Dark Chamber)
โครงการวิจัยชุดวิเคราะห์ปริมาณไนโตรเจนและฟอสฟอรัสในดินแบบพกพา (มรภ.รำไพพรรณี)
หัวหน้าโครงการ: ผศ.ดร.ชีวะ ทัศนา
"""

import struct
import math

def write_stl(filename, facets):
    """เขียนไฟล์ STL รูปแบบ Binary Watertight"""
    with open(filename, 'wb') as f:
        # Header 80 bytes
        header = b'Dark Chamber 45/0 deg - RBRU Research - Dr.Chewa Thassana' + b' ' * 80
        f.write(header[:80])
        # Number of facets (uint32)
        f.write(struct.pack('<I', len(facets)))
        for norm, v1, v2, v3 in facets:
            # Normal vector (3 floats), vertices (3 * 3 floats), attribute byte count (uint16)
            f.write(struct.pack('<3f9fH', norm[0], norm[1], norm[2],
                                v1[0], v1[1], v1[2],
                                v2[0], v2[1], v2[2],
                                v3[0], v3[1], v3[2], 0))

def make_box_facets(x0, y0, z0, dx, dy, dz):
    """สร้าง Facets ทรงลูกบาศก์"""
    facets = []
    p = [
        (x0, y0, z0), (x0+dx, y0, z0), (x0+dx, y0+dy, z0), (x0, y0+dy, z0),
        (x0, y0, z0+dz), (x0+dx, y0, z0+dz), (x0+dx, y0+dy, z0+dz), (x0, y0+dy, z0+dz)
    ]
    # 6 หน้า แต่ละหน้ามี 2 สามเหลี่ยม
    faces = [
        # Bottom
        ((0, 0, -1), p[0], p[2], p[1]), ((0, 0, -1), p[0], p[3], p[2]),
        # Top
        ((0, 0, 1), p[4], p[5], p[6]), ((0, 0, 1), p[4], p[6], p[7]),
        # Front (-Y)
        ((0, -1, 0), p[0], p[1], p[5]), ((0, -1, 0), p[0], p[5], p[4]),
        # Back (+Y)
        ((0, 1, 0), p[2], p[3], p[7]), ((0, 1, 0), p[2], p[7], p[6]),
        # Left (-X)
        ((-1, 0, 0), p[0], p[4], p[7]), ((-1, 0, 0), p[0], p[7], p[3]),
        # Right (+X)
        ((1, 0, 0), p[1], p[2], p[6]), ((1, 0, 0), p[1], p[6], p[5])
    ]
    return faces

def make_cylinder_facets(cx, cy, cz, radius, height, segments=36):
    """สร้าง Facets ทรงกระบอกตัน"""
    facets = []
    angles = [2 * math.pi * i / segments for i in range(segments)]
    
    # Bottom circle
    for i in range(segments):
        a1 = angles[i]
        a2 = angles[(i + 1) % segments]
        v0 = (cx, cy, cz)
        v1 = (cx + radius * math.cos(a1), cy + radius * math.sin(a1), cz)
        v2 = (cx + radius * math.cos(a2), cy + radius * math.sin(a2), cz)
        facets.append(((0, 0, -1), v0, v2, v1))

    # Top circle
    for i in range(segments):
        a1 = angles[i]
        a2 = angles[(i + 1) % segments]
        v0 = (cx, cy, cz + height)
        v1 = (cx + radius * math.cos(a1), cy + radius * math.sin(a1), cz + height)
        v2 = (cx + radius * math.cos(a2), cy + radius * math.sin(a2), cz + height)
        facets.append(((0, 0, 1), v0, v1, v2))

    # Side wall
    for i in range(segments):
        a1 = angles[i]
        a2 = angles[(i + 1) % segments]
        c1, s1 = math.cos(a1), math.sin(a1)
        c2, s2 = math.cos(a2), math.sin(a2)
        b1 = (cx + radius * c1, cy + radius * s1, cz)
        b2 = (cx + radius * c2, cy + radius * s2, cz)
        t1 = (cx + radius * c1, cy + radius * s1, cz + height)
        t2 = (cx + radius * c2, cy + radius * s2, cz + height)
        
        norm = ((c1+c2)/2, (s1+s2)/2, 0)
        facets.append((norm, b1, b2, t2))
        facets.append((norm, b1, t2, t1))
        
    return facets

def build_models():
    # 1. Main Housing Body (120 x 80 x 65 mm)
    housing_facets = []
    # ฐานล่าง
    housing_facets.extend(make_box_facets(-60, -40, 0, 120, 80, 4))
    # ผนังซ้าย-ขวา
    housing_facets.extend(make_box_facets(-60, -40, 4, 6, 80, 61))
    housing_facets.extend(make_box_facets(54, -40, 4, 6, 80, 61))
    # ผนังหลัง
    housing_facets.extend(make_box_facets(-54, 34, 4, 108, 6, 61))
    # ผนังหน้า (เว้นช่องลิ้นชักตรงกลาง กว้าง 52 สูง 15 มม.)
    housing_facets.extend(make_box_facets(-54, -40, 4, 28, 6, 61))
    housing_facets.extend(make_box_facets(26, -40, 4, 28, 6, 61))
    housing_facets.extend(make_box_facets(-26, -40, 19, 52, 6, 46))
    # กรวยดักแสงวงแหวน LED ด้านใน
    housing_facets.extend(make_cylinder_facets(0, 0, 18, 30, 24, segments=48))
    write_stl('/Users/chewathassana/Desktop/handysense/chewa/dark_chamber_main_housing.stl', housing_facets)
    print("Generated dark_chamber_main_housing.stl successfully.")

    # 2. Soil Drawer (ลิ้นชักถาดตัวอย่างดิน พร้อมเขี้ยวดักแสง Labyrinth)
    drawer_facets = []
    # ตัวลิ้นชัก
    drawer_facets.extend(make_box_facets(-25, -38, 4.5, 50, 55, 14))
    # หน้ากากปีกเขี้ยวดักแสงชั้นที่ 1
    drawer_facets.extend(make_box_facets(-30, -42, 3.5, 60, 4, 16))
    # เขี้ยวดักแสงชั้นที่ 2
    drawer_facets.extend(make_box_facets(-27, -35, 4.5, 54, 2, 14))
    # มือจับด้านหน้า
    drawer_facets.extend(make_box_facets(-15, -49, 6, 30, 7, 10))
    write_stl('/Users/chewathassana/Desktop/handysense/chewa/dark_chamber_soil_drawer.stl', drawer_facets)
    print("Generated dark_chamber_soil_drawer.stl successfully.")

    # 3. Smartphone Top Mount (ฝาบนพร้อมแท่นจับยึดสมาร์ทโฟนและเบ้าเลนส์ EVA)
    phone_facets = []
    # แผ่นฝาบน (120 x 80 x 4 มม.)
    phone_facets.extend(make_box_facets(-60, -40, 65, 120, 80, 4))
    # ขอบรางหนีบสมาร์ทโฟน
    phone_facets.extend(make_box_facets(-42, -40, 69, 84, 6, 12))
    phone_facets.extend(make_box_facets(-42, 34, 69, 84, 6, 12))
    phone_facets.extend(make_box_facets(38, -40, 69, 4, 80, 12))
    # ฮูดครอบเลนส์พร้อมร่องปะเก็นยาง EVA
    phone_facets.extend(make_cylinder_facets(0, 0, 69, 14, 4, segments=36))
    write_stl('/Users/chewathassana/Desktop/handysense/chewa/dark_chamber_phone_mount.stl', phone_facets)
    print("Generated dark_chamber_phone_mount.stl successfully.")

    # 4. Alternative Optical Sensor Plate (ฝาบนสำหรับ ESP32-CAM / AS7262)
    plate_facets = []
    plate_facets.extend(make_box_facets(-60, -40, 65, 120, 80, 4))
    # เสายึดบอร์ดเซนเซอร์ 4 มุม
    for sx, sy in [(-14, -11), (14, -11), (-14, 11), (14, 11)]:
        plate_facets.extend(make_cylinder_facets(sx, sy, 69, 2.5, 8, segments=18))
    write_stl('/Users/chewathassana/Desktop/handysense/chewa/dark_chamber_sensor_plate.stl', plate_facets)
    print("Generated dark_chamber_sensor_plate.stl successfully.")

if __name__ == '__main__':
    build_models()
