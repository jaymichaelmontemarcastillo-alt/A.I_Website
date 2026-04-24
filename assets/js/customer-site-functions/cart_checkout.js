/**
 * assets/js/customer-site-functions/cart-checkout.js
 * Handles: remove from cart, update quantity, checkout modal, GCash upload, receipt
 */

// ── Utility: Email & Phone Validation ──────────────────────────────────────
function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
  return /^(09|\+639)\d{9}$/.test(phone.replace(/\s/g, ""));
}

// ── Remove from Cart ────────────────────────────────────────────────────────
async function removeFromCart(productId) {
  const confirmed = await notif.confirm({
    title: "Remove from Cart",
    message: "Are you sure you want to remove this item from your cart?",
    type: "warning",
    confirmText: "Remove",
    confirmClass: "danger",
    cancelText: "Keep",
  });
  if (!confirmed) return;

  const button = event.currentTarget;
  const originalIcon = button.innerHTML;
  button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
  button.disabled = true;

  const loading = notif.loading("Removing item from cart...");
  const formData = new FormData();
  formData.append("id", productId);

  try {
    const res = await fetch("api/remove_cart.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    loading.hide();

    if (data.success) {
      notif.toast("Item removed from cart", "success");
      setTimeout(() => location.reload(), 1000);
    } else {
      notif.toast(data.error || "Failed to remove item", "error");
      button.innerHTML = originalIcon;
      button.disabled = false;
    }
  } catch (err) {
    loading.hide();
    console.error("removeFromCart error:", err);
    notif.toast("Failed to remove item", "error");
    button.innerHTML = originalIcon;
    button.disabled = false;
  }
}

// ── Update Quantity ─────────────────────────────────────────────────────────
async function updateQuantity(productId, action) {
  const loading = notif.loading("Updating cart...");

  try {
    const res = await fetch("api/update_cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: productId, action }),
    });
    const data = await res.json();
    loading.hide();

    if (data.success) {
      notif.toast("Cart updated", "success");
      setTimeout(() => location.reload(), 500);
    } else {
      notif.toast(data.error || "Failed to update cart", "error");
    }
  } catch (err) {
    loading.hide();
    console.error("updateQuantity error:", err);
    notif.toast("Failed to update cart", "error");
  }
}

// ── Show / Hide GCash Section on Payment Method Change ─────────────────────
function initPaymentToggle() {
  const radios = document.querySelectorAll('input[name="modalPaymentMethod"]');
  const gcashSection = document.getElementById("gcashProofContainer");
  if (!gcashSection) return;

  radios.forEach((radio) => {
    radio.addEventListener("change", function () {
      gcashSection.style.display = this.value === "gcash" ? "block" : "none";
      // Clear GCash fields when hidden
      if (this.value !== "gcash") {
        document.getElementById("gcashReferenceNumber").value = "";
        document.getElementById("gcashProofImage").value = "";
      }
    });
  });
}

// ── Show Receipt Modal ──────────────────────────────────────────────────────
function showReceipt(orderData) {
  const receiptDetails = document.getElementById("receiptDetails");
  const receiptItems = document.getElementById("receiptItems");
  const checkoutModal = document.getElementById("checkoutModal");
  const receiptModal = document.getElementById("receiptModal");

  const orderDate = new Date().toLocaleString("en-PH", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });

  receiptDetails.innerHTML = `
        <div class="receipt-row">
            <span class="receipt-label">Order Number:</span>
            <span class="receipt-value order-number">${orderData.order_number}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Order Date:</span>
            <span class="receipt-value">${orderDate}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Customer Name:</span>
            <span class="receipt-value">${orderData.customerName}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Email:</span>
            <span class="receipt-value">${orderData.customerEmail}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Phone:</span>
            <span class="receipt-value">${orderData.customerPhone}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Payment Method:</span>
            <span class="receipt-value">${orderData.paymentMethod.toUpperCase()}</span>
        </div>
        ${
          orderData.paymentMethod === "gcash" && orderData.referenceNumber
            ? `
        <div class="receipt-row">
            <span class="receipt-label">GCash Reference:</span>
            <span class="receipt-value">${orderData.referenceNumber}</span>
        </div>`
            : ""
        }
    `;

  let itemsHtml = "<h4>Items Ordered:</h4>";
  orderData.items.forEach((item) => {
    itemsHtml += `
            <div class="receipt-item">
                <span class="receipt-item-name">${item.name} × ${item.quantity}</span>
                <span class="receipt-item-price">₱${(item.price * item.quantity).toFixed(2)}</span>
            </div>`;
  });
  itemsHtml += `
        <div class="receipt-total">
            <span>TOTAL AMOUNT:</span>
            <span>₱${orderData.total.toFixed(2)}</span>
        </div>`;

  receiptItems.innerHTML = itemsHtml;

  checkoutModal.style.display = "none";
  receiptModal.style.display = "block";
}

