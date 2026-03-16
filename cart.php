<?php
session_start();
require_once 'connect/config.php'; // Include database connection
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Get fresh product data from database for items in cart
$cartItems = [];
if (!empty($cart)) {
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    
    // Fetch current product details from database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create associative array with product id as key
    $productDetails = [];
    foreach ($dbProducts as $product) {
        $productDetails[$product['id']] = $product;
    }
    
    // Merge cart data with fresh database info
    foreach ($cart as $id => $item) {
        if (isset($productDetails[$id])) {
            $cartItems[$id] = [
                'id' => $id,
                'name' => $productDetails[$id]['name'],
                'price' => $productDetails[$id]['price'],
                'category' => $productDetails[$id]['category'],
                'image' => $productDetails[$id]['image'],
                'description' => $productDetails[$id]['description'],
                'stock' => $productDetails[$id]['stock'],
                'quantity' => $item['quantity']
            ];
            
            // Calculate subtotal
            $subtotal = $productDetails[$id]['price'] * $item['quantity'];
            $total += $subtotal;
        } else {
            // Product no longer exists in database
            unset($_SESSION['cart'][$id]);
        }
    }
    
    // Update cart in session with fresh data
    $_SESSION['cart'] = $cartItems;
    $cart = $cartItems; // Use the updated cart for display
}
?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: 30px auto;
    padding: 25px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    animation: modalSlideIn 0.3s ease;
}

