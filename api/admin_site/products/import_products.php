<?php
/**
 * import_products.php — Products Inventory Import Endpoint
 *
 * Consumes the unified enterprise import facade (ImportManager).
 * Supports both CSV (.csv) and Excel (.xlsx) file uploads.
 * Zero external dependencies — works natively on shared hosting (InfinityFree).
 *
 * Response JSON:
 *   { success: bool, message: string, error_type?: string, technical_message?: string,
 *     details?: { imported, updated, skipped, errors[] } }
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
        'success'    => false,
        'error_type' => 'server_error',
        'message'    => 'Server Error: The core import component (import_manager.php) is missing.',
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
            'success'           => false,
            'error_type'        => 'general',
            'message'           => 'Fatal PHP error: ' . $error['message'],
            'technical_message' => 'Fatal error in ' . basename($error['file']) . ' line ' . $error['line'],
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

// User friendly fallback messages
const PROD_FRIENDLY_MESSAGES = [
    'upload_error'    => "We couldn't upload your file. Please check your connection and try again.",
    'invalid_type'    => 'This file type is not supported. Please upload a CSV (.csv) or Excel (.xlsx) file.',
    'file_read'       => "We couldn't read this file. Make sure it isn't corrupted and try again.",
    'empty_file'      => 'This file has no product data rows. Please add your products and try again.',
    'column_mismatch' => 'No "Product Name" column was found. Make sure your file has a column called "Product Name", "Product", "Name", or "Item".',
    'database'        => 'A database error occurred while saving products. Please try again.',
    'general'         => 'Something went wrong during the product import. Please try again.',
];

// Product column alias dictionary
const PRODUCT_ALIAS_MAP = [
    'product_code' => ['product code', 'code', 'sku', 'item code', 'product sku'],
    'product_name' => ['product name', 'name', 'item name', 'product', 'item', 'product title'],
    'price'        => ['price', 'selling price', 'unit price', 'retail price'],
    'stock'        => ['stock', 'quantity', 'qty', 'on hand', 'inventory'],
    'category'     => ['category', 'type', 'product type', 'product category', 'group'],
    'brand'        => ['brand', 'brand name', 'manufacturer'],
    'description'  => ['description', 'desc', 'details', 'product description'],
    'materials'    => ['materials', 'material', 'components', 'ingredients'],
];

const PROD_EXTRA_FIELDS = [
    'brand' => 'Brand',
];

function prod_clean_numeric(string $value): string
{
    return str_replace([',', '₱', '$', ' '], '', trim($value)) ?: '0';
}

function prod_generate_sku(string $productName): string
{
    $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 8));
    return ($base ?: 'PRD') . '-' . rand(100, 999);
}

function prod_resolve_type(PDO $pdo, string $typeName, int $defaultTypeId): int
{
    if ($typeName === '') {
        return $defaultTypeId;
    }

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $typeName)));
    $stmt = $pdo->prepare('SELECT id FROM product_types WHERE name = ? OR slug = ? LIMIT 1');
    $stmt->execute([$typeName, $slug]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return (int) $row['id'];
    }

    $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES (?, ?, 'active')");
    $stmt->execute([$typeName, $slug]);
    return (int) $pdo->lastInsertId();
}

function prod_upsert(PDO $pdo, array $data): string
{
    $existing = null;

    if ($data['sku'] !== '') {
        $stmt = $pdo->prepare('SELECT id FROM products WHERE sku = ? LIMIT 1');
        $stmt->execute([$data['sku']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$existing) {
        $stmt = $pdo->prepare('SELECT id FROM products WHERE name = ? LIMIT 1');
        $stmt->execute([$data['product_name']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($existing) {
        $stmt = $pdo->prepare('
            UPDATE products
            SET sku             = :sku,
                product_type_id = :type_id,
                price           = :price,
                stock           = :stock,
                description     = :description,
                extra_data      = :extra_data,
                updated_at      = NOW()
            WHERE id = :id
        ');
        $stmt->execute([
            ':sku'         => $data['sku'],
            ':type_id'     => $data['type_id'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':description' => $data['description'],
            ':extra_data'  => $data['extra_data'],
            ':id'          => $existing['id'],
        ]);
        return 'update';
    }

    $stmt = $pdo->prepare('
        INSERT INTO products
            (name, sku, product_type_id, price, stock, description,
             unit, status, image, material_type, extra_data, created_at, updated_at)
        VALUES
            (:name, :sku, :type_id, :price, :stock, :description,
             :unit, :status, :image, :material_type, :extra_data, NOW(), NOW())
    ');
    $stmt->execute([
        ':name'          => $data['product_name'],
        ':sku'           => $data['sku'],
        ':type_id'       => $data['type_id'],
        ':price'         => $data['price'],
        ':stock'         => $data['stock'],
        ':description'   => $data['description'],
        ':unit'          => 'piece',
        ':status'        => 'active',
        ':image'         => 'default-product.png',
        ':material_type' => 'assembled_product',
        ':extra_data'    => $data['extra_data'],
    ]);
    return 'insert';
}

function prod_link_materials(PDO $pdo, int $productId, string $materialsStr): void
{
    $pdo->prepare('DELETE FROM product_materials WHERE product_id = ?')->execute([$productId]);

    foreach (explode(';', $materialsStr) as $entry) {
        $entry = trim($entry);
        if ($entry === '') continue;

        if (preg_match('/^(.+?)\s*\(([\d.]+)\s*[^)]*\)$/', $entry, $m)) {
            $materialName = trim($m[1]);
            $quantity     = (float) $m[2];
        } else {
            $materialName = $entry;
            $quantity     = 1.0;
        }

        $stmt = $pdo->prepare('SELECT id FROM materials WHERE material_name = ? LIMIT 1');
        $stmt->execute([$materialName]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$material) {
            $stmt = $pdo->prepare(
                "INSERT INTO materials (material_name, type, unit_cost, total_stock)
                 VALUES (?, 'imported', 0, 0)"
            );
            $stmt->execute([$materialName]);
            $materialId = (int) $pdo->lastInsertId();
        } else {
            $materialId = (int) $material['id'];
        }

        $stmt = $pdo->prepare(
            'INSERT INTO product_materials (product_id, material_id, quantity) VALUES (?, ?, ?)'
        );
        $stmt->execute([$productId, $materialId, $quantity]);
    }
}

// Main execution
try {
    if (!function_exists('getDBConnection')) {
        throw new ImportException('Database configuration is missing.', 'database');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new ImportException('Database connection failed.', 'database');
    }

    // Ensure extra_data column exists
    try {
        $check = $pdo->query("SHOW COLUMNS FROM products LIKE 'extra_data'");
        if ($check && $check->rowCount() === 0) {
            $pdo->exec('ALTER TABLE products ADD COLUMN extra_data JSON NULL');
        }
    } catch (Throwable $ignored) {}

    // 1. Identify upload field from request
    $fileKey = null;
    foreach (['csv_file', 'xlsx_file', 'excel_file', 'file', 'import_file'] as $key) {
        if (isset($_FILES[$key]) && ($_FILES[$key]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $fileKey = $key;
            break;
        }
    }

    if ($fileKey === null) {
        throw new ImportException('No product file was selected.', 'upload_error');
    }

    // 2. Parse file using unified engine with smart multi-sheet tab resolution
    $cleanRows = ImportManager::parseUploadedFile(
        $_FILES[$fileKey],
        ImportManager::MAX_FILE_SIZE,
        PRODUCT_ALIAS_MAP,
        ['product', 'products', 'item', 'items']
    );

    // 3. Map headers
    $headerRow = $cleanRows[0];
    $mapping   = CsvParser::mapColumns($headerRow, PRODUCT_ALIAS_MAP);

    if (!isset($mapping['map']['product_name'])) {
        $fallback = CsvParser::firstIdentifierColumn($headerRow);
        if ($fallback !== null) {
            $mapping['map']['product_name'] = $fallback;
        } else {
            throw new ImportException(
                'No "Product Name" column was found in the file headers.',
                'column_mismatch'
            );
        }
    }

    $colMap    = $mapping['map'];
    $extraCols = $mapping['extra'];
    $dataRows  = array_slice($cleanRows, 1);

    if (empty($dataRows)) {
        throw new ImportException('The file contains headers but no product rows.', 'empty_file');
    }

    // 4. Ensure default product type exists
    $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = 'Uncategorized' LIMIT 1");
    $stmt->execute();
    $defaultType = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($defaultType) {
        $defaultTypeId = (int) $defaultType['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES ('Uncategorized', 'uncategorized', 'active')");
        $stmt->execute();
        $defaultTypeId = (int) $pdo->lastInsertId();
    }

    // 5. Database transaction
    $pdo->beginTransaction();

    $totalImported = 0;
    $totalUpdated  = 0;
    $totalSkipped  = 0;
    $errors        = [];

    foreach ($dataRows as $rowIndex => $row) {
        $get = function (string $field) use ($row, $colMap): string {
            if (!isset($colMap[$field])) return '';
            return trim((string) ($row[$colMap[$field]] ?? ''));
        };

        $productName = $get('product_name');
        if ($productName === '') {
            $totalSkipped++;
            continue;
        }

        try {
            $sku          = $get('product_code');
            $price        = (float) prod_clean_numeric($get('price') ?: '0');
            $stock        = (int)   prod_clean_numeric($get('stock') ?: '0');
            $description  = $get('description');
            $typeName     = $get('category');
            $materialsStr = $get('materials');

            if ($sku === '') {
                $sku = prod_generate_sku($productName);
            }

            $typeId = prod_resolve_type($pdo, $typeName, $defaultTypeId);

            $extraData = [];
            foreach (PROD_EXTRA_FIELDS as $field => $label) {
                if (isset($colMap[$field])) {
                    $val = trim((string) ($row[$colMap[$field]] ?? ''));
                    if ($val !== '') $extraData[$label] = $val;
                }
            }

            foreach ($extraCols as $colIndex => $originalHeader) {
                $val = trim((string) ($row[$colIndex] ?? ''));
                if ($val !== '') $extraData[$originalHeader] = $val;
            }

            $extraDataJson = !empty($extraData) ? json_encode($extraData, JSON_UNESCAPED_UNICODE) : null;

            $action = prod_upsert($pdo, [
                'product_name' => $productName,
                'sku'          => $sku,
                'type_id'      => $typeId,
                'price'        => $price,
                'stock'        => $stock,
                'description'  => $description,
                'extra_data'   => $extraDataJson,
            ]);

            if ($action === 'insert') {
                $productId = (int) $pdo->lastInsertId();
                $totalImported++;
            } else {
                $stmt2 = $pdo->prepare('SELECT id FROM products WHERE name = ? LIMIT 1');
                $stmt2->execute([$productName]);
                $row2      = $stmt2->fetch(PDO::FETCH_ASSOC);
                $productId = $row2 ? (int) $row2['id'] : 0;
                $totalUpdated++;
            }

            if ($materialsStr !== '' && $productId > 0) {
                prod_link_materials($pdo, $productId, $materialsStr);
            }

        } catch (Exception $rowError) {
            $errors[] = 'Row ' . ($rowIndex + 2) . ' ("' . $productName . '"): ' . $rowError->getMessage();
            $totalSkipped++;
        }
    }

    $pdo->commit();

    $origExt = strtolower(pathinfo($_FILES[$fileKey]['name'] ?? '', PATHINFO_EXTENSION));
    $formatLabel = ($origExt === 'xlsx') ? 'Excel (.xlsx)' : 'CSV (.csv)';

    if (ob_get_length()) ob_clean();
    echo json_encode([
        'success' => true,
        'message' => "{$formatLabel} import completed — Added: {$totalImported}, Updated: {$totalUpdated}, Skipped: {$totalSkipped}",
        'details' => [
            'imported' => $totalImported,
            'updated'  => $totalUpdated,
            'skipped'  => $totalSkipped,
            'errors'   => $errors,
        ],
    ], JSON_UNESCAPED_UNICODE);

} catch (ImportException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    if (ob_get_length()) ob_clean();
    echo json_encode([
        'success'           => false,
        'error_type'        => $e->errorType,
        'message'           => PROD_FRIENDLY_MESSAGES[$e->errorType] ?? $e->getMessage(),
        'technical_message' => $e->technicalMessage,
    ]);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    if (ob_get_length()) ob_clean();
    echo json_encode([
        'success'           => false,
        'error_type'        => 'database',
        'message'           => PROD_FRIENDLY_MESSAGES['database'],
        'technical_message' => $e->getMessage(),
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    if (ob_get_length()) ob_clean();
    echo json_encode([
        'success'           => false,
        'error_type'        => 'general',
        'message'           => PROD_FRIENDLY_MESSAGES['general'],
        'technical_message' => $e->getMessage(),
    ]);
}