// ============================================================
// ADMIN CATEGORY FUNCTIONS - Complete CRUD for Categories
// ============================================================

let currentPage = 1;
let totalPages = 1;
let currentDeleteId = null;
let currentDeleteName = "";

// API Endpoints
const API = {
  getCategories:
    "../../api/admin_site/category_actions.php?action=getcategories",
  getCategory: "../../api/admin_site/category_actions.php?action=getcategory",
  createCategory: "../../api/admin_site/category_actions.php?action=create",
  updateCategory: "../../api/admin_site/category_actions.php?action=update",
  deleteCategory: "../../api/admin_site/category_actions.php?action=delete",
};

// Load categories with filters
async function loadCategories() {
  const search = document.getElementById("searchInput")?.value || "";
  const status = document.getElementById("statusFilter")?.value || "";

  const params = new URLSearchParams({
    page: currentPage,
    limit: 10,
    search: search,
    status: status,
  });

  const tbody = document.getElementById("categoriesTableBody");
  if (tbody) {
    tbody.innerHTML =
      '<tr class="loading-row"><td colspan="8" class="text-center">Loading categories...</td></tr>';
  }

  try {
    const response = await fetch(`${API.getCategories}&${params}`);
    const data = await response.json();

    if (data.success) {
      renderCategories(data.data);
      updatePagination(data.pagination);

      const resultCount = document.getElementById("resultCount");
      if (resultCount) {
        resultCount.textContent = `${data.pagination.total} category${data.pagination.total !== 1 ? "s" : ""}`;
      }
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error loading categories:", error);
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center error">Error: ${error.message}</td></tr>`;
    }
  }
}

