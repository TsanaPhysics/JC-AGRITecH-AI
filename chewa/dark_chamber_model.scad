// =============================================================================
// โครงการวิจัย: การพัฒนาชุดวิเคราะห์ปริมาณไนโตรเจนและฟอสฟอรัสในดินแบบพกพาด้วยหลักการสะท้อนแสงร่วมกับปัญญาประดิษฐ์
// หัวหน้าโครงการ: ผศ.ดร.ชีวะ ทัศนา (มหาวิทยาลัยราชภัฏรำไพพรรณี)
// ชื่องาน: โมเดล 3 มิติกล่องวัดแบบทึบแสง (Optical Dark Chamber) 45/0 องศา
// ไฟล์: dark_chamber_model.scad
// ซอฟต์แวร์ที่รองรับ: OpenSCAD (กด F5 เพื่อดูภาพตัวอย่าง, กด F6 เพื่อ Render, Export เป็น .STL)
// =============================================================================

$fn = 60; // ความละเอียดของส่วนโค้ง (Smoothness)

// -----------------------------------------------------------------------------
// ตัวแปรพารามิเตอร์หลักตามข้อกำหนดวิจัย (Research Dimensions in mm)
// -----------------------------------------------------------------------------
box_width       = 120.0; // ความกว้างภายนอกตัวกล่อง (มิลลิเมตร)
box_length      = 80.0;  // ความยาวภายนอกตัวกล่อง (มิลลิเมตร)
box_height      = 65.0;  // ความสูงภายนอกตัวกล่อง (มิลลิเมตร)
wall_thick      = 3.0;   // ความหนาผนังรอบกล่อง (ป้องกันแสงทะลุ 100%)

// พารามิเตอร์ถาดตัวอย่างดิน (Sample Tray)
tray_diameter   = 35.0;  // เส้นผ่านศูนย์กลางภายในถาดใส่ดิน (มิลลิเมตร)
tray_depth      = 8.0;   // ความลึกถาดบรรจุดิน (ปริมาตร 7.5 ลูกบาศก์เซนติเมตร)
tray_wall       = 2.0;   // ความหนาขอบถาด
drawer_clearance= 0.35;  // ช่องว่างระยะเลื่อนเพื่อความกระชับและกันแสง

// พารามิเตอร์ระยะทางทัศนศาสตร์ (Optical Working Distance)
optical_distance= 50.0;  // ระยะห่างจากเลนส์รับแสงถึงผิวหน้าดิน (50 มิลลิเมตร)
led_angle       = 45.0;  // มุมส่องสว่าง LED Array ตกกระทบผิวหน้าดิน (45 องศา)
led_ring_radius = 24.0;  // รัศมีวงแหวนหลอด LED 6 ดวง
led_hole_dia    = 5.2;   // ช่องสอดหลอด LED ขนาด 5 มม.

// พารามิเตอร์ช่องรับภาพและโมดูลาร์สมาร์ทโฟน
aperture_dia    = 18.0;  // เส้นผ่านศูนย์กลางรูรับแสงมุม 0 องศา (ตั้งฉาก)
cone_height     = 20.0;  // ความสูงกรวยดักแสงสะท้อนกวน (Light Baffle Cone)
phone_clamp_w   = 78.0;  // ความกว้างรางวางสมาร์ทโฟนมาตรฐาน
phone_clamp_lip = 8.0;   // ขอบปีกหนีบสมาร์ทโฟน

// ตัวแปรควบคุมการแสดงผลชิ้นส่วนใน OpenSCAD (ปรับ true/false ตามต้องการ)
show_main_housing    = true;  // แสดงตัวกล่องหลัก
show_soil_drawer     = true;  // แสดงลิ้นชักถาดดิน
show_phone_mount     = true;  // แสดงฝาบนแท่นวางสมาร์ทโฟน
show_optical_plate   = false; // แสดงฝาบนสำหรับโมดูลเซนเซอร์ ESP32-CAM / AS7262
exploded_view        = false; // โหมดมุมมองกระจายชิ้นส่วน (Exploded View)

// คำนวณระยะกระจายชิ้นส่วนในโหมด Exploded
offset_drawer = exploded_view ? [0, -60, 0] : [0, 0, 0];
offset_top    = exploded_view ? [0, 0, 35] : [0, 0, 0];

