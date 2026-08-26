// assets/js/admin-site-functions/quotation_audit_integration.js
// Updated with combined Overhead & Electricity dropdown and full edit support

/**
 * Quotation Audit Integration Module
 * Handles quotation selection and audit creation
 */

// ============================================================
// SEARCHABLE SELECT CLASS
// ============================================================
class SearchableSelect {
  constructor(container, options, onSelect, selectedValue = null) {
    this.container = container;
    this.options = options;
    this.onSelect = onSelect;
    this.selectedValue = selectedValue;
    this.filteredOptions = [...options];
    this.isOpen = false;
    this.render();
    this.attachEvents();
  }

  render() {
    const selectedOption = this.options.find(
      (opt) => opt.value == this.selectedValue,
    );

    this.container.innerHTML = `
            <div class="searchable-select">
                <div class="searchable-select-input">
                    <span class="selected-text ${!selectedOption ? "placeholder" : ""}">
                        ${selectedOption ? this.escapeHtml(selectedOption.label) : "Type or choose material..."}
                    </span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="searchable-select-dropdown">
                    <div class="searchable-select-search">
                        <input type="text" placeholder="Search materials..." autocomplete="off">
                    </div>
                    <div class="searchable-select-list"></div>
                </div>
            </div>
        `;

    this.renderList();
  }

  renderList(searchTerm = "") {
    const listContainer = this.container.querySelector(
      ".searchable-select-list",
    );
    if (!listContainer) return;

    let filtered = this.options;
    if (searchTerm) {
      const term = searchTerm.toLowerCase();
      filtered = this.options.filter(
        (opt) =>
          opt.label.toLowerCase().includes(term) ||
          (opt.type && opt.type.toLowerCase().includes(term)),
      );
    }
    this.filteredOptions = filtered;

    if (filtered.length === 0) {
      listContainer.innerHTML =
        '<div class="no-results"><i class="fa-solid fa-search"></i> No materials found</div>';
      return;
    }

    listContainer.innerHTML = filtered
      .map(
        (opt) => `
            <div class="searchable-select-item ${opt.value == this.selectedValue ? "selected" : ""}" data-value="${opt.value}" data-cost="${opt.cost}" data-name="${this.escapeHtml(opt.label)}">
                <div>
                    <div class="item-name">${this.escapeHtml(opt.label)}</div>
                    ${opt.type ? `<div class="item-stock"><i class="fa-solid fa-tag"></i> ${this.escapeHtml(opt.type)}</div>` : ""}
                </div>
                <div class="item-cost">₱${parseFloat(opt.cost || 0).toFixed(2)}</div>
            </div>
        `,
      )
      .join("");

    listContainer
      .querySelectorAll(".searchable-select-item")
      .forEach((item) => {
        item.addEventListener("click", (e) => {
          e.stopPropagation();
          const value = parseInt(item.dataset.value);
          const cost = parseFloat(item.dataset.cost);
          const name = item.dataset.name;
          this.selectedValue = value;

          const selectedText = this.container.querySelector(".selected-text");
          if (selectedText) {
            selectedText.textContent = name;
            selectedText.classList.remove("placeholder");
          }

          if (this.onSelect) {
            this.onSelect(value, cost, name);
          }

          this.close();
          this.renderList(searchTerm);
        });
      });
  }

  attachEvents() {
    const input = this.container.querySelector(".searchable-select-input");
    const searchInput = this.container.querySelector(
      ".searchable-select-search input",
    );

    if (input) {
      input.addEventListener("click", (e) => {
        e.stopPropagation();
        this.toggle();
        setTimeout(() => {
          const search = this.container.querySelector(
            ".searchable-select-search input",
          );
          if (search && this.isOpen) search.focus();
        }, 50);
      });
    }

    if (searchInput) {
      searchInput.addEventListener("input", (e) => {
        this.renderList(e.target.value);
      });
      searchInput.addEventListener("click", (e) => e.stopPropagation());
    }

    document.addEventListener("click", (e) => {
      if (this.container && !this.container.contains(e.target)) {
        this.close();
      }
    });
  }

  toggle() {
    this.isOpen ? this.close() : this.open();
  }

  open() {
    const dropdown = this.container.querySelector(
      ".searchable-select-dropdown",
    );
    const input = this.container.querySelector(".searchable-select-input");
    if (dropdown) {
      dropdown.classList.add("show");
      this.isOpen = true;
      if (input) input.classList.add("open");
      const searchInput = this.container.querySelector(
        ".searchable-select-search input",
      );
      if (searchInput) {
        searchInput.value = "";
        this.renderList("");
      }
    }
  }

  close() {
    const dropdown = this.container.querySelector(
      ".searchable-select-dropdown",
    );
    const input = this.container.querySelector(".searchable-select-input");
    if (dropdown) {
      dropdown.classList.remove("show");
      this.isOpen = false;
      if (input) input.classList.remove("open");
    }
  }

  getValue() {
    return this.selectedValue;
  }

  escapeHtml(str) {
    if (!str) return "";
    return String(str).replace(/[&<>]/g, function (m) {
      if (m === "&") return "&amp;";
      if (m === "<") return "&lt;";
      if (m === ">") return "&gt;";
      return m;
    });
  }
}

// ============================================================
// QUOTATION AUDIT INTEGRATION CLASS
// ============================================================
class QuotationAuditIntegration {
  constructor() {
    this.currentQuotation = null;
    this.currentQuotationItems = [];
    this.currentAuditData = null;
    this.isEditMode = false;
    this.quotationPage = 1;
    this.quotationSearch = "";
    this.materialsList = [];
    this.materialSelectors = [];
    this.rejectSelectors = [];

    this.overheadCategories = [
      { key: "shop_rent", label: "Shop Rent" },
      { key: "fixed_salaries", label: "Fixed Salaries (Admin/Finance)" },
      {
        key: "shop_utilities",
        label: "Shop Utilities (Internet + Base Power)",
      },
      { key: "subscriptions", label: "Subscriptions" },
      { key: "machine_depreciation", label: "Machine Depreciation (Fixed)" },
      {
        key: "maintenance_repair",
        label: "Maintenance & Repair Fund Allocation",
      },
      { key: "marketing_promo", label: "Marketing & Promo Materials" },
    ];

    this.api = {
      getDeliveredQuotations:
        "../../api/admin_site/inventory/get_delivered_quotations.php",
      getQuotationMaterials:
        "../../api/admin_site/inventory/get_quotation_materials_for_audit.php",
      createAudit: "../../api/admin_site/inventory/create_audit.php",
      getMaterials:
        "../../api/admin_site/inventory/get_materials_for_audit.php",
      getAuditData: "../../api/admin_site/inventory/get_audit_data.php",
      updateAudit: "../../api/admin_site/inventory/update_audit.php",
    };

    this.init();
  }

  async init() {
    this.setupQuotationSelectionModal();
    this.setupAuditFormModal();
    this.overrideAuditButton();
    await this.loadMaterials();
  }

  async loadMaterials() {
    try {
      const response = await fetch(this.api.getMaterials);
      const result = await response.json();
      if (result.success) {
        this.materialsList = result.materials || [];
      }
    } catch (error) {
      console.error("Error loading materials:", error);
    }
  }

