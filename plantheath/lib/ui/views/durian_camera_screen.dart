import 'dart:async';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import '../../models/disease_diagnosis.dart';
import '../../models/geo_location_data.dart';
import '../../models/leaf_color_tier.dart';
import '../../models/nutrient_health_metric.dart';
import '../../models/tropical_pomology_models.dart';
import '../../services/continual_learning_service.dart';
import '../../services/deep_learning_inference_service.dart';
import '../../services/geo_location_service.dart';
import '../../services/handysense_sensor_service.dart';
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
  bool _isStreamActive = false;
  bool _isProcessingFrame = false;
  DateTime _lastFrameTime = DateTime.now();

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

  // ROI Controls & Interactive Touch/Drag Position
  RoiShape _roiShape = RoiShape.rectangle;
  double _roiSize = 180.0;
  ScaleMode _scaleMode = ScaleMode.spad;
  double _fps = 30.0;
  Offset? _roiCenter;
  bool _useMgKgUnit = true; // Default to mg/kg (ppm) as requested

  // Cached screen size - updated in build() to avoid MediaQuery in stream callbacks
  Size _cachedScreenSize = const Size(390, 844); // default iPhone 14 size
  double _cachedTopPadding = 44.0; // default status bar + notch height

  // Live Analysis State
  DiseaseDiagnosis _liveDisease = DiseaseDiagnosis.empty();
  NutrientHealthMetric _liveNutrition = NutrientHealthMetric.defaultHealthy();
  List<int> _liveRgb = [46, 125, 50];
  List<double> _liveLab = [46.8, -38.5, 32.1];

  // Tropical Pomology & Crop Stage State
  LeafAgeStage _selectedLeafAge = LeafAgeStage.youngMature;
  TreeCropStage _selectedCropStage = TreeCropStage.flushRecovery;
  String _selectedTreeId = 'DUR-ต้นที่ 01';

  // HandySense Microclimate Telemetry
  HandySenseTelemetry _latestTelemetry = HandySenseTelemetry.mockChanthaburiOrchard();
  StreamSubscription<HandySenseTelemetry>? _telemetrySub;
  Timer? _telemetryDriftTimer;

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
    _initHandySenseTelemetry();
  }

  void _initHandySenseTelemetry() {
    _telemetrySub = HandySenseSensorService.telemetryStream.listen((telem) {
      if (mounted) setState(() => _latestTelemetry = telem);
    });
    int tick = 0;
    _telemetryDriftTimer = Timer.periodic(const Duration(seconds: 4), (timer) {
      tick++;
      HandySenseSensorService.driftTelemetry(tick);
    });
  }

  @override
  void dispose() {
    _stopImageStream();
    _recordingTimer?.cancel();
    _streamSimulationTimer?.cancel();
    _locationSubscription?.cancel();
    _telemetrySub?.cancel();
    _telemetryDriftTimer?.cancel();
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
    await _stopImageStream();
    await _cameraController?.dispose();

    // Medium resolution (720p) ensures butter-smooth 60fps preview and minimal memory consumption
    final controller = CameraController(
      camera,
      ResolutionPreset.medium,
      enableAudio: false,
      imageFormatGroup: ImageFormatGroup.yuv420,
    );

    try {
      await controller.initialize();
      if (mounted) {
        setState(() {
          _cameraController = controller;
          _isCameraInitialized = true;
        });
        await _startImageStreamAnalysis();
      }
    } catch (e) {
      debugPrint('[DurianCameraScreen] Camera setup error $e');
    }
  }

  Future<void> _startImageStreamAnalysis() async {
    if (_cameraController == null || !_cameraController!.value.isInitialized || _isStreamActive) {
      return;
    }
    _isStreamActive = true;
    try {
      await _cameraController!.startImageStream((CameraImage image) {
        _processLiveCameraFrame(image);
      });
    } catch (e) {
      debugPrint('[DurianCameraScreen] startImageStream error $e');
      _isStreamActive = false;
    }
  }

  Future<void> _stopImageStream() async {
    if (_isStreamActive && _cameraController != null && _cameraController!.value.isInitialized) {
      try {
        await _cameraController!.stopImageStream();
      } catch (_) {}
      _isStreamActive = false;
    }
  }

  void _processLiveCameraFrame(CameraImage image) {
    if (_isProcessingFrame) return;
    final now = DateTime.now();
    // Throttle inference to every ~120ms — fast enough to show real-time color changes
    final elapsedMs = now.difference(_lastFrameTime).inMilliseconds;
    if (elapsedMs < 120) return;
    _isProcessingFrame = true;
    // Update lastFrameTime AFTER capturing elapsed for FPS calculation
    final double currentFps = elapsedMs > 0 ? (1000.0 / elapsedMs).clamp(1.0, 60.0) : 30.0;
    _lastFrameTime = now;

    try {
      // Use cached screen size — NEVER call MediaQuery.of(context) from stream callbacks
      // It can throw FlutterError and silently kill the inference pipeline
      final screenSize = _cachedScreenSize;
      final curRoi = _roiCenter ?? Offset(screenSize.width / 2, screenSize.height * 0.38);

      final normX = (curRoi.dx / screenSize.width).clamp(0.05, 0.95);
      final normY = (curRoi.dy / screenSize.height).clamp(0.05, 0.95);

      final halfRoiNormW = (_roiWidth / 2) / screenSize.width;
      final halfRoiNormH = (_roiHeight / 2) / screenSize.height;

      int totalR = 0, totalG = 0, totalB = 0, sampleCount = 0;

      if (image.planes.length >= 3) {
        final yPlane = image.planes[0];
        final uPlane = image.planes[1];
        final vPlane = image.planes[2];

        final yBytes = yPlane.bytes;
        final uBytes = uPlane.bytes;
        final vBytes = vPlane.bytes;

        final yRowStride = yPlane.bytesPerRow;
        final uRowStride = uPlane.bytesPerRow;
        final uPixelStride = uPlane.bytesPerPixel ?? 1;
        final vRowStride = vPlane.bytesPerRow;
        final vPixelStride = vPlane.bytesPerPixel ?? 1;

        // Sample a grid of points inside the user-selected leaf ROI
        for (double dy = -0.5; dy <= 0.5; dy += 0.25) {
          for (double dx = -0.5; dx <= 0.5; dx += 0.25) {
            final sampleNormX = (normX + dx * halfRoiNormW).clamp(0.02, 0.98);
            final sampleNormY = (normY + dy * halfRoiNormH).clamp(0.02, 0.98);

            // In Android portrait orientation, sensor orientation is 90 deg clockwise
            final imgX = (sampleNormY * image.width).toInt().clamp(0, image.width - 1);
            final imgY = ((1.0 - sampleNormX) * image.height).toInt().clamp(0, image.height - 1);

            final yIndex = imgY * yRowStride + imgX;
            final uIndex = (imgY ~/ 2) * uRowStride + (imgX ~/ 2) * uPixelStride;
            final vIndex = (imgY ~/ 2) * vRowStride + (imgX ~/ 2) * vPixelStride;

            if (yIndex < yBytes.length && uIndex < uBytes.length && vIndex < vBytes.length) {
              final yVal = yBytes[yIndex];
              final uVal = uBytes[uIndex];
              final vVal = vBytes[vIndex];

              // ITU-R BT.601 YUV to RGB Conversion Matrix
              final r = (yVal + (1.402 * (vVal - 128))).round().clamp(0, 255);
              final g = (yVal - (0.344136 * (uVal - 128)) - (0.714136 * (vVal - 128))).round().clamp(0, 255);
              final b = (yVal + (1.772 * (uVal - 128))).round().clamp(0, 255);

              // Cuticular Wax & Specular Glare Software Filter:
              // Discard blown-out white glare reflections on glossy durian leaf cuticle
              final isSpecularGlare = yVal > 232 || (r > 225 && g > 225 && b > 205);
              if (!isSpecularGlare) {
                totalR += r;
                totalG += g;
                totalB += b;
                sampleCount++;
              }
            }
          }
        }
      }

      final avgR = sampleCount > 0 ? (totalR ~/ sampleCount) : _liveRgb[0];
      final avgG = sampleCount > 0 ? (totalG ~/ sampleCount) : _liveRgb[1];
      final avgB = sampleCount > 0 ? (totalB ~/ sampleCount) : _liveRgb[2];

      // Run real Deep Learning inference pipeline with Pomology & Microclimate context
      final result = widget.inferenceService.analyzeLeaf(
        r: avgR,
        g: avgG,
        b: avgB,
        leafAge: _selectedLeafAge,
        cropStage: _selectedCropStage,
        environment: _latestTelemetry,
      );

      if (mounted) {
        setState(() {
          _fps = currentFps;
          _liveDisease = result.disease;
          _liveNutrition = result.nutrition;
          _liveRgb = [avgR, avgG, avgB];
          _liveLab = result.nutrition.lab;
        });
      }
    } catch (e) {
      debugPrint('[DurianCameraScreen] Frame process error $e');
    } finally {
      _isProcessingFrame = false;
    }
  }

  Future<void> _switchCamera() async {
    if (_cameras.length < 2) return;
    _selectedCameraIndex = (_selectedCameraIndex + 1) % _cameras.length;
    setState(() => _isCameraInitialized = false);
    await _setupCameraController(_cameras[_selectedCameraIndex]);
  }

  void _startLiveStreamAnalysis() {
    _streamSimulationTimer?.cancel();
    int frameCount = 0;
    _streamSimulationTimer = Timer.periodic(const Duration(milliseconds: 320), (timer) {
      if (!mounted) return;
      // If hardware camera image stream is actively running, skip simulation
      if (_isStreamActive) return;
      frameCount++;

      // Fallback synthetic spectral variations when running on non-camera environment
      int r = (46 + (frameCount % 12) * 2).clamp(0, 255);
      int g = (125 - (frameCount % 8) * 2).clamp(0, 255);
      int b = (50 + (frameCount % 6)).clamp(0, 255);

      final result = widget.inferenceService.analyzeLeaf(
        r: r,
        g: g,
        b: b,
        leafAge: _selectedLeafAge,
        cropStage: _selectedCropStage,
        environment: _latestTelemetry,
      );

      setState(() {
        _liveDisease = result.disease;
        _liveNutrition = result.nutrition;
        _liveRgb = [r, g, b];
        _liveLab = result.nutrition.lab;
        _fps = 29.5 + ((frameCount % 5) * 0.2);
      });
    });
  }

  double get _roiWidth => _roiSize;
  double get _roiHeight => _roiShape == RoiShape.rectangle ? _roiSize * 1.3 : _roiSize;

  Offset _clampRoiCenter(Offset pos, Size screenSize) {
    final double halfW = _roiWidth / 2;
    final double halfH = _roiHeight / 2;
    final double minX = halfW + 10;
    final double maxX = screenSize.width - halfW - 10;
    // Use _cachedTopPadding (set in build()) to avoid MediaQuery in gesture callbacks
    final double minY = _cachedTopPadding + 70 + halfH;
    final double maxY = screenSize.height - 220 - halfH;

    return Offset(
      pos.dx.clamp(minX, maxX),
      pos.dy.clamp(minY, maxY),
    );
  }

  Future<void> _capturePhoto() async {
    if (_isProcessingCapture) return;
    setState(() => _isProcessingCapture = true);

    try {
      XFile? photo;
      if (_cameraController != null && _cameraController!.value.isInitialized) {
        await _stopImageStream();
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
          treeId: _selectedTreeId,
          leafStage: _selectedLeafAge.id,
          cropStage: _selectedCropStage.id,
          vpdKpa: _latestTelemetry.vpdKpa,
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
      await _startImageStreamAnalysis();
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
            treeId: _selectedTreeId,
            leafStage: _selectedLeafAge.id,
            cropStage: _selectedCropStage.id,
            vpdKpa: _latestTelemetry.vpdKpa,
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
        await _startImageStreamAnalysis();
      }
    } else {
      try {
        if (_cameraController != null && _cameraController!.value.isInitialized) {
          await _stopImageStream();
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
        await _startImageStreamAnalysis();
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
    // Cache screen size here (main thread) so stream callbacks can safely use it
    _cachedScreenSize = MediaQuery.of(context).size;
    _cachedTopPadding = MediaQuery.of(context).padding.top;
    final double bottomPadding = MediaQuery.of(context).padding.bottom;
    final double topPadding = MediaQuery.of(context).padding.top;
    // Responsive bottom HUD positioning based on actual safe area
    final double hudBottomOffset = bottomPadding + 110.0;
    final double controlsBottomOffset = bottomPadding + 14.0;

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

            // 2. Interactive Touch & Drag Viewport Layer for Leaf ROI Selection
            Positioned.fill(
              child: GestureDetector(
                behavior: HitTestBehavior.translucent,
                onTapDown: (details) {
                  setState(() {
                    _roiCenter = _clampRoiCenter(details.localPosition, MediaQuery.of(context).size);
                  });
                },
                onPanUpdate: (details) {
                  setState(() {
                    _roiCenter = _clampRoiCenter(details.localPosition, MediaQuery.of(context).size);
                  });
                },
              ),
            ),

            // 3. Dynamic Positioned ROI Bounding Box Overlay with Live RGB Badge
            Builder(
              builder: (context) {
                final screenSize = MediaQuery.of(context).size;
                final curRoi = _roiCenter ?? Offset(screenSize.width / 2, screenSize.height * 0.38);

                return Positioned(
                  left: curRoi.dx - (_roiWidth / 2),
                  top: curRoi.dy - (_roiHeight / 2) - 24,
                  child: IgnorePointer(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2.5),
                          decoration: BoxDecoration(
                            color: Colors.black.withOpacity(0.82),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(
                              color: _liveDisease.isLeaf ? const Color(0xFF00E676) : Colors.amberAccent,
                              width: 0.9,
                            ),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.4),
                                blurRadius: 4,
                              ),
                            ],
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Container(
                                width: 9,
                                height: 9,
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  color: Color.fromRGBO(_liveRgb[0], _liveRgb[1], _liveRgb[2], 1.0),
                                  border: Border.all(color: Colors.white, width: 1),
                                ),
                              ),
                              const SizedBox(width: 5),
                              Text(
                                _liveDisease.isLeaf
                                    ? 'RGB(${_liveRgb[0]},${_liveRgb[1]},${_liveRgb[2]})  |  แตะลาก ROI'
                                    : '🔍 ไม่พบใบพืช  |  เลื่อนกรอบไปที่ใบ',
                                style: TextStyle(
                                  color: _liveDisease.isLeaf ? Colors.white : Colors.amberAccent,
                                  fontSize: 9,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 4),
                        _buildRoiOverlay(),
                      ],
                    ),
                  ),
                );
              },
            ),

            // 4. Top HUD Bar with GPS Coordinates & Scrollable Quick Toggles (No Overflow)
            Positioned(
              top: topPadding + 8,
              left: 12,
              right: 12,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      CircleAvatar(
                        radius: 18,
                        backgroundColor: Colors.black54,
                        child: IconButton(
                          padding: EdgeInsets.zero,
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
                                  const Expanded(
                                    child: Text(
                                      '🌱 DURIANLEAF AI  |  SciRBRU AgriPhysics',
                                      style: TextStyle(
                                        color: Color(0xFF00E676),
                                        fontSize: 10,
                                        fontWeight: FontWeight.bold,
                                        letterSpacing: 0.4,
                                      ),
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ),
                                  const SizedBox(width: 4),
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
                                  Expanded(
                                    child: Text(
                                      'ระดับความสูง ${_currentLocation.formattedAltitude} (MSL)',
                                      style: const TextStyle(color: Colors.cyanAccent, fontSize: 9.5),
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ),
                                  const SizedBox(width: 4),
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
                    ],
                  ),
                  const SizedBox(height: 6),
                  // Quick Action Scrollable Strip (Prevents horizontal screen overflow)
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        _buildQuickActionChip(
                          icon: _isAudioEnabled ? Icons.mic : Icons.mic_off,
                          color: _isAudioEnabled ? Colors.greenAccent : Colors.white70,
                          bg: _isAudioEnabled ? Colors.black54 : Colors.redAccent.withOpacity(0.85),
                          label: _isAudioEnabled ? 'ไมค์' : 'ปิดไมค์',
                          onTap: () => setState(() => _isAudioEnabled = !_isAudioEnabled),
                        ),
                        const SizedBox(width: 6),
                        _buildQuickActionChip(
                          icon: _isDarkChamberMode ? Icons.bedtime : Icons.light_mode,
                          color: Colors.white,
                          bg: _isDarkChamberMode ? const Color(0xFF2E7D32) : Colors.black54,
                          label: 'กล่องมืด',
                          onTap: () => setState(() => _isDarkChamberMode = !_isDarkChamberMode),
                        ),
                        const SizedBox(width: 6),
                        _buildQuickActionChip(
                          icon: _isFlashOn ? Icons.flash_on : Icons.flash_off,
                          color: Colors.white,
                          bg: _isFlashOn ? const Color(0xFFC59B27) : Colors.black54,
                          label: 'ไฟฉาย',
                          onTap: () async {
                            setState(() => _isFlashOn = !_isFlashOn);
                            if (_cameraController != null && _cameraController!.value.isInitialized) {
                              try {
                                await _cameraController!.setFlashMode(_isFlashOn ? FlashMode.torch : FlashMode.off);
                              } catch (_) {}
                            }
                          },
                        ),
                        const SizedBox(width: 6),
                        _buildQuickActionChip(
                          icon: _isExposureLocked ? Icons.lock : Icons.lock_open,
                          color: Colors.white,
                          bg: _isExposureLocked ? const Color(0xFF0288D1) : Colors.black54,
                          label: 'ล็อกแสง',
                          onTap: () async {
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
                        const SizedBox(width: 6),
                        _buildQuickActionChip(
                          icon: Icons.school_outlined,
                          color: Colors.cyanAccent,
                          bg: Colors.black54,
                          label: 'สอนโมเดล AI',
                          onTap: _navigateToTrainer,
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 5),
                  // HandySense Microclimate Telemetry Pill
                  GestureDetector(
                    onTap: _showHandySenseDetailsSheet,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: _latestTelemetry.isStressCondition
                            ? Colors.amber.shade900.withOpacity(0.85)
                            : Colors.black.withOpacity(0.72),
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(
                          color: _latestTelemetry.isStressCondition
                              ? Colors.amberAccent
                              : const Color(0xFF00E5FF).withOpacity(0.4),
                          width: 1,
                        ),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            _latestTelemetry.isStressCondition ? Icons.warning_amber_rounded : Icons.sensors,
                            size: 13,
                            color: _latestTelemetry.isStressCondition ? Colors.amberAccent : const Color(0xFF00E5FF),
                          ),
                          const SizedBox(width: 5),
                          Text(
                            'HandySense | ${_latestTelemetry.temperatureC.toStringAsFixed(1)}°C  ${_latestTelemetry.relativeHumidity.toStringAsFixed(0)}%RH  VPD ${_latestTelemetry.vpdKpa.toStringAsFixed(2)}kPa  ดิน ${_latestTelemetry.soilMoisturePct.toStringAsFixed(0)}%',
                            style: TextStyle(
                              fontSize: 9.5,
                              color: _latestTelemetry.isStressCondition ? Colors.white : Colors.cyanAccent.shade100,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(width: 4),
                          const Icon(Icons.info_outline, size: 11, color: Colors.white70),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 5),
                  // Tropical Pomology & Tree ID Selector Strip
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        // Tree ID Chip
                        GestureDetector(
                          onTap: _showTreeIdSelectorDialog,
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                            decoration: BoxDecoration(
                              color: const Color(0xFF1B5E20).withOpacity(0.85),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: Colors.lightGreenAccent.withOpacity(0.5)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.park, size: 11, color: Colors.greenAccent),
                                const SizedBox(width: 4),
                                Text(
                                  _selectedTreeId,
                                  style: const TextStyle(fontSize: 9.5, color: Colors.white, fontWeight: FontWeight.bold),
                                ),
                              ],
                            ),
                          ),
                        ),
                        const SizedBox(width: 6),
                        // Leaf Age Chip
                        GestureDetector(
                          onTap: _showLeafAgePickerSheet,
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                            decoration: BoxDecoration(
                              color: Colors.black54,
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: Colors.cyanAccent.withOpacity(0.4)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.eco, size: 11, color: Colors.cyanAccent),
                                const SizedBox(width: 4),
                                Text(
                                  'รุ่นใบ: ${_selectedLeafAge.nameTh.split(' ').first}',
                                  style: const TextStyle(fontSize: 9.5, color: Colors.white, fontWeight: FontWeight.bold),
                                ),
                                const Icon(Icons.arrow_drop_down, size: 13, color: Colors.white70),
                              ],
                            ),
                          ),
                        ),
                        const SizedBox(width: 6),
                        // Crop Stage Chip
                        GestureDetector(
                          onTap: _showCropStagePickerSheet,
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                            decoration: BoxDecoration(
                              color: _selectedCropStage == TreeCropStage.floralInduction
                                  ? const Color(0xFFB71C1C).withOpacity(0.85)
                                  : Colors.black54,
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(
                                color: _selectedCropStage == TreeCropStage.floralInduction
                                    ? Colors.redAccent
                                    : Colors.amberAccent.withOpacity(0.4),
                              ),
                            ),
                            child: Row(
                              children: [
                                Icon(
                                  Icons.calendar_today,
                                  size: 10,
                                  color: _selectedCropStage == TreeCropStage.floralInduction ? Colors.white : Colors.amberAccent,
                                ),
                                const SizedBox(width: 4),
                                Text(
                                  'ระยะต้น: ${_selectedCropStage.nameTh.split(' ').first}',
                                  style: const TextStyle(fontSize: 9.5, color: Colors.white, fontWeight: FontWeight.bold),
                                ),
                                const Icon(Icons.arrow_drop_down, size: 13, color: Colors.white70),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // 4. Live Recording Indicator (When in Video Mode)
            if (_isRecordingVideo)
              Positioned(
                top: topPadding + 72,
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
              top: topPadding + 80,
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

            // 6. Switchable Cyber-Dark Bottom HUD Card (Soil App Style) - Responsive
            Positioned(
              left: 12,
              right: 12,
              bottom: hudBottomOffset,
              child: GestureDetector(
                onTap: _navigateToResult,
                child: Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: const Color(0xF20A0F1D), // Cyber Dark
                    borderRadius: BorderRadius.circular(14),
                    border: Border.all(
                      color: _hudMode == HudDisplayMode.pathologyAi
                          ? (!_liveDisease.isLeaf
                              ? Colors.amberAccent
                              : (_liveDisease.id == 'healthy' ? const Color(0xFF00E676) : const Color(0xFFFF5252)))
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
                          Expanded(
                            child: Row(
                              children: [
                                Icon(
                                  _hudMode == HudDisplayMode.pathologyAi ? Icons.bug_report_rounded : Icons.eco_rounded,
                                  size: 16,
                                  color: _hudMode == HudDisplayMode.pathologyAi ? const Color(0xFFFF5252) : const Color(0xFF00E676),
                                ),
                                const SizedBox(width: 6),
                                Expanded(
                                  child: Text(
                                    _hudMode == HudDisplayMode.pathologyAi
                                        ? 'AI PATHOLOGY & DISEASE SEVERITY'
                                        : 'AGRONOMIC NPK & CHLOROPHYLL HEALTH',
                                    style: const TextStyle(
                                      fontSize: 10,
                                      fontWeight: FontWeight.bold,
                                      letterSpacing: 0.5,
                                      color: Colors.white,
                                    ),
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(width: 6),

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
                                              color: !_liveDisease.isLeaf
                                                  ? Colors.amberAccent
                                                  : (_liveDisease.id == 'healthy' ? const Color(0xFF00E676) : const Color(0xFFFF5252)),
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
                                            _liveDisease.isLeaf
                                                ? '${(_liveDisease.confidence * 100).toStringAsFixed(0)}%'
                                                : 'รอตรวจ',
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

                            // Col 2: Macronutrients N-P-K (mg/kg or %) with Confidence %
                            Expanded(
                              flex: 6,
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 5, horizontal: 8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF131B2E),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.white12),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'ธาตุอาหาร (${_useMgKgUnit ? "mg/kg" : "%"}) • AI ${_liveNutrition.overallConfidence.toStringAsFixed(1)}%',
                                          style: const TextStyle(fontSize: 8.5, color: Colors.greenAccent, fontWeight: FontWeight.bold),
                                        ),
                                        InkWell(
                                          onTap: () => setState(() => _useMgKgUnit = !_useMgKgUnit),
                                          child: Container(
                                            padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1.5),
                                            decoration: BoxDecoration(
                                              color: Colors.white12,
                                              borderRadius: BorderRadius.circular(4),
                                              border: Border.all(color: Colors.white24, width: 0.8),
                                            ),
                                            child: Text(
                                              _useMgKgUnit ? 'ppm' : '%',
                                              style: const TextStyle(fontSize: 8, color: Colors.amberAccent, fontWeight: FontWeight.bold),
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 3),
                                    Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'N: ${_useMgKgUnit ? _liveNutrition.nitrogenMgKg.toStringAsFixed(0) : "${_liveNutrition.nitrogenPct.toStringAsFixed(2)}%"}',
                                          style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.bold, color: Color(0xFFFF7043)),
                                        ),
                                        Text(
                                          'P: ${_useMgKgUnit ? _liveNutrition.phosphorusMgKg.toStringAsFixed(0) : "${_liveNutrition.phosphorusPct.toStringAsFixed(2)}%"}',
                                          style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.bold, color: Color(0xFF42A5F5)),
                                        ),
                                        Text(
                                          'K: ${_useMgKgUnit ? _liveNutrition.potassiumMgKg.toStringAsFixed(0) : "${_liveNutrition.potassiumPct.toStringAsFixed(2)}%"}',
                                          style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.bold, color: Color(0xFFAB47BC)),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      _useMgKgUnit
                                          ? 'Mg: ${_liveNutrition.magnesiumMgKg.toStringAsFixed(0)} | Ca: ${_liveNutrition.calciumMgKg.toStringAsFixed(0)} | Fe: ${_liveNutrition.ironPpm.toStringAsFixed(0)} ppm'
                                          : 'Mg: ${_liveNutrition.magnesiumPct.toStringAsFixed(2)}% | Ca: ${_liveNutrition.calciumPct.toStringAsFixed(2)}% | Fe: ${_liveNutrition.ironPpm.toStringAsFixed(0)} ppm',
                                      style: const TextStyle(fontSize: 7.8, color: Colors.white60),
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
              bottom: controlsBottomOffset,
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
    final bool isLeaf = _liveDisease.isLeaf;
    final Color activeColor = isLeaf
        ? (_liveDisease.id == 'healthy' ? const Color(0xFF00E676) : const Color(0xFFFF5252))
        : Colors.amberAccent;

    switch (_roiShape) {
      case RoiShape.circle:
        return Container(
          width: _roiSize,
          height: _roiSize,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            border: Border.all(color: activeColor, width: 2.2),
            boxShadow: [
              BoxShadow(color: activeColor.withOpacity(0.25), blurRadius: 12),
            ],
          ),
          child: Center(
            child: Icon(
              isLeaf ? Icons.add : Icons.filter_center_focus_outlined,
              color: activeColor,
              size: 18,
            ),
          ),
        );
      case RoiShape.rectangle:
        return Container(
          width: _roiSize,
          height: _roiSize * 1.3,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: activeColor, width: 2.2),
            boxShadow: [
              BoxShadow(color: activeColor.withOpacity(0.25), blurRadius: 12),
            ],
          ),
          child: Center(
            child: Icon(
              isLeaf ? Icons.add : Icons.filter_center_focus_outlined,
              color: activeColor,
              size: 18,
            ),
          ),
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
            border: Border.all(color: activeColor, width: 2.2),
          ),
          child: Center(
            child: Icon(
              isLeaf ? Icons.gesture : Icons.filter_center_focus_outlined,
              color: activeColor,
              size: 18,
            ),
          ),
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

  Widget _buildQuickActionChip({
    required IconData icon,
    required Color color,
    required Color bg,
    required String label,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(20),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        decoration: BoxDecoration(
          color: bg,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.white24, width: 0.8),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: color, size: 14),
            const SizedBox(width: 4),
            Text(
              label,
              style: TextStyle(
                color: color,
                fontSize: 9.5,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showHandySenseDetailsSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: const Color(0xFF0F172A),
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      isScrollControlled: true,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setSheetState) {
            final t = _latestTelemetry;
            return Container(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              height: MediaQuery.of(context).size.height * 0.75,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Center(
                    child: Container(
                      width: 44,
                      height: 4,
                      decoration: BoxDecoration(
                        color: Colors.white24,
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                  ),
                  const SizedBox(height: 14),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: const Color(0xFF00E676).withOpacity(0.15),
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(Icons.sensors, color: Color(0xFF00E676), size: 20),
                          ),
                          const SizedBox(width: 10),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                t.stationName,
                                style: const TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
                              ),
                              Text(
                                'IP: ${t.ipAddress} • อัปเดต: ${t.timestamp.hour.toString().padLeft(2, '0')}:${t.timestamp.minute.toString().padLeft(2, '0')}:${t.timestamp.second.toString().padLeft(2, '0')} น.',
                                style: const TextStyle(color: Colors.white54, fontSize: 10),
                              ),
                            ],
                          ),
                        ],
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: t.isStressCondition ? Colors.redAccent.withOpacity(0.2) : Colors.greenAccent.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(
                            color: t.isStressCondition ? Colors.redAccent : Colors.greenAccent,
                            width: 0.8,
                          ),
                        ),
                        child: Text(
                          t.isStressCondition ? 'ภาวะวิกฤต' : 'ปกติ',
                          style: TextStyle(
                            color: t.isStressCondition ? Colors.redAccent : Colors.greenAccent,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ],
                  ),
                  if (t.isStressCondition) ...[
                    const SizedBox(height: 10),
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.red.withOpacity(0.15),
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.redAccent, width: 0.8),
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.warning_amber_rounded, color: Colors.redAccent, size: 16),
                          const SizedBox(width: 6),
                          Expanded(
                            child: Text(
                              t.stressAlert,
                              style: const TextStyle(color: Colors.white, fontSize: 11),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                  const SizedBox(height: 14),
                  const Text(
                    'สรีรวิทยาบรรยากาศ & ดิน (Microclimate & Soil 7-in-1)',
                    style: TextStyle(color: Colors.white70, fontSize: 11.5, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  Expanded(
                    child: ListView(
                      children: [
                        Row(
                          children: [
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'VPD (แรงดึงระเหยน้ำ)',
                                value: '${t.vpdKpa.toStringAsFixed(2)} kPa',
                                subtitle: t.vpdCategory,
                                icon: Icons.air,
                                iconColor: t.vpdKpa > 1.6 ? Colors.orangeAccent : (t.vpdKpa < 0.4 ? Colors.cyanAccent : const Color(0xFF00E676)),
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ความสว่างสังเคราะห์แสง',
                                value: '${t.solarLux.toStringAsFixed(0)} Lux',
                                subtitle: t.solarLux > 40000 ? 'แดดจัดมาก' : 'แสงปกติ',
                                icon: Icons.wb_sunny,
                                iconColor: Colors.amberAccent,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'อุณหภูมิอากาศ (SHT45)',
                                value: '${t.airTempC.toStringAsFixed(1)} °C',
                                subtitle: 'จุดน้ำค้าง ${t.dewPointC.toStringAsFixed(1)} °C',
                                icon: Icons.thermostat,
                                iconColor: Colors.deepOrangeAccent,
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ความชื้นสัมพัทธ์ (SHT45)',
                                value: '${t.airHumidityRh.toStringAsFixed(1)} %RH',
                                subtitle: 'ความดันไอ ${t.actualVaporPressureKpa.toStringAsFixed(2)} kPa',
                                icon: Icons.water_drop,
                                iconColor: Colors.blueAccent,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ความชื้นดิน (Soil VWC)',
                                value: '${t.soilMoisturePct.toStringAsFixed(1)} %',
                                subtitle: t.soilMoisturePct < 30 ? 'ดินแห้ง' : 'ความชื้นเหมาะสม',
                                icon: Icons.grass,
                                iconColor: Colors.lightGreenAccent,
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ความเป็นกรดด่างดิน (pH)',
                                value: '${t.soilPh.toStringAsFixed(1)} pH',
                                subtitle: (t.soilPh >= 5.5 && t.soilPh <= 6.5) ? 'กรดอ่อนเหมาะสม' : 'นอกเกณฑ์',
                                icon: Icons.science,
                                iconColor: Colors.purpleAccent,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ความนำไฟฟ้าดิน (EC)',
                                value: '${t.soilEcUsCm.toStringAsFixed(0)} µS/cm',
                                subtitle: 'เกลือในดินปกติ',
                                icon: Icons.bolt,
                                iconColor: Colors.tealAccent,
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: _buildSensorMetricCard(
                                title: 'ปุ๋ยตกค้างในดิน NPK',
                                value: '${t.soilNitrogenMgKg.toStringAsFixed(0)}-${t.soilPhosphorusMgKg.toStringAsFixed(0)}-${t.soilPotassiumMgKg.toStringAsFixed(0)}',
                                subtitle: 'หน่วย mg/kg (ppm)',
                                icon: Icons.eco,
                                iconColor: Colors.pinkAccent,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () {
                        setState(() {
                          _latestTelemetry = HandySenseSensorService.mockChanthaburiOrchard();
                        });
                        setSheetState(() {});
                        Navigator.pop(context);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF00E676),
                        foregroundColor: Colors.black,
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      icon: const Icon(Icons.sync),
                      label: const Text('จำลองดึงข้อมูลสด HandySense IoT Node', style: TextStyle(fontWeight: FontWeight.bold)),
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildSensorMetricCard({
    required String title,
    required String value,
    required String subtitle,
    required IconData icon,
    required Color iconColor,
  }) {
    return Container(
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: Colors.white12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, color: iconColor, size: 14),
              const SizedBox(width: 5),
              Expanded(
                child: Text(
                  title,
                  style: const TextStyle(color: Colors.white60, fontSize: 10),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold, fontFamily: 'monospace'),
          ),
          const SizedBox(height: 2),
          Text(
            subtitle,
            style: TextStyle(color: iconColor.withOpacity(0.85), fontSize: 9.5),
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }

  void _showLeafAgePickerSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: const Color(0xFF0F172A),
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) {
        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 44,
                  height: 4,
                  decoration: BoxDecoration(
                    color: Colors.white24,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 14),
              const Row(
                children: [
                  Icon(Icons.layers, color: Color(0xFF00E676), size: 18),
                  SizedBox(width: 8),
                  Text(
                    'เลือกรุ่นใบ / ตำแหน่งใบ (Leaf Age Stage)',
                    style: TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              const Text(
                'สรีรวิทยาไม้ผล: ใบแต่ละรุ่นมีระดับคลอโรฟิลล์และบทบาทการสะสมธาตุอาหารต่างกัน',
                style: TextStyle(color: Colors.white54, fontSize: 11),
              ),
              const SizedBox(height: 14),
              ...LeafAgeStage.values.map((stage) {
                final bool isSelected = _selectedLeafAge == stage;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 8.0),
                  child: InkWell(
                    onTap: () {
                      setState(() => _selectedLeafAge = stage);
                      Navigator.pop(ctx);
                    },
                    borderRadius: BorderRadius.circular(12),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                      decoration: BoxDecoration(
                        color: isSelected ? const Color(0xFF00E676).withOpacity(0.15) : const Color(0xFF1E293B),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: isSelected ? const Color(0xFF00E676) : Colors.white12,
                          width: isSelected ? 1.5 : 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          Icon(
                            isSelected ? Icons.radio_button_checked : Icons.radio_button_off,
                            color: isSelected ? const Color(0xFF00E676) : Colors.white38,
                            size: 18,
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  stage.nameTh,
                                  style: TextStyle(
                                    color: isSelected ? const Color(0xFF00E676) : Colors.white,
                                    fontSize: 13,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  stage.desc,
                                  style: const TextStyle(color: Colors.white60, fontSize: 10),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              }),
              const SizedBox(height: 8),
            ],
          ),
        );
      },
    );
  }

  void _showCropStagePickerSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: const Color(0xFF0F172A),
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      isScrollControlled: true,
      builder: (ctx) {
        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 44,
                  height: 4,
                  decoration: BoxDecoration(
                    color: Colors.white24,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 14),
              const Row(
                children: [
                  Icon(Icons.park, color: Colors.cyanAccent, size: 18),
                  SizedBox(width: 8),
                  Text(
                    'เลือกระยะการพัฒนาของต้น (Phenological Stage)',
                    style: TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              const Text(
                'คำแนะนำปุ๋ยจะปรับตามระยะสรีรวิทยา (เช่น งดปุ๋ย N ช่วงสะสมอาหาร)',
                style: TextStyle(color: Colors.white54, fontSize: 11),
              ),
              const SizedBox(height: 14),
              ...TreeCropStage.values.map((stage) {
                final bool isSelected = _selectedCropStage == stage;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 8.0),
                  child: InkWell(
                    onTap: () {
                      setState(() => _selectedCropStage = stage);
                      Navigator.pop(ctx);
                    },
                    borderRadius: BorderRadius.circular(12),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                      decoration: BoxDecoration(
                        color: isSelected ? Colors.cyanAccent.withOpacity(0.15) : const Color(0xFF1E293B),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: isSelected ? Colors.cyanAccent : Colors.white12,
                          width: isSelected ? 1.5 : 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          Icon(
                            isSelected ? Icons.radio_button_checked : Icons.radio_button_off,
                            color: isSelected ? Colors.cyanAccent : Colors.white38,
                            size: 18,
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  stage.nameTh,
                                  style: TextStyle(
                                    color: isSelected ? Colors.cyanAccent : Colors.white,
                                    fontSize: 13,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  stage.desc,
                                  style: const TextStyle(color: Colors.white60, fontSize: 10),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              }),
              const SizedBox(height: 8),
            ],
          ),
        );
      },
    );
  }

  void _showTreeIdSelectorDialog() {
    final textController = TextEditingController(text: _selectedTreeId);
    final List<String> quickTreeIds = [
      'DUR-ต้นที่ 01',
      'DUR-ต้นที่ 02',
      'DUR-ต้นที่ 03',
      'DUR-ต้นที่ 04',
      'DUR-โซนA-05',
      'DUR-โซนB-12',
    ];

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          backgroundColor: const Color(0xFF0F172A),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: const BorderSide(color: Colors.white24)),
          title: const Row(
            children: [
              Icon(Icons.qr_code, color: Colors.amberAccent, size: 20),
              SizedBox(width: 8),
              Text('รหัสต้น / Tree Passport', style: TextStyle(color: Colors.white, fontSize: 16)),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'บันทึกข้อมูลภาพใบและสุขภาพลงสมุดประจำต้นรายต้น:',
                style: TextStyle(color: Colors.white70, fontSize: 11),
              ),
              const SizedBox(height: 10),
              TextField(
                controller: textController,
                style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                decoration: InputDecoration(
                  hintText: 'ระบุรหัสต้น เช่น DUR-ต้นที่ 05',
                  hintStyle: const TextStyle(color: Colors.white38),
                  filled: true,
                  fillColor: const Color(0xFF1E293B),
                  contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide.none),
                ),
              ),
              const SizedBox(height: 12),
              const Text('เลือกด่วน:', style: TextStyle(color: Colors.white54, fontSize: 10)),
              const SizedBox(height: 6),
              Wrap(
                spacing: 6,
                runSpacing: 6,
                children: quickTreeIds.map((id) {
                  return ActionChip(
                    backgroundColor: _selectedTreeId == id ? Colors.amberAccent.withOpacity(0.2) : const Color(0xFF1E293B),
                    side: BorderSide(color: _selectedTreeId == id ? Colors.amberAccent : Colors.white12),
                    label: Text(id, style: TextStyle(color: _selectedTreeId == id ? Colors.amberAccent : Colors.white, fontSize: 10)),
                    onPressed: () {
                      textController.text = id;
                    },
                  );
                }).toList(),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('ยกเลิก', style: TextStyle(color: Colors.white54)),
            ),
            ElevatedButton(
              onPressed: () {
                final trimmed = textController.text.trim();
                if (trimmed.isNotEmpty) {
                  setState(() => _selectedTreeId = trimmed);
                }
                Navigator.pop(ctx);
              },
              style: ElevatedButton.styleFrom(backgroundColor: Colors.amberAccent, foregroundColor: Colors.black),
              child: const Text('ยืนยันรหัสต้น', style: TextStyle(fontWeight: FontWeight.bold)),
            ),
          ],
        );
      },
    );
  }
}
