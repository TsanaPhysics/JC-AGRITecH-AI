#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
=============================================================================
🌱 AgriVision Analyzer: Computer Vision & Edge AI for Smart Agriculture
หน่วยวิจัยปัญญาประดิษฐ์เพื่อเกษตรดิจิทัล คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี
ผศ.ดร.ชีวะ ทัศนา (Dr. Chewa Thassana) & ทีมวิจัย
=============================================================================
"""

import os
import sys
import sqlite3
import argparse
from datetime import datetime
import numpy as np

try:
    import cv2
except ImportError:
    print("[ERROR] กรุณาติดตั้ง OpenCV ก่อนใช้งาน: pip install opencv-python")
    sys.exit(1)

DEFAULT_DB_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "agri_telemetry.db")
OUTPUT_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "vision_outputs")


class AgriVisionAnalyzer:
    """
    คลาสสำหรับประมวลผลภาพทางการเกษตร (ใบพืชและหน้าดิน) ด้วย Computer Vision
    """

    def __init__(self, db_path=DEFAULT_DB_PATH):
        self.db_path = db_path
        os.makedirs(OUTPUT_DIR, exist_ok=True)
        self._ensure_database_schema()

    def _ensure_database_schema(self):
        """ตรวจสอบและสร้างตาราง vision_telemetry หากยังไม่มีในฐานข้อมูล"""
        try:
            if not os.path.exists(os.path.dirname(self.db_path)):
                return
            conn = sqlite3.connect(self.db_path)
            cursor = conn.cursor()
            cursor.execute("""
                CREATE TABLE IF NOT EXISTS vision_telemetry (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                    leaf_vari_mean REAL,
                    leaf_gli_mean REAL,
                    leaf_green_ratio REAL,
                    leaf_chlorosis_ratio REAL,
                    leaf_disease_severity REAL,
                    soil_surface_moisture_est REAL,
                    soil_roughness_score REAL,
                    annotated_image_path TEXT,
                    notes TEXT
                );
            """)
            conn.commit()
            conn.close()
        except Exception as e:
            print(f"[WARN] ไม่สามารถเข้าถึงฐานข้อมูล HandySense โดยตรง: {e}")

    def analyze_leaf(self, img_bgr):
        """
        วิเคราะห์สุขภาพใบพืช คำนวณดัชนี VARI, GLI และจำแนกสีใบ
        """
        img_rgb = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2RGB).astype(np.float32) / 255.0
        R = img_rgb[:, :, 0]
        G = img_rgb[:, :, 1]
        B = img_rgb[:, :, 2]

        denom_vari = G + R - B
        denom_vari[np.abs(denom_vari) < 1e-5] = 1e-5
        vari = (G - R) / denom_vari
        vari = np.clip(vari, -1.0, 1.0)

        denom_gli = 2 * G + R + B
        denom_gli[np.abs(denom_gli) < 1e-5] = 1e-5
        gli = (2 * G - R - B) / denom_gli
        gli = np.clip(gli, -1.0, 1.0)

        img_hsv = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2HSV)
        
        # ก. ใบเขียวสมบูรณ์ (Healthy Green)
        lower_green = np.array([35, 40, 40])
        upper_green = np.array([85, 255, 255])
        mask_green = cv2.inRange(img_hsv, lower_green, upper_green)

        # ข. ใบเหลือง ขาดธาตุอาหาร N (Chlorosis)
        lower_yellow = np.array([20, 50, 60])
        upper_yellow = np.array([35, 255, 255])
        mask_yellow = cv2.inRange(img_hsv, lower_yellow, upper_yellow)

        # ค. แผลไหม้ / จุดโรคสีน้ำตาล (Necrosis / Disease lesions)
        lower_brown = np.array([10, 50, 20])
        upper_brown = np.array([20, 255, 180])
        mask_brown = cv2.inRange(img_hsv, lower_brown, upper_brown)

        plant_mask = cv2.bitwise_or(mask_green, cv2.bitwise_or(mask_yellow, mask_brown))
        total_plant_pixels = np.count_nonzero(plant_mask)

        if total_plant_pixels > 0:
            green_ratio = (np.count_nonzero(mask_green) / total_plant_pixels) * 100.0
            chlorosis_ratio = (np.count_nonzero(mask_yellow) / total_plant_pixels) * 100.0
            disease_severity = (np.count_nonzero(mask_brown) / total_plant_pixels) * 100.0
            plant_vari = float(np.mean(vari[plant_mask > 0]))
            plant_gli = float(np.mean(gli[plant_mask > 0]))
        else:
            green_ratio = 0.0
            chlorosis_ratio = 0.0
            disease_severity = 0.0
            plant_vari = 0.0
            plant_gli = 0.0

        return {
            "vari_mean": round(plant_vari, 4),
            "gli_mean": round(plant_gli, 4),
            "green_ratio": round(green_ratio, 2),
            "chlorosis_ratio": round(chlorosis_ratio, 2),
            "disease_severity": round(disease_severity, 2),
            "plant_mask": plant_mask,
            "mask_green": mask_green,
            "mask_yellow": mask_yellow,
            "mask_brown": mask_brown
        }

    def analyze_soil(self, img_bgr):
        """
        วิเคราะห์สภาพหน้าดิน ประเมินความชื้นผิวดิน และความขรุขระแตกระแหง
        """
        img_lab = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2LAB)
        L_channel = img_lab[:, :, 0].astype(np.float32)

        mean_luminance = float(np.mean(L_channel))
        soil_moisture_est = np.clip(100.0 - ((mean_luminance - 40.0) / 140.0) * 100.0, 5.0, 95.0)

        gray = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2GRAY)
        laplacian_var = float(cv2.Laplacian(gray, cv2.CV_64F).var())
        roughness_score = np.clip(laplacian_var / 10.0, 0.0, 100.0)

        return {
            "mean_luminance": round(mean_luminance, 2),
            "surface_moisture_est": round(float(soil_moisture_est), 2),
            "roughness_score": round(float(roughness_score), 2)
        }

    def render_hud(self, img_bgr, leaf_metrics, soil_metrics):
        """
        วาดกราฟิก HUD ทางการเกษตรดิจิทัลลงบนภาพ (Digital Agri Telemetry HUD)
        """
        annotated = img_bgr.copy()
        h, w = annotated.shape[:2]

        overlay = annotated.copy()
        cv2.rectangle(overlay, (15, 15), (w - 15, 130), (20, 24, 30), -1)
        cv2.rectangle(overlay, (15, h - 85), (w - 15, h - 15), (20, 24, 30), -1)
        cv2.addWeighted(overlay, 0.75, annotated, 0.25, 0, annotated)

        font = cv2.FONT_HERSHEY_SIMPLEX
        cv2.putText(annotated, "JC-AGRITECH EDGE VISION AI | TELEMETRY HUD", (30, 45), 
                    font, 0.7, (0, 255, 200), 2, cv2.LINE_AA)
        
        timestamp_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        cv2.putText(annotated, f"TIME: {timestamp_str}", (w - 320, 45), 
                    font, 0.5, (180, 200, 220), 1, cv2.LINE_AA)

        l_text1 = f"LEAF HEALTH: {leaf_metrics['green_ratio']}% Green"
        l_text2 = f"VARI: {leaf_metrics['vari_mean']} | GLI: {leaf_metrics['gli_mean']}"
        l_text3 = f"CHLOROSIS: {leaf_metrics['chlorosis_ratio']}% | DISEASE: {leaf_metrics['disease_severity']}%"
        
        cv2.putText(annotated, l_text1, (30, 75), font, 0.55, (50, 230, 50), 2, cv2.LINE_AA)
        cv2.putText(annotated, l_text2, (30, 100), font, 0.5, (220, 220, 220), 1, cv2.LINE_AA)
        cv2.putText(annotated, l_text3, (30, 120), font, 0.45, (0, 165, 255), 1, cv2.LINE_AA)

        s_text1 = f"SOIL MOISTURE EST: {soil_metrics['surface_moisture_est']}%"
        s_text2 = f"ROUGHNESS: {soil_metrics['roughness_score']}/100"
        
        cv2.putText(annotated, s_text1, (w // 2 + 30, 75), font, 0.55, (255, 200, 50), 2, cv2.LINE_AA)
        cv2.putText(annotated, s_text2, (w // 2 + 30, 100), font, 0.5, (200, 200, 200), 1, cv2.LINE_AA)

        cx, cy = w // 2, h // 2
        cv2.circle(annotated, (cx, cy), 40, (0, 255, 200), 1, cv2.LINE_AA)
        cv2.line(annotated, (cx - 50, cy), (cx + 50, cy), (0, 255, 200), 1, cv2.LINE_AA)
        cv2.line(annotated, (cx, cy - 50), (cx, cy + 50), (0, 255, 200), 1, cv2.LINE_AA)

        status_text = "STATUS: ONLINE | CONNECTED TO HANDYSENSE TELEMETRY DB"
        cv2.putText(annotated, status_text, (30, h - 35), font, 0.5, (0, 255, 120), 1, cv2.LINE_AA)

        return annotated

    def save_to_handysense_db(self, leaf_metrics, soil_metrics, img_path):
        """
        บันทึกข้อมูลผลวิเคราะห์ลงในฐานข้อมูล SQLite ของ HandySense
        """
        if not os.path.exists(self.db_path):
            print(f"[WARN] ไม่พบไฟล์ฐานข้อมูลที่ {self.db_path} (จะข้ามการบันทึกฐานข้อมูล)")
            return False

        try:
            conn = sqlite3.connect(self.db_path)
            cursor = conn.cursor()
            
            cursor.execute("""
                INSERT INTO vision_telemetry (
                    leaf_vari_mean, leaf_gli_mean, leaf_green_ratio,
                    leaf_chlorosis_ratio, leaf_disease_severity,
                    soil_surface_moisture_est, soil_roughness_score,
                    annotated_image_path, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            """, (
                leaf_metrics['vari_mean'],
                leaf_metrics['gli_mean'],
                leaf_metrics['green_ratio'],
                leaf_metrics['chlorosis_ratio'],
                leaf_metrics['disease_severity'],
                soil_metrics['surface_moisture_est'],
                soil_metrics['roughness_score'],
                img_path,
                "Analyzed by AgriVision Analyzer"
            ))

            cursor.execute("""
                UPDATE telemetry 
                SET soil_stick_moisture = COALESCE(soil_stick_moisture, ?),
                    ai_predicted_soil_moist = ?
                WHERE id = (SELECT MAX(id) FROM telemetry)
            """, (soil_metrics['surface_moisture_est'], soil_metrics['surface_moisture_est']))

            conn.commit()
            conn.close()
            print(f"✅ บันทึกข้อมูลผลลัพธ์ Vision เข้าสู่ฐานข้อมูล HandySense สำเร็จ! ({self.db_path})")
            return True
        except Exception as e:
            print(f"❌ เกิดข้อผิดพลาดในการบันทึกฐานข้อมูล: {e}")
            return False

    def process_image(self, input_path):
        if not os.path.exists(input_path):
            print(f"[ERROR] ไม่พบไฟล์ภาพ: {input_path}")
            return None

        img = cv2.imread(input_path)
        if img is None:
            print(f"[ERROR] ไม่สามารถอ่านไฟล์ภาพได้: {input_path}")
            return None

        print(f"🔍 กำลังประมวลผลภาพ: {input_path} (ขนาด {img.shape[1]}x{img.shape[0]} px)...")

        leaf_res = self.analyze_leaf(img)
        soil_res = self.analyze_soil(img)
        annotated_img = self.render_hud(img, leaf_res, soil_res)

        filename = f"telemetry_hud_{datetime.now().strftime('%Y%m%d_%H%M%S')}.jpg"
        out_path = os.path.join(OUTPUT_DIR, filename)
        cv2.imwrite(out_path, annotated_img)
        print(f"📸 บันทึกภาพพร้อม HUD ที่: {out_path}")

        self.save_to_handysense_db(leaf_res, soil_res, out_path)

        print("─────────────────────────────────────────────────────────────")
        print("🌿 [ผลการวิเคราะห์สุขภาพใบพืช]")
        print(f"   • ค่าดัชนี VARI (Green Index)   : {leaf_res['vari_mean']:+.4f}")
        print(f"   • ค่าดัชนี GLI                  : {leaf_res['gli_mean']:+.4f}")
        print(f"   • สัดส่วนใบเขียวสมบูรณ์         : {leaf_res['green_ratio']:.1f}%")
        print(f"   • สัดส่วนใบเหลืองขาดธาตุ (N)     : {leaf_res['chlorosis_ratio']:.1f}%")
        print(f"   • ความรุนแรงของรอยแผลโรคพืช     : {leaf_res['disease_severity']:.1f}%")
        print("🟤 [ผลการวิเคราะห์สภาพหน้าดิน]")
        print(f"   • ค่าความชื้นหน้าดินประเมิน (Proxy): {soil_res['surface_moisture_est']:.1f}%")
        print(f"   • ความขรุขระแตกระแหงของดิน      : {soil_res['roughness_score']:.1f}/100")
        print("─────────────────────────────────────────────────────────────")

        return {
            "leaf": leaf_res,
            "soil": soil_res,
            "output_image": out_path
        }


def main():
    parser = argparse.ArgumentParser(description="🌱 AgriVision Analyzer for HandySense")
    parser.add_argument("--image", type=str, help="เส้นทางไฟล์ภาพใบพืชหรือหน้าดินที่ต้องการวิเคราะห์")
    parser.add_argument("--camera", action="store_true", help="เปิดกล้องเว็บแคมเพื่อวิเคราะห์ภาพแบบเรียลไทม์")
    parser.add_argument("--db", type=str, default=DEFAULT_DB_PATH, help="เส้นทางไฟล์ SQLite ของ HandySense")

    args = parser.parse_args()
    analyzer = AgriVisionAnalyzer(db_path=args.db)

    if args.image:
        analyzer.process_image(args.image)
    elif args.camera:
        print("🎥 กำลังเปิดกล้อง... (กด 's' เพื่อสแนปช็อตวิเคราะห์และบันทึก, กด 'q' เพื่อออก)")
        cap = cv2.VideoCapture(0)
        if not cap.isOpened():
            print("[ERROR] ไม่สามารถเปิดกล้องได้")
            return

        while True:
            ret, frame = cap.read()
            if not ret:
                break

            leaf_res = analyzer.analyze_leaf(frame)
            soil_res = analyzer.analyze_soil(frame)
            hud = analyzer.render_hud(frame, leaf_res, soil_res)

            cv2.imshow("AgriVision HUD (Press 's' to Save, 'q' to Quit)", hud)
            key = cv2.waitKey(1) & 0xFF
            if key == ord('q'):
                break
            elif key == ord('s'):
                analyzer.process_image(frame)
                print("⚡ สแนปช็อตและบันทึกลงฐานข้อมูลเรียบร้อย!")

        cap.release()
        cv2.destroyAllWindows()
    else:
        print("[INFO] กรุณาระบุ --image <path> หรือ --camera เพื่อเริ่มการทำงาน")
        print("ตัวอย่าง: python3 agri_vision_analyzer.py --image test_leaf.jpg")


if __name__ == "__main__":
    main()
