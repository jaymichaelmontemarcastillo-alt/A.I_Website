<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('Invalid product ID');
    }

    $stmt = $pdo->prepare("
        SELECT p.*, pt.name AS product_type_name
        FROM products p
        LEFT JOIN product_types pt ON pt.id = p.product_type_id
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('Product not found');
    }

    // Get materials
    $matStmt = $pdo->prepare("
        SELECT pm.*, m.material_name, m.type AS material_type
        FROM product_materials pm
        JOIN materials m ON m.id = pm.material_id
        WHERE pm.product_id = ?
        ORDER BY m.type, m.material_name
    ");
    $matStmt->execute([$id]);
    $product['materials'] = $matStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $product]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
