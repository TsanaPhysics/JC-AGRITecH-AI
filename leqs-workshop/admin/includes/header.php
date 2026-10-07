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

