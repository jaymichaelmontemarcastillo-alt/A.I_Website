<?php
session_start();
require_once 'connect/config.php';

$pdo = getDBConnection();

// Fetch ONLY merchandise (individual items)
$products = getMerchandise($pdo);

// Determine if we are viewing a single product or the listing
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If an ID is provided, show the single product detail
if ($id > 0) {
    // Find the product by ID
    $product = null;
    foreach ($products as $p) {
        if ($p['id'] === $id) {
            $product = $p;
            break;
        }
    }

    // If product not found, show error
    if (!$product) {
        include 'includes/header.php';
        ?>
        <main class="product-page">
            <div class="error-container" style="text-align: center; padding: 100px 20px;">
                <i class="fa-solid fa-exclamation-circle" style="font-size: 80px; color: #ff6b6b; margin-bottom: 20px;"></i>
                <h2>Product Not Found</h2>
                <p style="color: #666; margin-bottom: 30px;">The item you're looking for doesn't exist or has been removed.</p>
                <a href="product.php" class="back-link" style="display: inline-block; padding: 12px 30px; background: #0f3d67; color: white; text-decoration: none; border-radius: 5px;">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Merchandise
                </a>
            </div>
        </main>
        <?php
        include 'includes/footer.php';
        exit;
    }

    // Include header for detail page
    include 'includes/header.php';
    ?>
    <!-- Font Awesome + Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ========== PRODUCT DETAIL PAGE (Scoped) ========== */
        #product-detail {
            --pd-navy: #0a1a3a;
            --pd-blue: #0B3D91;
            --pd-amber: #f4a81d;
            --pd-white: #ffffff;
            --pd-muted: #5a6275;
            --pd-radius: 22px;
            --pd-transition: 0.35s cubic-bezier(0.25,0.46,0.45,0.94);
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            color: var(--pd-navy);
            background: #fafbfd;
            padding: 50px 4% 70px;
            min-height: 100vh;
        }

        #product-detail .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--pd-blue);
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 40px;
            transition: opacity 0.2s;
        }
        #product-detail .back-link:hover { opacity: 0.7; }

        #product-detail .product-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            gap: 60px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* LEFT: Image */
        #product-detail .product-image {
            flex: 1 1 400px;
            border-radius: var(--pd-radius);
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(10,26,58,0.15);
            transition: transform var(--pd-transition);
            position: relative;
            background: #eef1f7;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        #product-detail .product-image:hover {
            transform: translateY(-5px);
        }
        #product-detail .product-image img {
            width: 100%;
            display: block;
            object-fit: cover;
            aspect-ratio: 1 / 1;
        }
        #product-detail .product-image .no-image-icon {
            font-size: 5rem;
            color: #b0c0d0;
        }

        /* RIGHT: Details */
        #product-detail .product-details {
            flex: 1 1 400px;
        }

        #product-detail .type-badge {
            display: inline-block;
            background: rgba(11,61,145,0.12);
            color: var(--pd-blue);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
            border: 1px solid rgba(11,61,145,0.15);
        }

        #product-detail h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 700;
            line-height: 1.1;
            color: var(--pd-navy);
            margin-bottom: 20px;
        }

        #product-detail .description {
            color: var(--pd-muted);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* Inquiry button — now an anchor with .form-modal-trigger */
        #product-detail .inquiry-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--pd-amber);
            color: var(--pd-navy);
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(244,168,29,0.35);
            transition: all var(--pd-transition);
            border: none;
            cursor: pointer;
        }
        #product-detail .inquiry-btn:hover {
            background: #ffb81c;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(244,168,29,0.5);
        }

        /* Toast styles (kept for compatibility) */
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
        .toast.show { display: flex; }
        .toast.error { background-color: #ff4444; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            #product-detail .product-container {
                flex-direction: column;
                gap: 30px;
            }
            #product-detail .product-image {
                flex: none;
                width: 100%;
            }
        }
    </style>

    <main id="product-detail">
        <a href="product.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Merchandise
        </a>

        <div class="product-container">
            <!-- LEFT: IMAGE -->
            <div class="product-image">
                <?php if (!empty($product['image_path'])): ?>
                    <img src="<?= htmlspecialchars($product['image_path']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div class="no-image-icon"><i class="fas fa-cube"></i></div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: DETAILS -->
            <div class="product-details">
                <span class="type-badge"><i class="fas fa-tag"></i> Merchandise</span>
                <h1><?= htmlspecialchars($product['name']); ?></h1>
                <p class="description"><?= htmlspecialchars($product['description']); ?></p>

                <!-- ==== Direct Google Form Link ==== -->
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform"
                   class="inquiry-btn"
                   target="_blank"
                   rel="noopener noreferrer">
                    <i class="fa-solid fa-envelope"></i> Inquire About This Item
                </a>
            </div>
        </div>
    </main>

    <div id="toast" class="toast">
        <i class="fa-solid fa-check-circle"></i>
        <span id="toastMessage">Item added to cart!</span>
    </div>

    <script>
        // Toast (unchanged)
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const icon = toast.querySelector('i');
            toastMessage.textContent = message;
            if (isError) {
                toast.className = 'toast error show';
                icon.className = 'fa-solid fa-exclamation-circle';
            } else {
                toast.className = 'toast show';
                icon.className = 'fa-solid fa-check-circle';
            }
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // No additional modal code — the existing .form-modal-trigger handler
        // (presumably in a global script) will open the modal.
    </script>

    <?php include 'includes/footer.php'; ?>
    <?php exit; // stop further processing
}

