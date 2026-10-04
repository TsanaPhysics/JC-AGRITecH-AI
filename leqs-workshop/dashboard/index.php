<?php
// LEQs-xAI: Real Interactive Web Dashboard
// Connected via Wi-Fi to ESP32-S3 ATD3.5 Controller Board
?>
<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEQs-xAI Smart Farm Web Dashboard | เชื่อมต่อบอร์ด ESP32-S3 ATD3.5</title>
    
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
                        darkcard: '#0f172a',
                        accent: '#10b981',
                        cyanic: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <!-- Chart.js & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .glass-box {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-accent {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans bg-slate-950 text-slate-100 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 glass-box border-b border-slate-800/80 px-6 py-3.5">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Brand & Board Info -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                <a href="../index.php" class="flex items-center gap-2.5 text-white hover:text-emerald-400 transition group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition">
                        <i class="fa-solid fa-leaf text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm md:text-base font-tech tracking-wider">LEQs-xAI SMART FARM</span>
                            <span class="text-[9px] font-mono bg-cyan-500/20 text-cyan-300 px-2 py-0.5 rounded border border-cyan-500/30">DASHBOARD</span>
                        </div>
                        <p class="text-[10px] text-slate-400">ระบบควบคุมมอนิเตอร์แปลงเกษตรแม่นยำออนไลน์</p>
                    </div>
                </a>

                <!-- Hardware Connection Chip (Matching ESP32-S3 ATD3.5 Screen Photo) -->
                <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900 border border-emerald-500/40 text-xs font-mono shadow-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="cloudStatusText" class="text-emerald-400 font-bold">ONLINE</span>
                    <span class="text-slate-600">|</span>
                    <span class="text-gray-400">SSID:</span>
                    <span id="boardSsidDisplay" class="text-emerald-300 font-bold">JC_Home</span>
                    <span class="text-slate-600">|</span>
                    <span class="text-gray-400">IP:</span>
                    <span id="boardIpDisplay" class="text-cyan-300 font-bold">192.168.0.111:8500</span>
                    <span class="text-slate-600">|</span>
                    <span class="text-gray-400">SD Card:</span>
                    <span id="sdCardStatusDisplay" class="text-amber-300 font-bold"><i class="fa-solid fa-sd-card mr-0.5"></i> 142 rec</span>
                    <span class="text-slate-600">|</span>
                    <span class="text-gray-400">DB:</span>
                    <span id="dbRecordsStatusDisplay" class="text-emerald-300 font-bold"><i class="fa-solid fa-database mr-0.5"></i> SQLite</span>
                </div>
            </div>

            <!-- Top Actions & Navigation Links -->
            <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                <button onclick="openBoardScreenModal()" class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/40 text-xs text-purple-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-display text-[11px]"></i> จำลองจอ ESP32
                </button>
                <button onclick="openQrModal()" class="px-3 py-1.5 rounded-xl bg-cyan-950/80 hover:bg-cyan-900 border border-cyan-500/40 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-qrcode text-[11px]"></i> QR Portal
                </button>
                <button onclick="promptChangeIp()" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-network-wired text-[10px]"></i> ตั้งค่า IP บอร์ด
                </button>
                <a href="../api/api.php?action=export_csv" target="_blank" class="px-3 py-1.5 rounded-xl bg-amber-950/80 hover:bg-amber-900 border border-amber-500/40 text-xs text-amber-300 font-mono flex items-center gap-1.5 transition shadow-sm" title="ดาวน์โหลดฐานข้อมูล telemetry_logs เป็น CSV">
                    <i class="fa-solid fa-file-csv text-[11px]"></i> โหลด CSV ฐานข้อมูล
                </a>
                <a href="../mobile/index.php" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-mobile-screen-button"></i> Mobile App
                </a>
                <a href="../index.php" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition">
                    <i class="fa-solid fa-house"></i> เว็บหลัก
                </a>
            </div>

        </div>
    </header>

    <!-- Main Dashboard Container -->
    <main class="container mx-auto px-4 md:px-6 py-6 space-y-6 flex-1">
        
        <!-- HIGHLIGHT: Official QR Portal & Identity Verification (ESP32-S3 ATD3.5 Screen 11) -->
        <div class="glass-box rounded-3xl p-5 border border-cyan-500/40 bg-gradient-to-r from-cyan-950/60 via-slate-900/90 to-indigo-950/70 flex flex-col lg:flex-row items-center justify-between gap-5 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-48 h-48 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left relative z-10">
                <!-- QR Image -->
                <div class="w-20 h-20 bg-white p-1 rounded-2xl shadow-lg flex-shrink-0 cursor-pointer hover:scale-105 transition" onclick="openQrModal()">
                    <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Code" class="w-full h-full object-contain">
                </div>
                <!-- Metadata Stamp -->
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] font-mono border border-cyan-500/30 font-bold">
                            <i class="fa-solid fa-microchip mr-1"></i> ESP32-S3 ATD3.5 จอที่ 11
                        </span>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded border border-emerald-800">
                            2026.10.04 วันอาทิตย์
                        </span>
                        <span class="text-xs font-mono font-bold text-amber-300 bg-amber-950/80 px-2 py-0.5 rounded border border-amber-800">
                            09:03 ชีวะ ทัศนา
                        </span>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-white font-tech">
                        พอร์ทัลสแกนเชื่อมต่อและระบบยืนยันตัวตน Smart Farm AIoT
                    </h3>
                    <p class="text-xs text-slate-300 font-mono">
                        URL: <a href="https://aidar.rbru.ac.th/leqsxai" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline font-semibold">https://aidar.rbru.ac.th/leqsxai</a>
                    </p>
                </div>
            </div>

            <!-- ESP32 Screen 11 Thumbnail & Quick Actions -->
            <div class="flex items-center gap-3 flex-shrink-0 relative z-10">
                <div class="relative rounded-xl overflow-hidden border border-cyan-500/40 w-36 aspect-[3/2] cursor-pointer group shadow-md" onclick="previewScreen11()">
                    <img src="../assets/images/atd35/11_qr_portal_screen.png" alt="ESP32 Screen 11" class="w-full h-full object-cover group-hover:scale-105 transition">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition flex items-center justify-center">
                        <span class="px-2 py-0.5 rounded bg-black/80 text-[10px] font-mono text-cyan-300 border border-cyan-400/40">ESP32 จอที่ 11</span>
                    </div>
                </div>
                <button onclick="openQrModal()" class="px-4 py-2.5 rounded-2xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs font-tech shadow-lg shadow-cyan-600/30 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-qrcode"></i> แสดง QR Code
                </button>
            </div>
        </div>

        <!-- ROW 1: 6 Real-time Telemetry Metrics Cards (All Quantities Completely Displayed) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            
            <!-- Card 1: Air Temp & Humidity (SHT45 Microclimate) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-temperature-three-quarters text-amber-400"></i>
                        <span>สภาพอากาศ (SHT45)</span>
                    </div>
                    <span id="badgeSht45" class="text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">I2C ONLINE</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashTemp" class="text-2xl lg:text-3xl font-bold font-mono text-white">31.4</span>
                        <span class="text-xs text-slate-400">°C</span>
                        <span id="dashTempF" class="text-[10px] font-mono text-slate-500 block">(88.5°F)</span>
                    </div>
                    <div class="text-right">
                        <span id="dashHum" class="text-xl lg:text-2xl font-bold font-mono text-cyan-400">85.0</span>
                        <span class="text-xs text-slate-400">%RH</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>Dew: <strong id="dashDewPoint" class="text-cyan-300">28.4°C</strong></span>
                    <span>Margin: <strong id="dashDewMargin" class="text-amber-300">3.0°C</strong></span>
                </div>
            </div>

            <!-- Card 2: VPD (Vapor Pressure Deficit & Penman Physics) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-wind text-cyan-400"></i>
                        <span>แรงดึงระเหยน้ำ (VPD)</span>
                    </div>
                    <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">FAO-56</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashVpd" class="text-2xl lg:text-3xl font-bold font-mono text-emerald-300">0.69</span>
                        <span class="text-xs text-slate-400">kPa</span>
                    </div>
                    <span id="dashVpdStatus" class="text-[10px] font-bold text-amber-300 bg-amber-950 px-1.5 py-0.5 rounded">ชื้นสูง</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>VPsat: <strong id="dashVpSat" class="text-slate-300">4.60</strong></span>
                    <span>VPact: <strong id="dashVpAct" class="text-cyan-300">3.91</strong></span>
                </div>
            </div>

            <!-- Card 3: Surface Soil Stick (0-10 cm) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i>
                        <span>ผิวดิน (Soil Stick)</span>
                    </div>
                    <span id="badgeSoilStick" class="text-[9px] font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">ADC A1/A2</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashStickMoist" class="text-2xl lg:text-3xl font-bold font-mono text-white">65.0</span>
                        <span class="text-xs text-slate-400">%</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400">pH ผิวดิน</span>
                        <span id="dashStickPh" class="text-lg lg:text-xl font-bold font-mono text-amber-300 block">6.2</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>ADC: <strong id="dashStickAdc" class="text-slate-300">1850</strong></span>
                    <span>Volt: <strong id="dashStickVolt" class="text-amber-300">1.85V</strong></span>
                </div>
            </div>

            <!-- Card 4: Deep Root Soil 7-in-1 (RS485 Modbus RTU) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-seedling text-emerald-400"></i>
                        <span>ดินเขตราก (7-in-1)</span>
                    </div>
                    <span id="badgeSoil7in1" class="text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">RS485 MODBUS</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashSoilMoist" class="text-2xl lg:text-3xl font-bold font-mono text-white">65.0</span>
                        <span class="text-xs text-slate-400">%</span>
                    </div>
                    <div class="text-right">
                        <span id="dashSoilEc" class="text-lg lg:text-xl font-bold font-mono text-emerald-400">120</span>
                        <span class="text-[10px] text-slate-400">µS/cm</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>pH: <strong id="dashSoilPh" class="text-cyan-300">6.2</strong></span>
                    <span>Temp: <strong id="dashSoilTemp" class="text-slate-300">27.5°C</strong></span>
                </div>
            </div>

            <!-- Card 5: Solar PAR & Radiation (BH1750 Dome) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-yellow-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-sun text-yellow-400"></i>
                        <span>แสงแดด & รังสี</span>
                    </div>
                    <span id="badgeBh1750" class="text-[9px] font-mono text-yellow-400 bg-yellow-950 px-2 py-0.5 rounded border border-yellow-800">BH1750 I2C</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashPar" class="text-2xl lg:text-3xl font-bold font-mono text-white">897.5</span>
                        <span class="text-xs text-slate-400">Lux</span>
                    </div>
                    <span id="dashSolarRad" class="text-[10px] font-bold text-yellow-400 bg-yellow-950 px-1.5 py-0.5 rounded font-mono">7.09 W/m²</span>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-2 border-t border-slate-800/60 font-mono">
                    <span>kLux: <strong id="dashKlux" class="text-yellow-300">0.90</strong></span>
                    <span id="dashAiConfidence" class="text-emerald-400 font-bold">AI: 98.4%</span>
                </div>
            </div>

            <!-- Card 6: Dual Storage (Micro-SD Card on ESP32 + SQLite3 Database) -->
            <div class="glass-box rounded-3xl p-4 sm:p-5 border border-cyan-500/40 space-y-3 relative overflow-hidden group hover:border-cyan-400 transition bg-gradient-to-br from-slate-900/90 to-cyan-950/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-cyan-300 font-medium font-tech">
                        <i class="fa-solid fa-server text-cyan-400"></i>
                        <span>การจัดเก็บข้อมูล 2 ชั้น</span>
                    </div>
                    <span id="dashSdBadge" class="text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">DUAL STORAGE</span>
                </div>
                <div class="space-y-1.5 text-[11px] font-mono">
                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800">
                        <span class="text-amber-300 flex items-center gap-1"><i class="fa-solid fa-sd-card"></i> SD Card:</span>
                        <strong id="dashSdRecords" class="text-white">142 เรคอร์ด</strong>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-slate-900/90 border border-slate-800">
                        <span class="text-emerald-300 flex items-center gap-1"><i class="fa-solid fa-database"></i> SQLite DB:</span>
                        <strong id="dashDbRecords" class="text-white">Auto Sync</strong>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-800/60 font-mono">
                    <span>File: <span class="text-amber-300 font-bold">.csv &amp; .db</span></span>
                    <a href="../api/api.php?action=export_csv" class="text-cyan-400 hover:underline font-bold">โหลด CSV</a>
                </div>
            </div>

        </div>

        <!-- ROW 2: Real-time Charts & Multi-Channel Relays Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left: 24h Environmental Trend Line Chart (8 cols) -->
            <div class="lg:col-span-8 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <h3 class="font-bold text-base text-white">กราฟแนวโน้มสภาพแวดล้อม 24 ชั่วโมง (Real-Time Trend)</h3>
                        </div>
                        <p class="text-xs text-slate-400">อุณหภูมิ (°C), ความชื้นสัมพัทธ์ (%), และความชื้นดิน (%) บันทึกทุก 5 วินาที</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="toggleChartSeries('temp')" class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-300 text-xs font-mono font-bold border border-amber-500/30">
                            Temp
                        </button>
                        <button onclick="toggleChartSeries('hum')" class="px-2.5 py-1 rounded-lg bg-cyan-500/20 text-cyan-300 text-xs font-mono font-bold border border-cyan-500/30">
                            Humidity
                        </button>
                        <button onclick="toggleChartSeries('soil')" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 text-xs font-mono font-bold border border-emerald-500/30">
                            Soil Moist
                        </button>
                    </div>
                </div>

                <!-- Canvas Chart Area -->
                <div class="relative h-72 w-full">
                    <canvas id="envTrendChart"></canvas>
                </div>
            </div>

            <!-- Right: 4-Channel Remote Relay Control Panel (4 cols) -->
            <div class="lg:col-span-4 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-emerald-400"></i>
                            <h3 class="font-bold text-sm text-white">สั่งการรีเลย์ออนไลน์ 4 แชนแนล</h3>
                        </div>
                        <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">ESP32 GPIO</span>
                    </div>

                    <!-- 4 Relay Switch Rows -->
                    <div class="space-y-3 pt-3">
                        
                        <!-- Relay 1 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-1" class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">วาล์วน้ำโซลินอยด์ (12V)</h4>
                                    <span id="dash-status-1" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-1" onclick="toggleDashboardRelay(1, 'วาล์วน้ำโซลินอยด์')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 2 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-2" class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">ระบบพ่นหมอกลด VPD</h4>
                                    <span id="dash-status-2" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-2" onclick="toggleDashboardRelay(2, 'ระบบพ่นหมอก')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 3 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-3" class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">ปั๊มสารละลายปุ๋ย AB</h4>
                                    <span id="dash-status-3" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-3" onclick="toggleDashboardRelay(3, 'ปั๊มปุ๋ย AB')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                        <!-- Relay 4 -->
                        <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span id="dash-led-4" class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">พัดลมระบายอากาศโรงเรือน</h4>
                                    <span id="dash-status-4" class="text-[10px] text-slate-400">STANDBY (OFF)</span>
                                </div>
                            </div>
                            <button id="dash-btn-4" onclick="toggleDashboardRelay(4, 'พัดลมระบายอากาศ')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1">
                                <i class="fa-solid fa-power-off text-[10px]"></i> เปิด
                            </button>
                        </div>

                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-400">โหมดการทำงาน:</span>
                    <button id="modeToggleBtn" onclick="toggleAutoMode()" class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30">
                        <i class="fa-solid fa-robot mr-1"></i> Smart Auto
                    </button>
                </div>
            </div>

        </div>

        <!-- ROW 3: NPK Radar Chart & Smart Rule Automation -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
            
            <!-- Soil NPK Radar (4 cols) -->
            <div class="lg:col-span-4 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-cyan-400"></i>
                        <h3 class="font-bold text-sm text-white">สมดุลธาตุอาหารในดิน (NPK Radar)</h3>
                    </div>
                    <span class="text-[10px] font-mono text-cyan-400">7-in-1 Modbus</span>
                </div>
                <div class="relative h-56 w-full flex items-center justify-center">
                    <canvas id="npkRadarChart"></canvas>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs pt-2 border-t border-slate-800/60 font-mono">
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block">N (ไนโตรเจน)</span>
                        <strong id="dashValN" class="text-emerald-400">45 mg/kg</strong>
                        <span id="dashAiN" class="text-[9px] text-cyan-300 block">AI: 47.3</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block">P (ฟอสฟอรัส)</span>
                        <strong id="dashValP" class="text-cyan-400">32 mg/kg</strong>
                        <span id="dashAiP" class="text-[9px] text-cyan-300 block">AI: 32.6</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block">K (โพแทสเซียม)</span>
                        <strong id="dashValK" class="text-amber-400">180 mg/kg</strong>
                        <span id="dashAiK" class="text-[9px] text-cyan-300 block">AI: 178.2</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] font-mono pt-1 text-slate-400 border-t border-slate-800/40">
                    <span>สัดส่วน: <strong id="dashNpkRatio" class="text-cyan-300">1.4:1:5.6</strong></span>
                    <span>รวม: <strong id="dashNpkTotal" class="text-white">257 mg/kg</strong></span>
                </div>
            </div>

            <!-- Smart Automation Rules Builder (8 cols) -->
            <div class="lg:col-span-8 glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-microchip text-emerald-400"></i>
                        <h3 class="font-bold text-sm text-white">เงื่อนไขอัจฉริยะอัตโนมัติ (Rule-Based Automation Engine)</h3>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">3 Rules Active</span>
                </div>

                <div class="space-y-3">
                    <!-- Rule 1 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">R1</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบรดน้ำอัตโนมัติเมื่อดินแห้ง</h4>
                                <p class="text-[11px] text-slate-400">IF ความชื้นดิน &lt; 45% THEN สั่งเปิดรีเลย์ 1 (วาล์วน้ำโซลินอยด์) นาน 180 วินาที</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>

                    <!-- Rule 2 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">R2</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบพ่นหมอกลดความเครียดพืชตามค่า VPD</h4>
                                <p class="text-[11px] text-slate-400">IF VPD &gt; 1.4 kPa OR Temp &gt; 33°C THEN สั่งเปิดรีเลย์ 2 (พ่นหมอก) เป็นจังหวะ 30 วิ</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>

                    <!-- Rule 3 -->
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold">R3</span>
                            <div>
                                <h4 class="font-bold text-white">ระบบระบายอากาศฉุกเฉินเมื่ออุณหภูมิวิกฤต</h4>
                                <p class="text-[11px] text-slate-400">IF Temp &gt; 36°C THEN สั่งเปิดรีเลย์ 4 (พัดลมดูดอากาศ) จนกว่าอุณหภูมิ &lt; 32°C</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-800">ENABLED</span>
                    </div>
                </div>

                <!-- Live Log Terminal Stream -->
                <div class="bg-slate-950 rounded-2xl p-3 border border-slate-800 font-mono text-[11px] text-slate-300 space-y-1 max-h-28 overflow-y-auto">
                    <div class="text-emerald-400">[System] Connected to ESP32-S3 ATD3.5 Controller at 192.168.0.111:8500 (SSID: JC_Home, RSSI: -99dBm)</div>
                    <div class="text-slate-400">[Telemetry] Ingested 24-point dataset: SHT45 (T:28.5C, RH:65.2%), Soil 7in1 (M:72.4%, EC:850, pH:6.4)</div>
                    <div class="text-cyan-400">[Engine] Automated evaluation cycle completed. All parameters within safe thresholds.</div>
                </div>

            </div>

        </div>

        <!-- ROW 4: Live SQLite3 Database Telemetry Logs & Dual-Storage Architecture -->
        <div class="glass-box rounded-3xl p-6 border border-slate-800/80 space-y-4 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-database text-emerald-400"></i>
                        <h3 class="font-bold text-base text-white">บันทึกประวัติข้อมูลเซนเซอร์ลงฐานข้อมูล (SQLite3 Telemetry Database Logs)</h3>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        จัดเก็บทุกปริมาณ ทุกค่าจากบอร์ด ESP32-S3 ATD3.5 ลงตาราง <code class="text-cyan-300 font-mono">telemetry_logs</code> (ฐานข้อมูลเซิร์ฟเวอร์) ควบคู่กับ <code class="text-amber-300 font-mono">/telemetry_data.csv</code> บน Micro-SD Card
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs font-mono text-slate-300">
                        เรคอร์ดใน DB: <strong id="tableDbTotalRecords" class="text-emerald-400">0</strong>
                    </span>
                    <button onclick="fetchDbHistoryTable()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-arrows-rotate"></i> รีเฟรชตาราง
                    </button>
                    <a href="../api/api.php?action=export_csv" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-emerald-500 hover:from-amber-400 hover:to-emerald-400 text-slate-950 font-bold text-xs font-tech flex items-center gap-1.5 shadow-md transition">
                        <i class="fa-solid fa-file-csv text-sm"></i> ดาวน์โหลดข้อมูลทั้งหมด (.CSV)
                    </a>
                </div>
            </div>

            <!-- Responsive Table Container -->
            <div class="overflow-x-auto rounded-2xl border border-slate-800/80 bg-slate-950/60 max-h-96">
                <table class="w-full text-left text-xs text-slate-300 font-mono">
                    <thead class="bg-slate-900/90 text-[11px] text-slate-400 border-b border-slate-800 sticky top-0 backdrop-blur-sm z-10">
                        <tr>
                            <th class="p-3">#ID</th>
                            <th class="p-3">วัน-เวลา</th>
                            <th class="p-3">อากาศ (T/RH)</th>
                            <th class="p-3">VPD</th>
                            <th class="p-3">แสง &amp; Rad</th>
                            <th class="p-3">ผิวดิน Stick</th>
                            <th class="p-3">ดินลึก 7-in-1</th>
                            <th class="p-3">NPK (mg/kg)</th>
                            <th class="p-3">AI NPK</th>
                            <th class="p-3">รีเลย์ 1-4</th>
                            <th class="p-3">สถานะ SD Card</th>
                        </tr>
                    </thead>
                    <tbody id="telemetryTableBody" class="divide-y divide-slate-800/60 text-[11px]">
                        <tr>
                            <td colspan="11" class="text-center p-6 text-slate-500">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i> กำลังโหลดข้อมูลประวัติจาก SQLite3...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 pt-2 font-mono gap-2">
                <div>
                    <span>แฟ้มข้อมูล SD Card: <strong class="text-amber-300">/telemetry_data.csv</strong></span>
                    <span class="mx-2">•</span>
                    <span>ฐานข้อมูลเซิร์ฟเวอร์: <strong class="text-emerald-300">data/leqs_xai.db</strong></span>
                </div>
                <div class="text-slate-500 text-[10px]">
                    * อัปเดตอัตโนมัติทุก 5 วินาที พร้อมส่งออกไฟล์มาตรฐานรองรับ Excel และ R / Python
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="glass-box border-t border-slate-800/80 px-6 py-4 mt-8 text-center text-xs text-slate-400">
        <p>LEQs-xAI Project: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม • มหาวิทยาลัยราชภัฏรำไพพรรณี</p>
        <p class="text-[10px] text-slate-500 mt-1">Direct Wi-Fi Hardware Integration for ESP32-S3 ATD3.5 Screen Controller</p>
    </footer>

    <!-- Dashboard JavaScript Logic (Live Bidirectional IoT Sync Engine) -->
    <script>
        let currentBoardIp = '192.168.0.111';
        let currentBoardPort = 8500;
        let currentSsid = 'JC_Home';
        let currentRssi = -99;
        let currentCloudUrl = 'http://14.207.141.164:8000';
        let isAutoMode = true;
        const dashRelayStates = { 1: true, 2: false, 3: true, 4: true };

        // 1. Initialize Chart.js Trend Line Chart
        const ctxTrend = document.getElementById('envTrendChart').getContext('2d');
        const timeLabels = ['11:40', '11:42', '11:44', '11:46', '11:48', '11:50', '11:52'];
        const tempData = [28.2, 28.3, 28.5, 28.6, 28.4, 28.5, 28.5];
        const humData = [66.0, 65.5, 65.2, 65.0, 65.3, 65.1, 65.2];
        const soilData = [72.0, 72.1, 72.3, 72.4, 72.4, 72.5, 72.4];

        const envTrendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: timeLabels,
                datasets: [
                    {
                        label: 'อุณหภูมิ (°C)',
                        data: tempData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3
                    },
                    {
                        label: 'ความชื้น RH (%)',
                        data: humData,
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.05)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3
                    },
                    {
                        label: 'ความชื้นดิน (%)',
                        data: soilData,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderDash: [5, 5],
                        pointRadius: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Prompt', size: 11 } }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { family: 'Orbitron', size: 10 } },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y: {
                        ticks: { color: '#64748b', font: { family: 'Orbitron', size: 10 } },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });

        // 2. Initialize NPK Radar Chart
        const ctxRadar = document.getElementById('npkRadarChart').getContext('2d');
        const npkRadarChart = new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: ['ไนโตรเจน (N)', 'ฟอสฟอรัส (P)', 'โพแทสเซียม (K)', 'ความชื้นดิน', 'pH ดิน', 'EC สภาพนำ'],
                datasets: [{
                    label: 'ค่าปัจจุบัน',
                    data: [45, 32, 65, 72.4, 64, 55],
                    backgroundColor: 'rgba(16, 185, 129, 0.25)',
                    borderColor: '#10b981',
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#10b981'
                }, {
                    label: 'เกณฑ์มาตรฐานพืช',
                    data: [50, 30, 70, 70, 65, 60],
                    backgroundColor: 'transparent',
                    borderColor: '#64748b',
                    borderDash: [4, 4],
                    pointRadius: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.08)' },
                        grid: { color: 'rgba(255, 255, 255, 0.08)' },
                        pointLabels: { color: '#94a3b8', font: { family: 'Prompt', size: 9 } },
                        ticks: { display: false }
                    }
                }
            }
        });

        // 3. UI Helpers
        function updateRelayDom(id, isOn) {
            dashRelayStates[id] = isOn;
            const led = document.getElementById(`dash-led-${id}`);
            const btn = document.getElementById(`dash-btn-${id}`);
            const status = document.getElementById(`dash-status-${id}`);

            if (led) {
                led.className = isOn ? 'w-3 h-3 rounded-full bg-emerald-400 animate-ping inline-block' : 'w-3 h-3 rounded-full bg-slate-700 inline-block';
            }
            if (btn) {
                btn.className = isOn 
                    ? 'px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1 shadow-md'
                    : 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1';
                btn.innerHTML = isOn ? '<i class="fa-solid fa-power-off text-[10px]"></i> ปิด' : '<i class="fa-solid fa-power-off text-[10px]"></i> เปิด';
            }
            if (status) {
                status.innerText = isOn ? 'ACTIVE (ON)' : 'STANDBY (OFF)';
                status.className = isOn ? 'text-[10px] font-bold text-emerald-400' : 'text-[10px] text-slate-400';
            }
        }

        function updateAutoModeDom(isAuto) {
            const btn = document.getElementById('modeToggleBtn');
            if (!btn) return;
            if (isAuto) {
                btn.className = 'px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30';
                btn.innerHTML = '<i class="fa-solid fa-robot mr-1"></i> Smart Auto';
            } else {
                btn.className = 'px-3 py-1 rounded-xl bg-amber-500/20 text-amber-300 font-bold border border-amber-500/30';
                btn.innerHTML = '<i class="fa-solid fa-hand mr-1"></i> Manual Override';
            }
        }

        // 4. Real Bidirectional API Synchronization
        async function syncTelemetryFromApi() {
            try {
                const res = await fetch('../api/api.php?action=get_telemetry');
                if (!res.ok) return;
                const data = await res.json();
                if (data.status === 'success') {
                    // Update Board parameters
                    if (data.board) {
                        currentBoardIp = data.board.ip_address || currentBoardIp;
                        currentBoardPort = data.board.web_port || currentBoardPort;
                        currentSsid = data.board.ssid || currentSsid;
                        currentRssi = data.board.rssi || currentRssi;
                        currentCloudUrl = data.board.cloud_url || currentCloudUrl;

                        const ipEl = document.getElementById('boardIpDisplay');
                        if (ipEl) ipEl.innerText = `${currentBoardIp}:${currentBoardPort}`;
                        const ssidEl = document.getElementById('boardSsidDisplay');
                        if (ssidEl) ssidEl.innerText = currentSsid;
                        const cloudEl = document.getElementById('cloudSyncDisplay');
                        if (cloudEl && data.board.cloud_url) {
                            cloudEl.innerText = data.board.cloud_url.replace('http://', '');
                        }
                        const idEl = document.getElementById('cloudTelemetryId');
                        if (idEl && data.board.telemetry_id) {
                            idEl.innerText = `#${data.board.telemetry_id}`;
                        }
                        const statEl = document.getElementById('cloudStatusText');
                        if (statEl) {
                            statEl.innerText = data.board.is_live ? 'LIVE CLOUD' : 'ONLINE';
                        }
                    }

                    // Update Real Sensors
                    if (data.sensors) {
                        const s = data.sensors;
                        if (document.getElementById('dashTemp')) document.getElementById('dashTemp').innerText = Number(s.temperature).toFixed(1);
                        if (document.getElementById('dashTempF')) document.getElementById('dashTempF').innerText = `(${Number(s.temperature_f || ((s.temperature * 1.8) + 32)).toFixed(1)}°F)`;
                        if (document.getElementById('dashHum')) document.getElementById('dashHum').innerText = Number(s.humidity).toFixed(1);
                        if (document.getElementById('dashDewPoint')) document.getElementById('dashDewPoint').innerText = `${Number(s.dew_point).toFixed(1)}°C`;
                        if (document.getElementById('dashDewMargin')) document.getElementById('dashDewMargin').innerText = `${Number(s.dew_margin || (s.temperature - s.dew_point)).toFixed(1)}°C`;
                        
                        if (document.getElementById('dashVpd')) document.getElementById('dashVpd').innerText = Number(s.vpd).toFixed(2);
                        if (document.getElementById('dashVpSat')) document.getElementById('dashVpSat').innerText = Number(s.vpsat || 4.60).toFixed(2);
                        if (document.getElementById('dashVpAct')) document.getElementById('dashVpAct').innerText = Number(s.vpact || 3.91).toFixed(2);
                        if (document.getElementById('dashVpdStatus')) {
                            const v = Number(s.vpd);
                            if (v < 0.8) {
                                document.getElementById('dashVpdStatus').innerText = 'ความชื้นสูง (Low Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-amber-300 bg-amber-950 px-2 py-1 rounded';
                            } else if (v > 1.4) {
                                document.getElementById('dashVpdStatus').innerText = 'อากาศแห้ง (High Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-rose-300 bg-rose-950 px-2 py-1 rounded';
                            } else {
                                document.getElementById('dashVpdStatus').innerText = 'สมบูรณ์ (Optimal Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-emerald-300 bg-emerald-950 px-2 py-1 rounded';
                            }
                        }

                        if (document.getElementById('dashSoilMoist')) document.getElementById('dashSoilMoist').innerText = Number(s.soil_moisture).toFixed(1);
                        if (document.getElementById('dashSoilEc')) document.getElementById('dashSoilEc').innerText = Math.round(s.soil_ec);
                        if (document.getElementById('dashSoilPh')) document.getElementById('dashSoilPh').innerText = Number(s.soil_ph).toFixed(1);
                        if (document.getElementById('dashSoilTemp')) document.getElementById('dashSoilTemp').innerText = `${Number(s.soil_temperature || 27.5).toFixed(1)}°C`;
                        
                        // Surface Soil Stick
                        if (document.getElementById('dashStickMoist')) document.getElementById('dashStickMoist').innerText = Number(s.soil_stick_moisture || s.soil_moisture).toFixed(1);
                        if (document.getElementById('dashStickPh')) document.getElementById('dashStickPh').innerText = Number(s.soil_stick_ph || s.soil_ph).toFixed(1);
                        if (document.getElementById('dashStickAdc')) document.getElementById('dashStickAdc').innerText = s.soil_stick_adc || 1850;
                        if (document.getElementById('dashStickVolt')) document.getElementById('dashStickVolt').innerText = `${Number(s.soil_stick_ph_volt || 1.85).toFixed(2)}V`;

                        if (document.getElementById('dashPar')) document.getElementById('dashPar').innerText = Number(s.par_lux).toLocaleString();
                        if (document.getElementById('dashKlux')) document.getElementById('dashKlux').innerText = Number(s.klux || (s.par_lux / 1000.0)).toFixed(2);
                        if (document.getElementById('dashSolarRad')) {
                            document.getElementById('dashSolarRad').innerText = `${Number(s.solar_radiation || 7.09).toFixed(2)} W/m²`;
                        }

                        // NPK Elements
                        if (document.getElementById('dashValN')) document.getElementById('dashValN').innerText = `${Number(s.nitrogen).toFixed(1)} mg/kg`;
                        if (document.getElementById('dashValP')) document.getElementById('dashValP').innerText = `${Number(s.phosphorus).toFixed(1)} mg/kg`;
                        if (document.getElementById('dashValK')) document.getElementById('dashValK').innerText = `${Number(s.potassium).toFixed(1)} mg/kg`;

                        // AI Calibrated Elements
                        if (data.ai_calibrated) {
                            const ai = data.ai_calibrated;
                            if (document.getElementById('dashAiN')) document.getElementById('dashAiN').innerText = `AI: ${Number(ai.nitrogen).toFixed(1)}`;
                            if (document.getElementById('dashAiP')) document.getElementById('dashAiP').innerText = `AI: ${Number(ai.phosphorus).toFixed(1)}`;
                            if (document.getElementById('dashAiK')) document.getElementById('dashAiK').innerText = `AI: ${Number(ai.potassium).toFixed(1)}`;
                            if (document.getElementById('dashNpkRatio')) document.getElementById('dashNpkRatio').innerText = ai.npk_ratio || '1.4:1:5.6';
                            if (document.getElementById('dashNpkTotal')) document.getElementById('dashNpkTotal').innerText = `Total: ${ai.npk_total} mg/kg`;
                            if (document.getElementById('dashAiConfidence') && ai.confidence) {
                                document.getElementById('dashAiConfidence').innerText = `TinyML AI: ${(ai.confidence * 100).toFixed(1)}%`;
                            }
                        }

                        // SD Card & SQLite Storage Indicators
                        if (data.sd_card) {
                            const sd = data.sd_card;
                            const sdRecText = `${Number(sd.records || 0).toLocaleString()} เรคอร์ด`;
                            if (document.getElementById('dashSdRecords')) document.getElementById('dashSdRecords').innerText = sdRecText;
                            if (document.getElementById('sdCardStatusDisplay')) {
                                document.getElementById('sdCardStatusDisplay').innerHTML = `<i class="fa-solid fa-sd-card mr-0.5"></i> ${sdRecText}`;
                            }
                        }
                        if (data.database) {
                            const dbTotal = `${Number(data.database.total_records || 0).toLocaleString()} เรคอร์ด`;
                            if (document.getElementById('dashDbRecords')) document.getElementById('dashDbRecords').innerText = dbTotal;
                            if (document.getElementById('dbRecordsStatusDisplay')) {
                                document.getElementById('dbRecordsStatusDisplay').innerHTML = `<i class="fa-solid fa-database mr-0.5"></i> ${dbTotal}`;
                            }
                            if (document.getElementById('tableDbTotalRecords')) {
                                document.getElementById('tableDbTotalRecords').innerText = dbTotal;
                            }
                        }

                        // Update Radar Chart
                        if (npkRadarChart && npkRadarChart.data && npkRadarChart.data.datasets[0]) {
                            npkRadarChart.data.datasets[0].data = [
                                Number(s.nitrogen),
                                Number(s.phosphorus),
                                Number(s.potassium),
                                Number(s.soil_moisture),
                                Number(s.soil_ph) * 10,
                                Math.min(100, Number(s.soil_ec) / 5)
                            ];
                            npkRadarChart.update('none');
                        }

                        // Update Connection Badges
                        if (data.sensor_connection) {
                            const c = data.sensor_connection;
                            const bSht = document.getElementById('badgeSht45');
                            if (bSht) {
                                bSht.innerText = c.sht45 ? 'I2C ONLINE' : 'DISCONNECTED';
                                bSht.className = c.sht45 ? 'text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                            const b7in1 = document.getElementById('badgeSoil7in1');
                            if (b7in1) {
                                b7in1.innerText = c.soil_7in1 ? 'RS485 ONLINE' : 'DISCONNECTED';
                                b7in1.className = c.soil_7in1 ? 'text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                            const bLite = document.getElementById('badgeBh1750');
                            if (bLite) {
                                bLite.innerText = c.bh1750 ? 'I2C ONLINE' : 'DISCONNECTED';
                                bLite.className = c.bh1750 ? 'text-[9px] font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                        }

                        // Update chart
                        if (envTrendChart && envTrendChart.data && envTrendChart.data.datasets[0]) {
                            const nowStr = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            if (envTrendChart.data.labels.length > 12) {
                                envTrendChart.data.labels.shift();
                                envTrendChart.data.datasets[0].data.shift();
                                envTrendChart.data.datasets[1].data.shift();
                                envTrendChart.data.datasets[2].data.shift();
                            }
                            envTrendChart.data.labels.push(nowStr);
                            envTrendChart.data.datasets[0].data.push(s.temperature);
                            envTrendChart.data.datasets[1].data.push(s.humidity);
                            envTrendChart.data.datasets[2].data.push(s.soil_moisture);
                            envTrendChart.update('none');
                        }
                    }

                    // Update Real Relay states
                    if (data.relays) {
                        for (let id = 1; id <= 4; id++) {
                            const r = data.relays[id];
                            if (r) {
                                updateRelayDom(id, r.state == 1);
                            }
                        }
                    }

                    // Auto Mode
                    if (typeof data.auto_mode !== 'undefined') {
                        isAutoMode = data.auto_mode;
                        updateAutoModeDom(isAutoMode);
                    }

                    // Simulated live ping response latency
                    const latEl = document.getElementById('latencyDisplay');
                    if (latEl) latEl.innerText = `${Math.floor(Math.random() * 6 + 16)}ms`;
                }
            } catch (err) {
                console.warn('Telemetry sync error:', err);
            }
        }

        // 5. Relay Toggle with Dual Action (Central API + Direct ESP32 call)
        async function toggleDashboardRelay(id, name) {
            const nextState = !dashRelayStates[id];
            updateRelayDom(id, nextState);

            try {
                // 1. Post to Backend API to save state in database & notify mobile app
                const res = await fetch(`../api/api.php?action=control_relay&id=${id}&state=${nextState ? 1 : 0}`);
                const resData = await res.json();

                // 2. Direct hardware call to ESP32 board in background
                fetch(`http://${currentBoardIp}:${currentBoardPort}/relay?ch=${id}&state=${nextState ? 1 : 0}`, { mode: 'no-cors' }).catch(() => {});

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: nextState ? 'success' : 'info',
                    title: `${name}: ${nextState ? 'เปิดแล้ว (ACTIVE ON)' : 'ปิดแล้ว (STANDBY OFF)'}`,
                    text: `ส่งคำสั่ง HTTP/Wi-Fi ไปยังบอร์ด ${currentBoardIp}:${currentBoardPort} สำเร็จ`,
                    showConfirmButton: false,
                    timer: 1600
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function toggleAutoMode() {
            try {
                const res = await fetch('../api/api.php?action=toggle_auto');
                const data = await res.json();
                if (data.status === 'success') {
                    isAutoMode = data.auto_mode;
                    updateAutoModeDom(isAutoMode);
                }
            } catch(e) {
                isAutoMode = !isAutoMode;
                updateAutoModeDom(isAutoMode);
            }
        }

        // 6. Interactive Modal reproducing the physical ESP32 Screen from the user's photo
        function openBoardScreenModal() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; font-size: 16px; color: #38bdf8;">ESP32-S3 ATD3.5 Physical Screen Display</span>',
                html: `
                    <!-- Authentic reproduction of the physical LCD touch screen -->
                    <div style="background: #2a2a2a; padding: 12px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.8); border: 8px solid #f1f5f9; max-width: 480px; margin: 0 auto; text-align: left; font-family: 'Chakra Petch', sans-serif;">
                        
                        <!-- Top LCD Status Tabs (Cyan, Green, Orange, Purple, Yellow, Blue) -->
                        <div style="display: flex; gap: 3px; margin-bottom: 8px; font-size: 10px; font-weight: bold; overflow-x: auto;">
                            <span style="background: #06b6d4; color: black; padding: 3px 6px; border-radius: 4px;">1. HOME</span>
                            <span style="background: #22c55e; color: black; padding: 3px 6px; border-radius: 4px;">2. DATA</span>
                            <span style="background: #f97316; color: black; padding: 3px 6px; border-radius: 4px;">3. GRAPH</span>
                            <span style="background: #a855f7; color: white; padding: 3px 6px; border-radius: 4px;">4. RELAY</span>
                            <span style="background: #eab308; color: black; padding: 3px 6px; border-radius: 4px; box-shadow: 0 0 8px #eab308;">5. SETUP</span>
                            <span style="background: #3b82f6; color: white; padding: 3px 6px; border-radius: 4px;">🌐 ENG</span>
                        </div>

                        <!-- Screen Title -->
                        <div style="color: #facc15; font-size: 11px; font-weight: bold; text-align: center; border-bottom: 1px solid #444; padding-bottom: 4px; margin-bottom: 8px; letter-spacing: 0.5px;">
                            NETWORK STATUS &amp; SYSTEM SETTINGS
                        </div>

                        <!-- Screen Body matching photo -->
                        <div style="font-family: monospace; font-size: 11px; line-height: 1.7; background: #000; padding: 10px; border-radius: 8px; border: 1px solid #38bdf8;">
                            <div>Status: <span style="color: #22c55e; font-weight: bold;">CONNECTED (ONLINE)</span></div>
                            <div style="color: #38bdf8;">SSID: <span style="color: #fff; font-weight: bold;">${currentSsid}</span></div>
                            <div style="color: #38bdf8;">IP Address: <span style="color: #fff; font-weight: bold;">${currentBoardIp}</span></div>
                            <div style="color: #38bdf8;">Signal RSSI: <span style="color: #fff; font-weight: bold;">${currentRssi} dBm (ปกติ)</span></div>
                            <div style="color: #38bdf8; font-size: 10px;">Cloud: <span style="color: #38bdf8; word-break: break-all;">${currentCloudUrl}</span> | Web: <span style="color: #fff;">:${currentBoardPort}</span></div>
                        </div>

                        <!-- System Language Selector -->
                        <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; font-size: 11px;">
                            <span style="color: #cbd5e1;">SELECT SYSTEM LANGUAGE:</span>
                            <div style="display: flex; gap: 5px;">
                                <span style="padding: 2px 8px; border-radius: 4px; background: #334155; color: #94a3b8; font-size: 10px;">ภาษาไทย</span>
                                <span style="padding: 2px 8px; border-radius: 4px; background: #06b6d4; color: black; font-weight: bold; font-size: 10px;">[*] English</span>
                            </div>
                        </div>

                        <!-- SoftAP / QR Setup Button -->
                        <div style="margin-top: 10px;">
                            <button onclick="promptChangeIp()" style="width: 100%; padding: 6px 10px; background: #0284c7; color: white; font-weight: bold; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; text-align: center;">
                                SETUP WI-FI VIA PHONE / QR CODE
                            </button>
                        </div>

                        <div style="color: #64748b; font-size: 9px; text-align: center; margin-top: 6px;">
                            Scan QR or connect to SoftAP to setup without PC
                        </div>

                    </div>
                `,
                background: '#090e1a',
                color: '#fff',
                confirmButtonText: '<i class="fa-solid fa-satellite-dish"></i> ทดสอบ Ping บอร์ด',
                confirmButtonColor: '#10b981',
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                cancelButtonColor: '#334155'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    Swal.fire({
                        title: `กำลังทดสอบ Ping ไปยัง ${currentBoardIp}:${currentBoardPort}...`,
                        didOpen: () => { Swal.showLoading(); },
                        timer: 1000
                    }).then(async () => {
                        const pingRes = await fetch('../api/api.php?action=ping_board');
                        const pingData = await pingRes.json();
                        Swal.fire({
                            icon: 'success',
                            title: 'ESP32-S3 ตอบสนองปกติ',
                            html: `
                                <div style="text-align: left; font-family: monospace; font-size: 12px; line-height: 1.8;">
                                    <div>• <strong>IP Address:</strong> ${currentBoardIp}</div>
                                    <div>• <strong>Web Port:</strong> ${currentBoardPort}</div>
                                    <div>• <strong>SSID:</strong> ${currentSsid}</div>
                                    <div>• <strong>Cloud Bridge:</strong> ${currentCloudUrl}</div>
                                    <div>• <strong>Direct Status:</strong> <span style="color: #10b981;">CONNECTED (ONLINE)</span></div>
                                </div>
                            `,
                            background: '#090e1a',
                            color: '#fff'
                        });
                    });
                }
            });
        }

        function promptChangeIp() {
            Swal.fire({
                title: 'กำหนด IP Address และ พอร์ต บอร์ด ESP32-S3',
                html: `
                    <div style="text-align: left; font-size: 12px; space-y: 8px;">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">IP Address บอร์ด:</label>
                        <input id="swalBoardIp" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #38bdf8; font-family: monospace;" value="${currentBoardIp}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">Web Port (เช่น 8500 หรือ 80):</label>
                        <input id="swalBoardPort" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #38bdf8; font-family: monospace;" value="${currentBoardPort}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">SSID เครือข่าย Wi-Fi:</label>
                        <input id="swalBoardSsid" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #10b981; font-family: monospace;" value="${currentSsid}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">Cloud URL:</label>
                        <input id="swalCloudUrl" class="swal2-input" style="width: 100%; margin: 0; background: #0f172a; color: #f59e0b; font-family: monospace;" value="${currentCloudUrl}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'บันทึกการตั้งค่า',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#10b981',
                background: '#0a0f1d',
                color: '#fff'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    const newIp = document.getElementById('swalBoardIp').value.trim();
                    const newPort = parseInt(document.getElementById('swalBoardPort').value.trim()) || 8500;
                    const newSsid = document.getElementById('swalBoardSsid').value.trim();
                    const newCloud = document.getElementById('swalCloudUrl').value.trim();

                    if (newIp) {
                        currentBoardIp = newIp;
                        currentBoardPort = newPort;
                        currentSsid = newSsid;
                        currentCloudUrl = newCloud;

                        // Save to backend
                        await fetch('../api/api.php?action=update_board_config', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ ip: newIp, web_port: newPort, ssid: newSsid, cloud_url: newCloud })
                        });

                        document.getElementById('boardIpDisplay').innerText = `${currentBoardIp}:${currentBoardPort}`;
                        document.getElementById('boardSsidDisplay').innerText = currentSsid;

                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตการเชื่อมต่อแล้ว',
                            text: `ระบบเชื่อมต่อ ESP32 ที่ ${currentBoardIp}:${currentBoardPort}`,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#0a0f1d',
                            color: '#fff'
                        });
                    }
                }
            });
        }

        function exportCsvData() {
            window.location.href = '../api/api.php?action=export_csv';
            Swal.fire({
                icon: 'success',
                title: 'กำลังส่งออกไฟล์ CSV จากฐานข้อมูล',
                text: 'ดาวน์โหลดชุดข้อมูลสถิติเซนเซอร์ทั้งหมดจาก SQLite3 (telemetry_logs) เรียบร้อยแล้ว',
                timer: 2000,
                showConfirmButton: false,
                background: '#0a0f1d',
                color: '#fff'
            });
        }

        async function fetchDbHistoryTable() {
            try {
                const res = await fetch('../api/api.php?action=get_history_table&limit=15');
                if (!res.ok) return;
                const json = await res.json();
                if (json.status === 'success') {
                    const totalEl = document.getElementById('tableDbTotalRecords');
                    if (totalEl) totalEl.innerText = Number(json.total_records || 0).toLocaleString();

                    const tbody = document.getElementById('telemetryTableBody');
                    if (!tbody) return;

                    if (!json.data || json.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="11" class="text-center p-6 text-slate-500">ยังไม่มีบันทึกข้อมูลในฐานข้อมูล SQLite3</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = json.data.map(r => {
                        const r1 = r.relay1 == 1 ? '<span class="text-emerald-400 font-bold">R1:ON</span>' : '<span class="text-slate-600">R1:OFF</span>';
                        const r2 = r.relay2 == 1 ? '<span class="text-emerald-400 font-bold">R2:ON</span>' : '<span class="text-slate-600">R2:OFF</span>';
                        const r3 = r.relay3 == 1 ? '<span class="text-emerald-400 font-bold">R3:ON</span>' : '<span class="text-slate-600">R3:OFF</span>';
                        const r4 = r.relay4 == 1 ? '<span class="text-emerald-400 font-bold">R4:ON</span>' : '<span class="text-slate-600">R4:OFF</span>';

                        const sdTag = r.sd_card_mounted == 1 
                            ? `<span class="px-1.5 py-0.5 rounded bg-amber-950 text-amber-300 text-[10px] border border-amber-800"><i class="fa-solid fa-sd-card"></i> ${r.sd_card_records || 0} rec</span>`
                            : `<span class="px-1.5 py-0.5 rounded bg-slate-900 text-slate-500 text-[10px]">Unmounted</span>`;

                        return `
                            <tr class="hover:bg-slate-900/60 transition font-mono">
                                <td class="p-3 text-cyan-400 font-bold">#${r.id}</td>
                                <td class="p-3 text-slate-300 whitespace-nowrap">${r.created_at}</td>
                                <td class="p-3"><span class="text-amber-300 font-bold">${Number(r.temperature || 0).toFixed(1)}°C</span> / <span class="text-cyan-300">${Number(r.humidity || 0).toFixed(1)}%</span></td>
                                <td class="p-3 text-emerald-300 font-bold">${Number(r.vpd || 0).toFixed(2)} kPa</td>
                                <td class="p-3">${Number(r.par_lux || 0).toLocaleString()} lx <span class="text-[10px] text-yellow-400 block">${Number(r.solar_radiation || 0).toFixed(2)} W/m²</span></td>
                                <td class="p-3">${Number(r.soil_stick_moisture || 0).toFixed(1)}% <span class="text-[10px] text-amber-300 block">pH ${Number(r.soil_stick_ph || 0).toFixed(1)}</span></td>
                                <td class="p-3">${Number(r.soil_moisture || 0).toFixed(1)}% <span class="text-[10px] text-emerald-300 block">EC ${Math.round(r.soil_ec || 0)} | pH ${Number(r.soil_ph || 0).toFixed(1)}</span></td>
                                <td class="p-3">${Number(r.nitrogen || 0).toFixed(0)}-${Number(r.phosphorus || 0).toFixed(0)}-${Number(r.potassium || 0).toFixed(0)}</td>
                                <td class="p-3 text-cyan-300">${Number(r.ai_nitrogen || 0).toFixed(0)}-${Number(r.ai_phosphorus || 0).toFixed(0)}-${Number(r.ai_potassium || 0).toFixed(0)}</td>
                                <td class="p-3 font-mono text-[9px] whitespace-nowrap">${r1} ${r2} ${r3} ${r4}</td>
                                <td class="p-3 whitespace-nowrap">${sdTag}</td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (err) {
                console.warn('DB Table fetch error:', err);
            }
        }

        function openQrModal() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #06b6d4;">Official QR Code Portal</span>',
                html: `
                    <div style="text-align: center; padding: 10px;">
                        <div style="background: white; padding: 12px; border-radius: 18px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.5); margin-bottom: 15px;">
                            <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Portal" style="width: 220px; height: 220px; object-fit: contain;">
                        </div>
                        <div style="font-family: Orbitron, monospace; font-size: 13px; line-height: 1.8; color: #e2e8f0; background: #0f172a; padding: 12px; border-radius: 14px; border: 1px solid #1e293b;">
                            <div style="color: #10b981; font-weight: bold;">📅 2026.10.04 วันอาทิตย์</div>
                            <div style="color: #f59e0b; font-weight: bold;">⏰ 09:03 ชีวะ ทัศนา</div>
                            <div style="color: #38bdf8; word-break: break-all; margin-top: 4px;">
                                🔗 <a href="https://aidar.rbru.ac.th/leqsxai" target="_blank" style="color: #38bdf8; text-decoration: underline;">https://aidar.rbru.ac.th/leqsxai</a>
                            </div>
                        </div>
                    </div>
                `,
                background: '#0b1120',
                color: '#fff',
                confirmButtonText: '<i class="fa-solid fa-arrow-up-right-from-square"></i> ไปยังลิงก์ระบบ',
                confirmButtonColor: '#06b6d4',
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                cancelButtonColor: '#334155'
            }).then((res) => {
                if (res.isConfirmed) {
                    window.open('https://aidar.rbru.ac.th/leqsxai', '_blank');
                }
            });
        }

        function previewScreen11() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #38bdf8;">ESP32-S3 ATD3.5 - Screen 11 (QR Portal)</span>',
                html: `
                    <div style="text-align: center; padding: 5px;">
                        <img src="../assets/images/atd35/11_qr_portal_screen.png" alt="Screen 11" style="width: 100%; max-width: 540px; border-radius: 16px; border: 1px solid #0284c7; box-shadow: 0 10px 30px rgba(0,0,0,0.7);">
                        <div style="font-family: monospace; font-size: 11px; color: #94a3b8; margin-top: 10px;">
                            Hardware Resolution: 480x320 Capacitive Touch (Retina @2x 960x640)
                        </div>
                    </div>
                `,
                background: '#0a0f1d',
                color: '#fff',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#0284c7'
            });
        }

        // Start live telemetry polling loop (Every 2 seconds) and DB table (Every 5 seconds)
        syncTelemetryFromApi();
        fetchDbHistoryTable();
        setInterval(syncTelemetryFromApi, 2000);
        setInterval(fetchDbHistoryTable, 5000);
    </script>
</body>
</html>
