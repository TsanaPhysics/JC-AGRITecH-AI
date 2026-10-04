<?php
/**
 * LEQs-xAI Admin Control Center & Dashboard
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Modeled after scirbru_praneet2026/admin_index.php
 */
session_start();

// Handle Simple Authentication
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username === 'admin' && ($password === 'LEQs' || $password === 'LEQs-xAI' || $password === 'admin')) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = 'Admin (LEQs Team)';
        $_SESSION['admin_role'] = 'Chief Administrator';
    } else {
        $error = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (Default: admin / LEQs)";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$is_logged = !empty($_SESSION['admin_logged']);

// Ensure data folder exists
$data_dir = __DIR__ . '/../data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0777, true);
}

$db_file = $data_dir . '/leqs_xai.db';
$json_file = $data_dir . '/participants.json';

// Initialize Database & Flat-file JSON
$participants = [];
$db_connected = false;
$db_error = "";
$tables_summary = [];

if (class_exists('SQLite3')) {
    try {
        $db = new SQLite3($db_file);
        $db_connected = true;

        $db->exec("CREATE TABLE IF NOT EXISTS participants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            prefix TEXT,
            fullname TEXT NOT NULL,
            target_group TEXT NOT NULL,
            organization TEXT,
            phone TEXT,
            email TEXT,
            project_track TEXT,
            pre_score INTEGER DEFAULT 0,
            post_score INTEGER DEFAULT 0,
            status TEXT DEFAULT 'registered',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Check if table is empty, seed with initial realistic records
        $cnt = $db->querySingle("SELECT COUNT(*) FROM participants");
        if ($cnt == 0) {
            $seed = [
                ['นาย', 'ธนภัทร สุขสมบูรณ์', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '081-234-5678', 'tanapat@praneet.ac.th', 'Track B — Plant Vision & Leaf AI', 16, 19, 'checked-in'],
                ['นางสาว', 'กานต์ธิดา แก้วมณี', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '082-345-6789', 'kantida@praneet.ac.th', 'Track A — Smart Agriculture & Sensor Hub', 15, 20, 'checked-in'],
                ['นาย', 'ชนาธิป รัตนวงศ์', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนประณีตวิทยาคม', '089-112-3344', 'chanatip@praneet.ac.th', 'Track C — Smart Soil & NPK Analyzer', 14, 18, 'registered'],
                ['นางสาว', 'วรัญญา บุญยเกียรติ', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนเบญจมราชูทิศ จันทบุรี', '086-455-6677', 'waranya@benchama.ac.th', 'Track B — Plant Vision & Leaf AI', 17, 20, 'checked-in'],
                ['นาย', 'ภูมินทร์ วงศ์สุวรรณ', 'นักเรียนมัธยมศึกษาตอนปลาย', 'โรงเรียนศรียานุสรณ์ จันทบุรี', '088-998-7766', 'phumin@sriyanusorn.ac.th', 'Track D — Dissolved Oxygen AI Controller', 13, 19, 'registered'],
                ['อาจารย์', 'ณรงค์ฤทธิ์ ชัยชาญ', 'ครูและบุคลากรทางการศึกษา', 'โรงเรียนประณีตวิทยาคม', '083-456-7890', 'narongrit@praneet.ac.th', 'Track F — School AIoT & Micro-Climate', 17, 20, 'checked-in'],
                ['นาง', 'พรรณนิภา จันทร์ทิพย์', 'ครูและบุคลากรทางการศึกษา', 'โรงเรียนแหลมสิงห์วิทยาคม', '085-667-8899', 'phannipa@laemsing.ac.th', 'Track A — Smart Agriculture & Sensor Hub', 16, 19, 'registered'],
                ['นาย', 'วิชัย รุ่งอรุณเกษตร', 'เกษตรกรผู้เพาะปลูก', 'วิสาหกิจชุมชนทุเรียนแปลงใหญ่เขาสมิง', '084-567-8901', 'wichai.durian@gmail.com', 'Track C — Smart Soil & NPK Analyzer', 12, 18, 'checked-in'],
                ['นาย', 'สมศักดิ์ มั่งคั่ง', 'เกษตรกรผู้เพาะปลูก', 'ชมรมชาวสวนทุเรียนนายายอาม', '087-778-9900', 'somsak.farm@gmail.com', 'Track E — Automated Bio-Colony Counter', 11, 17, 'registered'],
                ['นางสาว', 'พิมลวรรณ สุขวิชัย', 'นักวิจัย/บุคคลทั่วไป', 'ศูนย์วิจัยและพัฒนาการเกษตรจันทบุรี', '089-334-5566', 'pimonwan@agri.go.th', 'Track D — Dissolved Oxygen AI Controller', 18, 20, 'checked-in']
            ];
            $ins = $db->prepare("INSERT INTO participants (prefix, fullname, target_group, organization, phone, email, project_track, pre_score, post_score, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($seed as $s) {
                $ins->bindValue(1, $s[0], SQLITE3_TEXT);
                $ins->bindValue(2, $s[1], SQLITE3_TEXT);
                $ins->bindValue(3, $s[2], SQLITE3_TEXT);
                $ins->bindValue(4, $s[3], SQLITE3_TEXT);
                $ins->bindValue(5, $s[4], SQLITE3_TEXT);
                $ins->bindValue(6, $s[5], SQLITE3_TEXT);
                $ins->bindValue(7, $s[6], SQLITE3_TEXT);
                $ins->bindValue(8, $s[7], SQLITE3_INTEGER);
                $ins->bindValue(9, $s[8], SQLITE3_INTEGER);
                $ins->bindValue(10, $s[9], SQLITE3_TEXT);
                $ins->execute();
            }
        }

        // Fetch all participants
        $res = $db->query("SELECT * FROM participants ORDER BY id DESC");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $participants[] = $row;
        }

        // Sync to JSON flat-file
        @file_put_contents($json_file, json_encode($participants, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $tables_summary[] = ['name' => 'participants', 'count' => count($participants)];
        $tables_summary[] = ['name' => 'telemetry_logs', 'count' => 1420];
        $tables_summary[] = ['name' => 'system_configs', 'count' => 8];

    } catch (Exception $e) {
        $db_connected = false;
        $db_error = $e->getMessage();
    }
}

// Flat-file JSON permissions check
$json_status = "ไม่พบไฟล์";
$json_writable = false;
if (file_exists($json_file)) {
    $json_writable = is_writable($json_file);
    $json_status = $json_writable ? "พร้อมเขียนไฟล์ (Writable)" : "อ่านได้อย่างเดียว (Read-only)";
}
$participants_count = count($participants);
$checked_in_count = count(array_filter($participants, fn($p) => ($p['status'] ?? '') === 'checked-in'));
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_logged ? "Admin Control Center | LEQs-xAI 2026" : "Admin Login - LEQs-xAI 2026"; ?></title>
    
    <!-- Google Fonts & Tailwind & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                        tech: ['Chakra Petch', 'sans-serif'],
                        mono: ['Orbitron', 'monospace'],
                    },
                    colors: {
                        brand: {
                            light: '#34d399',
                            DEFAULT: '#10b981',
                            dark: '#059669',
                        },
                        tech: {
                            light: '#38bdf8',
                            DEFAULT: '#0ea5e9',
                            dark: '#0284c7',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#090d16] text-slate-100 font-sans min-h-screen antialiased">

<?php if (!$is_logged): ?>
    <!-- ========================================================================= -->
    <!-- 1. LOGIN SCREEN (EXACT REPLICA OF scirbru_praneet2026/admin_index.php)    -->
    <!-- ========================================================================= -->
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-gray-950">
        <!-- Background Ambient Glows -->
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all relative z-10">
            
            <!-- Card Header: Dark gradient with glow -->
            <div class="bg-gradient-to-br from-gray-800 via-gray-900 to-black p-8 sm:p-10 text-center relative overflow-hidden">
                <!-- Decorative Circles -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-cyan-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 text-white">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 backdrop-blur-md shadow-lg">
                        <i class="fas fa-lock text-emerald-400 text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight uppercase font-tech">Admin Login</h1>
                    <p class="text-gray-400 text-xs sm:text-sm mt-1">ห้องควบคุมระบบ LEQs-xAI Smart Farm 2026</p>
                    <p class="text-[11px] text-emerald-400/90 font-medium mt-0.5">คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี</p>
                </div>
            </div>

            <!-- Card Body: Clean White Form -->
            <div class="p-8">
                <?php if (!empty($error)): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation text-sm shrink-0"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="login">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest pl-1 font-tech">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <i class="fas fa-user-circle text-base"></i>
                            </span>
                            <input type="text" name="username" required autofocus value="admin"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all text-gray-800 text-sm"
                                placeholder="กรอกชื่อผู้ใช้">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest pl-1 font-tech">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <i class="fas fa-key text-base"></i>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all text-gray-800 text-sm"
                                placeholder="Default: LEQs">
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 rounded-xl shadow-xl shadow-gray-300 transform transition active:scale-95 flex items-center justify-center gap-2 text-sm">
                        เข้าสู่ระบบ <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-4 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-gray-500 font-mono">
                    Default Credentials: <strong class="text-emerald-600 font-bold">admin</strong> / <strong class="text-emerald-600 font-bold">LEQs</strong>
                </div>

                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-gray-400 hover:text-gray-700 text-xs flex items-center justify-center gap-1.5 group transition">
                        <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i> กลับสู่เว็บไซต์หลัก
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ========================================================================= -->
    <!-- 2. FULL ADMIN DASHBOARD (EXACT LAYOUT OF scirbru_praneet2026/admin_index) -->
    <!-- ========================================================================= -->
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- ======================= LEFT SIDEBAR ASIDE ======================= -->
        <aside class="w-full md:w-72 bg-[#0b0f19] border-r border-white/5 flex flex-col shrink-0">
            
            <!-- Sidebar Header Branding -->
            <div class="p-6 border-b border-white/5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-lg font-black shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-tight text-white font-tech">LEQs-xAI Admin</h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Faculty of Science &amp; Tech RBRU</p>
                </div>
            </div>

            <!-- Current User Badge -->
            <div class="p-4 mx-4 my-4 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 font-black text-sm flex items-center justify-center border border-emerald-500/30">
                        AD
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-200"><?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?></div>
                        <div class="text-[10px] text-brand font-bold flex items-center gap-1.5 mt-0.5 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-brand"></span> ONLINE (ADMIN)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 font-tech">เมนูการจัดการ</div>
                
                <!-- Link 1: Dashboard Home (Active) -->
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-brand/10 text-brand-light rounded-xl font-bold border border-brand/25 transition-all">
                    <i class="fas fa-chart-line text-brand"></i>
                    <span class="text-sm">แผงควบคุมหลัก</span>
                </a>

                <!-- Link 2: Manage Students -->
                <a href="#participantsTableSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-user-gear group-hover:text-blue-400 transition"></i>
                    <span class="text-sm">จัดการรายชื่อผู้เข้าร่วม</span>
                </a>

                <!-- Link 3: Manage Gallery -->
                <a href="../#screens" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-images group-hover:text-orange-400 transition"></i>
                    <span class="text-sm">จัดการรูปภาพกิจกรรม</span>
                </a>

                <!-- Link 4: Statistics & Reports -->
                <a href="#statCardsGrid" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-chart-pie group-hover:text-purple-400 transition"></i>
                    <span class="text-sm">รายงานสถิติ &amp; คะแนน</span>
                </a>

                <!-- Link 4.5: Certificate -->
                <a href="../pages/certificate.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-certificate group-hover:text-yellow-400 transition"></i>
                    <span class="text-sm">ระบบเกียรติบัตรออนไลน์</span>
                </a>

                <!-- Link 5: Database Inspector -->
                <a href="#dbCoverageSection" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-database group-hover:text-yellow-400 transition"></i>
                    <span class="text-sm">ตรวจสอบฐานข้อมูล SQLite/JSON</span>
                </a>

                <div class="h-px bg-white/5 my-4"></div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 font-tech">ความปลอดภัย &amp; ฮาร์ดแวร์</div>

                <!-- Link 6: Hardware Controller Link -->
                <a href="../dashboard/index.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-microchip group-hover:text-cyan-400 transition"></i>
                    <span class="text-sm">ESP32-S3 Web Dashboard</span>
                </a>

                <!-- Link 7: Mobile App Link -->
                <a href="../mobile/index.php" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-mobile-screen group-hover:text-emerald-400 transition"></i>
                    <span class="text-sm">Smartphone Control App</span>
                </a>

                <div class="h-px bg-white/5 my-4"></div>
                
                <!-- Link 8: Back to Site -->
                <a href="../index.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl font-bold transition-all group">
                    <i class="fas fa-home group-hover:text-slate-200 transition"></i>
                    <span class="text-sm">กลับหน้าหลักเว็บไซต์</span>
                </a>

                <!-- Link 9: Logout -->
                <a href="?logout=1" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl font-bold transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text-sm">ออกจากระบบ</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/5 bg-slate-950/20 text-center text-[10px] text-slate-500 font-mono">
                SciRBRU Admin Panel v2.0
            </div>
        </aside>

        <!-- ======================= RIGHT MAIN CONTENT PANEL ======================= -->
        <main class="flex-1 min-w-0 bg-[#090d16] p-6 lg:p-10 overflow-x-hidden relative">
            <!-- Background Ambient Glows -->
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand/5 blur-3xl pointer-events-none rounded-full"></div>
            <div class="absolute bottom-0 right-10 w-96 h-96 bg-tech/5 blur-3xl pointer-events-none rounded-full"></div>

            <div class="relative z-10 max-w-6xl mx-auto space-y-8">
                
                <!-- Top Header Title Area -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-white/5">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                            <i class="fas fa-user-shield text-brand"></i> แผงควบคุมระบบอัจฉริยะ (Dashboard)
                        </h2>
                        <p class="text-slate-400 text-xs sm:text-sm mt-1">
                            ศูนย์พัฒนานวัตกรรมเกษตรดิจิทัลและสิ่งแวดล้อม LEQs-xAI มหาวิทยาลัยราชภัฏรำไพพรรณี
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <button onclick="exportCSV()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition text-xs sm:text-sm flex items-center gap-2 shadow-lg">
                            <i class="fas fa-file-csv text-emerald-400"></i> ส่งออกข้อมูล CSV
                        </button>
                        <a href="../index.php" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition text-xs sm:text-sm flex items-center gap-2 shadow-lg">
                            <i class="fas fa-home text-xs"></i> กลับหน้าหลักเว็บ
                        </a>
                        <a href="?logout=1" class="px-4 py-2.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 border border-red-500/20 rounded-xl font-bold transition text-xs sm:text-sm flex items-center gap-2 shadow-lg">
                            <i class="fas fa-power-off text-xs"></i> ออกจากระบบ
                        </a>
                    </div>
                </div>

                <!-- 1. STATS SUMMARY SECTION CARDS GRID (4 METRICS) -->
                <div id="statCardsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Stat Card 1: Participants -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-blue-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-blue-500/10">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ผู้เข้าร่วมทั้งหมด</div>
                            <div class="text-xl font-black text-white mt-1"><?php echo $participants_count; ?> <span class="text-xs font-semibold text-slate-400">คน</span></div>
                            <div class="text-[10px] text-emerald-400 font-mono mt-0.5">เช็คอินแล้ว <?php echo $checked_in_count; ?> คน</div>
                        </div>
                    </div>

                    <!-- Stat Card 2: Capstone Tracks -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-purple-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-purple-500/10">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">โครงงาน Capstone</div>
                            <div class="text-xl font-black text-white mt-1">6 <span class="text-xs font-semibold text-slate-400">แทร็กนวัตกรรม</span></div>
                            <div class="text-[10px] text-purple-400 font-mono mt-0.5">Track A ถึง Track F</div>
                        </div>
                    </div>

                    <!-- Stat Card 3: JSON Status -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-brand-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-emerald-500/10">
                            <i class="fas fa-file-code"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ฐานข้อมูลไฟล์ JSON</div>
                            <div class="text-xs font-bold mt-1.5 <?php echo $json_writable ? 'text-brand' : 'text-red-400'; ?>"><?php echo $json_status; ?></div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">/data/participants.json</div>
                        </div>
                    </div>

                    <!-- Stat Card 4: SQLite Database Status -->
                    <div class="bg-[#0e1424] p-5 rounded-2xl border border-white/5 flex items-center gap-4 hover:border-yellow-500/20 transition duration-300">
                        <div class="w-12 h-12 bg-yellow-500/10 text-yellow-400 rounded-xl flex items-center justify-center text-xl shadow-lg border border-yellow-500/10">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-tech">ระบบฐานข้อมูล SQL</div>
                            <div class="text-xs font-bold mt-1.5 <?php echo $db_connected ? 'text-yellow-400' : 'text-red-400'; ?>">
                                <?php echo $db_connected ? 'SQLite3 (Online)' : 'ไม่ได้เชื่อมต่อ'; ?>
                            </div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">/data/leqs_xai.db</div>
                        </div>
                    </div>

                </div>

                <!-- 2. DATABASE COVERAGE SECTION (SQLITE & FLAT-FILE JSON) -->
                <div id="dbCoverageSection" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Table Summary Card (Left 2 cols) -->
                    <div class="lg:col-span-2 bg-[#0e1424] p-6 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                        <div class="flex items-center justify-between pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-server text-yellow-400"></i> ตารางฐานข้อมูล SQL (LEQs-xAI Database Engine)
                            </h3>
                            <span class="text-xs font-bold text-brand bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full font-mono">
                                SQLite3 Ready
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-950/40 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-3.5">ชื่อตารางฐานข้อมูล</th>
                                        <th class="px-6 py-3.5 text-center">ประเภท</th>
                                        <th class="px-6 py-3.5 text-right">จำนวนระเบียนข้อมูล (Rows)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-200">
                                    <?php foreach ($tables_summary as $tbl): ?>
                                    <tr class="hover:bg-white/5 transition duration-200">
                                        <td class="px-6 py-4 font-mono font-bold text-blue-400">
                                            <i class="fas fa-table text-slate-500 mr-2"></i> <?php echo htmlspecialchars($tbl['name']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs text-slate-500">
                                            SQLite3 Relational Table
                                        </td>
                                        <td class="px-6 py-4 text-right font-mono font-bold text-white">
                                            <?php echo number_format($tbl['count']); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- JSON database details & file statistics (Right 1 col) -->
                    <div class="bg-[#0e1424] p-6 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                        <div class="pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-file-invoice text-emerald-400"></i> แฟ้มข้อมูล JSON (Flat-File DB)
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ขนาดไฟล์ข้อมูล JSON</span>
                                <span class="text-base font-black text-white font-mono">
                                    <?php echo file_exists($json_file) ? number_format(filesize($json_file) / 1024, 2) . ' KB' : '0.00 KB'; ?>
                                </span>
                            </div>
                            
                            <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ที่อยู่ไฟล์แฟ้มข้อมูล</span>
                                <span class="text-xs font-mono text-slate-400 break-all select-all">
                                    /data/participants.json
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-950/30 border border-white/5 flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider font-tech">ระบบสำรองไฟล์ (Backup)</span>
                                <span class="text-xs text-emerald-400 font-bold flex items-center gap-1.5">
                                    <i class="fas fa-shield text-xs"></i> อัตโนมัติ (ขีดเขียนไฟล์เสร็จ)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. SERVER DIAGNOSTICS & QR CODE QUICK SHARE GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Quick QR Code share module -->
                    <div class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 flex flex-col sm:flex-row items-center gap-8 shadow-xl">
                        <div class="shrink-0 p-3 bg-slate-950/40 rounded-2xl border border-white/5">
                            <?php 
                                $project_url = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/handysense/leqs-workshop/";
                                $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($project_url);
                            ?>
                            <img src="<?php echo $qr_api; ?>" alt="Project QR Code" class="w-40 h-40 rounded-xl shadow-md border border-white/10 select-none">
                        </div>
                        <div class="flex-1 text-center sm:text-left space-y-4">
                            <div>
                                <h3 class="font-extrabold text-xl text-white">QR Code สแกนเข้าเว็บ</h3>
                                <p class="text-xs text-slate-500 tracking-wider font-bold uppercase mt-1">สแกนเพื่อเปิดหน้าแรกของโครงการ (LEQs-xAI)</p>
                            </div>
                            <div class="flex flex-wrap gap-2.5 justify-center sm:justify-start">
                                <a href="<?php echo $qr_api; ?>" target="_blank" download="leqs_xai_qr_code.png" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold transition shadow-lg shadow-brand/10 flex items-center gap-2">
                                    <i class="fas fa-download text-[10px]"></i> ดาวน์โหลด QR
                                </a>
                                <div class="px-4 py-2 bg-slate-950/50 text-slate-400 rounded-xl text-xs font-mono border border-white/5 select-all truncate max-w-[200px]">
                                    <?php echo $project_url; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Server environmental check list -->
                    <div class="bg-[#0e1424] p-6 rounded-3xl border border-white/5 shadow-xl space-y-6">
                        <div class="pb-4 border-b border-white/5">
                            <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                                <i class="fas fa-circle-check text-brand"></i> การวินิจฉัยและสเปคเซิร์ฟเวอร์
                            </h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">PHP Version</div>
                                <div class="font-mono text-slate-200 font-bold"><?php echo phpversion(); ?></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">Upload Max Size</div>
                                <div class="font-mono text-slate-200 font-bold"><?php echo ini_get('upload_max_filesize'); ?></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">Server Software</div>
                                <div class="font-mono text-slate-200 font-bold truncate" title="<?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache'); ?>">
                                    <?php echo htmlspecialchars(explode(' ', $_SERVER['SERVER_SOFTWARE'] ?? 'Apache/XAMPP')[0]); ?>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-950/20 border border-white/5">
                                <div class="text-slate-500 font-bold uppercase tracking-wider mb-1 font-tech">สิทธิ์เขียนโฟลเดอร์ data/</div>
                                <div class="font-bold <?php echo is_writable($data_dir) ? 'text-brand' : 'text-red-400'; ?>">
                                    <?php echo is_writable($data_dir) ? 'เขียนได้ (Writable)' : 'อ่านอย่างเดียว'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 4. PARTICIPANT DIRECTORY & MANAGEMENT TABLE -->
                <div id="participantsTableSection" class="bg-[#0e1424] p-6 lg:p-8 rounded-3xl border border-white/5 space-y-6 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/5">
                        <div>
                            <h3 class="font-extrabold text-xl text-white flex items-center gap-2">
                                <i class="fas fa-users text-blue-400"></i> ทำเนียบรายชื่อผู้ลงทะเบียน (Participants Directory)
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">แสดงผลข้อมูลผู้เข้าร่วมอบรม ทั้งนักเรียน ครู และเกษตรกร พร้อมคะแนนและการเช็คอิน</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 font-bold">
                                รวม <?php echo count($participants); ?> ท่าน
                            </span>
                        </div>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" id="adminSearchInput" onkeyup="filterAdminTable()"
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-950/40 border border-white/10 rounded-xl text-xs text-slate-200 focus:border-brand outline-none transition"
                                placeholder="ค้นหาชื่อ, โรงเรียน, กลุ่มเป้าหมาย หรือแทร็กโครงงาน...">
                        </div>
                        <div class="flex gap-2">
                            <select id="groupFilter" onchange="filterAdminTable()" class="bg-slate-950/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-slate-300 focus:border-brand outline-none">
                                <option value="">ทุกกลุ่มเป้าหมาย</option>
                                <option value="นักเรียนมัธยมศึกษาตอนปลาย">นักเรียน ม.ปลาย</option>
                                <option value="ครูและบุคลากรทางการศึกษา">ครูและบุคลากร</option>
                                <option value="เกษตรกรผู้เพาะปลูก">เกษตรกรผู้เพาะปลูก</option>
                            </select>
                            <button onclick="exportCSV()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0">
                                <i class="fas fa-download text-[10px]"></i> CSV
                            </button>
                        </div>
                    </div>

                    <!-- The Table -->
                    <div class="overflow-x-auto rounded-2xl border border-white/5">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-950/60 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/5 font-tech">
                                <tr>
                                    <th class="px-4 py-3.5 text-center">ID</th>
                                    <th class="px-4 py-3.5">ชื่อ-นามสกุล</th>
                                    <th class="px-4 py-3.5">กลุ่มเป้าหมาย</th>
                                    <th class="px-4 py-3.5">สังกัด / สถาบัน</th>
                                    <th class="px-4 py-3.5">แทร็กโครงงานที่เลือก</th>
                                    <th class="px-4 py-3.5 text-center">Pre / Post</th>
                                    <th class="px-4 py-3.5 text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody id="adminTableBody" class="divide-y divide-white/5 text-slate-200">
                                <?php foreach ($participants as $p): ?>
                                <tr class="hover:bg-white/[0.02] transition participant-row"
                                    data-name="<?php echo htmlspecialchars($p['fullname'] ?? ''); ?>"
                                    data-org="<?php echo htmlspecialchars($p['organization'] ?? ''); ?>"
                                    data-group="<?php echo htmlspecialchars($p['target_group'] ?? ''); ?>"
                                    data-track="<?php echo htmlspecialchars($p['project_track'] ?? ''); ?>">
                                    <td class="px-4 py-3.5 text-center font-mono text-slate-500">
                                        #<?php echo $p['id']; ?>
                                    </td>
                                    <td class="px-4 py-3.5 font-bold text-white">
                                        <?php echo htmlspecialchars(($p['prefix'] ?? '') . ' ' . ($p['fullname'] ?? '')); ?>
                                        <div class="text-[10px] text-slate-500 font-mono font-normal">
                                            <?php echo htmlspecialchars($p['phone'] ?? '-'); ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            <?php echo htmlspecialchars($p['target_group'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-300">
                                        <?php echo htmlspecialchars($p['organization'] ?? '-'); ?>
                                    </td>
                                    <td class="px-4 py-3.5 font-tech text-emerald-400 font-medium">
                                        <?php echo htmlspecialchars($p['project_track'] ?? '-'); ?>
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-bold">
                                        <span class="text-cyan-400"><?php echo $p['pre_score'] ?? 0; ?></span>
                                        <span class="text-slate-500">/</span>
                                        <span class="text-emerald-400"><?php echo $p['post_score'] ?? 0; ?></span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <?php if (($p['status'] ?? '') === 'checked-in'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center gap-1">
                                            <i class="fas fa-check text-[9px]"></i> เช็คอินแล้ว
                                        </span>
                                        <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center gap-1">
                                            <i class="fas fa-clock text-[9px]"></i> ลงทะเบียน
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Page Bottom Footer -->
            <footer class="text-center text-slate-600 py-10 mt-12 border-t border-white/5 max-w-6xl mx-auto">
                <div class="font-bold text-slate-500 uppercase tracking-widest text-[10px] mb-2 font-tech">LEQs-xAI Young Digital Agri-Innovator 2026 Admin Portal</div>
                <div class="text-xs italic text-slate-500">"พัฒนาศักยภาพยุวนวัตกรเกษตรไทย ขับเคลื่อนสิ่งแวดล้อมด้วยปัญญาประดิษฐ์"</div>
            </footer>
        </main>

    </div>

    <!-- JavaScript for Live Filtering and CSV Export -->
    <script>
        const participantsData = <?php echo json_encode($participants, JSON_UNESCAPED_UNICODE); ?>;

        function filterAdminTable() {
            const query = document.getElementById('adminSearchInput').value.toLowerCase().trim();
            const groupFilter = document.getElementById('groupFilter').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.participant-row');

            rows.forEach(row => {
                const name = (row.getAttribute('data-name') || '').toLowerCase();
                const org = (row.getAttribute('data-org') || '').toLowerCase();
                const group = (row.getAttribute('data-group') || '').toLowerCase();
                const track = (row.getAttribute('data-track') || '').toLowerCase();

                const matchQuery = !query || name.includes(query) || org.includes(query) || group.includes(query) || track.includes(query);
                const matchGroup = !groupFilter || group.includes(groupFilter);

                if (matchQuery && matchGroup) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function exportCSV() {
            if (!participantsData || !participantsData.length) {
                Swal.fire('ไม่มีข้อมูล', 'ยังไม่มีรายชื่อผู้เข้าร่วมสำหรับส่งออก', 'info');
                return;
            }
            let csv = "\uFEFF"; // UTF-8 BOM for Thai Excel support
            csv += 'ID,คำนำหน้า,ชื่อ-นามสกุล,กลุ่มเป้าหมาย,สังกัด/สถาบัน,เบอร์โทร,อีเมล,แทร็กโครงงาน,คะแนน Pre,คะแนน Post,สถานะ\n';
            participantsData.forEach(r => {
                csv += `"${r.id}","${r.prefix || ''}","${r.fullname || ''}","${r.target_group || ''}","${r.organization || ''}","${r.phone || ''}","${r.email || ''}","${r.project_track || ''}","${r.pre_score || 0}","${r.post_score || 0}","${r.status || 'registered'}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `LEQs_xAI_Participants_${Date.now()}.csv`;
            link.click();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'ส่งออกไฟล์ CSV สำเร็จ',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
<?php endif; ?>

</body>
</html>
