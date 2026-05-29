<?php
// api/admin_site/inventory/get_delivered_quotations.php
// Get delivered/converted quotations for audit creation

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new RuntimeException('Database connection failed.');
    }

    // Admin authentication
    if (empty($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $offset = ($page - 1) * $perPage;

    // Build query for delivered quotations (converted status)
    $sql = "SELECT q.id, q.quote_number, q.client_name, q.contact_person, 
                   q.email, q.phone, q.address, q.total, q.created_at, q.status,
                   (SELECT COUNT(*) FROM quotation_items WHERE quotation_id = q.id) as item_count
            FROM quotations q
            WHERE q.status = 'converted'";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (q.quote_number LIKE :search 
                      OR q.client_name LIKE :search 
                      OR q.contact_person LIKE :search)";
        $params[':search'] = "%{$search}%";
    }

    $sql .= " ORDER BY q.created_at DESC LIMIT :offset, :perPage";

    $countSql = "SELECT COUNT(*) as total FROM quotations q WHERE q.status = 'converted'";
    if (!empty($search)) {
        $countSql .= " AND (quote_number LIKE :search OR client_name LIKE :search OR contact_person LIKE :search)";
    }

    $countStmt = $pdo->prepare($countSql);
    if (!empty($search)) {
        $countStmt->bindValue(':search', "%{$search}%");
    }
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%{$search}%");
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format quotations
    foreach ($quotations as &$q) {
        $q['total_formatted'] = number_format($q['total'], 2);
        $q['created_date'] = date('M d, Y', strtotime($q['created_at']));
    }

    echo json_encode([
        'success' => true,
        'quotations' => $quotations,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => (int)$total,
            'last_page' => ceil($total / $perPage)
        ]
    ]);
} catch (Exception $e) {
    error_log('get_delivered_quotations.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
