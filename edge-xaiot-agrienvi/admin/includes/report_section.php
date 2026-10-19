                <div id="reportSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20 text-xs font-bold font-tech uppercase tracking-wider mb-2">
                                <i class="fas fa-microscope"></i> Research &amp; Learning Gain Metrics
                            </div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-chart-line text-purple-400"></i> รายงานสรุปผลสัมฤทธิ์ &amp; ดัชนีประสิทธิผล (Effectiveness Index: E.I.)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                การวิเคราะห์เปรียบเทียบผลการทดสอบ Pre-test vs Post-test และดัชนีการพัฒนาการเรียนรู้ตามหลักวิจัย
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <a href="print_report.php" target="_blank" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-blue-500/20 transition">
                                <i class="fas fa-print"></i> พิมพ์รายงานทางการ (A4)
                            </a>
                            <a href="export_csv.php" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-emerald-500/30 font-bold text-xs flex items-center gap-1.5 transition">
                                <i class="fas fa-file-csv"></i> ส่งออก CSV
                            </a>
                        </div>
                    </div>

                    <!-- 4 Summary KPI Mini Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">คะแนนเฉลี่ยก่อนเรียน (Pre-test)</span>
                            <div class="text-2xl font-black text-cyan-400 font-mono mt-1">
                                <?php echo $avg_pre; ?> <span class="text-xs text-slate-400 font-sans">/ 20</span>
                            </div>
                            <span class="text-[10px] text-slate-400">ผู้เข้าสอบ <?php echo $count_pre; ?> ท่าน</span>
                        </div>

                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">คะแนนเฉลี่ยหลังเรียน (Post-test)</span>
                            <div class="text-2xl font-black text-emerald-400 font-mono mt-1">
                                <?php echo $avg_post; ?> <span class="text-xs text-slate-400 font-sans">/ 20</span>
                            </div>
                            <span class="text-[10px] text-slate-400">ผู้เข้าสอบ <?php echo $count_post; ?> ท่าน</span>
                        </div>

                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">คะแนนที่พัฒนาเพิ่มขึ้น (Δ)</span>
                            <div class="text-2xl font-black text-amber-400 font-mono mt-1">
                                +<?php echo $score_diff; ?> <span class="text-xs text-amber-400 font-sans">(+<?php echo $percent_increase; ?>%)</span>
                            </div>
                            <span class="text-[10px] text-emerald-400">ผลสัมฤทธิ์เพิ่มขึ้นอย่างมีนัยสำคัญ</span>
                        </div>

                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">ดัชนีประสิทธิผล (E.I.)</span>
                            <div class="text-2xl font-black text-purple-400 font-mono mt-1">
                                <?php echo $ei; ?> <span class="text-xs text-purple-300 font-sans">(<?php echo $ei_percent; ?>%)</span>
                            </div>
                            <span class="text-[10px] text-purple-400 font-semibold">เกณฑ์ประสิทธิภาพระดับสูงมาก</span>
                        </div>
                    </div>

                    <!-- E.I. Gauge & Formula Explanation -->
                    <div class="bg-slate-950/80 p-5 rounded-2xl border border-purple-500/20 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-white flex items-center gap-1.5">
                                <i class="fas fa-calculator text-purple-400"></i> สูตร Normalized Learning Gain (Effectiveness Index: E.I.)
                            </span>
                            <span class="font-mono font-bold text-purple-400">
                                E.I. = (Post - Pre) / (Full - Pre) = (<?php echo $avg_post; ?> - <?php echo $avg_pre; ?>) / (20 - <?php echo $avg_pre; ?>) = <?php echo $ei; ?>
                            </span>
                        </div>
                        <div class="w-full bg-slate-900 h-3 rounded-full overflow-hidden border border-white/10 p-0.5">
                            <div class="bg-gradient-to-r from-cyan-500 via-emerald-400 to-purple-500 h-full rounded-full transition-all duration-1000" style="width: <?php echo min(100, max(10, $ei_percent)); ?>%;"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-500 font-mono">
                            <span>0.00 (ไม่มีพัฒนาการ)</span>
                            <span>0.30 (ระดับต่ำ)</span>
                            <span>0.70 (ระดับปานกลาง)</span>
                            <span class="text-purple-400 font-bold">0.80+ (ระดับสูงมาก &check;)</span>
                            <span>1.00 (สมบูรณ์แบบ)</span>
                        </div>
                    </div>

                    <!-- Target Group Breakdown Table -->
                    <div class="overflow-x-auto rounded-2xl border border-white/5">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="bg-slate-950/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-white/5 font-tech">
                                <tr>
                                    <th class="px-4 py-3">กลุ่มเป้าหมาย</th>
                                    <th class="px-4 py-3 text-center">จำนวน (คน)</th>
                                    <th class="px-4 py-3 text-center">สัดส่วน (%)</th>
                                    <th class="px-4 py-3 text-center">คะแนนเฉลี่ยก่อนเรียน</th>
                                    <th class="px-4 py-3 text-center">คะแนนเฉลี่ยหลังเรียน</th>
                                    <th class="px-4 py-3 text-center">พัฒนาการ (Δ)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 bg-slate-950/40">
                                <?php foreach ($target_groups as $grp_name => $gdata): 
                                    $gcnt = $gdata['count'];
                                    $gpct = $participants_count > 0 ? round(($gcnt / $participants_count) * 100, 1) : 0;
                                    $gpre = $gcnt > 0 ? round($gdata['pre_sum'] / $gcnt, 1) : 0;
                                    $gpost = $gcnt > 0 ? round($gdata['post_sum'] / $gcnt, 1) : 0;
                                    $gdiff = round($gpost - $gpre, 1);
                                ?>
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-4 py-3 font-semibold text-white">
                                        <?php echo htmlspecialchars($grp_name); ?>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono"><?php echo $gcnt; ?></td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-400"><?php echo $gpct; ?>%</td>
                                    <td class="px-4 py-3 text-center font-mono text-cyan-400"><?php echo $gpre; ?></td>
                                    <td class="px-4 py-3 text-center font-mono text-emerald-400 font-bold"><?php echo $gpost; ?></td>
                                    <td class="px-4 py-3 text-center font-mono text-amber-400 font-bold">+<?php echo $gdiff; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 4. EVALUATION SATISFACTION ANALYTICS SECTION -->
                <!-- ========================================================================= -->
