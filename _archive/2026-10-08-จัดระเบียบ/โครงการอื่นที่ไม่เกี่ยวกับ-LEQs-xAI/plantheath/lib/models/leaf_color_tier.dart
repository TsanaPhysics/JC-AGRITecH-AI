import 'package:flutter/material.dart';

enum ScaleMode {
  spad,
  nitrogen,
  phosphorus,
  potassium,
  diseaseSeverity,
  micronutrient,
}

class LeafColorTier {
  final int tier;
  final String label;
  final Color color;
  final String status;
  final String? interpretation;
  final double? minValue;
  final double? maxValue;

  const LeafColorTier({
    required this.tier,
    required this.label,
    required this.color,
    required this.status,
    this.interpretation,
    this.minValue,
    this.maxValue,
  });

  bool containsValue(double val) {
    if (minValue != null && maxValue != null) {
      return val >= minValue! && val <= maxValue!;
    }
    return false;
  }
}
