<?php
// cart.php - Displays the shopping cart, allows updating quantities, and processes checkout
session_start();
require_once 'connect/config.php';
include 'includes/header.php';

$cart  = $_SESSION['cart'] ?? [];
$total = 0;
$pdo   = getDBConnection();

$cartItems = [];
if (!empty($cart)) {
    $productIds   = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productDetails = [];
    foreach ($dbProducts as $product) {
        $productDetails[$product['id']] = $product;
    }

    foreach ($cart as $id => $item) {
        if (isset($productDetails[$id])) {
            $cartItems[$id] = [
                'id'          => $id,
                'name'        => $productDetails[$id]['name'],
                'price'       => $productDetails[$id]['price'],
                'category'    => $productDetails[$id]['category'],
                'image'       => $productDetails[$id]['image'],
                'description' => $productDetails[$id]['description'],
                'stock'       => $productDetails[$id]['stock'],
                'quantity'    => $item['quantity']
            ];
            $total += $productDetails[$id]['price'] * $item['quantity'];
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }

    $_SESSION['cart'] = $cartItems;
    $cart             = $cartItems;
}
?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/customer-site/cart.css">

<main class="cart-page">

    <h1 class="cart-title">Shopping Cart</h1>

    <p class="cart-subtitle">
        <?= count($cart) ?> items in your cart
    </p>

    <div class="cart-layout">

        <!-- ================= LEFT: CART ITEMS ================= -->
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
                    $price    = (float)($item['price']    ?? 0);
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
                                    <small style="color:#ff6b6b;">Only <?= $item['stock'] ?> left in stock!</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="cart-price-section">
                            <span class="cart-price">₱<?= number_format($subtotal, 2) ?></span>
                            <button class="remove-btn" style="color:#888;"
                                onclick="removeFromCart(<?= $id ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- ================= RIGHT: ORDER SUMMARY ================= -->
        <?php if (!empty($cart)): ?>
            <div class="cart-summary">
                <h3>Order Summary</h3>

                <div class="summary-list">
                    <?php foreach ($cart as $item): ?>
                        <?php
                        $quantity = (int)($item['quantity'] ?? 0);
                        $price    = (float)($item['price']    ?? 0);
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

                <a href="shop.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- ===================== CHECKOUT MODAL ===================== -->
<div id="checkoutModal" class="cart-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Complete Your Order</h2>
            <span class="close-modal">&times;</span>
        </div>

        <div class="modal-body">
            <div class="modal-top-sec">
                <div class="form-group-container">
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
                </div>

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
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label>Payment Method *</label>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="modalPaymentMethod" value="cash" checked>
                        <div class="option-icon-text">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Cash on Delivery</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="modalPaymentMethod" value="gcash">
                        <div class="option-icon-text">
                            <i class="fas fa-mobile-alt"></i>
                            <span>GCash</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- GCash Proof Upload (hidden by default) -->
            <div id="gcashProofContainer" style="display:none; margin-top:15px;">
                <div class="form-group">
                    <label>GCash Reference Number *</label>
                    <input type="text" id="gcashReferenceNumber" placeholder="Enter GCash reference number">
                </div>
                <div class="form-group">
                    <label>Proof of Payment *</label>
                    <input type="file" id="gcashProofImage" accept="image/jpeg,image/png,image/gif,image/webp">
                </div>
                <small style="color:#888;">Upload a screenshot of your GCash payment (JPG/PNG/WEBP, max 5MB)</small>
            </div>
        </div>

        <div class="modal-footer">
            <button class="cancel-btn">Cancel</button>
            <button class="confirm-btn" id="confirmCheckoutBtn">Confirm Order</button>
        </div>
    </div>
</div>

<!-- ===================== RECEIPT MODAL ===================== -->
<div id="receiptModal" class="cart-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Order Receipt</h2>
            <span class="close-modal" onclick="closeReceiptModal()">&times;</span>
        </div>

        <div class="receipt-modal-body">
            <div class="receipt-header">
                <i class="fa-solid fa-circle-check"></i>
                <h2>Thank You for Your Order!</h2>
                <p>A confirmation email has been sent to your email address.</p>
            </div>

            <div class="receipt-details" id="receiptDetails"></div>
            <div class="receipt-items" id="receiptItems"></div>

            <div class="receipt-footer">
                <button class="print-btn" onclick="printReceipt()">
                    <i class="fa-solid fa-print"></i> Print Receipt
                </button>
                <p style="margin-top:15px;">
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

<!-- ===================== DATA + EXTERNAL JS ===================== -->
<script>
    // Make PHP cart data available as globals for cart-checkout.js
    var cart = <?= json_encode(array_values($cart), JSON_PRETTY_PRINT) ?>;
    var totalAmount = <?= $total ?>;
</script>
<script src="assets/js/customer-site-functions/cart_checkout.js"></script>

<?php include 'includes/footer.php'; ?>