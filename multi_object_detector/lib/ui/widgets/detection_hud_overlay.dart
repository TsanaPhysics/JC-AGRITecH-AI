import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../services/camera_service.dart';
import '../../services/object_detection_service.dart';
import '../theme/app_theme.dart';

class DetectionHudOverlay extends StatelessWidget {
  final VoidCallback onOpenSettings;
  final VoidCallback onOpenHistory;

  const DetectionHudOverlay({
    super.key,
    required this.onOpenSettings,
    required this.onOpenHistory,
  });

  @override
  Widget build(BuildContext context) {
    final detector = context.watch<ObjectDetectionService>();
    final camera = context.watch<CameraService>();
    final stats = detector.statistics;

    return SafeArea(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisSize: MainAxisSize.min,
          children: [
            // Top App Bar Controls
            Row(
              children: [
                // Brand / Title Badge
                Flexible(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: const Color(0xDD0B1220),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(
                        color: AppTheme.primaryCyan.withValues(alpha: 0.6),
                        width: 1.2,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: AppTheme.primaryCyan.withValues(alpha: 0.15),
                          blurRadius: 8,
                        ),
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          width: 8,
                          height: 8,
                          decoration: const BoxDecoration(
                            color: AppTheme.accentGreen,
                            shape: BoxShape.circle,
                          ),
                        ),
                        SizedBox(width: 6),
                        Flexible(
                          child: Text(
                            'MULTI-OBJECT AI',
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 12,
                              fontWeight: FontWeight.w900,
                              letterSpacing: 0.8,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(width: 6),

                // Flashlight toggle
                _buildCompactButton(
                  onPressed: camera.toggleTorch,
                  icon: camera.isTorchOn ? Icons.flash_on : Icons.flash_off,
                  color: camera.isTorchOn ? AppTheme.accentAmber : Colors.white70,
                ),
                const SizedBox(width: 6),

                // Switch Camera toggle
                _buildCompactButton(
                  onPressed: camera.switchCamera,
                  icon: Icons.flip_camera_ios,
                  color: Colors.white,
                ),
                const SizedBox(width: 6),

                // Settings modal toggle
                _buildCompactButton(
                  onPressed: onOpenSettings,
                  icon: Icons.tune,
                  color: AppTheme.primaryCyan,
                ),
              ],
            ),

            const SizedBox(height: 8),

            // Performance & Object Count HUD Strip
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 8),
              decoration: BoxDecoration(
                color: const Color(0xCC090E1A),
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppTheme.border),
              ),
              child: Row(
                children: [
                  // Objects Detected Count
                  Expanded(
                    child: _buildMetricCell(
                      icon: Icons.filter_center_focus,
                      iconColor: AppTheme.primaryCyan,
                      label: 'ตรวจพบ',
                      value: '${stats.totalObjects} ชิ้น',
                      highlightColor: stats.totalObjects > 0 ? AppTheme.primaryCyan : Colors.white54,
                    ),
                  ),
                  Container(width: 1, height: 24, color: AppTheme.border),

                  // FPS
                  Expanded(
                    child: _buildMetricCell(
                      icon: Icons.speed,
                      iconColor: AppTheme.accentGreen,
                      label: 'FPS',
                      value: stats.fps.toStringAsFixed(1),
                      highlightColor: AppTheme.accentGreen,
                    ),
                  ),
                  Container(width: 1, height: 24, color: AppTheme.border),

                  // Latency
                  Expanded(
                    child: _buildMetricCell(
                      icon: Icons.bolt,
                      iconColor: AppTheme.accentAmber,
                      label: 'ความเร็ว',
                      value: '${stats.inferenceTimeMs}ms',
                      highlightColor: AppTheme.accentAmber,
                    ),
                  ),
                  Container(width: 1, height: 24, color: AppTheme.border),

                  // Engine
                  Expanded(
                    child: _buildMetricCell(
                      icon: Icons.memory,
                      iconColor: AppTheme.accentPurple,
                      label: 'AI Engine',
                      value: detector.isTfliteLoaded ? 'TFLite' : 'Edge AI',
                      highlightColor: AppTheme.accentPurple,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCompactButton({
    required VoidCallback onPressed,
    required IconData icon,
    required Color color,
  }) {
    return Container(
      width: 36,
      height: 36,
      decoration: BoxDecoration(
        color: const Color(0xAA121826),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: AppTheme.border, width: 1),
      ),
      child: IconButton(
        padding: EdgeInsets.zero,
        constraints: const BoxConstraints(),
        onPressed: onPressed,
        icon: Icon(icon, color: color, size: 18),
      ),
    );
  }

  Widget _buildMetricCell({
    required IconData icon,
    required Color iconColor,
    required String label,
    required String value,
    required Color highlightColor,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      mainAxisSize: MainAxisSize.min,
      children: [
        Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 12, color: iconColor),
            const SizedBox(width: 4),
            Text(
              label,
              style: const TextStyle(
                color: Colors.white60,
                fontSize: 9.5,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
        const SizedBox(height: 2),
        Text(
          value,
          style: TextStyle(
            color: highlightColor,
            fontSize: 12.5,
            fontWeight: FontWeight.w900,
          ),
        ),
      ],
    );
  }
}
