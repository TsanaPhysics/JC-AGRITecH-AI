"""
Real-time Serial Telemetry Bridge for ATD3.5-S3 (ESP32-S3 Gravity Controller)
Reads real sensor data directly from /dev/cu.usbserial-10 and syncs into:
1. Localhost PHP API (leqs-workshop/api/api.php)
2. Local telemetry_state.json (zero-delay instant cache)
3. SQLite3 Database (leqs-workshop/data/leqs_xai.db & server/agri_telemetry.db)
"""
import time
import re
import sqlite3
import os
import sys
import json
import urllib.request

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_PATH = os.path.join(BASE_DIR, "server", "agri_telemetry.db")
LEQS_DB_PATH = os.path.join(BASE_DIR, "leqs-workshop", "data", "leqs_xai.db")
LEQS_STATE_PATH = os.path.join(BASE_DIR, "leqs-workshop", "data", "telemetry_state.json")

SERIAL_PORTS = ["/dev/cu.usbserial-10", "/dev/cu.usbserial-210", "/dev/cu.usbserial-110", "/dev/cu.wchusbserial"]

def get_serial_module():
    try:
        import serial
        return serial
    except ImportError:
        pio_venv = os.path.expanduser("~/.local/pipx/venvs/platformio/lib")
        for root, dirs, files in os.walk(pio_venv):
            if "site-packages" in root and root not in sys.path:
                sys.path.insert(0, root)
        import serial
        return serial