.modal-content.large {
    max-width: 700px;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.modal-header h2 {
    margin: 0;
    color: #333;
    font-size: 24px;
}

.close-modal {
    font-size: 28px;
    font-weight: bold;
    color: #888;
    cursor: pointer;
    transition: color 0.3s;
}

.close-modal:hover {
    color: #333;
}

.modal-body {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #0f3d67;
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 15px 0;
}

.payment-option {
    display: flex;
    align-items: center;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-option:hover {
    border-color: #0f3d67;
    background-color: #f0f7ff;
}

.payment-option input[type="radio"] {
    margin-right: 12px;
    width: 18px;
    height: 18px;
    accent-color: #0f3d67;
}

.payment-option span {
    font-size: 15px;
    color: #333;
}

.payment-option i {
    margin-right: 10px;
    font-size: 20px;
    color: #0f3d67;
}

.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.modal-footer button {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.cancel-btn {
    background-color: #f0f0f0;
    color: #666;
}

.cancel-btn:hover {
    background-color: #e0e0e0;
}

.confirm-btn {
    background-color: #0f3d67;
    color: white;
}

.confirm-btn:hover {
    background-color: #0a2e4a;
}

.confirm-btn:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.order-summary-modal {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
    max-height: 200px;
    overflow-y: auto;
}

.order-summary-modal h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.summary-item-modal {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
    color: #666;
}

.total-modal {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px dashed #ddd;
    font-weight: bold;
    color: #333;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
    background: #f9f9f9;
    border-radius: 8px;
    margin: 20px 0;
}

.empty-cart p {
    font-size: 18px;
    color: #666;
    margin-bottom: 20px;
}

.empty-cart .continue-shopping {
    display: inline-block;
    padding: 12px 30px;
    background: #0f3d67;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.empty-cart .continue-shopping:hover {
    background: #0a2e4a;
}

/* Receipt Modal Styles */
.receipt-header {
    text-align: center;
    margin-bottom: 20px;
}

.receipt-header i {
    font-size: 60px;
    color: #4CAF50;
    margin-bottom: 10px;
}

.receipt-header h2 {
    color: #333;
    margin-bottom: 5px;
}

.receipt-header p {
    color: #666;
}

.receipt-details {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.receipt-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.receipt-label {
    color: #666;
    font-weight: 500;
}

.receipt-value {
    color: #333;
    font-weight: 600;
}

.receipt-items {
    margin-bottom: 20px;
}

.receipt-items h4 {
    color: #333;
    margin-bottom: 10px;
}

.receipt-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #eee;
}

.receipt-item-name {
    color: #555;
}

.receipt-item-price {
    color: #333;
    font-weight: 500;
}

.receipt-total {
    background: #0f3d67;
    color: white;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    font-weight: bold;
    margin-top: 15px;
}

.receipt-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.receipt-footer p {
    color: #888;
    font-size: 14px;
}

.print-btn {
    background: #0f3d67;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: background 0.3s;
}

.print-btn:hover {
    background: #0a2e4a;
}

.order-number {
    font-family: monospace;
    font-size: 18px;
    letter-spacing: 1px;
}
</style>

<main class="cart-page">

    <h1 class="cart-title">Shopping Cart</h1>

    <p class="cart-subtitle">
        <?= count($cart) ?> items in your cart
    </p>

    <div class="cart-layout">

        <!-- ================= LEFT CART ITEMS ================= -->
        <div class="cart-items">
            <?php if (empty($cart)): ?>
                <div class="empty-cart">
                    <p>Your cart is empty</p>
                    <a href="shop.php" class="continue-shopping">Start Shopping</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                    $quantity = (int)($item['quantity'] ?? 0);
                    $price = (float)($item['price'] ?? 0);
                    $subtotal = $price * $quantity;
                    ?>

                    <div class="cart-card">
                        <div class="cart-product-info">
                            <img src="<?= htmlspecialchars($item['image'] ?? 'assets/images/default.jpg') ?>" 
                                 class="cart-img" 
                                 alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>">

                            <div class="cart-text">
                                <h4><?= htmlspecialchars($item['name'] ?? 'Unknown Product') ?></h4>

                                <span class="cart-category">
                                    <?= htmlspecialchars($item['category'] ?? 'Uncategorized') ?>
                                </span>

                                <div class="quantity-control">
                                    <button class="qty-btn" onclick="updateQuantity(<?= $id ?>, 'decrease')">−</button>
                                    <span class="qty-number"><?= $quantity ?></span>
                                    <button class="qty-btn" onclick="updateQuantity(<?= $id ?>, 'increase')">+</button>
                                </div>
                                
                                <?php if (isset($item['stock']) && $item['stock'] < 5): ?>
                                    <small style="color: #ff6b6b;">Only <?= $item['stock'] ?> left in stock!</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="cart-price-section">
                            <span class="cart-price">₱<?= number_format($subtotal, 2) ?></span>

                            <button class="remove-btn" style="color:#888;" onclick="removeFromCart(<?= $id ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- ================= RIGHT SUMMARY ================= -->
        <?php if (!empty($cart)): ?>
        <div class="cart-summary">
            <h3>Order Summary</h3>

            <div class="summary-list">
                <?php foreach ($cart as $item): ?>
                    <?php
                    $quantity = (int)($item['quantity'] ?? 0);
                    $price = (float)($item['price'] ?? 0);
                    ?>
                    <div class="summary-item">
                        <span><?= htmlspecialchars($item['name'] ?? 'Product') ?> × <?= $quantity ?></span>
                        <span>₱<?= number_format($price * $quantity, 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-total">
                <h4>Total</h4>
                <h2 id="totalAmount">₱<?= number_format($total, 2) ?></h2>
            </div>

            <button id="checkoutBtn" class="checkout-btn">
                <span>🛍️ Process Checkout</span>
            </button>

            <a href="shop.php" class="continue-shopping">
                Continue Shopping
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Checkout Modal -->
<div id="checkoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Complete Your Order</h2>
            <span class="close-modal">&times;</span>
        </div>
        
        <div class="modal-body">
            <!-- Order Summary -->
            <div class="order-summary-modal">
                <h4>Order Summary</h4>
                <?php foreach ($cart as $item): ?>
                <div class="summary-item-modal">
                    <span><?= htmlspecialchars($item['name'] ?? 'Product') ?> × <?= $item['quantity'] ?></span>
                    <span>₱<?= number_format(($item['price'] ?? 0) * $item['quantity'], 2) ?></span>
                </div>
                <?php endforeach; ?>
                <div class="total-modal">
                    <span>Total Amount:</span>
                    <span>₱<?= number_format($total, 2) ?></span>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="form-group">
                <label for="modalCustomerName">Full Name *</label>
                <input type="text" id="modalCustomerName" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="modalCustomerEmail">Email Address *</label>
                <input type="email" id="modalCustomerEmail" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="modalCustomerPhone">Phone Number *</label>
                <input type="tel" id="modalCustomerPhone" placeholder="Enter your phone number" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label>Payment Method *</label>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="modalPaymentMethod" value="cash" checked>
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Cash on Delivery</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="modalPaymentMethod" value="gcash">
                        <i class="fas fa-mobile-alt"></i>
                        <span>GCash</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="modalPaymentMethod" value="card">
                        <i class="fas fa-credit-card"></i>
                        <span>Credit/Debit Card</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="cancel-btn">Cancel</button>
            <button class="confirm-btn" id="confirmCheckoutBtn">Confirm Order</button>
        </div>
    </div>
</div>

<!-- Receipt Confirmation Modal -->
<div id="receiptModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h2>Order Confirmation</h2>
            <span class="close-modal" onclick="closeReceiptModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="receipt-header">
                <i class="fa-solid fa-circle-check"></i>
                <h2>Thank You for Your Order!</h2>
                <p>A confirmation email has been sent to your email address.</p>
            </div>
            
            <div class="receipt-details" id="receiptDetails">
                <!-- Will be filled by JavaScript -->
            </div>
            
            <div class="receipt-items" id="receiptItems">
                <!-- Will be filled by JavaScript -->
            </div>
            
            <div class="receipt-footer">
                <button class="print-btn" onclick="printReceipt()">
                    <i class="fa-solid fa-print"></i> Print Receipt
                </button>
                <p style="margin-top: 15px;">
                    <i class="fa-regular fa-clock"></i> 
                    You will receive your order within 3-5 business days.
                </p>
            </div>
        </div>

        <div class="modal-footer">
            <button class="confirm-btn" onclick="continueShopping()">Continue Shopping</button>
        </div>
    </div>
</div>

<script>
// Cart data from PHP session
let cart = <?= json_encode(array_values($cart), JSON_PRETTY_PRINT) ?>;
let totalAmount = <?= $total ?>;

// ========== REMOVE FROM CART WITH NOTIFICATION ==========
async function removeFromCart(productId) {
    const confirmed = await notif.confirm({
        title: 'Remove from Cart',
        message: 'Are you sure you want to remove this item from your cart?',
        type: 'warning',
        confirmText: 'Remove',
        confirmClass: 'danger',
        cancelText: 'Keep'
    });

    if (!confirmed) return;

    const button = event.currentTarget;
    const originalIcon = button.innerHTML;
    button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
    button.disabled = true;

    const loading = notif.loading('Removing item from cart...');

    const formData = new FormData();
    formData.append('id', productId);

    fetch('api/remove_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loading.hide();
        
        if (data.success) {
            notif.toast('Item removed from cart', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            notif.toast(data.error || 'Failed to remove item', 'error');
            button.innerHTML = originalIcon;
            button.disabled = false;
        }
    })
    .catch(error => {
        loading.hide();
        console.error('Error:', error);
        notif.toast('Failed to remove item', 'error');
        button.innerHTML = originalIcon;
        button.disabled = false;
    });
}

// ========== UPDATE QUANTITY ==========
function updateQuantity(productId, action) {
    const loading = notif.loading('Updating cart...');
    
    fetch('api/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: productId,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        loading.hide();
        
        if (data.success) {
            notif.toast('Cart updated', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            notif.toast(data.error || 'Failed to update cart', 'error');
        }
    })
    .catch(error => {
        loading.hide();
        console.error('Error:', error);
        notif.toast('Failed to update cart', 'error');
    });
}

// ========== CHECKOUT MODAL FUNCTIONALITY ==========
const checkoutModal = document.getElementById('checkoutModal');
const receiptModal = document.getElementById('receiptModal');
const checkoutBtn = document.getElementById('checkoutBtn');
const closeModal = document.querySelector('.close-modal');
const cancelBtn = document.querySelector('.cancel-btn');
const confirmBtn = document.getElementById('confirmCheckoutBtn');

// Open modal when checkout button is clicked
if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function() {
        if (cart.length === 0) {
            notif.toast('Your cart is empty', 'warning');
            return;
        }
        
        // Reset form fields
        document.getElementById('modalCustomerName').value = '';
        document.getElementById('modalCustomerEmail').value = '';
        document.getElementById('modalCustomerPhone').value = '';
        document.querySelector('input[name="modalPaymentMethod"][value="cash"]').checked = true;
        
        checkoutModal.style.display = 'block';
    });
}

// Close checkout modal functions
function closeCheckoutModal() {
    checkoutModal.style.display = 'none';
}

// Close receipt modal function
function closeReceiptModal() {
    receiptModal.style.display = 'none';
}

if (closeModal) closeModal.addEventListener('click', closeCheckoutModal);
if (cancelBtn) cancelBtn.addEventListener('click', closeCheckoutModal);

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target == checkoutModal) {
        closeCheckoutModal();
    }
    if (event.target == receiptModal) {
        closeReceiptModal();
    }
});

