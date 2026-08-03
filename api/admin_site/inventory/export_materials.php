<?php
session_start();
require_once '../../../connect/config.php';

if (ob_get_level()) ob_end_clean();
ob_start();

try {
    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized');
    }

    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT type, material_name, shop_stock, ph_stock, total_stock, 
               total_cost, pieces_per_pack, unit_cost, remarks
        FROM materials 
        ORDER BY type, material_name
    ");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Clear output buffer before sending headers
    if (ob_get_level()) ob_end_clean();

    // Send headers ONLY after processing is complete
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="materials_inventory_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Cache-Control: max-age=0, must-revalidate');
    header('Pragma: public');

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
