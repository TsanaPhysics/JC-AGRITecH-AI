/**
 * LEQs-xAI Portal - Main Script
 * Styled and engineered following cmu_aiot Portal standard
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */

// Configure Tailwind
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
                        blue: '#007ACC',
                        orange: '#FF8C00',
                        pink: '#EC4899',
                        dark: '#0f172a',
                        light: '#f8fafc',
                        purple: '#8B5CF6'
                    }
                },
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'bounce-slow': 'bounce 3s infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0)' },
                        '50%': { transform: 'translateY(-15px)' },
                    }
                }
            }
        }
    };
}

// Copy to Clipboard with Toast
function copyToClipboard(elementId) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const text = el.innerText || el.textContent;
    navigator.clipboard.writeText(text).then(function() {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-900/95 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 z-50 animate-bounce transition-all duration-300 border border-white/20';
        toast.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-400"></i> คัดลอกโค้ดสำเร็จ!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }).catch(function() {
        alert('คัดลอกโค้ด: ' + text.substring(0, 50) + '...');
    });
}

// Mobile Menu Toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) menu.classList.toggle('hidden');
}

// ==========================================
// 1. NAVBAR LOGO CAROUSEL ANIMATION
// ==========================================
function initNavbarLogoCarousel() {
    const logoElement = document.getElementById('navbarLogo');
    if (!logoElement) return;

    const logoImages = [
        'assets/images/nong_smartscience.png',
        'assets/images/nong_robot_v1.png',
        'assets/images/nong_robot_v2.png',
        'assets/images/nong_space.png'
    ];
    let logoIndex = 0;

    setInterval(() => {
        logoElement.style.opacity = '0';
        logoElement.style.transform = 'scale(0.8) rotate(-180deg)';
        setTimeout(() => {
            logoIndex = (logoIndex + 1) % logoImages.length;
            logoElement.src = logoImages[logoIndex];
            logoElement.style.opacity = '1';
            logoElement.style.transform = 'scale(1) rotate(0deg)';
        }, 400);
    }, 4000);
}

// ==========================================
// 2. HERO MAIN ARTWORK CAROUSEL
// ==========================================
function initHeroImageCarousel() {
    const heroImg = document.getElementById('heroImage');
    const heroCaption = document.getElementById('heroImageCaption');
    if (!heroImg) return;

    const heroArtworks = [
        {
            src: 'assets/images/cv_cover_artwork.jpg',
            caption: '🌾 Computer Vision & Precision AgriTech 2026'
        },
        {
            src: 'assets/images/cv_hardware_setup.jpg',
            caption: '🔬 ชุดบอร์ดทดลอง ATD3.5-S3 & เซนเซอร์ Modbus'
        },
        {
            src: 'assets/images/cv_agri_vision.jpg',
            caption: '🌱 การตรวจจับโรคใบทุเรียนและผลผลิตด้วย YOLOv8'
        },
        {
            src: 'assets/images/soil_7in1_dashboard_ui.jpg',
            caption: '📊 แดชบอร์ดติดตามค่า NPK, EC, pH และความชื้นดิน'
        },
        {
            src: 'assets/images/soil_probe_atd_lcd_render.jpg',
            caption: '⚡ โพรบสเตนเลส RS485 Modbus RTU สำหรับสวนผลไม้'
        },
        {
            src: 'assets/images/card_plant_ai.png',
            caption: '🤖 โมเดล TinyML On-Device วิเคราะห์สุขภาพใบพืช'
        }
    ];

    let currentIdx = 0;
    setInterval(() => {
        heroImg.style.opacity = '0';
        heroImg.style.transform = 'scale(0.96) rotate(0deg)';
        setTimeout(() => {
            currentIdx = (currentIdx + 1) % heroArtworks.length;
            const item = heroArtworks[currentIdx];
            heroImg.src = item.src;
            if (heroCaption) heroCaption.innerText = item.caption;
            heroImg.style.opacity = '1';
            heroImg.style.transform = 'scale(1) rotate(-1deg)';
        }, 500);
    }, 4800);
}

