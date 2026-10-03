<?php
/**
 * LEQs-xAI Student & Participant Directory
 */
$page_title = "ประกาศรายชื่อผู้สมัคร | LEQs-xAI";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Kanit:wght@400;600;700&family=Chakra+Petch:wght@500;600;700&family=Orbitron:wght@600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between hero-pattern">

    <!-- Top Navbar -->
    <nav class="bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 py-4 px-6 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="../index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-cyan-500/40 flex items-center justify-center text-cyan-400 font-tech font-bold text-xl">
                    L
                </div>
                <div>
                    <div class="font-tech text-base font-black text-white">LEQs-xAI</div>
                    <div class="text-[10px] text-slate-400">ทำเนียบรายชื่อผู้สมัครเข้าร่วมอบรม</div>
                </div>
            </a>
            <div class="flex items-center gap-3 text-xs">
                <a href="../index.php" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> หน้าหลัก
                </a>
                <a href="register.php" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-slate-950 font-bold hover:scale-105 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-user-plus"></i> สมัครเพิ่ม
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 max-w-7xl mx-auto w-full">
        
        <div class="text-center mb-10">
            <span class="px-3.5 py-1 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase tracking-wider">
                Participant Directory
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white font-heading mt-2">
                ประกาศรายชื่อผู้สมัครเข้าร่วมอบรม LEQs-xAI
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-2">
                ตรวจสอบรายชื่อ สังกัด และสถานะการจัดกลุ่มแทร็กโครงงาน Capstone
            </p>
        </div>

        <!-- Filter & Search Controls -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 mb-8 shadow-xl">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                <div class="sm:col-span-8 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                    <input type="text" id="searchInput" oninput="renderTable()" placeholder="ค้นหาด้วยชื่อ-นามสกุล หรือโรงเรียน/สังกัด..."
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-xs sm:text-sm focus:border-cyan-400 outline-none">
                </div>
                <div class="sm:col-span-4">
                    <select id="groupFilter" onchange="renderTable()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-xs sm:text-sm focus:border-cyan-400 outline-none">
                        <option value="">ทุกกลุ่มเป้าหมาย</option>
                        <option value="นักเรียน">นักเรียนมัธยมศึกษาตอนปลาย</option>
                        <option value="ครู">ครูและบุคลากรทางการศึกษา</option>
                        <option value="เกษตรกรผู้เพาะปลูก">เกษตรกรผู้เพาะปลูก</option>
                        <option value="ทั่วไป">ผู้สนใจทั่วไป</option>
                    </select>
                </div>
            </div>

            <!-- Stats Counts -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-4 border-t border-slate-800 text-center text-xs">
                <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                    <span class="text-slate-400">ผู้สมัครทั้งหมด:</span> <b id="statTotal" class="text-cyan-400 font-tech">0</b> คน
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                    <span class="text-slate-400">นักเรียน ม.ปลาย:</span> <b id="statStudent" class="text-emerald-400 font-tech">0</b> คน
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                    <span class="text-slate-400">ครู/อาจารย์:</span> <b id="statTeacher" class="text-purple-400 font-tech">0</b> คน
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                    <span class="text-slate-400">เกษตรกร/ทั่วไป:</span> <b id="statFarmer" class="text-amber-400 font-tech">0</b> คน
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-950/80 text-slate-400 uppercase text-[11px] font-mono border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-4 text-center">ลำดับ</th>
                            <th class="py-3.5 px-4">ชื่อ - นามสกุล</th>
                            <th class="py-3.5 px-4">กลุ่มเป้าหมาย</th>
                            <th class="py-3.5 px-4">โรงเรียน / สังกัด</th>
                            <th class="py-3.5 px-4">แทร็กโครงงาน Capstone</th>
                            <th class="py-3.5 px-4 text-center">แบบทดสอบ</th>
                        </tr>
                    </thead>
                    <tbody id="participantRows" class="divide-y divide-slate-800/60 text-slate-300">
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <i class="fa-solid fa-spinner fa-spin text-2xl text-cyan-400 mb-2"></i><br>
                                กำลังโหลดข้อมูลผู้สมัคร...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        LEQs-xAI Directory © 2026 มหาวิทยาลัยราชภัฏรำไพพรรณี & โรงเรียนประณีตวิทยาคม
    </footer>

    <!-- Logic Script -->
    <script>
    let allData = [];

    async function loadData() {
        try {
            const res = await fetch('../api/api.php?action=list');
            const result = await res.json();
            allData = result.data || [];
            
            // Seed Mock Data if empty
            if (allData.length === 0) {
                allData = [
                    { id: 1, prefix: 'นาย', fullname: 'ธนภัทร สุขสมบูรณ์', target_group: 'นักเรียนมัธยมศึกษาตอนปลาย', organization: 'โรงเรียนประณีตวิทยาคม', project_track: 'Track B — Plant Vision', pre_score: 16, post_score: 19 },
                    { id: 2, prefix: 'นางสาว', fullname: 'กานต์ธิดา แก้วมณี', target_group: 'นักเรียนมัธยมศึกษาตอนปลาย', organization: 'โรงเรียนประณีตวิทยาคม', project_track: 'Track A — Smart Agriculture', pre_score: 15, post_score: 20 },
                    { id: 3, prefix: 'อาจารย์', fullname: 'ณรงค์ฤทธิ์ ชัยชาญ', target_group: 'ครูและบุคลากรทางการศึกษา', organization: 'กลุ่มสาระวิทยาศาสตร์ รร.ประณีตวิทยาคม', project_track: 'Track F — School AI', pre_score: 17, post_score: 20 },
                    { id: 4, prefix: 'นาย', fullname: 'วิชัย รุ่งอรุณเกษตร', target_group: 'เกษตรกรผู้เพาะปลูก', organization: 'วิสาหกิจชุมชนทุเรียนแปลงใหญ่เขาสมิง', project_track: 'Track C — Smart Soil', pre_score: 12, post_score: 18 },
                    { id: 5, prefix: 'นาย', fullname: 'สิทธิพงษ์ จันทร์กระจ่าง', target_group: 'เกษตรกรและผู้สนใจทั่วไป', organization: 'สวนทุเรียนต้นน้ำคลองเขาสมิง', project_track: 'Track E — Edge AI', pre_score: 14, post_score: 19 }
                ];
            }
            renderTable();
        } catch (e) {
            console.error('Error loading data:', e);
        }
    }

    function renderTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('groupFilter').value;
        const tbody = document.getElementById('participantRows');

        let filtered = allData.filter(item => {
            const matchQ = (item.fullname?.toLowerCase().includes(query)) || (item.organization?.toLowerCase().includes(query));
            const matchF = filter === '' || (item.target_group?.includes(filter));
            return matchQ && matchF;
        });

        // Update stats
        document.getElementById('statTotal').innerText = allData.length;
        document.getElementById('statStudent').innerText = allData.filter(x => x.target_group?.includes('นักเรียน')).length;
        document.getElementById('statTeacher').innerText = allData.filter(x => x.target_group?.includes('ครู')).length;
        document.getElementById('statFarmer').innerText = allData.filter(x => x.target_group?.includes('เกษตรกร') || x.target_group?.includes('ทั่วไป')).length;

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-slate-500">ไม่พบรายชื่อตามเงื่อนไขการค้นหา</td></tr>`;
            return;
        }

        tbody.innerHTML = filtered.map((item, idx) => `
            <tr class="hover:bg-slate-800/40 transition">
                <td class="py-3 px-4 text-center font-mono text-slate-500">${idx + 1}</td>
                <td class="py-3 px-4 font-semibold text-white">
                    ${item.prefix || ''} ${item.fullname}
                </td>
                <td class="py-3 px-4">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold ${
                        item.target_group?.includes('นักเรียน') ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' :
                        item.target_group?.includes('ครู') ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' :
                        'bg-amber-500/20 text-amber-400 border border-amber-500/30'
                    }">
                        ${item.target_group}
                    </span>
                </td>
                <td class="py-3 px-4 text-slate-400">${item.organization || '-'}</td>
                <td class="py-3 px-4 text-cyan-300 font-medium">${item.project_track || '-'}</td>
                <td class="py-3 px-4 text-center">
                    <a href="assessment_pre.php?id=${item.id}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-400 font-mono text-xs transition">
                        Pre: ${item.pre_score || 0}/20
                    </a>
                </td>
            </tr>
        `).join('');
    }

    document.addEventListener('DOMContentLoaded', loadData);
    </script>
</body>
</html>
