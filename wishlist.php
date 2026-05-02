<?php
// wishlist.php - Displays user's wishlist items and allows managing them
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
        margin: clamp(30px, 5vw, 50px) auto;
        padding: 0 clamp(15px, 3vw, 25px);
        min-height: 60vh;
    }

    .wishlist-header {
        text-align: center;
        margin-bottom: clamp(30px, 5vw, 40px);
    }

    .wishlist-header h1 {
        font-size: clamp(24px, 6vw, 36px);
        color: #333;
        margin-bottom: clamp(8px, 1.5vw, 10px);
        font-weight: 700;
    }

    .wishlist-header p {
        color: #666;
        font-size: clamp(12px, 2.5vw, 16px);
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(clamp(160px, 100%, 280px), 1fr));
        gap: clamp(15px, 3vw, 30px);
        margin-top: clamp(20px, 3vw, 30px);
    }

    .wishlist-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .wishlist-card:active {
        transform: translateY(-4px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .wishlist-img {
        position: relative;
        height: clamp(150px, 40vw, 250px);
        overflow: hidden;
        cursor: pointer;
        aspect-ratio: 1;
    }

    .wishlist-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .wishlist-card:active .wishlist-img img {
        transform: scale(1.05);
    }

    .wishlist-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(15, 61, 103, 0.9);
        color: white;
        padding: clamp(4px, 1vw, 5px) clamp(8px, 1.5vw, 10px);
        border-radius: 5px;
        font-size: clamp(9px, 1.8vw, 12px);
        font-weight: 600;
        z-index: 2;
    }

    .wishlist-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        color: #ff4444;
        font-size: clamp(14px, 3vw, 16px);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 2;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
        min-height: 44px;
        min-width: 44px;
    }

    .wishlist-remove:active {
        background: #ff4444;
        color: white;
        transform: scale(1.05);
    }

    .wishlist-info {
        padding: clamp(12px, 2vw, 20px);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .wishlist-info h3 {
        margin: 0 0 clamp(6px, 1vw, 10px) 0;
        color: #333;
        font-size: clamp(13px, 2.5vw, 15px);
        cursor: pointer;
        font-weight: 700;
        line-height: 1.3;
    }

    .wishlist-info h3:active {
        color: #0f3d67;
        opacity: 0.8;
    }

    .wishlist-info p {
        color: #666;
        font-size: clamp(11px, 2vw, 13px);
        margin-bottom: clamp(10px, 1.5vw, 15px);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .wishlist-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: clamp(8px, 1.5vw, 12px);
        margin-top: auto;
    }

    .wishlist-price {
        font-size: clamp(14px, 3vw, 18px);
        font-weight: 700;
        color: #0f3d67;
    }

    .wishlist-add-to-cart {
        background: #0f3d67;
        color: white;
        border: none;
        padding: clamp(8px, 1.5vw, 10px) clamp(10px, 2vw, 14px);
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: clamp(4px, 1vw, 5px);
        transition: all 0.3s ease;
        font-size: clamp(11px, 2vw, 13px);
        font-weight: 600;
        min-height: 40px;
        min-width: 40px;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
        flex-shrink: 0;
    }

    .wishlist-add-to-cart i {
        font-size: clamp(12px, 2.5vw, 14px);
    }

    .wishlist-add-to-cart:active {
        background: #0a2e4a;
        transform: scale(0.95);
    }

    .empty-wishlist {
        text-align: center;
        padding: clamp(40px, 8vw, 80px) clamp(20px, 3vw, 40px);
        background: #f9fafb;
        border-radius: 12px;
        margin: clamp(30px, 5vw, 60px) 0;
    }

    .empty-icon {
        font-size: clamp(60px, 15vw, 80px);
        color: #ddd;
        margin-bottom: clamp(15px, 2vw, 20px);
    }

    .empty-icon.heart i {
        color: #ff6b6b;
    }

    .empty-wishlist h2 {
        color: #333;
        margin-bottom: clamp(8px, 1.5vw, 10px);
        font-size: clamp(20px, 5vw, 28px);
        font-weight: 700;
    }

    .empty-wishlist p {
        color: #666;
        margin-bottom: clamp(20px, 3vw, 30px);
        font-size: clamp(13px, 2.5vw, 16px);
    }

    .primary-btn {
        display: inline-block;
        padding: clamp(10px, 1.5vw, 12px) clamp(20px, 3vw, 30px);
        background-color: #0f3d67;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: clamp(13px, 2.5vw, 16px);
        font-weight: 600;
        min-height: 44px;
        touch-action: manipulation;
    }

    .primary-btn:active {
        background-color: #0a2e4a;
        transform: scale(0.98);
    }

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
        .wishlist-page {
            margin: clamp(20px, 3vw, 30px) auto;
        }

        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(clamp(140px, 45vw, 200px), 1fr));
        }

        .toast {
            bottom: 15px;
            right: 15px;
            left: 15px;
            max-width: calc(100% - 30px);
        }
    }

    @media (max-width: 480px) {
        .wishlist-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(10px, 2vw, 12px);
        }

        .empty-wishlist {
            padding: clamp(30px, 5vw, 40px) clamp(15px, 2vw, 20px);
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
                                <i class="fa-solid fa-cart-plus"></i> <span>Add</span>
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

    // Remove from wishlist with notification
    async function removeFromWishlist(productId) {
        const button = event.currentTarget;
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
        button.disabled = true;

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

                    showToast('Item removed from wishlist');
                } else {
                    showToast(data.error || 'Failed to remove item', true);
                    button.innerHTML = originalIcon;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to remove item', true);
                button.innerHTML = originalIcon;
                button.disabled = false;
            });
    }

    // Add to cart from wishlist
    function addToCartFromWishlist(productId) {
        const button = event.currentTarget;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-spinner"></i>';
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