<?php
// ============================================================
//  api/admin_site/update_order_status.php
//  Updates order status; restocks items when cancelled
//  POST body (JSON):
//    order_id    int
//    status      pending|paid|processing|packed|shipped|delivered|cancelled
//    note        string (optional)
// ============================================================

declare(strict_types=1);
header('Content-Type: application/json');

require_once '../../../connect/config.php';
require_once 'inventory_helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$adminId = (int) $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$orderId   = isset($body['order_id']) ? (int) $body['order_id'] : 0;
$newStatus = trim($body['status'] ?? '');
$note      = trim($body['note']   ?? '');

// ── Validate ─────────────────────────────────────────────────
$validStatuses = ['pending', 'paid', 'processing', 'packed', 'shipped', 'delivered', 'cancelled'];

if ($orderId <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid order_id.']);
    exit;
}

if (!in_array($newStatus, $validStatuses, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // ── Fetch order (lock row) ───────────────────────────────
    $orderStmt = $pdo->prepare(
        "SELECT id, order_number, order_status FROM orders WHERE id = :id FOR UPDATE"
    );
    $orderStmt->execute([':id' => $orderId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Order not found.']);
        exit;
    }

    $prevStatus = $order['order_status'];

    // No-op guard
    if ($prevStatus === $newStatus) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => "Order is already '{$newStatus}'.",
        ]);
        exit;
    }

    // ── Cancellation logic ───────────────────────────────────
    if ($newStatus === 'cancelled') {

        // Double-cancel guard
        if ($prevStatus === 'cancelled') {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Order is already cancelled.']);
            exit;
        }

        // Fetch order items
        $itemsStmt = $pdo->prepare(
            "SELECT product_id, product_name, quantity FROM order_items WHERE order_id = :oid"
        );
        $itemsStmt->execute([':oid' => $orderId]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $pid = (int) $item['product_id'];
            $qty = (int) $item['quantity'];

            // Lock product row, get current stock
            $pStmt = $pdo->prepare("SELECT stock FROM products WHERE id = :id FOR UPDATE");
            $pStmt->execute([':id' => $pid]);
            $prod = $pStmt->fetch(PDO::FETCH_ASSOC);

            if (!$prod) {
                // Product deleted; skip but don't fail the whole transaction
                continue;
            }

            $prevStock = (int) $prod['stock'];
            $newStock  = $prevStock + $qty;

            // Return stock
            $upd = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
            $upd->execute([':stock' => $newStock, ':id' => $pid]);

            // Log the return
            $logNote = $note ?: "Order #{$order['order_number']} cancelled – stock returned";
            logInventoryChange(
                $pdo,
                $pid,
                'return',
                $qty,
                $prevStock,
                $newStock,
                $adminId,
                $logNote
            );
        }
    }

    // ── Update order status ──────────────────────────────────
    $updOrder = $pdo->prepare("UPDATE orders SET order_status = :status WHERE id = :id");
    $updOrder->execute([':status' => $newStatus, ':id' => $orderId]);

    $pdo->commit();

    echo json_encode([
        'success'        => true,
        'message'        => 'Order status updated.',
        'order_id'       => $orderId,
        'order_number'   => $order['order_number'],
        'previous_status' => $prevStatus,
        'new_status'     => $newStatus,
    ]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('update_order_status.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
}
