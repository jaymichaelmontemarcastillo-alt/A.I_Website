// Category Management Functions
const API_BASE = "../../api/admin_site/category_actions.php";
let currentPage = 1;
let currentLimit = 10;
let deleteTargetId = null;

// Initialize
document.addEventListener("DOMContentLoaded", function () {
  loadCategories();
  setupEventListeners();
});

// Event Listeners
function setupEventListeners() {
  // Add Category
  document
    .getElementById("addCategoryBtn")
    .addEventListener("click", openCategoryModal);

  // Modal controls
  document
    .getElementById("closeModalBtn")
    .addEventListener("click", closeCategoryModal);
  document
    .getElementById("cancelModalBtn")
    .addEventListener("click", closeCategoryModal);
  document
    .getElementById("categoryForm")
    .addEventListener("submit", handleSaveCategory);

  // Delete Modal
  document
    .getElementById("closeDeleteBtn")
    .addEventListener("click", closeCategoryDeleteModal);
  document
    .getElementById("cancelDeleteBtn")
    .addEventListener("click", closeCategoryDeleteModal);
  document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", confirmDelete);

  // Search & Filter
  document.getElementById("searchInput").addEventListener("input", () => {
    currentPage = 1;
    loadCategories();
  });

  document.getElementById("statusFilter").addEventListener("change", () => {
    currentPage = 1;
    loadCategories();
  });
}

// Load Categories
function loadCategories() {
  const search = document.getElementById("searchInput").value;
  const status = document.getElementById("statusFilter").value;

  const params = new URLSearchParams({
    action: "getcategories",
    page: currentPage,
    limit: currentLimit,
    search: search,
    status: status,
  });

  fetch(`${API_BASE}?${params}`)
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        renderCategories(data.data);
        renderPagination(data.pagination);

        if (data.data.length === 0 && currentPage === 1) {
          showEmptyState();
        } else {
          hideEmptyState();
        }
      } else {
        showError(data.message);
      }
    })
    .catch((err) => {
      console.error(err);
      showError("Failed to load categories");
    });
}

// Render Categories
function renderCategories(categories) {
  const tbody = document.getElementById("categoriesTableBody");

  if (categories.length === 0) {
    tbody.innerHTML =
      '<tr class="loading-row"><td colspan="7" class="text-center">No categories found</td></tr>';
    return;
  }

  tbody.innerHTML = categories
    .map(
      (cat) => `
        <tr>
            <td>${cat.id}</td>
            <td><strong>${escapeHtml(cat.name)}</strong></td>
            <td>${cat.description ? escapeHtml(cat.description.substring(0, 50)) : "-"}</td>
            <td>${cat.product_count}</td>
            <td>
                <span class="badge badge-${cat.status}">
                    ${cat.status.charAt(0).toUpperCase() + cat.status.slice(1)}
                </span>
            </td>
            <td>${new Date(cat.created_at).toLocaleDateString()}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn action-view" onclick="viewCategory(${cat.id})">
                        <i class="fa-solid fa-eye"></i> View
                    </button>
                    <button class="action-btn action-edit" onclick="openCategoryEditModal(${cat.id})">
                        <i class="fa-solid fa-edit"></i> Edit
                    </button>
                    <button class="action-btn action-delete" onclick="openCategoryDeleteModal(${cat.id}, '${escapeHtml(cat.name)}')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </td>
        </tr>
    `,
    )
    .join("");
}

// View Category
function viewCategory(id) {
  window.location.href = `category_details.php?id=${id}`;
}

// Open Add Category Modal
function openCategoryModal() {
  document.getElementById("categoryId").value = "";
  document.getElementById("modalTitle").textContent = "Add Category";
  document.getElementById("categoryName").value = "";
  document.getElementById("categoryDescription").value = "";
  document.getElementById("statusGroup").style.display = "none";
  document.getElementById("categoryForm").reset();
  document.getElementById("categoryModal").classList.add("show");
}

// Open Edit Category Modal
function openCategoryEditModal(id) {
  fetch(`${API_BASE}?action=getcategory&id=${id}`)
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        const cat = data.data;
        document.getElementById("categoryId").value = cat.id;
        document.getElementById("modalTitle").textContent = "Edit Category";
        document.getElementById("categoryName").value = cat.name;
        document.getElementById("categoryDescription").value = cat.description;
        document.getElementById("categoryStatus").value = cat.status;
        document.getElementById("statusGroup").style.display = "block";
        openCategoryModal();
      }
    })
    .catch((err) => showError("Failed to load category"));
}

