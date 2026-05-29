// ============================================================
// ADMIN PRODUCTS JS - Complete CRUD with search and filters
// ============================================================

let currentPage = 1;
let totalPages = 1;
let currentDeleteId = null;
let currentDeleteName = "";

// API Endpoints
const API = {
  getProducts: "../../api/admin_site/products/get_products.php",
  addProduct: "../../api/admin_site/products/add_product.php",
  updateProduct: "../../api/admin_site/products/update_product.php",
  deleteProduct: "../../api/admin_site/products/delete_product.php",
};

// Load products with filters
async function loadProducts() {
  const search = document.getElementById("searchInput")?.value || "";
  const categoryId = document.getElementById("categoryFilter")?.value || "";
  const productTypeId = document.getElementById("typeFilter")?.value || "";
  const stockStatus = document.getElementById("stockStatusFilter")?.value || "";

  const params = new URLSearchParams({
    page: currentPage,
    limit: 12,
    search: search,
    category_id: categoryId,
    product_type_id: productTypeId,
    stock_status: stockStatus,
  });

  const grid = document.getElementById("productsGrid");
  if (grid) {
    grid.innerHTML =
      '<div class="loading-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading products...</p></div>';
  }

  try {
    const response = await fetch(`${API.getProducts}?${params}`);
    const data = await response.json();

    if (data.success) {
      renderProducts(data.data);
      updatePagination(data.pagination);

      const resultCount = document.getElementById("resultCount");
      if (resultCount) {
        resultCount.textContent = `${data.pagination.total} product${data.pagination.total !== 1 ? "s" : ""}`;
      }
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error loading products:", error);
    if (grid) {
      grid.innerHTML = `<div class="error-state"><i class="fa-solid fa-circle-exclamation"></i><p>Error: ${error.message}</p><button onclick="loadProducts()" style="margin-top:10px;padding:8px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;">Retry</button></div>`;
    }
  }
}

// Render products grid
function renderProducts(products) {
  const grid = document.getElementById("productsGrid");
  if (!grid) return;

  if (!products || products.length === 0) {
    grid.innerHTML =
      '<div class="empty-state"><i class="fa-regular fa-box-open"></i><h3>No Products Found</h3><p>Click "Add Product" to create your first product.</p></div>';
    return;
  }

  grid.innerHTML = products
    .map((product) => {
      const stockStatus =
        product.stock_status ||
        (product.stock > 10
          ? "in_stock"
          : product.stock > 0
            ? "low_stock"
            : "out_of_stock");
      const stockText =
        product.stock > 10
          ? "In Stock"
          : product.stock > 0
            ? "Low Stock"
            : "Out of Stock";
      const imagePath = product.image
        ? `../../${product.image}`
        : "../../assets/images/admin-site/default.png";
      const categoryName = product.category_name || product.category || "";
      const productTypeName =
        product.product_type_name || product.product_type || "";

      return `
            <div class="product-card" data-id="${product.id}">
                <div class="product-image-wrapper">
                    <img src="${imagePath}" class="product-image" alt="${escapeHtml(product.name)}" onerror="this.src='../../assets/images/admin-site/default.png'">
                    <div class="product-status ${stockStatus}">${stockText}</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">${escapeHtml(product.name)}</h3>
                    ${categoryName ? `<span class="product-category">${escapeHtml(categoryName)}</span>` : ""}
                    ${productTypeName ? `<span class="product-category" style="color:#6b7280;">${escapeHtml(productTypeName)}</span>` : ""}
                    <div class="product-price">₱${parseFloat(product.price || 0).toFixed(2)}</div>
                    <div class="product-stock">
                        <i class="fa-solid fa-boxes"></i> Stock: <strong>${product.stock || 0}</strong>
                    </div>
                    ${product.sku ? `<div class="product-sku" style="font-size:11px;color:#9ca3af;">SKU: ${escapeHtml(product.sku)}</div>` : ""}
                    <div class="product-actions">
                        <button class="btn-icon btn-edit" onclick="editProduct(${product.id})">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button class="btn-icon btn-delete" onclick="confirmDelete(${product.id}, '${escapeHtml(product.name)}')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        `;
    })
    .join("");
}

// Update pagination
function updatePagination(pagination) {
  const container = document.getElementById("paginationContainer");
  if (!container) return;

  totalPages = pagination.pages || 1;
  currentPage = pagination.page || 1;

  if (totalPages <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = '<div class="pagination">';
  html += `<button class="page-btn" ${currentPage <= 1 ? "disabled" : ""} onclick="goToPage(${currentPage - 1})">‹ Prev</button>`;

  const startPage = Math.max(1, currentPage - 2);
  const endPage = Math.min(totalPages, startPage + 4);

  if (startPage > 1) {
    html += `<button class="page-btn" onclick="goToPage(1)">1</button>`;
    if (startPage > 2) html += '<span class="page-dots">...</span>';
  }

  for (let i = startPage; i <= endPage; i++) {
    html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="goToPage(${i})">${i}</button>`;
  }

  if (endPage < totalPages) {
    if (endPage < totalPages - 1) html += '<span class="page-dots">...</span>';
    html += `<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
  }

  html += `<button class="page-btn" ${currentPage >= totalPages ? "disabled" : ""} onclick="goToPage(${currentPage + 1})">Next ›</button>`;
  html += "</div>";
  container.innerHTML = html;
}

// Go to page
function goToPage(page) {
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  loadProducts();
}

// Open add product modal
function openAddModal() {
  const modal = document.getElementById("productModal");
  const title = document.getElementById("modalTitle");
  const form = document.getElementById("productForm");

  if (title) title.innerHTML = '<i class="fa-solid fa-box"></i> Add Product';
  if (form) form.reset();

  document.getElementById("productId").value = "";
  document.getElementById("previewImg").src =
    "../../assets/images/admin-site/default.png";
  document.getElementById("productCategoryId").value = "";
  document.getElementById("productTypeId").value = "";
  document.getElementById("materialType").value = "assembled_product";
  document.getElementById("unit").value = "piece";

  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
}

// Edit product
async function editProduct(id) {
  try {
    const response = await fetch(`${API.getProducts}?page=1&limit=1000`);
    const data = await response.json();

    if (data.success) {
      const product = data.data.find((p) => p.id === id);
      if (product) {
        document.getElementById("productId").value = product.id;
        document.getElementById("productName").value = product.name;
        document.getElementById("productSku").value = product.sku || "";
        document.getElementById("productCategoryId").value =
          product.category_id || "";
        document.getElementById("productTypeId").value =
          product.product_type_id || "";
        document.getElementById("materialType").value =
          product.material_type || "assembled_product";
        document.getElementById("unit").value = product.unit || "piece";
        document.getElementById("productPrice").value = product.price;
        document.getElementById("productStock").value = product.stock;
        document.getElementById("productDescription").value =
          product.description || "";

        const imagePath = product.image
          ? `../../${product.image}`
          : "../../assets/images/admin-site/default.png";
        document.getElementById("previewImg").src = imagePath;

        const title = document.getElementById("modalTitle");
        if (title)
          title.innerHTML = '<i class="fa-solid fa-pen"></i> Edit Product';

        const modal = document.getElementById("productModal");
        if (modal) {
          modal.style.display = "flex";
          document.body.style.overflow = "hidden";
        }
      } else {
        alert("Product not found");
      }
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error loading product:", error);
    alert("Failed to load product details: " + error.message);
  }
}

// Confirm delete
function confirmDelete(id, name) {
  currentDeleteId = id;
  currentDeleteName = name;

  const messageEl = document.getElementById("deleteMessage");
  if (messageEl) {
    messageEl.innerHTML = `Are you sure you want to delete <strong>${escapeHtml(name)}</strong>?`;
  }

  const modal = document.getElementById("deleteModal");
  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
}

// Delete product
async function deleteProduct() {
  if (!currentDeleteId) return;

  try {
    const response = await fetch(API.deleteProduct, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: currentDeleteId }),
    });

    const data = await response.json();

    if (data.success) {
      closeDeleteModal();
      loadProducts();
      showToast("Product deleted successfully!", "success");
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error deleting product:", error);
    alert("Failed to delete product: " + error.message);
  }
}

// Save product
async function saveProduct(event) {
  event.preventDefault();

  const formData = new FormData();
  const id = document.getElementById("productId").value;

  if (id) formData.append("id", id);
  formData.append("name", document.getElementById("productName").value.trim());
  formData.append("sku", document.getElementById("productSku").value.trim());
  formData.append(
    "category_id",
    document.getElementById("productCategoryId").value,
  );
  formData.append(
    "product_type_id",
    document.getElementById("productTypeId").value,
  );
  formData.append(
    "material_type",
    document.getElementById("materialType").value,
  );
  formData.append("unit", document.getElementById("unit").value.trim());
  formData.append("price", document.getElementById("productPrice").value);
  formData.append("stock", document.getElementById("productStock").value);
  formData.append(
    "description",
    document.getElementById("productDescription").value.trim(),
  );

  const imageFile = document.getElementById("productImage").files[0];
  if (imageFile) {
    formData.append("image", imageFile);
  }

  // Validation
  const name = document.getElementById("productName").value.trim();
  if (!name || name.length < 3) {
    alert("Product name must be at least 3 characters");
    return;
  }

  const price = parseFloat(document.getElementById("productPrice").value);
  if (isNaN(price) || price < 0) {
    alert("Please enter a valid price");
    return;
  }

  const stock = parseInt(document.getElementById("productStock").value);
  if (isNaN(stock) || stock < 0) {
    alert("Please enter a valid stock quantity");
    return;
  }

  const url = id ? API.updateProduct : API.addProduct;

  try {
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      closeModal();
      loadProducts();
      showToast(data.message, "success");
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error saving product:", error);
    alert("Failed to save product: " + error.message);
  }
}

// Close modal
function closeModal() {
  const modal = document.getElementById("productModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "";
  }
}

// Close delete modal
function closeDeleteModal() {
  const modal = document.getElementById("deleteModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "";
  }
  currentDeleteId = null;
}

// Show toast notification
function showToast(message, type = "success") {
  const existingToast = document.querySelector(".toast-notification");
  if (existingToast) existingToast.remove();

  const toast = document.createElement("div");
  toast.className = `toast-notification toast-${type}`;
  toast.innerHTML = `<i class="fa-solid ${type === "success" ? "fa-circle-check" : "fa-circle-exclamation"}"></i><span>${escapeHtml(message)}</span>`;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = "slideOut 0.3s ease";
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Image preview
function initImagePreview() {
  const previewBox = document.getElementById("imagePreviewBox");
  const imageInput = document.getElementById("productImage");

  if (previewBox) {
    previewBox.addEventListener("click", () => {
      if (imageInput) imageInput.click();
    });
  }

  if (imageInput) {
    imageInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          const previewImg = document.getElementById("previewImg");
          if (previewImg) previewImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }
}

// Escape HTML
function escapeHtml(text) {
  if (!text) return "";
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
  loadProducts();
  initImagePreview();

  const addBtn = document.getElementById("addProductBtn");
  if (addBtn) addBtn.addEventListener("click", openAddModal);

  const closeBtn = document.getElementById("closeModalBtn");
  if (closeBtn) closeBtn.addEventListener("click", closeModal);

  const cancelBtn = document.getElementById("cancelModalBtn");
  if (cancelBtn) cancelBtn.addEventListener("click", closeModal);

  const closeDeleteBtn = document.getElementById("closeDeleteBtn");
  if (closeDeleteBtn)
    closeDeleteBtn.addEventListener("click", closeDeleteModal);

  const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
  if (cancelDeleteBtn)
    cancelDeleteBtn.addEventListener("click", closeDeleteModal);

  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  if (confirmDeleteBtn)
    confirmDeleteBtn.addEventListener("click", deleteProduct);

  const form = document.getElementById("productForm");
  if (form) form.addEventListener("submit", saveProduct);

  // Filters
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    let timeout;
    searchInput.addEventListener("input", () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        currentPage = 1;
        loadProducts();
      }, 300);
    });
  }

  const categoryFilter = document.getElementById("categoryFilter");
  if (categoryFilter) {
    categoryFilter.addEventListener("change", () => {
      currentPage = 1;
      loadProducts();
    });
  }

  const typeFilter = document.getElementById("typeFilter");
  if (typeFilter) {
    typeFilter.addEventListener("change", () => {
      currentPage = 1;
      loadProducts();
    });
  }

  const stockStatusFilter = document.getElementById("stockStatusFilter");
  if (stockStatusFilter) {
    stockStatusFilter.addEventListener("change", () => {
      currentPage = 1;
      loadProducts();
    });
  }

  // Close modal on backdrop click
  const modal = document.getElementById("productModal");
  if (modal) {
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });
  }

  const deleteModal = document.getElementById("deleteModal");
  if (deleteModal) {
    deleteModal.addEventListener("click", (e) => {
      if (e.target === deleteModal) closeDeleteModal();
    });
  }

  // Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeModal();
      closeDeleteModal();
    }
  });
});
