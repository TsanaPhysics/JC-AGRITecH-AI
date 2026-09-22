import 'dart:convert';
import 'dart:io';
import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';
import 'package:path_provider/path_provider.dart';
import '../models/disease_diagnosis.dart';
import '../models/geo_location_data.dart';
import '../models/nutrient_health_metric.dart';

enum LeafMediaType { photo, video }

class LeafDatasetItem {
  final String id;
  final String filePath;
  final LeafMediaType mediaType;
  final DateTime timestamp;
  final GeoLocationData location;
  final DiseaseDiagnosis disease;
  final NutrientHealthMetric nutrition;
  final int durationSeconds;
  final List<int> rgb;
  final List<double> lab;

  const LeafDatasetItem({
    required this.id,
    required this.filePath,
    required this.mediaType,
    required this.timestamp,
    required this.location,
    required this.disease,
    required this.nutrition,
    this.durationSeconds = 0,
    required this.rgb,
    required this.lab,
  });

  Map<String, dynamic> toJson() => {
    'id': id,
    'filePath': filePath,
    'mediaType': mediaType == LeafMediaType.photo ? 'photo' : 'video',
    'timestamp': timestamp.toIso8601String(),
    'location': location.toJson(),
    'disease': disease.toJson(),
    'nutrition': nutrition.toJson(),
    'durationSeconds': durationSeconds,
    'rgb': rgb,
    'lab': lab,
  };

  factory LeafDatasetItem.fromJson(Map<String, dynamic> json) {
    return LeafDatasetItem(
      id: json['id'] ?? '',
      filePath: json['filePath'] ?? '',
      mediaType: json['mediaType'] == 'video' ? LeafMediaType.video : LeafMediaType.photo,
      timestamp: json['timestamp'] != null ? DateTime.parse(json['timestamp']) : DateTime.now(),
      location: json['location'] != null ? GeoLocationData.fromJson(json['location']) : GeoLocationData.mockDefault(),
      disease: json['disease'] != null ? DiseaseDiagnosis.fromJson(json['disease']) : DiseaseDiagnosis.empty(),
      nutrition: json['nutrition'] != null ? NutrientHealthMetric.fromJson(json['nutrition']) : NutrientHealthMetric.defaultHealthy(),
      durationSeconds: (json['durationSeconds'] as num?)?.toInt() ?? 0,
      rgb: (json['rgb'] as List<dynamic>?)?.map((e) => (e as num).toInt()).toList() ?? [46, 125, 50],
      lab: (json['lab'] as List<dynamic>?)?.map((e) => (e as num).toDouble()).toList() ?? [46.8, -38.5, 32.1],
    );
  }
}

class LeafDatasetService {
  static const String _folderName = 'durian_leaf_datasets';

  static Future<Directory> _getDatasetDir() async {
    final appDir = await getApplicationDocumentsDirectory();
    final dir = Directory('${appDir.path}/$_folderName');
    if (!await dir.exists()) {
      await dir.create(recursive: true);
    }
    return dir;
  }

  static Future<int> getSampleCount() async {
    try {
      final items = await loadAllSamples();
      return items.length;
    } catch (_) {
      return 0;
    }
  }

  static Future<String> savePhotoSample({
    required XFile photo,
    required DiseaseDiagnosis disease,
    required NutrientHealthMetric nutrition,
    required GeoLocationData location,
    required List<int> rgb,
    required List<double> lab,
  }) async {
    final dir = await _getDatasetDir();
    final timestamp = DateTime.now();
    final id = 'leaf_img_${timestamp.millisecondsSinceEpoch}';
    final targetPath = '${dir.path}/$id.jpg';
    final metaPath = '${dir.path}/$id.json';

    await photo.saveTo(targetPath);

    final item = LeafDatasetItem(
      id: id,
      filePath: targetPath,
      mediaType: LeafMediaType.photo,
      timestamp: timestamp,
      location: location,
      disease: disease,
      nutrition: nutrition,
      rgb: rgb,
      lab: lab,
    );

    final metaFile = File(metaPath);
    await metaFile.writeAsString(jsonEncode(item.toJson()));
    debugPrint('[LeafDatasetService] Saved photo sample $targetPath');
    return targetPath;
  }

  static Future<String> saveVideoSample({
    required XFile video,
    required DiseaseDiagnosis disease,
    required NutrientHealthMetric nutrition,
    required GeoLocationData location,
    required int durationSeconds,
    required List<int> rgb,
    required List<double> lab,
  }) async {
    final dir = await _getDatasetDir();
    final timestamp = DateTime.now();
    final id = 'leaf_vid_${timestamp.millisecondsSinceEpoch}';
    final targetPath = '${dir.path}/$id.mp4';
    final metaPath = '${dir.path}/$id.json';

    await video.saveTo(targetPath);

    final item = LeafDatasetItem(
      id: id,
      filePath: targetPath,
      mediaType: LeafMediaType.video,
      timestamp: timestamp,
      location: location,
      disease: disease,
      nutrition: nutrition,
      durationSeconds: durationSeconds,
      rgb: rgb,
      lab: lab,
    );

    final metaFile = File(metaPath);
    await metaFile.writeAsString(jsonEncode(item.toJson()));
    debugPrint('[LeafDatasetService] Saved video sample $targetPath');
    return targetPath;
  }

  static Future<List<LeafDatasetItem>> loadAllSamples() async {
    try {
      final dir = await _getDatasetDir();
      final entities = dir.listSync();
      final List<LeafDatasetItem> items = [];

      for (final entity in entities) {
        if (entity is File && entity.path.endsWith('.json')) {
          try {
            final content = await entity.readAsString();
            final json = jsonDecode(content) as Map<String, dynamic>;
            final item = LeafDatasetItem.fromJson(json);
            if (File(item.filePath).existsSync()) {
              items.add(item);
            }
          } catch (e) {
            debugPrint('[LeafDatasetService] Error reading JSON ${entity.path}: $e');
          }
        }
      }

      items.sort((a, b) => b.timestamp.compareTo(a.timestamp));
      return items;
    } catch (e) {
      debugPrint('[LeafDatasetService] Error loading samples: $e');
      return [];
    }
  }

  static Future<void> deleteSample(LeafDatasetItem item) async {
    try {
      final mediaFile = File(item.filePath);
      if (await mediaFile.exists()) await mediaFile.delete();

      final jsonFile = File('${item.filePath.substring(0, item.filePath.lastIndexOf('.'))}.json');
      if (await jsonFile.exists()) await jsonFile.delete();
    } catch (e) {
      debugPrint('[LeafDatasetService] Error deleting sample: $e');
    }
  }
}
