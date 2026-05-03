<?php
// api/dashboard_api.php
// Dashboard API - returns JSON data for all dashboard sections

require_once '../../connect/config.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

$pdo = getDBConnection();

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'kpi':
            echo json_encode(getDashboardKPIs($pdo));
            break;
        case 'daily_sales':
            echo json_encode(getDailySales($pdo));
            break;
        case 'monthly_sales':
            echo json_encode(getMonthlySales($pdo));
            break;
        case 'top_products':
            echo json_encode(getTopProducts($pdo));
            break;
        case 'sales_by_category':
            echo json_encode(getSalesByCategory($pdo));
            break;
        case 'recent_orders':
            echo json_encode(getRecentOrders($pdo));
            break;
        case 'product_insights':
            echo json_encode(getProductInsights($pdo));
            break;
        case 'payment_overview':
            echo json_encode(getPaymentOverview($pdo));
            break;
        case 'customer_insights':
            echo json_encode(getCustomerInsights($pdo));
            break;
        case 'quotations_requests':
            echo json_encode(getQuotationsRequests($pdo));
            break;
        case 'alerts':
            echo json_encode(getAlerts($pdo));
            break;
        case 'activity_logs':
            echo json_encode(getActivityLogs($pdo));
            break;
        case 'all':
        default:
            echo json_encode([
                'kpi'                 => getDashboardKPIs($pdo),
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

// ─────────────────────────────────────────────
// KPI CARDS
// ─────────────────────────────────────────────
function getDashboardKPIs(PDO $pdo): array
{
    // Revenue
    $revenue = $pdo->query("
        SELECT
            COALESCE(SUM(total_amount), 0)                                                            AS all_time,
            COALESCE(SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) THEN total_amount END), 0) AS this_month,
            COALESCE(SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total_amount END), 0)            AS today
        FROM orders
        WHERE order_status NOT IN ('cancelled') AND payment_status = 'paid'
    ")->fetch(PDO::FETCH_ASSOC);

    // Orders by status
    $orders = $pdo->query("
        SELECT
            COUNT(*)                                                                AS total,
            SUM(CASE WHEN order_status = 'pending'   THEN 1 ELSE 0 END)           AS pending,
            SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END)           AS completed,
            SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END)           AS cancelled,
            SUM(CASE WHEN order_status = 'processing' OR order_status = 'packed' OR order_status = 'shipped' THEN 1 ELSE 0 END) AS in_progress
        FROM orders
    ")->fetch(PDO::FETCH_ASSOC);

    // Customers
    $customers = $pdo->query("
        SELECT
            COUNT(DISTINCT customer_email)                                          AS total,
            COUNT(DISTINCT CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) THEN customer_email END) AS new_this_month
        FROM orders
    ")->fetch(PDO::FETCH_ASSOC);

    // Stock alerts
    $stock = $pdo->query("
        SELECT
            SUM(CASE WHEN stock > 0 AND stock <= 5 THEN 1 ELSE 0 END)  AS low_stock,
            SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END)                  AS out_of_stock
        FROM products
    ")->fetch(PDO::FETCH_ASSOC);

    // Month-over-month revenue change
    $momStmt = $pdo->query("
        SELECT
            COALESCE(SUM(CASE WHEN MONTH(created_at) = MONTH(NOW())     AND YEAR(created_at) = YEAR(NOW())     THEN total_amount END), 0) AS current_month,
            COALESCE(SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) - 1 AND YEAR(created_at) = YEAR(NOW())     THEN total_amount END), 0) AS last_month
        FROM orders
        WHERE order_status NOT IN ('cancelled') AND payment_status = 'paid'
    ");
    $mom = $momStmt->fetch(PDO::FETCH_ASSOC);
    $revenueChange = $mom['last_month'] > 0
        ? round((($mom['current_month'] - $mom['last_month']) / $mom['last_month']) * 100, 1)
        : 0;

    return [
        'revenue'        => $revenue,
        'revenue_change' => $revenueChange,
        'orders'         => $orders,
        'customers'      => $customers,
        'stock'          => $stock,
    ];
}

// ─────────────────────────────────────────────
// DAILY SALES (last 7 days)
// ─────────────────────────────────────────────
function getDailySales(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            DATE(created_at)              AS sale_date,
            DAYNAME(created_at)           AS day_name,
            COALESCE(SUM(total_amount), 0) AS total
        FROM orders
        WHERE
            order_status NOT IN ('cancelled')
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(created_at), DAYNAME(created_at)
        ORDER BY sale_date ASC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fill missing days with 0
    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $dayName = date('D', strtotime("-$i days"));
        $found = array_filter($rows, fn($r) => $r['sale_date'] === $date);
        $found = reset($found);
        $result[] = [
            'date'  => $date,
            'label' => $dayName,
            'total' => $found ? (float)$found['total'] : 0,
        ];
    }
    return $result;
}

