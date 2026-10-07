<!-- Top Navigation Bar & Fleet Header -->
<header class="sticky top-0 z-40 bg-[#060a14]/90 backdrop-blur-xl border-b border-white/10 px-4 lg:px-8 py-3.5 transition-all">
    <div class="max-w-[1720px] mx-auto flex flex-col md:flex-row items-center justify-between gap-3">
        
        <!-- Left: Brand & Fleet Status Pill -->
        <div class="flex items-center gap-3.5 w-full md:w-auto justify-between md:justify-start">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-cyan-500/20">
                    <i class="fa-solid fa-microchip-nodes text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base sm:text-lg font-bold font-tech tracking-wide text-white">AIoT Multi-Board Fleet Hub</h1>
                        <span class="text-[9px] font-mono uppercase bg-cyan-950 text-cyan-300 px-2 py-0.5 rounded-full border border-cyan-500/40">ESP32 Fleet</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-tech flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 pulse-online"></span>
                        <span id="navFleetStatusText">เชื่อมต่อ 16/16 บอร์ดออนไลน์ (ห้องอบรม 15 กลุ่ม + 1 สาธิตหลัก)</span>
                    </p>
                </div>
            </div>

            <!-- Mobile quick action -->
            <button onclick="openBoardModal()" class="md:hidden px-3 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold font-tech flex items-center gap-1.5 shadow-md">
                <i class="fa-solid fa-plus"></i> เพิ่มบอร์ด
            </button>
        </div>

        <!-- Center: View Mode Segmented Controls -->
        <div class="flex items-center bg-slate-900/90 p-1 rounded-2xl border border-white/10 text-xs font-tech shadow-inner w-full md:w-auto justify-center">
            <button id="btnViewFocus" onclick="switchViewMode('focus')" class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-xs">
                <i class="fa-solid fa-bullseye text-cyan-400"></i> โฟกัสบอร์ดเดียว (Focus)
            </button>
            <button id="btnViewFleet" onclick="switchViewMode('fleet_grid')" class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-white">
                <i class="fa-solid fa-table-cells text-slate-400"></i> ภาพรวมทุกบอร์ด (Grid)
            </button>
            <button id="btnViewCompare" onclick="switchViewMode('comparative')" class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-white">
                <i class="fa-solid fa-code-compare text-slate-400"></i> เปรียบเทียบ (Compare)
            </button>
        </div>

        <!-- Right: Action Buttons & Controls -->
        <div class="flex items-center gap-2 w-full md:w-auto justify-end flex-wrap">
            
            <!-- Quick Link to Main Workshop Dashboard -->
            <a href="../leqs-workshop/dashboard/index.php" target="_blank" class="px-3 py-2 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/40 text-xs font-bold font-tech flex items-center gap-1.5 transition shadow-xs" title="เปิดหน้าแดชบอร์ดหลักของวิทยากร (/leqs-workshop/dashboard/)">
                <i class="fa-solid fa-crown text-amber-400 text-xs"></i> แดชบอร์ดสาธิต
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-amber-400/80"></i>
            </a>

            <!-- Download Center Portal Link -->
            <a href="../app-portal/index.php" target="_blank" class="px-3 py-2 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/40 text-xs font-bold font-tech flex items-center gap-1.5 transition shadow-xs" title="ศูนย์ดาวน์โหลดแอปพลิเคชัน & ไฟล์ APK สำหรับผู้เข้าร่วมอบรม">
                <i class="fa-solid fa-cloud-arrow-down text-emerald-400 text-xs"></i> ดาวน์โหลดแอป (APK)
            </a>

            <!-- Admin Fleet & Sensor Management Hub Button -->
            <button onclick="openAdminFleetModal()" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-500/20 to-orange-500/20 hover:from-amber-500/30 hover:to-orange-500/30 text-amber-300 border border-amber-500/50 text-xs font-bold font-tech flex items-center gap-1.5 transition shadow-xs" title="ระบบหลังบ้าน: ปรับแก้ข้อมูลกลุ่มและเลือกเปิด/ปิดเซนเซอร์ที่ใช้ในแต่ละกลุ่ม">
                <i class="fa-solid fa-screwdriver-wrench text-amber-400"></i> จัดการหลังบ้าน
            </button>

            <!-- Add Board Button -->
            <button onclick="openBoardModal()" class="hidden md:flex px-3.5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white text-xs font-bold font-tech items-center gap-1.5 shadow-md shadow-cyan-900/30 transition transform hover:scale-[1.02]">
                <i class="fa-solid fa-circle-plus"></i> เพิ่มบอร์ด ESP32
            </button>

            <!-- Customize Sensors & Widgets Button -->
            <button onclick="openWidgetCatalogModal()" class="px-3.5 py-2 rounded-xl bg-slate-800/90 hover:bg-slate-700/90 text-slate-200 border border-white/10 text-xs font-bold font-tech flex items-center gap-1.5 transition">
                <i class="fa-solid fa-sliders text-cyan-400"></i> เลือกเซนเซอร์ / หน้าจอ
            </button>

            <!-- Broadcast Actuator Button -->
            <button onclick="openBroadcastModal()" class="px-3 py-2 rounded-xl bg-purple-950/80 hover:bg-purple-900/90 text-purple-200 border border-purple-500/40 text-xs font-bold font-tech flex items-center gap-1.5 transition" title="สั่งการรีเลย์พร้อมกันทุกบอร์ด">
                <i class="fa-solid fa-bolt text-purple-400"></i> สั่งการรวม
            </button>

            <!-- Refresh Interval Badge -->
            <button onclick="refreshFleetNow()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition" title="รีเฟรชข้อมูลทันที">
                <i id="btnRefreshIcon" class="fa-solid fa-rotate-right"></i>
            </button>
        </div>

    </div>
</header>
