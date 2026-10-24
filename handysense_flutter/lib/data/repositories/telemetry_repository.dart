import '../models/farm_models.dart';
import '../models/telemetry_model.dart';
import '../services/api_service.dart';

class TelemetryRepository {
  final ApiService _api;

  TelemetryRepository({ApiService? apiService}) : _api = apiService ?? ApiService();

  Future<TelemetryModel> getTelemetry() => _api.fetchTelemetry();

  Future<CommandResult> setRelay(int relayId, bool state, {int durationMin = 0}) =>
      _api.controlRelay(relayId, state, durationMin: durationMin);

  Future<CommandResult> setMode(String mode) => _api.setControlMode(mode);

  Future<int?> testPing() => _api.pingBoard();

  Future<List<ScheduleItem>> getSchedules() => _api.getSchedules();
  Future<CommandResult> saveSchedule(Map<String, dynamic> s) => _api.saveSchedule(s);
  Future<CommandResult> deleteSchedule(String id) => _api.deleteSchedule(id);
  Future<List<AuditEntry>> getAudit({int limit = 80}) => _api.getAudit(limit: limit);
  Future<List<AlertItem>> getAlerts() => _api.getAlerts();
  Future<(String, List<PlantProfile>)> getProfiles() => _api.getProfiles();
  Future<CommandResult> setProfile(String id) => _api.setProfile(id);
  Future<HistoryResult> getHistory({int limit = 60}) => _api.getHistory(limit: limit);

  void updateSettings({
    required String ip,
    required int port,
    required String apiUrl,
    String? apiKey,
  }) {
    _api.updateConfig(boardIp: ip, boardPort: port, localApiUrl: apiUrl, apiKey: apiKey);
  }
}
