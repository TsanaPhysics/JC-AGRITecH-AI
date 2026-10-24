import '../models/telemetry_model.dart';
import '../services/api_service.dart';

class TelemetryRepository {
  final ApiService _api;

  TelemetryRepository({ApiService? apiService})
      : _api = apiService ?? ApiService();

  Future<TelemetryModel> getTelemetry() => _api.fetchTelemetry();

  Future<bool> setRelay(int relayId, bool state) =>
      _api.controlRelay(relayId, state);

  Future<bool> setMode(String mode) => _api.setControlMode(mode);

  Future<int?> testPing() => _api.pingBoard();

  void updateSettings({
    required String ip,
    required int port,
    required String apiUrl,
  }) {
    _api.updateConfig(boardIp: ip, boardPort: port, localApiUrl: apiUrl);
  }
}
