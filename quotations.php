<?php
// quotations.php
session_start();
require_once 'connect/config.php';

$pdo  = getDBConnection();
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$products = $pdo->query("SELECT id, name, price, image FROM products ORDER BY name")
    ->fetchAll(PDO::FETCH_ASSOC);

$user_id    = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

$error   = '';
$success = '';

// ── Generate unique quote number ─────────────────────────────────────────────
function generateQuoteNumber($conn)
{
    $year   = date('Y');
    $month  = date('m');
    $prefix = "AI-{$year}{$month}-";

    $result = mysqli_query($conn, "SELECT MAX(quote_number) AS last FROM quotations WHERE quote_number LIKE '{$prefix}%'");
    $row    = mysqli_fetch_assoc($result);

    $new_num = $row['last']
        ? str_pad(intval(substr($row['last'], -4)) + 1, 4, '0', STR_PAD_LEFT)
        : '0001';

    return $prefix . $new_num;
}

// ── Handle form submission ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $client_name    = trim($_POST['client_name']    ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $email          = trim($_POST['email']          ?? '');
    $phone          = trim($_POST['phone']          ?? '');
    $expires_at     = trim($_POST['expires_at']     ?? '');
    $notes          = '';

    $descriptions = $_POST['description'] ?? [];
    $quantities   = $_POST['quantity']    ?? [];
    $unit_prices  = $_POST['unit_price']  ?? [];

    $subtotal = 0;
    $items    = [];

    for ($i = 0; $i < count($descriptions); $i++) {
        if (!empty($descriptions[$i]) && floatval($quantities[$i]) > 0) {
            $qty   = floatval($quantities[$i]);
            $price = floatval($unit_prices[$i]);
            $total = $qty * $price;
            $subtotal += $total;

            $items[] = [
                'description' => $descriptions[$i],
                'quantity'    => $qty,
                'unit_price'  => $price,
                'total'       => $total,
            ];
        }
    }

    $grand_total = $subtotal;

    if (empty($client_name) || empty($email) || empty($phone)) {
        $error = 'Client name, email address, and phone number are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^(09|\+639)\d{9}$/', preg_replace('/\s+/', '', $phone))) {
        $error = 'Please enter a valid Philippine phone number (e.g. 09123456789).';
    } elseif (count($items) === 0) {
        $error = 'Please add at least one quotation item.';
    }

    if (empty($error)) {
        $quote_number = generateQuoteNumber($conn);
        $tax          = 0;
        $discount     = 0;

        if ($user_id !== null) {
            $stmt = $conn->prepare(
                "INSERT INTO quotations
                    (quote_number, user_id, session_id, client_name, contact_person,
                     email, phone, subtotal, tax, discount, total, notes, expires_at, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')"
            );
            $stmt->bind_param(
                'sissssddddsss',
                $quote_number,
                $user_id,
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
                $expires_at
            );
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO quotations
                    (quote_number, user_id, session_id, client_name, contact_person,
                     email, phone, subtotal, tax, discount, total, notes, expires_at, status)
                 VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'draft')"
            );
            $stmt->bind_param(
                'ssssssddddss',
                $quote_number,
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
                $expires_at
            );
        }

        if ($stmt->execute()) {
            $quotation_id = $conn->insert_id;
            $stmt->close();

            if (count($items) > 0) {
                $item_stmt = $conn->prepare(
                    "INSERT INTO quotation_items (quotation_id, description, quantity, unit_price, total)
                     VALUES (?, ?, ?, ?, ?)"
                );
                foreach ($items as $item) {
                    $desc = $item['description'];
                    $item_stmt->bind_param('isddd', $quotation_id, $desc, $item['quantity'], $item['unit_price'], $item['total']);
                    $item_stmt->execute();
                }
                $item_stmt->close();
            }

            $_SESSION['quote_success'] = "Quotation created successfully! Quote #: {$quote_number}";
            $conn->close();
            header('Location: quotations.php?created=1');
            exit;
        } else {
            $error = 'Database error: ' . $conn->error;
            $stmt->close();
        }
    }
}

if (isset($_GET['created']) && isset($_SESSION['quote_success'])) {
    $success = $_SESSION['quote_success'];
    unset($_SESSION['quote_success']);
}

