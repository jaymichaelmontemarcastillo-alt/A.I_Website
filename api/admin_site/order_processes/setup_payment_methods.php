<?php

/**
 * Payment Methods Table Initialization Script
 * Run this file once to create the payment_methods table
 * Access: /api/admin_site/order_processes/setup_payment_methods.php
 */

require_once '../../../connect/config.php';

header('Content-Type: application/json');

try {
    $pdo = getDBConnection();

    // Check if table already exists
    $checkTableQuery = "SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'payment_methods'";
    $checkTableStmt = $pdo->prepare($checkTableQuery);
    $checkTableStmt->execute([DB_NAME]);

    if ($checkTableStmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Payment methods table already exists',
            'action' => 'none'
        ]);
        exit;
    }

    // Create the payment_methods table
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS `payment_methods` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `method_name` varchar(100) NOT NULL COMMENT 'Display name of the payment method',
      `method_value` varchar(50) NOT NULL UNIQUE COMMENT 'Identifier used in database',
      `icon_class` varchar(100) DEFAULT 'fa-solid fa-credit-card' COMMENT 'Font Awesome icon class',
      `sort_order` int(11) DEFAULT 999,
      `is_system` tinyint(1) DEFAULT 0 COMMENT '1 if cannot be deleted',
      `is_active` tinyint(1) DEFAULT 1,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_method_value` (`method_value`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    $pdo->exec($createTableSQL);

    // Insert default payment methods
    $insertSQL = "
    INSERT IGNORE INTO `payment_methods` 
    (`method_name`, `method_value`, `icon_class`, `sort_order`, `is_system`, `is_active`) 
    VALUES 
    ('Cash on Delivery', 'cash', 'fa-solid fa-money-bill-wave', 1, 1, 1),
    ('GCash', 'gcash', 'fa-solid fa-mobile-alt', 2, 1, 1),
    ('Card', 'card', 'fa-regular fa-credit-card', 3, 1, 1)
    ";

    $pdo->exec($insertSQL);

    echo json_encode([
        'success' => true,
        'message' => 'Payment methods table created and initialized successfully',
        'action' => 'created'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
