<?php
// api/admin_site/inventory/create_audit.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');

$configPath = __DIR__ . '/../../../connect/config.php';
require_once $configPath;

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['admin_id'])) {
        throw new Exception('Unauthorized');
    }

    $adminId = (int) $_SESSION['admin_id'];
    $adminName = $_SESSION['admin_name'] ?? 'Admin';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    // Extract data matching your JavaScript payload
    $items = $input['items'] ?? [];
    $materials = $input['materials'] ?? [];
    $rejects = $input['rejects'] ?? [];
    $overhead = $input['overhead'] ?? [];
    $productionHours = floatval($input['production_hours'] ?? 0);
    $autoCompute = isset($input['auto_compute']) ? (bool)$input['auto_compute'] : true;
    $quotationId = isset($input['quotation_id']) ? (int)$input['quotation_id'] : 0;
    $auditName = $input['audit_name'] ?? '';
    
    // Extract electricity data
    $electricity = $input['electricity'] ?? ['shop' => 0, 'production' => 0];
    $electricityShop = floatval($electricity['shop'] ?? 0);
    $electricityProduction = floatval($electricity['production'] ?? 0);
    
    // Extract working hours
    $workingHours = floatval($input['working_hours'] ?? 250);
    $overheadPerHour = floatval($input['overhead_per_hour'] ?? 0);

    // Extract signatures/people data
    $signatures = [
        'created_by' => trim($input['created_by'] ?? $adminName),
        'audited_by' => trim($input['audited_by'] ?? ''),
        'acknowledged_by' => trim($input['acknowledged_by'] ?? '')
    ];

    if (empty($materials) && empty($items)) {
        throw new Exception('Please add at least one material or item');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Ensure remarks column exists BEFORE starting transaction (DDL causes implicit commit in MySQL)
    try {
        $pdo->exec("ALTER TABLE bom_audit ADD COLUMN remarks TEXT DEFAULT NULL");
    } catch (Exception $ex) {
        // Ignore if exists
    }

    $pdo->beginTransaction();

    // ── Guard: prevent creating a duplicate/blank audit that shadows an existing one ──
    if ($quotationId > 0) {
        $checkQuote = $pdo->prepare("SELECT audit_id FROM quotations WHERE id = :id FOR UPDATE");
        $checkQuote->execute([':id' => $quotationId]);
        $existingAuditId = $checkQuote->fetchColumn();

        if ($existingAuditId) {
            $pdo->rollBack();
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => "This quotation already has an audit (#{$existingAuditId}). Please edit the existing audit instead of creating a new one.",
                'existing_audit_id' => (int) $existingAuditId
            ]);
            exit;
        }
    }

    // Calculate totals from materials/rejects/items
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

    // Extract remarks
    $remarks = trim($input['remarks'] ?? '');

    // Extract overhead values & calculate total overhead
    $totalOverhead = 0;
    $shopRent = floatval($overhead['shop_rent'] ?? 0);
    $fixedSalaries = floatval($overhead['fixed_salaries'] ?? 0);
    $shopUtilities = floatval($overhead['shop_utilities'] ?? 0);
    $subscriptions = floatval($overhead['subscriptions'] ?? 0);
    $machineDepreciation = floatval($overhead['machine_depreciation'] ?? 0);
    $maintenanceRepair = floatval($overhead['maintenance_repair'] ?? 0);
    $marketing = floatval($overhead['marketing_promo'] ?? 0);

    if (is_array($overhead) && !empty($overhead)) {
        // If passed as array of rows [{item, quantity, percentage, total}]
        if (array_keys($overhead) === range(0, count($overhead) - 1)) {
            foreach ($overhead as $ovRow) {
                $totalOverhead += floatval($ovRow['total'] ?? 0);
            }
        } else {
            $totalOverhead = $shopRent + $fixedSalaries + $shopUtilities + $subscriptions +
                $machineDepreciation + $maintenanceRepair + $marketing;
        }
    } else {
        $totalOverhead = floatval($input['total_overhead'] ?? 0);
    }

    // Calculate overhead per hour if not provided
    if ($overheadPerHour <= 0 && $workingHours > 0) {
        $overheadPerHour = $totalOverhead / $workingHours;
    }

    $totalCostWithOverhead = $totalMaterialCost + $totalRejectCost + $totalOverhead;

    // Calculate profit (Total Sales - Total Cost)
    if (isset($input['manual_profit'])) {
        $profit = floatval($input['manual_profit']);
    } else {
        $profit = $totalAmountDue - $totalCostWithOverhead;
    }

    // Convert to JSON
    $itemsJson = json_encode($items);
    $materialsJson = json_encode($materials);
    $rejectsJson = json_encode($rejects);
    $signaturesJson = json_encode($signatures);
    $overheadJson = json_encode($overhead);

    // Get the quotation ID from the payload
    $quoteId = isset($input['quotation_id']) ? (int)$input['quotation_id'] : null;

    // Insert into bom_audit
    $stmt = $pdo->prepare("
        INSERT INTO bom_audit 
        (quote_id, items, materials, rejects, signatures,
         total_material_cost, total_reject_cost, total_amount_due, profit, auto_compute,
         overhead_shop_rent, overhead_fixed_salaries, overhead_shop_utilities, 
         overhead_subscriptions, overhead_machine_depreciation, overhead_maintenance_repair,
         overhead_marketing, overhead_electricity, total_overhead, overhead_per_hour,
         production_hours, total_cost_with_overhead, is_completed, created_at,
         electricity_shop, electricity_production, overhead_data, working_hours,
         audit_name, remarks)
        VALUES 
        (:quote_id, :items, :materials, :rejects, :signatures,
         :total_material_cost, :total_reject_cost, :total_amount_due, :profit, :auto_compute,
         :overhead_shop_rent, :overhead_fixed_salaries, :overhead_shop_utilities,
         :overhead_subscriptions, :overhead_machine_depreciation, :overhead_maintenance_repair,
         :overhead_marketing, :overhead_electricity, :total_overhead, :overhead_per_hour,
         :production_hours, :total_cost_with_overhead, 1, NOW(),
         :electricity_shop, :electricity_production, :overhead_data, :working_hours,
         :audit_name, :remarks)
    ");

    $stmt->execute([
        ':quote_id' => $quoteId,
        ':items' => $itemsJson,
        ':materials' => $materialsJson,
        ':rejects' => $rejectsJson,
        ':signatures' => $signaturesJson,
        ':total_material_cost' => $totalMaterialCost,
        ':total_reject_cost' => $totalRejectCost,
        ':total_amount_due' => $totalAmountDue,
        ':profit' => $profit,
        ':auto_compute' => $autoCompute ? 1 : 0,
        ':overhead_shop_rent' => $shopRent,
        ':overhead_fixed_salaries' => $fixedSalaries,
        ':overhead_shop_utilities' => $shopUtilities,
        ':overhead_subscriptions' => $subscriptions,
        ':overhead_machine_depreciation' => $machineDepreciation,
        ':overhead_maintenance_repair' => $maintenanceRepair,
        ':overhead_marketing' => $marketing,
        ':overhead_electricity' => 0,
        ':total_overhead' => $totalOverhead,
        ':overhead_per_hour' => $overheadPerHour,
        ':production_hours' => $productionHours,
        ':total_cost_with_overhead' => $totalCostWithOverhead,
        ':electricity_shop' => $electricityShop,
        ':electricity_production' => $electricityProduction,
        ':overhead_data' => $overheadJson,
        ':working_hours' => $workingHours,
        ':audit_name' => $auditName,
        ':remarks' => $remarks
    ]);

    $auditId = $pdo->lastInsertId();

    // Update quotation to mark as audited
    if ($quoteId > 0 && $auditId > 0) {
        $updateQuotation = $pdo->prepare("
            UPDATE quotations 
            SET audited = 1, audit_id = :audit_id 
            WHERE id = :quote_id
        ");
        $updateQuotation->execute([
            ':audit_id' => $auditId,
            ':quote_id' => $quoteId
        ]);
    }

    // Process materials - DEDUCT from inventory
    $loggedCount = 0;
    foreach ($materials as $mat) {
        $materialId = isset($mat['id']) ? (int)$mat['id'] : 0;
        $quantity = isset($mat['quantity']) ? (int)$mat['quantity'] : 0;
        $materialName = isset($mat['name']) ? trim($mat['name']) : '';

        if ($quantity <= 0 || $materialName === '') continue;
        if ($materialId <= 0) continue;

        $checkStmt = $pdo->prepare("SELECT total_stock FROM materials WHERE id = :id FOR UPDATE");
        $checkStmt->execute([':id' => $materialId]);
        $dbMaterial = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbMaterial) continue;

        $currentStock = (int) $dbMaterial['total_stock'];

        if ($currentStock >= $quantity) {
            $newStock = $currentStock - $quantity;

            $updateMat = $pdo->prepare("UPDATE materials SET total_stock = :stock WHERE id = :id");
            $updateMat->execute([':stock' => $newStock, ':id' => $materialId]);

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
            throw new Exception("Insufficient stock for {$materialName}. Available: {$currentStock}, Requested: {$quantity}");
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => "Audit created successfully! {$loggedCount} inventory changes logged.",
        'audit_id' => $auditId,
        'logged_count' => $loggedCount,
        'totals' => [
            'total_material_cost' => $totalMaterialCost,
            'total_reject_cost' => $totalRejectCost,
            'total_overhead' => $totalOverhead,
            'total_amount_due' => $totalAmountDue,
            'profit' => $profit
        ]
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
exit;