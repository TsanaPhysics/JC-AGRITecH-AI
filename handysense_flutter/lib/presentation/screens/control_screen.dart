import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/common.dart';
import '../widgets/relay_control_card.dart';
import '../widgets/tri_mode_selector.dart';
import 'audit_screen.dart';
import 'schedule_screen.dart';

/// หน้าควบคุม: โหมดการทำงาน + รีเลย์ทั้ง 4 ช่อง + ปิดทั้งหมด + ทางลัดไปตารางเวลา/บันทึกการใช้งาน
class ControlScreen extends StatelessWidget {
  const ControlScreen({super.key});

  static const List<int> _presets = [5, 15, 30, 60];

  Future<void> _pickDuration(BuildContext context, TelemetryViewModel vm, int relayId) async {
    final minutes = await showModalBottomSheet<int>(
      context: context,
      backgroundColor: AppColors.darkCard,
      showDragHandle: true,
      builder: (ctx) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.fromLTRB(20, 4, 20, 20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('เปิดรีเลย์ $relayId แล้วปิดอัตโนมัติ',
                  style: thText(16, weight: FontWeight.w700, color: AppColors.textWhite)),
              const SizedBox(height: 4),
              Text('เลือกระยะเวลา ระบบจะสั่งปิดให้เมื่อครบเวลา',
                  style: thText(12.5, color: AppColors.textMuted)),
              const SizedBox(height: 16),
              Wrap(
                spacing: 10,
                runSpacing: 10,
                children: [
                  for (final m in _presets)
                    ActionChip(
                      label: Text(m >= 60 ? '${m ~/ 60} ชั่วโมง' : '$m นาที',
                          style: thText(14, weight: FontWeight.w600, color: AppColors.textWhite)),
                      onPressed: () => Navigator.pop(ctx, m),
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                    ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
    if (minutes != null) {
      await vm.toggleRelay(relayId, target: true, durationMin: minutes);
    }
  }

  Future<void> _confirmAllOff(BuildContext context, TelemetryViewModel vm) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkCard,
        title: Text('ปิดรีเลย์ทั้งหมด?', style: thText(17, weight: FontWeight.w700, color: AppColors.textWhite)),
        content: Text('ระบบจะสั่งปิดรีเลย์ที่กำลังทำงานทุกช่อง', style: thText(14)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: Text('ยกเลิก', style: thText(14))),
          FilledButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: FilledButton.styleFrom(backgroundColor: AppColors.alertRed),
            child: Text('ปิดทั้งหมด', style: thText(14, weight: FontWeight.w700, color: Colors.white)),
          ),
        ],
      ),
    );
    if (ok != true) return;
    final failed = await vm.allOff();
    if (context.mounted) {
      showAppSnack(context, failed == 0 ? 'ส่งคำสั่งปิดทั้งหมดแล้ว' : 'ปิดไม่สำเร็จ $failed ช่อง', error: failed > 0);
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final canControl = vm.canControl;
    final anyOn = [for (int i = 1; i <= 4; i++) vm.relayView(i).on].any((e) => e);
    final sw = MediaQuery.of(context).size.width;
    final cols = sw > 700 ? 2 : 1;

    return RefreshIndicator(
      onRefresh: () => vm.fetchTelemetry(silent: false),
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
        children: [
          Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 900),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (!canControl)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: AppCard(
                        borderColor: AppColors.alertAmber,
                        child: Row(
                          children: [
                            const Icon(Icons.lock_outline_rounded, color: AppColors.alertAmber),
                            const SizedBox(width: 10),
                            Expanded(
                              child: Text('ปิดการควบคุมชั่วคราว: ${vm.controlBlockedReason}',
                                  style: thText(13, weight: FontWeight.w600, color: AppColors.alertAmber)),
                            ),
                          ],
                        ),
                      ),
                    ),
                  const SectionTitle('โหมดการทำงาน', subtitle: 'ควบคุมเอง / อัตโนมัติ / AI'),
                  const SizedBox(height: 10),
                  TriModeSelector(currentMode: vm.controlMode, onModeChanged: vm.setMode),
                  const SizedBox(height: 22),
                  SectionTitle(
                    'รีเลย์',
                    subtitle: 'แตะสวิตช์เพื่อสั่งงาน — บอร์ดยืนยันภายใน ~1–3 วินาที',
                    trailing: OutlinedButton.icon(
                      onPressed: (canControl && anyOn) ? () => _confirmAllOff(context, vm) : null,
                      icon: const Icon(Icons.power_settings_new_rounded, size: 18),
                      label: Text('ปิดทั้งหมด', style: thText(13, weight: FontWeight.w700)),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppColors.alertRed,
                        side: BorderSide(color: anyOn && canControl ? AppColors.alertRed : AppColors.borderDark),
                        minimumSize: const Size(48, 44),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  if (cols == 1)
                    Column(
                      children: [
                        for (int id = 1; id <= 4; id++) ...[
                          RelayControlCard(
                            view: vm.relayView(id),
                            enabled: canControl,
                            onToggle: () => vm.toggleRelay(id),
                            onTimerOn: () => _pickDuration(context, vm, id),
                          ),
                          if (id < 4) const SizedBox(height: 12),
                        ],
                      ],
                    )
                  else
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Expanded(
                          child: Column(
                            children: [
                              for (int id in [1, 3]) ...[
                                RelayControlCard(
                                  view: vm.relayView(id),
                                  enabled: canControl,
                                  onToggle: () => vm.toggleRelay(id),
                                  onTimerOn: () => _pickDuration(context, vm, id),
                                ),
                                if (id == 1) const SizedBox(height: 12),
                              ],
                            ],
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            children: [
                              for (int id in [2, 4]) ...[
                                RelayControlCard(
                                  view: vm.relayView(id),
                                  enabled: canControl,
                                  onToggle: () => vm.toggleRelay(id),
                                  onTimerOn: () => _pickDuration(context, vm, id),
                                ),
                                if (id == 2) const SizedBox(height: 12),
                              ],
                            ],
                          ),
                        ),
                      ],
                    ),
                  const SizedBox(height: 22),
                  const SectionTitle('ระบบอัตโนมัติ'),
                  const SizedBox(height: 10),
                  _NavTile(
                    icon: Icons.event_repeat_rounded,
                    title: 'ตารางเวลา',
                    subtitle: 'ตั้งเวลาเปิด/ปิดรีเลย์ประจำวัน',
                    onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ScheduleScreen())),
                  ),
                  const SizedBox(height: 10),
                  _NavTile(
                    icon: Icons.history_rounded,
                    title: 'บันทึกการใช้งาน',
                    subtitle: 'ใครสั่งอะไร เมื่อไร (ย้อนหลัง)',
                    onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AuditScreen())),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _NavTile extends StatelessWidget {
  const _NavTile({required this.icon, required this.title, required this.subtitle, required this.onTap});
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: AppColors.darkCard,
      borderRadius: BorderRadius.circular(16),
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: onTap,
        child: Container(
          constraints: const BoxConstraints(minHeight: 64),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.borderDark, width: 1.2),
          ),
          child: Row(
            children: [
              Icon(icon, color: AppColors.neonCyan),
              const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(title, style: thText(15, weight: FontWeight.w700, color: AppColors.textWhite)),
                    Text(subtitle, style: thText(12.5, color: AppColors.textMuted)),
                  ],
                ),
              ),
              const Icon(Icons.chevron_right_rounded, color: AppColors.textDim),
            ],
          ),
        ),
      ),
    );
  }
}
