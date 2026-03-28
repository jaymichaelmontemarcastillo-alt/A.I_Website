<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Payments.php
        $current_page = 'Payments';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <h1 class="page-title">Payments</h1>
                <div class="payments-header">
                    <div>
                        <p class="subtitle">Verify and manage payment submissions</p>
                    </div>
                </div>

                <div class="payments-list">

                    <!-- ITEM -->
                    <div class="payment-card">
                        <div class="payment-left">
                            <div class="img-box">
                                <i class="fa-regular fa-image"></i>
                            </div>

                            <div class="payment-info">
                                <div class="top-line">
                                    <strong>ORD-001</strong>
                                    <span class="badge verified">Verified</span>
                                </div>
                                <p>Alice Johnson</p>
                                <span class="price">$189.98</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-card">
                        <div class="payment-left">
                            <div class="img-box">
                                <i class="fa-regular fa-image"></i>
                            </div>

                            <div class="payment-info">
                                <div class="top-line">
                                    <strong>ORD-002</strong>
                                    <span class="badge pending">Pending</span>
                                </div>
                                <p>Bob Smith</p>
                                <span class="price">$129.99</span>
                            </div>
                        </div>

                        <div class="payment-actions">
                            <button class="approve-btn">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                            <button class="reject-btn">
                                <i class="fa-solid fa-xmark"></i> Reject
                            </button>
                        </div>
                    </div>

                    <div class="payment-card">
                        <div class="payment-left">
                            <div class="img-box">
                                <i class="fa-regular fa-image"></i>
                            </div>

                            <div class="payment-info">
                                <div class="top-line">
                                    <strong>ORD-003</strong>
                                    <span class="badge verified">Verified</span>
                                </div>
                                <p>Carol Davis</p>
                                <span class="price">$59.99</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-card">
                        <div class="payment-left">
                            <div class="img-box">
                                <i class="fa-regular fa-image"></i>
                            </div>

                            <div class="payment-info">
                                <div class="top-line">
                                    <strong>ORD-004</strong>
                                    <span class="badge verified">Verified</span>
                                </div>
                                <p>Dan Wilson</p>
                                <span class="price">$249.98</span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </main>
    </div>

</body>

</html>