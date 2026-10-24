import 'package:flutter/material.dart';
import '../../models/leaf_color_tier.dart';
import 'academic_legend_modal.dart';

enum RoiShape {
  rectangle,
  circle,
  freeform,
}

class InteractiveRoiSelector extends StatefulWidget {
  final RoiShape selectedShape;
  final double roiSize;
  final ValueChanged<RoiShape> onShapeChanged;
  final ValueChanged<double> onSizeChanged;
  final ScaleMode scaleMode;
  final ValueChanged<ScaleMode> onScaleModeChanged;
  final double? liveSpad;
  final double? liveNitrogen;
  final double? livePhosphorus;
  final double? livePotassium;
  final String? liveDiseaseLabel;
  final List<int>? liveRgb;
  final List<double>? liveLab;

  const InteractiveRoiSelector({
    super.key,
    required this.selectedShape,
    required this.roiSize,
    required this.onShapeChanged,
    required this.onSizeChanged,
    this.scaleMode = ScaleMode.spad,
    required this.onScaleModeChanged,
    this.liveSpad,
    this.liveNitrogen,
    this.livePhosphorus,
    this.livePotassium,
    this.liveDiseaseLabel,
    this.liveRgb,
    this.liveLab,
  });

  @override
  State<InteractiveRoiSelector> createState() => _InteractiveRoiSelectorState();
}

class _InteractiveRoiSelectorState extends State<InteractiveRoiSelector> {
  static const List<LeafColorTier> spadTiers = [
    LeafColorTier(tier: 1, label: '<25 ซีดขาว (วิกฤต)', color: Color(0xFFE4EEAC), status: 'Critical', minValue: 0.0, maxValue: 24.9),
    LeafColorTier(tier: 2, label: '25-35 เขียวตอง (ต่ำ)', color: Color(0xFFB6DA7E), status: 'Low', minValue: 25.0, maxValue: 34.9),
    LeafColorTier(tier: 3, label: '35-45 เขียวปกติ (ปานกลาง)', color: Color(0xFF6CBA4C), status: 'Moderate', minValue: 35.0, maxValue: 44.9),
    LeafColorTier(tier: 4, label: '45-55 เขียวสด (สมบูรณ์)', color: Color(0xFF388E3C), status: 'Optimal', minValue: 45.0, maxValue: 54.9),
    LeafColorTier(tier: 5, label: '>55 มรกต (สะสมสูง)', color: Color(0xFF1B5E20), status: 'Surplus', minValue: 55.0, maxValue: 80.0),
  ];

  static const List<LeafColorTier> nitrogenTiers = [
    LeafColorTier(tier: 1, label: '<1.8% N ขาดวิกฤต', color: Color(0xFFF5EBA0), status: 'Critical', minValue: 0.0, maxValue: 1.79),
    LeafColorTier(tier: 2, label: '1.8-2.1% N ต่ำ', color: Color(0xFFC6DE82), status: 'Low', minValue: 1.80, maxValue: 2.19),
    LeafColorTier(tier: 3, label: '2.2-2.8% N เหมาะสม', color: Color(0xFF388E3C), status: 'Optimal', minValue: 2.20, maxValue: 2.80),
    LeafColorTier(tier: 4, label: '2.8-3.2% N สูง', color: Color(0xFF1B5E20), status: 'High', minValue: 2.81, maxValue: 3.20),
    LeafColorTier(tier: 5, label: '>3.2% N เกินเกณฑ์', color: Color(0xFF0D47A1), status: 'Excessive', minValue: 3.21, maxValue: 5.0),
  ];

  static const List<LeafColorTier> phosphorusTiers = [
    LeafColorTier(tier: 1, label: '<0.12% P ม่วงคล้ำ', color: Color(0xFF7B1FA2), status: 'Low', minValue: 0.0, maxValue: 0.119),
    LeafColorTier(tier: 2, label: '0.12-0.14% P ค่อนข้างต่ำ', color: Color(0xFF455A64), status: 'Slightly Low', minValue: 0.12, maxValue: 0.149),
    LeafColorTier(tier: 3, label: '0.15-0.25% P เหมาะสม', color: Color(0xFF2E7D32), status: 'Optimal', minValue: 0.15, maxValue: 0.25),
    LeafColorTier(tier: 4, label: '>0.25% P สูง', color: Color(0xFF00796B), status: 'High', minValue: 0.251, maxValue: 0.60),
  ];

  static const List<LeafColorTier> potassiumTiers = [
    LeafColorTier(tier: 1, label: 'K ขาด ขอบใบไหม้', color: Color(0xFFA52A2A), status: 'Deficient', minValue: 0.0, maxValue: 1.49),
    LeafColorTier(tier: 2, label: '1.5-2.2% K เหมาะสม', color: Color(0xFF2E7D32), status: 'Optimal', minValue: 1.5, maxValue: 2.2),
    LeafColorTier(tier: 3, label: '>2.2% K สูง', color: Color(0xFF1E88E5), status: 'High', minValue: 2.21, maxValue: 4.0),
  ];

