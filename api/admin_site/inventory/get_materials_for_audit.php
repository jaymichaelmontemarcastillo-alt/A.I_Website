<?php
// api/admin_site/materials/get_materials_for_audit.php
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

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, material_name, type, total_stock, unit_cost FROM materials ORDER BY material_name ASC");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'materials' => $materials
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
