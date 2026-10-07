        <section id="modules" class="scroll-mt-24 space-y-8">
            
            <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-12 shadow-xl border border-emerald-200/80 relative overflow-hidden group w-full min-w-0">
                <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6 border-b border-gray-100 pb-6 relative z-10 w-full min-w-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-2">
                            <span class="px-2.5 sm:px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">หลักสูตรอบรมเชิงปฏิบัติการ</span>
                            <span class="text-xs text-gray-400 font-mono">7 หน่วยการเรียนรู้ (Modules)</span>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                <i class="fa-solid fa-sparkles text-amber-500 mr-1"></i>3D Pixar Ghibli Style
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-heading font-bold text-gray-900 break-words">
                            โครงสร้างหลักสูตร 7 Modules (Active Learning 70%)
                        </h2>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">
                            เรียนรู้ปัญญาประดิษฐ์และเซนเซอร์การเกษตรแม่นยำผ่านแนวทางสร้างสรรค์และลงมือปฏิบัติจริง
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <img src="assets/images/pixar_smart_farm_hero.jpg" alt="Active Learning 3D Pixar" class="w-20 sm:w-24 h-14 sm:h-16 md:w-32 md:h-20 object-cover rounded-xl sm:rounded-2xl shadow-md border-2 border-white hover:scale-105 transition cursor-pointer" onclick="openScreenModal('assets/images/pixar_smart_farm_hero.jpg', 'นวัตกรรมเกษตรดิจิทัล 3D Pixar Ghibli AIoT', 'การเรียนรู้ปัญญาประดิษฐ์และเซนเซอร์การเกษตรเชิงสร้างสรรค์ ผสานหุ่นยนต์และระบบอัจฉริยะ')" title="คลิกดูภาพขยาย">
                        <a href="pages/register.php" class="px-4 sm:px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 whitespace-nowrap">
                            <i class="fa-solid fa-user-plus"></i> สมัครเข้าร่วมอบรม
                        </a>
                    </div>
                </div>

                <!-- 7 Modules Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-6 relative z-10">
                    
                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Module 1 • 2 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">บทนำ: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</h4>
                        <p class="text-xs text-gray-500 mt-1">เข้าใจนิเวศ AI, IoT, Edge AI และความแตกต่างระหว่าง Cloud AI กับ On-Device TinyML</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded">Module 2 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Digital Agriculture: การเกษตรแม่นยำด้วยข้อมูลและเซนเซอร์</h4>
                        <p class="text-xs text-gray-500 mt-1">การอ่านโพรบ RS485 Modbus RTU, คำนวณ VPD และวิเคราะห์ความชื้นดินสองระดับ</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Module 3 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Deep Learning: เปิดกล่องดำสู่ปัญญาประดิษฐ์</h4>
                        <p class="text-xs text-gray-500 mt-1">สถาปัตยกรรม CNN, การเตรียม Dataset ภาพใบพืช และการฝึกสอนโมเดลบน Google Colab</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Module 4 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Computer Vision: สายตา AI ในแปลงเกษตร</h4>
                        <p class="text-xs text-gray-500 mt-1">OpenCV ตรวจคัดแยกสีผลไม้ และ YOLOv8 ตรวจจับโรคพืชพร้อมประเมินค่า mAP@50</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Module 5 • 3 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Edge AI: นำ AI ออกจาก Cloud สู่พื้นที่จริง</h4>
                        <p class="text-xs text-gray-500 mt-1">การควอนไทซ์โมเดล INT8, บีบอัดสู่ C++ Array และ On-Device Inference บน ESP32-S3</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1">
                        <span class="text-[10px] font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">Module 6 • 2 ชม.</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Environmental AI: AI นักสืบสิ่งแวดล้อม</h4>
                        <p class="text-xs text-gray-500 mt-1">วิเคราะห์สมดุลคาร์บอน แสงอาทิตย์ PAR สภาพอากาศชุมชน และการอนุรักษ์น้ำ</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/90 border border-gray-100 shadow-sm hover:shadow-md transition hover:-translate-y-1 sm:col-span-2 lg:col-span-3">
                        <span class="text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Module 7 • 3 ชม. • Capstone Showcase</span>
                        <h4 class="font-bold text-gray-800 text-sm mt-1.5">Agri-Environmental Intelligence: บูรณาการภาพ + เซนเซอร์ + IoT + AI</h4>
                        <p class="text-xs text-gray-500 mt-1">การนำเสนอโครงงาน Capstone Mini Projects การตัดสินใจเปิดน้ำใส่ปุ๋ยอัตโนมัติ และพิธีมอบเกียรติบัตร</p>
                    </div>

                </div>

                <!-- 3-Day Activity Schedule Showcase -->
                <div class="mt-8 pt-8 border-t border-gray-100 relative z-10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-calendar-check text-emerald-600"></i> Workshop Timeline (3 วัน 18 ชั่วโมง)
                            </span>
                            <h3 class="text-xl md:text-2xl font-heading font-bold text-gray-900 mt-2">
                                กำหนดการจัดกิจกรรมอบรมเชิงปฏิบัติการ วันที่ 28 - 30 พฤศจิกายน 2569
                            </h3>
                            <p class="text-xs md:text-sm text-gray-600 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-location-dot text-rose-500"></i>
                                <span><strong>สถานที่:</strong> คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี</span>
                            </p>
                        </div>
                        <div class="shrink-0">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 text-emerald-800 font-bold text-xs">
                                <i class="fa-solid fa-award text-amber-500 text-base"></i>
                                <span>เน้นลงมือปฏิบัติจริงทุกขั้นตอน</span>
                            </span>
                        </div>
                    </div>

                    <!-- 3-Day Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        <!-- Day 1 -->
                        <div class="p-5 rounded-2xl bg-gradient-to-b from-emerald-50/70 to-white border border-emerald-200/80 shadow-xs hover:shadow-md transition">
                            <div class="flex items-center justify-between border-b border-emerald-100 pb-3 mb-3">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-md bg-emerald-600 text-white font-mono text-xs font-bold">DAY 1</span>
                                    <div class="font-bold text-emerald-950 text-base mt-1">28 พ.ย. 2569</div>
                                </div>
                                <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-seedling"></i>
                                </span>
                            </div>
                            <div class="font-bold text-emerald-800 text-xs mb-2">AI + Digital Agriculture + IoT</div>
                            <ul class="text-xs text-gray-600 space-y-1.5 font-mono leading-relaxed">
                                <li>• <strong>09.00 - 09.30:</strong> ลงทะเบียน / เปิดโครงการ</li>
                                <li>• <strong>09.30 - 10.30:</strong> AI for Digital Agriculture</li>
                                <li>• <strong>10.45 - 12.00:</strong> Digital Agriculture &amp; IoT</li>
                                <li>• <strong>13.00 - 14.30:</strong> Sensor &amp; Smart Farm Lab</li>
                                <li>• <strong>14.45 - 16.00:</strong> Computer Vision พื้นฐาน</li>
                                <li>• <strong>16.00 - 16.30:</strong> AI Experiment &amp; Hands-on</li>
                            </ul>
                            <div class="mt-3.5 pt-2.5 border-t border-emerald-100/80 text-[11px] text-emerald-700 font-medium">
                                <i class="fa-solid fa-check-circle text-emerald-500 mr-1"></i> ผลงาน: ได้ชิ้นงาน AI เกษตรดิจิทัล และอ่านค่าเซนเซอร์จริง
                            </div>
                        </div>

                        <!-- Day 2 -->
                        <div class="p-5 rounded-2xl bg-gradient-to-b from-cyan-50/70 to-white border border-cyan-200/80 shadow-xs hover:shadow-md transition">
                            <div class="flex items-center justify-between border-b border-cyan-100 pb-3 mb-3">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-md bg-cyan-600 text-white font-mono text-xs font-bold">DAY 2</span>
                                    <div class="font-bold text-cyan-950 text-base mt-1">29 พ.ย. 2569</div>
                                </div>
                                <span class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-brain"></i>
                                </span>
                            </div>
                            <div class="font-bold text-cyan-800 text-xs mb-2">Computer Vision + Deep Learning + Edge AI</div>
                            <ul class="text-xs text-gray-600 space-y-1.5 font-mono leading-relaxed">
                                <li>• <strong>09.00 - 10.00:</strong> Computer Vision เชิงลึก</li>
                                <li>• <strong>10.15 - 12.00:</strong> เตรียมข้อมูล &amp; สร้าง Dataset</li>
                                <li>• <strong>13.00 - 14.30:</strong> Train Deep Learning Model</li>
                                <li>• <strong>14.45 - 16.00:</strong> Edge AI บนอุปกรณ์ปลายทาง</li>
                                <li>• <strong>16.00 - 16.30:</strong> Edge AI Hands-on Workshop</li>
                            </ul>
                            <div class="mt-3.5 pt-2.5 border-t border-cyan-100/80 text-[11px] text-cyan-700 font-medium">
                                <i class="fa-solid fa-check-circle text-cyan-500 mr-1"></i> ผลงาน: สร้างโมเดล AI และนำไปใช้งานบนอุปกรณ์ Edge ได้
                            </div>
                        </div>

                        <!-- Day 3 -->
                        <div class="p-5 rounded-2xl bg-gradient-to-b from-indigo-50/70 to-white border border-indigo-200/80 shadow-xs hover:shadow-md transition">
                            <div class="flex items-center justify-between border-b border-indigo-100 pb-3 mb-3">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-md bg-indigo-600 text-white font-mono text-xs font-bold">DAY 3</span>
                                    <div class="font-bold text-indigo-950 text-base mt-1">30 พ.ย. 2569</div>
                                </div>
                                <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-trophy"></i>
                                </span>
                            </div>
                            <div class="font-bold text-indigo-800 text-xs mb-2">Environment AI + Mini Project Capstone</div>
                            <ul class="text-xs text-gray-600 space-y-1.5 font-mono leading-relaxed">
                                <li>• <strong>09.00 - 10.00:</strong> Environment AI &amp; Smart Climate</li>
                                <li>• <strong>10.15 - 12.00:</strong> Agri-Environmental Intelligence</li>
                                <li>• <strong>13.00 - 15.00:</strong> Mini Project Capstone Hackathon</li>
                                <li>• <strong>15.15 - 16.15:</strong> Pitching นำเสนอผลงาน 6 แทร็ก</li>
                                <li>• <strong>16.15 - 16.30:</strong> สรุปผล / Post-test / พิธีมอบเกียรติบัตร</li>
                            </ul>
                            <div class="mt-3.5 pt-2.5 border-t border-indigo-100/80 text-[11px] text-indigo-700 font-medium">
                                <i class="fa-solid fa-check-circle text-indigo-500 mr-1"></i> ผลงาน: นำเสนอ Mini Project พร้อมวุฒิบัตรรับรองสมรรถนะ
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 7. CAPSTONE MINI PROJECTS (6 TRACKS)                                      -->
        <!-- ========================================================================= -->
