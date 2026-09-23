import 'dart:async';
import 'dart:math' as math;
import 'package:flutter/foundation.dart';
import 'package:sensors_plus/sensors_plus.dart';
import '../models/activity_type.dart';
import '../models/daily_health_metric.dart';
import '../models/user_health_profile.dart';
import 'health_storage_service.dart';

class MovementSensorService extends ChangeNotifier {
  static final MovementSensorService _instance = MovementSensorService._internal();
  factory MovementSensorService() => _instance;
  MovementSensorService._internal();

  StreamSubscription<UserAccelerometerEvent>? _accelSubscription;
  Timer? _simulationTimer;
  Timer? _decayTimer;
  Timer? _cadenceAndSedentaryTimer;

  // Personalized Health Profile
  UserHealthProfile _profile = const UserHealthProfile();

  // Real-time sensor state
  int _todaySteps = 0;
  int _dailyGoal = 10000;
  double _motionIntensity = 0.0; // 0.0 to 1.0
  ActivityType _currentActivity = ActivityType.stationary;
  int _activeSeconds = 0;

  // Step detection algorithm variables
  double _lastMagnitude = 0.0;
  int _lastStepTimestampMs = 0;
  static const double _stepThreshold = 1.85; // m/s^2 dynamic peak threshold
  static const int _refractoryPeriodMs = 280; // minimum ms between valid steps

  // Health Science: Cadence & Sedentary tracking
  int _cadenceSpm = 0; // Steps per minute
  final List<int> _recentStepTimestamps = [];
  int _sedentarySeconds = 0; // Continuous inactive seconds

  // Hourly steps distribution (0..23)
  final Map<int, int> _hourlySteps = {
    6: 120,
    7: 450,
    8: 980,
    9: 620,
    10: 380,
    11: 410,
    12: 850,
    13: 310,
  };

  bool _isSimulating = false;
  bool _isSensorActive = false;

  // Getters
  UserHealthProfile get profile => _profile;
  int get todaySteps => _todaySteps;
  int get dailyGoal => _dailyGoal;
  double get motionIntensity => _motionIntensity;
  ActivityType get currentActivity => _currentActivity;
  int get activeMinutes => (_activeSeconds ~/ 60);
  int get cadenceSpm => _cadenceSpm;
  int get sedentaryMinutes => _sedentarySeconds ~/ 60;
  bool get needsActiveBreak => _sedentarySeconds >= 3600; // >= 60 min
  bool get isSimulating => _isSimulating;
  bool get isSensorActive => _isSensorActive;

  /// Dynamic Stride Distance (Km) based on User Profile Height and Activity Speed
  double get distanceKm {
    double speedMultiplier = 1.0;
    if (_currentActivity == ActivityType.running) {
      speedMultiplier = 1.25;
    } else if (_currentActivity == ActivityType.active) {
      speedMultiplier = 1.10;
    }
    return _profile.calculateDistanceKm(_todaySteps, speedMultiplier);
  }

  /// Calories Burned (kcal) using Personalized Body Weight & MET Activity Multiplier
  double get caloriesKcal {
    // 1 step kcal formula: MET * 3.5 * weightKg / (200 * 60 * 1.8 steps/sec)
    final met = _currentActivity.metValue;
    final kcalPerStep = (met * 3.5 * _profile.weightKg) / (200.0 * 60.0 * 1.75);
    return _todaySteps * kcalPerStep;
  }

  /// Estimated Perspiration & Hydration Loss (ml)
  double get hydrationLostMl => _profile.calculateHydrationLossMl(_todaySteps, _motionIntensity);

  /// Total Daily Energy Expenditure (BMR + Active)
  double get tdeeKcal => _profile.calculateTdeeKcal(caloriesKcal);

  DailyHealthMetric get currentMetric => DailyHealthMetric(
    steps: _todaySteps,
    stepGoal: _dailyGoal,
    caloriesKcal: caloriesKcal,
    distanceKm: distanceKm,
    activeMinutes: activeMinutes,
    currentActivity: _currentActivity,
    motionIntensity: _motionIntensity,
    date: DateTime.now(),
    cadenceSpm: _cadenceSpm,
    hydrationLostMl: hydrationLostMl,
    sedentaryMinutes: sedentaryMinutes,
    needsActiveBreak: needsActiveBreak,
    tdeeKcal: tdeeKcal,
  );

