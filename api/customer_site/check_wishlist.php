<?php
// api/check_wishlist.php - Check if a product is in the user's wishlist
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

$pdo = getDBConnection();
$sessionId = session_id();

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid product ID');
    }

    $productId = (int)$_GET['id'];

    // Check if product is in wishlist
    $stmt = $pdo->prepare("SELECT * FROM wishlists WHERE session_id = ? AND product_id = ?");
    $stmt->execute([$sessionId, $productId]);
    $inWishlist = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'in_wishlist' => !empty($inWishlist)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'in_wishlist' => false
    ]);
}
