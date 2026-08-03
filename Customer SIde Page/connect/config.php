<?php

// ===============================
// DATABASE CONFIGURATION
// ===============================
define('DB_HOST', 'localhost');
define('DB_NAME', 'new_ai_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'Anything Inside');
define('SITE_EMAIL', 'no-reply@anythinginside.com');

// ===============================
// TABLE NAME – UPDATED to match your actual table
// ===============================
define('PRODUCTS_TABLE', 'bundles_merchandise');

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
            return null;
        }
    }

    return $pdo;
}

// ===============================
// GET PRODUCTS – filters by type only (category removed)
// ===============================
function getProducts($pdo = null, $category = null, $type = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }

    if (!$pdo) {
        return [];
    }

    $table = PRODUCTS_TABLE;

    $sql = "SELECT * FROM {$table} WHERE 1=1";
    $params = [];

    if ($type !== null) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    // Category filter removed – uncomment if you add a 'category' column later
    /*
    if ($category !== null) {
        $sql .= " AND LOWER(category) = LOWER(?)";
        $params[] = $category;
    }
    */

    $sql .= " ORDER BY name";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// ===============================
// GET SINGLE PRODUCT (by ID, optionally enforce type)
// ===============================
function getProduct($pdo, $id, $type = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }

    if (!$pdo) {
        return false;
    }

    $table = PRODUCTS_TABLE;

    $sql = "SELECT * FROM {$table} WHERE id = ?";
    $params = [$id];

    if ($type !== null) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// ===============================
// COMPATIBILITY WRAPPERS
// ===============================
function getBundleMerchandise($pdo = null, $category = null)
{
    return getProducts($pdo, $category, 'bundle');
}

function getBundles($pdo = null, $category = null)
{
    return getProducts($pdo, $category, 'bundle');
}

function getMerchandise($pdo = null, $category = null)
{
    return getProducts($pdo, $category, 'merchandise');
}

// ===============================
// GET CATEGORY ID BY NAME (unchanged)
// ===============================
function getCategoryIdByName($pdo, $name)
{
    $name = trim($name);
    if ($name === '') {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
        $stmt->execute([$name]);
        $row = $stmt->fetch();

        if ($row) {
            return $row['id'];
        }

        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $name), '-'));
        $insert = $pdo->prepare("INSERT INTO categories (name, slug, status, created_at, updated_at) VALUES (?, ?, 'active', NOW(), NOW())");
        $insert->execute([$name, $slug]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return null;
    }
}

// ===============================
// NEW: IMAGE HELPERS
// ===============================

/**
 * Generate a public URL for a product's image.
 * Assumes your document root is the base and image_path is relative to it.
 * If image_path is empty or null, returns a fallback placeholder URL.
 */
function getProductImageUrl($product, $fallback = 'https://via.placeholder.com/300x200?text=No+Image')
{
    if (!empty($product['image_path'])) {
        // If the stored path already starts with '/', use it as is.
        // Otherwise, prepend a slash to make it absolute from document root.
        $path = $product['image_path'];
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        return $path;
    }
    return $fallback;
}

/**
 * Serve the actual image file directly (binary output).
 * Use this in a dedicated script like image.php?id=123
 * It will output the image with correct MIME type and cache headers.
 */
function serveProductImage($id, $pdo = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }
    if (!$pdo) {
        header('HTTP/1.0 500 Internal Server Error');
        exit;
    }

    $product = getProduct($pdo, $id);
    if (!$product || empty($product['image_path'])) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    // Build filesystem path (adjust to your document root)
    $baseDir = $_SERVER['DOCUMENT_ROOT'];
    $filePath = $baseDir . $product['image_path'];

    // Security: prevent directory traversal (already safe because path comes from DB, but double-check)
    $realBase = realpath($baseDir);
    $realFile = realpath($filePath);
    if ($realFile === false || strpos($realFile, $realBase) !== 0) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }

    if (!file_exists($filePath) || !is_readable($filePath)) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    // Determine MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filePath);
    finfo_close($finfo);
    if (!$mime) {
        $mime = 'application/octet-stream';
    }

    // Output with caching
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: public, max-age=86400');
    readfile($filePath);
    exit;
}

// ===============================
// OPTIONAL: AUTO-DISPLAY (for testing)
// When this file is executed directly, show all products with images.
// ===============================
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    $pdo = getDBConnection();
    if (!$pdo) {
        die('Database connection failed');
    }

    $merchandise = getMerchandise($pdo);
    $bundles = getBundles($pdo);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Product Listing – <?= SITE_NAME ?></title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .grid { display: flex; flex-wrap: wrap; gap: 20px; }
            .product { border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 220px; text-align: center; }
            .product img { max-width: 100%; height: auto; max-height: 200px; object-fit: cover; border-radius: 4px; }
            .product h3 { margin: 10px 0 5px; font-size: 1.1em; }
            .product p { font-size: 0.9em; color: #555; }
            .badge { display: inline-block; background: #007bff; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 0.7em; margin-top: 5px; }
            .section { margin-bottom: 30px; }
            .section h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        </style>
    </head>
    <body>
        <h1><?= SITE_NAME ?> – Products</h1>

        <div class="section">
            <h2>Merchandise</h2>
            <div class="grid">
                <?php foreach ($merchandise as $item): ?>
                    <div class="product">
                        <img src="<?= htmlspecialchars(getProductImageUrl($item)) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars(substr($item['description'], 0, 60)) ?>…</p>
                        <span class="badge">Merchandise</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>Bundles</h2>
            <div class="grid">
                <?php foreach ($bundles as $item): ?>
                    <div class="product">
                        <img src="<?= htmlspecialchars(getProductImageUrl($item)) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars(substr($item['description'], 0, 60)) ?>…</p>
                        <span class="badge">Bundle</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit; // Stop further execution when run directly
}