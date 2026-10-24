/**
 * AgriLLM SmartFarm Dashboard - Execution & Analytics Engine
 */

let currentTelemetry = {
    crop_name: "ทุเรียนหมอนทอง",
    soil_moisture: 34.2,
    soil_ph: 5.18,
    soil_ec: 0.55,
    soil_n: 38,
    soil_p: 12,
    soil_k: 85,
    air_temp: 34.5,
    air_humidity: 84.0,
    light_lux: 68400
};

let chartNpkInstance = null;
let chartClimateInstance = null;

document.addEventListener("DOMContentLoaded", () => {
    initNavigationTabs();
    initCharts();
    initEventListeners();
    updateAnalyticsView();
    loadKbChunks();
    checkSystemStatus();
});

/* ================= 1. Navigation Tabs ================= */
function initNavigationTabs() {
    const tabs = document.querySelectorAll(".nav-tab");
    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));

            tab.classList.add("active");
            const targetId = tab.getAttribute("data-tab");
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add("active");
                if (targetId === "tab-analytics") {
                    updateCharts();
                }
            }
        });
    });
}

/* ================= 2. Event Listeners ================= */
function initEventListeners() {
    const cropSelect = document.getElementById("cropSelect");
    cropSelect.addEventListener("change", (e) => {
        currentTelemetry.crop_name = e.target.value;
        updateAnalyticsView();
    });

    // Preset Scenario buttons
    document.querySelectorAll(".btn-scenario").forEach(btn => {
        btn.addEventListener("click", () => {
            const sc = btn.getAttribute("data-scenario");
            applyScenario(sc);
        });
    });

    // Run Analytics button
    document.getElementById("btnRunAnalytics").addEventListener("click", () => {
        updateAnalyticsView(true);
    });

    // Benchmark Run Button
    document.getElementById("btnRunBenchmark").addEventListener("click", () => {
        triggerBenchmarkRun();
    });

    // Sync Knowledge Base Button
    document.getElementById("btnSyncKb").addEventListener("click", () => {
        triggerSyncKb();
    });

    // Copilot Chat form
    const chatForm = document.getElementById("chatForm");
    const userInput = document.getElementById("userInput");
    chatForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if (!text) return;
        appendChatMessage("user", text);
        userInput.value = "";
        sendChatRequest(text);
    });

    // Quick chips
    document.querySelectorAll(".chip").forEach(chip => {
        chip.addEventListener("click", () => {
            const q = chip.getAttribute("data-q");
            appendChatMessage("user", q);
            sendChatRequest(q);
        });
    });

    // Inject Telemetry into chat
    document.getElementById("btnInjectTelemetry").addEventListener("click", () => {
        const query = `ช่วยวิเคราะห์สุขภาพและวินิจฉัยสภาพแปลง ${currentTelemetry.crop_name} จากค่าเซนเซอร์สดขณะนี้`;
        appendChatMessage("user", `⚡ ส่งค่าเซนเซอร์ HandySense เข้าห้องประมวลผล (${currentTelemetry.crop_name})`);
        sendChatRequest(query, currentTelemetry);
    });

    // Randomize telemetry in chat sidebar
    document.getElementById("btnRandomTelemetry").addEventListener("click", () => {
        randomizeTelemetry();
    });

    // KB Search
    document.getElementById("btnSearchKb").addEventListener("click", () => {
        const q = document.getElementById("kbSearchInput").value.trim();
        loadKbChunks(q);
    });
    document.getElementById("kbSearchInput").addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            const q = document.getElementById("kbSearchInput").value.trim();
            loadKbChunks(q);
        }
    });
}

