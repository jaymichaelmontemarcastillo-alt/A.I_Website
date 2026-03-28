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
    $stmt = $pdo->prepare("SELECT AdminID, FullName, Email, Role, AccountStatus, CreatedAt FROM admins ORDER BY CreatedAt DESC");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $admins,
        'currentAdminId' => $_SESSION['AdminID'] ?? null,
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Unable to fetch admin list: ' . $e->getMessage()]);
}
