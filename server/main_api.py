from fastapi import FastAPI, Depends, HTTPException, Query, Response
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy.orm import Session
from datetime import datetime, timedelta
import pandas as pd
import io

from database import init_db, get_db, TelemetryRecord

# เริ่มต้นฐานข้อมูล
init_db()

app = FastAPI(
    title="JC-AgriTech + AI Telemetry Hub",
    description="REST API สำหรับรับข้อมูลเซนเซอร์การเกษตรจากบอร์ด ATD3.5-S3 และบริการข้อมูลสำหรับ AI Dashboard",
    version="1.0.0"
)

# อนุญาตให้เข้าถึง API จาก Cross-Origin (CORS) สำหรับเว็บ Dashboard
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def root():
    return {
        "system": "JC -AgriTech + AI Local Telemetry & AI Hub",
        "status": "Online",
        "docs_url": "/docs",
        "streamlit_dashboard": "http://localhost:8501"
    }

@app.post("/api/telemetry")
async def receive_telemetry(payload: dict, db: Session = Depends(get_db)):
    """
    รับข้อมูล JSON จากบอร์ด ATD3.5-S3 (ESP32-S3) แล้วบันทึกลง SQLite/PostgreSQL ทันที
    """
    try:
        # ถอดรหัสโครงสร้าง JSON
        air = payload.get("air", {})
        light = payload.get("light", {})
        soil_stick = payload.get("soil_stick", {})
        soil_7in1 = payload.get("soil_7in1", {})
        ai_cal = payload.get("ai_calibrated", {})
        actuators = payload.get("actuators", {})

        # จัดการเวลาบันทึก
        record_time = datetime.now()
        if "datetime" in payload and payload["datetime"] != "N/A":
            try:
                record_time = datetime.strptime(payload["datetime"], "%Y-%m-%d %H:%M:%S")
            except Exception:
                pass

        # สร้าง Entity บันทึกในฐานข้อมูล
        record = TelemetryRecord(
            timestamp=record_time,
            # อากาศ SHT45
            air_temp=air.get("temperature", air.get("temp")),
            air_humidity=air.get("humidity"),
            air_dew_point=air.get("dew_point"),
            air_vpd=air.get("vpd"),
            air_connected=air.get("connected", True),
            # แสง โดมตะวัน BH1750
            light_lux=light.get("lux"),
            light_klux=light.get("klux", (light.get("lux") / 1000.0) if light.get("lux") is not None else None),
            light_solar_radiation=light.get("solar_radiation"),
            light_connected=light.get("connected", True),
            # ดินผิวดิน Soil Stick
            soil_stick_adc=soil_stick.get("adc_raw", soil_stick.get("adc")),
            soil_stick_moisture=soil_stick.get("moisture_percent", soil_stick.get("moisture")),
            # ดินลึกเขตรากพืช 7-in-1 Modbus
            soil_7in1_moisture=soil_7in1.get("moisture_percent", soil_7in1.get("moisture")),
            soil_7in1_temp=soil_7in1.get("temperature", soil_7in1.get("temp")),
            soil_7in1_ec=soil_7in1.get("ec"),
            soil_7in1_ph=soil_7in1.get("ph"),
            soil_7in1_n=soil_7in1.get("nitrogen", soil_7in1.get("n")),
            soil_7in1_p=soil_7in1.get("phosphorus", soil_7in1.get("p")),
            soil_7in1_k=soil_7in1.get("potassium", soil_7in1.get("k")),
            soil_7in1_connected=soil_7in1.get("connected", True),
            # TinyML Edge AI Calibrated Data
            ai_calibrated_n=ai_cal.get("calibrated_n"),
            ai_calibrated_p=ai_cal.get("calibrated_p"),
            ai_calibrated_k=ai_cal.get("calibrated_k"),
            ai_calibrated_ph=ai_cal.get("calibrated_ph"),
            ai_calibrated_moisture=ai_cal.get("fused_moisture"),
            ai_confidence=ai_cal.get("confidence"),
            # สถานะอุปกรณ์รีเลย์
            actuator_pump=actuators.get("pump", False),
            actuator_misting=actuators.get("misting", False)
        )

        db.add(record)
        db.commit()
        db.refresh(record)

        return {
            "status": "success",
            "message": "Telemetry record saved successfully",
            "id": record.id,
            "timestamp": record.timestamp.strftime("%Y-%m-%d %H:%M:%S")
        }
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=400, detail=f"Error saving telemetry: {str(e)}")

