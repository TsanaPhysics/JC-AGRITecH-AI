<?php
/**
 * ============================================================================
 * LEQs-xAI Admin Control Center & Dashboard
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * ============================================================================
 * Architecture: Clean Component-Driven Modular Admin CMS
 * Sub-files & components are located in admin/includes/
 * ============================================================================
 */

// 1. Authentication, Sessions & Database Data Aggregation
require_once __DIR__ . '/includes/auth_and_data.php';

// 2. HTML Head, Fonts & CSS Setup
require_once __DIR__ . '/includes/header.php';

// 3. Conditional View: Login Screen vs. Full Admin Dashboard
if (!$is_logged):
    // 3.1 Login Screen Form
    require_once __DIR__ . '/includes/login_form.php';
else:
?>
    <!-- Full Admin CMS Layout Container -->
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <?php
        // 4. Left Sidebar Aside
        require_once __DIR__ . '/includes/sidebar.php';
        ?>

        <!-- Right Main Content Panel -->
        <main class="flex-1 min-w-0 bg-[#090d16] p-6 lg:p-10 overflow-x-hidden relative">
            
            <!-- Ambient Glow Backdrop -->
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand/5 blur-3xl pointer-events-none rounded-full"></div>
            <div class="absolute bottom-0 right-10 w-96 h-96 bg-tech/5 blur-3xl pointer-events-none rounded-full"></div>

            <div class="relative z-10 max-w-6xl mx-auto space-y-8">
                <?php
                // 5. Header Title & Top Quick Action Buttons
                require_once __DIR__ . '/includes/header_title.php';

                // 6. KPI Summary Cards Grid (4 Primary Metrics)
                require_once __DIR__ . '/includes/kpi_cards.php';

                // 7. Database Dual-Storage Coverage (SQLite & JSON)
                require_once __DIR__ . '/includes/database_coverage.php';

                // 8. Server Diagnostics & Official QR Quick Share
                require_once __DIR__ . '/includes/server_diagnostics.php';

                // 9. System Announcements & Real-time Messages
                require_once __DIR__ . '/includes/announcements.php';

                // 10. Participant Directory & Interactive CRUD Table
                require_once __DIR__ . '/includes/participants_table.php';

                // 11. Summary Report & Learning Gain (E.I.) Analytics
                require_once __DIR__ . '/includes/report_section.php';

                // 12. Evaluation & Satisfaction Analytics (10 Questions)
                require_once __DIR__ . '/includes/evaluation_section.php';

                // 13. Certificate Customization & Signatory Config
                require_once __DIR__ . '/includes/certificate_section.php';

                // 14. Admin Panel Footer
                require_once __DIR__ . '/includes/footer.php';
                ?>
            </div>
        </main>

    </div>

    <?php
    // 15. Admin Modals (Add/Edit Participant, Add/Edit Announcement)
    require_once __DIR__ . '/includes/modals.php';

    // 16. Admin JavaScript Logic (CRUD AJAX, Chart.js, Table Filters)
    require_once __DIR__ . '/includes/scripts.php';
    ?>

<?php endif; ?>

</body>
</html>