// ==========================================
// 3. ATD3.5-S3 SCREEN CYCLING CAROUSEL (หน้าจอหมุนวนสลับภาพ)
// ==========================================
const atdScreens = [
    {
        num: '01/10',
        name: '01. Overview Dashboard',
        file: 'assets/images/atd35/01_overview_dashboard.png',
        title: 'Screen 01: Overview Dashboard',
        desc: 'แดชบอร์ดหลัก 4 มิติ สภาพอากาศ VPD, แสงอาทิตย์ PAR, ผิวดิน, เขตราก 7-in-1 แบบเรียลไทม์ 60 FPS'
    },
    {
        num: '02/10',
        name: '02. Big Numbers Telemetry',
        file: 'assets/images/atd35/02_big_numbers.png',
        title: 'Screen 02: Big Numbers Telemetry',
        desc: 'โหมดตัวเลขอักษรขนาดใหญ่พิเศษ สำหรับมองเห็นชัดเจนในระยะ 5-10 เมตรในแปลง'
    },
    {
        num: '03/10',
        name: '03. Realtime Sensors Graph',
        file: 'assets/images/atd35/03_realtime_graphs.png',
        title: 'Screen 03: Realtime Graphs',
        desc: 'กราฟบันทึกแนวโน้มอุณหภูมิและความชื้นย้อนหลัง 24 ชั่วโมงแบบเรียลไทม์'
    },
    {
        num: '04/10',
        name: '04. Relay & Solenoid Control',
        file: 'assets/images/atd35/04_relay_control.png',
        title: 'Screen 04: Relay & Solenoid Control',
        desc: 'แผงควบคุมสวิตช์รีเลย์และวาล์วไฟฟ้า 12V/24V ควบคุมการให้น้ำอัตโนมัติ'
    },
    {
        num: '05/10',
        name: '05. Wi-Fi Captive Portal',
        file: 'assets/images/atd35/05_wifi_captive_portal.png',
        title: 'Screen 05: Wi-Fi Captive Portal',
        desc: 'ระบบจับคู่การเชื่อมต่ออินเทอร์เน็ตผ่าน QR Code แบบ Zero-Configuration'
    },
    {
        num: '06/10',
        name: '06. SHT45 Microclimate & VPD',
        file: 'assets/images/atd35/06_sht45_air_vpd.png',
        title: 'Screen 06: SHT45 Microclimate & VPD',
        desc: 'การวิเคราะห์สภาพอากาศย่อย ค่าความดันไอขาดดุล และสถานะความเสี่ยงโรครา'
    },
    {
        num: '07/10',
        name: '07. Solar Spectrum & PAR',
        file: 'assets/images/atd35/07_bh1750_solar_par.png',
        title: 'Screen 07: Solar Spectrum & PAR',
        desc: 'การวัดความเข้มแสงแดด Lux และคำนวณโฟตอนสังเคราะห์แสง PAR (umol/m2/s)'
    },
    {
        num: '08/10',
        name: '08. Soil Stick Surface Moisture',
        file: 'assets/images/atd35/08_soil_stick_surface.png',
        title: 'Screen 08: Soil Stick Surface Moisture',
        desc: 'การตรวจสอบความชื้นผิวดิน 0-10 cm เฝ้าระวังการระเหยน้ำและการแตกระแหง'
    },
    {
        num: '09/10',
        name: '09. Soil 7in1 TinyML Inference',
        file: 'assets/images/atd35/09_soil_7in1_tinyml.png',
        title: 'Screen 09: Soil 7in1 TinyML Inference',
        desc: 'ผลการทำนายความต้องการปุ๋ยและน้ำจากโมเดล TinyML ที่รันบน ESP32 โดยตรง'
    },
    {
        num: '10/10',
        name: '00. Nexus Boot Splash',
        file: 'assets/images/atd35/00_splash_nexus.png',
        title: 'Screen 00: Nexus Boot Splash',
        desc: 'หน้าจอต้อนรับและบูตระบบเฟิร์มแวร์ LEQs-xAI Smart Controller v3.5'
    }
];

let currentAtdIndex = 0;
let atdScreenTimer = null;

