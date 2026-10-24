class ApiConstants {
  static const String defaultBoardIp = '192.168.0.111';
  static const int defaultBoardPort = 8500;
  // Current active LAN IP for XAMPP Apache Server
  static const String defaultLocalApiUrl = 'http://10.100.2.179/handysense/leqs-workshop/api/api.php';
  static const String fallbackLocalhostUrl = 'http://localhost/handysense/leqs-workshop/api/api.php';
  static const String defaultCloudUrl = 'http://14.207.141.164:8000';

  // Smart Fallback Endpoints for automatic reconnection across Wi-Fi networks
  static const List<String> fallbackCandidateUrls = [
    'http://10.100.2.179/handysense/leqs-workshop/api/api.php',
    'http://192.168.0.107/handysense/leqs-workshop/api/api.php',
    'http://192.168.1.107/handysense/leqs-workshop/api/api.php',
    'http://10.100.2.179/handysense/aiot-fleet-dashboard/api/index.php?action=get_boards',
  ];

  // Relay GPIO Pinouts (Farm1 Expansion Shield)
  static const int relay1Gpio = 39; // Main Pump 1
  static const int relay2Gpio = 38; // Solenoid / Pump 2
  static const int relay3Gpio = 7;  // Surface Valve
  static const int relay4Gpio = 6;  // Misting System

  // Polling Interval
  static const Duration pollingInterval = Duration(seconds: 2);
}
