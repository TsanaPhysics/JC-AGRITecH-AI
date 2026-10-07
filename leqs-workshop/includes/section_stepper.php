<?php
/**
 * LEQs-xAI: Academic Learning Journey Stepper
 * แผนที่เส้นทางการเรียนรู้และการวัดสมรรถนะ 6 ขั้นตอน (Academic OBE & Active Learning Cycle)
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
?>
<section id="learning-journey" class="relative scroll-mt-28">
    <div class="glass-card-academic rounded-3xl p-6 sm:p-8 border border-emerald-100 shadow-xl relative overflow-hidden bg-gradient-to-br from-white via-emerald-50/20 to-teal-50/30">
        
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-300/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-cyan-300/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-gray-100 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200">
                        <i class="fa-solid fa-route text-emerald-600 mr-1"></i> LEARNING JOURNEY
                    </span>
                    <span class="text-xs sm:text-sm text-gray-500 font-medium">OBE &amp; Active Learning Workflow</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-heading font-bold text-slate-900 tracking-tight">
                    เส้นทางการเรียนรู้และวัดสมรรถนะ ๖ ขั้นตอน
                </h3>
                <p class="text-sm sm:text-base text-gray-600 mt-1 max-w-2xl leading-relaxed">
                    กระบวนการเรียนรู้เชิงปฏิบัติการตามมาตรฐาน Outcome-Based Education (OBE) ตั้งแต่การสำรวจพื้นฐาน สู่การลงมือปฏิบัติจริง และรับรองสมรรถนะ
                </p>
            </div>
            
            <div class="flex items-center gap-2 self-start md:self-auto shrink-0">
                <a href="pages/student_list.php" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition flex items-center gap-1.5 border border-slate-200">
                    <i class="fa-solid fa-users text-slate-500"></i> ตรวจสอบรายชื่อผู้สมัคร
                </a>
                <a href="admin/index.php" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-chart-line text-cyan-400"></i> สรุป KPI
                </a>
            </div>
        </div>

        <!-- Stepper 6 Steps Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 relative">
            
            <!-- Step 1: Register -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-emerald-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๑
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                            STEP 01
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-emerald-700 transition">
                        ลงทะเบียนเข้าร่วม
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        กรอกข้อมูลผู้เรียน โรงเรียน และเลือกแทร็กโครงงาน Capstone
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="pages/register.php" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-xs sm:text-sm font-bold transition">
                        <span>ลงทะเบียน</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Step 2: Pre-test -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-orange-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-orange-500 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๒
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 font-bold border border-orange-200">
                            STEP 02
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-orange-600 transition">
                        ทดสอบก่อนเรียน
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        วัดความรู้พื้นฐานเซนเซอร์ IoT และปัญญาประดิษฐ์ฝังตัว ๔ มิติ
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="pages/assessment_pre.php" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-orange-50 hover:bg-orange-500 hover:text-white text-orange-700 text-xs sm:text-sm font-bold transition">
                        <span>ทำแบบทดสอบ</span>
                        <i class="fa-solid fa-pen text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Step 3: Modules & IoT Lab -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-cyan-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-cyan-600 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๓
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 font-bold border border-cyan-200">
                            STEP 03
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-cyan-700 transition">
                        ๗ โมดูล &amp; IoT Lab
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        ทดลองจริงกับบอร์ด ESP32-S3 ATD3.5 และเว็บคอนโทรลเลอร์
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="#modules" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-cyan-50 hover:bg-cyan-600 hover:text-white text-cyan-700 text-xs sm:text-sm font-bold transition">
                        <span>เข้าสู่บทเรียน</span>
                        <i class="fa-solid fa-cubes text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Step 4: Post-test -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-pink-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-pink-600 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๔
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-pink-50 text-pink-700 font-bold border border-pink-200">
                            STEP 04
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-pink-600 transition">
                        ทดสอบหลังเรียน
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        ประเมินผลสัมฤทธิ์ทางการเรียนและคำนวณพัฒนาการ ($\langle g \rangle$)
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="pages/assessment_post.php" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-pink-50 hover:bg-pink-600 hover:text-white text-pink-700 text-xs sm:text-sm font-bold transition">
                        <span>วัดผลสัมฤทธิ์</span>
                        <i class="fa-solid fa-square-check text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Step 5: Evaluation -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-purple-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-purple-600 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๕
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 font-bold border border-purple-200">
                            STEP 05
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-purple-600 transition">
                        ประเมินโครงการ
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        ประเมินความพึงพอใจการจัดอบรม วิทยากร และประโยชน์ที่ได้รับ
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="pages/evaluation.php" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-purple-50 hover:bg-purple-600 hover:text-white text-purple-700 text-xs sm:text-sm font-bold transition">
                        <span>ประเมินค่าย</span>
                        <i class="fa-solid fa-star text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Step 6: Certificate -->
            <div class="stepper-step bg-white/95 rounded-2xl p-4 border border-amber-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group bg-gradient-to-b from-white to-amber-50/30">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="stepper-circle w-10 h-10 rounded-xl bg-amber-500 text-white font-bold flex items-center justify-center text-base shadow-md transition">
                            ๖
                        </span>
                        <span class="text-[11px] font-mono uppercase px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold border border-amber-300">
                            STEP 06
                        </span>
                    </div>
                    <h4 class="font-bold text-base text-slate-900 mb-1 group-hover:text-amber-700 transition">
                        รับวุฒิบัตรดิจิทัล
                    </h4>
                    <p class="text-xs sm:text-sm text-gray-600 leading-snug">
                        ดาวน์โหลดเกียรติบัตรอิเล็กทรอนิกส์พร้อม QR Code ตรวจสอบ
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="pages/certificate.php" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold transition shadow-xs">
                        <span>ดาวน์โหลด</span>
                        <i class="fa-solid fa-certificate text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>
