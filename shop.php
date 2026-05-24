<?php
require_once 'connect/config.php';
// shop.php - Main shopping page with product listing, category filters, and add to cart/wishlist functionality
$pdo = getDBConnection();
$products = getProducts($pdo);
?>
<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/customer-site/home.css">
<style>
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        font-size: clamp(12px, 2vw, 14px);
    }

    .toast i {
        font-size: 18px;
    }

    .toast.show {
        display: flex;
    }

    .toast.error {
        background-color: #ff4444;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Loading spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .cart-container {
        position: relative;
        display: inline-block;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #ff4444;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
        min-width: 18px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .toast {
            bottom: 15px;
            right: 15px;
            left: 15px;
            max-width: calc(100% - 30px);
        }
    }
</style>

<main>

    <!-- ================= CATEGORY ================= -->
    <section class="category-section-prod-page">
        <div class="section-title">
            <h2>Our Products</h2>
            <p>Browse our collection of thoughtfully curated gifts for every special moment.</p>
        </div>

        <div class="category-buttons">
            <button onclick="filterByCategory('birthday')">Birthday</button>
            <button onclick="filterByCategory('anniversary')">Anniversary</button>
            <button onclick="filterByCategory('holiday')">Holiday</button>
            <button onclick="filterByCategory('thank you')">Thank You</button>
            <button onclick="filterByCategory('baby shower')">Baby Shower</button>
            <button onclick="filterByCategory('wedding')">Wedding</button>
            <button onclick="filterByCategory('graduation')">Graduation</button>
            <button onclick="filterByCategory('valentine')">Valentine</button>
            <button onclick="filterByCategory('corporate')">Corporate</button>
            <button onclick="resetFilters()" class="reset-btn">Show All</button>
        </div>
    </section>

    <!-- ================= FEATURED GIFTS ================= -->
    <section class="featured-section">

        <div class="featured-grid" id="productsGrid">

            <?php foreach ($products as $product): ?>

                <div class="gift-card"
                    data-name="<?= strtolower($product['name']); ?>"
                    data-category="<?= strtolower($product['category']); ?>"
                    data-description="<?= strtolower($product['description']); ?>"
                    data-id="<?= $product['id']; ?>">

                    <div class="gift-img" onclick="viewProduct(<?= $product['id']; ?>)">
                        <span class="badge"><?= $product['category']; ?></span>
                        <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                    </div>

                    <div class="gift-info">
                        <h4 onclick="viewProduct(<?= $product['id']; ?>)"><?= $product['name']; ?></h4>
                        <p onclick="viewProduct(<?= $product['id']; ?>)"><?= $product['description']; ?></p>

                        <div class="gift-bottom">
                            <span class="price">₱<?= number_format($product['price']); ?></span>

                            <div class="card-icons">

                                <!-- Wishlist -->
                                <button class="icon-btn wishlist"
                                    id="wishlistBtn_<?= $product['id']; ?>"
                                    onclick="event.stopPropagation(); addToWishlist(event, <?= $product['id']; ?>)">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                                <!-- Add to Cart -->
                                <button class="icon-btn cart" id="addToCartBtn_<?= $product['id']; ?>"
                                    onclick="event.stopPropagation(); addToCart(<?= $product['id']; ?>)">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </section>
</main>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<?php
// shop.php — replace ONLY the bottom <script> block with this.
?>

<!-- ============================================================
     REPLACE the entire <script> block at bottom of shop.php
