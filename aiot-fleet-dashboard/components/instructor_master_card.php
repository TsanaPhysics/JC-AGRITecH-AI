<!-- ========================================================================= -->
<!-- COMPONENT: INSTRUCTOR MASTER DEMONSTRATION CARD                            -->
<!-- Highlights the Main Demo Board (/leqs-workshop/dashboard/index.php)        -->
<!-- for the 10-15 Workshop Participant Groups                                  -->
<!-- ========================================================================= -->
<section id="instructorMasterSection" class="relative overflow-hidden rounded-3xl border border-amber-500/40 bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950/20 shadow-2xl shadow-amber-500/10 p-5 sm:p-6 transition-all duration-300">
    
    <!-- Ambient Glow Effects -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 space-y-4">

        <!-- Top Header & Meta Row -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-white/10 pb-4">
            
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-xs">
                        <i class="fa-solid fa-crown text-amber-400"></i> INSTRUCTOR MASTER NODE
                    </span>
                    <span class="text-xs font-mono font-bold text-cyan-300 bg-cyan-950/80 px-2.5 py-0.5 rounded-full border border-cyan-500/30">
                        ESP32-S3 ATD3.5 Pro
                    </span>
                    <span class="text-xs font-tech text-emerald-400 bg-emerald-950/80 px-2.5 py-0.5 rounded-full border border-emerald-500/30 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> USB-C SERIAL BRIDGE ACTIVE
                    </span>
                    <span class="text-[10px] font-mono text-purple-300 bg-purple-950/80 px-2 py-0.5 rounded-full border border-purple-500/30">
                        Screen 11 Main Hub
                    </span>
                </div>

                <div class="flex items-baseline gap-3 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-black font-tech text-white tracking-wide flex items-center gap-2">
                        <span>⭐ บอร์ดสาธิตหลักประจำห้องอบรม</span>
                        <span class="text-sm font-normal text-amber-300/80 font-tech">(LEQs-xAI Reference Benchmark)</span>
                    </h2>
                </div>

                <p class="text-xs text-slate-300 font-tech flex items-center gap-4 flex-wrap">
                    <span><i class="fa-solid fa-network-wired text-cyan-400 mr-1"></i> IP: <strong class="text-white font-mono" id="masterHeroIp">192.168.0.111:8500</strong></span>
                    <span><i class="fa-solid fa-wifi text-emerald-400 mr-1"></i> SSID: <strong class="text-white font-mono" id="masterHeroSsid">JC_Home</strong></span>
                    <span><i class="fa-solid fa-location-dot text-rose-400 mr-1"></i> พิกัด: <strong class="text-white font-mono" id="masterHeroGps">12.6644° N, 102.1039° E (มรภ.รำไพพรรณี)</strong></span>
                    <span><i class="fa-solid fa-clock text-amber-400 mr-1"></i> ซิงค์สด: <strong class="text-slate-300 font-mono" id="masterHeroSyncTime">Real-time</strong></span>
                </p>
            </div>

            <!-- Action Buttons: Quick Navigation to Main Workshop System -->
            <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                <a href="../leqs-workshop/dashboard/index.php" target="_blank" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white text-xs font-bold font-tech flex items-center gap-2 shadow-lg shadow-emerald-950/50 transition transform hover:scale-[1.02]">
                    <i class="fa-solid fa-gauge-high text-sm"></i>
                    <span>เปิดแดชบอร์ดสาธิตหลัก</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>

                <a href="../leqs-workshop/mobile/index.php" target="_blank" class="px-3.5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-cyan-500/30 text-xs font-bold font-tech flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-mobile-screen text-sm"></i>
                    <span>แอปมือถือ</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                </a>

                <button onclick="selectBoard(1)" class="px-3.5 py-2.5 rounded-2xl bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/40 text-xs font-bold font-tech flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-bullseye"></i>
                    <span>โฟกัสบอร์ดนี้</span>
                </button>
            </div>

        </div>

        <!-- 6-Metric Live Sensor Matrix from Instructor Board -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            
            <!-- Metric 1: Microclimate (SHT45) -->
            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1 group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-temperature-three-quarters text-amber-400"></i> อากาศ (SHT45)</span>
                    <span class="text-[9px] font-mono text-emerald-400">I2C</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValTemp" class="text-xl sm:text-2xl font-black font-mono text-white whitespace-nowrap">24.4</span>
                    <span class="text-xs font-mono text-slate-400 font-bold">°C</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/5 pt-1">
                    <span>ชื้น: <strong id="masterValHum" class="text-cyan-300">65.7%</strong></span>
                    <span>Dew: <strong id="masterValDew" class="text-emerald-300">17.6°</strong></span>
                </div>
            </div>

            <!-- Metric 2: VPD Transpiration -->
            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1 group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-wind text-cyan-400"></i> ระเหยน้ำ (VPD)</span>
                    <span class="text-[9px] font-mono text-cyan-400">FAO-56</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValVpd" class="text-xl sm:text-2xl font-black font-mono text-emerald-300 whitespace-nowrap">1.05</span>
                    <span class="text-xs font-mono text-slate-400 font-bold">kPa</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/5 pt-1">
                    <span class="text-emerald-400 font-bold">สมบูรณ์ (Optimal)</span>
                    <span>VPsat: <strong id="masterValVpsat" class="text-slate-300">3.06</strong></span>
                </div>
            </div>

            <!-- Metric 3: Dedicated Soil Moisture -->
            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1 group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-droplet text-cyan-400"></i> ความชื้นดิน</span>
                    <span class="text-[9px] font-mono text-cyan-300 font-bold bg-cyan-950/80 px-1.5 py-0.5 rounded border border-cyan-500/40">ผิวดิน</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValStickMoist" class="text-xl sm:text-2xl font-black font-mono text-cyan-300 whitespace-nowrap">61.8</span>
                    <span class="text-xs font-mono text-cyan-400 font-bold">%</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/5 pt-1">
                    <span>เขตราก: <strong id="masterValSoilMoist" class="text-cyan-300">0.0%</strong></span>
                    <span>ADC: <strong id="masterValStickAdc" class="text-slate-300">2031</strong></span>
                </div>
            </div>

            <!-- Metric 4: Dedicated Soil pH (Root Zone 7-in-1 + Surface Stick pH) -->
            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1 group hover:border-lime-500/40 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-flask text-lime-400"></i> กรด-ด่างดิน (Soil pH)</span>
                    <span class="text-[9px] font-mono text-lime-300 font-bold bg-lime-950/80 px-1.5 py-0.5 rounded border border-lime-500/40">7-in-1 RS485</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValSoilPh" class="text-xl sm:text-2xl font-black font-mono text-lime-400 whitespace-nowrap">7.60</span>
                    <span class="text-xs font-mono text-lime-300 font-bold">pH</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/5 pt-1">
                    <span>pH ผิว Stick: <strong id="masterValStickPh" class="text-amber-300">3.20</strong></span>
                    <span>EC ดิน: <strong id="masterValSoilEc" class="text-slate-300">0 µS</strong></span>
                </div>
            </div>

            <!-- Metric 5: Solar Radiation & PAR (BH1750) -->
            <div class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 space-y-1 group hover:border-yellow-500/40 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-sun text-yellow-400"></i> แสงแดด (BH1750)</span>
                    <span class="text-[9px] font-mono text-yellow-400">I2C</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValLux" class="text-xl sm:text-2xl font-black font-mono text-white whitespace-nowrap">35.8</span>
                    <span class="text-xs font-mono text-slate-400 font-bold">Lux</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/5 pt-1">
                    <span>Rad: <strong id="masterValSolarRad" class="text-yellow-400">0.28 W/m²</strong></span>
                    <span>AI: <strong id="masterValAiConf" class="text-emerald-400">74.5%</strong></span>
                </div>
            </div>

            <!-- Metric 6: TinyML Edge AI Fusion NPK -->
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-purple-950/50 to-slate-900 border border-purple-500/30 space-y-1 group hover:border-purple-400/60 transition">
                <div class="flex items-center justify-between text-[11px] text-purple-300 font-tech">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-brain text-purple-400"></i> TinyML Edge AI</span>
                    <span class="text-[9px] font-mono text-purple-300 bg-purple-900/60 px-1.5 py-0.2 rounded">Edge ML</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="masterValAiPh" class="text-xl sm:text-2xl font-black font-mono text-purple-300 whitespace-nowrap">8.36</span>
                    <span class="text-xs font-mono text-purple-400 font-bold">AI pH</span>
                </div>
                <div class="flex items-center justify-between text-[10px] font-mono text-slate-300 border-t border-white/5 pt-1">
                    <span class="truncate">N-P-K: <strong id="masterValAiNpk" class="text-emerald-300 font-bold">17-11-10</strong></span>
                    <span class="text-[9px] text-purple-400">mg/kg</span>
                </div>
            </div>

        </div>

        <!-- Instructor Quick Relay Controls & Benchmark Notice -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-3 pt-1 text-xs font-tech">
            
            <div class="flex items-center gap-2 text-slate-400 text-xs">
                <i class="fa-solid fa-info-circle text-amber-400 text-sm flex-shrink-0"></i>
                <span><strong>เกณฑ์อ้างอิงกลาง (Benchmark Node):</strong> ผู้เข้าร่วมทั้ง 15 กลุ่ม สามารถเปรียบเทียบพารามิเตอร์เซนเซอร์ของโต๊ะตนเองเทียบกับบอร์ดสาธิตหลักนี้ได้ทันที</span>
            </div>

            <!-- Master Relay Direct Bar -->
            <div class="flex items-center gap-2 bg-slate-950/80 px-3 py-1.5 rounded-xl border border-white/10 flex-shrink-0">
                <span class="text-[11px] font-mono text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-bolt text-amber-400 text-[10px]"></i> รีเลย์สาธิต:
                </span>
                <div class="flex items-center gap-1 font-mono text-[10px]">
                    <button onclick="toggleActiveBoardRelay(1, 1)" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition" id="masterRelayBtn1">CH1 ปั๊ม 1</button>
                    <button onclick="toggleActiveBoardRelay(2, 1)" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition" id="masterRelayBtn2">CH2 วาล์ว 2</button>
                    <button onclick="toggleActiveBoardRelay(3, 1)" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition" id="masterRelayBtn3">CH3 ผิวดิน</button>
                    <button onclick="toggleActiveBoardRelay(4, 1)" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition" id="masterRelayBtn4">CH4 พ่นหมอก</button>
                </div>
            </div>

        </div>

    </div>
</section>
