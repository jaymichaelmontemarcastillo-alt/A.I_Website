<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';
//api/delete_product_type.php
try {
    $pdo = getDBConnection();

    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int)($input['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('Invalid product type ID');
    }

    // Check if type has products
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE product_type_id = ?");
    $stmt->execute([$id]);
    $productCount = (int)$stmt->fetchColumn();

    if ($productCount > 0) {
        throw new Exception("Cannot delete: This type has $productCount product(s) associated with it");
    }

    // Delete the type
    $stmt = $pdo->prepare("DELETE FROM product_types WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Product type not found');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Product type deleted successfully'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
