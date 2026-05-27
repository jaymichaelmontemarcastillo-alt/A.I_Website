<?php
// api/admin_site/inventory/create_audit.php
// SAFE VERSION WITH ERROR LOGGING

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../../../logs/audit_error.log');

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/../../../logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

// Function to log errors
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
audit_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

// Clean output buffers
while (ob_get_level()) {
    ob_end_clean();
}
header('Content-Type: application/json');

// Configuration
$configPath = __DIR__ . '/../../../connect/config.php';
audit_log("Config path: " . $configPath);

if (!file_exists($configPath)) {
    audit_log("ERROR: Config file not found");
    echo json_encode(['success' => false, 'message' => 'Configuration file not found']);
    exit;
}

require_once $configPath;

// Check if getDBConnection function exists
if (!function_exists('getDBConnection')) {
    audit_log("ERROR: getDBConnection function not found in config");
    echo json_encode(['success' => false, 'message' => 'Database connection function not found']);
    exit;
}

try {
    // Session check
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    audit_log("Session admin_id: " . ($_SESSION['admin_id'] ?? 'NOT SET'));

    if (empty($_SESSION['admin_id'])) {
        audit_log("ERROR: Unauthorized - no admin_id in session");
        throw new Exception('Unauthorized - Please login again');
    }

    $adminId = (int) $_SESSION['admin_id'];

    // Method check
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        audit_log("ERROR: Method not allowed - " . $_SERVER['REQUEST_METHOD']);
        throw new Exception('Method not allowed');
    }

    // Get input
    $rawInput = file_get_contents('php://input');
    audit_log("Raw input length: " . strlen($rawInput));

    if (empty($rawInput)) {
        audit_log("ERROR: No input data received");
        throw new Exception('No input data received');
    }

    $input = json_decode($rawInput, true);
    if ($input === null) {
        audit_log("ERROR: JSON decode failed - " . json_last_error_msg());
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    audit_log("Input data: " . json_encode(array_keys($input)));

    // Extract data with defaults
    $items = isset($input['items']) && is_array($input['items']) ? $input['items'] : [];
    $materials = isset($input['materials']) && is_array($input['materials']) ? $input['materials'] : [];
    $rejects = isset($input['rejects']) && is_array($input['rejects']) ? $input['rejects'] : [];
    $signatures = [
        'created_by' => $input['created_by'] ?? ($_SESSION['admin_name'] ?? 'Admin'),
        'audited_by' => $input['audited_by'] ?? '',
        'acknowledged_by' => $input['acknowledged_by'] ?? ''
    ];
    $autoCompute = isset($input['auto_compute']) ? (bool)$input['auto_compute'] : true;

    audit_log("Materials count: " . count($materials));
    audit_log("Items count: " . count($items));
    audit_log("Rejects count: " . count($rejects));

    if (empty($materials) && empty($items)) {
        audit_log("ERROR: No materials or items");
        throw new Exception('Please add at least one material or item');
    }

    // Database connection
    $pdo = getDBConnection();
    if (!$pdo) {
        audit_log("ERROR: Database connection failed");
        throw new Exception('Database connection failed');
    }
    audit_log("Database connection successful");

    $pdo->beginTransaction();
    audit_log("Transaction started");

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
        audit_log("Using manual profit: $profit");
    } else {
        $profit = $totalAmountDue - ($totalMaterialCost + $totalRejectCost);
        audit_log("Auto-calculated profit: $profit");
    }

    audit_log("Totals - Material: $totalMaterialCost, Reject: $totalRejectCost, Amount: $totalAmountDue, Profit: $profit");

    // Check if bom_audit table exists and has correct columns
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'bom_audit'");
    if (!$tableCheck || $tableCheck->rowCount() === 0) {
        audit_log("ERROR: bom_audit table does not exist");
        throw new Exception('bom_audit table does not exist. Please run the database setup SQL.');
    }
    audit_log("bom_audit table exists");

    // Get column list for bom_audit
    $columns = $pdo->query("SHOW COLUMNS FROM bom_audit");
    $columnNames = [];
    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
        $columnNames[] = $col['Field'];
    }
    audit_log("bom_audit columns: " . implode(', ', $columnNames));

    // Prepare data for insertion
    $itemsJson = json_encode($items, JSON_UNESCAPED_UNICODE);
    $materialsJson = json_encode($materials, JSON_UNESCAPED_UNICODE);
    $rejectsJson = json_encode($rejects, JSON_UNESCAPED_UNICODE);
    $signaturesJson = json_encode($signatures, JSON_UNESCAPED_UNICODE);

    // Build INSERT statement based on available columns
    $insertFields = ['items', 'materials', 'rejects', 'signatures', 'created_at'];
    $insertValues = [':items', ':materials', ':rejects', ':signatures', 'NOW()'];

    if (in_array('total_material_cost', $columnNames)) {
        $insertFields[] = 'total_material_cost';
        $insertValues[] = ':total_material_cost';
    }
    if (in_array('total_reject_cost', $columnNames)) {
        $insertFields[] = 'total_reject_cost';
        $insertValues[] = ':total_reject_cost';
    }
    if (in_array('total_amount_due', $columnNames)) {
        $insertFields[] = 'total_amount_due';
        $insertValues[] = ':total_amount_due';
    }
    if (in_array('profit', $columnNames)) {
        $insertFields[] = 'profit';
        $insertValues[] = ':profit';
    }
    if (in_array('auto_compute', $columnNames)) {
        $insertFields[] = 'auto_compute';
        $insertValues[] = ':auto_compute';
    }
    if (in_array('is_completed', $columnNames)) {
        $insertFields[] = 'is_completed';
        $insertValues[] = '1';
    }

    $insertSQL = "INSERT INTO bom_audit (" . implode(', ', $insertFields) . ") 
                   VALUES (" . implode(', ', $insertValues) . ")";

    audit_log("Insert SQL: " . $insertSQL);

    $stmt = $pdo->prepare($insertSQL);

    // Bind parameters
    $stmt->bindValue(':items', $itemsJson);
    $stmt->bindValue(':materials', $materialsJson);
    $stmt->bindValue(':rejects', $rejectsJson);
    $stmt->bindValue(':signatures', $signaturesJson);

    if (in_array('total_material_cost', $columnNames)) {
        $stmt->bindValue(':total_material_cost', $totalMaterialCost);
    }
    if (in_array('total_reject_cost', $columnNames)) {
        $stmt->bindValue(':total_reject_cost', $totalRejectCost);
    }
    if (in_array('total_amount_due', $columnNames)) {
        $stmt->bindValue(':total_amount_due', $totalAmountDue);
    }
    if (in_array('profit', $columnNames)) {
        $stmt->bindValue(':profit', $profit);
    }
    if (in_array('auto_compute', $columnNames)) {
        $stmt->bindValue(':auto_compute', $autoCompute ? 1 : 0);
    }

    $stmt->execute();
    $auditId = $pdo->lastInsertId();
    audit_log("Audit created with ID: $auditId");

    // Process materials - DEDUCT from inventory
    $loggedCount = 0;
    $failedItems = [];

    // Check inventory_logs table structure
    $logsColumns = $pdo->query("SHOW COLUMNS FROM inventory_logs");
    $logsColumnNames = [];
    while ($col = $logsColumns->fetch(PDO::FETCH_ASSOC)) {
        $logsColumnNames[] = $col['Field'];
    }
    audit_log("inventory_logs columns: " . implode(', ', $logsColumnNames));

    foreach ($materials as $mat) {
        $materialId = isset($mat['id']) ? (int)$mat['id'] : 0;
        $quantity = isset($mat['quantity']) ? (int)$mat['quantity'] : 0;
        $materialName = isset($mat['name']) ? $mat['name'] : 'Unknown';

        audit_log("Processing material: ID=$materialId, QTY=$quantity, Name=$materialName");

        if ($quantity <= 0 || $materialId <= 0) {
            audit_log("Skipping material - invalid data");
            continue;
        }

        // Check if material exists
        $checkStmt = $pdo->prepare("SELECT id, material_name, total_stock, shop_stock, ph_stock FROM materials WHERE id = :id");
        $checkStmt->execute([':id' => $materialId]);
        $dbMaterial = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbMaterial) {
            $failedItems[] = "Material ID {$materialId} not found";
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
            audit_log("Updated total_stock: $currentStock -> $newStock");

            // Update shop stock proportionally if possible
            $shopStock = (int) $dbMaterial['shop_stock'];
            if ($shopStock > 0 && $currentStock > 0) {
                $ratio = $shopStock / $currentStock;
                $newShopStock = max(0, $shopStock - round($quantity * $ratio));
                $updateShop = $pdo->prepare("UPDATE materials SET shop_stock = :stock WHERE id = :id");
                $updateShop->execute([':stock' => $newShopStock, ':id' => $materialId]);
                audit_log("Updated shop_stock: $shopStock -> $newShopStock");
            }

            // Update PH stock proportionally
            $phStock = (int) $dbMaterial['ph_stock'];
            if ($phStock > 0 && $currentStock > 0) {
                $ratio = $phStock / $currentStock;
                $newPhStock = max(0, $phStock - round($quantity * $ratio));
                $updatePh = $pdo->prepare("UPDATE materials SET ph_stock = :stock WHERE id = :id");
                $updatePh->execute([':stock' => $newPhStock, ':id' => $materialId]);
                audit_log("Updated ph_stock: $phStock -> $newPhStock");
            }

            // Insert into inventory_logs
            if (in_array('audit_id', $logsColumnNames)) {
                $logSQL = "INSERT INTO inventory_logs 
                           (material_id, change_type, quantity, previous_stock, new_stock, admin_id, note, audit_id, created_at)
                           VALUES (:material_id, 'order', :quantity, :prev, :new, :admin_id, :note, :audit_id, NOW())";
                $logStmt = $pdo->prepare($logSQL);
                $logResult = $logStmt->execute([
                    ':material_id' => $materialId,
                    ':quantity' => $quantity,
                    ':prev' => $currentStock,
                    ':new' => $newStock,
                    ':admin_id' => $adminId,
                    ':note' => "Audit #{$auditId}: Used {$quantity} x {$materialName}",
                    ':audit_id' => $auditId
                ]);
            } else {
                $logSQL = "INSERT INTO inventory_logs 
                           (material_id, change_type, quantity, previous_stock, new_stock, admin_id, note, created_at)
                           VALUES (:material_id, 'order', :quantity, :prev, :new, :admin_id, :note, NOW())";
                $logStmt = $pdo->prepare($logSQL);
                $logResult = $logStmt->execute([
                    ':material_id' => $materialId,
                    ':quantity' => $quantity,
                    ':prev' => $currentStock,
                    ':new' => $newStock,
                    ':admin_id' => $adminId,
                    ':note' => "Audit #{$auditId}: Used {$quantity} x {$materialName}"
                ]);
            }

            if ($logResult) {
                $loggedCount++;
                audit_log("Inventory log created for material $materialId");
            }
        } else {
            $errorMsg = "Insufficient stock for {$dbMaterial['material_name']}. Available: {$currentStock}, Requested: {$quantity}";
            audit_log("ERROR: $errorMsg");
            throw new Exception($errorMsg);
        }
    }

    // Insert audit log
    $auditLogSQL = "INSERT INTO audit_logs (audit_id, action, admin_id, details, created_at)
                    VALUES (:audit_id, 'create', :admin_id, :details, NOW())";
    $auditLogStmt = $pdo->prepare($auditLogSQL);
    $auditLogStmt->execute([
        ':audit_id' => $auditId,
        ':admin_id' => $adminId,
        ':details' => json_encode([
            'items_count' => count($items),
            'materials_count' => count($materials),
            'rejects_count' => count($rejects),
            'logged_count' => $loggedCount,
            'total_amount' => $totalAmountDue
        ])
    ]);
    audit_log("Audit log created");

    $pdo->commit();
    audit_log("Transaction committed successfully");

    // Clear any output buffers before sending JSON
    while (ob_get_level()) {
        ob_end_clean();
    }

    echo json_encode([
        'success' => true,
        'message' => "Audit created successfully! {$loggedCount} inventory changes logged.",
        'audit_id' => $auditId,
        'logged_count' => $loggedCount,
        'failed_items' => $failedItems,
        'totals' => [
            'total_material_cost' => $totalMaterialCost,
            'total_reject_cost' => $totalRejectCost,
            'total_amount_due' => $totalAmountDue,
            'profit' => $profit
        ]
    ]);
} catch (Exception $e) {
    audit_log("EXCEPTION: " . $e->getMessage());
    audit_log("Stack trace: " . $e->getTraceAsString());

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        audit_log("Transaction rolled back");
    }

    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug_info' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
exit;
