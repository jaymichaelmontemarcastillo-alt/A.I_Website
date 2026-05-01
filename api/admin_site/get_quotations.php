<?php
header('Content-Type: application/json');
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();

    // Get pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $offset = ($page - 1) * $limit;

    // Build query
    $query = "SELECT * FROM quotations WHERE 1=1";
    $params = [];

    if ($status) {
        $query .= " AND status = ?";
        $params[] = $status;
    }

    if ($search) {
        $query .= " AND (quote_number LIKE ? OR client_name LIKE ? OR email LIKE ?)";
        $searchTerm = "%{$search}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    // Count total
    $countStmt = $pdo->prepare(str_replace("SELECT *", "SELECT COUNT(*) as count", $query));
    $countStmt->execute($params);
    $total = $countStmt->fetch()['count'];

    // Get data
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $quotations = $stmt->fetchAll();

    $totalPages = ceil($total / $limit);

    echo json_encode([
        'success' => true,
        'data' => $quotations,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
