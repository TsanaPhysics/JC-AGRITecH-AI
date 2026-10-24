import 'dart:async';
import 'package:flutter/material.dart';
import '../../core/constants/api_constants.dart';
import '../../core/settings/app_settings.dart';
import '../../data/models/farm_models.dart';
import '../../data/models/telemetry_model.dart';
import '../../data/repositories/telemetry_repository.dart';

/// สถานะการเชื่อมต่อ (ดูจากอายุข้อมูลที่บอร์ดส่งขึ้นเซิร์ฟเวอร์ ไม่ใช่แค่แอปต่อเซิร์ฟเวอร์ได้)
enum ConnState { connecting, live, delayed, boardOffline, serverDown }

/// สถานะคำสั่งรีเลย์ 3 ขั้น: กำลังส่ง -> เซิร์ฟเวอร์รับแล้ว (รอบอร์ดยืนยัน) -> บอร์ดยืนยัน
enum CmdState { idle, sending, awaitingBoard, confirmed, failed, unconfirmed }

class RelayView {
  final RelayItem item;
  final bool on;
  final CmdState cmd;
  final int? timerLeftSec;
  final int? sinceEpoch;
  const RelayView({
    required this.item,
    required this.on,
    required this.cmd,
    required this.timerLeftSec,
    required this.sinceEpoch,
  });
  bool get busy => cmd == CmdState.sending || cmd == CmdState.awaitingBoard;
}

class TelemetryViewModel extends ChangeNotifier with WidgetsBindingObserver {
  TelemetryViewModel({required TelemetryRepository repository}) : _repository = repository {
    WidgetsBinding.instance.addObserver(this);
    _init();
  }

  final TelemetryRepository _repository;
  TelemetryRepository get repository => _repository;

  // ── เกณฑ์อายุข้อมูล (วินาที) ──
  static const int liveMaxAge = 20;
  static const int delayedMaxAge = 90;
  static const Duration confirmTimeout = Duration(seconds: 12);

  AppSettings _settings = AppSettings.defaults;
  String get currentBoardIp => _settings.boardIp;
  int get currentBoardPort => _settings.boardPort;
  String get currentLocalApiUrl => _settings.apiUrl;
  String get apiKey => _settings.apiKey;

  TelemetryModel? _telemetry;
  TelemetryModel? get telemetry => _telemetry;
  DateTime? _fetchedAt;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _errorMessage;
  String? get errorMessage => _errorMessage;
  int _failures = 0;

  int? _boardLatencyMs;
  int? get boardLatencyMs => _boardLatencyMs;

  String _controlMode = 'manual';
  String get controlMode => _controlMode;
  bool get isAutoMode => _controlMode == 'auto';

  // ── แจ้งเตือนและโปรไฟล์พืช ──
  List<AlertItem> _alerts = const [];
  List<AlertItem> get alerts => _alerts;
  List<PlantProfile> _profiles = const [PlantProfile.fallback];
  List<PlantProfile> get profiles => _profiles;
  String _activeProfileId = 'general';
  String get activeProfileId => _activeProfileId;
  PlantProfile get profile =>
      _profiles.firstWhere((p) => p.id == _activeProfileId, orElse: () => _profiles.first);

  // ── ข้อความแจ้งผู้ใช้ (SnackBar) ──
  String? _notice;
  bool _noticeIsError = false;
  String? get notice => _notice;
  bool get noticeIsError => _noticeIsError;
  void clearNotice() => _notice = null;
  void _setNotice(String msg, {bool error = false}) {
    _notice = msg;
    _noticeIsError = error;
  }

  // ── คำสั่งรีเลย์ที่กำลังดำเนินการ ──
  final Map<int, bool> _desired = {};
  final Map<int, DateTime> _desiredAt = {};
  final Map<int, CmdState> _cmd = {};

  Timer? _pollingTimer;
  Timer? _slowTimer;
  bool _fetching = false;
  bool _polling = false;

  Future<void> _init() async {
    _settings = await AppSettings.load();
    _repository.updateSettings(
      ip: _settings.boardIp,
      port: _settings.boardPort,
      apiUrl: _settings.apiUrl,
      apiKey: _settings.apiKey,
    );
    _startPolling();
  }

