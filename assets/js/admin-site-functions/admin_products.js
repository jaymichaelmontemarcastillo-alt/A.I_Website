/* ============================= */
/* 📦 ADMIN PRODUCTS MANAGEMENT */
/* ============================= */

let currentEditingId = null;

/**
 * Show notification message
 * @param {string} message - The notification message
 * @param {string} type - Type: 'success', 'error', or 'info'
 */
function showNotification(message, type = "success") {
  const notif = document.getElementById("notification");
  if (!notif) return;

  notif.className = `notification ${type}`;
  notif.textContent = message;
  notif.style.display = "block";

  // Auto-hide after 4 seconds
  setTimeout(() => {
    notif.style.display = "none";
  }, 4000);
}

/**
 * Clear all form errors
 */
function clearErrors() {
  const errorElements = document.querySelectorAll(".form-error");
  errorElements.forEach((el) => {
    el.classList.remove("show");
    el.textContent = "";
  });
}

/**
 * Validate all form inputs
 * @returns {boolean} - True if all validations pass
 */
function validateForm() {
  clearErrors();
  let isValid = true;

  const name = document.getElementById("productName").value.trim();
  const category = document.getElementById("productCategory").value.trim();
  const price = parseFloat(document.getElementById("productPrice").value);
  const stock = parseInt(document.getElementById("productStock").value);

  // Validate name
  if (name.length < 3) {
    showFieldError("nameError", "Name must be at least 3 characters");
    isValid = false;
  }

  // Validate category
  if (category.length < 2) {
    showFieldError("categoryError", "Category must be at least 2 characters");
    isValid = false;
  }

  // Validate price
  if (isNaN(price) || price < 0) {
    showFieldError(
      "priceError",
      "Price must be a valid number greater than or equal to 0",
    );
    isValid = false;
  }

  // Validate stock
  if (isNaN(stock) || stock < 0) {
    showFieldError(
      "stockError",
      "Stock must be a valid number greater than or equal to 0",
    );
    isValid = false;
  }

  return isValid;
}

/**
 * Show error for a specific field
 * @param {string} fieldId - The error element ID
 * @param {string} message - The error message
 */
function showFieldError(fieldId, message) {
  const errorEl = document.getElementById(fieldId);
  if (errorEl) {
    errorEl.textContent = message;
    errorEl.classList.add("show");
  }
}

/**
 * Open the add product modal (Products page specific)
 */
function openProductModal() {
  currentEditingId = null;

  const modal = document.getElementById("productModal");
  const title = document.getElementById("modalTitle");
  const form = document.getElementById("productForm");
  const previewImg = document.getElementById("previewImg");
  const productId = document.getElementById("productId");

  title.innerHTML = '<i class="fa-solid fa-box"></i> Add Product';
  form.reset();
  previewImg.src = "https://via.placeholder.com/300?text=No+Image";
  productId.value = "";

  clearErrors();
  modal.classList.add("show");

  // Focus on first input
  document.getElementById("productName").focus();
}

/**
 * Deprecated: Use openProductModal() instead
 */
function openModal() {
  openProductModal();
}

/**
 * Open the edit product modal (Products page specific)
 * @param {object} product - Product data object
 */
function openProductEditModal(product) {
  currentEditingId = product.id;

  const modal = document.getElementById("productModal");
  const title = document.getElementById("modalTitle");
  const productId = document.getElementById("productId");
  const productName = document.getElementById("productName");
  const productCategory = document.getElementById("productCategory");
  const productPrice = document.getElementById("productPrice");
  const productStock = document.getElementById("productStock");
  const previewImg = document.getElementById("previewImg");

  title.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Product';
  productId.value = product.id;
  productName.value = product.name;
  productCategory.value = product.category;
  productPrice.value = product.price;
  productStock.value = product.stock;
  previewImg.src = "../../" + product.image;

  clearErrors();
  modal.classList.add("show");

  // Focus on first input
  productName.focus();
}

/**
 * Deprecated: Use openProductEditModal() instead
 */
function openEditModal(product) {
  openProductEditModal(product);
}

/**
 * Close the product modal (Products page specific)
 */
function closeProductModal() {
  const modal = document.getElementById("productModal");
  const form = document.getElementById("productForm");

  modal.classList.remove("show");
  form.reset();
  clearErrors();
  currentEditingId = null;
}

/**
 * Deprecated: Use closeProductModal() instead
 */
function closeModal() {
  closeProductModal();
}

/**
 * Handle image file selection and preview
 */
function setupImagePreview() {
  const productImage = document.getElementById("productImage");

  if (!productImage) return;

  productImage.addEventListener("change", function (e) {
    const file = this.files[0];
    if (!file) return;

    // Validate file size (5MB)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
      showNotification("Image size must be less than 5MB", "error");
      this.value = "";
      return;
    }

    // Validate file type
    const validTypes = ["image/jpeg", "image/png", "image/webp", "image/avif"];
    if (!validTypes.includes(file.type)) {
      showNotification(
        "Only JPEG, PNG, WEBP, and AVIF images are allowed",
        "error",
      );
      this.value = "";
      return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function (e) {
      const previewImg = document.getElementById("previewImg");
      previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

/**
 * Handle form submission (add/edit product)
 */
function setupFormSubmission() {
  const productForm = document.getElementById("productForm");

  if (!productForm) return;

  productForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Validate form
    if (!validateForm()) {
      showNotification("Please fix the errors above", "error");
      return;
    }

    const formData = new FormData(this);
    const id = document.getElementById("productId").value;
    const endpoint = id
      ? "../../api/admin_site/products/update_products.php"
      : "../../api/admin_site/products/add_products.php";

    const submitBtn = document.getElementById("submitBtn");
    const originalText = submitBtn.textContent;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner"></span>Processing...';

    // Send request
    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          const productName = document.getElementById("productName").value;
          const actionType = id ? "updated" : "added";
          const message = `✓ Product "${productName}" ${actionType} successfully!`;
          showNotification(message, "success");
          // Reload page after 1.5 seconds
          setTimeout(() => {
            location.reload();
          }, 1500);
        } else {
          showNotification(data.message || "An error occurred", "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification(
          "An error occurred while processing the request",
          "error",
        );
      })
      .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
  });
}

