                <div id="certificateSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-bold font-tech uppercase tracking-wider mb-2">
                                <i class="fas fa-certificate"></i> Certificate Control Panel
                            </div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-award text-amber-400"></i> จัดการระบบเกียรติบัตรออนไลน์ (Certificate Management)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                ปรับแต่งข้อความหลักสูตร, โครงการ, ข้อมูลผู้ลงนาม 2 ท่าน, ลายมือชื่อดิจิทัล, และรหัสอ้างอิง
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <a href="../pages/certificate_view.php?style=modern" target="_blank" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-amber-500/20 transition">
                                <i class="fas fa-eye"></i> เปิดดูตัวอย่างเกียรติบัตร (Live Preview)
                            </a>
                            <a href="../pages/certificate.php" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs flex items-center gap-1.5 transition">
                                <i class="fas fa-shield-halved text-amber-400"></i> ตรวจสอบสิทธิ์ผู้เรียน
                            </a>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-2">
                        <i class="fas fa-circle-check text-sm"></i> <?php echo htmlspecialchars($_SESSION['flash_msg']); unset($_SESSION['flash_msg']); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Certificate Configuration Form -->
                    <form method="POST" class="space-y-5 text-xs">
                        <input type="hidden" name="action" value="save_certificate_config">

                        <!-- Row 1: Course & Project Titles -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-300">ชื่อหลักสูตรอบรม (Course Title):</label>
                                <input type="text" name="course_name" value="<?php echo htmlspecialchars($cert_config['course_name'] ?? ''); ?>" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-3.5 py-2.5 text-white focus:border-amber-400 outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-300">รหัสคำนำหน้าเกียรติบัตร (Certificate Prefix):</label>
                                <input type="text" name="cert_ref_prefix" value="<?php echo htmlspecialchars($cert_config['cert_ref_prefix'] ?? 'LEQs-xAI'); ?>" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-3.5 py-2.5 text-white focus:border-amber-400 outline-none font-mono">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-bold text-slate-300">ชื่อโครงการอย่างเป็นทางการ (Full Project Description):</label>
                            <textarea name="project_name" rows="2" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-3.5 py-2 text-white focus:border-amber-400 outline-none"><?php echo htmlspecialchars($cert_config['project_name'] ?? ''); ?></textarea>
                        </div>

                        <!-- Row 2: Signatory 1 & Signatory 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-white/5">
                            <!-- Signatory 1 (Project Leader) -->
                            <div class="p-4 rounded-2xl bg-slate-950/60 border border-white/5 space-y-3">
                                <div class="font-bold text-amber-400 flex items-center gap-1.5">
                                    <i class="fas fa-signature"></i> ผู้ลงนาม ๑ (หัวหน้าโครงการ / ผู้รับผิดชอบหลัก)
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ชื่อ-นามสกุล:</label>
                                    <input type="text" name="signatory1_name" value="<?php echo htmlspecialchars($cert_config['signatory1_name'] ?? ''); ?>" required class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ตำแหน่ง:</label>
                                    <input type="text" name="signatory1_title" value="<?php echo htmlspecialchars($cert_config['signatory1_title'] ?? ''); ?>" required class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">สังกัด / โครงการ:</label>
                                    <input type="text" name="signatory1_sub" value="<?php echo htmlspecialchars($cert_config['signatory1_sub'] ?? ''); ?>" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ชื่อไฟล์ภาพลายเซ็น (ใน /assets/images/):</label>
                                    <input type="text" name="signatory1_image" value="<?php echo htmlspecialchars($cert_config['signatory1_image'] ?? 'chewa_sign.png'); ?>" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs font-mono">
                                </div>
                            </div>

                            <!-- Signatory 2 (Dean / Administrator) -->
                            <div class="p-4 rounded-2xl bg-slate-950/60 border border-white/5 space-y-3">
                                <div class="font-bold text-amber-400 flex items-center gap-1.5">
                                    <i class="fas fa-signature"></i> ผู้ลงนาม ๒ (คณบดีคณะวิทยาศาสตร์และเทคโนโลยี)
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ชื่อ-นามสกุล:</label>
                                    <input type="text" name="signatory2_name" value="<?php echo htmlspecialchars($cert_config['signatory2_name'] ?? ''); ?>" required class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ตำแหน่ง:</label>
                                    <input type="text" name="signatory2_title" value="<?php echo htmlspecialchars($cert_config['signatory2_title'] ?? ''); ?>" required class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">สังกัด / สถาบัน:</label>
                                    <input type="text" name="signatory2_sub" value="<?php echo htmlspecialchars($cert_config['signatory2_sub'] ?? ''); ?>" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] text-slate-400">ชื่อไฟล์ภาพลายเซ็น (ใน /assets/images/):</label>
                                    <input type="text" name="signatory2_image" value="<?php echo htmlspecialchars($cert_config['signatory2_image'] ?? 'vichaladda_sign.png'); ?>" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-2 text-white text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Save Button & Style Previews -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-white/5">
                            <div class="flex items-center gap-2 flex-wrap text-xs text-slate-400">
                                <span>เปิดพรีวิวแต่ละสไตล์:</span>
                                <a href="../pages/certificate_view.php?style=modern" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-sky-400 font-bold transition">1. Modern</a>
                                <a href="../pages/certificate_view.php?style=formal" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold transition">2. Formal</a>
                                <a href="../pages/certificate_view.php?style=ai" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-teal-400 font-bold transition">3. AI Cyber</a>
                                <a href="../pages/certificate_view.php?style=classic" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-emerald-400 font-bold transition">4. Classic</a>
                            </div>

                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black text-xs shadow-lg shadow-orange-500/20 transition flex items-center gap-1.5">
                                <i class="fas fa-floppy-disk"></i> บันทึกการตั้งค่าเกียรติบัตร
                            </button>
                        </div>
                    </form>
                </div>

            <!-- Page Bottom Footer -->
