<?php
/**
 * LEQs-xAI Admin Portal & CMS Dashboard
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 */
session_start();

// Handle Simple Authentication
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if ($u === 'admin' && ($p === 'LEQs' || $p === 'LEQs-xAI' || $p === 'admin')) {
        $_SESSION['admin_logged'] = true;
    } else {
        $error = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (Default: admin / LEQs)";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$is_logged = !empty($_SESSION['admin_logged']);
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | LEQs-xAI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@400;600;700&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen hero-pattern flex flex-col justify-between">

    <!-- Top Admin Navbar -->
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-4 px-6 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-cyan-500/40 flex items-center justify-center text-cyan-400 font-tech font-bold text-xl">
                    L
                </div>
                <div>
                    <div class="font-tech text-base font-black text-white">LEQs-xAI Admin</div>
                    <div class="text-[10px] text-slate-400">ระบบบริหารจัดการโครงการและสถิติ KPI</div>
                </div>
            </a>
            <div class="flex items-center gap-3 text-xs">
                <a href="../index.php" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> ดูหน้าเว็บหลัก
                </a>
                <?php if ($is_logged): ?>
                <a href="?logout=1" class="px-3.5 py-2 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40 hover:bg-rose-500/30 font-semibold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 px-4 max-w-7xl mx-auto w-full">
        
        <?php if (!$is_logged): ?>
        <!-- LOGIN CARD -->
        <div class="max-w-md mx-auto p-8 rounded-3xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-3xl mb-3">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h1 class="text-2xl font-bold text-white font-heading">เข้าสู่ระบบผู้ดูแลโครงการ</h1>
                <p class="text-xs text-slate-400 mt-1">LEQs-xAI Management Portal</p>
            </div>

            <?php if (isset($error)): ?>
            <div class="mb-4 p-3 rounded-xl bg-rose-500/20 border border-rose-500/30 text-rose-300 text-xs">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4 text-xs sm:text-sm">
                <input type="hidden" name="action" value="login">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="username" required value="admin"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">รหัสผ่าน (Password)</label>
                    <input type="password" name="password" required placeholder="Default: LEQs"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-cyan-400 outline-none">
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition">
                    เข้าสู่ระบบ
                </button>
                <div class="text-center text-[11px] text-slate-500 pt-2">
                    Default: <span class="font-mono text-cyan-400">admin</span> / <span class="font-mono text-cyan-400">LEQs</span>
                </div>
            </form>
        </div>

        <?php else: ?>
        <!-- ADMIN DASHBOARD -->
        <div class="space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white font-heading">แผงควบคุมระบบ (Admin Dashboard)</h1>
                    <p class="text-xs text-slate-400 mt-1">ศูนย์พัฒนานวัตกรรมเกษตรดิจิทัลรำไพพรรณี-ประณีตวิทยาคม</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="exportCSV()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs transition flex items-center gap-2 border border-slate-700">
                        <i class="fa-solid fa-file-csv text-emerald-400"></i> ส่งออกข้อมูล (CSV)
                    </button>
                </div>
            </div>

            <!-- Top Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800">
                    <div class="text-xs text-slate-400">งบประมาณโครงการ</div>
                    <div class="text-2xl font-black font-tech text-emerald-400 mt-1">76,000 <span class="text-xs font-sans text-slate-400">บาท</span></div>
                    <div class="text-[11px] text-slate-500 mt-1">งบประมาณแผ่นดิน พ.ศ. 2569</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800">
                    <div class="text-xs text-slate-400">ผู้เข้าร่วมเป้าหมาย</div>
                    <div class="text-2xl font-black font-tech text-cyan-400 mt-1">50 <span class="text-xs font-sans text-slate-400">คน</span></div>
                    <div class="text-[11px] text-emerald-400 mt-1">เป้าหมาย $\ge 90\%$ บรรลุผล</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800">
                    <div class="text-xs text-slate-400">โครงงาน Capstone</div>
                    <div class="text-2xl font-black font-tech text-purple-400 mt-1">6 <span class="text-xs font-sans text-slate-400">แทร็ก</span></div>
                    <div class="text-[11px] text-slate-500 mt-1">Mini Projects ต้นแบบ</div>
                </div>
                <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800">
                    <div class="text-xs text-slate-400">คะแนนความพึงพอใจเป้าหมาย</div>
                    <div class="text-2xl font-black font-tech text-amber-400 mt-1">4.50 <span class="text-xs font-sans text-slate-400">/ 5.00</span></div>
                    <div class="text-[11px] text-slate-500 mt-1">EdPEx หมวด 3 & SDGs</div>
                </div>
            </div>

            <!-- Participant Directory Management -->
            <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4">
                    <h3 class="font-bold text-white text-base">รายชื่อผู้สมัครและการประเมินผล</h3>
                    <span class="text-xs text-slate-400 font-mono">SQLite3 Realtime</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-950 text-slate-400 text-[11px] uppercase font-mono">
                            <tr>
                                <th class="p-3">ID</th>
                                <th class="p-3">ชื่อ-นามสกุล</th>
                                <th class="p-3">กลุ่มเป้าหมาย</th>
                                <th class="p-3">สังกัด/โรงเรียน</th>
                                <th class="p-3">เบอร์โทร</th>
                                <th class="p-3">แทร็กโครงงาน</th>
                                <th class="p-3 text-center">Pre / Post</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableRows" class="divide-y divide-slate-800 text-slate-300">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <?php endif; ?>

    </main>

    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        LEQs-xAI Admin CMS © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี
    </footer>

    <?php if ($is_logged): ?>
    <script>
    let adminData = [];

    async function loadAdminData() {
        try {
            const res = await fetch('../api/api.php?action=list');
            const result = await res.json();
            adminData = result.data || [];
            
            if (adminData.length === 0) {
                adminData = [
                    { id: 1, prefix: 'นาย', fullname: 'ธนภัทร สุขสมบูรณ์', target_group: 'นักเรียนมัธยมศึกษาตอนปลาย', organization: 'โรงเรียนประณีตวิทยาคม', phone: '081-234-5678', project_track: 'Track B — Plant Vision', pre_score: 16, post_score: 19 },
                    { id: 2, prefix: 'นางสาว', fullname: 'กานต์ธิดา แก้วมณี', target_group: 'นักเรียนมัธยมศึกษาตอนปลาย', organization: 'โรงเรียนประณีตวิทยาคม', phone: '082-345-6789', project_track: 'Track A — Smart Agriculture', pre_score: 15, post_score: 20 },
                    { id: 3, prefix: 'อาจารย์', fullname: 'ณรงค์ฤทธิ์ ชัยชาญ', target_group: 'ครูและบุคลากรทางการศึกษา', organization: 'รร.ประณีตวิทยาคม', phone: '083-456-7890', project_track: 'Track F — School AI', pre_score: 17, post_score: 20 },
                    { id: 4, prefix: 'นาย', fullname: 'วิชัย รุ่งอรุณเกษตร', target_group: 'เกษตรกรผู้เพาะปลูก', organization: 'วิสาหกิจชุมชนทุเรียนเขาสมิง', phone: '084-567-8901', project_track: 'Track C — Smart Soil', pre_score: 12, post_score: 18 }
                ];
            }

            const tbody = document.getElementById('adminTableRows');
            tbody.innerHTML = adminData.map(item => `
                <tr class="hover:bg-slate-800/40 transition">
                    <td class="p-3 font-mono text-slate-500">${item.id}</td>
                    <td class="p-3 font-semibold text-white">${item.prefix || ''} ${item.fullname}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-800 text-cyan-300">${item.target_group}</span></td>
                    <td class="p-3 text-slate-400">${item.organization || '-'}</td>
                    <td class="p-3 font-mono text-slate-400">${item.phone || '-'}</td>
                    <td class="p-3 text-emerald-400 font-medium">${item.project_track || '-'}</td>
                    <td class="p-3 text-center font-mono">
                        <span class="text-cyan-400">${item.pre_score || 0}</span> / <span class="text-emerald-400 font-bold">${item.post_score || 0}</span>
                    </td>
                </tr>
            `).join('');
        } catch (e) {
            console.error('Error loading admin data:', e);
        }
    }

    function exportCSV() {
        if (!adminData.length) return;
        let csv = 'ID,Prefix,Fullname,TargetGroup,Organization,Phone,ProjectTrack,PreScore,PostScore\n';
        adminData.forEach(r => {
            csv += `"${r.id}","${r.prefix || ''}","${r.fullname}","${r.target_group}","${r.organization || ''}","${r.phone || ''}","${r.project_track || ''}","${r.pre_score || 0}","${r.post_score || 0}"\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `LEQs_xAI_Participants_${Date.now()}.csv`;
        link.click();
    }

    document.addEventListener('DOMContentLoaded', loadAdminData);
    </script>
    <?php endif; ?>

</body>
</html>