// -------------------------------------------------------------
// LISTING PAGE (no ID provided) – unchanged
// -------------------------------------------------------------
include 'includes/header.php';
?>
<!-- listing page content (unchanged) ... -->
<link rel="stylesheet" href="assets/css/customer-site/home.css">
<style>
    .product-page { padding: 40px 4%; background: #f0f5fb; min-height: 100vh; max-width: 1300px; margin: 0 auto; }
    .section-title { margin-bottom: 18px; }
    .section-title h2 { font-size: clamp(2.2rem, 3vw, 3rem); margin-bottom: 10px; }
    .section-title p { max-width: 720px; margin: 0 auto; color: #546d84; }
    .back-link { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; color: #0f3d67; font-weight: 700; margin-bottom: 35px; font-size: 0.98rem; }
    .featured-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; align-items: stretch; margin-top: 30px; }
    .gift-card { border-radius: 28px; overflow: hidden; transition: transform 0.25s ease, box-shadow 0.25s ease; border: 1px solid rgba(15,61,103,0.08); background: white; display: flex; flex-direction: column; }
    .gift-card:hover { transform: translateY(-4px); box-shadow: 0 24px 80px rgba(15,61,103,0.12); }
    .gift-img { min-height: 220px; overflow: hidden; position: relative; background: #eef1f7; display: flex; align-items: center; justify-content: center; }
    .gift-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s ease; }
    .gift-card:hover .gift-img img { transform: scale(1.04); }
    .gift-img .no-image-icon { font-size: 3.5rem; color: #b0c0d0; }
    .gift-info { padding: 22px; display: flex; flex-direction: column; gap: 14px; flex: 1; }
    .gift-info h4 { font-size: 1.1rem; margin: 0; color: #0f3d67; }
    .gift-info p { color: #5c6d7d; line-height: 1.75; margin: 0; min-height: 68px; }
    .gift-card .badge { position: absolute; top: 15px; left: 15px; background: rgba(11,61,145,0.9); color: white; font-size: 0.82rem; padding: 8px 12px; z-index: 2; border-radius: 200px; }
    .gift-card h4, .gift-card p { cursor: pointer; }
    @media (max-width: 640px) { .back-link { width: 100%; justify-content: center; } }
    .toast {
        position: fixed; bottom: 30px; right: 30px; background: #4CAF50; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; align-items: center; gap: 10px; z-index: 1000; animation: slideIn 0.3s ease;
    }
    .toast.show { display: flex; }
    .toast.error { background: #ff4444; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>

<main class="product-page">
    <section class="category-section-prod-page">
        <div class="section-title">
            <h2>Custom Merchandise</h2>
            <p>Discover our exclusive collection of high-quality merchandise items.</p>
        </div>
    </section>

    <section class="featured-section">
        <div class="featured-grid" id="productsGrid">
            <?php if (empty($products)): ?>
                <p style="grid-column:1/-1; text-align:center; padding:40px 0;">No merchandise items available at the moment.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="gift-card"
                         data-id="<?= $product['id']; ?>">
                        <div class="gift-img" onclick="viewProduct(<?= $product['id']; ?>)">
                            <span class="badge"><i class="fas fa-tag"></i> Merch</span>
                            <?php if (!empty($product['image_path'])): ?>
                                <img src="<?= htmlspecialchars($product['image_path']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div class="no-image-icon"><i class="fas fa-cube"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="gift-info">
                            <h4 onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars($product['name']); ?></h4>
                            <p onclick="viewProduct(<?= $product['id']; ?>)"><?= htmlspecialchars($product['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">No products found.</span>
</div>

<script>
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const icon = toast.querySelector('i');
        toastMessage.textContent = message;
        if (isError) {
            toast.className = 'toast error show';
            icon.className = 'fa-solid fa-exclamation-circle';
        } else {
            toast.className = 'toast show';
            icon.className = 'fa-solid fa-check-circle';
        }
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
    function viewProduct(productId) {
        window.location.href = 'product.php?id=' + productId;
    }
</script>

<?php include 'includes/footer.php'; ?>