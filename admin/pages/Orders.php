<?php
include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        $current_page = 'Orders';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">
                <h1 class="page-title">Orders & Payments</h1>

                <!-- TABS -->
                <div class="tab-switch">
                    <button class="tab-btn active" onclick="showTab('ordersTab', this)">Orders</button>
                    <button class="tab-btn" onclick="showTab('paymentsTab', this)">Pending Payments</button>
                </div>

                <!-- ===================== -->
                <!-- ORDERS TAB -->
                <!-- ===================== -->
                <div id="ordersTab" class="tab-content active">

                    <div class="orders-header">
                        <p class="subtitle">0 total orders</p>
                    </div>

                    <!-- SEARCH -->
                    <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search by ID or customer..." id="orderSearch">
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
                            <tbody id="ordersTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- PAYMENTS TAB -->
                <!-- ===================== -->
                <div id="paymentsTab" class="tab-content">

                    <div class="payments-header">
                        <p class="subtitle">Pending GCash Payments</p>
                    </div>

                    <div class="payments-list" id="paymentsList">
                        <!-- Payments will load here -->
                    </div>

                </div>

            </section>
        </main>
    </div>

    <!-- ===================== -->
    <!-- ORDER MODAL -->
    <!-- ===================== -->
    <div id="OrderItemModal" class="OrderItemModal" style="display:none;">
        <div class="modal-content large">
            <div class="modal-header">
                <h2>Order Details</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" id="orderModalBody">
                <!-- ORDER DETAILS LOAD HERE -->
            </div>
            <div class="modal-footer">
                <button class="confirm-btn" id="closeOrderBtn">Close</button>
            </div>
        </div>
    </div>

    <div id="toast-container"></div>

    <!-- ===================== -->
    <!-- TAB SWITCHING -->
    <!-- ===================== -->
    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');

            if (tabId === 'paymentsTab') {
                loadPendingPayments();
            }
        }

        // ================= LOAD PENDING PAYMENTS TAB =================
        function loadPendingPayments() {
            const container = document.getElementById("paymentsList");
            container.innerHTML = '<p style="padding:20px;text-align:center;color:#999;">Loading...</p>';

            fetch("../../api/admin_site/order_processes/fetch_orders.php")
                .then(res => res.json())
                .then(response => {
                    if (response.status === "success") {
                        // Only GCash orders with pending status
                        const pending = response.data.filter(
                            o => o.payment_status === 'pending' && o.payment_method === 'gcash'
                        );
                        displayPendingPayments(pending);
                    }
                })
                .catch(() => {
                    container.innerHTML = '<p style="padding:20px;text-align:center;color:red;">Failed to load payments.</p>';
                });
        }

        function displayPendingPayments(payments) {
            const container = document.getElementById("paymentsList");

            if (payments.length === 0) {
                container.innerHTML = '<p style="padding:20px;text-align:center;color:#999;">No pending GCash payments</p>';
                return;
            }

            container.innerHTML = "";

            payments.forEach(p => {
                // Fetch full order details to get proof image
                fetch(`../../api/admin_site/order_processes/fetch_order_details.php?order_id=${p.order_number}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) return;
                        const o = data.order;

                        const proofHTML = o.resolved_proof ?
                            `<div style="margin-top:10px;">
                                <p style="margin:0 0 5px;font-size:13px;color:#555;"><strong>Proof of Payment:</strong></p>
                                <a href="${o.resolved_proof}" target="_blank">
                                  <img src="${o.resolved_proof}" alt="Proof" 
                                    style="max-width:200px;max-height:150px;border:1px solid #ddd;border-radius:4px;display:block;"
                                    onerror="this.parentElement.innerHTML='<span style=\\'color:#999;font-size:12px;\\'>Image unavailable</span>'"
                                  >
                                </a>
                               </div>` :
                            `<p style="margin-top:8px;color:#FF9800;font-size:13px;">⚠️ No proof uploaded yet</p>`;

                        const refHTML = o.resolved_reference ?
                            `<p style="margin:4px 0;font-size:13px;"><strong>Reference #:</strong> ${o.resolved_reference}</p>` :
                            '';

                        const canApprove = !!o.resolved_proof;

                        const card = document.createElement("div");
                        card.className = "payment-card";
                        card.style.cssText = "border:1px solid #ddd;padding:15px;margin-bottom:12px;border-radius:6px;display:flex;justify-content:space-between;align-items:flex-start;gap:15px;";
                        card.innerHTML = `
                            <div style="flex:1;">
                                <div style="margin-bottom:8px;">
                                    <strong style="font-size:16px;">${o.order_number}</strong>
                                    <span class="badge pending" style="background:#FF9800;color:white;padding:4px 8px;border-radius:4px;margin-left:10px;font-size:12px;">Pending</span>
                                </div>
                                <p style="margin:4px 0;"><strong>Customer:</strong> ${o.customer_name}</p>
                                <p style="margin:4px 0;"><strong>Method:</strong> GCASH</p>
                                <p style="margin:4px 0;"><strong>Amount:</strong> ₱${parseFloat(o.total_amount).toFixed(2)}</p>
                                ${refHTML}
                                ${proofHTML}
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <button 
                                    class="approve-btn" 
                                    onclick="approvePayment('${o.order_number}')" 
                                    style="padding:8px 15px;background:${canApprove ? '#4CAF50' : '#bdbdbd'};color:white;border:none;border-radius:4px;cursor:${canApprove ? 'pointer' : 'not-allowed'};margin-bottom:5px;display:block;width:100%;"
                                    ${canApprove ? '' : 'disabled title="Waiting for proof upload"'}
                                >Approve</button>
                                <button 
                                    class="reject-btn" 
                                    onclick="rejectPayment('${o.order_number}')" 
                                    style="padding:8px 15px;background:#f44336;color:white;border:none;border-radius:4px;cursor:pointer;display:block;width:100%;"
                                >Reject</button>
                            </div>
                        `;
                        container.appendChild(card);
                    });
            });
        }
    </script>

    <script src="../../assets/js/admin-site-functions/admin_data_fetch/orders.js"></script>

    <!-- ===================== -->
    <!-- STYLES -->
    <!-- ===================== -->
    <style>
        .tab-switch {
            margin-bottom: 15px;
            display: flex;
            gap: 5px;
        }

        .tab-btn {
            padding: 10px 20px;
            border: none;
            background: #eee;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .tab-btn.active {
            background: #333;
            color: #fff;
        }

        .tab-btn:hover {
            background: #ddd;
        }

        .tab-btn.active:hover {
            background: #333;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .orders-table th {
            background: #f5f5f5;
            font-weight: 600;
        }

        .orders-table tbody tr:hover {
            background: #f9f9f9;
        }

        .status-dropdown {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-icon {
            cursor: pointer;
            color: #333;
            font-size: 18px;
        }

        .action-icon:hover {
            color: #0066cc;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.verified {
            background: #4CAF50;
            color: white;
        }

        .badge.pending {
            background: #FF9800;
            color: white;
        }

        .badge.rejected {
            background: #f44336;
            color: white;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .search-bar input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
        }

        .orders-table-wrapper {
            overflow-x: auto;
        }

        /* ───────────────── MODAL CONTENT ───────────────── */
        .modal-content.large {
            width: 95%;
            max-width: 900px;
            /* 🔥 wider for 2 columns */
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        /* ───────────────── BODY GRID ───────────────── */
        .modal-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            /* LEFT bigger */
            gap: 20px;
            padding: 20px;
        }

        /* ───────────────── LEFT SIDE ───────────────── */
        .modal-left {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ───────────────── RIGHT SIDE ───────────────── */
        .modal-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: sticky;
            top: 70px;
            /* below header */
            height: fit-content;
        }

        /* ───────────────── CARD STYLE (reuse) ───────────────── */
        .modal-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 16px;
            background: #fafafa;
        }

        /* ───────────────── HEADER / FOOTER IMPROVE ───────────────── */
        .modal-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
        }

        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid #eee;
            position: sticky;
            bottom: 0;
            background: #fff;
        }

        /* ───────────────── SCROLL AREA ───────────────── */
        .modal-body {
            overflow-y: auto;
        }

        /* Custom scrollbar */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        /* ───────────────── RESPONSIVE ───────────────── */
        @media (max-width: 768px) {
            .modal-grid {
                grid-template-columns: 1fr;
                /* stack */
            }

            .modal-right {
                position: static;
            }
        }

        .confirm-btn {
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .confirm-btn:hover {
            background: #555;
        }

        .approve-btn {
            padding: 8px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .approve-btn:hover:not(:disabled) {
            background: #45a049;
        }

        .reject-btn {
            padding: 8px 15px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .reject-btn:hover {
            background: #da190b;
        }

        /* Toasts */
        #toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast {
            padding: 15px 20px;
            background: #333;
            color: white;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.3s;
            min-width: 200px;
        }

        .toast.show {
            opacity: 1;
        }

        .toast.success {
            background: #4CAF50;
        }

        .toast.error {
            background: #f44336;
        }
    </style>

</body>

</html>