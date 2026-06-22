/**
 * Orders Management - COMPLETE WORKING Version
 * Handles all dropdowns: Payment Method, Payment Status, Order Status
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize state
  window.currentOrders = [];
  window.currentOrderData = null;
  window.currentOrderNumber = null;
  window.paymentMethods = null;

  // Initial load
  fetchOrders();

  // Poll for updates every 15 seconds (reduced frequency)
  setInterval(() => fetchOrders(true), 15000);

  // Modal close handlers
  setupModalHandlers();

  // Filter handlers
  setupFilterHandlers();

  // Load payment methods for dropdowns (handled by payment_methods.js)
  // This ensures compatibility with the new payment methods system
});

/* ═════════════════════════════════════════════════════════════ */
/* MODAL HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function setupModalHandlers() {
  const closeBtn = document.querySelector(".modal-close");
  if (closeBtn) {
    closeBtn.addEventListener("click", closeOrderModal);
  }

  const modal = document.getElementById("OrderItemModal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        closeOrderModal();
      }
    });
  }

  const footerBtn = document.querySelector(".modal-footer .btn-secondary");
  if (footerBtn) {
    footerBtn.addEventListener("click", closeOrderModal);
  }

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeOrderModal();
    }
  });
}

function openOrderModal(orderId) {
  showToast("Loading order details...");

  const apiUrl = `../../api/admin_site/order_processes/fetch_order_details.php?order_id=${encodeURIComponent(orderId)}`;

  fetch(apiUrl)
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        showToast(data.message || "Error loading order details", true);
        return;
      }

      window.currentOrderData = data.order;
      window.currentOrderNumber =
        data.order.quote_number || data.order.order_number;

      populateModal(data.order, data.items || []);
      document.getElementById("OrderItemModal").classList.add("active");
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error loading order details", true);
    });
}

function closeOrderModal() {
  const modal = document.getElementById("OrderItemModal");
  if (modal) {
    modal.classList.remove("active");
  }
  window.currentOrderData = null;
  window.currentOrderNumber = null;
}

function populateModal(order, items) {
  // Header
  document.getElementById("modalOrderId").textContent =
    `Order Details - ${order.quote_number || order.order_number || "N/A"}`;

  // Customer Information
  document.getElementById("modalClientName").textContent =
    order.client_name || "N/A";
  document.getElementById("modalContactPerson").textContent =
    order.contact_person || "N/A";
  document.getElementById("modalCustomerEmail").textContent =
    order.customer_email || "N/A";
  document.getElementById("modalCustomerPhone").textContent =
    order.customer_phone || "N/A";
  document.getElementById("modalAddress").textContent = order.address || "N/A";

  // Quotation Information
  document.getElementById("modalQuoteNumber").textContent =
    order.quote_number || order.order_number || "N/A";
  document.getElementById("modalQuoteDate").textContent = formatDate(
    order.created_at,
  );
  document.getElementById("modalQuoteStatus").innerHTML =
    getQuotationStatusBadge(order.quotation_status);

  // Notes row
  const notesRow = document.getElementById("modalNotesRow");
  const notesEl = document.getElementById("modalNotes");
  if (order.notes) {
    notesEl.textContent = order.notes;
    notesRow.style.display = "flex";
  } else {
    notesRow.style.display = "none";
  }

  // Items Table
  const itemsBody = document.getElementById("modalItemsBody");
  itemsBody.innerHTML = "";

  if (items && items.length > 0) {
    items.forEach((item) => {
      const row = document.createElement("tr");
      const price = parseFloat(item.price) || 0;
      const qty = parseInt(item.quantity) || 0;
      const subtotal = parseFloat(item.subtotal) || price * qty;

      row.innerHTML = `
        <td>${escapeHtml(item.product_name || item.description || "N/A")}</td>
        <td style="text-align: center;">${qty}</td>
        <td style="text-align: right;">₱${price.toFixed(2)}</td>
        <td style="text-align: right;">₱${subtotal.toFixed(2)}</td>
      `;
      itemsBody.appendChild(row);
    });
  } else {
    itemsBody.innerHTML =
      '<tr><td colspan="4" style="text-align: center; color: var(--text-secondary);">No items</td></tr>';
  }

  // Summary
  const subtotal = parseFloat(order.subtotal) || 0;
  const tax = parseFloat(order.tax) || 0;
  const discount = parseFloat(order.discount) || 0;
  const grandTotal = parseFloat(order.total_amount) || 0;

  document.getElementById("modalSubtotal").textContent =
    `₱${subtotal.toFixed(2)}`;
  document.getElementById("modalTax").textContent = `₱${tax.toFixed(2)}`;
  document.getElementById("modalDiscount").textContent =
    `₱${discount.toFixed(2)}`;
  document.getElementById("modalGrandTotal").textContent =
    `₱${grandTotal.toFixed(2)}`;

  // Payment Information
  document.getElementById("modalPaymentMethod").innerHTML =
    getPaymentMethodBadge(order.payment_method);
  document.getElementById("modalPaymentStatus").innerHTML =
    getPaymentStatusBadge(order.payment_status);
  document.getElementById("modalOrderStatus").innerHTML = getOrderStatusBadge(
    order.order_status,
  );

  // Reference number
  const referenceNumber = order.resolved_reference;
  const refRow = document.getElementById("refRow");
  if (referenceNumber) {
    document.getElementById("modalReference").textContent = referenceNumber;
    refRow.style.display = "flex";
  } else {
    refRow.style.display = "none";
  }

  // Proof of Payment
  const proofUrl = order.resolved_proof;
  const proofCard = document.getElementById("proofCard");
  const proofContent = document.getElementById("proofContent");

  if (order.payment_method === "gcash") {
    proofCard.style.display = "block";
    if (proofUrl) {
      proofContent.innerHTML = `
        <a href="${proofUrl}" target="_blank" class="proof-link">
          <img src="${proofUrl}" alt="Payment Proof" class="proof-image"
            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22150%22%3E%3Crect fill=%22%23f0f0f0%22 width=%22200%22 height=%22150%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23999%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22%3EImage unavailable%3C/text%3E%3C/svg%3E'">
        </a>
      `;
    } else {
      proofContent.innerHTML = `
        <div class="proof-missing">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>No proof uploaded yet</span>
        </div>
      `;
    }
  } else {
    proofCard.style.display = "none";
  }

  // Action Buttons - Show only for pending GCash payments with proof
  const actionCard = document.getElementById("actionCard");
  if (
    order.payment_status === "pending" &&
    order.payment_method === "gcash" &&
    proofUrl
  ) {
    actionCard.style.display = "block";
  } else {
    actionCard.style.display = "none";
  }
}

/* ═════════════════════════════════════════════════════════════ */
/* FETCH & RENDER ORDERS */
/* ═════════════════════════════════════════════════════════════ */

