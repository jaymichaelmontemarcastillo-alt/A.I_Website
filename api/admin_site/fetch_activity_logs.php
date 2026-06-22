<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../connect/config.php';

// Set timezone
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

try {
    $pdo = getDBConnection();

    if (!$pdo) {
        throw new Exception("Database connection failed");
    }

    // Simple query first to check if table has data
    $query = "
        SELECT 
            LogID,
            UserID,
            UserName, 
            ActionDetails, 
            ReferenceID, 
            Status, 
            CreatedAt
        FROM activity_logs 
        ORDER BY CreatedAt DESC 
        LIMIT 100
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If no data, return empty array
    if (!$logs) {
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'count' => 0,
            'message' => 'No logs found in database'
        ]);
        exit;
    }

    // Process data
    $processedLogs = [];
    foreach ($logs as $log) {
        $processedLogs[] = [
            'LogID' => (int)$log['LogID'],
            'UserID' => $log['UserID'] ? (int)$log['UserID'] : null,
            'UserName' => $log['UserName'] ?? 'Unknown',
            'ActionDetails' => $log['ActionDetails'] ?? 'No details',
            'ReferenceID' => $log['ReferenceID'] ?? null,
            'Status' => $log['Status'] ?? 'Unknown',
            'CreatedAt' => $log['CreatedAt'] ?? date('Y-m-d H:i:s'),
            'ProfilePicture' => null
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $processedLogs,
        'count' => count($processedLogs)
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
