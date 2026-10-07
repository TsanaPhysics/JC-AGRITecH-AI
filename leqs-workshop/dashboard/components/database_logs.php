
        <!-- ROW 4: Live SQLite3 Database Telemetry Logs & Dual-Storage Architecture -->
        <div class="glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-database text-emerald-400"></i>
                        <h3 class="font-bold text-base text-white">บันทึกประวัติข้อมูลเซนเซอร์ลงฐานข้อมูล (SQLite3 Telemetry Database Logs)</h3>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        จัดเก็บทุกปริมาณ ทุกค่าจากบอร์ด ESP32-S3 ATD3.5 ลงตาราง <code class="text-cyan-300 font-mono">telemetry_logs</code> (ฐานข้อมูลเซิร์ฟเวอร์) ควบคู่กับ <code class="text-amber-300 font-mono">/telemetry_data.csv</code> บน Micro-SD Card
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs font-mono text-slate-300">
                        เรคอร์ดใน DB: <strong id="tableDbTotalRecords" class="text-emerald-400">0</strong>
                    </span>
                    <button onclick="fetchDbHistoryTable()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-arrows-rotate"></i> รีเฟรชตาราง
                    </button>
                    <a href="../api/api.php?action=export_csv" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-emerald-500 hover:from-amber-400 hover:to-emerald-400 text-slate-950 font-bold text-xs font-tech flex items-center gap-1.5 shadow-md transition">
                        <i class="fa-solid fa-file-csv text-sm"></i> ดาวน์โหลดข้อมูลทั้งหมด (.CSV)
                    </a>
                </div>
            </div>

            <!-- Responsive Table Container -->
            <div class="overflow-x-auto rounded-2xl border border-slate-800/80 bg-slate-950/60 max-h-96">
                <table class="w-full text-left text-xs text-slate-300 font-mono">
                    <thead class="bg-slate-900/90 text-[11px] text-slate-400 border-b border-slate-800 sticky top-0 backdrop-blur-sm z-10">
                        <tr>
                            <th class="p-3">#ID</th>
                            <th class="p-3">วัน-เวลา</th>
                            <th class="p-3">อากาศ (T/RH)</th>
                            <th class="p-3">VPD</th>
                            <th class="p-3">แสง &amp; Rad</th>
                            <th class="p-3">ผิวดิน Stick</th>
                            <th class="p-3">ดินลึก 7-in-1</th>
                            <th class="p-3">NPK (mg/kg)</th>
                            <th class="p-3">AI NPK</th>
                            <th class="p-3">รีเลย์ 1-4</th>
                            <th class="p-3">สถานะ SD Card</th>
                        </tr>
                    </thead>
                    <tbody id="telemetryTableBody" class="divide-y divide-slate-800/60 text-[11px]">
                        <tr>
                            <td colspan="11" class="text-center p-6 text-slate-500">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i> กำลังโหลดข้อมูลประวัติจาก SQLite3...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 pt-2 font-mono gap-2">
                <div>
                    <span>แฟ้มข้อมูล SD Card: <strong class="text-amber-300">/telemetry_data.csv</strong></span>
                    <span class="mx-2">•</span>
                    <span>ฐานข้อมูลเซิร์ฟเวอร์: <strong class="text-emerald-300">data/leqs_xai.db</strong></span>
                </div>
                <div class="text-slate-500 text-[10px]">
                    * อัปเดตอัตโนมัติทุก 5 วินาที พร้อมส่งออกไฟล์มาตรฐานรองรับ Excel และ R / Python
                </div>
            </div>
        </div>

