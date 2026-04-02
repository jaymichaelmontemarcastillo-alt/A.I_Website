<?php
session_start();
require_once '../../connect/config.php'; // getDBConnection() should return PDO

$pdo = getDBConnection();

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and trim input values
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['signup_error'] = "All fields are required.";
        header("Location: ../admin_register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = "Invalid email address.";
        header("Location: ../admin_register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: ../admin_register.php");
        exit();
    }

    // Check if email already exists in pending_admins
    $stmt = $pdo->prepare("SELECT * FROM pending_admins WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['signup_error'] = "You already have a pending registration. Wait for admin approval.";
        header("Location: ../admin_register.php");
        exit();
    }

    // Check if email already exists in admins
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE Email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['signup_error'] = "Email is already registered.";
        header("Location: ../admin_register.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into pending_admins
    $stmt = $pdo->prepare("INSERT INTO pending_admins (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);

    $_SESSION['signup_success'] = "Registration submitted successfully! Wait for admin approval.";
    header("Location: ../admin_register.php");
    exit();
} else {
    $_SESSION['signup_error'] = "Invalid request.";
    header("Location: ../admin_register.php");
    exit();
}
