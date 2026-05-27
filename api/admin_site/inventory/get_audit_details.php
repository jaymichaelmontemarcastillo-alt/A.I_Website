<?php
// api/admin_site/inventory/get_audit_details.php
// Get full audit details with inventory logs

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

$auditId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($auditId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid audit ID']);
    exit;
}

try {
    $pdo = getDBConnection();

    // Get audit data
    $stmt = $pdo->prepare("SELECT * FROM bom_audit WHERE id = :id");
    $stmt->execute([':id' => $auditId]);
    $audit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$audit) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Audit not found']);
        exit;
    }

    // Parse JSON fields
    $audit['items'] = json_decode($audit['items'], true) ?? [];
    $audit['materials'] = json_decode($audit['materials'], true) ?? [];
    $audit['rejects'] = json_decode($audit['rejects'], true) ?? [];
    $audit['signatures'] = json_decode($audit['signatures'], true) ?? [];

    // Get related inventory logs
    $logsStmt = $pdo->prepare("
        SELECT il.*, m.material_name, 
               COALESCE(a.FullName, 'Unknown') as admin_name
        FROM inventory_logs il
        JOIN materials m ON m.id = il.material_id
        LEFT JOIN admins a ON a.AdminID = il.admin_id
        WHERE il.audit_id = :audit_id
        ORDER BY il.created_at DESC
    ");
    $logsStmt->execute([':audit_id' => $auditId]);
    $logs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get audit log
    $auditLogStmt = $pdo->prepare("
        SELECT al.*, COALESCE(a.FullName, 'Unknown') as admin_name
        FROM audit_logs al
        LEFT JOIN admins a ON a.AdminID = al.admin_id
        WHERE al.audit_id = :audit_id
        ORDER BY al.created_at DESC
    ");
    $auditLogStmt->execute([':audit_id' => $auditId]);
    $auditLogs = $auditLogStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'audit' => $audit,
        'inventory_logs' => $logs,
        'audit_logs' => $auditLogs
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
