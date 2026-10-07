        <section id="screens" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold uppercase tracking-wider">
                    Hardware &amp; Firmware Architecture
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    ฮาร์ดแวร์จริงและ 11 หน้าจอแสดงผล ATD3.5-S3
                </h2>
                <p class="text-gray-500 text-sm">
                    คอนโทรลเลอร์หน้าจอสัมผัสแบบ Capacitive Touch 3.5 นิ้ว ความละเอียด 480x320 พิกเซล ควบคุมแปลงจริง
                </p>
            </div>

            <!-- Hardware Spec & Pinout Feature -->
            <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-12 shadow-xl border border-gray-100 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center w-full min-w-0">
                <div class="lg:col-span-5 rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-200 shadow-lg bg-white p-2 w-full">
                    <img src="assets/images/cv_hardware_setup.jpg" alt="Hardware Setup Kit" class="w-full h-auto object-cover rounded-xl sm:rounded-2xl hover:scale-105 transition duration-500">
                    <div class="p-2 sm:p-3 text-center">
                        <span class="text-xs font-bold text-gray-700">ชุดบอร์ดทดลอง ATD3.5-S3 พร้อมกล้อง OV2640 &amp; RS485 Modbus</span>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-4 w-full min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-cyan-500 rounded-xl sm:rounded-2xl flex items-center justify-center text-white shadow-md shadow-cyan-200">
                        <i class="fa-solid fa-microchip text-lg sm:text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-cyan-700 uppercase tracking-widest">Hardware Specifications</span>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-heading font-bold text-gray-900 break-words">
                        ESP32-S3 Dual-Core Xtensa LX7 @ 240MHz
                    </h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed break-words">
                        ผสานเซนเซอร์สภาพแวดล้อมชั้นนำ: อุณหภูมิ/ความชื้น SHT45 (I2C), แสงสังเคราะห์แสง BH1750 (PAR), โพรบวัดดิน 7-in-1 NPK/EC/pH (RS485 Modbus RTU), และรีเลย์ควบคุมวาล์วให้น้ำ 12V/24V โซลินอยด์
                    </p>

                    <!-- Code Snippet Box with Copy Button (matching cmu_aiot) -->
                    <div class="bg-white border rounded-2xl p-3.5 sm:p-5 shadow-sm w-full min-w-0 overflow-hidden">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="font-bold text-gray-800 text-xs flex items-center gap-2">
                                <i class="fa-solid fa-code text-cyan-600"></i> โค้ดคำนวณ VPD บนเฟิร์มแวร์ ESP32 Arduino C++
                            </span>
                            <button onclick="copyToClipboard('code-vpd')" class="text-cyan-600 hover:text-cyan-800 font-bold text-xs bg-cyan-50 px-3 py-1 rounded-full transition">
                                <i class="fa-regular fa-copy"></i> Copy
                            </button>
                        </div>
                        <pre class="bg-gray-900 text-emerald-400 p-3.5 rounded-xl text-xs font-mono overflow-x-auto" id="code-vpd">float calculateVPD(float T, float RH) {
    float SVP = 0.61078 * exp((17.27 * T) / (T + 237.3));
    float AVP = SVP * (RH / 100.0);
    return SVP - AVP; // returns VPD in kPa
}</pre>
                    </div>
                </div>
            </div>

            <!-- 11 ATD3.5-S3 Screens Grid (Clickable Lightbox) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                
                <!-- 01 Overview -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/01_overview_dashboard.png', 'Screen 01: Overview Dashboard', 'แดชบอร์ดหลัก 4 มิติ สภาพอากาศ VPD, แสงอาทิตย์ PAR, ผิวดิน, และเขตราก 7-in-1')">
                    <img src="assets/images/atd35/01_overview_dashboard.png" alt="01 Overview" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">01. Overview</span>
                        <span class="text-[10px] text-gray-500">Main 4D Display</span>
                    </div>
                </div>

                <!-- 02 Data (Big Numbers) -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/02_big_numbers.png', 'Screen 02: Big Numbers Live Data', 'หน้าจอแสดงผลตัวเลขขนาดใหญ่ (Full Screen Big Digits) อุณหภูมิ SHT45, ความชื้นอากาศ RH%, ความชื้นผิวดิน และ AI pH')">
                    <img src="assets/images/atd35/02_big_numbers.png" alt="02 Data Big Numbers" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">02. Data (ตัวเลขใหญ่)</span>
                        <span class="text-[10px] text-gray-500">Big Numbers Display</span>
                    </div>
                </div>

                <!-- 03 Graph (Realtime Trends) -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/03_realtime_graphs.png', 'Screen 03: Realtime Dynamic Trends', 'กราฟแนวโน้มอนุกรมเวลาเรียลไทม์ 60 วินาที แสดงพลวัตสภาพอากาศย่อยและความชื้นดินแบบไดนามิก')">
                    <img src="assets/images/atd35/03_realtime_graphs.png" alt="03 Trends Graph" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">03. Graph (กราฟแนวโน้ม)</span>
                        <span class="text-[10px] text-gray-500">Realtime Trends</span>
                    </div>
                </div>

                <!-- 04 Relay Control -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/04_relay_control.png', 'Screen 04: Direct Relay Control', 'แผงสวิตช์สัมผัสควบคุมรีเลย์ปั๊มน้ำ โซลินอยด์วาล์ว ระบบพ่นหมอก และพัดลมระบายอากาศ พร้อมโหมดอัตโนมัติ AI')">
                    <img src="assets/images/atd35/04_relay_control.png" alt="04 Relay Control" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">04. Relay (ควบคุมรีเลย์)</span>
                        <span class="text-[10px] text-gray-500">Direct Actuators</span>
                    </div>
                </div>

                <!-- 05 Wi-Fi QR -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/05_wifi_captive_portal.png', 'Screen 05: Wi-Fi Captive Portal', 'ระบบจับคู่การเชื่อมต่ออินเทอร์เน็ตผ่าน QR Code แบบ Zero-Configuration')">
                    <img src="assets/images/atd35/05_wifi_captive_portal.png" alt="05 Wi-Fi" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">05. Wi-Fi Portal</span>
                        <span class="text-[10px] text-gray-500">QR Code Pairing</span>
                    </div>
                </div>

                <!-- 06 SHT45 VPD -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/06_sht45_air_vpd.png', 'Screen 06: SHT45 VPD Detailed Gauge', 'เกจวัดค่า VPD เชิงลึกและการประเมินสภาวะเปิด/ปิดปากใบของพืช')">
                    <img src="assets/images/atd35/06_sht45_air_vpd.png" alt="06 SHT45" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">06. SHT45 VPD</span>
                        <span class="text-[10px] text-gray-500">Physics Engine</span>
                    </div>
                </div>

                <!-- 07 BH1750 PAR -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/07_bh1750_solar_par.png', 'Screen 07: Solar Spectrum & PAR', 'การวัดความเข้มแสงแดดและการคำนวณพลังงานแสงอาทิตย์สำหรับฟาร์ม')">
                    <img src="assets/images/atd35/07_bh1750_solar_par.png" alt="07 BH1750" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">07. Solar PAR</span>
                        <span class="text-[10px] text-gray-500">Solar Spectrum</span>
                    </div>
                </div>

                <!-- 08 Soil Stick -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/08_soil_stick_surface.png', 'Screen 08: Soil Stick Surface Moisture', 'การตรวจสอบความชื้นผิวดิน 0-10 cm เฝ้าระวังการระเหยน้ำและการแตกระแหง')">
                    <img src="assets/images/atd35/08_soil_stick_surface.png" alt="08 Soil Stick" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">08. Soil Stick</span>
                        <span class="text-[10px] text-gray-500">Surface Moisture</span>
                    </div>
                </div>

                <!-- 09 Soil 7-in-1 TinyML -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/09_soil_7in1_tinyml.png', 'Screen 09: Soil 7in1 TinyML Inference', 'ผลการทำนายความต้องการปุ๋ยและน้ำจากโมเดล TinyML ที่รันบน ESP32 โดยตรง')">
                    <img src="assets/images/atd35/09_soil_7in1_tinyml.png" alt="09 TinyML" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">09. TinyML Inference</span>
                        <span class="text-[10px] text-gray-500">On-Device xAI</span>
                    </div>
                </div>

                <!-- 10 Master Showcase -->
                <div class="glass-card p-2 rounded-2xl border border-gray-100 hover:border-cyan-400 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/master_pr_showcase_poster.jpg', 'Screen 10: Master PR Showcase Poster', 'โปสเตอร์ประชาสัมพันธ์ความละเอียดสูง Ultra-HD แสดงครบทุกหน้าจอ')">
                    <img src="assets/images/atd35/master_pr_showcase_poster.jpg" alt="10 Poster" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-gray-800 block">10. Ultra-HD Poster</span>
                        <span class="text-[10px] text-gray-500">PR Exhibition</span>
                    </div>
                </div>

                <!-- 11 QR Portal & Verification (New Screen) -->
                <div class="glass-card p-2 rounded-2xl border-2 border-cyan-400 bg-cyan-50/40 hover:border-cyan-500 transition cursor-pointer shadow-sm hover:shadow-md"
                     onclick="openScreenModal('assets/images/atd35/11_qr_portal_screen.png', 'Screen 11: Official QR Portal &amp; Identity Verification', 'หน้าจอใหม่สำหรับแสดง QR Code ทางการ: 2026.10.04 วันอาทิตย์ • 09:03 ชีวะ ทัศนา • https://scicenter.rbru.ac.th/leqs-workshop/index.php')">
                    <img src="assets/images/atd35/11_qr_portal_screen.png" alt="11 QR Portal" class="w-full h-auto rounded-xl object-cover hover:scale-105 transition">
                    <div class="mt-2 text-center">
                        <span class="text-xs font-bold text-cyan-900 block flex items-center justify-center gap-1">
                            <i class="fa-solid fa-qrcode text-cyan-600"></i> 11. QR Portal
                        </span>
                        <span class="text-[10px] text-cyan-700 font-semibold block truncate">2026.10.04 ชีวะ ทัศนา</span>
                    </div>
                </div>

            </div>

            <!-- New Screen 11 & Official QR Portal Spotlight Feature -->
            <div class="glass-card rounded-[2.5rem] p-6 md:p-8 border-2 border-cyan-400/80 bg-gradient-to-br from-cyan-900/90 via-slate-900 to-indigo-950 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-64 h-64 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center relative z-10">
                    
                    <!-- Left: ESP32 Screen 11 Preview -->
                    <div class="md:col-span-6 rounded-2xl overflow-hidden border border-cyan-500/40 shadow-xl bg-black/60 p-2 cursor-pointer group"
                         onclick="openScreenModal('assets/images/atd35/11_qr_portal_screen.png', 'Screen 11: Official QR Portal (ESP32-S3 ATD3.5)', 'หน้าจอใหม่แสดงผลบนบอร์ด ESP32-S3 ATD3.5 Smart Touch: 2026.10.04 วันอาทิตย์ | 09:03 ชีวะ ทัศนา | https://scicenter.rbru.ac.th/leqs-workshop/index.php')">
                        <div class="flex items-center justify-between px-3 py-1.5 bg-slate-900/80 rounded-t-xl text-[11px] font-mono text-cyan-300 border-b border-cyan-500/30">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-microchip"></i> ESP32-S3 ATD3.5 Screen 11</span>
                            <span class="text-emerald-400 font-bold">ONLINE</span>
                        </div>
                        <img src="assets/images/atd35/11_qr_portal_screen.png" alt="Screen 11 ATD3.5" class="w-full h-auto object-cover rounded-b-xl group-hover:scale-[1.02] transition duration-300">
                    </div>

                    <!-- Right: QR Code & Metadata Card -->
                    <div class="md:col-span-6 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/20 border border-cyan-400/40 text-cyan-300 text-xs font-mono">
                            <i class="fa-solid fa-qrcode"></i> หน้าจอใหม่ในบอร์ด ESP32 • QR Code Portal
                        </div>
                        <h3 class="text-2xl md:text-3xl font-heading font-bold text-white leading-tight">
                            ระบบลงทะเบียนและเชื่อมต่อแปลงเกษตรอัจฉริยะ LEQs-xAI
                        </h3>
                        
                        <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/15">
                            <div class="w-24 h-24 bg-white p-1.5 rounded-xl shadow-md flex-shrink-0 flex items-center justify-center">
                                <img src="assets/images/qr_leqs-agri-workshop.png" alt="QR LEQs Agri Workshop" class="w-full h-full object-contain">
                            </div>
                            <div class="space-y-1 text-xs">
                                <div class="font-mono text-cyan-300 font-bold flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar-check"></i> 2026.10.04 วันอาทิตย์
                                </div>
                                <div class="font-mono text-amber-300 font-bold flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock"></i> 09:03 ชีวะ ทัศนา
                                </div>
                                <div class="text-[11px] text-gray-200 break-all font-mono">
                                    <i class="fa-solid fa-link text-emerald-400 mr-1"></i>
                                    <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" class="text-emerald-300 hover:underline">
                                        https://scicenter.rbru.ac.th/leqs-workshop/index.php
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2.5">
                            <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-white font-bold text-xs shadow-lg shadow-cyan-500/25 transition flex items-center gap-2">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> เปิดลิงก์ระบบ scicenter.rbru.ac.th
                            </a>
                            <a href="assets/images/qr_leqs-agri-workshop.png" download class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด QR Code
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 5.1 FROM BOARD TO SMARTPHONE APP & INTERACTIVE WEB DASHBOARD (#iot-platform)-->
        <!-- ========================================================================= -->
