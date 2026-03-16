<?php
session_start();
header('Content-Type: application/json');

require_once '../connect/config.php'; // Adjust path as needed

$cart = $_SESSION['cart'] ?? [];
$cartItems = [];
$total = 0;

if (!empty($cart)) {
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    
    // Fetch current product details from database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create associative array with product id as key
    $productDetails = [];
    foreach ($dbProducts as $product) {
        $productDetails[$product['id']] = $product;
    }
    
    // Merge cart data with fresh database info
    foreach ($cart as $id => $item) {
        if (isset($productDetails[$id])) {
            $cartItems[] = [
                'id' => $id,
                'name' => $productDetails[$id]['name'],
                'price' => $productDetails[$id]['price'],
                'category' => $productDetails[$id]['category'],
                'image' => $productDetails[$id]['image'],
                'stock' => $productDetails[$id]['stock'],
                'quantity' => $item['quantity'],
                'subtotal' => $productDetails[$id]['price'] * $item['quantity']
            ];
            $total += $productDetails[$id]['price'] * $item['quantity'];
        }
    }
}

echo json_encode([
    'success' => true,
    'cart' => $cartItems,
    'total' => $total,
    'count' => count($cartItems)
]);
?>