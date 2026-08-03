<?php
session_start();
header('Content-Type: application/json');

require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

$pdo = getDBConnection();

/*
|--------------------------------------------------
| SESSION CHECK
|--------------------------------------------------
*/
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Not logged in'
    ]);
    exit;
}

// Get current admin's details for logging
$stmt = $pdo->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
$stmt->execute([$_SESSION['admin_id']]);
$currentAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
$currentAdminName = $currentAdmin ? $currentAdmin['FullName'] : 'Unknown';

/*
|--------------------------------------------------
| METHOD CHECK
|--------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

/*
|--------------------------------------------------
| INPUT VALIDATION (with descriptive errors)
|--------------------------------------------------
| Each failure case reports exactly which field was
| bad, instead of a single generic "Invalid request
| details" message that hides the real cause.
*/
$rawRequestId = $_POST['request_id'] ?? null;
$action = trim($_POST['action'] ?? '');

if ($rawRequestId === null || $rawRequestId === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing request_id in submitted data.'
    ]);
    exit;
}

if (!ctype_digit((string) $rawRequestId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'request_id must be a positive whole number, received: ' . var_export($rawRequestId, true)
    ]);
    exit;
}

$requestId = (int) $rawRequestId;

if ($requestId <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'request_id must be greater than zero.'
    ]);
    exit;
}

if (!in_array($action, ['accept', 'reject'], true)) {
    echo json_encode([
        'status' => 'error',
        'message' => "action must be 'accept' or 'reject', received: " . var_export($action, true)
    ]);
    exit;
}

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------
    | GET PENDING REQUEST
    |--------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT request_id, username, email, password, submitted_at
        FROM pending_admins
        WHERE request_id = ?
        LIMIT 1
    ");
    $stmt->execute([$requestId]);
    $pending = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pending) {
        $pdo->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Request not found. It may have already been processed.'
        ]);
        exit;
    }

    /*
    |--------------------------------------------------
    | ACCEPT REQUEST
    |--------------------------------------------------
    */
    if ($action === 'accept') {

        // check duplicate email
        $stmt = $pdo->prepare("SELECT AdminID FROM admins WHERE Email = ? LIMIT 1");
        $stmt->execute([$pending['email']]);

        if ($stmt->fetch()) {
            $pdo->rollBack();
            
            // ❌ LOG FAILED ACTIVITY - Duplicate email
            logActivity(
                $pdo,
                $_SESSION['admin_id'],
                $currentAdminName,
                "Failed to accept pending admin request for '{$pending['username']}' (Email: {$pending['email']}) - Email already exists as admin",
                $requestId,
                "Pending Admin Management",
                "Failed"
            );
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Email already exists as admin.'
            ]);
            exit;
        }

        // insert admin
        $insert = $pdo->prepare("
            INSERT INTO admins (FullName, Email, Password, Role, AccountStatus)
            VALUES (?, ?, ?, 'Admin', 'Active')
        ");

        $insert->execute([
            $pending['username'],
            $pending['email'],
            $pending['password']
        ]);
        
        // Format submission date for log
        $submittedDate = date('Y-m-d h:i:s A', strtotime($pending['submitted_at']));
        
        // ✅ LOG SUCCESS - Accepted
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $currentAdminName,
            "Accepted pending admin request for '{$pending['username']}' (Email: {$pending['email']}, Submitted: {$submittedDate})",
            $requestId,
            "Pending Admin Management",
            "Success"
        );
    }

    /*
    |--------------------------------------------------
    | REJECT REQUEST
    |--------------------------------------------------
    */
    if ($action === 'reject') {
        // Format submission date for log
        $submittedDate = date('Y-m-d h:i:s A', strtotime($pending['submitted_at']));
        
        // ✅ LOG SUCCESS - Rejected
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $currentAdminName,
            "Rejected pending admin request for '{$pending['username']}' (Email: {$pending['email']}, Submitted: {$submittedDate})",
            $requestId,
            "Pending Admin Management",
            "Success"
        );
    }

    /*
    |--------------------------------------------------
    | DELETE REQUEST (BOTH ACCEPT & REJECT REMOVE IT)
    |--------------------------------------------------
    */
    $delete = $pdo->prepare("DELETE FROM pending_admins WHERE request_id = ?");
    $delete->execute([$requestId]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => $action === 'accept'
            ? 'Admin approved successfully.'
            : 'Admin request rejected.'
    ]);
    
} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // ❌ LOG FAILED ACTIVITY - Database error
    logActivity(
        $pdo,
        $_SESSION['admin_id'],
        $currentAdminName,
        "Pending admin request processing failed: " . $e->getMessage(),
        $requestId,
        "Pending Admin Management",
        "Failed"
    );

    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}