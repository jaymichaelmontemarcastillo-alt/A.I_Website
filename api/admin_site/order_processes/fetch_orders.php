<?php
// fetch_orders.php - Fetch ALL quotations and orders
session_start();
header('Content-Type: application/json');
require_once '../../../connect/config.php';

// Mapping from quotation status to display status
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

    // Query 1: Get ALL quotations with their order information if exists
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
        LEFT JOIN orders o ON o.quote_number COLLATE utf8mb4_unicode_ci = q.quote_number COLLATE utf8mb4_unicode_ci
        LEFT JOIN payments p ON p.order_id = o.id
        WHERE q.status IN ('sent', 'accepted', 'converted')
        ORDER BY q.created_at DESC
    ");
    $stmt->execute();
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query 2: Get legacy orders that don't have quotations
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
            SELECT 1 FROM quotations q 
            WHERE q.quote_number COLLATE utf8mb4_unicode_ci = o.quote_number COLLATE utf8mb4_unicode_ci
            OR q.quote_number COLLATE utf8mb4_unicode_ci = o.order_number COLLATE utf8mb4_unicode_ci
        )
        ORDER BY o.created_at DESC
    ");
    $stmt->execute();
    $legacyOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge all records
    $allOrders = array_merge($quotations, $legacyOrders);

    // Process and format the data
    $formattedOrders = [];
    foreach ($allOrders as $item) {
        // Determine the display order status
        if ($item['order_id']) {
            // Has actual order record
            $displayOrderStatus = $item['order_status_value'] ?? 'pending';
            $displayPaymentMethod = $item['payment_method'] ?? 'pending';
            $displayPaymentStatus = $item['payment_status'] ?? 'pending';
            $displayQuoteId = $item['order_number'] ?? $item['quotation_id'];
            $displayQuoteNumber = $item['quote_number'] ?? $item['order_number'];
        } else {
            // Quotation without order yet
            $displayOrderStatus = $statusDisplayMapping[$item['quotation_status']] ?? 'pending';
            $displayPaymentMethod = 'pending';
            $displayPaymentStatus = 'pending';
            $displayQuoteId = $item['quotation_id'];
            $displayQuoteNumber = $item['quote_number'];
        }

        // Special handling for converted quotations
        if ($item['quotation_status'] === 'converted') {
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
            'quotation_status' => $item['quotation_status'],
            'address' => $item['address'] ?? 'N/A',
            'subtotal' => floatval($item['subtotal'] ?? 0),
            'tax' => floatval($item['tax'] ?? 0),
            'discount' => floatval($item['discount'] ?? 0),
            'notes' => $item['notes'],
            'order_id' => $item['order_id'],
            'quotation_id' => $item['quotation_id'],
            'reference_number' => $item['reference_number'] ?? null,
            'proof_image' => $item['proof_image'] ?? null
        ];
    }

    // Sort by created_at DESC
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
