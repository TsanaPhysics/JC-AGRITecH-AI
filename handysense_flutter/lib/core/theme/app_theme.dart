import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';

// ─────────────────────────────────────────────────────────────
// 🌌 HandySense — Cyber Neon & Obsidian Sci-Fi Color System
// ─────────────────────────────────────────────────────────────
class AppColors {
  // ── Deep Obsidian & Space Navy Backgrounds ──────────────────
  static const Color scaffoldDark   = Color(0xFF090D16); // Deepest obsidian blue
  static const Color darkCard       = Color(0xFF111A2E); // Sleek cyber card slate
  static const Color darkCardHover  = Color(0xFF16233B); // Card hover/focus
  static const Color cardSurface    = Color(0xFF131D31); // Secondary surface
  static const Color dialBg         = Color(0xFF0E1626); // Circular dial fill
  static const Color dialTrack      = Color(0xFF172338); // Circular dial track
  static const Color dialDashedRing = Color(0xFF1D2E49); // Subtle inner tick ring

  // ── Borders & Glass Lines ──────────────────────────────────
  static const Color borderDark     = Color(0xFF1B2842); // Sleek card border
  static const Color borderGlow     = Color(0xFF263B60); // Highlight border
  static const Color divider        = Color(0xFF1E2D48); // Divider line
  static const Color slateBorder    = Color(0xFF1B2842);
  static const Color neonBorder     = Color(0x6000E5FF);

  // ── Cyber Neon Accents (Exact from Reference HUD) ──────────
  static const Color neonOrange     = Color(0xFFFF7A00); // Air Temperature
  static const Color neonAmber      = Color(0xFFFFAB00); // Optimal text
  static const Color neonCyan       = Color(0xFF00C6FF); // Humidity / Ideal
  static const Color neonCyanLight  = Color(0xFF00E5FF);
  static const Color neonGreen      = Color(0xFF00E676); // Soil Moisture / Ideal
  static const Color neonEmerald    = Color(0xFF10B981);
  static const Color neonPurple     = Color(0xFF7C4DFF); // VPD / Ideal
  static const Color neonViolet     = Color(0xFF8B5CF6);
  static const Color neonBlue       = Color(0xFF3B82F6);

  // ── Status & Functional Colors ─────────────────────────────
  static const Color alertRed       = Color(0xFFFF3366); // Cyber alert
  static const Color alertRose      = Color(0xFFFF3366); // Cyber alert rose
  static const Color alertAmber     = Color(0xFFFF9900); // Cyber warning
  static const Color successGreen   = Color(0xFF00E676); // Cyber online

  // ── Typography & Readability (Light on Dark) ───────────────
  static const Color textWhite      = Color(0xFFFFFFFF); // High-contrast headers
  static const Color textLight      = Color(0xFFF1F5F9); // Crisp metric titles
  static const Color textBody       = Color(0xFFCBD5E1); // Body text
  static const Color textMuted      = Color(0xFF8EA2C6); // Sub-targets (e.g. 28.5)
  static const Color textDim        = Color(0xFF64748B); // Inactive labels
  static const Color textDark       = Color(0xFFFFFFFF); // Compatible with dark mode
  static const Color textOnColor    = Color(0xFFFFFFFF);

  // ── Compatibility Aliases (Nature/Ghibli aliases mapped) ───
  static const Color forestMid      = neonGreen;
  static const Color forestDark     = Color(0xFF00C853);
  static const Color leafBright     = neonGreen;
  static const Color waterMid       = neonCyan;
  static const Color waterDeep      = Color(0xFF0091EA);
  static const Color sunGold        = neonAmber;
  static const Color sunOrange      = neonOrange;
  static const Color cardWhite      = darkCard;
  static const Color softMist       = Color(0xFF16233B);
  static const Color shadowWarm     = Color(0x60000000);
  static const Color shadowSky      = Color(0x4000C6FF);
  static const Color shadowGreen    = Color(0x4000E676);
  static const Color darkObsidian   = scaffoldDark;
}

