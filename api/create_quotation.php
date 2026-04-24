<?php
// create_quotation.php
session_start();
$baseUrl = '../';
require_once '../connect/config.php';
include_once '../includes/header.php';

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
    $notes = mysqli_real_escape_string($conn, trim($_POST['notes']));

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

    $tax = isset($_POST['tax']) ? floatval($_POST['tax']) : 0;
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $grand_total = $subtotal + $tax - $discount;

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
        header("refresh:2;url=../quotations.php");
    } elseif (empty($error)) {
        $error = "Error: " . $conn->error;
    }
}
?>

<style>
    /* Modern styling similar to cart.php */
    .create-quotation-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .page-header h2 {
        margin: 0;
        color: #333;
        font-size: 28px;
    }

    .back-btn {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .back-btn:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    /* Alert Styles */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.3s ease;
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
        font-size: 20px;
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
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .section-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #e9ecef;
    }

    .section-header h3 {
        margin: 0;
        color: #0f3d67;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header h3 i {
        font-size: 20px;
    }

    .section-body {
        padding: 25px;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group label.required:after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0f3d67;
        box-shadow: 0 0 0 3px rgba(15, 61, 103, 0.1);
    }

    /* Items Table */
    .items-table-container {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead tr {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .items-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .items-table td {
        padding: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .items-table input {
        width: 100%;
        padding: 8px 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .items-table input:focus {
        outline: none;
        border-color: #0f3d67;
    }

    .item-total {
        background: #f8f9fa;
        font-weight: 600;
        color: #0f3d67;
    }

    .remove-row-btn {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }

    .remove-row-btn:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    .add-row-btn {
        margin-top: 15px;
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .add-row-btn:hover {
        background: #218838;
        transform: translateY(-2px);
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
        padding: 20px;
    }

    .product-modal.show {
        display: flex;
    }

    .product-modal-content {
        width: min(1100px, 100%);
        max-height: 90vh;
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
    }

    .product-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .product-modal-header h3 {
        margin: 0;
        font-size: 20px;
        color: #0f3d67;
    }

    .product-modal-close {
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    .product-modal-body {
        padding: 20px;
        overflow: auto;
    }

    .product-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .product-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-name-cell span {
        font-weight: 600;
        color: #0f3d67;
    }

    .product-search-wrap {
        margin-bottom: 16px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .product-search-wrap input {
        flex: 1;
        padding: 10px 14px;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        font-size: 14px;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
    }

    .product-table th,
    .product-table td {
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid #eceff1;
        font-size: 14px;
    }

    .product-table th {
        background: #f5f7fa;
        font-weight: 700;
    }

    .product-select-btn {
        background: #0f3d67;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .product-select-btn:hover {
        background: #0b2a4a;
    }

    /* Summary Section */
    .summary-container {
        max-width: 350px;
        margin-left: auto;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-row.total {
        border-top: 2px solid #0f3d67;
        border-bottom: none;
        padding-top: 15px;
        margin-top: 5px;
        font-weight: bold;
        font-size: 18px;
        color: #0f3d67;
    }

    .summary-label {
        color: #666;
    }

    .summary-value {
        font-weight: 600;
        color: #333;
    }

    .summary-input {
        width: 120px;
        padding: 5px 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        text-align: right;
    }

    /* Submit Button */
    .submit-section {
        text-align: right;
        margin-top: 30px;
    }

    .submit-btn {
        background: #0f3d67;
        color: white;
        padding: 14px 32px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .submit-btn:hover {
        background: #0a2e4a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 61, 103, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .summary-container {
            max-width: 100%;
        }

        .items-table {
            font-size: 12px;
        }

        .items-table input {
            padding: 5px;
        }
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
                                <th style="width: 50%">Description</th>
                                <th style="width: 15%">Quantity</th>
                                <th style="width: 20%">Unit Price (₱)</th>
                                <th style="width: 15%">Total (₱)</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <tr class="item-row">
                                <td><input type="text" name="description[]" placeholder="Item description" required></td>
                                <td><input type="number" name="quantity[]" class="quantity" value="1" min="1" required></td>
                                <td><input type="number" name="unit_price[]" class="unit-price" value="0" min="0" step="0.01" required></td>
                                <td><input type="text" class="item-total" readonly style="background: #f8f9fa; font-weight: 600;"></td>
                                <td style="text-align: center;">
                                    <button type="button" class="remove-row-btn" title="Remove item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
                        <input type="text" id="productSearchInput" placeholder="Search products by name...">
                    </div>
                    <table class="product-table" id="productTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price (₱)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="product-name-cell">
                                            <img src="<?= $baseUrl . htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                                            <span><?= htmlspecialchars($product['name']) ?></span>
                                        </div>
                                    </td>
                                    <td>₱<?= number_format($product['price'], 2) ?></td>
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

        <!-- Summary Section -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-calculator"></i> Order Summary</h3>
            </div>
            <div class="section-body">
                <div class="summary-container">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">₱ <span id="subtotal-display">0.00</span></span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Tax (₱):</span>
                        <input type="number" name="tax" id="tax-input" value="0" step="0.01" class="summary-input">
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Discount (₱):</span>
                        <input type="number" name="discount" id="discount-input" value="0" step="0.01" class="summary-input">
                    </div>

                    <div class="summary-row total">
                        <span class="summary-label">Grand Total:</span>
                        <span class="summary-value">₱ <span id="grand-total-display">0.00</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-sticky-note"></i> Additional Notes</h3>
            </div>
            <div class="section-body">
                <div class="form-group">
                    <textarea name="notes" rows="4" placeholder="Any special instructions or notes for the client..."></textarea>
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
        const total = quantity * price;
        const totalField = row.querySelector('.item-total');
        if (totalField) {
            totalField.value = total.toFixed(2);
        }
        return total;
    }

    // Calculate all totals
    function calculateAllTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('#items-body .item-row');

        rows.forEach(row => {
            subtotal += calculateRowTotal(row);
        });

        const taxInput = document.getElementById('tax-input');
        const discountInput = document.getElementById('discount-input');
        const tax = taxInput ? (parseFloat(taxInput.value) || 0) : 0;
        const discount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;
        const grandTotal = subtotal + tax - discount;

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
                    // Show temporary notification
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
                <input type="text" name="description[]" value="${escapeHtml(productName)}" required>
            </td>
            <td><input type="number" name="quantity[]" class="quantity" value="1" min="1" required></td>
            <td><input type="number" name="unit_price[]" class="unit-price" value="${parseFloat(productPrice).toFixed(2)}" min="0" step="0.01" required></td>
            <td><input type="text" class="item-total" readonly style="background: #f8f9fa; font-weight: 600;"></td>
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

    // Tax and discount listeners
    const taxInput = document.getElementById('tax-input');
    const discountInput = document.getElementById('discount-input');

    if (taxInput) taxInput.addEventListener('input', calculateAllTotals);
    if (discountInput) discountInput.addEventListener('input', calculateAllTotals);

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
include '../includes/footer.php';
?>