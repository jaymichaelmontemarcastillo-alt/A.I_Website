<?php
header('Content-Type: application/json');
require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM materials");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Database connection successful',
        'material_count' => $count['count']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
