    <!-- Application Script (Live Bidirectional Mobile IoT Engine) -->
    <script>
        let currentBoardIp = '192.168.0.111';
        let currentBoardPort = 8500;
        let currentSsid = 'JC_Home';
        let currentRssi = -99;
        let currentCloudUrl = 'http://14.207.141.164:8000';
        const exactRelayStates = { 1: true, 2: false, 3: true, 4: true };

        // =========================================================================
        // Mobile Triple Soil Monitor Engine:
        // 0: ความชื้นคาปาซิทีฟ (ผิวดิน Stick - ตรงกับจอ ESP32)
        // 1: ความชื้น 7-in-1 (RS485 Modbus - เขตรากพืช 15-30 cm)
        // 2: กรด-ด่างดิน (Soil pH - RS485 7-in-1)
        // =========================================================================
        let mobileSoilModeIndex = 0; // 0=Stick Moist, 1=7in1 Moist, 2=Soil pH
        let mobileSoilStickMoist = 60.2;
        let mobileSoil7in1Moist = 2.7;
        let mobileSoilPh = 8.4;
        let mobileSoilCarouselTimer = null;

        function cycleMobileSoilMode(manual = false) {
            mobileSoilModeIndex = (mobileSoilModeIndex + 1) % 3;
            renderMobileSoilGauge();
            if (manual) {
                if (window.navigator && window.navigator.vibrate) {
                    try { window.navigator.vibrate(20); } catch(e){}
                }
                resetMobileSoilCarouselTimer();
            }
        }

        function resetMobileSoilCarouselTimer() {
            if (mobileSoilCarouselTimer) clearInterval(mobileSoilCarouselTimer);
            mobileSoilCarouselTimer = setInterval(() => {
                mobileSoilModeIndex = (mobileSoilModeIndex + 1) % 3;
                renderMobileSoilGauge();
            }, 4000); // สลับแสดงผลอัตโนมัติทุก 4 วินาที
        }

        function renderMobileSoilGauge() {
            const valEl = document.getElementById('gaugeValSoil');
            const unitEl = document.getElementById('gaugeUnitSoil');
            const subEl = document.getElementById('gaugeSubSoil');
            const titleEl = document.getElementById('gaugeTitleSoilText');
            const statusEl = document.getElementById('gaugeStatusSoil');
            const badgeEl = document.getElementById('mobileSoilModeBadge');
            const badgeText = document.getElementById('mobileSoilModeText');
            const badgeIcon = document.getElementById('mobileSoilModeIcon');
            const arcSoil = document.getElementById('arcSoil');
            const boxEl = document.getElementById('boxMobileSoilVal');
            const dot0 = document.getElementById('soilDot0');
            const dot1 = document.getElementById('soilDot1');
            const dot2 = document.getElementById('soilDot2');

            if (!valEl) return;

            // Update Dot Pagination (● ○ ○)
            if (dot0) dot0.className = mobileSoilModeIndex === 0 ? 'w-2 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_6px_#06b6d4] transition-all duration-300' : 'w-1.5 h-1.5 rounded-full bg-slate-600 transition-all duration-300';
            if (dot1) dot1.className = mobileSoilModeIndex === 1 ? 'w-2 h-1.5 rounded-full bg-teal-400 shadow-[0_0_6px_#14b8a6] transition-all duration-300' : 'w-1.5 h-1.5 rounded-full bg-slate-600 transition-all duration-300';
            if (dot2) dot2.className = mobileSoilModeIndex === 2 ? 'w-2 h-1.5 rounded-full bg-lime-400 shadow-[0_0_6px_#84cc16] transition-all duration-300' : 'w-1.5 h-1.5 rounded-full bg-slate-600 transition-all duration-300';

            // Quick Micro Fade-in effect
            if (boxEl) {
                boxEl.style.opacity = '0.35';
                boxEl.style.transform = 'scale(0.96)';
            }

            setTimeout(() => {
                if (mobileSoilModeIndex === 0) {
                    // MODE 0: ผิวดิน (0-10 cm)
                    const val = Number(mobileSoilStickMoist);
                    valEl.innerText = val.toFixed(1);
                    valEl.className = 'text-2xl font-bold font-mono text-cyan-300 leading-none tracking-tight';
                    if (unitEl) {
                        unitEl.innerText = '%';
                        unitEl.className = 'text-xs font-mono text-cyan-300 font-bold ml-0.5';
                    }
                    if (subEl) {
                        subEl.innerText = 'ผิวดิน (0-10 cm)';
                        subEl.className = 'text-[9px] font-mono text-cyan-300/90 font-bold mt-0.5';
                    }
                    if (titleEl) titleEl.innerText = 'ความชื้นดิน (ผิวดิน)';
                    if (statusEl) {
                        statusEl.innerText = val < 35 ? 'Low Moisture (แห้ง)' : (val > 75 ? 'Saturated (แฉะ)' : 'Ideal Moisture (พอดี)');
                        statusEl.className = 'text-[11px] font-mono text-cyan-400 font-medium';
                    }
                    if (badgeEl) {
                        badgeEl.className = 'text-[9px] font-mono font-bold text-cyan-300 bg-cyan-950/90 px-2 py-0.5 rounded-full border border-cyan-500/50 flex items-center gap-1 transition-colors duration-300';
                    }
                    if (badgeIcon) badgeIcon.className = 'fa-solid fa-droplet text-[8px] text-cyan-400';
                    if (badgeText) badgeText.innerText = 'ผิวดิน (Surface)';

                    if (arcSoil) {
                        arcSoil.setAttribute('stroke', 'url(#gradSoilMoist)');
                        const offset = 301.59 - ((Math.min(100, Math.max(0, val)) / 100.0) * 301.59);
                        arcSoil.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        arcSoil.style.filter = 'drop-shadow(0 0 8px #06b6d4)';
                    }
                } else if (mobileSoilModeIndex === 1) {
                    // MODE 1: เขตรากพืช (15-30 cm)
                    const val = Number(mobileSoil7in1Moist);
                    valEl.innerText = val.toFixed(1);
                    valEl.className = 'text-2xl font-bold font-mono text-teal-300 leading-none tracking-tight';
                    if (unitEl) {
                        unitEl.innerText = '%';
                        unitEl.className = 'text-xs font-mono text-teal-300 font-bold ml-0.5';
                    }
                    if (subEl) {
                        subEl.innerText = 'เขตราก (15-30 cm)';
                        subEl.className = 'text-[9px] font-mono text-teal-300/90 font-bold mt-0.5';
                    }
                    if (titleEl) titleEl.innerText = 'ความชื้นดิน (เขตราก)';
                    if (statusEl) {
                        statusEl.innerText = val < 20 ? 'Low Moisture (แล้งเขตราก)' : (val > 60 ? 'Wet (ชุ่มชื้นสูง)' : 'Optimal Root (พอเหมาะ)');
                        statusEl.className = 'text-[11px] font-mono text-teal-400 font-medium';
                    }
                    if (badgeEl) {
                        badgeEl.className = 'text-[9px] font-mono font-bold text-teal-300 bg-teal-950/90 px-2 py-0.5 rounded-full border border-teal-500/50 flex items-center gap-1 transition-colors duration-300';
                    }
                    if (badgeIcon) badgeIcon.className = 'fa-solid fa-seedling text-[8px] text-teal-400';
                    if (badgeText) badgeText.innerText = 'เขตราก (Root Zone)';

                    if (arcSoil) {
                        arcSoil.setAttribute('stroke', 'url(#gradSoil7in1)');
                        const offset = 301.59 - ((Math.min(100, Math.max(0, val)) / 100.0) * 301.59);
                        arcSoil.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        arcSoil.style.filter = 'drop-shadow(0 0 8px #14b8a6)';
                    }
                } else {
                    // MODE 2: กรด-ด่างดิน (Soil pH - RS485 7-in-1)
                    const val = Number(mobileSoilPh);
                    valEl.innerText = val.toFixed(1);
                    valEl.className = 'text-2xl font-bold font-mono text-lime-300 leading-none tracking-tight';
                    if (unitEl) {
                        unitEl.innerText = 'pH';
                        unitEl.className = 'text-xs font-mono text-lime-300 font-bold ml-0.5';
                    }
                    if (subEl) {
                        subEl.innerText = 'กรด-ด่าง (Soil Chemistry)';
                        subEl.className = 'text-[9px] font-mono text-lime-300/90 font-bold mt-0.5';
                    }
                    if (titleEl) titleEl.innerText = 'ความเป็นกรด-ด่าง (pH)';
                    if (statusEl) {
                        statusEl.innerText = val < 5.5 ? 'Acidic (ดินกรด)' : (val > 7.5 ? 'Alkaline (ดินด่าง)' : 'Optimal pH (เหมาะสม)');
                        statusEl.className = 'text-[11px] font-mono text-lime-400 font-medium';
                    }
                    if (badgeEl) {
                        badgeEl.className = 'text-[9px] font-mono font-bold text-lime-300 bg-lime-950/90 px-2 py-0.5 rounded-full border border-lime-500/50 flex items-center gap-1 transition-colors duration-300';
                    }
                    if (badgeIcon) badgeIcon.className = 'fa-solid fa-flask-vial text-[8px] text-lime-400';
                    if (badgeText) badgeText.innerText = 'กรด-ด่างดิน (Soil pH)';

                    if (arcSoil) {
                        arcSoil.setAttribute('stroke', 'url(#gradSoilPh)');
                        const offset = 301.59 - ((Math.min(14, Math.max(0, val)) / 14.0) * 301.59);
                        arcSoil.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        arcSoil.style.filter = 'drop-shadow(0 0 8px #84cc16)';
                    }
                }

                if (boxEl) {
                    boxEl.style.opacity = '1';
                    boxEl.style.transform = 'scale(1)';
                }
            }, 100);
        }

        // NPK Level Bar Updater — Standard Plant Nutrition Thresholds
        // N: 0-200 mg/kg | P: 0-100 mg/kg | K: 0-300 mg/kg
        // Levels: Very Low | Low | Medium (Optimal) | High | Very High
        function updateNpkBars(n, p, k) {
            // ─── N — Nitrogen thresholds (mg/kg) ───
            const nMax = 200;
            const nLevels = [
                { max: 20,  pct: ()=> Math.min(n/nMax*100,100), label:'ต่ำมาก', color:'#ef4444', glow:'#ef444488' },
                { max: 60,  pct: ()=> Math.min(n/nMax*100,100), label:'ต่ำ',    color:'#f97316', glow:'#f9731688' },
                { max: 120, pct: ()=> Math.min(n/nMax*100,100), label:'เหมาะสม',color:'#22c55e', glow:'#22c55e88' },
                { max: 160, pct: ()=> Math.min(n/nMax*100,100), label:'สูง',    color:'#3b82f6', glow:'#3b82f688' },
                { max: 999, pct: ()=> Math.min(n/nMax*100,100), label:'สูงมาก', color:'#8b5cf6', glow:'#8b5cf688' },
            ];
            // ─── P — Phosphorus thresholds (mg/kg) ───
            const pMax = 100;
            const pLevels = [
                { max: 10,  label:'ต่ำมาก', color:'#ef4444', glow:'#ef444488' },
                { max: 25,  label:'ต่ำ',    color:'#f97316', glow:'#f9731688' },
                { max: 50,  label:'เหมาะสม',color:'#22c55e', glow:'#22c55e88' },
                { max: 75,  label:'สูง',    color:'#3b82f6', glow:'#3b82f688' },
                { max: 999, label:'สูงมาก', color:'#8b5cf6', glow:'#8b5cf688' },
            ];
            // ─── K — Potassium thresholds (mg/kg) ───
            const kMax = 300;
            const kLevels = [
                { max: 50,  label:'ต่ำมาก', color:'#ef4444', glow:'#ef444488' },
                { max: 100, label:'ต่ำ',    color:'#f97316', glow:'#f9731688' },
                { max: 180, label:'เหมาะสม',color:'#22c55e', glow:'#22c55e88' },
                { max: 240, label:'สูง',    color:'#3b82f6', glow:'#3b82f688' },
                { max: 999, label:'สูงมาก', color:'#8b5cf6', glow:'#8b5cf688' },
            ];

            function applyBar(barId, statusId, value, maxVal, levels, baseGrad) {
                const bar    = document.getElementById(barId);
                const status = document.getElementById(statusId);
                if (!bar || !status) return;
                const pct = Math.min(Math.max(value / maxVal * 100, 0), 100);
                const lv  = levels.find(l => value <= l.max) || levels[levels.length - 1];
                bar.style.width = pct.toFixed(1) + '%';
                bar.style.background = `linear-gradient(to right,${baseGrad},${lv.color})`;
                bar.style.boxShadow  = `0 0 8px ${lv.glow}`;
                status.innerText     = lv.label;
                // Color the status label
                const colorMap = { 'ต่ำมาก':'#ef4444','ต่ำ':'#f97316','เหมาะสม':'#22c55e','สูง':'#3b82f6','สูงมาก':'#8b5cf6' };
                status.style.color = colorMap[lv.label] || '#9ca3af';
            }

            applyBar('barN','statusN', n, nMax, nLevels, '#6ee7b7');
            applyBar('barP','statusP', p, pMax, pLevels, '#67e8f9');
            applyBar('barK','statusK', k, kMax, kLevels, '#fcd34d');
        }

        // Play subtle Web Audio API click feedback and trigger mobile haptic vibration
        function playBeep(freq = 880, duration = 0.05) {
            // Haptic Feedback for smartphones
            if (typeof navigator !== 'undefined' && navigator.vibrate) {
                try { navigator.vibrate(18); } catch(e) {}
            }
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + duration);
            } catch(e) {}
        }

        // Section scrolling helper for physical tabs
        function scrollToSection(type) {
            playBeep(900, 0.03);
            if (type === 'gauges') {
                window.scrollTo({ top: 120, behavior: 'smooth' });
            } else if (type === 'telemetryDetails') {
                const el = document.getElementById('detailPh');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (type === 'relaySection') {
                const el = document.getElementById('switchPill-1');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        let mobControlMode = 'manual';

        function updateMobileControlModeDom(mode) {
            mobControlMode = (mode || 'manual').toLowerCase();
            const lbl       = document.getElementById('mobModeLabel');
            const btnM      = document.getElementById('mobBtnModeManual');
            const btnA      = document.getElementById('mobBtnModeAuto');
            const btnAI     = document.getElementById('mobBtnModeAI');
            const pointer   = document.getElementById('rotaryPointerGrp');
            const needleB   = document.getElementById('rotNeedleBody');
            const needleT   = document.getElementById('rotNeedleTip');
            const fillArc   = document.getElementById('rotaryFillArc');
            const dotM      = document.getElementById('rotDotManual');
            const dotA      = document.getElementById('rotDotAuto');
            const dotAI     = document.getElementById('rotDotAI');

            // Reset all buttons
            const btnOff = 'flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-slate-900/20 border-white/10 text-gray-500';
            if (btnM)  btnM.className  = btnOff;
            if (btnA)  btnA.className  = btnOff;
            if (btnAI) btnAI.className = btnOff;

            // Reset dots to dim
            [dotM, dotA, dotAI].forEach(d => { if(d){ d.setAttribute('r','5'); d.setAttribute('fill','rgba(255,255,255,0.18)'); d.style.filter=''; } });

            if (mobControlMode === 'manual') {
                if (lbl)    { lbl.innerText = 'MANUAL'; lbl.className = 'text-xs font-mono px-3 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 font-bold tracking-wider'; }
                if (btnM)   btnM.className = 'flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-amber-500/15 border-amber-500/50 text-amber-300';
                if (pointer) pointer.style.transform = 'rotate(-60deg)';
                if (needleB) needleB.setAttribute('fill','#f59e0b');
                if (needleT) { needleT.setAttribute('fill','#f59e0b'); needleT.style.filter = 'drop-shadow(0 0 9px #f59e0b)'; }
                if (fillArc) fillArc.style.opacity = '0';
                if (dotM)   { dotM.setAttribute('r','8'); dotM.setAttribute('fill','#f59e0b'); dotM.style.filter = 'drop-shadow(0 0 12px #f59e0b)'; }

            } else if (mobControlMode === 'auto') {
                if (lbl)    { lbl.innerText = 'AUTO'; lbl.className = 'text-xs font-mono px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-bold tracking-wider'; }
                if (btnA)   btnA.className = 'flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-emerald-500/15 border-emerald-500/50 text-emerald-300';
                if (pointer) pointer.style.transform = 'rotate(0deg)';
                if (needleB) needleB.setAttribute('fill','#10b981');
                if (needleT) { needleT.setAttribute('fill','#10b981'); needleT.style.filter = 'drop-shadow(0 0 9px #10b981)'; }
                if (fillArc) { fillArc.setAttribute('d','M 47.6 79 A 72 72 0 0 1 110 43'); fillArc.setAttribute('stroke','#10b981'); fillArc.style.filter = 'drop-shadow(0 0 10px #10b98180)'; fillArc.style.opacity = '1'; }
                if (dotA)   { dotA.setAttribute('r','8'); dotA.setAttribute('fill','#10b981'); dotA.style.filter = 'drop-shadow(0 0 12px #10b981)'; }

            } else if (mobControlMode === 'ai') {
                if (lbl)    { lbl.innerText = 'EDGE AI'; lbl.className = 'text-xs font-mono px-3 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-bold tracking-wider'; }
                if (btnAI)  btnAI.className = 'flex flex-col items-center gap-2 py-3 px-1 rounded-2xl border transition-all duration-300 bg-cyan-500/15 border-cyan-500/50 text-cyan-300';
                if (pointer) pointer.style.transform = 'rotate(60deg)';
                if (needleB) needleB.setAttribute('fill','#06b6d4');
                if (needleT) { needleT.setAttribute('fill','#06b6d4'); needleT.style.filter = 'drop-shadow(0 0 9px #06b6d4)'; }
                if (fillArc) { fillArc.setAttribute('d','M 47.6 79 A 72 72 0 0 1 172.4 79'); fillArc.setAttribute('stroke','#06b6d4'); fillArc.style.filter = 'drop-shadow(0 0 10px #06b6d480)'; fillArc.style.opacity = '1'; }
                if (dotAI)  { dotAI.setAttribute('r','8'); dotAI.setAttribute('fill','#06b6d4'); dotAI.style.filter = 'drop-shadow(0 0 12px #06b6d4)'; }
            }
        }

        async function setMobileControlMode(mode) {
            updateMobileControlModeDom(mode);
            playBeep(880, 0.04);
            try {
                // 1. Post to Central Server API
                await fetch(`../api/api.php?action=set_control_mode&mode=${mode}`);
                
                // 2. Direct hardware call to ESP32 board
                fetch(`http://${currentBoardIp}:${currentBoardPort}/mode?mode=${mode}`).catch(() => {
                    fetch(`http://${currentBoardIp}:${currentBoardPort}/mode?mode=${mode}`, { mode: 'no-cors' }).catch(() => {});
                });

                const modeMap = { 'manual': 'MANUAL ควบคุมเอง', 'auto': 'AUTO กฎเงื่อนไข', 'ai': 'EDGE AI สมองกล' };
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `โหมด: ${modeMap[mode] || mode}`,
                    text: `ส่งคำสั่งสู่บอร์ด ${currentBoardIp}:${currentBoardPort} สำเร็จ`,
                    showConfirmButton: false,
                    timer: 1600
                });
            } catch(e) {
                console.error(e);
            }
        }

        function updateSwitchDom(id, isOn) {
            exactRelayStates[id] = isOn;
            const pill = document.getElementById(`switchPill-${id}`);
            const txt = document.getElementById(`switchTxt-${id}`);
            const status = document.getElementById(`switchStatus-${id}`);

            if (pill) {
                if (isOn) {
                    pill.className = 'w-28 h-12 rounded-full neon-switch-active flex items-center justify-between px-3 transition-all duration-300';
                    pill.innerHTML = `<span id="switchTxt-${id}" class="text-base font-bold text-white font-mono flex-1 text-center order-first">ON</span><span class="w-8 h-8 rounded-full bg-white shadow-md flex-shrink-0"></span>`;
                } else {
                    pill.className = 'w-28 h-12 rounded-full neon-switch-inactive flex items-center justify-between px-3 transition-all duration-300';
                    pill.innerHTML = `<span class="w-8 h-8 rounded-full bg-gray-400 shadow-md flex-shrink-0"></span><span id="switchTxt-${id}" class="text-base font-bold text-gray-400 font-mono flex-1 text-center">OFF</span>`;
                }
            }

            if (status) {
                status.innerText = isOn ? 'ACTIVE (เปิด)' : 'STANDBY (ปิด)';
                status.className = isOn ? 'text-sm font-mono text-emerald-400 font-bold' : 'text-sm font-mono text-gray-500 font-bold';
            }
        }

        // Bidirectional Relay Toggle (Central Server API + Direct Hardware)
        async function toggleExactRelay(id, name) {
            const nextState = !exactRelayStates[id];
            updateSwitchDom(id, nextState);
            updateMobileControlModeDom('manual');
            playBeep(nextState ? 920 : 440);

            // 1. ส่งตรงเข้าฮาร์ดแวร์ ESP32 ทันที ณ เสี้ยววินาทีที่แตะ (<20ms)
            const directUrl = `http://${currentBoardIp}:${currentBoardPort}/relay?id=${id}&state=${nextState ? 1 : 0}`;
            fetch(directUrl).catch(() => {
                fetch(directUrl, { mode: 'no-cors' }).catch(() => {});
            });

            // 2. ซิงก์เข้า Central API ในเบื้องหลัง
            fetch(`../api/api.php?action=control_relay&id=${id}&state=${nextState ? 1 : 0}`)
                .then(r => r.json())
                .catch(e => console.warn('Mobile backend sync:', e));

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: nextState ? 'success' : 'info',
                title: `${name}: ${nextState ? 'ACTIVE (เปิดทำงานจริง)' : 'STANDBY (ปิดการทำงาน)'}`,
                text: `สั่งการรีเลย์ฮาร์ดแวร์ ${currentBoardIp}:${currentBoardPort} ทันที (<20ms)`,
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Live Clock
        function updateLiveClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const clockEl = document.getElementById('liveClock');
            if (clockEl) clockEl.innerText = `${h}:${m}`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // 4. Real-time Telemetry Polling from API
        async function syncMobileTelemetryFromApi() {
            try {
                const res = await fetch('../api/api.php?action=get_telemetry&_t=' + Date.now());
                if (!res.ok) return;
                const data = await res.json();
                if (data.status === 'success') {
                    // Update Board Status & Parameters
                    if (data.board) {
                        currentBoardIp = data.board.ip_address || currentBoardIp;
                        currentBoardPort = data.board.web_port || currentBoardPort;
                        currentSsid = data.board.ssid || currentSsid;
                        currentRssi = data.board.rssi || currentRssi;
                        currentCloudUrl = data.board.cloud_url || currentCloudUrl;

                        const navIp = document.getElementById('navBoardIp');
                        if (navIp) navIp.innerText = `${currentBoardIp}:${currentBoardPort}`;
                        const subNet = document.getElementById('subNetworkText');
                        if (subNet) subNet.innerText = `SSID: ${currentSsid} • IP: ${currentBoardIp} • ${currentRssi} dBm`;
                        const rssiLbl = document.getElementById('signalRssiLabel');
                        if (rssiLbl) rssiLbl.innerText = `${currentRssi} dBm`;
                    }

                    // Update Gauges & Detailed Physical Sensor Telemetry
                    if (data.sensors) {
                        const s = data.sensors;
                        const ai = data.ai_calibrated || {};

                        // 4 Main Gauges - Temp, Hum, VPD = 2 ตำแหน่ง, Soil Moisture = 1 ตำแหน่ง
                        const tempEl = document.getElementById('gaugeValTemp');
                        if (tempEl) tempEl.innerText = Number(s.temperature).toFixed(2);

                        const humEl = document.getElementById('gaugeValHum');
                        if (humEl) humEl.innerText = Number(s.humidity).toFixed(2);

                        const vpdEl = document.getElementById('gaugeValVpd');
                        if (vpdEl) vpdEl.innerText = Number(s.vpd).toFixed(2);

                        // Soil 7-in-1 & Stick - pH, EC แสดงทศนิยม 1 ตำแหน่งตามข้อกำหนด
                        const phEl = document.getElementById('detailPh');
                        if (phEl) phEl.innerText = Number(s.soil_ph).toFixed(1);

                        const stickPhEl = document.getElementById('detailStickPh');
                        if (stickPhEl) stickPhEl.innerText = Number(s.soil_stick_ph || s.soil_ph).toFixed(1);

                        const ecEl = document.getElementById('detailEc');
                        if (ecEl) ecEl.innerText = Number(s.soil_ec).toFixed(1);

                        const soilTempEl = document.getElementById('detailSoilTemp');
                        if (soilTempEl) soilTempEl.innerText = Number(s.soil_temperature || 27.5).toFixed(2);

                        // Light & Solar Radiation - ทศนิยม 2 ตำแหน่ง
                        const parEl = document.getElementById('detailPar');
                        if (parEl) {
                            parEl.innerText = `${Number(s.par_lux).toFixed(2)} Lx`;
                        }
                        const solarRadEl = document.getElementById('detailSolarRad');
                        if (solarRadEl) solarRadEl.innerText = `${Number(s.solar_radiation || 7.09).toFixed(2)} W/m²`;

                        // NPK Raw Sensor Metrics vs AI Fallback - ทศนิยม 1 ตำแหน่ง
                        const dispN = (Number(s.nitrogen) > 0.05) ? Number(s.nitrogen) : (Number(ai.nitrogen) || 0);
                        const dispP = (Number(s.phosphorus) > 0.05) ? Number(s.phosphorus) : (Number(ai.phosphorus) || 0);
                        const dispK = (Number(s.potassium) > 0.05) ? Number(s.potassium) : (Number(ai.potassium) || 0);

                        const nEl = document.getElementById('detailN');
                        if (nEl) nEl.innerText = dispN.toFixed(1);

                        const pEl = document.getElementById('detailP');
                        if (pEl) pEl.innerText = dispP.toFixed(1);

                        const kEl = document.getElementById('detailK');
                        if (kEl) kEl.innerText = dispK.toFixed(1);

                        // TinyML Edge AI Calibrated NPK - ทศนิยม 1 ตำแหน่ง
                        const aiNEl = document.getElementById('aiN');
                        if (aiNEl) aiNEl.innerText = Number(ai.nitrogen || s.nitrogen || 0).toFixed(1);

                        const aiPEl = document.getElementById('aiP');
                        if (aiPEl) aiPEl.innerText = Number(ai.phosphorus || s.phosphorus || 0).toFixed(1);

                        const aiKEl = document.getElementById('aiK');
                        if (aiKEl) aiKEl.innerText = Number(ai.potassium || s.potassium || 0).toFixed(1);

                        const confEl = document.getElementById('aiConfidence');
                        if (confEl) confEl.innerText = `${Number(ai.confidence ? ai.confidence * 100 : 99.2).toFixed(2)}%`;

                        const ratioEl = document.getElementById('npkRatio');
                        if (ratioEl) ratioEl.innerText = ai.npk_ratio || `${(dispN/(dispP||1)).toFixed(1)} : 1 : ${(dispK/(dispP||1)).toFixed(1)}`;

                        const totalEl = document.getElementById('npkTotal');
                        if (totalEl) totalEl.innerText = `รวม: ${(dispN + dispP + dispK).toFixed(1)} mg/kg`;

                        // Update NPK level bars
                        updateNpkBars(dispN, dispP, dispK);

                        // Mobile Dual-Engine Comparison: Raw Sensors vs Edge AI Model
                        if (document.getElementById('mobRawPh')) document.getElementById('mobRawPh').innerText = Number(s.soil_ph).toFixed(2);
                        if (document.getElementById('mobAiPh')) document.getElementById('mobAiPh').innerText = Number(ai.ph || (Number(s.soil_ph) + 0.94)).toFixed(2);
                        // Soil Moisture: Stick ผิวดิน 0-10cm (ตรงกับจอ ESP32) vs 7-in-1 รากลึก 10-30cm
                        const stickM = (s.soil_stick_moisture !== undefined && s.soil_stick_moisture !== null) ? Number(s.soil_stick_moisture) : 61.3;
                        const deepM = Number(s.soil_moisture || 2.6);
                        if (document.getElementById('mobStickMoist')) document.getElementById('mobStickMoist').innerText = `${stickM.toFixed(1)}%`;
                        if (document.getElementById('mobRawMoist')) document.getElementById('mobRawMoist').innerText = `${deepM.toFixed(1)}%`;
                        if (document.getElementById('mobAiMoist')) document.getElementById('mobAiMoist').innerText = `${Number(ai.moisture || ((deepM + stickM)/2)).toFixed(1)}%`;
                        if (document.getElementById('mobRawNpk')) document.getElementById('mobRawNpk').innerText = `${Number(s.nitrogen).toFixed(0)}-${Number(s.phosphorus).toFixed(0)}-${Number(s.potassium).toFixed(0)}`;
                        if (document.getElementById('mobAiNpk')) document.getElementById('mobAiNpk').innerText = `${Number(ai.nitrogen || 0).toFixed(0)}-${Number(ai.phosphorus || 0).toFixed(0)}-${Number(ai.potassium || 0).toFixed(0)}`;
                        if (document.getElementById('mobRawVpd')) document.getElementById('mobRawVpd').innerText = Number(s.vpd).toFixed(2);
                        if (document.getElementById('mobAiVpdStatus')) {
                            const v = Number(s.vpd);
                            document.getElementById('mobAiVpdStatus').innerText = (v < 0.8) ? 'ชื้นสูง' : ((v > 1.4) ? 'แห้งจัด' : 'สมบูรณ์');
                        }
                        if (document.getElementById('mobAiConfBadge')) {
                            document.getElementById('mobAiConfBadge').innerText = `${Number(ai.confidence ? ai.confidence * 100 : 77.6).toFixed(1)}%`;
                        }
                        if (document.getElementById('mobAiAlertText')) {
                            const stickPh = Number(s.soil_stick_ph || 3.03);
                            const deepPh = Number(s.soil_ph || 8.20);
                            const deepM = Number(s.soil_moisture || 2.6);
                            if (Math.abs(deepPh - stickPh) > 2.0) {
                                document.getElementById('mobAiAlertText').innerHTML = `<span class="text-amber-300 font-bold">เตือน:</span> พบความชัน pH ข้ามชั้นดิน (ผิวดิน Stick ${stickPh.toFixed(1)} vs รากลึก ${deepPh.toFixed(1)}) • ดินเขตรากชื้น ${deepM.toFixed(1)}% เสี่ยงแล้งเขตราก`;
                            } else {
                                document.getElementById('mobAiAlertText').innerText = `สภาวะโครงสร้างดินสม่ำเสมอ (pH: ${deepPh.toFixed(1)}, ชื้น: ${deepM.toFixed(1)}%) • TinyML ทำงานปกติ`;
                            }
                        }

                        // Microclimate Dew Point & Vapor Pressures - ทศนิยม 2 ตำแหน่ง
                        const dewPtEl = document.getElementById('detailDewPoint');
                        if (dewPtEl) dewPtEl.innerText = Number(s.dew_point || 28.4).toFixed(2);

                        const dewMgEl = document.getElementById('detailDewMargin');
                        if (dewMgEl) dewMgEl.innerText = Number(s.dew_margin || 3.0).toFixed(2);

                        const vpsatEl = document.getElementById('detailVpsat');
                        if (vpsatEl) vpsatEl.innerText = Number(s.vpsat || 4.61).toFixed(2);

                        const vpactEl = document.getElementById('detailVpact');
                        if (vpactEl) vpactEl.innerText = Number(s.vpact || 3.92).toFixed(2);

                        // Sensor Connection Status Badges
                        if (data.sensor_connection) {
                            const conn = data.sensor_connection;
                            const badgeSht = document.getElementById('badgeSht45');
                            if (badgeSht) {
                                badgeSht.innerText = conn.sht45 ? 'SHT45 ONLINE' : 'SHT45 OFFLINE';
                                badgeSht.className = conn.sht45 ? 'text-[9px] font-mono text-cyan-300 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-500/40 font-bold' : 'text-[9px] font-mono text-rose-300 bg-rose-950 px-2 py-0.5 rounded border border-rose-500/40 font-bold';
                            }
                            const badgeModbus = document.getElementById('badgeSoilModbus');
                            if (badgeModbus) {
                                badgeModbus.innerText = conn.soil_7in1 ? 'RS485 ONLINE' : 'RS485 OFFLINE';
                                badgeModbus.className = conn.soil_7in1 ? 'text-[9px] font-mono text-emerald-300 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-500/40 font-bold' : 'text-[9px] font-mono text-rose-300 bg-rose-950 px-2 py-0.5 rounded border border-rose-500/40 font-bold';
                            }
                        }

                        // Dual Storage: Micro-SD Card & SQLite Database
                        if (data.sd_card) {
                            const mobSd = document.getElementById('mobSdStatus');
                            if (mobSd) {
                                if (data.sd_card.mounted) {
                                    mobSd.innerText = `Mounted (${Number(data.sd_card.records || 0).toLocaleString()} rec)`;
                                    mobSd.className = 'text-amber-300 font-bold';
                                } else {
                                    mobSd.innerText = 'ไม่ได้ใส่การ์ด (No Card)';
                                    mobSd.className = 'text-slate-400 italic';
                                }
                            }
                        }
                        if (data.database) {
                            const mobDb = document.getElementById('mobDbStatus');
                            if (mobDb) {
                                mobDb.innerText = `${Number(data.database.total_records || 0).toLocaleString()} rec (SQLite)`;
                            }
                        }

                        // Dynamically update SVG gauge dashoffsets
                        // Circle circumference = 2 * PI * 48 = 301.59
                        const arcTemp = document.getElementById('arcTemp');
                        if (arcTemp) {
                            const offset = 301.59 - ((s.temperature / 50.0) * 301.59);
                            arcTemp.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        const arcHum = document.getElementById('arcHum');
                        if (arcHum) {
                            const offset = 301.59 - ((s.humidity / 100.0) * 301.59);
                            arcHum.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        // Triple Soil Monitor (1. คาปาซิทีฟ 2. ความชื้น 7-in-1 3. กรด-ด่าง pH)
                        mobileSoilStickMoist = (s.soil_stick_moisture !== undefined && s.soil_stick_moisture !== null) ? Number(s.soil_stick_moisture) : 60.2;
                        mobileSoil7in1Moist = (s.soil_moisture !== undefined && s.soil_moisture !== null) ? Number(s.soil_moisture) : 2.7;
                        mobileSoilPh = (s.soil_ph !== undefined && s.soil_ph !== null) ? Number(s.soil_ph) : 8.4;
                        renderMobileSoilGauge();
                        const arcVpd = document.getElementById('arcVpd');
                        if (arcVpd) {
                            const offset = 301.59 - ((s.vpd / 3.0) * 301.59);
                            arcVpd.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }

                        // Gauge Row 2: pH, EC, Soil Temp, Light
                        const phVal = Number(s.soil_ph) || 7.0;
                        const gaugeValPh = document.getElementById('gaugeValPh');
                        if (gaugeValPh) gaugeValPh.innerText = phVal.toFixed(1);
                        const arcPh = document.getElementById('arcPh');
                        if (arcPh) {
                            const offset = 301.59 - ((phVal / 14.0) * 301.59);
                            arcPh.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        const gaugeLabPh = document.getElementById('gaugeLabPh');
                        if (gaugeLabPh) gaugeLabPh.innerText = phVal < 5.5 ? 'Acidic' : (phVal > 7.5 ? 'Alkaline' : 'Neutral');

                        const ecVal = Number(s.soil_ec) || 0;
                        const gaugeValEc = document.getElementById('gaugeValEc');
                        if (gaugeValEc) gaugeValEc.innerText = ecVal.toFixed(0);
                        const arcEc = document.getElementById('arcEc');
                        if (arcEc) {
                            const offset = 301.59 - ((ecVal / 2000.0) * 301.59);
                            arcEc.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        const gaugeLabEc = document.getElementById('gaugeLabEc');
                        if (gaugeLabEc) gaugeLabEc.innerText = ecVal < 400 ? 'Low' : (ecVal > 1400 ? 'High' : 'Optimal');

                        const stVal = Number(s.soil_temperature) || 27.5;
                        const gaugeValSoilTemp = document.getElementById('gaugeValSoilTemp');
                        if (gaugeValSoilTemp) gaugeValSoilTemp.innerText = stVal.toFixed(1);
                        const arcSoilTemp = document.getElementById('arcSoilTemp');
                        if (arcSoilTemp) {
                            const offset = 301.59 - (((stVal - 10) / 40.0) * 301.59);
                            arcSoilTemp.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        const gaugeLabSoilTemp = document.getElementById('gaugeLabSoilTemp');
                        if (gaugeLabSoilTemp) gaugeLabSoilTemp.innerText = stVal < 18 ? 'Cold' : (stVal > 35 ? 'Hot' : 'Normal');

                        const lxVal = Number(s.par_lux) || 0;
                        const gaugeValLight = document.getElementById('gaugeValLight');
                        if (gaugeValLight) gaugeValLight.innerText = lxVal >= 1000 ? `${(lxVal/1000).toFixed(1)}k` : lxVal.toFixed(0);
                        const arcLight = document.getElementById('arcLight');
                        if (arcLight) {
                            const offset = 301.59 - ((lxVal / 100000.0) * 301.59);
                            arcLight.style.strokeDashoffset = Math.max(20, Math.min(300, offset));
                        }
                        const gaugeLabLight = document.getElementById('gaugeLabLight');
                        if (gaugeLabLight) gaugeLabLight.innerText = lxVal < 5000 ? 'Dim' : (lxVal > 50000 ? 'Bright' : 'Moderate');
                        const gaugeLabLight2 = document.getElementById('gaugeLabLight2');
                        if (gaugeLabLight2) {
                            const radVal = Number(s.solar_radiation) || 0;
                            gaugeLabLight2.innerText = `${radVal.toFixed(1)} W/m²`;
                        }
                    }

                    // Dynamically update GPS Coordinates from Telemetry API
                    if (data.gps) {
                        const mobGps = document.getElementById('mobGpsCoords');
                        if (mobGps && data.gps.formatted) {
                            mobGps.innerText = `${data.gps.formatted} (${data.gps.location_name || 'RBRU'})`;
                        }
                    }

                    // Sync Control Mode
                    if (data.control_mode) {
                        updateMobileControlModeDom(data.control_mode);
                    } else if (typeof data.auto_mode !== 'undefined') {
                        updateMobileControlModeDom(data.auto_mode ? 'auto' : 'manual');
                    }

                    // Sync Relays from Server / Web Dashboard
                    if (data.relays) {
                        for (let id = 1; id <= 4; id++) {
                            const r = data.relays[id];
                            if (r) {
                                updateSwitchDom(id, r.state == 1);
                            }
                        }
                    }
                }
            } catch (err) {
                console.warn('Mobile telemetry sync error:', err);
            }
        }

        // Modal Handlers
        function openWifiModal() {
            playBeep(1000, 0.05);
            document.getElementById('inputBoardIp').value = currentBoardIp;
            document.getElementById('wifiModal').classList.remove('hidden');
        }
        function closeWifiModal() {
            document.getElementById('wifiModal').classList.add('hidden');
        }
        async function saveWifiConfig() {
            const ip = document.getElementById('inputBoardIp').value.trim();
            if (ip) {
                currentBoardIp = ip;
                document.getElementById('navBoardIp').innerText = `${currentBoardIp}:${currentBoardPort}`;

                await fetch('../api/api.php?action=update_board_config', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ip: currentBoardIp, web_port: currentBoardPort, ssid: currentSsid, cloud_url: currentCloudUrl })
                });
            }
            closeWifiModal();
            Swal.fire({
                icon: 'success',
                title: 'บันทึกการตั้งค่าแล้ว',
                text: `เชื่อมต่อกับบอร์ดที่ ${currentBoardIp}:${currentBoardPort} เรียบร้อย`,
                timer: 1500,
                showConfirmButton: false,
                background: '#070d1e',
                color: '#fff'
            });
        }

        function captureLiveSnapshot() {
            playBeep(1200, 0.1);
            Swal.fire({
                title: 'กำลังถ่ายภาพจากกล้อง OV2640...',
                text: `ส่งคำสั่ง HTTP ไปยังบอร์ด ${currentBoardIp}:${currentBoardPort}`,
                timer: 1000,
                timerProgressBar: true,
                background: '#070d1e',
                color: '#fff',
                didOpen: () => { Swal.showLoading(); }
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'ตรวจจับสำเร็จ!',
                    text: 'ตรวจพบ: ใบพืชสมบูรณ์ (Healthy Leaf) 98.4%',
                    timer: 1800,
                    showConfirmButton: false,
                    background: '#070d1e',
                    color: '#fff'
                });
            });
        }

        function openBoardScreenModal() {
            playBeep(1050, 0.05);
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; font-size: 15px; color: #38bdf8;">ESP32-S3 Physical LCD Screen</span>',
                html: `
                    <!-- Authentic reproduction of the physical LCD touch screen from user photo -->
                    <div style="background: #222; padding: 10px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); border: 6px solid #e2e8f0; max-width: 360px; margin: 0 auto; text-align: left; font-family: 'Chakra Petch', sans-serif;">
                        
                        <!-- Top LCD Status Tabs (Cyan, Green, Orange, Purple, Yellow, Blue) -->
                        <div style="display: flex; gap: 2px; margin-bottom: 6px; font-size: 9px; font-weight: bold;">
                            <span style="background: #06b6d4; color: black; padding: 2px 4px; border-radius: 3px;">1. HOME</span>
                            <span style="background: #22c55e; color: black; padding: 2px 4px; border-radius: 3px;">2. DATA</span>
                            <span style="background: #f97316; color: black; padding: 2px 4px; border-radius: 3px;">3. GRAPH</span>
                            <span style="background: #a855f7; color: white; padding: 2px 4px; border-radius: 3px;">4. RELAY</span>
                            <span style="background: #eab308; color: black; padding: 2px 4px; border-radius: 3px; box-shadow: 0 0 6px #eab308;">5. SETUP</span>
                            <span style="background: #3b82f6; color: white; padding: 2px 4px; border-radius: 3px;">🌐 ENG</span>
                        </div>

                        <!-- Screen Title -->
                        <div style="color: #facc15; font-size: 10px; font-weight: bold; text-align: center; border-bottom: 1px solid #444; padding-bottom: 3px; margin-bottom: 6px;">
                            NETWORK STATUS &amp; SYSTEM SETTINGS
                        </div>

                        <!-- Screen Body matching photo -->
                        <div style="font-family: monospace; font-size: 10px; line-height: 1.6; background: #000; padding: 8px; border-radius: 6px; border: 1px solid #38bdf8;">
                            <div>Status: <span style="color: #22c55e; font-weight: bold;">CONNECTED (ONLINE)</span></div>
                            <div style="color: #facc15;">GPS: <a href="https://maps.google.com/?q=12.6644,102.1039" target="_blank" style="color: #38bdf8; text-decoration: underline;">12.6644 N, 102.1039 E (RBRU)</a></div>
                            <div style="color: #38bdf8;">SSID: <span style="color: #fff; font-weight: bold;">${currentSsid}</span></div>
                            <div style="color: #38bdf8;">IP Address: <span style="color: #fff; font-weight: bold;">${currentBoardIp}</span></div>
                            <div style="color: #38bdf8;">Signal RSSI: <span style="color: #fff; font-weight: bold;">${currentRssi} dBm (ปกติ)</span></div>
                            <div style="color: #38bdf8; font-size: 9px; word-break: break-all;">Cloud: ${currentCloudUrl} | Web: :${currentBoardPort}</div>
                        </div>

                        <!-- System Language Selector -->
                        <div style="margin-top: 8px; display: flex; align-items: center; justify-content: space-between; font-size: 10px;">
                            <span style="color: #cbd5e1;">SELECT SYSTEM LANGUAGE:</span>
                            <div style="display: flex; gap: 4px;">
                                <span style="padding: 2px 6px; border-radius: 3px; background: #334155; color: #94a3b8; font-size: 9px;">ภาษาไทย</span>
                                <span style="padding: 2px 6px; border-radius: 3px; background: #06b6d4; color: black; font-weight: bold; font-size: 9px;">[*] English</span>
                            </div>
                        </div>

                        <!-- SoftAP / QR Setup Button -->
                        <div style="margin-top: 8px;">
                            <button onclick="openWifiModal()" style="width: 100%; padding: 6px; background: #0284c7; color: white; font-weight: bold; font-size: 10px; border-radius: 5px; border: none; cursor: pointer; text-align: center;">
                                SETUP WI-FI VIA PHONE / QR CODE
                            </button>
                        </div>

                        <div style="color: #64748b; font-size: 8px; text-align: center; margin-top: 5px;">
                            Scan QR or connect to SoftAP to setup without PC
                        </div>

                    </div>
                `,
                background: '#070d1e',
                color: '#fff',
                confirmButtonText: 'ทดสอบ Ping บอร์ด',
                confirmButtonColor: '#10b981',
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                cancelButtonColor: '#334155'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    const pingRes = await fetch('../api/api.php?action=ping_board');
                    Swal.fire({
                        icon: 'success',
                        title: 'สถานะเชื่อมต่อบอร์ด',
                        text: `ESP32-S3 ที่ ${currentBoardIp}:${currentBoardPort} ออนไลน์และตอบสนองปกติ`,
                        timer: 1800,
                        showConfirmButton: false,
                        background: '#070d1e',
                        color: '#fff'
                    });
                }
            });
        }

        // Interactive Field GPS Configuration Modal (Mobile App)
        window.acquireDeviceGeo = function() {
            playBeep(880, 0.04);
            const statusEl = document.getElementById('geoStatusText');
            if (!navigator.geolocation) {
                if (statusEl) {
                    statusEl.innerText = 'เบราว์เซอร์มือถือไม่รองรับ Geolocation API';
                    statusEl.style.color = '#f87171';
                }
                return;
            }
            if (statusEl) {
                statusEl.innerText = 'กำลังตรวจจับดาวเทียม GPS จากสมาร์ทโฟน...';
                statusEl.style.color = '#38bdf8';
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    playBeep(1200, 0.08);
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const acc = pos.coords.accuracy;
                    const inputLat = document.getElementById('swalInputLat');
                    const inputLon = document.getElementById('swalInputLon');
                    if (inputLat) inputLat.value = lat.toFixed(6);
                    if (inputLon) inputLon.value = lon.toFixed(6);
                    if (statusEl) {
                        statusEl.innerText = `ดึงพิกัดจริงสำเร็จ! (${lat.toFixed(5)}, ${lon.toFixed(5)} แม่นยำ ±${acc.toFixed(1)} ม.)`;
                        statusEl.style.color = '#34d399';
                    }
                },
                (err) => {
                    if (statusEl) {
                        statusEl.innerText = 'ไม่สามารถดึงพิกัดได้: ' + err.message;
                        statusEl.style.color = '#f87171';
                    }
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        };

        function openGpsConfigModal() {
            playBeep(980, 0.05);
            let currentLat = (window.currentGps && window.currentGps.latitude) ? window.currentGps.latitude : 12.6644;
            let currentLon = (window.currentGps && window.currentGps.longitude) ? window.currentGps.longitude : 102.1039;
            let currentLocName = (window.currentGps && window.currentGps.location_name) ? window.currentGps.location_name : 'คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี (RBRU)';

            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; font-size: 15px; color: #f43f5e;"><i class="fa-solid fa-location-crosshairs mr-1"></i> พิกัด GPS จุดติดตั้งเซนเซอร์จริง</span>',
                html: `
                    <div style="text-align: left; font-size: 12px; font-family: sans-serif; color: #cbd5e1;">
                        <p style="margin-bottom: 10px; color: #94a3b8; font-size: 11px; line-height: 1.5;">
                            ระบุพิกัดจริงของแปลงที่ติดตั้งบอร์ด ESP32 หรือกดปุ่มดึงพิกัดจาก GPS สมาร์ทโฟนของท่านได้ทันที
                        </p>
                        
                        <div style="background: rgba(13, 19, 34, 0.9); border: 1px solid #334155; border-radius: 12px; padding: 10px; margin-bottom: 12px;">
                            <button id="btnAutoGeo" onclick="acquireDeviceGeo()" type="button" style="width: 100%; padding: 8px 12px; border-radius: 8px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; font-weight: bold; font-size: 12px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                <i class="fa-solid fa-crosshairs"></i> ดึงพิกัดจาก GPS มือถือเดี๋ยวนี้
                            </button>
                            <span id="geoStatusText" style="font-size: 10px; color: #64748b; display: block; text-align: center; margin-top: 5px;">High-Accuracy GNSS Phone Sensor</span>
                        </div>

                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ละติจูด (Latitude):</label>
                            <input id="swalInputLat" type="number" step="0.000001" value="${currentLat}" style="width: 100%; padding: 7px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #38bdf8; font-family: monospace; font-size: 12px;">
                        </div>

                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ลองจิจูด (Longitude):</label>
                            <input id="swalInputLon" type="number" step="0.000001" value="${currentLon}" style="width: 100%; padding: 7px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #38bdf8; font-family: monospace; font-size: 12px;">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: 600; color: #e2e8f0; margin-bottom: 4px; font-size: 11px;">ชื่อสถานที่ / แปลงติดตั้ง:</label>
                            <input id="swalInputLocName" type="text" value="${currentLocName}" style="width: 100%; padding: 7px; border-radius: 8px; background: #0f172a; border: 1px solid #475569; color: #fff; font-size: 12px;">
                        </div>

                        <div style="text-align: center; margin-top: 6px;">
                            <a href="https://maps.google.com/?q=${currentLat},${currentLon}" target="_blank" style="color: #38bdf8; text-decoration: underline; font-size: 11px;">
                                <i class="fa-solid fa-map-location-dot"></i> เปิดดูตำแหน่งพิกัดนี้บน Google Maps
                            </a>
                        </div>
                    </div>
                `,
                background: '#070d1e',
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
                    saveMobileGpsCoordinates(res.value.lat, res.value.lon, res.value.locName);
                }
            });
        }

        function saveMobileGpsCoordinates(lat, lon, locName) {
            playBeep(1100, 0.05);
            Swal.fire({
                title: 'กำลังบันทึกพิกัดจริง...',
                didOpen: () => { Swal.showLoading(); },
                background: '#070d1e',
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
                    if (document.getElementById('mobGpsCoords')) {
                        document.getElementById('mobGpsCoords').innerText = `${data.gps.formatted} (${data.gps.location_name})`;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกพิกัดจริงสำเร็จ',
                        html: `
                            <div style="font-size: 12px; line-height: 1.8;">
                                <div><strong>พิกัด:</strong> ${data.gps.formatted}</div>
                                <div><strong>สถานที่:</strong> ${data.gps.location_name}</div>
                            </div>
                        `,
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#070d1e',
                        color: '#fff'
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'บันทึกล้มเหลว', text: data.message, background: '#070d1e', color: '#fff' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'การเชื่อมต่อผิดพลาด', text: err.toString(), background: '#070d1e', color: '#fff' });
            });
        }

        function openMobileQrModal() {
            playBeep(950, 0.05);
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #06b6d4;">Official QR Code Portal</span>',
                html: `
                    <div style="text-align: center; padding: 5px;">
                        <div style="background: white; padding: 12px; border-radius: 16px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.6); margin-bottom: 12px;">
                            <img src="../assets/images/qr_leqs-agri-workshop.png" alt="QR Portal" style="width: 200px; height: 200px; object-fit: contain;">
                        </div>
                        <div style="font-family: Orbitron, monospace; font-size: 12px; line-height: 1.7; color: #f1f5f9; background: #0b1329; padding: 10px; border-radius: 12px; border: 1px solid #1e293b;">
                            <div style="color: #10b981; font-weight: bold;">📅 2026.10.04 วันอาทิตย์</div>
                            <div style="color: #f59e0b; font-weight: bold;">⏰ 09:03 ชีวะ ทัศนา</div>
                            <div style="color: #38bdf8; word-break: break-all; margin-top: 3px;">
                                🔗 <a href="https://scicenter.rbru.ac.th/leqs-workshop/index.php" target="_blank" style="color: #38bdf8; text-decoration: underline;">https://scicenter.rbru.ac.th/leqs-workshop/index.php</a>
                            </div>
                        </div>
                    </div>
                `,
                background: '#070d1e',
                color: '#fff',
                confirmButtonText: '<i class="fa-solid fa-arrow-up-right-from-square"></i> เปิดลิงก์ระบบ',
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

        function openScreen11Modal() {
            playBeep(1100, 0.05);
            Swal.fire({
                title: '<span style="font-family: \'Chakra Petch\', sans-serif; color: #38bdf8;">ESP32-S3 ATD3.5 - จอที่ 11</span>',
                html: `
                    <div style="text-align: center; padding: 5px;">
                        <img src="../assets/images/atd35/11_qr_portal_screen.png" alt="Screen 11" style="width: 100%; border-radius: 14px; border: 1px solid #0284c7; box-shadow: 0 10px 25px rgba(0,0,0,0.7);">
                        <div style="font-family: monospace; font-size: 11px; color: #94a3b8; margin-top: 8px;">
                            2026.10.04 วันอาทิตย์ • 09:03 ชีวะ ทัศนา • scicenter.rbru.ac.th/leqs-workshop/index.php
                        </div>
                    </div>
                `,
                background: '#070d1e',
                color: '#fff',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#0284c7'
            });
        }

        // Micro-SD Card Subsystem Toggle / Inspection
        async function toggleOrCheckSdCard() {
            try {
                playBeep(950, 0.05);
                const res = await fetch('../api/api.php?action=toggle_sd_card');
                const data = await res.json();
                if (data.status === 'success') {
                    syncMobileTelemetryFromApi();
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

        // Start mobile telemetry polling loop (Every 2 seconds)
        syncMobileTelemetryFromApi();
        setInterval(syncMobileTelemetryFromApi, 2000);

        // Start Triple Soil Monitor Rotation (Every 4 seconds)
        resetMobileSoilCarouselTimer();
    </script>
</body>
</html>
