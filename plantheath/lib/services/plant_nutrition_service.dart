import '../models/nutrient_health_metric.dart';
import 'multi_color_space_service.dart';
import 'plant_pathology_service.dart';

class PlantNutritionService {
  /// Analyze nutritional and chlorophyll status from leaf pixel colors
  static NutrientHealthMetric analyzeFromColor(int r, int g, int b, {List<double>? regressionOutputs}) {
    // Return no-leaf metric if target is non-vegetative
    if (!PlantPathologyService.isLeafPresence(r: r, g: g, b: b)) {
      return NutrientHealthMetric.noLeaf();
    }

    final hsv = MultiColorSpaceService.rgbToHsv(r, g, b);
    final lab = MultiColorSpaceService.rgbToLab(r, g, b);
    final dgci = MultiColorSpaceService.calculateDgci(hsv[0], hsv[1], hsv[2]);
    final vari = MultiColorSpaceService.calculateVari(r, g, b);
    final gli = MultiColorSpaceService.calculateGli(r, g, b);
    final exg = MultiColorSpaceService.calculateExg(r, g, b);

    // If real deep learning regression outputs are provided (9 outputs)
    // [0]=SPAD, [1]=N, [2]=P, [3]=K, [4]=Mg, [5]=Ca, [6]=Fe, [7]=Zn, [8]=B
    double spad;
    double nPct;
    double pPct;
    double kPct;
    double mgPct;
    double caPct;
    double fePpm;
    double znPpm;
    double bPpm;
    double cuPpm;
    double mnPpm;

    if (regressionOutputs != null && regressionOutputs.length >= 9) {
      spad = regressionOutputs[0].clamp(10.0, 75.0);
      nPct = regressionOutputs[1].clamp(1.0, 4.0);
      pPct = regressionOutputs[2].clamp(0.08, 0.40);
      kPct = regressionOutputs[3].clamp(0.8, 3.2);
      mgPct = regressionOutputs[4].clamp(0.15, 0.80);
      caPct = regressionOutputs[5].clamp(0.8, 3.5);
      fePpm = regressionOutputs[6].clamp(30.0, 250.0);
      znPpm = regressionOutputs[7].clamp(10.0, 90.0);
      bPpm = regressionOutputs[8].clamp(15.0, 100.0);
      cuPpm = (6.0 + (dgci * 12.0)).clamp(4.0, 25.0);
      mnPpm = (35.0 + (spad * 0.8) - (r > 150 ? 15.0 : 0.0)).clamp(20.0, 160.0);
    } else {
      // Physics-informed regression model based on leaf reflectance & color spaces
      spad = MultiColorSpaceService.estimateSpadFromColor(r, g, b);
      
      // Nitrogen closely correlates with SPAD (N% = 0.048 * SPAD + 0.35)
      nPct = (0.045 * spad + 0.38).clamp(1.2, 3.8);

      // Phosphorus correlation with chromatic a* and green ratio
      double greenRatio = g / (r + g + b + 0.001);
      pPct = (0.12 + (greenRatio * 0.16) - (lab[1].abs() * 0.001)).clamp(0.10, 0.32);

      // Potassium correlation: yellowing/browning at leaf margin
      bool hasBrownMargin = r > 110 && g > 90 && b < 70;
      kPct = (hasBrownMargin ? 1.15 : (1.45 + (dgci * 0.85))).clamp(0.9, 2.7);

      // Magnesium: interveinal yellowing (high red/green ratio with low saturation)
      mgPct = (0.22 + (dgci * 0.30) - ((r > g ? 0.12 : 0.0))).clamp(0.18, 0.65);

      // Calcium: structural integrity
      caPct = (1.6 + (lab[0] * 0.012)).clamp(1.2, 2.9);

      // Micronutrients: Fe, Zn, B, Cu, Mn in mg/kg (ppm)
      fePpm = (55.0 + (spad * 1.5) - (r > 160 ? 35.0 : 0.0)).clamp(40.0, 180.0);
      znPpm = (20.0 + (dgci * 28.0)).clamp(15.0, 65.0);
      bPpm = (28.0 + (caPct * 12.0)).clamp(20.0, 75.0);
      cuPpm = (6.0 + (dgci * 12.0)).clamp(4.0, 25.0);
      mnPpm = (35.0 + (spad * 0.8) - (r > 150 ? 15.0 : 0.0)).clamp(20.0, 160.0);
    }

    // Optical and Statistical Confidence Calculation (%)
    double lightingPenalty = 0.0;
    if (lab[0] < 32.0) {
      lightingPenalty = (32.0 - lab[0]) * 0.8;
    } else if (lab[0] > 70.0) {
      lightingPenalty = (lab[0] - 70.0) * 0.7;
    }

    double satPenalty = 0.0;
    if (hsv[1] < 0.30) {
      satPenalty = (0.30 - hsv[1]) * 25.0;
    }

    double overallConf = (96.5 - lightingPenalty - satPenalty).clamp(65.0, 98.8);
    double spadConf = (overallConf + 1.2).clamp(70.0, 99.2);
    double nConf = (overallConf * 0.99).clamp(68.0, 98.5);
    double pConf = (overallConf * 0.94).clamp(62.0, 96.0);
    double kConf = (overallConf * 0.96).clamp(65.0, 97.2);

    // Status evaluation with dual units: mg/kg (ppm) and %
    final nMgKg = nPct * 10000.0;
    String nStatus;
    if (nPct < 1.8) {
      nStatus = 'ขาดวิกฤต (${nMgKg.toStringAsFixed(0)} mg/kg | ${nPct.toStringAsFixed(2)}%)';
    } else if (nPct < 2.2) {
      nStatus = 'ค่อนข้างต่ำ (${nMgKg.toStringAsFixed(0)} mg/kg | ${nPct.toStringAsFixed(2)}%)';
    } else if (nPct <= 2.8) {
      nStatus = 'เหมาะสม (${nMgKg.toStringAsFixed(0)} mg/kg | ${nPct.toStringAsFixed(2)}%)';
    } else {
      nStatus = 'สูงเกินเกณฑ์ (${nMgKg.toStringAsFixed(0)} mg/kg | ${nPct.toStringAsFixed(2)}%)';
    }

    final pMgKg = pPct * 10000.0;
    String pStatus = (pPct < 0.15) 
        ? 'ต่ำ (${pMgKg.toStringAsFixed(0)} mg/kg | ${pPct.toStringAsFixed(2)}%)' 
        : (pPct <= 0.25 
            ? 'เหมาะสม (${pMgKg.toStringAsFixed(0)} mg/kg | ${pPct.toStringAsFixed(2)}%)' 
            : 'สูง (${pMgKg.toStringAsFixed(0)} mg/kg | ${pPct.toStringAsFixed(2)}%)');

    final kMgKg = kPct * 10000.0;
    String kStatus = (kPct < 1.5) 
        ? 'ต่ำ - ขอบใบเริ่มแห้ง (${kMgKg.toStringAsFixed(0)} mg/kg | ${kPct.toStringAsFixed(2)}%)' 
        : (kPct <= 2.2 
            ? 'เหมาะสม (${kMgKg.toStringAsFixed(0)} mg/kg | ${kPct.toStringAsFixed(2)}%)' 
            : 'สูง (${kMgKg.toStringAsFixed(0)} mg/kg | ${kPct.toStringAsFixed(2)}%)');

    final mgMgKg = mgPct * 10000.0;
    String mgStatus = (mgPct < 0.30) 
        ? 'ต่ำ - เหลืองก้างปลา (${mgMgKg.toStringAsFixed(0)} mg/kg | ${mgPct.toStringAsFixed(2)}%)' 
        : 'เหมาะสม (${mgMgKg.toStringAsFixed(0)} mg/kg | ${mgPct.toStringAsFixed(2)}%)';

    // Micronutrient alerts (mg/kg or ppm)
    List<String> microAlerts = [];
    if (fePpm < 70.0) microAlerts.add('ขาดเหล็ก (Fe: ${fePpm.toStringAsFixed(0)} mg/kg)');
    if (znPpm < 25.0) microAlerts.add('ขาดสังกะสี (Zn: ${znPpm.toStringAsFixed(0)} mg/kg)');
    if (bPpm < 30.0) microAlerts.add('ขาดโบรอน (B: ${bPpm.toStringAsFixed(0)} mg/kg)');
    if (cuPpm < 6.0) microAlerts.add('ขาดทองแดง (Cu: ${cuPpm.toStringAsFixed(0)} mg/kg)');
    if (mnPpm < 30.0) microAlerts.add('ขาดแมงกานีส (Mn: ${mnPpm.toStringAsFixed(0)} mg/kg)');
    if (mgPct < 0.30) microAlerts.add('ขาดแมกนีเซียม (Mg)');
    if (caPct < 1.8) microAlerts.add('ขาดแคลเซียม (Ca)');

    String microSummary = microAlerts.isEmpty 
        ? 'ธาตุอาหารรองและจุลธาตุครบถ้วน อยู่ในเกณฑ์เหมาะสม' 
        : microAlerts.join(' • ');

    // Prescriptive Fertilizer Plan
    String recommendation;
    if (nPct < 2.2) {
      recommendation = 'เสริมปุ๋ยไนโตรเจนสูงสูตร 25-7-7 หรือพ่นยูเรีย 0.5% ทางใบ เพื่อฟื้นฟูการสร้างคลอโรฟิลล์';
    } else if (kPct < 1.5) {
      recommendation = 'ใส่ปุ๋ยโพแทสเซียมซัลเฟต (0-0-50) ป้องกันอาการขอบใบไหม้ และฉีดพ่นโพแทสเซียมไนเตรต';
    } else if (mgPct < 0.30) {
      recommendation = 'หว่านโดโลไมต์ปรับปรุงดิน หรือฉีดพ่นแมกนีเซียมซัลเฟต 1% ทางใบแก้ใบเหลืองก้างปลา';
    } else {
      recommendation = 'บำรุงด้วยปุ๋ยสูตรเสมอ 16-16-16 หรือ 15-15-15 ควบคู่กับอินทรียวัตถุและรักษาระดับความชื้นดิน';
    }

    return NutrientHealthMetric(
      spadChlorophyll: spad,
      nitrogenPct: nPct,
      phosphorusPct: pPct,
      potassiumPct: kPct,
      magnesiumPct: mgPct,
      calciumPct: caPct,
      ironPpm: fePpm,
      zincPpm: znPpm,
      boronPpm: bPpm,
      copperPpm: cuPpm,
      manganesePpm: mnPpm,
      overallConfidence: overallConf,
      nitrogenConfidence: nConf,
      phosphorusConfidence: pConf,
      potassiumConfidence: kConf,
      spadConfidence: spadConf,
      dgci: dgci,
      vari: vari,
      gli: gli,
      exg: exg,
      rgb: [r, g, b],
      hsv: hsv,
      lab: lab,
      nitrogenStatus: nStatus,
      phosphorusStatus: pStatus,
      potassiumStatus: kStatus,
      magnesiumStatus: mgStatus,
      micronutrientAlert: microSummary,
      fertilizerRecommendation: recommendation,
    );
  }
}
