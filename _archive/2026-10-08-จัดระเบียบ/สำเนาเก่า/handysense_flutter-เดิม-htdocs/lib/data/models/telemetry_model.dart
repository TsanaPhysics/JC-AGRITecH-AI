class SensorData {
  final double temperature;
  final double humidity;
  final double vpd;
  final double dewPoint;
  final double parLux;
  final double solarRadiation;
  final double soilMoisture;
  final double soilPh;
  final double soilEc;
  final double soilTemperature;
  final double nitrogen;
  final double phosphorus;
  final double potassium;
  final double batteryPct;
  final String updatedAt;

  SensorData({
    required this.temperature,
    required this.humidity,
    required this.vpd,
    required this.dewPoint,
    required this.parLux,
    required this.solarRadiation,
    required this.soilMoisture,
    required this.soilPh,
    required this.soilEc,
    required this.soilTemperature,
    required this.nitrogen,
    required this.phosphorus,
    required this.potassium,
    required this.batteryPct,
    required this.updatedAt,
  });

  factory SensorData.fromJson(Map<String, dynamic> json) {
    double toDbl(dynamic val, [double def = 0.0]) {
      if (val == null) return def;
      if (val is num) return val.toDouble();
      return double.tryParse(val.toString()) ?? def;
    }

    return SensorData(
      temperature: toDbl(json['temperature'], 31.4),
      humidity: toDbl(json['humidity'], 85.0),
      vpd: toDbl(json['vpd'], 0.69),
      dewPoint: toDbl(json['dew_point'], 28.4),
      parLux: toDbl(json['par_lux'], 897.5),
      solarRadiation: toDbl(json['solar_radiation'], 7.09),
      soilMoisture: toDbl(json['soil_moisture'], 65.0),
      soilPh: toDbl(json['soil_ph'], 6.2),
      soilEc: toDbl(json['soil_ec'], 120.0),
      soilTemperature: toDbl(json['soil_temperature'], 27.5),
      nitrogen: toDbl(json['nitrogen'], 45.0),
      phosphorus: toDbl(json['phosphorus'], 32.0),
      potassium: toDbl(json['potassium'], 180.0),
      batteryPct: toDbl(json['battery_pct'], 98.5),
      updatedAt: json['updated_at']?.toString() ?? '',
    );
  }
}

class RelayItem {
  final int id;
  final String name;
  final bool state;
  final int gpio;

  RelayItem({
    required this.id,
    required this.name,
    required this.state,
    required this.gpio,
  });

  factory RelayItem.fromJson(Map<String, dynamic> json, int defaultId, int defaultGpio, String defaultName) {
    final rawState = json['state'];
    final bool isStateOn = (rawState == 1 || rawState == true || rawState == '1');
    return RelayItem(
      id: json['id'] is num ? (json['id'] as num).toInt() : defaultId,
      name: json['name']?.toString() ?? defaultName,
      state: isStateOn,
      gpio: json['gpio'] is num ? (json['gpio'] as num).toInt() : defaultGpio,
    );
  }

  RelayItem copyWith({bool? state}) {
    return RelayItem(
      id: id,
      name: name,
      state: state ?? this.state,
      gpio: gpio,
    );
  }
}

class BoardData {
  final String deviceName;
  final String status;
  final String ssid;
  final String ipAddress;
  final int webPort;
  final int rssi;
  final String cloudUrl;
  final bool isLive;

  BoardData({
    required this.deviceName,
    required this.status,
    required this.ssid,
    required this.ipAddress,
    required this.webPort,
    required this.rssi,
    required this.cloudUrl,
    required this.isLive,
  });

  factory BoardData.fromJson(Map<String, dynamic> json) {
    return BoardData(
      deviceName: json['device_name']?.toString() ?? 'ESP32-S3 ATD3.5',
      status: json['status']?.toString() ?? 'ONLINE',
      ssid: json['ssid']?.toString() ?? 'JC_Home',
      ipAddress: json['ip_address']?.toString() ?? '192.168.0.111',
      webPort: (json['web_port'] is num) ? (json['web_port'] as num).toInt() : 8500,
      rssi: (json['rssi'] is num) ? (json['rssi'] as num).toInt() : -99,
      cloudUrl: json['cloud_url']?.toString() ?? 'http://14.207.141.164:8000',
      isLive: json['is_live'] == true || json['status']?.toString().contains('ONLINE') == true,
    );
  }
}

class AiCalibratedData {
  final double nitrogen;
  final double phosphorus;
  final double potassium;
  final double ph;
  final double moisture;
  final double confidence;
  final String npkRatio;
  final double npkTotal;

