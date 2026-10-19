<?php
/**
 * Database CRUD Manager Component
 * Provides complete Create, Read, Update, and Delete operations for all SQLite tables:
 * - participants
 * - announcements
 * - evaluations
 * - telemetry_logs
 * - relay_states
 */
?>

<!-- ========================================================================= -->
<!-- SECTION: DATABASE CRUD MANAGER -->
<!-- ========================================================================= -->
<section id="databaseManagerSection" class="scroll-mt-24 p-6 lg:p-8 rounded-3xl bg-[#0e1424]/90 border border-white/10 backdrop-blur-xl relative overflow-hidden shadow-2xl">
    
    <!-- Ambient Backdrop Highlights -->
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-500/10 blur-3xl pointer-events-none rounded-full"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-emerald-500/10 blur-3xl pointer-events-none rounded-full"></div>

    <div class="relative z-10 space-y-6">
        
        <!-- Header Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-5">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20 mb-2 font-tech">
                    <i class="fas fa-database"></i> SQLITE3 DYNAMIC CRUD ENGINE
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <i class="fas fa-table-cells text-blue-400"></i> จัดการฐานข้อมูล (Database CRUD Engine)
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    เพิ่ม แก้ไข ปรับปรุง และลบข้อมูลระเบียนในทุกตารางของระบบแบบ Real-time พร้อมระบบซิงก์ไฟล์ JSON อัตโนมัติ
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="toggleSqlPlayground()" class="px-3.5 py-2 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 hover:text-white text-xs font-bold transition border border-purple-500/30 flex items-center gap-2">
                    <i class="fas fa-terminal"></i> SQL Console
                </button>
                <button type="button" onclick="refreshCurrentTable()" class="px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-bold transition border border-white/10 flex items-center gap-2">
                    <i class="fas fa-rotate"></i> รีเฟรชตาราง
                </button>
                <button type="button" onclick="openAddRowModal()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-blue-500/20 flex items-center gap-2">
                    <i class="fas fa-plus"></i> เพิ่มระเบียนใหม่
                </button>
            </div>
        </div>

        <!-- Table Selector Tabs -->
        <div class="flex flex-wrap gap-2 border-b border-white/5 pb-3" id="dbTableTabs">
            <button type="button" onclick="selectDbTable('participants')" id="dbTab_participants" class="db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-blue-500/20 text-blue-300 border border-blue-500/30 flex items-center gap-2">
                <i class="fas fa-users"></i> participants
                <span class="table-count-badge text-[10px] px-1.5 py-0.5 rounded-full bg-blue-500/30 font-mono" id="badge_count_participants">...</span>
            </button>
            <button type="button" onclick="selectDbTable('announcements')" id="dbTab_announcements" class="db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5 flex items-center gap-2">
                <i class="fas fa-bullhorn"></i> announcements
                <span class="table-count-badge text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 font-mono" id="badge_count_announcements">...</span>
            </button>
            <button type="button" onclick="selectDbTable('evaluations')" id="dbTab_evaluations" class="db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5 flex items-center gap-2">
                <i class="fas fa-star"></i> evaluations
                <span class="table-count-badge text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 font-mono" id="badge_count_evaluations">...</span>
            </button>
            <button type="button" onclick="selectDbTable('telemetry_logs')" id="dbTab_telemetry_logs" class="db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5 flex items-center gap-2">
                <i class="fas fa-wave-square"></i> telemetry_logs
                <span class="table-count-badge text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 font-mono" id="badge_count_telemetry_logs">...</span>
            </button>
            <button type="button" onclick="selectDbTable('relay_states')" id="dbTab_relay_states" class="db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5 flex items-center gap-2">
                <i class="fas fa-toggle-on"></i> relay_states
                <span class="table-count-badge text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 font-mono" id="badge_count_relay_states">...</span>
            </button>
        </div>

        <!-- Optional Collapsible SQL Playground Console -->
        <div id="sqlPlaygroundConsole" class="p-4 rounded-2xl bg-black/50 border border-purple-500/20 space-y-3 hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-bold text-purple-300 flex items-center gap-2">
                    <i class="fas fa-terminal"></i> SQLite3 Interactive Query Console
                </span>
                <span class="text-[10px] text-slate-500">พิมพ์คำสั่ง SQL เช่น: SELECT * FROM participants LIMIT 10</span>
            </div>
            <div class="flex gap-2">
                <input type="text" id="sqlCustomQueryInput" placeholder="SELECT * FROM participants ORDER BY id DESC LIMIT 10" class="flex-1 px-4 py-2 rounded-xl bg-slate-950 border border-purple-500/30 text-white font-mono text-xs focus:outline-none focus:border-purple-400">
                <button type="button" onclick="runCustomSqlQuery()" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-play"></i> รันคำสั่ง
                </button>
            </div>
            <div id="sqlQueryResultBox" class="max-h-48 overflow-auto text-xs font-mono p-3 rounded-xl bg-slate-950 border border-white/5 text-slate-300 hidden"></div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white/[0.02] p-3 rounded-2xl border border-white/5">
            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" id="dbSearchInput" onkeyup="handleDbSearch(event)" placeholder="ค้นหาข้อมูลในตารางนี้..." class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-blue-400 focus:outline-none">
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <span class="text-xs text-slate-400 font-mono" id="dbTableStatusText">กำลังโหลด...</span>
                <select id="dbLimitSelect" onchange="changeDbLimit(this.value)" class="px-3 py-1.5 rounded-xl bg-slate-900 border border-white/10 text-xs text-slate-300 focus:outline-none">
                    <option value="25">แสดง 25 แถว</option>
                    <option value="50" selected>แสดง 50 แถว</option>
                    <option value="100">แสดง 100 แถว</option>
                    <option value="200">แสดง 200 แถว</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Responsive Table Box -->
        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/60 max-h-[560px]">
            <table class="w-full text-left border-collapse" id="dbMainTable">
                <thead class="sticky top-0 bg-[#0c1220] text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-white/10 z-10" id="dbTableHead">
                    <tr>
                        <th class="p-3 text-center w-16">การจัดการ</th>
                        <th class="p-3">ID</th>
                        <th class="p-3">กำลังโหลดหัวตาราง...</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-xs text-slate-300" id="dbTableBody">
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i> กำลังโหลดข้อมูลจาก SQLite3...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center justify-between text-xs text-slate-400 pt-2 border-t border-white/5">
            <div id="dbPaginationInfo">แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ</div>
            <div class="flex items-center gap-2">
                <button type="button" id="btnDbPrevPage" onclick="navigateDbPage(-1)" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 disabled:opacity-30 disabled:pointer-events-none transition">
                    <i class="fas fa-chevron-left mr-1"></i> ก่อนหน้า
                </button>
                <span class="px-2 font-mono font-bold text-white" id="dbCurrentPageText">1</span>
                <button type="button" id="btnDbNextPage" onclick="navigateDbPage(1)" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 disabled:opacity-30 disabled:pointer-events-none transition">
                    ถัดไป <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>

    </div>
