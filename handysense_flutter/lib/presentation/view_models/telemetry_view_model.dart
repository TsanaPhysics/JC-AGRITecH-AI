import 'dart:async';
import 'package:flutter/material.dart';
import '../../core/constants/api_constants.dart';
import '../../data/models/telemetry_model.dart';
import '../../data/repositories/telemetry_repository.dart';

class SensorHistoryPoint {
  final DateTime time;
  final double temperature;
  final double humidity;
  final double soilMoisture;
  final double vpd;

  SensorHistoryPoint({
    required this.time,
    required this.temperature,
    required this.humidity,
    required this.soilMoisture,
    required this.vpd,
  });
}

class TelemetryViewModel extends ChangeNotifier {
  final TelemetryRepository _repository;

  TelemetryModel? _telemetry;
  TelemetryModel? get telemetry => _telemetry;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _errorMessage;
  String? get errorMessage => _errorMessage;

  int? _boardLatencyMs;
  int? get boardLatencyMs => _boardLatencyMs;

  bool _isAutoMode = false;
  bool get isAutoMode => _isAutoMode;

  String _controlMode = 'manual';
  String get controlMode => _controlMode;

  String _currentBoardIp = ApiConstants.defaultBoardIp;
  String get currentBoardIp => _currentBoardIp;

  int _currentBoardPort = ApiConstants.defaultBoardPort;
  int get currentBoardPort => _currentBoardPort;

  String _currentLocalApiUrl = ApiConstants.defaultLocalApiUrl;
  String get currentLocalApiUrl => _currentLocalApiUrl;

  final List<SensorHistoryPoint> _history = [];
  List<SensorHistoryPoint> get history => List.unmodifiable(_history);

  Timer? _pollingTimer;

  TelemetryViewModel({required TelemetryRepository repository}) : _repository = repository {
    _startPolling();
  }

  void _startPolling() {
    fetchTelemetry(silent: false);
    _pollingTimer?.cancel();
    _pollingTimer = Timer.periodic(ApiConstants.pollingInterval, (_) {
      fetchTelemetry(silent: true);
    });
  }

  Future<void> fetchTelemetry({bool silent = false}) async {
    if (!silent) {
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();
    }

    try {
      final model = await _repository.getTelemetry();
      _telemetry = model;
      _isAutoMode = model.autoMode;
      _controlMode = model.controlMode;
      _errorMessage = null;

      // Add to history (keep last 20 points for smooth graphing)
      _history.add(SensorHistoryPoint(
        time: DateTime.now(),
        temperature: model.sensors.temperature,
        humidity: model.sensors.humidity,
        soilMoisture: model.sensors.soilMoisture,
        vpd: model.sensors.vpd,
      ));
      if (_history.length > 25) {
        _history.removeAt(0);
      }

      // Check board latency periodically
      if (_history.length % 3 == 0) {
        _repository.testPing().then((lat) {
          if (lat != null) {
            _boardLatencyMs = lat;
            notifyListeners();
          }
        });
      }
    } catch (e) {
      if (!silent) {
        _errorMessage = e.toString();
      }
    } finally {
      if (!silent) {
        _isLoading = false;
      }
      notifyListeners();
    }
  }

  /// Physical Relay Actuation (Instant UI response + Hardware Call)
  Future<bool> toggleRelay(int relayId) async {
    if (_telemetry == null) return false;
    final currentRelay = _telemetry!.relays[relayId];
    if (currentRelay == null) return false;

    final nextState = !currentRelay.state;

    // Optimistic UI update
    _telemetry!.relays[relayId] = currentRelay.copyWith(state: nextState);
    _controlMode = 'manual';
    _isAutoMode = false;
    notifyListeners();

    final success = await _repository.setRelay(relayId, nextState);
    return success;
  }

  /// Change Tri-Mode: 'manual', 'auto', or 'ai'
  Future<bool> setMode(String mode) async {
    final cleanMode = mode.toLowerCase();
    _controlMode = cleanMode;
    _isAutoMode = cleanMode == 'auto';
    notifyListeners();

    final success = await _repository.setMode(cleanMode);
    return success;
  }

  void updateConnectionSettings({required String ip, required int port, required String apiUrl}) {
    _currentBoardIp = ip.trim();
    _currentBoardPort = port;
    _currentLocalApiUrl = apiUrl.trim();
    _repository.updateSettings(ip: _currentBoardIp, port: _currentBoardPort, apiUrl: _currentLocalApiUrl);
    fetchTelemetry(silent: false);
  }

  @override
  void dispose() {
    _pollingTimer?.cancel();
    super.dispose();
  }
}
