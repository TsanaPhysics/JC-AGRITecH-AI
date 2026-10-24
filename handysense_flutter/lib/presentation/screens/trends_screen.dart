import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/farm_models.dart';
import '../view_models/telemetry_view_model.dart';
import '../widgets/common.dart';

/// แนวโน้มย้อนหลังของเซนเซอร์ (ดึงจาก get_history) พร้อมช่วงข้อมูลและส่งออก CSV
class TrendsScreen extends StatefulWidget {
  const TrendsScreen({super.key});
  @override
  State<TrendsScreen> createState() => _TrendsScreenState();
}

enum _Metric { temperature, humidity, soilMoisture, vpd }

class _TrendsScreenState extends State<TrendsScreen> {
  HistoryResult? _data;
  String? _error;
  bool _loading = true;
  int _limit = 60;
  _Metric _metric = _Metric.temperature;

  static const _ranges = {30: '30 จุด', 60: '60 จุด', 180: '180 จุด', 500: '500 จุด'};

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
      final r = await context.read<TelemetryViewModel>().repository.getHistory(limit: _limit);
      if (!mounted) return;
      setState(() => _data = r);
    } catch (e) {
      if (!mounted) return;
      setState(() => _error = e.toString().replaceFirst('Exception: ', ''));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  double _value(HistoryPoint p) {
    switch (_metric) {
      case _Metric.temperature:
        return p.temperature;
      case _Metric.humidity:
        return p.humidity;
      case _Metric.soilMoisture:
        return p.soilMoisture;
      case _Metric.vpd:
        return p.vpd;
    }
  }

  ({String label, String unit, Color color, String key}) get _meta {
    switch (_metric) {
      case _Metric.temperature:
        return (label: 'อุณหภูมิอากาศ', unit: '°C', color: AppColors.neonOrange, key: 'temp');
      case _Metric.humidity:
        return (label: 'ความชื้นอากาศ', unit: '%', color: AppColors.neonCyan, key: 'humidity');
      case _Metric.soilMoisture:
        return (label: 'ความชื้นดิน', unit: '%', color: AppColors.neonGreen, key: 'soil_moisture');
      case _Metric.vpd:
        return (label: 'VPD', unit: 'kPa', color: AppColors.neonPurple, key: 'vpd');
    }
  }

  Future<void> _copyCsv() async {
    final pts = _data?.points ?? [];
    final b = StringBuffer('time,temperature,humidity,soil_moisture,vpd\n');
    for (final p in pts) {
      b.writeln('${p.label},${p.temperature},${p.humidity},${p.soilMoisture},${p.vpd}');
    }
    await Clipboard.setData(ClipboardData(text: b.toString()));
    if (mounted) showAppSnack(context, 'คัดลอกข้อมูล CSV ${pts.length} แถวแล้ว — นำไปวางใน Excel/Sheets ได้');
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final meta = _meta;
    final pts = _data?.points ?? const <HistoryPoint>[];
    final range = vm.profile.thresholds[meta.key];

    final spots = <FlSpot>[for (int i = 0; i < pts.length; i++) FlSpot(i.toDouble(), _value(pts[i]))];
    double minY = 0, maxY = 1;
    if (spots.isNotEmpty) {
      final vals = spots.map((e) => e.y).toList();
      var lo = vals.reduce((a, b) => a < b ? a : b);
      var hi = vals.reduce((a, b) => a > b ? a : b);
      if (range != null) {
        if (range.min < lo) lo = range.min;
        if (range.max > hi) hi = range.max;
      }
      final pad = (hi - lo) * 0.12 + 0.1;
      minY = lo - pad;
      maxY = hi + pad;
    }

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
        children: [
          Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 900),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SectionTitle(
                    'แนวโน้มย้อนหลัง',
                    subtitle: 'โปรไฟล์พืช: ${vm.profile.name}',
                    trailing: IconButton(
                      tooltip: 'คัดลอก CSV',
                      onPressed: pts.isEmpty ? null : _copyCsv,
                      icon: const Icon(Icons.copy_all_rounded, color: AppColors.neonCyan),
                      constraints: const BoxConstraints(minWidth: 48, minHeight: 48),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      for (final m in _Metric.values)
                        ChoiceChip(
                          label: Text(
                              const {
                                _Metric.temperature: 'อุณหภูมิ',
                                _Metric.humidity: 'ความชื้นอากาศ',
                                _Metric.soilMoisture: 'ความชื้นดิน',
                                _Metric.vpd: 'VPD',
                              }[m]!,
                              style: thText(13, weight: FontWeight.w600, color: AppColors.textWhite)),
                          selected: _metric == m,
                          onSelected: (_) => setState(() => _metric = m),
                        ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Wrap(
                    spacing: 8,
                    children: [
                      for (final e in _ranges.entries)
                        ChoiceChip(
                          label: Text(e.value, style: thText(12.5, weight: FontWeight.w600, color: AppColors.textWhite)),
                          selected: _limit == e.key,
                          onSelected: (_) {
                            setState(() => _limit = e.key);
                            _load();
                          },
                        ),
                    ],
                  ),
                  const SizedBox(height: 14),
                  if (_data?.simulated == true)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: AppCard(
                        borderColor: AppColors.alertAmber,
                        child: Row(children: [
                          const Icon(Icons.science_outlined, color: AppColors.alertAmber),
                          const SizedBox(width: 10),
                          Expanded(
                            child: Text('เซิร์ฟเวอร์ยังไม่มีข้อมูลย้อนหลังจริง — กราฟนี้เป็นค่าจำลองเพื่อการแสดงผลเท่านั้น',
                                style: thText(13, weight: FontWeight.w600, color: AppColors.alertAmber)),
                          ),
                        ]),
                      ),
                    ),
                  AppCard(
                    child: SizedBox(
                      height: 260,
                      child: _loading && _data == null
                          ? const Center(child: CircularProgressIndicator())
                          : _error != null && _data == null
                              ? Center(child: Text(_error!, style: thText(14), textAlign: TextAlign.center))
                              : spots.isEmpty
                                  ? Center(child: Text('ไม่มีข้อมูล', style: thText(14, color: AppColors.textMuted)))
                                  : LineChart(
                                      LineChartData(
                                        minY: minY,
                                        maxY: maxY,
                                        gridData: FlGridData(
                                          show: true,
                                          drawVerticalLine: false,
                                          getDrawingHorizontalLine: (_) =>
                                              const FlLine(color: AppColors.divider, strokeWidth: 1),
                                        ),
                                        borderData: FlBorderData(show: false),
                                        titlesData: FlTitlesData(
                                          topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                                          rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                                          bottomTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                                          leftTitles: AxisTitles(
                                            sideTitles: SideTitles(
                                              showTitles: true,
                                              reservedSize: 40,
                                              getTitlesWidget: (v, m) => Text(v.toStringAsFixed(meta.unit == 'kPa' ? 1 : 0),
                                                  style: thText(11, color: AppColors.textMuted)),
                                            ),
                                          ),
                                        ),
                                        extraLinesData: range == null
                                            ? null
                                            : ExtraLinesData(horizontalLines: [
                                                HorizontalLine(
                                                    y: range.min,
                                                    color: AppColors.alertAmber.withAlpha(150),
                                                    strokeWidth: 1,
                                                    dashArray: [6, 4]),
                                                HorizontalLine(
                                                    y: range.max,
                                                    color: AppColors.alertAmber.withAlpha(150),
                                                    strokeWidth: 1,
                                                    dashArray: [6, 4]),
                                              ]),
                                        lineTouchData: LineTouchData(
                                          touchTooltipData: LineTouchTooltipData(
                                            getTooltipItems: (items) => [
                                              for (final t in items)
                                                LineTooltipItem(
                                                  '${t.y.toStringAsFixed(2)} ${meta.unit}\n${pts[t.x.toInt()].label}',
                                                  thText(12, weight: FontWeight.w600, color: Colors.white),
                                                ),
                                            ],
                                          ),
                                        ),
                                        lineBarsData: [
                                          LineChartBarData(
                                            spots: spots,
                                            isCurved: true,
                                            curveSmoothness: 0.2,
                                            color: meta.color,
                                            barWidth: 2.5,
                                            dotData: const FlDotData(show: false),
                                            belowBarData: BarAreaData(show: true, color: meta.color.withAlpha(30)),
                                          ),
                                        ],
                                      ),
                                    ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  if (spots.isNotEmpty) _Stats(values: spots.map((e) => e.y).toList(), unit: meta.unit),
                  if (range != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 10),
                      child: Text(
                          'เส้นประ = ช่วงที่เหมาะสมของโปรไฟล์ ${vm.profile.name} (${range.min}–${range.max} ${meta.unit})',
                          style: thText(12, color: AppColors.textMuted)),
                    ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _Stats extends StatelessWidget {
  const _Stats({required this.values, required this.unit});
  final List<double> values;
  final String unit;

  @override
  Widget build(BuildContext context) {
    final lo = values.reduce((a, b) => a < b ? a : b);
    final hi = values.reduce((a, b) => a > b ? a : b);
    final avg = values.reduce((a, b) => a + b) / values.length;
    Widget cell(String t, double v) => Expanded(
          child: AppCard(
            padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 10),
            child: Column(children: [
              Text(t, style: thText(12, color: AppColors.textMuted)),
              const SizedBox(height: 2),
              Text(v.toStringAsFixed(1), style: thText(18, weight: FontWeight.w800, color: AppColors.textWhite)),
              Text(unit, style: thText(11, color: AppColors.textMuted)),
            ]),
          ),
        );
    return Row(children: [
      cell('ต่ำสุด', lo),
      const SizedBox(width: 8),
      cell('เฉลี่ย', avg),
      const SizedBox(width: 8),
      cell('สูงสุด', hi),
    ]);
  }
}