</section>

<!-- ========================================================================= -->
<!-- DYNAMIC MODAL: ADD / EDIT TABLE ROW -->
<!-- ========================================================================= -->
<div id="dbRowModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md hidden">
    <div class="w-full max-w-2xl bg-[#0c1220] border border-white/15 rounded-3xl p-6 lg:p-8 shadow-2xl relative max-h-[90vh] flex flex-col">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
            <div>
                <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30" id="modalTableBadge">TABLE</span>
                <h3 class="text-lg font-black text-white mt-1" id="modalRowTitle">แก้ไขข้อมูลระเบียน</h3>
            </div>
            <button type="button" onclick="closeDbRowModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Dynamic Form Fields Container -->
        <form id="dbRowForm" onsubmit="submitDbRowForm(event)" class="overflow-y-auto flex-1 pr-2 space-y-4">
            <div id="dbModalFormFields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dynamically rendered based on active table's columns -->
            </div>

            <!-- Modal Footer Actions -->
            <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3 mt-4">
                <button type="button" onclick="closeDbRowModal()" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold transition">
                    ยกเลิก
                </button>
                <button type="submit" id="btnSaveDbRow" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-blue-500/20 flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i> บันทึกข้อมูล
                </button>
            </div>
        </form>

    </div>
</div>