/* ================= 3. Scenarios & Telemetry ================= */
function applyScenario(sc) {
    if (sc === "optimal") {
        currentTelemetry.soil_ph = 6.4;
        currentTelemetry.soil_moisture = 58.0;
        currentTelemetry.soil_ec = 1.1;
        currentTelemetry.soil_n = 55;
        currentTelemetry.soil_p = 32;
        currentTelemetry.soil_k = 110;
        currentTelemetry.air_temp = 29.5;
        currentTelemetry.air_humidity = 68.0;
        currentTelemetry.light_lux = 52000;
    } else if (sc === "acidic") {
        currentTelemetry.soil_ph = 4.95;
        currentTelemetry.soil_moisture = 42.0;
        currentTelemetry.soil_ec = 0.45;
        currentTelemetry.soil_n = 35;
        currentTelemetry.soil_p = 10;
        currentTelemetry.soil_k = 75;
        currentTelemetry.air_temp = 32.0;
        currentTelemetry.air_humidity = 72.0;
        currentTelemetry.light_lux = 61000;
    } else if (sc === "drought") {
        currentTelemetry.soil_ph = 6.8;
        currentTelemetry.soil_moisture = 22.5;
        currentTelemetry.soil_ec = 1.8;
        currentTelemetry.soil_n = 40;
        currentTelemetry.soil_p = 20;
        currentTelemetry.soil_k = 90;
        currentTelemetry.air_temp = 38.2;
        currentTelemetry.air_humidity = 45.0;
        currentTelemetry.light_lux = 82000;
    } else if (sc === "disease_risk") {
        currentTelemetry.soil_ph = 5.2;
        currentTelemetry.soil_moisture = 78.0;
        currentTelemetry.soil_ec = 0.7;
        currentTelemetry.soil_n = 45;
        currentTelemetry.soil_p = 15;
        currentTelemetry.soil_k = 80;
        currentTelemetry.air_temp = 31.5;
        currentTelemetry.air_humidity = 88.0;
        currentTelemetry.light_lux = 38000;
    }
    updateAnalyticsView();
}

function randomizeTelemetry() {
    currentTelemetry.soil_moisture = +(20 + Math.random() * 60).toFixed(1);
    currentTelemetry.soil_ph = +(4.8 + Math.random() * 2.8).toFixed(2);
    currentTelemetry.soil_ec = +(0.3 + Math.random() * 2.0).toFixed(2);
    currentTelemetry.soil_n = Math.floor(20 + Math.random() * 50);
    currentTelemetry.soil_p = Math.floor(8 + Math.random() * 40);
    currentTelemetry.soil_k = Math.floor(40 + Math.random() * 120);
    currentTelemetry.air_temp = +(26 + Math.random() * 12).toFixed(1);
    currentTelemetry.air_humidity = +(50 + Math.random() * 45).toFixed(0);
    currentTelemetry.light_lux = Math.floor(20000 + Math.random() * 70000);
    updateAnalyticsView();
}

