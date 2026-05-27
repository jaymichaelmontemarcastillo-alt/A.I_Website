<?php
header('Content-Type: application/json');
session_start();
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Get the quote number before updating
    $stmt = $pdo->prepare("SELECT quote_number FROM quotations WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    $quote_number = $quote ? $quote['quote_number'] : '';

    $query = "UPDATE quotations SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Status updated',
        'quote_number' => $quote_number,
        'id' => $data['id']
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
