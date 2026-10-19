<?php
/**
 * ============================================================================
 * น้อง LEQs AI Bot : Autonomous Agentic AI Mascot & Voice Assistant
 * Edge xAI : 4DAgriEnvi Smart Agri-Environmental System
 * Modeled after LEQs-GLOBE 2570 Agentic Bot Architecture
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * ============================================================================
 */
$bot_base_prefix = (isset($is_subpage) && $is_subpage) ? '../' : '';
?>

<!-- =========================================================================
     Floating 3D Mascot Trigger (Bottom Right - Ergonomic & Non-overlapping)
========================================================================= -->
<div id="leqsBotFloatingTrigger" class="fixed bottom-[86px] md:bottom-6 right-3 sm:right-6 z-50 flex flex-col items-end gap-1.5 group select-none">
    
    <!-- Speech Bubble Prompt (Floats directly above avatar) -->
    <div id="leqsBotSpeechBubble" class="hidden sm:flex items-center gap-2 bg-slate-900/95 backdrop-blur-xl px-3.5 py-2 rounded-2xl shadow-2xl border border-emerald-500/30 text-xs font-semibold text-white animate-bounce cursor-pointer hover:border-emerald-400 transition-all max-w-[260px]" onclick="toggleLeqsChatbot()">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
        <span id="leqsBubbleText" class="truncate text-[11px]">มีอะไรให้ <strong>น้อง LEQs</strong> ช่วยเนี่ยฮิ? 🌱🌤️</span>
        <button type="button" class="text-slate-400 hover:text-white ml-1 text-sm leading-none shrink-0" onclick="event.stopPropagation(); document.getElementById('leqsBotSpeechBubble').classList.add('hidden');">&times;</button>
    </div>

    <!-- 3D Mascot Avatar Circle Button -->
    <button type="button" id="leqsBotAvatarBtn" onclick="toggleLeqsChatbot()" aria-label="เปิดน้อง LEQs AI Bot"
        class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-tr from-emerald-600 via-teal-500 to-cyan-400 p-1 shadow-[0_10px_25px_rgba(16,185,129,0.45)] hover:scale-105 active:scale-95 transition-all duration-300 ring-4 ring-white/90 focus:outline-none">
        
        <!-- Rotating Aura Ring -->
        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 opacity-70 blur-[3px] animate-spin" style="animation-duration: 9s;"></div>
        
        <!-- Mascot 3D Agri-Eco Robot Inside -->
        <div class="relative w-full h-full rounded-full bg-slate-950 overflow-hidden flex items-center justify-center border-2 border-white/70">
            <svg class="w-14 h-14 sm:w-16 sm:h-16 transform group-hover:rotate-6 transition-transform" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="robotGrad" cx="35%" cy="30%" r="70%">
                        <stop offset="0%" stop-color="#10B981"/>
                        <stop offset="55%" stop-color="#047857"/>
                        <stop offset="100%" stop-color="#064E3B"/>
                    </radialGradient>
                    <radialGradient id="visorGrad" cx="50%" cy="40%" r="60%">
                        <stop offset="0%" stop-color="#38BDF8"/>
                        <stop offset="50%" stop-color="#0284C7"/>
                        <stop offset="100%" stop-color="#0F172A"/>
                    </radialGradient>
                    <filter id="ecoGlow">
                        <feGaussianBlur stdDeviation="1.5" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>

                <!-- Robot Head Sphere -->
                <circle cx="50" cy="50" r="42" fill="url(#robotGrad)" />
                
                <!-- Smart Agri Glass Visor (Curved 3D Display) -->
                <rect x="22" y="32" width="56" height="32" rx="14" fill="url(#visorGrad)" stroke="#38BDF8" stroke-width="1.5" />
                <path d="M26 36 Q50 30 74 36" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" fill="none" />

                <!-- Cute Expressive Glowing Cyan Eyes -->
                <g id="leqsEyesGroup">
                    <ellipse cx="38" cy="48" rx="6" ry="8" fill="#38BDF8" filter="url(#ecoGlow)" />
                    <circle cx="39" cy="45" r="2.5" fill="#FFFFFF" />
                    <ellipse cx="62" cy="48" rx="6" ry="8" fill="#38BDF8" filter="url(#ecoGlow)" />
                    <circle cx="63" cy="45" r="2.5" fill="#FFFFFF" />
                </g>

                <!-- Cheerful Rosy Cheeks -->
                <circle cx="28" cy="56" r="3.5" fill="#FB7185" opacity="0.7" />
                <circle cx="72" cy="56" r="3.5" fill="#FB7185" opacity="0.7" />

                <!-- Animated Lip-sync Mouth -->
                <path id="leqsMouth" d="M44 58 Q50 64 56 58" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" fill="none" />

                <!-- Smart Agri Eco Sprout Antenna -->
                <path d="M50 14 L50 8" stroke="#34D399" stroke-width="3" stroke-linecap="round" />
                <!-- Twin Sprout Leaves on Antenna -->
                <path d="M50 8 C44 4, 38 6, 44 12 Z" fill="#34D399" filter="url(#ecoGlow)" />
                <path d="M50 8 C56 4, 62 6, 56 12 Z" fill="#34D399" filter="url(#ecoGlow)" />
                <circle cx="50" cy="8" r="2" fill="#FBBF24" />
            </svg>
        </div>

        <!-- Online Pulse Badge -->
        <span class="absolute top-0 right-0 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[9px] text-white font-bold shadow-md">
            <i class="fa-solid fa-sparkles text-[8px]"></i>
        </span>
    </button>
</div>

<!-- =========================================================================
     Chatbot Window Modal (High-End Glassmorphism, Tailored to Agri-IoT)
