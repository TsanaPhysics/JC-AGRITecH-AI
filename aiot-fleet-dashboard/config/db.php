<?php
/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - Database Connection & Migration
 * ============================================================================
 */

function getFleetDatabase(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dbDir = __DIR__ . '/../database';
    if (!is_dir($dbDir)) {
        @mkdir($dbDir, 0777, true);
    }

    $dbFile = $dbDir . '/fleet.db';
    $isFirstTime = !file_exists($dbFile);

    try {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Foreign keys & WAL mode for high concurrency
        $pdo->exec('PRAGMA foreign_keys = ON;');
        $pdo->exec('PRAGMA journal_mode = WAL;');

        if ($isFirstTime || filesize($dbFile) === 0) {
            $schemaFile = __DIR__ . '/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $pdo->exec($sql);
            }
        }

        return $pdo;
    } catch (PDOException $e) {
        die(json_encode([
            'status' => 'error',
            'message' => 'Fleet Database Connection Failed: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE));
    }
}
