                <!-- 1. STATS SUMMARY SECTION CARDS GRID (4 METRICS) -->
                <div id="statCardsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Stat Card 1: Participants -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-blue-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-blue-500/10">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ผู้เข้าร่วมทั้งหมด</div>
                            <div class="text-xl font-black text-white mt-1"><?php echo $participants_count; ?> <span class="text-xs font-semibold text-slate-400">คน</span></div>
                            <div class="text-[10px] text-emerald-400 font-mono mt-0.5">เช็คอินแล้ว <?php echo $checked_in_count; ?> คน</div>
                        </div>
                    </div>

                    <!-- Stat Card 2: Capstone Tracks -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-purple-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-purple-500/10">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">โครงงาน Capstone</div>
                            <div class="text-xl font-black text-white mt-1">6 <span class="text-xs font-semibold text-slate-400">แทร็กนวัตกรรม</span></div>
                            <div class="text-[10px] text-purple-400 font-mono mt-0.5">Track A ถึง Track F</div>
                        </div>
                    </div>

                    <!-- Stat Card 3: JSON Status -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-brand-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-emerald-500/10">
                            <i class="fas fa-file-code"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ฐานข้อมูลไฟล์ JSON</div>
                            <div class="text-xs font-bold mt-1.5 <?php echo $json_writable ? 'text-brand' : 'text-red-400'; ?>"><?php echo $json_status; ?></div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">/data/participants.json</div>
                        </div>
                    </div>

                    <!-- Stat Card 4: SQLite Database Status -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-yellow-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-yellow-500/10 text-yellow-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-yellow-500/10">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ระบบฐานข้อมูล SQL</div>
                            <div class="text-xs font-bold mt-1.5 <?php echo $db_connected ? 'text-yellow-400' : 'text-red-400'; ?>">
                                <?php echo $db_connected ? 'SQLite3 (Online)' : 'ไม่ได้เชื่อมต่อ'; ?>
                            </div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">/data/leqs_xai.db</div>
                        </div>
                    </div>

                </div>

