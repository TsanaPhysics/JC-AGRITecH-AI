
        <!-- ROW 1: 6 Real-time Telemetry Metrics Cards (All Quantities Completely Displayed) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            
            <!-- Card 1: Air Temp & Humidity (SHT45 Microclimate) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-temperature-three-quarters text-amber-400"></i>
                        <span>สภาพอากาศ (SHT45)</span>
                    </div>
                    <span id="badgeSht45" class="text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">I2C ONLINE</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashTemp" class="text-2xl lg:text-3xl font-bold font-mono text-white">31.4</span>
                        <span class="text-xs text-slate-400">°C</span>
                        <span id="dashTempF" class="text-[10px] font-mono text-slate-500 block">(88.5°F)</span>
                    </div>
                    <div class="text-right">
                        <span id="dashHum" class="text-xl lg:text-2xl font-bold font-mono text-cyan-400">85.0</span>
                        <span class="text-xs text-slate-400">%RH</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>Dew: <strong id="dashDewPoint" class="text-cyan-300">28.4°C</strong></span>
                    <span>Margin: <strong id="dashDewMargin" class="text-amber-300">3.0°C</strong></span>
                </div>
            </div>

            <!-- Card 2: VPD (Vapor Pressure Deficit & Penman Physics) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-wind text-cyan-400"></i>
                        <span>แรงดึงระเหยน้ำ (VPD)</span>
                    </div>
                    <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">FAO-56</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashVpd" class="text-2xl lg:text-3xl font-bold font-mono text-emerald-300">0.69</span>
                        <span class="text-xs text-slate-400">kPa</span>
                    </div>
                    <span id="dashVpdStatus" class="text-[10px] font-bold text-amber-300 bg-amber-950 px-1.5 py-0.5 rounded">ชื้นสูง</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>VPsat: <strong id="dashVpSat" class="text-slate-300">4.60</strong></span>
                    <span>VPact: <strong id="dashVpAct" class="text-cyan-300">3.91</strong></span>
                </div>
            </div>

            <!-- Card 3: Surface Soil Stick (0-10 cm) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i>
                        <span>ผิวดิน (Soil Stick)</span>
                    </div>
                    <span id="badgeSoilStick" class="text-[9px] font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">ADC A1/A2</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashStickMoist" class="text-2xl lg:text-3xl font-bold font-mono text-white">65.0</span>
                        <span class="text-xs text-slate-400">%</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400">pH ผิวดิน</span>
                        <span id="dashStickPh" class="text-lg lg:text-xl font-bold font-mono text-amber-300 block">6.2</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>ADC: <strong id="dashStickAdc" class="text-slate-300">1850</strong></span>
                    <span>Volt: <strong id="dashStickVolt" class="text-amber-300">1.85V</strong></span>
                </div>
            </div>

            <!-- Card 4: Deep Root Soil 7-in-1 (RS485 Modbus RTU) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-seedling text-emerald-400"></i>
                        <span>ดินเขตราก (7-in-1)</span>
                    </div>
                    <span id="badgeSoil7in1" class="text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">RS485 MODBUS</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashSoilMoist" class="text-2xl lg:text-3xl font-bold font-mono text-white">4.8</span>
                        <span class="text-xs text-slate-400">%</span>
                    </div>
                    <div class="text-right">
                        <span id="dashSoilEc" class="text-lg lg:text-xl font-bold font-mono text-emerald-400">0.0</span>
                        <span class="text-[10px] text-slate-400">µS/cm</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>pH: <strong id="dashSoilPh" class="text-emerald-400 text-xs">8.6</strong></span>
                    <span>Temp: <strong id="dashSoilTemp" class="text-slate-300">27.50°C</strong></span>
                </div>
                <!-- NPK Capsules เหมือนหน้าจอบอร์ด ESP32 -->
                <div class="flex items-center justify-between gap-1 pt-1 font-mono text-[10px]">
                    <span class="bg-indigo-950/80 text-indigo-300 border border-indigo-700/60 px-2 py-0.5 rounded-full flex-1 text-center font-bold" id="dashCard4N">N 24.2</span>
                    <span class="bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 px-2 py-0.5 rounded-full flex-1 text-center font-bold" id="dashCard4P">P 14.1</span>
                    <span class="bg-amber-950/80 text-amber-300 border border-amber-700/60 px-2 py-0.5 rounded-full flex-1 text-center font-bold" id="dashCard4K">K 0.0</span>
                </div>
            </div>

            <!-- Card 5: Solar PAR & Radiation (BH1750 Dome) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-yellow-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-sun text-yellow-400"></i>
                        <span>แสงแดด & รังสี</span>
                    </div>
                    <span id="badgeBh1750" class="text-[9px] font-mono text-yellow-400 bg-yellow-950 px-2 py-0.5 rounded border border-yellow-800">BH1750 I2C</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashPar" class="text-2xl lg:text-3xl font-bold font-mono text-white">897.5</span>
                        <span class="text-xs text-slate-400">Lux</span>
                    </div>
                    <span id="dashSolarRad" class="text-[10px] font-bold text-yellow-400 bg-yellow-950 px-1.5 py-0.5 rounded font-mono">7.09 W/m²</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>kLux: <strong id="dashKlux" class="text-yellow-300">0.90</strong></span>
                    <span id="dashAiConfidence" class="text-emerald-400 font-bold">AI: 98.4%</span>
                </div>
            </div>

            <!-- Card 6: Dual Storage (Micro-SD Card on ESP32 + SQLite3 Database) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-cyan-500/40 space-y-3 relative overflow-hidden group hover:border-cyan-400 transition bg-gradient-to-br from-slate-900/90 to-cyan-950/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-cyan-300 font-medium font-tech">
                        <i class="fa-solid fa-server text-cyan-400"></i>
                        <span>การจัดเก็บข้อมูล 2 ชั้น</span>
                    </div>
                    <span id="dashSdBadge" class="text-[9px] font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded border border-slate-700">STANDBY (NO SD)</span>
                </div>
                <div class="space-y-1.5 text-[11px] font-mono">
                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800">
                        <span class="text-amber-300 flex items-center gap-1"><i class="fa-solid fa-sd-card"></i> SD Card:</span>
                        <strong id="dashSdRecords" class="text-slate-400 italic">ไม่ได้ใส่การ์ด (No Card)</strong>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800">
                        <span class="text-emerald-300 flex items-center gap-1"><i class="fa-solid fa-database"></i> SQLite DB:</span>
                        <strong id="dashDbRecords" class="text-white">Auto Sync</strong>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-800/60 font-mono">
                    <span>File: <span class="text-amber-300 font-bold">.csv &amp; .db</span></span>
                    <a href="../api/api.php?action=export_csv" class="text-cyan-400 hover:underline font-bold">โหลด CSV</a>
                </div>
            </div>

        </div>
