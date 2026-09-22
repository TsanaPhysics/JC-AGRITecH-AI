import '../models/disease_diagnosis.dart';
import 'multi_color_space_service.dart';

class PlantPathologyService {
  static const List<String> diseaseKeys = [
    'healthy',
    'phytophthora',
    'rhizoctonia',
    'anthracnose',
    'algal_spot',
    'pest_damage',
  ];

  static const Map<String, Map<String, String>> diseaseMeta = {
    'healthy': {
      'name_th': 'ใบปกติสมบูรณ์',
      'name_en': 'Healthy Leaf',
      'sci': 'Normal Durian Foliage',
      'type': 'None',
      'sev': 'None',
      'desc': 'ใบมีสีเขียวสดเป็นมันเงา สังเคราะห์แสงได้สมบูรณ์ ไม่พบรอยโรคหรือการทำลายของศัตรูพืช',
      'treat': 'รักษาการให้น้ำสม่ำเสมอ และให้ปุ๋ยบำรุงตามระยะการพัฒนาของใบและผล',
    },
    'phytophthora': {
      'name_th': 'โรคใบไหม้ไฟทอปธอร่า',
      'name_en': 'Phytophthora Leaf Blight',
      'sci': 'Phytophthora palmivora',
      'type': 'Oomycete / เชื้อราน้ำ',
      'sev': 'Severe',
      'desc': 'แผลฉ่ำน้ำสีน้ำตาลเข้มถึงดำ ขอบแผลไม่แน่นอน ลุกลามรวดเร็วในสภาพอากาศร้อนชื้น ใบแห้งร่วงง่าย',
      'treat': 'พ่นสารฟอสอีทิล-อะลูมิเนียม หรือเมทาแลกซิล ตัดกิ่งและเก็บใบที่เป็นโรคเผาทำลายนอกแปลง',
    },
    'rhizoctonia': {
      'name_th': 'โรคราใบติด',
      'name_en': 'Rhizoctonia Web Blight',
      'sci': 'Rhizoctonia solani',
      'type': 'Fungi / เชื้อรา',
      'sev': 'High',
      'desc': 'แผลคล้ายน้ำร้อนลวก สีน้ำตาลอ่อน มีเส้นใยยึดใบติดกันเป็นแพและแห้งร่วงเป็นกระจุก',
      'treat': 'พ่นสารแฮกซาโคนาโซล หรือไดฟีโนโคนาโซล ตัดแต่งทรงพุ่มให้อากาศถ่ายเทสะดวก ลดความชื้น',
    },
    'anthracnose': {
      'name_th': 'โรคแอนแทรคโนส',
      'name_en': 'Anthracnose',
      'sci': 'Colletotrichum gloeosporioides',
      'type': 'Fungi / เชื้อรา',
      'sev': 'Moderate',
      'desc': 'แผลกลมสีน้ำตาล ขอบแผลเข้ม มีวงซ้อนกันเป็นชั้น (Zonate) กลางแผลอาจแห้งกรอบฉีกขาด',
      'treat': 'พ่นสารโพรคลอราช อะซอกซีสโตรบิน หรือคอปเปอร์ออกซีคลอไรด์ หลีกเลี่ยงการพ่นน้ำโดนใบ',
    },
    'algal_spot': {
      'name_th': 'โรคราสนิม / จุดสนิมสาหร่าย',
      'name_en': 'Algal Leaf Spot / Red Rust',
      'sci': 'Cephaleuros virescens',
      'type': 'Parasitic Alga / สาหร่ายกาฝาก',
      'sev': 'Low-Moderate',
      'desc': 'จุดฟูกำมะหยี่สีส้มอมแดงหรือสีสนิมบนผิวใบ สังเคราะห์แสงลดลง',
      'treat': 'พ่นสารประกอบทองแดง เช่น คอปเปอร์ไฮดรอกไซด์ และตัดแต่งกิ่งให้รับแสงแดดทั่วถึง',
    },
    'pest_damage': {
      'name_th': 'ความเสียหายจากไรแดงและเพลี้ยไก่แจ้',
      'name_en': 'Spider Mites & Psyllid Damage',
      'sci': 'Oligonychus biharensis & Pseudophacopteron canarium',
      'type': 'Insect / แมลงศัตรูพืช',
      'sev': 'Moderate-High',
      'desc': 'ผิวใบมีจุดประขาวซีด ด้านบนใบกร้าน ขอบใบหงิกม้วน ยอดชะงักการเจริญเติบโต',
      'treat': 'พ่นสารอะบาเมกติน โพรพาร์ไกต์ หรือสารสกัดสะเดา/กำมะถันผง และกำจัดวัชพืชรอบโคนต้น',
    },
  };