  setupAuditFormModal() {
    if (!document.getElementById("auditLoadingOverlay")) {
      document.body.insertAdjacentHTML(
        "beforeend",
        `<div id="auditLoadingOverlay" class="qa-loading-overlay">
          <i class="fa-solid fa-spinner fa-spin"></i> Processing...
      </div>`,
      );
    }

    if (!document.getElementById("quotationAuditModal")) {
      const modalHtml = `
              <div id="quotationAuditModal" class="qa-audit-overlay">
                  <div class="qa-audit-modal">
                      <div class="qa-audit-header">
                          <h3><i class="fa-regular fa-file-lines"></i> <span id="qaModalTitle">Create Inventory Audit</span></h3>
                          <button class="qa-audit-close" onclick="quotationAuditIntegration.closeAuditModal()">
                              <i class="fa-solid fa-xmark"></i>
                          </button>
                      </div>

                      <div class="qa-audit-body qa-audit-body-two-col">
                          <!-- LEFT COLUMN -->
                          <div class="qa-audit-col-left">
                              <!-- Audit Container -->
                              <div class="qa-audit-container">
                                  <!-- 1. Audit Name -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-tag"></i> Audit Name</div>
                                      <input type="text" id="qaAuditItemName" class="qa-input-full" placeholder="Enter audit name...">
                                  </div>

                                  <!-- 2. Materials Used -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-cubes"></i> Materials Used</div>
                                      <div class="qa-dynamic-container qa-material-header">
                                          <div class="qa-table-header">
                                              <span>Material Name</span>
                                              <span>Qty</span>
                                              <span>Price</span>
                                              <span>Subtotal</span>
                                              <span></span>
                                          </div>
                                          <div id="qaMaterialCostsContainer"></div>
                                      </div>
                                      <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addMaterialRow()">
                                          <i class="fa-solid fa-plus"></i> Add Material
                                      </button>
                                  </div>

                                  <!-- 3. Rejects / Wastage -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-trash-alt"></i> Rejects / Wastage</div>
                                      <div class="qa-dynamic-container qa-material-header">
                                          <div class="qa-table-header">
                                              <span>Material Name</span>
                                              <span>Qty</span>
                                              <span>Price</span>
                                              <span>Subtotal</span>
                                              <span></span>
                                          </div>
                                          <div id="qaRejectCostsContainer"></div>
                                      </div>
                                      <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addRejectRow()">
                                          <i class="fa-solid fa-plus"></i> Add Reject
                                      </button>
                                  </div>

                                  <!-- 4. Total Sales -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-box"></i> Total Sales</div>
                                      <div class="qa-dynamic-container qa-item-header">
                                          <div class="qa-table-header">
                                              <span>Item Name</span>
                                              <span>Qty</span>
                                              <span>Unit Price</span>
                                              <span>Total</span>
                                              <span></span>
                                          </div>
                                          <div id="qaItemsContainer"></div>
                                      </div>
                                      <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addItemRow()">
                                          <i class="fa-solid fa-plus"></i> Add Item
                                      </button>
                                  </div>

                                  <!-- 5. Overhead Cost -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-coins"></i> Overhead Cost</div>
                                      <div class="qa-dynamic-container qa-overhead-header">
                                          <div class="qa-table-header">
                                              <span>Item</span>
                                              <span>Qty</span>
                                              <span>Unit Cost</span>
                                              <span>Percentage (%)</span>
                                              <span>Total</span>
                                              <span></span>
                                          </div>
                                          <div id="qaOverheadContainer"></div>
                                      </div>
                                      <button type="button" class="qa-add-row-btn" onclick="quotationAuditIntegration.addOverheadRow()">
                                          <i class="fa-solid fa-plus"></i> Add Item
                                      </button>
                                  </div>

                                  <!-- 6. Summary -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-calculator"></i> Summary</div>
                                      <div class="qa-totals-summary">
                                          <div>
                                              <span class="label">Material Cost</span>
                                              <span class="value" id="qaTotalMaterialCost">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Reject Cost</span>
                                              <span class="value" id="qaTotalRejectCost">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Overhead Cost</span>
                                              <span class="value" id="qaTotalOverheadCost">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Total Sales</span>
                                              <span class="value" id="qaTotalAmountDue">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Total Cost</span>
                                              <span class="value" id="qaTotalCost">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Profit</span>
                                              <span class="value" id="qaProfit">₱0.00</span>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- 7. Remarks -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-comment-dots"></i> Remarks</div>
                                      <textarea id="qaRemarks" class="qa-input-full qa-remarks-textarea" rows="3" placeholder="Enter remarks or notes..."></textarea>
                                  </div>
                              </div>
                          </div>

                          <!-- RIGHT COLUMN - Quotation Info + People -->
                          <div class="qa-audit-col-right">
                              <!-- Quotation Info -->
                              <div class="qa-section qa-quotation-info-section">
                                  <div class="qa-section-label"><i class="fa-solid fa-file-invoice"></i> Quotation Details</div>
                                  <div class="qa-quotation-info-item">
                                      <span class="qa-info-label">Quote #</span>
                                      <span class="qa-info-value" id="qaQuoteNumber">—</span>
                                  </div>
                                  <div class="qa-quotation-info-item">
                                      <span class="qa-info-label">Customer</span>
                                      <span class="qa-info-value" id="qaCustomerName">—</span>
                                  </div>
                                  <div class="qa-quotation-info-item">
                                      <span class="qa-info-label">Contact</span>
                                      <span class="qa-info-value" id="qaContactPerson">—</span>
                                  </div>
                                  <div class="qa-quotation-info-item">
                                      <span class="qa-info-label">Address</span>
                                      <span class="qa-info-value" id="qaCustomerAddress">—</span>
                                  </div>
                                  <div class="qa-quotation-info-item">
                                      <span class="qa-info-label">Email</span>
                                      <span class="qa-info-value" id="qaCustomerEmail">—</span>
                                  </div>
                              </div>

                              <!-- People -->
                              <div class="qa-section qa-people-section">
                                  <div class="qa-section-label"><i class="fa-solid fa-users"></i> People</div>
                                  <div class="qa-field">
                                      <label for="qaCreatedBy">Created By</label>
                                      <input type="text" id="qaCreatedBy" placeholder="Created by">
                                  </div>
                                  <div class="qa-field">
                                      <label for="qaAuditedBy">Audited By</label>
                                      <input type="text" id="qaAuditedBy" placeholder="Audited by">
                                  </div>
                                  <div class="qa-field">
                                      <label for="qaAcknowledgedBy">Acknowledged By</label>
                                      <input type="text" id="qaAcknowledgedBy" placeholder="Acknowledged by">
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="qa-audit-footer">
                          <button class="qa-btn-cancel" onclick="quotationAuditIntegration.closeAuditModal()">
                              <i class="fa-solid fa-xmark"></i> Cancel
                          </button>
                          <div style="display: flex; gap: 10px;">
                              <button type="button" onclick="inventoryAuditDownload.openDownloadModal()" style="background-color: #10b981; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 14px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'">
                                  <i class="fa-solid fa-download"></i> Download Options
                              </button>
                              <button class="qa-btn-submit" id="qaSubmitBtn" onclick="quotationAuditIntegration.submitAudit()">
                                  <i class="fa-solid fa-check"></i> <span id="qaSubmitBtnText">Create Audit</span>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          `;
      document.body.insertAdjacentHTML("beforeend", modalHtml);
    }
  }
  setupQuotationSelectionModal() {
    if (!document.getElementById("qaQuotationModal")) {
      const modalHtml = `
                <div id="qaQuotationModal" class="qa-quotation-modal">
                    <div class="qa-quotation-container">
                        <div class="qa-quotation-header">
                            <h3><i class="fa-solid fa-clipboard-list"></i> Select Delivered Quotation</h3>
                            <button class="qa-quotation-close" onclick="quotationAuditIntegration.closeQuotationModal()">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                        <div class="qa-quotation-search">
                            <input type="text" id="qaSearchInput" placeholder="Search by Quote #, Client Name..." autocomplete="off">
                        </div>
                        <div class="qa-quotation-list" id="qaQuotationList">
                            <div class="qa-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</div>
                        </div>
                        <div class="qa-pagination" id="qaPagination"></div>
                    </div>
                </div>
            `;
      document.body.insertAdjacentHTML("beforeend", modalHtml);

      const searchInput = document.getElementById("qaSearchInput");
      if (searchInput) {
        let timer;
        searchInput.addEventListener("input", (e) => {
          clearTimeout(timer);
          timer = setTimeout(() => {
            this.quotationSearch = e.target.value;
            this.quotationPage = 1;
            this.loadDeliveredQuotations();
          }, 400);
        });
      }
    }
  }

