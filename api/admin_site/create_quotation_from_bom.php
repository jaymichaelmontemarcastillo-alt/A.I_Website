<?php
header('Content-Type: application/json');
session_start();
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    // Start transaction
    $pdo->beginTransaction();

    // Calculate totals
    $subtotal = 0;
    foreach ($data['items'] as $item) {
        $subtotal += $item['quantity'] * $item['unit_price'];
    }
    $total = $subtotal;
    $tax = 0;
    $discount = 0;

    // Generate quote number
    $year = date('Y');
    $month = date('m');
    $query = "SELECT COUNT(*) as count FROM quotations WHERE YEAR(created_at) = :year AND MONTH(created_at) = :month";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':year' => $year, ':month' => $month]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] + 1;
    $quoteNumber = 'Q-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

    // Get user ID from session
    $userId = $_SESSION['AdminID'] ?? null;
    $sessionId = session_id();

    // Insert quotation
    $query = "INSERT INTO quotations (quote_number, user_id, session_id, client_name, contact_person, email, phone, status, subtotal, tax, discount, total, notes, created_at) 
              VALUES (:quote_number, :user_id, :session_id, :client_name, :contact_person, :email, :phone, 'draft', :subtotal, :tax, :discount, :total, :notes, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':quote_number' => $quoteNumber,
        ':user_id' => $userId,
        ':session_id' => $sessionId,
        ':client_name' => $data['client_name'],
        ':contact_person' => $data['contact_person'] ?? '',
        ':email' => $data['email'],
        ':phone' => $data['phone'],
        ':subtotal' => $subtotal,
        ':tax' => $tax,
        ':discount' => $discount,
        ':total' => $total,
        ':notes' => $data['notes'] ?? ''
    ]);
    $quoteId = $pdo->lastInsertId();

    // Insert quotation items - using quotation_id (not quote_id)
    $query = "INSERT INTO quotation_items (quotation_id, description, quantity, unit_price, total) 
              VALUES (:quotation_id, :description, :quantity, :unit_price, :total)";
    $stmt = $pdo->prepare($query);

    foreach ($data['items'] as $item) {
        $itemTotal = $item['quantity'] * $item['unit_price'];
        $stmt->execute([
            ':quotation_id' => $quoteId,
            ':description' => $item['description'],
            ':quantity' => $item['quantity'],
            ':unit_price' => $item['unit_price'],
            ':total' => $itemTotal
        ]);
    }

    // Update BOM audit with quote_id
    if (isset($data['bom_id']) && $data['bom_id']) {
        $query = "UPDATE bom_audit SET quote_id = :quote_id, status = 'completed' WHERE id = :bom_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':quote_id' => $quoteId, ':bom_id' => $data['bom_id']]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'quote_id' => $quoteId,
        'quote_number' => $quoteNumber,
        'message' => 'Quotation created successfully'
    ]);
} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