========================================================================= -->
<div id="leqsChatbotModal" class="fixed bottom-0 md:bottom-6 right-0 sm:right-4 md:right-6 w-full sm:w-[450px] max-w-full h-[90vh] sm:h-[650px] max-h-[96vh] bg-slate-900/95 backdrop-blur-2xl sm:rounded-3xl shadow-2xl border border-white/10 z-[70] flex flex-col overflow-hidden transform translate-y-full opacity-0 pointer-events-none transition-all duration-300">
    
    <!-- Header -->
    <div class="px-4 sm:px-5 py-3.5 bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-950 text-white flex items-center justify-between shrink-0 shadow-lg relative overflow-hidden border-b border-white/10">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-400/20 rounded-full blur-xl pointer-events-none"></div>

        <div class="flex items-center gap-2.5 sm:gap-3 relative z-10">
            <!-- Mini Animated Avatar -->
            <div class="relative w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-emerald-400 to-cyan-400 p-0.5 shadow-md shrink-0">
                <div class="w-full h-full rounded-[14px] bg-slate-950 flex items-center justify-center overflow-hidden">
                    <span class="text-lg sm:text-xl">🌱</span>
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-slate-950 rounded-full animate-ping"></span>
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <h3 id="leqsBotTitle" class="font-extrabold text-sm sm:text-base leading-tight text-white">น้อง LEQs AI</h3>
                    <span id="leqsDialectBadge" class="text-[9px] sm:text-[10px] bg-amber-400 text-slate-950 font-black px-1.5 py-0.2 rounded-full uppercase shadow-xs">สำเนียงจันท์ฮิ</span>
                </div>
                <div id="leqsBotSubtitle" class="text-[10px] sm:text-[11px] text-teal-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>RAG Agentic AI • Edge xAI : 4DAgriEnvi</span>
                </div>
            </div>
        </div>

        <!-- Top Right Control Buttons -->
        <div class="flex items-center gap-1.5 relative z-10">
            <!-- Language Switcher Pill -->
            <div class="flex items-center bg-black/40 backdrop-blur-md rounded-xl p-0.5 border border-white/10 text-[10px] font-bold">
                <button type="button" onclick="setLeqsLang('th')" id="langBtnTh" title="ภาษาไทยสำเนียงจันท์"
                    class="px-2 py-0.5 rounded-lg bg-emerald-500 text-white shadow-xs transition">ไทย</button>
                <button type="button" onclick="setLeqsLang('en')" id="langBtnEn" title="English AI Mode"
                    class="px-2 py-0.5 rounded-lg text-slate-400 hover:text-white transition">EN</button>
                <button type="button" onclick="setLeqsLang('zh')" id="langBtnZh" title="中文助手"
                    class="px-2 py-0.5 rounded-lg text-slate-400 hover:text-white transition">中文</button>
            </div>

            <!-- Test Voice Button -->
            <button type="button" onclick="testLeqsVoice()" title="ทดสอบฟังเสียงพูดสตูดิโอ"
                class="px-2 py-1 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-white flex items-center gap-1 text-[10px] font-bold transition border border-emerald-400/30">
                <i class="fa-solid fa-play text-[9px]"></i>
                <span class="hidden sm:inline">ทดสอบเสียง</span>
            </button>

            <!-- Sound Toggle -->
            <button type="button" id="leqsVoiceToggleBtn" onclick="toggleLeqsVoice()" title="เปิด/ปิดเสียงพูดน้อง LEQs"
                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-teal-200 hover:text-white flex items-center justify-center text-xs transition">
                <i id="leqsVoiceIcon" class="fa-solid fa-volume-high"></i>
            </button>
            
            <!-- Clear History -->
            <button type="button" onclick="clearLeqsChat()" title="ล้างบทสนทนา"
                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-400 hover:text-white flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-rotate-right"></i>
            </button>

            <!-- Close Modal -->
            <button type="button" onclick="toggleLeqsChatbot()" aria-label="ปิดหน้าต่างแชท"
                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-rose-500 text-slate-300 hover:text-white flex items-center justify-center text-sm transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Active Voice Indicator Bar (When Speaking) -->
    <div id="leqsSpeakingIndicator" class="hidden bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-slate-950 px-4 py-1.5 text-[11px] font-bold flex items-center justify-between shrink-0 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="flex items-end gap-0.5 h-3">
                <span class="w-1 bg-slate-950 rounded-full animate-bounce" style="height: 60%; animation-delay: 0.1s;"></span>
                <span class="w-1 bg-slate-950 rounded-full animate-bounce" style="height: 100%; animation-delay: 0.2s;"></span>
                <span class="w-1 bg-slate-950 rounded-full animate-bounce" style="height: 40%; animation-delay: 0.3s;"></span>
                <span class="w-1 bg-slate-950 rounded-full animate-bounce" style="height: 80%; animation-delay: 0.15s;"></span>
            </div>
            <span id="leqsSpeakingText">🎙️ เสียงมนุษย์สตูดิโอ (Studio HD Voice)...</span>
        </div>
        <button type="button" onclick="stopLeqsSpeech()" class="text-xs hover:underline text-slate-900 font-extrabold flex items-center gap-1 bg-white/40 px-2 py-0.5 rounded-lg">
            <i class="fa-solid fa-stop text-[10px]"></i>
            <span>หยุดเสียง</span>
        </button>
    </div>

    <!-- Active Voice Input Indicator Bar (When Mic Listening) -->
    <div id="leqsListeningIndicator" class="hidden bg-gradient-to-r from-rose-600 via-pink-600 to-rose-700 text-white px-4 py-2 text-[11px] font-bold flex items-center justify-between shrink-0 shadow-md">
        <div class="flex items-center gap-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-200 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
            <span id="leqsListeningText">🎙️ กำลังฟังเสียงพูด... พูดคำถามได้เลยเนี่ยฮิ (เช่น "7 โมดูลมีอะไรบ้าง")</span>
        </div>
        <button type="button" onclick="stopVoiceInput()" class="text-xs hover:underline text-white font-extrabold flex items-center gap-1 bg-black/30 hover:bg-black/50 px-2 py-0.5 rounded-lg transition">
            <i class="fa-solid fa-xmark text-[10px]"></i>
            <span>ยกเลิก</span>
        </button>
    </div>

    <!-- Live RAG Data Source Sync Status Pill -->
    <div id="leqsRagStatusPill" class="bg-emerald-950/60 border-b border-emerald-500/20 px-3.5 py-1.5 text-[10px] text-emerald-300 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-1.5 truncate">
            <i class="fa-solid fa-bolt text-emerald-400"></i>
            <span id="leqsRagSyncText">RAG Live: เชื่อมฐานข้อมูลสดระบบ Edge xAI สำเร็จ ⚡</span>
        </div>
        <span class="text-[9px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-1.5 py-0.2 rounded font-bold uppercase">Active</span>
    </div>

    <!-- Chat Messages Scroll Container -->
    <div id="leqsChatMessages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs sm:text-sm bg-slate-950/70">
        <!-- Messages dynamically injected -->
    </div>

    <!-- Quick Prompt Suggestions Chips -->
    <div id="leqsQuickChips" class="px-3 py-2 bg-slate-900 border-t border-white/5 flex items-center gap-1.5 overflow-x-auto text-[11px] shrink-0 no-scrollbar">
        <!-- Chips dynamically injected based on language -->
    </div>

    <!-- Chat Input Area -->
    <div class="p-3 bg-slate-900 border-t border-white/10 shrink-0">
        <form id="leqsChatForm" onsubmit="handleLeqsChatSubmit(event)" class="flex items-center gap-2">
            <!-- Voice Input Mic Button -->
            <button type="button" id="leqsMicBtn" onclick="toggleVoiceInput()" title="กดเพื่อพูดด้วยเสียง"
                class="w-10 h-10 rounded-2xl bg-slate-800 hover:bg-emerald-500/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center shrink-0 transition border border-white/10">
                <i id="leqsMicIcon" class="fa-solid fa-microphone text-sm"></i>
            </button>

            <!-- Text Input Field -->
            <input type="text" id="leqsChatInput" placeholder="พิมพ์ถามน้อง LEQs สำเนียงจันท์ได้เลยเนี่ยฮิ..."
                class="flex-1 bg-slate-800/90 hover:bg-slate-800 focus:bg-slate-950 text-white text-xs sm:text-sm px-3.5 py-2.5 rounded-2xl border border-transparent focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20 focus:outline-none transition placeholder-slate-500">

            <!-- Send Button -->
            <button type="submit" id="leqsSendBtn" aria-label="ส่งข้อความ"
                class="w-10 h-10 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-slate-950 font-bold flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20 transition active:scale-95">
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>

</div>

<!-- =========================================================================
     น้อง LEQs AI: JAVASCRIPT AGENTIC RAG & MULTILINGUAL VOICE ENGINE
========================================================================= -->
<script>
// State Management
let currentLeqsLang = 'th'; // 'th' | 'en' | 'zh'
let isLeqsChatOpen = false;
let isLeqsVoiceEnabled = localStorage.getItem('leqs_bot_voice_enabled') !== 'false';
let isLeqsListening = false;
let leqsSpeechSynth = window.speechSynthesis;
let speechRecognition = null;

// RAG In-Memory Live Knowledge Store
const RAG_KNOWLEDGE_STORE = {
    project: {
        title: 'Edge xAI : 4DAgriEnvi',
        full_title: 'โครงการฝึกอบรมเชิงปฏิบัติการปัญญาประดิษฐ์ฝังตัวและเอดจ์ไอโอทีเพื่อการเกษตรและสิ่งแวดล้อม 4 มิติ',
        institution: 'คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี',
        coordinator: 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา',
        phone: '093-742-2654',
        email: 'chewa.t@rbru.ac.th',
        date_range: 'ธันวาคม 2569 - มกราคม 2570',
        venue: 'ศูนย์วิทยาศาสตร์และเทคโนโลยี / อาคาร 35 มรภ.รำไพพรรณี',
        line_oa: '@888qrcgj (Edge-xAI)'
    },
    liveKpi: {
        total_registered: 12,
        target_participants: 40,
        completion_percent: 30.0,
        satisfaction_score: 4.85,
        evaluations_count: 5
    },
    participants: [],
    announcements: [],
    telemetry_latest: null,
    modules: [
        { id: 1, name: 'Module 1: Fundamental IoT & Microcontroller', desc: 'สถาปัตยกรรม ESP32-S3 Dual-Core, GPIO, ADC, PWM และระบบการเชื่อมต่อ Wi-Fi / BLE' },
        { id: 2, name: 'Module 2: Agricultural Sensor Hub & Industrial RS485', desc: 'การเชื่อมต่อเซ็นเซอร์วัดดิน NPK 7-in-1, pH, ความชื้น, อุณหภูมิ และ Modbus RTU Protocol' },
        { id: 3, name: 'Module 3: Edge AI & Machine Learning on Embedded', desc: 'โมเดลทำนายการดูดซับธาตุอาหารและประเมินสภาวะแวดล้อมด้วย Edge TinyML' },
        { id: 4, name: 'Module 4: 4D Agri-Environmental Data Modeling', desc: 'การจำลองมิติสิ่งแวดล้อม 4 มิติ (อุณหภูมิ, ความชื้น, แสง, สารอาหารดิน)' },
        { id: 5, name: 'Module 5: Smart Actuators & Relay Automation', desc: 'ระบบควบคุมปั๊มน้ำ, วาล์วไฟฟ้า, พัดลมระบายอากาศ และไฟช่วยสังเคราะห์แสง' },
        { id: 6, name: 'Module 6: Cloud Telemetry & Google Sheets Real-time', desc: 'การสตรีมข้อมูลขึ้นคลาวด์, แจ้งเตือนผ่าน LINE Messaging API และ Google Sheets Sync' },
        { id: 7, name: 'Module 7: Capstone AIoT Prototype & Pitching', desc: 'การบูรณาการโครงงานเกษตรอัจฉริยะ 4 แทร็ก และการนำเสนอผลงานระดับมืออาชีพ' }
    ],
    tracks: {
        'Track A': 'Smart Agriculture & Sensor Hub (สถานีตรวจวัดและควบคุมโรงเรือนเกษตรอัจฉริยะ)',
        'Track B': 'Microclimate & Bio-Environment (ระบบเฝ้าระวังจุลภูมิอากาศและชีวสิ่งแวดล้อมพืช)',
        'Track C': 'Soil & Fertilizer AI (ระบบ AI วิเคราะห์สุขภาพดิน ธาตุอาหาร NPK และคำนวณปุ๋ยแม่นยำ)',
        'Track D': 'Smart Vision & Edge AI (ระบบคอมพิวเตอร์วิชันและ AI จำแนกโรคพืชบนกล้อง ESP32-CAM)'
    }
};