/* ================= 4. Analytics Calculations & Updates ================= */
async function updateAnalyticsView(triggerAiAdvice = false) {
    // 1. Calculate VPD (Tetens equation)
    const T = currentTelemetry.air_temp;
    const RH = currentTelemetry.air_humidity;
    const vpSat = 0.61078 * Math.exp((17.27 * T) / (T + 237.3));
    const vpAct = vpSat * (RH / 100.0);
    const vpd = Math.max(0.0, vpSat - vpAct);

    // Update VPD KPI
    const valVpdEl = document.getElementById("valVpd");
    const vpdBadge = document.getElementById("vpdBadge");
    const barVpd = document.getElementById("barVpd");
    const descVpd = document.getElementById("descVpd");

    valVpdEl.textContent = vpd.toFixed(2);
    const vpdPct = Math.min(100, (vpd / 2.5) * 100);
    barVpd.style.width = `${vpdPct}%`;

    if (vpd < 0.4) {
        vpdBadge.className = "badge-sub danger";
        vpdBadge.textContent = "ชื้นจัด เสี่ยงรา";
        barVpd.style.background = "var(--danger)";
        descVpd.textContent = "ความชื้นสูงเกินไป การคายน้ำต่ำ เสี่ยงระบาดของเชื้อราใบจุด/ราน้ำค้าง";
    } else if (vpd <= 1.25) {
        vpdBadge.className = "badge-sub optimal";
        vpdBadge.textContent = "สมบูรณ์แบบ";
        barVpd.style.background = "var(--optimal)";
        descVpd.textContent = "การคายน้ำสมบูรณ์แบบ พืชสังเคราะห์แสงและดูดปุ๋ยได้ดีที่สุด";
    } else if (vpd <= 1.6) {
        vpdBadge.className = "badge-sub warning";
        vpdBadge.textContent = "แล้งปานกลาง";
        barVpd.style.background = "var(--warning)";
        descVpd.textContent = "อากาศแห้ง พืชเริ่มคายน้ำเร็ว ควรเปิดพ่นหมอกลดความร้อน";
    } else {
        vpdBadge.className = "badge-sub danger";
        vpdBadge.textContent = "แล้งวิกฤต";
        barVpd.style.background = "var(--danger)";
        descVpd.textContent = "อากาศแห้งจัด ปากใบพืชปิดตัว การสังเคราะห์แสงหยุดชะงัก";
    }

    // 2. Update Soil pH KPI
    const valPh = currentTelemetry.soil_ph;
    document.getElementById("valPh").textContent = valPh.toFixed(2);
    const phBadge = document.getElementById("phBadge");
    const barPh = document.getElementById("barPh");
    const descPh = document.getElementById("descPh");
    barPh.style.width = `${Math.min(100, (valPh / 9.0) * 100)}%`;

    if (valPh < 5.3) {
        phBadge.className = "badge-sub danger";
        phBadge.textContent = "กรดจัด";
        barPh.style.background = "var(--danger)";
        descPh.textContent = "ฟอสฟอรัสถูกตรึง ดินเป็นกรดจัด ควรใส่ปูนโดโลไมต์และเติมไตรโคเดอร์มา";
    } else if (valPh > 7.4) {
        phBadge.className = "badge-sub warning";
        phBadge.textContent = "ดินด่าง";
        barPh.style.background = "var(--warning)";
        descPh.textContent = "ดินด่าง พืชอาจขาดจุลธาตุเหล็ก-สังกะสี ควรเติมอินทรียวัตถุ";
    } else {
        phBadge.className = "badge-sub optimal";
        phBadge.textContent = "เหมาะสม";
        barPh.style.background = "var(--optimal)";
        descPh.textContent = "ช่วง pH เหมาะสมที่สุดต่อการดูดซึมธาตุอาหารหลัก N-P-K";
    }

    // 3. Update Soil Moisture KPI
    const valSm = currentTelemetry.soil_moisture;
    document.getElementById("valMoisture").textContent = valSm.toFixed(1);
    const moistureBadge = document.getElementById("moistureBadge");
    const barMoisture = document.getElementById("barMoisture");
    const descMoisture = document.getElementById("descMoisture");
    barMoisture.style.width = `${Math.min(100, valSm)}%`;

    if (valSm < 35.0) {
        moistureBadge.className = "badge-sub danger";
        moistureBadge.textContent = "แห้งวิกฤต";
        barMoisture.style.background = "var(--danger)";
        descMoisture.textContent = "ดินแห้งต่ำกว่าเกณฑ์ (<35%) เสี่ยงต่อการสลัดดอกและผลร่วง";
    } else if (valSm > 75.0) {
        moistureBadge.className = "badge-sub warning";
        moistureBadge.textContent = "แฉะเกินไป";
        barMoisture.style.background = "var(--warning)";
        descMoisture.textContent = "ดินแฉะน้ำขัง เสี่ยงต่อการขาดออกซิเจนในเขตรากและโรครากเน่า";
    } else {
        moistureBadge.className = "badge-sub optimal";
        moistureBadge.textContent = "พอดี";
        barMoisture.style.background = "var(--optimal)";
        descMoisture.textContent = "ความชื้นในดินสมดุลดีเยี่ยม สนับสนุนการเจริญเติบโตเต็มที่";
    }

    // 4. Update EC KPI
    const valEc = currentTelemetry.soil_ec;
    document.getElementById("valEc").textContent = valEc.toFixed(2);
    const ecBadge = document.getElementById("ecBadge");
    const barEc = document.getElementById("barEc");
    const descEc = document.getElementById("descEc");
    barEc.style.width = `${Math.min(100, (valEc / 3.0) * 100)}%`;

    if (valEc > 2.0) {
        ecBadge.className = "badge-sub danger";
        ecBadge.textContent = "ดินเค็ม ปุ๋ยเกิน";
        barEc.style.background = "var(--danger)";
        descEc.textContent = "เกลือแร่ละลายได้สูงเกินขนาด เสี่ยงรากพืชไหม้ ควรใช้น้ำชะล้าง";
    } else {
        ecBadge.className = "badge-sub optimal";
        ecBadge.textContent = "ปลอดภัย";
        barEc.style.background = "var(--optimal)";
        descEc.textContent = "ระดับเกลือแร่ละลายได้ต่ำ-ปานกลาง ปลอดภัยต่อรากพืช";
    }

    // Update mini values in copilot sidebar
    const chatPh = document.getElementById("chatPh");
    if (chatPh) {
        document.getElementById("chatPh").textContent = valPh.toFixed(2);
        document.getElementById("chatMoisture").textContent = `${valSm.toFixed(1)}%`;
        document.getElementById("chatEc").textContent = `${valEc.toFixed(2)} mS/cm`;
        document.getElementById("chatNpk").textContent = `${currentTelemetry.soil_n} - ${currentTelemetry.soil_p} - ${currentTelemetry.soil_k}`;
        document.getElementById("chatTemp").textContent = `${currentTelemetry.air_temp.toFixed(1)} °C`;
        document.getElementById("chatHum").textContent = `${currentTelemetry.air_humidity.toFixed(0)}%`;
        document.getElementById("chatLight").textContent = `${currentTelemetry.light_lux.toLocaleString()} Lux`;
    }

    // Update Charts
    updateCharts();

    // Update Actionable Prescription
    updatePrescriptionText(valSm, valPh, valEc, vpd);

    // If trigger AI advice was clicked, also request from backend
    if (triggerAiAdvice) {
        requestAgronomistAdvice();
    }
}

