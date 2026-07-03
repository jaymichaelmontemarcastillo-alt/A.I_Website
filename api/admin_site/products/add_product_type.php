<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';
//api/add_product_type.php
try {
    $pdo = getDBConnection();

    $name = trim($_POST['name'] ?? '');
    if (empty($name)) {
        throw new Exception('Product type name is required');
    }

    // Check if type already exists
    $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()) {
        throw new Exception('Product type already exists');
    }

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES (?, ?, 'active')");
    $stmt->execute([$name, $slug]);
    $typeId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Product type added successfully',
        'data' => [
            'id' => $typeId,
            'name' => $name,
            'slug' => $slug
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
