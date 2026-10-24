// โมเดลข้อมูลส่วนเสริมของระบบ: ตารางเวลา, บันทึกการใช้งาน, แจ้งเตือน, โปรไฟล์พืช, ประวัติเซนเซอร์
// ฟิลด์ทั้งหมดตรงกับ endpoint ใน leqs-workshop/api/api.php + automation.php

double _d(dynamic v, [double def = 0]) {
  if (v == null) return def;
  if (v is num) return v.toDouble();
  return double.tryParse(v.toString()) ?? def;
}

int _i(dynamic v, [int def = 0]) {
  if (v == null) return def;
  if (v is num) return v.toInt();
  return int.tryParse(v.toString()) ?? def;
}

class ScheduleItem {
  final String id;
  final int relay; // 1-4
  final String time; // HH:MM
  final List<int> days; // 0=จันทร์ ... 6=อาทิตย์
  final int durationMin; // 0 = ไม่ตั้งเวลาปิด
  final bool turnOn;
  final bool enabled;
  final String name;

  const ScheduleItem({
    required this.id,
    required this.relay,
    required this.time,
    required this.days,
    required this.durationMin,
    required this.turnOn,
    required this.enabled,
    required this.name,
  });

  factory ScheduleItem.fromJson(Map<String, dynamic> j) {
    final rawDays = j['days'];
    return ScheduleItem(
      id: j['id']?.toString() ?? '',
      relay: _i(j['relay'], 1),
      time: j['time']?.toString() ?? '06:00',
      days: rawDays is List ? rawDays.map((e) => _i(e)).toList() : <int>[0, 1, 2, 3, 4, 5, 6],
      durationMin: _i(j['duration']),
      turnOn: (j['action']?.toString() ?? 'on') == 'on',
      enabled: j['enabled'] == 1 || j['enabled'] == true,
      name: j['name']?.toString() ?? '',
    );
  }
}

class AuditEntry {
  final String time;
  final String type;
  final String detail;
  final String src;

  const AuditEntry({required this.time, required this.type, required this.detail, required this.src});

  factory AuditEntry.fromJson(Map<String, dynamic> j) => AuditEntry(
        time: j['time']?.toString() ?? '',
        type: j['type']?.toString() ?? '',
        detail: j['detail']?.toString() ?? '',
        src: j['src']?.toString() ?? '',
      );
}

class AlertItem {
  final String level; // critical | warning | info
  final String key;
  final String title;
  final String detail;

  const AlertItem({required this.level, required this.key, required this.title, required this.detail});

  factory AlertItem.fromJson(Map<String, dynamic> j) => AlertItem(
        level: j['level']?.toString() ?? 'info',
        key: j['key']?.toString() ?? '',
        title: j['title']?.toString() ?? '',
        detail: j['detail']?.toString() ?? '',
      );
}

class Range {
  final double min;
  final double max;
  const Range(this.min, this.max);

  double get mid => (min + max) / 2;
  bool contains(double v) => v >= min && v <= max;
}

class PlantProfile {
  final String id;
  final String name;
  final String note;
  final Map<String, Range> thresholds; // temp, humidity, soil_moisture, vpd, soil_ph

  const PlantProfile({required this.id, required this.name, required this.note, required this.thresholds});

  factory PlantProfile.fromJson(Map<String, dynamic> j) {
    final th = <String, Range>{};
    final raw = j['thresholds'];
    if (raw is Map) {
      raw.forEach((k, v) {
        if (v is Map) th[k.toString()] = Range(_d(v['min']), _d(v['max']));
      });
    }
    return PlantProfile(
      id: j['id']?.toString() ?? '',
      name: j['name']?.toString() ?? '',
      note: j['note']?.toString() ?? '',
      thresholds: th,
    );
  }

  /// โปรไฟล์สำรองเมื่อยังโหลดจากเซิร์ฟเวอร์ไม่ได้ (ค่าเดียวกับโปรไฟล์ "ทั่วไป" ของเซิร์ฟเวอร์)
  static const PlantProfile fallback = PlantProfile(
    id: 'general',
    name: 'ทั่วไป',
    note: '',
    thresholds: {
      'temp': Range(20, 35),
      'humidity': Range(40, 90),
      'soil_moisture': Range(30, 80),
      'vpd': Range(0.4, 1.6),
      'soil_ph': Range(5.5, 7.5),
    },
  );
}

class HistoryPoint {
  final String label;
  final double temperature;
  final double humidity;
  final double soilMoisture;
  final double vpd;

  const HistoryPoint({
    required this.label,
    required this.temperature,
    required this.humidity,
    required this.soilMoisture,
    required this.vpd,
  });

  factory HistoryPoint.fromJson(Map<String, dynamic> j) => HistoryPoint(
        label: j['created_at']?.toString() ?? '',
        temperature: _d(j['temperature']),
        humidity: _d(j['humidity']),
        soilMoisture: _d(j['soil_moisture']),
        vpd: _d(j['vpd']),
      );
}

class HistoryResult {
  final List<HistoryPoint> points;
  final bool simulated; // เซิร์ฟเวอร์ยังไม่มีข้อมูลจริงและส่งค่าจำลองมา
  const HistoryResult(this.points, this.simulated);
}
