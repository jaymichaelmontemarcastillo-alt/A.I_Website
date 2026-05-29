<?php

/**
 * Customer Management API - Main Fetch
 * api/admin_site/customers/fetch_customers.php
 */

header('Content-Type: application/json');
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get parameters
    $filter = $_GET['filter'] ?? 'all';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    // First, get all customers with their calculated values
    $baseSql = "
        SELECT 
            client_name AS name,
            email,
            phone,
            address,
            COUNT(*) AS total_orders,
            MAX(created_at) AS last_order,
            SUM(total) AS total_spent,
            SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) AS has_converted,
            SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS has_expired,
            SUM(CASE WHEN status IN ('draft', 'sent') THEN 1 ELSE 0 END) AS has_pending
        FROM quotations
        WHERE email IS NOT NULL AND email != ''
        GROUP BY email
    ";

    // Apply filter conditions
    $filterCondition = "";
    switch ($filter) {
        case 'high_value':
            $filterCondition = "HAVING total_spent > 50000";
            break;
        case 'delivered':
            $filterCondition = "HAVING has_converted > 0";
            break;
        case 'cancelled':
            $filterCondition = "HAVING has_expired > 0";
            break;
        case 'pending':
            $filterCondition = "HAVING has_pending > 0";
            break;
        default:
            $filterCondition = "";
            break;
    }

    // Main query with pagination
    $sql = $baseSql . " " . $filterCondition . " ORDER BY last_order DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM (" . $baseSql . " " . $filterCondition . ") as filtered";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $total = (int)$countStmt->fetchColumn();

    // Get statistics for all customers (without filter)
    $statsSql = "
        SELECT 
            COUNT(DISTINCT email) AS total_customers,
            COUNT(DISTINCT CASE WHEN total_spent > 50000 THEN email END) AS high_value,
            COUNT(DISTINCT CASE WHEN has_converted > 0 THEN email END) AS delivered,
            COUNT(DISTINCT CASE WHEN has_expired > 0 THEN email END) AS cancelled,
            COUNT(DISTINCT CASE WHEN has_pending > 0 THEN email END) AS pending
        FROM (
            SELECT 
                email,
                SUM(total) AS total_spent,
                SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) AS has_converted,
                SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) AS has_expired,
                SUM(CASE WHEN status IN ('draft', 'sent') THEN 1 ELSE 0 END) AS has_pending
            FROM quotations
            WHERE email IS NOT NULL AND email != ''
            GROUP BY email
        ) sub
    ";
    $statsStmt = $pdo->query($statsSql);
    $statsResult = $statsStmt->fetch(PDO::FETCH_ASSOC);

    $stats = [
        'total_customers' => (int)($statsResult['total_customers'] ?? 0),
        'has_quotations' => (int)($statsResult['total_customers'] ?? 0),
        'high_value' => (int)($statsResult['high_value'] ?? 0),
        'delivered' => (int)($statsResult['delivered'] ?? 0),
        'cancelled' => (int)($statsResult['cancelled'] ?? 0),
        'pending' => (int)($statsResult['pending'] ?? 0)
    ];

    // Format customers
    $customers = array_map(function ($c) {
        return [
            'name' => (string)($c['name'] ?? ''),
            'email' => (string)($c['email'] ?? ''),
            'phone' => (string)($c['phone'] ?? ''),
            'address' => (string)($c['address'] ?? ''),
            'total_orders' => (int)($c['total_orders'] ?? 0),
            'last_order' => $c['last_order'] ?? null,
            'total_spent' => (float)($c['total_spent'] ?? 0)
        ];
    }, $customers);

    echo json_encode([
        'success' => true,
        'customers' => $customers,
        'stats' => $stats,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => max(1, ceil($total / $limit)),
            'total_rows' => $total,
            'per_page' => $limit
        ]
    ]);
} catch (PDOException $e) {
    error_log("Customer API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
