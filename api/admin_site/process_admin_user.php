<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['AdminID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$adminId = isset($_POST['admin_id']) ? (int) $_POST['admin_id'] : 0;
$action = trim($_POST['action'] ?? '');

if ($adminId <= 0 || !$action) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request details']);
    exit;
}

if ($adminId === (int) $_SESSION['AdminID']) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot modify your own account here.']);
    exit;
}

$allowedRoles = ['Admin', 'Finance', 'Staff'];
$allowedStatuses = ['Active', 'Disabled'];

try {
    if ($action === 'change_role') {
        $role = trim($_POST['role'] ?? '');
        if (!in_array($role, $allowedRoles, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role selected']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE admins SET Role = ? WHERE AdminID = ?");
        $stmt->execute([$role, $adminId]);
        echo json_encode(['status' => 'success', 'message' => 'User role updated successfully.']);
        exit;
    }

    if ($action === 'set_status') {
        $status = trim($_POST['status'] ?? '');
        if (!in_array($status, $allowedStatuses, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid status value']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE admins SET AccountStatus = ? WHERE AdminID = ?");
        $stmt->execute([$status, $adminId]);
        echo json_encode(['status' => 'success', 'message' => 'User account status updated successfully.']);
        exit;
    }

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM admins WHERE AdminID = ?");
        $stmt->execute([$adminId]);
        echo json_encode(['status' => 'success', 'message' => 'Admin user deleted successfully.']);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Unable to process user update: ' . $e->getMessage()]);
}