function updatePrescriptionText(sm, ph, ec, vpd) {
    const rxWater = document.getElementById("rxWater");
    const rxFertilizer = document.getElementById("rxFertilizer");
    const rxBio = document.getElementById("rxBio");

    if (sm < 35) {
        rxWater.textContent = "สั่งการ Relay 1 เปิดระบบน้ำหยด/มินิสปริงเกลอร์ทันที 25-30 ลิตร/ต้น จนความชื้นแตะ 60%";
    } else if (sm > 75) {
        rxWater.textContent = "ระงับการให้น้ำทุกแปลง ตรวจท่อระบายน้ำแปลงปลูกเพื่อป้องกันน้ำขังเขตราก";
    } else {
        rxWater.textContent = "ระดับน้ำสมดุล รักษาอัตราการให้น้ำตามรอบปกติ (Pulse 10-15 นาที/วัน)";
    }

    if (ph < 5.3) {
        rxFertilizer.textContent = "หว่านปูนโดโลไมต์ 150-200 กก./ไร่ เสริมปุ๋ยฟอสฟอรัสสูง (12-24-12) เพื่อแก้ปัญหาฟอสฟอรัสถูกตรึง";
    } else if (ph > 7.4) {
        rxFertilizer.textContent = "เติมอินทรียวัตถุปรับโครงสร้างดิน หลีกเลี่ยงปูนขาว เสริมปุ๋ยธาตุสังกะสีและเหล็กคีเลต";
    } else {
        rxFertilizer.textContent = "ดินสมดุลดี ใส่ปุ๋ยเคมีสูตรเสมอ (16-16-16) ร่วมกับปุ๋ยหมักชีวภาพอัตราปกติ";
    }

    if (vpd < 0.4 || currentTelemetry.air_humidity > 80) {
        rxBio.textContent = "ฉีดพ่นชีวภัณฑ์เชื้อรา *Trichoderma asperellum* ทางดิน และพ่น *Bacillus subtilis* ทางใบเพื่อป้องกันโรคราน้ำค้าง";
    } else {
        rxBio.textContent = "เติมหัวเชื้อจุลินทรีย์ไมคอร์ไรซาและไตรโคเดอร์มาทางดินเดือนละ 1 ครั้ง เพื่อสร้างภูมิคุ้มกันระบบราก";
    }
}

