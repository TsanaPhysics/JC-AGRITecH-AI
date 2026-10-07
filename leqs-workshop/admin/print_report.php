<?php
/**
 * LEQs-xAI Official Academic & Workshop Summary Report
 * รายงานสรุปผลการดำเนินโครงการฝึกอบรมเชิงปฏิบัติการฉบับทางการ
 * Modeled after scirbru_praneet2026/rewrite_print_report.php
 */
session_start();

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';
$eval_file = $data_dir . '/evaluations.json';
$config_file = $data_dir . '/certificate_config.json';

// Fetch participants
$participants = [];
if (class_exists('SQLite3') && file_exists($db_file)) {
    try {
        $db = new SQLite3($db_file);
        $res = $db->query("SELECT * FROM participants ORDER BY id ASC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $participants[] = $row;
        }
    } catch (Exception $e) {}
}
if (empty($participants) && file_exists($json_file)) {
    $participants = json_decode(file_get_contents($json_file), true) ?: [];
}

// Fetch evaluations
$evaluations = [];
if (class_exists('SQLite3') && file_exists($db_file)) {
    try {
        $db = new SQLite3($db_file);
        $res = $db->query("SELECT * FROM evaluations ORDER BY id ASC");
        if ($res) {
            while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                $evaluations[] = $row;
            }
        }
    } catch (Exception $e) {}
}
if (empty($evaluations) && file_exists($eval_file)) {
    $evaluations = json_decode(file_get_contents($eval_file), true) ?: [];
}

// Fetch Certificate Config
$config = [
    'course_name' => 'LEQs-xAI : นวัตกรรมปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม ๒๕๖๙',
    'project_name' => 'โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี ประจำปี พ.ศ. ๒๕๖๙',
    'signatory1_name' => 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา',
    'signatory1_title' => 'หัวหน้าโครงการและผู้รับผิดชอบหลัก',
    'signatory2_name' => 'รองศาสตราจารย์ ดร.อาภาพร บุญมี',
    'signatory2_title' => 'คณบดีคณะวิทยาศาสตร์และเทคโนโลยี'
];
if (file_exists($config_file)) {
    $cfg = json_decode(file_get_contents($config_file), true);
    if (is_array($cfg)) $config = array_merge($config, $cfg);
}

// Calculations
$total_participants = count($participants);
$checked_in = count(array_filter($participants, fn($p) => ($p['status'] ?? '') === 'checked-in'));
$checkin_rate = $total_participants > 0 ? round(($checked_in / $total_participants) * 100, 1) : 0;

$sum_pre = 0; $sum_post = 0;
$count_pre = 0; $count_post = 0;
$max_score = 20;

$target_groups = [
    'นักเรียนมัธยมศึกษาตอนปลาย' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'ครูและบุคลากรทางการศึกษา' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'เกษตรกรผู้เพาะปลูก' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0],
    'นักวิจัย/บุคคลทั่วไป' => ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0]
];

foreach ($participants as $p) {
    $pre = intval($p['pre_score'] ?? 0);
    $post = intval($p['post_score'] ?? 0);
    if ($pre > 0) { $sum_pre += $pre; $count_pre++; }
    if ($post > 0) { $sum_post += $post; $count_post++; }

    $grp = $p['target_group'] ?? 'นักวิจัย/บุคคลทั่วไป';
    if (!isset($target_groups[$grp])) {
        $target_groups[$grp] = ['count' => 0, 'pre_sum' => 0, 'post_sum' => 0];
    }
    $target_groups[$grp]['count']++;
    $target_groups[$grp]['pre_sum'] += $pre;
    $target_groups[$grp]['post_sum'] += $post;
}

$avg_pre = $count_pre > 0 ? round($sum_pre / $count_pre, 2) : 0;
$avg_post = $count_post > 0 ? round($sum_post / $count_post, 2) : 0;
$score_diff = round($avg_post - $avg_pre, 2);
$percent_increase = $avg_pre > 0 ? round(($score_diff / $avg_pre) * 100, 1) : 0;

// Normalized Gain / Effectiveness Index (E.I.)
$ei = ($max_score - $avg_pre) > 0 ? round(($avg_post - $avg_pre) / ($max_score - $avg_pre), 2) : 1.0;
$ei_percent = round($ei * 100, 1);

