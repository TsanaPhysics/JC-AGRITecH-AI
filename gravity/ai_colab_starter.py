"""
================================================================================
JC-AgriTech + AI: Agricultural Deep Learning Quickstart
Script สำหรับรันบน Google Colab หรือ Jupyter Notebook
ดึงข้อมูล Time-Series จาก Firebase Realtime Database เพื่อสร้างโมเดล Deep Learning (PyTorch LSTM)
================================================================================
"""

import requests
import json
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# ==============================================================================
# 1. กำหนดค่า Firebase Realtime Database
# ==============================================================================
# ใส่ URL ฐานข้อมูล Firebase ของคุณ (เช่น "https://your-project-default-rtdb.asia-southeast1.firebasedatabase.app")
FIREBASE_DATABASE_URL = "https://your-project-default-rtdb.asia-southeast1.firebasedatabase.app"

def fetch_telemetry_dataset(db_url):
    """ดึงข้อมูล Time-Series ทั้งหมดจาก Firebase เข้าสู่ Pandas DataFrame"""
    endpoint = f"{db_url.rstrip('/')}/telemetry.json"
    print(f"[*] Fetching telemetry data from: {endpoint} ...")
    
    response = requests.get(endpoint)
    if response.status_code != 200:
        raise Exception(f"Failed to fetch data: HTTP {response.status_code}")
    
    raw_data = response.json()
    if not raw_data:
        print("[!] No data found in Firebase yet.")
        return pd.DataFrame()
    
    # แปลงโครงสร้าง Nested JSON เป็นตารางแบน (Flattened DataFrame)
    records = []
    for key, val in raw_data.items():
        record = {
            "record_id": key,
            "timestamp": val.get("timestamp"),
            "datetime": val.get("datetime"),
            # อากาศ SHT45
            "air_temp": val.get("air", {}).get("temperature"),
            "air_humidity": val.get("air", {}).get("humidity"),
            "air_dew_point": val.get("air", {}).get("dew_point"),
            "air_vpd": val.get("air", {}).get("vpd"),
            # แสง โดมตะวัน
            "light_lux": val.get("light", {}).get("lux"),
            "light_solar_radiation": val.get("light", {}).get("solar_radiation"),
            # ดิน Soil Stick (ผิวดิน)
            "soil_stick_adc": val.get("soil_stick", {}).get("adc_raw"),
            "soil_stick_moisture": val.get("soil_stick", {}).get("moisture_percent"),
            # ดิน 7-in-1 (เขตรากพืช)
            "soil_7in1_moisture": val.get("soil_7in1", {}).get("moisture_percent"),
            "soil_7in1_temp": val.get("soil_7in1", {}).get("temperature"),
            "soil_7in1_ec": val.get("soil_7in1", {}).get("ec"),
            "soil_7in1_ph": val.get("soil_7in1", {}).get("ph"),
            "soil_7in1_n": val.get("soil_7in1", {}).get("nitrogen"),
            "soil_7in1_p": val.get("soil_7in1", {}).get("phosphorus"),
            "soil_7in1_k": val.get("soil_7in1", {}).get("potassium"),
            # สถานะอุปกรณ์รีเลย์
            "actuator_pump": 1 if val.get("actuators", {}).get("pump") else 0,
            "actuator_misting": 1 if val.get("actuators", {}).get("misting") else 0,
        }
        records.append(record)
    
    df = pd.DataFrame(records)
    df["datetime"] = pd.to_datetime(df["datetime"])
    df = df.sort_values("timestamp").reset_index(drop=True)
    print(f"[+] Loaded {len(df)} records successfully!")
    return df

# ==============================================================================
# 2. ตัวอย่างการสร้างโมเดล Deep Learning (PyTorch LSTM) พยากรณ์ความชื้นในดิน
# ==============================================================================
import torch
import torch.nn as nn
from torch.utils.data import Dataset, DataLoader
from sklearn.preprocessing import MinMaxScaler

class AgriTimeSeriesDataset(Dataset):
    """สร้าง Windowed Dataset สำหรับ Time-Series Forecasting"""
    def __init__(self, data, lookback=12, horizon=1):
        self.X = []
        self.y = []
        for i in range(len(data) - lookback - horizon + 1):
            self.X.append(data[i : i + lookback, :])
            # พยากรณ์ค่าความชื้นในดินล่วงหน้า (สมมติว่าเป็นคอลัมน์แรก)
            self.y.append(data[i + lookback + horizon - 1, 0])
        self.X = torch.tensor(np.array(self.X), dtype=torch.float32)
        self.y = torch.tensor(np.array(self.y), dtype=torch.float32).unsqueeze(-1)
        
    def __len__(self):
        return len(self.X)
    
    def __getitem__(self, idx):
        return self.X[idx], self.y[idx]

class AgriLSTMForecaster(nn.Module):
    """โครงข่ายประสาทเทียม LSTM สำหรับการทำนายแนวโน้มสภาวะแปลงล่วงหน้า"""
    def __init__(self, input_dim, hidden_dim=64, num_layers=2):
        super().__init__()
        self.lstm = nn.LSTM(input_dim, hidden_dim, num_layers, batch_first=True, dropout=0.2)
        self.fc = nn.Sequential(
            nn.Linear(hidden_dim, 32),
            nn.ReLU(),
            nn.Linear(32, 1)
        )
        
    def forward(self, x):
        out, _ = self.lstm(x)
        out = self.fc(out[:, -1, :]) # ดึง Output ของ Step สุดท้ายมาทำนาย
        return out

if __name__ == "__main__":
    print("=== JC-AgriTech AI Starter Kit ===")
    print("1. ใส่ URL ฐานข้อมูล Firebase ของคุณในตัวแปร FIREBASE_DATABASE_URL")
    print("2. รันคำสั่ง fetch_telemetry_dataset(FIREBASE_DATABASE_URL) เพื่อดึง DataFrame")
    print("3. นำ DataFrame ไปเข้ากระบวนการเทรนโมเดล LSTM ใน PyTorch ได้ทันที")
