<?php

/**
 * Customer Details API
 * api/admin_site/customers/fetch_customer_details.php
 */

header('Content-Type: application/json');
require_once '../../../connect/config.php';

/**
 * Get customer basic information
 */
function getCustomerInfo($pdo, $email, $phone)
{
    if ($email) {
        $condition = "email = :identifier";
        $value = $email;
    } else {
        $condition = "phone = :identifier";
        $value = $phone;
    }

    $sql = "
        SELECT 
            client_name AS name,
            email,
            phone,
            address,
            MAX(created_at) AS last_activity
        FROM quotations
        WHERE $condition
        GROUP BY client_name, email, phone, address
        ORDER BY created_at DESC
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':identifier' => $value]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get all quotations for a customer
 */
function getCustomerQuotations($pdo, $email)
{
    $sql = "
        SELECT 
            q.id,
            q.quote_number,
            q.total,
            q.status,
            q.expires_at,
            q.created_at,
            q.address,
            GROUP_CONCAT(DISTINCT qi.description SEPARATOR ', ') AS items_summary
        FROM quotations q
        LEFT JOIN quotation_items qi ON qi.quotation_id = q.id
        WHERE q.email = :email
        GROUP BY q.id, q.quote_number, q.total, q.status, q.expires_at, q.created_at, q.address
        ORDER BY q.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Calculate customer summary
 */
function calculateSummary($quotations)
{
    $totalQuotations = count($quotations);
    $totalAmount = array_sum(array_column($quotations, 'total'));
    $convertedCount = count(array_filter($quotations, function ($q) {
        return $q['status'] === 'converted';
    }));
    $lastQuoteDate = $quotations ? $quotations[0]['created_at'] : null;

    return [
        'total_quotations' => $totalQuotations,
        'total_amount' => (float)$totalAmount,
        'converted_count' => $convertedCount,
        'last_quote_date' => $lastQuoteDate
    ];
}

/**
 * Format customer info for response
 */
function formatCustomerInfo($info)
{
    return [
        'name' => (string)($info['name'] ?? ''),
        'email' => (string)($info['email'] ?? ''),
        'phone' => (string)($info['phone'] ?? ''),
        'address' => (string)($info['address'] ?? ''),
        'last_activity' => $info['last_activity'] ?? null
    ];
}

// ==================== MAIN EXECUTION ====================

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = trim($_GET['email'] ?? '');
    $phone = trim($_GET['phone'] ?? '');

    if (!$email && !$phone) {
        throw new Exception("Email or phone is required");
    }

    // Get customer info
    $info = getCustomerInfo($pdo, $email, $phone);

    if (!$info) {
        throw new Exception("Customer not found");
    }

    // Get quotations
    $quotations = getCustomerQuotations($pdo, $info['email']);

    // Calculate summary
    $summary = calculateSummary($quotations);

    // Format response
    $formattedInfo = formatCustomerInfo($info);

    echo json_encode([
        'success' => true,
        'info' => $formattedInfo,
        'quotations' => $quotations,
        'summary' => $summary
    ]);
} catch (PDOException $e) {
    error_log("Customer Details API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Customer Details API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