async function requestAgronomistAdvice() {
    try {
        const res = await fetch("/api/agronomist-advice", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                crop_name: currentTelemetry.crop_name,
                telemetry: currentTelemetry
            })
        });
        const data = await res.json();
        if (data.response) {
            // Append as notification or chat message
            appendChatMessage("assistant", data.response, data.model_used, data.rag_applied);
        }
    } catch (e) {
        console.error("Agronomist advice error", e);
    }
}

/* ================= 5. Data Charts ================= */
function initCharts() {
    // 1. Chart NPK
    const ctxNpk = document.getElementById("chartNpk");
    if (ctxNpk && typeof Chart !== "undefined") {
        chartNpkInstance = new Chart(ctxNpk, {
            type: "bar",
            data: {
                labels: ["ไนโตรเจน (N)", "ฟอสฟอรัส (P)", "โพแทสเซียม (K)"],
                datasets: [
                    {
                        label: "ค่าปัจจุบัน (mg/kg)",
                        data: [currentTelemetry.soil_n, currentTelemetry.soil_p, currentTelemetry.soil_k],
                        backgroundColor: ["rgba(16, 185, 129, 0.7)", "rgba(245, 158, 11, 0.7)", "rgba(59, 130, 246, 0.7)"],
                        borderColor: ["#10b981", "#f59e0b", "#3b82f6"],
                        borderWidth: 1.5,
                        borderRadius: 6
                    },
                    {
                        label: "เกณฑ์แนะนำ (Target)",
                        data: [50, 30, 100],
                        backgroundColor: "rgba(255, 255, 255, 0.15)",
                        borderColor: "rgba(255, 255, 255, 0.4)",
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "rgba(255, 255, 255, 0.06)" },
                        ticks: { color: "#94a3b8" }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: "#cbd5e1" }
                    }
                },
                plugins: {
                    legend: { labels: { color: "#cbd5e1", font: { family: "Sarabun" } } }
                }
            }
        });
    }

    // 2. Chart Climate
    const ctxClimate = document.getElementById("chartClimate");
    if (ctxClimate && typeof Chart !== "undefined") {
        chartClimateInstance = new Chart(ctxClimate, {
            type: "line",
            data: {
                labels: ["06:00", "09:00", "12:00", "15:00", "18:00", "ปัจจุบัน"],
                datasets: [
                    {
                        label: "อุณหภูมิ (°C)",
                        data: [26.0, 29.5, 33.8, 35.2, 31.0, currentTelemetry.air_temp],
                        borderColor: "#f59e0b",
                        backgroundColor: "rgba(245, 158, 11, 0.1)",
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: "ความชื้นอากาศ (%)",
                        data: [92, 78, 65, 60, 75, currentTelemetry.air_humidity],
                        borderColor: "#3b82f6",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: "rgba(255, 255, 255, 0.06)" },
                        ticks: { color: "#94a3b8" }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: "#cbd5e1" }
                    }
                },
                plugins: {
                    legend: { labels: { color: "#cbd5e1", font: { family: "Sarabun" } } }
                }
            }
        });
    }
}

