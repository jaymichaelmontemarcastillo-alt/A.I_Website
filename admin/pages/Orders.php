<?php
include 'auth_check.php';
include '../includes/header.php';
?>

<body>
    <link rel="stylesheet" href="../../assets/css/admin-site/orders_styles.css">
    <div class="admin-wrapper">
        <?php
        $current_page = 'Orders';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">
                <!-- PAGE HEADER -->
                <div class="page-header-section">
                    <div>
                        <h1 class="page-title">Orders & Payments</h1>
                        <p class="page-subtitle">Manage customer orders and payment verification</p>
                    </div>
                </div>

                <!-- TABS -->
                <div class="tab-navigation">
                    <button class="tab-btn active" onclick="showTab('ordersTab', this)">
                        <i class="fa-solid fa-list"></i> Orders
                    </button>
                    <button class="tab-btn" onclick="showTab('paymentsTab', this)">
                        <i class="fa-solid fa-credit-card"></i> Pending Payments
                    </button>
                </div>

                <!-- ===================== ORDERS TAB ===================== -->
                <div id="ordersTab" class="tab-content active">
                    <div class="orders-section">
                        <!-- FILTERS AND SEARCH -->
                        <div class="filters-section">
                            <div class="search-wrapper">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input
                                    type="text"
                                    placeholder="Search by Order ID or Customer name..."
                                    id="orderSearch"
                                    class="search-input">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Order Status:</label>
                                <select id="statusFilter" class="filter-select">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="packed">Packed</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Payment Status:</label>
                                <select id="paymentFilter" class="filter-select">
                                    <option value="">All Payment Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Payment Method:</label>
                                <select id="methodFilter" class="filter-select">
                                    <option value="">All Methods</option>
                                    <option value="gcash">GCash</option>
                                    <option value="cash">Cash on Delivery</option>
                                </select>
                            </div>
                        </div>

                        <!-- ORDERS COUNT -->
                        <div class="orders-info">
                            <div class="info-stat">
                                <span class="stat-value" id="totalOrdersCount">0</span>
                                <span class="stat-label">Total Orders</span>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-wrapper">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody">
                                    <tr class="loading-row">
                                        <td colspan="8" style="text-align: center; padding: 40px;">
                                            <i class="fa-solid fa-spinner" style="animation: spin 1s linear infinite; font-size: 24px;"></i>
                                            <p style="margin-top: 10px; color: var(--text-secondary);">Loading orders...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ===================== PAYMENTS TAB ===================== -->
                <div id="paymentsTab" class="tab-content">
                    <div class="payments-section">
                        <div class="payments-header">
                            <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Pending GCash Payments</h2>
                            <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 14px;">Verify and approve customer payment proofs</p>
                        </div>

                        <div class="payments-list" id="paymentsList">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>No pending payments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- ===================== ORDER MODAL TEMPLATE ===================== -->
    <div id="OrderItemModal" class="modal-overlay">
        <div class="modal-container">
            <!-- MODAL HEADER -->
            <div class="modal-header">
                <h2 id="modalOrderId" style="margin: 0; font-size: 20px; font-weight: 700;">Order Details</h2>
                <button class="modal-close" onclick="closeOrderModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- MODAL BODY -->
            <div class="modal-body">
                <div class="modal-grid">
                    <!-- LEFT COLUMN -->
                    <div class="modal-left">
                        <!-- ORDER INFORMATION -->
                        <div class="modal-card">
                            <h3 class="card-title">Order Information</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Order ID</label>
                                    <p id="modalOrderNumber" class="info-value"></p>
                                </div>
                                <div class="info-item">
                                    <label>Customer Name</label>
                                    <p id="modalCustomerName" class="info-value"></p>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <p id="modalCustomerEmail" class="info-value"></p>
                                </div>
                                <div class="info-item">
                                    <label>Phone</label>
                                    <p id="modalCustomerPhone" class="info-value"></p>
                                </div>
                                <div class="info-item">
                                    <label>Order Date</label>
                                    <p id="modalOrderDate" class="info-value"></p>
                                </div>
                            </div>
                        </div>

                        <!-- ORDER ITEMS -->
                        <div class="modal-card">
                            <h3 class="card-title">Order Items</h3>
                            <div class="items-table-wrapper">
                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalItemsBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="items-total">
                                <strong>Total Amount:</strong>
                                <span id="modalTotalAmount" class="total-price">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="modal-right">
                        <!-- PAYMENT INFORMATION -->
                        <div class="modal-card">
                            <h3 class="card-title">Payment Details</h3>
                            <div class="payment-info">
                                <div class="payment-row">
                                    <span class="payment-label">Method:</span>
                                    <span id="modalPaymentMethod" class="payment-value"></span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Status:</span>
                                    <span id="modalPaymentStatus" class="payment-value"></span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Order Status:</span>
                                    <span id="modalOrderStatus" class="payment-value"></span>
                                </div>
                                <div class="payment-row" id="refRow" style="display: none;">
                                    <span class="payment-label">Reference #:</span>
                                    <span id="modalReference" class="payment-value"></span>
                                </div>
                            </div>
                        </div>

                        <!-- PROOF OF PAYMENT -->
                        <div class="modal-card" id="proofCard" style="display: none;">
                            <h3 class="card-title">Payment Proof</h3>
                            <div id="proofContent"></div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="modal-card action-card" id="actionCard" style="display: none;">
                            <h3 class="card-title">Actions</h3>
                            <div class="button-group">
                                <button class="btn-approve" onclick="approvePayment()">
                                    <i class="fa-solid fa-check"></i> Approve Payment
                                </button>
                                <button class="btn-reject" onclick="rejectPayment()">
                                    <i class="fa-solid fa-times"></i> Reject Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeOrderModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- TOAST CONTAINER -->
    <div id="toast-container"></div>


    <!-- SCRIPTS -->
    <script src="../../assets/js/admin-site-functions/admin_data_fetch/orders.js"></script>
</body>

</html>