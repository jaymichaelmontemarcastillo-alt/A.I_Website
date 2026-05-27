<?php

/**
 * api/admin_site/get_quotation_stats.php
 * Returns statistics for dashboard cards
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once '../../connect/config.php';

try {
    $conn = getDBConnection();
    if (!$conn) {
        throw new RuntimeException('Database connection failed.');
    }

    $stats = [
        'total' => 0,
        'draft' => 0,
        'sent' => 0,
        'accepted' => 0,
        'expired' => 0,
        'converted' => 0,
    ];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations");
    $stats['total'] = (int)$stmt->fetch()['count'];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'draft'");
    $stats['draft'] = (int)$stmt->fetch()['count'];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'sent'");
    $stats['sent'] = (int)$stmt->fetch()['count'];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'accepted'");
    $stats['accepted'] = (int)$stmt->fetch()['count'];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'expired'");
    $stats['expired'] = (int)$stmt->fetch()['count'];

    $stmt = $conn->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'converted'");
    $stats['converted'] = (int)$stmt->fetch()['count'];

    echo json_encode([
        'success' => true,
        'data' => $stats,
    ]);
} catch (Throwable $e) {
    error_log('get_quotation_stats.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
