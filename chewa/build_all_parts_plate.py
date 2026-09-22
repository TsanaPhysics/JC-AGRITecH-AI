#!/usr/bin/env python3
"""
สร้างไฟล์ STL รวมทุกชิ้นส่วนบนฐานพิมพ์เดียว (All-in-one 3D Print Plate)
ขนาดฐานพิมพ์รองรับเครื่องพิมพ์มาตรฐานทั่วไป 200 x 200 มม. ขึ้นไป
"""

from generate_dark_chamber_stl import make_box_facets, make_cylinder_facets, write_stl

facets_all = []

# 1. Main Housing (วางกึ่งกลางฐานพิมพ์ X: 0, Y: 25)
ox, oy = 0, 25
# ฐานล่าง
facets_all.extend(make_box_facets(ox-60, oy-40, 0, 120, 80, 4))
# ผนังซ้าย-ขวา
facets_all.extend(make_box_facets(ox-60, oy-40, 4, 6, 80, 61))
facets_all.extend(make_box_facets(ox+54, oy-40, 4, 6, 80, 61))
# ผนังหลัง
facets_all.extend(make_box_facets(ox-54, oy+34, 4, 108, 6, 61))
# ผนังหน้า
facets_all.extend(make_box_facets(ox-54, oy-40, 4, 28, 6, 61))
facets_all.extend(make_box_facets(ox+26, oy-40, 4, 28, 6, 61))
facets_all.extend(make_box_facets(ox-26, oy-40, 19, 52, 6, 46))
# กรวยดักแสงวงแหวน LED ด้านใน
facets_all.extend(make_cylinder_facets(ox, oy, 18, 30, 24, segments=48))

# 2. Soil Drawer (วางด้านหน้า X: 0, Y: -55)
dx, dy = 0, -55
facets_all.extend(make_box_facets(dx-25, dy-25, 0, 50, 55, 14))
facets_all.extend(make_box_facets(dx-30, dy-29, 0, 60, 4, 16))
facets_all.extend(make_box_facets(dx-27, dy-22, 0, 54, 2, 14))
facets_all.extend(make_box_facets(dx-15, dy-36, 0, 30, 7, 10))

# 3. Smartphone Top Mount (วางด้านขวา X: 85, Y: 0)
sx, sy = 85, 0
facets_all.extend(make_box_facets(sx-40, sy-60, 0, 80, 120, 4))
facets_all.extend(make_box_facets(sx-40, sy-41.25, 4, 80, 3.5, 14))
facets_all.extend(make_box_facets(sx-40, sy+37.75, 4, 80, 3.5, 14))
facets_all.extend(make_box_facets(sx+36, sy-37.75, 4, 4, 75.5, 14))
facets_all.extend(make_cylinder_facets(sx, sy, 4, 14, 4, segments=36))

# 4. Sensor Plate (วางด้านซ้าย X: -85, Y: 0)
px, py = -85, 0
facets_all.extend(make_box_facets(px-40, py-60, 0, 80, 120, 4))
for p_offset_x, p_offset_y in [(-14, -11), (14, -11), (-14, 11), (14, 11)]:
    facets_all.extend(make_cylinder_facets(px + p_offset_x, py + p_offset_y, 4, 2.5, 8, segments=18))

# บันทึกไฟล์ STL รวมฐานพิมพ์
output_path = '/Users/chewathassana/Desktop/handysense/chewa/dark_chamber_all_parts_plate.stl'
write_stl(output_path, facets_all)
print("Generated consolidated 3D print plate:", output_path)
