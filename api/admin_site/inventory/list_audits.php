<?php
// api/admin_site/inventory/list_audits.php
// List all audits with pagination

declare(strict_types=1);
header('Content-Type: application/json');

require_once '../../../connect/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = min(50, max(1, (int) ($_GET['per_page'] ?? 10)));
$offset = ($page - 1) * $perPage;
$search = trim($_GET['search'] ?? '');

try {
    $pdo = getDBConnection();

    // Build where clause
    $where = ["is_completed = 1"];
    $params = [];

    if ($search) {
        $where[] = "(items LIKE :search OR signatures LIKE :search OR id = :id_search)";
        $params[':search'] = '%' . $search . '%';
        $params[':id_search'] = (int) $search;
    }

    $whereSQL = "WHERE " . implode(" AND ", $where);

    // Count total
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM bom_audit $whereSQL");
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    // Get audits
    $stmt = $pdo->prepare("
        SELECT id, items, materials, rejects, signatures, 
               total_material_cost, total_reject_cost, total_amount_due, profit, 
               created_at, auto_compute
        FROM bom_audit
        $whereSQL
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset
    ");

    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $audits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Parse JSON fields and add summary
    foreach ($audits as &$audit) {
        $items = json_decode($audit['items'], true) ?? [];
        $materials = json_decode($audit['materials'], true) ?? [];
        $rejects = json_decode($audit['rejects'], true) ?? [];
        $signatures = json_decode($audit['signatures'], true) ?? [];

        $audit['items_count'] = count($items);
        $audit['materials_count'] = count($materials);
        $audit['rejects_count'] = count($rejects);
        $audit['created_by'] = $signatures['created_by'] ?? 'Unknown';
        $audit['item_names'] = array_column($items, 'name');
        $audit['material_names'] = array_column($materials, 'name');
    }

    echo json_encode([
        'success' => true,
        'audits' => $audits,
        'pagination' => [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
