
        <!-- ROW 2: Real-time Charts & Multi-Channel Relays Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left: 24h Environmental Trend Line Chart (8 cols) -->
            <div class="lg:col-span-8 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <h3 class="font-bold text-base text-white">กราฟแนวโน้มสภาพแวดล้อม 24 ชั่วโมง (Real-Time Trend)</h3>
                        </div>
                        <p class="text-xs text-slate-400">อุณหภูมิ (°C), ความชื้นสัมพัทธ์ (%), และความชื้นดิน (%) บันทึกทุก 5 วินาที</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="toggleChartSeries('temp')" class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-300 text-xs font-mono font-bold border border-amber-500/30">
                            Temp
                        </button>
                        <button onclick="toggleChartSeries('hum')" class="px-2.5 py-1 rounded-lg bg-cyan-500/20 text-cyan-300 text-xs font-mono font-bold border border-cyan-500/30">
                            Humidity
                        </button>
                        <button onclick="toggleChartSeries('soil')" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 text-xs font-mono font-bold border border-emerald-500/30">
                            Soil Moist
                        </button>
                    </div>
                </div>

                <!-- Canvas Chart Area -->
                <div class="relative h-72 w-full">
                    <canvas id="envTrendChart"></canvas>
                </div>
            </div>

            <!-- Right: 4-Channel Remote Relay Control Panel (4 cols) -->
            <div class="lg:col-span-4 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-emerald-400"></i>
                            <h3 class="font-bold text-sm text-white">สั่งการรีเลย์ออนไลน์ 4 แชนแนล</h3>
                        </div>
                        <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">ESP32 GPIO</span>
                    </div>

                    <!-- 4 Relay Switch Rows -->
                    <div class="space-y-3 pt-3">
                        
                        <!-- Relay 1 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-1" class="w-3 h-3 rounded-full bg-slate-700 inline-block shadow-sm"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <span>ปั๊มน้ำหลัก (Main Pump 1)</span>
                                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-slate-800 text-cyan-400 border border-slate-700">GPIO 39</span>
                                    </h4>
                                    <span id="dash-status-1" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-1" onclick="toggleDashboardRelay(1, 'ปั๊มน้ำหลัก (Main Pump 1)')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 2 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-2" class="w-3 h-3 rounded-full bg-slate-700 inline-block shadow-sm"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <span>วาล์วโซลินอยด์/ปั๊ม 2</span>
                                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-slate-800 text-cyan-400 border border-slate-700">GPIO 38</span>
                                    </h4>
                                    <span id="dash-status-2" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-2" onclick="toggleDashboardRelay(2, 'วาล์วโซลินอยด์/ปั๊ม 2')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 3 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-3" class="w-3 h-3 rounded-full bg-slate-700 inline-block shadow-sm"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <span>วาล์วน้ำผิวดิน (Surface Valve)</span>
                                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-slate-800 text-cyan-400 border border-slate-700">GPIO 7</span>
                                    </h4>
                                    <span id="dash-status-3" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-3" onclick="toggleDashboardRelay(3, 'วาล์วน้ำผิวดิน')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 4 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-4" class="w-3 h-3 rounded-full bg-slate-700 inline-block shadow-sm"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <span>ระบบพ่นหมอก (Misting System)</span>
                                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-slate-800 text-cyan-400 border border-slate-700">GPIO 6</span>
                                    </h4>
                                    <span id="dash-status-4" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-4" onclick="toggleDashboardRelay(4, 'ระบบพ่นหมอก')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Tri-Mode Controller: MANUAL / AUTO / AI -->
                <div class="pt-3 border-t border-slate-800/80 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">โหมดควบคุมการสั่งการ:</span>
                        <span id="currentModeLabel" class="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">MANUAL</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 p-1 rounded-2xl bg-slate-900/90 border border-slate-800">
                        <button id="btnModeManual" onclick="setControlMode('manual')" class="py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 bg-amber-500/20 text-amber-300 border border-amber-500/40">
                            <i class="fa-solid fa-hand text-[10px]"></i> Manual
                        </button>
                        <button id="btnModeAuto" onclick="setControlMode('auto')" class="py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 text-slate-400 hover:text-white">
                            <i class="fa-solid fa-robot text-[10px]"></i> Auto
                        </button>
                        <button id="btnModeAI" onclick="setControlMode('ai')" class="py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 text-slate-400 hover:text-cyan-300">
                            <i class="fa-solid fa-brain text-[10px]"></i> AI Smart
                        </button>
                    </div>
                </div>
            </div>

        </div>