// =========================================================================
// 1. DIGITAL CHIME SYNTHESIZER (WEB AUDIO API)
// =========================================================================
let leqsAudioCtx = null;
function getLeqsAudioContext() {
    try {
        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (!AudioContextClass) return null;
        if (!leqsAudioCtx || leqsAudioCtx.state === 'closed') {
            leqsAudioCtx = new AudioContextClass();
        }
        if (leqsAudioCtx.state === 'suspended') {
            leqsAudioCtx.resume().catch(() => {});
        }
        return leqsAudioCtx;
    } catch (e) {
        return null;
    }
}

function playDigitalChime(type = 'start') {
    try {
        const ctx = getLeqsAudioContext();
        if (!ctx) return;
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        const now = ctx.currentTime;
        if (type === 'start') {
            // Gentle warm bell chime: F5 (698 Hz) -> A5 (880 Hz)
            osc.type = 'sine';
            osc.frequency.setValueAtTime(698.46, now);
            osc.frequency.exponentialRampToValueAtTime(880, now + 0.12);
            gain.gain.setValueAtTime(0.04, now);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.28);
            osc.start(now);
            osc.stop(now + 0.30);
        } else if (type === 'end') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, now);
            osc.frequency.exponentialRampToValueAtTime(698.46, now + 0.10);
            gain.gain.setValueAtTime(0.03, now);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.22);
            osc.start(now);
            osc.stop(now + 0.24);
        } else if (type === 'switch_lang') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(659.25, now);
            osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.08);
            gain.gain.setValueAtTime(0.04, now);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.18);
            osc.start(now);
            osc.stop(now + 0.20);
        }
    } catch(e) {}
}

// =========================================================================
// 2. LIVE RAG KNOWLEDGE INGESTION (FETCH BACKEND SQLITE / JSON)
// =========================================================================
async function initRAGKnowledgeBase() {
    try {
        const prefix = '<?= $bot_base_prefix ?>';
        const res = await fetch(`${prefix}api/api.php?action=rag_bundle`);
        if (res.ok) {
            const data = await res.json();
            if (data.success) {
                if (data.project) RAG_KNOWLEDGE_STORE.project = { ...RAG_KNOWLEDGE_STORE.project, ...data.project };
                if (data.kpi) RAG_KNOWLEDGE_STORE.liveKpi = { ...RAG_KNOWLEDGE_STORE.liveKpi, ...data.kpi };
                if (Array.isArray(data.participants)) RAG_KNOWLEDGE_STORE.participants = data.participants;
                if (Array.isArray(data.announcements)) RAG_KNOWLEDGE_STORE.announcements = data.announcements;
                if (data.telemetry_latest) RAG_KNOWLEDGE_STORE.telemetry_latest = data.telemetry_latest;
                if (Array.isArray(data.modules)) RAG_KNOWLEDGE_STORE.modules = data.modules;
                if (data.tracks) RAG_KNOWLEDGE_STORE.tracks = data.tracks;
            }
        }
        
        const pill = document.getElementById('leqsRagSyncText');
        if (pill) {
            const regCount = (RAG_KNOWLEDGE_STORE.participants || []).length;
            const target = RAG_KNOWLEDGE_STORE.liveKpi.target_participants || 40;
            pill.innerHTML = `RAG Live: ฐานข้อมูลสด (${regCount}/${target} ผู้สมัคร • 7 โมดูล • 4 แทร็ก) ⚡`;
        }
    } catch (err) {
        console.warn('RAG Ingestion fallback:', err);
    }
}

// =========================================================================
// 3. MULTILINGUAL UI DICTIONARY & QUICK CHIPS
// =========================================================================
const I18N_DATA = {
    th: {
        title: 'น้อง LEQs AI',
        dialect: 'สำเนียงจันท์ฮิ',
        subtitle: 'RAG Agentic AI • Edge xAI : 4DAgriEnvi',
        bubble: 'มีอะไรให้ <strong>น้อง LEQs</strong> ช่วยเนี่ยฮิ? 🌱🌤️',
        placeholder: 'พิมพ์ถามน้อง LEQs สำเนียงจันท์ได้เลยเนี่ยฮิ...',
        speaking: 'น้อง LEQs กำลังส่งเสียงโทนสตูดิโอสำเนียงจันท์ฮิ...',
        welcomeTitle: 'สวัสดีฮิอาจารย์ คุณครู และเพื่อนๆ ทุกท่าน! 🙏🌱',
        welcomeMsg: `น้องชื่อ <strong>"น้อง LEQs AI"</strong> มาสคอต AI อัจฉริยะประจำโครงการ <strong>Edge xAI : 4DAgriEnvi</strong> 
                    คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรีฮิ! 
                    <br><br>
                    น้องเชื่อมต่อกับฐานข้อมูลระบบสด 24 ชม. สามารถตอบคำถามเรื่อง 
                    <strong>7 โมดูลการอบรม, 4 แทร็กโครงงาน, สถิติผู้ลงทะเบียนล่าสุด, เซ็นเซอร์ RS485 Modbus, และระบบแจ้งเตือน LINE</strong> ได้ทันทีเลยเนี่ยฮิ! 
                    อยากทราบเรื่องอะไรกดเลือกคำถามด้านล่าง หรือพิมพ์ถามน้องได้เลยจ้าฮิ 🌤️`,
        chips: [
            { text: '📝 สมัครเข้าร่วมยังไงฮิ?', q: 'สมัครเข้าร่วมโครงการ Edge xAI ทำอย่างไรและมีเกณฑ์อะไรบ้างฮิ?' },
            { text: '👥 สถิติผู้ลงทะเบียนล่าสุด', q: 'ตอนนี้มีผู้ลงทะเบียนเข้าร่วมอบรมทั้งหมดกี่คนแล้วและมีใครบ้าง?' },
            { text: '📚 7 โมดูลการอบรม', q: 'เนื้อหาการอบรมทั้ง 7 โมดูลมีอะไรบ้าง?' },
            { text: '🎯 4 แทร็กโครงงาน', q: 'มีแทร็กโครงงาน Track A B C D อะไรบ้างและเน้นเรื่องอะไร?' },
            { text: '🔌 อุปกรณ์ & เซ็นเซอร์ดิน', q: 'มีฮาร์ดแวร์ ESP32-S3 และเซ็นเซอร์ RS485 Modbus อะไรใช้ในการอบรมบ้าง?' },
            { text: '📱 แจ้งเตือน LINE OA', q: 'ระบบแจ้งเตือนผ่าน LINE Official Account ทำงานอย่างไร?' },
            { text: '📜 เกียรติบัตรรับรอง', q: 'ผู้เข้าร่วมอบรมจะได้รับเกียรติบัตรรับรองจากใครและมีเงื่อนไขอย่างไร?' },
            { text: '👨‍🏫 ติดต่อ อ.ชีวะ', q: 'ขอข้อมูลช่องทางการติดต่อ ผศ.ดร.ชีวะ ทัศนา และทีมงาน มรภ.รำไพพรรณี' }
        ]
    },
    en: {
        title: 'LEQs AI Assistant',
        dialect: 'AI Mascot',
        subtitle: 'RAG Agentic AI • Edge xAI : 4DAgriEnvi',
        bubble: 'Need help with <strong>Edge xAI</strong>? 🌱',
        placeholder: 'Ask LEQs AI anything about the workshop...',
        speaking: 'LEQs AI is speaking with natural studio voice...',
        welcomeTitle: 'Welcome to Edge xAI : 4DAgriEnvi! 👋🌱',
        welcomeMsg: `I am <strong>LEQs AI</strong>, your autonomous agentic companion from the Faculty of Science & Technology, Rambhai Barni Rajabhat University (RBRU), Chanthaburi!
                    <br><br>
                    Powered by real-time RAG ingestion, I provide live answers regarding the <strong>7 Learning Modules, 4 Capstone Tracks, Live Participant Statistics, Industrial RS485 Modbus Sensors, and real-time LINE Messaging integration</strong>!`,
        chips: [
            { text: '📝 How to Register?', q: 'How can I register for Edge xAI : 4DAgriEnvi workshop?' },
            { text: '👥 Participant Stats', q: 'Show the latest registered participants and quota progress' },
            { text: '📚 7 Core Modules', q: 'What are the 7 core training modules covered in the workshop?' },
            { text: '🎯 4 Capstone Tracks', q: 'Explain the 4 project tracks (Track A, B, C, D)' },
            { text: '🔌 RS485 & Sensors', q: 'What sensors and microcontrollers are utilized (ESP32-S3, RS485 Modbus)?' },
            { text: '📱 LINE Notifications', q: 'How does the LINE Messaging API real-time alert work?' },
            { text: '📜 Certificates', q: 'What certificate will be awarded upon course completion?' },
            { text: '👨‍🏫 Contact Coordinator', q: 'How can I contact Asst. Prof. Dr. Chewa Thassana and the RBRU team?' }
        ]
    },
    zh: {
        title: 'LEQs 智能助手',
        dialect: '数码生态助手',
        subtitle: '实时 RAG 智能 • Edge xAI : 4DAgriEnvi',
        bubble: '有什么可以帮您？<strong>LEQs 智能助手</strong>在线 🌱',
        placeholder: '向 LEQs 智能助手咨询培训课程详情...',
        speaking: 'LEQs 助手正在以高清自然语音播报...',
        welcomeTitle: '欢迎来到 Edge xAI 智慧农业培训营！👋🌱',
        welcomeMsg: `我是 <strong>LEQs 智能助手</strong>，来自泰国兰帕潘尼皇家大学（RBRU）科技学院！
                    <br><br>
                    我已接入本站 SQLite/JSON 实时数据库，可为您即时解答：<strong>7 大核心教学模块、4 个综合实践课题、最新报名人员统计、RS485 Modbus 工业土壤传感器及 LINE 实时通知机制</strong>！`,
        chips: [
            { text: '📝 如何报名？', q: '如何报名参加 Edge xAI 培训以及入选资格是什么？' },
            { text: '👥 查看报名名单', q: '目前有多少学员报名？显示最新学员统计' },
            { text: '📚 7 个教学模块', q: '介绍培训营的 7 个核心教学模块' },
            { text: '🎯 4 大课题方向', q: '介绍 4 个实践课题 Track A B C D' },
            { text: '🔌 硬件与传感器', q: '培训中使用了哪些硬件与 RS485 传感器？' },
            { text: '📱 LINE 实时提醒', q: 'LINE 消息推送系统是如何工作的？' },
            { text: '📜 结业证书核验', q: '学员将获得何种官方培训结业证书？' },
            { text: '👨‍🏫 联系负责人', q: '如何联系 Chewa 博士及大学科技学院团队？' }
        ]
    }
};

