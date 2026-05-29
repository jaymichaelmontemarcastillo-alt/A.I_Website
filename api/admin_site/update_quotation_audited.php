<?php

/**
 * api/admin_site/update_quotation_audited.php
 * Updates the audited status of a quotation after audit creation
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'message' => 'Method not allowed.']));
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new RuntimeException('Database connection failed.');
    }

    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    $quotationId = isset($input['quotation_id']) ? (int)$input['quotation_id'] : 0;
    $auditId = isset($input['audit_id']) ? (int)$input['audit_id'] : 0;
    $adminId = $_SESSION['admin_id'] ?? null;

    if ($quotationId <= 0) {
        throw new Exception('Invalid quotation ID');
    }

    if ($auditId <= 0) {
        throw new Exception('Invalid audit ID');
    }

    // Update the quotation with audit information
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
    error_log('update_quotation_audited.php error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
