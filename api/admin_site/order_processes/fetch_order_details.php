<?php
// fetch_order_details.php - Fetch details for ANY quotation or order
header('Content-Type: application/json; charset=utf-8');
require_once '../../../connect/config.php';

$input = trim($_GET['order_id'] ?? '');

if (!$input) {
    echo json_encode([
        'success' => false,
        'message' => 'No order/quotation ID provided'
    ]);
    exit;
}

$statusDisplayMapping = [
    'draft' => 'pending',
    'sent' => 'pending',
    'accepted' => 'processing',
    'expired' => 'cancelled',
    'converted' => 'delivered'
];

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Try to find by quotation_id first
    if (is_numeric($input)) {
        $stmt = $pdo->prepare("
            SELECT 
                q.id AS quotation_id,
                q.quote_number,
                q.client_name,
                q.contact_person,
                q.email,
                q.phone,
                q.address,
                q.status AS quotation_status,
                q.subtotal,
                q.tax,
                q.discount,
                q.total,
                q.notes,
                q.created_at,
                q.expires_at,
                o.id AS order_id,
                o.order_number,
                o.payment_method,
                o.payment_status,
                o.order_status,
                p.reference_number,
                p.proof_image
            FROM quotations q
            LEFT JOIN orders o ON o.quote_number COLLATE utf8mb4_unicode_ci = q.quote_number COLLATE utf8mb4_unicode_ci
            LEFT JOIN payments p ON p.order_id = o.id
            WHERE q.id = ?
            LIMIT 1
        ");
        $stmt->execute([$input]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Try by quote_number or order_number
        $stmt = $pdo->prepare("
            SELECT 
                q.id AS quotation_id,
                q.quote_number,
                q.client_name,
                q.contact_person,
                q.email,
                q.phone,
                q.address,
                q.status AS quotation_status,
                q.subtotal,
                q.tax,
                q.discount,
                q.total,
                q.notes,
                q.created_at,
                q.expires_at,
                o.id AS order_id,
                o.order_number,
                o.payment_method,
                o.payment_status,
                o.order_status,
                p.reference_number,
                p.proof_image
            FROM quotations q
            LEFT JOIN orders o ON o.quote_number COLLATE utf8mb4_unicode_ci = q.quote_number COLLATE utf8mb4_unicode_ci
            LEFT JOIN payments p ON p.order_id = o.id
            WHERE q.quote_number = ? OR o.order_number = ? OR o.quote_number = ?
            LIMIT 1
        ");
        $stmt->execute([$input, $input, $input]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // If still not found, try legacy orders
        if (!$order) {
            $stmt = $pdo->prepare("
                SELECT 
                    NULL AS quotation_id,
                    NULL AS quote_number,
                    NULL AS client_name,
                    NULL AS contact_person,
                    o.customer_email AS email,
                    o.customer_phone AS phone,
                    NULL AS address,
                    NULL AS quotation_status,
                    NULL AS subtotal,
                    NULL AS tax,
                    NULL AS discount,
                    o.total_amount AS total,
                    NULL AS notes,
                    o.created_at,
                    NULL AS expires_at,
                    o.id AS order_id,
                    o.order_number,
                    o.payment_method,
                    o.payment_status,
                    o.order_status,
                    NULL AS reference_number,
                    NULL AS proof_image
                FROM orders o
                WHERE o.order_number = ? OR o.quote_number = ?
                LIMIT 1
            ");
            $stmt->execute([$input, $input]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order/Quotation not found']);
        exit;
    }

    // Get items
    $items = [];

    // Try order items first
    if ($order['order_id']) {
        $stmt = $pdo->prepare("
            SELECT product_name, quantity, price, subtotal
            FROM order_items
            WHERE order_id = ?
        ");
        $stmt->execute([$order['order_id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // If no order items, try quotation items
    if (empty($items) && $order['quotation_id']) {
        $stmt = $pdo->prepare("
            SELECT description AS product_name, quantity, unit_price AS price, total AS subtotal
            FROM quotation_items
            WHERE quotation_id = ?
        ");
        $stmt->execute([$order['quotation_id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Format items
    $formattedItems = [];
    foreach ($items as $item) {
        $formattedItems[] = [
            'product_name' => $item['product_name'] ?? $item['description'] ?? 'N/A',
            'quantity' => intval($item['quantity'] ?? 0),
            'price' => floatval($item['price'] ?? $item['unit_price'] ?? 0),
            'subtotal' => floatval($item['subtotal'] ?? 0)
        ];
    }

    // Determine display order status
    if ($order['order_id']) {
        $displayOrderStatus = $order['order_status'] ?? 'pending';
    } else {
        $displayOrderStatus = $statusDisplayMapping[$order['quotation_status']] ?? 'pending';
    }

    // Handle special case for converted quotations
    if ($order['quotation_status'] === 'converted') {
        $displayOrderStatus = 'delivered';
    }

    // Resolve proof image URL
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Anything_Inside_Website";
    $proof = $order['proof_image'] ?? null;
    $resolved_proof = null;

    if (!empty($proof)) {
        $proof = str_replace('\\', '/', $proof);
        $proof = ltrim($proof, '/');
        $resolved_proof = $baseUrl . "/" . $proof;
    }

    // Build response
    $response = [
        'success' => true,
        'order' => [
            // Identification
            'quotation_id' => $order['quotation_id'],
            'quote_number' => $order['quote_number'] ?? $order['order_number'],
            'order_number' => $order['order_number'],

            // Customer Information
            'client_name' => $order['client_name'] ?? $order['customer_name'] ?? 'N/A',
            'contact_person' => $order['contact_person'] ?? $order['client_name'] ?? 'N/A',
            'customer_email' => $order['email'] ?? 'N/A',
            'customer_phone' => $order['phone'] ?? 'N/A',
            'address' => $order['address'] ?? 'N/A',

            // Quotation Information
            'quotation_status' => $order['quotation_status'] ?? 'sent',
            'created_at' => $order['created_at'],
            'expires_at' => $order['expires_at'],
            'notes' => $order['notes'],

            // Financial Information
            'subtotal' => floatval($order['subtotal'] ?? 0),
            'tax' => floatval($order['tax'] ?? 0),
            'discount' => floatval($order['discount'] ?? 0),
            'total_amount' => floatval($order['total'] ?? 0),

            // Order Information
            'payment_method' => $order['payment_method'] ?? 'pending',
            'payment_status' => $order['payment_status'] ?? 'pending',
            'order_status' => $displayOrderStatus,

            // Payment Proof
            'resolved_proof' => $resolved_proof,
            'resolved_reference' => $order['reference_number'] ?? null
        ],
        'items' => $formattedItems
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    error_log("fetch_order_details.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
