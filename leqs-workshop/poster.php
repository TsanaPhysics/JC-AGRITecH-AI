<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปสเตอร์ประชาสัมพันธ์ | LEQs-xAI ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม 2026</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Kanit:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Sarabun', 'sans-serif'],
                        heading: ['Kanit', 'sans-serif'],
                        tech: ['Outfit', 'monospace'],
                    },
                    colors: {
                        brand: {
                            emerald: '#10B981',
                            cyan: '#06B6D4',
                            amber: '#F59E0B',
                            dark: '#0f172a'
                        }
                    }
                }
            }
        };
    </script>
    
    <style>
        /* Printable A4 Container Rules */
        .poster-a4 {
            width: 860px;
            min-height: 1216px;
            margin: 0 auto;
            position: relative;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }

        .poster-story {
            width: 540px;
            min-height: 960px;
            margin: 0 auto;
            position: relative;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .poster-a4, .poster-story {
                width: 100% !important;
                min-height: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }

        .gradient-text-emerald {
            background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans py-6 px-3 min-h-screen">

    <!-- Top Action Toolbar (No Print) -->
    <header class="no-print max-w-4xl mx-auto mb-6 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-md border border-gray-200 flex flex-wrap items-center justify-between gap-3 sticky top-3 z-50">
        <div class="flex items-center gap-3">
            <a href="index.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 text-xs font-bold transition">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <span class="text-xs text-gray-400">|</span>
            <span class="font-heading font-bold text-sm text-gray-800 flex items-center gap-1.5">
                <i class="fa-solid fa-bullhorn text-emerald-600"></i> โปสเตอร์ประชาสัมพันธ์รับสมัคร 2026
            </span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Aspect Ratio Switcher -->
            <button onclick="setPosterMode('a4')" id="btnModeA4" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-file-lines"></i> ขนาดพิมพ์ A4
            </button>
            <button onclick="setPosterMode('story')" id="btnModeStory" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs flex items-center gap-1.5 transition">
                <i class="fa-solid fa-mobile-screen"></i> ขนาด Story (9:16)
            </button>

            <!-- Video Promo Page Link -->
            <a href="promo_video.php" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-film"></i> วิดีโอสปอตประชาสัมพันธ์
            </a>

            <!-- Print / Save Button -->
            <button onclick="window.print()" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition">
                <i class="fa-solid fa-print"></i> พิมพ์โปสเตอร์ (Print/PDF)
            </button>
        </div>
    </header>

    <!-- Poster Content Container -->
    <main id="posterContainer" class="poster-a4 rounded-3xl overflow-hidden border border-gray-200 flex flex-col justify-between relative">
        
        <!-- Header Ribbon Strip -->
        <div class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 py-2 px-6 text-white flex items-center justify-between text-xs font-semibold">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-microchip text-amber-300"></i>
                <strong class="text-amber-300">LEQs xAI</strong>
                <span class="text-cyan-200 font-semibold">Digital Agri-Envi</span>
                <span>คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
            </div>
            <div class="flex items-center gap-2 font-mono text-[10px] bg-white/20 px-2.5 py-0.5 rounded-full">
                <i class="fa-solid fa-certificate text-amber-300"></i> วุฒิบัตรรับรอง 18 ชั่วโมง
            </div>
        </div>

        <div class="p-8 sm:p-10 space-y-6 flex-1 flex flex-col justify-between">
            
            <!-- Top Logo & Title Grid -->
            <div>
                <div class="flex items-center justify-between gap-4 border-b border-gray-100 pb-5">
                    <div class="flex items-center gap-4">
                        <img src="assets/images/scirbru_logo.png" alt="RBRU Sci Logo" class="h-16 w-auto object-contain drop-shadow">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-widest block font-tech">FACULTY OF SCIENCE &amp; TECHNOLOGY, RBRU</span>
                            <h2 class="text-xl sm:text-2xl font-black text-gray-900 font-heading leading-tight">
                                เปิดรับสมัครผู้เข้าร่วมโครงการอบรมเชิงปฏิบัติการ
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">การเรียนรู้เชิงรุก (Active Learning) ผสานเทคโนโลยีและปัญญาประดิษฐ์ฝังตัว</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-block px-3 py-1 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold font-heading">
                            🎯 รับจำนวนจำกัด • ฟรีตลอดการอบรม
                        </span>
                    </div>
                </div>

                <!-- Main Highlight Headline -->
                <div class="mt-4 text-center space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        โครงการพัฒนาสมรรถนะเยาวชน ครู และเกษตรกรสู่ศตวรรษที่ 21
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold font-heading tracking-tight leading-tight">
                        <span class="text-gray-900">LEQs-xAI : </span>
                        <span class="gradient-text-emerald">ปัญญาประดิษฐ์ฝังตัว</span>
                        <span class="block text-2xl sm:text-3xl md:text-4xl text-amber-500 font-bold mt-0.5">เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 max-w-2xl mx-auto leading-relaxed">
                        หลักสูตรเข้มข้น 3 วัน 18 ชั่วโมง ทดลองจริงกับฮาร์ดแวร์อัจฉริยะ <strong>ESP32-S3 ATD3.5 Smart Touch</strong> และโพรบวัดดินสแตนเลส <strong>RS485 Modbus 7-in-1</strong>
                    </p>
                </div>
            </div>

            <!-- 3 Target Audiences -->
            <div class="grid grid-cols-3 gap-2.5">
                <div class="p-3 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 text-center space-y-1 shadow-xs">
                    <div class="w-9 h-9 mx-auto rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <h4 class="font-bold text-xs text-emerald-900 font-heading">นักเรียน มัธยมศึกษา</h4>
                    <p class="text-[10px] text-gray-600 leading-tight">ม.1 - ม.6 / ปวช. ต่อยอดโครงงานวิทย์ สู่พอร์ตฟอลิโอ TCAS</p>
                </div>

                <div class="p-3 rounded-2xl bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200 text-center space-y-1 shadow-xs">
                    <div class="w-9 h-9 mx-auto rounded-xl bg-cyan-600 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <h4 class="font-bold text-xs text-cyan-900 font-heading">ครูและอาจารย์</h4>
                    <p class="text-[10px] text-gray-600 leading-tight">กลุ่มสาระวิทย์/คอมพิวเตอร์ อัปสกิล ว PA ประยุกต์สอนจริง</p>
                </div>

                <div class="p-3 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 text-center space-y-1 shadow-xs">
                    <div class="w-9 h-9 mx-auto rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h4 class="font-bold text-xs text-amber-900 font-heading">เกษตรกร &amp; ผู้สนใจ</h4>
                    <p class="text-[10px] text-gray-600 leading-tight">เกษตรกรยุคใหม่ ลดต้นทุนปุ๋ย เพิ่มผลผลิตทุเรียนและผลไม้</p>
                </div>
            </div>

            <!-- 7 Modules & 6 Capstone Tracks Grid (Arabic Numerals) -->
            <div class="bg-slate-900 rounded-3xl p-4 sm:p-5 text-white shadow-xl space-y-3.5 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- 7 Modules Bar -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 font-tech font-bold text-[10px] border border-emerald-400/30">
                            7 โมดูลปฏิบัติการเข้มข้น (18 ชั่วโมง Active Learning 70%)
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Hands-on Hardware Lab</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-1.5 text-center">
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-emerald-400">M1 • 2h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">AIoT &amp; นิเวศวิจัย</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-cyan-400">M2 • 3h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">โพรบดิน RS485</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-blue-400">M3 • 3h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">VPD อากาศ &amp; SHT45</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-purple-400">M4 • 3h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">Edge TinyML</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-rose-400">M5 • 3h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">กล้อง AI Vision</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-slate-700">
                            <span class="text-[9px] font-mono font-bold text-amber-400">M6 • 2h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">Dashboard &amp; App</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/90 border border-emerald-400/50 bg-emerald-950/40">
                            <span class="text-[9px] font-mono font-bold text-emerald-300">M7 • 2h</span>
                            <div class="text-[11px] font-bold text-white leading-tight">Pitching Capstone</div>
                        </div>
                    </div>
                </div>

                <!-- 6 Capstone Tracks -->
                <div class="pt-1 border-t border-slate-800">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-cyan-400/20 text-cyan-300 font-tech font-bold text-[10px] border border-cyan-400/30">
                            6 แทร็กโครงงานนวัตกรรม Capstone (เลือกพัฒนาตามความถนัด)
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Tracks A - F</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-1.5 text-center">
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-cyan-300 font-bold">Track A</span>
                            <div class="text-[10px] font-semibold text-gray-200">ระบบให้น้ำ 2 ระดับ</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-emerald-300 font-bold">Track B</span>
                            <div class="text-[10px] font-semibold text-gray-200">AI ตรวจโรคพืช</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-amber-300 font-bold">Track C</span>
                            <div class="text-[10px] font-semibold text-gray-200">VPD &amp; คาร์บอนสวน</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-purple-300 font-bold">Track D</span>
                            <div class="text-[10px] font-semibold text-gray-200">TinyML บนชิป</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-blue-300 font-bold">Track E</span>
                            <div class="text-[10px] font-semibold text-gray-200">คุณภาพน้ำ DO บ่อปลา</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/70 border border-slate-700">
                            <span class="text-[9px] font-mono text-rose-300 font-bold">Track F</span>
                            <div class="text-[10px] font-semibold text-gray-200">สมองกลสั่งการฟาร์ม</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 5+ iSmart App Smartphone Showcase & 2+ XR Labs -->
            <div class="p-4 sm:p-5 rounded-3xl bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 border-2 border-emerald-500/40 text-white shadow-xl space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-300 font-tech font-bold text-[10px] border border-emerald-400/30">
                            5+ iSmart App สมาร์ทโฟน &amp; 2+ XR Virtual Labs
                        </span>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono">Mobile App • Vision • 3D XR Simulation</span>
                </div>

                <!-- 5 Smartphone Frames Grid (Real app screenshots from /cmu_aiot, /soil_app, /SoilpHTxAI, /NPxAI) -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-1">
                    
                    <!-- App 1: Soil Parameter Detector & pH AI (from /06_AI_Research/soil_app/) -->
                    <div class="bg-slate-900/90 rounded-2xl p-2 border border-emerald-500/40 text-center space-y-1 group">
                        <!-- Realistic Smartphone Frame -->
                        <div class="relative w-full aspect-[9/18] rounded-[1.2rem] overflow-hidden border-2 border-slate-700 bg-black shadow-lg">
                            <div class="absolute top-1 left-1/2 -translate-x-1/2 w-8 h-2 rounded-full bg-slate-800 z-10"></div>
                            <img src="assets/images/apps/app_soil_parameter.png" alt="Soil Parameter Detector" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-[10px] font-bold text-emerald-300 truncate">1. Soil Detector AI</div>
                        <div class="text-[8px] text-gray-400 leading-tight">วัด pH, NPK &amp; สภาพดินแม่นยำ</div>
                    </div>

                    <!-- App 2: Soil Colorimetric (from /06_AI_Research/soil_app/) -->
                    <div class="bg-slate-900/90 rounded-2xl p-2 border border-cyan-500/40 text-center space-y-1 group">
                        <div class="relative w-full aspect-[9/18] rounded-[1.2rem] overflow-hidden border-2 border-slate-700 bg-black shadow-lg">
                            <div class="absolute top-1 left-1/2 -translate-x-1/2 w-8 h-2 rounded-full bg-slate-800 z-10"></div>
                            <img src="assets/images/apps/app_soil_colorimetric.png" alt="Colorimetric Vision" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-[10px] font-bold text-cyan-300 truncate">2. Soil Colorimeter</div>
                        <div class="text-[8px] text-gray-400 leading-tight">วิเคราะห์สีเนื้อดินด้วยสมาร์ทโฟน</div>
                    </div>

                    <!-- App 3: NPxAI Soil Analyzer (from /06_AI_Research/NPxAI/) -->
                    <div class="bg-slate-900/90 rounded-2xl p-2 border border-amber-500/40 text-center space-y-1 group">
                        <div class="relative w-full aspect-[9/18] rounded-[1.2rem] overflow-hidden border-2 border-slate-700 bg-black shadow-lg">
                            <div class="absolute top-1 left-1/2 -translate-x-1/2 w-8 h-2 rounded-full bg-slate-800 z-10"></div>
                            <img src="assets/images/apps/app_npxai.png" alt="NPxAI" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-[10px] font-bold text-amber-300 truncate">3. NPxAI Analyzer</div>
                        <div class="text-[8px] text-gray-400 leading-tight">AI พยากรณ์ N-P-K &amp; ปุ๋ยแม่นยำ</div>
                    </div>

                    <!-- App 4: Plant AI Vision (from /cmu_aiot/) -->
                    <div class="bg-slate-900/90 rounded-2xl p-2 border border-purple-500/40 text-center space-y-1 group">
                        <div class="relative w-full aspect-[9/18] rounded-[1.2rem] overflow-hidden border-2 border-slate-700 bg-black shadow-lg">
                            <div class="absolute top-1 left-1/2 -translate-x-1/2 w-8 h-2 rounded-full bg-slate-800 z-10"></div>
                            <img src="assets/images/apps/app_plant_ai.png" alt="Plant AI" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-[10px] font-bold text-purple-300 truncate">4. Plant-AI Vision</div>
                        <div class="text-[8px] text-gray-400 leading-tight">สแกนวินิจฉัยโรคพืชด้วย AI</div>
                    </div>

                    <!-- App 5: DO Water Quality (from /cmu_aiot/) -->
                    <div class="bg-slate-900/90 rounded-2xl p-2 border border-blue-500/40 text-center space-y-1 group col-span-2 sm:col-span-1">
                        <div class="relative w-full aspect-[9/18] rounded-[1.2rem] overflow-hidden border-2 border-slate-700 bg-black shadow-lg">
                            <div class="absolute top-1 left-1/2 -translate-x-1/2 w-8 h-2 rounded-full bg-slate-800 z-10"></div>
                            <img src="assets/images/apps/app_do_meter.png" alt="DO Meter" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="text-[10px] font-bold text-blue-300 truncate">5. DO Water Quality</div>
                        <div class="text-[8px] text-gray-400 leading-tight">วัดคุณภาพน้ำ &amp; ออกซิเจนละลาย</div>
                    </div>

                </div>

                <!-- 2+ XR Virtual Labs Bar -->
                <div class="pt-2 border-t border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                        <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs shrink-0 font-bold">XR1</span>
                        <div>
                            <div class="font-bold text-white text-[11px]">VPD Microclimate Simulator</div>
                            <div class="text-[9px] text-gray-300">จำลองการคายน้ำของพืชตามหลักอุณหพลศาสตร์ 60 FPS</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                        <span class="w-7 h-7 rounded-lg bg-cyan-600 text-white flex items-center justify-center text-xs shrink-0 font-bold">XR2</span>
                        <div>
                            <div class="font-bold text-white text-[11px]">3D Soil Probe Virtual Lab</div>
                            <div class="text-[9px] text-gray-300">จำลองหัวโพรบสแตนเลสและชั้นดิน 3 มิติ Interactive</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Complete 6 Faculty Members / Instructors (ครบทั้ง 6 ท่านพร้อมภาพถ่าย) -->
            <div class="bg-gray-50 border border-gray-200 rounded-3xl p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        <h4 class="font-bold text-sm text-gray-900 font-heading">
                            ทีมวิทยากรและคณาจารย์ผู้ทรงคุณวุฒิ (ครบ 6 ท่าน)
                        </h4>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                        มรภ.รำไพพรรณี จันทบุรี
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-6 gap-2.5 text-center">
                    
                    <!-- 1. Asst. Prof. Dr. Chewa Thassana (วิทยากรหลัก) -->
                    <div class="p-2 rounded-2xl bg-white border-2 border-emerald-400 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_chewa.png" alt="ผศ.ดร.ชีวะ ทัศนา" class="w-full h-full object-cover rounded-full ring-2 ring-emerald-400">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-amber-500 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">วิทยากรหลัก</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">ผศ.ดร.ชีวะ ทัศนา</div>
                        <div class="text-[8px] text-emerald-700 font-semibold leading-tight">TinyML &amp; AIoT</div>
                    </div>

                    <!-- 2. Asst. Prof. Atthakorn Khamchat (วิทยากรหลัก) -->
                    <div class="p-2 rounded-2xl bg-white border-2 border-cyan-400 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_attaporn.png" alt="ผศ.อรรถกร คำฉัตร" class="w-full h-full object-cover rounded-full ring-2 ring-cyan-400">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-amber-500 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">วิทยากรหลัก</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">ผศ.อรรถกร คำฉัตร</div>
                        <div class="text-[8px] text-cyan-700 font-semibold leading-tight">Climate &amp; Envi</div>
                    </div>

                    <!-- 3. Assoc. Prof. Dr. Niphat Jongsawat -->
                    <div class="p-2 rounded-2xl bg-white border border-purple-200 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_niphat.png" alt="รศ.ดร.นิภัทร จงสวัสดิ์" class="w-full h-full object-cover rounded-full ring-2 ring-purple-300">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">ทีมวิทยากร</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">รศ.ดร.นิภัทร จงสวัสดิ์</div>
                        <div class="text-[8px] text-purple-700 leading-tight">Data Science &amp; AI</div>
                    </div>

                    <!-- 4. Asst. Prof. Dr. Jirapat Janthamalee -->
                    <div class="p-2 rounded-2xl bg-white border border-blue-200 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_jiraporn.png" alt="ผศ.ดร.จิรภัทร จันทมาลี" class="w-full h-full object-cover rounded-full ring-2 ring-blue-300">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">ทีมวิทยากร</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">ผศ.ดร.จิรภัทร จันทมาลี</div>
                        <div class="text-[8px] text-blue-700 leading-tight">Hydrosphere &amp; น้ำ</div>
                    </div>

                    <!-- 5. Asst. Prof. Dr. Nantaporn Poojaroen -->
                    <div class="p-2 rounded-2xl bg-white border border-emerald-200 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_nantaporn.png" alt="ผศ.ดร.นันทพร ภู่เจริญ" class="w-full h-full object-cover rounded-full ring-2 ring-emerald-300">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-emerald-600 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">ทีมวิทยากร</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">ผศ.ดร.นันทพร ภู่เจริญ</div>
                        <div class="text-[8px] text-emerald-700 leading-tight">Biosphere &amp; เกษตร</div>
                    </div>

                    <!-- 6. Asst. Prof. Dr. Namonruk Boonmee -->
                    <div class="p-2 rounded-2xl bg-white border border-amber-200 shadow-xs space-y-1">
                        <div class="relative w-14 h-14 mx-auto">
                            <img src="assets/images/trainers/speaker_namonruk.png" alt="ผศ.ดร.ณมนรัก บุญมี" class="w-full h-full object-cover rounded-full ring-2 ring-amber-300">
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-amber-600 text-white text-[7px] font-bold px-1.5 py-0.2 rounded-full whitespace-nowrap">ทีมวิทยากร</span>
                        </div>
                        <div class="text-[11px] font-bold text-gray-900 truncate">ผศ.ดร.ณมนรัก บุญมี</div>
                        <div class="text-[8px] text-amber-700 leading-tight">Pedosphere &amp; ดิน</div>
                    </div>

                </div>
            </div>

            <!-- Workshop Schedule, Location, and QR Code Registration -->
            <div class="grid grid-cols-12 gap-3.5 items-center bg-gray-50 border border-gray-200 rounded-3xl p-4 sm:p-5">
                
                <!-- Schedule & Venue (Col 1-8) -->
                <div class="col-span-8 space-y-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        <h4 class="font-bold text-sm text-gray-900 font-heading">กำหนดการและสถานที่จัดงาน</h4>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div class="p-2.5 rounded-xl bg-white border border-gray-200 space-y-0.5">
                            <div class="text-[10px] text-gray-500 flex items-center gap-1 font-semibold">
                                <i class="fa-solid fa-calendar-days text-emerald-600"></i> วันที่จัดกิจกรรม:
                            </div>
                            <div class="font-bold text-gray-900 text-sm">28 - 30 พฤศจิกายน 2569</div>
                            <div class="text-[10px] text-gray-500">รวม 3 วันเต็ม (18 ชั่วโมงการเรียนรู้)</div>
                        </div>

                        <div class="p-2.5 rounded-xl bg-white border border-gray-200 space-y-0.5">
                            <div class="text-[10px] text-gray-500 flex items-center gap-1 font-semibold">
                                <i class="fa-solid fa-location-dot text-rose-500"></i> สถานที่จัดอบรม:
                            </div>
                            <div class="font-bold text-gray-900 text-sm">คณะวิทยาศาสตร์และเทคโนโลยี</div>
                            <div class="text-[10px] text-gray-500">มหาวิทยาลัยราชภัฏรำไพพรรณี จ.จันทบุรี</div>
                        </div>
                    </div>
                </div>

                <!-- Registration QR Code (Col 9-12) -->
                <div class="col-span-4 flex flex-col items-center justify-center p-3 rounded-2xl bg-white border-2 border-emerald-400 shadow-md text-center">
                    <span class="text-[10px] font-bold text-emerald-700 mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-qrcode"></i> สแกนเพื่อลงทะเบียนทันที
                    </span>
                    <img src="assets/images/qr_leqs-agri-workshop.png" alt="Register QR Code" class="w-24 h-24 object-contain">
                    <span class="text-[9px] font-mono text-gray-500 mt-0.5">scicenter.rbru.ac.th/leqs-workshop</span>
                    <a href="pages/register.php" class="mt-1.5 w-full py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] transition text-center shadow-xs">
                        กดเพื่อสมัครออนไลน์ ฟรี
                    </a>
                </div>

            </div>

        </div>

        <!-- Footer Strip -->
        <div class="w-full bg-slate-950 text-slate-400 py-3 px-8 text-[11px] flex flex-wrap items-center justify-between border-t border-slate-800">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-phone text-emerald-400"></i>
                <span>สอบถามข้อมูลเพิ่มเติม: <strong>093 7422654</strong> (ผศ.ดร.ชีวะ ทัศนา)</span>
                <span class="text-slate-600">•</span>
                <span>chewa.t@rbru.ac.th</span>
            </div>
            <div>
                <span>คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี จันทบุรี</span>
            </div>
        </div>

    </main>

    <!-- Script to toggle A4 vs Story Mode -->
    <script>
        function setPosterMode(mode) {
            const container = document.getElementById('posterContainer');
            const btnA4 = document.getElementById('btnModeA4');
            const btnStory = document.getElementById('btnModeStory');

            if (mode === 'story') {
                container.className = 'poster-story rounded-3xl overflow-hidden border border-gray-200 flex flex-col justify-between relative';
                btnStory.className = 'px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition';
                btnA4.className = 'px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs flex items-center gap-1.5 transition';
            } else {
                container.className = 'poster-a4 rounded-3xl overflow-hidden border border-gray-200 flex flex-col justify-between relative';
                btnA4.className = 'px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition';
                btnStory.className = 'px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs flex items-center gap-1.5 transition';
            }
        }
    </script>
</body>
</html>
