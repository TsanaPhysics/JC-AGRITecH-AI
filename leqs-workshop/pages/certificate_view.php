<?php
/**
 * LEQs-xAI Multi-Style Certificate Generator & Viewer
 * สร้างและแสดงผลเกียรติบัตรออนไลน์ (Modern, Formal, Classic, AI Cyber)
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
session_start();

$data_dir = __DIR__ . '/../data';
$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';
$config_file = $data_dir . '/certificate_config.json';

// Assets base path
$assets_base = '../assets/images/';

// Default Certificate Settings
$defaults = [
    'course_name' => 'LEQs-xAI : นวัตกรรมปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม ๒๕๖๙',
    'project_name' => 'โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑ คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี ประจำปี พ.ศ. ๒๕๖๙',
    'signatory1_name' => 'ผู้ช่วยศาสตราจารย์ ดร.ชีวะ ทัศนา',
    'signatory1_title' => 'หัวหน้าโครงการและผู้รับผิดชอบหลัก',
    'signatory1_sub' => 'LEQs-xAI Smart Agri-Environment',
    'signatory1_image' => 'chewa_sign.png',
    'signatory2_name' => 'รองศาสตราจารย์ ดร.อาภาพร บุญมี',
    'signatory2_title' => 'คณบดีคณะวิทยาศาสตร์และเทคโนโลยี',
    'signatory2_sub' => 'มหาวิทยาลัยราชภัฏรำไพพรรณี',
    'signatory2_image' => 'vichaladda_sign.png',
    'cert_ref_prefix' => 'LEQs-xAI'
];

$config = $defaults;
if (file_exists($config_file)) {
    $existing = json_decode(@file_get_contents($config_file), true);
    if (is_array($existing)) {
        $config = array_merge($defaults, $existing);
    }
}

// Student Lookup
$student_id = trim($_GET['student_id'] ?? $_GET['id'] ?? '');

$student = null;
// 1. Try SQLite3
if (class_exists('SQLite3') && file_exists($db_file)) {
    try {
        $db = new SQLite3($db_file);
        if (!empty($student_id)) {
            $stmt = $db->prepare("SELECT * FROM participants WHERE id = :id OR fullname = :name LIMIT 1");
            $stmt->bindValue(':id', intval($student_id), SQLITE3_INTEGER);
            $stmt->bindValue(':name', $student_id, SQLITE3_TEXT);
            $res = $stmt->execute();
            if ($res) {
                $student = $res->fetchArray(SQLITE3_ASSOC);
            }
        }
        if (!$student && empty($student_id)) {
            $student = $db->querySingle("SELECT * FROM participants ORDER BY id ASC LIMIT 1", true);
        }
    } catch (Exception $e) {}
}

// 2. Try JSON Fallback
if (!$student && file_exists($json_file)) {
    $participants = json_decode(file_get_contents($json_file), true) ?: [];
    if (!empty($student_id)) {
        foreach ($participants as $p) {
            if (($p['id'] ?? '') == $student_id || ($p['fullname'] ?? '') === $student_id) {
                $student = $p;
                break;
            }
        }
    }
    if (!$student && !empty($participants)) {
        $student = $participants[0];
    }
}

if (!$student) {
    // Default placeholder student for preview
    $student = [
        'id' => 1,
        'prefix' => 'นาย',
        'fullname' => 'ธนภัทร สุขสมบูรณ์',
        'organization' => 'โรงเรียนประณีตวิทยาคม ตำบลประณีต อำเภอเขาสมิง จังหวัดตราด',
        'project_track' => 'Track B — Plant Vision & Leaf AI'
    ];
}

$fullname = trim(($student['prefix'] ?? '') . ' ' . $student['fullname']);
$organization = $student['organization'] ?? 'ทั่วไป';
$style = $_GET['style'] ?? 'modern'; // Default style: modern, formal, classic, ai

// Thai Date formatting
$thai_months = [
    1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", 
    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
];
$day = date("j");
$month = $thai_months[date("n")];
$year = date("Y") + 543;
$date = "$day $month $year";

// Unique Certificate ID
$clean_num = str_pad(preg_replace('/[^0-9]/', '', (string)($student['id'] ?? '1')) ?: '1', 4, '0', STR_PAD_LEFT);
$certId = htmlspecialchars($config['cert_ref_prefix']) . "-" . (date("Y") + 543) . "-" . $clean_num;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo htmlspecialchars($fullname); ?> | LEQs-xAI</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Charmonman:wght@400;700&family=Prompt:wght@300;400;500;600;700;800&family=Chakra+Petch:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- html2canvas for high-res PNG export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        @page {
            size: 297mm 210mm; /* A4 Landscape */
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Fixed Top Action Bar */
        .no-print {
            position: fixed;
            top: 15px;
            right: 20px;
            background: rgba(15, 23, 42, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 8px 16px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            z-index: 10000;
            font-family: 'Prompt', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            max-width: calc(100vw - 30px);
            transition: all 0.3s ease;
        }

        .no-print-section {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .style-btn {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            color: #94a3b8;
            white-space: nowrap;
        }
        .style-btn:hover { color: white; background: rgba(255,255,255,0.08); }

        .btn-modern.active { background: #0284c7; color: white; box-shadow: 0 0 12px rgba(2, 132, 199, 0.4); }
        .btn-formal.active { background: #1e3a8a; color: white; box-shadow: 0 0 12px rgba(30, 58, 138, 0.4); }
        .btn-classic.active { background: #15803d; color: white; box-shadow: 0 0 12px rgba(21, 128, 61, 0.4); }
        .btn-ai.active { background: #0d9488; color: white; box-shadow: 0 0 12px rgba(13, 148, 136, 0.4); }

        .action-btn {
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            color: white;
            transition: transform 0.15s, background 0.2s;
            white-space: nowrap;
        }
        .action-btn:active { transform: scale(0.96); }

        .btn-print { background: #3b82f6; }
        .btn-print:hover { background: #2563eb; }
        .btn-download { background: #f97316; }
        .btn-download:hover { background: #ea580c; }
        .btn-back { background: #475569; text-decoration: none; }
        .btn-back:hover { background: #334155; }
        .btn-zoom { background: #1e293b; border: 1px solid rgba(255, 255, 255, 0.2); }
        .btn-zoom:hover { background: #334155; }

        /* Rotate Helper Badge */
        .orient-hint {
            display: none;
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.35);
            color: #7dd3fc;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 20px;
            font-family: 'Prompt', sans-serif;
            text-align: center;
            margin: 6px auto 0 auto;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Mobile & Tablet Responsive Toolbar */
        @media screen and (max-width: 980px) {
            .no-print {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100vw;
                border-radius: 0 0 18px 18px;
                padding: 10px 12px;
                flex-direction: column;
                gap: 8px;
                box-shadow: 0 6px 24px rgba(0, 0, 0, 0.7);
            }
            .no-print-section {
                width: 100%;
                justify-content: center;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 2px;
                scrollbar-width: none;
            }
            .no-print-section::-webkit-scrollbar {
                display: none;
            }
            .style-btn {
                padding: 5px 11px;
                font-size: 11px;
            }
            .action-btn {
                padding: 6px 12px;
                font-size: 11px;
            }
            .paper-container {
                padding-top: 110px !important;
            }
            .orient-hint {
                display: flex;
            }
        }

        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }
            .paper-container {
                padding: 0 !important;
                margin: 0 !important;
                height: auto !important;
                display: block !important;
                overflow: visible !important;
            }
            .paper-scaler {
                transform: none !important;
                width: 297mm !important;
                height: 210mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .paper {
                margin: 0 !important;
                box-shadow: none !important;
                width: 297mm !important;
                height: 210mm !important;
                min-width: 297mm !important;
                min-height: 210mm !important;
                page-break-after: always;
                transform: none !important;
                border-radius: 0 !important;
            }
            .no-print, .orient-hint {
                display: none !important;
            }
        }

        /* Certificate A4 Paper Container with Smart Auto-Scaler */
        .paper-container {
            width: 100%;
            max-width: 100vw;
            padding: 85px 16px 40px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            box-sizing: border-box;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            transition: height 0.2s ease-out;
        }

        .paper-scaler {
            width: 297mm;
            height: 210mm;
            transform-origin: top center;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            justify-content: center;
            flex-shrink: 0;
        }

        .paper {
            width: 297mm;
            height: 210mm;
            min-width: 297mm;
            min-height: 210mm;
            background: #ffffff;
            position: relative;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55);
            display: flex;
            overflow: hidden;
            border-radius: 6px;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        /* ========================================================
           STYLE 1: MODERN SIDEBAR (Left Column + Grid Layout)
           ======================================================== */
        .paper.style-modern {
            background: #ffffff;
        }
        .paper.style-modern::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                linear-gradient(rgba(14, 165, 233, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14, 165, 233, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
        }
        .sidebar-modern {
            width: 80px;
            background: linear-gradient(180deg, #0ea5e9 0%, #10b981 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .sidebar-text {
            transform: rotate(-90deg);
            white-space: nowrap;
            color: #ffffff;
            font-family: 'Chakra Petch', sans-serif;
            font-size: 16px;
            letter-spacing: 5px;
            font-weight: 700;
        }
        .body-modern {
            flex: 1;
            padding: 35px 50px 30px 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        /* ========================================================
           STYLE 2: FORMAL (Royal Navy & Gold Certificate)
           ======================================================== */
        .paper.style-formal {
            background: #fdfbf7;
            padding: 30px;
            border: 14px solid #1e3a8a;
        }
        .paper.style-formal .inner-border {
            border: 2px solid #ca8a04;
            padding: 25px 45px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            position: relative;
        }

        /* ========================================================
           STYLE 3: AI CYBER (Matrix Cyan/Emerald Tech)
           ======================================================== */
        .paper.style-ai {
            background: #ffffff;
            border: 10px solid #0f766e;
            padding: 30px 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            position: relative;
        }
        .paper.style-ai::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(13, 148, 136, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        /* ========================================================
           STYLE 4: CLASSIC GREEN (Academic Traditional)
           ======================================================== */
        .paper.style-classic {
            background: #fcfffc;
            border: 12px double #15803d;
            padding: 30px 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            position: relative;
        }

        /* Common Elements */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .cert-logos {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cert-logo-rbru {
            height: 75px;
            object-fit: contain;
        }
        .cert-logo-proj {
            height: 60px;
            object-fit: contain;
        }
        .cert-org-text {
            font-family: 'Prompt', sans-serif;
            text-align: left;
        }
        .cert-org-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .cert-org-sub {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        .cert-title-th {
            font-family: 'Prompt', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #0284c7;
            margin-top: 15px;
            letter-spacing: 0.5px;
        }
        .cert-title-en {
            font-family: 'Charmonman', cursive;
            font-size: 42px;
            font-weight: 700;
            color: #ca8a04;
            line-height: 1.1;
            margin: 5px 0;
        }
        .cert-title-ai {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 34px;
            font-weight: 800;
            color: #0d9488;
            letter-spacing: 2px;
            margin: 5px 0;
        }

        .cert-recipient-block {
            margin: 10px 0;
        }
        .cert-name {
            font-family: 'Prompt', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .cert-org {
            font-family: 'Prompt', sans-serif;
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        .cert-course-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px 20px;
            margin: 10px 0;
            border: 1px solid #e2e8f0;
        }
        .cert-course-name {
            font-family: 'Prompt', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #0284c7;
        }
        .cert-project-desc {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.4;
            margin-top: 4px;
        }

        .cert-date {
            font-size: 13.5px;
            font-weight: 700;
            color: #475569;
            margin-top: 5px;
        }

        .cert-signatures {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            margin-top: 15px;
            gap: 40px;
        }
        .sig-block {
            text-align: center;
            width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sig-img {
            height: 65px;
            max-width: 200px;
            object-fit: contain;
            margin-bottom: -10px;
        }
        .sig-line {
            width: 200px;
            height: 1px;
            background: #94a3b8;
            margin-bottom: 6px;
        }
        .sig-name {
            font-family: 'Prompt', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }
        .sig-title {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.3;
        }

        .cert-ref-code {
            position: absolute;
            bottom: 15px;
            right: 25px;
            font-family: 'Chakra Petch', monospace;
            font-size: 10px;
            color: #94a3b8;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Responsive Sticky Style Selector & Action Bar -->
    <div class="no-print">
        <!-- Row 1: Style Switchers -->
        <div class="no-print-section">
            <span style="font-size: 11px; font-weight: bold; color: #94a3b8; margin-right: 2px;">
                <i class="fa-solid fa-palette text-cyan-400"></i> สไตล์:
            </span>
            <a href="?student_id=<?php echo urlencode($student['id']); ?>&style=modern" class="style-btn btn-modern <?php echo $style == 'modern' ? 'active' : ''; ?>">
                <i class="fa-solid fa-table-columns mr-1"></i> Modern
            </a>
            <a href="?student_id=<?php echo urlencode($student['id']); ?>&style=formal" class="style-btn btn-formal <?php echo $style == 'formal' ? 'active' : ''; ?>">
                <i class="fa-solid fa-ribbon mr-1"></i> Formal
            </a>
            <a href="?student_id=<?php echo urlencode($student['id']); ?>&style=ai" class="style-btn btn-ai <?php echo $style == 'ai' ? 'active' : ''; ?>">
                <i class="fa-solid fa-microchip mr-1"></i> AI Cyber
            </a>
            <a href="?student_id=<?php echo urlencode($student['id']); ?>&style=classic" class="style-btn btn-classic <?php echo $style == 'classic' ? 'active' : ''; ?>">
                <i class="fa-solid fa-certificate mr-1"></i> Classic
            </a>
        </div>

        <!-- Row 2: Action Buttons & Zoom -->
        <div class="no-print-section">
            <button onclick="toggleZoomMode()" class="action-btn btn-zoom" id="zoomToggleBtn" title="สลับโหมดย่อพอดีจอ / ขนาดจริง 100%">
                <i class="fa-solid fa-expand"></i> <span id="zoomBtnText">พอดีจอ</span>
            </button>
            <button onclick="window.print()" class="action-btn btn-print">
                <i class="fa-solid fa-print"></i> พิมพ์ / PDF
            </button>
            <button onclick="downloadAsPNG()" class="action-btn btn-download">
                <i class="fa-solid fa-download"></i> โหลด PNG
            </button>
            <a href="certificate.php?student_id=<?php echo urlencode($student['id']); ?>" class="action-btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> กลับ
            </a>
        </div>

        <!-- Mobile Landscape Rotation Hint -->
        <div class="orient-hint">
            <i class="fa-solid fa-mobile-screen-button"></i> แนะนำหมุนหน้าจอแนวนอนเพื่อมุมมองที่ดีที่สุด
        </div>
    </div>

    <!-- Paper Container with Auto-Scaler Wrapper -->
    <div class="paper-container" id="paperContainer">
        <div class="paper-scaler" id="paperScaler">
        
        <!-- ========================================================
             LAYOUT 1: MODERN SIDEBAR
             ======================================================== -->
        <?php if ($style == 'modern'): ?>
        <div class="paper style-modern" id="certPaper">
            <div class="sidebar-modern">
                <div class="sidebar-text">LEQs-xAI EMBEDDED AI 2026</div>
            </div>
            <div class="body-modern">
                <!-- Header -->
                <div class="cert-header">
                    <div class="cert-logos">
                        <img src="<?php echo $assets_base; ?>scirbru_logo.png" class="cert-logo-rbru" alt="RBRU">
                        <img src="<?php echo $assets_base; ?>logo.svg" class="cert-logo-proj" alt="LEQs-xAI" onerror="this.style.display='none';">
                        <div class="cert-org-text">
                            <div class="cert-org-title">โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI)</div>
                            <div class="cert-org-sub">คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</div>
                        </div>
                    </div>
                </div>

                <!-- Title & Recipient -->
                <div>
                    <div class="cert-title-th">เกียรติบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า</div>
                    <div class="cert-recipient-block">
                        <div class="cert-name"><?php echo htmlspecialchars($fullname); ?></div>
                        <div class="cert-org"><?php echo htmlspecialchars($organization); ?></div>
                    </div>

                    <!-- Course Description Box -->
                    <div class="cert-course-box">
                        <div style="font-size: 12px; color: #475569; font-weight: 600;">ได้ผ่านการฝึกอบรมเชิงปฏิบัติการหลักสูตร</div>
                        <div class="cert-course-name"><?php echo htmlspecialchars($config['course_name']); ?></div>
                        <div class="cert-project-desc"><?php echo htmlspecialchars($config['project_name']); ?></div>
                    </div>

                    <div class="cert-date">
                        ให้ไว้ ณ วันที่ <?php echo $date; ?>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="cert-signatures">
                    <div class="sig-block">
                        <img src="<?php echo $assets_base . htmlspecialchars($config['signatory1_image']); ?>" class="sig-img" alt="Signature 1">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($config['signatory1_name']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_title']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_sub']); ?></div>
                    </div>

                    <div class="sig-block">
                        <img src="<?php echo $assets_base . htmlspecialchars($config['signatory2_image']); ?>" class="sig-img" alt="Signature 2">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($config['signatory2_name']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_title']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_sub']); ?></div>
                    </div>
                </div>

                <div class="cert-ref-code">Ref: <?php echo $certId; ?></div>
            </div>
        </div>

        <!-- ========================================================
             LAYOUT 2: FORMAL (Royal Navy & Gold)
             ======================================================== -->
        <?php elseif ($style == 'formal'): ?>
        <div class="paper style-formal" id="certPaper">
            <div class="inner-border">
                <!-- Logos & Title -->
                <div style="display:flex; justify-content:center; align-items:center; gap:25px;">
                    <img src="<?php echo $assets_base; ?>scirbru_logo.png" style="height:70px;" alt="RBRU">
                    <div style="text-align:center;">
                        <div style="font-size:17px; font-weight:800; color:#1e3a8a; font-family:'Prompt';">โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI)</div>
                        <div style="font-size:12px; color:#475569; font-weight:600;">คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</div>
                    </div>
                    <img src="<?php echo $assets_base; ?>logo.svg" style="height:55px;" alt="LEQs-xAI" onerror="this.style.display='none';">
                </div>

                <div>
                    <div class="cert-title-en">Certificate of Completion</div>
                    <div style="font-size:14px; color:#64748b; font-weight:600; margin-bottom:10px;">เกียรติบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า</div>
                    
                    <div class="cert-name" style="font-size:34px;"><?php echo htmlspecialchars($fullname); ?></div>
                    <div class="cert-org"><?php echo htmlspecialchars($organization); ?></div>
                    
                    <div style="font-size:13px; color:#475569; margin-top:12px;">ได้ผ่านการฝึกอบรมเชิงปฏิบัติการหลักสูตร</div>
                    <div style="font-size:19px; font-weight:800; color:#1e3a8a; font-family:'Prompt'; margin-top:3px;">
                        <?php echo htmlspecialchars($config['course_name']); ?>
                    </div>
                    <div style="font-size:11px; color:#64748b; max-width:700px; margin:4px auto; line-height:1.4;">
                        <?php echo htmlspecialchars($config['project_name']); ?>
                    </div>

                    <div class="cert-date" style="margin-top:8px;">
                        ให้ไว้ ณ วันที่ <?php echo $date; ?>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="cert-signatures">
                    <div class="sig-block">
                        <img src="<?php echo $assets_base . htmlspecialchars($config['signatory1_image']); ?>" class="sig-img" alt="Signature 1">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($config['signatory1_name']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_title']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_sub']); ?></div>
                    </div>

                    <div class="sig-block">
                        <img src="<?php echo $assets_base . htmlspecialchars($config['signatory2_image']); ?>" class="sig-img" alt="Signature 2">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($config['signatory2_name']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_title']); ?></div>
                        <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_sub']); ?></div>
                    </div>
                </div>

                <div class="cert-ref-code">Ref: <?php echo $certId; ?></div>
            </div>
        </div>

        <!-- ========================================================
             LAYOUT 3: AI CYBER
             ======================================================== -->
        <?php elseif ($style == 'ai'): ?>
        <div class="paper style-ai" id="certPaper">
            <!-- Header -->
            <div style="display:flex; justify-content:center; align-items:center; gap:25px;">
                <img src="<?php echo $assets_base; ?>scirbru_logo.png" style="height:70px;" alt="RBRU">
                <div style="text-align:center;">
                    <div style="font-size:17px; font-weight:800; color:#0f766e; font-family:'Prompt';">โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI)</div>
                    <div style="font-size:12px; color:#64748b; font-weight:600;">Faculty of Science &amp; Technology, Rambhai Barni Rajabhat University</div>
                </div>
                <img src="<?php echo $assets_base; ?>logo.svg" style="height:55px;" alt="LEQs-xAI" onerror="this.style.display='none';">
            </div>

            <div>
                <div class="cert-title-ai">CERTIFICATE OF AI INNOVATION</div>
                <div style="font-size:14px; color:#64748b; font-weight:600;">เกียรติบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า</div>
                
                <div class="cert-name" style="font-size:34px; color:#0f766e;"><?php echo htmlspecialchars($fullname); ?></div>
                <div class="cert-org"><?php echo htmlspecialchars($organization); ?></div>
                
                <div style="font-size:13px; color:#475569; margin-top:12px;">ได้ผ่านการฝึกอบรมเชิงปฏิบัติการหลักสูตร</div>
                <div style="font-size:19px; font-weight:800; color:#0d9488; font-family:'Prompt'; margin-top:3px;">
                    <?php echo htmlspecialchars($config['course_name']); ?>
                </div>
                <div style="font-size:11px; color:#64748b; max-width:700px; margin:4px auto; line-height:1.4;">
                    <?php echo htmlspecialchars($config['project_name']); ?>
                </div>

                <div class="cert-date" style="margin-top:8px;">
                    ให้ไว้ ณ วันที่ <?php echo $date; ?>
                </div>
            </div>

            <!-- Signatures -->
            <div class="cert-signatures">
                <div class="sig-block">
                    <img src="<?php echo $assets_base . htmlspecialchars($config['signatory1_image']); ?>" class="sig-img" alt="Signature 1">
                    <div class="sig-line"></div>
                    <div class="sig-name"><?php echo htmlspecialchars($config['signatory1_name']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_title']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_sub']); ?></div>
                </div>

                <div class="sig-block">
                    <img src="<?php echo $assets_base . htmlspecialchars($config['signatory2_image']); ?>" class="sig-img" alt="Signature 2">
                    <div class="sig-line"></div>
                    <div class="sig-name"><?php echo htmlspecialchars($config['signatory2_name']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_title']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_sub']); ?></div>
                </div>
            </div>

            <div class="cert-ref-code">Ref: <?php echo $certId; ?></div>
        </div>

        <!-- ========================================================
             LAYOUT 4: CLASSIC GREEN
             ======================================================== -->
        <?php else: ?>
        <div class="paper style-classic" id="certPaper">
            <!-- Header -->
            <div style="display:flex; justify-content:center; align-items:center; gap:25px;">
                <img src="<?php echo $assets_base; ?>scirbru_logo.png" style="height:70px;" alt="RBRU">
                <div style="text-align:center;">
                    <div style="font-size:17px; font-weight:800; color:#15803d; font-family:'Prompt';">โครงการปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม (LEQs-xAI)</div>
                    <div style="font-size:12px; color:#475569; font-weight:600;">คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี</div>
                </div>
                <img src="<?php echo $assets_base; ?>logo.svg" style="height:55px;" alt="LEQs-xAI" onerror="this.style.display='none';">
            </div>

            <div>
                <div style="font-family:'Prompt'; font-size:26px; font-weight:800; color:#15803d; margin:10px 0;">เกียรติบัตรการเข้าร่วมอบรม</div>
                <div style="font-size:14px; color:#64748b; font-weight:600;">เกียรติบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า</div>
                
                <div class="cert-name" style="font-size:34px;"><?php echo htmlspecialchars($fullname); ?></div>
                <div class="cert-org"><?php echo htmlspecialchars($organization); ?></div>
                
                <div style="font-size:13px; color:#475569; margin-top:12px;">ได้ผ่านการฝึกอบรมเชิงปฏิบัติการหลักสูตร</div>
                <div style="font-size:19px; font-weight:800; color:#15803d; font-family:'Prompt'; margin-top:3px;">
                    <?php echo htmlspecialchars($config['course_name']); ?>
                </div>
                <div style="font-size:11px; color:#64748b; max-width:700px; margin:4px auto; line-height:1.4;">
                    <?php echo htmlspecialchars($config['project_name']); ?>
                </div>

                <div class="cert-date" style="margin-top:8px;">
                    ให้ไว้ ณ วันที่ <?php echo $date; ?>
                </div>
            </div>

            <!-- Signatures -->
            <div class="cert-signatures">
                <div class="sig-block">
                    <img src="<?php echo $assets_base . htmlspecialchars($config['signatory1_image']); ?>" class="sig-img" alt="Signature 1">
                    <div class="sig-line"></div>
                    <div class="sig-name"><?php echo htmlspecialchars($config['signatory1_name']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_title']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory1_sub']); ?></div>
                </div>

                <div class="sig-block">
                    <img src="<?php echo $assets_base . htmlspecialchars($config['signatory2_image']); ?>" class="sig-img" alt="Signature 2">
                    <div class="sig-line"></div>
                    <div class="sig-name"><?php echo htmlspecialchars($config['signatory2_name']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_title']); ?></div>
                    <div class="sig-title"><?php echo htmlspecialchars($config['signatory2_sub']); ?></div>
                </div>
            </div>

            <div class="cert-ref-code">Ref: <?php echo $certId; ?></div>
        </div>
        <?php endif; ?>

        </div> <!-- /paper-scaler -->
    </div> <!-- /paper-container -->

    <!-- Smart Responsive Auto-Scaler & Download Script -->
    <script>
    let zoomMode = 'fit'; // 'fit' (fit screen) or 'actual' (100% full scale)

    function autoScaleCertificate() {
        const scaler = document.getElementById('paperScaler');
        const container = document.getElementById('paperContainer');
        const zoomText = document.getElementById('zoomBtnText');
        const zoomBtn = document.getElementById('zoomToggleBtn');
        if (!scaler || !container) return;

        // A4 Landscape dimensions in CSS px at standard 96 DPI:
        // 297mm ≈ 1122.52px, 210mm ≈ 793.70px
        const A4_WIDTH = 1122.52;
        const A4_HEIGHT = 793.70;

        const screenWidth = window.innerWidth;
        const padding = screenWidth < 640 ? 20 : 36;
        const availableWidth = Math.max(screenWidth - padding, 280);

        let targetScale = 1;
        if (zoomMode === 'fit') {
            targetScale = Math.min(availableWidth / A4_WIDTH, 1.0);
            if (zoomText) zoomText.innerText = Math.round(targetScale * 100) + '% พอดีจอ';
            if (zoomBtn) {
                zoomBtn.style.background = '#1e293b';
                zoomBtn.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            }
        } else {
            targetScale = 1.0;
            if (zoomText) zoomText.innerText = '100% ขนาดจริง';
            if (zoomBtn) {
                zoomBtn.style.background = '#0284c7';
                zoomBtn.style.borderColor = '#38bdf8';
            }
        }

        scaler.style.transform = 'scale(' + targetScale + ')';

        // Dynamically adjust container height to eliminate blank gap under scaled element
        const scaledHeight = A4_HEIGHT * targetScale;
        const topOffset = screenWidth <= 980 ? 115 : 90;
        container.style.height = (scaledHeight + topOffset + 35) + 'px';
    }

    function toggleZoomMode() {
        zoomMode = zoomMode === 'fit' ? 'actual' : 'fit';
        autoScaleCertificate();
    }

    // High Resolution PNG Export (always renders at unscaled 300DPI)
    function downloadAsPNG() {
        const cert = document.getElementById('certPaper');
        const scaler = document.getElementById('paperScaler');
        if (!cert) return;

        const btn = document.querySelector('.btn-download');
        const oldHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังเรนเดอร์...';
            btn.disabled = true;
        }

        // Temporarily reset scaler transform for crystal clear capture
        const prevTransform = scaler ? scaler.style.transform : '';
        if (scaler) scaler.style.transform = 'none';

        html2canvas(cert, {
            scale: 2.5, // 300 DPI equivalent
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            if (scaler) scaler.style.transform = prevTransform;
            const link = document.createElement('a');
            link.download = 'Certificate_<?php echo preg_replace("/[^a-zA-Z0-9_\-]/", "_", $fullname); ?>_<?php echo $certId; ?>.png';
            link.href = canvas.toDataURL('image/png');
            link.click();

            if (btn) {
                btn.innerHTML = oldHtml;
                btn.disabled = false;
            }
        }).catch(err => {
            if (scaler) scaler.style.transform = prevTransform;
            alert('เกิดข้อผิดพลาดในการสร้างไฟล์ภาพ: ' + err);
            if (btn) {
                btn.innerHTML = oldHtml;
                btn.disabled = false;
            }
        });
    }

    window.addEventListener('resize', autoScaleCertificate);
    window.addEventListener('orientationchange', () => {
        setTimeout(autoScaleCertificate, 250);
    });
    document.addEventListener('DOMContentLoaded', autoScaleCertificate);

    // Auto download if requested
    <?php if (isset($_GET['action']) && $_GET['action'] === 'download'): ?>
    window.addEventListener('load', () => {
        setTimeout(downloadAsPNG, 800);
    });
    <?php endif; ?>
    </script>

</body>
</html>
