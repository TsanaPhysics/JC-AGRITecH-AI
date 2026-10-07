import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';

// ─────────────────────────────────────────────────────────────
// 🌍 SoilAnalyticsScreen — Cyber Dark Sci-Fi Soil Analytics
// ─────────────────────────────────────────────────────────────
class SoilAnalyticsScreen extends StatelessWidget {
  const SoilAnalyticsScreen({super.key});

  // ── NPK Classification ────────────────────────────────────
  static const Map<String, List<Map<String, dynamic>>> _levels = {
    'N': [
      {'max': 20.0,  'label': 'CRITICAL LOW', 'emoji': '🔴', 'color': 0xFFFF3366},
      {'max': 60.0,  'label': 'LOW',          'emoji': '🟠', 'color': 0xFFFF9900},
      {'max': 120.0, 'label': 'OPTIMAL',      'emoji': '🟢', 'color': 0xFF00E676},
      {'max': 160.0, 'label': 'HIGH',         'emoji': '🔵', 'color': 0xFF00C6FF},
      {'max': 999.0, 'label': 'VERY HIGH',    'emoji': '🟣', 'color': 0xFF7C4DFF},
    ],
    'P': [
      {'max': 10.0,  'label': 'CRITICAL LOW', 'emoji': '🔴', 'color': 0xFFFF3366},
      {'max': 25.0,  'label': 'LOW',          'emoji': '🟠', 'color': 0xFFFF9900},
      {'max': 50.0,  'label': 'OPTIMAL',      'emoji': '🟢', 'color': 0xFF00E676},
      {'max': 75.0,  'label': 'HIGH',         'emoji': '🔵', 'color': 0xFF00C6FF},
      {'max': 999.0, 'label': 'VERY HIGH',    'emoji': '🟣', 'color': 0xFF7C4DFF},
    ],
    'K': [
      {'max': 50.0,  'label': 'CRITICAL LOW', 'emoji': '🔴', 'color': 0xFFFF3366},
      {'max': 100.0, 'label': 'LOW',          'emoji': '🟠', 'color': 0xFFFF9900},
      {'max': 180.0, 'label': 'OPTIMAL',      'emoji': '🟢', 'color': 0xFF00E676},
      {'max': 240.0, 'label': 'HIGH',         'emoji': '🔵', 'color': 0xFF00C6FF},
      {'max': 999.0, 'label': 'VERY HIGH',    'emoji': '🟣', 'color': 0xFF7C4DFF},
    ],
  };

  static const Map<String, double> _maxValues = {
    'N': 200.0,
    'P': 100.0,
    'K': 300.0,
  };

