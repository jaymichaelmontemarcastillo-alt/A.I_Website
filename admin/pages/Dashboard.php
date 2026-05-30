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
                     KPI CARDS — loaded immediately
                ══════════════════════════════════════ -->
                <div class="stats-grid" id="kpiGrid">
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Revenue</p>
                            <h3 id="kpi-revenue-alltime">
                                <span class="skeleton skeleton-text" style="width:120px"></span>
                            </h3>
                            <span id="kpi-revenue-change" class="positive">
                                <span class="skeleton skeleton-text" style="width:90px"></span>
                            </span>
                            <small id="kpi-revenue-today" style="display:block;margin-top:3px;color:var(--text-secondary);font-size:.78rem;"></small>
                        </div>
                        <div class="stat-icon sales"><i class="fa-solid fa-peso-sign"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Orders</p>
                            <h3 id="kpi-orders-total">
                                <span class="skeleton skeleton-text" style="width:80px"></span>
                            </h3>
                            <span id="kpi-orders-pending" class="warning-text">
                                <span class="skeleton skeleton-text" style="width:110px"></span>
                            </span>
                        </div>
                        <div class="stat-icon orders"><i class="fa-solid fa-cart-shopping"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Customers</p>
                            <h3 id="kpi-customers-total">
                                <span class="skeleton skeleton-text" style="width:80px"></span>
                            </h3>
                            <span id="kpi-customers-new" class="positive">
                                <span class="skeleton skeleton-text" style="width:100px"></span>
                            </span>
                        </div>
                        <div class="stat-icon customers"><i class="fa-solid fa-users"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Low Stock</p>
                            <h3 id="kpi-stock-low">
                                <span class="skeleton skeleton-text" style="width:60px"></span>
                            </h3>
                            <span id="kpi-stock-out" style="font-size:.8rem;color:var(--danger)">
                                <span class="skeleton skeleton-text" style="width:90px"></span>
                            </span>
                        </div>
                        <div class="stat-icon stock"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     CHARTS — Daily & Monthly Sales
                ══════════════════════════════════════ -->
                <div class="charts-grid" id="chartsRow1">
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
                     RECENT ORDERS TABLE
                ══════════════════════════════════════ -->
                <div class="table-card" id="recentOrdersSection">
                    <div class="table-card-header">
                        <h3>Recent Orders</h3>
                        <a href="orders.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
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
                                    <td colspan="6" class="loading-row">
                                        <span class="skeleton skeleton-text" style="width:200px;display:inline-block"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     PAYMENTS & ALERTS
                ══════════════════════════════════════ -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>Quotations</h3>
                        <a href="Quotation.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="quote-stats" id="quoteStats"></div>
                    <div class="table-responsive">
                        <table class="data-table">
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
                <div class="insights-grid" id="insightsGrid">
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
                                <span class="pay-label">Verified</span>
                                <span class="pay-value" id="pay-verified">—</span>
                            </div>
                            <div class="pay-stat">
                                <span class="pay-label">Paid</span>
                                <span class="pay-value" id="pay-paid">—</span>
                            </div>
                            <div class="pay-stat">
                                <span class="pay-label">Failed</span>
                                <span class="pay-value danger-text" id="pay-failed">—</span>
                            </div>
                        </div>
                        <div id="payMethods"></div>
                    </div>

                    <!-- Alerts -->
                    <div class="insight-card" id="alertsSection">
                        <div class="insight-header">
                            <h3><i class="fa-solid fa-bell"></i> Alerts</h3>
                        </div>
                        <div class="alerts-list" id="alertsList">
                            <p class="loading-row">Loading alerts...</p>
                        </div>
                    </div>



                    <!-- ══════════════════════════════════════
                     QUOTATIONS & REQUESTS
                ══════════════════════════════════════ -->

                    <!-- ══════════════════════════════════════
                     RECENT ACTIVITY LOGS
                ══════════════════════════════════════ -->

            </section><!-- end content-body -->
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Dashboard JS -->
    <script src="../../assets/js/admin-site-functions/admin_dashboard.js"></script>

</body>

</html>