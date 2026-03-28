<?php
session_start();
header('Content-Type: application/json');
require_once '../../connect/config.php';

$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['AdminID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$requestId = isset($_POST['request_id']) ? (int) $_POST['request_id'] : 0;
$action = trim($_POST['action'] ?? '');

if ($requestId <= 0 || !in_array($action, ['accept', 'reject'], true)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request details']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT username, email, password FROM pending_admins WHERE request_id = ? LIMIT 1");
    $stmt->execute([$requestId]);
    $pending = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pending) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Request not found']);
        exit;
    }

    if ($action === 'accept') {
        $email = $pending['email'];

        $stmt = $pdo->prepare("SELECT AdminID FROM admins WHERE Email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'This email is already registered as an admin.']);
            exit;
        }

        $insert = $pdo->prepare("INSERT INTO admins (FullName, Email, Password, Role, AccountStatus) VALUES (?, ?, ?, 'Admin', 'Active')");
        $insert->execute([
            $pending['username'],
            $pending['email'],
            $pending['password']
        ]);
    }

    $delete = $pdo->prepare("DELETE FROM pending_admins WHERE request_id = ?");
    $delete->execute([$requestId]);

    $pdo->commit();

    $message = $action === 'accept' ? 'Admin request approved and created successfully.' : 'Admin request rejected and removed.';
    echo json_encode(['status' => 'success', 'message' => $message]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Unable to process request: ' . $e->getMessage()]);
}
