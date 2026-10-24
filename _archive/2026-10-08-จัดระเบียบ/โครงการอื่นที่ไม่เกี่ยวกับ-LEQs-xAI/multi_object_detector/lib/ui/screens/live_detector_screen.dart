import 'dart:async';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../services/camera_service.dart';
import '../../services/object_detection_service.dart';
import '../theme/app_theme.dart';
import '../widgets/bounding_box_painter.dart';
import '../widgets/category_filter_chips.dart';
import '../widgets/detected_objects_summary_list.dart';
import '../widgets/detection_control_sheet.dart';
import '../widgets/detection_hud_overlay.dart';
import '../widgets/image_analysis_modal.dart';

class LiveDetectorScreen extends StatefulWidget {
  const LiveDetectorScreen({super.key});

  @override
  State<LiveDetectorScreen> createState() => _LiveDetectorScreenState();
}

class _LiveDetectorScreenState extends State<LiveDetectorScreen> with WidgetsBindingObserver {
  bool _isPaused = false;
  bool _isAnalyzingSnapshot = false;
  final ImagePicker _imagePicker = ImagePicker();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);

    WidgetsBinding.instance.addPostFrameCallback((_) {
      _initDetector();
    });
  }

  Future<void> _initDetector() async {
    final cameraService = context.read<CameraService>();
    final detector = context.read<ObjectDetectionService>();

    await detector.initialize();
    await cameraService.initialize();

    if (cameraService.isInitialized) {
      cameraService.startImageStream((CameraImage image) {
        if (!_isPaused && mounted) {
          detector.processCameraImage(image);
        }
      });
    }
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    final cameraService = context.read<CameraService>();
    if (state == AppLifecycleState.inactive ||
        state == AppLifecycleState.paused ||
        state == AppLifecycleState.detached) {
      cameraService.stopImageStream();
    } else if (state == AppLifecycleState.resumed && !_isPaused) {
      if (cameraService.isInitialized) {
        cameraService.startImageStream((image) {
          if (!_isPaused && mounted) {
            context.read<ObjectDetectionService>().processCameraImage(image);
          }
        });
      }
    }
  }

  void _togglePause() {
    setState(() {
      _isPaused = !_isPaused;
    });
    final snackText = _isPaused
        ? 'พักการตรวจจับเรียลไทม์ (Live Detection Paused)'
        : 'เริ่มการตรวจจับต่อเนื่อง (Live Detection Resumed)';
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(snackText),
        duration: const Duration(milliseconds: 900),
        backgroundColor: AppTheme.surfaceElevated,
      ),
    );
  }

  void _openSettings() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (_) => const DetectionControlSheet(),
    );
  }

  /// Capture still snapshot from camera or take picture
  Future<void> _captureAndAnalyze() async {
    final cameraService = context.read<CameraService>();
    final detector = context.read<ObjectDetectionService>();

    setState(() => _isAnalyzingSnapshot = true);

    try {
      if (cameraService.isInitialized) {
        final photo = await cameraService.takePicture();
        if (photo != null) {
          final bytes = await photo.readAsBytes();
          final results = await detector.processImageBytes(bytes);
          if (mounted) {
            showDialog(
              context: context,
              builder: (_) => ImageAnalysisModal(
                imageBytes: bytes,
                objects: results,
                inferenceTimeMs: detector.lastInferenceTimeMs,
              ),
            );
          }
        }
      } else {
        // Fallback: Pick or take picture with system picker
        final photo = await _imagePicker.pickImage(
          source: ImageSource.camera,
          maxWidth: 1280,
          maxHeight: 1280,
        );
        if (photo != null) {
          final bytes = await photo.readAsBytes();
          final results = await detector.processImageBytes(bytes);
          if (mounted) {
            showDialog(
              context: context,
              builder: (_) => ImageAnalysisModal(
                imageBytes: bytes,
                objects: results,
                inferenceTimeMs: detector.lastInferenceTimeMs,
              ),
            );
          }
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('ข้อผิดพลาดการถ่ายภาพ: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isAnalyzingSnapshot = false);
    }
  }

  /// Pick image from gallery and run detection
  Future<void> _pickImageFromGallery() async {
    final detector = context.read<ObjectDetectionService>();
    setState(() => _isAnalyzingSnapshot = true);

    try {
      final photo = await _imagePicker.pickImage(
        source: ImageSource.gallery,
        maxWidth: 1280,
        maxHeight: 1280,
      );

      if (photo != null) {
        final bytes = await photo.readAsBytes();
        final results = await detector.processImageBytes(bytes);
        if (mounted) {
          showDialog(
            context: context,
            builder: (_) => ImageAnalysisModal(
              imageBytes: bytes,
              objects: results,
              inferenceTimeMs: detector.lastInferenceTimeMs,
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('ไม่สามารถเปิดคลังภาพได้: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isAnalyzingSnapshot = false);
    }
  }

  /// Test analysis on sample scene
  Future<void> _testSampleScene(String sceneName) async {
    final detector = context.read<ObjectDetectionService>();
    setState(() => _isAnalyzingSnapshot = true);

    try {
      // Use master icon as high-contrast test target
      final byteData = await rootBundle.load('assets/icons/app_icon.png');
      final bytes = byteData.buffer.asUint8List();
      final results = await detector.processImageBytes(bytes);

      if (mounted) {
        showDialog(
          context: context,
          builder: (_) => ImageAnalysisModal(
            imageBytes: bytes,
            objects: results,
            inferenceTimeMs: detector.lastInferenceTimeMs,
          ),
        );
      }
    } catch (e) {
      debugPrint('[!] Sample scene error: $e');
    } finally {
      if (mounted) setState(() => _isAnalyzingSnapshot = false);
    }
  }

  void _showDetectionHistoryDialog() {
    final detector = context.read<ObjectDetectionService>();
    final objects = detector.currentDetections;

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppTheme.surfaceElevated,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: AppTheme.primaryCyan, width: 1.2),
        ),
        title: const Row(
          children: [
            Icon(Icons.view_in_ar, color: AppTheme.accentGreen, size: 24),
            SizedBox(width: 8),
            Text(
              'รายการวัตถุที่กำลังตรวจจับ',
              style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
            ),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'ตรวจพบขณะนี้ทั้งหมด ${objects.length} รายการ',
              style: const TextStyle(color: AppTheme.primaryCyan, fontWeight: FontWeight.w600),
            ),
            const SizedBox(height: 12),
            if (objects.isEmpty)
              const Padding(
                padding: EdgeInsets.symmetric(vertical: 8.0),
                child: Text('ยังไม่พบวัตถุ เล็งกล้องไปที่วัตถุรอบตัว', style: TextStyle(color: Colors.white54)),
              )
            else
              ...objects.map((obj) => Padding(
                padding: const EdgeInsets.symmetric(vertical: 4.0),
                child: Row(
                  children: [
                    Container(
                      width: 8,
                      height: 8,
                      decoration: BoxDecoration(color: obj.color, shape: BoxShape.circle),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        '${obj.labelTh} (${obj.labelEn})',
                        style: const TextStyle(color: Colors.white, fontSize: 13),
                      ),
                    ),
                    Text(
                      obj.confidencePercent,
                      style: TextStyle(color: obj.confidenceColor, fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                  ],
                ),
              )),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('ปิด (Close)', style: TextStyle(color: AppTheme.primaryCyan)),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    CameraService().stopImageStream();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final camera = context.watch<CameraService>();
    final detector = context.watch<ObjectDetectionService>();
    final screenSize = MediaQuery.of(context).size;

    return Scaffold(
      backgroundColor: AppTheme.background,
      body: Stack(
        fit: StackFit.expand,
        children: [
          // 1. Camera Viewfinder or Fallback Live Vision Background
          _buildCameraView(camera, screenSize),

          // 2. Real-Time Multi-Object Bounding Boxes Overlay
          CustomPaint(
            size: screenSize,
            painter: BoundingBoxPainter(
              objects: detector.currentDetections,
              previewSize: camera.isInitialized
                  ? camera.controller!.value.previewSize ?? screenSize
                  : screenSize,
              screenSize: screenSize,
            ),
          ),

          // 3. Top Cyber HUD Overlay & Category Filter Chips
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                DetectionHudOverlay(
                  onOpenSettings: _openSettings,
                  onOpenHistory: _showDetectionHistoryDialog,
                ),
                const SizedBox(height: 4),
                const CategoryFilterChips(),
              ],
            ),
          ),

          // 4. Sample Test Scenes Quick Bar (When camera is offline / on simulator)
          if (!camera.isInitialized)
            Positioned(
              top: 155,
              left: 16,
              right: 16,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: const Color(0xCC0F172A),
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: AppTheme.primaryCyan.withValues(alpha: 0.5)),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.science_outlined, color: AppTheme.primaryCyan, size: 18),
                    const SizedBox(width: 8),
                    const Expanded(
                      child: Text(
                        'ทดสอบตรวจจับวัตถุด้วยภาพตัวอย่าง:',
                        style: TextStyle(color: Colors.white70, fontSize: 12),
                      ),
                    ),
                    ElevatedButton(
                      onPressed: () => _testSampleScene('Test'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryCyan,
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      child: const Text(
                        'วิเคราะห์ภาพทันที',
                        style: TextStyle(color: Colors.black87, fontSize: 11, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ],
                ),
              ),
            ),

          // 5. Bottom Detection Summary and Action Buttons
          Positioned(
            bottom: 24,
            left: 0,
            right: 0,
            child: SafeArea(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Summary of objects detected
                  DetectedObjectsSummaryList(objects: detector.currentDetections),

                  const SizedBox(height: 14),

                  // Bottom Action Floating Bar
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24.0),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        // Gallery Image Picker Button
                        FloatingActionButton.small(
                          heroTag: 'gallery_btn',
                          onPressed: _pickImageFromGallery,
                          backgroundColor: const Color(0xDD1E293B),
                          foregroundColor: AppTheme.primaryCyan,
                          child: const Icon(Icons.photo_library),
                        ),

                        // Center Shutter Snapshot Button
                        GestureDetector(
                          onTap: _isAnalyzingSnapshot ? null : _captureAndAnalyze,
                          child: Container(
                            width: 68,
                            height: 68,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              gradient: const LinearGradient(
                                colors: [AppTheme.primaryCyan, AppTheme.accentGreen],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ),
                              boxShadow: [
                                BoxShadow(
                                  color: AppTheme.primaryCyan.withValues(alpha: 0.4),
                                  blurRadius: 16,
                                  spreadRadius: 2,
                                ),
                              ],
                              border: Border.all(color: Colors.white, width: 3.5),
                            ),
                            child: Center(
                              child: _isAnalyzingSnapshot
                                  ? const SizedBox(
                                      width: 26,
                                      height: 26,
                                      child: CircularProgressIndicator(
                                        color: Colors.black87,
                                        strokeWidth: 3,
                                      ),
                                    )
                                  : const Icon(Icons.camera_alt, color: Colors.black87, size: 30),
                            ),
                          ),
                        ),

                        // Pause / Resume Stream
                        FloatingActionButton.small(
                          heroTag: 'pause_btn',
                          onPressed: _togglePause,
                          backgroundColor: _isPaused ? AppTheme.accentAmber : const Color(0xDD1E293B),
                          foregroundColor: _isPaused ? Colors.black : Colors.white,
                          child: Icon(_isPaused ? Icons.play_arrow : Icons.pause),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCameraView(CameraService camera, Size screenSize) {
    if (camera.isInitialized && camera.controller != null) {
      return SizedBox.expand(
        child: FittedBox(
          fit: BoxFit.cover,
          child: SizedBox(
            width: camera.controller!.value.previewSize?.height ?? screenSize.width,
            height: camera.controller!.value.previewSize?.width ?? screenSize.height,
            child: CameraPreview(camera.controller!),
          ),
        ),
      );
    }

    // Fallback Mock Live Vision Background with Grid
    return Container(
      decoration: const BoxDecoration(
        gradient: RadialGradient(
          center: Alignment.center,
          radius: 1.2,
          colors: [
            Color(0xFF131B2E),
            Color(0xFF070B14),
          ],
        ),
      ),
      child: Stack(
        children: [
          // Cyber Grid Pattern
          Positioned.fill(
            child: CustomPaint(
              painter: _CyberGridPainter(),
            ),
          ),
          Center(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: Image.asset(
                    'assets/icons/app_icon.png',
                    width: 72,
                    height: 72,
                    errorBuilder: (_, __, ___) => Icon(
                      Icons.videocam_outlined,
                      size: 48,
                      color: AppTheme.primaryCyan.withValues(alpha: 0.6),
                    ),
                  ),
                ),
                const SizedBox(height: 14),
                const Text(
                  'Real-Time Multi-Object AI Detector Active',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 0.5,
                  ),
                ),
                const SizedBox(height: 6),
                const Text(
                  'พร้อมตรวจวิเคราะห์กล้องสดและภาพถ่าย (TFLite MobileNet)',
                  style: TextStyle(
                    color: Colors.white60,
                    fontSize: 12,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _CyberGridPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0x1100E5FF)
      ..strokeWidth = 1.0;

    const double step = 40.0;
    for (double x = 0; x < size.width; x += step) {
      canvas.drawLine(Offset(x, 0), Offset(x, size.height), paint);
    }
    for (double y = 0; y < size.height; y += step) {
      canvas.drawLine(Offset(0, y), Offset(size.width, y), paint);
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
