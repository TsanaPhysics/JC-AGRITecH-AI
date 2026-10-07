<?php
/**
 * LEQs-xAI: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Co-developed with Praneetwittayakhom School
 * Styled and structured following cmu_aiot Portal Design System
 */
$page_title = "LEQs-xAI: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม | RBRU 2026";
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?php echo $page_title; ?></title>

    <!-- Mobile Web App & PWA Settings -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LEQs-xAI">
    <meta name="theme-color" content="#059669">
    <meta name="application-name" content="LEQs-xAI">
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="assets/images/nong_smartscience.png">
    <link rel="icon" type="image/png" href="assets/images/nong_smartscience.png">
    
    <!-- Google Fonts: Sarabun (Formal Thai), Kanit (Headings), Outfit (Modern English), JetBrains Mono (Telemetry/Math), Chakra Petch & Orbitron (Cyber/Tech) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Kanit:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&family=Chakra+Petch:wght@400;500;600;700&family=Orbitron:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- KaTeX for Scientific & Academic Mathematical Formulas (LaTeX) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/contrib/auto-render.min.js" onload="renderMathInElement(document.body, {delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}]});"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS (Academic Design System & Glassmorphism) -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Custom Scripts -->
    <script src="assets/js/script.js" defer></script>
</head>
<body class="hero-bg text-slate-800 antialiased font-sans selection:bg-emerald-500 selection:text-white">