  async loadDeliveredQuotations() {
    const listContainer = document.getElementById("qaQuotationList");
    if (!listContainer) return;

    listContainer.innerHTML =
      '<div class="qa-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</div>';

    try {
      const params = new URLSearchParams({
        page: this.quotationPage,
        per_page: 10,
        search: this.quotationSearch,
      });

      const response = await fetch(
        `${this.api.getDeliveredQuotations}?${params}`,
      );
      const result = await response.json();

      if (result.success) {
        this.renderQuotationList(result.quotations, result.pagination);
      } else {
        listContainer.innerHTML = `<div class="qa-empty">${result.message || "Failed to load quotations"}</div>`;
      }
    } catch (error) {
      console.error("Error loading quotations:", error);
      listContainer.innerHTML =
        '<div class="qa-empty">Error loading quotations. Please try again.</div>';
    }
  }

  renderQuotationList(quotations, pagination) {
    const listContainer = document.getElementById("qaQuotationList");
    const paginationContainer = document.getElementById("qaPagination");

    if (!listContainer) return;

    if (!quotations || quotations.length === 0) {
      listContainer.innerHTML =
        '<div class="qa-empty"><i class="fa-solid fa-inbox"></i> No delivered quotations found</div>';
      if (paginationContainer) paginationContainer.innerHTML = "";
      return;
    }

    listContainer.innerHTML = quotations
      .map(
        (q) => `
            <div class="qa-quotation-item" data-id="${q.id}" data-number="${this.escapeHtml(q.quote_number)}" data-audited="${q.has_audit ? 1 : 0}">
                <div class="qa-quotation-info">
                    <div class="qa-quotation-number">${this.escapeHtml(q.quote_number)}</div>
                    <div class="qa-quotation-client">
                        <i class="fa-solid fa-building"></i> ${this.escapeHtml(q.client_name)}
                        ${q.contact_person ? ` • <i class="fa-solid fa-user"></i> ${this.escapeHtml(q.contact_person)}` : ""}
                    </div>
                    <div class="qa-quotation-date">
                        <i class="fa-regular fa-calendar"></i> ${q.created_date || new Date(q.created_at).toLocaleDateString()}
                        ${q.item_count ? ` • <i class="fa-solid fa-box"></i> ${q.item_count} items` : ""}
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="qa-quotation-total">₱ ${this.escapeHtml(q.total_formatted || q.total)}</div>
                    ${q.has_audit ? '<span style="font-size:10px; color:#10b981; background:#10b98122; padding:2px 8px; border-radius:4px;"><i class="fa-solid fa-check"></i> Audited</span>' : ""}
                </div>
            </div>
        `,
      )
      .join("");

    document.querySelectorAll(".qa-quotation-item").forEach((item) => {
      item.addEventListener("click", () => {
        const id = parseInt(item.dataset.id);
        const number = item.dataset.number;
        const hasAudit = parseInt(item.dataset.audited) === 1;
        this.selectQuotationForAudit(id, number, hasAudit);
      });
    });

    if (pagination && pagination.last_page > 1 && paginationContainer) {
      this.renderPagination(pagination, paginationContainer);
    } else if (paginationContainer) {
      paginationContainer.innerHTML = "";
    }
  }

  renderPagination(pagination, container) {
    if (!container) return;

    let html = "";
    html += `<button class="qa-page-btn" ${pagination.current_page <= 1 ? "disabled" : ""} onclick="quotationAuditIntegration.goToPage(${pagination.current_page - 1})"><i class="fa-solid fa-chevron-left"></i></button>`;

    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, startPage + 4);

    if (startPage > 1) {
      html += `<button class="qa-page-btn" onclick="quotationAuditIntegration.goToPage(1)">1</button>`;
      if (startPage > 2) html += `<span class="qa-page-dots">...</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="qa-page-btn ${i === pagination.current_page ? "active" : ""}" onclick="quotationAuditIntegration.goToPage(${i})">${i}</button>`;
    }

    if (endPage < pagination.last_page) {
      if (endPage < pagination.last_page - 1)
        html += `<span class="qa-page-dots">...</span>`;
      html += `<button class="qa-page-btn" onclick="quotationAuditIntegration.goToPage(${pagination.last_page})">${pagination.last_page}</button>`;
    }

    html += `<button class="qa-page-btn" ${pagination.current_page >= pagination.last_page ? "disabled" : ""} onclick="quotationAuditIntegration.goToPage(${pagination.current_page + 1})"><i class="fa-solid fa-chevron-right"></i></button>`;

