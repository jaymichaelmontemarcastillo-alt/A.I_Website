<?php

/**
 * api/admin_site/mark_quotation_audited.php
 * Marks a quotation as audited after audit creation
 */

header('Content-Type: application/json');
require_once '../../connect/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = getDBConnection();

    $input = json_decode(file_get_contents('php://input'), true);
    $quotationId = isset($input['quotation_id']) ? (int)$input['quotation_id'] : 0;
    $auditId = isset($input['audit_id']) ? (int)$input['audit_id'] : 0;
    $adminId = $_SESSION['admin_id'] ?? null;

    if ($quotationId <= 0) {
        throw new Exception('Invalid quotation ID');
    }

    if ($auditId <= 0) {
        throw new Exception('Invalid audit ID');
    }

    // Update quotation with audit information
    $stmt = $pdo->prepare("
        UPDATE quotations 
        SET audited = 1, 
            audit_id = :audit_id, 
            audited_at = NOW(),
            audited_by = :admin_id
        WHERE id = :quotation_id
    ");

    $stmt->execute([
        ':audit_id' => $auditId,
        ':admin_id' => $adminId,
        ':quotation_id' => $quotationId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Quotation marked as audited successfully.'
    ]);
} catch (Exception $e) {
    error_log("mark_quotation_audited.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
