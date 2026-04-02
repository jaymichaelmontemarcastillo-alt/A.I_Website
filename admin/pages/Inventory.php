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
                            <h3 id="totalStockValue">-</h3>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon yellow">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                        <div>
                            <p>Low Stock</p>
                            <h3 id="lowStockValue">-</h3>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <p>Out of Stock</p>
                            <h3 id="outOfStockValue">-</h3>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="alerts-box">
                    <h3><i class="fa-solid fa-triangle-exclamation"></i> Stock Alerts</h3>
                    <div id="alertsContainer">
                        <p style="text-align: center; color: #999;">Loading alerts...</p>
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

                        <tbody id="productsTableBody">
                            <tr>
                                <td colspan="4" style="text-align: center; color: #999; padding: 20px;">Loading products...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>
    </div>

    <script>
        // Fetch inventory data and populate the page
        document.addEventListener('DOMContentLoaded', function() {
            loadInventoryData();
        });

        function loadInventoryData() {
            fetch('../../api/admin_site/fetch_inventory_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateStats(data.stats);
                        populateAlerts(data.alerts);
                        populateProductsTable(data.products);
                    } else {
                        console.error('Error:', data.message);
                        showError();
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showError();
                });
        }

        function populateStats(stats) {
            document.getElementById('totalStockValue').textContent = stats.totalStock;
            document.getElementById('lowStockValue').textContent = stats.lowStockCount;
            document.getElementById('outOfStockValue').textContent = stats.outOfStockCount;
        }

        function populateAlerts(alerts) {
            const alertsContainer = document.getElementById('alertsContainer');
            let alertsHTML = '';

            // Add out of stock products
            if (alerts.outOfStock && alerts.outOfStock.length > 0) {
                alerts.outOfStock.forEach(product => {
                    alertsHTML += `
                        <div class="alert-row">
                            <span>${product.name}</span>
                            <span class="badge red">Out of Stock</span>
                        </div>
                    `;
                });
            }

            // Add low stock products
            if (alerts.lowStock && alerts.lowStock.length > 0) {
                alerts.lowStock.forEach(product => {
                    alertsHTML += `
                        <div class="alert-row">
                            <span>${product.name}</span>
                            <span class="badge yellow">${product.stock} left</span>
                        </div>
                    `;
                });
            }

            // If no alerts, show message
            if (alertsHTML === '') {
                alertsHTML = '<p style="text-align: center; color: #999; padding: 15px;">All products are in stock!</p>';
            }

            alertsContainer.innerHTML = alertsHTML;
        }

        function populateProductsTable(products) {
            const tableBody = document.getElementById('productsTableBody');
            let tableHTML = '';

            // Calculate max stock for progress bar width reference
            const maxStock = Math.max(...products.map(p => p.stock), 1);

            products.forEach(product => {
                const stock = parseInt(product.stock);
                let badgeClass = 'green';
                let badgeText = 'In Stock';
                let barClass = 'good';

                if (stock === 0) {
                    badgeClass = 'red';
                    badgeText = 'Out of Stock';
                    barClass = '';
                } else if (stock < 5) {
                    badgeClass = 'yellow';
                    badgeText = 'Low Stock';
                    barClass = 'low';
                }

                const progressWidth = maxStock > 0 ? (stock / maxStock) * 100 : 0;

                tableHTML += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${product.category}</td>
                        <td>
                            <div class="progress">
                                <div class="bar ${barClass}" style="width:${progressWidth}%"></div>
                            </div>
                            <span>${stock}</span>
                        </td>
                        <td><span class="badge ${badgeClass}">${badgeText}</span></td>
                    </tr>
                `;
            });

            tableBody.innerHTML = tableHTML;
        }

        function showError() {
            document.getElementById('totalStockValue').textContent = 'Error';
            document.getElementById('lowStockValue').textContent = 'Error';
            document.getElementById('outOfStockValue').textContent = 'Error';

            const alertsContainer = document.getElementById('alertsContainer');
            alertsContainer.innerHTML = '<p style="text-align: center; color: red; padding: 15px;">Failed to load alerts</p>';

            const tableBody = document.getElementById('productsTableBody');
            tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: red; padding: 20px;">Failed to load products</td></tr>';
        }
    </script>

</body>


</html>