<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $existingImage = $_POST['existing_image'] ?? '';
    
    if ($id <= 0) throw new Exception('Invalid ID');
    if (empty($name)) throw new Exception('Name is required');
    
    $imagePath = $existingImage;
    
    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get current type
        $stmt = $pdo->prepare("SELECT type FROM bundles_merchandise WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        $type = $current['type'] ?? 'bundle';
        
        $uploadDir = __DIR__ . '/../../../uploads/' . $type . 's/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Delete old image if exists
        if ($existingImage && file_exists(__DIR__ . '/../../../' . $existingImage)) {
            unlink(__DIR__ . '/../../../' . $existingImage);
        }
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/' . $type . 's/' . $filename;
        }
    }
    
    $stmt = $pdo->prepare("
        UPDATE bundles_merchandise 
        SET name = ?, description = ?, image_path = ?, sort_order = ?
        WHERE id = ?
    ");
    $stmt->execute([$name, $description, $imagePath, $sortOrder, $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}