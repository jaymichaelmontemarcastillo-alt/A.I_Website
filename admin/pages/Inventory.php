<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Inventory.php
        $current_page = 'Inventory';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">

                <h1 class="page-title">Inventory</h1>
                <p class="page-subtitle">Stock levels and alerts</p>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <p>Total Stock</p>
                            <h3>124</h3>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon yellow">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                        <div>
                            <p>Low Stock</p>
                            <h3>3</h3>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <p>Out of Stock</p>
                            <h3>1</h3>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="alerts-box">
                    <h3><i class="fa-solid fa-triangle-exclamation"></i> Stock Alerts</h3>

                    <div class="alert-row">
                        <span>Valentine's Special</span>
                        <span class="badge red">Out of Stock</span>
                    </div>

                    <div class="alert-row">
                        <span>Romantic Rose Hamper</span>
                        <span class="badge yellow">3 left</span>
                    </div>

                    <div class="alert-row">
                        <span>Custom Message Box</span>
                        <span class="badge yellow">2 left</span>
                    </div>

                    <div class="alert-row">
                        <span>Thank You Basket</span>
                        <span class="badge yellow">1 left</span>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h3>All Products</h3>
                        <span class="sort"><i class="fa-solid fa-arrow-up-wide-short"></i> Sort by name</span>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Valentine's Special</td>
                                <td>Romantic</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar" style="width:0%"></div>
                                    </div>
                                    <span>0</span>
                                </td>
                                <td><span class="badge red">Out of Stock</span></td>
                            </tr>

                            <tr>
                                <td>Thank You Basket</td>
                                <td>Other</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar low" style="width:10%"></div>
                                    </div>
                                    <span>1</span>
                                </td>
                                <td><span class="badge yellow">Low Stock</span></td>
                            </tr>

                            <tr>
                                <td>Custom Message Box</td>
                                <td>Other</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar low" style="width:20%"></div>
                                    </div>
                                    <span>2</span>
                                </td>
                                <td><span class="badge yellow">Low Stock</span></td>
                            </tr>

                            <tr>
                                <td>Romantic Rose Hamper</td>
                                <td>Romantic</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar low" style="width:30%"></div>
                                    </div>
                                    <span>3</span>
                                </td>
                                <td><span class="badge yellow">Low Stock</span></td>
                            </tr>

                            <tr>
                                <td>Premium Chocolate Set</td>
                                <td>Birthday</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar good" style="width:60%"></div>
                                    </div>
                                    <span>18</span>
                                </td>
                                <td><span class="badge green">In Stock</span></td>
                            </tr>

                            <tr>
                                <td>Birthday Gift Box Deluxe</td>
                                <td>Birthday</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar good" style="width:75%"></div>
                                    </div>
                                    <span>24</span>
                                </td>
                                <td><span class="badge green">In Stock</span></td>
                            </tr>

                            <tr>
                                <td>Christmas Joy Bundle</td>
                                <td>Holiday</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar good" style="width:85%"></div>
                                    </div>
                                    <span>31</span>
                                </td>
                                <td><span class="badge green">In Stock</span></td>
                            </tr>

                            <tr>
                                <td>Holiday Surprise Pack</td>
                                <td>Holiday</td>
                                <td>
                                    <div class="progress">
                                        <div class="bar good" style="width:95%"></div>
                                    </div>
                                    <span>45</span>
                                </td>
                                <td><span class="badge green">In Stock</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>
    </div>

</body>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>

</html>