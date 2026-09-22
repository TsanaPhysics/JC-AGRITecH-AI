import 'dart:async';
import 'dart:math' as math;

class HandySenseTelemetry {
  final double temperatureC;
  final double relativeHumidity;
  final double vpdKpa;
  final double soilMoisturePct;
  final double soilEc;
  final double soilPh;
  final double solarLux;
  final String stressAlert;
  final bool isStressCondition;
  final DateTime timestamp;

  const HandySenseTelemetry({
    required this.temperatureC,
    required this.relativeHumidity,
    required this.vpdKpa,
    required this.soilMoisturePct,
    required this.soilEc,
    required this.soilPh,
    required this.solarLux,
    required this.stressAlert,
    required this.isStressCondition,
    required this.timestamp,
  });

  String get stationName => 'สถานีวิจัยฟิสิกส์เกษตร HandySense #01';
  String get ipAddress => '192.168.4.1 (AP/Station)';
  double get airTempC => temperatureC;
  double get airHumidityRh => relativeHumidity;

  String get vpdCategory {
    if (vpdKpa < 0.4) return 'ชื้นจัด เสี่ยงเชื้อรา';
    if (vpdKpa <= 1.2) return 'สมดุลการคายน้ำดีเยี่ยม';
    if (vpdKpa <= 1.6) return 'คายน้ำปานกลาง';
    return 'แดดเผา/คายน้ำวิกฤต';
  }

  double get dewPointC {
    final a = 17.27;
    final b = 237.7;
    final alpha = ((a * temperatureC) / (b + temperatureC)) + math.log(relativeHumidity / 100.0);
    return double.parse(((b * alpha) / (a - alpha)).toStringAsFixed(1));
  }

  double get actualVaporPressureKpa {
    final svp = 0.61078 * math.exp((17.27 * temperatureC) / (temperatureC + 237.3));
    return double.parse((svp * (relativeHumidity / 100.0)).toStringAsFixed(2));
  }

  double get soilEcUsCm => soilEc * 1000.0;
  double get soilNitrogenMgKg => 145.0;
  double get soilPhosphorusMgKg => 38.0;
  double get soilPotassiumMgKg => 210.0;

  /// Calculate Vapor Pressure Deficit (VPD) in kPa from Air Temp (°C) & RH (%)
  /// Tetens equation for Saturated Vapor Pressure (SVP):
  /// SVP = 0.61078 * exp((17.27 * T) / (T + 237.3))
  /// AVP = SVP * (RH / 100.0)
  /// VPD = SVP - AVP
  static double calculateVpd(double tempC, double rhPct) {
    if (tempC < -20.0 || tempC > 65.0 || rhPct < 0.0 || rhPct > 100.0) return 1.1;
    final svp = 0.61078 * math.exp((17.27 * tempC) / (tempC + 237.3));
    final avp = svp * (rhPct / 100.0);
    final vpd = svp - avp;
    return double.parse(vpd.toStringAsFixed(2));
  }

  factory HandySenseTelemetry.create({
    required double temperatureC,
    required double relativeHumidity,
    required double soilMoisturePct,
    required double soilEc,
    required double soilPh,
    required double solarLux,
    DateTime? timestamp,
  }) {
    final vpd = calculateVpd(temperatureC, relativeHumidity);
    String alert = 'สภาวะจุลภูมิอากาศเหมาะสม (VPD ${vpd.toStringAsFixed(2)} kPa)';
    bool isStress = false;

    if (vpd > 2.2) {
      alert = 'เตือน: อากาศแห้งจัด/แดดเผา (VPD ${vpd.toStringAsFixed(2)} kPa สูงเกินเกณฑ์ เสี่ยงใบไหม้)';
      isStress = true;
    } else if (vpd < 0.4) {
      alert = 'เตือน: อากาศชื้นอับจัด (VPD ${vpd.toStringAsFixed(2)} kPa เสี่ยงเชื้อราน้ำลุกลาม)';
      isStress = true;
    } else if (soilMoisturePct < 25.0) {
      alert = 'เตือน: ดินแห้งวิกฤต (ความชื้นดิน ${soilMoisturePct.toStringAsFixed(0)}% ควรเปิดน้ำ)';
      isStress = true;
    } else if (soilMoisturePct > 85.0) {
      alert = 'เตือน: ดินแฉะขังน้ำ (ความชื้นดิน ${soilMoisturePct.toStringAsFixed(0)}% เสี่ยงรากเน่าโคนเน่า)';
      isStress = true;
    } else if (soilPh < 5.0) {
      alert = 'เตือน: ดินเป็นกรดจัด (pH ${soilPh.toStringAsFixed(1)} พืชตรึงธาตุฟอสฟอรัส)';
      isStress = true;
    }

    return HandySenseTelemetry(
      temperatureC: temperatureC,
      relativeHumidity: relativeHumidity,
      vpdKpa: vpd,
      soilMoisturePct: soilMoisturePct,
      soilEc: soilEc,
      soilPh: soilPh,
      solarLux: solarLux,
      stressAlert: alert,
      isStressCondition: isStress,
      timestamp: timestamp ?? DateTime.now(),
    );
  }

