    <main class="container mx-auto px-4 md:px-6 py-6 space-y-6 flex-1">
        
        <!-- HIGHLIGHT: Official QR Portal & Identity Verification (ESP32-S3 ATD3.5 Screen 11) -->
        <div class="glass-box rounded-3xl p-5 border border-cyan-500/40 bg-gradient-to-r from-cyan-950/60 via-slate-900/90 to-indigo-950/70 flex flex-col lg:flex-row items-center justify-between gap-5 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-48 h-48 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left relative z-10">
                <!-- QR Image -->
                <div class="w-20 h-20 bg-white p-1 rounded-2xl shadow-lg flex-shrink-0 cursor-pointer hover:scale-105 transition" onclick="openQrModal()">
                    <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Code" class="w-full h-full object-contain">
                </div>
                <!-- Metadata Stamp -->
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] font-mono border border-cyan-500/30 font-bold">
                            <i class="fa-solid fa-microchip mr-1"></i> ESP32-S3 ATD3.5 จอที่ 11
                        </span>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded border border-emerald-800">
                            2026.10.04 วันอาทิตย์
                        </span>
                        <span class="text-xs font-mono font-bold text-amber-300 bg-amber-950/80 px-2 py-0.5 rounded border border-amber-800">
                            09:03 ชีวะ ทัศนา
                        </span>
                        <a href="https://maps.google.com/?q=12.6644,102.1039" target="_blank" rel="noopener noreferrer" class="text-xs font-mono font-bold text-rose-300 bg-rose-950/80 px-2 py-0.5 rounded border border-rose-800 hover:bg-rose-900 transition flex items-center gap-1">
                            <i class="fa-solid fa-location-crosshairs text-rose-400"></i> GPS: 12.6644° N, 102.1039° E (มรภ.รำไพพรรณี)
                        </a>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-white font-tech">
                        พอร์ทัลสแกนเชื่อมต่อและระบบยืนยันตัวตน Smart Farm AIoT
                    </h3>
                    <p class="text-xs text-slate-300 font-mono">
                        URL: <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline font-semibold">https://scicenter.rbru.ac.th/leqs-workshop/index.php</a>
                    </p>
                </div>
            </div>

            <!-- ESP32 Screen 11 Thumbnail & Quick Actions -->
            <div class="flex items-center gap-3 flex-shrink-0 relative z-10">
                <div class="relative rounded-xl overflow-hidden border border-cyan-500/40 w-36 aspect-[3/2] cursor-pointer group shadow-md" onclick="previewScreen11()">
                    <img src="../assets/images/atd35/11_qr_portal_screen.png" alt="ESP32 Screen 11" class="w-full h-full object-cover group-hover:scale-105 transition">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition flex items-center justify-center">
                        <span class="px-2 py-0.5 rounded bg-black/80 text-[10px] font-mono text-cyan-300 border border-cyan-400/40">ESP32 จอที่ 11</span>
                    </div>
                </div>
                <button onclick="openQrModal()" class="px-4 py-2.5 rounded-2xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs font-tech shadow-lg shadow-cyan-600/30 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-qrcode"></i> แสดง QR Code
                </button>
            </div>
        </div>
