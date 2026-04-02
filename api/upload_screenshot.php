<?php
session_start();
require_once '../connect/config.php';
header('Content-Type: application/json');

// Disable error display
ini_set('display_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['screenshot'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed']);
    exit;
}

// Validate file size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'File size too large. Maximum 5MB']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = '../uploads/payments/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'payment_' . time() . '_' . uniqid() . '.' . $extension;
$destination = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $destination)) {
    // Clean output buffer
    if (ob_get_length()) ob_clean();
    
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'path' => 'uploads/payments/' . $filename
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save file']);
}
exit;
?>