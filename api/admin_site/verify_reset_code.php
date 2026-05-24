<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : '';
    $code = isset($input['code']) ? trim($input['code']) : '';

    if (empty($email) || empty($code)) {
        throw new Exception('Email and code are required');
    }

    if (strlen($code) !== 6 || !is_numeric($code)) {
        throw new Exception('Invalid code format');
    }

    // Check if code exists in session
    if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_code_email'])) {
        throw new Exception('No reset code was requested. Please start over.');
    }

    // Check if code has expired
    if (time() > $_SESSION['reset_code_expire']) {
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_code_email']);
        unset($_SESSION['reset_code_time']);
        unset($_SESSION['reset_code_expire']);
        throw new Exception('Code has expired. Please request a new one.');
    }

    // Check if email matches
    if ($_SESSION['reset_code_email'] !== $email) {
        throw new Exception('Email does not match the one code was sent to');
    }

    // Verify code
    if ($_SESSION['reset_code'] !== $code) {
        throw new Exception('Invalid code. Please try again.');
    }

    // Code is valid - set verification flag
    $_SESSION['reset_verified'] = true;
    $_SESSION['reset_verified_email'] = $email;

    exit(json_encode([
        'success' => true,
        'message' => 'Code verified successfully'
    ]));
} catch (Exception $e) {
    error_log('verify_reset_code.php: ' . $e->getMessage());
    exit(json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]));
}