function setLeqsLang(lang) {
    if (!I18N_DATA[lang]) return;
    currentLeqsLang = lang;
    playDigitalChime('switch_lang');

    ['th', 'en', 'zh'].forEach(l => {
        const btn = document.getElementById(`langBtn${l.charAt(0).toUpperCase() + l.slice(1)}`);
        if (btn) {
            btn.className = (l === lang) 
                ? 'px-2 py-0.5 rounded-lg bg-emerald-500 text-white shadow-xs transition' 
                : 'px-2 py-0.5 rounded-lg text-slate-400 hover:text-white transition';
        }
    });

    const dict = I18N_DATA[lang];
    document.getElementById('leqsBotTitle').textContent = dict.title;
    document.getElementById('leqsDialectBadge').textContent = dict.dialect;
    document.getElementById('leqsBotSubtitle').querySelector('span:last-child').textContent = dict.subtitle;
    document.getElementById('leqsBubbleText').innerHTML = dict.bubble;
    document.getElementById('leqsChatInput').placeholder = dict.placeholder;
    document.getElementById('leqsSpeakingText').textContent = dict.speaking;

    renderQuickChips();
    clearLeqsChat(true);
}

function renderQuickChips() {
    const chipsContainer = document.getElementById('leqsQuickChips');
    if (!chipsContainer) return;
    const chips = I18N_DATA[currentLeqsLang].chips;
    chipsContainer.innerHTML = chips.map(c => `
        <button type="button" onclick="sendQuickPrompt('${c.q}')" 
            class="whitespace-nowrap px-3 py-1.5 rounded-full bg-slate-800 text-emerald-300 hover:bg-emerald-500/20 border border-emerald-500/30 transition font-medium text-[11px] active:scale-95">
            ${c.text}
        </button>
    `).join('');
}

// =========================================================================
// 4. STUDIO HUMAN VOICE TTS & BROWSER SYNTHESIS
// =========================================================================
let isAudioUnlocked = false;
const leqsGlobalAudio = new Audio();
leqsGlobalAudio.preload = 'auto';

let leqsSpeechKeepAliveTimer = null;
let leqsActiveUtterance = null;
let leqsAvailableVoices = [];

function loadLeqsVoices() {
    if (!('speechSynthesis' in window)) return;
    try {
        leqsAvailableVoices = window.speechSynthesis.getVoices() || [];
    } catch(e) {}
}

if ('speechSynthesis' in window) {
    loadLeqsVoices();
    window.speechSynthesis.onvoiceschanged = loadLeqsVoices;
}

function unlockAudio() {
    try {
        const ctx = getLeqsAudioContext();
        if (ctx && ctx.state === 'suspended') {
            ctx.resume().catch(() => {});
        }
        if ('speechSynthesis' in window && window.speechSynthesis.paused) {
            window.speechSynthesis.resume();
        }
        if (!isAudioUnlocked) {
            leqsGlobalAudio.src = "data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEARKwAAIhYAQACABAAAABkYXRhAgAAAAEA";
            const p = leqsGlobalAudio.play();
            if (p !== undefined) {
                p.then(() => {
                    leqsGlobalAudio.pause();
                    leqsGlobalAudio.currentTime = 0;
                }).catch(() => {});
            }
            isAudioUnlocked = true;
        }
    } catch(e) {}
}

['click', 'touchstart'].forEach(evt => {
    document.addEventListener(evt, unlockAudio, { passive: true });
});

function testLeqsVoice() {
    unlockAudio();
    isLeqsVoiceEnabled = true;
    localStorage.setItem('leqs_bot_voice_enabled', 'true');
    updateVoiceToggleBtn();

    if (currentLeqsLang === 'th') {
        speakWithDigitalTone("สวัสดีฮิ! ระบบเสียงพูดสตูดิโอสำเนียงจันท์ของน้อง LEQs พร้อมทำงานเต็มรูปแบบแล้วเนี่ยฮิ!", null, true);
    } else if (currentLeqsLang === 'en') {
        speakWithDigitalTone("Hello! Natural studio voice synthesis is fully active and ready to assist you!", null, true);
    } else {
        speakWithDigitalTone("您好！自然高清智能语音引擎现已就绪！", null, true);
    }
}

function toggleLeqsVoice() {
    unlockAudio();
    isLeqsVoiceEnabled = !isLeqsVoiceEnabled;
    localStorage.setItem('leqs_bot_voice_enabled', isLeqsVoiceEnabled);
    updateVoiceToggleBtn();

    if (isLeqsVoiceEnabled) {
        if (currentLeqsLang === 'th') {
            speakWithDigitalTone("เปิดเสียงพูดสำเนียงจันท์เรียบร้อยแล้วเนี่ยฮิ!", null, true);
        } else if (currentLeqsLang === 'en') {
            speakWithDigitalTone("Natural studio voice is now enabled!", null, true);
        } else {
            speakWithDigitalTone("已为您开启语音播报功能！", null, true);
        }
    } else {
        stopLeqsSpeech();
    }
}

function updateVoiceToggleBtn() {
    const icon = document.getElementById('leqsVoiceIcon');
    const btn = document.getElementById('leqsVoiceToggleBtn');
    if (!icon || !btn) return;
    if (isLeqsVoiceEnabled) {
        icon.className = 'fa-solid fa-volume-high text-emerald-400';
        btn.classList.remove('opacity-60');
        btn.title = 'ปิดเสียงพูด (Mute)';
    } else {
        icon.className = 'fa-solid fa-volume-xmark text-rose-400';
        btn.classList.add('opacity-60');
        btn.title = 'เปิดเสียงพูด (Unmute)';
    }
}

function stopLeqsSpeech() {
    try {
        if (leqsGlobalAudio) {
            leqsGlobalAudio.pause();
            leqsGlobalAudio.currentTime = 0;
            leqsGlobalAudio.onended = null;
            leqsGlobalAudio.onerror = null;
        }
    } catch(e) {}

    if (leqsSpeechKeepAliveTimer) {
        clearInterval(leqsSpeechKeepAliveTimer);
        leqsSpeechKeepAliveTimer = null;
    }

    if ('speechSynthesis' in window) {
        try {
            window.speechSynthesis.cancel();
            window.speechSynthesis.resume();
        } catch(e) {}
    }
    leqsActiveUtterance = null;

    const indicator = document.getElementById('leqsSpeakingIndicator');
    if (indicator) indicator.classList.add('hidden');
    animateMouth(false);
}

