<?php
// update_order_status.php
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$order_number   = trim($data['order_number']   ?? '');
$order_status   = trim($data['order_status']   ?? '');
$payment_status = trim($data['payment_status'] ?? '');

// Allowed values — whitelist to prevent invalid enum insertion
$allowed_order_status   = ['pending', 'processing', 'packed', 'shipped', 'delivered', 'cancelled'];
$allowed_payment_status = ['pending', 'paid', 'failed'];

if (!$order_number) {
    echo json_encode(['success' => false, 'message' => 'Missing order number']);
    exit;
}

if ($order_status && !in_array($order_status, $allowed_order_status, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order status value']);
    exit;
}

if ($payment_status && !in_array($payment_status, $allowed_payment_status, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment status value']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fields = [];
    $params = [];

    if ($order_status) {
        $fields[] = "order_status = ?";
        $params[]  = $order_status;
    }

    if ($payment_status) {
        $fields[] = "payment_status = ?";
        $params[]  = $payment_status;
    }

    if (empty($fields)) {
        echo json_encode(['success' => false, 'message' => 'Nothing to update']);
        exit;
    }

    $sql      = "UPDATE orders SET " . implode(", ", $fields) . " WHERE order_number = ?";
    $params[] = $order_number;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found or nothing changed']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Order updated']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
