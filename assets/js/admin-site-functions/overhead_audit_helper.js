// overhead_audit_helper.js
const OverheadHelper = {
  attachListeners(container, onUpdateCallback) {
    const inputs = container.querySelectorAll(
      ".overhead-input, #production_hours",
    );
    inputs.forEach((input) => {
      input.removeEventListener("input", this._handler);
      const handler = () => {
        this.updateTotals(container);
        if (typeof onUpdateCallback === "function") onUpdateCallback();
      };
      input.addEventListener("input", handler);
      input._overheadHandler = handler;
    });
  },

  updateTotals(container) {
    const fields = [
      "overhead_shop_rent",
      "overhead_fixed_salaries",
      "overhead_shop_utilities",
      "overhead_subscriptions",
      "overhead_machine_depreciation",
      "overhead_maintenance_repair",
      "overhead_marketing",
      "overhead_electricity",
    ];
    let total = 0;
    fields.forEach((field) => {
      const el = container.querySelector(`#${field}`);
      if (el) total += parseFloat(el.value) || 0;
    });
    const totalEl = container.querySelector("#total_overhead_display");
    if (totalEl) totalEl.textContent = `₱${total.toFixed(2)}`;

    const hours =
      parseFloat(container.querySelector("#production_hours")?.value) || 0;
    const perHour = hours > 0 ? total / hours : 0;
    const perHourEl = container.querySelector("#overhead_per_hour_display");
    if (perHourEl)
      perHourEl.innerHTML = `<strong>₱${perHour.toFixed(4)}</strong> per hour`;

    container._overheadTotal = total;
    container._overheadPerHour = perHour;
    container._productionHours = hours;
  },

  getOverheadData(container) {
    return {
      shop_rent:
        parseFloat(container.querySelector("#overhead_shop_rent")?.value) || 0,
      fixed_salaries:
        parseFloat(
          container.querySelector("#overhead_fixed_salaries")?.value,
        ) || 0,
      shop_utilities:
        parseFloat(
          container.querySelector("#overhead_shop_utilities")?.value,
        ) || 0,
      subscriptions:
        parseFloat(container.querySelector("#overhead_subscriptions")?.value) ||
        0,
      machine_depreciation:
        parseFloat(
          container.querySelector("#overhead_machine_depreciation")?.value,
        ) || 0,
      maintenance_repair:
        parseFloat(
          container.querySelector("#overhead_maintenance_repair")?.value,
        ) || 0,
      marketing:
        parseFloat(container.querySelector("#overhead_marketing")?.value) || 0,
      electricity:
        parseFloat(container.querySelector("#overhead_electricity")?.value) ||
        0,
    };
  },

  getProductionHours(container) {
    return parseFloat(container.querySelector("#production_hours")?.value) || 0;
  },

  getTotalOverhead(container) {
    return container._overheadTotal || 0;
  },
};
/**
 * Open Audit Modal with Quotation Data
 * This function is called from the Quotation page
 */
function openQuotationAuditModal(quotationId, quotationNumber) {
  // Check if we're on the right page
  const auditModal = document.getElementById("auditModal");

  // If audit modal doesn't exist on this page, redirect to Inventory page
  if (!auditModal) {
    // Store the quotation ID in session storage
    sessionStorage.setItem("pending_audit_quotation_id", quotationId);
    sessionStorage.setItem("pending_audit_quotation_number", quotationNumber);
    sessionStorage.setItem("pending_audit_timestamp", Date.now());

    // Show a notification
    if (typeof quotationManager?.showNotification === "function") {
      quotationManager.showNotification(
        "Redirecting to Inventory page for audit...",
        "info",
      );
    }

    // Redirect to Inventory page
    setTimeout(() => {
      window.location.href = "../inventory/inventory.php";
    }, 500);

    return;
  }

  // If audit modal exists, open it directly
  try {
    // Store the quotation info for use in the audit
    window.currentAuditQuotationId = quotationId;
    window.currentAuditQuotationNumber = quotationNumber;

    // Show the audit modal
    if (typeof matOpenAuditModal === "function") {
      matOpenAuditModal();
    } else {
      // Fallback: just show the modal
      const modal = document.getElementById("auditModal");
      if (modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
      }
    }

    // Pre-fill audit form with quotation data
    setTimeout(() => {
      prefillAuditWithQuotation(quotationId, quotationNumber);
    }, 300);

    if (typeof quotationManager?.showNotification === "function") {
      quotationManager.showNotification(
        `Audit form opened for ${quotationNumber}. Please add materials used.`,
        "success",
      );
    }
  } catch (error) {
    console.error("Error opening audit modal:", error);
    if (typeof quotationManager?.showNotification === "function") {
      quotationManager.showNotification(
        "Error opening audit form. Please try again.",
        "error",
      );
    }
  }
}

