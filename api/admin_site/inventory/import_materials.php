<?php
/**
 * import_materials.php — Materials Inventory Import Endpoint
 *
 * Consumes the unified enterprise import facade (ImportManager).
 * Supports both CSV (.csv) and Excel (.xlsx) file uploads.
 * Zero external dependencies — works natively on shared hosting (InfinityFree).
 *
 * Response JSON:
 *   { success: bool, message: string, details?: { imported, updated, skipped, errors[] } }
 */

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// Load ImportManager & dependencies
$importManagerCandidates = [
    __DIR__ . '/../shared/import_manager.php',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/api/admin_site/shared/import_manager.php',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/Anything_Inside_Website/api/admin_site/shared/import_manager.php',
];

$importManagerLoaded = false;
foreach ($importManagerCandidates as $path) {
    if (!empty($path) && file_exists($path)) {
        require_once $path;
        $importManagerLoaded = true;
        break;
    }
}

if (!$importManagerLoaded) {
    if (ob_get_length()) ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Server Error: The core import component (import_manager.php) is missing.',
    ]);
    exit;
}

use AnythingInside\Import\ImportManager;
use AnythingInside\Import\ImportException;
use AnythingInside\Import\CsvParser;

// Fatal error safety net
register_shutdown_function(function (): void {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Fatal server error: ' . $error['message'] . ' in ' . basename($error['file']) . ' on line ' . $error['line'],
        ]);
    }
});

// Load database configuration
$configCandidates = [
    __DIR__ . '/../../../connect/config.php',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/connect/config.php',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/Anything_Inside_Website/connect/config.php',
];
foreach ($configCandidates as $configPath) {
    if (!empty($configPath) && file_exists($configPath)) {
        require_once $configPath;
        break;
    }
}

// Column alias dictionary for materials inventory
const MATERIAL_ALIAS_MAP = [
    'type'            => ['type', 'category', 'group'],
    'material_name'   => ['materials', 'material', 'material name', 'item', 'item name', 'name'],
    'shop_stock'      => ['shop', 'shop stock', 'shop qty', 'shop location', 'shop quantity'],
    'ph_stock'        => ['ph', 'ph stock', 'ph qty', 'ph location', 'ph quantity', 'p.h.'],
    'total_stock'     => ['total on hand', 'total stock', 'total', 'on hand', 'quantity', 'qty', 'total quantity'],
    'total_cost'      => ['total cost', 'total price', 'cost per pack', 'cost'],
    'pieces_per_pack' => ['quantity per pack', 'qty per pack', 'pieces per pack', 'pack qty', 'pcs per pack', 'pack size'],
    'unit_cost'       => ['unit cost', 'cost per unit', 'unit price', 'price per unit'],
    'remarks'         => ['remarks', 'notes', 'note', 'comment', 'comments'],
];

/** Strip currency symbols, commas, and spaces from numeric strings */
function mat_clean_number(string $value): string
{
    return str_replace([',', '₱', '$', ' '], '', trim($value)) ?: '0';
}

