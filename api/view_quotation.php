<?php
session_start();
$baseUrl = '../';
require_once '../connect/config.php';
include_once '../includes/header.php';

$pdo = getDBConnection();
$user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
$quote_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$quote = null;
$items = [];

if ($quote_id <= 0) {
    $error = 'Invalid quotation selected.';
} else {
    if ($user_id !== null) {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND user_id = ?');
        $stmt->execute([$quote_id, $user_id]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND session_id = ?');
        $stmt->execute([$quote_id, $session_id]);
    }

    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$quote) {
        $error = 'Quotation not found or access denied.';
    } else {
        $itemStmt = $pdo->prepare('SELECT * FROM quotation_items WHERE quotation_id = ?');
        $itemStmt->execute([$quote_id]);
        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<div class="create-quotation-container">
    <div class="page-header">
        <h2><i class="fas fa-file-invoice"></i> View Quotation</h2>
        <div class="page-header-actions">
            <a href="../quotations.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <?php if (!$error): ?>
                <a href="edit_quotation.php?id=<?= $quote_id ?>" class="btn btn-primary">Edit</a>
                <a href="delete_quotation.php?id=<?= $quote_id ?>" class="btn btn-delete" onclick="event.preventDefault(); handleQuotationDelete(this.href);">Delete</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php else: ?>
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-user"></i> Client Information</h3>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Client Name</label>
                        <p><?= htmlspecialchars($quote['client_name']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        <p><?= htmlspecialchars($quote['contact_person'] ?? 'N/A') ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <p><?= htmlspecialchars($quote['email']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <p><?= htmlspecialchars($quote['phone']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Valid Until</label>
                        <p><?= $quote['expires_at'] ? date('F j, Y', strtotime($quote['expires_at'])) : 'N/A' ?></p>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <p><?= ucfirst(htmlspecialchars($quote['status'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-shopping-cart"></i> Quotation Items</h3>
            </div>
            <div class="section-body">
                <div class="items-table-container">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Price (₱)</th>
                                <th>Total (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['description']) ?></td>
                                    <td><?= intval($item['quantity']) ?></td>
                                    <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                                    <td>₱<?= number_format($item['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-calculator"></i> Summary</h3>
            </div>
            <div class="section-body">
                <div class="summary-container">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">₱ <?= number_format($quote['subtotal'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax:</span>
                        <span class="summary-value">₱ <?= number_format($quote['tax'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Discount:</span>
                        <span class="summary-value">₱ <?= number_format($quote['discount'], 2) ?></span>
                    </div>
                    <div class="summary-row total">
                        <span class="summary-label">Grand Total:</span>
                        <span class="summary-value">₱ <?= number_format($quote['total'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Notes:</span>
                        <span class="summary-value"><?= nl2br(htmlspecialchars($quote['notes'] ?? 'None')) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Created:</span>
                        <span class="summary-value"><?= date('F j, Y', strtotime($quote['created_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    async function handleQuotationDelete(url) {
        if (typeof notif === 'undefined' || !notif.confirm) {
            window.location.href = url;
            return;
        }

        const confirmed = await notif.confirm({
            title: 'Delete Quotation',
            message: 'Are you sure you want to delete this quotation?',
            type: 'warning',
            confirmText: 'Delete',
            confirmClass: 'danger',
            cancelText: 'Cancel'
        });

        if (confirmed) {
            window.location.href = url;
        }
    }
</script>

<?php include '../includes/footer.php'; ?>
