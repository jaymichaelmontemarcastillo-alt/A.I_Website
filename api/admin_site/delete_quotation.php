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

    // Get the quote_number
    $getQuoteQuery = "SELECT quote_number FROM quotations WHERE id = :id";
    $getQuoteStmt = $pdo->prepare($getQuoteQuery);
    $getQuoteStmt->execute([':id' => $data['id']]);
    $quoteData = $getQuoteStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$quoteData) {
        echo json_encode(['success' => false, 'message' => 'Quotation not found']);
        exit;
    }

    $quoteNumber = $quoteData['quote_number'];

    // Check if there are any orders linked to this quotation
    $checkQuery = "SELECT COUNT(*) as order_count FROM orders WHERE quote_number = :quote_number";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute([':quote_number' => $quoteNumber]);
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

    // Delete associated orders first (if any)
    if ($result && $result['order_count'] > 0) {
        $deleteOrdersQuery = "DELETE FROM orders WHERE quote_number = :quote_number";
        $deleteOrdersStmt = $pdo->prepare($deleteOrdersQuery);
        $deleteOrdersStmt->execute([':quote_number' => $quoteNumber]);
    }

    // Delete quotation items
    $query = "DELETE FROM quotation_items WHERE quotation_id = :id";
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

    $message = 'Quotation deleted successfully';
    if ($result && $result['order_count'] > 0) {
        $message .= ' with ' . $result['order_count'] . ' associated order(s)';
    }

    echo json_encode(['success' => true, 'message' => $message]);
} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    
    // Check if it's a foreign key constraint error
    if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
        echo json_encode([
            'success' => false, 
            'message' => 'Cannot delete quotation because it is referenced by other records. Please ensure all references are removed first.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}