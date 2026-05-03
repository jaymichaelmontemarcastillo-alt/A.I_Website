<?php
// admin/dashboard.php
include 'auth_check.php';
include '../includes/header.php';

$current_page = 'Dashboard';
?>

<body>

    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">
                <h1 class="page-title">Dashboard</h1>
                <p class="subtitle">Overview of your business performance</p>

                <!-- ══════════════════════════════════════
                 KPI CARDS
            ══════════════════════════════════════ -->
                <div class="stats-grid" id="kpiGrid">
                    <!-- Revenue -->
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Revenue</p>
                            <h3 id="kpi-revenue-alltime">—</h3>
                            <span id="kpi-revenue-change" class="positive">Loading…</span>
                            <small id="kpi-revenue-today" style="display:block;margin-top:3px;color:var(--text-secondary);font-size:.78rem;"></small>
                        </div>
                        <div class="stat-icon sales"><i class="fa-solid fa-peso-sign"></i></div>
                    </div>

                    <!-- Orders -->
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Orders</p>
                            <h3 id="kpi-orders-total">—</h3>
                            <span id="kpi-orders-pending" class="warning-text">Loading…</span>
                        </div>
                        <div class="stat-icon orders"><i class="fa-solid fa-cart-shopping"></i></div>
                    </div>

                    <!-- Customers -->
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Customers</p>
                            <h3 id="kpi-customers-total">—</h3>
                            <span id="kpi-customers-new" class="positive">Loading…</span>
                        </div>
                        <div class="stat-icon customers"><i class="fa-solid fa-users"></i></div>
                    </div>

                    <!-- Low Stock -->
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Low Stock</p>
                            <h3 id="kpi-stock-low">—</h3>
                            <span id="kpi-stock-out" style="font-size:.8rem;color:var(--danger)">Loading…</span>
                        </div>
                        <div class="stat-icon stock"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                 CHARTS ROW 1 — Daily & Monthly Sales
            ══════════════════════════════════════ -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h3>Daily Sales <span class="chart-sub">(Last 7 Days)</span></h3>
                        <div class="chart-wrapper">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3>Monthly Sales <span class="chart-sub">(Last 12 Months)</span></h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                 CHARTS ROW 2 — Top Products & Category
            ══════════════════════════════════════ -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h3>Top Selling Products</h3>
                        <div class="chart-wrapper">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card chart-card--donut">
                        <h3>Sales by Category</h3>
                        <div class="chart-wrapper donut-wrapper">
                            <canvas id="categoryChart"></canvas>
                            <div class="donut-legend" id="categoryLegend"></div>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                 RECENT ORDERS TABLE
            ══════════════════════════════════════ -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>Recent Orders</h3>
                        <a href="orders.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentOrdersBody">
                                <tr>
                                    <td colspan="6" class="loading-row">Loading orders…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                 BOTTOM GRID: Insights + Payments + Customers
            ══════════════════════════════════════ -->
                <div class="insights-grid">

                    <!-- Product Insights -->
                    <div class="insight-card">
                        <div class="insight-header">
                            <h3><i class="fa-solid fa-box"></i> Product Insights</h3>
                        </div>
                        <div class="insight-tabs">
                            <button class="tab-btn active" data-tab="best">Best Sellers</button>
                            <button class="tab-btn" data-tab="low">Low Stock</button>
                            <button class="tab-btn" data-tab="recent">Recent</button>
                        </div>
                        <div id="tab-best" class="tab-content active">
                            <ul class="insight-list" id="bestSellingList">
                                <li class="loading-item">Loading…</li>
                            </ul>
                        </div>
                        <div id="tab-low" class="tab-content">
                            <ul class="insight-list" id="lowStockList">
                                <li class="loading-item">Loading…</li>
                            </ul>
                        </div>
                        <div id="tab-recent" class="tab-content">
                            <ul class="insight-list" id="recentProductsList">
                                <li class="loading-item">Loading…</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Payment Overview -->
                    <div class="insight-card">
                        <div class="insight-header">
                            <h3><i class="fa-solid fa-credit-card"></i> Payments</h3>
                        </div>
                        <div class="payment-stats" id="paymentStats">
                            <div class="pay-stat">
                                <span class="pay-label">Total Received</span>
                                <span class="pay-value" id="pay-received">—</span>
                            </div>
                            <div class="pay-stat">
                                <span class="pay-label">Pending</span>
                                <span class="pay-value warning-text" id="pay-pending">—</span>
                            </div>
                            <div class="pay-stat">
                                <span class="pay-label">Failed</span>
                                <span class="pay-value danger-text" id="pay-failed">—</span>
                            </div>
                        </div>
                        <div class="pay-methods" id="payMethods">
                            <!-- populated by JS -->
                        </div>
                    </div>

                    <!-- Customer Insights -->
                    <div class="insight-card">
                        <div class="insight-header">
                            <h3><i class="fa-solid fa-user-group"></i> Top Customers</h3>
                        </div>
                        <ul class="insight-list" id="topCustomersList">
                            <li class="loading-item">Loading…</li>
                        </ul>
                    </div>

                </div>

                <!-- ══════════════════════════════════════
                 QUOTATIONS & REQUESTS
            ══════════════════════════════════════ -->
                <div class="two-col-grid">
                    <!-- Quotations Summary -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Quotations</h3>
                            <a href="Quotation.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="quote-stats" id="quoteStats">
                            <!-- populated by JS -->
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Quote #</th>
                                        <th>Client</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recentQuotationsBody">
                                    <tr>
                                        <td colspan="4" class="loading-row">Loading…</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Requests -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Customer Requests</h3>
                            <a href="requests.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="quote-stats" id="requestStats">
                            <!-- populated by JS -->
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recentRequestsBody">
                                    <tr>
                                        <td colspan="4" class="loading-row">Loading…</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                 ALERTS & ACTIVITY LOGS
            ══════════════════════════════════════ -->
                <div class="two-col-grid">
                    <!-- Alerts Panel -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3><i class="fa-solid fa-bell" style="color:var(--warning)"></i> Alerts</h3>
                        </div>
                        <div class="alerts-list" id="alertsList">
                            <p class="loading-row">Loading alerts…</p>
                        </div>
                    </div>

                    <!-- Activity Logs -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3><i class="fa-solid fa-clock-rotate-left" style="color:var(--info)"></i> Recent Activity</h3>
                        </div>
                        <ul class="activity-log-list" id="activityLogList">
                            <li class="loading-item">Loading activity…</li>
                        </ul>
                    </div>
                </div>

            </section><!-- end content-body -->
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Dashboard JS -->
    <script src="../../assets/js/admin-site-functions/admin_dashboard.js"></script>

</body>

</html>