  Map<String, dynamic> _classify(String key, double value) {
    final list = _levels[key]!;
    return list.firstWhere(
      (l) => value <= (l['max'] as double),
      orElse: () => list.last,
    );
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final telemetry = vm.telemetry;
    final sw = MediaQuery.of(context).size.width;

    if (telemetry == null) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(Icons.biotech_rounded, size: 52, color: AppColors.neonCyan),
            const SizedBox(height: 16),
            Text(
              'ANALYZING SOIL PARAMETERS...',
              style: GoogleFonts.orbitron(
                fontSize: 15,
                fontWeight: FontWeight.w700,
                color: AppColors.neonCyan,
                letterSpacing: 0.8,
              ),
            ),
            const SizedBox(height: 8),
            const CircularProgressIndicator(color: AppColors.neonCyan),
          ],
        ),
      );
    }

    final s = telemetry.sensors;
    final ai = telemetry.aiCalibrated;
    final hPad = sw < 360 ? 12.0 : sw < 420 ? 16.0 : 20.0;

    return SingleChildScrollView(
      padding: EdgeInsets.symmetric(horizontal: hPad, vertical: 16),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 850),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Hero Header ──
              _buildHeroHeader(ai, sw),
              const SizedBox(height: 20),

              // ── NPK Hero Card ──
              _buildNpkCard(s, ai, sw),
              const SizedBox(height: 18),

              // ── NPK Level Legend ──
              _buildLegend(sw),
              const SizedBox(height: 22),

              // ── Soil Properties Section Header ──
              Row(
                children: [
                  Container(
                    width: 4,
                    height: 28,
                    decoration: BoxDecoration(
                      color: AppColors.neonCyan,
                      borderRadius: BorderRadius.circular(2),
                      boxShadow: [
                        BoxShadow(
                          color: AppColors.neonCyan.withAlpha(180),
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
                          'คุณสมบัติทางเคมีและกายภาพของดิน',
                          style: GoogleFonts.rajdhani(
                            fontSize: sw < 360 ? 15 : 17,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                            letterSpacing: 0.3,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        Text(
                          'SOIL CHEMICAL & PHYSICAL PROPERTIES',
                          style: GoogleFonts.orbitron(
                            fontSize: sw < 360 ? 8.5 : 9.5,
                            fontWeight: FontWeight.w700,
                            color: const Color(0xFF64748B),
                            letterSpacing: 0.8,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // ── Property Cards Grid ──
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: sw > 500 ? 4 : 2,
                crossAxisSpacing: 10,
                mainAxisSpacing: 10,
                childAspectRatio: sw > 500 ? 0.95 : 1.05,
                children: [
                  _buildPropertyCard(
                    icon: Icons.science_rounded,
                    title: 'ความเป็นกรด-ด่าง',
                    subtitle: 'Soil pH',
                    value: s.soilPh.toStringAsFixed(1),
                    unit: 'pH',
                    subInfo: 'AI: ${ai.ph.toStringAsFixed(1)}',
                    accentColor: AppColors.neonPurple,
                    sw: sw,
                  ),
                  _buildPropertyCard(
                    icon: Icons.bolt_rounded,
                    title: 'การนำไฟฟ้าดิน',
                    subtitle: 'Soil EC',
                    value: s.soilEc.toStringAsFixed(0),
                    unit: 'µS/cm',
                    subInfo: s.soilEc > 500 ? '⚠️ High Salinity' : 'Optimal',
                    accentColor: AppColors.neonAmber,
                    sw: sw,
                  ),
                  _buildPropertyCard(
                    icon: Icons.water_drop_rounded,
                    title: 'ความชื้นในดิน',
                    subtitle: 'Moisture',
                    value: s.soilMoisture.toStringAsFixed(1),
                    unit: '%',
                    subInfo: 'Depth 15–20 cm',
                    accentColor: AppColors.neonCyan,
                    sw: sw,
                  ),
                  _buildPropertyCard(
                    icon: Icons.thermostat_rounded,
                    title: 'อุณหภูมิดิน',
                    subtitle: 'Soil Temp',
                    value: s.soilTemperature.toStringAsFixed(1),
                    unit: '°C',
                    subInfo: 'Root Zone',
                    accentColor: AppColors.neonOrange,
                    sw: sw,
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // ── AI Insight Banner ──
              _buildAiInsightBanner(ai, sw),
              const SizedBox(height: 16),
            ],
          ),
        ),
      ),
    );
  }

  // ── Hero Header ───────────────────────────────────────────
  Widget _buildHeroHeader(dynamic ai, double sw) {
    return Container(
      padding: EdgeInsets.all(sw < 360 ? 14 : 18),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E), // Obsidian Slate Card
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: AppColors.neonGreen.withAlpha(120),
          width: 1.4,
        ),
        boxShadow: [
          BoxShadow(
            color: AppColors.neonGreen.withAlpha(20),
            blurRadius: 18,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: sw < 360 ? 50 : 58,
            height: sw < 360 ? 50 : 58,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(16),
              color: const Color(0xFF0E1626),
              border: Border.all(
                color: AppColors.neonGreen.withAlpha(100),
                width: 1.5,
              ),
            ),
            child: const Center(
              child: Icon(Icons.psychology_rounded,
                  color: AppColors.neonGreen, size: 30),
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Wrap(
                  spacing: 6,
                  runSpacing: 4,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 7, vertical: 2.5),
                      decoration: BoxDecoration(
                        color: AppColors.neonGreen.withAlpha(20),
                        borderRadius: BorderRadius.circular(6),
                        border: Border.all(
                          color: AppColors.neonGreen.withAlpha(80),
                          width: 1,
                        ),
                      ),
                      child: Text(
                        'EDGE AI ENGINE',
                        style: GoogleFonts.orbitron(
                          fontSize: 8.5,
                          fontWeight: FontWeight.w700,
                          color: AppColors.neonGreen,
                          letterSpacing: 0.5,
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      'OLS REGRESSION',
                      style: GoogleFonts.orbitron(
                        fontSize: 8.5,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF8EA2C6),
                        letterSpacing: 0.5,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Text(
                  'วิเคราะห์แร่ธาตุและสุขภาพดิน AIoT',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 15 : 18,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                    letterSpacing: 0.3,
                  ),
                ),
                Text(
                  'ความแม่นยำ AI Calibrated ${(ai.confidence * 100).toStringAsFixed(1)}% · RBRU Model',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 11 : 12.5,
                    color: const Color(0xFF8EA2C6),
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ── NPK Card ──────────────────────────────────────────────
  Widget _buildNpkCard(dynamic s, dynamic ai, double sw) {
    return Container(
      padding: EdgeInsets.all(sw < 360 ? 14 : 18),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E), // Obsidian Slate Card
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: const Color(0xFF1B2842),
          width: 1.4,
        ),
        boxShadow: const [
          BoxShadow(
            color: Color(0x60000000),
            blurRadius: 18,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        children: [
          // Header
          Row(
            children: [
              Expanded(
                child: Row(
                  children: [
                    const Icon(Icons.grain_rounded,
                        color: AppColors.neonCyan, size: 20),
                    const SizedBox(width: 8),
                    Flexible(
                      child: Text(
                        'สัดส่วนธาตุอาหารหลัก N-P-K',
                        style: GoogleFonts.rajdhani(
                          fontSize: sw < 360 ? 14 : 16,
                          fontWeight: FontWeight.w700,
                          color: Colors.white,
                          letterSpacing: 0.3,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
                decoration: BoxDecoration(
                  color: AppColors.neonCyan.withAlpha(20),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(
                    color: AppColors.neonCyan.withAlpha(80),
                    width: 1,
                  ),
                ),
                child: Text(
                  'RATIO: ${ai.npkRatio}',
                  style: GoogleFonts.orbitron(
                    fontSize: 9.5,
                    fontWeight: FontWeight.w700,
                    color: AppColors.neonCyanLight,
                    letterSpacing: 0.5,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // 3 NPK columns
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildNpkColumn(
                key: 'N',
                label: 'ไนโตรเจน (N)',
                symbol: 'N',
                rawValue: s.nitrogen,
                aiValue: ai.nitrogen,
                accentColor: AppColors.neonGreen,
                sw: sw,
              ),
              const SizedBox(width: 8),
              _buildNpkColumn(
                key: 'P',
                label: 'ฟอสฟอรัส (P)',
                symbol: 'P',
                rawValue: s.phosphorus,
                aiValue: ai.phosphorus,
                accentColor: AppColors.neonCyan,
                sw: sw,
              ),
              const SizedBox(width: 8),
              _buildNpkColumn(
                key: 'K',
                label: 'โพแทสเซียม (K)',
                symbol: 'K',
                rawValue: s.potassium,
                aiValue: ai.potassium,
                accentColor: AppColors.neonOrange,
                sw: sw,
              ),
            ],
          ),

          const SizedBox(height: 16),
          const Divider(color: Color(0xFF1B2842), height: 1),
          const SizedBox(height: 12),

          // Total NPK footer
          Row(
            children: [
              Expanded(
                child: Row(
                  children: [
                    const Icon(Icons.analytics_rounded,
                        color: AppColors.neonGreen, size: 18),
                    const SizedBox(width: 8),
                    Flexible(
                      child: Text(
                        'ปริมาณธาตุอาหารรวม (TOTAL NPK)',
                        style: GoogleFonts.orbitron(
                          fontSize: sw < 360 ? 9.5 : 10.5,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF8EA2C6),
                          letterSpacing: 0.5,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppColors.neonGreen.withAlpha(20),
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(
                    color: AppColors.neonGreen.withAlpha(100),
                    width: 1.2,
                  ),
                ),
                child: Text(
                  '${ai.npkTotal.toStringAsFixed(1)} mg/kg',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 12 : 13.5,
                    fontWeight: FontWeight.w800,
                    color: AppColors.neonGreen,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  // ── Single NPK Column ─────────────────────────────────────
  Widget _buildNpkColumn({
    required String key,
    required String label,
    required String symbol,
    required double rawValue,
    required double aiValue,
    required Color accentColor,
    required double sw,
  }) {
    final maxVal = _maxValues[key]!;
    final progress = (rawValue / maxVal).clamp(0.0, 1.0);
    final lv = _classify(key, rawValue);
    final lvColor = Color(lv['color'] as int);

    return Expanded(
      child: Container(
        padding: EdgeInsets.symmetric(
          horizontal: sw < 360 ? 8 : 10,
          vertical: sw < 360 ? 10 : 12,
        ),
        decoration: BoxDecoration(
          color: const Color(0xFF0A101D), // Dark dial fill
          borderRadius: BorderRadius.circular(18),
          border: Border.all(
            color: accentColor.withAlpha(80),
            width: 1.2,
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Text(
              symbol,
              style: GoogleFonts.orbitron(
                fontSize: sw < 360 ? 22 : 26,
                fontWeight: FontWeight.w800,
                color: accentColor,
                height: 1,
              ),
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: GoogleFonts.rajdhani(
                fontSize: sw < 360 ? 10 : 11.5,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF8EA2C6),
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 8),

            // Value
            FittedBox(
              fit: BoxFit.scaleDown,
              child: Text(
                rawValue.toStringAsFixed(1),
                style: GoogleFonts.orbitron(
                  fontSize: sw < 360 ? 20 : 24,
                  fontWeight: FontWeight.w800,
                  color: Colors.white,
                  height: 1,
                ),
              ),
            ),
            Text(
              'mg/kg',
              style: GoogleFonts.orbitron(
                fontSize: 8.5,
                color: accentColor,
              ),
            ),
            const SizedBox(height: 6),

            // AI badge
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(
                color: accentColor.withAlpha(20),
                borderRadius: BorderRadius.circular(6),
                border: Border.all(
                  color: accentColor.withAlpha(70),
                  width: 1,
                ),
              ),
              child: Text(
                'AI ${aiValue.toStringAsFixed(1)}',
                style: GoogleFonts.orbitron(
                  fontSize: 8.5,
                  fontWeight: FontWeight.w700,
                  color: accentColor,
                ),
              ),
            ),
            const SizedBox(height: 8),

            // Level bar
            ClipRRect(
              borderRadius: BorderRadius.circular(6),
              child: LinearProgressIndicator(
                value: progress,
                backgroundColor: const Color(0xFF172338),
                valueColor: AlwaysStoppedAnimation<Color>(lvColor),
                minHeight: 6,
              ),
            ),
            const SizedBox(height: 6),

            // Status pill
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2.5),
              decoration: BoxDecoration(
                color: lvColor.withAlpha(20),
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: lvColor.withAlpha(90), width: 1),
              ),
              child: Text(
                lv['label'] as String,
                style: GoogleFonts.orbitron(
                  fontSize: sw < 360 ? 7.5 : 8.5,
                  fontWeight: FontWeight.w700,
                  color: lvColor,
                ),
                textAlign: TextAlign.center,
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Legend ────────────────────────────────────────────────
  Widget _buildLegend(double sw) {
    const items = [
      {'label': 'CRIT LOW', 'color': 0xFFFF3366},
      {'label': 'LOW',      'color': 0xFFFF9900},
      {'label': 'OPTIMAL',  'color': 0xFF00E676},
      {'label': 'HIGH',     'color': 0xFF00C6FF},
      {'label': 'VERY HIGH','color': 0xFF7C4DFF},
    ];

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: const Color(0xFF0E1626),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFF1B2842), width: 1.2),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: items.map((item) {
          final c = Color(item['color'] as int);
          return Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 7,
                height: 7,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: c,
                  boxShadow: [
                    BoxShadow(color: c.withAlpha(180), blurRadius: 4),
                  ],
                ),
              ),
              const SizedBox(width: 4),
              Text(
                item['label'] as String,
                style: GoogleFonts.orbitron(
                  fontSize: sw < 360 ? 7.5 : 8.5,
                  fontWeight: FontWeight.w700,
                  color: c,
                ),
              ),
            ],
          );
        }).toList(),
      ),
    );
  }

  // ── Property Mini Cards ───────────────────────────────────
  Widget _buildPropertyCard({
    required IconData icon,
    required String title,
    required String subtitle,
    required String value,
    required String unit,
    required String subInfo,
    required Color accentColor,
    required double sw,
  }) {
    return Container(
      padding: EdgeInsets.all(sw < 360 ? 10 : 13),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E), // Obsidian Slate Card
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFF1B2842), width: 1.4),
        boxShadow: [
          BoxShadow(
            color: accentColor.withAlpha(15),
            blurRadius: 14,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Icon(icon, color: accentColor, size: 20),
              const SizedBox(width: 8),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      subtitle,
                      style: GoogleFonts.orbitron(
                        fontSize: 9.5,
                        fontWeight: FontWeight.w700,
                        color: accentColor,
                      ),
                    ),
                    Text(
                      title,
                      style: GoogleFonts.rajdhani(
                        fontSize: sw < 360 ? 10.5 : 12,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF8EA2C6),
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              ),
            ],
          ),

          // Big value
          FittedBox(
            fit: BoxFit.scaleDown,
            alignment: Alignment.centerLeft,
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  value,
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 22 : 26,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                    height: 1,
                  ),
                ),
                const SizedBox(width: 3),
                Padding(
                  padding: const EdgeInsets.only(top: 2),
                  child: Text(
                    unit,
                    style: GoogleFonts.orbitron(
                      fontSize: sw < 360 ? 9.5 : 10.5,
                      fontWeight: FontWeight.w700,
                      color: accentColor,
                    ),
                  ),
                ),
              ],
            ),
          ),

          // Sub info
          Text(
            subInfo,
            style: GoogleFonts.rajdhani(
              fontSize: sw < 360 ? 10 : 11.5,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF8EA2C6),
            ),
          ),
        ],
      ),
    );
  }

  // ── AI Insight Banner ─────────────────────────────────────
  Widget _buildAiInsightBanner(dynamic ai, double sw) {
    return Container(
      padding: EdgeInsets.all(sw < 360 ? 12 : 16),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: AppColors.neonPurple.withAlpha(120),
          width: 1.4,
        ),
        boxShadow: [
          BoxShadow(
            color: AppColors.neonPurple.withAlpha(20),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: AppColors.neonPurple.withAlpha(20),
              border: Border.all(color: AppColors.neonPurple.withAlpha(100)),
            ),
            child: const Center(
              child: Icon(Icons.memory_rounded,
                  color: AppColors.neonPurple, size: 24),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'TINYML EDGE AI REGRESSION INSIGHT',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 10 : 11,
                    fontWeight: FontWeight.w700,
                    color: AppColors.neonPurple,
                    letterSpacing: 0.5,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  'AI-calibrated regression ช่วยชดเชยค่าความคลาดเคลื่อนจากเซ็นเซอร์ RS485 • '
                  'ความแม่นยำ ${(ai.confidence * 100).toStringAsFixed(1)}% • '
                  'NPK ratio ${ai.npkRatio}',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 11 : 12.5,
                    color: const Color(0xFFCBD5E1),
                    height: 1.3,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
