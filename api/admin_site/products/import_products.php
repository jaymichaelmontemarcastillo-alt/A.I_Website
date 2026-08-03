<?php
// import_products.php - Supports both CSV and Excel files
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in output

header('Content-Type: application/json');

// ------------------------------------------------------------------
// Structured error handling
// ------------------------------------------------------------------
// ImportException carries a "type" so the frontend can show a clear,
// user-friendly modal instead of raw technical text. The real technical
// detail is still logged server-side (error_log) and included in the
// JSON as `technical_message` for developer debugging in the browser
// console only - the UI never displays it directly to the end user.
class ImportException extends Exception
{
    public $type;

    public function __construct($message, $type = 'general')
    {
        parent::__construct($message);
        $this->type = $type;
    }
}

// User-facing message shown per error type. Kept short, non-technical,
// and actionable so the person knows what to do next.
$friendlyMessages = [
    'upload'          => "We couldn't upload your file. Please check your connection and try again.",
    'invalid_type'    => "This file type isn't supported. Please upload an Excel file (.xlsx or .xls).",
    'file_read'       => "We couldn't read this file. Please make sure it isn't corrupted or password-protected, then try again.",
    'empty_file'      => "This file doesn't have any product data in it. Please add your products and try again.",
    'column_mismatch' => 'This Excel file doesn\'t match the expected format. Please make sure it has a column named "Product Name" (or "Product", "Name", or "Item"), then try again.',
    'database'        => "A technical problem occurred while saving your data. Please try again in a moment.",
    'general'         => "Something went wrong during the import. Please try again.",
];

