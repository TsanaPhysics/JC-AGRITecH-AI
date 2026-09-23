import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class LabelItem {
  final int id;
  final String nameEn;
  final String nameTh;
  final String category;
  final Color color;

  const LabelItem({
    required this.id,
    required this.nameEn,
    required this.nameTh,
    required this.category,
    required this.color,
  });

  factory LabelItem.fromJson(Map<String, dynamic> json) {
    Color parseHex(String hex) {
      final buffer = StringBuffer();
      if (hex.length == 6 || hex.length == 7) buffer.write('ff');
      buffer.write(hex.replaceFirst('#', ''));
      return Color(int.parse(buffer.toString(), radix: 16));
    }

    return LabelItem(
      id: json['id'] as int,
      nameEn: json['name_en'] as String,
      nameTh: json['name_th'] as String,
      category: json['category'] as String,
      color: parseHex(json['color_hex'] as String? ?? '#00E5FF'),
    );
  }
}

class LabelService {
  static final LabelService _instance = LabelService._internal();
  factory LabelService() => _instance;
  LabelService._internal() {
    _loadFallbackLabels();
  }

  final Map<int, LabelItem> _labels = {};
  bool _isLoaded = false;

  bool get isLoaded => _isLoaded;
  Map<int, LabelItem> get allLabels => Map.unmodifiable(_labels);

  Future<void> loadLabels() async {
    if (_isLoaded) return;
    try {
      final jsonString = await rootBundle.loadString('assets/labels/labels_coco.json');
      final List<dynamic> list = json.decode(jsonString);
      for (final item in list) {
        final label = LabelItem.fromJson(item as Map<String, dynamic>);
        _labels[label.id] = label;
      }
      _isLoaded = true;
      debugPrint('[+] Loaded ${_labels.length} COCO labels successfully.');
    } catch (e) {
      debugPrint('[!] Failed to load COCO labels JSON: $e, using default fallback map');
      _loadFallbackLabels();
      _isLoaded = true;
    }
  }

  LabelItem getLabel(int id) {
    if (_labels.containsKey(id)) {
      return _labels[id]!;
    }
    return LabelItem(
      id: id,
      nameEn: 'object_$id',
      nameTh: 'วัตถุรหัส $id',
      category: 'general',
      color: const Color(0xFF00E5FF),
    );
  }

  void _loadFallbackLabels() {
    final fallback = [
      {'id': 0, 'name_en': 'person', 'name_th': 'บุคคล / คน', 'category': 'people', 'color_hex': '#00E5FF'},
      {'id': 1, 'name_en': 'bicycle', 'name_th': 'จักรยาน', 'category': 'vehicles', 'color_hex': '#00FF66'},
      {'id': 2, 'name_en': 'car', 'name_th': 'รถยนต์', 'category': 'vehicles', 'color_hex': '#3D82FF'},
      {'id': 3, 'name_en': 'motorcycle', 'name_th': 'รถจักรยานยนต์', 'category': 'vehicles', 'color_hex': '#00E5FF'},
      {'id': 15, 'name_en': 'cat', 'name_th': 'แมว', 'category': 'animals', 'color_hex': '#FF4081'},
      {'id': 16, 'name_en': 'dog', 'name_th': 'สุนัข', 'category': 'animals', 'color_hex': '#FFAB40'},
      {'id': 39, 'name_en': 'bottle', 'name_th': 'ขวดน้ำ', 'category': 'kitchen', 'color_hex': '#00E5FF'},
      {'id': 41, 'name_en': 'cup', 'name_th': 'ถ้วย / แก้วกาแฟ', 'category': 'kitchen', 'color_hex': '#FFB74D'},
      {'id': 56, 'name_en': 'chair', 'name_th': 'เก้าอี้', 'category': 'furniture', 'color_hex': '#8D6E63'},
      {'id': 58, 'name_en': 'potted plant', 'name_th': 'ต้นไม้กระถาง / พืช', 'category': 'furniture', 'color_hex': '#00E676'},
      {'id': 62, 'name_en': 'tv', 'name_th': 'โทรทัศน์ / จอภาพ', 'category': 'electronics', 'color_hex': '#00B0FF'},
      {'id': 63, 'name_en': 'laptop', 'name_th': 'คอมพิวเตอร์แล็ปท็อป', 'category': 'electronics', 'color_hex': '#00E5FF'},
      {'id': 67, 'name_en': 'cell phone', 'name_th': 'โทรศัพท์มือถือ / สมาร์ทโฟน', 'category': 'electronics', 'color_hex': '#00E5FF'},
      {'id': 73, 'name_en': 'book', 'name_th': 'หนังสือ / เอกสาร', 'category': 'indoor', 'color_hex': '#FFCA28'},
    ];
    for (final item in fallback) {
      final label = LabelItem.fromJson(item);
      _labels[label.id] = label;
    }
  }
}
