<?php
/**
 * Cloud Integrations Panel: LINE Messaging API & Google Sheets Real-time Sync
 * Edge xAI : 4DAgriEnvi Control Center
 */
?>
<!-- CLOUD INTEGRATIONS SECTION (LINE OA & GOOGLE SHEETS) -->
<div id="cloudIntegrationsSection" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- 1. LINE Official Account Messaging API Card -->
    <div class="bg-[#0e1424] p-6 lg:p-7 rounded-3xl border border-white/5 space-y-6 shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-44 h-44 bg-emerald-500/10 blur-3xl rounded-full pointer-events-none"></div>
        
        <div class="flex items-center justify-between pb-4 border-b border-white/5 relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-lg shadow-lg shadow-emerald-500/10">
                    <i class="fab fa-line"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base lg:text-lg text-white">LINE Messaging API</h3>
                    <p class="text-xs text-slate-400">แจ้งเตือนผู้ลงทะเบียนใหม่เรียลไทม์</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold <?php echo !empty($line_config['enabled']) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'; ?>">
                <span class="w-2 h-2 rounded-full <?php echo !empty($line_config['enabled']) ? 'bg-emerald-400 animate-pulse' : 'bg-red-400'; ?>"></span>
                <?php echo !empty($line_config['enabled']) ? 'เชื่อมต่อแล้ว (Active)' : 'ปิดการทำงาน'; ?>
            </span>
        </div>

        <div class="space-y-3 text-xs relative z-10">
            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">LINE OA Name / Basic ID</span>
                <span class="font-mono text-emerald-400 font-bold">Edge-xAI (@888qrcgj)</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">Channel ID</span>
                <span class="font-mono text-white font-bold">2012097925</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">สถานะการยิงข้อความล่าสุด</span>
                <span class="font-mono text-cyan-300 font-bold">
                    <?php echo htmlspecialchars($line_config['last_status'] ?? 'พร้อมใช้งาน'); ?>
                </span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">เวลายิงล่าสุด</span>
                <span class="font-mono text-slate-300">
                    <?php echo !empty($line_config['last_sent']) ? htmlspecialchars($line_config['last_sent']) . ' น.' : 'ยังไม่มีการส่ง'; ?>
                </span>
            </div>
        </div>

        <div class="pt-2 flex flex-wrap gap-3 relative z-10">
            <button onclick="triggerLineTestNotify(this)" class="flex-1 min-w-[160px] px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs flex items-center justify-center gap-2 transition shadow-lg shadow-emerald-500/20 active:scale-95">
                <i class="fas fa-paper-plane"></i> ยิงทดสอบแจ้งเตือน LINE
            </button>
            <a href="https://manager.line.biz/account/@888qrcgj/setting/messaging-api" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-200 border border-white/10 text-xs font-bold flex items-center gap-2 transition">
                <i class="fas fa-gear text-slate-400"></i> LINE OA Manager
            </a>
        </div>
    </div>

    <!-- 2. Google Sheets Cloud Database Card -->
    <div class="bg-[#0e1424] p-6 lg:p-7 rounded-3xl border border-white/5 space-y-6 shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-44 h-44 bg-cyan-500/10 blur-3xl rounded-full pointer-events-none"></div>

        <div class="flex items-center justify-between pb-4 border-b border-white/5 relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-lg shadow-lg shadow-cyan-500/10">
                    <i class="fas fa-file-spreadsheet"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base lg:text-lg text-white">Google Sheets Cloud Sync</h3>
                    <p class="text-xs text-slate-400">คลาวด์สเปรดชีตตารางข้อมูลสด 24 ชม.</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                เชื่อมโยงแล้ว
            </span>
        </div>

        <div class="space-y-3 text-xs relative z-10">
            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex flex-col gap-1">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">Live Google Spreadsheet</span>
                <a href="<?php echo htmlspecialchars($gs_config['sheet_url']); ?>" target="_blank" class="font-mono text-cyan-300 font-bold hover:underline truncate flex items-center gap-1.5">
                    <i class="fas fa-external-link-alt text-[10px]"></i> เปิดดูสเปรดชีตของผู้เข้าร่วมอบรม
                </a>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">Google Apps Script</span>
                <span class="font-mono text-slate-300 font-bold">/google_sheets_script/Code.gs</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">โหมดการซิงก์อัตโนมัติ</span>
                <span class="font-mono text-emerald-400 font-bold flex items-center gap-1">
                    <i class="fas fa-bolt text-amber-400"></i> Auto-Sync เมื่อมีคนลงทะเบียน
                </span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/40 border border-white/5 flex items-center justify-between">
                <span class="text-slate-400 font-bold uppercase tracking-wider font-tech">สถานะสเปรดชีต</span>
                <span class="font-mono text-slate-300 font-bold">
                    พร้อมรับข้อมูล (Gid: 838789992)
                </span>
            </div>
        </div>

        <div class="pt-2 flex flex-wrap gap-3 relative z-10">
            <a href="<?php echo htmlspecialchars($gs_config['sheet_url']); ?>" target="_blank" class="flex-1 min-w-[160px] px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-slate-950 font-bold text-xs flex items-center justify-center gap-2 transition shadow-lg shadow-cyan-500/20 active:scale-95">
                <i class="fas fa-table"></i> เปิด Google Sheet แผ่นงานสด
            </a>
            <button onclick="syncAllToGoogleSheets(this)" class="px-4 py-2.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-200 border border-white/10 text-xs font-bold flex items-center gap-2 transition">
                <i class="fas fa-rotate text-cyan-400"></i> ซิงก์รายชื่อทั้งหมด
            </button>
        </div>
    </div>

</div>
