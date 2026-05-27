<?php

/**
 * api/admin_site/create_quotation.php
 * Creates a new quotation
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'message' => 'Method not allowed.']));
}

require_once '../../connect/config.php';

try {
    $conn = getDBConnection();
    if (!$conn) {
        throw new RuntimeException('Database connection failed.');
    }

    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
    }

    $client_name    = trim($input['client_name'] ?? '');
    $contact_person = trim($input['contact_person'] ?? '');
    $email          = trim($input['email'] ?? '');
    $phone          = trim($input['phone'] ?? '');
    $tax            = max(0, (float)($input['tax'] ?? 0));
    $discount       = max(0, (float)($input['discount'] ?? 0));
    $notes          = trim($input['notes'] ?? '');
    $items          = $input['items'] ?? [];

    if ($client_name === '') {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Client name is required.']));
    }

    if (!is_array($items) || empty($items)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'At least one item is required.']));
    }

    // Build & validate items
    $subtotal   = 0.0;
    $cleanItems = [];

    foreach ($items as $item) {
        $desc  = trim($item['description'] ?? '');
        $qty   = max(0, (float)($item['quantity'] ?? 0));
        $price = max(0, (float)($item['unit_price'] ?? 0));

        if ($desc === '') continue;

        $rowTotal    = $qty * $price;
        $subtotal   += $rowTotal;
        $cleanItems[] = [
            'description' => $desc,
            'quantity'    => $qty,
            'unit_price'  => $price,
            'total'       => $rowTotal,
        ];
    }

    if (empty($cleanItems)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'No valid items found (description is required).']));
    }

    $taxAmount  = $subtotal * ($tax / 100);
    $grandTotal = max(0.0, $subtotal + $taxAmount - $discount);

    // Generate unique quote number
    $quote_number = 'QT-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Check for uniqueness
    $checkStmt = $conn->prepare('SELECT id FROM quotations WHERE quote_number = ?');
    $checkStmt->execute([$quote_number]);
    while ($checkStmt->fetch()) {
        $quote_number = 'QT-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $checkStmt->execute([$quote_number]);
    }

    $conn->beginTransaction();

    try {
        // Insert quotation
        $insertSql = '
            INSERT INTO quotations 
                (quote_number, client_name, contact_person, email, phone, 
                 subtotal, tax, discount, total, notes, status)
            VALUES
                (:quote_number, :client_name, :contact_person, :email, :phone,
                 :subtotal, :tax, :discount, :total, :notes, "draft")';

        $stmt = $conn->prepare($insertSql);
        $stmt->execute([
            ':quote_number'    => $quote_number,
            ':client_name'     => $client_name,
            ':contact_person'  => $contact_person,
            ':email'           => $email,
            ':phone'           => $phone,
            ':subtotal'        => round($subtotal, 4),
            ':tax'             => round($tax, 4),
            ':discount'        => round($discount, 4),
            ':total'           => round($grandTotal, 4),
            ':notes'           => $notes,
        ]);

        $quotation_id = $conn->lastInsertId();

        // Insert items
        $insertItemSql = '
            INSERT INTO quotation_items
                (quotation_id, description, quantity, unit_price, total)
            VALUES
                (:quotation_id, :description, :quantity, :unit_price, :total)';

        $insertStmt = $conn->prepare($insertItemSql);
        foreach ($cleanItems as $item) {
            $insertStmt->execute([
                ':quotation_id' => $quotation_id,
                ':description'  => $item['description'],
                ':quantity'     => $item['quantity'],
                ':unit_price'   => $item['unit_price'],
                ':total'        => $item['total'],
            ]);
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Quotation created successfully.',
            'quotation_id' => $quotation_id,
            'quote_number' => $quote_number,
        ]);
    } catch (Throwable $inner) {
        $conn->rollBack();
        throw $inner;
    }
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('create_quotation.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
