<?php
// api/admin_site/inventory/import_materials.php
header('Content-Type: application/json');
error_reporting(0); // Turn off error display

require_once '../../../connect/config.php';

// Start clean output
if (ob_get_level()) ob_end_clean();

$response = ['success' => false, 'message' => ''];

try {
    session_start();

    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Please select a valid CSV file');
    }

    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');

    if (!$handle) {
        throw new Exception('Cannot read file');
    }

    // Read CSV with proper encoding
    $rows = [];
    while (($data = fgetcsv($handle, 10000, ',')) !== false) {
        $rows[] = $data;
    }
    fclose($handle);

    if (count($rows) < 2) {
        throw new Exception('File contains no data rows');
    }

    // Remove header row
    $headers = array_shift($rows);

    $pdo = getDBConnection();
    $pdo->beginTransaction();

    $imported = 0;
    $updated = 0;

    foreach ($rows as $rowIndex => $row) {
        // Skip empty rows
        if (empty(array_filter($row))) continue;

        $type = trim($row[0] ?? '');
        $materialName = trim($row[1] ?? '');
        $shopStock = (int) ($row[2] ?? 0);
        $phStock = (int) ($row[3] ?? 0);
        $totalStock = (int) ($row[4] ?? 0);
        $totalCost = (float) ($row[5] ?? 0);
        $piecesPerPack = (int) ($row[6] ?? 1);
        $unitCost = (float) ($row[7] ?? 0);
        $remarks = trim($row[8] ?? '');

        if (empty($materialName)) {
            continue;
        }

        // Check if exists
        $checkStmt = $pdo->prepare("SELECT id FROM materials WHERE material_name = :name");
        $checkStmt->execute([':name' => $materialName]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Update
            $updateStmt = $pdo->prepare("
                UPDATE materials 
                SET type = :type, shop_stock = :shop, ph_stock = :ph, total_stock = :total,
                    total_cost = :total_cost, pieces_per_pack = :pieces, unit_cost = :unit_cost, remarks = :remarks
                WHERE material_name = :name
            ");
            $updateStmt->execute([
                ':type' => $type,
                ':shop' => $shopStock,
                ':ph' => $phStock,
                ':total' => $totalStock,
                ':total_cost' => $totalCost,
                ':pieces' => $piecesPerPack,
                ':unit_cost' => $unitCost,
                ':remarks' => $remarks,
                ':name' => $materialName
            ]);
            $updated++;
        } else {
            // Insert
            $insertStmt = $pdo->prepare("
                INSERT INTO materials (type, material_name, shop_stock, ph_stock, total_stock, total_cost, pieces_per_pack, unit_cost, remarks)
                VALUES (:type, :name, :shop, :ph, :total, :total_cost, :pieces, :unit_cost, :remarks)
            ");
            $insertStmt->execute([
                ':type' => $type,
                ':name' => $materialName,
                ':shop' => $shopStock,
                ':ph' => $phStock,
                ':total' => $totalStock,
                ':total_cost' => $totalCost,
                ':pieces' => $piecesPerPack,
                ':unit_cost' => $unitCost,
                ':remarks' => $remarks
            ]);
            $imported++;
        }
    }

    $pdo->commit();

    $response['success'] = true;
    $response['message'] = "Imported: $imported new, $updated updated";
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['message'] = $e->getMessage();
}

// Clear any output and send JSON
if (ob_get_level()) ob_end_clean();
echo json_encode($response);
exit;
