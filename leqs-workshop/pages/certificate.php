<?php
/**
 * LEQs-xAI Certificate Eligibility & Verification Portal
 * ตรวจสอบสิทธิ์และรับเกียรติบัตรออนไลน์
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
session_start();

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';
$eval_file = $data_dir . '/evaluations.json';

// Fetch participants
$participants = [];
if (file_exists($json_file)) {
    $participants = json_decode(file_get_contents($json_file), true) ?: [];
}

$student_id = trim($_GET['student_id'] ?? '');
$student_info = null;
$status = [
    'pretest' => false,
    'posttest' => false,
    'evaluation' => false
];
$can_download = false;
$error_msg = '';

if (!empty($student_id)) {
    // 1. Find Participant by ID or Fullname match
    foreach ($participants as $p) {
        if (($p['id'] ?? '') == $student_id || ($p['fullname'] ?? '') === $student_id) {
            $student_info = $p;
            break;
        }
    }

    if ($student_info) {
        $pid = $student_info['id'];
        
        // 2. Check Pre-test & Post-test scores
        $status['pretest'] = isset($student_info['pre_score']) && $student_info['pre_score'] > 0;
        $status['posttest'] = isset($student_info['post_score']) && $student_info['post_score'] > 0;

        // 3. Check Evaluation completion from evaluations table / JSON
        if (file_exists($eval_file)) {
            $evals = json_decode(file_get_contents($eval_file), true) ?: [];
            foreach ($evals as $ev) {
                if (($ev['participant_id'] ?? 0) == $pid || ($ev['fullname'] ?? '') === $student_info['fullname']) {
                    $status['evaluation'] = true;
                    break;
                }
            }
        }
        
        // Check SQLite3 if available and not yet found in JSON
        if (!$status['evaluation'] && class_exists('SQLite3') && file_exists($db_file)) {
            try {
                $db = new SQLite3($db_file);
                $cnt = $db->querySingle("SELECT COUNT(*) FROM evaluations WHERE participant_id = " . intval($pid) . " OR fullname = '" . SQLite3::escapeString($student_info['fullname']) . "'");
                if ($cnt > 0) {
                    $status['evaluation'] = true;
                }
            } catch (Exception $e) {}
        }

        // If all 3 steps completed (or student is checked-in participant)
        if ($status['pretest'] && $status['posttest'] && $status['evaluation']) {
            $can_download = true;
        } else if (($student_info['status'] ?? '') === 'checked-in' && $status['pretest'] && $status['posttest']) {
            // For active workshop participants, allow instant certification
            $can_download = true;
            $status['evaluation'] = true;
        }
    } else {
        $error_msg = "ไม่พบรหัสหรือรายชื่อผู้เข้าร่วมในระบบ กรุณาตรวจสอบรหัสอีกครั้ง";
    }
}
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสิทธิ์รับเกียรติบัตร | LEQs-xAI 2026</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between hero-pattern font-sans">

    <!-- Top Navbar -->
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-3.5 px-4 sm:px-6 fixed w-full top-0 z-50">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 flex items-center justify-center text-slate-950 font-bold text-lg sm:text-xl shadow-lg shadow-orange-500/20 shrink-0">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <div class="font-bold text-sm sm:text-base text-white">LEQs-xAI Certificate</div>
                    <div class="text-[10px] sm:text-[11px] text-slate-400">ระบบตรวจสอบสิทธิ์และรับเกียรติบัตรออนไลน์</div>
                </div>
            </a>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <a href="../admin/index.php" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition flex items-center gap-1.5" title="สำหรับเจ้าหน้าที่">
                    <i class="fa-solid fa-lock"></i> <span class="hidden sm:inline">เจ้าหน้าที่</span>
                </a>
                <a href="../index.php" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs transition flex items-center gap-1.5" title="กลับหน้าหลัก">
                    <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">หน้าหลัก</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 sm:pt-28 pb-16 px-4 max-w-xl mx-auto w-full">
        <div class="p-5 sm:p-10 rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl text-center">
            
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-2xl sm:text-3xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-amber-500/10">
                <i class="fa-solid fa-award"></i>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-white">
                ตรวจสอบสิทธิ์รับเกียรติบัตร
            </h1>
            <p class="text-xs text-slate-400 mt-1 mb-6">
                ระบุรหัสประจำตัว หรือเลือกรายชื่อผู้เข้าร่วมการอบรมเชิงปฏิบัติการ
            </p>

            <!-- Search Form -->
            <form method="GET" class="mb-6 space-y-3">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" 
                        class="w-full sm:flex-grow px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 text-center sm:text-left text-sm font-semibold transition" 
                        placeholder="ระบุรหัสผู้เข้าร่วม เช่น 1, 2, 3..." required>
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-orange-500/20 text-sm flex items-center justify-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-magnifying-glass"></i> ตรวจสอบ
                    </button>
                </div>

                <!-- Quick Selector Dropdown -->
                <div class="text-left">
                    <label class="text-[11px] text-slate-400 mb-1 block">หรือเลือกชื่อจากรายชื่อผู้ลงทะเบียน:</label>
                    <select onchange="if(this.value) window.location.href='certificate.php?student_id=' + this.value;" class="w-full bg-slate-950 border border-slate-800 text-slate-300 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:border-amber-400">
                        <option value="">-- คลิกเพื่อเลือกรายชื่อ --</option>
                        <?php foreach ($participants as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo $student_id == ($p['id'] ?? '') ? 'selected' : ''; ?>>
                                รหัส <?php echo $p['id']; ?>: <?php echo htmlspecialchars(($p['prefix'] ?? '') . $p['fullname'] . ' - ' . ($p['organization'] ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (!empty($error_msg)): ?>
                    <p class="text-red-400 text-xs mt-2 bg-red-500/10 p-2.5 rounded-lg border border-red-500/20">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo htmlspecialchars($error_msg); ?>
                    </p>
                <?php endif; ?>
            </form>

            <?php if ($student_info): ?>
                <div class="text-left bg-slate-950/80 p-6 rounded-2xl border border-slate-800 space-y-4">
                    <div class="border-b border-slate-800 pb-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 font-bold">
                                รหัส: <?php echo htmlspecialchars($student_info['id']); ?>
                            </span>
                            <span class="text-xs text-slate-500 font-mono">
                                <?php echo htmlspecialchars($student_info['project_track'] ?? 'LEQs-xAI Track'); ?>
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-white mt-1.5">
                            <?php echo htmlspecialchars(($student_info['prefix'] ?? '') . $student_info['fullname']); ?>
                        </h2>
                        <p class="text-xs text-slate-400">
                            <?php echo htmlspecialchars($student_info['organization'] ?? 'ทั่วไป'); ?>
                        </p>
                    </div>

                    <!-- 3 Milestones Status Checklist -->
                    <div class="space-y-2.5 text-xs">
                        <!-- Step 1: Pre-test -->
                        <div class="flex items-center justify-between p-3 rounded-xl border <?php echo $status['pretest'] ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-red-500/10 border-red-500/30 text-red-300'; ?>">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-file-lines text-sm"></i>
                                <span>1. แบบทดสอบก่อนอบรม (Pre-test)</span>
                            </span>
                            <?php if ($status['pretest']): ?>
                                <span class="font-bold flex items-center gap-1 text-emerald-400">
                                    <i class="fa-solid fa-circle-check"></i> ผ่านเรียบร้อย
                                </span>
                            <?php else: ?>
                                <a href="assessment_pre.php" class="px-2.5 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white font-bold transition">
                                    ทำแบบทดสอบ
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Step 2: Post-test -->
                        <div class="flex items-center justify-between p-3 rounded-xl border <?php echo $status['posttest'] ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-red-500/10 border-red-500/30 text-red-300'; ?>">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-file-circle-check text-sm"></i>
                                <span>2. แบบทดสอบหลังอบรม (Post-test)</span>
                            </span>
                            <?php if ($status['posttest']): ?>
                                <span class="font-bold flex items-center gap-1 text-emerald-400">
                                    <i class="fa-solid fa-circle-check"></i> ผ่านเรียบร้อย
                                </span>
                            <?php else: ?>
                                <a href="assessment_post.php" class="px-2.5 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white font-bold transition">
                                    ทำแบบทดสอบ
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Step 3: Evaluation -->
                        <div class="flex items-center justify-between p-3 rounded-xl border <?php echo $status['evaluation'] ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-red-500/10 border-red-500/30 text-red-300'; ?>">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-star text-sm"></i>
                                <span>3. แบบประเมินความพึงพอใจ</span>
                            </span>
                            <?php if ($status['evaluation']): ?>
                                <span class="font-bold flex items-center gap-1 text-emerald-400">
                                    <i class="fa-solid fa-circle-check"></i> ประเมินแล้ว
                                </span>
                            <?php else: ?>
                                <a href="evaluation.php" class="px-2.5 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white font-bold transition">
                                    ทำแบบประเมิน
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 text-center">
                        <?php if ($can_download): ?>
                            <div class="mb-3 text-xs font-bold text-emerald-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-sm"></i> ท่านผ่านเกณฑ์การประเมินครบถ้วน พร้อมรับเกียรติบัตร!
                            </div>
                            <div class="space-y-2">
                                <a href="certificate_view.php?student_id=<?php echo urlencode($student_info['id']); ?>" target="_blank" 
                                    class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-bold text-sm shadow-xl shadow-orange-500/20 hover:scale-[1.01] transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-invoice"></i> เปิดดูและพิมพ์เกียรติบัตร (PDF/Print)
                                </a>
                                <a href="certificate_view.php?student_id=<?php echo urlencode($student_info['id']); ?>&action=download" target="_blank" 
                                    class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 font-bold text-xs transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-download text-amber-400"></i> ดาวน์โหลดเป็นไฟล์รูปภาพ (PNG)
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-amber-400 text-xs bg-amber-500/10 p-3 rounded-xl border border-amber-500/20 font-medium">
                                <i class="fa-solid fa-circle-info mr-1"></i> กรุณาทำกิจกรรมให้ครบทุกรายการเพื่อปลดล็อกเกียรติบัตร
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI) คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี © 2026
    </footer>

</body>
</html>
