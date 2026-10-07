    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 glass-box border-b border-slate-800/80 px-3 sm:px-6 py-3 backdrop-blur-xl">
        <div class="max-w-[1720px] mx-auto flex flex-col xl:flex-row items-center justify-between gap-3">
            
            <!-- Brand & Board Info -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto justify-between sm:justify-start">
                <a href="../index.php" class="flex items-center gap-2.5 text-white hover:text-emerald-400 transition group flex-shrink-0">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition">
                        <i class="fa-solid fa-leaf text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm md:text-base font-tech tracking-wider">LEQs-xAI SMART FARM</span>
                            <span class="text-[9px] font-mono bg-cyan-500/20 text-cyan-300 px-2 py-0.5 rounded border border-cyan-500/30">DASHBOARD</span>
                        </div>
                        <p class="text-[10px] text-slate-400">ระบบควบคุมมอนิเตอร์แปลงเกษตรแม่นยำออนไลน์</p>
                    </div>
                </a>

                <!-- Hardware Connection Chip (Matching ESP32-S3 ATD3.5 Screen Photo) -->
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 px-3 py-1.5 rounded-2xl bg-slate-900 border border-emerald-500/40 text-[11px] font-mono shadow-md justify-center sm:justify-start">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="cloudStatusText" class="text-emerald-400 font-bold">ESP32 DIRECT</span>
                    <span class="text-slate-600 hidden sm:inline">|</span>
                    <span class="text-gray-400">SSID:</span>
                    <span id="boardSsidDisplay" class="text-emerald-300 font-bold">JC_Home</span>
                    <span class="text-slate-600 hidden sm:inline">|</span>
                    <span class="text-gray-400">IP:</span>
                    <span id="boardIpDisplay" class="text-cyan-300 font-bold">192.168.0.111:8500</span>
                    <span class="text-slate-600 hidden sm:inline">|</span>
                    <button onclick="openGpsConfigModal()" class="text-gray-400 hover:text-white flex items-center gap-1 group transition cursor-pointer" title="คลิกเพื่อดูหรือปรับพิกัด GPS จริงจุดติดตั้ง">
                        <i class="fa-solid fa-location-dot text-rose-400 group-hover:scale-110 transition"></i>
                        <span>GPS:</span>
                        <span id="boardGpsDisplay" class="text-rose-300 font-bold">12.6644° N, 102.1039° E</span>
                    </button>
                    <span class="text-slate-600 hidden md:inline">|</span>
                    <span class="text-gray-400 hidden md:inline">SD:</span>
                    <span id="sdCardStatusDisplay" class="text-slate-400 font-bold hidden md:inline"><i class="fa-solid fa-sd-card mr-0.5 text-slate-500"></i> ไม่ได้ใส่การ์ด</span>
                    <span class="text-slate-600 hidden md:inline">|</span>
                    <span class="text-gray-400 hidden md:inline">DB:</span>
                    <span id="dbRecordsStatusDisplay" class="text-emerald-300 font-bold hidden md:inline"><i class="fa-solid fa-database mr-0.5"></i> SQLite</span>
                </div>
            </div>

            <!-- Top Actions & Navigation Links -->
            <div class="flex items-center gap-2 flex-wrap w-full xl:w-auto justify-center xl:justify-end">
                <button onclick="openBoardScreenModal()" class="px-2.5 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/40 text-xs text-purple-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-display text-[11px]"></i> จำลองจอ ESP32
                </button>
                <button onclick="openQrModal()" class="px-2.5 py-1.5 rounded-xl bg-cyan-950/80 hover:bg-cyan-900 border border-cyan-500/40 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-qrcode text-[11px]"></i> QR Portal
                </button>
                <button onclick="promptChangeIp()" class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-network-wired text-[10px]"></i> ตั้งค่า IP
                </button>
                <a href="../api/api.php?action=export_csv" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-amber-950/80 hover:bg-amber-900 border border-amber-500/40 text-xs text-amber-300 font-mono flex items-center gap-1.5 transition shadow-sm" title="ดาวน์โหลดฐานข้อมูล telemetry_logs เป็น CSV">
                    <i class="fa-solid fa-file-csv text-[11px]"></i> โหลด CSV
                </a>
                <a href="../mobile/index.php" target="_blank" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-mobile-screen-button"></i> Mobile
                </a>
                <a href="../../app-portal/index.php" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-emerald-950/90 hover:bg-emerald-900 border border-emerald-500/50 text-emerald-300 text-xs font-bold transition flex items-center gap-1.5" title="ศูนย์ดาวน์โหลดแอปพลิเคชัน & APK สำหรับผู้เข้าร่วมอบรม">
                    <i class="fa-solid fa-cloud-arrow-down text-emerald-400"></i> โหลด APK
                </a>
                <a href="../index.php" class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition">
                    <i class="fa-solid fa-house"></i> เว็บหลัก
                </a>
            </div>

        </div>
    </header>

