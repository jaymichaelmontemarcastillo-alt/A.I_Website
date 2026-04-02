<?php

/**
 * CATEGORIES TABLE MIGRATION
 * Run this file once to create the categories table
 * 
 * After running, comment out the execution or delete the file
 */

include '../config.php';

try {
    $pdo = getDBConnection();

    // Check if categories table exists
    $check = $pdo->query("SHOW TABLES LIKE 'categories'");

    if ($check->rowCount() > 0) {
        echo "Categories table already exists.";
        exit;
    }

    // Create categories table
    $sql = "
    CREATE TABLE `categories` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL UNIQUE,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `description` TEXT DEFAULT NULL,
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        INDEX `idx_slug` (`slug`),
        INDEX `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sql);

    // Seed with existing categories from products table
    $stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $insertStmt = $pdo->prepare("
        INSERT INTO categories (name, slug, description, status) 
        VALUES (?, ?, ?, 'active')
    ");

    foreach ($categories as $category) {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $category), '-'));
        $insertStmt->execute([$category, $slug, '']);
    }

    echo "Categories table created and seeded successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
