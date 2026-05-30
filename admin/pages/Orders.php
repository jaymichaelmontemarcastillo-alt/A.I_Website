<?php
include 'auth_check.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders & Payments - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/orders_styles.css">
</head>

<body>
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
                        <h1 class="page-title">Orders & Quotations</h1>
                        <p class="page-subtitle">Manage customer orders and track quotation status</p>
                    </div>
                </div>

                <!-- FILTERS AND SEARCH -->
                <div class="filters-section">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="text"
                            placeholder="Search by Customer name or Quote ID..."
                            id="orderSearch"
                            class="search-input">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Order Status</label>
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
                        <label class="filter-label">Payment Status</label>
                        <select id="paymentFilter" class="filter-select">
                            <option value="">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Payment Method</label>
                        <select id="methodFilter" class="filter-select">
                            <option value="">All Methods</option>
                            <option value="gcash">GCash</option>
                            <option value="cash">Cash on Delivery</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                </div>

                <!-- ORDERS TABLE -->
                <div class="table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Quotation ID</th>
                                <th>Customer Name</th>
                                <th>Quote Number</th>
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
                                <td colspan="9" style="text-align: center; padding: 40px;">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                    <p style="margin-top: 10px; color: var(--text-secondary);">Loading orders...</p>
                                </td>
                    </table>
                    </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- ===================== ORDER DETAIL MODAL ===================== -->
    <div id="OrderItemModal" class="modal-overlay">
        <div class="modal-container modal-large">
            <div class="modal-header">
                <h2 id="modalOrderId">Order Details</h2>
                <button class="modal-close" onclick="closeOrderModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-grid">
                    <!-- LEFT COLUMN - Customer & Quotation Info -->
                    <div class="modal-left">
                        <!-- CUSTOMER INFORMATION -->
                        <div class="modal-card">
                            <h3 class="card-title">Customer Information</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Client Name</label>
                                    <p id="modalClientName" class="info-value">-</p>
                                </div>
                                <div class="info-item">
                                    <label>Contact Person</label>
                                    <p id="modalContactPerson" class="info-value">-</p>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <p id="modalCustomerEmail" class="info-value">-</p>
                                </div>
                                <div class="info-item">
                                    <label>Phone</label>
                                    <p id="modalCustomerPhone" class="info-value">-</p>
                                </div>
                                <div class="info-item full-width">
                                    <label>Address</label>
                                    <p id="modalAddress" class="info-value">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- QUOTATION INFORMATION -->
                        <div class="modal-card">
                            <h3 class="card-title">Quotation Information</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Quote Number</label>
                                    <p id="modalQuoteNumber" class="info-value">-</p>
                                </div>
                                <div class="info-item">
                                    <label>Date</label>
                                    <p id="modalQuoteDate" class="info-value">-</p>
                                </div>
                                <div class="info-item">
                                    <label>Status</label>
                                    <p id="modalQuoteStatus" class="info-value">-</p>
                                </div>
                                <div class="info-item full-width" id="modalNotesRow" style="display: none;">
                                    <label>Notes</label>
                                    <p id="modalNotes" class="info-value">-</p>
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
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalItemsBody">
                                        <tr>
                                            <td colspan="4" style="text-align: center;">Loading items...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN - Summary & Payment -->
                    <div class="modal-right">
                        <!-- SUMMARY -->
                        <div class="modal-card">
                            <h3 class="card-title">Summary</h3>
                            <div class="summary-info">
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span id="modalSubtotal">₱0.00</span>
                                </div>
                                <div class="summary-row">
                                    <span>Tax:</span>
                                    <span id="modalTax">₱0.00</span>
                                </div>
                                <div class="summary-row">
                                    <span>Discount:</span>
                                    <span id="modalDiscount">₱0.00</span>
                                </div>
                                <div class="summary-row grand-total">
                                    <span>Grand Total:</span>
                                    <span id="modalGrandTotal">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- PAYMENT INFORMATION -->
                        <div class="modal-card">
                            <h3 class="card-title">Payment Details</h3>
                            <div class="payment-info">
                                <div class="payment-row">
                                    <span class="payment-label">Method:</span>
                                    <span id="modalPaymentMethod" class="payment-value">-</span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Status:</span>
                                    <span id="modalPaymentStatus" class="payment-value">-</span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Order Status:</span>
                                    <span id="modalOrderStatus" class="payment-value">-</span>
                                </div>
                                <div class="payment-row" id="refRow" style="display: none;">
                                    <span class="payment-label">Reference #:</span>
                                    <span id="modalReference" class="payment-value">-</span>
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