function renderAtdScreen(index) {
    currentAtdIndex = (index + atdScreens.length) % atdScreens.length;
    const item = atdScreens[currentAtdIndex];

    const screenImg = document.getElementById('atdScreenImg');
    const screenBadge = document.getElementById('atdScreenBadge');
    const screenName = document.getElementById('atdScreenName');

    if (screenImg) {
        screenImg.style.opacity = '0.3';
        screenImg.style.transform = 'scale(0.97)';
        setTimeout(() => {
            screenImg.src = item.file;
            screenImg.style.opacity = '1';
            screenImg.style.transform = 'scale(1)';
        }, 250);
    }

    if (screenBadge) screenBadge.innerText = 'Screen ' + item.num;
    if (screenName) screenName.innerText = item.name;

    // Update Dots indicator if present
    const dots = document.querySelectorAll('.atd-dot');
    dots.forEach((dot, idx) => {
        if (idx === currentAtdIndex) {
            dot.className = 'atd-dot w-4 h-1.5 rounded-full bg-emerald-400 transition-all duration-300';
        } else {
            dot.className = 'atd-dot w-1.5 h-1.5 rounded-full bg-slate-700 hover:bg-slate-500 transition-all duration-300';
        }
    });
}

function jumpToAtdScreen(index) {
    renderAtdScreen(index);
    resetAtdScreenTimer();
}

function openActiveAtdScreenModal() {
    const item = atdScreens[currentAtdIndex];
    openScreenModal(item.file, item.title, item.desc);
}

function resetAtdScreenTimer() {
    if (atdScreenTimer) clearInterval(atdScreenTimer);
    atdScreenTimer = setInterval(() => {
        renderAtdScreen(currentAtdIndex + 1);
    }, 3500);
}

function initAtdScreenCarousel() {
    const container = document.getElementById('atdScreenContainer');
    if (!container) return;

    renderAtdScreen(0);
    resetAtdScreenTimer();

    // Pause on hover
    container.addEventListener('mouseenter', () => {
        if (atdScreenTimer) clearInterval(atdScreenTimer);
    });
    container.addEventListener('mouseleave', () => {
        resetAtdScreenTimer();
    });
}

// ==========================================
// 4. HERO TICKER SEQUENCE (cmu_aiot style)
// ==========================================
function initHeroTicker() {
    const ticker = document.getElementById('hero-ticker');
    if (!ticker) return;

    const baseClass = "inline-block py-2 px-6 rounded-2xl bg-emerald-100/90 backdrop-blur-sm shadow-md text-sm md:text-base font-bold tracking-wide whitespace-nowrap border-l-4 border-emerald-500 transition-all duration-300";
    const colors = ['#059669', '#0284c7', '#d97706', '#7c3aed', '#dc2626', '#0891b2'];
    const animations = ['anim-zoom', 'anim-slide-up', 'anim-slide-down', 'anim-wobble'];
    const wait = (ms) => new Promise(resolve => setTimeout(resolve, ms));
    const getRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];

    const runTickerSequence = async () => {
        while (true) {
            // Stage 1: Faculty / Initiative name
            ticker.className = baseClass + " text-emerald-700 anim-blink";
            ticker.innerHTML = "🏛️ คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี x งบประมาณ 76,000 บ.";
            await wait(3400);

            // Stage 2: Program Title
            let anim = getRandom(animations);
            ticker.className = `${baseClass} ${anim}`;
            ticker.style.color = getRandom(colors);
            ticker.innerHTML = "🌾 LEQs-xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม 2026";
            await wait(4200);

            // Stage 3: Modules Marquee
            ticker.className = `${baseClass} anim-marquee`;
            ticker.style.color = getRandom(colors);
            ticker.innerHTML = "🚀 7 โมดูลปฏิบัติการ: M1 AIoT Sensor Hub | M2 TinyML Edge AI | M3 Computer Vision | M4 LoRaWAN & Zigbee Mesh | M5 VPD & Irrigation | M6 Cloud & Telegram Bot | M7 Capstone Showcase";
            await wait(24000);

            // Stage 4: Motto
            anim = getRandom(animations);
            ticker.className = `${baseClass} ${anim}`;
            ticker.style.color = getRandom(colors);
            ticker.innerHTML = "💡 “จากข้อมูลสู่ปัญญา จาก AI สู่เกษตรอัจฉริยะ และจากห้องเรียนสู่ภาคสนาม”";
            await wait(3800);
        }
    };

    runTickerSequence();
}

