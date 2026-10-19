                <!-- 2. DATABASE COVERAGE SECTION (SQLITE & FLAT-FILE JSON DUAL-STORAGE ARCHITECTURE) -->
                <div id="dbCoverageSection" class="grid grid-cols-1 lg:grid-cols-3 gap-8 scroll-mt-24">
                    
                    <!-- Table Summary Card (Left 2 cols) -->
                    <div class="lg:col-span-2 bg-[#0e1424] p-6 sm:p-7 rounded-3xl border border-white/5 space-y-6 shadow-xl relative overflow-hidden">
                        <!-- Subtle ambient glow -->
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-500/5 blur-3xl pointer-events-none rounded-full"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/5 relative z-10">
                            <div>
                                <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                    <i class="fas fa-server text-yellow-400"></i> ตารางฐานข้อมูล SQL (Edge xAI : 4DAgriEnvi Database Engine)
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    เครื่องยนต์ฐานข้อมูลหลักแบบ Relational Database จัดเก็บระเบียนข้อมูลฮาร์ดแวร์ ผู้ลงทะเบียน และเซนเซอร์
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-mono font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    SQLite3 Ready
                                </span>
                                <span class="text-xs font-bold text-slate-300 bg-white/5 border border-white/10 px-3 py-1 rounded-full font-mono">
                                    <?php echo number_format($total_db_rows ?? 0); ?> Rows
                                </span>
                            </div>
                        </div>

                        <!-- 4 Quick-Glance SQLite Statistics Pills -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 relative z-10">
                            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 space-y-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech flex items-center gap-1">
                                    <i class="fas fa-file-database text-yellow-400"></i> ไฟล์ฐานข้อมูล
                                </span>
                                <div class="text-xs font-bold font-mono text-white truncate" title="<?php echo htmlspecialchars($db_file ?? 'data/leqs_xai.db'); ?>">
                                    leqs_xai.db
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 space-y-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech flex items-center gap-1">
                                    <i class="fas fa-hard-drive text-blue-400"></i> ขนาดไฟล์ SQL
                                </span>
                                <div class="text-xs font-bold font-mono text-emerald-400">
                                    <?php echo number_format(($sqlite_file_size ?? 0) / (1024 * 1024), 2); ?> MB
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 space-y-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech flex items-center gap-1">
                                    <i class="fas fa-table-cells text-cyan-400"></i> จำนวนตาราง
                                </span>
                                <div class="text-xs font-bold font-mono text-white">
                                    <?php echo count($tables_summary ?? []); ?> ตารางหลัก
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 space-y-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech flex items-center gap-1">
                                    <i class="fas fa-bolt text-amber-400"></i> โหมดการทำงาน
                                </span>
                                <div class="text-xs font-bold font-mono text-cyan-300">
                                    WAL Active
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Table List -->
                        <div class="overflow-x-auto relative z-10">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-950/60 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/5">
                                    <tr>
                                        <th class="px-5 py-3.5">ชื่อตารางฐานข้อมูล</th>
                                        <th class="px-5 py-3.5 hidden md:table-cell">วัตถุประสงค์การใช้งาน</th>
                                        <th class="px-5 py-3.5 text-center">ประเภท</th>
                                        <th class="px-5 py-3.5 text-right">จำนวนระเบียน (Rows)</th>
                                        <th class="px-5 py-3.5 text-center">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-200">
                                    <?php 
                                    $table_icons = [
                                        'participants'   => ['icon' => 'fa-users', 'color' => 'text-blue-400'],
                                        'announcements'  => ['icon' => 'fa-bullhorn', 'color' => 'text-amber-400'],
                                        'evaluations'    => ['icon' => 'fa-star', 'color' => 'text-emerald-400'],
                                        'telemetry_logs' => ['icon' => 'fa-wave-square', 'color' => 'text-rose-400'],
                                        'relay_states'   => ['icon' => 'fa-toggle-on', 'color' => 'text-purple-400']
                                    ];
                                    foreach ($tables_summary as $tbl): 
                                        $tinfo = $table_icons[$tbl['name']] ?? ['icon' => 'fa-table', 'color' => 'text-slate-400'];
                                    ?>
                                    <tr class="hover:bg-white/[0.03] transition duration-200 group">
                                        <td class="px-5 py-3.5 font-mono font-bold text-blue-300 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-2">
                                                <i class="fas <?php echo $tinfo['icon'] . ' ' . $tinfo['color']; ?>"></i>
                                                <span><?php echo htmlspecialchars($tbl['name']); ?></span>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-400 hidden md:table-cell">
                                            <?php echo htmlspecialchars($tbl['desc'] ?? 'ตารางระเบียนข้อมูลระบบ'); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-xs text-slate-500 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-md bg-white/5 border border-white/5 font-mono text-[11px]">SQLite3</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-mono font-bold whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-lg <?php echo $tbl['count'] > 1000 ? 'bg-rose-500/10 text-rose-300 border border-rose-500/20' : 'bg-slate-900 text-white'; ?>">
                                                <?php echo number_format($tbl['count']); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                            <button type="button" 
                                                    onclick="if (typeof selectDbTable === 'function') { selectDbTable('<?php echo $tbl['name']; ?>'); document.getElementById('databaseManagerSection')?.scrollIntoView({behavior:'smooth'}); }" 
                                                    class="px-2.5 py-1 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-300 hover:text-white border border-blue-500/25 text-xs font-bold transition flex items-center gap-1.5 mx-auto"
                                                    title="เปิดดูและจัดการระเบียนข้อมูลในตารางนี้">
                                                <span>CRUD</span>
                                                <i class="fas fa-arrow-right text-[10px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Note -->
                        <div class="pt-3 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-2">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-shield-halved text-emerald-400"></i>
                                <span>ระบบใช้สถาปัตยกรรม SQLite3 พร้อม PHP Native Extension ประสิทธิภาพสูง ไม่พึ่งพา Network Latency</span>
                            </span>
                            <a href="#databaseManagerSection" class="text-blue-400 hover:underline font-bold flex items-center gap-1">
                                จัดการตารางทั้งหมด <i class="fas fa-chevron-down text-[9px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- JSON database details & file statistics (Right 1 col) -->
                    <div class="bg-[#0e1424] p-6 sm:p-7 rounded-3xl border border-white/5 space-y-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
                        <!-- Subtle ambient glow -->
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/5 blur-3xl pointer-events-none rounded-full"></div>

                        <div class="space-y-5 relative z-10">
                            <div class="pb-4 border-b border-white/5 flex items-center justify-between">
                                <div>
                                    <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                        <i class="fas fa-file-invoice text-emerald-400"></i> แฟ้มข้อมูล JSON (Flat-File DB)
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        สถาปัตยกรรม Dual-Storage ซิงก์คู่ขนาน SQLite + Flat-file JSON
                                    </p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-mono font-bold shrink-0">
                                    <?php echo count($json_files_catalog ?? []); ?> Files
                                </span>
                            </div>

                            <!-- List of All JSON Flat-Files in Ecosystem -->
                            <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                                <?php if (!empty($json_files_catalog)): ?>
                                    <?php foreach ($json_files_catalog as $jf): 
                                        $fexists = file_exists($jf['path']);
                                        $fsize = $fexists ? filesize($jf['path']) : 0;
                                        $fsize_kb = number_format($fsize / 1024, 2);
                                    ?>
                                    <div class="p-3 rounded-2xl bg-slate-950/40 border border-white/5 hover:border-white/10 transition group">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-7 h-7 rounded-lg <?php echo $jf['bg_color'] ?? 'bg-white/5'; ?> flex items-center justify-center shrink-0 text-xs <?php echo $jf['color']; ?>">
                                                    <i class="fas <?php echo $jf['icon']; ?>"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold font-mono text-white group-hover:text-cyan-300 transition truncate">
                                                        <?php echo htmlspecialchars($jf['name']); ?>
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 truncate">
                                                        <?php echo htmlspecialchars($jf['title']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="text-[11px] font-mono font-bold text-slate-200">
                                                    <?php echo $fsize_kb; ?> KB
                                                </span>
                                                <div class="text-[9px] text-emerald-400 font-bold flex items-center justify-end gap-1">
                                                    <i class="fas fa-circle-check text-[8px]"></i> <?php echo $jf['badge'] ?? 'Synced'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ขนาดไฟล์ข้อมูล JSON</span>
                                        <span class="text-base font-black text-white font-mono">
                                            <?php echo file_exists($json_file) ? number_format(filesize($json_file) / 1024, 2) . ' KB' : '0.00 KB'; ?>
                                        </span>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ที่อยู่ไฟล์แฟ้มข้อมูล</span>
                                        <span class="text-xs font-mono text-slate-400 break-all select-all">
                                            /data/participants.json
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Dual-Mirroring Integrity Badge -->
                        <div class="pt-4 border-t border-white/5 relative z-10">
                            <div class="p-3.5 rounded-2xl bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <i class="fas fa-shield text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-emerald-300">Dual-Write Auto-Mirroring Active</div>
                                    <div class="text-[10px] text-slate-400 leading-snug">
                                        ทุกการเพิ่ม/แก้ไข/ลบ จะเขียนลงทั้ง SQLite และ JSON พร้อมกัน 100%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
