    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR WITH ACADEMIC INSTITUTIONAL TOP RIBBON                  -->
    <!-- ========================================================================= -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm fixed w-full top-0 z-50 transition-all duration-300 border-b border-gray-100" id="navbar">
        
        <!-- Official Academic Top Ribbon (RBRU x Praneetwittayakhom School) -->
        <div class="academic-top-ribbon py-1 px-3 sm:px-4 md:px-6 w-full max-w-full overflow-hidden">
            <div class="container mx-auto flex items-center justify-between gap-2 text-[10px] md:text-[11px] min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2 min-w-0 truncate">
                    <span class="inline-flex items-center gap-1 sm:gap-1.5 font-bold tracking-wide text-white truncate">
                        <i class="fa-solid fa-microchip text-amber-300 shrink-0"></i> <strong class="text-amber-300 shrink-0">LEQs xAI</strong> <span class="text-cyan-200 font-semibold shrink-0">Digital Agri-Envi</span> <span class="text-emerald-100 truncate">คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
                    </span>
                </div>
                <div class="hidden lg:flex items-center gap-3 text-emerald-100">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-microchip text-cyan-300"></i> Smart Agri-AIoT &amp; TinyML Lab
                    </span>
                    <span class="text-emerald-400/60">•</span>
                    <span class="flex items-center gap-1 font-mono text-[9px] bg-emerald-950/70 px-2 py-0.5 rounded-full border border-emerald-400/30 text-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE TELEMETRY
                    </span>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 md:px-6">
            <div class="flex justify-between items-center h-16 md:h-18">
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <!-- Outer Glow Ring -->
                        <div class="absolute inset-0 bg-emerald-500 rounded-full blur-md opacity-25 group-hover:opacity-60 animate-pulse transition duration-500"></div>
                        
                        <!-- Animated Mascot Logo -->
                        <img id="navbarLogo" src="assets/images/nong_smartscience.png" 
                             alt="LEQs Mascot" 
                             class="relative w-full h-full object-cover rounded-full shadow-lg transform group-hover:scale-110 transition-all duration-500 ease-in-out ring-1 ring-white/50 backdrop-blur-sm"
                             style="filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4));">
                        
                        <!-- Orbiting Dot Animation -->
                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-cyan-400 rounded-full animate-ping"></div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 tracking-tight" style="font-family: 'Outfit', sans-serif;">
                                LEQs-xAI
                            </span>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-widest">
                                RBRU 2026
                            </span>
                        </div>
                        <span class="text-[10px] text-gray-500 font-medium tracking-widest uppercase group-hover:text-emerald-600 transition">
                            Agri &amp; Environmental AI
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu: EXACTLY 5 MAIN ITEMS -->
                <div class="hidden md:flex space-x-1 font-medium text-gray-600 items-center text-sm">
                    
                    <!-- MENU 1: ภาพรวม -->
                    <a href="#overview" class="px-3.5 py-2 rounded-xl hover:text-emerald-600 hover:bg-emerald-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-emerald-500"></i> ภาพรวม
                    </a>

                    <!-- MENU 2: ระบบผู้เรียน (Student System Dropdown) -->
                    <div class="relative group px-2 py-2">
                        <button class="flex items-center gap-1.5 hover:text-emerald-600 transition py-1">
                            <i class="fa-solid fa-user-graduate text-emerald-500"></i> ระบบผู้เรียน <i class="fa-solid fa-chevron-down text-xs ml-0.5 opacity-60"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-64 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="pages/register.php" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-id-card text-emerald-500 w-5"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ลงทะเบียนเข้าร่วมอบรม</div>
                                    <div class="text-[10px] text-gray-500">สำหรับนักเรียน ครู และเกษตรกร</div>
                                </div>
                            </a>
                            <a href="pages/student_list.php" class="px-3.5 py-2.5 rounded-xl hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-list-check text-purple-500 w-5"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ประกาศรายชื่อผู้สมัคร</div>
                                    <div class="text-[10px] text-gray-500">ตรวจสอบสถานะและกลุ่มโครงงาน</div>
                                </div>
                            </a>
                            <hr class="border-gray-100 my-1">
                            <a href="pages/assessment_pre.php" class="px-3.5 py-2.5 rounded-xl hover:bg-orange-50 hover:text-orange-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-pen text-orange-500 w-5"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">แบบทดสอบก่อนเรียน (Pre-test)</div>
                                    <div class="text-[10px] text-gray-500">วัดสมรรถนะก่อนเริ่ม 7 Modules</div>
                                </div>
                            </a>
                            <a href="pages/assessment_post.php" class="px-3.5 py-2.5 rounded-xl hover:bg-pink-50 hover:text-pink-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-circle-check text-pink-500 w-5"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">แบบทดสอบหลังเรียน (Post-test)</div>
                                    <div class="text-[10px] text-gray-500">ประเมินผลการเรียนรู้</div>
                                </div>
                            </a>
                            <a href="pages/certificate.php" class="px-3.5 py-2.5 rounded-xl hover:bg-yellow-50 hover:text-yellow-700 transition flex items-center gap-3 border-t border-gray-100">
                                <i class="fa-solid fa-certificate text-yellow-500 w-5"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">เกียรติบัตร (E-Certificate)</div>
                                    <div class="text-[10px] text-gray-500">ดาวน์โหลดวุฒิบัตรรับรองทักษะ</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- MENU 3: 7 โมดูล -->
                    <a href="#modules" class="px-3.5 py-2 rounded-xl hover:text-cyan-600 hover:bg-cyan-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-cubes text-cyan-500"></i> 7 โมดูล
                    </a>

                    <!-- MENU 4: Virtual Lab -->
                    <a href="#simulators" class="px-3.5 py-2 rounded-xl hover:text-amber-600 hover:bg-amber-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-flask-vial text-amber-500"></i> Virtual Lab
                    </a>

                    <!-- MENU: SDGs -->
                    <a href="#sdgs" class="px-3 py-2 rounded-xl hover:text-emerald-700 hover:bg-emerald-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-earth-asia text-emerald-600"></i> SDGs
                    </a>

                    <!-- MENU: LEQs Teams -->
                    <a href="#leqs-teams" class="px-3 py-2 rounded-xl hover:text-purple-700 hover:bg-purple-50 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-purple-600"></i> LEQs Teams
                    </a>

                    <!-- MENU 5: นวัตกรรม & เอกสาร (Dropdown) -->
                    <div class="relative group px-2 py-2">
                        <button class="flex items-center gap-1.5 text-emerald-700 hover:text-emerald-800 font-bold transition py-1 bg-emerald-50 px-3.5 rounded-full border border-emerald-200">
                            <i class="fa-solid fa-layer-group text-emerald-600"></i> นวัตกรรม &amp; เอกสาร <i class="fa-solid fa-chevron-down text-xs ml-0.5"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-80 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            
                            <!-- สื่อประชาสัมพันธ์ & วิดีโอ -->
                            <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-bullhorn text-emerald-600"></i> สื่อประชาสัมพันธ์โครงการ
                            </div>
                            <a href="poster.php" class="px-3.5 py-2 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-image text-emerald-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">โปสเตอร์ประชาสัมพันธ์ (A4 &amp; 9:16)</span>
                                    <span class="text-[10px] text-gray-500">สื่อประชาสัมพันธ์สำหรับพิมพ์และแชร์ Social</span>
                                </div>
                            </a>
                            <a href="promo_video.php" class="px-3.5 py-2 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-film text-cyan-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">วิดีโอสปอตประชาสัมพันธ์ (Motion Video)</span>
                                    <span class="text-[10px] text-gray-500">ทีเซอร์แอนิเมชัน 6 ฉากพร้อมเสียงดนตรี</span>
                                </div>
                            </a>
                            
                            <hr class="border-gray-100 my-1">
                            
                            <div class="px-3 pt-1 pb-1 text-[10px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-microchip text-gray-400"></i> นวัตกรรมและเอกสาร
                            </div>
                            <a href="#iot-platform" class="px-3.5 py-2 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-mobile-screen-button text-cyan-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">Smartphone App &amp; Web Dashboard</span>
                                    <span class="text-[10px] text-gray-500">มอนิเตอร์ ควบคุม สั่งการบอร์ดออนไลน์</span>
                                </div>
                            </a>
                            <a href="#learning-resources" class="px-3.5 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-graduation-cap text-indigo-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">แหล่งเรียนรู้เพิ่มเติม 10 ระบบ</span>
                                    <span class="text-[10px] text-gray-500">เชื่อมโยงระบบนิเวศ cmu_aiot &amp; soil_nutrient2026</span>
                                </div>
                            </a>
                            <a href="#screens" class="px-3.5 py-2 rounded-xl hover:bg-amber-50 hover:text-amber-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-display text-amber-500 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">จอแสดงผล ATD3.5-S3 (11 Screens)</span>
                                    <span class="text-[10px] text-gray-500">สกรีนช็อตฮาร์ดแวร์จริง 480x320</span>
                                </div>
                            </a>
                            <a href="#capstone" class="px-3.5 py-2 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-diagram-project text-rose-500 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">โครงงาน Capstone (6 Tracks)</span>
                                    <span class="text-[10px] text-gray-500">นวัตกรรมเกษตรและสิ่งแวดล้อม</span>
                                </div>
                            </a>
                            <a href="#documents" class="px-3.5 py-2 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-book-open text-emerald-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">เอกสารและคู่มือโครงการ</span>
                                    <span class="text-[10px] text-gray-500">ข้อเสนอโครงการและคู่มือปฏิบัติการ</span>
                                </div>
                            </a>
                            <hr class="border-gray-100 my-1">
                            <a href="admin/index.php" class="px-3.5 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-black transition flex items-center justify-between text-xs font-bold">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-cyan-400"></i> แผงควบคุม Admin CMS</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Right Action Button & Mobile Toggle -->
                <div class="flex items-center gap-3">
                    <a href="pages/register.php" class="hidden sm:flex bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-5 py-2.5 rounded-full font-bold shadow-md hover:shadow-emerald-500/30 hover:scale-105 transition transform items-center gap-2 text-xs md:text-sm">
                        <i class="fa-solid fa-user-plus"></i> ลงทะเบียน
                    </a>

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()" class="md:hidden text-gray-600 hover:text-emerald-600 focus:outline-none p-2 rounded-xl bg-gray-50 border border-gray-200">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 px-6 py-4 space-y-3">
            <a href="#overview" onclick="toggleMobileMenu()" class="block py-2 text-gray-700 hover:text-emerald-600"><i class="fa-solid fa-house w-6 text-emerald-500"></i> 1. ภาพรวมโครงการ</a>
            <a href="pages/register.php" class="block py-2 text-gray-700 hover:text-emerald-600 font-bold"><i class="fa-solid fa-user-plus w-6 text-emerald-600"></i> 2. ลงทะเบียนเข้าร่วมอบรม</a>
            <a href="pages/student_list.php" class="block py-2 text-gray-700 hover:text-purple-600"><i class="fa-solid fa-list-check w-6 text-purple-500"></i> ประกาศรายชื่อผู้สมัคร</a>
            <a href="pages/assessment_pre.php" class="block py-2 text-gray-700 hover:text-orange-600"><i class="fa-solid fa-file-pen w-6 text-orange-500"></i> แบบทดสอบก่อนเรียน (Pre-test)</a>
            <a href="pages/assessment_post.php" class="block py-2 text-gray-700 hover:text-pink-600"><i class="fa-solid fa-file-circle-check w-6 text-pink-500"></i> แบบทดสอบหลังเรียน (Post-test)</a>
            <hr class="border-gray-100">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider pt-1">นวัตกรรม &amp; เอกสาร</div>
            <a href="poster.php" class="block py-1.5 text-emerald-700 font-bold hover:text-emerald-800"><i class="fa-solid fa-bullhorn w-6 text-emerald-600"></i> 📢 โปสเตอร์ประชาสัมพันธ์ (A4 / 9:16)</a>
            <a href="promo_video.php" class="block py-1.5 text-cyan-700 font-bold hover:text-cyan-800"><i class="fa-solid fa-film w-6 text-cyan-600"></i> 🎬 วิดีโอสปอตประชาสัมพันธ์ (Motion Video)</a>
            <a href="#iot-platform" onclick="toggleMobileMenu()" class="block py-1.5 text-cyan-700 font-medium"><i class="fa-solid fa-mobile-screen-button w-6 text-cyan-600"></i> Smartphone App &amp; Web Dashboard</a>
            <a href="#modules" onclick="toggleMobileMenu()" class="block py-1.5 text-gray-700 hover:text-cyan-600"><i class="fa-solid fa-cubes w-6 text-cyan-500"></i> 3. หลักสูตร 7 โมดูล</a>
            <a href="#simulators" onclick="toggleMobileMenu()" class="block py-1.5 text-gray-700 hover:text-amber-600"><i class="fa-solid fa-flask-vial w-6 text-amber-500"></i> 4. Virtual Lab เสมือนจริง</a>
            <a href="#sdgs" onclick="toggleMobileMenu()" class="block py-1.5 text-emerald-700 font-medium hover:text-emerald-800"><i class="fa-solid fa-earth-asia w-6 text-emerald-600"></i> เป้าหมายการพัฒนาที่ยั่งยืน (SDGs)</a>
            <a href="#leqs-teams" onclick="toggleMobileMenu()" class="block py-1.5 text-purple-700 font-medium hover:text-purple-800"><i class="fa-solid fa-users w-6 text-purple-600"></i> ทีมวิทยากร &amp; นักวิจัย (LEQs Teams)</a>
            <a href="#learning-resources" onclick="toggleMobileMenu()" class="block py-1.5 text-indigo-700 font-medium"><i class="fa-solid fa-graduation-cap w-6 text-indigo-600"></i> 5. แหล่งเรียนรู้เพิ่มเติม 10 ระบบ</a>
            <a href="#screens" onclick="toggleMobileMenu()" class="block py-1.5 text-gray-700 hover:text-amber-600"><i class="fa-solid fa-display w-6 text-amber-500"></i> จอแสดงผล ATD3.5-S3 (11 จอ)</a>
            <a href="#documents" onclick="toggleMobileMenu()" class="block py-1.5 text-gray-700 hover:text-emerald-600"><i class="fa-solid fa-book-open w-6 text-emerald-600"></i> เอกสารและคู่มือโครงการ</a>
            <hr class="border-gray-100">
            <a href="admin/index.php" class="block py-2 text-slate-900 font-bold"><i class="fa-solid fa-shield-halved w-6 text-cyan-500"></i> แผงควบคุมระบบ Admin CMS</a>
        </div>
    </nav>