// ── Print Receipt ───────────────────────────────────────────────────────────
function printReceipt() {
  const receiptContent = document
    .getElementById("receiptModal")
    .cloneNode(true);
  const printWindow = window.open("", "_blank");

  printWindow.document.write(`
        <html>
        <head>
            <title>Order Receipt</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; }
                .receipt-header { text-align: center; margin-bottom: 30px; }
                .receipt-header i { font-size: 60px; color: #4CAF50; }
                .receipt-details { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                .receipt-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
                .receipt-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #ddd; }
                .receipt-total { background: #0f3d67; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; margin-top: 15px; }
                .order-number { font-family: monospace; font-size: 18px; }
                @media print { body { padding: 20px; } .no-print { display: none; } }
            </style>
        </head>
        <body>
            ${receiptContent.querySelector(".receipt-modal-body").innerHTML}
            <div style="text-align:center;margin-top:30px;" class="no-print">
                <button onclick="window.print()">Print</button>
                <button onclick="window.close()">Close</button>
            </div>
        </body>
        </html>
    `);
  printWindow.document.close();
}

// ── Continue Shopping ───────────────────────────────────────────────────────
function continueShopping() {
  document.getElementById("receiptModal").style.display = "none";
  window.location.href = "shop.php";
}

// ── Close Receipt Modal ─────────────────────────────────────────────────────
function closeReceiptModal() {
  document.getElementById("receiptModal").style.display = "none";
}

// ── processCheckout: Sends FormData (supports file upload) ──────────────────
async function processCheckout(customerData, cart, totalAmount, confirmBtn) {
  const referenceNumber =
    document.getElementById("gcashReferenceNumber")?.value?.trim() || "";
  const proofImageFile =
    document.getElementById("gcashProofImage")?.files[0] || null;

  // GCash client-side validation before sending
  if (customerData.paymentMethod === "gcash") {
    if (!referenceNumber) {
      notif.toast("Please enter your GCash reference number", "warning");
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Confirm Order";
      return;
    }
    if (!proofImageFile) {
      notif.toast("Please upload your GCash payment screenshot", "warning");
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Confirm Order";
      return;
    }
  }

  // Build FormData — the ONLY correct way when sending a file
  const formData = new FormData();
  formData.append("customerName", customerData.customerName);
  formData.append("customerEmail", customerData.customerEmail);
  formData.append("customerPhone", customerData.customerPhone);
  formData.append("paymentMethod", customerData.paymentMethod);
  formData.append("total", totalAmount);
  formData.append(
    "items",
    JSON.stringify(
      cart.map((item) => ({
        id: parseInt(item.id),
        name: item.name || "Unknown Product",
        quantity: parseInt(item.quantity) || 1,
        price: parseFloat(item.price) || 0,
      })),
    ),
  );

  if (customerData.paymentMethod === "gcash") {
    formData.append("reference_number", referenceNumber);
    formData.append("proof_image", proofImageFile);
  }

  const loading = notif.loading("Placing your order...");

  try {
    // ── Step 1: Save order (FormData — NO Content-Type header) ──────────
    const orderRes = await fetch("api/save_order.php", {
      method: "POST",
      body: formData, // ← DO NOT set Content-Type manually
    });

    if (!orderRes.ok) throw new Error("Network error: " + orderRes.status);
    const orderResult = await orderRes.json();

    if (!orderResult.success) {
      loading.hide();
      notif.toast(orderResult.error || "Failed to place order", "error");
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Confirm Order";
      return;
    }

    // ── Step 2: Clear cart ───────────────────────────────────────────────
    try {
      await fetch("api/clear_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
      });
    } catch (clearErr) {
      console.warn("Cart clear failed (non-fatal):", clearErr);
    }

    loading.hide();
    notif.toast("Order placed successfully!", "success");

    // Build orderData for receipt
    const orderData = {
      order_number: orderResult.order_number,
      customerName: customerData.customerName,
      customerEmail: customerData.customerEmail,
      customerPhone: customerData.customerPhone,
      paymentMethod: customerData.paymentMethod,
      referenceNumber: referenceNumber || null,
      total: parseFloat(totalAmount),
      items: cart.map((item) => ({
        name: item.name,
        quantity: parseInt(item.quantity),
        price: parseFloat(item.price),
      })),
    };

    showReceipt(orderData);
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Confirm Order";
  } catch (err) {
    loading.hide();
    console.error("processCheckout error:", err);
    notif.toast("Error placing order: " + err.message, "error");
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Confirm Order";
  }
}

