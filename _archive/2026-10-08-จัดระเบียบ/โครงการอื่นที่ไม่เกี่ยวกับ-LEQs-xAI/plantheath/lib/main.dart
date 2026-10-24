import 'package:flutter/material.dart';
import 'services/continual_learning_service.dart';
import 'services/deep_learning_inference_service.dart';
import 'ui/views/durian_camera_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  final continualService = ContinualLearningService();
  final inferenceService = DeepLearningInferenceService(continualService: continualService);
  await inferenceService.initialize();

  runApp(DurianHealthApp(
    inferenceService: inferenceService,
    continualService: continualService,
  ));
}

class DurianHealthApp extends StatelessWidget {
  final DeepLearningInferenceService inferenceService;
  final ContinualLearningService continualService;

  const DurianHealthApp({
    super.key,
    required this.inferenceService,
    required this.continualService,
  });

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'DurianLeaf AI - ระบบวินิจฉัยโรคและสุขภาพใบพืชทุเรียน',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        brightness: Brightness.dark,
        scaffoldBackgroundColor: const Color(0xFF10141D),
        primaryColor: const Color(0xFF2E7D32),
        colorScheme: const ColorScheme.dark(
          primary: Color(0xFF4CAF50),
          secondary: Color(0xFF81C784),
          surface: Color(0xFF1A2230),
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF1A2230),
          elevation: 0,
          centerTitle: true,
        ),
      ),
      home: DurianCameraScreen(
        inferenceService: inferenceService,
        continualService: continualService,
      ),
    );
  }
}
