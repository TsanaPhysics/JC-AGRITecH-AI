class UserFeedbackSample {
  final String id;
  final String imagePath;
  final String trueDiseaseId;
  final String predictedDiseaseId;
  final double confidence;
  final List<double> featureEmbedding; // 128-d latent vector
  final DateTime timestamp;
  final double? latitude;
  final double? longitude;
  final String notes;

  const UserFeedbackSample({
    required this.id,
    required this.imagePath,
    required this.trueDiseaseId,
    required this.predictedDiseaseId,
    required this.confidence,
    required this.featureEmbedding,
    required this.timestamp,
    this.latitude,
    this.longitude,
    this.notes = '',
  });

  Map<String, dynamic> toJson() => {
    'id': id,
    'image_path': imagePath,
    'true_disease_id': trueDiseaseId,
    'predicted_disease_id': predictedDiseaseId,
    'confidence': confidence,
    'feature_embedding': featureEmbedding,
    'timestamp': timestamp.toIso8601String(),
    'latitude': latitude,
    'longitude': longitude,
    'notes': notes,
  };

  factory UserFeedbackSample.fromJson(Map<String, dynamic> json) => UserFeedbackSample(
    id: json['id'] as String,
    imagePath: json['image_path'] as String,
    trueDiseaseId: json['true_disease_id'] as String,
    predictedDiseaseId: json['predicted_disease_id'] as String,
    confidence: (json['confidence'] as num).toDouble(),
    featureEmbedding: (json['feature_embedding'] as List).map((e) => (e as num).toDouble()).toList(),
    timestamp: DateTime.parse(json['timestamp'] as String),
    latitude: json['latitude'] != null ? (json['latitude'] as num).toDouble() : null,
    longitude: json['longitude'] != null ? (json['longitude'] as num).toDouble() : null,
    notes: json['notes'] as String? ?? '',
  );
}
