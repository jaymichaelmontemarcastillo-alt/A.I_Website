<?php
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
$user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
$quote_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$success = '';
$quote = null;
$items = [];

if ($quote_id <= 0) {
    $error = 'Invalid quotation selected.';
} else {
    if ($user_id !== null) {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND user_id = ?');
        $stmt->execute([$quote_id, $user_id]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND session_id = ?');
        $stmt->execute([$quote_id, $session_id]);
    }
    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$quote) {
        $error = 'Quotation not found or access denied.';
    } else {
        $itemStmt = $pdo->prepare('SELECT * FROM quotation_items WHERE quotation_id = ?');
        $itemStmt->execute([$quote_id]);
        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $client_name = mysqli_real_escape_string($conn, trim($_POST['client_name']));
    $contact_person = mysqli_real_escape_string($conn, trim($_POST['contact_person']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $expires_at = mysqli_real_escape_string($conn, trim($_POST['expires_at']));
    $notes = mysqli_real_escape_string($conn, trim($_POST['notes']));

    $descriptions = $_POST['description'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $unit_prices = $_POST['unit_price'] ?? [];

    $subtotal = 0;
    $updatedItems = [];
    for ($i = 0; $i < count($descriptions); $i++) {
        if (!empty($descriptions[$i]) && isset($quantities[$i]) && floatval($quantities[$i]) > 0) {
            $qty = floatval($quantities[$i]);
            $price = floatval($unit_prices[$i]);
            $total = $qty * $price;
            $subtotal += $total;
            $updatedItems[] = [
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

    if (empty($client_name) || empty($email) || empty($phone)) {
        $error = 'Client name, email address, and phone number are required to update the quotation.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^(09|\+639)\d{9}$/', preg_replace('/\s+/', '', $phone))) {
        $error = 'Please enter a valid Philippine phone number (e.g. 09123456789 or +639123456789).';
    } elseif (count($updatedItems) === 0) {
        $error = 'Please add at least one quotation item.';
    }

    if (empty($error)) {
        if ($user_id !== null) {
            $update = $conn->prepare(
                "UPDATE quotations SET client_name = ?, contact_person = ?, email = ?, phone = ?, subtotal = ?, tax = ?, discount = ?, total = ?, notes = ?, expires_at = ? WHERE id = ? AND user_id = ?"
            );
            $update->bind_param(
                'ssssddddssii',
                $client_name,
                $contact_person,
                $email,
                $phone,
                $subtotal,
                $tax,
                $discount,
                $grand_total,
                $notes,
                $expires_at,
                $quote_id,
                $user_id
            );
        } else {
            $update = $conn->prepare(
                "UPDATE quotations SET session_id = ?, client_name = ?, contact_person = ?, email = ?, phone = ?, subtotal = ?, tax = ?, discount = ?, total = ?, notes = ?, expires_at = ? WHERE id = ? AND session_id = ?"
            );
            $update->bind_param(
                'sssssddddssis',
                $session_id,
                $client_name,
                $contact_person,
                $email,
                $phone,
                $subtotal,
                $tax,
                $discount,
                $grand_total,
                $notes,
                $expires_at,
                $quote_id,
                $session_id
            );
        }

        if ($update->execute()) {
            $update->close();
            $deleteItems = $conn->prepare("DELETE FROM quotation_items WHERE quotation_id = ?");
            $deleteItems->bind_param('i', $quote_id);
            $deleteItems->execute();
            $deleteItems->close();

            if (count($updatedItems) > 0) {
                $item_stmt = $conn->prepare("INSERT INTO quotation_items (quotation_id, description, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)");
                foreach ($updatedItems as $item) {
                    $desc = $item['description'];
                    $item_stmt->bind_param('isddd', $quote_id, $desc, $item['quantity'], $item['unit_price'], $item['total']);
                    $item_stmt->execute();
                }
                $item_stmt->close();
            }

            $success = 'Quotation updated successfully.';
            $quote['client_name'] = $client_name;
            $quote['contact_person'] = $contact_person;
            $quote['email'] = $email;
            $quote['phone'] = $phone;
            $quote['expires_at'] = $expires_at;
            $quote['notes'] = $notes;
            $quote['subtotal'] = $subtotal;
            $quote['tax'] = $tax;
            $quote['discount'] = $discount;
            $quote['total'] = $grand_total;
            $items = $updatedItems;
        } else {
            $error = 'Could not update quotation: ' . $conn->error;
        }
    }
}

function renderItemRow($item)
{
    $product_id = isset($item['product_id']) ? intval($item['product_id']) : '';
    $description = htmlspecialchars($item['description']);
    $quantity = intval($item['quantity']);
    $unit_price = number_format($item['unit_price'], 2, '.', '');
    return "<tr class=\"item-row\"><td><input type=\"hidden\" name=\"product_id[]\" value=\"{$product_id}\"><input type=\"text\" name=\"description[]\" value=\"{$description}\" required></td><td><input type=\"number\" name=\"quantity[]\" class=\"quantity\" value=\"{$quantity}\" min=\"1\" required></td><td><input type=\"number\" name=\"unit_price[]\" class=\"unit-price\" value=\"{$unit_price}\" min=\"0\" step=\"0.01\" required></td><td><input type=\"text\" class=\"item-total\" readonly style=\"background: #f8f9fa; font-weight: 600;\"></td><td style=\"text-align: center;\"><button type=\"button\" class=\"remove-row-btn\" title=\"Remove item\"><i class=\"fas fa-trash\"></i></button></td></tr>";
}
?>

<style>
    /* Reuse the same styling from create_quotation.php */
    .create-quotation-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0; }
    .page-header h2 { margin: 0; color: #333; font-size: 28px; }
    .back-btn, .add-row-btn, .remove-row-btn { text-decoration: none; }
    .back-btn { background: #6c757d; color: white; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; }
    .back-btn:hover { background: #5a6268; }
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
    .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .alert-error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
    .form-section { background: white; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .section-header { background: #f8f9fa; padding: 15px 20px; border-bottom: 2px solid #e9ecef; }
    .section-header h3 { margin: 0; color: #0f3d67; font-size: 18px; display: flex; align-items: center; gap: 10px; }
    .section-body { padding: 25px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; font-size: 14px; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; }
    .product-modal { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65); z-index: 2000; align-items: center; justify-content: center; padding: 20px; }
    .product-modal.show { display: flex; }
    .product-modal-content { width: min(1100px, 100%); max-height: 90vh; background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column; }
    .product-modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #e9ecef; background: #f8f9fa; }
    .product-table { width: 100%; border-collapse: collapse; }
    .product-table th, .product-table td { padding: 14px 12px; border-bottom: 1px solid #eceff1; }
    .product-table th { background: #f5f7fa; font-weight: 700; }
    .product-select-btn { background: #0f3d67; color: white; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; }
    .product-select-btn:hover { background: #0b2a4a; }
    .items-table-container { overflow-x: auto; }
    .items-table { width: 100%; border-collapse: collapse; }
    .items-table th { padding: 12px; background: #f8f9fa; text-align: left; }
    .items-table td { padding: 10px; border-bottom: 1px solid #e9ecef; }
    .items-table input { width: 100%; padding: 8px 10px; border: 2px solid #e0e0e0; border-radius: 6px; }
    .item-total { background: #f8f9fa; font-weight: 600; color: #0f3d67; }
    .add-row-btn { margin-top: 15px; background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; }
    .submit-btn { background: #0f3d67; color: white; padding: 14px 32px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<div class="create-quotation-container">
    <div class="page-header">
        <h2><i class="fas fa-file-invoice"></i> Edit Quotation</h2>
        <div class="page-header-actions">
            <a href="../quotations.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($error)): ?>
        <form method="POST" action="?id=<?= $quote_id ?>">
            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-user"></i> Client Information</h3>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="required">Client Name</label>
                            <input type="text" name="client_name" value="<?= htmlspecialchars($quote['client_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" value="<?= htmlspecialchars($quote['contact_person']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="required">Email Address</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($quote['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Phone Number</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($quote['phone']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Valid Until</label>
                            <input type="date" name="expires_at" value="<?= htmlspecialchars($quote['expires_at']) ?>">
                        </div>
                    </div>
                </div>
            </div>

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
                                <?php if (count($items) > 0): ?>
                                    <?php foreach ($items as $item): ?>
                                        <?= renderItemRow($item) ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="item-row">
                                        <td><input type="text" name="description[]" placeholder="Item description" required></td>
                                        <td><input type="number" name="quantity[]" class="quantity" value="1" min="1" required></td>
                                        <td><input type="number" name="unit_price[]" class="unit-price" value="0" min="0" step="0.01" required></td>
                                        <td><input type="text" class="item-total" readonly style="background: #f8f9fa; font-weight: 600;"></td>
                                        <td style="text-align: center;"><button type="button" class="remove-row-btn" title="Remove item"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="add-row" class="btn btn-primary add-row-btn"><i class="fas fa-plus"></i> Add Item</button>
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

            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-calculator"></i> Order Summary</h3>
                </div>
                <div class="section-body">
                    <div class="summary-container">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">₱ <span id="subtotal-display"><?= number_format($quote['subtotal'], 2) ?></span></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Tax (₱):</span>
                            <input type="number" name="tax" id="tax-input" value="<?= number_format($quote['tax'], 2) ?>" step="0.01" class="summary-input">
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Discount (₱):</span>
                            <input type="number" name="discount" id="discount-input" value="<?= number_format($quote['discount'], 2) ?>" step="0.01" class="summary-input">
                        </div>
                        <div class="summary-row total">
                            <span class="summary-label">Grand Total:</span>
                            <span class="summary-value">₱ <span id="grand-total-display"><?= number_format($quote['total'], 2) ?></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <h3><i class="fas fa-sticky-note"></i> Additional Notes</h3>
                </div>
                <div class="section-body">
                    <div class="form-group">
                        <textarea name="notes" rows="4" placeholder="Any special instructions or notes for the client..."><?= htmlspecialchars($quote['notes']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn"><i class="fas fa-save"></i> Update Quotation</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(row.querySelector('.unit-price').value) || 0;
        const total = quantity * price;
        const totalField = row.querySelector('.item-total');
        if (totalField) totalField.value = total.toFixed(2);
        return total;
    }

    function calculateAllTotals() {
        let subtotal = 0;
        document.querySelectorAll('#items-body .item-row').forEach(row => {
            subtotal += calculateRowTotal(row);
        });
        const tax = parseFloat(document.getElementById('tax-input').value) || 0;
        const discount = parseFloat(document.getElementById('discount-input').value) || 0;
        const grandTotal = subtotal + tax - discount;
        document.getElementById('subtotal-display').innerText = subtotal.toFixed(2);
        document.getElementById('grand-total-display').innerText = grandTotal.toFixed(2);
    }

    function addRowListeners(row) {
        row.querySelector('.quantity')?.addEventListener('input', calculateAllTotals);
        row.querySelector('.unit-price')?.addEventListener('input', calculateAllTotals);
        row.querySelector('.remove-row-btn')?.addEventListener('click', () => {
            const rows = document.querySelectorAll('#items-body .item-row');
            if (rows.length > 1) {
                row.remove();
                calculateAllTotals();
            }
        });
    }

    document.querySelectorAll('#items-body .item-row').forEach(row => addRowListeners(row));
    document.getElementById('tax-input')?.addEventListener('input', calculateAllTotals);
    document.getElementById('discount-input')?.addEventListener('input', calculateAllTotals);
    calculateAllTotals();

    const productModal = document.getElementById('productModal');
    const productSearchInput = document.getElementById('productSearchInput');

    document.getElementById('add-row')?.addEventListener('click', () => {
        productModal.classList.add('show');
        if (productSearchInput) {
            productSearchInput.value = '';
            filterProductTable('');
        }
    });
    document.getElementById('closeProductModal')?.addEventListener('click', () => productModal.classList.remove('show'));
    productModal?.addEventListener('click', event => { if (event.target === productModal) productModal.classList.remove('show'); });

    document.getElementById('productTable')?.addEventListener('click', event => {
        const btn = event.target.closest('.product-select-btn');
        if (!btn) return;
        const productId = btn.dataset.productId;
        const productName = btn.dataset.productName;
        const productPrice = parseFloat(btn.dataset.productPrice) || 0;
        selectProduct(productId, productName, productPrice);
    });

    productSearchInput?.addEventListener('input', () => filterProductTable(productSearchInput.value.trim()));

    function filterProductTable(query) {
        const normalized = query.toLowerCase();
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const text = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            row.style.display = text.includes(normalized) ? '' : 'none';
        });
    }

    function selectProduct(productId, productName, productPrice) {
        const tbody = document.getElementById('items-body');
        if (!tbody) return;
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td><input type="hidden" name="product_id[]" value="${escapeHtml(productId)}"><input type="text" name="description[]" value="${escapeHtml(productName)}" required></td>
            <td><input type="number" name="quantity[]" class="quantity" value="1" min="1" required></td>
            <td><input type="number" name="unit_price[]" class="unit-price" value="${parseFloat(productPrice).toFixed(2)}" min="0" step="0.01" required></td>
            <td><input type="text" class="item-total" readonly style="background: #f8f9fa; font-weight: 600;"></td>
            <td style="text-align: center;"><button type="button" class="remove-row-btn" title="Remove item"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(newRow);
        addRowListeners(newRow);
        calculateAllTotals();
        productModal.classList.remove('show');
    }

    function escapeHtml(value) {
        return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
</script>

<?php include '../includes/footer.php'; ?>
