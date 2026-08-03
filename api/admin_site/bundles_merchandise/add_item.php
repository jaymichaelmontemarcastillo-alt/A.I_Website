<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    
    $type = $_POST['type'] ?? 'bundle';
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    
    if (empty($name)) {
        throw new Exception('Name is required');
    }
    
    $imagePath = '';
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../uploads/' . $type . 's/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/' . $type . 's/' . $filename;
        }
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO bundles_merchandise (type, name, description, image_path, sort_order)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$type, $name, $description, $imagePath, $sortOrder]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item added successfully',
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>