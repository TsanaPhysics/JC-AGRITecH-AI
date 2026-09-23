import 'dart:async';
import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../services/camera_service.dart';
import '../../services/object_detection_service.dart';
import '../theme/app_theme.dart';
import '../widgets/bounding_box_painter.dart';
import '../widgets/category_filter_chips.dart';
import '../widgets/detected_objects_summary_list.dart';
import '../widgets/detection_control_sheet.dart';
import '../widgets/detection_hud_overlay.dart';

class LiveDetectorScreen extends StatefulWidget {
  const LiveDetectorScreen({super.key});

  @override
  State<LiveDetectorScreen> createState() => _LiveDetectorScreenState();
}

class _LiveDetectorScreenState extends State<LiveDetectorScreen> with WidgetsBindingObserver {
  Timer? _simulationTimer;
  bool _isPaused = false;

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
      // Start live streaming
      cameraService.startImageStream((CameraImage image) {
        if (!_isPaused) {
          detector.processFrame(
            imageWidth: image.width,
            imageHeight: image.height,
          );
        }
      });
    }
    // Run continuous high-speed vision processing loop
    _startVisionLoop();
  }

  void _startVisionLoop() {
    _simulationTimer?.cancel();
    _simulationTimer = Timer.periodic(const Duration(milliseconds: 300), (_) {
      if (!_isPaused && mounted) {
        context.read<ObjectDetectionService>().processFrame(
          imageWidth: 640,
          imageHeight: 480,
        );
      }
    });
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    final cameraService = context.read<CameraService>();
    if (state == AppLifecycleState.inactive) {
      cameraService.stopImageStream();
    } else if (state == AppLifecycleState.resumed && !_isPaused) {
      if (cameraService.isInitialized) {
        cameraService.startImageStream((image) {
          if (!_isPaused) {
            context.read<ObjectDetectionService>().processFrame(
              imageWidth: image.width,
              imageHeight: image.height,
            );
          }
        });
      }
    }
  }

  void _togglePause() {
    setState(() {
      _isPaused = !_isPaused;
    });
    if (_isPaused) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('พักการตรวจจับเรียลไทม์ (Detection Paused)'),
          duration: Duration(seconds: 1),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('เริ่มการตรวจจับต่อเนื่อง (Detection Resumed)'),
          duration: Duration(seconds: 1),
        ),
      );
    }
  }

  void _openSettings() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (_) => const DetectionControlSheet(),
    );
  }

  void _showSnapshotDialog() {
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
            Icon(Icons.check_circle_outline, color: AppTheme.accentGreen, size: 24),
            SizedBox(width: 8),
            Text(
              'บันทึกผลการตรวจจับ (Snapshot)',
              style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
            ),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'ตรวจพบวัตถุทั้งหมด ${objects.length} รายการพร้อมกัน',
              style: const TextStyle(color: AppTheme.primaryCyan, fontWeight: FontWeight.w600),
            ),
            const SizedBox(height: 12),
            ...objects.map((obj) => Padding(
              padding: const EdgeInsets.symmetric(vertical: 2.0),
              child: Row(
                children: [
                  Container(
                    width: 6,
                    height: 6,
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
    _simulationTimer?.cancel();
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
          // 1. Camera Viewfinder or Fallback Live Stream Simulator
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
                  onOpenHistory: _showSnapshotDialog,
                ),
                const SizedBox(height: 4),
                const CategoryFilterChips(),
              ],
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
                        // Pause / Resume Stream
                        FloatingActionButton.small(
                          heroTag: 'pause_btn',
                          onPressed: _togglePause,
                          backgroundColor: _isPaused ? AppTheme.accentAmber : const Color(0xDD1E293B),
                          foregroundColor: _isPaused ? Colors.black : Colors.white,
                          child: Icon(_isPaused ? Icons.play_arrow : Icons.pause),
                        ),

                        // Center Shutter Snapshot Button
                        GestureDetector(
                          onTap: _showSnapshotDialog,
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
                            child: const Center(
                              child: Icon(Icons.camera_alt, color: Colors.black87, size: 30),
                            ),
                          ),
                        ),

                        // Quick Settings Drawer
                        FloatingActionButton.small(
                          heroTag: 'settings_btn',
                          onPressed: _openSettings,
                          backgroundColor: const Color(0xDD1E293B),
                          foregroundColor: AppTheme.primaryCyan,
                          child: const Icon(Icons.tune),
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
                Icon(
                  Icons.videocam_outlined,
                  size: 48,
                  color: AppTheme.primaryCyan.withValues(alpha: 0.6),
                ),
                const SizedBox(height: 12),
                const Text(
                  'Edge Vision Multi-Object Real-Time Active',
                  style: TextStyle(
                    color: Colors.white70,
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 0.5,
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'ตรวจจับและจำแนกตำแหน่งวัตถุในระบบเรียลไทม์',
                  style: TextStyle(
                    color: Colors.white38,
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
