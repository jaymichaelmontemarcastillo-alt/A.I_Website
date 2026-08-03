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
require_once '../../api/admin_site/activity_logger.php';

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
    $adminName = 'Unknown';

    if ($quotationId <= 0) {
        throw new Exception('Invalid quotation ID');
    }

    if ($auditId <= 0) {
        throw new Exception('Invalid audit ID');
    }

    // Get admin name for logging
    if ($adminId) {
        $stmt = $pdo->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminName = $admin ? $admin['FullName'] : 'Unknown';
    }

    // Get quotation details for logging
    $stmt = $pdo->prepare("SELECT client_name, quote_number FROM quotations WHERE id = ?");
    $stmt->execute([$quotationId]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);
    $clientName = $quotation ? $quotation['client_name'] : 'Unknown';
    $quoteNumber = $quotation ? $quotation['quote_number'] : '';

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

    // ✅ LOG ACTIVITY - Success
    logActivity(
        $pdo,
        $adminId,
        $adminName,
        "Marked quotation #{$quoteNumber} (ID: {$quotationId}) for client '{$clientName}' as audited - Audit ID: {$auditId}",
        $quotationId,
        "Quotation Audit",
        "Success"
    );

    echo json_encode([
        'success' => true,
        'message' => 'Quotation marked as audited successfully.'
    ]);
} catch (Exception $e) {
    // ❌ LOG FAILED ACTIVITY
    if (isset($pdo)) {
        $adminId = $_SESSION['admin_id'] ?? null;
        $adminName = 'Unknown';
        if ($adminId) {
            try {
                $stmt = $pdo->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
                $stmt->execute([$adminId]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                $adminName = $admin ? $admin['FullName'] : 'Unknown';
            } catch (Exception $e) {
                // Ignore
            }
        }
        
        logActivity(
            $pdo,
            $adminId,
            $adminName,
            "Failed to mark quotation as audited (ID: {$quotationId}): " . $e->getMessage(),
            $quotationId ?? null,
            "Quotation Audit",
            "Failed"
        );
    }
    
    error_log('update_quotation_audited.php error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}