<?php
session_start();
require_once '../../connect/config.php';
$pdo = getDBConnection();

if (!isset($_SESSION['AdminID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$adminID = $_POST['AdminID'] ?? '';
$fullName = $_POST['FullName'] ?? '';
$email = $_POST['Email'] ?? '';
$currentPassword = $_POST['CurrentPassword'] ?? '';
$password = $_POST['Password'] ?? '';

if (!$adminID) {
    echo json_encode(['status' => 'error', 'message' => 'Admin ID is required']);
    exit;
}

if (!$currentPassword) {
    echo json_encode(['status' => 'error', 'message' => 'Current password is required to confirm changes.']);
    exit;
}

$stmt = $pdo->prepare("SELECT Password FROM admins WHERE AdminID = ?");
$stmt->execute([$adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || !password_verify($currentPassword, $admin['Password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
    exit;
}

if ($password) {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long and include uppercase, lowercase, and a number.']);
        exit;
    }
}

// Handle profile picture upload
$profilePath = null;
if (isset($_FILES['ProfilePicture']) && $_FILES['ProfilePicture']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['ProfilePicture']['tmp_name'];
    $fileName = time() . '_' . basename($_FILES['ProfilePicture']['name']);
    $uploadDir = '../../uploads/admins/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $destination = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmp, $destination)) {
        $profilePath = 'uploads/admins/' . $fileName; // path to save in DB
    }
}

// Update admin data
try {
    $query = "UPDATE admins SET FullName = ?, Email = ?" .
        ($password ? ", Password = ?" : "") .
        ($profilePath ? ", ProfilePicture = ?" : "") .
        " WHERE AdminID = ?";

    $stmt = $pdo->prepare($query);

    $params = [$fullName, $email];
    if ($password) $params[] = password_hash($password, PASSWORD_DEFAULT);
    if ($profilePath) $params[] = $profilePath;
    $params[] = $adminID;

    $stmt->execute($params);

    echo json_encode(['status' => 'success', 'message' => 'Admin updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
