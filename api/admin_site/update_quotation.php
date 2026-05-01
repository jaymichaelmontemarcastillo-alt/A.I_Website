<?php

/**
 * api/admin_site/update_quotation.php
 * Updates a quotation and replaces its items atomically.
 *
 * Fixes vs original:
 *  - rowCount() === 0 no longer thrown as error (some PDO drivers return 0 even on success)
 *  - Verifies the quotation exists BEFORE the update (SELECT first)
 *  - No stray output before header() calls
 *  - Consistent JSON error shape
 */

// ── Strict output control ──────────────────────────────────────────────────
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

// ── Only allow POST ────────────────────────────────────────────────────────
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

    // ── Parse JSON body ────────────────────────────────────────────────────
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
    }

    // ── Validate required fields ───────────────────────────────────────────
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Valid quotation ID is required.']));
    }

    $client_name    = trim($input['client_name']    ?? '');
    $contact_person = trim($input['contact_person'] ?? '');
    $email          = trim($input['email']          ?? '');
    $phone          = trim($input['phone']          ?? '');
    $tax            = max(0, (float)($input['tax']      ?? 0));
    $discount       = max(0, (float)($input['discount'] ?? 0));
    $notes          = trim($input['notes'] ?? '');
    $items          = $input['items'] ?? [];
    /*
    if ($client_name === '') {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Client name is required.']));
    }

    if (!is_array($items) || empty($items)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'At least one item is required.']));
    }
*/
    // ── Verify quotation exists ────────────────────────────────────────────
    $checkStmt = $conn->prepare('SELECT id FROM quotations WHERE id = ? LIMIT 1');
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'message' => "Quotation ID {$id} not found."]));
    }

    // ── Build & validate items ─────────────────────────────────────────────
    $subtotal   = 0.0;
    $cleanItems = [];

    foreach ($items as $item) {
        $desc  = trim($item['description'] ?? '');
        $qty   = max(0, (float)($item['quantity']   ?? 0));
        $price = max(0, (float)($item['unit_price'] ?? 0));

        if ($desc === '') continue; // skip blank rows

        $rowTotal    = $qty * $price;
        $subtotal   += $rowTotal;
        $cleanItems[] = [
            'description' => $desc,
            'quantity'    => $qty,
            'unit_price'  => $price,
            'total'       => $rowTotal,
        ];
    }
    /*
    if (empty($cleanItems)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'No valid items found (description is required).']));
    }
*/
    $taxAmount  = $subtotal * ($tax / 100);
    $grandTotal = max(0.0, $subtotal + $taxAmount - $discount);

    // ── Transaction ────────────────────────────────────────────────────────
    $conn->beginTransaction();

    try {
        // 1. Update quotation header
        $updateSql = '
            UPDATE quotations SET
                client_name    = :client_name,
                contact_person = :contact_person,
                email          = :email,
                phone          = :phone,
                subtotal       = :subtotal,
                tax            = :tax,
                discount       = :discount,
                total          = :total,
                notes          = :notes,
                updated_at     = NOW()
            WHERE id = :id';

        $stmt = $conn->prepare($updateSql);
        $stmt->execute([
            ':id'             => $id,
            ':client_name'    => $client_name,
            ':contact_person' => $contact_person,
            ':email'          => $email,
            ':phone'          => $phone,
            ':subtotal'       => round($subtotal, 4),
            ':tax'            => round($tax, 4),
            ':discount'       => round($discount, 4),
            ':total'          => round($grandTotal, 4),
            ':notes'          => $notes,
        ]);

        // NOTE: We intentionally do NOT check rowCount() here.
        // PDO may return 0 if the values are unchanged, which is still a valid success.

        // 2. Delete old items
        $conn->prepare('DELETE FROM quotation_items WHERE quotation_id = ?')
            ->execute([$id]);

        // 3. Insert new items
        $insertSql = '
            INSERT INTO quotation_items
                (quotation_id, description, quantity, unit_price, total)
            VALUES
                (:quotation_id, :description, :quantity, :unit_price, :total)';

        $insertStmt = $conn->prepare($insertSql);
        foreach ($cleanItems as $item) {
            $insertStmt->execute([
                ':quotation_id' => $id,
                ':description'  => $item['description'],
                ':quantity'     => $item['quantity'],
                ':unit_price'   => $item['unit_price'],
                ':total'        => $item['total'],
            ]);
        }

        $conn->commit();
    } catch (Throwable $inner) {
        $conn->rollBack();
        throw $inner;
    }

    // ── Success ────────────────────────────────────────────────────────────
    echo json_encode([
        'success' => true,
        'message' => 'Quotation updated successfully.',
        'data'    => [
            'id'         => $id,
            'subtotal'   => round($subtotal,   2),
            'tax'        => round($tax,        2),
            'discount'   => round($discount,   2),
            'total'      => round($grandTotal, 2),
            'item_count' => count($cleanItems),
        ],
    ]);
} catch (Throwable $e) {
    // Rollback if transaction is active
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('update_quotation.php error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
