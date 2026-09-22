import 'dart:io';
import 'package:flutter/material.dart';
import '../../services/leaf_dataset_service.dart';

class LeafDatasetGalleryScreen extends StatefulWidget {
  const LeafDatasetGalleryScreen({super.key});

  @override
  State<LeafDatasetGalleryScreen> createState() => _LeafDatasetGalleryScreenState();
}

class _LeafDatasetGalleryScreenState extends State<LeafDatasetGalleryScreen> {
  List<LeafDatasetItem> _samples = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSamples();
  }

  Future<void> _loadSamples() async {
    setState(() => _isLoading = true);
    final items = await LeafDatasetService.loadAllSamples();
    if (mounted) {
      setState(() {
        _samples = items;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF0D1117),
      appBar: AppBar(
        backgroundColor: const Color(0xFF161B22),
        elevation: 0,
        title: const Text(
          'คลังข้อมูลภาพและวีดีโอใบพืช',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh, color: Colors.cyanAccent),
            onPressed: _loadSamples,
            tooltip: 'รีเฟรช',
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: Colors.greenAccent))
          : _samples.isEmpty
              ? _buildEmptyState()
              : GridView.builder(
                  padding: const EdgeInsets.all(12),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 10,
                    mainAxisSpacing: 10,
                    childAspectRatio: 0.72,
                  ),
                  itemCount: _samples.length,
                  itemBuilder: (context, index) {
                    final item = _samples[index];
                    return _buildSampleCard(item);
                  },
                ),
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.photo_library_outlined, size: 72, color: Colors.white.withOpacity(0.2)),
          const SizedBox(height: 14),
          const Text(
            'ยังไม่มีตัวอย่างภาพถ่ายหรือวีดีโอ',
            style: TextStyle(color: Colors.white70, fontSize: 15, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 6),
          const Text(
            'กดปุ่มชัตเตอร์ในหน้ากล้องเพื่อบันทึกข้อมูลใบพืช',
            style: TextStyle(color: Colors.white38, fontSize: 12),
          ),
        ],
      ),
    );
  }

  Widget _buildSampleCard(LeafDatasetItem item) {
    final isVideo = item.mediaType == LeafMediaType.video;
    final color = item.disease.id == 'healthy' ? const Color(0xFF4CAF50) : const Color(0xFFFF5252);

    return GestureDetector(
      onTap: () => _showSampleDetailsDialog(item),
      child: Container(
        decoration: BoxDecoration(
          color: const Color(0xFF161B22),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: Colors.white12),
          boxShadow: const [
            BoxShadow(color: Colors.black45, blurRadius: 6, offset: Offset(0, 3)),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Media Preview Header
            Expanded(
              child: Stack(
                fit: StackFit.expand,
                children: [
                  isVideo
                      ? Container(
                          color: const Color(0xFF1F2937),
                          child: const Center(
                            child: Icon(Icons.videocam, size: 48, color: Colors.cyanAccent),
                          ),
                        )
                      : Image.file(
                          File(item.filePath),
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => const Center(
                            child: Icon(Icons.broken_image, color: Colors.white30),
                          ),
                        ),

                  // Media Type Badge
                  Positioned(
                    top: 6,
                    left: 6,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(
                        color: Colors.black.withOpacity(0.7),
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            isVideo ? Icons.videocam : Icons.photo_camera,
                            size: 11,
                            color: isVideo ? Colors.redAccent : Colors.cyanAccent,
                          ),
                          const SizedBox(width: 4),
                          Text(
                            isVideo ? '${item.durationSeconds}s' : 'IMAGE',
                            style: const TextStyle(color: Colors.white, fontSize: 9.5, fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                    ),
                  ),

                  // Delete Button
                  Positioned(
                    top: 4,
                    right: 4,
                    child: GestureDetector(
                      onTap: () => _confirmDelete(item),
                      child: Container(
                        padding: const EdgeInsets.all(4),
                        decoration: BoxDecoration(
                          color: Colors.black.withOpacity(0.6),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(Icons.delete_outline, size: 16, color: Colors.white70),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // Telemetry Metadata Footer
            Padding(
              padding: const EdgeInsets.all(8),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 8,
                        height: 8,
                        decoration: BoxDecoration(color: color, shape: BoxShape.circle),
                      ),
                      const SizedBox(width: 6),
                      Expanded(
                        child: Text(
                          item.disease.nameTh,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 11.5,
                            fontWeight: FontWeight.bold,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 3),
                  // Tree ID & VPD badge
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1.5),
                        decoration: BoxDecoration(
                          color: Colors.amberAccent.withOpacity(0.15),
                          borderRadius: BorderRadius.circular(4),
                          border: Border.all(color: Colors.amberAccent.withOpacity(0.5), width: 0.6),
                        ),
                        child: Text(
                          item.treeId,
                          style: const TextStyle(color: Colors.amberAccent, fontSize: 8.5, fontWeight: FontWeight.bold),
                        ),
                      ),
                      Text(
                        '${item.vpdKpa.toStringAsFixed(2)} kPa',
                        style: const TextStyle(color: Colors.cyanAccent, fontSize: 8.5, fontFamily: 'monospace'),
                      ),
                    ],
                  ),
                  const SizedBox(height: 3),
                  Text(
                    'SPAD: ${item.nutrition.spadChlorophyll.toStringAsFixed(1)} | N: ${item.nutrition.nitrogenPct.toStringAsFixed(1)}%',
                    style: const TextStyle(color: Color(0xFF81C784), fontSize: 9.5, fontFamily: 'monospace'),
                  ),
                  const SizedBox(height: 2),
                  Row(
                    children: [
                      const Icon(Icons.location_on, size: 9, color: Colors.white54),
                      const SizedBox(width: 2),
                      Expanded(
                        child: Text(
                          item.location.formattedCoordinates,
                          style: const TextStyle(color: Colors.white54, fontSize: 8.5),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    ),
  );
}

  void _confirmDelete(LeafDatasetItem item) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: const Color(0xFF1A2230),
        title: const Text('ลบตัวอย่างนี้หรือไม่?', style: TextStyle(color: Colors.white)),
        content: const Text(
          'ข้อมูลภาพ/วีดีโอและเมทริกซ์การวิเคราะห์จะถูกลบออกจากเครื่องอย่างถาวร',
          style: TextStyle(color: Colors.white70, fontSize: 13),
        ),
        actions: [
          TextButton(
            child: const Text('ยกเลิก', style: TextStyle(color: Colors.white54)),
            onPressed: () => Navigator.pop(ctx),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.redAccent),
            child: const Text('ลบข้อมูล', style: TextStyle(color: Colors.white)),
            onPressed: () async {
              Navigator.pop(ctx);
              await LeafDatasetService.deleteSample(item);
              _loadSamples();
            },
          ),
        ],
      ),
    );
  }

  void _showSampleDetailsDialog(LeafDatasetItem item) {
    final isVideo = item.mediaType == LeafMediaType.video;
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
        backgroundColor: const Color(0xFF0F172A),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: const BorderSide(color: Colors.white24)),
        child: Container(
          constraints: const BoxConstraints(maxWidth: 400),
          padding: const EdgeInsets.all(16),
          child: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.description_outlined, color: Colors.cyanAccent, size: 20),
                        const SizedBox(width: 8),
                        Text(
                          item.treeId,
                          style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white54, size: 20),
                      onPressed: () => Navigator.pop(ctx),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                ClipRRect(
                  borderRadius: BorderRadius.circular(10),
                  child: AspectRatio(
                    aspectRatio: 16 / 9,
                    child: isVideo
                        ? Container(
                            color: const Color(0xFF1E293B),
                            child: const Center(child: Icon(Icons.videocam, color: Colors.redAccent, size: 48)),
                          )
                        : Image.file(
                            File(item.filePath),
                            fit: BoxFit.cover,
                            errorBuilder: (_, __, ___) => const Center(
                              child: Icon(Icons.broken_image, color: Colors.white30),
                            ),
                          ),
                  ),
                ),
                const SizedBox(height: 14),
                _buildDetailRow('การวินิจฉัยโรคพืช', item.disease.nameTh, item.disease.id == 'healthy' ? Colors.greenAccent : Colors.redAccent),
                _buildDetailRow('ความรุนแรง (DSI)', item.disease.severityLevel, Colors.amberAccent),
                _buildDetailRow('รุ่นใบ (Leaf Stage)', item.leafStage, Colors.cyanAccent),
                _buildDetailRow('ระยะต้น (Crop Stage)', item.cropStage, Colors.lightGreenAccent),
                const Divider(color: Colors.white24, height: 20),
                const Text('เมทริกซ์สเปกตรัม & ธาตุอาหาร (mg/kg | %)', style: TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.bold)),
                const SizedBox(height: 6),
                _buildDetailRow('คลอโรฟิลล์ SPAD', item.nutrition.spadChlorophyll.toStringAsFixed(1), const Color(0xFF00E676)),
                _buildDetailRow('ไนโตรเจน (N)', '${item.nutrition.nitrogenMgKg.toStringAsFixed(0)} mg/kg (${item.nutrition.nitrogenPct.toStringAsFixed(2)}%)', const Color(0xFFFF7043)),
                _buildDetailRow('ฟอสฟอรัส (P)', '${item.nutrition.phosphorusMgKg.toStringAsFixed(0)} mg/kg (${item.nutrition.phosphorusPct.toStringAsFixed(2)}%)', const Color(0xFF42A5F5)),
                _buildDetailRow('โพแทสเซียม (K)', '${item.nutrition.potassiumMgKg.toStringAsFixed(0)} mg/kg (${item.nutrition.potassiumPct.toStringAsFixed(2)}%)', const Color(0xFFAB47BC)),
                _buildDetailRow('แมกนีเซียม (Mg)', '${item.nutrition.magnesiumMgKg.toStringAsFixed(0)} mg/kg (${item.nutrition.magnesiumPct.toStringAsFixed(2)}%)', Colors.tealAccent),
                _buildDetailRow('แคลเซียม (Ca)', '${item.nutrition.calciumMgKg.toStringAsFixed(0)} mg/kg (${item.nutrition.calciumPct.toStringAsFixed(2)}%)', Colors.pinkAccent),
                _buildDetailRow('เหล็ก (Fe)', '${item.nutrition.ironPpm.toStringAsFixed(0)} ppm', Colors.orangeAccent),
                _buildDetailRow('ความเชื่อมั่น AI', '${item.nutrition.overallConfidence.toStringAsFixed(1)}%', Colors.greenAccent),
                const Divider(color: Colors.white24, height: 20),
                const Text('HandySense Microclimate & พิกัด', style: TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.bold)),
                const SizedBox(height: 6),
                _buildDetailRow('VPD แรงดึงระเหยน้ำ', '${item.vpdKpa.toStringAsFixed(2)} kPa', Colors.cyanAccent),
                _buildDetailRow('พิกัดภูมิศาสตร์ (GPS)', item.location.formattedCoordinates, Colors.amberAccent),
                _buildDetailRow('เวลาบันทึก', '${item.timestamp.day}/${item.timestamp.month}/${item.timestamp.year} ${item.timestamp.hour.toString().padLeft(2, '0')}:${item.timestamp.minute.toString().padLeft(2, '0')} น.', Colors.white60),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(color: const Color(0xFF1E293B), borderRadius: BorderRadius.circular(8)),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('ข้อแนะนำการจัดการปุ๋ย/โรคตามระยะต้น:', style: TextStyle(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 4),
                      Text(item.nutrition.fertilizerRecommendation, style: const TextStyle(color: Colors.white, fontSize: 11)),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value, Color valueColor) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2.5),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: Colors.white54, fontSize: 11)),
          Text(value, style: TextStyle(color: valueColor, fontSize: 11, fontWeight: FontWeight.bold, fontFamily: 'monospace')),
        ],
      ),
    );
  }
}
