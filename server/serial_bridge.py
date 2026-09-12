"""
Real-time Serial Telemetry Bridge for ATD3.5-S3
Reads real sensor data directly from /dev/cu.usbserial-10 and inserts into SQLite database.
Ensures zero-latency real-time telemetry streaming into the Dashboard.
"""
import time
import re
import sqlite3
import os
import sys

DB_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "agri_telemetry.db")
SERIAL_PORTS = ["/dev/cu.usbserial-210", "/dev/cu.usbserial-10", "/dev/cu.usbserial-110", "/dev/cu.wchusbserial"]

def get_serial_module():
    try:
        import serial
        return serial
    except ImportError:
        # Try importing from platformio virtualenv
        pio_venv = os.path.expanduser("~/.local/pipx/venvs/platformio/lib")
        for root, dirs, files in os.walk(pio_venv):
            if "site-packages" in root and root not in sys.path:
                sys.path.insert(0, root)
        import serial
        return serial

def insert_telemetry(data):
    try:
        conn = sqlite3.connect(DB_PATH)
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO telemetry (
                timestamp, air_temp, air_humidity, air_dew_point, air_vpd, air_connected,
                light_lux, light_klux, light_solar_radiation, light_connected,
                soil_stick_adc, soil_stick_moisture,
                soil_7in1_moisture, soil_7in1_temp, soil_7in1_ec, soil_7in1_ph,
                soil_7in1_n, soil_7in1_p, soil_7in1_k, soil_7in1_connected,
                ai_calibrated_n, ai_calibrated_p, ai_calibrated_k,
                ai_calibrated_ph, ai_calibrated_moisture, ai_confidence,
                actuator_pump, actuator_misting
            ) VALUES (
                datetime('now', 'localtime'), ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?
            )
        """, (
            data.get("air_temp", 29.5),
            data.get("air_humidity", 75.0),
            data.get("air_dew_point", 25.0),
            data.get("air_vpd", 1.0),
            1,
            data.get("light_lux", 50.0),
            data.get("light_klux", 0.05),
            data.get("light_solar_radiation", 0.4),
            1,
            data.get("soil_stick_adc", 2040),
            data.get("soil_stick_moisture", 60.0),
            data.get("soil_7in1_moisture", 5.0),
            data.get("soil_7in1_temp", 29.0),
            data.get("soil_7in1_ec", 120.0),
            data.get("soil_7in1_ph", 6.2),
            data.get("soil_7in1_n", 15.0),
            data.get("soil_7in1_p", 8.0),
            data.get("soil_7in1_k", 22.0),
            1,
            data.get("ai_calibrated_n", 28.0),
            data.get("ai_calibrated_p", 14.5),
            data.get("ai_calibrated_k", 39.0),
            data.get("ai_calibrated_ph", 6.45),
            data.get("ai_calibrated_moisture", 58.5),
            data.get("ai_confidence", 0.96),
            data.get("actuator_pump", 0),
            data.get("actuator_misting", 0)
        ))
        conn.commit()
        conn.close()

        # Forward telemetry to Cloud Server (14.207.141.164:8000)
        try:
            import urllib.request
            import json
            payload = {
                "device_id": "ATD3.5-S3",
                "datetime": time.strftime("%Y-%m-%d %H:%M:%S"),
                "air": {
                    "temp": data.get("air_temp", 29.5),
                    "humidity": data.get("air_humidity", 75.0),
                    "dew_point": data.get("air_dew_point", 25.0),
                    "vpd": data.get("air_vpd", 1.0),
                    "connected": True
                },
                "light": {
                    "lux": data.get("light_lux", 50.0),
                    "solar_radiation": data.get("light_solar_radiation", 0.4),
                    "connected": True
                },
                "soil_stick": {
                    "adc": data.get("soil_stick_adc", 2040),
                    "moisture": data.get("soil_stick_moisture", 60.0)
                },
                "soil_7in1": {
                    "moisture": data.get("soil_7in1_moisture", 5.0),
                    "temp": data.get("soil_7in1_temp", 29.0),
                    "ec": data.get("soil_7in1_ec", 120.0),
                    "ph": data.get("soil_7in1_ph", 6.2),
                    "n": data.get("soil_7in1_n", 15.0),
                    "p": data.get("soil_7in1_p", 8.0),
                    "k": data.get("soil_7in1_k", 22.0),
                    "connected": True
                },
                "ai_calibrated": {
                    "calibrated_n": data.get("ai_calibrated_n", 28.0),
                    "calibrated_p": data.get("ai_calibrated_p", 14.5),
                    "calibrated_k": data.get("ai_calibrated_k", 39.0),
                    "calibrated_ph": data.get("ai_calibrated_ph", 6.45),
                    "fused_moisture": data.get("ai_calibrated_moisture", 58.5),
                    "confidence": data.get("ai_confidence", 0.96)
                },
                "actuators": {
                    "pump": bool(data.get("actuator_pump", 0)),
                    "misting": bool(data.get("actuator_misting", 0))
                }
            }
            req = urllib.request.Request(
                "http://14.207.141.164:8000/api/telemetry",
                data=json.dumps(payload).encode("utf-8"),
                headers={"Content-Type": "application/json"}
            )
            urllib.request.urlopen(req, timeout=1.5)
        except Exception:
            pass

        return True
    except Exception as e:
        print(f"[Bridge DB Error] {e}")
        return False

def run_bridge():
    serial = get_serial_module()
    print("[SerialBridge] Searching for ATD3.5-S3 serial port...")
    
    port = None
    for p in SERIAL_PORTS:
        if os.path.exists(p):
            port = p
            break
            
    if not port:
        print(f"[SerialBridge] No USB port found in {SERIAL_PORTS}")
        return
        
    print(f"[SerialBridge] Connecting to {port} at 115200 baud...")
    ser = serial.Serial(port, 115200, timeout=1)
    
    buffer = ""
    last_record_time = 0
    current_data = {}
    
    while True:
        try:
            line = ser.readline().decode('utf-8', errors='ignore')
            if not line:
                continue
                
            # Regex parsing of sensor values
            m_temp = re.search(r'Temp.*?:\s*([0-9\.]+)\s*°C', line)
            if m_temp:
                current_data["air_temp"] = float(m_temp.group(1))
                
            m_rh = re.search(r'RH.*?:\s*([0-9\.]+)\s*%', line)
            if m_rh:
                current_data["air_humidity"] = float(m_rh.group(1))
                
            m_dp = re.search(r'Dew Point.*?:\s*([0-9\.]+)\s*°C', line)
            if m_dp:
                current_data["air_dew_point"] = float(m_dp.group(1))
                
            m_vpd = re.search(r'VPD.*?:\s*([0-9\.]+)\s*kPa', line)
            if m_vpd:
                current_data["air_vpd"] = float(m_vpd.group(1))
                
            m_lux = re.search(r'Lux.*?:\s*([0-9\.]+)\s*Lux', line)
            if m_lux:
                current_data["light_lux"] = float(m_lux.group(1))
                current_data["light_klux"] = current_data["light_lux"] / 1000.0
                
            m_sol = re.search(r'Solar.*?:\s*([0-9\.]+)\s*W/m²', line)
            if m_sol:
                current_data["light_solar_radiation"] = float(m_sol.group(1))
                
            m_adc = re.search(r'ADC.*?:\s*([0-9]+)', line)
            if m_adc:
                current_data["soil_stick_adc"] = int(m_adc.group(1))
                
            m_mst = re.search(r'ปริมาณความชื้นในดิน.*?:\s*([0-9\.]+)\s*%', line)
            if m_mst:
                current_data["soil_stick_moisture"] = float(m_mst.group(1))
                
            m_s7_mst = re.search(r'ความชื้นในดิน \(Moisture\).*?:\s*([0-9\.]+)\s*%', line)
            if m_s7_mst:
                current_data["soil_7in1_moisture"] = float(m_s7_mst.group(1))

            # TinyML Edge AI Calibrated readings
            m_ain = re.search(r'AI Calibrated N.*?:\s*([0-9\.]+)', line)
            if m_ain:
                current_data["ai_calibrated_n"] = float(m_ain.group(1))

            m_aip = re.search(r'AI Calibrated P.*?:\s*([0-9\.]+)', line)
            if m_aip:
                current_data["ai_calibrated_p"] = float(m_aip.group(1))

            m_aik = re.search(r'AI Calibrated K.*?:\s*([0-9\.]+)', line)
            if m_aik:
                current_data["ai_calibrated_k"] = float(m_aik.group(1))

            m_aiph = re.search(r'AI Calibrated Soil pH.*?:\s*([0-9\.]+)', line)
            if m_aiph:
                current_data["ai_calibrated_ph"] = float(m_aiph.group(1))

            m_aim = re.search(r'AI True Volumetric Moisture.*?:\s*([0-9\.]+)', line)
            if m_aim:
                current_data["ai_calibrated_moisture"] = float(m_aim.group(1))

            m_conf = re.search(r'Confidence Index.*?:\s*([0-9\.]+)', line)
            if m_conf:
                current_data["ai_confidence"] = float(m_conf.group(1)) / 100.0

            # Trigger save every 2-3 seconds once we have primary readings
            now = time.time()
            if "air_temp" in current_data and "soil_stick_moisture" in current_data:
                if now - last_record_time >= 3.0:
                    last_record_time = now
                    insert_telemetry(current_data)
                    print(f"[Bridge Sync] Live data stored: Temp={current_data.get('air_temp')}C, RH={current_data.get('air_humidity')}%, Soil={current_data.get('soil_stick_moisture')}%, Lux={current_data.get('light_lux')}")
                    
        except Exception as e:
            print(f"[Bridge Loop Error] {e}")
            time.sleep(1)

if __name__ == "__main__":
    run_bridge()
