<?php
// Initial Hero Slide (Uniform 1024x1024 square image, zero distortion, zero black screen)
$initial_hero_file = 'assets/images/soil_nutrient2026/portable_iot_pixar.png';
?>
        <header id="overview" class="py-4 sm:py-8 md:py-12 relative w-full max-w-full overflow-hidden">
            <div class="grid lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 xl:gap-12 items-start w-full min-w-0 max-w-full">
                
                <!-- Left Column (5 Cols): Smart Screen Frame + Event Schedule & Venue Card -->
                <div class="lg:col-span-5 flex flex-col gap-4 sm:gap-5 w-full min-w-0 max-w-full order-last lg:order-first">
                    
                    <!-- Hero Image Showcase - Single Unified Smart Screen Frame matching cmu_aiot -->
                    <div class="relative animate-float w-full min-w-0 max-w-full">
                        <div class="absolute -inset-2 sm:-inset-4 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 rounded-[2rem] sm:rounded-[3rem] opacity-25 blur-2xl pointer-events-none"></div>
                        
                        <div id="heroSingleScreenContainer" class="relative rounded-2xl sm:rounded-[2.5rem] shadow-2xl border-2 sm:border-4 border-white/90 overflow-hidden bg-white p-2.5 sm:p-4 space-y-2 sm:space-y-3 group hover:shadow-emerald-200/50 transition-all duration-700 select-none w-full max-w-[560px] mx-auto">
                            
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
                            <div class="slide-screen-viewport rounded-xl sm:rounded-2xl shadow-inner cursor-pointer group/screen w-full" onclick="openCurrentHeroSlideModal()" title="คลิกหรือแตะเพื่อดูภาพขนาดใหญ่เต็มจอ">
                                
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

                    <!-- Event Date & Venue Standout Hero Card (Perfect Weight Balance on Left) -->
                    <div class="p-3.5 sm:p-4.5 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-emerald-50 via-teal-50/80 to-amber-50/80 border-2 border-emerald-300/80 shadow-md hover:shadow-lg transition space-y-2.5 w-full max-w-[560px] mx-auto">
                        
                        <div class="flex flex-col gap-2 sm:gap-2.5">
                            
                            <!-- Highlighted Event Date Row/Card -->
                            <div class="flex items-center gap-3 p-2.5 sm:p-3 rounded-2xl bg-white/95 border border-emerald-200/80 shadow-2xs">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex flex-col items-center justify-center shrink-0 shadow-md ring-3 ring-emerald-100">
                                    <i class="fa-solid fa-calendar-check text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[9px] sm:text-[10px] font-mono font-bold tracking-wider uppercase text-emerald-800 bg-emerald-100/90 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <i class="fa-solid fa-clock text-[9px]"></i> วันจัดกิจกรรม (3 วัน 18 ชม.)
                                        </span>
                                    </div>
                                    <div class="text-base sm:text-xl font-black text-emerald-950 font-heading tracking-tight mt-0.5">
                                        17 - 19 ธันวาคม 2569
                                    </div>
                                </div>
                            </div>

                            <!-- Venue Card with Direct Map Action -->
                            <a href="#venue-map" 
                               class="flex items-center justify-between gap-2 p-2.5 sm:p-3 rounded-2xl bg-white/95 border border-emerald-200/80 shadow-2xs group/venue hover:bg-emerald-50/80 transition duration-200"
                               title="คลิกเพื่อเลื่อนไปยังแผนที่พิกัดสถานที่จัดอบรมที่ Footer">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-br from-teal-600 to-cyan-700 text-white flex items-center justify-center shrink-0 shadow-md ring-3 ring-teal-100 group-hover/venue:scale-105 transition">
                                        <i class="fa-solid fa-location-dot text-base sm:text-lg text-amber-300 animate-bounce"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] sm:text-[10px] font-mono font-bold tracking-wider uppercase text-teal-800 bg-teal-100/90 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                                📍 สถานที่จัดกิจกรรม
                                            </span>
                                        </div>
                                        <div class="text-xs sm:text-sm font-bold text-gray-900 group-hover/venue:text-emerald-700 transition leading-snug mt-0.5 truncate">
                                            ห้องประชุมปะการัง อาคาร 9 มรภ.รำไพพรรณี
                                        </div>
                                    </div>
                                </div>
                                <span class="shrink-0 text-[10px] sm:text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1 rounded-xl font-bold flex items-center gap-1 shadow-xs transition group-hover/venue:scale-105">
                                    แผนที่ <i class="fa-solid fa-arrow-down text-[9px]"></i>
                                </span>
                            </a>

                            <!-- Coordinator & Inquiries Card matching Poster -->
                            <div class="flex items-center justify-between gap-2 p-2.5 sm:p-3 rounded-2xl bg-white/95 border border-emerald-200/80 shadow-2xs">
                                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shrink-0 shadow-md ring-3 ring-amber-100">
                                        <i class="fa-solid fa-headset text-base sm:text-lg"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[9px] sm:text-[10px] font-mono font-bold tracking-wider uppercase text-amber-800 bg-amber-100/90 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-info text-[9px]"></i> สอบถามเพิ่มเติม
                                        </div>
                                        <div class="text-xs sm:text-sm font-bold text-gray-900 mt-0.5 truncate">
                                            ผศ.ดร.ชีวะ ทัศนา
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="tel:0937422654" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition transform hover:scale-105">
                                        <i class="fa-solid fa-phone text-[10px]"></i> โทรเลย
                                    </a>
                                    <a href="poster.php" class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs transition">
                                        <i class="fa-solid fa-bullhorn text-amber-600 text-[10px]"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Right Column (7 Cols): Narrative, 4D Pillars & Primary CTAs -->
                <div class="lg:col-span-7 text-left space-y-4 sm:space-y-5 z-10 w-full min-w-0 max-w-full">
                    
                    <!-- Hero Ticker Container -->
                    <div class="min-h-[44px] sm:min-h-[50px] flex items-center overflow-hidden w-full max-w-full min-w-0">
                        <span id="hero-ticker" class="inline-block py-2 sm:py-2.5 px-4 sm:px-6 rounded-xl sm:rounded-2xl bg-emerald-100/90 backdrop-blur-sm shadow-md text-xs sm:text-sm md:text-base font-bold tracking-wide border-l-4 border-emerald-500 transition-all duration-300 max-w-full truncate">
                            🏛️ พัฒนาโดย LEQs (หน่วยบริการวิเคราะห์สิ่งแวดล้อมเพื่อท้องถิ่น) คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี
                        </span>
                    </div>

                    <!-- Heading Badges -->
                    <div class="space-y-2">
                        <!-- Official Project Badge matching Poster -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs sm:text-sm font-bold shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>โครงการพัฒนาสมรรถนะเยาวชน ครู และเกษตรกรสู่ศตวรรษที่ 21</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold shadow-xs">
                                <i class="fa-solid fa-gift text-[11px]"></i> ฟรีตลอดการอบรม
                            </span>
                        </div>
                        
                        <div class="text-xs sm:text-sm md:text-base font-bold text-gray-700 flex items-center gap-2 flex-wrap">
                            <span class="text-emerald-700 font-heading">เปิดรับสมัครผู้เข้าร่วมโครงการอบรมเชิงปฏิบัติการ</span>
                            <span class="text-gray-300 hidden sm:inline">•</span>
                            <span class="text-[11px] font-mono text-gray-500">Active Learning 3 วัน 18 ชม.</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="font-heading font-bold leading-tight text-shadow w-full min-w-0 mt-1">
                        <span class="text-xs sm:text-sm md:text-base text-gray-400 block mb-1 font-['Orbitron'] tracking-widest uppercase opacity-90">
                            &lt; Smart_Agricultural_Intelligence /&gt;
                        </span>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                            <span class="text-3xl sm:text-5xl md:text-6xl font-extrabold tracking-tight inline-flex items-baseline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 font-black font-['Outfit']">Edge</span>
                                <span class="ml-1 sm:ml-1.5 text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 font-black font-['Outfit']">xAIoT</span>
                            </span>
                            <span class="text-xs sm:text-base md:text-xl font-tech font-bold bg-white/95 px-2.5 sm:px-4 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl border-2 border-emerald-300 shadow-xs inline-flex items-center gap-1.5 sm:gap-2 flex-wrap max-w-full">
                                <span class="text-emerald-700 font-extrabold">4D</span>
                                <span class="text-amber-500 font-medium">-</span>
                                <span class="text-cyan-700">AgriEnvi</span>
                            </span>
                        </div>
                        <span class="text-lg sm:text-2xl md:text-3xl block mt-2 font-heading font-bold text-gray-900 leading-snug break-words">
                            <span class="text-emerald-600">ปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอที</span> 
                            <span class="text-amber-500 font-normal">เพื่อการเกษตรและสิ่งแวดล้อม</span>
                        </span>
                    </h1>

                    <!-- LEQs & xAI Academic Innovation Origin Card -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-amber-500/10 border-2 border-emerald-400/40 space-y-1.5 shadow-xs">
                        <div class="flex items-start sm:items-center gap-2 flex-wrap sm:flex-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-emerald-700 text-white font-bold text-xs font-mono shadow-xs shrink-0">
                                <i class="fa-solid fa-flask-vial text-[11px] text-emerald-200"></i> LEQs
                            </span>
                            <span class="text-xs sm:text-sm font-bold text-emerald-950 leading-snug">
                                หน่วยบริการวิเคราะห์สิ่งแวดล้อมเพื่อท้องถิ่น <span class="font-normal text-emerald-700 text-[11px] sm:text-xs block sm:inline">(Local Environmental Quality Services Center)</span>
                            </span>
                        </div>
                        <div class="flex items-start sm:items-center gap-2 flex-wrap sm:flex-nowrap pt-1 border-t border-emerald-200/60">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold text-xs font-mono shadow-xs shrink-0">
                                <i class="fa-solid fa-brain text-[11px] text-amber-100"></i> xAI
                            </span>
                            <span class="text-xs sm:text-sm font-semibold text-amber-950 leading-snug">
                                Agentic AI สำหรับงานด้านการเกษตรและสิ่งแวดล้อม พัฒนาโดยทีม LEQs มรภ.รำไพพรรณี
                            </span>
                        </div>
                    </div>

                    <!-- Core Innovation Paragraph -->
                    <div class="space-y-3 w-full min-w-0">
                        <strong class="dynamic-color-text font-bold block text-emerald-800 text-sm sm:text-base md:text-lg leading-snug break-words">
                            TinyML Edge Computing • 4D-AgriEnvi Architecture • Edge Computer Vision • Active Learning
                        </strong>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700 leading-relaxed font-sans break-words">
                            ยกระดับครู เยาวชน และเกษตรกรสู่สมรรถนะยุคใหม่ด้วย <strong>Active Learning</strong> ผ่านกรอบแนวคิด <strong>4D-AgriEnvi</strong> และ <strong>6 โครงงาน Capstone</strong> ผสานปัญญาประดิษฐ์ฝังตัว <strong>TinyML</strong> บนบอร์ด ESP32-S3 ตรวจวัดสิ่งแวดล้อมเกษตรแม่นยำ <strong>เรียลไทม์</strong> ตอบโจทย์ <strong>SDGs</strong> อย่างยั่งยืน
                        </p>
                    </div>

                    <!-- 4D Pillars of Innovation (Clean Balanced Grid) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5 w-full">
                        <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-950 shadow-2xs flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-mono font-bold text-emerald-600">D1 • SENSING</div>
                                <div class="text-xs font-bold font-heading leading-tight mt-0.5">Digital Telemetry</div>
                            </div>
                            <div class="text-[9px] text-gray-600 leading-tight mt-1">IoT • ดิน • อากาศ</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-cyan-50 border border-cyan-300 text-cyan-950 shadow-2xs flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-mono font-bold text-cyan-600">D2 • VISION</div>
                                <div class="text-xs font-bold font-heading leading-tight mt-0.5">Deep Learning</div>
                            </div>
                            <div class="text-[9px] text-gray-600 leading-tight mt-1">Computer Vision</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-blue-50 border border-blue-300 text-blue-950 shadow-2xs flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-mono font-bold text-blue-600">D3 • EDGE AI</div>
                                <div class="text-xs font-bold font-heading leading-tight mt-0.5">Device TinyML</div>
                            </div>
                            <div class="text-[9px] text-gray-600 leading-tight mt-1">ESP32-S3 ออนดีไวซ์</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-purple-50 border border-purple-300 text-purple-950 shadow-2xs flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-mono font-bold text-purple-600">D4 • IMPACT</div>
                                <div class="text-xs font-bold font-heading leading-tight mt-0.5">Data-Driven</div>
                            </div>
                            <div class="text-[9px] text-gray-600 leading-tight mt-1">สิ่งแวดล้อม • SDGs</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2.5 sm:gap-3 pt-1 w-full">
                        <a href="pages/register.php" 
                           class="flex-1 sm:flex-initial bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 sm:px-7 py-2.5 sm:py-3 rounded-full font-bold shadow-lg hover:shadow-emerald-500/40 hover:scale-105 transition transform flex items-center justify-center gap-2 text-xs sm:text-sm md:text-base">
                            <i class="fa-solid fa-user-plus"></i> ลงทะเบียนร่วมอบรม (ฟรี)
                        </a>
                        <a href="poster.php" 
                           class="flex-1 sm:flex-initial bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-full font-bold shadow-md hover:scale-105 transition transform flex items-center justify-center gap-2 text-xs sm:text-sm md:text-base">
                            <i class="fa-solid fa-bullhorn text-amber-100"></i> โปสเตอร์ประชาสัมพันธ์
                        </a>
                        <a href="#modules" 
                           class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-800 border-2 border-emerald-500/40 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full font-bold shadow-xs hover:scale-105 transition transform flex items-center justify-center gap-1.5 text-xs sm:text-sm">
                            <i class="fa-solid fa-cubes text-emerald-600"></i> สำรวจ 7 โมดูล
                        </a>
                        <a href="#learning-resources" 
                           class="w-full sm:w-auto bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full font-bold shadow-xs transition flex items-center justify-center gap-1.5 text-xs sm:text-sm">
                            <i class="fa-solid fa-graduation-cap text-indigo-600"></i> แหล่งเรียนรู้ 10 ระบบ
                        </a>
                    </div>

                    <!-- Target Audience Pills -->
                    <div class="pt-0.5 flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs text-gray-600 w-full">
                        <span class="font-bold text-gray-800 text-xs sm:text-sm">กลุ่มเป้าหมาย:</span>
                        <span class="px-2.5 sm:px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] sm:text-xs font-semibold">ครูนวัตกร (ว PA)</span>
                        <span class="px-2.5 sm:px-3 py-1 rounded-full bg-cyan-50 text-cyan-800 border border-cyan-200 text-[11px] sm:text-xs font-semibold">เยาวชน (TCAS Portfolio)</span>
                        <span class="px-2.5 sm:px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] sm:text-xs font-semibold">เกษตรกรชาวสวน</span>
                        <span class="px-2.5 sm:px-3 py-1 rounded-full bg-purple-50 text-purple-800 border border-purple-200 text-[11px] sm:text-xs font-semibold">ผู้สนใจ AIoT ทั่วไป</span>
                    </div>

                </div>

            </div>

            <!-- Key Metric Statistics Bar (cmu_aiot style) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-10 sm:mt-16 pt-6 sm:pt-8 w-full">
                <a href="#capstone" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black font-tech text-emerald-600 group-hover:text-emerald-500 transition">6 Tracks</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">6 แทร็กโครงงานนวัตกรรม</div>
                </a>
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition">
                    <div class="text-xl sm:text-3xl md:text-4xl font-black font-tech text-cyan-600">7 Modules</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">ทฤษฎี 30% + ปฏิบัติการ 70%</div>
                </div>
                <a href="#simulators" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black font-tech text-amber-600 group-hover:text-amber-500 transition">7 Virtual Labs</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold">3D Realtime Sim &amp; AR</div>
                </a>
                <a href="#iot-platform" class="glass-card p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl text-center shadow-sm hover:shadow-md transition block group">
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black font-tech text-purple-600 group-hover:text-purple-500 transition">6+ iSmart Apps</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 mt-1 font-semibold leading-snug">5+ Smart Apps &amp; Dashboard</div>
                </a>
            </div>
        </header>

