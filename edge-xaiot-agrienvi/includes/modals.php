    <!-- ========================================================================= -->
    <!-- 11. AUTONOMOUS AGENTIC AI ASSISTANT ("น้อง LEQs AI" - GLOBE 2570 ARCHITECTURE) -->
    <!-- ========================================================================= -->
    <?php require_once __DIR__ . '/leqs_chatbot.php'; ?>

    <!-- ========================================================================= -->
    <!-- 12. SCREEN LIGHTBOX MODAL                                                 -->
    <!-- ========================================================================= -->
    <div id="screen-modal" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4">
        <div class="relative max-w-4xl w-full bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-2xl border border-gray-100 max-h-[92vh] overflow-y-auto">
            <button onclick="closeScreenModal()" class="absolute top-3 right-3 sm:top-5 sm:right-5 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-800 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm sm:text-lg"></i>
            </button>
            <div class="space-y-3 sm:space-y-4">
                <div class="pr-10">
                    <h3 id="modal-screen-title" class="text-base sm:text-xl font-bold text-gray-900 font-heading"></h3>
                    <p id="modal-screen-desc" class="text-[11px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1"></p>
                </div>
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden border border-gray-100 bg-slate-950 p-2 sm:p-4 flex items-center justify-center min-h-[260px] sm:min-h-[360px]">
                    <img id="modal-screen-bg" src="" alt="" class="absolute inset-0 w-full h-full object-cover blur-2xl opacity-40 scale-110 pointer-events-none">
                    <img id="modal-screen-img" src="" alt="Screen Preview" class="relative z-10 w-auto h-auto max-w-full max-h-[65vh] object-contain mx-auto drop-shadow-2xl rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 12.1 3D AR MEDIAPIPE SIMULATOR FULLSCREEN MODAL                            -->
    <!-- ========================================================================= -->
    <div id="ar-lab-modal" class="fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-xl hidden flex-col items-center justify-center p-2 sm:p-4">
        <div class="relative w-full max-w-6xl h-[94vh] bg-slate-900 rounded-2xl sm:rounded-3xl border border-cyan-500/30 shadow-2xl flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="px-4 py-3 bg-slate-950/90 border-b border-slate-800 flex items-center justify-between text-white flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-sm border border-cyan-500/40">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <div>
                        <h4 id="ar-modal-title" class="font-bold text-sm font-['Chakra_Petch'] text-cyan-300">3D AR Virtual Lab</h4>
                        <p class="text-[10px] text-slate-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            MediaPipe Hands 21-Joint &bull; Web Audio API Active
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a id="ar-modal-fullscreen-btn" href="#" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-400 text-xs font-semibold flex items-center gap-1.5 border border-slate-700 transition" title="เปิดหน้าต่างแยกเต็มจอ">
                        <i class="fa-solid fa-up-right-from-square"></i> <span class="hidden sm:inline">เปิดเต็มจอ</span>
                    </a>
                    <button onclick="closeArLabModal()" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-rose-900/60 text-slate-400 hover:text-rose-300 flex items-center justify-center transition" title="ปิดหน้าต่าง">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Iframe Container -->
            <div class="relative flex-1 w-full bg-black overflow-hidden">
                <iframe id="ar-modal-iframe" src="" class="w-full h-full border-0" allow="camera; microphone; xr-spatial-tracking; fullscreen"></iframe>
            </div>
        </div>
    </div>

    <script>
        function openArLabModal(labPath, title) {
            const modal = document.getElementById('ar-lab-modal');
            const iframe = document.getElementById('ar-modal-iframe');
            const titleEl = document.getElementById('ar-modal-title');
            const fsBtn = document.getElementById('ar-modal-fullscreen-btn');
            if (!modal || !iframe) return;

            const fullUrl = 'simulators/' + labPath + '/index.html';
            iframe.src = fullUrl;
            if (titleEl) titleEl.innerText = title || '3D AR Virtual Lab';
            if (fsBtn) fsBtn.href = fullUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeArLabModal() {
            const modal = document.getElementById('ar-lab-modal');
            const iframe = document.getElementById('ar-modal-iframe');
            if (!modal) return;
            if (iframe) iframe.src = 'about:blank';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    </script>


</body>
</html>
