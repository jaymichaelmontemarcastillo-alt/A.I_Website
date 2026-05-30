<?php
// update_order_status.php - COMPLETE FIXED Version (without updated_at)
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$order_number   = trim($data['order_number'] ?? '');
$order_status   = trim($data['order_status'] ?? '');
$payment_status = trim($data['payment_status'] ?? '');
$payment_method = trim($data['payment_method'] ?? '');

// Allowed values
$allowed_order_status   = ['pending', 'processing', 'packed', 'shipped', 'delivered', 'cancelled'];
$allowed_payment_status = ['pending', 'paid', 'failed'];
$allowed_payment_method = ['cash', 'gcash', 'card', 'pending'];

// Mapping: Order display status -> Quotation status
$orderToQuotationMapping = [
    'pending'    => 'sent',
    'processing' => 'accepted',
    'packed'     => 'accepted',
    'shipped'    => 'accepted',
    'delivered'  => 'converted',
    'cancelled'  => 'expired'
];

if (!$order_number) {
    echo json_encode(['success' => false, 'message' => 'Missing order number']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    // Find or create order
    $stmt = $pdo->prepare("
        SELECT o.id, o.order_number, o.quote_number, o.order_status, o.payment_status, o.payment_method,
               q.id AS quotation_id, q.status AS quotation_status, q.client_name, q.email, q.phone, q.total
        FROM orders o
        RIGHT JOIN quotations q ON q.quote_number COLLATE utf8mb4_unicode_ci = o.quote_number COLLATE utf8mb4_unicode_ci
        WHERE q.quote_number = ? OR o.order_number = ?
        LIMIT 1
    ");
    $stmt->execute([$order_number, $order_number]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $orderId = $record['id'] ?? null;
    $quotationId = $record['quotation_id'];
    $actualQuoteNumber = $record['quote_number'] ?? $order_number;

    // If no order exists but quotation exists, create order
    if (!$orderId && $quotationId) {
        $newOrderNumber = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt = $pdo->prepare("
            INSERT INTO orders (order_number, quote_number, customer_name, customer_email, customer_phone, total_amount, payment_method, payment_status, order_status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', 'pending', 'pending')
        ");
        $stmt->execute([$newOrderNumber, $actualQuoteNumber, $record['client_name'], $record['email'], $record['phone'], $record['total']]);
        $orderId = $pdo->lastInsertId();
    }

    $updates = [];

    // Handle PAYMENT METHOD update
    if ($payment_method && in_array($payment_method, $allowed_payment_method, true) && $orderId) {
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET payment_method = ?
            WHERE id = ?
        ");
        $stmt->execute([$payment_method, $orderId]);
        $updates['payment_method'] = $payment_method;
    }

    // Handle ORDER STATUS update
    if ($order_status && in_array($order_status, $allowed_order_status, true)) {
        if ($orderId) {
            $stmt = $pdo->prepare("
                UPDATE orders 
                SET order_status = ?
                WHERE id = ?
            ");
            $stmt->execute([$order_status, $orderId]);
            $updates['order_status'] = $order_status;
        }

        // Update quotation status based on mapping
        if ($quotationId && isset($orderToQuotationMapping[$order_status])) {
            $stmt = $pdo->prepare("
                UPDATE quotations 
                SET status = ?
                WHERE id = ?
            ");
            $stmt->execute([$orderToQuotationMapping[$order_status], $quotationId]);
        }
    }

    // Handle PAYMENT STATUS update
    if ($payment_status && in_array($payment_status, $allowed_payment_status, true)) {
        if ($orderId) {
            $stmt = $pdo->prepare("
                UPDATE orders 
                SET payment_status = ?
                WHERE id = ?
            ");
            $stmt->execute([$payment_status, $orderId]);
            $updates['payment_status'] = $payment_status;

            // Update payments table
            $stmt = $pdo->prepare("
                UPDATE payments 
                SET payment_status = ?
                WHERE order_id = ?
            ");
            $stmt->execute([$payment_status, $orderId]);

            // If payment is paid, update order status to delivered and quotation to converted
            if ($payment_status === 'paid') {
                $stmt = $pdo->prepare("
                    UPDATE orders 
                    SET order_status = 'delivered'
                    WHERE id = ?
                ");
                $stmt->execute([$orderId]);
                $updates['order_status'] = 'delivered';

                if ($quotationId) {
                    $stmt = $pdo->prepare("
                        UPDATE quotations 
                        SET status = 'converted'
                        WHERE id = ?
                    ");
                    $stmt->execute([$quotationId]);
                }
            }

            // If payment is failed, update order status to cancelled
            if ($payment_status === 'failed') {
                $stmt = $pdo->prepare("
                    UPDATE orders 
                    SET order_status = 'cancelled'
                    WHERE id = ?
                ");
                $stmt->execute([$orderId]);
                $updates['order_status'] = 'cancelled';

                if ($quotationId) {
                    $stmt = $pdo->prepare("
                        UPDATE quotations 
                        SET status = 'expired'
                        WHERE id = ?
                    ");
                    $stmt->execute([$quotationId]);
                }
            }
        }
    }

    $pdo->commit();

    // Fetch the updated data to return
    $updatedData = [];
    if ($orderId) {
        $stmt = $pdo->prepare("
            SELECT order_status, payment_status, payment_method
            FROM orders
            WHERE id = ?
        ");
        $stmt->execute([$orderId]);
        $updatedData = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($quotationId) {
        $stmt = $pdo->prepare("
            SELECT status AS order_status
            FROM quotations
            WHERE id = ?
        ");
        $stmt->execute([$quotationId]);
        $quotationStatus = $stmt->fetch(PDO::FETCH_ASSOC);
        $updatedData = [
            'order_status' => $orderToQuotationMapping[$quotationStatus['order_status']] ?? 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'pending'
        ];
    }

    echo json_encode([
        'success' => true,
        'message' => 'Order updated successfully',
        'updated_data' => $updatedData
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log("update_order_status.php error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
