<link rel="stylesheet" href="../../assets/css/admin-site/admin_customers.css">
<?php
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

            <!-- ── Page Header ──────────────────────────────────────────── -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Customers</h1>
                    <p class="page-subtitle">Unified CRM — orders &amp; quotations</p>
                </div>
                <!--   <button class="btn-export" id="btnExport">
                    <i class="fa-solid fa-file-arrow-down"></i> Export CSV
                </button>-->
            </div>

            <!-- ── Summary Cards ─────────────────────────────────────────── -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card" data-filter="all">
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-body">
                        <h3 id="statTotal">—</h3>
                        <p>Total Customers</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="orders">
                    <div class="stat-icon accent-green"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="stat-body">
                        <h3 id="statActive">—</h3>
                        <p>Has Orders</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="quotations">
                    <div class="stat-icon accent-amber"><i class="fa-solid fa-file-invoice"></i></div>
                    <div class="stat-body">
                        <h3 id="statQuoteOnly">—</h3>
                        <p>Quotation Only</p>
                    </div>
                </div>
                <div class="stat-card" data-filter="highvalue">
                    <div class="stat-icon accent-purple"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <div class="stat-body">
                        <h3 id="statRepeat">—</h3>
                        <p>Repeat Customers</p>
                    </div>
                </div>
            </div>

            <!-- ── Table Container ──────────────────────────────────────── -->
            <div class="table-container">

                <div class="table-header">
                    <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput"
                            placeholder="Search name, email, phone…"
                            class="search-input" autocomplete="off">
                    </div>

                    <div class="filter-pills" id="filterPills">
                        <button class="pill active" data-filter="all">All</button>
                        <button class="pill" data-filter="orders">Has Orders</button>
                        <button class="pill" data-filter="quotations">Has Quotations</button>
                        <button class="pill" data-filter="active">Active (30d)</button>
                        <button class="pill" data-filter="highvalue">High Value</button>
                    </div>
                </div>

                <div class="table-scroll">
                    <table class="customer-table" id="customerTable">
                        <!-- colgroup gives table-layout: fixed its column widths -->
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Orders</th>
                                <th>Quotations</th>
                                <th>Total Spent</th>
                                <th>Last Activity</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody id="customerTbody">
                            <tr class="loading-row">
                                <td colspan="9">
                                    <div class="loading">
                                        <div class="spinner"></div>
                                        <span>Loading customers…</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class=" pagination-bar" id="paginationBar">
                </div>
            </div>

        </section>
    </main>
</div>

<!-- ── Customer Detail Modal ─────────────────────────────────────────── -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">

        <div class="modal-content" id="modalContent" style="display:none">
            <button class="modal-close" id="modalClose" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="modal-loading" id="modalLoading">
                <div class="spinner"></div>
                <p>Loading profile…</p>
            </div>

            <!-- Profile Header -->
            <div class="modal-profile-header">
                <div class="modal-avatar" id="modalAvatar"></div>
                <div class="modal-profile-info">
                    <h2 id="modalCustomerName"></h2>
                    <p id="modalCustomerEmail"></p>
                    <p id="modalCustomerPhone"></p>
                    <span class="badge" id="modalCustomerType"></span>
                </div>
            </div>

            <!-- Summary Tiles -->
            <div class="modal-summary-tiles">
                <div class="m-tile">
                    <span id="mTileOrders">0</span>
                    <p>Orders</p>
                </div>
                <div class="m-tile">
                    <span id="mTileQuotes">0</span>
                    <p>Quotations</p>
                </div>
                <div class="m-tile">
                    <span id="mTileSpent">₱0</span>
                    <p>Total Spent</p>
                </div>
                <div class="m-tile">
                    <span id="mTileActivity">—</span>
                    <p>Last Activity</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="modal-tabs">
                <button class="m-tab active" data-tab="orders">Order History</button>
                <button class="m-tab" data-tab="quotations">Quotation History</button>
            </div>

            <!-- Orders Tab -->
            <div class="tab-panel active" id="tabOrders">
                <div class="history-list" id="ordersList"></div>
                <p class="empty-note" id="ordersEmpty" style="display:none">No orders found.</p>
            </div>

            <!-- Quotations Tab -->
            <div class="tab-panel" id="tabQuotations">
                <div class="history-list" id="quotesList"></div>
                <p class="empty-note" id="quotesEmpty" style="display:none">No quotations found.</p>
            </div>

        </div><!-- /modal-content -->
    </div><!-- /modal-box -->
</div><!-- /modal-overlay -->

<!-- ── External JavaScript ───────────────────────────────────────────── -->
<script src="../../assets/js/admin-site-functions/admin_customer.js" defer></script>