function fetchOrders(isPolling = false) {
  fetch("../../api/admin_site/order_processes/fetch_orders.php")
    .then((res) => res.json())
    .then((response) => {
      if (response.status === "success") {
        if (isPolling) {
          updateTableLive(response.data);
        } else {
          window.currentOrders = response.data;
          applyFilters();
        }
      } else {
        console.error(
          "fetch_orders.php error:",
          response.message || "Unknown error",
        );
        if (!isPolling) {
          renderOrders([]);
          showToast(
            "Failed to load orders: " + (response.message || "Unknown error"),
            true,
          );
        }
      }
    })
    .catch((err) => {
      console.error("Error fetching orders:", err);
      if (!isPolling) {
        const tbody = document.getElementById("ordersTableBody");
        if (tbody) {
          tbody.innerHTML = `
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px;">
                <p style="color: var(--text-secondary); margin: 0;">
                  Failed to load orders. Please refresh the page.
                </p>
              </td>
            </tr>
          `;
        }
        showToast("Network error loading orders", true);
      }
    });
}

function getFilteredOrders() {
  const statusFilter = document.getElementById("statusFilter")?.value || "";
  const paymentFilter = document.getElementById("paymentFilter")?.value || "";
  const methodFilter = document.getElementById("methodFilter")?.value || "";
  const searchTerm = (document.getElementById("orderSearch")?.value || "")
    .toLowerCase()
    .trim();

  return window.currentOrders.filter((order) => {
    const matchesStatus = !statusFilter || order.order_status === statusFilter;
    const matchesPayment =
      !paymentFilter || order.payment_status === paymentFilter;
    const matchesMethod =
      !methodFilter || order.payment_method === methodFilter;
    const matchesSearch =
      !searchTerm ||
      (order.customer_name || "").toLowerCase().includes(searchTerm) ||
      (order.quote_number || "").toLowerCase().includes(searchTerm) ||
      (order.display_quote_id || "").toString().includes(searchTerm);

    return matchesStatus && matchesPayment && matchesMethod && matchesSearch;
  });
}

