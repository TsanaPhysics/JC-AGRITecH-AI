import 'package:flutter/material.dart';

class HealthTheme {
  static const Color background = Color(0xFF090D16);
  static const Color surface = Color(0xFF131A29);
  static const Color surfaceCard = Color(0xFF131A29);
  static const Color surfaceElevated = Color(0xFF1B2438);
  static const Color border = Color(0xFF263248);

  static const Color emeraldStep = Color(0xFF00E676);
  static const Color orangeCalorie = Color(0xFFFF6D00);
  static const Color cyanDistance = Color(0xFF00E5FF);
  static const Color purpleTime = Color(0xFFB388FF);

  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: background,
      primaryColor: emeraldStep,
      colorScheme: const ColorScheme.dark(
        primary: emeraldStep,
        secondary: cyanDistance,
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
        elevation: 2,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: border, width: 1),
        ),
      ),
    );
  }
}
