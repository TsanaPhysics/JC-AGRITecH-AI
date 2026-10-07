<?php
/**
 * LEQs-xAI Post-test & E-Certificate Generator
 */
$page_title = "แบบทดสอบหลังเรียน & วุฒิบัตร | LEQs-xAI";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
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
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-3.5 px-4 sm:px-6 fixed w-full top-0 z-50">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-800 border border-cyan-500/40 flex items-center justify-center text-cyan-400 font-tech font-bold text-lg sm:text-xl shrink-0">
                    L
                </div>
                <div>
                    <div class="font-tech text-sm sm:text-base font-black text-white">LEQs-xAI</div>
                    <div class="text-[9px] sm:text-[10px] text-slate-400">แบบทดสอบหลังเรียน & วุฒิบัตร E-Cert</div>
                </div>
            </a>
            <a href="../index.php" class="px-3 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition flex items-center gap-1.5" title="กลับหน้าหลัก">
                <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">หน้าหลัก</span>
            </a>
        </div>
    </nav>

    <!-- Post-test / Cert Content -->
    <main class="pt-24 sm:pt-32 pb-20 px-3 sm:px-4 max-w-3xl mx-auto w-full">
        <div class="p-5 sm:p-10 rounded-2xl sm:rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl">
            
            <div class="text-center mb-6 sm:mb-8">
                <span class="px-3.5 py-1 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20 text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                    Post-training & Certification
                </span>
                <h1 class="text-xl sm:text-3xl font-black text-white font-heading mt-2">
                    ประเมินผลหลังเรียน & รับเกียรติบัตร
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-2">
                    กรอกชื่อ-นามสกุล และทำแบบทดสอบเพื่อออกวุฒิบัตรรับรองทักษะปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block font-semibold text-slate-300 text-xs sm:text-sm mb-1.5">ชื่อ-นามสกุล ที่จะพิมพ์บนเกียรติบัตร (ภาษาไทย หรือ อังกฤษ)</label>
                    <input type="text" id="certName" placeholder="เช่น นายสมชาย ใจดี หรือ Somchai Jaidee" 
                           class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-sm focus:border-cyan-400 outline-none">
                </div>

                <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-4">
                    <i class="fa-solid fa-award text-5xl text-purple-400 animate-bounce"></i>
                    <h3 class="font-bold text-white text-base">เกียรติบัตรรับรองสมรรถนะ: LEQs-xAI Young Innovator</h3>
                    <p class="text-xs text-slate-400 max-w-md mx-auto">
                        ออกโดย โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI) คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                    </p>
                    <button onclick="generateCertificate()" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold text-xs uppercase tracking-wider hover:scale-105 transition flex items-center justify-center gap-2 mx-auto">
                        <i class="fa-solid fa-file-pdf"></i> ออกเกียรติบัตรอิเล็กทรอนิกส์ (E-Certificate)
                    </button>
                </div>
            </div>

            <!-- Certificate Canvas Preview (Hidden by default) -->
            <div id="certPreview" class="hidden mt-8 p-6 rounded-2xl bg-slate-950 border border-slate-800 text-center">
                <canvas id="certCanvas" width="1000" height="700" class="w-full h-auto rounded-xl shadow-2xl border border-slate-800 mx-auto"></canvas>
                <div class="mt-4">
                    <button onclick="downloadCertImage()" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs hover:bg-emerald-400 transition">
                        <i class="fa-solid fa-download mr-1"></i> ดาวน์โหลดรูปภาพเกียรติบัตร (PNG)
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        LEQs-xAI Certificate System © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี
    </footer>

    <script>
    function generateCertificate() {
        const name = document.getElementById('certName').value.trim();
        if (!name) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณากรอกชื่อ-นามสกุล',
                text: 'เพื่อพิมพ์ลงในใบประกาศนียบัตร',
                confirmButtonColor: '#8B5CF6'
            });
            return;
        }

        const preview = document.getElementById('certPreview');
        preview.classList.remove('hidden');
        const canvas = document.getElementById('certCanvas');
        const ctx = canvas.getContext('2d');

        // Certificate Background
        ctx.fillStyle = '#0B1120';
        ctx.fillRect(0, 0, 1000, 700);

        // Gold & Cyan Border
        ctx.lineWidth = 10;
        ctx.strokeStyle = '#06B6D4';
        ctx.strokeRect(20, 20, 960, 660);

        ctx.lineWidth = 2;
        ctx.strokeStyle = '#F59E0B';
        ctx.strokeRect(35, 35, 930, 630);

        // University Header
        ctx.fillStyle = '#10B981';
        ctx.font = 'bold 22px Kanit, Sarabun, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('มหาวิทยาลัยราชภัฏรำไพพรรณี', 500, 100);

        ctx.fillStyle = '#94A3B8';
        ctx.font = '16px Sarabun, sans-serif';
        ctx.fillText('คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี', 500, 130);

        // Title
        ctx.fillStyle = '#38BDF8';
        ctx.font = 'bold 36px Kanit, Sarabun, sans-serif';
        ctx.fillText('เกียรติบัตรรับรองการฝึกอบรม', 500, 210);

        ctx.fillStyle = '#E2E8F0';
        ctx.font = '18px Sarabun, sans-serif';
        ctx.fillText('ขอมอบเกียรติบัตรฉบับนี้ไว้เพื่อแสดงว่า', 500, 260);

        // Recipient Name
        ctx.fillStyle = '#F59E0B';
        ctx.font = 'bold 36px Sarabun, sans-serif';
        ctx.fillText(name, 500, 330);

        // Achievement Text
        ctx.fillStyle = '#CBD5E1';
        ctx.font = '18px Sarabun, sans-serif';
        ctx.fillText('ได้ผ่านการฝึกอบรมเชิงปฏิบัติการโครงการ LEQs-xAI', 500, 385);
        ctx.fillText('“ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม”', 500, 418);
        ctx.font = '15px Sarabun, sans-serif';
        ctx.fillText('ระหว่างวันที่ 28 - 30 พฤศจิกายน พ.ศ. 2569 ณ คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี', 500, 450);
        ctx.fillText('ครอบคลุมทักษะ: Embedded AI • Edge TinyML • Digital Electronics • IIoT • Computer Vision', 500, 480);

        // Signatures
        ctx.fillStyle = '#E2E8F0';
        ctx.font = '15px Sarabun, sans-serif';
        ctx.fillText('(ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา)', 300, 580);
        ctx.fillStyle = '#94A3B8';
        ctx.font = '13px Sarabun, sans-serif';
        ctx.fillText('หัวหน้าโครงการและผู้รับผิดชอบหลักสูตร', 300, 605);

        ctx.fillStyle = '#E2E8F0';
        ctx.font = '15px Sarabun, sans-serif';
        ctx.fillText('(รองศาสตราจารย์ ดร.อาภาพร บุญมี)', 700, 580);
        ctx.fillStyle = '#94A3B8';
        ctx.font = '13px Sarabun, sans-serif';
        ctx.fillText('คณบดีคณะวิทยาศาสตร์และเทคโนโลยี', 700, 605);

        Swal.fire({
            icon: 'success',
            title: 'สร้างเกียรติบัตรสำเร็จ!',
            text: 'สามารถดาวน์โหลดไฟล์รูปภาพเกียรติบัตรได้ทันที',
            confirmButtonColor: '#10B981'
        });
    }

    function downloadCertImage() {
        const canvas = document.getElementById('certCanvas');
        const link = document.createElement('a');
        link.download = `LEQs_xAI_Certificate_${Date.now()}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
    </script>
</body>
</html>