function updateCharts() {
    if (chartNpkInstance) {
        chartNpkInstance.data.datasets[0].data = [
            currentTelemetry.soil_n,
            currentTelemetry.soil_p,
            currentTelemetry.soil_k
        ];
        chartNpkInstance.update();
    }
    if (chartClimateInstance) {
        const lastIdx = chartClimateInstance.data.datasets[0].data.length - 1;
        chartClimateInstance.data.datasets[0].data[lastIdx] = currentTelemetry.air_temp;
        chartClimateInstance.data.datasets[1].data[lastIdx] = currentTelemetry.air_humidity;
        chartClimateInstance.update();
    }
}

/* ================= 6. Benchmark Runner ================= */
async function triggerBenchmarkRun() {
    const box = document.getElementById("evalProgressBox");
    const bar = document.getElementById("evalProgressBar");
    const pct = document.getElementById("evalProgressPct");
    const statusText = document.getElementById("evalStatusText");
    const btn = document.getElementById("btnRunBenchmark");

    box.style.display = "block";
    btn.disabled = true;
    btn.innerHTML = `⏳ กำลังรัน Benchmark...`;

    let progress = 0;
    const interval = setInterval(() => {
        progress += 15;
        if (progress > 90) progress = 90;
        bar.style.width = `${progress}%`;
        pct.textContent = `${progress}%`;
    }, 150);

    try {
        const res = await fetch("/api/benchmark/run", { method: "POST" });
        clearInterval(interval);
        bar.style.width = "100%";
        pct.textContent = "100%";
        statusText.textContent = "✅ รันการประเมิน Benchmark เสร็จสมบูรณ์!";

        if (res.ok) {
            const data = await res.json();
            if (data.metrics) {
                document.getElementById("metricCorrectness").textContent = `${(data.metrics.mean_answer_correctness * 100).toFixed(1)}%`;
                document.getElementById("metricPrecision").textContent = `${(data.metrics.mean_factual_precision * 100).toFixed(1)}%`;
                document.getElementById("metricRecall").textContent = `${(data.metrics.mean_factual_recall * 100).toFixed(1)}%`;
            }
        }
    } catch (e) {
        clearInterval(interval);
        statusText.textContent = "⚠️ รันเสร็จสิ้น (ใช้ผลลัพธ์ Benchmark ล่าสุด)";
    } finally {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = `<span class="btn-icon">⚡</span> สั่งรันการประเมิน Benchmark (Live Eval)`;
        }, 1500);
    }
}