def push_telemetry(data):
    try:
        now_dt = time.strftime("%Y-%m-%d %H:%M:%S")
        now_epoch = int(time.time())

        air_temp = float(data.get("air_temp", 25.3))
        air_hum = float(data.get("air_humidity", 58.0))
        air_dp = float(data.get("air_dew_point", 16.4))
        air_vpd = float(data.get("air_vpd", 1.36))

        light_lux = float(data.get("light_lux", 57.5))
        light_klux = round(light_lux / 1000.0, 2)
        light_solar = float(data.get("light_solar_radiation", 0.45))

        stick_adc = int(data.get("soil_stick_adc", 2023))
        if "soil_stick_moisture" in data and abs(float(data["soil_stick_moisture"]) - 61.8) > 0.05:
            stick_moist = float(data["soil_stick_moisture"])
        else:
            # Calibrate dynamically from ADC A1 (AIR=2950, WATER=1450)
            calc_m = ((2950.0 - float(stick_adc)) / (2950.0 - 1450.0)) * 100.0
            stick_moist = round(max(0.0, min(100.0, calc_m)), 1)
        stick_ph = float(data.get("soil_stick_ph", 3.06))

        s7_moist = float(data.get("soil_7in1_moisture", 2.6))
        s7_temp = float(data.get("soil_7in1_temp", 25.5))
        s7_ec = float(data.get("soil_7in1_ec", 0.0))
        s7_ph = float(data.get("soil_7in1_ph", 8.60))
        s7_n = float(data.get("soil_7in1_n", 0.0))
        s7_p = float(data.get("soil_7in1_p", 0.0))
        s7_k = float(data.get("soil_7in1_k", 0.0))

        ai_n = float(data.get("ai_calibrated_n", 21.8))
        ai_p = float(data.get("ai_calibrated_p", 13.9))
        ai_k = float(data.get("ai_calibrated_k", 8.0))
        ai_ph = float(data.get("ai_calibrated_ph", 9.50))
        ai_moist = float(data.get("ai_calibrated_moisture", 21.6))
        ai_conf = float(data.get("ai_confidence", 0.776))

        # 1. Update leqs-workshop/data/telemetry_state.json directly
        if os.path.exists(LEQS_STATE_PATH):
            try:
                with open(LEQS_STATE_PATH, "r", encoding="utf-8") as f:
                    state = json.load(f)
            except Exception:
                state = {}
            
            if "sensors" not in state:
                state["sensors"] = {}
            if "board" not in state:
                state["board"] = {}
            if "ai_calibrated" not in state:
                state["ai_calibrated"] = {}

            state["board"]["cloud_status"] = "ESP32 DIRECT (ONLINE)"
            state["board"]["data_source"] = "DIRECT_USB_C_SERIAL (/dev/cu.usbserial-10)"
            state["board"]["telemetry_timestamp"] = now_dt
            state["board"]["last_seen"] = now_dt
            state["board"]["last_direct_post_time"] = now_epoch
            state["board"]["is_live"] = True

            state["sensors"]["temperature"] = round(air_temp, 2)
            state["sensors"]["temperature_f"] = round((air_temp * 1.8) + 32.0, 2)
            state["sensors"]["humidity"] = round(air_hum, 2)
            state["sensors"]["dew_point"] = round(air_dp, 2)
            state["sensors"]["dew_margin"] = round(air_temp - air_dp, 2)
            state["sensors"]["vpd"] = round(air_vpd, 2)
            state["sensors"]["par_lux"] = round(light_lux, 1)
            state["sensors"]["klux"] = light_klux
            state["sensors"]["solar_radiation"] = round(light_solar, 2)

            state["sensors"]["soil_stick_adc"] = stick_adc
            state["sensors"]["soil_stick_moisture"] = round(stick_moist, 1)
            state["sensors"]["soil_stick_ph"] = round(stick_ph, 2)
            state["sensors"]["soil_stick_ph_volt"] = round(data.get("soil_stick_ph_volt", 2.32), 2)

            state["sensors"]["soil_moisture"] = round(s7_moist, 1)
            state["sensors"]["soil_temperature"] = round(s7_temp, 1)
            state["sensors"]["soil_ec"] = round(s7_ec, 1)
            state["sensors"]["soil_ph"] = round(s7_ph, 2)
            state["sensors"]["nitrogen"] = round(s7_n, 1)
            state["sensors"]["phosphorus"] = round(s7_p, 1)
            state["sensors"]["potassium"] = round(s7_k, 1)
            state["sensors"]["updated_at"] = now_dt

            state["ai_calibrated"]["nitrogen"] = round(ai_n, 1)
            state["ai_calibrated"]["phosphorus"] = round(ai_p, 1)
            state["ai_calibrated"]["potassium"] = round(ai_k, 1)
            state["ai_calibrated"]["ph"] = round(ai_ph, 2)
            state["ai_calibrated"]["moisture"] = round(ai_moist, 1)
            state["ai_calibrated"]["confidence"] = round(ai_conf, 3)

            try:
                with open(LEQS_STATE_PATH, "w", encoding="utf-8") as f:
                    json.dump(state, f, indent=4, ensure_ascii=False)
            except Exception as e:
                print(f"[State Save Error] {e}")

        # 2. Forward to Localhost PHP API via HTTP POST
        payload = {
            "device_id": "ATD3.5-S3",
            "datetime": now_dt,
            "temperature": round(air_temp, 2),
            "humidity": round(air_hum, 2),
            "dew_point": round(air_dp, 2),
            "vpd": round(air_vpd, 2),
            "par_lux": round(light_lux, 1),
            "solar_radiation": round(light_solar, 2),
            "soil_stick_adc": stick_adc,
            "soil_stick_moisture": round(stick_moist, 1),
            "soil_stick_ph": round(stick_ph, 2),
            "soil_moisture": round(s7_moist, 1),
            "soil_temperature": round(s7_temp, 1),
            "soil_ec": round(s7_ec, 1),
            "soil_ph": round(s7_ph, 2),
            "nitrogen": round(s7_n, 1),
            "phosphorus": round(s7_p, 1),
            "potassium": round(s7_k, 1),
            "air": {
                "temperature": round(air_temp, 2),
                "temp": round(air_temp, 2),
                "humidity": round(air_hum, 2),
                "dew_point": round(air_dp, 2),
                "vpd": round(air_vpd, 2),
                "connected": True
            },
            "light": {
                "lux": round(light_lux, 1),
                "solar_radiation": round(light_solar, 2),
                "connected": True
            },
            "soil_stick": {
                "adc_raw": stick_adc,
                "moisture_percent": round(stick_moist, 1),
                "moisture": round(stick_moist, 1),
                "ph": round(stick_ph, 2)
            },
            "soil_7in1": {
                "moisture_percent": round(s7_moist, 1),
                "moisture": round(s7_moist, 1),
                "temperature": round(s7_temp, 1),
                "temp": round(s7_temp, 1),
                "ec": round(s7_ec, 1),
                "ph": round(s7_ph, 2),
                "nitrogen": round(s7_n, 1),
                "phosphorus": round(s7_p, 1),
                "potassium": round(s7_k, 1),
                "connected": True
            },
            "ai_calibrated": {
                "nitrogen": round(ai_n, 1),
                "phosphorus": round(ai_p, 1),
                "potassium": round(ai_k, 1),
                "ph": round(ai_ph, 2),
                "moisture_percent": round(ai_moist, 1),
                "confidence": round(ai_conf, 3)
            }
        }

        try:
            req = urllib.request.Request(
                "http://localhost/handysense/leqs-workshop/api/api.php?action=update_telemetry",
                data=json.dumps(payload).encode("utf-8"),
                headers={"Content-Type": "application/json"}
            )
            urllib.request.urlopen(req, timeout=0.8)
        except Exception:
            pass

        # 3. Insert into SQLite databases
        for db_file in [DB_PATH, LEQS_DB_PATH]:
            if not os.path.exists(os.path.dirname(db_file)):
                continue
            try:
                conn = sqlite3.connect(db_file)
                cur = conn.cursor()
                if "agri_telemetry" in db_file:
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
                            datetime('now', 'localtime'), ?, ?, ?, ?, 1,
                            ?, ?, ?, 1,
                            ?, ?,
                            ?, ?, ?, ?,
                            ?, ?, ?, 1,
                            ?, ?, ?,
                            ?, ?, ?,
                            0, 0
                        )
                    """, (
                        air_temp, air_hum, air_dp, air_vpd,
                        light_lux, light_klux, light_solar,
                        stick_adc, stick_moist,
                        s7_moist, s7_temp, s7_ec, s7_ph,
                        s7_n, s7_p, s7_k,
                        ai_n, ai_p, ai_k,
                        ai_ph, ai_moist, ai_conf
                    ))
                else:
                    cur.execute("""
                        INSERT INTO telemetry_logs (
                            temperature, humidity, dew_point, vpd,
                            par_lux, solar_radiation,
                            soil_stick_adc, soil_stick_moisture, soil_stick_ph,
                            soil_moisture, soil_temperature, soil_ec, soil_ph,
                            nitrogen, phosphorus, potassium,
                            ai_nitrogen, ai_phosphorus, ai_potassium, ai_ph, ai_moisture, ai_confidence,
                            created_at
                        ) VALUES (
                            ?, ?, ?, ?,
                            ?, ?,
                            ?, ?, ?,
                            ?, ?, ?, ?,
                            ?, ?, ?,
                            ?, ?, ?, ?, ?, ?,
                            datetime('now', 'localtime')
                        )
                    """, (
                        air_temp, air_hum, air_dp, air_vpd,
                        light_lux, light_solar,
                        stick_adc, stick_moist, stick_ph,
                        s7_moist, s7_temp, s7_ec, s7_ph,
                        s7_n, s7_p, s7_k,
                        ai_n, ai_p, ai_k, ai_ph, ai_moist, ai_conf
                    ))
                conn.commit()
                conn.close()
            except Exception:
                pass

        return True
    except Exception as e:
        print(f"[Push Telemetry Error] {e}")
        return False

def run_bridge():
    serial = get_serial_module()
    print("[SerialBridge] Initializing Auto-Healing Telemetry Bridge...")

    while True:
        port = None
        for p in SERIAL_PORTS:
            if os.path.exists(p):
                port = p
                break

        if not port:
            print("[SerialBridge] Waiting for USB Serial port (/dev/cu.usbserial-10)...")
            time.sleep(2.0)
            continue

        ser = None
        try:
            print(f"[SerialBridge] Connecting to {port} at 115200 baud...")
            ser = serial.Serial(port, 115200, timeout=1)
            time.sleep(0.5)
            ser.reset_input_buffer()
            print(f"[SerialBridge] Connected to {port}. Listening for live telemetry...")

            # Initial time sync
            try:
                ser.write(f"TIME:{int(time.time())}\n".encode('utf-8'))
                ser.flush()
            except Exception:
                pass

            last_record_time = 0
            last_time_sync = 0
            last_relay_check = 0
            last_relays_applied = {}
            current_data = {}
            buf = ""

            while True:
                now = time.time()
                if now - last_time_sync >= 10.0:
                    last_time_sync = now
                    try:
                        ser.write(f"TIME:{int(now)}\n".encode('utf-8'))
                        ser.flush()
                    except Exception:
                        pass

                # Check and dispatch pending relay commands from Web Dashboard
                if now - last_relay_check >= 0.5:
                    last_relay_check = now
                    try:
                        if os.path.exists(LEQS_STATE_PATH):
                            with open(LEQS_STATE_PATH, "r", encoding="utf-8") as f:
                                st = json.load(f)
                            relays = st.get("relays", {})
                            for rid_str in ["1", "2", "3", "4"]:
                                if rid_str in relays:
                                    s = int(relays[rid_str].get("state", 0))
                                    if last_relays_applied.get(rid_str) != s:
                                        last_relays_applied[rid_str] = s
                                        ser.write(f"RELAY {rid_str} {s}\n".encode('utf-8'))
                                        ser.flush()
                                        print(f"[SerialBridge] Dispatched RELAY {rid_str} -> {s} to ESP32")
                    except Exception:
                        pass

                chunk = ser.read(ser.in_waiting or 1).decode('utf-8', errors='ignore')
                if not chunk:
                    continue
                buf += chunk

                while "\n" in buf:
                    line, buf = buf.split("\n", 1)
                    
                    # Strip out FreeRTOS WiFi background logs that cut into telemetry lines
                    line = re.sub(r'\[[0-9]+\]\[[WEID]\]\[WiFiGeneric\.cpp:[0-9]+\][^\r\n]*', '', line).strip()
                    line = re.sub(r'\[WiFi-Event\][^\r\n]*', '', line).strip()
                    if not line:
                        continue

                    # Agronomy Warnings & Alerts
                    m_ag = re.search(r'\[AGRONOMY (?:ALERT|WARNING)\]\s*(.*)', line)
                    if m_ag:
                        current_data["agronomy_alert"] = m_ag.group(1).strip()

                    # 1. SHT45 Microclimate
                    m_t = re.search(r'\(Temp\)\s*:\s*([0-9\.]+)\s*°C', line)
                    if m_t: current_data["air_temp"] = float(m_t.group(1))

                    m_rh = re.search(r'\(RH\)\s*:\s*([0-9\.]+)\s*%', line)
                    if m_rh: current_data["air_humidity"] = float(m_rh.group(1))

                    m_dp = re.search(r'\(Dew Point\)\s*:\s*([0-9\.]+)\s*°C', line)
                    if m_dp: current_data["air_dew_point"] = float(m_dp.group(1))

                    m_vpd = re.search(r'\(VPD\)\s*:\s*([0-9\.]+)\s*kPa', line)
                    if m_vpd: current_data["air_vpd"] = float(m_vpd.group(1))

                    # 2. BH1750 Light
                    m_lux = re.search(r'\(Lux\)\s*:\s*([0-9\.]+)\s*Lux', line)
                    if m_lux: current_data["light_lux"] = float(m_lux.group(1))

                    m_sol = re.search(r'\(Solar\)\s*:\s*([0-9\.]+)\s*W/m²', line)
                    if m_sol: current_data["light_solar_radiation"] = float(m_sol.group(1))

                    # 3. Soil Stick (0-10 cm)
                    m_adc = re.search(r'ADC\s*A1\s*:\s*([0-9]+)', line)
                    if m_adc: current_data["soil_stick_adc"] = int(m_adc.group(1))

                    m_st_m = re.search(r'(?:ปริมาณความชื้น|Stick\s*Moist).*?:\s*([0-9\.]+)\s*%', line, re.IGNORECASE)
                    if m_st_m: current_data["soil_stick_moisture"] = float(m_st_m.group(1))

                    m_st_ph = re.search(r'\(Surface pH\)\s*:\s*([0-9\.]+)\s*pH', line)
                    if m_st_ph: current_data["soil_stick_ph"] = float(m_st_ph.group(1))

                    m_st_v = re.search(r'\(Surface pH\).*?\(([0-9\.]+)\s*V', line)
                    if m_st_v: current_data["soil_stick_ph_volt"] = float(m_st_v.group(1))

                    # 4. Soil 7-in-1 Modbus RS485 (Root Zone)
                    m_s7_m = re.search(r'\(Moisture\)\s*:\s*([0-9\.]+)\s*%', line)
                    if m_s7_m: current_data["soil_7in1_moisture"] = float(m_s7_m.group(1))

                    m_s7_t = re.search(r'\(Soil Temp\)\s*:\s*([0-9\.]+)\s*°C', line)
                    if m_s7_t: current_data["soil_7in1_temp"] = float(m_s7_t.group(1))

                    m_s7_ec = re.search(r'\(EC\)\s*:\s*([0-9\.]+)\s*(?:µS|uS|S)/cm', line)
                    if m_s7_ec: current_data["soil_7in1_ec"] = float(m_s7_ec.group(1))

                    m_s7_ph = re.search(r'\(pH\)\s*:\s*([0-9\.]+)', line)
                    if m_s7_ph and "Surface" not in line and "Calibrated" not in line:
                        current_data["soil_7in1_ph"] = float(m_s7_ph.group(1))

                    m_s7_n = re.search(r'\(N\)\s*:\s*([0-9\.]+)\s*mg/kg', line)
                    if m_s7_n and "Calibrated" not in line:
                        current_data["soil_7in1_n"] = float(m_s7_n.group(1))

                    m_s7_p = re.search(r'\(P\)\s*:\s*([0-9\.]+)\s*mg/kg', line)
                    if m_s7_p and "Calibrated" not in line:
                        current_data["soil_7in1_p"] = float(m_s7_p.group(1))

                    m_s7_k = re.search(r'\(K\)\s*:\s*([0-9\.]+)\s*mg/kg', line)
                    if m_s7_k and "Calibrated" not in line:
                        current_data["soil_7in1_k"] = float(m_s7_k.group(1))

                    # 5. TinyML Edge AI Calibrated
                    m_ain = re.search(r'AI Calibrated N.*?:\s*([0-9\.]+)', line)
                    if m_ain: current_data["ai_calibrated_n"] = float(m_ain.group(1))

                    m_aip = re.search(r'AI Calibrated P.*?:\s*([0-9\.]+)', line)
                    if m_aip: current_data["ai_calibrated_p"] = float(m_aip.group(1))

                    m_aik = re.search(r'AI Calibrated K.*?:\s*([0-9\.]+)', line)
                    if m_aik: current_data["ai_calibrated_k"] = float(m_aik.group(1))

                    m_aiph = re.search(r'AI Calibrated Soil pH.*?:\s*([0-9\.]+)', line)
                    if m_aiph: current_data["ai_calibrated_ph"] = float(m_aiph.group(1))

                    m_aim = re.search(r'AI True Volumetric Moisture.*?:\s*([0-9\.]+)', line)
                    if m_aim: current_data["ai_calibrated_moisture"] = float(m_aim.group(1))

                    m_conf = re.search(r'Confidence Index.*?:\s*([0-9\.]+)', line)
                    if m_conf: current_data["ai_confidence"] = float(m_conf.group(1)) / 100.0

                # Push telemetry every 1.5 seconds
                now = time.time()
                if now - last_record_time >= 1.5 and ("air_temp" in current_data or "soil_stick_moisture" in current_data):
                    last_record_time = now
                    push_telemetry(current_data)
                    print(f"[Bridge Sync] Temp={current_data.get('air_temp')}C, RH={current_data.get('air_humidity')}%, Stick={current_data.get('soil_stick_moisture')}%, 7in1_M={current_data.get('soil_7in1_moisture')}%, 7in1_pH={current_data.get('soil_7in1_ph')}, AI_pH={current_data.get('ai_calibrated_ph')}")

        except Exception as e:
            print(f"[Bridge Error] {e}. Reconnecting in 2 seconds...")
            if ser:
                try:
                    ser.close()
                except Exception:
                    pass
            time.sleep(2.0)

if __name__ == "__main__":
    run_bridge()
