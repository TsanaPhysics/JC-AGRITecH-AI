<?php
/**
 * LEQs-xAI Registration Page
 */
$page_title = "ลงทะเบียนเข้าร่วมอบรม | LEQs-xAI";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@400;600;700&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between hero-pattern">

    <!-- Top Navbar -->
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-4 px-6 fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-cyan-500/40 flex items-center justify-center text-cyan-400 font-tech font-bold text-xl">
                    L
                </div>
                <div>
                    <div class="font-tech text-base font-black text-white">LEQs-xAI</div>
                    <div class="text-[10px] text-slate-400">ระบบลงทะเบียนเข้าร่วมอบรม</div>
                </div>
            </a>
            <div class="flex items-center gap-3 text-xs">
                <a href="../index.php" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
                </a>
                <a href="student_list.php" class="px-3.5 py-2 rounded-xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 hover:bg-cyan-500/30 font-semibold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check"></i> รายชื่อผู้สมัคร
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Registration Form Container -->
    <main class="pt-32 pb-20 px-4 max-w-2xl mx-auto w-full">
        <div class="p-8 sm:p-10 rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl relative">
            
            <div class="text-center mb-8">
                <span class="px-3.5 py-1 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase tracking-wider">
                    Registration Portal
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-heading mt-2">
                    ลงทะเบียนเข้าร่วมอบรมเชิงปฏิบัติการ
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-2">
                    โครงการ LEQs-xAI ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (ไม่มีค่าใช้จ่าย)
                </p>
            </div>

            <form id="regForm" onsubmit="submitRegistration(event)" class="space-y-5 text-xs sm:text-sm">
                
                <!-- Prefix & Fullname -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">คำนำหน้า</label>
                        <select id="prefix" name="prefix" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none">
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                            <!--
                            <option value="อาจารย์">อาจารย์</option>
                            <option value="ผศ.">ผศ.</option>
                            <option value="ดร.">ดร.</option -->
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block font-semibold text-slate-300 mb-1.5">ชื่อ - นามสกุล <span class="text-rose-400">*</span></label>
                        <input type="text" id="fullname" name="fullname" required placeholder="เช่น สมชาย ใจดี"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none placeholder:text-slate-600">
                    </div>
                </div>

                <!-- Target Group -->
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">กลุ่มเป้าหมายผู้สมัคร <span class="text-rose-400">*</span></label>
                    <select id="target_group" name="target_group" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none">
                        <option value="นักเรียนมัธยมศึกษาตอนปลาย">1. นักเรียนระดับมัธยมศึกษาตอนปลาย (ม.4 - ม.6)</option>
                        <option value="ครูและบุคลากรทางการศึกษา">2. ครูและบุคลากรทางการศึกษา (วิทยาศาสตร์/คอมพิวเตอร์/เกษตร)</option>
                        <option value="เกษตรกรผู้เพาะปลูก">3. เกษตรกรผู้เพาะปลูกผลไม้ / สวนทุเรียน / เกษตรกรรุ่นใหม่</option>
                        <option value="เกษตรกรและผู้สนใจทั่วไป">4. เกษตรกรและประชาชนผู้สนใจเทคโนโลยี AIoT ทั่วไป</option>
                    </select>
                </div>

                <!-- Organization / School -->
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">โรงเรียน / สวนเกษตร / หน่วยงานสังกัด <span class="text-rose-400">*</span></label>
                    <input type="text" id="organization" name="organization" required placeholder="เช่น โรงเรียนประณีตวิทยาคม หรือ สวนทุเรียนเขาสมิง"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none placeholder:text-slate-600">
                </div>

                <!-- Contact: Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">เบอร์โทรศัพท์ติดต่อ <span class="text-rose-400">*</span></label>
                        <input type="tel" id="phone" name="phone" required placeholder="08x-xxx-xxxx"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none placeholder:text-slate-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">อีเมล (ถ้ามี)</label>
                        <input type="email" id="email" name="email" placeholder="example@email.com"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none placeholder:text-slate-600">
                    </div>
                </div>

                <!-- Preferred Capstone Project Track -->
                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">แทร็กโครงงาน Capstone ที่สนใจเป็นพิเศษ</label>
                    <select id="project_track" name="project_track" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none">
                        <option value="Track A — Smart Agriculture">Track A — Smart Agriculture (ระบบฟาร์มอัจฉริยะครบวงจร)</option>
                        <option value="Track B — Plant Vision">Track B — Plant Vision (ตรวจจับโรคพืชด้วยคอมพิวเตอร์วิทัศน์)</option>
                        <option value="Track C — Smart Soil">Track C — Smart Soil (วิเคราะห์ธาตุอาหารดิน NPK & pH)</option>
                        <option value="Track D — Environmental AI">Track D — Environmental AI (สถานีตรวจวัดสภาพอากาศ & VPD)</option>
                        <option value="Track E — Edge AI">Track E — Edge AI (ระบบปัญญาประดิษฐ์ออฟไลน์บน ESP32-S3)</option>
                        <option value="Track F — School AI">Track F — School AI (โครงงานวิทย์ ม.ปลาย สะสม Portfolio)</option>
                    </select>
                </div>

                <!-- LINE Official Group QR Code Section (Mandatory) -->
                <div id="line_group_card" class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-950/40 via-slate-900/90 to-slate-950 border-2 border-emerald-500/40 shadow-xl shadow-emerald-950/40 relative overflow-hidden transition-all duration-300">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-emerald-500/20">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-[#06C755] text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-emerald-500/30 shrink-0">
                                <i class="fa-brands fa-line"></i>
                            </span>
                            <div>
                                <h3 class="font-bold text-sm sm:text-base text-white flex items-center gap-2">
                                    เข้าร่วมกลุ่มไลน์ทางการ (LEQs-xAI Workshop)
                                </h3>
                                <p class="text-[11px] sm:text-xs text-emerald-400">ช่องทางหลักสำหรับแจ้งกำหนดการ นัดหมาย ลิงก์ดาวน์โหลดสื่อ และประสานงานวิทยากร</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[11px] font-bold shrink-0 animate-pulse">
                            * บังคับเข้าร่วม
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
                        <!-- QR Code Image -->
                        <div class="sm:col-span-5 flex flex-col items-center justify-center p-3.5 rounded-xl bg-slate-950/90 border border-emerald-500/30">
                            <div class="p-2.5 bg-white rounded-2xl shadow-xl inline-block">
                                <img src="../assets/images/qr_line_group.jpg" alt="LINE Group QR Code LEQs-xAI" class="w-40 h-40 sm:w-44 sm:h-44 object-contain rounded-xl">
                            </div>
                            <span class="text-[11px] text-slate-400 mt-2 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-qrcode text-emerald-400"></i> สแกน QR Code ด้วย LINE
                            </span>
                        </div>

                        <!-- Instructions & Direct Link -->
                        <div class="sm:col-span-7 space-y-3.5">
                            <div class="space-y-2 text-xs text-slate-300">
                                <div class="flex items-start gap-2 bg-slate-950/50 p-2.5 rounded-xl border border-slate-800">
                                    <i class="fa-solid fa-desktop text-emerald-400 mt-0.5 text-xs shrink-0"></i>
                                    <span><strong>เข้าใช้งานผ่านคอมพิวเตอร์:</strong> เปิดแอปพลิเคชัน LINE บนมือถือ แล้วเปิดกล้องสแกน QR Code ด้านซ้าย</span>
                                </div>
                                <div class="flex items-start gap-2 bg-slate-950/50 p-2.5 rounded-xl border border-slate-800">
                                    <i class="fa-solid fa-mobile-screen-button text-cyan-400 mt-0.5 text-xs shrink-0"></i>
                                    <span><strong>เข้าใช้งานผ่านมือถือ:</strong> แตะปุ่มสีเขียวด้านล่างเพื่อเปิดกลุ่ม LINE และกดเข้าร่วมกลุ่มได้ทันที</span>
                                </div>
                            </div>

                            <a href="https://line.me/R/ti/g/svZ-F2msk-" target="_blank" rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-[#06C755] hover:bg-[#05b34c] text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.01] transition-all">
                                <i class="fa-brands fa-line text-lg"></i>
                                <span>แตะที่นี่เพื่อเข้าร่วมกลุ่มไลน์ทันที</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px] opacity-80"></i>
                            </a>

                            <!-- Mandatory Checkbox Confirmation -->
                            <div class="p-3.5 rounded-xl bg-emerald-500/10 border-2 border-emerald-500/40 transition-colors hover:bg-emerald-500/15">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input type="checkbox" id="line_group_joined" name="line_group_joined" required
                                           class="mt-0.5 w-4 h-4 rounded text-emerald-500 focus:ring-emerald-400 accent-emerald-500 cursor-pointer shrink-0">
                                    <span class="text-xs sm:text-sm font-semibold text-emerald-200 leading-snug">
                                        ข้าพเจ้าได้สแกน QR Code หรือกดเข้าร่วมกลุ่มไลน์ทางการ "LEQs-xAI Workshop" เรียบร้อยแล้ว <span class="text-rose-400 font-bold">* (จำเป็นต้องยืนยัน)</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participant Terms & Commitment Box (Outcome-Based Assessment Criteria) -->
                <div id="terms_condition_card" class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-slate-900/95 via-cyan-950/20 to-slate-950 border-2 border-cyan-500/40 shadow-xl shadow-cyan-950/40 relative overflow-hidden transition-all duration-300">
                    <div class="absolute -top-12 -left-12 w-36 h-36 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-cyan-500/20">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 flex items-center justify-center font-bold text-lg shadow-lg shadow-cyan-500/20 shrink-0">
                                <i class="fa-solid fa-file-contract"></i>
                            </span>
                            <div>
                                <h3 class="font-bold text-sm sm:text-base text-white flex items-center gap-2">
                                    เงื่อนไขและข้อตกลงการเข้าร่วมโครงการ (เพื่อการวัดผลสัมฤทธิ์จริง)
                                </h3>
                                <p class="text-[11px] sm:text-xs text-cyan-400">กรอบการประเมินผลลัพธ์เชิงประจักษ์ (Outcome-Based Assessment) เพื่อรับรองสมรรถนะ AIoT</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 text-[11px] font-bold shrink-0">
                            มาตรฐานวิชาการ RBRU
                        </span>
                    </div>

                    <!-- 4 Core Criteria Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-300 mb-4">
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-950/70 border border-slate-800/80">
                            <div class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 font-bold flex items-center justify-center shrink-0 text-xs">1</div>
                            <div>
                                <strong class="text-white">เวลาเข้าร่วมกิจกรรมไม่น้อยกว่า 80%</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed">ต้องเข้าร่วมการอบรมเชิงปฏิบัติการครบตามกำหนด สแกน QR Code เช็คชื่อเช้า-บ่าย เพื่อบันทึกเวลาเข้ารับการอบรมจริง</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-950/70 border border-slate-800/80">
                            <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center shrink-0 text-xs">2</div>
                            <div>
                                <strong class="text-white">การทดสอบวัดผลสัมฤทธิ์ (Pre/Post Test)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed">ต้องทำแบบทดสอบทั้งก่อนและหลังการอบรม โดยมีผลคะแนนผ่านเกณฑ์ $\ge 70\%$ หรือมีอัตราการพัฒนา $\langle g \rangle \ge 50\%$</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-950/70 border border-slate-800/80">
                            <div class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center shrink-0 text-xs">3</div>
                            <div>
                                <strong class="text-white">ปฏิบัติการและส่งโครงงาน (Capstone Project)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed">ฝึกประกอบวงจร เชื่อมต่อ Dashboard และส่งผลงานต้นแบบ AIoT ตามแทร็กที่เลือก ประเมินผ่านเกณฑ์ Rubric $\ge 75\%$</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-950/70 border border-slate-800/80">
                            <div class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 font-bold flex items-center justify-center shrink-0 text-xs">4</div>
                            <div>
                                <strong class="text-white">การนำไปใช้จริงในพื้นที่ (Field Deployment)</strong>
                                <p class="text-slate-400 text-[11px] mt-0.5 leading-relaxed">ยินยอมส่งภาพถ่ายหรือรายงานสั้นๆ 1 หน้า แสดงการนำความรู้/อุปกรณ์ไปทดลองติดตั้งใช้งานในแปลงเกษตรหรือโรงเรียนใน 30–60 วัน</p>
                            </div>
                        </div>
                    </div>

                    <!-- Certification Note -->
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-amber-500/30 text-[11px] sm:text-xs text-slate-300 mb-4 flex items-start gap-2.5">
                        <i class="fa-solid fa-award text-amber-400 text-sm mt-0.5 shrink-0"></i>
                        <span><strong>สิทธิ์ในการรับวุฒิบัตร (Certification):</strong> ผู้ที่ผ่านเกณฑ์ครบถ้วนจะได้รับ <strong>วุฒิบัตรรับรองสมรรถนะ AIoT (Certificate of Competence)</strong> ออกโดยคณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</span>
                    </div>

                    <!-- Mandatory Agreement Checkbox -->
                    <div class="p-3.5 rounded-xl bg-cyan-500/10 border-2 border-cyan-500/40 transition-colors hover:bg-cyan-500/15">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" id="terms_agreed" name="terms_agreed" required
                                   class="mt-0.5 w-4 h-4 rounded text-cyan-500 focus:ring-cyan-400 accent-cyan-500 cursor-pointer shrink-0">
                            <span class="text-xs sm:text-sm font-semibold text-cyan-200 leading-snug">
                                ข้าพเจ้าได้อ่าน เข้าใจ และยอมรับเงื่อนไขการเข้าร่วมโครงการเพื่อการวัดผลสัมฤทธิ์ทุกประการ <span class="text-rose-400 font-bold">* (จำเป็นต้องยอมรับ)</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" id="btnSubmit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-sky-500 text-slate-950 font-bold text-sm shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> ยืนยันการลงทะเบียน
                    </button>
                </div>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        LEQs-xAI Registration System © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี & โรงเรียนประณีตวิทยาคม
    </footer>

    <!-- Submit Logic -->
    <script>
    async function submitRegistration(e) {
        e.preventDefault();

        // 1. Mandatory check: Must join LINE Group
        const lineJoined = document.getElementById('line_group_joined');
        if (!lineJoined || !lineJoined.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเข้าร่วมกลุ่มไลน์ก่อน',
                html: '<div class="space-y-2 text-sm text-slate-300 text-left sm:text-center">' +
                      '<p>โครงการกำหนดให้ผู้สมัครทุกคนต้องสแกน QR Code หรือกดเข้าร่วมกลุ่มไลน์ทางการ <b>LEQs-xAI Workshop</b></p>' +
                      '<p class="text-emerald-400 text-xs font-semibold">เพื่อรับการแจ้งเตือนกำหนดการ ลิงก์สื่อการสอน และประสานงานกับทีมวิทยากร</p>' +
                      '</div>',
                confirmButtonColor: '#10B981',
                confirmButtonText: '<i class="fa-brands fa-line mr-1"></i> ไปที่ QR Code กลุ่มไลน์'
            }).then(() => {
                const card = document.getElementById('line_group_card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-4', 'ring-emerald-400', 'border-emerald-400');
                    setTimeout(() => card.classList.remove('ring-4', 'ring-emerald-400'), 3000);
                }
                if (lineJoined) lineJoined.focus();
            });
            return false;
        }

        // 2. Mandatory check: Must accept Terms & Conditions
        const termsAgreed = document.getElementById('terms_agreed');
        if (!termsAgreed || !termsAgreed.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณายอมรับเงื่อนไขโครงการ',
                html: '<div class="space-y-2 text-sm text-slate-300 text-left sm:text-center">' +
                      '<p>ผู้สมัครต้องรับทราบและยอมรับ <b>เงื่อนไขและข้อตกลงการเข้าร่วมโครงการ</b></p>' +
                      '<p class="text-cyan-400 text-xs font-semibold">(เวลาเรียน $\\ge 80\%$, Pre/Post Test, ส่งโครงงาน Capstone และรายงานการนำไปใช้จริง)</p>' +
                      '</div>',
                confirmButtonColor: '#06B6D4',
                confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> ไปที่กล่องเงื่อนไข'
            }).then(() => {
                const card = document.getElementById('terms_condition_card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-4', 'ring-cyan-400', 'border-cyan-400');
                    setTimeout(() => card.classList.remove('ring-4', 'ring-cyan-400'), 3000);
                }
                if (termsAgreed) termsAgreed.focus();
            });
            return false;
        }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> กำลังบันทึกข้อมูล...';

        const payload = {
            prefix: document.getElementById('prefix').value,
            fullname: document.getElementById('fullname').value,
            target_group: document.getElementById('target_group').value,
            organization: document.getElementById('organization').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            project_track: document.getElementById('project_track').value,
            line_group_joined: true,
            terms_agreed: true
        };

        try {
            const res = await fetch('../api/api.php?action=register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'ลงทะเบียนสำเร็จ!',
                    html: '<div class="space-y-3 text-sm text-slate-300">' +
                          '<p>ยินดีต้อนรับเข้าสู่โครงการอบรม LEQs-xAI ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</p>' +
                          '<div class="p-3 bg-emerald-950/60 rounded-xl border border-emerald-500/40 text-emerald-300 text-xs">' +
                          '<i class="fa-brands fa-line text-base mr-1"></i> หากท่านยังไม่ได้เข้าร่วมกลุ่มไลน์ กรุณากดปุ่มด้านล่างนี้' +
                          '</div>' +
                          '</div>',
                    showCancelButton: true,
                    confirmButtonColor: '#06B6D4',
                    cancelButtonColor: '#06C755',
                    confirmButtonText: '<i class="fa-solid fa-list mr-1"></i> ดูรายชื่อผู้สมัคร',
                    cancelButtonText: '<i class="fa-brands fa-line mr-1"></i> เปิดกลุ่มไลน์ทันที'
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.open('https://line.me/R/ti/g/svZ-F2msk-', '_blank');
                        setTimeout(() => { window.location.href = 'student_list.php'; }, 1000);
                    } else {
                        window.location.href = 'student_list.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถลงทะเบียนได้ กรุณาลองใหม่อีกครั้ง',
                    confirmButtonColor: '#F43F5E'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ยืนยันการลงทะเบียน';
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'การเชื่อมต่อผิดพลาด',
                text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ กรุณาตรวจสอบอินเทอร์เน็ต',
                confirmButtonColor: '#F43F5E'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ยืนยันการลงทะเบียน';
        }
    }
    </script>
</body>
</html>
