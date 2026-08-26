<?php

// ===============================
// DATABASE CONFIGURATION
// ===============================
define('DB_HOST', 'localhost');
define('DB_NAME', 'anything_inside_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_NAME', 'My Store');

// ===============================
// EMAIL / APP PASSWORD ENCRYPTION KEY
// Used by connect/email_crypto.php to encrypt/decrypt the Gmail
// app password stored in the email_settings table. Keep this out
// of version control if this repo is ever public.
// ===============================
define('EMAIL_SECRET_KEY', '5ee9cf12941f497efccd53bbd6316b627905333a5a50d208794df27585ffe43c');

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
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

// ===============================
// GET PRODUCTS
// ===============================
/**
 * Get all active products with category information
 * @param PDO $pdo Database connection
 * @return array Array of products with category name
 */
function getProducts($pdo, $category = null, $type = null)
{
    try {
        $sql = "
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
        ";
        $params = [];

        // Filter by category if provided
        if ($category !== null) {
            $sql .= " AND LOWER(c.name) = LOWER(?)";
            $params[] = $category;
        }

        // Filter by product type if provided
        if ($type !== null) {
            $sql .= " AND p.type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
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
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error in getProduct: " . $e->getMessage());
        return null;
    }
}

/**
 * Get bundle merchandise
 */
function getBundleMerchandise($pdo = null, $category = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }
    return getProducts($pdo, $category, 'bundle');
}

/**
 * Get bundles
 */
function getBundles($pdo = null, $category = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }
    return getProducts($pdo, $category, 'bundle');
}

/**
 * Get merchandise
 */
function getMerchandise($pdo = null, $category = null)
{
    if ($pdo === null) {
        $pdo = getDBConnection();
    }
    return getProducts($pdo, $category, 'merchandise');
}

// ===============================
// GET CATEGORY ID BY NAME
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
        error_log("Error in getCategoryIdByName: " . $e->getMessage());
        return null;
    }
}

// ===============================
// IMAGE HELPERS
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

    // Security: prevent directory traversal
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
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; }
            .grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start; }
            .product { 
                border: 1px solid #ddd; 
                border-radius: 8px; 
                padding: 15px; 
                width: 220px; 
                text-align: center; 
                background: white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: transform 0.2s;
            }
            .product:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }
            .product img { 
                max-width: 100%; 
                height: auto; 
                max-height: 200px; 
                object-fit: cover; 
                border-radius: 4px; 
                margin-bottom: 10px;
            }
            .product h3 { 
                margin: 10px 0 5px; 
                font-size: 1.1em; 
                color: #333;
            }
            .product p { 
                font-size: 0.9em; 
                color: #666;
                margin: 5px 0;
            }
            .product .price {
                font-weight: bold;
                color: #28a745;
                font-size: 1.1em;
                margin: 8px 0;
            }
            .badge { 
                display: inline-block; 
                background: #007bff; 
                color: #fff; 
                padding: 2px 12px; 
                border-radius: 12px; 
                font-size: 0.7em; 
                margin-top: 5px;
            }
            .badge.bundle { background: #6f42c1; }
            .badge.merchandise { background: #28a745; }
            .section { 
                margin-bottom: 40px; 
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .section h2 { 
                border-bottom: 2px solid #eee; 
                padding-bottom: 10px; 
                margin-top: 0;
                color: #333;
            }
            .section h2 span {
                font-weight: normal;
                font-size: 0.7em;
                color: #666;
            }
            .no-products {
                color: #999;
                font-style: italic;
                padding: 20px;
                text-align: center;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1><?= SITE_NAME ?> – Products</h1>

            <div class="section">
                <h2>Merchandise <span>(<?= count($merchandise) ?> items)</span></h2>
                <?php if (empty($merchandise)): ?>
                    <div class="no-products">No merchandise products available</div>
                <?php else: ?>
                    <div class="grid">
                        <?php foreach ($merchandise as $item): ?>
                            <div class="product">
                                <img src="<?= htmlspecialchars(getProductImageUrl($item)) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p><?= htmlspecialchars(substr($item['description'] ?? '', 0, 60)) ?>…</p>
                                <?php if (!empty($item['price'])): ?>
                                    <div class="price">$<?= number_format($item['price'], 2) ?></div>
                                <?php endif; ?>
                                <span class="badge merchandise">Merchandise</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section">
                <h2>Bundles <span>(<?= count($bundles) ?> items)</span></h2>
                <?php if (empty($bundles)): ?>
                    <div class="no-products">No bundle products available</div>
                <?php else: ?>
                    <div class="grid">
                        <?php foreach ($bundles as $item): ?>
                            <div class="product">
                                <img src="<?= htmlspecialchars(getProductImageUrl($item)) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p><?= htmlspecialchars(substr($item['description'] ?? '', 0, 60)) ?>…</p>
                                <?php if (!empty($item['price'])): ?>
                                    <div class="price">$<?= number_format($item['price'], 2) ?></div>
                                <?php endif; ?>
                                <span class="badge bundle">Bundle</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit; // Stop further execution when run directly
}
?>