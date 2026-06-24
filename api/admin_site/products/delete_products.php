<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
session_start();

error_log("=== DELETE PRODUCT REQUEST ===");
error_log("Raw input: " . file_get_contents('php://input'));

require_once __DIR__ . '/../../../connect/config.php';

try {
    if (!function_exists('getDBConnection')) {
        throw new Exception('Database connection function not found');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Failed to connect to database');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    error_log("Parsed input: " . print_r($input, true));

    $id = (int)($input['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Check if product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        throw new Exception('Product not found');
    }

    $pdo->beginTransaction();

    // Delete product_materials first
    $pdo->prepare("DELETE FROM product_materials WHERE product_id = ?")->execute([$id]);

    // Delete product
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);

    $pdo->commit();

    error_log("Product deleted: $id");
    echo json_encode([
        'success' => true,
        'message' => 'Product deleted successfully'
    ]);
} catch (Exception $e) {
    error_log("ERROR in delete_product: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
