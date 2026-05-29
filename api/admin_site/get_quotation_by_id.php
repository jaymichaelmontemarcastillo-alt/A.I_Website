<?php

/**
 * api/admin_site/get_quotation_by_id.php
 * Fetches a single quotation by ID with its items
 */

header('Content-Type: application/json');
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();

    $quotationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($quotationId <= 0) {
        throw new Exception('Invalid quotation ID');
    }

    // Get quotation details
    $stmt = $pdo->prepare("
        SELECT 
            id, quote_number, client_name, contact_person, 
            email, phone, address, subtotal, tax, discount, 
            total, notes, status, created_at, audited, audit_id
        FROM quotations 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $quotationId]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        throw new Exception('Quotation not found');
    }

    // Get quotation items
    $stmtItems = $pdo->prepare("
        SELECT id, description, quantity, unit_price, total
        FROM quotation_items
        WHERE quotation_id = :quotation_id
        ORDER BY id ASC
    ");
    $stmtItems->execute([':quotation_id' => $quotationId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'quotation' => $quotation,
            'items' => $items
        ]
    ]);
} catch (PDOException $e) {
    error_log("get_quotation_by_id.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("get_quotation_by_id.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
