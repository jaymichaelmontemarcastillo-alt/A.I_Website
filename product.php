<?php
session_start();
require_once 'connect/config.php'; // Use database instead of products_list.php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product from database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    // Product not found
    include 'includes/header.php';
    ?>
    <main class="product-page">
        <div class="error-container" style="text-align: center; padding: 100px 20px;">
            <i class="fa-solid fa-exclamation-circle" style="font-size: 80px; color: #ff6b6b; margin-bottom: 20px;"></i>
            <h2>Product Not Found</h2>
            <p style="color: #666; margin-bottom: 30px;">The product you're looking for doesn't exist or has been removed.</p>
            <a href="shop.php" class="back-link" style="display: inline-block; padding: 12px 30px; background: #0f3d67; color: white; text-decoration: none; border-radius: 5px;">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Shop
            </a>
        </div>
    </main>
    <?php
    include 'includes/footer.php';
    exit;
}

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/product.css">
<style>
/* Additional styles for product page */
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

.error-container {
    text-align: center;
    padding: 100px 20px;
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.quantity-wrapper {
    margin: 20px 0;
}

.quantity-wrapper label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.quantity-wrapper input {
    width: 100px;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    font-size: 16px;
}

.quantity-wrapper input:focus {
    outline: none;
    border-color: #0f3d67;
}

.btn-cart {
    width: 100%;
    padding: 15px;
    background: #0f3d67;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.3s;
    margin-bottom: 15px;
}

.btn-cart:hover {
    background: #0a2e4a;
}

.btn-cart:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

.btn-wishlist {
    width: 100%;
    padding: 15px;
    background: white;
    color: #ff4444;
    border: 2px solid #ff4444;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-wishlist:hover {
    background: #fff0f0;
}

.btn-wishlist.in-wishlist {
    background: #ff4444;
    color: white;
}

.stock {
    margin: 15px 0;
    padding: 10px;
    background: #f0f8ff;
    border-radius: 5px;
    color: #0f3d67;
}

.stock.low-stock {
    background: #fff3cd;
    color: #856404;
}

.stock.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

.product-category {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.product-category a {
    color: #0f3d67;
    text-decoration: none;
}

.product-category a:hover {
    text-decoration: underline;
}
</style>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main class="product-page">

    <a href="shop.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Products
    </a>
    
    <div class="product-container">

        <!-- LEFT: IMAGE -->
        <div class="product-image">
            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>

        <!-- RIGHT: DETAILS -->
        <div class="product-details">

            <span class="badge"><?= htmlspecialchars($product['category']); ?></span>

            <h1><?= htmlspecialchars($product['name']); ?></h1>

            <p class="description">
                <?= htmlspecialchars($product['description']); ?>
            </p>

            <div class="price">
                ₱<?= number_format($product['price'], 2); ?>
            </div>

            <?php
            $stockStatus = '';
            $stockClass = '';
            if ($product['stock'] <= 0) {
                $stockStatus = 'Out of Stock';
                $stockClass = 'out-of-stock';
            } elseif ($product['stock'] < 5) {
                $stockStatus = 'Low Stock - Only ' . $product['stock'] . ' left!';
                $stockClass = 'low-stock';
            } else {
                $stockStatus = $product['stock'] . ' in stock';
                $stockClass = '';
            }
            ?>

            <div class="stock <?= $stockClass ?>">
                <i class="fa-solid fa-box"></i>
                <?= $stockStatus ?>
            </div>

            <!-- Add to Cart Form -->
            <div class="add-to-cart-section">
                <div class="quantity-wrapper">
                    <label>
                        <i class="fa-solid fa-layer-group"></i>
                        Quantity
                    </label>

                    <input type="number"
                        id="quantity"
                        name="quantity"
                        value="1"
                        min="1"
                        max="<?= $product['stock'] ?>"
                        <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                </div>

                <button type="button" 
                        id="addToCartBtn"
                        class="btn-cart"
                        onclick="addToCart(<?= $product['id'] ?>)"
                        <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>

                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="btnText">Add to Cart</span>
                </button>

                <button type="button" 
                        id="addToWishlistBtn"
                        class="btn-wishlist"
                        onclick="addToWishlist(<?= $product['id'] ?>)">

                    <i class="fa-regular fa-heart" id="wishlistIcon"></i>
                    <span id="wishlistBtnText">Add to Wishlist</span>
                </button>
            </div>

            <!-- Product Category Link -->
            <div class="product-category">
                <p>
                    <i class="fa-solid fa-tag"></i>
                    Category: 
                    <a href="shop.php?category=<?= urlencode(strtolower($product['category'])) ?>">
                        <?= htmlspecialchars($product['category']) ?>
                    </a>
                </p>
            </div>

        </div>
    </div>
</main>

<script>
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

// Add to cart function
function addToCart(productId) {
    const button = document.getElementById('addToCartBtn');
    const btnText = document.getElementById('btnText');
    const quantity = document.getElementById('quantity').value;
    
    // Validate quantity
    if (quantity < 1) {
        showToast('Please enter a valid quantity', true);
        return;
    }
    
    const maxStock = <?= $product['stock'] ?>;
    if (quantity > maxStock) {
        showToast('Only ' + maxStock + ' items available in stock', true);
        return;
    }
    
    // Show loading state
    const originalText = btnText.textContent;
    btnText.textContent = 'Adding...';
    button.innerHTML = '<i class="fa-solid fa-spinner"></i> Adding...';
    button.disabled = true;
    
    // Prepare cart data
    const cartData = {
        id: productId,
        name: '<?= addslashes($product['name']) ?>',
        price: <?= $product['price'] ?>,
        category: '<?= addslashes($product['category']) ?>',
        image: '<?= addslashes($product['image']) ?>',
        quantity: parseInt(quantity)
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
            if (typeof updateCartCount === 'function') {
                updateCartCount(data.cart_count);
            }
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
        btnText.textContent = originalText;
        button.innerHTML = '<i class="fa-solid fa-cart-shopping"></i> ' + originalText;
        button.disabled = false;
    });
}

// Add to wishlist function
function addToWishlist(productId) {
    const button = document.getElementById('addToWishlistBtn');
    const btnText = document.getElementById('wishlistBtnText');
    const icon = document.getElementById('wishlistIcon');
    
    // Show loading state
    const originalText = btnText.textContent;
    btnText.textContent = 'Adding...';
    button.innerHTML = '<i class="fa-solid fa-spinner"></i> Adding...';
    button.disabled = true;
    
    // Prepare wishlist data
    const wishlistData = {
        id: productId,
        name: '<?= addslashes($product['name']) ?>',
        price: <?= $product['price'] ?>,
        category: '<?= addslashes($product['category']) ?>',
        image: '<?= addslashes($product['image']) ?>',
        description: '<?= addslashes($product['description']) ?>'
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
            
            // Update button to show it's in wishlist
            button.classList.add('in-wishlist');
            button.innerHTML = '<i class="fa-solid fa-heart"></i> In Wishlist';
            
            // Update wishlist count in header
            if (data.wishlist_count !== undefined && typeof updateWishlistCount === 'function') {
                updateWishlistCount(data.wishlist_count);
            }
        } else {
            if (data.already_exists) {
                showToast('Product already in wishlist', true);
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fa-solid fa-heart"></i> In Wishlist';
            } else {
                showToast('Error: ' + (data.error || 'Failed to add to wishlist'), true);
                button.innerHTML = '<i class="fa-regular fa-heart"></i> ' + originalText;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add to wishlist', true);
        button.innerHTML = '<i class="fa-regular fa-heart"></i> ' + originalText;
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Check if product is in wishlist on page load
document.addEventListener('DOMContentLoaded', function() {
    const productId = <?= $product['id'] ?>;
    
    // Check wishlist status
    fetch('api/check_wishlist.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.in_wishlist) {
                const button = document.getElementById('addToWishlistBtn');
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fa-solid fa-heart"></i> In Wishlist';
            }
        })
        .catch(error => console.error('Error checking wishlist:', error));
});

// Update cart count function (if not defined globally)
if (typeof updateCartCount !== 'function') {
    window.updateCartCount = function(count) {
        const cartBadge = document.getElementById('cartCount');
        if (cartBadge) {
            cartBadge.textContent = count;
        }
    };
}

// Update wishlist count function (if not defined globally)
if (typeof updateWishlistCount !== 'function') {
    window.updateWishlistCount = function(count) {
        const wishlistBadge = document.getElementById('wishlistCount');
        if (wishlistBadge) {
            wishlistBadge.textContent = count;
        }
    };
}
</script>

<?php include 'includes/footer.php'; ?>