<?php
// reject_payment.php - MODIFIED to update quotation on rejection, handles collation
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

    // Update orders table
    $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'failed', order_status = 'cancelled'
        WHERE order_number = ?
    ")->execute([$order_number]);

    // Update linked quotation to 'expired' - handle collation
    $stmt = $pdo->prepare("
        UPDATE quotations 
        SET status = 'expired' 
        WHERE quote_number COLLATE utf8mb4_unicode_ci = ? COLLATE utf8mb4_unicode_ci
    ");
    $stmt->execute([$order_number]);

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
    error_log("reject_payment.php error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
