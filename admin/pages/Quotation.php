<?php
include 'auth_check.php';
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

                <!-- DASHBOARD CARDS -->
                <div class="dashboard-cards">
                    <div class="card card-total" onclick="quotationManager.filterByStatus('all')">
                        <div class="card-icon">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div class="card-info">
                            <h3 id="totalQuotes">0</h3>
                            <p>Total Quotes</p>
                        </div>
                    </div>
                    <div class="card card-pending" onclick="quotationManager.filterByStatus('draft')">
                        <div class="card-icon">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="card-info">
                            <h3 id="pendingQuotes">0</h3>
                            <p>Pending / Draft</p>
                        </div>
                    </div>
                    <div class="card card-approved" onclick="quotationManager.filterByStatus('accepted')">
                        <div class="card-icon">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div class="card-info">
                            <h3 id="approvedQuotes">0</h3>
                            <p>Approved</p>
                        </div>
                    </div>
                    <div class="card card-declined" onclick="quotationManager.filterByStatus('expired')">
                        <div class="card-icon">
                            <i class="fa-solid fa-times-circle"></i>
                        </div>
                        <div class="card-info">
                            <h3 id="declinedQuotes">0</h3>
                            <p>Declined / Expired</p>
                        </div>
                    </div>
                    <div class="card card-delivered" onclick="quotationManager.filterByStatus('converted')">
                        <div class="card-icon">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div class="card-info">
                            <h3 id="deliveredQuotes">0</h3>
                            <p>Delivered</p>
                        </div>
                    </div>
                </div>

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
                            <option value="draft">Draft / Pending</option>
                            <option value="sent">Sent</option>
                            <option value="accepted">Accepted / Approved</option>
                            <option value="expired">Expired / Declined</option>
                            <option value="converted">Converted / Delivered</option>
                        </select>
                    </div>

                    <div class="quotations-info">
                        <button class="btn-primary" onclick="quotationManager.openCreateModal()">
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

    <!-- QUOTATION DETAIL MODAL (View Only) -->
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

    <!-- QUOTATION EDIT/CREATE MODAL -->
    <div id="QuotationEditModal" class="qe-overlay" style="display:none;">
        <div class="qe-modal">
            <!-- HEADER -->
            <div class="qe-header">
                <div class="qe-header-left">
                    <span class="qe-icon"><i class="fa-solid fa-file-pen"></i></span>
                    <div>
                        <h2 class="qe-title" id="qeModalTitle">Edit Quotation</h2>
                        <p class="qe-subtitle" id="qeQuoteNumber">—</p>
                    </div>
                </div>
                <button class="qe-close" onclick="quotationManager.closeEditModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- SCROLLABLE BODY -->
            <div class="qe-body">
                <!-- SECTION 1: CLIENT INFO -->
                <div class="qe-section">
                    <div class="qe-section-label">
                        <i class="fa-solid fa-building"></i> Client Information
                    </div>
                    <div class="qe-grid-2">
                        <div class="qe-field">
                            <label class="qe-label">Client Name <span class="req">*</span></label>
                            <input type="text" id="qeClientName" class="qe-input" placeholder="e.g. Acme Corporation">
                        </div>
                        <div class="qe-field">
                            <label class="qe-label">Contact Person</label>
                            <input type="text" id="qeContactPerson" class="qe-input" placeholder="e.g. Juan dela Cruz">
                        </div>
                        <div class="qe-field">
                            <label class="qe-label">Email</label>
                            <input type="email" id="qeEmail" class="qe-input" placeholder="email@example.com">
                        </div>
                        <div class="qe-field">
                            <label class="qe-label">Phone</label>
                            <input type="text" id="qePhone" class="qe-input" placeholder="+63 900 000 0000">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: QUOTATION ITEMS -->
                <div class="qe-section">
                    <div class="qe-section-label">
                        <i class="fa-solid fa-list-check"></i> Quotation Items
                    </div>
                    <div class="qe-items-wrapper">
                        <table class="qe-items-table">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Qty</th>
                                    <th>Description</th>
                                    <th style="width:140px;">Unit Price</th>
                                    <th style="width:130px;">Total</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="qeItemsBody">
                                <!-- rows injected by JS -->
                            </tbody>
                        </table>
                    </div>
                    <button class="qe-add-row" onclick="quotationManager.addItemRow()">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                </div>

                <!-- SECTION 3: SUMMARY -->
                <div class="qe-section">
                    <div class="qe-section-label">
                        <i class="fa-solid fa-calculator"></i> Summary
                    </div>
                    <div class="qe-summary-wrapper">
                        <div class="qe-summary-row">
                            <span class="qe-summary-label">Subtotal</span>
                            <span class="qe-summary-value" id="qeSubtotal">Php 0.00</span>
                        </div>
                        <div class="qe-summary-row">
                            <span class="qe-summary-label">Tax (%)</span>
                            <input type="number" id="qeTax" class="qe-input qe-summary-input" value="0" min="0" max="100" step="0.01"
                                oninput="quotationManager.recalcTotals()">
                        </div>
                        <div class="qe-summary-row">
                            <span class="qe-summary-label">Discount (Php)</span>
                            <input type="number" id="qeDiscount" class="qe-input qe-summary-input" value="0" min="0" step="0.01"
                                oninput="quotationManager.recalcTotals()">
                        </div>
                        <div class="qe-summary-row qe-total-row">
                            <span class="qe-summary-label">Grand Total</span>
                            <span class="qe-summary-value qe-grand" id="qeGrandTotal">Php 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: NOTES -->
                <div class="qe-section">
                    <div class="qe-section-label">
                        <i class="fa-solid fa-note-sticky"></i> Notes
                    </div>
                    <textarea id="qeNotes" class="qe-textarea" rows="4"
                        placeholder="Additional notes, terms, or conditions..."></textarea>
                </div>
            </div>

            <!-- FOOTER ACTIONS -->
            <div class="qe-footer">
                <button class="qe-btn-cancel" onclick="quotationManager.closeEditModal()">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </button>
                <div class="qe-footer-right">
                    <button class="qe-btn-delivery" id="qeDeliveryReceiptBtn" onclick="quotationManager.generateDeliveryReceiptFromModal()" style="display:none;">
                        <i class="fa-solid fa-truck"></i> Generate Delivery Receipt
                    </button>
                    <button class="qe-btn-save-pdf" id="qeSavePdfBtn" onclick="quotationManager.saveAndGeneratePDF()">
                        <i class="fa-solid fa-file-pdf"></i> Save & Generate PDF
                    </button>
                    <button class="qe-btn-save" id="qeSaveBtn" onclick="quotationManager.saveQuotation()">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </div>

            <style>
                .qe-btn-delivery {
                    background: #ff9800;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 6px;
                    font-size: 14px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .qe-btn-delivery:hover {
                    background: #f57c00;
                }
            </style>
        </div>
    </div>

    <!-- APPROVAL CONFIRMATION MODAL (For generating Delivery Receipt) -->
    <div id="ApprovalModal" class="approval-overlay" style="display:none;">
        <div class="approval-modal">
            <div class="approval-header">
                <i class="fa-solid fa-check-circle"></i>
                <h2>Quotation Approved</h2>
            </div>
            <div class="approval-body">
                <p>This quotation has been approved. Would you like to proceed with generating the Delivery Receipt?</p>
                <div class="approval-info">
                    <strong>Quote #: <span id="approvalQuoteNumber"></span></strong>
                </div>
            </div>
            <div class="approval-footer">
                <button class="approval-btn-cancel" onclick="quotationManager.closeApprovalModal()">
                    <i class="fa-solid fa-times"></i> Cancel
                </button>
                <button class="approval-btn-generate" onclick="quotationManager.generateDeliveryReceipt()">
                    <i class="fa-solid fa-file-pdf"></i> Generate Delivery Receipt
                </button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="../../assets/css/admin-site/quotations.css">
    <style>
        /* Dashboard Cards Styles */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .card-total .card-icon {
            background: #e3f2fd;
            color: #1976d2;
        }

        .card-pending .card-icon {
            background: #fff3e0;
            color: #ff9800;
        }

        .card-approved .card-icon {
            background: #e8f5e9;
            color: #4caf50;
        }

        .card-declined .card-icon {
            background: #ffebee;
            color: #f44336;
        }

        .card-delivered .card-icon {
            background: #e0f2f1;
            color: #009688;
        }

        .card-info h3 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        .card-info p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        /* Approval Modal Styles */
        .approval-overlay {
            position: fixed;
            inset: 0;
            z-index: 100000;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .approval-modal {
            background: white;
            border-radius: 16px;
            width: 450px;
            max-width: 90%;
            overflow: hidden;
            animation: slideInUp 0.3s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .approval-header {
            background: #4caf50;
            padding: 25px;
            text-align: center;
            color: white;
        }

        .approval-header i {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .approval-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .approval-body {
            padding: 25px;
            text-align: center;
        }

        .approval-body p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        .approval-info {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 8px;
            font-size: 18px;
        }

        .approval-footer {
            padding: 20px 25px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            border-top: 1px solid #eee;
        }

        .approval-btn-cancel,
        .approval-btn-generate {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .approval-btn-cancel {
            background: #f5f5f5;
            color: #666;
        }

        .approval-btn-cancel:hover {
            background: #e0e0e0;
        }

        .approval-btn-generate {
            background: #ff9800;
            color: white;
        }

        .approval-btn-generate:hover {
            background: #f57c00;
        }
    </style>

</body>
<script src="../../assets/js/admin-site-functions/admin_data_fetch/fetch_quotations.js"></script>

</html>