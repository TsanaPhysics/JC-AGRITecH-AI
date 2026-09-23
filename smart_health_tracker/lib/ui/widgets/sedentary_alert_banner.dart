import 'package:flutter/material.dart';
import '../theme/health_theme.dart';

class SedentaryAlertBanner extends StatelessWidget {
  final int sedentaryMinutes;
  final VoidCallback onDismiss;

  const SedentaryAlertBanner({
    super.key,
    required this.sedentaryMinutes,
    required this.onDismiss,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFF2A1B0A),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: HealthTheme.orangeCalorie.withValues(alpha: 0.6)),
        boxShadow: [
          BoxShadow(
            color: HealthTheme.orangeCalorie.withValues(alpha: 0.15),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: HealthTheme.orangeCalorie.withValues(alpha: 0.2),
              shape: BoxShape.circle,
            ),
            child: const Icon(Icons.alarm_on, color: HealthTheme.orangeCalorie, size: 22),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'นั่งนิ่งต่อเนื่องนานเกินเกณฑ์! (Sedentary Alert)',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  'คุณอยู่นิ่งมา $sedentaryMinutes นาทีแล้ว ลุกขึ้นยืดเหยียดขยับตัว 2-3 นาที เพื่อสุขภาพหลอดเลือดและหัวใจ',
                  style: const TextStyle(color: Colors.white70, fontSize: 11),
                ),
              ],
            ),
          ),
          IconButton(
            tooltip: 'ปิดการแจ้งเตือน',
            onPressed: onDismiss,
            icon: const Icon(Icons.close, color: Colors.white54, size: 18),
          ),
        ],
      ),
    );
  }
}
