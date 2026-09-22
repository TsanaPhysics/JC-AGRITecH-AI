class NutrientHealthMetric {
  final double spadChlorophyll;
  final double nitrogenPct;
  final double phosphorusPct;
  final double potassiumPct;
  final double magnesiumPct;
  final double calciumPct;
  final double ironPpm;
  final double zincPpm;
  final double boronPpm;

  // Optical Vegetation Indices
  final double dgci; // Dark Green Color Index
  final double vari; // Visible Atmospherically Resistant Index
  final double gli;  // Green Leaf Index
  final double exg;  // Excess Green Index
  
  // Color Space Metrics
  final List<int> rgb;
  final List<double> hsv;
  final List<double> lab;

  final String nitrogenStatus;
  final String phosphorusStatus;
  final String potassiumStatus;
  final String magnesiumStatus;
  final String micronutrientAlert;
  final String fertilizerRecommendation;

  const NutrientHealthMetric({
    required this.spadChlorophyll,
    required this.nitrogenPct,
    required this.phosphorusPct,
    required this.potassiumPct,
    required this.magnesiumPct,
    required this.calciumPct,
    required this.ironPpm,
    required this.zincPpm,
    required this.boronPpm,
    required this.dgci,
    required this.vari,
    required this.gli,
    required this.exg,
    required this.rgb,
    required this.hsv,
    required this.lab,
    required this.nitrogenStatus,
    required this.phosphorusStatus,
    required this.potassiumStatus,
    required this.magnesiumStatus,
    required this.micronutrientAlert,
    required this.fertilizerRecommendation,
  });

  factory NutrientHealthMetric.defaultHealthy() {
    return const NutrientHealthMetric(
      spadChlorophyll: 48.5,
      nitrogenPct: 2.5,
      phosphorusPct: 0.18,
      potassiumPct: 1.85,
      magnesiumPct: 0.42,
      calciumPct: 2.15,
      ironPpm: 110.0,
      zincPpm: 38.0,
      boronPpm: 45.0,
      dgci: 0.62,
      vari: 0.35,
      gli: 0.22,
      exg: 42.0,
      rgb: [46, 125, 50],
      hsv: [123.0, 0.63, 0.49],
      lab: [46.8, -38.5, 32.1],
      nitrogenStatus: 'เหมาะสม (2.5%)',
      phosphorusStatus: 'เหมาะสม (0.18%)',
      potassiumStatus: 'เหมาะสม (1.85%)',
      magnesiumStatus: 'เหมาะสม (0.42%)',
      micronutrientAlert: 'ธาตุรองและจุลธาตุครบถ้วนสมบูรณ์',
      fertilizerRecommendation: 'รักษาการใส่ปุ๋ยบำรุงทางดินสูตร 16-16-16 หรือ 15-15-15 ตามระยะทรงพุ่ม',
    );
  }

  factory NutrientHealthMetric.noLeaf() {
    return const NutrientHealthMetric(
      spadChlorophyll: 0.0,
      nitrogenPct: 0.0,
      phosphorusPct: 0.0,
      potassiumPct: 0.0,
      magnesiumPct: 0.0,
      calciumPct: 0.0,
      ironPpm: 0.0,
      zincPpm: 0.0,
      boronPpm: 0.0,
      dgci: 0.0,
      vari: 0.0,
      gli: 0.0,
      exg: 0.0,
      rgb: [0, 0, 0],
      hsv: [0.0, 0.0, 0.0],
      lab: [0.0, 0.0, 0.0],
      nitrogenStatus: 'รอส่องใบพืช',
      phosphorusStatus: 'รอส่องใบพืช',
      potassiumStatus: 'รอส่องใบพืช',
      magnesiumStatus: 'รอส่องใบพืช',
      micronutrientAlert: 'ไม่พบใบพืชในกรอบ ROI',
      fertilizerRecommendation: 'กรุณานำกรอบ ROI ไปทาบลงบนใบพืชทุเรียนเพื่อประเมินระดับธาตุอาหารและคลอโรฟิลล์',
    );
  }

  Map<String, dynamic> toJson() => {
    'spad_chlorophyll': spadChlorophyll,
    'nitrogen_pct': nitrogenPct,
    'phosphorus_pct': phosphorusPct,
    'potassium_pct': potassiumPct,
    'magnesium_pct': magnesiumPct,
    'calcium_pct': calciumPct,
    'iron_ppm': ironPpm,
    'zinc_ppm': zincPpm,
    'boron_ppm': boronPpm,
    'dgci': dgci,
    'vari': vari,
    'gli': gli,
    'exg': exg,
    'rgb': rgb,
    'hsv': hsv,
    'lab': lab,
    'nitrogen_status': nitrogenStatus,
    'phosphorus_status': phosphorusStatus,
    'potassium_status': potassiumStatus,
    'magnesium_status': magnesiumStatus,
    'micronutrient_alert': micronutrientAlert,
    'fertilizer_recommendation': fertilizerRecommendation,
  };

  factory NutrientHealthMetric.fromJson(Map<String, dynamic> json) {
    return NutrientHealthMetric(
      spadChlorophyll: (json['spad_chlorophyll'] as num?)?.toDouble() ?? 48.5,
      nitrogenPct: (json['nitrogen_pct'] as num?)?.toDouble() ?? 2.5,
      phosphorusPct: (json['phosphorus_pct'] as num?)?.toDouble() ?? 0.18,
      potassiumPct: (json['potassium_pct'] as num?)?.toDouble() ?? 1.85,
      magnesiumPct: (json['magnesium_pct'] as num?)?.toDouble() ?? 0.42,
      calciumPct: (json['calcium_pct'] as num?)?.toDouble() ?? 2.15,
      ironPpm: (json['iron_ppm'] as num?)?.toDouble() ?? 110.0,
      zincPpm: (json['zinc_ppm'] as num?)?.toDouble() ?? 38.0,
      boronPpm: (json['boron_ppm'] as num?)?.toDouble() ?? 45.0,
      dgci: (json['dgci'] as num?)?.toDouble() ?? 0.62,
      vari: (json['vari'] as num?)?.toDouble() ?? 0.35,
      gli: (json['gli'] as num?)?.toDouble() ?? 0.22,
      exg: (json['exg'] as num?)?.toDouble() ?? 42.0,
      rgb: (json['rgb'] as List<dynamic>?)?.map((e) => (e as num).toInt()).toList() ?? [46, 125, 50],
      hsv: (json['hsv'] as List<dynamic>?)?.map((e) => (e as num).toDouble()).toList() ?? [123.0, 0.63, 0.49],
      lab: (json['lab'] as List<dynamic>?)?.map((e) => (e as num).toDouble()).toList() ?? [46.8, -38.5, 32.1],
      nitrogenStatus: json['nitrogen_status'] ?? 'เหมาะสม',
      phosphorusStatus: json['phosphorus_status'] ?? 'เหมาะสม',
      potassiumStatus: json['potassium_status'] ?? 'เหมาะสม',
      magnesiumStatus: json['magnesium_status'] ?? 'เหมาะสม',
      micronutrientAlert: json['micronutrient_alert'] ?? '',
      fertilizerRecommendation: json['fertilizer_recommendation'] ?? '',
    );
  }
}
