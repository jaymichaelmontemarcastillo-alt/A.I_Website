<?php

// ✅ FIX: make session work across ALL folders (/admin, /admin/pages, etc.)
ini_set('session.cookie_path', '/');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

date_default_timezone_set('Asia/Manila');

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {

        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Please fill in all fields.';

        header("Location: ../admin_login.php");
        exit;
    }

    // ✅ GET ADMIN
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE Email = ? LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // ✅ CHECK LOGIN
    if ($admin && $admin['AccountStatus'] !== 'Disabled' && password_verify($password, $admin['Password'])) {

        session_regenerate_id(true);

        // ✅ UNIFIED SESSION KEYS (IMPORTANT)
        $_SESSION['admin_id'] = $admin['AdminID'];
        $_SESSION['full_name'] = $admin['FullName'];
        $_SESSION['email'] = $admin['Email'];
        $_SESSION['role'] = strtolower($admin['Role']);

        // update last login
        $update = $pdo->prepare("UPDATE admins SET LastLogin = NOW() WHERE AdminID = ?");
        $update->execute([$admin['AdminID']]);

        // log success
        logActivity(
            $pdo,
            $admin['AdminID'],
            $admin['FullName'],
            "Admin logged in",
            null,
            "Logins",
            "Success"
        );

        // ✅ ROLE-BASED REDIRECT
        switch ($_SESSION['role']) {

            case 'admin':
                header("Location: ../pages/Dashboard.php");
                break;

            case 'finance':
                header("Location: ../pages/Dashboard.php");
                break;

            case 'staff':
                header("Location: ../pages/Orders.php");
                break;

            default:
                header("Location: ../admin_login.php");
                break;
        }

        exit;
    }

    // ❌ FAILED LOGIN LOGGING
    logActivity(
        $pdo,
        $admin['AdminID'] ?? null,
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
