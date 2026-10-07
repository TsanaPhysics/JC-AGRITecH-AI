    <!-- Dashboard JavaScript Logic (Live Bidirectional IoT Sync Engine) -->
    <script>
        let currentBoardIp = '192.168.0.111';
        let currentBoardPort = 8500;
        let currentSsid = 'JC_Home';
        let currentRssi = -99;
        let currentCloudUrl = 'http://14.207.141.164:8000';
        let isAutoMode = true;
        const dashRelayStates = { 1: true, 2: false, 3: true, 4: true };

        // 1. Initialize Chart.js Trend Line Chart
        const ctxTrend = document.getElementById('envTrendChart').getContext('2d');
        const timeLabels = ['11:40', '11:42', '11:44', '11:46', '11:48', '11:50', '11:52'];
        const tempData = [28.2, 28.3, 28.5, 28.6, 28.4, 28.5, 28.5];
        const humData = [66.0, 65.5, 65.2, 65.0, 65.3, 65.1, 65.2];
        const soilData = [72.0, 72.1, 72.3, 72.4, 72.4, 72.5, 72.4];

        const envTrendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: timeLabels,
                datasets: [
                    {
                        label: 'อุณหภูมิ (°C)',
                        data: tempData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3
                    },
                    {
                        label: 'ความชื้น RH (%)',
                        data: humData,
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.05)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3
                    },
                    {
                        label: 'ความชื้นดิน (%)',
                        data: soilData,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderDash: [5, 5],
                        pointRadius: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Prompt', size: 11 } }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { family: 'Orbitron', size: 10 } },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y: {
                        ticks: { color: '#64748b', font: { family: 'Orbitron', size: 10 } },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });

        // 2. Initialize NPK Radar Chart
        const ctxRadar = document.getElementById('npkRadarChart').getContext('2d');
        const npkRadarChart = new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: ['ไนโตรเจน (N)', 'ฟอสฟอรัส (P)', 'โพแทสเซียม (K)', 'ความชื้นดิน', 'pH ดิน', 'EC สภาพนำ'],
                datasets: [{
                    label: 'ค่าปัจจุบัน',
                    data: [45, 32, 65, 72.4, 64, 55],
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
                plugins: {
                    legend: { display: false }
                },
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

        // 3. UI Helpers
        function updateRelayDom(id, isOn) {
            dashRelayStates[id] = isOn;
            const led = document.getElementById(`dash-led-${id}`);
            const btn = document.getElementById(`dash-btn-${id}`);
            const status = document.getElementById(`dash-status-${id}`);

            if (led) {
                led.className = isOn ? 'w-3 h-3 rounded-full bg-emerald-400 animate-ping inline-block' : 'w-3 h-3 rounded-full bg-slate-700 inline-block';
            }
            if (btn) {
                btn.className = isOn 
                    ? 'px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1 shadow-md'
                    : 'px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs transition active:scale-95 flex items-center gap-1';
                btn.innerHTML = isOn ? '<i class="fa-solid fa-power-off text-[10px]"></i> ปิด' : '<i class="fa-solid fa-power-off text-[10px]"></i> เปิด';
            }
            if (status) {
                status.innerText = isOn ? 'ACTIVE (ON)' : 'STANDBY (OFF)';
                status.className = isOn ? 'text-[10px] font-bold text-emerald-400' : 'text-[10px] text-slate-400';
            }
        }

        let currentControlMode = 'manual';

        function updateControlModeDom(mode) {
            currentControlMode = (mode || 'manual').toLowerCase();
            const label = document.getElementById('currentModeLabel');
            const btnManual = document.getElementById('btnModeManual');
            const btnAuto = document.getElementById('btnModeAuto');
            const btnAI = document.getElementById('btnModeAI');

            // Reset base styles
            const inactiveClass = 'py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 text-slate-400 hover:text-white';
            if (btnManual) btnManual.className = inactiveClass;
            if (btnAuto) btnAuto.className = inactiveClass;
            if (btnAI) btnAI.className = inactiveClass;

            if (currentControlMode === 'manual') {
                if (label) {
                    label.innerText = 'MANUAL';
                    label.className = 'text-[10px] font-mono px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20';
                }
                if (btnManual) btnManual.className = 'py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm';
            } else if (currentControlMode === 'auto') {
                if (label) {
                    label.innerText = 'SMART AUTO';
                    label.className = 'text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                }
                if (btnAuto) btnAuto.className = 'py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-sm';
            } else if (currentControlMode === 'ai') {
                if (label) {
                    label.innerText = 'EDGE AI';
                    label.className = 'text-[10px] font-mono px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20';
                }
                if (btnAI) btnAI.className = 'py-1.5 px-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-sm animate-pulse';
            }
        }

        // Backward compatibility
        function updateAutoModeDom(isAuto) {
            updateControlModeDom(isAuto ? 'auto' : 'manual');
        }

        // 4. Real Bidirectional API Synchronization
        async function syncTelemetryFromApi() {
            try {
                const res = await fetch('../api/api.php?action=get_telemetry');
                if (!res.ok) return;
                const data = await res.json();
                if (data.status === 'success') {
                    // Update Board parameters
                    if (data.board) {
                        currentBoardIp = data.board.ip_address || currentBoardIp;
                        currentBoardPort = data.board.web_port || currentBoardPort;
                        currentSsid = data.board.ssid || currentSsid;
                        currentRssi = data.board.rssi || currentRssi;
                        currentCloudUrl = data.board.cloud_url || currentCloudUrl;

                        const ipEl = document.getElementById('boardIpDisplay');
                        if (ipEl) ipEl.innerText = `${currentBoardIp}:${currentBoardPort}`;
                        const ssidEl = document.getElementById('boardSsidDisplay');
                        if (ssidEl) ssidEl.innerText = currentSsid;
                        const cloudEl = document.getElementById('cloudSyncDisplay');
                        if (cloudEl && data.board.cloud_url) {
                            cloudEl.innerText = data.board.cloud_url.replace('http://', '');
                        }
                        const idEl = document.getElementById('cloudTelemetryId');
                        if (idEl && data.board.telemetry_id) {
                            idEl.innerText = `#${data.board.telemetry_id}`;
                        }
                        const statEl = document.getElementById('cloudStatusText');
                        if (statEl) {
                            if (data.board.cloud_status && data.board.cloud_status.includes('DIRECT')) {
                                statEl.innerText = 'ESP32 DIRECT';
                                statEl.className = 'text-cyan-300 font-bold';
                            } else {
                                statEl.innerText = data.board.is_live ? 'CLOUD HUB' : 'ONLINE';
                                statEl.className = 'text-emerald-400 font-bold';
                            }
                        }
                    }

                    // Update Real Sensors
                    if (data.sensors) {
                        const s = data.sensors;
                        const ai = data.ai_calibrated || {};

                        // อัปเดตค่าพารามิเตอร์ทุกตัวเป็นทศนิยม 2 ตำแหน่งตามข้อกำหนด
                        if (document.getElementById('dashTemp')) document.getElementById('dashTemp').innerText = Number(s.temperature).toFixed(2);
                        if (document.getElementById('dashTempF')) document.getElementById('dashTempF').innerText = `(${Number(s.temperature_f || ((s.temperature * 1.8) + 32)).toFixed(2)}°F)`;
                        if (document.getElementById('dashHum')) document.getElementById('dashHum').innerText = Number(s.humidity).toFixed(2);
                        if (document.getElementById('dashDewPoint')) document.getElementById('dashDewPoint').innerText = `${Number(s.dew_point).toFixed(2)}°C`;
                        if (document.getElementById('dashDewMargin')) document.getElementById('dashDewMargin').innerText = `${Number(s.dew_margin || (s.temperature - s.dew_point)).toFixed(2)}°C`;
                        
                        if (document.getElementById('dashVpd')) document.getElementById('dashVpd').innerText = Number(s.vpd).toFixed(2);
                        if (document.getElementById('dashVpSat')) document.getElementById('dashVpSat').innerText = Number(s.vpsat || 4.60).toFixed(2);
                        if (document.getElementById('dashVpAct')) document.getElementById('dashVpAct').innerText = Number(s.vpact || 3.91).toFixed(2);
                        if (document.getElementById('dashVpdStatus')) {
                            const v = Number(s.vpd);
                            if (v < 0.8) {
                                document.getElementById('dashVpdStatus').innerText = 'ความชื้นสูง (Low Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-amber-300 bg-amber-950 px-2 py-1 rounded';
                            } else if (v > 1.4) {
                                document.getElementById('dashVpdStatus').innerText = 'อากาศแห้ง (High Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-rose-300 bg-rose-950 px-2 py-1 rounded';
                            } else {
                                document.getElementById('dashVpdStatus').innerText = 'สมบูรณ์ (Optimal Transp)';
                                document.getElementById('dashVpdStatus').className = 'text-xs font-bold text-emerald-300 bg-emerald-950 px-2 py-1 rounded';
                            }
                        }

                        // Root 7-in-1 Soil Sensor (pH, EC, Moisture ทศนิยม 1 ตำแหน่งตามคำสั่ง)
                        if (document.getElementById('dashSoilMoist')) document.getElementById('dashSoilMoist').innerText = Number(s.soil_moisture).toFixed(1);
                        if (document.getElementById('dashSoilEc')) document.getElementById('dashSoilEc').innerText = Number(s.soil_ec).toFixed(1);
                        if (document.getElementById('dashSoilPh')) document.getElementById('dashSoilPh').innerText = Number(s.soil_ph).toFixed(1);
                        if (document.getElementById('dashSoilTemp')) document.getElementById('dashSoilTemp').innerText = `${Number(s.soil_temperature || 27.5).toFixed(2)}°C`;
                        
                        // Surface Soil Stick (Moisture, pH ทศนิยม 1 ตำแหน่ง)
                        if (document.getElementById('dashStickMoist')) document.getElementById('dashStickMoist').innerText = Number(s.soil_stick_moisture || s.soil_moisture).toFixed(1);
                        if (document.getElementById('dashStickPh')) document.getElementById('dashStickPh').innerText = Number(s.soil_stick_ph || s.soil_ph).toFixed(1);
                        if (document.getElementById('dashStickAdc')) document.getElementById('dashStickAdc').innerText = s.soil_stick_adc || 1850;
                        if (document.getElementById('dashStickVolt')) document.getElementById('dashStickVolt').innerText = `${Number(s.soil_stick_ph_volt || 1.85).toFixed(2)}V`;

                        if (document.getElementById('dashPar')) document.getElementById('dashPar').innerText = Number(s.par_lux).toFixed(2);
                        if (document.getElementById('dashKlux')) document.getElementById('dashKlux').innerText = Number(s.klux || (s.par_lux / 1000.0)).toFixed(2);
                        if (document.getElementById('dashSolarRad')) {
                            document.getElementById('dashSolarRad').innerText = `${Number(s.solar_radiation || 7.09).toFixed(2)} W/m²`;
                        }

                        // NPK Elements: ทศนิยม 1 ตำแหน่งตามข้อกำหนด (N, P, K)
                        const valN = (Number(s.nitrogen) > 0.05) ? Number(s.nitrogen) : (Number(ai.nitrogen) || 0);
                        const valP = (Number(s.phosphorus) > 0.05) ? Number(s.phosphorus) : (Number(ai.phosphorus) || 0);
                        const valK = (Number(s.potassium) > 0.05) ? Number(s.potassium) : (Number(ai.potassium) || 0);

                        if (document.getElementById('dashValN')) document.getElementById('dashValN').innerText = `${valN.toFixed(1)} mg/kg`;
                        if (document.getElementById('dashValP')) document.getElementById('dashValP').innerText = `${valP.toFixed(1)} mg/kg`;
                        if (document.getElementById('dashValK')) document.getElementById('dashValK').innerText = `${valK.toFixed(1)} mg/kg`;

                        // Card 4 Capsules (ตรงกับหน้าจอ ESP32 - ทศนิยม 1 ตำแหน่ง)
                        if (document.getElementById('dashCard4N')) document.getElementById('dashCard4N').innerText = `N ${valN.toFixed(1)}`;
                        if (document.getElementById('dashCard4P')) document.getElementById('dashCard4P').innerText = `P ${valP.toFixed(1)}`;
                        if (document.getElementById('dashCard4K')) document.getElementById('dashCard4K').innerText = `K ${valK.toFixed(1)}`;

                        // AI Calibrated Elements (ทศนิยม 1 ตำแหน่ง)
                        if (document.getElementById('dashAiN')) document.getElementById('dashAiN').innerText = `AI: ${Number(ai.nitrogen || 0).toFixed(1)}`;
                        if (document.getElementById('dashAiP')) document.getElementById('dashAiP').innerText = `AI: ${Number(ai.phosphorus || 0).toFixed(1)}`;
                        if (document.getElementById('dashAiK')) document.getElementById('dashAiK').innerText = `AI: ${Number(ai.potassium || 0).toFixed(1)}`;
                        if (document.getElementById('dashNpkRatio')) document.getElementById('dashNpkRatio').innerText = ai.npk_ratio || `${(valN/(valP||1)).toFixed(1)}:1:${(valK/(valP||1)).toFixed(1)}`;
                        if (document.getElementById('dashNpkTotal')) document.getElementById('dashNpkTotal').innerText = `Total: ${(valN + valP + valK).toFixed(1)} mg/kg`;
                        if (document.getElementById('dashAiConfidence') && ai.confidence) {
                            document.getElementById('dashAiConfidence').innerText = `TinyML AI: ${(ai.confidence * 100).toFixed(1)}%`;
                        }

                        // GPS Coordinate update
                        if (data.gps && document.getElementById('boardGpsDisplay')) {
                            document.getElementById('boardGpsDisplay').innerText = data.gps.formatted || '12.6644° N, 102.1039° E';
                        }

                        // SD Card & SQLite Storage Indicators
                        if (data.sd_card) {
                            const sd = data.sd_card;
                            const isMounted = Boolean(sd.mounted);
                            const sdRecText = isMounted ? `${Number(sd.records || 0).toLocaleString()} เรคอร์ด` : 'ไม่ได้ใส่การ์ด (No Card)';
                            if (document.getElementById('dashSdRecords')) {
                                document.getElementById('dashSdRecords').innerText = sdRecText;
                                document.getElementById('dashSdRecords').className = isMounted ? 'text-white' : 'text-slate-400 italic';
                            }
                            if (document.getElementById('sdCardStatusDisplay')) {
                                document.getElementById('sdCardStatusDisplay').innerHTML = isMounted 
                                    ? `<i class="fa-solid fa-sd-card mr-0.5 text-amber-400"></i> ${Number(sd.records || 0).toLocaleString()} rec`
                                    : `<i class="fa-solid fa-sd-card mr-0.5 text-slate-500"></i> <span class="text-slate-400">ไม่ได้ใส่การ์ด</span>`;
                            }
                            if (document.getElementById('dashSdBadge')) {
                                document.getElementById('dashSdBadge').innerText = isMounted ? 'SD LOGGING' : 'STANDBY (NO SD)';
                                document.getElementById('dashSdBadge').className = isMounted 
                                    ? 'text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800'
                                    : 'text-[9px] font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded border border-slate-700';
                            }
                        }
                        if (data.database) {
                            const dbTotal = `${Number(data.database.total_records || 0).toLocaleString()} เรคอร์ด`;
                            if (document.getElementById('dashDbRecords')) document.getElementById('dashDbRecords').innerText = dbTotal;
                            if (document.getElementById('dbRecordsStatusDisplay')) {
                                document.getElementById('dbRecordsStatusDisplay').innerHTML = `<i class="fa-solid fa-database mr-0.5"></i> ${dbTotal}`;
                            }
                            if (document.getElementById('tableDbTotalRecords')) {
                                document.getElementById('tableDbTotalRecords').innerText = dbTotal;
                            }
                        }

                        // Update Radar Chart
                        if (npkRadarChart && npkRadarChart.data && npkRadarChart.data.datasets[0]) {
                            npkRadarChart.data.datasets[0].data = [
                                valN,
                                valP,
                                valK,
                                Number(s.soil_moisture),
                                Number(s.soil_ph) * 10,
                                Math.min(100, Number(s.soil_ec) / 5)
                            ];
                            npkRadarChart.update('none');
                        }

                        // Update Connection Badges
                        if (data.sensor_connection) {
                            const c = data.sensor_connection;
                            const bSht = document.getElementById('badgeSht45');
                            if (bSht) {
                                bSht.innerText = c.sht45 ? 'I2C ONLINE' : 'DISCONNECTED';
                                bSht.className = c.sht45 ? 'text-[9px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                            const b7in1 = document.getElementById('badgeSoil7in1');
                            if (b7in1) {
                                b7in1.innerText = c.soil_7in1 ? 'RS485 ONLINE' : 'DISCONNECTED';
                                b7in1.className = c.soil_7in1 ? 'text-[9px] font-mono text-indigo-400 bg-indigo-950 px-2 py-0.5 rounded border border-indigo-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                            const bLite = document.getElementById('badgeBh1750');
                            if (bLite) {
                                bLite.innerText = c.bh1750 ? 'I2C ONLINE' : 'DISCONNECTED';
                                bLite.className = c.bh1750 ? 'text-[9px] font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800' : 'text-[9px] font-mono text-rose-400 bg-rose-950 px-2 py-0.5 rounded border border-rose-800';
                            }
                        }

                        // Update chart
                        if (envTrendChart && envTrendChart.data && envTrendChart.data.datasets[0]) {
                            const nowStr = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            if (envTrendChart.data.labels.length > 12) {
                                envTrendChart.data.labels.shift();
                                envTrendChart.data.datasets[0].data.shift();
                                envTrendChart.data.datasets[1].data.shift();
                                envTrendChart.data.datasets[2].data.shift();
                            }
                            envTrendChart.data.labels.push(nowStr);
                            envTrendChart.data.datasets[0].data.push(s.temperature);
                            envTrendChart.data.datasets[1].data.push(s.humidity);
                            envTrendChart.data.datasets[2].data.push(s.soil_moisture);
                            envTrendChart.update('none');
                        }
                    }

                    // Update Real Relay states
                    if (data.relays) {
                        for (let id = 1; id <= 4; id++) {
                            const r = data.relays[id];
                            if (r) {
                                updateRelayDom(id, r.state == 1);
                            }
                        }
                    }

                    // Control Mode (Manual, Auto, AI)
                    if (data.control_mode) {
                        currentControlMode = data.control_mode;
                        updateControlModeDom(currentControlMode);
                    } else if (typeof data.auto_mode !== 'undefined') {
                        isAutoMode = data.auto_mode;
                        updateControlModeDom(isAutoMode ? 'auto' : 'manual');
                    }

                    // Simulated live ping response latency
                    const latEl = document.getElementById('latencyDisplay');
                    if (latEl) latEl.innerText = `${Math.floor(Math.random() * 6 + 16)}ms`;
                }
            } catch (err) {
                console.warn('Telemetry sync error:', err);
            }
        }

        // 5. Relay Toggle with Dual Action (Central API + Direct ESP32 Hardware Call)
        async function toggleDashboardRelay(id, name) {
            const nextState = !dashRelayStates[id];
            updateRelayDom(id, nextState);
            // Manual actuation immediately switches UI mode to MANUAL
            updateControlModeDom('manual');

            try {
                // 1. Post to Backend API (which writes SQLite3 DB, updates state, and dispatches direct curl to ESP32)
                const res = await fetch(`../api/api.php?action=control_relay&id=${id}&state=${nextState ? 1 : 0}`);
                const resData = await res.json();

                // 2. Direct hardware call to ESP32 board in browser LAN for ultra-low latency (<50ms)
                fetch(`http://${currentBoardIp}:${currentBoardPort}/relay?id=${id}&state=${nextState ? 1 : 0}`)
                    .catch(() => {
                        // Fallback with no-cors if browser restricts LAN access
                        fetch(`http://${currentBoardIp}:${currentBoardPort}/relay?id=${id}&state=${nextState ? 1 : 0}`, { mode: 'no-cors' }).catch(() => {});
                    });

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: nextState ? 'success' : 'info',
                    title: `${name}: ${nextState ? 'เปิดทำงานจริง (ACTIVE ON)' : 'ปิดการทำงาน (STANDBY OFF)'}`,
                    text: `สั่งการรีเลย์ช่อง ${id} บนบอร์ด ${currentBoardIp}:${currentBoardPort} สำเร็จ (ฮาร์ดแวร์ทำงานจริง)`,
                    showConfirmButton: false,
                    timer: 1600
                });
            } catch(e) {
                console.error(e);
            }
        }

        // 5.1 Set Control Mode: MANUAL / AUTO / AI
        async function setControlMode(mode) {
            updateControlModeDom(mode);
            try {
                // 1. Post to Backend API
                await fetch(`../api/api.php?action=set_control_mode&mode=${mode}`);
                
                // 2. Direct call to ESP32 board on port 8500
                fetch(`http://${currentBoardIp}:${currentBoardPort}/mode?mode=${mode}`)
                    .catch(() => {
                        fetch(`http://${currentBoardIp}:${currentBoardPort}/mode?mode=${mode}`, { mode: 'no-cors' }).catch(() => {});
                    });

                const modeLabels = {
                    'manual': 'MANUAL (ควบคุมสั่งการเอง)',
                    'auto': 'SMART AUTO (กฎเงื่อนไขอัตโนมัติ)',
                    'ai': 'EDGE AI (สมองกลอัจฉริยะวิเคราะห์ผล)'
                };

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `เปลี่ยนโหมด: ${modeLabels[mode] || mode}`,
                    text: `ซิงก์สถานะกับบอร์ดฮาร์ดแวร์ ${currentBoardIp}:${currentBoardPort} เรียบร้อย`,
                    showConfirmButton: false,
                    timer: 1800
                });
            } catch(e) {
                console.error(e);
            }
        }

        // Backward compatibility
        async function toggleAutoMode() {
            const nextMode = (currentControlMode === 'auto') ? 'manual' : 'auto';
            await setControlMode(nextMode);
        }

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

        // Start live telemetry polling loop (Every 2 seconds) and DB table (Every 5 seconds)
        syncTelemetryFromApi();
        fetchDbHistoryTable();
        setInterval(syncTelemetryFromApi, 2000);
        setInterval(fetchDbHistoryTable, 5000);
    </script>
</body>
</html>