// ── Fetch existing quotations ─────────────────────────────────────────────────
if ($user_id !== null) {
    $q_stmt = $conn->prepare(
        "SELECT quote_number, client_name, total, status, expires_at, created_at, id
         FROM quotations WHERE user_id = ? ORDER BY created_at DESC"
    );
    $q_stmt->bind_param('i', $user_id);
} else {
    $q_stmt = $conn->prepare(
        "SELECT quote_number, client_name, total, status, expires_at, created_at, id
         FROM quotations WHERE session_id = ? ORDER BY created_at DESC"
    );
    $q_stmt->bind_param('s', $session_id);
}
$q_stmt->execute();
$quotations = $q_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$q_stmt->close();

include_once 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/quotations-list.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

<div class="quotations-page">

    <!-- ── Page Header ──────────────────────────────────────────────────── -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-file-invoice-dollar"></i> My Quotations</h1>
            <p class="header-subtitle">Manage and track all your submitted quotations</p>
        </div>
        <button class="btn-create-header" id="openCreateModal">
            <i class="fas fa-plus"></i> <span class="btn-text">Create New</span>
        </button>
    </div>

    <!-- ── Alerts ───────────────────────────────────────────────────────── -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" id="pageAlert">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($success) ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- ── Stats Strip ──────────────────────────────────────────────────── -->
    <?php
    $total_count   = count($quotations);
    $draft_count   = count(array_filter($quotations, fn($q) => strtolower($q['status']) === 'draft'));
    $sent_count    = count(array_filter($quotations, fn($q) => strtolower($q['status']) === 'sent'));
    $approved_count = count(array_filter($quotations, fn($q) => strtolower($q['status']) === 'approved'));
    $total_value   = array_sum(array_column($quotations, 'total'));
    ?>
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon stat-all"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Quotes</div>
                <div class="stat-value"><?= $total_count ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-draft"><i class="fas fa-pencil-alt"></i></div>
            <div class="stat-content">
                <div class="stat-label">Draft</div>
                <div class="stat-value"><?= $draft_count ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-sent"><i class="fas fa-paper-plane"></i></div>
            <div class="stat-content">
                <div class="stat-label">Sent</div>
                <div class="stat-value"><?= $sent_count ?></div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-approved"><i class="fas fa-check-double"></i></div>
            <div class="stat-content">
                <div class="stat-label">Approved</div>
                <div class="stat-value"><?= $approved_count ?></div>
            </div>
        </div>
        <div class="stat-box stat-total">
            <div class="stat-icon stat-peso"><i class="fas fa-peso-sign"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Value</div>
                <div class="stat-value">₱<?= number_format($total_value, 2) ?></div>
            </div>
        </div>
    </div>

    <!-- ── Table Card ───────────────────────────────────────────────────── -->
    <div class="quotations-card">
        <div class="card-toolbar">
            <div class="toolbar-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="draft">Draft</button>
                <button class="filter-btn" data-filter="sent">Sent</button>
                <button class="filter-btn" data-filter="approved">Approved</button>
                <button class="filter-btn" data-filter="rejected">Rejected</button>
            </div>
            <div class="toolbar-search">
                <i class="fas fa-search"></i>
                <input type="text" id="tableSearch" placeholder="Search quotes..." class="search-input">
            </div>
        </div>

        <div class="quotations-list">
            <?php if (empty($quotations)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-file-invoice"></i></div>
                    <h3>No quotations yet</h3>
                    <p>Get started by creating your first quotation</p>
                    <button class="btn-create-empty" id="openCreateModalEmpty">
                        <i class="fas fa-plus"></i> Create Quotation
                    </button>
                </div>
            <?php else: ?>
                <div class="quotations-table-wrapper">
                    <table class="quotations-table" id="quotationsTable">
                        <thead>
                            <tr>
                                <th>Quote #</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quotations as $q): ?>
                                <?php
                                $status    = strtolower($q['status']);
                                $expires   = $q['expires_at'] ? date('M j', strtotime($q['expires_at'])) : '—';
                                $created   = date('M j, Y', strtotime($q['created_at']));
                                ?>
                                <tr class="table-row" data-status="<?= htmlspecialchars($status) ?>"
                                    data-search="<?= htmlspecialchars(strtolower($q['quote_number'] . ' ' . $q['client_name'])) ?>">
                                    <td data-label="Quote #">
                                        <span class="quote-badge"><?= htmlspecialchars($q['quote_number']) ?></span>
                                    </td>
                                    <td data-label="Client"><?= htmlspecialchars($q['client_name']) ?></td>
                                    <td data-label="Amount" class="amount-cell">₱<?= number_format($q['total'], 2) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-<?= $status ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td data-label="Expires" class="expires-cell"><?= $expires ?></td>
                                    <td data-label="Date" class="date-cell"><?= $created ?></td>
                                    <td data-label="Actions">
                                        <div class="action-group">
                                            <?php if (strtolower($q['status']) === 'converted'): ?>
                                                <a href="api/view_quotation.php?id=<?= $q['id'] ?>" class="action-btn view-btn" title="View PDF">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button class="action-btn edit-btn" onclick="openEditModal(<?= $q['id'] ?>, '<?= htmlspecialchars($q['quote_number'], ENT_QUOTES) ?>')" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="action-btn delete-btn" onclick="confirmDelete(<?= $q['id'] ?>, '<?= htmlspecialchars($q['quote_number']) ?>')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════════
     CREATE QUOTATION MODAL
════════════════════════════════════════════════════════════════════════ -->
<div id="createQuotationModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <h2>Create New Quotation</h2>
                    <p>Fill in the details to generate a quotation</p>
                </div>
            </div>
            <button class="modal-close" id="closeCreateModal">&times;</button>
        </div>

        <div id="modalAlert" class="modal-alert" style="display:none;"></div>

        <div class="modal-body">
            <form method="POST" action="" id="createQuotationForm">

                <!-- Client Information -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i> Client Information
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Client Name <span class="req">*</span></label>
                            <input type="text" name="client_name" placeholder="Enter client or company name"
                                value="<?= htmlspecialchars($_POST['client_name'] ?? '') ?>">
                        </div>
                        <div class="form-field">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" placeholder="Enter contact person"
                                value="<?= htmlspecialchars($_POST['contact_person'] ?? '') ?>">
                        </div>
                        <div class="form-field">
                            <label>Email Address <span class="req">*</span></label>
                            <input type="email" name="email" placeholder="client@example.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="form-field">
                            <label>Phone Number <span class="req">*</span></label>
                            <input type="text" name="phone" placeholder="09XXXXXXXXX"
                                value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>
                        <div class="form-field">
                            <label>Valid Until</label>
                            <input type="date" name="expires_at"
                                value="<?= htmlspecialchars($_POST['expires_at'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Quotation Items -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i> Quotation Items
                    </div>

                    <div class="items-container">
                        <table class="items-table" id="items-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="items-body"></tbody>
                        </table>
                    </div>

                    <button type="button" id="add-row" class="btn-add-item">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>

                <!-- Submit -->
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="cancelModal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Quotation
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════════
     PRODUCT SELECTION MODAL
════════════════════════════════════════════════════════════════════════ -->
<div id="productModal" class="modal-overlay product-modal-overlay">
    <div class="modal-container product-modal-container">
        <div class="modal-header">
            <h2><i class="fas fa-box"></i> Select Products</h2>
            <button type="button" id="closeProductModal" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="product-search">
                <i class="fas fa-search"></i>
                <input type="text" id="productSearchInput" placeholder="Search products…" class="search-input">
            </div>
            <table class="product-table" id="productTable">
                <thead>
                    <tr>
                        <th style="width:auto;"></th>
                        <th>Product</th>
                        <th style="width:100px;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="product-checkbox"
                                    data-product-id="<?= $product['id'] ?>"
                                    data-product-name="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>"
                                    data-product-price="<?= htmlspecialchars($product['price'], ENT_QUOTES) ?>">
                            </td>
                            <td>
                                <div class="product-cell">
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="" class="product-img">
                                    <span><?= htmlspecialchars($product['name']) ?></span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="quantity-input" value="1" min="1">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" id="addSelectedProducts" class="btn-submit">
                <i class="fas fa-check"></i> Add Selected
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════════
     EDIT QUOTATION MODAL
════════════════════════════════════════════════════════════════════════ -->
<div id="editQuotationModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <h2>Edit Quotation</h2>
                    <p>Update the quotation details</p>
                </div>
            </div>
            <button class="modal-close" id="closeEditModal">&times;</button>
        </div>

        <div id="editModalAlert" class="modal-alert" style="display:none;"></div>

        <div class="modal-body">
            <form method="POST" action="" id="editQuotationForm">
                <input type="hidden" id="editQuotationId" name="id" value="">

                <!-- Client Information -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i> Client Information
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Client Name <span class="req">*</span></label>
                            <input type="text" id="editClientName" name="client_name" placeholder="Enter client or company name">
                        </div>
                        <div class="form-field">
                            <label>Contact Person</label>
                            <input type="text" id="editContactPerson" name="contact_person" placeholder="Enter contact person">
                        </div>
                        <div class="form-field">
                            <label>Email Address <span class="req">*</span></label>
                            <input type="email" id="editEmail" name="email" placeholder="client@example.com">
                        </div>
                        <div class="form-field">
                            <label>Phone Number <span class="req">*</span></label>
                            <input type="text" id="editPhone" name="phone" placeholder="09XXXXXXXXX">
                        </div>
                        <div class="form-field">
                            <label>Valid Until</label>
                            <input type="date" id="editExpiresAt" name="expires_at">
                        </div>
                    </div>
                </div>

                <!-- Quotation Items -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i> Quotation Items
                    </div>

                    <div class="items-container">
                        <table class="items-table" id="editItemsTable">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="editItemsBody"></tbody>
                        </table>
                    </div>

                    <button type="button" id="editAddRow" class="btn-add-item">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>

                <!-- Submit -->
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="cancelEditModal">Cancel</button>
                    <button type="button" class="btn-submit" id="submitEditForm">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
════════════════════════════════════════════════════════════════════════ -->
<div id="deleteModal" class="modal-overlay delete-modal-overlay">
    <div class="delete-modal">
        <div class="delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete Quotation?</h3>
        <p>Are you sure you want to delete <strong id="deleteQuoteNum"></strong>?</p>
        <p class="delete-warning">This action cannot be undone.</p>
        <div class="delete-actions">
            <button class="btn-cancel" onclick="document.getElementById('deleteModal').classList.remove('show')">Cancel</button>
            <button id="deleteConfirmBtn" class="btn-delete" onclick="performDelete()">Delete</button>
        </div>
    </div>
</div>

<script>
    // ── Utility Functions ────────────────────────────────────────────────
    function escapeHtml(v) {
        const div = document.createElement('div');
        div.textContent = v;
        return div.innerHTML;
    }

    // ── Modal Management ────────────────────────────────────────────────
    const createModal = document.getElementById('createQuotationModal');
    const productModal = document.getElementById('productModal');
    const deleteModal = document.getElementById('deleteModal');

    function openModal(modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    document.getElementById('openCreateModal').addEventListener('click', () => openModal(createModal));
    document.getElementById('closeCreateModal').addEventListener('click', () => closeModal(createModal));
    document.getElementById('cancelModal').addEventListener('click', () => closeModal(createModal));
    document.getElementById('openCreateModalEmpty')?.addEventListener('click', () => openModal(createModal));
    document.getElementById('closeProductModal').addEventListener('click', () => closeModal(productModal));

    createModal.addEventListener('click', (e) => {
        if (e.target === createModal) closeModal(createModal);
    });
    productModal.addEventListener('click', (e) => {
        if (e.target === productModal) closeModal(productModal);
    });
    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeModal(deleteModal);
    });

    <?php if (!empty($error)): ?>
        window.addEventListener('DOMContentLoaded', () => {
            openModal(createModal);
            const alertEl = document.getElementById('modalAlert');
            alertEl.style.display = 'flex';
            alertEl.className = 'modal-alert alert-error';
            alertEl.innerHTML = '<i class="fas fa-exclamation-circle"></i><span><?= addslashes(htmlspecialchars($error)) ?></span>';
        });
    <?php endif; ?>

    // ── Table Filtering ────────────────────────────────────────────────
    const searchInput = document.getElementById('tableSearch');
    const filterButtons = document.querySelectorAll('.filter-btn');
    let activeFilter = 'all';

    searchInput?.addEventListener('input', applyFilters);

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    function applyFilters() {
        const query = searchInput?.value.toLowerCase() || '';
        document.querySelectorAll('.table-row').forEach(row => {
            const matchFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
            const matchSearch = row.dataset.search.includes(query);
            row.style.display = (matchFilter && matchSearch) ? '' : 'none';
        });
    }

    // ── Delete Modal ────────────────────────────────────────────────────
    function confirmDelete(id, quoteNum) {
        document.getElementById('deleteQuoteNum').textContent = quoteNum;
        document.getElementById('deleteConfirmLink').href = `delete_quotation.php?id=${id}`;
        openModal(deleteModal);
    }

    // ── Items Management ────────────────────────────────────────────────
    function addRowListeners(row) {
        row.querySelector('.remove-row-btn')?.addEventListener('click', () => {
            const rows = document.querySelectorAll('#items-body .item-row');
            if (rows.length > 1) {
                row.remove();
            } else {
                showModalAlert('You need at least one item.', 'error');
            }
        });
    }

    function showModalAlert(msg, type = 'error') {
        const alertEl = document.getElementById('modalAlert');
        alertEl.style.display = 'flex';
        alertEl.className = `modal-alert alert-${type}`;
        alertEl.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i><span>${escapeHtml(msg)}</span>`;
        setTimeout(() => alertEl.style.display = 'none', 4000);
    }

    // ── Product Modal ────────────────────────────────────────────────────
    const productSearchInput = document.getElementById('productSearchInput');

    document.getElementById('add-row').addEventListener('click', () => {
        openModal(productModal);
        productSearchInput.value = '';
        filterProductTable('');
    });

    productSearchInput?.addEventListener('input', () => {
        filterProductTable(productSearchInput.value.trim());
    });

    function filterProductTable(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const name = row.querySelector('.product-cell span')?.textContent.toLowerCase() || '';
            row.style.display = name.includes(q) ? '' : 'none';
        });
    }

    document.getElementById('addSelectedProducts').addEventListener('click', () => {
        const checked = document.querySelectorAll('.product-checkbox:checked');
        if (!checked.length) {
            alert('Please select at least one product.');
            return;
        }

        const tbody = document.getElementById('items-body');

        checked.forEach(checkbox => {
            const tr = checkbox.closest('tr');
            const qty = parseInt(tr.querySelector('.quantity-input').value, 10) || 1;
            const id = checkbox.dataset.productId;
            const name = checkbox.dataset.productName;
            const price = parseFloat(checkbox.dataset.productPrice) || 0;

            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
            <td>
                <input type="hidden" name="product_id[]"  value="${escapeHtml(id)}">
                <input type="hidden" name="description[]" value="${escapeHtml(name)}">
                <input type="hidden" name="unit_price[]"  class="unit-price" value="${price.toFixed(2)}">
                <div class="item-details">
                    <span class="item-name">${escapeHtml(name)}</span>
                    <span class="item-price">₱${price.toFixed(2)}</span>
                </div>
            </td>
            <td>
                <input type="number" name="quantity[]" class="quantity"
                       value="${qty}" min="1" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </td>`;

            tbody.appendChild(newRow);
            addRowListeners(newRow);

            checkbox.checked = false;
            tr.querySelector('.quantity-input').value = 1;
        });

        closeModal(productModal);
    });

    // ── Init ────────────────────────────────────────────────────────────
    document.querySelectorAll('#items-body .item-row').forEach(addRowListeners);

    // Auto-dismiss page alert
    const pageAlert = document.getElementById('pageAlert');
    if (pageAlert) setTimeout(() => pageAlert.remove(), 5000);

    // ── Edit Modal Management ────────────────────────────────────────────
    const editModal = document.getElementById('editQuotationModal');
    let currentEditId = null;

    document.getElementById('closeEditModal').addEventListener('click', () => closeModal(editModal));
    document.getElementById('cancelEditModal').addEventListener('click', () => closeModal(editModal));

    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) closeModal(editModal);
    });

    async function openEditModal(quotationId, quoteNumber) {
        currentEditId = quotationId;

        // Fetch quotation data
        try {
            const response = await fetch(`api/get_quotation_details.php?id=${quotationId}`);
            const data = await response.json();

            if (data.success) {
                const quote = data.quotation;
                const items = data.items;

                // Populate form fields
                document.getElementById('editQuotationId').value = quotationId;
                document.getElementById('editClientName').value = quote.client_name || '';
                document.getElementById('editContactPerson').value = quote.contact_person || '';
                document.getElementById('editEmail').value = quote.email || '';
                document.getElementById('editPhone').value = quote.phone || '';
                document.getElementById('editExpiresAt').value = quote.expires_at ? quote.expires_at.split(' ')[0] : '';

                // Populate items
                const itemsBody = document.getElementById('editItemsBody');
                itemsBody.innerHTML = '';

                items.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'item-row';
                    row.innerHTML = `
                        <td>
                            <input type="hidden" name="description[]" value="${escapeHtml(item.description)}">
                            <input type="hidden" name="unit_price[]" class="unit-price" value="${parseFloat(item.unit_price).toFixed(2)}">
                            <div class="item-details">
                                <span class="item-name">${escapeHtml(item.description)}</span>
                                <span class="item-price">₱${parseFloat(item.unit_price).toFixed(2)}</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" class="quantity" value="${parseInt(item.quantity)}" min="1" required>
                        </td>
                        <td>
                            <button type="button" class="remove-row-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    itemsBody.appendChild(row);
                    addRowListeners(row);
                });

                openModal(editModal);
            } else {
                showEditModalAlert('Failed to load quotation details', 'error');
            }
        } catch (error) {
            console.error('Error loading quotation:', error);
            showEditModalAlert('Error loading quotation details', 'error');
        }
    }

    function showEditModalAlert(msg, type = 'error') {
        const alertEl = document.getElementById('editModalAlert');
        alertEl.style.display = 'flex';
        alertEl.className = `modal-alert alert-${type}`;
        alertEl.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i><span>${escapeHtml(msg)}</span>`;
        setTimeout(() => alertEl.style.display = 'none', 4000);
    }

    document.getElementById('editAddRow').addEventListener('click', () => {
        const itemsBody = document.getElementById('editItemsBody');
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
            <td>
                <input type="text" name="description[]" placeholder="Item description" required>
            </td>
            <td>
                <input type="number" name="quantity[]" placeholder="Qty" value="1" min="1" required>
            </td>
            <td>
                <input type="number" name="unit_price[]" placeholder="Price" step="0.01" min="0" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        itemsBody.appendChild(row);
        addRowListeners(row);
    });

    document.getElementById('submitEditForm').addEventListener('click', async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('id', currentEditId);
        formData.append('client_name', document.getElementById('editClientName').value);
        formData.append('contact_person', document.getElementById('editContactPerson').value);
        formData.append('email', document.getElementById('editEmail').value);
        formData.append('phone', document.getElementById('editPhone').value);
        formData.append('expires_at', document.getElementById('editExpiresAt').value);

        // Add items
        const descInputs = document.querySelectorAll('#editItemsBody input[name="description[]"]');
        const qtyInputs = document.querySelectorAll('#editItemsBody input[name="quantity[]"]');
        const priceInputs = document.querySelectorAll('#editItemsBody input[name="unit_price[]"]');

        descInputs.forEach((input, index) => {
            formData.append('description[]', input.value);
            formData.append('quantity[]', qtyInputs[index]?.value || 1);
            formData.append('unit_price[]', priceInputs[index]?.value || 0);
        });

        try {
            const response = await fetch('api/update_quotation_customer.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showEditModalAlert('Quotation updated successfully', 'success');
                setTimeout(() => {
                    closeModal(editModal);
                    location.reload();
                }, 1500);
            } else {
                showEditModalAlert(data.message || 'Failed to update quotation', 'error');
            }
        } catch (error) {
            console.error('Error updating quotation:', error);
            showEditModalAlert('Error updating quotation', 'error');
        }
    });

    // ── Delete Modal with API ────────────────────────────────────────────
    let deleteQuotationId = null;

    function confirmDelete(id, quoteNum) {
        deleteQuotationId = id;
        document.getElementById('deleteQuoteNum').textContent = quoteNum;
        openModal(deleteModal);
    }

    async function performDelete() {
        if (!deleteQuotationId) return;

        try {
            const response = await fetch('api/delete_quotation_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + deleteQuotationId
            });

            const data = await response.json();

            if (data.success) {
                closeModal(deleteModal);
                // Remove row from table
                const row = document.querySelector(`tr[data-status]`);
                if (row) {
                    const rowsToCheck = document.querySelectorAll('.table-row');
                    rowsToCheck.forEach(r => {
                        const buttons = r.querySelectorAll('button');
                        buttons.forEach(btn => {
                            if (btn.onclick && btn.onclick.toString().includes(deleteQuotationId)) {
                                r.style.display = 'none';
                            }
                        });
                    });
                }
                // Refresh page after a short delay
                setTimeout(() => location.reload(), 800);
            } else {
                alert('Failed to delete quotation: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error deleting quotation:', error);
            alert('Error deleting quotation');
        }
    }
</script>

<?php
if (isset($conn) && $conn instanceof mysqli) $conn->close();
include 'includes/footer.php';
?>