function animateMouth(isTalking) {
    const mouth = document.getElementById('leqsMouth');
    if (!mouth) return;
    if (isTalking) {
        mouth.setAttribute('d', 'M40 60 Q50 72 60 60');
    } else {
        mouth.setAttribute('d', 'M44 58 Q50 64 56 58');
    }
}

async function speakWithDigitalTone(text, forceLang = null, forcePlay = false) {
    if (!isLeqsVoiceEnabled && !forcePlay) return;
    stopLeqsSpeech();
    unlockAudio();
    playDigitalChime('start');

    const lang = forceLang || currentLeqsLang;
    let cleanText = String(text || '').replace(/<[^>]*>/g, '').replace(/[*_#`~]/g, '').trim();
    if (!cleanText) return;

    const indicator = document.getElementById('leqsSpeakingIndicator');
    const label = document.getElementById('leqsSpeakingText');

    // 1. Try Fetching Studio Natural Audio from api/tts.php
    try {
        const prefix = '<?= $bot_base_prefix ?>';
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 3500);

        const ttsResp = await fetch(`${prefix}api/tts.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            signal: controller.signal,
            body: JSON.stringify({
                text: cleanText,
                lang: lang === 'th' ? 'th-TH' : (lang === 'zh' ? 'cmn-CN' : 'en-US'),
                voice: lang === 'th' ? 'th-TH-Neural2-C' : (lang === 'zh' ? 'cmn-CN-Wavenet-A' : 'en-US-Neural2-F'),
                rate: 1.0
            })
        });
        clearTimeout(timeoutId);

        if (ttsResp.ok) {
            const ttsData = await ttsResp.json();
            if (ttsData.success && (ttsData.audio_url || ttsData.audio_data)) {
                if (indicator) {
                    if (label) label.textContent = '🎙️ เสียงมนุษย์สตูดิโอ (Studio HD Voice)...';
                    indicator.classList.remove('hidden');
                }
                animateMouth(true);

                leqsGlobalAudio.src = ttsData.audio_data || `${prefix}${ttsData.audio_url}`;
                leqsGlobalAudio.playbackRate = 1.0;
                leqsGlobalAudio.preload = 'auto';

                leqsGlobalAudio.onended = () => {
                    if (indicator) indicator.classList.add('hidden');
                    animateMouth(false);
                    playDigitalChime('end');
                };

                leqsGlobalAudio.onerror = () => {
                    fallbackToBrowserSpeech(cleanText, lang);
                };

                const p = leqsGlobalAudio.play();
                if (p !== undefined) {
                    p.catch(() => {
                        fallbackToBrowserSpeech(cleanText, lang);
                    });
                }
                return;
            }
        }
    } catch (err) {
        // Fallback silently
    }

    // 2. Fallback to Browser Speech Synthesis
    fallbackToBrowserSpeech(cleanText, lang);
}

function fallbackToBrowserSpeech(cleanText, lang) {
    if (!('speechSynthesis' in window)) return;
    const synth = window.speechSynthesis;
    if (synth.paused) {
        try { synth.resume(); } catch(e) {}
    }
    try { synth.cancel(); } catch(e) {}

    setTimeout(() => {
        const indicator = document.getElementById('leqsSpeakingIndicator');
        const label = document.getElementById('leqsSpeakingText');

        let textToSpeak = cleanText;
        if (textToSpeak.length > 250) {
            const sentenceMatch = textToSpeak.match(/[^.!?。\n]+[.!?。\n]?/g);
            if (sentenceMatch && sentenceMatch.length > 0) {
                textToSpeak = sentenceMatch.slice(0, 3).join(' ');
            } else {
                textToSpeak = textToSpeak.substring(0, 240) + '...';
            }
        }

        const utterance = new SpeechSynthesisUtterance(textToSpeak);
        leqsActiveUtterance = utterance;

        if (lang === 'th') {
            utterance.lang = 'th-TH';
            utterance.pitch = 1.0;
            utterance.rate = 0.96;
        } else if (lang === 'zh') {
            utterance.lang = 'zh-CN';
            utterance.pitch = 1.0;
            utterance.rate = 0.98;
        } else {
            utterance.lang = 'en-US';
            utterance.pitch = 1.0;
            utterance.rate = 0.98;
        }

        let voices = synth.getVoices();
        if (!voices || voices.length === 0) voices = leqsAvailableVoices;
        if (voices && voices.length > 0) {
            const langPrefix = utterance.lang.slice(0, 2).toLowerCase();
            const matchingVoices = voices.filter(v => v.lang.toLowerCase().replace('_', '-').startsWith(langPrefix));
            let bestVoice = matchingVoices.find(v => 
                v.name.includes('Natural') || 
                v.name.includes('Neural') || 
                v.name.includes('Premium') || 
                v.name.includes('Kanya') ||
                v.name.includes('Google')
            );
            if (!bestVoice && matchingVoices.length > 0) bestVoice = matchingVoices[0];
            if (bestVoice) utterance.voice = bestVoice;
        }

        utterance.onstart = () => {
            if (indicator) {
                if (label) label.textContent = '🗣️ เสียงสังเคราะห์สำเนียงมนุษย์ (Natural Voice)...';
                indicator.classList.remove('hidden');
            }
            animateMouth(true);

            if (leqsSpeechKeepAliveTimer) clearInterval(leqsSpeechKeepAliveTimer);
            leqsSpeechKeepAliveTimer = setInterval(() => {
                if (synth.speaking) {
                    if (!synth.paused) {
                        synth.pause();
                        synth.resume();
                    }
                } else {
                    clearInterval(leqsSpeechKeepAliveTimer);
                    leqsSpeechKeepAliveTimer = null;
                }
            }, 8000);
        };

        utterance.onend = () => {
            if (indicator) indicator.classList.add('hidden');
            animateMouth(false);
            playDigitalChime('end');
            leqsActiveUtterance = null;
            if (leqsSpeechKeepAliveTimer) {
                clearInterval(leqsSpeechKeepAliveTimer);
                leqsSpeechKeepAliveTimer = null;
            }
        };

        utterance.onerror = () => {
            if (indicator) indicator.classList.add('hidden');
            animateMouth(false);
            leqsActiveUtterance = null;
            if (leqsSpeechKeepAliveTimer) {
                clearInterval(leqsSpeechKeepAliveTimer);
                leqsSpeechKeepAliveTimer = null;
            }
        };

        synth.resume();
        synth.speak(utterance);
    }, 60);
}

// =========================================================================
// 5. MODAL TOGGLE & DISPLAY
// =========================================================================
function toggleLeqsChatbot() {
    unlockAudio();
    const modal = document.getElementById('leqsChatbotModal');
    const bubble = document.getElementById('leqsBotSpeechBubble');
    isLeqsChatOpen = !isLeqsChatOpen;

    if (isLeqsChatOpen) {
        modal.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
        modal.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
        if (bubble) bubble.classList.add('hidden');
        playDigitalChime('start');
        document.getElementById('leqsChatInput').focus();

        if (!sessionStorage.getItem('leqs_chat_welcomed')) {
            if (currentLeqsLang === 'th') {
                speakWithDigitalTone("สวัสดีฮิ! น้องชื่อน้อง LEQs AI ยินดีต้อนรับสู่โครงการ Edge xAI มรภ.รำไพพรรณี จันทบุรีฮิ! มีอะไรให้ช่วยถามน้องได้เลยเนี่ยฮิ!");
            }
            sessionStorage.setItem('leqs_chat_welcomed', 'true');
        }
    } else {
        modal.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
        modal.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
        playDigitalChime('end');
        stopLeqsSpeech();
    }
}

// =========================================================================
// 6. RAG RETRIEVAL & INTENT PROCESSING ENGINE
// =========================================================================
function processRAGQuery(rawQuery, lang) {
    const q = rawQuery.toLowerCase().trim();
    const kpi = RAG_KNOWLEDGE_STORE.liveKpi;
    const proj = RAG_KNOWLEDGE_STORE.project;
    const participants = RAG_KNOWLEDGE_STORE.participants || [];
    const modules = RAG_KNOWLEDGE_STORE.modules || [];
    const tracks = RAG_KNOWLEDGE_STORE.tracks || {};
    const prefix = '<?= $bot_base_prefix ?>';

    // INTENT A: REGISTRATION & ELIGIBILITY (สมัครเข้าร่วม / วิธีการสมัคร / คุณสมบัติ)
    if (q.includes('สมัคร') || q.includes('ลงทะเบียน') || q.includes('register') || q.includes('apply') || q.includes('报名') || q.includes('เกณฑ์')) {
        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-emerald-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-id-card"></i> การสมัครเข้าร่วมโครงการ Edge xAI : 4DAgriEnvi 🌱
                        </div>
                        <p class="text-slate-300 leading-relaxed">
                            โครงการเปิดรับสมัคร <strong>ครู อาจารย์ บุคลากรทางการศึกษา นักเรียน/นักศึกษา และเกษตรกร/ผู้สนใจทั่วไป</strong> 
                            ในเขตภาคตะวันออกและทั่วประเทศ เพื่อฝึกอบรมเชิงปฏิบัติการพัฒนาโครงงานจริง
                        </p>
                        <div class="bg-slate-900/80 p-3 rounded-xl border border-emerald-500/30 space-y-1.5 font-mono text-[11px]">
                            <div class="text-emerald-300 font-bold"><i class="fa-solid fa-users mr-1"></i> ยอดผู้สมัครล่าสุด: ${participants.length} / ${kpi.target_participants} ท่าน</div>
                            <div class="text-cyan-300"><i class="fa-solid fa-calendar-check mr-1"></i> กำหนดการ: ${proj.date_range}</div>
                            <div class="text-amber-300"><i class="fa-solid fa-location-dot mr-1"></i> สถานที่: ${proj.venue}</div>
                            <div class="text-slate-300"><i class="fa-solid fa-gift mr-1"></i> ไม่มีค่าใช้จ่ายในการลงทะเบียน (ฟรีตลอดหลักสูตร)</div>
                        </div>
                        <p class="text-slate-400 text-[11px]">
                            กดปุ่มด้านล่างเพื่อเปิดแบบฟอร์มลงทะเบียนออนไลน์ได้ทันทีเนี่ยฮิ! 📝
                        </p>
                    </div>`,
            speechText: `การลงทะเบียนเปิดรับครู นักเรียน และผู้สนใจทั่วไปฟรีตลอดหลักสูตร ยอดผู้สมัครล่าสุดมี ${participants.length} ท่านแล้ว กดลงทะเบียนได้เลยเนี่ยฮิ!`,
            actions: [
                { label: 'กรอกแบบฟอร์มลงทะเบียน', icon: 'fa-solid fa-user-plus', url: `${prefix}pages/register.php` },
                { label: 'ดูรายชื่อผู้สมัครล่าสุด', icon: 'fa-solid fa-users', url: `${prefix}pages/student_list.php` }
            ]
        };
    }

    // INTENT B: REGISTERED PARTICIPANTS (รายชื่อผู้ลงทะเบียน / ใครสมัครแล้วบ้าง / มีกี่คน)
    if (q.includes('รายชื่อ') || q.includes('ผู้ลงทะเบียน') || q.includes('ผู้สมัคร') || q.includes('กี่คน') || q.includes('participants') || q.includes('student') || q.includes('名单') || q.includes('ใครบ้าง')) {
        const recent = participants.slice(0, 5);
        let listHtml = '';
        if (recent.length > 0) {
            listHtml = recent.map((p, idx) => `
                <div class="p-2 bg-slate-900/90 rounded-lg border border-white/5 flex items-center justify-between text-[11px]">
                    <span class="text-white font-bold">${idx + 1}. ${escapeHtml(p.prefix || '')} ${escapeHtml(p.fullname || '')}</span>
                    <span class="text-emerald-400 font-mono text-[10px]">${escapeHtml(p.organization || '-')}</span>
                </div>
            `).join('');
        }

        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-cyan-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-users"></i> สถิติและรายชื่อผู้ลงทะเบียนจริง (Live Database) 📊
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center my-2">
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-emerald-500/30">
                                <div class="text-[10px] text-slate-400">ผู้สมัครทั้งหมด</div>
                                <div class="text-lg font-black text-emerald-400 font-mono">${participants.length} ท่าน</div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-900 border border-cyan-500/30">
                                <div class="text-[10px] text-slate-400">เป้าหมายโครงการ</div>
                                <div class="text-lg font-black text-cyan-400 font-mono">${kpi.target_participants} ท่าน</div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <div class="text-slate-400 text-[11px] font-bold">ตัวอย่างผู้สมัครล่าสุดในระบบ:</div>
                            ${listHtml || '<div class="text-slate-500 text-center py-2">ยังไม่มีรายชื่อ</div>'}
                        </div>
                    </div>`,
            speechText: `น้องตรวจสอบฐานข้อมูลสด พบผู้ลงทะเบียนแล้วทั้งหมด ${participants.length} ท่าน จากเป้าหมาย ${kpi.target_participants} ท่านฮิ!`,
            actions: [
                { label: 'ดูตารางรายชื่อทั้งหมด 100%', icon: 'fa-solid fa-table-list', url: `${prefix}pages/student_list.php` },
                { label: 'ไปหน้าลงทะเบียนเพิ่ม', icon: 'fa-solid fa-user-plus', url: `${prefix}pages/register.php` }
            ]
        };
    }

    // INTENT C: 7 TRAINING MODULES (7 โมดูล / เนื้อหา / หลักสูตร / เรียนอะไรบ้าง)
    if (q.includes('โมดูล') || q.includes('module') || q.includes('หลักสูตร') || q.includes('เรียนอะไร') || q.includes('เนื้อหา') || q.includes('课程') || q.includes('模块')) {
        const modHtml = modules.map(m => `
            <div class="p-2.5 bg-slate-900/80 rounded-xl border border-white/5 space-y-1 text-xs">
                <div class="font-bold text-amber-300 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-amber-400/20 text-amber-300 flex items-center justify-center text-[10px] font-black">${m.id}</span>
                    <span>${escapeHtml(m.name)}</span>
                </div>
                <div class="text-slate-300 text-[11px] leading-relaxed pl-6.5">${escapeHtml(m.desc)}</div>
            </div>
        `).join('');

        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-amber-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-book-open"></i> โครงสร้างหลักสูตร 7 โมดูล (Edge xAI : 4DAgriEnvi) 📚
                        </div>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            ออกแบบตามแนวทาง <strong>Active Learning &amp; CDIO Framework</strong> เน้นลงมือปฏิบัติการเขียนโค้ดและต่อวงจรจริงทุกโมดูลฮิ:
                        </p>
                        <div class="space-y-2 mt-2 max-h-60 overflow-y-auto pr-1">
                            ${modHtml}
                        </div>
                    </div>`,
            speechText: `หลักสูตรมีทั้งหมด 7 โมดูลฮิ ตั้งแต่พื้นฐาน ESP32-S3 เซ็นเซอร์ RS485 ดิน Edge TinyML โมเดลสิ่งแวดล้อม 4 มิติ ไปจนถึงการสตรีมข้อมูลคลาวด์และเคปสโตนโปรเจกต์เนี่ยฮิ!`,
            actions: [
                { label: 'ดูรายละเอียดบนหน้าหลัก', icon: 'fa-solid fa-desktop', url: `${prefix}index.php#modules` }
            ]
        };
    }

    // INTENT D: 4 CAPSTONE TRACKS (4 แทร็ก / Track A B C D / โครงงาน)
    if (q.includes('แทร็ก') || q.includes('track') || q.includes('โครงงาน') || q.includes('project') || q.includes('课题') || q.includes('ทิศทาง')) {
        let trackHtml = Object.entries(tracks).map(([k, v]) => `
            <div class="p-2.5 bg-slate-900/80 rounded-xl border border-teal-500/20 text-xs">
                <span class="font-black text-teal-300 font-mono mr-1.5">[${k}]</span>
                <span class="text-slate-200">${escapeHtml(v)}</span>
            </div>
        `).join('');

        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-teal-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-diagram-project"></i> 4 แทร็กโครงงานปฏิบัติการ (Capstone Projects) 🎯
                        </div>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            ผู้เข้าร่วมอบรมสามารถเลือกแทร็กโครงงานที่สนใจในการพัฒนา Prototype ได้อย่างอิสระ:
                        </p>
                        <div class="space-y-2 mt-2">
                            ${trackHtml}
                        </div>
                    </div>`,
            speechText: `โครงงานมี 4 แทร็กฮิ ได้แก่ Track A โรงเรือนอัจฉริยะ, Track B จุลภูมิอากาศ, Track C AI วิเคราะห์ดิน NPK, และ Track D คอมพิวเตอร์วิชันตรวจโรคพืชเนี่ยฮิ!`,
            actions: [
                { label: 'เลือกแทร็กในฟอร์มลงทะเบียน', icon: 'fa-solid fa-check-to-slot', url: `${prefix}pages/register.php` }
            ]
        };
    }

    // INTENT E: HARDWARE & SENSORS (ESP32 / RS485 Modbus / เซ็นเซอร์ดิน NPK / รีเลย์)
    if (q.includes('เซ็นเซอร์') || q.includes('sensor') || q.includes('rs485') || q.includes('modbus') || q.includes('npk') || q.includes('ดิน') || q.includes('esp32') || q.includes('hardware') || q.includes('อุปกรณ์') || q.includes('รีเลย์') || q.includes('กล้อง')) {
        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-purple-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-microchip"></i> ฮาร์ดแวร์และเซ็นเซอร์เกรดอุตสาหกรรมในโครงการ 🔌
                        </div>
                        <div class="bg-slate-900/80 p-3 rounded-xl border border-purple-500/30 space-y-2 text-[11px]">
                            <div><strong>1. บอร์ดประมวลผลหลัก:</strong> <span class="text-purple-300">ESP32-S3 Dual-Core Xtensa LX7 (รองรับ AI Vector Acceleration &amp; TinyML)</span></div>
                            <div><strong>2. อินเทอร์เฟซอุตสาหกรรม:</strong> <span class="text-cyan-300">RS485 Modbus RTU Bus (ป้องกันสัญญาณรบกวน เดินสายระยะไกลได้)</span></div>
                            <div><strong>3. เซ็นเซอร์วัดดิน 7-in-1:</strong> <span class="text-emerald-300">ตรวจวัด N, P, K, ค่า pH, ความชื้น, อุณหภูมิดิน และความนำไฟฟ้า (EC)</span></div>
                            <div><strong>4. ชุดควบคุมอัตโนมัติ:</strong> <span class="text-amber-300">บอร์ดรีเลย์แยกสัญญาณ 4 ช่อง สำหรับสั่งปั๊มน้ำ วาล์วไฟฟ้า และพัดลม</span></div>
                            <div><strong>5. กล้อง Smart Vision:</strong> <span class="text-pink-300">OV2640 Camera Module สำหรับวิเคราะห์ใบพืชด้วย Edge AI</span></div>
                        </div>
                    </div>`,
            speechText: `เราใช้บอร์ด ESP32-S3 พร้อมเซ็นเซอร์ดิน NPK แบบ 7-in-1 ผ่านบัส RS485 Modbus RTU เกรดอุตสาหกรรม และรีเลย์ควบคุมอัตโนมัติเนี่ยฮิ!`,
            actions: [
                { label: 'ทดสอบจำลองใน Simulator', icon: 'fa-solid fa-gauge', url: `${prefix}dashboard/index.php` }
            ]
        };
    }

    // INTENT F: LINE NOTIFICATIONS & GOOGLE SHEETS (แจ้งเตือนไลน์ / LINE OA / Google Sheet)
    if (q.includes('line') || q.includes('ไลน์') || q.includes('sheet') || q.includes('ชีท') || q.includes('แจ้งเตือน') || q.includes('messaging api') || q.includes('notify')) {
        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-emerald-400 flex items-center gap-1.5 text-sm">
                            <i class="fab fa-line"></i> ระบบแจ้งเตือน LINE &amp; Google Sheets คลาวด์เรียลไทม์ 📲
                        </div>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            ระบบของโครงการได้รับการอัปเกรดสู่สถาปัตยกรรมระดับองค์กร:
                        </p>
                        <div class="bg-slate-900/80 p-3 rounded-xl border border-emerald-500/30 space-y-2 text-[11px]">
                            <div><strong>1. LINE Official Account:</strong> <span class="text-emerald-300 font-mono font-bold">${proj.line_oa}</span> (Channel ID: 2012097925) ใช้ Messaging API ส่งแจ้งเตือนผู้ลงทะเบียนใหม่ทันที HTTP 200</div>
                            <div><strong>2. Google Sheets Cloud Sync:</strong> ซิงก์รายชื่อและข้อมูลตรวจวัดขึ้นสู่สเปรดชีตสด 24 ชม. ป้องกันข้อมูลสูญหาย 100%</div>
                        </div>
                    </div>`,
            speechText: `ระบบแจ้งเตือนผ่าน LINE Official Account Edge-xAI ด้วย Messaging API ทันทีเมื่อมีคนสมัคร และซิงก์ข้อมูลขึ้น Google Sheets อัตโนมัติเนี่ยฮิ!`,
            actions: [
                { label: 'เปิดแผงควบคุมใน Admin', icon: 'fa-solid fa-lock', url: `${prefix}admin/index.php` }
            ]
        };
    }

    // INTENT G: CERTIFICATE (เกียรติบัตร / วุฒิบัตร / รับรอง)
    if (q.includes('เกียรติบัตร') || q.includes('วุฒิบัตร') || q.includes('ใบประกาศ') || q.includes('certificate') || q.includes('证书')) {
        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-amber-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-certificate"></i> การออกเกียรติบัตรรับรองทางการ 📜
                        </div>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            ผู้ผ่านการอบรมและส่งโครงงานตามเกณฑ์จะได้รับ <strong>เกียรติบัตรรับรองอย่างเป็นทางการ</strong> จากคณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                        </p>
                        <div class="bg-slate-900/80 p-3 rounded-xl border border-amber-500/30 space-y-1.5 text-[11px]">
                            <div><strong>ผู้ลงนามที่ 1:</strong> ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา (หัวหน้าโครงการ)</div>
                            <div><strong>ผู้ลงนามที่ 2:</strong> รองศาสตราจารย์ ดร.อาภาพร บุญมี (คณบดีคณะวิทยาศาสตร์และเทคโนโลยี)</div>
                            <div><strong>ระบบตรวจสอบ:</strong> มี QR Code และ Digital Reference Code ป้องกันการปลอมแปลง 100%</div>
                        </div>
                    </div>`,
            speechText: `ผู้ผ่านการอบรมจะได้รับเกียรติบัตรรับรองทางการ มีลายเซ็นคณบดีและอาจารย์ชีวะ พร้อมระบบ QR Code ตรวจสอบความถูกต้องได้เลยเนี่ยฮิ!`,
            actions: [
                { label: 'ดูตัวอย่างเกียรติบัตร', icon: 'fa-solid fa-eye', url: `${prefix}pages/certificate_view.php?id=1` }
            ]
        };
    }

    // INTENT H: CONTACT & COORDINATOR (ติดต่อ อ.ชีวะ / เบอร์โทร / อีเมล / สถานที่)
    if (q.includes('ติดต่อ') || q.includes('ชีวะ') || q.includes('อาจารย์') || q.includes('เบอร์') || q.includes('โทร') || q.includes('email') || q.includes('contact') || q.includes('联系') || q.includes('สถานที่')) {
        return {
            reply: `<div class="space-y-2 text-xs">
                        <div class="font-bold text-sky-400 flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-address-book"></i> ช่องทางการติดต่อผู้รับผิดชอบโครงการ 📞
                        </div>
                        <div class="bg-slate-900/80 p-3 rounded-xl border border-sky-500/30 space-y-1.5 text-[11px]">
                            <div><strong>หัวหน้าโครงการ:</strong> <span class="text-white font-bold">${proj.coordinator}</span></div>
                            <div><strong>สังกัด:</strong> ${proj.institution}</div>
                            <div><strong>เบอร์โทรศัพท์:</strong> <a href="tel:${proj.phone}" class="text-emerald-400 font-bold hover:underline">${proj.phone}</a></div>
                            <div><strong>อีเมล:</strong> <a href="mailto:${proj.email}" class="text-cyan-400 font-bold hover:underline">${proj.email}</a></div>
                            <div><strong>สถานที่จัดอบรม:</strong> ${proj.venue}</div>
                        </div>
                    </div>`,
            speechText: `ติดต่อหัวหน้าโครงการ ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา ได้ที่เบอร์โทร 093-742-2654 หรืออีเมล chewa.t@rbru.ac.th ได้ตลอดเวลาเลยเนี่ยฮิ!`,
            actions: [
                { label: 'โทรหา อ.ชีวะ ทันที', icon: 'fa-solid fa-phone', url: `tel:${proj.phone}` }
            ]
        };
    }

    // DEFAULT / CHIT-CHAT (ทักทายทั่วไป / สำเนียงจันท์)
    return {
        reply: `<div class="space-y-2 text-xs">
                    <p class="text-slate-200 leading-relaxed">
                        ฮั่นแน่! น้องยินดีช่วยอาจารย์และเพื่อนๆ ทุกเรื่องเลยเนี่ยฮิ! 🌤️🌱<br>
                        เรื่องที่น้องเชี่ยวชาญและให้ข้อมูลได้ทันที เช่น:
                    </p>
                    <div class="grid grid-cols-2 gap-1.5 my-2">
                        <button type="button" onclick="sendQuickPrompt('7 โมดูลมีอะไรบ้าง')" class="p-2 rounded-lg bg-slate-800 text-emerald-300 hover:bg-slate-700 text-left border border-white/5 text-[11px]">
                            📚 7 โมดูลการอบรม
                        </button>
                        <button type="button" onclick="sendQuickPrompt('4 แทร็กโครงงานมีอะไรบ้าง')" class="p-2 rounded-lg bg-slate-800 text-cyan-300 hover:bg-slate-700 text-left border border-white/5 text-[11px]">
                            🎯 4 แทร็กโครงงาน
                        </button>
                        <button type="button" onclick="sendQuickPrompt('ผู้ลงทะเบียนกี่คนแล้ว')" class="p-2 rounded-lg bg-slate-800 text-amber-300 hover:bg-slate-700 text-left border border-white/5 text-[11px]">
                            👥 สถิติผู้ลงทะเบียน (${participants.length} ท่าน)
                        </button>
                        <button type="button" onclick="sendQuickPrompt('ติดต่อ อ.ชีวะ')" class="p-2 rounded-lg bg-slate-800 text-purple-300 hover:bg-slate-700 text-left border border-white/5 text-[11px]">
                            👨‍🏫 ติดต่อ อ.ชีวะ ทัศนา
                        </button>
                    </div>
                    <p class="text-slate-400 text-[11px]">
                        ลองพิมพ์คำถามเพิ่มเติม หรือกดปุ่มด้านบนได้เลยจ้าฮิ! 🌾
                    </p>
                </div>`,
        speechText: `น้องยินดีตอบทุกคำถามเกี่ยวกับโครงการ Edge xAI : 4DAgriEnvi ทั้งเรื่อง 7 โมดูล 4 แทร็ก และการสมัคร ถามน้องได้เลยเนี่ยฮิ!`,
        actions: []
    };
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// =========================================================================
// 7. CHAT MESSAGE SUBMISSION & RENDERING
// =========================================================================
function handleLeqsChatSubmit(event) {
    if (event) event.preventDefault();
    const input = document.getElementById('leqsChatInput');
    const query = input.value.trim();
    if (!query) return;

    input.value = '';
    sendUserMessage(query);
}

