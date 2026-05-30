<?php
// shop.php - Main shopping page with product listing, category filters, and add to cart/wishlist functionality
require_once 'connect/config.php';
$pdo = getDBConnection();

// Get all categories for filter buttons
$stmt = $pdo->query("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get products with category information
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

    <!-- ================= CATEGORY SECTION ================= -->
    <section class="category-section-prod-page">
        <div class="section-title">
            <h2>Our Products</h2>
            <p>Browse our collection of thoughtfully curated gifts for every special moment.</p>
        </div>

        <div class="category-buttons">
            <?php foreach ($categories as $cat): ?>
                <button onclick="filterByCategory('<?= htmlspecialchars(strtolower($cat['name'])) ?>')">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
            <button onclick="resetFilters()" class="reset-btn">Show All</button>
        </div>
    </section>

    <!-- ================= PRODUCTS GRID ================= -->
    <section class="featured-section">
        <div class="featured-grid" id="productsGrid">
            <?php foreach ($products as $product): ?>
                <div class="gift-card"
                    data-name="<?= strtolower(htmlspecialchars($product['name'])); ?>"
                    data-category="<?= strtolower(htmlspecialchars($product['category_name'] ?? $product['category'] ?? '')); ?>"
                    data-description="<?= strtolower(htmlspecialchars($product['description'] ?? '')); ?>"
                    data-id="<?= $product['id']; ?>">

                    <div class="gift-img" onclick="viewProduct(<?= $product['id']; ?>)">
                        <span class="badge"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span>
                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    </div>

                    <div class="gift-info">
                        <h4 onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars($product['name']); ?></h4>
                        <p onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 100)); ?>...</p>

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
                    category: product.category_name || product.category || 'Uncategorized',
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
                    category: product.category_name || product.category || 'Uncategorized',
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
        const categoryLower = category.toLowerCase();

        document.querySelectorAll('.gift-card').forEach(card => {
            const cardCategory = card.getAttribute('data-category').toLowerCase();
            const match = cardCategory === categoryLower;
            card.style.display = match ? 'block' : 'none';
            if (match) visible++;
        });

        if (visible === 0) {
            showToast('No products found in this category', true);
        }
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

        // Handle ?category= URL param
        const params = new URLSearchParams(window.location.search);
        const cat = params.get('category');
        if (cat) filterByCategory(cat);
    });
</script>

<?php include 'includes/footer.php'; ?>