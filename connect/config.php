<?php

// ===============================
// DATABASE CONFIGURATION
// ===============================
define('DB_HOST', 'localhost');
define('DB_NAME', 'anything_inside_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// ===============================
// CREATE PDO CONNECTION
// ===============================
function getDBConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

// ===============================
// GET PRODUCTS
// ===============================
/**
 * Get all products from database
 * @param PDO $pdo Database connection
 * @return array Array of products
 */
/**
 * Get all active products with category information
 * @param PDO $pdo Database connection
 * @return array Array of products with category name
 */
function getProducts($pdo)
{
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add category field for backward compatibility
        foreach ($products as &$product) {
            $product['category'] = $product['category_name'] ?? 'Uncategorized';
        }

        return $products;
    } catch (PDOException $e) {
        error_log("Error in getProducts: " . $e->getMessage());
        return [];
    }
}

/**
 * Get products by category name
 * @param PDO $pdo Database connection
 * @param string $categoryName Category name
 * @return array Array of products
 */
function getProductsByCategory($pdo, $categoryName)
{
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active' AND LOWER(c.name) = LOWER(?)
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$categoryName]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['category'] = $product['category_name'] ?? 'Uncategorized';
        }

        return $products;
    } catch (PDOException $e) {
        error_log("Error in getProductsByCategory: " . $e->getMessage());
        return [];
    }
}

// ===============================
// GET SINGLE PRODUCT
// ===============================
function getProduct($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
