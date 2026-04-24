<?php
require_once '../../connect/config.php';

header('Content-Type: application/json');

try {
    $conn = getDBConnection(); // Make sure this returns PDO

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $status = $_GET['status'] ?? null;
    $search = $_GET['search'] ?? null;

    $where = [];
    $params = [];

    // ======================
    // FILTER: STATUS
    // ======================
    if ($status && $status !== 'all') {
        $where[] = "status = :status";
        $params[':status'] = $status;
    }

    // ======================
    // FILTER: SEARCH
    // ======================
    if ($search) {
        $where[] = "(quote_number LIKE :search OR client_name LIKE :search OR email LIKE :search)";
        $params[':search'] = "%$search%";
    }

    $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // ======================
    // COUNT QUERY
    // ======================
    $countSql = "SELECT COUNT(*) FROM quotations $whereClause";
    $countStmt = $conn->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();

    // ======================
    // MAIN QUERY
    // ======================
    $sql = "SELECT id, quote_number, client_name, contact_person, email, phone,
                   status, subtotal, tax, discount, total, created_at, expires_at
            FROM quotations
            $whereClause
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);

    // Bind all params
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Bind limit & offset (IMPORTANT: must be INT)
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();

    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ======================
    // PAGINATION
    // ======================
    $totalPages = ceil($total / $limit);

    echo json_encode([
        'success' => true,
        'data' => $quotations,
        'pagination' => [
            'total' => (int)$total,
            'page' => (int)$page,
            'limit' => (int)$limit,
            'totalPages' => (int)$totalPages
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
