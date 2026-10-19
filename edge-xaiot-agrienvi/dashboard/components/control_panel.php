
        <!-- ROW 2: Real-time Charts & Multi-Channel Relays Grid -->
        <div id="sec-control" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: Environmental Trend Line Chart (8 cols) -->
            <div class="lg:col-span-8 glass-box rounded-3xl p-5 sm:p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <h3 class="font-bold text-base text-white">กราฟแนวโน้มสภาพแวดล้อม (Real-Time Trend)</h3>
                        </div>
                        <p id="chartSubtitle" class="text-xs text-slate-400">อุณหภูมิ (°C), ความชื้นสัมพัทธ์ (%) และความชื้นดิน (%) — กำลังโหลดข้อมูลย้อนหลัง…</p>
                    </div>
                    <div class="flex items-center gap-2" role="group" aria-label="เลือกเส้นกราฟ">
                        <button id="chartBtnTemp" onclick="toggleChartSeries('temp')" aria-pressed="true" class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-300 text-xs font-mono font-bold border border-amber-500/30 transition">Temp</button>
                        <button id="chartBtnHum" onclick="toggleChartSeries('hum')" aria-pressed="true" class="px-2.5 py-1 rounded-lg bg-cyan-500/20 text-cyan-300 text-xs font-mono font-bold border border-cyan-500/30 transition">Humidity</button>
                        <button id="chartBtnSoil" onclick="toggleChartSeries('soil')" aria-pressed="true" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 text-xs font-mono font-bold border border-emerald-500/30 transition">Soil Moist</button>
                    </div>
                </div>

                <div class="relative h-72 w-full">
                    <canvas id="envTrendChart"></canvas>
                </div>
            </div>

            <!-- Right: 4-Channel Remote Relay Control Panel (4 cols) -->
            <div class="lg:col-span-4 glass-box rounded-3xl p-5 sm:p-6 border border-slate-800/80 space-y-4 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-emerald-400"></i>
                            <h3 class="font-bold text-sm text-white">สั่งการรีเลย์ 4 แชนแนล</h3>
                        </div>
                        <button onclick="allRelaysOff()" class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-rose-950/70 hover:bg-rose-900 text-rose-300 border border-rose-500/40 transition flex items-center gap-1">
                            <i class="fa-solid fa-power-off"></i> ปิดทั้งหมด
                        </button>
                    </div>

                    <div class="space-y-2.5 pt-3">
                        <?php
                        $relays = [
                            1 => ['ปั๊มน้ำหลัก (Main Pump 1)', 'ปั๊มน้ำหลัก (Main Pump 1)', 'GPIO 39'],
                            2 => ['วาล์วโซลินอยด์/ปั๊ม 2', 'วาล์วโซลินอยด์/ปั๊ม 2', 'GPIO 38'],
                            3 => ['วาล์วน้ำผิวดิน (Surface Valve)', 'วาล์วน้ำผิวดิน', 'GPIO 7'],
                            4 => ['ระบบพ่นหมอก (Misting System)', 'ระบบพ่นหมอก', 'GPIO 6'],
                        ];
                        foreach ($relays as $rid => $r): ?>
                        <div id="dash-row-<?= $rid ?>" class="p-3 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between gap-3 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span id="dash-led-<?= $rid ?>" class="w-3 h-3 rounded-full bg-slate-700 inline-block flex-shrink-0"></span>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-white flex items-center gap-1.5 flex-wrap">
                                        <span><?= $r[0] ?></span>
                                        <span class="text-[9px] font-mono px-1.5 rounded bg-slate-800 text-cyan-400 border border-slate-700"><?= $r[2] ?></span>
                                    </h4>
                                    <span id="dash-status-<?= $rid ?>" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-<?= $rid ?>" type="button" role="switch" aria-checked="false" aria-label="<?= htmlspecialchars($r[1]) ?>"
                                    onclick="toggleDashboardRelay(<?= $rid ?>, '<?= $r[1] ?>')" class="sw"></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tri-Mode Controller: MANUAL / AUTO / AI -->
                <div class="pt-3 border-t border-slate-800/80 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">โหมดควบคุมการสั่งการ:</span>
                        <span id="currentModeLabel" class="pill pill-warn">MANUAL</span>
                    </div>
                    <div class="seg" role="group" aria-label="โหมดควบคุม">
                        <button id="btnModeManual" onclick="setControlMode('manual')" class="m-manual" aria-pressed="true"><i class="fa-solid fa-hand text-[10px]"></i> Manual</button>
                        <button id="btnModeAuto" onclick="setControlMode('auto')" class="m-auto" aria-pressed="false"><i class="fa-solid fa-robot text-[10px]"></i> Auto</button>
                        <button id="btnModeAI" onclick="setControlMode('ai')" class="m-ai" aria-pressed="false"><i class="fa-solid fa-brain text-[10px]"></i> AI Smart</button>
                    </div>
                </div>
            </div>

        </div>
