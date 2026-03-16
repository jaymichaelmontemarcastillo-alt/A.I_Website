<?php
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$productId = $input['id'] ?? null;
$sessionId = session_id();

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'Product ID is required']);
    exit;
}

try {
    // Remove from wishlist
    $stmt = $pdo->prepare("DELETE FROM wishlists WHERE session_id = ? AND product_id = ?");
    $stmt->execute([$sessionId, $productId]);
    
    // Get updated count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM wishlists WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    $count = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Removed from wishlist',
        'wishlist_count' => $count
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>