  static const List<LeafColorTier> diseaseTiers = [
    LeafColorTier(tier: 1, label: 'ปกติ ไร้รอยโรค (0%)', color: Color(0xFF388E3C), status: 'Healthy'),
    LeafColorTier(tier: 2, label: 'แผลระยะแรก (<10%)', color: Color(0xFFFBC02D), status: 'Initial'),
    LeafColorTier(tier: 3, label: 'แผลปานกลาง (10-25%)', color: Color(0xFFF57C00), status: 'Moderate'),
    LeafColorTier(tier: 4, label: 'แผลลุกลาม (25-50%)', color: Color(0xFFD32F2F), status: 'Severe'),
    LeafColorTier(tier: 5, label: 'ใบไหม้รุนแรง (>50%)', color: Color(0xFF5D4037), status: 'Destructive'),
  ];

  List<LeafColorTier> get _currentTiers {
    switch (widget.scaleMode) {
      case ScaleMode.spad:
        return spadTiers;
      case ScaleMode.nitrogen:
        return nitrogenTiers;
      case ScaleMode.phosphorus:
        return phosphorusTiers;
      case ScaleMode.potassium:
        return potassiumTiers;
      case ScaleMode.diseaseSeverity:
        return diseaseTiers;
      case ScaleMode.micronutrient:
        return spadTiers;
    }
  }

  int _getActiveTierIndex() {
    final tiers = _currentTiers;
    switch (widget.scaleMode) {
      case ScaleMode.spad:
        if (widget.liveSpad == null) return 2;
        for (int i = 0; i < tiers.length; i++) {
          if (tiers[i].containsValue(widget.liveSpad!)) return i;
        }
        return 2;
      case ScaleMode.nitrogen:
        if (widget.liveNitrogen == null) return 2;
        for (int i = 0; i < tiers.length; i++) {
          if (tiers[i].containsValue(widget.liveNitrogen!)) return i;
        }
        return 2;
      case ScaleMode.phosphorus:
        if (widget.livePhosphorus == null) return 2;
        for (int i = 0; i < tiers.length; i++) {
          if (tiers[i].containsValue(widget.livePhosphorus!)) return i;
        }
        return 2;
      case ScaleMode.potassium:
        if (widget.livePotassium == null) return 1;
        for (int i = 0; i < tiers.length; i++) {
          if (tiers[i].containsValue(widget.livePotassium!)) return i;
        }
        return 1;
      case ScaleMode.diseaseSeverity:
        if (widget.liveDiseaseLabel == null || widget.liveDiseaseLabel!.contains('ปกติ')) return 0;
        if (widget.liveDiseaseLabel!.contains('แอนแทรคโนส')) return 2;
        if (widget.liveDiseaseLabel!.contains('ราใบติด')) return 3;
        if (widget.liveDiseaseLabel!.contains('ไฟทอปธอร่า')) return 4;
        return 1;
      case ScaleMode.micronutrient:
        return 2;
    }
  }

  String _getLiveTagText() {
    switch (widget.scaleMode) {
      case ScaleMode.spad:
        return '◀ SPAD ${widget.liveSpad?.toStringAsFixed(1) ?? "48.5"}';
      case ScaleMode.nitrogen:
        return '◀ N ${widget.liveNitrogen?.toStringAsFixed(2) ?? "2.50"}%';
      case ScaleMode.phosphorus:
        return '◀ P ${widget.livePhosphorus?.toStringAsFixed(2) ?? "0.18"}%';
      case ScaleMode.potassium:
        return '◀ K ${widget.livePotassium?.toStringAsFixed(2) ?? "1.85"}%';
      case ScaleMode.diseaseSeverity:
        return '◀ ${widget.liveDiseaseLabel ?? "ใบปกติ"}';
      case ScaleMode.micronutrient:
        return '◀ จุลธาตุ';
    }
  }

