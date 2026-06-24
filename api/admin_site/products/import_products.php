<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $pdo = getDBConnection();

    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Please upload a valid Excel file');
    }

    $file = $_FILES['excel_file']['tmp_name'];
    $extension = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ['xlsx', 'xls'])) {
        throw new Exception('Only .xlsx and .xls files are allowed');
    }

    // Load spreadsheet
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows) || count($rows) < 2) {
        throw new Exception('File is empty or has no data rows');
    }

    // Get headers from first row
    $headers = array_map('trim', $rows[0]);

    // Debug: Log headers to see what we're getting
    error_log("Import Headers: " . print_r($headers, true));

    // Check if this is the Product List sheet (has "Type" and "Product" columns)
    $hasType = in_array('Type', $headers);
    $hasProduct = in_array('Product', $headers);
    $hasMaterials = in_array('Materials', $headers);

    // If it has "Type" and "Product", it's the Product List sheet
    if ($hasType && $hasProduct) {
        $colMap = [
            'type' => array_search('Type', $headers),
            'product' => array_search('Product', $headers),
            'materials' => $hasMaterials ? array_search('Materials', $headers) : null
        ];
    }
    // Check for alternative headers
    else if (in_array('Product Type', $headers) && in_array('Product Name', $headers)) {
        $colMap = [
            'type' => array_search('Product Type', $headers),
            'product' => array_search('Product Name', $headers),
            'materials' => in_array('Materials Used', $headers) ? array_search('Materials Used', $headers) : null
        ];
    } else {
        // Try to find any columns that might contain product data
        $typeCol = null;
        $productCol = null;
        $materialsCol = null;

        foreach ($headers as $index => $header) {
            $headerLower = strtolower($header);
            if (strpos($headerLower, 'type') !== false && $typeCol === null) {
                $typeCol = $index;
            }
            if (strpos($headerLower, 'product') !== false && $productCol === null) {
                $productCol = $index;
            }
            if (strpos($headerLower, 'material') !== false && $materialsCol === null) {
                $materialsCol = $index;
            }
        }

        if ($productCol === null) {
            throw new Exception('Could not find "Product" or "Product Name" column in your Excel file. Found headers: ' . implode(', ', $headers));
        }

        $colMap = [
            'type' => $typeCol,
            'product' => $productCol,
            'materials' => $materialsCol
        ];
    }

    $pdo->beginTransaction();

    $imported = 0;
    $errors = [];
    $skipped = 0;

    // Process each row (skip header)
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];

        // Skip empty rows
        if (empty(array_filter($row))) continue;

        try {
            // Get values from columns
            $productType = $colMap['type'] !== null ? trim($row[$colMap['type']] ?? '') : '';
            $productName = trim($row[$colMap['product']] ?? '');
            $materialsStr = $colMap['materials'] !== null ? trim($row[$colMap['materials']] ?? '') : '';

            if (empty($productName)) {
                $errors[] = "Row " . ($i + 1) . ": Product name is required";
                continue;
            }

            // Generate SKU from product name if not provided
            $sku = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 10)) . '-' . rand(100, 999);

            // Get or create product type
            $typeId = null;
            if (!empty($productType)) {
                $stmt = $pdo->prepare("SELECT id FROM product_types WHERE name = ?");
                $stmt->execute([$productType]);
                $type = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($type) {
                    $typeId = $type['id'];
                } else {
                    // Create new product type with slug
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $productType)));
                    $stmt = $pdo->prepare("INSERT INTO product_types (name, slug, status) VALUES (?, ?, 'active')");
                    $stmt->execute([$productType, $slug]);
                    $typeId = $pdo->lastInsertId();
                }
            }

            // Check if product already exists by name
            $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
            $stmt->execute([$productName]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get or create category (default to first active category if none exists)
            $categoryId = null;
            $catStmt = $pdo->query("SELECT id FROM categories WHERE status = 'active' LIMIT 1");
            $category = $catStmt->fetch(PDO::FETCH_ASSOC);
            if ($category) {
                $categoryId = $category['id'];
            }

            if ($existing) {
                // Update existing product
                $stmt = $pdo->prepare("
                    UPDATE products 
                    SET name = ?, sku = ?, product_type_id = ?, category_id = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $productName,
                    $sku,
                    $typeId,
                    $categoryId,
                    $existing['id']
                ]);
                $productId = $existing['id'];
                $skipped++;
            } else {
                // Insert new product with default values
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, sku, product_type_id, category_id, price, stock, unit, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $productName,
                    $sku,
                    $typeId,
                    $categoryId,
                    0, // default price
                    0, // default stock
                    'piece', // default unit
                    'active'
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
                        // Create new material
                        $stmt = $pdo->prepare("INSERT INTO materials (material_name, type, unit_cost) VALUES (?, 'imported', 0)");
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

    echo json_encode([
        'success' => true,
        'message' => "Import completed!",
        'details' => [
            'imported' => $imported,
            'updated' => $skipped,
            'errors' => $errors
        ]
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
