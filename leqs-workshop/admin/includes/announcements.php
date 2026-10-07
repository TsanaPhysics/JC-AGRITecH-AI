                <!-- 4. SYSTEM ANNOUNCEMENTS & MESSAGES MANAGEMENT -->
                <div id="announcementsSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-bullhorn text-amber-400"></i> จัดการข้อความและประกาศประชาสัมพันธ์ (System Announcements &amp; Broadcasts)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">เพิ่ม แก้ไข หรือลบข้อความข่าวสาร ประชาสัมพันธ์โครงการ และกำหนดการสำหรับหน้าเว็บหลัก</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-amber-400 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 font-bold">
                                รวม <?php echo count($announcements); ?> ประกาศ
                            </span>
                            <button onclick="openAddAnnouncementModal()" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black rounded-xl text-xs shadow-lg shadow-amber-500/20 transition flex items-center gap-1.5 shrink-0">
                                <i class="fas fa-plus"></i> เพิ่มประกาศใหม่
                            </button>
                        </div>
                    </div>

                    <!-- Announcements Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if (empty($announcements)): ?>
                        <div class="col-span-full p-8 text-center rounded-2xl bg-slate-950/40 border border-dashed border-white/10 text-slate-500 text-xs">
                            <i class="fas fa-info-circle text-2xl mb-2 text-slate-600 block"></i>
                            ยังไม่มีข้อความประกาศในระบบ กดปุ่ม <b>"+ เพิ่มประกาศใหม่"</b> เพื่อสร้างข้อความแรก
                        </div>
                        <?php else: ?>
                        <?php foreach ($announcements as $ann): ?>
                        <?php
                            $badge_color = $ann['badge_color'] ?? 'cyan';
                            $color_classes = [
                                'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'cyan' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'purple' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                'rose' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'
                            ][$badge_color] ?? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
                            $is_active = ($ann['is_active'] ?? 1) == 1;
                        ?>
                        <div class="p-5 rounded-2xl bg-slate-950/40 border <?php echo $is_active ? 'border-white/5 hover:border-amber-500/30' : 'border-dashed border-white/10 opacity-60'; ?> transition flex flex-col justify-between gap-4">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $color_classes; ?>">
                                        <?php echo htmlspecialchars($ann['badge'] ?? 'ประกาศ'); ?>
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full <?php echo $is_active ? 'bg-emerald-400 animate-pulse' : 'bg-slate-600'; ?>"></span>
                                        <span class="text-[10px] font-mono <?php echo $is_active ? 'text-emerald-400' : 'text-slate-500'; ?>">
                                            <?php echo $is_active ? 'เผยแพร่' : 'ซ่อนไว้'; ?>
                                        </span>
                                    </div>
                                </div>
                                <h4 class="font-bold text-sm text-white line-clamp-2"><?php echo htmlspecialchars($ann['title'] ?? ''); ?></h4>
                                <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed"><?php echo nl2br(htmlspecialchars($ann['content'] ?? '')); ?></p>
                            </div>
                            <div class="pt-3 border-t border-white/5 flex items-center justify-between text-xs">
                                <span class="text-[10px] font-mono text-slate-500">
                                    <i class="far fa-clock mr-1"></i> <?php echo substr($ann['created_at'] ?? '', 0, 10); ?>
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="toggleAnnouncementActive(<?php echo $ann['id']; ?>)" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition" title="<?php echo $is_active ? 'ปิดการแสดงผล' : 'เปิดการแสดงผล'; ?>">
                                        <i class="fas <?php echo $is_active ? 'fa-eye-slash text-amber-400' : 'fa-eye text-emerald-400'; ?>"></i>
                                    </button>
                                    <button onclick='openEditAnnouncementModal(<?php echo htmlspecialchars(json_encode($ann), ENT_QUOTES, "UTF-8"); ?>)' class="p-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/20 transition" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmDeleteAnnouncement(<?php echo $ann['id']; ?>, '<?php echo htmlspecialchars(addslashes($ann['title'])); ?>')" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition" title="ลบ">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

