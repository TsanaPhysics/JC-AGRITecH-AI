class DetectionStatistics {
  final double fps;
  final int inferenceTimeMs;
  final int totalObjects;
  final List<String> detectedClasses;
  final DateTime timestamp;

  const DetectionStatistics({
    this.fps = 0.0,
    this.inferenceTimeMs = 0,
    this.totalObjects = 0,
    this.detectedClasses = const [],
    required this.timestamp,
  });

  DetectionStatistics copyWith({
    double? fps,
    int? inferenceTimeMs,
    int? totalObjects,
    List<String>? detectedClasses,
    DateTime? timestamp,
  }) {
    return DetectionStatistics(
      fps: fps ?? this.fps,
      inferenceTimeMs: inferenceTimeMs ?? this.inferenceTimeMs,
      totalObjects: totalObjects ?? this.totalObjects,
      detectedClasses: detectedClasses ?? this.detectedClasses,
      timestamp: timestamp ?? this.timestamp,
    );
  }
}
