import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:plantheath/models/leaf_color_tier.dart';
import 'package:plantheath/ui/widgets/interactive_roi_selector.dart';

void main() {
  testWidgets('InteractiveRoiSelector renders shape, size and academic color scale strip', (WidgetTester tester) async {
    RoiShape currentShape = RoiShape.rectangle;
    double currentSize = 180.0;
    ScaleMode currentScale = ScaleMode.spad;

    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: Center(
            child: InteractiveRoiSelector(
              selectedShape: currentShape,
              roiSize: currentSize,
              onShapeChanged: (shape) => currentShape = shape,
              onSizeChanged: (size) => currentSize = size,
              scaleMode: currentScale,
              onScaleModeChanged: (mode) => currentScale = mode,
              liveSpad: 48.5,
              liveNitrogen: 2.50,
              livePhosphorus: 0.18,
              livePotassium: 1.85,
              liveDiseaseLabel: 'ใบปกติสมบูรณ์',
              liveRgb: const [46, 125, 50],
              liveLab: const [46.8, -38.5, 32.1],
            ),
          ),
        ),
      ),
    );

    // 1. Verify shape buttons exist
    expect(find.byIcon(Icons.crop_square), findsOneWidget);
    expect(find.byIcon(Icons.circle_outlined), findsOneWidget);
    expect(find.byIcon(Icons.gesture), findsOneWidget);

    // 2. Verify resize buttons exist
    expect(find.byIcon(Icons.add_circle_outline), findsOneWidget);
    expect(find.byIcon(Icons.remove_circle_outline), findsOneWidget);

    // 3. Verify color scale strip and pointer tag exist
    expect(find.byKey(const Key('color_scale_strip')), findsOneWidget);
    expect(find.textContaining('SPAD 48.5'), findsOneWidget);

    // 4. Test tapping scale mode switcher (cycles to Nitrogen)
    await tester.tap(find.text('SPAD'));
    expect(currentScale, equals(ScaleMode.nitrogen));

    // 5. Test tapping color strip opens modal
    await tester.tap(find.byKey(const Key('color_scale_strip')));
    await tester.pumpAndSettle();

    expect(find.textContaining('เกณฑ์มาตรฐานระดับคลอโรฟิลล์'), findsOneWidget);
    expect(find.textContaining('RGB: (46, 125, 50)'), findsOneWidget);
  });
}
