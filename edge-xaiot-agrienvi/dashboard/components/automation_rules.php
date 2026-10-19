
        <!-- ROW 3: NPK Radar Chart & Smart Rule Automation -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
            
            <!-- Soil NPK Radar (4 cols) -->
            <div class="lg:col-span-4 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-cyan-400"></i>
                        <h3 class="font-bold text-sm text-white">สมดุลธาตุอาหารในดิน (NPK Radar)</h3>
                    </div>
                    <span class="text-[10px] font-mono text-cyan-400">7-in-1 Modbus</span>
                </div>
                <div class="relative h-56 w-full flex items-center justify-center">
                    <canvas id="npkRadarChart"></canvas>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs pt-2 border-t border-slate-800/60 font-mono">
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800 space-y-0.5">
                        <span class="text-[10px] text-slate-400 block font-sans font-bold">N (ไนโตรเจน)</span>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-slate-400">เซนเซอร์:</span>
                            <strong id="dashRawNVal" class="text-slate-300">0.0</strong>
                        </div>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-purple-400 font-bold">โมเดล AI:</span>
                            <strong id="dashAiN" class="text-emerald-400">47.3</strong>
                        </div>
                        <span id="dashValN" class="hidden">47.3</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800 space-y-0.5">
                        <span class="text-[10px] text-slate-400 block font-sans font-bold">P (ฟอสฟอรัส)</span>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-slate-400">เซนเซอร์:</span>
                            <strong id="dashRawPVal" class="text-slate-300">0.0</strong>
                        </div>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-purple-400 font-bold">โมเดล AI:</span>
                            <strong id="dashAiP" class="text-cyan-400">32.6</strong>
                        </div>
                        <span id="dashValP" class="hidden">32.6</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800 space-y-0.5">
                        <span class="text-[10px] text-slate-400 block font-sans font-bold">K (โพแทสเซียม)</span>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-slate-400">เซนเซอร์:</span>
                            <strong id="dashRawKVal" class="text-slate-300">0.0</strong>
                        </div>
                        <div class="flex items-center justify-between text-[10px] px-1">
                            <span class="text-purple-400 font-bold">โมเดล AI:</span>
                            <strong id="dashAiK" class="text-amber-400">178.2</strong>
                        </div>
                        <span id="dashValK" class="hidden">178.2</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] font-mono pt-1 text-slate-400 border-t border-slate-800/40">
                    <span>สัดส่วน: <strong id="dashNpkRatio" class="text-cyan-300">1.4:1:5.6</strong></span>
                    <span>รวม: <strong id="dashNpkTotal" class="text-white">257 mg/kg</strong></span>
                </div>
            </div>

            <!-- Smart Automation Rules Builder (8 cols) -->
            <div class="lg:col-span-8 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-microchip text-emerald-400"></i>
                        <h3 class="font-bold text-sm text-white">เงื่อนไขอัจฉริยะอัตโนมัติ (Rule-Based Automation Engine)</h3>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">3 Rules Active</span>
                </div>

                <div class="space-y-3">
                    <!-- Rule 1 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">R1</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบรดน้ำอัตโนมัติเมื่อดินแห้ง</h4>
                                <p class="text-[11px] text-slate-400">IF ความชื้นดิน &lt; 45% THEN สั่งเปิดรีเลย์ 1 (วาล์วน้ำโซลินอยด์) นาน 180 วินาที</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>

                    <!-- Rule 2 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">R2</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบพ่นหมอกลดความเครียดพืชตามค่า VPD</h4>
                                <p class="text-[11px] text-slate-400">IF VPD &gt; 1.4 kPa OR Temp &gt; 33°C THEN สั่งเปิดรีเลย์ 2 (พ่นหมอก) เป็นจังหวะ 30 วิ</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>

                    <!-- Rule 3 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold">R3</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบระบายอากาศฉุกเฉินเมื่ออุณหภูมิวิกฤต</h4>
                                <p class="text-[11px] text-slate-400">IF Temp &gt; 36°C THEN สั่งเปิดรีเลย์ 4 (พัดลมดูดอากาศ) จนกว่าอุณหภูมิ &lt; 32°C</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>
                </div>

                <!-- Live Log Terminal Stream -->
                <div class="bg-slate-950 rounded-2xl p-3 border border-slate-800 font-mono text-[11px] text-slate-300 space-y-1 max-h-28 overflow-y-auto">
                    <div class="text-emerald-400">[System] Connected to ESP32-S3 ATD3.5 Controller at 192.168.0.111:8500 (SSID: JC_Home, RSSI: -99dBm)</div>
                    <div class="text-slate-400">[Telemetry] Ingested 24-point dataset: SHT45 (T:28.5C, RH:65.2%), Soil 7in1 (M:72.4%, EC:850, pH:6.4)</div>
                    <div class="text-cyan-400">[Engine] Automated evaluation cycle completed. All parameters within safe thresholds.</div>
                </div>

            </div>

        </div>
