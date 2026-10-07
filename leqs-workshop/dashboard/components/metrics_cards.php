<!-- ========================================================================= -->
<!-- ROW 1: 6 Real-time Telemetry Metrics Cards (Responsive Fluid Grid)        -->
<!-- Breakpoints: 1 col (mobile) -> 2 cols (tablet) -> 3 cols (laptop) -> 6 cols (2xl) -->
<!-- ========================================================================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-3.5 sm:gap-4">
    
    <!-- Card 1: Air Temp & Humidity (SHT45 Microclimate) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-tech">
                <i class="fa-solid fa-temperature-three-quarters text-amber-400"></i>
                <span class="whitespace-nowrap">สภาพอากาศ (SHT45)</span>
            </div>
            <span id="badgeSht45" class="text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800 flex-shrink-0">I2C ONLINE</span>
        </div>

        <!-- 2-Column Clean Micro-Grid (Prevents Any Number Collision) -->
        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-800/40">
            <!-- Left: Air Temperature -->
            <div class="min-w-0 pr-1">
                <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">อุณหภูมิอากาศ</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span id="dashTemp" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-white tracking-tight whitespace-nowrap">24.27</span>
                    <span class="text-xs text-slate-400 font-mono font-bold">°C</span>
                </div>
                <span id="dashTempF" class="text-[10px] font-mono text-slate-500 block whitespace-nowrap mt-0.5">(75.69°F)</span>
            </div>

            <!-- Right: Relative Humidity -->
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">ความชื้นสัมพัทธ์</span>
                <div class="flex items-baseline justify-end gap-1 mt-0.5">
                    <span id="dashHum" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-cyan-400 tracking-tight whitespace-nowrap">57.01</span>
                    <span class="text-xs text-slate-400 font-mono font-bold">%</span>
                </div>
                <span class="text-[10px] font-mono text-cyan-500/80 block whitespace-nowrap mt-0.5">RH</span>
            </div>
        </div>

        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
            <span class="whitespace-nowrap">Dew: <strong id="dashDewPoint" class="text-cyan-300">15.48°C</strong></span>
            <span class="whitespace-nowrap">Margin: <strong id="dashDewMargin" class="text-amber-300">8.79°C</strong></span>
        </div>
    </div>

    <!-- Card 2: VPD (Vapor Pressure Deficit & Penman Physics) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-cyan-500/40 transition">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-tech">
                <i class="fa-solid fa-wind text-cyan-400"></i>
                <span class="whitespace-nowrap">แรงดึงระเหยน้ำ (VPD)</span>
            </div>
            <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800 flex-shrink-0">FAO-56</span>
        </div>

        <div class="grid grid-cols-2 gap-2 items-center pt-1 border-t border-slate-800/40">
            <div class="min-w-0">
                <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">Vapor Deficit</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span id="dashVpd" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-emerald-300 tracking-tight whitespace-nowrap">1.28</span>
                    <span class="text-xs text-slate-400 font-mono font-bold">kPa</span>
                </div>
            </div>
            <div class="text-right flex justify-end">
                <span id="dashVpdStatus" class="text-[10px] font-tech font-bold text-emerald-300 bg-emerald-950 px-2 py-1 rounded border border-emerald-800/80 text-center leading-tight">
                    สมบูรณ์ (Optimal)
                </span>
            </div>
        </div>

        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
            <span class="whitespace-nowrap">VPsat: <strong id="dashVpSat" class="text-slate-300">3.03</strong></span>
            <span class="whitespace-nowrap">VPact: <strong id="dashVpAct" class="text-cyan-300">1.75</strong></span>
        </div>
    </div>

    <!-- Card 3: Dedicated Soil Moisture Card (ความชื้นในดิน - 2 ระดับความลึก) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-cyan-500/50 transition" id="cardSoilMoisture">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-tech">
                <i class="fa-solid fa-droplet text-cyan-400"></i>
                <span class="whitespace-nowrap font-bold text-white">ความชื้นในดิน (Soil Moisture)</span>
            </div>
            <span id="badgeSoilMoistDepth" class="text-[9px] font-mono text-cyan-300 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800 flex-shrink-0">Dual-Depth</span>
        </div>

        <!-- 2-Column Clean Micro-Grid: ผิวดิน vs เขตราก -->
        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-800/40">
            <div class="min-w-0 pr-1">
                <div class="flex items-center gap-1">
                    <span class="text-[10px] font-tech text-cyan-300 block whitespace-nowrap font-semibold">ผิวดิน (Surface)</span>
                    <span class="text-[8px] bg-cyan-900/60 text-cyan-200 px-1 rounded font-mono">Analog</span>
                </div>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span id="dashStickMoist" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-white tracking-tight whitespace-nowrap drop-shadow-[0_0_12px_rgba(6,182,212,0.4)]">61.8</span>
                    <span class="text-xs text-cyan-400 font-mono font-bold">%</span>
                </div>
                <span class="text-[9px] font-mono text-slate-400 block whitespace-nowrap">ผิวดิน (0-10 cm)</span>
            </div>
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <div class="flex items-center justify-end gap-1">
                    <span class="text-[8px] bg-slate-800 text-slate-300 px-1 rounded font-mono">Modbus</span>
                    <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">เขตราก (Root)</span>
                </div>
                <div class="flex items-baseline justify-end gap-1 mt-0.5">
                    <span id="dashSoilMoist" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-cyan-400/90 tracking-tight whitespace-nowrap">0.0</span>
                    <span class="text-xs text-slate-400 font-mono font-bold">%</span>
                </div>
                <span class="text-[9px] font-mono text-slate-400 block whitespace-nowrap">เขตราก (15-30 cm)</span>
            </div>
        </div>

        <!-- Hidden backward-compat elements for scripts -->
        <span id="badgeSoilStick" class="hidden">ADC A1/A2</span>
        <span id="dashSoilMoistFoot" class="hidden">0.0%</span>

        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
            <span class="whitespace-nowrap">ADC: <strong id="dashStickAdc" class="text-slate-300">2030</strong></span>
            <span class="text-emerald-400 font-semibold">สมบูรณ์ (Optimal)</span>
            <span class="whitespace-nowrap">Volt: <strong id="dashStickVolt" class="text-cyan-300">2.32V</strong></span>
        </div>
    </div>

    <!-- Card 4: Dedicated Soil pH & Chemistry Card (กรด-ด่างในดิน - ไร้ความชื้นปน) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-lime-500/50 transition" id="cardSoil7in1">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-tech">
                <i class="fa-solid fa-flask-vial text-lime-400"></i>
                <span class="whitespace-nowrap font-bold text-white">กรด-ด่างดิน (Soil pH)</span>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <span id="badgeSoil7in1Mode" class="text-[9px] font-mono font-bold text-lime-300 bg-lime-950/80 px-2 py-0.5 rounded-full border border-lime-500/40">pH เขตราก</span>
                <span id="badgeSoil7in1" class="text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">RS485</span>
            </div>
        </div>

        <!-- 2-Column: pH เขตราก 7-in-1 (Hero) vs EC ดิน & pH ผิว -->
        <div class="grid grid-cols-2 gap-2 items-center pt-1 border-t border-slate-800/40">
            <div id="box7in1Hero" class="transition-all duration-300 ease-out min-w-0">
                <div class="flex items-baseline gap-1">
                    <span id="dash7in1HeroVal" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-lime-400 tracking-tight drop-shadow-[0_0_15px_rgba(163,230,53,0.45)] whitespace-nowrap">7.3</span>
                    <span id="dash7in1HeroUnit" class="text-xs font-bold font-mono text-lime-300">pH</span>
                </div>
                <span id="dash7in1HeroSub" class="text-[9px] font-mono text-lime-400/90 block font-semibold whitespace-nowrap">เป็นกลาง (Neutral)</span>
            </div>
            <div class="text-right min-w-0 pl-1 border-l border-slate-800/80">
                <div id="dash7in1SecTitle" class="text-[10px] text-slate-400 whitespace-nowrap">ความเค็มดิน (EC)</div>
                <div class="flex items-baseline justify-end gap-1 mt-0.5">
                    <span id="dashSoilEc" class="text-base sm:text-lg xl:text-xl font-bold font-mono text-amber-300 block whitespace-nowrap">0.0</span>
                    <span class="text-[10px] text-slate-400 font-mono">µS/cm</span>
                </div>
                <div class="text-[9px] font-mono text-slate-400 whitespace-nowrap mt-0.5">
                    pH ผิว: <strong id="dashStickPh" class="text-amber-300">3.02</strong>
                </div>
            </div>
        </div>

        <!-- Hidden elements for script backwards compatibility -->
        <span id="dashSoilPh" class="hidden">7.3</span>
        <span id="dash7in1SecVal" class="hidden">0.0</span>

        <!-- Bottom Telemetry Summary (ไม่มีความชื้นปน) -->
        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
            <span>pH ราก: <strong id="dashSoilPhFoot" class="text-lime-400 text-xs font-bold">7.3</strong></span>
            <span>สถานะ: <strong class="text-emerald-400">สมบูรณ์</strong></span>
            <span>Temp: <strong id="dashSoilTemp" class="text-slate-300">24.1°C</strong></span>
        </div>

        <!-- NPK Capsules เหมือนหน้าจอบอร์ด ESP32 -->
        <div class="grid grid-cols-3 gap-1 pt-1 font-mono text-[9px]">
            <span class="bg-indigo-950/80 text-indigo-300 border border-indigo-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap" id="dashCard4N">N 14.0</span>
            <span class="bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap" id="dashCard4P">P 9.5</span>
            <span class="bg-amber-950/80 text-amber-300 border border-amber-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap" id="dashCard4K">K 14.6</span>
        </div>
    </div>

    <!-- Card 5: Solar PAR & Radiation (BH1750 Dome) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-yellow-500/40 transition">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-tech">
                <i class="fa-solid fa-sun text-yellow-400"></i>
                <span class="whitespace-nowrap">แสงแดด & รังสี</span>
            </div>
            <span id="badgeBh1750" class="text-[9px] font-mono text-yellow-400 bg-yellow-950 px-2 py-0.5 rounded border border-yellow-800 flex-shrink-0">BH1750 I2C</span>
        </div>

        <div class="grid grid-cols-2 gap-2 items-center pt-1 border-t border-slate-800/40">
            <div class="min-w-0 pr-1">
                <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">ความเข้มแสง</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span id="dashPar" class="text-lg sm:text-xl xl:text-2xl 2xl:text-3xl font-black font-mono text-white tracking-tight whitespace-nowrap">42.5</span>
                    <span class="text-xs text-slate-400 font-mono font-bold">Lux</span>
                </div>
            </div>
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <span class="text-[10px] font-tech text-slate-400 block whitespace-nowrap">Solar Rad</span>
                <span id="dashSolarRad" class="text-xs sm:text-sm font-bold text-yellow-400 font-mono block mt-1 whitespace-nowrap">0.34 W/m²</span>
            </div>
        </div>

        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
            <span class="whitespace-nowrap">kLux: <strong id="dashKlux" class="text-yellow-300">0.04</strong></span>
            <span id="dashAiConfidence" class="text-emerald-400 font-bold whitespace-nowrap">AI: 74.5%</span>
        </div>
    </div>

    <!-- Card 6: Dual Storage (Micro-SD Card on ESP32 + SQLite3 Database) -->
    <div class="glass-box rounded-3xl p-4 sm:p-5 border border-cyan-500/40 space-y-3 relative overflow-hidden group hover:border-cyan-400 transition bg-gradient-to-br from-slate-900/90 to-cyan-950/30">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-1.5 text-xs text-cyan-300 font-medium font-tech">
                <i class="fa-solid fa-server text-cyan-400"></i>
                <span class="whitespace-nowrap">การจัดเก็บข้อมูล 2 ชั้น</span>
            </div>
            <span id="dashSdBadge" class="text-[9px] font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded border border-slate-700 flex-shrink-0">STANDBY (NO SD)</span>
        </div>

        <div class="space-y-1.5 text-[11px] font-mono pt-1 border-t border-slate-800/40">
            <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800 gap-1 cursor-pointer hover:border-amber-500/40 transition" onclick="toggleOrCheckSdCard()" title="คลิกเพื่อสลับ/ตรวจสอบสถานะ Micro-SD Card">
                <span class="text-amber-300 flex items-center gap-1 text-[10px] whitespace-nowrap"><i class="fa-solid fa-sd-card"></i> SD Card:</span>
                <strong id="dashSdRecords" class="text-slate-400 italic text-[10px] whitespace-nowrap">ไม่ได้ใส่การ์ด</strong>
            </div>
            <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800 gap-1">
                <span class="text-emerald-300 flex items-center gap-1 text-[10px] whitespace-nowrap"><i class="fa-solid fa-database"></i> SQLite DB:</span>
                <strong id="dashDbRecords" class="text-white text-[10px] whitespace-nowrap">Auto Sync</strong>
            </div>
        </div>

        <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-800/60 font-mono">
            <span>File: <span class="text-amber-300 font-bold">.csv &amp; .db</span></span>
            <a href="../api/api.php?action=export_csv" class="text-cyan-400 hover:underline font-bold">โหลด CSV</a>
        </div>
    </div>

</div>