/**
 * Open delete confirmation modal
 * @param {number} id - Product ID
 */
function showDeleteConfirm(id) {
  const row = document.querySelector(`tr[data-product-id="${id}"]`);
  if (!row) return;

  const productName = row.querySelector("td:nth-child(2)").textContent.trim();
  const deleteModal = document.getElementById("deleteConfirmModal");
  const deleteMessage = document.getElementById("deleteMessage");
  const deleteConfirmBtn = document.getElementById("deleteConfirmBtn");

  deleteMessage.innerHTML = `<strong>Are you sure you want to delete <span style="color: #dc3545;">"${productName}"</span>?</strong>`;
  deleteModal.classList.add("show");

  // Clear previous event listeners by cloning
  const newBtn = deleteConfirmBtn.cloneNode(true);
  deleteConfirmBtn.parentNode.replaceChild(newBtn, deleteConfirmBtn);

  // Add new event listener
  newBtn.addEventListener("click", function () {
    performDelete(id, productName);
  });
}

/**
 * Close delete confirmation modal (Products page specific)
 */
function closeProductDeleteModal() {
  const deleteModal = document.getElementById("deleteConfirmModal");
  deleteModal.classList.remove("show");
}

/**
 * Deprecated: Use closeProductDeleteModal() instead
 */
function closeDeleteConfirm() {
  closeProductDeleteModal();
}

/**
 * Perform the actual deletion
 * @param {number} id - Product ID
 * @param {string} productName - Product name
 */
function performDelete(id, productName) {
  const formData = new FormData();
  formData.append("id", id);

  fetch("../../api/admin_site/products/delete_products.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      closeDeleteConfirm();
      if (data.success) {
        const message = `✓ Product "${productName}" deleted successfully!`;
        showNotification(message, "success");

        // Remove row from table with animation
        const row = document.querySelector(`tr[data-product-id="${id}"]`);
        if (row) {
          row.style.animation = "fadeOut 0.3s ease forwards";
          setTimeout(() => {
            row.remove();
            // Update product count
            const tableBody = document.getElementById("productTableBody");
            const count = tableBody.querySelectorAll("tr").length;
            const countEl = document.querySelector(".product-count");
            if (countEl) {
              countEl.textContent =
                count + (count === 1 ? " product" : " products");
            }
          }, 300);
        }
      } else {
        showNotification(data.message || "Failed to delete product", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Failed to delete product", "error");
    });
}

/**
 * Close modal when clicking outside of it
 */
function setupModalClickOutside() {
  const modal = document.getElementById("productModal");

  if (!modal) return;

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      closeProductModal();
    }
  });
}

/**
 * Close modal on ESC key
 */
function setupModalKeyListener() {
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      const modal = document.getElementById("productModal");
      if (modal && modal.classList.contains("show")) {
        closeProductModal();
      }
    }
  });
}

/**
 * Setup animation styles
 */
function setupAnimations() {
  const style = document.createElement("style");
  style.textContent = `
    @keyframes fadeOut {
      from {
        opacity: 1;
      }
      to {
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);
}

/**
 * Setup close button event listeners for reliability
 */
function setupCloseButtons() {
  // Modal close button (X) - using product-modal
  const closeBtn = document.querySelector(".product-modal .close");
  if (closeBtn) {
    closeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeProductModal();
      return false;
    });
  }

  // Modal cancel button - using product-modal
  const cancelBtn = document.querySelector(
    ".product-modal .modal-footer .cancel-btn",
  );
  if (cancelBtn) {
    cancelBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeProductModal();
      return false;
    });
  }

  // Delete confirmation modal close button - using product-delete-modal
  const deleteCloseBtn = document.querySelector(".product-delete-modal .close");
  if (deleteCloseBtn) {
    deleteCloseBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeProductDeleteModal();
      return false;
    });
  }
}

/**
 * Update delete button onclick handlers to use modal instead of confirm
 */
function updateDeleteButtons() {
  const deleteButtons = document.querySelectorAll(".delete-btn");
  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      const row = this.closest("tr");
      const productId = row.dataset.productId;
      showDeleteConfirm(productId);
    });
  });
}

/**
 * Initialize all product management functionality
 */
function initProducts() {
  setupImagePreview();
  setupFormSubmission();
  setupModalClickOutside();
  setupModalKeyListener();
  setupAnimations();
  setupCloseButtons();
  updateDeleteButtons();
}

/**
 * Initialize on DOM ready
 */
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initProducts);
} else {
  initProducts();
}
