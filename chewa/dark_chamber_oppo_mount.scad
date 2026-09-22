// =============================================================================
// โครงการวิจัย: การพัฒนาชุดวิเคราะห์ปริมาณไนโตรเจนและฟอสฟอรัสในดินแบบพกพาด้วยหลักการสะท้อนแสงร่วมกับปัญญาประดิษฐ์
// หัวหน้าโครงการ: ผศ.ดร.ชีวะ ทัศนา (มหาวิทยาลัยราชภัฏรำไพพรรณี)
// ชื่องาน: โมเดลฝาบนแท่นจับยึดสมาร์ทโฟน OPPO เฉพาะทาง (Custom OPPO Smartphone Mount Lid)
// ไฟล์: dark_chamber_oppo_mount.scad
// =============================================================================

$fn = 60; // ความละเอียดของส่วนโค้ง

// พารามิเตอร์ขนาดกล่อง Dark Chamber หลัก
box_w           = 120.0; // ความกว้างฝาปิด (มม.)
box_l           = 80.0;  // ความยาวฝาปิด (มม.)
lid_thick       = 4.0;   // ความหนาแผ่นฝาปิด

// พารามิเตอร์เฉพาะสำหรับสมาร์ทโฟน OPPO (Reno / Find / A Series)
oppo_width      = 75.5;  // ความกว้างตัวเครื่องรวมเคสบาง (มม.)
oppo_thick      = 8.5;   // ความหนาตัวเครื่อง (มม.)
rail_height     = 14.0;  // ความสูงปีกประคองด้านข้าง
camera_bump_w   = 44.0;  // ความกว้างเบ้าโมดูลกล้องนูน OPPO
camera_bump_l   = 56.0;  // ความยาวเบ้าโมดูลกล้องนูน OPPO
camera_bump_d   = 3.2;   // ความลึกหลุมรับโมดูลกล้อง
aperture_dia    = 18.0;  // ช่องรับแสง 0 องศา สู่เลนส์หลัก

// พารามิเตอร์ปะเก็นยางกันแสง EVA รอบโมดูลกล้อง OPPO
gasket_trench_w = 48.0;
gasket_trench_l = 60.0;
gasket_depth    = 2.5;

module oppo_mount_lid() {
    difference() {
        union() {
            // 1. แผ่นฝาบนปิดกล่องหลัก
            translate([-box_w/2, -box_l/2, 0])
                cube([box_w, box_l, lid_thick]);

            // 2. ปีกประคองขอบเครื่อง OPPO ซ้าย-ขวา
            translate([-oppo_width/2 - 3.5, -box_l/2, lid_thick])
                cube([3.5, box_l, rail_height]);
            translate([oppo_width/2, -box_l/2, lid_thick])
                cube([3.5, box_l, rail_height]);

            // 3. ปีกประคองหัวเครื่องด้านบน
            translate([-oppo_width/2, box_l/2 - 4.0, lid_thick])
                cube([oppo_width, 4.0, rail_height]);

            // 4. กรวยดักแสงทางเดินแสง 0 องศา ยื่นลงไปในกล่องมืด
            translate([0, 0, -12.0])
                cylinder(d1=aperture_dia + 2, d2=aperture_dia + 8, h=12.0);
                
            // 5. ตัวล็อกเขี้ยวเลื่อนด้านข้าง (Side Lock Catch)
            translate([-oppo_width/2 - 8.0, -12.0, lid_thick])
                cube([5.0, 24.0, 10.0]);
        }

        // --- ส่วนที่เจาะออก (Subtractions) ---

        // A. รูรับแสงตั้งฉาก 0 องศา มุ่งสู่ผิวหน้าดินในถาด
        translate([0, 0, -13.0])
            cylinder(d=aperture_dia, h=25.0);

        // B. หลุมเบ้ารองรับโมดูลกล้องนูนของ OPPO (Camera Island Recess)
        translate([-camera_bump_w/2, -camera_bump_l/2 + 6, lid_thick - camera_bump_d])
            cube([camera_bump_w, camera_bump_l, camera_bump_d + 0.1]);

        // C. ร่องวางแผ่นปะเก็นยางโฟม EVA สีดำซีลกันแสงรั่ว 100%
        translate([-gasket_trench_w/2, -gasket_trench_l/2 + 6, lid_thick - gasket_depth])
            difference() {
                cube([gasket_trench_w, gasket_trench_l, gasket_depth + 0.1]);
                translate([2.5, 2.5, -0.1])
                    cube([gasket_trench_w - 5.0, gasket_trench_l - 5.0, gasket_depth + 0.3]);
            }

        // D. ช่องเว้นสำหรับเสียบสาย USB-C OTG ด้านล่างเครื่อง
        translate([-12.0, -box_l/2 - 1.0, lid_thick])
            cube([24.0, 8.0, 12.0]);

        // E. รูขันสกรู M3 ยึดเข้ากับกล่องหลัก 4 จุด
        translate([-box_w/2 + 6, -box_l/2 + 6, -1]) cylinder(d=3.4, h=lid_thick + 2);
        translate([box_w/2 - 6, -box_l/2 + 6, -1])  cylinder(d=3.4, h=lid_thick + 2);
        translate([-box_w/2 + 6, box_l/2 - 6, -1])  cylinder(d=3.4, h=lid_thick + 2);
        translate([box_w/2 - 6, box_l/2 - 6, -1])   cylinder(d=3.4, h=lid_thick + 2);
    }
}

// แสดงผลโมเดลสีดำด้าน
color([0.15, 0.15, 0.15, 1.0])
    oppo_mount_lid();