// ── Init Checkout Modal ─────────────────────────────────────────────────────
function initCheckout(cart, totalAmount) {
  const checkoutModal = document.getElementById("checkoutModal");
  const receiptModal = document.getElementById("receiptModal");
  const checkoutBtn = document.getElementById("checkoutBtn");
  const confirmBtn = document.getElementById("confirmCheckoutBtn");
  const closeModal = document.querySelector("#checkoutModal .close-modal");
  const cancelBtn = document.querySelector(".cancel-btn");

  function closeCheckoutModal() {
    checkoutModal.style.display = "none";
  }

  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", () => {
      if (!cart || cart.length === 0) {
        notif.toast("Your cart is empty", "warning");
        return;
      }
      // Reset form
      document.getElementById("modalCustomerName").value = "";
      document.getElementById("modalCustomerEmail").value = "";
      document.getElementById("modalCustomerPhone").value = "";
      document.querySelector(
        'input[name="modalPaymentMethod"][value="cash"]',
      ).checked = true;
      document.getElementById("gcashProofContainer").style.display = "none";

      checkoutModal.style.display = "block";
    });
  }

  if (closeModal) closeModal.addEventListener("click", closeCheckoutModal);
  if (cancelBtn) cancelBtn.addEventListener("click", closeCheckoutModal);

  window.addEventListener("click", (e) => {
    if (e.target === checkoutModal) closeCheckoutModal();
    if (e.target === receiptModal) closeReceiptModal();
  });

  if (confirmBtn) {
    confirmBtn.addEventListener("click", async () => {
      const customerName = document
        .getElementById("modalCustomerName")
        .value.trim();
      const customerEmail = document
        .getElementById("modalCustomerEmail")
        .value.trim();
      const customerPhone = document
        .getElementById("modalCustomerPhone")
        .value.trim();
      const paymentMethod = document.querySelector(
        'input[name="modalPaymentMethod"]:checked',
      )?.value;

      if (!paymentMethod) {
        notif.toast("Please select a payment method", "warning");
        return;
      }
      if (!customerName || !customerEmail || !customerPhone) {
        notif.toast("Please fill in all required fields", "warning");
        return;
      }
      if (!validateEmail(customerEmail)) {
        notif.toast("Please enter a valid email address", "warning");
        return;
      }
      if (!validatePhone(customerPhone)) {
        notif.toast(
          "Please enter a valid phone number (e.g. 09123456789)",
          "warning",
        );
        return;
      }

      const confirmed = await notif.confirm({
        title: "Confirm Order",
        message: "Are you sure you want to place this order?",
        type: "info",
        confirmText: "Place Order",
        confirmClass: "confirm",
        cancelText: "Review",
      });
      if (!confirmed) return;

      confirmBtn.disabled = true;
      confirmBtn.textContent = "Processing...";

      await processCheckout(
        {
          customerName,
          customerEmail,
          customerPhone: customerPhone.replace(/\s/g, ""),
          paymentMethod,
        },
        cart,
        totalAmount,
        confirmBtn,
      );
    });
  }
}

// ── Bootstrap on DOMContentLoaded ──────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initPaymentToggle();
  // cart and totalAmount must be declared as globals in the cart.php <script> block
  if (typeof cart !== "undefined" && typeof totalAmount !== "undefined") {
    initCheckout(cart, totalAmount);
  }
});
