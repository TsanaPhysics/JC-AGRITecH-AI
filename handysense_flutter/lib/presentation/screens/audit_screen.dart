import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/farm_models.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/common.dart';

/// บันทึกการใช้งาน (audit log) — ใครสั่งอะไร เมื่อไร จากช่องทางใด
class AuditScreen extends StatefulWidget {
  const AuditScreen({super.key});
  @override
  State<AuditScreen> createState() => _AuditScreenState();
}

class _AuditScreenState extends State<AuditScreen> {
  List<AuditEntry>? _log;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final l = await context.read<TelemetryViewModel>().repository.getAudit(limit: 100);
      if (!mounted) return;
      setState(() {
        _log = l;
        _error = null;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() => _error = e.toString().replaceFirst('Exception: ', ''));
    }
  }

  static String _srcLabel(String s) {
    switch (s) {
      case 'app':
        return 'แอป';
      case 'web':
        return 'เว็บ';
      case 'mobile':
        return 'มือถือ (เว็บ)';
      case 'schedule':
        return 'ตารางเวลา';
      case 'server':
        return 'ระบบ';
      case 'board':
        return 'บอร์ด';
      default:
        return s.isEmpty ? '-' : s;
    }
  }

  static IconData _icon(String type) {
    switch (type) {
      case 'relay':
        return Icons.toggle_on_rounded;
      case 'mode':
        return Icons.tune_rounded;
      case 'timer_off':
        return Icons.timer_off_outlined;
      case 'schedule':
      case 'schedule_save':
      case 'schedule_delete':
        return Icons.event_repeat_rounded;
      case 'profile':
      case 'profile_save':
        return Icons.eco_rounded;
      default:
        return Icons.info_outline_rounded;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.scaffoldDark,
      appBar: AppBar(
        backgroundColor: const Color(0xFF0C1322),
        title: Text('บันทึกการใช้งาน', style: thText(18, weight: FontWeight.w700, color: AppColors.textWhite)),
      ),
      body: _log == null && _error == null
          ? const Center(child: CircularProgressIndicator())
          : _error != null && _log == null
              ? Center(
                  child: Column(mainAxisSize: MainAxisSize.min, children: [
                    Text(_error!, style: thText(14)),
                    const SizedBox(height: 10),
                    FilledButton(onPressed: _load, child: Text('ลองใหม่', style: thText(14, weight: FontWeight.w700, color: Colors.black))),
                  ]),
                )
              : RefreshIndicator(
                  onRefresh: _load,
                  child: (_log ?? []).isEmpty
                      ? ListView(children: [
                          Padding(
                            padding: const EdgeInsets.all(40),
                            child: Center(child: Text('ยังไม่มีบันทึก', style: thText(15, color: AppColors.textMuted))),
                          ),
                        ])
                      : ListView.separated(
                          padding: const EdgeInsets.all(16),
                          itemCount: _log!.length,
                          separatorBuilder: (context, index) => const SizedBox(height: 8),
                          itemBuilder: (_, i) {
                            final e = _log![i];
                            return AppCard(
                              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                              child: Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Icon(_icon(e.type), color: AppColors.neonCyan, size: 22),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(e.detail, style: thText(14, weight: FontWeight.w600, color: AppColors.textWhite)),
                                        const SizedBox(height: 2),
                                        Text('${e.time} · ${_srcLabel(e.src)}',
                                            style: thText(12, color: AppColors.textMuted)),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            );
                          },
                        ),
                ),
    );
  }
}
