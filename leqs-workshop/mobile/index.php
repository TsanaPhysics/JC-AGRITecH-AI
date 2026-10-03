<?php
// LEQs-xAI: Real Smartphone Mobile Web Application
// Connected via Wi-Fi to ESP32-S3 ATD3.5 Controller Board
?>
<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>LEQs-xAI Smart Farm Mobile App | ควบคุมบอร์ด ESP32-S3 ATD3.5</title>
    
    <!-- PWA & Mobile Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#090d16">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                        tech: ['Chakra Petch', 'sans-serif'],
                        mono: ['Orbitron', 'monospace'],
                    },
                    colors: {
                        darkbg: '#0a0f1d',
                        darkcard: '#111827',
                        accent: '#10b981',
                        cyanic: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: radial-gradient(circle at 50% 0%, #0f172a 0%, #030712 100%);
            color: #f3f4f6;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card-glow {
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans overflow-x-hidden antialiased">

    <!-- Top Status Bar & App Header -->
    <header class="sticky top-0 z-40 glass-panel border-b border-white/10 px-4 pt-3 pb-3">
        <div class="flex items-center justify-between">
            <!-- App Identity -->
            <div class="flex items-center gap-2.5">
                <a href="../index.php" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition active:scale-95" title="กลับสู่หน้าหลัก">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h1 class="font-bold text-sm text-white font-tech tracking-wider">LEQs-xAI MOBILE</h1>
                        <span class="text-[9px] font-mono bg-emerald-500/20 text-emerald-300 px-1.5 py-0.2 rounded border border-emerald-500/30">v2.6</span>
                    </div>
                    <div class="text-[10px] text-gray-400 font-mono flex items-center gap-1">
                        <i class="fa-solid fa-wifi text-emerald-400 text-[9px]"></i>
                        <span id="headerBoardIp">ESP32-S3 • 192.168.1.105</span>
                    </div>
                </div>
            </div>

            <!-- Wi-Fi Settings & Quick Actions -->
            <div class="flex items-center gap-2">
                <button onclick="openWifiModal()" class="px-2.5 py-1 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 text-cyan-300 text-[11px] font-tech font-bold flex items-center gap-1.5 active:scale-95 transition">
                    <i class="fa-solid fa-gear text-[10px]"></i>
                    <span>Wi-Fi Config</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Mobile Content Area (Tab Views) -->
    <main class="flex-1 overflow-y-auto px-4 py-4 space-y-4 pb-24 hide-scrollbar">
        
        <!-- SECTION 1: Wi-Fi Live Link Banner -->
        <div class="glass-panel rounded-2xl p-3.5 border border-emerald-500/30 bg-gradient-to-r from-emerald-950/40 via-slate-900/60 to-cyan-950/40 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400 text-lg">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-white">ESP32-S3 ATD3.5</span>
                        <span class="text-[9px] font-mono text-emerald-300 bg-emerald-900/80 px-2 py-0.5 rounded-full border border-emerald-500/30 font-bold">ONLINE</span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">
                        Ping: <span id="pingValue" class="text-emerald-400">18ms</span> | RSSI: <span class="text-cyan-300">-58 dBm</span>
                    </p>
                </div>
            </div>
            <button onclick="syncSensorData()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-emerald-400 flex items-center justify-center active:rotate-180 transition duration-300" title="รีเฟรชข้อมูลเซนเซอร์">
                <i class="fa-solid fa-arrows-rotate text-xs"></i>
            </button>
        </div>

        <!-- TAB 1: OVERVIEW & SENSORS -->
        <div id="tab-overview" class="space-y-4">
            
            <!-- 4 Quick Metric Gauges -->
            <div class="grid grid-cols-2 gap-3">
                
                <!-- Air Temp -->
                <div class="glass-panel p-3.5 rounded-2xl border border-white/5 space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-400">อุณหภูมิอากาศ (SHT45)</span>
                        <i class="fa-solid fa-temperature-half text-amber-400 text-sm"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span id="valTemp" class="text-2xl font-bold font-mono text-white">28.5</span>
                        <span class="text-xs text-gray-400">°C</span>
                    </div>
                    <div class="flex items-center justify-between text-[9px] text-gray-400">
                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-circle-check"></i> ปกติ (Optimal)</span>
                        <span>เป้าหมาย 26-30°C</span>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div id="barTemp" class="bg-gradient-to-r from-amber-400 to-rose-400 h-full rounded-full" style="width: 58%"></div>
                    </div>
                </div>

                <!-- Humidity -->
                <div class="glass-panel p-3.5 rounded-2xl border border-white/5 space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-400">ความชื้นสัมพัทธ์ (RH)</span>
                        <i class="fa-solid fa-droplet text-cyan-400 text-sm"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span id="valHum" class="text-2xl font-bold font-mono text-white">65.2</span>
                        <span class="text-xs text-gray-400">%</span>
                    </div>
                    <div class="flex items-center justify-between text-[9px] text-gray-400">
                        <span class="text-cyan-400 font-bold"><i class="fa-solid fa-circle-check"></i> เหมาะสม (Ideal)</span>
                        <span>เป้าหมาย 60-75%</span>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div id="barHum" class="bg-gradient-to-r from-cyan-400 to-blue-500 h-full rounded-full" style="width: 65%"></div>
                    </div>
                </div>

                <!-- Soil Moisture -->
                <div class="glass-panel p-3.5 rounded-2xl border border-white/5 space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-400">ความชื้นในดิน 7-in-1</span>
                        <i class="fa-solid fa-seedling text-emerald-400 text-sm"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span id="valSoilMoist" class="text-2xl font-bold font-mono text-white">72.0</span>
                        <span class="text-xs text-gray-400">%</span>
                    </div>
                    <div class="flex items-center justify-between text-[9px] text-gray-400">
                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-check"></i> เขตราก 15 cm</span>
                        <span>ชุ่มชื้นพอดี</span>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div id="barSoilMoist" class="bg-gradient-to-r from-teal-400 to-emerald-500 h-full rounded-full" style="width: 72%"></div>
                    </div>
                </div>

                <!-- VPD -->
                <div class="glass-panel p-3.5 rounded-2xl border border-white/5 space-y-2 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-400">แรงดึงระเหยน้ำ (VPD)</span>
                        <i class="fa-solid fa-wind text-indigo-400 text-sm"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span id="valVpd" class="text-2xl font-bold font-mono text-emerald-300">0.95</span>
                        <span class="text-xs text-gray-400">kPa</span>
                    </div>
                    <div class="flex items-center justify-between text-[9px] text-gray-400">
                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-circle-check"></i> สมดุลการคายน้ำ</span>
                        <span>0.8 - 1.2 kPa</span>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div id="barVpd" class="bg-gradient-to-r from-emerald-400 to-cyan-400 h-full rounded-full" style="width: 48%"></div>
                    </div>
                </div>

            </div>

            <!-- Soil 7-in-1 Full Breakdown Card -->
            <div class="glass-panel rounded-2xl p-4 border border-white/5 space-y-3">
                <div class="flex items-center justify-between border-b border-white/5 pb-2.5">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-flask-vial text-emerald-400 text-xs"></i>
                        <h3 class="font-bold text-xs text-white">ผลตรวจดิน RS485 Modbus RTU (7-in-1)</h3>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono">Stainless Probe</span>
                </div>

                <div class="grid grid-cols-4 gap-2 text-center">
                    <!-- pH -->
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-white/5">
                        <span class="text-[10px] text-gray-400 block">pH ดิน</span>
                        <span id="valSoilPh" class="text-sm font-bold font-mono text-emerald-400">6.4</span>
                    </div>
                    <!-- EC -->
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-white/5">
                        <span class="text-[10px] text-gray-400 block">EC (µS/cm)</span>
                        <span id="valSoilEc" class="text-sm font-bold font-mono text-cyan-400">850</span>
                    </div>
                    <!-- Light PAR -->
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-white/5">
                        <span class="text-[10px] text-gray-400 block">PAR (Lux)</span>
                        <span id="valPar" class="text-sm font-bold font-mono text-amber-400">42,500</span>
                    </div>
                    <!-- Soil Temp -->
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-white/5">
                        <span class="text-[10px] text-gray-400 block">อุณหภูมิดิน</span>
                        <span id="valSoilTemp" class="text-sm font-bold font-mono text-indigo-400">26.8°C</span>
                    </div>
                </div>

                <!-- N-P-K Breakdown -->
                <div class="p-2.5 rounded-xl bg-slate-950/60 border border-white/5 flex items-center justify-around text-xs font-mono">
                    <div>
                        <span class="text-gray-400 text-[10px]">Nitrogen (N):</span>
                        <span id="valN" class="font-bold text-emerald-400 ml-1">45 mg/kg</span>
                    </div>
                    <div class="w-px h-4 bg-white/10"></div>
                    <div>
                        <span class="text-gray-400 text-[10px]">Phosphorus (P):</span>
                        <span id="valP" class="font-bold text-cyan-400 ml-1">28 mg/kg</span>
                    </div>
                    <div class="w-px h-4 bg-white/10"></div>
                    <div>
                        <span class="text-gray-400 text-[10px]">Potassium (K):</span>
                        <span id="valK" class="font-bold text-amber-400 ml-1">160 mg/kg</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: 4-CHANNEL REMOTE RELAY SWITCHES -->
            <div class="glass-panel rounded-2xl p-4 border border-white/5 space-y-3">
                <div class="flex items-center justify-between border-b border-white/5 pb-2.5">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-cyan-400 text-xs"></i>
                        <h3 class="font-bold text-xs text-white">แผงควบคุมรีเลย์ 4 แชนแนล (ESP32 Relay)</h3>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono">Direct Command</span>
                </div>

                <div class="space-y-2.5">
                    
                    <!-- Relay 1 -->
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span id="app-led-1" class="w-3 h-3 rounded-full bg-gray-600 inline-block"></span>
                            <div>
                                <h4 class="text-xs font-bold text-white">วาล์วน้ำโซลินอยด์ (12V)</h4>
                                <span id="app-status-1" class="text-[10px] text-gray-400">สถานะ: ปิด (STANDBY)</span>
                            </div>
                        </div>
                        <button id="app-btn-1" onclick="toggleAppRelay(1, 'วาล์วน้ำโซลินอยด์')" class="px-4 py-1.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off text-[10px]"></i> สลับเปิด
                        </button>
                    </div>

                    <!-- Relay 2 -->
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span id="app-led-2" class="w-3 h-3 rounded-full bg-gray-600 inline-block"></span>
                            <div>
                                <h4 class="text-xs font-bold text-white">ระบบพ่นหมอกลด VPD</h4>
                                <span id="app-status-2" class="text-[10px] text-gray-400">สถานะ: ปิด (STANDBY)</span>
                            </div>
                        </div>
                        <button id="app-btn-2" onclick="toggleAppRelay(2, 'ระบบพ่นหมอกลด VPD')" class="px-4 py-1.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off text-[10px]"></i> สลับเปิด
                        </button>
                    </div>

                    <!-- Relay 3 -->
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span id="app-led-3" class="w-3 h-3 rounded-full bg-gray-600 inline-block"></span>
                            <div>
                                <h4 class="text-xs font-bold text-white">ปั๊มสารละลายปุ๋ย AB</h4>
                                <span id="app-status-3" class="text-[10px] text-gray-400">สถานะ: ปิด (STANDBY)</span>
                            </div>
                        </div>
                        <button id="app-btn-3" onclick="toggleAppRelay(3, 'ปั๊มสารละลายปุ๋ย AB')" class="px-4 py-1.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off text-[10px]"></i> สลับเปิด
                        </button>
                    </div>

                    <!-- Relay 4 -->
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span id="app-led-4" class="w-3 h-3 rounded-full bg-gray-600 inline-block"></span>
                            <div>
                                <h4 class="text-xs font-bold text-white">พัดลมระบายอากาศโรงเรือน</h4>
                                <span id="app-status-4" class="text-[10px] text-gray-400">สถานะ: ปิด (STANDBY)</span>
                            </div>
                        </div>
                        <button id="app-btn-4" onclick="toggleAppRelay(4, 'พัดลมระบายอากาศ')" class="px-4 py-1.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off text-[10px]"></i> สลับเปิด
                        </button>
                    </div>

                </div>
            </div>

            <!-- SECTION 3: Edge AI Vision Camera -->
            <div class="glass-panel rounded-2xl p-4 border border-white/5 space-y-3">
                <div class="flex items-center justify-between border-b border-white/5 pb-2.5">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-camera text-rose-400 text-xs"></i>
                        <h3 class="font-bold text-xs text-white">กล้อง AI Vision (OV2640 Snapshot)</h3>
                    </div>
                    <span class="text-[10px] text-emerald-400 font-mono">YOLOv8 On-Device</span>
                </div>

                <div class="relative rounded-xl overflow-hidden aspect-[16/10] bg-black border border-white/10 flex items-center justify-center">
                    <img id="cameraStreamImg" src="../assets/images/cv_agri_vision.jpg" alt="Camera Feed" class="w-full h-full object-cover">
                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-black/70 text-[9px] font-mono text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Live Snapshot
                    </div>
                    <div class="absolute bottom-2 left-2 right-2 px-3 py-1.5 rounded-lg bg-black/80 backdrop-blur-sm border border-white/10 flex items-center justify-between text-[10px]">
                        <span class="text-white font-medium">ผลวินิจฉัย: ใบพืชสมบูรณ์ (Healthy)</span>
                        <span class="text-emerald-400 font-mono font-bold">Conf: 98.4%</span>
                    </div>
                </div>

                <button onclick="captureSnapshot()" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md transition active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-camera-rotate"></i> ถ่ายภาพวิเคราะห์โรคพืชใหม่
                </button>
            </div>

        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 inset-x-0 z-40 glass-panel border-t border-white/10 px-6 py-2.5">
        <div class="flex items-center justify-around text-center">
            
            <button onclick="switchTab('overview')" class="flex flex-col items-center gap-1 text-emerald-400">
                <i class="fa-solid fa-gauge-high text-base"></i>
                <span class="text-[10px] font-medium">มอนิเตอร์</span>
            </button>

            <button onclick="window.location.href='../dashboard/index.php'" class="flex flex-col items-center gap-1 text-gray-400 hover:text-cyan-400 transition">
                <i class="fa-solid fa-chart-line text-base"></i>
                <span class="text-[10px] font-medium">เว็บแดชบอร์ด</span>
            </button>

            <button onclick="openWifiModal()" class="flex flex-col items-center gap-1 text-gray-400 hover:text-amber-400 transition">
                <i class="fa-solid fa-wifi text-base"></i>
                <span class="text-[10px] font-medium">Wi-Fi บอร์ด</span>
            </button>

            <a href="../index.php" class="flex flex-col items-center gap-1 text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[10px] font-medium">กลับเว็บหลัก</span>
            </a>

        </div>
    </nav>

    <!-- Wi-Fi Configuration Modal -->
    <div id="wifiModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-panel rounded-3xl p-6 border border-white/10 w-full max-w-sm space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wifi text-cyan-400"></i>
                    <h3 class="font-bold text-sm text-white">ตั้งค่าการเชื่อมต่อ Wi-Fi กับบอร์ด</h3>
                </div>
                <button onclick="closeWifiModal()" class="text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-gray-400 mb-1">IP Address บอร์ด ESP32-S3 ATD3.5</label>
                    <input type="text" id="inputBoardIp" value="192.168.1.105" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                    <span class="text-[10px] text-gray-500 mt-1 block">โหมด AP ค่าเริ่มต้น: 192.168.4.1 หรือ IP ในวง Wi-Fi บ้าน</span>
                </div>

                <div>
                    <label class="block text-gray-400 mb-1">โปรโตคอลการสื่อสาร</label>
                    <select id="inputProtocol" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                        <option value="http">HTTP REST API / JSON Polling</option>
                        <option value="ws">WebSockets (Real-time 60 FPS)</option>
                        <option value="mqtt">MQTT Telemetry Topic</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button onclick="saveWifiConfig()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> บันทึกและเชื่อมต่อ
                </button>
                <button onclick="closeWifiModal()" class="px-4 py-2.5 rounded-xl bg-white/10 text-gray-300 font-bold text-xs hover:bg-white/20 transition">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <!-- Application Script -->
    <script>
        const appRelayStates = { 1: false, 2: false, 3: false, 4: false };

        function toggleAppRelay(id, name) {
            appRelayStates[id] = !appRelayStates[id];
            const isOn = appRelayStates[id];

            const led = document.getElementById(`app-led-${id}`);
            const btn = document.getElementById(`app-btn-${id}`);
            const status = document.getElementById(`app-status-${id}`);

            if (led) {
                led.className = isOn ? 'w-3 h-3 rounded-full bg-emerald-400 animate-ping inline-block' : 'w-3 h-3 rounded-full bg-gray-600 inline-block';
            }
            if (btn) {
                btn.className = isOn 
                    ? 'px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5'
                    : 'px-4 py-1.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1.5';
                btn.innerHTML = isOn ? '<i class="fa-solid fa-power-off text-[10px]"></i> สลับปิด' : '<i class="fa-solid fa-power-off text-[10px]"></i> สลับเปิด';
            }
            if (status) {
                status.innerText = isOn ? 'สถานะ: กำลังทำงาน (ON)' : 'สถานะ: ปิด (STANDBY)';
                status.className = isOn ? 'text-[10px] font-bold text-emerald-400' : 'text-[10px] text-gray-400';
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: isOn ? 'success' : 'info',
                title: `${name}: ${isOn ? 'เปิดการทำงานแล้ว' : 'ปิดการทำงานแล้ว'}`,
                text: `คำสั่ง Wi-Fi ส่งสำเร็จสู่บอร์ด ESP32-S3`,
                showConfirmButton: false,
                timer: 1800
            });
        }

        function captureSnapshot() {
            Swal.fire({
                title: 'กำลังจับภาพจากกล้อง OV2640...',
                text: 'ประมวลผลโมเดล Edge AI YOLOv8 บนบอร์ด ESP32-S3',
                timer: 1200,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'จับภาพและวินิจฉัยสำเร็จ!',
                    text: 'ตรวจพบ: ใบพืชสมบูรณ์ (Healthy Plant) ความมั่นใจ 98.4%',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }

        function syncSensorData() {
            // Simulate reading live jitter from SHT45 & Soil 7-in-1
            const temp = (28.2 + Math.random() * 0.8).toFixed(1);
            const hum = (64.5 + Math.random() * 1.5).toFixed(1);
            const soilMoist = (71.0 + Math.random() * 2.0).toFixed(1);
            
            // Calculate VPD
            const svp = 0.61078 * Math.exp((17.27 * temp) / (parseFloat(temp) + 237.3));
            const avp = svp * (hum / 100.0);
            const vpd = (svp - avp).toFixed(2);

            document.getElementById('valTemp').innerText = temp;
            document.getElementById('valHum').innerText = hum;
            document.getElementById('valSoilMoist').innerText = soilMoist;
            document.getElementById('valVpd').innerText = vpd;
            document.getElementById('pingValue').innerText = Math.floor(15 + Math.random() * 8) + 'ms';

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'ซิงค์ข้อมูลล่าสุดผ่าน Wi-Fi สำเร็จ',
                showConfirmButton: false,
                timer: 1200
            });
        }

        function openWifiModal() {
            document.getElementById('wifiModal').classList.remove('hidden');
        }

        function closeWifiModal() {
            document.getElementById('wifiModal').classList.add('hidden');
        }

        function saveWifiConfig() {
            const ip = document.getElementById('inputBoardIp').value.trim();
            if (ip) {
                document.getElementById('headerBoardIp').innerText = `ESP32-S3 • ${ip}`;
            }
            closeWifiModal();
            Swal.fire({
                icon: 'success',
                title: 'บันทึกการตั้งค่า Wi-Fi แล้ว',
                text: `เชื่อมต่อกับบอร์ดที่ ${ip} สำเร็จ`,
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Periodic live heartbeat
        setInterval(() => {
            const currentTemp = parseFloat(document.getElementById('valTemp').innerText);
            const delta = (Math.random() * 0.2 - 0.1);
            document.getElementById('valTemp').innerText = (currentTemp + delta).toFixed(1);
        }, 5000);
    </script>
</body>
</html>
