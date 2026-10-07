<?php
/**
 * LEQs AIoT Application & Resource Portal - Database Initializer
 */

header('Content-Type: application/json; charset=utf-8');

$dbDir = __DIR__;
$dbPath = $dbDir . '/app_portal.db';
$schemaPath = $dbDir . '/schema.sql';
$filesDir = dirname(__DIR__) . '/files';

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute schema
    $schemaSql = file_get_contents($schemaPath);
    $db->exec($schemaSql);

    // Initial apps dataset
    $appsData = [
        [
            'id' => 'handysense',
            'name' => 'LEQs xAI Smart Controller',
            'sub_title' => 'แอปพลิเคชันควบคุมฟาร์มอัจฉริยะ & มอนิเตอร์เซนเซอร์บอร์ด ESP32-S3 ATD3.5 (LEQs xAI)',
            'version' => '1.0.0 (Release Build)',
            'filename' => 'handysense_controller_v1.0.apk',
            'category' => 'Smart Agriculture & IoT',
            'badge_label' => 'Main Workshop App',
            'badge_color' => '#10b981', // Emerald
            'description' => 'แอปพลิเคชันหลักประจำการอบรม LEQs xAI เชื่อมต่อบอร์ด ESP32-S3 ATD3.5 แสดงผลเซนเซอร์ NPK, Soil 7in1, Soil Stick, SHT45, BH1750 ควบคุมรีเลย์ 4 ช่อง และ AI Edge Assistant แนะนำการให้น้ำและธาตุอาหารตามสภาพแวดล้อมจริง',
            'highlights' => json_encode([
                'Live Telemetry 60 FPS ควบคุมรีเลย์เปิด-ปิดปั๊มน้ำ/วาล์วปุ๋ย',
                'อ่านค่า NPK, ความชื้น, อุณหภูมิ, EC, pH และแสง Real-time',
                'Dual-Band WiFi & Offline Mode รองรับเครือข่ายฟาร์ม',
                'รองรับการทำงานเชื่อมต่อ Fleet Dashboard 15 กลุ่ม'
            ], JSON_UNESCAPED_UNICODE),
            'target_platform' => 'Android (5.0 ขึ้นไป)',
            'icon_type' => 'fa-seedling'
        ],
        [
            'id' => 'durianleaf',
            'name' => 'DurianLeaf AI Plant Health Scanner',
            'sub_title' => 'ระบบตรวจวินิจฉัยโรคและสุขภาพใบพืชเศรษฐกิจด้วย Mobile Computer Vision',
            'version' => '1.0.0 (Deep Learning Kit)',
            'filename' => 'durianleaf_ai_v1.0.apk',
            'category' => 'Edge AI & Plant Pathology',
            'badge_label' => 'Computer Vision AI',
            'badge_color' => '#8b5cf6', // Violet
            'description' => 'แอปพลิเคชันสแกนและวินิจฉัยโรคใบทุเรียนและพืชสวนเศรษฐกิจด้วยปัญญาประดิษฐ์ Deep Learning แบบ On-Device Vision Classifier ทำงานได้รวดเร็วแม้ไม่มีสัญญาณอินเทอร์เน็ตในแปลงสวน',
            'highlights' => json_encode([
                'Edge ML Model พรีโหลดในตัว ถ่ายภาพและวิเคราะห์ได้ทันที 0.3 วินาที',
                'ตรวจจับโรคราใบติด ราสีชมพู เพลี้ยไก่แจ้ และอาการขาดธาตุอาหาร',
                'คำแนะนำเวชภัณฑ์และการจัดการเกษตรอินทรีย์/เคมีแบบแม่นยำ',
                'บันทึกพิกัด GPS แปลงที่พบโรคเพื่อวางแผนฉีดพ่นรักษา'
            ], JSON_UNESCAPED_UNICODE),
            'target_platform' => 'Android (6.0 ขึ้นไป)',
            'icon_type' => 'fa-leaf'
        ],
        [
            'id' => 'smarthealth',
            'name' => 'Smart Health & Vital Signs Tracker',
            'sub_title' => 'ระบบมอนิเตอร์สัญญาณชีพ & สุขภาพเกษตรกรยุคดิจิทัลผ่านเซนเซอร์ AIoT',
            'version' => '1.0.0 (Health AI Edition)',
            'filename' => 'smart_health_tracker_v1.0.apk',
            'category' => 'HealthTech & Bio-Sensing',
            'badge_label' => 'Health AIoT',
            'badge_color' => '#ef4444', // Red
            'description' => 'แอปพลิเคชันติดตามสุขภาพ สัญญาณชีพ และสภาวะความร้อนของเกษตรกรขณะปฏิบัติงานกลางแจ้ง แจ้งเตือนความเสี่ยงภาวะ Heat Stroke และระดับออกซิเจน SpO2/ชีพจรแบบเรียลไทม์',
            'highlights' => json_encode([
                'เชื่อมต่อเซนเซอร์วัดอัตราการเต้นหัวใจ (BPM) & ความอิ่มตัวออกซิเจน SpO2',
                'ระบบแจ้งเตือนสภาวะฉุกเฉินและดัชนีความร้อน (Heat Stress Alert)',
                'วิเคราะห์แนวโน้มสุขภาพและความเหนื่อยล้าด้วย AI อัจฉริยะ',
                'UI สวยงาม กราฟิคทางการแพทย์เข้าใจง่ายสำหรับเกษตรกร'
            ], JSON_UNESCAPED_UNICODE),
            'target_platform' => 'Android (5.0 ขึ้นไป)',
            'icon_type' => 'fa-heart-pulse'
        ],
        [
            'id' => 'objectdetect',
            'name' => 'AI Multi-Object Agri-Detector',
            'sub_title' => 'เครื่องมือตรวจจับและนับจำนวนผลผลิตการเกษตรอัตโนมัติด้วย AI Vision',
            'version' => '1.0.0 (Vision Detection)',
            'filename' => 'ai_multi_object_detector_v1.0.apk',
            'category' => 'Computer Vision & Automation',
            'badge_label' => 'YOLO Vision Kit',
            'badge_color' => '#06b6d4', // Cyan
            'description' => 'ระบบตรวจจับ จำแนกประเภท และประเมินผลผลิตพืชเกษตรแบบ Real-time Multi-Object Detection ด้วยโมเดลวิชั่นความเร็วสูง ตีกรอบ Bounding Box พร้อมค่าความเชื่อมั่น (Confidence)',
            'highlights' => json_encode([
                'Real-time Object Detection เฟรมเรตสูงผ่านกล้องสมาร์ทโฟน',
                'นับจำนวนผลผลิต คัดขนาด และตรวจสอบตำหนิบนผิวผลไม้',
                'ส่งออกรายงานภาพถ่ายพร้อม Metadata สำหรับงานวิจัยเกษตร',
                'ปรับเกณฑ์ Threshold ความแม่นยำได้ตามแสงและสภาพแวดล้อม'
            ], JSON_UNESCAPED_UNICODE),
            'target_platform' => 'Android (7.0 ขึ้นไป)',
            'icon_type' => 'fa-camera-rotate'
        ],
        [
            'id' => 'workshop_kit',
            'name' => 'LEQs Workshop Master Source & Docs Pack',
            'sub_title' => 'ชุดไฟล์ซอร์สโค้ดเฟิร์มแวร์ ESP32-S3, แผนผังวงจร และคู่มือปฏิบัติการฉบับสมบูรณ์',
            'version' => '2026.1 (Full Bundle)',
            'filename' => 'leqs_workshop_bundle.zip',
            'category' => 'Firmware & Workshop Manual',
            'badge_label' => 'Full Source & PDF',
            'badge_color' => '#f59e0b', // Amber
            'description' => 'รวมไฟล์เอกสารประกอบการอบรม คู่มือการเชื่อมต่อบอร์ด ATD3.5-S3 กับ LEQs xAI ไฟล์ซอร์สโค้ด C++/PlatformIO แผนภาพการต่อสายเซนเซอร์ และชุดคำสั่ง AI Prompt สำหรับผู้เข้าร่วมอบรม',
            'highlights' => json_encode([
                'คู่มือ PDF: การใช้ ATD3.5-S3 เชื่อมต่อ LEQs xAI ฉบับสมบูรณ์',
                'PlatformIO C++ Project ครบทุกไลบรารีพร้อมคอมไพล์',
                'WIRING_DIAGRAM.md แผนผังการต่อวงจรเซนเซอร์ RS485 & I2C',
                'MANUAL_AND_PROMPT_BOOK.md ตำราและชุดคำสั่งการวิจัย'
            ], JSON_UNESCAPED_UNICODE),
            'target_platform' => 'ZIP Package (Windows / macOS / Linux / Android)',
            'icon_type' => 'fa-file-zipper'
        ]
    ];

    $insertStmt = $db->prepare("
        INSERT INTO apps (
            id, name, sub_title, version, filename, filesize_bytes, category, 
            badge_label, badge_color, description, highlights, target_platform, icon_type
        ) VALUES (
            :id, :name, :sub_title, :version, :filename, :filesize_bytes, :category,
            :badge_label, :badge_color, :description, :highlights, :target_platform, :icon_type
        ) ON CONFLICT(id) DO UPDATE SET
            name = excluded.name,
            sub_title = excluded.sub_title,
            version = excluded.version,
            filename = excluded.filename,
            filesize_bytes = excluded.filesize_bytes,
            category = excluded.category,
            badge_label = excluded.badge_label,
            badge_color = excluded.badge_color,
            description = excluded.description,
            highlights = excluded.highlights,
            target_platform = excluded.target_platform,
            icon_type = excluded.icon_type,
            updated_at = CURRENT_TIMESTAMP
    ");

    $results = [];
    foreach ($appsData as $app) {
        $filePath = $filesDir . '/' . $app['filename'];
        $filesize = file_exists($filePath) ? filesize($filePath) : 0;
        $app['filesize_bytes'] = $filesize;

        $insertStmt->execute([
            ':id' => $app['id'],
            ':name' => $app['name'],
            ':sub_title' => $app['sub_title'],
            ':version' => $app['version'],
            ':filename' => $app['filename'],
            ':filesize_bytes' => $app['filesize_bytes'],
            ':category' => $app['category'],
            ':badge_label' => $app['badge_label'],
            ':badge_color' => $app['badge_color'],
            ':description' => $app['description'],
            ':highlights' => $app['highlights'],
            ':target_platform' => $app['target_platform'],
            ':icon_type' => $app['icon_type']
        ]);

        $results[] = [
            'id' => $app['id'],
            'name' => $app['name'],
            'filename' => $app['filename'],
            'size_mb' => round($filesize / (1024 * 1024), 2)
        ];
    }

    // Adjust file permissions
    @chmod($dbPath, 0666);
    @chmod($dbDir, 0777);

    echo json_encode([
        'status' => 'success',
        'message' => 'Database initialized successfully',
        'database' => $dbPath,
        'apps_count' => count($results),
        'apps' => $results
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
