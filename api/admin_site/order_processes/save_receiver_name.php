<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../../../connect/config.php';

$pdo = getDBConnection();

$orderId = trim($_POST['order_id'] ?? '');
$quoteNumber = trim($_POST['quote_number'] ?? '');
$orderNumber = trim($_POST['order_number'] ?? '');
$receiverName = trim($_POST['receiver_name'] ?? '');

if (($orderId === '' && $quoteNumber === '' && $orderNumber === '')) {
    echo json_encode(['success' => false, 'message' => 'No order reference provided']);
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $checkCol = $pdo->prepare("
        SELECT COUNT(*)
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'orders'
          AND column_name = 'receiver_name'
    ");
    $checkCol->execute();
    if ((int)$checkCol->fetchColumn() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN receiver_name VARCHAR(255) NULL AFTER customer_phone");
    }

    $sql = "UPDATE orders SET receiver_name = :receiver_name, updated_at = NOW() WHERE 1=0";
    $params = [':receiver_name' => $receiverName];

    if ($orderId !== '') {
        $sql = "UPDATE orders SET receiver_name = :receiver_name, updated_at = NOW() WHERE id = :order_id";
        $params[':order_id'] = (int)$orderId;
    } elseif ($orderNumber !== '') {
        $sql = "UPDATE orders SET receiver_name = :receiver_name, updated_at = NOW() WHERE order_number = :order_number";
        $params[':order_number'] = $orderNumber;
    } elseif ($quoteNumber !== '') {
        $sql = "UPDATE orders SET receiver_name = :receiver_name, updated_at = NOW() WHERE quote_number = :quote_number";
        $params[':quote_number'] = $quoteNumber;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'success' => true,
        'message' => 'Receiver name saved successfully',
        'receiver_name' => $receiverName,
    ]);
} catch (PDOException $e) {
    error_log('save_receiver_name.php error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Unable to save receiver name: ' . $e->getMessage(),
    ]);
}
