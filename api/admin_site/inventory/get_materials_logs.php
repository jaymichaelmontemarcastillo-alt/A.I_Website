<?php
// api/admin_site/inventory/get_materials_logs.php
// COMPLETE REWRITE - FIXED VERSION

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

// Params
$materialId = isset($_GET['material_id']) ? (int) $_GET['material_id'] : 0;
$changeType = trim($_GET['change_type'] ?? '');
$location = trim($_GET['location'] ?? '');
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');
$auditId = isset($_GET['audit_id']) ? (int) $_GET['audit_id'] : 0;
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = min(50, max(1, (int) ($_GET['per_page'] ?? 15)));
$offset = ($page - 1) * $perPage;

$validTypes = ['add', 'subtract', 'order', 'return', 'adjust'];
if ($changeType && !in_array($changeType, $validTypes, true)) {
    $changeType = '';
}

$validLocations = ['shop_stock', 'ph_stock', 'total_stock'];
if ($location && !in_array($location, $validLocations, true)) {
    $location = '';
}

$where = [];
$params = [];

if ($materialId > 0) {
    $where[] = 'il.material_id = :material_id';
    $params[':material_id'] = $materialId;
}

if ($changeType) {
    $where[] = 'il.change_type = :change_type';
    $params[':change_type'] = $changeType;
}

if ($location) {
    $where[] = 'il.location = :location';
    $params[':location'] = $location;
}

if ($auditId > 0) {
    $where[] = 'il.audit_id = :audit_id';
    $params[':audit_id'] = $auditId;
}

if ($dateFrom) {
    $where[] = 'DATE(il.created_at) >= :date_from';
    $params[':date_from'] = $dateFrom;
}

if ($dateTo) {
    $where[] = 'DATE(il.created_at) <= :date_to';
    $params[':date_to'] = $dateTo;
}

$whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

try {
    $pdo = getDBConnection();

    // Check which log table to use (prefer inventory_logs)
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'inventory_logs'");
    $useInventoryLogs = $tableCheck && $tableCheck->rowCount() > 0;

    if (!$useInventoryLogs) {
        // Fallback to materials_logs
        $tableCheck2 = $pdo->query("SHOW TABLES LIKE 'materials_logs'");
        if (!$tableCheck2 || $tableCheck2->rowCount() === 0) {
            echo json_encode([
                'success' => true,
                'logs' => [],
                'pagination' => ['total' => 0, 'per_page' => $perPage, 'current_page' => $page, 'last_page' => 0],
                'message' => 'No log tables found'
            ]);
            exit;
        }

        // Count total
        $countSQL = "SELECT COUNT(*) FROM materials_logs il $whereSQL";
        $countStmt = $pdo->prepare($countSQL);
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetchColumn() ?? 0);

        // Get logs
        $sql = "SELECT
                    il.id,
                    il.material_id,
                    m.material_name,
                    m.type AS material_type,
                    il.change_type,
                    il.quantity,
                    il.previous_stock,
                    il.new_stock,
                    il.admin_id,
                    COALESCE(a.FullName, 'Unknown Admin') AS admin_name,
                    il.note,
                    il.audit_id,
                    il.created_at,
                    il.location
                FROM materials_logs il
                JOIN materials m ON m.id = il.material_id
                LEFT JOIN admins a ON a.AdminID = il.admin_id
                $whereSQL
                ORDER BY il.created_at DESC
                LIMIT :limit OFFSET :offset";
    } else {
        // Use inventory_logs with proper columns
        // Check if location column exists in inventory_logs
        $colCheck = $pdo->query("SHOW COLUMNS FROM inventory_logs LIKE 'location'");
        $hasLocation = $colCheck && $colCheck->rowCount() > 0;

        // Count total
        $countSQL = "SELECT COUNT(*) FROM inventory_logs il $whereSQL";
        $countStmt = $pdo->prepare($countSQL);
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetchColumn() ?? 0);

        // Build select based on available columns
        $locationSelect = $hasLocation ? "il.location," : "'total_stock' as location,";

        $sql = "SELECT
                    il.id,
                    il.material_id,
                    m.material_name,
                    m.type AS material_type,
                    il.change_type,
                    il.quantity,
                    il.previous_stock,
                    il.new_stock,
                    il.admin_id,
                    COALESCE(a.FullName, 'Unknown Admin') AS admin_name,
                    il.note,
                    il.audit_id,
                    il.created_at,
                    $locationSelect
                    ba.items as audit_items
                FROM inventory_logs il
                JOIN materials m ON m.id = il.material_id
                LEFT JOIN admins a ON a.AdminID = il.admin_id
                LEFT JOIN bom_audit ba ON ba.id = il.audit_id
                $whereSQL
                ORDER BY il.created_at DESC
                LIMIT :limit OFFSET :offset";
    }

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric fields
    foreach ($logs as &$log) {
        $log['quantity'] = (int) ($log['quantity'] ?? 0);
        $log['previous_stock'] = (int) ($log['previous_stock'] ?? 0);
        $log['new_stock'] = (int) ($log['new_stock'] ?? 0);
        $log['audit_id'] = $log['audit_id'] ? (int) $log['audit_id'] : null;
        $log['location'] = $log['location'] ?? 'total_stock';
        $log['delta'] = $log['new_stock'] - $log['previous_stock'];
    }

    echo json_encode([
        'success' => true,
        'logs' => $logs,
        'pagination' => [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ],
    ]);
} catch (PDOException $e) {
    error_log('get_materials_logs.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
exit;
