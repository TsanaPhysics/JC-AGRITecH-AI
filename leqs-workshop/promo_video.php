<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิดีโอประชาสัมพันธ์โมชันกราฟิก | LEQs-xAI ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม 2026</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&family=Orbitron:wght@600;800;900&display=swap" rel="stylesheet">
    
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
                        tech: ['Outfit', 'Orbitron', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            emerald: '#10B981',
                            cyan: '#06B6D4',
                            orange: '#F59E0B',
                            dark: '#020617'
                        }
                    }
                }
            }
        };
    </script>
    
    <style>
        /* Video Aspect Ratio Stages */
        .stage-16-9 {
            width: 1000px;
            max-width: 100%;
            aspect-ratio: 16 / 9;
        }

        .stage-9-16 {
            width: 480px;
            max-width: 100%;
            aspect-ratio: 9 / 16;
            max-height: 85vh;
        }

        .glow-emerald {
            filter: drop-shadow(0 0 25px rgba(16, 185, 129, 0.45));
        }

        .glow-cyan {
            filter: drop-shadow(0 0 25px rgba(6, 182, 212, 0.45));
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }

        .scanline-effect {
            animation: scanline 8s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white">

    <!-- Top Navigation Toolbar -->
    <header class="p-4 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <a href="index.php" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-gray-200 text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> กลับสู่หน้าหลัก
            </a>
            <a href="poster.php" class="px-3 py-1.5 rounded-xl bg-emerald-600/30 hover:bg-emerald-600/50 border border-emerald-500/40 text-emerald-300 text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-lines"></i> ดูโปสเตอร์ประชาสัมพันธ์
            </a>
        </div>

        <div class="flex items-center gap-2">
            <!-- Format Switcher -->
            <button onclick="setVideoFormat('16-9')" id="btnFmt169" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold transition flex items-center gap-1">
                <i class="fa-solid fa-tv"></i> จอกว้าง 16:9
            </button>
            <button onclick="setVideoFormat('9-16')" id="btnFmt916" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-bold transition flex items-center gap-1">
                <i class="fa-solid fa-mobile-screen"></i> มือถือ 9:16
            </button>

            <!-- Sound Toggle -->
            <button onclick="toggleAudio()" id="btnAudio" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-bold transition flex items-center gap-1">
                <i class="fa-solid fa-volume-high text-cyan-400" id="audioIcon"></i>
                <span id="audioText">เปิดเสียงเพลง</span>
            </button>

            <!-- Record Video Button -->
            <button onclick="toggleRecording()" id="btnRecord" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                <i class="fa-solid fa-circle text-[10px] text-white animate-pulse" id="recordDot"></i>
                <span id="recordText">บันทึกเป็นวิดีโอ (WebM)</span>
            </button>
        </div>
    </header>

    <!-- Main Video Stage Center Area -->
    <main class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6 overflow-hidden">
        
        <div id="videoStage" class="stage-16-9 bg-slate-900 rounded-3xl overflow-hidden border-2 border-slate-700/80 shadow-2xl relative flex flex-col justify-between transition-all duration-500 select-none">
            
            <!-- Canvas Overlay for Motion Graphics Particles and Recording Stream -->
            <canvas id="motionCanvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

            <!-- Ambient Scanline Effect -->
            <div class="absolute inset-0 opacity-15 bg-gradient-to-b from-transparent via-emerald-400/20 to-transparent h-20 w-full scanline-effect pointer-events-none z-10"></div>

            <!-- Top Stage Banner Bar -->
            <div class="relative z-20 flex items-center justify-between p-4 sm:p-6">
                <div class="flex items-center gap-3">
                    <img src="assets/images/nong_smartscience.png" class="w-10 h-10 rounded-full bg-slate-950 p-1 ring-2 ring-emerald-400/50 shadow-md">
                    <div>
                        <div class="font-tech font-bold text-xs sm:text-sm tracking-wider text-emerald-400">LEQs-xAI 2026 OFFICIAL TEASER</div>
                        <div class="text-[10px] text-gray-400">Faculty of Science &amp; Technology, RBRU</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span id="sceneBadge" class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 font-mono text-[10px] font-bold">
                        SCENE 01 / 09
                    </span>
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                </div>
            </div>

            <!-- DYNAMIC SCENE CONTAINER (Animated Transitions via JavaScript) -->
            <div id="sceneContent" class="relative z-20 flex-1 flex flex-col items-center justify-center p-6 text-center transition-all duration-700">
                <!-- Injected dynamically by renderScene() -->
            </div>

            <!-- Bottom Progress & Subtitle Bar -->
            <div class="relative z-20 p-4 sm:p-6 bg-gradient-to-t from-slate-950/90 via-slate-950/60 to-transparent">
                <!-- Timeline Progress Bar -->
                <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden mb-3">
                    <div id="progressBar" class="bg-gradient-to-r from-emerald-500 to-cyan-400 h-full w-0 transition-all duration-200"></div>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-400">
                    <div class="flex items-center gap-3">
                        <button onclick="prevScene()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-backward-step"></i>
                        </button>
                        <button onclick="togglePlay()" id="btnPlayPause" class="w-9 h-9 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold transition flex items-center justify-center text-sm shadow-md">
                            <i class="fa-solid fa-pause" id="playIcon"></i>
                        </button>
                        <button onclick="nextScene()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-forward-step"></i>
                        </button>
                        <span id="sceneTitleBottom" class="text-gray-300 font-semibold text-[11px] sm:text-xs">
                            บทนำ: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัล
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span id="timeCounter" class="font-mono text-[11px] text-emerald-400">00:00 / 00:36</span>
                        <a href="pages/register.php" class="px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                            สมัครเลย <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Bottom Scene Selector Thumbnails Strip -->
    <footer class="p-2 sm:p-3 bg-slate-900 border-t border-slate-800">
        <div id="sceneThumbnailsStrip" class="max-w-6xl mx-auto flex items-center justify-start md:justify-center gap-1.5 sm:gap-2 overflow-x-auto text-[11px] py-1 px-2 no-scrollbar">
            <!-- Dynamically populated by renderThumbnails() -->
        </div>
    </footer>

    <!-- Video Logic & Canvas Motion & Web Audio Synthesizer -->
    <script>
        // Comprehensive 9-Scene Definitions (7 Modules, 6 Capstones, 5+ iSmart Apps, 2+ XR Labs)
        const scenes = [
            {
                shortTitle: '1. บทนำ',
                title: 'บทนำ: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัล',
                duration: 6,
                render: () => `
                    <div class="space-y-4 animate-fade-in max-w-xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-mono border border-emerald-500/40">
                            <i class="fa-solid fa-seedling"></i> NEXT-GEN DIGITAL AGRI-TECH 2026
                        </span>
                        <h1 class="text-3xl sm:text-5xl font-black font-heading leading-tight tracking-tight">
                            เมื่อเกษตรกรรมยุคใหม่<br>
                            <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">
                                ขับเคลื่อนด้วย Edge TinyML
                            </span>
                        </h1>
                        <p class="text-sm sm:text-base text-gray-300 leading-relaxed font-sans">
                            โครงการอบรมเชิงปฏิบัติการ <strong>LEQs xAI Digital Agri-Envi</strong> คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                        </p>
                        <div class="pt-2 flex justify-center">
                            <img src="assets/images/soil_nutrient2026/portable_iot_pixar.png" class="w-28 h-28 sm:w-36 sm:h-36 object-contain glow-emerald animate-bounce">
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '2. เซนเซอร์ 4 มิติ',
                title: 'เซนเซอร์ 4 มิติ: ดิน อากาศ น้ำ และกล้อง AI',
                duration: 6,
                render: () => `
                    <div class="space-y-4 animate-fade-in max-w-2xl">
                        <span class="px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-mono border border-cyan-500/40">
                            FOUR ENVIRONMENTAL DIMENSIONS
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-white">
                            ตรวจวัดแม่นยำครบ 4 ปัจจัยสิ่งแวดล้อม
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                            <div class="p-3 rounded-2xl bg-emerald-950/70 border border-emerald-500/40 text-center space-y-1">
                                <i class="fa-solid fa-layer-group text-2xl text-emerald-400"></i>
                                <div class="font-bold text-xs text-white">มิติดิน</div>
                                <div class="text-[10px] text-gray-300">NPK, EC, pH 7-in-1 Modbus</div>
                            </div>
                            <div class="p-3 rounded-2xl bg-cyan-950/70 border border-cyan-500/40 text-center space-y-1">
                                <i class="fa-solid fa-cloud-sun text-2xl text-cyan-400"></i>
                                <div class="font-bold text-xs text-white">มิติอากาศ</div>
                                <div class="text-[10px] text-gray-300">VPD, SHT45, Temp &amp; PAR</div>
                            </div>
                            <div class="p-3 rounded-2xl bg-blue-950/70 border border-blue-500/40 text-center space-y-1">
                                <i class="fa-solid fa-droplet text-2xl text-blue-400"></i>
                                <div class="font-bold text-xs text-white">มิติน้ำ</div>
                                <div class="text-[10px] text-gray-300">DO ออกซิเจนละลาย &amp; คุณภาพน้ำ</div>
                            </div>
                            <div class="p-3 rounded-2xl bg-purple-950/70 border border-purple-500/40 text-center space-y-1">
                                <i class="fa-solid fa-camera text-2xl text-purple-400"></i>
                                <div class="font-bold text-xs text-white">มิติพืช</div>
                                <div class="text-[10px] text-gray-300">Edge AI กล้องสแกนโรคใบพืช</div>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '3. 7 โมดูลหลักสูตร',
                title: 'โครงสร้างหลักสูตร 7 Modules (Active Learning 70%)',
                duration: 7,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-2xl">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-mono border border-emerald-500/40">
                            18 HOURS COMPREHENSIVE CURRICULUM
                        </span>
                        <h2 class="text-xl sm:text-3xl font-extrabold font-heading text-white">
                            หลักสูตรเข้มข้น 7 โมดูล (Active Learning 70%)
                        </h2>
                        <div class="flex items-center justify-center gap-3 py-1">
                            <img src="assets/images/pixar_smart_farm_hero.jpg" class="w-20 h-14 sm:w-28 sm:h-18 object-cover rounded-xl border border-emerald-400/40 shadow-md">
                            <div class="text-left text-[11px] sm:text-xs text-slate-300 leading-snug">
                                <span class="text-emerald-400 font-bold">ฝึกปฏิบัติจริงกับบอร์ดทดลอง ESP32-S3 ATD3.5</span><br>
                                ตั้งแต่พื้นฐานไอโอที จนถึงดีพเลิร์นนิงและ TinyML บนชิป
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-left pt-1">
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-emerald-400 font-bold">Module 1 • 2h</span>
                                <div class="text-xs font-bold text-white truncate">บทนำ AIoT &amp; นิเวศวิจัย</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-cyan-400 font-bold">Module 2 • 3h</span>
                                <div class="text-xs font-bold text-white truncate">Digital Agri &amp; Modbus</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-blue-400 font-bold">Module 3 • 3h</span>
                                <div class="text-xs font-bold text-white truncate">Deep Learning CNN</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-purple-400 font-bold">Module 4 • 3h</span>
                                <div class="text-xs font-bold text-white truncate">Computer Vision YOLO</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-amber-400 font-bold">Module 5 • 3h</span>
                                <div class="text-xs font-bold text-white truncate">Edge AI TinyML ESP32</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700">
                                <span class="text-[9px] font-mono text-rose-400 font-bold">Module 6 • 2h</span>
                                <div class="text-xs font-bold text-white truncate">Environmental Carbon</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/80 border border-slate-700 col-span-2">
                                <span class="text-[9px] font-mono text-indigo-400 font-bold">Module 7 • 3h</span>
                                <div class="text-xs font-bold text-white truncate">Capstone Integrated Showcase</div>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '4. 6 โครงงาน Capstone',
                title: '6 แทร็กโครงงานนวัตกรรม (Capstone Tracks A - F)',
                duration: 8,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-3xl">
                        <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-mono border border-rose-500/40">
                            6 HANDS-ON CAPSTONE INNOVATION TRACKS
                        </span>
                        <h2 class="text-xl sm:text-3xl font-extrabold font-heading text-white">
                            6 โครงงานนวัตกรรมแก้โจทย์จริงในแปลงเกษตร
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 text-center pt-1">
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-cyan-500/40 space-y-1">
                                <img src="assets/images/pixar_track_a.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-cyan-300">Track A</div>
                                <div class="text-[9px] text-gray-300 leading-tight">Smart Agri IoT</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-emerald-500/40 space-y-1">
                                <img src="assets/images/pixar_track_b.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-emerald-300">Track B</div>
                                <div class="text-[9px] text-gray-300 leading-tight">Plant Vision AI</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-amber-500/40 space-y-1">
                                <img src="assets/images/pixar_track_c.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-amber-300">Track C</div>
                                <div class="text-[9px] text-gray-300 leading-tight">Environmental</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-purple-500/40 space-y-1">
                                <img src="assets/images/pixar_track_d.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-purple-300">Track D</div>
                                <div class="text-[9px] text-gray-300 leading-tight">TinyML ESP32</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-rose-500/40 space-y-1">
                                <img src="assets/images/pixar_track_e.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-rose-300">Track E</div>
                                <div class="text-[9px] text-gray-300 leading-tight">Aquaculture DO</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-800/90 border border-indigo-500/40 space-y-1">
                                <img src="assets/images/pixar_track_f.jpg" class="w-full aspect-square object-cover rounded-lg">
                                <div class="text-[10px] font-bold text-indigo-300">Track F</div>
                                <div class="text-[9px] text-gray-300 leading-tight">AI Decision</div>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '5. 5+ iSmart App',
                title: '5+ ระบบเชื่อมต่ออัจฉริยะ iSmart App & Smartphone Mobile Suite',
                duration: 8,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-3xl">
                        <span class="px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-mono border border-cyan-500/40">
                            5+ SMARTPHONE APPS &amp; MOBILE AI SUITE
                        </span>
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold font-heading text-white">
                            5+ นวัตกรรม iSmart App บนสมาร์ทโฟน
                        </h2>
                        
                        <!-- 5 Realistic Smartphone Screen Mockups -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 sm:gap-3 pt-1 justify-items-center">
                            
                            <!-- Phone 1: Soil Parameter Detector (SoilpHTxAI) -->
                            <div class="w-24 sm:w-28 md:w-32 rounded-[1.2rem] p-1 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-950 border border-emerald-500/40 shadow-xl flex flex-col group hover:scale-105 transition-all">
                                <div class="w-8 h-1 bg-black/80 rounded-full mx-auto mb-1"></div>
                                <div class="w-full h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-slate-950 border border-slate-700/50">
                                    <img src="assets/images/apps/app_soil_parameter.png" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-1 text-center">
                                    <div class="text-[10px] font-bold text-white truncate">Soil Parameter</div>
                                    <div class="text-[8px] text-emerald-400 truncate">pH, NPK &amp; สภาพดิน AI</div>
                                </div>
                            </div>

                            <!-- Phone 2: Soil Colorimetric -->
                            <div class="w-24 sm:w-28 md:w-32 rounded-[1.2rem] p-1 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-950 border border-cyan-500/40 shadow-xl flex flex-col group hover:scale-105 transition-all">
                                <div class="w-8 h-1 bg-black/80 rounded-full mx-auto mb-1"></div>
                                <div class="w-full h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-slate-950 border border-slate-700/50">
                                    <img src="assets/images/apps/app_soil_colorimetric.png" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-1 text-center">
                                    <div class="text-[10px] font-bold text-white truncate">Colorimetric AI</div>
                                    <div class="text-[8px] text-cyan-400 truncate">วิเคราะห์สีดิน Munsell</div>
                                </div>
                            </div>

                            <!-- Phone 3: NPxAI -->
                            <div class="w-24 sm:w-28 md:w-32 rounded-[1.2rem] p-1 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-950 border border-amber-500/40 shadow-xl flex flex-col group hover:scale-105 transition-all">
                                <div class="w-8 h-1 bg-black/80 rounded-full mx-auto mb-1"></div>
                                <div class="w-full h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-slate-950 border border-slate-700/50">
                                    <img src="assets/images/apps/app_npxai.png" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-1 text-center">
                                    <div class="text-[10px] font-bold text-white truncate">NPxAI Analyzer</div>
                                    <div class="text-[8px] text-amber-400 truncate">ธาตุอาหาร NPK ในดิน</div>
                                </div>
                            </div>

                            <!-- Phone 4: Plant AI Vision -->
                            <div class="w-24 sm:w-28 md:w-32 rounded-[1.2rem] p-1 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-950 border border-purple-500/40 shadow-xl flex flex-col group hover:scale-105 transition-all">
                                <div class="w-8 h-1 bg-black/80 rounded-full mx-auto mb-1"></div>
                                <div class="w-full h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-slate-950 border border-slate-700/50">
                                    <img src="assets/images/apps/app_plant_ai.png" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-1 text-center">
                                    <div class="text-[10px] font-bold text-white truncate">Plant AI Vision</div>
                                    <div class="text-[8px] text-purple-400 truncate">ตรวจโรคใบพืช &amp; AI</div>
                                </div>
                            </div>

                            <!-- Phone 5: DO Meter Water -->
                            <div class="w-24 sm:w-28 md:w-32 rounded-[1.2rem] p-1 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-950 border border-blue-500/40 shadow-xl flex flex-col group hover:scale-105 transition-all">
                                <div class="w-8 h-1 bg-black/80 rounded-full mx-auto mb-1"></div>
                                <div class="w-full h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-slate-950 border border-slate-700/50">
                                    <img src="assets/images/apps/app_do_meter.png" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-1 text-center">
                                    <div class="text-[10px] font-bold text-white truncate">DO Smart Meter</div>
                                    <div class="text-[8px] text-blue-400 truncate">คุณภาพน้ำ &amp; สัตว์น้ำ</div>
                                </div>
                            </div>

                        </div>

                        <!-- Full Ecosystem Strip -->
                        <div class="flex items-center justify-center gap-2 pt-1 text-[11px] text-slate-300 flex-wrap">
                            <span class="bg-slate-800 px-2 py-0.5 rounded border border-slate-700 text-cyan-300">
                                <i class="fa-solid fa-chart-line text-cyan-400"></i> Interactive Web Dashboard
                            </span>
                            <span class="bg-slate-800 px-2 py-0.5 rounded border border-slate-700 text-amber-300">
                                <i class="fa-solid fa-desktop text-amber-400"></i> ATD3.5 LCD Touchscreen
                            </span>
                            <span class="bg-slate-800 px-2 py-0.5 rounded border border-slate-700 text-emerald-300">
                                <i class="fa-solid fa-bell text-emerald-400"></i> LINE Alert &amp; CSV Export
                            </span>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '6. 2+ XR Virtual Labs',
                title: '2+ ห้องปฏิบัติการจำลองเสมือนจริง (Interactive XR Labs)',
                duration: 7,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-2xl">
                        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-mono border border-amber-500/40">
                            INTERACTIVE VIRTUAL LABORATORIES
                        </span>
                        <h2 class="text-xl sm:text-3xl font-extrabold font-heading text-white">
                            2+ ห้องปฏิบัติการจำลองเสมือนจริง (XR Labs)
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <div class="p-3 rounded-2xl bg-slate-800/90 border border-emerald-500/40 text-center space-y-1.5">
                                <img src="assets/images/card_soil_expert.png" class="w-16 h-16 mx-auto object-contain glow-emerald">
                                <div class="text-xs font-bold text-emerald-300">XR Lab 1: VPD Engine</div>
                                <div class="text-[10px] text-gray-300 leading-snug">
                                    จำลองการคายน้ำของพืช SHT45 &amp; VPD Microclimate แบบเรียลไทม์
                                </div>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-800/90 border border-sky-500/40 text-center space-y-1.5">
                                <img src="assets/images/card_do_meter.png" class="w-16 h-16 mx-auto object-contain glow-cyan">
                                <div class="text-xs font-bold text-sky-300">XR Lab 2: Aquaculture DO</div>
                                <div class="text-[10px] text-gray-300 leading-snug">
                                    สมดุลออกซิเจนละลาย Benson-Krause &amp; ประหยัดพลังงานกังหัน VFD
                                </div>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-800/90 border border-purple-500/40 text-center space-y-1.5">
                                <img src="assets/images/card_plant_ai.png" class="w-16 h-16 mx-auto object-contain glow-purple">
                                <div class="text-xs font-bold text-purple-300">Bonus XR: Vision TinyML</div>
                                <div class="text-[10px] text-gray-300 leading-snug">
                                    จำลองระบบวิทัศน์คอมพิวเตอร์และสแกนโรคพืชด้วย On-Device AI
                                </div>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '7. 3 กลุ่มเป้าหมาย',
                title: 'กลุ่มเป้าหมาย: 3 กลุ่มหลัก นักเรียน ครู เกษตรกร และผู้สนใจ',
                duration: 6,
                render: () => `
                    <div class="space-y-4 animate-fade-in max-w-2xl">
                        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-mono border border-amber-500/40">
                            INCLUSIVE ACTIVE LEARNING
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-white">
                            เปิดรับสมัคร 3 กลุ่มเป้าหมายสำคัญ
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-left">
                            <div class="p-4 rounded-2xl bg-slate-800/90 border border-slate-700 space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500 text-slate-950 flex items-center justify-center font-bold">1</div>
                                <h3 class="font-bold text-sm text-emerald-300">นักเรียนมัธยมศึกษา</h3>
                                <p class="text-[11px] text-gray-300">ม.1 - ม.6 / อาชีวะ สร้างพอร์ตฟอลิโอและต่อยอดโครงงานวิจัย</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-800/90 border border-slate-700 space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-cyan-500 text-slate-950 flex items-center justify-center font-bold">2</div>
                                <h3 class="font-bold text-sm text-cyan-300">ครูและบุคลากร</h3>
                                <p class="text-[11px] text-gray-300">กลุ่มสาระวิทย์/คอมพิวเตอร์ อัปสกิลนำไปประยุกต์สอนจริง</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-800/90 border border-slate-700 space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-500 text-slate-950 flex items-center justify-center font-bold">3</div>
                                <h3 class="font-bold text-sm text-amber-300">เกษตรกร &amp; ผู้สนใจ</h3>
                                <p class="text-[11px] text-gray-300">เกษตรกรยุคใหม่ ประยุกต์ใช้ลดต้นทุนปุ๋ย เพิ่มผลผลิตทุเรียน</p>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '8. ทีมวิทยากร 6 ท่าน',
                title: 'วิทยากรหลัก 2 ท่าน & ทีมวิทยากร 4 ท่าน รวม 6 ท่าน มรภ.รำไพพรรณี',
                duration: 8,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-3xl">
                        <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-mono border border-indigo-500/40">
                            ACADEMIC EXCELLENCE &amp; GLOBE FACULTY (6 EXPERTS)
                        </span>
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold font-heading text-white">
                            วิทยากรหลัก 2 ท่าน &amp; ทีมวิทยากร 4 ท่าน (รวม 6 ท่าน)
                        </h2>
                        
                        <!-- 2 Lead Instructors -->
                        <div class="flex items-center justify-center gap-6 pt-1">
                            <div class="text-center space-y-1">
                                <div class="relative inline-block">
                                    <img src="assets/images/trainers/speaker_chewa.png" class="w-14 h-14 sm:w-16 sm:h-16 md:w-18 md:h-18 rounded-full mx-auto object-cover ring-2 ring-emerald-400 shadow-lg">
                                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-amber-500 text-slate-950 font-black text-[9px] px-2 py-0.2 rounded-full whitespace-nowrap shadow">วิทยากรหลัก</span>
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-white pt-0.5">ผศ.ดร.ชีวะ ทัศนา</div>
                                <div class="text-[9px] sm:text-[10px] text-emerald-300">TinyML &amp; AIoT Architecture</div>
                            </div>
                            <div class="text-center space-y-1">
                                <div class="relative inline-block">
                                    <img src="assets/images/trainers/speaker_attaporn.png" class="w-14 h-14 sm:w-16 sm:h-16 md:w-18 md:h-18 rounded-full mx-auto object-cover ring-2 ring-cyan-400 shadow-lg">
                                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-amber-500 text-slate-950 font-black text-[9px] px-2 py-0.2 rounded-full whitespace-nowrap shadow">วิทยากรหลัก</span>
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-white pt-0.5">ผศ.อรรถกร คำฉัตร</div>
                                <div class="text-[9px] sm:text-[10px] text-cyan-300">Climate Change &amp; Environment</div>
                            </div>
                        </div>

                        <!-- 4 Co-Trainers Grid with Photos -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 max-w-2xl mx-auto">
                            <div class="p-2 rounded-xl bg-white/5 border border-white/10 text-center space-y-1">
                                <img src="assets/images/trainers/speaker_niphat.png" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mx-auto object-cover ring-1 ring-purple-400">
                                <div class="text-[11px] font-bold text-white truncate">รศ.ดร.นิภัทร จงสวัสดิ์</div>
                                <div class="text-[9px] text-purple-300 truncate">Atmosphere &amp; Data Science</div>
                            </div>
                            <div class="p-2 rounded-xl bg-white/5 border border-white/10 text-center space-y-1">
                                <img src="assets/images/trainers/speaker_jiraporn.png" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mx-auto object-cover ring-1 ring-blue-400">
                                <div class="text-[11px] font-bold text-white truncate">ผศ.ดร.จิรภัทร จันทมาลี</div>
                                <div class="text-[9px] text-blue-300 truncate">Hydrosphere &amp; Water Science</div>
                            </div>
                            <div class="p-2 rounded-xl bg-white/5 border border-white/10 text-center space-y-1">
                                <img src="assets/images/trainers/speaker_nantaporn.png" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mx-auto object-cover ring-1 ring-emerald-400">
                                <div class="text-[11px] font-bold text-white truncate">ผศ.ดร.นันทพร ภู่เจริญ</div>
                                <div class="text-[9px] text-emerald-300 truncate">Biosphere &amp; Agriculture</div>
                            </div>
                            <div class="p-2 rounded-xl bg-white/5 border border-white/10 text-center space-y-1">
                                <img src="assets/images/trainers/speaker_namonruk.png" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mx-auto object-cover ring-1 ring-amber-400">
                                <div class="text-[11px] font-bold text-white truncate">ผศ.ดร.ณมนรัก บุญมี</div>
                                <div class="text-[9px] text-amber-300 truncate">Pedosphere &amp; Soil Physics</div>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-semibold">
                            <i class="fa-solid fa-certificate text-amber-400"></i> ได้รับวุฒิบัตรรับรอง 18 ชั่วโมง เมื่อผ่านการอบรม
                        </div>
                    </div>
                `
            },
            {
                shortTitle: '9. สมัครออนไลน์',
                title: 'ลงทะเบียนด่วน: รับจำนวนจำกัด • ฟรีตลอดงาน',
                duration: 6,
                render: () => `
                    <div class="space-y-3 animate-fade-in max-w-xl">
                        <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-mono border border-rose-500/40">
                            LIMITED SEATS • REGISTER NOW
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-white">
                            กำหนดการ 28 - 30 พ.ย. 2569
                        </h2>
                        <div class="flex items-center justify-center gap-4 py-2">
                            <div class="p-2 rounded-2xl bg-white text-slate-900 shadow-xl inline-block">
                                <img src="assets/images/qr_leqs-agri-workshop.png" class="w-28 h-28 object-contain">
                            </div>
                        </div>
                        <div class="text-xs text-gray-300">
                            สแกน QR Code หรือเข้าสู่เว็บไซต์เพื่อสมัครทันที
                        </div>
                        <div class="pt-1">
                            <a href="pages/register.php" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-slate-950 font-black text-sm hover:scale-105 transition shadow-xl">
                                <i class="fa-solid fa-id-card"></i> สมัครเข้าร่วมอบรมฟรีที่นี่
                            </a>
                        </div>
                    </div>
                `
            }
        ];

        let currentScene = 0;
        let isPlaying = true;
        let sceneInterval = null;
        let sceneElapsed = 0;
        let totalElapsed = 0;
        const totalDuration = scenes.reduce((acc, s) => acc + s.duration, 0);

        function renderThumbnails() {
            const container = document.getElementById('sceneThumbnailsStrip');
            if (!container) return;
            container.innerHTML = scenes.map((sc, idx) => `
                <button onclick="jumpToScene(${idx})" id="thumb${idx}" class="scene-thumb px-2.5 sm:px-3 py-1.5 rounded-xl border ${idx === currentScene ? 'border-emerald-500 bg-emerald-500/20 text-emerald-300 font-bold' : 'border-slate-700 bg-slate-800 text-gray-400 hover:text-white'} whitespace-nowrap transition">
                    ${sc.shortTitle}
                </button>
            `).join('');
        }

        function renderScene() {
            const sc = scenes[currentScene];
            const contentBox = document.getElementById('sceneContent');
            const badge = document.getElementById('sceneBadge');
            const titleBottom = document.getElementById('sceneTitleBottom');

            if (contentBox) contentBox.innerHTML = sc.render();
            if (badge) badge.innerText = `SCENE 0${currentScene + 1} / 0${scenes.length}`;
            if (titleBottom) titleBottom.innerText = sc.title;

            // Update Thumbnails
            document.querySelectorAll('.scene-thumb').forEach((t, idx) => {
                if (idx === currentScene) {
                    t.className = 'scene-thumb px-2.5 sm:px-3 py-1.5 rounded-xl border border-emerald-500 bg-emerald-500/20 text-emerald-300 font-bold whitespace-nowrap transition';
                } else {
                    t.className = 'scene-thumb px-2.5 sm:px-3 py-1.5 rounded-xl border border-slate-700 bg-slate-800 text-gray-400 hover:text-white whitespace-nowrap transition';
                }
            });

            // Play transition chime if audio is enabled
            playChime();
        }

        function tick() {
            if (!isPlaying) return;

            sceneElapsed += 0.2;
            totalElapsed += 0.2;

            if (totalElapsed > totalDuration) {
                totalElapsed = 0;
                currentScene = 0;
                sceneElapsed = 0;
                renderScene();
            } else if (sceneElapsed >= scenes[currentScene].duration) {
                currentScene = (currentScene + 1) % scenes.length;
                sceneElapsed = 0;
                renderScene();
            }

            // Update progress bar
            const pct = (totalElapsed / totalDuration) * 100;
            const pb = document.getElementById('progressBar');
            if (pb) pb.style.width = pct + '%';

            // Update time counter
            const tc = document.getElementById('timeCounter');
            if (tc) {
                const curM = String(Math.floor(totalElapsed / 60)).padStart(2, '0');
                const curS = String(Math.floor(totalElapsed % 60)).padStart(2, '0');
                const totM = String(Math.floor(totalDuration / 60)).padStart(2, '0');
                const totS = String(Math.floor(totalDuration % 60)).padStart(2, '0');
                tc.innerText = `${curM}:${curS} / ${totM}:${totS}`;
            }
        }

        function togglePlay() {
            isPlaying = !isPlaying;
            const icon = document.getElementById('playIcon');
            if (icon) {
                icon.className = isPlaying ? 'fa-solid fa-pause' : 'fa-solid fa-play';
            }
        }

        function nextScene() {
            currentScene = (currentScene + 1) % scenes.length;
            sceneElapsed = 0;
            calculateTotalElapsed();
            renderScene();
        }

        function prevScene() {
            currentScene = (currentScene - 1 + scenes.length) % scenes.length;
            sceneElapsed = 0;
            calculateTotalElapsed();
            renderScene();
        }

        function jumpToScene(idx) {
            currentScene = idx;
            sceneElapsed = 0;
            calculateTotalElapsed();
            renderScene();
        }

        function calculateTotalElapsed() {
            totalElapsed = 0;
            for (let i = 0; i < currentScene; i++) {
                totalElapsed += scenes[i].duration;
            }
        }

        function setVideoFormat(fmt) {
            const stage = document.getElementById('videoStage');
            const btn169 = document.getElementById('btnFmt169');
            const btn916 = document.getElementById('btnFmt916');

            if (fmt === '9-16') {
                stage.className = 'stage-9-16 bg-slate-900 rounded-3xl overflow-hidden border-2 border-slate-700/80 shadow-2xl relative flex flex-col justify-between transition-all duration-500 select-none';
                btn916.className = 'px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold transition flex items-center gap-1';
                btn169.className = 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-bold transition flex items-center gap-1';
            } else {
                stage.className = 'stage-16-9 bg-slate-900 rounded-3xl overflow-hidden border-2 border-slate-700/80 shadow-2xl relative flex flex-col justify-between transition-all duration-500 select-none';
                btn169.className = 'px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold transition flex items-center gap-1';
                btn916.className = 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-gray-300 text-xs font-bold transition flex items-center gap-1';
            }
            resizeCanvas();
        }

        // ==========================================
        // WEB AUDIO API LIVE SYNTHESIZER
        // ==========================================
        let audioCtx = null;
        let isAudioEnabled = false;
        let audioLoopTimer = null;

        function initAudio() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
        }

        function playChime() {
            if (!isAudioEnabled || !audioCtx) return;
            try {
                const now = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();

                osc.type = 'sine';
                osc.frequency.setValueAtTime(523.25, now); // C5
                osc.frequency.exponentialRampToValueAtTime(1046.5, now + 0.3); // C6

                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);

                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now);
                osc.stop(now + 0.5);
            } catch (e) {}
        }

        function startAmbientBeat() {
            if (!audioCtx) initAudio();
            if (audioLoopTimer) clearInterval(audioLoopTimer);

            audioLoopTimer = setInterval(() => {
                if (!isAudioEnabled || !audioCtx) return;
                try {
                    const now = audioCtx.currentTime;
                    // Bass pulse
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(110, now); // A2
                    gain.gain.setValueAtTime(0.08, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(now);
                    osc.stop(now + 0.4);
                } catch (e) {}
            }, 800);
        }

        function toggleAudio() {
            initAudio();
            isAudioEnabled = !isAudioEnabled;
            const text = document.getElementById('audioText');
            const icon = document.getElementById('audioIcon');

            if (isAudioEnabled) {
                if (audioCtx.state === 'suspended') audioCtx.resume();
                text.innerText = 'ปิดเสียงเพลง';
                icon.className = 'fa-solid fa-volume-xmark text-rose-400';
                startAmbientBeat();
                playChime();
            } else {
                text.innerText = 'เปิดเสียงเพลง';
                icon.className = 'fa-solid fa-volume-high text-cyan-400';
                if (audioLoopTimer) clearInterval(audioLoopTimer);
            }
        }

        // ==========================================
        // CANVAS MOTION PARTICLES
        // ==========================================
        const canvas = document.getElementById('motionCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
        }

        function initParticles() {
            particles = [];
            for (let i = 0; i < 40; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    vx: (Math.random() - 0.5) * 0.8,
                    vy: (Math.random() - 0.5) * 0.8,
                    r: Math.random() * 2 + 1,
                    alpha: Math.random() * 0.5 + 0.2
                });
            }
        }

        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#10B981';

            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0) p.x = canvas.width;
                if (p.x > canvas.width) p.x = 0;
                if (p.y < 0) p.y = canvas.height;
                if (p.y > canvas.height) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.globalAlpha = p.alpha;
                ctx.fill();
            });

            requestAnimationFrame(drawParticles);
        }

        // ==========================================
        // BROWSER SCREEN RECORDER (DOWNLOAD WEBM)
        // ==========================================
        let mediaRecorder = null;
        let recordedChunks = [];
        let isRecording = false;

        async function toggleRecording() {
            if (!isRecording) {
                try {
                    const stream = canvas.captureStream(30);
                    recordedChunks = [];
                    mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm;codecs=vp9' });

                    mediaRecorder.ondataavailable = (e) => {
                        if (e.data.size > 0) recordedChunks.push(e.data);
                    };

                    mediaRecorder.onstop = () => {
                        const blob = new Blob(recordedChunks, { type: 'video/webm' });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'LEQs_xAI_Promo_Video_2026.webm';
                        a.click();
                        URL.revokeObjectURL(url);
                        alert('บันทึกและดาวน์โหลดวิดีโอ LEQs_xAI_Promo_Video_2026.webm สำเร็จแล้ว!');
                    };

                    mediaRecorder.start();
                    isRecording = true;

                    // Start from Scene 1
                    jumpToScene(0);
                    isPlaying = true;

                    document.getElementById('recordText').innerText = 'กำลังบันทึกคลิป (กดเพื่อหยุด)...';
                    document.getElementById('btnRecord').className = 'px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md animate-pulse';
                } catch (e) {
                    alert('เบราว์เซอร์ไม่รองรับการบันทึกแคนวาสอัตโนมัติ: ' + e.message);
                }
            } else {
                mediaRecorder.stop();
                isRecording = false;
                document.getElementById('recordText').innerText = 'บันทึกเป็นวิดีโอ (WebM)';
                document.getElementById('btnRecord').className = 'px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md';
            }
        }

        // Initialize on Load
        window.addEventListener('DOMContentLoaded', () => {
            resizeCanvas();
            initParticles();
            drawParticles();
            renderThumbnails();
            renderScene();
            setInterval(tick, 200);
        });

        window.addEventListener('resize', resizeCanvas);
    </script>
</body>
</html>
