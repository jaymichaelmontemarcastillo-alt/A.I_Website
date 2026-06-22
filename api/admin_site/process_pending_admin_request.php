<?php
session_start();
header('Content-Type: application/json');

require_once '../../connect/config.php';
$pdo = getDBConnection();

/*
|--------------------------------------------------
| SESSION CHECK
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
| METHOD CHECK
|--------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

/*
|--------------------------------------------------
| INPUT VALIDATION (with descriptive errors)
|--------------------------------------------------
| Each failure case reports exactly which field was
| bad, instead of a single generic "Invalid request
| details" message that hides the real cause.
*/
$rawRequestId = $_POST['request_id'] ?? null;
$action = trim($_POST['action'] ?? '');

if ($rawRequestId === null || $rawRequestId === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing request_id in submitted data.'
    ]);
    exit;
}

if (!ctype_digit((string) $rawRequestId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'request_id must be a positive whole number, received: ' . var_export($rawRequestId, true)
    ]);
    exit;
}

$requestId = (int) $rawRequestId;

if ($requestId <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'request_id must be greater than zero.'
    ]);
    exit;
}

if (!in_array($action, ['accept', 'reject'], true)) {
    echo json_encode([
        'status' => 'error',
        'message' => "action must be 'accept' or 'reject', received: " . var_export($action, true)
    ]);
    exit;
}

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------
    | GET PENDING REQUEST
    |--------------------------------------------------
    */
    $stmt = $pdo->prepare("
        SELECT username, email, password
        FROM pending_admins
        WHERE request_id = ?
        LIMIT 1
    ");
    $stmt->execute([$requestId]);
    $pending = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pending) {
        $pdo->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Request not found. It may have already been processed.'
        ]);
        exit;
    }

    /*
    |--------------------------------------------------
    | ACCEPT REQUEST
    |--------------------------------------------------
    */
    if ($action === 'accept') {

        // check duplicate email
        $stmt = $pdo->prepare("SELECT AdminID FROM admins WHERE Email = ? LIMIT 1");
        $stmt->execute([$pending['email']]);

        if ($stmt->fetch()) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Email already exists as admin.'
            ]);
            exit;
        }

        // insert admin
        $insert = $pdo->prepare("
            INSERT INTO admins (FullName, Email, Password, Role, AccountStatus)
            VALUES (?, ?, ?, 'Admin', 'Active')
        ");

        $insert->execute([
            $pending['username'],
            $pending['email'],
            $pending['password']
        ]);
    }

    /*
    |--------------------------------------------------
    | DELETE REQUEST (BOTH ACCEPT & REJECT REMOVE IT)
    |--------------------------------------------------
    */
    $delete = $pdo->prepare("DELETE FROM pending_admins WHERE request_id = ?");
    $delete->execute([$requestId]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => $action === 'accept'
            ? 'Admin approved successfully.'
            : 'Admin request rejected.'
    ]);
} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
