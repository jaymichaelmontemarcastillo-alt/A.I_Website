<?php
// api/admin_site/inventory/get_audit_data.php
// Get audit data for editing

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../connect/config.php';

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new RuntimeException('Database connection failed.');
    }

    if (empty($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $auditId = isset($_GET['audit_id']) ? (int)$_GET['audit_id'] : 0;
    $quotationId = isset($_GET['quotation_id']) ? (int)$_GET['quotation_id'] : 0;

    if ($auditId <= 0 && $quotationId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid audit ID or quotation ID.']);
        exit;
    }

    // If we have audit_id, use it directly
    if ($auditId > 0) {
        $sql = "SELECT a.*, 
                       q.id as quotation_id, q.quote_number, q.client_name, q.contact_person, 
                       q.email, q.phone, q.address, q.created_at as quote_created_at,
                       q.total as quotation_total
                FROM bom_audit a
                LEFT JOIN quotations q ON q.audit_id = a.id
                WHERE a.id = :audit_id";
        $params = [':audit_id' => $auditId];
    } else {
        // If we have quotation_id, find the audit
        $sql = "SELECT a.*, 
                       q.id as quotation_id, q.quote_number, q.client_name, q.contact_person, 
                       q.email, q.phone, q.address, q.created_at as quote_created_at,
                       q.total as quotation_total
                FROM bom_audit a
                JOIN quotations q ON q.audit_id = a.id
                WHERE q.id = :quotation_id";
        $params = [':quotation_id' => $quotationId];
    }
    
    $sql .= " LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $audit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$audit) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Audit not found']);
        exit;
    }

    // Parse JSON fields
    $audit['items'] = json_decode($audit['items'], true) ?? [];
    $audit['materials'] = json_decode($audit['materials'], true) ?? [];
    $audit['rejects'] = json_decode($audit['rejects'], true) ?? [];
    $audit['signatures'] = json_decode($audit['signatures'], true) ?? [];
    
    // Build overhead_data from the individual flat columns
    $overheadData = [
        'shop_rent' => floatval($audit['overhead_shop_rent'] ?? 0),
        'fixed_salaries' => floatval($audit['overhead_fixed_salaries'] ?? 0),
        'shop_utilities' => floatval($audit['overhead_shop_utilities'] ?? 0),
        'subscriptions' => floatval($audit['overhead_subscriptions'] ?? 0),
        'machine_depreciation' => floatval($audit['overhead_machine_depreciation'] ?? 0),
        'maintenance_repair' => floatval($audit['overhead_maintenance_repair'] ?? 0),
        'marketing_promo' => floatval($audit['overhead_marketing'] ?? 0)
    ];
    
    // Build electricity_data object
    $electricityData = [
        'shop' => floatval($audit['electricity_shop'] ?? 0),
        'production' => floatval($audit['electricity_production'] ?? 0)
    ];

    // Ensure working_hours is set
    $workingHours = floatval($audit['working_hours'] ?? 250);
    
    // Audit name
    $auditName = $audit['audit_name'] ?? '';

    // Build quotation object
    $quotation = [
        'id' => $audit['quotation_id'] ?? null,
        'quote_number' => $audit['quote_number'] ?? 'N/A',
        'client_name' => $audit['client_name'] ?? 'N/A',
        'contact_person' => $audit['contact_person'] ?? '',
        'email' => $audit['email'] ?? '',
        'phone' => $audit['phone'] ?? '',
        'address' => $audit['address'] ?? '',
        'created_at' => $audit['quote_created_at'] ?? date('Y-m-d H:i:s'),
        'total' => $audit['quotation_total'] ?? 0
    ];

    // Get quotation items if we have a quotation_id
    $items = [];
    if ($audit['quotation_id']) {
        $stmtItems = $pdo->prepare("
            SELECT id, description, quantity, unit_price, total
            FROM quotation_items 
            WHERE quotation_id = :quotation_id
            ORDER BY id ASC
        ");
        $stmtItems->execute([':quotation_id' => $audit['quotation_id']]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    // Build the full audit object with all data
    $auditData = [
        'id' => (int)$audit['id'],
        'audit_name' => $auditName,
        'items' => $audit['items'],
        'materials' => $audit['materials'],
        'rejects' => $audit['rejects'],
        'signatures' => $audit['signatures'],
        'overhead_data' => $overheadData,
        'electricity_data' => $electricityData,
        'working_hours' => $workingHours,
        'total_material_cost' => floatval($audit['total_material_cost'] ?? 0),
        'total_reject_cost' => floatval($audit['total_reject_cost'] ?? 0),
        'total_amount_due' => floatval($audit['total_amount_due'] ?? 0),
        'profit' => floatval($audit['profit'] ?? 0),
        'total_overhead' => floatval($audit['total_overhead'] ?? 0),
        'overhead_per_hour' => floatval($audit['overhead_per_hour'] ?? 0),
        'production_hours' => floatval($audit['production_hours'] ?? 0),
        'total_cost_with_overhead' => floatval($audit['total_cost_with_overhead'] ?? 0),
        'auto_compute' => (bool)($audit['auto_compute'] ?? true),
        'is_completed' => (bool)($audit['is_completed'] ?? true),
        'created_at' => $audit['created_at'] ?? date('Y-m-d H:i:s'),
        'updated_at' => $audit['updated_at'] ?? date('Y-m-d H:i:s')
    ];

    // Debug output
    error_log('get_audit_data.php: Returning audit data');
    error_log('Signatures: ' . print_r($auditData['signatures'], true));
    error_log('Electricity data: ' . print_r($auditData['electricity_data'], true));
    error_log('Overhead data: ' . print_r($auditData['overhead_data'], true));

    echo json_encode([
        'success' => true,
        'data' => [
            'audit' => $auditData,
            'quotation' => $quotation,
            'items' => $items
        ]
    ]);
} catch (Exception $e) {
    error_log('get_audit_data.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}