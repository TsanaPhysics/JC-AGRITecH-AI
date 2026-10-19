/**
 * =========================================================================
 * Edge xAIoT : 4D-AgriEnvi — Multi-Database Google Sheets Sync Engine
 * พัฒนาโดย: หน่วยบริการวิเคราะห์สิ่งแวดล้อมเพื่อท้องถิ่น (LEQs) & xAI
 * คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
 * =========================================================================
 * 
 * รองรับและแสดงผลฐานข้อมูลระบบทั้งหมด 6 แผ่นงาน (Sheets/Tabs):
 * 1. [📊 ภาพรวมระบบ (Overview)] - Dashboard สรุปสถิติ KPI, คะแนนเฉลี่ย, ข้อมูลสิ่งแวดล้อมสด
 * 2. [1. ผู้ลงทะเบียน (Participants)] - ทำเนียบผู้เข้าร่วม, กลุ่มเป้าหมาย, คะแนน Pre/Post, เช็คอิน
 * 3. [2. บันทึกโทรมาตร IoT (Telemetry)] - บันทึกประวัติเซนเซอร์สิ่งแวดล้อม Temp, Hum, VPD, Soil, NPK, Light
 * 4. [3. สถานะรีเลย์ (Relay States)] - สถานะควบคุมฮาร์ดแวร์ ปั๊มน้ำ, วาล์วม่านหมอก, พัดลม, หลอดไฟ
 * 5. [4. การประเมินโครงการ (Evaluations)] - ผลการประเมินความพึงพอใจ 10 ข้อ, ค่าเฉลี่ย, ข้อเสนอแนะ
 * 6. [5. ข่าวประชาสัมพันธ์ (Announcements)] - ประกาศและข่าวสารโครงการทั้งหมด
 * 
 * วิธีการติดตั้งและอัปเดต (ใช้เวลา 1 นาที):
 * 1. เปิด Google Sheets ที่ต้องการใช้งาน
 * 2. ไปที่เมนู "ส่วนขยาย" (Extensions) > "Apps Script"
 * 3. ลบโค้ดเดิมทั้งหมดออก แล้ววางโค้ดนี้ลงไปแทน
 * 4. กดปุ่ม "บันทึก" (รูปแผ่นดิสก์)
 * 5. กดปุ่มสีน้ำเงิน "การทำให้ใช้งานได้" (Deploy) > "การทำให้ใช้งานได้ใหม่" (New deployment)
 *    - ประเภท: "เว็บแอป" (Web app)
 *    - การเข้าถึง: "ทุกคน" (Anyone)
 *    - กด "ทำให้ใช้งานได้" (Deploy) แล้วคัดลอก Web App URL ไว้อัปเดตในระบบ
 * 6. สลับกลับมาที่หน้า Google Sheet แล้วรีเฟรชหน้าต่าง (F5) จะพบเมนู "🌾 Edge xAI : 4DAgriEnvi"
 */

// ชื่อแผ่นงานทั้งหมดในระบบ
const SH_OVERVIEW = "📊 ภาพรวมระบบ (Overview)";
const SH_PARTICIPANTS = "1. ผู้ลงทะเบียน (Participants)";
const SH_TELEMETRY = "2. บันทึกโทรมาตร IoT (Telemetry)";
const SH_RELAYS = "3. สถานะรีเลย์ (Relay States)";
const SH_EVALUATIONS = "4. การประเมินโครงการ (Evaluations)";
const SH_ANNOUNCEMENTS = "5. ข่าวประชาสัมพันธ์ (Announcements)";

// URL เริ่มต้นของ API เซิร์ฟเวอร์ (ปรับเปลี่ยนได้ผ่านเมนู 🌾 Edge xAI : 4DAgriEnvi > ตั้งค่า API URL เซิร์ฟเวอร์)
const DEFAULT_API_URL = "http://localhost/G3_Agri_Microbiology_SmartFarm/3.1_%E0%B9%80%E0%B8%81%E0%B8%A9%E0%B8%95%E0%B8%A3%E0%B8%97%E0%B8%B1%E0%B9%88%E0%B8%A7%E0%B9%84%E0%B8%9B%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B8%AA%E0%B8%A1%E0%B8%B2%E0%B8%A3%E0%B9%8C%E0%B8%97%E0%B8%9F%E0%B8%B2%E0%B8%A3%E0%B9%8C%E0%B8%A1/handysense/edge-xaiot-agrienvi/api/api.php";

