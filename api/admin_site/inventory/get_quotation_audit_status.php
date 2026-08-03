<?php
// api/admin_site/inventory/get_quotation_audit_status.php
// Check if a quotation has an audit and return audit data

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

    if (empty($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $quotationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($quotationId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid quotation ID.']);
        exit;
    }

    // Check if quotation has an audit
    $stmt = $pdo->prepare("
        SELECT q.id, q.audit_id, q.audited,
               a.id as audit_exists
        FROM quotations q
        LEFT JOIN bom_audit a ON a.id = q.audit_id
        WHERE q.id = :id
    ");
    $stmt->execute([':id' => $quotationId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Quotation not found.']);
        exit;
    }

    $hasAudit = $result['audit_exists'] !== null && $result['audited'] == 1;

    echo json_encode([
        'success' => true,
        'has_audit' => $hasAudit,
        'audit_id' => $hasAudit ? (int)$result['audit_id'] : null,
        'quotation_id' => (int)$result['id']
    ]);
} catch (Exception $e) {
    error_log('get_quotation_audit_status.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}