<?php
session_start();
require_once 'connect/config.php'; // Use database instead of products_list.php

// Get featured products (limit to 8 for the homepage)
$stmt = $pdo->query("SELECT * FROM products ORDER BY id LIMIT 8");
$products = $stmt->fetchAll();

// Function to get image URL (add this to config.php later)
function getImageUrl($imagePath) {
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    return '/' . $imagePath;
}

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/home.css">
<style>
/* Toast notification for better UX */
.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #4CAF50;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Icon button styles */
.icon-btn {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 50%;
    background: white;
    color: #333;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.icon-btn.wishlist:hover {
    background: #ff4444;
    color: white;
}

.icon-btn.cart:hover {
    background: #0f3d67;
    color: white;
}

.icon-btn.in-wishlist {
    background: #ff4444;
    color: white;
}

.icon-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.gift-card {
    cursor: pointer;
}

/* Fix for hero section typo */
.hero-text h1 span {
    color: #0f3d67;
}
</style>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main>

    <!-- ================= HERO ================= -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>
                    Finding the <span>Perfect Gift</span><br>
                    shouldn't be stressful.
                </h1>

                <p>
                    <span>Let us help you make it effortless and personal.</span>
                    <br>
                    Curated gift collections crafted with love. From birthdays to weddings,
                    we've got something special inside for everyone.
                </p>

                <div class="hero-buttons">
                    <a href="shop.php" style="color:inherit; text-decoration:none;">
                        <button class="btn-primary">
                            Shop Now <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </a>
                    <a href="#categories" style="color:inherit; text-decoration:none;">
                        <button class="btn-secondary" style="border:0.35px solid rgb(219, 219, 219)">
                            Browse Categories
                        </button>
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=600" alt="Gift Box">
            </div>
        </div>
    </section>

    <!-- ================= FEATURES ================= -->
    <section class="features-bar">
        <div class="feature-item">
            <i class="fa-solid fa-gift"></i>
            <div>
                <h4>Curated Gifts</h4>
                <p>Hand-picked selections</p>
            </div>
        </div>

        <div class="feature-item">
            <i class="fa-solid fa-truck-fast"></i>
            <div>
                <h4>Fast Delivery</h4>
                <p>Nationwide shipping</p>
            </div>
        </div>

        <div class="feature-item">
            <i class="fa-solid fa-shield-halved"></i>
            <div>
                <h4>Secure Payment</h4>
                <p>Safe transactions</p>
            </div>
        </div>
    </section>

    <!-- ================= CATEGORY ================= -->
    <section class="category-section" id="categories">
        <div class="section-title">
            <h2>Shop by Category</h2>
            <p>Browse our collection of thoughtfully curated gifts for every special moment.</p>
        </div>

        <div class="category-buttons">
            <button onclick="window.location.href='shop.php?category=birthday'">Birthday</button>
            <button onclick="window.location.href='shop.php?category=anniversary'">Anniversary</button>
            <button onclick="window.location.href='shop.php?category=holiday'">Holiday</button>
            <button onclick="window.location.href='shop.php?category=thank you'">Thank You</button>
            <button onclick="window.location.href='shop.php?category=baby shower'">Baby Shower</button>
            <button onclick="window.location.href='shop.php?category=wedding'">Wedding</button>
        </div>
    </section>

    <!-- ================= FEATURED GIFTS ================= -->
    <section class="featured-section">
        <div class="featured-header">
            <h2>Featured Gifts</h2>
            <a href="shop.php">View all →</a>
        </div>

        <div class="featured-grid" id="productsGrid">

            <?php foreach ($products as $product): ?>
                <?php 
                $imageUrl = getImageUrl($product['image']);
                $productId = $product['id'];
                ?>

                <div class="gift-card"
                     data-id="<?= $productId ?>"
                     onclick="viewProduct(<?= $productId ?>)">

                    <div class="gift-img">
                        <span class="badge"><?= htmlspecialchars($product['category']) ?></span>
                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.src='assets/images/placeholder.jpg'">
                    </div>

                    <div class="gift-info">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p><?= htmlspecialchars($product['description']) ?></p>

                        <div class="gift-bottom">
                            <span class="price">₱<?= number_format($product['price'], 2) ?></span>

                            <div class="card-icons">
                                <!-- Wishlist -->
                                <button class="icon-btn wishlist" 
                                        id="wishlistBtn_<?= $productId ?>"
                                        onclick="event.stopPropagation(); addToWishlist(<?= $productId ?>)">
                                    <i class="fa-regular fa-heart"></i>
                                </button>

                                <!-- Add to Cart -->
                                <button class="icon-btn cart" 
                                        id="addToCartBtn_<?= $productId ?>"
                                        onclick="event.stopPropagation(); addToCart(<?= $productId ?>)">
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