// ==========================================
// SIMULATOR 1: VPD (Vapor Pressure Deficit)
// ==========================================
function updateVPDSimulator() {
    const tempInput = document.getElementById('vpd-temp');
    const humInput = document.getElementById('vpd-humidity');
    if (!tempInput || !humInput) return;

    const T = parseFloat(tempInput.value);
    const RH = parseFloat(humInput.value);

    const dispTemp = document.getElementById('val-vpd-temp');
    const dispHum = document.getElementById('val-vpd-hum');
    if (dispTemp) dispTemp.innerText = T.toFixed(1) + ' °C';
    if (dispHum) dispHum.innerText = RH.toFixed(0) + ' %';

    const SVP = 0.61078 * Math.exp((17.27 * T) / (T + 237.3));
    const AVP = SVP * (RH / 100.0);
    const VPD = SVP - AVP;

    const outVpd = document.getElementById('out-vpd-val');
    const outSvp = document.getElementById('out-svp-val');
    const outAvp = document.getElementById('out-avp-val');

    if (outVpd) outVpd.innerText = VPD.toFixed(2);
    if (outSvp) outSvp.innerText = SVP.toFixed(2);
    if (outAvp) outAvp.innerText = AVP.toFixed(2);

    const box = document.getElementById('box-vpd-advisory');
    const title = document.getElementById('title-vpd-advisory');
    const desc = document.getElementById('desc-vpd-advisory');

    if (box && title && desc) {
        if (VPD < 0.4) {
            box.className = "mt-6 p-4 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-800 text-xs flex items-start gap-3";
            title.innerText = "ภาวะเสี่ยงเชื้อราและโรคพืช (VPD < 0.4 kPa) - ความชื้นสูงเกินไป";
            desc.innerText = "ปากใบพืชปิด ไม่มีการคายน้ำ เสี่ยงต่อโรคราสนิม ราแป้ง และโรครากเน่า ควรเพิ่มการระบายอากาศ เปิดพัดลมระบายความชื้น";
        } else if (VPD <= 1.25) {
            box.className = "mt-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs flex items-start gap-3";
            title.innerText = "สภาวะเหมาะสมสมบูรณ์แบบ (VPD 0.8 - 1.25 kPa)";
            desc.innerText = "การคายน้ำและการดูดซึมธาตุอาหาร NPK ดำเนินไปอย่างสมบูรณ์แบบ ทุเรียนและพืชแปลงขยายขนาดได้อย่างมีประสิทธิภาพ";
        } else if (VPD <= 1.6) {
            box.className = "mt-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 text-xs flex items-start gap-3";
            title.innerText = "สภาวะเริ่มเครียดน้ำ (VPD 1.25 - 1.60 kPa)";
            desc.innerText = "อากาศเริ่มแห้งและร้อน แนะนำให้เพิ่มรอบการให้น้ำทางดิน หรือเปิดสปริงเกลอร์ใต้ทรงพุ่มรักษาความชื้น";
        } else {
            box.className = "mt-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-700 text-xs flex items-start gap-3";
            title.innerText = "วิกฤตความแห้งแล้งสูง (VPD > 1.60 kPa) - อันตราย";
            desc.innerText = "พืชปิดปากใบสนิท เสี่ยงสลัดผลอ่อนและหนามไหม้แดง ต้องเปิดระบบพ่นหมอก/สปริงเกลอร์ช่วยระบายความร้อนด่วน";
        }
    }
}

