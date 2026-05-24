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
//  api/admin_site/materials/get_materials_logs.php (FIXED)
//  Returns paginated materials_logs with material & admin info
//  GET params:
//    material_id  – filter by material
//    change_type – add|subtract|order|return|adjust
//    location    – shop_stock|ph_stock|total_stock
//    date_from   – YYYY-MM-DD
//    date_to     – YYYY-MM-DD
//    page        – int (default 1)
//    per_page    – int (default 15, max 50)
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

// ── Params ───────────────────────────────────────────────────
$materialId = isset($_GET['material_id'])  ? (int) $_GET['material_id']  : 0;
$changeType = trim($_GET['change_type']  ?? '');
$location   = trim($_GET['location']     ?? '');
$dateFrom   = trim($_GET['date_from']    ?? '');
$dateTo     = trim($_GET['date_to']      ?? '');
$page       = max(1, (int) ($_GET['page']     ?? 1));
$perPage    = min(50, max(1, (int) ($_GET['per_page'] ?? 15)));
$offset     = ($page - 1) * $perPage;

// ── Validate change_type ─────────────────────────────────────
$validTypes = ['add', 'subtract', 'order', 'return', 'adjust'];
if ($changeType && !in_array($changeType, $validTypes, true)) {
    $changeType = '';
}

// ── Validate location ────────────────────────────────────────
$validLocations = ['shop_stock', 'ph_stock', 'total_stock'];
if ($location && !in_array($location, $validLocations, true)) {
    $location = '';
}

// ── Build WHERE ──────────────────────────────────────────────
$where  = [];
$params = [];

if ($materialId > 0) {
    $where[]            = 'ml.material_id = :material_id';
    $params[':material_id'] = $materialId;
}

if ($changeType) {
    $where[]            = 'ml.change_type = :change_type';
    $params[':change_type'] = $changeType;
}

if ($location) {
    $where[]         = 'ml.location = :location';
    $params[':location'] = $location;
}

if ($dateFrom) {
    $where[]          = 'DATE(ml.created_at) >= :date_from';
    $params[':date_from'] = $dateFrom;
}

if ($dateTo) {
    $where[]        = 'DATE(ml.created_at) <= :date_to';
    $params[':date_to'] = $dateTo;
}

$whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

try {
    $pdo = getDBConnection();

    // ── Verify required tables exist ─────────────────────────
    $logTableCheck = $pdo->query("SHOW TABLES LIKE 'materials_logs'")->fetch();
    if (!$logTableCheck) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'materials_logs table not found']);
        exit;
    }

    $matTableCheck = $pdo->query("SHOW TABLES LIKE 'materials'")->fetch();
    if (!$matTableCheck) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'materials table not found']);
        exit;
    }

    // ── Count ────────────────────────────────────────────────
    $countSQL  = "SELECT COUNT(*) FROM materials_logs ml $whereSQL";
    $countStmt = $pdo->prepare($countSQL);
    $countStmt->execute($params);
    $total = (int) ($countStmt->fetchColumn() ?? 0);

    // ── Logs ─────────────────────────────────────────────────
    // NOTE: Verify your admins table column name — if it's 'id' instead of 'AdminID', adjust the JOIN
    $sql = "SELECT
                ml.id,
                ml.material_id,
                m.material_name,
                m.type               AS material_type,
                ml.location,
                ml.change_type,
                ml.quantity,
                ml.previous_stock,
                ml.new_stock,
                ml.admin_id,
                COALESCE(a.FullName, 'Unknown Admin') AS admin_name,
                ml.note,
                ml.created_at
            FROM materials_logs ml
            JOIN materials m ON m.id = ml.material_id
            LEFT JOIN admins a ON a.AdminID = ml.admin_id
            $whereSQL
            ORDER BY ml.created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric fields with null safety
    foreach ($logs as &$log) {
        $log['quantity']       = (int) ($log['quantity'] ?? 0);
        $log['previous_stock'] = (int) ($log['previous_stock'] ?? 0);
        $log['new_stock']      = (int) ($log['new_stock'] ?? 0);
    }
    unset($log);

    echo json_encode([
        'success' => true,
        'logs'    => $logs,
        'pagination' => [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ],
    ]);
} catch (PDOException $e) {
    error_log('get_materials_logs.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'debug'   => 'PDOException',
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
    ]);
} catch (Exception $e) {
    error_log('get_materials_logs.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'debug'   => 'Exception',
        'message' => $e->getMessage(),
    ]);
}
