<?php
session_start();
header('Content-Type: application/json');

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(0);

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get the product ID from POST data
$productId = $_POST['id'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'Product ID is required']);
    exit;
}

// Check if cart exists
if (!isset($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit;
}

// Remove the item from cart
if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
    
    // Calculate new cart count
    $cartCount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 0;
    }
    
    // Clean output buffer before sending JSON
    if (ob_get_length()) ob_clean();
    
    echo json_encode([
        'success' => true,
        'message' => 'Item removed from cart',
        'cart_count' => $cartCount
    ]);
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Item not found in cart']);
    exit;
}
?>