// ==========================================
// SIMULATOR 2: Coastal Aquaculture DO & VFD
// ==========================================
function updateAquaSimulator() {
    const tempInput = document.getElementById('inputAquaTemp');
    const salInput = document.getElementById('inputAquaSal');
    const doInput = document.getElementById('inputAquaDo');
    if (!tempInput || !salInput || !doInput) return;

    const T = parseFloat(tempInput.value);
    const S = parseFloat(salInput.value);
    const DO = parseFloat(doInput.value);

    const valTemp = document.getElementById('valAquaTemp');
    const valSal = document.getElementById('valAquaSal');
    const valDo = document.getElementById('valAquaDo');
    if (valTemp) valTemp.innerText = T.toFixed(1) + ' °C';
    if (valSal) valSal.innerText = S.toFixed(0) + ' ppt';
    if (valDo) valDo.innerText = DO.toFixed(2) + ' mg/L';

    const Tk = T + 273.15;
    const lnC = -139.34411 + (1.575701e5 / Tk) - (6.642308e7 / Math.pow(Tk, 2)) + (1.2438e10 / Math.pow(Tk, 3)) - (8.621949e11 / Math.pow(Tk, 4));
    let DO_sat = Math.exp(lnC);
    const Fs = S * (0.017674 - (10.754 / Tk) + (2140.7 / Math.pow(Tk, 2)));
    DO_sat = DO_sat * Math.exp(-Fs);

    const outDoSat = document.getElementById('outDoSat');
    if (outDoSat) outDoSat.innerText = DO_sat.toFixed(2) + ' mg/L';

    let freq = 50.0;
    if (DO < 3.5) {
        freq = 50.0;
    } else if (DO < 4.5) {
        freq = 44.0;
    } else if (DO < 6.0) {
        freq = 38.0;
    } else {
        freq = 30.0;
    }

    const powerRatio = Math.pow(freq / 50.0, 3);
    const powerSavePct = (1.0 - powerRatio) * 100.0;

    const outFreq = document.getElementById('outVfdFreq');
    const outSave = document.getElementById('outPowerSave');
    if (outFreq) outFreq.innerText = freq.toFixed(1) + ' Hz';
    if (outSave) outSave.innerText = powerSavePct.toFixed(1) + ' %';

    const box = document.getElementById('boxAquaAdvisory');
    const title = document.getElementById('titleAquaAdvisory');
    const desc = document.getElementById('descAquaAdvisory');

    if (box && title && desc) {
        if (DO < 3.5) {
            box.className = "mt-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-700 text-xs flex items-start gap-3";
            title.innerText = "ระดับออกซิเจนวิกฤต (DO < 3.5 mg/L) - กุ้งเสี่ยงตายเฉียบพลัน";
            desc.innerText = "สั่งการมอเตอร์กังหันตีน้ำทำงานเต็มพิกัด 100% (50.0 Hz) พร้อมเปิดท่อฟองอากาศใต้น้ำช่วยเสริมออกซิเจนด่วน";
        } else if (DO < 4.5) {
            box.className = "mt-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 text-xs flex items-start gap-3";
            title.innerText = "ระดับออกซิเจนปานกลาง (DO 3.5 - 4.5 mg/L)";
            desc.innerText = "รักษารอบมอเตอร์ที่ 44.0 Hz ป้องกันการขาดออกซิเจนช่วงเช้ามืด ประหยัดไฟ 31.8%";
        } else {
            box.className = "mt-6 p-4 rounded-2xl bg-sky-500/10 border border-sky-500/30 text-sky-800 text-xs flex items-start gap-3";
            title.innerText = "ออกซิเจนสมบูรณ์แบบ (DO > 4.5 mg/L) - ประหยัดพลังงานสูงสุด";
            desc.innerText = `ระบบ VFD ชะลอความถี่ลงเหลือ ${freq.toFixed(1)} Hz ช่วยลดกำลังไฟฟ้าได้ถึง ${powerSavePct.toFixed(1)}% ลดต้นทุนค่าไฟได้หลักหมื่นบาทต่อรอบการเลี้ยง`;
        }
    }
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
        status: 'text-emerald-600',
        badge: 'bg-emerald-100 text-emerald-700',
        color: '#10B981',
        desc: 'พืชสังเคราะห์แสงได้เต็มที่ คลอโรฟิลล์สม่ำเสมอ ผิวใบมันวาว ไร้ร่องรอยสปอร์เชื้อราหรือแมลงดูดกินน้ำเลี้ยง',
        action: 'คงการให้น้ำและธาตุอาหารตามตารางมาตรฐาน ไม่จำเป็นต้องใช้สารควบคุมศัตรูพืช'
    },
    'fungal_spot': {
        title: 'โรคใบจุดราสนิม (Leaf Spot / Rust Fungal)',
        cls: 'Cercospora / Rust Disease',
        conf: '96.8%',
        infTime: '48 ms (ESP32-S3 TinyML)',
        status: 'text-rose-600',
        badge: 'bg-rose-100 text-rose-700',
        color: '#F43F5E',
        desc: 'พบจุดแผลสีน้ำตาลไหม้ขอบเหลืองกระจายตัวทั่วใบ สัมพันธ์กับค่า VPD < 0.35 kPa ในช่วงสัปดาห์ที่ผ่านมา',
        action: 'ตัดแต่งใบที่เป็นโรคไปทำลายทิ้ง งดการพ่นน้ำโดนใบ ใช้สารไตรโคเดอร์มาหรือคอปเปอร์ไฮดรอกไซด์ควบคุม'
    },
    'nutrient_def': {
        title: 'ภาวะขาดธาตุอาหารแมกนีเซียม/เหล็ก (Nutrient Stress)',
        cls: 'Interveinal Chlorosis (Mg/Fe Def)',
        conf: '93.2%',
        infTime: '45 ms (ESP32-S3 TinyML)',
        status: 'text-amber-600',
        badge: 'bg-amber-100 text-amber-700',
        color: '#F59E0B',
        desc: 'ใบมีอาการซีดเหลืองระหว่างเส้นใบ (เส้นใบยังคงเขียว) สัมพันธ์กับค่า pH ดินที่สูงเกิน 7.2 ทำให้รากดูดซึมจุลธาตุไม่ได้',
        action: 'ปรับค่า pH ดินให้อยู่ที่ 6.0-6.5 และเสริมปุ๋ยทางใบธาตุอาหารรองแมกนีเซียมและคีเลตเหล็ก'
    },
    'pest_damage': {
        title: 'ร่องรอยเพลี้ยไฟ/ไรแดงทำลาย (Pest Damage)',
        cls: 'Thrips / Red Mite Infestation',
        conf: '94.5%',
        infTime: '44 ms (ESP32-S3 TinyML)',
        status: 'text-purple-600',
        badge: 'bg-purple-100 text-purple-700',
        color: '#8B5CF6',
        desc: 'ผิวใบมีรอยสะกิดจุดเงินบรอนซ์ ใบหงิกงอผิดรูป พบการระบาดในช่วงสภาพอากาศแห้งจัดและแดดแรง',
        action: 'ใช้น้ำแรงดันสูงฉีดใต้ใบช่วงเช้า ฉีดพ่นน้ำมันกำจัดศัตรูพืช (White Oil) หรือปล่อยแมลงตัวห้ำธรรมชาติ'
    }
};

