import 'package:flutter/material.dart';
import '../../models/detected_object.dart';
import '../theme/app_theme.dart';

class DetectedObjectsSummaryList extends StatelessWidget {
  final List<DetectedObject> objects;

  const DetectedObjectsSummaryList({
    super.key,
    required this.objects,
  });

  @override
  Widget build(BuildContext context) {
    if (objects.isEmpty) {
      return Container(
        margin: const EdgeInsets.symmetric(horizontal: 16.0),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: const Color(0xCC0E1422),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppTheme.border),
        ),
        child: const Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.search, size: 16, color: Colors.white38),
            SizedBox(width: 8),
            Expanded(
              child: Text(
                'กำลังสแกนหาวัตถุในเฟรมกล้อง... (Scanning Objects)',
                style: TextStyle(color: Colors.white54, fontSize: 12),
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      );
    }

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16.0),
      padding: const EdgeInsets.symmetric(horizontal: 14.0, vertical: 10.0),
      decoration: BoxDecoration(
        color: const Color(0xEE0B101D),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: AppTheme.border, width: 1.2),
        boxShadow: const [
          BoxShadow(
            color: Colors.black45,
            blurRadius: 10,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            children: [
              const Icon(Icons.analytics_outlined, color: AppTheme.primaryCyan, size: 16),
              const SizedBox(width: 6),
              Text(
                'รายการวัตถุที่ตรวจพบพร้อมกัน (${objects.length})',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Spacer(),
              const Text(
                'ความเชื่อมั่น (Confidence)',
                style: TextStyle(color: Colors.white54, fontSize: 11),
              ),
            ],
          ),
          const SizedBox(height: 8),

          // Object items
          ConstrainedBox(
            constraints: const BoxConstraints(maxHeight: 120),
            child: ListView.separated(
              shrinkWrap: true,
              itemCount: objects.length,
              separatorBuilder: (_, __) => const SizedBox(height: 6),
              itemBuilder: (context, index) {
                final obj = objects[index];
                return Row(
                  children: [
                    // Dot
                    Container(
                      width: 8,
                      height: 8,
                      decoration: BoxDecoration(
                        color: obj.color,
                        shape: BoxShape.circle,
                      ),
                    ),
                    const SizedBox(width: 8),

                    // Label Thai
                    Expanded(
                      flex: 4,
                      child: Text(
                        obj.labelTh,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 12.5,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),

                    // Label English Subtitle
                    Expanded(
                      flex: 3,
                      child: Text(
                        obj.labelEn,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          color: Colors.white38,
                          fontSize: 11,
                        ),
                      ),
                    ),

                    // Confidence Score Badge
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                      decoration: BoxDecoration(
                        color: obj.confidenceColor.withValues(alpha: 0.15),
                        borderRadius: BorderRadius.circular(6),
                        border: Border.all(color: obj.confidenceColor.withValues(alpha: 0.8), width: 0.8),
                      ),
                      child: Text(
                        obj.confidencePercent,
                        style: TextStyle(
                          color: obj.confidenceColor,
                          fontSize: 12,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
