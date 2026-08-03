/**
 * Quotation View Manager
 * Handles: viewing, editing, rich text formatting, item management
 */
class QuotationViewManager {
  constructor() {
    this.currentQuoteId = null;
    this.isEditMode = false;
    this.originalData = null;
    this.rowCounter = 0;
  }

  openModal(quoteId) {
    this.currentQuoteId = quoteId;
    this.isEditMode = false;
    this.modal = document.getElementById("QuotationViewModal");

    if (!this.modal) return;

    this.modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    this.loadQuotationData(quoteId);
  }

  closeModal() {
    if (this.modal) this.modal.style.display = "none";
    document.body.style.overflow = "";
    this.isEditMode = false;
    this.currentQuoteId = null;
  }

  async loadQuotationData(quoteId) {
    this.showLoading(true);

    try {
      const response = await fetch(
        `../../api/admin_site/get_single_quotation.php?id=${quoteId}`,
      );
      const result = await response.json();

      if (result.success && result.data) {
        this.originalData = result.data;
        this.populateModal(result.data.quotation, result.data.items || []);
        this.setEditMode(false);
      } else {
        this.showNotification(
          result.message || "Failed to load quotation",
          "error",
        );
        this.closeModal();
      }
    } catch (error) {
      console.error("Load error:", error);
      this.showNotification("Error loading quotation", "error");
    } finally {
      this.showLoading(false);
    }
  }

  populateModal(quotation, items) {
    // Client info
    document.getElementById("qvClientName").value = quotation.client_name || "";
    document.getElementById("qvContactPerson").value =
      quotation.contact_person || "";
    document.getElementById("qvEmail").value = quotation.email || "";
    document.getElementById("qvPhone").value = quotation.phone || "";
    document.getElementById("qvQuoteNumber").textContent =
      quotation.quote_number || "—";
    document.getElementById("qvTax").value = quotation.tax || 0;
    document.getElementById("qvDiscount").value = quotation.discount || 0;
    document.getElementById("qvNotes").value = quotation.notes || "";
    document.getElementById("qvStatus").value = quotation.status || "draft";

    // Items table
    const tbody = document.getElementById("qvItemsBody");
    tbody.innerHTML = "";
    this.rowCounter = 0;

    if (items && items.length > 0) {
      items.forEach((item) => this.addItemRow(item));
    } else {
      this.addItemRow();
    }

    this.recalculateTotals();
  }

  addItemRow(item = {}) {
    const tbody = document.getElementById("qvItemsBody");
    const rowId = ++this.rowCounter;
    const qty = item.quantity ?? "";
    const price = item.unit_price ?? "";
    const total = (parseFloat(qty) * parseFloat(price) || 0).toFixed(2);
    const desc = item.description || "";

    const tr = document.createElement("tr");
    tr.setAttribute("data-row-id", rowId);
    tr.innerHTML = `
            <td class="qv-desc-cell">
                <div class="qv-editor-container">
                    <div class="qv-toolbar qv-row-toolbar" style="display: none;">
                        <button type="button" class="qv-toolbar-btn" onclick="quotationViewManager.formatRowText(${rowId}, 'bold')"><b>B</b></button>
                        <button type="button" class="qv-toolbar-btn" onclick="quotationViewManager.formatRowText(${rowId}, 'insertUnorderedList')">• List</button>
                        <button type="button" class="qv-toolbar-btn" onclick="quotationViewManager.formatRowText(${rowId}, 'indent')">→ Indent</button>
                        <button type="button" class="qv-toolbar-btn" onclick="quotationViewManager.formatRowText(${rowId}, 'outdent')">← Outdent</button>
                    </div>
                    <div class="qv-desc-editor" contenteditable="false" data-row="${rowId}">${this.escapeHtml(desc)}</div>
                </div>
            </td>
            <td><input type="number" class="qv-item-input qv-qty" data-row="${rowId}" value="${qty}" min="0" step="1" readonly></td>
            <td><input type="number" class="qv-item-input qv-price" data-row="${rowId}" value="${price}" min="0" step="0.01" readonly></td>
            <td><span class="qv-row-total" data-row="${rowId}">₱ ${total}</span></td>
            <td><button class="qv-btn-remove" onclick="quotationViewManager.removeRow(${rowId})" style="display: none;"><i class="fa-solid fa-trash"></i></button></td>
        `;
    tbody.appendChild(tr);
    this.recalculateTotals();
  }

  removeRow(rowId) {
    const row = document.querySelector(
      `#qvItemsBody tr[data-row-id="${rowId}"]`,
    );
    if (row && document.querySelectorAll("#qvItemsBody tr").length > 1) {
      row.remove();
      this.recalculateTotals();
    } else {
      this.showNotification("At least one item is required", "warning");
    }
  }

  formatText(command) {
    document.execCommand(command, false, null);
  }

  formatRowText(rowId, command) {
    const editor = document.querySelector(
      `.qv-desc-editor[data-row="${rowId}"]`,
    );
    if (editor) {
      editor.focus();
      document.execCommand(command, false, null);
    }
  }