@app.get("/api/telemetry/latest")
def get_latest_telemetry(db: Session = Depends(get_db)):
    """ดึงข้อมูลแถวล่าสุดสำหรับแสดงผลบน Dashboard แบบสด"""
    record = db.query(TelemetryRecord).order_by(TelemetryRecord.id.desc()).first()
    if not record:
        return {"status": "empty", "message": "No telemetry data recorded yet."}
    
    return {
        "id": record.id,
        "timestamp": record.timestamp.strftime("%Y-%m-%d %H:%M:%S"),
        "air": {
            "temperature": record.air_temp,
            "humidity": record.air_humidity,
            "dew_point": record.air_dew_point,
            "vpd": record.air_vpd,
            "connected": record.air_connected
        },
        "light": {
            "lux": record.light_lux,
            "klux": record.light_klux,
            "solar_radiation": record.light_solar_radiation,
            "connected": record.light_connected
        },
        "soil_stick": {
            "adc_raw": record.soil_stick_adc,
            "moisture_percent": record.soil_stick_moisture
        },
        "soil_7in1": {
            "moisture_percent": record.soil_7in1_moisture,
            "temperature": record.soil_7in1_temp,
            "ec": record.soil_7in1_ec,
            "ph": record.soil_7in1_ph,
            "nitrogen": record.soil_7in1_n,
            "phosphorus": record.soil_7in1_p,
            "potassium": record.soil_7in1_k,
            "connected": record.soil_7in1_connected
        },
        "ai_calibrated": {
            "nitrogen": record.ai_calibrated_n,
            "phosphorus": record.ai_calibrated_p,
            "potassium": record.ai_calibrated_k,
            "ph": record.ai_calibrated_ph,
            "moisture": record.ai_calibrated_moisture,
            "confidence": record.ai_confidence
        },
        "actuators": {
            "pump": record.actuator_pump,
            "misting": record.actuator_misting
        }
    }

@app.get("/api/telemetry/history")
def get_telemetry_history(
    limit: int = Query(200, ge=1, le=5000),
    hours: int = Query(None, ge=1),
    db: Session = Depends(get_db)
):
    """ดึงข้อมูลอนุกรมเวลารองรับการพล็อตกราฟ Time-series และเทรนโมเดล AI"""
    query = db.query(TelemetryRecord)
    if hours:
        since = datetime.now() - timedelta(hours=hours)
        query = query.filter(TelemetryRecord.timestamp >= since)
    
    records = query.order_by(TelemetryRecord.timestamp.desc()).limit(limit).all()
    # กลับลำดับให้เรียงจากอดีต -> ปัจจุบัน
    records.reverse()

    results = []
    for r in records:
        results.append({
            "id": r.id,
            "timestamp": r.timestamp.strftime("%Y-%m-%d %H:%M:%S"),
            "air_temp": r.air_temp,
            "air_humidity": r.air_humidity,
            "air_vpd": r.air_vpd,
            "light_lux": r.light_lux,
            "light_solar_radiation": r.light_solar_radiation,
            "soil_stick_moisture": r.soil_stick_moisture,
            "soil_7in1_moisture": r.soil_7in1_moisture,
            "soil_7in1_temp": r.soil_7in1_temp,
            "soil_7in1_ph": r.soil_7in1_ph,
            "soil_7in1_ec": r.soil_7in1_ec,
            "soil_7in1_n": r.soil_7in1_n,
            "soil_7in1_p": r.soil_7in1_p,
            "soil_7in1_k": r.soil_7in1_k,
            "ai_calibrated_n": r.ai_calibrated_n,
            "ai_calibrated_p": r.ai_calibrated_p,
            "ai_calibrated_k": r.ai_calibrated_k,
            "ai_calibrated_ph": r.ai_calibrated_ph,
            "ai_calibrated_moisture": r.ai_calibrated_moisture,
            "ai_confidence": r.ai_confidence,
            "actuator_pump": 1 if r.actuator_pump else 0,
            "actuator_misting": 1 if r.actuator_misting else 0
        })
    return results

@app.get("/api/telemetry/export-csv")
def export_telemetry_csv(db: Session = Depends(get_db)):
    """ดาวน์โหลดชุดข้อมูลทั้งหมดออกมาเป็นไฟล์ CSV สำหรับนักวิทยาศาสตร์ข้อมูลและนักวิจัย AI"""
    records = db.query(TelemetryRecord).order_by(TelemetryRecord.id.asc()).all()
    if not records:
        raise HTTPException(status_code=404, detail="No data available to export")

    data = []
    for r in records:
        data.append({
            "id": r.id,
            "timestamp": r.timestamp.strftime("%Y-%m-%d %H:%M:%S"),
            "air_temp": r.air_temp,
            "air_humidity": r.air_humidity,
            "air_dew_point": r.air_dew_point,
            "air_vpd": r.air_vpd,
            "light_lux": r.light_lux,
            "light_solar_radiation": r.light_solar_radiation,
            "soil_stick_moisture": r.soil_stick_moisture,
            "soil_7in1_moisture": r.soil_7in1_moisture,
            "soil_7in1_temp": r.soil_7in1_temp,
            "soil_7in1_ph": r.soil_7in1_ph,
            "soil_7in1_ec": r.soil_7in1_ec,
            "soil_7in1_n": r.soil_7in1_n,
            "soil_7in1_p": r.soil_7in1_p,
            "soil_7in1_k": r.soil_7in1_k,
            "ai_calibrated_n": r.ai_calibrated_n,
            "ai_calibrated_p": r.ai_calibrated_p,
            "ai_calibrated_k": r.ai_calibrated_k,
            "ai_calibrated_ph": r.ai_calibrated_ph,
            "ai_calibrated_moisture": r.ai_calibrated_moisture,
            "ai_confidence": r.ai_confidence,
            "actuator_pump": 1 if r.actuator_pump else 0,
            "actuator_misting": 1 if r.actuator_misting else 0
        })

    df = pd.DataFrame(data)
    stream = io.StringIO()
    df.to_csv(stream, index=False)
    
    filename = f"jc_agritech_dataset_{datetime.now().strftime('%Y%m%d_%H%M%S')}.csv"
    response = Response(content=stream.getvalue(), media_type="text/csv")
    response.headers["Content-Disposition"] = f"attachment; filename={filename}"
    return response

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
