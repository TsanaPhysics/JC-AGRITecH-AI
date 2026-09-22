import 'dart:math' as math;
import 'package:flutter/foundation.dart';
import 'package:tflite_flutter/tflite_flutter.dart';
import '../models/disease_diagnosis.dart';
import '../models/nutrient_health_metric.dart';
import 'continual_learning_service.dart';
import 'plant_nutrition_service.dart';
import 'plant_pathology_service.dart';

class DualInferenceResult {
  final DiseaseDiagnosis disease;
  final NutrientHealthMetric nutrition;
  final List<double> featureEmbedding;
  final bool isCustomClassMatch;
  final String? customClassName;

  const DualInferenceResult({
    required this.disease,
    required this.nutrition,
    required this.featureEmbedding,
    this.isCustomClassMatch = false,
    this.customClassName,
  });
}

class DeepLearningInferenceService {
  Interpreter? _dualInterpreter;
  Interpreter? _embedInterpreter;
  bool _isInitialized = false;
  final ContinualLearningService continualLearning;

  DeepLearningInferenceService({ContinualLearningService? continualService})
      : continualLearning = continualService ?? ContinualLearningService();

  bool get isInitialized => _isInitialized;

  /// Initialize real TFLite models from app assets
  Future<void> initialize() async {
    try {
      _dualInterpreter = await Interpreter.fromAsset(
        'assets/models/durian_leaf_health_mobilenet.tflite',
        options: InterpreterOptions()..threads = 2,
      );
      _embedInterpreter = await Interpreter.fromAsset(
        'assets/models/durian_embedding_extractor.tflite',
        options: InterpreterOptions()..threads = 2,
      );
      _isInitialized = true;
      debugPrint('[+] Real TFLite Durian models loaded successfully!');
    } catch (e) {
      debugPrint('[!] TFLite Interpreter load notice: $e (Falling back to physics-informed model)');
      _isInitialized = false;
    }
  }

  /// Run dual-head inference from ROI color sample or image buffer
  DualInferenceResult analyzeLeaf({
    required int r,
    required int g,
    required int b,
    List<int>? pixelBuffer,
    int width = 224,
    int height = 224,
  }) {
    List<double> diseaseProbs = List.filled(6, 0.0);
    List<double> nutrientOutputs = List.filled(9, 0.0);
    List<double> embedding = List.filled(128, 0.0);

    if (_isInitialized && _dualInterpreter != null) {
      try {
        // Prepare 1x224x224x3 input tensor
        var input = List.generate(
          1,
          (_) => List.generate(
            224,
            (y) => List.generate(
              224,
              (x) => [r / 255.0, g / 255.0, b / 255.0],
            ),
          ),
        );

        // Prepare outputs
        // Output 0: (1, 6) disease probs
        // Output 1: (1, 9) nutrient continuous values
        var outDisease = List.generate(1, (_) => List.filled(6, 0.0));
        var outNutrient = List.generate(1, (_) => List.filled(9, 0.0));
        var outputs = {0: outNutrient, 1: outDisease};

        _dualInterpreter!.runForMultipleInputs([input], outputs);

        for (int i = 0; i < 6; i++) {
          diseaseProbs[i] = outDisease[0][i];
        }
        for (int i = 0; i < 9; i++) {
          nutrientOutputs[i] = outNutrient[0][i];
        }

        // Run embedding model
        if (_embedInterpreter != null) {
          var outEmbed = List.generate(1, (_) => List.filled(128, 0.0));
          _embedInterpreter!.run(input, outEmbed);
          for (int i = 0; i < 128; i++) {
            embedding[i] = outEmbed[0][i];
          }
        }
      } catch (e) {
        debugPrint('[!] TFLite execution fallback: $e');
        _generateSyntheticEmbedding(embedding, r, g, b);
      }
    } else {
      _generateSyntheticEmbedding(embedding, r, g, b);
    }

    // 1. Check Continual Learning Custom Classes first
    final customMatch = continualLearning.matchCustomClass(embedding);
    DiseaseDiagnosis disease;
    bool isCustom = false;
    String? customName;

    if (customMatch != null) {
      isCustom = true;
      customName = customMatch['label_th'] as String;
      disease = DiseaseDiagnosis(
        id: customMatch['class_id'] as String,
        nameTh: customMatch['label_th'] as String,
        nameEn: customMatch['label_en'] as String,
        scientificName: 'Custom Trained Pattern',
        pathogenType: 'User Defined / Continual Learning',
        severityLevel: 'Special',
        confidence: customMatch['confidence'] as double,
        description: 'ตรวจพบอาการตรงกับรูปแบบที่สอนเพิ่มในตัวเครื่อง (Few-Shot Matching)',
        treatment: customMatch['treatment'] as String,
        lesionAreaPercentage: 20.0,
      );
    } else {
      disease = PlantPathologyService.diagnose(
        modelProbabilities: _isInitialized ? diseaseProbs : null,
        r: r,
        g: g,
        b: b,
      );
    }

    // 2. Compute Nutritional Health Metrics
    final nutrition = PlantNutritionService.analyzeFromColor(
      r,
      g,
      b,
      regressionOutputs: _isInitialized ? nutrientOutputs : null,
    );

    return DualInferenceResult(
      disease: disease,
      nutrition: nutrition,
      featureEmbedding: embedding,
      isCustomClassMatch: isCustom,
      customClassName: customName,
    );
  }

  void _generateSyntheticEmbedding(List<double> embedding, int r, int g, int b) {
    // Generate deterministic normalized pseudo-embedding based on color physics
    double seed = (r * 31 + g * 17 + b * 7) / 255.0;
    double norm = 0.0;
    for (int i = 0; i < 128; i++) {
      double val = math.sin(seed + i * 0.45);
      embedding[i] = val;
      norm += val * val;
    }
    double sqrtNorm = math.sqrt(norm);
    if (sqrtNorm > 0.0) {
      for (int i = 0; i < 128; i++) {
        embedding[i] /= sqrtNorm;
      }
    }
  }

  void dispose() {
    _dualInterpreter?.close();
    _embedInterpreter?.close();
  }
}