$response = ['success' => false, 'message' => ''];

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized. Please log in as an admin and try again.');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed.');
    }

    // 1. Identify upload field from request
    $fileKey = null;
    foreach (['csv_file', 'xlsx_file', 'excel_file', 'file'] as $key) {
        if (isset($_FILES[$key]) && ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $fileKey = $key;
            break;
        }
    }

    if ($fileKey === null) {
        throw new Exception('No file was selected. Please select a CSV (.csv) or Excel (.xlsx) file.');
    }

    // 2. Parse file using unified engine with smart multi-sheet tab resolution
    $cleanRows = ImportManager::parseUploadedFile(
        $_FILES[$fileKey],
        ImportManager::MAX_FILE_SIZE,
        MATERIAL_ALIAS_MAP,
        ['material', 'materials', 'inventory', 'stock']
    );

    // 3. Header mapping & validation
    $headerRow = $cleanRows[0];
    $mapping   = CsvParser::mapColumns($headerRow, MATERIAL_ALIAS_MAP);

    if (!isset($mapping['map']['material_name'])) {
        $fallback = CsvParser::firstIdentifierColumn($headerRow);
        if ($fallback !== null) {
            $mapping['map']['material_name'] = $fallback;
        } else {
            $found = implode(', ', array_filter(array_map('trim', $headerRow)));
            throw new Exception(
                'Could not find a "Material Name" column. Found headers: ' . ($found ?: 'none') . '. ' .
                'Make sure your file includes a column named "Materials", "Material Name", or "Item".'
            );
        }
    }

    $colMap   = $mapping['map'];
    $dataRows = array_slice($cleanRows, 1);

    if (empty($dataRows)) {
        throw new Exception('The file contains headers but no material data rows.');
    }

    // 4. Database Transaction Execution
    if (!function_exists('getDBConnection')) {
        throw new Exception('Database configuration error: getDBConnection not found.');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed.');
    }

    $pdo->exec('SET NAMES utf8mb4');
    $pdo->beginTransaction();

    $totalImported = 0;
    $totalUpdated  = 0;
    $totalSkipped  = 0;
    $errors        = [];

    foreach ($dataRows as $rowIndex => $row) {
        $get = function (string $field) use ($row, $colMap): string {
            if (!isset($colMap[$field])) {
                return '';
            }
            return trim((string) ($row[$colMap[$field]] ?? ''));
        };

        $materialName = $get('material_name');
        if ($materialName === '') {
            $totalSkipped++;
            continue;
        }

        try {
            $shopStock     = (int)   mat_clean_number($get('shop_stock')      ?: '0');
            $phStock       = (int)   mat_clean_number($get('ph_stock')        ?: '0');
            $totalStock    = (int)   mat_clean_number($get('total_stock')     ?: '0');
            $totalCost     = (float) mat_clean_number($get('total_cost')      ?: '0');
            $piecesPerPack = max(1, (int) mat_clean_number($get('pieces_per_pack') ?: '1'));
            $unitCost      = (float) mat_clean_number($get('unit_cost')       ?: '0');

            if ($totalStock <= 0) {
                $totalStock = $shopStock + $phStock;
            }
            if ($unitCost <= 0 && $totalCost > 0) {
                $unitCost = $totalCost / $piecesPerPack;
            }

            $type    = $get('type');
            $remarks = $get('remarks');

            // Upsert record
            $stmt = $pdo->prepare('SELECT id FROM materials WHERE material_name = :name LIMIT 1');
            $stmt->execute([':name' => $materialName]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $stmt = $pdo->prepare('
                    UPDATE materials
                    SET type            = :type,
                        shop_stock      = :shop,
                        ph_stock        = :ph,
                        total_stock     = :total,
                        total_cost      = :total_cost,
                        pieces_per_pack = :pieces,
                        unit_cost       = :unit_cost,
                        remarks         = :remarks
                    WHERE material_name = :name
                ');
                $stmt->execute([
                    ':type'       => $type,
                    ':shop'       => $shopStock,
                    ':ph'         => $phStock,
                    ':total'      => $totalStock,
                    ':total_cost' => $totalCost,
                    ':pieces'     => $piecesPerPack,
                    ':unit_cost'  => $unitCost,
                    ':remarks'    => $remarks,
                    ':name'       => $materialName,
                ]);
                $totalUpdated++;
            } else {
                $stmt = $pdo->prepare('
                    INSERT INTO materials
                        (type, material_name, shop_stock, ph_stock, total_stock,
                         total_cost, pieces_per_pack, unit_cost, remarks)
                    VALUES
                        (:type, :name, :shop, :ph, :total,
                         :total_cost, :pieces, :unit_cost, :remarks)
                ');
                $stmt->execute([
                    ':type'       => $type,
                    ':name'       => $materialName,
                    ':shop'       => $shopStock,
                    ':ph'         => $phStock,
                    ':total'      => $totalStock,
                    ':total_cost' => $totalCost,
                    ':pieces'     => $piecesPerPack,
                    ':unit_cost'  => $unitCost,
                    ':remarks'    => $remarks,
                ]);
                $totalImported++;
            }

        } catch (Exception $rowError) {
            $errors[] = 'Row ' . ($rowIndex + 2) . ' ("' . $materialName . '"): ' . $rowError->getMessage();
            $totalSkipped++;
        }
    }

    $pdo->commit();

    $origExt = strtolower(pathinfo($_FILES[$fileKey]['name'] ?? '', PATHINFO_EXTENSION));
    $formatLabel = ($origExt === 'xlsx') ? 'Excel (.xlsx)' : 'CSV (.csv)';

    $response = [
        'success' => true,
        'message' => "{$formatLabel} import completed — Added: {$totalImported}, Updated: {$totalUpdated}, Skipped: {$totalSkipped}",
        'details' => [
            'imported' => $totalImported,
            'updated'  => $totalUpdated,
            'skipped'  => $totalSkipped,
            'errors'   => $errors,
        ],
    ];

} catch (ImportException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response = [
        'success'           => false,
        'error_type'        => $e->errorType,
        'message'           => $e->getMessage(),
        'technical_message' => $e->technicalMessage,
    ];
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
    ];
}

if (ob_get_length()) {
    ob_end_clean();
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;