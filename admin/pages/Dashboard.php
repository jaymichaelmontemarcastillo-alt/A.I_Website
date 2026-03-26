<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Dashboard.php
        $current_page = 'Dashboard';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>
            <section class="content-body">
                <h1 class="page-title">Dashboard</h1>
                <p class="subtitle">Overview of your business performance</p>

                <!-- STATS -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Sales</p>
                            <h3>$128,750</h3>
                            <span class="positive">+12.5% from last month</span>
                        </div>
                        <div class="stat-icon sales"><i class="fa-solid fa-dollar-sign"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Total Orders</p>
                            <h3>342</h3>
                            <span class="positive">+8.2%</span>
                        </div>
                        <div class="stat-icon orders"><i class="fa-solid fa-cart-shopping"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Customers</p>
                            <h3>189</h3>
                            <span class="positive">+4.1%</span>
                        </div>
                        <div class="stat-icon customers"><i class="fa-solid fa-users"></i></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-text">
                            <p>Low Stock</p>
                            <h3>7</h3>
                        </div>
                        <div class="stat-icon stock"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                </div>

                <!-- CHARTS -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h3>Daily Sales</h3>
                        <div class="chart-wrapper">
                            <canvas id="dailyChart" style="padding-top: 20px;"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h3>Monthly Sales</h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyChart" style="padding-top: 20px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="table-card">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ORD-001</td>
                                <td>Alice Johnson</td>
                                <td>2026-03-17</td>
                                <td>$189.98</td>
                                <td><span class="badge processing">Processing</span></td>
                            </tr>
                            <tr>
                                <td>ORD-002</td>
                                <td>Bob Smith</td>
                                <td>2026-03-16</td>
                                <td>$129.99</td>
                                <td><span class="badge pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>ORD-003</td>
                                <td>Carol Davis</td>
                                <td>2026-03-15</td>
                                <td>$59.99</td>
                                <td><span class="badge shipped">Shipped</span></td>
                            </tr>
                            <tr>
                                <td>ORD-004</td>
                                <td>Dan Wilson</td>
                                <td>2026-03-15</td>
                                <td>$249.98</td>
                                <td><span class="badge delivered">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>ORD-005</td>
                                <td>Eve Brown</td>
                                <td>2026-03-14</td>
                                <td>$39.99</td>
                                <td><span class="badge cancelled">Cancelled</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

</body>
<script src="../../assets/js/admin-site-functions/admin_dashboard.js"></script>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>

</html>