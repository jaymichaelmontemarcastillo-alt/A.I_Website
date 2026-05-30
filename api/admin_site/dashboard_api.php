<?php

/**
 * dashboard_api.php
 * All sales data derived from quotations table
 */

require_once '../../connect/config.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

$pdo    = getDBConnection();
$action = $_GET['action'] ?? 'all';

try {
    switch ($action) {
        case 'kpi':
            echo json_encode(getKPI($pdo));
            break;
        case 'charts':
            echo json_encode([
                'daily_sales'   => getDailySales($pdo),
                'monthly_sales' => getMonthlySales($pdo),
            ]);
            break;
        case 'orders':
            echo json_encode([
                'recent_orders'     => getRecentOrders($pdo),
                'recent_quotations' => getRecentQuotations($pdo),
                'recent_requests'   => getRecentRequests($pdo),
            ]);
            break;
        case 'insights':
            echo json_encode([
                'payment_overview' => getPaymentOverview($pdo),
                'alerts'           => getAlerts($pdo),
            ]);
            break;
        case 'activity':
            echo json_encode([
                'activity_logs' => getActivityLogs($pdo),
            ]);
            break;
        default:
            echo json_encode([
                'kpi'               => getKPI($pdo),
                'daily_sales'       => getDailySales($pdo),
                'monthly_sales'     => getMonthlySales($pdo),
                'recent_orders'     => getRecentOrders($pdo),
                'recent_quotations' => getRecentQuotations($pdo),
                'recent_requests'   => getRecentRequests($pdo),
                'payment_overview'  => getPaymentOverview($pdo),
                'alerts'            => getAlerts($pdo),
                'activity_logs'     => getActivityLogs($pdo),
            ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
exit;

// ════════════════════════════════════════════════════════════════════════════
//  KPI - Based on Quotations with status = 'converted'
// ════════════════════════════════════════════════════════════════════════════

function getKPI(PDO $pdo): array
{
    // Total Revenue from converted quotations (completed sales)
    $revenue = $pdo->query("
        SELECT
            COALESCE(SUM(total), 0) AS all_time,
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN total END), 0) AS this_month,
            COALESCE(SUM(CASE
                WHEN DATE(created_at) = CURDATE()
                THEN total END), 0) AS today
        FROM quotations
        WHERE status = 'converted'
    ")->fetch(PDO::FETCH_ASSOC);

    // Month-over-month % change
    $mom = $pdo->query("
        SELECT
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN total END), 0) AS current_month,
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH))
                 AND YEAR(created_at)  = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH))
                THEN total END), 0) AS last_month
        FROM quotations
        WHERE status = 'converted'
    ")->fetch(PDO::FETCH_ASSOC);

    $revenueChange = $mom['last_month'] > 0
        ? round((($mom['current_month'] - $mom['last_month']) / $mom['last_month']) * 100, 1)
        : ($mom['current_month'] > 0 ? 100 : 0);

    // Total converted quotations
    $orders = $pdo->query("
        SELECT
            COUNT(*) AS total,
            SUM(status = 'converted') AS completed
        FROM quotations
        WHERE status = 'converted'
    ")->fetch(PDO::FETCH_ASSOC);

    // Also get pending/other status counts for display
    $statusCounts = $pdo->query("
        SELECT
            SUM(status = 'draft') AS draft,
            SUM(status = 'sent') AS sent,
            SUM(status = 'accepted') AS accepted,
            SUM(status = 'expired') AS expired,
            SUM(status = 'converted') AS converted
        FROM quotations
    ")->fetch(PDO::FETCH_ASSOC);

    $orders['draft'] = $statusCounts['draft'] ?? 0;
    $orders['sent'] = $statusCounts['sent'] ?? 0;
    $orders['accepted'] = $statusCounts['accepted'] ?? 0;
    $orders['expired'] = $statusCounts['expired'] ?? 0;

    // Customers from quotations
    $customers = $pdo->query("
        SELECT
            COUNT(DISTINCT client_name) AS total,
            COUNT(DISTINCT CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN client_name END) AS new_this_month
        FROM quotations
        WHERE status = 'converted'
    ")->fetch(PDO::FETCH_ASSOC);

    // Low Stock from materials table
    $stock = $pdo->query("
        SELECT
            SUM(total_stock > 0 AND total_stock <= low_stock_threshold) AS low_stock,
            SUM(total_stock = 0) AS out_of_stock
        FROM materials
    ")->fetch(PDO::FETCH_ASSOC);

    return [
        'revenue'        => $revenue,
        'revenue_change' => $revenueChange,
        'orders'         => $orders,
        'customers'      => $customers,
        'stock'          => $stock,
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  DAILY SALES CHART - Based on converted quotations
// ════════════════════════════════════════════════════════════════════════════

function getDailySales(PDO $pdo): array
{
    $rows = $pdo->query("
        SELECT
            DATE(created_at) AS sale_date,
            COALESCE(SUM(total), 0) AS total
        FROM quotations
        WHERE status = 'converted'
          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(created_at)
        ORDER BY sale_date ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $indexed = array_column($rows, 'total', 'sale_date');

    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $date     = date('Y-m-d', strtotime("-$i days"));
        $result[] = [
            'label' => date('D', strtotime("-$i days")),
            'total' => isset($indexed[$date]) ? (float)$indexed[$date] : 0.0,
        ];
    }
    return $result;
}

// ════════════════════════════════════════════════════════════════════════════
//  MONTHLY SALES CHART - Based on converted quotations
// ════════════════════════════════════════════════════════════════════════════

function getMonthlySales(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            DATE_FORMAT(created_at, '%b %Y') AS label,
            COALESCE(SUM(total), 0) AS total
        FROM quotations
        WHERE status = 'converted'
          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY YEAR(created_at), MONTH(created_at) ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

// ════════════════════════════════════════════════════════════════════════════
//  RECENT ORDERS (Converted Quotations)
// ════════════════════════════════════════════════════════════════════════════

function getRecentOrders(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            quote_number AS order_number,
            client_name AS customer_name,
            contact_person,
            email AS customer_email,
            phone,
            DATE_FORMAT(created_at, '%b %d, %Y') AS order_date,
            total AS total_amount,
            status,
            audited,
            audit_id
        FROM quotations
        WHERE status = 'converted'
        ORDER BY created_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
}

// ════════════════════════════════════════════════════════════════════════════
//  RECENT QUOTATIONS (All statuses)
// ════════════════════════════════════════════════════════════════════════════

function getRecentQuotations(PDO $pdo): array
{
    // Quotation status summary
    $quoteSummary = $pdo->query("
        SELECT
            COUNT(*) AS total,
            SUM(status = 'draft') AS draft,
            SUM(status = 'sent') AS sent,
            SUM(status = 'accepted') AS accepted,
            SUM(status = 'expired') AS expired,
            SUM(status = 'converted') AS converted
        FROM quotations
    ")->fetch(PDO::FETCH_ASSOC);

    $recentQuotations = $pdo->query("
        SELECT
            quote_number,
            client_name,
            status,
            total,
            DATE_FORMAT(created_at, '%b %d, %Y') AS quote_date
        FROM quotations
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'summary' => $quoteSummary,
        'recent'  => $recentQuotations,
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  RECENT QUOTATION REQUESTS
// ════════════════════════════════════════════════════════════════════════════

function getRecentRequests(PDO $pdo): array
{
    $requestSummary = $pdo->query("
        SELECT
            COUNT(*) AS total,
            SUM(status = 'pending') AS pending,
            SUM(status = 'processed') AS processed,
            SUM(status = 'cancelled') AS cancelled
        FROM requests
    ")->fetch(PDO::FETCH_ASSOC);

    $recentRequests = $pdo->query("
        SELECT
            request_number,
            client_name,
            status,
            DATE_FORMAT(created_at, '%b %d, %Y') AS request_date
        FROM requests
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'summary' => $requestSummary,
        'recent'  => $recentRequests,
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  PAYMENT OVERVIEW
// ════════════════════════════════════════════════════════════════════════════

function getPaymentOverview(PDO $pdo): array
{
    // Total received from converted quotations
    $totalReceived = $pdo->query("
        SELECT COALESCE(SUM(total), 0) AS total_received
        FROM quotations
        WHERE status = 'converted'
    ")->fetch(PDO::FETCH_ASSOC);

    // Payment counts by status from payments table
    $paymentCounts = $pdo->query("
        SELECT
            SUM(payment_status = 'pending') AS pending,
            SUM(payment_status = 'verified') AS verified,
            SUM(payment_status = 'paid') AS paid,
            SUM(payment_status = 'failed') AS failed
        FROM payments
    ")->fetch(PDO::FETCH_ASSOC);

    // Payment methods distribution
    $methods = $pdo->query("
        SELECT payment_method, COUNT(*) AS count
        FROM payments
        GROUP BY payment_method
        ORDER BY count DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'summary' => [
            'total_received' => $totalReceived['total_received'] ?? 0,
            'pending'        => (int)($paymentCounts['pending'] ?? 0),
            'verified'       => (int)($paymentCounts['verified'] ?? 0),
            'paid'           => (int)($paymentCounts['paid'] ?? 0),
            'failed'         => (int)($paymentCounts['failed'] ?? 0),
        ],
        'methods' => $methods,
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  ALERTS
// ════════════════════════════════════════════════════════════════════════════

function getAlerts(PDO $pdo): array
{
    // Out of stock materials
    $outOfStock = $pdo->query("
        SELECT material_name, total_stock
        FROM materials
        WHERE total_stock = 0
        ORDER BY updated_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Low stock materials (<= threshold)
    $lowStock = $pdo->query("
        SELECT material_name, total_stock, low_stock_threshold
        FROM materials
        WHERE total_stock > 0 AND total_stock <= low_stock_threshold
        ORDER BY total_stock ASC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Pending payments
    $pendingPayments = (int)$pdo->query("
        SELECT COUNT(*) FROM payments WHERE payment_status = 'pending'
    ")->fetchColumn();

    // Failed payments in last 7 days
    $failedPayments = (int)$pdo->query("
        SELECT COUNT(*) FROM payments
        WHERE payment_status = 'failed'
          AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ")->fetchColumn();

    // Failed login attempts in last 24 hours
    $failedLogins = (int)$pdo->query("
        SELECT COUNT(*) FROM activity_logs
        WHERE ActionType = 'Logins'
          AND Status = 'Failed'
          AND CreatedAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ")->fetchColumn();

    // Accepted quotations pending conversion/audit
    $pendingConversion = (int)$pdo->query("
        SELECT COUNT(*) FROM quotations
        WHERE status = 'accepted'
    ")->fetchColumn();

    // Converted quotations pending audit
    $pendingAudit = (int)$pdo->query("
        SELECT COUNT(*) FROM quotations
        WHERE status = 'converted'
          AND (audited = 0 OR audited IS NULL)
    ")->fetchColumn();

    return [
        'out_of_stock'       => $outOfStock,
        'low_stock'          => $lowStock,
        'pending_payments'   => $pendingPayments,
        'failed_payments'    => $failedPayments,
        'failed_logins'      => $failedLogins,
        'pending_conversion' => $pendingConversion,
        'pending_audit'      => $pendingAudit,
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  ACTIVITY LOGS
// ════════════════════════════════════════════════════════════════════════════

function getActivityLogs(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            UserName,
            ActionType,
            ActionDetails,
            Status,
            DATE_FORMAT(CreatedAt, '%b %d, %Y %H:%i') AS log_time
        FROM activity_logs
        ORDER BY CreatedAt DESC
        LIMIT 20
    ")->fetchAll(PDO::FETCH_ASSOC);
}
