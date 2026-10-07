<?php
/**
 * ============================================================================
 * LEQs-xAI: Real Interactive Web Dashboard
 * Connected via Wi-Fi to ESP32-S3 ATD3.5 Controller Board
 * ============================================================================
 * Architecture: Clean Component-Driven Modular Dashboard
 * All widgets are located in dashboard/components/
 * ============================================================================
 */

// 1. Head & Theme Setup
require_once __DIR__ . '/components/header.php';

// 2. Top Navigation Bar & Hardware Status
require_once __DIR__ . '/components/navbar.php';
?>

<!-- ========================================================================= -->
<!-- MAIN DASHBOARD CONTAINER                                                  -->
<!-- ========================================================================= -->
<main class="flex-1 p-4 md:p-6 max-w-7xl mx-auto w-full space-y-6">

    <?php
    // 3. Official QR Portal & Identity Verification (ESP32-S3 ATD3.5 Screen 11)
    require_once __DIR__ . '/components/qr_portal.php';

    // 4. Row 1: 6 Real-time Telemetry Metrics Cards
    require_once __DIR__ . '/components/metrics_cards.php';

    // 5. Row 2: 24h Environmental Trend Line Chart & 4-Channel Relays
    require_once __DIR__ . '/components/control_panel.php';

    // 6. Row 3: Soil NPK Radar Chart & Smart Rule Automation
    require_once __DIR__ . '/components/automation_rules.php';

    // 7. Row 4: Live SQLite3 Database Telemetry Logs
    require_once __DIR__ . '/components/database_logs.php';
    ?>

</main>

<?php
// 8. Footer
require_once __DIR__ . '/components/footer.php';

// 9. JavaScript Sync Engine, Charts & Simulators
require_once __DIR__ . '/components/scripts.php';
?>
