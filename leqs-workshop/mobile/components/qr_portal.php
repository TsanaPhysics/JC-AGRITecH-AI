            <!-- 5. ESP32-S3 ATD3.5 จอที่ 11: OFFICIAL QR CODE PORTAL & IDENTITY -->
            <div class="glass-inner-panel rounded-2xl p-4 border border-cyan-500/30 bg-gradient-to-r from-cyan-950/40 via-slate-900/60 to-slate-950/80 space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2">
                    <span class="text-xs font-bold text-cyan-300 font-tech flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode text-cyan-400"></i> ESP32-S3 ATD3.5 จอที่ 11: Official QR Portal
                    </span>
                    <span class="text-[9px] font-mono text-emerald-400 font-bold bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">
                        ONLINE SYNC
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3.5">
                    <!-- QR Code Image -->
                    <div class="w-20 h-20 bg-white p-1 rounded-xl shadow-md flex-shrink-0 cursor-pointer hover:scale-105 transition" onclick="openMobileQrModal()">
                        <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Code" class="w-full h-full object-contain">
                    </div>

                    <!-- Metadata Stamp & Link -->
                    <div class="space-y-1 text-center sm:text-left flex-1 min-w-0">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 text-[10px] font-mono">
                            <span class="text-emerald-400 font-bold bg-emerald-950/90 px-1.5 py-0.5 rounded border border-emerald-800/80">
                                2026.10.04 วันอาทิตย์
                            </span>
                            <span class="text-amber-300 font-bold bg-amber-950/90 px-1.5 py-0.5 rounded border border-amber-800/80">
                                09:03 ชีวะ ทัศนา
                            </span>
                        </div>
                        <div class="text-xs font-tech font-bold text-white truncate">
                            LEQs-xAI Smart Farm Portal
                        </div>
                        <div class="text-[11px] font-mono text-cyan-300 truncate">
                            <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" class="hover:underline flex items-center justify-center sm:justify-start gap-1">
                                <i class="fa-solid fa-link text-[10px]"></i> https://scicenter.rbru.ac.th/leqs-workshop/index.php
                            </a>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex sm:flex-col gap-1.5 flex-shrink-0 w-full sm:w-auto">
                        <button onclick="openMobileQrModal()" class="flex-1 sm:flex-none px-3 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-tech font-bold text-xs shadow-md transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-expand text-[10px]"></i> สแกน QR
                        </button>
                        <button onclick="openScreen11Modal()" class="flex-1 sm:flex-none px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-tech font-bold text-xs border border-white/10 transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-display text-[10px]"></i> ดูจอที่ 11
                        </button>
                    </div>
                </div>
            </div>

