<link rel="stylesheet" href="../../assets/css/admin-site/admin_customers.css">
<?php
include 'auth_check.php';
include '../includes/header.php';
?>

<div class="admin-wrapper">
    <?php
    $current_page = 'Customers';
    include 'admin_sidebar.php';
    ?>

    <main class="main-content">
        <?php include 'admin_page_header.php'; ?>

        <section class="content-body">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Customer Management</h1>
                    <p class="page-subtitle">Customer insights based on quotation history</p>
                </div>
                <button class="btn-export" id="btnExport">
                    <i class="fa-solid fa-file-arrow-down"></i> Export CSV
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card" data-filter="all">
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-body">
                        <h3 id="statTotal">—</h3>
                        <p>Total Customers</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="has_quotations">
                    <div class="stat-icon accent-green"><i class="fa-solid fa-file-invoice"></i></div>
                    <div class="stat-body">
                        <h3 id="statHasQuotations">—</h3>
                        <p>Has Quotations</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="high_value">
                    <div class="stat-icon accent-amber"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="stat-body">
                        <h3 id="statHighValue">—</h3>
                        <p>High Value (>₱50k)</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="delivered">
                    <div class="stat-icon accent-purple"><i class="fa-solid fa-truck"></i></div>
                    <div class="stat-body">
                        <h3 id="statDelivered">—</h3>
                        <p>Received Delivery</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="cancelled">
                    <div class="stat-icon accent-red"><i class="fa-solid fa-ban"></i></div>
                    <div class="stat-body">
                        <h3 id="statCancelled">—</h3>
                        <p>Cancelled Quotations</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="pending">
                    <div class="stat-icon accent-blue"><i class="fa-solid fa-clock"></i></div>
                    <div class="stat-body">
                        <h3 id="statPending">—</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-header">
                    <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, email, phone, or address..." autocomplete="off">
                    </div>
                    <div class="filter-pills" id="filterPills">
                        <button class="pill active" data-filter="all">All Customers</button>
                        <button class="pill" data-filter="has_quotations">Has Quotations</button>
                        <button class="pill" data-filter="high_value">High Value (>₱50k)</button>
                        <button class="pill" data-filter="delivered">Received Delivery</button>
                        <button class="pill" data-filter="cancelled">Cancelled</button>
                        <button class="pill" data-filter="pending">Pending</button>
                    </div>
                </div>

                <div class="table-scroll">
                    <table class="customer-table" id="customerTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Orders</th>
                                <th>Address</th>
                                <th>Last Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customerTbody">
                            <tr class="loading-row">
                                <td colspan="7">
                                    <div class="loading">
                                        <div class="spinner"></div>
                                        <span>Loading customers...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-bar" id="paginationBar"></div>
            </div>
        </section>
    </main>
</div>

<!-- Customer Detail Modal -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <div class="modal-content" id="modalContent" style="display:none">
            <button class="modal-close" id="modalClose" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="modal-loading" id="modalLoading">
                <div class="spinner"></div>
                <p>Loading customer profile...</p>
            </div>

            <!-- Profile Header -->
            <div class="modal-profile-header">
                <div class="modal-avatar" id="modalAvatar"></div>
                <div class="modal-profile-info">
                    <h2 id="modalCustomerName"></h2>
                    <p><i class="fa-solid fa-envelope"></i> <span id="modalCustomerEmail"></span></p>
                    <p><i class="fa-solid fa-phone"></i> <span id="modalCustomerPhone"></span></p>
                    <p><i class="fa-solid fa-location-dot"></i> <span id="modalCustomerAddress">—</span></p>
                </div>
            </div>

            <!-- Summary Tiles -->
            <div class="modal-summary-tiles">
                <div class="m-tile">
                    <span id="mTileQuotes">0</span>
                    <p>Total Quotations</p>
                </div>
                <div class="m-tile">
                    <span id="mTileTotal">₱0</span>
                    <p>Total Amount</p>
                </div>
                <div class="m-tile">
                    <span id="mTileConverted">0</span>
                    <p>Converted to Order</p>
                </div>
                <div class="m-tile">
                    <span id="mTileLastQuote">—</span>
                    <p>Last Quotation</p>
                </div>
            </div>

            <!-- Quotations Tab -->
            <div class="modal-tabs">
                <button class="m-tab active" data-tab="quotations">Quotation History</button>
            </div>

            <div class="tab-panel active" id="tabQuotations">
                <div class="history-list" id="quotesList"></div>
                <p class="empty-note" id="quotesEmpty" style="display:none">No quotations found for this customer.</p>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/admin-site-functions/admin_customer.js"></script>