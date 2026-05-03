<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';

$pdo = getDBConnection();

/*
|--------------------------------------------------------------------------
| LOGIN CHECK (FIXED)
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
| INPUTS (FIXED NAMES)
|--------------------------------------------------------------------------
*/
$adminId = isset($_POST['admin_id']) ? (int) $_POST['admin_id'] : 0;
$action   = $_POST['action'] ?? '';

if ($adminId <= 0 || empty($action)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request details'
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

        if (!in_array($role, $allowedRoles)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
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

        if (!in_array($status, $allowedStatuses)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
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

    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
