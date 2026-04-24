<?php
include '../includes/header.php';

?>

<body>
    <link rel="stylesheet" href="../../assets/css/admin-site/quotations.css">
    <div class="admin-wrapper">
        <?php
        $current_page = 'Quotation';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>
            <section class="content-body">
                <h1 class="page-title">Quotations</h1>

                <!-- QUOTATIONS HEADER WITH SEARCH AND FILTER -->
                <div class="quotations-header">
                    <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search by Quote ID, Customer, or Email..." id="quotationSearch">
                    </div>

                    <div class="filter-group">
                        <label for="statusFilter">Filter by Status:</label>
                        <select id="statusFilter">
                            <option value="all">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="accepted">Accepted</option>
                            <option value="expired">Expired</option>
                            <option value="converted">Converted</option>
                        </select>
                    </div>

                    <div class="quotations-info">
                        <button class="btn-primary" onclick="window.location.href='quotation-create.php'">
                            <i class="fa-solid fa-plus"></i> New Quotation
                        </button>
                        <button class="btn-secondary" onclick="quotationManager.refresh()">
                            <i class="fa-solid fa-refresh"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- QUOTATIONS TABLE -->
                <div class="quotations-table-wrapper">
                    <table class="quotations-table">
                        <thead>
                            <tr>
                                <th>Quote ID</th>
                                <th>Customer</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="quotationsTableBody">
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 40px;">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- PAGINATION -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            <span id="pageInfo">Page 1 of 1</span>
                        </div>
                        <div class="pagination-controls">
                            <button class="pagination-btn" id="prevPage">
                                <i class="fa-solid fa-chevron-left"></i> Previous
                            </button>
                            <button class="pagination-btn" id="nextPage">
                                Next <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- QUOTATION DETAIL MODAL (Optional for quick view) -->
    <div id="QuotationModal" class="QuotationModal" style="display:none;">
        <div class="modal-content large">
            <div class="modal-header">
                <h2>Quotation Details</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" id="quotationModalBody">
                <!-- AJAX will populate quotation details here -->
            </div>
            <div class="modal-footer">
                <button class="confirm-btn" id="closeQuotationBtn">Close</button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="../../assets/css/admin-site/quotations.css">
    <!-- Load JavaScript -->


</body>
<script src="../../assets/js/admin-site-functions/admin_data_fetch/fetch_quotations.js"></script>

</html>