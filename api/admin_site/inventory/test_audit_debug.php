<?php
// Place this in: api/admin_site/inventory/test_audit_debug.php
// Run this directly to test your setup

error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: text/html');

echo "<h1>Audit System Debug</h1>";

// Test database connection
$configPath = __DIR__ . '/../../../connect/config.php';
echo "<p>Config path: " . $configPath . "</p>";

if (!file_exists($configPath)) {
    die("<p style='color:red'>ERROR: Config file not found at: " . $configPath . "</p>");
}

require_once $configPath;

try {
    $pdo = getDBConnection();
    echo "<p style='color:green'>✓ Database connection successful</p>";

    // Check tables
    $tables = ['materials', 'bom_audit', 'inventory_logs', 'audit_logs'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color:green'>✓ Table '$table' exists</p>";

            // Show columns
            $cols = $pdo->query("SHOW COLUMNS FROM $table");
            echo "<ul>";
            while ($col = $cols->fetch()) {
                echo "<li>" . $col['Field'] . " (" . $col['Type'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color:red'>✗ Table '$table' does NOT exist</p>";
        }
    }

    // Test session
    session_start();
    if (empty($_SESSION['admin_id'])) {
        echo "<p style='color:orange'>⚠ No admin logged in. Set test admin ID?</p>";
        // For testing, you can set a test admin ID
        $_SESSION['admin_id'] = 1;
        echo "<p>Set test admin_id = 1</p>";
    } else {
        echo "<p style='color:green'>✓ Admin logged in: ID " . $_SESSION['admin_id'] . "</p>";
    }

    echo "<h2>Test materials in database:</h2>";
    $stmt = $pdo->query("SELECT id, material_name, total_stock FROM materials LIMIT 5");
    while ($row = $stmt->fetch()) {
        echo "<p>ID: {$row['id']} - {$row['material_name']} (Stock: {$row['total_stock']})</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
