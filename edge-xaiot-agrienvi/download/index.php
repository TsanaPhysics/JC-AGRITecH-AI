<?php
/**
 * ============================================================================
 * Edge xAI : LEQs-4D-AgriEnvi Mobile Application Download Portal
 * Mobile App Release Hub for Android (.apk) & iOS (.ipa / PWA)
 * ผศ.ดร.ชีวะ ทัศนา | มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU)
 * ============================================================================
 */

$apkFileName = 'Edge_xAI_LEQs-4D_AgriEnvi_v1.0.apk';
$apkPath = __DIR__ . '/' . $apkFileName;
$apkSize = file_exists($apkPath) ? round(filesize($apkPath) / (1024 * 1024), 1) . ' MB' : '28.6 MB';

$ipaFileName = 'Edge_xAI_LEQs-4D_AgriEnvi_v1.0.ipa';
$ipaPath = __DIR__ . '/' . $ipaFileName;
$ipaSize = file_exists($ipaPath) ? round(filesize($ipaPath) / (1024 * 1024), 1) . ' MB' : 'พร้อมดาวน์โหลด';

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$currentDir = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
$downloadApkUrl = $baseUrl . $currentDir . '/' . $apkFileName;
$downloadIpaUrl = $baseUrl . $currentDir . '/' . $ipaFileName;
$pwaUrl = $baseUrl . '/edge-xaiot-agrienvi/mobile/index.php';
?>
<!DOCTYPE html>
<html lang="th" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ดาวน์โหลดแอปมือถือ — Edge xAI : LEQs-4D-AgriEnvi</title>
    
    <!-- TailwindCSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Orbitron:wght@600;800;900&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        tech: ['"Chakra Petch"', 'sans-serif'],
                        orbitron: ['"Orbitron"', 'sans-serif'],
                        rajdhani: ['"Rajdhani"', 'sans-serif'],
                    },
                    colors: {
                        obsidian: '#080d1a',
                        cardBg: '#0f172a',
                        neonGreen: '#00ff66',
                        neonCyan: '#00f0ff',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: radial-gradient(circle at 50% 0%, #132338 0%, #070d18 70%, #040810 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }
        .glow-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px -10px rgba(0, 240, 255, 0.15);
        }
        .glow-btn-green {
            background: linear-gradient(135deg, #00ff66 0%, #00cc55 100%);
            box-shadow: 0 0 20px rgba(0, 255, 102, 0.4);
        }
        .glow-btn-cyan {
            background: linear-gradient(135deg, #00f0ff 0%, #0099ff 100%);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        }
        .glow-btn-purple {
            background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.4);
        }
    </style>
</head>
<body class="font-tech flex flex-col items-center justify-start p-4 sm:p-8">

    <!-- Top Navigation -->
    <header class="w-full max-w-4xl flex items-center justify-between py-3 mb-6 border-b border-white/10">
        <a href="../dashboard/index.php" class="flex items-center gap-2.5 text-cyan-400 hover:text-cyan-300 transition group">
            <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition"></i>
            <span class="font-bold text-sm tracking-wide">กลับสู่แดชบอร์ด</span>
        </a>
        <div class="flex items-center gap-2 text-xs font-mono text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            ESP32 Live Sync: 14.207.141.164
        </div>
    </header>

    <!-- App Title & Hero -->
    <div class="w-full max-w-4xl text-center space-y-3 mb-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-cyan-950/60 border border-cyan-500/30 text-cyan-300 text-xs font-mono font-bold">
            <i class="fa-solid fa-microchip text-emerald-400"></i> OFFICIAL MOBILE RELEASES v1.0.0
        </div>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-white to-emerald-400 font-orbitron tracking-tight">
            Edge xAI : LEQs-4D-AgriEnvi
        </h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto">
            แอปพลิเคชันมือถือควบคุมสมาร์ทฟาร์มและวิเคราะห์สภาพแวดล้อมดิจิทัล ด้วย Edge AI & Dual-Engine Telemetry<br>
            <span class="text-slate-300 font-semibold">ผศ.ดร.ชีวะ ทัศนา</span> · มหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU)
        </p>
    </div>

    <!-- Download Cards Grid -->
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- ========================================== -->
        <!-- ANDROID DOWNLOAD CARD                      -->
        <!-- ========================================== -->
        <div class="glow-card rounded-3xl p-6 sm:p-7 flex flex-col justify-between border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400 text-2xl shadow-inner">
                            <i class="fa-brands fa-android"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white font-orbitron">Android</h2>
                            <p class="text-xs text-emerald-400 font-mono">APK Package (Release)</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono bg-slate-800 text-slate-300 px-2.5 py-1 rounded-lg border border-slate-700">
                        ขนาด: <?= $apkSize ?>
                    </span>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed">
                    ไฟล์ติดตั้ง APK ตรงสำหรับสมาร์ทโฟน Android ทุกรุ่น (Samsung, Xiaomi, OPPO, Vivo, Realme, ฯลฯ) ไม่ต้องใช้ Play Store
                </p>

                <!-- Installation Instructions -->
                <div class="bg-slate-900/80 rounded-2xl p-3.5 border border-white/5 space-y-2 text-[11px] text-slate-300">
                    <div class="font-bold text-emerald-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check"></i> ขั้นตอนการติดตั้งง่ายๆ บน Android:
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-slate-400 pl-1">
                        <li>กดปุ่มดาวน์โหลดไฟล์ <code class="text-emerald-300 font-mono">.apk</code> ด้านล่าง</li>
                        <li>เมื่อดาวน์โหลดเสร็จ กดเปิดไฟล์แล้วเลือก <strong class="text-white">"ติดตั้ง (Install)"</strong></li>
                        <li>หากมีแจ้งเตือน ให้เลือก <strong class="text-white">"อนุญาตจากแหล่งนี้ (Allow from this source)"</strong></li>
                        <li>เปิดแอปและเริ่มใช้งานควบคุมบอร์ดได้ทันที!</li>
                    </ol>
                </div>
            </div>

            <!-- Download Button -->
            <div class="pt-6">
                <a href="<?= htmlspecialchars($apkFileName) ?>" download="<?= htmlspecialchars($apkFileName) ?>" class="glow-btn-green w-full py-3.5 px-6 rounded-2xl text-slate-950 font-bold text-sm sm:text-base flex items-center justify-center gap-2.5 hover:scale-[1.02] active:scale-[0.98] transition shadow-lg">
                    <i class="fa-solid fa-download text-lg"></i>
                    <span>ดาวน์โหลด Android APK (v1.0.0)</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- IOS DOWNLOAD & INSTALL CARD                -->
        <!-- ========================================== -->
        <div class="glow-card rounded-3xl p-6 sm:p-7 flex flex-col justify-between border-cyan-500/30 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-cyan-400 text-2xl shadow-inner">
                            <i class="fa-brands fa-apple"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white font-orbitron">iPhone / iPad</h2>
                            <p class="text-xs text-cyan-400 font-mono">iOS Package & Web App</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono bg-slate-800 text-slate-300 px-2.5 py-1 rounded-lg border border-slate-700">
                        iOS 15.0+
                    </span>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed">
                    รองรับทั้งการติดตั้งผ่านไฟล์ IPA (Sideloadly / AltStore / Scarlet) หรือเปิดใช้เป็นแอปเต็มจอผ่าน Safari ทันที
                </p>

                <!-- 2 Install Options for iOS -->
                <div class="bg-slate-900/80 rounded-2xl p-3.5 border border-white/5 space-y-2 text-[11px] text-slate-300">
                    <div class="font-bold text-cyan-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt"></i> วิธีใช้งานบน iPhone:
                    </div>
                    <div class="space-y-2 text-slate-400">
                        <div class="p-2 rounded-xl bg-cyan-950/40 border border-cyan-500/20">
                            <strong class="text-cyan-200">วิธีที่ 1 (เร็วที่สุด): เพิ่มไปยังหน้าจอโฮม (PWA)</strong>
                            <p class="text-[10.5px] mt-0.5">เปิด Safari ไปที่ลิงก์แอปเว็บ แล้วกดปุ่ม <strong>แชร์ (Share)</strong> &rarr; เลือก <strong>"เพิ่มไปยังหน้าจอโฮม (Add to Home Screen)"</strong> จะได้ไอคอนแอปเปิดเต็มจอทันที</p>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-800/60 border border-slate-700/50">
                            <strong class="text-slate-200">วิธีที่ 2: Sideload ไฟล์ .IPA</strong>
                            <p class="text-[10.5px] mt-0.5">ดาวน์โหลดไฟล์ <code class="text-cyan-300 font-mono">.ipa</code> แล้วติดตั้งผ่าน Sideloadly, AltStore, หรือ Apple Configurator ด้วย Apple ID ส่วนตัว</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Buttons -->
            <div class="pt-6 space-y-2.5">
                <a href="<?= htmlspecialchars($ipaFileName) ?>" download="<?= htmlspecialchars($ipaFileName) ?>" class="glow-btn-cyan w-full py-3.5 px-6 rounded-2xl text-slate-950 font-bold text-sm sm:text-base flex items-center justify-center gap-2.5 hover:scale-[1.02] active:scale-[0.98] transition shadow-lg">
                    <i class="fa-solid fa-file-arrow-down text-lg"></i>
                    <span>ดาวน์โหลด iOS Package (.ipa)</span>
                </a>
                <a href="<?= htmlspecialchars($pwaUrl) ?>" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold text-xs flex items-center justify-center gap-2 border border-cyan-500/20 transition">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    <span>เปิด Safari Web App เพื่อ Add to Home Screen</span>
                </a>
            </div>
        </div>

    </div>

    <!-- QR Code Scan & Share Card -->
    <div class="w-full max-w-4xl glow-card rounded-3xl p-6 sm:p-7 border border-white/10 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center sm:text-left flex-1">
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-cyan-950/80 text-cyan-300 text-xs font-mono border border-cyan-500/30">
                    <i class="fa-solid fa-qrcode"></i> SCAN FROM PHONE
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white font-orbitron">
                    สแกน QR Code ด้วยมือถือเพื่อเปิดหน้านี้ทันที
                </h3>
                <p class="text-xs text-slate-400">
                    เปิดกล้องมือถือแล้วส่องที่ QR Code ด้านขวา เพื่อเข้าสู่หน้าดาวน์โหลดนี้บนโทรศัพท์โดยตรง
                </p>
                <div class="text-[11px] font-mono text-cyan-300 bg-slate-950/70 p-2 rounded-xl border border-white/5 break-all">
                    <?= htmlspecialchars($baseUrl . $_SERVER['REQUEST_URI']) ?>
                </div>
            </div>

            <!-- QR Container -->
            <div class="p-3 bg-white rounded-2xl shadow-xl flex-shrink-0">
                <div id="qrcode" class="w-[140px] h-[140px]"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-4xl text-center py-4 border-t border-white/10 text-xs text-slate-500 space-y-1">
        <div>
            Edge xAI : LEQs-4D-AgriEnvi Smart Farm Control Ecosystem
        </div>
        <div>
            &copy; 2026 ผศ.ดร.ชีวะ ทัศนา · คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
        </div>
    </footer>

    <!-- Render QR Code Script -->
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: window.location.href,
            width: 140,
            height: 140,
            colorDark : "#080d1a",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
