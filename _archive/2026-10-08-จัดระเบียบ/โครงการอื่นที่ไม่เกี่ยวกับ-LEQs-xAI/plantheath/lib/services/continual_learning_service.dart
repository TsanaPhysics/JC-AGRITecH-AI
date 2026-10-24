import 'dart:math' as math;
import '../models/user_feedback_sample.dart';

class CustomTrainedClass {
  final String classId;
  final String labelTh;
  final String labelEn;
  final List<List<double>> referenceEmbeddings; // 128-d vectors
  final String treatment;

  CustomTrainedClass({
    required this.classId,
    required this.labelTh,
    required this.labelEn,
    required this.referenceEmbeddings,
    required this.treatment,
  });

  Map<String, dynamic> toJson() => {
    'class_id': classId,
    'label_th': labelTh,
    'label_en': labelEn,
    'reference_embeddings': referenceEmbeddings,
    'treatment': treatment,
  };

  factory CustomTrainedClass.fromJson(Map<String, dynamic> json) => CustomTrainedClass(
    classId: json['class_id'] as String,
    labelTh: json['label_th'] as String,
    labelEn: json['label_en'] as String,
    referenceEmbeddings: (json['reference_embeddings'] as List)
        .map((item) => (item as List).map((e) => (e as num).toDouble()).toList())
        .toList(),
    treatment: json['treatment'] as String,
  );
}

class ContinualLearningService {
  final List<CustomTrainedClass> _customClasses = [];
  final List<UserFeedbackSample> _stagedFeedbackSamples = [];

  List<CustomTrainedClass> get customClasses => List.unmodifiable(_customClasses);
  List<UserFeedbackSample> get stagedSamples => List.unmodifiable(_stagedFeedbackSamples);

  /// Compute Cosine Similarity between two 128-dimensional embedding vectors
  static double cosineSimilarity(List<double> v1, List<double> v2) {
    if (v1.length != v2.length || v1.isEmpty) return 0.0;
    double dot = 0.0;
    double norm1 = 0.0;
    double norm2 = 0.0;

    for (int i = 0; i < v1.length; i++) {
      dot += v1[i] * v2[i];
      norm1 += v1[i] * v1[i];
      norm2 += v2[i] * v2[i];
    }

    if (norm1 <= 0.0 || norm2 <= 0.0) return 0.0;
    return dot / (math.sqrt(norm1) * math.sqrt(norm2));
  }

  /// Add a new few-shot learned class on device
  void registerCustomClass({
    required String classId,
    required String labelTh,
    required String labelEn,
    required List<List<double>> sampleEmbeddings,
    required String treatment,
  }) {
    _customClasses.removeWhere((c) => c.classId == classId);
    _customClasses.add(CustomTrainedClass(
      classId: classId,
      labelTh: labelTh,
      labelEn: labelEn,
      referenceEmbeddings: sampleEmbeddings,
      treatment: treatment,
    ));
  }

  /// Query if an input embedding vector matches any on-device learned pattern
  Map<String, dynamic>? matchCustomClass(List<double> inputEmbedding, {double threshold = 0.85}) {
    double highestSimilarity = 0.0;
    CustomTrainedClass? matchedClass;

    for (final customClass in _customClasses) {
      for (final refEmbed in customClass.referenceEmbeddings) {
        double sim = cosineSimilarity(inputEmbedding, refEmbed);
        if (sim > highestSimilarity) {
          highestSimilarity = sim;
          matchedClass = customClass;
        }
      }
    }

    if (matchedClass != null && highestSimilarity >= threshold) {
      return {
        'class_id': matchedClass.classId,
        'label_th': matchedClass.labelTh,
        'label_en': matchedClass.labelEn,
        'confidence': highestSimilarity,
        'treatment': matchedClass.treatment,
      };
    }
    return null;
  }

  /// Add a sample to the continual learning feedback queue
  void stageFeedbackSample(UserFeedbackSample sample) {
    _stagedFeedbackSamples.add(sample);
  }

  /// Clear staged samples after successful server/local retraining sync
  void clearStagedSamples() {
    _stagedFeedbackSamples.clear();
  }
}
