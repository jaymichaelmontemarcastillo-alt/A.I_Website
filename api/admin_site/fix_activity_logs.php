<?php

/**
 * Activity Logs Table Repair Script
 * Automatically fixes the activity_logs table structure
 * Access: /api/admin_site/fix_activity_logs.php
 */

require_once '../../connect/config.php';
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

try {
    $pdo = getDBConnection();

    // Check if table exists
    $checkTableStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'activity_logs'
    ");
    $checkTableStmt->execute([DB_NAME]);
    $tableExists = $checkTableStmt->fetchColumn() > 0;

    if (!$tableExists) {
        echo json_encode([
            'success' => false,
            'message' => 'Activity logs table does not exist'
        ]);
        exit;
    }

    // Check current primary key
    $pkCheckStmt = $pdo->prepare("
        SELECT COLUMN_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'activity_logs' AND CONSTRAINT_NAME = 'PRIMARY'
    ");
    $pkCheckStmt->execute([DB_NAME]);
    $primaryKey = $pkCheckStmt->fetchColumn();

    if ($primaryKey === 'LogID') {
        echo json_encode([
            'success' => true,
            'message' => 'Activity logs table is already fixed',
            'status' => 'already_fixed'
        ]);
        exit;
    }

    // Fix the table by adding PRIMARY KEY and AUTO_INCREMENT
    $pdo->beginTransaction();

    try {
        // If there's an existing primary key, drop it first
        if ($primaryKey && $primaryKey !== 'LogID') {
            $pdo->exec("ALTER TABLE `activity_logs` DROP PRIMARY KEY");
        }

        // Add PRIMARY KEY and AUTO_INCREMENT to LogID
        $pdo->exec("
            ALTER TABLE `activity_logs` 
            MODIFY COLUMN `LogID` INT(11) NOT NULL AUTO_INCREMENT,
            ADD PRIMARY KEY (`LogID`)
        ");

        // Create indexes for better performance
        $indexes = [
            'idx_created_at' => 'ALTER TABLE `activity_logs` ADD KEY `idx_created_at` (`CreatedAt`)',
            'idx_user_id' => 'ALTER TABLE `activity_logs` ADD KEY `idx_user_id` (`UserID`)',
            'idx_status' => 'ALTER TABLE `activity_logs` ADD KEY `idx_status` (`Status`)'
        ];

        foreach ($indexes as $indexName => $indexQuery) {
            try {
                // Check if index already exists
                $indexCheckStmt = $pdo->prepare("
                    SELECT 1 FROM information_schema.STATISTICS 
                    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'activity_logs' AND INDEX_NAME = ?
                ");
                $indexCheckStmt->execute([DB_NAME, $indexName]);

                if ($indexCheckStmt->rowCount() === 0) {
                    $pdo->exec($indexQuery);
                }
            } catch (Exception $e) {
                // Index might already exist, continue
            }
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Activity logs table has been successfully fixed',
            'status' => 'fixed',
            'changes' => [
                'Added PRIMARY KEY to LogID',
                'Added AUTO_INCREMENT to LogID',
                'Created performance indexes'
            ]
        ]);
    } catch (Exception $commitError) {
        $pdo->rollBack();
        throw $commitError;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fixing activity logs table: ' . $e->getMessage()
    ]);
}
