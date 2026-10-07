<!-- ========================================================================= -->
<!-- MODAL: BROADCAST ACTUATOR CONTROL ACROSS ALL BOARDS                       -->
<!-- ========================================================================= -->
<div id="modalBroadcast" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel rounded-3xl p-6 max-w-md w-full border border-purple-500/40 shadow-2xl space-y-5 bg-[#090e1c] widget-enter">
        
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-tower-broadcast text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold font-tech text-white">สั่งการรวมทุกบอร์ด (Broadcast)</h3>
                    <p class="text-xs text-slate-400">ส่งคำสั่งควบคุมอุปกรณ์ภาคสนามพร้อมกันทั้งเครือข่าย</p>
                </div>
            </div>
            <button onclick="closeBroadcastModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="space-y-3.5 text-xs font-tech">
            <p class="text-slate-300">
                เลือกช่องสัญญาณรีเลย์ที่ต้องการสั่งการไปยังบอร์ด ESP32 ทุกตัวในระบบพร้อมกัน:
            </p>

            <div class="space-y-2">
                <!-- Channel 1: Main Pump -->
                <div class="p-3 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-white block">CH-01: ปั๊มน้ำหลัก (Main Pumps)</span>
                        <span class="text-[10px] text-slate-400">สั่งเปิด/ปิดปั๊มน้ำทุกแปลงพร้อมกัน</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <button onclick="sendBroadcastRelay(1, 1)" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition">ON</button>
                        <button onclick="sendBroadcastRelay(1, 0)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">OFF</button>
                    </div>
                </div>

                <!-- Channel 2: Solenoid -->
                <div class="p-3 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-white block">CH-02: วาล์วโซลินอยด์ (Solenoid)</span>
                        <span class="text-[10px] text-slate-400">สั่งเปิด/ปิดวาล์วน้ำโซลินอยด์ทุกแปลง</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <button onclick="sendBroadcastRelay(2, 1)" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition">ON</button>
                        <button onclick="sendBroadcastRelay(2, 0)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">OFF</button>
                    </div>
                </div>

                <!-- Channel 3: Surface Valve -->
                <div class="p-3 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-white block">CH-03: วาล์วน้ำผิวดิน (Surface Valve)</span>
                        <span class="text-[10px] text-slate-400">สั่งเปิด/ปิดวาล์วผิวดินทุกแปลง</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <button onclick="sendBroadcastRelay(3, 1)" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition">ON</button>
                        <button onclick="sendBroadcastRelay(3, 0)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">OFF</button>
                    </div>
                </div>

                <!-- Channel 4: Misting -->
                <div class="p-3 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-white block">CH-04: ระบบพ่นหมอกลด VPD</span>
                        <span class="text-[10px] text-slate-400">สั่งเปิด/ปิดหัวพ่นหมอกทุกแปลง</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <button onclick="sendBroadcastRelay(4, 1)" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition">ON</button>
                        <button onclick="sendBroadcastRelay(4, 0)" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">OFF</button>
                    </div>
                </div>
            </div>

            <!-- Emergency All Off -->
            <button onclick="sendEmergencyAllOff()" class="w-full py-2.5 rounded-xl bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/40 font-bold flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-triangle-exclamation"></i> สั่งปิดอุปกรณ์ทั้งหมดทันที (Emergency All OFF)
            </button>
        </div>

    </div>
</div>
