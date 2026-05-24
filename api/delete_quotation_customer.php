<?php
// api/delete_quotation_customer.php - Delete a quotation (customer side)
session_start();
header('Content-Type: application/json');

require_once '../connect/config.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception('Database connection failed');
    }

    // Get quotation ID from request
    $quotation_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : null);

    if (!$quotation_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Quotation ID is required']);
        exit;
    }

    $user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_id = session_id();

    // Fetch the quotation to verify ownership
    if ($user_id !== null) {
        $stmt = $conn->prepare("SELECT id, user_id FROM quotations WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $quotation_id, $user_id);
    } else {
        $stmt = $conn->prepare("SELECT id, session_id FROM quotations WHERE id = ? AND session_id = ?");
        $stmt->bind_param('is', $quotation_id, $session_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Quotation not found or access denied']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    // Delete quotation items first (foreign key constraint)
    $delete_items = $conn->prepare("DELETE FROM quotation_items WHERE quotation_id = ?");
    $delete_items->bind_param('i', $quotation_id);
    $delete_items->execute();
    $delete_items->close();

    // Delete the quotation
    $delete_quote = $conn->prepare("DELETE FROM quotations WHERE id = ?");
    $delete_quote->bind_param('i', $quotation_id);

    if ($delete_quote->execute()) {
        echo json_encode(['success' => true, 'message' => 'Quotation deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete quotation']);
    }

    $delete_quote->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
