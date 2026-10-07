                <!-- 3. SERVER DIAGNOSTICS & QR CODE QUICK SHARE GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Quick QR Code share module with User Specific QR & Metadata -->
                    <div class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 flex flex-col sm:flex-row items-center gap-6 shadow-xl">
                        <div class="shrink-0 p-3 bg-white rounded-2xl border-2 border-cyan-400/40 shadow-lg shadow-cyan-500/10">
                            <img src="../assets/images/qr_leqs-agri-workshop.png" alt="LEQs-xAI Official QR Code" class="w-36 h-36 object-contain select-none">
                        </div>
                        <div class="flex-1 text-center sm:text-left space-y-3">
                            <div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-mono font-bold mb-1">
                                    <i class="fas fa-qrcode"></i> Official QR Portal
                                </div>
                                <h3 class="font-extrabold text-lg text-white">QR Code สแกนเข้าเว็บทางการ</h3>
                                <div class="space-y-1 text-xs text-slate-300 mt-2 font-mono">
                                    <div class="text-emerald-400 font-bold"><i class="fas fa-calendar-day mr-1"></i> 2026.10.04 วันอาทิตย์</div>
                                    <div class="text-amber-400 font-bold"><i class="fas fa-clock mr-1"></i> 09:03 ชีวะ ทัศนา</div>
                                    <div class="text-cyan-400 font-bold truncate"><i class="fas fa-link mr-1"></i> https://scicenter.rbru.ac.th/leqs-workshop/index.php</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 justify-center sm:justify-start pt-1">
                                <a href="../assets/images/qr_leqs-agri-workshop.png" target="_blank" download="qr_leqs-agri-workshop.png" class="px-3.5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold transition shadow-lg shadow-brand/10 flex items-center gap-2">
                                    <i class="fas fa-download text-[10px]"></i> ดาวน์โหลด QR
                                </a>
                                <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" class="px-3.5 py-2 bg-slate-950/60 hover:bg-slate-900 text-cyan-300 rounded-xl text-xs font-mono border border-cyan-500/20 transition flex items-center gap-1.5">
                                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i> เปิดลิงก์ตรง
                                </a>
                                <a href="../assets/images/atd35/11_qr_portal_screen.png" target="_blank" class="px-3.5 py-2 bg-slate-950/60 hover:bg-slate-900 text-amber-300 rounded-xl text-xs font-mono border border-amber-500/20 transition flex items-center gap-1.5">
                                    <i class="fas fa-display text-[10px]"></i> ดูจอ ESP32 ที่ 11
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Server environmental check list -->
                    <div class="bg-[#0e1424] p-6 rounded-3xl border border-white/5 shadow-xl space-y-6">
                        <div class="pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-circle-check text-brand"></i> การวินิจฉัยและสเปคเซิร์ฟเวอร์
                            </h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">PHP Version</div>
                                <div class="font-mono text-slate-200 font-bold"><?php echo phpversion(); ?></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">Upload Max Size</div>
                                <div class="font-mono text-slate-200 font-bold"><?php echo ini_get('upload_max_filesize'); ?></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">Server Software</div>
                                <div class="font-mono text-slate-200 font-bold truncate" title="<?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache'); ?>">
                                    <?php echo htmlspecialchars(explode(' ', $_SERVER['SERVER_SOFTWARE'] ?? 'Apache/XAMPP')[0]); ?>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">สิทธิ์เขียนโฟลเดอร์ data/</div>
                                <div class="font-bold <?php echo is_writable($data_dir) ? 'text-brand' : 'text-red-400'; ?>">
                                    <?php echo is_writable($data_dir) ? 'เขียนได้ (Writable)' : 'อ่านอย่างเดียว'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

