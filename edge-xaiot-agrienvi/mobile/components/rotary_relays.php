            <!-- 3. THE 4 INTERACTIVE ROUNDED TOGGLE SWITCHES (EXACT VISUAL REPLICA) -->
            <div class="glass-inner-panel rounded-3xl p-4 sm:p-5 border border-white/5 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-white/5">
                    <span class="text-[11px] font-bold text-gray-400 font-tech tracking-wider uppercase flex items-center gap-1.5">
                        <i class="fa-solid fa-sliders text-emerald-400"></i> DIRECT RELAY ACTUATOR CONTROLS
                    </span>
                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-500/30">Wi-Fi GPIO</span>
                </div>

                <!-- Tri-Mode ROTARY DIAL CONTROLLER -->
                <div class="p-4 rounded-2xl space-y-4" style="background:linear-gradient(145deg,rgba(0,0,0,0.55),rgba(15,23,42,0.88));border:1px solid rgba(255,255,255,0.07);">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-gray-400 font-tech tracking-widest uppercase flex items-center gap-1.5">
                            <i class="fa-solid fa-rotate text-emerald-400"></i> Control Mode
                        </span>
                        <span id="mobModeLabel" class="text-xs font-mono px-3 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 font-bold tracking-wider">MANUAL</span>
                    </div>

                    <!-- SVG Rotary Dial -->
                    <!-- Geometry: center=(110,115), track-r=72 -->
                    <!-- Manual at 210°: (47.6,79)  Auto at 270°: (110,43)  AI at 330°: (172.4,79) -->
                    <div class="flex flex-col items-center gap-3">
                        <svg width="220" height="140" viewBox="0 0 220 140" style="overflow:visible;">
                            <defs>
                                <radialGradient id="knobGradRot" cx="38%" cy="32%" r="65%">
                                    <stop offset="0%" stop-color="#475569"/>
                                    <stop offset="55%" stop-color="#1e293b"/>
                                    <stop offset="100%" stop-color="#090e1a"/>
                                </radialGradient>
                            </defs>

                            <!-- Track arc background (120° arc, upper) -->
                            <path d="M 47.6 79 A 72 72 0 0 1 172.4 79"
                                  fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="11" stroke-linecap="round"/>

                            <!-- Active fill arc (JS updates d, stroke, opacity) -->
                            <path id="rotaryFillArc"
                                  d="M 47.6 79 A 72 72 0 0 1 110 43"
                                  fill="none" stroke="#f59e0b" stroke-width="11" stroke-linecap="round"
                                  style="opacity:0;filter:drop-shadow(0 0 10px #f59e0b88);transition:d 0.4s ease,stroke 0.3s,opacity 0.3s;"/>

                            <!-- Position dots on the arc track -->
                            <circle id="rotDotManual" cx="47.6" cy="79" r="7" fill="#f59e0b"
                                    style="filter:drop-shadow(0 0 10px #f59e0b);transition:all 0.3s;"/>
                            <circle id="rotDotAuto"   cx="110"   cy="43" r="5" fill="rgba(255,255,255,0.2)"
                                    style="transition:all 0.3s;"/>
                            <circle id="rotDotAI"    cx="172.4" cy="79" r="5" fill="rgba(255,255,255,0.2)"
                                    style="transition:all 0.3s;"/>

                            <!-- Knob drop shadow -->
                            <circle cx="112" cy="117" r="50" fill="rgba(0,0,0,0.55)"/>
                            <!-- Knob body -->
                            <circle cx="110" cy="115" r="50" fill="url(#knobGradRot)"
                                    stroke="rgba(255,255,255,0.13)" stroke-width="1.5"/>
                            <!-- Knob inner dashed ring -->
                            <circle cx="110" cy="115" r="44" fill="none"
                                    stroke="rgba(255,255,255,0.05)" stroke-width="1" stroke-dasharray="3,3"/>

                            <!-- Rotating pointer (transform-origin at knob center 110,115) -->
                            <g id="rotaryPointerGrp"
                               style="transform-origin:110px 115px;transform:rotate(-60deg);transition:transform 0.55s cubic-bezier(0.34,1.56,0.64,1);">
                                <!-- Needle body -->
                                <rect id="rotNeedleBody" x="108" y="70" width="4" height="41" rx="2" fill="#f59e0b"/>
                                <!-- Needle tip glowing dot -->
                                <circle id="rotNeedleTip" cx="110" cy="68" r="6.5" fill="#f59e0b"
                                        style="filter:drop-shadow(0 0 9px #f59e0b);"/>
                            </g>

                            <!-- Center cap -->
                            <circle cx="110" cy="115" r="12" fill="#080d18"
                                    stroke="rgba(255,255,255,0.22)" stroke-width="1.5"/>
                            <circle cx="110" cy="115" r="5" fill="rgba(255,255,255,0.3)"/>
                        </svg>

                        <!-- 3 Large Mode Tap Buttons -->
                        <div class="grid grid-cols-3 gap-3 w-full">
                            <!-- MANUAL -->
                            <button id="mobBtnModeManual" onclick="setMobileControlMode('manual')"
                                    class="flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-amber-500/15 border-amber-500/50 text-amber-300">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-amber-500/20 border border-amber-400/50">
                                    <i class="fa-solid fa-hand"></i>
                                </div>
                                <span class="text-[11px] font-bold font-tech tracking-widest">MANUAL</span>
                            </button>
                            <!-- AUTO -->
                            <button id="mobBtnModeAuto" onclick="setMobileControlMode('auto')"
                                    class="flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-slate-900/20 border-white/10 text-gray-500 hover:border-emerald-500/30">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-slate-800/80 border border-white/10">
                                    <i class="fa-solid fa-robot"></i>
                                </div>
                                <span class="text-[11px] font-bold font-tech tracking-widest">AUTO</span>
                            </button>
                            <!-- AI SMART -->
                            <button id="mobBtnModeAI" onclick="setMobileControlMode('ai')"
                                    class="flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-slate-900/20 border-white/10 text-gray-500 hover:border-cyan-500/30">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-slate-800/80 border border-white/10">
                                    <i class="fa-solid fa-brain"></i>
                                </div>
                                <span class="text-[11px] font-bold font-tech tracking-widest">AI SMART</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-1">
                    
                    <!-- SWITCH 1: Main Pump 1 (GPIO 39) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-3 hover:border-cyan-500/40 transition">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-cyan-400 text-3xl">
                            <i class="fa-solid fa-faucet-drip"></i>
                        </div>
                        <span class="text-base font-bold text-gray-200 font-tech">ปั๊มหลัก Pump 1</span>
                        <span class="text-xs font-mono text-cyan-400">GPIO 39</span>
                        <button id="switchPill-1" onclick="toggleExactRelay(1, 'ปั๊มน้ำหลัก (Main Pump 1)')" class="w-28 h-12 rounded-full neon-switch-inactive flex items-center justify-between px-3 transition-all duration-300">
                            <span class="w-8 h-8 rounded-full bg-gray-400 shadow-md flex-shrink-0"></span>
                            <span id="switchTxt-1" class="text-base font-bold text-gray-400 font-mono flex-1 text-center">OFF</span>
                        </button>
                        <span id="switchStatus-1" class="text-sm font-mono text-gray-500 font-bold">STANDBY</span>
                    </div>

                    <!-- SWITCH 2: Solenoid / Pump 2 (GPIO 38) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-3 hover:border-cyan-500/40 transition">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-cyan-300 text-3xl">
                            <i class="fa-solid fa-water"></i>
                        </div>
                        <span class="text-base font-bold text-gray-200 font-tech">โซลินอยด์/ปั๊ม 2</span>
                        <span class="text-xs font-mono text-cyan-400">GPIO 38</span>
                        <button id="switchPill-2" onclick="toggleExactRelay(2, 'วาล์วโซลินอยด์/ปั๊ม 2')" class="w-28 h-12 rounded-full neon-switch-inactive flex items-center justify-between px-3 transition-all duration-300">
                            <span class="w-8 h-8 rounded-full bg-gray-400 shadow-md flex-shrink-0"></span>
                            <span id="switchTxt-2" class="text-base font-bold text-gray-400 font-mono flex-1 text-center">OFF</span>
                        </button>
                        <span id="switchStatus-2" class="text-sm font-mono text-gray-500 font-bold">STANDBY</span>
                    </div>

                    <!-- SWITCH 3: Surface Valve (GPIO 7) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-3 hover:border-emerald-500/40 transition">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-emerald-400 text-3xl">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <span class="text-base font-bold text-gray-200 font-tech">วาล์วผิวดิน Surface</span>
                        <span class="text-xs font-mono text-emerald-400">GPIO 7</span>
                        <button id="switchPill-3" onclick="toggleExactRelay(3, 'วาล์วน้ำผิวดิน')" class="w-28 h-12 rounded-full neon-switch-inactive flex items-center justify-between px-3 transition-all duration-300">
                            <span class="w-8 h-8 rounded-full bg-gray-400 shadow-md flex-shrink-0"></span>
                            <span id="switchTxt-3" class="text-base font-bold text-gray-400 font-mono flex-1 text-center">OFF</span>
                        </button>
                        <span id="switchStatus-3" class="text-sm font-mono text-gray-500 font-bold">STANDBY</span>
                    </div>

                    <!-- SWITCH 4: Misting System (GPIO 6) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-white/5 flex flex-col items-center justify-center text-center space-y-3 hover:border-blue-500/40 transition">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-blue-400 text-3xl">
                            <i class="fa-solid fa-shower"></i>
                        </div>
                        <span class="text-base font-bold text-gray-200 font-tech">พ่นหมอก Misting</span>
                        <span class="text-xs font-mono text-blue-400">GPIO 6</span>
                        <button id="switchPill-4" onclick="toggleExactRelay(4, 'ระบบพ่นหมอก')" class="w-28 h-12 rounded-full neon-switch-inactive flex items-center justify-between px-3 transition-all duration-300">
                            <span class="w-8 h-8 rounded-full bg-gray-400 shadow-md flex-shrink-0"></span>
                            <span id="switchTxt-4" class="text-base font-bold text-gray-400 font-mono flex-1 text-center">OFF</span>
                        </button>
                        <span id="switchStatus-4" class="text-sm font-mono text-gray-500 font-bold">STANDBY</span>
                    </div>

                </div>
            </div>

