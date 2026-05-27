<?php
// api/admin_site/inventory/export_materials.php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="materials_inventory_' . date('Y-m-d') . '.csv"');

require_once '../../../connect/config.php';

session_start();
if (empty($_SESSION['admin_id'])) {
    die('Unauthorized');
}

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT type, material_name, shop_stock, ph_stock, total_stock, 
               total_cost, pieces_per_pack, unit_cost, remarks
        FROM materials 
        ORDER BY type, material_name
    ");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for Excel
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Headers
    fputcsv($output, ['Type', 'Materials', 'Shop', 'PH', 'Total On Hand', 'Total Cost', 'Quantity per pack', 'Unit Cost', 'Remarks']);

    // Data
    foreach ($materials as $material) {
        fputcsv($output, [
            $material['type'] ?? '',
            $material['material_name'] ?? '',
            $material['shop_stock'] ?? 0,
            $material['ph_stock'] ?? 0,
            $material['total_stock'] ?? 0,
            $material['total_cost'] ?? 0,
            $material['pieces_per_pack'] ?? 1,
            $material['unit_cost'] ?? 0,
            $material['remarks'] ?? ''
        ]);
    }

    fclose($output);
} catch (Exception $e) {
    echo "Export failed: " . $e->getMessage();
}
exit;
