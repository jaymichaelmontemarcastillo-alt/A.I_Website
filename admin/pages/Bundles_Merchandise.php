<?php
// admin/Bundles_Merchandise.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'auth_check.php';
include '../includes/header.php';

$current_page = 'Bundles_Merchandise';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundles & Merchandise</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/products.css">
    <link rel="stylesheet" href="../../assets/css/admin-site/bundles_merchandise.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php   include 'admin_sidebar.php'; ?>

        <main class="main-content">
            <?php include 'admin_page_header.php'; ?>

            <section class="content-body">
                <!-- Breadcrumb Navigation -->
                <div class="breadcrumb-nav">
                    <a href="Products.php" class="breadcrumb-link">
                        <i class="fa-solid fa-arrow-left"></i> Back to Products
                    </a>
                    <span class="breadcrumb-separator">|</span>
                    <span class="breadcrumb-current">Bundles & Merchandise</span>
                </div>

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div class="page-header-left">
                        <h1 class="page-title">Bundles & Merchandise</h1>
                        <span class="page-count" id="totalItems">0 items</span>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-secondary" id="refreshBtn" title="Refresh">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                        <button class="btn btn-primary" id="addBtn">
                            <i class="fa-solid fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <!-- TAB SYSTEM -->
                <div class="tab-system">
                    <button class="tab-btn active" data-type="bundle">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Bundles</span>
                        <span class="tab-badge" id="bundleCount">0</span>
                    </button>
                    <button class="tab-btn" data-type="merchandise">
                        <i class="fa-solid fa-shirt"></i>
                        <span>Merchandise</span>
                        <span class="tab-badge" id="merchCount">0</span>
                    </button>
                </div>

                <!-- FILTER BAR -->
                <div class="filter-bar">
                    <div class="filter-search">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" id="searchField" placeholder="Search items..." class="search-input" autocomplete="off">
                    </div>
                    <select id="sortFilter" class="filter-select">
                        <option value="sort_order">Sort by Order</option>
                        <option value="name_asc">Name (A-Z)</option>
                        <option value="name_desc">Name (Z-A)</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>

                <!-- GRID CONTAINER -->
                <div class="grid-container" id="gridContainer">
                    <div class="grid-loading">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Loading items...</span>
                    </div>
                </div>

                <!-- PAGINATION -->
                <div id="paginationWrap" class="pagination"></div>
            </section>
        </main>
    </div>

    
    <!-- ==========================================
MODAL: ADD / EDIT ITEM
========================================== -->
<div id="itemModal" class="modal-overlay">
    <div class="modal-box modal-box-lg">
        <div class="modal-header modal-header-sticky">
            <h2 id="modalTitle"><i class="fa-solid fa-box"></i> Add Item</h2>
            <button class="modal-close" id="modalCloseBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="itemForm" enctype="multipart/form-data" novalidate>
            <div class="bundle-merch-modal-body">
                <input type="hidden" id="editId" name="id">
                <input type="hidden" id="itemType" name="type" value="bundle">

                <div class="form-group">
                    <label for="itemName">Item Name <span class="required">*</span></label>
                    <input type="text" id="itemName" name="name" required minlength="2" maxlength="255" placeholder="Enter item name">
                </div>

                <div class="form-group">
                    <label for="itemDescription">Description</label>
                    <textarea id="itemDescription" name="description" rows="3" maxlength="2000" placeholder="Brief description..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="itemSortOrder">Sort Order</label>
                        <input type="number" id="itemSortOrder" name="sort_order" min="0" placeholder="0">
                        <span class="form-helper">Lower numbers appear first</span>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload-area" id="imageUploadArea">
                        <div class="image-preview" id="imagePreview">
                            <i class="fa-regular fa-image"></i>
                            <span>No image selected</span>
                        </div>
                        <div class="upload-actions">
                            <button type="button" class="btn btn-secondary btn-sm" id="imageUploadBtn">
                                <i class="fa-solid fa-upload"></i> Choose Image
                            </button>
                            <input type="file" id="itemImage" name="image" accept="image/*" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm" id="imageRemoveBtn" style="display:none;">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                            <p class="form-helper">Recommended: 800x800px, Max 5MB (JPG, PNG, WEBP)</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="existingImage" name="existing_image">
            </div>

            <div class="modal-footer modal-footer-sticky">
                <button type="button" class="btn btn-cancel" id="modalCancelBtn">Cancel</button>
                <button type="submit" class="btn btn-save" id="submitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save Item
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
        <div class="modal-header modal-header-sticky">
            <h2><i class="fa-solid fa-triangle-exclamation"></i> Delete Item</h2>
            <button class="modal-close" id="deleteCloseBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="bundle-merch-modal-body">
            <p id="deleteMsg">Are you sure you want to delete this item?</p>
            <p class="delete-warning"><i class="fa-solid fa-exclamation-circle"></i> This action cannot be undone.</p>
        </div>
        <div class="modal-footer modal-footer-sticky">
            <button type="button" class="btn btn-cancel" id="deleteCancelBtn">Cancel</button>
            <button type="button" class="btn btn-delete" id="deleteConfirmBtn">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>

    <script src="../../assets/js/admin-site-functions/admin_bundles_merchandise.js"></script>
</body>
</html>