// Save Category
function handleSaveCategory(e) {
  e.preventDefault();

  const id = document.getElementById("categoryId").value;
  const name = document.getElementById("categoryName").value.trim();
  const description = document
    .getElementById("categoryDescription")
    .value.trim();
  const status = document.getElementById("categoryStatus").value;

  if (!name) {
    showFieldError("categoryName", "Category name is required");
    return;
  }

  const formData = new FormData();
  formData.append("action", id ? "update" : "create");
  formData.append("name", name);
  formData.append("description", description);
  if (id) {
    formData.append("id", id);
    formData.append("status", status);
  }

  fetch(API_BASE, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        closeCategoryModal();
        currentPage = 1;
        loadCategories();
        showSuccess(data.message);
      } else {
        showError(data.message);
      }
    })
    .catch((err) => showError("Failed to save category"));
}

function openCategoryDeleteModal(id, name) {
  deleteTargetId = id;
  const body = document.getElementById("deleteModalBody");
  body.innerHTML = `<p>Are you sure you want to delete <strong>${name}</strong>?</p>`;
  document.getElementById("deleteModal").classList.add("show");
}

// Confirm Delete
function confirmDelete() {
  if (!deleteTargetId) return;

  const formData = new FormData();
  formData.append("action", "delete");
  formData.append("id", deleteTargetId);
  formData.append("force", true);

  fetch(API_BASE, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      closeCategoryDeleteModal();
      if (data.success) {
        currentPage = 1;
        loadCategories();
        showSuccess(data.message);
      } else {
        if (data.requiresConfirmation) {
          const confirmed = confirm(
            `This category has ${data.productCount} product(s). Delete anyway?`,
          );
          if (confirmed) {
            confirmDelete(); // Already set force: true
          }
        } else {
          showError(data.message);
        }
      }
    })
    .catch((err) => showError("Failed to delete category"));
}

// Pagination
function renderPagination(pag) {
  const container = document.getElementById("paginationContainer");
  let html = "";

  if (pag.pages <= 1) {
    container.innerHTML = "";
    return;
  }

  // Previous button
  if (currentPage > 1) {
    html += `<button class="pagination-btn" onclick="goToPage(${currentPage - 1})">← Previous</button>`;
  }

  // Page numbers
  for (let i = 1; i <= pag.pages; i++) {
    if (i === currentPage) {
      html += `<button class="pagination-btn active">${i}</button>`;
    } else {
      html += `<button class="pagination-btn" onclick="goToPage(${i})">${i}</button>`;
    }
  }

  // Next button
  if (currentPage < pag.pages) {
    html += `<button class="pagination-btn" onclick="goToPage(${currentPage + 1})">Next →</button>`;
  }

  container.innerHTML = html;
}

function goToPage(page) {
  currentPage = page;
  loadCategories();
  window.scrollTo(0, 0);
}

// Close Category Modal
function closeCategoryModal() {
  document.getElementById("categoryModal").classList.remove("show");
  document.getElementById("categoryForm").reset();
}

// Close Delete Category Modal
function closeCategoryDeleteModal() {
  document.getElementById("deleteModal").classList.remove("show");
  deleteTargetId = null;
}

// Helpers
function escapeHtml(text) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, (m) => map[m]);
}

function showFieldError(fieldId, message) {
  const field = document.getElementById(fieldId);
  const errorEl = field.parentElement.querySelector(".error-text");
  if (errorEl) {
    errorEl.textContent = message;
  }
}

function showEmptyState() {
  document.getElementById("emptyState").style.display = "block";
  document.querySelector(".table-container").style.display = "none";
  document.getElementById("paginationContainer").innerHTML = "";
}

function hideEmptyState() {
  document.getElementById("emptyState").style.display = "none";
  document.querySelector(".table-container").style.display = "block";
}

function showSuccess(msg) {
  // Could integrate with a toast notification system
  console.log("Success:", msg);
}

function showError(msg) {
  // Could integrate with a toast notification system
  console.error("Error:", msg);
  alert(msg);
}
