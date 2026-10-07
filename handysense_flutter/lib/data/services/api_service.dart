import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../../core/constants/api_constants.dart';
import '../models/telemetry_model.dart';

class ApiService {
  String _boardIp = ApiConstants.defaultBoardIp;
  int _boardPort = ApiConstants.defaultBoardPort;
  String _localApiUrl = ApiConstants.defaultLocalApiUrl;

  ApiService({String? boardIp, int? boardPort, String? localApiUrl}) {
    if (boardIp != null) _boardIp = boardIp;
    if (boardPort != null) _boardPort = boardPort;
    if (localApiUrl != null) _localApiUrl = localApiUrl;
  }

  void updateConfig({required String boardIp, required int boardPort, required String localApiUrl}) {
    _boardIp = boardIp;
    _boardPort = boardPort;
    _localApiUrl = localApiUrl;
  }

  String get directBoardBaseUrl => 'http://$_boardIp:$_boardPort';
  String get localApiUrl => _localApiUrl;

  /// Fetches complete telemetry from local backend API or directly from ESP32
  Future<TelemetryModel> fetchTelemetry() async {
    // 1. Try PHP Central API first (rich database + complete sensor telemetry)
    try {
      final uri = Uri.parse('$_localApiUrl?action=get_telemetry');
      final response = await http.get(uri).timeout(const Duration(milliseconds: 2500));
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
            if (!candidate.contains('aiot-fleet-dashboard')) {
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
            'device_name': 'ESP32-S3 ATD3.5 Smart Farm',
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

  /// Sends physical relay actuation to both Direct ESP32 REST (<50ms) and Central API
  Future<bool> controlRelay(int relayId, bool state) async {
    final int stateInt = state ? 1 : 0;
    bool success = false;

    // 1. Send direct to ESP32 board for sub-50ms ultra fast response
    try {
      final directUri = Uri.parse('$directBoardBaseUrl/relay?id=$relayId&state=$stateInt');
      final res = await http.get(directUri).timeout(const Duration(milliseconds: 1200));
      if (res.statusCode == 200) {
        success = true;
      }
    } catch (_) {}

    // 2. Also send to Central API to persist in SQLite3 DB and notify all web/mobile dashboards
    try {
      final apiUri = Uri.parse('$_localApiUrl?action=control_relay&id=$relayId&state=$stateInt');
      final res = await http.get(apiUri).timeout(const Duration(milliseconds: 1500));
      if (res.statusCode == 200) {
        success = true;
      }
    } catch (_) {
      try {
        final fallbackUri = Uri.parse('${ApiConstants.fallbackLocalhostUrl}?action=control_relay&id=$relayId&state=$stateInt');
        await http.get(fallbackUri).timeout(const Duration(milliseconds: 1000));
      } catch (_) {}
    }

    return success;
  }

  /// Switches Mode: 'manual', 'auto', or 'ai'
  Future<bool> setControlMode(String mode) async {
    final cleanMode = mode.toLowerCase();
    bool success = false;

    // 1. Send direct to board
    try {
      final directUri = Uri.parse('$directBoardBaseUrl/mode?mode=$cleanMode');
      final res = await http.get(directUri).timeout(const Duration(milliseconds: 1200));
      if (res.statusCode == 200) {
        success = true;
      }
    } catch (_) {}

    // 2. Send to API
    try {
      final apiUri = Uri.parse('$_localApiUrl?action=set_control_mode&mode=$cleanMode');
      final res = await http.get(apiUri).timeout(const Duration(milliseconds: 1500));
      if (res.statusCode == 200) {
        success = true;
      }
    } catch (_) {
      try {
        final fallbackUri = Uri.parse('${ApiConstants.fallbackLocalhostUrl}?action=set_control_mode&mode=$cleanMode');
        await http.get(fallbackUri).timeout(const Duration(milliseconds: 1000));
      } catch (_) {}
    }

    return success;
  }

  /// Quick latency test to ESP32 board
  Future<int?> pingBoard() async {
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