  void _startPolling() {
    if (_polling) return;
    _polling = true;
    fetchTelemetry(silent: false);
    _refreshSlow();
    _pollingTimer?.cancel();
    _pollingTimer = Timer.periodic(ApiConstants.pollingInterval, (_) => fetchTelemetry(silent: true));
    _slowTimer?.cancel();
    _slowTimer = Timer.periodic(const Duration(seconds: 15), (_) => _refreshSlow());
  }

  void _stopPolling() {
    _polling = false;
    _pollingTimer?.cancel();
    _slowTimer?.cancel();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      _startPolling();
    } else if (state == AppLifecycleState.paused || state == AppLifecycleState.detached) {
      _stopPolling(); // ประหยัดแบตเตอรี่/ดาต้าเมื่อแอปอยู่เบื้องหลัง
    }
  }

  // ── สถานะการเชื่อมต่อ ──
  int? get dataAgeSeconds {
    final t = _telemetry;
    final at = _fetchedAt;
    if (t == null || at == null) return null;
    final base = t.dataAgeSeconds;
    if (base == null) return null;
    return base + DateTime.now().difference(at).inSeconds;
  }

  ConnState get connState {
    if (_telemetry == null) return _failures >= 3 ? ConnState.serverDown : ConnState.connecting;
    if (_failures >= 3) return ConnState.serverDown;
    final age = dataAgeSeconds;
    if (age == null) return _telemetry!.board.isLive ? ConnState.live : ConnState.boardOffline;
    if (age <= liveMaxAge) return ConnState.live;
    if (age <= delayedMaxAge) return ConnState.delayed;
    return ConnState.boardOffline;
  }

  /// สั่งรีเลย์ได้เมื่อบอร์ดออนไลน์ (กันคำสั่งค้างแล้วไปทำงานทีหลังโดยไม่ตั้งใจ)
  bool get canControl => connState == ConnState.live || connState == ConnState.delayed;

  String get controlBlockedReason {
    switch (connState) {
      case ConnState.serverDown:
        return 'เชื่อมต่อเซิร์ฟเวอร์ไม่ได้';
      case ConnState.boardOffline:
        return 'บอร์ดออฟไลน์ — ไม่ได้รับข้อมูลจากบอร์ดนานเกินไป';
      case ConnState.connecting:
        return 'กำลังเชื่อมต่อ...';
      default:
        return '';
    }
  }

  Future<void> fetchTelemetry({bool silent = false}) async {
    if (_fetching) return;
    _fetching = true;
    if (!silent) {
      _isLoading = true;
      notifyListeners();
    }
    try {
      final model = await _repository.getTelemetry();
      _telemetry = model;
      _fetchedAt = DateTime.now();
      _controlMode = model.controlMode;
      _failures = 0;
      _errorMessage = null;
      _reconcileCommands(model);
      if (_boardLatencyMs == null || DateTime.now().second % 15 == 0) {
        _repository.testPing().then((lat) {
          if (lat != null) {
            _boardLatencyMs = lat;
            notifyListeners();
          }
        });
      }
    } catch (e) {
      _failures++;
      if (_failures >= 3 || !silent) {
        _errorMessage = e.toString().replaceFirst('Exception: ', '');
      }
    } finally {
      _fetching = false;
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> _refreshSlow() async {
    try {
      _alerts = await _repository.getAlerts();
      notifyListeners();
    } catch (_) {}
    if (_profilesLoaded) return;
    try {
      final (active, list) = await _repository.getProfiles();
      if (list.isNotEmpty) {
        _profiles = list;
        _activeProfileId = active;
        _profilesLoaded = true;
        notifyListeners();
      }
    } catch (_) {}
  }

  bool _profilesLoaded = false;

  // ── ประสานสถานะที่ผู้ใช้สั่งกับค่าที่บอร์ดรายงานจริง ──
  void _reconcileCommands(TelemetryModel model) {
    final now = DateTime.now();
    for (final id in _desired.keys.toList()) {
      final actual = model.relays[id]?.state;
      final at = _desiredAt[id] ?? now;
      if (actual == _desired[id]) {
        _cmd[id] = CmdState.confirmed;
        _desired.remove(id);
        _desiredAt.remove(id);
      } else if (now.difference(at) > confirmTimeout) {
        _cmd[id] = CmdState.unconfirmed;
        _desired.remove(id);
        _desiredAt.remove(id);
        _setNotice('บอร์ดยังไม่ยืนยันการสั่งรีเลย์ $id — ตรวจสอบบอร์ดหรือลองใหม่', error: true);
      }
    }
  }

  RelayView relayView(int id) {
    final t = _telemetry;
    final base = t?.relays[id] ?? RelayItem(id: id, name: 'รีเลย์ $id', state: false, gpio: 0);
    final desired = _desired[id];
    int? left;
    final raw = t?.timers[id];
    if (raw != null && _fetchedAt != null) {
      left = raw - DateTime.now().difference(_fetchedAt!).inSeconds;
      if (left < 0) left = 0;
    }
    return RelayView(
      item: base,
      on: desired ?? base.state,
      cmd: _cmd[id] ?? CmdState.idle,
      timerLeftSec: (left != null && (desired ?? base.state)) ? left : null,
      sinceEpoch: t?.relaySince[id],
    );
  }

  /// สั่งเปิด/ปิดรีเลย์ — ไม่อัปเดตแบบมองโลกในแง่ดีโดยไม่ตรวจสอบ: แสดงสถานะ "กำลังส่ง" แล้วรอบอร์ดยืนยัน
  /// ถ้าเซิร์ฟเวอร์ปฏิเสธจะย้อนสถานะกลับและแจ้งสาเหตุ
  Future<bool> toggleRelay(int relayId, {bool? target, int durationMin = 0}) async {
    if (_telemetry == null) return false;
    if (!canControl) {
      _setNotice(controlBlockedReason, error: true);
      notifyListeners();
      return false;
    }
    final current = relayView(relayId);
    if (current.busy) return false;
    final next = target ?? !current.on;

    _desired[relayId] = next;
    _desiredAt[relayId] = DateTime.now();
    _cmd[relayId] = CmdState.sending;
    notifyListeners();

    final r = await _repository.setRelay(relayId, next, durationMin: durationMin);
    if (!r.ok) {
      _desired.remove(relayId);
      _desiredAt.remove(relayId);
      _cmd[relayId] = CmdState.failed;
      _setNotice('สั่งรีเลย์ $relayId ไม่สำเร็จ: ${r.message}', error: true);
      notifyListeners();
      return false;
    }
    _cmd[relayId] = CmdState.awaitingBoard;
    _desiredAt[relayId] = DateTime.now();
    _controlMode = 'manual';
    notifyListeners();
    return true;
  }

  /// ปิดรีเลย์ทั้งหมด (ใช้เมื่อฉุกเฉิน)
  Future<int> allOff() async {
    int failed = 0;
    for (int id = 1; id <= 4; id++) {
      final v = relayView(id);
      if (!v.on && !v.busy) continue;
      final ok = await toggleRelay(id, target: false);
      if (!ok) failed++;
    }
    return failed;
  }

  Future<bool> setMode(String mode) async {
    final clean = mode.toLowerCase();
    final prev = _controlMode;
    if (!canControl) {
      _setNotice(controlBlockedReason, error: true);
      notifyListeners();
      return false;
    }
    _controlMode = clean;
    notifyListeners();
    final r = await _repository.setMode(clean);
    if (!r.ok) {
      _controlMode = prev;
      _setNotice('เปลี่ยนโหมดไม่สำเร็จ: ${r.message}', error: true);
      notifyListeners();
      return false;
    }
    return true;
  }

  Future<bool> selectProfile(String id) async {
    final r = await _repositoryProfileSet(id);
    if (r) {
      _activeProfileId = id;
      notifyListeners();
    }
    return r;
  }

  Future<bool> _repositoryProfileSet(String id) async {
    final res = await _repository.setProfile(id);
    if (!res.ok) {
      _setNotice('เปลี่ยนโปรไฟล์พืชไม่สำเร็จ: ${res.message}', error: true);
      notifyListeners();
    }
    return res.ok;
  }

  void updateConnectionSettings({
    required String ip,
    required int port,
    required String apiUrl,
    String? apiKey,
  }) {
    _settings = _settings.copyWith(
      boardIp: ip.trim(),
      boardPort: port,
      apiUrl: apiUrl.trim(),
      apiKey: apiKey?.trim(),
    );
    _settings.save();
    _repository.updateSettings(
      ip: _settings.boardIp,
      port: _settings.boardPort,
      apiUrl: _settings.apiUrl,
      apiKey: _settings.apiKey,
    );
    _profilesLoaded = false;
    fetchTelemetry(silent: false);
    _refreshSlow();
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _pollingTimer?.cancel();
    _slowTimer?.cancel();
    super.dispose();
  }
}
