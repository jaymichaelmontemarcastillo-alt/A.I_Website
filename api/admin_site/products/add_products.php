<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

function logActivity(PDO $pdo, string $action, string $details, string $status, $referenceId = null): void
{
    $userId   = $_SESSION['AdminID']  ?? null;
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

    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = $_POST['price'] ?? null;
    $stock    = $_POST['stock'] ?? null;

    // ─── Validation
    if (empty($name) || strlen($name) < 3) throw new Exception('Product name must be at least 3 characters');
    if (empty($category) || strlen($category) < 2) throw new Exception('Category must be at least 2 characters');
    if ($price === null || !is_numeric($price) || $price < 0) throw new Exception('Price must be a valid number');
    if ($stock === null || !is_numeric($stock) || $stock < 0) throw new Exception('Stock must be a valid number');

    // ─── Image upload
    $filename = 'assets/images/admin-site/default.png';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['image'];
        if ($img['size'] > 5 * 1024 * 1024) throw new Exception('Image size must be less than 5MB');

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $img['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!in_array($mime, $allowed)) throw new Exception('Only JPEG, PNG, WEBP, and AVIF images are allowed');

        $upload_dir = '../../../uploads/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $filename = 'uploads/products/' . uniqid('prod_', true) . '.' . $ext;

        if (!move_uploaded_file($img['tmp_name'], '../../../' . $filename))
            throw new Exception('Failed to upload image. Check directory permissions.');
    }

    // ─── Insert product
    $stmt = $pdo->prepare(
        "INSERT INTO products (name, category, price, stock, image, created_at)
         VALUES (?, ?, ?, ?, ?, NOW())"
    );
    $stmt->execute([$name, $category, (float)$price, (int)$stock, $filename]);
    $newId = $pdo->lastInsertId();

    // ─── Log success
    logActivity(
        $pdo,
        'Add Product',
        "Added new product: \"$name\" | Category: $category | Price: ₱" . number_format((float)$price, 2) . " | Stock: $stock",
        'Success',
        $newId
    );

    echo json_encode(['success' => true, 'message' => 'Product added successfully!', 'id' => $newId, 'image' => $filename]);
} catch (Exception $e) {
    if (isset($pdo)) {
        $attemptedName = trim($_POST['name'] ?? 'Unknown');
        logActivity($pdo, 'Add Product', "Failed to add product \"$attemptedName\": " . $e->getMessage(), 'Failed');
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
