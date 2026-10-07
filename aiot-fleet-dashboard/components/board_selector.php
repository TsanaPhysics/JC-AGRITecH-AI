<!-- ========================================================================= -->
<!-- COMPONENT: INTERACTIVE MULTI-BOARD SELECTOR MATRIX (15 WORKSHOP GROUPS)    -->
<!-- ========================================================================= -->
<section class="space-y-3.5">
    
    <!-- Top Filter & Activity Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-900/60 p-3.5 rounded-2xl border border-white/5">
        
        <!-- Left: Workshop Context & Stats -->
        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2 text-xs font-tech text-slate-200">
                <i class="fa-solid fa-chalkboard-user text-cyan-400 text-sm"></i>
                <span class="font-bold text-white">กลุ่มปฏิบัติการในห้องอบรม (Workshop Participant Nodes)</span>
                <span id="boardCountBadge" class="text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-cyan-950 text-cyan-300 border border-cyan-500/40 font-bold">16 Nodes</span>
            </div>
            <span class="hidden lg:inline-block text-[11px] font-tech text-slate-400 border-l border-white/10 pl-3">
                15 กลุ่มปฏิบัติการ + 1 บอร์ดสาธิตหลักวิทยากร
            </span>
        </div>

        <!-- Right: Group Filter Segmented Tabs & Search -->
        <div class="flex items-center gap-2 flex-wrap">
            
            <!-- Quick Filter Buttons -->
            <div class="flex items-center bg-slate-950/90 p-1 rounded-xl border border-white/10 text-xs font-tech shadow-inner" id="workshopFilterGroup">
                <button type="button" onclick="filterWorkshopGroup('all')" id="btnFilterAll" class="px-2.5 py-1 rounded-lg font-bold transition bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-[11px]">
                    ทั้งหมด
                </button>
                <button type="button" onclick="filterWorkshopGroup('master')" id="btnFilterMaster" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-400 hover:text-amber-300 text-[11px]">
                    ⭐ สาธิตหลัก
                </button>
                <button type="button" onclick="filterWorkshopGroup('zoneA')" id="btnFilterZoneA" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-400 hover:text-white text-[11px]">
                    กลุ่ม 1-5 (ชุด A)
                </button>
                <button type="button" onclick="filterWorkshopGroup('zoneB')" id="btnFilterZoneB" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-400 hover:text-white text-[11px]">
                    กลุ่ม 6-10 (ชุด B)
                </button>
                <button type="button" onclick="filterWorkshopGroup('zoneC')" id="btnFilterZoneC" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-400 hover:text-white text-[11px]">
                    กลุ่ม 11-15 (ชุด C)
                </button>
            </div>

            <!-- Search Group Box -->
            <div class="relative">
                <input type="text" id="inputSearchGroup" oninput="searchWorkshopGroup(this.value)" placeholder="ค้นหากลุ่ม / โต๊ะ / รหัสบอร์ด..." class="bg-slate-950 text-slate-200 text-xs px-3 py-1.5 pl-8 rounded-xl border border-white/10 focus:outline-none focus:border-cyan-500 font-tech w-44 sm:w-56 placeholder:text-slate-600">
                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-500 text-xs pointer-events-none"></i>
            </div>

        </div>

    </div>

    <!-- Responsive Matrix of 16 Board Cards (1 Master + 15 Groups) -->
    <div id="boardPillsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-3.5">
        <!-- Rendered dynamically by fleet-ui.js -->
        <div class="p-4 rounded-2xl glass-panel animate-pulse flex items-center justify-center text-slate-500 text-xs">
            กำลังโหลดรายชื่อบอร์ดปฏิบัติการ 15 กลุ่ม...
        </div>
    </div>

</section>