// Render categories table
function renderCategories(categories) {
  const tbody = document.getElementById("categoriesTableBody");
  if (!tbody) return;

  if (!categories || categories.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="8" class="text-center">No categories found</td></tr>';
    return;
  }

  tbody.innerHTML = categories
    .map((category) => {
      const statusBadge =
        category.status === "active"
          ? '<span class="badge badge-success">Active</span>'
          : '<span class="badge badge-danger">Inactive</span>';
      const createdDate = category.created_at
        ? new Date(category.created_at).toLocaleDateString()
        : "—";

      return `
            <tr data-id="${category.id}">
                <td>${category.id}</td>
                <td><strong>${escapeHtml(category.name)}</strong></td>
                <td><code>${escapeHtml(category.slug || "—")}</code></td>
                <td>${escapeHtml(category.description || "—")}</td>
                <td>${category.product_count || 0}</td>
                <td>${statusBadge}</td>
                <td>${createdDate}</td>
                <td class="actions">
                    <button class="btn-icon btn-edit" onclick="editCategory(${category.id})" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn-icon btn-delete" onclick="confirmDelete(${category.id}, '${escapeHtml(category.name)}', ${category.product_count || 0})" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
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

  // Previous button
  html += `<button class="page-btn" ${currentPage <= 1 ? "disabled" : ""} onclick="goToPage(${currentPage - 1})">‹ Prev</button>`;

  // Page numbers
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

  // Next button
  html += `<button class="page-btn" ${currentPage >= totalPages ? "disabled" : ""} onclick="goToPage(${currentPage + 1})">Next ›</button>`;

  html += "</div>";
  container.innerHTML = html;
}

// Go to page
function goToPage(page) {
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  loadCategories();
}

// Generate slug from name
function generateSlug(name) {
  return name
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, "")
    .replace(/[\s_-]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

// Auto-generate slug when name is entered
function initSlugGeneration() {
  const nameInput = document.getElementById("categoryName");
  const slugInput = document.getElementById("categorySlug");

  if (nameInput && slugInput) {
    nameInput.addEventListener("input", () => {
      if (!slugInput.value || slugInput.value === "") {
        slugInput.value = generateSlug(nameInput.value);
      }
    });
  }
}

// Open add category modal
function openAddModal() {
  const modal = document.getElementById("categoryModal");
  const title = document.getElementById("modalTitle");
  const form = document.getElementById("categoryForm");

  if (title) title.textContent = "Add Category";
  if (form) form.reset();

  document.getElementById("categoryId").value = "";
  document.getElementById("statusGroup").style.display = "none";

  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
}

// Edit category
async function editCategory(id) {
  try {
    const response = await fetch(`${API.getCategory}&id=${id}`);
    const data = await response.json();

    if (data.success) {
      const category = data.data;

      document.getElementById("categoryId").value = category.id;
      document.getElementById("categoryName").value = category.name;
      document.getElementById("categorySlug").value = category.slug || "";
      document.getElementById("categoryDescription").value =
        category.description || "";
      document.getElementById("categoryStatus").value = category.status;

      document.getElementById("statusGroup").style.display = "block";

      const title = document.getElementById("modalTitle");
      if (title) title.textContent = "Edit Category";

      const modal = document.getElementById("categoryModal");
      if (modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
      }
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error loading category:", error);
    alert("Failed to load category details: " + error.message);
  }
}

// Confirm delete (with product check)
function confirmDelete(id, name, productCount) {
  currentDeleteId = id;
  currentDeleteName = name;

  const messageEl = document.getElementById("deleteMessage");
  if (messageEl) {
    if (productCount > 0) {
      messageEl.innerHTML = `Category "<strong>${escapeHtml(name)}</strong>" has <strong>${productCount}</strong> product(s).<br>Are you sure you want to delete it? This will affect these products.`;
    } else {
      messageEl.innerHTML = `Are you sure you want to delete category "<strong>${escapeHtml(name)}</strong>"?`;
    }
  }

  const modal = document.getElementById("deleteModal");
  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
}

// Delete category
async function deleteCategory() {
  if (!currentDeleteId) return;

  try {
    const formData = new FormData();
    formData.append("id", currentDeleteId);
    formData.append("force", "true");

    const response = await fetch(API.deleteCategory, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      closeDeleteModal();
      loadCategories();
      showToast("Category deleted successfully!", "success");
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error deleting category:", error);
    alert("Failed to delete category: " + error.message);
  }
}

// Save category (add or update)
async function saveCategory(event) {
  event.preventDefault();

  const id = document.getElementById("categoryId").value;
  const name = document.getElementById("categoryName").value.trim();
  let slug = document.getElementById("categorySlug").value.trim();
  const description = document
    .getElementById("categoryDescription")
    .value.trim();

  // Validation
  if (!name) {
    alert("Category name is required");
    return;
  }

  if (name.length < 2) {
    alert("Category name must be at least 2 characters");
    return;
  }

  if (name.length > 255) {
    alert("Category name must be less than 255 characters");
    return;
  }

  // Auto-generate slug if empty
  if (!slug) {
    slug = generateSlug(name);
  }

  const formData = new FormData();
  if (id) formData.append("id", id);
  formData.append("name", name);
  formData.append("slug", slug);
  formData.append("description", description);

  if (id) {
    const status = document.getElementById("categoryStatus").value;
    formData.append("status", status);
  }

  const url = id ? API.updateCategory : API.createCategory;

  try {
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      closeModal();
      loadCategories();
      showToast(data.message, "success");
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error saving category:", error);
    alert("Failed to save category: " + error.message);
  }
}

// Close modal
function closeModal() {
  const modal = document.getElementById("categoryModal");
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
  // Remove existing toast
  const existingToast = document.querySelector(".toast-notification");
  if (existingToast) existingToast.remove();

  const toast = document.createElement("div");
  toast.className = `toast-notification toast-${type}`;
  toast.innerHTML = `<i class="fa-solid ${type === "success" ? "fa-circle-check" : "fa-circle-exclamation"}"></i><span>${escapeHtml(message)}</span>`;
  toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === "success" ? "#10b981" : "#ef4444"};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        max-width: 350px;
    `;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = "slideOut 0.3s ease";
    setTimeout(() => toast.remove(), 300);
  }, 3000);
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
  loadCategories();
  initSlugGeneration();

  // Add button
  const addBtn = document.getElementById("addCategoryBtn");
  if (addBtn) addBtn.addEventListener("click", openAddModal);

  // Close buttons
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
    confirmDeleteBtn.addEventListener("click", deleteCategory);

  // Form submit
  const form = document.getElementById("categoryForm");
  if (form) form.addEventListener("submit", saveCategory);

  // Filters
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    let timeout;
    searchInput.addEventListener("input", () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        currentPage = 1;
        loadCategories();
      }, 300);
    });
  }

  const statusFilter = document.getElementById("statusFilter");
  if (statusFilter)
    statusFilter.addEventListener("change", () => {
      currentPage = 1;
      loadCategories();
    });

  // Close modal on backdrop click
  const modal = document.getElementById("categoryModal");
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

// Add CSS animations
const style = document.createElement("style");
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .toast-notification {
        font-family: 'Inter', sans-serif;
    }
    .badge-success {
        background: #d1fae5;
        color: #065f46;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .error {
        color: #ef4444;
    }
    .text-center {
        text-align: center;
    }
    code {
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
    }
`;
document.head.appendChild(style);
