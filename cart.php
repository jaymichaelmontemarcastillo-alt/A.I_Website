<?php
session_start();
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">
<main class="cart-page">

    <h1 class="cart-title">Shopping Cart</h1>

    <p class="cart-subtitle">
        <?= count($cart ?? []) ?> items in your cart
    </p>

    <div class="cart-layout">

        <!-- ================= LEFT CART ITEMS ================= -->
        <div class="cart-items">

            <?php
            $cart = $_SESSION['cart'] ?? [];
            $total = 0;
            ?>

            <?php foreach ($cart as $id => $item): ?>

                <?php
                if (!is_array($item)) continue;

                $product = $item['product'] ?? [];
                $quantity = $item['quantity'] ?? 0;

                $price = $product['price'] ?? 0;
                $subtotal = $price * $quantity;
                $total += $subtotal;
                ?>

                <div class="cart-card">

                    <div class="cart-product-info">

                        <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" class="cart-img">

                        <div class="cart-text">

                            <h4><?= htmlspecialchars($product['name'] ?? '') ?></h4>

                            <span class="cart-category">
                                <?= htmlspecialchars($product['category'] ?? '') ?>
                            </span>

                            <div class="quantity-control">

                                <button class="qty-btn"
                                    onclick="event.preventDefault();">
                                    −
                                </button>

                                <span class="qty-number">
                                    <?= intval($quantity) ?>
                                </span>

                                <button class="qty-btn"
                                    onclick="event.preventDefault();">
                                    +
                                </button>

                            </div>

                        </div>
                    </div>

                    <div class="cart-price-section">

                        <span class="cart-price">
                            ₱<?= number_format($subtotal) ?>
                        </span>

                        <form method="POST" action="api/remove_cart.php">
                            <input type="hidden" name="id"
                                value="<?= htmlspecialchars($product['id'] ?? '') ?>">

                            <button class="remove-btn" style="color:#888;">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- ================= RIGHT SUMMARY ================= -->

        <div class="cart-summary">

            <h3>Order Summary</h3>

            <div class="summary-list">

                <?php foreach ($cart as $item): ?>

                    <?php
                    if (!is_array($item)) continue;

                    $product = $item['product'] ?? [];
                    $quantity = $item['quantity'] ?? 0;

                    $price = $product['price'] ?? 0;
                    ?>

                    <div class="summary-item">
                        <span>
                            <?= htmlspecialchars($product['name'] ?? '') ?>
                            × <?= intval($quantity) ?>
                        </span>

                        <span>
                            ₱<?= number_format($price * $quantity) ?>
                        </span>
                    </div>

                <?php endforeach; ?>

            </div>

            <div class="summary-total">
                <h4>Total</h4>
                <h2>₱<?= number_format($total) ?></h2>
            </div>

            <button class="checkout-btn">
                Proceed to Checkout
            </button>

            <a href="shop.php" class="continue-shopping">
                Continue Shopping
            </a>

        </div>

    </div>

</main>

<?php include 'includes/footer.php'; ?>