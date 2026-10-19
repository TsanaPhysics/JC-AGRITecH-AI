    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR WITH ACADEMIC INSTITUTIONAL TOP RIBBON                  -->
    <!-- ========================================================================= -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm fixed w-full top-0 z-50 transition-all duration-300 border-b border-gray-100" id="navbar">
        
        <!-- Official Academic Top Ribbon (RBRU x Praneetwittayakhom School) -->
        <div class="academic-top-ribbon py-1 px-3 sm:px-4 md:px-6 w-full max-w-full overflow-hidden">
            <div class="container mx-auto max-w-7xl 2xl:max-w-[1400px] flex items-center justify-between gap-2 text-[10px] md:text-[11px] min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2 min-w-0 truncate">
                    <span class="inline-flex items-center gap-1 sm:gap-1.5 font-bold tracking-wide text-white truncate">
                        <i class="fa-solid fa-graduation-cap text-amber-300 shrink-0"></i> 
                        <strong class="text-amber-300 shrink-0">โครงการพัฒนาสมรรถนะเยาวชน ครู และเกษตรกรสู่ศตวรรษที่ 21</strong> 
                        <span class="text-emerald-100 truncate">• Edge xAIoT : 4D-AgriEnvi (ฟรีตลอดโครงการ)</span>
                    </span>
                </div>
                <div class="hidden lg:flex items-center gap-3 text-emerald-100 shrink-0">
                    <a href="tel:0937422654" class="hover:text-amber-300 transition flex items-center gap-1 text-[10px]">
                        <i class="fa-solid fa-phone text-amber-300"></i> สอบถาม: ผศ.ดร.ชีวะ 093 742 2654
                    </a>
                    <span class="text-emerald-400/60">•</span>
                    <a href="poster.php" class="hover:text-cyan-300 transition flex items-center gap-1 text-[10px]">
                        <i class="fa-solid fa-bullhorn text-cyan-300"></i> โปสเตอร์ประชาสัมพันธ์
                    </a>
                    <span class="text-emerald-400/60">•</span>
                    <span class="flex items-center gap-1 font-mono text-[9px] bg-emerald-950/70 px-2 py-0.5 rounded-full border border-emerald-400/30 text-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE TELEMETRY
                    </span>
                </div>
            </div>
        </div>

        <div class="container mx-auto max-w-7xl 2xl:max-w-[1400px] px-4 md:px-6">
            <div class="flex justify-between items-center h-16 md:h-18">
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <!-- Outer Glow Ring -->
                        <div class="absolute inset-0 bg-emerald-500 rounded-full blur-md opacity-25 group-hover:opacity-60 animate-pulse transition duration-500"></div>
                        
                        <!-- Animated Mascot Logo -->
                        <img id="navbarLogo" src="assets/images/nong_smartscience.png" 
                             alt="Edge xAIoT Mascot" 
                             class="relative w-full h-full object-cover rounded-full shadow-lg transform group-hover:scale-110 transition-all duration-500 ease-in-out ring-1 ring-white/50 backdrop-blur-sm"
                             style="filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4));">
                        
                        <!-- Orbiting Dot Animation -->
                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-cyan-400 rounded-full animate-ping"></div>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xl sm:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 tracking-tight" style="font-family: 'Outfit', sans-serif;">
                                Edge xAIoT
                            </span>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-widest font-mono">
                                4D-AgriEnvi
                            </span>
                        </div>
                        <span class="text-[10px] text-gray-500 font-medium tracking-wide group-hover:text-emerald-600 transition truncate max-w-[220px] sm:max-w-none">
                            LEQs • หน่วยบริการวิเคราะห์สิ่งแวดล้อมเพื่อท้องถิ่น
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu: EXACTLY 5 MAIN MENU GROUPS -->
                <div class="hidden md:flex space-x-1 lg:space-x-1.5 font-medium text-gray-700 items-center text-xs lg:text-sm">
                    
                    <!-- 1. ภาพรวมโครงการ (Overview & Framework) -->
                    <div class="relative group px-1 py-2">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl hover:text-emerald-700 hover:bg-emerald-50 transition group-hover:bg-emerald-50 group-hover:text-emerald-700 cursor-pointer">
                            <i class="fa-solid fa-house text-emerald-600"></i>
                            <span>ภาพรวม</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-60 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="#overview" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-compass text-emerald-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ภาพรวมโครงการ</div>
                                    <div class="text-[10px] text-gray-500">วิสัยทัศน์ วัตถุประสงค์ และเป้าหมาย</div>
                                </div>
                            </a>
                            <a href="#stepper" class="px-3.5 py-2.5 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-stairs text-teal-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">กรอบการเรียนรู้ OBE Stepper</div>
                                    <div class="text-[10px] text-gray-500">5 ขั้นตอนกระบวนการ Active Learning</div>
                                </div>
                            </a>
                            <a href="#framework" class="px-3.5 py-2.5 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-network-wired text-cyan-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">กรอบแนวคิด LEQs Architecture</div>
                                    <div class="text-[10px] text-gray-500">บูรณาการนิเวศน์ฟิสิกส์และสิ่งแวดล้อม</div>
                                </div>
                            </a>
                            <a href="#sdgs" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3 border-t border-gray-100">
                                <i class="fa-solid fa-earth-asia text-emerald-600 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">เป้าหมายความยั่งยืน (SDGs)</div>
                                    <div class="text-[10px] text-gray-500">UN SDGs 2, 4, 9, 13 สู่ชุมชน</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 2. ระบบผู้เรียน (Student Portal) -->
                    <div class="relative group px-1 py-2">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl hover:text-emerald-700 hover:bg-emerald-50 transition group-hover:bg-emerald-50 group-hover:text-emerald-700 cursor-pointer">
                            <i class="fa-solid fa-user-graduate text-emerald-600"></i>
                            <span>ระบบผู้เรียน</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-60 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="pages/register.php" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-id-card text-emerald-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ลงทะเบียนเข้าร่วมอบรม</div>
                                    <div class="text-[10px] text-gray-500">สำหรับนักเรียน ครู และเกษตรกร (ฟรี)</div>
                                </div>
                            </a>
                            <a href="pages/student_list.php" class="px-3.5 py-2.5 rounded-xl hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-list-check text-purple-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ประกาศรายชื่อผู้สมัคร</div>
                                    <div class="text-[10px] text-gray-500">ตรวจสอบสถานะและกลุ่มโครงงาน</div>
                                </div>
                            </a>
                            <hr class="border-gray-100 my-1">
                            <a href="pages/assessment_pre.php" class="px-3.5 py-2.5 rounded-xl hover:bg-orange-50 hover:text-orange-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-pen text-orange-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">แบบทดสอบก่อนเรียน (Pre-test)</div>
                                    <div class="text-[10px] text-gray-500">วัดสมรรถนะก่อนเริ่ม 7 Modules</div>
                                </div>
                            </a>
                            <a href="pages/assessment_post.php" class="px-3.5 py-2.5 rounded-xl hover:bg-pink-50 hover:text-pink-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-circle-check text-pink-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">แบบทดสอบหลังเรียน (Post-test)</div>
                                    <div class="text-[10px] text-gray-500">ประเมินผลสัมฤทธิ์ทางการเรียนรู้</div>
                                </div>
                            </a>
                            <a href="pages/certificate.php" class="px-3.5 py-2.5 rounded-xl hover:bg-yellow-50 hover:text-yellow-700 transition flex items-center gap-3 border-t border-gray-100">
                                <i class="fa-solid fa-certificate text-yellow-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">เกียรติบัตร (E-Certificate)</div>
                                    <div class="text-[10px] text-gray-500">ดาวน์โหลดวุฒิบัตรรับรองทักษะ</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 3. หลักสูตร 7 โมดูล (Curriculum & Hardware) -->
                    <div class="relative group px-1 py-2">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl hover:text-cyan-700 hover:bg-cyan-50 transition group-hover:bg-cyan-50 group-hover:text-cyan-700 cursor-pointer">
                            <i class="fa-solid fa-cubes text-cyan-600"></i>
                            <span>7 โมดูล</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-60 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="#modules" class="px-3.5 py-2.5 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-layer-group text-cyan-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">โครงสร้างหลักสูตร 7 โมดูล</div>
                                    <div class="text-[10px] text-gray-500">เนื้อหาฟิสิกส์ AIoT เกษตร &amp; สิ่งแวดล้อม</div>
                                </div>
                            </a>
                            <a href="#screens" class="px-3.5 py-2.5 rounded-xl hover:bg-amber-50 hover:text-amber-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-display text-amber-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">11 จอแสดงผล ATD3.5-S3</div>
                                    <div class="text-[10px] text-gray-500">สกรีนช็อต UI ฮาร์ดแวร์จริง 480x320</div>
                                </div>
                            </a>
                            <a href="#learning-resources" class="px-3.5 py-2.5 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition flex items-center gap-3 border-t border-gray-100">
                                <i class="fa-solid fa-graduation-cap text-indigo-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">10 แหล่งเรียนรู้ดิจิทัล &amp; E-Book</div>
                                    <div class="text-[10px] text-gray-500">ระบบนิเวศ cmu_aiot &amp; soil_nutrient2026</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 4. Virtual Lab & AI (Virtual Lab & IoT Hub) -->
                    <div class="relative group px-1 py-2">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl hover:text-teal-700 hover:bg-teal-50 transition group-hover:bg-teal-50 group-hover:text-teal-700 cursor-pointer">
                            <i class="fa-solid fa-flask-vial text-teal-600"></i>
                            <span>Virtual Lab &amp; AI</span>
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] bg-cyan-100 text-cyan-800 font-bold font-mono">3D AR</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-60 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-76 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="#simulators" class="px-3.5 py-2.5 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-cube text-cyan-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">ห้องทดลองเสมือนจริง 3D AR</div>
                                    <div class="text-[10px] text-gray-500">ฟิสิกส์จำลองและเครื่องมือทดลองเสมือน</div>
                                </div>
                            </a>
                            <a href="app/durian-leaf-app/index.html" target="_blank" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-mobile-screen-button text-emerald-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">Mobile Edge AI ตรวจโรคใบพืช</div>
                                    <div class="text-[10px] text-gray-500">PyTorch CV โมเดลจำแนกโรคทุเรียน</div>
                                </div>
                            </a>
                            <a href="#iot-platform" class="px-3.5 py-2.5 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-gauge-high text-teal-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">แผงควบคุมเซ็นเซอร์สด ESP32-S3</div>
                                    <div class="text-[10px] text-gray-500">Live Telemetry &amp; Relay Controller</div>
                                </div>
                            </a>
                            <a href="#capstone" class="px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition flex items-center gap-3 border-t border-gray-100">
                                <i class="fa-solid fa-diagram-project text-rose-500 w-5 text-center"></i>
                                <div>
                                    <div class="font-bold text-xs text-gray-800">โครงงานบูรณาการ Capstone</div>
                                    <div class="text-[10px] text-gray-500">6 แทร็กนวัตกรรมเกษตรและสิ่งแวดล้อม</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 5. นวัตกรรม & ทีมงาน (Media, Docs & LEQs Teams) -->
                    <div class="relative group px-1 py-2">
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-emerald-800 hover:text-emerald-900 font-bold hover:bg-emerald-50 transition group-hover:bg-emerald-50 bg-emerald-50/70 border border-emerald-200/80 cursor-pointer">
                            <i class="fa-solid fa-layer-group text-emerald-600"></i>
                            <span>นวัตกรรม &amp; สื่อ</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-80 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-bullhorn text-emerald-600"></i> สื่อประชาสัมพันธ์โครงการ
                            </div>
                            <a href="poster.php" class="px-3.5 py-2 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-image text-emerald-600 w-5 text-center"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">โปสเตอร์ประชาสัมพันธ์ (A4 &amp; 9:16)</span>
                                    <span class="text-[10px] text-gray-500">สื่อประชาสัมพันธ์สำหรับพิมพ์และแชร์ Social</span>
                                </div>
                            </a>
                            <a href="promo_video.php" class="px-3.5 py-2 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-film text-cyan-600 w-5 text-center"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">วิดีโอสปอตประชาสัมพันธ์ (Motion Video)</span>
                                    <span class="text-[10px] text-gray-500">ทีเซอร์แอนิเมชัน 6 ฉากพร้อมเสียงดนตรี</span>
                                </div>
                            </a>
                            <hr class="border-gray-100 my-1">
                            <div class="px-3 pt-1 pb-1 text-[10px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-folder-open text-gray-400"></i> เอกสารและทีมวิจัย
                            </div>
                            <a href="#documents" class="px-3.5 py-2 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-book-open text-blue-600 w-5 text-center"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">เอกสารและคู่มือโครงการ</span>
                                    <span class="text-[10px] text-gray-500">ข้อเสนอโครงการและคู่มือปฏิบัติการ</span>
                                </div>
                            </a>
                            <a href="#leqs-teams" class="px-3.5 py-2 rounded-xl hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-users text-purple-600 w-5 text-center"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">ทีมวิจัยและวิทยากร (LEQs Teams)</span>
                                    <span class="text-[10px] text-gray-500">คณาจารย์ มรภ.รำไพพรรณี และผู้ทรงคุณวุฒิ</span>
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

        <!-- Mobile Dropdown Menu: Organized into the 5 Main Groups -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 px-6 py-4 space-y-3 max-h-[85vh] overflow-y-auto">
            <!-- Group 1 -->
            <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider pt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-house text-emerald-600"></i> 1. ภาพรวมโครงการ &amp; SDGs
            </div>
            <a href="#overview" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-emerald-600 text-xs">ภาพรวมและวัตถุประสงค์</a>
            <a href="#stepper" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-teal-600 text-xs">กรอบการเรียนรู้ OBE Stepper</a>
            <a href="#framework" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-cyan-600 text-xs">กรอบแนวคิด LEQs Architecture</a>
            <a href="#sdgs" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-emerald-600 text-xs">เป้าหมายความยั่งยืน UN SDGs</a>
            
            <hr class="border-gray-100">
            <!-- Group 2 -->
            <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider pt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-user-graduate text-emerald-600"></i> 2. ระบบผู้เรียน &amp; ประเมินผล
            </div>
            <a href="pages/register.php" class="block pl-4 py-1.5 text-emerald-700 font-bold hover:text-emerald-800 text-xs">ลงทะเบียนเข้าร่วมอบรม (ฟรี)</a>
            <a href="pages/student_list.php" class="block pl-4 py-1.5 text-gray-700 hover:text-purple-600 text-xs">ประกาศรายชื่อผู้สมัคร</a>
            <a href="pages/assessment_pre.php" class="block pl-4 py-1.5 text-gray-700 hover:text-orange-600 text-xs">แบบทดสอบก่อนเรียน (Pre-test)</a>
            <a href="pages/assessment_post.php" class="block pl-4 py-1.5 text-gray-700 hover:text-pink-600 text-xs">แบบทดสอบหลังเรียน (Post-test)</a>
            <a href="pages/certificate.php" class="block pl-4 py-1.5 text-gray-700 hover:text-yellow-600 text-xs">เกียรติบัตร (E-Certificate)</a>

            <hr class="border-gray-100">
            <!-- Group 3 -->
            <div class="text-[10px] font-bold text-cyan-800 uppercase tracking-wider pt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-cubes text-cyan-600"></i> 3. หลักสูตร 7 โมดูล &amp; ฮาร์ดแวร์
            </div>
            <a href="#modules" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-cyan-600 text-xs">โครงสร้าง 7 โมดูลการเรียนรู้</a>
            <a href="#screens" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-amber-600 text-xs">11 จอแสดงผล ATD3.5-S3</a>
            <a href="#learning-resources" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-indigo-600 text-xs">10 แหล่งเรียนรู้ดิจิทัล &amp; E-Book</a>

            <hr class="border-gray-100">
            <!-- Group 4 -->
            <div class="text-[10px] font-bold text-teal-800 uppercase tracking-wider pt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-flask-vial text-teal-600"></i> 4. Virtual Lab &amp; AI
            </div>
            <a href="#simulators" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-cyan-800 font-semibold hover:text-cyan-900 text-xs">ห้องทดลองเสมือนจริง 3D AR</a>
            <a href="app/durian-leaf-app/index.html" class="block pl-4 py-1.5 text-emerald-700 hover:text-emerald-800 text-xs">Mobile Edge AI ตรวจโรคใบพืช</a>
            <a href="#iot-platform" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-teal-600 text-xs">แผงควบคุมเซ็นเซอร์สด ESP32-S3</a>
            <a href="#capstone" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-rose-600 text-xs">โครงงาน Capstone (6 Tracks)</a>

            <hr class="border-gray-100">
            <!-- Group 5 -->
            <div class="text-[10px] font-bold text-purple-800 uppercase tracking-wider pt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-layer-group text-purple-600"></i> 5. นวัตกรรม สื่อ &amp; ทีมงาน
            </div>
            <a href="poster.php" class="block pl-4 py-1.5 text-emerald-700 font-semibold hover:text-emerald-800 text-xs">📢 โปสเตอร์ประชาสัมพันธ์ (A4 / 9:16)</a>
            <a href="promo_video.php" class="block pl-4 py-1.5 text-cyan-700 font-semibold hover:text-cyan-800 text-xs">🎬 วิดีโอสปอตประชาสัมพันธ์ (Motion Video)</a>
            <a href="#documents" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-blue-600 text-xs">เอกสารและคู่มือโครงการ</a>
            <a href="#leqs-teams" onclick="toggleMobileMenu()" class="block pl-4 py-1.5 text-gray-700 hover:text-purple-600 text-xs">ทีมวิทยากร &amp; นักวิจัย (LEQs Teams)</a>
            <a href="admin/index.php" class="block pl-4 py-2 text-slate-900 font-bold hover:text-cyan-600 text-xs flex items-center gap-1.5">
                <i class="fa-solid fa-shield-halved text-cyan-600"></i> แผงควบคุมระบบ Admin CMS
            </a>
        </div>
    </nav>
