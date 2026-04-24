<?php
session_start();
include '../connect/config.php';

header('Content-Type: application/json');

try {
    $pdo = getDBConnection();

    $user_id = $_SESSION['user_id'] ?? null;
    $session_id = session_id();

    $request_number = 'REQ-' . time();

    $stmt = $pdo->prepare("
        INSERT INTO requests 
        (request_number, user_id, session_id, client_name, contact_person, email, phone, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $request_number,
        $user_id,
        $session_id,
        $_POST['client_name'],
        $_POST['contact_person'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['notes']
    ]);

    $request_id = $pdo->lastInsertId();

    $selected = $_POST['selected_products'] ?? [];

    $stmtItem = $pdo->prepare("
        INSERT INTO request_items (request_id, description, quantity)
        VALUES (?, ?, ?)
    ");

    foreach ($selected as $product_id) {

        // get product name
        $stmtName = $pdo->prepare("SELECT name FROM products WHERE id = ?");
        $stmtName->execute([$product_id]);
        $product = $stmtName->fetch();

        $desc = $_POST['description'][$product_id] ?? $product['name'];
        $qty = $_POST['quantity'][$product_id] ?? 1;

        $stmtItem->execute([
            $request_id,
            $desc,
            $qty
        ]);
    }

    echo json_encode([
        "status" => "success",
        "message" => "Request submitted successfully!"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
