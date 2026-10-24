import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/telemetry_model.dart';

// ─────────────────────────────────────────────────────────────
// ⚡ RelaySwitchCard — Cyber Dark HUD Relay Switch
// ─────────────────────────────────────────────────────────────
class RelaySwitchCard extends StatefulWidget {
  final RelayItem relay;
  final VoidCallback onToggle;
  final IconData icon;

  const RelaySwitchCard({
    super.key,
    required this.relay,
    required this.onToggle,
    required this.icon,
  });

  @override
  State<RelaySwitchCard> createState() => _RelaySwitchCardState();
}

class _RelaySwitchCardState extends State<RelaySwitchCard>
    with SingleTickerProviderStateMixin {
  late AnimationController _pulseCtrl;
  late Animation<double> _pulseAnim;

  @override
  void initState() {
    super.initState();
    _pulseCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1500),
    );
    _pulseAnim = Tween<double>(begin: 0.85, end: 1.0).animate(
      CurvedAnimation(parent: _pulseCtrl, curve: Curves.easeInOutCubic),
    );
    if (widget.relay.state) _pulseCtrl.repeat(reverse: true);
  }

  @override
  void didUpdateWidget(RelaySwitchCard old) {
    super.didUpdateWidget(old);
    if (widget.relay.state != old.relay.state) {
      if (widget.relay.state) {
        _pulseCtrl.repeat(reverse: true);
      } else {
        _pulseCtrl.stop();
        _pulseCtrl.value = 0;
      }
    }
  }

  @override
  void dispose() {
    _pulseCtrl.dispose();
    super.dispose();
  }

  void _handleTap() {
    HapticFeedback.mediumImpact();
    widget.onToggle();
  }

  @override
  Widget build(BuildContext context) {
    final bool isOn = widget.relay.state;
    final sw = MediaQuery.of(context).size.width;
    final isSmall = sw < 360;

    return AnimatedBuilder(
      animation: _pulseAnim,
      builder: (context, _) {
        return GestureDetector(
          onTap: _handleTap,
          child: AnimatedContainer(
            duration: const Duration(milliseconds: 260),
            curve: Curves.easeInOutCubic,
            padding: EdgeInsets.symmetric(
              horizontal: isSmall ? 12 : 16,
              vertical: isSmall ? 12 : 14,
            ),
            decoration: BoxDecoration(
              color: const Color(0xFF111A2E), // Obsidian Slate Card
              borderRadius: BorderRadius.circular(22),
              border: Border.all(
                color: isOn
                    ? AppColors.neonGreen.withAlpha(180)
                    : const Color(0xFF1B2842),
                width: isOn ? 1.5 : 1.2,
              ),
              boxShadow: [
                // Base ambient shadow
                const BoxShadow(
                  color: Color(0x60000000),
                  blurRadius: 16,
                  offset: Offset(0, 6),
                ),
                // Neon glow when relay is energized
                if (isOn)
                  BoxShadow(
                    color: AppColors.neonGreen.withAlpha(50),
                    blurRadius: 20,
                    offset: const Offset(0, 4),
                  ),
              ],
            ),
            child: Row(
              children: [
                // ── Cyber Icon Badge ──
                AnimatedContainer(
                  duration: const Duration(milliseconds: 260),
                  curve: Curves.easeInOutCubic,
                  width: isSmall ? 44 : 48,
                  height: isSmall ? 44 : 48,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(15),
                    gradient: isOn
                        ? const LinearGradient(
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                            colors: [Color(0xFF00E676), Color(0xFF00A844)],
                          )
                        : const LinearGradient(
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                            colors: [Color(0xFF16233B), Color(0xFF0E1626)],
                          ),
                    border: Border.all(
                      color: isOn
                          ? AppColors.neonGreen
                          : const Color(0xFF263B60),
                      width: 1.2,
                    ),
                    boxShadow: isOn
                        ? [
                            BoxShadow(
                              color: AppColors.neonGreen.withAlpha(90),
                              blurRadius: 12,
                              offset: const Offset(0, 2),
                            ),
                          ]
                        : null,
                  ),
                  child: Icon(
                    widget.icon,
                    size: isSmall ? 22 : 24,
                    color: isOn ? Colors.black : const Color(0xFF8EA2C6),
                  ),
                ),

                SizedBox(width: isSmall ? 12 : 14),

                // ── Relay Info Column ──
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Row(
                        children: [
                          Flexible(
                            child: Text(
                              widget.relay.name,
                              style: GoogleFonts.rajdhani(
                                fontSize: isSmall ? 15 : 16.5,
                                fontWeight: FontWeight.w700,
                                color: Colors.white,
                                letterSpacing: 0.3,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                          const SizedBox(width: 8),
                          // GPIO Badge
                          Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 6, vertical: 2),
                            decoration: BoxDecoration(
                              color: AppColors.neonCyan.withAlpha(20),
                              borderRadius: BorderRadius.circular(6),
                              border: Border.all(
                                color: AppColors.neonCyan.withAlpha(90),
                                width: 1,
                              ),
                            ),
                            child: Text(
                              'GPIO ${widget.relay.gpio}',
                              style: GoogleFonts.orbitron(
                                fontSize: 8.5,
                                fontWeight: FontWeight.w700,
                                color: AppColors.neonCyanLight,
                                letterSpacing: 0.5,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          // Neon LED Status Dot
                          Container(
                            width: 7,
                            height: 7,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              color: isOn
                                  ? AppColors.neonGreen
                                  : const Color(0xFF475569),
                              boxShadow: isOn
                                  ? [
                                      BoxShadow(
                                        color: AppColors.neonGreen.withAlpha(200),
                                        blurRadius: 8,
                                        spreadRadius: 1,
                                      ),
                                    ]
                                  : null,
                            ),
                          ),
                          const SizedBox(width: 6),
                          Text(
                            isOn ? 'กำลังทำงาน (ACTIVE)' : 'สแตนด์บาย (OFF)',
                            style: GoogleFonts.rajdhani(
                              fontSize: isSmall ? 11.5 : 12.5,
                              fontWeight: FontWeight.w600,
                              color: isOn
                                  ? AppColors.neonGreen
                                  : const Color(0xFF8EA2C6),
                              letterSpacing: 0.3,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),

                const SizedBox(width: 10),

                // ── Cyber Futuristic Toggle Switch ──
                _buildCyberToggle(isOn, isSmall),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildCyberToggle(bool isOn, bool isSmall) {
    final w = isSmall ? 56.0 : 62.0;
    final h = isSmall ? 30.0 : 32.0;
    final thumbSize = h - 6;

    return AnimatedContainer(
      duration: const Duration(milliseconds: 240),
      curve: Curves.easeInOutCubic,
      width: w,
      height: h,
      padding: const EdgeInsets.all(3),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(h / 2),
        color: isOn ? const Color(0xFF00381B) : const Color(0xFF0A101D),
        border: Border.all(
          color: isOn ? AppColors.neonGreen : const Color(0xFF1F2D47),
          width: 1.5,
        ),
        boxShadow: isOn
            ? [
                BoxShadow(
                  color: AppColors.neonGreen.withAlpha(80),
                  blurRadius: 12,
                  offset: const Offset(0, 2),
                ),
              ]
            : null,
      ),
      child: AnimatedAlign(
        duration: const Duration(milliseconds: 240),
        curve: Curves.easeInOutCubic,
        alignment: isOn ? Alignment.centerRight : Alignment.centerLeft,
        child: Container(
          width: thumbSize,
          height: thumbSize,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: isOn ? AppColors.neonGreen : const Color(0xFF64748B),
            boxShadow: [
              BoxShadow(
                color: isOn
                    ? AppColors.neonGreen.withAlpha(180)
                    : const Color(0x60000000),
                blurRadius: isOn ? 8 : 4,
                offset: const Offset(0, 1),
              ),
            ],
          ),
          child: Center(
            child: Icon(
              Icons.power_settings_new_rounded,
              size: thumbSize * 0.65,
              color: isOn ? Colors.black : const Color(0xFF1E293B),
            ),
          ),
        ),
      ),
    );
  }
}
