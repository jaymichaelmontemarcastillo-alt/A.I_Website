<?php
// api/admin_site/get_delivery_receipt.php

header('Content-Type: application/json');
require_once __DIR__ . '/../../connect/config.php';

session_start();
if (empty($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$drId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($drId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM delivery_receipts WHERE id = ?");
    $stmt->execute([$drId]);
    $dr = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dr) {
        $dr['items'] = json_decode($dr['items'], true);
        echo json_encode(['success' => true, 'delivery_receipt' => $dr]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delivery receipt not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