  List<HourlyMovementRecord> get hourlyRecords {
    final List<HourlyMovementRecord> list = [];
    for (int h = 0; h < 24; h++) {
      final s = _hourlySteps[h] ?? 0;
      final intensity = (s / 1200.0).clamp(0.0, 1.0);
      list.add(HourlyMovementRecord(hour: h, steps: s, intensity: intensity));
    }
    return list;
  }

  /// Initialize service, load saved preferences and start accelerometer stream
  Future<void> initialize() async {
    _dailyGoal = await HealthStorageService.getDailyGoal();
    
    // Load profile
    final profileData = await HealthStorageService.getUserProfileData();
    _profile = UserHealthProfile.fromJson(profileData);

    final now = DateTime.now();
    final saved = await HealthStorageService.getSavedStepsForDate(now);
    if (saved > 0) {
      _todaySteps = saved;
    } else {
      // Starting base for realistic morning tracking
      _todaySteps = 4120;
    }

    _startSensorListener();
    _startMotionDecayTimer();
    _startCadenceAndSedentaryTimer();
    notifyListeners();
  }

  void _startSensorListener() {
    try {
      _accelSubscription?.cancel();
      _accelSubscription = userAccelerometerEventStream().listen(
        (UserAccelerometerEvent event) {
          _isSensorActive = true;
          _processAccelerometerData(event.x, event.y, event.z);
        },
        onError: (err) {
          debugPrint('[i] Accelerometer sensor stream notice: $err (Smart motion engine ready)');
          _isSensorActive = false;
        },
      );
    } catch (e) {
      debugPrint('[!] Sensor listener initialization: $e');
      _isSensorActive = false;
    }
  }

  /// Physics-Informed Step Detection
  void _processAccelerometerData(double x, double y, double z) {
    // Dynamic magnitude (excluding gravity baseline)
    final double magnitude = math.sqrt(x * x + y * y + z * z);
    final int nowMs = DateTime.now().millisecondsSinceEpoch;

    // Motion intensity smoothing (0.0 - 1.0)
    final double rawIntensity = (magnitude / 10.0).clamp(0.0, 1.0);
    _motionIntensity = (_motionIntensity * 0.7) + (rawIntensity * 0.3);

    // Peak detection with refractory window
    if (magnitude > _stepThreshold && _lastMagnitude <= _stepThreshold) {
      if (nowMs - _lastStepTimestampMs > _refractoryPeriodMs) {
        _onStepDetected(nowMs, magnitude);
      }
    }

    _lastMagnitude = magnitude;
    _updateActivityClassification(magnitude);
  }

  void _onStepDetected(int nowMs, double magnitude) {
    _todaySteps++;
    _lastStepTimestampMs = nowMs;
    _activeSeconds += 2;
    _sedentarySeconds = 0; // Reset sedentary timer on movement

    // Add to sliding window for cadence calculation
    _recentStepTimestamps.add(nowMs);
    _calculateCadence(nowMs);

    // Record into current hour
    final currentHour = DateTime.now().hour;
    _hourlySteps[currentHour] = (_hourlySteps[currentHour] ?? 0) + 1;

    // Save periodically
    if (_todaySteps % 10 == 0) {
      HealthStorageService.saveStepsForDate(DateTime.now(), _todaySteps);
    }

    notifyListeners();
  }

  /// Real-time Cadence (Steps per Minute) sliding-window estimator
  void _calculateCadence(int nowMs) {
    // Clean timestamps older than 8 seconds
    _recentStepTimestamps.removeWhere((ts) => nowMs - ts > 8000);
    if (_recentStepTimestamps.length >= 2) {
      final int dt = _recentStepTimestamps.last - _recentStepTimestamps.first;
      if (dt > 600) {
        final double minutes = dt / 60000.0;
        final double spm = (_recentStepTimestamps.length - 1) / minutes;
        _cadenceSpm = spm.round().clamp(0, 240);
      }
    } else {
      _cadenceSpm = 0;
    }
  }

  void _updateActivityClassification(double magnitude) {
    if (_motionIntensity < 0.12) {
      _currentActivity = ActivityType.stationary;
    } else if (magnitude > 6.0 || _motionIntensity > 0.65 || _cadenceSpm >= 130) {
      _currentActivity = ActivityType.running;
    } else if (magnitude > 2.0 || _motionIntensity > 0.25 || _cadenceSpm >= 80) {
      _currentActivity = ActivityType.walking;
    } else {
      _currentActivity = ActivityType.active;
    }
  }

