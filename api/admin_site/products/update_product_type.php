<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    if ($id <= 0) {
        throw new Exception('Invalid product type ID');
    }
    if (empty($name)) {
        throw new Exception('Product type name is required');
    }

    // Check if another type has this name
    $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = ? AND id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetch()) {
        throw new Exception('Product type name already exists');
    }

    // Generate new slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    $stmt = $pdo->prepare("UPDATE product_types SET name = ?, slug = ? WHERE id = ?");
    $stmt->execute([$name, $slug, $id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Product type not found or no changes made');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Product type updated successfully',
        'data' => [
            'id' => $id,
            'name' => $name,
            'slug' => $slug
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
