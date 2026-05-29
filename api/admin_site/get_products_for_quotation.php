<?php
// api/admin_site/get_products_for_quotation.php
// Get materials/products for suggestion in quotation (using materials table)

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new RuntimeException('Database connection failed.');
    }

    // Get materials from materials table (these are the products/inventory items)
    $stmt = $pdo->query("
        SELECT id, material_name as name, unit_cost as price, type, total_stock
        FROM materials 
        WHERE total_stock > 0 OR total_stock IS NULL
        ORDER BY material_name ASC
        LIMIT 200
    ");

    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the response
    $products = array_map(function ($material) {
        return [
            'id' => $material['id'],
            'name' => $material['name'],
            'price' => floatval($material['price']),
            'type' => $material['type'],
            'stock' => intval($material['total_stock'])
        ];
    }, $materials);

    echo json_encode([
        'success' => true,
        'products' => $products,
        'message' => count($products) . ' materials available for suggestion'
    ]);
} catch (Exception $e) {
    error_log('get_products_for_quotation.php error: ' . $e->getMessage());
    echo json_encode([
        'success' => true,
        'products' => [],
        'message' => 'No materials available'
    ]);
}
