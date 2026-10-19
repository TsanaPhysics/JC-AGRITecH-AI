        <section id="simulators" class="scroll-mt-24 space-y-8" x-data="{ activeSimTab: 'ar' }">
            
            <!-- SECTION HEADER -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyan-100 text-cyan-800 text-xs font-bold uppercase tracking-wider border border-cyan-200">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span>
                    Interactive 3D AR &amp; Virtual Laboratories
                </div>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    ห้องปฏิบัติการจำลองเสมือนจริง 7 Flagship Labs (Virtual Labs)
                </h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    เริ่มต้นใช้งานในโหมด <strong>3D Interactive Realtime Simulation Experimental Lab (7 ห้องปฏิบัติการเสมือน)</strong> หมุนสำรวจมุมมอง 360° และปรับสไลเดอร์ฟิสิกส์ 60 FPS ได้ทันที พร้อมปุ่มกดสลับเปิดโหมด <strong>3D AR MediaPipe Gesture AI</strong> ตรวจจับท่าทางมือแบบไร้สัมผัสผ่านกล้อง
                </p>

                <!-- DUAL MODE TAB SELECTOR -->
                <div class="inline-flex p-1.5 rounded-2xl bg-gray-100 border border-gray-200 shadow-inner mt-2">
                    <button @click="activeSimTab = 'ar'" 
                            :class="activeSimTab === 'ar' ? 'bg-gradient-to-r from-cyan-600 to-teal-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 sm:px-6 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-cube"></i> ห้องทดลอง 3D Realtime Simulation &amp; AR (7 Labs)
                    </button>
                    <button @click="activeSimTab = 'web'" 
                            :class="activeSimTab === 'web' ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 sm:px-6 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-sliders"></i> เครื่องคำนวณ Interactive บนเว็บ
                    </button>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 1: 3D AR MEDIAPIPE SPATIAL SIMULATORS (5 FLAGSHIP LABS GRID)          -->
            <!-- ========================================================================= -->
            <div x-show="activeSimTab === 'ar'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    
                    <!-- 3D AR LAB 1: RS485 Modbus 7-in-1 Soil Multi-Depth Probe -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-emerald-100 text-emerald-800 font-mono">
                                    3D &amp; AR Lab 1
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Pinch (🤏) ควบคุมความลึก
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-200">
                                    <i class="fa-solid fa-seedling"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        โพรบวัดดิน 7-in-1 RS485 Modbus RTU 3D
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Stratified Soil Horizons A, B, C &amp; Live Hex Telemetry
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                จำลองการปักหัววัดสแตนเลสสตีล 3 เข็ม (316L) ลงในชั้นดินลึก 0-30 ซม. ตรวจวัดความชื้น, EC, pH, N, P, K และอุณหภูมิ พร้อมสร้างเฟรมคำสั่ง Modbus RTU Hex Packet แบบเรียลไทม์
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">316L Electrodes</span>
                                <span class="px-2.5 py-1 rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200">Modbus Hex Packets</span>
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Audio Horizon Beep</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-soil-7in1', 'Lab 1: 3D AR RS485 Modbus 7-in-1 Soil Probe')" 
                                    class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-soil-7in1/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-emerald-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 2: ESP32-S3 ATD3.5 Controller & 4 Relays -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-sky-100 text-sky-800 font-mono">
                                    3D &amp; AR Lab 2
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                    Point (☝️) สลับรีเลย์
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center text-xl shadow-md shadow-sky-200">
                                    <i class="fa-solid fa-microchip"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        บอร์ด ESP32-S3 ATD3.5 &amp; รีเลย์ 4 ช่อง
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Touchscreen Dashboard &amp; Mechanical Relays
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                สั่งการบอร์ดไมโครคอนโทรลเลอร์ ESP32-S3 WROOM พร้อมจอสี 3.5 นิ้ว ควบคุมปั๊มน้ำ, วาล์วปุ๋ย, พัดลม และหัวพ่นหมอก พร้อมเสียงคลิกสวิตช์รีเลย์สมจริงผ่าน Web Audio API
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200">ESP32-S3 240MHz</span>
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">4x 10A Songle</span>
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Audio Relay Snap</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-esp32-controller', 'Lab 2: 3D AR ESP32-S3 Controller & 4 Relays')" 
                                    class="px-4 py-2 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-800 text-xs font-bold border border-sky-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-esp32-controller/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-500 hover:to-cyan-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-sky-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 3: SHT45 Microclimate & Stomatal Transpiration -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-teal-100 text-teal-800 font-mono">
                                    3D &amp; AR Lab 3
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                                    Pinch (🤏) ปรับอุณหภูมิ/ชื้น
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center text-xl shadow-md shadow-teal-200">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        จุลอากาศ SHT45 &amp; การคายน้ำของปากใบ
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Guard Cells Morphing &amp; Vapor Pressure Deficit
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                วิเคราะห์สมดุล VPD ด้วยหัววัด SHT45 3D ในกระเปาะป้องกันรังสี และการเปิด-ปิดของเซลล์คุมปากใบพืชทุเรียน พร้อมละอองน้ำคายน้ำเสมือนจริงและไฟเตือนสภาวะวิกฤต
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-teal-50 text-teal-700 border border-teal-200">Tetens SVP Eq.</span>
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">Guard Cells 3D</span>
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">VPD 0.8-1.25 kPa</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-vpd-microclimate', 'Lab 3: 3D AR SHT45 Microclimate & Stomatal Chamber')" 
                                    class="px-4 py-2 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold border border-teal-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-vpd-microclimate/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-teal-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 4: Coastal Aquaculture DO & VFD Inverter -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-indigo-100 text-indigo-800 font-mono">
                                    3D &amp; AR Lab 4
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                    Pinch (🤏) ปรับอุณหภูมิ/ความเค็ม
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-sky-600 text-white flex items-center justify-center text-xl shadow-md shadow-indigo-200">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        ออกซิเจน DO &amp; อินเวอร์เตอร์ VFD ประหยัดไฟ
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Benson-Krause Saturation &amp; Affinity Power Laws
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                จำลองบ่อกุ้งและกังหันตีน้ำหมุน 60 FPS ปรับรอบการทำงานด้วย VFD Inverter ตามสมการ Benson-Krause ลดค่าไฟได้ถึง 56.1% พร้อมเสียงฮัมมอเตอร์ปรับความถี่ตามจริง
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">Affinity P ∝ f³</span>
                                <span class="px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200">60 FPS Paddlewheel</span>
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Acoustic Motor Hum</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-aquaculture-vfd', 'Lab 4: 3D AR Aquaculture DO & VFD Inverter Engine')" 
                                    class="px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-800 text-xs font-bold border border-indigo-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-aquaculture-vfd/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-indigo-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 5: Computer Vision & Leaf Disease AI (MobileNetV3 + Grad-CAM) -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group md:col-span-2 lg:col-span-1">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-purple-100 text-purple-800 font-mono">
                                    3D &amp; AR Lab 5
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                                    Pinch (🤏) สลับพยาธิสภาพใบพืช
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 via-indigo-600 to-pink-500 text-white flex items-center justify-center text-xl shadow-md shadow-purple-200">
                                    <i class="fa-solid fa-brain"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        คอมพิวเตอร์วิทัศน์ AI ตรวจโรคใบพืช &amp; Grad-CAM 3D
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        MobileNetV3 PyTorch &amp; ONNX WASM Heatmap Chamber
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                หมุนสำรวจแท่นวางใบพืช 3 มิติ 360° พร้อมลำแสงเลเซอร์สแกนและ Heatmap ความร้อน Grad-CAM ระบุพิกัดรอยโรค (ราใบติด, ใบจุดสนิม, โฟมอปซิส) ด้วยโมเดล PyTorch/ONNX Edge AI ออฟไลน์ 100%
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 border border-purple-200">MobileNetV3 PyTorch</span>
                                <span class="px-2.5 py-1 rounded-lg bg-pink-50 text-pink-700 border border-pink-200">Grad-CAM Heatmap</span>
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">100% Offline Edge WASM</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-vision-ai', 'Lab 5: 3D AR Computer Vision & Leaf Disease AI')" 
                                    class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 text-xs font-bold border border-purple-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-vision-ai/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-purple-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 6: Soil Quality Analysis with Computer Vision & Deep Learning (CV+DL) -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-amber-100 text-amber-800 font-mono">
                                    3D &amp; AR Lab 6
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pinch (🤏) สลับชุดตัวอย่างดิน
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-600 via-yellow-600 to-amber-700 text-white flex items-center justify-center text-xl shadow-md shadow-amber-200">
                                    <i class="fa-solid fa-camera-rotate"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        วิเคราะห์คุณภาพดินด้วย CV+DL 3D
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Munsell Soil Color (CIE-Lab ΔE*ab) &amp; Deep Learning SOM% Engine
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                จำลองแท่นตรวจวิเคราะห์หน้าดิน 3 มิติ 360° ร่วมกับการเทียบการ์ด White Balance ColorChecker แปลงค่าภาพถ่ายกล้องสู่พิกัดสีดินมาตรฐาน Munsell และประมวลผลด้วย Deep CNN เพื่อพยากรณ์อินทรียวัตถุ (SOM %) และเนื้อดินแบบเรียลไทม์
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Munsell System</span>
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">CNN SOM% Regress</span>
                                <span class="px-2.5 py-1 rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200">CIE-Lab ΔE*ab</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-soil-vision-ai', 'Lab 6: 3D AR Soil Quality Analysis with CV+DL')" 
                                    class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-soil-vision-ai/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 text-slate-950 font-bold text-xs transition flex items-center gap-2 shadow-md shadow-amber-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                    <!-- 3D AR LAB 7: Solar PAR Radiation & Photosynthesis Station -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase bg-amber-100 text-amber-800 font-mono">
                                    3D &amp; AR Lab 7
                                </span>
                                <span class="text-xs text-gray-500 font-mono flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pinch (🤏) ปรับเวลาสุริยะ
                                </span>
                            </div>

                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-600 text-white flex items-center justify-center text-xl shadow-md shadow-amber-200">
                                    <i class="fa-solid fa-solar-panel"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                        โฟตอนสังเคราะห์แสง PAR &amp; การตรึงคาร์บอน 3D
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Quantum PPFD &amp; Beer-Lambert Canopy Extinction Engine
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed">
                                จำลองวิถีโคจรดวงอาทิตย์บนท้องฟ้า 3 มิติ และหัววัด Quantum PAR Sensor / Pyranometer ตรวจวัดความเข้มแสงสังเคราะห์ PPFD คำนวณอัตราการสังเคราะห์แสงสุทธิ An และการผลิตไฟฟ้าจากแผงโซลาร์เซลล์
                            </p>

                            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">PPFD µmol/m²/s</span>
                                <span class="px-2.5 py-1 rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200">An Carbon Model</span>
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">Beer Canopy LAI</span>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 relative z-10">
                            <button onclick="openArLabModal('lab-solar-par', 'Lab 7: 3D AR Solar PAR & Photosynthesis Station')" 
                                    class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 transition flex items-center gap-2 shadow-xs">
                                <i class="fa-solid fa-window-maximize"></i> ทดลองในหน้าต่าง (Modal)
                            </button>
                            <a href="simulators/lab-solar-par/index.html" target="_blank"
                               class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 text-slate-950 font-bold text-xs transition flex items-center gap-2 shadow-md shadow-amber-200">
                                <i class="fa-solid fa-play"></i> เปิดเต็มจอ (Fullscreen)
                            </a>
                        </div>
                    </div>

                </div>

                <!-- DIRECTORY HUB BANNER -->
                <div class="p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white flex flex-col md:flex-row items-center justify-between gap-4 border border-cyan-500/30 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-2xl border border-cyan-500/40">
                            <i class="fa-solid fa-cubes-stacked"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-base font-['Chakra_Petch'] text-cyan-300">
                                ศูนย์รวมห้องปฏิบัติการเสมือนจริง (3D AR Virtual Labs Directory)
                            </h4>
                            <p class="text-xs text-slate-400">
                                เข้าชมสารบัญห้องทดลอง 3D AR ทั้งหมด พร้อมเอกสารทฤษฎี สถาปัตยกรรมระบบ และคู่มือการพัฒนา
                            </p>
                        </div>
                    </div>
                    <a href="simulators/index.html" target="_blank"
                       class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs flex items-center gap-2 shadow-lg shadow-cyan-500/30 transition transform hover:scale-105 whitespace-nowrap">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> เปิด Virtual Labs Directory Hub
                    </a>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- TAB 2: WEB NUMERICAL & AI SIMULATORS (EXISTING LABS 1, 2, 3)               -->
            <!-- ========================================================================= -->
            <div x-show="activeSimTab === 'web'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-8">

                <!-- SIMULATORS GRID -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- SIMULATOR 1: VPD Plant Transpiration Simulator -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group w-full min-w-0">
                        <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-200">
                                        <i class="fa-solid fa-leaf"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-base">เครื่องคำนวณ VPD และการคายน้ำของพืช</h3>
                                        <p class="text-xs text-gray-500">SHT45 Microclimate &amp; Vapor Pressure Deficit Engine</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">Lab 1</span>
                            </div>

                            <!-- Readout Values -->
                            <div class="grid grid-cols-3 gap-3 my-6">
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">VPD (kPa)</span>
                                    <span id="out-vpd-val" class="text-2xl font-black text-emerald-600 font-tech">1.10</span>
                                </div>
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">SVP (kPa)</span>
                                    <span id="out-svp-val" class="text-2xl font-black text-cyan-600 font-tech">4.24</span>
                                </div>
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">AVP (kPa)</span>
                                    <span id="out-avp-val" class="text-2xl font-black text-blue-600 font-tech">3.14</span>
                                </div>
                            </div>

                            <!-- Sliders -->
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>อุณหภูมิอากาศ (Air Temp)</span>
                                        <span id="val-vpd-temp" class="text-emerald-600 font-mono">30.0 °C</span>
                                    </div>
                                    <input type="range" id="vpd-temp" min="15" max="45" step="0.5" value="30" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>ความชื้นสัมพัทธ์ (Relative Humidity)</span>
                                        <span id="val-vpd-hum" class="text-cyan-600 font-mono">74 %</span>
                                    </div>
                                    <input type="range" id="vpd-humidity" min="20" max="98" step="1" value="74" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                                </div>
                            </div>

                            <!-- Advisory Card -->
                            <div id="box-vpd-advisory" class="mt-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-base mt-0.5"></i>
                                <div>
                                    <div id="title-vpd-advisory" class="font-bold">สภาวะเหมาะสมสมบูรณ์แบบ (VPD 0.8 - 1.25 kPa)</div>
                                    <div id="desc-vpd-advisory" class="mt-0.5 text-gray-600 leading-relaxed">การคายน้ำและการดูดซึมธาตุอาหาร NPK ดำเนินไปอย่างสมบูรณ์แบบ ทุเรียนและพืชแปลงขยายขนาดได้อย่างมีประสิทธิภาพ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SIMULATOR 2: Coastal Aquaculture DO & VFD Power Saver Simulator -->
                    <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group w-full min-w-0">
                        <div class="absolute top-0 right-0 w-72 h-72 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-xl shadow-md shadow-sky-200">
                                        <i class="fa-solid fa-water"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-base">การจัดการออกซิเจนละลาย DO &amp; อินเวอร์เตอร์ VFD</h3>
                                        <p class="text-xs text-gray-500">Benson-Krause Saturation &amp; Affinity Power Laws</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-sky-100 text-sky-700">Lab 2</span>
                            </div>

                            <!-- Readout Values -->
                            <div class="grid grid-cols-3 gap-3 my-6">
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">DO อิ่มตัว (Sat)</span>
                                    <span id="outDoSat" class="text-2xl font-black text-sky-600 font-tech">6.82</span>
                                </div>
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">VFD Freq</span>
                                    <span id="outVfdFreq" class="text-2xl font-black text-indigo-600 font-tech">38.0 Hz</span>
                                </div>
                                <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                    <span class="text-[10px] text-gray-400 block uppercase font-mono">ประหยัดไฟ</span>
                                    <span id="outPowerSave" class="text-2xl font-black text-emerald-600 font-tech">56.1 %</span>
                                </div>
                            </div>

                            <!-- Sliders -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                            <span>อุณหภูมิน้ำ</span>
                                            <span id="valAquaTemp" class="text-sky-600 font-mono">29.0 °C</span>
                                        </div>
                                        <input type="range" id="inputAquaTemp" min="20" max="38" step="0.5" value="29" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-sky-500">
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                            <span>ความเค็มน้ำ (Salinity)</span>
                                            <span id="valAquaSal" class="text-blue-600 font-mono">25 ppt</span>
                                        </div>
                                        <input type="range" id="inputAquaSal" min="0" max="40" step="1" value="25" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>ออกซิเจนในบ่อจริง (Dissolved Oxygen)</span>
                                        <span id="valAquaDo" class="text-emerald-600 font-mono">5.20 mg/L</span>
                                    </div>
                                    <input type="range" id="inputAquaDo" min="1.0" max="8.0" step="0.1" value="5.2" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                                </div>
                            </div>

                            <!-- Advisory Card -->
                            <div id="boxAquaAdvisory" class="mt-6 p-4 rounded-2xl bg-sky-500/10 border border-sky-500/30 text-sky-800 text-xs flex items-start gap-3">
                                <i class="fa-solid fa-circle-check text-base mt-0.5"></i>
                                <div>
                                    <div id="titleAquaAdvisory" class="font-bold">ออกซิเจนสมบูรณ์แบบ (DO > 4.5 mg/L) - ประหยัดพลังงานสูงสุด</div>
                                    <div id="descAquaAdvisory" class="mt-0.5 text-gray-600 leading-relaxed">ระบบ VFD ชะลอความถี่ลงเหลือ 38.0 Hz ช่วยลดกำลังไฟฟ้าได้ถึง 56.1% ลดต้นทุนค่าไฟได้หลักหมื่นบาทต่อรอบ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SIMULATOR 3: Edge AI Plant Vision Simulator (Full Width Card) -->
                <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden group">
                    <div class="absolute bottom-0 right-0 w-80 h-80 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 translate-y-1/3"></div>

                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-gray-100 gap-4 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-purple-500 text-white flex items-center justify-center text-xl shadow-md shadow-purple-200">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">Lab 3: คอมพิวเตอร์วิทัศน์จำแนกโรคพืชและสุขภาพใบ (Edge Vision AI)</h3>
                                <p class="text-xs text-gray-500">TinyML MobileNet / CNN On-Device Inference Simulation บนชิป ESP32-S3</p>
                            </div>
                        </div>
                        <!-- Sample Selector Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <button id="pbtn-healthy" onclick="selectPlantSample('healthy')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                                <i class="fa-solid fa-seedling text-emerald-500 mr-1"></i> ใบสมบูรณ์
                            </button>
                            <button id="pbtn-fungal_spot" onclick="selectPlantSample('fungal_spot')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                                <i class="fa-solid fa-circle-exclamation text-rose-500 mr-1"></i> โรคใบจุดราสนิม
                            </button>
                            <button id="pbtn-nutrient_def" onclick="selectPlantSample('nutrient_def')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> ขาดธาตุอาหาร
                            </button>
                            <button id="pbtn-pest_damage" onclick="selectPlantSample('pest_damage')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                                <i class="fa-solid fa-bug text-purple-500 mr-1"></i> เพลี้ยไฟ/ไรแดง
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-6 items-center relative z-10">
                        <!-- Image Preview with Bounding Box -->
                        <div class="md:col-span-5 relative bg-white rounded-2xl p-3 border border-gray-200 shadow-md overflow-hidden group">
                            <img src="assets/images/cv_agri_vision.jpg" alt="Agricultural Vision in Orchard" class="w-full h-64 object-cover rounded-xl">
                            <div id="diag-bbox" class="absolute top-10 left-10 w-44 h-44 border-2 border-dashed rounded-xl flex items-center justify-center transition-all duration-500">
                                <div class="absolute -top-3 left-2 px-2 py-0.5 rounded bg-slate-900 text-[10px] font-mono text-emerald-400 border border-slate-700">
                                    ROI: AI-Detect
                                </div>
                            </div>
                        </div>

                        <!-- AI Diagnostic Readouts -->
                        <div class="md:col-span-7 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 id="diag-title" class="text-xl font-bold text-gray-900 font-heading">ใบพืชสุขภาพสมบูรณ์ (Healthy Leaf)</h4>
                                <span id="diag-speed" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-mono font-bold border border-emerald-200">42 ms (ESP32-S3 TinyML)</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                    <div class="text-[10px] text-gray-400 uppercase font-semibold">ผลวินิจฉัย (Predicted Class)</div>
                                    <div id="diag-class" class="font-bold text-emerald-600 text-lg">Healthy - No Infection</div>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                    <div class="text-[10px] text-gray-400 uppercase font-semibold">ความเชื่อมั่น (Confidence)</div>
                                    <div id="diag-conf" class="text-xl font-mono font-black text-gray-900">99.4%</div>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs space-y-2.5">
                                <div><strong class="text-gray-800">อาการทางสรีรวิทยา:</strong> <span id="diag-desc" class="text-gray-600">พืชสังเคราะห์แสงได้เต็มที่ คลอโรฟิลล์สม่ำเสมอ ผิวใบมันวาว ไร้ร่องรอยสปอร์เชื้อรา</span></div>
                                <hr class="border-gray-100">
                                <div><strong class="text-emerald-700">คำแนะนำการแก้ไข xAI:</strong> <span id="diag-action" class="text-gray-700 font-medium">คงการให้น้ำและธาตุอาหารตามตารางมาตรฐาน ไม่จำเป็นต้องใช้สารควบคุมศัตรูพืช</span></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>
