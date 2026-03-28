<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';

$pdo = getDBConnection();

if (!isset($_SESSION['AdminID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT request_id, username, email, submitted_at FROM pending_admins ORDER BY submitted_at ASC");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $requests]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Unable to fetch pending requests: ' . $e->getMessage()]);
}
