<?php
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

$sessionId = session_id();

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM wishlists WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    $count = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'count' => (int)$count
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>