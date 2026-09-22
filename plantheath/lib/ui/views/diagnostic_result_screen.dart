import 'package:flutter/material.dart';
import '../../models/disease_diagnosis.dart';
import '../../models/nutrient_health_metric.dart';

class DiagnosticResultScreen extends StatelessWidget {
  final DiseaseDiagnosis disease;
  final NutrientHealthMetric nutrition;
  final VoidCallback? onTeachModel;

  const DiagnosticResultScreen({
    super.key,
    required this.disease,
    required this.nutrition,
    this.onTeachModel,
  });

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        backgroundColor: const Color(0xFF10141D),
        appBar: AppBar(
          backgroundColor: const Color(0xFF1A2230),
          elevation: 0,
          title: const Text(
            'ผลวินิจฉัยสุขภาพและโรคใบพืชทุเรียน',
            style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
          ),
          bottom: const TabBar(
            indicatorColor: Color(0xFF4CAF50),
            tabs: [
              Tab(icon: Icon(Icons.coronavirus_outlined), text: 'โรคและศัตรูพืช'),
              Tab(icon: Icon(Icons.eco_outlined), text: 'โภชนาการและ NPK'),
            ],
          ),
        ),
        body: TabBarView(
          children: [
            _buildPathologyTab(context),
            _buildNutritionTab(context),
          ],
        ),
      ),
    );
  }

  Widget _buildPathologyTab(BuildContext context) {
    final isHealthy = disease.id == 'healthy';
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        // 1. Hero Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: isHealthy
                  ? [const Color(0xFF1B5E20), const Color(0xFF2E7D32)]
                  : [const Color(0xFFB71C1C), const Color(0xFF880E4F)],
            ),
            borderRadius: BorderRadius.circular(12),
            boxShadow: const [BoxShadow(color: Colors.black38, blurRadius: 8)],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      disease.nameTh,
                      style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(color: Colors.black45, borderRadius: BorderRadius.circular(6)),
                    child: Text(
                      'ความเชื่อมั่น ${(disease.confidence * 100).toStringAsFixed(1)}%',
                      style: const TextStyle(color: Colors.amberAccent, fontSize: 12, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 4),
              Text(
                '${disease.nameEn} (${disease.scientificName})',
                style: const TextStyle(color: Colors.white70, fontSize: 13, fontStyle: FontStyle.italic),
              ),
              const SizedBox(height: 10),
              Row(
                children: [
                  _badge('ประเภทเชื้อ', disease.pathogenType),
                  const SizedBox(width: 8),
                  _badge('ระดับความรุนแรง', disease.severityLevel),
                  if (disease.lesionAreaPercentage > 0) ...[
                    const SizedBox(width: 8),
                    _badge('พื้นที่แผล', '${disease.lesionAreaPercentage.toStringAsFixed(1)}%'),
                  ],
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // 2. Description
        _infoCard(
          title: 'ลักษณะอาการของโรค',
          content: disease.description,
          icon: Icons.description_outlined,
          iconColor: Colors.blueAccent,
        ),
        const SizedBox(height: 12),

        // 3. Treatment
        _infoCard(
          title: 'แนวทางการจัดการและรักษาในสวนทุเรียน',
          content: disease.treatment,
          icon: Icons.healing,
          iconColor: const Color(0xFF4CAF50),
        ),
        const SizedBox(height: 20),

        // 4. Action: Fine-tune/Teach Model
        if (onTeachModel != null)
          OutlinedButton.icon(
            style: OutlinedButton.styleFrom(
              foregroundColor: Colors.amberAccent,
              side: const BorderSide(color: Colors.amberAccent),
              padding: const EdgeInsets.symmetric(vertical: 12),
            ),
            onPressed: onTeachModel,
            icon: const Icon(Icons.school_outlined),
            label: const Text('สอนโมเดลเพิ่มเติม (Continual Few-Shot Learning)'),
          ),
      ],
    );
  }

  Widget _buildNutritionTab(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        // 1. SPAD Chlorophyll Hero Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: const Color(0xFF1A261E),
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: const Color(0xFF4CAF50)),
          ),
          child: Row(
            children: [
              Container(
                width: 68,
                height: 68,
                decoration: const BoxDecoration(
                  color: Color(0xFF2E7D32),
                  shape: BoxShape.circle,
                ),
                child: Center(
                  child: Text(
                    nutrition.spadChlorophyll.toStringAsFixed(1),
                    style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'ระดับคลอโรฟิลล์ (SPAD Index)',
                      style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      'ดัชนีสีเขียวเข้ม (DGCI): ${nutrition.dgci.toStringAsFixed(3)}',
                      style: const TextStyle(color: Color(0xFF81C784), fontSize: 13),
                    ),
                    Text(
                      'Excess Green (ExG): ${nutrition.exg.toStringAsFixed(1)} • VARI: ${nutrition.vari.toStringAsFixed(2)}',
                      style: const TextStyle(color: Colors.white54, fontSize: 12),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // 2. Macro Nutrients (N, P, K)
        const Text(
          'ธาตุอาหารหลัก (Primary Macro Nutrients)',
          style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 8),
        Row(
          children: [
            Expanded(child: _metricBox('N (ไนโตรเจน)', '${nutrition.nitrogenPct.toStringAsFixed(2)}%', nutrition.nitrogenStatus, const Color(0xFF388E3C))),
            const SizedBox(width: 8),
            Expanded(child: _metricBox('P (ฟอสฟอรัส)', '${nutrition.phosphorusPct.toStringAsFixed(2)}%', nutrition.phosphorusStatus, const Color(0xFF00796B))),
            const SizedBox(width: 8),
            Expanded(child: _metricBox('K (โพแทสเซียม)', '${nutrition.potassiumPct.toStringAsFixed(2)}%', nutrition.potassiumStatus, const Color(0xFF1E88E5))),
          ],
        ),
        const SizedBox(height: 16),

        // 3. Secondary & Micronutrients
        const Text(
          'ธาตุอาหารรองและจุลธาตุ (Secondary & Micronutrients)',
          style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 8),
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: const Color(0xFF19202E),
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: Colors.white12),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('แมกนีเซียม (Mg): ${nutrition.magnesiumPct.toStringAsFixed(2)}%', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                  Text('แคลเซียม (Ca): ${nutrition.calciumPct.toStringAsFixed(2)}%', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                ],
              ),
              const Divider(color: Colors.white12, height: 12),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('เหล็ก (Fe): ${nutrition.ironPpm.toStringAsFixed(0)} ppm', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                  Text('สังกะสี (Zn): ${nutrition.zincPpm.toStringAsFixed(0)} ppm', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                  Text('โบรอน (B): ${nutrition.boronPpm.toStringAsFixed(0)} ppm', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                ],
              ),
              const SizedBox(height: 8),
              Text(
                'สรุปภาวะ: ${nutrition.micronutrientAlert}',
                style: const TextStyle(color: Colors.amberAccent, fontSize: 12, fontWeight: FontWeight.w500),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // 4. Prescriptive Fertilizer Advice
        _infoCard(
          title: 'คำแนะนำการจัดการปุ๋ยบำรุงทุเรียน',
          content: nutrition.fertilizerRecommendation,
          icon: Icons.agriculture_outlined,
          iconColor: Colors.amberAccent,
        ),
      ],
    );
  }

  Widget _badge(String label, String value) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      decoration: BoxDecoration(color: Colors.black26, borderRadius: BorderRadius.circular(4)),
      child: Text('$label $value', style: const TextStyle(color: Colors.white70, fontSize: 11)),
    );
  }

  Widget _infoCard({
    required String title,
    required String content,
    required IconData icon,
    required Color iconColor,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFF19202E),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: Colors.white12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, color: iconColor, size: 18),
              const SizedBox(width: 8),
              Text(title, style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 8),
          Text(content, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.4)),
        ],
      ),
    );
  }

  Widget _metricBox(String title, String val, String status, Color accentColor) {
    return Container(
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: const Color(0xFF19202E),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: accentColor.withOpacity(0.5)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(color: Colors.white54, fontSize: 10)),
          const SizedBox(height: 4),
          Text(val, style: TextStyle(color: accentColor, fontSize: 16, fontWeight: FontWeight.bold)),
          const SizedBox(height: 2),
          Text(status, style: const TextStyle(color: Colors.white70, fontSize: 9), overflow: TextOverflow.ellipsis),
        ],
      ),
    );
  }
}
