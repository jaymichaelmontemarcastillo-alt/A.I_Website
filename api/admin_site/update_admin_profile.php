<?php
session_start();
header('Content-Type: application/json');

require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

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
| SECURITY FIX: NEVER TRUST POST ADMIN ID
|--------------------------------------------------
*/
$adminID = $_SESSION['admin_id'];

$fullName = trim($_POST['FullName'] ?? '');
$email = trim($_POST['Email'] ?? '');
$currentPassword = $_POST['CurrentPassword'] ?? '';
$password = $_POST['Password'] ?? '';

if (!$currentPassword) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Current password is required.'
    ]);
    exit;
}

/*
|--------------------------------------------------
| VERIFY CURRENT PASSWORD
|--------------------------------------------------
*/
$stmt = $pdo->prepare("SELECT Password, FullName FROM admins WHERE AdminID = ?");
$stmt->execute([$adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || !password_verify($currentPassword, $admin['Password'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Current password is incorrect.'
    ]);
    exit;
}

/*
|--------------------------------------------------
| VALIDATE NEW PASSWORD
|--------------------------------------------------
*/
if ($password) {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Password must be at least 8 chars, include upper, lower, number.'
        ]);
        exit;
    }
}

/*
|--------------------------------------------------
| HANDLE PROFILE IMAGE
|--------------------------------------------------
*/
$profilePath = null;

if (isset($_FILES['ProfilePicture']) && $_FILES['ProfilePicture']['error'] === UPLOAD_ERR_OK) {

    $fileTmp = $_FILES['ProfilePicture']['tmp_name'];
    $fileName = time() . '_' . basename($_FILES['ProfilePicture']['name']);
    $uploadDir = '../../uploads/admins/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $destination = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmp, $destination)) {
        $profilePath = 'uploads/admins/' . $fileName;
    }
}

/*
|--------------------------------------------------
| UPDATE DATA
|--------------------------------------------------
*/
try {

    $query = "UPDATE admins SET FullName = ?, Email = ?";

    $params = [$fullName, $email];

    $changes = [];
    
    if ($password) {
        $query .= ", Password = ?";
        $params[] = password_hash($password, PASSWORD_DEFAULT);
        $changes[] = "password updated";
    }

    if ($profilePath) {
        $query .= ", ProfilePicture = ?";
        $params[] = $profilePath;
        $changes[] = "profile picture updated";
    }

    $query .= " WHERE AdminID = ?";
    $params[] = $adminID;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // ✅ LOG ACTIVITY
    $actionDetails = "Profile updated";
    if (!empty($changes)) {
        $actionDetails .= " (" . implode(", ", $changes) . ")";
    }
    
    logActivity(
        $pdo,
        $adminID,
        $admin['FullName'],
        $actionDetails,
        null,
        "Profile Management",
        "Success"
    );

    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully'
    ]);
} catch (PDOException $e) {
    
    // ❌ LOG FAILED ACTIVITY
    logActivity(
        $pdo,
        $adminID,
        $admin['FullName'],
        "Profile update failed: " . $e->getMessage(),
        null,
        "Profile Management",
        "Failed"
    );
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}