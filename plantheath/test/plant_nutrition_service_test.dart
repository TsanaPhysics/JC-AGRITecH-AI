import 'package:flutter_test/flutter_test.dart';
import 'package:plantheath/services/plant_nutrition_service.dart';
import 'package:plantheath/services/multi_color_space_service.dart';

void main() {
  group('MultiColorSpaceService Tests', () {
    test('RGB to HSV conversion is valid', () {
      final hsv = MultiColorSpaceService.rgbToHsv(56, 142, 60);
      expect(hsv[0], inInclusiveRange(120.0, 130.0)); // Green hue
      expect(hsv[1], inInclusiveRange(0.5, 0.7));     // Saturation
      expect(hsv[2], inInclusiveRange(0.5, 0.6));     // Value
    });

    test('DGCI calculation is normalized between 0.0 and 1.0', () {
      double dgci = MultiColorSpaceService.calculateDgci(122.0, 0.60, 0.55);
      expect(dgci, inInclusiveRange(0.0, 1.0));
      expect(dgci, greaterThan(0.4)); // Green leaf has high DGCI
    });

    test('SPAD chlorophyll estimation reflects greenness', () {
      // Healthy deep green
      double spadHealthy = MultiColorSpaceService.estimateSpadFromColor(46, 125, 50);
      // Pale chlorotic leaf
      double spadPale = MultiColorSpaceService.estimateSpadFromColor(220, 230, 160);

      expect(spadHealthy, greaterThan(spadPale));
      expect(spadHealthy, inInclusiveRange(40.0, 70.0));
      expect(spadPale, lessThan(35.0));
    });
  });

  group('PlantNutritionService Diagnostic Tests', () {
    test('Healthy green leaf returns optimal NPK status', () {
      final metric = PlantNutritionService.analyzeFromColor(46, 125, 50);
      expect(metric.spadChlorophyll, greaterThan(35.0));
      expect(metric.nitrogenPct, inInclusiveRange(2.0, 3.2));
      expect(metric.phosphorusPct, inInclusiveRange(0.12, 0.28));
      expect(metric.potassiumPct, inInclusiveRange(1.4, 2.5));
      expect(metric.magnesiumPct, greaterThanOrEqualTo(0.30));
    });

    test('Yellow chlorotic leaf detects nitrogen deficiency', () {
      final metric = PlantNutritionService.analyzeFromColor(230, 235, 140);
      expect(metric.nitrogenStatus.contains('ขาด') || metric.nitrogenStatus.contains('ต่ำ'), isTrue);
      expect(metric.fertilizerRecommendation.contains('ไนโตรเจน') || metric.fertilizerRecommendation.contains('ยูเรีย'), isTrue);
    });

    test('Margin burn leaf detects potassium deficiency', () {
      final metric = PlantNutritionService.analyzeFromColor(165, 105, 55);
      expect(metric.potassiumPct, lessThan(1.5));
      expect(metric.potassiumStatus.contains('ต่ำ') || metric.potassiumStatus.contains('แห้ง'), isTrue);
    });
  });
}
