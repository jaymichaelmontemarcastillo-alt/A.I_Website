<?php
//api/admin_site/customers/fetch_customers.php
header('Content-Type: application/json');
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search = $_GET['search'] ?? '';
    $filter = $_GET['filter'] ?? 'all';
    $page   = max(1, (int)($_GET['page'] ?? 1));
    $limit  = (int)($_GET['limit'] ?? 20);
    $offset = ($page - 1) * $limit;

    // ── WHERE clause for orders ───────────────────────────────────────────────
    $where  = "1=1";
    $params = [];

    if ($search) {
        $where .= " AND (
        o.customer_name  LIKE :s1 OR
        o.customer_email LIKE :s2 OR
        o.customer_phone LIKE :s3
    )";

        $params[':s1'] = "%$search%";
        $params[':s2'] = "%$search%";
        $params[':s3'] = "%$search%";
    }

    // ── Filter-specific HAVING ────────────────────────────────────────────────
    $havingClause = "";
    switch ($filter) {
        case 'orders':
            $havingClause = "HAVING total_orders > 0";
            break;
        case 'quotations':
            $havingClause = "HAVING total_quotations > 0";
            break;
        case 'active':
            $where .= " AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            break;
        case 'highvalue':
            $havingClause = "HAVING total_orders > 1";
            break;
    }

    // ── Main customer query ───────────────────────────────────────────────────
    $sql = "
        SELECT
            o.customer_name                               AS name,
            o.customer_email                              AS email,
            o.customer_phone                              AS phone,
            COUNT(DISTINCT o.id)                          AS total_orders,
            COALESCE(q.quote_count, 0)                    AS total_quotations,
            COALESCE(SUM(o.total_amount), 0)              AS total_spent,
            GREATEST(
                MAX(o.created_at),
                COALESCE(q.last_quote, '1970-01-01')
            )                                             AS last_activity,
            CASE
                WHEN COUNT(DISTINCT o.id) > 1 THEN 'Returning Customer'
                WHEN COUNT(DISTINCT o.id) = 1 THEN 'Buyer'
                ELSE 'Quotation Only'
            END AS customer_type
        FROM orders o
        LEFT JOIN (
            SELECT
                email,
                COUNT(*)        AS quote_count,
                MAX(created_at) AS last_quote
            FROM quotations
            WHERE email IS NOT NULL
            GROUP BY email
        ) q ON q.email COLLATE utf8mb4_general_ci = o.customer_email COLLATE utf8mb4_general_ci
        WHERE $where
        GROUP BY o.customer_email, o.customer_name, o.customer_phone
        $havingClause
        ORDER BY last_activity DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Pagination count ──────────────────────────────────────────────────────
    $countSql = "
        SELECT COUNT(DISTINCT o.customer_email)
        FROM orders o
        WHERE $where
    ";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // ── Global summary stats ──────────────────────────────────────────────────
    $summaryStmt = $pdo->query("
        SELECT
            COUNT(DISTINCT customer_email)                           AS total_customers,
            COUNT(DISTINCT customer_email)                           AS active_customers,
            0                                                        AS quotation_only,
            COUNT(DISTINCT CASE WHEN order_count > 1
                                THEN customer_email END)             AS repeat_customers
        FROM (
            SELECT customer_email, COUNT(*) AS order_count
            FROM orders
            GROUP BY customer_email
        ) sub
    ");
    $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC) ?: [
        'total_customers'  => 0,
        'active_customers' => 0,
        'quotation_only'   => 0,
        'repeat_customers' => 0,
    ];

    echo json_encode([
        'success'    => true,
        'customers'  => $customers,
        'summary'    => $summary,
        'pagination' => [
            'current_page' => $page,
            'total_pages'  => $total > 0 ? (int)ceil($total / $limit) : 1,
            'total_rows'   => $total,
            'per_page'     => $limit,
        ],
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
