<?php

/**
 * Customer Search API
 * api/admin_site/customers/search_customers.php
 */

header('Content-Type: application/json');
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get parameters
    $search = trim($_GET['search'] ?? '');
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    // If no search term, return empty results
    if (empty($search)) {
        echo json_encode([
            'success' => true,
            'customers' => [],
            'search_term' => '',
            'results_count' => 0,
            'pagination' => [
                'current_page' => 1,
                'total_pages' => 1,
                'total_rows' => 0,
                'per_page' => $limit
            ]
        ]);
        exit;
    }

    // Search term with wildcards
    $searchTerm = "%{$search}%";

    // PDO does not support reusing the same named placeholder multiple times
    // in a single prepared statement — use unique names for each occurrence.
    $sql = "
        SELECT 
            client_name AS name,
            email,
            phone,
            address,
            COUNT(*) AS total_orders,
            MAX(created_at) AS last_order,
            SUM(total) AS total_spent
        FROM quotations
        WHERE email IS NOT NULL AND email != ''
            AND (
                client_name LIKE :search1
                OR email     LIKE :search2
                OR phone     LIKE :search3
                OR address   LIKE :search4
            )
        GROUP BY email
        ORDER BY last_order DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search4', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':limit',   $limit,      PDO::PARAM_INT);
    $stmt->bindValue(':offset',  $offset,     PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count query also needs unique placeholders for the same reason
    $countSql = "
        SELECT COUNT(DISTINCT email) AS total
        FROM quotations
        WHERE email IS NOT NULL AND email != ''
            AND (
                client_name LIKE :search1
                OR email     LIKE :search2
                OR phone     LIKE :search3
                OR address   LIKE :search4
            )
    ";

    $countStmt = $pdo->prepare($countSql);
    $countStmt->bindValue(':search1', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search2', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search3', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search4', $searchTerm, PDO::PARAM_STR);
    $countStmt->execute();
    $total = (int)$countStmt->fetchColumn();

    error_log("Search term: '{$search}', Pattern: '{$searchTerm}', Results: {$total}");

    // Format customers
    $customers = array_map(function ($c) {
        return [
            'name'         => (string)($c['name']         ?? ''),
            'email'        => (string)($c['email']        ?? ''),
            'phone'        => (string)($c['phone']        ?? ''),
            'address'      => (string)($c['address']      ?? ''),
            'total_orders' => (int)   ($c['total_orders'] ?? 0),
            'last_order'   =>          $c['last_order']   ?? null,
            'total_spent'  => (float) ($c['total_spent']  ?? 0)
        ];
    }, $customers);

    echo json_encode([
        'success'       => true,
        'customers'     => $customers,
        'search_term'   => $search,
        'results_count' => $total,
        'pagination'    => [
            'current_page' => $page,
            'total_pages'  => max(1, ceil($total / $limit)),
            'total_rows'   => $total,
            'per_page'     => $limit
        ]
    ]);
} catch (PDOException $e) {
    error_log("Customer Search API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage()
    ]);
}
