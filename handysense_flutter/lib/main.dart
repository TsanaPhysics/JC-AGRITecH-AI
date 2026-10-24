import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';

import 'core/theme/app_theme.dart';
import 'data/repositories/telemetry_repository.dart';
import 'data/services/api_service.dart';
import 'presentation/screens/control_screen.dart';
import 'presentation/screens/dashboard_screen.dart';
import 'presentation/screens/trends_screen.dart';
import 'presentation/widgets/common.dart';
import 'data/models/farm_models.dart';
import 'presentation/screens/soil_analytics_screen.dart';
import 'presentation/screens/settings_screen.dart';
import 'presentation/view_models/telemetry_view_model.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();

  // Allow portrait + landscape
  SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
    DeviceOrientation.landscapeLeft,
    DeviceOrientation.landscapeRight,
  ]);

  // Dark cyber status bar & nav bar
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
      systemNavigationBarColor: Color(0xFF090D16),
      systemNavigationBarIconBrightness: Brightness.light,
    ),
  );

  runApp(
    ChangeNotifierProvider(
      create: (_) => TelemetryViewModel(
        repository: TelemetryRepository(
          apiService: ApiService(),
        ),
      ),
      child: const HandySenseApp(),
    ),
  );
}

// ─────────────────────────────────────────────────────────────
// 🌌 HandySense App — Cyber Dark Sci-Fi IoT Smart Farm
// ─────────────────────────────────────────────────────────────
class HandySenseApp extends StatelessWidget {
  const HandySenseApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Edge xAI : LEQs-4D-AgriEnvi',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.cyberDarkTheme,
      home: const AppShell(),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// 🏡 AppShell — Cyber Obsidian Navigation Shell
// ─────────────────────────────────────────────────────────────
class AppShell extends StatefulWidget {
  const AppShell({super.key});
  @override
  State<AppShell> createState() => _AppShellState();
}

class _AppShellState extends State<AppShell> with TickerProviderStateMixin {
  int _currentIndex = 0;
  late AnimationController _fadeCtrl;
  late Animation<double> _fadeAnim;

  // Nav items — Sci-Fi Cyber Icons & Labels
  static const _navItems = [
    _NavItem(emoji: '📊', label: 'ภาพรวม', icon: Icons.dashboard_customize_rounded),
    _NavItem(emoji: '🎛️', label: 'ควบคุม', icon: Icons.toggle_on_rounded),
    _NavItem(emoji: '📈', label: 'แนวโน้ม', icon: Icons.show_chart_rounded),
    _NavItem(emoji: '🧪', label: 'ดิน', icon: Icons.biotech_rounded),
    _NavItem(emoji: '⚙️', label: 'ตั้งค่า', icon: Icons.tune_rounded),
  ];

