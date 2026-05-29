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

    $material_name = trim($data['material_name'] ?? '');
    $type = trim($data['type'] ?? '');
    $category = trim($data['category'] ?? '');
    $shop_stock = (int)($data['shop_stock'] ?? 0);
    $ph_stock = (int)($data['ph_stock'] ?? 0);
    $unit_cost = (float)($data['unit_cost'] ?? 0);
    $pieces_per_pack = (int)($data['pieces_per_pack'] ?? 1);
    $remarks = trim($data['remarks'] ?? '');
    $low_stock_threshold = (int)($data['low_stock_threshold'] ?? 5);

    // Validation
    if (empty($material_name)) {
        throw new Exception('Material name is required');
    }

    if (strlen($material_name) < 2) {
        throw new Exception('Material name must be at least 2 characters');
    }

    if ($shop_stock < 0 || $ph_stock < 0) {
        throw new Exception('Stock values cannot be negative');
    }

    if ($unit_cost < 0) {
        throw new Exception('Unit cost cannot be negative');
    }

    if ($pieces_per_pack < 1) {
        throw new Exception('Pieces per pack must be at least 1');
    }

    $total_stock = $shop_stock + $ph_stock;
    $total_cost = $total_stock * $unit_cost;

    // Check for duplicate material name
    $checkStmt = $pdo->prepare("SELECT id FROM materials WHERE material_name = ?");
    $checkStmt->execute([$material_name]);
    if ($checkStmt->fetch()) {
        throw new Exception('Material name already exists');
    }

    $stmt = $pdo->prepare("
        INSERT INTO materials (material_name, type, category, shop_stock, ph_stock, total_stock, 
                               unit_cost, total_cost, pieces_per_pack, remarks, low_stock_threshold)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $material_name,
        $type,
        $category,
        $shop_stock,
        $ph_stock,
        $total_stock,
        $unit_cost,
        $total_cost,
        $pieces_per_pack,
        $remarks,
        $low_stock_threshold
    ]);

    $newId = $pdo->lastInsertId();

    logActivity($pdo, 'Add Material', "Added new material: \"$material_name\" | Type: $type | Category: $category | Stock: $total_stock", 'Success', $newId);

    echo json_encode(['success' => true, 'message' => 'Material added successfully!', 'id' => $newId]);
} catch (Exception $e) {
    if (isset($pdo)) {
        $attemptedName = trim($data['material_name'] ?? 'Unknown');
        logActivity($pdo, 'Add Material', "Failed to add material \"$attemptedName\": " . $e->getMessage(), 'Failed');
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
