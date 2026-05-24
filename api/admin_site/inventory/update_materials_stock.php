<?php
// ============================================================
//  api/admin_site/materials/update_material_stock.php
//  Admin manual stock management for materials (STANDALONE)
//  Methods : POST
//  Actions : add | subtract | adjust
// ============================================================

declare(strict_types=1);
header('Content-Type: application/json');

require_once '../../../connect/config.php';

// ── Auth session guard ──────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$adminId = (int) $_SESSION['admin_id'];

// ── Only POST ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// ── Parse JSON body ─────────────────────────────────────────
$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    $body = $_POST;
}

$materialId = isset($body['material_id']) ? (int) $body['material_id'] : 0;
$location   = trim($body['location'] ?? 'total_stock'); // 'shop_stock', 'ph_stock', or 'total_stock'
$action     = trim($body['action']   ?? '');
$quantity   = isset($body['quantity']) ? (int) $body['quantity'] : 0;
$note       = trim($body['note']     ?? '');

// ── Validate ─────────────────────────────────────────────────
$errors = [];

if ($materialId <= 0) {
    $errors[] = 'Invalid material_id.';
}

if (!in_array($location, ['shop_stock', 'ph_stock', 'total_stock'], true)) {
    $errors[] = 'location must be one of: shop_stock, ph_stock, total_stock.';
}

if (!in_array($action, ['add', 'subtract', 'adjust'], true)) {
    $errors[] = 'action must be one of: add, subtract, adjust.';
}

if ($quantity < 0) {
    $errors[] = 'quantity must be a non-negative integer.';
}

if ($action !== 'adjust' && $quantity === 0) {
    $errors[] = 'quantity must be greater than 0 for add/subtract.';
}

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ── Database work ────────────────────────────────────────────
try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Lock the material row for update
    $stmt = $pdo->prepare(
        "SELECT id, material_name, shop_stock, ph_stock, total_stock FROM materials WHERE id = :id FOR UPDATE"
    );
    $stmt->execute([':id' => $materialId]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$material) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Material not found.']);
        exit;
    }

    $prevStock = (int) $material[$location];

    // Compute new stock
    switch ($action) {
        case 'add':
            $newStock = $prevStock + $quantity;
            break;

        case 'subtract':
            if ($quantity > $prevStock) {
                $pdo->rollBack();
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => "Cannot remove {$quantity} units; only {$prevStock} in {$location}.",
                ]);
                exit;
            }
            $newStock = $prevStock - $quantity;
            break;

        case 'adjust':
            if ($quantity < 0) {
                $pdo->rollBack();
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Adjusted stock cannot be negative.']);
                exit;
            }
            $newStock = $quantity;
            // actual delta for the log
            $quantity = abs($newStock - $prevStock);
            break;

        default:
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Unknown action.']);
            exit;
    }

    // Determine the UPDATE column based on location
    $updateField = match ($location) {
        'shop_stock'  => 'shop_stock',
        'ph_stock'    => 'ph_stock',
        'total_stock' => 'total_stock',
        default       => 'total_stock',
    };

    // Update materials table
    $upd = $pdo->prepare("UPDATE materials SET {$updateField} = :stock WHERE id = :id");
    $upd->execute([':stock' => $newStock, ':id' => $materialId]);

    // ── LOG THE CHANGE (Optional) ────────────────────────────
    // Only if materials_logs table exists and has the right schema
    try {
        $logSql = "INSERT INTO materials_logs 
                   (material_id, location, change_type, quantity, previous_stock, new_stock, admin_id, note, created_at)
                   VALUES (:material_id, :location, :change_type, :quantity, :previous_stock, :new_stock, :admin_id, :note, NOW())";

        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            ':material_id'    => $materialId,
            ':location'       => $location,
            ':change_type'    => $action,
            ':quantity'       => $quantity,
            ':previous_stock' => $prevStock,
            ':new_stock'      => $newStock,
            ':admin_id'       => $adminId,
            ':note'           => $note,
        ]);
    } catch (PDOException $logErr) {
        // Log table doesn't exist or has different schema — continue anyway
        error_log('materials_logs insert failed (table may not exist): ' . $logErr->getMessage());
    }

    $pdo->commit();

    echo json_encode([
        'success'        => true,
        'message'        => 'Stock updated successfully.',
        'material_id'    => $materialId,
        'material_name'  => $material['material_name'],
        'location'       => $location,
        'action'         => $action,
        'previous_stock' => $prevStock,
        'new_stock'      => $newStock,
    ]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('update_material_stock.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error. Please try again.',
    ]);
}
