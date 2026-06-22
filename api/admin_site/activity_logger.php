<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');

function logActivity($pdo, $userID, $userName, $actionDetails, $referenceID, $actionType, $status)
{
    try {
        // PHP generates timestamp in PH time
        $createdAt = date('Y-m-d H:i:s'); // 24-hour DB storage

        // Validate inputs
        $userName = trim($userName) ?: 'Unknown';
        $actionDetails = trim($actionDetails) ?: 'Unknown Action';
        $referenceID = trim($referenceID) ?: null;
        $actionType = trim($actionType) ?: 'Other';
        $status = in_array($status, ['Success', 'Failed']) ? $status : 'Success';

        $stmt = $pdo->prepare("
            INSERT INTO activity_logs 
            (UserID, UserName, ActionDetails, ReferenceID, ActionType, Status, CreatedAt)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $success = $stmt->execute([
            $userID ?: null,
            $userName,
            $actionDetails,
            $referenceID,
            $actionType,
            $status,
            $createdAt
        ]);

        if (!$success) {
            error_log("Activity Log Insert Failed: " . json_encode($stmt->errorInfo()));
        }
    } catch (Exception $e) {
        error_log("Activity Log Error: " . $e->getMessage());
    }
}
