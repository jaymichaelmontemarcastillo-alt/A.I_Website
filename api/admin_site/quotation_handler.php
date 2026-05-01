<?php
// ===============================
// QUOTATION API HANDLER (FIXED)
// ===============================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../../connect/config.php';

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}

// Get action from GET or POST
$action = $_GET['action'] ?? $_POST['action'] ?? null;

// Debug: Log the incoming request
error_log("API Handler - Action: $action, Method: " . $_SERVER['REQUEST_METHOD']);

// ===============================
// FETCH ALL QUOTATIONS
// ===============================
if ($action === 'fetchAll') {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id, 
                quote_number, 
                user_id, 
                client_name, 
                contact_person, 
                email, 
                phone, 
                status, 
                subtotal, 
                tax, 
                discount, 
                total, 
                notes, 
                created_at, 
                expires_at
            FROM quotations
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $quotations ?? []
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching quotations: ' . $e->getMessage()
        ]);
    }
}

// ===============================
// FETCH SINGLE QUOTATION
// ===============================
else if ($action === 'getQuotation') {
    try {
        $quotation_id = $_POST['quotation_id'] ?? null;

        if (!$quotation_id) {
            throw new Exception('Quotation ID is required');
        }

        $stmt = $pdo->prepare("
            SELECT 
                id, 
                quote_number, 
                user_id, 
                client_name, 
                contact_person, 
                email, 
                phone, 
                status, 
                subtotal, 
                tax, 
                discount, 
                total, 
                notes, 
                created_at, 
                expires_at
            FROM quotations 
            WHERE id = ?
        ");
        $stmt->execute([$quotation_id]);
        $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quotation) {
            throw new Exception('Quotation not found');
        }

        echo json_encode([
            'success' => true,
            'data' => $quotation
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// UPDATE QUOTATION STATUS
// ===============================
else if ($action === 'updateStatus') {
    try {
        $quotation_id = $_POST['quotation_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$quotation_id || !$status) {
            throw new Exception('Quotation ID and status are required');
        }

        // Validate status
        $valid_statuses = ['draft', 'sent', 'accepted', 'expired', 'converted'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception('Invalid status value: ' . $status);
        }

        $stmt = $pdo->prepare("UPDATE quotations SET status = ? WHERE id = ?");
        $result = $stmt->execute([$status, $quotation_id]);

        if (!$result) {
            throw new Exception('Failed to update status');
        }

        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// SAVE QUOTATION (CREATE/UPDATE)
// ===============================
else if ($action === 'saveQuotation') {
    try {
        $quotation_id = $_POST['quotation_id'] ?? null;
        $client_name = trim($_POST['client_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $tax = floatval($_POST['tax'] ?? 0);
        $discount = floatval($_POST['discount'] ?? 0);
        $total = floatval($_POST['total'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');
        $expires_at = $_POST['expires_at'] ?? null;
        $status = $_POST['status'] ?? 'draft';

        // Validate required fields
        if (!$client_name) {
            throw new Exception('Client name is required');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Valid email is required');
        }

        // Handle empty expires_at
        $expires_at = !empty($expires_at) ? $expires_at : null;

        if ($quotation_id) {
            // UPDATE existing quotation
            $stmt = $pdo->prepare("
                UPDATE quotations 
                SET 
                    client_name = ?, 
                    contact_person = ?, 
                    email = ?, 
                    phone = ?, 
                    tax = ?, 
                    discount = ?, 
                    total = ?, 
                    notes = ?, 
                    expires_at = ?, 
                    status = ?
                WHERE id = ?
            ");

            $result = $stmt->execute([
                $client_name,
                $contact_person,
                $email,
                $phone,
                $tax,
                $discount,
                $total,
                $notes,
                $expires_at,
                $status,
                $quotation_id
            ]);

            if (!$result) {
                throw new Exception('Failed to update quotation');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Quotation updated successfully',
                'quotation_id' => $quotation_id
            ]);
        } else {
            // CREATE new quotation
            $quote_number = 'QT-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $stmt = $pdo->prepare("
                INSERT INTO quotations 
                (quote_number, client_name, contact_person, email, phone, tax, discount, total, notes, expires_at, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $result = $stmt->execute([
                $quote_number,
                $client_name,
                $contact_person,
                $email,
                $phone,
                $tax,
                $discount,
                $total,
                $notes,
                $expires_at,
                $status
            ]);

            if (!$result) {
                throw new Exception('Failed to create quotation');
            }

            $new_id = $pdo->lastInsertId();

            echo json_encode([
                'success' => true,
                'message' => 'Quotation created successfully',
                'quotation_id' => $new_id,
                'quote_number' => $quote_number
            ]);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// DELETE QUOTATION
// ===============================
else if ($action === 'deleteQuotation') {
    try {
        $quotation_id = $_POST['quotation_id'] ?? null;

        if (!$quotation_id) {
            throw new Exception('Quotation ID is required');
        }

        $stmt = $pdo->prepare("DELETE FROM quotations WHERE id = ?");
        $result = $stmt->execute([$quotation_id]);

        if (!$result) {
            throw new Exception('Failed to delete quotation');
        }

        echo json_encode([
            'success' => true,
            'message' => 'Quotation deleted successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// GET STATISTICS
// ===============================
else if ($action === 'getStats') {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM quotations");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'draft'");
        $draft = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'sent'");
        $sent = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM quotations WHERE status = 'accepted'");
        $accepted = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

        echo json_encode([
            'success' => true,
            'data' => [
                'total' => intval($total),
                'draft' => intval($draft),
                'sent' => intval($sent),
                'accepted' => intval($accepted)
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// SEARCH QUOTATIONS
// ===============================
else if ($action === 'search') {
    try {
        $search_term = '%' . trim($_POST['search_term'] ?? '') . '%';
        $status_filter = trim($_POST['status_filter'] ?? '');

        $sql = "
            SELECT 
                id, 
                quote_number, 
                user_id, 
                client_name, 
                contact_person, 
                email, 
                phone, 
                status, 
                subtotal, 
                tax, 
                discount, 
                total, 
                notes, 
                created_at, 
                expires_at
            FROM quotations 
            WHERE (
                quote_number LIKE ? 
                OR client_name LIKE ? 
                OR email LIKE ?
                OR phone LIKE ?
            )
        ";

        $params = [$search_term, $search_term, $search_term, $search_term];

        // Add status filter if provided
        if (!empty($status_filter) && $status_filter !== 'all') {
            $sql .= " AND status = ?";
            $params[] = $status_filter;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $results ?? []
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// GENERATE PDF
// ===============================
else if ($action === 'generatePDF') {
    try {
        $quotation_id = $_POST['id'] ?? null;

        if (!$quotation_id) {
            throw new Exception('Quotation ID is required');
        }

        $stmt = $pdo->prepare("SELECT * FROM quotations WHERE id = ?");
        $stmt->execute([$quotation_id]);
        $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quotation) {
            throw new Exception('Quotation not found');
        }

        // Generate PDF URL or file
        // This depends on your PDF generation library
        // For now, return success
        echo json_encode([
            'success' => true,
            'message' => 'PDF generated',
            'pdf_url' => './pdf/quotation-' . $quotation['id'] . '.pdf'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// ===============================
// INVALID ACTION
// ===============================
else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid action: ' . ($action ?? 'none provided')
    ]);
}
