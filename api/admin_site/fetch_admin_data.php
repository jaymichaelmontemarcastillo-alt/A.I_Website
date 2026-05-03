<?php
session_start();
header('Content-Type: application/json');

require_once '../../connect/config.php';
$pdo = getDBConnection();

/*
|--------------------------------------------------
| SESSION CHECK (FIXED)
|--------------------------------------------------
*/
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Not logged in'
    ]);
    exit;
}

/*
|--------------------------------------------------
| FIX: CONSISTENT SESSION KEY
|--------------------------------------------------
*/
$adminID = $_SESSION['admin_id'];

try {

    $stmt = $pdo->prepare("
        SELECT AdminID, FullName, Email, Role, ProfilePicture, AccountStatus, CreatedAt
        FROM admins
        WHERE AdminID = ?
        LIMIT 1
    ");

    $stmt->execute([$adminID]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo json_encode([
            'status' => 'success',
            'data' => $admin
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Admin not found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
