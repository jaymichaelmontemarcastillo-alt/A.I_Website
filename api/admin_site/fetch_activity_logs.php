<?php
require_once '../../connect/config.php';

// ✅ Set timezone for correct display
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

$pdo = getDBConnection();

try {
    $stmt = $pdo->prepare("
        SELECT 
            al.UserName, 
            al.ActionDetails, 
            al.ReferenceID, 
            al.Status, 
            al.CreatedAt,
            a.ProfilePicture
        FROM activity_logs al
        LEFT JOIN admins a ON al.UserID = a.AdminID
        ORDER BY al.CreatedAt DESC
    ");
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $logs
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