// =============================================================================
// 1. โมดูลตัวกล่องหลัก (Main Housing with 45° LED Ring Baffle & Light-trap)
// =============================================================================
module main_housing() {
    difference() {
        // บล็อกนอกตัวกล่อง ลบมุมมนเพื่อความแข็งแรง
        hull() {
            translate([-box_width/2 + 4, -box_length/2 + 4, 0]) cylinder(r=4, h=box_height);
            translate([box_width/2 - 4, -box_length/2 + 4, 0])  cylinder(r=4, h=box_height);
            translate([-box_width/2 + 4, box_length/2 - 4, 0]) cylinder(r=4, h=box_height);
            translate([box_width/2 - 4, box_length/2 - 4, 0])  cylinder(r=4, h=box_height);
        }
        
        // โพรงห้องมืดภายในกล่อง (Optical Chamber Cavity)
        translate([-box_width/2 + wall_thick, -box_length/2 + wall_thick, wall_thick + 14])
            cube([box_width - wall_thick*2, box_length - wall_thick*2, box_height]);

        // ช่องสอดลิ้นชักถาดตัวอย่างดินด้านหน้า (Front Drawer Slot)
        translate([-(tray_diameter + 16)/2 - drawer_clearance, -box_length/2 - 1, wall_thick])
            cube([tray_diameter + 16 + drawer_clearance*2, box_length/2 + 20, 14]);

        // ร่องดักแสงสองชั้นรอบช่องลิ้นชัก (Double-lip Labyrinth Groove for Light-trap)
        translate([-(tray_diameter + 22)/2, -box_length/2 + 6, wall_thick - 0.5])
            cube([tray_diameter + 22, 2.5, 15]);

        // รูร้อยสายไฟวงจร LED และเซนเซอร์ออกไปยังห้องแบตเตอรี่ด้านหลัง
        translate([0, box_length/2 - wall_thick - 1, 30])
            rotate([-90, 0, 0]) cylinder(d=6.0, h=wall_thick + 2);

        // ช่องขันน็อตทองเหลือง M3 ยึดฝาบน 4 จุด
        translate([-box_width/2 + 6, -box_length/2 + 6, box_height - 12]) cylinder(d=3.2, h=13);
        translate([box_width/2 - 6, -box_length/2 + 6, box_height - 12])  cylinder(d=3.2, h=13);
        translate([-box_width/2 + 6, box_length/2 - 6, box_height - 12])  cylinder(d=3.2, h=13);
        translate([box_width/2 - 6, box_length/2 - 6, box_height - 12])   cylinder(d=3.2, h=13);
    }

    // แผงกรวยกั้นแสงภายใน (Internal Optical Baffle Cone & 45-degree LED Mounts)
    translate([0, 0, 14 + wall_thick]) {
        difference() {
            // โครงฐานยึดวงแหวน LED ทรงกรวยคว่ำ
            cylinder(d1=tray_diameter + 28, d2=tray_diameter + 14, h=25);
            cylinder(d1=tray_diameter + 20, d2=tray_diameter + 8, h=26);
            
            // ช่องเจาะส่องแสง 6 ช่อง ทำมุมเอียง 45 องศา สู่จุดศูนย์กลางถาดดิน
            for (i = [0:5]) {
                rotate([0, 0, i * 60])
                translate([led_ring_radius - 2, 0, 16])
                rotate([0, led_angle, 0])
                cylinder(d=led_hole_dia, h=22, center=true);
            }
        }
    }
}

// =============================================================================
// 2. โมดูลลิ้นชักและถาดบรรจุตัวอย่างดิน (Sliding Soil Drawer & Tray)
// =============================================================================
module soil_drawer() {
    translate(offset_drawer) {
        difference() {
            union() {
                // ตัวลิ้นชักทรงสี่เหลี่ยมแนบสนิท
                translate([-(tray_diameter + 16)/2, -box_length/2 + 2, wall_thick + 0.3])
                    cube([tray_diameter + 16, box_length/2 + 15, 13.2]);

                // ปีกหน้ากากลิ้นชักพร้อมเขี้ยวดักแสง (Front Faceplate with Labyrinth Light-seal)
                translate([-(tray_diameter + 26)/2, -box_length/2 - 2.5, wall_thick - 1])
                    cube([tray_diameter + 26, 4.5, 17]);

                // เขี้ยวดักแสงชั้นที่สอง (Labyrinth Interlocking Lip)
                translate([-(tray_diameter + 21.6)/2, -box_length/2 + 5.8, wall_thick])
                    cube([tray_diameter + 21.6, 2.0, 14]);

                // มือจับลิ้นชักด้านหน้า (Ergonomic Pull Handle)
                translate([-14, -box_length/2 - 10, wall_thick + 2])
                    cube([28, 8, 10]);
            }

            // เบ้าถาดกลมบรรจุตัวอย่างดิน (เส้นผ่านศูนย์กลาง 35 มม. ลึก 8 มม. ปริมาตร 7.5 cm³)
            translate([0, 0, wall_thick + 5.5])
                cylinder(d=tray_diameter, h=tray_depth + 0.2);

            // ขอบบ่ารองรับแผ่นอ้างอิงสีขาวมาตรฐาน (White Reference Calibration Disc)
            translate([0, 0, wall_thick + 12.8])
                cylinder(d=tray_diameter + 3.0, h=1.0);
        }
    }
}

