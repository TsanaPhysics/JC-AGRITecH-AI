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

                <!-- Hardware Connection Chip -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs font-mono">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-emerald-400 font-bold">ESP32-S3 Wi-Fi:</span>
                    <span id="boardIpDisplay" class="text-cyan-300">192.168.1.105</span>
                    <span class="text-slate-500">|</span>
                    <span id="latencyDisplay" class="text-slate-300">22ms</span>
                </div>
            </div>

            <!-- Top Actions & Navigation Links -->
            <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                <button onclick="promptChangeIp()" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-cyan-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-network-wired text-[10px]"></i> ตั้งค่า IP บอร์ด
                </button>
                <button onclick="exportCsvData()" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-xs text-amber-300 font-mono flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-file-csv text-[10px]"></i> Export CSV
                </button>
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
        
        <!-- ROW 1: 4 Real-time Telemetry Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Air Temp & Humidity -->
            <div class="glass-box rounded-3xl p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-temperature-three-quarters text-amber-400"></i>
                        <span>อุณหภูมิอากาศ (SHT45)</span>
                    </div>
                    <span class="text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">I2C BUS</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashTemp" class="text-3xl font-bold font-mono text-white">28.5</span>
                        <span class="text-sm text-slate-400">°C</span>
                    </div>
                    <div class="text-right">
                        <span id="dashHum" class="text-xl font-bold font-mono text-cyan-400">65.2</span>
                        <span class="text-xs text-slate-400">%RH</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-800/60">
                    <span class="text-emerald-400 font-bold"><i class="fa-solid fa-circle-check"></i> สมบูรณ์ (Optimal)</span>
                    <span>Min 24.1 / Max 32.4</span>
                </div>
            </div>

            <!-- Card 2: VPD (Vapor Pressure Deficit) -->
            <div class="glass-box rounded-3xl p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-cyan-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-wind text-cyan-400"></i>
                        <span>แรงดึงระเหยน้ำ (VPD)</span>
                    </div>
                    <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">Calculated</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashVpd" class="text-3xl font-bold font-mono text-emerald-300">0.95</span>
                        <span class="text-sm text-slate-400">kPa</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-950 px-2 py-1 rounded">Transpiration OK</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-800/60">
                    <span>โซนเหมาะสม 0.8 - 1.2 kPa</span>
                    <span class="text-cyan-400 font-mono">Formula: SVP - AVP</span>
                </div>
            </div>

            <!-- Card 3: Soil Moisture & EC -->
            <div class="glass-box rounded-3xl p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-seedling text-emerald-400"></i>
                        <span>ความชื้นและสภาพดิน (7-in-1)</span>
                    </div>
                    <span class="text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">RS485 Modbus</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashSoilMoist" class="text-3xl font-bold font-mono text-white">72.4</span>
                        <span class="text-sm text-slate-400">%</span>
                    </div>
                    <div class="text-right">
                        <span id="dashSoilEc" class="text-xl font-bold font-mono text-emerald-400">850</span>
                        <span class="text-xs text-slate-400">µS/cm</span>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-800/60">
                    <span>pH: <strong id="dashSoilPh" class="text-cyan-300">6.4</strong> (กรดอ่อน)</span>
                    <span>Temp ดิน: <strong class="text-slate-300">26.8°C</strong></span>
                </div>
            </div>

            <!-- Card 4: Solar PAR & TinyML Inference -->
            <div class="glass-box rounded-3xl p-5 border border-slate-800/80 space-y-3 relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-sun text-amber-400"></i>
                        <span>แสงอาทิตย์ PAR (BH1750)</span>
                    </div>
                    <span class="text-[9px] font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">Edge AI</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <div>
                        <span id="dashPar" class="text-3xl font-bold font-mono text-white">42,500</span>
                        <span class="text-sm text-slate-400">Lux</span>
                    </div>
                    <span class="text-xs font-bold text-amber-400 bg-amber-950 px-2 py-1 rounded">Solar Active</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-800/60">
                    <span>On-Device TinyML:</span>
                    <span class="text-emerald-400 font-mono font-bold">Inference: 14ms</span>
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
                        <strong class="text-emerald-400">45 mg/kg</strong>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block">P (ฟอสฟอรัส)</span>
                        <strong class="text-cyan-400">28 mg/kg</strong>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                        <span class="text-[10px] text-slate-400 block">K (โพแทสเซียม)</span>
                        <strong class="text-amber-400">160 mg/kg</strong>
                    </div>
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
                    <div class="text-emerald-400">[System] Connected to ESP32-S3 ATD3.5 Controller at 192.168.1.105:80</div>
                    <div class="text-slate-400">[Telemetry] Ingested 24-point dataset: SHT45 (T:28.5C, RH:65.2%), Soil 7in1 (M:72.4%, EC:850, pH:6.4)</div>
                    <div class="text-cyan-400">[Engine] Automated evaluation cycle completed. All parameters within safe thresholds.</div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="glass-box border-t border-slate-800/80 px-6 py-4 mt-8 text-center text-xs text-slate-400">
        <p>LEQs-xAI Project: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม • มหาวิทยาลัยราชภัฏรำไพพรรณี</p>
        <p class="text-[10px] text-slate-500 mt-1">Direct Wi-Fi Hardware Integration for ESP32-S3 ATD3.5 Screen Controller</p>
    </footer>

    <!-- Dashboard JavaScript Logic -->
    <script>
        let currentBoardIp = '192.168.1.105';
        let isAutoMode = true;
        const dashRelayStates = { 1: false, 2: false, 3: false, 4: false };

        // 1. Initialize Chart.js Trend Line Chart
        const ctxTrend = document.getElementById('envTrendChart').getContext('2d');
        const timeLabels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '24:00'];
        const tempData = [24.5, 24.0, 23.8, 25.2, 27.5, 30.1, 32.4, 31.8, 29.5, 28.5, 27.2, 26.0, 25.5];
        const humData = [78, 80, 82, 75, 68, 60, 52, 55, 62, 65, 70, 74, 76];
        const soilData = [75, 75, 74, 73, 72, 70, 68, 66, 74, 72, 72, 71, 71];

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
                    data: [45, 28, 60, 72, 64, 55],
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

        // 3. Relay Toggle Handling
        function toggleDashboardRelay(id, name) {
            dashRelayStates[id] = !dashRelayStates[id];
            const isOn = dashRelayStates[id];

            const led = document.getElementById(`dash-led-${id}`);
            const btn = document.getElementById(`dash-btn-${id}`);
            const status = document.getElementById(`dash-status-${id}`);

            if (led) {
                led.className = isOn ? 'w-3 h-3 rounded-full bg-emerald-400 animate-ping inline-block' : 'w-3 h-3 rounded-full bg-slate-700 inline-block';
            }
            if (btn) {
                btn.className = isOn 
                    ? 'px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1'
                    : 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1';
                btn.innerHTML = isOn ? '<i class="fa-solid fa-power-off text-[10px]"></i> ปิด' : '<i class="fa-solid fa-power-off text-[10px]"></i> เปิด';
            }
            if (status) {
                status.innerText = isOn ? 'ACTIVE (ON)' : 'STANDBY (OFF)';
                status.className = isOn ? 'text-[10px] font-bold text-emerald-400' : 'text-[10px] text-slate-400';
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: isOn ? 'success' : 'info',
                title: `${name}: ${isOn ? 'เปิดการทำงานแล้ว' : 'ปิดการทำงานแล้ว'}`,
                text: `ส่งคำสั่ง HTTP/Wi-Fi ไปยัง ${currentBoardIp} สำเร็จ`,
                showConfirmButton: false,
                timer: 1600
            });
        }

        function toggleAutoMode() {
            isAutoMode = !isAutoMode;
            const btn = document.getElementById('modeToggleBtn');
            if (isAutoMode) {
                btn.className = 'px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30';
                btn.innerHTML = '<i class="fa-solid fa-robot mr-1"></i> Smart Auto';
            } else {
                btn.className = 'px-3 py-1 rounded-xl bg-amber-500/20 text-amber-300 font-bold border border-amber-500/30';
                btn.innerHTML = '<i class="fa-solid fa-hand mr-1"></i> Manual Override';
            }
        }

        function promptChangeIp() {
            Swal.fire({
                title: 'กำหนด IP Address บอร์ด ESP32-S3',
                input: 'text',
                inputValue: currentBoardIp,
                text: 'ใส่ที่อยู่ IP สำหรับการสื่อสารผ่าน Wi-Fi เช่น 192.168.1.105 หรือ 192.168.4.1 (AP Mode)',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#10b981'
            }).then((res) => {
                if (res.isConfirmed && res.value) {
                    currentBoardIp = res.value.trim();
                    document.getElementById('boardIpDisplay').innerText = currentBoardIp;
                    Swal.fire({
                        icon: 'success',
                        title: 'อัปเดต IP แล้ว',
                        text: `ระบบจะเรียก API ไปยัง ${currentBoardIp}`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function exportCsvData() {
            const rows = [
                ['Timestamp', 'Temperature_C', 'Humidity_RH', 'SoilMoisture_Pct', 'VPD_kPa', 'Soil_EC', 'Soil_pH', 'PAR_Lux'],
                ['2026-10-04 00:00:00', '28.5', '65.2', '72.4', '0.95', '850', '6.4', '42500'],
                ['2026-10-04 00:05:00', '28.6', '65.0', '72.3', '0.96', '852', '6.4', '42480'],
                ['2026-10-04 00:10:00', '28.4', '65.5', '72.5', '0.94', '848', '6.4', '42520']
            ];
            let csvContent = 'data:text/csv;charset=utf-8,' + rows.map(e => e.join(',')).join('\n');
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `leqs_smartfarm_telemetry_${Date.now()}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'ส่งออกไฟล์ CSV สำเร็จ',
                text: 'ดาวน์โหลดชุดข้อมูลสถิติเซนเซอร์เรียบร้อยแล้ว',
                timer: 1800,
                showConfirmButton: false
            });
        }
    </script>
</body>
</html>
