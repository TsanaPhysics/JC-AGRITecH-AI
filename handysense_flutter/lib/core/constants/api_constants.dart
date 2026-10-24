class ApiConstants {
  static const String defaultBoardIp = '192.168.0.111';
  static const int defaultBoardPort = 8500;
  // เซิร์ฟเวอร์สาธารณะ (ใช้ได้ข้ามเครือข่าย: บอร์ดบนฮอตสปอต <-> เซิร์ฟเวอร์ <-> แอป)
  static const String publicApiUrl = 'http://14.207.141.164/edge-xaiot-agrienvi/api/api.php';
  static const String defaultLocalApiUrl = publicApiUrl;
  static const String fallbackLocalhostUrl = 'http://localhost/edge-xaiot-agrienvi/api/api.php';
  static const String defaultCloudUrl = 'http://14.207.141.164:8000';

  // Smart Fallback Endpoints for automatic reconnection across Wi-Fi networks
  static const List<String> fallbackCandidateUrls = [
    'http://14.207.141.164/edge-xaiot-agrienvi/api/api.php',
    'https://scicenter.rbru.ac.th/edge-xaiot-agrienvi/api/api.php',
    'https://scicenter.rbru.ac.th/leqs-workshop/api/api.php',
    'http://192.168.0.107/edge-xaiot-agrienvi/api/api.php',
    'http://10.100.2.179/handysense/aiot-fleet-dashboard/api/index.php?action=get_boards',
  ];

  // Relay GPIO Pinouts (Farm1 Expansion Shield)
  static const int relay1Gpio = 39; // Main Pump 1
  static const int relay2Gpio = 38; // Solenoid / Pump 2
  static const int relay3Gpio = 7;  // Surface Valve
  static const int relay4Gpio = 6;  // Misting System

  // Polling Interval
  static const Duration pollingInterval = Duration(seconds: 1);
}
