<?php

/**
 * api/admin_site/update_quotation.php
 * Updates an existing quotation
 */

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'message' => 'Method not allowed.']));
}

require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

// Start session for admin authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $conn = getDBConnection();
    if (!$conn) {
        throw new RuntimeException('Database connection failed.');
    }

    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
    }

    $id             = (int)($input['id'] ?? 0);
    $client_name    = trim($input['client_name'] ?? '');
    $contact_person = trim($input['contact_person'] ?? '');
    $email          = trim($input['email'] ?? '');
    $phone          = trim($input['phone'] ?? '');
    $address        = trim($input['address'] ?? '');
    $tax            = max(0, (float)($input['tax'] ?? 0));
    $discount       = max(0, (float)($input['discount'] ?? 0));
    $notes          = trim($input['notes'] ?? '');
    $items          = $input['items'] ?? [];

    if ($id <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Invalid quotation ID.']));
    }

    if ($client_name === '') {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Client name is required.']));
    }

    if (!is_array($items) || empty($items)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'At least one item is required.']));
    }

    // Get admin details for logging
    $adminId = $_SESSION['admin_id'] ?? null;
    $adminName = 'Unknown';
    if ($adminId) {
        $stmt = $conn->prepare("SELECT FullName FROM admins WHERE AdminID = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminName = $admin ? $admin['FullName'] : 'Unknown';
    }

    // Get old quotation data for logging
    $oldStmt = $conn->prepare("SELECT client_name, contact_person, email, phone, address, subtotal, tax, discount, total FROM quotations WHERE id = ?");
    $oldStmt->execute([$id]);
    $oldQuotation = $oldStmt->fetch(PDO::FETCH_ASSOC);

    // Build & validate items
    $subtotal   = 0.0;
    $cleanItems = [];

    foreach ($items as $item) {
        $desc  = trim($item['description'] ?? '');
        $qty   = max(0, (float)($item['quantity'] ?? 0));
        $price = max(0, (float)($item['unit_price'] ?? 0));

        if ($desc === '') continue;

        $rowTotal    = $qty * $price;
        $subtotal   += $rowTotal;
        $cleanItems[] = [
            'description' => $desc,
            'quantity'    => $qty,
            'unit_price'  => $price,
            'total'       => $rowTotal,
        ];
    }

    if (empty($cleanItems)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'No valid items found (description is required).']));
    }

    $taxAmount  = $subtotal * ($tax / 100);
    $grandTotal = max(0.0, $subtotal + $taxAmount - $discount);

    $conn->beginTransaction();

    try {
        // Update quotation
        $updateSql = '
            UPDATE quotations 
            SET client_name = :client_name,
                contact_person = :contact_person,
                email = :email,
                phone = :phone,
                address = :address,
                subtotal = :subtotal,
                tax = :tax,
                discount = :discount,
                total = :total,
                notes = :notes
            WHERE id = :id
        ';

        $stmt = $conn->prepare($updateSql);
        $stmt->execute([
            ':id'              => $id,
            ':client_name'     => $client_name,
            ':contact_person'  => $contact_person,
            ':email'           => $email,
            ':phone'           => $phone,
            ':address'         => $address,
            ':subtotal'        => round($subtotal, 4),
            ':tax'             => round($tax, 4),
            ':discount'        => round($discount, 4),
            ':total'           => round($grandTotal, 4),
            ':notes'           => $notes,
        ]);

        // Delete existing items
        $deleteSql = 'DELETE FROM quotation_items WHERE quotation_id = :quotation_id';
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->execute([':quotation_id' => $id]);

        // Insert new items
        $insertItemSql = '
            INSERT INTO quotation_items
                (quotation_id, description, quantity, unit_price, total)
            VALUES
                (:quotation_id, :description, :quantity, :unit_price, :total)';

        $insertStmt = $conn->prepare($insertItemSql);
        foreach ($cleanItems as $item) {
            $insertStmt->execute([
                ':quotation_id' => $id,
                ':description'  => $item['description'],
                ':quantity'     => $item['quantity'],
                ':unit_price'   => $item['unit_price'],
                ':total'        => $item['total'],
            ]);
        }

        $conn->commit();

        // ✅ LOG ACTIVITY - Success
        $changes = [];
        if ($oldQuotation) {
            if ($oldQuotation['client_name'] !== $client_name) {
                $changes[] = "client name: '{$oldQuotation['client_name']}' → '{$client_name}'";
            }
            if ($oldQuotation['contact_person'] !== $contact_person) {
                $changes[] = "contact person: '{$oldQuotation['contact_person']}' → '{$contact_person}'";
            }
            if ($oldQuotation['email'] !== $email) {
                $changes[] = "email: '{$oldQuotation['email']}' → '{$email}'";
            }
            if ($oldQuotation['phone'] !== $phone) {
                $changes[] = "phone: '{$oldQuotation['phone']}' → '{$phone}'";
            }
            if ((float)$oldQuotation['subtotal'] !== $subtotal) {
                $changes[] = "subtotal: ₱" . number_format($oldQuotation['subtotal'], 2) . " → ₱" . number_format($subtotal, 2);
            }
            if ((float)$oldQuotation['tax'] !== $tax) {
                $changes[] = "tax: " . $oldQuotation['tax'] . "% → " . $tax . "%";
            }
            if ((float)$oldQuotation['discount'] !== $discount) {
                $changes[] = "discount: ₱" . number_format($oldQuotation['discount'], 2) . " → ₱" . number_format($discount, 2);
            }
            if ((float)$oldQuotation['total'] !== $grandTotal) {
                $changes[] = "total: ₱" . number_format($oldQuotation['total'], 2) . " → ₱" . number_format($grandTotal, 2);
            }
        }
        
        $actionDetails = "Updated quotation (ID: {$id}) for client '{$client_name}'";
        if (!empty($changes)) {
            $actionDetails .= " - Changes: " . implode(", ", $changes);
        }
        $actionDetails .= " - Items: " . count($cleanItems) . " item(s)";
        
        logActivity(
            $conn,
            $adminId,
            $adminName,
            $actionDetails,
            $id,
            "Quotation Management",
            "Success"
        );

        echo json_encode([
            'success' => true,
            'message' => 'Quotation updated successfully.',
            'quotation_id' => $id,
        ]);
    } catch (Throwable $inner) {
        $conn->rollBack();
        throw $inner;
    }
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    // ❌ LOG FAILED ACTIVITY
    if (isset($adminId) && isset($adminName) && isset($conn)) {
        logActivity(
            $conn,
            $adminId,
            $adminName,
            "Failed to update quotation (ID: {$id}): " . $e->getMessage(),
            $id ?? null,
            "Quotation Management",
            "Failed"
        );
    }

    error_log('update_quotation.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}