<?php
/**
 * Page Content CMS Manager Component
 * Provides interactive editing for site titles, coordinator info, dates, venue, 7 modules, 4 tracks, and announcements.
 * Directly updates data/site_content.json which powers both index.php and the RAG mascot bot.
 */

$site_content_file = __DIR__ . '/../../data/site_content.json';
$site_data = file_exists($site_content_file) ? json_decode(file_get_contents($site_content_file), true) : [];
?>

<!-- ========================================================================= -->
<!-- SECTION: PAGE CONTENT CMS MANAGER -->
<!-- ========================================================================= -->
<section id="pageContentSection" class="scroll-mt-24 p-6 lg:p-8 rounded-3xl bg-[#0e1424]/90 border border-white/10 backdrop-blur-xl relative overflow-hidden shadow-2xl">
    
    <!-- Ambient Gradient Highlights -->
    <div class="absolute -top-24 -left-24 w-80 h-80 bg-brand/10 blur-3xl pointer-events-none rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-tech/10 blur-3xl pointer-events-none rounded-full"></div>

    <div class="relative z-10 space-y-6">
        
        <!-- Header & Action Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-5">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-bold border border-brand/20 mb-2 font-tech">
                    <i class="fas fa-sliders"></i> LIVE PAGE CMS &amp; METADATA
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                    <i class="fas fa-file-pen text-brand"></i> จัดการเนื้อหาหน้าเว็บและข้อมูลโครงการ
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    ปรับแก้ข้อมูลหัวข้อโครงการ ผู้ประสานงาน วันเวลา สถานที่ 7 โมดูล และ 4 แทร็กโครงงาน (อัปเดตหน้าแรกและ RAG Mascot Bot ทันที)
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="loadSiteContentFromBackend()" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-bold transition border border-white/10 flex items-center gap-2">
                    <i class="fas fa-rotate"></i> โหลดข้อมูลล่าสุด
                </button>
                <button type="button" onclick="saveSiteContentToBackend()" id="btnSaveSiteContent" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white text-xs font-bold transition shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>

        <!-- CMS Sub-navigation Tabs -->
        <div class="flex flex-wrap gap-2 border-b border-white/5 pb-3">
            <button type="button" onclick="switchCmsTab('cmsTabGeneral')" id="tabBtn_cmsTabGeneral" class="cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-brand/20 text-brand-light border border-brand/30">
                <i class="fas fa-circle-info mr-1.5"></i> 1. ข้อมูลทั่วไป &amp; ติดต่อ
            </button>
            <button type="button" onclick="switchCmsTab('cmsTabModules')" id="tabBtn_cmsTabModules" class="cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5">
                <i class="fas fa-layer-group mr-1.5"></i> 2. หลักสูตร 7 โมดูล
            </button>
            <button type="button" onclick="switchCmsTab('cmsTabTracks')" id="tabBtn_cmsTabTracks" class="cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5">
                <i class="fas fa-diagram-project mr-1.5"></i> 3. โครงงาน 4 แทร็ก
            </button>
            <button type="button" onclick="switchCmsTab('cmsTabBanner')" id="tabBtn_cmsTabBanner" class="cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5">
                <i class="fas fa-bullhorn mr-1.5"></i> 4. แถบประกาศหัวเว็บ
            </button>
        </div>

        <!-- TAB 1: General Info & Contact -->
        <div id="cmsTabGeneral" class="cms-tab-content space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Project Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">ชื่อย่อโครงการ (Brand / Short Title)</label>
                    <input type="text" id="cms_project_title" value="<?php echo htmlspecialchars($site_data['project_title'] ?? 'Edge xAI : 4DAgriEnvi'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Subtitle -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">คำโปรย / หัวข้อย่อย (Subtitle)</label>
                    <input type="text" id="cms_project_subtitle" value="<?php echo htmlspecialchars($site_data['project_subtitle'] ?? 'ปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอทีเพื่อการเกษตรและสิ่งแวดล้อม 4 มิติ'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Full Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-2">ชื่อโครงการเต็ม (Full Academic Title)</label>
                    <input type="text" id="cms_full_title" value="<?php echo htmlspecialchars($site_data['full_title'] ?? 'โครงการฝึกอบรมเชิงปฏิบัติการปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอทีเพื่อการเกษตรและสิ่งแวดล้อม 4 มิติ (Edge xAI : 4DAgriEnvi)'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Institution -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-2">หน่วยงานผู้จัด (Institution)</label>
                    <input type="text" id="cms_institution" value="<?php echo htmlspecialchars($site_data['institution'] ?? 'คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Coordinator Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">อาจารย์ผู้รับผิดชอบโครงการ (Coordinator Name)</label>
                    <input type="text" id="cms_coordinator_name" value="<?php echo htmlspecialchars($site_data['coordinator_name'] ?? 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Coordinator Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">ตำแหน่ง (Coordinator Role)</label>
                    <input type="text" id="cms_coordinator_title" value="<?php echo htmlspecialchars($site_data['coordinator_title'] ?? 'หัวหน้าโครงการและอาจารย์ผู้รับผิดชอบหลัก'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">เบอร์โทรศัพท์ติดต่อ (Phone)</label>
                    <input type="text" id="cms_coordinator_phone" value="<?php echo htmlspecialchars($site_data['coordinator_phone'] ?? '093-742-2654'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">อีเมลติดต่อ (Email)</label>
                    <input type="email" id="cms_coordinator_email" value="<?php echo htmlspecialchars($site_data['coordinator_email'] ?? 'chewa.t@rbru.ac.th'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Workshop Dates -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">กำหนดการอบรม (Dates)</label>
                    <input type="text" id="cms_workshop_dates" value="<?php echo htmlspecialchars($site_data['workshop_dates'] ?? '17 - 19 ธันวาคม 2569'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">ระยะเวลา (Duration)</label>
                    <input type="text" id="cms_workshop_duration" value="<?php echo htmlspecialchars($site_data['workshop_duration'] ?? '3 วัน (รวม 18 ชั่วโมง)'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Venue -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-2">สถานที่จัดอบรม (Venue)</label>
                    <input type="text" id="cms_workshop_venue" value="<?php echo htmlspecialchars($site_data['workshop_venue'] ?? 'ห้องปฏิบัติการคอมพิวเตอร์และนวัตกรรม อาคาร 9 / อาคาร 35 คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Target Participants -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">จำนวนรับสมัครเป้าหมาย (คน)</label>
                    <input type="number" id="cms_target_participants" value="<?php echo htmlspecialchars($site_data['target_participants'] ?? 40); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- Fee Note -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">หมายเหตุค่าลงทะเบียน (Fee Note)</label>
                    <input type="text" id="cms_fee_note" value="<?php echo htmlspecialchars($site_data['fee_note'] ?? 'ไม่มีค่าใช้จ่ายในการลงทะเบียน (เข้าร่วมฟรีตลอดหลักสูตร)'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- LINE OA ID -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">LINE Official Account ID</label>
                    <input type="text" id="cms_line_oa_id" value="<?php echo htmlspecialchars($site_data['line_oa_id'] ?? '@888qrcgj'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

                <!-- LINE OA Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">LINE Official Account Name</label>
                    <input type="text" id="cms_line_oa_name" value="<?php echo htmlspecialchars($site_data['line_oa_name'] ?? 'Edge-xAI'); ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-white/10 text-white text-sm focus:border-brand focus:outline-none">
                </div>

            </div>
        </div>

        <!-- TAB 2: 7 Learning Modules -->
        <div id="cmsTabModules" class="cms-tab-content space-y-4 hidden">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400">
                    กำหนดรายละเอียดเนื้อหาและชั่วโมงการเรียนรู้ของ 7 โมดูลปฏิบัติการ
                </p>
                <button type="button" onclick="addNewModuleRow()" class="px-3 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs font-bold border border-emerald-500/20 transition flex items-center gap-1.5">
                    <i class="fas fa-plus"></i> เพิ่มโมดูล
                </button>
            </div>

            <div id="modulesContainer" class="space-y-4">
                <!-- Dynamically populated or rendered from PHP -->
                <?php 
                $modules = $site_data['modules'] ?? [];
                foreach ($modules as $idx => $m): 
                ?>
                <div class="module-item p-4 rounded-2xl bg-white/[0.02] border border-white/10 relative space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-brand/20 text-brand text-xs font-bold flex items-center justify-center"><?php echo $idx + 1; ?></span>
                            <input type="text" class="module-code px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-brand font-mono font-bold w-24" value="<?php echo htmlspecialchars($m['code'] ?? ('MOD-0' . ($idx + 1))); ?>">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" class="module-hours px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-amber-400 font-bold w-24 text-right" placeholder="เช่น 2.5 ชม." value="<?php echo htmlspecialchars($m['hours'] ?? '2.5 ชม.'); ?>">
                            <button type="button" onclick="this.closest('.module-item').remove()" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <input type="text" class="module-name w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs font-bold text-white mb-2" placeholder="ชื่อโมดูลการเรียนรู้" value="<?php echo htmlspecialchars($m['name'] ?? ''); ?>">
                        <textarea class="module-desc w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs text-slate-300 resize-y" rows="2" placeholder="คำอธิบายรายละเอียดหัวข้อย่อย"><?php echo htmlspecialchars($m['desc'] ?? ''); ?></textarea>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TAB 3: 4 Capstone Tracks -->
        <div id="cmsTabTracks" class="cms-tab-content space-y-4 hidden">
            <p class="text-xs text-slate-400">
                รายละเอียดหัวข้อโครงงานต้นแบบ (Capstone Projects) 4 ทิศทางสำหรับผู้เข้าร่วมอบรม
            </p>

            <div id="tracksContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php 
                $tracks = $site_data['tracks'] ?? [];
                foreach ($tracks as $tidx => $tr): 
                ?>
                <div class="track-item p-4 rounded-2xl bg-white/[0.02] border border-white/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <input type="text" class="track-code px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-cyan-400 font-mono font-bold w-24" value="<?php echo htmlspecialchars($tr['code'] ?? ('Track ' . chr(65 + $tidx))); ?>">
                        <input type="text" class="track-badge px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-emerald-400 font-bold w-32 text-right" placeholder="ป้ายกำกับ" value="<?php echo htmlspecialchars($tr['badge'] ?? ''); ?>">
                    </div>
                    <div>
                        <input type="text" class="track-name w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs font-bold text-white mb-2" placeholder="ชื่อแทร็กโครงงาน" value="<?php echo htmlspecialchars($tr['name'] ?? ''); ?>">
                        <textarea class="track-desc w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs text-slate-300 resize-y mb-2" rows="2" placeholder="คำอธิบายโครงงาน"><?php echo htmlspecialchars($tr['desc'] ?? ''); ?></textarea>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-500 font-bold uppercase">FontAwesome Icon:</span>
                            <input type="text" class="track-icon px-3 py-1 rounded-lg bg-slate-900 border border-white/10 text-xs text-purple-300 font-mono flex-1" value="<?php echo htmlspecialchars($tr['icon'] ?? 'fa-solid fa-microchip'); ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TAB 4: Hero Alert & Ticker Announcement -->
        <div id="cmsTabBanner" class="cms-tab-content space-y-5 hidden">
            <div class="p-5 rounded-2xl bg-amber-500/5 border border-amber-500/20 space-y-4">
                <div class="flex items-center gap-2 text-amber-400 text-xs font-bold">
                    <i class="fas fa-bullhorn"></i> ข้อความแถบประกาศหัวเว็บไซต์ (Top Notification Ticker)
                </div>
                <p class="text-xs text-slate-400">
                    ข้อความนี้จะปรากฏเป็นแถบวิ่งไฮไลต์ด้านบนสุดของหน้าเว็บ เพื่อแจ้งกำหนดการหรือข้อความสำคัญแก่ผู้เข้าชม
                </p>
                <div>
                    <input type="text" id="cms_hero_alert_text" value="<?php echo htmlspecialchars($site_data['hero_alert_text'] ?? 'เปิดรับสมัครเข้าร่วมโครงการฟรี 40 ท่าน • วุฒิบัตรรับรองสมรรถนะมาตรฐาน มรภ.รำไพพรรณี'); ?>" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/15 text-white text-sm font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>
        </div>

    </div>
</section>
