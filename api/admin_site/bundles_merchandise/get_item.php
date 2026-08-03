<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $id = (int)($_GET['id'] ?? 0);
    
    if ($id <= 0) {
        throw new Exception('Invalid ID');
    }
    
    $stmt = $pdo->prepare("SELECT * FROM bundles_merchandise WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        throw new Exception('Item not found');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $item
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>