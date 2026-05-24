<?php

/**
 * dashboard_api.php
 *
 * Lazy-load endpoints (aligned with admin_dashboard.js):
 *
 *   ?action=kpi        → KPI cards only            (immediate load)
 *   ?action=charts     → daily + monthly + top_products + sales_by_category
 *   ?action=orders     → recent_orders + quotations_requests
 *   ?action=insights   → product_insights + payment_overview + customer_insights
 *   ?action=alerts     → alerts panel
 *   ?action=activity   → activity logs
 *   ?action=all        → legacy fallback (all combined)
 *
 * Schema alignment (your real tables):
 *   orders        — customer_name, customer_email, order_number,
 *                   total_amount, order_status, payment_status
 *   order_items   — product_name, quantity, subtotal, product_id, order_id
 *   products      — name, category, stock, price, created_at, updated_at
 *   payments      — order_id, payment_method, payment_status, created_at
 *   quotations    — quote_number, client_name, status, total, created_at
 *   requests      — request_number, client_name, status, created_at
 *   activity_logs — UserName, ActionType, ActionDetails, Status, CreatedAt
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
                'daily_sales'       => getDailySales($pdo),
                'monthly_sales'     => getMonthlySales($pdo),
                'top_products'      => getTopProducts($pdo),
                'sales_by_category' => getSalesByCategory($pdo),
            ]);
            break;

        case 'orders':
            echo json_encode([
                'recent_orders'       => getRecentOrders($pdo),
                'quotations_requests' => getQuotationsRequests($pdo),
            ]);
            break;

        case 'insights':
            echo json_encode([
                'product_insights'  => getProductInsights($pdo),
                'payment_overview'  => getPaymentOverview($pdo),
                'customer_insights' => getCustomerInsights($pdo),
            ]);
            break;

        case 'alerts':
            echo json_encode([
                'alerts' => getAlerts($pdo),
            ]);
            break;

        case 'activity':
            echo json_encode([
                'activity_logs' => getActivityLogs($pdo),
            ]);
            break;

        // Legacy fallback
        case 'all':
        default:
            echo json_encode([
                'kpi'                 => getKPI($pdo),
                'daily_sales'         => getDailySales($pdo),
                'monthly_sales'       => getMonthlySales($pdo),
                'top_products'        => getTopProducts($pdo),
                'sales_by_category'   => getSalesByCategory($pdo),
                'recent_orders'       => getRecentOrders($pdo),
                'product_insights'    => getProductInsights($pdo),
                'payment_overview'    => getPaymentOverview($pdo),
                'customer_insights'   => getCustomerInsights($pdo),
                'quotations_requests' => getQuotationsRequests($pdo),
                'alerts'              => getAlerts($pdo),
                'activity_logs'       => getActivityLogs($pdo),
            ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
exit;


// ════════════════════════════════════════════════════════════════════════════
//  KPI  — ?action=kpi
//  JS renderKPI() reads: data.kpi.revenue, .revenue_change, .orders,
//                        .customers, .stock
// ════════════════════════════════════════════════════════════════════════════
function getKPI(PDO $pdo): array
{
    $revenue = $pdo->query("
        SELECT
            COALESCE(SUM(total_amount), 0) AS all_time,
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN total_amount END), 0) AS this_month,
            COALESCE(SUM(CASE
                WHEN DATE(created_at) = CURDATE()
                THEN total_amount END), 0) AS today
        FROM orders
        WHERE order_status NOT IN ('cancelled')
          AND payment_status = 'paid'
    ")->fetch(PDO::FETCH_ASSOC);

    // Month-over-month % — handles January correctly via DATE_SUB
    $mom = $pdo->query("
        SELECT
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN total_amount END), 0) AS current_month,
            COALESCE(SUM(CASE
                WHEN MONTH(created_at) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH))
                 AND YEAR(created_at)  = YEAR(DATE_SUB(NOW(),  INTERVAL 1 MONTH))
                THEN total_amount END), 0) AS last_month
        FROM orders
        WHERE order_status NOT IN ('cancelled')
          AND payment_status = 'paid'
    ")->fetch(PDO::FETCH_ASSOC);

    $revenueChange = $mom['last_month'] > 0
        ? round((($mom['current_month'] - $mom['last_month']) / $mom['last_month']) * 100, 1)
        : 0;

    $orders = $pdo->query("
        SELECT
            COUNT(*)                                                           AS total,
            SUM(order_status = 'pending')                                      AS pending,
            SUM(order_status = 'delivered')                                    AS completed,
            SUM(order_status = 'cancelled')                                    AS cancelled,
            SUM(order_status IN ('processing', 'packed', 'shipped'))           AS in_progress
        FROM orders
    ")->fetch(PDO::FETCH_ASSOC);

    // Customers derived from orders table (your schema has no separate customers table)
    $customers = $pdo->query("
        SELECT
            COUNT(DISTINCT customer_email) AS total,
            COUNT(DISTINCT CASE
                WHEN MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at)  = YEAR(NOW())
                THEN customer_email END)   AS new_this_month
        FROM orders
    ")->fetch(PDO::FETCH_ASSOC);

    $stock = $pdo->query("
        SELECT
            SUM(stock > 0 AND stock <= 5) AS low_stock,
            SUM(stock = 0)                AS out_of_stock
        FROM products
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
//  CHARTS  — ?action=charts
// ════════════════════════════════════════════════════════════════════════════

/**
 * JS renderDailyChart() reads: rows[].label, rows[].total
 */
