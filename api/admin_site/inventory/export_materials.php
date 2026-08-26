<?php
session_start();
require_once __DIR__ . '/../../../connect/config.php';

while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

try {
    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized. Please login as admin.');
    }

    $pdo = getDBConnection();

    // Get all materials
    $stmt = $pdo->query("
        SELECT 
            material_name,
            type,
            total_stock,
            shop_stock,
            ph_stock,
            unit_cost,
            total_cost,
            remarks
        FROM materials
        ORDER BY material_name ASC
    ");

    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Clear any output buffer before sending headers
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Send headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="materials_export_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Cache-Control: max-age=0, must-revalidate');
    header('Pragma: public');

    $output = fopen('php://output', 'w');

    // Output UTF-8 BOM for Excel compatibility
    fprintf($output, "\xEF\xBB\xBF");

    // Write CSV headers
    fputcsv($output, [
        'Material Name',
        'Type',
        'Total Stock',
        'Shop Stock',
        'PH Stock',
        'Unit Cost (₱)',
        'Total Value (₱)',
        'Remarks'
    ]);

    // Write CSV data rows
    foreach ($materials as $material) {
        fputcsv($output, [
            $material['material_name'] ?? '',
            $material['type'] ?? '',
            $material['total_stock'] ?? 0,
            $material['shop_stock'] ?? 0,
            $material['ph_stock'] ?? 0,
            $material['unit_cost'] ?? 0,
            $material['total_cost'] ?? 0,
            $material['remarks'] ?? ''
        ]);
    }

    fclose($output);
    
    // Log activity
    try {
        $adminName = $_SESSION['admin_name'] ?? 'Admin';
        require_once __DIR__ . '/../../activity_logger.php';
        logActivity($pdo, $_SESSION['admin_id'], $adminName, 'Exported all materials to CSV', 0, 'Inventory', 'Success');
    } catch (Throwable $activityErr) {
        error_log('export_materials activity log error: ' . $activityErr->getMessage());
    }

    exit;

} catch (Exception $e) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Export failed: ' . $e->getMessage()
    ]);
    exit;
}