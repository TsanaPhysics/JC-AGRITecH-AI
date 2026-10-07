<?php
/**
 * ============================================================================
 * LEQs-xAI: Real Smartphone Mobile Web Application
 * Styled exactly to match real_mobile_app_preview.jpg
 * Direct Wi-Fi Connection to ESP32-S3 ATD3.5 Controller Board
 * ============================================================================
 * Architecture: Clean Component-Driven Modular Mobile Architecture
 * All widgets are located in mobile/components/
 * ============================================================================
 */

// 1. Head & Mobile/PWA Configuration
require_once __DIR__ . '/components/header.php';

// 2. Floating Top Header & Action Controls
require_once __DIR__ . '/components/navbar.php';
?>

<!-- ========================================================================= -->
<!-- MAIN SMARTPHONE CONTAINER & SCREEN FRAME                                  -->
<!-- ========================================================================= -->
<main class="flex-1 flex flex-col items-center justify-start px-3 sm:px-4 py-4 sm:py-6 overflow-y-auto hide-scrollbar">
    
    <div class="w-full max-w-3xl glass-phone-card rounded-[2.5rem] p-4 sm:p-6 border border-white/10 space-y-5 relative overflow-hidden">
        
        <?php
        // 3. Status Bar & Physical Board Tabs
        require_once __DIR__ . '/components/status_bar.php';

        // 4. The 4 Circular Radial Gauges (Air Temp, Humidity, Soil, VPD)
        require_once __DIR__ . '/components/radial_gauges.php';

        // 5. Tri-Mode Rotary Dial Controller & 4-Channel Relays
        require_once __DIR__ . '/components/rotary_relays.php';

        // 6. Deep Root 7-in-1 Soil & Edge AI Camera Telemetry
        require_once __DIR__ . '/components/sensor_telemetry.php';

        // 7. ESP32-S3 Screen 11: Official QR Code Portal & Identity
        require_once __DIR__ . '/components/qr_portal.php';
        ?>

    </div>

</main>

<?php
// 8. Configuration & Settings Modals
require_once __DIR__ . '/components/modals.php';

// 9. Interactive Mobile IoT Sync Engine & Dial Controller
require_once __DIR__ . '/components/scripts.php';
?>
