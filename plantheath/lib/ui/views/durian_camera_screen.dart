import 'dart:async';
import 'package:flutter/material.dart';
import '../../models/disease_diagnosis.dart';
import '../../models/leaf_color_tier.dart';
import '../../models/nutrient_health_metric.dart';
import '../../services/continual_learning_service.dart';
import '../../services/deep_learning_inference_service.dart';
import '../widgets/interactive_roi_selector.dart';
import 'diagnostic_result_screen.dart';
import 'model_trainer_screen.dart';

class DurianCameraScreen extends StatefulWidget {
  final DeepLearningInferenceService inferenceService;
  final ContinualLearningService continualService;

  const DurianCameraScreen({
    super.key,
    required this.inferenceService,
    required this.continualService,
  });

  @override
  State<DurianCameraScreen> createState() => _DurianCameraScreenState();
}

class _DurianCameraScreenState extends State<DurianCameraScreen> {
  RoiShape _roiShape = RoiShape.rectangle;
  double _roiSize = 180.0;
  ScaleMode _scaleMode = ScaleMode.spad;
  bool _isDarkChamberMode = false;
  bool _isFlashOn = false;
  bool _isExposureLocked = false;
  double _fps = 30.0;

  // Live Analysis State
  DiseaseDiagnosis _liveDisease = DiseaseDiagnosis.empty();
  NutrientHealthMetric _liveNutrition = NutrientHealthMetric.defaultHealthy();
  List<int> _liveRgb = [46, 125, 50];
  List<double> _liveLab = [46.8, -38.5, 32.1];

  Timer? _streamSimulationTimer;

  @override
  void initState() {
    super.initState();
    _startLiveStreamAnalysis();
  }

  @override
  void dispose() {
    _streamSimulationTimer?.cancel();
    super.dispose();
  }

  void _startLiveStreamAnalysis() {
    int frameCount = 0;
    _streamSimulationTimer = Timer.periodic(const Duration(milliseconds: 300), (timer) {
      if (!mounted) return;
      frameCount++;

      // Simulate slight chromatic fluctuations from natural leaf lighting
      int r = (46 + (frameCount % 12) * 2).clamp(0, 255);
      int g = (125 - (frameCount % 8) * 2).clamp(0, 255);
      int b = (50 + (frameCount % 6)).clamp(0, 255);

      final result = widget.inferenceService.analyzeLeaf(r: r, g: g, b: b);

      setState(() {
        _liveDisease = result.disease;
        _liveNutrition = result.nutrition;
        _liveRgb = [r, g, b];
        _liveLab = result.nutrition.lab;
        _fps = 29.5 + ((frameCount % 5) * 0.2);
      });
    });
  }