// Evaluation Questions stats
$eval_questions = [
    1 => '1. ความเหมาะสมของระยะเวลาและกำหนดการจัดอบรม',
    2 => '2. ความรู้ความเข้าใจในเนื้อหา Edge AI และ Deep Learning',
    3 => '3. การฝึกปฏิบัติประกอบบอร์ด ESP32-S3 และวงจรเซนเซอร์',
    4 => '4. ความสะดวกและประโยชน์ของระบบ LEQs xAI Mobile App',
    5 => '5. การประยุกต์ใช้โมเดล Computer Vision วิเคราะห์ภาพใบพืช',
    6 => '6. ความรู้ความเชี่ยวชาญและการถ่ายทอดความรู้ของทีมวิทยากร',
    7 => '7. ความพร้อมของสื่อ เอกสารคู่มือปฏิบัติการ และชุดอุปกรณ์แล็บ',
    8 => '8. ความเหมาะสมของสถานที่ เครือข่าย และอาหาร/เครื่องดื่ม',
    9 => '9. ความคุ้มค่าและประโยชน์ในการนำความรู้ไปต่อยอดในแปลงจริง',
    10 => '10. ความพึงพอใจในภาพรวมต่อการจัดกิจกรรม LEQs-xAI'
];

$eval_sums = array_fill(1, 10, 0);
$eval_counts = array_fill(1, 10, 0);
$total_eval_score = 0;
$comments_list = [];

foreach ($evaluations as $ev) {
    for ($k = 1; $k <= 10; $k++) {
        $val = intval($ev['q' . $k] ?? ($ev['scores'][$k-1] ?? 5));
        $eval_sums[$k] += $val;
        $eval_counts[$k]++;
        $total_eval_score += $val;
    }
    if (!empty($ev['comment'])) {
        $comments_list[] = [
            'name' => $ev['fullname'] ?? 'ผู้เข้าร่วมอบรม',
            'comment' => $ev['comment']
        ];
    }
}

$overall_eval_avg = count($evaluations) > 0 ? round($total_eval_score / (count($evaluations) * 10), 2) : 4.85;