<script>
// Products data for JavaScript
const products = <?= json_encode(array_map(function($product) {
    return [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'category' => $product['category'],
        'image' => getImageUrl($product['image']),
        'description' => $product['description']
    ];
}, $products)) ?>;

// Create a map for faster lookup
const productsMap = {};
products.forEach(p => productsMap[p.id] = p);

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
    event.stopPropagation();
    
    const button = document.getElementById('addToCartBtn_' + productId);
    const originalIcon = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
    button.disabled = true;
    
    // Find product details
    const product = productsMap[productId];
    
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
            
            // Update cart count in header
            updateCartCount(data.cart_count);
        } else {
            showToast('Error: ' + (data.error || 'Failed to add to cart'), true);
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

// Add to wishlist function
function addToWishlist(productId) {
    event.stopPropagation();
    
    const button = document.getElementById('wishlistBtn_' + productId);
    const originalIcon = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
    button.disabled = true;
    
    // Find product details
    const product = productsMap[productId];
    
    if (!product) {
        showToast('Product not found!', true);
        button.innerHTML = originalIcon;
        button.disabled = false;
        return;
    }
    
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
            
            // Change button style to indicate it's in wishlist
            button.classList.add('in-wishlist');
            button.innerHTML = '<i class="fa-solid fa-heart"></i>';
            
            // Update wishlist count in header
            if (data.wishlist_count !== undefined) {
                updateWishlistCount(data.wishlist_count);
            }
        } else {
            if (data.already_exists) {
                showToast('Already in wishlist', true);
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fa-solid fa-heart"></i>';
            } else {
                showToast('Error: ' + (data.error || 'Failed to add to wishlist'), true);
                button.innerHTML = originalIcon;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add to wishlist', true);
        button.innerHTML = originalIcon;
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Update cart count in header
function updateCartCount(count) {
    const cartBadge = document.getElementById('cartCount');
    if (cartBadge) {
        cartBadge.textContent = count;
    }
}

// Update wishlist count in header
function updateWishlistCount(count) {
    const wishlistBadge = document.getElementById('wishlistCount');
    if (wishlistBadge) {
        wishlistBadge.textContent = count;
    }
}

// Check wishlist status for all products on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get all product IDs
    const productIds = products.map(p => p.id);
    
    // Check each product's wishlist status
    productIds.forEach(id => {
        fetch('api/check_wishlist.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.in_wishlist) {
                    const button = document.getElementById('wishlistBtn_' + id);
                    if (button) {
                        button.classList.add('in-wishlist');
                        button.innerHTML = '<i class="fa-solid fa-heart"></i>';
                    }
                }
            })
            .catch(error => console.error('Error checking wishlist:', error));
    });
    
    // Get cart count
    fetch('api/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.count);
            }
        })
        .catch(error => console.error('Error getting cart count:', error));
    
    // Get wishlist count
    fetch('api/get_wishlist_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateWishlistCount(data.count);
            }
        })
        .catch(error => console.error('Error getting wishlist count:', error));
});
</script>

<?php include 'includes/footer.php'; ?>