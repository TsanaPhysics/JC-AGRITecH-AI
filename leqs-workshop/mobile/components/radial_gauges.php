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
                                <span id="gaugeValSoil" class="text-2xl font-bold font-mono text-white leading-none">72.0</span>
                                <span class="text-xs font-mono text-emerald-400 font-bold ml-0.5">%</span>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-400/90 font-bold mt-0.5">72.0</span>
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

