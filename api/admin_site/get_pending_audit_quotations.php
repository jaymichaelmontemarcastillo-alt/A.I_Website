<?php

/**
 * api/admin_site/get_pending_audit_quotations.php
 * Fetches quotations that are delivered (converted) but NOT yet audited
 */

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();

    // Get quotations that are:
    // 1. status = 'converted' (delivered)
    // 2. audited = 0 OR audited IS NULL (not yet audited)
    $sql = "SELECT 
                id, 
                quote_number, 
                client_name, 
                contact_person, 
                email, 
                phone, 
                total, 
                created_at, 
                status,
                address
            FROM quotations 
            WHERE status = 'converted' 
                AND (audited = 0 OR audited IS NULL)
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the data for frontend
    $formattedQuotations = array_map(function ($q) {
        return [
            'id' => (int)$q['id'],
            'quote_number' => $q['quote_number'],
            'client_name' => $q['client_name'],
            'contact_person' => $q['contact_person'] ?: '—',
            'email' => $q['email'] ?: '—',
            'phone' => $q['phone'] ?: '—',
            'total' => (float)$q['total'],
            'created_at' => $q['created_at'],
            'status' => $q['status'],
            'address' => $q['address'] ?: ''
        ];
    }, $quotations);

    echo json_encode([
        'success' => true,
        'quotations' => $formattedQuotations,
        'count' => count($formattedQuotations)
    ]);
} catch (PDOException $e) {
    error_log("get_pending_audit_quotations.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("get_pending_audit_quotations.php error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
