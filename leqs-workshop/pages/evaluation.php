<?php
/**
 * LEQs-xAI Workshop Evaluation Portal
 * แบบประเมินความพึงพอใจการอบรมเชิงปฏิบัติการ
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
session_start();

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';

// Fetch participants for selection dropdown
$participants = [];
if (file_exists($json_file)) {
    $participants = json_decode(file_get_contents($json_file), true) ?: [];
}

$eval_saved = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_eval'])) {
    $participant_id = intval($_POST['participant_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    // Extract 10 question scores
    $q_scores = [];
    $total_score = 0;
    for ($i = 1; $i <= 10; $i++) {
        $score = intval($_POST['q' . $i] ?? 5);
        $score = max(1, min(5, $score)); // clamp 1-5
        $q_scores[$i] = $score;
        $total_score += $score;
    }
    $avg_score = round($total_score / 10.0, 2);

    // Find participant details
    $fullname = 'ผู้เข้าร่วมอบรม';
    $organization = 'ทั่วไป';
    foreach ($participants as $p) {
        if (($p['id'] ?? 0) == $participant_id) {
            $fullname = $p['fullname'] ?? $fullname;
            $organization = $p['organization'] ?? $organization;
            break;
        }
    }

    // Save to SQLite3
    if (class_exists('SQLite3') && file_exists($db_file)) {
        try {
            $db = new SQLite3($db_file);
            $db->exec("CREATE TABLE IF NOT EXISTS evaluations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                participant_id INTEGER,
                fullname TEXT NOT NULL,
                organization TEXT,
                q1 INTEGER, q2 INTEGER, q3 INTEGER, q4 INTEGER, q5 INTEGER,
                q6 INTEGER, q7 INTEGER, q8 INTEGER, q9 INTEGER, q10 INTEGER,
                avg_score REAL,
                comment TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            $stmt = $db->prepare("INSERT INTO evaluations (participant_id, fullname, organization, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, avg_score, comment)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $participant_id, SQLITE3_INTEGER);
            $stmt->bindValue(2, $fullname, SQLITE3_TEXT);
            $stmt->bindValue(3, $organization, SQLITE3_TEXT);
            for ($k = 1; $k <= 10; $k++) {
                $stmt->bindValue($k + 3, $q_scores[$k], SQLITE3_INTEGER);
            }
            $stmt->bindValue(14, $avg_score, SQLITE3_FLOAT);
            $stmt->bindValue(15, $comment, SQLITE3_TEXT);
            $stmt->execute();

            // Sync to JSON
            $eval_json_file = $data_dir . '/evaluations.json';
            $all_evals = [];
            $res = $db->query("SELECT * FROM evaluations ORDER BY id DESC");
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                $all_evals[] = $row;
            }
            @file_put_contents($eval_json_file, json_encode($all_evals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $eval_saved = true;
        } catch (Exception $e) {
            $error_msg = "เกิดข้อผิดพลาดในการบันทึก: " . $e->getMessage();
        }
    }
}

$questions = [
    1 => '1. ความเหมาะสมของระยะเวลาและกำหนดการจัดอบรมเชิงปฏิบัติการ',
    2 => '2. ความรู้ความเข้าใจในเนื้อหา Edge AI และ Deep Learning ในภาคการเกษตร',
    3 => '3. การฝึกปฏิบัติประกอบบอร์ด ESP32-S3 และการต่อวงจรเซนเซอร์สิ่งแวดล้อม',
    4 => '4. ความสะดวกและประโยชน์ของระบบ LEQs xAI IoT และ Mobile Dashboard',
    5 => '5. การนำโมเดล Computer Vision วิเคราะห์โรคและสุขภาพใบพืชไปใช้จริง',
    6 => '6. ความรู้ความสามารถและการถ่ายทอดความรู้ของทีมวิทยากรและผู้ช่วยวิทยากร',
    7 => '7. ความพร้อมของสื่อการสอน เอกสารคู่มือปฏิบัติการ และชุดอุปกรณ์ทดลอง',
    8 => '8. ความเหมาะสมของสถานที่ การอำนวยความสะดวก ระบบสัญญาณอินเทอร์เน็ต และอาหาร/เครื่องดื่ม',
    9 => '9. ความคุ้มค่าและประโยชน์ในการนำความรู้ไปประยุกต์ใช้ในแปลงเพาะปลูกหรือสถานศึกษา',
    10 => '10. ความพึงพอใจในภาพรวมต่อการจัดกิจกรรม LEQs-xAI Agri-Tech Workshop'
];
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบประเมินความพึงพอใจ | LEQs-xAI 2026</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between hero-pattern font-sans">

    <!-- Top Navbar -->
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-3.5 px-4 sm:px-6 fixed w-full top-0 z-50">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-cyan-500 flex items-center justify-center text-slate-950 font-bold text-lg sm:text-xl shadow-lg shadow-cyan-500/20 shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <div class="font-bold text-sm sm:text-base text-white">LEQs-xAI Workshop</div>
                    <div class="text-[9px] sm:text-[11px] text-slate-400">แบบประเมินความพึงพอใจโครงการ</div>
                </div>
            </a>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <a href="certificate.php" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 border border-amber-500/30 font-bold text-xs transition flex items-center gap-1.5 shrink-0" title="รับเกียรติบัตร">
                    <i class="fa-solid fa-certificate"></i> <span class="hidden sm:inline">รับเกียรติบัตร</span>
                </a>
                <a href="../index.php" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition flex items-center gap-1.5" title="กลับหน้าหลัก">
                    <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">หน้าหลัก</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 sm:pt-28 pb-16 px-3 sm:px-4 max-w-3xl mx-auto w-full">
        <div class="p-5 sm:p-10 rounded-2xl sm:rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl">
            
            <div class="text-center mb-6 sm:mb-8">
                <span class="px-3.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                    Satisfaction Evaluation
                </span>
                <h1 class="text-xl sm:text-3xl font-black text-white mt-2">
                    แบบประเมินความพึงพอใจการอบรม
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-2">
                    โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI 2026)<br>
                    คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี
                </p>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="evalForm" class="space-y-6">
                <input type="hidden" name="submit_eval" value="1">

                <!-- Participant Selection -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                        <i class="fa-solid fa-user-check text-cyan-400 mr-1.5"></i> เลือกชื่อผู้เข้าร่วมการอบรม
                    </label>
                    <select name="participant_id" id="participant_id" required class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-cyan-500 transition">
                        <option value="">-- กรุณาเลือกชื่อของท่าน --</option>
                        <?php foreach ($participants as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo htmlspecialchars(($p['prefix'] ?? '') . $p['fullname'] . ' (' . ($p['organization'] ?? '-') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Rating Criteria Rating Legend -->
                <div class="bg-cyan-500/10 border border-cyan-500/20 p-4 rounded-2xl flex flex-wrap items-center justify-between text-xs text-cyan-300 gap-2">
                    <span class="font-bold"><i class="fa-solid fa-circle-info mr-1"></i> เกณฑ์ระดับคะแนน:</span>
                    <span>5 = มากที่สุด</span>
                    <span>4 = มาก</span>
                    <span>3 = ปานกลาง</span>
                    <span>2 = น้อย</span>
                    <span>1 = น้อยที่สุด</span>
                </div>

                <!-- Evaluation 10 Questions -->
                <div class="space-y-4">
                    <?php foreach ($questions as $q_num => $q_text): ?>
                        <div class="p-5 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                            <div class="text-sm font-semibold text-white">
                                <?php echo htmlspecialchars($q_text); ?>
                            </div>
                            <div class="grid grid-cols-5 gap-2 text-center text-xs">
                                <?php for ($val = 5; $val >= 1; $val--): ?>
                                    <label class="cursor-pointer group flex flex-col items-center">
                                        <input type="radio" name="q<?php echo $q_num; ?>" value="<?php echo $val; ?>" <?php echo $val === 5 ? 'checked' : ''; ?> class="peer sr-only">
                                        <div class="w-full py-2.5 rounded-xl border border-slate-800 bg-slate-900 peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-cyan-500 peer-checked:text-slate-950 peer-checked:font-bold peer-checked:border-transparent group-hover:border-slate-700 transition">
                                            <?php echo $val; ?>
                                        </div>
                                        <span class="text-[10px] text-slate-500 peer-checked:text-emerald-400 mt-1">
                                            <?php 
                                            $labels = [5 => 'มากที่สุด', 4 => 'มาก', 3 => 'ปานกลาง', 2 => 'น้อย', 1 => 'น้อยที่สุด'];
                                            echo $labels[$val]; 
                                            ?>
                                        </span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Feedback Comments -->
                <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                        <i class="fa-solid fa-comment-dots text-emerald-400 mr-1.5"></i> ข้อเสนอแนะและความคิดเห็นเพิ่มเติมสำหรับการพัฒนา
                    </label>
                    <textarea name="comment" rows="3" placeholder="ระบุข้อคิดเห็น ข้อเสนอแนะ หรือสิ่งที่ต้องการให้จัดอบรมเพิ่มเติม..." class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500 transition"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-slate-950 font-bold text-base shadow-xl shadow-cyan-500/10 hover:scale-[1.01] transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> ส่งแบบประเมินและไปยังระบบเกียรติบัตร
                </button>
            </form>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI) คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี © 2026
    </footer>

    <?php if ($eval_saved): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'ส่งแบบประเมินเรียบร้อยแล้ว!',
            html: 'ขอขอบพระคุณสำหรับข้อคิดเห็นอันเป็นประโยชน์อย่างยิ่ง<br>ท่านสามารถเข้าสู่ระบบเพื่อตรวจสอบสิทธิ์และรับเกียรติบัตรได้ทันที',
            confirmButtonColor: '#10B981',
            confirmButtonText: 'ไปที่หน้ารับเกียรติบัตร'
        }).then(() => {
            window.location.href = 'certificate.php?student_id=<?php echo $participant_id; ?>';
        });
    </script>
    <?php endif; ?>

</body>
</html>
