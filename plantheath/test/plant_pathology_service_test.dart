import 'package:flutter_test/flutter_test.dart';
import 'package:plantheath/services/plant_pathology_service.dart';
import 'package:plantheath/services/continual_learning_service.dart';

void main() {
  group('PlantPathologyService Tests', () {
    test('Diagnoses healthy leaf correctly with high confidence', () {
      final diag = PlantPathologyService.diagnose(r: 46, g: 125, b: 50);
      expect(diag.id, equals('healthy'));
      expect(diag.nameTh, contains('ใบปกติ'));
      expect(diag.confidence, greaterThan(0.8));
    });

    test('Diagnoses Phytophthora water-soaked necrotic lesions', () {
      final diag = PlantPathologyService.diagnose(r: 60, g: 50, b: 40);
      expect(diag.id, equals('phytophthora'));
      expect(diag.nameTh, contains('ไฟทอปธอร่า'));
      expect(diag.severityLevel, equals('Severe'));
    });

    test('Diagnoses Algal Leaf Spot (Red Rust)', () {
      final diag = PlantPathologyService.diagnose(r: 175, g: 85, b: 45);
      expect(diag.id, equals('algal_spot'));
      expect(diag.nameTh, contains('สนิม'));
    });

    test('Diagnoses from model probability distribution vector', () {
      // 6 classes: ['healthy', 'phytophthora', 'rhizoctonia', 'anthracnose', 'algal_spot', 'pest_damage']
      final probs = [0.05, 0.02, 0.85, 0.04, 0.02, 0.02];
      final diag = PlantPathologyService.diagnose(modelProbabilities: probs);
      expect(diag.id, equals('rhizoctonia'));
      expect(diag.nameTh, contains('ราใบติด'));
      expect(diag.confidence, equals(0.85));
    });
  });

  group('ContinualLearningService Tests', () {
    test('Cosine similarity between identical vectors is 1.0', () {
      final v1 = [0.5, 0.5, 0.5, 0.5];
      final v2 = [0.5, 0.5, 0.5, 0.5];
      expect(ContinualLearningService.cosineSimilarity(v1, v2), closeTo(1.0, 0.0001));
    });

    test('Registers and matches Few-Shot custom class accurately', () {
      final continual = ContinualLearningService();
      final sampleVector = List.generate(128, (i) => i % 2 == 0 ? 0.2 : -0.2);

      continual.registerCustomClass(
        classId: 'musan_king_sunburn',
        labelTh: 'ใบไหม้แดดมูซานคิง',
        labelEn: 'Musan King Sunburn',
        sampleEmbeddings: [sampleVector],
        treatment: 'พ่นสารเคลือบสะท้อนแสง Kaolin',
      );

      expect(continual.customClasses.length, equals(1));

      // Match with nearly identical query vector
      final queryVector = List.generate(128, (i) => (i % 2 == 0 ? 0.205 : -0.195));
      final match = continual.matchCustomClass(queryVector, threshold: 0.90);

      expect(match, isNotNull);
      expect(match!['label_th'], equals('ใบไหม้แดดมูซานคิง'));
      expect(match['confidence'], greaterThan(0.95));
    });
  });
}
