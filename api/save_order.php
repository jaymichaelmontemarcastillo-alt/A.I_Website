<?php
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

$pdo = getDBConnection();

try {
    // ── Validate required fields ─────────────────────────────
    if (
        empty($_POST['customerName']) ||
        empty($_POST['customerEmail']) ||
        empty($_POST['customerPhone']) ||
        empty($_POST['items']) ||
        empty($_POST['total']) ||
        empty($_POST['paymentMethod'])
    ) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid input data'
        ]);
        exit;
    }

    $items = json_decode($_POST['items'], true);
    if (!$items || count($items) === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'No items in order'
        ]);
        exit;
    }

    $pdo->beginTransaction();

    // ── Create order number ─────────────────────────────
    $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

    // ── Insert order ─────────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            order_number,
            customer_name,
            customer_email,
            customer_phone,
            total_amount,
            payment_method,
            payment_status,
            order_status,
            created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())
    ");

    $stmt->execute([
        $orderNumber,
        $_POST['customerName'],
        $_POST['customerEmail'],
        $_POST['customerPhone'],
        $_POST['total'],
        $_POST['paymentMethod']
    ]);

    $orderId = $pdo->lastInsertId();

    // ── Insert order items ─────────────────────────────
    $stmtItem = $pdo->prepare("
        INSERT INTO order_items (
            order_id,
            product_id,
            product_name,
            quantity,
            price
        ) VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($items as $item) {
        $stmtItem->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['quantity'],
            $item['price']
        ]);
    }

    // ── PAYMENT (IMPORTANT FIX HERE) ─────────────────────────────
    $referenceNumber = $_POST['reference_number'] ?? null;
    $proofPath = null;

    if ($_POST['paymentMethod'] === 'gcash' && isset($_FILES['proof_image'])) {

        $uploadDir = "../uploads/gcash/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = $_FILES['proof_image'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        $filename = "gcash_" . $orderNumber . "_" . time() . "." . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $proofPath = "uploads/gcash/" . $filename; // store relative path
        }
    }

    // ── Insert payment record ─────────────────────────────
    $stmtPay = $pdo->prepare("
        INSERT INTO payments (
            order_id,
            payment_method,
            payment_status,
            reference_number,
            proof_image
        ) VALUES (?, ?, 'pending', ?, ?)
    ");

    $stmtPay->execute([
        $orderId,
        $_POST['paymentMethod'],
        $referenceNumber,
        $proofPath
    ]);

    $pdo->commit();

    // ── Clear cart ─────────────────────────────
    $_SESSION['cart'] = [];

    echo json_encode([
        'success' => true,
        'order_number' => $orderNumber
    ]);
} catch (Exception $e) {
    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
