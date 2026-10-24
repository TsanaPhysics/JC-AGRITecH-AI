import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../core/constants/api_constants.dart';
import '../models/farm_models.dart';
import '../models/telemetry_model.dart';

/// ผลลัพธ์ของคำสั่งที่ส่งไปเซิร์ฟเวอร์
class CommandResult {
  final bool ok;
  final String message;
  final Map<String, dynamic>? data;
  const CommandResult(this.ok, this.message, {this.data});
}

class ApiService {
  String _boardIp = ApiConstants.defaultBoardIp;
  int _boardPort = ApiConstants.defaultBoardPort;
  String _localApiUrl = ApiConstants.defaultLocalApiUrl;
  String _apiKey = '';

  ApiService({String? boardIp, int? boardPort, String? localApiUrl}) {
    if (boardIp != null) _boardIp = boardIp;
    if (boardPort != null) _boardPort = boardPort;
    if (localApiUrl != null) _localApiUrl = localApiUrl;
  }

  void updateConfig({required String boardIp, required int boardPort, required String localApiUrl, String? apiKey}) {
    _boardIp = boardIp;
    _boardPort = boardPort;
    _localApiUrl = localApiUrl;
    if (apiKey != null) _apiKey = apiKey;
  }

  /// header สำหรับคำสั่งที่เปลี่ยนแปลงข้อมูล (เซิร์ฟเวอร์ตรวจเมื่อมีไฟล์ data/api_key.txt)
  Map<String, String> get _headers => _apiKey.isEmpty ? const {} : {'X-Api-Key': _apiKey};

  String get directBoardBaseUrl => 'http://$_boardIp:$_boardPort';

  /// ยิงตรงเข้าบอร์ดเฉพาะเมื่อใช้ API แบบ HTTP ในวง LAN เดียวกัน
  /// ถ้าใช้เซิร์ฟเวอร์สาธารณะ (HTTPS) บอร์ดอาจอยู่คนละเครือข่าย (เช่น ฮอตสปอต) จึงข้ามเพื่อไม่ให้เสียเวลารอ timeout
  bool get _useDirectBoard => !_localApiUrl.startsWith('https://');
  String get localApiUrl => _localApiUrl;

  /// Fetches complete telemetry from local backend API or directly from ESP32
  Future<TelemetryModel> fetchTelemetry() async {
    // 1. Try PHP Central API first (rich database + complete sensor telemetry)
    try {
      final uri = Uri.parse('$_localApiUrl?action=get_telemetry');
      final response = await http.get(uri).timeout(const Duration(seconds: 5));
      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        if (data['status'] == 'success') {
          return TelemetryModel.fromJson(data);
        }
      }
    } catch (_) {}

    // 2. Auto-fallback across LAN candidate URLs
    for (final candidate in ApiConstants.fallbackCandidateUrls) {
      if (candidate == _localApiUrl) continue;
      try {
        final uri = Uri.parse(candidate.contains('?') ? candidate : '$candidate?action=get_telemetry');
        final response = await http.get(uri).timeout(const Duration(milliseconds: 1500));
        if (response.statusCode == 200) {
          final Map<String, dynamic> data = jsonDecode(response.body);
          if (data['status'] == 'success') {
            // ไม่สลับจากเซิร์ฟเวอร์สาธารณะ (HTTPS) ไปเป็น URL วง LAN เพื่อไม่ให้คำสั่งไปผิดเซิร์ฟเวอร์
            if (!candidate.contains('aiot-fleet-dashboard') && !_localApiUrl.startsWith('https://')) {
              _localApiUrl = candidate;
            }
            return TelemetryModel.fromJson(data);
          }
        }
      } catch (_) {}
    }

