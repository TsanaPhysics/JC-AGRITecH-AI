<!-- ========================================================================= -->
<!-- COMPONENT: COMPUTER VISION & EDGE AI (PYTORCH & ONNX) MODAL               -->
<!-- Connected with /3.6_CV-pytorch (MobileNetV3 Durian Leaf Disease Model)   -->
<!-- ========================================================================= -->
<div id="modalCvVision" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4">
    <div class="glass-panel rounded-3xl p-5 sm:p-7 max-w-5xl w-full border border-purple-500/40 shadow-2xl space-y-5 bg-[#090e1c] max-h-[92vh] overflow-y-auto widget-enter">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 via-pink-600 to-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-purple-500/30">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg sm:text-xl font-bold font-tech text-white">
                            Computer Vision &amp; Edge AI Hub (CV-PyTorch)
                        </h3>
                        <span class="text-[10px] font-mono uppercase bg-purple-950 text-purple-300 px-2.5 py-0.5 rounded-full border border-purple-500/40">
                            MobileNetV3 + ONNX
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-tech">
                        ระบบปัญญาประดิษฐ์คอมพิวเตอร์วิทัศน์ตรวจวินิจฉัยโรคในใบพืชและทุเรียน • โดย ผศ.ดร.ชีวะ ทัศนา (มรภ.รำไพพรรณี)
                    </p>
                </div>
            </div>
            <button onclick="closeCvVisionModal()" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- 4 Quick Highlights Ribbon -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-mono">
            <div class="p-3 rounded-2xl bg-slate-900/90 border border-purple-500/20 text-center space-y-1">
                <span class="text-[10px] text-purple-400 block font-sans">สถาปัตยกรรมโมเดล</span>
                <span class="font-bold text-white text-sm block">MobileNetV3</span>
                <span class="text-[9px] text-slate-500 block">PyTorch &bull; 6.1 MB</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-900/90 border border-cyan-500/20 text-center space-y-1">
                <span class="text-[10px] text-cyan-400 block font-sans">ความแม่นยำโมเดล</span>
                <span class="font-bold text-cyan-300 text-sm block">98.4% Top-1</span>
                <span class="text-[9px] text-slate-500 block">Grad-CAM XAI</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-900/90 border border-emerald-500/20 text-center space-y-1">
                <span class="text-[10px] text-emerald-400 block font-sans">การรันประมวลผล</span>
                <span class="font-bold text-emerald-300 text-sm block">100% On-Device</span>
                <span class="text-[9px] text-slate-500 block">ONNX WebAssembly</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-900/90 border border-amber-500/20 text-center space-y-1">
                <span class="text-[10px] text-amber-400 block font-sans">รองรับโรคพืช</span>
                <span class="font-bold text-amber-300 text-sm block">4 คลาสหลัก</span>
                <span class="text-[9px] text-slate-500 block">ใบจุดสาหร่าย/ใบติด/สนิม/ปกติ</span>
            </div>
        </div>

        <!-- Main Interactive Embedded Application Preview -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-tech">
                <span class="text-slate-300 font-bold flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen-button text-purple-400"></i>
                    หน้าจอแอปพลิเคชันเสมือนจริง (Interactive Web App &bull; ONNX Engine)
                </span>
                <a href="../../../3.6_CV-pytorch/app/2026-10-16-durian-leaf-app/index.html" target="_blank" class="text-cyan-400 hover:text-cyan-300 flex items-center gap-1 font-mono">
                    <span>เปิดแบบเต็มจอ (New Tab)</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>

            <!-- Iframe Window Container -->
            <div class="relative w-full rounded-2xl overflow-hidden border border-purple-500/30 bg-slate-950 shadow-inner" style="height: 520px;">
                <iframe id="cvVisionIframe" 
                        src="../../../3.6_CV-pytorch/app/2026-10-16-durian-leaf-app/index.html" 
                        class="w-full h-full border-0" 
                        title="Computer Vision Durian Leaf Disease App">
                </iframe>
            </div>
        </div>

        <!-- 4 Disease Class Summary & Agronomy Advice -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-1">
                <div class="flex items-center justify-between font-bold text-emerald-400 font-tech">
                    <span>1. ใบสมบูรณ์แข็งแรง</span>
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="text-[10px] text-slate-400 font-mono block">Healthy Durian Leaf</span>
                <p class="text-[11px] text-slate-300 leading-relaxed">
                    ใบมีคลอโรฟิลล์สม่ำเสมอ แผ่นใบเรียบตึงสังเคราะห์แสงได้เต็มศักยภาพ ไม่ต้องพ่นสารเคมี
                </p>
            </div>

            <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-1">
                <div class="flex items-center justify-between font-bold text-amber-400 font-tech">
                    <span>2. โรคใบจุดสาหร่าย</span>
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <span class="text-[10px] text-slate-400 font-mono block">Cephaleuros virescens</span>
                <p class="text-[11px] text-slate-300 leading-relaxed">
                    จุดฟูกำมะหยี่สีส้มอมเขียว แนะนำพ่นสารประกอบทองแดง (คอปเปอร์ออกซีคลอไรด์) หรือตัดแต่งทรงพุ่ม
                </p>
            </div>

            <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-1">
                <div class="flex items-center justify-between font-bold text-rose-400 font-tech">
                    <span>3. โรคใบติด (ใบไหม้)</span>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="text-[10px] text-slate-400 font-mono block">Rhizoctonia solani</span>
                <p class="text-[11px] text-slate-300 leading-relaxed">
                    แผลคล้ายโดนน้ำร้อนลวก ใบติดกันเป็นกระจุก แนะนำสารเฮกซะโคนาโซล หรือไดฟีโนโคนาโซล
                </p>
            </div>

            <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-1">
                <div class="flex items-center justify-between font-bold text-cyan-400 font-tech">
                    <span>4. โรคใบจุดสนิม/ทั่วไป</span>
                    <i class="fa-solid fa-circle-notch"></i>
                </div>
                <span class="text-[10px] text-slate-400 font-mono block">Colletotrichum spp.</span>
                <p class="text-[11px] text-slate-300 leading-relaxed">
                    จุดแผลสีน้ำตาลขอบเข้ม วงซ้อนกันเป็นชั้น แนะนำพ่นสารอะซอกซีสโตรบิน หรือแมนโคเซบ
                </p>
            </div>
        </div>

        <!-- Download & Academic Assets Bar -->
        <div class="pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
            <div class="text-xs text-slate-400 font-tech">
                <i class="fa-solid fa-graduation-cap text-purple-400 mr-1.5"></i>
                โครงงานภายใต้การสนับสนุนของ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- Academic PDF Manual Link -->
                <a href="../../../3.6_CV-pytorch/docs/main.pdf" target="_blank" 
                   class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-white/10 text-xs font-bold font-tech flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-file-pdf text-rose-400"></i> คู่มือวิชาการ 6x9 (PDF)
                </a>

                <!-- Android APK Download Link -->
                <a href="../../../3.6_CV-pytorch/app/2026-10-16-durian-leaf-app/2026-10-16-durian-leaf-app.apk" download
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold font-tech flex items-center gap-1.5 transition shadow-lg shadow-emerald-900/30">
                    <i class="fa-brands fa-android text-sm"></i> ดาวน์โหลด APK (18.5 MB)
                </a>

                <!-- Launch Web App Link -->
                <a href="../../../3.6_CV-pytorch/app/2026-10-16-durian-leaf-app/index.html" target="_blank" 
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold font-tech flex items-center gap-1.5 transition shadow-lg shadow-purple-900/40">
                    <i class="fa-solid fa-rocket"></i> เปิดแอปแบบเต็มจอ
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function openCvVisionModal() {
    const m = document.getElementById('modalCvVision');
    if (m) {
        m.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeCvVisionModal() {
    const m = document.getElementById('modalCvVision');
    if (m) {
        m.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
</script>
