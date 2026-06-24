<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    $name = trim($_POST['name'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    $productTypeId = (int)($_POST['product_type_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $unit = $_POST['unit'] ?? 'piece';
    $materialType = $_POST['material_type'] ?? 'assembled_product';
    $description = trim($_POST['description'] ?? '');
    $materials = json_decode($_POST['materials'] ?? '[]', true);

    // Validate
    if (empty($name)) throw new Exception('Product name is required');
    if ($productTypeId <= 0) throw new Exception('Product type is required');
    if ($price < 0) throw new Exception('Price must be non-negative');
    if ($stock < 0) throw new Exception('Stock must be non-negative');

    $pdo->beginTransaction();

    // REMOVED category_id from INSERT - it doesn't exist in your table
    $stmt = $pdo->prepare("
        INSERT INTO products (name, sku, product_type_id, price, stock, unit, material_type, description, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')
    ");
    $stmt->execute([$name, $sku, $productTypeId, $price, $stock, $unit, $materialType, $description]);
    $productId = $pdo->lastInsertId();

    // Insert materials
    if (!empty($materials) && is_array($materials)) {
        $matStmt = $pdo->prepare("INSERT INTO product_materials (product_id, material_id, quantity) VALUES (?, ?, ?)");
        foreach ($materials as $mat) {
            if (!empty($mat['material_id'])) {
                $matStmt->execute([
                    $productId,
                    (int)$mat['material_id'],
                    (float)($mat['quantity'] ?? 1)
                ]);
            }
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Product added successfully',
        'id' => $productId
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
