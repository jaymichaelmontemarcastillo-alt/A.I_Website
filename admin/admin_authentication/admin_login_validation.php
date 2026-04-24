<?php
session_start();
require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

// ✅ Set timezone
date_default_timezone_set('Asia/Manila');

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Please fill in all fields.';

        logActivity($pdo, null, $email, "Login attempt with empty fields", null, "Logins", "Failed");

        header("Location: ../admin_login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE Email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && $admin['AccountStatus'] !== 'Disabled' && password_verify($password, $admin['Password'])) {

        session_regenerate_id(true);

        $_SESSION['AdminID'] = $admin['AdminID'];
        $_SESSION['FullName'] = $admin['FullName'];
        $_SESSION['Email'] = $admin['Email'];
        $_SESSION['Role'] = $admin['Role'];

        $pdo->prepare("UPDATE admins SET LastLogin = NOW() WHERE AdminID = ?")
            ->execute([$admin['AdminID']]);

        logActivity(
            $pdo,
            $admin['AdminID'],
            $admin['FullName'],
            "Admin logged in",
            null,
            "Logins",
            "Success"
        );

        header("Location: ../pages/Dashboard.php");
        exit;
    }

    logActivity(
        $pdo,
        $admin ? $admin['AdminID'] : null,
        $email,
        "Failed login attempt",
        null,
        "Logins",
        "Failed"
    );

    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Wrong email or password.';
    header("Location: ../admin_login.php");
    exit;
}