  factory HandySenseTelemetry.mockChanthaburiOrchard() {
    return HandySenseTelemetry.create(
      temperatureC: 31.8,
      relativeHumidity: 68.0,
      soilMoisturePct: 54.0,
      soilEc: 1.25,
      soilPh: 6.2,
      solarLux: 48500.0,
    );
  }

  Map<String, dynamic> toJson() => {
    'temperature_c': temperatureC,
    'relative_humidity': relativeHumidity,
    'vpd_kpa': vpdKpa,
    'soil_moisture_pct': soilMoisturePct,
    'soil_ec': soilEc,
    'soil_ph': soilPh,
    'solar_lux': solarLux,
    'stress_alert': stressAlert,
    'is_stress': isStressCondition,
    'timestamp': timestamp.toIso8601String(),
  };

  factory HandySenseTelemetry.fromJson(Map<String, dynamic> json) {
    return HandySenseTelemetry(
      temperatureC: (json['temperature_c'] as num?)?.toDouble() ?? 31.8,
      relativeHumidity: (json['relative_humidity'] as num?)?.toDouble() ?? 68.0,
      vpdKpa: (json['vpd_kpa'] as num?)?.toDouble() ?? 1.45,
      soilMoisturePct: (json['soil_moisture_pct'] as num?)?.toDouble() ?? 54.0,
      soilEc: (json['soil_ec'] as num?)?.toDouble() ?? 1.25,
      soilPh: (json['soil_ph'] as num?)?.toDouble() ?? 6.2,
      solarLux: (json['solar_lux'] as num?)?.toDouble() ?? 48500.0,
      stressAlert: json['stress_alert'] as String? ?? 'สภาวะเหมาะสม',
      isStressCondition: json['is_stress'] as bool? ?? false,
      timestamp: json['timestamp'] != null ? DateTime.parse(json['timestamp']) : DateTime.now(),
    );
  }
}

class HandySenseSensorService {
  static HandySenseTelemetry _latest = HandySenseTelemetry.mockChanthaburiOrchard();
  static final StreamController<HandySenseTelemetry> _controller = StreamController<HandySenseTelemetry>.broadcast();

  static HandySenseTelemetry get latestTelemetry => _latest;
  static Stream<HandySenseTelemetry> get telemetryStream => _controller.stream;

  static HandySenseTelemetry mockChanthaburiOrchard() => HandySenseTelemetry.mockChanthaburiOrchard();

  static void updateTelemetry(HandySenseTelemetry telemetry) {
    _latest = telemetry;
    _controller.add(telemetry);
  }

  /// Simulate realistic diurnal microclimate drift for demonstration and testing
  static void driftTelemetry(int tick) {
    final temp = 30.0 + math.sin(tick * 0.1) * 2.5;
    final rh = 70.0 - math.sin(tick * 0.1) * 10.0;
    final soilMoisture = 55.0 + math.cos(tick * 0.05) * 4.0;
    final lux = 45000.0 + (tick % 10) * 800.0;

    final updated = HandySenseTelemetry.create(
      temperatureC: double.parse(temp.toStringAsFixed(1)),
      relativeHumidity: double.parse(rh.toStringAsFixed(1)),
      soilMoisturePct: double.parse(soilMoisture.toStringAsFixed(1)),
      soilEc: 1.28,
      soilPh: 6.2,
      solarLux: lux,
    );
    updateTelemetry(updated);
  }
}
