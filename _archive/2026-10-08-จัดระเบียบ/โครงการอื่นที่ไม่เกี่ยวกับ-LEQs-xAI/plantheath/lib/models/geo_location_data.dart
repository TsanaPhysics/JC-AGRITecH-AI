class GeoLocationData {
  final double latitude;
  final double longitude;
  final double altitude;
  final double accuracy;
  final DateTime timestamp;
  final bool isAvailable;

  const GeoLocationData({
    required this.latitude,
    required this.longitude,
    required this.altitude,
    required this.accuracy,
    required this.timestamp,
    required this.isAvailable,
  });

  factory GeoLocationData.mockDefault() {
    return GeoLocationData(
      latitude: 12.658214, // จันทบุรี สวนทุเรียนแม่ข่ายวิจัย RBRU
      longitude: 102.108422,
      altitude: 48.5,
      accuracy: 2.1,
      timestamp: DateTime.now(),
      isAvailable: true,
    );
  }

  factory GeoLocationData.unavailable() {
    return GeoLocationData(
      latitude: 0.0,
      longitude: 0.0,
      altitude: 0.0,
      accuracy: 0.0,
      timestamp: DateTime.now(),
      isAvailable: false,
    );
  }

  String get formattedCoordinates {
    if (!isAvailable) return 'พิกัด GPS ไม่พร้อมใช้งาน';
    final latDir = latitude >= 0 ? 'N' : 'S';
    final lngDir = longitude >= 0 ? 'E' : 'W';
    return '${latitude.abs().toStringAsFixed(5)}° $latDir, ${longitude.abs().toStringAsFixed(5)}° $lngDir';
  }

  String get formattedAltitude {
    if (!isAvailable) return '- m';
    return '${altitude.toStringAsFixed(1)} m';
  }

  Map<String, dynamic> toJson() => {
    'latitude': latitude,
    'longitude': longitude,
    'altitude': altitude,
    'accuracy': accuracy,
    'timestamp': timestamp.toIso8601String(),
    'isAvailable': isAvailable,
  };

  factory GeoLocationData.fromJson(Map<String, dynamic> json) {
    return GeoLocationData(
      latitude: (json['latitude'] as num?)?.toDouble() ?? 0.0,
      longitude: (json['longitude'] as num?)?.toDouble() ?? 0.0,
      altitude: (json['altitude'] as num?)?.toDouble() ?? 0.0,
      accuracy: (json['accuracy'] as num?)?.toDouble() ?? 0.0,
      timestamp: json['timestamp'] != null ? DateTime.parse(json['timestamp']) : DateTime.now(),
      isAvailable: json['isAvailable'] as bool? ?? false,
    );
  }
}
