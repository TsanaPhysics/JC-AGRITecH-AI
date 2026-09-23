import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../models/activity_type.dart';
import '../../services/movement_sensor_service.dart';
import '../theme/health_theme.dart';
import '../widgets/activity_ring_progress.dart';
import '../widgets/cadence_and_hydration_card.dart';
import '../widgets/goal_setting_dialog.dart';
import '../widgets/hourly_activity_chart.dart';
import '../widgets/kinetic_avatar_widget.dart';
import '../widgets/live_motion_gauge.dart';
import '../widgets/metric_stat_cards.dart';
import '../widgets/profile_settings_dialog.dart';
import '../widgets/responsive_layout_builder.dart';
import '../widgets/sedentary_alert_banner.dart';

class HomeTrackerScreen extends StatefulWidget {
  const HomeTrackerScreen({super.key});

  @override
  State<HomeTrackerScreen> createState() => _HomeTrackerScreenState();
}

class _HomeTrackerScreenState extends State<HomeTrackerScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<MovementSensorService>().initialize();
    });
  }

  void _openGoalDialog() {
    final sensor = context.read<MovementSensorService>();
    showDialog(
      context: context,
      builder: (_) => GoalSettingDialog(
        currentGoal: sensor.dailyGoal,
        onSave: (newGoal) => sensor.updateGoal(newGoal),
      ),
    );
  }

  void _openProfileDialog() {
    final sensor = context.read<MovementSensorService>();
    showDialog(
      context: context,
      builder: (_) => ProfileSettingsDialog(
        profile: sensor.profile,
        onSave: (newProfile) => sensor.updateProfile(newProfile),
      ),
    );
  }

  void _confirmReset() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: HealthTheme.surfaceElevated,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: const Text('รีเซ็ตจำนวนก้าววันนี้?', style: TextStyle(color: Colors.white)),
        content: const Text(
          'ต้องการเริ่มนับก้าวใหม่สำหรับวันนี้หรือไม่?',
          style: TextStyle(color: Colors.white70),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('ยกเลิก', style: TextStyle(color: Colors.white54)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: HealthTheme.orangeCalorie),
            onPressed: () {
              context.read<MovementSensorService>().resetSteps();
              Navigator.pop(ctx);
            },
            child: const Text('รีเซ็ต', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final sensor = context.watch<MovementSensorService>();
    final metric = sensor.currentMetric;
    final now = DateTime.now();
    final dateStr = DateFormat('d MMMM yyyy').format(now);

    // Dynamic Ambient Background Colors according to current movement activity
    final Color ambientColor1 = metric.currentActivity == ActivityType.running
        ? const Color(0xFF2A0D0A)
        : metric.currentActivity == ActivityType.walking
            ? const Color(0xFF082015)
            : metric.currentActivity == ActivityType.active
                ? const Color(0xFF0C1929)
                : const Color(0xFF0D121F);

    return Scaffold(
      backgroundColor: HealthTheme.background,
      body: AnimatedContainer(
        duration: const Duration(milliseconds: 600),
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [
              ambientColor1,
              HealthTheme.background,
              HealthTheme.background,
            ],
            stops: const [0.0, 0.45, 1.0],
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              // Top Custom App Bar
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                child: Row(
                  children: [
                    Container(
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(
                          color: metric.currentActivity.color.withValues(alpha: 0.8),
                          width: 1.5,
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: metric.currentActivity.color.withValues(alpha: 0.4),
                            blurRadius: 10,
                            spreadRadius: 1,
                          ),
                        ],
                      ),
                      child: ClipOval(
                        child: Image.asset(
                          'assets/icons/app_icon.png',
                          width: 32,
                          height: 32,
                          fit: BoxFit.cover,
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'SMART HEALTH TRACKER',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 14,
                              fontWeight: FontWeight.w900,
                              letterSpacing: 1.1,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                          Text(
                            '$dateStr • ส่วนสูง ${sensor.profile.heightCm.round()} ซม. (${sensor.profile.gender.displayName})',
                            style: const TextStyle(color: Colors.white38, fontSize: 10.5),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 4),
                    IconButton(
                      tooltip: 'โปรไฟล์สรีรวิทยา',
                      onPressed: _openProfileDialog,
                      icon: const Icon(Icons.person_outline, color: HealthTheme.cyanDistance),
                    ),
                    IconButton(
                      tooltip: 'ตั้งเป้าหมายก้าว',
                      onPressed: _openGoalDialog,
                      icon: const Icon(Icons.flag_outlined, color: HealthTheme.emeraldStep),
                    ),
                    IconButton(
                      tooltip: 'รีเซ็ตข้อมูล',
                      onPressed: _confirmReset,
                      icon: const Icon(Icons.refresh, color: Colors.white54),
                    ),
                  ],
                ),
              ),

              // Responsive Body Container
              Expanded(
                child: ResponsiveLayoutBuilder(
                  builder: (context, breakpoint, constraints) {
                    if (breakpoint == ResponsiveBreakpoint.expanded) {
                      return _buildExpandedLayout(sensor, metric);
                    } else if (breakpoint == ResponsiveBreakpoint.medium) {
                      return _buildMediumLayout(sensor, metric);
                    } else {
                      return _buildCompactLayout(sensor, metric);
                    }
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  /// Compact Layout (< 600dp) - Mobile Phones (Fluid 1-Column Bento Grid)
  Widget _buildCompactLayout(MovementSensorService sensor, dynamic metric) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.only(bottom: 28),
      child: Column(
        children: [
          if (metric.needsActiveBreak)
            SedentaryAlertBanner(
              sedentaryMinutes: metric.sedentaryMinutes,
              onDismiss: () => sensor.dismissActiveBreak(),
            ),
          const SizedBox(height: 6),
          ActivityRingProgress(metric: metric, onGoalTap: _openGoalDialog),
          const SizedBox(height: 18),
          KineticAvatarWidget(
            activity: metric.currentActivity,
            intensity: metric.motionIntensity,
            cadenceSpm: metric.cadenceSpm,
          ),
          const SizedBox(height: 16),
          MetricStatCards(metric: metric),
          const SizedBox(height: 16),
          CadenceAndHydrationCard(
            cadenceSpm: metric.cadenceSpm,
            hydrationLossMl: metric.hydrationLostMl,
            tdeeKcal: metric.tdeeKcal,
            bmrKcal: sensor.profile.bmrKcal,
          ),
          const SizedBox(height: 16),
          LiveMotionGauge(intensity: metric.motionIntensity, isSensorLive: sensor.isSensorActive),
          const SizedBox(height: 16),
          HourlyActivityChart(records: sensor.hourlyRecords),
          const SizedBox(height: 20),
          _buildControls(sensor),
        ],
      ),
    );
  }

  /// Medium Layout (600 - 840dp) - Foldable Phones / Tablets Portrait (2-Column Bento Grid)
  Widget _buildMediumLayout(MovementSensorService sensor, dynamic metric) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Column(
        children: [
          if (metric.needsActiveBreak)
            SedentaryAlertBanner(
              sedentaryMinutes: metric.sedentaryMinutes,
              onDismiss: () => sensor.dismissActiveBreak(),
            ),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Left Column
              Expanded(
                flex: 5,
                child: Column(
                  children: [
                    ActivityRingProgress(metric: metric, onGoalTap: _openGoalDialog),
                    const SizedBox(height: 16),
                    KineticAvatarWidget(
                      activity: metric.currentActivity,
                      intensity: metric.motionIntensity,
                      cadenceSpm: metric.cadenceSpm,
                    ),
                    const SizedBox(height: 16),
                    LiveMotionGauge(intensity: metric.motionIntensity, isSensorLive: sensor.isSensorActive),
                    const SizedBox(height: 16),
                    _buildControls(sensor),
                  ],
                ),
              ),
              const SizedBox(width: 16),
              // Right Column
              Expanded(
                flex: 5,
                child: Column(
                  children: [
                    MetricStatCards(metric: metric),
                    const SizedBox(height: 16),
                    CadenceAndHydrationCard(
                      cadenceSpm: metric.cadenceSpm,
                      hydrationLossMl: metric.hydrationLostMl,
                      tdeeKcal: metric.tdeeKcal,
                      bmrKcal: sensor.profile.bmrKcal,
                    ),
                    const SizedBox(height: 16),
                    HourlyActivityChart(records: sensor.hourlyRecords),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Expanded Layout (> 840dp) - Large Tablets / Landscape / Web (3-Column Studio Hub)
  Widget _buildExpandedLayout(MovementSensorService sensor, dynamic metric) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        children: [
          if (metric.needsActiveBreak)
            SedentaryAlertBanner(
              sedentaryMinutes: metric.sedentaryMinutes,
              onDismiss: () => sensor.dismissActiveBreak(),
            ),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Column 1: Ring & Profile Summary
              Expanded(
                flex: 3,
                child: Column(
                  children: [
                    ActivityRingProgress(metric: metric, onGoalTap: _openGoalDialog),
                    const SizedBox(height: 18),
                    _buildProfileSummaryCard(sensor),
                    const SizedBox(height: 18),
                    _buildControls(sensor),
                  ],
                ),
              ),
              const SizedBox(width: 16),

              // Column 2: Kinetic Motion & Biometrics
              Expanded(
                flex: 4,
                child: Column(
                  children: [
                    KineticAvatarWidget(
                      activity: metric.currentActivity,
                      intensity: metric.motionIntensity,
                      cadenceSpm: metric.cadenceSpm,
                    ),
                    const SizedBox(height: 16),
                    CadenceAndHydrationCard(
                      cadenceSpm: metric.cadenceSpm,
                      hydrationLossMl: metric.hydrationLostMl,
                      tdeeKcal: metric.tdeeKcal,
                      bmrKcal: sensor.profile.bmrKcal,
                    ),
                    const SizedBox(height: 16),
                    LiveMotionGauge(intensity: metric.motionIntensity, isSensorLive: sensor.isSensorActive),
                  ],
                ),
              ),
              const SizedBox(width: 16),

              // Column 3: Metrics & 24h Activity
              Expanded(
                flex: 4,
                child: Column(
                  children: [
                    MetricStatCards(metric: metric),
                    const SizedBox(height: 16),
                    HourlyActivityChart(records: sensor.hourlyRecords),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Profile Summary Bento Box
  Widget _buildProfileSummaryCard(MovementSensorService sensor) {
    final p = sensor.profile;
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: HealthTheme.surfaceElevated,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: HealthTheme.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('ข้อมูลสรีรวิทยา', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
              TextButton(
                onPressed: _openProfileDialog,
                style: TextButton.styleFrom(padding: EdgeInsets.zero, minimumSize: const Size(40, 24)),
                child: const Text('แก้ไข', style: TextStyle(color: HealthTheme.cyanDistance, fontSize: 11)),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _profileMiniItem('BMI', p.bmi.toStringAsFixed(1), HealthTheme.emeraldStep),
              _profileMiniItem('BMR', '${p.bmrKcal.round()} kcal', HealthTheme.orangeCalorie),
              _profileMiniItem('ช่วงก้าว', '${(p.baseStrideLengthM * 100).round()} cm', HealthTheme.cyanDistance),
            ],
          ),
        ],
      ),
    );
  }

  Widget _profileMiniItem(String title, String val, Color color) {
    return Column(
      children: [
        Text(title, style: const TextStyle(color: Colors.white54, fontSize: 10)),
        const SizedBox(height: 2),
        Text(val, style: TextStyle(color: color, fontSize: 12, fontWeight: FontWeight.w900)),
      ],
    );
  }

  /// Simulation & Manual Control Row
  Widget _buildControls(MovementSensorService sensor) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16.0),
      child: Row(
        children: [
          Expanded(
            flex: 3,
            child: ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: sensor.isSimulating ? HealthTheme.orangeCalorie : HealthTheme.surfaceElevated,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                  side: BorderSide(
                    color: sensor.isSimulating ? HealthTheme.orangeCalorie : HealthTheme.border,
                  ),
                ),
              ),
              onPressed: () => sensor.toggleSimulation(),
              icon: Icon(sensor.isSimulating ? Icons.stop_circle : Icons.directions_walk, size: 18),
              label: Text(
                sensor.isSimulating ? 'หยุดการจำลองเดิน' : 'จำลองการเดินต่อเนื่อง',
                style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
              ),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            flex: 2,
            child: OutlinedButton.icon(
              style: OutlinedButton.styleFrom(
                foregroundColor: HealthTheme.emeraldStep,
                padding: const EdgeInsets.symmetric(vertical: 14),
                side: const BorderSide(color: HealthTheme.emeraldStep, width: 1.2),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              ),
              onPressed: () => sensor.addManualStep(),
              icon: const Icon(Icons.add, size: 16),
              label: const Text('+1 ก้าว', style: TextStyle(fontWeight: FontWeight.bold)),
            ),
          ),
        ],
      ),
    );
  }
}
