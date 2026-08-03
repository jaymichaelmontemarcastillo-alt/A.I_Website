<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

require_once __DIR__ . '/../../../connect/config.php';

try {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $password = trim((string) ($input['password'] ?? ''));

    if ($password === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password is required.']);
        exit;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT Password, FullName FROM admins WHERE AdminID = ? LIMIT 1");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || !password_verify($password, $admin['Password'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM product_materials");
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM products");
    $stmt->execute();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'All products deleted successfully.'
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Unable to delete all products right now.'
    ]);
}
