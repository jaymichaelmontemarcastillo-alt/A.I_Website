<?php
// api/admin_site/inventory/get_types.php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../../connect/config.php';

try {
    // Check authentication
    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized');
    }

    $pdo = getDBConnection();

    // Get distinct types from materials table
    $stmt = $pdo->query("
        SELECT DISTINCT type 
        FROM materials 
        WHERE type IS NOT NULL AND type != '' 
        ORDER BY type ASC
    ");

    $types = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'types' => $types
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
