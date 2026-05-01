<?php

/**
 * api/admin_site/delete_quotation.php
 * Deletes a quotation and all its items atomically.
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

    // ── Parse JSON body ────────────────────────────────────────────────────
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
    }

    $id = isset($input['id']) ? (int) $input['id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Valid quotation ID is required.']));
    }

    // ── Verify quotation exists ────────────────────────────────────────────
    $checkStmt = $conn->prepare('SELECT id, quote_number FROM quotations WHERE id = ? LIMIT 1');
    $checkStmt->execute([$id]);
    $quotation = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'message' => "Quotation ID {$id} not found."]));
    }

    // ── Transaction: delete items then header ──────────────────────────────
    $conn->beginTransaction();

    try {
        // 1. Delete items first (foreign key constraint)
        $conn->prepare('DELETE FROM quotation_items WHERE quotation_id = ?')
            ->execute([$id]);

        // 2. Delete the quotation
        $stmt = $conn->prepare('DELETE FROM quotations WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException("Failed to delete quotation ID {$id}.");
        }

        $conn->commit();
    } catch (Throwable $inner) {
        $conn->rollBack();
        throw $inner;
    }

    echo json_encode([
        'success' => true,
        'message' => "Quotation {$quotation['quote_number']} deleted successfully.",
        'data'    => [
            'id'           => $id,
            'quote_number' => $quotation['quote_number'],
        ],
    ]);
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('delete_quotation.php error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
