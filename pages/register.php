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
                            <option value="อาจารย์">อาจารย์</option>
                            <option value="ผศ.">ผศ.</option>
                            <option value="ดร.">ดร.</option>
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
            project_track: document.getElementById('project_track').value
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
                    text: 'ยินดีต้อนรับเข้าสู่โครงการอบรม LEQs-xAI ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม',
                    confirmButtonColor: '#06B6D4',
                    confirmButtonText: 'ดูรายชื่อผู้สมัคร'
                }).then(() => {
                    window.location.href = 'student_list.php';
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
