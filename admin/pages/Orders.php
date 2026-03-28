<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Orders.php
        $current_page = 'Orders';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <h1 class="page-title">Orders</h1>
                <div class="orders-header">
                    <div>
                        <p class="subtitle">5 total orders</p>
                    </div>
                </div>

                <!-- SEARCH -->
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search by ID or customer...">
                </div>

                <!-- TABLE -->
                <div class="orders-table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>ORD-001</td>
                                <td>Alice Johnson</td>
                                <td>2026-03-17</td>
                                <td class="price">$189.98</td>
                                <td><span class="badge verified">Verified</span></td>
                                <td>
                                    <select class="status-dropdown">
                                        <option>Processing</option>
                                        <option>Shipped</option>
                                        <option>Delivered</option>
                                    </select>
                                </td>
                                <td><i class="fa-regular fa-eye action-icon"></i></td>
                            </tr>

                            <tr>
                                <td>ORD-002</td>
                                <td>Bob Smith</td>
                                <td>2026-03-16</td>
                                <td class="price">$129.99</td>
                                <td><span class="badge pending">Pending</span></td>
                                <td>
                                    <select class="status-dropdown">
                                        <option>Pending Payment</option>
                                        <option>Processing</option>
                                    </select>
                                </td>
                                <td><i class="fa-regular fa-eye action-icon"></i></td>
                            </tr>

                            <tr>
                                <td>ORD-003</td>
                                <td>Carol Davis</td>
                                <td>2026-03-15</td>
                                <td class="price">$59.99</td>
                                <td><span class="badge verified">Verified</span></td>
                                <td>
                                    <select class="status-dropdown">
                                        <option>Shipped</option>
                                        <option>Delivered</option>
                                    </select>
                                </td>
                                <td><i class="fa-regular fa-eye action-icon"></i></td>
                            </tr>

                            <tr>
                                <td>ORD-004</td>
                                <td>Dan Wilson</td>
                                <td>2026-03-15</td>
                                <td class="price">$249.98</td>
                                <td><span class="badge verified">Verified</span></td>
                                <td>
                                    <select class="status-dropdown">
                                        <option>Delivered</option>
                                    </select>
                                </td>
                                <td><i class="fa-regular fa-eye action-icon"></i></td>
                            </tr>

                            <tr>
                                <td>ORD-005</td>
                                <td>Eve Brown</td>
                                <td>2026-03-14</td>
                                <td class="price">$39.99</td>
                                <td><span class="badge rejected">Rejected</span></td>
                                <td>
                                    <select class="status-dropdown">
                                        <option>Cancelled</option>
                                    </select>
                                </td>
                                <td><i class="fa-regular fa-eye action-icon"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

</body>


</html>