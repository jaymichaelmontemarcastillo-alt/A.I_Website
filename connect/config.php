<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'anything_inside_db');
define('DB_USER', 'root'); // Change this to your database username
define('DB_PASS', ''); // Change this to your database password

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get products from database
function getProducts($pdo, $category = null) {
    if ($category) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE LOWER(category) = LOWER(?) ORDER BY name");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY category, name");
    }
    return $stmt->fetchAll();
}

// Function to get single product
function getProduct($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>