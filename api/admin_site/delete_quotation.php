<?php
header('Content-Type: application/json');
session_start();
require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing quotation ID']);
        exit;
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete quotation items first
    $query = "DELETE FROM quotation_items WHERE quote_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $data['id']]);

    // Update bom_audit to remove reference
    $query = "UPDATE bom_audit SET quote_id = NULL WHERE quote_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $data['id']]);

    // Delete quotation
    $query = "DELETE FROM quotations WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $data['id']]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Quotation deleted']);
} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