// ─────────────────────────────────────────────────────────────
// 🌌 AppTheme — Cyber Dark Sci-Fi Theme
// ─────────────────────────────────────────────────────────────
class AppTheme {
  static ThemeData get ghibliTheme => cyberDarkTheme; // Alias for backward compatibility
  static ThemeData get darkTheme   => cyberDarkTheme;

  static ThemeData get cyberDarkTheme {
    const baseScheme = ColorScheme.dark(
      primary:    AppColors.neonCyan,
      secondary:  AppColors.neonGreen,
      tertiary:   AppColors.neonOrange,
      surface:    AppColors.darkCard,
      error:      AppColors.alertRed,
      onPrimary:  Colors.black,
      onSecondary: Colors.black,
      onSurface:  AppColors.textWhite,
    );

    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.dark,
      colorScheme: baseScheme,

      // ── Scaffold Background ────────────────────────────────
      scaffoldBackgroundColor: AppColors.scaffoldDark,

      // ── AppBar ─────────────────────────────────────────────
      appBarTheme: AppBarTheme(
        backgroundColor: const Color(0xFF0C1322),
        elevation: 0,
        scrolledUnderElevation: 2,
        shadowColor: Colors.black45,
        surfaceTintColor: Colors.transparent,
        centerTitle: false,
        systemOverlayStyle: const SystemUiOverlayStyle(
          statusBarColor: Colors.transparent,
          statusBarIconBrightness: Brightness.light,
          systemNavigationBarColor: Color(0xFF090D16),
          systemNavigationBarIconBrightness: Brightness.light,
        ),
        titleTextStyle: GoogleFonts.orbitron(
          fontSize: 16,
          fontWeight: FontWeight.w800,
          color: AppColors.textWhite,
          letterSpacing: 0.5,
        ),
        iconTheme: const IconThemeData(color: AppColors.textWhite),
      ),

      // ── Card ───────────────────────────────────────────────
      cardTheme: CardThemeData(
        color: AppColors.darkCard,
        elevation: 6,
        shadowColor: const Color(0x60000000),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(22),
          side: const BorderSide(color: AppColors.borderDark, width: 1.5),
        ),
      ),

      // ── Typography ─────────────────────────────────────────
      textTheme: GoogleFonts.rajdhaniTextTheme(ThemeData.dark().textTheme).apply(
        bodyColor: AppColors.textBody,
        displayColor: AppColors.textWhite,
      ),

      // ── BottomNav ──────────────────────────────────────────
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: Color(0xFF0C1322),
        selectedItemColor: AppColors.neonCyan,
        unselectedItemColor: AppColors.textDim,
        type: BottomNavigationBarType.fixed,
        elevation: 16,
      ),

      // ── Input Fields ───────────────────────────────────────
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: const Color(0xFF131D31),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: AppColors.borderDark),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: AppColors.borderDark, width: 1.5),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: AppColors.neonCyan, width: 2),
        ),
      ),

      // ── ElevatedButton ────────────────────────────────────
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.neonCyan,
          foregroundColor: Colors.black,
          elevation: 6,
          shadowColor: AppColors.neonCyan.withAlpha(100),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          textStyle: GoogleFonts.rajdhani(
            fontWeight: FontWeight.w700,
            fontSize: 15,
            letterSpacing: 0.5,
          ),
        ),
      ),

      // ── OutlinedButton ────────────────────────────────────
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.neonCyan,
          side: const BorderSide(color: AppColors.neonCyan, width: 1.5),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
        ),
      ),

      // ── Divider ────────────────────────────────────────────
      dividerTheme: const DividerThemeData(
        color: AppColors.divider,
        thickness: 1,
        space: 1,
      ),

      // ── Snackbar ───────────────────────────────────────────
      snackBarTheme: SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
        backgroundColor: const Color(0xFF131D31),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
          side: const BorderSide(color: AppColors.borderDark, width: 1),
        ),
        contentTextStyle: GoogleFonts.rajdhani(
          fontWeight: FontWeight.w600,
          color: Colors.white,
          fontSize: 14,
        ),
      ),
    );
  }
}
