<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../../connect/config.php';

function logActivity(PDO $pdo, string $action, string $details, string $status, $referenceId = null): void
{
    $userId = $_SESSION['AdminID'] ?? null;
    $userName = $_SESSION['FullName'] ?? 'System/Unknown';
    $stmt = $pdo->prepare(
        "INSERT INTO activity_logs (UserID, UserName, ActionType, ActionDetails, ReferenceID, Status)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$userId, $userName, $action, $details, $referenceId, $status]);
}

try {
    $pdo = getDBConnection();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception('Invalid ID');
    }

    // Fetch old data
    $oldStmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
    $oldStmt->execute([$id]);
    $old = $oldStmt->fetch(PDO::FETCH_ASSOC);
    if (!$old) {
        throw new Exception('Item not found');
    }

    $material_name = trim($data['material_name'] ?? '');
    $sku = trim($data['sku'] ?? '');
    $type = trim($data['type'] ?? '');
    $category = trim($data['category'] ?? '');
    $is_product = (int)($data['is_product'] ?? $old['is_product']);
    $shop_stock = (int)($data['shop_stock'] ?? 0);
    $ph_stock = (int)($data['ph_stock'] ?? 0);
    $unit_cost = (float)($data['unit_cost'] ?? 0);
    $pieces_per_pack = (int)($data['pieces_per_pack'] ?? 1);
    $remarks = trim($data['remarks'] ?? '');
    $description = trim($data['description'] ?? '');
    $low_stock_threshold = (int)($data['low_stock_threshold'] ?? 5);

    // Validation
    if (empty($material_name)) {
        throw new Exception('Name is required');
    }

    if (strlen($material_name) < 2) {
        throw new Exception('Name must be at least 2 characters');
    }

    // Check for duplicate (excluding current)
    $checkStmt = $pdo->prepare("SELECT id FROM materials WHERE material_name = ? AND id != ?");
    $checkStmt->execute([$material_name, $id]);
    if ($checkStmt->fetch()) {
        throw new Exception('Name already exists');
    }

    $total_stock = $shop_stock + $ph_stock;
    $total_cost = $total_stock * $unit_cost;

    $stmt = $pdo->prepare("
        UPDATE materials 
        SET material_name = ?, sku = ?, type = ?, category = ?, is_product = ?,
            shop_stock = ?, ph_stock = ?, total_stock = ?, unit_cost = ?, total_cost = ?,
            pieces_per_pack = ?, remarks = ?, description = ?, low_stock_threshold = ?, updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $material_name,
        $sku,
        $type,
        $category,
        $is_product,
        $shop_stock,
        $ph_stock,
        $total_stock,
        $unit_cost,
        $total_cost,
        $pieces_per_pack,
        $remarks,
        $description,
        $low_stock_threshold,
        $id
    ]);

    // Log changes
    $changes = [];
    if ($old['material_name'] !== $material_name) $changes[] = "Name: \"{$old['material_name']}\" → \"$material_name\"";
    if (($old['type'] ?? '') !== $type) $changes[] = "Type: {$old['type']} → $type";
    if (($old['category'] ?? '') !== $category) $changes[] = "Category: {$old['category']} → $category";
    if ((int)$old['shop_stock'] !== $shop_stock) $changes[] = "Shop Stock: {$old['shop_stock']} → $shop_stock";
    if ((int)$old['ph_stock'] !== $ph_stock) $changes[] = "PH Stock: {$old['ph_stock']} → $ph_stock";
    if ((float)$old['unit_cost'] !== $unit_cost) $changes[] = "Unit Cost: ₱{$old['unit_cost']} → ₱$unit_cost";

    $detail = empty($changes)
        ? "Updated item \"$material_name\" (ID: $id) — no changes"
        : "Updated item \"$material_name\" (ID: $id): " . implode(', ', $changes);

    logActivity($pdo, 'Update Item', $detail, 'Success', $id);

    echo json_encode(['success' => true, 'message' => 'Item updated successfully!']);
} catch (Exception $e) {
    if (isset($pdo)) {
        logActivity($pdo, 'Update Item', "Failed to update item (ID: {$id}): " . $e->getMessage(), 'Failed', $id ?? null);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
