<?php
/**
 * LEQs AIoT Application & Resource Portal
 * Academic & Workshop Download Center with Participant Registration & Real-time Analytics
 * มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU) - LEQs-xAI AgriPhysics
 */

$hostIp = '10.100.2.179'; // Detected Wi-Fi LAN IP
$currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
?>
<!DOCTYPE html>
<html lang="th" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ศูนย์ดาวน์โหลดแอปพลิเคชัน & APK | LEQs-xAI AgriPhysics RBRU</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            900: '#164e63',
                            950: '#083344',
                        },
                        agri: {
                            500: '#10b981',
                            600: '#059669',
                            950: '#022c22',
                        }
                    },
                    fontFamily: {
                        sans: ['Prompt', 'Sarabun', 'sans-serif'],
                        mono: ['JetBrains Mono', 'Fira Code', 'monospace']
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Prompt:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Prompt', 'Sarabun', sans-serif;
            background-color: #060913;
            color: #f1f5f9;
            background-image: 
                radial-gradient(at 0% 0%, rgba(6, 182, 212, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(139, 92, 246, 0.1) 0px, transparent 60%);
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card-hover:hover {
            transform: translateY(-4px);
            border-color: rgba(34, 211, 238, 0.4);
            box-shadow: 0 20px 35px -10px rgba(6, 182, 212, 0.2);
        }
        .neon-glow-cyan {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
        }
        .neon-glow-emerald {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.35);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #06b6d4;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-cyan-500 selection:text-black">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 glass-card border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-600 via-emerald-500 to-teal-400 p-0.5 shadow-lg shadow-cyan-500/20">
                    <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-cloud-arrow-down text-cyan-400 text-lg"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-white tracking-wide text-base sm:text-lg">LEQs App Portal</span>
                        <span class="px-2 py-0.5 text-[10px] font-mono bg-cyan-950 text-cyan-300 rounded border border-cyan-700/50">v2026.1</span>
                    </div>
                    <p class="text-xs text-slate-400 hidden sm:block">ศูนย์ดาวน์โหลดแอปพลิเคชัน & APK มหาวิทยาลัยราชภัฏรำไพพรรณี</p>
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="../aiot-fleet-dashboard/index.php" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 border border-slate-700 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-network-wired text-cyan-400"></i>
                    <span class="hidden md:inline">Fleet Dashboard</span>
                </a>
                <a href="../leqs-workshop/mobile/index.php" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700 border border-slate-700 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-mobile-screen text-emerald-400"></i>
                    <span class="hidden md:inline">Mobile Web</span>
                </a>
                <button onclick="openRegistrationModal()" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-black bg-gradient-to-r from-cyan-400 to-emerald-400 hover:from-cyan-300 hover:to-emerald-300 shadow-md shadow-cyan-500/20 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card"></i>
                    <span id="navRegBtnText">ลงทะเบียนผู้ใช้งาน</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Hero Section -->
        <section class="relative rounded-3xl p-6 sm:p-10 overflow-hidden border border-slate-700/60 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-cyan-950/40 glass-card">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-3 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        ศูนย์แจกจ่ายซอฟต์แวร์ & ไฟล์สำหรับการอบรมเชิงปฏิบัติการ Smart AgriPhysics
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        ดาวน์โหลดแอปพลิเคชัน & APK <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400">
                            LEQs-xAI Smart Farm & AI Vision
                        </span>
                    </h1>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        รวมไฟล์ติดตั้ง APK สมาร์ทโฟน Android และไฟล์ต้นฉบับ PlatformIO สำหรับผู้เข้าร่วมกิจกรรมทั้ง 15 กลุ่ม สามารถดาวน์โหลดติดตั้งได้จริง พร้อมระบบบันทึกหลักฐานการนำไปใช้ประโยชน์ทางวิชาการและนับจำนวนดาวน์โหลดอัตโนมัติ
                    </p>
                </div>

                <!-- Registration Quick Status Card -->
                <div id="participantStatusBox" class="w-full lg:w-80 flex-shrink-0 p-4 rounded-2xl bg-slate-950/70 border border-slate-700/80 shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-400">สถานะการลงทะเบียน</span>
                        <span id="regStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-mono bg-amber-950/90 text-amber-300 border border-amber-800">
                            ยังไม่ลงทะเบียน
                        </span>
                    </div>
                    <div id="regStatusContent" class="space-y-2">
                        <p class="text-xs text-slate-300 leading-snug">
                            กรุณาลงทะเบียนสั้นๆ เพื่อรับสิทธิ์ดาวน์โหลดและบันทึกเป็นหลักฐานการนำไปใช้
                        </p>
                        <button onclick="openRegistrationModal()" class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white font-semibold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> กรอกข้อมูลลงทะเบียน (1 นาที)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-8 pt-6 border-t border-slate-800 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-950/50 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-950 text-cyan-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-download"></i>
                    </div>
                    <div>
                        <div class="text-lg sm:text-2xl font-bold font-mono text-cyan-400" id="statTotalDownloads">0</div>
                        <div class="text-[11px] text-slate-400">ยอดดาวน์โหลดทั้งหมด</div>
                    </div>
                </div>

                <div class="bg-slate-950/50 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-950 text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-lg sm:text-2xl font-bold font-mono text-emerald-400" id="statTotalRegistered">0</div>
                        <div class="text-[11px] text-slate-400">ผู้ลงทะเบียนรับสิทธิ์</div>
                    </div>
                </div>

                <div class="bg-slate-950/50 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-950 text-violet-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                    <div>
                        <div class="text-lg sm:text-2xl font-bold font-mono text-violet-400">5 ชุด</div>
                        <div class="text-[11px] text-slate-400">แอป & ไฟล์ระบบพร้อมโหลด</div>
                    </div>
                </div>

                <div class="bg-slate-950/50 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-950 text-amber-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <div>
                        <div class="text-xs sm:text-sm font-bold font-mono text-amber-400 truncate"><?= htmlspecialchars($hostIp) ?></div>
                        <div class="text-[11px] text-slate-400">LAN Server Online</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App Catalog Section -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-cyan-400"></i>
                        รายการแอปพลิเคชันและแพ็กเกจไฟล์สำหรับติดตั้ง
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400">เลือกดาวน์โหลดไฟล์ APK ติดตั้งบนสมาร์ทโฟน หรือสแกน QR Code ด้วยกล้องมือถือได้ทันที</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="refreshCatalog()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs border border-slate-700 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-cyan-400" id="refreshIcon"></i> อัปเดตสถิติ
                    </button>
                    <a href="#analytics-section" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs border border-slate-700 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-simple text-emerald-400"></i> แดชบอร์ดความนิยม
                    </a>
                </div>
            </div>

            <!-- Apps Grid -->
            <div id="appsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Dynamically loaded via JavaScript -->
                <div class="col-span-full py-16 text-center text-slate-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-cyan-400 mb-3"></i>
                    <p>กำลังโหลดรายการแอปพลิเคชันและยอดดาวน์โหลด...</p>
                </div>
            </div>
        </section>

        <!-- Analytics & Research Evidence Section -->
        <section id="analytics-section" class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-400"></i>
                        ระบบสถิติความนิยม & บันทึกหลักฐานการนำไปใช้ (R&D Analytics)
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400">
                        ตรวจสอบความนิยมการดาวน์โหลดแต่ละแอป เพื่อประเมินแนวทางการพัฒนา และส่งออกรายงานหลักฐานทางวิชาการ
                    </p>
                </div>
                <!-- CSV Export Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="api.php?action=export_registrations_csv" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-300 border border-emerald-700/60 text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-file-excel text-emerald-400"></i> ส่งออกผู้ลงทะเบียน (CSV)
                    </a>
                    <a href="api.php?action=export_downloads_csv" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-cyan-700/60 text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-file-csv text-cyan-400"></i> ส่งออกประวัติดาวน์โหลด (CSV)
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- App Popularity Ranking -->
                <div class="lg:col-span-1 glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-fire text-amber-400"></i> อันดับความนิยมแอปพลิเคชัน
                        </h3>
                        <span class="text-[11px] text-slate-400">เรียงตามยอดโหลด</span>
                    </div>
                    <div id="popularityRankingList" class="space-y-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Group Distribution & Purpose -->
                <div class="lg:col-span-2 glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-people-group text-cyan-400"></i> การนำไปใช้ประโยชน์จำแนกตามกลุ่มอบรม (1-15)
                        </h3>
                        <span class="text-[11px] text-slate-400">สถิติกลุ่มผู้เข้าร่วม</span>
                    </div>
                    <div id="groupStatsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                        <!-- Populated by JS -->
                    </div>

                    <!-- Recent Downloads Table -->
                    <div class="pt-4 border-t border-slate-800/80">
                        <h4 class="text-xs font-bold text-slate-300 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> ประวัติการดาวน์โหลดล่าสุด
                        </h4>
                        <div class="overflow-x-auto max-h-48 overflow-y-auto">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead class="text-[11px] uppercase bg-slate-900/80 text-slate-400 sticky top-0">
                                    <tr>
                                        <th class="py-2 px-3">เวลา</th>
                                        <th class="py-2 px-3">แอปพลิเคชัน</th>
                                        <th class="py-2 px-3">ผู้ดาวน์โหลด</th>
                                        <th class="py-2 px-3">กลุ่ม/หน่วยงาน</th>
                                    </tr>
                                </thead>
                                <tbody id="recentLogsTbody" class="divide-y divide-slate-800/60 font-mono">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="glass-card border-t border-slate-800 mt-12 py-6 text-center text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-cyan-400"></i>
                <span>โครงการบูรณาการเกษตรอัจฉริยะ LEQs-xAI & AgriPhysics | มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
            </div>
            <div class="text-[11px] text-slate-400 font-mono">
                Host LAN: <?= htmlspecialchars($hostIp) ?> | Localhost: <?= htmlspecialchars($currentHost) ?>
            </div>
        </div>
    </footer>

    <!-- ================= REGISTRATION MODAL ================= -->
    <div id="registrationModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-card bg-slate-900/95 border border-cyan-500/40 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeRegistrationModal()" class="absolute right-5 top-5 w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="space-y-2 mb-6">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-medium border border-cyan-500/30">
                    <i class="fa-solid fa-shield-halved"></i> ยืนยันสิทธิ์และบันทึกหลักฐานการใช้งาน
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-white">ลงทะเบียนรับสิทธิ์ดาวน์โหลดแอป</h3>
                <p class="text-xs text-slate-400">
                    ข้อมูลนี้จะถูกนำไปใช้เป็นหลักฐานประกอบรายงานโครงการอบรมเชิงปฏิบัติการ และเพื่อวัดผลความนิยมในการพัฒนาต่อยอด
                </p>
            </div>

            <form id="registrationForm" onsubmit="handleRegistrationSubmit(event)" class="space-y-4">
                <input type="hidden" id="pendingDownloadAppId" value="">

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">
                        ชื่อ - นามสกุล <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="regFullName" required placeholder="เช่น นายสมชาย เกษตรก้าวหน้า" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">
                            สังกัด / หน่วยงาน / มหาวิทยาลัย <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="regOrg" required placeholder="เช่น มรภ.รำไพพรรณี / วิสาหกิจชุมชน" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">
                            กลุ่มที่เข้าร่วมอบรม <span class="text-rose-400">*</span>
                        </label>
                        <select id="regGroup" required 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                            <option value="กลุ่มที่ 1">กลุ่มที่ 1 (ESP32 Fleet Node 01)</option>
                            <option value="กลุ่มที่ 2">กลุ่มที่ 2 (ESP32 Fleet Node 02)</option>
                            <option value="กลุ่มที่ 3">กลุ่มที่ 3 (ESP32 Fleet Node 03)</option>
                            <option value="กลุ่มที่ 4">กลุ่มที่ 4 (ESP32 Fleet Node 04)</option>
                            <option value="กลุ่มที่ 5">กลุ่มที่ 5 (ESP32 Fleet Node 05)</option>
                            <option value="กลุ่มที่ 6">กลุ่มที่ 6 (ESP32 Fleet Node 06)</option>
                            <option value="กลุ่มที่ 7">กลุ่มที่ 7 (ESP32 Fleet Node 07)</option>
                            <option value="กลุ่มที่ 8">กลุ่มที่ 8 (ESP32 Fleet Node 08)</option>
                            <option value="กลุ่มที่ 9">กลุ่มที่ 9 (ESP32 Fleet Node 09)</option>
                            <option value="กลุ่มที่ 10">กลุ่มที่ 10 (ESP32 Fleet Node 10)</option>
                            <option value="กลุ่มที่ 11">กลุ่มที่ 11 (ESP32 Fleet Node 11)</option>
                            <option value="กลุ่มที่ 12">กลุ่มที่ 12 (ESP32 Fleet Node 12)</option>
                            <option value="กลุ่มที่ 13">กลุ่มที่ 13 (ESP32 Fleet Node 13)</option>
                            <option value="กลุ่มที่ 14">กลุ่มที่ 14 (ESP32 Fleet Node 14)</option>
                            <option value="กลุ่มที่ 15">กลุ่มที่ 15 (ESP32 Fleet Node 15)</option>
                            <option value="วิทยากรและผู้ช่วยสอน">วิทยากรและผู้ช่วยสอน (Instructor/TA)</option>
                            <option value="เกษตรกรและบุคคลทั่วไป">เกษตรกรและบุคคลทั่วไป (General Public)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">
                            หมายเลขโทรศัพท์ <span class="text-rose-400">*</span>
                        </label>
                        <input type="tel" id="regPhone" required placeholder="เช่น 081-234-5678" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">
                            อีเมล (สำหรับรับข่าวสารอัปเดต)
                        </label>
                        <input type="email" id="regEmail" placeholder="เช่น user@example.com" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">
                        วัตถุประสงค์การนำแอปพลิเคชันไปใช้ประโยชน์ <span class="text-rose-400">*</span>
                    </label>
                    <select id="regPurpose" required 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-sm">
                        <option value="เพื่อการอบรมเชิงปฏิบัติการ Smart AgriPhysics">เพื่อการอบรมเชิงปฏิบัติการ Smart AgriPhysics (ในงาน)</option>
                        <option value="เพื่อนำไปประยุกต์ใช้ในแปลงเกษตร/สวนผลไม้จริง">เพื่อนำไปประยุกต์ใช้ในแปลงเกษตร/สวนผลไม้จริง</option>
                        <option value="เพื่อการเรียนการสอนและพัฒนานักศึกษา">เพื่อการเรียนการสอนและพัฒนานักศึกษาในสถาบัน</option>
                        <option value="เพื่อการวิจัยและพัฒนาทางวิชาการ (R&D)">เพื่อการวิจัยและพัฒนาทางวิชาการ (R&D)</option>
                        <option value="เพื่อทดสอบและต่อยอดระบบ IoT/AIoT">เพื่อทดสอบและต่อยอดระบบ IoT/AIoT</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">
                        บันทึกเพิ่มเติม / ข้อเสนอแนะในการพัฒนา (Optional)
                    </label>
                    <textarea id="regNotes" rows="2" placeholder="เช่น อยากให้เพิ่มฟังก์ชันแจ้งเตือนผ่าน LINE หรือเซนเซอร์ชนิดอื่น..." 
                        class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-700 focus:border-cyan-400 focus:outline-none text-white text-xs"></textarea>
                </div>

                <div class="flex items-start gap-2 pt-2">
                    <input type="checkbox" id="regConsent" required checked class="mt-1 rounded bg-slate-950 border-slate-700 text-cyan-500 focus:ring-0">
                    <label for="regConsent" class="text-xs text-slate-400 leading-tight">
                        ข้าพเจ้ายินยอมบันทึกข้อมูลเพื่อเป็นหลักฐานการนำไปใช้ประโยชน์ในกิจกรรมวิชาการของ มรภ.รำไพพรรณี และยินยอมให้ระบบนับสถิติการดาวน์โหลด
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRegistrationModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition">
                        ยกเลิก
                    </button>
                    <button type="submit" id="regSubmitBtn" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-black font-bold text-xs shadow-lg shadow-cyan-500/30 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i> บันทึกข้อมูลและเริ่มดาวน์โหลด
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= QR CODE MODAL ================= -->
    <div id="qrModal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-card bg-slate-900 border border-cyan-500/50 rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl relative">
            <button onclick="closeQrModal()" class="absolute right-4 top-4 w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="w-12 h-12 rounded-2xl bg-cyan-950 text-cyan-400 flex items-center justify-center text-xl mx-auto border border-cyan-700/50">
                <i class="fa-solid fa-qrcode"></i>
            </div>

            <div>
                <h3 id="qrModalTitle" class="text-lg font-bold text-white">สแกนเพื่อดาวน์โหลดบนสมาร์ทโฟน</h3>
                <p id="qrModalSubtitle" class="text-xs text-slate-400">ใช้กล้องมือถือหรือแอป Line / Google Lens สแกนเพื่อโหลดและติดตั้งทันที</p>
            </div>

            <!-- QR Image Box -->
            <div class="p-3 bg-white rounded-2xl shadow-inner inline-block mx-auto border-4 border-cyan-500/20">
                <img id="qrModalImage" src="" alt="App Download QR Code" class="w-56 h-56 object-contain mx-auto">
            </div>

            <!-- URL Display and Copy -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 p-2 bg-slate-950 rounded-xl border border-slate-800 text-left">
                    <input type="text" id="qrModalUrlInput" readonly class="bg-transparent text-xs text-cyan-300 font-mono flex-1 focus:outline-none">
                    <button onclick="copyQrUrl()" class="px-2.5 py-1 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-semibold flex items-center gap-1 transition">
                        <i class="fa-solid fa-copy"></i> คัดลอก
                    </button>
                </div>
                <p class="text-[11px] text-slate-400">
                    เชื่อมต่อ Wi-Fi ในห้องอบรมเดียวกันเพื่อความเร็วในการดาวน์โหลดสูงสุด
                </p>
            </div>
        </div>
    </div>

    <!-- Script Application Logic -->
    <script>
        const LAN_IP = '<?= $hostIp ?>';
        const LOCAL_HOST = '<?= $currentHost ?>';
        let currentApps = [];
        let currentToken = localStorage.getItem('leqs_reg_token') || '';
        let currentParticipant = null;

        document.addEventListener('DOMContentLoaded', () => {
            initPortal();
        });

        async function initPortal() {
            // Check existing token
            if (currentToken) {
                await verifyToken(currentToken);
            } else {
                updateParticipantUI(null);
            }

            // Load Apps & Stats
            await loadCatalog();

            // Check URL params for pending download or registration trigger
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('require_reg') === '1') {
                const pendingApp = urlParams.get('app');
                openRegistrationModal(pendingApp);
            }
        }

        async function verifyToken(token) {
            try {
                const res = await fetch(`api.php?action=verify_token&token=${encodeURIComponent(token)}`);
                const data = await res.json();
                if (data.status === 'success' && data.valid) {
                    currentParticipant = data.user;
                    updateParticipantUI(currentParticipant);
                } else {
                    // Invalid token
                    localStorage.removeItem('leqs_reg_token');
                    currentToken = '';
                    updateParticipantUI(null);
                }
            } catch (err) {
                console.error("Token verification error:", err);
            }
        }

        function updateParticipantUI(user) {
            const statusBox = document.getElementById('participantStatusBox');
            const badge = document.getElementById('regStatusBadge');
            const content = document.getElementById('regStatusContent');
            const navBtnText = document.getElementById('navRegBtnText');

            if (user) {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-950/90 text-emerald-300 border border-emerald-700 font-bold';
                badge.innerHTML = '<i class="fa-solid fa-circle-check mr-1"></i> ยืนยันสิทธิ์แล้ว';
                
                content.innerHTML = `
                    <div class="space-y-1">
                        <div class="text-xs font-bold text-white truncate">${user.full_name}</div>
                        <div class="text-[11px] text-cyan-300 font-mono truncate">
                            ${user.workshop_group} | ${user.organization}
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono">
                            รหัส: <span class="text-amber-300 font-semibold">${user.reg_token}</span> (โหลดแล้ว ${user.download_count_user || 0} ครั้ง)
                        </div>
                    </div>
                    <button onclick="openRegistrationModal()" class="w-full mt-2 py-1.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition flex items-center justify-center gap-1">
                        <i class="fa-solid fa-user-pen"></i> แก้ไขข้อมูลผู้ลงทะเบียน
                    </button>
                `;
                navBtnText.textContent = user.full_name.split(' ')[0] || 'ข้อมูลผู้ลงทะเบียน';
            } else {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-mono bg-amber-950/90 text-amber-300 border border-amber-800';
                badge.textContent = 'ยังไม่ลงทะเบียน';
                
                content.innerHTML = `
                    <p class="text-xs text-slate-300 leading-snug">
                        กรุณาลงทะเบียนสั้นๆ เพื่อรับสิทธิ์ดาวน์โหลดและบันทึกเป็นหลักฐานการนำไปใช้
                    </p>
                    <button onclick="openRegistrationModal()" class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white font-semibold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i> กรอกข้อมูลลงทะเบียน (1 นาที)
                    </button>
                `;
                navBtnText.textContent = 'ลงทะเบียนผู้ใช้งาน';
            }
        }

        async function loadCatalog() {
            try {
                const res = await fetch('api.php?action=get_apps');
                const data = await res.json();
                if (data.status === 'success') {
                    currentApps = data.apps;
                    renderAppsGrid(data.apps);
                    
                    // Update stats
                    document.getElementById('statTotalDownloads').textContent = data.stats.total_downloads;
                    document.getElementById('statTotalRegistered').textContent = data.stats.total_registered;

                    // Load Analytics & Ranking
                    await loadAnalytics();
                }
            } catch (err) {
                console.error("Failed to load catalog:", err);
            }
        }

        function renderAppsGrid(apps) {
            const grid = document.getElementById('appsGrid');
            grid.innerHTML = '';

            apps.forEach(app => {
                const isApk = app.filename.endsWith('.apk');
                const card = document.createElement('div');
                card.className = 'glass-card glass-card-hover rounded-3xl p-6 border border-slate-800 flex flex-col justify-between relative overflow-hidden';
                
                // Highlights HTML
                const highlightsList = (app.highlights || []).map(h => `
                    <li class="flex items-start gap-2 text-xs text-slate-300">
                        <i class="fa-solid fa-circle-check text-emerald-400 mt-0.5 text-[11px] flex-shrink-0"></i>
                        <span>${h}</span>
                    </li>
                `).join('');

                card.innerHTML = `
                    <div>
                        <!-- Header Badges -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold" 
                                style="background-color: ${app.badge_color}20; color: ${app.badge_color}; border: 1px solid ${app.badge_color}50;">
                                ${app.badge_label}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-mono bg-slate-800 text-amber-300 border border-slate-700 flex items-center gap-1">
                                <i class="fa-solid fa-fire text-amber-400"></i> ${app.download_count} ครั้ง
                            </span>
                        </div>

                        <!-- App Icon & Title -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-lg"
                                style="background: linear-gradient(135deg, ${app.badge_color}30, #0f172a); border: 1px solid ${app.badge_color}60; color: ${app.badge_color};">
                                <i class="fa-solid ${app.icon_type}"></i>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-white leading-snug">${app.name}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">${app.sub_title}</p>
                            </div>
                        </div>

                        <!-- Meta Info Pills -->
                        <div class="flex flex-wrap items-center gap-2 mb-4 text-[11px] font-mono">
                            <span class="px-2 py-0.5 rounded bg-slate-950 text-cyan-300 border border-slate-800">
                                <i class="fa-solid fa-code-branch mr-1"></i> ${app.version}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-slate-950 text-emerald-300 border border-slate-800">
                                <i class="fa-solid fa-hard-drive mr-1"></i> ${app.size_formatted}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-slate-950 text-slate-300 border border-slate-800">
                                <i class="fa-solid fa-mobile-screen mr-1"></i> ${app.target_platform}
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-slate-300 leading-relaxed mb-4">
                            ${app.description}
                        </p>

                        <!-- Highlights -->
                        <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 mb-6 space-y-2">
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">จุดเด่นสำคัญ:</div>
                            <ul class="space-y-1.5">
                                ${highlightsList}
                            </ul>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-2 border-t border-slate-800/80">
                        <div class="grid grid-cols-5 gap-2">
                            <button onclick="requestDownload('${app.id}')" 
                                class="col-span-4 py-2.5 px-4 rounded-xl font-bold text-xs shadow-lg transition flex items-center justify-center gap-2"
                                style="background: linear-gradient(to right, ${app.badge_color}, #06b6d4); color: #000;">
                                <i class="fa-solid fa-download"></i>
                                <span>ดาวน์โหลด ${isApk ? 'APK ติดตั้ง' : 'ไฟล์ทั้งหมด'}</span>
                            </button>
                            <button onclick="showQrModal('${app.id}')" 
                                title="สแกน QR Code ด้วยมือถือ"
                                class="col-span-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center text-sm transition">
                                <i class="fa-solid fa-qrcode"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-slate-400 px-1 font-mono">
                            <span>ไฟล์: ${app.filename}</span>
                            <span>Direct File Stream</span>
                        </div>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        async function loadAnalytics() {
            try {
                const res = await fetch('api.php?action=get_stats');
                const data = await res.json();
                if (data.status === 'success') {
                    renderPopularityRanking(data.apps);
                    renderGroupStats(data.groups);
                    renderRecentLogs(data.recent_logs);
                }
            } catch (err) {
                console.error("Failed to load analytics:", err);
            }
        }

        function renderPopularityRanking(apps) {
            const list = document.getElementById('popularityRankingList');
            list.innerHTML = '';

            const maxDownloads = Math.max(...apps.map(a => a.download_count), 1);

            apps.forEach((app, idx) => {
                const percentage = Math.round((app.download_count / maxDownloads) * 100);
                const item = document.createElement('div');
                item.className = 'space-y-1.5 p-2 rounded-xl bg-slate-950/50 border border-slate-800/60';
                item.innerHTML = `
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-200 truncate flex items-center gap-1.5">
                            <span class="w-4 text-center font-mono font-bold ${idx === 0 ? 'text-amber-400' : 'text-slate-400'}">${idx + 1}.</span>
                            ${app.name}
                        </span>
                        <span class="font-mono font-bold text-cyan-400 ml-2 flex-shrink-0">${app.download_count} โหลด</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500" 
                            style="width: ${percentage}%; background-color: ${app.badge_color || '#06b6d4'};"></div>
                    </div>
                `;
                list.appendChild(item);
            });
        }

        function renderGroupStats(groups) {
            const grid = document.getElementById('groupStatsGrid');
            grid.innerHTML = '';

            // Ensure groups 1 to 15 exist in display
            for (let i = 1; i <= 15; i++) {
                const gName = `กลุ่มที่ ${i}`;
                const found = groups.find(g => g.workshop_group === gName) || { participant_count: 0, total_downloads: 0 };

                const card = document.createElement('div');
                const active = found.participant_count > 0;
                card.className = `p-2.5 rounded-xl border text-center transition ${active ? 'bg-cyan-950/30 border-cyan-500/40' : 'bg-slate-950/40 border-slate-800'}`;
                card.innerHTML = `
                    <div class="text-[11px] font-bold ${active ? 'text-cyan-300' : 'text-slate-400'}">กลุ่ม ${i}</div>
                    <div class="text-sm font-mono font-bold text-white my-0.5">${found.total_downloads || 0}</div>
                    <div class="text-[9px] text-slate-400">${found.participant_count} คน</div>
                `;
                grid.appendChild(card);
            }
        }

        function renderRecentLogs(logs) {
            const tbody = document.getElementById('recentLogsTbody');
            tbody.innerHTML = '';

            if (!logs || logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-slate-400 font-sans">ยังไม่มีประวัติการดาวน์โหลด</td></tr>`;
                return;
            }

            logs.forEach(log => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-800/40 transition';
                const time = log.downloaded_at ? log.downloaded_at.substring(11, 16) : '-';
                tr.innerHTML = `
                    <td class="py-1.5 px-3 text-slate-400">${time}</td>
                    <td class="py-1.5 px-3 text-cyan-300 font-semibold truncate max-w-[140px]">${log.app_name}</td>
                    <td class="py-1.5 px-3 text-slate-200 truncate max-w-[120px]">${log.full_name || 'บุคคลทั่วไป'}</td>
                    <td class="py-1.5 px-3 text-emerald-400 truncate max-w-[120px]">${log.workshop_group || '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function requestDownload(appId) {
            if (!currentToken) {
                // Not registered yet -> trigger registration modal with pending app
                openRegistrationModal(appId);
                return;
            }

            // Execute direct download with token
            initiateDownload(appId, currentToken);
        }

        function initiateDownload(appId, token) {
            const downloadUrl = `download.php?app=${encodeURIComponent(appId)}&token=${encodeURIComponent(token)}`;
            
            // Create hidden iframe or link to trigger binary stream
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.setAttribute('download', '');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Notify user
            showToast('เริ่มการดาวน์โหลดแล้ว... กรุณาตรวจสอบแท็บดาวน์โหลดในเบราว์เซอร์');

            // Refresh stats after a short delay
            setTimeout(() => {
                loadCatalog();
                if (currentToken) verifyToken(currentToken);
            }, 1200);
        }

        function openRegistrationModal(pendingAppId = '') {
            document.getElementById('pendingDownloadAppId').value = pendingAppId;
            
            // Pre-fill if already registered
            if (currentParticipant) {
                document.getElementById('regFullName').value = currentParticipant.full_name || '';
                document.getElementById('regOrg').value = currentParticipant.organization || '';
                document.getElementById('regGroup').value = currentParticipant.workshop_group || 'กลุ่มที่ 1';
                document.getElementById('regPhone').value = currentParticipant.phone || '';
                document.getElementById('regEmail').value = currentParticipant.email || '';
                document.getElementById('regPurpose').value = currentParticipant.purpose || '';
            }

            document.getElementById('registrationModal').classList.remove('hidden');
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').classList.add('hidden');
        }

        async function handleRegistrationSubmit(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('regSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...';

            const payload = {
                full_name: document.getElementById('regFullName').value.trim(),
                organization: document.getElementById('regOrg').value.trim(),
                workshop_group: document.getElementById('regGroup').value,
                phone: document.getElementById('regPhone').value.trim(),
                email: document.getElementById('regEmail').value.trim(),
                purpose: document.getElementById('regPurpose').value,
                notes: document.getElementById('regNotes').value.trim()
            };

            try {
                const res = await fetch('api.php?action=register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'success') {
                    currentToken = data.reg_token;
                    localStorage.setItem('leqs_reg_token', currentToken);
                    
                    // Set cookie for browser download stream verification
                    document.cookie = `leqs_reg_token=${currentToken}; path=/; max-age=2592000`; // 30 days

                    currentParticipant = {
                        ...payload,
                        reg_token: data.reg_token,
                        download_count_user: 0
                    };
                    updateParticipantUI(currentParticipant);
                    closeRegistrationModal();

                    showToast('ลงทะเบียนสำเร็จ! บันทึกรหัสรับสิทธิ์เรียบร้อยแล้ว');

                    // If user was attempting to download an app, launch it now!
                    const pendingApp = document.getElementById('pendingDownloadAppId').value;
                    if (pendingApp) {
                        setTimeout(() => {
                            initiateDownload(pendingApp, currentToken);
                        }, 500);
                    } else {
                        loadCatalog();
                    }
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถลงทะเบียนได้'));
                }
            } catch (err) {
                console.error("Registration error:", err);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> บันทึกข้อมูลและเริ่มดาวน์โหลด';
            }
        }

        function showQrModal(appId) {
            const app = currentApps.find(a => a.id === appId);
            if (!app) return;

            document.getElementById('qrModalTitle').textContent = `สแกนติดตั้ง: ${app.name}`;
            document.getElementById('qrModalSubtitle').textContent = `เวอร์ชัน ${app.version} | ขนาด ${app.size_formatted}`;

            // Build full LAN URL for mobile phone scan
            const tokenQuery = currentToken ? `&token=${encodeURIComponent(currentToken)}` : '';
            // Prefer LAN IP so mobile device on Wi-Fi reaches the server directly
            const targetHost = (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') ? LAN_IP : window.location.hostname;
            const fullDownloadUrl = `http://${targetHost}/handysense/app-portal/download.php?app=${encodeURIComponent(appId)}${tokenQuery}`;

            document.getElementById('qrModalUrlInput').value = fullDownloadUrl;
            
            // Generate QR code using QR server API
            const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=${encodeURIComponent(fullDownloadUrl)}`;
            document.getElementById('qrModalImage').src = qrImageUrl;

            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        function copyQrUrl() {
            const input = document.getElementById('qrModalUrlInput');
            input.select();
            navigator.clipboard.writeText(input.value);
            showToast('คัดลอกลิงก์ดาวน์โหลดแล้ว');
        }

        function refreshCatalog() {
            const icon = document.getElementById('refreshIcon');
            icon.classList.add('fa-spin');
            loadCatalog().finally(() => {
                setTimeout(() => icon.classList.remove('fa-spin'), 600);
            });
        }

        function showToast(msg) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 z-50 px-4 py-3 rounded-2xl bg-slate-900 border border-cyan-500/60 text-white text-xs shadow-2xl flex items-center gap-2 transform transition-all duration-300';
            toast.innerHTML = `<i class="fa-solid fa-bell text-cyan-400"></i> <span>${msg}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
