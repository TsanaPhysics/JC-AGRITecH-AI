# Leaf AI (PWA)

แอปจำแนกใบด้วยโมเดล TF.js ที่ส่งออกจาก Colab notebook

## ใส่โมเดลของกลุ่ม
1. แตก `leaf_model_for_app.zip`
2. แทนที่โฟลเดอร์ `model/` และไฟล์ `labels.json` ในแอปนี้
3. (ทางเลือก) แก้ `threshold` ใน `labels.json` ระหว่าง 0–1

โฟลเดอร์ `model/` ปัจจุบันเป็นโมเดลทดสอบที่ยังไม่ได้เทรน ผลทำนายจึงเป็นค่าสุ่ม

## ทดสอบบนคอมพิวเตอร์
```
cd pwa
python3 -m http.server 8000
```
เปิด http://localhost:8000 (localhost ใช้กล้องได้โดยไม่ต้อง HTTPS)

## เผยแพร่ให้มือถือใช้
ต้องเป็น HTTPS เช่น GitHub Pages: อัปโหลดทั้งโฟลเดอร์ แล้วเปิด Settings → Pages
เมื่ออัปเดตโมเดล ให้เปลี่ยนค่า `V` ใน `sw.js` (เช่น leaf-ai-v2) เพื่อล้างแคชเก่า

## ติดตั้ง
- Android Chrome: เมนู ⋮ → ติดตั้งแอป
- iPhone Safari: แชร์ → เพิ่มลงหน้าจอโฮม

ไลบรารี: TensorFlow.js 4.22.0 (Apache-2.0) อยู่ใน `vendor/tf.min.js` ใช้ออฟไลน์ได้
