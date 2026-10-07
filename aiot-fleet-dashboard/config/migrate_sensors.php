<?php
/**
 * Migration Script: Add sensor configuration fields to boards table
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

try {
    $db = getFleetDatabase();
    
    // Check existing columns
    $cols = [];
    $stmt = $db->query("PRAGMA table_info(boards)");
    while ($row = $stmt->fetch()) {
        $cols[] = $row['name'];
    }

    $added = [];
    if (!in_array('has_ai_npk', $cols)) {
        $db->exec("ALTER TABLE boards ADD COLUMN has_ai_npk BOOLEAN DEFAULT 1;");
        $added[] = 'has_ai_npk';
    }
    if (!in_array('has_vital_signs', $cols)) {
        $db->exec("ALTER TABLE boards ADD COLUMN has_vital_signs BOOLEAN DEFAULT 0;");
        $added[] = 'has_vital_signs';
    }
    if (!in_array('sensor_preset', $cols)) {
        $db->exec("ALTER TABLE boards ADD COLUMN sensor_preset VARCHAR(50) DEFAULT 'full';");
        $added[] = 'sensor_preset';
    }
    if (!in_array('notes', $cols)) {
        $db->exec("ALTER TABLE boards ADD COLUMN notes TEXT DEFAULT '';");
        $added[] = 'notes';
    }

    // Set permission on db file
    $dbFile = __DIR__ . '/../database/fleet.db';
    @chmod($dbFile, 0666);

    echo json_encode([
        'status' => 'success',
        'message' => 'Migration completed successfully',
        'added_columns' => $added,
        'current_columns' => array_merge($cols, $added)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
