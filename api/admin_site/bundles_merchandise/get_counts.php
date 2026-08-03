<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();
    
    $stmt = $pdo->query("SELECT type, COUNT(*) as count FROM bundles_merchandise GROUP BY type");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $counts = ['bundles' => 0, 'merchandise' => 0];
    foreach ($results as $row) {
        if ($row['type'] === 'bundle') $counts['bundles'] = (int)$row['count'];
        if ($row['type'] === 'merchandise') $counts['merchandise'] = (int)$row['count'];
    }
    
    echo json_encode([
        'success' => true,
        'bundles' => $counts['bundles'],
        'merchandise' => $counts['merchandise']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}