/**
 * Activity Logs Retention Management
 * Handles auto-cleanup settings and confirmation
 */

document.addEventListener("DOMContentLoaded", function () {
  // Load current retention settings
  loadRetentionSettings();

  // Toggle switch handler
  const toggle = document.getElementById("retentionToggle");
  if (toggle) {
    toggle.addEventListener("change", function () {
      const config = document.getElementById("retentionConfig");
      if (this.checked) {
        config.style.display = "flex";
      } else {
        config.style.display = "none";
      }
    });
  }

  // Apply retention button handler
  const applyBtn = document.getElementById("applyRetentionBtn");
  if (applyBtn) {
    applyBtn.addEventListener("click", function () {
      openRetentionModal();
    });
  }

  // Confirm retention button handler
  const confirmBtn = document.querySelector(".activity-log-btn-confirm");
  if (confirmBtn) {
    confirmBtn.addEventListener("click", function () {
      applyRetention();
    });
  }

  // Password input - Enter key handler
  const passwordInput = document.getElementById("retentionPassword");
  if (passwordInput) {
    passwordInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        applyRetention();
      }
    });
  }

  // Close modal on overlay click
  const modalOverlay = document.querySelector(".activity-log-modal-overlay");
  if (modalOverlay) {
    modalOverlay.addEventListener("click", function (e) {
      if (e.target === this) {
        closeRetentionModal();
      }
    });
  }

  // Close modal with ESC key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeRetentionModal();
    }
  });
});

/**
 * Load current retention settings from server
 */
function loadRetentionSettings() {
  fetch("../../api/admin_site/activity_logs_retention.php", {
    method: "GET",
    credentials: "same-origin",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const toggle = document.getElementById("retentionToggle");
        const periodSelect = document.getElementById("retentionPeriodSelect");
        const config = document.getElementById("retentionConfig");
        const statusText = document.getElementById("retentionPeriod");

        // Set toggle state
        toggle.checked = data.enabled;

        // Show/hide config
        config.style.display = data.enabled ? "flex" : "none";

        // Set period
        if (data.period) {
          periodSelect.value = data.period;
        }

        // Update status text
        if (data.enabled) {
          const periodLabel =
            periodSelect.options[periodSelect.selectedIndex]?.text ||
            "2 Months";
          statusText.textContent = periodLabel;
          statusText.style.color = "#16a34a";
        } else {
          statusText.textContent = "Disabled";
          statusText.style.color = "#6b7280";
        }
      }
    })
    .catch((error) => {
      console.error("Error loading retention settings:", error);
    });
}

/**
 * Open the confirmation modal
 */
function openRetentionModal() {
  const modal = document.querySelector(".activity-log-modal-overlay");
  const periodSelect = document.getElementById("retentionPeriodSelect");
  const periodText = document.getElementById("modalPeriodText");
  const passwordInput = document.getElementById("retentionPassword");
  const errorDiv = document.querySelector(".activity-log-modal-error");

  if (!modal) return;

  // Set the period text
  periodText.textContent =
    periodSelect.options[periodSelect.selectedIndex]?.text || "2 months";

  // Clear previous values
  passwordInput.value = "";
  if (errorDiv) {
    errorDiv.style.display = "none";
    errorDiv.textContent = "";
  }

  // Show modal by adding active class
  modal.classList.add("active");
  modal.style.display = "flex";

  // Focus on password input
  setTimeout(() => passwordInput.focus(), 100);
}

/**
 * Close the confirmation modal
 */
function closeRetentionModal() {
  const modal = document.querySelector(".activity-log-modal-overlay");
  if (modal) {
    modal.classList.remove("active");
    modal.style.display = "none";
  }
}

/**
 * Apply retention settings (called from modal)
 */
function applyRetention() {
  const toggle = document.getElementById("retentionToggle");
  const periodSelect = document.getElementById("retentionPeriodSelect");
  const passwordInput = document.getElementById("retentionPassword");
  const errorDiv = document.querySelector(".activity-log-modal-error");
  const confirmBtn = document.querySelector(".activity-log-btn-confirm");
  const periodLabel =
    periodSelect.options[periodSelect.selectedIndex]?.text || "2 months";

  // Validate password
  const password = passwordInput.value.trim();
  if (!password) {
    if (errorDiv) {
      errorDiv.textContent = "Please enter your admin password.";
      errorDiv.style.display = "block";
    }
    passwordInput.focus();
    return;
  }

  // Disable button and show loading
  confirmBtn.disabled = true;
  confirmBtn.innerHTML =
    '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
  if (errorDiv) {
    errorDiv.style.display = "none";
  }

  const enabled = toggle.checked;
  const period = periodSelect.value;

  // Prepare data
  const data = {
    action: "update_retention",
    enabled: enabled,
    period: period,
    password: password,
  };

  // Send request
  fetch("../../api/admin_site/activity_logs_retention.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "same-origin",
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Close modal
        closeRetentionModal();

        // Update status
        const statusText = document.getElementById("retentionPeriod");
        if (enabled) {
          statusText.textContent = periodLabel;
          statusText.style.color = "#16a34a";
        } else {
          statusText.textContent = "Disabled";
          statusText.style.color = "#6b7280";
        }

        // Show success message
        showNotification("success", data.message);

        // Reload table if logs were deleted
        if (data.deleted_count > 0 && typeof loadActivityLogs === "function") {
          loadActivityLogs();
        }

        // Save settings to localStorage for persistence
        localStorage.setItem("retention_enabled", enabled ? "1" : "0");
        localStorage.setItem("retention_period", period);
      } else {
        // Show error
        if (errorDiv) {
          errorDiv.textContent = data.message || "An error occurred.";
          errorDiv.style.display = "block";
        }
        passwordInput.value = "";
        passwordInput.focus();
      }
    })
    .catch((error) => {
      if (errorDiv) {
        errorDiv.textContent = "Network error: " + error.message;
        errorDiv.style.display = "block";
      }
    })
    .finally(() => {
      // Re-enable button
      confirmBtn.disabled = false;
      confirmBtn.innerHTML =
        '<i class="fa-solid fa-trash-can"></i> Delete Logs';
    });
}

/**
 * Show notification
 */
function showNotification(type, message) {
  // Check if notification element exists, if not create it
  let notification = document.querySelector(".notification-toast");
  if (!notification) {
    notification = document.createElement("div");
    notification.className = "notification-toast";
    document.body.appendChild(notification);
  }

  // Set styles based on type
  notification.className = "notification-toast " + type;
  notification.innerHTML = message;
  notification.style.display = "block";

  // Auto-hide after 5 seconds
  clearTimeout(notification._timeout);
  notification._timeout = setTimeout(() => {
    notification.style.display = "none";
  }, 5000);
}