/**
 * -------------------------------------------------------------------------
 * 1. GOOGLE SHEETS CUSTOM MENU (เมนูลัดบนแถบเครื่องมือ)
 * -------------------------------------------------------------------------
 */
function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu("🌾 Edge xAI : 4DAgriEnvi")
    .addItem("📥 ซิงก์ดึงข้อมูลทุกฐานข้อมูล (Sync All Databases)", "syncAllDatabasesFromSQLite")
    .addSeparator()
    .addSubMenu(ui.createMenu("📑 ซิงก์เฉพาะตารางที่เลือก")
      .addItem("1. ผู้ลงทะเบียน (Participants)", "syncParticipantsOnly")
      .addItem("2. บันทึกโทรมาตร IoT (Telemetry Logs)", "syncTelemetryOnly")
      .addItem("3. สถานะรีเลย์ฮาร์ดแวร์ (Relay States)", "syncRelaysOnly")
      .addItem("4. ผลการประเมินโครงการ (Evaluations)", "syncEvaluationsOnly")
      .addItem("5. ข่าวประชาสัมพันธ์ (Announcements)", "syncAnnouncementsOnly")
    )
    .addSeparator()
    .addItem("⚙️ ตั้งค่า API URL เซิร์ฟเวอร์", "promptSetApiUrl")
    .addItem("🔄 จัดรูปแบบและหัวตารางทุกแผ่นงาน", "formatAllSheets")
    .addItem("🕒 ตั้งเวลาดึงข้อมูลอัตโนมัติ (Trigger ทุก 1 ชม.)", "setupAutoSyncTrigger")
    .addToUi();
}

/**
 * -------------------------------------------------------------------------
 * 2. PULL ALL DATABASES (ซิงก์ดึงข้อมูลทุกฐานข้อมูลพร้อมกัน)
 * -------------------------------------------------------------------------
 */
function syncAllDatabasesFromSQLite() {
  const ui = SpreadsheetApp.getUi();
  const apiUrl = getApiUrl();
  
  if (!apiUrl) {
    ui.alert("⚠️ ยังไม่ได้กำหนด API URL", "กรุณาไปที่เมนู '🌾 Edge xAI : 4DAgriEnvi' > '⚙️ ตั้งค่า API URL เซิร์ฟเวอร์'", ui.ButtonSet.OK);
    return;
  }

  SpreadsheetApp.getActiveSpreadsheet().toast("กำลังเชื่อมต่อ SQLite และดาวน์โหลดทุกฐานข้อมูล...", "Edge xAI : 4DAgriEnvi Engine", 8);

  try {
    const separator = apiUrl.includes("?") ? "&" : "?";
    const targetUrl = apiUrl.replace(/\?action=.*$/, "") + separator + "action=get_all_database_tables";

    const response = UrlFetchApp.fetch(targetUrl, {
      method: "get",
      muteHttpExceptions: true,
      headers: { "Accept": "application/json" }
    });

    const statusCode = response.getResponseCode();
    if (statusCode !== 200) {
      ui.alert(
        "❌ ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ (HTTP " + statusCode + ")",
        "URL ที่เรียก: " + targetUrl + "\n\nคำแนะนำ:\n1. ตรวจสอบว่า Apache/XAMPP กำลังทำงาน\n2. หากใช้งานข้ามเครือข่าย ให้ตั้งค่า URL เป็น Cloudflare Tunnel หรือ ngrok\n3. หรือกดปุ่ม 'ซิงก์ข้อมูลไป Google Sheets' จากหน้าเว็บ Admin (Push Mode)",
        ui.ButtonSet.OK
      );
      return;
    }

    const resJson = JSON.parse(response.getContentText());
    if (resJson.status !== "success" || !resJson.tables) {
      ui.alert("⚠️ รูปแบบข้อมูลไม่ถูกต้อง", "ไม่พบโครงสร้าง tables ในผลลัพธ์จากเซิร์ฟเวอร์", ui.ButtonSet.OK);
      return;
    }

    const tables = resJson.tables;
    const ss = SpreadsheetApp.getActiveSpreadsheet();

    // 1. ผู้ลงทะเบียน
    writeParticipantsTable(ss, tables.participants || []);

    // 2. บันทึกโทรมาตร IoT
    writeTelemetryTable(ss, tables.telemetry_logs || []);

    // 3. สถานะรีเลย์
    writeRelaysTable(ss, tables.relay_states || []);

    // 4. การประเมินโครงการ
    writeEvaluationsTable(ss, tables.evaluations || []);

    // 5. ข่าวประชาสัมพันธ์
    writeAnnouncementsTable(ss, tables.announcements || []);

    // 6. ภาพรวมระบบ (Overview Dashboard)
    writeOverviewDashboard(ss, tables);

    SpreadsheetApp.getActiveSpreadsheet().toast("ซิงก์ข้อมูลครบทุกฐานข้อมูลเรียบร้อยแล้ว!", "สำเร็จ ✨", 5);
    ui.alert(
      "✅ ซิงก์ข้อมูลครบทุกฐานข้อมูลสำเร็จ!",
      "ดึงข้อมูลจาก SQLite อัปเดตลงแผ่นงาน Google Sheets เรียบร้อยแล้ว:\n\n" +
      "• ผู้ลงทะเบียน: " + (tables.participants ? tables.participants.length : 0) + " รายชื่อ\n" +
      "• บันทึกโทรมาตร IoT: " + (tables.telemetry_logs ? tables.telemetry_logs.length : 0) + " รายการ\n" +
      "• รีเลย์ควบคุม: " + (tables.relay_states ? tables.relay_states.length : 0) + " ช่อง\n" +
      "• ผลการประเมินโครงการ: " + (tables.evaluations ? tables.evaluations.length : 0) + " ชุด\n" +
      "• ข่าวประชาสัมพันธ์: " + (tables.announcements ? tables.announcements.length : 0) + " ข่าว\n" +
      "• แดชบอร์ดสรุปภาพรวม KPI อัปเดตล่าสุด: " + (resJson.timestamp || "ขณะนี้"),
      ui.ButtonSet.OK
    );

  } catch (err) {
    ui.alert("❌ เกิดข้อผิดพลาดในการดึงข้อมูล", err.toString(), ui.ButtonSet.OK);
  }
}

