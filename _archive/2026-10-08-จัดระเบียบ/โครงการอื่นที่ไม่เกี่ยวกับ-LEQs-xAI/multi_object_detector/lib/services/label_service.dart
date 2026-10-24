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

  final Map<int, LabelItem> _labelsById = {};
  final Map<String, LabelItem> _labelsByName = {};
  final List<String> _labelmapTxt = [];
  bool _isLoaded = false;

  bool get isLoaded => _isLoaded;
  Map<int, LabelItem> get allLabels => Map.unmodifiable(_labelsById);

  Future<void> loadLabels() async {
    if (_isLoaded) return;
    try {
      // 1. Load labels_coco.json
      final jsonString = await rootBundle.loadString('assets/labels/labels_coco.json');
      final List<dynamic> list = json.decode(jsonString);
      for (final item in list) {
        final label = LabelItem.fromJson(item as Map<String, dynamic>);
        _labelsById[label.id] = label;
        _labelsByName[label.nameEn.toLowerCase().trim()] = label;
      }

      // 2. Load labelmap.txt if available
      try {
        final txtString = await rootBundle.loadString('assets/models/labelmap.txt');
        _labelmapTxt.clear();
        for (final line in txtString.split('\n')) {
          final trimmed = line.trim();
          if (trimmed.isNotEmpty) {
            _labelmapTxt.add(trimmed);
          }
        }
      } catch (e) {
        debugPrint('[i] labelmap.txt notice: $e');
      }

      _isLoaded = true;
      debugPrint('[+] Loaded ${_labelsById.length} COCO labels successfully.');
    } catch (e) {
      debugPrint('[!] Failed to load COCO labels JSON: $e, using default fallback map');
      _loadFallbackLabels();
      _isLoaded = true;
    }
  }

  LabelItem getLabel(int id) {
    // Check if labelmapTxt has an entry at id
    if (id >= 0 && id < _labelmapTxt.length) {
      final nameFromMap = _labelmapTxt[id].toLowerCase().trim();
      if (nameFromMap != '???' && _labelsByName.containsKey(nameFromMap)) {
        return _labelsByName[nameFromMap]!;
      }
    }

    // Check direct ID match
    if (_labelsById.containsKey(id)) {
      return _labelsById[id]!;
    }

    // Check 1-offset match (1-based index to 0-based index)
    if (_labelsById.containsKey(id - 1)) {
      return _labelsById[id - 1]!;
    }

    return LabelItem(
      id: id,
      nameEn: 'object_$id',
      nameTh: 'วัตถุรหัส $id',
      category: 'general',
      color: const Color(0xFF00E5FF),
    );
  }

  LabelItem? getLabelByName(String name) {
    final key = name.toLowerCase().trim();
    return _labelsByName[key];
  }

  void _loadFallbackLabels() {
    final fallback = [
      {'id': 0, 'name_en': 'person', 'name_th': 'บุคคล / คน', 'category': 'people', 'color_hex': '#00E5FF'},
      {'id': 1, 'name_en': 'bicycle', 'name_th': 'จักรยาน', 'category': 'vehicles', 'color_hex': '#00FF66'},
      {'id': 2, 'name_en': 'car', 'name_th': 'รถยนต์', 'category': 'vehicles', 'color_hex': '#3D82FF'},
      {'id': 3, 'name_en': 'motorcycle', 'name_th': 'รถจักรยานยนต์', 'category': 'vehicles', 'color_hex': '#00E5FF'},
      {'id': 4, 'name_en': 'airplane', 'name_th': 'เครื่องบิน', 'category': 'vehicles', 'color_hex': '#7C4DFF'},
      {'id': 5, 'name_en': 'bus', 'name_th': 'รถบัส / รถโดยสาร', 'category': 'vehicles', 'color_hex': '#FF9100'},
      {'id': 6, 'name_en': 'train', 'name_th': 'รถไฟ', 'category': 'vehicles', 'color_hex': '#FF3D00'},
      {'id': 7, 'name_en': 'truck', 'name_th': 'รถบรรทุก', 'category': 'vehicles', 'color_hex': '#FF6D00'},
      {'id': 8, 'name_en': 'boat', 'name_th': 'เรือ', 'category': 'vehicles', 'color_hex': '#00B0FF'},
      {'id': 9, 'name_en': 'traffic light', 'name_th': 'สัญญาณไฟจราจร', 'category': 'outdoor', 'color_hex': '#FFD600'},
      {'id': 14, 'name_en': 'bird', 'name_th': 'นก', 'category': 'animals', 'color_hex': '#00E676'},
      {'id': 15, 'name_en': 'cat', 'name_th': 'แมว', 'category': 'animals', 'color_hex': '#FF4081'},
      {'id': 16, 'name_en': 'dog', 'name_th': 'สุนัข', 'category': 'animals', 'color_hex': '#FFAB40'},
      {'id': 24, 'name_en': 'backpack', 'name_th': 'กระเป๋าเป้', 'category': 'accessories', 'color_hex': '#26A69A'},
      {'id': 25, 'name_en': 'umbrella', 'name_th': 'ร่ม', 'category': 'accessories', 'color_hex': '#AB47BC'},
      {'id': 26, 'name_en': 'handbag', 'name_th': 'กระเป๋าถือ', 'category': 'accessories', 'color_hex': '#EC407A'},
      {'id': 39, 'name_en': 'bottle', 'name_th': 'ขวดน้ำ / ขวดเครื่องดื่ม', 'category': 'kitchen', 'color_hex': '#00E5FF'},
      {'id': 41, 'name_en': 'cup', 'name_th': 'ถ้วย / แก้วกาแฟ', 'category': 'kitchen', 'color_hex': '#FFB74D'},
      {'id': 46, 'name_en': 'banana', 'name_th': 'กล้วย', 'category': 'food', 'color_hex': '#FFEA00'},
      {'id': 47, 'name_en': 'apple', 'name_th': 'แอปเปิ้ล', 'category': 'food', 'color_hex': '#FF1744'},
      {'id': 56, 'name_en': 'chair', 'name_th': 'เก้าอี้', 'category': 'furniture', 'color_hex': '#8D6E63'},
      {'id': 58, 'name_en': 'potted plant', 'name_th': 'ต้นไม้กระถาง / พืช', 'category': 'furniture', 'color_hex': '#00E676'},
      {'id': 62, 'name_en': 'tv', 'name_th': 'โทรทัศน์ / จอภาพ', 'category': 'electronics', 'color_hex': '#00B0FF'},
      {'id': 63, 'name_en': 'laptop', 'name_th': 'คอมพิวเตอร์แล็ปท็อป', 'category': 'electronics', 'color_hex': '#00E5FF'},
      {'id': 64, 'name_en': 'mouse', 'name_th': 'เมาส์', 'category': 'electronics', 'color_hex': '#80DEEA'},
      {'id': 66, 'name_en': 'keyboard', 'name_th': 'คีย์บอร์ด', 'category': 'electronics', 'color_hex': '#B0BEC5'},
      {'id': 67, 'name_en': 'cell phone', 'name_th': 'โทรศัพท์มือถือ / สมาร์ทโฟน', 'category': 'electronics', 'color_hex': '#00E5FF'},
      {'id': 73, 'name_en': 'book', 'name_th': 'หนังสือ / เอกสาร', 'category': 'indoor', 'color_hex': '#FFCA28'},
      {'id': 74, 'name_en': 'clock', 'name_th': 'นาฬิกา', 'category': 'indoor', 'color_hex': '#FF7043'},
      {'id': 75, 'name_en': 'vase', 'name_th': 'แจกัน', 'category': 'indoor', 'color_hex': '#AB47BC'},
    ];
    for (final item in fallback) {
      final label = LabelItem.fromJson(item);
      _labelsById[label.id] = label;
      _labelsByName[label.nameEn.toLowerCase().trim()] = label;
    }
  }
}
