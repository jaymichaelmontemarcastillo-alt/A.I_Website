<?php
session_start();
header('Content-Type: application/json');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$productId = $input['id'] ?? null;
$action = $input['action'] ?? null;

if (!$productId || !$action) {
    echo json_encode(['success' => false, 'error' => 'Product ID and action are required']);
    exit;
}

// Check if cart exists and item is in cart
if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$productId])) {
    echo json_encode(['success' => false, 'error' => 'Item not found in cart']);
    exit;
}

// Update quantity based on action
if ($action === 'increase') {
    $_SESSION['cart'][$productId]['quantity'] += 1;
} elseif ($action === 'decrease') {
    if ($_SESSION['cart'][$productId]['quantity'] > 1) {
        $_SESSION['cart'][$productId]['quantity'] -= 1;
    } else {
        // If quantity becomes 0, remove the item
        unset($_SESSION['cart'][$productId]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
    exit;
}

// Calculate new cart count and total
$cartCount = 0;
$cartTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 0;
    $price = isset($item['price']) ? (float)$item['price'] : 0;
    $cartCount += $quantity;
    $cartTotal += $price * $quantity;
}

// Clean output buffer
if (ob_get_length()) ob_clean();

echo json_encode([
    'success' => true,
    'message' => 'Cart updated',
    'cart_count' => $cartCount,
    'cart_total' => $cartTotal
]);
exit;
?>