function applyFilters() {
  const filteredOrders = getFilteredOrders();
  renderOrders(filteredOrders);
}

function renderOrders(orders) {
  const tbody = document.getElementById("ordersTableBody");
  if (!tbody) return;

  tbody.innerHTML = "";

  if (orders.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="9" style="text-align: center; padding: 40px;">
          <p style="color: var(--text-secondary); margin: 0;">No orders found</p>
        </td>
      </tr>
    `;
    return;
  }

  orders.forEach((order) => {
    const row = createTableRow(order);
    tbody.appendChild(row);
  });
}
function createTableRow(order) {
  const tr = document.createElement("tr");
  const recordId = order.quote_number || order.order_number;
  tr.dataset.orderId = recordId;

  const displayQuoteId = order.display_quote_id || order.quotation_id || "N/A";
  const customerName = order.customer_name || "N/A";
  const quoteNumber = order.quote_number || "N/A";
  const date = formatDate(order.created_at);
  const total = parseFloat(order.total_amount) || 0;
  const paymentMethod = order.payment_method || "pending";
  const paymentStatus = order.payment_status || "pending";
  const orderStatus = order.order_status || "pending";
  // Alternative version with Font Awesome icons in dropdowns
  tr.innerHTML = `
  <td style="text-align: center;"><strong>${escapeHtml(displayQuoteId.toString())}</strong></td>
  <td>${escapeHtml(customerName)}</td>
  <td>${escapeHtml(quoteNumber)}</td>
  <td>${date}</td>
  <td><strong>₱${total.toFixed(2)}</strong></td>
  <td>
    <select class="method-dropdown styled-dropdown" data-order-id="${escapeHtml(recordId)}" data-field="payment_method">
      <option value="pending" ${paymentMethod === "pending" ? "selected" : ""}><i class="fa-regular fa-circle"></i> Select Method</option>
      <option value="cash" ${paymentMethod === "cash" ? "selected" : ""}><i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery</option>
      <option value="gcash" ${paymentMethod === "gcash" ? "selected" : ""}><i class="fa-solid fa-mobile-alt"></i> GCash</option>
      <option value="card" ${paymentMethod === "card" ? "selected" : ""}><i class="fa-regular fa-credit-card"></i> Card</option>
      <option value="add_new" style="font-weight: bold; color: #007bff;">+ Add Payment Method</option>
    </select>
  </td>
  <td>
    <select class="payment-dropdown styled-dropdown" data-order-id="${escapeHtml(recordId)}" data-field="payment_status">
      <option value="pending" ${paymentStatus === "pending" ? "selected" : ""}><i class="fa-regular fa-clock"></i> Pending</option>
      <option value="paid" ${paymentStatus === "paid" ? "selected" : ""}><i class="fa-regular fa-circle-check"></i> Paid</option>
      <option value="failed" ${paymentStatus === "failed" ? "selected" : ""}><i class="fa-regular fa-circle-xmark"></i> Failed</option>
    </select>
  </td>
  <td>
    <select class="status-dropdown styled-dropdown" data-order-id="${escapeHtml(recordId)}" data-field="order_status">
      <option value="pending" ${orderStatus === "pending" ? "selected" : ""}><i class="fa-regular fa-clock"></i> Pending</option>
      <option value="processing" ${orderStatus === "processing" ? "selected" : ""}><i class="fa-solid fa-gear"></i> Processing</option>
      <option value="packed" ${orderStatus === "packed" ? "selected" : ""}><i class="fa-solid fa-box"></i> Packed</option>
      <option value="shipped" ${orderStatus === "shipped" ? "selected" : ""}><i class="fa-solid fa-truck"></i> Shipped</option>
      <option value="delivered" ${orderStatus === "delivered" ? "selected" : ""}><i class="fa-regular fa-circle-check"></i> Delivered</option>
      <option value="cancelled" ${orderStatus === "cancelled" ? "selected" : ""}><i class="fa-regular fa-circle-xmark"></i> Cancelled</option>
    </select>
  </td>
  <td>
    <button class="action-icon" title="View Order" onclick="openOrderModal('${escapeHtml(recordId)}')">
      <i class="fa-regular fa-eye"></i>
    </button>
  </td>
`;
  // Add event listeners
  const statusDropdown = tr.querySelector(".status-dropdown");
  const paymentDropdown = tr.querySelector(".payment-dropdown");
  const methodDropdown = tr.querySelector(".method-dropdown");

  if (statusDropdown) {
    statusDropdown.addEventListener("change", (e) => {
      updateOrderField(
        recordId,
        "order_status",
        e.target.value,
        statusDropdown,
      );
    });
  }

  if (paymentDropdown) {
    paymentDropdown.addEventListener("change", (e) => {
      updateOrderField(
        recordId,
        "payment_status",
        e.target.value,
        paymentDropdown,
      );
    });
  }

  if (methodDropdown) {
    methodDropdown.addEventListener("change", (e) => {
      if (e.target.value === "add_new") {
        // Store the current value so we can restore it
        const currentValue = e.target.dataset.previousValue || "pending";
        e.target.value = currentValue;

        // Open the add payment method modal
        if (typeof openAddPaymentModal === "function") {
          openAddPaymentModal();
        }
      } else {
        // Store current value for reference
        e.target.dataset.previousValue = e.target.value;

        updateOrderField(
          recordId,
          "payment_method",
          e.target.value,
          methodDropdown,
        );
      }
    });

    // Store initial value
    methodDropdown.dataset.previousValue = methodDropdown.value;
  }

  return tr;
}
/* ═════════════════════════════════════════════════════════════ */
/* UPDATE HANDLER - Unified function for all updates */
/* ═════════════════════════════════════════════════════════════ */

function updateOrderField(orderId, field, value, dropdownElement) {
  // Store original value in case of failure
  const originalValue = dropdownElement.value;

  // Disable dropdown during update
  dropdownElement.disabled = true;

  const payload = { order_number: orderId };

  if (field === "order_status") {
    payload.order_status = value;
  } else if (field === "payment_status") {
    payload.payment_status = value;
  } else if (field === "payment_method") {
    payload.payment_method = value;
  }

  showToast(`Updating ${field.replace("_", " ")}...`);

  fetch("../../api/admin_site/order_processes/update_order_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast(`${field.replace("_", " ")} updated successfully`);

        // Update the dropdown with the confirmed value from server
        if (data.updated_data) {
          const newValue = data.updated_data[field];
          if (newValue && dropdownElement.value !== newValue) {
            dropdownElement.value = newValue;
          }
        }

        // Refresh the orders list in background
        setTimeout(() => fetchOrders(true), 500);

        // If modal is open, refresh it with updated data
        if (window.currentOrderNumber) {
          setTimeout(() => {
            openOrderModal(orderId);
          }, 1000);
        }
      } else {
        // Revert to original value on failure
        dropdownElement.value = originalValue;
        showToast(data.message || `Failed to update ${field}`, true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      // Revert to original value on error
      dropdownElement.value = originalValue;
      showToast("Network error updating", true);
    })
    .finally(() => {
      // Re-enable dropdown
      dropdownElement.disabled = false;
    });
}

function updateTableLive(newOrders) {
  // Update the stored orders
  window.currentOrders = newOrders;

  // Re-apply filters to refresh the display
  applyFilters();
}

/* ═════════════════════════════════════════════════════════════ */
/* PAYMENT ACTIONS */
/* ═════════════════════════════════════════════════════════════ */

function approvePayment() {
  if (!window.currentOrderNumber) return;
  if (
    !confirm(
      "Approve this payment? This will mark the order as paid and delivered.",
    )
  )
    return;

  showToast("Approving payment...");

  fetch("../../api/admin_site/order_processes/approve_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: window.currentOrderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment approved successfully");
        fetchOrders();
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to approve payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error approving payment", true);
    });
}

function rejectPayment() {
  if (!window.currentOrderNumber) return;
  if (!confirm("Reject this payment? This will cancel the order.")) return;

  showToast("Rejecting payment...");

  fetch("../../api/admin_site/order_processes/reject_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ order_number: window.currentOrderNumber }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showToast("Payment rejected");
        fetchOrders();
        setTimeout(() => closeOrderModal(), 1000);
      } else {
        showToast(data.message || "Failed to reject payment", true);
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      showToast("Error rejecting payment", true);
    });
}

/* ═════════════════════════════════════════════════════════════ */
/* FILTER HANDLERS */
/* ═════════════════════════════════════════════════════════════ */

function setupFilterHandlers() {
  const searchInput = document.getElementById("orderSearch");
  const statusFilter = document.getElementById("statusFilter");
  const paymentFilter = document.getElementById("paymentFilter");
  const methodFilter = document.getElementById("methodFilter");

  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (statusFilter) statusFilter.addEventListener("change", applyFilters);
  if (paymentFilter) paymentFilter.addEventListener("change", applyFilters);
  if (methodFilter) methodFilter.addEventListener("change", applyFilters);
}

/* ═════════════════════════════════════════════════════════════ */
/* BADGE & STATUS FUNCTIONS */
/* ═════════════════════════════════════════════════════════════ */

function getQuotationStatusBadge(status) {
  const badges = {
    draft:
      '<span class="badge" style="background: #f3f4f6; color: #6b7280;">📄 Draft</span>',
    sent: '<span class="badge pending">📨 Sent</span>',
    accepted: '<span class="badge processing">✓ Accepted</span>',
    expired: '<span class="badge cancelled">⏰ Expired</span>',
    converted: '<span class="badge delivered">🔄 Converted</span>',
  };
  return badges[status] || `<span class="badge">${status || "N/A"}</span>`;
}

function getPaymentMethodBadge(method) {
  const badges = {
    gcash:
      '<span class="badge" style="background: #eff6ff; color: #2563eb;">📱 GCash</span>',
    cash: '<span class="badge" style="background: #f3f4f6; color: #6b7280;">💰 Cash on Delivery</span>',
    card: '<span class="badge" style="background: #f0fdf4; color: #059669;">💳 Card</span>',
    pending: '<span class="badge pending">🔘 Pending</span>',
  };
  return badges[method] || `<span class="badge">${method || "N/A"}</span>`;
}

function getPaymentStatusBadge(status) {
  const badges = {
    paid: '<span class="badge paid">✅ Paid</span>',
    pending: '<span class="badge pending">⏳ Pending</span>',
    failed: '<span class="badge cancelled">❌ Failed</span>',
  };
  return badges[status] || `<span class="badge">${status || "N/A"}</span>`;
}

function getOrderStatusBadge(status) {
  const badges = {
    pending: '<span class="badge pending">⏳ Pending</span>',
    processing: '<span class="badge processing">⚙️ Processing</span>',
    packed: '<span class="badge packed">📦 Packed</span>',
    shipped: '<span class="badge shipped">🚚 Shipped</span>',
    delivered: '<span class="badge delivered">✅ Delivered</span>',
    cancelled: '<span class="badge cancelled">❌ Cancelled</span>',
  };
  return badges[status] || `<span class="badge">${status || "N/A"}</span>`;
}

/* ═════════════════════════════════════════════════════════════ */
/* UTILITY FUNCTIONS */
/* ═════════════════════════════════════════════════════════════ */

function escapeHtml(str) {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function formatDate(dateString) {
  if (!dateString) return "N/A";
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
    });
  } catch {
    return dateString;
  }
}

function showToast(message, isError = false) {
  let container = document.getElementById("toast-container");
  if (!container) {
    container = document.createElement("div");
    container.id = "toast-container";
    document.body.appendChild(container);
  }

  const toast = document.createElement("div");
  toast.className = `toast ${isError ? "error" : "success"}`;
  toast.textContent = message;

  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("show");
  }, 10);

  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}