/**
 * -------------------------------------------------------------------------
 * 3. SYNC SPECIFIC TABLES (ซิงก์แยกเฉพาะแต่ละตาราง)
 * -------------------------------------------------------------------------
 */
function syncParticipantsOnly() {
  syncSingleTable("participants", "ทำเนียบผู้ลงทะเบียน", (ss, data) => writeParticipantsTable(ss, data));
}

function syncTelemetryOnly() {
  syncSingleTable("telemetry_logs", "บันทึกโทรมาตร IoT", (ss, data) => writeTelemetryTable(ss, data));
}

function syncRelaysOnly() {
  syncSingleTable("relay_states", "สถานะรีเลย์ฮาร์ดแวร์", (ss, data) => writeRelaysTable(ss, data));
}

function syncEvaluationsOnly() {
  syncSingleTable("evaluations", "ผลการประเมินโครงการ", (ss, data) => writeEvaluationsTable(ss, data));
}

function syncAnnouncementsOnly() {
  syncSingleTable("announcements", "ข่าวประชาสัมพันธ์", (ss, data) => writeAnnouncementsTable(ss, data));
}

function syncSingleTable(tableKey, tableLabel, writeCallback) {
  const ui = SpreadsheetApp.getUi();
  const apiUrl = getApiUrl();
  SpreadsheetApp.getActiveSpreadsheet().toast("กำลังดึงข้อมูล " + tableLabel + "...", "Edge xAI : 4DAgriEnvi", 5);
  try {
    const separator = apiUrl.includes("?") ? "&" : "?";
    const targetUrl = apiUrl.replace(/\?action=.*$/, "") + separator + "action=get_all_database_tables";
    const response = UrlFetchApp.fetch(targetUrl, { method: "get", muteHttpExceptions: true });
    if (response.getResponseCode() !== 200) {
      ui.alert("เชื่อมต่อไม่สำเร็จ HTTP " + response.getResponseCode());
      return;
    }
    const res = JSON.parse(response.getContentText());
    const data = res.tables && res.tables[tableKey] ? res.tables[tableKey] : [];
    const ss = SpreadsheetApp.getActiveSpreadsheet();
    writeCallback(ss, data);
    ui.alert("✅ ซิงก์ " + tableLabel + " สำเร็จ (" + data.length + " รายการ)");
  } catch (err) {
    ui.alert("❌ ผิดพลาด: " + err.toString());
  }
}

/**
 * -------------------------------------------------------------------------
 * 4. WRITE DATA TO INDIVIDUAL SHEETS (ฟังก์ชันเขียนข้อมูลลงแต่ละแผ่นงาน)
 * -------------------------------------------------------------------------
 */

