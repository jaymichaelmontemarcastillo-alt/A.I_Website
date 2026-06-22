<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';

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

        $stmt = $pdo->prepare("UPDATE admins SET Role = ? WHERE AdminID = ?");
        $stmt->execute([$role, $adminId]);

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

        $stmt = $pdo->prepare("UPDATE admins SET AccountStatus = ? WHERE AdminID = ?");
        $stmt->execute([$status, $adminId]);

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

        echo json_encode(['status' => 'success', 'message' => 'User deleted']);
        exit;
    }

    // Should be unreachable due to the allow-list check above, kept as a safety net
    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
