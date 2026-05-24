<?php
// api/update_quotation_customer.php - Update a quotation (customer side)
session_start();
header('Content-Type: application/json');

require_once '../connect/config.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception('Database connection failed');
    }

    // Get quotation ID from request
    $quotation_id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if (!$quotation_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Quotation ID is required']);
        exit;
    }

    $user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_id = session_id();

    // Verify ownership
    if ($user_id !== null) {
        $stmt = $conn->prepare("SELECT id, user_id FROM quotations WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $quotation_id, $user_id);
    } else {
        $stmt = $conn->prepare("SELECT id, session_id FROM quotations WHERE id = ? AND session_id = ?");
        $stmt->bind_param('is', $quotation_id, $session_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Quotation not found or access denied']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    // Get POST data
    $client_name = trim($_POST['client_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $expires_at = trim($_POST['expires_at'] ?? '');

    // Validate required fields
    if (empty($client_name) || empty($email) || empty($phone)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Client name, email, and phone are required']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }

    if (!preg_match('/^(09|\+639)\d{9}$/', preg_replace('/\s+/', '', $phone))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid Philippine phone number']);
        exit;
    }

    // Process items
    $descriptions = $_POST['description'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $unit_prices = $_POST['unit_price'] ?? [];

    $subtotal = 0;
    $items = [];

    for ($i = 0; $i < count($descriptions); $i++) {
        if (!empty($descriptions[$i]) && floatval($quantities[$i]) > 0) {
            $qty = floatval($quantities[$i]);
            $price = floatval($unit_prices[$i]);
            $total = $qty * $price;
            $subtotal += $total;

            $items[] = [
                'description' => $descriptions[$i],
                'quantity' => $qty,
                'unit_price' => $price,
                'total' => $total,
            ];
        }
    }

    if (count($items) === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please add at least one item']);
        exit;
    }

    $grand_total = $subtotal;

    // Update quotation
    $update_stmt = $conn->prepare(
        "UPDATE quotations 
         SET client_name = ?, contact_person = ?, email = ?, phone = ?, 
             subtotal = ?, total = ?, expires_at = ?
         WHERE id = ?"
    );

    $update_stmt->bind_param(
        'ssssddsi',
        $client_name,
        $contact_person,
        $email,
        $phone,
        $subtotal,
        $grand_total,
        $expires_at,
        $quotation_id
    );

    if (!$update_stmt->execute()) {
        throw new Exception('Failed to update quotation: ' . $conn->error);
    }

    $update_stmt->close();

    // Delete existing items
    $conn->query("DELETE FROM quotation_items WHERE quotation_id = $quotation_id");

    // Insert new items
    if (count($items) > 0) {
        $item_stmt = $conn->prepare(
            "INSERT INTO quotation_items (quotation_id, description, quantity, unit_price, total)
             VALUES (?, ?, ?, ?, ?)"
        );

        foreach ($items as $item) {
            $desc = $item['description'];
            $item_stmt->bind_param('isddd', $quotation_id, $desc, $item['quantity'], $item['unit_price'], $item['total']);
            $item_stmt->execute();
        }
        $item_stmt->close();
    }

    echo json_encode(['success' => true, 'message' => 'Quotation updated successfully']);
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
