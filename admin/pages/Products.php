<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../connect/config.php';
include '../includes/header.php';

$isAdminRole = isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin';
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
                        <h1 class="pg-title">  Products</h1>
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
                    <?php if ($isAdminRole): ?>
                        <button type="button" class="btn btn-delete" id="deleteAllBtn">
                            <i class="fa-solid fa-trash"></i> Delete All
                        </button>
                    <?php endif; ?>
                </div>

                <!-- TABLE -->
                <div class="table-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th class="col-name">Name</th>
                                <th class="col-type">Type</th>
                                <th class="col-num">Price</th>
                                <th class="col-num">Stock</th>
                                <th class="col-status">Status</th>
                                <th class="col-action">Actions</th>
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
    MODAL: DELETE ALL PRODUCTS
    ========================================== -->
    <?php if ($isAdminRole): ?>
    <div id="deleteAllModal" class="modal-overlay">
        <div class="modal-box modal-box-sm" style="border-radius: 16px; padding: 24px; font-family: 'Inter', sans-serif;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="width:44px; height:44px; border-radius:12px; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h2 style="margin:0; font-size:1.1rem; font-weight:700; color:#0f172a; font-family:'Inter', sans-serif;">Delete All Products</h2>
                    <span style="font-size:0.78rem; color:#ef4444; font-weight:600; text-transform:uppercase; letter-spacing:0.04em;">Irreversible Action</span>
                </div>
                <button class="modal-close" id="deleteAllCloseBtn" style="margin-left:auto;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <p id="deleteAllMsg" style="margin:0 0 18px 0; color:#64748b; font-size:0.875rem; line-height:1.5;">This will permanently erase every product from the database. This action cannot be undone.</p>
                <div class="form-group" style="margin-bottom:18px;">
                    <label for="deleteAllPassword" style="font-weight:600; font-size:0.85rem; color:#334155;">Admin Password <span class="req" style="color:#ef4444;">*</span></label>
                    <input type="password" id="deleteAllPassword" name="password" placeholder="Enter your admin password" autocomplete="current-password"
                        style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-family:'Inter', sans-serif; font-size:0.9rem; color:#1e293b; outline:none; transition:all 0.2s ease; background:#f8fafc;"
                        onfocus="this.style.borderColor='#ef4444'; this.style.backgroundColor='#fff'; this.style.boxShadow='0 0 0 3px rgba(239, 68, 68, 0.15)';"
                        onblur="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none';">
                </div>
                <div id="deleteAllFeedback" style="display:none; margin-bottom:16px; padding:10px 14px; border-radius:10px; font-size:0.85rem;"></div>
            </div>
            <div class="modal-foot" style="padding-top:12px; border-top:1px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-cancel" id="deleteAllCancelBtn" style="border-radius:10px; font-weight:600;">Cancel</button>
                <button type="button" class="btn btn-delete" id="deleteAllConfirmBtn" style="border-radius:10px; background:#ef4444; font-weight:600; box-shadow:0 4px 12px rgba(239, 68, 68, 0.25);">
                    <i class="fa-solid fa-trash"></i> Delete All Products
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

                    <!-- Supported formats -->
                    <div class="import-info">
                        <div style="display:flex; gap:10px; margin-bottom:14px;">
                            <div style="flex:1; padding:10px 12px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; display:flex; align-items:center; gap:8px;">
                                <i class="fa-solid fa-file-csv" style="color:#0ea5e9; font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-weight:600; font-size:0.85rem; color:#0369a1;">.CSV</div>
                                    <div style="font-size:0.75rem; color:#6b7280;">Comma-separated values</div>
                                </div>
                            </div>
                            <div style="flex:1; padding:10px 12px; background:#f0fdf4; border:1px solid #86efac; border-radius:8px; display:flex; align-items:center; gap:8px;">
                                <i class="fa-solid fa-file-excel" style="color:#16a34a; font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-weight:600; font-size:0.85rem; color:#15803d;">.XLSX</div>
                                    <div style="font-size:0.75rem; color:#6b7280;">Excel spreadsheet</div>
                                </div>
                            </div>
                        </div>
                        <div class="info-box">
                            <i class="fa-solid fa-info-circle"></i>
                            <div>
                                <p><strong>Instructions:</strong></p>
                                <ul>
                                    <li>Upload a CSV (.csv) or Excel (.xlsx) file</li>
                                    <li>Required columns: <strong>Product Type</strong> and <strong>Product Name</strong></li>
                                    <li>Existing products will be <strong>updated</strong> (matched by SKU or Name)</li>
                                    <li>New products will be <strong>created</strong></li>
                                    <li>Materials format: <code>Material Name (quantity unit); Material Name (quantity)</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="importFile">Select or Drop File <span class="req">*</span></label>
                        <div id="productDropzone" style="border:2px dashed #cbd5e1; border-radius:10px; padding:20px; text-align:center; background:#f8fafc; cursor:pointer; transition:all 0.2s ease; margin-bottom:8px;" onclick="document.getElementById('importFile').click();">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem; color:#3b82f6; margin-bottom:8px;"></i>
                            <div style="font-weight:600; font-size:0.9rem; color:#1e293b;">Click to browse or drag &amp; drop file here</div>
                            <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">Supports .csv and .xlsx up to 10MB</div>
                            <input type="file" id="importFile" name="import_file" accept=".csv,.xlsx" style="display:none;" required>
                        </div>
                        <!-- Live hint shown after the user picks a file -->
                        <p id="importFileHint" class="form-help" style="min-height:18px; margin-top:4px;"></p>
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

    <!-- ==========================================
    MODAL: IMPORT ERROR
    ========================================== -->
    <div id="importErrorModal" class="modal-overlay">
        <div class="modal-box modal-box-sm">
            <div class="modal-head">
                <h2 id="importErrorTitle"><i class="fa-solid fa-triangle-exclamation" style="color: var(--clr-danger);"></i> Import Problem</h2>
                <button class="modal-close" id="importErrorCloseBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p id="importErrorMessage" style="margin: 16px 0; font-size: 15px; color: var(--clr-text-secondary);">
                    Something went wrong during the import. Please try again.
                </p>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-cancel" id="importErrorCancelBtn">Close</button>
                <button type="button" class="btn btn-save" id="importErrorRetryBtn">
                    <i class="fa-solid fa-rotate-right"></i> Try Again
                </button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin-site-functions/admin_products.js?v=<?php echo time(); ?>"></script>
</body>

</html>