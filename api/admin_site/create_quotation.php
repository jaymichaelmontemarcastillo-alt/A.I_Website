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

/**
 * Generate a unique quotation number in format: XX-MMYY-NNNN
 * Example: AI-0526-0001 (May 2026, first quotation)
 * 
 * @param PDO $conn Database connection
 * @param string $prefix 2-letter prefix (e.g., 'AI', 'QT', 'IN')
 * @return string Generated quote number
 */
function generateQuoteNumber($conn, $prefix = 'AI')
{
    // Get current month and year (MMYY format) - month first, then year
    $monthYear = date('my'); // e.g., 0526 for May 2026

    // Query to get the highest sequential number for this month-year
    $pattern = $prefix . '-' . $monthYear . '-%';

    $stmt = $conn->prepare("
        SELECT quote_number 
        FROM quotations 
        WHERE quote_number LIKE :pattern 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmt->execute([':pattern' => $pattern]);
    $lastQuote = $stmt->fetch(PDO::FETCH_ASSOC);

    $nextNumber = 1;
    if ($lastQuote) {
        // Extract the last 4 digits from the quote number
        $parts = explode('-', $lastQuote['quote_number']);
        $lastSeq = (int)end($parts);
        $nextNumber = $lastSeq + 1;
    }

    // Format with leading zeros (4 digits)
    $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    return $prefix . '-' . $monthYear . '-' . $sequence;
}

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
    $address        = trim($input['address'] ?? '');
    $tax            = max(0, (float)($input['tax'] ?? 0));
    $discount       = max(0, (float)($input['discount'] ?? 0));
    $notes          = trim($input['notes'] ?? '');
    $items          = $input['items'] ?? [];
    $prefix         = trim($input['quote_prefix'] ?? 'AI');

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

    // Generate unique quote number in format: XX-MMYY-NNNN
    $quote_number = generateQuoteNumber($conn, $prefix);

    // Double-check uniqueness (fallback safety)
    $checkStmt = $conn->prepare('SELECT id FROM quotations WHERE quote_number = ?');
    $checkStmt->execute([$quote_number]);
    if ($checkStmt->fetch()) {
        $quote_number = generateQuoteNumber($conn, $prefix);
    }

    $conn->beginTransaction();

    try {
        // Insert quotation with audited = 0 by default
        $insertSql = '
            INSERT INTO quotations 
                (quote_number, client_name, contact_person, email, phone, address,
                 subtotal, tax, discount, total, notes, status, audited)
            VALUES
                (:quote_number, :client_name, :contact_person, :email, :phone, :address,
                 :subtotal, :tax, :discount, :total, :notes, "draft", 0)';

        $stmt = $conn->prepare($insertSql);
        $stmt->execute([
            ':quote_number'    => $quote_number,
            ':client_name'     => $client_name,
            ':contact_person'  => $contact_person,
            ':email'           => $email,
            ':phone'           => $phone,
            ':address'         => $address,
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