// =============================================================================
// 3. โมดูลฝาบนพร้อมแท่นยึดสมาร์ทโฟน (Universal Smartphone Mount & Lens Gasket Hood)
// =============================================================================
module smartphone_mount() {
    translate([0, 0, box_height] + offset_top) {
        difference() {
            union() {
                // แผ่นปิดฝาบน (Top Lid Flange)
                translate([-box_width/2, -box_length/2, 0])
                    cube([box_width, box_length, 4.0]);

                // กรวยบังแสงทางสายตาและช่องรับแสง 0 องศา ยื่นลงไปในกล่อง (Viewing Baffle Cone)
                translate([0, 0, -12])
                    cylinder(d1=aperture_dia + 2, d2=aperture_dia + 8, h=12);

                // ขอบรางหนีบสมาร์ทโฟนด้านบน (Phone Alignment Guide Rails)
                translate([-phone_clamp_w/2 - 3, -box_length/2, 4])
                    cube([phone_clamp_w + 6, 6, 12]);
                translate([-phone_clamp_w/2 - 3, box_length/2 - 6, 4])
                    cube([phone_clamp_w + 6, 6, 12]);
                translate([phone_clamp_w/2, -box_length/2, 4])
                    cube([4, box_length, 12]);

                // แท่นฮูดครอบเลนส์กล้องพร้อมร่องใส่ปะเก็นยางกันแสง EVA (Lens Gasket Rim)
                translate([0, 0, 4])
                    cylinder(d=aperture_dia + 10, h=4);
            }

            // รูรับแสงตั้งฉาก 0 องศา สู่พื้นผิวตัวอย่างดิน (0-degree Normal Optical Path)
            translate([0, 0, -13])
                cylinder(d=aperture_dia, h=25);

            // ร่องใส่ปะเก็นยางโฟม EVA สีดำรอบเลนส์ ป้องกันแสงภายนอกรั่วเข้า 100%
            translate([0, 0, 5.5])
                difference() {
                    cylinder(d=aperture_dia + 8, h=3);
                    cylinder(d=aperture_dia, h=3.1);
                }

            // รูร้อยน็อต M3 ยึดฝาเข้ากับตัวกล่องหลัก 4 รู
            translate([-box_width/2 + 6, -box_length/2 + 6, -1]) cylinder(d=3.4, h=7);
            translate([box_width/2 - 6, -box_length/2 + 6, -1])  cylinder(d=3.4, h=7);
            translate([-box_width/2 + 6, box_length/2 - 6, -1])  cylinder(d=3.4, h=7);
            translate([box_width/2 - 6, box_length/2 - 6, -1])   cylinder(d=3.4, h=7);
        }
    }
}

// =============================================================================
// 4. โมดูลฝาบนทางเลือกสำหรับโมดูลเซนเซอร์ตรวจวัด (Alternative Optical Sensor Bracket)
// =============================================================================
module optical_sensor_plate() {
    translate([0, 0, box_height] + offset_top) {
        difference() {
            // แผ่นปิดฝาบน
            translate([-box_width/2, -box_length/2, 0])
                cube([box_width, box_length, 4.0]);

            // ช่องรูรับแสงตรงกลาง
            translate([0, 0, -1])
                cylinder(d=aperture_dia, h=6.0);

            // รูเสายึดบอร์ด ESP32-CAM หรือ AS7262 Spectral Sensor (พิกัด 4 รู 28x22 มม.)
            translate([-14, -11, -1]) cylinder(d=2.4, h=6);
            translate([14, -11, -1])  cylinder(d=2.4, h=6);
            translate([-14, 11, -1])  cylinder(d=2.4, h=6);
            translate([14, 11, -1])   cylinder(d=2.4, h=6);

            // รูร้อยน็อต M3 ยึดฝาหลัก
            translate([-box_width/2 + 6, -box_length/2 + 6, -1]) cylinder(d=3.4, h=7);
            translate([box_width/2 - 6, -box_length/2 + 6, -1])  cylinder(d=3.4, h=7);
            translate([-box_width/2 + 6, box_length/2 - 6, -1])  cylinder(d=3.4, h=7);
            translate([box_width/2 - 6, box_length/2 - 6, -1])   cylinder(d=3.4, h=7);
        }
    }
}

// =============================================================================
// การประกอบเรนเดอร์ชิ้นงานตามโหมดที่เลือก (Assembly Execution)
// =============================================================================
if (show_main_housing) {
    color([0.15, 0.15, 0.15, 1.0]) // สีดำด้าน (Matt Black PETG)
        main_housing();
}

if (show_soil_drawer) {
    color([0.25, 0.25, 0.25, 1.0]) // สีเทาเข้ม/ดำ
        soil_drawer();
}

if (show_phone_mount) {
    color([0.2, 0.2, 0.25, 0.9])   // ฝาบนพร้อมแท่นสมาร์ทโฟน
        smartphone_mount();
}

if (show_optical_plate) {
    color([0.1, 0.3, 0.2, 0.9])    // ฝาบนสำหรับบอร์ดเซนเซอร์ออปติคอล
        optical_sensor_plate();
}
