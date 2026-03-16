<?php
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

// Get session ID for non-logged in users
$sessionId = session_id();

try {
    // Get wishlist items with product details
    $stmt = $pdo->prepare("
        SELECT w.*, p.name, p.price, p.category, p.image, p.description 
        FROM wishlists w
        JOIN products p ON w.product_id = p.id
        WHERE w.session_id = ?
        ORDER BY w.created_at DESC
    ");
    $stmt->execute([$sessionId]);
    $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'wishlist' => $wishlist,
        'count' => count($wishlist)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>