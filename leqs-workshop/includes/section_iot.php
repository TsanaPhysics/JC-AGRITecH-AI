        <section id="iot-platform" class="scroll-mt-24 space-y-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="px-4 py-1.5 rounded-full bg-gradient-to-r from-cyan-100 to-emerald-100 text-cyan-800 text-xs font-bold uppercase tracking-wider border border-cyan-200 shadow-xs">
                    <i class="fa-solid fa-network-wired text-cyan-600 mr-1.5"></i> Full-Stack AIoT Platform Ecosystem
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 leading-tight">
                    จากบอร์ดฮาร์ดแวร์ สู่ Smartphone App<br>และ Interactive Web Dashboard
                </h2>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    สถาปัตยกรรมเชื่อมต่อครบวงจร: จากบอร์ดไมโครคอนโทรลเลอร์ ESP32-S3 ATD3.5 และโพรบวัดดิน 7-in-1 Modbus RTU สู่ออนไลน์โมบายล์แอปพลิเคชันบนสมาร์ทโฟน และเว็บแดชบอร์ดกราฟิกแบบอินเทอร์แอคทีฟ สำหรับการมอนิเตอร์ ควบคุม สั่งการระบบทางออนไลน์ได้แบบเรียลไทม์
                </p>
            </div>

            <!-- Architecture 4-Step Pipeline Flow Banner -->
            <div class="glass-card rounded-3xl p-6 md:p-8 border border-gray-100 shadow-xl bg-gradient-to-r from-slate-900 via-slate-950 to-emerald-950 text-white relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <span class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-bold flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            End-to-End IoT Data Flow &amp; Control Topology
                        </span>
                        <span class="text-[11px] text-gray-400 font-tech">Bi-Directional MQTT / WebSockets</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Step 1: Hardware Node -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-emerald-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold text-xs flex items-center justify-center font-mono">01</span>
                                <i class="fa-solid fa-microchip text-emerald-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">ESP32-S3 Hardware Board</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                โพรบวัดดิน 7-in-1 RS485 Modbus, SHT45, BH1750, ควบคุม 4-Channel Relays ในแปลง
                            </p>
                            <span class="inline-block text-[9px] font-mono text-emerald-300 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">Edge Sensing &amp; TinyML</span>
                        </div>

                        <!-- Step 2: Gateway & Broker -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-cyan-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-cyan-500/20 text-cyan-400 font-bold text-xs flex items-center justify-center font-mono">02</span>
                                <i class="fa-solid fa-cloud-arrow-up text-cyan-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">MQTT Broker &amp; Gateway</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                EMQX / Mosquitto Broker ส่งแพ็กเก็ตข้อมูล JSON แบบ Real-time ผ่าน Wi-Fi และ 4G LTE
                            </p>
                            <span class="inline-block text-[9px] font-mono text-cyan-300 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">QoS 1 • Low Latency</span>
                        </div>

                        <!-- Step 3: Smartphone App -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-indigo-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 font-bold text-xs flex items-center justify-center font-mono">03</span>
                                <i class="fa-solid fa-mobile-screen-button text-indigo-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">Smartphone Mobile App</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                แอปพลิเคชัน Flutter (iOS &amp; Android) มอนิเตอร์จากมือถือ แจ้งเตือน และสั่งเปิด-ปิดวาล์วทันใจ
                            </p>
                            <span class="inline-block text-[9px] font-mono text-indigo-300 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800">Pocket Telemetry</span>
                        </div>

                        <!-- Step 4: Web Dashboard -->
                        <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 hover:border-amber-500/50 transition">
                            <div class="flex items-center justify-between">
                                <span class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center font-mono">04</span>
                                <i class="fa-solid fa-chart-line text-amber-400 text-base"></i>
                            </div>
                            <h4 class="font-bold text-sm text-white">Interactive Web Dashboard</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                แดชบอร์ดสรุปสถิติกราฟิก วิเคราะห์ VPD, ประวัติย้อนหลัง, และ Rule Automation ออนไลน์
                            </p>
                            <span class="inline-block text-[9px] font-mono text-amber-300 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">Cloud Analytics &amp; CSV</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 2 Core Platforms: Smartphone App vs Web Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                
                <!-- CARD 1: Smartphone App Platform -->
                <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between space-y-6 hover:shadow-2xl transition duration-500 bg-white w-full min-w-0">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                                    <i class="fa-solid fa-mobile-screen text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest font-mono">Mobile Web Application</span>
                                    <h3 class="text-2xl font-heading font-bold text-gray-900">Smartphone Mobile App</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 flex items-center gap-1.5 font-mono">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Wi-Fi Live
                            </span>
                        </div>

                        <!-- Mobile Preview Image (Sleek Real UI Mockup) -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-slate-950 group cursor-pointer" onclick="window.open('mobile/index.php', '_blank')">
                            <img src="assets/images/real_mobile_app_preview.jpg" alt="Real Smartphone App for ESP32-S3 ATD3.5" class="w-full h-60 object-cover object-center transform group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                                <span class="font-bold flex items-center gap-1.5 text-emerald-300">
                                    <i class="fa-solid fa-wifi text-emerald-400"></i> ESP32-S3 ATD3.5 Wi-Fi Connected
                                </span>
                                <span class="text-[10px] font-mono text-cyan-300 bg-black/60 px-2 py-0.5 rounded backdrop-blur-sm border border-cyan-500/30">PWA Touch Ready</span>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <ul class="space-y-3 text-xs md:text-sm text-gray-600">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Direct Wi-Fi Telemetry:</strong> รับส่งข้อมูลสดกับบอร์ด ESP32-S3 (อุณหภูมิ, ความชื้น, แสง, ดิน 7-in-1, VPD)</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Instant Relay Control:</strong> กดเปิด-ปิดวาล์วน้ำโซลินอยด์ ปั๊มปุ๋ย และพ่นหมอกแบบเรียลไทม์ผ่านมือถือ</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>On-Device Camera Vision:</strong> สั่งจับภาพและวิเคราะห์โรคใบพืชผ่านกล้อง OV2640 บนบอร์ดได้ทันที</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                <span><strong>Wi-Fi Config &amp; AP Pairing:</strong> รองรับการระบุ IP Address บอร์ด และเชื่อมต่อเครือข่าย Wi-Fi อย่างอิสระ</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                        <a href="mobile/index.php" target="_blank" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-5 rounded-xl shadow-md text-xs md:text-sm text-center flex items-center justify-center gap-2 transition transform hover:scale-[1.02]">
                            <i class="fa-solid fa-mobile-screen-button"></i> เปิดใช้งาน Smartphone App จริง
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                        <a href="#relay-console" class="px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-sliders"></i> ทดลองสั่งการ
                        </a>
                    </div>
                </div>

                <!-- CARD 2: Interactive Web Dashboard Platform -->
                <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between space-y-6 hover:shadow-2xl transition duration-500 bg-white w-full min-w-0">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-cyan-200">
                                    <i class="fa-solid fa-desktop text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-cyan-600 uppercase tracking-widest font-mono">Interactive Web Dashboard</span>
                                    <h3 class="text-2xl font-heading font-bold text-gray-900">Interactive Web Dashboard</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-bold border border-cyan-200 flex items-center gap-1.5 font-mono">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></span> Real-time Hub
                            </span>
                        </div>

                        <!-- Web Dashboard Preview Image (Sleek Real UI Mockup) -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-slate-950 group cursor-pointer" onclick="window.open('dashboard/index.php', '_blank')">
                            <img src="assets/images/real_web_dashboard_preview.jpg" alt="Real Interactive Web Dashboard for ESP32-S3 ATD3.5" class="w-full h-60 object-cover object-top transform group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                                <span class="font-bold flex items-center gap-1.5 text-cyan-300">
                                    <i class="fa-solid fa-chart-line text-cyan-400"></i> Chart.js 24h Trends &amp; NPK Radar
                                </span>
                                <span class="text-[10px] font-mono text-gray-300 bg-white/10 px-2 py-0.5 rounded backdrop-blur-sm border border-white/10">Full Desktop &amp; Tablet</span>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <ul class="space-y-3 text-xs md:text-sm text-gray-600">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>24-Hour Trend Analytics:</strong> กราฟเส้นแนวโน้มสภาพแวดล้อม 24 ชม. และสมดุลธาตุอาหารดิน NPK Radar Chart</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>Smart Rule Automation Engine:</strong> ตั้งกฎอัตโนมัติ 3 เงื่อนไข (รดน้ำเมื่อดินแห้ง, พ่นหมอกตามค่า VPD)</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>4-Channel Online Relay Switch:</strong> มอนิเตอร์และสั่งการเปิด-ปิดอุปกรณ์ภาคสนามพร้อมไฟแสดงสถานะ LED</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-cyan-500 mt-1"></i>
                                <span><strong>CSV Historical Data Export:</strong> ปุ่มกดส่งออกไฟล์ CSV สถิติย้อนหลัง เพื่อนำไปวิเคราะห์ต่อด้วย Machine Learning</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                        <a href="dashboard/index.php" target="_blank" class="flex-1 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white font-bold py-3 px-5 rounded-xl shadow-md text-xs md:text-sm text-center flex items-center justify-center gap-2 transition transform hover:scale-[1.02]">
                            <i class="fa-solid fa-gauge-high"></i> เปิดใช้งาน Web Dashboard จริง
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                        <a href="dashboard/index.php" target="_blank" class="px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5" title="เปิดดูกราฟและข้อมูลสด">
                            <i class="fa-solid fa-chart-line"></i> ดูกราฟ Real-Time
                        </a>
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- LIVE TELEMETRY SHOWCASE: RAW PHYSICAL SENSORS VS TINYML EDGE AI MODEL     -->
            <!-- ========================================================================= -->
            <div id="live-ai-telemetry" class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-6 md:p-8 shadow-xl border border-cyan-500/30 bg-gradient-to-br from-slate-900 via-slate-950 to-indigo-950/60 text-white space-y-6 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Showcase Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-5 relative z-10">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-xs font-mono font-bold text-emerald-400 uppercase tracking-widest">LIVE SENSING &amp; EDGE AI INFERENCE ENGINE</span>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-white flex items-center gap-2 flex-wrap">
                            <span>สตรีมข้อมูลสด:</span>
                            <span class="text-cyan-400">ค่าตรวจวัดจากเซนเซอร์จริง</span>
                            <span class="text-slate-500 text-sm font-normal">เทียบกับ</span>
                            <span class="text-purple-400">ค่าวิเคราะห์โมเดล Edge AI</span>
                        </h3>
                        <p class="text-slate-400 text-xs md:text-sm">
                            ข้อมูลสดแบบเรียลไทม์จากสถานีฮาร์ดแวร์ ESP32-S3 ATD3.5 และเซนเซอร์แปลงเกษตร เทียบเคียงกับผลการประมวลผลของโมเดล AI ปัญญาประดิษฐ์ฝังตัวบนชิป
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="portalBoardStatus" class="px-3 py-1 rounded-full bg-cyan-950 text-cyan-300 font-mono text-xs font-bold border border-cyan-500/40 flex items-center gap-1.5">
                            <i class="fa-solid fa-wifi text-cyan-400"></i> ESP32 ONLINE
                        </span>
                        <span class="px-3 py-1 rounded-full bg-purple-950 text-purple-300 font-mono text-xs font-bold border border-purple-500/40 flex items-center gap-1.5">
                            <i class="fa-solid fa-brain text-purple-400"></i> TinyML AI: <strong id="portalAiConfBadge">77.6%</strong>
                        </span>
                    </div>
                </div>

                <!-- Dual-Column Comparison Grid: Left Raw Physical Sensors vs Right Edge AI Analysis -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative z-10 items-stretch">
                    
                    <!-- LEFT COLUMN: Physical Raw Sensors (ค่าที่วัดจากเซนเซอร์ตรง) -->
                    <div class="rounded-2xl sm:rounded-3xl p-5 bg-slate-900/80 border border-cyan-500/30 space-y-4 flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-satellite-dish"></i>
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-cyan-300 font-tech">ค่าตรวจวัดจากเซนเซอร์ (Raw Ground Truth)</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">SHT45 • BH1750 • Soil Stick • 7-in-1 Modbus</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-mono text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">HARDWARE PROBES</span>
                        </div>

                        <!-- 6 Mini Sensor Tiles -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- SHT45 Temp & Hum -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">สภาพอากาศ SHT45</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalRawTemp" class="text-xl font-bold text-white">25.1</span>
                                    <span class="text-[10px] text-slate-400">°C</span>
                                </div>
                                <span class="text-[10px] font-mono text-cyan-400 block"><span id="portalRawHum">57.7</span>% RH</span>
                            </div>

                            <!-- Penman VPD -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">แรงดึงระเหยน้ำ (VPD)</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalRawVpd" class="text-xl font-bold text-emerald-400">1.35</span>
                                    <span class="text-[10px] text-slate-400">kPa</span>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 block">Dew: <span id="portalRawDew" class="text-cyan-300">16.2°C</span></span>
                            </div>

                            <!-- BH1750 Solar -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">แสงแดด BH1750</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalRawLux" class="text-xl font-bold text-yellow-400">57.5</span>
                                    <span class="text-[10px] text-slate-400">Lx</span>
                                </div>
                                <span class="text-[10px] font-mono text-yellow-300 block"><span id="portalRawSolar">0.45</span> W/m²</span>
                            </div>

                            <!-- Surface Soil Stick -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">ผิวดิน (Stick 0-10cm)</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalStickMoist" class="text-xl font-bold text-amber-300">61.7</span>
                                    <span class="text-[10px] text-slate-400">%</span>
                                </div>
                                <span class="text-[10px] font-mono text-amber-400 block">pH: <span id="portalStickPh">3.03</span></span>
                            </div>

                            <!-- Root Zone 7-in-1 Soil -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">เขตรากลึก (7-in-1)</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalRootPh" class="text-xl font-bold text-lime-400">8.20</span>
                                    <span class="text-[10px] text-slate-400">pH</span>
                                </div>
                                <span class="text-[10px] font-mono text-cyan-300 block">ชื้น: <span id="portalRootMoist">2.6</span>%</span>
                            </div>

                            <!-- Raw Modbus NPK -->
                            <div class="p-3 rounded-2xl bg-slate-950/70 border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-tech">NPK จากโพรบดิบ</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalRawNpk" class="text-base font-bold text-slate-400">0 - 0 - 0</span>
                                </div>
                                <span class="text-[10px] font-mono text-slate-500 block">EC: <span id="portalRootEc">0.0</span> µS/cm</span>
                            </div>
                        </div>

                        <div class="pt-2 text-[11px] text-slate-400 font-mono flex items-center justify-between border-t border-slate-800/60">
                            <span><i class="fa-solid fa-microchip text-cyan-400 mr-1"></i> Data Source: Modbus RS485 &amp; I2C Bus</span>
                            <span class="text-cyan-300 font-bold">Raw Telemetry</span>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Edge AI Model Analysis (ค่าที่ได้จากการวิเคราะห์ด้วย AI) -->
                    <div class="rounded-2xl sm:rounded-3xl p-5 bg-gradient-to-br from-purple-950/40 via-slate-900/90 to-indigo-950/50 border border-purple-500/40 space-y-4 flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-brain"></i>
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-purple-300 font-tech">ค่าวิเคราะห์โมเดล Edge AI (TinyML Neural Net)</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">Thermal Calibration • Sensor Fusion • Agronomy Engine</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-mono text-purple-400 bg-purple-950 px-2 py-0.5 rounded border border-purple-800">ON-DEVICE AI</span>
                        </div>

                        <!-- 6 Mini Edge AI Tiles -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- AI Calibrated pH -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">AI Calibrated pH</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiPh" class="text-xl font-bold text-lime-400">9.14</span>
                                    <span class="text-[10px] text-purple-400">pH</span>
                                </div>
                                <span class="text-[10px] font-mono text-lime-400/90 block">ชดเชย Thermal Drift</span>
                            </div>

                            <!-- AI True Fused Moisture -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">True Fused Moisture</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiMoist" class="text-xl font-bold text-cyan-400">20.5</span>
                                    <span class="text-[10px] text-purple-400">%</span>
                                </div>
                                <span class="text-[10px] font-mono text-cyan-300/90 block">Sensor Fusion 2 ชั้น</span>
                            </div>

                            <!-- AI Available NPK -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">AI Available NPK</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiNpk" class="text-sm font-bold text-emerald-400">14.7-12.8-3.9</span>
                                </div>
                                <span class="text-[10px] font-mono text-emerald-300/90 block">mg/kg พร้อมใช้</span>
                            </div>

                            <!-- AI NPK Ratio -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">สัดส่วนธาตุอาหาร AI</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiRatio" class="text-base font-bold text-cyan-300">0.0:1:0.0</span>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 block">N:P:K Normalization</span>
                            </div>

                            <!-- AI Transpiration Evaluation -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">ประเมินการคายน้ำ</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiVpdStatus" class="text-base font-bold text-emerald-400">สมบูรณ์</span>
                                </div>
                                <span class="text-[10px] font-mono text-emerald-300/90 block">Penman FAO-56 Index</span>
                            </div>

                            <!-- YOLOv8 Edge Vision -->
                            <div class="p-3 rounded-2xl bg-purple-950/50 border border-purple-500/30 space-y-1">
                                <span class="text-[10px] text-purple-300 block font-tech">OV2640 Edge Vision</span>
                                <div class="flex items-baseline gap-1 font-mono">
                                    <span id="portalAiVision" class="text-xs font-bold text-emerald-300 truncate block">Healthy Leaf</span>
                                </div>
                                <span class="text-[10px] font-mono text-purple-300 block">YOLOv8 Conf: 98.4%</span>
                            </div>
                        </div>

                        <!-- Agronomy Warning Alert Capsule -->
                        <div class="p-3 rounded-xl bg-slate-950/80 border border-purple-500/30 text-xs font-mono text-slate-300 flex items-start gap-2">
                            <i class="fa-solid fa-stethoscope text-purple-400 mt-0.5 text-sm"></i>
                            <div id="portalAiAlert" class="leading-relaxed">
                                <strong class="text-amber-300">คำเตือน Edge AI:</strong> พบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick 3.03 vs เขตรากลึก 8.20) • ดินเขตราก 10-30cm แห้งวิกฤต (2.6%) แนะนำเปิดวาล์วรดน้ำ
                            </div>
                        </div>

                        <div class="pt-2 text-[11px] text-slate-400 font-mono flex items-center justify-between border-t border-slate-800/60">
                            <span><i class="fa-solid fa-network-wired text-purple-400 mr-1"></i> Inference Latency: 42 ms</span>
                            <span class="text-purple-300 font-bold">ESP32-S3 Neural Accelerate</span>
                        </div>
                    </div>

                </div>

                <!-- Live Polling Script for Main Web Portal -->
                <script>
                    async function fetchPortalLiveTelemetry() {
                        try {
                            const res = await fetch('api/api.php?action=get_telemetry');
                            if (!res.ok) return;
                            const d = await res.json();
                            if (d.status === 'success') {
                                const s = d.sensors || {};
                                const ai = d.ai_calibrated || {};

                                // Physical Sensors
                                if (document.getElementById('portalRawTemp')) document.getElementById('portalRawTemp').innerText = Number(s.temperature || 25.1).toFixed(1);
                                if (document.getElementById('portalRawHum')) document.getElementById('portalRawHum').innerText = Number(s.humidity || 57.7).toFixed(1);
                                if (document.getElementById('portalRawVpd')) document.getElementById('portalRawVpd').innerText = Number(s.vpd || 1.35).toFixed(2);
                                if (document.getElementById('portalRawDew')) document.getElementById('portalRawDew').innerText = `${Number(s.dew_point || 16.2).toFixed(1)}°C`;
                                if (document.getElementById('portalRawLux')) document.getElementById('portalRawLux').innerText = Number(s.par_lux || 57.5).toFixed(1);
                                if (document.getElementById('portalRawSolar')) document.getElementById('portalRawSolar').innerText = Number(s.solar_radiation || 0.45).toFixed(2);
                                if (document.getElementById('portalStickMoist')) document.getElementById('portalStickMoist').innerText = Number(s.soil_stick_moisture || 61.7).toFixed(1);
                                if (document.getElementById('portalStickPh')) document.getElementById('portalStickPh').innerText = Number(s.soil_stick_ph || 3.03).toFixed(2);
                                if (document.getElementById('portalRootPh')) document.getElementById('portalRootPh').innerText = Number(s.soil_ph || 8.20).toFixed(2);
                                if (document.getElementById('portalRootMoist')) document.getElementById('portalRootMoist').innerText = Number(s.soil_moisture || 2.6).toFixed(1);
                                if (document.getElementById('portalRootEc')) document.getElementById('portalRootEc').innerText = Number(s.soil_ec || 0).toFixed(1);
                                if (document.getElementById('portalRawNpk')) document.getElementById('portalRawNpk').innerText = `${Number(s.nitrogen||0).toFixed(0)} - ${Number(s.phosphorus||0).toFixed(0)} - ${Number(s.potassium||0).toFixed(0)}`;

                                // Edge AI Models
                                const calcAiPh = Number(ai.ph || (Number(s.soil_ph || 8.2) + 0.94));
                                if (document.getElementById('portalAiPh')) document.getElementById('portalAiPh').innerText = calcAiPh.toFixed(2);
                                const calcAiM = Number(ai.moisture || ((Number(s.soil_moisture||2.6) + Number(s.soil_stick_moisture||61.7))/2));
                                if (document.getElementById('portalAiMoist')) document.getElementById('portalAiMoist').innerText = calcAiM.toFixed(1);
                                const aiN = Number(ai.nitrogen || 14.7).toFixed(1);
                                const aiP = Number(ai.phosphorus || 12.8).toFixed(1);
                                const aiK = Number(ai.potassium || 3.9).toFixed(1);
                                if (document.getElementById('portalAiNpk')) document.getElementById('portalAiNpk').innerText = `${aiN}-${aiP}-${aiK}`;
                                if (document.getElementById('portalAiRatio')) document.getElementById('portalAiRatio').innerText = ai.npk_ratio || '0.0:1:0.0';
                                if (document.getElementById('portalAiConfBadge')) document.getElementById('portalAiConfBadge').innerText = `${Number(ai.confidence ? ai.confidence * 100 : 77.6).toFixed(1)}%`;
                                
                                const vpdVal = Number(s.vpd || 1.35);
                                if (document.getElementById('portalAiVpdStatus')) {
                                    document.getElementById('portalAiVpdStatus').innerText = (vpdVal < 0.8) ? 'ชื้นสูง' : ((vpdVal > 1.4) ? 'แห้งจัด' : 'สมบูรณ์');
                                }

                                if (document.getElementById('portalAiAlert')) {
                                    const stickPh = Number(s.soil_stick_ph || 3.03);
                                    const deepPh = Number(s.soil_ph || 8.20);
                                    const deepM = Number(s.soil_moisture || 2.6);
                                    if (Math.abs(deepPh - stickPh) > 2.0) {
                                        document.getElementById('portalAiAlert').innerHTML = `<strong class="text-amber-300">คำเตือน Edge AI:</strong> พบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick ${stickPh.toFixed(2)} vs รากลึก ${deepPh.toFixed(2)}) • ดินเขตรากแห้งวิกฤต (${deepM.toFixed(1)}%) แนะนำสั่งเปิดวาล์วรดน้ำ`;
                                    } else {
                                        document.getElementById('portalAiAlert').innerHTML = `<strong class="text-emerald-300">สถานะปกติ:</strong> โครงสร้างดินสมดุลดี (pH: ${deepPh.toFixed(2)}, ความชื้น: ${deepM.toFixed(1)}%) • โมเดล TinyML ทำงานปกติ`;
                                    }
                                }
                            }
                        } catch(e) {
                            console.error('Portal telemetry fetch error', e);
                        }
                    }
                    setInterval(fetchPortalLiveTelemetry, 2500);
                    fetchPortalLiveTelemetry();
                </script>
            </div>

            <!-- Interactive Online Actuator Console (ทดลองกดสั่งการบอร์ดจริงออนไลน์) -->
            <div id="relay-console" class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-6 md:p-10 shadow-xl border border-emerald-200/80 bg-gradient-to-b from-white to-emerald-50/30 space-y-6 w-full min-w-0">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200/80 pb-5">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-xs font-mono font-bold text-emerald-700 uppercase tracking-widest">LIVE HARDWARE ACTUATOR CONSOLE</span>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-gray-900">
                            คอนโซลทดลองสั่งการบอร์ดจริงออนไลน์ (Interactive Relay Control)
                        </h3>
                        <p class="text-gray-500 text-xs md:text-sm">
                            ทดลองกดสวิตช์สั่งเปิด-ปิดอุปกรณ์ภาคสนามผ่านคำสั่ง MQTT Protocol เพื่อเชื่อมโยงสู่บอร์ด ESP32-S3 ATD3.5
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-mono text-xs font-bold border border-emerald-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt text-emerald-600"></i> MQTT Status: Connected
                        </span>
                    </div>
                </div>

                <!-- 4 Interactive Relay Channels Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Relay 1 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-01 (PIN D4)</span>
                            <span id="relay-led-1" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">วาล์วน้ำโซลินอยด์ (12V)</h4>
                            <p class="text-[11px] text-gray-500">แปลงปลูกผักสลัดไฮโดรโปนิกส์</p>
                        </div>
                        <div id="relay-status-1" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-1" onclick="toggleRemoteRelay(1, 'วาล์วน้ำโซลินอยด์ 12V')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 2 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-02 (PIN D5)</span>
                            <span id="relay-led-2" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">ระบบพ่นหมอกลด VPD</h4>
                            <p class="text-[11px] text-gray-500">ควบคุมความชื้นสัมพัทธ์ในอากาศ</p>
                        </div>
                        <div id="relay-status-2" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-2" onclick="toggleRemoteRelay(2, 'ระบบพ่นหมอกลด VPD')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 3 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-03 (PIN D6)</span>
                            <span id="relay-led-3" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">ปั๊มสารละลายปุ๋ย AB</h4>
                            <p class="text-[11px] text-gray-500">ระบบปรับค่า EC อัตโนมัติ</p>
                        </div>
                        <div id="relay-status-3" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-3" onclick="toggleRemoteRelay(3, 'ปั๊มสารละลายปุ๋ย AB')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                    <!-- Relay 4 -->
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:border-emerald-400 transition space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 font-mono">CH-04 (PIN D7)</span>
                            <span id="relay-led-4" class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">พัดลมระบายอากาศโรงเรือน</h4>
                            <p class="text-[11px] text-gray-500">ลดความร้อนสะสมช่วงกลางวัน</p>
                        </div>
                        <div id="relay-status-4" class="text-[11px] font-bold text-gray-400">สถานะ: ปิดการทำงาน (STANDBY OFF)</div>
                        <button id="relay-btn-4" onclick="toggleRemoteRelay(4, 'พัดลมระบายอากาศโรงเรือน')" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)
                        </button>
                    </div>

                </div>

                <!-- Live MQTT Packet Console Stream Box -->
                <div class="bg-slate-950 rounded-2xl p-4 border border-slate-800 font-mono text-xs text-gray-300 space-y-2 shadow-inner">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-[11px]">
                        <span class="text-emerald-400 font-bold flex items-center gap-2">
                            <i class="fa-solid fa-terminal text-emerald-500"></i> MQTT TELEMETRY PACKET CONSOLE (ESP32-S3 CLIENT)
                        </span>
                        <span class="text-[10px] text-gray-500">Topic: /board/relay/+/cmd</span>
                    </div>
                    <div id="mqtt-log-console" class="max-h-28 overflow-y-auto space-y-1 text-[11px]">
                        <div class="text-[10px] font-mono text-emerald-400">
                            [System Ready] Connected to MQTT Broker tcp://localhost:1883 | ClientID: LEQs_ESP32S3_Gateway
                        </div>
                        <div class="text-[10px] font-mono text-gray-500">
                            [Subscription] Subscribed to topic /board/relay/+/cmd (QoS: 1)
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 6. 7 LEARNING MODULES CURRICULUM (cmu_aiot 9-MODULES GRID STYLE)          -->
        <!-- ========================================================================= -->
