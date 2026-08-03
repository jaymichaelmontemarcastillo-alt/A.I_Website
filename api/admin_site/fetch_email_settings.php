<?php
session_start();
error_log(print_r($_SESSION, true));
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';

// NOTE: adjust this session key if your other admin_site endpoints
// (fetch_admin_data.php, update_admin_profile.php) use a different
// session variable name for the logged-in admin's ID.
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated.']);
    exit;
}

try {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare('SELECT GmailAddress, AppPasswordEncrypted, UpdatedAt FROM email_settings WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'GmailAddress' => $row['GmailAddress'] ?? '',
            // Never send the encrypted value (or the plaintext) to the browser.
            // The front end only needs to know whether one has been set.
            'HasAppPassword' => !empty($row['AppPasswordEncrypted']),
            'UpdatedAt' => $row['UpdatedAt'] ?? null,
        ],
    ]);
} catch (Throwable $e) {
    error_log('fetch_email_settings.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Unable to load email settings.']);
}