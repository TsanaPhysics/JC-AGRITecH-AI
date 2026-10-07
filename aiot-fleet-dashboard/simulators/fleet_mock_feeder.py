#!/usr/bin/env python3
"""
============================================================================
AIoT Multi-Board Fleet Telemetry Feeder / Hardware Simulator
============================================================================
Simulates continuous telemetry streams from multiple ESP32 field boards
pushing real-time sensor metrics and TinyML Edge AI inferences to the Fleet API.
"""

import time
import json
import random
import math
import urllib.request

API_URL = "http://localhost/handysense/aiot-fleet-dashboard/api/index.php"

def push_board_telemetry(board_id, code, base_temp, base_hum, base_ph, base_moist):
    # Add realistic micro-fluctuations
    t_sec = time.time()
    temp_noise = 0.4 * math.sin(t_sec / 15.0) + random.uniform(-0.1, 0.1)
    hum_noise = 0.8 * math.cos(t_sec / 20.0) + random.uniform(-0.2, 0.2)
    moist_noise = random.uniform(-0.1, 0.1)
    ph_noise = random.uniform(-0.03, 0.03)

    air_temp = round(base_temp + temp_noise, 2)
    air_hum = round(base_hum + hum_noise, 2)
    soil_ph = round(base_ph + ph_noise, 2)
    soil_moist = round(max(1.0, min(99.0, base_moist + moist_noise)), 2)

    # Compute VPD (FAO-56)
    vpsat = 0.61078 * math.exp((17.27 * air_temp) / (air_temp + 237.3))
    vpact = vpsat * (air_hum / 100.0)
    vpd = round(max(0.1, vpsat - vpact), 3)

    # TinyML AI Model Calibration
    ai_ph = round(soil_ph + 0.94 + random.uniform(-0.02, 0.02), 2)
    ai_n = round(21.8 + random.uniform(-0.5, 0.5), 1)
    ai_p = round(13.9 + random.uniform(-0.3, 0.3), 1)
    ai_k = round(8.0 + random.uniform(-0.2, 0.2), 1)
    ai_conf = round(77.5 + random.uniform(-0.5, 0.5), 1)

    payload = {
        "board_id": board_id,
        "telemetry": {
            "air_temp": air_temp,
            "air_hum": air_hum,
            "soil_ph": soil_ph,
            "soil_moisture": soil_moist,
            "soil_ec": round(0.42 + random.uniform(-0.02, 0.02), 2),
            "soil_temp": round(air_temp - 0.8, 2),
            "surface_ph": round(3.03 + random.uniform(-0.05, 0.05), 2),
            "surface_moist": round(20.5 + random.uniform(-0.3, 0.3), 1),
            "lux": int(34000 + 2000 * math.sin(t_sec / 30.0) + random.randint(-200, 200)),
            "solar_radiation": round(270.0 + 20.0 * math.sin(t_sec / 30.0), 1),
            "vpd": vpd,
            "ai_calibrated_ph": ai_ph,
            "ai_nitrogen": ai_n,
            "ai_phosphorus": ai_p,
            "ai_potassium": ai_k,
            "ai_confidence": ai_conf,
            "rssi": random.randint(-72, -62)
        }
    }

    req = urllib.request.Request(
        f"{API_URL}?action=push_telemetry",
        data=json.dumps(payload).encode("utf-8"),
        headers={"Content-Type": "application/json"}
    )
    try:
        with urllib.request.urlopen(req, timeout=3) as resp:
            return json.loads(resp.read().decode("utf-8"))
    except Exception as e:
        return {"status": "error", "message": str(e)}

def run_once():
    boards_config = [
        {"id": 1, "code": "ESP32-NODE-01", "temp": 28.4, "hum": 65.2, "ph": 8.50, "moist": 2.6},
        {"id": 2, "code": "ESP32-NODE-02", "temp": 28.6, "hum": 78.4, "ph": 6.40, "moist": 55.4},
        {"id": 3, "code": "ESP32-NODE-03", "temp": 26.2, "hum": 84.1, "ph": 5.80, "moist": 88.0},
        {"id": 4, "code": "ESP32-NODE-04", "temp": 29.1, "hum": 62.0, "ph": 6.20, "moist": 32.5}
    ]
    for b in boards_config:
        res = push_board_telemetry(b["id"], b["code"], b["temp"], b["hum"], b["ph"], b["moist"])
        print(f"[{b['code']}] Pushed: {res.get('status')}")

if __name__ == "__main__":
    run_once()
