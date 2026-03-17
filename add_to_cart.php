<?php
session_start();
header('Content-Type: application/json');

$cart = $_SESSION['cart'] ?? [];
$count = array_sum(array_column($cart, 'quantity'));

echo json_encode([
    'success' => true,
    'count' => $count
]);
?>