<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connect/config.php';
require_once __DIR__ . '/../../connect/email_crypto.php';
require_once __DIR__ . '/../../api/admin_site/activity_logger.php';

// NOTE: adjust this session key if it differs elsewhere in your admin panel.
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated.']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];

    $gmailAddress = trim($input['GmailAddress'] ?? '');
    $appPassword = $input['AppPassword'] ?? ''; // optional: blank = keep existing
    $currentPassword = $input['CurrentPassword'] ?? '';

    if ($gmailAddress === '') {
        throw new RuntimeException('Gmail address is required.');
    }

    if (!filter_var($gmailAddress, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Please enter a valid email address.');
    }

    if ($currentPassword === '') {
        throw new RuntimeException('Please confirm your admin password to save changes.');
    }

    $pdo = getDBConnection();

    // Get admin details for logging
    $stmt = $pdo->prepare('SELECT Password, FullName FROM admins WHERE AdminID = ? LIMIT 1');
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || !password_verify($currentPassword, $admin['Password'])) {
        throw new RuntimeException('Current password is incorrect.');
    }

    // Check if email settings exist to determine if updating or creating
    $stmt = $pdo->prepare('SELECT id, GmailAddress FROM email_settings WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $existingSettings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Track what changed for logging
    $gmailChanged = false;
    $passwordChanged = false;
    $isNewEntry = !$existingSettings;
    
    if ($existingSettings) {
        // Check if Gmail address changed
        if ($existingSettings['GmailAddress'] !== $gmailAddress) {
            $gmailChanged = true;
        }
    } else {
        $gmailChanged = true; // New entry means Gmail is being set
    }

    if ($appPassword !== '') {
        $passwordChanged = true;
    }

    // Build action details based on what changed
    $actionDetails = "";
    
    if ($isNewEntry) {
        // New entry
        $actionDetails = "Created new email settings with Gmail address '{$gmailAddress}'";
        if ($passwordChanged) {
            $actionDetails .= " and set App Password";
        }
    } else {
        // Update existing entry
        $changes = [];
        if ($gmailChanged) {
            $changes[] = "Gmail address from '{$existingSettings['GmailAddress']}' to '{$gmailAddress}'";
        }
        if ($passwordChanged) {
            $changes[] = "App Password updated";
        }
        
        if (empty($changes)) {
            $actionDetails = "No changes made to email settings (same Gmail address and no new password provided)";
        } else {
            $actionDetails = "Updated email settings: " . implode("; ", $changes);
        }
    }

    if ($appPassword !== '') {
        // Gmail app passwords are shown as 16 characters (sometimes with spaces).
        $normalizedAppPassword = str_replace(' ', '', $appPassword);
        if (strlen($normalizedAppPassword) < 12) {
            throw new RuntimeException('That doesn\'t look like a valid Gmail app password.');
        }

        $encrypted = encryptAppPassword($normalizedAppPassword);

        $stmt = $pdo->prepare(
            'INSERT INTO email_settings (id, GmailAddress, AppPasswordEncrypted, UpdatedBy)
             VALUES (1, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                GmailAddress = VALUES(GmailAddress),
                AppPasswordEncrypted = VALUES(AppPasswordEncrypted),
                UpdatedBy = VALUES(UpdatedBy)'
        );
        $stmt->execute([$gmailAddress, $encrypted, $_SESSION['admin_id']]);
    } else {
        // No new app password entered — only update the address, keep
        // whatever encrypted password (if any) is already stored.
        if ($existingSettings) {
            $stmt = $pdo->prepare('UPDATE email_settings SET GmailAddress = ?, UpdatedBy = ? WHERE id = 1');
            $stmt->execute([$gmailAddress, $_SESSION['admin_id']]);
        } else {
            throw new RuntimeException('Please set an app password the first time you configure this.');
        }
    }

    // ✅ LOG ACTIVITY - Only log if there were actual changes
    if ($gmailChanged || $passwordChanged || $isNewEntry) {
        $actionType = $isNewEntry ? "Email Settings Creation" : "Email Settings Update";
        
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $admin['FullName'],
            $actionDetails,
            null,
            $actionType,
            "Success"
        );
    }

    echo json_encode(['status' => 'success', 'message' => 'Email settings updated successfully.']);
} catch (Throwable $e) {
    
    // ❌ LOG FAILED ACTIVITY
    if (isset($_SESSION['admin_id']) && isset($pdo)) {
        // Get admin name for logging
        $adminName = 'Unknown';
        try {
            $stmt = $pdo->prepare('SELECT FullName FROM admins WHERE AdminID = ? LIMIT 1');
            $stmt->execute([$_SESSION['admin_id']]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            $adminName = $admin ? $admin['FullName'] : 'Unknown';
        } catch (Exception $e) {
            // Ignore
        }
        
        logActivity(
            $pdo,
            $_SESSION['admin_id'],
            $adminName,
            "Failed to update email settings: " . $e->getMessage(),
            null,
            "Email Settings",
            "Failed"
        );
    }
    
    error_log('update_email_settings.php: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}