import 'package:flutter/material.dart';
import '../../services/continual_learning_service.dart';
import '../../services/deep_learning_inference_service.dart';

class ModelTrainerScreen extends StatefulWidget {
  final ContinualLearningService continualService;
  final DeepLearningInferenceService inferenceService;

  const ModelTrainerScreen({
    super.key,
    required this.continualService,
    required this.inferenceService,
  });

  @override
  State<ModelTrainerScreen> createState() => _ModelTrainerScreenState();
}

class _ModelTrainerScreenState extends State<ModelTrainerScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameThCtrl = TextEditingController(text: 'โรคใบไหม้แดดทุเรียน');
  final _nameEnCtrl = TextEditingController(text: 'Durian Sunburn Scorch');
  final _treatmentCtrl = TextEditingController(text: 'พ่นสารเคลือบสะท้อนแสง เช่น เคโอลิน (Kaolin) และปรับระบบสปริงเกลอร์ลดอุณหภูมิ');

  int _samplesCaptured = 0;
  final List<List<double>> _embeddings = [];
  bool _isTraining = false;

  void _captureSample() {
    setState(() {
      _samplesCaptured++;
      // Analyze synthetic or real embedding from camera/color sample
      final result = widget.inferenceService.analyzeLeaf(
        r: 170 + (_samplesCaptured * 5),
        g: 110 - (_samplesCaptured * 4),
        b: 50,
      );
      _embeddings.add(result.featureEmbedding);
    });

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('บันทึกตัวอย่างที่ $_samplesCaptured สำเร็จ (สกัด 128-d Feature Embedding เรียบร้อย)'),
        backgroundColor: const Color(0xFF2E7D32),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void _saveTrainedClass() {
    if (_samplesCaptured < 2) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('กรุณาบันทึกภาพตัวอย่างอย่างน้อย 2 ตัวอย่างเพื่อความแม่นยำ'),
          backgroundColor: Colors.redAccent,
        ),
      );
      return;
    }

    setState(() => _isTraining = true);

    widget.continualService.registerCustomClass(
      classId: 'custom_${DateTime.now().millisecondsSinceEpoch}',
      labelTh: _nameThCtrl.text,
      labelEn: _nameEnCtrl.text,
      sampleEmbeddings: _embeddings,
      treatment: _treatmentCtrl.text,
    );

    setState(() => _isTraining = false);

    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: const Color(0xFF19202E),
        title: const Row(
          children: [
            Icon(Icons.check_circle, color: Color(0xFF4CAF50)),
            SizedBox(width: 8),
            Text('สอนโมเดลสำเร็จ!', style: TextStyle(color: Colors.white, fontSize: 16)),
          ],
        ),
        content: Text(
          'โมเดลได้เรียนรู้รูปแบบของ "${_nameThCtrl.text}" เข้าสู่ระบบ On-Device Few-Shot Memory เรียบร้อยแล้ว ระบบกล้องสดจะตรวจจับอาการนี้ได้ทันที',
          style: const TextStyle(color: Colors.white70, fontSize: 13),
        ),
        actions: [
          TextButton(
            onPressed: () {
              Navigator.pop(context); // close dialog
              Navigator.pop(context); // back to camera screen
            },
            child: const Text('กลับไปยังกล้องตรวจวัด', style: TextStyle(color: Color(0xFF81C784))),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF10141D),
      appBar: AppBar(
        backgroundColor: const Color(0xFF1A2230),
        title: const Text('สอนโมเดลเรียนรู้เพิ่มเติม (Few-Shot Learning)', style: TextStyle(color: Colors.white, fontSize: 16)),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: const Color(0xFF1E2838),
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: Colors.amberAccent.withOpacity(0.4)),
            ),
            child: const Row(
              children: [
                Icon(Icons.lightbulb_outline, color: Colors.amberAccent, size: 28),
                SizedBox(width: 12),
                Expanded(
                  child: Text(
                    'การเรียนรู้แบบ On-Device Continual Learning ช่วยให้เกษตรกรสามารถเพิ่มโรคใหม่หรือสายพันธุ์ทุเรียนใหม่ได้ทันที โดยสกัดเวกเตอร์เอกลักษณ์ 128 มิติโดยไม่ต้องเทรนโมเดลใหม่ทั้งหมด',
                    style: TextStyle(color: Colors.white70, fontSize: 12, height: 1.4),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('ชื่อโรคหรืออาการผิดปกติ (ภาษาไทย)', style: TextStyle(color: Colors.white, fontSize: 13)),
                const SizedBox(height: 6),
                TextFormField(
                  controller: _nameThCtrl,
                  style: const TextStyle(color: Colors.white),
                  decoration: _inputDecoration('เช่น โรคใบไหม้แดดทุเรียน'),
                ),
                const SizedBox(height: 12),
                const Text('ชื่อภาษาอังกฤษ / Scientific Name', style: TextStyle(color: Colors.white, fontSize: 13)),
                const SizedBox(height: 6),
                TextFormField(
                  controller: _nameEnCtrl,
                  style: const TextStyle(color: Colors.white),
                  decoration: _inputDecoration('เช่น Durian Sunburn Scorch'),
                ),
                const SizedBox(height: 12),
                const Text('แนวทางการจัดการและรักษาในสวน', style: TextStyle(color: Colors.white, fontSize: 13)),
                const SizedBox(height: 6),
                TextFormField(
                  controller: _treatmentCtrl,
                  maxLines: 2,
                  style: const TextStyle(color: Colors.white),
                  decoration: _inputDecoration('วิธีฉีดพ่นยา ปรับฮอร์โมน หรือการตัดแต่งใบ'),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),
          const Text('การเก็บภาพตัวอย่างเพื่อสกัด Feature Embeddings', style: TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Row(
            children: [
              Expanded(
                child: ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF1976D2),
                    padding: const EdgeInsets.symmetric(vertical: 12),
                  ),
                  onPressed: _captureSample,
                  icon: const Icon(Icons.camera_alt, color: Colors.white),
                  label: Text('ถ่ายบันทึกตัวอย่าง ($_samplesCaptured/3)', style: const TextStyle(color: Colors.white)),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF2E7D32),
                padding: const EdgeInsets.symmetric(vertical: 14),
              ),
              onPressed: _isTraining ? null : _saveTrainedClass,
              icon: _isTraining 
                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2)) 
                  : const Icon(Icons.save, color: Colors.white),
              label: Text(
                _isTraining ? 'กำลังประมวลผลเวกเตอร์...' : 'บันทึกเข้าหน่วยความจำโมเดล',
                style: const TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
              ),
            ),
          ),
        ],
      ),
    );
  }

  InputDecoration _inputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Colors.white30, fontSize: 13),
      filled: true,
      fillColor: const Color(0xFF19202E),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: Colors.white12)),
      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: Colors.white12)),
      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: const BorderSide(color: Color(0xFF4CAF50))),
      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
    );
  }
}
