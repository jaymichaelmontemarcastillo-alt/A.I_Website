<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    
    if ($id <= 0) throw new Exception('Invalid ID');
    
    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_path FROM bundles_merchandise WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete image file if exists
    if ($item && $item['image_path']) {
        $filePath = __DIR__ . '/../../../' . $item['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM bundles_merchandise WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item deleted successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}