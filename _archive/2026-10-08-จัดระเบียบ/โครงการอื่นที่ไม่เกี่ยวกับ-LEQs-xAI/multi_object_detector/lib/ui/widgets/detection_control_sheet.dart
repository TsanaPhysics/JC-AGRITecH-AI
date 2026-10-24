import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../services/object_detection_service.dart';
import '../theme/app_theme.dart';

class DetectionControlSheet extends StatelessWidget {
  const DetectionControlSheet({super.key});

  @override
  Widget build(BuildContext context) {
    final detector = context.watch<ObjectDetectionService>();

    return Container(
      padding: const EdgeInsets.fromLTRB(20, 16, 20, 32),
      decoration: const BoxDecoration(
        color: AppTheme.surfaceElevated,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        border: Border(
          top: BorderSide(color: AppTheme.border, width: 1.5),
        ),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Drag Handle
          Center(
            child: Container(
              width: 44,
              height: 4,
              decoration: BoxDecoration(
                color: Colors.white24,
                borderRadius: BorderRadius.circular(2),
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Title & Reset
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Row(
                children: [
                  Icon(Icons.tune, color: AppTheme.primaryCyan, size: 20),
                  SizedBox(width: 8),
                  Text(
                    'การตั้งค่าโมเดล AI (Detection Settings)',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ],
              ),
              TextButton(
                onPressed: () {
                  detector.setConfidenceThreshold(0.50);
                  detector.setMaxDetections(10);
                  detector.setIouThreshold(0.45);
                },
                child: const Text(
                  'รีเซ็ต (Reset)',
                  style: TextStyle(color: AppTheme.primaryCyan, fontSize: 13),
                ),
              ),
            ],
          ),
          const Divider(color: AppTheme.border),
          const SizedBox(height: 12),

          // 1. Confidence Threshold
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'เกณฑ์ความเชื่อมั่นขั้นต่ำ (Confidence Threshold)',
                    style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w600),
                  ),
                  Text(
                    'กรองเฉพาะวัตถุที่มีความมั่นใจมากกว่าเกณฑ์นี้',
                    style: TextStyle(color: Colors.white54, fontSize: 11),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.primaryCyan.withValues(alpha: 0.15),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: AppTheme.primaryCyan, width: 1),
                ),
                child: Text(
                  '${(detector.confidenceThreshold * 100).toInt()}%',
                  style: const TextStyle(
                    color: AppTheme.primaryCyan,
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          Slider(
            value: detector.confidenceThreshold,
            min: 0.10,
            max: 0.95,
            divisions: 17,
            label: '${(detector.confidenceThreshold * 100).toInt()}%',
            onChanged: (val) => detector.setConfidenceThreshold(val),
          ),
          const SizedBox(height: 16),

          // 2. Max Detections
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'จำนวนวัตถุสูงสุดพร้อมกัน (Max Detections)',
                    style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w600),
                  ),
                  Text(
                    'จำกัดจำนวนกรอบวัตถุที่จะแสดงบนหน้าจอในแต่ละเฟรม',
                    style: TextStyle(color: Colors.white54, fontSize: 11),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.accentGreen.withValues(alpha: 0.15),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: AppTheme.accentGreen, width: 1),
                ),
                child: Text(
                  '${detector.maxDetections} ชิ้น',
                  style: const TextStyle(
                    color: AppTheme.accentGreen,
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          Slider(
            value: detector.maxDetections.toDouble(),
            min: 1,
            max: 20,
            divisions: 19,
            activeColor: AppTheme.accentGreen,
            thumbColor: AppTheme.accentGreen,
            label: '${detector.maxDetections}',
            onChanged: (val) => detector.setMaxDetections(val.toInt()),
          ),
          const SizedBox(height: 16),

          // 3. IoU Threshold (NMS)
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'การกรองกล่องซ้อนทับ (NMS IoU Threshold)',
                    style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.w600),
                  ),
                  Text(
                    'ตัดกรอบวัตถุที่ซ้ำซ้อนกันในตำแหน่งเดียวกัน',
                    style: TextStyle(color: Colors.white54, fontSize: 11),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.accentAmber.withValues(alpha: 0.15),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: AppTheme.accentAmber, width: 1),
                ),
                child: Text(
                  detector.iouThreshold.toStringAsFixed(2),
                  style: const TextStyle(
                    color: AppTheme.accentAmber,
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          Slider(
            value: detector.iouThreshold,
            min: 0.20,
            max: 0.80,
            divisions: 12,
            activeColor: AppTheme.accentAmber,
            thumbColor: AppTheme.accentAmber,
            label: detector.iouThreshold.toStringAsFixed(2),
            onChanged: (val) => detector.setIouThreshold(val),
          ),
        ],
      ),
    );
  }
}
