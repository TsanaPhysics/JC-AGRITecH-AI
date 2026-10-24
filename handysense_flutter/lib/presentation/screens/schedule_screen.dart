import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/farm_models.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/common.dart';

/// ตารางเวลาเปิด/ปิดรีเลย์ประจำวัน (เก็บและประมวลผลที่เซิร์ฟเวอร์ — ทำงานแม้ปิดแอป)
class ScheduleScreen extends StatefulWidget {
  const ScheduleScreen({super.key});
  @override
  State<ScheduleScreen> createState() => _ScheduleScreenState();
}

class _ScheduleScreenState extends State<ScheduleScreen> {
  List<ScheduleItem>? _items;
  String? _error;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final list = await context.read<TelemetryViewModel>().repository.getSchedules();
      if (!mounted) return;
      setState(() => _items = list);
    } catch (e) {
      if (!mounted) return;
      setState(() => _error = e.toString().replaceFirst('Exception: ', ''));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<void> _edit([ScheduleItem? item]) async {
    final vm = context.read<TelemetryViewModel>();
    final result = await showModalBottomSheet<Map<String, dynamic>>(
      context: context,
      isScrollControlled: true,
      backgroundColor: AppColors.darkCard,
      showDragHandle: true,
      builder: (_) => _ScheduleEditor(initial: item, relayNames: {
        for (int i = 1; i <= 4; i++) i: relayShortName(vm.relayView(i).item.name),
      }),
    );
    if (result == null || !mounted) return;
    final r = await vm.repository.saveSchedule(result);
    if (!mounted) return;
    showAppSnack(context, r.ok ? 'บันทึกตารางเวลาแล้ว' : r.message, error: !r.ok);
    if (r.ok) _load();
  }

  Future<void> _toggle(ScheduleItem s, bool enabled) async {
    final vm = context.read<TelemetryViewModel>();
    final r = await vm.repository.saveSchedule({
      'id': s.id,
      'relay': s.relay,
      'time': s.time,
      'days': s.days,
      'duration': s.durationMin,
      'action_type': s.turnOn ? 'on' : 'off',
      'enabled': enabled ? 1 : 0,
      'name': s.name,
    });
    if (!mounted) return;
    if (!r.ok) showAppSnack(context, r.message, error: true);
    _load();
  }

  Future<void> _delete(ScheduleItem s) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkCard,
        title: Text('ลบตารางเวลานี้?', style: thText(17, weight: FontWeight.w700, color: AppColors.textWhite)),
        content: Text('รีเลย์ ${s.relay} เวลา ${s.time}', style: thText(14)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: Text('ยกเลิก', style: thText(14))),
          FilledButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: FilledButton.styleFrom(backgroundColor: AppColors.alertRed),
            child: Text('ลบ', style: thText(14, weight: FontWeight.w700, color: Colors.white)),
          ),
        ],
      ),
    );
    if (ok != true || !mounted) return;
    final r = await context.read<TelemetryViewModel>().repository.deleteSchedule(s.id);
    if (!mounted) return;
    showAppSnack(context, r.ok ? 'ลบแล้ว' : r.message, error: !r.ok);
    _load();
  }

  String _daysText(List<int> d) {
    if (d.length == 7) return 'ทุกวัน';
    final sorted = [...d]..sort();
    return sorted.map((e) => thaiDays[e.clamp(0, 6)]).join(' ');
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    return Scaffold(
      backgroundColor: AppColors.scaffoldDark,
      appBar: AppBar(
        backgroundColor: const Color(0xFF0C1322),
        title: Text('ตารางเวลา', style: thText(18, weight: FontWeight.w700, color: AppColors.textWhite)),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _edit(),
        backgroundColor: AppColors.neonCyan,
        foregroundColor: Colors.black,
        icon: const Icon(Icons.add_rounded),
        label: Text('เพิ่มตารางเวลา', style: thText(14, weight: FontWeight.w700, color: Colors.black)),
      ),
      body: _loading && _items == null
          ? const Center(child: CircularProgressIndicator())
          : _error != null && _items == null
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(mainAxisSize: MainAxisSize.min, children: [
                      const Icon(Icons.cloud_off_rounded, size: 40, color: AppColors.alertRed),
                      const SizedBox(height: 10),
                      Text(_error!, style: thText(14), textAlign: TextAlign.center),
                      const SizedBox(height: 12),
                      FilledButton(onPressed: _load, child: Text('ลองใหม่', style: thText(14, weight: FontWeight.w700, color: Colors.black))),
                    ]),
                  ),
                )
              : RefreshIndicator(
                  onRefresh: _load,
                  child: (_items ?? []).isEmpty
                      ? ListView(children: [
                          Padding(
                            padding: const EdgeInsets.all(40),
                            child: Column(children: [
                              const Icon(Icons.event_repeat_rounded, size: 48, color: AppColors.textDim),
                              const SizedBox(height: 12),
                              Text('ยังไม่มีตารางเวลา', style: thText(16, weight: FontWeight.w700, color: AppColors.textWhite)),
                              const SizedBox(height: 4),
                              Text('กด "เพิ่มตารางเวลา" เพื่อตั้งเวลารดน้ำอัตโนมัติ',
                                  style: thText(13, color: AppColors.textMuted), textAlign: TextAlign.center),
                            ]),
                          ),
                        ])
                      : ListView.separated(
                          padding: const EdgeInsets.fromLTRB(16, 16, 16, 96),
                          itemCount: _items!.length + 1,
                          separatorBuilder: (context, index) => const SizedBox(height: 10),
                          itemBuilder: (_, i) {
                            if (i == _items!.length) {
                              return Padding(
                                padding: const EdgeInsets.only(top: 6),
                                child: Text(
                                  'ตารางเวลาทำงานที่เซิร์ฟเวอร์ตามเวลาประเทศไทย (ตรวจทุกครั้งที่บอร์ดเรียกขอคำสั่ง) '
                                  'จึงต้องมีบอร์ดออนไลน์ ณ เวลานั้น',
                                  style: thText(12, color: AppColors.textMuted),
                                ),
                              );
                            }
                            final s = _items![i];
                            final name = relayShortName(vm.relayView(s.relay).item.name);
                            return AppCard(
                              borderColor: s.enabled ? AppColors.borderGlow : AppColors.borderDark,
                              child: Row(
                                children: [
                                  Expanded(
                                    child: InkWell(
                                      onTap: () => _edit(s),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(s.time,
                                              style: thText(26, weight: FontWeight.w800,
                                                  color: s.enabled ? AppColors.textWhite : AppColors.textDim)),
                                          Text('${s.turnOn ? 'เปิด' : 'ปิด'} รีเลย์ ${s.relay} · $name',
                                              style: thText(14, weight: FontWeight.w600)),
                                          Text(
                                              '${_daysText(s.days)}'
                                              '${s.turnOn && s.durationMin > 0 ? ' · ปิดเองใน ${s.durationMin} นาที' : ''}',
                                              style: thText(12.5, color: AppColors.textMuted)),
                                        ],
                                      ),
                                    ),
                                  ),
                                  Column(
                                    children: [
                                      Switch(value: s.enabled, onChanged: (v) => _toggle(s, v)),
                                      IconButton(
                                        tooltip: 'ลบ',
                                        onPressed: () => _delete(s),
                                        icon: const Icon(Icons.delete_outline_rounded, color: AppColors.alertRed),
                                        constraints: const BoxConstraints(minWidth: 48, minHeight: 48),
                                      ),
                                    ],
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

class _ScheduleEditor extends StatefulWidget {
  const _ScheduleEditor({required this.initial, required this.relayNames});
  final ScheduleItem? initial;
  final Map<int, String> relayNames;
  @override
  State<_ScheduleEditor> createState() => _ScheduleEditorState();
}

class _ScheduleEditorState extends State<_ScheduleEditor> {
  late int _relay;
  late TimeOfDay _time;
  late Set<int> _days;
  late bool _turnOn;
  late int _duration;

  @override
  void initState() {
    super.initState();
    final s = widget.initial;
    _relay = s?.relay ?? 1;
    final parts = (s?.time ?? '06:00').split(':');
    _time = TimeOfDay(hour: int.tryParse(parts[0]) ?? 6, minute: int.tryParse(parts.length > 1 ? parts[1] : '0') ?? 0);
    _days = {...(s?.days ?? [0, 1, 2, 3, 4, 5, 6])};
    _turnOn = s?.turnOn ?? true;
    _duration = s?.durationMin ?? 10;
  }

  String get _timeText =>
      '${_time.hour.toString().padLeft(2, '0')}:${_time.minute.toString().padLeft(2, '0')}';

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(20, 4, 20, 20 + MediaQuery.of(context).viewInsets.bottom),
      child: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(widget.initial == null ? 'เพิ่มตารางเวลา' : 'แก้ไขตารางเวลา',
                style: thText(18, weight: FontWeight.w700, color: AppColors.textWhite)),
            const SizedBox(height: 16),
            Text('รีเลย์', style: thText(13, color: AppColors.textMuted)),
            const SizedBox(height: 6),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                for (final e in widget.relayNames.entries)
                  ChoiceChip(
                    label: Text('${e.key}. ${e.value}', style: thText(13, weight: FontWeight.w600, color: AppColors.textWhite)),
                    selected: _relay == e.key,
                    onSelected: (_) => setState(() => _relay = e.key),
                  ),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: SegmentedButton<bool>(
                    segments: [
                      ButtonSegment(value: true, label: Text('เปิด', style: thText(14, weight: FontWeight.w600))),
                      ButtonSegment(value: false, label: Text('ปิด', style: thText(14, weight: FontWeight.w600))),
                    ],
                    selected: {_turnOn},
                    onSelectionChanged: (v) => setState(() => _turnOn = v.first),
                  ),
                ),
                const SizedBox(width: 12),
                OutlinedButton.icon(
                  onPressed: () async {
                    final t = await showTimePicker(context: context, initialTime: _time);
                    if (t != null) setState(() => _time = t);
                  },
                  icon: const Icon(Icons.schedule_rounded),
                  label: Text(_timeText, style: thText(18, weight: FontWeight.w800, color: AppColors.textWhite)),
                  style: OutlinedButton.styleFrom(minimumSize: const Size(110, 48)),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Text('วันที่ทำงาน', style: thText(13, color: AppColors.textMuted)),
            const SizedBox(height: 6),
            Wrap(
              spacing: 6,
              runSpacing: 6,
              children: [
                for (int d = 0; d < 7; d++)
                  FilterChip(
                    label: Text(thaiDays[d], style: thText(13, weight: FontWeight.w600, color: AppColors.textWhite)),
                    selected: _days.contains(d),
                    onSelected: (v) => setState(() => v ? _days.add(d) : _days.remove(d)),
                  ),
              ],
            ),
            if (_turnOn) ...[
              const SizedBox(height: 16),
              Text(_duration == 0 ? 'ไม่ปิดอัตโนมัติ' : 'ปิดอัตโนมัติหลัง $_duration นาที',
                  style: thText(13, color: AppColors.textMuted)),
              Slider(
                value: _duration.toDouble(),
                min: 0,
                max: 120,
                divisions: 24,
                label: _duration == 0 ? 'ไม่ปิด' : '$_duration นาที',
                onChanged: (v) => setState(() => _duration = v.round()),
              ),
            ],
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              height: 52,
              child: FilledButton(
                onPressed: _days.isEmpty
                    ? null
                    : () => Navigator.pop(context, {
                          if (widget.initial != null) 'id': widget.initial!.id,
                          'relay': _relay,
                          'time': _timeText,
                          'days': ([..._days]..sort()),
                          'duration': _turnOn ? _duration : 0,
                          'action_type': _turnOn ? 'on' : 'off',
                          'enabled': widget.initial?.enabled == false ? 0 : 1,
                        }),
                child: Text('บันทึก', style: thText(16, weight: FontWeight.w800, color: Colors.black)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
