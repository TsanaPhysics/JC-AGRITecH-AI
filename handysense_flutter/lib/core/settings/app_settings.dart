import 'package:shared_preferences/shared_preferences.dart';
import '../constants/api_constants.dart';

/// การตั้งค่าที่เก็บถาวรในเครื่อง (ไม่หายเมื่อปิดแอป)
class AppSettings {
  final String apiUrl;
  final String boardIp;
  final int boardPort;
  final String apiKey;

  const AppSettings({
    required this.apiUrl,
    required this.boardIp,
    required this.boardPort,
    required this.apiKey,
  });

  static const AppSettings defaults = AppSettings(
    apiUrl: ApiConstants.defaultLocalApiUrl,
    boardIp: ApiConstants.defaultBoardIp,
    boardPort: ApiConstants.defaultBoardPort,
    apiKey: '',
  );

  AppSettings copyWith({String? apiUrl, String? boardIp, int? boardPort, String? apiKey}) => AppSettings(
        apiUrl: apiUrl ?? this.apiUrl,
        boardIp: boardIp ?? this.boardIp,
        boardPort: boardPort ?? this.boardPort,
        apiKey: apiKey ?? this.apiKey,
      );

  static Future<AppSettings> load() async {
    try {
      final p = await SharedPreferences.getInstance();
      return AppSettings(
        apiUrl: p.getString('apiUrl') ?? defaults.apiUrl,
        boardIp: p.getString('boardIp') ?? defaults.boardIp,
        boardPort: p.getInt('boardPort') ?? defaults.boardPort,
        apiKey: p.getString('apiKey') ?? '',
      );
    } catch (_) {
      return defaults;
    }
  }

  Future<void> save() async {
    try {
      final p = await SharedPreferences.getInstance();
      await p.setString('apiUrl', apiUrl);
      await p.setString('boardIp', boardIp);
      await p.setInt('boardPort', boardPort);
      await p.setString('apiKey', apiKey);
    } catch (_) {}
  }
}
