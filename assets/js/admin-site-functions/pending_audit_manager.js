/**
 * Pending Audit Manager
 * Handles the pending audit modal and workflow for non-audited quotations
 * OPTIMIZED - No page scrolling, immediate modal display
 * UPDATED - Opens audit modal directly without redirect
 */

class PendingAuditManager {
  constructor() {
    this.currentQuotationId = null;
    this.currentQuotationNumber = null;
    this.currentQuotationClient = null;
    this.pendingQuotations = [];
    this.modalShown = false;
    this.init();
  }

  init() {
    // Check immediately without waiting for DOMContentLoaded if possible
    this.checkPendingAuditFromStorage();

    // Also listen for DOMContentLoaded as fallback
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => {
        this.checkPendingAuditFromStorage();
      });
    }
  }

  checkPendingAuditFromStorage() {
    // Prevent multiple triggers
    if (this.modalShown) return;

    const pendingId = sessionStorage.getItem("pending_audit_quotation_id");
    const pendingNumber = sessionStorage.getItem(
      "pending_audit_quotation_number",
    );

    if (pendingId && pendingNumber) {
      // Mark as shown immediately to prevent duplicates
      this.modalShown = true;

      // Clear storage immediately
      sessionStorage.removeItem("pending_audit_quotation_id");
      sessionStorage.removeItem("pending_audit_quotation_number");
      sessionStorage.removeItem("pending_audit_timestamp");

      // Show modal immediately - no delay, no scroll
      this.showPendingAuditModalImmediately(pendingId, pendingNumber);
    }
  }

  async showPendingAuditModalImmediately(highlightId, highlightNumber) {
    // Create modal DOM elements immediately
    this.createModal();

    // Show modal skeleton immediately with loading state
    const modal = document.getElementById("pendingAuditModal");
    if (modal) {
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";

      // Prevent page scroll - lock body position
      document.body.style.position = "fixed";
      document.body.style.top = `-${window.scrollY}px`;
      document.body.style.width = "100%";
    }

    // Load data in background
    await this.loadPendingQuotations();

    if (this.pendingQuotations.length === 0) {
      this.closeModal();
      if (typeof matShowToast === "function") {
        matShowToast(
          "No pending audits found. All quotations have been audited.",
          "info",
        );
      }
      return;
    }

    // Render the list
    this.renderQuotationList(highlightId);

    // If highlight ID provided, auto-select it immediately
    if (highlightId) {
      const selectedQuotation = this.pendingQuotations.find(
        (q) => q.id == highlightId,
      );
      if (selectedQuotation) {
        // Auto-select immediately without delay
        setTimeout(() => {
          this.selectQuotation(highlightId);
        }, 100);
      }
    }
  }

  async loadPendingQuotations() {
    try {
      const response = await fetch(
        "../../api/admin_site/get_pending_audit_quotations.php",
      );
      const result = await response.json();

      if (result.success) {
        this.pendingQuotations = result.quotations;
      } else {
        this.pendingQuotations = [];
      }
    } catch (err) {
      console.error("Error loading pending quotations:", err);
      this.pendingQuotations = [];
    }
  }

  createModal() {
    // Check if modal already exists
    if (document.getElementById("pendingAuditModal")) {
      return;
    }

    const modalHtml = `
            <div id="pendingAuditModal" class="pending-audit-modal" style="display:none;">
                <div class="pending-audit-container">
                    <div class="pending-audit-header">
                        <h3><i class="fa-solid fa-clipboard-list"></i> Pending Audit Quotations</h3>
                        <button class="pending-audit-close" onclick="pendingAuditManager.closeModal()">&times;</button>
                    </div>
                    <div class="pending-audit-body">
                        <p class="pending-audit-desc">
                            <i class="fa-solid fa-info-circle"></i> 
                            Select a quotation to create an inventory audit for material consumption tracking.
                        </p>
                        <div class="pending-audit-search">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" id="pendingAuditSearchInput" placeholder="Search by quote number or customer..." onkeyup="pendingAuditManager.filterList()">
                        </div>
                        <div class="pending-audit-list" id="pendingAuditList">
                            <div class="pending-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</div>
                        </div>
                    </div>
                    <div class="pending-audit-footer">
                        <button class="pending-audit-btn cancel" onclick="pendingAuditManager.closeModal()">
                            <i class="fa-solid fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    this.injectStyles();
  }

  injectStyles() {
    if (document.getElementById("pendingAuditStyles")) return;

    const styles = `
            <style id="pendingAuditStyles">
                .pending-audit-modal {
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.6);
                    z-index: 100000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                    animation: fadeIn 0.15s ease;
                }
                .pending-audit-container {
                    background: #fff;
                    border-radius: 16px;
                    width: 100%;
                    max-width: 750px;
                    max-height: 85vh;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
                    animation: slideUp 0.2s ease;
                }
                .pending-audit-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 18px 24px;
                    background: linear-gradient(135deg, #2563eb, #1e40af);
                    border-radius: 16px 16px 0 0;
                    color: white;
                }
                .pending-audit-header h3 {
                    margin: 0;
                    font-size: 1.2rem;
                    font-weight: 600;
                }
                .pending-audit-header h3 i {
                    margin-right: 8px;
                }
                .pending-audit-close {
                    background: rgba(255,255,255,0.2);
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: white;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    transition: all 0.2s;
                }
                .pending-audit-close:hover {
                    background: rgba(255,255,255,0.3);
                    transform: rotate(90deg);
                }
                .pending-audit-body {
                    flex: 1;
                    overflow-y: auto;
                    padding: 20px 24px;
                }
                .pending-audit-desc {
                    font-size: 13px;
                    color: #6b7280;
                    margin-bottom: 16px;
                    padding: 10px 14px;
                    background: #eff6ff;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .pending-audit-desc i {
                    color: #2563eb;
                }
                .pending-audit-search {
                    position: relative;
                    margin-bottom: 20px;
                }
                .pending-audit-search i {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #9ca3af;
                }
                .pending-audit-search input {
                    width: 100%;
                    padding: 10px 12px 10px 36px;
                    border: 1px solid #d1d5db;
                    border-radius: 10px;
                    font-size: 14px;
                    outline: none;
                }
                .pending-audit-search input:focus {
                    border-color: #2563eb;
                    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
                }
                .pending-audit-list {
                    max-height: 450px;
                    overflow-y: auto;
                }
                .pending-audit-item {
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 16px;
                    margin-bottom: 12px;
                    cursor: pointer;
                    transition: all 0.2s;
                    background: #fff;
                }
                .pending-audit-item:hover {
                    border-color: #2563eb;
                    background: #eff6ff;
                    transform: translateX(4px);
                }
                .pending-audit-item.selected {
                    border-color: #2563eb;
                    background: #dbeafe;
                }
                .pending-audit-item-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                    flex-wrap: wrap;
                    gap: 8px;
                }
                .pending-audit-quote-num {
                    font-weight: 700;
                    color: #1e40af;
                    font-size: 14px;
                }
                .pending-audit-quote-num i {
                    margin-right: 6px;
                }
                .pending-audit-date {
                    font-size: 11px;
                    color: #6b7280;
                }
                .pending-audit-client {
                    font-weight: 600;
                    color: #374151;
                    margin-bottom: 8px;
                    font-size: 14px;
                }
                .pending-audit-client i {
                    margin-right: 6px;
                    color: #6b7280;
                }
                .pending-audit-details {
                    display: flex;
                    gap: 15px;
                    font-size: 12px;
                    color: #6b7280;
                    flex-wrap: wrap;
                }
                .pending-audit-details i {
                    margin-right: 4px;
                    width: 14px;
                }
                .pending-audit-total {
                    font-weight: 700;
                    color: #059669;
                }
                .pending-loading, .pending-empty {
                    text-align: center;
                    padding: 40px;
                    color: #9ca3af;
                }
                .pending-loading i, .pending-empty i {
                    font-size: 48px;
                    margin-bottom: 12px;
                    display: block;
                }
                .pending-audit-footer {
                    padding: 16px 24px;
                    border-top: 1px solid #e5e7eb;
                    display: flex;
                    justify-content: flex-end;
                }
                .pending-audit-btn {
                    padding: 10px 24px;
                    border-radius: 10px;
                    font-size: 14px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                    border: none;
                }
                .pending-audit-btn.cancel {
                    background: #f3f4f6;
                    color: #374151;
                }
                .pending-audit-btn.cancel:hover {
                    background: #e5e7eb;
                }
                /* Audit Header Info Banner */
                .audit-quotation-info {
                    background: linear-gradient(135deg, #1e3a5f, #2563eb);
                    color: white;
                    padding: 12px 20px;
                    margin: -24px -28px 24px -28px;
                    border-radius: 0 0 12px 12px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .audit-quotation-info .info-label {
                    font-size: 12px;
                    opacity: 0.8;
                    margin-right: 8px;
                }
                .audit-quotation-info .info-value {
                    font-weight: 700;
                    font-size: 14px;
                }
                .audit-quotation-badge {
                    background: rgba(255,255,255,0.2);
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 500;
                }
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from { transform: translateY(20px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            </style>
        `;
    document.head.insertAdjacentHTML("beforeend", styles);
  }

  renderQuotationList(highlightId = null) {
    const listContainer = document.getElementById("pendingAuditList");
    if (!listContainer) return;

    if (!this.pendingQuotations || this.pendingQuotations.length === 0) {
      listContainer.innerHTML = `
                <div class="pending-empty">
                    <i class="fa-solid fa-check-circle"></i>
                    <p>No pending audits! All delivered quotations have been audited.</p>
                </div>
            `;
      return;
    }

    listContainer.innerHTML = this.pendingQuotations
      .map(
        (quote) => `
            <div class="pending-audit-item ${highlightId == quote.id ? "selected" : ""}" 
                 data-id="${quote.id}" 
                 data-number="${this.escapeHtml(quote.quote_number)}"
                 onclick="pendingAuditManager.selectQuotation(${quote.id})">
                <div class="pending-audit-item-header">
                    <span class="pending-audit-quote-num">
                        <i class="fa-solid fa-file-invoice"></i> ${this.escapeHtml(quote.quote_number)}
                    </span>
                    <span class="pending-audit-date">
                        <i class="fa-regular fa-calendar"></i> ${new Date(quote.created_at).toLocaleDateString()}
                    </span>
                </div>
                <div class="pending-audit-client">
                    <i class="fa-solid fa-building"></i> ${this.escapeHtml(quote.client_name)}
                </div>
                <div class="pending-audit-details">
                    <span><i class="fa-regular fa-user"></i> ${this.escapeHtml(quote.contact_person)}</span>
                    <span><i class="fa-regular fa-envelope"></i> ${this.escapeHtml(quote.email)}</span>
                    <span><i class="fa-solid fa-phone"></i> ${this.escapeHtml(quote.phone)}</span>
                    <span class="pending-audit-total"><i class="fa-solid fa-money-bill"></i> ₱ ${parseFloat(quote.total || 0).toFixed(2)}</span>
                </div>
            </div>
        `,
      )
      .join("");
  }

  filterList() {
    const searchTerm =
      document.getElementById("pendingAuditSearchInput")?.value.toLowerCase() ||
      "";

    if (!searchTerm) {
      this.renderQuotationList();
      return;
    }

    const filtered = this.pendingQuotations.filter(
      (quote) =>
        quote.quote_number.toLowerCase().includes(searchTerm) ||
        quote.client_name.toLowerCase().includes(searchTerm) ||
        (quote.contact_person &&
          quote.contact_person.toLowerCase().includes(searchTerm)) ||
        (quote.email && quote.email.toLowerCase().includes(searchTerm)),
    );

    const listContainer = document.getElementById("pendingAuditList");
    if (!listContainer) return;

    if (filtered.length === 0) {
      listContainer.innerHTML = `<div class="pending-empty"><i class="fa-solid fa-search"></i><p>No matching quotations found</p></div>`;
      return;
    }

    listContainer.innerHTML = filtered
      .map(
        (quote) => `
            <div class="pending-audit-item" data-id="${quote.id}" onclick="pendingAuditManager.selectQuotation(${quote.id})">
                <div class="pending-audit-item-header">
                    <span class="pending-audit-quote-num"><i class="fa-solid fa-file-invoice"></i> ${this.escapeHtml(quote.quote_number)}</span>
                    <span class="pending-audit-date"><i class="fa-regular fa-calendar"></i> ${new Date(quote.created_at).toLocaleDateString()}</span>
                </div>
                <div class="pending-audit-client"><i class="fa-solid fa-building"></i> ${this.escapeHtml(quote.client_name)}</div>
                <div class="pending-audit-details">
                    <span><i class="fa-regular fa-user"></i> ${this.escapeHtml(quote.contact_person)}</span>
                    <span><i class="fa-regular fa-envelope"></i> ${this.escapeHtml(quote.email)}</span>
                    <span><i class="fa-solid fa-phone"></i> ${this.escapeHtml(quote.phone)}</span>
                    <span class="pending-audit-total"><i class="fa-solid fa-money-bill"></i> ₱ ${parseFloat(quote.total || 0).toFixed(2)}</span>
                </div>
            </div>
        `,
      )
      .join("");
  }

  // MODIFIED: Opens audit modal directly without redirect to Inventory.php
  async selectQuotation(quotationId) {
    // Update selected state in UI
    document.querySelectorAll(".pending-audit-item").forEach((item) => {
      item.classList.remove("selected");
    });
    const selectedItem = document.querySelector(
      `.pending-audit-item[data-id="${quotationId}"]`,
    );
    if (selectedItem) {
      selectedItem.classList.add("selected");
    }

    this.currentQuotationId = quotationId;

    // Close the pending audit modal
    this.closeModal();

    // Open audit modal directly (no redirect)
    await this.openAuditModalDirectly(quotationId);
  }

  // NEW: Open audit modal directly without page redirect
  async openAuditModalDirectly(quotationId) {
    if (typeof matShowToast === "function") {
      matShowToast("Loading audit form...", "info");
    }

    // Check if we can open audit modal
    if (typeof window.matOpenAuditModal === "function") {
      // Store the quotation ID for reference in the audit submission
      window.currentAuditQuotationId = quotationId;

      // Get quotation details for pre-filling
      try {
        const response = await fetch(
          `../../api/admin_site/get_single_quotation.php?id=${quotationId}`,
        );
        const result = await response.json();

        if (result.success) {
          window.currentAuditQuotationNumber =
            result.data.quotation.quote_number;
        }
      } catch (err) {
        console.error("Error fetching quotation details:", err);
      }

      // Open audit modal
      window.matOpenAuditModal();

      // Pre-fill with quotation info after modal opens
      setTimeout(() => {
        const auditModal = document.getElementById("auditModal");
        if (auditModal && auditModal.style.display === "flex") {
          const itemNameInput = document.getElementById("auditItemName");
          if (
            itemNameInput &&
            !itemNameInput.value &&
            window.currentAuditQuotationNumber
          ) {
            itemNameInput.value = `Audit for ${window.currentAuditQuotationNumber}`;
          }
          const createdByInput = document.getElementById("createdBy");
          if (createdByInput && !createdByInput.value) {
            createdByInput.value = "Quotation System";
          }
        }
      }, 500);

      if (typeof matShowToast === "function") {
        matShowToast(
          "Audit form opened. Please add materials used and complete the audit.",
          "success",
        );
      }
    } else {
      // Fallback: show notification
      if (typeof matShowToast === "function") {
        matShowToast(
          "Audit module not available. Please create audit manually from Inventory page.",
          "error",
        );
      }
    }
  }

  // Legacy method - kept for compatibility but no longer redirects
  async openAuditModalWithQuotation(quotationId, quoteNumber) {
    // This now calls the direct method
    await this.openAuditModalDirectly(quotationId);
  }

  addQuotationInfoToAuditHeader(quotation) {
    // Find the audit modal header and add quotation info banner
    const auditModal = document.getElementById("auditModal");
    if (!auditModal) return;

    const modalContainer = auditModal.querySelector(".audit-modal-container");
    if (!modalContainer) return;

    // Remove existing info banner if any
    const existingBanner = modalContainer.querySelector(
      ".audit-quotation-info",
    );
    if (existingBanner) existingBanner.remove();

    // Find the header
    const header = modalContainer.querySelector(".audit-modal-header");
    if (!header) return;

    // Create info banner with enhanced UI
    const infoBanner = document.createElement("div");
    infoBanner.className = "audit-quotation-info";
    infoBanner.innerHTML = `
        <div class="audit-info">
            <div class="audit-info-row">
                <span class="info-label"><i class="fa-solid fa-file-invoice"></i> Quotation:</span>
                <span class="info-value">${this.escapeHtml(quotation.quote_number)}</span>
            </div>
            <div class="audit-info-row">
                <span class="info-label"><i class="fa-solid fa-building"></i> Client:</span>
                <span class="info-value">${this.escapeHtml(quotation.client_name)}</span>
            </div>
            <div class="audit-info-row">
                <span class="info-label"><i class="fa-solid fa-calendar"></i> Date:</span>
                <span class="info-value">${new Date(quotation.created_at).toLocaleDateString()}</span>
            </div>
            <div class="audit-quotation-badge">
                <i class="fa-solid fa-clipboard-list fa-fw"></i> 
                Pending Audit
                <i class="fa-solid fa-bell fa-fw" style="font-size: 11px;"></i>
            </div>
        </div>
    `;

    // Insert after header
    header.insertAdjacentElement("afterend", infoBanner);
  }

  prefillAuditModal(quotation, items) {
    // Leave item name field empty for user to fill
    const itemNameInput = document.getElementById("auditItemName");
    if (itemNameInput && !itemNameInput.value) {
      itemNameInput.value = "";
    }

    // Pre-fill created by with client name
    const createdByInput = document.getElementById("createdBy");
    if (createdByInput && quotation.client_name && !createdByInput.value) {
      createdByInput.value = quotation.client_name;
    }

    // Clear existing items
    const itemsContainer = document.getElementById("itemsContainer");
    if (itemsContainer) {
      itemsContainer.innerHTML = "";
    }

    // Add items from quotation to the items list
    if (items && items.length > 0) {
      items.forEach((item) => {
        this.addItemToAuditModal({
          name: item.description,
          quantity: item.quantity,
          unit_price: item.unit_price,
          total_amount: item.total,
        });
      });

      // Recalculate totals
      if (typeof matComputeTotals === "function") {
        matComputeTotals();
      }
    }

    if (typeof matShowToast === "function") {
      matShowToast(
        `Quotation ${quotation.quote_number} loaded. Please add materials used and complete the audit.`,
        "success",
      );
    }
  }

  addItemToAuditModal(itemData) {
    const container = document.getElementById("itemsContainer");
    if (!container) return;

    const rowId = Date.now() + Math.random();
    const row = document.createElement("div");
    row.className = "dynamic-row";
    row.dataset.id = rowId;
    row.innerHTML = `
            <input type="text" class="item-name" placeholder="Item name" value="${this.escapeHtml(itemData.name || "")}" style="flex:2;" oninput="matUpdateItemTotal(this)">
            <input type="number" class="item-qty" placeholder="QTY" value="${itemData.quantity || 0}" style="flex:1;" oninput="matUpdateItemTotal(this)">
            <input type="number" class="item-unit-price" placeholder="Unit Price" step="0.01" value="${itemData.unit_price || 0}" style="flex:1;" oninput="matUpdateItemTotal(this)">
            <input type="number" class="item-total-amount" placeholder="Total Amount" step="0.01" value="${itemData.total_amount || 0}" style="flex:1;" readonly>
            <button class="remove-row" onclick="matRemoveDynamicRow(this)"><i class="fa-solid fa-trash"></i></button>
        `;
    container.appendChild(row);
  }

  closeModal() {
    const modal = document.getElementById("pendingAuditModal");
    if (modal) {
      modal.style.display = "none";
      // Restore body scroll
      document.body.style.overflow = "";
      document.body.style.position = "";
      document.body.style.top = "";
      document.body.style.width = "";
    }
    this.currentQuotationId = null;
  }

  escapeHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }
}

// Initialize the manager immediately
window.pendingAuditManager = new PendingAuditManager();
