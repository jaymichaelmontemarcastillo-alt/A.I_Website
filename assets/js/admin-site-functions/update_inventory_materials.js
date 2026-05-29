// assets/js/admin-site-functions/update_inventory_materials.js
/**
 * Update Inventory Materials Module
 * Handles editing existing material items (name, type, stock, unit cost)
 */

// API Endpoint
const UPDATE_MATERIAL_API =
  "../../api/admin_site/inventory/update_material_item.php";

// Initialize Edit Item Modal
const matInitEditItemModal = () => {
  console.log("Initializing Edit Item Modal...");

  // Close button
  const closeBtn = document.getElementById("matEditItemCloseBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", matCloseEditItemModal);
  }

  // Cancel button
  const cancelBtn = document.getElementById("matEditItemCancelBtn");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", matCloseEditItemModal);
  }

  // Confirm button
  const confirmBtn = document.getElementById("matEditItemConfirmBtn");
  if (confirmBtn) {
    confirmBtn.addEventListener("click", matUpdateItem);
  }

  // Stock input listeners for total preview
  const shopStockInput = document.getElementById("matEditShopStock");
  const phStockInput = document.getElementById("matEditPhStock");

  if (shopStockInput) {
    shopStockInput.addEventListener("input", matUpdateEditTotalPreview);
  }
  if (phStockInput) {
    phStockInput.addEventListener("input", matUpdateEditTotalPreview);
  }

  // Unit cost input listener
  const unitCostInput = document.getElementById("matEditUnitCost");
  if (unitCostInput) {
    unitCostInput.addEventListener("input", matUpdateEditTotalPreview);
  }

  // Close modal when clicking outside
  const editItemModal = document.getElementById("matEditItemModal");
  if (editItemModal) {
    editItemModal.addEventListener("click", function (e) {
      if (e.target === editItemModal) {
        matCloseEditItemModal();
      }
    });
  }

  // Escape key listener
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      matCloseEditItemModal();
    }
  });
};

// Open Edit Item Modal
window.matOpenEditItemModal = (
  materialId,
  materialName,
  type,
  shopStock,
  phStock,
  unitCost,
) => {
  console.log("Opening Edit Modal for:", materialName);

  // Set form values
  const idInput = document.getElementById("matEditItemId");
  const nameInput = document.getElementById("matEditMaterialName");
  const typeSelect = document.getElementById("matEditType");
  const shopStockInput = document.getElementById("matEditShopStock");
  const phStockInput = document.getElementById("matEditPhStock");
  const unitCostInput = document.getElementById("matEditUnitCost");
  const errorDiv = document.getElementById("matEditItemError");

  if (idInput) idInput.value = materialId;
  if (nameInput) nameInput.value = materialName;
  if (typeSelect) typeSelect.value = type;
  if (shopStockInput) shopStockInput.value = shopStock;
  if (phStockInput) phStockInput.value = phStock;
  if (unitCostInput) unitCostInput.value = unitCost;
  if (errorDiv) errorDiv.innerHTML = "";

  // Update total preview
  matUpdateEditTotalPreview();

  // Show modal
  const modal = document.getElementById("matEditItemModal");
  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    // Focus on name input
    setTimeout(() => nameInput?.focus(), 100);
  }
};

// Close Edit Item Modal
const matCloseEditItemModal = () => {
  const modal = document.getElementById("matEditItemModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "";
  }
  // Clear error message
  const errorDiv = document.getElementById("matEditItemError");
  if (errorDiv) errorDiv.innerHTML = "";
};

// Update total stock preview for edit modal
const matUpdateEditTotalPreview = () => {
  const shopStock =
    parseInt(document.getElementById("matEditShopStock")?.value) || 0;
  const phStock =
    parseInt(document.getElementById("matEditPhStock")?.value) || 0;
  const total = shopStock + phStock;
  const totalSpan = document.getElementById("matEditTotalValue");
  if (totalSpan) totalSpan.innerText = total;
};

