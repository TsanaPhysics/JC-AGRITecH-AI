<?php
/**
 * ============================================================================
 * LEQs-xAI Mobile App Bottom Navigation Bar
 * Designed following sciexhub2026 standard (Bangchak Style Curve & Notch)
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * ============================================================================
 */

// Determine base prefix whether loaded from root or subdirectories
$current_script = basename($_SERVER['SCRIPT_NAME'] ?? '');
$script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$is_subpage = (basename($script_dir) === 'pages' || basename($script_dir) === 'dashboard');
$base_prefix = $is_subpage ? '../' : '';

$is_home = ($current_script === 'index.php' || empty($current_script));
$is_student = ($current_script === 'student_list.php');
$is_register = ($current_script === 'register.php');
$is_dashboard = (strpos($script_dir, 'dashboard') !== false);
?>

<!-- Mobile App Bottom Navigation (3D Curved Scoop Dock with Vibrant 3D Tactile Icons) -->
<nav id="mobileBottomNav" class="md:hidden fixed bottom-0 left-0 right-0 z-50 pointer-events-auto select-none w-full max-w-[100vw] overflow-visible" style="padding-bottom: max(env(safe-area-inset-bottom, 0px), 6px);">
    
    <!-- 3D Curved Scoop Background with Molded Recessed Dish (Zero Page Bleed-Through) -->
    <div class="absolute bottom-0 inset-x-0 w-full h-[78px] pointer-events-none">
        
        <!-- Solid Base Shield (blocks 100% of scrolling page content) -->
        <div class="absolute inset-0 bg-white shadow-[0_-10px_35px_rgba(0,0,0,0.08)]"></div>

        <!-- 3D Curved Notch SVG Overlay with Sculpted Concave Dish -->
        <svg class="absolute -top-3 w-full h-[90px] filter drop-shadow-[0_-6px_16px_rgba(0,0,0,0.06)]" viewBox="0 0 375 90" preserveAspectRatio="none">
            <defs>
                <!-- 3D Bar Surface Gradient -->
                <linearGradient id="barGradient3D" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#ffffff" />
                    <stop offset="40%" stop-color="#ffffff" />
                    <stop offset="100%" stop-color="#f8fafc" />
                </linearGradient>

                <!-- 3D Concave Scoop Dish Gradient -->
                <radialGradient id="scoopDish3D" cx="50%" cy="30%" r="65%">
                    <stop offset="0%" stop-color="#f1f5f9" stop-opacity="0.9" />
                    <stop offset="60%" stop-color="#e2e8f0" stop-opacity="0.6" />
                    <stop offset="100%" stop-color="#cbd5e1" stop-opacity="0.3" />
                </radialGradient>

                <!-- 3D Rim Highlight Line -->
                <linearGradient id="rimStroke3D" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#e2e8f0" />
                    <stop offset="35%" stop-color="#cbd5e1" />
                    <stop offset="50%" stop-color="#94a3b8" />
                    <stop offset="65%" stop-color="#cbd5e1" />
                    <stop offset="100%" stop-color="#e2e8f0" />
                </linearGradient>
            </defs>

            <!-- Molded 3D Concave Dish Backing (under the scoop) -->
            <ellipse cx="187.5" cy="20" rx="42" ry="20" fill="url(#scoopDish3D)" />

            <!-- Main Sculpted Curved Bar Body -->
            <path d="M0,12 
                     L134,12 
                     C146,12 153,46 187.5,46 
                     C222,46 229,12 241,12 
                     L375,12 
                     L375,90 
                     L0,90 Z" 
                  fill="url(#barGradient3D)" 
                  stroke="url(#rimStroke3D)" 
                  stroke-width="1.2" />

            <!-- Subtle Inner Scoop Shadow Curve for high-end depth -->
            <path d="M136,13 C148,13 155,47 187.5,47 C220,47 227,13 239,13" 
                  fill="none" 
                  stroke="rgba(0,0,0,0.06)" 
                  stroke-width="2.5" />
        </svg>
    </div>

    <!-- Navigation Items Grid -->
    <div class="relative flex justify-between items-end px-2 sm:px-5 h-[76px] w-full max-w-md mx-auto z-20 pb-2">
        
        <!-- Tab 1: หน้าหลัก (Home - 3D Emerald Orb) -->
        <a href="<?= $base_prefix ?>index.php" 
           id="navItemHome"
           class="flex flex-col items-center justify-center w-14 group transition-transform active:scale-90">
            <!-- 3D Claymorphic Icon Button -->
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-700 p-0.5 shadow-[0_4px_12px_rgba(16,185,129,0.38),inset_0_1.5px_2px_rgba(255,255,255,0.8)] border border-emerald-300/40 relative overflow-hidden flex items-center justify-center transition-all duration-300 group-hover:scale-110 <?= ($is_home && !$is_student && !$is_register) ? 'ring-2 ring-emerald-500/50 scale-105' : 'opacity-85 group-hover:opacity-100' ?>">
                <!-- 3D Specular Highlight Gloss -->
                <div class="absolute inset-x-1 top-0.5 h-3 bg-gradient-to-b from-white/70 to-transparent rounded-t-xl pointer-events-none"></div>
                <i class="fa-solid fa-house text-white text-base drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]"></i>
            </div>
            <span class="text-[10px] <?= ($is_home && !$is_student && !$is_register) ? 'font-bold text-emerald-800' : 'font-semibold text-slate-600 group-hover:text-emerald-700' ?> mt-1 tracking-tight">หน้าหลัก</span>
            <span class="nav-dot w-1.5 h-1.5 rounded-full bg-emerald-600 mt-0.5 <?= ($is_home && !$is_student && !$is_register) ? 'opacity-100' : 'opacity-0' ?> transition-opacity"></span>
        </a>

        <!-- Tab 2: ๗ โมดูล (Modules - 3D Amber/Orange Orb) -->
        <a href="<?= $base_prefix ?>index.php#modules" 
           id="navItemModules"
           class="flex flex-col items-center justify-center w-14 group transition-transform active:scale-90">
            <!-- 3D Claymorphic Icon Button -->
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-rose-600 p-0.5 shadow-[0_4px_12px_rgba(245,158,11,0.38),inset_0_1.5px_2px_rgba(255,255,255,0.8)] border border-amber-300/40 relative overflow-hidden flex items-center justify-center transition-all duration-300 group-hover:scale-110 opacity-85 group-hover:opacity-100">
                <!-- 3D Specular Highlight Gloss -->
                <div class="absolute inset-x-1 top-0.5 h-3 bg-gradient-to-b from-white/70 to-transparent rounded-t-xl pointer-events-none"></div>
                <i class="fa-solid fa-graduation-cap text-white text-base drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]"></i>
                <!-- 3D Pulse Notification Orb -->
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-gradient-to-tr from-amber-400 to-orange-500 shadow-sm border border-white"></span>
                </span>
            </div>
            <span class="text-[10px] font-semibold text-slate-600 group-hover:text-amber-700 mt-1 tracking-tight">๗ โมดูล</span>
            <span class="nav-dot w-1.5 h-1.5 rounded-full bg-amber-600 mt-0.5 opacity-0 transition-opacity"></span>
        </a>

        <!-- Center 3D Floating Action Button: ลงทะเบียน (Register - Ultra 3D Gem Sphere in Scoop) -->
        <div class="relative flex flex-col items-center justify-center -top-3 z-30">
            <a href="<?= $base_prefix ?>pages/register.php" 
               class="group relative flex items-center justify-center rounded-full p-1 bg-gradient-to-b from-white via-slate-100 to-slate-200 shadow-[0_10px_25px_rgba(16,185,129,0.45),0_2px_5px_rgba(0,0,0,0.12)] border border-white hover:scale-105 active:scale-90 transition-all duration-300"
               style="width: 58px; height: 58px;"
               title="ลงทะเบียนเข้าร่วมอบรม">
                <!-- Inner 3D Sphere Body -->
                <div class="w-full h-full rounded-full bg-gradient-to-tr from-emerald-600 via-teal-500 to-cyan-400 shadow-[inset_0_-4px_6px_rgba(0,0,0,0.3),inset_0_2px_4px_rgba(255,255,255,0.8)] border border-emerald-300/40 flex items-center justify-center relative overflow-hidden">
                    <!-- 3D Specular Highlight Glare -->
                    <div class="absolute top-1 left-2 w-5 h-2.5 bg-white/70 rounded-full blur-[0.6px] pointer-events-none"></div>
                    <!-- Icon -->
                    <i class="fa-solid fa-user-plus text-2xl text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.35)] group-hover:rotate-12 transition-transform duration-300"></i>
                </div>
            </a>
            <!-- 3D Floating Pill Badge -->
            <span class="px-2.5 py-0.5 rounded-full bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 text-white text-[10px] font-bold shadow-[0_2px_8px_rgba(5,150,105,0.4)] border border-emerald-400/50 mt-1 whitespace-nowrap leading-tight tracking-tight">
                ลงทะเบียน
            </span>
        </div>

        <!-- Tab 3: รายชื่อ (Participants - 3D Blue/Cyan Orb) -->
        <a href="<?= $base_prefix ?>pages/student_list.php" 
           id="navItemStudents"
           class="flex flex-col items-center justify-center w-14 group transition-transform active:scale-90">
            <!-- 3D Claymorphic Icon Button -->
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-cyan-400 via-blue-500 to-indigo-700 p-0.5 shadow-[0_4px_12px_rgba(59,130,246,0.38),inset_0_1.5px_2px_rgba(255,255,255,0.8)] border border-blue-300/40 relative overflow-hidden flex items-center justify-center transition-all duration-300 group-hover:scale-110 <?= $is_student ? 'ring-2 ring-blue-500/50 scale-105' : 'opacity-85 group-hover:opacity-100' ?>">
                <!-- 3D Specular Highlight Gloss -->
                <div class="absolute inset-x-1 top-0.5 h-3 bg-gradient-to-b from-white/70 to-transparent rounded-t-xl pointer-events-none"></div>
                <i class="fa-solid fa-users text-white text-base drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]"></i>
            </div>
            <span class="text-[10px] <?= $is_student ? 'font-bold text-blue-800' : 'font-semibold text-slate-600 group-hover:text-blue-700' ?> mt-1 tracking-tight">รายชื่อ</span>
            <span class="nav-dot w-1.5 h-1.5 rounded-full bg-blue-600 mt-0.5 <?= $is_student ? 'opacity-100' : 'opacity-0' ?> transition-opacity"></span>
        </a>

        <!-- Tab 4: แดชบอร์ด (Dashboard - 3D Purple/Violet Orb) -->
        <a href="<?= $base_prefix ?>dashboard/index.php" 
           id="navItemDashboard"
           class="flex flex-col items-center justify-center w-14 group transition-transform active:scale-90">
            <!-- 3D Claymorphic Icon Button -->
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-fuchsia-400 via-purple-500 to-indigo-700 p-0.5 shadow-[0_4px_12px_rgba(168,85,247,0.38),inset_0_1.5px_2px_rgba(255,255,255,0.8)] border border-purple-300/40 relative overflow-hidden flex items-center justify-center transition-all duration-300 group-hover:scale-110 <?= $is_dashboard ? 'ring-2 ring-purple-500/50 scale-105' : 'opacity-85 group-hover:opacity-100' ?>">
                <!-- 3D Specular Highlight Gloss -->
                <div class="absolute inset-x-1 top-0.5 h-3 bg-gradient-to-b from-white/70 to-transparent rounded-t-xl pointer-events-none"></div>
                <i class="fa-solid fa-chart-line text-white text-base drop-shadow-[0_2px_3px_rgba(0,0,0,0.35)]"></i>
            </div>
            <span class="text-[10px] <?= $is_dashboard ? 'font-bold text-purple-800' : 'font-semibold text-slate-600 group-hover:text-purple-700' ?> mt-1 tracking-tight">แดชบอร์ด</span>
            <span class="nav-dot w-1.5 h-1.5 rounded-full bg-purple-600 mt-0.5 <?= $is_dashboard ? 'opacity-100' : 'opacity-0' ?> transition-opacity"></span>
        </a>

    </div>
