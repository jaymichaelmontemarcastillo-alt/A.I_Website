<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

function logActivity(PDO $pdo, string $action, string $details, string $status, $referenceId = null): void
{
    $userId = $_SESSION['AdminID'] ?? null;
    $userName = $_SESSION['FullName'] ?? 'System/Unknown';
    $stmt = $pdo->prepare(
        "INSERT INTO activity_logs (UserID, UserName, ActionType, ActionDetails, ReferenceID, Status)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$userId, $userName, $action, $details, $referenceId, $status]);
}

try {
    $pdo = getDBConnection();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Fetch old product
    $oldStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $oldStmt->execute([$id]);
    $old = $oldStmt->fetch(PDO::FETCH_ASSOC);
    if (!$old) {
        throw new Exception('Product not found');
    }

    $name = trim($_POST['name'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $product_type_id = (int)($_POST['product_type_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $material_type = trim($_POST['material_type'] ?? $old['material_type']);
    $unit = trim($_POST['unit'] ?? $old['unit']);

    // Validation
    if (empty($name) || strlen($name) < 3) {
        throw new Exception('Product name must be at least 3 characters');
    }

    if ($price < 0) {
        throw new Exception('Price must be a valid number');
    }

    if ($stock < 0) {
        throw new Exception('Stock must be a valid number');
    }

    // Check for duplicate SKU (excluding current)
    if ($sku && $sku !== $old['sku']) {
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE sku = ? AND id != ?");
        $checkStmt->execute([$sku, $id]);
        if ($checkStmt->fetch()) {
            throw new Exception('SKU already exists');
        }
    }

    $image = $old['image'];
    $imageUpdated = false;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['image'];
        if ($img['size'] > 5 * 1024 * 1024) {
            throw new Exception('Image size must be less than 5MB');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $img['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!in_array($mime, $allowed)) {
            throw new Exception('Only JPEG, PNG, WEBP, and AVIF images are allowed');
        }

        // Delete old image if not default
        if ($old['image'] && strpos($old['image'], 'default') === false && file_exists('../../../' . $old['image'])) {
            @unlink('../../../' . $old['image']);
        }

        $upload_dir = '../../../uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $filename = 'uploads/products/' . uniqid('prod_', true) . '.' . $ext;
        $image = $filename;
        $imageUpdated = true;

        if (!move_uploaded_file($img['tmp_name'], '../../../' . $filename)) {
            throw new Exception('Failed to upload image. Check directory permissions.');
        }
    }

    $stmt = $pdo->prepare("
        UPDATE products 
        SET name = ?, sku = ?, category_id = ?, product_type_id = ?, material_type = ?,
            price = ?, stock = ?, description = ?, image = ?, unit = ?, updated_at = NOW() 
        WHERE id = ?
    ");

    $stmt->execute([$name, $sku, $category_id, $product_type_id, $material_type, $price, $stock, $description, $image, $unit, $id]);

    // Log changes
    $changes = [];
    if ($old['name'] !== $name) $changes[] = "Name: \"{$old['name']}\" → \"$name\"";
    if (($old['category_id'] ?? 0) != $category_id) $changes[] = "Category updated";
    if (($old['product_type_id'] ?? 0) != $product_type_id) $changes[] = "Product type updated";
    if ((float)$old['price'] !== $price) $changes[] = "Price: ₱" . number_format((float)$old['price'], 2) . " → ₱" . number_format($price, 2);
    if ((int)($old['stock'] ?? 0) !== $stock) $changes[] = "Stock: {$old['stock']} → $stock";
    if ($imageUpdated) $changes[] = "Image updated";

    $detail = empty($changes)
        ? "Updated product \"$name\" (ID: $id) — no changes"
        : "Updated product \"$name\" (ID: $id): " . implode(', ', $changes);

    logActivity($pdo, 'Update Product', $detail, 'Success', $id);

    $response = ['success' => true, 'message' => 'Product updated successfully!'];
    if ($imageUpdated) $response['image'] = $image;
    echo json_encode($response);
} catch (Exception $e) {
    if (isset($pdo)) {
        $attemptedName = trim($_POST['name'] ?? 'Unknown');
        logActivity($pdo, 'Update Product', "Failed to update \"$attemptedName\" (ID: {$id}): " . $e->getMessage(), 'Failed', $id ?? null);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
