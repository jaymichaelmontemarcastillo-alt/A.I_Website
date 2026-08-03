<?php
header('Content-Type: application/json');
session_start();
require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

try {
    $pdo = getDBConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Get admin details for logging
    $adminId = $_SESSION['admin_id'] ?? null;
    $adminName = 'Unknown';
    if ($adminId) {
        $stmt = $pdo->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminName = $admin ? $admin['FullName'] : 'Unknown';
    }

    // Get the quote details before updating
    $stmt = $pdo->prepare("SELECT quote_number, client_name, status FROM quotations WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    $quote_number = $quote ? $quote['quote_number'] : '';
    $client_name = $quote ? $quote['client_name'] : 'Unknown';
    $old_status = $quote ? $quote['status'] : 'Unknown';

    $query = "UPDATE quotations SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['id']
    ]);

    // ✅ LOG ACTIVITY - Success
    logActivity(
        $pdo,
        $adminId,
        $adminName,
        "Changed status of quotation #{$quote_number} (ID: {$data['id']}) for client '{$client_name}' from '{$old_status}' to '{$data['status']}'",
        $data['id'],
        "Quotation Status",
        "Success"
    );

    echo json_encode([
        'success' => true,
        'message' => 'Status updated',
        'quote_number' => $quote_number,
        'id' => $data['id']
    ]);
} catch (PDOException $e) {
    // ❌ LOG FAILED ACTIVITY
    if (isset($pdo)) {
        $adminId = $_SESSION['admin_id'] ?? null;
        $adminName = 'Unknown';
        if ($adminId) {
            try {
                $stmt = $pdo->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
                $stmt->execute([$adminId]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                $adminName = $admin ? $admin['FullName'] : 'Unknown';
            } catch (Exception $e) {
                // Ignore
            }
        }
        
        logActivity(
            $pdo,
            $adminId,
            $adminName,
            "Failed to update quotation status (ID: {$data['id']}): " . $e->getMessage(),
            $data['id'] ?? null,
            "Quotation Status",
            "Failed"
        );
    }
    
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}