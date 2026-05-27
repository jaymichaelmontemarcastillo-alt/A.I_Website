// ============================================================
//  inventory_item_manager.js
//  Handles Add New Item and Edit Item modals for Inventory
//  ISOLATED - No conflicts with admin_materials.js
// ============================================================

(function () {
  "use strict";

  // API Endpoints
  const API = {
    addItem: "../../api/admin_site/inventory/add_inventory_item.php",
    updateItem: "../../api/admin_site/inventory/update_material_item.php",
  };

  // DOM Elements cache
  let elements = {};

  // Initialize when DOM is ready
  document.addEventListener("DOMContentLoaded", function () {
    cacheElements();
    attachEventListeners();
  });

  // Cache DOM elements
  function cacheElements() {
    elements = {
      // Add Item Modal
      addModal: document.getElementById("matAddItemModal"),
      addName: document.getElementById("matNewMaterialName"),
      addType: document.getElementById("matNewType"),
      addShopStock: document.getElementById("matNewShopStock"),
      addPhStock: document.getElementById("matNewPhStock"),
      addUnitCost: document.getElementById("matNewUnitCost"),
      addError: document.getElementById("matAddItemError"),
      addTotalValue: document.getElementById("matNewTotalValue"),
      addCloseBtn: document.getElementById("matAddItemCloseBtn"),
      addCancelBtn: document.getElementById("matAddItemCancelBtn"),
      addConfirmBtn: document.getElementById("matAddItemConfirmBtn"),

      // Edit Item Modal
      editModal: document.getElementById("matEditItemModal"),
      editId: document.getElementById("matEditItemId"),
      editName: document.getElementById("matEditMaterialName"),
      editType: document.getElementById("matEditType"),
      editShopStock: document.getElementById("matEditShopStock"),
      editPhStock: document.getElementById("matEditPhStock"),
      editUnitCost: document.getElementById("matEditUnitCost"),
      editError: document.getElementById("matEditItemError"),
      editTotalValue: document.getElementById("matEditTotalValue"),
      editCloseBtn: document.getElementById("matEditItemCloseBtn"),
      editCancelBtn: document.getElementById("matEditItemCancelBtn"),
      editConfirmBtn: document.getElementById("matEditItemConfirmBtn"),
    };
  }

  // Attach all event listeners
  function attachEventListeners() {
    // Add Item Modal events
    if (elements.addCloseBtn) {
      elements.addCloseBtn.addEventListener("click", closeAddModal);
    }
    if (elements.addCancelBtn) {
      elements.addCancelBtn.addEventListener("click", closeAddModal);
    }
    if (elements.addConfirmBtn) {
      elements.addConfirmBtn.addEventListener("click", submitAddItem);
    }
    if (elements.addShopStock) {
      elements.addShopStock.addEventListener("input", updateAddTotalPreview);
    }
    if (elements.addPhStock) {
      elements.addPhStock.addEventListener("input", updateAddTotalPreview);
    }
    if (elements.addModal) {
      elements.addModal.addEventListener("click", function (e) {
        if (e.target === elements.addModal) closeAddModal();
      });
    }

    // Edit Item Modal events
    if (elements.editCloseBtn) {
      elements.editCloseBtn.addEventListener("click", closeEditModal);
    }
    if (elements.editCancelBtn) {
      elements.editCancelBtn.addEventListener("click", closeEditModal);
    }
    if (elements.editConfirmBtn) {
      elements.editConfirmBtn.addEventListener("click", submitEditItem);
    }
    if (elements.editShopStock) {
      elements.editShopStock.addEventListener("input", updateEditTotalPreview);
    }
    if (elements.editPhStock) {
      elements.editPhStock.addEventListener("input", updateEditTotalPreview);
    }
    if (elements.editModal) {
      elements.editModal.addEventListener("click", function (e) {
        if (e.target === elements.editModal) closeEditModal();
      });
    }

    // ESC key to close modals
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        if (isModalOpen(elements.addModal)) closeAddModal();
        if (isModalOpen(elements.editModal)) closeEditModal();
      }
    });
  }

  // Check if modal is open
  function isModalOpen(modal) {
    return modal && modal.style.display === "flex";
  }

  // ==================== ADD ITEM FUNCTIONS ====================

  // Open Add Item Modal (exposed globally)
  window.matOpenAddItemModal = function () {
    console.log("Opening Add Item Modal");

    // Reset form
    if (elements.addName) elements.addName.value = "";
    if (elements.addType) elements.addType.value = "";
    if (elements.addShopStock) elements.addShopStock.value = "0";
    if (elements.addPhStock) elements.addPhStock.value = "0";
    if (elements.addUnitCost) elements.addUnitCost.value = "0";
    if (elements.addError) elements.addError.innerHTML = "";

    updateAddTotalPreview();

    if (elements.addModal) {
      elements.addModal.style.display = "flex";
      document.body.style.overflow = "hidden";
      setTimeout(() => elements.addName?.focus(), 100);
    }
  };

  // Close Add Item Modal
  function closeAddModal() {
    if (elements.addModal) {
      elements.addModal.style.display = "none";
      document.body.style.overflow = "";
    }
  }

  // Update total stock preview for Add modal
  function updateAddTotalPreview() {
    const shopStock = parseInt(elements.addShopStock?.value) || 0;
    const phStock = parseInt(elements.addPhStock?.value) || 0;
    const total = shopStock + phStock;
    if (elements.addTotalValue) elements.addTotalValue.innerText = total;
  }

  // Submit Add Item
  async function submitAddItem() {
    const materialName = elements.addName?.value.trim() || "";
    const type = elements.addType?.value || "";
    const shopStock = parseInt(elements.addShopStock?.value) || 0;
    const phStock = parseInt(elements.addPhStock?.value) || 0;
    const unitCost = parseFloat(elements.addUnitCost?.value) || 0;

    // Validation
    if (!materialName) {
      if (elements.addError)
        elements.addError.innerHTML = "Please enter material name.";
      return;
    }

    if (!type) {
      if (elements.addError)
        elements.addError.innerHTML = "Please select a type.";
      return;
    }

    if (shopStock < 0 || phStock < 0) {
      if (elements.addError)
        elements.addError.innerHTML = "Stock values cannot be negative.";
      return;
    }

    if (unitCost < 0) {
      if (elements.addError)
        elements.addError.innerHTML = "Unit cost cannot be negative.";
      return;
    }

    // Show loading state
    if (elements.addError) {
      elements.addError.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Adding item...';
    }
    if (elements.addConfirmBtn) {
      elements.addConfirmBtn.disabled = true;
      elements.addConfirmBtn.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    }

    try {
      const response = await fetch(API.addItem, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          material_name: materialName,
          type: type,
          shop_stock: shopStock,
          ph_stock: phStock,
          unit_cost: unitCost,
        }),
      });

      const result = await response.json();

      if (result.success) {
        if (elements.addError) elements.addError.innerHTML = "";
        closeAddModal();
        showToast("Item added successfully!", "success");

        // Refresh the materials table (call global function if exists)
        if (typeof matReloadMaterials === "function") {
          matReloadMaterials(true);
        }
        if (typeof matReloadLogs === "function") {
          matReloadLogs(true);
        }
      } else {
        if (elements.addError) elements.addError.innerHTML = result.message;
      }
    } catch (error) {
      console.error("Error adding item:", error);
      if (elements.addError)
        elements.addError.innerHTML = "Network error. Please try again.";
    } finally {
      if (elements.addConfirmBtn) {
        elements.addConfirmBtn.disabled = false;
        elements.addConfirmBtn.innerHTML =
          '<i class="fa-solid fa-save"></i> Save Item';
      }
    }
  }

  // ==================== EDIT ITEM FUNCTIONS ====================

  // Open Edit Item Modal (exposed globally)
  window.matOpenEditItemModal = function (
    id,
    name,
    type,
    shopStock,
    phStock,
    unitCost,
  ) {
    console.log("Opening Edit Item Modal for:", name);

    if (elements.editId) elements.editId.value = id;
    if (elements.editName) elements.editName.value = name || "";
    if (elements.editType) elements.editType.value = type || "";
    if (elements.editShopStock) elements.editShopStock.value = shopStock || 0;
    if (elements.editPhStock) elements.editPhStock.value = phStock || 0;
    if (elements.editUnitCost) elements.editUnitCost.value = unitCost || 0;
    if (elements.editError) elements.editError.innerHTML = "";

    updateEditTotalPreview();

    if (elements.editModal) {
      elements.editModal.style.display = "flex";
      document.body.style.overflow = "hidden";
      setTimeout(() => elements.editName?.focus(), 100);
    }
  };

  // Close Edit Item Modal
  function closeEditModal() {
    if (elements.editModal) {
      elements.editModal.style.display = "none";
      document.body.style.overflow = "";
    }
  }

  // Update total stock preview for Edit modal
  function updateEditTotalPreview() {
    const shopStock = parseInt(elements.editShopStock?.value) || 0;
    const phStock = parseInt(elements.editPhStock?.value) || 0;
    const total = shopStock + phStock;
    if (elements.editTotalValue) elements.editTotalValue.innerText = total;
  }

  // Submit Edit Item
  async function submitEditItem() {
    const id = parseInt(elements.editId?.value) || 0;
    const materialName = elements.editName?.value.trim() || "";
    const type = elements.editType?.value || "";
    const shopStock = parseInt(elements.editShopStock?.value) || 0;
    const phStock = parseInt(elements.editPhStock?.value) || 0;
    const unitCost = parseFloat(elements.editUnitCost?.value) || 0;

    // Validation
    if (!id) {
      if (elements.editError)
        elements.editError.innerHTML = "Invalid material ID.";
      return;
    }

    if (!materialName) {
      if (elements.editError)
        elements.editError.innerHTML = "Please enter material name.";
      return;
    }

    if (!type) {
      if (elements.editError)
        elements.editError.innerHTML = "Please select a type.";
      return;
    }

    if (shopStock < 0 || phStock < 0) {
      if (elements.editError)
        elements.editError.innerHTML = "Stock values cannot be negative.";
      return;
    }

    if (unitCost < 0) {
      if (elements.editError)
        elements.editError.innerHTML = "Unit cost cannot be negative.";
      return;
    }

    // Show loading state
    if (elements.editError) {
      elements.editError.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Updating item...';
    }
    if (elements.editConfirmBtn) {
      elements.editConfirmBtn.disabled = true;
      elements.editConfirmBtn.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    }

    try {
      const response = await fetch(API.updateItem, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: id,
          material_name: materialName,
          type: type,
          shop_stock: shopStock,
          ph_stock: phStock,
          unit_cost: unitCost,
        }),
      });

      const result = await response.json();

      if (result.success) {
        if (elements.editError) elements.editError.innerHTML = "";
        closeEditModal();
        showToast("Item updated successfully!", "success");

        // Refresh the materials table (call global function if exists)
        if (typeof matReloadMaterials === "function") {
          matReloadMaterials(true);
        }
        if (typeof matReloadLogs === "function") {
          matReloadLogs(true);
        }
      } else {
        if (elements.editError) elements.editError.innerHTML = result.message;
      }
    } catch (error) {
      console.error("Error updating item:", error);
      if (elements.editError)
        elements.editError.innerHTML = "Network error. Please try again.";
    } finally {
      if (elements.editConfirmBtn) {
        elements.editConfirmBtn.disabled = false;
        elements.editConfirmBtn.innerHTML =
          '<i class="fa-solid fa-save"></i> Save Changes';
      }
    }
  }

  // ==================== TOAST NOTIFICATION ====================

  function showToast(message, type = "success") {
    // Check if toast container exists, if not create it
    let container = document.getElementById("inventoryToastContainer");
    if (!container) {
      container = document.createElement("div");
      container.id = "inventoryToastContainer";
      container.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 100002;
                display: flex;
                flex-direction: column;
                gap: 10px;
            `;
      document.body.appendChild(container);
    }

    const icon =
      type === "success" ? "fa-circle-check" : "fa-circle-exclamation";
    const bgColor = type === "success" ? "#10b981" : "#ef4444";

    const toast = document.createElement("div");
    toast.style.cssText = `
            background: ${bgColor};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
            transform: translateX(0);
        `;
    toast.innerHTML = `<i class="fa-solid ${icon}"></i> ${escapeHtml(message)}`;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translateX(100%)";
      toast.style.transition = "all 0.3s ease";
      setTimeout(() => {
        if (toast.parentNode) toast.parentNode.removeChild(toast);
      }, 300);
    }, 3000);
  }

  function escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  // Add animation styles if not present
  if (!document.getElementById("inventoryToastStyles")) {
    const style = document.createElement("style");
    style.id = "inventoryToastStyles";
    style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
    document.head.appendChild(style);
  }
})();
