            <!-- 2. THE 6 CIRCULAR RADIAL GAUGES (EXACT VISUAL REPLICA) -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 py-2">
                
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
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Air Humidity</span>
                    <span class="text-[11px] font-mono text-cyan-400 font-medium">Ideal</span>
                </div>

                <!-- GAUGES 3: DUAL-DEPTH SOIL MONITOR (1. ผิวดิน 2. เขตราก 3. กรด-ด่าง pH) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-cyan-500/40 active:scale-95 transition-all duration-300 group select-none cursor-pointer relative" 
                     id="cardMobileSoilTriple"
                     onclick="cycleMobileSoilMode(true)" 
                     title="แตะเพื่อสลับดู: 1. ความชื้นผิวดิน 2. ความชื้นเขตราก 3. กรด-ด่างดิน (pH)">
                    <!-- Mode Indicator Pill with 3-Dot Carousel Indicator -->
                    <div class="flex items-center gap-1.5 mb-1">
                        <span id="mobileSoilModeBadge" class="text-[9px] font-mono font-bold text-cyan-300 bg-cyan-950/90 px-2 py-0.5 rounded-full border border-cyan-500/50 flex items-center gap-1 transition-colors duration-300">
                            <i id="mobileSoilModeIcon" class="fa-solid fa-droplet text-[8px] text-cyan-400"></i>
                            <span id="mobileSoilModeText">ผิวดิน (Surface)</span>
                        </span>
                        <!-- 3 Mini Dot Indicators -->
                        <div class="flex items-center gap-1 bg-slate-950/80 px-1.5 py-1 rounded-full border border-white/10" id="soilCarouselDots">
                            <span id="soilDot0" class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_6px_#06b6d4] transition-all duration-300"></span>
                            <span id="soilDot1" class="w-1.5 h-1.5 rounded-full bg-slate-600 transition-all duration-300"></span>
                            <span id="soilDot2" class="w-1.5 h-1.5 rounded-full bg-slate-600 transition-all duration-300"></span>
                        </div>
                    </div>

                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(6, 182, 212, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" id="circleSoilInner" />
                            <circle id="arcSoil" cx="60" cy="60" r="48" 
                                    stroke="url(#gradSoilMoist)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="116" 
                                    stroke-linecap="round" 
                                    class="transition-all duration-700 ease-out" />
                            <defs>
                                <!-- Mode 0: Surface Moisture Gradient (Cyan to Emerald) -->
                                <linearGradient id="gradSoilMoist" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#22c55e" />
                                    <stop offset="100%" stop-color="#06b6d4" />
                                </linearGradient>
                                <!-- Mode 1: Root Zone Moisture Gradient (Teal to Sky) -->
                                <linearGradient id="gradSoil7in1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#0ea5e9" />
                                    <stop offset="100%" stop-color="#14b8a6" />
                                </linearGradient>
                                <!-- Mode 2: Soil pH Gradient (Lime to Green) -->
                                <linearGradient id="gradSoilPh" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#84cc16" />
                                    <stop offset="100%" stop-color="#22c55e" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center transition-all duration-300" id="boxMobileSoilVal">
                            <div class="flex items-start">
                                <span id="gaugeValSoil" class="text-2xl font-bold font-mono text-cyan-300 leading-none tracking-tight">61.8</span>
                                <span id="gaugeUnitSoil" class="text-xs font-mono text-cyan-300 font-bold ml-0.5">%</span>
                            </div>
                            <span id="gaugeSubSoil" class="text-[10px] font-mono text-cyan-300/90 font-bold mt-0.5">ผิวดิน (0-10 cm)</span>
                        </div>
                    </div>
                    <span id="gaugeTitleSoil" class="text-xs font-bold text-gray-200 mt-2 font-tech flex items-center gap-1">
                        <span id="gaugeTitleSoilText">ความชื้นดิน</span>
                        <i class="fa-solid fa-arrows-rotate text-[10px] text-slate-500 animate-spin-slow"></i>
                    </span>
                    <span id="gaugeStatusSoil" class="text-[11px] font-mono text-cyan-400 font-medium">Ideal Moisture (พอดี)</span>
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

                <!-- GAUGES 5: SOIL pH (LIME / YELLOW GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-lime-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(132, 204, 22, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <circle id="arcPh" cx="60" cy="60" r="48" 
                                    stroke="url(#gradPhGauge)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="150" 
                                    stroke-linecap="round" 
                                    class="glow-lime transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradPhGauge" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#eab308" />
                                    <stop offset="100%" stop-color="#84cc16" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValPh" class="text-2xl font-bold font-mono text-white leading-none">6.5</span>
                                <span class="text-xs font-mono text-lime-400 font-bold ml-0.5">pH</span>
                            </div>
                            <span class="text-[11px] font-mono text-lime-400/90 font-bold mt-0.5">6.5</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Soil pH</span>
                    <span id="gaugeLabPh" class="text-[11px] font-mono text-lime-400 font-medium">Optimal</span>
                </div>

                <!-- GAUGES 6: LIGHT INTENSITY (AMBER / SUN GLOW) -->
                <div class="flex flex-col items-center justify-center p-3 rounded-3xl bg-slate-900/40 border border-white/5 hover:border-amber-500/30 transition group">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="48" stroke="rgba(255, 255, 255, 0.06)" stroke-width="7" fill="none" />
                            <circle cx="60" cy="60" r="38" stroke="rgba(251, 191, 36, 0.15)" stroke-width="2" fill="none" stroke-dasharray="4, 4" />
                            <circle id="arcLight" cx="60" cy="60" r="48" 
                                    stroke="url(#gradLightGauge)" stroke-width="7" fill="none" 
                                    stroke-dasharray="301.59" stroke-dashoffset="120" 
                                    stroke-linecap="round" 
                                    class="glow-orange transition-all duration-700" />
                            <defs>
                                <linearGradient id="gradLightGauge" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#f59e0b" />
                                    <stop offset="100%" stop-color="#fbbf24" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <div class="flex items-start">
                                <span id="gaugeValLight" class="text-2xl font-bold font-mono text-white leading-none">25.0</span>
                                <span class="text-[10px] font-mono text-amber-400 font-bold ml-0.5">kLx</span>
                            </div>
                            <span id="gaugeLabLight2" class="text-[11px] font-mono text-amber-400/90 font-bold mt-0.5">7.1 W/m²</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-200 mt-2 font-tech">Light Intensity</span>
                    <span id="gaugeLabLight" class="text-[11px] font-mono text-amber-400 font-medium">Moderate</span>
                </div>

            </div>

