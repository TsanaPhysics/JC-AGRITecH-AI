<!-- ========================================================================= -->
<!-- MODAL: WIDGET & SENSOR CATALOG CUSTOMIZER (ULTRA-FLEXIBLE DASHBOARD)      -->
<!-- ========================================================================= -->
<div id="modalWidgetCatalog" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="glass-panel rounded-3xl p-6 max-w-2xl w-full border border-cyan-500/40 shadow-2xl space-y-5 bg-[#090e1c] max-h-[90vh] overflow-y-auto widget-enter">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-sliders text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold font-tech text-white">ปรับแต่งหน้าจอ &amp; เลือกเซนเซอร์ (Dashboard Customizer)</h3>
                    <p class="text-xs text-slate-400">เลือกเซนเซอร์และการควบคุมที่ต้องการแสดงผลได้อย่างอิสระ</p>
                </div>
            </div>
            <button onclick="closeWidgetCatalogModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Section 1: Grid Columns Selector -->
        <div class="space-y-2">
            <label class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                <i class="fa-solid fa-border-all text-cyan-400"></i> จำนวนคอลัมน์การจัดวาง (Grid Columns)
            </label>
            <div class="grid grid-cols-4 gap-2 text-xs font-mono">
                <button type="button" onclick="selectGridCols(2)" class="col-btn p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:border-cyan-500 text-center font-bold" data-cols="2">2 Columns</button>
                <button type="button" onclick="selectGridCols(3)" class="col-btn p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:border-cyan-500 text-center font-bold" data-cols="3">3 Columns</button>
                <button type="button" onclick="selectGridCols(4)" class="col-btn p-2 rounded-xl bg-cyan-950 border border-cyan-500 text-cyan-300 text-center font-bold" data-cols="4">4 Columns</button>
                <button type="button" onclick="selectGridCols(6)" class="col-btn p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:border-cyan-500 text-center font-bold" data-cols="6">6 Columns</button>
            </div>
        </div>

        <!-- Section 2: Sensor & Widget Checklist -->
        <div class="space-y-2">
            <label class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                <i class="fa-solid fa-square-check text-emerald-400"></i> เลือกวิดเจ็ตและเซนเซอร์ที่ต้องการแสดงบนหน้าจอ
            </label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-72 overflow-y-auto pr-1 text-xs">
                
                <!-- Widget 1: SHT45 -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="weather_microclimate" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">สภาพอากาศ (SHT45)</span>
                            <span class="text-[10px] text-slate-400">อุณหภูมิ, ความชื้น RH, จุดน้ำค้าง</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-temperature-half text-amber-400"></i>
                </label>

                <!-- Widget 2: VPD -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="vpd_transpiration" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">แรงดึงระเหยน้ำ (VPD)</span>
                            <span class="text-[10px] text-slate-400">Penman FAO-56, VPsat, VPact</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-wind text-cyan-400"></i>
                </label>

                <!-- Widget 3: Soil 7-in-1 Root -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="soil_7in1_root" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">ดินเขตราก (Soil 7-in-1)</span>
                            <span class="text-[10px] text-slate-400">pH รากลึก, ชื้นเขตราก, EC, Temp</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-seedling text-lime-400"></i>
                </label>

                <!-- Widget 4: Surface Soil Stick -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="surface_soil_stick" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">ผิวดิน (Soil Stick)</span>
                            <span class="text-[10px] text-slate-400">ความชื้น 0-10cm, pH ผิวดิน, ADC</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i>
                </label>

                <!-- Widget 5: Solar Radiation BH1750 -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="solar_radiation" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">แสงแดด &amp; รังสี (BH1750)</span>
                            <span class="text-[10px] text-slate-400">PAR Lux, Solar W/m², kLux</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-sun text-yellow-400"></i>
                </label>

                <!-- Widget 6: TinyML Edge AI NPK -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="ai_npk_calibration" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">โมเดล AI ธาตุอาหาร NPK</span>
                            <span class="text-[10px] text-slate-400">Available N, Bray II P, Exch K</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-flask-vial text-emerald-400"></i>
                </label>

                <!-- Widget 7: Dual-Engine Sensor vs AI Comparison -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="ai_sensor_comparison" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">แผงเปรียบเทียบเซนเซอร์ VS AI</span>
                            <span class="text-[10px] text-slate-400">Raw Probe vs TinyML Inference</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-code-compare text-purple-400"></i>
                </label>

                <!-- Widget 8: 4-Channel Relays Control -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="relays_control" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">คอนโซลสั่งการรีเลย์ 4 ช่อง</span>
                            <span class="text-[10px] text-slate-400">ปั๊มน้ำ, วาล์วผิวดิน, พ่นหมอก</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-power-off text-rose-400"></i>
                </label>

                <!-- Widget 9: 24h Real-time Trends Chart -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="trend_charts" class="widget-toggle accent-cyan-500 w-4 h-4 rounded" checked>
                        <div>
                            <span class="font-bold text-white block">กราฟแนวโน้ม 24 ชั่วโมง</span>
                            <span class="text-[10px] text-slate-400">อุณหภูมิ, ความชื้น, ดิน Real-time</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chart-line text-cyan-400"></i>
                </label>

                <!-- Widget 10: OV2640 YOLOv8 Vision -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="camera_vision" class="widget-toggle accent-cyan-500 w-4 h-4 rounded">
                        <div>
                            <span class="font-bold text-white block">กล้อง OV2640 AI Vision</span>
                            <span class="text-[10px] text-slate-400">ตรวจโรคใบพืช YOLOv8 Edge Vision</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-camera text-indigo-400"></i>
                </label>

                <!-- Widget 11: Wearable Vital Signs -->
                <label class="p-3 rounded-2xl bg-slate-900/90 border border-white/5 hover:border-cyan-500/30 flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" value="farmer_vital_signs" class="widget-toggle accent-rose-500 w-4 h-4 rounded">
                        <div>
                            <span class="font-bold text-white block">สุขภาพเกษตรกร (Vital Signs)</span>
                            <span class="text-[10px] text-slate-400">ชีพจร Heart Rate, SpO2, Heat Stress</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-heart-pulse text-rose-500"></i>
                </label>

            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="flex items-center justify-between pt-3 border-t border-white/10">
            <button type="button" onclick="resetDefaultLayout()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold font-tech transition">
                คืนค่าเริ่มต้น
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeWidgetCatalogModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold font-tech transition">
                    ยกเลิก
                </button>
                <button type="button" onclick="saveCustomLayout()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white text-xs font-bold font-tech transition shadow-md">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> บันทึกการจัดแต่งหน้าจอ
                </button>
            </div>
        </div>

    </div>
</div>