</nav>

<!-- JavaScript to highlight active section on scroll -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modulesSection = document.getElementById('modules');
    const homeTab = document.getElementById('navItemHome');
    const modulesTab = document.getElementById('navItemModules');

    if (modulesSection && homeTab && modulesTab && (window.location.pathname.endsWith('index.php') || window.location.pathname.endsWith('/'))) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const homeIcon = homeTab.querySelector('div');
                const homeText = homeTab.querySelector('span:not(.nav-dot)');
                const homeDot = homeTab.querySelector('.nav-dot');

                const modIcon = modulesTab.querySelector('div');
                const modText = modulesTab.querySelector('span:not(.flex):not(.animate-ping):not(.nav-dot)');
                const modDot = modulesTab.querySelector('.nav-dot');

                if (entry.isIntersecting) {
                    if (modIcon) modIcon.classList.add('ring-2', 'ring-amber-500/50', 'scale-105');
                    if (modIcon) modIcon.classList.remove('opacity-85');
                    if (modText) {
                        modText.className = 'text-[10px] font-bold text-amber-800 mt-1 tracking-tight';
                    }
                    if (modDot) modDot.classList.replace('opacity-0', 'opacity-100');

                    if (homeIcon) homeIcon.classList.remove('ring-2', 'ring-emerald-500/50', 'scale-105');
                    if (homeIcon) homeIcon.classList.add('opacity-85');
                    if (homeText) {
                        homeText.className = 'text-[10px] font-semibold text-slate-600 group-hover:text-emerald-700 mt-1 tracking-tight';
                    }
                    if (homeDot) homeDot.classList.replace('opacity-100', 'opacity-0');
                } else if (window.scrollY < (modulesSection.offsetTop - 300)) {
                    if (homeIcon) homeIcon.classList.add('ring-2', 'ring-emerald-500/50', 'scale-105');
                    if (homeIcon) homeIcon.classList.remove('opacity-85');
                    if (homeText) {
                        homeText.className = 'text-[10px] font-bold text-emerald-800 mt-1 tracking-tight';
                    }
                    if (homeDot) homeDot.classList.replace('opacity-0', 'opacity-100');

                    if (modIcon) modIcon.classList.remove('ring-2', 'ring-amber-500/50', 'scale-105');
                    if (modIcon) modIcon.classList.add('opacity-85');
                    if (modText) {
                        modText.className = 'text-[10px] font-semibold text-slate-600 group-hover:text-amber-700 mt-1 tracking-tight';
                    }
                    if (modDot) modDot.classList.replace('opacity-100', 'opacity-0');
                }
            });
        }, { threshold: 0.2 });

        observer.observe(modulesSection);
    }
});
</script>
