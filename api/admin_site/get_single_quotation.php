<?php
// api/admin_site/get_single_quotation.php
// Get single quotation details with items

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../connect/config.php';

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

    // Get quotation details - INCLUDING audited and has_audit
    $stmt = $pdo->prepare("
        SELECT q.id, q.quote_number, q.client_name, q.contact_person, q.email, q.phone, q.address,
               q.total, q.tax, q.discount, q.notes, q.created_at, q.status, q.audited, q.audit_id,
               CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END as has_audit
        FROM quotations q
        LEFT JOIN bom_audit a ON a.id = q.audit_id
        WHERE q.id = :id
    ");
    $stmt->execute([':id' => $quotationId]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Quotation not found.']);
        exit;
    }

    // Get quotation items
    $stmtItems = $pdo->prepare("
        SELECT id, description, quantity, unit_price, total
        FROM quotation_items 
        WHERE quotation_id = :quotation_id
        ORDER BY id ASC
    ");
    $stmtItems->execute([':quotation_id' => $quotationId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // Format response
    echo json_encode([
        'success' => true,
        'data' => [
            'quotation' => $quotation,
            'items' => $items
        ]
    ]);
} catch (Exception $e) {
    error_log('get_single_quotation.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}