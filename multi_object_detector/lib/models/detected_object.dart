import 'dart:math' as math;
import 'package:flutter/material.dart';

enum ObjectCategoryGroup {
  all('ทั้งหมด / All', Icons.apps, Color(0xFF00E5FF)),
  people('บุคคล / People', Icons.person, Color(0xFF00E5FF)),
  vehicles('ยานพาหนะ / Vehicles', Icons.directions_car, Color(0xFF3D82FF)),
  animals('สัตว์ / Animals', Icons.pets, Color(0xFFFF4081)),
  food('อาหาร / Food', Icons.restaurant, Color(0xFFFF9100)),
  electronics('อิเล็กทรอนิกส์ / Electronics', Icons.devices, Color(0xFF1DE9B6)),
  furniture('เฟอร์นิเจอร์ / Furniture', Icons.chair, Color(0xFF8D6E63)),
  kitchen('เครื่องครัว / Kitchen', Icons.kitchen, Color(0xFFFFB74D)),
  accessories('ของใช้ / Accessories', Icons.backpack, Color(0xFF26A69A)),
  sports('กีฬา / Sports', Icons.sports_soccer, Color(0xFF66BB6A)),
  indoor('ของใช้ในบ้าน / Indoor', Icons.home, Color(0xFFFFCA28));

  final String label;
  final IconData icon;
  final Color themeColor;

  const ObjectCategoryGroup(this.label, this.icon, this.themeColor);
}

class DetectedObject {
  final String id;
  final int classId;
  final String labelEn;
  final String labelTh;
  final String category;
  final double confidence;
  /// Bounding box normalized coordinates in [0.0, 1.0] range
  /// left, top, width, height relative to input image dimension
  final Rect normalizedRect;
  final Color color;
  final DateTime detectedAt;

  const DetectedObject({
    required this.id,
    required this.classId,
    required this.labelEn,
    required this.labelTh,
    required this.category,
    required this.confidence,
    required this.normalizedRect,
    required this.color,
    required this.detectedAt,
  });

  /// Confidence percentage as string (e.g. "92.4%")
  String get confidencePercent => '${(confidence * 100).toStringAsFixed(1)}%';

  /// Confidence label level
  String get confidenceLevel {
    if (confidence >= 0.85) return 'สูงมาก (Very High)';
    if (confidence >= 0.70) return 'สูง (High)';
    if (confidence >= 0.50) return 'ปานกลาง (Medium)';
    return 'ต่ำ (Low)';
  }

  /// Confidence badge color
  Color get confidenceColor {
    if (confidence >= 0.85) return const Color(0xFF00E676);
    if (confidence >= 0.70) return const Color(0xFF00E5FF);
    if (confidence >= 0.50) return const Color(0xFFFFD600);
    return const Color(0xFFFF5252);
  }

  /// Convert normalized rect into actual screen pixel Rect with BoxFit.cover alignment
  Rect toScreenRect({
    required Size screenSize,
    required Size imageSize,
    bool isRotated = false,
  }) {
    // When preview is rendered with BoxFit.cover:
    double camW = imageSize.width;
    double camH = imageSize.height;

    // Normalize orientation: portrait width is smaller than height
    if (camW > camH) {
      final tmp = camW;
      camW = camH;
      camH = tmp;
    }

    if (camW <= 0 || camH <= 0 || screenSize.width <= 0 || screenSize.height <= 0) {
      return Rect.fromLTWH(
        normalizedRect.left * screenSize.width,
        normalizedRect.top * screenSize.height,
        normalizedRect.width * screenSize.width,
        normalizedRect.height * screenSize.height,
      );
    }

    final double scale = math.max(screenSize.width / camW, screenSize.height / camH);
    final double fittedW = camW * scale;
    final double fittedH = camH * scale;
    final double offsetX = (screenSize.width - fittedW) / 2.0;
    final double offsetY = (screenSize.height - fittedH) / 2.0;

    final double screenLeft = offsetX + normalizedRect.left * fittedW;
    final double screenTop = offsetY + normalizedRect.top * fittedH;
    final double screenWidth = normalizedRect.width * fittedW;
    final double screenHeight = normalizedRect.height * fittedH;

    return Rect.fromLTWH(screenLeft, screenTop, screenWidth, screenHeight);
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'classId': classId,
    'labelEn': labelEn,
    'labelTh': labelTh,
    'category': category,
    'confidence': confidence,
    'rect': [
      normalizedRect.left,
      normalizedRect.top,
      normalizedRect.width,
      normalizedRect.height,
    ],
    'detectedAt': detectedAt.toIso8601String(),
  };

  DetectedObject copyWith({
    String? id,
    int? classId,
    String? labelEn,
    String? labelTh,
    String? category,
    double? confidence,
    Rect? normalizedRect,
    Color? color,
    DateTime? detectedAt,
  }) {
    return DetectedObject(
      id: id ?? this.id,
      classId: classId ?? this.classId,
      labelEn: labelEn ?? this.labelEn,
      labelTh: labelTh ?? this.labelTh,
      category: category ?? this.category,
      confidence: confidence ?? this.confidence,
      normalizedRect: normalizedRect ?? this.normalizedRect,
      color: color ?? this.color,
      detectedAt: detectedAt ?? this.detectedAt,
    );
  }
}
