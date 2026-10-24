import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/common.dart';
import '../widgets/sensor_gauge_card.dart';

// ─────────────────────────────────────────────────────────────
// 🌌 DashboardScreen — Cyber Dark Sci-Fi IoT Farm Dashboard
// ─────────────────────────────────────────────────────────────
class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

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
    
    final isLargeScreen = sw > 650;
    final isTablet = sw > 500;
    final hPad = sw < 360 ? 12.0 : sw < 420 ? 16.0 : 20.0;
    final gridCols = isLargeScreen ? 4 : 2;
    // Calibrated for circular HUD dials:
    final gridRatio = isLargeScreen ? 0.95 : (isTablet ? 0.90 : 0.74);

    final profile = vm.profile;

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
                _buildConnectionBanner(vm, telemetry, sw),
                const SizedBox(height: 14),
                _buildRunningSummary(context, vm),
                const SizedBox(height: 20),
                SectionTitle(
                  'สภาพแวดล้อมและดิน',
                  subtitle: 'เกณฑ์เทียบกับโปรไฟล์พืช: ${profile.name}',
                  trailing: Text(
                    'อัปเดต ${fmtAge(vm.dataAgeSeconds)}',
                    style: thText(12, color: AppColors.textMuted),
                  ),
                ),
                const SizedBox(height: 12),
                GridView.count(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisCount: gridCols,
                  crossAxisSpacing: 10,
                  mainAxisSpacing: 10,
                  childAspectRatio: gridRatio,
                  children: [
                    _gauge(vm, key: 'temp', label: 'อุณหภูมิอากาศ', value: sensors.temperature, unit: '°C',
                        decimals: 1, maxScale: 50, icon: Icons.thermostat_rounded, accent: const Color(0xFFFF7A00)),
                    _gauge(vm, key: 'humidity', label: 'ความชื้นอากาศ', value: sensors.humidity, unit: '%',
                        decimals: 1, maxScale: 100, icon: Icons.water_drop_rounded, accent: const Color(0xFF00C6FF)),
                    _gauge(vm, key: 'soil_moisture', label: 'ความชื้นดิน', value: sensors.soilMoisture, unit: '%',
                        decimals: 1, maxScale: 100, icon: Icons.grass_rounded, accent: const Color(0xFF00E676)),
                    _gauge(vm, key: 'vpd', label: 'VPD', value: sensors.vpd, unit: 'kPa',
                        decimals: 2, maxScale: 2.5, icon: Icons.cloud_sync_rounded, accent: const Color(0xFF7C4DFF)),
                    _gauge(vm, key: 'soil_ph', label: 'ความเป็นกรด-ด่างของดิน', value: sensors.soilPh, unit: 'pH',
                        decimals: 1, maxScale: 14, icon: Icons.science_rounded, accent: const Color(0xFFFFD600)),
                    SensorGaugeCard(
                      label: 'ความเข้มแสง',
                      value: !_hasData(vm)
                          ? '--'
                          : sensors.parLux >= 1000
                              ? (sensors.parLux / 1000).toStringAsFixed(1)
                              : sensors.parLux.toStringAsFixed(0),
                      unit: sensors.parLux >= 1000 ? 'kLux' : 'Lux',
                      statusText: !_hasData(vm)
                          ? 'ไม่มีข้อมูล'
                          : sensors.parLux < 500
                              ? 'แสงน้อย'
                              : sensors.parLux <= 50000
                                  ? 'เหมาะสม'
                                  : 'แสงจัด',
                      icon: Icons.wb_sunny_rounded,
                      accentColor: const Color(0xFFFF4081),
                      statusColor: const Color(0xFFFF80AB),
                      progress: (sensors.parLux / 80000.0).clamp(0.0, 1.0),
                    ),
                  ],
                ),
                const SizedBox(height: 20),
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

  bool _hasData(TelemetryViewModel vm) => (vm.telemetry?.sensors.updatedAt ?? '').isNotEmpty;

  /// เกจเซนเซอร์ที่อิงเกณฑ์ของโปรไฟล์พืช (ไม่ใช้ค่าเป้าหมายตายตัวในโค้ด)
  Widget _gauge(
    TelemetryViewModel vm, {
    required String key,
    required String label,
    required double value,
    required String unit,
    required int decimals,
    required double maxScale,
    required IconData icon,
    required Color accent,
  }) {
    final range = vm.profile.thresholds[key];
    final has = _hasData(vm);
    String status;
    Color statusColor;
    if (!has) {
      status = 'ไม่มีข้อมูล';
      statusColor = AppColors.textDim;
    } else if (range == null) {
      status = '-';
      statusColor = AppColors.textMuted;
    } else if (value < range.min) {
      status = 'ต่ำกว่าเกณฑ์';
      statusColor = AppColors.alertAmber;
    } else if (value > range.max) {
      status = 'สูงกว่าเกณฑ์';
      statusColor = AppColors.alertAmber;
    } else {
      status = 'เหมาะสม';
      statusColor = AppColors.neonGreen;
    }
    return SensorGaugeCard(
      label: label,
      value: has ? value.toStringAsFixed(decimals) : '--',
      unit: unit,
      subTarget: range?.mid.toStringAsFixed(decimals),
      statusText: status,
      icon: icon,
      accentColor: accent,
      statusColor: statusColor,
      progress: (value / maxScale).clamp(0.0, 1.0),
    );
  }

  /// สรุปรีเลย์ที่กำลังทำงาน (ชื่อจริงจากเซิร์ฟเวอร์) + ทางลัดไปหน้าควบคุม
  Widget _buildRunningSummary(BuildContext context, TelemetryViewModel vm) {
    final running = [
      for (int i = 1; i <= 4; i++)
        if (vm.relayView(i).on) vm.relayView(i)
    ];
    return AppCard(
      borderColor: running.isEmpty ? AppColors.borderDark : AppColors.neonGreen.withAlpha(120),
      child: Row(
        children: [
          Icon(running.isEmpty ? Icons.power_off_rounded : Icons.bolt_rounded,
              color: running.isEmpty ? AppColors.textDim : AppColors.neonGreen),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(running.isEmpty ? 'ไม่มีรีเลย์ที่กำลังทำงาน' : 'กำลังทำงาน ${running.length} ช่อง',
                    style: thText(14.5, weight: FontWeight.w700, color: AppColors.textWhite)),
                if (running.isNotEmpty)
                  Text(
                    running
                        .map((r) => relayShortName(r.item.name) +
                            (r.timerLeftSec != null ? ' (อีก ${fmtCountdown(r.timerLeftSec!)})' : ''))
                        .join(' · '),
                    style: thText(12.5, color: AppColors.neonGreen),
                  ),
              ],
            ),
          ),
        ],
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
            'กำลังเชื่อมต่อเซิร์ฟเวอร์ Edge xAI : LEQs-4D-AgriEnvi',
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


  // ── Connection Banner ─────────────────────────────────────
  Widget _buildConnectionBanner(
      TelemetryViewModel vm, dynamic telemetry, double sw) {
    final board = telemetry.board;
    final cs = vm.connState;
    final isOnline = cs == ConnState.live;
    final style = connStyle(cs);
    final statusColor = style.color;

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
                      ? 'ESP32-S3 ${style.label} · ${board.status}'
                      : '${style.label} · ข้อมูลล่าสุด ${fmtAge(vm.dataAgeSeconds)}',
                  style: thText(sw < 360 ? 11.5 : 12.5, weight: FontWeight.w700, color: statusColor),
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
