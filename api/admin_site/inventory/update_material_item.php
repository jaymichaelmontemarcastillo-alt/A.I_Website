<?php

/**
 * api/admin_site/inventory/update_material_item.php
 * Updates an existing material item (name, type, stock, unit cost, remarks)
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

    // Get JSON input and fall back to form data if needed
    $rawBody = file_get_contents('php://input');
    $input = json_decode($rawBody, true);

    if (!is_array($input) || empty($input)) {
        $input = $_POST;
    }

    if (!is_array($input) || empty($input)) {
        $input = $_REQUEST;
    }

    if (!is_array($input) || empty($input)) {
        throw new Exception('Invalid input data.');
    }

    // Log the incoming data for debugging
    error_log('UPDATE_MATERIAL_ITEM - Input: ' . json_encode($input));

    // Sanitize and validate inputs
    $id = (int) ($input['id'] ?? 0);
    $materialName = trim((string) ($input['material_name'] ?? ''));
    $type = trim((string) ($input['type'] ?? ''));
    $shopStock = (int) ($input['shop_stock'] ?? 0);
    $phStock = (int) ($input['ph_stock'] ?? 0);
    $unitCost = (float) ($input['unit_cost'] ?? 0);
    $costPerPack = (float) ($input['cost_per_pack'] ?? $input['total_cost'] ?? 0);
    $piecesPerPack = (int) ($input['pieces_per_pack'] ?? $input['qty_per_pack'] ?? 1);
    if ($piecesPerPack <= 0) {
        $piecesPerPack = 1;
    }

    $totalStock = $shopStock + $phStock;
    if ($costPerPack > 0) {
        $totalCost = $costPerPack;
        if ($unitCost == 0 && $piecesPerPack > 0) {
            $unitCost = $costPerPack / $piecesPerPack;
        }
    } else {
        $totalCost = $totalStock * $unitCost;
        if ($unitCost > 0 && $piecesPerPack > 0) {
            $costPerPack = $unitCost * $piecesPerPack;
        }
    }

    $remarks = trim((string) ($input['remarks'] ?? $input['note'] ?? $input['remark'] ?? ''));

    error_log('UPDATE_MATERIAL_ITEM - Remarks extracted: "' . $remarks . '"');

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

    // Check if material exists and get current data
    $checkStmt = $pdo->prepare('SELECT * FROM materials WHERE id = ?');
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

    // ================================================================
    // Update materials table with ALL fields including pieces_per_pack & total_cost
    // ================================================================
    $stmt = $pdo->prepare('
        UPDATE materials 
        SET material_name = ?, 
            type = ?, 
            shop_stock = ?, 
            ph_stock = ?, 
            total_stock = ?, 
            total_cost = ?, 
            pieces_per_pack = ?,
            unit_cost = ?, 
            remarks = ?,
            updated_at = NOW()
        WHERE id = ?
    ');

    $result = $stmt->execute([
        $materialName,
        $type,
        $shopStock,
        $phStock,
        $totalStock,
        $costPerPack > 0 ? $costPerPack : $totalCost,
        $piecesPerPack,
        $unitCost,
        $remarks,
        $id
    ]);

    if (!$result) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception('Failed to update material item: ' . $errorInfo[2]);
    }

    // Verify the update was successful
    $verifyStmt = $pdo->prepare('SELECT id, material_name, remarks FROM materials WHERE id = ?');
    $verifyStmt->execute([$id]);
    $verified = $verifyStmt->fetch(PDO::FETCH_ASSOC);
    error_log('UPDATE_MATERIAL_ITEM - Verified remarks in materials table: "' . ($verified['remarks'] ?? 'NULL') . '"');

    $adminName = $_SESSION['admin_name'] ?? 'Admin';

    // Use the actual remarks for the log; fall back to the existing material remark if present.
    $noteText = trim($remarks) !== ''
        ? $remarks
        : (trim((string) ($oldMaterial['remarks'] ?? '')) !== '' ? (string) $oldMaterial['remarks'] : 'No remarks provided');

    // ================================================================
    // Log to materials_logs (separate from materials table)
    // ================================================================
    try {
        // Check if materials_logs table has a note column
        $logColumns = $pdo->query("SHOW COLUMNS FROM materials_logs");
        $logCols = $logColumns->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('note', $logCols)) {
            $logStmt = $pdo->prepare('
                INSERT INTO materials_logs (
                    material_id, location, change_type, quantity, previous_stock, new_stock, admin_id, note, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ');

            // Log shop stock changes
            if ($oldMaterial['shop_stock'] != $shopStock) {
                $quantity = abs($shopStock - $oldMaterial['shop_stock']);
                $changeType = ($shopStock > $oldMaterial['shop_stock']) ? 'add' : 'subtract';
                $logStmt->execute([
                    $id,
                    'shop_stock',
                    $changeType,
                    $quantity,
                    $oldMaterial['shop_stock'],
                    $shopStock,
                    $_SESSION['admin_id'],
                    $noteText,
                ]);
            }

            // Log PH stock changes
            if ($oldMaterial['ph_stock'] != $phStock) {
                $quantity = abs($phStock - $oldMaterial['ph_stock']);
                $changeType = ($phStock > $oldMaterial['ph_stock']) ? 'add' : 'subtract';
                $logStmt->execute([
                    $id,
                    'ph_stock',
                    $changeType,
                    $quantity,
                    $oldMaterial['ph_stock'],
                    $phStock,
                    $_SESSION['admin_id'],
                    $noteText,
                ]);
            }

            // If no stock changes, log an adjustment if other fields changed
            if ($oldMaterial['shop_stock'] == $shopStock && $oldMaterial['ph_stock'] == $phStock) {
                $nameChanged = $oldMaterial['material_name'] !== $materialName;
                $typeChanged = ($oldMaterial['type'] ?? '') !== $type;
                $costChanged = (float)($oldMaterial['unit_cost'] ?? 0) !== $unitCost;
                $remarksChanged = (trim($oldMaterial['remarks'] ?? '') !== $remarks);
                
                if ($nameChanged || $typeChanged || $costChanged || $remarksChanged || trim($remarks) !== '') {
                    $logStmt->execute([
                        $id,
                        'total_stock',
                        'adjust',
                        0,
                        $oldMaterial['total_stock'],
                        $totalStock,
                        $_SESSION['admin_id'],
                        $noteText,
                    ]);
                }
            }
        } else {
            error_log('UPDATE_MATERIAL_ITEM - materials_logs table does not have note column');
        }
    } catch (PDOException $logErr) {
        error_log('UPDATE_MATERIAL_ITEM - Log insert failed: ' . $logErr->getMessage());
    }

    // Activity log
    try {
        require_once __DIR__ . '/../../activity_logger.php';
        $activityMsg = 'Updated material "' . $materialName . '"';
        if (!empty($remarks)) {
            $activityMsg .= ' — ' . $remarks;
        }
        logActivity($pdo, $_SESSION['admin_id'], $adminName, $activityMsg, $id, 'Inventory', 'Success');
    } catch (Throwable $activityErr) {
        error_log('UPDATE_MATERIAL_ITEM - Activity log failed: ' . $activityErr->getMessage());
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
            'unit_cost' => $unitCost,
            'remarks' => $remarks
        ]
    ]);
} catch (Exception $e) {
    error_log('update_material_item.php ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;