import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'services/camera_service.dart';
import 'services/object_detection_service.dart';
import 'ui/screens/live_detector_screen.dart';
import 'ui/theme/app_theme.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Set immersive orientation and status bar styling
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
  ]);
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
      systemNavigationBarColor: AppTheme.background,
      systemNavigationBarIconBrightness: Brightness.light,
    ),
  );

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => CameraService()),
        ChangeNotifierProvider(create: (_) => ObjectDetectionService()),
      ],
      child: const MultiObjectDetectorApp(),
    ),
  );
}

class MultiObjectDetectorApp extends StatelessWidget {
  const MultiObjectDetectorApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Multi-Object AI Detector',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      home: const LiveDetectorScreen(),
    );
  }
}
