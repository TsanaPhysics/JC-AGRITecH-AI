import 'package:flutter/material.dart';

class AppTheme {
  static const Color background = Color(0xFF090D16);
  static const Color surface = Color(0xFF121826);
  static const Color surfaceElevated = Color(0xFF1B2234);
  static const Color border = Color(0xFF263248);

  static const Color primaryCyan = Color(0xFF00E5FF);
  static const Color accentGreen = Color(0xFF00E676);
  static const Color accentBlue = Color(0xFF3D82FF);
  static const Color accentAmber = Color(0xFFFFB74D);
  static const Color accentPink = Color(0xFFFF4081);
  static const Color accentPurple = Color(0xFFB388FF);

  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: background,
      primaryColor: primaryCyan,
      colorScheme: const ColorScheme.dark(
        primary: primaryCyan,
        secondary: accentGreen,
        surface: surface,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: false,
        titleTextStyle: TextStyle(
          color: Colors.white,
          fontSize: 18,
          fontWeight: FontWeight.bold,
          letterSpacing: 0.5,
        ),
      ),
      cardTheme: CardThemeData(
        color: surface,
        elevation: 4,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: const BorderSide(color: border, width: 1),
        ),
      ),
      sliderTheme: SliderThemeData(
        activeTrackColor: primaryCyan,
        inactiveTrackColor: border,
        thumbColor: primaryCyan,
        overlayColor: primaryCyan.withValues(alpha: 0.2),
        valueIndicatorColor: surfaceElevated,
        valueIndicatorTextStyle: const TextStyle(color: primaryCyan, fontWeight: FontWeight.bold),
      ),
    );
  }
}
