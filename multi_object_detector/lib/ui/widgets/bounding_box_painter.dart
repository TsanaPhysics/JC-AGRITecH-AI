import 'package:flutter/material.dart';
import '../../models/detected_object.dart';

class BoundingBoxPainter extends CustomPainter {
  final List<DetectedObject> objects;
  final Size previewSize;
  final Size screenSize;

  BoundingBoxPainter({
    required this.objects,
    required this.previewSize,
    required this.screenSize,
  });

  @override
  void paint(Canvas canvas, Size size) {
    if (objects.isEmpty) return;

    for (final obj in objects) {
      final rect = obj.toScreenRect(
        screenSize: size,
        imageSize: previewSize,
      );

      _drawSingleObject(canvas, rect, obj);
    }
  }

  void _drawSingleObject(Canvas canvas, Rect rect, DetectedObject obj) {
    final Color color = obj.color;

    // 1. Semi-transparent bounding box fill
    final fillPaint = Paint()
      ..color = color.withValues(alpha: 0.08)
      ..style = PaintingStyle.fill;
    canvas.drawRRect(RRect.fromRectAndRadius(rect, const Radius.circular(8)), fillPaint);

    // 2. Neon stroke
    final strokePaint = Paint()
      ..color = color.withValues(alpha: 0.85)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2.0;
    canvas.drawRRect(RRect.fromRectAndRadius(rect, const Radius.circular(8)), strokePaint);

    // 3. Cyber HUD Corner Brackets
    final cornerPaint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeWidth = 3.5
      ..strokeCap = StrokeCap.round;

    final double cornerLen = (rect.width * 0.15).clamp(8.0, 24.0);

    // Top-left
    canvas.drawLine(Offset(rect.left, rect.top + cornerLen), Offset(rect.left, rect.top), cornerPaint);
    canvas.drawLine(Offset(rect.left, rect.top), Offset(rect.left + cornerLen, rect.top), cornerPaint);

    // Top-right
    canvas.drawLine(Offset(rect.right - cornerLen, rect.top), Offset(rect.right, rect.top), cornerPaint);
    canvas.drawLine(Offset(rect.right, rect.top), Offset(rect.right, rect.top + cornerLen), cornerPaint);

    // Bottom-left
    canvas.drawLine(Offset(rect.left, rect.bottom - cornerLen), Offset(rect.left, rect.bottom), cornerPaint);
    canvas.drawLine(Offset(rect.left, rect.bottom), Offset(rect.left + cornerLen, rect.bottom), cornerPaint);

    // Bottom-right
    canvas.drawLine(Offset(rect.right - cornerLen, rect.bottom), Offset(rect.right, rect.bottom), cornerPaint);
    canvas.drawLine(Offset(rect.right, rect.bottom), Offset(rect.right, rect.bottom - cornerLen), cornerPaint);

    // 4. Label & Confidence Header Pill
    _drawLabelPill(canvas, rect, obj, color);
  }

  void _drawLabelPill(Canvas canvas, Rect rect, DetectedObject obj, Color color) {
    final textSpan = TextSpan(
      children: [
        TextSpan(
          text: '${obj.labelTh} ',
          style: const TextStyle(
            color: Colors.white,
            fontSize: 12.0,
            fontWeight: FontWeight.bold,
          ),
        ),
        TextSpan(
          text: obj.confidencePercent,
          style: TextStyle(
            color: obj.confidenceColor,
            fontSize: 12.0,
            fontWeight: FontWeight.w900,
          ),
        ),
      ],
    );

    final textPainter = TextPainter(
      text: textSpan,
      textDirection: TextDirection.ltr,
    )..layout();

    const double hPadding = 8.0;
    const double vPadding = 4.0;
    final double pillWidth = textPainter.width + (hPadding * 2);
    final double pillHeight = textPainter.height + (vPadding * 2);

    // Position above bounding box, or inside if near top edge
    double pillTop = rect.top - pillHeight - 4;
    if (pillTop < 4) {
      pillTop = rect.top + 4;
    }
    final double pillLeft = rect.left.clamp(4.0, screenSize.width - pillWidth - 4);

    final pillRect = Rect.fromLTWH(pillLeft, pillTop, pillWidth, pillHeight);

    // Pill background
    final pillBgPaint = Paint()
      ..color = const Color(0xDD0B101D)
      ..style = PaintingStyle.fill;
    canvas.drawRRect(RRect.fromRectAndRadius(pillRect, const Radius.circular(6)), pillBgPaint);

    // Pill border
    final pillBorderPaint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.2;
    canvas.drawRRect(RRect.fromRectAndRadius(pillRect, const Radius.circular(6)), pillBorderPaint);

    // Draw text
    textPainter.paint(canvas, Offset(pillLeft + hPadding, pillTop + vPadding));
  }

  @override
  bool shouldRepaint(covariant BoundingBoxPainter oldDelegate) {
    return oldDelegate.objects != objects;
  }
}
