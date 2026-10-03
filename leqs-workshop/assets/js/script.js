/**
 * LEQs-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
 * Main Interactive Portal Script & Live Simulation Engines
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */

// Initialize Tailwind Theme Extension
if (typeof tailwind !== 'undefined') {
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Sarabun', 'sans-serif'],
                    heading: ['Outfit', 'Kanit', 'sans-serif'],
                    tech: ['Orbitron', 'Chakra Petch', 'monospace'],
                },
                colors: {
                    brand: {
                        emerald: '#10B981',
                        cyan: '#06B6D4',
                        sky: '#0284C7',
                        amber: '#F59E0B',
                        purple: '#8B5CF6',
                        rose: '#F43F5E',
                        dark: '#0B1120',
                        surface: '#0F172A'
                    }
                }
            }
        }
    };
}

// Copy to Clipboard with Toast Notification
function copyToClipboard(elementId) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const text = el.innerText || el.textContent;
    navigator.clipboard.writeText(text).then(function() {
        showToast('คัดลอกโค้ดสำเร็จแล้ว! (Copied to Clipboard)', 'success');
    }).catch(function(err) {
        console.error('Async clipboard error:', err);
    });
}

// Global Toast Notification
function showToast(message, type = 'success') {
    const existing = document.getElementById('global-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'global-toast';
    const isSuccess = type === 'success';
    toast.className = `fixed bottom-8 left-1/2 transform -translate-x-1/2 ${
        isSuccess ? 'bg-slate-900/95 border-emerald-500/50' : 'bg-rose-900/95 border-rose-500/50'
    } text-white px-6 py-3.5 rounded-full shadow-2xl flex items-center gap-3 z-50 animate-bounce border backdrop-blur-md transition-all duration-300`;
    
    toast.innerHTML = `
        <i class="fa-solid ${isSuccess ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-rose-400'} text-lg"></i>
        <span class="font-medium text-sm font-sans">${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 2400);
}

// ==========================================
// SIMULATOR 1: VPD & Disease Risk Engine
// ==========================================
function updateVPDSimulator() {
    const tempInput = document.getElementById('vpd-temp-slider');
    const rhInput = document.getElementById('vpd-rh-slider');
    if (!tempInput || !rhInput) return;

    const T = parseFloat(tempInput.value);
    const RH = parseFloat(rhInput.value);

    // Update Slider Display Labels
    const tempVal = document.getElementById('vpd-temp-val');
    const rhVal = document.getElementById('vpd-rh-val');
    if (tempVal) tempVal.innerText = `${T.toFixed(1)} °C`;
    if (rhVal) rhVal.innerText = `${RH.toFixed(0)} %`;

    // Saturated Vapor Pressure: VPsat (kPa) using Tetens equation
    const VPsat = 0.61078 * Math.exp((17.27 * T) / (T + 237.3));
    // Actual Vapor Pressure: VPact (kPa)
    const VPact = VPsat * (RH / 100.0);
    // Vapor Pressure Deficit: VPD (kPa)
    const VPD = VPsat - VPact;

    // Dew Point Calculation (°C)
    const a = 17.27;
    const b = 237.3;
    const alpha = ((a * T) / (b + T)) + Math.log(RH / 100.0);
    const Tdew = (b * alpha) / (a - alpha);

    // Update Numerical Outputs
    const vpdDisplay = document.getElementById('vpd-kpa-display');
    const dewDisplay = document.getElementById('vpd-dew-display');
    const statusBadge = document.getElementById('vpd-status-badge');
    const recText = document.getElementById('vpd-recommendation-text');
    const vpdGauge = document.getElementById('vpd-gauge-fill');

    if (vpdDisplay) vpdDisplay.innerText = VPD.toFixed(2);
    if (dewDisplay) dewDisplay.innerText = `${Tdew.toFixed(1)} °C`;

    // Gauge width (0.0 to 2.5 kPa map to 0 - 100%)
    if (vpdGauge) {
        const pct = Math.min(Math.max((VPD / 2.2) * 100, 5), 100);
        vpdGauge.style.width = `${pct}%`;
    }

    // Determine Agronomic State & Explainable AI Interpretation
    if (VPD < 0.40) {
        if (statusBadge) {
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/30';
            statusBadge.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i> ความเสี่ยงโรคราสูงมาก (Danger: High Fungal Risk)';
        }
        if (vpdGauge) vpdGauge.className = 'h-full rounded-full transition-all duration-300 bg-gradient-to-r from-blue-400 to-rose-500';
        if (recText) recText.innerHTML = '<b class="text-rose-400">คำอธิบาย xAI:</b> อากาศชื้นจัด พืชไม่สามารถคายน้ำได้ ปากใบปิด เสี่ยงต่อโรคราสนิม ราน้ำค้าง และผลแตก <span class="text-emerald-400">แนะนำ:</span> เปิดพัดลมระบายอากาศ หยุดการให้น้ำ พ่นสารชีวภัณฑ์ดักจับสปอร์รา';
    } else if (VPD >= 0.40 && VPD <= 0.80) {
        if (statusBadge) {
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-sky-500/20 text-sky-400 border border-sky-500/30';
            statusBadge.innerHTML = '<i class="fa-solid fa-seedling mr-1"></i> ระยะการเจริญเติบโตต้นกล้า (Early Vegetative)';
        }
        if (vpdGauge) vpdGauge.className = 'h-full rounded-full transition-all duration-300 bg-gradient-to-r from-blue-400 to-cyan-400';
        if (recText) recText.innerHTML = '<b class="text-sky-400">คำอธิบาย xAI:</b> อากาศชุ่มชื้น เหมาะสมอย่างยิ่งสำหรับต้นกล้าเพาะชำ และการแตกยอดอ่อนของทุเรียน ไม่พบความเครียดจากความร้อน';
    } else if (VPD > 0.80 && VPD <= 1.25) {
        if (statusBadge) {
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
            statusBadge.innerHTML = '<i class="fa-solid fa-check-circle mr-1"></i> โซนสังเคราะห์แสงสมบูรณ์ (Optimal Transpiration)';
        }
        if (vpdGauge) vpdGauge.className = 'h-full rounded-full transition-all duration-300 bg-gradient-to-r from-cyan-400 to-emerald-500';
        if (recText) recText.innerHTML = '<b class="text-emerald-400">คำอธิบาย xAI:</b> ปากใบพืชเปิดกว้าง อัตราการดูดซึมธาตุอาหาร N-P-K และแคลเซียมสูงสุด สมดุลการคายน้ำเหมาะสมที่สุดสำหรับการสร้างเนื้อและขยายผลผลิต';
    } else if (VPD > 1.25 && VPD <= 1.60) {
        if (statusBadge) {
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/30';
            statusBadge.innerHTML = '<i class="fa-solid fa-sun mr-1"></i> เริ่มมีความเครียดน้ำ (Moderate Water Stress)';
        }
        if (vpdGauge) vpdGauge.className = 'h-full rounded-full transition-all duration-300 bg-gradient-to-r from-emerald-500 to-amber-500';
        if (recText) recText.innerHTML = '<b class="text-amber-400">คำอธิบาย xAI:</b> อากาศแห้งและร้อน พืชเริ่มคายน้ำเร็วกว่าการดูดน้ำจากราก <span class="text-cyan-400">ระบบสั่งการ:</span> เตรียมเปิดสปริงเกลอร์พ่นหมอกใต้ทรงพุ่มเพื่อลดอุณหภูมิอากาศ';
    } else {
        if (statusBadge) {
            statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-600/30 text-rose-300 border border-rose-500';
            statusBadge.innerHTML = '<i class="fa-solid fa-fire mr-1"></i> ภาวะวิกฤตแห้งแล้งรุนแรง (Severe Stress / Wilting)';
        }
        if (vpdGauge) vpdGauge.className = 'h-full rounded-full transition-all duration-300 bg-gradient-to-r from-amber-500 to-rose-600';
        if (recText) recText.innerHTML = '<b class="text-rose-400">คำอธิบาย xAI:</b> ปากใบพืชปิดสนิทเพื่อรักษาชีวิต การสังเคราะห์แสงหยุดชะงัก ดอกและผลอ่อนร่วง <span class="text-rose-400 font-bold">เตือนด่วน:</span> สั่งระบบรดน้ำอัตโนมัติทำงานทันที!';
    }
}

// ==========================================
// SIMULATOR 2: Dual-Depth Soil Irrigation Engine
// ==========================================
function updateSoilSimulator() {
    const s1Input = document.getElementById('soil-surf-slider');
    const s2Input = document.getElementById('soil-deep-slider');
    if (!s1Input || !s2Input) return;

    const S1 = parseFloat(s1Input.value); // Surface 0-10cm (%)
    const S2 = parseFloat(s2Input.value); // Deep 10-30cm (%)

    const s1Val = document.getElementById('soil-surf-val');
    const s2Val = document.getElementById('soil-deep-val');
    if (s1Val) s1Val.innerText = `${S1.toFixed(0)} %`;
    if (s2Val) s2Val.innerText = `${S2.toFixed(0)} %`;

    // Visual indicators
    const surfBar = document.getElementById('soil-surf-bar');
    const deepBar = document.getElementById('soil-deep-bar');
    if (surfBar) surfBar.style.width = `${S1}%`;
    if (deepBar) deepBar.style.width = `${S2}%`;

    const pumpStatus = document.getElementById('pump-relay-indicator');
    const soilDecisionText = document.getElementById('soil-decision-text');

    // Dual-Depth Irrigation Intelligent Decision Logic
    let isPumpOn = false;
    let explanation = '';

    if (S2 < 30.0) {
        // Critical deep moisture shortage at root zone
        isPumpOn = true;
        explanation = '<span class="text-rose-400 font-bold">สั่งเปิดปั๊มน้ำ (Relay 1: ON):</span> ความชื้นเขตรากดูดซึม (10-30 cm) ต่ำกว่า 30% พืชขาดน้ำรุนแรง ระบบรดน้ำหลักทำงานต่อเนื่อง 15 นาที';
    } else if (S1 < 25.0 && S2 >= 30.0 && S2 <= 55.0) {
        // Surface dry, root zone moderate
        isPumpOn = true;
        explanation = '<span class="text-amber-400 font-bold">สั่งเปิดสปริงเกลอร์เบา (Relay 2: ON):</span> หน้าดินแห้งแตกผาก แต่เขตรากยังพอมีความชื้น ระบบพ่นละอองผิวดิน 5 นาทีเพื่อรักษาโครงสร้างหน้าดินและจุลินทรีย์';
    } else if (S2 > 65.0) {
        // Oversaturated / Waterlogged
        isPumpOn = false;
        explanation = '<span class="text-sky-400 font-bold">สั่งปิดปั๊มน้ำ (Relay OFF):</span> ดินชั้นล่างมีความชื้นสูงเกิน 65% เสี่ยงต่อภาวะรากเน่าโคนเน่า (Phytophthora) สั่งระบายน้ำและงดให้น้ำเด็ดขาด';
    } else {
        // Optimal moisture balance
        isPumpOn = false;
        explanation = '<span class="text-emerald-400 font-bold">สถานะสมบูรณ์ (Standby):</span> ความชื้นผิวดินและเขตรากพืชอยู่ในเกณฑ์สมดุล (Field Capacity) ประหยัดน้ำได้ 45% เมื่อเทียบกับระบบตั้งเวลาทั่วไป';
    }

    if (pumpStatus) {
        if (isPumpOn) {
            pumpStatus.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 text-xs font-bold animate-pulse';
            pumpStatus.innerHTML = '<span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span> ปั๊มน้ำกำลังทำงาน (Active ON)';
        } else {
            pumpStatus.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800 text-slate-400 border border-slate-700 text-xs font-medium';
            pumpStatus.innerHTML = '<span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span> ปั๊มน้ำหยุดพัก (Standby OFF)';
        }
    }

    if (soilDecisionText) soilDecisionText.innerHTML = explanation;
}

// ==========================================
// SIMULATOR 3: Edge AI Plant Vision Simulator
// ==========================================
const plantSampleData = {
    'healthy': {
        title: 'ใบพืชสุขภาพสมบูรณ์ (Healthy Leaf)',
        cls: 'Healthy - No Infection',
        conf: '99.4%',
        infTime: '42 ms (ESP32-S3 TinyML)',
        status: 'text-emerald-400',
        badge: 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400',
        color: '#10B981',
        desc: 'พืชสังเคราะห์แสงได้เต็มที่ คลอโรฟิลล์สม่ำเสมอ ผิวใบมันวาว ไร้ร่องรอยสปอร์เชื้อราหรือแมลงดูดกินน้ำเลี้ยง',
        action: 'คงการให้น้ำและธาตุอาหารตามตารางมาตรฐาน ไม่จำเป็นต้องใช้สารควบคุมศัตรูพืช'
    },
    'fungal_spot': {
        title: 'โรคใบจุดราสนิม (Leaf Spot / Rust Fungal)',
        cls: 'Cercospora / Rust Disease',
        conf: '96.8%',
        infTime: '48 ms (ESP32-S3 TinyML)',
        status: 'text-rose-400',
        badge: 'bg-rose-500/20 border-rose-500/40 text-rose-400',
        color: '#F43F5E',
        desc: 'พบจุดแผลสีน้ำตาลไหม้ขอบเหลืองกระจายตัวทั่วใบ สัมพันธ์กับค่า VPD < 0.35 kPa ในช่วงสัปดาห์ที่ผ่านมา',
        action: 'ตัดแต่งใบที่เป็นโรคไปทำลายทิ้ง งดการพ่นน้ำโดนใบ ใช้สารไตรโคเดอร์มาหรือคอปเปอร์ไฮดรอกไซด์ควบคุม'
    },
    'nutrient_def': {
        title: 'ภาวะขาดธาตุอาหารแมกนีเซียม/เหล็ก (Nutrient Stress)',
        cls: 'Interveinal Chlorosis (Mg/Fe Def)',
        conf: '93.2%',
        infTime: '45 ms (ESP32-S3 TinyML)',
        status: 'text-amber-400',
        badge: 'bg-amber-500/20 border-amber-500/40 text-amber-400',
        color: '#F59E0B',
        desc: 'ใบมีอาการซีดเหลืองระหว่างเส้นใบ (เส้นใบยังคงเขียว) สัมพันธ์กับค่า pH ดินที่สูงเกิน 7.2 ทำให้รากดูดซึมจุลธาตุไม่ได้',
        action: 'ปรับค่า pH ดินให้อยู่ที่ 6.0-6.5 และเสริมปุ๋ยทางใบธาตุอาหารรองแมกนีเซียมและคีเลตเหล็ก'
    },
    'pest_damage': {
        title: 'ร่องรอยเพลี้ยไฟ/ไรแดงทำลาย (Pest Damage)',
        cls: 'Thrips / Red Mite Infestation',
        conf: '94.5%',
        infTime: '44 ms (ESP32-S3 TinyML)',
        status: 'text-purple-400',
        badge: 'bg-purple-500/20 border-purple-500/40 text-purple-400',
        color: '#8B5CF6',
        desc: 'ผิวใบมีรอยสะกิดจุดเงินบรอนซ์ ใบหงิกงอผิดรูป พบการระบาดในช่วงสภาพอากาศแห้งจัดและแดดแรง',
        action: 'ใช้น้ำแรงดันสูงฉีดใต้ใบช่วงเช้า ฉีดพ่นน้ำมันกำจัดศัตรูพืช (White Oil) หรือปล่อยแมลงตัวห้ำธรรมชาติ'
    }
};

function selectPlantSample(key) {
    const data = plantSampleData[key];
    if (!data) return;

    // Highlight button
    document.querySelectorAll('.plant-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-cyan-400', 'bg-slate-700/80');
    });
    const activeBtn = document.getElementById(`pbtn-${key}`);
    if (activeBtn) activeBtn.classList.add('ring-2', 'ring-cyan-400', 'bg-slate-700/80');

    // Update diagnosis views
    const diagTitle = document.getElementById('diag-title');
    const diagClass = document.getElementById('diag-class');
    const diagConf = document.getElementById('diag-conf');
    const diagSpeed = document.getElementById('diag-speed');
    const diagDesc = document.getElementById('diag-desc');
    const diagAction = document.getElementById('diag-action');
    const diagBox = document.getElementById('diag-bbox');

    if (diagTitle) diagTitle.innerText = data.title;
    if (diagClass) {
        diagClass.innerText = data.cls;
        diagClass.className = `text-lg font-bold ${data.status}`;
    }
    if (diagConf) diagConf.innerText = data.conf;
    if (diagSpeed) diagSpeed.innerText = data.infTime;
    if (diagDesc) diagDesc.innerHTML = `<span class="text-slate-300">${data.desc}</span>`;
    if (diagAction) diagAction.innerHTML = `<span class="text-emerald-300">${data.action}</span>`;

    // Simulated Bounding Box
    if (diagBox) {
        diagBox.style.borderColor = data.color;
        diagBox.style.boxShadow = `0 0 15px ${data.color}40`;
    }
}

// ==========================================
// ATD3.5-S3 Screen Lightbox Modal
// ==========================================
function openScreenModal(src, title, desc) {
    const modal = document.getElementById('screen-modal');
    const modalImg = document.getElementById('modal-screen-img');
    const modalTitle = document.getElementById('modal-screen-title');
    const modalDesc = document.getElementById('modal-screen-desc');
    if (!modal || !modalImg) return;

    modalImg.src = src;
    if (modalTitle) modalTitle.innerText = title;
    if (modalDesc) modalDesc.innerText = desc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeScreenModal() {
    const modal = document.getElementById('screen-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Setup Event Listeners on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    // Initial calculation of simulators
    updateVPDSimulator();
    updateSoilSimulator();
    selectPlantSample('healthy');

    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar?.classList.add('bg-slate-900/95', 'shadow-xl', 'border-slate-800');
            navbar?.classList.remove('bg-slate-900/80');
        } else {
            navbar?.classList.remove('bg-slate-900/95', 'shadow-xl', 'border-slate-800');
            navbar?.classList.add('bg-slate-900/80');
        }
    });
});
