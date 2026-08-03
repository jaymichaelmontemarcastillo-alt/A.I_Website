<?php
// api/admin_site/inventory/update_audit.php
// Update existing audit

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

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Extract data
    $auditId = isset($input['audit_id']) ? (int)$input['audit_id'] : 0;
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

    if ($auditId <= 0) {
        throw new Exception('Invalid audit ID');
    }

    if (empty($materials) && empty($items)) {
        throw new Exception('Please add at least one material or item');
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    $pdo->beginTransaction();

    // Check if audit exists
    $checkStmt = $pdo->prepare("SELECT id FROM bom_audit WHERE id = :id FOR UPDATE");
    $checkStmt->execute([':id' => $auditId]);
    if (!$checkStmt->fetch()) {
        throw new Exception('Audit not found');
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

    // Extract overhead values
    $shopRent = floatval($overhead['shop_rent'] ?? 0);
    $fixedSalaries = floatval($overhead['fixed_salaries'] ?? 0);
    $shopUtilities = floatval($overhead['shop_utilities'] ?? 0);
    $subscriptions = floatval($overhead['subscriptions'] ?? 0);
    $machineDepreciation = floatval($overhead['machine_depreciation'] ?? 0);
    $maintenanceRepair = floatval($overhead['maintenance_repair'] ?? 0);
    $marketing = floatval($overhead['marketing_promo'] ?? $overhead['marketing'] ?? 0);
    
    // Calculate total overhead
    $totalOverhead = $shopRent + $fixedSalaries + $shopUtilities + $subscriptions +
        $machineDepreciation + $maintenanceRepair + $marketing;

    // Calculate overhead per hour if not provided
    if ($overheadPerHour <= 0 && $workingHours > 0) {
        $overheadPerHour = $totalOverhead / $workingHours;
    }

    $totalCostWithOverhead = $totalMaterialCost + $totalRejectCost + $totalOverhead;

    // Calculate profit
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

    // Update bom_audit
    $stmt = $pdo->prepare("
        UPDATE bom_audit SET
            audit_name = :audit_name,
            items = :items,
            materials = :materials,
            rejects = :rejects,
            signatures = :signatures,
            total_material_cost = :total_material_cost,
            total_reject_cost = :total_reject_cost,
            total_amount_due = :total_amount_due,
            profit = :profit,
            auto_compute = :auto_compute,
            overhead_shop_rent = :overhead_shop_rent,
            overhead_fixed_salaries = :overhead_fixed_salaries,
            overhead_shop_utilities = :overhead_shop_utilities,
            overhead_subscriptions = :overhead_subscriptions,
            overhead_machine_depreciation = :overhead_machine_depreciation,
            overhead_maintenance_repair = :overhead_maintenance_repair,
            overhead_marketing = :overhead_marketing,
            overhead_electricity = 0,
            total_overhead = :total_overhead,
            overhead_per_hour = :overhead_per_hour,
            production_hours = :production_hours,
            total_cost_with_overhead = :total_cost_with_overhead,
            electricity_shop = :electricity_shop,
            electricity_production = :electricity_production,
            overhead_data = :overhead_data,
            working_hours = :working_hours,
            updated_at = NOW()
        WHERE id = :audit_id
    ");

    $stmt->execute([
        ':audit_id' => $auditId,
        ':audit_name' => $auditName,
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
        ':total_overhead' => $totalOverhead,
        ':overhead_per_hour' => $overheadPerHour,
        ':production_hours' => $productionHours,
        ':total_cost_with_overhead' => $totalCostWithOverhead,
        ':electricity_shop' => $electricityShop,
        ':electricity_production' => $electricityProduction,
        ':overhead_data' => $overheadJson,
        ':working_hours' => $workingHours
    ]);

    // Update quotation to mark as audited (if not already)
    if ($quotationId > 0) {
        $updateQuotation = $pdo->prepare("
            UPDATE quotations 
            SET audited = 1, audit_id = :audit_id 
            WHERE id = :quote_id AND (audited = 0 OR audit_id IS NULL)
        ");
        $updateQuotation->execute([
            ':audit_id' => $auditId,
            ':quote_id' => $quotationId
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Audit updated successfully!',
        'audit_id' => $auditId,
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