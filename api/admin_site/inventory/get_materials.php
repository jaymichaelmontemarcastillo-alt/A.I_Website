<?php

declare(strict_types=1);

// ── DEBUG WRAPPER (remove once working) ─────────────────────
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'debug'   => 'PHP Error',
        'message' => $errstr,
        'file'    => $errfile,
        'line'    => $errline,
        'errno'   => $errno,
    ]);
    exit;
});
set_exception_handler(function (Throwable $e): void {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'debug'   => 'Uncaught Exception',
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString(),
    ]);
    exit;
});
register_shutdown_function(function (): void {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'debug'   => 'Fatal Error',
            'message' => $err['message'],
            'file'    => $err['file'],
            'line'    => $err['line'],
        ]);
    }
});
// ── END DEBUG WRAPPER ────────────────────────────────────────

// ============================================================
//  api/admin_site/materials/get_materials.php (FIXED)
//  Returns paginated material list + stats for the inventory page
//  GET params:
//    search       – material name substring
//    status       – in_stock | low_stock | out_of_stock
//    sort         – stock_asc | stock_desc | name_asc | name_desc
//    page         – int (default 1)
//    per_page     – int (default 10, max 50)
// ============================================================
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

// ── Constants ────────────────────────────────────────────────
define('LOW_STOCK_THRESHOLD', 5);

// ── Params ───────────────────────────────────────────────────
$search  = trim($_GET['search']   ?? '');
$status  = trim($_GET['status']   ?? '');
$sort    = trim($_GET['sort']     ?? 'name_asc');
$page    = max(1, (int) ($_GET['page']     ?? 1));
$perPage = min(50, max(1, (int) ($_GET['per_page'] ?? 10)));
$offset  = ($page - 1) * $perPage;

// ── Sort whitelist ───────────────────────────────────────────
$sortMap = [
    'stock_asc'  => 'm.total_stock ASC,  m.material_name ASC',
    'stock_desc' => 'm.total_stock DESC, m.material_name ASC',
    'name_asc'   => 'm.material_name ASC',
    'name_desc'  => 'm.material_name DESC',
];
$orderBy = $sortMap[$sort] ?? 'm.material_name ASC';

// ── Build WHERE clauses ──────────────────────────────────────
$where  = [];
$params = [];

if ($search !== '') {
    $where[]          = 'm.material_name LIKE :search';
    $params[':search'] = '%' . $search . '%';
}

switch ($status) {
    case 'in_stock':
        $where[] = 'm.total_stock > ' . LOW_STOCK_THRESHOLD;
        break;
    case 'low_stock':
        $where[] = 'm.total_stock > 0 AND m.total_stock <= ' . LOW_STOCK_THRESHOLD;
        break;
    case 'out_of_stock':
        $where[] = 'm.total_stock = 0';
        break;
}

$whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

try {
    $pdo = getDBConnection();

    // ── Verify table exists ──────────────────────────────────
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'materials'")->fetch();
    if (!$tableCheck) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Materials table not found']);
        exit;
    }

    // ── Stats (always over full table) ───────────────────────
    $statsSQL = "SELECT
                    SUM(total_stock)                                       AS total_stock,
                    COUNT(CASE WHEN total_stock > " . LOW_STOCK_THRESHOLD . " THEN 1 END) AS in_stock,
                    COUNT(CASE WHEN total_stock > 0 AND total_stock <= " . LOW_STOCK_THRESHOLD . " THEN 1 END) AS low_stock,
                    COUNT(CASE WHEN total_stock = 0  THEN 1 END) AS out_of_stock
                 FROM materials";

    $statsResult = $pdo->query($statsSQL)->fetch(PDO::FETCH_ASSOC);

    // ── FIX: Handle nullable stats result ─────────────────────
    $stats = $statsResult ?? [
        'total_stock'  => 0,
        'in_stock'     => 0,
        'low_stock'    => 0,
        'out_of_stock' => 0,
    ];

    // ── Count for pagination ─────────────────────────────────
    $countSQL  = "SELECT COUNT(*) FROM materials m $whereSQL";
    $countStmt = $pdo->prepare($countSQL);
    $countStmt->execute($params);
    $total = (int) ($countStmt->fetchColumn() ?? 0);

    // ── Materials ────────────────────────────────────────────
    $sql  = "SELECT
                m.id,
                m.material_name,
                m.type,
                m.shop_stock,
                m.ph_stock,
                m.total_stock,
                m.total_cost,
                m.unit_cost,
                m.pieces_per_pack,
                m.remarks,
                CASE
                    WHEN m.total_stock = 0                   THEN 'out_of_stock'
                    WHEN m.total_stock <= " . LOW_STOCK_THRESHOLD . " THEN 'low_stock'
                    ELSE                                         'in_stock'
                END AS stock_status
             FROM materials m
             $whereSQL
             ORDER BY $orderBy
             LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric fields with null safety
    foreach ($materials as &$material) {
        $material['shop_stock']    = (int) ($material['shop_stock'] ?? 0);
        $material['ph_stock']      = (int) ($material['ph_stock'] ?? 0);
        $material['total_stock']   = (int) ($material['total_stock'] ?? 0);
        $material['total_cost']    = (float) ($material['total_cost'] ?? 0);
        $material['unit_cost']     = (float) ($material['unit_cost'] ?? 0);
        $material['pieces_per_pack'] = (int) ($material['pieces_per_pack'] ?? 0);
    }
    unset($material);

    echo json_encode([
        'success'  => true,
        'stats'    => [
            'total_stock'  => (int) ($stats['total_stock'] ?? 0),
            'in_stock'     => (int) ($stats['in_stock'] ?? 0),
            'low_stock'    => (int) ($stats['low_stock'] ?? 0),
            'out_of_stock' => (int) ($stats['out_of_stock'] ?? 0),
        ],
        'materials' => $materials,
        'pagination' => [
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page' => $page,
            'last_page'   => (int) ceil($total / $perPage),
        ],
    ]);
} catch (PDOException $e) {
    error_log('get_materials.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'debug'   => 'PDOException',
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
    ]);
} catch (Exception $e) {
    error_log('get_materials.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'debug'   => 'Exception',
        'message' => $e->getMessage(),
    ]);
}
