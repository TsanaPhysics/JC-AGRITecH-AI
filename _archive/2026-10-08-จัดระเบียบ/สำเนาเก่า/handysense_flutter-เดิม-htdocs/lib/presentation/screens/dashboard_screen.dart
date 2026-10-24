import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/relay_switch_card.dart';
import '../widgets/sensor_gauge_card.dart';
import '../widgets/tri_mode_selector.dart';

// ─────────────────────────────────────────────────────────────
// 🌌 DashboardScreen — Cyber Dark Sci-Fi IoT Farm Dashboard
// ─────────────────────────────────────────────────────────────
class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  // Relay icons — cyber tech hardware
  static const _relayIcons = [
    Icons.water_rounded,          // Main pump
    Icons.opacity_rounded,        // Drip / mist system
    Icons.wb_sunny_rounded,       // Grow lights
    Icons.air_rounded,            // Ventilation fans
  ];

  static const _relayEmojis = ['💧', '🌧️', '☀️', '💨'];

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final telemetry = vm.telemetry;
    final mq = MediaQuery.of(context);
    final sw = mq.size.width;

    // ── Loading ──
    if (vm.isLoading && telemetry == null) {
      return _buildLoadingState(sw);
    }

    // ── Error / No Data ──
    if (telemetry == null) {
      return _buildErrorState(context, vm, sw);
    }

    final sensors = telemetry.sensors;
    final relays = telemetry.relays;

    final isLargeScreen = sw > 650;
    final isTablet = sw > 500;
    final hPad = sw < 360 ? 12.0 : sw < 420 ? 16.0 : 20.0;
    final gridCols = isLargeScreen ? 4 : 2;
    // Calibrated for circular HUD dials:
    final gridRatio = isLargeScreen ? 0.95 : (isTablet ? 0.90 : 0.74);

    return RefreshIndicator(
      onRefresh: () => vm.fetchTelemetry(silent: false),
      color: AppColors.neonCyan,
      backgroundColor: const Color(0xFF111A2E),
      displacement: 60,
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: EdgeInsets.symmetric(horizontal: hPad, vertical: 16),
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 900),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // ── Board Connection Banner ──
                _buildConnectionBanner(vm, telemetry, sw),
                const SizedBox(height: 20),

                // ── Sensor HUD Section Header ──
                _buildSectionHeader(
                  title: 'สภาพแวดล้อมและพารามิเตอร์ดิน',
                  subtitle: 'ENVIRONMENT & SOIL SENSORS',
                  accentColor: AppColors.neonCyan,
                  sw: sw,
                ),
                const SizedBox(height: 12),

                // ── 6-Gauge HUD Grid (Environment + Soil + Light + pH) ──
                GridView.count(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisCount: gridCols,
                  crossAxisSpacing: 10,
                  mainAxisSpacing: 10,
                  childAspectRatio: gridRatio,
                  children: [
                    // 1. Air Temperature (Neon Orange)
                    SensorGaugeCard(
                      label: 'Air Temperature',
                      value: sensors.temperature.toStringAsFixed(2),
                      unit: '°C',
                      subTarget: '28.5',
                      statusText: sensors.temperature > 35
                          ? 'Hot'
                          : sensors.temperature < 20
                              ? 'Cold'
                              : 'Optimal',
                      icon: Icons.thermostat_rounded,
                      accentColor: const Color(0xFFFF7A00),
                      statusColor: const Color(0xFFFFAB00),
                      progress: (sensors.temperature / 50.0).clamp(0.0, 1.0),
                    ),

                    // 2. Air Humidity (Neon Cyan)
                    SensorGaugeCard(
                      label: 'Air Humidity',
                      value: sensors.humidity.toStringAsFixed(2),
                      unit: '%',
                      subTarget: '65',
                      statusText: sensors.humidity < 40 ? 'Dry' : 'Ideal',
                      icon: Icons.water_drop_rounded,
                      accentColor: const Color(0xFF00C6FF),
                      statusColor: const Color(0xFF00E5FF),
                      progress: (sensors.humidity / 100.0).clamp(0.0, 1.0),
                    ),

                    // 3. Soil Moisture (Neon Green)
                    SensorGaugeCard(
                      label: 'Soil Moisture',
                      value: sensors.soilMoisture.toStringAsFixed(1),
                      unit: '%',
                      subTarget: '72.0',
                      statusText: sensors.soilMoisture < 30 ? 'Low' : 'Ideal',
                      icon: Icons.grass_rounded,
                      accentColor: const Color(0xFF00E676),
                      statusColor: const Color(0xFF00E676),
                      progress: (sensors.soilMoisture / 100.0).clamp(0.0, 1.0),
                    ),

                    // 4. VPD (Neon Violet/Purple)
                    SensorGaugeCard(
                      label: 'VPD',
                      value: sensors.vpd.toStringAsFixed(2),
                      unit: 'kPa',
                      subTarget: '0.95',
                      statusText: (sensors.vpd >= 0.8 && sensors.vpd <= 1.2)
                          ? 'Ideal'
                          : 'Caution',
                      icon: Icons.cloud_sync_rounded,
                      accentColor: const Color(0xFF7C4DFF),
                      statusColor: const Color(0xFF8B5CF6),
                      progress: (sensors.vpd / 2.5).clamp(0.0, 1.0),
                    ),

                    // 5. Soil pH (Neon Amber/Yellow)
                    SensorGaugeCard(
                      label: 'Soil pH',
                      value: sensors.soilPh.toStringAsFixed(1),
                      unit: 'pH',
                      subTarget: '6.5',
                      statusText: (sensors.soilPh >= 5.5 && sensors.soilPh <= 7.0)
                          ? 'Optimal'
                          : sensors.soilPh < 5.5
                              ? 'Acidic'
                              : 'Alkaline',
                      icon: Icons.science_rounded,
                      accentColor: const Color(0xFFFFD600),
                      statusColor: const Color(0xFFFFAB40),
                      progress: (sensors.soilPh / 14.0).clamp(0.0, 1.0),
                    ),

                    // 6. Light Intensity (Neon Rose/Pink)
                    SensorGaugeCard(
                      label: 'Light Intensity',
                      value: sensors.parLux >= 1000
                          ? (sensors.parLux / 1000).toStringAsFixed(1)
                          : sensors.parLux.toStringAsFixed(0),
                      unit: sensors.parLux >= 1000 ? 'kLux' : 'Lux',
                      subTarget: '25.0',
                      statusText: sensors.parLux < 500
                          ? 'Low'
                          : sensors.parLux <= 50000
                              ? 'Good'
                              : 'Intense',
                      icon: Icons.wb_sunny_rounded,
                      accentColor: const Color(0xFFFF4081),
                      statusColor: const Color(0xFFFF80AB),
                      progress: (sensors.parLux / 80000.0).clamp(0.0, 1.0),
                    ),
                  ],
                ),

                const SizedBox(height: 24),

                // ── Mode Selector ──
                TriModeSelector(
                  currentMode: vm.controlMode,
                  onModeChanged: vm.setMode,
                ),

                const SizedBox(height: 24),

                // ── Relay Section ──
                _buildRelayHeader(relays, sw),
                const SizedBox(height: 12),

                // Relay cards layout
                if (isTablet)
                  GridView.count(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    crossAxisCount: 2,
                    crossAxisSpacing: 10,
                    mainAxisSpacing: 10,
                    childAspectRatio: 3.2,
                    children: _buildRelayList(relays, vm),
                  )
                else
                  Column(
                    children: [
                      for (int i = 0; i < 4; i++) ...[
                        if (relays.containsKey(i + 1))
                          RelaySwitchCard(
                            relay: relays[i + 1]!,
                            onToggle: () => vm.toggleRelay(i + 1),
                            icon: _relayIcons[i],
                          ),
                        if (i < 3 && relays.containsKey(i + 1))
                          const SizedBox(height: 10),
                      ],
                    ],
                  ),

                const SizedBox(height: 18),

                // ── Active relay count summary ──
                _buildRelayStatusSummary(relays, sw),
                const SizedBox(height: 10),

                // ── GPS Pill ──
                if (telemetry.gps.locationName.isNotEmpty) ...[
                  _buildGpsPill(telemetry, sw),
                  const SizedBox(height: 8),
                ],
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ── Loading State ─────────────────────────────────────────
  Widget _buildLoadingState(double sw) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          const _CyberPulseLoader(),
          const SizedBox(height: 24),
          Text(
            'CONNECTING ESP32-S3...',
            style: GoogleFonts.orbitron(
              fontSize: sw < 360 ? 13 : 15,
              fontWeight: FontWeight.w700,
              color: AppColors.neonCyan,
              letterSpacing: 1.0,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'กำลังเชื่อมต่อเซิร์ฟเวอร์ LEQsxAI IoT',
            style: GoogleFonts.rajdhani(
              fontSize: 12,
              color: const Color(0xFF8EA2C6),
            ),
          ),
        ],
      ),
    );
  }

  // ── Error State ───────────────────────────────────────────
  Widget _buildErrorState(
      BuildContext context, TelemetryViewModel vm, double sw) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(sw < 360 ? 20 : 32),
        child: Container(
          padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(
            color: const Color(0xFF111A2E),
            borderRadius: BorderRadius.circular(24),
            border: Border.all(color: AppColors.alertRed.withAlpha(120), width: 1.5),
            boxShadow: [
              BoxShadow(
                color: AppColors.alertRed.withAlpha(30),
                blurRadius: 20,
                offset: const Offset(0, 8),
              ),
            ],
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 72,
                height: 72,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppColors.alertRed.withAlpha(20),
                  border: Border.all(color: AppColors.alertRed.withAlpha(100)),
                ),
                child: const Center(
                  child: Icon(Icons.wifi_off_rounded,
                      size: 36, color: AppColors.alertRed),
                ),
              ),
              const SizedBox(height: 16),
              Text(
                'CONNECTION TIMEOUT',
                style: GoogleFonts.orbitron(
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  color: AppColors.alertRed,
                  letterSpacing: 0.8,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                vm.errorMessage ?? 'ไม่สามารถดึงข้อมูล telemetry ได้',
                style: GoogleFonts.rajdhani(
                  fontSize: 12.5,
                  color: const Color(0xFFCBD5E1),
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 20),
              ElevatedButton.icon(
                onPressed: () => vm.fetchTelemetry(silent: false),
                icon: const Icon(Icons.refresh_rounded, size: 18),
                label: Text(
                  'เชื่อมต่อใหม่ (RETRY)',
                  style: GoogleFonts.rajdhani(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.neonCyan,
                  foregroundColor: Colors.black,
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(14),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // ── Section Header ────────────────────────────────────────
  Widget _buildSectionHeader({
    required String title,
    required String subtitle,
    required Color accentColor,
    required double sw,
  }) {
    return Row(
      children: [
        Container(
          width: 4,
          height: 28,
          decoration: BoxDecoration(
            color: accentColor,
            borderRadius: BorderRadius.circular(2),
            boxShadow: [
              BoxShadow(
                color: accentColor.withAlpha(180),
                blurRadius: 8,
              ),
            ],
          ),
        ),
        const SizedBox(width: 10),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: GoogleFonts.rajdhani(
                fontSize: sw < 360 ? 15 : 17,
                fontWeight: FontWeight.w700,
                color: Colors.white,
                letterSpacing: 0.3,
              ),
            ),
            Text(
              subtitle,
              style: GoogleFonts.orbitron(
                fontSize: sw < 360 ? 8.5 : 9.5,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF64748B),
                letterSpacing: 0.8,
              ),
            ),
          ],
        ),
      ],
    );
  }

  // ── Relay Section Header ──────────────────────────────────
  Widget _buildRelayHeader(Map<int, dynamic> relays, double sw) {
    final activeCount = relays.values.where((r) => r.state == true).length;

    return Row(
      children: [
        Container(
          width: 4,
          height: 28,
          decoration: BoxDecoration(
            color: AppColors.neonGreen,
            borderRadius: BorderRadius.circular(2),
            boxShadow: [
              BoxShadow(
                color: AppColors.neonGreen.withAlpha(180),
                blurRadius: 8,
              ),
            ],
          ),
        ),
        const SizedBox(width: 10),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'ควบคุมรีเลย์ 4 ช่อง',
                style: GoogleFonts.rajdhani(
                  fontSize: sw < 360 ? 15 : 17,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                  letterSpacing: 0.3,
                ),
              ),
              Text(
                'HARDWARE RELAY CONTROL · ESP32 GPIO',
                style: GoogleFonts.orbitron(
                  fontSize: sw < 360 ? 8.5 : 9.5,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF64748B),
                  letterSpacing: 0.8,
                ),
              ),
            ],
          ),
        ),
        if (activeCount > 0)
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(
              color: AppColors.neonGreen.withAlpha(25),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: AppColors.neonGreen.withAlpha(120),
                width: 1.2,
              ),
              boxShadow: [
                BoxShadow(
                  color: AppColors.neonGreen.withAlpha(40),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Text(
              '$activeCount ACTIVE',
              style: GoogleFonts.orbitron(
                fontSize: sw < 360 ? 9.5 : 10.5,
                fontWeight: FontWeight.w800,
                color: AppColors.neonGreen,
                letterSpacing: 0.5,
              ),
            ),
          ),
      ],
    );
  }

  List<Widget> _buildRelayList(
      Map<int, dynamic> relays, TelemetryViewModel vm) {
    return List.generate(4, (i) {
      final id = i + 1;
      if (!relays.containsKey(id)) return const SizedBox.shrink();
      return RelaySwitchCard(
        relay: relays[id]!,
        onToggle: () => vm.toggleRelay(id),
        icon: _relayIcons[i],
      );
    });
  }

  // ── Relay Status Summary ──────────────────────────────────
  Widget _buildRelayStatusSummary(Map<int, dynamic> relays, double sw) {
    final names = ['ปั๊มน้ำ', 'สปริงเกลอร์', 'หลอดโต', 'พัดลม'];
    final active = <String>[];
    for (int i = 0; i < 4; i++) {
      final id = i + 1;
      if (relays.containsKey(id) && relays[id]!.state == true) {
        active.add('${_relayEmojis[i]} ${names[i]}');
      }
    }

    if (active.isEmpty) return const SizedBox.shrink();

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: sw < 360 ? 12 : 16,
        vertical: 10,
      ),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: AppColors.neonGreen.withAlpha(120),
          width: 1.2,
        ),
        boxShadow: [
          BoxShadow(
            color: AppColors.neonGreen.withAlpha(25),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          const Icon(Icons.bolt_rounded, color: AppColors.neonGreen, size: 18),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              'กำลังทำงาน: ${active.join('  •  ')}',
              style: GoogleFonts.rajdhani(
                fontSize: sw < 360 ? 12 : 13.5,
                fontWeight: FontWeight.w700,
                color: AppColors.neonGreen,
                letterSpacing: 0.3,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ── Connection Banner ─────────────────────────────────────
  Widget _buildConnectionBanner(
      TelemetryViewModel vm, dynamic telemetry, double sw) {
    final board = telemetry.board;
    final isOnline = vm.errorMessage == null;
    final statusColor = isOnline ? AppColors.neonGreen : AppColors.alertRed;

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: sw < 360 ? 12 : 16,
        vertical: sw < 360 ? 10 : 13,
      ),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E), // Obsidian Slate Card
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: const Color(0xFF1B2842),
          width: 1.4,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x60000000),
            blurRadius: 16,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: Row(
        children: [
          // Glowing status LED indicator
          Container(
            width: 10,
            height: 10,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: statusColor,
              boxShadow: [
                BoxShadow(
                  color: statusColor.withAlpha(200),
                  blurRadius: 8,
                  spreadRadius: 1,
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),

          // Board info
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  '${board.ssid}  ·  ${board.ipAddress}:${board.webPort}',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 10.5 : 12,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                    letterSpacing: 0.4,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  isOnline
                      ? 'ESP32-S3 ONLINE · ${board.status}'
                      : 'OFFLINE · ตรวจสอบการเชื่อมต่อ WI-FI',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 11 : 12,
                    color: statusColor,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 0.5,
                  ),
                ),
              ],
            ),
          ),

          // RSSI + latency chip
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: AppColors.neonCyan.withAlpha(20),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(
                    color: AppColors.neonCyan.withAlpha(80),
                    width: 1,
                  ),
                ),
                child: Text(
                  '${board.rssi} dBm',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 9.5 : 11,
                    fontWeight: FontWeight.w700,
                    color: AppColors.neonCyanLight,
                  ),
                ),
              ),
              if (vm.boardLatencyMs != null) ...[
                const SizedBox(height: 3),
                Text(
                  '⚡ ${vm.boardLatencyMs}ms',
                  style: GoogleFonts.orbitron(
                    fontSize: 9.5,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xFF8EA2C6),
                  ),
                ),
              ],
            ],
          ),
        ],
      ),
    );
  }

  // ── GPS Pill ──────────────────────────────────────────────
  Widget _buildGpsPill(dynamic telemetry, double sw) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: sw < 360 ? 12 : 16,
        vertical: sw < 360 ? 9 : 11,
      ),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: const Color(0xFF1B2842),
          width: 1.2,
        ),
      ),
      child: Row(
        children: [
          const Icon(Icons.location_on_rounded, color: AppColors.neonCyan, size: 16),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              '${telemetry.gps.locationName}  (${telemetry.gps.formatted})',
              style: GoogleFonts.rajdhani(
                fontSize: sw < 360 ? 11 : 12.5,
                fontWeight: FontWeight.w700,
                color: AppColors.neonCyanLight,
                letterSpacing: 0.3,
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// 🌌 _CyberPulseLoader — Cyber HUD Pulsing Circle
// ─────────────────────────────────────────────────────────────
class _CyberPulseLoader extends StatefulWidget {
  const _CyberPulseLoader();
  @override
  State<_CyberPulseLoader> createState() => _CyberPulseLoaderState();
}

class _CyberPulseLoaderState extends State<_CyberPulseLoader>
    with SingleTickerProviderStateMixin {
  late AnimationController _ctrl;

  @override
  void initState() {
    super.initState();
    _ctrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1400),
    )..repeat();
  }

  @override
  void dispose() {
    _ctrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return RotationTransition(
      turns: _ctrl,
      child: Container(
        width: 58,
        height: 58,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          gradient: const SweepGradient(
            colors: [
              Colors.transparent,
              AppColors.neonCyan,
              AppColors.neonGreen,
            ],
          ),
          boxShadow: [
            BoxShadow(
              color: AppColors.neonCyan.withAlpha(80),
              blurRadius: 16,
            ),
          ],
        ),
        child: Padding(
          padding: const EdgeInsets.all(4.0),
          child: Container(
            decoration: const BoxDecoration(
              shape: BoxShape.circle,
              color: Color(0xFF090D16),
            ),
            child: const Center(
              child: Icon(
                Icons.hub_rounded,
                color: AppColors.neonCyan,
                size: 24,
              ),
            ),
          ),
        ),
      ),
    );
  }
}
