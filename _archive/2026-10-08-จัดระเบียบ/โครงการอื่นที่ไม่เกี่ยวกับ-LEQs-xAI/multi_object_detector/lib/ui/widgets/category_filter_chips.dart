import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/detected_object.dart';
import '../../services/object_detection_service.dart';
import '../theme/app_theme.dart';

class CategoryFilterChips extends StatelessWidget {
  const CategoryFilterChips({super.key});

  @override
  Widget build(BuildContext context) {
    final detector = context.watch<ObjectDetectionService>();
    final selected = detector.selectedCategory;

    return SizedBox(
      height: 38,
      child: ListView.separated(
        padding: const EdgeInsets.symmetric(horizontal: 16.0),
        scrollDirection: Axis.horizontal,
        itemCount: ObjectCategoryGroup.values.length,
        separatorBuilder: (_, __) => const SizedBox(width: 8),
        itemBuilder: (context, index) {
          final cat = ObjectCategoryGroup.values[index];
          final bool isSelected = cat == selected;

          return FilterChip(
            selected: isSelected,
            showCheckmark: false,
            avatar: Icon(
              cat.icon,
              size: 15,
              color: isSelected ? Colors.black87 : cat.themeColor,
            ),
            label: Text(
              cat.label,
              style: TextStyle(
                fontSize: 12,
                fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                color: isSelected ? Colors.black : Colors.white70,
              ),
            ),
            backgroundColor: const Color(0xCC111827),
            selectedColor: cat.themeColor,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(20),
              side: BorderSide(
                color: isSelected ? cat.themeColor : AppTheme.border,
                width: 1.2,
              ),
            ),
            onSelected: (_) {
              detector.setSelectedCategory(cat);
            },
          );
        },
      ),
    );
  }
}
