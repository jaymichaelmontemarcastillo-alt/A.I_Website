<?php
session_start();
header('Content-Type: application/json');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$productId = $input['id'] ?? null;
$productName = $input['name'] ?? '';
$productPrice = $input['price'] ?? 0;
$productCategory = $input['category'] ?? '';
$productImage = $input['image'] ?? '';
$quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

// Validate required fields
if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'Product ID is required']);
    exit;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product already in cart
if (isset($_SESSION['cart'][$productId])) {
    // Update quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
    $message = 'Cart updated!';
} else {
    // Add new item to cart
    $_SESSION['cart'][$productId] = [
        'id' => $productId,
        'name' => $productName,
        'price' => (float)$productPrice,
        'category' => $productCategory,
        'image' => $productImage,
        'quantity' => $quantity
    ];
    $message = 'Item added to cart!';
}

// Calculate total cart items
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 0;
}

// Clean output buffer
if (ob_get_length()) ob_clean();

echo json_encode([
    'success' => true,
    'message' => $message,
    'cart_count' => $cartCount
]);
exit;
?>