/**
 * Pre-fill audit form with quotation data
 */
async function prefillAuditWithQuotation(quotationId, quotationNumber) {
  try {
    // Fetch quotation details
    const response = await fetch(
      `../../api/admin_site/get_single_quotation.php?id=${quotationId}`,
    );
    const result = await response.json();

    if (!result.success) {
      console.error("Failed to load quotation data:", result.message);
      return;
    }

    const quotation = result.data.quotation;
    const items = result.data.items || [];

    // Pre-fill the audit form fields
    const itemNameInput = document.getElementById("auditItemName");
    if (itemNameInput) {
      itemNameInput.value = `Audit for ${quotationNumber} - ${quotation.client_name || ""}`;
    }

    const createdByInput = document.getElementById("createdBy");
    if (createdByInput && quotation.client_name) {
      createdByInput.value = quotation.client_name;
    }

    // Add items from quotation to the audit items list
    const itemsContainer = document.getElementById("itemsContainer");
    if (itemsContainer && items.length > 0) {
      // Clear existing items
      itemsContainer.innerHTML = "";

      // Add each item
      items.forEach((item) => {
        addItemToAuditList(item);
      });

      // Recalculate totals
      if (typeof matComputeTotals === "function") {
        matComputeTotals();
      }
    }

    // Show a success notification
    if (typeof quotationManager?.showNotification === "function") {
      quotationManager.showNotification(
        `Loaded ${items.length} items from ${quotationNumber}`,
        "success",
      );
    }
  } catch (error) {
    console.error("Error prefilling audit form:", error);
  }
}

/**
 * Add an item to the audit items list
 */
function addItemToAuditList(item) {
  const container = document.getElementById("itemsContainer");
  if (!container) return;

  // Create a row for the item
  const row = document.createElement("div");
  row.className = "dynamic-row";
  row.innerHTML = `
        <input type="text" class="item-name" placeholder="Item name" value="${escapeHtml(item.description || "")}" style="flex:2;">
        <input type="number" class="item-qty" placeholder="QTY" value="${item.quantity || 0}" style="flex:1;">
        <input type="number" class="item-unit-price" placeholder="Unit Price" step="0.01" value="${item.unit_price || 0}" style="flex:1;">
        <input type="number" class="item-total-amount" placeholder="Total Amount" step="0.01" value="${item.total || 0}" style="flex:1;" readonly>
        <button class="remove-row" onclick="removeAuditDynamicRow(this)"><i class="fa-solid fa-trash"></i></button>
    `;
  container.appendChild(row);
}

/**
 * Remove a dynamic row from audit
 */
function removeAuditDynamicRow(button) {
  const row = button.closest(".dynamic-row");
  if (row && document.querySelectorAll(".dynamic-row").length > 1) {
    row.remove();
    if (typeof matComputeTotals === "function") {
      matComputeTotals();
    }
  }
}

/**
 * Escape HTML helper
 */
function escapeHtml(text) {
  if (!text) return "";
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}
// scroll_helper.js - Add to your project
const ScrollHelper = {
  lockScroll() {
    // Save current scroll position
    document.body.dataset.scrollY = window.scrollY;
    document.body.style.overflow = "hidden";
  },

  unlockScroll() {
    // Remove all scroll-blocking styles
    document.body.style.overflow = "";
    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.width = "";
    document.body.style.height = "";

    // Restore scroll position if saved
    if (document.body.dataset.scrollY) {
      const scrollY = parseInt(document.body.dataset.scrollY) || 0;
      window.scrollTo(0, scrollY);
      delete document.body.dataset.scrollY;
    }
  },
};

// Make it globally available
window.ScrollHelper = ScrollHelper;
