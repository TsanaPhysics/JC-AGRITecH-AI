import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';

/// สไตล์ตัวอักษรไทยกลางของแอป (Noto Sans Thai อ่านชัดทั้งตัวไทย/อังกฤษ)
TextStyle thText(double size, {FontWeight weight = FontWeight.w500, Color color = AppColors.textBody, double? height}) {
  return GoogleFonts.notoSansThai(fontSize: size, fontWeight: weight, color: color, height: height);
}

String fmtAge(int? sec) {
  if (sec == null) return 'ไม่ทราบ';
  if (sec < 5) return 'เมื่อสักครู่';
  if (sec < 60) return '$sec วินาทีที่แล้ว';
  if (sec < 3600) return '${sec ~/ 60} นาทีที่แล้ว';
  if (sec < 86400) return '${sec ~/ 3600} ชม. ${(sec % 3600) ~/ 60} นาทีที่แล้ว';
  return '${sec ~/ 86400} วันที่แล้ว';
}

String fmtCountdown(int sec) {
  final m = sec ~/ 60;
  final s = sec % 60;
  if (m >= 60) return '${m ~/ 60} ชม. ${m % 60} นาที';
  return '${m.toString().padLeft(2, '0')}:${s.toString().padLeft(2, '0')}';
}

const List<IconData> relayIcons = [
  Icons.water_rounded, // รีเลย์ 1 ปั๊มน้ำหลัก
  Icons.plumbing_rounded, // รีเลย์ 2 วาล์วโซลินอยด์/ปั๊ม 2
  Icons.opacity_rounded, // รีเลย์ 3 วาล์วน้ำผิวดิน
  Icons.cloud_rounded, // รีเลย์ 4 พ่นหมอก
];

IconData relayIcon(int id) => relayIcons[(id - 1).clamp(0, relayIcons.length - 1)];

/// ชื่อสั้นภาษาไทยของรีเลย์ (ตัดวงเล็บภาษาอังกฤษท้ายชื่อที่เซิร์ฟเวอร์ส่งมา)
String relayShortName(String full) {
  final i = full.indexOf('(');
  return (i > 0 ? full.substring(0, i) : full).trim();
}

const List<String> thaiDays = ['จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.', 'อา.'];

({Color color, IconData icon, String label}) connStyle(ConnState s) {
  switch (s) {
    case ConnState.live:
      return (color: AppColors.neonGreen, icon: Icons.check_circle_rounded, label: 'ออนไลน์');
    case ConnState.delayed:
      return (color: AppColors.alertAmber, icon: Icons.schedule_rounded, label: 'ข้อมูลล่าช้า');
    case ConnState.boardOffline:
      return (color: AppColors.alertRed, icon: Icons.portable_wifi_off_rounded, label: 'บอร์ดออฟไลน์');
    case ConnState.serverDown:
      return (color: AppColors.alertRed, icon: Icons.cloud_off_rounded, label: 'เชื่อมต่อเซิร์ฟเวอร์ไม่ได้');
    case ConnState.connecting:
      return (color: AppColors.neonCyan, icon: Icons.sync_rounded, label: 'กำลังเชื่อมต่อ');
  }
}

/// แถบสถานะการเชื่อมต่อ: แสดงทั้งสี ไอคอน และข้อความ (ไม่ใช้สีอย่างเดียว)
class ConnectionBanner extends StatelessWidget {
  const ConnectionBanner({super.key, required this.vm});
  final TelemetryViewModel vm;

  @override
  Widget build(BuildContext context) {
    final st = vm.connState;
    final style = connStyle(st);
    if (st == ConnState.live) return const SizedBox.shrink();
    String detail;
    switch (st) {
      case ConnState.delayed:
        detail = 'ข้อมูลล่าสุด ${fmtAge(vm.dataAgeSeconds)} — ค่าที่เห็นอาจไม่ใช่ปัจจุบัน';
      case ConnState.boardOffline:
        detail = 'ได้รับข้อมูลล่าสุด ${fmtAge(vm.dataAgeSeconds)} — ปิดการสั่งรีเลย์ชั่วคราว';
      case ConnState.serverDown:
        detail = vm.errorMessage ?? 'ตรวจสอบอินเทอร์เน็ตหรือ URL ที่หน้าตั้งค่า';
      default:
        detail = 'กรุณารอสักครู่';
    }
    return Semantics(
      liveRegion: true,
      label: '${style.label}. $detail',
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        color: style.color.withAlpha(30),
        child: Row(
          children: [
            Icon(style.icon, color: style.color, size: 20),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(style.label, style: thText(13, weight: FontWeight.w700, color: style.color)),
                  Text(detail, style: thText(12, color: AppColors.textBody)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class SectionTitle extends StatelessWidget {
  const SectionTitle(this.title, {super.key, this.subtitle, this.trailing});
  final String title;
  final String? subtitle;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: thText(16, weight: FontWeight.w700, color: AppColors.textWhite)),
              if (subtitle != null) Text(subtitle!, style: thText(12, color: AppColors.textMuted)),
            ],
          ),
        ),
        if (trailing != null) trailing!,
      ],
    );
  }
}

/// การ์ดมาตรฐานของแอป
class AppCard extends StatelessWidget {
  const AppCard({super.key, required this.child, this.padding = const EdgeInsets.all(16), this.borderColor});
  final Widget child;
  final EdgeInsets padding;
  final Color? borderColor;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: padding,
      decoration: BoxDecoration(
        color: AppColors.darkCard,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: borderColor ?? AppColors.borderDark, width: 1.2),
      ),
      child: child,
    );
  }
}

void showAppSnack(BuildContext context, String msg, {bool error = false}) {
  final m = ScaffoldMessenger.of(context);
  m.hideCurrentSnackBar();
  m.showSnackBar(SnackBar(
    behavior: SnackBarBehavior.floating,
    backgroundColor: error ? const Color(0xFF4A1020) : const Color(0xFF0F3B2A),
    content: Row(
      children: [
        Icon(error ? Icons.error_outline_rounded : Icons.check_circle_outline_rounded,
            color: error ? AppColors.alertRed : AppColors.neonGreen),
        const SizedBox(width: 10),
        Expanded(child: Text(msg, style: thText(13, color: Colors.white))),
      ],
    ),
  ));
}
