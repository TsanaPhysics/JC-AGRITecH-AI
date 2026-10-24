import 'package:flutter/material.dart';
import '../../models/leaf_color_tier.dart';

class AcademicLegendModal extends StatelessWidget {
  final ScaleMode currentMode;
  final List<LeafColorTier> tiers;
  final double? liveValue;
  final String? activeLabel;
  final List<int>? liveRgb;
  final List<double>? liveLab;

  const AcademicLegendModal({
    super.key,
    required this.currentMode,
    required this.tiers,
    this.liveValue,
    this.activeLabel,
    this.liveRgb,
    this.liveLab,
  });

  String _getModeTitle() {
    switch (currentMode) {
      case ScaleMode.spad:
        return 'เกณฑ์มาตรฐานระดับคลอโรฟิลล์ (SPAD Index)';
      case ScaleMode.nitrogen:
        return 'เกณฑ์วิเคราะห์ธาตุไนโตรเจน (Nitrogen N %)';
      case ScaleMode.phosphorus:
        return 'เกณฑ์วิเคราะห์ธาตุฟอสฟอรัส (Phosphorus P %)';
      case ScaleMode.potassium:
        return 'เกณฑ์วิเคราะห์ธาตุโพแทสเซียม (Potassium K %)';
      case ScaleMode.diseaseSeverity:
        return 'ระดับความรุนแรงของโรคใบพืช (Disease Severity Index)';
      case ScaleMode.micronutrient:
        return 'เกณฑ์ธาตุอาหารรองและจุลธาตุ (Secondary & Micro)';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Color(0xFF1E2430),
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Container(
              width: 44,
              height: 4,
              decoration: BoxDecoration(
                color: Colors.white24,
                borderRadius: BorderRadius.circular(2),
              ),
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              const Icon(Icons.menu_book, color: Color(0xFF4CAF50), size: 22),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  _getModeTitle(),
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          if (liveRgb != null || liveLab != null)
            Container(
              padding: const EdgeInsets.all(10),
              margin: const EdgeInsets.only(bottom: 12),
              decoration: BoxDecoration(
                color: Colors.black26,
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.white12),
              ),
              child: Wrap(
                alignment: WrapAlignment.spaceAround,
                spacing: 12,
                runSpacing: 4,
                children: [
                  if (liveRgb != null)
                    Text(
                      'RGB: (${liveRgb![0]}, ${liveRgb![1]}, ${liveRgb![2]})',
                      style: const TextStyle(color: Color(0xFF81C784), fontSize: 11, fontFamily: 'monospace'),
                    ),
                  if (liveLab != null)
                    Text(
                      'CIE L*a*b*: (${liveLab![0].toStringAsFixed(1)}, ${liveLab![1].toStringAsFixed(1)}, ${liveLab![2].toStringAsFixed(1)})',
                      style: const TextStyle(color: Color(0xFF64B5F6), fontSize: 11, fontFamily: 'monospace'),
                    ),
                ],
              ),
            ),
          Flexible(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxHeight: 380),
              child: SingleChildScrollView(
                child: Column(
                  children: tiers.map((tier) {
                    final isHighlighted = (activeLabel != null && tier.label.contains(activeLabel!)) ||
                        (liveValue != null && tier.containsValue(liveValue!));

                    return Container(
                      margin: const EdgeInsets.symmetric(vertical: 4),
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: isHighlighted ? tier.color.withOpacity(0.22) : Colors.white.withOpacity(0.04),
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(
                          color: isHighlighted ? tier.color : Colors.white10,
                          width: isHighlighted ? 1.8 : 1.0,
                        ),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            width: 22,
                            height: 22,
                            decoration: BoxDecoration(
                              color: tier.color,
                              shape: BoxShape.circle,
                              boxShadow: isHighlighted
                                  ? [BoxShadow(color: tier.color.withOpacity(0.8), blurRadius: 8)]
                                  : null,
                            ),
                            child: Center(
                              child: Text(
                                '${tier.tier}',
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 11,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Expanded(
                                      child: Text(
                                        tier.label,
                                        style: TextStyle(
                                          color: isHighlighted ? Colors.white : Colors.white70,
                                          fontWeight: isHighlighted ? FontWeight.bold : FontWeight.w500,
                                          fontSize: 13,
                                        ),
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ),
                                    if (isHighlighted)
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                        decoration: BoxDecoration(
                                          color: tier.color,
                                          borderRadius: BorderRadius.circular(4),
                                        ),
                                        child: const Text(
                                          'ACTIVE',
                                          style: TextStyle(color: Colors.black, fontSize: 9, fontWeight: FontWeight.bold),
                                        ),
                                      ),
                                  ],
                                ),
                                if (tier.interpretation != null) ...[
                                  const SizedBox(height: 3),
                                  Text(
                                    tier.interpretation!,
                                    style: const TextStyle(color: Colors.white54, fontSize: 11),
                                  ),
                                ],
                              ],
                            ),
                          ),
                        ],
                      ),
                    );
                  }).toList(),
                ),
              ),
            ),
          ),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF2E7D32),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
              ),
              onPressed: () => Navigator.pop(context),
              child: const Text('ปิดคำอธิบาย', style: TextStyle(color: Colors.white)),
            ),
          ),
        ],
      ),
    );
  }
}
