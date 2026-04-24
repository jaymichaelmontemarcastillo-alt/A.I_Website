<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../../../connect/config.php';

$order_number = trim($_GET['order_id'] ?? '');

if (!$order_number) {
    echo json_encode([
        'success' => false,
        'message' => 'No order ID provided'
    ]);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT 
            o.id,
            o.order_number,
            o.customer_name,
            o.customer_email,
            o.customer_phone,
            o.total_amount,
            o.payment_method,
            o.payment_status,
            o.order_status,
            o.created_at,

            p.reference_number,
            p.proof_image

        FROM orders o
        LEFT JOIN payments p ON p.order_id = o.id
        WHERE o.order_number = ?
        LIMIT 1
    ");

    $stmt->execute([$order_number]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => 'Order not found'
        ]);
        exit;
    }

    $itemsStmt = $pdo->prepare("
        SELECT product_name, quantity, price
        FROM order_items
        WHERE order_id = ?
    ");
    $itemsStmt->execute([$order['id']]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    -------------------------------------------------
    FIX: CLEAN + BUILD ABSOLUTE IMAGE URL
    -------------------------------------------------
    */

    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Anything_Inside_Website";

    $proof = $order['proof_image'] ?? null;

    if (!empty($proof)) {

        // normalize slashes
        $proof = str_replace('\\', '/', $proof);

        // remove leading slashes
        $proof = ltrim($proof, '/');

        // build FULL URL
        $resolved_proof = $baseUrl . "/" . $proof;
    } else {
        $resolved_proof = null;
    }

    $resolved_reference = $order['reference_number'] ?? null;

    echo json_encode([
        'success' => true,
        'order' => [
            'id' => $order['id'],
            'order_number' => $order['order_number'],
            'customer_name' => $order['customer_name'],
            'customer_email' => $order['customer_email'],
            'customer_phone' => $order['customer_phone'],
            'total_amount' => $order['total_amount'],
            'payment_method' => $order['payment_method'],
            'payment_status' => $order['payment_status'],
            'created_at' => $order['created_at'],

            // IMPORTANT FIXED OUTPUT
            'resolved_proof' => $resolved_proof,
            'resolved_reference' => $resolved_reference
        ],
        'items' => $items
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
