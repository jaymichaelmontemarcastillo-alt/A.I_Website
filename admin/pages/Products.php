<?php
session_start();
require_once '../../connect/config.php';
include '../includes/header.php';
?>

<link rel="stylesheet" href="../../assets/css/admin-site/products.css">

<body>
    <div class="admin-wrapper">
        <?php $current_page = 'Products';
        include 'admin_sidebar.php'; ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">

                <!-- PAGE HEADER -->
                <div class="pg-header">
                    <div class="pg-header-left">
                        <h1 class="pg-title"><i class="fa-solid fa-cube"></i> Products</h1>
                        <span class="pg-count" id="totalCount">Loading...</span>
                    </div>
                    <div class="pg-actions">
                        <button class="btn btn-secondary" id="importBtn">
                            <i class="fa-solid fa-file-import"></i> Import
                        </button>
                        <button class="btn btn-secondary" id="exportBtn">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <button class="btn btn-primary" id="addBtn">
                            <i class="fa-solid fa-plus"></i> Add Product
                        </button>
                    </div>
                </div>

                <!-- FILTER BAR -->
                <div class="filter-bar">
                    <div class="filter-search">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" id="searchField" placeholder="Search by name, SKU..." class="search-input" autocomplete="off">
                    </div>
                    <select id="typeFilter" class="filter-select">
                        <option value="">All Types</option>
                    </select>
                    <select id="stockFilter" class="filter-select">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>

                <!-- TABLE -->
                <div class="table-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr class="tbl-loading">
                                <td colspan="6">
                                    <div class="spinner">
                                        <i class="fa-solid fa-spinner fa-spin"></i> Loading products...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div id="paginationWrap" class="pagination"></div>

            </section>
        </main>
    </div>

    <!-- ==========================================
    MODAL: ADD / EDIT PRODUCT
    ========================================== -->
    <div id="productModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-head">
                <h2 id="modalTitle"><i class="fa-solid fa-box"></i> Add Product</h2>
                <button class="modal-close" id="modalCloseBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="productForm" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id">

                    <div class="form-row">
                        <div class="form-group col-2">
                            <label for="productName">Product Name <span class="req">*</span></label>
                            <input type="text" id="productName" name="name" required minlength="3" maxlength="255" placeholder="Enter product name">
                            <span class="form-error" id="nameErr"></span>
                        </div>
                        <div class="form-group col-1">
                            <label for="productSku">SKU</label>
                            <input type="text" id="productSku" name="sku" maxlength="100" placeholder="e.g. PRD-001">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productType">Product Type <span class="req">*</span></label>
                        <select id="productType" name="product_type_id" required>
                            <option value="">— Select type —</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-1">
                            <label for="productPrice">Price (₱) <span class="req">*</span></label>
                            <input type="number" id="productPrice" name="price" step="0.01" min="0" required placeholder="0.00">
                        </div>
                        <div class="form-group col-1">
                            <label for="productStock">Stock <span class="req">*</span></label>
                            <input type="number" id="productStock" name="stock" min="0" required placeholder="0">
                        </div>
                        <div class="form-group col-1">
                            <label for="productUnit">Unit</label>
                            <select id="productUnit" name="unit">
                                <option value="piece">Piece</option>
                                <option value="set">Set</option>
                                <option value="pack">Pack</option>
                                <option value="roll">Roll</option>
                                <option value="sheet">Sheet</option>
                                <option value="meter">Meter</option>
                                <option value="pair">Pair</option>
                                <option value="box">Box</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productMaterialType">Material Type</label>
                        <select id="productMaterialType" name="material_type">
                            <option value="assembled_product">Assembled Product</option>
                            <option value="raw_material">Raw Material</option>
                            <option value="print_service">Print Service</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productDesc">Description</label>
                        <textarea id="productDesc" name="description" rows="2" maxlength="2000" placeholder="Brief description..."></textarea>
                    </div>

                    <!-- Materials -->
                    <div class="materials-wrap">
                        <div class="materials-head">
                            <h3><i class="fa-solid fa-cubes"></i> Materials Used</h3>
                            <button type="button" class="btn btn-sm btn-outline" id="addMatBtn">
                                <i class="fa-solid fa-plus"></i> Add Material
                            </button>
                        </div>
                        <div id="matContainer"></div>
                        <p id="matEmpty" class="mat-empty">No materials linked. Click "Add Material" to link materials.</p>
                    </div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn btn-cancel" id="modalCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-save" id="submitBtn">
                        <i class="fa-solid fa-floppy-disk"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
    MODAL: DELETE CONFIRMATION
    ========================================== -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box modal-box-sm">
            <div class="modal-head">
                <h2><i class="fa-solid fa-triangle-exclamation" style="color: var(--clr-danger);"></i> Delete Product</h2>
                <button class="modal-close" id="deleteCloseBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p id="deleteMsg" style="margin: 16px 0; font-size: 15px; color: var(--clr-text-secondary);">Are you sure you want to delete this product?</p>
                <p style="margin: 8px 0; font-size: 13px; color: var(--clr-text-muted);"><i class="fa-solid fa-exclamation-circle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-cancel" id="deleteCancelBtn">Cancel</button>
                <button type="button" class="btn btn-delete" id="deleteConfirmBtn">
                    <i class="fa-solid fa-trash"></i> Delete Product
                </button>
            </div>
        </div>
    </div>

    <!-- ==========================================
    MODAL: IMPORT
    ========================================== -->
    <div id="importModal" class="modal-overlay">
        <div class="modal-box modal-box-md">
            <div class="modal-head">
                <h2><i class="fa-solid fa-file-import"></i> Import Products</h2>
                <button class="modal-close" id="importCloseBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="import-info">
                        <div class="info-box">
                            <i class="fa-solid fa-info-circle"></i>
                            <div>
                                <p><strong>Instructions:</strong></p>
                                <ul>
                                    <li>Upload an Excel file (.xlsx or .xls)</li>
                                    <li>Required columns: <strong>Product Type</strong> and <strong>Product Name</strong></li>
                                    <li>Existing products will be <strong>updated</strong> (matched by SKU or Name)</li>
                                    <li>New products will be <strong>created</strong></li>
                                    <li>Materials format: <code>Material Name (quantity unit); Material Name (quantity)</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="excelFile">Select Excel File <span class="req">*</span></label>
                        <input type="file" id="excelFile" name="excel_file" accept=".xlsx,.xls" required>
                        <p class="form-help">Supported formats: .xlsx, .xls</p>
                    </div>

                    <div id="importProgress" style="display:none;" class="progress-wrap">
                        <div class="progress-track">
                            <div class="progress-fill" id="progressFill" style="width:0%"></div>
                        </div>
                        <p id="progressStatus" class="progress-status">Processing...</p>
                    </div>

                    <div id="importResult" style="display:none;"></div>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn btn-cancel" id="importCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-save" id="importSubmitBtn">
                        <i class="fa-solid fa-upload"></i> Import Products
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_products.js"></script>
</body>

</html>