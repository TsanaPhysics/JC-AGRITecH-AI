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
// 2. HERO SINGLE SCREEN CAROUSEL (สกรีนเดียว รวมภาพจาก soil_nutrient2026, cmu_aiot & บอร์ด)
// ==========================================
const heroSlides = [
    {
        file: 'assets/images/leqs_xai_hero_cover.jpg',
        category: 'LEQs xAI Master Showcase',
        categoryDot: 'bg-emerald-400',
        title: 'LEQs xAI: ปัญญาประดิษฐ์เพื่อเกษตรดิจิทัลและสิ่งแวดล้อม',
        desc: 'Digital Agriculture - Environment • Edge AI • Deep Learning • Computer Vision • Environmental IoT | พัฒนาโดย LEQs-TEAMS คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี'
    },
    {
        file: 'assets/images/soil_nutrient2026/portable_iot_pixar.png',
        category: 'Portable Soil IoT',
        categoryDot: 'bg-emerald-400',
        title: 'Portable IoT Device: เครื่องวัดวิเคราะห์ธาตุอาหารดินแบบพกพา',
        desc: 'ย่อส่วนเทคโนโลยีห้องแล็บให้อยู่ในรูปแบบอุปกรณ์พกพา หรือใช้ร่วมกับ Smartphone ทราบค่า NPK ทันทีที่หน้าแปลงปลูก (chewa.rbru.ac.th/soil_nutrient2026)'
    },
    {
        file: 'assets/images/soil_nutrient2026/tech_spectroscopy_diagram_1766189587511.png',
        category: 'Reflectance Spectroscopy',
        categoryDot: 'bg-cyan-400',
        title: 'Reflectance Spectroscopy: ลายนิ้วมือสเปกตรัมแสงสะท้อน',
        desc: 'วัดค่าแสงสะท้อนจากดินช่วง Visible & NIR ธาตุอาหารแต่ละชนิดดูดซับแสงต่างกัน เกิดเป็นลายนิ้วมือสเปกตรัมเฉพาะตัว'
    },
    {
        file: 'assets/images/soil_nutrient2026/tech_ai_network_1766189604369.png',
        category: 'AI & Deep Learning',
        categoryDot: 'bg-indigo-400',
        title: 'Artificial Intelligence (AI): โครงข่ายประสาทเทียมคำนวณปุ๋ย NPK',
        desc: 'Machine Learning ประมวลผลข้อมูลแสงสเปกตรัม ตัดสัญญาณรบกวน และแปลงค่าแสงเป็นปริมาณธาตุอาหาร N, P, K แม่นยำ'
    },
    {
        file: 'assets/images/soil_nutrient2026/simulated_cam_feed_1766190123.jpg',
        category: 'Computer Vision App',
        categoryDot: 'bg-amber-400',
        title: 'Smart Soil App & Camera Vision สแกนวิเคราะห์หน้าดิน',
        desc: 'ประมวลผลผ่านกล้องสมาร์ตโฟนและ Edge AI ตัดปัญหาแสงเพี้ยน ให้ผลแม่นยำกว่าตาเปล่าและใช้งานได้จริงในสนาม'
    },
    {
        file: 'assets/images/soil_nutrient2026/sample_soil_quick.jpg',
        category: 'Quick Scan Field Test',
        categoryDot: 'bg-orange-400',
        title: 'Quick Scan: โหมดสแกนด่วนวิเคราะห์เนื้อดินและธาตุอาหาร',
        desc: 'ส่องกล้องสแกนไปที่ผิวดินโดยตรงเพื่อประเมินความอุดมสมบูรณ์และปริมาณธาตุอาหารหลักเบื้องต้นอย่างรวดเร็ว'
    },
    {
        file: 'assets/images/soil_nutrient2026/sample_soil_lab.jpg',
        category: 'Lab Standard Chemistry',
        categoryDot: 'bg-purple-400',
        title: 'Lab Test: เทียบเคียงมาตรฐานการทดสอบเคมีในห้องปฏิบัติการ',
        desc: 'เปรียบเทียบผลการวิเคราะห์กับสารเคมีมาตรฐานและแล็บวิจัย เพื่อความแม่นยำสูงสุดตามหลักการเกษตรแม่นยำ'
    },
    {
        file: 'assets/images/cv_hardware_setup.jpg',
        category: 'Hardware & Sensors',
        categoryDot: 'bg-cyan-400',
        title: 'ชุดบอร์ดทดลอง ATD3.5-S3 & เซนเซอร์ Modbus',
        desc: 'ESP32-S3 Xtensa LX7 พร้อมกล้อง AI และโพรบวัดดินสแตนเลส 7-in-1'
    },
    {
        file: 'assets/images/cv_agri_vision.jpg',
        category: 'AgriTech AI Vision',
        categoryDot: 'bg-teal-400',
        title: 'ตรวจจับโรคใบทุเรียนและคัดเกรดผลผลิต',
        desc: 'ประยุกต์ใช้โมเดลดีพเลิร์นนิง YOLOv8 ตรวจวินิจฉัยโรคพืชในสวนจริง'
    },
    {
        file: 'assets/images/soil_7in1_dashboard_ui.jpg',
        category: 'IoT Telemetry UI',
        categoryDot: 'bg-blue-400',
        title: 'แดชบอร์ดติดตามค่า NPK, EC, pH และความชื้นดิน',
        desc: 'แสดงผลข้อมูลเขตราก 10-30 cm วิเคราะห์ความอุดมสมบูรณ์ของดินด้วย AI'
    },
    {
        file: 'assets/images/pixar_smart_farm_hero.jpg',
        category: '3D Pixar & Ghibli AIoT',
        categoryDot: 'bg-emerald-400',
        title: '🌱 นวัตกรรมเกษตรดิจิทัล 3D Pixar Ghibli AIoT',
        desc: 'การเรียนรู้ปัญญาประดิษฐ์และเซนเซอร์การเกษตรเชิงสร้างสรรค์ ผสานหุ่นยนต์และระบบอัจฉริยะ'
    },
    {
        file: 'assets/images/atd35/01_overview_dashboard.png',
        category: 'ATD3.5-S3 Touch Display',
        categoryDot: 'bg-purple-400',
        title: 'จอแสดงผลสัมผัส 3.5 นิ้ว: Overview 4D Dashboard',
        desc: 'แสดงผล 4 มิติ สภาพอากาศ VPD, แสงอาทิตย์ PAR, ผิวดิน และเขตราก 60 FPS'
    },
    {
        file: 'assets/images/atd35/11_qr_portal_screen.png',
        category: 'ESP32 Screen 11',
        categoryDot: 'bg-cyan-400',
        title: 'จอที่ 11: Official QR Portal & Identity Verification',
        desc: '2026.10.04 วันอาทิตย์ • 09:03 ชีวะ ทัศนา • https://aidar.rbru.ac.th/leqsxai'
    },
    {
        file: 'assets/images/card_plant_ai.png',
        category: 'TinyML On-Device',
        categoryDot: 'bg-rose-400',
        title: 'โมเดล TinyML On-Device วิเคราะห์สุขภาพใบพืช',
        desc: 'สถาปัตยกรรม CNN ขนาดกะทัดรัด ประมวลผลบนชิปไมโครคอนโทรลเลอร์โดยไม่ต้องต่อเน็ต'
    },
    {
        file: 'assets/images/card_soil_expert.png',
        category: 'Quantitative Science',
        categoryDot: 'bg-emerald-400',
        title: 'ระบบผู้เชี่ยวชาญวินิจฉัยธาตุอาหารในดิน (NPK)',
        desc: 'ประเมินสมดุลธาตุอาหารพืชและคำนวณการใส่ปุ๋ยเคมี/อินทรีย์อย่างแม่นยำ'
    },
    {
        file: 'assets/images/soil_probe_atd_lcd_render.jpg',
        category: 'Industrial Probes',
        categoryDot: 'bg-indigo-400',
        title: 'โพรบวัดดินสแตนเลสแท้มาตรฐานอุตสาหกรรม',
        desc: 'ทนทานต่อการกัดกร่อน เชื่อมต่อผ่านสายสัญญาณ RS485 มาตรฐานอุตสาหกรรม'
    }
];

