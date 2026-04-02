<?php

header('Content-Type: application/json');

include '../../connect/config.php';

try {
    $pdo = getDBConnection();

    // Fetch all products with stock information
    $stmt = $pdo->query("
        SELECT 
            id,
            name,
            category,
            stock,
            price
        FROM products
        ORDER BY stock ASC, name ASC
    ");

    $products = $stmt->fetchAll();

    // Calculate inventory statistics
    $totalStock = 0;
    $lowStockCount = 0;
    $outOfStockCount = 0;
    $lowStockProducts = [];
    $outOfStockProducts = [];

    // Define threshold for low stock
    $lowStockThreshold = 5;

    foreach ($products as $product) {
        $stock = (int)$product['stock'];
        $totalStock += $stock;

        if ($stock === 0) {
            $outOfStockCount++;
            $outOfStockProducts[] = $product;
        } elseif ($stock < $lowStockThreshold) {
            $lowStockCount++;
            $lowStockProducts[] = $product;
        }
    }

    // Prepare response
    $response = [
        'success' => true,
        'stats' => [
            'totalStock' => $totalStock,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount
        ],
        'alerts' => [
            'outOfStock' => $outOfStockProducts,
            'lowStock' => $lowStockProducts
        ],
        'products' => $products
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
