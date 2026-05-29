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
    $location = $data['location'] ?? 'total_stock';
    $action = $data['action'] ?? 'add';
    $quantity = (int)($data['quantity'] ?? 0);
    $note = trim($data['note'] ?? '');

    if ($id <= 0) {
        throw new Exception('Invalid item ID');
    }

    if ($quantity <= 0) {
        throw new Exception('Quantity must be greater than 0');
    }

    // Get current stock
    $stmt = $pdo->prepare("SELECT material_name, shop_stock, ph_stock, total_stock, is_product FROM materials WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        throw new Exception('Item not found');
    }

    $oldShopStock = (int)$item['shop_stock'];
    $oldPhStock = (int)$item['ph_stock'];
    $oldTotalStock = (int)$item['total_stock'];

    $newShopStock = $oldShopStock;
    $newPhStock = $oldPhStock;
    $newTotalStock = $oldTotalStock;

    if ($location === 'shop_stock') {
        if ($action === 'add') {
            $newShopStock = $oldShopStock + $quantity;
        } elseif ($action === 'subtract') {
            if ($quantity > $oldShopStock) {
                throw new Exception("Cannot remove more than current shop stock ({$oldShopStock})");
            }
            $newShopStock = $oldShopStock - $quantity;
        } elseif ($action === 'adjust') {
            $newShopStock = $quantity;
        }
        $newTotalStock = $newShopStock + $oldPhStock;
    } elseif ($location === 'ph_stock') {
        if ($action === 'add') {
            $newPhStock = $oldPhStock + $quantity;
        } elseif ($action === 'subtract') {
            if ($quantity > $oldPhStock) {
                throw new Exception("Cannot remove more than current PH stock ({$oldPhStock})");
            }
            $newPhStock = $oldPhStock - $quantity;
        } elseif ($action === 'adjust') {
            $newPhStock = $quantity;
        }
        $newTotalStock = $oldShopStock + $newPhStock;
    } else {
        // total_stock update
        if ($action === 'add') {
            // Add to both locations proportionally? Just add to shop for simplicity
            $newShopStock = $oldShopStock + $quantity;
            $newTotalStock = $newShopStock + $oldPhStock;
        } elseif ($action === 'subtract') {
            if ($quantity > $oldTotalStock) {
                throw new Exception("Cannot remove more than current total stock ({$oldTotalStock})");
            }
            // Subtract from shop first, then from ph if needed
            $remaining = $quantity;
            $newShopStock = max(0, $oldShopStock - $remaining);
            $remaining -= ($oldShopStock - $newShopStock);
            $newPhStock = max(0, $oldPhStock - $remaining);
            $newTotalStock = $newShopStock + $newPhStock;
        } elseif ($action === 'adjust') {
            // Set total to specific value - adjust shop stock accordingly
            $difference = $quantity - $oldTotalStock;
            $newShopStock = max(0, $oldShopStock + $difference);
            $newTotalStock = $newShopStock + $oldPhStock;
        }
    }

    // Calculate total cost
    $unit_cost = (float)($data['unit_cost'] ?? 0);
    $newTotalCost = $newTotalStock * $unit_cost;

    // Update stock
    $updateStmt = $pdo->prepare("
        UPDATE materials 
        SET shop_stock = ?, ph_stock = ?, total_stock = ?, total_cost = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $updateStmt->execute([$newShopStock, $newPhStock, $newTotalStock, $newTotalCost, $id]);

    // Log the change
    $changeAmount = $newTotalStock - $oldTotalStock;
    $changeType = $changeAmount > 0 ? 'add' : ($changeAmount < 0 ? 'subtract' : 'adjust');
    $logNote = $note ?: "Stock updated via $action on $location: {$quantity} units";

    $logStmt = $pdo->prepare("
        INSERT INTO materials_log (material_id, material_name, change_type, location, quantity, previous_stock, new_stock, note, admin_id, admin_name)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $id,
        $item['material_name'],
        $changeType,
        $location,
        abs($changeAmount),
        $oldTotalStock,
        $newTotalStock,
        $logNote,
        $_SESSION['AdminID'] ?? null,
        $_SESSION['FullName'] ?? 'System'
    ]);

    $itemType = $item['is_product'] ? 'Product' : 'Material';
    logActivity($pdo, 'Stock Update', "Updated stock for $itemType \"{$item['material_name']}\": {$oldTotalStock} → {$newTotalStock} units", 'Success', $id);

    echo json_encode([
        'success' => true,
        'message' => "Stock updated successfully!",
        'material_name' => $item['material_name'],
        'new_stock' => $newTotalStock
    ]);
} catch (Exception $e) {
    if (isset($pdo)) {
        logActivity($pdo, 'Stock Update', "Failed to update stock: " . $e->getMessage(), 'Failed');
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
