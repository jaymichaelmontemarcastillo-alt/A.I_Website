<?php
// api/get_quotation_details.php - Fetch quotation and items for editing
session_start();
header('Content-Type: application/json');

require_once '../connect/config.php';

try {
    $pdo = getDBConnection();

    $quotation_id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$quotation_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Quotation ID is required']);
        exit;
    }

    $user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_id = session_id();

    // Fetch quotation
    if ($user_id !== null) {
        $stmt = $pdo->prepare("SELECT * FROM quotations WHERE id = ? AND user_id = ?");
        $stmt->execute([$quotation_id, $user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM quotations WHERE id = ? AND session_id = ?");
        $stmt->execute([$quotation_id, $session_id]);
    }

    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Quotation not found or access denied']);
        exit;
    }

    // Fetch items
    $itemsStmt = $pdo->prepare("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY id ASC");
    $itemsStmt->execute([$quotation_id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'quotation' => $quotation,
        'items' => $items
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
