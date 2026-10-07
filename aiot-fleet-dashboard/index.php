<?php
/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - Master Application Entrypoint
 * ============================================================================
 * Architecture: Clean Modular PHP + SQLite3 + TailwindCSS + RESTful Fleet API
 * Features:
 *   - Concurrent Multi-Board ESP32 Monitoring & Management
 *   - Dynamic Node Registration (Add / Edit / Remove Boards)
 *   - Board Selector Matrix with Real-Time Connectivity Health
 *   - Customizable Sensor & Actuator Widget Catalog (Toggle & Re-arrange)
 *   - Direct Node Relay Actuation + Fleet-Wide Broadcast & Emergency Control
 *   - 3 Adaptive View Modes: Focus (Single Node), Fleet Grid, Comparative Matrix
 *   - Dual-Engine Comparison: Raw Hardware Sensors VS TinyML Edge AI Models
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';

// Include Header (Styles, Tailwind, Fonts, Chart.js, SweetAlert2)
require_once __DIR__ . '/includes/header.php';

// Include Sticky Navigation Bar
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Master Content Layout -->
<main class="flex-1 max-w-[1720px] mx-auto w-full px-4 lg:px-8 py-6 space-y-6">

    <!-- Section 1: Instructor Master Demonstration Card (LEQs-xAI Reference Board) -->
    <?php require_once __DIR__ . '/components/instructor_master_card.php'; ?>

    <!-- Section 2: Multi-Board Node Selector Matrix (15 Workshop Groups + Master) -->
    <?php require_once __DIR__ . '/components/board_selector.php'; ?>

    <!-- Section 3: Modular Widget Canvas (Focus, Fleet Grid, Comparative Views) -->
    <?php require_once __DIR__ . '/components/widget_canvas.php'; ?>

</main>

<!-- Modals & Overlays -->
<?php require_once __DIR__ . '/components/widget_catalog_modal.php'; ?>
<?php require_once __DIR__ . '/components/board_modal.php'; ?>
<?php require_once __DIR__ . '/components/broadcast_modal.php'; ?>
<?php require_once __DIR__ . '/components/admin_fleet_modal.php'; ?>

<!-- Master Footer -->
<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- Client Application Scripts -->
<script src="assets/js/fleet-api.js"></script>
<script src="assets/js/fleet-ui.js"></script>
<script src="assets/js/fleet-core.js"></script>

</body>
</html>
