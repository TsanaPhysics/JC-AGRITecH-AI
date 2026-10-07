<!-- ========================================================================= -->
<!-- DUAL-ENGINE COMPARISON: PHYSICAL SENSOR GROUND TRUTH vs EDGE AI MODEL     -->
<!-- ========================================================================= -->
<section class="glass-box rounded-3xl p-5 sm:p-6 border border-cyan-500/30 space-y-5 shadow-2xl relative overflow-hidden bg-gradient-to-br from-slate-900/95 via-slate-950 to-indigo-950/40">
    <!-- Ambient Glow Backing -->
    <div class="absolute -right-20 -top-20 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800/80 pb-4 relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-code-compare text-base"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base sm:text-lg font-bold font-tech text-white flex items-center gap-2">
                        <span>ระบบประมวลผลคู่ขนาน</span>
                        <span class="text-slate-400 font-normal">|</span>
                        <span class="text-cyan-300">ค่าตรวจวัดจากเซนเซอร์</span>
                        <span class="text-slate-500 font-mono text-xs">VS</span>
                        <span class="text-purple-300">ค่าวิเคราะห์โมเดล Edge AI</span>
                    </h3>
                </div>
                <p class="text-xs text-slate-400 font-tech">
                    เปรียบเทียบข้อมูลจริงจากโพรบฮาร์ดแวร์ (Raw Physical Sensors) กับผลการประมวลผลของโมเดลปัญญาประดิษฐ์ฝังตัวบนชิป ESP32-S3 (TinyML Neural Engine)
                </p>
            </div>
        </div>

        <!-- Badges & Confidence -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[10px] font-mono font-bold text-cyan-300 bg-cyan-950/80 px-2.5 py-1 rounded-full border border-cyan-500/40 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>RAW GROUND TRUTH</span>
            </span>
            <span class="text-[10px] font-mono font-bold text-purple-300 bg-purple-950/80 px-2.5 py-1 rounded-full border border-purple-500/40 flex items-center gap-1.5">
                <i class="fa-solid fa-brain text-[10px] text-purple-400"></i>
                <span>TINYML EDGE AI: <strong id="compareAiConfBadge" class="text-white">77.6%</strong></span>
            </span>
        </div>
    </div>

    <!-- Dual Comparison Grid (6 Key Agronomy Parameters - Responsive Fluid Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-3.5 relative z-10">
        
        <!-- Parameter 1: Soil pH -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-lime-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-flask text-lime-400 text-xs"></i> กรด-ด่าง (pH)
                </span>
                <span class="text-[9px] font-mono text-slate-500">Root Zone</span>
            </div>
            <!-- Side-by-side cards -->
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <!-- Raw Sensor -->
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="compareRawPh" class="text-base sm:text-lg font-black text-cyan-300 block">8.20</span>
                    <span class="text-[8px] text-slate-500 block">7-in-1 Modbus</span>
                </div>
                <!-- Edge AI -->
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="compareAiPh" class="text-base sm:text-lg font-black text-lime-400 block">9.14</span>
                    <span class="text-[8px] text-purple-300/80 block">ชดเชย Thermal</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>ผลต่าง: <strong id="compareDiffPh" class="text-amber-400">+0.94</strong></span>
                <span class="text-lime-400">ชดเชยดริฟต์</span>
            </div>
        </div>

        <!-- Parameter 2: Soil Moisture (3-Depth Matrix) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-cyan-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-droplet text-cyan-400 text-xs"></i> ความชื้นดิน (%)
                </span>
                <span class="text-[9px] font-mono text-emerald-400 font-bold bg-emerald-950/80 px-1.5 py-0.5 rounded border border-emerald-500/30">จอ ESP32 1:1</span>
            </div>
            <div class="grid grid-cols-3 gap-1.5 text-center font-mono">
                <div class="p-1.5 rounded-xl bg-slate-950/80 border border-emerald-500/30">
                    <span class="text-[9px] text-emerald-400 font-sans block font-semibold truncate">จอ ESP32</span>
                    <span id="compareStickMoist" class="text-base font-black text-emerald-300 block">61.3%</span>
                    <span class="text-[8px] text-slate-500 block">ผิวดิน Stick</span>
                </div>
                <div class="p-1.5 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold truncate">รากลึก</span>
                    <span id="compareRawMoist" class="text-base font-black text-cyan-300 block">2.6%</span>
                    <span class="text-[8px] text-slate-500 block">7in1 เขตราก</span>
                </div>
                <div class="p-1.5 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold truncate">โมเดล AI</span>
                    <span id="compareAiMoist" class="text-base font-black text-purple-300 block">20.5%</span>
                    <span class="text-[8px] text-purple-400 block">ชดเชย AI</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>Multi-Depth Fusion:</span>
                <span class="text-emerald-300 font-bold">ตรงกับจอบอร์ด 100%</span>
            </div>
        </div>

        <!-- Parameter 3: Available Nitrogen (N) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-leaf text-emerald-400 text-xs"></i> ไนโตรเจน (N)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="compareRawN" class="text-base sm:text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบไม่มีปุ๋ย</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="compareAiN" class="text-base sm:text-lg font-black text-emerald-400 block">14.7</span>
                    <span class="text-[8px] text-purple-300/80 block">Available N</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>TinyML Calibrator:</span>
                <span class="text-emerald-400">ประเมินพร้อมใช้</span>
            </div>
        </div>

        <!-- Parameter 4: Available Phosphorus (P) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-cyan-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-seedling text-cyan-400 text-xs"></i> ฟอสฟอรัส (P)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="compareRawP" class="text-base sm:text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบไม่มีปุ๋ย</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="compareAiP" class="text-base sm:text-lg font-black text-cyan-400 block">12.8</span>
                    <span class="text-[8px] text-purple-300/80 block">Bray II Match</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>TinyML Calibrator:</span>
                <span class="text-cyan-400">Bray II Model</span>
            </div>
        </div>

        <!-- Parameter 5: Exchangeable Potassium (K) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-amber-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-cubes-stacked text-amber-400 text-xs"></i> โพแทสเซียม (K)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="compareRawK" class="text-base sm:text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบไม่มีปุ๋ย</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="compareAiK" class="text-base sm:text-lg font-black text-amber-400 block">3.9</span>
                    <span class="text-[8px] text-purple-300/80 block">Exch. K</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>TinyML Calibrator:</span>
                <span class="text-amber-400">ชดเชยค่า EC</span>
            </div>
        </div>

        <!-- Parameter 6: Microclimate VPD & Crop Stress -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-indigo-500/40 transition space-y-2.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-wind text-indigo-400 text-xs"></i> แรงดึงระเหย (VPD)
                </span>
                <span class="text-[9px] font-mono text-slate-500">kPa</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="compareRawVpd" class="text-base sm:text-lg font-black text-cyan-300 block">1.35</span>
                    <span class="text-[8px] text-slate-500 block">SHT45 Physics</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="compareAiVpdStatus" class="text-xs sm:text-sm font-black text-emerald-400 block truncate mt-1">สมบูรณ์</span>
                    <span class="text-[8px] text-purple-300/80 block">Penman FAO-56</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>อัตราคายน้ำพืช:</span>
                <span id="compareTranspText" class="text-emerald-400 font-bold">Optimal</span>
            </div>
        </div>

    </div>

    <!-- AI Diagnostic & Agronomy Reasoning Strip -->
    <div class="p-3.5 rounded-2xl bg-slate-950/80 border border-purple-500/30 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-xs relative z-10">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold flex-shrink-0">
                <i class="fa-solid fa-stethoscope text-sm"></i>
            </div>
            <div>
                <span class="text-[10px] font-mono text-purple-400 font-bold uppercase tracking-wider block">Edge AI Agronomy Real-Time Diagnosis</span>
                <p id="compareAiAgronomyText" class="text-xs text-slate-200 font-medium">
                    <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i>
                    ตรวจพบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick 3.03 vs เขตรากลึก 8.20) • ดินเขตราก 10-30 cm มีความชื้นต่ำ 2.6% แนะนำสั่งเปิดวาล์วน้ำโซลินอยด์
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-[10px] font-mono text-slate-400">YOLOv8 Edge Vision:</span>
            <span id="compareAiVisionBadge" class="text-[10px] font-mono font-bold text-emerald-300 bg-emerald-950/80 px-2 py-0.5 rounded border border-emerald-500/40">
                Healthy Leaf 98.4%
            </span>
        </div>
    </div>
</section>
