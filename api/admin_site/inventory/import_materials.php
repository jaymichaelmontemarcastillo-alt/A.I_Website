<?php
// api/admin_site/inventory/import_materials.php
header('Content-Type: application/json');
error_reporting(0);

require_once '../../../connect/config.php';
require_once '../../../vendor/autoload.php'; // Composer autoload

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        throw new Exception('Please select a valid file');
    }

    $file = $_FILES['csv_file']['tmp_name'];
    $fileType = $_FILES['csv_file']['type'];
    $fileExtension = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));

    // ============================================================
    // Handle Excel or CSV
    // ============================================================
    $rows = [];

    if ($fileExtension === 'xlsx' || $fileExtension === 'xls') {
        // ----- Load Excel file -----
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove empty trailing rows
        $rows = array_filter($rows, function ($row) {
            return !empty(array_filter($row));
        });
    } else {
        // ----- Load CSV file -----
        $content = file_get_contents($file);

        // Remove UTF-8 BOM if present
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }

        // Detect and convert encoding
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        $tempHandle = fopen('php://memory', 'r+');
        fwrite($tempHandle, $content);
        rewind($tempHandle);

        while (($data = fgetcsv($tempHandle, 10000, ',')) !== false) {
            $data = array_map(function ($field) {
                return trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $field));
            }, $data);
            if (!empty(array_filter($data))) {
                $rows[] = $data;
            }
        }
        fclose($tempHandle);
    }

    if (count($rows) < 2) {
        throw new Exception('File contains no data rows');
    }

    // Remove header row
    $headers = array_shift($rows);

    $pdo = getDBConnection();
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->beginTransaction();

    $imported = 0;
    $updated = 0;

    // Expected columns: Type, Materials, Shop, PH, Total On Hand, Cost per pack, Quantity per pack, Unit Cost
    foreach ($rows as $rowIndex => $row) {
        if (empty(array_filter($row))) continue;

        $type = trim($row[0] ?? '');
        $materialName = trim($row[1] ?? '');

        // If material name is empty, skip this row
        if (empty($materialName)) continue;

        // Handle numeric values - Excel may return floats
        $shopStock = (int) ($row[2] ?? 0);
        $phStock = (int) ($row[3] ?? 0);
        $totalStock = (int) ($row[4] ?? 0);

        // If total stock is empty or 0, calculate from shop + ph
        if ($totalStock <= 0) {
            $totalStock = $shopStock + $phStock;
        }

        $totalCost = (float) ($row[5] ?? 0);
        $piecesPerPack = (int) ($row[6] ?? 1);

        // If pieces per pack is 0, set to 1
        if ($piecesPerPack <= 0) $piecesPerPack = 1;

        $unitCost = (float) ($row[7] ?? 0);

        // If unit cost is empty, calculate from total cost / pieces per pack
        if ($unitCost <= 0 && $totalCost > 0) {
            $unitCost = $totalCost / $piecesPerPack;
        }

        $remarks = trim($row[8] ?? '');

        // Force UTF-8 encoding
        $materialName = mb_convert_encoding($materialName, 'UTF-8', 'UTF-8');
        $type = mb_convert_encoding($type, 'UTF-8', 'UTF-8');
        $remarks = mb_convert_encoding($remarks, 'UTF-8', 'UTF-8');

        // Check if material exists
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

if (ob_get_level()) ob_end_clean();
echo json_encode($response);
exit;
