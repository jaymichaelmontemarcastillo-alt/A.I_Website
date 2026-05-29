<?php
// api/admin_site/inventory/get_quotation_materials_for_audit.php
// Get quotation items/materials to pre-fill audit form

ini_set('display_errors', 0);
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

    $quotationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($quotationId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid quotation ID.']);
        exit;
    }

    // Get quotation details - include address since it now exists
    $stmt = $pdo->prepare("
        SELECT id, quote_number, client_name, contact_person, email, phone, address,
               total, notes, created_at, status
        FROM quotations 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $quotationId]);
    $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quotation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Quotation not found.']);
        exit;
    }

    // Get quotation items
    $stmtItems = $pdo->prepare("
        SELECT id, description, quantity, unit_price, total
        FROM quotation_items 
        WHERE quotation_id = :quotation_id
        ORDER BY id ASC
    ");
    $stmtItems->execute([':quotation_id' => $quotationId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // Get materials from inventory for matching
    $stmtMaterials = $pdo->query("
        SELECT id, material_name, type, unit_cost, total_stock
        FROM materials 
        WHERE total_stock > 0 OR total_stock IS NULL
        ORDER BY material_name ASC
    ");
    $availableMaterials = $stmtMaterials->fetchAll(PDO::FETCH_ASSOC);

    // Parse item descriptions to suggest materials
    $suggestedMaterials = [];
    $usedMaterialIds = [];

    foreach ($items as $item) {
        // Clean HTML from description
        $description = strip_tags($item['description']);
        $descriptionLower = strtolower($description);

        foreach ($availableMaterials as $material) {
            $materialName = strtolower($material['material_name']);
            $materialType = strtolower($material['type'] ?? '');

            // Skip if already used
            if (in_array($material['id'], $usedMaterialIds)) {
                continue;
            }

            // Check for matches
            $matchFound = false;

            // Check if material name appears in description
            if (strpos($descriptionLower, $materialName) !== false) {
                $matchFound = true;
            }

            // Check individual words from material name
            if (!$matchFound) {
                $materialWords = explode(' ', $materialName);
                foreach ($materialWords as $word) {
                    if (strlen($word) > 2 && strpos($descriptionLower, $word) !== false) {
                        $matchFound = true;
                        break;
                    }
                }
            }

            // Check type match
            if (!$matchFound && $materialType && strpos($descriptionLower, $materialType) !== false) {
                $matchFound = true;
            }

            if ($matchFound) {
                $usedMaterialIds[] = $material['id'];
                $suggestedMaterials[] = [
                    'material_id' => $material['id'],
                    'material_name' => $material['material_name'],
                    'unit_cost' => floatval($material['unit_cost']),
                    'suggested_quantity' => max(1, ceil($item['quantity'] / max(1, count($items)))),
                    'matched_from' => substr($item['description'], 0, 50)
                ];
                break; // Only take first match per item
            }
        }
    }

    echo json_encode([
        'success' => true,
        'quotation' => $quotation,
        'items' => $items,
        'suggested_materials' => $suggestedMaterials,
        'available_materials' => $availableMaterials
    ]);
} catch (Exception $e) {
    error_log('get_quotation_materials_for_audit.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
