<?php
// LEQs-xAI: Real Interactive Web Dashboard
// Connected via Wi-Fi to ESP32-S3 ATD3.5 Controller Board
?>
<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#020617">
    <title>LEQs-xAI Smart Farm Web Dashboard | เชื่อมต่อบอร์ด ESP32-S3 ATD3.5</title>

    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
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
                        darkbg: '#0a0f1d',
                        darkcard: '#0f172a',
                        accent: '#10b981',
                        cyanic: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <!-- Chart.js & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html { scroll-behavior: smooth; scroll-padding-top: 130px; }
        body {
            background:
                radial-gradient(1100px 560px at 8% -8%, rgba(16,185,129,.10), transparent 60%),
                radial-gradient(900px 480px at 100% 0%, rgba(6,182,212,.10), transparent 55%),
                #020617;
            background-attachment: fixed;
        }
        .glass-box { background: rgba(15,23,42,.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.08); }
        .glow-accent { box-shadow: 0 0 25px rgba(16,185,129,.15); }
        .no-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        .num { font-variant-numeric: tabular-nums; font-feature-settings: "tnum"; }
        @keyframes valflash { 0% { text-shadow: 0 0 14px rgba(110,231,183,.65); } 100% { text-shadow: 0 0 0 rgba(110,231,183,0); } }
        .val-flash { animation: valflash .6s ease-out; }

        /* KPI card */
        .kpi { position: relative; display: flex; flex-direction: column; gap: .7rem; padding: 1rem 1.1rem; border-radius: 1.5rem;
               background: linear-gradient(180deg, rgba(15,23,42,.88), rgba(2,6,23,.88)); border: 1px solid rgba(148,163,184,.13);
               transition: border-color .2s, transform .2s, box-shadow .2s; overflow: hidden; }
        .kpi:hover { border-color: rgba(16,185,129,.38); transform: translateY(-1px); box-shadow: 0 10px 30px -12px rgba(16,185,129,.25); }
        .kpi-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; font-size: 12px; color: #cbd5e1; }
        .kpi-label { font-size: 10px; color: #94a3b8; }
        .kpi-value { font-family: 'Chakra Petch', sans-serif; font-weight: 700; letter-spacing: -.01em; line-height: 1.05; }
        .kpi-unit { font-size: 12px; color: #94a3b8; font-weight: 600; margin-left: .2rem; }
        .kpi-foot { display: flex; align-items: center; justify-content: space-between; gap: .5rem; flex-wrap: wrap; font-size: 10px; color: #94a3b8; font-family: 'Chakra Petch', monospace; padding-top: .55rem; border-top: 1px solid rgba(148,163,184,.12); }

        /* Bars / sparklines */
        .bar { position: relative; height: 6px; border-radius: 999px; background: rgba(148,163,184,.16); overflow: hidden; }
        .bar > i { display: block; height: 100%; width: 0; border-radius: 999px; background: #10b981; transition: width .6s cubic-bezier(.2,.8,.2,1), background-color .4s; }
        .bar > .zone { position: absolute; top: 0; bottom: 0; background: rgba(16,185,129,.30); border-left: 1px solid rgba(16,185,129,.6); border-right: 1px solid rgba(16,185,129,.6); }
        .spark { width: 100%; height: 28px; display: block; }
        .spark path { fill: none; stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round; vector-effect: non-scaling-stroke; }

        /* Pills */
        .pill { display: inline-flex; align-items: center; gap: .3rem; font-size: 10px; font-weight: 600; line-height: 1; padding: .22rem .6rem; border-radius: 999px; border: 1px solid; white-space: nowrap; }
        .pill-ok   { color: #6ee7b7; background: rgba(6,78,59,.45);   border-color: rgba(16,185,129,.40); }
        .pill-warn { color: #fcd34d; background: rgba(120,53,15,.40); border-color: rgba(245,158,11,.40); }
        .pill-bad  { color: #fda4af; background: rgba(127,29,29,.40); border-color: rgba(244,63,94,.40); }
        .pill-info { color: #67e8f9; background: rgba(8,51,68,.50);   border-color: rgba(6,182,212,.40); }
        .pill-mute { color: #94a3b8; background: rgba(30,41,59,.60);  border-color: rgba(100,116,139,.40); }

        /* Toggle switch */
        .sw { position: relative; width: 48px; height: 28px; flex-shrink: 0; border-radius: 999px; background: #334155; border: 1px solid rgba(148,163,184,.25); transition: background-color .2s; cursor: pointer; }
        .sw::after { content: ""; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,.45); transition: transform .2s cubic-bezier(.2,.8,.2,1); }
        .sw[aria-checked="true"] { background: #059669; border-color: rgba(52,211,153,.6); }
        .sw[aria-checked="true"]::after { transform: translateX(20px); }
        @keyframes swpend { from { opacity: .55; } to { opacity: 1; } }
        .sw.is-pending { cursor: progress; animation: swpend .6s ease-in-out infinite alternate; }

        /* Segmented control */
        .seg { display: grid; grid-template-columns: repeat(3,1fr); gap: 4px; padding: 4px; border-radius: 1rem; background: rgba(15,23,42,.9); border: 1px solid rgba(51,65,85,.8); }
        .seg button { padding: .55rem .4rem; border-radius: .75rem; font-size: 12px; font-weight: 700; color: #94a3b8; border: 1px solid transparent; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: .35rem; }
        .seg button:hover { color: #fff; }
        .seg button[aria-pressed="true"].m-manual { background: rgba(245,158,11,.18); color: #fcd34d; border-color: rgba(245,158,11,.45); }
        .seg button[aria-pressed="true"].m-auto   { background: rgba(16,185,129,.18); color: #6ee7b7; border-color: rgba(16,185,129,.45); }
        .seg button[aria-pressed="true"].m-ai     { background: rgba(6,182,212,.18);  color: #67e8f9; border-color: rgba(6,182,212,.45); }

        /* Stale */
        .is-stale .num { opacity: .5; }
        .is-stale .kpi { border-color: rgba(244,63,94,.25); }

        :focus-visible { outline: 2px solid #22d3ee; outline-offset: 2px; border-radius: 8px; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans bg-slate-950 text-slate-100 antialiased selection:bg-emerald-500 selection:text-white">

