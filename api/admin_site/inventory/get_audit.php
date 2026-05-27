<?php
// api/admin_site/materials/get_audit.php
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
    $stmt = $pdo->prepare("SELECT * FROM bom_audit WHERE id = :id");
    $stmt->execute([':id' => $auditId]);
    $audit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$audit) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Audit not found']);
        exit;
    }

    $audit['items'] = json_decode($audit['items'], true) ?? [];
    $audit['materials'] = json_decode($audit['materials'], true) ?? [];
    $audit['rejects'] = json_decode($audit['rejects'], true) ?? [];
    $audit['signatures'] = json_decode($audit['signatures'], true) ?? [];

    echo json_encode([
        'success' => true,
        'audit' => $audit
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