function sendQuickPrompt(query) {
    if (!query) return;
    if (!isLeqsChatOpen) toggleLeqsChatbot();
    sendUserMessage(query);
}

function sendUserMessage(query) {
    const container = document.getElementById('leqsChatMessages');
    const time = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });

    // User Message Bubble
    const userBubble = document.createElement('div');
    userBubble.className = 'flex justify-end gap-2.5';
    userBubble.innerHTML = `
        <div class="max-w-[85%] bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-3 rounded-2xl rounded-tr-sm shadow-md text-xs space-y-1">
            <div class="leading-relaxed font-medium">${escapeHtml(query)}</div>
            <div class="text-[9px] text-emerald-200 text-right font-mono">${time}</div>
        </div>
        <div class="w-8 h-8 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center shrink-0 text-slate-300 text-xs">
            <i class="fa-solid fa-user"></i>
        </div>
    `;
    container.appendChild(userBubble);
    container.scrollTop = container.scrollHeight;

    // Typing Animation
    const typingId = 'typing_' + Date.now();
    const typingBubble = document.createElement('div');
    typingBubble.id = typingId;
    typingBubble.className = 'flex justify-start gap-2.5';
    typingBubble.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0 text-emerald-400 text-xs shadow-sm">
            🌱
        </div>
        <div class="bg-slate-900 border border-white/10 p-3 rounded-2xl rounded-tl-sm text-slate-400 text-xs flex items-center gap-1.5 shadow-md">
            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0s;"></span>
            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.15s;"></span>
            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.3s;"></span>
            <span class="text-[10px] text-slate-500 font-mono ml-1">น้องกำลังประมวลผล...</span>
        </div>
    `;
    container.appendChild(typingBubble);
    container.scrollTop = container.scrollHeight;

    // Process RAG Query with slight delay for natural feel
    setTimeout(() => {
        const typingEl = document.getElementById(typingId);
        if (typingEl) typingEl.remove();

        const result = processRAGQuery(query, currentLeqsLang);
        renderBotResponse(result);
    }, 450);
}

function renderBotResponse(result) {
    const container = document.getElementById('leqsChatMessages');
    const time = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });

    let actionBtnsHtml = '';
    if (Array.isArray(result.actions) && result.actions.length > 0) {
        actionBtnsHtml = `
            <div class="pt-2 border-t border-white/10 flex flex-wrap gap-1.5 mt-2">
                ${result.actions.map(a => `
                    <a href="${a.url}" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-slate-950 font-bold text-[10px] flex items-center gap-1 transition border border-emerald-400/30">
                        <i class="${a.icon} text-[9px]"></i>
                        <span>${a.label}</span>
                    </a>
                `).join('')}
            </div>
        `;
    }

    const botBubble = document.createElement('div');
    botBubble.className = 'flex justify-start gap-2.5';
    botBubble.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0 text-emerald-400 text-xs shadow-sm">
            🌱
        </div>
        <div class="max-w-[85%] bg-slate-900 border border-white/10 text-white p-3.5 rounded-2xl rounded-tl-sm shadow-xl text-xs space-y-1.5">
            <div class="leading-relaxed">${result.reply}</div>
            ${actionBtnsHtml}
            <div class="flex items-center justify-between text-[9px] text-slate-500 pt-1">
                <span class="font-mono text-emerald-400">น้อง LEQs AI</span>
                <span class="font-mono">${time}</span>
            </div>
        </div>
    `;
    container.appendChild(botBubble);
    container.scrollTop = container.scrollHeight;

    if (result.speechText) {
        speakWithDigitalTone(result.speechText);
    }
}

