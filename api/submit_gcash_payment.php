<?php
// submit_gcash_payment.php
// Called by customer when submitting GCash payment proof
header('Content-Type: application/json');
require_once '../../../connect/config.php';

$order_number      = trim($_POST['order_number']      ?? '');
$reference_number  = trim($_POST['reference_number']  ?? '');

if (!$order_number || !$reference_number) {
    echo json_encode(['success' => false, 'message' => 'Order number and reference number are required']);
    exit;
}

if (!isset($_FILES['proof_image']) || $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Payment proof image is required']);
    exit;
}

// ── File validation ──────────────────────────────────────────
$file      = $_FILES['proof_image'];
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize      = 5 * 1024 * 1024; // 5 MB

$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);

if (!in_array($mimeType, $allowedMimes, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed']);
    exit;
}

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB']);
    exit;
}

// ── Save file ────────────────────────────────────────────────
$uploadDir = __DIR__ . '/../../../uploads/payment_proofs/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext          = pathinfo($file['name'], PATHINFO_EXTENSION);
$safeFilename = 'proof_' . $order_number . '_' . time() . '.' . strtolower($ext);
$destination  = $uploadDir . $safeFilename;
$publicPath   = 'uploads/payment_proofs/' . $safeFilename; // relative path stored in DB

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
    exit;
}

// ── Database update ──────────────────────────────────────────
try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the order
    $stmt = $pdo->prepare("SELECT id, payment_status, payment_method FROM orders WHERE order_number = ? LIMIT 1");
    $stmt->execute([$order_number]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    if ($order['payment_method'] !== 'gcash') {
        echo json_encode(['success' => false, 'message' => 'This order is not a GCash payment']);
        exit;
    }

    if ($order['payment_status'] === 'paid') {
        echo json_encode(['success' => false, 'message' => 'Payment already verified']);
        exit;
    }

    // Check for duplicate reference number
    $dupStmt = $pdo->prepare("SELECT id FROM payments WHERE reference_number = ? LIMIT 1");
    $dupStmt->execute([$reference_number]);
    if ($dupStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'This reference number has already been submitted']);
        exit;
    }

    $pdo->beginTransaction();

    // Update orders table
    $pdo->prepare("
        UPDATE orders 
        SET payment_proof = ?, payment_reference = ?, payment_status = 'pending'
        WHERE order_number = ?
    ")->execute([$publicPath, $reference_number, $order_number]);

    // Check if a payments record already exists for this order
    $existingPay = $pdo->prepare("SELECT id FROM payments WHERE order_id = ? LIMIT 1");
    $existingPay->execute([$order['id']]);
    $payRow = $existingPay->fetch(PDO::FETCH_ASSOC);

    if ($payRow) {
        // Update existing
        $pdo->prepare("
            UPDATE payments 
            SET proof_image = ?, reference_number = ?, payment_status = 'pending'
            WHERE id = ?
        ")->execute([$publicPath, $reference_number, $payRow['id']]);
    } else {
        // Insert new
        $pdo->prepare("
            INSERT INTO payments (order_id, payment_method, payment_status, reference_number, proof_image)
            VALUES (?, 'gcash', 'pending', ?, ?)
        ")->execute([$order['id'], $reference_number, $publicPath]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Payment proof submitted. Awaiting admin verification.']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
