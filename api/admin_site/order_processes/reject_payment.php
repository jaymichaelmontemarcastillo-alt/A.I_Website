<?php
// reject_payment.php
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_number = trim($data['order_number'] ?? '');

if (!$order_number) {
    echo json_encode(['success' => false, 'message' => 'Missing order number']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch order with payment record
    $stmt = $pdo->prepare("
        SELECT o.id, o.payment_status, p.id AS payment_id
        FROM orders o
        LEFT JOIN payments p ON p.order_id = o.id
        WHERE o.order_number = ?
        LIMIT 1
    ");
    $stmt->execute([$order_number]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    if ($order['payment_status'] === 'paid') {
        echo json_encode(['success' => false, 'message' => 'Cannot reject an already approved payment']);
        exit;
    }

    if ($order['payment_status'] === 'failed') {
        echo json_encode(['success' => false, 'message' => 'Payment already rejected']);
        exit;
    }

    $pdo->beginTransaction();

    // Update orders table - order_status stays 'pending' on rejection
    $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'failed'
        WHERE order_number = ?
    ")->execute([$order_number]);

    // Update payments table if record exists
    if ($order['payment_id']) {
        $pdo->prepare("
            UPDATE payments 
            SET payment_status = 'failed',
                verified_at = NOW()
            WHERE id = ?
        ")->execute([$order['payment_id']]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Payment rejected']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
