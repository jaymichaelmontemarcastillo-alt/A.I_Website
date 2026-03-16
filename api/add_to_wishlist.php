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
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }
    
    // Check if already in wishlist
    $stmt = $pdo->prepare("SELECT * FROM wishlists WHERE session_id = ? AND product_id = ?");
    $stmt->execute([$sessionId, $productId]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => true,
            'message' => 'Product already in wishlist',
            'already_exists' => true
        ]);
        exit;
    }
    
    // Add to wishlist
    $stmt = $pdo->prepare("INSERT INTO wishlists (session_id, product_id) VALUES (?, ?)");
    $stmt->execute([$sessionId, $productId]);
    
    // Get updated count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM wishlists WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    $count = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Added to wishlist!',
        'wishlist_count' => $count
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>