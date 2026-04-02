<?php
session_start();
require_once 'connect/config.php';
include 'includes/header.php';

$sessionId = session_id();
$pdo = getDBConnection();
// Get wishlist items from database
$stmt = $pdo->prepare("
    SELECT w.*, p.name, p.price, p.category, p.image, p.description 
    FROM wishlists w
    JOIN products p ON w.product_id = p.id
    WHERE w.session_id = ?
    ORDER BY w.created_at DESC
");
$stmt->execute([$sessionId]);
$wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="assets/css/customer-site/wishlist.css">
<style>
    .wishlist-page {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
        min-height: 60vh;
    }

    .wishlist-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .wishlist-header h1 {
        font-size: 36px;
        color: #333;
        margin-bottom: 10px;
    }

    .wishlist-header p {
        color: #666;
        font-size: 16px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .wishlist-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .wishlist-img {
        position: relative;
        height: 200px;
        overflow: hidden;
        cursor: pointer;
    }

    .wishlist-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .wishlist-card:hover .wishlist-img img {
        transform: scale(1.05);
    }

    .wishlist-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(15, 61, 103, 0.9);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
    }

    .wishlist-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: #ff4444;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 2;
    }

    .wishlist-remove:hover {
        background: #ff4444;
        color: white;
        transform: scale(1.1);
    }

    .wishlist-info {
        padding: 20px;
    }

    .wishlist-info h3 {
        margin: 0 0 10px;
        color: #333;
        font-size: 18px;
        cursor: pointer;
    }

    .wishlist-info h3:hover {
        color: #0f3d67;
    }

    .wishlist-info p {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .wishlist-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wishlist-price {
        font-size: 20px;
        font-weight: bold;
        color: #0f3d67;
    }

    .wishlist-add-to-cart {
        background: #0f3d67;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: background 0.3s;
        font-size: 14px;
    }

    .wishlist-add-to-cart:hover {
        background: #0a2e4a;
    }

    .wishlist-add-to-cart i {
        font-size: 14px;
    }

    .empty-wishlist {
        text-align: center;
        padding: 80px 20px;
        background: #f9f9f9;
        border-radius: 12px;
        margin: 40px 0;
    }

    .empty-icon {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-icon.heart i {
        color: #ff6b6b;
    }

    .empty-wishlist h2 {
        color: #333;
        margin-bottom: 10px;
        font-size: 24px;
    }

    .empty-wishlist p {
        color: #666;
        margin-bottom: 30px;
        font-size: 16px;
    }

    .primary-btn {
        display: inline-block;
        padding: 12px 30px;
        background: #0f3d67;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .primary-btn:hover {
        background: #0a2e4a;
    }

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
</style>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main class="wishlist-page">

    <div class="wishlist-header">
        <h1>My Wishlist</h1>
        <p><?= count($wishlist) ?> items in your wishlist</p>
    </div>

    <?php if (empty($wishlist)): ?>
        <div class="empty-wishlist">
            <div class="empty-icon heart">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h2>Your wishlist is empty</h2>
            <p>Save your favorite items here and shop them later!</p>
            <a href="shop.php" class="primary-btn">
                Browse Products
            </a>
        </div>
    <?php else: ?>
        <div class="wishlist-grid" id="wishlistGrid">
            <?php foreach ($wishlist as $item): ?>
                <div class="wishlist-card" data-id="<?= $item['product_id'] ?>">
                    <div class="wishlist-img" onclick="viewProduct(<?= $item['product_id'] ?>)">
                        <span class="wishlist-badge"><?= htmlspecialchars($item['category']) ?></span>
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>

                    <button class="wishlist-remove" onclick="removeFromWishlist(<?= $item['product_id'] ?>)">
                        <i class="fa-solid fa-times"></i>
                    </button>

                    <div class="wishlist-info">
                        <h3 onclick="viewProduct(<?= $item['product_id'] ?>)"><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars($item['description'] ?? '') ?></p>

                        <div class="wishlist-bottom">
                            <span class="wishlist-price">₱<?= number_format($item['price'], 2) ?></span>
                            <button class="wishlist-add-to-cart" onclick="addToCartFromWishlist(<?= $item['product_id'] ?>)">
                                <i class="fa-solid fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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

    // View product details
    function viewProduct(productId) {
        window.location.href = 'product.php?id=' + productId;
    }

    // Remove from wishlist
    // Remove from wishlist with beautiful confirmation
    // Remove from wishlist with notification
    async function removeFromWishlist(productId) {
        const confirmed = await notif.confirm({
            title: 'Remove from Wishlist',
            message: 'Are you sure you want to remove this item from your wishlist?',
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

        const loading = notif.loading('Removing from wishlist...');

        fetch('api/remove_from_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.hide();

                if (data.success) {
                    // Remove card from DOM with animation
                    const card = document.querySelector(`.wishlist-card[data-id="${productId}"]`);
                    if (card) {
                        card.style.transition = 'all 0.3s';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.8)';

                        setTimeout(() => {
                            card.remove();

                            // Check if wishlist is empty
                            if (document.querySelectorAll('.wishlist-card').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }

                    notif.toast('Item removed from wishlist', 'success');
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
    // Add to cart from wishlist
    function addToCartFromWishlist(productId) {
        const button = event.currentTarget;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner"></i> Adding...';
        button.disabled = true;

        // Get product details from the card
        const card = button.closest('.wishlist-card');
        const productName = card.querySelector('h3').textContent;
        const productPrice = card.querySelector('.wishlist-price').textContent.replace('₱', '').replace(',', '');
        const productImage = card.querySelector('img').src;
        const productCategory = card.querySelector('.wishlist-badge').textContent;

        const cartData = {
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            category: productCategory,
            image: productImage,
            quantity: 1
        };

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

                    // Optionally remove from wishlist after adding to cart
                    // Uncomment if you want to remove from wishlist after adding to cart
                    // removeFromWishlist(productId);
                } else {
                    showToast('Error: ' + data.error, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add to cart', true);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    // Update wishlist count in header
    function updateWishlistCount(count) {
        const wishlistBadge = document.getElementById('wishlistCount');
        if (wishlistBadge) {
            wishlistBadge.textContent = count;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Update wishlist count
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