  void _openLegendSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (_) => AcademicLegendModal(
        currentMode: widget.scaleMode,
        tiers: _currentTiers,
        liveValue: widget.scaleMode == ScaleMode.spad ? widget.liveSpad : widget.liveNitrogen,
        activeLabel: widget.liveDiseaseLabel,
        liveRgb: widget.liveRgb,
        liveLab: widget.liveLab,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final activeIndex = _getActiveTierIndex();
    final tiers = _currentTiers;

    return Container(
      width: 50,
      padding: const EdgeInsets.symmetric(vertical: 5),
      decoration: BoxDecoration(
        color: const Color(0xDD121620),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.white24, width: 1.2),
        boxShadow: const [
          BoxShadow(color: Colors.black45, blurRadius: 10, offset: Offset(0, 4)),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // 1. Shape selection
          _buildIconButton(
            icon: Icons.crop_square,
            isSelected: widget.selectedShape == RoiShape.rectangle,
            tooltip: 'กรอบสี่เหลี่ยม',
            onTap: () => widget.onShapeChanged(RoiShape.rectangle),
          ),
          _buildIconButton(
            icon: Icons.circle_outlined,
            isSelected: widget.selectedShape == RoiShape.circle,
            tooltip: 'กรอบวงกลม',
            onTap: () => widget.onShapeChanged(RoiShape.circle),
          ),
          _buildIconButton(
            icon: Icons.gesture,
            isSelected: widget.selectedShape == RoiShape.freeform,
            tooltip: 'วาดกรอบอิสระ',
            onTap: () => widget.onShapeChanged(RoiShape.freeform),
          ),
          const Divider(color: Colors.white24, height: 8, indent: 6, endIndent: 6),

          // 2. Size adjustments
          _buildIconButton(
            icon: Icons.add_circle_outline,
            tooltip: 'ขยายกรอบ ROI',
            onTap: () {
              if (widget.roiSize < 340.0) {
                widget.onSizeChanged(widget.roiSize + 20.0);
              }
            },
          ),
          _buildIconButton(
            icon: Icons.remove_circle_outline,
            tooltip: 'ย่อกรอบ ROI',
            onTap: () {
              if (widget.roiSize > 80.0) {
                widget.onSizeChanged(widget.roiSize - 20.0);
              }
            },
          ),
          const Divider(color: Colors.white24, height: 8, indent: 6, endIndent: 6),

          // 3. Academic Scale Mode Switcher
          GestureDetector(
            onTap: () {
              // Cycle through modes: SPAD -> N -> P -> K -> Disease -> SPAD
              final nextMode = ScaleMode.values[(widget.scaleMode.index + 1) % (ScaleMode.values.length - 1)];
              widget.onScaleModeChanged(nextMode);
            },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 2),
              decoration: BoxDecoration(
                color: const Color(0xFF2E7D32),
                borderRadius: BorderRadius.circular(5),
              ),
              child: Text(
                widget.scaleMode == ScaleMode.spad
                    ? 'SPAD'
                    : (widget.scaleMode == ScaleMode.nitrogen
                        ? 'N'
                        : (widget.scaleMode == ScaleMode.phosphorus
                            ? 'P'
                            : (widget.scaleMode == ScaleMode.potassium ? 'K' : 'โรค'))),
                style: const TextStyle(color: Colors.white, fontSize: 8.5, fontWeight: FontWeight.bold),
              ),
            ),
          ),
          const SizedBox(height: 5),

          // 4. Vertical Academic Color Strip with Neon Glow & Live Pointer Badge
          GestureDetector(
            key: const Key('color_scale_strip'),
            onTap: _openLegendSheet,
            child: Stack(
              clipBehavior: Clip.none,
              alignment: Alignment.center,
              children: [
                Container(
                  width: 20,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: Colors.white38),
                    color: Colors.black45,
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: List.generate(tiers.length, (i) {
                      final isCurrent = i == activeIndex;
                      return AnimatedContainer(
                        duration: const Duration(milliseconds: 250),
                        width: isCurrent ? 20 : 15,
                        height: isCurrent ? 16 : 11,
                        margin: const EdgeInsets.symmetric(vertical: 0.8),
                        decoration: BoxDecoration(
                          color: tiers[i].color,
                          borderRadius: BorderRadius.circular(3),
                          boxShadow: isCurrent
                              ? [
                                  BoxShadow(
                                    color: tiers[i].color.withOpacity(0.9),
                                    blurRadius: 8,
                                    spreadRadius: 1.5,
                                  ),
                                ]
                              : null,
                          border: isCurrent
                              ? Border.all(color: Colors.white, width: 1.2)
                              : null,
                        ),
                      );
                    }),
                  ),
                ),

                // Live Pointer Badge on the left
                Positioned(
                  left: -105,
                  top: (activeIndex * 13.0) + 1,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                    decoration: BoxDecoration(
                      color: Colors.black.withOpacity(0.85),
                      borderRadius: BorderRadius.circular(4),
                      border: Border.all(
                        color: tiers[activeIndex.clamp(0, tiers.length - 1)].color,
                        width: 1.2,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: tiers[activeIndex.clamp(0, tiers.length - 1)].color.withOpacity(0.5),
                          blurRadius: 6,
                        ),
                      ],
                    ),
                    child: Text(
                      _getLiveTagText(),
                      style: TextStyle(
                        color: tiers[activeIndex.clamp(0, tiers.length - 1)].color,
                        fontSize: 9.5,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 5),
          // Info icon to tap
          GestureDetector(
            onTap: _openLegendSheet,
            child: const Icon(Icons.info_outline, color: Colors.white70, size: 15),
          ),
        ],
      ),
    );
  }

  Widget _buildIconButton({
    required IconData icon,
    bool isSelected = false,
    required String tooltip,
    required VoidCallback onTap,
  }) {
    return Tooltip(
      message: tooltip,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          width: 32,
          height: 32,
          margin: const EdgeInsets.symmetric(vertical: 1.5),
          decoration: BoxDecoration(
            color: isSelected ? const Color(0xFF2E7D32) : Colors.transparent,
            shape: BoxShape.circle,
          ),
          child: Icon(
            icon,
            size: 18,
            color: isSelected ? Colors.white : Colors.white70,
          ),
        ),
      ),
    );
  }
}
