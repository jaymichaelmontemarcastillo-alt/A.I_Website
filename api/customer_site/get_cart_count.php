<?php
// api/get_cart_count.php - Get the number of items in the cart
session_start();
header('Content-Type: application/json');

$count = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += isset($item['quantity']) ? (int)$item['quantity'] : 0;
    }
}

echo json_encode([
    'success' => true,
    'count' => $count
]);
