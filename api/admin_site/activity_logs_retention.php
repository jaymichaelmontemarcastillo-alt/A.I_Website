<?php
/**
 * Activity Logs Retention Management API
 * Handles automatic cleanup of old activity logs
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// Prevent direct access if not authenticated
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

require_once '../../connect/config.php';
require_once '../../api/admin_site/activity_logger.php';

try {
    $pdo = getDBConnection();
    
    // Get the request method
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        // Get current retention settings
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key LIKE 'retention_%'");
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        echo json_encode([
            'success' => true,
            'enabled' => ($settings['retention_enabled'] ?? '0') === '1',
            'period' => $settings['retention_period'] ?? '2_month'
        ]);
        exit;
        
    } elseif ($method === 'POST') {
        // Update retention settings or apply cleanup
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $action = $input['action'] ?? '';
        $password = $input['password'] ?? '';
        $period = $input['period'] ?? '2_month';
        $enabled = isset($input['enabled']) ? (bool)$input['enabled'] : false;
        
        // Verify admin password
        $stmt = $pdo->prepare("SELECT Password, FullName FROM admins WHERE AdminID = ? LIMIT 1");
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin || !password_verify($password, $admin['Password'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
            exit;
        }
        
        // Insert or update system_settings table (create if not exists)
        $stmt = $pdo->prepare("
            INSERT INTO system_settings (setting_key, setting_value, updated_at) 
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
                setting_value = VALUES(setting_value),
                updated_at = NOW()
        ");
        
        $pdo->beginTransaction();
        
        try {
            // Save retention settings
            $stmt->execute(['retention_enabled', $enabled ? '1' : '0']);
            $stmt->execute(['retention_period', $period]);
            
            // If enabled, perform cleanup
            $deletedCount = 0;
            if ($enabled) {
                // Calculate the cutoff date based on period
                $cutoffDate = calculateCutoffDate($period);
                
                // Delete old logs
                $stmt = $pdo->prepare("DELETE FROM activity_logs WHERE CreatedAt < ?");
                $stmt->execute([$cutoffDate]);
                $deletedCount = $stmt->rowCount();
                
                // Log the cleanup action
                $periodLabel = getPeriodLabel($period);
                logActivity(
                    $pdo,
                    $_SESSION['admin_id'],
                    $admin['FullName'],
                    "Auto-cleanup enabled: Deleted {$deletedCount} activity log(s) older than {$periodLabel}",
                    null,
                    "System Maintenance",
                    "Success"
                );
            } else {
                // Log disabling
                logActivity(
                    $pdo,
                    $_SESSION['admin_id'],
                    $admin['FullName'],
                    "Auto-cleanup disabled for activity logs",
                    null,
                    "System Maintenance",
                    "Success"
                );
            }
            
            $pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => $enabled 
                    ? "Auto-cleanup enabled. Deleted {$deletedCount} log(s) older than " . getPeriodLabel($period) . "."
                    : "Auto-cleanup disabled.",
                'deleted_count' => $deletedCount,
                'period' => $period,
                'period_label' => getPeriodLabel($period)
            ]);
            
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        exit;
    }
    
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

/**
 * Calculate cutoff date based on period string
 */
function calculateCutoffDate($period) {
    $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
    
    switch ($period) {
        case '1':
        case '1_week':
            $now->modify('-1 week');
            break;
        case '2':
        case '2_week':
            $now->modify('-2 weeks');
            break;
        case '3':
        case '3_week':
            $now->modify('-3 weeks');
            break;
        case '1_month':
            $now->modify('-1 month');
            break;
        case '2_month':
            $now->modify('-2 months');
            break;
        case '3_month':
            $now->modify('-3 months');
            break;
        case '6_month':
            $now->modify('-6 months');
            break;
        default:
            $now->modify('-2 months');
            break;
    }
    
    return $now->format('Y-m-d H:i:s');
}

/**
 * Get human-readable period label
 */
function getPeriodLabel($period) {
    $labels = [
        '1' => '1 week',
        '1_week' => '1 week',
        '2' => '2 weeks',
        '2_week' => '2 weeks',
        '3' => '3 weeks',
        '3_week' => '3 weeks',
        '1_month' => '1 month',
        '2_month' => '2 months',
        '3_month' => '3 months',
        '6_month' => '6 months'
    ];
    return $labels[$period] ?? '2 months';
}