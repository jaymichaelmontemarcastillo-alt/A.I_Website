<?php

/**
 * Fix Collation Mismatch
 * Ensures all tables use consistent UTF8MB4 collation
 */

include 'config.php';

try {
    $pdo = getDBConnection();

    echo "Fixing collations...<br><br>";

    // Check current collations
    $stmt = $pdo->query("
        SELECT TABLE_NAME, TABLE_COLLATION 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = 'anything_inside_db'
    ");

    echo "<strong>Current Collations:</strong><br>";
    while ($row = $stmt->fetch()) {
        echo "- {$row['TABLE_NAME']}: {$row['TABLE_COLLATION']}<br>";
    }
    echo "<br>";

    // Fix products table collation
    $pdo->exec("ALTER TABLE products CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ products table collation fixed<br>";

    // Fix categories table collation
    if ($pdo->query("SHOW TABLES LIKE 'categories'")->rowCount() > 0) {
        $pdo->exec("ALTER TABLE categories CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ categories table collation fixed<br>";
    }

    // Fix column collations in products
    $pdo->exec("ALTER TABLE products MODIFY COLUMN category VARCHAR(100) COLLATE utf8mb4_unicode_ci");
    echo "✓ products.category column collation fixed<br>";

    // Fix column collations in categories
    if ($pdo->query("SHOW TABLES LIKE 'categories'")->rowCount() > 0) {
        $pdo->exec("ALTER TABLE categories MODIFY COLUMN name VARCHAR(255) COLLATE utf8mb4_unicode_ci");
        $pdo->exec("ALTER TABLE categories MODIFY COLUMN slug VARCHAR(255) COLLATE utf8mb4_unicode_ci");
        echo "✓ categories.name and slug collations fixed<br>";
    }

    echo "<br><strong>Collations fixed successfully!</strong><br>";
    echo "You can now delete this file.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
