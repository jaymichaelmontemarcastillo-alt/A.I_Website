<?php
// api/admin_site/inventory/create_audit.php
// FIXED VERSION - Properly saves audit logs and handles manual material entry

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../../../logs/audit_error.log');

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/../../../logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

function audit_log($message, $data = null)
{
    $logFile = __DIR__ . '/../../../logs/audit_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message";
    if ($data) {
        $logEntry .= " - " . print_r($data, true);
    }
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
}

audit_log("=== AUDIT API CALLED ===");

// Clean output buffers
while (ob_get_level()) {
    ob_end_clean();
}
header('Content-Type: application/json');

$configPath = __DIR__ . '/../../../connect/config.php';
if (!file_exists($configPath)) {
    echo json_encode(['success' => false, 'message' => 'Configuration file not found']);
    exit;
}

require_once $configPath;

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized - Please login again');
    }

    $adminId = (int) $_SESSION['admin_id'];
    $adminName = $_SESSION['admin_name'] ?? 'Admin';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $rawInput = file_get_contents('php://input');
    if (empty($rawInput)) {
        throw new Exception('No input data received');
    }

    $input = json_decode($rawInput, true);
    if ($input === null) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    // Extract data
    $items = isset($input['items']) && is_array($input['items']) ? $input['items'] : [];
    $materials = isset($input['materials']) && is_array($input['materials']) ? $input['materials'] : [];
    $rejects = isset($input['rejects']) && is_array($input['rejects']) ? $input['rejects'] : [];
    $signatures = [
        'created_by' => $input['created_by'] ?? $adminName,
        'audited_by' => $input['audited_by'] ?? '',
        'acknowledged_by' => $input['acknowledged_by'] ?? ''
    ];
    $autoCompute = isset($input['auto_compute']) ? (bool)$input['auto_compute'] : true;

    if (empty($materials) && empty($items)) {
        throw new Exception('Please add at least one material or item');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    $pdo->beginTransaction();

    // Calculate totals
    $totalMaterialCost = 0;
    foreach ($materials as $mat) {
        $totalMaterialCost += floatval($mat['total_cost'] ?? 0);
    }

    $totalRejectCost = 0;
    foreach ($rejects as $rej) {
        $totalRejectCost += floatval($rej['total_cost'] ?? 0);
    }

    $totalAmountDue = 0;
    foreach ($items as $item) {
        $totalAmountDue += floatval($item['total_amount'] ?? 0);
    }

    // Check if manual profit is provided
    if (isset($input['manual_profit'])) {
        $profit = floatval($input['manual_profit']);
    } else {
        $profit = $totalAmountDue - ($totalMaterialCost + $totalRejectCost);
    }

    // Insert into bom_audit
    $itemsJson = json_encode($items, JSON_UNESCAPED_UNICODE);
    $materialsJson = json_encode($materials, JSON_UNESCAPED_UNICODE);
    $rejectsJson = json_encode($rejects, JSON_UNESCAPED_UNICODE);
    $signaturesJson = json_encode($signatures, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("
        INSERT INTO bom_audit 
        (items, materials, rejects, signatures, total_material_cost, total_reject_cost, total_amount_due, profit, auto_compute, is_completed, created_at)
        VALUES 
        (:items, :materials, :rejects, :signatures, :total_material_cost, :total_reject_cost, :total_amount_due, :profit, :auto_compute, 1, NOW())
    ");

    $stmt->execute([
        ':items' => $itemsJson,
        ':materials' => $materialsJson,
        ':rejects' => $rejectsJson,
        ':signatures' => $signaturesJson,
        ':total_material_cost' => $totalMaterialCost,
        ':total_reject_cost' => $totalRejectCost,
        ':total_amount_due' => $totalAmountDue,
        ':profit' => $profit,
        ':auto_compute' => $autoCompute ? 1 : 0
    ]);

    $auditId = $pdo->lastInsertId();
    audit_log("Audit created with ID: $auditId");

    // Process materials - DEDUCT from inventory
    $loggedCount = 0;

    foreach ($materials as $mat) {
        $materialId = isset($mat['id']) ? (int)$mat['id'] : 0;
        $quantity = isset($mat['quantity']) ? (int)$mat['quantity'] : 0;
        $materialName = isset($mat['name']) ? $mat['name'] : 'Unknown';
        $unitCost = isset($mat['unit_cost']) ? (float)$mat['unit_cost'] : 0;

        if ($quantity <= 0 || $materialId <= 0) {
            continue;
        }

        // Check if material exists
        $checkStmt = $pdo->prepare("SELECT id, material_name, total_stock, shop_stock, ph_stock FROM materials WHERE id = :id FOR UPDATE");
        $checkStmt->execute([':id' => $materialId]);
        $dbMaterial = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbMaterial) {
            audit_log("Material not found: $materialId");
            continue;
        }

        $currentStock = (int) $dbMaterial['total_stock'];
        audit_log("Current stock for {$dbMaterial['material_name']}: $currentStock");

        if ($currentStock >= $quantity) {
            $newStock = $currentStock - $quantity;

            // Update material total stock
            $updateMat = $pdo->prepare("UPDATE materials SET total_stock = :stock WHERE id = :id");
            $updateMat->execute([':stock' => $newStock, ':id' => $materialId]);

            // Update shop stock proportionally if possible
            $shopStock = (int) $dbMaterial['shop_stock'];
            if ($shopStock > 0 && $currentStock > 0) {
                $ratio = $shopStock / $currentStock;
                $newShopStock = max(0, $shopStock - round($quantity * $ratio));
                $updateShop = $pdo->prepare("UPDATE materials SET shop_stock = :stock WHERE id = :id");
                $updateShop->execute([':stock' => $newShopStock, ':id' => $materialId]);
            }

            // Update PH stock proportionally
            $phStock = (int) $dbMaterial['ph_stock'];
            if ($phStock > 0 && $currentStock > 0) {
                $ratio = $phStock / $currentStock;
                $newPhStock = max(0, $phStock - round($quantity * $ratio));
                $updatePh = $pdo->prepare("UPDATE materials SET ph_stock = :stock WHERE id = :id");
                $updatePh->execute([':stock' => $newPhStock, ':id' => $materialId]);
            }

            // Insert into inventory_logs
            $logStmt = $pdo->prepare("
                INSERT INTO inventory_logs 
                (material_id, change_type, quantity, previous_stock, new_stock, admin_id, note, audit_id, created_at)
                VALUES 
                (:material_id, 'order', :quantity, :prev, :new, :admin_id, :note, :audit_id, NOW())
            ");
            $logStmt->execute([
                ':material_id' => $materialId,
                ':quantity' => $quantity,
                ':prev' => $currentStock,
                ':new' => $newStock,
                ':admin_id' => $adminId,
                ':note' => "Audit #{$auditId}: Used {$quantity} x {$materialName}",
                ':audit_id' => $auditId
            ]);
            $loggedCount++;
        } else {
            throw new Exception("Insufficient stock for {$dbMaterial['material_name']}. Available: {$currentStock}, Requested: {$quantity}");
        }
    }

    // ============================================================
    // FIXED: Insert into audit_logs - This was the problem!
    // ============================================================
    try {
        $auditLogSQL = "
            INSERT INTO audit_logs (audit_id, action, admin_id, details, created_at)
            VALUES (:audit_id, 'create', :admin_id, :details, NOW())
        ";
        $auditLogStmt = $pdo->prepare($auditLogSQL);
        $auditLogStmt->execute([
            ':audit_id' => $auditId,
            ':admin_id' => $adminId,
            ':details' => json_encode([
                'items_count' => count($items),
                'materials_count' => count($materials),
                'rejects_count' => count($rejects),
                'logged_count' => $loggedCount,
                'total_amount' => $totalAmountDue,
                'total_material_cost' => $totalMaterialCost,
                'profit' => $profit
            ])
        ]);
        audit_log("Audit log created successfully for audit_id: $auditId");
    } catch (PDOException $logErr) {
        audit_log("WARNING: Could not create audit log: " . $logErr->getMessage());
        // Don't throw - this is non-critical
    }

    $pdo->commit();
    audit_log("Transaction committed successfully");

    while (ob_get_level()) {
        ob_end_clean();
    }

    echo json_encode([
        'success' => true,
        'message' => "Audit created successfully! {$loggedCount} inventory changes logged.",
        'audit_id' => $auditId,
        'logged_count' => $loggedCount,
        'totals' => [
            'total_material_cost' => $totalMaterialCost,
            'total_reject_cost' => $totalRejectCost,
            'total_amount_due' => $totalAmountDue,
            'profit' => $profit
        ]
    ]);
} catch (Exception $e) {
    audit_log("EXCEPTION: " . $e->getMessage());

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        audit_log("Transaction rolled back");
    }

    while (ob_get_level()) {
        ob_end_clean();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
exit;
