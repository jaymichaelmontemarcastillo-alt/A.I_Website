<?php

/**
 * api/admin_site/inventory/update_material_item.php
 * Updates an existing material item (name, type, stock, unit cost)
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
    $id = (int) ($input['id'] ?? 0);
    $materialName = trim($input['material_name'] ?? '');
    $type = trim($input['type'] ?? '');
    $shopStock = (int) ($input['shop_stock'] ?? 0);
    $phStock = (int) ($input['ph_stock'] ?? 0);
    $unitCost = (float) ($input['unit_cost'] ?? 0);
    $totalStock = $shopStock + $phStock;
    $totalCost = $totalStock * $unitCost;

    // Validation
    if ($id <= 0) {
        throw new Exception('Invalid material ID.');
    }

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

    // Check if material exists
    $checkStmt = $pdo->prepare('SELECT id, material_name, shop_stock, ph_stock, total_stock, unit_cost FROM materials WHERE id = ?');
    $checkStmt->execute([$id]);
    $oldMaterial = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$oldMaterial) {
        throw new Exception('Material not found.');
    }

    // Check if another material has the same name (excluding current)
    $dupStmt = $pdo->prepare('SELECT id FROM materials WHERE material_name = ? AND id != ?');
    $dupStmt->execute([$materialName, $id]);
    if ($dupStmt->fetch()) {
        throw new Exception('Material "' . $materialName . '" already exists.');
    }

    // Update material item
    $stmt = $pdo->prepare('
        UPDATE materials 
        SET material_name = ?, type = ?, shop_stock = ?, ph_stock = ?, total_stock = ?, total_cost = ?, unit_cost = ?, updated_at = NOW()
        WHERE id = ?
    ');

    $result = $stmt->execute([
        $materialName,
        $type,
        $shopStock,
        $phStock,
        $totalStock,
        $totalCost,
        $unitCost,
        $id
    ]);

    if (!$result) {
        throw new Exception('Failed to update material item.');
    }

    // Log the changes if stock changed (if materials_log table exists)
    $adminName = $_SESSION['admin_name'] ?? 'Admin';

    // Check if materials_log table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'materials_log'");
    $hasLogTable = $tableCheck->rowCount() > 0;

    if ($hasLogTable) {
        if ($oldMaterial['shop_stock'] != $shopStock) {
            $logStmt = $pdo->prepare('
                INSERT INTO materials_log (material_id, material_name, type, location, quantity_change, before_quantity, after_quantity, admin_name, note, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ');
            $delta = $shopStock - $oldMaterial['shop_stock'];
            $logStmt->execute([
                $id,
                $materialName,
                $delta > 0 ? 'add' : 'subtract',
                'shop_stock',
                abs($delta),
                $oldMaterial['shop_stock'],
                $shopStock,
                $adminName,
                'Stock updated via edit item'
            ]);
        }

        if ($oldMaterial['ph_stock'] != $phStock) {
            $logStmt = $pdo->prepare('
                INSERT INTO materials_log (material_id, material_name, type, location, quantity_change, before_quantity, after_quantity, admin_name, note, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ');
            $delta = $phStock - $oldMaterial['ph_stock'];
            $logStmt->execute([
                $id,
                $materialName,
                $delta > 0 ? 'add' : 'subtract',
                'ph_stock',
                abs($delta),
                $oldMaterial['ph_stock'],
                $phStock,
                $adminName,
                'Stock updated via edit item'
            ]);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Item updated successfully.',
        'item' => [
            'id' => $id,
            'material_name' => $materialName,
            'type' => $type,
            'shop_stock' => $shopStock,
            'ph_stock' => $phStock,
            'total_stock' => $totalStock,
            'unit_cost' => $unitCost
        ]
    ]);
} catch (Exception $e) {
    error_log('update_material_item.php ERROR: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