  recalculateTotals() {
    let subtotal = 0;

    document.querySelectorAll("#qvItemsBody tr").forEach((row) => {
      const qty = parseFloat(row.querySelector(".qv-qty")?.value) || 0;
      const price = parseFloat(row.querySelector(".qv-price")?.value) || 0;
      const total = qty * price;
      const rowId = row.getAttribute("data-row-id");
      const totalSpan = document.querySelector(
        `.qv-row-total[data-row="${rowId}"]`,
      );
      if (totalSpan) totalSpan.textContent = `₱ ${total.toFixed(2)}`;
      subtotal += total;
    });

    const tax = parseFloat(document.getElementById("qvTax")?.value) || 0;
    const discount =
      parseFloat(document.getElementById("qvDiscount")?.value) || 0;
    const grandTotal = subtotal + tax - discount;

    document.getElementById("qvSubtotal").textContent =
      `₱ ${subtotal.toFixed(2)}`;
    document.getElementById("qvGrandTotal").textContent =
      `₱ ${grandTotal.toFixed(2)}`;
  }

  toggleEdit() {
    this.isEditMode = !this.isEditMode;
    this.setEditMode(this.isEditMode);
  }

  setEditMode(editMode) {
    const isEditing = editMode === true;

    // Toggle input readonly states
    const inputs = [
      "qvClientName",
      "qvContactPerson",
      "qvEmail",
      "qvPhone",
      "qvTax",
      "qvDiscount",
      "qvNotes",
    ];
    inputs.forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.readOnly = !isEditing;
    });

    // Toggle status select
    const statusSelect = document.getElementById("qvStatus");
    if (statusSelect) statusSelect.disabled = !isEditing;

    // Toggle item inputs
    document.querySelectorAll(".qv-qty, .qv-price").forEach((input) => {
      input.readOnly = !isEditing;
    });

    // Toggle contenteditable on description editors
    document.querySelectorAll(".qv-desc-editor").forEach((editor) => {
      editor.contentEditable = isEditing;
    });

    // Toggle toolbars
    document
      .querySelectorAll(".qv-row-toolbar, #qvToolbar")
      .forEach((toolbar) => {
        toolbar.style.display = isEditing ? "flex" : "none";
      });

    // Toggle remove buttons
    document.querySelectorAll(".qv-btn-remove").forEach((btn) => {
      btn.style.display = isEditing ? "inline-block" : "none";
    });

    // Toggle add item button
    const addBtn = document.querySelector(".qv-btn-add");
    if (addBtn) addBtn.style.display = isEditing ? "inline-flex" : "none";

    // Toggle edit/save buttons
    const editBtn = document.getElementById("qvEditBtn");
    const saveBtn = document.getElementById("qvSaveBtn");
    if (editBtn) editBtn.style.display = isEditing ? "none" : "inline-flex";
    if (saveBtn) saveBtn.style.display = isEditing ? "inline-flex" : "none";

    // Update modal title
    const titleEl = document.getElementById("qvModalTitle");
    if (titleEl)
      titleEl.textContent = isEditing ? "Edit Quotation" : "Quotation Details";
  }

  async saveQuotation() {
    const items = [];
    let hasError = false;

    document.querySelectorAll("#qvItemsBody tr").forEach((row) => {
      const editor = row.querySelector(".qv-desc-editor");
      const desc = editor ? editor.innerHTML.trim() : "";
      const qty = parseFloat(row.querySelector(".qv-qty")?.value) || 0;
      const price = parseFloat(row.querySelector(".qv-price")?.value) || 0;

      if (!desc) {
        hasError = true;
        this.showNotification("Item description cannot be empty", "error");
        return;
      }

      if (qty <= 0 || price <= 0) {
        hasError = true;
        this.showNotification(
          "Quantity and price must be greater than 0",
          "error",
        );
        return;
      }

      items.push({
        description: desc,
        quantity: qty,
        unit_price: price,
      });
    });

    if (hasError || items.length === 0) return;

    const payload = {
      id: this.currentQuoteId,
      client_name: document.getElementById("qvClientName").value,
      contact_person: document.getElementById("qvContactPerson").value,
      email: document.getElementById("qvEmail").value,
      phone: document.getElementById("qvPhone").value,
      tax: parseFloat(document.getElementById("qvTax").value) || 0,
      discount: parseFloat(document.getElementById("qvDiscount").value) || 0,
      notes: document.getElementById("qvNotes").value,
      status: document.getElementById("qvStatus").value,
      items: items,
    };

    this.showNotification("Saving...", "info");

    try {
      const response = await fetch(
        "../../api/admin_site/update_quotation.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        },
      );

      const result = await response.json();

      if (result.success) {
        this.showNotification("Quotation updated successfully!", "success");
        this.isEditMode = false;
        this.setEditMode(false);
        if (window.quotationManager) {
          window.quotationManager.fetchQuotations();
        }
      } else {
        this.showNotification(result.message || "Failed to update", "error");
      }
    } catch (error) {
      console.error("Save error:", error);
      this.showNotification("Error saving quotation", "error");
    }
  }

  generatePDF() {
    if (this.currentQuoteId && window.quotationManager) {
      window.quotationManager.generatePDF(this.currentQuoteId);
    }
  }

  showLoading(show) {
    // Optional loading indicator
  }

  showNotification(message, type) {
    const colors = {
      success: "#10b981",
      error: "#ef4444",
      info: "#3b82f6",
      warning: "#f59e0b",
    };
    const notification = document.createElement("div");
    notification.style.cssText = `position:fixed; bottom:20px; right:20px; z-index:100002; background:${colors[type]}; color:#fff; padding:12px 20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease;`;
    notification.innerHTML = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
  }

  escapeHtml(text) {
    if (!text) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
}
