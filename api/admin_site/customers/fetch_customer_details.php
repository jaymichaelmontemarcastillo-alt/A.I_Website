<?php
// api/admin_site/customers/fetch_customer_details.php
header('Content-Type: application/json');
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = trim($_GET['email'] ?? '');
    $phone = trim($_GET['phone'] ?? '');

    if (!$email && !$phone) {
        echo json_encode(['success' => false, 'error' => 'Email or phone required']);
        exit;
    }

    // ── Customer info from most recent order ──────────────────────────────────
    if ($email) {
        $infoStmt = $pdo->prepare("
            SELECT
                customer_name  AS name,
                customer_email AS email,
                customer_phone AS phone
            FROM orders
            WHERE customer_email = :ident
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $infoStmt->execute([':ident' => $email]);
    } else {
        $infoStmt = $pdo->prepare("
            SELECT
                customer_name  AS name,
                customer_email AS email,
                customer_phone AS phone
            FROM orders
            WHERE customer_phone = :ident
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $infoStmt->execute([':ident' => $phone]);
    }

    $info = $infoStmt->fetch(PDO::FETCH_ASSOC) ?: [
        'name'  => '',
        'email' => $email,
        'phone' => $phone,
    ];

    $resolvedEmail = $info['email'] ?? $email;
    $resolvedPhone = $info['phone'] ?? $phone;

    // ── Orders with items summary ─────────────────────────────────────────────
    $ordersStmt = $pdo->prepare("
        SELECT
            o.order_number,
            o.total_amount,
            o.order_status,
            o.payment_method,
            o.payment_status,
            o.created_at,
            GROUP_CONCAT(oi.product_name ORDER BY oi.id SEPARATOR ', ') AS items_summary
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        WHERE o.customer_email = :email
        GROUP BY
            o.id,
            o.order_number,
            o.total_amount,
            o.order_status,
            o.payment_method,
            o.payment_status,
            o.created_at
        ORDER BY o.created_at DESC
    ");
    $ordersStmt->execute([':email' => $resolvedEmail]);
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Quotations (collation-safe) ───────────────────────────────────────────
    $quotesStmt = $pdo->prepare("
        SELECT
            quote_number,
            total,
            status,
            expires_at,
            created_at,
            client_name,
            NULL AS items_summary
        FROM quotations
        WHERE email COLLATE utf8mb4_general_ci = :email
           OR phone COLLATE utf8mb4_general_ci = :phone
        ORDER BY created_at DESC
    ");
    $quotesStmt->execute([
        ':email' => $resolvedEmail,
        ':phone' => $resolvedPhone,
    ]);
    $quotations = $quotesStmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Summary ───────────────────────────────────────────────────────────────
    $totalOrders     = count($orders);
    $totalQuotations = count($quotations);
    $totalSpent      = array_sum(array_column($orders, 'total_amount'));

    $allDates = array_filter(array_merge(
        array_column($orders,     'created_at'),
        array_column($quotations, 'created_at')
    ));
    $lastActivity = $allDates ? max($allDates) : null;

    if ($totalOrders > 1)       $type = 'Returning Customer';
    elseif ($totalOrders === 1) $type = 'Buyer';
    else                        $type = 'Quotation Only';

    echo json_encode([
        'success'    => true,
        'info'       => $info,
        'orders'     => $orders,
        'quotations' => $quotations,
        'summary'    => [
            'total_orders'     => $totalOrders,
            'total_quotations' => $totalQuotations,
            'total_spent'      => $totalSpent,
            'last_activity'    => $lastActivity,
            'customer_type'    => $type,
        ],
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
