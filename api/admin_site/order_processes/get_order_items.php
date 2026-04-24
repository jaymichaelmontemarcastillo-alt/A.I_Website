<?php
//get_order_items.php
header('Content-Type: application/json; charset=utf-8');
require_once '../../../connect/config.php';

$order_number = $_GET['order_id'] ?? '';

if (!$order_number) {
    echo json_encode(['success' => false, 'message' => 'No order ID provided']);
    exit;
}

try {
    $pdo = getDBConnection();

    // Correct join to match order_number to order_items
    $stmt = $pdo->prepare("
        SELECT i.product_name, i.quantity, i.price, i.subtotal
        FROM order_items i
        JOIN orders o ON o.id = i.order_id
        WHERE o.order_number = ?
    ");
    $stmt->execute([$order_number]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        echo json_encode(['success' => false, 'message' => 'No items found for this order']);
        exit;
    }

    // Calculate total (optional since order.total_amount exists)
    $total = array_reduce($items, fn($carry, $item) => $carry + $item['subtotal'], 0);

    echo json_encode([
        'success' => true,
        'items' => $items,
        'total' => $total
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
