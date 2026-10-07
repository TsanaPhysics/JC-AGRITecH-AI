<!-- ========================================================================= -->
<!-- COMPONENT: DUAL-ENGINE AI VS SENSOR COMPARISON PANEL                      -->
<!-- ========================================================================= -->
<div class="glass-panel rounded-3xl p-5 sm:p-6 border border-cyan-500/30 space-y-5 shadow-2xl relative overflow-hidden bg-gradient-to-br from-slate-900/95 via-slate-950 to-indigo-950/40">
    <!-- Ambient glow -->
    <div class="absolute -right-20 -top-20 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-white/10 pb-4 relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-code-compare text-base"></i>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold font-tech text-white flex items-center gap-2">
                    <span class="text-cyan-300">ค่าตรวจวัดจากเซนเซอร์จริง</span>
                    <span class="text-slate-500 text-xs font-mono">VS</span>
                    <span class="text-purple-300">ค่าวิเคราะห์โมเดล Edge AI</span>
                </h3>
                <p class="text-xs text-slate-400 font-tech">
                    โหนดปัจจุบัน: <strong id="compareBoardNameText" class="text-white">ESP32-NODE-01</strong> • ประมวลผลคู่ขนานบนชิป ESP32-S3 TinyML
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-[10px] font-mono font-bold text-cyan-300 bg-cyan-950/80 px-2.5 py-1 rounded-full border border-cyan-500/40">
                RAW GROUND TRUTH
            </span>
            <span class="text-[10px] font-mono font-bold text-purple-300 bg-purple-950/80 px-2.5 py-1 rounded-full border border-purple-500/40 flex items-center gap-1.5">
                <i class="fa-solid fa-brain text-purple-400"></i>
                <span>TINYML CONF: <strong id="compareAiConfBadge" class="text-white">77.6%</strong></span>
            </span>
        </div>
    </div>

    <!-- 6 Comparison Tiles Matrix -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3.5 relative z-10">
        
        <!-- Tile 1: Soil pH -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-lime-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-flask text-lime-400 text-xs"></i> กรด-ด่าง (pH)
                </span>
                <span class="text-[9px] font-mono text-slate-500">Root Zone</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="aiCompRawPh" class="text-lg font-black text-cyan-300 block">8.50</span>
                    <span class="text-[8px] text-slate-500 block">7-in-1 โพรบ</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="aiCompAiPh" class="text-lg font-black text-lime-400 block">9.44</span>
                    <span class="text-[8px] text-purple-300/80 block">ชดเชย Thermal</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>ผลต่าง: <strong id="aiCompDiffPh" class="text-amber-400">+0.94</strong></span>
                <span class="text-lime-400">ชดเชยดริฟต์</span>
            </div>
        </div>

        <!-- Tile 2: Soil Moisture (3-Layer Depth Matrix) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-cyan-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-droplet text-cyan-400 text-xs"></i> ความชื้นดิน (%)
                </span>
                <span class="text-[9px] font-mono text-emerald-400 font-bold bg-emerald-950/80 px-1.5 py-0.5 rounded border border-emerald-500/30">จอ ESP32 1:1</span>
            </div>
            <div class="grid grid-cols-3 gap-1.5 text-center font-mono">
                <div class="p-1.5 rounded-xl bg-slate-950/80 border border-emerald-500/30">
                    <span class="text-[9px] text-emerald-400 font-sans block font-semibold truncate" title="Soil Stick ผิวดิน 0-10cm ตรงกับจอ ESP32">จอ ESP32</span>
                    <span id="aiCompStickMoist" class="text-base font-black text-emerald-300 block">60.7%</span>
                    <span class="text-[8px] text-slate-500 block">ผิวดิน Stick</span>
                </div>
                <div class="p-1.5 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold truncate" title="โพรบ 7-in-1 เขตรากลึก 10-30cm">รากลึก 7in1</span>
                    <span id="aiCompRawMoist" class="text-base font-black text-cyan-300 block">2.6%</span>
                    <span class="text-[8px] text-slate-500 block">10-30 cm</span>
                </div>
                <div class="p-1.5 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold truncate" title="TinyML True Fused VWC">โมเดล AI</span>
                    <span id="aiCompAiMoist" class="text-base font-black text-purple-300 block">20.5%</span>
                    <span class="text-[8px] text-purple-400 block">ชดเชย AI</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>Stick ผิว vs ราก 7in1:</span>
                <span class="text-emerald-300 font-bold">ตรงกับจอบอร์ด 100%</span>
            </div>
        </div>

        <!-- Tile 3: Available Nitrogen (N) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-leaf text-emerald-400 text-xs"></i> ไนโตรเจน (N)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="aiCompRawN" class="text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบดิบ</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="aiCompAiN" class="text-lg font-black text-emerald-400 block">21.8</span>
                    <span class="text-[8px] text-purple-300/80 block">Available N</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>TinyML Est:</span>
                <span class="text-emerald-400">ประเมินพร้อมใช้</span>
            </div>
        </div>

        <!-- Tile 4: Available Phosphorus (P) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-cyan-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-seedling text-cyan-400 text-xs"></i> ฟอสฟอรัส (P)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="aiCompRawP" class="text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบดิบ</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="aiCompAiP" class="text-lg font-black text-cyan-400 block">13.9</span>
                    <span class="text-[8px] text-purple-300/80 block">Bray II Model</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>Bray II Match:</span>
                <span class="text-cyan-400">แม่นยำ 94%</span>
            </div>
        </div>

        <!-- Tile 5: Exchangeable Potassium (K) -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-amber-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-cubes text-amber-400 text-xs"></i> โพแทสเซียม (K)
                </span>
                <span class="text-[9px] font-mono text-slate-500">mg/kg</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="aiCompRawK" class="text-lg font-black text-slate-400 block">0.0</span>
                    <span class="text-[8px] text-slate-500 block">โพรบดิบ</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="aiCompAiK" class="text-lg font-black text-amber-400 block">8.0</span>
                    <span class="text-[8px] text-purple-300/80 block">Exch. K</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>EC Calibrated:</span>
                <span class="text-amber-400">ชดเชย EC ดิน</span>
            </div>
        </div>

        <!-- Tile 6: VPD & Transpiration -->
        <div class="rounded-2xl p-3.5 bg-slate-900/90 border border-slate-800 hover:border-indigo-500/40 transition space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-300 font-tech flex items-center gap-1.5">
                    <i class="fa-solid fa-wind text-indigo-400 text-xs"></i> แรงดึงระเหย (VPD)
                </span>
                <span class="text-[9px] font-mono text-slate-500">kPa</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                <div class="p-2 rounded-xl bg-slate-950/80 border border-cyan-500/20">
                    <span class="text-[9px] text-cyan-400 font-sans block font-semibold">เซนเซอร์ตรง</span>
                    <span id="aiCompRawVpd" class="text-lg font-black text-cyan-300 block">1.35</span>
                    <span class="text-[8px] text-slate-500 block">SHT45 kPa</span>
                </div>
                <div class="p-2 rounded-xl bg-purple-950/40 border border-purple-500/30">
                    <span class="text-[9px] text-purple-300 font-sans block font-semibold">โมเดล AI</span>
                    <span id="aiCompAiVpdStatus" class="text-xs font-black text-emerald-400 block mt-1">สมบูรณ์</span>
                    <span class="text-[8px] text-purple-300/80 block">Penman FAO-56</span>
                </div>
            </div>
            <div class="text-[9px] text-slate-400 border-t border-slate-800/60 pt-1 flex justify-between font-mono">
                <span>Transpiration:</span>
                <span class="text-emerald-400 font-bold">Optimal</span>
            </div>
        </div>

    </div>

    <!-- AI Agronomy Diagnostic Text -->
    <div class="p-3.5 rounded-2xl bg-slate-950/80 border border-purple-500/30 flex items-start gap-3 text-xs relative z-10">
        <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold flex-shrink-0 mt-0.5">
            <i class="fa-solid fa-stethoscope text-sm"></i>
        </div>
        <div class="flex-1">
            <span class="text-[10px] font-mono text-purple-400 font-bold uppercase tracking-wider block">Edge AI Agronomy Inference &amp; Alert</span>
            <p id="aiCompAgronomyAlertText" class="text-slate-200 font-tech leading-relaxed">
                <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i>
                ตรวจพบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick 3.03 vs เขตรากลึก 8.50) • ดินเขตราก 10-30 cm มีความชื้นต่ำ 2.6% แนะนำสั่งเปิดวาล์วน้ำโซลินอยด์
            </p>
        </div>
    </div>
</div>
