<?php
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

    $raw   = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
    }

    $id     = isset($input['id'])     ? (int) $input['id']         : 0;
    $status = isset($input['status']) ? trim($input['status'])      : '';

    $allowed = ['draft', 'sent', 'accepted', 'expired', 'converted'];

    if ($id <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Valid quotation ID is required.']));
    }

    if (!in_array($status, $allowed, true)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Invalid status value.']));
    }

    // Verify quotation exists
    $check = $conn->prepare('SELECT id FROM quotations WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'message' => "Quotation ID {$id} not found."]));
    }

    $stmt = $conn->prepare('UPDATE quotations SET status = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$status, $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Status updated successfully.',
        'data'    => ['id' => $id, 'status' => $status],
    ]);
} catch (Throwable $e) {
    error_log('update_quotation_status.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
