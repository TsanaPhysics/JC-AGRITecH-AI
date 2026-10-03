<?php
/**
 * LEQs-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
 * Faculty of Science and Technology, Rambhai Barni Rajabhat University
 * Prototype based on http://localhost/cmu_aiot/index.php
 */
$page_title = "LEQs-xAI | ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม";
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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Custom Scripts -->
    <script src="assets/js/script.js" defer></script>
</head>
<body class="bg-slate-950 text-slate-100 antialiased font-sans selection:bg-cyan-500 selection:text-white">

    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR -->
    <!-- ========================================================================= -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-slate-900/80 backdrop-blur-xl border-b border-slate-800" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo & Title -->
                <a href="index.php" class="flex items-center gap-3.5 group">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500 rounded-2xl blur-md opacity-40 group-hover:opacity-80 transition duration-500 animate-pulse-ring"></div>
                        <div class="relative w-full h-full bg-slate-900 rounded-2xl border border-cyan-500/40 flex items-center justify-center p-2 shadow-inner">
                            <i class="fa-solid fa-microchip text-2xl text-cyan-400 group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full border-2 border-slate-900 animate-ping"></span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black tracking-tight font-tech bg-gradient-to-r from-emerald-400 via-cyan-400 to-sky-400 bg-clip-text text-transparent">
                                LEQs-xAI
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                                RBRU 2026
                            </span>
                        </div>
                        <span class="text-xs text-slate-400 font-medium tracking-wide">
                            ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <div class="hidden lg:flex items-center gap-1.5 font-medium text-sm text-slate-300">
                    <a href="#overview" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-compass text-cyan-400"></i> ภาพรวม
                    </a>
                    
                    <!-- Dropdown: Student System -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-user-graduate text-emerald-400"></i> ระบบผู้เรียน <i class="fa-solid fa-chevron-down text-xs ml-0.5 opacity-60"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute left-0 mt-2 w-64 bg-slate-900/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-slate-800 p-2 z-50">
                            <a href="pages/register.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-cyan-300 transition">
                                <i class="fa-solid fa-id-card text-emerald-400 w-5"></i>
                                <div>
                                    <div class="font-semibold text-sm">ลงทะเบียนเข้าอบรม</div>
                                    <div class="text-[11px] text-slate-400">สำหรับนักเรียน ครู และเกษตรกร</div>
                                </div>
                            </a>
                            <a href="pages/student_list.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-cyan-300 transition">
                                <i class="fa-solid fa-clipboard-list text-cyan-400 w-5"></i>
                                <div>
                                    <div class="font-semibold text-sm">ประกาศรายชื่อผู้สมัคร</div>
                                    <div class="text-[11px] text-slate-400">ตรวจสอบสถานะและกลุ่มโครงงาน</div>
                                </div>
                            </a>
                            <div class="my-1 border-t border-slate-800"></div>
                            <a href="pages/assessment_pre.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-cyan-300 transition">
                                <i class="fa-solid fa-pen-ruler text-amber-400 w-5"></i>
                                <div>
                                    <div class="font-semibold text-sm">แบบทดสอบก่อนเรียน (Pre-test)</div>
                                    <div class="text-[11px] text-slate-400">วัดความรู้ก่อนเริ่ม 7 Modules</div>
                                </div>
                            </a>
                            <a href="pages/assessment_post.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 hover:text-cyan-300 transition">
                                <i class="fa-solid fa-award text-purple-400 w-5"></i>
                                <div>
                                    <div class="font-semibold text-sm">Post-test & วุฒิบัตร E-Cert</div>
                                    <div class="text-[11px] text-slate-400">รับเกียรติบัตรรับรองทักษะ AIoT</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <a href="#modules" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-book-bookmark text-sky-400"></i> 7 โมดูล
                    </a>
                    <a href="#simulators" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-flask-vial text-purple-400"></i> Virtual Lab
                    </a>
                    <a href="#screens" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-desktop text-amber-400"></i> จอ ATD3.5-S3
                    </a>
                    <a href="#capstone" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-diagram-project text-rose-400"></i> โครงงาน
                    </a>
                    <a href="#documents" class="px-3 py-2 rounded-lg hover:text-cyan-400 hover:bg-slate-800/60 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf text-emerald-400"></i> เอกสารขออนุมัติ
                    </a>
                </div>

                <!-- Admin Action Button -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="admin/index.php" class="px-4 py-2 rounded-xl bg-gradient-to-r from-slate-800 to-slate-900 border border-slate-700/80 hover:border-cyan-500/50 text-cyan-300 font-semibold text-xs tracking-wider uppercase transition-all shadow-md hover:shadow-cyan-500/20 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-cyan-400"></i> Admin CMS
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden" x-data="{ mobileNav: false }">
                    <button @click="mobileNav = !mobileNav" class="p-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <!-- Mobile Menu Overlay -->
                    <div x-show="mobileNav" @click.outside="mobileNav = false" class="absolute top-20 left-0 w-full bg-slate-900/98 border-b border-slate-800 p-5 shadow-2xl flex flex-col gap-3 z-50">
                        <a href="#overview" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-slate-800">ภาพรวมโครงการ</a>
                        <a href="pages/register.php" class="py-2 px-3 rounded-lg hover:bg-slate-800 text-emerald-400">ลงทะเบียนเข้าอบรม</a>
                        <a href="pages/student_list.php" class="py-2 px-3 rounded-lg hover:bg-slate-800 text-cyan-400">ประกาศรายชื่อผู้สมัคร</a>
                        <a href="#modules" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-slate-800">หลักสูตร 7 โมดูล</a>
                        <a href="#simulators" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-slate-800">Virtual Lab เสมือนจริง</a>
                        <a href="#screens" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-slate-800">แกลเลอรีหน้าจอ ATD3.5-S3</a>
                        <a href="#documents" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-slate-800">เอกสารโครงการ</a>
                        <a href="admin/index.php" class="py-2 px-3 rounded-lg bg-slate-800 text-cyan-300 text-center font-bold">เข้าสู่ระบบ Admin</a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- ========================================================================= -->
    <!-- 2. HERO SECTION -->
    <!-- ========================================================================= -->
    <section id="overview" class="relative pt-32 pb-20 overflow-hidden hero-pattern">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Column: Copy & Taglines -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/80 text-xs font-semibold text-slate-300 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        โครงการความร่วมมือ มรภ.รำไพพรรณี & โรงเรียนประณีตวิทยาคม
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-tech tracking-tight leading-none text-white">
                        LEQs<span class="text-cyan-400">-</span><span class="bg-gradient-to-r from-emerald-400 via-cyan-400 to-sky-400 bg-clip-text text-transparent">xAI</span>
                    </h1>

                    <h2 class="text-xl sm:text-2xl font-bold text-slate-200">
                        ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัล วิทยาศาสตร์ และสิ่งแวดล้อม
                    </h2>

                    <p class="text-base sm:text-lg text-slate-400 max-w-2xl leading-relaxed mx-auto lg:mx-0">
                        <span class="text-cyan-300 font-semibold">“จากข้อมูลสู่ปัญญา จาก AI สู่เกษตรอัจฉริยะ และจากห้องเรียนสู่ภาคสนาม”</span><br>
                        บูรณาการ 6 เสาหลักเทคโนโลยี: <b class="text-slate-200">Digital Agriculture • Deep Learning • Edge AI • IoT • Computer Vision • Environmental AI</b> มุ่งเน้นการลงมือปฏิบัติจริง 70%
                    </p>

                    <!-- Core Badges Grid -->
                    <div class="flex flex-wrap gap-2 justify-center lg:justify-start pt-2">
                        <span class="badge-tech bg-emerald-950/60 text-emerald-300 border border-emerald-800/50">
                            <i class="fa-solid fa-leaf"></i> Digital Agriculture
                        </span>
                        <span class="badge-tech bg-cyan-950/60 text-cyan-300 border border-cyan-800/50">
                            <i class="fa-solid fa-brain"></i> Deep Learning (CNN)
                        </span>
                        <span class="badge-tech bg-sky-950/60 text-sky-300 border border-sky-800/50">
                            <i class="fa-solid fa-microchip"></i> Edge AI (ESP32-S3)
                        </span>
                        <span class="badge-tech bg-amber-950/60 text-amber-300 border border-amber-800/50">
                            <i class="fa-solid fa-eye"></i> Computer Vision
                        </span>
                        <span class="badge-tech bg-purple-950/60 text-purple-300 border border-purple-800/50">
                            <i class="fa-solid fa-network-wired"></i> Modbus RS485 IoT
                        </span>
                        <span class="badge-tech bg-rose-950/60 text-rose-300 border border-rose-800/50">
                            <i class="fa-solid fa-cloud-sun-rain"></i> Environmental AI (VPD/PAR)
                        </span>
                    </div>

                    <!-- Call-to-Action Buttons -->
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start pt-4">
                        <a href="pages/register.php" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-sky-500 text-slate-950 font-bold text-sm shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 hover:scale-105 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> สมัครเข้าร่วมอบรม (ฟรี)
                        </a>
                        <a href="#simulators" class="px-6 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-slate-200 font-semibold text-sm hover:border-cyan-400 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-cyan-400"></i> ทดลอง Virtual Lab เสมือนจริง
                        </a>
                        <a href="#documents" class="px-5 py-3.5 rounded-2xl bg-slate-900 border border-slate-800 text-slate-300 font-medium text-sm hover:text-white transition flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-amber-400"></i> เอกสารงบ 76,000 บ.
                        </a>
                    </div>
                </div>

                <!-- Right Column: Interactive Hardware Preview Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <!-- Neon Glow Frame -->
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-cyan-500 via-emerald-500 to-purple-500 rounded-3xl blur-xl opacity-40 animate-pulse-slow"></div>
                        
                        <!-- Device Mockup Card -->
                        <div class="relative glass-card-dark rounded-3xl p-5 border border-slate-700/80 shadow-2xl">
                            <!-- Screen Top Status -->
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                    <span class="font-mono text-cyan-400 font-bold">ATD3.5-S3 ONLINE</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 font-mono text-[10px]">
                                    ESP32-S3 Dual-Core
                                </span>
                            </div>

                            <!-- Image of Overview Screen -->
                            <div class="mt-4 rounded-2xl overflow-hidden border border-slate-700/50 shadow-inner group relative cursor-pointer" onclick="openScreenModal('assets/images/atd35/01_overview_dashboard.png', 'Overview Dashboard (จอที่ 1)', 'แดชบอร์ดหลัก 4 มิติ ตรวจวัดสภาพอากาศ VPD, แสงอาทิตย์ PAR, ผิวดิน และเขตราก 7-in-1 แบบเรียลไทม์ 60 FPS')">
                                <img src="assets/images/atd35/01_overview_dashboard.png" alt="ATD3.5-S3 Overview Dashboard" class="w-full h-auto object-cover transform group-hover:scale-105 transition-all duration-500">
                                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-4 py-2 rounded-xl bg-slate-900/90 text-cyan-300 font-bold text-xs border border-cyan-500/40 shadow-xl">
                                        <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> คลิกเพื่อดูภาพขยาย
                                    </span>
                                </div>
                            </div>

                            <!-- Hardware Specs Row -->
                            <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                                <div class="p-2.5 rounded-xl bg-slate-900/70 border border-slate-800">
                                    <div class="text-[10px] text-slate-400 uppercase font-mono">LCD Touch</div>
                                    <div class="text-xs font-bold text-cyan-400">3.5" IPS 480x320</div>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-900/70 border border-slate-800">
                                    <div class="text-[10px] text-slate-400 uppercase font-mono">Sensors</div>
                                    <div class="text-xs font-bold text-emerald-400">4 Probes (10 Pars)</div>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-900/70 border border-slate-800">
                                    <div class="text-[10px] text-slate-400 uppercase font-mono">Inference</div>
                                    <div class="text-xs font-bold text-purple-400">Edge TinyML</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16 pt-10 border-t border-slate-800/80">
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center">
                    <div class="text-3xl font-black font-tech text-cyan-400">4 กลุ่ม</div>
                    <div class="text-xs text-slate-400 mt-1">มัธยมปลาย • ครู • เกษตรกร • ผู้สนใจ</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center">
                    <div class="text-3xl font-black font-tech text-emerald-400">7 Modules</div>
                    <div class="text-xs text-slate-400 mt-1">ทฤษฎี 30% + ปฏิบัติการเข้มข้น 70%</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center">
                    <div class="text-3xl font-black font-tech text-purple-400">6 Tracks</div>
                    <div class="text-xs text-slate-400 mt-1">โครงงาน Capstone Mini Projects</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center">
                    <div class="text-3xl font-black font-tech text-amber-400">10 หน้าจอ</div>
                    <div class="text-xs text-slate-400 mt-1">UI คอนโทรลเลอร์ฮาร์ดแวร์จริง</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. LEQs FRAMEWORK & CORE OPERATING LOOP -->
    <!-- ========================================================================= -->
    <section class="py-20 bg-slate-900/40 relative border-t border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase tracking-wider">
                    Core Operating Loop & Learning Model
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    กระบวนการเรียนรู้และวงจรทำงาน LEQs-xAI
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    เปลี่ยนข้อมูลกายภาพจากสวนผลไม้และสิ่งแวดล้อม สู่การตัดสินใจที่โปร่งใสและอธิบายได้ด้วย Edge AI
                </p>
            </div>

            <!-- Core Operating Loop (SEE -> SENSE -> LEARN -> THINK -> ACT) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-16">
                <!-- SEE -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-2xl group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <div class="mt-4 font-bold text-lg text-white font-tech">1. SEE</div>
                    <div class="text-xs font-semibold text-cyan-400 uppercase tracking-wider">Computer Vision</div>
                    <p class="text-xs text-slate-400 mt-2">
                        กล้องถ่ายภาพใบพืช ผิวดิน และผลผลิต เพื่อจำแนกความผิดปกติด้วยสายตาคอมพิวเตอร์
                    </p>
                </div>

                <!-- SENSE -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 hover:border-emerald-500/50 transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                    <div class="mt-4 font-bold text-lg text-white font-tech">2. SENSE</div>
                    <div class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">IoT & Sensors</div>
                    <p class="text-xs text-slate-400 mt-2">
                        เซนเซอร์ 4 ชนิด ตรวจวัดความชื้นดิน 2 ระดับ, อุณหภูมิ, ความชื้น, แสง PAR, pH, EC และ NPK
                    </p>
                </div>

                <!-- LEARN -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 hover:border-purple-500/50 transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-2xl group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <div class="mt-4 font-bold text-lg text-white font-tech">3. LEARN</div>
                    <div class="text-xs font-semibold text-purple-400 uppercase tracking-wider">Deep Learning</div>
                    <p class="text-xs text-slate-400 mt-2">
                        ฝึกสอนโครงข่ายประสาทเทียม CNN และอัลกอริทึมพยากรณ์ความเสี่ยงโรคพืชและสภาพอากาศ
                    </p>
                </div>

                <!-- THINK -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 hover:border-amber-500/50 transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <div class="mt-4 font-bold text-lg text-white font-tech">4. THINK</div>
                    <div class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Edge AI (xAI)</div>
                    <p class="text-xs text-slate-400 mt-2">
                        ประมวลผลบนชิป ESP32-S3 ทันทีโดยไม่ง้อเน็ต พร้อมสรุปเหตุผลการตัดสินใจที่อธิบายได้
                    </p>
                </div>

                <!-- ACT -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 hover:border-rose-500/50 transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center text-2xl group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-faucet-drip"></i>
                    </div>
                    <div class="mt-4 font-bold text-lg text-white font-tech">5. ACT</div>
                    <div class="text-xs font-semibold text-rose-400 uppercase tracking-wider">Smart Actuation</div>
                    <p class="text-xs text-slate-400 mt-2">
                        สั่งการรีเลย์เปิด/ปิดปั๊มน้ำ โซลินอยด์วาล์ว พัดลมระบายอากาศ และแจ้งเตือนเกษตรกร
                    </p>
                </div>
            </div>

            <!-- LEQs Model Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800">
                    <div class="text-4xl font-black text-cyan-400 font-tech">L</div>
                    <div class="text-base font-bold text-white mt-1">Learn (เรียนรู้)</div>
                    <p class="text-xs text-slate-400 mt-2">
                        ทำความเข้าใจแนวคิดพื้นฐาน Machine Learning, Deep Learning, และเทคโนโลยีเกษตรอัจฉริยะจากหลักการทางฟิสิกส์
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800">
                    <div class="text-4xl font-black text-emerald-400 font-tech">E</div>
                    <div class="text-base font-bold text-white mt-1">Explore (สำรวจ)</div>
                    <p class="text-xs text-slate-400 mt-2">
                        ลงพื้นที่แปลงเกษตรจริง เก็บตัวอย่างภาพใบพืช สภาพดิน อากาศ และรวบรวมดาต้าเซ็ตเพื่อเตรียมพร้อมสำหรับโมเดล
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800">
                    <div class="text-4xl font-black text-amber-400 font-tech">Q</div>
                    <div class="text-base font-bold text-white mt-1">Quantify (วัดปริมาณ)</div>
                    <p class="text-xs text-slate-400 mt-2">
                        วัดเชิงตัวเลขทางวิทยาศาสตร์ด้วยโพรบเซนเซอร์ RS485 Modbus RTU คำนวณค่า VPD, PAR, EC, pH อย่างแม่นยำ
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800">
                    <div class="text-4xl font-black text-purple-400 font-tech">xAI</div>
                    <div class="text-base font-bold text-white mt-1">Build & Deploy</div>
                    <p class="text-xs text-slate-400 mt-2">
                        สร้างและบีบอัดโมเดล TinyML สู่ C++ Array ติดตั้งบนบอร์ดจริง ควบคุมแปลงเกษตรด้วยปัญญาประดิษฐ์ที่อธิบายได้
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. INTERACTIVE VIRTUAL LABS & SIMULATORS -->
    <!-- ========================================================================= -->
    <section id="simulators" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20 text-xs font-bold uppercase tracking-wider">
                    Interactive Virtual Laboratories
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    ห้องปฏิบัติการจำลองเสมือนจริง (Virtual Labs)
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    ทดลองปรับค่าพารามิเตอร์สภาพแวดล้อม และสัมผัสการตัดสินใจของปัญญาประดิษฐ์แบบเรียลไทม์ได้ทันทีบนเว็บเบราว์เซอร์
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- LAB 1: VPD & Fungal Disease Risk Simulator -->
                <div class="p-7 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-cloud-sun"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-base">Lab 1: เครื่องคำนวณ VPD และเตือนโรครา</h3>
                                    <p class="text-xs text-slate-400">SHT45 Microclimate & Vapor Pressure Deficit Engine</p>
                                </div>
                            </div>
                            <span id="vpd-status-badge" class="px-3 py-1 rounded-full text-xs font-bold uppercase"></span>
                        </div>

                        <!-- VPD Big Display -->
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 text-center">
                                <div class="text-xs text-slate-400">ค่า VPD (ความดันไอขาดดุล)</div>
                                <div class="text-4xl font-black font-tech text-cyan-400 mt-1">
                                    <span id="vpd-kpa-display">1.10</span> <span class="text-base text-slate-400 font-sans">kPa</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 text-center">
                                <div class="text-xs text-slate-400">จุดน้ำค้าง (Dew Point)</div>
                                <div class="text-4xl font-black font-tech text-emerald-400 mt-1">
                                    <span id="vpd-dew-display">21.5 °C</span>
                                </div>
                            </div>
                        </div>

                        <!-- Gauge Bar -->
                        <div class="space-y-1.5 mb-6">
                            <div class="flex justify-between text-xs text-slate-400">
                                <span>โรคราสูง (0.0 kPa)</span>
                                <span>โซนสมบูรณ์ (0.8 - 1.25)</span>
                                <span>แห้งแล้งจัด (2.0+ kPa)</span>
                            </div>
                            <div class="w-full h-3 bg-slate-950 rounded-full p-0.5 border border-slate-800">
                                <div id="vpd-gauge-fill" class="h-full rounded-full transition-all duration-300"></div>
                            </div>
                        </div>

                        <!-- Interactive Sliders -->
                        <div class="space-y-5">
                            <!-- Temp Slider -->
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-300"><i class="fa-solid fa-temperature-half text-rose-400 mr-1"></i> อุณหภูมิอากาศ (Air Temp)</span>
                                    <span id="vpd-temp-val" class="font-mono text-cyan-400 font-bold">29.0 °C</span>
                                </div>
                                <input type="range" id="vpd-temp-slider" min="15" max="45" step="0.5" value="29" 
                                       oninput="updateVPDSimulator()" 
                                       class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
                            </div>

                            <!-- RH Slider -->
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-300"><i class="fa-solid fa-droplet text-sky-400 mr-1"></i> ความชื้นสัมพัทธ์ (Relative Humidity)</span>
                                    <span id="vpd-rh-val" class="font-mono text-cyan-400 font-bold">75 %</span>
                                </div>
                                <input type="range" id="vpd-rh-slider" min="20" max="100" step="1" value="75" 
                                       oninput="updateVPDSimulator()" 
                                       class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-sky-400">
                            </div>
                        </div>
                    </div>

                    <!-- xAI Explanation Box -->
                    <div class="mt-6 p-4 rounded-2xl bg-slate-950/80 border border-slate-800 text-xs leading-relaxed text-slate-300">
                        <div id="vpd-recommendation-text"></div>
                    </div>
                </div>

                <!-- LAB 2: Dual-Depth Soil Irrigation Simulator -->
                <div class="p-7 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-base">Lab 2: การให้น้ำแม่นยำดิน 2 ระดับความลึก</h3>
                                    <p class="text-xs text-slate-400">Soil Stick (0-10 cm) & Soil 7-in-1 (10-30 cm) RS485</p>
                                </div>
                            </div>
                            <div id="pump-relay-indicator"></div>
                        </div>

                        <!-- Dual Bars Representation -->
                        <div class="space-y-4 my-6">
                            <!-- Surface Soil -->
                            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800">
                                <div class="flex justify-between items-center text-xs font-semibold mb-2">
                                    <span class="text-slate-300">ผิวดิน 0–10 cm (Soil Stick)</span>
                                    <span id="soil-surf-val" class="font-mono text-amber-400 font-bold">35 %</span>
                                </div>
                                <div class="w-full h-3 bg-slate-900 rounded-full p-0.5 border border-slate-800">
                                    <div id="soil-surf-bar" class="h-full rounded-full bg-gradient-to-r from-amber-500 to-emerald-400 transition-all duration-300"></div>
                                </div>
                            </div>

                            <!-- Deep Soil -->
                            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800">
                                <div class="flex justify-between items-center text-xs font-semibold mb-2">
                                    <span class="text-slate-300">เขตรากดูดซึม 10–30 cm (Soil 7-in-1)</span>
                                    <span id="soil-deep-val" class="font-mono text-cyan-400 font-bold">48 %</span>
                                </div>
                                <div class="w-full h-3 bg-slate-900 rounded-full p-0.5 border border-slate-800">
                                    <div id="soil-deep-bar" class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Sliders -->
                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-300">ปรับความชื้นผิวดิน (0-10 cm)</span>
                                </div>
                                <input type="range" id="soil-surf-slider" min="5" max="95" step="1" value="35" 
                                       oninput="updateSoilSimulator()" 
                                       class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-amber-400">
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-300">ปรับความชื้นเขตราก (10-30 cm)</span>
                                </div>
                                <input type="range" id="soil-deep-slider" min="5" max="95" step="1" value="48" 
                                       oninput="updateSoilSimulator()" 
                                       class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
                            </div>
                        </div>
                    </div>

                    <!-- Decision Logic Display -->
                    <div class="mt-6 p-4 rounded-2xl bg-slate-950/80 border border-slate-800 text-xs leading-relaxed text-slate-300">
                        <div id="soil-decision-text"></div>
                    </div>
                </div>

            </div>

            <!-- LAB 3: Edge AI Plant Vision Simulator -->
            <div class="mt-8 p-7 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-800 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base">Lab 3: คอมพิวเตอร์วิทัศน์จำแนกโรคพืชและสุขภาพใบ (Edge Vision AI)</h3>
                            <p class="text-xs text-slate-400">TinyML MobileNet / CNN On-Device Inference Simulation (ESP32-S3)</p>
                        </div>
                    </div>
                    <!-- Sample Selector Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button id="pbtn-healthy" onclick="selectPlantSample('healthy')" class="plant-btn px-3 py-1.5 rounded-xl bg-slate-800 text-xs font-semibold text-slate-300 hover:text-white transition">
                            <i class="fa-solid fa-seedling text-emerald-400 mr-1"></i> ใบสมบูรณ์
                        </button>
                        <button id="pbtn-fungal_spot" onclick="selectPlantSample('fungal_spot')" class="plant-btn px-3 py-1.5 rounded-xl bg-slate-800 text-xs font-semibold text-slate-300 hover:text-white transition">
                            <i class="fa-solid fa-circle-exclamation text-rose-400 mr-1"></i> โรคใบจุดราสนิม
                        </button>
                        <button id="pbtn-nutrient_def" onclick="selectPlantSample('nutrient_def')" class="plant-btn px-3 py-1.5 rounded-xl bg-slate-800 text-xs font-semibold text-slate-300 hover:text-white transition">
                            <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i> ขาดธาตุอาหาร
                        </button>
                        <button id="pbtn-pest_damage" onclick="selectPlantSample('pest_damage')" class="plant-btn px-3 py-1.5 rounded-xl bg-slate-800 text-xs font-semibold text-slate-300 hover:text-white transition">
                            <i class="fa-solid fa-bug text-purple-400 mr-1"></i> เพลี้ยไฟ/ไรแดง
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6 items-center">
                    <!-- Leaf Inspection Visualizer with Bounding Box -->
                    <div class="md:col-span-5 relative bg-slate-950 rounded-2xl p-4 border border-slate-800 flex items-center justify-center min-h-[220px]">
                        <div id="diag-bbox" class="relative w-48 h-48 border-2 border-dashed rounded-2xl flex items-center justify-center transition-all duration-500">
                            <i class="fa-solid fa-leaf text-6xl text-slate-700 animate-pulse"></i>
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-slate-900/90 text-[10px] font-mono text-cyan-400 border border-slate-700">
                                ROI: Leaf #01
                            </div>
                        </div>
                    </div>

                    <!-- AI Diagnostic Readouts -->
                    <div class="md:col-span-7 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 id="diag-title" class="text-xl font-bold text-white font-heading"></h4>
                            <span id="diag-speed" class="px-2.5 py-1 rounded-full bg-slate-800 text-cyan-400 text-xs font-mono"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-xl bg-slate-950 border border-slate-800">
                                <div class="text-[10px] text-slate-400 uppercase">ผลวินิจฉัย (Predicted Class)</div>
                                <div id="diag-class" class="font-bold"></div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-950 border border-slate-800">
                                <div class="text-[10px] text-slate-400 uppercase">ความเชื่อมั่น (Confidence)</div>
                                <div id="diag-conf" class="text-lg font-mono font-bold text-white"></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 text-xs space-y-2">
                            <div><b class="text-cyan-400">อาการทางสรีรวิทยา:</b> <span id="diag-desc"></span></div>
                            <div><b class="text-emerald-400">คำแนะนำการแก้ไข xAI:</b> <span id="diag-action"></span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. ATD3.5-S3 HARDWARE SCREENS SHOWCASE -->
    <!-- ========================================================================= -->
    <section id="screens" class="py-20 bg-slate-900/40 relative border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase tracking-wider">
                    ATD3.5-S3 Touch LCD 480x320
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    แกลเลอรี 10 หน้าจอฮาร์ดแวร์ LEQs-xAI
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    ภาพเรนเดอร์ความละเอียดสูงจริงจากเฟิร์มแวร์ ESP32-S3 ที่ติดตั้งบนบอร์ดคอนโทรลเลอร์หน้าจอสัมผัส ATD3.5-S3
                </p>
            </div>

            <!-- Screen Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                
                <!-- 00 Splash -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/00_splash_nexus.png', 'Screen 00: Splash Nexus Screen', 'หน้าจอบูตเริ่มต้น โหลดโมเดล TinyML, ตรวจสอบฮาร์ดแวร์บัส I2C / Modbus RS485, และแสดงแบรนด์ LEQs-xAI')">
                    <img src="assets/images/atd35/00_splash_nexus.png" alt="Splash Screen" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">00. Splash Nexus</div>
                        <div class="text-[10px] text-slate-400">Boot & TinyML Engine</div>
                    </div>
                </div>

                <!-- 01 Overview -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/01_overview_dashboard.png', 'Screen 01: Overview Dashboard', 'แดชบอร์ดหลัก 4 มิติ สรุปข้อมูล อากาศ-VPD, แสง-PAR, ผิวดิน, ดินลึก 7-in-1 และสถานะรีเลย์ 4 ช่อง')">
                    <img src="assets/images/atd35/01_overview_dashboard.png" alt="Overview Dashboard" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">01. Overview 4D</div>
                        <div class="text-[10px] text-slate-400">Master Dashboard</div>
                    </div>
                </div>

                <!-- 02 Big Numbers -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/02_big_numbers.png', 'Screen 02: Big Numbers Focus', 'หน้าจอแสดงตัวเลขขนาดใหญ่ คอนทราสต์สูง สำหรับเกษตรกรอ่านค่ากลางแจ้งได้อย่างชัดเจน')">
                    <img src="assets/images/atd35/02_big_numbers.png" alt="Big Numbers Screen" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">02. Big Numbers</div>
                        <div class="text-[10px] text-slate-400">High-Contrast Sunview</div>
                    </div>
                </div>

                <!-- 03 Realtime Graphs -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/03_realtime_graphs.png', 'Screen 03: Realtime Trends & Graphs', 'กราฟเส้นแนวโน้มย้อนหลัง 24 ชั่วโมง ตรวจจับความชื้นดินสองระดับและการซึมลึกของน้ำ')">
                    <img src="assets/images/atd35/03_realtime_graphs.png" alt="Realtime Graphs" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">03. Realtime Graphs</div>
                        <div class="text-[10px] text-slate-400">24h Dual-Depth Trends</div>
                    </div>
                </div>

                <!-- 04 Relay Control -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/04_relay_control.png', 'Screen 04: Relay & Actuator Control', 'แผงควบคุมสวิตช์รีเลย์ 4 ช่อง เปิด/ปิดปั๊มน้ำ โซลินอยด์วาล์ว ระบบรดน้ำอัตโนมัติ และระบบแมนนวล')">
                    <img src="assets/images/atd35/04_relay_control.png" alt="Relay Control" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">04. Relay Actuator</div>
                        <div class="text-[10px] text-slate-400">4-CH Intelligent Switch</div>
                    </div>
                </div>

                <!-- 05 Wi-Fi Captive -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/05_wifi_captive_portal.png', 'Screen 05: Wi-Fi Captive Portal', 'หน้าจอ QR Code สำหรับสแกนเชื่อมต่อ Wi-Fi หรือตั้งค่า SoftAP ชื่อ LEQs-AgriEnvi-Setup')">
                    <img src="assets/images/atd35/05_wifi_captive_portal.png" alt="Wi-Fi Captive Portal" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">05. Wi-Fi QR Portal</div>
                        <div class="text-[10px] text-slate-400">Zero-Config Pairing</div>
                    </div>
                </div>

                <!-- 06 SHT45 VPD -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/06_sht45_air_vpd.png', 'Screen 06: SHT45 Microclimate & VPD', 'หน้าวิเคราะห์สภาพอากาศเชิงลึก อุณหภูมิ ความชื้นสัมพัทธ์ จุดน้ำค้าง และดัชนีเสี่ยงโรครา')">
                    <img src="assets/images/atd35/06_sht45_air_vpd.png" alt="SHT45 VPD" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">06. SHT45 VPD</div>
                        <div class="text-[10px] text-slate-400">Microclimate Physics</div>
                    </div>
                </div>

                <!-- 07 BH1750 PAR -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/07_bh1750_solar_par.png', 'Screen 07: BH1750 Solar Spectrum & PAR', 'การวัดความสว่างแดด Lux และคำนวณโฟตอนสังเคราะห์แสง PAR (umol/m2/s) สำหรับพืช')">
                    <img src="assets/images/atd35/07_bh1750_solar_par.png" alt="BH1750 Solar PAR" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">07. BH1750 PAR</div>
                        <div class="text-[10px] text-slate-400">Solar Photosynthesis</div>
                    </div>
                </div>

                <!-- 08 Soil Stick -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/08_soil_stick_surface.png', 'Screen 08: Soil Stick Surface Moisture', 'การตรวจสอบหน้าดิน 0-10 cm เฝ้าระวังการระเหยน้ำและการแตกระแหงของผิวดิน')">
                    <img src="assets/images/atd35/08_soil_stick_surface.png" alt="Soil Stick" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">08. Soil Stick</div>
                        <div class="text-[10px] text-slate-400">Surface 0-10 cm</div>
                    </div>
                </div>

                <!-- 09 Soil 7-in-1 TinyML -->
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 hover:border-cyan-500/50 transition cursor-pointer p-2"
                     onclick="openScreenModal('assets/images/atd35/09_soil_7in1_tinyml.png', 'Screen 09: Soil 7-in-1 & TinyML Inference', 'การวิเคราะห์แร่ธาตุเขตราก 10-30 cm: NPK, pH, EC พร้อมผลทำนายจาก TinyML และ xAI')">
                    <img src="assets/images/atd35/09_soil_7in1_tinyml.png" alt="Soil 7-in-1 TinyML" class="w-full h-auto rounded-xl object-cover transform group-hover:scale-105 transition duration-300">
                    <div class="mt-2 text-center">
                        <div class="text-xs font-bold text-white group-hover:text-cyan-400 transition">09. Soil 7in1 TinyML</div>
                        <div class="text-[10px] text-slate-400">Deep Root xAI</div>
                    </div>
                </div>

            </div>

            <!-- Master Showcase Banner CTA -->
            <div class="mt-12 p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 border border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-white">โปสเตอร์ Master PR Showcase (ความละเอียด 3200x2160 Ultra-HD)</h4>
                        <p class="text-xs text-slate-400">รวบรวมครบทั้ง 10 หน้าจอ สำหรับใช้เป็นสื่อประชาสัมพันธ์โครงการและจัดทำบอร์ดนิทรรศการ</p>
                    </div>
                </div>
                <a href="assets/images/atd35/master_pr_showcase_poster.jpg" target="_blank" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> เปิดดูภาพ Ultra-HD
                </a>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. 7 LEARNING MODULES CURRICULUM -->
    <!-- ========================================================================= -->
    <section id="modules" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold uppercase tracking-wider">
                    Comprehensive Curriculum
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    หลักสูตรเข้มข้น 7 หน่วยการเรียนรู้ (Modules)
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    ออกแบบตามมาตรฐาน Active Learning และ Problem-based Learning (PBL) มุ่งสร้างทักษะจริง 70%
                </p>
            </div>

            <!-- Modules Vertical Stack -->
            <div class="space-y-5">
                
                <!-- Module 1 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M1
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-cyan-400 uppercase tracking-wider">Module 1</span>
                                <h3 class="text-lg font-bold text-white">AI for Digital Agriculture: รู้จัก AI ในโลกการเกษตร</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Machine Learning vs Deep Learning, Data → Model → Prediction, การค้นหาปัญหาจริงในแปลงเกษตร (Agri-AI Problem Discovery)
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">2 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 2 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M2
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Module 2</span>
                                <h3 class="text-lg font-bold text-white">Digital Agriculture & IoT: เปลี่ยนแปลงพื้นที่เกษตรให้เป็นข้อมูล</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    การต่อวงจรเซนเซอร์ RS485 Modbus RTU, I2C, ฟิสิกส์การวัดดิน น้ำ แสง อากาศ และ Smart Farm Sensor Lab
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">3 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 3 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M3
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-purple-400 uppercase tracking-wider">Module 3</span>
                                <h3 class="text-lg font-bold text-white">Computer Vision for Agriculture: ให้ AI มองเห็นพืช</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Digital Image, Image Annotation, Data Augmentation, CNN เบื้องต้น และปฏิบัติการ Plant Vision AI จำแนกภาพโรคใบพืช
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">2.5 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 4 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M4
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-blue-400 uppercase tracking-wider">Module 4</span>
                                <h3 class="text-lg font-bold text-white">Deep Learning: สร้างสมอง AI</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    โครงข่ายประสาทเทียม CNN, Training, Epoch, Loss, Overfitting และเวิร์กช็อป Train Your First Agriculture AI ด้วย Colab
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">2.5 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 5 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M5
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Module 5 (Core Highlight)</span>
                                <h3 class="text-lg font-bold text-white">Edge AI: นำ AI ออกจาก Cloud สู่พื้นที่จริง</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    การบีบอัดโมเดล TinyML สู่ C++ Array, On-Device Inference บน ESP32-S3 ATD3.5-S3 ทำงานแบบเรียลไทม์ไม่ต้องพึ่งพาคลาวด์
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-mono font-bold self-start md:self-auto">3 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 6 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M6
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-rose-400 uppercase tracking-wider">Module 6</span>
                                <h3 class="text-lg font-bold text-white">Environmental AI: AI นักสืบสิ่งแวดล้อม</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    วิเคราะห์สมดุลคาร์บอน, สภาพอากาศ, ดัชนีไอน้ำสัมพันธ์ VPD, แสงสังเคราะห์แสง PAR และการอนุรักษ์น้ำและสิ่งแวดล้อม
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">2 ชั่วโมง</span>
                    </div>
                </div>

                <!-- Module 7 -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-slate-700 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-tech font-bold text-lg flex-shrink-0">
                                M7
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-sky-400 uppercase tracking-wider">Module 7</span>
                                <h3 class="text-lg font-bold text-white">Agri-Environmental Intelligence: บูรณาการภาพ + เซนเซอร์ + IoT + AI</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    ระบบช่วยตัดสินใจเปิดน้ำใส่ปุ๋ยอัตโนมัติ และเวิร์กช็อป Capstone Mini Project ชิงรางวัลและเกียรติบัตร
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 text-xs font-mono self-start md:self-auto">3 ชั่วโมง</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. CAPSTONE MINI PROJECTS & 6 TRACKS -->
    <!-- ========================================================================= -->
    <section id="capstone" class="py-20 bg-slate-900/40 relative border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-bold uppercase tracking-wider">
                    Hands-on Capstone Innovation
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    6 แทร็กโครงงานนวัตกรรม (Mini Project Tracks)
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    ผู้เข้าร่วมอบรมเลือกพัฒนาโครงงานแก้ปัญหาจริงในแปลงเกษตรของตนเอง หรือต่อยอดเป็นผลงานทางวิทยาศาสตร์
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Track A -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-cyan-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-wheat-awn"></i>
                    </div>
                    <span class="text-xs font-bold text-cyan-400 uppercase tracking-wider font-tech">Track A</span>
                    <h3 class="text-lg font-bold text-white mt-1">Smart Agriculture</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        ระบบฟาร์มอัจฉริยะแบบครบวงจร เซนเซอร์วัดสภาพแวดล้อม ควบคุมวาล์วให้น้ำอัตโนมัติตามสภาพอากาศ
                    </p>
                </div>

                <!-- Track B -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-emerald-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider font-tech">Track B</span>
                    <h3 class="text-lg font-bold text-white mt-1">Plant Vision AI</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        ระบบตรวจจับและวินิจฉัยโรคพืชจากภาพถ่ายใบพืช ประเมินความสมบูรณ์และเตือนการระบาดของโรครา
                    </p>
                </div>

                <!-- Track C -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-amber-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-mountain"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider font-tech">Track C</span>
                    <h3 class="text-lg font-bold text-white mt-1">Smart Soil Monitoring</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        ระบบวิเคราะห์ธาตุอาหารดิน N-P-K, ค่า pH, ความเค็ม EC และแนะนำสูตรปุ๋ยที่เหมาะสมแบบแม่นยำ
                    </p>
                </div>

                <!-- Track D -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-rose-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-smog"></i>
                    </div>
                    <span class="text-xs font-bold text-rose-400 uppercase tracking-wider font-tech">Track D</span>
                    <h3 class="text-lg font-bold text-white mt-1">Environmental AI Station</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        สถานีตรวจวัดสภาพอากาศจุลภาค คำนวณค่า VPD, PAR, และเฝ้าระวังผลกระทบจากการเปลี่ยนแปลงภูมิอากาศ
                    </p>
                </div>

                <!-- Track E -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-purple-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-wider font-tech">Track E</span>
                    <h3 class="text-lg font-bold text-white mt-1">Offline Edge AI Agriculture</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        ระบบปัญญาประดิษฐ์แบบออฟไลน์สำหรับพื้นที่ห่างไกล ไร้สัญญาณอินเทอร์เน็ต ตัดสินใจบนบอร์ด ESP32-S3 100%
                    </p>
                </div>

                <!-- Track F -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 hover:border-sky-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider font-tech">Track F</span>
                    <h3 class="text-lg font-bold text-white mt-1">School AI STEM Project</h3>
                    <p class="text-xs text-slate-400 mt-2">
                        โครงงานวิทยาศาสตร์สำหรับนักเรียนมัธยมปลาย เพื่อการแข่งขันระดับชาติ และการสะสมแฟ้มผลงาน TCAS Portfolio
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. PROJECT PROPOSAL DOCUMENTS & DOWNLOADS -->
    <!-- ========================================================================= -->
    <section id="documents" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="px-3.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold uppercase tracking-wider">
                    Official Proposals & Approvals
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading mt-3">
                    เอกสารโครงการและขออนุมัติงบประมาณ
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    จัดทำเป็นระบบตามแบบฟอร์มงบประมาณรายจ่าย ประจำปีงบประมาณ พ.ศ. 2569 มหาวิทยาลัยราชภัฏรำไพพรรณี
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Doc 1: Official Approval -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <h3 class="font-bold text-white text-base">โครงการขออนุมัติงบประมาณ พ.ศ. 2569</h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            แบบฟอร์มทางการของ มรภ.รำไพพรรณี เสนอต่อสภามหาวิทยาลัย วงเงินงบประมาณ 76,000 บาท ระบุตัวชี้วัด SDGs และ EdPEx
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-emerald-400 font-mono">LEQs_xAI_Project_Approval_RBRU.md</span>
                        <a href="LEQs_xAI_Project_Approval_RBRU.md" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 transition">
                            <i class="fa-solid fa-arrow-down mr-1"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>

                <!-- Doc 2: Detailed Workshop Proposal -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h3 class="font-bold text-white text-base">ร่างโครงสร้างเนื้อหาและการจัดอบรม</h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            รายละเอียดเนื้อหาทั้ง 7 โมดูล แผนการสอน Active Learning, โมเดล LEQs, และการจัดสรรเวลาปฏิบัติการ 2 วัน 1 คืน
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-cyan-400 font-mono">LEQs_xAI_Workshop_Proposal.md</span>
                        <a href="LEQs_xAI_Workshop_Proposal.md" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 transition">
                            <i class="fa-solid fa-arrow-down mr-1"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>

                <!-- Doc 3: Manual & Prompts Book -->
                <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid fa-book-atlas"></i>
                        </div>
                        <h3 class="font-bold text-white text-base">คู่มือและคลังพร้อมท์วิศวกรรม</h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            คู่มือปฏิบัติการ C++ PlatformIO, โค้ดควบคุมหน้าจอ TFT_eSPI, คำสั่ง Modbus RTU และพร้อมท์สำหรับต่อยอดปัญญาประดิษฐ์
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-purple-400 font-mono">MANUAL_AND_PROMPT_BOOK.md</span>
                        <a href="MANUAL_AND_PROMPT_BOOK.md" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 transition">
                            <i class="fa-solid fa-arrow-down mr-1"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. FOOTER (4-COLUMN MODEL) -->
    <!-- ========================================================================= -->
    <footer class="bg-slate-950 border-t border-slate-800/80 pt-16 pb-12 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: Identity -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-cyan-500/40 flex items-center justify-center text-cyan-400 text-xl font-tech font-bold">
                            L
                        </div>
                        <div>
                            <div class="font-tech text-lg font-black text-white">LEQs-xAI</div>
                            <div class="text-[10px] text-slate-400">Digital Agriculture & Environmental AI</div>
                        </div>
                    </div>
                    <p class="text-slate-400 leading-relaxed">
                        ศูนย์พัฒนานวัตกรรมเกษตรดิจิทัลรำไพพรรณี-ประณีตวิทยาคม (RBRU-Praneet Digital Agri-Innovation Center)
                        คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                    </p>
                    <div class="text-[11px] text-emerald-400 font-semibold">
                        <i class="fa-solid fa-check mr-1"></i> สอดคล้องเป้าหมายการพัฒนาที่ยั่งยืน (SDGs)
                    </div>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-3">
                    <div class="font-bold text-white uppercase tracking-wider text-xs font-heading">ระบบและบริการ</div>
                    <ul class="space-y-2">
                        <li><a href="pages/register.php" class="hover:text-cyan-400 transition">ลงทะเบียนเข้าร่วมอบรม</a></li>
                        <li><a href="pages/student_list.php" class="hover:text-cyan-400 transition">ประกาศรายชื่อผู้สมัคร</a></li>
                        <li><a href="pages/assessment_pre.php" class="hover:text-cyan-400 transition">แบบทดสอบก่อนเรียน (Pre-test)</a></li>
                        <li><a href="pages/assessment_post.php" class="hover:text-cyan-400 transition">แบบทดสอบหลังเรียน & วุฒิบัตร</a></li>
                        <li><a href="admin/index.php" class="hover:text-cyan-400 transition">ระบบผู้ดูแลระบบ (Admin CMS)</a></li>
                    </ul>
                </div>

                <!-- Col 3: Coordinator & Contact -->
                <div class="space-y-3">
                    <div class="font-bold text-white uppercase tracking-wider text-xs font-heading">ผู้ประสานงานโครงการ</div>
                    <div class="space-y-1.5">
                        <div class="text-slate-200 font-bold">ผศ.ดร.ชีวะ ทัศนา</div>
                        <div>อาจารย์ประจำสาขาวิชาฟิสิกส์</div>
                        <div>คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี</div>
                        <div><i class="fa-solid fa-envelope text-cyan-400 mr-1.5"></i> chewa.t@rbru.ac.th</div>
                        <div><i class="fa-solid fa-phone text-emerald-400 mr-1.5"></i> 039-319-111 ต่อ 207</div>
                    </div>
                </div>

                <!-- Col 4: Location Map & Coordinates -->
                <div class="space-y-3">
                    <div class="font-bold text-white uppercase tracking-wider text-xs font-heading">พิกัดศูนย์การเรียนรู้</div>
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        โรงเรียนประณีตวิทยาคม ตำบลประณีต อำเภอเขาสมิง จังหวัดตราด 23150
                    </p>
                    <div class="rounded-xl overflow-hidden border border-slate-800 bg-slate-900 h-28 relative">
                        <!-- Embedded Mini Map Representation -->
                        <iframe class="w-full h-full border-0 opacity-80 hover:opacity-100 transition" 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3896.793739775082!2d102.4069678!3d12.4013697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3104683058a9da95%3A0xc48c0a87a71f021e!2z4LmC4Lij4LiH4LmA4Lij4Li14Lii4LiZ4Lib4Lij4Liw4LiT4Li14LiV4Lin4Li04Lii4Liy4LiE4Lih!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth"
                                allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

            </div>

            <!-- Bottom Sub-Footer -->
            <div class="mt-12 pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
                <div>
                    © 2026 LEQs-xAI Consortium. All rights reserved. มหาวิทยาลัยราชภัฏรำไพพรรณี ร่วมกับ โรงเรียนประณีตวิทยาคม
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-slate-400">Powered by ESP32-S3 ATD3.5 & HandySense</span>
                    <a href="#overview" class="text-cyan-400 hover:text-cyan-300 font-semibold"><i class="fa-solid fa-arrow-up mr-1"></i> กลับด้านบน</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- 10. SCREEN LIGHTBOX MODAL -->
    <!-- ========================================================================= -->
    <div id="screen-modal" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-xl hidden items-center justify-center p-4">
        <div class="relative max-w-4xl w-full bg-slate-900 border border-slate-700/80 rounded-3xl p-6 shadow-2xl">
            <button onclick="closeScreenModal()" class="absolute top-5 right-5 w-10 h-10 rounded-full bg-slate-800 text-slate-300 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="space-y-4">
                <div>
                    <h3 id="modal-screen-title" class="text-xl font-bold text-white font-heading"></h3>
                    <p id="modal-screen-desc" class="text-xs text-slate-400 mt-1"></p>
                </div>
                <div class="rounded-2xl overflow-hidden border border-slate-800 bg-slate-950 p-2">
                    <img id="modal-screen-img" src="" alt="Screen Preview" class="w-full h-auto rounded-xl max-h-[70vh] object-contain mx-auto">
                </div>
            </div>
        </div>
    </div>

</body>
</html>
