    <!-- ========================================================================= -->
    <!-- MODALS: PARTICIPANT ADD & EDIT -->
    <!-- ========================================================================= -->

    <!-- 1. ADD PARTICIPANT MODAL -->
    <div id="addParticipantModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#0e1424] border border-cyan-500/30 rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">เพิ่มผู้ลงทะเบียนใหม่</h3>
                        <p class="text-xs text-slate-400">บันทึกข้อมูลผู้เข้าร่วมอบรมโครงการ Edge xAI : 4DAgriEnvi</p>
                    </div>
                </div>
                <button onclick="closeAddParticipantModal()" class="text-slate-400 hover:text-white p-2 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addParticipantForm" onsubmit="submitAddParticipant(event)" class="space-y-4 text-xs sm:text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คำนำหน้า</label>
                        <select name="prefix" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                            <option value="อาจารย์">อาจารย์</option>
                            <option value="ดร.">ดร.</option>
                            <option value="ผศ.ดร.">ผศ.ดร.</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-300 font-semibold mb-1">ชื่อ - นามสกุล <span class="text-rose-400">*</span></label>
                        <input type="text" name="fullname" required placeholder="เช่น สมชาย ใจดี" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">กลุ่มเป้าหมาย</label>
                        <select name="target_group" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="นักเรียนมัธยมศึกษาตอนปลาย">นักเรียนมัธยมศึกษาตอนปลาย</option>
                            <option value="ครูและบุคลากรทางการศึกษา">ครูและบุคลากรทางการศึกษา</option>
                            <option value="เกษตรกรผู้เพาะปลูก">เกษตรกรผู้เพาะปลูก</option>
                            <option value="นักวิจัย/บุคคลทั่วไป">นักวิจัย/บุคคลทั่วไป</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">สังกัด / โรงเรียน / หน่วยงาน</label>
                        <input type="text" name="organization" placeholder="เช่น โรงเรียนประณีตวิทยาคม" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" placeholder="08x-xxx-xxxx" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">อีเมล</label>
                        <input type="email" name="email" placeholder="example@email.com" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">แทร็กโครงงาน Capstone ที่เลือก</label>
                    <select name="project_track" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-tech">
                        <option value="Track A — Smart Agriculture & Sensor Hub">Track A — Smart Agriculture & Sensor Hub</option>
                        <option value="Track B — Plant Vision & Leaf AI">Track B — Plant Vision & Leaf AI</option>
                        <option value="Track C — Smart Soil & NPK Analyzer">Track C — Smart Soil & NPK Analyzer</option>
                        <option value="Track D — Dissolved Oxygen AI Controller">Track D — Dissolved Oxygen AI Controller</option>
                        <option value="Track E — Automated Bio-Colony Counter">Track E — Automated Bio-Colony Counter</option>
                        <option value="Track F — School AIoT & Micro-Climate">Track F — School AIoT & Micro-Climate</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คะแนน Pre-test (0-20)</label>
                        <input type="number" min="0" max="20" name="pre_score" value="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คะแนน Post-test (0-20)</label>
                        <input type="number" min="0" max="20" name="post_score" value="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">สถานะ</label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="registered">ลงทะเบียน</option>
                            <option value="checked-in">เช็คอินแล้ว</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeAddParticipantModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">ยกเลิก</button>
                    <button type="submit" id="btnSubmitAddP" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 text-slate-950 font-black shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. EDIT PARTICIPANT MODAL -->
    <div id="editParticipantModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#0e1424] border border-cyan-500/40 rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">แก้ไขข้อมูลผู้ลงทะเบียน</h3>
                        <p class="text-xs text-slate-400" id="editParticipantSubtitle">รหัสผู้เข้าร่วม #</p>
                    </div>
                </div>
                <button onclick="closeEditParticipantModal()" class="text-slate-400 hover:text-white p-2 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editParticipantForm" onsubmit="submitEditParticipant(event)" class="space-y-4 text-xs sm:text-sm">
                <input type="hidden" name="id" id="edit_id">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คำนำหน้า</label>
                        <select name="prefix" id="edit_prefix" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                            <option value="อาจารย์">อาจารย์</option>
                            <option value="ดร.">ดร.</option>
                            <option value="ผศ.ดร.">ผศ.ดร.</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-300 font-semibold mb-1">ชื่อ - นามสกุล <span class="text-rose-400">*</span></label>
                        <input type="text" name="fullname" id="edit_fullname" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">กลุ่มเป้าหมาย</label>
                        <select name="target_group" id="edit_target_group" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="นักเรียนมัธยมศึกษาตอนปลาย">นักเรียนมัธยมศึกษาตอนปลาย</option>
                            <option value="ครูและบุคลากรทางการศึกษา">ครูและบุคลากรทางการศึกษา</option>
                            <option value="เกษตรกรผู้เพาะปลูก">เกษตรกรผู้เพาะปลูก</option>
                            <option value="นักวิจัย/บุคคลทั่วไป">นักวิจัย/บุคคลทั่วไป</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">สังกัด / โรงเรียน / หน่วยงาน</label>
                        <input type="text" name="organization" id="edit_organization" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" id="edit_phone" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">อีเมล</label>
                        <input type="email" name="email" id="edit_email" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">แทร็กโครงงาน Capstone ที่เลือก</label>
                    <select name="project_track" id="edit_project_track" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-tech">
                        <option value="Track A — Smart Agriculture & Sensor Hub">Track A — Smart Agriculture & Sensor Hub</option>
                        <option value="Track B — Plant Vision & Leaf AI">Track B — Plant Vision & Leaf AI</option>
                        <option value="Track C — Smart Soil & NPK Analyzer">Track C — Smart Soil & NPK Analyzer</option>
                        <option value="Track D — Dissolved Oxygen AI Controller">Track D — Dissolved Oxygen AI Controller</option>
                        <option value="Track E — Automated Bio-Colony Counter">Track E — Automated Bio-Colony Counter</option>
                        <option value="Track F — School AIoT & Micro-Climate">Track F — School AIoT & Micro-Climate</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คะแนน Pre-test (0-20)</label>
                        <input type="number" min="0" max="20" name="pre_score" id="edit_pre_score" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">คะแนน Post-test (0-20)</label>
                        <input type="number" min="0" max="20" name="post_score" id="edit_post_score" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">สถานะ</label>
                        <select name="status" id="edit_status" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-400 outline-none">
                            <option value="registered">ลงทะเบียน</option>
                            <option value="checked-in">เช็คอินแล้ว</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeEditParticipantModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">ยกเลิก</button>
                    <button type="submit" id="btnSubmitEditP" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 text-slate-950 font-black shadow-lg shadow-cyan-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-check"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODALS: ANNOUNCEMENTS ADD & EDIT -->
    <!-- ========================================================================= -->

    <!-- 3. ADD ANNOUNCEMENT MODAL -->
    <div id="addAnnouncementModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#0e1424] border border-amber-500/30 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">เพิ่มข้อความประกาศใหม่</h3>
                        <p class="text-xs text-slate-400">เผยแพร่ข่าวสารและกำหนดการสู่ระบบ</p>
                    </div>
                </div>
                <button onclick="closeAddAnnouncementModal()" class="text-slate-400 hover:text-white p-2 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addAnnouncementForm" onsubmit="submitAddAnnouncement(event)" class="space-y-4 text-xs sm:text-sm">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">หัวข้อประกาศ <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" required placeholder="เช่น กำหนดการจัดอบรมรอบที่ 2" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">เนื้อหาประกาศ <span class="text-rose-400">*</span></label>
                    <textarea name="content" rows="4" required placeholder="ระบุรายละเอียดข้อความประกาศ..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">ป้ายกำกับ (Badge Text)</label>
                        <input type="text" name="badge" value="ประชาสัมพันธ์" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">โทนสีของป้ายกำกับ</label>
                        <select name="badge_color" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                            <option value="cyan">สีฟ้าเทคโนโลยี (Cyan)</option>
                            <option value="emerald">สีเขียวสด (Emerald)</option>
                            <option value="amber">สีส้มแจ้งเตือน (Amber)</option>
                            <option value="purple">สีม่วงนวัตกรรม (Purple)</option>
                            <option value="rose">สีแดงด่วน (Rose)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="w-4 h-4 rounded text-amber-500 bg-slate-950 border-white/20">
                    <label for="add_is_active" class="text-xs text-slate-300">เปิดเผยแพร่ข้อความประกาศนี้ทันที</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeAddAnnouncementModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">ยกเลิก</button>
                    <button type="submit" id="btnSubmitAddAnn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 text-slate-950 font-black shadow-lg shadow-amber-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> บันทึกประกาศ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. EDIT ANNOUNCEMENT MODAL -->
    <div id="editAnnouncementModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#0e1424] border border-amber-500/40 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">แก้ไขข้อความประกาศ</h3>
                        <p class="text-xs text-slate-400" id="editAnnSubtitle">รหัสประกาศ #</p>
                    </div>
                </div>
                <button onclick="closeEditAnnouncementModal()" class="text-slate-400 hover:text-white p-2 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editAnnouncementForm" onsubmit="submitEditAnnouncement(event)" class="space-y-4 text-xs sm:text-sm">
                <input type="hidden" name="id" id="edit_ann_id">

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">หัวข้อประกาศ <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" id="edit_ann_title" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">เนื้อหาประกาศ <span class="text-rose-400">*</span></label>
                    <textarea name="content" id="edit_ann_content" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">ป้ายกำกับ (Badge Text)</label>
                        <input type="text" name="badge" id="edit_ann_badge" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">โทนสีของป้ายกำกับ</label>
                        <select name="badge_color" id="edit_ann_badge_color" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-white focus:border-amber-400 outline-none">
                            <option value="cyan">สีฟ้าเทคโนโลยี (Cyan)</option>
                            <option value="emerald">สีเขียวสด (Emerald)</option>
                            <option value="amber">สีส้มแจ้งเตือน (Amber)</option>
                            <option value="purple">สีม่วงนวัตกรรม (Purple)</option>
                            <option value="rose">สีแดงด่วน (Rose)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="edit_ann_is_active" value="1" class="w-4 h-4 rounded text-amber-500 bg-slate-950 border-white/20">
                    <label for="edit_ann_is_active" class="text-xs text-slate-300">เปิดเผยแพร่ข้อความประกาศนี้</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeEditAnnouncementModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition">ยกเลิก</button>
                    <button type="submit" id="btnSubmitEditAnn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 text-slate-950 font-black shadow-lg shadow-amber-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-check"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 5. GOOGLE SHEETS SYNC & INTEGRATION MODAL                                 -->
    <!-- ========================================================================= -->
    <div id="googleSheetsModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="bg-[#0e1424] border border-emerald-500/30 rounded-3xl p-6 sm:p-7 max-w-2xl w-full max-h-[92vh] overflow-y-auto space-y-5 shadow-2xl relative text-left">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-table"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg sm:text-xl text-white flex items-center gap-2">
                            <span>Google Sheets Real-time Cloud Sync</span>
                            <span id="gs_status_pill" class="text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                กำลังโหลด...
                            </span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">เชื่อมต่อฐานข้อมูลผู้สมัครกับ Google Sheets สำหรับทีมงานดูและจัดการแบบเรียลไทม์</p>
                    </div>
                </div>
                <button type="button" onclick="closeGoogleSheetsModal()" class="text-slate-400 hover:text-white text-lg p-2 rounded-xl hover:bg-white/5 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Sync Status Summary Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3.5 rounded-2xl bg-slate-950/60 border border-white/5 text-center">
                <div class="p-2.5 rounded-xl bg-slate-900/50 border border-white/5">
                    <div class="text-[10px] text-slate-400">สถานะระบบ</div>
                    <div id="gs_stat_status" class="text-xs font-bold text-emerald-400 mt-1">พร้อมใช้งาน</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900/50 border border-white/5">
                    <div class="text-[10px] text-slate-400">ซิงก์ล่าสุด</div>
                    <div id="gs_stat_last_sync" class="text-xs font-bold text-cyan-400 mt-1">-</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900/50 border border-white/5">
                    <div class="text-[10px] text-slate-400">จำนวนที่ซิงก์ล่าสุด</div>
                    <div id="gs_stat_count" class="text-xs font-bold text-amber-400 mt-1">0 ท่าน</div>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="space-y-4">
                
                <!-- Toggle Switch: Enable Sync -->
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-emerald-950/20 border border-emerald-500/20">
                    <div>
                        <div class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off text-emerald-400"></i> เปิดใช้งานระบบเชื่อมต่อ Google Sheets
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">เปิดเพื่อส่งข้อมูลสำเนาอัตโนมัติเมื่อมีผู้สมัครใหม่</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="gs_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <!-- Webhook URL Input -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">
                        Google Apps Script Web App URL <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="url" id="gs_webhook_url" 
                               placeholder="https://script.google.com/macros/s/.../exec"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-xs text-white focus:border-emerald-400 outline-none font-mono">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">URL ที่ได้จากการ Deploy เป็น Web app ใน Google Apps Script (เลือกสิทธิ์: Anyone)</p>
                </div>

                <!-- Google Sheet File Link (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">
                        ลิงก์ไฟล์ Google Sheet (สำหรับกดเปิดดูสะดวก)
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="url" id="gs_sheet_url" 
                               placeholder="https://docs.google.com/spreadsheets/d/.../edit"
                               class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-xs text-white focus:border-emerald-400 outline-none font-mono">
                        <button type="button" onclick="openTargetSheet()" id="btnOpenSheet" class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-400 font-bold text-xs flex items-center gap-1.5 transition whitespace-nowrap">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> เปิดชีต
                        </button>
                    </div>
                </div>

                <!-- Auto-sync on Register Option -->
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="gs_auto_sync" class="w-4 h-4 rounded text-emerald-500 bg-slate-950 border-white/20">
                    <label for="gs_auto_sync" class="text-xs text-slate-300">
                        ส่งข้อมูลขึ้น Google Sheet ทันทีเมื่อมีผู้สมัครใหม่ผ่านหน้าเว็บ (Real-time Background Sync)
                    </label>
                </div>

            </div>

            <!-- Action Buttons Toolbar -->
            <div class="p-3.5 rounded-2xl bg-slate-900/60 border border-white/5 flex flex-wrap items-center justify-between gap-2.5">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="testGoogleSheetsWebhook()" id="btnTestGS" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold text-xs flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-plug text-cyan-400"></i> ทดสอบการเชื่อมต่อ
                    </button>
                    <button type="button" onclick="syncAllToGoogleSheets()" id="btnSyncAllGS" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md transition">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sync ข้อมูลทั้งหมดขึ้น Google Sheet
                    </button>
                </div>
                <div>
                    <button type="button" onclick="saveGoogleSheetsConfig()" id="btnSaveGS" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs flex items-center gap-1.5 shadow-lg shadow-emerald-500/20 transition">
                        <i class="fa-solid fa-floppy-disk"></i> บันทึกการตั้งค่า
                    </button>
                </div>
            </div>

            <!-- Collapsible Setup Guide (Accordion) -->
            <div class="rounded-2xl border border-white/5 bg-slate-950/60 overflow-hidden">
                <button type="button" onclick="toggleGSGuide()" class="w-full p-3.5 flex items-center justify-between text-xs font-bold text-slate-300 hover:text-white transition">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-question text-amber-400"></i> วิธีสร้าง Webhook ใน Google Sheet (ทำครั้งเดียวใน 2 นาที)
                    </span>
                    <i id="gsGuideChevron" class="fa-solid fa-chevron-down text-slate-400 transition transform"></i>
                </button>
                <div id="gsGuideContent" class="p-4 pt-1 border-t border-white/5 space-y-3 text-xs text-slate-300 hidden">
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-300 leading-relaxed">
                        <li>เปิด Google Sheet ที่ต้องการ &gt; ไปที่เมนู <strong>"ส่วนขยาย" (Extensions) &gt; "Apps Script"</strong></li>
                        <li>ลบโค้ดเดิมออกทั้งหมด แล้วกดปุ่ม <strong>"คัดลอกสคริปต์ Code.gs"</strong> ด้านล่างนี้ไปวาง</li>
                        <li>กดบันทึก (แผ่นดิสก์) &gt; กด <strong>"การทำให้ใช้งานได้" (Deploy) &gt; "การทำให้ใช้งานได้ใหม่" (New deployment)</strong></li>
                        <li>เลือกประเภท <strong>"เว็บแอป" (Web app)</strong> &gt; กำหนดผู้มีสิทธิ์เข้าถึง: <strong>"ทุกคน" (Anyone)</strong></li>
                        <li>กด <strong>Deploy</strong> แล้วคัดลอก <strong>Web app URL</strong> มาวางในช่องด้านบนนี้</li>
                    </ol>

                    <div class="pt-2 flex items-center justify-between">
                        <button type="button" onclick="copyAppsScriptCode()" class="px-3 py-1.5 rounded-lg bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-300 border border-emerald-500/40 font-bold text-[11px] flex items-center gap-1.5 transition">
                            <i class="fa-regular fa-copy"></i> คัดลอกโค้ด Code.gs (1-Click Copy)
                        </button>
                        <a href="../google_sheets_script/Code.gs" target="_blank" class="text-[11px] text-cyan-400 hover:underline">
                            ดูไฟล์ Code.gs เต็ม <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

