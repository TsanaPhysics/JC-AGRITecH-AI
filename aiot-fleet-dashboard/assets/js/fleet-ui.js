/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - UI Renderer & Interaction Engine
 * ============================================================================
 */

const FleetUI = {
    trendChartInstance: null,

    // =========================================================================
    // 0. RENDER INSTRUCTOR MASTER CARD (/leqs-workshop/dashboard Sync)
    // =========================================================================
    renderMasterCard() {
        const master = FleetState.getMasterBoard();
        if (!master) return;
        const t = master.telemetry || {};

        const setTxt = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.innerText = val;
        };

        setTxt('masterHeroIp', `${master.ip_address || '192.168.0.111'}:${master.port || 8500}`);
        setTxt('masterHeroSsid', master.ssid || 'JC_Home');
        setTxt('masterHeroSyncTime', t.timestamp ? (t.timestamp.split(' ')[1] || t.timestamp) : 'สดเมื่อสักครู่');

        setTxt('masterValTemp', t.air_temp !== undefined ? Number(t.air_temp).toFixed(1) : '24.4');
        setTxt('masterValHum', (t.air_hum !== undefined ? Number(t.air_hum).toFixed(1) : '65.7') + '%');
        setTxt('masterValDew', (t.dew_point !== undefined ? Number(t.dew_point).toFixed(1) : '17.6') + '°');
        setTxt('masterValVpd', t.vpd !== undefined ? Number(t.vpd).toFixed(2) : '1.05');

        setTxt('masterValStickMoist', t.surface_moist !== undefined ? Number(t.surface_moist).toFixed(1) : '60.7');
        setTxt('masterValStickPh', t.surface_ph !== undefined ? Number(t.surface_ph).toFixed(2) : '3.20');
        setTxt('masterValStickAdc', t.stick_adc || 2031);

        setTxt('masterValSoilPh', t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(2) : '7.60');
        setTxt('masterValSoilMoist', (t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) : '0.0') + '%');
        setTxt('masterValSoilEc', (t.soil_ec !== undefined ? Number(t.soil_ec).toFixed(1) : '0') + ' µS');

        setTxt('masterValLux', t.lux !== undefined ? Number(t.lux).toFixed(1) : '35.8');
        setTxt('masterValSolarRad', (t.solar_radiation !== undefined ? Number(t.solar_radiation).toFixed(2) : '0.28') + ' W/m²');

        setTxt('masterValAiPh', t.ai_calibrated_ph !== undefined ? Number(t.ai_calibrated_ph).toFixed(2) : '8.36');
        const n = Math.round(t.ai_nitrogen !== undefined ? t.ai_nitrogen : 17);
        const p = Math.round(t.ai_phosphorus !== undefined ? t.ai_phosphorus : 11);
        const k = Math.round(t.ai_potassium !== undefined ? t.ai_potassium : 10);
        setTxt('masterValAiNpk', `${n}-${p}-${k}`);
        
        const conf = t.ai_confidence !== undefined ? Number(t.ai_confidence) : 74.5;
        setTxt('masterValAiConf', (conf <= 1.0 ? (conf * 100) : conf).toFixed(1) + '%');

        // Master Relay Buttons LED State
        [1, 2, 3, 4].forEach(ch => {
            const btn = document.getElementById(`masterRelayBtn${ch}`);
            if (btn) {
                const state = Number(master[`relay_${ch}`]) === 1;
                if (state) {
                    btn.className = 'px-2 py-1 rounded-lg bg-emerald-500 text-slate-950 font-bold transition shadow-md shadow-emerald-500/20';
                } else {
                    btn.className = 'px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition';
                }
            }
        });
    },

    // =========================================================================
    // 1. RENDER BOARD SELECTOR (TOP NODE MATRIX WITH WORKSHOP FILTERING)
    // =========================================================================
    renderBoardSelector() {
        const container = document.getElementById('boardPillsContainer');
        const countBadge = document.getElementById('boardCountBadge');
        if (!container) return;

        if (countBadge) {
            countBadge.innerText = `${FleetState.boards.length} Nodes`;
        }

        if (!FleetState.boards || FleetState.boards.length === 0) {
            container.innerHTML = `
                <div class="col-span-full p-4 rounded-2xl glass-panel text-center text-slate-400 text-xs">
                    ไม่พบบอร์ด ESP32 ในระบบ กรุณากดปุ่ม <strong>"เพิ่มบอร์ด ESP32"</strong> เพื่อเริ่มต้น
                </div>
            `;
            return;
        }

        let displayBoards = FleetState.boards;

        // 1. Apply Workshop Filter
        if (FleetState.workshopFilter === 'master') {
            displayBoards = displayBoards.filter(b => Number(b.is_master) === 1 || Number(b.id) === 1);
        } else if (FleetState.workshopFilter === 'zoneA') {
            displayBoards = displayBoards.filter(b => (b.zone && b.zone.includes('Zone A')) || (Number(b.id) >= 2 && Number(b.id) <= 6));
        } else if (FleetState.workshopFilter === 'zoneB') {
            displayBoards = displayBoards.filter(b => (b.zone && b.zone.includes('Zone B')) || (Number(b.id) >= 7 && Number(b.id) <= 11));
        } else if (FleetState.workshopFilter === 'zoneC') {
            displayBoards = displayBoards.filter(b => (b.zone && b.zone.includes('Zone C')) || (Number(b.id) >= 12 && Number(b.id) <= 16));
        }

        // 2. Apply Search Query
        if (FleetState.searchQuery) {
            const q = FleetState.searchQuery;
            displayBoards = displayBoards.filter(b => 
                (b.name && b.name.toLowerCase().includes(q)) ||
                (b.code && b.code.toLowerCase().includes(q)) ||
                (b.zone && b.zone.toLowerCase().includes(q))
            );
        }

        if (displayBoards.length === 0) {
            container.innerHTML = `
                <div class="col-span-full p-4 rounded-2xl glass-panel text-center text-slate-400 text-xs">
                    ไม่พบบอร์ดที่ตรงกับเงื่อนไขการค้นหา "${escapeHtml(FleetState.searchQuery)}"
                </div>
            `;
            return;
        }

        container.innerHTML = displayBoards.map(b => {
            const isActive = Number(b.id) === Number(FleetState.activeBoardId);
            const isOnline = b.status === 'online';
            const isMaster = Number(b.is_master) === 1 || Number(b.id) === 1;
            const t = b.telemetry || {};

            let cardBorder = 'border-white/5 bg-slate-900/70 hover:border-white/20 hover:bg-slate-900/90';
            if (isActive) {
                cardBorder = isMaster
                    ? 'border-amber-400/80 bg-gradient-to-br from-amber-950/70 via-slate-900 to-indigo-950/50 shadow-lg shadow-amber-500/15 ring-1 ring-amber-400/40'
                    : 'border-cyan-400/80 bg-gradient-to-br from-cyan-950/70 via-slate-900 to-indigo-950/50 shadow-lg shadow-cyan-500/15 ring-1 ring-cyan-400/40';
            } else if (isMaster) {
                cardBorder = 'border-amber-500/30 bg-gradient-to-br from-amber-950/30 via-slate-900 to-slate-950 hover:border-amber-500/50 shadow-xs';
            }

            const statusDot = isOnline
                ? '<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>'
                : '<span class="w-2 h-2 rounded-full bg-rose-500"></span>';

            const activeRelaysCount = [b.relay_1, b.relay_2, b.relay_3, b.relay_4].filter(r => Number(r) === 1).length;

            // Group & Sensor Preset Label for Workshop
            const groupBadge = isMaster
                ? `<span class="text-[9px] font-mono px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 font-bold flex items-center gap-1"><i class="fa-solid fa-crown text-[8px]"></i> สาธิตหลัก</span>`
                : `<span class="text-[9px] font-mono px-2 py-0.5 rounded-full bg-cyan-950 text-cyan-300 border border-cyan-500/30 font-bold">กลุ่ม ${b.id - 1}</span>`;

            const hasSht = Number(b.has_sht45 ?? 1) === 1;
            const hasSoil7 = Number(b.has_soil_7in1 ?? 1) === 1;
            const hasStick = Number(b.has_soil_stick ?? 1) === 1;
            const preset = b.sensor_preset || 'full';

            const presetBadges = {
                'full': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-emerald-950 text-emerald-300 border border-emerald-500/40">ครบชุด</span>',
                'soil_npk': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-lime-950 text-lime-300 border border-lime-500/40">ดิน NPK</span>',
                'greenhouse': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-cyan-950 text-cyan-300 border border-cyan-500/40">โรงเรือน</span>',
                'basic': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-amber-950 text-amber-300 border border-amber-500/40">พื้นฐาน</span>',
                'health_iot': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-rose-950 text-rose-300 border border-rose-500/40">สุขภาพ</span>',
                'custom': '<span class="text-[8px] font-mono px-1.5 py-0.2 rounded bg-purple-950 text-purple-300 border border-purple-500/40">กำหนดเอง</span>'
            };
            const presetTag = presetBadges[preset] || presetBadges['full'];

            return `
                <div onclick="selectBoard(${b.id})" class="p-3.5 rounded-2xl glass-panel border transition-all cursor-pointer relative group ${cardBorder}">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-1.5 overflow-hidden">
                            ${groupBadge}
                            <span class="text-xs font-mono font-bold ${isActive ? (isMaster ? 'text-amber-300' : 'text-cyan-300') : 'text-slate-200'} truncate">
                                ${escapeHtml(b.code || 'ESP32')}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            ${statusDot}
                            <span class="text-[10px] font-mono ${isOnline ? 'text-emerald-400' : 'text-rose-400'} font-bold">
                                ${isOnline ? 'ONLINE' : 'OFFLINE'}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-1 mb-1">
                        <div class="text-xs font-bold text-white truncate" title="${escapeHtml(b.name || '')}">
                            ${escapeHtml(b.name || 'แปลงเกษตร')}
                        </div>
                        ${presetTag}
                    </div>

                    <div class="text-[10px] font-tech text-slate-400 truncate mb-2">
                        ${escapeHtml(b.zone || '')}
                    </div>

                    <!-- Mini Live Telemetry Chips (Sensor-Aware) -->
                    <div class="grid grid-cols-3 gap-1.5 text-[11px] font-mono mb-2.5">
                        <div class="p-1 rounded-lg bg-slate-950/60 border border-white/5 text-center">
                            <span class="text-[9px] text-slate-400 block">${hasSht ? 'Air T' : '<span class="text-slate-500">Air (ปิด)</span>'}</span>
                            <span class="${hasSht ? 'text-cyan-300 font-bold' : 'text-slate-500'}">${hasSht && t.air_temp !== undefined ? Number(t.air_temp).toFixed(1) + '°' : '--'}</span>
                        </div>
                        <div class="p-1 rounded-lg bg-slate-950/60 border border-white/5 text-center">
                            <span class="text-[9px] text-slate-400 block">${hasSoil7 ? 'pH ดิน' : '<span class="text-slate-500">pH (ปิด)</span>'}</span>
                            <span class="${hasSoil7 ? 'text-lime-300 font-bold' : 'text-slate-500'}">${hasSoil7 && t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(1) : '--'}</span>
                        </div>
                        <div class="p-1 rounded-lg bg-slate-950/60 border border-white/5 text-center">
                            <span class="text-[9px] text-slate-400 block">${(hasSoil7 || hasStick) ? (hasStick ? 'ชื้นดิน (จอ)' : 'ชื้นดิน') : '<span class="text-slate-500">ชื้น (ปิด)</span>'}</span>
                            <span class="${(hasSoil7 || hasStick) ? 'text-emerald-300 font-bold' : 'text-slate-500'}">${(hasSoil7 || hasStick) ? (((hasStick && (t.surface_moist !== undefined || t.stick_moisture !== undefined)) ? Number(t.surface_moist ?? t.stick_moisture).toFixed(1) : (t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) : '60.7')) + '%') : '--'}</span>
                        </div>
                    </div>

                    <!-- Footer: IP & Relays Badge -->
                    <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono border-t border-white/5 pt-1.5">
                        <span class="truncate max-w-[130px]"><i class="fa-solid fa-wifi text-[9px] text-cyan-400 mr-1"></i>${escapeHtml(b.ip_address || '0.0.0.0')}</span>
                        <span class="px-1.5 py-0.5 rounded-md ${activeRelaysCount > 0 ? 'bg-amber-950 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-400'}">
                            <i class="fa-solid fa-bolt text-[9px] mr-1"></i>${activeRelaysCount}/4
                        </span>
                    </div>

                    ${isActive ? `<div class="absolute -top-1 -right-1 w-3 h-3 ${isMaster ? 'bg-amber-400' : 'bg-cyan-400'} rounded-full blur-[2px]"></div>` : ''}
                </div>
            `;
        }).join('');
    },

    // =========================================================================
    // 2. RENDER CURRENT ACTIVE VIEW (FOCUS / FLEET GRID / COMPARATIVE)
    // =========================================================================
    renderCurrentView() {
        const focusView = document.getElementById('viewContainerFocus');
        const gridView = document.getElementById('viewContainerFleetGrid');
        const compView = document.getElementById('viewContainerComparative');

        // Update Nav Segmented Control styling
        const btnFocus = document.getElementById('btnViewFocus');
        const btnFleet = document.getElementById('btnViewFleet');
        const btnCompare = document.getElementById('btnViewCompare');

        const activeBtnClasses = ['bg-cyan-500/20', 'text-cyan-300', 'border-cyan-500/40', 'shadow-xs'];
        const inactiveBtnClasses = ['text-slate-400', 'hover:text-white'];

        [btnFocus, btnFleet, btnCompare].forEach(btn => {
            if (btn) {
                btn.className = 'px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 border border-transparent ' + inactiveBtnClasses.join(' ');
            }
        });

        if (FleetState.viewMode === 'focus') {
            if (focusView) focusView.classList.remove('hidden');
            if (gridView) gridView.classList.add('hidden');
            if (compView) compView.classList.add('hidden');
            if (btnFocus) {
                btnFocus.className = 'px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 border ' + activeBtnClasses.join(' ');
            }
            this.renderFocusView();
        } else if (FleetState.viewMode === 'fleet_grid') {
            if (focusView) focusView.classList.add('hidden');
            if (gridView) gridView.classList.remove('hidden');
            if (compView) compView.classList.add('hidden');
            if (btnFleet) {
                btnFleet.className = 'px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 border ' + activeBtnClasses.join(' ');
            }
            this.renderFleetGrid();
        } else if (FleetState.viewMode === 'comparative') {
            if (focusView) focusView.classList.add('hidden');
            if (gridView) gridView.classList.add('hidden');
            if (compView) compView.classList.remove('hidden');
            if (btnCompare) {
                btnCompare.className = 'px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 border ' + activeBtnClasses.join(' ');
            }
            this.renderComparative();
        }
    },

    // =========================================================================
    // 3. RENDER FOCUS VIEW (DETAILED TELEMETRY & WIDGETS FOR ONE BOARD)
    // =========================================================================
    renderFocusView() {
        const board = FleetState.getActiveBoard();
        if (!board) return;

        const t = board.telemetry || {};

        // 3.1 Update Header Banner Metadata
        setText('activeBoardCodeBadge', board.code || 'ESP32');
        setText('activeBoardZoneBadge', board.zone || 'Zone Default');
        setText('activeBoardModelBadge', board.model || 'ESP32-S3');
        setText('activeBoardTitle', board.name || 'แปลงเกษตรอัจฉริยะ');
        setText('activeBoardIp', `${board.ip_address || '192.168.0.x'}:${board.port || 8500}`);
        setText('activeBoardMac', board.mac_address || '48:27:E2:B4:8A:XX');
        setText('activeBoardRssi', `${t.rssi !== undefined ? t.rssi : -65} dBm`);
        setText('activeBoardLastSeen', t.timestamp ? formatRelativeTime(t.timestamp) : 'สดเมื่อสักครู่');

        // 3.2 Update Quick Relays in Banner
        for (let ch = 1; ch <= 4; ch++) {
            const state = Number(board[`relay_${ch}`]) === 1;
            const btn = document.getElementById(`quickRelayBtn${ch}`);
            const led = document.getElementById(`quickRelayLed${ch}`);
            if (btn && led) {
                if (state) {
                    btn.className = 'px-3 py-1.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-xs font-mono font-bold text-amber-300 border border-amber-500/40 transition flex items-center gap-1.5';
                    led.className = 'w-2 h-2 rounded-full bg-amber-400 animate-pulse';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-slate-300 border border-transparent transition flex items-center gap-1.5';
                    led.className = 'w-2 h-2 rounded-full bg-slate-600';
                }
            }
        }

        // 3.3 Update Dual-Engine AI Inference Panel
        setText('compareBoardNameText', `${board.code} (${board.name})`);
        setText('compareAiConfBadge', `${t.ai_confidence ? Number(t.ai_confidence).toFixed(1) : '78.5'}%`);
        setText('aiCompRawPh', t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(2) : '8.50');
        setText('aiCompAiPh', t.ai_calibrated_ph !== undefined ? Number(t.ai_calibrated_ph).toFixed(2) : '9.44');
        
        const phDiff = (Number(t.ai_calibrated_ph || 9.44) - Number(t.soil_ph || 8.50)).toFixed(2);
        setText('aiCompDiffPh', (phDiff > 0 ? '+' : '') + phDiff);

        const stickVal = t.surface_moist !== undefined ? Number(t.surface_moist) : (t.stick_moisture !== undefined ? Number(t.stick_moisture) : 60.7);
        const deepVal = t.soil_moisture !== undefined ? Number(t.soil_moisture) : 2.6;
        setText('aiCompStickMoist', `${stickVal.toFixed(1)}%`);
        setText('aiCompRawMoist', `${deepVal.toFixed(1)}%`);
        setText('aiCompAiMoist', `${t.ai_fused_moisture !== undefined ? Number(t.ai_fused_moisture).toFixed(1) : ((stickVal + deepVal)/2).toFixed(1)}%`);

        setText('aiCompRawN', t.soil_n !== undefined ? Number(t.soil_n).toFixed(1) : '0.0');
        setText('aiCompAiN', t.ai_nitrogen !== undefined ? Number(t.ai_nitrogen).toFixed(1) : '21.8');

        setText('aiCompRawP', t.soil_p !== undefined ? Number(t.soil_p).toFixed(1) : '0.0');
        setText('aiCompAiP', t.ai_phosphorus !== undefined ? Number(t.ai_phosphorus).toFixed(1) : '13.9');

        setText('aiCompRawK', t.soil_k !== undefined ? Number(t.soil_k).toFixed(1) : '0.0');
        setText('aiCompAiK', t.ai_potassium !== undefined ? Number(t.ai_potassium).toFixed(1) : '8.0');

        setText('aiCompRawVpd', t.vpd !== undefined ? Number(t.vpd).toFixed(2) : '1.35');
        
        // Agronomy Alert Text update based on Board & Telemetry
        const alertEl = document.getElementById('aiCompAgronomyAlertText');
        if (alertEl) {
            if (Number(t.soil_moisture) < 15) {
                alertEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i> โหนด <strong>${board.code}</strong>: ตรวจพบความชื้นเขตรากลึกต่ำวิกฤต (${Number(t.soil_moisture).toFixed(1)}%) • pH โพรบ ${Number(t.soil_ph).toFixed(2)} (AI ปรับแก้ ${Number(t.ai_calibrated_ph || 9.44).toFixed(2)}) แนะนำเปิดวาล์วรดน้ำ CH-01 หรือ CH-02`;
            } else if (Number(t.soil_ph) < 5.5) {
                alertEl.innerHTML = `<i class="fa-solid fa-circle-exclamation text-rose-400 mr-1"></i> โหนด <strong>${board.code}</strong>: ดินมีสภาวะกรดจัด (pH ${Number(t.soil_ph).toFixed(2)}) แนะนำตรวจเช็คการให้ปุ๋ยเคมีหรือปรับสภาพดินด้วยปูนโดโลไมท์`;
            } else {
                alertEl.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i> โหนด <strong>${board.code}</strong>: สภาพแปลงสมบูรณ์ ความชื้นราก ${Number(t.soil_moisture).toFixed(1)}% • pH ${Number(t.soil_ph).toFixed(2)} • สภาพอากาศและแรงดึงระเหยน้ำ (VPD ${Number(t.vpd).toFixed(2)} kPa) อยู่ในเกณฑ์อุดมคติ`;
            }
        }

        // 3.4 Render Modular Widgets Grid
        this.renderDynamicWidgets(board, t);
    },

    // =========================================================================
    // 4. RENDER MODULAR SENSOR WIDGETS
    // =========================================================================
    renderDynamicWidgets(board, t) {
        // 1. Render Active Sensor Capabilities Banner
        const banner = document.getElementById('boardSensorCapabilityBanner');
        if (banner) {
            const presetNames = {
                'full': '🌟 ครบวงจร (Full Suite)',
                'soil_npk': '🪴 ดิน NPK (เฉพาะทางดิน)',
                'greenhouse': '🏡 โรงเรือน (SHT45 + แสง)',
                'basic': '⚡ พื้นฐาน (เฉพาะอากาศ & รีเลย์)',
                'health_iot': '🩺 สุขภาพเกษตรกร & สิ่งแวดล้อม',
                'custom': '🛠️ กำหนดเอง (Custom)'
            };
            const presetLabel = presetNames[board.sensor_preset] || presetNames['full'];

            const sensors = [
                { key: 'has_sht45', icon: 'fa-temperature-half', color: 'text-amber-400', label: 'SHT45 อากาศ' },
                { key: 'has_soil_7in1', icon: 'fa-seedling', color: 'text-lime-400', label: 'Soil 7in1 ราก' },
                { key: 'has_soil_stick', icon: 'fa-wand-magic-sparkles', color: 'text-amber-400', label: 'Soil Stick ผิว' },
                { key: 'has_bh1750', icon: 'fa-sun', color: 'text-yellow-400', label: 'BH1750 แสง' },
                { key: 'has_ai_npk', icon: 'fa-flask-vial', color: 'text-emerald-400', label: 'TinyML NPK' },
                { key: 'has_camera', icon: 'fa-camera', color: 'text-indigo-400', label: 'กล้อง OV2640' },
                { key: 'has_vital_signs', icon: 'fa-heart-pulse', color: 'text-rose-400', label: 'สัญญาณชีพ' }
            ];

            const sensorChips = sensors.map(s => {
                const active = Number(board[s.key]) === 1;
                return active 
                    ? `<span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-200 border border-slate-700 flex items-center gap-1 font-mono text-[11px]"><i class="fa-solid ${s.icon} ${s.color}"></i> ${s.label}</span>`
                    : `<span class="px-2 py-0.5 rounded-lg bg-slate-950/60 text-slate-600 border border-white/5 line-through flex items-center gap-1 font-mono text-[11px] opacity-40"><i class="fa-solid ${s.icon}"></i> ${s.label}</span>`;
            }).join('');

            banner.innerHTML = `
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold font-tech text-cyan-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-microchip text-cyan-400"></i> เซนเซอร์ประจำกลุ่ม ${board.name || board.code}:
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono bg-cyan-950/80 text-cyan-300 border border-cyan-500/40">
                        ${presetLabel}
                    </span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        ${sensorChips}
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openAdminFleetModal(${board.id})" class="px-3 py-1 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-bold font-tech transition flex items-center gap-1">
                        <i class="fa-solid fa-sliders text-amber-400"></i> ปรับแต่งเซนเซอร์กลุ่มนี้
                    </button>
                </div>
            `;
        }

        const grid = document.getElementById('dynamicWidgetsGrid');
        if (!grid) return;

        // Apply selected grid columns class
        const colMap = {
            2: 'grid-cols-1 md:grid-cols-2',
            3: 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3',
            4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
            6: 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6'
        };
        grid.className = `grid gap-4 ${colMap[FleetState.gridColumns] || colMap[4]}`;

        let widgets = [...(FleetState.visibleWidgets || [])];
        if (Number(board.has_vital_signs) === 1 && !widgets.includes('farmer_vital_signs')) {
            widgets.push('farmer_vital_signs');
        }

        let html = '';
        let activeWidgetCount = 0;

        widgets.forEach(wId => {
            // Per-board sensor filter check
            if ((wId === 'weather_microclimate' || wId === 'vpd_transpiration') && Number(board.has_sht45) === 0) return;
            if (wId === 'soil_7in1_root' && Number(board.has_soil_7in1) === 0) return;
            if (wId === 'surface_soil_stick' && Number(board.has_soil_stick) === 0) return;
            if (wId === 'solar_radiation' && Number(board.has_bh1750) === 0) return;
            if (wId === 'ai_npk_calibration' && Number(board.has_ai_npk) === 0) return;
            if (wId === 'camera_vision' && Number(board.has_camera) === 0) return;
            if (wId === 'farmer_vital_signs' && Number(board.has_vital_signs) === 0) return;

            activeWidgetCount++;
            switch(wId) {
                case 'weather_microclimate':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-temperature-half text-amber-400"></i> สภาพอากาศ (SHT45)
                                </span>
                                <span class="text-[10px] font-mono text-cyan-400 bg-cyan-950/80 px-2 py-0.5 rounded-full border border-cyan-500/30">Microclimate</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">อุณหภูมิอากาศ</span>
                                    <span class="text-2xl font-black text-amber-300">${t.air_temp !== undefined ? Number(t.air_temp).toFixed(1) : '28.4'}<span class="text-xs font-normal text-slate-400">°C</span></span>
                                </div>
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">ความชื้นสัมพัทธ์</span>
                                    <span class="text-2xl font-black text-cyan-300">${t.air_hum !== undefined ? Number(t.air_hum).toFixed(1) : '65.2'}<span class="text-xs font-normal text-slate-400">%</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>จุดน้ำค้าง (Dew Point): <strong class="text-white">${((t.air_temp || 28.4) - ((100 - (t.air_hum || 65.2)) / 5)).toFixed(1)}°C</strong></span>
                                <span class="text-emerald-400"><i class="fa-solid fa-shield-halved mr-1"></i>ปกติ</span>
                            </div>
                        </div>
                    `;
                    break;

                case 'vpd_transpiration':
                    const vpdVal = Number(t.vpd || 1.35);
                    const vpdStatus = vpdVal < 0.8 ? 'ความชื้นสูงเกินไป (Low Transp)' : (vpdVal > 1.6 ? 'เครียดน้ำสูง (High Deficit)' : 'อัตราคายน้ำสมบูรณ์ (Optimal)');
                    const vpdColor = vpdVal < 0.8 ? 'text-cyan-400' : (vpdVal > 1.6 ? 'text-amber-400' : 'text-emerald-400');
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-wind text-cyan-400"></i> แรงดึงระเหยน้ำ (VPD)
                                </span>
                                <span class="text-[10px] font-mono text-purple-400 bg-purple-950/80 px-2 py-0.5 rounded-full border border-purple-500/30">FAO-56</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-950/80 border border-white/5 text-center font-mono">
                                <span class="text-[10px] text-slate-400 block font-sans">Vapor Pressure Deficit</span>
                                <span class="text-3xl font-black text-cyan-300">${vpdVal.toFixed(2)}<span class="text-sm font-normal text-slate-400 ml-1">kPa</span></span>
                                <span class="text-[10px] font-tech font-bold ${vpdColor} block mt-0.5">${vpdStatus}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>VPsat: <strong class="text-slate-300">3.88 kPa</strong></span>
                                <span>VPact: <strong class="text-slate-300">2.53 kPa</strong></span>
                            </div>
                        </div>
                    `;
                    break;

                case 'soil_7in1_root':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-seedling text-lime-400"></i> ดินเขตราก (Soil 7-in-1)
                                </span>
                                <span class="text-[10px] font-mono text-lime-400 bg-lime-950/80 px-2 py-0.5 rounded-full border border-lime-500/30">Root 10-30cm</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">pH รากลึก</span>
                                    <span class="text-2xl font-black text-lime-400">${t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(2) : '8.50'}</span>
                                </div>
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">ความชื้นราก</span>
                                    <span class="text-2xl font-black text-cyan-300">${t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) : '2.6'}<span class="text-xs font-normal text-slate-400">%</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>EC: <strong class="text-amber-400">${t.soil_ec !== undefined ? Number(t.soil_ec).toFixed(2) : '0.42'} mS/cm</strong></span>
                                <span>Temp: <strong class="text-slate-300">${t.soil_temp !== undefined ? Number(t.soil_temp).toFixed(1) : '27.8'}°C</strong></span>
                            </div>
                        </div>
                    `;
                    break;

                case 'surface_soil_stick':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i> ดินชั้นบน (Soil Stick)
                                </span>
                                <span class="text-[10px] font-mono text-amber-400 bg-amber-950/80 px-2 py-0.5 rounded-full border border-amber-500/30">Depth 0-10cm</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">pH ผิวดิน</span>
                                    <span class="text-2xl font-black text-amber-400">${t.surface_ph !== undefined ? Number(t.surface_ph).toFixed(2) : '3.03'}</span>
                                </div>
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">ความชื้นผิว</span>
                                    <span class="text-2xl font-black text-cyan-300">${t.surface_moist !== undefined ? Number(t.surface_moist).toFixed(1) : '20.5'}<span class="text-xs font-normal text-slate-400">%</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>Dual Gradient: <strong class="text-amber-400">ความชันสูง 5.47 pH</strong></span>
                                <span class="text-cyan-400">ADC Calibrated</span>
                            </div>
                        </div>
                    `;
                    break;

                case 'solar_radiation':
                    const luxVal = Number(t.lux || 35200);
                    const solarW = Number(t.solar_radiation || (luxVal * 0.0079)).toFixed(1);
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-sun text-yellow-400"></i> แสงแดด & รังสี (BH1750)
                                </span>
                                <span class="text-[10px] font-mono text-yellow-400 bg-yellow-950/80 px-2 py-0.5 rounded-full border border-yellow-500/30">PAR Sensor</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">ความสว่าง</span>
                                    <span class="text-2xl font-black text-yellow-300">${(luxVal / 1000).toFixed(1)}<span class="text-xs font-normal text-slate-400 ml-1">kLux</span></span>
                                </div>
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">Solar Radiation</span>
                                    <span class="text-2xl font-black text-amber-400">${solarW}<span class="text-xs font-normal text-slate-400 ml-1">W/m²</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>DLI สังเคราะห์แสง: <strong class="text-emerald-400">28.4 mol/m²/d</strong></span>
                                <span class="text-yellow-400">แดดจัด</span>
                            </div>
                        </div>
                    `;
                    break;

                case 'ai_npk_calibration':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-flask-vial text-emerald-400"></i> ธาตุอาหาร Edge AI (NPK)
                                </span>
                                <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded-full border border-emerald-500/30">Available</span>
                            </div>
                            <div class="grid grid-cols-3 gap-1.5 text-center font-mono">
                                <div class="p-2 rounded-2xl bg-slate-950/80 border border-emerald-500/20">
                                    <span class="text-[10px] text-emerald-400 block font-bold">N</span>
                                    <span class="text-lg font-black text-white">${t.ai_nitrogen !== undefined ? Number(t.ai_nitrogen).toFixed(1) : '21.8'}</span>
                                    <span class="text-[8px] text-slate-500 block">mg/kg</span>
                                </div>
                                <div class="p-2 rounded-2xl bg-slate-950/80 border border-cyan-500/20">
                                    <span class="text-[10px] text-cyan-400 block font-bold">P</span>
                                    <span class="text-lg font-black text-white">${t.ai_phosphorus !== undefined ? Number(t.ai_phosphorus).toFixed(1) : '13.9'}</span>
                                    <span class="text-[8px] text-slate-500 block">Bray II</span>
                                </div>
                                <div class="p-2 rounded-2xl bg-slate-950/80 border border-amber-500/20">
                                    <span class="text-[10px] text-amber-400 block font-bold">K</span>
                                    <span class="text-lg font-black text-white">${t.ai_potassium !== undefined ? Number(t.ai_potassium).toFixed(1) : '8.0'}</span>
                                    <span class="text-[8px] text-slate-500 block">Exch.</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>ความอุดมสมบูรณ์: <strong class="text-emerald-400">ปานกลาง-สมบูรณ์</strong></span>
                                <span class="text-purple-400">TinyML</span>
                            </div>
                        </div>
                    `;
                    break;

                case 'relays_control':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950 col-span-1 md:col-span-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-power-off text-rose-400"></i> คอนโซลสั่งการรีเลย์ (Direct Actuator Controls)
                                </span>
                                <span class="text-[10px] font-mono text-slate-400">4-Channel Optocoupler</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                ${[1, 2, 3, 4].map(ch => {
                                    const state = Number(board[`relay_${ch}`]) === 1;
                                    const labels = ['ปั๊มน้ำหลัก', 'วาล์วน้ำโซลินอยด์', 'วาล์วผิวดิน', 'ระบบพ่นหมอก'];
                                    return `
                                        <div class="p-3 rounded-2xl ${state ? 'bg-amber-950/40 border-amber-500/40 text-amber-300' : 'bg-slate-950/80 border-white/5 text-slate-400'} border space-y-2">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-bold font-mono">CH-0${ch}</span>
                                                <span class="w-2 h-2 rounded-full ${state ? 'bg-amber-400 animate-pulse' : 'bg-slate-600'}"></span>
                                            </div>
                                            <span class="text-[11px] font-tech text-white block truncate">${labels[ch - 1]}</span>
                                            <button onclick="toggleActiveBoardRelay(${ch})" class="w-full py-1.5 rounded-xl font-mono text-xs font-bold transition ${state ? 'bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-md shadow-amber-500/20' : 'bg-slate-800 hover:bg-slate-700 text-slate-300'}">
                                                ${state ? 'เปิดอยู่ (ON)' : 'ปิดอยู่ (OFF)'}
                                            </button>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                    break;

                case 'trend_charts':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950 col-span-1 md:col-span-2 xl:col-span-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-chart-line text-cyan-400"></i> กราฟแนวโน้มพลวัต Real-time (24-Hour Telemetry Dynamics)
                                </span>
                                <div class="flex items-center gap-2 text-[10px] font-mono">
                                    <span class="text-cyan-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-cyan-400"></span> Air Temp</span>
                                    <span class="text-amber-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Soil Temp</span>
                                    <span class="text-lime-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-lime-400"></span> Soil Moist</span>
                                </div>
                            </div>
                            <div class="h-56 w-full relative">
                                <canvas id="fleetTrendChartCanvas"></canvas>
                            </div>
                        </div>
                    `;
                    break;

                case 'camera_vision':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-white/10 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-slate-950 col-span-1 md:col-span-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-slate-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera text-indigo-400"></i> กล้องตรวจโรคใบพืช OV2640 (YOLOv8 Edge Vision)
                                </span>
                                <span class="text-[10px] font-mono text-indigo-400 bg-indigo-950/80 px-2 py-0.5 rounded-full border border-indigo-500/30">FPS: 15</span>
                            </div>
                            <div class="relative rounded-2xl overflow-hidden bg-slate-950 border border-white/10 aspect-video flex items-center justify-center">
                                <img src="../assets/img/hero-cover.jpg" onerror="this.src='https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?auto=format&fit=crop&w=600&q=80'" class="w-full h-full object-cover opacity-80" alt="Plant Vision">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                                <div class="absolute top-3 left-3 px-2 py-1 rounded-lg bg-black/70 backdrop-blur-md text-[10px] font-mono text-emerald-400 border border-emerald-500/30">
                                    YOLOv8: Anthracnose 0% (Healthy)
                                </div>
                            </div>
                        </div>
                    `;
                    break;

                case 'farmer_vital_signs':
                    html += `
                        <div class="glass-panel rounded-3xl p-5 border border-rose-500/30 space-y-3 relative overflow-hidden bg-gradient-to-br from-slate-900/90 to-rose-950/30">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold font-tech text-rose-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-heart-pulse text-rose-500 animate-pulse"></i> สุขภาพเกษตรกร (Wearable Vitals)
                                </span>
                                <span class="text-[10px] font-mono text-rose-400 bg-rose-950/80 px-2 py-0.5 rounded-full border border-rose-500/30">MAX30102 / WBGT</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono">
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">อัตราการเต้นหัวใจ</span>
                                    <span class="text-2xl font-black text-rose-400">${t.heart_rate !== undefined ? t.heart_rate : 76}<span class="text-xs font-normal text-slate-400 ml-1">BPM</span></span>
                                </div>
                                <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block font-sans">ออกซิเจน SpO2</span>
                                    <span class="text-2xl font-black text-cyan-300">${t.spo2 !== undefined ? t.spo2 : 98.4}<span class="text-xs font-normal text-slate-400 ml-1">%</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 border-t border-white/5 pt-2">
                                <span>ดัชนีความเครียดความร้อน: <strong class="text-emerald-400">ปกติ (Low Heat Stress)</strong></span>
                                <span class="text-rose-400"><i class="fa-solid fa-shield-halved mr-1"></i>ปลอดภัย</span>
                            </div>
                        </div>
                    `;
                    break;
            }
        });

        if (activeWidgetCount === 0) {
            html = `
                <div class="col-span-full p-8 rounded-3xl bg-slate-900/60 border border-dashed border-slate-700 text-center space-y-3">
                    <i class="fa-solid fa-microchip text-4xl text-slate-600"></i>
                    <h4 class="text-base font-bold font-tech text-slate-300">กลุ่มนี้ยังไม่ได้เปิดใช้งานเซนเซอร์สำหรับการเกษตร</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">ท่านสามารถเปิดเซนเซอร์ SHT45, โพรบดิน 7-in-1, Soil Stick, แสง BH1750 หรือ AI NPK ให้กับกลุ่มนี้ได้ทันทีผ่านระบบหลังบ้าน</p>
                    <button onclick="openAdminFleetModal(${board.id})" class="px-4 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold font-tech text-xs hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                        <i class="fa-solid fa-sliders mr-1"></i> เปิดใช้งานเซนเซอร์ให้กลุ่มนี้
                    </button>
                </div>
            `;
        }

        grid.innerHTML = html;

        // Re-initialize Chart if canvas exists
        if (widgets.includes('trend_charts')) {
            this.initTrendChart(t);
        }
    },

    // =========================================================================
    // 5. CHART.JS INITIALIZER & UPDATE
    // =========================================================================
    initTrendChart(t) {
        const canvas = document.getElementById('fleetTrendChartCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        if (this.trendChartInstance) {
            this.trendChartInstance.destroy();
        }

        const baseTemp = Number(t.air_temp || 28.4);
        const baseSoilTemp = Number(t.soil_temp || 27.8);
        const stickMoistVal = (t.surface_moist !== undefined || t.stick_moisture !== undefined) ? Number(t.surface_moist ?? t.stick_moisture) : undefined;
        const baseMoist = stickMoistVal !== undefined ? stickMoistVal : Number(t.soil_moisture || 60.7);

        // Generate synthetic past 12 hour timeline points
        const labels = ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '00:00', '02:00', 'ตอนนี้'];
        const dataAirTemp = [24.2, 26.5, 29.8, 32.1, 33.4, 31.2, 29.0, 27.5, 26.8, 26.0, 25.4, baseTemp];
        const dataSoilTemp = [23.8, 24.9, 26.2, 28.5, 29.2, 28.8, 28.1, 27.9, 27.5, 27.0, 26.8, baseSoilTemp];
        const dataSoilMoist = [baseMoist + 2.5, baseMoist + 1.8, baseMoist + 0.9, baseMoist + 0.2, baseMoist - 0.5, baseMoist - 0.2, baseMoist, baseMoist + 0.1, baseMoist - 0.3, baseMoist, baseMoist - 0.1, baseMoist];

        this.trendChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Air Temp (°C)',
                        data: dataAirTemp,
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.08)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2,
                        pointHoverRadius: 5,
                        fill: true
                    },
                    {
                        label: 'Soil Temp (°C)',
                        data: dataSoilTemp,
                        borderColor: '#f59e0b',
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Soil Moisture (%)',
                        data: dataSoilMoist,
                        borderColor: '#a3e635',
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(9, 14, 28, 0.95)',
                        titleFont: { family: 'Outfit', size: 12 },
                        bodyFont: { family: 'JetBrains Mono', size: 11 },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } }
                    }
                }
            }
        });
    },

    // =========================================================================
    // 6. RENDER FLEET GRID MATRIX (ALL BOARDS SIMULTANEOUSLY)
    // =========================================================================
    renderFleetGrid() {
        const container = document.getElementById('fleetGridCardsContainer');
        if (!container) return;

        if (!FleetState.boards || FleetState.boards.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center text-slate-400 text-xs p-6">ไม่พบบอร์ด</div>';
            return;
        }

        container.innerHTML = FleetState.boards.map(b => {
            const isOnline = b.status === 'online';
            const isMaster = Number(b.is_master) === 1 || Number(b.id) === 1;
            const t = b.telemetry || {};

            const hasSht = Number(b.has_sht45 ?? 1) === 1;
            const hasSoil7 = Number(b.has_soil_7in1 ?? 1) === 1;
            const hasStick = Number(b.has_soil_stick ?? 1) === 1;
            const hasBh = Number(b.has_bh1750 ?? 1) === 1;
            const hasAi = Number(b.has_ai_npk ?? 1) === 1;
            const hasVital = Number(b.has_vital_signs ?? 0) === 1;

            const cardBorder = isMaster
                ? 'border-amber-500/40 bg-gradient-to-br from-amber-950/30 via-slate-900/90 to-slate-950 shadow-xl ring-1 ring-amber-500/20'
                : 'border-white/10 bg-gradient-to-br from-slate-900/90 to-slate-950 shadow-xl';

            const groupTag = isMaster
                ? `<span class="text-xs font-mono font-bold text-amber-300 bg-amber-500/20 px-2.5 py-0.5 rounded-full border border-amber-500/40 flex items-center gap-1"><i class="fa-solid fa-crown text-[10px] text-amber-400"></i> สาธิตหลัก</span>`
                : `<span class="text-xs font-mono font-bold text-cyan-300 bg-cyan-950/80 px-2 py-0.5 rounded-full border border-cyan-500/30">กลุ่ม ${b.id - 1}</span>`;

            return `
                <div class="glass-panel rounded-3xl p-5 border space-y-4 relative overflow-hidden ${cardBorder}">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                ${groupTag}
                                <span class="text-xs font-mono font-bold text-white">
                                    ${escapeHtml(b.code || 'ESP32')}
                                </span>
                                <span class="text-[10px] font-mono text-purple-300 bg-purple-950/60 px-2 py-0.5 rounded-full">
                                    ${escapeHtml(b.zone || '')}
                                </span>
                            </div>
                            <h4 class="text-base font-bold font-tech text-white mt-1">
                                ${escapeHtml(b.name || 'แปลงเกษตร')}
                            </h4>
                            <!-- Active Sensors Badges -->
                            <div class="flex flex-wrap items-center gap-1 mt-1.5">
                                ${hasSht ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-cyan-950/80 text-cyan-300 border border-cyan-500/30">🌡️ SHT45</span>' : ''}
                                ${hasSoil7 ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-lime-950/80 text-lime-300 border border-lime-500/30">🪴 7in1</span>' : ''}
                                ${hasStick ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-amber-950/80 text-amber-300 border border-amber-500/30">📍 Stick</span>' : ''}
                                ${hasBh ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-yellow-950/80 text-yellow-300 border border-yellow-500/30">☀️ BH1750</span>' : ''}
                                ${hasAi ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-purple-950/80 text-purple-300 border border-purple-500/30">🧠 AI</span>' : ''}
                                ${hasVital ? '<span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-rose-950/80 text-rose-300 border border-rose-500/30">🩺 Vital</span>' : ''}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-bold ${isOnline ? 'text-emerald-400' : 'text-rose-400'}">
                                <span class="w-2 h-2 rounded-full ${isOnline ? 'bg-emerald-400 animate-ping' : 'bg-rose-500'}"></span>
                                ${isOnline ? 'ONLINE' : 'OFFLINE'}
                            </span>
                            <span class="text-[9px] font-mono text-slate-500 block">${escapeHtml(b.ip_address || '0.0.0.0')}</span>
                        </div>
                    </div>

                    <!-- Telemetry Metrics Grid (Sensor-Aware) -->
                    <div class="grid grid-cols-4 gap-2 text-center font-mono">
                        <div class="p-2 rounded-xl bg-slate-950/80 border border-white/5">
                            <span class="text-[9px] text-slate-400 block">${hasSht ? 'Air Temp' : '<span class="text-slate-500">Air (ปิด)</span>'}</span>
                            <span class="text-sm font-bold ${hasSht ? 'text-amber-300' : 'text-slate-500'}">${hasSht && t.air_temp !== undefined ? Number(t.air_temp).toFixed(1) + '°C' : '--'}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-950/80 border border-white/5">
                            <span class="text-[9px] text-slate-400 block">${hasSht ? 'Air RH' : '<span class="text-slate-500">RH (ปิด)</span>'}</span>
                            <span class="text-sm font-bold ${hasSht ? 'text-cyan-300' : 'text-slate-500'}">${hasSht && t.air_hum !== undefined ? Number(t.air_hum).toFixed(1) + '%' : '--'}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-950/80 border border-white/5">
                            <span class="text-[9px] text-slate-400 block">${hasSoil7 ? 'Soil pH' : '<span class="text-slate-500">pH (ปิด)</span>'}</span>
                            <span class="text-sm font-bold ${hasSoil7 ? 'text-lime-400' : 'text-slate-500'}">${hasSoil7 && t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(2) : '--'}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-950/80 border border-white/5">
                            <span class="text-[9px] text-slate-400 block">${(hasSoil7 || hasStick) ? (hasStick ? 'Soil Moist (จอ)' : 'Soil Moist') : '<span class="text-slate-500">Moist (ปิด)</span>'}</span>
                            <span class="text-sm font-bold ${(hasSoil7 || hasStick) ? 'text-emerald-300' : 'text-slate-500'}">${(hasSoil7 || hasStick) ? (((hasStick && (t.surface_moist !== undefined || t.stick_moisture !== undefined)) ? Number(t.surface_moist ?? t.stick_moisture).toFixed(1) : (t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) : '60.7')) + '%') : '--'}</span>
                        </div>
                    </div>

                    <!-- Dual-Engine Quick Badge: Sensor pH vs AI Calibrated pH -->
                    <div class="p-2.5 rounded-2xl bg-purple-950/30 border border-purple-500/30 flex items-center justify-between text-xs font-mono">
                        <span class="text-[10px] text-purple-300 font-sans flex items-center gap-1">
                            <i class="fa-solid fa-brain text-purple-400"></i> AI Model Calibration:
                        </span>
                        <div class="flex items-center gap-2">
                            ${hasAi ? `
                                <span class="text-slate-400">Raw: <strong class="text-white">${hasSoil7 ? (t.soil_ph || '8.50') : '--'}</strong></span>
                                <span class="text-purple-400">→</span>
                                <span class="text-lime-400">AI: <strong>${t.ai_calibrated_ph || '9.44'}</strong></span>
                            ` : `
                                <span class="text-slate-500 text-[10px]">ปิดการประมวลผล AI</span>
                            `}
                        </div>
                    </div>

                    <!-- 4-Channel Relays Control for THIS board -->
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between text-[11px] font-tech text-slate-300">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-bolt text-amber-400"></i> สั่งการรีเลย์ประจำโหนด</span>
                            <span class="text-[10px] font-mono text-slate-500">Board Direct</span>
                        </div>
                        <div class="grid grid-cols-4 gap-1.5">
                            ${[1, 2, 3, 4].map(ch => {
                                const state = Number(b[`relay_${ch}`]) === 1;
                                return `
                                    <button onclick="toggleBoardRelay(${b.id}, ${ch})" class="py-1.5 rounded-xl font-mono text-[11px] font-bold transition flex flex-col items-center justify-center gap-0.5 ${state ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'bg-slate-800 hover:bg-slate-700 text-slate-300'}">
                                        <span>CH${ch}</span>
                                        <span class="text-[9px] font-sans">${state ? 'ON' : 'OFF'}</span>
                                    </button>
                                `;
                            }).join('')}
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="flex items-center justify-between border-t border-white/5 pt-3">
                        <button onclick="selectBoard(${b.id}); switchViewMode('focus');" class="text-xs font-tech font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> เข้าสู่หน้า Focus
                        </button>
                        <div class="flex items-center gap-1">
                            <button onclick="openAdminFleetModal(${b.id})" class="p-1.5 rounded-lg bg-amber-950/60 hover:bg-amber-900 text-amber-400 hover:text-amber-200 text-xs transition" title="ปรับแต่งเซนเซอร์กลุ่มนี้">
                                <i class="fa-solid fa-sliders"></i>
                            </button>
                            <button onclick="openBoardModal(${b.id})" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white text-xs transition" title="แก้ไขข้อมูลบอร์ด">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteBoard(${b.id})" class="p-1.5 rounded-lg bg-rose-950/60 hover:bg-rose-900 text-rose-400 hover:text-rose-200 text-xs transition" title="ลบบอร์ด">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    },

    // =========================================================================
    // 7. RENDER COMPARATIVE ANALYSIS MATRIX (CROSS-BOARD BENCHMARK)
    // =========================================================================
    renderComparative() {
        const container = document.getElementById('comparativeTableContainer');
        if (!container) return;

        if (!FleetState.boards || FleetState.boards.length === 0) {
            container.innerHTML = '<div class="text-center text-slate-400 text-xs p-6">ไม่พบบอร์ด</div>';
            return;
        }

        container.innerHTML = `
            <table class="w-full text-left border-collapse text-xs font-tech">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 font-mono text-[11px]">
                        <th class="py-3 px-3">โหนด / บอร์ด ESP32</th>
                        <th class="py-3 px-3">โซน / พื้นที่</th>
                        <th class="py-3 px-3">อุณหภูมิ & ความชื้น</th>
                        <th class="py-3 px-3">VPD (kPa)</th>
                        <th class="py-3 px-3">ดินเขตราก (Probe vs AI)</th>
                        <th class="py-3 px-3">ความชื้นราก (%)</th>
                        <th class="py-3 px-3">EC ดิน (mS/cm)</th>
                        <th class="py-3 px-3">AI NPK (mg/kg)</th>
                        <th class="py-3 px-3">สถานะรีเลย์</th>
                        <th class="py-3 px-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    ${FleetState.boards.map(b => {
                        const t = b.telemetry || {};
                        const isOnline = b.status === 'online';
                        const isMaster = Number(b.is_master) === 1 || Number(b.id) === 1;
                        const activeRelays = [b.relay_1, b.relay_2, b.relay_3, b.relay_4].filter(r => Number(r) === 1).length;

                        const rowClass = isMaster
                            ? 'bg-amber-950/35 border-y border-amber-500/40 font-bold text-white shadow-xs'
                            : 'hover:bg-slate-900/60 transition';

                        const codeTag = isMaster
                            ? `<span class="font-bold text-amber-300 font-mono flex items-center gap-1.5"><i class="fa-solid fa-crown text-amber-400 text-[10px]"></i> ${escapeHtml(b.code)} <span class="text-[9px] font-sans px-1.5 py-0.2 bg-amber-500/20 text-amber-300 rounded border border-amber-500/40">สาธิตหลัก</span></span>`
                            : `<span class="font-bold text-white font-mono flex items-center gap-1.5"><span class="text-[9px] px-1.5 py-0.2 bg-cyan-950 text-cyan-300 rounded border border-cyan-500/30">กลุ่ม ${b.id - 1}</span> ${escapeHtml(b.code)}</span>`;

                        return `
                            <tr class="${rowClass}">
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full ${isOnline ? 'bg-emerald-400' : 'bg-rose-500'}"></span>
                                        <div>
                                            ${codeTag}
                                            <span class="text-[10px] text-slate-400 truncate max-w-[140px] block">${escapeHtml(b.name)}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-slate-300">${escapeHtml(b.zone)}</td>
                                <td class="py-3.5 px-3 font-mono">
                                    <span class="text-amber-300 font-bold">${t.air_temp !== undefined ? Number(t.air_temp).toFixed(1) + '°C' : '--'}</span>
                                    <span class="text-slate-500"> / </span>
                                    <span class="text-cyan-300">${t.air_hum !== undefined ? Number(t.air_hum).toFixed(1) + '%' : '--'}</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono font-bold text-purple-300">
                                    ${t.vpd !== undefined ? Number(t.vpd).toFixed(2) : '--'}
                                </td>
                                <td class="py-3.5 px-3 font-mono">
                                    <span class="text-cyan-400 font-bold">${t.soil_ph !== undefined ? Number(t.soil_ph).toFixed(2) : '--'}</span>
                                    <span class="text-slate-500"> → </span>
                                    <span class="text-lime-400 font-bold">${t.ai_calibrated_ph !== undefined ? Number(t.ai_calibrated_ph).toFixed(2) : '--'}</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono">
                                    <span class="font-bold text-emerald-300">
                                        ${(t.surface_moist !== undefined || t.stick_moisture !== undefined) ? Number(t.surface_moist ?? t.stick_moisture).toFixed(1) + '%' : (t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) + '%' : '--')}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">ราก: ${t.soil_moisture !== undefined ? Number(t.soil_moisture).toFixed(1) + '%' : '--'}</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-slate-300">
                                    ${t.soil_ec !== undefined ? Number(t.soil_ec).toFixed(2) : '--'}
                                </td>
                                <td class="py-3.5 px-3 font-mono text-[11px]">
                                    <span class="text-emerald-400">N:${Math.round(t.ai_nitrogen || 21)}</span>
                                    <span class="text-cyan-400 ml-1">P:${Math.round(t.ai_phosphorus || 14)}</span>
                                    <span class="text-amber-400 ml-1">K:${Math.round(t.ai_potassium || 8)}</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] ${activeRelays > 0 ? 'bg-amber-950 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-400'}">
                                        ${activeRelays}/4 Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <button onclick="selectBoard(${b.id}); switchViewMode('focus');" class="px-3 py-1 rounded-xl bg-cyan-600/30 hover:bg-cyan-600/50 text-cyan-300 border border-cyan-500/30 font-bold text-[11px] transition">
                                        โฟกัส
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;
    }
};

// =============================================================================
// GLOBAL INTERACTION CONTROLLERS
// =============================================================================

function selectBoard(boardId) {
    FleetState.activeBoardId = Number(boardId);
    FleetUI.renderBoardSelector();
    FleetUI.renderCurrentView();
}

function switchViewMode(mode) {
    FleetState.viewMode = mode;
    FleetUI.renderCurrentView();
    // Save state
    FleetAPI.saveLayout('default', FleetState.activeBoardId, FleetState.viewMode, FleetState.visibleWidgets, FleetState.gridColumns);
}

async function toggleActiveBoardRelay(relayId) {
    const board = FleetState.getActiveBoard();
    if (!board) return;
    const currentState = Number(board[`relay_${relayId}`]) === 1;
    const nextState = !currentState;

    // Optimistic UI update
    board[`relay_${relayId}`] = nextState ? 1 : 0;
    FleetUI.renderCurrentView();
    FleetUI.renderBoardSelector();

    try {
        const res = await FleetAPI.controlActuator(board.id, relayId, nextState);
        if (res && res.status === 'success') {
            showToast('success', `รีเลย์ CH-0${relayId} บอร์ด ${board.code}: ${nextState ? 'เปิด (ON)' : 'ปิด (OFF)'}`);
        } else {
            throw new Error(res.message || 'Actuator error');
        }
    } catch(e) {
        // Revert
        board[`relay_${relayId}`] = currentState ? 1 : 0;
        FleetUI.renderCurrentView();
        showToast('error', `ไม่สามารถสั่งการรีเลย์ CH-0${relayId}: ${e.message}`);
    }
}

async function toggleBoardRelay(boardId, relayId) {
    const board = FleetState.boards.find(b => Number(b.id) === Number(boardId));
    if (!board) return;
    const currentState = Number(board[`relay_${relayId}`]) === 1;
    const nextState = !currentState;

    board[`relay_${relayId}`] = nextState ? 1 : 0;
    FleetUI.renderCurrentView();

    try {
        const res = await FleetAPI.controlActuator(board.id, relayId, nextState);
        if (res && res.status === 'success') {
            showToast('success', `รีเลย์ CH-0${relayId} บอร์ด ${board.code}: ${nextState ? 'เปิด (ON)' : 'ปิด (OFF)'}`);
        }
    } catch(e) {
        board[`relay_${relayId}`] = currentState ? 1 : 0;
        FleetUI.renderCurrentView();
        showToast('error', `ไม่สามารถสั่งการ: ${e.message}`);
    }
}

// Modal Controllers: Board Add/Edit
function openBoardModal(editId = null) {
    const modal = document.getElementById('modalBoardForm');
    const form = document.getElementById('formBoardAdd');
    const title = document.getElementById('boardModalTitle');
    if (!modal) return;

    if (editId) {
        const b = FleetState.boards.find(x => Number(x.id) === Number(editId));
        if (b) {
            title.innerText = `แก้ไขข้อมูลบอร์ด ${b.code}`;
            document.getElementById('boardFormId').value = b.id;
            document.getElementById('boardFormName').value = b.name || '';
            document.getElementById('boardFormCode').value = b.code || '';
            document.getElementById('boardFormZone').value = b.zone || '';
            document.getElementById('boardFormIp').value = b.ip_address || '';
            document.getElementById('boardFormPort').value = b.port || 8500;
            document.getElementById('boardFormModel').value = b.model || 'ESP32-S3 ATD3.5 Pro';
            document.getElementById('boardFormMac').value = b.mac_address || '';
        }
    } else {
        title.innerText = 'เพิ่มบอร์ด ESP32 ใหม่ในเครือข่าย';
        form.reset();
        document.getElementById('boardFormId').value = '';
        document.getElementById('boardFormPort').value = 8500;
        const nextIdx = FleetState.boards.length + 1;
        document.getElementById('boardFormCode').value = `ESP32-NODE-0${nextIdx}`;
        document.getElementById('boardFormIp').value = `192.168.0.11${nextIdx}`;
        document.getElementById('boardFormZone').value = `Zone ${String.fromCharCode(64 + nextIdx)} - แปลงสาธิต`;
    }

    modal.classList.remove('hidden');
}

function closeBoardModal() {
    const modal = document.getElementById('modalBoardForm');
    if (modal) modal.classList.add('hidden');
}

async function handleBoardSubmit(event) {
    event.preventDefault();
    const id = document.getElementById('boardFormId').value;
    const payload = {
        name: document.getElementById('boardFormName').value.trim(),
        code: document.getElementById('boardFormCode').value.trim(),
        zone: document.getElementById('boardFormZone').value.trim(),
        ip_address: document.getElementById('boardFormIp').value.trim(),
        port: parseInt(document.getElementById('boardFormPort').value) || 8500,
        model: document.getElementById('boardFormModel').value,
        mac_address: document.getElementById('boardFormMac').value.trim()
    };

    if (id) payload.id = id;

    try {
        const res = await FleetAPI.addBoard(payload);
        if (res && res.status === 'success') {
            showToast('success', id ? 'อัปเดตข้อมูลบอร์ดเรียบร้อยแล้ว' : 'เพิ่มบอร์ด ESP32 ใหม่ในเครือข่ายสำเร็จ!');
            closeBoardModal();
            await refreshFleetNow();
            if (!id && res.board_id) {
                FleetState.activeBoardId = res.board_id;
            }
        } else {
            throw new Error(res.message || 'บันทึกไม่สำเร็จ');
        }
    } catch(e) {
        showToast('error', `เกิดข้อผิดพลาด: ${e.message}`);
    }
}

async function deleteBoard(id) {
    const b = FleetState.boards.find(x => Number(x.id) === Number(id));
    const confirm = await Swal.fire({
        title: 'ยืนยันการลบบอร์ด?',
        text: `คุณต้องการลบบอร์ด ${b?.code || ''} (${b?.name || ''}) ออกจากระบบใช่หรือไม่?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155',
        confirmButtonText: 'ใช่, ลบบอร์ด',
        cancelButtonText: 'ยกเลิก',
        background: '#090e1c',
        color: '#ffffff'
    });

    if (confirm.isConfirmed) {
        try {
            const res = await FleetAPI.deleteBoard(id);
            if (res && res.status === 'success') {
                showToast('success', 'ลบบอร์ดเรียบร้อยแล้ว');
                await refreshFleetNow();
            }
        } catch(e) {
            showToast('error', `ลบบอร์ดไม่สำเร็จ: ${e.message}`);
        }
    }
}

// Modal Controllers: Widget Catalog Customizer
function openWidgetCatalogModal() {
    const modal = document.getElementById('modalWidgetCatalog');
    if (!modal) return;

    // Set checkboxes
    const checkboxes = modal.querySelectorAll('.widget-toggle');
    checkboxes.forEach(cb => {
        cb.checked = FleetState.visibleWidgets.includes(cb.value);
    });

    // Set column buttons
    selectGridCols(FleetState.gridColumns, false);

    modal.classList.remove('hidden');
}

function closeWidgetCatalogModal() {
    const modal = document.getElementById('modalWidgetCatalog');
    if (modal) modal.classList.add('hidden');
}

function selectGridCols(cols, updateState = true) {
    if (updateState) FleetState.gridColumns = Number(cols);
    const buttons = document.querySelectorAll('.col-btn');
    buttons.forEach(btn => {
        if (Number(btn.dataset.cols) === Number(cols)) {
            btn.className = 'col-btn p-2 rounded-xl bg-cyan-950 border border-cyan-500 text-cyan-300 text-center font-bold';
        } else {
            btn.className = 'col-btn p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:border-cyan-500 text-center font-bold';
        }
    });
}

async function saveCustomLayout() {
    const modal = document.getElementById('modalWidgetCatalog');
    if (!modal) return;

    const checkboxes = modal.querySelectorAll('.widget-toggle:checked');
    const selected = Array.from(checkboxes).map(cb => cb.value);

    if (selected.length === 0) {
        showToast('warning', 'กรุณาเลือกวิดเจ็ตอย่างน้อย 1 รายการ');
        return;
    }

    FleetState.visibleWidgets = selected;
    FleetUI.renderCurrentView();

    try {
        await FleetAPI.saveLayout('default', FleetState.activeBoardId, FleetState.viewMode, FleetState.visibleWidgets, FleetState.gridColumns);
        showToast('success', 'บันทึกรูปแบบหน้าจอ & วิดเจ็ตสำเร็จ!');
        closeWidgetCatalogModal();
    } catch(e) {
        showToast('info', 'บันทึกหน้าจอในเครื่องสำเร็จ');
        closeWidgetCatalogModal();
    }
}

function resetDefaultLayout() {
    FleetState.gridColumns = 4;
    FleetState.visibleWidgets = [
        'weather_microclimate',
        'vpd_transpiration',
        'soil_7in1_root',
        'surface_soil_stick',
        'solar_radiation',
        'ai_npk_calibration',
        'relays_control',
        'trend_charts'
    ];
    selectGridCols(4);
    const modal = document.getElementById('modalWidgetCatalog');
    if (modal) {
        const checkboxes = modal.querySelectorAll('.widget-toggle');
        checkboxes.forEach(cb => {
            cb.checked = FleetState.visibleWidgets.includes(cb.value);
        });
    }
}

// Modal Controllers: Broadcast Actuators
function openBroadcastModal() {
    const modal = document.getElementById('modalBroadcast');
    if (modal) modal.classList.remove('hidden');
}

function closeBroadcastModal() {
    const modal = document.getElementById('modalBroadcast');
    if (modal) modal.classList.add('hidden');
}

async function sendBroadcastRelay(relayId, state) {
    try {
        const res = await FleetAPI.broadcastActuators(relayId, state);
        if (res && res.status === 'success') {
            showToast('success', `ส่งคำสั่งรีเลย์ CH-0${relayId} (${state ? 'ON' : 'OFF'}) ไปยังทุกบอร์ดแล้ว`);
            await refreshFleetNow();
        }
    } catch(e) {
        showToast('error', `ไม่สามารถกระจายคำสั่ง: ${e.message}`);
    }
}

async function sendEmergencyAllOff() {
    try {
        for (let ch = 1; ch <= 4; ch++) {
            await FleetAPI.broadcastActuators(ch, 0);
        }
        showToast('warning', 'ส่งคำสั่งปิดฉุกเฉิน (Emergency All OFF) ทุกรีเลย์ ทุกบอร์ดเรียบร้อยแล้ว!');
        closeBroadcastModal();
        await refreshFleetNow();
    } catch(e) {
        showToast('error', `ปิดฉุกเฉินล้มเหลว: ${e.message}`);
    }
}

// =========================================================================
// 8. BACKEND ADMIN FLEET & SENSOR CUSTOMIZATION MODULE
// =========================================================================
const FleetAdmin = {
    renderTable() {
        const tbody = document.getElementById('adminFleetTableBody');
        if (!tbody) return;

        let shtCount = 0, soil7Count = 0, stickCount = 0, bhCount = 0, aiCount = 0, camCount = 0, vitalCount = 0;

        tbody.innerHTML = FleetState.boards.map(b => {
            const isMaster = Number(b.is_master) === 1 || Number(b.id) === 1;
            const hasSht = Number(b.has_sht45 ?? 1) === 1;
            const hasSoil7 = Number(b.has_soil_7in1 ?? 1) === 1;
            const hasStick = Number(b.has_soil_stick ?? 1) === 1;
            const hasBh = Number(b.has_bh1750 ?? 1) === 1;
            const hasAi = Number(b.has_ai_npk ?? 1) === 1;
            const hasCam = Number(b.has_camera ?? 1) === 1;
            const hasVital = Number(b.has_vital_signs ?? 0) === 1;
            const preset = b.sensor_preset || 'full';
            const isOnline = b.status === 'online';

            if (hasSht) shtCount++;
            if (hasSoil7) soil7Count++;
            if (hasStick) stickCount++;
            if (hasBh) bhCount++;
            if (hasAi) aiCount++;
            if (hasCam) camCount++;
            if (hasVital) vitalCount++;

            const groupBadge = isMaster
                ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-amber-500/20 text-amber-300 border border-amber-500/40 font-bold flex items-center gap-1"><i class="fa-solid fa-crown text-[9px]"></i> สาธิตหลัก</span>`
                : `<span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-cyan-950 text-cyan-300 border border-cyan-500/30 font-bold">กลุ่ม ${b.id - 1}</span>`;

            return `
                <tr class="hover:bg-slate-900/60 transition" id="admin_row_${b.id}">
                    <td class="py-2.5 px-3">
                        <div class="flex items-center gap-1.5">
                            ${groupBadge}
                            <input type="text" id="admin_code_${b.id}" value="${escapeHtml(b.code || b.board_code || '')}" class="w-24 bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-cyan-300 font-mono text-[11px] focus:outline-none focus:border-cyan-500">
                        </div>
                    </td>
                    <td class="py-2.5 px-3">
                        <input type="text" id="admin_name_${b.id}" value="${escapeHtml(b.name || '')}" class="w-full min-w-[140px] bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-white text-xs focus:outline-none focus:border-cyan-500 font-sans">
                    </td>
                    <td class="py-2.5 px-3">
                        <input type="text" id="admin_zone_${b.id}" value="${escapeHtml(b.zone || '')}" class="w-full min-w-[110px] bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-slate-300 text-xs focus:outline-none focus:border-cyan-500">
                    </td>
                    <td class="py-2.5 px-3">
                        <input type="text" id="admin_ip_${b.id}" value="${escapeHtml(b.ip_address || '192.168.0.100')}" class="w-28 bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-white font-mono text-[11px] focus:outline-none focus:border-cyan-500">
                    </td>
                    <td class="py-2.5 px-3">
                        <select id="admin_preset_${b.id}" onchange="FleetAdmin.onRowPresetChange(${b.id}, this.value)" class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-amber-300 font-tech focus:outline-none focus:border-amber-500">
                            <option value="full" ${preset === 'full' ? 'selected' : ''}>🌿 ครบชุด</option>
                            <option value="soil_npk" ${preset === 'soil_npk' ? 'selected' : ''}>🧪 ดิน NPK</option>
                            <option value="greenhouse" ${preset === 'greenhouse' ? 'selected' : ''}>🏡 โรงเรือน</option>
                            <option value="basic" ${preset === 'basic' ? 'selected' : ''}>💧 พื้นฐาน</option>
                            <option value="health_iot" ${preset === 'health_iot' ? 'selected' : ''}>🩺 สุขภาพ</option>
                            <option value="custom" ${preset === 'custom' ? 'selected' : ''}>⚙️ กำหนดเอง</option>
                        </select>
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_sht45_${b.id}" ${hasSht ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_soil7in1_${b.id}" ${hasSoil7 ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-lime-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_stick_${b.id}" ${hasStick ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_bh1750_${b.id}" ${hasBh ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-yellow-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_ainpk_${b.id}" ${hasAi ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-purple-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_camera_${b.id}" ${hasCam ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-indigo-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-2 text-center">
                        <input type="checkbox" id="admin_vital_${b.id}" ${hasVital ? 'checked' : ''} onchange="FleetAdmin.updateCounts()" class="rounded bg-slate-900 border-slate-700 text-rose-500 focus:ring-0 cursor-pointer">
                    </td>
                    <td class="py-2.5 px-3 text-center">
                        <select id="admin_status_${b.id}" class="bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-[11px] font-mono ${isOnline ? 'text-emerald-400' : 'text-rose-400'}">
                            <option value="online" ${isOnline ? 'selected' : ''}>ONLINE</option>
                            <option value="offline" ${!isOnline ? 'selected' : ''}>OFFLINE</option>
                        </select>
                    </td>
                    <td class="py-2.5 px-3 text-right">
                        <button onclick="FleetAdmin.saveRow(${b.id})" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-tech font-bold text-xs transition shadow-sm inline-flex items-center gap-1">
                            <i class="fa-solid fa-floppy-disk text-[10px]"></i> บันทึก
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

        setText('metricCountSht45', shtCount);
        setText('metricCountSoil7in1', soil7Count);
        setText('metricCountStick', stickCount);
        setText('metricCountBh1750', bhCount);
        setText('metricCountAiNpk', aiCount);
        setText('metricCountCamera', camCount);
        setText('metricCountVital', vitalCount);
    },

    updateCounts() {
        let shtCount = 0, soil7Count = 0, stickCount = 0, bhCount = 0, aiCount = 0, camCount = 0, vitalCount = 0;
        FleetState.boards.forEach(b => {
            const cbSht = document.getElementById(`admin_sht45_${b.id}`);
            const cbSoil7 = document.getElementById(`admin_soil7in1_${b.id}`);
            const cbStick = document.getElementById(`admin_stick_${b.id}`);
            const cbBh = document.getElementById(`admin_bh1750_${b.id}`);
            const cbAi = document.getElementById(`admin_ainpk_${b.id}`);
            const cbCam = document.getElementById(`admin_camera_${b.id}`);
            const cbVital = document.getElementById(`admin_vital_${b.id}`);

            if (cbSht?.checked) shtCount++;
            if (cbSoil7?.checked) soil7Count++;
            if (cbStick?.checked) stickCount++;
            if (cbBh?.checked) bhCount++;
            if (cbAi?.checked) aiCount++;
            if (cbCam?.checked) camCount++;
            if (cbVital?.checked) vitalCount++;
        });

        setText('metricCountSht45', shtCount);
        setText('metricCountSoil7in1', soil7Count);
        setText('metricCountStick', stickCount);
        setText('metricCountBh1750', bhCount);
        setText('metricCountAiNpk', aiCount);
        setText('metricCountCamera', camCount);
        setText('metricCountVital', vitalCount);
    },

    onRowPresetChange(boardId, preset) {
        const cbSht = document.getElementById(`admin_sht45_${boardId}`);
        const cbSoil7 = document.getElementById(`admin_soil7in1_${boardId}`);
        const cbStick = document.getElementById(`admin_stick_${boardId}`);
        const cbBh = document.getElementById(`admin_bh1750_${boardId}`);
        const cbAi = document.getElementById(`admin_ainpk_${boardId}`);
        const cbCam = document.getElementById(`admin_camera_${boardId}`);
        const cbVital = document.getElementById(`admin_vital_${boardId}`);

        if (preset === 'full') {
            if (cbSht) cbSht.checked = true;
            if (cbSoil7) cbSoil7.checked = true;
            if (cbStick) cbStick.checked = true;
            if (cbBh) cbBh.checked = true;
            if (cbAi) cbAi.checked = true;
            if (cbCam) cbCam.checked = true;
            if (cbVital) cbVital.checked = false;
        } else if (preset === 'soil_npk') {
            if (cbSht) cbSht.checked = false;
            if (cbSoil7) cbSoil7.checked = true;
            if (cbStick) cbStick.checked = true;
            if (cbBh) cbBh.checked = false;
            if (cbAi) cbAi.checked = true;
            if (cbCam) cbCam.checked = false;
            if (cbVital) cbVital.checked = false;
        } else if (preset === 'greenhouse') {
            if (cbSht) cbSht.checked = true;
            if (cbSoil7) cbSoil7.checked = false;
            if (cbStick) cbStick.checked = true;
            if (cbBh) cbBh.checked = true;
            if (cbAi) cbAi.checked = false;
            if (cbCam) cbCam.checked = false;
            if (cbVital) cbVital.checked = false;
        } else if (preset === 'basic') {
            if (cbSht) cbSht.checked = true;
            if (cbSoil7) cbSoil7.checked = false;
            if (cbStick) cbStick.checked = true;
            if (cbBh) cbBh.checked = false;
            if (cbAi) cbAi.checked = false;
            if (cbCam) cbCam.checked = false;
            if (cbVital) cbVital.checked = false;
        } else if (preset === 'health_iot') {
            if (cbSht) cbSht.checked = true;
            if (cbSoil7) cbSoil7.checked = false;
            if (cbStick) cbStick.checked = false;
            if (cbBh) cbBh.checked = false;
            if (cbAi) cbAi.checked = false;
            if (cbCam) cbCam.checked = false;
            if (cbVital) cbVital.checked = true;
        }
        this.updateCounts();
    },

    async saveRow(boardId) {
        const payload = {
            id: boardId,
            name: document.getElementById(`admin_name_${boardId}`)?.value.trim() || '',
            board_code: document.getElementById(`admin_code_${boardId}`)?.value.trim() || '',
            zone: document.getElementById(`admin_zone_${boardId}`)?.value.trim() || '',
            ip_address: document.getElementById(`admin_ip_${boardId}`)?.value.trim() || '',
            port: 8500,
            status: document.getElementById(`admin_status_${boardId}`)?.value || 'online',
            sensor_preset: document.getElementById(`admin_preset_${boardId}`)?.value || 'custom',
            has_sht45: document.getElementById(`admin_sht45_${boardId}`)?.checked ? 1 : 0,
            has_soil_7in1: document.getElementById(`admin_soil7in1_${boardId}`)?.checked ? 1 : 0,
            has_soil_stick: document.getElementById(`admin_stick_${boardId}`)?.checked ? 1 : 0,
            has_bh1750: document.getElementById(`admin_bh1750_${boardId}`)?.checked ? 1 : 0,
            has_ai_npk: document.getElementById(`admin_ainpk_${boardId}`)?.checked ? 1 : 0,
            has_camera: document.getElementById(`admin_camera_${boardId}`)?.checked ? 1 : 0,
            has_vital_signs: document.getElementById(`admin_vital_${boardId}`)?.checked ? 1 : 0
        };

        try {
            const res = await FleetAPI.updateBoardFull(payload);
            if (res && res.status === 'success') {
                showToast('success', `บันทึกข้อมูล ${payload.board_code} เรียบร้อยแล้ว`);
                await refreshFleetNow();
            } else {
                showToast('error', res.message || 'บันทึกไม่สำเร็จ');
            }
        } catch(e) {
            showToast('error', 'บันทึกล้มเหลว: ' + e.message);
        }
    }
};

function openAdminFleetModal(focusBoardId = null) {
    const modal = document.getElementById('modalAdminFleet');
    if (modal) {
        modal.classList.remove('hidden');
        FleetAdmin.renderTable();
        if (focusBoardId) {
            setTimeout(() => {
                const row = document.getElementById(`admin_row_${focusBoardId}`);
                if (row) {
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    row.classList.add('bg-amber-950/40');
                    setTimeout(() => row.classList.remove('bg-amber-950/40'), 2000);
                }
            }, 100);
        }
    }
}

function closeAdminFleetModal() {
    const modal = document.getElementById('modalAdminFleet');
    if (modal) modal.classList.add('hidden');
}

async function applyBatchPreset(preset) {
    try {
        const res = await FleetAPI.batchApplyPreset(preset);
        if (res && res.status === 'success') {
            showToast('success', res.message);
            await refreshFleetNow();
            FleetAdmin.renderTable();
        }
    } catch(e) {
        showToast('error', 'ไม่สามารถปรับพรีเซ็ต: ' + e.message);
    }
}

async function saveAllAdminBoards() {
    try {
        for (const b of FleetState.boards) {
            await FleetAdmin.saveRow(b.id);
        }
        showToast('success', 'บันทึกการตั้งค่าบอร์ดและเซนเซอร์ทุกกลุ่มเรียบร้อยแล้ว');
        closeAdminFleetModal();
        await refreshFleetNow();
    } catch(e) {
        showToast('error', 'บันทึกล้มเหลว: ' + e.message);
    }
}

async function confirmResetWorkshop() {
    if (typeof Swal !== 'undefined') {
        const result = await Swal.fire({
            title: 'ยืนยันรีเซ็ตบอร์ด 16 กลุ่ม?',
            text: 'ระบบจะคืนค่าชื่อกลุ่ม, โซนแปลง, IP Address และชุดเซนเซอร์ตั้งต้นสำหรับการอบรม',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0891b2',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ยืนยันรีเซ็ต',
            cancelButtonText: 'ยกเลิก',
            background: '#090e1c',
            color: '#fff'
        });
        if (!result.isConfirmed) return;
    }

    try {
        const res = await FleetAPI.resetWorkshopBoards();
        if (res && res.status === 'success') {
            showToast('success', res.message);
            await refreshFleetNow();
            FleetAdmin.renderTable();
        }
    } catch(e) {
        showToast('error', 'รีเซ็ตล้มเหลว: ' + e.message);
    }
}

// Helper Utilities
function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.innerText = text;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function formatRelativeTime(ts) {
    if (!ts) return 'เมื่อสักครู่';
    const now = new Date();
    const past = new Date(ts);
    const diffSec = Math.floor((now - past) / 1000);
    if (isNaN(diffSec) || diffSec < 5) return 'สดเมื่อสักครู่';
    if (diffSec < 60) return `${diffSec} วินาทีที่แล้ว`;
    return `${Math.floor(diffSec / 60)} นาทีที่แล้ว`;
}

function showToast(icon, title) {
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2600,
            timerProgressBar: true,
            background: '#0c1427',
            color: '#ffffff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({ icon, title });
    } else {
        console.log(`[${icon.toUpperCase()}] ${title}`);
    }
}
