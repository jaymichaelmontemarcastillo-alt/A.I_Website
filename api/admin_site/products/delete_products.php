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
        throw new Exception('Invalid product ID');
    }

    // Get product info before deletion
    $stmt = $pdo->prepare("SELECT name, image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        throw new Exception('Product not found');
    }

    // Delete image if not default
    if ($product['image'] && strpos($product['image'], 'default') === false && file_exists('../../../' . $product['image'])) {
        @unlink('../../../' . $product['image']);
    }

    // Delete the product
    $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $deleteStmt->execute([$id]);

    logActivity($pdo, 'Delete Product', "Deleted product \"{$product['name']}\" (ID: $id)", 'Success', $id);

    echo json_encode(['success' => true, 'message' => 'Product deleted successfully!']);
} catch (Exception $e) {
    if (isset($pdo)) {
        logActivity($pdo, 'Delete Product', "Failed to delete product (ID: {$id}): " . $e->getMessage(), 'Failed', $id ?? null);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
