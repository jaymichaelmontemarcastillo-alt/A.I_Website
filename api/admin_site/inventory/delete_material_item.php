<?php

/**
 * api/admin_site/inventory/delete_material_item.php
 * Deletes a specific material item by ID
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../connect/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login as admin.']);
    exit;
}

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed.');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input) || empty($input)) {
        $input = $_POST;
    }

    $id = (int) ($input['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('Invalid material ID.');
    }

    // Fetch material before deletion
    $stmt = $pdo->prepare('SELECT id, material_name, total_stock FROM materials WHERE id = ?');
    $stmt->execute([$id]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$material) {
        throw new Exception('Material not found.');
    }

    $materialName = $material['material_name'];

    $pdo->beginTransaction();

    // 1. Delete associated logs in materials_logs to satisfy FK constraints
    $delLogsStmt = $pdo->prepare('DELETE FROM materials_logs WHERE material_id = ?');
    $delLogsStmt->execute([$id]);

    // 2. Delete material from materials table
    $delStmt = $pdo->prepare('DELETE FROM materials WHERE id = ?');
    $result = $delStmt->execute([$id]);

    if (!$result) {
        $pdo->rollBack();
        throw new Exception('Failed to delete material item.');
    }

    $pdo->commit();

    // Activity log
    $adminName = $_SESSION['admin_name'] ?? 'Admin';
    try {
        require_once __DIR__ . '/../../activity_logger.php';
        logActivity($pdo, $_SESSION['admin_id'], $adminName, 'Deleted material "' . $materialName . '"', $id, 'Inventory', 'Success');
    } catch (Throwable $activityErr) {
        error_log('delete_material_item activity log error: ' . $activityErr->getMessage());
    }

    echo json_encode([
        'success' => true,
        'message' => 'Material "' . $materialName . '" deleted successfully.'
    ]);
} catch (Exception $e) {
    error_log('delete_material_item.php ERROR: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
