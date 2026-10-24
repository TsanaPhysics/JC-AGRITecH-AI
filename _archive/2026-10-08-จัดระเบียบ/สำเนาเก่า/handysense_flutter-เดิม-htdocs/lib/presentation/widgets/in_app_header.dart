import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../core/theme/app_theme.dart';

class InAppHeader extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final String? subtitle;
  final bool isOnline;
  final int? latencyMs;
  final VoidCallback? onSettingsPressed;

  const InAppHeader({
    super.key,
    required this.title,
    this.subtitle,
    required this.isOnline,
    this.latencyMs,
    this.onSettingsPressed,
  });

  @override
  Size get preferredSize => const Size.fromHeight(68);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(
        top: MediaQuery.paddingOf(context).top + 8,
        left: 16,
        right: 16,
        bottom: 12,
      ),
      decoration: BoxDecoration(
        color: AppColors.darkObsidian.withAlpha(240),
        border: const Border(
          bottom: BorderSide(color: AppColors.slateBorder, width: 1),
        ),
      ),
      child: Row(
        children: [
          // Custom App Icon Branding (flutter-app-architect Requirement)
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: AppColors.neonCyan.withAlpha(120), width: 1.5),
              boxShadow: [
                BoxShadow(
                  color: AppColors.neonCyan.withAlpha(60),
                  blurRadius: 8,
                  spreadRadius: 1,
                ),
              ],
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(8.5),
              child: Image.asset(
                'assets/icons/app_icon.png',
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) => const Icon(
                  Icons.eco,
                  color: AppColors.neonGreen,
                  size: 22,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),

          // Title & Subtitle
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  title,
                  style: GoogleFonts.chakraPetch(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: AppColors.textWhite,
                    letterSpacing: 0.5,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                Text(
                  subtitle ?? 'ESP32-S3 ATD3.5 Farm Controller',
                  style: GoogleFonts.prompt(
                    fontSize: 11,
                    color: AppColors.textMuted,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),

          // Online / Latency Pill
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(
              color: isOnline ? AppColors.neonEmerald.withAlpha(35) : AppColors.alertRose.withAlpha(35),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(
                color: isOnline ? AppColors.neonEmerald.withAlpha(120) : AppColors.alertRose.withAlpha(120),
                width: 1,
              ),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 6,
                  height: 6,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: isOnline ? AppColors.neonGreen : AppColors.alertRose,
                  ),
                ),
                const SizedBox(width: 5),
                Text(
                  isOnline ? (latencyMs != null ? '${latencyMs}ms' : 'ONLINE') : 'OFFLINE',
                  style: GoogleFonts.chakraPetch(
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                    color: isOnline ? AppColors.neonGreen : AppColors.alertRose,
                  ),
                ),
              ],
            ),
          ),

          if (onSettingsPressed != null) ...[
            const SizedBox(width: 8),
            IconButton(
              icon: const Icon(Icons.tune, color: AppColors.neonCyan, size: 20),
              onPressed: onSettingsPressed,
              tooltip: 'ตั้งค่าการเชื่อมต่อ (Setup)',
            ),
          ],
        ],
      ),
    );
  }
}
