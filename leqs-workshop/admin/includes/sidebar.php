        <!-- ======================= LEFT SIDEBAR ASIDE ======================= -->
        <aside class="w-full md:w-72 bg-[#0b0f19] border-r border-white/5 flex flex-col shrink-0">
            
            <!-- Sidebar Header Branding -->
            <div class="p-6 border-b border-white/5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-lg font-black shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-tight text-white font-tech">LEQs-xAI Admin</h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Faculty of Science &amp; Tech RBRU</p>
                </div>
            </div>

            <!-- Current User Badge -->
            <div class="p-4 mx-4 my-4 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 font-black text-sm flex items-center justify-center border border-emerald-500/30">
                        AD
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-200"><?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?></div>
                        <div class="text-[10px] text-brand font-bold flex items-center gap-1.5 mt-0.5 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-brand"></span> ONLINE (ADMIN)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 font-tech">เมนูการจัดการ</div>
                
                <!-- Link 1: Dashboard Home (Active) -->
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-brand/10 text-brand-light rounded-xl font-bold border border-brand/25 transition-all">
                    <i class="fas fa-chart-line text-brand"></i>
                    <span class="text-sm">แผงควบคุมหลัก</span>
                </a>

                <!-- Link 2: Manage Students -->
                <a href="#participantsTableSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-users-gear group-hover:text-blue-400 transition"></i>
                    <span class="text-sm">จัดการรายชื่อผู้เข้าร่วม</span>
                </a>

                <!-- Link 2.5: Manage Announcements -->
                <a href="#announcementsSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-bullhorn group-hover:text-amber-400 transition"></i>
                    <span class="text-sm">จัดการข้อความ &amp; ประกาศ</span>
                </a>

                <!-- Link 3: Manage Gallery -->
                <a href="../#screens" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-images group-hover:text-orange-400 transition"></i>
                    <span class="text-sm">จัดการรูปภาพกิจกรรม</span>
                </a>

                <!-- Link 4: Summary Report & Learning Gain -->
                <a href="#reportSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-chart-line group-hover:text-purple-400 transition"></i>
                    <span class="text-sm">รายงานสรุป &amp; E.I. ผลสัมฤทธิ์</span>
                </a>

                <!-- Link 4.2: Evaluation Satisfaction -->
                <a href="#evaluationSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-star group-hover:text-emerald-400 transition"></i>
                    <span class="text-sm">ผลประเมินความพึงพอใจ</span>
                </a>

                <!-- Link 4.5: Certificate Management -->
                <a href="#certificateSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-award group-hover:text-amber-400 transition"></i>
                    <span class="text-sm">จัดการระบบเกียรติบัตร</span>
                </a>

                <!-- Link 4.8: Official Print Report -->
                <a href="print_report.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-print group-hover:text-blue-400 transition"></i>
                    <span class="text-sm">พิมพ์รายงานทางการ A4</span>
                </a>

                <!-- Link 5: Database Inspector -->
                <a href="#dbCoverageSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-database group-hover:text-yellow-400 transition"></i>
                    <span class="text-sm">ตรวจสอบฐานข้อมูล SQLite/JSON</span>
                </a>

                <div class="h-px bg-white/5 my-4"></div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 font-tech">ความปลอดภัย &amp; ฮาร์ดแวร์</div>

                <!-- Link 6: Hardware Controller Link -->
                <a href="../dashboard/index.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-microchip group-hover:text-cyan-400 transition"></i>
                    <span class="text-sm">ESP32-S3 Web Dashboard</span>
                </a>

                <!-- Link 7: Mobile App Link -->
                <a href="../mobile/index.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-mobile-screen group-hover:text-emerald-400 transition"></i>
                    <span class="text-sm">Smartphone Control App</span>
                </a>

                <div class="h-px bg-white/5 my-4"></div>
                
                <!-- Link 8: Back to Site -->
                <a href="../index.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-home group-hover:text-slate-200 transition"></i>
                    <span class="text-sm">กลับหน้าหลักเว็บไซต์</span>
                </a>

                <!-- Link 9: Logout -->
                <a href="?logout=1" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl font-bold transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text-sm">ออกจากระบบ</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/5 bg-slate-950/20 text-center text-[10px] text-slate-500 font-mono">
                SciRBRU Admin Panel v2.0
            </div>
        </aside>

