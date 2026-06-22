/**
 * Payment Methods Management
 * Handles adding new payment methods with modal interface
 */

let cachedPaymentMethods = null;

document.addEventListener("DOMContentLoaded", function () {
  setupPaymentMethodModalHandlers();
  loadPaymentMethods();
});

/**
 * Setup modal handlers for payment methods
 */
function setupPaymentMethodModalHandlers() {
  const modal = document.getElementById("addPaymentMethodModal");
  if (!modal) return;

  // Close modal handlers
  const closeBtn = modal.querySelector(".modal-close");
  if (closeBtn) {
    closeBtn.addEventListener("click", closeAddPaymentModal);
  }

  modal.addEventListener("click", function (e) {
    if (e.target === this) {
      closeAddPaymentModal();
    }
  });

  // Icon preview update
  const iconInput = document.getElementById("paymentMethodIcon");
  if (iconInput) {
    iconInput.addEventListener("input", function () {
      const iconPreview = document.getElementById("iconPreview");
      if (iconPreview) {
        iconPreview.className = this.value || "fa-solid fa-credit-card";
      }
    });
  }

  // Method value auto-format
  const methodValue = document.getElementById("paymentMethodValue");
  if (methodValue) {
    methodValue.addEventListener("input", function () {
      this.value = this.value.toLowerCase().replace(/\s+/g, "_");
    });
  }

  // Escape key to close
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modal.classList.contains("active")) {
      closeAddPaymentModal();
    }
  });
}

/**
 * Load payment methods from API
 */
function loadPaymentMethods() {
  fetch("../../api/admin_site/order_processes/payment_methods.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        cachedPaymentMethods = data.data || [];
        // Update the payment method dropdowns if they exist
        updatePaymentMethodDropdowns();
      }
    })
    .catch((err) => console.error("Error loading payment methods:", err));
}

/**
 * Update all payment method dropdowns in the table
 */
function updatePaymentMethodDropdowns() {
  const dropdowns = document.querySelectorAll(".method-dropdown");
  dropdowns.forEach((dropdown) => {
    updateSingleDropdown(dropdown);
  });
}

/**
 * Update a single payment method dropdown
 */
function updateSingleDropdown(dropdown) {
  const currentValue = dropdown.value;
  const options = dropdown.querySelectorAll("option");

  // Find the "Add Option" element
  const addOptionIndex = Array.from(options).findIndex(
    (opt) => opt.value === "add_new",
  );

  // Remove old options except first two (Select Method and hardcoded defaults)
  Array.from(options).forEach((opt, index) => {
    if (
      index > 3 ||
      (index === options.length - 1 && opt.value === "add_new")
    ) {
      opt.remove();
    }
  });

  // Add payment methods from API
  if (cachedPaymentMethods && cachedPaymentMethods.length > 0) {
    cachedPaymentMethods.forEach((method) => {
      // Skip if it's a built-in method (already in hardcoded options)
      if (["cash", "gcash", "card"].includes(method.method_value)) {
        return;
      }

      const option = document.createElement("option");
      option.value = method.method_value;
      option.textContent = `${method.method_name}`;
      if (method.icon_class) {
        option.innerHTML = `<i class="${method.icon_class}"></i> ${method.method_name}`;
      }
      option.dataset.icon = method.icon_class || "";
      dropdown.appendChild(option);
    });
  }

  // Add "Add Option" button as last option
  const addOption = document.createElement("option");
  addOption.value = "add_new";
  addOption.textContent = "+ Add Payment Method";
  addOption.style.fontWeight = "bold";
  addOption.style.color = "#007bff";
  dropdown.appendChild(addOption);

  // Restore original value
  dropdown.value = currentValue;

  // Add change handler
  dropdown.addEventListener("change", handlePaymentMethodChange);
}

/**
 * Handle payment method dropdown change
 */
function handlePaymentMethodChange(e) {
  if (e.target.value === "add_new") {
    // Reset dropdown to previous value
    const options = Array.from(e.target.options);
    const previousValue = options[options.length - 2]?.value || "pending";
    e.target.value = previousValue;

    // Open modal
    openAddPaymentModal();
  }
}

/**
 * Open the add payment method modal
 */
function openAddPaymentModal() {
  const modal = document.getElementById("addPaymentMethodModal");
  if (modal) {
    modal.classList.add("active");
    document.getElementById("paymentMethodName").focus();
  }
}

/**
 * Close the add payment method modal
 */
function closeAddPaymentModal() {
  const modal = document.getElementById("addPaymentMethodModal");
  if (modal) {
    modal.classList.remove("active");
    document.getElementById("addPaymentMethodForm").reset();
    document.getElementById("iconPreview").className =
      "fa-solid fa-credit-card";
  }
}

/**
 * Handle form submission for adding new payment method
 */
function handleAddPaymentMethod(event) {
  event.preventDefault();

  const methodName = document.getElementById("paymentMethodName").value.trim();
  const methodValue = document
    .getElementById("paymentMethodValue")
    .value.trim();
  const iconClass =
    document.getElementById("paymentMethodIcon").value.trim() ||
    "fa-solid fa-credit-card";

  // Validation
  if (!methodName || !methodValue) {
    showToast("Please fill in all required fields", true);
    return;
  }

  if (methodValue.length < 3) {
    showToast("Method identifier must be at least 3 characters", true);
    return;
  }

  if (!/^[a-z_]+$/.test(methodValue)) {
    showToast(
      "Method identifier can only contain lowercase letters and underscores",
      true,
    );
    return;
  }

  // Disable submit button
  const submitBtn = event.target.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';

  // Send API request
  fetch("../../api/admin_site/order_processes/payment_methods.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      method_name: methodName,
      method_value: methodValue,
      icon_class: iconClass,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast(`Payment method "${methodName}" added successfully!`);

        // Update cached methods
        if (!cachedPaymentMethods) cachedPaymentMethods = [];
        cachedPaymentMethods.push(data.data);

        // Update all dropdowns
        updatePaymentMethodDropdowns();

        // Close modal
        closeAddPaymentModal();

        // Reset form
        document.getElementById("addPaymentMethodForm").reset();
      } else {
        showToast(data.message || "Failed to add payment method", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Network error adding payment method", true);
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    });
}

/**
 * Show toast notification (reuse existing function)
 */
function showToast(message, isError = false) {
  const container = document.getElementById("toast-container");
  if (!container) return;

  const toast = document.createElement("div");
  toast.className = `toast ${isError ? "error" : "success"}`;
  toast.innerHTML = `
    <div class="toast-content">
      <i class="fa-solid ${isError ? "fa-circle-exclamation" : "fa-circle-check"}"></i>
      <span>${message}</span>
    </div>
  `;

  container.appendChild(toast);

  // Auto remove
  setTimeout(() => {
    toast.classList.add("fade-out");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}
