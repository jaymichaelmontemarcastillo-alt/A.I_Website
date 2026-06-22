<?php
// api/cancel_order.php - Cancel an order and restore inventory
header('Content-Type: application/json');
session_start();
require_once '../connect/config.php';

$pdo = getDBConnection();

$response = [
    'success' => false,
    'error' => 'Invalid request'
];

try {
    // Get JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['order_id']) || !is_numeric($data['order_id'])) {
        throw new Exception('Invalid order ID');
    }

    $orderId = intval($data['order_id']);

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Get order details
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) {
            throw new Exception('Order not found');
        }

        // Check if order can be cancelled
        if (!in_array($order['order_status'], ['pending', 'processing'])) {
            throw new Exception('Cannot cancel ' . $order['order_status'] . ' orders');
        }

        // Get order items
        $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$orderId]);
        $items = $itemsStmt->fetchAll();

        // Restore inventory for each item
        foreach ($items as $item) {
            $updateStmt = $pdo->prepare("
                UPDATE products 
                SET stock = stock + ? 
                WHERE id = ?
            ");
            $updateStmt->execute([$item['quantity'], $item['product_id']]);
        }

        // Update order status to cancelled
        $cancelStmt = $pdo->prepare("
            UPDATE orders 
            SET order_status = 'cancelled' 
            WHERE id = ?
        ");
        $cancelStmt->execute([$orderId]);

        // Commit transaction
        $pdo->commit();

        $response = [
            'success' => true,
            'message' => 'Order cancelled successfully',
            'restored_items' => count($items)
        ];
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    http_response_code(400);
}

echo json_encode($response);