  @override
  void initState() {
    super.initState();
    _fadeCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 220),
    );
    _fadeAnim = CurvedAnimation(parent: _fadeCtrl, curve: Curves.easeInOut);
    _fadeCtrl.forward();
  }

  @override
  void dispose() {
    _fadeCtrl.dispose();
    super.dispose();
  }

  void _onNavTap(int index) {
    if (index == _currentIndex) return;
    HapticFeedback.selectionClick();
    _fadeCtrl.reverse().then((_) {
      setState(() => _currentIndex = index);
      _fadeCtrl.forward();
    });
  }

  Widget _buildBody() {
    switch (_currentIndex) {
      case 0:
        return const DashboardScreen();
      case 1:
        return const ControlScreen();
      case 2:
        return const TrendsScreen();
      case 3:
        return const SoilAnalyticsScreen();
      case 4:
        return const SettingsScreen();
      default:
        return const DashboardScreen();
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final pending = vm.notice;
    if (pending != null) {
      final isErr = vm.noticeIsError;
      vm.clearNotice();
      WidgetsBinding.instance.addPostFrameCallback((_) {
        if (mounted) showAppSnack(context, pending, error: isErr);
      });
    }
    return Scaffold(
      backgroundColor: AppColors.scaffoldDark, // Deep obsidian #090D16
      appBar: _buildAppBar(vm),
      body: Column(
        children: [
          ConnectionBanner(vm: vm),
          Expanded(
            child: FadeTransition(
              opacity: _fadeAnim,
              child: _buildBody(),
            ),
          ),
        ],
      ),
      bottomNavigationBar: _buildBottomNav(),
    );
  }

  // ── AppBar ───────────────────────────────────────────────
  PreferredSizeWidget _buildAppBar(TelemetryViewModel vm) {
    final cs = connStyle(vm.connState);
    final statusColor = cs.color;
    final statusLabel = 'ESP32-S3 · ${cs.label}';

    return AppBar(
      backgroundColor: const Color(0xFF0C1322), // Dark glass obsidian
      elevation: 0,
      scrolledUnderElevation: 2,
      shadowColor: Colors.black,
      toolbarHeight: 64,
      shape: const Border(
        bottom: BorderSide(color: Color(0xFF1B2842), width: 1.2),
      ),
      leading: Padding(
        padding: const EdgeInsets.all(10),
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            gradient: const LinearGradient(
              colors: [Color(0xFF00C6FF), Color(0xFF0072FF)],
            ),
            boxShadow: [
              BoxShadow(
                color: AppColors.neonCyan.withAlpha(80),
                blurRadius: 8,
              ),
            ],
          ),
          child: const Center(
            child: Icon(Icons.hub_rounded, color: Colors.black, size: 22),
          ),
        ),
      ),
      title: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            'Edge xAI : LEQs-4D',
            style: GoogleFonts.orbitron(
              fontSize: 13.5,
              fontWeight: FontWeight.w800,
              color: Colors.white,
              letterSpacing: 0.8,
            ),
          ),
          const SizedBox(height: 1),
          Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 6,
                height: 6,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: statusColor,
                  boxShadow: [
                    BoxShadow(
                      color: statusColor.withAlpha(200),
                      blurRadius: 6,
                      spreadRadius: 1,
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 5),
              Flexible(
                child: Text(
                  statusLabel,
                  style: GoogleFonts.rajdhani(
                    fontSize: 11,
                    fontWeight: FontWeight.w700,
                    color: statusColor,
                    letterSpacing: 0.5,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
        ],
      ),
      actions: [
        // Mode badge
        _ModeBadge(vm: vm),

        // Alerts
        IconButton(
          tooltip: 'การแจ้งเตือน',
          constraints: const BoxConstraints(minWidth: 48, minHeight: 48),
          onPressed: () => _showAlerts(vm),
          icon: Badge(
            isLabelVisible: vm.alerts.isNotEmpty,
            label: Text('${vm.alerts.length}'),
            backgroundColor: vm.alerts.any((a) => a.level == 'critical') ? AppColors.alertRed : AppColors.alertAmber,
            child: Icon(
              vm.alerts.isEmpty ? Icons.notifications_none_rounded : Icons.notifications_active_rounded,
              color: vm.alerts.isEmpty ? AppColors.textMuted : AppColors.alertAmber,
            ),
          ),
        ),

        // Refresh Button
        Padding(
          padding: const EdgeInsets.only(right: 6),
          child: IconButton(
            icon: vm.isLoading
                ? const SizedBox(
                    width: 18,
                    height: 18,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color: AppColors.neonCyan,
                    ),
                  )
                : const Icon(
                    Icons.sync_rounded,
                    color: AppColors.neonCyan,
                    size: 22,
                  ),
            onPressed:
                vm.isLoading ? null : () => vm.fetchTelemetry(silent: false),
            tooltip: 'รีเฟรชข้อมูล',
          ),
        ),
      ],
    );
  }

  void _showAlerts(TelemetryViewModel vm) {
    showModalBottomSheet<void>(
      context: context,
      backgroundColor: AppColors.darkCard,
      showDragHandle: true,
      isScrollControlled: true,
      builder: (ctx) {
        final alerts = vm.alerts;
        return SafeArea(
          child: Padding(
            padding: const EdgeInsets.fromLTRB(20, 4, 20, 20),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('การแจ้งเตือน', style: thText(18, weight: FontWeight.w700, color: AppColors.textWhite)),
                const SizedBox(height: 12),
                if (alerts.isEmpty)
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 24),
                    child: Row(children: [
                      const Icon(Icons.check_circle_rounded, color: AppColors.neonGreen),
                      const SizedBox(width: 10),
                      Text('ทุกอย่างปกติ ไม่มีการแจ้งเตือน', style: thText(14)),
                    ]),
                  )
                else
                  for (final AlertItem a in alerts)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 10),
                      child: AppCard(
                        borderColor: a.level == 'critical' ? AppColors.alertRed : AppColors.alertAmber,
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Icon(
                              a.level == 'critical' ? Icons.error_rounded : Icons.warning_amber_rounded,
                              color: a.level == 'critical' ? AppColors.alertRed : AppColors.alertAmber,
                            ),
                            const SizedBox(width: 10),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(a.title, style: thText(14.5, weight: FontWeight.w700, color: AppColors.textWhite)),
                                  Text(a.detail, style: thText(12.5, color: AppColors.textBody)),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                Text('การแจ้งเตือนแสดงเฉพาะขณะเปิดแอป (การแจ้งเตือนเบื้องหลังต้องเพิ่ม Firebase ในภายหลัง)',
                    style: thText(11.5, color: AppColors.textMuted)),
              ],
            ),
          ),
        );
      },
    );
  }

  // ── Bottom Nav ───────────────────────────────────────────
  Widget _buildBottomNav() {
    return Container(
      decoration: const BoxDecoration(
        color: Color(0xFF0C1322), // Dark obsidian glass
        border: Border(
          top: BorderSide(color: Color(0xFF1B2842), width: 1.2),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black54,
            blurRadius: 16,
            offset: Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        child: SizedBox(
          height: 66,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: List.generate(_navItems.length, (index) {
              final item = _navItems[index];
              final isSelected = _currentIndex == index;
              return Expanded(
                child: GestureDetector(
                  onTap: () => _onNavTap(index),
                  behavior: HitTestBehavior.opaque,
                  child: Semantics(
                    button: true,
                    selected: isSelected,
                    label: item.label,
                    child: AnimatedContainer(
                    duration: const Duration(milliseconds: 200),
                    curve: Curves.easeInOut,
                    padding: const EdgeInsets.symmetric(vertical: 4),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        AnimatedContainer(
                          duration: const Duration(milliseconds: 200),
                          padding: const EdgeInsets.symmetric(
                            horizontal: 12,
                            vertical: 3.5,
                          ),
                          decoration: BoxDecoration(
                            color: isSelected
                                ? AppColors.neonCyan.withAlpha(25)
                                : Colors.transparent,
                            borderRadius: BorderRadius.circular(20),
                            border: isSelected
                                ? Border.all(
                                    color: AppColors.neonCyan.withAlpha(120),
                                    width: 1.2,
                                  )
                                : null,
                            boxShadow: isSelected
                                ? [
                                    BoxShadow(
                                      color: AppColors.neonCyan.withAlpha(40),
                                      blurRadius: 10,
                                      offset: const Offset(0, 2),
                                    ),
                                  ]
                                : null,
                          ),
                          child: Icon(
                            item.icon,
                            size: 20,
                            color: isSelected
                                ? AppColors.neonCyanLight
                                : const Color(0xFF64748B),
                          ),
                        ),
                        const SizedBox(height: 3),
                        AnimatedDefaultTextStyle(
                          duration: const Duration(milliseconds: 200),
                          style: GoogleFonts.rajdhani(
                            fontSize: 11.0,
                            fontWeight: isSelected
                                ? FontWeight.w700
                                : FontWeight.w600,
                            color: isSelected
                                ? AppColors.neonCyanLight
                                : const Color(0xFF64748B),
                            letterSpacing: 0.3,
                          ),
                          child: Text(
                            item.label,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                  ),
                    ),
                ),
              );
            }),
          ),
        ),
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────
// 🎛️ Mode Badge
// ─────────────────────────────────────────────────────────────
class _ModeBadge extends StatelessWidget {
  const _ModeBadge({required this.vm});
  final TelemetryViewModel vm;

  @override
  Widget build(BuildContext context) {
    final mode = vm.controlMode;
    final String emoji;
    final Color color;

    switch (mode) {
      case 'auto':
        emoji = '🤖';
        color = AppColors.neonGreen;
      case 'ai':
        emoji = '✨';
        color = AppColors.neonCyan;
      default: // manual
        emoji = '🕹️';
        color = AppColors.neonOrange;
    }

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 4),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        decoration: BoxDecoration(
          color: color.withAlpha(20),
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: color.withAlpha(100), width: 1),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(emoji, style: const TextStyle(fontSize: 11)),
            const SizedBox(width: 4),
            Text(
              mode.toUpperCase(),
              style: GoogleFonts.orbitron(
                fontSize: 9,
                fontWeight: FontWeight.w800,
                color: color,
                letterSpacing: 0.5,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ── Nav item descriptor ──
class _NavItem {
  final String emoji;
  final String label;
  final IconData icon;
  const _NavItem({
    required this.emoji,
    required this.label,
    required this.icon,
  });
}
