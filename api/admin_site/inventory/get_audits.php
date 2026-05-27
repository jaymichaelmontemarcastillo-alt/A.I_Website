<?php
// api/admin_site/materials/get_audits.php
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

try {
    $pdo = getDBConnection();

    // Count total
    $countStmt = $pdo->query("SELECT COUNT(*) FROM bom_audit WHERE is_completed = 1");
    $total = (int) $countStmt->fetchColumn();

    // Get audits
    $stmt = $pdo->prepare("
        SELECT id, items, materials, rejects, signatures, total_material_cost, total_reject_cost, total_amount_due, profit, created_at
        FROM bom_audit
        WHERE is_completed = 1
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $audits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Parse JSON fields
    foreach ($audits as &$audit) {
        $audit['items'] = json_decode($audit['items'], true) ?? [];
        $audit['materials'] = json_decode($audit['materials'], true) ?? [];
        $audit['rejects'] = json_decode($audit['rejects'], true) ?? [];
        $audit['signatures'] = json_decode($audit['signatures'], true) ?? [];
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
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
