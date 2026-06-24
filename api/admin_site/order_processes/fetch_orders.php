<?php
// fetch_orders.php - Fixed version
session_start();
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$statusDisplayMapping = [
    'draft'     => 'pending',
    'sent'      => 'pending',
    'accepted'  => 'processing',
    'expired'   => 'cancelled',
    'converted' => 'delivered'
];

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =========================
    // 1. Quotations WITH Orders
    // =========================
    $stmt = $pdo->prepare("
        SELECT 
            q.id AS quotation_id,
            q.quote_number,
            q.client_name AS customer_name,
            q.email AS customer_email,
            q.phone AS customer_phone,
            q.total AS total_amount,
            q.status AS quotation_status,
            q.created_at,
            q.address,
            q.subtotal,
            q.tax,
            q.discount,
            q.notes,

            o.id AS order_id,
            o.order_number,
            o.payment_method,
            o.payment_status,
            o.order_status AS order_status_value,

            p.reference_number,
            p.proof_image

        FROM quotations q
        LEFT JOIN orders o 
            ON o.quote_number = q.quote_number
        LEFT JOIN payments p 
            ON p.order_id = o.id
        WHERE q.status IN ('sent', 'accepted', 'converted')
        ORDER BY q.created_at DESC
    ");

    $stmt->execute();
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =========================
    // 2. Legacy Orders (no quotation)
    // =========================
    $stmt = $pdo->prepare("
        SELECT 
            NULL AS quotation_id,
            NULL AS quote_number,
            o.customer_name,
            o.customer_email,
            o.customer_phone,
            o.total_amount,
            NULL AS quotation_status,
            o.created_at,
            NULL AS address,
            NULL AS subtotal,
            NULL AS tax,
            NULL AS discount,
            NULL AS notes,

            o.id AS order_id,
            o.order_number,
            o.payment_method,
            o.payment_status,
            o.order_status AS order_status_value,

            NULL AS reference_number,
            NULL AS proof_image

        FROM orders o
        WHERE NOT EXISTS (
            SELECT 1 
            FROM quotations q
            WHERE q.quote_number = o.quote_number
        )
        ORDER BY o.created_at DESC
    ");

    $stmt->execute();
    $legacyOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =========================
    // Merge
    // =========================
    $allOrders = array_merge($quotations, $legacyOrders);

    $formattedOrders = [];

    foreach ($allOrders as $item) {

        if ($item['order_id']) {
            $displayOrderStatus = $item['order_status_value'] ?? 'pending';
            $displayPaymentMethod = $item['payment_method'] ?? 'pending';
            $displayPaymentStatus = $item['payment_status'] ?? 'pending';
            $displayQuoteId = $item['order_number'] ?? $item['quotation_id'];
            $displayQuoteNumber = $item['quote_number'] ?? $item['order_number'];
        } else {
            $displayOrderStatus = $statusDisplayMapping[$item['quotation_status']] ?? 'pending';
            $displayPaymentMethod = 'pending';
            $displayPaymentStatus = 'pending';
            $displayQuoteId = $item['quotation_id'];
            $displayQuoteNumber = $item['quote_number'];
        }

        if (($item['quotation_status'] ?? '') === 'converted') {
            $displayOrderStatus = 'delivered';
        }

        $formattedOrders[] = [
            'display_quote_id' => $displayQuoteId,
            'quote_number' => $displayQuoteNumber,
            'customer_name' => $item['customer_name'] ?? 'N/A',
            'customer_email' => $item['customer_email'] ?? 'N/A',
            'customer_phone' => $item['customer_phone'] ?? 'N/A',
            'total_amount' => floatval($item['total_amount'] ?? 0),

            'payment_method' => $displayPaymentMethod,
            'payment_status' => $displayPaymentStatus,
            'order_status' => $displayOrderStatus,

            'created_at' => $item['created_at'],
            'quotation_status' => $item['quotation_status'] ?? null,

            'address' => $item['address'] ?? 'N/A',
            'subtotal' => floatval($item['subtotal'] ?? 0),
            'tax' => floatval($item['tax'] ?? 0),
            'discount' => floatval($item['discount'] ?? 0),

            'notes' => $item['notes'] ?? null,

            'order_id' => $item['order_id'],
            'quotation_id' => $item['quotation_id'],

            'reference_number' => $item['reference_number'] ?? null,
            'proof_image' => $item['proof_image'] ?? null
        ];
    }

    usort($formattedOrders, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    echo json_encode([
        "status" => "success",
        "data" => array_values($formattedOrders),
        "count" => count($formattedOrders)
    ]);
} catch (Exception $e) {
    error_log("fetch_orders.php error: " . $e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
