<?php
// api/get_quotation_pdf.php - Get PDF URL for a quotation (when status is converted)
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
        $stmt = $pdo->prepare("SELECT id, quote_number, status, pdf_url FROM quotations WHERE id = ? AND user_id = ?");
        $stmt->execute([$quotation_id, $user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT id, quote_number, status, pdf_url FROM quotations WHERE id = ? AND session_id = ?");
        $stmt->execute([$quotation_id, $session_id]);
    }

    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Quotation not found or access denied']);
        exit;
    }

    // Check if status is converted
    if (strtolower($quotation['status']) !== 'converted') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'PDF only available for converted quotations']);
        exit;
    }

    // If PDF URL is stored in database, use it
    if ($quotation['pdf_url']) {
        echo json_encode([
            'success' => true,
            'pdf_url' => $quotation['pdf_url']
        ]);
        exit;
    }

    // Otherwise, try to find PDF file by quote_number
    $uploadDir = __DIR__ . '/../uploads/quotations/';
    $safeNum = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $quotation['quote_number']);
    $pattern = 'quotation_' . $safeNum . '_*.pdf';

    $files = glob($uploadDir . $pattern);

    if (!empty($files)) {
        // Get most recent PDF
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $pdfFile = basename($files[0]);
        $pdfUrl = '/Anything_Inside_Website/uploads/quotations/' . $pdfFile;

        // Optionally update database with PDF URL
        $updateStmt = $pdo->prepare("UPDATE quotations SET pdf_url = ? WHERE id = ?");
        $updateStmt->execute([$pdfUrl, $quotation_id]);

        echo json_encode([
            'success' => true,
            'pdf_url' => $pdfUrl
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'PDF file not found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
