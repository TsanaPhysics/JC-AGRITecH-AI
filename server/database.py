from datetime import datetime
from sqlalchemy import create_engine, Column, Integer, Float, String, DateTime, Boolean
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
import os

# เส้นทางไฟล์ฐานข้อมูล SQLite ภายในโฟลเดอร์ server
DB_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "agri_telemetry.db")
DATABASE_URL = os.getenv("DATABASE_URL", f"sqlite:///{DB_PATH}")

# กำหนด connect_args สำหรับ SQLite
connect_args = {"check_same_thread": False} if DATABASE_URL.startswith("sqlite") else {}

engine = create_engine(DATABASE_URL, connect_args=connect_args)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

class TelemetryRecord(Base):
    """
    ตารางบันทึกข้อมูล Telemetry จากฟาร์มอัจฉริยะ JC-AgriTech + AI
    ครอบคลุมตัวแปรสภาพแวดล้อม พลังงานแสง คุณสมบัติดิน และสถานะอุปกรณ์สั่งการ
    """
    __tablename__ = "telemetry"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    timestamp = Column(DateTime, default=datetime.now, index=True)
    
    # 1. ข้อมูลบรรยากาศโรงเรือน / แปลงปลูก (SHT45)
    air_temp = Column(Float, nullable=True)           # อุณหภูมิอากาศ (°C)
    air_humidity = Column(Float, nullable=True)       # ความชื้นสัมพัทธ์ในอากาศ (%RH)
    air_dew_point = Column(Float, nullable=True)      # จุดน้ำค้าง (°C)
    air_vpd = Column(Float, nullable=True)            # แรงดึงระเหยน้ำ (VPD in kPa)
    air_connected = Column(Boolean, default=True)

    # 2. ข้อมูลความเข้มแสง (โดมตะวัน BH1750)
    light_lux = Column(Float, nullable=True)          # ความเข้มแสง (Lux)
    light_klux = Column(Float, nullable=True)         # ความเข้มแสง (kLux)
    light_solar_radiation = Column(Float, nullable=True) # พลังงานแสงอาทิตย์ (W/m²)
    light_connected = Column(Boolean, default=True)

    # 3. ข้อมูลความชื้นดินผิวดิน (Soil Stick เกษตรไทย IoT)
    soil_stick_adc = Column(Integer, nullable=True)   # สัญญาณดิบ ADC (0 - 4095)
    soil_stick_moisture = Column(Float, nullable=True)# ความชื้นผิวดิน (%)

    # 4. ข้อมูลคุณสมบัติดินเชิงลึกเขตรากพืช (Soil Multi-parameter 7-in-1 Modbus RTU)
    soil_7in1_moisture = Column(Float, nullable=True) # ความชื้นดินลึก (%)
    soil_7in1_temp = Column(Float, nullable=True)     # อุณหภูมิดิน (°C)
    soil_7in1_ec = Column(Float, nullable=True)       # สภาพนำไฟฟ้า / ความเค็มปุ๋ย (µS/cm)
    soil_7in1_ph = Column(Float, nullable=True)       # ค่ากรด-ด่างของดิน (pH)
    soil_7in1_n = Column(Float, nullable=True)        # ปริมาณไนโตรเจน N (mg/kg)
    soil_7in1_p = Column(Float, nullable=True)        # ปริมาณฟอสฟอรัส P (mg/kg)
    soil_7in1_k = Column(Float, nullable=True)        # ปริมาณโพแทสเซียม K (mg/kg)
    soil_7in1_connected = Column(Boolean, default=True)

    # 5. สถานะอุปกรณ์ควบคุมอัตโนมัติ (Actuators)
    actuator_pump = Column(Boolean, default=False)    # สถานะปั๊มน้ำ
    actuator_misting = Column(Boolean, default=False) # สถานะระบบพ่นหมอก

    # 6. ฟิลด์พยากรณ์จากโมเดล Deep Learning (AI Predictions)
    ai_predicted_soil_moist = Column(Float, nullable=True) # ค่าทำนายความชื้นดินล่วงหน้า 1 ชั่วโมง
    ai_predicted_vpd = Column(Float, nullable=True)        # ค่าทำนาย VPD ล่วงหน้า 1 ชั่วโมง

    # 7. ฟิลด์ชดเชยอุณหภูมิและความชื้นจากโมเดล TinyML Edge AI (Soil Neural Calibrator)
    ai_calibrated_n = Column(Float, nullable=True)         # ไนโตรเจนชดเชยแล้ว (mg/kg)
    ai_calibrated_p = Column(Float, nullable=True)         # ฟอสฟอรัสชดเชยแล้ว (mg/kg)
    ai_calibrated_k = Column(Float, nullable=True)         # โพแทสเซียมชดเชยแล้ว (mg/kg)
    ai_calibrated_ph = Column(Float, nullable=True)        # pH ชดเชยอุณหภูมิ Nernst แล้ว
    ai_calibrated_moisture = Column(Float, nullable=True)  # ความชื้นดินผสาน 2 เซนเซอร์ (%)
    ai_confidence = Column(Float, nullable=True)           # ค่าความเชื่อมั่นโมเดล TinyML (0.0 - 1.0)

def init_db():
    """สร้างตารางในฐานข้อมูลหากยังไม่มี พร้อม migration อัตโนมัติสำหรับฟิลด์ใหม่"""
    Base.metadata.create_all(bind=engine)
    
    # อัปเดตคอลัมน์ใหม่สำหรับฐานข้อมูล SQLite เดิมที่สร้างไว้ก่อนหน้า
    if DATABASE_URL.startswith("sqlite"):
        import sqlite3
        db_file = DATABASE_URL.replace("sqlite:///", "")
        if os.path.exists(db_file):
            conn = sqlite3.connect(db_file)
            cursor = conn.cursor()
            cursor.execute("PRAGMA table_info(telemetry);")
            existing_cols = [row[1] for row in cursor.fetchall()]
            
            new_columns = [
                ("ai_calibrated_n", "REAL"),
                ("ai_calibrated_p", "REAL"),
                ("ai_calibrated_k", "REAL"),
                ("ai_calibrated_ph", "REAL"),
                ("ai_calibrated_moisture", "REAL"),
                ("ai_confidence", "REAL")
            ]
            for col_name, col_type in new_columns:
                if col_name not in existing_cols:
                    try:
                        cursor.execute(f"ALTER TABLE telemetry ADD COLUMN {col_name} {col_type};")
                    except Exception:
                        pass
            conn.commit()
            conn.close()

def get_db():
    """Dependency สำหรับ FastAPI Session"""
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
