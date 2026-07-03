<?php
// import_products.php - Supports both CSV and Excel files
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in output

header('Content-Type: application/json');

try {
    // Include config
    require_once __DIR__ . '/../../../connect/config.php';

    if (!function_exists('getDBConnection')) {
        throw new Exception('getDBConnection function not found');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed');
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
        throw new Exception('Upload error: ' . $errorMsg);
    }

    $file = $_FILES['excel_file']['tmp_name'];
    $originalName = $_FILES['excel_file']['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
        throw new Exception('Only .xlsx, .xls, and .csv files are allowed. Got: ' . $extension);
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
                throw new Exception('vendor/autoload.php not found. Please run: composer require phpoffice/phpspreadsheet');
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
        } catch (Exception $e) {
            // If PhpSpreadsheet fails, try to read as CSV as fallback
            if (($handle = fopen($file, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            } else {
                throw new Exception('Failed to read file: ' . $e->getMessage());
            }
        }
    }

    if (empty($rows) || count($rows) < 2) {
        throw new Exception('File is empty or has no data rows. Found ' . count($rows) . ' rows.');
    }

    // Get headers from first row and clean them
    $headers = array_map('trim', $rows[0]);
    $headers = array_filter($headers, function ($h) {
        return !empty($h);
    });
    $headers = array_values($headers);

    // Find column indexes - support multiple header variations
    $colMap = [];

    // Map "Type" column
    $typeKeywords = ['type', 'product type', 'category', 'product category'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($typeKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['type'] = $index;
                break 2;
            }
        }
    }

    // Map "Product Name" column
    $productKeywords = ['product', 'product name', 'name', 'item', 'item name', 'materials'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($productKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['product'] = $index;
                break 2;
            }
        }
    }

    // Map "SKU" column
    $skuKeywords = ['sku', 'code', 'product code', 'item code'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($skuKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['sku'] = $index;
                break 2;
            }
        }
    }

    // Map "Price" column
    $priceKeywords = ['price', 'cost', 'unit cost', 'amount'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($priceKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['price'] = $index;
                break 2;
            }
        }
    }

    // Map "Stock" column
    $stockKeywords = ['stock', 'quantity', 'qty', 'inventory', 'total on hand', 'on hand'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($stockKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['stock'] = $index;
                break 2;
            }
        }
    }

    // Map "Materials" column (for product materials)
    $materialKeywords = ['materials', 'material', 'components'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($materialKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['materials'] = $index;
                break 2;
            }
        }
    }

    // Map "Description" column
    $descKeywords = ['description', 'desc', 'details'];
    foreach ($headers as $index => $header) {
        $headerLower = strtolower(trim($header));
        foreach ($descKeywords as $keyword) {
            if ($headerLower === $keyword || strpos($headerLower, $keyword) !== false) {
                $colMap['description'] = $index;
                break 2;
            }
        }
    }

    // Validate required columns
    if (!isset($colMap['product'])) {
        throw new Exception('Could not find "Product" or "Product Name" column. Found headers: ' . implode(', ', $headers));
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

    // Process each row
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];

        // Skip completely empty rows
        $rowData = array_map('trim', $row);
        if (empty(array_filter($rowData, function ($val) {
            return $val !== '' && $val !== null;
        }))) {
            continue;
        }

        try {
            // Get values from columns
            $productName = isset($colMap['product']) ? trim($row[$colMap['product']] ?? '') : '';
            if (empty($productName)) {
                $errors[] = "Row " . ($i + 1) . ": Product name is required";
                continue;
            }

            $productType = isset($colMap['type']) ? trim($row[$colMap['type']] ?? '') : '';
            $sku = isset($colMap['sku']) ? trim($row[$colMap['sku']] ?? '') : '';
            $price = isset($colMap['price']) ? floatval(trim($row[$colMap['price']] ?? 0)) : 0;
            $stock = isset($colMap['stock']) ? intval(trim($row[$colMap['stock']] ?? 0)) : 0;
            $materialsStr = isset($colMap['materials']) ? trim($row[$colMap['materials']] ?? '') : '';
            $description = isset($colMap['description']) ? trim($row[$colMap['description']] ?? '') : '';

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
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $sku,
                    $typeId,
                    $price,
                    $stock,
                    $description,
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
                        created_at,
                        updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'assembled_product', NOW(), NOW())
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
                    $defaultImage
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
            $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
        }
    }

    $pdo->commit();

    $summary = "Import completed! Added: $imported, Updated: $updated, Errors: " . count($errors);

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
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