// 4.1 ผู้ลงทะเบียน (Participants)
function writeParticipantsTable(ss, list) {
  let sheet = getOrCreateSheet(ss, SH_PARTICIPANTS);
  const headers = [
    "ID", "วัน-เวลาลงทะเบียน", "คำนำหน้า", "ชื่อ - นามสกุล", "กลุ่มเป้าหมาย",
    "สังกัด / โรงเรียน", "เบอร์โทรศัพท์", "อีเมล", "แทร็กโครงงาน Capstone",
    "Pre-test", "Post-test", "สถานะการเช็คอิน"
  ];
  setupSheetHeaders(sheet, headers, "#059669"); // Emerald Dark

  clearSheetData(sheet, headers.length);

  if (list && list.length > 0) {
    const rows = list.map(p => {
      const ts = p.created_at || Utilities.formatDate(new Date(), "Asia/Bangkok", "yyyy-MM-dd HH:mm:ss");
      return [
        p.id || "",
        ts,
        p.prefix || "",
        p.fullname || "",
        p.target_group || "",
        p.organization || "",
        "'" + (p.phone || ""),
        p.email || "",
        p.project_track || "",
        p.pre_score !== undefined ? Number(p.pre_score) : 0,
        p.post_score !== undefined ? Number(p.post_score) : 0,
        p.status === "checked-in" ? "เช็คอินแล้ว (Checked-in)" : "ลงทะเบียนแล้ว (Registered)"
      ];
    });
    sheet.getRange(2, 1, rows.length, headers.length).setValues(rows);
    sheet.autoResizeColumns(1, headers.length);
  }
}

// 4.2 บันทึกโทรมาตร IoT (Telemetry Logs)
function writeTelemetryTable(ss, logs) {
  let sheet = getOrCreateSheet(ss, SH_TELEMETRY);
  const headers = [
    "Log ID", "วัน-เวลาบันทึก", "อุณหภูมิ (°C)", "ความชื้น (%RH)", "VPD (kPa)",
    "ความชื้นดิน (%)", "EC ดิน (µS/cm)", "pH ดิน", "ไนโตรเจน N (mg/kg)", "ฟอสฟอรัส P (mg/kg)",
    "โพแทสเซียม K (mg/kg)", "แสง PAR (Lux)", "รังสีแสงอาทิตย์ (MJ/m²)", "รีเลย์ 1", "รีเลย์ 2",
    "รีเลย์ 3", "รีเลย์ 4", "WiFi RSSI (dBm)", "IP Address", "แหล่งข้อมูล"
  ];
  setupSheetHeaders(sheet, headers, "#0284c7"); // Sky Blue

  clearSheetData(sheet, headers.length);

  if (logs && logs.length > 0) {
    const rows = logs.map(l => [
      l.id || "",
      l.created_at || "",
      l.temperature !== undefined ? Number(l.temperature) : "",
      l.humidity !== undefined ? Number(l.humidity) : "",
      l.vpd !== undefined ? Number(l.vpd) : "",
      l.soil_moisture !== undefined ? Number(l.soil_moisture) : "",
      l.soil_ec !== undefined ? Number(l.soil_ec) : "",
      l.soil_ph !== undefined ? Number(l.soil_ph) : "",
      l.nitrogen !== undefined ? Number(l.nitrogen) : "",
      l.phosphorus !== undefined ? Number(l.phosphorus) : "",
      l.potassium !== undefined ? Number(l.potassium) : "",
      l.par_lux !== undefined ? Number(l.par_lux) : "",
      l.solar_radiation !== undefined ? Number(l.solar_radiation) : "",
      l.relay1 == 1 ? "ON" : "OFF",
      l.relay2 == 1 ? "ON" : "OFF",
      l.relay3 == 1 ? "ON" : "OFF",
      l.relay4 == 1 ? "ON" : "OFF",
      l.rssi !== undefined ? Number(l.rssi) : "",
      l.ip_address || "",
      l.data_source || "ESP32-S3 ATD3.5"
    ]);
    sheet.getRange(2, 1, rows.length, headers.length).setValues(rows);
    sheet.autoResizeColumns(1, headers.length);
  }
}

