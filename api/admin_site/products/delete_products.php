<?php
//api/admin_site/products/delete_products.php
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

    // Get product details before deletion
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        throw new Exception('Product not found');
    }

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $result = $stmt->execute([$id]);

    if (!$result || $stmt->rowCount() === 0) {
        throw new Exception('Failed to delete product');
    }

    // Delete product image if it exists and is not the default
    if ($product['image'] && strpos($product['image'], 'default') === false) {
        $image_path = '../../../' . $product['image'];
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Product deleted successfully!'
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
