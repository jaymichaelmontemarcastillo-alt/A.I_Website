<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    // Remove the WHERE status = 'active' if column doesn't exist
    $stmt = $pdo->query("
        SELECT id, material_name, type, unit_cost, total_stock 
        FROM materials 
        ORDER BY type, material_name
    ");
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $materials
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
