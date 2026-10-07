<!DOCTYPE html>
<html lang="th" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIoT Multi-Board Fleet Dashboard | LEQs-xAI AgriPhysics</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Outfit:wght@400;600;700;800;900&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS 3 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                        tech: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            dark: '#060a12',
                            panel: '#0c1427',
                            border: 'rgba(255, 255, 255, 0.08)',
                            cyan: '#06b6d4',
                            emerald: '#10b981',
                            purple: '#a855f7',
                            amber: '#f59e0b',
                            lime: '#a3e635'
                        }
                    }
                }
            }
        };
    </script>
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 for Premium Toasts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Fleet Dashboard Styles -->
    <link rel="stylesheet" href="assets/css/fleet.css">
</head>
<body class="bg-[#050811] text-slate-100 font-sans min-h-screen flex flex-col selection:bg-cyan-500 selection:text-black antialiased overflow-x-hidden">