    // 3. Fallback to localhost (for testing on desktop/emulator)
    try {
      final fallbackUri = Uri.parse('${ApiConstants.fallbackLocalhostUrl}?action=get_telemetry');
      final response = await http.get(fallbackUri).timeout(const Duration(milliseconds: 1500));
      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        if (data['status'] == 'success') {
          return TelemetryModel.fromJson(data);
        }
      }
    } catch (_) {}

    // 4. Direct ESP32 Board fallback query
    try {
      final directUri = Uri.parse('$directBoardBaseUrl/status');
      final response = await http.get(directUri).timeout(const Duration(milliseconds: 1500));
      if (response.statusCode == 200) {
        final Map<String, dynamic> boardStatus = jsonDecode(response.body);
        return TelemetryModel.fromJson({
          'status': 'success',
          'board': {
            'device_name': 'ESP32-S3 Edge xAI : LEQs-4D-AgriEnvi',
            'ip_address': _boardIp,
            'web_port': _boardPort,
            'status': 'CONNECTED (DIRECT BOARD)',
            'is_live': true,
          },
          'sensors': {},
          'relays': {
            '1': {'id': 1, 'state': (boardStatus['relays']?['1'] == 1), 'gpio': 39, 'name': 'ปั๊มน้ำหลัก (Main Pump 1)'},
            '2': {'id': 2, 'state': (boardStatus['relays']?['2'] == 1), 'gpio': 38, 'name': 'วาล์วน้ำโซลินอยด์/ปั๊ม 2'},
            '3': {'id': 3, 'state': (boardStatus['relays']?['3'] == 1), 'gpio': 7, 'name': 'วาล์วน้ำผิวดิน (Surface Valve)'},
            '4': {'id': 4, 'state': (boardStatus['relays']?['4'] == 1), 'gpio': 6, 'name': 'ระบบพ่นหมอกลดอุณหภูมิ'},
          },
          'auto_mode': boardStatus['mode'] == 'AUTO',
          'control_mode': boardStatus['mode']?.toString().toLowerCase() ?? 'manual',
          'ai_calibrated': {},
          'gps': {},
        });
      }
    } catch (_) {}

    throw Exception('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์หรือบอร์ด ESP32 ได้ กรุณาตรวจสอบ IP หรือเครือข่าย Wi-Fi');
  }

  /// ส่งคำสั่งรีเลย์ผ่านเซิร์ฟเวอร์ (ข้ามเครือข่ายได้) และยิงตรงเข้าบอร์ดเฉพาะเมื่ออยู่วง LAN เดียวกัน
  /// durationMin > 0 = ปิดอัตโนมัติเมื่อครบเวลา (ใช้กับคำสั่งเปิดเท่านั้น)
  Future<CommandResult> controlRelay(int relayId, bool state, {int durationMin = 0}) async {
    final int stateInt = state ? 1 : 0;
    bool directOk = false;

    if (_useDirectBoard) {
      try {
        final directUri = Uri.parse('$directBoardBaseUrl/relay?id=$relayId&state=$stateInt');
        final res = await http.get(directUri).timeout(const Duration(milliseconds: 1200));
        if (res.statusCode == 200) directOk = true;
      } catch (_) {}
    }

    final dur = (state && durationMin > 0) ? '&duration=$durationMin' : '';
    final r = await _call('control_relay&id=$relayId&state=$stateInt$dur&src=app');
    
    // บันทึก audit ประจำเครื่อง
    addLocalAudit('relay', 'สั่ง${state ? "เปิด" : "ปิด"}รีเลย์ $relayId${(state && durationMin > 0) ? " (ปิดใน $durationMin นาที)" : ""}', 'app');

    if (!r.ok && directOk) return const CommandResult(true, 'สั่งบอร์ดโดยตรงสำเร็จ แต่เซิร์ฟเวอร์ไม่ตอบ');
    return r;
  }

  /// เปลี่ยนโหมด: 'manual', 'auto' หรือ 'ai'
  Future<CommandResult> setControlMode(String mode) async {
    final cleanMode = mode.toLowerCase();
    bool directOk = false;

    if (_useDirectBoard) {
      try {
        final directUri = Uri.parse('$directBoardBaseUrl/mode?mode=$cleanMode');
        final res = await http.get(directUri).timeout(const Duration(milliseconds: 1200));
        if (res.statusCode == 200) directOk = true;
      } catch (_) {}
    }

    final r = await _call('set_control_mode&mode=$cleanMode&src=app');
    addLocalAudit('mode', 'เปลี่ยนโหมดเป็น $cleanMode', 'app');

    if (!r.ok && directOk) return const CommandResult(true, 'สั่งบอร์ดโดยตรงสำเร็จ แต่เซิร์ฟเวอร์ไม่ตอบ');
    return r;
  }

  /// เรียก endpoint ของเซิร์ฟเวอร์ (query = ส่วนหลัง action=) แล้วตรวจผลลัพธ์
  Future<CommandResult> _call(String query, {Map<String, dynamic>? body}) async {
    try {
      final uri = Uri.parse('$_localApiUrl?action=$query');
      final http.Response res;
      if (body == null) {
        res = await http.get(uri, headers: _headers).timeout(const Duration(seconds: 8));
      } else {
        res = await http
            .post(uri, headers: {..._headers, 'Content-Type': 'application/json'}, body: jsonEncode(body))
            .timeout(const Duration(seconds: 8));
      }
      Map<String, dynamic>? j;
      try {
        j = jsonDecode(res.body) as Map<String, dynamic>;
      } catch (_) {}
      if (res.statusCode == 401) return const CommandResult(false, 'API Key ไม่ถูกต้อง — ตรวจสอบที่หน้าตั้งค่า');
      if (res.statusCode != 200) return CommandResult(false, 'เซิร์ฟเวอร์ตอบ HTTP ${res.statusCode}');
      if (j == null) return const CommandResult(false, 'เซิร์ฟเวอร์ตอบข้อมูลที่อ่านไม่ได้');
      if (j['status'] == 'success') return CommandResult(true, j['message']?.toString() ?? 'สำเร็จ', data: j);
      return CommandResult(false, j['message']?.toString() ?? 'เซิร์ฟเวอร์ปฏิเสธคำสั่ง', data: j);
    } on TimeoutException {
      return const CommandResult(false, 'หมดเวลารอเซิร์ฟเวอร์');
    } catch (_) {
      return const CommandResult(false, 'เชื่อมต่อเซิร์ฟเวอร์ไม่ได้');
    }
  }

  // ── ตารางเวลา (พร้อมระบบสำรองในเครื่องกรณีเซิร์ฟเวอร์ยังไม่เปิดฟังก์ชัน) ──
  Future<List<ScheduleItem>> getSchedules() async {
    try {
      final r = await _call('get_schedules');
      final list = r.data?['schedules'];
      if (r.ok && list is List) {
        final items = list.whereType<Map>().map((e) => ScheduleItem.fromJson(Map<String, dynamic>.from(e))).toList();
        await _saveLocalSchedules(items);
        return items;
      }
    } catch (_) {}
    return _loadLocalSchedules();
  }

  Future<CommandResult> saveSchedule(Map<String, dynamic> s) async {
    bool serverOk = false;
    String msg = 'บันทึกตารางเวลาเรียบร้อยแล้ว';
    try {
      final q = StringBuffer('save_schedule');
      s.forEach((k, v) {
        if (v == null) return;
        final val = v is List ? v.join(',') : v.toString();
        q.write('&$k=${Uri.encodeQueryComponent(val)}');
      });
      q.write('&src=app');
      final r = await _call(q.toString());
      if (r.ok) {
        serverOk = true;
        msg = r.message;
      }
    } catch (_) {}

    final localList = await _loadLocalSchedules();
    final id = s['id']?.toString() ?? 'sch_${DateTime.now().millisecondsSinceEpoch}';
    final updated = Map<String, dynamic>.from(s)..['id'] = id;
    final item = ScheduleItem.fromJson(updated);
    final idx = localList.indexWhere((e) => e.id == id);
    if (idx >= 0) {
      localList[idx] = item;
    } else {
      localList.add(item);
    }
    await _saveLocalSchedules(localList);
    addLocalAudit('schedule_save', 'บันทึกตารางเวลารีเลย์ ${item.relay} เวลา ${item.time}', 'app');

    return CommandResult(true, serverOk ? msg : 'บันทึกตารางเวลาในเครื่องแล้ว');
  }

  Future<CommandResult> deleteSchedule(String id) async {
    bool serverOk = false;
    try {
      final r = await _call('delete_schedule&id=${Uri.encodeQueryComponent(id)}&src=app');
      if (r.ok) serverOk = true;
    } catch (_) {}

    final localList = await _loadLocalSchedules();
    localList.removeWhere((e) => e.id == id);
    await _saveLocalSchedules(localList);
    addLocalAudit('schedule_delete', 'ลบตารางเวลา ID $id', 'app');

    return CommandResult(true, serverOk ? 'ลบตารางเวลาสำเร็จ' : 'ลบตารางเวลาจากเครื่องแล้ว');
  }

  Future<List<ScheduleItem>> _loadLocalSchedules() async {
    try {
      final p = await SharedPreferences.getInstance();
      final str = p.getString('local_schedules');
      if (str != null && str.isNotEmpty) {
        final List raw = jsonDecode(str);
        return raw.map((e) => ScheduleItem.fromJson(Map<String, dynamic>.from(e))).toList();
      }
    } catch (_) {}
    return const [];
  }

  Future<void> _saveLocalSchedules(List<ScheduleItem> list) async {
    try {
      final p = await SharedPreferences.getInstance();
      final jsonList = list.map((e) => {
        'id': e.id,
        'relay': e.relay,
        'time': e.time,
        'days': e.days,
        'duration': e.durationMin,
        'action': e.turnOn ? 'on' : 'off',
        'enabled': e.enabled ? 1 : 0,
        'name': e.name,
      }).toList();
      await p.setString('local_schedules', jsonEncode(jsonList));
    } catch (_) {}
  }

  // ── บันทึกการใช้งาน / แจ้งเตือน / โปรไฟล์ / ประวัติ ──────────
  Future<List<AuditEntry>> getAudit({int limit = 80}) async {
    try {
      final r = await _call('get_audit&limit=$limit');
      final list = r.data?['log'];
      if (r.ok && list is List) {
        return list.whereType<Map>().map((e) => AuditEntry.fromJson(Map<String, dynamic>.from(e))).toList();
      }
    } catch (_) {}
    return _loadLocalAudit(limit: limit);
  }

  Future<List<AuditEntry>> _loadLocalAudit({int limit = 80}) async {
    try {
      final p = await SharedPreferences.getInstance();
      final str = p.getString('local_audit_log');
      if (str != null && str.isNotEmpty) {
        final List raw = jsonDecode(str);
        final list = raw.map((e) => AuditEntry.fromJson(Map<String, dynamic>.from(e))).toList();
        return list.take(limit).toList();
      }
    } catch (_) {}
    return [
      AuditEntry(
        time: DateTime.now().toString().substring(0, 19),
        type: 'system',
        detail: 'เริ่มต้นแอปพลิเคชัน HandySense LEQs-xAI',
        src: 'app',
      ),
    ];
  }

  Future<void> addLocalAudit(String type, String detail, String src) async {
    try {
      final p = await SharedPreferences.getInstance();
      final str = p.getString('local_audit_log');
      final List raw = (str != null && str.isNotEmpty) ? jsonDecode(str) : [];
      final nowStr = DateTime.now().toString().substring(0, 19);
      raw.insert(0, {'time': nowStr, 'type': type, 'detail': detail, 'src': src});
      if (raw.length > 100) raw.removeRange(100, raw.length);
      await p.setString('local_audit_log', jsonEncode(raw));
    } catch (_) {}
  }

  Future<List<AlertItem>> getAlerts() async {
    try {
      final r = await _call('get_alerts');
      final list = r.data?['alerts'];
      if (r.ok && list is List) {
        return list.whereType<Map>().map((e) => AlertItem.fromJson(Map<String, dynamic>.from(e))).toList();
      }
    } catch (_) {}
    return const [];
  }

  /// คืน (id ที่ใช้งานอยู่, โปรไฟล์ทั้งหมด)
  Future<(String, List<PlantProfile>)> getProfiles() async {
    try {
      final r = await _call('get_profiles');
      final raw = r.data?['profiles'];
      if (r.ok && raw is Map) {
        final list = raw.values.whereType<Map>().map((e) => PlantProfile.fromJson(Map<String, dynamic>.from(e))).toList();
        return (r.data?['active']?.toString() ?? 'general', list);
      }
    } catch (_) {}
    return _loadLocalProfiles();
  }

  Future<(String, List<PlantProfile>)> _loadLocalProfiles() async {
    try {
      final p = await SharedPreferences.getInstance();
      final active = p.getString('local_active_profile') ?? 'general';
      return (active, _defaultProfiles);
    } catch (_) {
      return ('general', _defaultProfiles);
    }
  }

  static const List<PlantProfile> _defaultProfiles = [
    PlantProfile(
      id: 'general',
      name: 'พืชทั่วไป',
      note: 'เกณฑ์มาตรฐานโรงเรือนอัจฉริยะ',
      thresholds: {
        'temp': Range(20, 35),
        'humidity': Range(40, 90),
        'soil_moisture': Range(30, 80),
        'vpd': Range(0.4, 1.6),
        'soil_ph': Range(5.5, 7.5),
      },
    ),
    PlantProfile(
      id: 'durian',
      name: 'ทุเรียน (Durian)',
      note: 'ควบคุมความชื้นดินและ VPD แม่นยำ',
      thresholds: {
        'temp': Range(24, 34),
        'humidity': Range(65, 85),
        'soil_moisture': Range(45, 70),
        'vpd': Range(0.8, 1.3),
        'soil_ph': Range(5.5, 6.5),
      },
    ),
    PlantProfile(
      id: 'melon',
      name: 'เมลอน (Melon)',
      note: 'ควบคุมอุณหภูมิและความชื้นสัมพัทธ์',
      thresholds: {
        'temp': Range(22, 32),
        'humidity': Range(55, 75),
        'soil_moisture': Range(40, 65),
        'vpd': Range(0.9, 1.4),
        'soil_ph': Range(6.0, 6.8),
      },
    ),
    PlantProfile(
      id: 'hydro',
      name: 'ผักไฮโดรโปนิกส์ (Hydroponics)',
      note: 'ผักสลัด กรีนโอ๊ค เรดโอ๊ค',
      thresholds: {
        'temp': Range(18, 28),
        'humidity': Range(50, 75),
        'soil_moisture': Range(50, 85),
        'vpd': Range(0.6, 1.2),
        'soil_ph': Range(5.8, 6.5),
      },
    ),
  ];

  Future<CommandResult> setProfile(String id) async {
    try {
      final r = await _call('set_profile&id=${Uri.encodeQueryComponent(id)}&src=app');
      if (r.ok) {
        final p = await SharedPreferences.getInstance();
        await p.setString('local_active_profile', id);
        return r;
      }
    } catch (_) {}
    try {
      final p = await SharedPreferences.getInstance();
      await p.setString('local_active_profile', id);
      addLocalAudit('profile_save', 'เลือกโปรไฟล์ $id ในเครื่อง', 'app');
    } catch (_) {}
    return const CommandResult(true, 'เปลี่ยนโปรไฟล์พืชเรียบร้อยแล้ว');
  }

  Future<HistoryResult> getHistory({int limit = 60}) async {
    final r = await _call('get_history&limit=$limit');
    final list = r.data?['data'];
    if (!r.ok || list is! List) throw Exception(r.message);
    final pts = list.whereType<Map>().map((e) => HistoryPoint.fromJson(Map<String, dynamic>.from(e))).toList();
    return HistoryResult(pts, r.data?['simulated'] == true);
  }

  /// Quick latency test to ESP32 board
  Future<int?> pingBoard() async {
    if (!_useDirectBoard) return null; // ใช้เซิร์ฟเวอร์สาธารณะ: ไม่ยิงเข้า IP ของบอร์ดตรงๆ
    final stopwatch = Stopwatch()..start();
    try {
      final uri = Uri.parse('$directBoardBaseUrl/status');
      final res = await http.get(uri).timeout(const Duration(milliseconds: 1500));
      stopwatch.stop();
      if (res.statusCode == 200) {
        return stopwatch.elapsedMilliseconds;
      }
    } catch (_) {}
    return null;
  }
}
