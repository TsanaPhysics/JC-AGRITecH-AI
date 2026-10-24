# LEQs AgriSci xAI — Health Step Tracker ESP32

## 🔌 วงจรต่อสาย MPU6050

| MPU6050 | ESP32-S3 (ATD3.5-S3) | ESP32 DevKit |
|---------|----------------------|--------------|
| VCC     | 3.3V                 | 3.3V         |
| GND     | GND                  | GND          |
| SDA     | GPIO8                | GPIO21       |
| SCL     | GPIO9                | GPIO22       |
| AD0     | GND (addr=0x68)      | GND (0x68)   |
| INT     | ไม่ต้องต่อ           | ไม่ต้องต่อ   |

### อุปกรณ์เพิ่มเติม (ทางเลือก)
| อุปกรณ์    | GPIO            | หมายเหตุ                      |
|------------|-----------------|-------------------------------|
| Buzzer     | GPIO5           | Active Buzzer (เตือน Sedentary) |
| LED        | GPIO2           | แสดงสถานะกิจกรรม              |

## 🚀 วิธีใช้งาน

### 1. ตั้งค่า Wi-Fi ใน `include/Config.h`
```cpp
#define WIFI_SSID     "ชื่อ Wi-Fi ของคุณ"
#define WIFI_PASSWORD "รหัสผ่าน Wi-Fi ของคุณ"
```

### 2. Build & Upload ด้วย PlatformIO
```bash
# ESP32-S3 (ATD3.5-S3)
pio run -e esp32-s3-atd35 -t upload

# ESP32 DevKit ทั่วไป
pio run -e esp32-devkit -t upload

# เปิด Serial Monitor
pio device monitor -b 115200
```

### 3. API Endpoints (Flutter App ดึงข้อมูล)
```
GET http://<ESP32_IP>:8080/health       → ข้อมูลสุขภาพ JSON
GET http://<ESP32_IP>:8080/reset        → รีเซ็ตทุกค่าเป็น 0
GET http://<ESP32_IP>:8080/set_weight?kg=65 → ตั้งค่าน้ำหนัก
```

### 4. ตัวอย่าง JSON Response
```json
{
  "steps": 1247,
  "calories": "58.3",
  "distance_km": "0.94",
  "activity": "walking",
  "accel_magnitude": "1.023",
  "sedentary_min": 5,
  "uptime_min": 23,
  "weight_kg": 60.0
}
```

## 📐 Algorithm

### Step Detection (Peak Detector)
```
Raw MPU6050 → Low-pass Filter → Peak Detection → Debounce → Step Count
     ↓             (α=0.3)         (>1.3g high,      (>300ms)
  |A|=√(ax²+ay²+az²)              <0.8g low)
```

### Calorie Formula (MET-based)
```
kcal = MET × weight_kg × steps × (1/100) × 0.9
MET: Walking=3.5, Running=9.8
```

## 🔗 Flutter Integration
ใน `movement_sensor_service.dart` เพิ่ม:
```dart
Future<void> fetchFromESP32(String ip) async {
  final response = await http.get(Uri.parse('http://$ip:8080/health'));
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    // อัปเดต steps, calories ฯลฯ
  }
}
```
