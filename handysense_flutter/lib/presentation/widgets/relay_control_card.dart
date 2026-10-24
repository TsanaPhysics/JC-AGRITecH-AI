import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';
import 'common.dart';

/// การ์ดควบคุมรีเลย์ 1 ช่อง: ชื่อ, สถานะ 3 ขั้น, นับถอยหลังปิดอัตโนมัติ, สวิตช์ (เป้าสัมผัส ≥ 48dp)
class RelayControlCard extends StatelessWidget {
  const RelayControlCard({
    super.key,
    required this.view,
    required this.enabled,
    required this.onToggle,
    required this.onTimerOn,
  });

  final RelayView view;
  final bool enabled;
  final VoidCallback onToggle;
  final VoidCallback onTimerOn; // เปิดแบบตั้งเวลาปิดอัตโนมัติ

  String _statusText() {
    switch (view.cmd) {
      case CmdState.sending:
        return 'กำลังส่งคำสั่ง...';
      case CmdState.awaitingBoard:
        return 'เซิร์ฟเวอร์รับแล้ว รอบอร์ดยืนยัน...';
      case CmdState.failed:
        return 'ส่งคำสั่งไม่สำเร็จ';
      case CmdState.unconfirmed:
        return 'บอร์ดยังไม่ยืนยัน';
      default:
        return view.on ? 'กำลังทำงาน' : 'ปิดอยู่';
    }
  }

  @override
  Widget build(BuildContext context) {
    final on = view.on;
    final accent = on ? AppColors.neonGreen : AppColors.textDim;
    final warn = view.cmd == CmdState.failed || view.cmd == CmdState.unconfirmed;
    final name = relayShortName(view.item.name);
    final left = view.timerLeftSec;

    return Semantics(
      container: true,
      label: '$name ${on ? 'เปิด' : 'ปิด'} ${_statusText()}',
      child: AppCard(
        borderColor: warn ? AppColors.alertAmber : (on ? AppColors.neonGreen.withAlpha(120) : AppColors.borderDark),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 48,
                  height: 48,
                  decoration: BoxDecoration(
                    color: accent.withAlpha(30),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  child: Icon(relayIcon(view.item.id), color: accent, size: 26),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('รีเลย์ ${view.item.id} · $name',
                          style: thText(15, weight: FontWeight.w700, color: AppColors.textWhite)),
                      const SizedBox(height: 2),
                      Row(
                        children: [
                          if (view.busy)
                            const Padding(
                              padding: EdgeInsets.only(right: 6),
                              child: SizedBox(
                                  width: 12, height: 12, child: CircularProgressIndicator(strokeWidth: 2)),
                            )
                          else
                            Padding(
                              padding: const EdgeInsets.only(right: 6),
                              child: Icon(
                                warn
                                    ? Icons.warning_amber_rounded
                                    : (on ? Icons.power_rounded : Icons.power_off_rounded),
                                size: 14,
                                color: warn ? AppColors.alertAmber : accent,
                              ),
                            ),
                          Flexible(
                            child: Text(_statusText(),
                                style: thText(12.5,
                                    weight: FontWeight.w600,
                                    color: warn ? AppColors.alertAmber : accent)),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                Semantics(
                  button: true,
                  toggled: on,
                  label: 'สวิตช์ $name',
                  child: SizedBox(
                    width: 64,
                    height: 48,
                    child: FittedBox(
                      child: Switch(
                        value: on,
                        onChanged: (enabled && !view.busy) ? (_) => onToggle() : null,
                        activeColor: Colors.black,
                        activeTrackColor: AppColors.neonGreen,
                      ),
                    ),
                  ),
                ),
              ],
            ),
            if (left != null) ...[
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: AppColors.neonCyan.withAlpha(22),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.timer_outlined, size: 18, color: AppColors.neonCyanLight),
                    const SizedBox(width: 8),
                    Text('ปิดอัตโนมัติใน ${fmtCountdown(left)}',
                        style: thText(13, weight: FontWeight.w600, color: AppColors.neonCyanLight)),
                  ],
                ),
              ),
            ],
            if (!on && enabled) ...[
              const SizedBox(height: 10),
              Align(
                alignment: Alignment.centerRight,
                child: TextButton.icon(
                  onPressed: view.busy ? null : onTimerOn,
                  icon: const Icon(Icons.timer_outlined, size: 18),
                  label: Text('เปิดแบบตั้งเวลา', style: thText(13, weight: FontWeight.w600, color: AppColors.neonCyan)),
                  style: TextButton.styleFrom(minimumSize: const Size(48, 40)),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
