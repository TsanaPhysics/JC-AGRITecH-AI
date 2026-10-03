<?php
/**
 * LEQs-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Co-developed with Praneetwittayakhom School
 * Styled and structured following cmu_aiot Portal Design System
 */
$page_title = "LEQs-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม | RBRU 2026";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@300;400;500;600;700&family=Chakra+Petch:wght@400;500;600;700&family=Orbitron:wght@400;600;700;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS (cmu_aiot Design System) -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Custom Scripts -->
    <script src="assets/js/script.js" defer></script>
</head>
<body class="hero-bg text-gray-800 antialiased font-sans selection:bg-emerald-500 selection:text-white">

    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR (EXACTLY 5 MAIN MENUS - cmu_aiot STYLE)                 -->
    <!-- ========================================================================= -->
    <nav class="bg-white/90 backdrop-blur-md shadow-sm fixed w-full top-0 z-50 transition-all duration-300 border-b border-gray-100" id="navbar">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex justify-between items-center h-20">
                
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

                    <!-- MENU 5: นวัตกรรม & เอกสาร (Dropdown) -->
                    <div class="relative group px-2 py-2">
                        <button class="flex items-center gap-1.5 text-emerald-700 hover:text-emerald-800 font-bold transition py-1 bg-emerald-50 px-3.5 rounded-full border border-emerald-200">
                            <i class="fa-solid fa-layer-group text-emerald-600"></i> นวัตกรรม &amp; เอกสาร <i class="fa-solid fa-chevron-down text-xs ml-0.5"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden p-1.5">
                            <a href="#iot-platform" class="px-3.5 py-2.5 rounded-xl hover:bg-cyan-50 hover:text-cyan-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-mobile-screen-button text-cyan-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">Smartphone App &amp; Web Dashboard</span>
                                    <span class="text-[10px] text-gray-500">มอนิเตอร์ ควบคุม สั่งการบอร์ดออนไลน์</span>
                                </div>
                            </a>
                            <a href="#learning-resources" class="px-3.5 py-2.5 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-graduation-cap text-indigo-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">แหล่งเรียนรู้เพิ่มเติม 9 ระบบ</span>
                                    <span class="text-[10px] text-gray-500">เชื่อมโยงระบบนิเวศ cmu_aiot ทั้งหมด</span>
                                </div>
                            </a>
                            <a href="#screens" class="px-3.5 py-2.5 rounded-xl hover:bg-amber-50 hover:text-amber-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-display text-amber-500 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">จอแสดงผล ATD3.5-S3 (10 Screens)</span>
                                    <span class="text-[10px] text-gray-500">สกรีนช็อตฮาร์ดแวร์จริง 480x320</span>
                                </div>
                            </a>
                            <a href="#capstone" class="px-3.5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-diagram-project text-rose-500 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">โครงงาน Capstone (6 Tracks)</span>
                                    <span class="text-[10px] text-gray-500">นวัตกรรมเกษตรและสิ่งแวดล้อม</span>
                                </div>
                            </a>
                            <a href="#documents" class="px-3.5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <i class="fa-solid fa-file-invoice-dollar text-emerald-600 w-5"></i>
                                <div>
                                    <span class="font-bold text-xs block text-gray-800">เอกสารงบประมาณ 76,000 บ.</span>
                                    <span class="text-[10px] text-gray-500">แบบฟอร์ม มรภ.รำไพพรรณี 2569</span>
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
            <a href="#iot-platform" onclick="toggleMobileMenu()" class="block py-2 text-cyan-700 font-bold"><i class="fa-solid fa-mobile-screen-button w-6 text-cyan-600"></i> Smartphone App &amp; Web Dashboard</a>
            <a href="#modules" onclick="toggleMobileMenu()" class="block py-2 text-gray-700 hover:text-cyan-600"><i class="fa-solid fa-cubes w-6 text-cyan-500"></i> 3. หลักสูตร 7 โมดูล</a>
            <a href="#simulators" onclick="toggleMobileMenu()" class="block py-2 text-gray-700 hover:text-amber-600"><i class="fa-solid fa-flask-vial w-6 text-amber-500"></i> 4. Virtual Lab เสมือนจริง</a>
            <a href="#learning-resources" onclick="toggleMobileMenu()" class="block py-2 text-indigo-700 font-bold"><i class="fa-solid fa-graduation-cap w-6 text-indigo-600"></i> 5. แหล่งเรียนรู้เพิ่มเติม 9 ระบบ</a>
            <a href="#screens" onclick="toggleMobileMenu()" class="block py-2 text-gray-700 hover:text-amber-600"><i class="fa-solid fa-display w-6 text-amber-500"></i> จอแสดงผล ATD3.5-S3 (10 จอ)</a>
            <a href="#documents" onclick="toggleMobileMenu()" class="block py-2 text-gray-700 hover:text-emerald-600"><i class="fa-solid fa-file-invoice-dollar w-6 text-emerald-600"></i> เอกสารงบประมาณ 76,000 บ.</a>
            <hr class="border-gray-100">
            <a href="admin/index.php" class="block py-2 text-slate-900 font-bold"><i class="fa-solid fa-shield-halved w-6 text-cyan-500"></i> แผงควบคุมระบบ Admin CMS</a>
        </div>
    </nav>

    <!-- ========================================================================= -->
    <!-- 2. MAIN CONTAINER & HERO SECTION (cmu_aiot 2-COLUMN LAYOUT)               -->
    <!-- ========================================================================= -->
    <main class="container mx-auto px-4 md:px-6 pt-28 pb-16 space-y-24">

        <!-- HERO SECTION -->
        <header id="overview" class="py-10 md:py-16 relative">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                
                <!-- Hero Image Showcase (Left) - Single Unified Smart Screen Frame matching cmu_aiot -->
                <div class="relative animate-float order-last md:order-first">
                    <div class="absolute -inset-4 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 rounded-[3.5rem] opacity-25 blur-3xl"></div>
                    
                    <div id="heroSingleScreenContainer" class="relative rounded-[2.5rem] shadow-2xl border-4 border-white/90 overflow-hidden bg-white p-4 space-y-3.5 group hover:shadow-emerald-200/50 transition-all duration-700">
                        
                        <!-- Top Monitor Header Bar -->
                        <div class="flex items-center justify-between px-2 pt-1 pb-2 border-b border-gray-100 text-xs">
                            <div class="flex items-center gap-2">
                                <span id="heroCategoryDot" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                                <span id="heroCategoryName" class="font-bold text-gray-800 font-tech tracking-wider text-[11px]">Computer Vision AI</span>
                                <span id="heroSlideBadge" class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-mono text-[9px] border border-emerald-200 font-semibold">
                                    Slide 01/10
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button onclick="prevHeroSlide()" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-emerald-100 hover:text-emerald-700 text-gray-600 flex items-center justify-center transition text-xs" title="ภาพก่อนหน้า">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </button>
                                <button onclick="nextHeroSlide()" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-emerald-100 hover:text-emerald-700 text-gray-600 flex items-center justify-center transition text-xs" title="ภาพถัดไป">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </button>
                                <button onclick="openCurrentHeroSlideModal()" class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-600 flex items-center justify-center transition text-xs ml-1" title="ขยายดูภาพขนาดใหญ่">
                                    <i class="fa-solid fa-expand text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Main Screen Display (16:10 high-clarity ratio) -->
                        <div class="relative rounded-2xl overflow-hidden bg-slate-950 aspect-[16/11] flex items-center justify-center shadow-inner cursor-pointer" onclick="openCurrentHeroSlideModal()">
                            <img id="heroSingleScreenImg" 
                                 src="assets/images/cv_cover_artwork.jpg" 
                                 alt="Smart Display Screen" 
                                 class="w-full h-full object-cover transition-all duration-500 transform group-hover:scale-[1.02]">
                            
                            <!-- Status Badges Overlay -->
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-full bg-slate-900/85 backdrop-blur-md border border-emerald-400/40 text-emerald-300 text-[10px] font-tech font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-md">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>ONLINE • LIVE TELEMETRY</span>
                            </div>

                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-black/75 backdrop-blur-md text-[10px] text-cyan-300 font-mono border border-cyan-500/30 flex items-center gap-1">
                                <i class="fa-solid fa-signal text-[9px] text-cyan-400"></i>
                                <span>ESP32-S3 ATD3.5</span>
                            </div>

                            <!-- Bottom Floating Title Overlay -->
                            <div class="absolute bottom-2.5 left-2.5 right-2.5 px-3.5 py-2.5 rounded-xl bg-slate-950/85 backdrop-blur-md border border-white/10 text-white shadow-lg">
                                <div class="font-bold text-xs md:text-sm text-emerald-300 truncate" id="heroSingleScreenTitle">
                                    Computer Vision & Precision AgriTech 2026
                                </div>
                                <div class="text-[11px] text-gray-300 truncate mt-0.5" id="heroSingleScreenDesc">
                                    ระบบกล้อง AI อัจฉริยะ ตรวจจับศัตรูพืชและวิเคราะห์ความสมบูรณ์ของใบพืช
                                </div>
                            </div>
                        </div>

                        <!-- Screen Bottom Navigation Dots & Quick Switcher -->
                        <div class="flex items-center justify-between pt-1 px-1">
                            <div class="text-[10px] text-gray-400 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-arrows-rotate text-emerald-500 text-[9px]"></i>
                                <span>Auto-cycling 3.8s</span>
                            </div>
                            <div id="heroScreenDots" class="flex items-center gap-1.5">
                                <!-- Dots dynamically rendered by script.js -->
                            </div>
                            <a href="#iot-platform" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition">
                                <span>ดูระบบ App &amp; Web</span>
                                <i class="fa-solid fa-arrow-down text-[10px]"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Text Content (Right) -->
                <div class="text-left space-y-6 z-10">
                    
                    <!-- Hero Ticker Container -->
                    <div class="min-h-[56px] flex items-center overflow-hidden max-w-full">
                        <span id="hero-ticker" class="inline-block py-2 px-6 rounded-2xl bg-emerald-100/90 backdrop-blur-sm shadow-md text-sm md:text-base font-bold tracking-wide whitespace-nowrap border-l-4 border-emerald-500 transition-all duration-300">
                            🏛️ คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี x งบประมาณ 76,000 บ.
                        </span>
                    </div>

                    <!-- Heading -->
                    <h1 class="font-heading font-bold leading-tight text-shadow">
                        <span class="text-sm md:text-base text-gray-400 block mb-2 font-['Orbitron'] tracking-widest uppercase opacity-90">
                            &lt; Hands_on_Workshop /&gt;
                        </span>
                        <span class="text-4xl md:text-6xl block font-extrabold">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600">LEQs</span>
                            <span class="text-blue-600">-xAI</span>
                            <span class="text-gray-800">2026</span>
                        </span>
                        <span class="text-2xl md:text-4xl block mt-2 font-heading font-bold text-gray-900">
                            <span class="text-emerald-600">ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัล</span> 
                            <span class="text-amber-500 font-light">และสิ่งแวดล้อม</span>
                        </span>
                    </h1>

                    <p class="text-base md:text-lg text-gray-600 leading-relaxed max-w-xl">
                        โครงการอบรมเชิงปฏิบัติการพัฒนาทักษะ <span class="dynamic-color-text font-bold">Edge AI • Deep Learning • Computer Vision • Environmental IoT</span> เพื่อยกระดับนักเรียน ม.ปลาย ครู และเกษตรกรสู่นวัตกรเกษตรแม่นยำยุคใหม่ ✨
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3.5 pt-2">
                        <a href="#modules" 
                           class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-7 py-3.5 rounded-full font-bold shadow-lg hover:shadow-emerald-500/40 hover:scale-105 transition transform flex items-center gap-2">
                            <i class="fa-solid fa-cubes"></i> สำรวจ 7 โมดูล
                        </a>
                        <a href="pages/register.php" 
                           class="bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 px-6 py-3.5 rounded-full font-bold shadow-md hover:scale-105 transition transform flex items-center gap-2">
                            <i class="fa-solid fa-user-plus text-emerald-600"></i> ลงทะเบียนร่วมอบรม
                        </a>
                        <a href="#learning-resources" 
                           class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-5 py-3.5 rounded-full font-semibold shadow-sm transition flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-graduation-cap text-indigo-600"></i> แหล่งเรียนรู้ 9 ระบบ
                        </a>
                    </div>

                    <!-- Target Audience Pills -->
                    <div class="pt-2 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                        <span class="font-bold text-gray-700">กลุ่มเป้าหมาย:</span>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">นักเรียน ม.ปลาย</span>
                        <span class="px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200">ครูวิทยาศาสตร์</span>
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">เกษตรกรชาวสวน</span>
                        <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200">ผู้สนใจทั่วไป</span>
                    </div>

                </div>

            </div>

            <!-- Key Metric Statistics Bar (cmu_aiot style) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16 pt-8">
                <a href="#capstone" class="glass-card p-5 rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-2xl md:text-3xl lg:text-4xl font-black font-tech text-emerald-600 group-hover:text-emerald-500 transition">6 Tracks</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">6 แทร็กโครงงานนวัตกรรม</div>
                </a>
                <div class="glass-card p-5 rounded-3xl text-center shadow-sm hover:shadow-md transition">
                    <div class="text-3xl md:text-4xl font-black font-tech text-cyan-600">7 Modules</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">ทฤษฎี 30% + ปฏิบัติการ 70%</div>
                </div>
                <a href="#simulators" class="glass-card p-5 rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-2xl md:text-3xl font-black font-tech text-amber-600 group-hover:text-amber-500 transition">Virtual XR Lab</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">ห้องทดลองเสมือนจริง 3 มิติ</div>
                </a>
                <div class="glass-card p-5 rounded-3xl text-center shadow-sm hover:shadow-md transition">
                    <div class="text-3xl md:text-4xl font-black font-tech text-purple-600">100% Cert</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">วุฒิบัตรรับรองสมรรถนะ AIoT</div>
                </div>
            </div>
        </header>

        <!-- ========================================================================= -->
        <!-- 3. FRAMEWORK & PILLAR SECTION (L-E-Q-s-A-I CARDS & GOAL BANNER)          -->
        <!-- ========================================================================= -->
        <section id="framework" class="space-y-8 scroll-mt-24">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                    Core Learning Framework
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    เสาหลักแห่งการเรียนรู้: LEQs-xAI Framework
                </h2>
                <p class="text-gray-500 text-sm">
                    บูรณาการวิทยาศาสตร์เชิงปริมาณ ระบบประมวลผลบนขอบ และปัญญาประดิษฐ์ที่อธิบายได้
                </p>
            </div>

            <!-- 6 Framework Cards (Matching cmu_aiot C-V-A-I-O-T grid) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                
                <!-- L Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-emerald-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-emerald-500 to-teal-700 mb-2 font-tech">
                        L
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">Learning &amp; Active Pedagogy</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        การเรียนรู้เชิงรุก PBL ทำความเข้าใจจากหลักการฟิสิกส์สู่การประยุกต์
                    </p>
                </div>

                <!-- E Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-cyan-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-cyan-400 to-blue-600 mb-2 font-tech">
                        E
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">Environment &amp; Sensors</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        เซนเซอร์วัดอากาศ SHT45, แสง PAR, ดิน 7-in-1 และโพรบ RS485
                    </p>
                </div>

                <!-- Q Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-amber-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-amber-400 to-orange-600 mb-2 font-tech">
                        Q
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">Quantitative Science</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        วิทยาศาสตร์เชิงตัวเลข คำนวณค่าความดันไอ VPD, NPK และสถิติโมเดล
                    </p>
                </div>

                <!-- s Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-purple-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-purple-400 to-indigo-600 mb-2 font-tech">
                        s
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">Smart Edge Computing</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        ฮาร์ดแวร์บอร์ด ESP32-S3 ATD3.5 และการประมวลผล TinyML ออฟไลน์
                    </p>
                </div>

                <!-- A Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-rose-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-rose-400 to-red-600 mb-2 font-tech">
                        A
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">AgriTech &amp; Plant Vision</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        คอมพิวเตอร์วิทัศน์ YOLOv8 ตรวจโรคพืชและสุขภาพผลผลิตแปลงจริง
                    </p>
                </div>

                <!-- I Card -->
                <div class="glass-card p-6 rounded-3xl text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-blue-500 shadow-sm hover:shadow-lg">
                    <div class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-sky-600 mb-2 font-tech">
                        I
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-2 h-12 flex items-center justify-center">Innovation &amp; Capstone</h3>
                    <p class="text-xs text-gray-500 leading-snug">
                        สร้างโครงงาน Capstone นวัตกรรมแก้ปัญหาจริงสู่การแข่งขันระดับชาติ
                    </p>
                </div>

            </div>

            <!-- Goal Banner (Matching cmu_aiot i-SMART Goal Banner) -->
            <div class="glass-card rounded-3xl p-8 md:p-10 bg-gradient-to-r from-emerald-950 via-slate-900 to-teal-950 text-white text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-full opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative z-10 space-y-4">
                    <h3 class="text-2xl md:text-3xl font-bold text-yellow-300 font-heading">
                        Goal: The LEQs-xAI Innovator 💡
                    </h3>
                    <p class="text-emerald-100 max-w-2xl mx-auto text-sm md:text-base">
                        “จากข้อมูลสู่ปัญญา จาก AI สู่เกษตรอัจฉริยะ และจากห้องเรียนสู่ภาคสนาม” บ่มเพาะทักษะรอบด้านเพื่อแก้ปัญหาความมั่นคงทางอาหารและสิ่งแวดล้อม
                    </p>
                    
                    <div class="flex flex-wrap justify-center gap-3 pt-2">
                        <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full border border-white/20 text-xs md:text-sm font-medium hover:bg-white/20 transition cursor-default">
                            <span class="text-yellow-400 font-bold">L</span> - Learning (การเรียนรู้เชิงรุก)
                        </span>
                        <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full border border-white/20 text-xs md:text-sm font-medium hover:bg-white/20 transition cursor-default">
                            <span class="text-yellow-400 font-bold">E</span> - Environment (สิ่งแวดล้อมและสภาพอากาศ)
                        </span>
                        <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full border border-white/20 text-xs md:text-sm font-medium hover:bg-white/20 transition cursor-default">
                            <span class="text-yellow-400 font-bold">Q</span> - Quantitative (วิทยาศาสตร์เชิงปริมาณ)
                        </span>
                        <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full border border-white/20 text-xs md:text-sm font-medium hover:bg-white/20 transition cursor-default">
                            <span class="text-yellow-400 font-bold">s</span> - Smart Edge (สมองกลฝังตัวออฟไลน์)
                        </span>
                        <span class="px-4 py-2 bg-white/10 backdrop-blur rounded-full border border-white/20 text-xs md:text-sm font-medium hover:bg-white/20 transition cursor-default">
                            <span class="text-yellow-400 font-bold">xAI</span> - Explainable AI (ปัญญาประดิษฐ์ที่อธิบายได้)
                        </span>
                    </div>
                </div>
            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 4. VIRTUAL LABS & SIMULATORS (cmu_aiot LIGHT GLASS DESIGN)                 -->
        <!-- ========================================================================= -->
        <section id="simulators" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-wider">
                    Interactive Virtual Laboratories
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    ห้องปฏิบัติการจำลองเสมือนจริง (Virtual Labs)
                </h2>
                <p class="text-gray-500 text-sm">
                    ทดลองปรับค่าพารามิเตอร์สิ่งแวดล้อม และสัมผัสการตัดสินใจของ AI และสมการฟิสิกส์แบบเรียลไทม์
                </p>
            </div>

            <!-- SIMULATORS GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- SIMULATOR 1: VPD Plant Transpiration Simulator -->
                <div class="glass-card rounded-[2.5rem] p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-200">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">เครื่องคำนวณ VPD และการคายน้ำของพืช</h3>
                                    <p class="text-xs text-gray-500">SHT45 Microclimate &amp; Vapor Pressure Deficit Engine</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">Lab 1</span>
                        </div>

                        <!-- Readout Values -->
                        <div class="grid grid-cols-3 gap-3 my-6">
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">VPD (kPa)</span>
                                <span id="out-vpd-val" class="text-2xl font-black text-emerald-600 font-tech">1.10</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">SVP (kPa)</span>
                                <span id="out-svp-val" class="text-2xl font-black text-cyan-600 font-tech">4.24</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">AVP (kPa)</span>
                                <span id="out-avp-val" class="text-2xl font-black text-blue-600 font-tech">3.14</span>
                            </div>
                        </div>

                        <!-- Sliders -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>อุณหภูมิอากาศ (Air Temp)</span>
                                    <span id="val-vpd-temp" class="text-emerald-600 font-mono">30.0 °C</span>
                                </div>
                                <input type="range" id="vpd-temp" min="15" max="45" step="0.5" value="30" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>ความชื้นสัมพัทธ์ (Relative Humidity)</span>
                                    <span id="val-vpd-hum" class="text-cyan-600 font-mono">74 %</span>
                                </div>
                                <input type="range" id="vpd-humidity" min="20" max="98" step="1" value="74" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                            </div>
                        </div>

                        <!-- Advisory Card -->
                        <div id="box-vpd-advisory" class="mt-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-base mt-0.5"></i>
                            <div>
                                <div id="title-vpd-advisory" class="font-bold">สภาวะเหมาะสมสมบูรณ์แบบ (VPD 0.8 - 1.25 kPa)</div>
                                <div id="desc-vpd-advisory" class="mt-0.5 text-gray-600 leading-relaxed">การคายน้ำและการดูดซึมธาตุอาหาร NPK ดำเนินไปอย่างสมบูรณ์แบบ ทุเรียนและพืชแปลงขยายขนาดได้อย่างมีประสิทธิภาพ</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIMULATOR 2: Coastal Aquaculture DO & VFD Power Saver Simulator -->
                <div class="glass-card rounded-[2.5rem] p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-xl shadow-md shadow-sky-200">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">การจัดการออกซิเจนละลาย DO &amp; อินเวอร์เตอร์ VFD</h3>
                                    <p class="text-xs text-gray-500">Benson-Krause Saturation &amp; Affinity Power Laws</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-sky-100 text-sky-700">Lab 2</span>
                        </div>

                        <!-- Readout Values -->
                        <div class="grid grid-cols-3 gap-3 my-6">
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">DO อิ่มตัว (Sat)</span>
                                <span id="outDoSat" class="text-2xl font-black text-sky-600 font-tech">6.82</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">VFD Freq</span>
                                <span id="outVfdFreq" class="text-2xl font-black text-indigo-600 font-tech">38.0 Hz</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">ประหยัดไฟ</span>
                                <span id="outPowerSave" class="text-2xl font-black text-emerald-600 font-tech">56.1 %</span>
                            </div>
                        </div>

                        <!-- Sliders -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>อุณหภูมิน้ำ</span>
                                        <span id="valAquaTemp" class="text-sky-600 font-mono">29.0 °C</span>
                                    </div>
                                    <input type="range" id="inputAquaTemp" min="20" max="38" step="0.5" value="29" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-sky-500">
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>ความเค็มน้ำ (Salinity)</span>
                                        <span id="valAquaSal" class="text-blue-600 font-mono">25 ppt</span>
                                    </div>
                                    <input type="range" id="inputAquaSal" min="0" max="40" step="1" value="25" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>ออกซิเจนในบ่อจริง (Dissolved Oxygen)</span>
                                    <span id="valAquaDo" class="text-emerald-600 font-mono">5.20 mg/L</span>
                                </div>
                                <input type="range" id="inputAquaDo" min="1.0" max="8.0" step="0.1" value="5.2" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            </div>
                        </div>

                        <!-- Advisory Card -->
                        <div id="boxAquaAdvisory" class="mt-6 p-4 rounded-2xl bg-sky-500/10 border border-sky-500/30 text-sky-800 text-xs flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-base mt-0.5"></i>
                            <div>
                                <div id="titleAquaAdvisory" class="font-bold">ออกซิเจนสมบูรณ์แบบ (DO > 4.5 mg/L) - ประหยัดพลังงานสูงสุด</div>
                                <div id="descAquaAdvisory" class="mt-0.5 text-gray-600 leading-relaxed">ระบบ VFD ชะลอความถี่ลงเหลือ 38.0 Hz ช่วยลดกำลังไฟฟ้าได้ถึง 56.1% ลดต้นทุนค่าไฟได้หลักหมื่นบาทต่อรอบ</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SIMULATOR 3: Edge AI Plant Vision Simulator (Full Width Card) -->
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 translate-y-1/3"></div>

                <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-gray-100 gap-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-500 text-white flex items-center justify-center text-xl shadow-md shadow-purple-200">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Lab 3: คอมพิวเตอร์วิทัศน์จำแนกโรคพืชและสุขภาพใบ (Edge Vision AI)</h3>
                            <p class="text-xs text-gray-500">TinyML MobileNet / CNN On-Device Inference Simulation บนชิป ESP32-S3</p>
                        </div>
                    </div>
                    <!-- Sample Selector Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button id="pbtn-healthy" onclick="selectPlantSample('healthy')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-seedling text-emerald-500 mr-1"></i> ใบสมบูรณ์
                        </button>
                        <button id="pbtn-fungal_spot" onclick="selectPlantSample('fungal_spot')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 mr-1"></i> โรคใบจุดราสนิม
                        </button>
                        <button id="pbtn-nutrient_def" onclick="selectPlantSample('nutrient_def')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> ขาดธาตุอาหาร
                        </button>
                        <button id="pbtn-pest_damage" onclick="selectPlantSample('pest_damage')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-bug text-purple-500 mr-1"></i> เพลี้ยไฟ/ไรแดง
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-6 items-center relative z-10">
                    <!-- Image Preview with Bounding Box -->
                    <div class="md:col-span-5 relative bg-white rounded-2xl p-3 border border-gray-200 shadow-md overflow-hidden group">
                        <img src="assets/images/cv_agri_vision.jpg" alt="Agricultural Vision in Orchard" class="w-full h-64 object-cover rounded-xl">
                        <div id="diag-bbox" class="absolute top-10 left-10 w-44 h-44 border-2 border-dashed rounded-xl flex items-center justify-center transition-all duration-500">
                            <div class="absolute -top-3 left-2 px-2 py-0.5 rounded bg-slate-900 text-[10px] font-mono text-emerald-400 border border-slate-700">
                                ROI: AI-Detect
                            </div>
                        </div>
                    </div>

                    <!-- AI Diagnostic Readouts -->
                    <div class="md:col-span-7 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 id="diag-title" class="text-xl font-bold text-gray-900 font-heading">ใบพืชสุขภาพสมบูรณ์ (Healthy Leaf)</h4>
                            <span id="diag-speed" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-mono font-bold border border-emerald-200">42 ms (ESP32-S3 TinyML)</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">ผลวินิจฉัย (Predicted Class)</div>
                                <div id="diag-class" class="font-bold text-emerald-600 text-lg">Healthy - No Infection</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">ความเชื่อมั่น (Confidence)</div>
                                <div id="diag-conf" class="text-xl font-mono font-black text-gray-900">99.4%</div>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs space-y-2.5">
                            <div><strong class="text-gray-800">อาการทางสรีรวิทยา:</strong> <span id="diag-desc" class="text-gray-600">พืชสังเคราะห์แสงได้เต็มที่ คลอโรฟิลล์สม่ำเสมอ ผิวใบมันวาว ไร้ร่องรอยสปอร์เชื้อรา</span></div>
                            <hr class="border-gray-100">
                            <div><strong class="text-emerald-700">คำแนะนำการแก้ไข xAI:</strong> <span id="diag-action" class="text-gray-700 font-medium">คงการให้น้ำและธาตุอาหารตามตารางมาตรฐาน ไม่จำเป็นต้องใช้สารควบคุมศัตรูพืช</span></div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 5. ATD3.5-S3 HARDWARE & 10 SCREENS SHOWCASE                                -->
        <!-- ========================================================================= -->
        <section id="screens" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold uppercase tracking-wider">
                    Hardware &amp; Firmware Architecture
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    ฮาร์ดแวร์จริงและ 10 หน้าจอแสดงผล ATD3.5-S3
                </h2>
                <p class="text-gray-500 text-sm">
                    คอนโทรลเลอร์หน้าจอสัมผัสแบบ Capacitive Touch 3.5 นิ้ว ความละเอียด 480x320 พิกเซล ควบคุมแปลงจริง
                </p>
            </div>

            <!-- Hardware Spec & Pinout Feature -->
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 rounded-3xl overflow-hidden border border-gray-200 shadow-lg bg-white p-2">
                    <img src="assets/images/cv_hardware_setup.jpg" alt="Hardware Setup Kit" class="w-full h-auto object-cover rounded-2xl hover:scale-105 transition duration-500">
                    <div class="p-3 text-center">
                        <span class="text-xs font-bold text-gray-700">ชุดบอร์ดทดลอง ATD3.5-S3 พร้อมกล้อง OV2640 &amp; RS485 Modbus</span>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-4">
                    <div class="w-12 h-12 bg-cyan-500 rounded-2xl flex items-center justify-center text-white shadow-md shadow-cyan-200">
                        <i class="fa-solid fa-microchip text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-cyan-700 uppercase tracking-widest">Hardware Specifications</span>
                    <h3 class="text-2xl md:text-3xl font-heading font-bold text-gray-900">
                        ESP32-S3 Dual-Core Xtensa LX7 @ 240MHz
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        ผสานเซนเซอร์สภาพแวดล้อมชั้นนำ: อุณหภูมิ/ความชื้น SHT45 (I2C), แสงสังเคราะห์แสง BH1750 (PAR), โพรบวัดดิน 7-in-1 NPK/EC/pH (RS485 Modbus RTU), และรีเลย์ควบคุมวาล์วให้น้ำ 12V/24V โซลินอยด์
                    </p>

                    <!-- Code Snippet Box with Copy Button (matching cmu_aiot) -->
                    <div class="bg-white border rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="font-bold text-gray-800 text-xs flex items-center gap-2">
                                <i class="fa-solid fa-code text-cyan-600"></i> โค้ดคำนวณ VPD บนเฟิร์มแวร์ ESP32 Arduino C++
                            </span>
                            <button onclick="copyToClipboard('code-vpd')" class="text-cyan-600 hover:text-cyan-800 font-bold text-xs bg-cyan-50 px-3 py-1 rounded-full transition">
                                <i class="fa-regular fa-copy"></i> Copy
                            </button>
                        </div>
                        <pre class="bg-gray-900 text-emerald-400 p-3.5 rounded-xl text-xs font-mono overflow-x-auto" id="code-vpd">float calculateVPD(float T, float RH) {
    float SVP = 0.61078 * exp((17.27 * T) / (T + 237.3));
    float AVP = SVP * (RH / 100.0);
    return SVP - AVP; // returns VPD in kPa
}</pre>
                    </div>
                </div>
            </div>

            <!-- 10 ATD3.5-S3 Screens Grid (Clickable Lightbox) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3.5">
                
                <!-- 01 Overview -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/01_overview_dashboard.png', 'Screen 01: Overview Dashboard', 'แดชบอร์ดหลัก 4 มิติ สภาพอากาศ VPD, แสงอาทิตย์ PAR, ผิวดิน, และเขตราก 7-in-1')">
                    <img src="assets/images/atd35/01_overview_dashboard.png" alt="01 Overview" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">01. Overview</span>
                        <span class="text-[10px] text-gray-500">Main 4D Display</span>
                    </div>
                </div>

                <!-- 02 Microclimate -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/02_microclimate_vpd.png', 'Screen 02: Microclimate & VPD Zone', 'การวิเคราะห์สภาพอากาศย่อย ค่าความดันไอขาดดุล และสถานะความเสี่ยงโรครา')">
                    <img src="assets/images/atd35/02_microclimate_vpd.png" alt="02 Microclimate" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">02. Microclimate</span>
                        <span class="text-[10px] text-gray-500">VPD Safe Zone</span>
                    </div>
                </div>

                <!-- 03 Light PAR -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/03_light_solar_par.png', 'Screen 03: Light & Solar PAR', 'โฟตอนแสงสังเคราะห์แสง PAR (umol/m2/s) และการสะสมแสงรายวัน DLI')">
                    <img src="assets/images/atd35/03_light_solar_par.png" alt="03 Light PAR" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">03. Light PAR</span>
                        <span class="text-[10px] text-gray-500">BH1750 Sensor</span>
                    </div>
                </div>

                <!-- 04 Soil 7-in-1 -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/04_soil_deep_7in1.png', 'Screen 04: Soil Deep Root 7-in-1', 'การวัดแร่ธาตุ NPK, ค่าการนำไฟฟ้า EC, ความเป็นกรดด่าง pH ในเขตราก')">
                    <img src="assets/images/atd35/04_soil_deep_7in1.png" alt="04 Soil 7in1" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">04. Soil 7-in-1</span>
                        <span class="text-[10px] text-gray-500">RS485 Modbus</span>
                    </div>
                </div>

                <!-- 05 Wi-Fi QR -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/05_wifi_captive_portal.png', 'Screen 05: Wi-Fi Captive Portal', 'ระบบจับคู่การเชื่อมต่ออินเทอร์เน็ตผ่าน QR Code แบบ Zero-Configuration')">
                    <img src="assets/images/atd35/05_wifi_captive_portal.png" alt="05 Wi-Fi" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">05. Wi-Fi Portal</span>
                        <span class="text-[10px] text-gray-500">QR Code Pairing</span>
                    </div>
                </div>

                <!-- 06 SHT45 VPD -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/06_sht45_air_vpd.png', 'Screen 06: SHT45 VPD Detailed Gauge', 'เกจวัดค่า VPD เชิงลึกและการประเมินสภาวะเปิด/ปิดปากใบของพืช')">
                    <img src="assets/images/atd35/06_sht45_air_vpd.png" alt="06 SHT45" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">06. SHT45 VPD</span>
                        <span class="text-[10px] text-gray-500">Physics Engine</span>
                    </div>
                </div>

                <!-- 07 BH1750 PAR -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/07_bh1750_solar_par.png', 'Screen 07: Solar Spectrum & PAR', 'การวัดความเข้มแสงแดดและการคำนวณพลังงานแสงอาทิตย์สำหรับฟาร์ม')">
                    <img src="assets/images/atd35/07_bh1750_solar_par.png" alt="07 BH1750" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">07. Solar PAR</span>
                        <span class="text-[10px] text-gray-500">Solar Spectrum</span>
                    </div>
                </div>

                <!-- 08 Soil Stick -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/08_soil_stick_surface.png', 'Screen 08: Soil Stick Surface Moisture', 'การตรวจสอบความชื้นผิวดิน 0-10 cm เฝ้าระวังการระเหยน้ำและการแตกระแหง')">
                    <img src="assets/images/atd35/08_soil_stick_surface.png" alt="08 Soil Stick" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">08. Soil Stick</span>
                        <span class="text-[10px] text-gray-500">Surface Moisture</span>
                    </div>
                </div>

                <!-- 09 Soil 7-in-1 TinyML -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/09_soil_7in1_tinyml.png', 'Screen 09: Soil 7in1 TinyML Inference', 'ผลการทำนายความต้องการปุ๋ยและน้ำจากโมเดล TinyML ที่รันบน ESP32 โดยตรง')">
                    <img src="assets/images/atd35/09_soil_7in1_tinyml.png" alt="09 TinyML" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">09. TinyML Inference</span>
                        <span class="text-[10px] text-gray-500">On-Device xAI</span>
                    </div>
                </div>

                <!-- 10 Master Showcase -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/master_pr_showcase_poster.jpg', 'Screen 10: Master PR Showcase Poster', 'โปสเตอร์ประชาสัมพันธ์ความละเอียดสูง Ultra-HD แสดงครบทั้ง 10 หน้าจอ')">
                    <img src="assets/images/atd35/master_pr_showcase_poster.jpg" alt="10 Poster" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">10. Ultra-HD Poster</span>
                        <span class="text-[10px] text-gray-500">PR Exhibition</span>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 5.1 FROM BOARD TO SMARTPHONE APP & INTERACTIVE WEB DASHBOARD (#iot-platform)-->
        <!-- ========================================================================= -->
        <section id="iot-platform" class="scroll-mt-24 space-y-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="px-4 py-1.5 rounded-full bg-gradient-to-r from-cyan-100 to-emerald-100 text-cyan-800 text-xs font-bold uppercase tracking-wider border border-cyan-200 shadow-xs">
                    <i class="fa-solid fa-network-wired text-cyan-600 mr-1.5"></i> Full-Stack AIoT Platform Ecosystem
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 leading-tight">
                    จากบอร์ดฮาร์ดแวร์ สู่ Smartphone App<br>และ Interactive Web Dashboard
                </h2>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    สถาปัตยกรรมเชื่อมต่อครบวงจร: จากบอร์ดไมโครคอนโทรลเลอร์ ESP32-S3 ATD3.5 และโพรบวัดดิน 7-in-1 Modbus RTU สู่ออนไลน์โมบายล์แอปพลิเคชันบนสมาร์ทโฟน และเว็บแดชบอร์ดกราฟิกแบบอินเทอร์แอคทีฟ สำหรับการมอนิเตอร์ ควบคุม สั่งการระบบทางออนไลน์ได้แบบเรียลไทม์
                </p>
            </div>

            <!-- Architecture 4-Step Pipeline Flow Banner -->
            <div class="glass-card rounded-3xl p-6 md:p-8 border border-gray-100 shadow-xl bg-gradient-to-r from-slate-900 via-slate-950 to-emerald-950 text-white relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <span class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-bold flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            End-to-End IoT Data Flow &amp; Control Topology
                        </span>
                        <span class="text-[11px] text-gray-400 font-tech">Bi-Directional MQTT / WebSockets</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Step 1: Hardware Node -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-emerald-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold text-xs flex items-center justify-center font-mono">01</span>
                                <i class="fa-solid fa-microchip text-emerald-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">ESP32-S3 Hardware Board</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                โพรบวัดดิน 7-in-1 RS485 Modbus, SHT45, BH1750, ควบคุม 4-Channel Relays ในแปลง
                            </p>
                            <span class="inline-block text-[9px] font-mono text-emerald-300 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">Edge Sensing &amp; TinyML</span>
                        </div>

                        <!-- Step 2: Gateway & Broker -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-cyan-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-cyan-500/20 text-cyan-400 font-bold text-xs flex items-center justify-center font-mono">02</span>
                                <i class="fa-solid fa-cloud-arrow-up text-cyan-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">MQTT Broker &amp; Gateway</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                EMQX / Mosquitto Broker ส่งแพ็กเก็ตข้อมูล JSON แบบ Real-time ผ่าน Wi-Fi และ 4G LTE
                            </p>
                            <span class="inline-block text-[9px] font-mono text-cyan-300 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">QoS 1 • Low Latency</span>
                        </div>

                        <!-- Step 3: Smartphone App -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-indigo-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 font-bold text-xs flex items-center justify-center font-mono">03</span>
                                <i class="fa-solid fa-mobile-screen-button text-indigo-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">Smartphone Mobile App</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                แอปพลิเคชัน Flutter (iOS &amp; Android) มอนิเตอร์จากมือถือ แจ้งเตือน และสั่งเปิด-ปิดวาล์วทันใจ
                            </p>
                            <span class="inline-block text-[9px] font-mono text-indigo-300 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">Pocket Telemetry</span>
                        </div>

                        <!-- Step 4: Web Dashboard -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-amber-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center font-mono">04</span>
                                <i class="fa-solid fa-chart-line text-amber-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">Interactive Web Dashboard</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                แดชบอร์ดสรุปสถิติกราฟิก วิเคราะห์ VPD, ประวัติย้อนหลัง, และ Rule Automation ออนไลน์
                            </p>
                            <span class="inline-block text-[9px] font-mono text-amber-300 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">Cloud Analytics &amp; CSV</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 2 Core Platforms: Smartphone App vs Web Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                
                <!-- CARD 1: Smartphone App Platform -->
                <div class="glass-card rounded-[2.5rem] p-8 shadow-xl border border-gray-100 flex flex-col justify-between space-y-6 hover:shadow-2xl transition duration-500 bg-white">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                                    <i class="fa-solid fa-mobile-screen text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest font-mono">Mobile Web Application</span>
                                    <h3 class="text-2xl font-heading font-bold text-gray-900">Smartphone Mobile App</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 flex items-center gap-1.5 font-mono">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Wi-Fi Live
                            </span>
                        </div>

                        <!-- Mobile Preview Image (Sleek Real UI Mockup) -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-slate-950 group cursor-pointer" onclick="window.open('mobile/index.php', '_blank')">
                            <img src="assets/images/real_mobile_app_preview.jpg" alt="Real Smartphone App for ESP32-S3 ATD3.5" class="w-full h-60 object-cover object-center transform group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                                <span class="font-bold flex items-center gap-1.5 text-emerald-300">
                                    <i class="fa-solid fa-wifi text-emerald-400"></i> ESP32-S3 ATD3.5 Wi-Fi Connected
                                </span>
                                <span class="text-[10px] font-mono text-cyan-300 bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm border border-cyan-500/30">PWA Touch Ready</span>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <ul class="space-y-3 text-xs md:text-sm text-gray-600">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Direct Wi-Fi Telemetry:</strong> รับส่งข้อมูลสดกับบอร์ด ESP32-S3 (อุณหภูมิ, ความชื้น, แสง, ดิน 7-in-1, VPD)</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Instant Relay Control:</strong> กดเปิด-ปิดวาล์วน้ำโซลินอยด์ ปั๊มปุ๋ย และพ่นหมอกแบบเรียลไทม์ผ่านมือถือ</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>On-Device Camera Vision:</strong> สั่งจับภาพและวิเคราะห์โรคใบพืชผ่านกล้อง OV2640 บนบอร์ดได้ทันที</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Wi-Fi Config &amp; AP Pairing:</strong> รองรับการระบุ IP Address บอร์ด และเชื่อมต่อเครือข่าย Wi-Fi อย่างอิสระ</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                        <a href="mobile/index.php" target="_blank" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-5 rounded-xl shadow-md text-xs md:text-sm text-center flex items-center justify-center gap-2 transition transform hover:scale-[1.02]">
                            <i class="fa-solid fa-mobile-screen-button"></i> เปิดใช้งาน Smartphone App จริง
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                        <a href="#relay-console" class="px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-sliders"></i> ทดลองสั่งการ
                        </a>
                    </div>
                </div>

                <!-- CARD 2: Interactive Web Dashboard Platform -->
                <div class="glass-card rounded-[2.5rem] p-8 shadow-xl border border-gray-100 flex flex-col justify-between space-y-6 hover:shadow-2xl transition duration-500 bg-white">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-cyan-200">
                                    <i class="fa-solid fa-desktop text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-cyan-600 uppercase tracking-widest font-mono">Interactive Web Dashboard</span>
                                    <h3 class="text-2xl font-heading font-bold text-gray-900">Interactive Web Dashboard</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-bold border border-cyan-200 flex items-center gap-1.5 font-mono">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></span> Real-time Hub
                            </span>
                        </div>

                        <!-- Web Dashboard Preview Image (Sleek Real UI Mockup) -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-slate-950 group cursor-pointer" onclick="window.open('dashboard/index.php', '_blank')">
                            <img src="assets/images/real_web_dashboard_preview.jpg" alt="Real Interactive Web Dashboard for ESP32-S3 ATD3.5" class="w-full h-60 object-cover object-top transform group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                                <span class="font-bold flex items-center gap-1.5 text-cyan-300">
                                    <i class="fa-solid fa-chart-line text-cyan-400"></i> Chart.js 24h Trends &amp; NPK Radar
                                </span>
                                <span class="text-[10px] font-mono text-gray-300 bg-white/10 px-2 py-0.5 rounded backdrop-blur-sm border border-white/10">Full Desktop &amp; Tablet</span>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <ul class="space-y-3 text-xs md:text-sm text-gray-600">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>24-Hour Trend Analytics:</strong> กราฟเส้นแนวโน้มสภาพแวดล้อม 24 ชม. และสมดุลธาตุอาหารดิน NPK Radar Chart</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>Smart Rule Automation Engine:</strong> ตั้งกฎอัตโนมัติ 3 เงื่อนไข (รดน้ำเมื่อดินแห้ง, พ่นหมอกตามค่า VPD)</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>4-Channel Online Relay Switch:</strong> มอนิเตอร์และสั่งการเปิด-ปิดอุปกรณ์ภาคสนามพร้อมไฟแสดงสถานะ LED</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>CSV Historical Data Export:</strong> ปุ่มกดส่งออกไฟล์ CSV สถิติย้อนหลัง เพื่อนำไปวิเคราะห์ต่อด้วย Machine Learning</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                        <a href="dashboard/index.php" target="_blank" class="flex-1 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white font-bold py-3 px-5 rounded-xl shadow-md text-xs md:text-sm text-center flex items-center justify-center gap-2 transition transform hover:scale-[1.02]">
                            <i class="fa-solid fa-gauge-high"></i> เปิดใช้งาน Web Dashboard จริง
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                        <a href="dashboard/index.php" target="_blank" class="px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5" title="เปิดดูกราฟและข้อมูลสด">
                            <i class="fa-solid fa-chart-line"></i> ดูกราฟ Real-Time
                        </a>
                    </div>
                </div>

            </div>

            <!-- Interactive Online Actuator Console (ทดลองกดสั่งการบอร์ดจริงออนไลน์) -->
            <div id="relay-console" class="glass-card rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-emerald-200/80 bg-gradient-to-b from-white to-emerald-50/30 space-y-6">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200/80 pb-5">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-xs font-mono font-bold text-emerald-700 uppercase tracking-widest">LIVE HARDWARE ACTUATOR CONSOLE</span>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-gray-900">
                            คอนโซลทดลองสั่งการบอร์ดจริงออนไลน์ (Interactive Relay Control)
                        </h3>
                        <p class="text-gray-500 text-xs md:text-sm">
                            ทดลองกดสวิตช์สั่งเปิด-ปิดอุปกรณ์ภาคสนามผ่านคำสั่ง MQTT Protocol เพื่อเชื่อมโยงสู่บอร์ด ESP32-S3 ATD3.5
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-mono text-xs font-bold border border-emerald-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt text-emerald-600"></i> MQTT Status: Connected
                        </span>
                    </div>
                </div>

                <!-- 4 Interactive Relay Channels Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Relay 1 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-01 (PIN D4)</span>
                            <span id="relay-led-1" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">วาล์วน้ำโซลินอยด์ (12V)</h4>
                            <p class="text-[11px] text-gray-500">แปลงปลูกผักสลัดไฮโดรโปนิกส์</p>
                        </div>
                        <div id="relay-status-1" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-1" onclick="toggleRemoteRelay(1, 'วาล์วน้ำโซลินอยด์ 12V')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 2 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-02 (PIN D5)</span>
                            <span id="relay-led-2" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">ระบบพ่นหมอกลด VPD</h4>
                            <p class="text-[11px] text-gray-500">ควบคุมความชื้นสัมพัทธ์ในอากาศ</p>
                        </div>
                        <div id="relay-status-2" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-2" onclick="toggleRemoteRelay(2, 'ระบบพ่นหมอกลด VPD')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 3 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-03 (PIN D6)</span>
                            <span id="relay-led-3" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">ปั๊มสารละลายปุ๋ย AB</h4>
                            <p class="text-[11px] text-gray-500">ระบบปรับค่า EC อัตโนมัติ</p>
                        </div>
                        <div id="relay-status-3" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-3" onclick="toggleRemoteRelay(3, 'ปั๊มสารละลายปุ๋ย AB')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 4 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-04 (PIN D7)</span>
                            <span id="relay-led-4" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">พัดลมระบายอากาศโรงเรือน</h4>
                            <p class="text-[11px] text-gray-500">ลดความร้อนสะสมช่วงกลางวัน</p>
                        </div>
                        <div id="relay-status-4" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-4" onclick="toggleRemoteRelay(4, 'พัดลมระบายอากาศโรงเรือน')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                </div>

                <!-- Live MQTT Packet Console Stream Box -->
                <div class="bg-slate-950 rounded-2xl p-4 border border-slate-800 font-mono text-xs text-gray-300 space-y-2 shadow-inner">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-[11px]">
                        <span class="text-emerald-400 font-bold flex items-center gap-2">
                            <i class="fa-solid fa-terminal text-emerald-500"></i> MQTT TELEMETRY PACKET CONSOLE (ESP32-S3 CLIENT)
                        </span>
                        <span class="text-[10px] text-gray-500">Topic: /board/relay/+/cmd</span>
                    </div>
                    <div id="mqtt-log-console" class="max-h-28 overflow-y-auto space-y-1 text-[11px]">
                        <div class="text-[10px] font-mono text-emerald-400">
                            [System Ready] Connected to MQTT Broker tcp://localhost:1883 | ClientID: LEQs_ESP32S3_Gateway
                        </div>
                        <div class="text-[10px] font-mono text-gray-500">
                            [Subscription] Subscribed to topic /board/relay/+/cmd (QoS: 1)
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 6. 7 LEARNING MODULES CURRICULUM (cmu_aiot 9-MODULES GRID STYLE)          -->
        <!-- ========================================================================= -->
        <section id="modules" class="scroll-mt-24 space-y-8">
            
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-emerald-200/80 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-gray-100 pb-6 relative z-10">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">หลักสูตรอบรมเชิงปฏิบัติการ</span>
                            <span class="text-xs text-gray-400 font-mono">7 หน่วยการเรียนรู้ (Modules)</span>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                <i class="fa-solid fa-sparkles text-amber-500 mr-1"></i>3D Pixar Ghibli Style
                            </span>
                        </div>
                        <h2 class="text-3xl font-heading font-bold text-gray-900">
                            โครงสร้างหลักสูตร 7 Modules (Active Learning 70%)
                        </h2>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">
                            เรียนรู้ปัญญาประดิษฐ์และเซนเซอร์การเกษตรแม่นยำผ่านแนวทางสร้างสรรค์และลงมือปฏิบัติจริง
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <img src="assets/images/pixar_smart_farm_hero.jpg" alt="Active Learning 3D Pixar" class="w-24 h-16 md:w-32 md:h-20 object-cover rounded-2xl shadow-md border-2 border-white hover:scale-105 transition cursor-pointer" onclick="openScreenModal('assets/images/pixar_smart_farm_hero.jpg', 'นวัตกรรมเกษตรดิจิทัล 3D Pixar Ghibli AIoT', 'การเรียนรู้ปัญญาประดิษฐ์และเซนเซอร์การเกษตรเชิงสร้างสรรค์ ผสานหุ่นยนต์และระบบอัจฉริยะ')" title="คลิกดูภาพขยาย">
                        <a href="pages/register.php" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-user-plus"></i> สมัครเข้าร่วมอบรม
                        </a>
                    </div>
                </div>

                <!-- 7 Modules Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-6 relative z-10">
                    
                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Module 1 • 2 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">บทนำ: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</h4>
                        <p class="text-xs text-gray-500 mt-1">เข้าใจนิเวศ AI, IoT, Edge AI และความแตกต่างระหว่าง Cloud AI กับ On-Device TinyML</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded">Module 2 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Digital Agriculture: การเกษตรแม่นยำด้วยข้อมูลและเซนเซอร์</h4>
                        <p class="text-xs text-gray-500 mt-1">การอ่านโพรบ RS485 Modbus RTU, คำนวณ VPD และวิเคราะห์ความชื้นดินสองระดับ</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Module 3 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Deep Learning: เปิดกล่องดำสู่ปัญญาประดิษฐ์</h4>
                        <p class="text-xs text-gray-500 mt-1">สถาปัตยกรรม CNN, การเตรียม Dataset ภาพใบพืช และการฝึกสอนโมเดลบน Google Colab</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Module 4 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Computer Vision: สายตา AI ในแปลงเกษตร</h4>
                        <p class="text-xs text-gray-500 mt-1">OpenCV ตรวจคัดแยกสีผลไม้ และ YOLOv8 ตรวจจับโรคพืชพร้อมประเมินค่า mAP@50</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Module 5 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Edge AI: นำ AI ออกจาก Cloud สู่พื้นที่จริง</h4>
                        <p class="text-xs text-gray-500 mt-1">การควอนไทซ์โมเดล INT8, บีบอัดสู่ C++ Array และ On-Device Inference บน ESP32-S3</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">Module 6 • 2 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Environmental AI: AI นักสืบสิ่งแวดล้อม</h4>
                        <p class="text-xs text-gray-500 mt-1">วิเคราะห์สมดุลคาร์บอน แสงอาทิตย์ PAR สภาพอากาศชุมชน และการอนุรักษ์น้ำ</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1 sm:col-span-2 lg:col-span-3">
                        <span class="text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Module 7 • 3 ชม. • Capstone Showcase</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Agri-Environmental Intelligence: บูรณาการภาพ + เซนเซอร์ + IoT + AI</h4>
                        <p class="text-xs text-gray-500 mt-1">การนำเสนอโครงงาน Capstone Mini Projects การตัดสินใจเปิดน้ำใส่ปุ๋ยอัตโนมัติ และพิธีมอบเกียรติบัตร</p>
                    </div>

                </div>
            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 7. CAPSTONE MINI PROJECTS (6 TRACKS)                                      -->
        <!-- ========================================================================= -->
        <section id="capstone" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-bold uppercase tracking-wider">
                    Hands-on Capstone Innovation
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    6 แทร็กโครงงานนวัตกรรม (Capstone Tracks)
                </h2>
                <p class="text-gray-500 text-sm">
                    ผู้เข้าร่วมอบรมเลือกพัฒนาโครงงานแก้ปัญหาจริงในแปลงเกษตรของตนเอง หรือต่อยอดเป็นผลงานวิทยาศาสตร์
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Track A -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-cyan-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_a.jpg" alt="Track A Smart Agriculture IoT" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-cyan-300 font-tech font-bold text-[10px] border border-cyan-400/30">
                                TRACK A
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-cyan-600 font-mono">Precision Agriculture</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">IoT &amp; Modbus</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-cyan-600 transition">Smart Agriculture IoT</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                ระบบฟาร์มอัจฉริยะแบบครบวงจร เซนเซอร์วัดสภาพแวดล้อม ควบคุมวาล์วให้น้ำอัตโนมัติตามค่า VPD และความชื้นเขตรากพืช
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Track B -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-emerald-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_b.jpg" alt="Track B Plant Vision AI" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-emerald-300 font-tech font-bold text-[10px] border border-emerald-400/30">
                                TRACK B
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-emerald-600 font-mono">Computer Vision</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">YOLOv8 &amp; OpenCV</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-emerald-600 transition">Plant Vision AI</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                ระบบตรวจจับและวินิจฉัยโรคพืชจากภาพถ่ายใบพืช ประเมินความสมบูรณ์และเตือนภัยโรคราสนิม/แมลงศัตรูพืช
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Track C -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-amber-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_c.jpg" alt="Track C Environmental AI" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 font-tech font-bold text-[10px] border border-amber-400/30">
                                TRACK C
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-amber-600 font-mono">Environmental Station</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Solar PAR &amp; Carbon</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-amber-600 transition">Environmental AI</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                สถานีตรวจวัดสภาพอากาศชุมชน พลังงานแสงอาทิตย์ PAR อุณหภูมิ ความชื้น และการประเมินสมดุลคาร์บอน
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Track D -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-purple-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_d.jpg" alt="Track D Edge AI & TinyML" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-purple-300 font-tech font-bold text-[10px] border border-purple-400/30">
                                TRACK D
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-purple-600 font-mono">Edge Intelligence</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">ESP32-S3 TinyML</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-purple-600 transition">Edge AI &amp; TinyML</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                การบีบอัดและฝังโมเดลปัญญาประดิษฐ์ INT8 ลงบนชิปไมโครคอนโทรลเลอร์ ESP32-S3 ประมวลผลรวดเร็วแบบออฟไลน์
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Track E -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-rose-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_e.jpg" alt="Track E Aquaculture AIoT" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-rose-300 font-tech font-bold text-[10px] border border-rose-400/30">
                                TRACK E
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-rose-600 font-mono">Coastal Aquaculture</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">DO Sensor &amp; VFD</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-rose-600 transition">Aquaculture AIoT</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                ระบบฟาร์มสัตว์น้ำชายฝั่ง ตรวจวัดออกซิเจนละลาย DO ควบคุมกังหันตีน้ำ VFD อัจฉริยะ ลดต้นทุนพลังงานไฟฟ้า
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Track F -->
                <div class="glass-card p-5 rounded-3xl border-b-4 border-indigo-500 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group overflow-hidden bg-white">
                    <div class="space-y-4">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-inner">
                            <img src="assets/images/pixar_track_f.jpg" alt="Track F AI Integrated Decision System" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-slate-900/80 backdrop-blur-md text-indigo-300 font-tech font-bold text-[10px] border border-indigo-400/30">
                                TRACK F
                            </span>
                            <span class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[9px] text-white font-mono">
                                3D Pixar Art
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-indigo-600 font-mono">Integrated AI System</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded">App &amp; Cloud</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-600 transition">AI Integrated Decision System</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                ระบบตัดสินใจอัจฉริยะผสานภาพถ่าย เซนเซอร์ผิวดิน และแจ้งเตือนเกษตรกรผ่าน LINE Notify และ Smartphone App
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 8. EXTENDED LEARNING ECOSYSTEM (9 SYSTEMS LINKING TO CMU_AIOT)            -->
        <!-- ========================================================================= -->
        <section id="learning-resources" class="scroll-mt-24 space-y-8">
            
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-indigo-200/80 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-6 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-indigo-100 text-indigo-700">Extended Learning Ecosystem</span>
                            <span class="text-xs text-gray-400 font-mono">9 แหล่งเรียนรู้เพิ่มเติม</span>
                        </div>
                        <h2 class="text-3xl font-heading font-bold text-gray-900">
                            แหล่งเรียนรู้เพิ่มเติมและระบบนิเวศ AIoT ที่เกี่ยวข้อง
                        </h2>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">
                            เชื่อมต่อแพลตฟอร์ม โค้ดตัวอย่าง แดชบอร์ด และเอกสารคู่มือระดับมืออาชีพบนเซิร์ฟเวอร์ XAMPP
                        </p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                        <i class="fa-solid fa-check-circle mr-1"></i> เชื่อมต่อพร้อมใช้งาน 100%
                    </span>
                </div>

                <!-- 9 Systems Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 relative z-10">
                    
                    <!-- 1. SCIRBRU AIoT 207 -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-emerald-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-emerald-100">
                                    <i class="fa-solid fa-microchip"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-mono border border-emerald-200">scirbru_aiot207</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-emerald-600 transition">SCIRBRU AIoT 207 Platform</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                พอร์ทัล AIoT ภาคตะวันออก 2570 เชื่อมโยงสวนผลไม้มูลค่าสูงและฟาร์มสัตว์น้ำชายฝั่ง 9 โมดูล
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Track 1 &amp; Track 2</span>
                            <a href="http://localhost/cmu_aiot/scirbru_aiot207/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 2. Computer Vision -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-cyan-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-cyan-100">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 text-[10px] font-mono border border-cyan-200">computer_vision</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-cyan-600 transition">Computer Vision &amp; AI Camera</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                หลักสูตรวิทัศน์คอมพิวเตอร์ การประมวลผลภาพดิจิทัล OpenCV, YOLOv8 และคู่มือฉบับสมบูรณ์ 81 หน้า
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">OpenCV &amp; YOLOv8</span>
                            <a href="http://localhost/cmu_aiot/computer_vision/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Smart Farm Dashboard -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-amber-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-amber-100">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-mono border border-amber-200">smart_farm_dashboard</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-amber-600 transition">Smart Farm Dashboard</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                แดชบอร์ดมอนิเตอร์เซนเซอร์ฟาร์ม กราฟแสดงผลแบบเรียลไทม์ และระบบควบคุมโซลินอยด์วาล์วน้ำ
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Telemetry &amp; Control</span>
                            <a href="http://localhost/cmu_aiot/smart_farm_dashboard/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 4. คู่มือการทดลอง -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-blue-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-blue-100">
                                    <i class="fa-solid fa-book-bookmark"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-mono border border-blue-200">คู่มือ (Guides)</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition">ศูนย์รวมคู่มือการทดลองฉบับเต็ม</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                ใบงานและคู่มือ Hands-on Lab Activity 1-7 สำหรับนักเรียน ครู และเกษตรกร พร้อมโค้ดตัวอย่าง
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Activity 1 - 7 Labs</span>
                            <a href="http://localhost/cmu_aiot/คู่มือ/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 5. Zigbee System -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-purple-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-purple-100">
                                    <i class="fa-solid fa-circle-nodes"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[10px] font-mono border border-purple-200">zigbee_system</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-purple-600 transition">Zigbee Mesh Sensor Network</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                โครงข่ายเซนเซอร์ไร้สายระยะไกล Zigbee Mesh เชื่อมโยงโหนดวัดความชื้นดินและสถานีอากาศในสวนแปลงใหญ่
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Mesh Topology</span>
                            <a href="http://localhost/cmu_aiot/zigbee_system/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 6. LEQs AIoT Master -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-rose-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-rose-100">
                                    <i class="fa-solid fa-server"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-mono border border-rose-200">LEQs_AIoT</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-rose-600 transition">LEQs AIoT Master Station</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                ระบบประมวลผลกลาง LEQs Edge AI &amp; IoT Telemetry Hub สตรีมข้อมูลจากภาคสนามสู่หน้าเว็บแบบเรียลไทม์
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Central Telemetry</span>
                            <a href="http://localhost/cmu_aiot/LEQs_AIoT/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 7. Leaf AI System -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-emerald-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-emerald-100">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-mono border border-emerald-200">leaf_ai_system</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-emerald-600 transition">Leaf AI Disease Vision</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                ระบบ AI จำแนกโรคพืชและศัตรูพืช พร้อม Colab Notebooks และโมเดลจำแนกสายพันธุ์พืช
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Colab &amp; Models</span>
                            <a href="http://localhost/cmu_aiot/leaf_ai_system/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 8. IoT Training Workshop -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-teal-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-teal-100">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-teal-50 text-teal-700 text-[10px] font-mono border border-teal-200">iot_training_workshop</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-teal-600 transition">IoT Training Workshop</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                สื่อการสอนและสไลด์โมดูลการเขียนโปรแกรมไมโครคอนโทรลเลอร์ ESP32 สำหรับจัดค่ายอบรม
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Worksheets &amp; Slides</span>
                            <a href="http://localhost/cmu_aiot/iot_training_workshop/" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                เข้าสู่ระบบ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 9. LaTeX Academic Textbook -->
                    <div class="p-6 rounded-3xl bg-white/90 border border-gray-100 hover:border-purple-300 shadow-sm hover:shadow-lg transition hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition duration-300 border border-purple-100">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[10px] font-mono border border-purple-200">latex_textbook</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-purple-600 transition">LaTeX Academic Textbook</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                คลังซอร์สโค้ดตำราวิชาการระดับ Masterclass (XeLaTeX ภาษาไทย วงจร TikZ) พร้อมไฟล์ PDF 3.5 MB
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-mono">Textbook PDF 3.5 MB</span>
                            <a href="http://localhost/cmu_aiot/latex_textbook/main.pdf" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                ดาวน์โหลด PDF <i class="fa-solid fa-download text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 9. OFFICIAL PROPOSALS & BUDGET DOCUMENTS (76,000 THB)                     -->
        <!-- ========================================================================= -->
        <section id="documents" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                    Official Proposals &amp; Approvals
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    เอกสารโครงการและขออนุมัติงบประมาณ 76,000 บ.
                </h2>
                <p class="text-gray-500 text-sm">
                    จัดทำเป็นระบบตามแบบฟอร์มงบประมาณรายจ่าย ประจำปีงบประมาณ พ.ศ. 2569 มหาวิทยาลัยราชภัฏรำไพพรรณี
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Doc 1: Official Approval -->
                <div class="glass-card p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between group">
                    <div>
                        <div class="rounded-2xl overflow-hidden border border-gray-100 mb-4 bg-white p-2">
                            <img src="assets/images/ai_proposal.png" alt="AI Project Proposal" class="w-full h-36 object-contain group-hover:scale-105 transition duration-300">
                        </div>
                        <span class="text-[10px] font-mono font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded">RBRU FY2569 Proposal</span>
                        <h3 class="font-bold text-gray-800 text-base mt-2">โครงการขออนุมัติงบประมาณ 76,000 บาท</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                            เอกสารข้อเสนอโครงการฉบับสมบูรณ์ สำหรับการจัดสรรงบประมาณกิจกรรมบริการวิชาการ 2 วัน 1 คืน
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400 font-mono">Markdown / Word</span>
                        <a href="LEQs_xAI_Workshop_Approval_Request.md" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-file-lines"></i> อ่านเอกสาร
                        </a>
                    </div>
                </div>

                <!-- Doc 2: Detailed Workshop Proposal -->
                <div class="glass-card p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl mb-4 border border-cyan-100">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <span class="text-[10px] font-mono font-bold text-cyan-600 uppercase bg-cyan-50 px-2 py-0.5 rounded">Full Proposal Syllabus</span>
                        <h3 class="font-bold text-gray-800 text-base mt-2">แผนการสอนและโครงร่าง 7 โมดูลฉบับเต็ม</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                            กำหนดการรายชั่วโมง เกณฑ์การประเมินผล รูบริกส์คะแนน และหัวข้อกิจกรรมเชิงลึก
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400 font-mono">19 หน้า A4</span>
                        <a href="LEQs_xAI_Workshop_Proposal.md" target="_blank" class="px-3 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-download"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>

                <!-- Doc 3: Lab Manual & Prompt Engineering -->
                <div class="glass-card p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 border border-purple-100">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <span class="text-[10px] font-mono font-bold text-purple-600 uppercase bg-purple-50 px-2 py-0.5 rounded">Lab Manual &amp; Prompts</span>
                        <h3 class="font-bold text-gray-800 text-base mt-2">คู่มือการทดลองและชุดคำสั่ง Prompt AI</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                            สูตรการสร้าง Prompt สำหรับเทรนโมเดล โค้ดตัวอย่าง ESP32 และคู่มือการติดตั้งโปรแกรม
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400 font-mono">Quick Reference</span>
                        <a href="MANUAL_AND_PROMPT_BOOK.md" target="_blank" class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-download"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>

            </div>

            <!-- Budget Breakdown Table Card (76,000 THB) -->
            <div class="glass-card rounded-3xl p-6 md:p-8 border border-gray-100 shadow-md">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h4 class="font-bold text-gray-800 text-base">สรุปรายการงบประมาณ 76,000 บาท (จำแนกตามระเบียบ มรภ.รำไพพรรณี)</h4>
                        <p class="text-xs text-gray-500">สำหรับผู้เข้าร่วมอบรม 30 คน (นักเรียน 20 คน, ครู 5 คน, เกษตรกร 5 คน) 2 วัน 1 คืน</p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-tech font-bold text-sm">
                        Total: 76,000 THB
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="py-2.5 px-3">หมวดรายจ่าย</th>
                                <th class="py-2.5 px-3">รายการ</th>
                                <th class="py-2.5 px-3 text-right">จำนวนเงิน (บาท)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            <tr>
                                <td class="py-2.5 px-3 font-semibold">1. ค่าตอบแทน</td>
                                <td class="py-2.5 px-3">ค่าตอบแทนวิทยากรผู้เชี่ยวชาญ Edge AI &amp; IoT (12 ชม. x 600 บ.) และผู้ช่วยวิทยากร</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold">14,400</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold">2. ค่าใช้สอย</td>
                                <td class="py-2.5 px-3">ค่าอาหารกลางวัน (30 คน x 2 มื้อ x 80 บ.) และอาหารว่าง/เครื่องดื่ม (4 มื้อ x 35 บ.)</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold">9,000</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold">3. ค่าวัสดุฝึกอบรม</td>
                                <td class="py-2.5 px-3">ชุดบอร์ดทดลอง ATD3.5-S3, โพรบวัดดิน RS485 7-in-1, กล้อง AI OV2640, วาล์วน้ำโซลินอยด์</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold">45,000</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold">4. ค่าจัดพิมพ์ &amp; เกียรติบัตร</td>
                                <td class="py-2.5 px-3">เอกสารประกอบการสอนเข้าเล่ม, ป้ายโครงการ, และวุฒิบัตรเคลือบทอง</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold">7,600</td>
                            </tr>
                            <tr class="bg-emerald-50/70 font-bold text-emerald-900">
                                <td colspan="2" class="py-3 px-3 text-right font-heading">รวมงบประมาณทั้งสิ้น</td>
                                <td class="py-3 px-3 text-right font-mono text-sm text-emerald-700 font-black">76,000 บ.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </main>

    <!-- ========================================================================= -->
    <!-- 10. FOOTER (cmu_aiot CLEAN LIGHT AESTHETIC)                                -->
    <!-- ========================================================================= -->
    <footer class="bg-white/90 border-t border-gray-100 backdrop-blur-md pt-16 pb-12 text-gray-600 text-xs">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: Identity -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="assets/images/nong_smartscience.png" class="w-10 h-10 rounded-full object-cover shadow-sm ring-1 ring-emerald-200">
                        <div>
                            <div class="font-heading text-lg font-bold text-gray-900">LEQs-xAI Consortium</div>
                            <div class="text-[10px] text-gray-400">Digital Agriculture &amp; Environmental AI</div>
                        </div>
                    </div>
                    <p class="text-gray-500 leading-relaxed">
                        ศูนย์พัฒนานวัตกรรมเกษตรดิจิทัลรำไพพรรณี-ประณีตวิทยาคม (RBRU-Praneet Digital Agri-Innovation Center)
                        คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                    </p>
                    <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> สอดคล้องเป้าหมายการพัฒนาที่ยั่งยืน (SDGs 2, 4, 13)
                    </div>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-3">
                    <div class="font-bold text-gray-800 uppercase tracking-wider text-xs font-heading">ระบบและบริการ</div>
                    <ul class="space-y-2">
                        <li><a href="pages/register.php" class="hover:text-emerald-600 transition flex items-center gap-2"><i class="fa-solid fa-user-plus text-emerald-500 text-[10px]"></i> ลงทะเบียนเข้าร่วมอบรม</a></li>
                        <li><a href="pages/student_list.php" class="hover:text-purple-600 transition flex items-center gap-2"><i class="fa-solid fa-list-check text-purple-500 text-[10px]"></i> ประกาศรายชื่อผู้สมัคร</a></li>
                        <li><a href="pages/assessment_pre.php" class="hover:text-orange-600 transition flex items-center gap-2"><i class="fa-solid fa-file-pen text-orange-500 text-[10px]"></i> แบบทดสอบก่อนเรียน (Pre-test)</a></li>
                        <li><a href="pages/assessment_post.php" class="hover:text-pink-600 transition flex items-center gap-2"><i class="fa-solid fa-file-circle-check text-pink-500 text-[10px]"></i> แบบทดสอบหลังเรียน &amp; วุฒิบัตร</a></li>
                        <li><a href="admin/index.php" class="hover:text-cyan-600 transition flex items-center gap-2"><i class="fa-solid fa-shield-halved text-cyan-500 text-[10px]"></i> แผงควบคุมระบบ (Admin CMS)</a></li>
                    </ul>
                </div>

                <!-- Col 3: Coordinator & Contact -->
                <div class="space-y-3">
                    <div class="font-bold text-gray-800 uppercase tracking-wider text-xs font-heading">ผู้ประสานงานโครงการ</div>
                    <div class="space-y-1.5 text-gray-600">
                        <div class="font-bold text-gray-800">ผศ.ดร.ชีวะ ทัศนา</div>
                        <div>อาจารย์ประจำสาขาวิชาฟิสิกส์</div>
                        <div>คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี</div>
                        <div><i class="fa-solid fa-envelope text-cyan-600 mr-1.5"></i> chewa.t@rbru.ac.th</div>
                        <div><i class="fa-solid fa-phone text-emerald-600 mr-1.5"></i> 039-319-111 ต่อ 207</div>
                    </div>
                </div>

                <!-- Col 4: Location Map -->
                <div class="space-y-3">
                    <div class="font-bold text-gray-800 uppercase tracking-wider text-xs font-heading">พิกัดศูนย์การเรียนรู้</div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        โรงเรียนประณีตวิทยาคม ตำบลประณีต อำเภอเขาสมิง จังหวัดตราด 23150
                    </p>
                    <div class="rounded-2xl overflow-hidden border border-gray-200 bg-white h-28 relative shadow-sm">
                        <iframe class="w-full h-full border-0 opacity-80 hover:opacity-100 transition" 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3896.793739775082!2d102.4069678!3d12.4013697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3104683058a9da95%3A0xc48c0a87a71f021e!2z4LmC4Lij4LiH4LmA4Lij4Li14Lii4LiZ4Lib4Lij4Liw4LiT4Li14LiV4Lin4Li04Lii4Liy4LiE4Lih!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth"
                                allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

            </div>

            <!-- Bottom Sub-Footer -->
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-gray-400">
                <div>
                    © 2026 LEQs-xAI Consortium. All rights reserved. มหาวิทยาลัยราชภัฏรำไพพรรณี ร่วมกับ โรงเรียนประณีตวิทยาคม
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-400">Powered by ESP32-S3 ATD3.5 &amp; HandySense</span>
                    <a href="#overview" class="text-emerald-600 hover:text-emerald-700 font-bold"><i class="fa-solid fa-arrow-up mr-1"></i> กลับด้านบน</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- 11. FLOATING AI ASSISTANT AVATAR ("น้อง SmartScience 🤖" - cmu_aiot STYLE)  -->
    <!-- ========================================================================= -->
    <div x-data="nongSmartScience()" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        
        <!-- Chat Drawer / Modal -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-6 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-6 scale-95"
             class="mb-4 w-80 sm:w-96 rounded-3xl shadow-2xl border border-gray-100 bg-white/95 backdrop-blur-xl overflow-hidden flex flex-col z-50"
             style="height: 480px; display: none;">
            
            <!-- Chat Header -->
            <div class="p-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-white/50 overflow-hidden bg-white/20">
                        <img src="assets/images/nong_smartscience.png" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm font-heading">น้อง SmartScience 🤖</h4>
                        <div class="text-[10px] text-emerald-100 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                            AI ผู้ช่วยวิจัยเกษตรอัจฉริยะ (พร้อมตอบ 24 ชม.)
                        </div>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-white/80 hover:text-white p-1 rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Chat Messages Box -->
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 text-xs bg-slate-50/50">
                <!-- Welcome Message -->
                <div class="flex items-start gap-2.5">
                    <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                    <div class="p-3 rounded-2xl bg-white text-gray-800 shadow-sm border border-gray-100 max-w-[85%] leading-relaxed">
                        สวัสดีครับ! ผมน้อง <strong>SmartScience</strong> 🤖 ผู้ช่วยอัจฉริยะประจำโครงการ <strong>LEQs-xAI</strong> สอบถามข้อมูลหลักสูตร 7 โมดูล, โครงงาน Capstone, หรือการคำนวณเซนเซอร์ได้เลยครับ!
                    </div>
                </div>

                <!-- Suggested Quick Prompts -->
                <div class="flex flex-wrap gap-1.5 pt-1 pl-9">
                    <button @click="askQuick('หลักสูตร 7 โมดูลมีอะไรบ้าง?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-emerald-50 text-[11px] text-emerald-700 border border-emerald-200 transition shadow-xs">
                        🌱 7 โมดูลเรียนรู้อะไรบ้าง?
                    </button>
                    <button @click="askQuick('VPD คืออะไร และส่งผลต่อทุเรียนอย่างไร?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-cyan-50 text-[11px] text-cyan-700 border border-cyan-200 transition shadow-xs">
                        💨 VPD ส่งผลต่อทุเรียนอย่างไร?
                    </button>
                    <button @click="askQuick('งบประมาณ 76,000 บ. ใช้อะไรบ้าง?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-amber-50 text-[11px] text-amber-700 border border-amber-200 transition shadow-xs">
                        💰 งบประมาณ 76,000 ใช้อะไรบ้าง?
                    </button>
                </div>

                <!-- Dynamic Chat Bubbles -->
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.isUser ? 'flex justify-end' : 'flex items-start gap-2.5'">
                        <template x-if="!msg.isUser">
                            <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                        </template>
                        <div :class="msg.isUser ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-sm' : 'bg-white text-gray-800 shadow-sm border border-gray-100'"
                             class="p-3 rounded-2xl max-w-[85%] leading-relaxed"
                             x-html="msg.text">
                        </div>
                    </div>
                </template>

                <!-- Loading Bubble -->
                <div x-show="isLoading" class="flex items-start gap-2.5">
                    <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                    <div class="p-3 rounded-2xl bg-white text-gray-500 shadow-sm border border-gray-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: 0.15s"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Chat Input Form -->
            <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-gray-100 flex items-center gap-2">
                <input type="text" 
                       x-model="inputMessage" 
                       placeholder="พิมพ์คำถามที่นี่..." 
                       class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 focus:bg-white transition"
                       :disabled="isLoading">
                <button type="submit" 
                        class="w-8 h-8 rounded-full bg-gradient-to-r from-emerald-600 to-teal-500 text-white flex items-center justify-center hover:scale-105 disabled:opacity-50 transition shadow-md"
                        :disabled="!inputMessage.trim() || isLoading">
                    <i class="fa-solid fa-paper-plane text-[11px]"></i>
                </button>
            </form>
        </div>

        <!-- Floating Avatar Button (cmu_aiot style) -->
        <button @click="isOpen = !isOpen" 
                class="group relative w-16 h-16 md:w-20 md:h-20 focus:outline-none transform hover:scale-110 transition duration-300">
            <div class="absolute inset-0 animate-bounce-slow rounded-full bg-gradient-to-tr from-emerald-100 to-cyan-100 border-4 border-white shadow-2xl overflow-hidden p-1">
                <img src="assets/images/nong_smartscience.png" 
                     alt="Nong SmartScience" 
                     class="w-full h-full object-cover">
            </div>
            
            <!-- Notification Badge -->
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-ping"></span>
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
            
            <!-- Tooltip -->
            <span class="absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-xl text-xs font-bold text-gray-800 whitespace-nowrap opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none border border-gray-100">
                ถามน้อง SmartScience สิ! 🤖
                <span class="absolute right-[-6px] top-1/2 -translate-y-1/2 w-3 h-3 bg-white transform rotate-45 border-r border-t border-gray-100"></span>
            </span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 12. SCREEN LIGHTBOX MODAL                                                 -->
    <!-- ========================================================================= -->
    <div id="screen-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="relative max-w-4xl w-full bg-white rounded-3xl p-6 shadow-2xl border border-gray-100">
            <button onclick="closeScreenModal()" class="absolute top-5 right-5 w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-800 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="space-y-4">
                <div>
                    <h3 id="modal-screen-title" class="text-xl font-bold text-gray-900 font-heading"></h3>
                    <p id="modal-screen-desc" class="text-xs text-gray-500 mt-1"></p>
                </div>
                <div class="rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 p-2">
                    <img id="modal-screen-img" src="" alt="Screen Preview" class="w-full h-auto rounded-xl max-h-[70vh] object-contain mx-auto shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Alpine.js Helper for Nong SmartScience -->
    <script>
        function nongSmartScience() {
            return {
                isOpen: false,
                isLoading: false,
                inputMessage: '',
                messages: [],
                
                askQuick(text) {
                    this.inputMessage = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    if (!this.inputMessage.trim()) return;
                    const userText = this.inputMessage;
                    this.messages.push({ id: Date.now(), text: userText, isUser: true });
                    this.inputMessage = '';
                    this.isLoading = true;
                    this.scrollToBottom();

                    try {
                        // Simulated intelligent response for LEQs-xAI knowledge base
                        await new Promise(r => setTimeout(r, 800));
                        let replyText = '';
                        const q = userText.toLowerCase();

                        if (q.includes('โมดูล') || q.includes('module') || q.includes('หลักสูตร')) {
                            replyText = 'หลักสูตร LEQs-xAI มี <strong>7 โมดูลเข้มข้น</strong> ครอบคลุม: <br>1. AIoT Overview<br>2. Precision Sensors &amp; VPD<br>3. Deep Learning &amp; CNN<br>4. Computer Vision (YOLOv8)<br>5. Edge AI TinyML บน ESP32-S3<br>6. Environmental AI<br>7. Capstone Mini Projects ครับ!';
                        } else if (q.includes('vpd')) {
                            replyText = '<strong>VPD (Vapor Pressure Deficit)</strong> คือความดันไอขาดดุลของอากาศครับ สภาวะที่เหมาะสมสำหรับทุเรียนคือ <strong>0.8 - 1.25 kPa</strong> ถ้าต่ำกว่า 0.4 kPa พืชไม่คายน้ำและเสี่ยงโรครา แต่ถ้าเกิน 1.60 kPa ปากใบจะปิดสนิทและเสี่ยงสลัดผลอ่อนครับ';
                        } else if (q.includes('งบประมาณ') || q.includes('76,000') || q.includes('เงิน')) {
                            replyText = 'งบประมาณ <strong>76,000 บาท</strong> ได้รับการสนับสนุนจาก มรภ.รำไพพรรณี แบ่งเป็น:<br>• ค่าวัสดุชุดทดลองและเซนเซอร์: 45,000 บ.<br>• ค่าตอบแทนวิทยากร: 14,400 บ.<br>• ค่าอาหารและเครื่องดื่ม: 9,000 บ.<br>• ค่าจัดพิมพ์และเกียรติบัตร: 7,600 บ. ครับ';
                        } else if (q.includes('สมัคร') || q.includes('ลงทะเบียน')) {
                            replyText = 'สามารถลงทะเบียนเข้าร่วมอบรมได้ทันทีที่ปุ่ม <strong><a href="pages/register.php" class="text-emerald-600 underline">ลงทะเบียนเข้าร่วมอบรม</a></strong> รับจำนวนจำกัด 30 ท่าน (นักเรียน ครู และเกษตรกร) ฟรีตลอดหลักสูตรครับ!';
                        } else {
                            replyText = 'ยินดีต้อนรับสู่โครงการ LEQs-xAI ครับ! หากต้องการสอบถามข้อมูลเฉพาะด้าน สามารถติดต่อ ผศ.ดร.ชีวะ ทัศนา ทางอีเมล <a href="mailto:chewa.t@rbru.ac.th" class="text-emerald-600 underline">chewa.t@rbru.ac.th</a> หรือโทร 039-319-111 ต่อ 207 ได้ตลอดเวลาครับ 😊';
                        }

                        this.messages.push({ id: Date.now() + 1, text: replyText, isUser: false });
                    } catch (err) {
                        this.messages.push({ id: Date.now() + 1, text: 'ขออภัยครับ ระบบขัดข้องชั่วคราว', isUser: false });
                    } finally {
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-messages');
                        if (box) box.scrollTop = box.scrollHeight;
                    });
                }
            };
        }
    </script>

</body>
</html>