try {
    // Include config
    require_once __DIR__ . '/../../../connect/config.php';

    if (!function_exists('getDBConnection')) {
        throw new ImportException('getDBConnection function not found', 'database');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new ImportException('Database connection failed', 'database');
    }

    // Ensure the products table has the extra_data column for storing
    // unrecognized Excel columns. Only adds it if it doesn't already exist.
    $colCheck = $pdo->query("SHOW COLUMNS FROM products LIKE 'extra_data'");
    if ($colCheck->rowCount() === 0) {
        $pdo->exec("ALTER TABLE products ADD COLUMN extra_data JSON NULL");
    }

    // Check if file was uploaded
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
        ];
        $errorCode = $_FILES['excel_file']['error'] ?? UPLOAD_ERR_NO_FILE;
        $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error';
        throw new ImportException('Upload error: ' . $errorMsg, 'upload');
    }

    $file = $_FILES['excel_file']['tmp_name'];
    $originalName = $_FILES['excel_file']['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
        throw new ImportException('Only .xlsx, .xls, and .csv files are allowed. Got: ' . $extension, 'invalid_type');
    }

    if ($extension === 'csv' && !is_uploaded_file($file)) {
        throw new ImportException('Uploaded file could not be verified.', 'upload');
    }

    // Get data from file (supports both CSV and Excel)
    $rows = [];

    if ($extension === 'csv') {
        // Handle CSV file
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
    } else {
        // Handle Excel file - try to load PhpSpreadsheet
        try {
            // Check if vendor/autoload.php exists
            $vendorPath = __DIR__ . '/../../../vendor/autoload.php';
            if (!file_exists($vendorPath)) {
                throw new ImportException('vendor/autoload.php not found. Please run: composer require phpoffice/phpspreadsheet', 'file_read');
            }

            require_once $vendorPath;

            // Use fully-qualified class names instead of top-level "use" imports,
            // since "use" statements are compile-time only and cannot legally
            // appear inside a try block / conditional branch (causes a parse error).
            $reader = null;

            if ($extension === 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } elseif ($extension === 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            if ($reader) {
                $spreadsheet = $reader->load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
            } else {
                // Fallback to IOFactory
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
            }
        } catch (ImportException $e) {
            throw $e;
        } catch (Throwable $e) {
            // If PhpSpreadsheet fails (corrupt file, wrong format, etc.),
            // try to read it as CSV as a last-resort fallback.
            $rows = [];
            if (($handle = fopen($file, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
            if (empty($rows)) {
                error_log('Import file_read failure: ' . $e->getMessage());
                throw new ImportException('Failed to read file: ' . $e->getMessage(), 'file_read');
            }
        }
    }

    if (empty($rows) || count($rows) < 2) {
        throw new ImportException('File is empty or has no data rows. Found ' . count($rows) . ' rows.', 'empty_file');
    }

    // ------------------------------------------------------------------
    // Dynamic header-based column mapping
    // ------------------------------------------------------------------
    // Headers are read from row 0 and matched (case-insensitive, trimmed)
    // against a set of known aliases. This lets the Excel file have its
    // columns in ANY order - we map by header name, not by position.
    // Any header that isn't recognized is preserved (not discarded) and
    // its values are stored per-row in the `extra_data` JSON column.

    // Keep the raw header row untouched (no filtering/reindexing) so that
    // column indexes stay perfectly aligned with each data row's indexes.
    $rawHeaderRow = $rows[0];
    $headers = [];
    foreach ($rawHeaderRow as $idx => $h) {
        $headers[$idx] = ($h === null) ? '' : trim($h);
    }

    // Normalizes a header for comparison: trim, collapse inner whitespace,
    // lowercase. E.g. " Product   Code " -> "product code"
    function normalizeHeaderName($h)
    {
        $h = trim((string) $h);
        $h = preg_replace('/\s+/', ' ', $h);
        return strtolower($h);
    }

    // Canonical field => accepted header aliases (already normalized/lowercase).
    $aliasMap = [
        'product_code' => ['product code', 'code', 'sku', 'item code'],
        'product_name' => ['product name', 'name', 'item name', 'product', 'item'],
        'price'        => ['price', 'selling price', 'unit price'],
        'stock'        => ['stock', 'quantity', 'qty'],
        'category'     => ['category', 'type', 'product type', 'product category'],
        'brand'        => ['brand'],
        'description'  => ['description', 'desc', 'details'],
        // Kept for backward compatibility with the existing materials feature.
        'materials'    => ['materials', 'material', 'components'],
    ];

    // Canonical fields that don't have a dedicated products table column.
    // Their values are still recognized (not treated as "unknown"), but are
    // routed into extra_data using a clean, human-readable key.
    $noColumnFields = [
        'brand' => 'Brand',
    ];

    $colMap = [];       // canonical_field => column index
    $unknownCols = [];  // column index => original header text

    foreach ($headers as $index => $header) {
        if ($header === '') {
            continue; // blank header cell, skip entirely
        }

        $normalized = normalizeHeaderName($header);
        $matchedField = null;

        foreach ($aliasMap as $field => $aliases) {
            if (isset($colMap[$field])) {
                continue; // already mapped to an earlier column
            }
            if (in_array($normalized, $aliases, true)) {
                $matchedField = $field;
                break;
            }
        }

        if ($matchedField !== null) {
            $colMap[$matchedField] = $index;
        } else {
            $unknownCols[$index] = $header;
        }
    }

    // Validate required columns
    if (!isset($colMap['product_name'])) {
        throw new ImportException(
            'Could not find a "Product Name" column. Found headers: ' . implode(', ', array_filter($headers)),
            'column_mismatch'
        );
    }

    $pdo->beginTransaction();

    $imported = 0;
    $updated = 0;
    $errors = [];
    $skipped = 0;

    // Get or create default product type
    $defaultTypeId = null;
    $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = 'Uncategorized' LIMIT 1");
    $stmt->execute();
    $defaultType = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($defaultType) {
        $defaultTypeId = $defaultType['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES ('Uncategorized', 'uncategorized', 'active')");
        $stmt->execute();
        $defaultTypeId = $pdo->lastInsertId();
    }

    // Helper: clean a numeric string (strips currency symbols, commas,
    // stray spaces) before casting, so "₱1,250.00" or " 20 " convert correctly.
    function cleanNumeric($val)
    {
        $val = trim((string) $val);
        $val = str_replace([',', '₱', '$', ' '], '', $val);
        return $val === '' ? 0 : $val;
    }

    // Process each row
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];

        // Skip completely empty rows
        $rowData = array_map(function ($v) {
            return trim((string) ($v ?? ''));
        }, $row);
        if (empty(array_filter($rowData, function ($val) {
            return $val !== '' && $val !== null;
        }))) {
            $skipped++;
            continue;
        }

        try {
            // Get values from columns using the dynamic header map
            $productName = isset($colMap['product_name']) ? trim($row[$colMap['product_name']] ?? '') : '';
            if (empty($productName)) {
                $errors[] = "Row " . ($i + 1) . ": Skipped - Product name is required";
                $skipped++;
                continue;
            }

            $productType = isset($colMap['category']) ? trim($row[$colMap['category']] ?? '') : '';
            $sku = isset($colMap['product_code']) ? trim($row[$colMap['product_code']] ?? '') : '';
            $price = isset($colMap['price']) ? floatval(cleanNumeric($row[$colMap['price']] ?? 0)) : 0;
            $stock = isset($colMap['stock']) ? intval(cleanNumeric($row[$colMap['stock']] ?? 0)) : 0;
            $materialsStr = isset($colMap['materials']) ? trim($row[$colMap['materials']] ?? '') : '';
            $description = isset($colMap['description']) ? trim($row[$colMap['description']] ?? '') : '';

            // Build extra_data JSON from recognized-but-columnless fields
            // (e.g. Brand) plus any header that wasn't recognized at all.
            $extraData = [];
            foreach ($noColumnFields as $field => $label) {
                if (isset($colMap[$field])) {
                    $val = trim($row[$colMap[$field]] ?? '');
                    if ($val !== '') {
                        $extraData[$label] = $val;
                    }
                }
            }
            foreach ($unknownCols as $colIndex => $originalHeader) {
                $val = isset($row[$colIndex]) ? trim((string) $row[$colIndex]) : '';
                if ($val !== '') {
                    $extraData[$originalHeader] = $val;
                }
            }
            $extraDataJson = !empty($extraData) ? json_encode($extraData, JSON_UNESCAPED_UNICODE) : null;

            // Generate SKU if not provided
            if (empty($sku)) {
                $sku = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 8)) . '-' . rand(100, 999);
            }

            // Get or create product type
            $typeId = $defaultTypeId;
            if (!empty($productType)) {
                $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = ? OR slug = ?");
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $productType)));
                $stmt->execute([$productType, $slug]);
                $type = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($type) {
                    $typeId = $type['id'];
                } else {
                    $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES (?, ?, 'active')");
                    $stmt->execute([$productType, $slug]);
                    $typeId = $pdo->lastInsertId();
                }
            }

            // Check if product already exists by SKU or Name
            $existing = null;
            if (!empty($sku)) {
                $stmt = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
                $stmt->execute([$sku]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if (!$existing) {
                $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
                $stmt->execute([$productName]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $defaultImage = 'default-product.png';
            $defaultUnit = 'piece';
            $defaultStatus = 'active';

            if ($existing) {
                // Update existing product
                $stmt = $pdo->prepare("
                    UPDATE products 
                    SET sku = ?, 
                        product_type_id = ?, 
                        price = ?,
                        stock = ?,
                        description = ?,
                        extra_data = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $sku,
                    $typeId,
                    $price,
                    $stock,
                    $description,
                    $extraDataJson,
                    $existing['id']
                ]);
                $productId = $existing['id'];
                $updated++;
            } else {
                // Insert new product
                $stmt = $pdo->prepare("
                    INSERT INTO products (
                        name, 
                        sku, 
                        product_type_id, 
                        price, 
                        stock, 
                        description,
                        unit, 
                        status, 
                        image,
                        material_type,
                        extra_data,
                        created_at,
                        updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'assembled_product', ?, NOW(), NOW())
                ");
                $stmt->execute([
                    $productName,
                    $sku,
                    $typeId,
                    $price,
                    $stock,
                    $description,
                    $defaultUnit,
                    $defaultStatus,
                    $defaultImage,
                    $extraDataJson
                ]);
                $productId = $pdo->lastInsertId();
                $imported++;
            }

            // Process materials if available
            if (!empty($materialsStr)) {
                // Parse materials: "Material Name (quantity); Material Name (quantity)"
                $materialsList = explode(';', $materialsStr);

                // Delete existing materials
                $pdo->prepare("DELETE FROM product_materials WHERE product_id = ?")->execute([$productId]);

                foreach ($materialsList as $materialStr) {
                    $materialStr = trim($materialStr);
                    if (empty($materialStr)) continue;

                    // Extract material name and quantity
                    if (preg_match('/^(.+?)\s*\(([\d.]+)\s*([^)]*)\)$/', $materialStr, $matches)) {
                        $materialName = trim($matches[1]);
                        $quantity = floatval($matches[2]);
                    } else {
                        $materialName = $materialStr;
                        $quantity = 1;
                    }

                    // Find or create material
                    $stmt = $pdo->prepare("SELECT id FROM materials WHERE material_name = ?");
                    $stmt->execute([$materialName]);
                    $material = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($material) {
                        $materialId = $material['id'];
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO materials (material_name, type, unit_cost, total_stock) VALUES (?, 'imported', 0, 0)");
                        $stmt->execute([$materialName]);
                        $materialId = $pdo->lastInsertId();
                    }

                    // Link material to product
                    $stmt = $pdo->prepare("INSERT INTO product_materials (product_id, material_id, quantity) VALUES (?, ?, ?)");
                    $stmt->execute([$productId, $materialId, $quantity]);
                }
            }
        } catch (Exception $e) {
            // Row-level errors never abort the whole import - record the
            // reason and continue with the next row.
            $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
            $skipped++;
        }
    }

    $pdo->commit();

    $summary = "Import completed! Added: $imported, Updated: $updated, Skipped: $skipped, Errors: " . count($errors);

    echo json_encode([
        'success' => true,
        'message' => $summary,
        'details' => [
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors
        ]
    ]);
} catch (ImportException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Import failed [' . $e->type . ']: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error_type' => $e->type,
        'message' => $friendlyMessages[$e->type] ?? $friendlyMessages['general'],
        'technical_message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Import database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error_type' => 'database',
        'message' => $friendlyMessages['database'],
        'technical_message' => $e->getMessage()
    ]);
} catch (Throwable $e) {
    // Catches anything unexpected (PHP fatal-level errors, type errors, etc.)
    // so the response is always valid JSON instead of a raw PHP error page
    // that would break JSON.parse() on the frontend.
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Import unexpected error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error_type' => 'general',
        'message' => $friendlyMessages['general'],
        'technical_message' => $e->getMessage()
    ]);
}
