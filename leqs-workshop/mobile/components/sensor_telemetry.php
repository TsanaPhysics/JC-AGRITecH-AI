            <!-- 4. PHYSICAL SENSOR TELEMETRY & EDGE AI (SOIL 7-IN-1, MICRO-CLIMATE, CAMERA) -->
            <div class="space-y-4">
                
                <!-- Soil 7-in-1 Modbus & TinyML Edge AI Live Telemetry -->
                <div class="glass-inner-panel rounded-2xl p-4 border border-white/5 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-white/5">
                        <span class="text-xs font-bold text-white font-tech flex items-center gap-1.5">
                            <i class="fa-solid fa-seedling text-emerald-400"></i> RS485 Soil 7-in-1 & TinyML Edge AI
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span id="badgeSoilModbus" class="text-[9px] font-mono text-emerald-300 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-500/40 font-bold">
                                RS485 ONLINE
                            </span>
                            <span id="badgeTinyML" class="text-[9px] font-mono text-purple-300 bg-purple-950 px-2 py-0.5 rounded border border-purple-500/40 font-bold">
                                TinyML AI 99.2%
                            </span>
                        </div>
                    </div>

                    <!-- Dedicated Dual-Insight: Raw Sensor vs TinyML Edge AI Matrix -->
                    <div class="rounded-2xl p-3.5 border border-purple-500/30 bg-gradient-to-br from-slate-950/90 to-purple-950/30 space-y-3">
                        <div class="flex items-center justify-between text-xs font-tech">
                            <span class="text-white font-bold flex items-center gap-1.5">
                                <i class="fa-solid fa-code-compare text-cyan-400"></i> เซนเซอร์ตรง VS โมเดล Edge AI
                            </span>
                            <span class="text-[9px] font-mono text-purple-300 bg-purple-900/60 px-2 py-0.5 rounded-full border border-purple-500/40">
                                TinyML: <strong id="mobAiConfBadge">77.6%</strong>
                            </span>
                        </div>

                        <!-- 4 Dual-Column Mini Cards -->
                        <div class="grid grid-cols-2 gap-2 text-xs font-mono">
                            <!-- pH Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5 space-y-1">
                                <span class="text-[10px] text-gray-400 font-sans block">กรด-ด่าง (Soil pH)</span>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] text-cyan-400 block">โพรบดิบ</span>
                                        <span id="mobRawPh" class="text-base font-bold text-cyan-300">8.20</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] text-purple-400 block">โมเดล AI</span>
                                        <span id="mobAiPh" class="text-base font-bold text-lime-400">9.14</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Moisture Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5 space-y-1">
                                <span class="text-[10px] text-gray-400 font-sans block">ความชื้นดิน (%)</span>
                                <div class="grid grid-cols-3 gap-1 items-center">
                                    <div>
                                        <span class="text-[9px] text-emerald-400 block truncate" title="Soil Stick ผิวดิน 0-10cm ตรงกับจอ ESP32">จอ ESP32</span>
                                        <span id="mobStickMoist" class="text-sm font-bold text-emerald-300">61.3%</span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] text-cyan-400 block truncate" title="โพรบ 7-in-1 ดินลึก 10-30cm">รากลึก 7-in-1</span>
                                        <span id="mobRawMoist" class="text-sm font-bold text-cyan-300">2.6%</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] text-purple-400 block">AI ชดเชย</span>
                                        <span id="mobAiMoist" class="text-sm font-bold text-purple-300">20.5%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- NPK Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5 space-y-1">
                                <span class="text-[10px] text-gray-400 font-sans block">ธาตุอาหาร N-P-K (mg/kg)</span>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] text-cyan-400 block">โพรบดิบ</span>
                                        <span id="mobRawNpk" class="text-xs font-bold text-slate-400 block">0-0-0</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] text-purple-400 block">โมเดล AI</span>
                                        <span id="mobAiNpk" class="text-xs font-bold text-emerald-400 block">15-13-4</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Microclimate VPD Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-white/5 space-y-1">
                                <span class="text-[10px] text-gray-400 font-sans block">แรงดึงระเหยน้ำ (VPD)</span>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] text-cyan-400 block">SHT45</span>
                                        <span id="mobRawVpd" class="text-base font-bold text-cyan-300">1.35</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] text-purple-400 block">โมเดล AI</span>
                                        <span id="mobAiVpdStatus" class="text-xs font-bold text-emerald-400 block mt-0.5">สมบูรณ์</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agronomy Warning Alert -->
                        <div class="p-2.5 rounded-xl bg-black/40 border border-purple-500/20 text-[10px] font-mono text-gray-300 flex items-start gap-2">
                            <i class="fa-solid fa-stethoscope text-purple-400 mt-0.5 text-xs"></i>
                            <div id="mobAiAlertText" class="leading-tight">
                                พบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick 3.03 vs เขตรากลึก 8.20) • ดินเขตราก 10-30cm มีความชื้นต่ำ 2.6% แนะนำสั่งเปิดวาล์วน้ำโซลินอยด์
                            </div>
                        </div>
                    </div>

                    <!-- SVG Gauge Row 2: pH / EC / Soil Temp / Light -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 py-2">

                        <!-- GAUGE PH: LIME GREEN -->
                        <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-lime-500/30 transition group">
                            <div class="relative w-36 h-36 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="48" stroke="rgba(255,255,255,0.06)" stroke-width="7" fill="none" />
                                    <circle cx="60" cy="60" r="38" stroke="rgba(132,204,22,0.15)" stroke-width="2" fill="none" stroke-dasharray="4,4" />
                                    <circle id="arcPh" cx="60" cy="60" r="48"
                                            stroke="url(#gradPh)" stroke-width="7" fill="none"
                                            stroke-dasharray="301.59" stroke-dashoffset="150"
                                            stroke-linecap="round"
                                            style="filter:drop-shadow(0 0 6px #84cc16);" class="transition-all duration-700" />
                                    <defs>
                                        <linearGradient id="gradPh" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#84cc16" />
                                            <stop offset="100%" stop-color="#22c55e" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <div class="flex items-start">
                                        <span id="gaugeValPh" class="text-2xl font-bold font-mono text-white leading-none">7.1</span>
                                        <span class="text-xs font-mono text-lime-400 font-bold ml-0.5">pH</span>
                                    </div>
                                    <span class="text-[11px] font-mono text-lime-400/90 font-bold mt-0.5">Soil pH</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-200 mt-2 font-tech">pH ดิน</span>
                            <span id="gaugeLabPh" class="text-[11px] font-mono text-lime-400 font-medium">Neutral</span>
                            <!-- hidden compat spans -->
                            <span id="detailPh" class="hidden">7.1</span>
                            <span id="detailStickPh" class="hidden">7.1</span>
                        </div>

                        <!-- GAUGE EC: PURPLE -->
                        <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-purple-500/30 transition group">
                            <div class="relative w-36 h-36 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="48" stroke="rgba(255,255,255,0.06)" stroke-width="7" fill="none" />
                                    <circle cx="60" cy="60" r="38" stroke="rgba(168,85,247,0.15)" stroke-width="2" fill="none" stroke-dasharray="4,4" />
                                    <circle id="arcEc" cx="60" cy="60" r="48"
                                            stroke="url(#gradEc)" stroke-width="7" fill="none"
                                            stroke-dasharray="301.59" stroke-dashoffset="290"
                                            stroke-linecap="round"
                                            style="filter:drop-shadow(0 0 6px #a855f7);" class="transition-all duration-700" />
                                    <defs>
                                        <linearGradient id="gradEc" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#a855f7" />
                                            <stop offset="100%" stop-color="#6366f1" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <div class="flex items-start">
                                        <span id="gaugeValEc" class="text-2xl font-bold font-mono text-white leading-none">0.0</span>
                                        <span class="text-[10px] font-mono text-purple-400 font-bold ml-0.5">µS</span>
                                    </div>
                                    <span class="text-[11px] font-mono text-purple-400/90 font-bold mt-0.5">EC Soil</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-200 mt-2 font-tech">EC ดิน</span>
                            <span id="gaugeLabEc" class="text-[11px] font-mono text-purple-400 font-medium">Low</span>
                            <!-- hidden compat spans -->
                            <span id="detailEc" class="hidden">0.0</span>
                        </div>

                        <!-- GAUGE SOIL TEMP: ORANGE -->
                        <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-orange-500/30 transition group">
                            <div class="relative w-36 h-36 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="48" stroke="rgba(255,255,255,0.06)" stroke-width="7" fill="none" />
                                    <circle cx="60" cy="60" r="38" stroke="rgba(249,115,22,0.15)" stroke-width="2" fill="none" stroke-dasharray="4,4" />
                                    <circle id="arcSoilTemp" cx="60" cy="60" r="48"
                                            stroke="url(#gradSoilTemp)" stroke-width="7" fill="none"
                                            stroke-dasharray="301.59" stroke-dashoffset="130"
                                            stroke-linecap="round"
                                            style="filter:drop-shadow(0 0 6px #f97316);" class="transition-all duration-700" />
                                    <defs>
                                        <linearGradient id="gradSoilTemp" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#f97316" />
                                            <stop offset="100%" stop-color="#ef4444" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <div class="flex items-start">
                                        <span id="gaugeValSoilTemp" class="text-2xl font-bold font-mono text-white leading-none">27.5</span>
                                        <span class="text-xs font-mono text-orange-400 font-bold ml-0.5">°C</span>
                                    </div>
                                    <span class="text-[11px] font-mono text-orange-400/90 font-bold mt-0.5">Root Zone</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-200 mt-2 font-tech">อุณหภูมิดิน</span>
                            <span id="gaugeLabSoilTemp" class="text-[11px] font-mono text-orange-400 font-medium">Normal</span>
                            <!-- hidden compat spans -->
                            <span id="detailSoilTemp" class="hidden">27.5</span>
                        </div>

                        <!-- GAUGE LIGHT: YELLOW -->
                        <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-yellow-500/30 transition group">
                            <div class="relative w-36 h-36 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="48" stroke="rgba(255,255,255,0.06)" stroke-width="7" fill="none" />
                                    <circle cx="60" cy="60" r="38" stroke="rgba(234,179,8,0.15)" stroke-width="2" fill="none" stroke-dasharray="4,4" />
                                    <circle id="arcLight" cx="60" cy="60" r="48"
                                            stroke="url(#gradLight)" stroke-width="7" fill="none"
                                            stroke-dasharray="301.59" stroke-dashoffset="200"
                                            stroke-linecap="round"
                                            style="filter:drop-shadow(0 0 6px #eab308);" class="transition-all duration-700" />
                                    <defs>
                                        <linearGradient id="gradLight" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#eab308" />
                                            <stop offset="100%" stop-color="#f59e0b" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <div class="flex items-start">
                                        <span id="gaugeValLight" class="text-2xl font-bold font-mono text-white leading-none">0</span>
                                        <span class="text-[10px] font-mono text-yellow-400 font-bold ml-0.5">Lx</span>
                                    </div>
                                    <span id="gaugeLabLight2" class="text-[11px] font-mono text-yellow-400/90 font-bold mt-0.5">PAR</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-200 mt-2 font-tech">แสงแดด / Rad</span>
                            <span id="gaugeLabLight" class="text-[11px] font-mono text-yellow-400 font-medium">Dim</span>
                            <!-- hidden compat spans -->
                            <span id="detailPar" class="hidden">0</span>
                            <span id="detailSolarRad" class="hidden">0 W/m²</span>
                        </div>

                    </div>

                    <!-- Real-Time NPK Breakdown & Edge AI Calibration — Premium Layout -->
                    <div class="p-4 rounded-2xl border border-white/6 space-y-4" style="background:linear-gradient(145deg,rgba(2,8,20,0.85),rgba(10,18,40,0.9));">
                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-300 font-tech flex items-center gap-1.5">
                                <i class="fa-solid fa-flask-vial text-emerald-400"></i> ธาตุอาหารหลัก NPK
                            </span>
                            <span id="npkTotal" class="text-sm font-bold text-emerald-400 font-mono">รวม: 38.3 mg/kg</span>
                        </div>

                        <!-- 3 NPK Cards -->
                        <div class="grid grid-cols-3 gap-3">

                            <!-- N — Nitrogen -->
                            <div class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-emerald-500/25 bg-emerald-950/20">
                                <span class="text-[11px] font-bold text-emerald-300 font-tech tracking-wide">ไนโตรเจน</span>
                                <span class="text-[11px] text-gray-500 font-mono -mt-1">(N)</span>
                                <!-- Big value -->
                                <div class="flex items-baseline gap-0.5">
                                    <span id="detailN" class="font-black text-emerald-400 leading-none" style="font-size:2.4rem;">24.2</span>
                                </div>
                                <span class="text-[10px] text-gray-500 font-mono -mt-1">mg/kg</span>
                                <!-- AI value -->
                                <span class="text-[10px] text-emerald-300/70 font-mono">AI: <span id="aiN">24.2</span></span>
                                <!-- Level bar -->
                                <div class="w-full space-y-1">
                                    <div class="w-full h-2.5 rounded-full bg-black/50 overflow-hidden relative">
                                        <!-- gradient track: Very Low→Low→Medium→High→Very High -->
                                        <div class="absolute inset-0 rounded-full" style="background:linear-gradient(to right,#ef4444,#f97316,#22c55e,#3b82f6,#8b5cf6);opacity:0.18;"></div>
                                        <!-- fill bar -->
                                        <div id="barN" class="h-full rounded-full transition-all duration-700"
                                             style="width:12%;background:linear-gradient(to right,#6ee7b7,#10b981);box-shadow:0 0 6px #10b98188;"></div>
                                    </div>
                                    <div class="flex justify-between text-[8px] text-gray-600 font-mono">
                                        <span>0</span><span>50</span><span>100</span><span>150</span><span>200</span>
                                    </div>
                                    <div id="statusN" class="text-center text-[10px] font-bold text-orange-400 font-tech tracking-wide">ต่ำมาก</div>
                                </div>
                            </div>

                            <!-- P — Phosphorus -->
                            <div class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-cyan-500/25 bg-cyan-950/20">
                                <span class="text-[11px] font-bold text-cyan-300 font-tech tracking-wide">ฟอสฟอรัส</span>
                                <span class="text-[11px] text-gray-500 font-mono -mt-1">(P)</span>
                                <div class="flex items-baseline gap-0.5">
                                    <span id="detailP" class="font-black text-cyan-400 leading-none" style="font-size:2.4rem;">14.1</span>
                                </div>
                                <span class="text-[10px] text-gray-500 font-mono -mt-1">mg/kg</span>
                                <span class="text-[10px] text-cyan-300/70 font-mono">AI: <span id="aiP">14.1</span></span>
                                <div class="w-full space-y-1">
                                    <div class="w-full h-2.5 rounded-full bg-black/50 overflow-hidden relative">
                                        <div class="absolute inset-0 rounded-full" style="background:linear-gradient(to right,#ef4444,#f97316,#22c55e,#3b82f6,#8b5cf6);opacity:0.18;"></div>
                                        <div id="barP" class="h-full rounded-full transition-all duration-700"
                                             style="width:14%;background:linear-gradient(to right,#67e8f9,#06b6d4);box-shadow:0 0 6px #06b6d488;"></div>
                                    </div>
                                    <div class="flex justify-between text-[8px] text-gray-600 font-mono">
                                        <span>0</span><span>25</span><span>50</span><span>75</span><span>100</span>
                                    </div>
                                    <div id="statusP" class="text-center text-[10px] font-bold text-orange-400 font-tech tracking-wide">ต่ำมาก</div>
                                </div>
                            </div>

                            <!-- K — Potassium -->
                            <div class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-amber-500/25 bg-amber-950/20">
                                <span class="text-[11px] font-bold text-amber-300 font-tech tracking-wide">โพแทสเซียม</span>
                                <span class="text-[11px] text-gray-500 font-mono -mt-1">(K)</span>
                                <div class="flex items-baseline gap-0.5">
                                    <span id="detailK" class="font-black text-amber-400 leading-none" style="font-size:2.4rem;">0.0</span>
                                </div>
                                <span class="text-[10px] text-gray-500 font-mono -mt-1">mg/kg</span>
                                <span class="text-[10px] text-amber-300/70 font-mono">AI: <span id="aiK">0.0</span></span>
                                <div class="w-full space-y-1">
                                    <div class="w-full h-2.5 rounded-full bg-black/50 overflow-hidden relative">
                                        <div class="absolute inset-0 rounded-full" style="background:linear-gradient(to right,#ef4444,#f97316,#22c55e,#3b82f6,#8b5cf6);opacity:0.18;"></div>
                                        <div id="barK" class="h-full rounded-full transition-all duration-700"
                                             style="width:0%;background:linear-gradient(to right,#fcd34d,#f59e0b);box-shadow:0 0 6px #f59e0b88;"></div>
                                    </div>
                                    <div class="flex justify-between text-[8px] text-gray-600 font-mono">
                                        <span>0</span><span>75</span><span>150</span><span>225</span><span>300</span>
                                    </div>
                                    <div id="statusK" class="text-center text-[10px] font-bold text-red-400 font-tech tracking-wide">ต่ำมาก</div>
                                </div>
                            </div>
                        </div>

                        <!-- Legend row -->
                        <div class="flex items-center justify-center gap-2 pt-1 flex-wrap">
                            <span class="text-[9px] font-mono text-red-400 flex items-center gap-0.5"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>ต่ำมาก</span>
                            <span class="text-[9px] font-mono text-orange-400 flex items-center gap-0.5"><span class="w-2 h-2 rounded-full bg-orange-500 inline-block"></span>ต่ำ</span>
                            <span class="text-[9px] font-mono text-emerald-400 flex items-center gap-0.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>เหมาะสม</span>
                            <span class="text-[9px] font-mono text-blue-400 flex items-center gap-0.5"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>สูง</span>
                            <span class="text-[9px] font-mono text-purple-400 flex items-center gap-0.5"><span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span>สูงมาก</span>
                        </div>

                        <!-- Footer ratio + confidence -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-gray-400 pt-2 border-t border-white/6">
                            <span>N:P:K = <strong id="npkRatio" class="text-emerald-300 font-bold">1.4:1:5.6</strong></span>
                            <span>AI Confidence: <strong id="aiConfidence" class="text-purple-300 font-bold">99.2%</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Atmospheric Microclimate & Camera Vision Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Microclimate Dew Point & Vapor Pressure Metrics -->
                    <div class="glass-inner-panel rounded-2xl p-4 border border-white/5 space-y-2.5">
                        <div class="flex items-center justify-between pb-1.5 border-b border-white/5">
                            <span class="text-xs font-bold text-white font-tech flex items-center gap-1.5">
                                <i class="fa-solid fa-cloud-sun text-cyan-400"></i> Microclimate Dew &amp; Pressure
                            </span>
                            <span id="badgeSht45" class="text-[9px] font-mono text-cyan-300 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-500/40 font-bold">
                                SHT45 ONLINE
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center text-xs font-mono">
                            <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                                <span class="text-[10px] text-gray-400 block">จุดน้ำค้าง (Dew Point)</span>
                                <span id="detailDewPoint" class="font-bold text-cyan-400 text-sm">28.4</span>
                                <span class="text-[9px] text-gray-400">°C</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                                <span class="text-[10px] text-gray-400 block">ระยะเสี่ยงน้ำค้าง</span>
                                <span id="detailDewMargin" class="font-bold text-emerald-400 text-sm">3.0</span>
                                <span class="text-[9px] text-gray-400">°C Margin</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                                <span class="text-[10px] text-gray-400 block">VPsat (อิ่มตัว)</span>
                                <span id="detailVpsat" class="font-bold text-blue-400 text-sm">4.61</span>
                                <span class="text-[9px] text-gray-400">kPa</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                                <span class="text-[10px] text-gray-400 block">VPact (จริง)</span>
                                <span id="detailVpact" class="font-bold text-indigo-400 text-sm">3.92</span>
                                <span class="text-[9px] text-gray-400">kPa</span>
                            </div>
                        </div>
                    </div>

                    <!-- Camera Vision Snapshot -->
                    <div class="glass-inner-panel rounded-2xl p-4 border border-white/5 space-y-2.5 flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-1.5 border-b border-white/5">
                            <span class="text-xs font-bold text-white font-tech flex items-center gap-1.5">
                                <i class="fa-solid fa-camera text-rose-400"></i> OV2640 AI Camera Vision
                            </span>
                            <span class="text-[10px] font-mono text-emerald-400 font-bold bg-emerald-950 px-2 py-0.5 rounded border border-emerald-500/30">YOLOv8 98.4%</span>
                        </div>

                        <div class="relative rounded-xl overflow-hidden aspect-[16/9] bg-black border border-white/10 group cursor-pointer" onclick="captureLiveSnapshot()">
                            <img src="../assets/images/cv_agri_vision.jpg" alt="Camera Snapshot" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition duration-300"></div>
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-black/75 text-[9px] font-mono text-emerald-300 border border-emerald-500/30">
                                Healthy Leaf Detected
                            </div>
                            <div class="absolute bottom-2 right-2 px-2.5 py-1 rounded bg-black/80 text-[10px] text-white font-tech flex items-center gap-1">
                                <i class="fa-solid fa-camera-rotate text-emerald-400"></i> แตะเพื่อถ่ายภาพใหม่
                            </div>
                        </div>
                    </div>

                </div>
            </div>

