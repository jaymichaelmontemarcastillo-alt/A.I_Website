<?php require_once 'connect/config.php';

$pdo = getDBConnection();
$products = getProducts($pdo); ?>
<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/customer-site/home.css">
<style>
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #4CAF50;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    }

    .toast i {
        font-size: 20px;
    }

    .toast.show {
        display: flex;
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
                                    onclick="event.stopPropagation(); addToWishlist(<?= $product['id']; ?>)">
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
    // Products data from PHP
    const products = <?= json_encode(array_values($products)); ?>;

    // Toast function
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = toast.querySelector('i');

        toastMessage.textContent = message;

        if (isError) {
            toast.style.backgroundColor = '#ff4444';
            toastIcon.className = 'fa-solid fa-exclamation-circle';
        } else {
            toast.style.backgroundColor = '#4CAF50';
            toastIcon.className = 'fa-solid fa-check-circle';
        }

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // View product details
    function viewProduct(productId) {
        window.location.href = 'product.php?id=' + productId;
    }

    // Add to cart function
    function addToCart(productId) {
        const button = document.getElementById('addToCartBtn_' + productId);
        const originalIcon = button.innerHTML;

        // Show loading state
        button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
        button.disabled = true;

        // Find product details
        const product = products.find(p => p.id === productId);

        if (!product) {
            showToast('Product not found!', true);
            button.innerHTML = originalIcon;
            button.disabled = false;
            return;
        }

        // Prepare cart data
        const cartData = {
            id: productId,
            name: product.name,
            price: product.price,
            category: product.category,
            image: product.image,
            quantity: 1
        };

        // Send to server
        fetch('api/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cartData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✓ Added to cart!');

                    // Update cart count if you have a cart counter in header
                    updateCartCount(data.cart_count);
                } else {
                    showToast('Error: ' + data.error, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add to cart', true);
            })
            .finally(() => {
                // Restore button
                button.innerHTML = originalIcon;
                button.disabled = false;
            });
    }

    // Add to wishlist
    // Add to wishlist
    function addToWishlist(productId) {
        // Prevent event bubbling
        event.stopPropagation();

        // Find product details
        const product = products.find(p => p.id === productId);

        if (!product) {
            showToast('Product not found!', true);
            return;
        }

        // Show loading state on the button
        const button = event.currentTarget;
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
        button.disabled = true;

        // Prepare wishlist data
        const wishlistData = {
            id: productId,
            name: product.name,
            price: product.price,
            category: product.category,
            image: product.image,
            description: product.description
        };

        // Send to server
        fetch('api/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(wishlistData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('❤️ Added to wishlist!');

                    // Optional: Change button style to indicate it's in wishlist
                    button.classList.add('in-wishlist');
                    button.innerHTML = '<i class="fa-solid fa-heart" style="color: #ff4444;"></i>';
                } else {
                    showToast('Error: ' + (data.error || 'Failed to add to wishlist'), true);
                    button.innerHTML = originalIcon;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add to wishlist', true);
                button.innerHTML = originalIcon;
                button.disabled = false;
            });
    }
    // Filter by category
    function filterByCategory(category) {
        const cards = document.querySelectorAll('.gift-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (cardCategory.includes(category.toLowerCase())) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            showToast('No products found in this category', true);
        }
    }

    // Reset all filters
    function resetFilters() {
        const cards = document.querySelectorAll('.gift-card');
        cards.forEach(card => {
            card.style.display = 'block';
        });
    }

    // Update cart count in header (if you have a cart icon in header)
    function updateCartCount(count) {
        const cartIcon = document.querySelector('.cart-icon'); // Adjust selector based on your header
        if (cartIcon) {
            // Check if badge exists
            let badge = cartIcon.querySelector('.cart-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'cart-badge';
                cartIcon.style.position = 'relative';
                cartIcon.appendChild(badge);
            }
            badge.textContent = count;
        }
    }

    // Go to cart page
    function goToCart() {
        window.location.href = 'cart.php';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a cart count in session and update badge
        fetch('api/get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.count);
                }
            })
            .catch(error => console.error('Error getting cart count:', error));
    });
</script>

<?php include 'includes/footer.php'; ?>