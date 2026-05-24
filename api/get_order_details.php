<?php
// api/get_order_details.php - Get detailed information for a specific order
header('Content-Type: application/json');
session_start();
require_once '../connect/config.php';

$pdo = getDBConnection();

$response = [
    'success' => false,
    'error' => 'Invalid request'
];

try {
    // Get order ID from query parameter
    if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
        throw new Exception('Invalid order ID');
    }

    $orderId = intval($_GET['order_id']);

    // Get order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Get order items
    $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $itemsStmt->execute([$orderId]);
    $items = $itemsStmt->fetchAll();

    $response = [
        'success' => true,
        'order' => $order,
        'items' => $items
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);