// ========== RECEIPT FUNCTIONS ==========
function showReceipt(orderData) {
    const receiptDetails = document.getElementById('receiptDetails');
    const receiptItems = document.getElementById('receiptItems');
    
    // Format date
    const orderDate = new Date().toLocaleString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Build receipt details HTML
    receiptDetails.innerHTML = `
        <div class="receipt-row">
            <span class="receipt-label">Order Number:</span>
            <span class="receipt-value order-number">${orderData.order_number}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Order Date:</span>
            <span class="receipt-value">${orderDate}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Customer Name:</span>
            <span class="receipt-value">${orderData.customerName}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Email:</span>
            <span class="receipt-value">${orderData.customerEmail}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Phone:</span>
            <span class="receipt-value">${orderData.customerPhone}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Payment Method:</span>
            <span class="receipt-value">${orderData.paymentMethod.toUpperCase()}</span>
        </div>
    `;
    
    // Build receipt items HTML
    let itemsHtml = '<h4>Items Ordered:</h4>';
    orderData.items.forEach(item => {
        itemsHtml += `
            <div class="receipt-item">
                <span class="receipt-item-name">${item.name} × ${item.quantity}</span>
                <span class="receipt-item-price">₱${(item.price * item.quantity).toFixed(2)}</span>
            </div>
        `;
    });
    
    itemsHtml += `
        <div class="receipt-total">
            <span>TOTAL AMOUNT:</span>
            <span>₱${orderData.total.toFixed(2)}</span>
        </div>
    `;
    
    receiptItems.innerHTML = itemsHtml;
    
    // Close checkout modal and open receipt modal
    checkoutModal.style.display = 'none';
    receiptModal.style.display = 'block';
}

