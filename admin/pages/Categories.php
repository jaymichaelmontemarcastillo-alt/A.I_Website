<?php

include '../includes/header.php';
?>

<body>

    <div class="admin-wrapper">
        <?php
        // Categories.php
        $current_page = 'Categories';
        include 'admin_sidebar.php';
        ?>

        <main class="main-content">
            <?php
            include 'admin_page_header.php';
            ?>

            <section class="content-body">
                <h1 class="page-title">Categories</h1>
                <div class="category-header">
                    <div>
                        <p class="subtitle">Organize products by category</p>
                    </div>

                    <button class="add-btn" id="openModal">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>

                <div class="category-grid">
                    <div class="category-card">
                        <div class="icon-box"><i class="fa-solid fa-tag"></i></div>
                        <h3>Birthday</h3>
                        <p>Birthday celebration gifts and hampers</p>
                        <span>12 products</span>
                    </div>

                    <div class="category-card">
                        <div class="icon-box"><i class="fa-solid fa-tag"></i></div>
                        <h3>Holiday</h3>
                        <p>Seasonal and holiday gift packs</p>
                        <span>8 products</span>
                    </div>

                    <div class="category-card">
                        <div class="icon-box"><i class="fa-solid fa-tag"></i></div>
                        <h3>Romantic</h3>
                        <p>Romantic gifts for special occasions</p>
                        <span>15 products</span>
                    </div>

                    <div class="category-card">
                        <div class="icon-box"><i class="fa-solid fa-tag"></i></div>
                        <h3>Other</h3>
                        <p>Miscellaneous gift items</p>
                        <span>6 products</span>
                    </div>

                </div>

                <!-- MODAL -->
                <div class="modal" id="categoryModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Category</h3>
                            <span class="close-btn" id="closeModal">&times;</span>
                        </div>

                        <form>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" placeholder="Enter category name">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea placeholder="Enter description"></textarea>
                            </div>

                            <button type="submit" class="submit-btn">Save Category</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>
<script src="../../assets/js/admin-site-functions/admin_sidebar.js"></script>
<script src="../../assets/js/admin-site-functions/admin_category.js"></script>

</html>