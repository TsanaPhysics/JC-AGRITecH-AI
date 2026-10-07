<!-- ========================================================================= -->
<!-- MODAL: ADD / EDIT ESP32 FLEET BOARD                                       -->
<!-- ========================================================================= -->
<div id="modalBoardForm" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel rounded-3xl p-6 max-w-lg w-full border border-cyan-500/40 shadow-2xl space-y-5 bg-[#090e1c] widget-enter">
        
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-500 to-indigo-600 text-white flex items-center justify-center font-bold">
                    <i class="fa-solid fa-microchip text-lg"></i>
                </div>
                <div>
                    <h3 id="boardModalTitle" class="text-lg font-bold font-tech text-white">เพิ่มบอร์ด ESP32 ใหม่ในเครือข่าย</h3>
                    <p class="text-xs text-slate-400">ลงทะเบียนบอร์ดไมโครคอนโทรลเลอร์เพื่อมอนิเตอร์และสั่งการ</p>
                </div>
            </div>
            <button onclick="closeBoardModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formBoardAdd" onsubmit="handleBoardSubmit(event)" class="space-y-4 text-xs font-tech">
            <input type="hidden" id="boardFormId" value="">

            <div>
                <label class="block text-slate-300 font-bold mb-1">ชื่อบอร์ด / โหนดแปลงเกษตร *</label>
                <input type="text" id="boardFormName" required placeholder="เช่น แปลงทุเรียนโซนเหนือ 4" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-hidden focus:border-cyan-500 font-sans">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-bold mb-1">รหัสบอร์ด (Code) *</label>
                    <input type="text" id="boardFormCode" required placeholder="ESP32-NODE-04" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-cyan-300 font-mono focus:outline-hidden focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-bold mb-1">โซน / พื้นที่ติดตั้ง *</label>
                    <input type="text" id="boardFormZone" required placeholder="Zone D - แปลงทดลอง" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-hidden focus:border-cyan-500 font-sans">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                    <label class="block text-slate-300 font-bold mb-1">IP Address *</label>
                    <input type="text" id="boardFormIp" required placeholder="192.168.0.114" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-mono focus:outline-hidden focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-bold mb-1">Port</label>
                    <input type="number" id="boardFormPort" value="8500" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-mono focus:outline-hidden focus:border-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-bold mb-1">รุ่นฮาร์ดแวร์ (Hardware Model)</label>
                    <select id="boardFormModel" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-hidden focus:border-cyan-500">
                        <option value="ESP32-S3 ATD3.5 Pro">ESP32-S3 ATD3.5 Pro</option>
                        <option value="ESP32-S3 Dual-Core">ESP32-S3 Dual-Core</option>
                        <option value="ESP32-WROOM-32">ESP32-WROOM-32</option>
                        <option value="ESP32-CAM AI Vision">ESP32-CAM AI Vision</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-bold mb-1">MAC Address (Optional)</label>
                    <input type="text" id="boardFormMac" placeholder="48:27:E2:B4:8A:XX" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-slate-300 font-mono focus:outline-hidden focus:border-cyan-500">
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/10">
                <button type="button" onclick="closeBoardModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">
                    ยกเลิก
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white font-bold transition shadow-md flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> บันทึกข้อมูลบอร์ด
                </button>
            </div>
        </form>

    </div>
</div>