let currentSlideIdx = 0;
let heroSlideTimer = null;

function renderHeroSlide(index) {
    currentSlideIdx = (index + heroSlides.length) % heroSlides.length;
    const slide = heroSlides[currentSlideIdx];

    const img = document.getElementById('heroSingleScreenImg');
    const title = document.getElementById('heroSingleScreenTitle');
    const desc = document.getElementById('heroSingleScreenDesc');
    const badge = document.getElementById('heroSlideBadge');
    const catName = document.getElementById('heroCategoryName');
    const catDot = document.getElementById('heroCategoryDot');

    if (img) {
        img.style.opacity = '0.3';
        img.style.transform = 'scale(0.97)';
        setTimeout(() => {
            img.src = slide.file;
            img.style.opacity = '1';
            img.style.transform = 'scale(1)';
        }, 200);
    }

    if (title) title.innerText = slide.title;
    if (desc) desc.innerText = slide.desc;
    if (badge) {
        const cur = String(currentSlideIdx + 1).padStart(2, '0');
        const tot = String(heroSlides.length).padStart(2, '0');
        badge.innerText = `Slide ${cur}/${tot}`;
    }
    if (catName) catName.innerText = slide.category;
    if (catDot) catDot.className = `w-2 h-2 rounded-full ${slide.categoryDot} animate-ping`;

    // Render Indicator Dots
    renderHeroDots();
}

function renderHeroDots() {
    const dotsBox = document.getElementById('heroScreenDots');
    if (!dotsBox) return;

    dotsBox.innerHTML = '';
    heroSlides.forEach((s, idx) => {
        const dot = document.createElement('button');
        if (idx === currentSlideIdx) {
            dot.className = 'w-5 h-2 rounded-full bg-emerald-500 transition-all duration-300 shadow-sm';
        } else {
            dot.className = 'w-2 h-2 rounded-full bg-gray-200 hover:bg-gray-400 transition-all duration-300';
        }
        dot.title = s.title;
        dot.onclick = () => jumpToHeroSlide(idx);
        dotsBox.appendChild(dot);
    });
}

