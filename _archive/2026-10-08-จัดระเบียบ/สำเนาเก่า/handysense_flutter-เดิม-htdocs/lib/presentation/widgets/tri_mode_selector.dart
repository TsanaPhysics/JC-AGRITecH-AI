import 'dart:math' as math;
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../core/theme/app_theme.dart';

// ─────────────────────────────────────────────────────────────
// 🎛️ TriModeSelector — Sci-Fi Cyber Rotary Dial & Tactical Mode
// ─────────────────────────────────────────────────────────────
class TriModeSelector extends StatefulWidget {
  final String currentMode;
  final ValueChanged<String> onModeChanged;

  const TriModeSelector({
    super.key,
    required this.currentMode,
    required this.onModeChanged,
  });

  @override
  State<TriModeSelector> createState() => _TriModeSelectorState();
}

class _TriModeSelectorState extends State<TriModeSelector>
    with SingleTickerProviderStateMixin {
  late AnimationController _rotaryCtrl;
  late Animation<double> _angleAnim;
  double _currentAngle = 0.0;

  // Angles relative to 12 o'clock (-math.pi / 2)
  static const double _angleManual = -0.785; // -45 deg
  static const double _angleAuto   = 0.0;    // 0 deg
  static const double _angleAi     = 0.785;  // +45 deg

  static const _modes = ['manual', 'auto', 'ai'];

  double _getAngleForMode(String mode) {
    switch (mode.toLowerCase()) {
      case 'manual':
        return _angleManual;
      case 'ai':
        return _angleAi;
      default:
        return _angleAuto;
    }
  }

  Color _getColorForMode(String mode) {
    switch (mode.toLowerCase()) {
      case 'manual':
        return AppColors.neonOrange;
      case 'ai':
        return AppColors.neonCyan;
      default:
        return AppColors.neonGreen;
    }
  }

  @override
  void initState() {
    super.initState();
    _currentAngle = _getAngleForMode(widget.currentMode);
    _rotaryCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 320),
    );
    _angleAnim = Tween<double>(begin: _currentAngle, end: _currentAngle)
        .animate(CurvedAnimation(parent: _rotaryCtrl, curve: Curves.easeInOutCubic));
  }

  @override
  void didUpdateWidget(TriModeSelector old) {
    super.didUpdateWidget(old);
    if (old.currentMode != widget.currentMode) {
      final targetAngle = _getAngleForMode(widget.currentMode);
      _angleAnim = Tween<double>(
        begin: _angleAnim.value,
        end: targetAngle,
      ).animate(CurvedAnimation(parent: _rotaryCtrl, curve: Curves.easeInOutCubic));
      _rotaryCtrl
        ..reset()
        ..forward();
      _currentAngle = targetAngle;
    }
  }

  @override
  void dispose() {
    _rotaryCtrl.dispose();
    super.dispose();
  }

  void _cycleNextMode() {
    HapticFeedback.mediumImpact();
    final curIdx = _modes.indexOf(widget.currentMode.toLowerCase());
    final nextIdx = (curIdx + 1) % _modes.length;
    widget.onModeChanged(_modes[nextIdx]);
  }

  void _selectMode(String mode) {
    if (widget.currentMode.toLowerCase() == mode.toLowerCase()) return;
    HapticFeedback.mediumImpact();
    widget.onModeChanged(mode);
  }

  @override
  Widget build(BuildContext context) {
    final mode = widget.currentMode.toLowerCase();
    final sw = MediaQuery.of(context).size.width;
    final isSmall = sw < 360;
    final activeColor = _getColorForMode(mode);

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: isSmall ? 12 : 16,
        vertical: isSmall ? 12 : 16,
      ),
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
            offset: Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Header (Overflow Protected with Expanded) ──
          Row(
            children: [
              Container(
                width: 32,
                height: 32,
                decoration: BoxDecoration(
                  color: const Color(0xFF16233B),
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(
                    color: const Color(0xFF263B60),
                    width: 1,
                  ),
                ),
                child: const Icon(
                  Icons.tune_rounded,
                  color: AppColors.neonCyan,
                  size: 17,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      'โหมดควบคุมระบบ',
                      style: GoogleFonts.rajdhani(
                        fontSize: isSmall ? 14 : 16,
                        fontWeight: FontWeight.w700,
                        color: Colors.white,
                        letterSpacing: 0.3,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    Text(
                      'ROTARY MODE SELECTOR',
                      style: GoogleFonts.orbitron(
                        fontSize: isSmall ? 8.0 : 9.0,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF64748B),
                        letterSpacing: 0.6,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 8),
              _buildActiveBadge(mode, isSmall),
            ],
          ),

          const SizedBox(height: 14),

          // ── Cyber Rotary Dial + Tactical Visual Selector ──
          AnimatedBuilder(
            animation: _angleAnim,
            builder: (context, _) {
              return Center(
                child: GestureDetector(
                  onTap: _cycleNextMode,
                  child: SizedBox(
                    width: 130,
                    height: 98,
                    child: CustomPaint(
                      painter: _CyberRotaryKnobPainter(
                        angle: _angleAnim.value,
                        activeColor: activeColor,
                        selectedMode: mode,
                      ),
                    ),
                  ),
                ),
              );
            },
          ),

          const SizedBox(height: 8),

          // ── 3 Tactical Push Buttons Tray (100% Overflow Protected) ──
          Container(
            padding: const EdgeInsets.all(4),
            decoration: BoxDecoration(
              color: const Color(0xFF0A101D), // Dark bezel tray
              borderRadius: BorderRadius.circular(18),
              border: Border.all(
                color: const Color(0xFF1B2842),
                width: 1,
              ),
            ),
            child: Row(
              children: [
                _buildModeButton(
                  title: 'Manual',
                  subtitle: 'ควบคุมเอง',
                  emoji: '🕹️',
                  modeKey: 'manual',
                  activeGrad: [const Color(0xFFFF7A00), const Color(0xFFFF5722)],
                  glowColor: AppColors.neonOrange,
                  isSelected: mode == 'manual',
                  isSmall: isSmall,
                ),
                _buildModeButton(
                  title: 'Auto',
                  subtitle: 'อัตโนมัติ',
                  emoji: '🤖',
                  modeKey: 'auto',
                  activeGrad: [const Color(0xFF00E676), const Color(0xFF00A844)],
                  glowColor: AppColors.neonGreen,
                  isSelected: mode == 'auto',
                  isSmall: isSmall,
                ),
                _buildModeButton(
                  title: 'Edge AI',
                  subtitle: 'สมาร์ท AI',
                  emoji: '✨',
                  modeKey: 'ai',
                  activeGrad: [const Color(0xFF00C6FF), const Color(0xFF0072FF)],
                  glowColor: AppColors.neonCyan,
                  isSelected: mode == 'ai',
                  isSmall: isSmall,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ── Compact Active Badge ──
  Widget _buildActiveBadge(String mode, bool isSmall) {
    Color color;
    String label;
    IconData icon;

    switch (mode) {
      case 'ai':
        color = AppColors.neonCyan;
        label = 'EDGE AI';
        icon = Icons.auto_awesome_rounded;
        break;
      case 'auto':
        color = AppColors.neonGreen;
        label = 'AUTO';
        icon = Icons.smart_toy_rounded;
        break;
      default:
        color = AppColors.neonOrange;
        label = 'MANUAL';
        icon = Icons.sports_esports_rounded;
    }

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: isSmall ? 7 : 9,
        vertical: 3.5,
      ),
      decoration: BoxDecoration(
        color: color.withAlpha(20),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: color.withAlpha(120), width: 1.2),
        boxShadow: [
          BoxShadow(
            color: color.withAlpha(35),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: isSmall ? 11 : 13, color: color),
          const SizedBox(width: 4),
          Text(
            label,
            style: GoogleFonts.orbitron(
              fontSize: isSmall ? 8.5 : 9.5,
              fontWeight: FontWeight.w700,
              color: color,
              letterSpacing: 0.5,
            ),
          ),
        ],
      ),
    );
  }

  // ── Tactical Mode Push Button ──
  Widget _buildModeButton({
    required String title,
    required String subtitle,
    required String emoji,
    required String modeKey,
    required List<Color> activeGrad,
    required Color glowColor,
    required bool isSelected,
    required bool isSmall,
  }) {
    return Expanded(
      child: GestureDetector(
        onTap: () => _selectMode(modeKey),
        behavior: HitTestBehavior.opaque,
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          curve: Curves.easeInOutCubic,
          margin: const EdgeInsets.symmetric(horizontal: 2, vertical: 2),
          padding: EdgeInsets.symmetric(
            vertical: isSmall ? 7 : 9,
            horizontal: 3,
          ),
          decoration: isSelected
              ? BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: activeGrad,
                  ),
                  borderRadius: BorderRadius.circular(14),
                  boxShadow: [
                    BoxShadow(
                      color: glowColor.withAlpha(120),
                      blurRadius: 10,
                      offset: const Offset(0, 3),
                    ),
                  ],
                )
              : BoxDecoration(
                  color: const Color(0xFF131D31),
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(
                    color: const Color(0xFF1B2842),
                    width: 1,
                  ),
                ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                emoji,
                style: TextStyle(fontSize: isSmall ? 18 : 22),
              ),
              const SizedBox(height: 2),
              FittedBox(
                fit: BoxFit.scaleDown,
                child: Text(
                  title,
                  style: GoogleFonts.rajdhani(
                    fontSize: isSmall ? 12.0 : 13.5,
                    fontWeight: FontWeight.w700,
                    color: isSelected ? Colors.black : Colors.white,
                    letterSpacing: 0.2,
                  ),
                  maxLines: 1,
                  softWrap: false,
                ),
              ),
              FittedBox(
                fit: BoxFit.scaleDown,
                child: Text(
                  subtitle,
                  style: GoogleFonts.rajdhani(
                    fontSize: isSmall ? 9.0 : 10.0,
                    fontWeight: FontWeight.w600,
                    color: isSelected
                        ? Colors.black.withAlpha(180)
                        : const Color(0xFF8EA2C6),
                  ),
                  maxLines: 1,
                  softWrap: false,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// 🌌 _CyberRotaryKnobPainter — Tactical Sci-Fi Rotary Dial Painter
// ─────────────────────────────────────────────────────────────
class _CyberRotaryKnobPainter extends CustomPainter {
  final double angle; // Offset angle relative to 12 o'clock (-pi/2)
  final Color activeColor;
  final String selectedMode;

  _CyberRotaryKnobPainter({
    required this.angle,
    required this.activeColor,
    required this.selectedMode,
  });

  @override
  void paint(Canvas canvas, Size size) {
    final center = Offset(size.width / 2, size.height * 0.58);
    final outerRadius = size.height * 0.50;
    final knobRadius = outerRadius - 12.0;

    // 1. Draw Outer Arc Track (-140° to -40°)
    const baseAngle = -math.pi / 2; // 12 o'clock
    const arcSpan = 1.6; // Total arc span in radians (~92 degrees)
    final arcRect = Rect.fromCircle(center: center, radius: outerRadius);

    final trackPaint = Paint()
      ..color = const Color(0xFF172338)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 4.0
      ..strokeCap = StrokeCap.round;

    canvas.drawArc(
      arcRect,
      baseAngle - (arcSpan / 2),
      arcSpan,
      false,
      trackPaint,
    );

    // 2. Draw 3 Tactical Detent Marks (Manual, Auto, AI)
    final detents = [
      {'angle': baseAngle - 0.785, 'color': AppColors.neonOrange, 'key': 'manual'},
      {'angle': baseAngle,         'color': AppColors.neonGreen,  'key': 'auto'},
      {'angle': baseAngle + 0.785, 'color': AppColors.neonCyan,   'key': 'ai'},
    ];

    for (final detent in detents) {
      final a = detent['angle'] as double;
      final c = detent['color'] as Color;
      final isCur = detent['key'] == selectedMode;

      final p1 = Offset(
        center.dx + (outerRadius - 2) * math.cos(a),
        center.dy + (outerRadius - 2) * math.sin(a),
      );
      final p2 = Offset(
        center.dx + (outerRadius + 5) * math.cos(a),
        center.dy + (outerRadius + 5) * math.sin(a),
      );

      final tickPaint = Paint()
        ..color = isCur ? c : const Color(0xFF263B60)
        ..style = PaintingStyle.stroke
        ..strokeWidth = isCur ? 3.0 : 2.0
        ..strokeCap = StrokeCap.round;

      if (isCur) {
        // Glow on active detent
        final glow = Paint()
          ..color = c.withAlpha(160)
          ..strokeWidth = 5.0
          ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 3.0);
        canvas.drawLine(p1, p2, glow);
      }

      canvas.drawLine(p1, p2, tickPaint);

      // Detent dot
      final dotPos = Offset(
        center.dx + (outerRadius + 8) * math.cos(a),
        center.dy + (outerRadius + 8) * math.sin(a),
      );
      canvas.drawCircle(
        dotPos,
        isCur ? 3.0 : 2.0,
        Paint()..color = isCur ? c : const Color(0xFF263B60),
      );
    }

    // 3. Draw Metallic Rotary Knob Body
    final knobRect = Rect.fromCircle(center: center, radius: knobRadius);

    // Bevel shadow
    canvas.drawCircle(
      center + const Offset(0, 3),
      knobRadius,
      Paint()..color = Colors.black.withAlpha(120),
    );

    // Knob Face Gradient
    final knobPaint = Paint()
      ..shader = RadialGradient(
        colors: [
          const Color(0xFF1E2E4A),
          const Color(0xFF0F1829),
          const Color(0xFF090F1C),
        ],
        stops: const [0.0, 0.7, 1.0],
      ).createShader(knobRect);
    canvas.drawCircle(center, knobRadius, knobPaint);

    // Inner Concentric Milled Ring
    final groovePaint = Paint()
      ..color = const Color(0xFF1D2E49)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.4;
    canvas.drawCircle(center, knobRadius * 0.72, groovePaint);

    // Knob Outer Rim Border
    final rimPaint = Paint()
      ..color = const Color(0xFF263B60)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.6;
    canvas.drawCircle(center, knobRadius, rimPaint);

    // 4. Rotating Pointer Indicator Line & Glowing Notch
    final currentHeading = baseAngle + angle;
    final pointerStart = Offset(
      center.dx + (knobRadius * 0.28) * math.cos(currentHeading),
      center.dy + (knobRadius * 0.28) * math.sin(currentHeading),
    );
    final pointerEnd = Offset(
      center.dx + (knobRadius - 2) * math.cos(currentHeading),
      center.dy + (knobRadius - 2) * math.sin(currentHeading),
    );

    // Pointer Neon Glow Pass
    final pointerGlow = Paint()
      ..color = activeColor.withAlpha(160)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 4.5
      ..strokeCap = StrokeCap.round
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 4.0);
    canvas.drawLine(pointerStart, pointerEnd, pointerGlow);

    // Pointer Sharp Core
    final pointerCore = Paint()
      ..color = Colors.white
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2.2
      ..strokeCap = StrokeCap.round;
    canvas.drawLine(pointerStart, pointerEnd, pointerCore);

    // Pointer Tip Glowing Dot
    final tipGlow = Paint()
      ..color = activeColor
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 3.0);
    canvas.drawCircle(pointerEnd, 3.5, tipGlow);
    canvas.drawCircle(pointerEnd, 2.0, Paint()..color = Colors.white);

    // 5. Center Hub Cap
    final hubRadius = knobRadius * 0.30;
    canvas.drawCircle(
      center,
      hubRadius,
      Paint()..color = const Color(0xFF090E18),
    );

    // Glowing LED core at center
    final ledGlow = Paint()
      ..color = activeColor.withAlpha(140)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 3.5);
    canvas.drawCircle(center, 4.5, ledGlow);
    canvas.drawCircle(center, 2.5, Paint()..color = Colors.white);
  }

  @override
  bool shouldRepaint(covariant _CyberRotaryKnobPainter oldDelegate) {
    return oldDelegate.angle != angle ||
        oldDelegate.activeColor != activeColor ||
        oldDelegate.selectedMode != selectedMode;
  }
}
