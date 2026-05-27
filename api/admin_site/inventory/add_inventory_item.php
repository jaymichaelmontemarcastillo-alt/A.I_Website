<?php

/**
 * api/admin_site/inventory/add_inventory_item.php
 * Adds a new item to materials table
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

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid input data.');
    }

    // Sanitize and validate inputs
    $materialName = trim($input['material_name'] ?? '');
    $type = trim($input['type'] ?? '');
    $shopStock = (int) ($input['shop_stock'] ?? 0);
    $phStock = (int) ($input['ph_stock'] ?? 0);
    $unitCost = (float) ($input['unit_cost'] ?? 0);
    $totalStock = $shopStock + $phStock;
    $totalCost = $totalStock * $unitCost;
    $piecesPerPack = 1; // Default value
    $remarks = '';

    // Validation
    if (empty($materialName)) {
        throw new Exception('Material name is required.');
    }

    if (empty($type)) {
        throw new Exception('Type is required.');
    }

    if ($shopStock < 0) {
        throw new Exception('Shop stock cannot be negative.');
    }

    if ($phStock < 0) {
        throw new Exception('PH stock cannot be negative.');
    }

    if ($unitCost < 0) {
        throw new Exception('Unit cost cannot be negative.');
    }

    // Check if material already exists
    $checkStmt = $pdo->prepare('SELECT id FROM materials WHERE material_name = ?');
    $checkStmt->execute([$materialName]);
    if ($checkStmt->fetch()) {
        throw new Exception('Material "' . $materialName . '" already exists.');
    }

    // Insert new material item
    $stmt = $pdo->prepare('
        INSERT INTO materials (material_name, type, shop_stock, ph_stock, total_stock, total_cost, pieces_per_pack, unit_cost, remarks, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ');

    $result = $stmt->execute([
        $materialName,
        $type,
        $shopStock,
        $phStock,
        $totalStock,
        $totalCost,
        $piecesPerPack,
        $unitCost,
        $remarks
    ]);

    if (!$result) {
        throw new Exception('Failed to add material item.');
    }

    $newId = $pdo->lastInsertId();

    // Log the addition (if materials_log table exists)
    $adminName = $_SESSION['admin_name'] ?? 'Admin';

    // Check if materials_log table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'materials_log'");
    if ($tableCheck->rowCount() > 0) {
        $logStmt = $pdo->prepare('
            INSERT INTO materials_log (material_id, material_name, type, location, quantity_change, before_quantity, after_quantity, admin_name, note, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ');
        $logStmt->execute([
            $newId,
            $materialName,
            'add',
            'total_stock',
            $totalStock,
            0,
            $totalStock,
            $adminName,
            'New material added'
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Item added successfully.',
        'item' => [
            'id' => $newId,
            'material_name' => $materialName,
            'type' => $type,
            'shop_stock' => $shopStock,
            'ph_stock' => $phStock,
            'total_stock' => $totalStock,
            'unit_cost' => $unitCost
        ]
    ]);
} catch (Exception $e) {
    error_log('add_inventory_item.php ERROR: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
