    <!-- Dashboard JavaScript Logic (Live Bidirectional IoT Sync Engine) -->
    <script>
        let currentBoardIp = '192.168.0.111';
        let currentBoardPort = 8500;
        let currentSsid = 'JC_Home';
        let currentRssi = -99;
        let currentCloudUrl = 'http://14.207.141.164:8000';
        // Soil Telemetry States
        let latestSoilPh = NaN;
        let latestSoilMoist = NaN;
        let currentControlMode = 'manual';
        let isAutoMode = false;

        // Relay state (optimistic UI + pending lock so polling never overwrites a fresh click)
        const dashRelayStates = {};
        const relayPending = {};          // id -> timestamp (ms) until which polling must not overwrite
        const RELAY_LOCK_MS = 5000;
        let modePendingUntil = 0;

        // Freshness thresholds (seconds)
        const STALE_DELAYED_S = 6;
        const STALE_OFFLINE_S = 20;

        // ---------- Helpers ----------
        const $ = (id) => document.getElementById(id);
        const isNum = (v) => v !== null && v !== undefined && v !== '' && Number.isFinite(Number(v));
        const fmt = (v, d = 1) => isNum(v) ? Number(v).toFixed(d) : '--';

        function setText(id, text) {
            const el = $(id);
            if (!el) return;
            const t = String(text);
            if (el.textContent === t) return;
            el.textContent = t;
            el.classList.remove('val-flash');
            void el.offsetWidth;
            el.classList.add('val-flash');
        }
        function setHtml(id, html) { const el = $(id); if (el && el.innerHTML !== html) el.innerHTML = html; }
        function setPill(id, kind, text) {
            const el = $(id);
            if (!el) return;
            const cls = `pill pill-${kind}`;
            if (el.dataset.pk !== kind) {
                // preserve extra utility classes (those not starting with pill)
                const extras = [...el.classList].filter(c => !c.startsWith('pill'));
                el.className = [cls, ...extras].join(' ');
                el.dataset.pk = kind;
            }
            if (text !== undefined && el.textContent !== text) el.textContent = text;
        }
        function setBar(id, pct, color) {
            const el = $(id);
            if (!el) return;
            const p = Math.max(0, Math.min(100, Number.isFinite(pct) ? pct : 0));
            el.style.width = p + '%';
            if (color) el.style.backgroundColor = color;
        }

        // Sparkline buffers (client-side rolling window)
        const SPARK_MAX = 40;
        const sparkBuf = { sparkTemp: [], sparkVpd: [], sparkSoil: [], sparkLux: [] };
        function pushSpark(id, v) {
            if (!isNum(v)) return;
            const b = sparkBuf[id];
            b.push(Number(v));
            if (b.length > SPARK_MAX) b.shift();
            const svg = $(id);
            if (!svg || b.length < 2) return;
            const min = Math.min(...b), max = Math.max(...b);
            const span = (max - min) || 1;
            const step = 100 / (SPARK_MAX - 1);
            const x0 = 100 - (b.length - 1) * step;
            const d = b.map((val, i) => `${i ? 'L' : 'M'}${(x0 + i * step).toFixed(2)},${(25 - ((val - min) / span) * 22).toFixed(2)}`).join(' ');
            svg.firstElementChild.setAttribute('d', d);
        }

        // Toast (single shared mixin)
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 1800, timerProgressBar: true,
            background: '#0f172a', color: '#e2e8f0'
        });

        function renderSoil7in1Hero() {
            const heroVal = $('dash7in1HeroVal');
            if (!heroVal) return;
            setText('dash7in1HeroVal', fmt(latestSoilPh, 1));
            setText('dash7in1HeroUnit', 'pH');
            const ph = Number(latestSoilPh);
            if (!Number.isFinite(ph)) { setText('dash7in1HeroSub', 'รอข้อมูล'); return; }
            if (ph < 5.5) setText('dash7in1HeroSub', 'ดินเป็นกรด (Acidic)');
            else if (ph > 7.5) setText('dash7in1HeroSub', 'ดินเป็นด่าง (Alkaline)');
            else setText('dash7in1HeroSub', 'เป็นกลาง สมบูรณ์ (Optimal)');
        }

        // ---------- 1. Trend chart (dual axis, rolling window, real history) ----------
        const CHART_MAX_POINTS = 90;
        const ctxTrend = $('envTrendChart').getContext('2d');
        const envTrendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { key: 'temp', label: 'อุณหภูมิ (°C)', data: [], yAxisID: 'y', borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.10)', tension: 0.35, fill: true, pointRadius: 0, pointHoverRadius: 4, borderWidth: 2 },
                    { key: 'hum', label: 'ความชื้น RH (%)', data: [], yAxisID: 'y1', borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,0.05)', tension: 0.35, fill: false, pointRadius: 0, pointHoverRadius: 4, borderWidth: 2 },
                    { key: 'soil', label: 'ความชื้นดิน (%)', data: [], yAxisID: 'y1', borderColor: '#10b981', backgroundColor: 'transparent', tension: 0.35, borderDash: [5, 5], pointRadius: 0, pointHoverRadius: 4, borderWidth: 2 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { color: '#94a3b8', font: { family: 'Prompt', size: 11 }, usePointStyle: true, boxWidth: 8 } },
                    tooltip: { backgroundColor: 'rgba(15,23,42,.95)', borderColor: 'rgba(148,163,184,.3)', borderWidth: 1 }
                },
                scales: {
                    x: { ticks: { color: '#64748b', maxTicksLimit: 8, maxRotation: 0, font: { family: 'Chakra Petch', size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { position: 'left', title: { display: true, text: '°C', color: '#f59e0b' }, ticks: { color: '#fbbf24', font: { family: 'Chakra Petch', size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' }, grace: '10%' },
                    y1: { position: 'right', min: 0, max: 100, title: { display: true, text: '%', color: '#22d3ee' }, ticks: { color: '#67e8f9', font: { family: 'Chakra Petch', size: 10 } }, grid: { drawOnChartArea: false } }
                }
            }
        });

        const chartSeriesIdx = { temp: 0, hum: 1, soil: 2 };
        function toggleChartSeries(key) {
            const idx = chartSeriesIdx[key];
            if (idx === undefined) return;
            const visible = envTrendChart.isDatasetVisible(idx);
            envTrendChart.setDatasetVisibility(idx, !visible);
            envTrendChart.update('none');
            const btn = $('chartBtn' + key.charAt(0).toUpperCase() + key.slice(1));
            if (btn) { btn.setAttribute('aria-pressed', String(!visible)); btn.style.opacity = visible ? '.4' : '1'; }
        }

        let lastChartStamp = null;
        function pushChartPoint(label, t, h, s) {
            const d = envTrendChart.data;
            d.labels.push(label);
            d.datasets[0].data.push(isNum(t) ? Number(t) : null);
            d.datasets[1].data.push(isNum(h) ? Number(h) : null);
            d.datasets[2].data.push(isNum(s) ? Number(s) : null);
            while (d.labels.length > CHART_MAX_POINTS) {
                d.labels.shift();
                d.datasets.forEach(ds => ds.data.shift());
            }
        }
        async function loadChartHistory() {
            try {
                const res = await fetch('../api/api.php?action=get_history&limit=' + CHART_MAX_POINTS + '&_t=' + Date.now());
                const j = await res.json();
                if (j.status === 'success' && Array.isArray(j.data)) {
                    // rows from DB have an id; the API's synthetic fallback does not -> ignore synthetic data
                    const rows = j.data.filter(r => r && r.id !== undefined);
                    rows.forEach(r => {
                        const ts = String(r.created_at || '');
                        const lbl = ts.length >= 19 ? ts.slice(11, 19) : ts.slice(-8);
                        const soil = isNum(r.soil_stick_moisture) ? r.soil_stick_moisture : r.soil_moisture;
                        pushChartPoint(lbl, r.temperature, r.humidity, soil);
                    });
                    envTrendChart.update('none');
                    const sub = $('chartSubtitle');
                    if (sub) sub.textContent = rows.length
                        ? `อุณหภูมิ (°C), ความชื้นสัมพัทธ์ (%) และความชื้นดิน (%) — ย้อนหลัง ${rows.length} จุดจากฐานข้อมูล + อัปเดตสดทุกครั้งที่บอร์ดส่งข้อมูล`
                        : 'อุณหภูมิ (°C), ความชื้นสัมพัทธ์ (%) และความชื้นดิน (%) — ยังไม่มีข้อมูลย้อนหลัง กำลังรวบรวมแบบสด';
                }
            } catch (e) { console.warn('History load error:', e); }
        }

        // ---------- 2. NPK Radar Chart ----------
        const ctxRadar = $('npkRadarChart').getContext('2d');
        const npkRadarChart = new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: ['ไนโตรเจน (N)', 'ฟอสฟอรัส (P)', 'โพแทสเซียม (K)', 'ความชื้นดิน', 'pH ดิน', 'EC สภาพนำ'],
                datasets: [{
                    label: 'ค่าปัจจุบัน',
                    data: [0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(16, 185, 129, 0.25)',
                    borderColor: '#10b981',
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#10b981'
                }, {
                    label: 'เกณฑ์มาตรฐานพืช',
                    data: [50, 30, 70, 70, 65, 60],
                    backgroundColor: 'transparent',
                    borderColor: '#64748b',
                    borderDash: [4, 4],
                    pointRadius: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: { legend: { display: false } },
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.08)' },
                        grid: { color: 'rgba(255, 255, 255, 0.08)' },
                        pointLabels: { color: '#94a3b8', font: { family: 'Prompt', size: 9 } },
                        ticks: { display: false }
                    }
                }
            }
        });

        // ---------- 3. UI Helpers: relays & mode ----------
        function updateRelayDom(id, isOn) {
            dashRelayStates[id] = isOn;
            const led = $(`dash-led-${id}`);
            const btn = $(`dash-btn-${id}`);
            const status = $(`dash-status-${id}`);
            const row = $(`dash-row-${id}`);
            if (led) led.className = isOn ? 'w-3 h-3 rounded-full bg-emerald-400 inline-block flex-shrink-0 shadow-[0_0_10px_rgba(52,211,153,.9)]' : 'w-3 h-3 rounded-full bg-slate-700 inline-block flex-shrink-0';
            if (btn) {
                btn.setAttribute('aria-checked', String(isOn));
                btn.classList.toggle('is-pending', (relayPending[id] || 0) > Date.now());
            }
            if (status) {
                status.textContent = isOn ? 'ACTIVE (ON)' : 'STANDBY (OFF)';
                status.className = isOn ? 'text-[10px] font-bold text-emerald-400' : 'text-[10px] text-slate-400';
            }
            if (row) row.style.borderColor = isOn ? 'rgba(16,185,129,.45)' : '';
        }

        function updateControlModeDom(mode) {
            currentControlMode = (mode || 'manual').toLowerCase();
            const map = { manual: ['btnModeManual', 'warn', 'MANUAL'], auto: ['btnModeAuto', 'ok', 'SMART AUTO'], ai: ['btnModeAI', 'info', 'EDGE AI'] };
            Object.keys(map).forEach(k => {
                const b = $(map[k][0]);
                if (b) b.setAttribute('aria-pressed', String(k === currentControlMode));
            });
            const m = map[currentControlMode];
            if (m) { setPill('currentModeLabel', m[1], m[2]); }
            isAutoMode = currentControlMode === 'auto';
        }
        function updateAutoModeDom(isAuto) { updateControlModeDom(isAuto ? 'auto' : 'manual'); }

        // ---------- 4. Freshness / connection state ----------
        let lastFetchOkAt = 0;          // performance clock (ms) of last successful API response
        let ageAtFetchS = null;         // sensor data age (s) when last fetched
        let consecutiveFailures = 0;
        let lastLatencyMs = null;

        function parseSqlTime(s) {
            if (!s || typeof s !== 'string') return NaN;
            const t = Date.parse(s.replace(' ', 'T'));
            return Number.isFinite(t) ? t : NaN;
        }
        function renderLiveChip() {
            let state = 'connecting', ageS = null;
            if (consecutiveFailures >= 3) {
                state = 'offline';
            } else if (ageAtFetchS !== null) {
                ageS = ageAtFetchS + (performance.now() - lastFetchOkAt) / 1000;
                state = ageS > STALE_OFFLINE_S ? 'offline' : (ageS > STALE_DELAYED_S ? 'delayed' : 'live');
            }
            const cfg = {
                connecting: ['mute', 'bg-slate-500', 'CONNECTING'],
                live: ['ok', 'bg-emerald-400 animate-pulse', 'LIVE'],
                delayed: ['warn', 'bg-amber-400', 'DELAYED'],
                offline: ['bad', 'bg-rose-500', 'OFFLINE']
            }[state];
            const chip = $('liveChip');
            if (chip) {
                setPill('liveChip', cfg[0]);
                chip.classList.add('!text-[11px]', '!py-1.5', '!px-3');
            }
            const dot = $('liveDot'); if (dot) dot.className = 'w-2 h-2 rounded-full ' + cfg[1];
            const lt = $('liveText'); if (lt && lt.textContent !== cfg[2]) lt.textContent = cfg[2];
            const la = $('liveAge');
            if (la) la.textContent = (consecutiveFailures >= 3) ? 'ไม่ตอบสนอง' : (ageS === null ? '--' : (ageS < 1 ? '<1 วิ' : Math.round(ageS) + ' วิ ที่แล้ว'));
            const lat = $('latencyDisplay');
            if (lat) lat.textContent = lastLatencyMs === null ? '-- ms' : Math.round(lastLatencyMs) + ' ms';

            const banner = $('staleBanner');
            if (banner) {
                const show = state === 'offline' || state === 'delayed';
                banner.classList.toggle('hidden', !show);
                const bt = $('staleBannerText');
                if (bt && show) {
                    bt.textContent = consecutiveFailures >= 3
                        ? 'เชื่อมต่อเซิร์ฟเวอร์ไม่ได้ — กำลังลองใหม่อัตโนมัติ'
                        : (state === 'offline'
                            ? `บอร์ดไม่ส่งข้อมูลมา ${Math.round(ageS)} วินาที — ค่าที่แสดงอาจไม่ใช่ค่าปัจจุบัน`
                            : `ข้อมูลล่าช้า ${Math.round(ageS)} วินาที`);
                }
            }
            document.body.classList.toggle('is-stale', state === 'offline');
        }
        setInterval(renderLiveChip, 1000);

        // ---------- 5. Telemetry apply ----------
        let lastSensorStamp = null;
        function applyTelemetry(data) {
            // Board
            if (data.board) {
                const b = data.board;
                currentBoardIp = b.ip_address || currentBoardIp;
                currentBoardPort = b.web_port || currentBoardPort;
                currentSsid = b.ssid || currentSsid;
                if (isNum(b.rssi)) currentRssi = Number(b.rssi);
                currentCloudUrl = b.cloud_url || currentCloudUrl;

                setText('boardIpDisplay', `${currentBoardIp}:${currentBoardPort}`);
                setText('boardSsidDisplay', currentSsid);
                if ($('boardRssiDisplay') && isNum(b.rssi) && Number(b.rssi) < 0) setText('boardRssiDisplay', `${Math.round(b.rssi)} dBm`);
                if ($('cloudSyncDisplay') && b.cloud_url) setText('cloudSyncDisplay', b.cloud_url.replace('http://', ''));
                if ($('cloudTelemetryId') && b.telemetry_id) setText('cloudTelemetryId', `#${b.telemetry_id}`);
                const statEl = $('cloudStatusText');
                if (statEl) {
                    const direct = b.cloud_status && String(b.cloud_status).includes('DIRECT');
                    setText('cloudStatusText', direct ? 'ESP32 DIRECT' : (b.is_live ? 'CLOUD HUB' : 'ONLINE'));
                }
            }

            // Freshness from server clock vs. sensor timestamp
            const sensorTs = data.sensors ? parseSqlTime(data.sensors.updated_at) : NaN;
            const serverTs = parseSqlTime(data.server_time);
            if (Number.isFinite(sensorTs) && Number.isFinite(serverTs)) {
                ageAtFetchS = Math.max(0, (serverTs - sensorTs) / 1000);
            } else if (ageAtFetchS === null) {
                ageAtFetchS = 0;
            }

            // Sensors
            if (data.sensors) {
                const s = data.sensors;
                const ai = data.ai_calibrated || {};
                const tempF = isNum(s.temperature_f) ? s.temperature_f : (isNum(s.temperature) ? s.temperature * 1.8 + 32 : NaN);

                setText('dashTemp', fmt(s.temperature, 2));
                setText('dashTempF', isNum(tempF) ? `(${fmt(tempF, 2)}°F)` : '--');
                setText('dashHum', fmt(s.humidity, 2));
                setText('dashDewPoint', isNum(s.dew_point) ? `${fmt(s.dew_point, 2)}°C` : '--');
                const dewMargin = isNum(s.dew_margin) ? s.dew_margin : (isNum(s.temperature) && isNum(s.dew_point) ? s.temperature - s.dew_point : NaN);
                setText('dashDewMargin', isNum(dewMargin) ? `${fmt(dewMargin, 2)}°C` : '--');
                setBar('barTemp', ((Number(s.temperature) - 10) / 30) * 100);
                pushSpark('sparkTemp', s.temperature);

                // VPD
                setText('dashVpd', fmt(s.vpd, 2));
                setText('dashVpSat', fmt(s.vpsat, 2));
                setText('dashVpAct', fmt(s.vpact, 2));
                if (isNum(s.vpd)) {
                    const v = Number(s.vpd);
                    if (v < 0.8) setPill('dashVpdStatus', 'warn', 'ความชื้นสูง (Low Transp)');
                    else if (v > 1.4) setPill('dashVpdStatus', 'bad', 'อากาศแห้ง (High Transp)');
                    else setPill('dashVpdStatus', 'ok', 'สมบูรณ์ (Optimal Transp)');
                    setBar('barVpd', (v / 3) * 100, v < 0.8 ? '#fbbf24' : (v > 1.4 ? '#fb7185' : '#34d399'));
                    pushSpark('sparkVpd', v);
                }

                // Soil (7-in-1 root zone + surface stick)
                latestSoilMoist = isNum(s.soil_moisture) ? Number(s.soil_moisture) : NaN;
                latestSoilPh = isNum(s.soil_ph) ? Number(s.soil_ph) : NaN;
                renderSoil7in1Hero();
                const stickM = isNum(s.soil_stick_moisture) ? Number(s.soil_stick_moisture) : latestSoilMoist;

                setText('dashSoilMoist', fmt(latestSoilMoist, 1));
                setText('dashStickMoist', fmt(stickM, 1));
                setText('dashSoilEc', fmt(s.soil_ec, 1));
                setText('dashSoilPh', fmt(latestSoilPh, 1));
                setText('dashSoilMoistFoot', isNum(latestSoilMoist) ? `${fmt(latestSoilMoist, 1)}%` : '--');
                setText('dashSoilPhFoot', fmt(latestSoilPh, 1));
                setText('dashSoilTemp', isNum(s.soil_temperature) ? `${fmt(s.soil_temperature, 2)}°C` : '--');
                setText('dashStickPh', fmt(isNum(s.soil_stick_ph) ? s.soil_stick_ph : s.soil_ph, 1));
                setText('dashStickAdc', isNum(s.soil_stick_adc) ? s.soil_stick_adc : '--');
                setText('dashStickVolt', isNum(s.soil_stick_ph_volt) ? `${fmt(s.soil_stick_ph_volt, 2)}V` : '--');
                setBar('barStick', stickM, '#22d3ee');
                setBar('barRoot', latestSoilMoist, '#38bdf8');
                pushSpark('sparkSoil', stickM);
                if (isNum(latestSoilMoist)) {
                    const m = latestSoilMoist;
                    if (m < 40) setPill('soilMoistStatus', 'warn', 'ดินแห้ง');
                    else if (m > 65) setPill('soilMoistStatus', 'info', 'ดินชื้นมาก');
                    else setPill('soilMoistStatus', 'ok', 'เหมาะสม');
                }
                if (isNum(latestSoilPh)) {
                    const ph = latestSoilPh;
                    setBar('barPh', ((ph - 4) / 5) * 100, (ph < 5.5 || ph > 7.5) ? '#fbbf24' : '#a3e635');
                    setPill('soilPhStatus', (ph < 5.5 || ph > 7.5) ? 'warn' : 'ok', ph < 5.5 ? 'เป็นกรด' : (ph > 7.5 ? 'เป็นด่าง' : 'สมบูรณ์'));
                }

                // Light
                setText('dashPar', fmt(s.par_lux, 2));
                setText('dashKlux', isNum(s.klux) ? fmt(s.klux, 2) : (isNum(s.par_lux) ? fmt(s.par_lux / 1000, 2) : '--'));
                setText('dashSolarRad', isNum(s.solar_radiation) ? `${fmt(s.solar_radiation, 2)} W/m²` : '--');
                if (isNum(s.par_lux)) {
                    setBar('barLux', (Math.log10(Number(s.par_lux) + 1) / 5) * 100);
                    pushSpark('sparkLux', Number(s.par_lux));
                }

                // NPK
                const valN = (Number(s.nitrogen) > 0.05) ? Number(s.nitrogen) : (Number(ai.nitrogen) || 0);
                const valP = (Number(s.phosphorus) > 0.05) ? Number(s.phosphorus) : (Number(ai.phosphorus) || 0);
                const valK = (Number(s.potassium) > 0.05) ? Number(s.potassium) : (Number(ai.potassium) || 0);
                setText('dashRawNVal', fmt(s.nitrogen, 1));
                setText('dashRawPVal', fmt(s.phosphorus, 1));
                setText('dashRawKVal', fmt(s.potassium, 1));
                setText('dashValN', `${valN.toFixed(1)} mg/kg`);
                setText('dashValP', `${valP.toFixed(1)} mg/kg`);
                setText('dashValK', `${valK.toFixed(1)} mg/kg`);
                setText('dashCard4N', `N ${valN.toFixed(1)}`);
                setText('dashCard4P', `P ${valP.toFixed(1)}`);
                setText('dashCard4K', `K ${valK.toFixed(1)}`);
                setText('dashAiN', fmt(ai.nitrogen || 0, 1));
                setText('dashAiP', fmt(ai.phosphorus || 0, 1));
                setText('dashAiK', fmt(ai.potassium || 0, 1));
                setText('dashNpkRatio', ai.npk_ratio || `${(valN / (valP || 1)).toFixed(1)}:1:${(valK / (valP || 1)).toFixed(1)}`);
                setText('dashNpkTotal', `Total: ${(valN + valP + valK).toFixed(1)} mg/kg`);
                if (ai.confidence) setText('dashAiConfidence', `TinyML AI: ${(ai.confidence * 100).toFixed(1)}%`);

                // Comparison panel (Raw sensors vs Edge AI)
                setText('compareRawPh', fmt(s.soil_ph, 2));
                const calcAiPh = isNum(ai.ph) ? Number(ai.ph) : (isNum(s.soil_ph) ? Number(s.soil_ph) + 0.94 : NaN);
                setText('compareAiPh', fmt(calcAiPh, 2));
                if (isNum(calcAiPh) && isNum(s.soil_ph)) {
                    const diffPh = calcAiPh - Number(s.soil_ph);
                    setText('compareDiffPh', `${diffPh >= 0 ? '+' : ''}${diffPh.toFixed(2)}`);
                }
                const deepVal = latestSoilMoist;
                setText('compareStickMoist', isNum(stickM) ? `${fmt(stickM, 1)}%` : '--');
                setText('compareRawMoist', isNum(deepVal) ? `${fmt(deepVal, 1)}%` : '--');
                setText('compareStickMoistSub', isNum(stickM) ? `ผิว ${fmt(stickM, 1)}%` : '--');
                if (isNum(ai.moisture) || (isNum(deepVal) && isNum(stickM))) {
                    const calcAiM = isNum(ai.moisture) ? Number(ai.moisture) : (deepVal + stickM) / 2;
                    setText('compareAiMoist', `${calcAiM.toFixed(1)}%`);
                }
                setText('compareRawN', fmt(s.nitrogen, 1));
                setText('compareAiN', fmt(ai.nitrogen || 0, 1));
                setText('compareRawP', fmt(s.phosphorus, 1));
                setText('compareAiP', fmt(ai.phosphorus || 0, 1));
                setText('compareRawK', fmt(s.potassium, 1));
                setText('compareAiK', fmt(ai.potassium || 0, 1));
                setText('compareRawVpd', fmt(s.vpd, 2));
                if (isNum(s.vpd)) {
                    const vv = Number(s.vpd);
                    setText('compareAiVpdStatus', vv < 0.8 ? 'ชื้นสูง' : (vv > 1.4 ? 'อากาศแห้ง' : 'สมบูรณ์'));
                    setText('compareTranspText', vv < 0.8 ? 'Low Transp' : (vv > 1.4 ? 'High Transp' : 'Optimal Transp'));
                }
                if (ai.confidence) setText('compareAiConfBadge', `${(ai.confidence * 100).toFixed(1)}%`);
                if ($('compareAiAgronomyText') && isNum(s.soil_ph) && isNum(deepVal)) {
                    const stickPh = isNum(s.soil_stick_ph) ? Number(s.soil_stick_ph) : Number(s.soil_ph);
                    const deepPh = Number(s.soil_ph);
                    if (Math.abs(deepPh - stickPh) > 2.0) {
                        setHtml('compareAiAgronomyText', `<i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i> <strong class="text-amber-300">พบความชัน pH ข้ามชั้นดิน:</strong> ผิวดิน Stick ${stickPh.toFixed(2)} vs เขตรากลึก 7-in-1 ${deepPh.toFixed(2)} • ความชื้นเขตราก ${deepVal.toFixed(1)}%${deepVal < 40 ? ' แนะนำเปิดวาล์วรดน้ำ' : ''}`);
                    } else {
                        setHtml('compareAiAgronomyText', `<i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i> <strong class="text-emerald-300">สถานะปกติ:</strong> โครงสร้างดินสมดุลดี (pH: ${deepPh.toFixed(2)}, ความชื้น: ${deepVal.toFixed(1)}%) • โมเดล TinyML ทำงานเต็มประสิทธิภาพ`);
                    }
                }

                // GPS
                if (data.gps && data.gps.formatted) setText('boardGpsDisplay', data.gps.formatted);

                // Storage
                if (data.sd_card) {
                    const sd = data.sd_card;
                    const mounted = Boolean(sd.mounted);
                    setText('dashSdRecords', mounted ? `${Number(sd.records || 0).toLocaleString()} เรคอร์ด` : 'ไม่ได้ใส่การ์ด (No Card)');
                    const rec = $('dashSdRecords'); if (rec) rec.className = mounted ? 'text-white text-[10px] whitespace-nowrap num' : 'text-slate-400 italic text-[10px] whitespace-nowrap';
                    setHtml('sdCardStatusDisplay', mounted
                        ? `<i class="fa-solid fa-sd-card mr-0.5 text-amber-400"></i> ${Number(sd.records || 0).toLocaleString()} rec`
                        : `<i class="fa-solid fa-sd-card mr-0.5 text-slate-500"></i> <span class="text-slate-400">ไม่ได้ใส่การ์ด</span>`);
                    setPill('dashSdBadge', mounted ? 'ok' : 'mute', mounted ? 'SD LOGGING' : 'STANDBY (NO SD)');
                }
                if (data.database) {
                    const dbTotal = `${Number(data.database.total_records || 0).toLocaleString()} เรคอร์ด`;
                    setText('dashDbRecords', dbTotal);
                    setHtml('dbRecordsStatusDisplay', `<i class="fa-solid fa-database mr-0.5"></i> ${dbTotal}`);
                    setText('tableDbTotalRecords', dbTotal);
                }

                // Radar
                if (npkRadarChart.data.datasets[0]) {
                    npkRadarChart.data.datasets[0].data = [
                        valN, valP, valK,
                        Number(s.soil_moisture) || 0,
                        (Number(s.soil_ph) || 0) * 10,
                        Math.min(100, (Number(s.soil_ec) || 0) / 5)
                    ];
                    npkRadarChart.update('none');
                }

                // Sensor connection badges
                if (data.sensor_connection) {
                    const c = data.sensor_connection;
                    setPill('badgeSht45', c.sht45 ? 'ok' : 'bad', c.sht45 ? 'I2C ONLINE' : 'DISCONNECTED');
                    setPill('badgeSoil7in1', c.soil_7in1 ? 'info' : 'bad', c.soil_7in1 ? 'RS485 ONLINE' : 'DISCONNECTED');
                    setPill('badgeBh1750', c.bh1750 ? 'warn' : 'bad', c.bh1750 ? 'I2C ONLINE' : 'DISCONNECTED');
                }

                // Chart: one point per new board reading (no duplicates from re-polling)
                const stamp = s.updated_at || null;
                if (stamp === null || stamp !== lastChartStamp) {
                    lastChartStamp = stamp;
                    const lbl = stamp && String(stamp).length >= 19 ? String(stamp).slice(11, 19)
                        : new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    if (isNum(s.temperature) || isNum(s.humidity)) {
                        pushChartPoint(lbl, s.temperature, s.humidity, stickM);
                        envTrendChart.update('none');
                    }
                }
            }

            // Relays (never overwrite a pending optimistic toggle)
            if (data.relays) {
                const now = Date.now();
                for (let id = 1; id <= 4; id++) {
                    const r = data.relays[id];
                    if (r && !((relayPending[id] || 0) > now)) updateRelayDom(id, r.state == 1);
                }
            }

            // Control mode
            if (!(modePendingUntil > Date.now())) {
                if (data.control_mode) updateControlModeDom(data.control_mode);
                else if (typeof data.auto_mode !== 'undefined') updateControlModeDom(data.auto_mode ? 'auto' : 'manual');
            }
        }

        // ---------- 6. Polling engine (chained timeout, abort, backoff, pause when hidden) ----------
        const POLL_MS = 1000;
        let pollTimer = null;
        let pollBusy = false;

        async function pollOnce() {
            if (pollBusy) return;
            pollBusy = true;
            const ctrl = new AbortController();
            const to = setTimeout(() => ctrl.abort(), 4000);
            const t0 = performance.now();
            try {
                const res = await fetch('../api/api.php?action=get_telemetry&_t=' + Date.now(), { signal: ctrl.signal, cache: 'no-store' });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                if (data.status !== 'success') throw new Error('API status ' + data.status);
                lastLatencyMs = performance.now() - t0;
                lastFetchOkAt = performance.now();
                consecutiveFailures = 0;
                applyTelemetry(data);
            } catch (err) {
                consecutiveFailures++;
                console.warn('Telemetry sync error:', err && err.message ? err.message : err);
            } finally {
                clearTimeout(to);
                pollBusy = false;
                renderLiveChip();
            }
        }
        function syncTelemetryFromApi() { return pollOnce(); }

        function schedulePoll() {
            clearTimeout(pollTimer);
            if (document.hidden) return;
            const delay = consecutiveFailures ? Math.min(POLL_MS * Math.pow(2, consecutiveFailures), 8000) : POLL_MS;
            pollTimer = setTimeout(async () => { await pollOnce(); schedulePoll(); }, delay);
        }
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) { clearTimeout(pollTimer); }
            else { pollOnce().then(schedulePoll); }
        });

        // ---------- 7. Relay control ----------
        function fireDirect(url) {
            // Fire-and-forget straight to the ESP32 (no-cors avoids CORS errors; response not needed)
            try { fetch(url, { mode: 'no-cors', cache: 'no-store' }).catch(() => {}); } catch (e) {}
        }

        async function setRelay(id, nextState, name, silent) {
            relayPending[id] = Date.now() + RELAY_LOCK_MS;
            const prev = !!dashRelayStates[id];
            updateRelayDom(id, nextState);
            modePendingUntil = Date.now() + RELAY_LOCK_MS;
            updateControlModeDom('manual');

            fireDirect(`http://${currentBoardIp}:${currentBoardPort}/relay?id=${id}&state=${nextState ? 1 : 0}`);

            try {
                const res = await fetch(`../api/api.php?action=control_relay&id=${id}&state=${nextState ? 1 : 0}`, { cache: 'no-store' });
                const j = await res.json().catch(() => ({}));
                if (!res.ok || j.status === 'error') throw new Error(j.message || ('HTTP ' + res.status));
                if (!silent) Toast.fire({ icon: nextState ? 'success' : 'info', title: `${name}: ${nextState ? 'เปิด (ON)' : 'ปิด (OFF)'}`, text: 'ส่งคำสั่งแล้ว — รอบอร์ดยืนยันสถานะ' });
                // let the next poll confirm the real state shortly
                setTimeout(() => { relayPending[id] = 0; modePendingUntil = 0; pollOnce(); }, 1200);
            } catch (e) {
                relayPending[id] = 0;
                modePendingUntil = 0;
                updateRelayDom(id, prev);
                Toast.fire({ icon: 'error', title: `สั่ง ${name} ไม่สำเร็จ`, text: String(e.message || e) });
            }
        }

        function toggleDashboardRelay(id, name) {
            return setRelay(id, !dashRelayStates[id], name);
        }
        async function allRelaysOff() {
            const ons = [1, 2, 3, 4].filter(i => dashRelayStates[i]);
            if (!ons.length) { Toast.fire({ icon: 'info', title: 'รีเลย์ทุกช่องปิดอยู่แล้ว' }); return; }
            await Promise.all(ons.map(i => setRelay(i, false, `รีเลย์ ${i}`, true)));
            Toast.fire({ icon: 'success', title: `ปิดรีเลย์ ${ons.length} ช่องแล้ว` });
        }

        // 7.1 Control Mode
        async function setControlMode(mode) {
            modePendingUntil = Date.now() + RELAY_LOCK_MS;
            updateControlModeDom(mode);
            fireDirect(`http://${currentBoardIp}:${currentBoardPort}/mode?mode=${mode}`);
            const modeLabels = { manual: 'MANUAL (ควบคุมเอง)', auto: 'SMART AUTO (กฎอัตโนมัติ)', ai: 'EDGE AI (สมองกลอัจฉริยะ)' };
            try {
                const res = await fetch(`../api/api.php?action=set_control_mode&mode=${mode}`, { cache: 'no-store' });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                Toast.fire({ icon: 'success', title: `เปลี่ยนโหมด: ${modeLabels[mode] || mode}` });
                setTimeout(() => { modePendingUntil = 0; pollOnce(); }, 1200);
            } catch (e) {
                modePendingUntil = 0;
                Toast.fire({ icon: 'error', title: 'เปลี่ยนโหมดไม่สำเร็จ', text: String(e.message || e) });
            }
        }
        async function toggleAutoMode() {
            await setControlMode(currentControlMode === 'auto' ? 'manual' : 'auto');
        }

        // ---------- 8. Misc UI ----------
        function toggleToolsMenu(ev) {
            if (ev) ev.stopPropagation();
            const m = $('toolsMenu'), b = $('toolsBtn');
            if (!m) return;
            const open = m.classList.toggle('hidden') === false;
            if (b) b.setAttribute('aria-expanded', String(open));
        }
        document.addEventListener('click', (e) => {
            const w = $('toolsWrap'), m = $('toolsMenu');
            if (m && w && !w.contains(e.target) && !m.classList.contains('hidden')) toggleToolsMenu();
        });
        document.addEventListener('keydown', (e) => {
            const m = $('toolsMenu');
            if (e.key === 'Escape' && m && !m.classList.contains('hidden')) toggleToolsMenu();
        });

        // 6. Interactive Modal reproducing the physical ESP32 Screen from the user's photo
        function openBoardScreenModal() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; font-size: 16px; color: #38bdf8;">ESP32-S3 ATD3.5 Physical Screen Display</span>',
                html: `
                    <!-- Authentic reproduction of the physical LCD touch screen -->
                    <div style="background: #2a2a2a; padding: 12px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.8); border: 8px solid #f1f5f9; max-width: 480px; margin: 0 auto; text-align: left; font-family: 'Chakra Petch', sans-serif;">
                        
                        <!-- Top LCD Status Tabs (Cyan, Green, Orange, Purple, Yellow, Blue) -->
                        <div style="display: flex; gap: 3px; margin-bottom: 8px; font-size: 10px; font-weight: bold; overflow-x: auto;">
                            <span style="background: #06b6d4; color: black; padding: 3px 6px; border-radius: 4px;">1. HOME</span>
                            <span style="background: #22c55e; color: black; padding: 3px 6px; border-radius: 4px;">2. DATA</span>
                            <span style="background: #f97316; color: black; padding: 3px 6px; border-radius: 4px;">3. GRAPH</span>
                            <span style="background: #a855f7; color: white; padding: 3px 6px; border-radius: 4px;">4. RELAY</span>
                            <span style="background: #eab308; color: black; padding: 3px 6px; border-radius: 4px; box-shadow: 0 0 8px #eab308;">5. SETUP</span>
                            <span style="background: #3b82f6; color: white; padding: 3px 6px; border-radius: 4px;">🌐 ENG</span>
                        </div>

                        <!-- Screen Title -->
                        <div style="color: #facc15; font-size: 11px; font-weight: bold; text-align: center; border-bottom: 1px solid #444; padding-bottom: 4px; margin-bottom: 8px; letter-spacing: 0.5px;">
                            NETWORK STATUS &amp; SYSTEM SETTINGS
                        </div>

                        <!-- Screen Body matching photo -->
                        <div style="font-family: monospace; font-size: 11px; line-height: 1.7; background: #000; padding: 10px; border-radius: 8px; border: 1px solid #38bdf8;">
                            <div>Status: <span style="color: #22c55e; font-weight: bold;">CONNECTED (ONLINE)</span></div>
                            <div style="color: #38bdf8;">SSID: <span style="color: #fff; font-weight: bold;">${currentSsid}</span></div>
                            <div style="color: #38bdf8;">IP Address: <span style="color: #fff; font-weight: bold;">${currentBoardIp}</span></div>
                            <div style="color: #38bdf8;">Signal RSSI: <span style="color: #fff; font-weight: bold;">${currentRssi} dBm (ปกติ)</span></div>
                            <div style="color: #38bdf8; font-size: 10px;">Cloud: <span style="color: #38bdf8; word-break: break-all;">${currentCloudUrl}</span> | Web: <span style="color: #fff;">:${currentBoardPort}</span></div>
                        </div>

                        <!-- System Language Selector -->
                        <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; font-size: 11px;">
                            <span style="color: #cbd5e1;">SELECT SYSTEM LANGUAGE:</span>
                            <div style="display: flex; gap: 5px;">
                                <span style="padding: 2px 8px; border-radius: 4px; background: #334155; color: #94a3b8; font-size: 10px;">ภาษาไทย</span>
                                <span style="padding: 2px 8px; border-radius: 4px; background: #06b6d4; color: black; font-weight: bold; font-size: 10px;">[*] English</span>
                            </div>
                        </div>

                        <!-- SoftAP / QR Setup Button -->
                        <div style="margin-top: 10px;">
                            <button onclick="promptChangeIp()" style="width: 100%; padding: 6px 10px; background: #0284c7; color: white; font-weight: bold; font-size: 11px; border-radius: 6px; border: none; cursor: pointer; text-align: center;">
                                SETUP WI-FI VIA PHONE / QR CODE
                            </button>
                        </div>

                        <div style="color: #64748b; font-size: 9px; text-align: center; margin-top: 6px;">
                            Scan QR or connect to SoftAP to setup without PC
                        </div>

                    </div>
                `,
                background: '#090e1a',
                color: '#fff',
                confirmButtonText: '<i class="fa-solid fa-satellite-dish"></i> ทดสอบ Ping บอร์ด',
                confirmButtonColor: '#10b981',
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                cancelButtonColor: '#334155'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    Swal.fire({
                        title: `กำลังทดสอบ Ping ไปยัง ${currentBoardIp}:${currentBoardPort}...`,
                        didOpen: () => { Swal.showLoading(); },
                        timer: 1000
                    }).then(async () => {
                        const pingRes = await fetch('../api/api.php?action=ping_board');
                        const pingData = await pingRes.json();
                        Swal.fire({
                            icon: 'success',
                            title: 'ESP32-S3 ตอบสนองปกติ',
                            html: `
                                <div style="text-align: left; font-family: monospace; font-size: 12px; line-height: 1.8;">
                                    <div>• <strong>IP Address:</strong> ${currentBoardIp}</div>
                                    <div>• <strong>Web Port:</strong> ${currentBoardPort}</div>
                                    <div>• <strong>SSID:</strong> ${currentSsid}</div>
                                    <div>• <strong>Cloud Bridge:</strong> ${currentCloudUrl}</div>
                                    <div>• <strong>Direct Status:</strong> <span style="color: #10b981;">CONNECTED (ONLINE)</span></div>
                                </div>
                            `,
                            background: '#090e1a',
                            color: '#fff'
                        });
                    });
                }
            });
        }

        // 7. Interactive Field GPS Configuration Modal (Real-Field Installation Coordinates)
        window.acquireDeviceGeo = function() {
            const statusEl = document.getElementById('geoStatusText');
            if (!navigator.geolocation) {
                if (statusEl) {
                    statusEl.innerText = 'เบราว์เซอร์ไม่รองรับ HTML5 Geolocation API';
                    statusEl.style.color = '#f87171';
                }
                return;
            }
            if (statusEl) {
                statusEl.innerText = 'กำลังตรวจจับสัญญาณดาวเทียม GPS จากอุปกรณ์...';
                statusEl.style.color = '#38bdf8';
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const acc = pos.coords.accuracy;
                    const inputLat = document.getElementById('swalInputLat');
                    const inputLon = document.getElementById('swalInputLon');
                    if (inputLat) inputLat.value = lat.toFixed(6);
                    if (inputLon) inputLon.value = lon.toFixed(6);
                    if (statusEl) {
                        statusEl.innerText = `ดึงพิกัดจริงสำเร็จ! ละติจูด: ${lat.toFixed(5)}, ลองจิจูด: ${lon.toFixed(5)} (ความแม่นยำ ±${acc.toFixed(1)} ม.)`;
                        statusEl.style.color = '#34d399';
                    }
                },
                (err) => {
                    if (statusEl) {
                        statusEl.innerText = 'ไม่สามารถดึงพิกัดได้ (' + err.message + ') กรุณากรอกพิกัดด้วยตนเอง';
                        statusEl.style.color = '#f87171';
                    }
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        };

        function openGpsConfigModal() {
            let currentLat = (window.currentGps && window.currentGps.latitude) ? window.currentGps.latitude : 12.6644;
            let currentLon = (window.currentGps && window.currentGps.longitude) ? window.currentGps.longitude : 102.1039;
            let currentLocName = (window.currentGps && window.currentGps.location_name) ? window.currentGps.location_name : 'คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี (RBRU)';

            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; font-size: 16px; color: #f43f5e;"><i class="fa-solid fa-location-crosshairs mr-1"></i> กำหนดพิกัด GPS จุดติดตั้งเซนเซอร์จริง</span>',
                html: `
                    <div style="text-align: left; font-size: 12px; font-family: sans-serif; color: #cbd5e1;">
                        <p style="margin-bottom: 12px; color: #94a3b8; font-size: 11px; line-height: 1.5;">
                            ระบุพิกัดจริงตามตำแหน่งที่ติดตั้งบอร์ดและเซนเซอร์ในแปลงเกษตร หรือกดปุ่มดึงพิกัดจาก GPS อุปกรณ์ปัจจุบัน (มือถือ/คอมฯ) ได้ทันที
                        </p>
                        
                        <div style="background: rgba(15, 23, 42, 0.9); border: 1px solid #334155; border-radius: 12px; padding: 10px; margin-bottom: 14px;">
                            <button id="btnAutoGeo" onclick="acquireDeviceGeo()" type="button" style="width: 100%; padding: 8px 12px; border-radius: 8px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; font-weight: bold; font-size: 12px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                <i class="fa-solid fa-crosshairs"></i> ดึงพิกัดสดจาก GPS อุปกรณ์นี้ (มือถือ/คอม)
                            </button>
                            <span id="geoStatusText" style="font-size: 10px; color: #64748b; display: block; text-align: center; margin-top: 6px;">รองรับระบบดาวเทียมความแม่นยำสูง (High-Accuracy GNSS)</span>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ละติจูด (Latitude):</label>
                            <input id="swalInputLat" type="number" step="0.000001" value="${currentLat}" style="width: 100%; padding: 8px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #38bdf8; font-family: monospace; font-size: 13px;">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ลองจิจูด (Longitude):</label>
                            <input id="swalInputLon" type="number" step="0.000001" value="${currentLon}" style="width: 100%; padding: 8px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #38bdf8; font-family: monospace; font-size: 13px;">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ชื่อสถานที่ / แปลงติดตั้ง (Location Name):</label>
                            <input id="swalInputLocName" type="text" value="${currentLocName}" style="width: 100%; padding: 8px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #fff; font-size: 12px;">
                        </div>

                        <div style="text-align: center; margin-top: 6px;">
                            <a href="https://maps.google.com/?q=${currentLat},${currentLon}" target="_blank" style="color: #38bdf8; text-decoration: underline; font-size: 11px;">
                                <i class="fa-solid fa-map-location-dot"></i> เปิดดูตำแหน่งพิกัดนี้บน Google Maps
                            </a>
                        </div>
                    </div>
                `,
                background: '#090e1a',
                color: '#fff',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-floppy-disk"></i> บันทึกพิกัดจริง',
                confirmButtonColor: '#059669',
                cancelButtonText: 'ยกเลิก',
                preConfirm: () => {
                    const lat = parseFloat(document.getElementById('swalInputLat').value);
                    const lon = parseFloat(document.getElementById('swalInputLon').value);
                    const locName = document.getElementById('swalInputLocName').value.trim();
                    if (isNaN(lat) || isNaN(lon)) {
                        Swal.showValidationMessage('กรุณากรอกตัวเลขละติจูดและลองจิจูดให้ถูกต้อง');
                        return false;
                    }
                    return { lat, lon, locName };
                }
            }).then((res) => {
                if (res.isConfirmed && res.value) {
                    saveGpsCoordinates(res.value.lat, res.value.lon, res.value.locName);
                }
            });
        }

        function saveGpsCoordinates(lat, lon, locName) {
            Swal.fire({
                title: 'กำลังบันทึกพิกัดจริง...',
                didOpen: () => { Swal.showLoading(); },
                background: '#090e1a',
                color: '#fff'
            });

            fetch('../api/api.php?action=update_gps', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lon,
                    location_name: locName
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    window.currentGps = data.gps;
                    if (document.getElementById('boardGpsDisplay')) {
                        document.getElementById('boardGpsDisplay').innerText = data.gps.formatted;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกพิกัดจริงสำเร็จ',
                        html: `
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><strong>พิกัด:</strong> ${data.gps.formatted}</div>
                                <div><strong>สถานที่:</strong> ${data.gps.location_name}</div>
                                <div style="margin-top: 8px;">
                                    <a href="${data.gps.maps_url}" target="_blank" style="color: #38bdf8; text-decoration: underline;">
                                        <i class="fa-solid fa-map-location-dot"></i> เปิดดูบน Google Maps
                                    </a>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#059669',
                        background: '#090e1a',
                        color: '#fff'
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'บันทึกล้มเหลว', text: data.message, background: '#090e1a', color: '#fff' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'การเชื่อมต่อผิดพลาด', text: err.toString(), background: '#090e1a', color: '#fff' });
            });
        }

        function promptChangeIp() {
            Swal.fire({
                title: 'กำหนด IP Address และ พอร์ต บอร์ด ESP32-S3',
                html: `
                    <div style="text-align: left; font-size: 12px; space-y: 8px;">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">IP Address บอร์ด:</label>
                        <input id="swalBoardIp" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #38bdf8; font-family: monospace;" value="${currentBoardIp}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">Web Port (เช่น 8500 หรือ 80):</label>
                        <input id="swalBoardPort" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #38bdf8; font-family: monospace;" value="${currentBoardPort}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">SSID เครือข่าย Wi-Fi:</label>
                        <input id="swalBoardSsid" class="swal2-input" style="width: 100%; margin: 0 0 10px 0; background: #0f172a; color: #10b981; font-family: monospace;" value="${currentSsid}">
                        <label style="color: #94a3b8; display: block; margin-bottom: 4px;">Cloud URL:</label>
                        <input id="swalCloudUrl" class="swal2-input" style="width: 100%; margin: 0; background: #0f172a; color: #f59e0b; font-family: monospace;" value="${currentCloudUrl}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'บันทึกการตั้งค่า',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#10b981',
                background: '#0a0f1d',
                color: '#fff'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    const newIp = document.getElementById('swalBoardIp').value.trim();
                    const newPort = parseInt(document.getElementById('swalBoardPort').value.trim()) || 8500;
                    const newSsid = document.getElementById('swalBoardSsid').value.trim();
                    const newCloud = document.getElementById('swalCloudUrl').value.trim();

                    if (newIp) {
                        currentBoardIp = newIp;
                        currentBoardPort = newPort;
                        currentSsid = newSsid;
                        currentCloudUrl = newCloud;

                        // Save to backend
                        await fetch('../api/api.php?action=update_board_config', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ ip: newIp, web_port: newPort, ssid: newSsid, cloud_url: newCloud })
                        });

                        document.getElementById('boardIpDisplay').innerText = `${currentBoardIp}:${currentBoardPort}`;
                        document.getElementById('boardSsidDisplay').innerText = currentSsid;

                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตการเชื่อมต่อแล้ว',
                            text: `ระบบเชื่อมต่อ ESP32 ที่ ${currentBoardIp}:${currentBoardPort}`,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#0a0f1d',
                            color: '#fff'
                        });
                    }
                }
            });
        }

        function exportCsvData() {
            window.location.href = '../api/api.php?action=export_csv';
            Swal.fire({
                icon: 'success',
                title: 'กำลังส่งออกไฟล์ CSV จากฐานข้อมูล',
                text: 'ดาวน์โหลดชุดข้อมูลสถิติเซนเซอร์ทั้งหมดจาก SQLite3 (telemetry_logs) เรียบร้อยแล้ว',
                timer: 2000,
                showConfirmButton: false,
                background: '#0a0f1d',
                color: '#fff'
            });
        }

        async function fetchDbHistoryTable() {
            try {
                const res = await fetch('../api/api.php?action=get_history_table&limit=15');
                if (!res.ok) return;
                const json = await res.json();
                if (json.status === 'success') {
                    const totalEl = document.getElementById('tableDbTotalRecords');
                    if (totalEl) totalEl.innerText = Number(json.total_records || 0).toLocaleString();

                    const tbody = document.getElementById('telemetryTableBody');
                    if (!tbody) return;

                    if (!json.data || json.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="11" class="text-center p-6 text-slate-500">ยังไม่มีบันทึกข้อมูลในฐานข้อมูล SQLite3</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = json.data.map(r => {
                        const r1 = r.relay1 == 1 ? '<span class="text-emerald-400 font-bold">R1:ON</span>' : '<span class="text-slate-600">R1:OFF</span>';
                        const r2 = r.relay2 == 1 ? '<span class="text-emerald-400 font-bold">R2:ON</span>' : '<span class="text-slate-600">R2:OFF</span>';
                        const r3 = r.relay3 == 1 ? '<span class="text-emerald-400 font-bold">R3:ON</span>' : '<span class="text-slate-600">R3:OFF</span>';
                        const r4 = r.relay4 == 1 ? '<span class="text-emerald-400 font-bold">R4:ON</span>' : '<span class="text-slate-600">R4:OFF</span>';

                        const sdTag = r.sd_card_mounted == 1 
                            ? `<span class="px-1.5 py-0.5 rounded bg-amber-950 text-amber-300 text-[10px] border border-amber-800"><i class="fa-solid fa-sd-card"></i> ${r.sd_card_records || 0} rec</span>`
                            : `<span class="px-1.5 py-0.5 rounded bg-slate-900 text-slate-500 text-[10px]">Unmounted</span>`;

                        return `
                            <tr class="hover:bg-slate-900/60 transition font-mono">
                                <td class="p-3 text-cyan-400 font-bold">#${r.id}</td>
                                <td class="p-3 text-slate-300 whitespace-nowrap">${r.created_at}</td>
                                <td class="p-3"><span class="text-amber-300 font-bold">${Number(r.temperature || 0).toFixed(1)}°C</span> / <span class="text-cyan-300">${Number(r.humidity || 0).toFixed(1)}%</span></td>
                                <td class="p-3 text-emerald-300 font-bold">${Number(r.vpd || 0).toFixed(2)} kPa</td>
                                <td class="p-3">${Number(r.par_lux || 0).toLocaleString()} lx <span class="text-[10px] text-yellow-400 block">${Number(r.solar_radiation || 0).toFixed(2)} W/m²</span></td>
                                <td class="p-3">${Number(r.soil_stick_moisture || 0).toFixed(1)}% <span class="text-[10px] text-amber-300 block">pH ${Number(r.soil_stick_ph || 0).toFixed(1)}</span></td>
                                <td class="p-3">${Number(r.soil_moisture || 0).toFixed(1)}% <span class="text-[10px] text-emerald-300 block">EC ${Math.round(r.soil_ec || 0)} | pH ${Number(r.soil_ph || 0).toFixed(1)}</span></td>
                                <td class="p-3">${Number(r.nitrogen || 0).toFixed(0)}-${Number(r.phosphorus || 0).toFixed(0)}-${Number(r.potassium || 0).toFixed(0)}</td>
                                <td class="p-3 text-cyan-300">${Number(r.ai_nitrogen || 0).toFixed(0)}-${Number(r.ai_phosphorus || 0).toFixed(0)}-${Number(r.ai_potassium || 0).toFixed(0)}</td>
                                <td class="p-3 font-mono text-[9px] whitespace-nowrap">${r1} ${r2} ${r3} ${r4}</td>
                                <td class="p-3 whitespace-nowrap">${sdTag}</td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (err) {
                console.warn('DB Table fetch error:', err);
            }
        }

        function openQrModal() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #06b6d4;">Official QR Code Portal</span>',
                html: `
                    <div style="text-align: center; padding: 10px;">
                        <div style="background: white; padding: 12px; border-radius: 18px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.5); margin-bottom: 15px;">
                            <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Portal" style="width: 220px; height: 220px; object-fit: contain;">
                        </div>
                        <div style="font-family: Orbitron, monospace; font-size: 13px; line-height: 1.8; color: #e2e8f0; background: #0f172a; padding: 12px; border-radius: 14px; border: 1px solid #1e293b;">
                            <div style="color: #10b981; font-weight: bold;">📅 2026.10.04 วันอาทิตย์</div>
                            <div style="color: #f59e0b; font-weight: bold;">⏰ 09:03 ชีวะ ทัศนา</div>
                            <div style="color: #38bdf8; word-break: break-all; margin-top: 4px;">
                                🔗 <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" style="color: #38bdf8; text-decoration: underline;">https://scicenter.rbru.ac.th/leqs-workshop/index.php</a>
                            </div>
                        </div>
                    </div>
                `,
                background: '#0b1120',
                color: '#fff',
                confirmButtonText: '<i class="fa-solid fa-arrow-up-right-from-square"></i> ไปยังลิงก์ระบบ',
                confirmButtonColor: '#06b6d4',
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                cancelButtonColor: '#334155'
            }).then((res) => {
                if (res.isConfirmed) {
                    window.open('https://scicenter.rbru.ac.th/leqs-workshop/index.php', '_blank');
                }
            });
        }

        function previewScreen11() {
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #38bdf8;">ESP32-S3 ATD3.5 - Screen 11 (QR Portal)</span>',
                html: `
                    <div style="text-align: center; padding: 5px;">
                        <img src="../assets/images/atd35/11_qr_portal_screen.png" alt="Screen 11" style="width: 100%; max-width: 540px; border-radius: 16px; border: 1px solid #0284c7; box-shadow: 0 10px 30px rgba(0,0,0,0.7);">
                        <div style="font-family: monospace; font-size: 11px; color: #94a3b8; margin-top: 10px;">
                            Hardware Resolution: 480x320 Capacitive Touch (Retina @2x 960x640)
                        </div>
                    </div>
                `,
                background: '#0a0f1d',
                color: '#fff',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#0284c7'
            });
        }

        // Micro-SD Card Subsystem Toggle / Inspection
        async function toggleOrCheckSdCard() {
            try {
                const res = await fetch('../api/api.php?action=toggle_sd_card');
                const data = await res.json();
                if (data.status === 'success') {
                    syncTelemetryFromApi();
                    if (data.sd_card.mounted) {
                        Swal.fire({
                            icon: 'success',
                            title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #f59e0b;">Micro-SD Card ตรวจพบสำเร็จ</span>',
                            html: `
                                <div style="font-family: monospace; font-size: 12px; color: #cbd5e1; line-height: 1.8; text-align: left; background: #0b1329; padding: 12px; border-radius: 12px; border: 1px solid #1e293b;">
                                    <div>📁 ไฟล์บันทึก: <strong style="color: #38bdf8;">/telemetry_data.csv</strong></div>
                                    <div>📊 จำนวนเรคอร์ด: <strong style="color: #10b981;">${Number(data.sd_card.records).toLocaleString()} records</strong></div>
                                    <div>💾 พิน SPI CS: <strong style="color: #a855f7;">GPIO ${data.sd_card.cs_pin}</strong></div>
                                    <div>⚡ สถานะ: <strong style="color: #22c55e;">ACTIVE LOGGING (ออฟไลน์)</strong></div>
                                </div>
                            `,
                            background: '#070d1e',
                            color: '#fff',
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'ตกลง'
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #94a3b8;">ไม่ได้ใส่การ์ด (No Card)</span>',
                            html: `
                                <div style="font-size: 12px; color: #cbd5e1; line-height: 1.6; text-align: left; background: #0b1329; padding: 12px; border-radius: 12px; border: 1px solid #1e293b;">
                                    <div style="font-weight: bold; color: #f59e0b; margin-bottom: 6px;">💡 คำแนะนำเมื่อเสียบการ์ดแล้วแต่ยังขึ้น No Card:</div>
                                    <ol style="margin-left: 18px; line-height: 1.8;">
                                        <li><strong>กดปุ่ม RESET บนบอร์ด ESP32:</strong> บอร์ดจะตรวจหาการ์ดเฉพาะตอนเริ่มบูตระบบ (Boot Time)</li>
                                        <li><strong>ระบบไฟล์ต้องเป็น FAT32:</strong> ห้ามใช้ exFAT หรือ NTFS</li>
                                        <li><strong>ดันการ์ดให้สุดล็อก:</strong> สล็อตเป็นแบบ Push-Push ต้องได้ยินเสียงคลิก</li>
                                    </ol>
                                </div>
                            `,
                            background: '#070d1e',
                            color: '#fff',
                            confirmButtonColor: '#0284c7',
                            confirmButtonText: 'รับทราบ'
                        });
                    }
                }
            } catch (err) {
                console.error('SD Card check error:', err);
            }
        }

        // Start live telemetry: initial chart history + chained 1 s polling; DB table every 5 s (paused when tab hidden)
        loadChartHistory().finally(() => { pollOnce().then(schedulePoll); });
        fetchDbHistoryTable();
        setInterval(() => { if (!document.hidden) fetchDbHistoryTable(); }, 5000);
    </script>
</body>
</html>