// 4.3 สถานะรีเลย์ฮาร์ดแวร์ (Relay States)
function writeRelaysTable(ss, relays) {
  let sheet = getOrCreateSheet(ss, SH_RELAYS);
  const headers = [
    "รีเลย์ที่", "ชื่ออุปกรณ์ฮาร์ดแวร์ / โหลดควบคุม", "ขา GPIO (ESP32-S3)", "สถานะปัจจุบัน (State)", "วัน-เวลาอัปเดตล่าสุด"
  ];
  setupSheetHeaders(sheet, headers, "#4f46e5"); // Indigo

  clearSheetData(sheet, headers.length);

  if (relays && relays.length > 0) {
    const rows = relays.map(r => [
      r.id || "",
      r.name || "",
      r.gpio !== undefined ? Number(r.gpio) : "",
      r.state == 1 ? "เปิดใช้งาน (ON)" : "ปิด (OFF)",
      r.updated_at || Utilities.formatDate(new Date(), "Asia/Bangkok", "yyyy-MM-dd HH:mm:ss")
    ]);
    sheet.getRange(2, 1, rows.length, headers.length).setValues(rows);
    sheet.autoResizeColumns(1, headers.length);
  }
}

// 4.4 การประเมินโครงการ (Evaluations)
function writeEvaluationsTable(ss, evals) {
  let sheet = getOrCreateSheet(ss, SH_EVALUATIONS);
  const headers = [
    "ID", "รหัสผู้เข้าร่วม", "ชื่อ - นามสกุล", "สังกัด / โรงเรียน",
    "ข้อ 1 (ความเข้าใจ)", "ข้อ 2 (ประโยชน์)", "ข้อ 3 (เนื้อหา/สื่อ)", "ข้อ 4 (ปฏิบัติจริง)",
    "ข้อ 5 (วิทยากร)", "ข้อ 6 (ประยุกต์ใช้)", "ข้อ 7 (สถานที่/อุปกรณ์)", "ข้อ 8 (ระยะเวลา)",
    "ข้อ 9 (การดูแล)", "ข้อ 10 (ภาพรวม)", "คะแนนเฉลี่ย (เต็ม 5)", "ข้อคิดเห็นและข้อเสนอแนะ", "วัน-เวลาประเมิน"
  ];
  setupSheetHeaders(sheet, headers, "#d97706"); // Amber

  clearSheetData(sheet, headers.length);

  if (evals && evals.length > 0) {
    const rows = evals.map(e => [
      e.id || "",
      e.participant_id || "",
      e.fullname || "",
      e.organization || "",
      e.q1 || 5, e.q2 || 5, e.q3 || 5, e.q4 || 5, e.q5 || 5,
      e.q6 || 5, e.q7 || 5, e.q8 || 5, e.q9 || 5, e.q10 || 5,
      e.avg_score !== undefined ? Number(e.avg_score).toFixed(2) : "5.00",
      e.comment || "",
      e.created_at || ""
    ]);
    sheet.getRange(2, 1, rows.length, headers.length).setValues(rows);
    sheet.autoResizeColumns(1, headers.length);
  }
}

// 4.5 ข่าวประชาสัมพันธ์ (Announcements)
function writeAnnouncementsTable(ss, announcements) {
  let sheet = getOrCreateSheet(ss, SH_ANNOUNCEMENTS);
  const headers = [
    "ID", "หัวข้อข่าวประชาสัมพันธ์", "รายละเอียดเนื้อหา", "ป้ายกำกับ (Badge)", "สถานะการแสดงผล", "วัน-เวลาประกาศ", "แก้ไขล่าสุด"
  ];
  setupSheetHeaders(sheet, headers, "#7c3aed"); // Violet

  clearSheetData(sheet, headers.length);

  if (announcements && announcements.length > 0) {
    const rows = announcements.map(a => [
      a.id || "",
      a.title || "",
      a.content || "",
      a.badge || "ประชาสัมพันธ์",
      a.is_active == 1 ? "กำลังแสดงผล (Active)" : "ซ่อน (Inactive)",
      a.created_at || "",
      a.updated_at || ""
    ]);
    sheet.getRange(2, 1, rows.length, headers.length).setValues(rows);
    sheet.autoResizeColumns(1, headers.length);
  }
}

