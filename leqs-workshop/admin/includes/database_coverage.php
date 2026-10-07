                <!-- 2. DATABASE COVERAGE SECTION (SQLITE & FLAT-FILE JSON) -->
                <div id="dbCoverageSection" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Table Summary Card (Left 2 cols) -->
                    <div class="lg:col-span-2 bg-[#0e1424] p-6 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                        <div class="flex items-center justify-between pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-server text-yellow-400"></i> ตารางฐานข้อมูล SQL (LEQs-xAI Database Engine)
                            </h3>
                            <span class="text-xs font-bold text-brand bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full font-mono">
                                SQLite3 Ready
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-950/40 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-3.5">ชื่อตารางฐานข้อมูล</th>
                                        <th class="px-6 py-3.5 text-center">ประเภท</th>
                                        <th class="px-6 py-3.5 text-right">จำนวนระเบียนข้อมูล (Rows)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-200">
                                    <?php foreach ($tables_summary as $tbl): ?>
                                    <tr class="hover:bg-white/5 transition duration-200">
                                        <td class="px-6 py-4 font-mono font-bold text-blue-400">
                                            <i class="fas fa-table text-slate-500 mr-2"></i> <?php echo htmlspecialchars($tbl['name']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs text-slate-500">
                                            SQLite3 Relational Table
                                        </td>
                                        <td class="px-6 py-4 text-right font-mono font-bold text-white">
                                            <?php echo number_format($tbl['count']); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- JSON database details & file statistics (Right 1 col) -->
                    <div class="bg-[#0e1424] p-6 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                        <div class="pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-file-invoice text-emerald-400"></i> แฟ้มข้อมูล JSON (Flat-File DB)
                            </h3>
                        </div>

                        <div class="space-y-4">
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

                            <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ระบบสำรองไฟล์ (Backup)</span>
                                <span class="text-xs text-emerald-400 font-bold flex items-center gap-1.5">
                                    <i class="fas fa-shield text-xs"></i> อัตโนมัติ (ขีดเขียนไฟล์เสร็จ)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

