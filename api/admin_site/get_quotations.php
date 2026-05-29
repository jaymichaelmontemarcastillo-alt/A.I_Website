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

    // Build query - ADDED audited, audit_id, audited_at, audited_by fields
    $query = "SELECT id, quote_number, client_name, contact_person, email, phone, 
                     address, subtotal, tax, discount, total, notes, status, 
                     pdf_url, created_at, expires_at, updated_at,
                     audited, audit_id, audited_at, audited_by 
              FROM quotations WHERE 1=1";
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
    $countStmt = $pdo->prepare(str_replace("FROM quotations", "FROM quotations", $query));
    $countQuery = "SELECT COUNT(*) as count FROM quotations WHERE 1=1";

    if ($status) {
        $countQuery .= " AND status = ?";
    }
    if ($search) {
        $countQuery .= " AND (quote_number LIKE ? OR client_name LIKE ? OR email LIKE ?)";
    }

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get data with ORDER BY and LIMIT
    $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