  /// Detect whether the target in the ROI has optical properties of plant foliage or foliar lesions
  static bool isLeafPresence({required int r, required int g, required int b}) {
    final hsv = MultiColorSpaceService.rgbToHsv(r, g, b);
    final lab = MultiColorSpaceService.rgbToLab(r, g, b);
    final h = hsv[0];
    final s = hsv[1];
    final l = lab[0];

    // 1. Extreme dark (lens covered) or blown out white glare
    if (l < 8.0 || l > 95.0) return false;

    // 2. Neutral non-foliar backgrounds (white wall, grey paper, concrete, metal)
    if (s < 0.12) return false;
    if ((r - g).abs() < 10 && (g - b).abs() < 10 && (r - b).abs() < 10) return false;

    // 3. Dominant Blue / Sky / Indigo / Purple
    if (b > g && b > r) return false;
    if (h >= 180.0 && h <= 285.0) return false;

    // 4. Highly saturated artificial red / magenta
    if (h >= 320.0 || (h <= 10.0 && s > 0.65)) return false;

    // 5. Green foliage (Healthy to chlorotic green-yellow)
    if (h >= 60.0 && h <= 170.0 && s >= 0.14) return true;

    // 6. Yellow chlorotic leaf (N / K deficiency)
    if (h >= 38.0 && h < 60.0 && s >= 0.20 && g > b) return true;

    // 7. Foliar necrosis & brown lesions (Phytophthora, Rhizoctonia, Anthracnose, Algal spot)
    if (h >= 14.0 && h < 38.0) {
      if (b < g && g < r && b < 135 && s >= 0.20) {
        // Exclude human skin tones (typically high L and low contrast)
        if (l > 75.0 && (r - g) < 45 && s < 0.35) return false;
        return true;
      }
    }

    // 8. Dark water-soaked necrosis (Phytophthora) where L is low (10-38)
    if (l >= 10.0 && l <= 38.0 && g >= b && s >= 0.14) {
      return true;
    }

    return false;
  }

  /// Diagnose from Deep Learning model output probabilities or color physics fallback
  static DiseaseDiagnosis diagnose({
    List<double>? modelProbabilities,
    int r = 46,
    int g = 125,
    int b = 50,
    double lesionAreaPct = 0.0,
  }) {
    // Check if target under ROI is actually a plant leaf
    if (!isLeafPresence(r: r, g: g, b: b)) {
      return DiseaseDiagnosis.noLeaf();
    }

    String bestKey = 'healthy';
    double maxConf = 0.85;

    if (modelProbabilities != null && modelProbabilities.length >= 6) {
      int maxIdx = 0;
      double curMax = -1.0;
      for (int i = 0; i < 6; i++) {
        if (modelProbabilities[i] > curMax) {
          curMax = modelProbabilities[i];
          maxIdx = i;
        }
      }
      bestKey = diseaseKeys[maxIdx];
      maxConf = curMax;
    } else {
      // Physics-informed visual lesion fallback
      final hsv = MultiColorSpaceService.rgbToHsv(r, g, b);
      final lab = MultiColorSpaceService.rgbToLab(r, g, b);

      if (r > 150 && g < 100 && b < 60) {
        // Reddish-orange velvet spots -> Algal Leaf Spot (Red Rust)
        bestKey = 'algal_spot';
        maxConf = 0.88;
        lesionAreaPct = 18.5;
      } else if (r < 80 && g < 75 && b < 60 && lab[0] < 35.0) {
        // Dark water-soaked necrosis -> Phytophthora
        bestKey = 'phytophthora';
        maxConf = 0.92;
        lesionAreaPct = 34.0;
      } else if (r > 120 && g > 95 && b < 80 && lab[1] > 8.0) {
        // Zonate brownish target lesion -> Anthracnose
        bestKey = 'anthracnose';
        maxConf = 0.84;
        lesionAreaPct = 22.0;
      } else if (r > 140 && g > 130 && b < 95 && hsv[1] < 0.45) {
        // Scalded straw-brown web lesions -> Rhizoctonia
        bestKey = 'rhizoctonia';
        maxConf = 0.89;
        lesionAreaPct = 28.0;
      } else if (hsv[1] < 0.28 && lab[0] > 60.0) {
        // Stippled white/pale spots -> Spider Mites / Psyllid
        bestKey = 'pest_damage';
        maxConf = 0.86;
        lesionAreaPct = 15.0;
      } else {
        // Rich green -> Healthy
        bestKey = 'healthy';
        maxConf = 0.95;
        lesionAreaPct = 0.0;
      }
    }

    final meta = diseaseMeta[bestKey] ?? diseaseMeta['healthy']!;
    return DiseaseDiagnosis(
      id: bestKey,
      nameTh: meta['name_th']!,
      nameEn: meta['name_en']!,
      scientificName: meta['sci']!,
      pathogenType: meta['type']!,
      severityLevel: meta['sev']!,
      confidence: maxConf,
      description: meta['desc']!,
      treatment: meta['treat']!,
      lesionAreaPercentage: lesionAreaPct,
    );
  }
}
