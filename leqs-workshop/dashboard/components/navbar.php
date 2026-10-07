    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 glass-box border-b border-slate-800/80 px-3 sm:px-6 py-2.5 backdrop-blur-xl">
        <div class="max-w-[1720px] mx-auto space-y-2">

            <!-- Row 1: Brand + live status + toolbar -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <a href="../index.php" class="flex items-center gap-2.5 text-white hover:text-emerald-400 transition group flex-shrink-0">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition">
                        <i class="fa-solid fa-leaf text-base"></i>
                    </div>
                    <div class="leading-tight">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm md:text-base font-tech tracking-wider">LEQs-xAI SMART FARM</span>
                            <span class="text-[9px] font-mono bg-cyan-500/20 text-cyan-300 px-2 py-0.5 rounded border border-cyan-500/30 hidden sm:inline">DASHBOARD</span>
                        </div>
                        <p class="text-[10px] text-slate-400 hidden sm:block">ระบบควบคุมมอนิเตอร์แปลงเกษตรแม่นยำออนไลน์</p>
                    </div>
                </a>

                <!-- Live status -->
                <div id="liveChip" class="pill pill-mute !text-[11px] !py-1.5 !px-3" role="status" aria-live="polite">
                    <span id="liveDot" class="w-2 h-2 rounded-full bg-slate-500"></span>
                    <span id="liveText" class="font-mono font-bold">CONNECTING</span>
                    <span class="text-slate-500">·</span>
                    <span id="liveAge" class="font-mono num">--</span>
                    <span class="text-slate-500 hidden sm:inline">·</span>
                    <span id="latencyDisplay" class="font-mono num hidden sm:inline">-- ms</span>
                </div>

                <!-- Toolbar -->
                <nav class="flex items-center gap-1.5 flex-wrap justify-end" aria-label="เมนูหลัก">
                    <a href="#sec-overview" class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-slate-300 transition hidden md:inline-flex items-center gap-1.5"><i class="fa-solid fa-gauge-high text-emerald-400 text-[11px]"></i> ภาพรวม</a>
                    <a href="#sec-control" class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-slate-300 transition hidden md:inline-flex items-center gap-1.5"><i class="fa-solid fa-sliders text-cyan-400 text-[11px]"></i> ควบคุม</a>
                    <button onclick="openBoardScreenModal()" class="px-2.5 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/40 text-xs text-purple-300 flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-display text-[11px]"></i> <span class="hidden sm:inline">จำลองจอ ESP32</span>
                    </button>
                    <button onclick="openQrModal()" class="px-2.5 py-1.5 rounded-xl bg-cyan-950/80 hover:bg-cyan-900 border border-cyan-500/40 text-xs text-cyan-300 flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-qrcode text-[11px]"></i> <span class="hidden sm:inline">QR Portal</span>
                    </button>
                    <a href="../mobile/index.php" target="_blank" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen-button"></i> Mobile
                    </a>
                    <!-- เครื่องมือเพิ่มเติม -->
                    <div class="relative" id="toolsWrap">
                        <button id="toolsBtn" type="button" aria-haspopup="true" aria-expanded="false" onclick="toggleToolsMenu(event)" class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-slate-300 flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-ellipsis"></i> เพิ่มเติม
                        </button>
                        <div id="toolsMenu" class="hidden absolute right-0 mt-2 w-52 rounded-2xl glass-box border border-slate-700 shadow-2xl p-1.5 text-xs z-50">
                            <button onclick="promptChangeIp(); toggleToolsMenu();" class="w-full text-left px-3 py-2 rounded-xl hover:bg-slate-800 flex items-center gap-2 text-cyan-300"><i class="fa-solid fa-network-wired w-4"></i> ตั้งค่า IP บอร์ด</button>
                            <a href="../api/api.php?action=export_csv" target="_blank" class="px-3 py-2 rounded-xl hover:bg-slate-800 flex items-center gap-2 text-amber-300"><i class="fa-solid fa-file-csv w-4"></i> ดาวน์โหลด CSV</a>
                            <a href="../../app-portal/index.php" target="_blank" class="px-3 py-2 rounded-xl hover:bg-slate-800 flex items-center gap-2 text-emerald-300"><i class="fa-solid fa-cloud-arrow-down w-4"></i> โหลด APK</a>
                            <a href="../index.php" class="px-3 py-2 rounded-xl hover:bg-slate-800 flex items-center gap-2 text-slate-300"><i class="fa-solid fa-house w-4"></i> เว็บหลัก</a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Row 2: Board info chips -->
            <div class="flex flex-wrap items-center gap-1.5 text-[11px] font-mono">
                <span class="pill pill-info"><i class="fa-solid fa-plug"></i> <span id="cloudStatusText" class="font-bold">ESP32 DIRECT</span></span>
                <span class="pill pill-mute"><i class="fa-solid fa-wifi"></i> <span id="boardSsidDisplay">JC_Home</span> <span id="boardRssiDisplay" class="text-slate-500 num"></span></span>
                <span class="pill pill-mute"><i class="fa-solid fa-network-wired"></i> <span id="boardIpDisplay">192.168.0.111:8500</span></span>
                <button onclick="openGpsConfigModal()" class="pill pill-mute hover:!text-white cursor-pointer" title="คลิกเพื่อดูหรือปรับพิกัด GPS จริงจุดติดตั้ง">
                    <i class="fa-solid fa-location-dot text-rose-400"></i> <span id="boardGpsDisplay">12.6644° N, 102.1039° E</span>
                </button>
                <span class="pill pill-mute hidden md:inline-flex"><span id="sdCardStatusDisplay"><i class="fa-solid fa-sd-card mr-0.5 text-slate-500"></i> ไม่ได้ใส่การ์ด</span></span>
                <span class="pill pill-ok hidden md:inline-flex"><span id="dbRecordsStatusDisplay"><i class="fa-solid fa-database mr-0.5"></i> SQLite</span></span>
            </div>
        </div>
    </header>

    <!-- Stale / offline banner -->
    <div id="staleBanner" class="hidden max-w-[1720px] w-full mx-auto px-3 sm:px-6 mt-3">
        <div class="rounded-2xl border border-rose-500/40 bg-rose-950/40 text-rose-200 px-4 py-2.5 text-xs flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
            <span id="staleBannerText">ไม่ได้รับข้อมูลใหม่จากบอร์ด</span>
        </div>
    </div>

