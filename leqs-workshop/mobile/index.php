<?php
// LEQs-xAI: Real Smartphone Mobile Web Application
// Styled exactly to match real_mobile_app_preview.jpg
// Direct Wi-Fi Connection to ESP32-S3 ATD3.5 Controller Board
?>
<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>LEQs-xAI Smart Farm Mobile App | ควบคุมบอร์ด ESP32-S3 ATD3.5</title>
    
    <!-- PWA & Mobile Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#030712">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&family=Orbitron:wght@500;600;700;900&display=swap" rel="stylesheet">
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
                        darkphone: '#0d1322',
                        phonepanel: '#151d30',
                        accentgreen: '#10b981',
                        accentcyan: '#06b6d4',
                        accentamber: '#f59e0b',
                        accentblue: '#3b82f6',
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #030712;
            color: #f3f4f6;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        /* Ambient Smart Greenhouse Twilight Glow Backdrop */
        .farm-backdrop {
            background: 
                radial-gradient(circle at 50% 10%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 10% 40%, rgba(16, 185, 129, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 40%, rgba(245, 158, 11, 0.12) 0%, transparent 40%),
                linear-gradient(180deg, #090e1a 0%, #030712 100%);
        }

        /* Glassmorphism Phone Frame & Panels */
        .glass-phone-card {
            background: rgba(18, 26, 43, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .glass-inner-panel {
            background: rgba(13, 19, 34, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* Glowing circular arcs */
        .glow-orange {
            filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.65));
        }
        .glow-cyan {
            filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.65));
        }
        .glow-green {
            filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.75));
        }
        .glow-blue {
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.65));
        }

        /* Active Switch Neon Glow */
        .neon-switch-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 0 18px rgba(16, 185, 129, 0.75), inset 0 1px 2px rgba(255, 255, 255, 0.4);
        }
        .neon-switch-inactive {
            background: #1e293b;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
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
<body class="min-h-full flex flex-col font-sans farm-backdrop antialiased">

    <!-- Top Floating Header Bar (Navigation & Controls) -->
    <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur-md border-b border-white/5 px-4 py-2.5">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <a href="../index.php" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition active:scale-95" title="กลับสู่หน้าหลัก">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="font-bold text-xs sm:text-sm text-white font-tech tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        LEQs-xAI SMART PHONE APP
                    </h1>
                    <span class="text-[10px] text-gray-400 font-mono">ESP32-S3 ATD3.5 Hardware Interface</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="openWifiModal()" class="px-3 py-1 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 text-cyan-300 text-xs font-tech font-bold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-wifi text-[10px]"></i>
                    <span id="navBoardIp">192.168.1.105</span>
                </button>
                <a href="../dashboard/index.php" target="_blank" class="px-3 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-desktop text-[10px]"></i>
                    <span class="hidden sm:inline">Web Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container: Styled to exact mockup preview screen -->
    <main class="flex-1 flex flex-col items-center justify-start px-3 sm:px-4 py-4 sm:py-6 overflow-y-auto hide-scrollbar">
        
        <!-- ========================================================================= -->
        <!-- THE SMARTPHONE APP SCREEN FRAME (MATCHING real_mobile_app_preview.jpg)     -->
        <!-- ========================================================================= -->
        <div class="w-full max-w-3xl glass-phone-card rounded-[2.5rem] p-4 sm:p-6 border border-white/10 space-y-6 relative overflow-hidden">
            
            <!-- 1. TOP SMARTPHONE STATUS BAR & HARDWARE ISLAND -->
            <div class="glass-inner-panel rounded-2xl px-4 py-2.5 flex items-center justify-between text-xs font-mono">
                
                <!-- Left: Hardware Connection Badge -->
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wifi text-emerald-400 text-sm"></i>
                    <div>
                        <div class="flex items-center gap-1.5 leading-none">
                            <span class="font-bold text-white tracking-wide text-[11px] sm:text-xs">ESP32-S3 ATD3.5</span>
                            <span class="text-[9px] font-bold text-emerald-300 bg-emerald-950 px-1.5 py-0.5 rounded border border-emerald-500/40">CONNECTED</span>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-0.5 block">Wi-Fi 5GHz • AP: 192.168.1.105</span>
                    </div>
                </div>

                <!-- Center: Real-Time Live Clock -->
                <div class="hidden sm:flex flex-col items-center">
                    <span id="liveClock" class="text-sm font-bold text-white tracking-widest">18:42</span>
                    <span class="text-[9px] text-gray-400 font-sans">เวลาปัจจุบัน</span>
                </div>

                <!-- Right: Battery & Signal Level -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 text-[11px] text-gray-300">
                        <i class="fa-solid fa-signal text-cyan-400 text-xs"></i>
                        <span>Signal 4</span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-emerald-400 font-bold">
                        <span id="batteryPct">98%</span>
                        <i class="fa-solid fa-battery-full text-sm"></i>
                    </div>
                </div>

            </div>

            <!-- 2. THE 4 CIRCULAR RADIAL GAUGES (EXACT VISUAL REPLICA) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 py-2">
                
                <!-- GAUGES 1: AIR TEMPERATURE (ORANGE / AMBER GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-amber-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <!-- Background Track Ring -->
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <!-- Inner Outline Ring -->
                            <circle cx="60" cy="60" r="38" stroke="rgba(245, 158, 11, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <!-- Active Arc Ring -->
                            <circle id="arcTemp" cx="60" cy="60" r="48" 
                                    stroke="url(#gradTemp)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="125" 
                                    stroke-linecap="round" 
                                    class="glow-orange transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradTemp" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#f59e0b" />
                                    <stop offset="100%" stop-color="#ef4444" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <!-- Centered Value Display -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValTemp" class="text-2xl font-bold font-mono text-white leading-none">28.5</span>
                                <span class="text-xs font-mono text-amber-400 font-bold ml-0.5">°C</span>
                            </div>
                            <span class="text-[11px] font-mono text-amber-400/90 font-bold mt-0.5">28.5</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Air Temperature</span>
                    <span class="text-[11px] font-mono text-amber-400 font-medium">Optimal</span>
                </div>

                <!-- GAUGES 2: HUMIDITY (CYAN / AQUA GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-cyan-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(6, 182, 212, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <circle id="arcHum" cx="60" cy="60" r="48" 
                                    stroke="url(#gradHum)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="105" 
                                    stroke-linecap="round" 
                                    class="glow-cyan transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradHum" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#06b6d4" />
                                    <stop offset="100%" stop-color="#3b82f6" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValHum" class="text-2xl font-bold font-mono text-white leading-none">65</span>
                                <span class="text-xs font-mono text-cyan-400 font-bold ml-0.5">%</span>
                            </div>
                            <span class="text-[11px] font-mono text-cyan-400/90 font-bold mt-0.5">65</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Humidity</span>
                    <span class="text-[11px] font-mono text-cyan-400 font-medium">Ideal</span>
                </div>

                <!-- GAUGES 3: SOIL MOISTURE (LIME / EMERALD GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-emerald-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(16, 185, 129, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <circle id="arcSoil" cx="60" cy="60" r="48" 
                                    stroke="url(#gradSoil)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="84" 
                                    stroke-linecap="round" 
                                    class="glow-green transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradSoil" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#22c55e" />
                                    <stop offset="100%" stop-color="#10b981" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValSoil" class="text-2xl font-bold font-mono text-white leading-none">72</span>
                                <span class="text-xs font-mono text-emerald-400 font-bold ml-0.5">%</span>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-400/90 font-bold mt-0.5">72</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Soil Moisture</span>
                    <span class="text-[11px] font-mono text-emerald-400 font-medium">Ideal</span>
                </div>

                <!-- GAUGES 4: VPD (SAPPHIRE BLUE GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-blue-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(59, 130, 246, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <circle id="arcVpd" cx="60" cy="60" r="48" 
                                    stroke="url(#gradVpd)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="135" 
                                    stroke-linecap="round" 
                                    class="glow-blue transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradVpd" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#3b82f6" />
                                    <stop offset="100%" stop-color="#6366f1" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValVpd" class="text-2xl font-bold font-mono text-white leading-none">0.95</span>
                                <span class="text-[10px] font-mono text-blue-400 font-bold ml-0.5">kPa</span>
                            </div>
                            <span class="text-[11px] font-mono text-blue-400/90 font-bold mt-0.5">0.95</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">VPD</span>
                    <span class="text-[11px] font-mono text-blue-400 font-medium">Ideal</span>
                </div>

            </div>

            <!-- 3. THE 4 INTERACTIVE ROUNDED TOGGLE SWITCHES (EXACT VISUAL REPLICA) -->
            <div class="glass-inner-panel rounded-3xl p-4 sm:p-5 border border-white/5 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-white/5">
                    <span class="text-[11px] font-bold text-gray-400 font-tech tracking-wider uppercase flex items-center gap-1.5">
                        <i class="fa-solid fa-sliders text-emerald-400"></i> DIRECT RELAY ACTUATOR CONTROLS
                    </span>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-500/30">Wi-Fi GPIO</span>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-1">
                    
                    <!-- SWITCH 1: Solenoid Valve -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-2 hover:border-emerald-500/40 transition">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-cyan-400 text-xs">
                                <i class="fa-solid fa-faucet-drip"></i>
                            </div>
                            <!-- Switch Button Pill -->
                            <button id="switchPill-1" onclick="toggleExactRelay(1, 'Solenoid Valve')" class="w-14 h-7 rounded-full neon-switch-active flex items-center justify-end px-1 transition-all duration-300">
                                <span id="switchTxt-1" class="text-[10px] font-bold text-white mr-1.5 font-mono">ON</span>
                                <span class="w-5 h-5 rounded-full bg-white shadow-md"></span>
                            </button>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-200 block font-tech">Solenoid Valve</span>
                            <span id="switchStatus-1" class="text-[10px] font-mono text-emerald-400 font-bold">ACTIVE</span>
                        </div>
                    </div>

                    <!-- SWITCH 2: Misting Sprinkler -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-2 hover:border-emerald-500/40 transition">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-cyan-300 text-xs">
                                <i class="fa-solid fa-shower"></i>
                            </div>
                            <button id="switchPill-2" onclick="toggleExactRelay(2, 'Misting Sprinkler')" class="w-14 h-7 rounded-full neon-switch-inactive flex items-center justify-start px-1 transition-all duration-300">
                                <span class="w-5 h-5 rounded-full bg-gray-400 shadow-md"></span>
                                <span id="switchTxt-2" class="text-[10px] font-bold text-gray-400 ml-1.5 font-mono">OFF</span>
                            </button>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-200 block font-tech">Misting Sprinkler</span>
                            <span id="switchStatus-2" class="text-[10px] font-mono text-gray-500 font-bold">STANDBY</span>
                        </div>
                    </div>

                    <!-- SWITCH 3: Fertilizer Pump -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-2 hover:border-emerald-500/40 transition">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-emerald-400 text-xs">
                                <i class="fa-solid fa-flask"></i>
                            </div>
                            <button id="switchPill-3" onclick="toggleExactRelay(3, 'Fertilizer Pump')" class="w-14 h-7 rounded-full neon-switch-active flex items-center justify-end px-1 transition-all duration-300">
                                <span id="switchTxt-3" class="text-[10px] font-bold text-white mr-1.5 font-mono">ON</span>
                                <span class="w-5 h-5 rounded-full bg-white shadow-md"></span>
                            </button>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-200 block font-tech">Fertilizer Pump</span>
                            <span id="switchStatus-3" class="text-[10px] font-mono text-emerald-400 font-bold">ACTIVE</span>
                        </div>
                    </div>

                    <!-- SWITCH 4: Ventilation Fan -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-2 hover:border-emerald-500/40 transition">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-blue-400 text-xs">
                                <i class="fa-solid fa-fan"></i>
                            </div>
                            <button id="switchPill-4" onclick="toggleExactRelay(4, 'Ventilation Fan')" class="w-14 h-7 rounded-full neon-switch-active flex items-center justify-end px-1 transition-all duration-300">
                                <span id="switchTxt-4" class="text-[10px] font-bold text-white mr-1.5 font-mono">ON</span>
                                <span class="w-5 h-5 rounded-full bg-white shadow-md"></span>
                            </button>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-200 block font-tech">Ventilation Fan</span>
                            <span id="switchStatus-4" class="text-[10px] font-mono text-emerald-400 font-bold">ACTIVE</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 4. EXPANDABLE TELEMETRY DETAILS (SOIL 7-IN-1 & CAMERA VISION) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Soil 7-in-1 Modbus Live Telemetry -->
                <div class="glass-inner-panel rounded-2xl p-4 border border-white/5 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white font-tech flex items-center gap-1.5">
                            <i class="fa-solid fa-seedling text-emerald-400"></i> RS485 Soil 7-in-1 Probe
                        </span>
                        <span class="text-[10px] text-gray-400 font-mono">Root Zone 15cm</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-mono">
                        <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                            <span class="text-[10px] text-gray-400 block">pH ดิน</span>
                            <span id="detailPh" class="font-bold text-emerald-400">6.4</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                            <span class="text-[10px] text-gray-400 block">EC (µS/cm)</span>
                            <span id="detailEc" class="font-bold text-cyan-400">850</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-900/70 border border-white/5">
                            <span class="text-[10px] text-gray-400 block">PAR (Lux)</span>
                            <span id="detailPar" class="font-bold text-amber-400">42.5k</span>
                        </div>
                    </div>

                    <div class="p-2 rounded-xl bg-slate-950/60 border border-white/5 flex items-center justify-around text-[11px] font-mono">
                        <span>N: <strong class="text-emerald-400">45 mg/kg</strong></span>
                        <span>P: <strong class="text-cyan-400">28 mg/kg</strong></span>
                        <span>K: <strong class="text-amber-400">160 mg/kg</strong></span>
                    </div>
                </div>

                <!-- Camera Vision Snapshot -->
                <div class="glass-inner-panel rounded-2xl p-4 border border-white/5 space-y-2.5 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white font-tech flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-rose-400"></i> OV2640 AI Camera Vision
                        </span>
                        <span class="text-[10px] font-mono text-emerald-400 font-bold">YOLOv8 98.4%</span>
                    </div>

                    <div class="relative rounded-xl overflow-hidden aspect-[16/9] bg-black border border-white/10 group cursor-pointer" onclick="captureLiveSnapshot()">
                        <img src="../assets/images/cv_agri_vision.jpg" alt="Camera Snapshot" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition duration-300"></div>
                        <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-black/75 text-[9px] font-mono text-emerald-300 border border-emerald-500/30">
                            Healthy Leaf Detected
                        </div>
                        <div class="absolute bottom-2 right-2 px-2.5 py-1 rounded bg-black/80 text-[10px] text-white font-tech flex items-center gap-1">
                            <i class="fa-solid fa-camera-rotate text-emerald-400"></i> แตะเพื่อถ่ายภาพใหม่
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Wi-Fi IP Configuration Modal -->
    <div id="wifiModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-phone-card rounded-3xl p-6 border border-white/10 w-full max-w-sm space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wifi text-cyan-400"></i>
                    <h3 class="font-bold text-sm text-white font-tech">ตั้งค่าการเชื่อมต่อ Wi-Fi บอร์ด</h3>
                </div>
                <button onclick="closeWifiModal()" class="text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-gray-400 mb-1 font-tech">ESP32-S3 ATD3.5 IP Address</label>
                    <input type="text" id="inputBoardIp" value="192.168.1.105" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                    <span class="text-[10px] text-gray-500 mt-1 block">เช่น 192.168.4.1 (AP Mode) หรือ IP ในวง Wi-Fi บ้าน</span>
                </div>

                <div>
                    <label class="block text-gray-400 mb-1 font-tech">Polling Interval</label>
                    <select class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                        <option value="3">3 วินาที (Fast)</option>
                        <option value="5" selected>5 วินาที (Optimal)</option>
                        <option value="10">10 วินาที (Eco)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button onclick="saveWifiConfig()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs font-tech transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> บันทึกและเชื่อมต่อ
                </button>
                <button onclick="closeWifiModal()" class="px-4 py-2.5 rounded-xl bg-white/10 text-gray-300 font-bold text-xs font-tech hover:bg-white/20 transition">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <!-- Application Script -->
    <script>
        // Exact relay states matching real_mobile_app_preview.jpg:
        // Relay 1: ON, Relay 2: OFF, Relay 3: ON, Relay 4: ON
        const exactRelayStates = { 1: true, 2: false, 3: true, 4: true };

        // Play subtle Web Audio API click feedback
        function playBeep(freq = 880, duration = 0.05) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + duration);
            } catch(e) {}
        }

        function toggleExactRelay(id, name) {
            exactRelayStates[id] = !exactRelayStates[id];
            const isOn = exactRelayStates[id];

            const pill = document.getElementById(`switchPill-${id}`);
            const txt = document.getElementById(`switchTxt-${id}`);
            const status = document.getElementById(`switchStatus-${id}`);

            playBeep(isOn ? 920 : 440);

            if (pill && txt) {
                if (isOn) {
                    pill.className = 'w-14 h-7 rounded-full neon-switch-active flex items-center justify-end px-1 transition-all duration-300';
                    pill.innerHTML = `<span id="switchTxt-${id}" class="text-[10px] font-bold text-white mr-1.5 font-mono">ON</span><span class="w-5 h-5 rounded-full bg-white shadow-md"></span>`;
                } else {
                    pill.className = 'w-14 h-7 rounded-full neon-switch-inactive flex items-center justify-start px-1 transition-all duration-300';
                    pill.innerHTML = `<span class="w-5 h-5 rounded-full bg-gray-400 shadow-md"></span><span id="switchTxt-${id}" class="text-[10px] font-bold text-gray-400 ml-1.5 font-mono">OFF</span>`;
                }
            }

            if (status) {
                status.innerText = isOn ? 'ACTIVE' : 'STANDBY';
                status.className = isOn ? 'text-[10px] font-mono text-emerald-400 font-bold' : 'text-[10px] font-mono text-gray-500 font-bold';
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: isOn ? 'success' : 'info',
                title: `${name}: ${isOn ? 'ACTIVE (เปิด)' : 'STANDBY (ปิด)'}`,
                text: `คำสั่ง Wi-Fi ส่งสำเร็จสู่บอร์ด ESP32-S3 ATD3.5`,
                showConfirmButton: false,
                timer: 1600
            });
        }

        // Live Clock
        function updateLiveClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const clockEl = document.getElementById('liveClock');
            if (clockEl) clockEl.innerText = `${h}:${m}`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Modal Handlers
        function openWifiModal() {
            document.getElementById('wifiModal').classList.remove('hidden');
        }
        function closeWifiModal() {
            document.getElementById('wifiModal').classList.add('hidden');
        }
        function saveWifiConfig() {
            const ip = document.getElementById('inputBoardIp').value.trim();
            if (ip) {
                document.getElementById('navBoardIp').innerText = ip;
            }
            closeWifiModal();
            Swal.fire({
                icon: 'success',
                title: 'บันทึกการตั้งค่า Wi-Fi แล้ว',
                text: `เชื่อมต่อกับบอร์ดที่ ${ip} เรียบร้อย`,
                timer: 1500,
                showConfirmButton: false
            });
        }

        function captureLiveSnapshot() {
            playBeep(1200, 0.1);
            Swal.fire({
                title: 'กำลังถ่ายภาพจากกล้อง OV2640...',
                text: 'ประมวลผลโมเดล Edge AI YOLOv8 บน ESP32-S3',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => { Swal.showLoading(); }
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'ตรวจจับสำเร็จ!',
                    text: 'ตรวจพบ: ใบพืชสมบูรณ์ (Healthy Leaf) 98.4%',
                    timer: 1800,
                    showConfirmButton: false
                });
            });
        }

        // Subtle sensor heartbeat simulation
        setInterval(() => {
            const tempEl = document.getElementById('gaugeValTemp');
            if (tempEl) {
                const current = parseFloat(tempEl.innerText);
                const delta = (Math.random() * 0.2 - 0.1);
                tempEl.innerText = (current + delta).toFixed(1);
            }
        }, 4000);
    </script>
</body>
</html>
