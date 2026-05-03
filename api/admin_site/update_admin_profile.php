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
$stmt = $pdo->prepare("SELECT Password FROM admins WHERE AdminID = ?");
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

    if ($password) {
        $query .= ", Password = ?";
        $params[] = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($profilePath) {
        $query .= ", ProfilePicture = ?";
        $params[] = $profilePath;
    }

    $query .= " WHERE AdminID = ?";
    $params[] = $adminID;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully'
    ]);
} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