// Update item via API
const matUpdateItem = async () => {
  const id = document.getElementById("matEditItemId")?.value;
  const materialName =
    document.getElementById("matEditMaterialName")?.value.trim() || "";
  const type = document.getElementById("matEditType")?.value || "";
  const shopStock =
    parseInt(document.getElementById("matEditShopStock")?.value) || 0;
  const phStock =
    parseInt(document.getElementById("matEditPhStock")?.value) || 0;
  const unitCost =
    parseFloat(document.getElementById("matEditUnitCost")?.value) || 0;

  const errorDiv = document.getElementById("matEditItemError");
  const confirmBtn = document.getElementById("matEditItemConfirmBtn");

  // Validation
  if (!materialName) {
    if (errorDiv) {
      errorDiv.innerHTML =
        '<i class="fa-solid fa-exclamation-triangle"></i> Please enter material name.';
      errorDiv.style.color = "#dc2626";
    }
    return;
  }

  if (!type) {
    if (errorDiv) {
      errorDiv.innerHTML =
        '<i class="fa-solid fa-exclamation-triangle"></i> Please select a type.';
      errorDiv.style.color = "#dc2626";
    }
    return;
  }

  if (shopStock < 0 || phStock < 0) {
    if (errorDiv) {
      errorDiv.innerHTML =
        '<i class="fa-solid fa-exclamation-triangle"></i> Stock values cannot be negative.';
      errorDiv.style.color = "#dc2626";
    }
    return;
  }

  if (unitCost < 0) {
    if (errorDiv) {
      errorDiv.innerHTML =
        '<i class="fa-solid fa-exclamation-triangle"></i> Unit cost cannot be negative.';
      errorDiv.style.color = "#dc2626";
    }
    return;
  }

  if (errorDiv) {
    errorDiv.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Updating item...';
    errorDiv.style.color = "#2563eb";
  }

  if (confirmBtn) {
    confirmBtn.disabled = true;
    confirmBtn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
  }

  try {
    const response = await fetch(UPDATE_MATERIAL_API, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id: parseInt(id),
        material_name: materialName,
        type: type,
        shop_stock: shopStock,
        ph_stock: phStock,
        unit_cost: unitCost,
      }),
    });

    const result = await response.json();

    if (result.success) {
      if (errorDiv) errorDiv.innerHTML = "";
      matCloseEditItemModal();
      matShowToast("Item updated successfully!", "success");
      // Reload tables
      if (typeof matReloadMaterials === "function") matReloadMaterials(true);
      if (typeof matReloadLogs === "function") matReloadLogs(true);
    } else {
      if (errorDiv) {
        errorDiv.innerHTML =
          '<i class="fa-solid fa-exclamation-triangle"></i> ' + result.message;
        errorDiv.style.color = "#dc2626";
      }
    }
  } catch (error) {
    console.error("Error updating item:", error);
    if (errorDiv) {
      errorDiv.innerHTML =
        '<i class="fa-solid fa-exclamation-triangle"></i> Network error. Please try again.';
      errorDiv.style.color = "#dc2626";
    }
  } finally {
    if (confirmBtn) {
      confirmBtn.disabled = false;
      confirmBtn.innerHTML = '<i class="fa-solid fa-save"></i> Save Changes';
    }
  }
};

// Toast notification function (if not already defined)
const matShowToast = (message, type = "success") => {
  let container = document.getElementById("matToastContainer");
  if (!container) {
    container = document.createElement("div");
    container.id = "matToastContainer";
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  const icon = type === "success" ? "fa-circle-check" : "fa-circle-exclamation";
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${matEsc(message)}</span>`;
  container.appendChild(toast);

  setTimeout(() => toast.classList.add("show"), 10);

  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3500);
};

// Helper function for escaping HTML
const matEsc = (str) => {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
};

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  console.log("Update Inventory Materials JS loaded");
  matInitEditItemModal();
});
