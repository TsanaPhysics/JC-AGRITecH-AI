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
