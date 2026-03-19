<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Customers.php
        $current_page = 'Customers';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <header class="top-nav">
                <button id="toggle-btn" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-chevron-left toggle-arrow"></i>
                </button>
            </header>

            <section class="content-body">

                <!-- Header -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Customers</h1>
                        <p class="page-subtitle">Manage and monitor your customers</p>
                    </div>
                    <!--
                    <button class="btn-add">
                        <i class="fa-solid fa-user-plus"></i> Add Customer
                    </button> -->
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>1,248</h3>
                        <p>Total Customers</p>
                    </div>

                    <div class="stat-card">
                        <h3>320</h3>
                        <p>Active</p>
                    </div>

                    <div class="stat-card">
                        <h3>45</h3>
                        <p>New This Month</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">

                    <div class="table-header">
                        <input type="text" placeholder="Search customers..." class="search-input">
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td class="customer-cell">
                                    <div class="avatar">J</div>
                                    <span>Juan Dela Cruz</span>
                                </td>
                                <td>juan@email.com</td>
                                <td>12</td>
                                <td><span class="badge active">Active</span></td>
                                <td>Mar 12, 2026</td>
                                <td><i class="fa-solid fa-ellipsis"></i></td>
                            </tr>

                            <tr>
                                <td class="customer-cell">
                                    <div class="avatar">M</div>
                                    <span>Maria Santos</span>
                                </td>
                                <td>maria@email.com</td>
                                <td>5</td>
                                <td><span class="badge inactive">Inactive</span></td>
                                <td>Feb 20, 2026</td>
                                <td><i class="fa-solid fa-ellipsis"></i></td>
                            </tr>

                            <tr>
                                <td class="customer-cell">
                                    <div class="avatar">A</div>
                                    <span>Alex Reyes</span>
                                </td>
                                <td>alex@email.com</td>
                                <td>20</td>
                                <td><span class="badge active">Active</span></td>
                                <td>Jan 05, 2026</td>
                                <td><i class="fa-solid fa-ellipsis"></i></td>
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