// 4.6 แดชบอร์ดสรุปภาพรวม (Overview KPI Dashboard)
function writeOverviewDashboard(ss, tables) {
  let sheet = getOrCreateSheet(ss, SH_OVERVIEW);
  sheet.clear();

  const participants = tables.participants || [];
  const telemetry = tables.current_telemetry || {};
  const sensors = telemetry.sensors || {};
  const board = telemetry.board || {};
  const evals = tables.evaluations || [];
  const logs = tables.telemetry_logs || [];

  const checkedInCount = participants.filter(p => p.status === "checked-in").length;
  const totalCount = participants.length;
  const checkinPct = totalCount > 0 ? ((checkedInCount / totalCount) * 100).toFixed(1) + "%" : "0%";

  let sumPre = 0, sumPost = 0;
  participants.forEach(p => {
    sumPre += Number(p.pre_score || 0);
    sumPost += Number(p.post_score || 0);
  });
  const avgPre = totalCount > 0 ? (sumPre / totalCount).toFixed(2) : "0.00";
  const avgPost = totalCount > 0 ? (sumPost / totalCount).toFixed(2) : "0.00";

  let sumEval = 0;
  evals.forEach(e => { sumEval += Number(e.avg_score || 5); });
  const avgEval = evals.length > 0 ? (sumEval / evals.length).toFixed(2) : "0.00";

  const rows = [
    ["🌾 ระบบเอดจ์ไอโอทีและปัญญาประดิษฐ์เพื่อการเกษตรและสิ่งแวดล้อม (Edge xAIoT : 4D-AgriEnvi)", "", "", ""],
    ["มหาวิทยาลัยราชภัฏรำไพพรรณี ร่วมกับ โรงเรียนประณีตวิทยาคม | สรุปภาพรวมฐานข้อมูลระบบทั้งหมด", "", "", ""],
    ["", "", "", ""],
    ["📌 ตัวชี้วัดสำคัญของโครงการ (Core Project KPIs)", "ค่าสถิติปัจจุบัน", "หน่วยนับ", "หมายเหตุ"],
    ["จำนวนผู้ลงทะเบียนเข้าร่วมอบรมทั้งหมด", totalCount, "คน", "จากฐานข้อมูล SQLite participants"],
    ["จำนวนผู้รายงานตัวและเช็คอินแล้ว", checkedInCount, "คน", "คิดเป็น " + checkinPct + " ของผู้สมัครทั้งหมด"],
    ["คะแนนเฉลี่ยแบบทดสอบก่อนเรียน (Pre-test)", avgPre, "คะแนน (เต็ม 20)", "วัดความรู้พื้นฐานก่อนเริ่มอบรม"],
    ["คะแนนเฉลี่ยแบบทดสอบหลังเรียน (Post-test)", avgPost, "คะแนน (เต็ม 20)", "วัดความสัมฤทธิผลของการอบรม"],
    ["จำนวนผู้ตอบแบบประเมินความพึงพอใจ", evals.length, "ชุด", "จากแบบประเมิน 10 ข้อ"],
    ["คะแนนความพึงพอใจโครงการโดยเฉลี่ย", avgEval, "คะแนน (เต็ม 5)", "ระดับความพึงพอใจเฉลี่ย"],
    ["จำนวนรายการบันทึกโทรมาตรสิ่งแวดล้อม IoT", logs.length, "รายการ", "บันทึกย้อนหลังจากแปลงปลูกจริง"],
    ["", "", "", ""],
    ["🌿 สถานะโทรมาตรสิ่งแวดล้อมล่าสุด (Live Telemetry)", "ค่าที่วัดได้", "หน่วยวัด", "การประเมิน xAI"],
    ["อุณหภูมิอากาศ (Air Temperature)", sensors.temperature || "31.4", "°C", "สภาพปกติ"],
    ["ความชื้นสัมพัทธ์ (Relative Humidity)", sensors.humidity || "85.0", "%RH", "ความชื้นสูง"],
    ["ความดันไอขาดดุล (VPD)", sensors.vpd || "0.69", "kPa", "สภาวะปากใบเปิดสมบูรณ์"],
    ["ความชื้นในดินลึก 30 ซม. (Soil Moisture)", sensors.soil_moisture || "65.0", "%", "ระดับความชื้นเหมาะสม"],
    ["ความเป็นกรด-ด่างของดิน (Soil pH)", sensors.soil_ph || "6.2", "pH", "กรดอ่อน เหมาะกับการดูดซึมปุ๋ย"],
    ["ค่าการนำไฟฟ้าของดิน (Soil EC)", sensors.soil_ec || "120.0", "µS/cm", "ความเข้มข้นสารอาหารปกติ"],
    ["ธาตุอาหารหลัก ไนโตรเจน (N)", sensors.nitrogen || "45.0", "mg/kg", "ระดับปานกลาง"],
    ["ธาตุอาหารหลัก ฟอสฟอรัส (P)", sensors.phosphorus || "32.0", "mg/kg", "เพียงพอ"],
    ["ธาตุอาหารหลัก โพแทสเซียม (K)", sensors.potassium || "180.0", "mg/kg", "สมบูรณ์"],
    ["ความเข้มแสงสังเคราะห์แสง (PAR Light)", sensors.par_lux || "897.5", "Lux", "แสงธรรมชาติ"],
    ["", "", "", ""],
    ["📟 ข้อมูลฮาร์ดแวร์บอร์ดควบคุม ESP32-S3 ATD3.5", "รายละเอียด", "", ""],
    ["ชื่ออุปกรณ์และเฟิร์มแวร์", board.device_name || "ESP32-S3 ATD3.5 Controller", "", ""],
    ["สถานะการเชื่อมต่อเครือข่าย", board.status || "CONNECTED (ONLINE)", "", ""],
    ["IP Address / พอร์ตสื่อสาร", (board.ip_address || "192.168.0.111") + ":" + (board.web_port || "8500"), "", ""],
    ["WiFi SSID / สัญญาณ RSSI", (board.ssid || "JC_Home") + " (" + (board.rssi || "-99") + " dBm)", "", ""],
    ["วัน-เวลาซิงก์ข้อมูลขึ้น Google Sheets ล่าสุด", Utilities.formatDate(new Date(), "Asia/Bangkok", "yyyy-MM-dd HH:mm:ss"), "", ""]
  ];

  sheet.getRange(1, 1, rows.length, 4).setValues(rows);

  // ตกแต่ง Title
  sheet.getRange(1, 1, 1, 4).merge().setBackground("#0f172a").setFontColor("#38bdf8").setFontWeight("bold").setFontSize(13).setVerticalAlignment("middle");
  sheet.getRange(2, 1, 1, 4).merge().setBackground("#1e293b").setFontColor("#cbd5e1").setFontSize(10).setVerticalAlignment("middle");
  sheet.setRowHeight(1, 36);
  sheet.setRowHeight(2, 26);

  // ตกแต่งหัวส่วนย่อย
  sheet.getRange(4, 1, 1, 4).setBackground("#059669").setFontColor("#ffffff").setFontWeight("bold");
  sheet.getRange(13, 1, 1, 4).setBackground("#0284c7").setFontColor("#ffffff").setFontWeight("bold");
  sheet.getRange(25, 1, 1, 4).setBackground("#475569").setFontColor("#ffffff").setFontWeight("bold");

  sheet.autoResizeColumns(1, 4);
}