function getDailySales(PDO $pdo): array
{
    $rows = $pdo->query("
        SELECT
            DATE(created_at)               AS sale_date,
            COALESCE(SUM(total_amount), 0) AS total
        FROM orders
        WHERE order_status NOT IN ('cancelled')
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

/**
 * JS renderMonthlyChart() reads: rows[].label, rows[].total
 */
function getMonthlySales(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            DATE_FORMAT(created_at, '%b %Y')   AS label,
            COALESCE(SUM(total_amount), 0)      AS total
        FROM orders
        WHERE order_status NOT IN ('cancelled')
          AND created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY YEAR(created_at), MONTH(created_at) ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * JS renderTopProductsChart() reads: rows[].product_name, .total_qty, .total_revenue
 */
function getTopProducts(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            oi.product_name,
            SUM(oi.quantity) AS total_qty,
            SUM(oi.subtotal) AS total_revenue
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY oi.product_name
        ORDER BY total_qty DESC
        LIMIT 8
    ")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * JS renderCategoryChart() reads: rows[].category, rows[].total
 */
function getSalesByCategory(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            p.category,
            COALESCE(SUM(oi.subtotal), 0) AS total
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        JOIN orders   o ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY p.category
        ORDER BY total DESC
        LIMIT 6
    ")->fetchAll(PDO::FETCH_ASSOC);
}


// ════════════════════════════════════════════════════════════════════════════
//  ORDERS  — ?action=orders
// ════════════════════════════════════════════════════════════════════════════

/**
 * JS renderRecentOrders() reads:
 *   o.order_number, .customer_name, .customer_email,
 *   .order_date, .total_amount, .payment_status, .order_status
 */
function getRecentOrders(PDO $pdo): array
{
    return $pdo->query("
        SELECT
            order_number,
            customer_name,
            customer_email,
            DATE_FORMAT(created_at, '%b %d, %Y') AS order_date,
            total_amount,
            order_status,
            payment_status
        FROM orders
        ORDER BY created_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * JS renderQuotationsRequests() reads:
 *   .quote_summary   { draft, sent, accepted, expired, converted }
 *   .recent_quotations[] { quote_number, client_name, total, status }
 *   .request_summary { pending, processed, cancelled }
 *   .recent_requests[]  { request_number, client_name, request_date, status }
 */
function getQuotationsRequests(PDO $pdo): array
{
    $quoteSummary = $pdo->query("
        SELECT
            COUNT(*)                      AS total,
            SUM(status = 'draft')         AS draft,
            SUM(status = 'sent')          AS sent,
            SUM(status = 'accepted')      AS accepted,
            SUM(status = 'expired')       AS expired,
            SUM(status = 'converted')     AS converted
        FROM quotations
    ")->fetch(PDO::FETCH_ASSOC);

    $recentQuotations = $pdo->query("
        SELECT
            quote_number,
            client_name,
            status,
            total
        FROM quotations
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    $requestSummary = $pdo->query("
        SELECT
            COUNT(*)                      AS total,
            SUM(status = 'pending')       AS pending,
            SUM(status = 'processed')     AS processed,
            SUM(status = 'cancelled')     AS cancelled
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
        'quote_summary'     => $quoteSummary,
        'recent_quotations' => $recentQuotations,
        'request_summary'   => $requestSummary,
        'recent_requests'   => $recentRequests,
    ];
}


// ════════════════════════════════════════════════════════════════════════════
//  INSIGHTS  — ?action=insights
// ════════════════════════════════════════════════════════════════════════════

/**
 * JS renderProductInsightsTab() reads per tab:
 *   best: p.product_name, p.category, p.total_sold
 *   low:  p.name, p.category, p.stock
 *   recent: p.name, p.category, p.price, p.added_date
 */
function getProductInsights(PDO $pdo): array
{
    $bestSelling = $pdo->query("
        SELECT
            oi.product_name,
            p.category,
            SUM(oi.quantity) AS total_sold
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        JOIN orders o ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY oi.product_name, p.category
        ORDER BY total_sold DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $lowStock = $pdo->query("
        SELECT name, category, stock
        FROM products
        WHERE stock > 0 AND stock <= 5
        ORDER BY stock ASC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $recent = $pdo->query("
        SELECT
            name,
            category,
            price,
            DATE_FORMAT(created_at, '%b %d, %Y') AS added_date
        FROM products
        ORDER BY created_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'best_selling' => $bestSelling,
        'low_stock'    => $lowStock,
        'recent'       => $recent,
    ];
}

/**
 * JS renderPaymentOverview() reads:
 *   data.payment_overview.summary → { total_received, pending, failed }
 *   data.payment_overview.methods[] → { payment_method, count }
 */
function getPaymentOverview(PDO $pdo): array
{
    $amounts = $pdo->query("
        SELECT COALESCE(SUM(o.total_amount), 0) AS total_received
        FROM payments p
        JOIN orders o ON o.id = p.order_id
        WHERE p.payment_status IN ('verified', 'paid')
    ")->fetch(PDO::FETCH_ASSOC);

    $counts = $pdo->query("
        SELECT
            SUM(payment_status = 'pending') AS pending,
            SUM(payment_status = 'failed')  AS failed
        FROM payments
    ")->fetch(PDO::FETCH_ASSOC);

    $methods = $pdo->query("
        SELECT payment_method, COUNT(*) AS count
        FROM payments
        GROUP BY payment_method
        ORDER BY count DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'summary' => [
            'total_received' => $amounts['total_received'],
            'pending'        => (int)$counts['pending'],
            'failed'         => (int)$counts['failed'],
        ],
        'methods' => $methods,
    ];
}

/**
 * JS renderCustomerInsights() reads:
 *   data.customer_insights.top_customers[] →
 *     { customer_name, order_count, total_spent }
 */
function getCustomerInsights(PDO $pdo): array
{
    $topCustomers = $pdo->query("
        SELECT
            customer_name,
            customer_email,
            COUNT(*)          AS order_count,
            SUM(total_amount) AS total_spent
        FROM orders
        WHERE order_status NOT IN ('cancelled')
          AND payment_status = 'paid'
        GROUP BY customer_email, customer_name
        ORDER BY total_spent DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'top_customers' => $topCustomers,
    ];
}


// ════════════════════════════════════════════════════════════════════════════
//  ALERTS  — ?action=alerts
//  JS renderAlerts() reads: data.alerts →
//    { out_of_stock[], low_stock[], pending_payments,
//      failed_payments, failed_logins, stale_pending }
// ════════════════════════════════════════════════════════════════════════════
function getAlerts(PDO $pdo): array
{
    $outOfStock = $pdo->query("
        SELECT name FROM products
        WHERE stock = 0
        ORDER BY updated_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $lowStock = $pdo->query("
        SELECT name, stock FROM products
        WHERE stock > 0 AND stock <= 5
        ORDER BY stock ASC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $pendingPayments = (int)$pdo->query("
        SELECT COUNT(*) FROM payments WHERE payment_status = 'pending'
    ")->fetchColumn();

    $failedPayments = (int)$pdo->query("
        SELECT COUNT(*) FROM payments
        WHERE payment_status = 'failed'
          AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ")->fetchColumn();

    // NOTE: activity_logs uses 'CreatedAt' (capital C) — your real column name
    $failedLogins = (int)$pdo->query("
        SELECT COUNT(*) FROM activity_logs
        WHERE ActionType = 'login'
          AND Status     = 'Failed'
          AND CreatedAt  >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ")->fetchColumn();

    $stalePending = (int)$pdo->query("
        SELECT COUNT(*) FROM orders
        WHERE order_status = 'pending'
          AND created_at  <= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ")->fetchColumn();

    return [
        'out_of_stock'     => $outOfStock,
        'low_stock'        => $lowStock,
        'pending_payments' => $pendingPayments,
        'failed_payments'  => $failedPayments,
        'failed_logins'    => $failedLogins,
        'stale_pending'    => $stalePending,
    ];
}


// ════════════════════════════════════════════════════════════════════════════
//  ACTIVITY LOGS  — ?action=activity
//  JS renderActivityLogs() reads: rows[] →
//    { UserName, ActionType, ActionDetails, Status, log_time }
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
