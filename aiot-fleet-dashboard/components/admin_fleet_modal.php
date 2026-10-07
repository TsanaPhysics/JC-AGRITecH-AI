<!-- ========================================================================= -->
<!-- MODAL: AIoT FLEET BACKEND ADMIN & SENSOR CUSTOMIZATION HUB                -->
<!-- ========================================================================= -->
<div id="modalAdminFleet" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-5">
    <div class="glass-panel rounded-3xl p-5 sm:p-7 max-w-6xl w-full border border-amber-500/40 shadow-2xl space-y-5 bg-[#080d1a] widget-enter max-h-[92vh] flex flex-col">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-rose-600 text-slate-950 flex items-center justify-center font-bold shadow-lg shadow-amber-500/20 text-xl">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg sm:text-xl font-bold font-tech text-white">ระบบจัดการหลังบ้าน & ปรับแต่งเซนเซอร์รายกลุ่ม</h3>
                        <span class="px-2 py-0.5 text-[10px] font-mono bg-amber-500/20 text-amber-300 rounded border border-amber-500/40 font-bold">Admin Hub</span>
                    </div>
                    <p class="text-xs text-slate-400">
                        ปรับแก้ข้อมูลบอร์ด, IP Address, สถานะออนไลน์ และเลือกเปิด/ปิดชุดเซนเซอร์ตามฮาร์ดแวร์จริงของแต่ละกลุ่ม (1-15)
                    </p>
                </div>
            </div>
            <button onclick="closeAdminFleetModal()" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Quick Presets Toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 rounded-2xl bg-slate-900/90 border border-white/10 flex-shrink-0">
            <div class="flex flex-wrap items-center gap-2 text-xs font-tech">
                <span class="text-slate-400 font-bold flex items-center gap-1.5 mr-1">
                    <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i> เลือกพรีเซ็ตด่วนทั้งห้อง:
                </span>
                <button onclick="applyBatchPreset('full')" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-300 border border-emerald-500/30 transition flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> ครบทุกเซนเซอร์ (Full Suite)
                </button>
                <button onclick="applyBatchPreset('soil_npk')" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-lime-300 border border-lime-500/30 transition flex items-center gap-1">
                    <i class="fa-solid fa-seedling text-[10px]"></i> เน้นดิน NPK (7-in-1 + Stick)
                </button>
                <button onclick="applyBatchPreset('greenhouse')" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-cyan-500/30 transition flex items-center gap-1">
                    <i class="fa-solid fa-sun text-[10px]"></i> โรงเรือน (SHT45 + BH1750)
                </button>
                <button onclick="applyBatchPreset('basic')" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/30 transition flex items-center gap-1">
                    <i class="fa-solid fa-feather text-[10px]"></i> ชุดพื้นฐาน (Basic)
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="openBoardModal()" class="px-3 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-tech font-bold text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-plus"></i> เพิ่มบอร์ดใหม่
                </button>
                <button onclick="saveAllAdminBoards()" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-tech font-bold text-xs flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-floppy-disk"></i> บันทึกทั้งหมด
                </button>
            </div>
        </div>

        <!-- Sensor Overview Metrics Banner -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5 text-center font-mono text-xs flex-shrink-0">
            <div class="p-2.5 rounded-xl bg-slate-950 border border-cyan-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">🌡️ SHT45 (อากาศ)</span>
                <span id="metricCountSht45" class="text-base font-bold text-cyan-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-lime-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">🪴 Soil 7-in-1 (ดินลึก)</span>
                <span id="metricCountSoil7in1" class="text-base font-bold text-lime-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-amber-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">📍 Stick (ผิวดิน)</span>
                <span id="metricCountStick" class="text-base font-bold text-amber-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-yellow-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">☀️ BH1750 (แสง)</span>
                <span id="metricCountBh1750" class="text-base font-bold text-yellow-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-purple-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">🧠 AI NPK (TinyML)</span>
                <span id="metricCountAiNpk" class="text-base font-bold text-purple-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-indigo-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">📷 กล้องตรวจใบ</span>
                <span id="metricCountCamera" class="text-base font-bold text-indigo-400">16</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-950 border border-rose-500/20">
                <span class="text-[10px] text-slate-400 block font-sans">🩺 สัญญาณชีพ (Health)</span>
                <span id="metricCountVital" class="text-base font-bold text-rose-400">0</span>
                <span class="text-[9px] text-slate-500 block">บอร์ดเปิดใช้</span>
            </div>
        </div>

        <!-- Scrollable Management Table -->
        <div class="flex-1 overflow-y-auto border border-white/10 rounded-2xl bg-slate-950/60 p-1">
            <table class="w-full text-left border-collapse text-xs font-tech">
                <thead class="sticky top-0 bg-[#0c1222] z-10 text-slate-300 font-mono text-[11px] border-b border-white/10 shadow-sm">
                    <tr>
                        <th class="py-3 px-3">โหนด / กลุ่ม</th>
                        <th class="py-3 px-3">ชื่อแปลง / การทดลอง</th>
                        <th class="py-3 px-3">โซน / โต๊ะ</th>
                        <th class="py-3 px-3">IP Address</th>
                        <th class="py-3 px-3">ชุดพรีเซ็ต</th>
                        <th class="py-3 px-2 text-center" title="เซนเซอร์อากาศ SHT45 (อุณหภูมิ & ความชื้น)">SHT45</th>
                        <th class="py-3 px-2 text-center" title="เซนเซอร์ดิน RS485 7-in-1 (pH/EC/ชื้น/NPK ราก)">Soil 7in1</th>
                        <th class="py-3 px-2 text-center" title="เซนเซอร์ผิวดิน Stick ADC (pH/ชื้นผิว)">Stick</th>
                        <th class="py-3 px-2 text-center" title="เซนเซอร์แสง BH1750 (Lux & Solar)">BH1750</th>
                        <th class="py-3 px-2 text-center" title="โมเดล TinyML คำนวณ NPK & pH">AI NPK</th>
                        <th class="py-3 px-2 text-center" title="กล้องตรวจโรคใบพืช">Camera</th>
                        <th class="py-3 px-2 text-center" title="เซนเซอร์สุขภาพเกษตรกร">Vital</th>
                        <th class="py-3 px-3 text-center">สถานะ</th>
                        <th class="py-3 px-3 text-right">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody id="adminFleetTableBody" class="divide-y divide-white/5 font-sans">
                    <!-- Populated via JavaScript FleetAdmin.renderTable() -->
                </tbody>
            </table>
        </div>

        <!-- Modal Footer -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-white/10 flex-shrink-0 text-xs">
            <div class="flex items-center gap-2 text-slate-400 font-tech">
                <button onclick="confirmResetWorkshop()" class="text-rose-400 hover:text-rose-300 transition flex items-center gap-1">
                    <i class="fa-solid fa-rotate-left"></i> รีเซ็ตเป็นบอร์ดตั้งต้น 16 กลุ่ม
                </button>
                <span>•</span>
                <span>การเปลี่ยนแปลงจะมีผลต่อการแสดงผลการ์ดในแดชบอร์ดทันที</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeAdminFleetModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-tech font-bold transition">
                    ปิดหน้าต่าง
                </button>
                <button type="button" onclick="saveAllAdminBoards()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white font-tech font-bold transition shadow-md flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> บันทึกและปรับปรุงหน้าจอ
                </button>
            </div>
        </div>

    </div>
</div>