  AiCalibratedData({
    required this.nitrogen,
    required this.phosphorus,
    required this.potassium,
    required this.ph,
    required this.moisture,
    required this.confidence,
    required this.npkRatio,
    required this.npkTotal,
  });

  factory AiCalibratedData.fromJson(Map<String, dynamic> json) {
    double toDbl(dynamic val, [double def = 0.0]) {
      if (val == null) return def;
      if (val is num) return val.toDouble();
      return double.tryParse(val.toString()) ?? def;
    }

    return AiCalibratedData(
      nitrogen: toDbl(json['nitrogen'], 48.2),
      phosphorus: toDbl(json['phosphorus'], 33.1),
      potassium: toDbl(json['potassium'], 178.5),
      ph: toDbl(json['ph'], 6.2),
      moisture: toDbl(json['moisture'], 65.4),
      confidence: toDbl(json['confidence'], 0.984),
      npkRatio: json['npk_ratio']?.toString() ?? '1.5:1:5.6',
      npkTotal: toDbl(json['npk_total'], 259.8),
    );
  }
}

class GpsData {
  final double latitude;
  final double longitude;
  final String formatted;
  final String locationName;
  final String province;

  GpsData({
    required this.latitude,
    required this.longitude,
    required this.formatted,
    required this.locationName,
    required this.province,
  });

  factory GpsData.fromJson(Map<String, dynamic> json) {
    return GpsData(
      latitude: (json['latitude'] is num) ? (json['latitude'] as num).toDouble() : 12.6644,
      longitude: (json['longitude'] is num) ? (json['longitude'] as num).toDouble() : 102.1039,
      formatted: json['formatted']?.toString() ?? '12.6644° N, 102.1039° E',
      locationName: json['location_name']?.toString() ?? 'คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี (RBRU)',
      province: json['province']?.toString() ?? 'จันทบุรี (Chanthaburi)',
    );
  }
}

class TelemetryModel {
  final BoardData board;
  final SensorData sensors;
  final Map<int, RelayItem> relays;
  final bool autoMode;
  final String controlMode; // 'manual', 'auto', 'ai'
  final AiCalibratedData aiCalibrated;
  final GpsData gps;

  TelemetryModel({
    required this.board,
    required this.sensors,
    required this.relays,
    required this.autoMode,
    required this.controlMode,
    required this.aiCalibrated,
    required this.gps,
  });

  factory TelemetryModel.fromJson(Map<String, dynamic> json) {
    final relaysMap = <int, RelayItem>{};
    final rawRelays = json['relays'];

    if (rawRelays is Map) {
      final defaultDefs = {
        1: {'gpio': 39, 'name': 'ปั๊มน้ำหลัก (Main Pump 1)'},
        2: {'gpio': 38, 'name': 'วาล์วน้ำโซลินอยด์/ปั๊ม 2 (Solenoid/Pump 2)'},
        3: {'gpio': 7, 'name': 'วาล์วน้ำผิวดิน (Surface Valve)'},
        4: {'gpio': 6, 'name': 'ระบบพ่นหมอกลดอุณหภูมิ (Misting System)'},
      };

      for (int i = 1; i <= 4; i++) {
        final keyStr = i.toString();
        if (rawRelays.containsKey(keyStr) && rawRelays[keyStr] is Map) {
          relaysMap[i] = RelayItem.fromJson(
            Map<String, dynamic>.from(rawRelays[keyStr] as Map),
            i,
            defaultDefs[i]!['gpio'] as int,
            defaultDefs[i]!['name'] as String,
          );
        } else {
          relaysMap[i] = RelayItem(
            id: i,
            name: defaultDefs[i]!['name'] as String,
            state: false,
            gpio: defaultDefs[i]!['gpio'] as int,
          );
        }
      }
    }

    return TelemetryModel(
      board: BoardData.fromJson(Map<String, dynamic>.from(json['board'] as Map? ?? {})),
      sensors: SensorData.fromJson(Map<String, dynamic>.from(json['sensors'] as Map? ?? {})),
      relays: relaysMap,
      autoMode: json['auto_mode'] == true,
      controlMode: (json['control_mode']?.toString() ?? (json['auto_mode'] == true ? 'auto' : 'manual')).toLowerCase(),
      aiCalibrated: AiCalibratedData.fromJson(Map<String, dynamic>.from(json['ai_calibrated'] as Map? ?? {})),
      gps: GpsData.fromJson(Map<String, dynamic>.from(json['gps'] as Map? ?? {})),
    );
  }
}
