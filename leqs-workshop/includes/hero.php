<?php
// Initial Hero Slide (Uniform 1024x1024 square image, zero distortion, zero black screen)
$initial_hero_file = 'assets/images/soil_nutrient2026/portable_iot_pixar.png';
?>
        <header id="overview" class="py-6 sm:py-10 md:py-16 relative w-full max-w-full overflow-hidden">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center w-full min-w-0 max-w-full">
                
                <!-- Hero Image Showcase (Left) - Single Unified Smart Screen Frame matching cmu_aiot -->
                <div class="relative animate-float order-last md:order-first w-full min-w-0 max-w-full">
                    <div class="absolute -inset-2 sm:-inset-4 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 rounded-[2rem] sm:rounded-[3.5rem] opacity-25 blur-2xl sm:blur-3xl pointer-events-none"></div>
                    
                    <div id="heroSingleScreenContainer" class="relative rounded-2xl sm:rounded-[2.5rem] shadow-2xl border-2 sm:border-4 border-white/90 overflow-hidden bg-white p-2 sm:p-4 space-y-2 sm:space-y-3.5 group hover:shadow-emerald-200/50 transition-all duration-700 select-none w-full max-w-full">
                        
                        <!-- Top Monitor Header Bar -->
                        <div class="flex items-center justify-between px-1 sm:px-2 pt-0.5 sm:pt-1 pb-1.5 sm:pb-2 border-b border-gray-100 text-xs">
                            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                <span id="heroCategoryDot" class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
                                <span id="heroCategoryName" class="font-bold text-gray-800 font-tech tracking-wider text-[10px] sm:text-[11px] truncate max-w-[120px] sm:max-w-none">Portable Soil IoT</span>
                                <span id="heroSlideBadge" class="px-1.5 sm:px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-mono text-[8px] sm:text-[9px] border border-emerald-200 font-semibold shrink-0">
                                    Slide 01/12
                                </span>
                            </div>
                            <div class="flex items-center gap-1 sm:gap-1.5 shrink-0">
                                <button onclick="prevHeroSlide()" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-gray-100 hover:bg-emerald-100 hover:text-emerald-700 text-gray-600 flex items-center justify-center transition text-xs" title="ภาพก่อนหน้า (Swipe Right)">
                                    <i class="fa-solid fa-chevron-left text-[9px] sm:text-[10px]"></i>
                                </button>
                                <button onclick="nextHeroSlide()" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-gray-100 hover:bg-emerald-100 hover:text-emerald-700 text-gray-600 flex items-center justify-center transition text-xs" title="ภาพถัดไป (Swipe Left)">
                                    <i class="fa-solid fa-chevron-right text-[9px] sm:text-[10px]"></i>
                                </button>
                                <button onclick="openCurrentHeroSlideModal()" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-600 flex items-center justify-center transition text-xs ml-0.5 sm:ml-1" title="ขยายดูภาพขนาดใหญ่เต็มจอ">
                                    <i class="fa-solid fa-expand text-[9px] sm:text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Main Screen Display (Smart Equalized Viewport with Ambient Backdrop) -->
                        <div class="slide-screen-viewport rounded-xl sm:rounded-2xl shadow-inner cursor-pointer group/screen" onclick="openCurrentHeroSlideModal()" title="คลิกหรือแตะเพื่อดูภาพขนาดใหญ่เต็มจอ">
                            
                            <!-- Ambient Blurred Background (Eliminates empty black bars on non-1:1 ratios) -->
                            <img id="heroSingleScreenBg" 
                                 src="<?php echo $initial_hero_file; ?>" 
                                 alt="" 
                                 class="slide-ambient-bg">

                            <!-- Responsive Auto-Fit Foreground Image (Clean 1:1 fit with zero distortion) -->
                            <img id="heroSingleScreenImg" 
                                 src="<?php echo $initial_hero_file; ?>" 
                                 alt="Portable IoT Device: เครื่องวัดวิเคราะห์ธาตุอาหารดินแบบพกพา" 
                                 class="slide-foreground-img group-hover/screen:scale-[1.02]">
                            
                            <!-- Status Badges Overlay (Top-Left) -->
                            <div class="absolute top-2 left-2 sm:top-3 sm:left-3 z-20 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-slate-950/85 backdrop-blur-md border border-emerald-400/40 text-emerald-300 text-[8px] sm:text-[10px] font-tech font-bold uppercase tracking-wider flex items-center gap-1 sm:gap-1.5 shadow-md pointer-events-none">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>ONLINE • TELEMETRY</span>
                            </div>

                            <!-- Hardware Tag Overlay (Top-Right) -->
                            <div class="absolute top-2 right-2 sm:top-3 sm:right-3 z-20 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-black/75 backdrop-blur-md text-[8px] sm:text-[10px] text-cyan-300 font-mono border border-cyan-500/30 flex items-center gap-1 pointer-events-none">
                                <i class="fa-solid fa-signal text-[8px] sm:text-[9px] text-cyan-400"></i>
                                <span>ESP32-S3 ATD3.5</span>
                            </div>

                            <!-- Bottom Caption Overlay (Sleek gradient dock with no image occlusion) -->
                            <div class="absolute bottom-0 left-0 right-0 z-20 pt-6 pb-2 sm:pb-3 px-3 sm:px-4 bg-gradient-to-t from-slate-950/95 via-slate-950/80 to-transparent pointer-events-none">
                                <div class="font-bold text-[11px] sm:text-xs md:text-sm text-emerald-300 truncate" id="heroSingleScreenTitle">
                                    Portable IoT Device: เครื่องวัดวิเคราะห์ธาตุอาหารดินแบบพกพา
                                </div>
                                <div class="text-[9px] sm:text-[11px] text-gray-300 truncate mt-0.5 hidden sm:block" id="heroSingleScreenDesc">
                                    ย่อส่วนเทคโนโลยีห้องแล็บให้อยู่ในรูปแบบอุปกรณ์พกพา หรือใช้ร่วมกับ Smartphone ทราบค่า NPK ทันทีที่หน้าแปลงปลูก
                                </div>
                            </div>
                        </div>

                        <!-- Touch Gestures Hint for Mobile (Appears only on mobile) -->
                        <div class="sm:hidden flex items-center justify-between px-1 text-[9px] text-emerald-700 bg-emerald-50/80 py-1 rounded-lg">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-hand-pointer text-emerald-600 animate-pulse text-[9px]"></i>
                                <span>แตะภาพเพื่อขยายเต็มจอ</span>
                            </span>
                            <span class="flex items-center gap-1 font-mono text-[8px] text-gray-500">
                                <span>ปัด ซ้าย-ขวา ได้ 👆</span>
                            </span>
                        </div>

                        <!-- Screen Bottom Navigation Dots & Quick Switcher -->
                        <div class="flex items-center justify-between pt-0.5 sm:pt-1 px-1">
                            <div class="text-[9px] sm:text-[10px] text-gray-400 flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-arrows-rotate text-emerald-500 text-[8px] sm:text-[9px]"></i>
                                <span>Auto 3.8s</span>
                            </div>
                            <div id="heroScreenDots" class="flex items-center gap-1 sm:gap-1.5 overflow-x-auto max-w-[130px] sm:max-w-none py-0.5">
                                <!-- Dots dynamically rendered by script.js -->
                            </div>
                            <a href="#iot-platform" class="text-[10px] sm:text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition shrink-0">
                                <span>ระบบ Web App</span>
                                <i class="fa-solid fa-arrow-down text-[9px]"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Text Content (Right) -->
                <div class="text-left space-y-5 sm:space-y-6 z-10 w-full min-w-0 max-w-full">
                    
                    <!-- Hero Ticker Container -->
                    <div class="min-h-[46px] sm:min-h-[56px] flex items-center overflow-hidden w-full max-w-full min-w-0">
                        <span id="hero-ticker" class="inline-block py-2 sm:py-2.5 px-4 sm:px-6 rounded-xl sm:rounded-2xl bg-emerald-100/90 backdrop-blur-sm shadow-md text-xs sm:text-base md:text-lg font-bold tracking-wide border-l-4 border-emerald-500 transition-all duration-300 max-w-full truncate">
                            🏛️ LEQs-Teams คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                        </span>
                    </div>

                    <!-- Heading -->
                    <h1 class="font-heading font-bold leading-tight text-shadow w-full min-w-0">
                        <span class="text-xs sm:text-sm md:text-base text-gray-400 block mb-1.5 sm:mb-2 font-['Orbitron'] tracking-widest uppercase opacity-90">
                            &lt; Smart_Agricultural_Intelligence /&gt;
                        </span>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                            <span class="text-3xl sm:text-5xl md:text-6xl font-extrabold tracking-tight inline-flex items-baseline">
                                <span class="text-emerald-500">L</span><span class="text-cyan-500">E</span><span class="text-purple-600">Q</span><span class="text-pink-500">s</span>
                                <span class="ml-1 sm:ml-1.5 text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 font-black">xAI</span>
                            </span>
                            <span class="text-xs sm:text-lg md:text-2xl font-tech font-bold bg-white/95 px-2.5 sm:px-4 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl border border-gray-200 shadow-xs inline-flex items-center gap-1.5 sm:gap-2 flex-wrap max-w-full">
                                <span class="text-blue-600">Digital</span>
                                <span class="text-emerald-600">Agriculture</span>
                                <span class="text-amber-500 font-medium">-</span>
                                <span class="text-teal-600">Environment</span>
                            </span>
                        </div>
                        <span class="text-xl sm:text-3xl md:text-4xl block mt-2 sm:mt-2.5 font-heading font-bold text-gray-900 leading-snug break-words">
                            <span class="text-emerald-600">ปัญญาประดิษฐ์ฝังตัว</span> 
                            <span class="text-amber-500 font-normal">เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</span>
                        </span>
                    </h1>

                    <div class="space-y-4 max-w-xl w-full min-w-0">
                        <strong class="dynamic-color-text font-bold block text-emerald-800 text-sm sm:text-lg md:text-xl leading-snug break-words">
                            TinyML Edge Computing • Multi-Sensors ๔ มิติ • Edge Computer Vision • Active Learning
                        </strong>
                        <p class="text-sm sm:text-base md:text-lg text-gray-700 leading-relaxed font-sans break-words">
                            ยกระดับครูและเยาวชนสู่สมรรถนะยุคใหม่ด้วย <strong>Active Learning</strong> ผ่าน <strong>๗ โมดูลเข้มข้น</strong> และ <strong>๖ โครงงาน Capstone</strong> ผสานปัญญาประดิษฐ์ฝังตัว <strong>TinyML</strong> บนบอร์ด ESP32-S3 ตรวจวัดสิ่งแวดล้อมเกษตรแม่นยำ <strong>๔ มิติเรียลไทม์</strong> เชื่อมต่อแอปและแดชบอร์ด ตอบโจทย์ <strong>SDGs</strong> อย่างยั่งยืน
                        </p>

                        <!-- 4 Environmental Dimensions Pills -->
                        <div class="flex flex-wrap gap-1.5 sm:gap-2 pt-1 w-full">
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-emerald-100/80 text-emerald-900 border border-emerald-300 text-[11px] sm:text-xs md:text-sm font-bold shadow-2xs">
                                <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 rounded-full bg-emerald-500 animate-pulse"></span> ดิน (NPK/pH/EC)
                            </span>
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-cyan-100/80 text-cyan-900 border border-cyan-300 text-[11px] sm:text-xs md:text-sm font-bold shadow-2xs">
                                <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 rounded-full bg-cyan-500 animate-pulse"></span> อากาศ (VPD/RH/Temp)
                            </span>
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-blue-100/80 text-blue-900 border border-blue-300 text-[11px] sm:text-xs md:text-sm font-bold shadow-2xs">
                                <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 rounded-full bg-blue-500 animate-pulse"></span> น้ำ (DO/Quality)
                            </span>
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-purple-100/80 text-purple-900 border border-purple-300 text-[11px] sm:text-xs md:text-sm font-bold shadow-2xs">
                                <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 rounded-full bg-purple-500 animate-pulse"></span> โรคพืช (Edge Vision)
                            </span>
                        </div>

                        <!-- Event Date & Venue Standout Hero Card -->
                        <div class="p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl bg-gradient-to-r from-emerald-50 via-teal-50/80 to-amber-50/80 border-2 border-emerald-300/80 shadow-md hover:shadow-lg transition space-y-3 sm:space-y-3.5 w-full">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5 divide-y sm:divide-y-0 sm:divide-x divide-emerald-200/80">
                                
                                <!-- Highlighted Event Date -->
                                <div class="flex items-center gap-3 sm:gap-3.5 pr-2">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex flex-col items-center justify-center shrink-0 shadow-md ring-4 ring-emerald-100">
                                        <i class="fa-solid fa-calendar-check text-lg sm:text-xl"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] sm:text-xs font-mono font-bold tracking-wider uppercase text-emerald-800 bg-emerald-200/60 px-2 sm:px-2.5 py-0.5 rounded-full truncate">
                                                📅 วันจัดกิจกรรม (3 วัน 18 ชม.)
                                            </span>
                                        </div>
                                        <div class="text-lg sm:text-2xl font-black text-emerald-950 font-heading tracking-tight mt-0.5 truncate">
                                            28 - 30 พฤศจิกายน 2569
                                        </div>
                                    </div>
                                </div>

                                <!-- Venue with Smooth Link to Footer Map -->
                                <a href="#venue-map" 
                                   class="flex items-center justify-between gap-2 sm:gap-3 pt-3 sm:pt-0 sm:pl-3.5 group/venue hover:bg-emerald-100/60 p-1.5 sm:p-2 rounded-xl sm:rounded-2xl transition duration-200 min-w-0"
                                   title="คลิกเพื่อเลื่อนไปยังแผนที่พิกัดสถานที่จัดอบรมที่ Footer">
                                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-teal-600 to-cyan-700 text-white flex items-center justify-center shrink-0 shadow-md ring-4 ring-teal-100 group-hover/venue:scale-105 transition">
                                            <i class="fa-solid fa-location-dot text-lg sm:text-xl text-amber-300 animate-bounce"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] sm:text-xs font-mono font-bold tracking-wider uppercase text-teal-800 bg-teal-200/60 px-2 sm:px-2.5 py-0.5 rounded-full truncate">
                                                    📍 สถานที่จัดกิจกรรม
                                                </span>
                                            </div>
                                            <div class="text-xs sm:text-base font-bold text-gray-900 group-hover/venue:text-emerald-700 transition leading-snug mt-0.5 break-words">
                                                คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี จันทบุรี
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center justify-center text-emerald-700 font-bold text-xs shrink-0 group-hover/venue:translate-x-1 transition ml-1">
                                        <span class="text-[10px] sm:text-xs bg-emerald-600 text-white px-2 sm:px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-xs whitespace-nowrap">
                                            แผนที่ <i class="fa-solid fa-arrow-down text-[9px] sm:text-[10px]"></i>
                                        </span>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2.5 sm:gap-3.5 pt-2 w-full">
                        <a href="#modules" 
                           class="flex-1 sm:flex-initial bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 sm:px-8 py-3 sm:py-3.5 rounded-full font-bold shadow-lg hover:shadow-emerald-500/40 hover:scale-105 transition transform flex items-center justify-center gap-2 text-sm sm:text-base md:text-lg">
                            <i class="fa-solid fa-cubes"></i> สำรวจ ๗ โมดูล
                        </a>
                        <a href="pages/register.php" 
                           class="flex-1 sm:flex-initial bg-white hover:bg-gray-50 text-gray-900 border-2 border-emerald-500/40 px-5 sm:px-7 py-3 sm:py-3.5 rounded-full font-bold shadow-md hover:scale-105 transition transform flex items-center justify-center gap-2 text-sm sm:text-base md:text-lg">
                            <i class="fa-solid fa-user-plus text-emerald-600"></i> ลงทะเบียนร่วมอบรม
                        </a>
                        <a href="#learning-resources" 
                           class="w-full sm:w-auto bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-5 sm:px-6 py-2.5 sm:py-3.5 rounded-full font-bold shadow-sm transition flex items-center justify-center gap-2 text-xs sm:text-base">
                            <i class="fa-solid fa-graduation-cap text-indigo-600"></i> แหล่งเรียนรู้ ๑๐ ระบบ
                        </a>
                    </div>

                    <!-- Target Audience Pills -->
                    <div class="pt-1 flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-600 w-full">
                        <span class="font-bold text-gray-800 text-xs sm:text-sm md:text-base">กลุ่มเป้าหมาย:</span>
                        <span class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] sm:text-xs md:text-sm font-semibold">ครูนวัตกร (ว PA)</span>
                        <span class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-cyan-50 text-cyan-800 border border-cyan-200 text-[11px] sm:text-xs md:text-sm font-semibold">เยาวชน (TCAS Portfolio)</span>
                        <span class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] sm:text-xs md:text-sm font-semibold">เกษตรกรชาวสวนผลไม้</span>
                        <span class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-purple-50 text-purple-800 border border-purple-200 text-[11px] sm:text-xs md:text-sm font-semibold">ผู้สนใจ AIoT ทั่วไป</span>
                    </div>

                </div>

            </div>

            <!-- Key Metric Statistics Bar (cmu_aiot style) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-10 sm:mt-16 pt-6 sm:pt-8 w-full">
                <a href="#capstone" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black font-tech text-emerald-600 group-hover:text-emerald-500 transition">6 Tracks</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">๖ แทร็กโครงงานนวัตกรรม</div>
                </a>
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition">
                    <div class="text-xl sm:text-3xl md:text-4xl font-black font-tech text-cyan-600">7 Modules</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">ทฤษฎี 30% + ปฏิบัติการ 70%</div>
                </div>
                <a href="#simulators" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl font-black font-tech text-amber-600 group-hover:text-amber-500 transition">2+ Virtual XR Lab</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">ห้องทดลองเสมือนจริง ๓ มิติ</div>
                </a>
                <a href="#iot-platform" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black font-tech text-purple-600 group-hover:text-purple-500 transition">5+ Smart App</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold leading-snug">คุณภาพดิน น้ำ อากาศ &amp; AI วิทัศน์</div>
                </a>
            </div>
        </header>