  void _navigateToResult() {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => DiagnosticResultScreen(
          disease: _liveDisease,
          nutrition: _liveNutrition,
          onTeachModel: _navigateToTrainer,
        ),
      ),
    );
  }

  void _navigateToTrainer() {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => ModelTrainerScreen(
          continualService: widget.continualService,
          inferenceService: widget.inferenceService,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: SafeArea(
        child: Stack(
          children: [
            // 1. Camera Viewport / Background
            Positioned.fill(
              child: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: _isDarkChamberMode
                        ? [const Color(0xFF0D1117), const Color(0xFF161B22)]
                        : [const Color(0xFF1B2A1E), const Color(0xFF0F1710)],
                  ),
                ),
                child: Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(
                        Icons.energy_savings_leaf_outlined,
                        size: 96,
                        color: Colors.white.withOpacity(0.08),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'DURIAN LEAF AI VISION ENGINE',
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.12),
                          letterSpacing: 2.0,
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),

            // 2. Central ROI Bounding Box Overlay
            Center(
              child: _buildRoiOverlay(),
            ),

            // 3. Top Status Bar (Controls & FPS)
            Positioned(
              top: 12,
              left: 16,
              right: 16,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(
                      color: Colors.black54,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.white24),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.circle, color: Color(0xFF4CAF50), size: 10),
                        const SizedBox(width: 6),
                        Text(
                          'LIVE AI ${_fps.toStringAsFixed(0)} FPS',
                          style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                  Row(
                    children: [
                      _topIconButton(
                        icon: _isDarkChamberMode ? Icons.bedtime : Icons.light_mode,
                        tooltip: 'โหมดกล่องมืด (Dark Chamber)',
                        isActive: _isDarkChamberMode,
                        onTap: () => setState(() => _isDarkChamberMode = !_isDarkChamberMode),
                      ),
                      const SizedBox(width: 8),
                      _topIconButton(
                        icon: _isExposureLocked ? Icons.lock : Icons.lock_open,
                        tooltip: 'ล็อกค่าแสง (Exposure Lock)',
                        isActive: _isExposureLocked,
                        onTap: () => setState(() => _isExposureLocked = !_isExposureLocked),
                      ),
                      const SizedBox(width: 8),
                      _topIconButton(
                        icon: _isFlashOn ? Icons.flash_on : Icons.flash_off,
                        tooltip: 'ไฟฉายส่องใบ',
                        isActive: _isFlashOn,
                        onTap: () => setState(() => _isFlashOn = !_isFlashOn),
                      ),
                      const SizedBox(width: 8),
                      _topIconButton(
                        icon: Icons.school_outlined,
                        tooltip: 'สอนโมเดลเพิ่มเติม (Continual Learning)',
                        isActive: false,
                        onTap: _navigateToTrainer,
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // 4. Floating ROI Toolbar with Embedded Academic Color Strip on the Right
            Positioned(
              right: 12,
              top: 80,
              child: InteractiveRoiSelector(
                selectedShape: _roiShape,
                roiSize: _roiSize,
                onShapeChanged: (shape) => setState(() => _roiShape = shape),
                onSizeChanged: (size) => setState(() => _roiSize = size),
                scaleMode: _scaleMode,
                onScaleModeChanged: (mode) => setState(() => _scaleMode = mode),
                liveSpad: _liveNutrition.spadChlorophyll,
                liveNitrogen: _liveNutrition.nitrogenPct,
                livePhosphorus: _liveNutrition.phosphorusPct,
                livePotassium: _liveNutrition.potassiumPct,
                liveDiseaseLabel: _liveDisease.nameTh,
                liveRgb: _liveRgb,
                liveLab: _liveLab,
              ),
            ),

            // 5. Bottom Live Result Overview Bar
            Positioned(
              bottom: 16,
              left: 16,
              right: 16,
              child: GestureDetector(
                onTap: _navigateToResult,
                child: Container(
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: const Color(0xEE1A2230),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.white24),
                    boxShadow: const [
                      BoxShadow(color: Colors.black54, blurRadius: 12, offset: Offset(0, 4)),
                    ],
                  ),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: _liveDisease.id == 'healthy'
                              ? const Color(0xFF2E7D32)
                              : const Color(0xFFC62828),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          _liveDisease.id == 'healthy' ? Icons.check : Icons.warning_amber_rounded,
                          color: Colors.white,
                          size: 22,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    _liveDisease.nameTh,
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontSize: 15,
                                      fontWeight: FontWeight.bold,
                                    ),
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                  decoration: BoxDecoration(
                                    color: Colors.black38,
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                  child: Text(
                                    '${(_liveDisease.confidence * 100).toStringAsFixed(0)}%',
                                    style: const TextStyle(color: Colors.amberAccent, fontSize: 11, fontWeight: FontWeight.bold),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'SPAD: ${_liveNutrition.spadChlorophyll.toStringAsFixed(1)} • N: ${_liveNutrition.nitrogenPct.toStringAsFixed(2)}% • P: ${_liveNutrition.phosphorusPct.toStringAsFixed(2)}% • K: ${_liveNutrition.potassiumPct.toStringAsFixed(2)}%',
                              style: const TextStyle(color: Color(0xFF81C784), fontSize: 12, fontFamily: 'monospace'),
                              overflow: TextOverflow.ellipsis,
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(width: 8),
                      const Icon(Icons.chevron_right, color: Colors.white70),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRoiOverlay() {
    switch (_roiShape) {
      case RoiShape.circle:
        return Container(
          width: _roiSize,
          height: _roiSize,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            border: Border.all(color: const Color(0xFF4CAF50), width: 2.2),
            boxShadow: [
              BoxShadow(color: const Color(0xFF4CAF50).withOpacity(0.25), blurRadius: 12),
            ],
          ),
          child: const Center(child: Icon(Icons.add, color: Color(0xFF4CAF50), size: 18)),
        );
      case RoiShape.rectangle:
        return Container(
          width: _roiSize,
          height: _roiSize * 1.3,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: const Color(0xFF4CAF50), width: 2.2),
            boxShadow: [
              BoxShadow(color: const Color(0xFF4CAF50).withOpacity(0.25), blurRadius: 12),
            ],
          ),
          child: const Center(child: Icon(Icons.add, color: Color(0xFF4CAF50), size: 18)),
        );
      case RoiShape.freeform:
        return Container(
          width: _roiSize,
          height: _roiSize,
          decoration: BoxDecoration(
            borderRadius: const BorderRadius.only(
              topLeft: Radius.circular(30),
              topRight: Radius.circular(10),
              bottomLeft: Radius.circular(15),
              bottomRight: Radius.circular(40),
            ),
            border: Border.all(color: const Color(0xFF00E676), width: 2.2),
          ),
          child: const Center(child: Icon(Icons.gesture, color: Color(0xFF00E676), size: 18)),
        );
    }
  }

  Widget _topIconButton({
    required IconData icon,
    required String tooltip,
    required bool isActive,
    required VoidCallback onTap,
  }) {
    return Tooltip(
      message: tooltip,
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          width: 38,
          height: 38,
          decoration: BoxDecoration(
            color: isActive ? const Color(0xFF2E7D32) : Colors.black54,
            shape: BoxShape.circle,
            border: Border.all(color: Colors.white24),
          ),
          child: Icon(icon, color: Colors.white, size: 18),
        ),
      ),
    );
  }
}
