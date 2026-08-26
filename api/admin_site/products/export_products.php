<?php
session_start();
require_once __DIR__ . '/../../../connect/config.php';

while (ob_get_level()) {
    ob_end_clean();
}
ob_start();
try {
    $pdo = getDBConnection();

    // Get all products with their types and materials
    $stmt = $pdo->query("
        SELECT 
            pt.name AS product_type,
            p.name AS product_name,
            p.sku,
            p.price,
            p.stock,
            p.unit,
            p.material_type,
            p.description,
            p.status,
            GROUP_CONCAT(
                CONCAT(m.material_name, ' (', COALESCE(pm.quantity, 0), ')')
                SEPARATOR '; '
            ) AS materials
        FROM products p
        LEFT JOIN product_types pt ON pt.id = p.product_type_id
        LEFT JOIN product_materials pm ON pm.product_id = p.id
        LEFT JOIN materials m ON m.id = pm.material_id
        GROUP BY p.id
        ORDER BY pt.name, p.name
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Clear any output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Send headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Cache-Control: max-age=0, must-revalidate');
    header('Pragma: public');

    $output = fopen('php://output', 'w');

    // Output UTF-8 BOM for Excel compatibility
    fprintf($output, "\xEF\xBB\xBF");

    // Write CSV headers
    fputcsv($output, [
        'Product Type',
        'Product Name',
        'SKU',
        'Price (₱)',
        'Stock',
        'Unit',
        'Material Type',
        'Description',
        'Status',
        'Materials Used'
    ]);

    // Write CSV data rows
    foreach ($products as $product) {
        fputcsv($output, [
            $product['product_type'] ?? 'Uncategorized',
            $product['product_name'],
            $product['sku'] ?? '',
            $product['price'] ?? 0,
            $product['stock'] ?? 0,
            $product['unit'] ?? 'piece',
            $product['material_type'] ?? 'assembled_product',
            $product['description'] ?? '',
            $product['status'] ?? 'active',
            $product['materials'] ?? ''
        ]);
    }

    fclose($output);
    exit;

} catch (Exception $e) {
    if (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Export failed: ' . $e->getMessage()
    ]);
    exit;
}
