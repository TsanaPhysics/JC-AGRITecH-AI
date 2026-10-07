<?php
/**
 * LEQs-xAI Pre-test Assessment Portal
 */
$page_title = "แบบทดสอบก่อนเรียน (Pre-test) | LEQs-xAI";
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
                    <div class="text-[9px] sm:text-[10px] text-slate-400">แบบทดสอบวัดความรู้ก่อนเรียน</div>
                </div>
            </a>
            <a href="../index.php" class="px-3 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition flex items-center gap-1.5" title="กลับหน้าหลัก">
                <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">หน้าหลัก</span>
            </a>
        </div>
    </nav>

    <!-- Quiz Content -->
    <main class="pt-24 sm:pt-32 pb-20 px-3 sm:px-4 max-w-3xl mx-auto w-full">
        <div class="p-5 sm:p-10 rounded-2xl sm:rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl">
            
            <div class="text-center mb-6 sm:mb-8">
                <span class="px-3.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                    Pre-training Assessment
                </span>
                <h1 class="text-xl sm:text-3xl font-black text-white font-heading mt-2">
                    แบบทดสอบก่อนเรียน: LEQs-xAI
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-2">
                    วัดความรู้พื้นฐานด้าน Embedded AI, TinyML, Digital Electronics, IIoT และเซนเซอร์สิ่งแวดล้อม
                </p>
            </div>

            <!-- Quiz Questions Form -->
            <form id="quizForm" onsubmit="calculateScore(event)" class="space-y-8 text-sm">
                
                <!-- Q1 -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800/80 space-y-3">
                    <div class="font-bold text-white flex items-start gap-2">
                        <span class="px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-mono text-xs">ข้อ 1</span>
                        <span>เทคโนโลยี "Edge AI" มีความแตกต่างจาก "Cloud AI" ในมิติสำคัญใดมากที่สุด?</span>
                    </div>
                    <div class="space-y-2 text-xs sm:text-sm text-slate-300 pl-8">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q1" value="A" required class="accent-cyan-400">
                            <span>A. Edge AI ต้องส่งรูปภาพทั้งหมดไปยังเซิร์ฟเวอร์ขนาดใหญ่เพื่อประมวลผล</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q1" value="B" class="accent-cyan-400">
                            <span>B. Edge AI ประมวลผลบนชิปไมโครคอนโทรลเลอร์ขนาดเล็กได้ทันทีแบบเรียลไทม์ โดยไม่ต้องพึ่งพาอินเทอร์เน็ต</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q1" value="C" class="accent-cyan-400">
                            <span>C. Edge AI ใช้พลังงานไฟฟ้ามากกว่าคลาวด์หลายเท่าตัว</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q1" value="D" class="accent-cyan-400">
                            <span>D. Edge AI สามารถทำงานได้เฉพาะบนสมาร์ทโฟนระบบ iOS เท่านั้น</span>
                        </label>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800/80 space-y-3">
                    <div class="font-bold text-white flex items-start gap-2">
                        <span class="px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-mono text-xs">ข้อ 2</span>
                        <span>ค่าความดันไอขาดดุล (Vapor Pressure Deficit หรือ VPD) มีความสำคัญอย่างไรต่อสรีรวิทยาของพืช?</span>
                    </div>
                    <div class="space-y-2 text-xs sm:text-sm text-slate-300 pl-8">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q2" value="A" required class="accent-cyan-400">
                            <span>A. เป็นดัชนีบอกระดับการคายน้ำและการเปิดปิดปากใบของพืช รวมถึงประเมินความเสี่ยงต่อโรครา</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q2" value="B" class="accent-cyan-400">
                            <span>B. เป็นค่าบอกปริมาณปุ๋ยเคมีไนโตรเจนที่ตกค้างในดิน</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q2" value="C" class="accent-cyan-400">
                            <span>C. เป็นค่าตรวจวัดความเป็นกรด-ด่างของน้ำฝน</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q2" value="D" class="accent-cyan-400">
                            <span>D. เป็นดัชนีวัดความเค็มของดินชายฝั่งทะเล</span>
                        </label>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800/80 space-y-3">
                    <div class="font-bold text-white flex items-start gap-2">
                        <span class="px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-mono text-xs">ข้อ 3</span>
                        <span>โปรโตคอลการสื่อสารใดนิยมใช้เชื่อมต่อระหว่างเซนเซอร์วัดดิน 7-in-1 อุตสาหกรรมเข้ากับบอร์ด ESP32-S3 ในระยะไกล?</span>
                    </div>
                    <div class="space-y-2 text-xs sm:text-sm text-slate-300 pl-8">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q3" value="A" required class="accent-cyan-400">
                            <span>A. Bluetooth Classic</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q3" value="B" class="accent-cyan-400">
                            <span>B. RS485 Modbus RTU</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q3" value="C" class="accent-cyan-400">
                            <span>C. USB Audio Class</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q3" value="D" class="accent-cyan-400">
                            <span>D. HDMI Cable</span>
                        </label>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800/80 space-y-3">
                    <div class="font-bold text-white flex items-start gap-2">
                        <span class="px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 font-mono text-xs">ข้อ 4</span>
                        <span>สถาปัตยกรรมโครงข่ายประสาทเทียมชนิดใดที่เหมาะสมที่สุดสำหรับงานจำแนกภาพถ่ายโรคใบพืช (Computer Vision)?</span>
                    </div>
                    <div class="space-y-2 text-xs sm:text-sm text-slate-300 pl-8">
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q4" value="A" required class="accent-cyan-400">
                            <span>A. Recurrent Neural Network (RNN)</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q4" value="B" class="accent-cyan-400">
                            <span>B. Convolutional Neural Network (CNN)</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q4" value="C" class="accent-cyan-400">
                            <span>C. Linear Regression ธรรมดา</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-white">
                            <input type="radio" name="q4" value="D" class="accent-cyan-400">
                            <span>D. K-Means Clustering</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 font-bold text-sm shadow-xl hover:scale-[1.01] transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check-double"></i> ส่งคำตอบและตรวจผลคะแนน
                </button>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        LEQs-xAI Assessment System © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี
    </footer>

    <script>
    function calculateScore(e) {
        e.preventDefault();
        const answers = { q1: 'B', q2: 'A', q3: 'B', q4: 'B' };
        let score = 0;
        for (const [q, correct] of Object.entries(answers)) {
            const selected = document.querySelector(`input[name="${q}"]:checked`);
            if (selected && selected.value === correct) score++;
        }

        Swal.fire({
            icon: score >= 3 ? 'success' : 'info',
            title: `ผลคะแนน Pre-test: ${score}/4 ข้อ`,
            html: `
                <div class="text-left text-sm space-y-2 mt-3">
                    <p><b>ข้อ 1:</b> เฉลย B (Edge AI ประมวลผลบนชิปปลายทางได้ทันที)</p>
                    <p><b>ข้อ 2:</b> เฉลย A (VPD บ่งชี้การคายน้ำและการเตือนโรครา)</p>
                    <p><b>ข้อ 3:</b> เฉลย B (RS485 Modbus RTU เป็นมาตรฐานเซนเซอร์เกษตร)</p>
                    <p><b>ข้อ 4:</b> เฉลย B (CNN เป็นหัวใจของการจำแนกภาพพืช)</p>
                </div>
            `,
            confirmButtonColor: '#06B6D4',
            confirmButtonText: 'กลับหน้าหลัก'
        }).then(() => {
            window.location.href = '../index.php';
        });
    }
    </script>
</body>
</html>