function clearLeqsChat(isLanguageSwitch = false) {
    const container = document.getElementById('leqsChatMessages');
    if (!container) return;
    container.innerHTML = '';

    const dict = I18N_DATA[currentLeqsLang];
    const time = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });

    const welcomeBubble = document.createElement('div');
    welcomeBubble.className = 'flex justify-start gap-2.5';
    welcomeBubble.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0 text-emerald-400 text-xs shadow-sm">
            🌱
        </div>
        <div class="max-w-[90%] bg-slate-900 border border-white/10 text-white p-3.5 rounded-2xl rounded-tl-sm shadow-xl text-xs space-y-2">
            <div class="font-black text-emerald-400 flex items-center gap-1.5 text-sm">
                ${dict.welcomeTitle}
            </div>
            <div class="text-slate-300 leading-relaxed text-[11px]">
                ${dict.welcomeMsg}
            </div>
            <div class="flex items-center justify-between text-[9px] text-slate-500 pt-1 border-t border-white/5">
                <span class="font-mono text-emerald-400">Edge xAI AI Mascot</span>
                <span class="font-mono">${time}</span>
            </div>
        </div>
    `;
    container.appendChild(welcomeBubble);

    if (!isLanguageSwitch) {
        playDigitalChime('end');
    }
}

// =========================================================================
// 8. VOICE RECOGNITION (SPEECH-TO-TEXT MICROPHONE)
// =========================================================================
function toggleVoiceInput() {
    unlockAudio();
    if (isLeqsListening) {
        stopVoiceInput();
        return;
    }
    startVoiceInput();
}

function startVoiceInput() {
    const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRec) {
        alert('เบราว์เซอร์ของท่านยังไม่รองรับระบบตรวจจับเสียงพูด กรุณาใช้ Chrome หรือ Edge ครับ');
        return;
    }

    try {
        speechRecognition = new SpeechRec();
        speechRecognition.lang = currentLeqsLang === 'th' ? 'th-TH' : (currentLeqsLang === 'zh' ? 'zh-CN' : 'en-US');
        speechRecognition.interimResults = false;
        speechRecognition.maxAlternatives = 1;

        const micBtn = document.getElementById('leqsMicBtn');
        const indicator = document.getElementById('leqsListeningIndicator');

        speechRecognition.onstart = () => {
            isLeqsListening = true;
            if (micBtn) {
                micBtn.className = 'w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shrink-0 transition animate-pulse shadow-lg shadow-rose-500/30';
            }
            if (indicator) indicator.classList.remove('hidden');
            playDigitalChime('start');
        };

        speechRecognition.onresult = (e) => {
            const transcript = e.results[0][0].transcript;
            if (transcript) {
                document.getElementById('leqsChatInput').value = transcript;
                sendUserMessage(transcript);
            }
        };

        speechRecognition.onerror = () => {
            stopVoiceInput();
        };

        speechRecognition.onend = () => {
            stopVoiceInput();
        };

        speechRecognition.start();
    } catch(e) {
        stopVoiceInput();
    }
}

function stopVoiceInput() {
    isLeqsListening = false;
    if (speechRecognition) {
        try { speechRecognition.stop(); } catch(e) {}
        speechRecognition = null;
    }
    const micBtn = document.getElementById('leqsMicBtn');
    const indicator = document.getElementById('leqsListeningIndicator');
    if (micBtn) {
        micBtn.className = 'w-10 h-10 rounded-2xl bg-slate-800 hover:bg-emerald-500/20 text-slate-300 hover:text-emerald-400 flex items-center justify-center shrink-0 transition border border-white/10';
    }
    if (indicator) indicator.classList.add('hidden');
}

// Auto-initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    initRAGKnowledgeBase();
    renderQuickChips();
    clearLeqsChat(true);
    updateVoiceToggleBtn();

    // Show bubble prompt after 2.5 seconds
    setTimeout(() => {
        const bubble = document.getElementById('leqsBotSpeechBubble');
        if (bubble && !isLeqsChatOpen) {
            bubble.classList.remove('hidden');
        }
    }, 2500);
});
</script>
