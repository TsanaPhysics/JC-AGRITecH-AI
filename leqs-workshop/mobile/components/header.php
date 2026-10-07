<?php
// LEQs-xAI: Real Smartphone Mobile Web Application
// Styled exactly to match real_mobile_app_preview.jpg
// Direct Wi-Fi Connection to ESP32-S3 ATD3.5 Controller Board
?>
<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>LEQs-xAI Smart Farm Mobile App | ควบคุมบอร์ด ESP32-S3 ATD3.5</title>
    
    <!-- PWA & Mobile Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#030712">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&family=Orbitron:wght@500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                        darkphone: '#0d1322',
                        phonepanel: '#151d30',
                        accentgreen: '#10b981',
                        accentcyan: '#06b6d4',
                        accentamber: '#f59e0b',
                        accentblue: '#3b82f6',
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #030712;
            color: #f3f4f6;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        /* Ambient Smart Greenhouse Twilight Glow Backdrop */
        .farm-backdrop {
            background: 
                radial-gradient(circle at 50% 10%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 10% 40%, rgba(16, 185, 129, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 40%, rgba(245, 158, 11, 0.12) 0%, transparent 40%),
                linear-gradient(180deg, #090e1a 0%, #030712 100%);
        }

        /* Glassmorphism Phone Frame & Panels */
        .glass-phone-card {
            background: rgba(18, 26, 43, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .glass-inner-panel {
            background: rgba(13, 19, 34, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* Glowing circular arcs */
        .glow-orange {
            filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.65));
        }
        .glow-cyan {
            filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.65));
        }
        .glow-green {
            filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.75));
        }
        .glow-blue {
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.65));
        }

        /* Active Switch Neon Glow */
        .neon-switch-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 0 18px rgba(16, 185, 129, 0.75), inset 0 1px 2px rgba(255, 255, 255, 0.4);
        }
        .neon-switch-inactive {
            background: #1e293b;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans farm-backdrop antialiased">

