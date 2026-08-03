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
            <!-- Page Header - Tighter spacing -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Customer Management</h1>
                    <p class="page-subtitle">Manage and track customer activity</p>
                </div>
                <button class="btn-export" id="btnExport">
                    <i class="fa-solid fa-file-arrow-down"></i> Export CSV
                </button>
            </div>

            <!-- Stats Cards - Tighter spacing -->
            <div class="stats-grid" id="statsGrid">
                <div class="customer-stat-card" data-filter="all">
                    <div class="stat-icon accent-blue"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-body">
                        <h3 id="statTotal">—</h3>
                        <p>Total Customers</p>
                    </div>
                </div>
                <div class="customer-stat-card" data-filter="delivered">
                    <div class="stat-icon accent-green"><i class="fa-solid fa-truck"></i></div>
                    <div class="stat-body">
                        <h3 id="statDelivered">—</h3>
                        <p>Received Delivery</p>
                    </div>
                </div>
                <div class="customer-stat-card" data-filter="cancelled">
                    <div class="stat-icon accent-red"><i class="fa-solid fa-ban"></i></div>
                    <div class="stat-body">
                        <h3 id="statCancelled">—</h3>
                        <p>Cancelled Quotations</p>
                    </div>
                </div>
                <div class="customer-stat-card" data-filter="pending">
                    <div class="stat-icon accent-amber"><i class="fa-solid fa-clock"></i></div>
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
                        <button class="search-clear" id="searchClear" aria-label="Clear search">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="filter-pills" id="filterPills">
                        <button class="pill active" data-filter="all">All Customers</button>
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
                                <th style="text-align:center;">Orders</th>
                                <th>Address</th>
                                <th>Last Order</th>
                                <th style="text-align:center;">Actions</th>
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

<!-- Customer Order Modal - Two Column -->
<div class="customer-order-modal-overlay" id="customerOrderModal">
    <div class="customer-order-modal-box">
        <button class="customer-order-modal-close" id="modalClose" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Loading State -->
        <div id="modalLoading" style="display:block;">
            <div class="customer-order-modal-loading">
                <div class="spinner"></div>
                <p>Loading customer profile...</p>
            </div>
        </div>

        <!-- Modal Body - Two Columns -->
        <div id="modalBody" style="display:none;">
            <div class="customer-order-modal-body">
                <!-- LEFT COLUMN - Quotations List (Scrollable) -->
                <div class="customer-order-modal-left">
                    <div class="customer-order-modal-tabs">
                        <button class="customer-order-modal-tab active">Quotation History</button>
                    </div>
                    <div id="quotesContainer">
                        <div class="customer-order-modal-history" id="quotesList"></div>
                        <p class="customer-order-modal-empty" id="quotesEmpty" style="display:none">No quotations found for this customer.</p>
                    </div>
                </div>

                <!-- RIGHT COLUMN - Customer Info (Fixed) -->
                <div class="customer-order-modal-right">
                    <!-- Profile Header -->
                    <div class="customer-order-modal-profile">
                        <div class="customer-order-modal-avatar" id="modalAvatar">?</div>
                        <div class="customer-order-modal-info">
                            <h2 id="modalCustomerName">—</h2>
                            <p><i class="fa-solid fa-envelope"></i> <span id="modalCustomerEmail">—</span></p>
                            <p><i class="fa-solid fa-phone"></i> <span id="modalCustomerPhone">—</span></p>
                            <p><i class="fa-solid fa-location-dot"></i> <span id="modalCustomerAddress">—</span></p>
                        </div>
                    </div>

                    <!-- Summary Tiles -->
                    <div class="customer-order-modal-summary">
                        <div class="customer-order-modal-tile">
                            <span id="mTileQuotes">0</span>
                            <p>Quotations</p>
                        </div>
                        <div class="customer-order-modal-tile">
                            <span id="mTileTotal">₱0</span>
                            <p>Total Amount</p>
                        </div>
                        <div class="customer-order-modal-tile">
                            <span id="mTileConverted">0</span>
                            <p>Converted</p>
                        </div>
                        <div class="customer-order-modal-tile">
                            <span id="mTileLastQuote">—</span>
                            <p>Last Quote</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/admin-site-functions/admin_customer.js"></script>