function selectPlantSample(key) {
    const data = plantSampleData[key];
    if (!data) return;

    document.querySelectorAll('.plant-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50', 'text-emerald-700');
        btn.classList.add('bg-white', 'text-gray-700');
    });
    const activeBtn = document.getElementById(`pbtn-${key}`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-gray-700');
        activeBtn.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50', 'text-emerald-700');
    }

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
    if (diagDesc) diagDesc.innerHTML = `<span class="text-gray-700">${data.desc}</span>`;
    if (diagAction) diagAction.innerHTML = `<span class="text-emerald-700 font-medium">${data.action}</span>`;

    if (diagBox) {
        diagBox.style.borderColor = data.color;
        diagBox.style.boxShadow = `0 0 15px ${data.color}40`;
    }
}

// ATD3.5-S3 Screen Lightbox Modal
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

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    initNavbarLogoCarousel();
    initHeroImageCarousel();
    initAtdScreenCarousel();
    initHeroTicker();
    updateVPDSimulator();
    updateAquaSimulator();
    selectPlantSample('healthy');

    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar?.classList.add('bg-white/95', 'shadow-md', 'border-gray-200');
            navbar?.classList.remove('bg-white/90');
        } else {
            navbar?.classList.remove('bg-white/95', 'shadow-md', 'border-gray-200');
            navbar?.classList.add('bg-white/90');
        }
    });
});
