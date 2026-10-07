            <!-- 1. TOP SMARTPHONE STATUS BAR & HARDWARE ISLAND (MATCHING ESP32 SCREEN) -->
            <div class="glass-inner-panel rounded-2xl px-4 py-2.5 flex items-center justify-between text-xs font-mono">
                
                <!-- Left: Hardware Connection Badge -->
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wifi text-emerald-400 text-sm"></i>
                    <div>
                        <div class="flex items-center gap-1.5 leading-none">
                            <span class="font-bold text-white tracking-wide text-[11px] sm:text-xs">ESP32-S3 ATD3.5</span>
                            <span class="text-[9px] font-bold text-emerald-300 bg-emerald-950 px-1.5 py-0.5 rounded border border-emerald-500/40">CONNECTED (ONLINE)</span>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap mt-0.5">
                            <span id="subNetworkText" class="text-[10px] text-gray-400">SSID: JC_Home • IP: 192.168.0.111</span>
                            <button onclick="openGpsConfigModal()" id="mobGpsBadge" class="inline-flex items-center gap-1 text-[10px] font-mono text-cyan-300 bg-cyan-950/80 px-2 py-0.5 rounded border border-cyan-700/60 hover:bg-cyan-900/80 transition cursor-pointer" title="คลิกเพื่อดูหรือปรับพิกัด GPS จริงจุดติดตั้ง">
                                <i class="fa-solid fa-location-dot text-rose-400 text-[9px]"></i>
                                <span id="mobGpsCoords">12.6644° N, 102.1039° E (RBRU)</span>
                                <i class="fa-solid fa-pen-to-square text-[8px] text-gray-400 ml-0.5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Center: Real-Time Live Clock -->
                <div class="hidden sm:flex flex-col items-center">
                    <span id="liveClock" class="text-sm font-bold text-white tracking-widest">18:42</span>
                    <span class="text-[9px] text-gray-400 font-sans">เวลาปัจจุบัน</span>
                </div>

                <!-- Right: Battery & Signal Level -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 text-[11px] text-gray-300">
                        <i class="fa-solid fa-signal text-cyan-400 text-xs"></i>
                        <span id="signalRssiLabel">-99 dBm</span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-emerald-400 font-bold">
                        <span id="batteryPct">98%</span>
                        <i class="fa-solid fa-battery-full text-sm"></i>
                    </div>
                </div>

            </div>

            <!-- AUTHENTIC ESP32-S3 ATD3.5 PHYSICAL TABS BAR (FROM USER'S PHOTO) -->
            <div class="glass-inner-panel rounded-2xl p-1.5 flex items-center justify-between text-[11px] font-tech font-bold overflow-x-auto gap-1 shadow-md">
                <button onclick="scrollToSection('gauges')" class="flex-1 py-1.5 px-2 rounded-xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-center hover:bg-cyan-500/30 transition">
                    1. HOME
                </button>
                <button onclick="scrollToSection('telemetryDetails')" class="flex-1 py-1.5 px-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-center hover:bg-emerald-500/30 transition">
                    2. DATA
                </button>
                <button onclick="window.open('../dashboard/index.php#analytics', '_blank')" class="flex-1 py-1.5 px-2 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/30 text-center hover:bg-amber-500/30 transition">
                    3. GRAPH
                </button>
                <button onclick="scrollToSection('relaySection')" class="flex-1 py-1.5 px-2 rounded-xl bg-purple-500/20 text-purple-300 border border-purple-500/30 text-center hover:bg-purple-500/30 transition">
                    4. RELAY
                </button>
                <button onclick="openBoardScreenModal()" class="flex-1 py-1.5 px-2 rounded-xl bg-yellow-500/30 text-yellow-300 border border-yellow-500/40 text-center hover:bg-yellow-500/40 transition shadow-sm animate-pulse">
                    5. SETUP
                </button>
                <button onclick="openBoardScreenModal()" class="py-1.5 px-2.5 rounded-xl bg-blue-500/20 text-blue-300 border border-blue-500/30 text-center hover:bg-blue-500/30 transition">
                    🌐 ENG
                </button>
            </div>

            <!-- DUAL STORAGE ARCHITECTURE STATUS (ESP32 MICRO-SD & SERVER SQLITE3) -->
            <div class="glass-inner-panel rounded-2xl p-2.5 flex items-center justify-between text-[11px] font-mono gap-2 border border-white/5">
                <div class="flex items-center gap-1.5 text-amber-300">
                    <i class="fa-solid fa-sd-card text-xs"></i>
                    <span>SD Card:</span>
                    <strong id="mobSdStatus" class="text-slate-400 italic">ไม่ได้ใส่การ์ด (No Card)</strong>
                </div>
                <div class="flex items-center gap-1.5 text-emerald-300">
                    <i class="fa-solid fa-database text-xs"></i>
                    <span>SQLite DB:</span>
                    <strong id="mobDbStatus" class="text-white">Active (Auto Sync)</strong>
                </div>
                <a href="../api/api.php?action=export_csv" class="px-2 py-0.5 rounded-lg bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 text-[10px] font-bold border border-amber-500/30 flex items-center gap-1 transition">
                    <i class="fa-solid fa-file-csv"></i> CSV
                </a>
            </div>

