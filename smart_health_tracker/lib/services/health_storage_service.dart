import 'package:shared_preferences/shared_preferences.dart';

class HealthStorageService {
  static const String _keyDailyGoal = 'health_step_goal';
  static const String _keySavedStepsPrefix = 'health_steps_';

  static Future<int> getDailyGoal() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getInt(_keyDailyGoal) ?? 10000;
  }

  static Future<void> setDailyGoal(int goal) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt(_keyDailyGoal, goal);
  }

  static Future<int> getSavedStepsForDate(DateTime date) async {
    final prefs = await SharedPreferences.getInstance();
    final dateKey = '$_keySavedStepsPrefix${date.year}_${date.month}_${date.day}';
    return prefs.getInt(dateKey) ?? 0;
  }

  static const String _keyProfileWeight = 'health_profile_weight';
  static const String _keyProfileHeight = 'health_profile_height';
  static const String _keyProfileAge = 'health_profile_age';
  static const String _keyProfileGender = 'health_profile_gender';

  static Future<void> saveStepsForDate(DateTime date, int steps) async {
    final prefs = await SharedPreferences.getInstance();
    final dateKey = '$_keySavedStepsPrefix${date.year}_${date.month}_${date.day}';
    await prefs.setInt(dateKey, steps);
  }

  static Future<void> saveUserProfile(double weightKg, double heightCm, int age, int genderIndex) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setDouble(_keyProfileWeight, weightKg);
    await prefs.setDouble(_keyProfileHeight, heightCm);
    await prefs.setInt(_keyProfileAge, age);
    await prefs.setInt(_keyProfileGender, genderIndex);
  }

  static Future<Map<String, dynamic>> getUserProfileData() async {
    final prefs = await SharedPreferences.getInstance();
    return {
      'weightKg': prefs.getDouble(_keyProfileWeight) ?? 65.0,
      'heightCm': prefs.getDouble(_keyProfileHeight) ?? 170.0,
      'age': prefs.getInt(_keyProfileAge) ?? 25,
      'gender': prefs.getInt(_keyProfileGender) ?? 0,
    };
  }
}
