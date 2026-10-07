<!-- ========================================================================= -->
<!-- ROW 1: 6 Real-time Telemetry Metrics Cards (Responsive Fluid Grid)        -->
<!-- Breakpoints: 1 col (mobile) -> 2 cols (tablet) -> 3 cols (laptop) -> 6 cols (2xl) -->
<!-- ========================================================================= -->
<div id="sec-overview" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-3.5 sm:gap-4">

    <!-- Card 1: Air Temp & Humidity (SHT45 Microclimate) -->
    <div class="kpi" id="cardAir">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 font-tech"><i class="fa-solid fa-temperature-three-quarters text-amber-400"></i><span class="whitespace-nowrap">สภาพอากาศ (SHT45)</span></div>
            <span id="badgeSht45" class="pill pill-ok">I2C ONLINE</span>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="min-w-0">
                <span class="kpi-label block">อุณหภูมิอากาศ</span>
                <div class="flex items-baseline mt-0.5"><span id="dashTemp" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-white">--</span><span class="kpi-unit">°C</span></div>
                <span id="dashTempF" class="text-[10px] font-mono text-slate-500 block num">--</span>
            </div>
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <span class="kpi-label block">ความชื้นสัมพัทธ์</span>
                <div class="flex items-baseline justify-end mt-0.5"><span id="dashHum" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-cyan-400">--</span><span class="kpi-unit">%</span></div>
                <span class="text-[10px] font-mono text-cyan-500/80 block">RH</span>
            </div>
        </div>
        <svg id="sparkTemp" class="spark" viewBox="0 0 100 28" preserveAspectRatio="none" aria-hidden="true"><path stroke="#f59e0b" d=""/></svg>
        <div class="bar" title="อุณหภูมิ 10–40°C"><i id="barTemp" style="background:#f59e0b"></i></div>
        <div class="kpi-foot">
            <span>Dew: <strong id="dashDewPoint" class="text-cyan-300 num">--</strong></span>
            <span>Margin: <strong id="dashDewMargin" class="text-amber-300 num">--</strong></span>
        </div>
    </div>

    <!-- Card 2: VPD (Vapor Pressure Deficit & Penman Physics) -->
    <div class="kpi" id="cardVpd">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 font-tech"><i class="fa-solid fa-wind text-cyan-400"></i><span class="whitespace-nowrap">แรงดึงระเหยน้ำ (VPD)</span></div>
            <span class="pill pill-info">FAO-56</span>
        </div>
        <div class="grid grid-cols-2 gap-2 items-center">
            <div class="min-w-0">
                <span class="kpi-label block">Vapor Deficit</span>
                <div class="flex items-baseline mt-0.5"><span id="dashVpd" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-emerald-300">--</span><span class="kpi-unit">kPa</span></div>
            </div>
            <div class="text-right flex justify-end">
                <span id="dashVpdStatus" class="pill pill-mute !whitespace-normal text-center !leading-tight">รอข้อมูล</span>
            </div>
        </div>
        <svg id="sparkVpd" class="spark" viewBox="0 0 100 28" preserveAspectRatio="none" aria-hidden="true"><path stroke="#34d399" d=""/></svg>
        <div class="bar" title="โซนเหมาะสม 0.8–1.2 kPa (สเกล 0–3)">
            <span class="zone" style="left:26.6%;width:13.4%"></span>
            <i id="barVpd" style="background:#34d399"></i>
        </div>
        <div class="kpi-foot">
            <span>VPsat: <strong id="dashVpSat" class="text-slate-300 num">--</strong></span>
            <span>VPact: <strong id="dashVpAct" class="text-cyan-300 num">--</strong></span>
        </div>
    </div>

    <!-- Card 3: Soil Moisture (2 depths) -->
    <div class="kpi" id="cardSoilMoisture">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 font-tech"><i class="fa-solid fa-droplet text-cyan-400"></i><span class="whitespace-nowrap font-bold text-white">ความชื้นในดิน</span></div>
            <span id="badgeSoilMoistDepth" class="pill pill-info">Dual-Depth</span>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="min-w-0">
                <span class="kpi-label block text-cyan-300 font-semibold">ผิวดิน (Surface) <span class="text-[8px] bg-cyan-900/60 px-1 rounded font-mono">Analog</span></span>
                <div class="flex items-baseline mt-0.5"><span id="dashStickMoist" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-white">--</span><span class="kpi-unit !text-cyan-400">%</span></div>
                <span class="text-[9px] font-mono text-slate-500 block">0–10 cm</span>
            </div>
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <span class="kpi-label block">เขตราก (Root) <span class="text-[8px] bg-slate-800 px-1 rounded font-mono">Modbus</span></span>
                <div class="flex items-baseline justify-end mt-0.5"><span id="dashSoilMoist" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-cyan-400/90">--</span><span class="kpi-unit">%</span></div>
                <span class="text-[9px] font-mono text-slate-500 block">15–30 cm</span>
            </div>
        </div>
        <svg id="sparkSoil" class="spark" viewBox="0 0 100 28" preserveAspectRatio="none" aria-hidden="true"><path stroke="#22d3ee" d=""/></svg>
        <div class="space-y-1.5">
            <div class="bar" title="ผิวดิน (เหมาะสม 40–65%)"><span class="zone" style="left:40%;width:25%"></span><i id="barStick" style="background:#22d3ee"></i></div>
            <div class="bar" title="เขตราก (เหมาะสม 40–65%)"><span class="zone" style="left:40%;width:25%"></span><i id="barRoot" style="background:#38bdf8"></i></div>
        </div>

        <!-- Hidden backward-compat elements for scripts -->
        <span id="badgeSoilStick" class="hidden">ADC A1/A2</span>
        <span id="dashSoilMoistFoot" class="hidden">0.0%</span>

        <div class="kpi-foot">
            <span>ADC: <strong id="dashStickAdc" class="text-slate-300 num">--</strong></span>
            <span id="soilMoistStatus" class="pill pill-mute">รอข้อมูล</span>
            <span>Volt: <strong id="dashStickVolt" class="text-cyan-300 num">--</strong></span>
        </div>
    </div>

    <!-- Card 4: Soil pH & Chemistry -->
    <div class="kpi" id="cardSoil7in1">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 font-tech"><i class="fa-solid fa-flask-vial text-lime-400"></i><span class="whitespace-nowrap font-bold text-white">กรด-ด่างดิน (pH)</span></div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <span id="badgeSoil7in1Mode" class="pill pill-ok">pH เขตราก</span>
                <span id="badgeSoil7in1" class="pill pill-info">RS485</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 items-center">
            <div id="box7in1Hero" class="min-w-0">
                <div class="flex items-baseline"><span id="dash7in1HeroVal" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-lime-400">--</span><span id="dash7in1HeroUnit" class="kpi-unit !text-lime-300">pH</span></div>
                <span id="dash7in1HeroSub" class="text-[9px] font-mono text-lime-400/90 block font-semibold">รอข้อมูล</span>
            </div>
            <div class="text-right min-w-0 pl-1 border-l border-slate-800/80">
                <div id="dash7in1SecTitle" class="kpi-label">ความเค็มดิน (EC)</div>
                <div class="flex items-baseline justify-end mt-0.5"><span id="dashSoilEc" class="kpi-value num text-lg text-amber-300">--</span><span class="kpi-unit !text-[10px]">µS/cm</span></div>
                <div class="text-[9px] font-mono text-slate-400 mt-0.5">pH ผิว: <strong id="dashStickPh" class="text-amber-300 num">--</strong></div>
            </div>
        </div>

        <!-- Hidden elements for script backwards compatibility -->
        <span id="dashSoilPh" class="hidden">7.3</span>
        <span id="dash7in1SecVal" class="hidden">0.0</span>

        <div class="bar" title="pH 4–9 (โซนเหมาะสม 5.5–7.5)"><span class="zone" style="left:30%;width:40%"></span><i id="barPh" style="background:#a3e635"></i></div>

        <div class="grid grid-cols-3 gap-1 font-mono text-[9px]">
            <span class="bg-indigo-950/80 text-indigo-300 border border-indigo-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap num" id="dashCard4N">N --</span>
            <span class="bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap num" id="dashCard4P">P --</span>
            <span class="bg-amber-950/80 text-amber-300 border border-amber-700/60 px-1 py-0.5 rounded-md text-center font-bold whitespace-nowrap num" id="dashCard4K">K --</span>
        </div>

        <div class="kpi-foot">
            <span>pH ราก: <strong id="dashSoilPhFoot" class="text-lime-400 num">--</strong></span>
            <span id="soilPhStatus" class="pill pill-mute">รอข้อมูล</span>
            <span>Temp: <strong id="dashSoilTemp" class="text-slate-300 num">--</strong></span>
        </div>
    </div>

    <!-- Card 5: Solar PAR & Radiation (BH1750) -->
    <div class="kpi" id="cardLight">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 font-tech"><i class="fa-solid fa-sun text-yellow-400"></i><span class="whitespace-nowrap">แสงแดด &amp; รังสี</span></div>
            <span id="badgeBh1750" class="pill pill-warn">BH1750 I2C</span>
        </div>
        <div class="grid grid-cols-2 gap-2 items-center">
            <div class="min-w-0">
                <span class="kpi-label block">ความเข้มแสง</span>
                <div class="flex items-baseline mt-0.5"><span id="dashPar" class="kpi-value num text-2xl 2xl:text-[1.7rem] text-white">--</span><span class="kpi-unit">Lux</span></div>
            </div>
            <div class="min-w-0 pl-2 border-l border-slate-800/80 text-right">
                <span class="kpi-label block">Solar Rad</span>
                <span id="dashSolarRad" class="text-sm font-bold text-yellow-400 font-mono block mt-1 num">--</span>
            </div>
        </div>
        <svg id="sparkLux" class="spark" viewBox="0 0 100 28" preserveAspectRatio="none" aria-hidden="true"><path stroke="#facc15" d=""/></svg>
        <div class="bar" title="สเกลลอการิทึม 1–100,000 Lux"><i id="barLux" style="background:#facc15"></i></div>
        <div class="kpi-foot">
            <span>kLux: <strong id="dashKlux" class="text-yellow-300 num">--</strong></span>
            <span id="dashAiConfidence" class="text-emerald-400 font-bold whitespace-nowrap">AI: --</span>
        </div>
    </div>

    <!-- Card 6: Dual Storage (Micro-SD Card on ESP32 + SQLite3 Database) -->
    <div class="kpi !border-cyan-500/40 !bg-gradient-to-br from-slate-900/90 to-cyan-950/30">
        <div class="kpi-head">
            <div class="flex items-center gap-1.5 text-cyan-300 font-medium font-tech"><i class="fa-solid fa-server text-cyan-400"></i><span class="whitespace-nowrap">การจัดเก็บข้อมูล 2 ชั้น</span></div>
            <span id="dashSdBadge" class="pill pill-mute">STANDBY (NO SD)</span>
        </div>
        <div class="space-y-1.5 text-[11px] font-mono">
            <button type="button" class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-900/90 border border-slate-800 gap-1 hover:border-amber-500/40 transition" onclick="toggleOrCheckSdCard()" title="คลิกเพื่อสลับ/ตรวจสอบสถานะ Micro-SD Card">
                <span class="text-amber-300 flex items-center gap-1 text-[10px] whitespace-nowrap"><i class="fa-solid fa-sd-card"></i> SD Card:</span>
                <strong id="dashSdRecords" class="text-slate-400 italic text-[10px] whitespace-nowrap">ไม่ได้ใส่การ์ด</strong>
            </button>
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/90 border border-slate-800 gap-1">
                <span class="text-emerald-300 flex items-center gap-1 text-[10px] whitespace-nowrap"><i class="fa-solid fa-database"></i> SQLite DB:</span>
                <strong id="dashDbRecords" class="text-white text-[10px] whitespace-nowrap num">--</strong>
            </div>
        </div>
        <div class="kpi-foot">
            <span>File: <span class="text-amber-300 font-bold">.csv &amp; .db</span></span>
            <a href="../api/api.php?action=export_csv" class="text-cyan-400 hover:underline font-bold">โหลด CSV</a>
        </div>
    </div>

</div>
