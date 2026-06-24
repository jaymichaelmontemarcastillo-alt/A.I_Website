<?php
// ============================================
// FULL ERROR REPORTING - SEE EVERYTHING
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');

$logFile = __DIR__ . '/debug_log.txt';
file_put_contents($logFile, "=== " . date('Y-m-d H:i:s') . " - UPDATE PRODUCT ===\n", FILE_APPEND);
file_put_contents($logFile, "POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

try {
    require_once __DIR__ . '/../../../connect/config.php';

    if (!function_exists('getDBConnection')) {
        throw new Exception('getDBConnection function not found');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Failed to connect to database');
    }

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) throw new Exception('Invalid product ID');

    $name = trim($_POST['name'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    $productTypeId = (int)($_POST['product_type_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $unit = $_POST['unit'] ?? 'piece';
    $materialType = $_POST['material_type'] ?? 'assembled_product';
    $description = trim($_POST['description'] ?? '');
    $materials = json_decode($_POST['materials'] ?? '[]', true);

    file_put_contents($logFile, "Parsed: id=$id, name=$name, type=$productTypeId\n", FILE_APPEND);

    // Validate
    if (empty($name)) throw new Exception('Product name is required');
    if ($productTypeId <= 0) throw new Exception('Product type is required');
    if ($price < 0) throw new Exception('Price must be non-negative');
    if ($stock < 0) throw new Exception('Stock must be non-negative');

    // Check if product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) throw new Exception('Product not found');

    $pdo->beginTransaction();

    // UPDATE - Using ONLY columns that exist
    $stmt = $pdo->prepare("
        UPDATE products 
        SET name = ?, 
            sku = ?, 
            product_type_id = ?, 
            price = ?, 
            stock = ?, 
            unit = ?, 
            material_type = ?, 
            description = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $result = $stmt->execute([
        $name,
        $sku,
        $productTypeId,
        $price,
        $stock,
        $unit,
        $materialType,
        $description,
        $id
    ]);

    if (!$result) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception('Database update failed: ' . $errorInfo[2]);
    }

    file_put_contents($logFile, "Product updated: $id\n", FILE_APPEND);

    // Update materials
    $pdo->prepare("DELETE FROM product_materials WHERE product_id = ?")->execute([$id]);

    if (!empty($materials) && is_array($materials)) {
        $matStmt = $pdo->prepare("INSERT INTO product_materials (product_id, material_id, quantity) VALUES (?, ?, ?)");
        foreach ($materials as $mat) {
            if (!empty($mat['material_id'])) {
                $matStmt->execute([
                    $id,
                    (int)$mat['material_id'],
                    (float)($mat['quantity'] ?? 1)
                ]);
            }
        }
    }

    $pdo->commit();

    file_put_contents($logFile, "SUCCESS: Product updated\n", FILE_APPEND);
    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully'
    ]);
} catch (Exception $e) {
    $errorMsg = "ERROR: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString() . "\n";
    file_put_contents($logFile, $errorMsg, FILE_APPEND);

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
