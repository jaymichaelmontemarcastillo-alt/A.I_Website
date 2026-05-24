<?php
// orders.php - Customer Orders Management Page
session_start();
require_once 'connect/config.php';

$pdo = getDBConnection();

// Get customer session info
$customerEmail = isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : null;
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : null;

// For demo purposes, allow viewing orders by email in query params
if (isset($_GET['email'])) {
    $customerEmail = $_GET['email'];
}

// If no customer info, show a demo mode message
if (!$customerEmail) {
    $customerEmail = 'demo@example.com';
    $demoMode = true;
} else {
    $demoMode = false;
}

// Get orders for the customer
try {
    $stmt = $pdo->prepare("
        SELECT * FROM orders 
        WHERE LOWER(customer_email) = LOWER(?) 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$customerEmail]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    $orders = [];
    $error = "Error fetching orders: " . $e->getMessage();
}

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/orders.css">

<style>
    /* Additional responsive styles for orders page */
    .orders-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: clamp(15px, 3vw, 30px);
    }

    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: clamp(20px, 4vw, 30px);
        flex-wrap: wrap;
        gap: 15px;
    }

    .orders-header h1 {
        font-size: clamp(24px, 5vw, 32px);
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .orders-header .order-count {
        background: #123b5d;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: clamp(12px, 2vw, 14px);
    }

    .no-orders {
        text-align: center;
        padding: clamp(40px, 10vw, 60px) 20px;
    }

    .no-orders i {
        font-size: clamp(48px, 15vw, 80px);
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .no-orders h2 {
        font-size: clamp(18px, 4vw, 24px);
        color: #666;
        margin-bottom: 10px;
    }

    .no-orders p {
        color: #999;
        margin-bottom: 20px;
        font-size: clamp(13px, 2vw, 15px);
    }

    .no-orders a {
        display: inline-block;
        background: #123b5d;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-orders a:active {
        transform: scale(0.98);
    }

    .demo-notice {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: clamp(12px, 2vw, 16px);
        border-radius: 8px;
        margin-bottom: 20px;
        color: #856404;
        font-size: clamp(12px, 2vw, 13px);
    }

    .orders-list {
        display: flex;
        flex-direction: column;
        gap: clamp(12px, 2vw, 16px);
    }

    .order-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: clamp(12px, 2vw, 16px);
        transition: all 0.2s ease;
    }

    .order-card:active {
        background: #f9fafb;
        border-color: #123b5d;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eee;
    }

    .order-number {
        font-weight: 700;
        color: #123b5d;
        font-size: clamp(13px, 2.5vw, 15px);
    }

    .order-date {
        color: #999;
        font-size: clamp(11px, 2vw, 12px);
    }

    .order-status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .order-total {
        font-weight: 700;
        color: #123b5d;
        font-size: clamp(14px, 2.5vw, 16px);
    }

    .order-details {
        font-size: clamp(12px, 2vw, 13px);
        color: #666;
        margin-bottom: 12px;
    }

    .order-details-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        flex-wrap: wrap;
        gap: 8px;
    }

    .order-details-label {
        font-weight: 600;
        color: #333;
    }

    .order-items {
        background: #f9fafb;
        padding: clamp(10px, 2vw, 12px);
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .order-items h4 {
        margin: 0 0 8px 0;
        font-size: clamp(12px, 2vw, 13px);
        font-weight: 600;
        color: #333;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #e0e0e0;
        font-size: clamp(11px, 2vw, 12px);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-name {
        flex: 1;
        color: #333;
    }

    .item-qty {
        color: #666;
        margin: 0 12px;
    }

    .item-subtotal {
        font-weight: 600;
        color: #123b5d;
        text-align: right;
        min-width: 70px;
    }

    .order-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-view,
    .btn-cancel,
    .btn-track {
        flex: 1;
        padding: 10px 12px;
        border: none;
        border-radius: 6px;
        font-size: clamp(11px, 2vw, 12px);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-view {
        background: #123b5d;
        color: white;
    }

    .btn-view:active {
        background: #0d2a42;
        transform: scale(0.98);
    }

    .btn-track {
        background: #f0f0f0;
        color: #333;
        border: 1px solid #ddd;
    }

    .btn-track:active {
        background: #e0e0e0;
        transform: scale(0.98);
    }

    .btn-cancel {
        background: #ff4444;
        color: white;
    }

    .btn-cancel:active {
        background: #cc0000;
        transform: scale(0.98);
    }

    .btn-cancel:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .order-timeline {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #eee;
    }

    .timeline-item {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: clamp(11px, 2vw, 12px);
    }

    .timeline-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ddd;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .timeline-indicator.active {
        background: #4CAF50;
    }

    .timeline-text {
        flex: 1;
        color: #666;
    }

    /* Modal for order details */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        padding: 15px;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        padding: clamp(20px, 4vw, 30px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 12px;
    }

    .modal-header h2 {
        margin: 0;
        font-size: clamp(18px, 4vw, 22px);
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
        width: 36px;
        height: 36px;
        min-height: 44px;
        min-width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .modal-close:active {
        background: #f0f0f0;
        color: #333;
    }
</style>

<main>
    <div class="orders-container">
        <!-- Orders Header -->
        <div class="orders-header">
            <h1>My Orders</h1>
            <div class="order-count"><?= count($orders) ?> Order<?= count($orders) !== 1 ? 's' : '' ?></div>
        </div>

        <!-- Demo Mode Notice -->
        <?php if ($demoMode): ?>
            <div class="demo-notice">
                <i class="fas fa-info-circle"></i> Demo Mode: Viewing sample orders. Sign in with your account to see your actual orders.
            </div>
        <?php endif; ?>

        <!-- No Orders Message -->
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <i class="fas fa-box-open"></i>
                <h2>No Orders Yet</h2>
                <p>You haven't placed any orders yet. Start shopping now!</p>
                <a href="shop.php">
                    <i class="fas fa-shopping-bags"></i> Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <?php
                    // Get order items
                    $itemsStmt = $pdo->prepare("
                        SELECT * FROM order_items 
                        WHERE order_id = ?
                    ");
                    $itemsStmt->execute([$order['id']]);
                    $items = $itemsStmt->fetchAll();

                    // Determine if order can be cancelled
                    $canCancel = in_array($order['order_status'], ['pending', 'processing']);

                    // Status color
                    $statusClass = 'status-' . $order['order_status'];
                    ?>

                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-number">Order #<?= htmlspecialchars($order['order_number']) ?></div>
                                <div class="order-date"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="order-status-badge <?= $statusClass ?>">
                                <?= ucfirst($order['order_status']) ?>
                            </div>
                            <div class="order-total">₱<?= number_format($order['total_amount'], 2) ?></div>
                        </div>

                        <div class="order-details">
                            <div class="order-details-row">
                                <span class="order-details-label">Payment:</span>
                                <span><?= ucfirst($order['payment_method']) ?> - <?= ucfirst($order['payment_status']) ?></span>
                            </div>
                            <div class="order-details-row">
                                <span class="order-details-label">Items:</span>
                                <span><?= count($items) ?> item<?= count($items) !== 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="order-items">
                            <h4>Items</h4>
                            <?php foreach ($items as $item): ?>
                                <div class="order-item">
                                    <span class="item-name"><?= htmlspecialchars($item['product_name']) ?></span>
                                    <span class="item-qty">x<?= $item['quantity'] ?></span>
                                    <span class="item-subtotal">₱<?= number_format($item['subtotal'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Order Timeline -->
                        <div class="order-timeline">
                            <div class="timeline-item">
                                <div class="timeline-indicator active"></div>
                                <div class="timeline-text">Order Placed - <?= date('M d', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-indicator <?= in_array($order['order_status'], ['processing', 'completed']) ? 'active' : '' ?>"></div>
                                <div class="timeline-text">Processing</div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-indicator <?= $order['order_status'] === 'completed' ? 'active' : '' ?>"></div>
                                <div class="timeline-text">Completed</div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="order-actions">
                            <button class="btn-view" onclick="viewOrderDetails(<?= $order['id'] ?>)">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-track">
                                <i class="fas fa-map-marker-alt"></i> Track
                            </button>
                            <?php if ($canCancel): ?>
                                <button class="btn-cancel" onclick="cancelOrder(<?= $order['id'] ?>, '<?= htmlspecialchars($order['order_number']) ?>')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Order Details Modal -->
<div class="modal-overlay" id="orderModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Order Details</h2>
            <button class="modal-close" onclick="closeOrderModal()">×</button>
        </div>
        <div id="orderModalContent"></div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Cancel Order</h2>
            <button class="modal-close" onclick="closeCancelModal()">×</button>
        </div>
        <div style="padding: 20px 0;">
            <p>Are you sure you want to cancel order <strong id="cancelOrderNumber"></strong>?</p>
            <p style="color: #666; font-size: 13px; margin-top: 10px;">
                This action cannot be undone. Product stock will be restored to inventory.
            </p>
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button class="btn-secondary" onclick="closeCancelModal()" style="flex: 1; background: white; color: #333; border: 1px solid #ddd;">
                    Keep Order
                </button>
                <button class="btn-danger" onclick="confirmCancel()" style="flex: 1;">
                    Yes, Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let cancelOrderId = null;

    function viewOrderDetails(orderId) {
        const modal = document.getElementById('orderModal');
        const content = document.getElementById('orderModalContent');
        if (!modal || !content) return;

        content.innerHTML = '<p style="color:#555; margin:0;">Loading order details...</p>';
        modal.classList.add('show');
    }

    function closeOrderModal() {
        const modal = document.getElementById('orderModal');
        if (modal) modal.classList.remove('show');
    }

    function cancelOrder(orderId, orderNumber) {
        cancelOrderId = orderId;
        const orderNumberElement = document.getElementById('cancelOrderNumber');
        if (orderNumberElement) orderNumberElement.textContent = orderNumber;
        const modal = document.getElementById('cancelModal');
        if (modal) modal.classList.add('show');
    }

    function closeCancelModal() {
        cancelOrderId = null;
        const modal = document.getElementById('cancelModal');
        if (modal) modal.classList.remove('show');
    }

    function confirmCancel() {
        if (!cancelOrderId) {
            closeCancelModal();
            return;
        }

        const button = document.querySelector('#cancelModal .btn-danger');
        const originalHtml = button ? button.innerHTML : '';
        if (button) {
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cancelling...';
            button.disabled = true;
        }

        fetch((window.__baseDir || '') + 'api/cancel_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: cancelOrderId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCancelModal();
                    window.location.reload();
                } else {
                    alert(data.error || 'Unable to cancel order.');
                }
            })
            .catch(() => {
                alert('Unable to cancel order.');
            })
            .finally(() => {
                if (button) {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }
            });
    }
</script>
<?php include 'includes/footer.php'; ?>