    container.innerHTML = html;
  }

  goToPage(page) {
    this.quotationPage = page;
    this.loadDeliveredQuotations();
  }

  // In quotation_audit_integration.js - Update selectQuotationForAudit
  async selectQuotationForAudit(
    quotationId,
    quoteNumber,
    hasAudit = false,
    auditId = null,
  ) {
    this.closeQuotationModal();
    this.showAuditLoading(true);

    try {
      // First check if the quotation has an audit using the dedicated API
      const statusResponse = await fetch(
        `../../api/admin_site/inventory/get_quotation_audit_status.php?id=${quotationId}`,
      );
      const statusResult = await statusResponse.json();

      if (statusResult.success && statusResult.has_audit) {
        // It has an audit - load it for editing
        await this.loadExistingAudit(
          quotationId,
          quoteNumber,
          statusResult.audit_id,
        );
        return;
      }

      // No audit exists - load quotation data for new audit
      const response = await fetch(
        `${this.api.getQuotationMaterials}?id=${quotationId}`,
      );
      const result = await response.json();

      if (result.success) {
        this.currentQuotation = result.quotation;
        this.currentQuotationItems = result.items;
        this.currentAuditData = null;
        this.isEditMode = false;
        this.prefillAuditForm(result);
        this.openAuditModal();
        this.updateSubmitButton(false);
        this.showAuditLoading(false);
        this.showNotification(
          `Loaded quotation ${quoteNumber} for audit`,
          "success",
        );
      } else {
        this.showAuditLoading(false);
        this.showNotification(
          result.message || "Failed to load quotation details",
          "error",
        );
      }
    } catch (error) {
      console.error("Error loading quotation details:", error);
      this.showAuditLoading(false);
      this.showNotification(
        "Error loading quotation details: " + error.message,
        "error",
      );
    }
  }

  // Update loadExistingAudit to accept auditId parameter
  // In quotation_audit_integration.js - Replace loadExistingAudit
  async loadExistingAudit(quotationId, quoteNumber, auditId = null) {
    try {
      if (!this.materialsList || this.materialsList.length === 0) {
        await this.loadMaterials();
      }

      // Build the URL
      let url;
      if (auditId) {
        url = `${this.api.getAuditData}?audit_id=${auditId}`;
      } else {
        url = `${this.api.getAuditData}?quotation_id=${quotationId}`;
      }

      console.log("Loading audit from URL:", url);

      const response = await fetch(url);
      const result = await response.json();

      console.log("Audit response:", result);

      if (result.success && result.data) {
        this.currentAuditData = result.data;
        this.currentQuotation = result.data.quotation;
        this.currentQuotationItems = result.data.items || [];
        this.isEditMode = true;

        console.log("Audit data loaded:", this.currentAuditData);
        console.log("Signatures:", this.currentAuditData.audit.signatures);
        console.log(
          "Electricity:",
          this.currentAuditData.audit.electricity_data,
        );
        console.log("Overhead:", this.currentAuditData.audit.overhead_data);

        this.prefillEditAuditForm(result.data);
        this.openAuditModal();
        this.updateSubmitButton(true);
        this.showAuditLoading(false);
        this.showNotification(
          `Loaded audit for ${quoteNumber} (Edit Mode)`,
          "success",
        );
      } else {
        console.error("Failed to load audit:", result.message);
        // Fallback: load quotation data for new audit
        const response = await fetch(
          `${this.api.getQuotationMaterials}?id=${quotationId}`,
        );
        const result = await response.json();
        if (result.success) {
          this.currentQuotation = result.quotation;
          this.currentQuotationItems = result.items;
          this.currentAuditData = null;
          this.isEditMode = false;
          this.prefillAuditForm(result);
          this.openAuditModal();
          this.updateSubmitButton(false);
          this.showAuditLoading(false);
          this.showNotification(
            `Loaded quotation ${quoteNumber} (No existing audit found)`,
            "info",
          );
        }
      }
    } catch (error) {
      console.error("Error loading existing audit:", error);
      this.showAuditLoading(false);
      this.showNotification("Error loading audit data", "error");
    }
  }
  // Replace the openAuditModal method
  // Update openAuditModal method
  openAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      this._scrollY = window.scrollY;
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
      document.body.style.position = "fixed";
      document.body.style.top = `-${this._scrollY}px`;
      document.body.style.width = "100%";
      document.body.style.height = "100%";
    }
  }

  // Update closeAuditModal method
  closeAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      modal.style.display = "none";
    }
    // Restore scroll - remove ALL styles
    document.body.style.overflow = "";
    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.width = "";
    document.body.style.height = "";

    // Restore scroll position
    if (this._scrollY !== undefined && this._scrollY > 0) {
      window.scrollTo(0, this._scrollY);
      this._scrollY = undefined;
    }

    this.clearAuditForm();
    this.isEditMode = false;
    this.currentAuditData = null;
    this.updateSubmitButton(false);
  }

  // Replace the closeQuotationModal method
  closeQuotationModal() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      modal.style.display = "none";
    }
    // Restore scroll - remove ALL styles
    document.body.style.overflow = "";
    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.width = "";
    document.body.style.height = "";

    // Restore scroll position
    if (this._scrollY !== undefined && this._scrollY > 0) {
      window.scrollTo(0, this._scrollY);
      this._scrollY = undefined;
    }
  }

  showAuditLoading(show) {
    const overlay = document.getElementById("auditLoadingOverlay");
    if (overlay) {
      overlay.style.display = show ? "flex" : "none";
    }
  }

  updateSubmitButton(isEdit) {
    const btnText = document.getElementById("qaSubmitBtnText");
    const title = document.getElementById("qaModalTitle");
    if (btnText) {
      btnText.textContent = isEdit ? "Update Audit" : "Create Audit";
    }
    if (title) {
      title.textContent = isEdit
        ? "Edit Inventory Audit"
        : "Create Inventory Audit";
    }
  }

  // ─── PREFILL FORMS ──────────────────────────────────────────────────────

  prefillAuditForm(data) {
    const { quotation, items, suggested_materials } = data;

    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput) {
      itemNameInput.value = "";
      itemNameInput.placeholder = `${quotation.quote_number} - ${quotation.client_name}`;
    }

    const remarksEl = document.getElementById("qaRemarks");
    if (remarksEl) remarksEl.value = "";

    this.updateQuotationInfo(quotation);
    this.clearAuditRows();

    if (suggested_materials && suggested_materials.length > 0) {
      suggested_materials.forEach((material) => {
        this.addMaterialRow(material);
      });
    } else {
      this.addMaterialRow();
    }

    this.addRejectRow();

    if (items && items.length > 0) {
      items.forEach((item) => {
        this.addItemRow(item);
      });
    } else {
      this.addItemRow();
    }

    // Reset fixed overhead fields
    this.resetOverheadFields();

    const createdBy = document.getElementById("qaCreatedBy");
    if (createdBy) createdBy.value = "";

    this.recalculateTotals();
    this.attachOverheadListeners();
  }

  prefillEditAuditForm(auditData) {
    const { quotation, items, audit } = auditData;

    console.log("Prefilling edit audit form:", auditData);

    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput) {
      itemNameInput.value = audit.audit_name || "";
      if (!audit.audit_name) {
        itemNameInput.placeholder = `${quotation.quote_number} - ${quotation.client_name}`;
      }
    }

    const remarksEl = document.getElementById("qaRemarks");
    if (remarksEl) remarksEl.value = audit.remarks || "";

    this.updateQuotationInfo(quotation);
    this.clearAuditRows();

    // Materials
    if (audit.materials && audit.materials.length > 0) {
      audit.materials.forEach((material) => {
        this.addMaterialRow({
          name: material.name || material.material_name,
          quantity: material.quantity,
          unit_cost: material.unit_cost,
          total_cost: material.total_cost,
          material_id: material.id || material.material_id,
        });
      });
    } else {
      this.addMaterialRow();
    }

    // Rejects
    if (audit.rejects && audit.rejects.length > 0) {
      audit.rejects.forEach((reject) => {
        this.addRejectRow({
          name: reject.name || reject.material_name,
          quantity: reject.quantity,
          unit_cost: reject.unit_cost,
          total_cost: reject.total_cost,
          material_id: reject.id || reject.material_id,
        });
      });
    } else {
      this.addRejectRow();
    }

    // Items Produced
    if (audit.items && audit.items.length > 0) {
      audit.items.forEach((item) => {
        this.addItemRow({
          description: item.name || item.item_name || item.description,
          quantity: item.quantity,
          unit_price: item.unit_price,
          total: item.total_amount || item.total,
        });
      });
    } else {
      this.addItemRow();
    }

    // Populate dynamic overhead fields from saved data
    this.resetOverheadFields();
    const overheadRows = audit.overhead_rows || audit.overhead_data || [];
    if (Array.isArray(overheadRows) && overheadRows.length > 0) {
      overheadRows.forEach(ov => this.addOverheadRow(ov));
    } else {
      this.addOverheadRow();
    }

    // Signatures / People
    const signatures = audit.signatures || {};
    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");
    if (createdBy) createdBy.value = signatures.created_by || "";
    if (auditedBy) auditedBy.value = signatures.audited_by || "";
    if (acknowledgedBy) acknowledgedBy.value = signatures.acknowledged_by || "";

    this.recalculateTotals();
    this.attachOverheadListeners();
    console.log("Prefill edit audit form complete!");
  }
  updateQuotationInfo(quotation) {
    const quoteNumberEl = document.getElementById("qaQuoteNumber");
    const customerNameEl = document.getElementById("qaCustomerName");
    const contactPersonEl = document.getElementById("qaContactPerson");
    const addressEl = document.getElementById("qaCustomerAddress");
    const emailEl = document.getElementById("qaCustomerEmail");

    if (quoteNumberEl)
      quoteNumberEl.textContent = quotation.quote_number || "—";
    if (customerNameEl)
      customerNameEl.textContent = quotation.client_name || "—";
    if (contactPersonEl)
      contactPersonEl.textContent = quotation.contact_person || "—";
    if (addressEl) addressEl.textContent = quotation.address || "—";
    if (emailEl) emailEl.textContent = quotation.email || "—";
  }

  // ─── ACCORDION TOGGLES ──────────────────────────────────────────────────

  toggleOverheadAccordion() {
    const panel = document.getElementById("qaOverheadPanel");
    const icon = document.getElementById("qaOverheadIcon");
    const badge = document.getElementById("qaOverheadBadge");

    if (!panel) return;

    if (panel.classList.contains("open")) {
      panel.classList.remove("open");
      panel.style.maxHeight = "0px";
      if (icon) icon.classList.remove("open");
      if (badge) badge.textContent = "Click to expand";
    } else {
      panel.classList.add("open");
      panel.style.maxHeight = panel.scrollHeight + "px";
      if (icon) icon.classList.add("open");
      if (badge) badge.textContent = "Click to collapse";

      const onEnd = (e) => {
        if (e.propertyName !== "max-height") return;
        if (panel.classList.contains("open")) panel.style.maxHeight = "none";
        panel.removeEventListener("transitionend", onEnd);
      };
      panel.addEventListener("transitionend", onEnd);
    }
  }

  expandOverheadAccordion() {
    const panel = document.getElementById("qaOverheadPanel");
    const icon = document.getElementById("qaOverheadIcon");
    const badge = document.getElementById("qaOverheadBadge");

    if (!panel) return;

    if (!panel.classList.contains("open")) {
      panel.classList.add("open");
      panel.style.maxHeight = panel.scrollHeight + "px";
      if (icon) icon.classList.add("open");
      if (badge) badge.textContent = "Click to collapse";

      const onEnd = (e) => {
        if (e.propertyName !== "max-height") return;
        if (panel.classList.contains("open")) panel.style.maxHeight = "none";
        panel.removeEventListener("transitionend", onEnd);
      };
      panel.addEventListener("transitionend", onEnd);
    }
  }

  // ─── MATERIAL ROWS ────────────────────────────────────────────────────────

  addMaterialRow(materialData = null) {
    const container = document.getElementById("qaMaterialCostsContainer");
    if (!container) return;

    const rowId =
      "mat_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    const materialName = this.escapeHtml(
      materialData?.name || materialData?.material_name || "",
    );
    const quantity =
      materialData?.suggested_quantity || materialData?.quantity || 1;
    const unitCost = parseFloat(materialData?.unit_cost || 0);
    const totalCost =
      materialData?.total_cost !== undefined && materialData?.total_cost !== null
        ? parseFloat(materialData.total_cost)
        : quantity * unitCost;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="material-name-input qa-input-full" list="qa-material-suggestions-${rowId}" placeholder="Type or choose material..." value="${materialName}">
          <datalist id="qa-material-suggestions-${rowId}"></datalist>
      </div>
      <input type="number" class="material-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
      <input type="number" class="material-cost cost-input" placeholder="0.00" step="0.0001" value="${unitCost.toFixed(4)}">
      <input type="number" class="material-total total-input" placeholder="0.00" step="0.01" value="${totalCost.toFixed(2)}">
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
  `;

    container.appendChild(row);

    const nameInput = row.querySelector(".material-name-input");
    const datalist = row.querySelector("datalist");
    if (datalist && this.materialsList) {
      datalist.innerHTML = this.materialsList
        .map(
          (m) =>
            `<option value="${this.escapeHtml(m.material_name)}" data-id="${m.id}" data-cost="${parseFloat(m.unit_cost || 0).toFixed(4)}"></option>`,
        )
        .join("");
    }

    const qtyEl = row.querySelector(".material-qty");
    const costEl = row.querySelector(".material-cost");
    const totalEl = row.querySelector(".material-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      row.dataset.totalMode = "auto";
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      if (totalEl) totalEl.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    const setManualTotal = () => {
      row.dataset.totalMode = "manual";
      this.recalculateTotals();
    };

    const syncFromMaterialName = () => {
      const name = nameInput.value.trim();
      const match = this.materialsList
        ? this.materialsList.find(
            (m) => m.material_name.toLowerCase() === name.toLowerCase(),
          )
        : null;
      if (match) {
        row.dataset.materialId = match.id;
        if (costEl && parseFloat(match.unit_cost || 0) > 0) {
          costEl.value = parseFloat(match.unit_cost || 0).toFixed(4);
        }
      } else {
        row.dataset.materialId = "";
      }
      updateTotal();
    };

    nameInput.addEventListener("input", syncFromMaterialName);
    nameInput.addEventListener("change", syncFromMaterialName);
    qtyEl.addEventListener("input", updateTotal);
    qtyEl.addEventListener("change", updateTotal);
    costEl.addEventListener("input", updateTotal);
    costEl.addEventListener("change", updateTotal);
    totalEl.addEventListener("input", setManualTotal);
    totalEl.addEventListener("change", setManualTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (materialData?.material_id) {
      row.dataset.materialId = materialData.material_id;
    }

    if (materialData?.name) {
      nameInput.value = materialData.name;
      syncFromMaterialName();
    } else {
      updateTotal();
    }
  }

  // ─── REJECT ROWS ──────────────────────────────────────────────────────────

  addRejectRow(rejectData = null) {
    const container = document.getElementById("qaRejectCostsContainer");
    if (!container) return;

    const rowId =
      "rej_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    const rejectName = this.escapeHtml(
      rejectData?.name || rejectData?.material_name || "",
    );
    const quantity = rejectData?.quantity || 1;
    const unitCost = parseFloat(rejectData?.unit_cost || 0);
    const totalCost =
      rejectData?.total_cost !== undefined && rejectData?.total_cost !== null
        ? parseFloat(rejectData.total_cost)
        : quantity * unitCost;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="reject-name-input qa-input-full" list="qa-reject-suggestions-${rowId}" placeholder="Type or choose reject item..." value="${rejectName}">
          <datalist id="qa-reject-suggestions-${rowId}"></datalist>
      </div>
      <input type="number" class="reject-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
      <input type="number" class="reject-cost cost-input" placeholder="0.00" step="0.0001" value="${unitCost.toFixed(4)}">
      <input type="number" class="reject-total total-input" placeholder="0.00" step="0.01" value="${totalCost.toFixed(2)}">
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
  `;

    container.appendChild(row);

    const nameInput = row.querySelector(".reject-name-input");
    const datalist = row.querySelector("datalist");
    if (datalist && this.materialsList) {
      datalist.innerHTML = this.materialsList
        .map(
          (m) =>
            `<option value="${this.escapeHtml(m.material_name)}" data-id="${m.id}" data-cost="${parseFloat(m.unit_cost || 0).toFixed(4)}"></option>`,
        )
        .join("");
    }

    const qtyEl = row.querySelector(".reject-qty");
    const costEl = row.querySelector(".reject-cost");
    const totalEl = row.querySelector(".reject-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      row.dataset.totalMode = "auto";
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      if (totalEl) totalEl.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    const setManualTotal = () => {
      row.dataset.totalMode = "manual";
      this.recalculateTotals();
    };

    const syncFromMaterialName = () => {
      const name = nameInput.value.trim();
      const match = this.materialsList
        ? this.materialsList.find(
            (m) => m.material_name.toLowerCase() === name.toLowerCase(),
          )
        : null;
      if (match) {
        row.dataset.materialId = match.id;
        if (costEl && parseFloat(match.unit_cost || 0) > 0) {
          costEl.value = parseFloat(match.unit_cost || 0).toFixed(4);
        }
      } else {
        row.dataset.materialId = "";
      }
      updateTotal();
    };

    nameInput.addEventListener("input", syncFromMaterialName);
    nameInput.addEventListener("change", syncFromMaterialName);
    qtyEl.addEventListener("input", updateTotal);
    qtyEl.addEventListener("change", updateTotal);
    costEl.addEventListener("input", updateTotal);
    costEl.addEventListener("change", updateTotal);
    totalEl.addEventListener("input", setManualTotal);
    totalEl.addEventListener("change", setManualTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (rejectData?.material_id) {
      row.dataset.materialId = rejectData.material_id;
    }

    if (rejectData?.name) {
      nameInput.value = rejectData.name;
      syncFromMaterialName();
    } else {
      updateTotal();
    }
  }

  // ─── ITEM ROWS ────────────────────────────────────────────────────────────

  addItemRow(itemData = null) {
    const container = document.getElementById("qaItemsContainer");
    if (!container) return;

    const rowId =
      "item_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    const description = itemData
      ? this.escapeHtml(itemData.description || itemData.name || itemData.item_name || "")
      : "";
    const quantity = itemData?.quantity || 1;
    const unitPrice = parseFloat(itemData?.unit_price || 0);
    const total =
      itemData?.total !== undefined
        ? itemData.total
        : itemData?.total_amount !== undefined
          ? itemData.total_amount
          : quantity * unitPrice;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="item-name" placeholder="Enter item name..." value="${description}">
      </div>
      <input type="number" class="item-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
      <input type="number" class="item-price price-input" placeholder="0.00" step="0.01" value="${unitPrice.toFixed(2)}">
      <input type="number" class="item-total total-input" placeholder="0.00" step="0.01" value="${parseFloat(total).toFixed(2)}" readonly>
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
  `;

    container.appendChild(row);

    const nameEl = row.querySelector(".item-name");
    const qtyEl = row.querySelector(".item-qty");
    const priceEl = row.querySelector(".item-price");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const price = parseFloat(priceEl.value) || 0;
      const totalInput = row.querySelector(".item-total");
      if (totalInput) totalInput.value = (qty * price).toFixed(2);
      this.recalculateTotals();
    };

    if (nameEl) {
      nameEl.addEventListener("input", () => this.recalculateTotals());
      nameEl.addEventListener("change", () => this.recalculateTotals());
    }
    if (qtyEl) {
      qtyEl.addEventListener("input", updateTotal);
      qtyEl.addEventListener("change", updateTotal);
    }
    if (priceEl) {
      priceEl.addEventListener("input", updateTotal);
      priceEl.addEventListener("change", updateTotal);
    }
    if (removeBtn) removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    updateTotal();
  }

  addOverheadRow(itemData = null) {
    const container = document.getElementById("qaOverheadContainer");
    if (!container) return;

    const rowId = "overhead_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    const item = itemData?.item || itemData?.name || "";
    const quantity = itemData?.quantity || itemData?.qty || 0;
    const unitCost = itemData?.unit_cost || itemData?.cost || 0;
    const percentage = itemData?.percentage || 0;
    const total = itemData?.total || itemData?.total_cost || 0;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="ov-item qa-input-full" placeholder="Enter item..." value="${this.escapeHtml(item)}" style="padding: 5px 8px; border: 1px solid #e2e8f0; border-radius: 4px; background: #ffffff; color: #0f172a; font-size: 12px; width: 100%;">
      </div>
      <input type="number" class="ov-qty qty-input" placeholder="0" step="1" min="0" value="${quantity}">
      <input type="number" class="ov-cost cost-input" placeholder="0.00" step="0.01" min="0" value="${unitCost}">
      <input type="number" class="ov-pct qty-input" placeholder="0" step="0.01" min="0" value="${percentage}">
      <input type="number" class="ov-total cost-input" placeholder="0.00" step="0.01" min="0" value="${parseFloat(total).toFixed(2)}">
      <button type="button" class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
    `;

    container.appendChild(row);

    const ovItemEl = row.querySelector(".ov-item");
    const qtyEl = row.querySelector(".ov-qty");
    const costEl = row.querySelector(".ov-cost");
    const pctEl = row.querySelector(".ov-pct");
    const totalEl = row.querySelector(".ov-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateRowTotal = () => {
      const qty = parseFloat(qtyEl?.value) || 0;
      const cost = parseFloat(costEl?.value) || 0;
      const pctStr = pctEl?.value !== undefined ? String(pctEl.value).trim() : "";
      const pct = parseFloat(pctStr) || 0;

      let rowTot = 0;
      if (pctStr !== "") {
        rowTot = qty * cost * pct;
      } else {
        rowTot = qty * cost;
      }

      if (totalEl) totalEl.value = rowTot.toFixed(2);
      this.recalculateTotals();
    };

    if (ovItemEl) {
        ovItemEl.addEventListener("input", () => {
            const selectedItemName = ovItemEl.value.trim();
            if (selectedItemName) {
                let foundQty = 0;
                let foundCost = 0;
                document.querySelectorAll("#qaItemsContainer .qa-dynamic-row").forEach((iRow) => {
                    const name = iRow.querySelector(".item-name")?.value?.trim() || "";
                    if (name.toLowerCase() === selectedItemName.toLowerCase()) {
                        foundQty = parseFloat(iRow.querySelector(".item-qty")?.value) || 0;
                        foundCost = parseFloat(iRow.querySelector(".item-price")?.value) || 0;
                    }
                });
                if (qtyEl && foundQty > 0 && parseFloat(qtyEl.value) === 0) {
                    qtyEl.value = foundQty;
                }
                if (costEl && foundCost > 0 && parseFloat(costEl.value) === 0) {
                    costEl.value = foundCost.toFixed(2);
                }
            }
            updateRowTotal();
        });
    }

    if (qtyEl) {
      qtyEl.addEventListener("input", updateRowTotal);
      qtyEl.addEventListener("change", updateRowTotal);
    }
    if (costEl) {
      costEl.addEventListener("input", updateRowTotal);
      costEl.addEventListener("change", updateRowTotal);
    }
    if (pctEl) {
      pctEl.addEventListener("input", updateRowTotal);
      pctEl.addEventListener("change", updateRowTotal);
    }
    if (totalEl) {
      totalEl.addEventListener("input", () => this.recalculateTotals());
      totalEl.addEventListener("change", () => this.recalculateTotals());
    }
    
    if (removeBtn) {
      removeBtn.addEventListener("click", () => {
        row.remove();
        this.recalculateTotals();
      });
    }

    if (!itemData) {
      this.recalculateTotals();
    }
  }


  removeDynamicRow(button) {
    const row = button.closest(".qa-dynamic-row");
    if (row) {
      const rowId = row.id;
      this.materialSelectors = this.materialSelectors.filter((s) => s.rowId !== rowId);
      this.rejectSelectors = this.rejectSelectors.filter((s) => s.rowId !== rowId);
      row.remove();
      this.recalculateTotals();
    }
  }

  clearAuditRows() {
    const materialsContainer = document.getElementById("qaMaterialCostsContainer");
    const rejectsContainer = document.getElementById("qaRejectCostsContainer");
    const itemsContainer = document.getElementById("qaItemsContainer");
    if (materialsContainer) materialsContainer.innerHTML = "";
    if (rejectsContainer) rejectsContainer.innerHTML = "";
    if (itemsContainer) itemsContainer.innerHTML = "";
    this.materialSelectors = [];
    this.rejectSelectors = [];
  }

  clearAuditForm() {
    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput) {
      itemNameInput.value = "";
      itemNameInput.placeholder = "Enter audit name...";
    }
    const remarksEl = document.getElementById("qaRemarks");
    if (remarksEl) remarksEl.value = "";
    this.clearAuditRows();
    this.resetOverheadFields();
    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");
    if (createdBy) createdBy.value = "";
    if (auditedBy) auditedBy.value = "";
    if (acknowledgedBy) acknowledgedBy.value = "";
    this.clearQuotationInfo();
    this.recalculateTotals();
  }

  clearQuotationInfo() {
    const quoteNumberEl = document.getElementById("qaQuoteNumber");
    const customerNameEl = document.getElementById("qaCustomerName");
    const contactPersonEl = document.getElementById("qaContactPerson");
    const addressEl = document.getElementById("qaCustomerAddress");
    const emailEl = document.getElementById("qaCustomerEmail");
    if (quoteNumberEl) quoteNumberEl.textContent = "—";
    if (customerNameEl) customerNameEl.textContent = "—";
    if (contactPersonEl) contactPersonEl.textContent = "—";
    if (addressEl) addressEl.textContent = "—";
    if (emailEl) emailEl.textContent = "—";
  }

  resetOverheadFields() {
    const container = document.getElementById("qaOverheadContainer");
    if (container) container.innerHTML = "";
  }

  attachOverheadListeners() {
    this.updateOverheadItemDropdown();
  }

  updateOverheadItemDropdown() {
    // Deprecated: Overhead items are now text inputs instead of dropdowns.
  }

  // ─── RECALCULATE TOTALS ──────────────────────────────────────────────────

  recalculateTotals() {
    this.updateOverheadItemDropdown();
    let materialTotal = 0;
    let rejectTotal = 0;
    let amountTotal = 0;

    // Materials Used Total
    document
      .querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const totalInput = row.querySelector(".material-total");
        const qtyInput = row.querySelector(".material-qty");
        const costInput = row.querySelector(".material-cost");
        if (row.dataset.totalMode === "manual" && totalInput && parseFloat(totalInput.value) >= 0) {
          materialTotal += parseFloat(totalInput.value) || 0;
        } else if (qtyInput && costInput) {
          const rowTot = (parseFloat(qtyInput.value) || 0) * (parseFloat(costInput.value) || 0);
          materialTotal += rowTot;
          if (totalInput) totalInput.value = rowTot.toFixed(2);
        }
      });

    // Rejects / Wastage Total
    document
      .querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const totalInput = row.querySelector(".reject-total");
        const qtyInput = row.querySelector(".reject-qty");
        const costInput = row.querySelector(".reject-cost");
        if (row.dataset.totalMode === "manual" && totalInput && parseFloat(totalInput.value) >= 0) {
          rejectTotal += parseFloat(totalInput.value) || 0;
        } else if (qtyInput && costInput) {
          const rowTot = (parseFloat(qtyInput.value) || 0) * (parseFloat(costInput.value) || 0);
          rejectTotal += rowTot;
          if (totalInput) totalInput.value = rowTot.toFixed(2);
        }
      });

    // Items Produced Total (Total Sales)
    document
      .querySelectorAll("#qaItemsContainer .qa-dynamic-row")
      .forEach((row) => {
        const totalInput = row.querySelector(".item-total");
        const qtyInput = row.querySelector(".item-qty");
        const priceInput = row.querySelector(".item-price");
        const qty = parseFloat(qtyInput?.value) || 0;
        const price = parseFloat(priceInput?.value) || 0;
        const tot = qty * price;
        if (totalInput) totalInput.value = tot.toFixed(2);
        amountTotal += tot;
      });

    // Fixed Overhead Cost calculation
    let overheadTotal = 0;
    document.querySelectorAll("#qaOverheadContainer .qa-dynamic-row").forEach(row => {
      const ovQtyEl = row.querySelector(".ov-qty");
      const ovCostEl = row.querySelector(".ov-cost");
      const ovPctEl = row.querySelector(".ov-pct");
      const ovTotalEl = row.querySelector(".ov-total");

      const ovQty = parseFloat(ovQtyEl?.value) || 0;
      const ovCost = parseFloat(ovCostEl?.value) || 0;
      const pctStr = ovPctEl?.value !== undefined ? String(ovPctEl.value).trim() : "";
      const ovPct = parseFloat(pctStr) || 0;

      let rowTotal = 0;
      if (pctStr !== "") {
        rowTotal = ovQty * ovCost * ovPct;
      } else {
        rowTotal = ovQty * ovCost;
      }

      if (ovTotalEl) ovTotalEl.value = rowTotal.toFixed(2);
      overheadTotal += rowTotal;
    });

    const totalCost = materialTotal + rejectTotal + overheadTotal;
    const profit = amountTotal - totalCost;

    const materialCostEl = document.getElementById("qaTotalMaterialCost");
    const rejectCostEl = document.getElementById("qaTotalRejectCost");
    const overheadCostEl = document.getElementById("qaTotalOverheadCost");
    const amountDueEl = document.getElementById("qaTotalAmountDue");
    const totalCostEl = document.getElementById("qaTotalCost");
    const profitEl = document.getElementById("qaProfit");

    if (materialCostEl) materialCostEl.textContent = `₱${materialTotal.toFixed(2)}`;
    if (rejectCostEl) rejectCostEl.textContent = `₱${rejectTotal.toFixed(2)}`;
    if (overheadCostEl) overheadCostEl.textContent = `₱${overheadTotal.toFixed(2)}`;
    if (amountDueEl) amountDueEl.textContent = `₱${amountTotal.toFixed(2)}`;
    if (totalCostEl) totalCostEl.textContent = `₱${totalCost.toFixed(2)}`;
    if (profitEl) {
      profitEl.textContent = `₱${profit.toFixed(2)}`;
      profitEl.className = `value ${profit >= 0 ? "positive" : "negative"}`;
    }
  }

  // ─── SUBMIT AUDIT ─────────────────────────────────────────────────────────

  async submitAudit() {
    // Collect materials
    const materials = [];
    document
      .querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = row.dataset.materialId
          ? parseInt(row.dataset.materialId)
          : 0;
        const materialName =
          row.querySelector(".material-name-input")?.value?.trim() || "";
        const quantity =
          parseFloat(row.querySelector(".material-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".material-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".material-total")?.value) || 0;

        if (materialName && quantity > 0) {
          materials.push({
            id: materialId,
            name: materialName,
            quantity: quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
          });
        }
      });

    // Collect rejects
    const rejects = [];
    document
      .querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = row.dataset.materialId
          ? parseInt(row.dataset.materialId)
          : 0;
        const materialName =
          row.querySelector(".reject-name-input")?.value?.trim() || "";
        const quantity =
          parseFloat(row.querySelector(".reject-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".reject-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".reject-total")?.value) || 0;

        if (materialName && quantity > 0) {
          rejects.push({
            id: materialId,
            name: materialName,
            quantity: quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
          });
        }
      });

    // Collect items
    const items = [];
    document
      .querySelectorAll("#qaItemsContainer .qa-dynamic-row")
      .forEach((row) => {
        const name = row.querySelector(".item-name")?.value?.trim() || "";
        const quantity = parseFloat(row.querySelector(".item-qty")?.value) || 0;
        const unitPrice =
          parseFloat(row.querySelector(".item-price")?.value) || 0;
        const totalAmount =
          parseFloat(row.querySelector(".item-total")?.value) || 0;

        if (name && quantity > 0) {
          items.push({
            name: name,
            quantity: quantity,
            unit_price: unitPrice,
            total_amount: totalAmount,
          });
        }
      });

    // Collect overhead from dynamic rows
    const overhead = [];
    document.querySelectorAll("#qaOverheadContainer .qa-dynamic-row").forEach(row => {
      const ovItem = row.querySelector(".ov-item")?.value?.trim() || "";
      const ovQty = parseFloat(row.querySelector(".ov-qty")?.value) || 0;
      const ovCost = parseFloat(row.querySelector(".ov-cost")?.value) || 0;
      const ovPct = parseFloat(row.querySelector(".ov-pct")?.value) || 0;
      const ovTotal = parseFloat(row.querySelector(".ov-total")?.value) || 0;
      if (ovItem || ovTotal > 0) {
        overhead.push({ item: ovItem, quantity: ovQty, unit_cost: ovCost, percentage: ovPct, total: ovTotal });
      }
    });

    // Collect audit name & remarks
    const auditName =
      document.getElementById("qaAuditItemName")?.value?.trim() || "";
    const remarks =
      document.getElementById("qaRemarks")?.value?.trim() || "";

    // Validate
    if (materials.length === 0 && items.length === 0) {
      this.showNotification(
        "Please add at least one material or item",
        "error",
      );
      return;
    }

    // Build payload
    const payload = {
      audit_id:
        this.isEditMode && this.currentAuditData?.audit?.id
          ? this.currentAuditData.audit.id
          : null,
      quotation_id: this.currentQuotation?.id || null,
      audit_name: auditName,
      remarks: remarks,
      items: items,
      materials: materials,
      rejects: rejects,
      overhead: overhead,
      created_by: document.getElementById("qaCreatedBy")?.value?.trim() || "",
      audited_by: document.getElementById("qaAuditedBy")?.value?.trim() || "",
      acknowledged_by:
        document.getElementById("qaAcknowledgedBy")?.value?.trim() || "",
      auto_compute: true,
    };

    console.log("Submitting payload:", payload);

    if (this.isEditMode && !payload.audit_id) {
      this.showNotification("Error: Missing audit ID for update", "error");
      return;
    }

    this.showAuditLoading(true);

    try {
      const endpoint = this.isEditMode
        ? "../../api/admin_site/inventory/update_audit.php"
        : "../../api/admin_site/inventory/create_audit.php";

      console.log("Sending to endpoint:", endpoint);

      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const text = await response.text();
        console.error("Server response (non-OK):", text);
        throw new Error(
          `Server error: ${response.status} - ${text.substring(0, 100)}`,
        );
      }

      const result = await response.json();
      console.log("Server response:", result);

      if (result.success) {
        this.closeAuditModal();
        this.showNotification(
          this.isEditMode
            ? "Audit updated successfully!"
            : "Audit created successfully! Inventory has been updated.",
          "success",
        );
        if (typeof matReloadMaterials === "function") matReloadMaterials(true);
        if (typeof matReloadLogs === "function") matReloadLogs(true);
        if (typeof quotationManager?.fetchQuotations === "function") {
          quotationManager.fetchQuotations();
        }
      } else {
        this.showNotification(
          result.message || "Failed to save audit",
          "error",
        );
      }
    } catch (error) {
      console.error("Error submitting audit:", error);
      this.showNotification(
        "Error submitting audit: " + error.message,
        "error",
      );
    } finally {
      this.showAuditLoading(false);
    }
  }

  // ─── OVERRIDE AUDIT BUTTON ──────────────────────────────────────────────

  overrideAuditButton() {
    window.matOpenAuditModal = () => {
      this.openQuotationSelector();
    };
  }

  // Replace the openQuotationSelector method
  openQuotationSelector() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      this._scrollY = window.scrollY;
      this.quotationPage = 1;
      this.quotationSearch = "";
      const searchInput = document.getElementById("qaSearchInput");
      if (searchInput) searchInput.value = "";
      this.loadDeliveredQuotations();
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
      document.body.style.position = "fixed";
      document.body.style.top = `-${this._scrollY}px`;
      document.body.style.width = "100%";
      document.body.style.height = "100%";
    }
  }

  // ─── NOTIFICATIONS ────────────────────────────────────────────────────────

  showNotification(message, type = "info") {
    const colors = {
      success: "#10b981",
      error: "#ef4444",
      info: "#3b82f6",
      warning: "#f59e0b",
    };
    const notification = document.createElement("div");
    notification.style.cssText = `
            position: fixed; bottom: 20px; right: 20px; z-index: 100030;
            background: ${colors[type]}; color: white; padding: 12px 20px;
            border-radius: 8px; font-size: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
        `;
    notification.innerHTML = `<i class="fa-solid ${type === "success" ? "fa-check-circle" : "fa-exclamation-circle"}"></i> ${message}`;
    document.body.appendChild(notification);
    setTimeout(() => {
      notification.style.opacity = "0";
      notification.style.transform = "translateX(100%)";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  escapeHtml(str) {
    if (!str) return "";
    return String(str).replace(/[&<>]/g, function (m) {
      if (m === "&") return "&amp;";
      if (m === "<") return "&lt;";
      if (m === ">") return "&gt;";
      return m;
    });
  }
}

// ─── INITIALIZATION ──────────────────────────────────────────────────────

let quotationAuditIntegration;
document.addEventListener("DOMContentLoaded", () => {
  quotationAuditIntegration = new QuotationAuditIntegration();
});

// ─── BRIDGE ────────────────────────────────────────────────────────────────

window.openQuotationAuditModal = function (quotationId, quoteNumber) {
  if (
    typeof quotationAuditIntegration !== "undefined" &&
    quotationAuditIntegration
  ) {
    quotationAuditIntegration.selectQuotationForAudit(
      quotationId,
      quoteNumber,
      false,
    );
  } else {
    console.error("quotationAuditIntegration is not initialized yet.");
  }
};
