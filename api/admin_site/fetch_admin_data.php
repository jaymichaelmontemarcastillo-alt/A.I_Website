<?php
session_start();
require_once '../../connect/config.php';
$pdo = getDBConnection();

if (!isset($_SESSION['AdminID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$adminID = $_SESSION['AdminID'];

$stmt = $pdo->prepare("
    SELECT AdminID, FullName, Email, Role, ProfilePicture, AccountStatus, CreatedAt
    FROM admins
    WHERE AdminID = ?
");
$stmt->execute([$adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo json_encode(['status' => 'success', 'data' => $admin]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Admin not found']);
}
