import 'dart:typed_data';
import 'package:flutter/material.dart';
import '../../models/detected_object.dart';
import '../theme/app_theme.dart';

class ImageAnalysisModal extends StatelessWidget {
  final Uint8List imageBytes;
  final List<DetectedObject> objects;
  final int inferenceTimeMs;

  const ImageAnalysisModal({
    super.key,
    required this.imageBytes,
    required this.objects,
    required this.inferenceTimeMs,
  });

  @override
  Widget build(BuildContext context) {
    return Dialog(
      backgroundColor: Colors.transparent,
      insetPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
      child: Container(
        decoration: BoxDecoration(
          color: const Color(0xFF0F172A),
          borderRadius: BorderRadius.circular(24),
          border: Border.all(color: AppTheme.primaryCyan, width: 1.5),
          boxShadow: [
            BoxShadow(
              color: AppTheme.primaryCyan.withValues(alpha: 0.25),
              blurRadius: 24,
              spreadRadius: 2,
            ),
          ],
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Modal Header
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 16, 16, 12),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: AppTheme.primaryCyan.withValues(alpha: 0.15),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.analytics_outlined, color: AppTheme.primaryCyan, size: 22),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'ผลวิเคราะห์ตรวจจับวัตถุ (AI Vision)',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Text(
                          'พบ ${objects.length} รายการ • ความเร็ว $inferenceTimeMs ms',
                          style: const TextStyle(color: Colors.white60, fontSize: 12),
                        ),
                      ],
                    ),
                  ),
                  IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(Icons.close, color: Colors.white70),
                  ),
                ],
              ),
            ),
            const Divider(color: AppTheme.border, height: 1),

            // Image Preview with Bounding Box Overlay
            ConstrainedBox(
              constraints: const BoxConstraints(maxHeight: 280),
              child: ClipRRect(
                child: Stack(
                  fit: StackFit.passthrough,
                  alignment: Alignment.center,
                  children: [
                    Image.memory(
                      imageBytes,
                      fit: BoxFit.contain,
                    ),
                    Positioned.fill(
                      child: LayoutBuilder(
                        builder: (ctx, constraints) {
                          return CustomPaint(
                            size: Size(constraints.maxWidth, constraints.maxHeight),
                            painter: _StaticImageBoundingBoxPainter(
                              objects: objects,
                              canvasSize: Size(constraints.maxWidth, constraints.maxHeight),
                            ),
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const Divider(color: AppTheme.border, height: 1),

            // Detected Objects List
            Flexible(
              child: Container(
                constraints: const BoxConstraints(maxHeight: 240),
                padding: const EdgeInsets.all(16),
                child: objects.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.search_off, size: 40, color: Colors.white.withValues(alpha: 0.3)),
                            const SizedBox(height: 8),
                            const Text(
                              'ไม่พบวัตถุที่ตรงตามเกณฑ์ความเชื่อมั่นที่ตั้งไว้',
                              style: TextStyle(color: Colors.white54, fontSize: 13),
                            ),
                          ],
                        ),
                      )
                    : ListView.separated(
                        shrinkWrap: true,
                        itemCount: objects.length,
                        separatorBuilder: (_, __) => const SizedBox(height: 8),
                        itemBuilder: (context, index) {
                          final item = objects[index];
                          return Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                            decoration: BoxDecoration(
                              color: const Color(0xFF1E293B),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(
                                color: item.color.withValues(alpha: 0.5),
                                width: 1.2,
                              ),
                            ),
                            child: Row(
                              children: [
                                Container(
                                  width: 10,
                                  height: 10,
                                  decoration: BoxDecoration(
                                    color: item.color,
                                    shape: BoxShape.circle,
                                    boxShadow: [
                                      BoxShadow(
                                        color: item.color.withValues(alpha: 0.6),
                                        blurRadius: 6,
                                      ),
                                    ],
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        item.labelTh,
                                        style: const TextStyle(
                                          color: Colors.white,
                                          fontWeight: FontWeight.bold,
                                          fontSize: 14,
                                        ),
                                      ),
                                      Text(
                                        '${item.labelEn.toUpperCase()} • หมวด ${item.category}',
                                        style: TextStyle(
                                          color: Colors.white.withValues(alpha: 0.6),
                                          fontSize: 11,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: item.confidenceColor.withValues(alpha: 0.15),
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: item.confidenceColor, width: 1),
                                  ),
                                  child: Text(
                                    item.confidencePercent,
                                    style: TextStyle(
                                      color: item.confidenceColor,
                                      fontWeight: FontWeight.w900,
                                      fontSize: 13,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
              ),
            ),

            // Modal Bottom Actions
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              child: ElevatedButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(Icons.check, color: Colors.black87),
                label: const Text(
                  'เสร็จสิ้น (Done)',
                  style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primaryCyan,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _StaticImageBoundingBoxPainter extends CustomPainter {
  final List<DetectedObject> objects;
  final Size canvasSize;

  _StaticImageBoundingBoxPainter({
    required this.objects,
    required this.canvasSize,
  });

  @override
  void paint(Canvas canvas, Size size) {
    for (final obj in objects) {
      final rect = Rect.fromLTWH(
        obj.normalizedRect.left * size.width,
        obj.normalizedRect.top * size.height,
        obj.normalizedRect.width * size.width,
        obj.normalizedRect.height * size.height,
      );

      // Box fill
      canvas.drawRRect(
        RRect.fromRectAndRadius(rect, const Radius.circular(6)),
        Paint()
          ..color = obj.color.withValues(alpha: 0.12)
          ..style = PaintingStyle.fill,
      );

      // Box border
      canvas.drawRRect(
        RRect.fromRectAndRadius(rect, const Radius.circular(6)),
        Paint()
          ..color = obj.color
          ..strokeWidth = 2.0
          ..style = PaintingStyle.stroke,
      );

      // Label Pill
      final span = TextSpan(
        text: '${obj.labelTh} ${obj.confidencePercent}',
        style: const TextStyle(
          color: Colors.white,
          fontSize: 10,
          fontWeight: FontWeight.bold,
          backgroundColor: Color(0xCC000000),
        ),
      );
      final tp = TextPainter(text: span, textDirection: TextDirection.ltr)..layout();
      tp.paint(canvas, Offset(rect.left.clamp(2.0, size.width - tp.width - 2), (rect.top - 14).clamp(2.0, size.height - 16)));
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => true;
}
