    <!-- Top Floating Header Bar (Navigation & Controls) -->
    <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur-md border-b border-white/5 px-4 py-2.5">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <a href="../index.php" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition active:scale-95" title="กลับสู่หน้าหลัก">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="font-bold text-xs sm:text-sm text-white font-tech tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        LEQs-xAI SMART PHONE APP
                    </h1>
                    <span class="text-[10px] text-gray-400 font-mono">ESP32-S3 ATD3.5 Hardware Interface</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="openMobileQrModal()" class="px-2.5 py-1 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 text-cyan-300 text-xs font-tech font-bold flex items-center gap-1 transition">
                    <i class="fa-solid fa-qrcode text-[11px]"></i>
                    <span class="hidden sm:inline">QR Portal</span>
                </button>
                <button onclick="openWifiModal()" class="px-3 py-1 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 text-cyan-300 text-xs font-tech font-bold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-wifi text-[10px]"></i>
                    <span id="navBoardIp">192.168.0.111:8500</span>
                </button>
                <a href="../dashboard/index.php" target="_blank" class="px-3 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-desktop text-[10px]"></i>
                    <span class="hidden sm:inline">Web Dashboard</span>
                </a>
            </div>
        </div>
    </header>