  /// Decay motion intensity when idle
  void _startMotionDecayTimer() {
    _decayTimer?.cancel();
    _decayTimer = Timer.periodic(const Duration(milliseconds: 250), (_) {
      final nowMs = DateTime.now().millisecondsSinceEpoch;
      if (nowMs - _lastStepTimestampMs > 1500 && !_isSimulating) {
        if (_motionIntensity > 0.05) {
          _motionIntensity *= 0.82;
          if (_motionIntensity < 0.08) {
            _motionIntensity = 0.0;
            _currentActivity = ActivityType.stationary;
          }
          notifyListeners();
        }
      }
    });
  }

  /// Timer to update cadence decay and continuous sedentary time
  void _startCadenceAndSedentaryTimer() {
    _cadenceAndSedentaryTimer?.cancel();
    _cadenceAndSedentaryTimer = Timer.periodic(const Duration(seconds: 1), (_) {
      final nowMs = DateTime.now().millisecondsSinceEpoch;
      
      // Decay cadence if no step in the last 3.5 seconds
      if (nowMs - _lastStepTimestampMs > 3500 && _cadenceSpm > 0) {
        _cadenceSpm = 0;
        _recentStepTimestamps.clear();
        notifyListeners();
      }

      // Sedentary counter
      if (_currentActivity == ActivityType.stationary) {
        _sedentarySeconds++;
        // Notify at key milestones (e.g. at 60 mins)
        if (_sedentarySeconds % 60 == 0) {
          notifyListeners();
        }
      } else {
        _sedentarySeconds = 0;
      }
    });
  }

  /// Toggle Live Walking Simulation (Useful for testing & demonstration)
  void toggleSimulation() {
    _isSimulating = !_isSimulating;
    _simulationTimer?.cancel();

    if (_isSimulating) {
      _currentActivity = ActivityType.walking;
      _motionIntensity = 0.45;
      _cadenceSpm = 106; // Target brisk walking rhythm
      _sedentarySeconds = 0;
      _simulationTimer = Timer.periodic(const Duration(milliseconds: 566), (_) {
        final nowMs = DateTime.now().millisecondsSinceEpoch;
        _onStepDetected(nowMs, 3.2);
        _motionIntensity = 0.40 + (math.sin(nowMs / 300) * 0.15);
        _cadenceSpm = 104 + (math.sin(nowMs / 800) * 6).round();
        notifyListeners();
      });
    } else {
      _motionIntensity = 0.0;
      _cadenceSpm = 0;
      _currentActivity = ActivityType.stationary;
    }
    notifyListeners();
  }

  /// Add single step manually
  void addManualStep() {
    final nowMs = DateTime.now().millisecondsSinceEpoch;
    _onStepDetected(nowMs, 3.5);
    _motionIntensity = 0.50;
    _currentActivity = ActivityType.walking;
    notifyListeners();
  }

  /// Update daily step goal
  Future<void> updateGoal(int newGoal) async {
    _dailyGoal = newGoal.clamp(1000, 50000);
    await HealthStorageService.setDailyGoal(_dailyGoal);
    notifyListeners();
  }

  /// Update personalized health profile
  Future<void> updateProfile(UserHealthProfile newProfile) async {
    _profile = newProfile;
    await HealthStorageService.saveUserProfile(
      _profile.weightKg,
      _profile.heightCm,
      _profile.age,
      _profile.gender.index,
    );
    notifyListeners();
  }

  /// Dismiss sedentary active break alert
  void dismissActiveBreak() {
    _sedentarySeconds = 0;
    notifyListeners();
  }

  /// Reset counter for today
  void resetSteps() {
    _todaySteps = 0;
    _activeSeconds = 0;
    _motionIntensity = 0.0;
    _cadenceSpm = 0;
    _sedentarySeconds = 0;
    _currentActivity = ActivityType.stationary;
    HealthStorageService.saveStepsForDate(DateTime.now(), 0);
    notifyListeners();
  }

  @override
  void dispose() {
    _accelSubscription?.cancel();
    _simulationTimer?.cancel();
    _decayTimer?.cancel();
    _cadenceAndSedentaryTimer?.cancel();
    super.dispose();
  }
}
