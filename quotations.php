<?php
// quotations.php - Main page for listing and managing quotations with mobile-optimized forms
session_start();

require_once 'connect/config.php';
include_once 'includes/header.php';

$pdo = getDBConnection();
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$products = $pdo->query("SELECT id, name, price, image FROM products ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Determine user/session identifier
$user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

// Initialize variables
$error = '';
$success = '';

// Generate unique quote number
function generateQuoteNumber($conn)
{
    $year = date('Y');
    $month = date('m');
    $prefix = "AI-$year$month-";

    $query = "SELECT MAX(quote_number) as last FROM quotations WHERE quote_number LIKE '$prefix%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row['last']) {
        $last_num = intval(substr($row['last'], -4));
        $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $new_num = '0001';
    }

    return $prefix . $new_num;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = mysqli_real_escape_string($conn, trim($_POST['client_name']));
    $contact_person = mysqli_real_escape_string($conn, trim($_POST['contact_person']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $expires_at = mysqli_real_escape_string($conn, trim($_POST['expires_at']));
    $notes = '';

    // Get items from form
    $descriptions = $_POST['description'];
    $quantities = $_POST['quantity'];
    $unit_prices = $_POST['unit_price'];

    // Calculate totals
    $subtotal = 0;
    $items = [];

    for ($i = 0; $i < count($descriptions); $i++) {
        if (!empty($descriptions[$i]) && $quantities[$i] > 0) {
            $qty = floatval($quantities[$i]);
            $price = floatval($unit_prices[$i]);
            $total = $qty * $price;
            $subtotal += $total;

            $items[] = [
                'description' => mysqli_real_escape_string($conn, $descriptions[$i]),
                'quantity' => $qty,
                'unit_price' => $price,
                'total' => $total
            ];
        }
    }

    $tax = 0;
    $discount = 0;
    $grand_total = $subtotal;

    // Validate required customer information for checkout
    if (empty($client_name) || empty($email) || empty($phone)) {
        $error = 'Client name, email address, and phone number are required to create a quotation.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^(09|\+639)\d{9}$/', preg_replace('/\s+/', '', $phone))) {
        $error = 'Please enter a valid Philippine phone number (e.g. 09123456789 or +639123456789).';
    } elseif (count($items) === 0) {
        $error = 'Please add at least one quotation item.';
    }

    // Generate quote number
    $quote_number = generateQuoteNumber($conn);

    // Insert quotation using prepared statement
    if ($user_id !== null) {
        $stmt = $conn->prepare("INSERT INTO quotations (quote_number, user_id, session_id, client_name, contact_person, email, phone, subtotal, tax, discount, total, notes, expires_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')");
        $stmt->bind_param("sissssddddsss", $quote_number, $user_id, $session_id, $client_name, $contact_person, $email, $phone, $subtotal, $tax, $discount, $grand_total, $notes, $expires_at);
    } else {
        $stmt = $conn->prepare("INSERT INTO quotations (quote_number, user_id, session_id, client_name, contact_person, email, phone, subtotal, tax, discount, total, notes, expires_at, status) VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')");
        $stmt->bind_param("ssssssddddss", $quote_number, $session_id, $client_name, $contact_person, $email, $phone, $subtotal, $tax, $discount, $grand_total, $notes, $expires_at);
    }

    if (empty($error) && $stmt->execute()) {
        $quotation_id = $conn->insert_id;
        $stmt->close();

        // Insert items with prepared statement
        if (count($items) > 0) {
            $item_stmt = $conn->prepare("INSERT INTO quotation_items (quotation_id, description, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $desc = $item['description'];
                $item_stmt->bind_param("isddd", $quotation_id, $desc, $item['quantity'], $item['unit_price'], $item['total']);
                $item_stmt->execute();
            }
            $item_stmt->close();
        }

        $success = "Quotation created successfully! Quote #: $quote_number";

        // Redirect after 2 seconds
        header("refresh:2;url=quotations.php");
    } elseif (empty($error)) {
        $error = "Error: " . $conn->error;
    }
}
?>

<style>
    /* ===== MOBILE-FIRST RESPONSIVE DESIGN ===== */
    :root {
        --primary-blue: #0f3d67;
        --text-dark: #1f2d3d;
        --text-light: #6b7c93;
        --border-color: #e5e7eb;
        --bg-light: #f9fafb;
        --danger-color: #ef4444;
    }

    body {
        background: var(--bg-light);
    }

    .create-quotation-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: clamp(15px, 4vw, 25px);
    }

    .page-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: clamp(20px, 4vw, 30px);
        padding-bottom: clamp(15px, 2vw, 20px);
        border-bottom: 2px solid var(--border-color);
        gap: clamp(12px, 2vw, 15px);
    }

    .page-header h2 {
        margin: 0;
        color: var(--text-dark);
        font-size: clamp(20px, 5vw, 28px);
        font-weight: 700;
    }

    .back-btn {
        background: #6c757d;
        color: white;
        padding: clamp(8px, 1.5vw, 10px) clamp(14px, 2vw, 20px);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: clamp(11px, 2vw, 13px);
        font-weight: 500;
        min-height: 40px;
        touch-action: manipulation;
    }

    .back-btn:active {
        background: #5a6268;
        transform: scale(0.98);
    }

    /* Alert Styles */
    .alert {
        padding: clamp(12px, 2vw, 15px) clamp(14px, 2vw, 20px);
        border-radius: 8px;
        margin-bottom: clamp(15px, 2vw, 20px);
        display: flex;
        align-items: flex-start;
        gap: clamp(10px, 2vw, 12px);
        animation: slideDown 0.3s ease;
        font-size: clamp(12px, 2vw, 14px);
        word-break: break-word;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert i {
        font-size: clamp(14px, 3vw, 20px);
        flex-shrink: 0;
        margin-top: 2px;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 10px;
        margin-bottom: clamp(15px, 3vw, 25px);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .section-header {
        background: var(--bg-light);
        padding: clamp(12px, 2vw, 15px) clamp(14px, 2vw, 20px);
        border-bottom: 2px solid var(--border-color);
    }

    .section-header h3 {
        margin: 0;
        color: var(--primary-blue);
        font-size: clamp(14px, 2.5vw, 18px);
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
    }

    .section-header h3 i {
        font-size: clamp(14px, 3vw, 20px);
    }

    .section-body {
        padding: clamp(15px, 2vw, 25px);
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: clamp(12px, 2vw, 15px);
    }

    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .form-group {
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        display: block;
        color: var(--text-light);
        font-weight: 500;
        font-size: clamp(11px, 2vw, 13px);
    }

    .form-group label.required:after {
        content: ' *';
        color: var(--danger-color);
        margin-left: 2px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: clamp(9px, 1.5vw, 11px) clamp(10px, 1.5vw, 12px);
        border: 2px solid var(--border-color);
        border-radius: 6px;
        font-size: clamp(12px, 2vw, 14px);
        transition: all 0.3s;
        box-sizing: border-box;
        font-family: inherit;
        background: white;
        color: var(--text-dark);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(15, 61, 103, 0.1);
    }

    /* Items Table */
    .items-table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: clamp(12px, 2vw, 14px);
    }

    .items-table thead tr {
        background: var(--bg-light);
        border-bottom: 2px solid var(--border-color);
    }

    .items-table th {
        padding: clamp(10px, 1.5vw, 12px);
        text-align: left;
        font-weight: 600;
        color: var(--text-light);
        font-size: clamp(11px, 2vw, 13px);
    }

    .items-table td {
        padding: clamp(8px, 1.5vw, 10px);
        border-bottom: 1px solid var(--border-color);
    }

    .items-table input {
        width: 100%;
        padding: clamp(6px, 1vw, 8px) clamp(8px, 1vw, 10px);
        border: 2px solid var(--border-color);
        border-radius: 4px;
        font-size: clamp(11px, 2vw, 12px);
        transition: all 0.3s;
        font-family: inherit;
    }

    .items-table input:focus {
        outline: none;
        border-color: var(--primary-blue);
    }

    .item-total {
        background: var(--bg-light);
        font-weight: 600;
        color: var(--primary-blue);
    }

    .remove-row-btn {
        background: var(--danger-color);
        color: white;
        border: none;
        border-radius: 4px;
        padding: clamp(6px, 1vw, 8px) clamp(10px, 1.5vw, 12px);
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: clamp(10px, 2vw, 12px);
        font-weight: 500;
        min-height: 32px;
        touch-action: manipulation;
    }

    .remove-row-btn:active {
        background: #c82333;
        transform: scale(0.96);
    }

    .add-row-btn {
        margin-top: clamp(10px, 1.5vw, 15px);
        background: #28a745;
        color: white;
        border: none;
        padding: clamp(8px, 1.5vw, 10px) clamp(14px, 2vw, 20px);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: clamp(11px, 2vw, 13px);
        font-weight: 600;
        min-height: 36px;
        touch-action: manipulation;
    }

    .add-row-btn:active {
        background: #218838;
        transform: scale(0.98);
    }

    /* Product modal */
    .product-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: clamp(10px, 2vw, 20px);
    }

    .product-modal.show {
        display: flex;
    }

    .product-modal-content {
        width: 100%;
        max-width: 100%;
        max-height: calc(100vh - 40px);
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 768px) {
        .product-modal-content {
            max-width: 800px;
        }
    }

    .product-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: clamp(12px, 2vw, 20px);
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-light);
        gap: 10px;
    }

    .product-modal-header h3 {
        margin: 0;
        font-size: clamp(14px, 3vw, 20px);
        color: var(--primary-blue);
        font-weight: 600;
    }

    .product-modal-close {
        background: transparent;
        border: none;
        font-size: clamp(18px, 4vw, 24px);
        cursor: pointer;
        color: var(--text-light);
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
        min-height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        touch-action: manipulation;
    }

    .product-modal-close:active {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-dark);
    }

    .product-modal-body {
        padding: clamp(12px, 2vw, 20px);
        overflow: auto;
    }

    .product-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .product-name-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-name-cell span {
        font-weight: 600;
        color: var(--primary-blue);
        font-size: clamp(12px, 2vw, 14px);
    }

    .product-search-wrap {
        margin-bottom: clamp(10px, 1.5vw, 16px);
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .product-search-wrap input {
        flex: 1;
        padding: clamp(8px, 1.5vw, 10px) clamp(10px, 1.5vw, 14px);
        border-radius: 6px;
        border: 2px solid var(--border-color);
        font-size: clamp(12px, 2vw, 14px);
        transition: all 0.3s;
    }

    .product-search-wrap input:focus {
        outline: none;
        border-color: var(--primary-blue);
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        font-size: clamp(11px, 2vw, 13px);
    }

    .product-table th,
    .product-table td {
        text-align: left;
        padding: clamp(8px, 1.5vw, 12px);
        border-bottom: 1px solid var(--border-color);
    }

    .product-table th {
        background: var(--bg-light);
        font-weight: 700;
        color: var(--text-light);
    }

    .product-select-btn {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: clamp(6px, 1vw, 8px) clamp(10px, 1.5vw, 14px);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: clamp(10px, 2vw, 12px);
        font-weight: 600;
        min-height: 32px;
        touch-action: manipulation;
    }

    .product-select-btn:active {
        background: #0b2a4a;
        transform: scale(0.96);
    }

    /* Submit Button */
    .submit-section {
        text-align: right;
        margin-top: clamp(15px, 3vw, 30px);
    }

    .submit-btn {
        background: var(--primary-blue);
        color: white;
        padding: clamp(10px, 1.5vw, 14px) clamp(20px, 3vw, 32px);
        border: none;
        border-radius: 6px;
        font-size: clamp(12px, 2vw, 16px);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        touch-action: manipulation;
    }

    .submit-btn:active {
        background: #0a2e4a;
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(15, 61, 103, 0.25);
    }

    .form-actions {
        display: flex;
        gap: clamp(8px, 1.5vw, 12px);
        padding: clamp(10px, 1.5vw, 15px);
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        padding: clamp(8px, 1.5vw, 10px) clamp(14px, 2vw, 18px);
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: clamp(11px, 2vw, 13px);
        transition: all 0.3s;
        min-height: 40px;
        touch-action: manipulation;
    }

    .btn-secondary {
        background: var(--border-color);
        color: var(--text-dark);
    }

    .btn-secondary:active {
        background: #d1d5db;
        transform: scale(0.98);
    }

    .text-center {
        text-align: center;
        color: var(--text-light);
        font-size: clamp(12px, 2vw, 14px);
        padding: clamp(15px, 2vw, 30px);
    }
</style>

<div class="create-quotation-container">
    <div class="page-header">
        <h2><i class="fas fa-file-invoice"></i> Create New Quotation</h2>
        <a href="../quotations.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Quotations
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Client Information Section -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-user"></i> Client Information</h3>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required">Client Name</label>
                        <input type="text" name="client_name" required placeholder="Enter client name">
                    </div>

                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" placeholder="Enter contact person">
                    </div>

                    <div class="form-group">
                        <label class="required">Email Address</label>
                        <input type="email" name="email" required placeholder="client@example.com">
                    </div>

                    <div class="form-group">
                        <label class="required">Phone Number</label>
                        <input type="text" name="phone" required placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <label>Valid Until</label>
                        <input type="date" name="expires_at">
                    </div>
                </div>
            </div>
        </div>

        <!-- Quotation Items Section -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-shopping-cart"></i> Quotation Items</h3>
            </div>
            <div class="section-body">
                <div class="items-table-container">
                    <table class="items-table" id="items-table">
                        <thead>
                            <tr>
                                <th style="width: 80%">Description</th>
                                <th style="width: 15%">Quantity</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-row" class="add-row-btn">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
        </div>

        <div id="productModal" class="product-modal">
            <div class="product-modal-content">
                <div class="product-modal-header">
                    <h3>Select a Product</h3>
                    <button type="button" id="closeProductModal" class="product-modal-close">&times;</button>
                </div>
                <div class="product-modal-body">
                    <div class="product-search-wrap">
                        <input type="text" id="productSearchInput" placeholder="Search products...">
                    </div>
                    <table class="product-table" id="productTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="product-name-cell">
                                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                                            <span><?= htmlspecialchars($product['name']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="product-select-btn" data-product-id="<?= $product['id'] ?>" data-product-name="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>" data-product-price="<?= htmlspecialchars($product['price'], ENT_QUOTES) ?>">Add</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Create Quotation
            </button>
        </div>
    </form>
</div>

<script>
    // Calculate row total
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(row.querySelector('.unit-price').value) || 0;
        return quantity * price;
    }

    // Calculate all totals
    function calculateAllTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('#items-body .item-row');

        rows.forEach(row => {
            subtotal += calculateRowTotal(row);
        });

        const grandTotal = subtotal;

        const subtotalDisplay = document.getElementById('subtotal-display');
        const grandTotalDisplay = document.getElementById('grand-total-display');

        if (subtotalDisplay) subtotalDisplay.innerText = subtotal.toFixed(2);
        if (grandTotalDisplay) grandTotalDisplay.innerText = grandTotal.toFixed(2);
    }

    // Add event listeners to a row
    function addRowListeners(row) {
        const quantityInput = row.querySelector('.quantity');
        const priceInput = row.querySelector('.unit-price');
        const removeBtn = row.querySelector('.remove-row-btn');

        if (quantityInput) {
            quantityInput.addEventListener('input', calculateAllTotals);
        }
        if (priceInput) {
            priceInput.addEventListener('input', calculateAllTotals);
        }
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                const rows = document.querySelectorAll('#items-body .item-row');
                if (rows.length > 1) {
                    row.remove();
                    calculateAllTotals();
                } else {
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-error';
                    alert.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>You need at least one item</span>';
                    document.querySelector('.create-quotation-container').insertBefore(alert, document.querySelector('.create-quotation-container').firstChild);
                    setTimeout(() => alert.remove(), 3000);
                }
            });
        }
    }

    const addRowBtn = document.getElementById('add-row');
    const productModal = document.getElementById('productModal');
    const closeProductModalBtn = document.getElementById('closeProductModal');
    const productSearchInput = document.getElementById('productSearchInput');

    if (addRowBtn) {
        addRowBtn.addEventListener('click', () => {
            if (productModal) {
                productModal.classList.add('show');
                if (productSearchInput) {
                    productSearchInput.value = '';
                    filterProductTable('');
                }
            }
        });
    }

    if (closeProductModalBtn) {
        closeProductModalBtn.addEventListener('click', () => {
            if (productModal) productModal.classList.remove('show');
        });
    }

    if (productModal) {
        productModal.addEventListener('click', event => {
            if (event.target === productModal) {
                productModal.classList.remove('show');
            }
        });
    }

    const productTable = document.getElementById('productTable');

    if (productSearchInput) {
        productSearchInput.addEventListener('input', () => {
            filterProductTable(productSearchInput.value.trim());
        });
    }

    if (productTable) {
        productTable.addEventListener('click', event => {
            const btn = event.target.closest('.product-select-btn');
            if (!btn) return;
            const productId = btn.dataset.productId;
            const productName = btn.dataset.productName;
            const productPrice = parseFloat(btn.dataset.productPrice) || 0;
            selectProduct(productId, productName, productPrice);
        });
    }

    function filterProductTable(query) {
        const normalizedQuery = query.toLowerCase();
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const name = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            row.style.display = name.includes(normalizedQuery) ? '' : 'none';
        });
    }

    function selectProduct(productId, productName, productPrice) {
        const tbody = document.getElementById('items-body');
        if (!tbody) return;

        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <input type="hidden" name="product_id[]" value="${escapeHtml(productId)}">
                <input type="hidden" name="description[]" value="${escapeHtml(productName)}">
                <input type="hidden" name="unit_price[]" class="unit-price" value="${parseFloat(productPrice).toFixed(2)}">
                <span>${escapeHtml(productName)}</span>
            </td>
            <td><input type="number" name="quantity[]" class="quantity" value="1" min="1" required></td>
            <td style="text-align: center;">
                <button type="button" class="remove-row-btn" title="Remove item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        addRowListeners(newRow);
        calculateAllTotals();
        if (productModal) productModal.classList.remove('show');
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Initialize first row listeners
    document.querySelectorAll('#items-body .item-row').forEach(row => {
        addRowListeners(row);
    });

    // Initial calculation
    calculateAllTotals();
</script>

<?php
// Close connection
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
include 'includes/footer.php';
?>