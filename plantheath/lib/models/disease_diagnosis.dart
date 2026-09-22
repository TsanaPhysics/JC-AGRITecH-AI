class DiseaseDiagnosis {
  final String id;
  final String nameTh;
  final String nameEn;
  final String scientificName;
  final String pathogenType;
  final String severityLevel;
  final double confidence;
  final String description;
  final String treatment;
  final double lesionAreaPercentage;

  const DiseaseDiagnosis({
    required this.id,
    required this.nameTh,
    required this.nameEn,
    required this.scientificName,
    required this.pathogenType,
    required this.severityLevel,
    required this.confidence,
    required this.description,
    required this.treatment,
    this.lesionAreaPercentage = 0.0,
  });

  factory DiseaseDiagnosis.empty() {
    return const DiseaseDiagnosis(
      id: 'healthy',
      nameTh: 'ใบปกติสมบูรณ์',
      nameEn: 'Healthy Leaf',
      scientificName: 'Normal Foliage',
      pathogenType: 'None',
      severityLevel: 'None',
      confidence: 1.0,
      description: 'ใบปกติ ไม่พบอาการโรคพืชหรือศัตรูพืชทำลาย',
      treatment: 'รักษาการให้น้ำและธาตุอาหารตามมาตรฐานสวนทุเรียน',
      lesionAreaPercentage: 0.0,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name_th': nameTh,
    'name_en': nameEn,
    'scientific_name': scientificName,
    'pathogen_type': pathogenType,
    'severity_level': severityLevel,
    'confidence': confidence,
    'description': description,
    'treatment': treatment,
    'lesion_area_percentage': lesionAreaPercentage,
  };

  factory DiseaseDiagnosis.fromJson(Map<String, dynamic> json) {
    return DiseaseDiagnosis(
      id: json['id'] ?? 'healthy',
      nameTh: json['name_th'] ?? 'ใบปกติสมบูรณ์',
      nameEn: json['name_en'] ?? 'Healthy Leaf',
      scientificName: json['scientific_name'] ?? 'Normal Foliage',
      pathogenType: json['pathogen_type'] ?? 'None',
      severityLevel: json['severity_level'] ?? 'None',
      confidence: (json['confidence'] as num?)?.toDouble() ?? 1.0,
      description: json['description'] ?? '',
      treatment: json['treatment'] ?? '',
      lesionAreaPercentage: (json['lesion_area_percentage'] as num?)?.toDouble() ?? 0.0,
    );
  }
}