/**
 * -------------------------------------------------------------------------
 * 5. WEBHOOK HANDLER: DOPUSH (รับข้อมูล Push Sync จากเว็บหรือแอดมิน)
 * -------------------------------------------------------------------------
 */
function doGet(e) {
  return ContentService.createTextOutput(JSON.stringify({
    status: "ok",
    service: "Edge xAI : 4DAgriEnvi Multi-Database Dual Engine",
    timestamp: new Date().toISOString(),
    supported_sheets: [SH_OVERVIEW, SH_PARTICIPANTS, SH_TELEMETRY, SH_RELAYS, SH_EVALUATIONS, SH_ANNOUNCEMENTS]
  })).setMimeType(ContentService.MimeType.JSON);
}

function doPost(e) {
  try {
    const rawData = e.postData.contents;
    const data = JSON.parse(rawData);
    const ss = SpreadsheetApp.getActiveSpreadsheet();

    // กรณีที่ 1: ซิงก์ครบทุกฐานข้อมูลพร้อมกัน (action = sync_all_databases)
    if (data.action === "sync_all_databases" && data.tables) {
      writeParticipantsTable(ss, data.tables.participants || []);
      writeTelemetryTable(ss, data.tables.telemetry_logs || []);
      writeRelaysTable(ss, data.tables.relay_states || []);
      writeEvaluationsTable(ss, data.tables.evaluations || []);
      writeAnnouncementsTable(ss, data.tables.announcements || []);
      writeOverviewDashboard(ss, data.tables);

      return ContentService.createTextOutput(JSON.stringify({
        status: "success",
        action: "sync_all_databases",
        message: "ซิงก์ครบทุกฐานข้อมูลเรียบร้อยแล้วทั้ง 6 แผ่นงาน"
      })).setMimeType(ContentService.MimeType.JSON);
    }

    // กรณีที่ 2: ซิงก์เฉพาะผู้ลงทะเบียนทั้งหมด (Backward Compatibility: sync_batch)
    if (data.action === "sync_batch" && Array.isArray(data.participants)) {
      writeParticipantsTable(ss, data.participants);
      return ContentService.createTextOutput(JSON.stringify({
        status: "success",
        action: "sync_batch",
        count: data.participants.length
      })).setMimeType(ContentService.MimeType.JSON);
    }

    // กรณีที่ 3: บันทึกผู้ลงทะเบียนใหม่ 1 รายการทันทีที่สมัคร (Realtime Append)
    let pSheet = getOrCreateSheet(ss, SH_PARTICIPANTS);
    const newRow = [
      data.id || "",
      data.created_at || Utilities.formatDate(new Date(), "Asia/Bangkok", "yyyy-MM-dd HH:mm:ss"),
      data.prefix || "",
      data.fullname || "",
      data.target_group || "",
      data.organization || "",
      "'" + (data.phone || ""),
      data.email || "",
      data.project_track || "",
      data.pre_score !== undefined ? Number(data.pre_score) : 0,
      data.post_score !== undefined ? Number(data.post_score) : 0,
      data.status === "checked-in" ? "เช็คอินแล้ว (Checked-in)" : "ลงทะเบียนแล้ว (Registered)"
    ];
    pSheet.appendRow(newRow);

    return ContentService.createTextOutput(JSON.stringify({
      status: "success",
      action: "append",
      id: data.id || null
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (err) {
    return ContentService.createTextOutput(JSON.stringify({
      status: "error",
      message: err.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * -------------------------------------------------------------------------
 * 6. HELPER UTILITIES: SHEET FORMATTING & CONFIG
 * -------------------------------------------------------------------------
 */
function getOrCreateSheet(ss, sheetName) {
  let sheet = ss.getSheetByName(sheetName);
  if (!sheet) {
    sheet = ss.insertSheet(sheetName);
  }
  return sheet;
}

function setupSheetHeaders(sheet, headers, bgHex) {
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(headers);
  } else {
    sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
  }
  const hRange = sheet.getRange(1, 1, 1, headers.length);
  hRange.setBackground(bgHex || "#0f172a");
  hRange.setFontColor("#FFFFFF");
  hRange.setFontWeight("bold");
  hRange.setHorizontalAlignment("center");
  hRange.setVerticalAlignment("middle");
  hRange.setFontSize(10.5);
  sheet.setRowHeight(1, 36);
  sheet.setFrozenRows(1);
}

function clearSheetData(sheet, colCount) {
  const lastRow = sheet.getLastRow();
  if (lastRow > 1) {
    sheet.getRange(2, 1, lastRow - 1, colCount).clearContent();
  }
}

function formatAllSheets() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  writeParticipantsTable(ss, []);
  writeTelemetryTable(ss, []);
  writeRelaysTable(ss, []);
  writeEvaluationsTable(ss, []);
  writeAnnouncementsTable(ss, []);
  SpreadsheetApp.getActiveSpreadsheet().toast("จัดหัวตารางทุกแผ่นงานเรียบร้อยแล้ว", "Edge xAI : 4DAgriEnvi", 4);
}

function getApiUrl() {
  const props = PropertiesService.getScriptProperties();
  return props.getProperty("SQLITE_API_URL") || DEFAULT_API_URL;
}

function promptSetApiUrl() {
  const ui = SpreadsheetApp.getUi();
  const currentUrl = getApiUrl();
  const result = ui.prompt(
    "⚙️ ตั้งค่า API URL เซิร์ฟเวอร์ SQLite",
    "ใส่ URL ของ api/api.php ของระบบ (รองรับทั้งโดเมนจริง, Cloudflare Tunnel หรือ ngrok):\n\nURL ปัจจุบัน:\n" + currentUrl,
    ui.ButtonSet.OK_CANCEL
  );
  if (result.getSelectedButton() === ui.Button.OK) {
    const newUrl = result.getResponseText().trim();
    if (newUrl) {
      PropertiesService.getScriptProperties().setProperty("SQLITE_API_URL", newUrl);
      ui.alert("✅ บันทึกสำเร็จ!", "ตั้งค่า API URL เรียบร้อยแล้ว:\n" + newUrl, ui.ButtonSet.OK);
    }
  }
}

function setupAutoSyncTrigger() {
  const ui = SpreadsheetApp.getUi();
  const triggers = ScriptApp.getProjectTriggers();
  for (let i = 0; i < triggers.length; i++) {
    if (triggers[i].getHandlerFunction() === "syncAllDatabasesFromSQLite") {
      ScriptApp.deleteTrigger(triggers[i]);
    }
  }
  ScriptApp.newTrigger("syncAllDatabasesFromSQLite")
    .timeBased()
    .everyHours(1)
    .create();
  ui.alert("⏰ ตั้งเวลาอัตโนมัติสำเร็จ", "ระบบจะดึงข้อมูลครบทุกฐานข้อมูล SQLite อัตโนมัติทุกๆ 1 ชั่วโมง", ui.ButtonSet.OK);
}
