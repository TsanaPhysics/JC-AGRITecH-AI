    <!-- Wi-Fi IP Configuration Modal -->
    <div id="wifiModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-phone-card rounded-3xl p-6 border border-white/10 w-full max-w-sm space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wifi text-cyan-400"></i>
                    <h3 class="font-bold text-sm text-white font-tech">ตั้งค่าการเชื่อมต่อ Wi-Fi บอร์ด</h3>
                </div>
                <button onclick="closeWifiModal()" class="text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-gray-400 mb-1 font-tech">ESP32-S3 ATD3.5 IP Address</label>
                    <input type="text" id="inputBoardIp" value="192.168.0.111" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                    <span class="text-[10px] text-gray-500 mt-1 block">เช่น 192.168.4.1 (AP Mode) หรือ IP ในวง Wi-Fi บ้าน</span>
                </div>

                <div>
                    <label class="block text-gray-400 mb-1 font-tech">Polling Interval</label>
                    <select class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white font-mono focus:border-cyan-400 focus:outline-none">
                        <option value="3">3 วินาที (Fast)</option>
                        <option value="5" selected>5 วินาที (Optimal)</option>
                        <option value="10">10 วินาที (Eco)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button onclick="saveWifiConfig()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs font-tech transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> บันทึกและเชื่อมต่อ
                </button>
                <button onclick="closeWifiModal()" class="px-4 py-2.5 rounded-xl bg-white/10 text-gray-300 font-bold text-xs font-tech hover:bg-white/20 transition">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

