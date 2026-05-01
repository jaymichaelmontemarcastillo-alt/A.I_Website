<?php
// api/admin_site/get_single_quotation.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../connect/config.php';

header('Content-Type: application/json');

try {
    $pdo = getDBConnection();

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quotation ID.']);
        exit;
    }

    // Fetch quotation
    $stmt = $pdo->prepare("
        SELECT
            id, quote_number, client_name, contact_person,
            email, phone, subtotal, tax, discount, total,
            notes, status, created_at
        FROM quotations
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        echo json_encode(['success' => false, 'message' => 'Quotation not found.']);
        exit;
    }

    // Fetch items
    $stmtItems = $pdo->prepare("
        SELECT id, description, quantity, unit_price, total
        FROM quotation_items
        WHERE quotation_id = ?
        ORDER BY id ASC
    ");
    $stmtItems->execute([$id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric fields
    $quotation['subtotal'] = (float) $quotation['subtotal'];
    $quotation['tax']      = (float) $quotation['tax'];
    $quotation['discount'] = (float) $quotation['discount'];
    $quotation['total']    = (float) $quotation['total'];

    foreach ($items as &$item) {
        $item['quantity']   = (float) $item['quantity'];
        $item['unit_price'] = (float) $item['unit_price'];
        $item['total']      = (float) $item['total'];
    }
    unset($item);

    echo json_encode([
        'success' => true,
        'data'    => [
            'quotation' => $quotation,
            'items'     => $items,
        ],
    ]);
} catch (\Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
    ]);
}
exit;