async function triggerSyncKb() {
    const btn = document.getElementById("btnSyncKb");
    btn.disabled = true;
    btn.textContent = "⏳ กำลังซิงค์ดัชนี...";
    try {
        const res = await fetch("/api/data/sync");
        const data = await res.json();
        alert(`✅ ซิงค์ดัชนีสำเร็จ! มี Chunks ใน Vector DB ทั้งสิ้น: ${data.synced_chunks} รายการ`);
        loadKbChunks();
    } catch (e) {
        alert("⚠️ ซิงค์ดัชนีไม่สำเร็จ");
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="btn-icon">🔄</span> ซิงค์ดัชนีคลังความรู้ RAG (Sync Documents)`;
    }
}

/* ================= 7. Knowledge Base Explorer ================= */
async function loadKbChunks(searchQuery = "") {
    const container = document.getElementById("kbChunksList");
    if (!container) return;

    container.innerHTML = `<p style="color:#94a3b8;">กำลังโหลดรายการดัชนีความรู้...</p>`;
    try {
        const url = searchQuery ? `/api/kb/search?q=${encodeURIComponent(searchQuery)}&top_k=8` : `/api/kb/search?q=การเกษตร&top_k=8`;
        const res = await fetch(url);
        const data = await res.json();

        if (!data.results || data.results.length === 0) {
            container.innerHTML = `<p style="color:#94a3b8;">ไม่พบดัชนีที่ตรงกับคำค้นหา</p>`;
            return;
        }

        container.innerHTML = data.results.map(chunk => `
            <div class="chunk-card">
                <h4>${escapeHtml(chunk.title)}</h4>
                <span class="chunk-source">📂 แหล่งที่มา: ${escapeHtml(chunk.source)} | หมวดหมู่: ${escapeHtml(chunk.category)}</span>
                <div class="chunk-content">${escapeHtml(chunk.content)}</div>
            </div>
        `).join("");
    } catch (e) {
        container.innerHTML = `<p style="color:#f87171;">เกิดข้อผิดพลาดในการโหลดคลังความรู้</p>`;
    }
}

/* ================= 8. Chat Console ================= */
async function sendChatRequest(query, telemetry = null) {
    const useRag = document.getElementById("toggleRagMain") ? document.getElementById("toggleRagMain").checked : true;
    const chatMessages = document.getElementById("chatMessages");

    const typingId = "typing-" + Date.now();
    const typingMsg = document.createElement("div");
    typingMsg.className = "message assistant";
    typingMsg.id = typingId;
    typingMsg.innerHTML = `
        <div class="avatar">🌱</div>
        <div class="bubble"><p><em>AgriLLM กำลังประมวลผลข้อเท็จจริงทางการเกษตร...</em></p></div>
    `;
    chatMessages.appendChild(typingMsg);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
        const payload = {
            query: query,
            telemetry: telemetry || currentTelemetry,
            use_rag: useRag
        };

        const res = await fetch("/api/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        const typingEl = document.getElementById(typingId);
        if (typingEl) typingEl.remove();

        if (data.response) {
            appendChatMessage("assistant", data.response, data.model_used, data.rag_applied);
        } else {
            appendChatMessage("assistant", "⚠️ ไม่สามารถสร้างคำตอบได้ในขณะนี้");
        }
    } catch (err) {
        const typingEl = document.getElementById(typingId);
        if (typingEl) typingEl.remove();
        appendChatMessage("assistant", "⚠️ เกิดข้อผิดพลาดในการเชื่อมต่อกับ AgriLLM Server");
    }
}

function appendChatMessage(role, text, modelUsed = null, ragApplied = null) {
    const chatMessages = document.getElementById("chatMessages");
    const msg = document.createElement("div");
    msg.className = `message ${role}`;

    const avatar = role === "user" ? "🧑‍🌾" : "🌾";
    let metaBadge = "";
    if (role === "assistant" && modelUsed) {
        metaBadge = `<div style="font-size:0.75rem; color:#94a3b8; margin-top:0.4rem; border-top:1px dashed rgba(255,255,255,0.1); padding-top:0.3rem;">
            ⚙️ Engine: <strong>${modelUsed}</strong> | RAG: <span style="color:${ragApplied ? '#34d399':'#fbbf24'}">${ragApplied ? 'Grounded' : 'Disabled'}</span>
        </div>`;
    }

    msg.innerHTML = `
        <div class="avatar">${avatar}</div>
        <div class="bubble">
            ${formatMarkdown(text)}
            ${metaBadge}
        </div>
    `;
    chatMessages.appendChild(msg);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function formatMarkdown(text) {
    if (!text) return "";
    return text
        .replace(/^### (.*$)/gim, '<h3>$1</h3>')
        .replace(/^#### (.*$)/gim, '<h4>$1</h4>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`([^`]+)`/g, '<code style="background:rgba(255,255,255,0.1);padding:2px 4px;border-radius:4px;">$1</code>')
        .replace(/\n\n/g, '<br><br>')
        .replace(/\n/g, '<br>');
}

function escapeHtml(str) {
    if (!str) return "";
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

async function checkSystemStatus() {
    const statusEl = document.getElementById("systemStatus");
    try {
        const res = await fetch("/api/status");
        if (res.ok) {
            const data = await res.json();
            statusEl.className = "status-indicator online";
            statusEl.querySelector(".status-text").textContent = `AgriLLM RAG (${data.vector_store_chunks} chunks): Ready`;
        }
    } catch (e) {
        statusEl.className = "status-indicator warning";
        statusEl.querySelector(".status-text").textContent = "AgriLLM: Offline / Standalone";
    }
}
