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

    // Get item info before deletion
    $stmt = $pdo->prepare("SELECT material_name, is_product FROM materials WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        throw new Exception('Item not found');
    }

    // Delete the item
    $deleteStmt = $pdo->prepare("DELETE FROM materials WHERE id = ?");
    $deleteStmt->execute([$id]);

    $itemType = $item['is_product'] ? 'Product' : 'Material';
    logActivity($pdo, 'Delete Item', "Deleted $itemType \"{$item['material_name']}\" (ID: $id)", 'Success', $id);

    echo json_encode(['success' => true, 'message' => "$itemType deleted successfully!"]);
} catch (Exception $e) {
    if (isset($pdo)) {
        logActivity($pdo, 'Delete Item', "Failed to delete item (ID: {$id}): " . $e->getMessage(), 'Failed', $id ?? null);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