============================================================ -->
<script>
    const products = <?= json_encode(array_values($products)) ?>;

    window.productsMap = {};
    products.forEach(p => window.productsMap[p.id] = p);

    // ==================== TOAST ====================
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        const msg = toast.querySelector('#toastMessage');
        const icon = toast.querySelector('i');
        if (msg) msg.textContent = message;
        toast.style.backgroundColor = isError ? '#ff4444' : '#4CAF50';
        if (icon) icon.className = isError ? 'fa-solid fa-exclamation-circle' : 'fa-solid fa-check-circle';
        toast.classList.add('show');
        clearTimeout(toast._t);
        toast._t = setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ==================== VIEW PRODUCT ====================
    function viewProduct(productId) {
        window.location.href = (window.__baseDir || '') + 'product.php?id=' + productId;
    }

    // ==================== ADD TO CART ====================
    function addToCart(productId) {
        event.stopPropagation();
        const button = document.getElementById('addToCartBtn_' + productId);
        if (!button) return;
        const orig = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        button.disabled = true;

        const product = window.productsMap[productId];
        if (!product) {
            showToast('Product not found!', true);
            button.innerHTML = orig;
            button.disabled = false;
            return;
        }

        fetch((window.__baseDir || '') + 'api/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: productId,
                    name: product.name,
                    price: product.price,
                    category: product.category,
                    image: product.image,
                    quantity: 1
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('✓ Added to cart!');
                    document.dispatchEvent(new CustomEvent('cartUpdated'));
                } else {
                    showToast('Error: ' + (data.error || 'Failed to add to cart'), true);
                }
            })
            .catch(() => showToast('Failed to add to cart', true))
            .finally(() => {
                button.innerHTML = orig;
                button.disabled = false;
            });
    }

    // ==================== ADD TO WISHLIST ====================
    function addToWishlist(event, productId) {
        event.stopPropagation();
        const button = document.getElementById('wishlistBtn_' + productId);
        if (!button) return;
        const orig = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        button.disabled = true;

        const product = window.productsMap[productId];
        if (!product) {
            showToast('Product not found!', true);
            button.innerHTML = orig;
            button.disabled = false;
            return;
        }

        fetch((window.__baseDir || '') + 'api/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: productId,
                    name: product.name,
                    price: product.price,
                    category: product.category,
                    image: product.image,
                    description: product.description
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('❤️ Added to wishlist!');
                    button.classList.add('in-wishlist');
                    button.innerHTML = '<i class="fa-solid fa-heart"></i>';
                    document.dispatchEvent(new CustomEvent('wishlistUpdated'));
                } else if (data.already_exists) {
                    showToast('Already in wishlist');
                    button.classList.add('in-wishlist');
                    button.innerHTML = '<i class="fa-solid fa-heart"></i>';
                } else {
                    showToast(data.error || 'Failed', true);
                    button.innerHTML = orig;
                }
            })
            .catch(() => {
                showToast('Failed to add to wishlist', true);
                button.innerHTML = orig;
            })
            .finally(() => {
                button.disabled = false;
            });
    }

    // ==================== FILTER BY CATEGORY ====================
    function filterByCategory(category) {
        let visible = 0;
        document.querySelectorAll('.gift-card').forEach(card => {
            const match = card.getAttribute('data-category').includes(category.toLowerCase());
            card.style.display = match ? 'block' : 'none';
            if (match) visible++;
        });
        if (visible === 0) showToast('No products found in this category', true);
    }

    function resetFilters() {
        document.querySelectorAll('.gift-card').forEach(c => c.style.display = 'block');
    }

    // ==================== PAGE LOAD ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Check wishlist status for each product
        products.forEach(p => {
            fetch((window.__baseDir || '') + 'api/check_wishlist.php?id=' + p.id, {
                    cache: 'no-store'
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.in_wishlist) {
                        const btn = document.getElementById('wishlistBtn_' + p.id);
                        if (btn) {
                            btn.classList.add('in-wishlist');
                            btn.innerHTML = '<i class="fa-solid fa-heart"></i>';
                        }
                    }
                })
                .catch(() => {});
        });

        // Handle ?category= URL param (from home page category buttons)
        const params = new URLSearchParams(window.location.search);
        const cat = params.get('category');
        if (cat) filterByCategory(cat);
    });
</script>

<?php include 'includes/footer.php'; ?>