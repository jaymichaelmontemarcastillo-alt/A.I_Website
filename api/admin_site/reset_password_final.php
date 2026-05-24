<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';
require_once __DIR__ . '/activity_logger.php';

try {
    // Verify that code was verified
    if (!isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
        throw new Exception('Email verification is required before resetting password');
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : '';
    $password = isset($input['password']) ? $input['password'] : '';

    if (empty($email) || empty($password)) {
        throw new Exception('Email and password are required');
    }

    // Check if email matches verified email
    if ($_SESSION['reset_verified_email'] !== $email) {
        throw new Exception('Verification email does not match');
    }

    // Validate password requirements
    if (strlen($password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }

    if (!preg_match('/[A-Z]/', $password)) {
        throw new Exception('Password must contain at least one uppercase letter');
    }

    if (!preg_match('/[a-z]/', $password)) {
        throw new Exception('Password must contain at least one lowercase letter');
    }

    if (!preg_match('/[0-9]/', $password)) {
        throw new Exception('Password must contain at least one number');
    }

    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        throw new Exception('Password must contain at least one special character');
    }

    // Get database connection
    $pdo = getDBConnection();

    // Find admin by email
    $stmt = $pdo->prepare('SELECT AdminID, FullName FROM admins WHERE Email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        throw new Exception('Admin account not found');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password in database
    $updateStmt = $pdo->prepare('UPDATE admins SET Password = ? WHERE AdminID = ?');
    $updateStmt->execute([$hashedPassword, $admin['AdminID']]);

    // Log the password reset activity
    logActivity(
        $pdo,
        $admin['AdminID'],
        $admin['FullName'],
        "Password reset via forgot password",
        null,
        "Account",
        "Success"
    );

    // Clear reset session variables
    unset($_SESSION['reset_code']);
    unset($_SESSION['reset_code_email']);
    unset($_SESSION['reset_code_time']);
    unset($_SESSION['reset_code_expire']);
    unset($_SESSION['reset_verified']);
    unset($_SESSION['reset_verified_email']);

    exit(json_encode([
        'success' => true,
        'message' => 'Password reset successfully'
    ]));
} catch (Exception $e) {
    error_log('reset_password_final.php: ' . $e->getMessage());
    exit(json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]));
}
