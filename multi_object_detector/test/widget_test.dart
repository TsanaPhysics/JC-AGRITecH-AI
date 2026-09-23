import 'package:flutter_test/flutter_test.dart';
import 'package:multi_object_detector/main.dart';
import 'package:provider/provider.dart';
import 'package:multi_object_detector/services/camera_service.dart';
import 'package:multi_object_detector/services/object_detection_service.dart';

void main() {
  testWidgets('App smoke test initializes without errors', (WidgetTester tester) async {
    await tester.pumpWidget(
      MultiProvider(
        providers: [
          ChangeNotifierProvider(create: (_) => CameraService()),
          ChangeNotifierProvider(create: (_) => ObjectDetectionService()),
        ],
        child: const MultiObjectDetectorApp(),
      ),
    );

    expect(find.byType(MultiObjectDetectorApp), findsOneWidget);
  });
}
