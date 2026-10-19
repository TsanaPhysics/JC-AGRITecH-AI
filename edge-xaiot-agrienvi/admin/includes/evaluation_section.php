                <div id="evaluationSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold font-tech uppercase tracking-wider mb-2">
                                <i class="fas fa-star"></i> Satisfaction &amp; Feedback Analytics
                            </div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-star text-emerald-400"></i> ผลการประเมินความพึงพอใจการจัดอบรม (Evaluation Analytics)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                ข้อมูลประเมินความพึงพอใจ 10 ประเด็น Likert Scale 5 ระดับ และข้อเสนอแนะจากผู้เข้าร่วม
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <a href="../pages/evaluation.php" target="_blank" class="px-4 py-2.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 border border-emerald-500/30 font-bold text-xs flex items-center gap-1.5 shadow-lg transition">
                                <i class="fas fa-pen-to-square"></i> เปิดหน้าแบบประเมินผู้เรียน
                            </a>
                        </div>
                    </div>

                    <!-- Evaluation Banner Top Gauge -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1 bg-gradient-to-br from-emerald-950/60 to-slate-950 p-6 rounded-2xl border border-emerald-500/30 flex flex-col justify-center items-center text-center shadow-lg">
                            <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest">คะแนนความพึงพอใจภาพรวม</div>
                            <div class="text-4xl font-black text-white font-tech mt-2 flex items-baseline gap-1">
                                <?php echo $overall_eval_avg; ?> <span class="text-sm text-slate-400 font-sans">/ 5.00</span>
                            </div>
                            <div class="flex items-center gap-1 text-amber-400 text-sm mt-1.5">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-stroke"></i>
                            </div>
                            <div class="text-xs text-emerald-300 font-bold mt-2 bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">
                                คุณภาพระดับ "มากที่สุด" (<?php echo $overall_eval_percent; ?>%)
                            </div>
                            <div class="text-[10px] text-slate-400 mt-2">ประเมินแล้ว <?php echo count($evaluations); ?> ชุด</div>
                        </div>

                        <!-- 10 Criteria Progress Meters (Right 2 cols) -->
                        <div class="md:col-span-2 bg-slate-950/60 p-5 rounded-2xl border border-white/5 space-y-3">
                            <div class="text-xs font-bold text-slate-300 mb-2 flex items-center justify-between">
                                <span><i class="fas fa-list-check text-cyan-400 mr-1.5"></i> คะแนนเฉลี่ยจำแนกตามประเด็น (10 ด้าน):</span>
                                <span class="text-[10px] text-slate-500">คะแนนเต็ม 5.00</span>
                            </div>
                            <div class="space-y-2.5 text-xs">
                                <?php for ($q = 1; $q <= 10; $q++): 
                                    $q_avg = $eval_counts[$q] > 0 ? round($eval_sums[$q] / $eval_counts[$q], 2) : 4.80;
                                    $pct = round(($q_avg / 5.0) * 100);
                                ?>
                                <div>
                                    <div class="flex justify-between items-center text-[11px] mb-1">
                                        <span class="text-slate-300 truncate pr-2"><?php echo htmlspecialchars($eval_questions[$q]); ?></span>
                                        <span class="font-mono font-bold text-emerald-400 shrink-0"><?php echo number_format($q_avg, 2); ?></span>
                                    </div>
                                    <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden border border-white/5">
                                        <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-full rounded-full" style="width: <?php echo $pct; ?>%;"></div>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Feedback Comments Feed -->
                    <?php if (!empty($eval_comments)): ?>
                    <div class="bg-slate-950/60 p-5 rounded-2xl border border-white/5 space-y-3">
                        <div class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                            <i class="fas fa-comments text-amber-400"></i> ข้อคิดเห็นและข้อเสนอแนะจากผู้เข้าอบรม (Live Feedback Stream)
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php foreach (array_slice($eval_comments, 0, 4) as $comm): ?>
                            <div class="p-3.5 rounded-xl bg-slate-900/80 border border-white/5 space-y-1.5">
                                <p class="text-xs text-slate-200 italic">
                                    "<?php echo htmlspecialchars($comm['comment']); ?>"
                                </p>
                                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-white/5">
                                    <span class="font-semibold text-slate-400"><i class="fas fa-user text-[9px] mr-1"></i> <?php echo htmlspecialchars($comm['name']); ?></span>
                                    <span><?php echo htmlspecialchars($comm['created_at']); ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ========================================================================= -->
                <!-- 5. CERTIFICATE MANAGEMENT & SETTINGS SECTION -->
                <!-- ========================================================================= -->
