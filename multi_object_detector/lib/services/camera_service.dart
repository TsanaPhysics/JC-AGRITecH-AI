import 'dart:async';
import 'dart:io';
import 'package:camera/camera.dart';
import 'package:flutter/foundation.dart';

class CameraService extends ChangeNotifier {
  static final CameraService _instance = CameraService._internal();
  factory CameraService() => _instance;
  CameraService._internal();

  List<CameraDescription> _cameras = [];
  CameraController? _controller;
  int _selectedCameraIndex = 0;
  bool _isTorchOn = false;
  bool _isStreaming = false;
  bool _isInitialized = false;
  String? _errorMessage;
  Function(CameraImage image)? _streamCallback;

  // Stream controller for passing raw frame metadata
  final StreamController<CameraImage> _imageStreamController = StreamController<CameraImage>.broadcast();

  List<CameraDescription> get cameras => _cameras;
  CameraController? get controller => _controller;
  bool get isInitialized => _isInitialized && _controller != null && _controller!.value.isInitialized;
  bool get isTorchOn => _isTorchOn;
  bool get isStreaming => _isStreaming;
  String? get errorMessage => _errorMessage;
  Stream<CameraImage> get imageStream => _imageStreamController.stream;

  CameraLensDirection get currentLensDirection {
    if (_cameras.isEmpty || _selectedCameraIndex >= _cameras.length) {
      return CameraLensDirection.back;
    }
    return _cameras[_selectedCameraIndex].lensDirection;
  }

  /// Initialize available cameras and set up controller
  Future<void> initialize() async {
    try {
      _cameras = await availableCameras();
      if (_cameras.isNotEmpty) {
        // Prefer back camera initially
        final backCameraIndex = _cameras.indexWhere((c) => c.lensDirection == CameraLensDirection.back);
        _selectedCameraIndex = backCameraIndex != -1 ? backCameraIndex : 0;
        await _setupController(_cameras[_selectedCameraIndex]);
      } else {
        _errorMessage = 'ไม่พบโมดูลกล้องบนอุปกรณ์นี้ (Camera module not found)';
        _isInitialized = false;
      }
    } catch (e) {
      _errorMessage = 'การเชื่อมต่อกล้องขัดข้อง: $e';
      _isInitialized = false;
    }
    notifyListeners();
  }

  Future<void> _setupController(CameraDescription camera) async {
    await _controller?.dispose();

    // Select suitable format: iOS prefers BGRA8888, Android prefers YUV420
    final imageFormat = !kIsWeb && Platform.isIOS 
        ? ImageFormatGroup.bgra8888 
        : ImageFormatGroup.yuv420;

    _controller = CameraController(
      camera,
      ResolutionPreset.medium,
      enableAudio: false,
      imageFormatGroup: imageFormat,
    );

    try {
      await _controller!.initialize();
      _isInitialized = true;
      _errorMessage = null;
    } catch (e) {
      _errorMessage = 'ไม่สามารถเปิดใช้งานกล้องได้: $e';
      _isInitialized = false;
    }
  }

  /// Switch between back and front cameras
  Future<void> switchCamera() async {
    if (_cameras.length <= 1) return;

    final wasStreaming = _isStreaming;
    final callback = _streamCallback;

    if (wasStreaming) {
      await stopImageStream();
    }

    _selectedCameraIndex = (_selectedCameraIndex + 1) % _cameras.length;
    await _setupController(_cameras[_selectedCameraIndex]);

    if (wasStreaming && callback != null && _controller != null && _controller!.value.isInitialized) {
      await startImageStream(callback);
    }

    notifyListeners();
  }

  /// Toggle torch / flashlight
  Future<void> toggleTorch() async {
    if (_controller == null || !_controller!.value.isInitialized) return;

    try {
      if (_isTorchOn) {
        await _controller!.setFlashMode(FlashMode.off);
        _isTorchOn = false;
      } else {
        await _controller!.setFlashMode(FlashMode.torch);
        _isTorchOn = true;
      }
      notifyListeners();
    } catch (e) {
      debugPrint('[!] Failed to toggle torch: $e');
    }
  }

  /// Capture still photo for high-accuracy object detection
  Future<XFile?> takePicture() async {
    if (_controller == null || !_controller!.value.isInitialized) return null;

    try {
      final wasStreaming = _isStreaming;
      final callback = _streamCallback;

      if (wasStreaming) {
        await _controller!.stopImageStream();
        _isStreaming = false;
      }

      final file = await _controller!.takePicture();

      if (wasStreaming && callback != null) {
        await _controller!.startImageStream((CameraImage image) {
          _imageStreamController.add(image);
          callback(image);
        });
        _isStreaming = true;
      }

      return file;
    } catch (e) {
      debugPrint('[!] Failed to capture photo: $e');
      return null;
    }
  }

  /// Start streaming camera frames to detector
  Future<void> startImageStream(Function(CameraImage image) onImage) async {
    if (_controller == null || !_controller!.value.isInitialized || _isStreaming) return;

    _streamCallback = onImage;
    try {
      await _controller!.startImageStream((CameraImage image) {
        _imageStreamController.add(image);
        onImage(image);
      });
      _isStreaming = true;
      notifyListeners();
    } catch (e) {
      debugPrint('[!] Failed to start image stream: $e');
    }
  }

  /// Stop streaming camera frames
  Future<void> stopImageStream() async {
    if (_controller == null || !_controller!.value.isInitialized || !_isStreaming) return;

    try {
      await _controller!.stopImageStream();
      _isStreaming = false;
      notifyListeners();
    } catch (e) {
      debugPrint('[!] Failed to stop image stream: $e');
    }
  }

  @override
  void dispose() {
    _imageStreamController.close();
    _controller?.dispose();
    super.dispose();
  }
}
