<!-- ========================================================================= -->
<!-- COMPONENT: DYNAMIC WIDGET CANVAS (ULTRA-FLEXIBLE DASHBOARD)               -->
<!-- ========================================================================= -->
<section id="widgetCanvasSection" class="space-y-6">

    <!-- MODE 1: SINGLE BOARD FOCUS VIEW (DEFAULT) -->
    <div id="viewContainerFocus" class="space-y-6">

        <!-- Active Board Header Banner & Live Quick Actuators -->
        <div class="glass-panel rounded-3xl p-5 sm:p-6 border border-white/10 relative overflow-hidden bg-gradient-to-r from-slate-900/90 via-slate-950 to-indigo-950/40">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 relative z-10">
                
                <!-- Board Metadata & Connectivity -->
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span id="activeBoardCodeBadge" class="text-xs font-mono font-bold text-cyan-300 bg-cyan-950/80 px-2.5 py-1 rounded-full border border-cyan-500/40">
                            ESP32-NODE-01
                        </span>
                        <span id="activeBoardZoneBadge" class="text-xs font-tech text-slate-300 bg-slate-800/80 px-2.5 py-1 rounded-full border border-slate-700">
                            Zone A - แปลงทุเรียน
                        </span>
                        <span id="activeBoardModelBadge" class="text-xs font-mono text-purple-300 bg-purple-950/80 px-2.5 py-1 rounded-full border border-purple-500/30">
                            ESP32-S3 ATD3.5 Pro
                        </span>
                        <span class="text-xs font-mono text-emerald-400 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> ONLINE
                        </span>
                    </div>

                    <h2 id="activeBoardTitle" class="text-xl sm:text-2xl font-bold font-tech text-white">
                        แปลงทุเรียนหมอนทอง (Chanthaburi Durian)
                    </h2>

                    <div class="flex items-center gap-3 text-xs font-mono text-slate-400 flex-wrap">
                        <span><i class="fa-solid fa-network-wired text-cyan-400 mr-1"></i> IP: <strong id="activeBoardIp" class="text-slate-200">192.168.0.111:8500</strong></span>
                        <span><i class="fa-solid fa-fingerprint text-purple-400 mr-1"></i> MAC: <strong id="activeBoardMac" class="text-slate-200">48:27:E2:B4:8A:1C</strong></span>
                        <span><i class="fa-solid fa-wifi text-emerald-400 mr-1"></i> RSSI: <strong id="activeBoardRssi" class="text-slate-200">-68 dBm</strong></span>
                        <span><i class="fa-solid fa-clock text-amber-400 mr-1"></i> อัปเดต: <strong id="activeBoardLastSeen" class="text-slate-200">สดเมื่อสักครู่</strong></span>
                    </div>
                </div>

                <!-- Active Board Quick Relay Action Pill Bar -->
                <div class="bg-slate-950/80 p-3 rounded-2xl border border-white/10 space-y-2 flex-shrink-0">
                    <div class="flex items-center justify-between text-xs font-tech">
                        <span class="text-slate-300 font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt text-amber-400"></i> ควบคุมรีเลย์ 4 ช่อง
                        </span>
                        <span class="text-[10px] font-mono text-slate-500">Node Direct Control</span>
                    </div>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button id="quickRelayBtn1" onclick="toggleActiveBoardRelay(1)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-slate-300 transition flex items-center gap-1.5">
                            <span id="quickRelayLed1" class="w-2 h-2 rounded-full bg-slate-600"></span> CH-01
                        </button>
                        <button id="quickRelayBtn2" onclick="toggleActiveBoardRelay(2)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-slate-300 transition flex items-center gap-1.5">
                            <span id="quickRelayLed2" class="w-2 h-2 rounded-full bg-slate-600"></span> CH-02
                        </button>
                        <button id="quickRelayBtn3" onclick="toggleActiveBoardRelay(3)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-slate-300 transition flex items-center gap-1.5">
                            <span id="quickRelayLed3" class="w-2 h-2 rounded-full bg-slate-600"></span> CH-03
                        </button>
                        <button id="quickRelayBtn4" onclick="toggleActiveBoardRelay(4)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-slate-300 transition flex items-center gap-1.5">
                            <span id="quickRelayLed4" class="w-2 h-2 rounded-full bg-slate-600"></span> CH-04
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- DUAL-ENGINE COMPARISON MODULE: RAW SENSOR VS EDGE AI MODEL -->
        <?php require_once __DIR__ . '/ai_inference_panel.php'; ?>

        <!-- SENSOR CONFIGURATION & ACTIVE CAPABILITIES BANNER -->
        <div id="boardSensorCapabilityBanner" class="p-3.5 rounded-2xl bg-slate-900/80 border border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs">
            <!-- Rendered by fleet-ui.js -->
        </div>

        <!-- CUSTOMIZABLE MODULAR SENSORS & ACTUATORS GRID -->
        <div id="dynamicWidgetsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <!-- Widgets rendered dynamically based on user selection in widget_catalog_modal -->
        </div>

    </div>

    <!-- MODE 2: FLEET GRID MATRIX VIEW (ALL BOARDS SIMULTANEOUSLY) -->
    <div id="viewContainerFleetGrid" class="hidden space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <div>
                <h3 class="text-lg font-bold font-tech text-white flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-cyan-400"></i> ภาพรวมเครือข่ายบอร์ดทั้งหมด (Fleet Grid Overview)
                </h3>
                <p class="text-xs text-slate-400">มอนิเตอร์และสั่งการรีเลย์ทุกบอร์ดพร้อมกันในตาราง Matrix เดียว</p>
            </div>
            <button onclick="openBroadcastModal()" class="px-3.5 py-1.5 rounded-xl bg-purple-950 text-purple-200 border border-purple-500/40 text-xs font-bold font-tech flex items-center gap-1.5">
                <i class="fa-solid fa-tower-broadcast text-purple-400"></i> สั่งการทุกบอร์ดพร้อมกัน
            </button>
        </div>
        <div id="fleetGridCardsContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <!-- Fleet Cards rendered dynamically by fleet-ui.js -->
        </div>
    </div>

    <!-- MODE 3: COMPARATIVE ANALYSIS VIEW (CROSS-BOARD BENCHMARK) -->
    <div id="viewContainerComparative" class="hidden space-y-4">
        <div class="border-b border-white/10 pb-3">
            <h3 class="text-lg font-bold font-tech text-white flex items-center gap-2">
                <i class="fa-solid fa-code-compare text-purple-400"></i> เปรียบเทียบสภาพแวดล้อมข้ามแปลง (Cross-Board Comparative Benchmark)
            </h3>
            <p class="text-xs text-slate-400">วิเคราะห์ความแตกต่างของอุณหภูมิ, ความชื้น, pH ดิน, NPK และการคายน้ำระหว่างแต่ละบอร์ด</p>
        </div>
        <div id="comparativeTableContainer" class="glass-panel rounded-3xl p-5 overflow-x-auto">
            <!-- Comparative Table rendered by fleet-ui.js -->
        </div>
    </div>

</section>
