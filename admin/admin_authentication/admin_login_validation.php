<?php
session_start();
require_once '../../connect/config.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Please fill in all fields.';
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

        header("Location: ../pages/Dashboard.php");
        exit;
    }

    // ❌ ONE unified error (best practice)
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Wrong email or password.';
    header("Location: ../admin_login.php");
    exit;
}
