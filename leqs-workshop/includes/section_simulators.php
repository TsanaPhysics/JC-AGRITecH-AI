        <section id="simulators" class="scroll-mt-24 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="px-3.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-wider">
                    Interactive Virtual Laboratories
                </span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">
                    ห้องปฏิบัติการจำลองเสมือนจริง (Virtual Labs)
                </h2>
                <p class="text-gray-500 text-sm">
                    ทดลองปรับค่าพารามิเตอร์สิ่งแวดล้อม และสัมผัสการตัดสินใจของ AI และสมการฟิสิกส์แบบเรียลไทม์
                </p>
            </div>

            <!-- SIMULATORS GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- SIMULATOR 1: VPD Plant Transpiration Simulator -->
                <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group w-full min-w-0">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-200">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">เครื่องคำนวณ VPD และการคายน้ำของพืช</h3>
                                    <p class="text-xs text-gray-500">SHT45 Microclimate &amp; Vapor Pressure Deficit Engine</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-700">Lab 1</span>
                        </div>

                        <!-- Readout Values -->
                        <div class="grid grid-cols-3 gap-3 my-6">
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">VPD (kPa)</span>
                                <span id="out-vpd-val" class="text-2xl font-black text-emerald-600 font-tech">1.10</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">SVP (kPa)</span>
                                <span id="out-svp-val" class="text-2xl font-black text-cyan-600 font-tech">4.24</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">AVP (kPa)</span>
                                <span id="out-avp-val" class="text-2xl font-black text-blue-600 font-tech">3.14</span>
                            </div>
                        </div>

                        <!-- Sliders -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>อุณหภูมิอากาศ (Air Temp)</span>
                                    <span id="val-vpd-temp" class="text-emerald-600 font-mono">30.0 °C</span>
                                </div>
                                <input type="range" id="vpd-temp" min="15" max="45" step="0.5" value="30" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>ความชื้นสัมพัทธ์ (Relative Humidity)</span>
                                    <span id="val-vpd-hum" class="text-cyan-600 font-mono">74 %</span>
                                </div>
                                <input type="range" id="vpd-humidity" min="20" max="98" step="1" value="74" oninput="updateVPDSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-cyan-500">
                            </div>
                        </div>

                        <!-- Advisory Card -->
                        <div id="box-vpd-advisory" class="mt-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-base mt-0.5"></i>
                            <div>
                                <div id="title-vpd-advisory" class="font-bold">สภาวะเหมาะสมสมบูรณ์แบบ (VPD 0.8 - 1.25 kPa)</div>
                                <div id="desc-vpd-advisory" class="mt-0.5 text-gray-600 leading-relaxed">การคายน้ำและการดูดซึมธาตุอาหาร NPK ดำเนินไปอย่างสมบูรณ์แบบ ทุเรียนและพืชแปลงขยายขนาดได้อย่างมีประสิทธิภาพ</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIMULATOR 2: Coastal Aquaculture DO & VFD Power Saver Simulator -->
                <div class="glass-card rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 md:p-10 shadow-xl border border-gray-100 flex flex-col justify-between relative overflow-hidden group w-full min-w-0">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-xl shadow-md shadow-sky-200">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">การจัดการออกซิเจนละลาย DO &amp; อินเวอร์เตอร์ VFD</h3>
                                    <p class="text-xs text-gray-500">Benson-Krause Saturation &amp; Affinity Power Laws</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-sky-100 text-sky-700">Lab 2</span>
                        </div>

                        <!-- Readout Values -->
                        <div class="grid grid-cols-3 gap-3 my-6">
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">DO อิ่มตัว (Sat)</span>
                                <span id="outDoSat" class="text-2xl font-black text-sky-600 font-tech">6.82</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">VFD Freq</span>
                                <span id="outVfdFreq" class="text-2xl font-black text-indigo-600 font-tech">38.0 Hz</span>
                            </div>
                            <div class="bg-white border border-gray-100 p-3 rounded-2xl text-center shadow-sm">
                                <span class="text-[10px] text-gray-400 block uppercase font-mono">ประหยัดไฟ</span>
                                <span id="outPowerSave" class="text-2xl font-black text-emerald-600 font-tech">56.1 %</span>
                            </div>
                        </div>

                        <!-- Sliders -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>อุณหภูมิน้ำ</span>
                                        <span id="valAquaTemp" class="text-sky-600 font-mono">29.0 °C</span>
                                    </div>
                                    <input type="range" id="inputAquaTemp" min="20" max="38" step="0.5" value="29" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-sky-500">
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                        <span>ความเค็มน้ำ (Salinity)</span>
                                        <span id="valAquaSal" class="text-blue-600 font-mono">25 ppt</span>
                                    </div>
                                    <input type="range" id="inputAquaSal" min="0" max="40" step="1" value="25" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1">
                                    <span>ออกซิเจนในบ่อจริง (Dissolved Oxygen)</span>
                                    <span id="valAquaDo" class="text-emerald-600 font-mono">5.20 mg/L</span>
                                </div>
                                <input type="range" id="inputAquaDo" min="1.0" max="8.0" step="0.1" value="5.2" oninput="updateAquaSimulator()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            </div>
                        </div>

                        <!-- Advisory Card -->
                        <div id="boxAquaAdvisory" class="mt-6 p-4 rounded-2xl bg-sky-500/10 border border-sky-500/30 text-sky-800 text-xs flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-base mt-0.5"></i>
                            <div>
                                <div id="titleAquaAdvisory" class="font-bold">ออกซิเจนสมบูรณ์แบบ (DO > 4.5 mg/L) - ประหยัดพลังงานสูงสุด</div>
                                <div id="descAquaAdvisory" class="mt-0.5 text-gray-600 leading-relaxed">ระบบ VFD ชะลอความถี่ลงเหลือ 38.0 Hz ช่วยลดกำลังไฟฟ้าได้ถึง 56.1% ลดต้นทุนค่าไฟได้หลักหมื่นบาทต่อรอบ</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SIMULATOR 3: Edge AI Plant Vision Simulator (Full Width Card) -->
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-x-1/3 translate-y-1/3"></div>

                <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-gray-100 gap-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-500 text-white flex items-center justify-center text-xl shadow-md shadow-purple-200">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Lab 3: คอมพิวเตอร์วิทัศน์จำแนกโรคพืชและสุขภาพใบ (Edge Vision AI)</h3>
                            <p class="text-xs text-gray-500">TinyML MobileNet / CNN On-Device Inference Simulation บนชิป ESP32-S3</p>
                        </div>
                    </div>
                    <!-- Sample Selector Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button id="pbtn-healthy" onclick="selectPlantSample('healthy')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-seedling text-emerald-500 mr-1"></i> ใบสมบูรณ์
                        </button>
                        <button id="pbtn-fungal_spot" onclick="selectPlantSample('fungal_spot')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 mr-1"></i> โรคใบจุดราสนิม
                        </button>
                        <button id="pbtn-nutrient_def" onclick="selectPlantSample('nutrient_def')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> ขาดธาตุอาหาร
                        </button>
                        <button id="pbtn-pest_damage" onclick="selectPlantSample('pest_damage')" class="plant-btn px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm border border-gray-200">
                            <i class="fa-solid fa-bug text-purple-500 mr-1"></i> เพลี้ยไฟ/ไรแดง
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-6 items-center relative z-10">
                    <!-- Image Preview with Bounding Box -->
                    <div class="md:col-span-5 relative bg-white rounded-2xl p-3 border border-gray-200 shadow-md overflow-hidden group">
                        <img src="assets/images/cv_agri_vision.jpg" alt="Agricultural Vision in Orchard" class="w-full h-64 object-cover rounded-xl">
                        <div id="diag-bbox" class="absolute top-10 left-10 w-44 h-44 border-2 border-dashed rounded-xl flex items-center justify-center transition-all duration-500">
                            <div class="absolute -top-3 left-2 px-2 py-0.5 rounded bg-slate-900 text-[10px] font-mono text-emerald-400 border border-slate-700">
                                ROI: AI-Detect
                            </div>
                        </div>
                    </div>

                    <!-- AI Diagnostic Readouts -->
                    <div class="md:col-span-7 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 id="diag-title" class="text-xl font-bold text-gray-900 font-heading">ใบพืชสุขภาพสมบูรณ์ (Healthy Leaf)</h4>
                            <span id="diag-speed" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-mono font-bold border border-emerald-200">42 ms (ESP32-S3 TinyML)</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">ผลวินิจฉัย (Predicted Class)</div>
                                <div id="diag-class" class="font-bold text-emerald-600 text-lg">Healthy - No Infection</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">ความเชื่อมั่น (Confidence)</div>
                                <div id="diag-conf" class="text-xl font-mono font-black text-gray-900">99.4%</div>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs space-y-2.5">
                            <div><strong class="text-gray-800">อาการทางสรีรวิทยา:</strong> <span id="diag-desc" class="text-gray-600">พืชสังเคราะห์แสงได้เต็มที่ คลอโรฟิลล์สม่ำเสมอ ผิวใบมันวาว ไร้ร่องรอยสปอร์เชื้อรา</span></div>
                            <hr class="border-gray-100">
                            <div><strong class="text-emerald-700">คำแนะนำการแก้ไข xAI:</strong> <span id="diag-action" class="text-gray-700 font-medium">คงการให้น้ำและธาตุอาหารตามตารางมาตรฐาน ไม่จำเป็นต้องใช้สารควบคุมศัตรูพืช</span></div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================================================= -->
        <!-- 5. ATD3.5-S3 HARDWARE & 10 SCREENS SHOWCASE                                -->
        <!-- ========================================================================= -->
