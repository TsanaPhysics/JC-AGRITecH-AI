<?php
/**
 * LEQs-xAI Registration Page — Cyber Dark Sci-Fi IoT HUD Theme
 * Aligned with HandySense LEQs-xAI Flutter App Design System
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
$page_title = "ลงทะเบียนเข้าร่วมอบรม | LEQs-xAI Cyber Portal";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@400;500;600;700;800&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;800;900&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        /* Cyber Dark Sci-Fi IoT Custom Tokens */
        :root {
            --cyber-bg: #090D16;
            --cyber-card: #111A2E;
            --cyber-card-inner: #0A101D;
            --cyber-border: #1B2842;
            --cyber-border-glow: #263B60;
            --neon-cyan: #00C6FF;
            --neon-cyan-light: #00E5FF;
            --neon-green: #00E676;
            --neon-orange: #FF7A00;
            --neon-purple: #7C4DFF;
        }

        body {
            font-family: 'Sarabun', 'Rajdhani', sans-serif;
            background-color: var(--cyber-bg);
            color: #F1F5F9;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(0, 198, 255, 0.07) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(0, 230, 118, 0.06) 0%, transparent 45%),
                linear-gradient(rgba(27, 40, 66, 0.22) 1px, transparent 1px),
                linear-gradient(90deg, rgba(27, 40, 66, 0.22) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 36px 36px, 36px 36px;
        }

        .font-orbitron { font-family: 'Orbitron', monospace; }
        .font-rajdhani { font-family: 'Rajdhani', sans-serif; }
        .font-kanit    { font-family: 'Kanit', sans-serif; }

        /* Tactical Corner HUD Accents */
        .hud-bracket {
            position: relative;
        }
        .hud-bracket::before, .hud-bracket::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .hud-bracket::before {
            top: -2px; left: -2px;
            border-top: 2px solid var(--neon-cyan);
            border-left: 2px solid var(--neon-cyan);
            border-top-left-radius: 6px;
        }
        .hud-bracket::after {
            bottom: -2px; right: -2px;
            border-bottom: 2px solid var(--neon-cyan);
            border-right: 2px solid var(--neon-cyan);
            border-bottom-right-radius: 6px;
        }

        /* Cyber Neon Glows */
        .glow-cyan {
            box-shadow: 0 0 20px rgba(0, 198, 255, 0.25);
        }
        .glow-green {
            box-shadow: 0 0 20px rgba(0, 230, 118, 0.25);
        }

        /* Pulsing LED Dot */
        .pulse-led {
            animation: pulse-glow 2s infinite ease-in-out;
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.5; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1.15); filter: drop-shadow(0 0 6px var(--neon-cyan)); }
        }

        /* Custom Cyber Inputs */
        .cyber-input {
            background-color: var(--cyber-card-inner);
            border: 1.4px solid var(--cyber-border);
            color: #F1F5F9;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cyber-input:focus {
            border-color: var(--neon-cyan-light);
            box-shadow: 0 0 16px rgba(0, 229, 255, 0.25);
            outline: none;
        }

        /* SweetAlert2 Cyber Dark Override */
        .swal2-popup.swal2-modal {
            background: #111A2E !important;
            border: 1.5px solid #1B2842 !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8), 0 0 30px rgba(0, 198, 255, 0.2) !important;
            color: #FFFFFF !important;
        }
        .swal2-title {
            color: #FFFFFF !important;
            font-family: 'Kanit', sans-serif !important;
        }
        @media (max-width: 640px) {
            .cyber-input, input, select, textarea {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-cyan-500/30 selection:text-cyan-200">

    <!-- Top Sci-Fi Navbar -->
    <nav class="bg-[#0C1322]/90 backdrop-blur-2xl border-b border-[#1B2842] py-3 px-4 sm:px-6 fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-2.5 sm:gap-3 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-slate-950 font-orbitron font-extrabold text-lg sm:text-xl shadow-lg shadow-cyan-500/30 group-hover:scale-105 transition-transform shrink-0">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <div>
                    <div class="font-orbitron text-sm sm:text-base font-black text-white tracking-wider flex items-center gap-1.5">
                        LEQs-xAI <span class="text-[9px] sm:text-[10px] px-1.5 py-0.5 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">HUD</span>
                    </div>
                    <div class="text-[10px] sm:text-[11px] font-rajdhani font-semibold text-slate-400 tracking-wide truncate max-w-[170px] sm:max-w-none">
                        SMART IOT REGISTRATION
                    </div>
                </div>
            </a>

            <!-- Right Buttons -->
            <div class="flex items-center gap-1.5 sm:gap-2.5 text-xs font-rajdhani font-bold tracking-wide">
                <a href="../index.php" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-[#131D31] hover:bg-[#1A2640] text-slate-300 border border-[#1B2842] hover:border-cyan-500/50 transition-all flex items-center gap-1.5" title="กลับหน้าหลัก">
                    <i class="fa-solid fa-arrow-left text-cyan-400"></i> <span class="hidden sm:inline">หน้าหลัก</span>
                </a>
                <a href="student_list.php" class="px-2.5 sm:px-4 py-2 rounded-xl bg-cyan-500/15 text-cyan-300 border border-cyan-500/50 hover:bg-cyan-500/25 shadow-lg shadow-cyan-500/15 transition-all flex items-center gap-1.5 shrink-0" title="ตรวจสอบรายชื่อผู้สมัคร">
                    <i class="fa-solid fa-id-card-clip text-cyan-400"></i> <span class="hidden sm:inline">ตรวจสอบ</span>รายชื่อ
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 sm:pt-28 pb-20 px-3 sm:px-4 max-w-3xl mx-auto w-full">
        
        <!-- Cyber HUD Master Card -->
        <div class="hud-bracket p-4 sm:p-10 rounded-2xl sm:rounded-3xl bg-[#111A2E]/95 border border-[#1B2842] shadow-2xl backdrop-blur-2xl relative overflow-hidden">
            
            <!-- Ambient Top Neon Glow -->
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-36 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header Section -->
            <div class="text-center mb-8 relative">
                <!-- Tech Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#0A101D] border border-cyan-500/40 shadow-sm shadow-cyan-500/20 mb-3">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 pulse-led"></span>
                    <span class="font-orbitron text-[11px] font-bold text-cyan-300 uppercase tracking-widest">
                        ADMISSION & REGISTRATION PORTAL
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white font-kanit tracking-tight mt-1 flex items-center justify-center gap-2">
                    <span>ลงทะเบียนเข้าร่วมอบรมเชิงปฏิบัติการ</span>
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-400 font-rajdhani font-semibold tracking-wide mt-2">
                    โครงการ LEQs-xAI ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (ไม่มีค่าใช้จ่ายตลอดโครงการ)
                </p>

                <!-- UN SDG Badges in Cyber Dark Tone -->
                <div class="flex flex-wrap items-center justify-center gap-1.5 mt-4 pt-3 border-t border-[#1B2842]">
                    <span class="text-[10px] font-orbitron text-slate-400 font-bold uppercase tracking-wider mr-1">UN SDGs:</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#DDA63A]/15 text-[#f3ca65] border border-[#DDA63A]/40 text-[10px] font-bold font-rajdhani">SDG 2 เกษตรยั่งยืน</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#C5192D]/15 text-[#ff808f] border border-[#C5192D]/40 text-[10px] font-bold font-rajdhani">SDG 4 การศึกษา</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#FF6925]/15 text-[#ffa67a] border border-[#FF6925]/40 text-[10px] font-bold font-rajdhani">SDG 9 นวัตกรรม</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#BF8B2E]/15 text-[#eed088] border border-[#BF8B2E]/40 text-[10px] font-bold font-rajdhani">SDG 12 ทรัพยากร</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#00E676]/15 text-[#69F0AE] border border-[#00E676]/40 text-[10px] font-bold font-rajdhani">SDG 13 สภาพอากาศ</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#00C6FF]/15 text-[#80D8FF] border border-[#00C6FF]/40 text-[10px] font-bold font-rajdhani">SDG 15 นิเวศดิน</span>
                    <span class="px-2 py-0.5 rounded-md bg-[#7C4DFF]/15 text-[#B388FF] border border-[#7C4DFF]/40 text-[10px] font-bold font-rajdhani">SDG 17 เครือข่าย</span>
                </div>

                <!-- Event Schedule & Location Cyber HUD Box -->
                <div class="mt-5 p-4 rounded-2xl bg-[#0A101D]/90 border border-cyan-500/30 shadow-inner grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-orbitron uppercase text-cyan-400 font-bold tracking-wider">วันจัดกิจกรรมอบรม (3 วัน 18 ชม.)</div>
                            <div class="text-sm font-bold text-white font-kanit">28 - 30 พฤศจิกายน 2569</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 border-t sm:border-t-0 sm:border-l border-[#1B2842] pt-2.5 sm:pt-0 sm:pl-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-orbitron uppercase text-emerald-400 font-bold tracking-wider">สถานที่จัดกิจกรรม</div>
                            <div class="text-xs sm:text-sm font-bold text-white font-kanit">คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี จันทบุรี</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form id="regForm" onsubmit="submitRegistration(event)" class="space-y-5 text-xs sm:text-sm">
                
                <!-- Section 1: Personal Information -->
                <div class="p-4 rounded-2xl bg-[#0E1626] border border-[#1B2842] space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-[#1B2842]">
                        <span class="w-2 h-4 rounded-full bg-cyan-400"></span>
                        <h2 class="font-kanit font-bold text-sm sm:text-base text-white">ข้อมูลส่วนบุคคลของผู้สมัคร</h2>
                    </div>

                    <!-- Prefix & Fullname -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-user-tag text-cyan-400 text-xs"></i> คำนำหน้า
                            </label>
                            <select id="prefix" name="prefix" class="cyber-input w-full px-3.5 py-2.5 rounded-xl font-medium cursor-pointer">
                                <option value="นาย">นาย</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="นาง">นาง</option>
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-id-badge text-cyan-400 text-xs"></i> ชื่อ - นามสกุล <span class="text-rose-400 font-bold">*</span>
                            </label>
                            <input type="text" id="fullname" name="fullname" required placeholder="เช่น ดร.สมชาย ใจดี หรือ สมชาย ใจดี"
                                   class="cyber-input w-full px-3.5 py-2.5 rounded-xl placeholder:text-slate-600">
                        </div>
                    </div>

                    <!-- Target Group -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-cyan-400 text-xs"></i> กลุ่มเป้าหมายผู้สมัคร <span class="text-rose-400 font-bold">*</span>
                        </label>
                        <select id="target_group" name="target_group" class="cyber-input w-full px-3.5 py-2.5 rounded-xl font-medium cursor-pointer">
                            <option value="นักเรียนมัธยมศึกษาตอนปลาย">1. นักเรียนระดับมัธยมศึกษาตอนปลาย (ม.4 - ม.6) สะสมผลงาน Portfolio</option>
                            <option value="ครูและบุคลากรทางการศึกษา">2. ครูและบุคลากรทางการศึกษา (กลุ่มสาระวิทยาศาสตร์/คอมพิวเตอร์/เกษตร)</option>
                            <option value="เกษตรกรผู้เพาะปลูก">3. เกษตรกรผู้เพาะปลูกผลไม้ / สวนทุเรียน / เกษตรกรรุ่นใหม่ (Young Smart Farmer)</option>
                            <option value="เกษตรกรและผู้สนใจทั่วไป">4. ประชาชนและผู้สนใจเทคโนโลยี AIoT และสมาร์ทฟาร์มทั่วไป</option>
                        </select>
                    </div>

                    <!-- Organization / School -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-building-columns text-cyan-400 text-xs"></i> โรงเรียน / สวนเกษตร / หน่วยงานสังกัด <span class="text-rose-400 font-bold">*</span>
                        </label>
                        <input type="text" id="organization" name="organization" required placeholder="เช่น โรงเรียนประณีตวิทยาคม หรือ สวนทุเรียนเขาสมิง หรือ ฟาร์มอิสระ"
                               class="cyber-input w-full px-3.5 py-2.5 rounded-xl placeholder:text-slate-600">
                    </div>

                    <!-- Contact: Phone & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-cyan-400 text-xs"></i> เบอร์โทรศัพท์ติดต่อ <span class="text-rose-400 font-bold">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required placeholder="08x-xxx-xxxx"
                                   class="cyber-input w-full px-3.5 py-2.5 rounded-xl placeholder:text-slate-600 font-orbitron text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-envelope text-cyan-400 text-xs"></i> อีเมล (ถ้ามี)
                            </label>
                            <input type="email" id="email" name="email" placeholder="example@email.com"
                                   class="cyber-input w-full px-3.5 py-2.5 rounded-xl placeholder:text-slate-600 font-rajdhani">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Capstone Track Selection -->
                <div class="p-4 rounded-2xl bg-[#0E1626] border border-[#1B2842] space-y-3">
                    <div class="flex items-center gap-2 pb-2 border-b border-[#1B2842]">
                        <span class="w-2 h-4 rounded-full bg-orange-400"></span>
                        <h2 class="font-kanit font-bold text-sm sm:text-base text-white">แทร็กโครงงาน Capstone ที่สนใจเป็นพิเศษ</h2>
                    </div>

                    <div>
                        <select id="project_track" name="project_track" class="cyber-input w-full px-3.5 py-3 rounded-xl font-medium cursor-pointer">
                            <option value="Track A — Smart Agriculture">🌾 Track A — Smart Agriculture (ระบบฟาร์มอัจฉริยะครบวงจร HandySense)</option>
                            <option value="Track B — Plant Vision">👁️ Track B — Plant Vision (ตรวจจับโรคและแมลงศัตรูพืชด้วย Computer Vision)</option>
                            <option value="Track C — Smart Soil">🧪 Track C — Smart Soil (วิเคราะห์ธาตุอาหารดิน NPK & pH ด้วย TinyML)</option>
                            <option value="Track D — Environmental AI">🌤️ Track D — Environmental AI (สถานีตรวจวัดสภาพอากาศจุลภาค & VPD Alert)</option>
                            <option value="Track E — Edge AI">⚡ Track E — Edge AI (ระบบปัญญาประดิษฐ์ประมวลผลออฟไลน์บน ESP32-S3)</option>
                            <option value="Track F — School AI">🎓 Track F — School AI (โครงงานนวัตกรรมวิทย์ ม.ปลาย เพื่อยื่น Portfolio TCAS)</option>
                        </select>
                    </div>
                </div>

                <!-- Section 3: LINE Official Group QR Code Section (Mandatory) -->
                <div id="line_group_card" class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-[#062417]/70 via-[#0A1624]/90 to-[#0A101D] border-2 border-emerald-500/50 shadow-xl shadow-emerald-950/40 relative overflow-hidden transition-all duration-300">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-emerald-500/20">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-[#06C755] text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-emerald-500/30 shrink-0">
                                <i class="fa-brands fa-line"></i>
                            </span>
                            <div>
                                <h3 class="font-kanit font-bold text-sm sm:text-base text-white flex items-center gap-2">
                                    เข้าร่วมกลุ่มไลน์ทางการ (LEQs-xAI Workshop)
                                </h3>
                                <p class="text-[11px] sm:text-xs text-emerald-300 font-rajdhani font-semibold">ช่องทางหลักสำหรับแจ้งกำหนดการ นัดหมาย ลิงก์ดาวน์โหลดสื่อ และประสานงานวิทยากร</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[10px] font-orbitron font-bold shrink-0 animate-pulse">
                            * MANDATORY
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
                        <!-- QR Code Image -->
                        <div class="sm:col-span-5 flex flex-col items-center justify-center p-3.5 rounded-xl bg-[#0A101D] border border-emerald-500/30 shadow-inner">
                            <div class="p-2.5 bg-white rounded-2xl shadow-xl inline-block">
                                <img src="../assets/images/qr_line_group.jpg" alt="LINE Group QR Code LEQs-xAI" class="w-36 h-36 sm:w-40 sm:h-40 object-contain rounded-xl">
                            </div>
                            <span class="text-[11px] text-slate-400 mt-2 font-rajdhani font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-qrcode text-emerald-400"></i> สแกน QR Code ด้วย LINE
                            </span>
                        </div>

                        <!-- Instructions & Direct Link -->
                        <div class="sm:col-span-7 space-y-3.5">
                            <div class="space-y-2 text-xs text-slate-300">
                                <div class="flex items-start gap-2 bg-[#0A101D]/70 p-2.5 rounded-xl border border-[#1B2842]">
                                    <i class="fa-solid fa-desktop text-emerald-400 mt-0.5 text-xs shrink-0"></i>
                                    <span><strong>บนคอมพิวเตอร์:</strong> เปิดแอปพลิเคชัน LINE บนมือถือ แล้วเปิดกล้องสแกน QR Code ด้านซ้าย</span>
                                </div>
                                <div class="flex items-start gap-2 bg-[#0A101D]/70 p-2.5 rounded-xl border border-[#1B2842]">
                                    <i class="fa-solid fa-mobile-screen-button text-cyan-400 mt-0.5 text-xs shrink-0"></i>
                                    <span><strong>บนมือถือ:</strong> แตะปุ่มสีเขียวด้านล่างเพื่อเปิดและเข้าร่วมกลุ่มไลน์ทันที</span>
                                </div>
                            </div>

                            <!-- Cyber Green Join Button -->
                            <a href="https://line.me/R/ti/g/svZ-F2msk-" target="_blank" rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-[#06C755] hover:bg-[#05b34c] text-slate-950 font-kanit font-bold text-xs sm:text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.01] transition-all">
                                <i class="fa-brands fa-line text-lg text-white"></i>
                                <span>แตะที่นี่เพื่อเข้าร่วมกลุ่มไลน์ทันที (DIRECT JOIN)</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px] opacity-80 text-white"></i>
                            </a>

                            <!-- Mandatory Checkbox Confirmation -->
                            <div class="p-3.5 rounded-xl bg-emerald-500/10 border-2 border-emerald-500/40 transition-colors hover:bg-emerald-500/15">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input type="checkbox" id="line_group_joined" name="line_group_joined" required
                                           class="mt-0.5 w-4 h-4 rounded text-emerald-500 focus:ring-emerald-400 accent-emerald-500 cursor-pointer shrink-0">
                                    <span class="text-xs sm:text-sm font-semibold text-emerald-200 leading-snug">
                                        ข้าพเจ้าได้สแกน QR Code หรือกดเข้าร่วมกลุ่มไลน์ทางการ "LEQs-xAI Workshop" เรียบร้อยแล้ว <span class="text-rose-400 font-bold">* (จำเป็นต้องยืนยัน)</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Terms & Conditions (Outcome-Based Assessment Criteria) -->
                <div id="terms_condition_card" class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-[#111A2E]/95 via-[#0A1628]/90 to-[#0A101D] border-2 border-cyan-500/40 shadow-xl shadow-cyan-950/40 relative overflow-hidden transition-all duration-300">
                    <div class="absolute -top-12 -left-12 w-36 h-36 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-cyan-500/20">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 flex items-center justify-center font-bold text-lg shadow-lg shadow-cyan-500/20 shrink-0">
                                <i class="fa-solid fa-file-contract"></i>
                            </span>
                            <div>
                                <h3 class="font-kanit font-bold text-sm sm:text-base text-white flex items-center gap-2">
                                    เงื่อนไขและข้อตกลงการเข้าร่วมโครงการ (Outcome-Based Criteria)
                                </h3>
                                <p class="text-[11px] sm:text-xs text-cyan-300 font-rajdhani font-semibold">กรอบการประเมินผลลัพธ์เชิงประจักษ์เพื่อรับรองสมรรถนะ AIoT</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 text-[10px] font-orbitron font-bold shrink-0">
                            RBRU ACADEMIC STANDARD
                        </span>
                    </div>

                    <!-- 4 Core Criteria Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-300 mb-4">
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#0A101D]/80 border border-[#1B2842]">
                            <div class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 font-orbitron font-bold flex items-center justify-center shrink-0 text-xs">1</div>
                            <div>
                                <strong class="text-white font-kanit">เวลาเข้าร่วมกิจกรรมไม่น้อยกว่า 80%</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed font-sarabun">ต้องเข้าร่วมการอบรมเชิงปฏิบัติการครบตามกำหนด สแกน QR Code เช็คชื่อเพื่อบันทึกเวลาจริง</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#0A101D]/80 border border-[#1B2842]">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 font-orbitron font-bold flex items-center justify-center shrink-0 text-xs">2</div>
                            <div>
                                <strong class="text-white font-kanit">การทดสอบวัดผลสัมฤทธิ์ (Pre/Post Test)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed font-sarabun">ทำแบบทดสอบทั้งก่อนและหลังการอบรม โดยผ่านเกณฑ์ &ge; 70% หรือมี Normalized Gain &ge; 50%</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#0A101D]/80 border border-[#1B2842]">
                            <div class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-400 font-orbitron font-bold flex items-center justify-center shrink-0 text-xs">3</div>
                            <div>
                                <strong class="text-white font-kanit">ปฏิบัติการและส่งโครงงาน (Capstone Project)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed font-sarabun">ฝึกประกอบวงจร ต่อบอร์ด ESP32-S3 และส่งผลงานต้นแบบ AIoT ตามแทร็กที่เลือก</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#0A101D]/80 border border-[#1B2842]">
                            <div class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 font-orbitron font-bold flex items-center justify-center shrink-0 text-xs">4</div>
                            <div>
                                <strong class="text-white font-kanit">การนำไปใช้จริงในพื้นที่ (Field Deployment)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed font-sarabun">ยินยอมส่งภาพถ่ายหรือรายงานสรุปผลการนำไปติดตั้งใช้งานจริงในแปลงเกษตรหรือโรงเรียน</p>
                            </div>
                        </div>
                    </div>

                    <!-- Certification Note -->
                    <div class="p-3 rounded-xl bg-[#0A101D] border border-amber-500/30 text-[11px] sm:text-xs text-slate-300 mb-4 flex items-start gap-2.5">
                        <i class="fa-solid fa-award text-amber-400 text-sm mt-0.5 shrink-0"></i>
                        <span><strong>สิทธิ์ในการรับวุฒิบัตร (Certification):</strong> ผู้ที่ผ่านเกณฑ์ครบถ้วนจะได้รับ <strong>วุฒิบัตรรับรองสมรรถนะ AIoT (Certificate of Competence)</strong> ออกโดยคณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
                    </div>

                    <!-- Mandatory Agreement Checkbox -->
                    <div class="p-3.5 rounded-xl bg-cyan-500/10 border-2 border-cyan-500/40 transition-colors hover:bg-cyan-500/15">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" id="terms_agreed" name="terms_agreed" required
                                   class="mt-0.5 w-4 h-4 rounded text-cyan-500 focus:ring-cyan-400 accent-cyan-500 cursor-pointer shrink-0">
                            <span class="text-xs sm:text-sm font-semibold text-cyan-200 leading-snug">
                                ข้าพเจ้าได้อ่าน เข้าใจ และยอมรับเงื่อนไขการเข้าร่วมโครงการเพื่อการวัดผลสัมฤทธิ์ทุกประการ <span class="text-rose-400 font-bold">* (จำเป็นต้องยอมรับ)</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button — Cyber Futuristic Neon Button -->
                <div class="pt-4">
                    <button type="submit" id="btnSubmit" 
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-cyan-400 to-blue-500 text-slate-950 font-orbitron font-extrabold text-sm sm:text-base tracking-wider shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-3">
                        <i class="fa-solid fa-paper-plane text-slate-950"></i>
                        <span>CONFIRM REGISTRATION (ยืนยันการลงทะเบียน)</span>
                    </button>
                </div>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0C1322] border-t border-[#1B2842] py-6 text-center text-xs text-slate-500 font-rajdhani tracking-wide">
        LEQs-xAI Smart IoT Registration Portal © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU)
    </footer>

    <!-- Submit Logic -->
    <script>
    async function submitRegistration(e) {
        e.preventDefault();

        // 1. Mandatory check: Must join LINE Group
        const lineJoined = document.getElementById('line_group_joined');
        if (!lineJoined || !lineJoined.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเข้าร่วมกลุ่มไลน์ก่อน',
                html: '<div class="space-y-2 text-sm text-slate-300 text-left sm:text-center">' +
                      '<p>โครงการกำหนดให้ผู้สมัครทุกคนต้องสแกน QR Code หรือกดเข้าร่วมกลุ่มไลน์ทางการ <b>LEQs-xAI Workshop</b></p>' +
                      '<p class="text-emerald-400 text-xs font-semibold">เพื่อรับการแจ้งเตือนกำหนดการ ลิงก์สื่อการสอน และประสานงานกับทีมวิทยากร</p>' +
                      '</div>',
                confirmButtonColor: '#00E676',
                confirmButtonText: '<i class="fa-brands fa-line mr-1 text-slate-950"></i> <span class="text-slate-950 font-bold">ไปที่ QR Code กลุ่มไลน์</span>'
            }).then(() => {
                const card = document.getElementById('line_group_card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-4', 'ring-emerald-400', 'border-emerald-400');
                    setTimeout(() => card.classList.remove('ring-4', 'ring-emerald-400'), 3000);
                }
                if (lineJoined) lineJoined.focus();
            });
            return false;
        }

        // 2. Mandatory check: Must accept Terms & Conditions
        const termsAgreed = document.getElementById('terms_agreed');
        if (!termsAgreed || !termsAgreed.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณายอมรับเงื่อนไขโครงการ',
                html: '<div class="space-y-2 text-sm text-slate-300 text-left sm:text-center">' +
                      '<p>ผู้สมัครต้องรับทราบและยอมรับ <b>เงื่อนไขและข้อตกลงการเข้าร่วมโครงการ</b></p>' +
                      '<p class="text-cyan-400 text-xs font-semibold">(เวลาเรียน &ge; 80%, Pre/Post Test, ส่งโครงงาน Capstone และรายงานการนำไปใช้จริง)</p>' +
                      '</div>',
                confirmButtonColor: '#00C6FF',
                confirmButtonText: '<i class="fa-solid fa-check mr-1 text-slate-950"></i> <span class="text-slate-950 font-bold">ไปที่กล่องเงื่อนไข</span>'
            }).then(() => {
                const card = document.getElementById('terms_condition_card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-4', 'ring-cyan-400', 'border-cyan-400');
                    setTimeout(() => card.classList.remove('ring-4', 'ring-cyan-400'), 3000);
                }
                if (termsAgreed) termsAgreed.focus();
            });
            return false;
        }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> PROCESSING REGISTRATION...';

        const payload = {
            prefix: document.getElementById('prefix').value,
            fullname: document.getElementById('fullname').value,
            target_group: document.getElementById('target_group').value,
            organization: document.getElementById('organization').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            project_track: document.getElementById('project_track').value,
            line_group_joined: true,
            terms_agreed: true
        };

        try {
            const res = await fetch('../api/api.php?action=register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'ลงทะเบียนสำเร็จ!',
                    html: '<div class="space-y-3 text-sm text-slate-300">' +
                          '<p>ยินดีต้อนรับเข้าสู่โครงการอบรม LEQs-xAI ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</p>' +
                          '<div class="p-3 bg-emerald-950/60 rounded-xl border border-emerald-500/40 text-emerald-300 text-xs">' +
                          '<i class="fa-brands fa-line text-base mr-1"></i> หากท่านยังไม่ได้เข้าร่วมกลุ่มไลน์ กรุณากดปุ่มด้านล่างนี้' +
                          '</div>' +
                          '</div>',
                    showCancelButton: true,
                    confirmButtonColor: '#00C6FF',
                    cancelButtonColor: '#06C755',
                    confirmButtonText: '<span class="text-slate-950 font-bold"><i class="fa-solid fa-list mr-1"></i> ดูรายชื่อผู้สมัคร</span>',
                    cancelButtonText: '<span class="text-white font-bold"><i class="fa-brands fa-line mr-1"></i> เปิดกลุ่มไลน์ทันที</span>'
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.open('https://line.me/R/ti/g/svZ-F2msk-', '_blank');
                        setTimeout(() => { window.location.href = 'student_list.php'; }, 1000);
                    } else {
                        window.location.href = 'student_list.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถลงทะเบียนได้ กรุณาลองใหม่อีกครั้ง',
                    confirmButtonColor: '#FF3366'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> CONFIRM REGISTRATION (ยืนยันการลงทะเบียน)';
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'การเชื่อมต่อผิดพลาด',
                text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ กรุณาตรวจสอบอินเทอร์เน็ตหรือบริการเว็บเซิร์ฟเวอร์',
                confirmButtonColor: '#FF3366'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> CONFIRM REGISTRATION (ยืนยันการลงทะเบียน)';
        }
    }
    </script>
    <?php require_once __DIR__ . '/../includes/bottom_nav.php'; ?>
</body>
</html>
