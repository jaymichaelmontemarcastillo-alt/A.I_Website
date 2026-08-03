<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

$pdo = getDBConnection();

/*
|--------------------------------------------------------------------------
| LOGIN CHECK
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| METHOD CHECK
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

/*
|--------------------------------------------------------------------------
| INPUT VALIDATION (with descriptive errors)
|--------------------------------------------------------------------------
*/
$rawAdminId = $_POST['admin_id'] ?? null;
$action = trim($_POST['action'] ?? '');

if ($rawAdminId === null || $rawAdminId === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing admin_id in submitted data.'
    ]);
    exit;
}

if (!ctype_digit((string) $rawAdminId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'admin_id must be a positive whole number, received: ' . var_export($rawAdminId, true)
    ]);
    exit;
}

$adminId = (int) $rawAdminId;

if ($adminId <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'admin_id must be greater than zero.'
    ]);
    exit;
}

if ($action === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing action in submitted data.'
    ]);
    exit;
}

$allowedActions = ['change_role', 'set_status', 'delete'];
if (!in_array($action, $allowedActions, true)) {
    echo json_encode([
        'status' => 'error',
        'message' => "Unknown action: " . var_export($action, true)
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| PREVENT SELF EDIT
|--------------------------------------------------------------------------
*/
if ($adminId === (int) $_SESSION['admin_id']) {
    echo json_encode([
        'status' => 'error',
        'message' => 'You cannot modify your own account.'
    ]);
    exit;
}

// Get target admin details for logging
$stmt = $pdo->prepare("SELECT FullName, Role, AccountStatus FROM admins WHERE AdminID = ?");
$stmt->execute([$adminId]);
$targetAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
$targetAdminName = $targetAdmin ? $targetAdmin['FullName'] : 'Unknown User';

try {

    /*
    |--------------------------------------------------------------------------
    | CHANGE ROLE
    |--------------------------------------------------------------------------
    */
    if ($action === 'change_role') {

        $role = $_POST['role'] ?? '';
        $allowedRoles = ['Admin', 'Finance', 'Staff'];

        if (!in_array($role, $allowedRoles, true)) {
            echo json_encode([
                'status' => 'error',
                'message' => "Invalid role: " . var_export($role, true)
            ]);
            exit;
        }

        $oldRole = $targetAdmin['Role'] ?? 'Unknown';
        
        $stmt = $pdo->prepare("UPDATE admins SET Role = ? WHERE AdminID = ?");
        $stmt->execute([$role, $adminId]);

        // ✅ LOG ACTIVITY
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $currentAdminName,
            "Changed role of '{$targetAdminName}' from '{$oldRole}' to '{$role}'",
            $adminId,
            "User Management",
            "Success"
        );

        echo json_encode(['status' => 'success', 'message' => 'Role updated']);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */
    if ($action === 'set_status') {

        $status = $_POST['status'] ?? '';
        $allowedStatuses = ['Active', 'Disabled'];

        if (!in_array($status, $allowedStatuses, true)) {
            echo json_encode([
                'status' => 'error',
                'message' => "Invalid status: " . var_export($status, true)
            ]);
            exit;
        }

        $oldStatus = $targetAdmin['AccountStatus'] ?? 'Unknown';
        
        $stmt = $pdo->prepare("UPDATE admins SET AccountStatus = ? WHERE AdminID = ?");
        $stmt->execute([$status, $adminId]);

        // ✅ LOG ACTIVITY
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $currentAdminName,
            "Changed status of '{$targetAdminName}' from '{$oldStatus}' to '{$status}'",
            $adminId,
            "User Management",
            "Success"
        );

        echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    if ($action === 'delete') {

        $stmt = $pdo->prepare("DELETE FROM admins WHERE AdminID = ?");
        $stmt->execute([$adminId]);

        // ✅ LOG ACTIVITY
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $currentAdminName,
            "Deleted user: '{$targetAdminName}' (ID: {$adminId})",
            $adminId,
            "User Management",
            "Success"
        );

        echo json_encode(['status' => 'success', 'message' => 'User deleted']);
        exit;
    }

    // Should be unreachable due to the allow-list check above, kept as a safety net
    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
} catch (PDOException $e) {
    
    // ❌ LOG FAILED ACTIVITY
    logActivity(
        $pdo,
        $_SESSION['admin_id'],
        $currentAdminName,
        "User management action failed ({$action}): " . $e->getMessage(),
        $adminId,
        "User Management",
        "Failed"
    );
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}