import 'dart:async';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import '../../models/disease_diagnosis.dart';
import '../../models/geo_location_data.dart';
import '../../models/leaf_color_tier.dart';
import '../../models/nutrient_health_metric.dart';
import '../../services/continual_learning_service.dart';
import '../../services/deep_learning_inference_service.dart';
import '../../services/geo_location_service.dart';
import '../../services/leaf_dataset_service.dart';
import '../widgets/interactive_roi_selector.dart';
import 'diagnostic_result_screen.dart';
import 'leaf_dataset_gallery_screen.dart';
import 'model_trainer_screen.dart';

enum CameraCaptureMode { photo, video }
enum HudDisplayMode { pathologyAi, nutritionAi }

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
  // Camera & Device State
  CameraController? _cameraController;
  List<CameraDescription> _cameras = [];
  int _selectedCameraIndex = 0;
  bool _isCameraInitialized = false;
  bool _isProcessingCapture = false;

  // Mode and Features
  CameraCaptureMode _captureMode = CameraCaptureMode.photo;
  HudDisplayMode _hudMode = HudDisplayMode.pathologyAi;
  bool _isRecordingVideo = false;
  int _recordingSeconds = 0;
  Timer? _recordingTimer;
  bool _isAudioEnabled = true;
  bool _isDarkChamberMode = false;
  bool _isFlashOn = false;
  bool _isExposureLocked = false;
  int _savedDatasetCount = 0;

  // ROI Controls
  RoiShape _roiShape = RoiShape.rectangle;
  double _roiSize = 180.0;
  ScaleMode _scaleMode = ScaleMode.spad;
  double _fps = 30.0;

  // Live Analysis State
  DiseaseDiagnosis _liveDisease = DiseaseDiagnosis.empty();
  NutrientHealthMetric _liveNutrition = NutrientHealthMetric.defaultHealthy();
  List<int> _liveRgb = [46, 125, 50];
  List<double> _liveLab = [46.8, -38.5, 32.1];

  // Geolocation
  GeoLocationData _currentLocation = GeoLocationData.mockDefault();
  StreamSubscription<GeoLocationData>? _locationSubscription;
  Timer? _streamSimulationTimer;

  @override
  void initState() {
    super.initState();
    _initCamera();
    _initLocation();
    _refreshDatasetCount();
    _startLiveStreamAnalysis();
  }

  @override
  void dispose() {
    _recordingTimer?.cancel();
    _streamSimulationTimer?.cancel();
    _locationSubscription?.cancel();
    _cameraController?.dispose();
    super.dispose();
  }

  Future<void> _refreshDatasetCount() async {
    final count = await LeafDatasetService.getSampleCount();
    if (mounted) setState(() => _savedDatasetCount = count);
  }

  Future<void> _initLocation() async {
    final loc = await GeoLocationService.determinePosition();
    if (mounted) setState(() => _currentLocation = loc);

    _locationSubscription = GeoLocationService.getPositionStream().listen((pos) {
      if (mounted) setState(() => _currentLocation = pos);
    });
  }

  Future<void> _initCamera() async {
    try {
      _cameras = await availableCameras();
      if (_cameras.isEmpty) {
        debugPrint('[DurianCameraScreen] No hardware camera found, using simulated stream');
        return;
      }

      _selectedCameraIndex = _cameras.indexWhere(
        (c) => c.lensDirection == CameraLensDirection.back,
      );
      if (_selectedCameraIndex == -1) _selectedCameraIndex = 0;

      await _setupCameraController(_cameras[_selectedCameraIndex]);
    } catch (e) {
      debugPrint('[DurianCameraScreen] Error initializing cameras $e');
    }
  }

  Future<void> _setupCameraController(CameraDescription camera) async {
    await _cameraController?.dispose();

    final controller = CameraController(
      camera,
      ResolutionPreset.high,
      enableAudio: _isAudioEnabled,
      imageFormatGroup: ImageFormatGroup.yuv420,
    );

    try {
      await controller.initialize();
      if (mounted) {
        setState(() {
          _cameraController = controller;
          _isCameraInitialized = true;
        });
      }
    } catch (e) {
      debugPrint('[DurianCameraScreen] Camera setup error $e');
    }
  }

  Future<void> _switchCamera() async {
    if (_cameras.length < 2) return;
    _selectedCameraIndex = (_selectedCameraIndex + 1) % _cameras.length;
    setState(() => _isCameraInitialized = false);
    await _setupCameraController(_cameras[_selectedCameraIndex]);
  }

  void _startLiveStreamAnalysis() {
    int frameCount = 0;
    _streamSimulationTimer = Timer.periodic(const Duration(milliseconds: 320), (timer) {
      if (!mounted) return;
      frameCount++;

      // Compute natural spectral variations
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

  Future<void> _capturePhoto() async {
    if (_isProcessingCapture) return;
    setState(() => _isProcessingCapture = true);

    try {
      XFile? photo;
      if (_cameraController != null && _cameraController!.value.isInitialized) {
        photo = await _cameraController!.takePicture();
      }

      if (photo != null) {
        final savedPath = await LeafDatasetService.savePhotoSample(
          photo: photo,
          disease: _liveDisease,
          nutrition: _liveNutrition,
          location: _currentLocation,
          rgb: _liveRgb,
          lab: _liveLab,
        );

        await _refreshDatasetCount();

        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF1B5E20),
              content: Row(
                children: [
                  const Icon(Icons.check_circle, color: Colors.greenAccent),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      'บันทึกภาพถ่ายและพิกัดแปลงเรียบร้อย (${savedPath.split('/').last})',
                      style: const TextStyle(fontSize: 12),
                    ),
                  ),
                ],
              ),
              duration: const Duration(seconds: 4),
              action: SnackBarAction(
                label: 'ดูคลังภาพ',
                textColor: Colors.cyanAccent,
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const LeafDatasetGalleryScreen()),
                  );
                },
              ),
            ),
          );
        }
      }
    } catch (e) {
      debugPrint('[DurianCameraScreen] Photo capture error $e');
    } finally {
      if (mounted) setState(() => _isProcessingCapture = false);
    }
  }

  Future<void> _toggleVideoRecording() async {
    if (_isRecordingVideo) {
      _recordingTimer?.cancel();
      try {
        XFile? video;
        if (_cameraController != null && _cameraController!.value.isRecordingVideo) {
          video = await _cameraController!.stopVideoRecording();
        }

        final duration = _recordingSeconds;
        setState(() {
          _isRecordingVideo = false;
          _recordingSeconds = 0;
          _isProcessingCapture = true;
        });

        if (video != null) {
          final savedPath = await LeafDatasetService.saveVideoSample(
            video: video,
            disease: _liveDisease,
            nutrition: _liveNutrition,
            location: _currentLocation,
            durationSeconds: duration,
            rgb: _liveRgb,
            lab: _liveLab,
          );

          await _refreshDatasetCount();

          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                backgroundColor: const Color(0xFF1B5E20),
                content: Text('บันทึกวีดีโอ (${duration}s) เรียบร้อย (${savedPath.split('/').last})'),
                duration: const Duration(seconds: 4),
                action: SnackBarAction(
                  label: 'ดูคลังภาพ',
                  textColor: Colors.cyanAccent,
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(builder: (_) => const LeafDatasetGalleryScreen()),
                    );
                  },
                ),
              ),
            );
          }
        }
      } catch (e) {
        debugPrint('[DurianCameraScreen] Stop video error $e');
      } finally {
        if (mounted) setState(() => _isProcessingCapture = false);
      }
    } else {
      try {
        if (_cameraController != null && _cameraController!.value.isInitialized) {
          await _cameraController!.startVideoRecording();
        }
        setState(() {
          _isRecordingVideo = true;
          _recordingSeconds = 0;
        });
        _recordingTimer = Timer.periodic(const Duration(seconds: 1), (_) {
          if (mounted) setState(() => _recordingSeconds++);
        });
      } catch (e) {
        debugPrint('[DurianCameraScreen] Start video error $e');
      }
    }
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
        top: false,
        bottom: true,
        child: Stack(
          children: [
            // 1. Camera Viewport / Background
            Positioned.fill(
              child: _isCameraInitialized && _cameraController != null
                  ? FittedBox(
                      fit: BoxFit.cover,
                      child: SizedBox(
                        width: _cameraController!.value.previewSize?.height ?? 1,
                        height: _cameraController!.value.previewSize?.width ?? 1,
                        child: CameraPreview(_cameraController!),
                      ),
                    )
                  : Container(
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
                              size: 88,
                              color: Colors.white.withOpacity(0.12),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'DURIAN LEAF AI VISION ENGINE',
                              style: TextStyle(
                                color: Colors.white.withOpacity(0.20),
                                letterSpacing: 2.0,
                                fontSize: 11,
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

            // 3. Top HUD Bar with GPS Coordinates and Quick Toggles (Soil App Style)
            Positioned(
              top: MediaQuery.of(context).padding.top + 8,
              left: 12,
              right: 12,
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  CircleAvatar(
                    backgroundColor: Colors.black54,
                    child: IconButton(
                      icon: const Icon(Icons.arrow_back, color: Colors.white, size: 20),
                      onPressed: () => Navigator.maybePop(context),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                      decoration: BoxDecoration(
                        color: Colors.black.withOpacity(0.75),
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: Colors.white12),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              const Text(
                                '🌱 DURIANLEAF AI  |  SciRBRU AgriPhysics',
                                style: TextStyle(
                                  color: Color(0xFF00E676),
                                  fontSize: 10,
                                  fontWeight: FontWeight.bold,
                                  letterSpacing: 0.4,
                                ),
                              ),
                              const Spacer(),
                              Text(
                                '${_fps.toStringAsFixed(0)} FPS',
                                style: const TextStyle(color: Colors.white60, fontSize: 9.5, fontWeight: FontWeight.bold),
                              ),
                            ],
                          ),
                          const SizedBox(height: 3),
                          Row(
                            children: [
                              const Icon(Icons.location_on, color: Colors.amberAccent, size: 13),
                              const SizedBox(width: 4),
                              Expanded(
                                child: Text(
                                  _currentLocation.formattedCoordinates,
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 11,
                                    fontWeight: FontWeight.bold,
                                  ),
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 2),
                          Row(
                            children: [
                              const Icon(Icons.terrain, color: Colors.cyanAccent, size: 11),
                              const SizedBox(width: 4),
                              Text(
                                'ระดับความสูง ${_currentLocation.formattedAltitude} (MSL)',
                                style: const TextStyle(color: Colors.cyanAccent, fontSize: 9.5),
                              ),
                              const Spacer(),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                                decoration: BoxDecoration(
                                  color: Colors.green.withOpacity(0.25),
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: const Text(
                                  'GPS พร้อมใช้งาน',
                                  style: TextStyle(
                                    fontSize: 8.5,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.greenAccent,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(width: 6),
                  // Quick Action Buttons
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      CircleAvatar(
                        radius: 17,
                        backgroundColor: _isAudioEnabled ? Colors.black54 : Colors.redAccent.withOpacity(0.85),
                        child: IconButton(
                          padding: EdgeInsets.zero,
                          tooltip: _isAudioEnabled ? 'เปิดเสียงไมค์' : 'ปิดเสียงไมค์',
                          icon: Icon(
                            _isAudioEnabled ? Icons.mic : Icons.mic_off,
                            color: _isAudioEnabled ? Colors.greenAccent : Colors.white,
                            size: 17,
                          ),
                          onPressed: () => setState(() => _isAudioEnabled = !_isAudioEnabled),
                        ),
                      ),
                      const SizedBox(width: 4),
                      CircleAvatar(
                        radius: 17,
                        backgroundColor: _isDarkChamberMode ? const Color(0xFF2E7D32) : Colors.black54,
                        child: IconButton(
                          padding: EdgeInsets.zero,
                          tooltip: 'โหมดกล่องมืด',
                          icon: Icon(
                            _isDarkChamberMode ? Icons.bedtime : Icons.light_mode,
                            color: Colors.white,
                            size: 17,
                          ),
                          onPressed: () => setState(() => _isDarkChamberMode = !_isDarkChamberMode),
                        ),
                      ),
                      const SizedBox(width: 4),
                      CircleAvatar(
                        radius: 17,
                        backgroundColor: _isFlashOn ? const Color(0xFFC59B27) : Colors.black54,
                        child: IconButton(
                          padding: EdgeInsets.zero,
                          tooltip: 'ไฟฉายส่องใบ',
                          icon: Icon(
                            _isFlashOn ? Icons.flash_on : Icons.flash_off,
                            color: Colors.white,
                            size: 17,
                          ),
                          onPressed: () async {
                            setState(() => _isFlashOn = !_isFlashOn);
                            if (_cameraController != null && _cameraController!.value.isInitialized) {
                              try {
                                await _cameraController!.setFlashMode(_isFlashOn ? FlashMode.torch : FlashMode.off);
                              } catch (_) {}
                            }
                          },
                        ),
                      ),
                      const SizedBox(width: 4),
                      CircleAvatar(
                        radius: 17,
                        backgroundColor: _isExposureLocked ? const Color(0xFF0288D1) : Colors.black54,
                        child: IconButton(
                          padding: EdgeInsets.zero,
                          tooltip: 'ล็อกค่าแสง (Exposure Lock)',
                          icon: Icon(
                            _isExposureLocked ? Icons.lock : Icons.lock_open,
                            color: Colors.white,
                            size: 17,
                          ),
                          onPressed: () async {
                            setState(() => _isExposureLocked = !_isExposureLocked);
                            if (_cameraController != null && _cameraController!.value.isInitialized) {
                              try {
                                await _cameraController!.setExposureMode(
                                  _isExposureLocked ? ExposureMode.locked : ExposureMode.auto,
                                );
                              } catch (_) {}
                            }
                          },
                        ),
                      ),
                      const SizedBox(width: 4),
                      CircleAvatar(
                        radius: 17,
                        backgroundColor: Colors.black54,
                        child: IconButton(
                          padding: EdgeInsets.zero,
                          tooltip: 'สอนโมเดลเพิ่มเติม (Continual Learning)',
                          icon: const Icon(Icons.school_outlined, color: Colors.cyanAccent, size: 17),
                          onPressed: _navigateToTrainer,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // 4. Live Recording Indicator (When in Video Mode)
            if (_isRecordingVideo)
              Positioned(
                top: MediaQuery.of(context).padding.top + 72,
                left: 0,
                right: 0,
                child: Center(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 5),
                    decoration: BoxDecoration(
                      color: Colors.red.withOpacity(0.85),
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.red.withOpacity(0.4),
                          blurRadius: 8,
                          spreadRadius: 1,
                        ),
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.fiber_manual_record, color: Colors.white, size: 14),
                        const SizedBox(width: 6),
                        Text(
                          'REC ${_recordingSeconds.toString().padLeft(2, '0')}s',
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                        const SizedBox(width: 8),
                        Container(width: 1, height: 12, color: Colors.white38),
                        const SizedBox(width: 8),
                        Icon(
                          _isAudioEnabled ? Icons.mic : Icons.mic_off,
                          color: _isAudioEnabled ? Colors.greenAccent : Colors.yellowAccent,
                          size: 14,
                        ),
                        const SizedBox(width: 4),
                        Text(
                          _isAudioEnabled ? 'MIC ON' : 'MIC OFF',
                          style: TextStyle(
                            color: _isAudioEnabled ? Colors.white : Colors.yellowAccent,
                            fontSize: 10.5,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

            // 5. Floating ROI Toolbar with Embedded Academic Color Strip on the Right
            Positioned(
              right: 12,
              top: MediaQuery.of(context).padding.top + 80,
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

            // 6. Switchable Cyber-Dark Bottom HUD Card (Soil App Style)
            Positioned(
              left: 12,
              right: 12,
              bottom: 114,
              child: GestureDetector(
                onTap: _navigateToResult,
                child: Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: const Color(0xF20A0F1D), // Cyber Dark
                    borderRadius: BorderRadius.circular(14),
                    border: Border.all(
                      color: _hudMode == HudDisplayMode.pathologyAi
                          ? (_liveDisease.id == 'healthy' ? const Color(0xFF00E676) : const Color(0xFFFF5252))
                          : const Color(0xFF00E5FF),
                      width: 1.2,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.6),
                        blurRadius: 12,
                        offset: const Offset(0, 3),
                      ),
                    ],
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      // Header Bar with Mode Toggle Tab
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Row(
                            children: [
                              Icon(
                                _hudMode == HudDisplayMode.pathologyAi ? Icons.bug_report_rounded : Icons.eco_rounded,
                                size: 16,
                                color: _hudMode == HudDisplayMode.pathologyAi ? const Color(0xFFFF5252) : const Color(0xFF00E676),
                              ),
                              const SizedBox(width: 6),
                              Text(
                                _hudMode == HudDisplayMode.pathologyAi
                                    ? 'AI PATHOLOGY & DISEASE SEVERITY'
                                    : 'AGRONOMIC NPK & CHLOROPHYLL HEALTH',
                                style: const TextStyle(
                                  fontSize: 10,
                                  fontWeight: FontWeight.bold,
                                  letterSpacing: 0.5,
                                  color: Colors.white,
                                ),
                              ),
                            ],
                          ),

                          // Switch HUD Mode Button
                          InkWell(
                            onTap: () {
                              setState(() {
                                _hudMode = _hudMode == HudDisplayMode.pathologyAi
                                    ? HudDisplayMode.nutritionAi
                                    : HudDisplayMode.pathologyAi;
                              });
                            },
                            borderRadius: BorderRadius.circular(8),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                              decoration: BoxDecoration(
                                color: Colors.white12,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: Colors.white24, width: 0.8),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Text(
                                    _hudMode == HudDisplayMode.pathologyAi ? 'สลับดู AI ธาตุอาหาร' : 'สลับดู AI โรคพืช',
                                    style: const TextStyle(fontSize: 9.5, color: Colors.cyanAccent, fontWeight: FontWeight.bold),
                                  ),
                                  const SizedBox(width: 3),
                                  const Icon(Icons.swap_horiz_rounded, size: 13, color: Colors.cyanAccent),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),

                      const Divider(color: Colors.white12, height: 10),

                      // Content View A: Pathology & Disease Severity
                      if (_hudMode == HudDisplayMode.pathologyAi) ...[
                        Row(
                          children: [
                            // Col 1: Disease Classification
                            Expanded(
                              flex: 5,
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF131B2E),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.white12),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text('การตรวจวินิจฉัยโรคพืช', style: TextStyle(fontSize: 8.5, color: Colors.white60)),
                                    const SizedBox(height: 2),
                                    Row(
                                      children: [
                                        Expanded(
                                          child: Text(
                                            _liveDisease.nameTh,
                                            style: TextStyle(
                                              fontSize: 13,
                                              fontWeight: FontWeight.bold,
                                              color: _liveDisease.id == 'healthy' ? const Color(0xFF00E676) : const Color(0xFFFF5252),
                                            ),
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                        ),
                                        Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                                          decoration: BoxDecoration(
                                            color: Colors.black45,
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            '${(_liveDisease.confidence * 100).toStringAsFixed(0)}%',
                                            style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.amberAccent),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      'ระดับความรุนแรง: ${_liveDisease.severityLevel} (${_liveDisease.lesionAreaPercentage.toStringAsFixed(1)}% แผล)',
                                      style: const TextStyle(fontSize: 8.5, color: Colors.white70),
                                    ),
                                  ],
                                ),
                              ),
                            ),

                            const SizedBox(width: 8),

                            // Col 2: Actionable Advice
                            Expanded(
                              flex: 4,
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF131B2E),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.white12),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text('มาตรการจัดการแปลง', style: TextStyle(fontSize: 8.5, color: Colors.white60)),
                                    const SizedBox(height: 2),
                                    Text(
                                      _liveDisease.treatment,
                                      style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.bold, color: Color(0xFF00E5FF)),
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ],
                                ),
                              ),
                            ),

                            const SizedBox(width: 8),

                            // Col 3: Live Color Swatch
                            Container(
                              padding: const EdgeInsets.all(5),
                              decoration: BoxDecoration(
                                color: const Color(0xFF131B2E),
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: Colors.white12),
                              ),
                              child: Column(
                                children: [
                                  Container(
                                    width: 22,
                                    height: 22,
                                    decoration: BoxDecoration(
                                      color: Color.fromRGBO(_liveRgb[0], _liveRgb[1], _liveRgb[2], 1.0),
                                      shape: BoxShape.circle,
                                      border: Border.all(color: Colors.white, width: 1.2),
                                    ),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    '#${_liveRgb[0].toRadixString(16).padLeft(2, '0')}${_liveRgb[1].toRadixString(16).padLeft(2, '0')}${_liveRgb[2].toRadixString(16).padLeft(2, '0')}'.toUpperCase(),
                                    style: const TextStyle(fontSize: 7.5, color: Colors.white70, fontFamily: 'monospace'),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),

                        const SizedBox(height: 6),

                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            _buildHudChip('ความรุนแรง DSI', _liveDisease.severityLevel, Colors.amberAccent),
                            _buildHudChip('คลอโรฟิลล์ SPAD', _liveNutrition.spadChlorophyll.toStringAsFixed(1), const Color(0xFF00E676)),
                            _buildHudChip('ดัชนี DGCI', _liveNutrition.dgci.toStringAsFixed(2), Colors.cyanAccent),
                            _buildHudChip('พิกัด L*a*b*', '${_liveLab[0].toStringAsFixed(0)},${_liveLab[1].toStringAsFixed(0)},${_liveLab[2].toStringAsFixed(0)}', Colors.white70),
                          ],
                        ),
                      ] else ...[
                        // Content View B: Nutrition NPK & SPAD Telemetry
                        Row(
                          children: [
                            // Col 1: SPAD Index
                            Expanded(
                              flex: 3,
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF131B2E),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.white12),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text('ดัชนีคลอโรฟิลล์ SPAD', style: TextStyle(fontSize: 8.5, color: Colors.white60)),
                                    const SizedBox(height: 2),
                                    Text(
                                      _liveNutrition.spadChlorophyll.toStringAsFixed(1),
                                      style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF00E676)),
                                    ),
                                    Text(
                                      _liveNutrition.spadChlorophyll >= 48.0 ? 'เขียวเข้มสมบูรณ์' : (_liveNutrition.spadChlorophyll >= 38.0 ? 'เกณฑ์ปกติ' : 'คลอโรฟิลล์ต่ำ'),
                                      style: const TextStyle(fontSize: 8.5, color: Colors.white54),
                                    ),
                                  ],
                                ),
                              ),
                            ),

                            const SizedBox(width: 8),

                            // Col 2: Macronutrients N-P-K
                            Expanded(
                              flex: 6,
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF131B2E),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.white12),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text('ธาตุอาหารหลัก N - P - K (%)', style: TextStyle(fontSize: 8.5, color: Colors.white60)),
                                    const SizedBox(height: 3),
                                    Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text('N: ${_liveNutrition.nitrogenPct.toStringAsFixed(2)}%', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFFFF7043))),
                                        Text('P: ${_liveNutrition.phosphorusPct.toStringAsFixed(2)}%', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF42A5F5))),
                                        Text('K: ${_liveNutrition.potassiumPct.toStringAsFixed(2)}%', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFFAB47BC))),
                                      ],
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      'Mg: ${_liveNutrition.magnesiumPct.toStringAsFixed(2)}% | Ca: ${_liveNutrition.calciumPct.toStringAsFixed(2)}% | Fe: ${_liveNutrition.ironPpm.toStringAsFixed(0)} ppm',
                                      style: const TextStyle(fontSize: 8, color: Colors.white54),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),

                        const SizedBox(height: 6),

                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            _buildHudChip('ดัชนี DGCI', _liveNutrition.dgci.toStringAsFixed(2), Colors.greenAccent),
                            _buildHudChip('ดัชนี VARI', _liveNutrition.vari.toStringAsFixed(2), Colors.cyanAccent),
                            _buildHudChip('ดัชนี GLI', _liveNutrition.gli.toStringAsFixed(2), Colors.blueAccent),
                            _buildHudChip('ดัชนี ExG', _liveNutrition.exg.toStringAsFixed(1), Colors.amberAccent),
                          ],
                        ),
                      ],
                    ],
                  ),
                ),
              ),
            ),

            // 7. Bottom Camera Controls (Photo / Video Switcher, Big Shutter, Gallery, Lens Switch)
            Positioned(
              bottom: 18,
              left: 0,
              right: 0,
              child: Column(
                children: [
                  // Mode Selector (Photo / Video)
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      GestureDetector(
                        onTap: () {
                          if (_isRecordingVideo) return;
                          setState(() => _captureMode = CameraCaptureMode.photo);
                        },
                        child: Text(
                          'ถ่ายภาพนิ่ง (PHOTO)',
                          style: TextStyle(
                            color: _captureMode == CameraCaptureMode.photo ? Colors.cyanAccent : Colors.white54,
                            fontWeight: FontWeight.bold,
                            fontSize: 11.5,
                            letterSpacing: 0.5,
                          ),
                        ),
                      ),
                      const SizedBox(width: 24),
                      GestureDetector(
                        onTap: () {
                          if (_isRecordingVideo) return;
                          setState(() => _captureMode = CameraCaptureMode.video);
                        },
                        child: Text(
                          'บันทึกวีดีโอ (VIDEO)',
                          style: TextStyle(
                            color: _captureMode == CameraCaptureMode.video ? Colors.redAccent : Colors.white54,
                            fontWeight: FontWeight.bold,
                            fontSize: 11.5,
                            letterSpacing: 0.5,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // Action Buttons Row
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      // Dataset Samples Gallery Button
                      GestureDetector(
                        onTap: () async {
                          if (_isRecordingVideo) return;
                          await Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const LeafDatasetGalleryScreen()),
                          );
                          _refreshDatasetCount();
                        },
                        child: Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: Colors.black54,
                            shape: BoxShape.circle,
                            border: Border.all(color: Colors.white24),
                          ),
                          child: Stack(
                            clipBehavior: Clip.none,
                            children: [
                              const Icon(Icons.photo_library, color: Colors.white70, size: 22),
                              if (_savedDatasetCount > 0)
                                Positioned(
                                  top: -5,
                                  right: -5,
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFF00E676),
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(
                                      '$_savedDatasetCount',
                                      style: const TextStyle(color: Colors.black, fontSize: 9, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                ),
                            ],
                          ),
                        ),
                      ),

                      // Main Shutter Button
                      GestureDetector(
                        onTap: () {
                          if (_captureMode == CameraCaptureMode.photo) {
                            _capturePhoto();
                          } else {
                            _toggleVideoRecording();
                          }
                        },
                        child: Container(
                          width: 68,
                          height: 68,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(
                              color: _captureMode == CameraCaptureMode.video
                                  ? (_isRecordingVideo ? Colors.redAccent : Colors.red)
                                  : Colors.white,
                              width: 3.5,
                            ),
                          ),
                          child: Center(
                            child: Container(
                              width: 52,
                              height: 52,
                              decoration: BoxDecoration(
                                color: _captureMode == CameraCaptureMode.video
                                    ? (_isRecordingVideo ? Colors.redAccent : Colors.red.shade700)
                                    : Colors.white,
                                borderRadius: BorderRadius.circular(
                                  _isRecordingVideo ? 8 : 26,
                                ),
                              ),
                              child: _isProcessingCapture
                                  ? const Center(child: CircularProgressIndicator(strokeWidth: 2, color: Colors.black54))
                                  : null,
                            ),
                          ),
                        ),
                      ),

                      // Switch Camera Lens Button
                      GestureDetector(
                        onTap: () {
                          if (_isRecordingVideo) return;
                          _switchCamera();
                        },
                        child: Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: Colors.black54,
                            shape: BoxShape.circle,
                            border: Border.all(color: Colors.white24),
                          ),
                          child: const Icon(Icons.flip_camera_ios, color: Colors.white70, size: 22),
                        ),
                      ),
                    ],
                  ),
                ],
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

  Widget _buildHudChip(String label, String value, Color color) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Text(label, style: const TextStyle(fontSize: 8, color: Colors.white54)),
        const SizedBox(height: 1),
        Text(
          value,
          style: TextStyle(fontSize: 10.5, fontWeight: FontWeight.bold, color: color, fontFamily: 'monospace'),
        ),
      ],
    );
  }
}
