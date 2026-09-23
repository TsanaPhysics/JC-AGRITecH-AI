import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:multi_object_detector/models/detected_object.dart';
import 'package:multi_object_detector/services/label_service.dart';

void main() {
  group('Multi-Object Detection Unit Tests', () {
    test('DetectedObject formatting and confidence levels', () {
      final objHigh = DetectedObject(
        id: 'test_1',
        classId: 0,
        labelEn: 'person',
        labelTh: 'บุคคล / คน',
        category: 'people',
        confidence: 0.945,
        normalizedRect: const Rect.fromLTWH(0.1, 0.1, 0.4, 0.6),
        color: const Color(0xFF00E5FF),
        detectedAt: DateTime.now(),
      );

      expect(objHigh.confidencePercent, equals('94.5%'));
      expect(objHigh.confidenceLevel, contains('High'));

      final screenRect = objHigh.toScreenRect(
        screenSize: const Size(400, 800),
        imageSize: const Size(640, 480),
      );

      expect(screenRect.left, equals(40.0));
      expect(screenRect.top, equals(80.0));
      expect(screenRect.width, equals(160.0));
      expect(screenRect.height, equals(480.0));
    });

    test('LabelService fallback mapping', () {
      final labelService = LabelService();
      final personLabel = labelService.getLabel(0);

      expect(personLabel.nameEn, equals('person'));
      expect(personLabel.nameTh, contains('คน'));

      final cellPhoneLabel = labelService.getLabel(67);
      expect(cellPhoneLabel.nameEn, contains('cell phone'));
    });

    test('Category filtering groups', () {
      expect(ObjectCategoryGroup.values.length, greaterThan(5));
      expect(ObjectCategoryGroup.people.label, contains('บุคคล'));
      expect(ObjectCategoryGroup.vehicles.label, contains('ยานพาหนะ'));
      expect(ObjectCategoryGroup.animals.label, contains('สัตว์'));
    });
  });
}