// Print receipt function
function printReceipt() {
    const receiptContent = document.getElementById('receiptModal').cloneNode(true);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Order Receipt</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                <style>
                    body { font-family: Arial, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; }
                    .receipt-header { text-align: center; margin-bottom: 30px; }
                    .receipt-header i { font-size: 60px; color: #4CAF50; }
                    .receipt-details { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                    .receipt-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
                    .receipt-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #ddd; }
                    .receipt-total { background: #0f3d67; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; margin-top: 15px; }
                    .order-number { font-family: monospace; font-size: 18px; }
                    @media print {
                        body { padding: 20px; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${receiptContent.querySelector('.modal-body').innerHTML}
                <div style="text-align: center; margin-top: 30px;" class="no-print">
                    <button onclick="window.print()">Print</button>
                    <button onclick="window.close()">Close</button>
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
}

// Continue shopping function
function continueShopping() {
    receiptModal.style.display = 'none';
    window.location.href = 'shop.php';
}

// ========== CHECKOUT PROCESS ==========
if (confirmBtn) {
    confirmBtn.addEventListener('click', async function() {
        // Get form values
        const customerName = document.getElementById('modalCustomerName').value.trim();
        const customerEmail = document.getElementById('modalCustomerEmail').value.trim();
        const customerPhone = document.getElementById('modalCustomerPhone').value.trim();
        const paymentMethod = document.querySelector('input[name="modalPaymentMethod"]:checked')?.value;

        if (!paymentMethod) {
            notif.toast('Please select a payment method', 'warning');
            return;
        }

        // Validate form
        if (!customerName || !customerEmail || !customerPhone) {
            notif.toast('Please fill in all required fields', 'warning');
            return;
        }

        if (!validateEmail(customerEmail)) {
            notif.toast('Please enter a valid email address', 'warning');
            return;
        }

        if (!validatePhone(customerPhone)) {
            notif.toast('Please enter a valid phone number (e.g., 09123456789)', 'warning');
            return;
        }

        // Show confirmation modal
        const confirmed = await notif.confirm({
            title: 'Confirm Order',
            message: 'Are you sure you want to place this order?',
            type: 'info',
            confirmText: 'Place Order',
            confirmClass: 'confirm',
            cancelText: 'Review'
        });

        if (!confirmed) return;

        // Disable button and show processing
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';

        // Process checkout
        processCheckout({
            customerName: customerName,
            customerEmail: customerEmail,
            customerPhone: customerPhone,
            paymentMethod: paymentMethod
        });
    });
}

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation (simple Philippine number format)
function validatePhone(phone) {
    const cleanPhone = phone.replace(/\s/g, '');
    const re = /^(09|\+639)\d{9}$/;
    return re.test(cleanPhone);
}

function processCheckout(customerData) {
    // Prepare order data with clean values
    const orderData = {
        items: cart.map(item => ({
            id: parseInt(item.id),
            name: item.name || 'Unknown Product',
            quantity: parseInt(item.quantity) || 1,
            price: parseFloat(item.price) || 0
        })),
        total: parseFloat(totalAmount),
        customerName: customerData.customerName,
        customerEmail: customerData.customerEmail,
        customerPhone: customerData.customerPhone.replace(/\s/g, ''),
        paymentMethod: customerData.paymentMethod
    };

    const loading = notif.loading('Processing your order...');

    // Send order to backend
    fetch('api/save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(orderResult => {
        if (orderResult.success) {
            // Add order number to customer data for receipt
            orderData.order_number = orderResult.order_number;
            
            // Clear cart from session via API
            return fetch('api/clear_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(clearResult => {
                loading.hide();
                notif.toast('Order placed successfully!', 'success');
                
                // Show receipt modal with order details
                showReceipt(orderData);
                
                // Reset confirm button
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm Order';
            });
        } else {
            loading.hide();
            notif.toast(orderResult.error || 'Failed to place order', 'error');
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Confirm Order';
        }
    })
    .catch(error => {
        loading.hide();
        console.error('Error saving order:', error);
        notif.toast('Error saving order: ' + error.message, 'error');
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Confirm Order';
    });
}
</script>

<?php include 'includes/footer.php'; ?>