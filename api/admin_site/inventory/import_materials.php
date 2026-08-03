<?php
// api/admin_site/inventory/import_materials.php
header('Content-Type: application/json');
error_reporting(0);

require_once '../../../connect/config.php';
require_once '../../../vendor/autoload.php';

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
    $fileExtension = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));

    // Expected columns for validation
    $expectedHeaders = [
        'Type', 'Materials', 'Shop', 'PH', 'Total On Hand',
        'Total Cost', 'Quantity per pack', 'Unit Cost', 'Remarks'
    ];

    // Normalize header names for comparison
    $normalizeHeader = function($h) {
        return strtolower(trim($h));
    };

    // Check if headers match expected format
    $headersMatch = function($headers) use ($expectedHeaders, $normalizeHeader) {
        if (count($headers) < 5) return false; // Minimum required columns

        $normalizedHeaders = array_map($normalizeHeader, $headers);
        $normalizedExpected = array_map($normalizeHeader, $expectedHeaders);

        // Check if at least the first 5 columns match
        for ($i = 0; $i < 5; $i++) {
            if (!isset($normalizedHeaders[$i]) || !in_array($normalizedHeaders[$i], $normalizedExpected)) {
                return false;
            }
        }
        return true;
    };

    // ============================================================
    // Handle Excel or CSV
    // ============================================================
    $rows = [];
    $allSheets = [];
    $validSheets = [];

    if ($fileExtension === 'xlsx' || $fileExtension === 'xls') {
        // ----- Load Excel file and check all sheets -----
        $spreadsheet = IOFactory::load($file);
        $sheetNames = $spreadsheet->getSheetNames();

        foreach ($sheetNames as $idx => $sheetName) {
            $worksheet = $spreadsheet->getSheet($idx);
            $sheetRows = $worksheet->toArray();

            // Remove empty trailing rows
            $sheetRows = array_filter($sheetRows, function ($row) {
                return !empty(array_filter($row));
            });

            if (!empty($sheetRows)) {
                $headers = array_shift($sheetRows);

                // Check if this sheet has matching columns
                if ($headersMatch($headers)) {
                    $validSheets[] = $sheetName;
                    if (count($validSheets) === 1) {
                        // Store first valid sheet's data
                        $rows = $sheetRows;
                    }
                }
            }
        }

        // If multiple valid sheets found, return list for user selection
        if (count($validSheets) > 1) {
            $response['sheets'] = $validSheets;
            echo json_encode($response);
            exit;
        }

        if (empty($rows)) {
            throw new Exception('No sheets with matching columns found in Excel file');
        }
    } else {
        // ----- Load CSV file -----
        $content = file_get_contents($file);

        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }

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

        if (count($rows) < 2) {
            throw new Exception('File contains no data rows');
        }

        $headers = array_shift($rows);
        if (!$headersMatch($headers)) {
            throw new Exception('CSV columns do not match the required format. Expected: Type, Materials, Shop, PH, Total On Hand, Total Cost, Quantity per pack, Unit Cost, Remarks');
        }
    }

    $pdo = getDBConnection();
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->beginTransaction();

    $imported = 0;
    $updated = 0;

    // Process rows
    foreach ($rows as $rowIndex => $row) {
        if (empty(array_filter($row))) continue;

        $type = trim($row[0] ?? '');
        $materialName = trim($row[1] ?? '');

        if (empty($materialName)) continue;

        $shopStock = (int) ($row[2] ?? 0);
        $phStock = (int) ($row[3] ?? 0);
        $totalStock = (int) ($row[4] ?? 0);

        if ($totalStock <= 0) {
            $totalStock = $shopStock + $phStock;
        }

        $totalCost = (float) ($row[5] ?? 0);
        $piecesPerPack = (int) ($row[6] ?? 1);

        if ($piecesPerPack <= 0) $piecesPerPack = 1;

        $unitCost = (float) ($row[7] ?? 0);

        if ($unitCost <= 0 && $totalCost > 0) {
            $unitCost = $totalCost / $piecesPerPack;
        }

        $remarks = trim($row[8] ?? '');

        $materialName = mb_convert_encoding($materialName, 'UTF-8', 'UTF-8');
        $type = mb_convert_encoding($type, 'UTF-8', 'UTF-8');
        $remarks = mb_convert_encoding($remarks, 'UTF-8', 'UTF-8');

        $checkStmt = $pdo->prepare("SELECT id FROM materials WHERE material_name = :name");
        $checkStmt->execute([':name' => $materialName]);
        $existing = $checkStmt->fetch();

        if ($existing) {
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

