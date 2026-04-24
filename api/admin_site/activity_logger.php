<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');

function logActivity($pdo, $userID, $userName, $actionDetails, $referenceID, $actionType, $status)
{
    try {
        // PHP generates timestamp in PH time
        $createdAt = date('Y-m-d H:i:s'); // 24-hour DB storage
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs 
            (UserID, UserName, ActionDetails, ReferenceID, ActionType, Status, CreatedAt)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userID,
            $userName,
            $actionDetails,
            $referenceID,
            $actionType,
            $status,
            $createdAt
        ]);
    } catch (Exception $e) {
        error_log("Activity Log Error: " . $e->getMessage());
    }
}
