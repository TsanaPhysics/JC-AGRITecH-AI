import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/app_theme.dart';
import '../view_models/telemetry_view_model.dart';

// ─────────────────────────────────────────────────────────────
// ⚙️ SettingsScreen — Cyber Dark Sci-Fi Configuration UI
// ─────────────────────────────────────────────────────────────
class SettingsScreen extends StatefulWidget {
  const SettingsScreen({super.key});
  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen>
    with SingleTickerProviderStateMixin {
  late TextEditingController _ipController;
  late TextEditingController _portController;
  late TextEditingController _apiUrlController;
  late AnimationController _pingAnim;
  bool _isPinging = false;

  @override
  void initState() {
    super.initState();
    final vm = context.read<TelemetryViewModel>();
    _ipController = TextEditingController(text: vm.currentBoardIp);
    _portController =
        TextEditingController(text: vm.currentBoardPort.toString());
    _apiUrlController = TextEditingController(text: vm.currentLocalApiUrl);
    _pingAnim = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 1),
    );
  }

  @override
  void dispose() {
    _ipController.dispose();
    _portController.dispose();
    _apiUrlController.dispose();
    _pingAnim.dispose();
    super.dispose();
  }

  Future<void> _handlePing() async {
    if (_isPinging) return;
    HapticFeedback.mediumImpact();
    setState(() => _isPinging = true);
    _pingAnim.repeat();
    final vm = context.read<TelemetryViewModel>();
    final messenger = ScaffoldMessenger.of(context);
    await vm.fetchTelemetry(silent: false);
    if (!mounted) return;
    _pingAnim.stop();
    _pingAnim.reset();
    setState(() => _isPinging = false);
    _showLatencySnackbar(messenger, vm.boardLatencyMs);
  }

  void _showLatencySnackbar(ScaffoldMessengerState m, int? ms) {
    m.showSnackBar(SnackBar(
      content: Text(
        ms != null
            ? '⚡ Latency: $ms ms — บอร์ด ESP32 ตอบสนองปกติ'
            : '❌ ไม่สามารถ ping บอร์ดได้ — ตรวจสอบ IP และเครือข่าย',
        style: GoogleFonts.rajdhani(
          fontWeight: FontWeight.w700,
          color: Colors.white,
          fontSize: 14,
        ),
      ),
      backgroundColor: ms != null ? const Color(0xFF00381B) : AppColors.alertRed,
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(14),
        side: BorderSide(
          color: ms != null ? AppColors.neonGreen : AppColors.alertRed,
          width: 1.2,
        ),
      ),
      margin: const EdgeInsets.all(16),
    ));
  }

  void _saveSettings() {
    final ip = _ipController.text.trim();
    final port = int.tryParse(_portController.text.trim()) ?? 8500;
    final apiUrl = _apiUrlController.text.trim();
    HapticFeedback.selectionClick();

    if (ip.isEmpty || apiUrl.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(
          '⚠️ กรุณากรอก IP และ API URL ให้ครบ',
          style: GoogleFonts.rajdhani(
            fontWeight: FontWeight.w700,
            color: Colors.white,
          ),
        ),
        backgroundColor: const Color(0xFF1E1500),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
          side: const BorderSide(color: AppColors.alertAmber, width: 1.2),
        ),
        margin: const EdgeInsets.all(16),
      ));
      return;
    }

    context.read<TelemetryViewModel>().updateConnectionSettings(
          ip: ip,
          port: port,
          apiUrl: apiUrl,
        );

    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text(
        '💾 บันทึกการตั้งค่าเรียบร้อย — กำลังเชื่อมต่อใหม่',
        style: GoogleFonts.rajdhani(
          fontWeight: FontWeight.w700,
          color: Colors.white,
        ),
      ),
      backgroundColor: const Color(0xFF00223D),
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(14),
        side: const BorderSide(color: AppColors.neonCyan, width: 1.2),
      ),
      margin: const EdgeInsets.all(16),
    ));
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<TelemetryViewModel>();
    final sw = MediaQuery.of(context).size.width;
    final hPad = sw < 360 ? 12.0 : sw < 420 ? 16.0 : 20.0;

    return SingleChildScrollView(
      padding: EdgeInsets.symmetric(horizontal: hPad, vertical: 16),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 720),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Page Header ──
              _buildPageHeader(sw),
              const SizedBox(height: 20),

              // ── Connection Health ──
              _buildHealthCard(vm, sw),
              const SizedBox(height: 16),

              // ── Board LAN Card ──
              _buildSectionCard(
                title: 'การเชื่อมต่อบอร์ด ESP32-S3',
                subtitle: 'DIRECT BOARD LAN / WI-FI',
                accentColor: AppColors.neonCyan,
                sw: sw,
                children: [
                  _buildTextField(
                    controller: _ipController,
                    label: 'Board IP Address',
                    hint: '192.168.0.111',
                    icon: Icons.router_rounded,
                    accentColor: AppColors.neonCyan,
                    sw: sw,
                  ),
                  const SizedBox(height: 12),
                  _buildTextField(
                    controller: _portController,
                    label: 'Board HTTP Port',
                    hint: '8500',
                    icon: Icons.settings_ethernet_rounded,
                    accentColor: AppColors.neonCyan,
                    keyboardType: TextInputType.number,
                    sw: sw,
                  ),
                ],
              ),
              const SizedBox(height: 14),

              // ── PHP API Card ──
              _buildSectionCard(
                title: 'PHP Backend API Server',
                subtitle: 'XAMPP / APACHE SERVER URL',
                accentColor: AppColors.neonGreen,
                sw: sw,
                children: [
                  _buildTextField(
                    controller: _apiUrlController,
                    label: 'PHP API Endpoint URL',
                    hint:
                        'http://192.168.0.107/handysense/leqs-workshop/api/api.php',
                    icon: Icons.link_rounded,
                    accentColor: AppColors.neonGreen,
                    sw: sw,
                  ),
                ],
              ),
              const SizedBox(height: 22),

              // ── Save Button ──
              _buildSaveButton(sw),
              const SizedBox(height: 12),

              // ── Ping Button ──
              _buildPingButton(sw),
              const SizedBox(height: 24),

              // ── System Status ──
              _buildStatusCard(vm, sw),
              const SizedBox(height: 24),

              // ── Footer ──
              _buildFooter(sw),
              const SizedBox(height: 16),
            ],
          ),
        ),
      ),
    );
  }

  // ── Page Header ──────────────────────────────────────────
  Widget _buildPageHeader(double sw) {
    return Row(
      children: [
        Container(
          width: 44,
          height: 44,
          decoration: BoxDecoration(
            color: const Color(0xFF16233B),
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: const Color(0xFF263B60), width: 1.2),
          ),
          child: const Center(
            child: Icon(Icons.settings_suggest_rounded,
                color: AppColors.neonCyan, size: 24),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
            Text(
              'ตั้งค่าระบบ',
              style: GoogleFonts.rajdhani(
                fontSize: sw < 360 ? 17 : 20,
                fontWeight: FontWeight.w700,
                color: Colors.white,
                letterSpacing: 0.3,
              ),
            ),
            Text(
              'ESP32-S3 · LEQsxAI v1.0.0',
              style: GoogleFonts.orbitron(
                fontSize: sw < 360 ? 8.5 : 9.5,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF64748B),
                letterSpacing: 0.8,
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    ],
  );
  }

  // ── Health Card ──────────────────────────────────────────
  Widget _buildHealthCard(TelemetryViewModel vm, double sw) {
    final isOnline = vm.telemetry != null && vm.errorMessage == null;
    final latency = vm.boardLatencyMs;

    final Color statusColor;
    final String statusLabel;

    if (!isOnline) {
      statusColor = AppColors.alertRed;
      statusLabel = 'DISCONNECTED (OFFLINE)';
    } else if (latency != null && latency < 80) {
      statusColor = AppColors.neonGreen;
      statusLabel = 'EXCELLENT LINK ($latency ms)';
    } else if (latency != null && latency < 200) {
      statusColor = AppColors.neonAmber;
      statusLabel = 'STABLE LINK ($latency ms)';
    } else {
      statusColor = AppColors.alertRed;
      statusLabel = latency != null ? 'HIGH LATENCY ($latency ms)' : 'UNKNOWN';
    }

    return Container(
      padding: EdgeInsets.all(sw < 360 ? 12 : 16),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(22),
        border: Border.all(color: statusColor.withAlpha(120), width: 1.4),
        boxShadow: [
          BoxShadow(
            color: statusColor.withAlpha(25),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: 12,
            height: 12,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: statusColor,
              boxShadow: [
                BoxShadow(
                  color: statusColor.withAlpha(200),
                  blurRadius: 8,
                  spreadRadius: 1,
                ),
              ],
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'NETWORK CONNECTION STATUS',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 8.5 : 9.5,
                    fontWeight: FontWeight.w700,
                    color: const Color(0xFF64748B),
                    letterSpacing: 0.8,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  statusLabel,
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 14 : 16.5,
                    fontWeight: FontWeight.w700,
                    color: statusColor,
                    letterSpacing: 0.4,
                  ),
                ),
              ],
            ),
          ),
          if (vm.telemetry != null)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
              decoration: BoxDecoration(
                color: AppColors.neonCyan.withAlpha(20),
                borderRadius: BorderRadius.circular(10),
                border: Border.all(
                  color: AppColors.neonCyan.withAlpha(80),
                  width: 1,
                ),
              ),
              child: Column(
                children: [
                  Text(
                    '${vm.telemetry!.board.rssi}',
                    style: GoogleFonts.orbitron(
                      fontSize: sw < 360 ? 15 : 17,
                      fontWeight: FontWeight.w800,
                      color: AppColors.neonCyanLight,
                      height: 1,
                    ),
                  ),
                  Text(
                    'dBm',
                    style: GoogleFonts.orbitron(
                      fontSize: 8,
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF8EA2C6),
                    ),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }

  // ── Section Card ─────────────────────────────────────────
  Widget _buildSectionCard({
    required String title,
    required String subtitle,
    required Color accentColor,
    required double sw,
    required List<Widget> children,
  }) {
    return Container(
      padding: EdgeInsets.all(sw < 360 ? 14 : 18),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFF1B2842), width: 1.4),
        boxShadow: const [
          BoxShadow(
            color: Color(0x60000000),
            blurRadius: 16,
            offset: Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 3.5,
                height: 22,
                decoration: BoxDecoration(
                  color: accentColor,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: GoogleFonts.rajdhani(
                        fontSize: sw < 360 ? 14 : 16,
                        fontWeight: FontWeight.w700,
                        color: Colors.white,
                        letterSpacing: 0.3,
                      ),
                    ),
                    Text(
                      subtitle,
                      style: GoogleFonts.orbitron(
                        fontSize: 8.5,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF64748B),
                        letterSpacing: 0.8,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          ...children,
        ],
      ),
    );
  }

  // ── Text Field ───────────────────────────────────────────
  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    required Color accentColor,
    required double sw,
    TextInputType keyboardType = TextInputType.text,
  }) {
    return TextField(
      controller: controller,
      keyboardType: keyboardType,
      style: GoogleFonts.rajdhani(
        fontSize: sw < 360 ? 13 : 14.5,
        fontWeight: FontWeight.w700,
        color: Colors.white,
        letterSpacing: 0.4,
      ),
      decoration: InputDecoration(
        labelText: label,
        hintText: hint,
        prefixIcon: Icon(icon, color: accentColor, size: 18),
        labelStyle: GoogleFonts.rajdhani(
          color: const Color(0xFF8EA2C6),
          fontSize: 13,
          fontWeight: FontWeight.w600,
        ),
        hintStyle: GoogleFonts.rajdhani(
          color: const Color(0xFF475569),
          fontSize: 12,
        ),
        filled: true,
        fillColor: const Color(0xFF0A101D),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: Color(0xFF1B2842), width: 1.2),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide(color: accentColor, width: 1.8),
        ),
        contentPadding: EdgeInsets.symmetric(
          vertical: sw < 360 ? 12 : 14,
          horizontal: 12,
        ),
      ),
    );
  }

  // ── Save Button ──────────────────────────────────────────
  Widget _buildSaveButton(double sw) {
    return SizedBox(
      width: double.infinity,
      height: sw < 360 ? 48 : 52,
      child: ElevatedButton.icon(
        onPressed: _saveSettings,
        icon: const Icon(Icons.save_rounded, size: 18),
        label: Text(
          'บันทึกและเชื่อมต่อใหม่ (SAVE & CONNECT)',
          style: GoogleFonts.rajdhani(
            fontSize: sw < 360 ? 13.5 : 15,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.5,
          ),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.neonCyan,
          foregroundColor: Colors.black,
          elevation: 4,
          shadowColor: AppColors.neonCyan.withAlpha(80),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
        ),
      ),
    );
  }

  // ── Ping Button ──────────────────────────────────────────
  Widget _buildPingButton(double sw) {
    return SizedBox(
      width: double.infinity,
      height: sw < 360 ? 46 : 50,
      child: OutlinedButton.icon(
        onPressed: _isPinging ? null : _handlePing,
        icon: RotationTransition(
          turns: _pingAnim,
          child: const Icon(Icons.radar_rounded, size: 18),
        ),
        label: Text(
          _isPinging ? 'PINGING ESP32...' : 'ทดสอบความเร็วสัญญาณ (PING LATENCY)',
          style: GoogleFonts.rajdhani(
            fontSize: sw < 360 ? 13 : 14.5,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.5,
            color: _isPinging
                ? AppColors.neonCyan.withAlpha(120)
                : AppColors.neonCyanLight,
          ),
        ),
        style: OutlinedButton.styleFrom(
          side: const BorderSide(color: Color(0xFF263B60), width: 1.4),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          backgroundColor: const Color(0xFF131D31),
        ),
      ),
    );
  }

  // ── Status Card ──────────────────────────────────────────
  Widget _buildStatusCard(TelemetryViewModel vm, double sw) {
    final rows = <_InfoEntry>[
      _InfoEntry(Icons.router_rounded, 'Board IP', vm.currentBoardIp),
      _InfoEntry(Icons.settings_ethernet_rounded, 'Port', vm.currentBoardPort.toString()),
      _InfoEntry(Icons.speed_rounded, 'Latency',
          vm.boardLatencyMs != null ? '${vm.boardLatencyMs} ms' : '—'),
      _InfoEntry(Icons.tune_rounded, 'Mode', vm.controlMode.toUpperCase()),
      if (vm.telemetry != null) ...[
        _InfoEntry(Icons.wifi_rounded, 'SSID', vm.telemetry!.board.ssid),
        _InfoEntry(Icons.network_check_rounded, 'RSSI', '${vm.telemetry!.board.rssi} dBm'),
        _InfoEntry(Icons.memory_rounded, 'Device', vm.telemetry!.board.deviceName),
        _InfoEntry(Icons.info_rounded, 'Status', vm.telemetry!.board.status),
      ],
    ];

    return Container(
      padding: EdgeInsets.all(sw < 360 ? 14 : 18),
      decoration: BoxDecoration(
        color: const Color(0xFF111A2E),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFF1B2842), width: 1.4),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.terminal_rounded,
                  color: AppColors.neonCyan, size: 20),
              const SizedBox(width: 8),
              Text(
                'สถานะระบบปัจจุบัน',
                style: GoogleFonts.rajdhani(
                  fontSize: sw < 360 ? 14 : 16,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                  letterSpacing: 0.3,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          const Divider(color: Color(0xFF1B2842), height: 1),
          const SizedBox(height: 8),
          ...rows.map((e) => _buildInfoRow(e, sw)),
        ],
      ),
    );
  }

  Widget _buildInfoRow(_InfoEntry e, double sw) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        children: [
          Icon(e.icon, size: 14, color: const Color(0xFF64748B)),
          const SizedBox(width: 8),
          Text(
            e.label,
            style: GoogleFonts.rajdhani(
              fontSize: sw < 360 ? 12 : 13,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF8EA2C6),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Align(
              alignment: Alignment.centerRight,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 3),
                decoration: BoxDecoration(
                  color: const Color(0xFF0E1626),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: const Color(0xFF1B2842), width: 1),
                ),
                child: Text(
                  e.value,
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 10.5 : 12,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                    letterSpacing: 0.4,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ── Footer ───────────────────────────────────────────────
  Widget _buildFooter(double sw) {
    return Center(
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            decoration: BoxDecoration(
              color: const Color(0xFF111A2E),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFF1B2842), width: 1.2),
            ),
            child: Column(
              children: [
                Text(
                  'LEQsxAI · SMART FARM IOT',
                  style: GoogleFonts.orbitron(
                    fontSize: sw < 360 ? 9.5 : 10.5,
                    fontWeight: FontWeight.w700,
                    color: AppColors.neonCyanLight,
                    letterSpacing: 0.8,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 3),
                Text(
                  'มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU) 🏫',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 11 : 12.5,
                    color: const Color(0xFF8EA2C6),
                    fontWeight: FontWeight.w600,
                  ),
                  textAlign: TextAlign.center,
                ),
                Text(
                  'ผศ.ดร.ชีวะ ทัศนา — ภาควิชาฟิสิกส์และดิจิทัลเกษตร',
                  style: GoogleFonts.rajdhani(
                    fontSize: sw < 360 ? 10 : 11.5,
                    color: const Color(0xFF64748B),
                  ),
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

// ── Data class ──
class _InfoEntry {
  final IconData icon;
  final String label;
  final String value;
  const _InfoEntry(this.icon, this.label, this.value);
}
