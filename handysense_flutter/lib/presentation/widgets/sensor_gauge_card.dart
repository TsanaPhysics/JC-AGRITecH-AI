import 'dart:math' as math;
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

// ─────────────────────────────────────────────────────────────
// 🌌 SensorGaugeCard — Cyber Dark Sci-Fi IoT HUD Dial
// ─────────────────────────────────────────────────────────────
class SensorGaugeCard extends StatefulWidget {
  final String label;
  final String value;
  final String unit;
  final String statusText;
  final IconData icon;
  final Color accentColor;
  final double progress; // 0.0 – 1.0
  final String? subTarget; // e.g. "28.5", "65", "72.0", "0.95"
  final Color? statusColor;

  const SensorGaugeCard({
    super.key,
    required this.label,
    required this.value,
    required this.unit,
    required this.statusText,
    required this.icon,
    required this.accentColor,
    required this.progress,
    this.subTarget,
    this.statusColor,
  });

  @override
  State<SensorGaugeCard> createState() => _SensorGaugeCardState();
}

class _SensorGaugeCardState extends State<SensorGaugeCard>
    with SingleTickerProviderStateMixin {
  late AnimationController _ctrl;
  late Animation<double> _progressAnim;

  @override
  void initState() {
    super.initState();
    _ctrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1200),
    );
    _progressAnim = Tween<double>(begin: 0.0, end: widget.progress.clamp(0.0, 1.0))
        .animate(CurvedAnimation(parent: _ctrl, curve: Curves.easeOutCubic));
    _ctrl.forward();
  }

  @override
  void didUpdateWidget(SensorGaugeCard old) {
    super.didUpdateWidget(old);
    if (old.progress != widget.progress) {
      _progressAnim = Tween<double>(
        begin: _progressAnim.value,
        end: widget.progress.clamp(0.0, 1.0),
      ).animate(CurvedAnimation(parent: _ctrl, curve: Curves.easeOutCubic));
      _ctrl
        ..reset()
        ..forward();
    }
  }

  @override
  void dispose() {
    _ctrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final sw = MediaQuery.of(context).size.width;
    final isSmall = sw < 380;
    final stColor = widget.statusColor ?? widget.accentColor;

    return AnimatedBuilder(
      animation: _ctrl,
      builder: (context, _) {
        return Container(
          decoration: BoxDecoration(
            color: const Color(0xFF111A2E), // Obsidian Slate Card
            borderRadius: BorderRadius.circular(24),
            border: Border.all(
              color: const Color(0xFF1B2842), // Tech border
              width: 1.4,
            ),
            boxShadow: [
              // Subtle ambient depth
              const BoxShadow(
                color: Color(0x60000000),
                blurRadius: 18,
                offset: Offset(0, 8),
              ),
              // Soft neon ambient glow matching gauge color
              BoxShadow(
                color: widget.accentColor.withAlpha(20),
                blurRadius: 24,
                spreadRadius: 1,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: LayoutBuilder(
            builder: (context, constraints) {
              // Ensure dial fits nicely inside the card width
              final maxAvailableW = constraints.maxWidth;
              final dialSize = (maxAvailableW * 0.76).clamp(88.0, 136.0);

              return Padding(
                padding: EdgeInsets.symmetric(
                  horizontal: isSmall ? 8.0 : 10.0,
                  vertical: isSmall ? 10.0 : 12.0,
                ),
                child: FittedBox(
                  fit: BoxFit.scaleDown,
                  alignment: Alignment.center,
                  child: SizedBox(
                    width: maxAvailableW > 0 ? maxAvailableW : 140,
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        // ── Circular Neon HUD Dial ──
                        SizedBox(
                          width: dialSize,
                          height: dialSize,
                          child: CustomPaint(
                            painter: _CyberNeonDialPainter(
                              progress: _progressAnim.value,
                              color: widget.accentColor,
                            ),
                            child: Center(
                              child: Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 4),
                                child: Column(
                                  mainAxisSize: MainAxisSize.min,
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    // ── Main Digital Value + Superscript Unit ──
                                    FittedBox(
                                      fit: BoxFit.scaleDown,
                                      child: Row(
                                        mainAxisSize: MainAxisSize.min,
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            widget.value,
                                            style: GoogleFonts.orbitron(
                                              fontSize: isSmall ? 17 : 20,
                                              fontWeight: FontWeight.w800,
                                              color: Colors.white,
                                              letterSpacing: -0.5,
                                              height: 1.1,
                                            ),
                                          ),
                                          const SizedBox(width: 2),
                                          Padding(
                                            padding: const EdgeInsets.only(top: 2),
                                            child: Text(
                                              widget.unit,
                                              style: GoogleFonts.orbitron(
                                                fontSize: isSmall ? 8.5 : 10.0,
                                                fontWeight: FontWeight.w700,
                                                color: widget.accentColor,
                                                height: 1.0,
                                              ),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),

                                    // ── Sub-Target Benchmark Value ──
                                    if (widget.subTarget != null &&
                                        widget.subTarget!.isNotEmpty) ...[
                                      const SizedBox(height: 3),
                                      Text(
                                        widget.subTarget!,
                                        style: GoogleFonts.orbitron(
                                          fontSize: isSmall ? 11 : 12.5,
                                          fontWeight: FontWeight.w600,
                                          color: const Color(0xFF8EA2C6),
                                          letterSpacing: 0.4,
                                        ),
                                      ),
                                    ],
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),

                        const SizedBox(height: 12),

                        // ── Label (Air Temperature / Humidity etc.) ──
                        Text(
                          widget.label,
                          style: GoogleFonts.rajdhani(
                            fontSize: isSmall ? 13.5 : 15.0,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                            letterSpacing: 0.3,
                          ),
                          textAlign: TextAlign.center,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),

                        const SizedBox(height: 2),

                        // ── Status Subtitle (Optimal / Ideal) ──
                        Text(
                          widget.statusText,
                          style: GoogleFonts.rajdhani(
                            fontSize: isSmall ? 12.0 : 13.5,
                            fontWeight: FontWeight.w600,
                            color: stColor,
                            letterSpacing: 0.4,
                          ),
                          textAlign: TextAlign.center,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
        );
      },
    );
  }
}

// ─────────────────────────────────────────────────────────────
// 🌌 _CyberNeonDialPainter — 360° Dark Dial + Glowing Neon Arc
// ─────────────────────────────────────────────────────────────
class _CyberNeonDialPainter extends CustomPainter {
  final double progress; // 0.0 - 1.0
  final Color color;

  _CyberNeonDialPainter({
    required this.progress,
    required this.color,
  });

  @override
  void paint(Canvas canvas, Size size) {
    final center = Offset(size.width / 2, size.height / 2);
    final radius = (math.min(size.width, size.height) / 2) - 8.0;
    if (radius <= 0) return;

    // 1. Dark Dial Face Background Circle
    final facePaint = Paint()
      ..color = const Color(0xFF0E1626)
      ..style = PaintingStyle.fill;
    canvas.drawCircle(center, radius + 4.0, facePaint);

    // 2. Inactive Outer Ring Track
    const strokeW = 8.5;
    final trackPaint = Paint()
      ..color = const Color(0xFF172338)
      ..style = PaintingStyle.stroke
      ..strokeWidth = strokeW
      ..strokeCap = StrokeCap.round;
    canvas.drawCircle(center, radius, trackPaint);

    // 3. Inner Dashed/Tick Ring
    final dashRadius = radius - 11.0;
    if (dashRadius > 0) {
      final tickPaint = Paint()
        ..color = const Color(0xFF1D2E49)
        ..style = PaintingStyle.stroke
        ..strokeWidth = 1.6
        ..strokeCap = StrokeCap.round;

      const int tickCount = 36;
      for (int i = 0; i < tickCount; i++) {
        // Leave tiny gap for dashed appearance
        final angle = (i * 2 * math.pi) / tickCount;
        final x1 = center.dx + dashRadius * math.cos(angle);
        final y1 = center.dy + dashRadius * math.sin(angle);
        final x2 = center.dx + (dashRadius - 2.8) * math.cos(angle);
        final y2 = center.dy + (dashRadius - 2.8) * math.sin(angle);
        canvas.drawLine(Offset(x1, y1), Offset(x2, y2), tickPaint);
      }
    }

    // 4. Active Glowing Neon Progress Arc (Starts at 12 o'clock, sweeps clockwise)
    final clampedP = progress.clamp(0.01, 1.0);
    final sweepAngle = clampedP * 2 * math.pi;
    const startAngle = -math.pi / 2; // 12 o'clock

    final arcRect = Rect.fromCircle(center: center, radius: radius);

    // Glow pass (Soft blur)
    final glowPaint = Paint()
      ..color = color.withAlpha(120)
      ..style = PaintingStyle.stroke
      ..strokeWidth = strokeW + 4.0
      ..strokeCap = StrokeCap.round
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 5.0);
    canvas.drawArc(arcRect, startAngle, sweepAngle, false, glowPaint);

    // Sharp glowing gradient arc
    final gradientColors = [
      color.withAlpha(140),
      color,
      color,
    ];
    final progressPaint = Paint()
      ..shader = SweepGradient(
        startAngle: startAngle,
        endAngle: startAngle + sweepAngle,
        colors: gradientColors,
      ).createShader(arcRect)
      ..style = PaintingStyle.stroke
      ..strokeWidth = strokeW
      ..strokeCap = StrokeCap.round;

    canvas.drawArc(arcRect, startAngle, sweepAngle, false, progressPaint);

    // 5. Glowing Tip Dot at End of Arc
    final tipAngle = startAngle + sweepAngle;
    final tipCenter = Offset(
      center.dx + radius * math.cos(tipAngle),
      center.dy + radius * math.sin(tipAngle),
    );

    // Tip glow
    final tipGlowPaint = Paint()
      ..color = color.withAlpha(180)
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 3.5);
    canvas.drawCircle(tipCenter, 4.5, tipGlowPaint);

    // Tip core
    final tipCorePaint = Paint()..color = Colors.white;
    canvas.drawCircle(tipCenter, 2.2, tipCorePaint);
  }

  @override
  bool shouldRepaint(covariant _CyberNeonDialPainter oldDelegate) {
    return oldDelegate.progress != progress || oldDelegate.color != color;
  }
}
