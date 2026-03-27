<?php
session_start();
require_once '../../connect/config.php';
$pdo = getDBConnection();

header('Content-Type: application/json');

function sendResponse($status, $message)
{
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request.');
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    sendResponse('error', 'All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse('error', 'Invalid email address.');
}

if ($password !== $confirm_password) {
    sendResponse('error', 'Passwords do not match.');
}

if (
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) ||
    strlen($password) < 8
) {
    sendResponse('error', 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.');
}

// Check pending_admins
$stmt = $pdo->prepare("SELECT * FROM pending_admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) sendResponse('error', 'You already have a pending registration. Wait for admin approval.');

// Check admins
$stmt = $pdo->prepare("SELECT * FROM admins WHERE Email = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) sendResponse('error', 'Email is already registered.');

// Insert into pending_admins
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO pending_admins (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hashedPassword]);

sendResponse('success', 'Registration submitted successfully! Wait for admin approval.');
