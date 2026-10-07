                <!-- 5. PARTICIPANT DIRECTORY & MANAGEMENT TABLE (WITH FULL CRUD) -->
                <div id="participantsTableSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-users text-blue-400"></i> ทำเนียบรายชื่อผู้ลงทะเบียน (Participants Directory)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">จัดการรายชื่อผู้เข้าร่วมอบรม เพิ่ม แก้ไข ลบข้อมูล และปรับปรุงสถานะการเช็คอินแบบ Real-time</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="text-xs font-mono text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-xl border border-emerald-500/20 font-bold">
                                รวม <?php echo count($participants); ?> ท่าน
                            </span>
                            <button onclick="openAddParticipantModal()" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                                <i class="fas fa-user-plus"></i> เพิ่มผู้ลงทะเบียน
                            </button>
                            <button onclick="confirmResetParticipants()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5" title="รีเซ็ตข้อมูลเริ่มต้น (10 คน)">
                                <i class="fas fa-rotate text-[11px] text-cyan-400"></i> รีเซ็ตชุดเริ่มต้น
                            </button>
                            <button onclick="exportCSV()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                        </div>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-5 relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" id="adminSearchInput" onkeyup="filterAdminTable()"
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-950/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-brand outline-none transition"
                                placeholder="ค้นหาชื่อ, โรงเรียน, กลุ่มเป้าหมาย, แทร็ก หรือเบอร์โทร...">
                        </div>
                        <div class="sm:col-span-3">
                            <select id="groupFilter" onchange="filterAdminTable()" class="w-full bg-slate-950/40 border border-white/10 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:border-brand outline-none">
                                <option value="">ทุกกลุ่มเป้าหมาย</option>
                                <option value="นักเรียนมัธยมศึกษาตอนปลาย">นักเรียน ม.ปลาย</option>
                                <option value="ครูและบุคลากรทางการศึกษา">ครูและบุคลากร</option>
                                <option value="เกษตรกรผู้เพาะปลูก">เกษตรกรผู้เพาะปลูก</option>
                                <option value="นักวิจัย/บุคคลทั่วไป">นักวิจัย/บุคคลทั่วไป</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <select id="trackFilter" onchange="filterAdminTable()" class="w-full bg-slate-950/40 border border-white/10 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:border-brand outline-none">
                                <option value="">ทุกแทร็กโครงงาน</option>
                                <option value="Track A">Track A (Smart Agri)</option>
                                <option value="Track B">Track B (Plant Vision)</option>
                                <option value="Track C">Track C (Smart Soil)</option>
                                <option value="Track D">Track D (DO Controller)</option>
                                <option value="Track E">Track E (Bio-Colony)</option>
                                <option value="Track F">Track F (School AI)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <select id="statusFilter" onchange="filterAdminTable()" class="w-full bg-slate-950/40 border border-white/10 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:border-brand outline-none">
                                <option value="">ทุกสถานะ</option>
                                <option value="checked-in">เช็คอินแล้ว</option>
                                <option value="registered">ยังไม่เช็คอิน</option>
                            </select>
                        </div>
                    </div>

                    <!-- The Table -->
                    <div class="overflow-x-auto rounded-2xl border border-white/5">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-950/60 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/5 font-tech">
                                <tr>
                                    <th class="px-4 py-3.5 text-center">ID</th>
                                    <th class="px-4 py-3.5">ชื่อ-นามสกุล</th>
                                    <th class="px-4 py-3.5">กลุ่มเป้าหมาย</th>
                                    <th class="px-4 py-3.5">สังกัด / สถาบัน</th>
                                    <th class="px-4 py-3.5">แทร็กโครงงานที่เลือก</th>
                                    <th class="px-4 py-3.5 text-center">Pre / Post</th>
                                    <th class="px-4 py-3.5 text-center">สถานะ</th>
                                    <th class="px-4 py-3.5 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminTableBody" class="divide-y divide-white/5 text-slate-200">
                                <?php if (empty($participants)): ?>
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-500">
                                        ยังไม่มีข้อมูลผู้ลงทะเบียน กดปุ่ม "+ เพิ่มผู้ลงทะเบียน" หรือ "รีเซ็ตชุดเริ่มต้น"
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($participants as $p): ?>
                                <tr class="hover:bg-white/[0.02] transition participant-row"
                                    id="participant-row-<?php echo $p['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($p['fullname'] ?? ''); ?>"
                                    data-phone="<?php echo htmlspecialchars($p['phone'] ?? ''); ?>"
                                    data-org="<?php echo htmlspecialchars($p['organization'] ?? ''); ?>"
                                    data-group="<?php echo htmlspecialchars($p['target_group'] ?? ''); ?>"
                                    data-track="<?php echo htmlspecialchars($p['project_track'] ?? ''); ?>"
                                    data-status="<?php echo htmlspecialchars($p['status'] ?? 'registered'); ?>">
                                    <td class="px-4 py-3.5 text-center font-mono text-slate-500">
                                        #<?php echo $p['id']; ?>
                                    </td>
                                    <td class="px-4 py-3.5 font-bold text-white">
                                        <?php echo htmlspecialchars(($p['prefix'] ?? '') . ' ' . ($p['fullname'] ?? '')); ?>
                                        <div class="text-[10px] text-slate-500 font-mono font-normal">
                                            <i class="fas fa-phone-alt text-[9px] mr-1 text-slate-600"></i><?php echo htmlspecialchars($p['phone'] ?? '-'); ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 whitespace-nowrap">
                                            <?php echo htmlspecialchars($p['target_group'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-300">
                                        <?php echo htmlspecialchars($p['organization'] ?? '-'); ?>
                                    </td>
                                    <td class="px-4 py-3.5 font-tech text-emerald-400 font-medium">
                                        <?php echo htmlspecialchars($p['project_track'] ?? '-'); ?>
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-bold">
                                        <span class="text-cyan-400"><?php echo $p['pre_score'] ?? 0; ?></span>
                                        <span class="text-slate-500">/</span>
                                        <span class="text-emerald-400"><?php echo $p['post_score'] ?? 0; ?></span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        <?php if (($p['status'] ?? '') === 'checked-in'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-flex items-center gap-1">
                                            <i class="fas fa-check text-[9px]"></i> เช็คอินแล้ว
                                        </span>
                                        <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 inline-flex items-center gap-1">
                                            <i class="fas fa-clock text-[9px]"></i> ลงทะเบียน
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Certificate Button -->
                                            <a href="../pages/certificate_view.php?student_id=<?php echo $p['id']; ?>" target="_blank" class="px-2 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/20 font-bold transition flex items-center gap-1 text-[11px]" title="เปิดดูและพิมพ์เกียรติบัตร">
                                                <i class="fas fa-certificate text-amber-400"></i> เกียรติบัตร
                                            </a>
                                            <!-- Edit Button -->
                                            <button onclick='openEditParticipantModal(<?php echo htmlspecialchars(json_encode($p), ENT_QUOTES, "UTF-8"); ?>)' class="px-2.5 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/20 font-bold transition flex items-center gap-1 text-[11px]" title="แก้ไขข้อมูล">
                                                <i class="fas fa-edit"></i> แก้ไข
                                            </button>
                                            <!-- Toggle Check-in Button -->
                                            <button onclick="toggleCheckIn(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['fullname'])); ?>')" class="px-2 py-1.5 rounded-lg <?php echo ($p['status'] ?? '') === 'checked-in' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20'; ?> font-bold transition text-[11px]" title="<?php echo ($p['status'] ?? '') === 'checked-in' ? 'สลับเป็นยังไม่เช็คอิน' : 'เช็คอินเข้าร่วม'; ?>">
                                                <i class="fas <?php echo ($p['status'] ?? '') === 'checked-in' ? 'fa-arrow-rotate-left' : 'fa-check'; ?>"></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button onclick="confirmDeleteParticipant(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['fullname'])); ?>')" class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 font-bold transition flex items-center gap-1 text-[11px]" title="ลบรายชื่อ">
                                                <i class="fas fa-trash-can"></i> ลบ
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

                <!-- ========================================================================= -->
                <!-- 3. SUMMARY REPORT & LEARNING GAIN ANALYTICS SECTION -->
                <!-- ========================================================================= -->