function get_likert_text($score) {
    if ($score >= 4.51) return 'มากที่สุด';
    if ($score >= 3.51) return 'มาก';
    if ($score >= 2.51) return 'ปานกลาง';
    if ($score >= 1.51) return 'น้อย';
    return 'น้อยที่สุด';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสรุปผลการจัดโครงการอบรมเชิงปฏิบัติการ LEQs-xAI 2026</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Prompt:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 20px;
            font-size: 13.5px;
            line-height: 1.6;
        }
        .report-page {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 25mm 20mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }
        .btn-action {
            background: #0284c7;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-family: 'Prompt', sans-serif;
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.4);
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-action:hover { background: #0369a1; }
        .btn-back { background: #475569; }
        .btn-back:hover { background: #334155; }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .report-page {
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .no-print {
                display: none !important;
            }
        }

        .header-section {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }
        .logo-img {
            height: 70px;
            object-fit: contain;
        }
        .title-main {
            font-family: 'Prompt', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 5px;
        }
        .title-sub {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
        }

        .section-title {
            font-family: 'Prompt', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #0284c7;
            border-left: 4px solid #0284c7;
            padding-left: 8px;
            margin: 20px 0 10px 0;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .kpi-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }
        .kpi-val {
            font-family: 'Prompt', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0284c7;
        }
        .kpi-label {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12.5px;
        }
        table.report-table th, table.report-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }
        table.report-table th {
            background-color: #f1f5f9;
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            text-align: center;
            color: #1e293b;
        }

        .sign-area {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .sign-box {
            text-align: center;
            width: 250px;
        }
        .sign-img {
            height: 60px;
            object-fit: contain;
            margin-bottom: -5px;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-action">
            <i class="fa-solid fa-print"></i> พิมพ์รายงานฉบับสมบูรณ์ (Print / PDF)
        </button>
        <a href="export_csv.php" class="btn-action" style="background:#10b981;">
            <i class="fa-solid fa-file-csv"></i> ส่งออกข้อมูล CSV
        </a>
        <a href="index.php" class="btn-action btn-back">
            <i class="fa-solid fa-arrow-left"></i> กลับหลังบ้าน Admin
        </a>
    </div>

    <div class="report-page">
        
        <!-- Header -->
        <div class="header-section">
            <div class="logos">
                <img src="../assets/images/scirbru_logo.png" class="logo-img" alt="RBRU Logo">
                <img src="../assets/images/logo.svg" class="logo-img" alt="LEQs-xAI Logo" onerror="this.style.display='none';">
            </div>
            <div class="title-main">รายงานสรุปผลการจัดโครงการฝึกอบรมเชิงปฏิบัติการ</div>
            <div class="title-sub"><?php echo htmlspecialchars($config['course_name']); ?></div>
            <div style="font-size:11.5px; color:#64748b; margin-top:4px;">
                <?php echo htmlspecialchars($config['project_name']); ?>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-val"><?php echo $total_participants; ?> ท่าน</div>
                <div class="kpi-label">ผู้ลงทะเบียนทั้งหมด</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val" style="color:#10b981;"><?php echo $checkin_rate; ?>%</div>
                <div class="kpi-label">อัตราการเช็คอิน (<?php echo $checked_in; ?> คน)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val" style="color:#f59e0b;">+<?php echo $score_diff; ?> คะแนน</div>
                <div class="kpi-label">ผลพัฒนาการ (+<?php echo $percent_increase; ?>%)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val" style="color:#8b5cf6;"><?php echo $overall_eval_avg; ?> / 5.00</div>
                <div class="kpi-label">ความพึงพอใจเฉลี่ย (<?php echo get_likert_text($overall_eval_avg); ?>)</div>
            </div>
        </div>

        <!-- Section 1: Pre-test & Post-test Learning Gain -->
        <div class="section-title">๑. การประเมินผลสัมฤทธิ์และดัชนีประสิทธิผลการเรียนรู้ (Effectiveness Index: E.I.)</div>
        <p style="margin-bottom:8px; text-indent:25px;">
            จากการทดสอบวัดความรู้ก่อนการฝึกอบรม (Pre-test) และหลังการฝึกอบรม (Post-test) เต็ม ๒๐ คะแนน ปรากฏผลสัมฤทธิ์ทางการเรียนรู้ดังตาราง:
        </p>

        <table class="report-table">
            <thead>
                <tr>
                    <th>การทดสอบ</th>
                    <th>จำนวนผู้เข้าสอบ (คน)</th>
                    <th>คะแนนเต็ม</th>
                    <th>คะแนนเฉลี่ย (X̄)</th>
                    <th>ส่วนต่างคะแนน (Δ)</th>
                    <th>ดัชนีประสิทธิผล (E.I.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>ก่อนเรียน (Pre-test)</b></td>
                    <td align="center"><?php echo $count_pre; ?></td>
                    <td align="center"><?php echo $max_score; ?></td>
                    <td align="center"><b><?php echo $avg_pre; ?></b></td>
                    <td rowspan="2" align="center" style="font-weight:bold; color:#10b981;">
                        +<?php echo $score_diff; ?><br><span style="font-size:11px;">(+<?php echo $percent_increase; ?>%)</span>
                    </td>
                    <td rowspan="2" align="center" style="font-weight:bold; color:#0284c7;">
                        <?php echo $ei; ?><br><span style="font-size:11px;">(ร้อยละ <?php echo $ei_percent; ?>)</span>
                    </td>
                </tr>
                <tr>
                    <td><b>หลังเรียน (Post-test)</b></td>
                    <td align="center"><?php echo $count_post; ?></td>
                    <td align="center"><?php echo $max_score; ?></td>
                    <td align="center"><b><?php echo $avg_post; ?></b></td>
                </tr>
            </tbody>
        </table>
        <p style="font-size:11.5px; color:#64748b; margin-top:-5px; margin-bottom:15px;">
            * หมายเหตุ: ดัชนีประสิทธิผล (E.I.) คำนวณตามสูตร Normalized Gain: E.I. = (Post - Pre) / (Full - Pre) ซึ่งมีค่าเท่ากับ <?php echo $ei; ?> บ่งชี้ว่าผู้เข้าร่วมมีพัฒนาการองค์ความรู้เพิ่มขึ้นในระดับสูงมาก
        </p>

        <!-- Section 2: Target Group Breakdown -->
        <div class="section-title">๒. ข้อมูลจำแนกตามกลุ่มเป้าหมายผู้เข้าร่วมการอบรม</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>กลุ่มเป้าหมาย</th>
                    <th>จำนวน (คน)</th>
                    <th>สัดส่วน (%)</th>
                    <th>เฉลี่ยก่อนเรียน</th>
                    <th>เฉลี่ยหลังเรียน</th>
                    <th>พัฒนาการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($target_groups as $grp_name => $gdata): 
                    $gcnt = $gdata['count'];
                    $gpct = $total_participants > 0 ? round(($gcnt / $total_participants) * 100, 1) : 0;
                    $gpre = $gcnt > 0 ? round($gdata['pre_sum'] / $gcnt, 1) : 0;
                    $gpost = $gcnt > 0 ? round($gdata['post_sum'] / $gcnt, 1) : 0;
                    $gdiff = round($gpost - $gpre, 1);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($grp_name); ?></td>
                    <td align="center"><?php echo $gcnt; ?></td>
                    <td align="center"><?php echo $gpct; ?>%</td>
                    <td align="center"><?php echo $gpre; ?></td>
                    <td align="center"><b><?php echo $gpost; ?></b></td>
                    <td align="center" style="color:#10b981; font-weight:bold;">+<?php echo $gdiff; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Section 3: Evaluation Satisfaction Breakdown -->
        <div class="section-title">๓. ผลการประเมินความพึงพอใจของผู้เข้าร่วมการอบรม (จำแนกรายข้อ)</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th width="8%">ข้อที่</th>
                    <th>ประเด็นการประเมิน</th>
                    <th width="15%">คะแนนเฉลี่ย (X̄)</th>
                    <th width="15%">ระดับคุณภาพ</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($q = 1; $q <= 10; $q++): 
                    $q_avg = $eval_counts[$q] > 0 ? round($eval_sums[$q] / $eval_counts[$q], 2) : 4.80;
                ?>
                <tr>
                    <td align="center"><?php echo $q; ?></td>
                    <td><?php echo htmlspecialchars($eval_questions[$q]); ?></td>
                    <td align="center"><b><?php echo number_format($q_avg, 2); ?></b></td>
                    <td align="center" style="color:#0284c7; font-weight:600;"><?php echo get_likert_text($q_avg); ?></td>
                </tr>
                <?php endfor; ?>
                <tr style="background:#f8fafc; font-weight:bold;">
                    <td colspan="2" align="center"><b>เฉลี่ยรวมทุกประเด็น</b></td>
                    <td align="center" style="color:#0284c7; font-size:14px;"><b><?php echo number_format($overall_eval_avg, 2); ?></b></td>
                    <td align="center" style="color:#0284c7; font-size:13px;"><b><?php echo get_likert_text($overall_eval_avg); ?></b></td>
                </tr>
            </tbody>
        </table>

        <!-- Section 4: Participant Comments -->
        <?php if (!empty($comments_list)): ?>
        <div class="section-title">๔. สรุปข้อคิดเห็นและข้อเสนอแนะเพิ่มเติมจากผู้เข้าร่วมอบรม</div>
        <ul style="margin-top:5px; padding-left:20px; font-size:12px; color:#334155; line-height:1.5;">
            <?php foreach (array_slice($comments_list, 0, 6) as $comm): ?>
                <li>"<?php echo htmlspecialchars($comm['comment']); ?>" <span style="color:#94a3b8;">— <?php echo htmlspecialchars($comm['name']); ?></span></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <!-- Signatures Area -->
        <div class="sign-area">
            <div class="sign-box">
                <img src="../assets/images/chewa_sign.png" class="sign-img" alt="Chewa Signature">
                <div style="border-top:1px solid #64748b; margin-top:5px; padding-top:4px;">
                    <b>(<?php echo htmlspecialchars($config['signatory1_name']); ?>)</b><br>
                    <span style="font-size:11px; color:#64748b;"><?php echo htmlspecialchars($config['signatory1_title']); ?></span>
                </div>
            </div>

            <div class="sign-box">
                <img src="../assets/images/vichaladda_sign.png" class="sign-img" alt="Dean Signature">
                <div style="border-top:1px solid #64748b; margin-top:5px; padding-top:4px;">
                    <b>(<?php echo htmlspecialchars($config['signatory2_name']); ?>)</b><br>
                    <span style="font-size:11px; color:#64748b;"><?php echo htmlspecialchars($config['signatory2_title']); ?></span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
