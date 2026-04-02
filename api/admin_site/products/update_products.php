<?php
//api/admin_site/products/update_products.php
header('Content-Type: application/json');
session_start();
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate product ID
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Validate required fields
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? null;
    $stock = $_POST['stock'] ?? null;

    if (empty($name) || strlen($name) < 3) {
        throw new Exception('Product name must be at least 3 characters');
    }

    if (empty($category) || strlen($category) < 2) {
        throw new Exception('Category must be at least 2 characters');
    }

    if ($price === null || !is_numeric($price) || $price < 0) {
        throw new Exception('Price must be a valid number');
    }

    if ($stock === null || !is_numeric($stock) || $stock < 0) {
        throw new Exception('Stock must be a valid number');
    }

    // Get current product image
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        throw new Exception('Product not found');
    }

    $filename = $product['image'];

    // Handle image upload if new image provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['image'];

        // Validate file size (5MB)
        if ($img['size'] > 5 * 1024 * 1024) {
            throw new Exception('Image size must be less than 5MB');
        }

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $img['tmp_name']);
        finfo_close($finfo);

        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!in_array($mime, $allowed_mimes)) {
            throw new Exception('Only JPEG, PNG, WEBP, and AVIF images are allowed');
        }

        // Delete old image if it's not the default
        if ($filename && strpos($filename, 'default') === false && file_exists('../../../' . $filename)) {
            @unlink('../../../' . $filename);
        }

        // Create uploads directory if it doesn't exist
        $upload_dir = '../../../uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $filename = 'uploads/products/' . uniqid('prod_', true) . '.' . $ext;

        if (!move_uploaded_file($img['tmp_name'], '../../../' . $filename)) {
            throw new Exception('Failed to upload image. Please check directory permissions.');
        }
    }

    // Update database
    $stmt = $pdo->prepare(
        "UPDATE products 
         SET name = ?, category = ?, price = ?, stock = ?, image = ?, updated_at = NOW() 
         WHERE id = ?"
    );

    $result = $stmt->execute([$name, $category, (float)$price, (int)$stock, $filename, $id]);

    if (!$result) {
        throw new Exception('Failed to update product');
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully!'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