function jumpToHeroSlide(idx) {
    renderHeroSlide(idx);
    resetHeroSlideTimer();
}

function nextHeroSlide() {
    renderHeroSlide(currentSlideIdx + 1);
    resetHeroSlideTimer();
}

function prevHeroSlide() {
    renderHeroSlide(currentSlideIdx - 1);
    resetHeroSlideTimer();
}

function openCurrentHeroSlideModal() {
    const slide = heroSlides[currentSlideIdx];
    openScreenModal(slide.file, slide.title, slide.desc);
}

function resetHeroSlideTimer() {
    if (heroSlideTimer) clearInterval(heroSlideTimer);
    heroSlideTimer = setInterval(() => {
        renderHeroSlide(currentSlideIdx + 1);
    }, 3800);
}

function initHeroSingleScreenCarousel() {
    const container = document.getElementById('heroSingleScreenContainer');
    if (!container) return;

    renderHeroSlide(0);
    resetHeroSlideTimer();

    // Pause on hover
    container.addEventListener('mouseenter', () => {
        if (heroSlideTimer) clearInterval(heroSlideTimer);
    });
    container.addEventListener('mouseleave', () => {
        resetHeroSlideTimer();
    });
}

// ==========================================
// 3. ONLINE REMOTE CONTROL SIMULATOR (ควบคุม สั่งการ บอร์ดทางออนไลน์)
// ==========================================
const relayStates = { 1: false, 2: false, 3: false, 4: false };

function toggleRemoteRelay(relayId, name) {
    relayStates[relayId] = !relayStates[relayId];
    const isNowOn = relayStates[relayId];

    const led = document.getElementById(`relay-led-${relayId}`);
    const btn = document.getElementById(`relay-btn-${relayId}`);
    const statusText = document.getElementById(`relay-status-${relayId}`);

    if (led) {
        led.className = isNowOn ? 'w-3 h-3 rounded-full bg-emerald-500 animate-ping inline-block' : 'w-3 h-3 rounded-full bg-gray-300 inline-block';
    }
    if (btn) {
        btn.className = isNowOn 
            ? 'px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5' 
            : 'px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs shadow-xs transition flex items-center gap-1.5';
        btn.innerHTML = isNowOn ? '<i class="fa-solid fa-power-off"></i> สั่งปิด (ON)' : '<i class="fa-solid fa-power-off"></i> สั่งเปิด (OFF)';
    }
    if (statusText) {
        statusText.innerText = isNowOn ? 'สถานะ: กำลังทำงาน (ACTIVE ON)' : 'สถานะ: ปิดการทำงาน (STANDBY OFF)';
        statusText.className = isNowOn ? 'text-[11px] font-bold text-emerald-600' : 'text-[11px] font-bold text-gray-400';
    }

    // Append to live MQTT packet log
    const logBox = document.getElementById('mqtt-log-console');
    if (logBox) {
        const timeStr = new Date().toLocaleTimeString('th-TH');
        const stateStr = isNowOn ? 'ACTIVE_HIGH_ON' : 'ACTIVE_LOW_OFF';
        const line = document.createElement('div');
        line.className = 'text-[10px] font-mono ' + (isNowOn ? 'text-emerald-400' : 'text-gray-400');
        line.innerText = `[${timeStr}] MQTT ➔ /board/relay/${relayId}/cmd: {"cmd":"${stateStr}", "actuator":"${name}"}`;
        logBox.prepend(line);
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: isNowOn ? 'success' : 'info',
            title: `${name}: ${isNowOn ? 'เปิดทำงานแล้ว' : 'ปิดทำงานแล้ว'}`,
            text: `ส่งคำสั่ง MQTT ควบคุมสำเร็จสู่บอร์ด ESP32-S3 ATD3.5`,
            showConfirmButton: false,
            timer: 2000
        });
    }
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
            ticker.innerHTML = "🏛️ พัฒนาโดย ทีม LEQs คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี";
            await wait(3800);

            // Stage 2: Program Title
            let anim = getRandom(animations);
            ticker.className = `${baseClass} ${anim}`;
            ticker.style.color = getRandom(colors);
            ticker.innerHTML = "🌱 LEQs xAI: Digital Agriculture - Environment (Edge AI • Deep Learning • Computer Vision • Environmental IoT)";
            await wait(4500);

            // Stage 3: Modules Marquee
            ticker.className = `${baseClass} anim-marquee`;
            ticker.style.color = getRandom(colors);
            ticker.innerHTML = "🚀 7 โมดูลปฏิบัติการ: M1 AIoT Sensor Hub | M2 TinyML Edge AI | M3 Computer Vision | M4 LoRaWAN & Zigbee Mesh | M5 Smartphone App & Web Dashboard | M6 Environmental AI | M7 Capstone Showcase";
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
    initHeroSingleScreenCarousel();
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
