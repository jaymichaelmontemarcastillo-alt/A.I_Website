<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

try {
    $pdo = getDBConnection();

    // Get unique categories from materials
    $stmt = $pdo->query("SELECT DISTINCT category FROM materials WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get unique types as well
    $typeStmt = $pdo->query("SELECT DISTINCT type FROM materials WHERE type IS NOT NULL AND type != '' ORDER BY type");
    $types = $typeStmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'categories' => $categories,
        'types' => $types
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
