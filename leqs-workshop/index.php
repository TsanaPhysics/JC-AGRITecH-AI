<?php
/**
 * ============================================================================
 * LEQs-xAI: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Co-developed with Praneetwittayakhom School
 * ============================================================================
 * Architecture: Clean Modular Components Architecture
 * All sections are organized in includes/ for rapid development & maintenance.
 * ============================================================================
 */

$page_title = "LEQs-xAI: ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม | RBRU 2026";

// 1. HTML Head & Global Assets
require_once __DIR__ . '/includes/header.php';

// 2. Navigation Bar (Top Sticky Glassmorphism)
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ========================================================================= -->
<!-- MAIN PORTAL CONTENT WRAPPER                                               -->
<!-- ========================================================================= -->
<main class="container mx-auto px-3 sm:px-4 md:px-6 pt-24 md:pt-28 pb-28 sm:pb-16 space-y-16 md:space-y-24 w-full max-w-full overflow-x-hidden min-w-0">

    <?php
    // 3. Hero Section (Overview & Status)
    require_once __DIR__ . '/includes/hero.php';

    // 3.1 Learning Journey Stepper (Academic OBE & Active Learning Cycle)
    require_once __DIR__ . '/includes/section_stepper.php';

    // 4. Conceptual Framework (L-E-Q-s-A-I Architecture)
    require_once __DIR__ . '/includes/section_framework.php';

    // 5. UN SDGs Alignment (SDGs 2, 4, 9, 13)
    require_once __DIR__ . '/includes/section_sdgs.php';

    // 6. Project Researchers & Instructors (LEQs Teams)
    require_once __DIR__ . '/includes/section_teams.php';

    // 7. Interactive Virtual Simulators (ESP32-S3 ATD3.5 Live Lab)
    require_once __DIR__ . '/includes/section_simulators.php';

    // 8. ESP32-S3 ATD3.5 11-Screens Showcase
    require_once __DIR__ . '/includes/section_screens.php';

    // 9. Real-time IoT Platform & Sensor Telemetry
    require_once __DIR__ . '/includes/section_iot.php';

    // 10. 7 Modules Curriculum Grid
    require_once __DIR__ . '/includes/section_modules.php';

    // 11. Capstone Mini-Project Tracks
    require_once __DIR__ . '/includes/section_capstone.php';

    // 12. 10 Digital Learning Resources
    require_once __DIR__ . '/includes/section_learning.php';

    // 13. Official Project Documents & Budget Breakdown
    require_once __DIR__ . '/includes/section_documents.php';
    ?>

</main>

<?php
// 14. Global High-Contrast Footer
require_once __DIR__ . '/includes/footer.php';

// 14.1 Mobile App Bottom Navigation (Bangchak Style Curve matching sciexhub2026)
require_once __DIR__ . '/includes/bottom_nav.php';

// 15. Modals, Floating AI Assistant (น้อง SmartScience 🤖) & Dynamic Scripts
require_once __DIR__ . '/includes/modals.php';
?>