// ─────────────────────────────────────────────
// MONTHLY SALES (last 12 months)
// ─────────────────────────────────────────────
function getMonthlySales(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            DATE_FORMAT(created_at, '%Y-%m')  AS month_key,
            DATE_FORMAT(created_at, '%b %Y')  AS label,
            COALESCE(SUM(total_amount), 0)     AS total
        FROM orders
        WHERE
            order_status NOT IN ('cancelled')
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
        GROUP BY month_key, label
        ORDER BY month_key ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ─────────────────────────────────────────────
// TOP SELLING PRODUCTS (bar chart, top 8)
// ─────────────────────────────────────────────
function getTopProducts(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            oi.product_name,
            SUM(oi.quantity)              AS total_qty,
            SUM(oi.subtotal)              AS total_revenue
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY oi.product_name
        ORDER BY total_qty DESC
        LIMIT 8
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ─────────────────────────────────────────────
// SALES BY CATEGORY (donut chart)
// ─────────────────────────────────────────────
function getSalesByCategory(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            p.category,
            COALESCE(SUM(oi.subtotal), 0) AS total
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        JOIN orders o   ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY p.category
        ORDER BY total DESC
        LIMIT 6
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ─────────────────────────────────────────────
// RECENT ORDERS (last 10)
// ─────────────────────────────────────────────
function getRecentOrders(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            order_number,
            customer_name,
            customer_email,
            DATE_FORMAT(created_at, '%b %d, %Y') AS order_date,
            total_amount,
            order_status,
            payment_status,
            payment_method
        FROM orders
        ORDER BY created_at DESC
        LIMIT 10
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ─────────────────────────────────────────────
// PRODUCT INSIGHTS
// ─────────────────────────────────────────────
function getProductInsights(PDO $pdo): array
{
    // Top 5 best-selling
    $bestSelling = $pdo->query("
        SELECT
            oi.product_name,
            p.category,
            SUM(oi.quantity)  AS total_sold,
            SUM(oi.subtotal)  AS revenue
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        JOIN orders o ON o.id = oi.order_id
        WHERE o.order_status NOT IN ('cancelled')
        GROUP BY oi.product_name, p.category
        ORDER BY total_sold DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Low stock (stock 1-5)
    $lowStock = $pdo->query("
        SELECT name, category, stock
        FROM products
        WHERE stock > 0 AND stock <= 5
        ORDER BY stock ASC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Out of stock
    $outOfStock = $pdo->query("
        SELECT name, category
        FROM products
        WHERE stock = 0
        ORDER BY updated_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Recently added (last 5)
    $recent = $pdo->query("
        SELECT name, category, price, stock,
               DATE_FORMAT(created_at, '%b %d, %Y') AS added_date
        FROM products
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'best_selling' => $bestSelling,
        'low_stock'    => $lowStock,
        'out_of_stock' => $outOfStock,
        'recent'       => $recent,
    ];
}

// ─────────────────────────────────────────────
// PAYMENT OVERVIEW
// ─────────────────────────────────────────────
function getPaymentOverview(PDO $pdo): array
{
    // From payments table
    $summary = $pdo->query("
        SELECT
            COUNT(*)                                                             AS total_payments,
            COALESCE(SUM(CASE WHEN payment_status IN ('verified','paid') THEN 1 END), 0) AS verified,
            COALESCE(SUM(CASE WHEN payment_status = 'pending'  THEN 1 END), 0)           AS pending,
            COALESCE(SUM(CASE WHEN payment_status = 'failed'   THEN 1 END), 0)           AS failed
        FROM payments
    ")->fetch(PDO::FETCH_ASSOC);

    // Total verified amount
    $amounts = $pdo->query("
        SELECT
            COALESCE(SUM(o.total_amount), 0) AS total_received
        FROM payments p
        JOIN orders o ON o.id = p.order_id
        WHERE p.payment_status IN ('verified', 'paid')
    ")->fetch(PDO::FETCH_ASSOC);

    // Payment method distribution
    $methods = $pdo->query("
        SELECT
            payment_method,
            COUNT(*) AS count
        FROM payments
        GROUP BY payment_method
        ORDER BY count DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'summary'        => array_merge($summary, $amounts),
        'methods'        => $methods,
    ];
}

// ─────────────────────────────────────────────
// CUSTOMER INSIGHTS
// ─────────────────────────────────────────────
function getCustomerInsights(PDO $pdo): array
{
    // New customers last 7 days (by email from orders)
    $newCustomers = $pdo->query("
        SELECT
            DATE(created_at)               AS reg_date,
            DAYNAME(created_at)            AS day_name,
            COUNT(DISTINCT customer_email) AS count
        FROM orders
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(created_at), DAYNAME(created_at)
        ORDER BY reg_date ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Top 5 customers by spending
    $topCustomers = $pdo->query("
        SELECT
            customer_name,
            customer_email,
            COUNT(*)                       AS order_count,
            SUM(total_amount)              AS total_spent
        FROM orders
        WHERE order_status NOT IN ('cancelled') AND payment_status = 'paid'
        GROUP BY customer_email, customer_name
        ORDER BY total_spent DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    // New staff/admin users last 7 days
    $newUsers = $pdo->query("
        SELECT COUNT(*) AS count
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ")->fetch(PDO::FETCH_ASSOC);

    return [
        'new_customers' => $newCustomers,
        'top_customers' => $topCustomers,
        'new_users'     => (int)$newUsers['count'],
    ];
}

// ─────────────────────────────────────────────
// QUOTATIONS & REQUESTS
// ─────────────────────────────────────────────
function getQuotationsRequests(PDO $pdo): array
{
    $quoteSummary = $pdo->query("
        SELECT
            COUNT(*)                                                    AS total,
            SUM(CASE WHEN status = 'draft'     THEN 1 ELSE 0 END)     AS draft,
            SUM(CASE WHEN status = 'sent'      THEN 1 ELSE 0 END)     AS sent,
            SUM(CASE WHEN status = 'accepted'  THEN 1 ELSE 0 END)     AS accepted,
            SUM(CASE WHEN status = 'expired'   THEN 1 ELSE 0 END)     AS expired,
            SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END)     AS converted
        FROM quotations
    ")->fetch(PDO::FETCH_ASSOC);

    $requestSummary = $pdo->query("
        SELECT
            COUNT(*)                                                       AS total,
            SUM(CASE WHEN status = 'pending'   THEN 1 ELSE 0 END)        AS pending,
            SUM(CASE WHEN status = 'processed' THEN 1 ELSE 0 END)        AS processed,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END)        AS cancelled
        FROM requests
    ")->fetch(PDO::FETCH_ASSOC);

    $recentRequests = $pdo->query("
        SELECT
            request_number,
            client_name,
            email,
            status,
            DATE_FORMAT(created_at, '%b %d, %Y') AS request_date
        FROM requests
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    $recentQuotations = $pdo->query("
        SELECT
            quote_number,
            client_name,
            email,
            status,
            total,
            DATE_FORMAT(created_at, '%b %d, %Y') AS quote_date
        FROM quotations
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    return [
        'quote_summary'    => $quoteSummary,
        'request_summary'  => $requestSummary,
        'recent_requests'  => $recentRequests,
        'recent_quotations' => $recentQuotations,
    ];
}

// ─────────────────────────────────────────────
// ALERTS PANEL
// ─────────────────────────────────────────────
function getAlerts(PDO $pdo): array
{
    // Low stock products (stock 1-5)
    $lowStock = $pdo->query("
        SELECT name, stock
        FROM products
        WHERE stock > 0 AND stock <= 5
        ORDER BY stock ASC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Out of stock
    $outOfStock = $pdo->query("
        SELECT name
        FROM products
        WHERE stock = 0
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Pending payments
    $pendingPayments = $pdo->query("
        SELECT COUNT(*) AS count FROM payments WHERE payment_status = 'pending'
    ")->fetch(PDO::FETCH_ASSOC);

    // Failed payments (last 7 days)
    $failedPayments = $pdo->query("
        SELECT COUNT(*) AS count FROM payments
        WHERE payment_status = 'failed'
          AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ")->fetch(PDO::FETCH_ASSOC);

    // Failed login attempts from activity_logs
    $failedLogins = $pdo->query("
        SELECT COUNT(*) AS count FROM activity_logs
        WHERE ActionType = 'login' AND Status = 'Failed'
          AND CreatedAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ")->fetch(PDO::FETCH_ASSOC);

    // Pending orders not updated in 24h
    $stalePending = $pdo->query("
        SELECT COUNT(*) AS count FROM orders
        WHERE order_status = 'pending'
          AND created_at <= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ")->fetch(PDO::FETCH_ASSOC);

    return [
        'low_stock'       => $lowStock,
        'out_of_stock'    => $outOfStock,
        'pending_payments' => (int)$pendingPayments['count'],
        'failed_payments' => (int)$failedPayments['count'],
        'failed_logins'   => (int)$failedLogins['count'],
        'stale_pending'   => (int)$stalePending['count'],
    ];
}

// ─────────────────────────────────────────────
// ACTIVITY LOGS (last 10)
// ─────────────────────────────────────────────
function getActivityLogs(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            UserName,
            ActionDetails,
            ActionType,
            ReferenceID,
            Status,
            DATE_FORMAT(CreatedAt, '%b %d, %Y %H:%i') AS log_time
        FROM activity_logs
        ORDER BY CreatedAt DESC
        LIMIT 10
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
