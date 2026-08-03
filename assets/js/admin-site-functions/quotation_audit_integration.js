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
                                  <!-- Audit Name -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-tag"></i> Audit Name</div>
                                      <input type="text" id="qaAuditItemName" class="qa-input-full" placeholder="Enter audit name...">
                                  </div>

                                  <!-- Materials Used -->
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

                                  <!-- Rejects / Wastage -->
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

                                  <!-- Items Produced -->
                                  <div class="qa-section">
                                      <div class="qa-section-label"><i class="fa-solid fa-box"></i> Items Produced</div>
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

                                  <!-- Summary -->
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
                                              <span class="label">Amount Due</span>
                                              <span class="value" id="qaTotalAmountDue">₱0.00</span>
                                          </div>
                                          <div>
                                              <span class="label">Profit</span>
                                              <span class="value" id="qaProfit">₱0.00</span>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Electricity & Overhead Costs - Combined Dropdown -->
                                  <div class="qa-section qa-collapsible-section">
                                      <div class="qa-collapsible-header" onclick="quotationAuditIntegration.toggleOverheadAccordion()">
                                          <i class="fa-solid fa-chevron-right qa-collapsible-icon" id="qaOverheadIcon"></i>
                                          <span class="qa-collapsible-title">Electricity &amp; Overhead Costs</span>
                                          <span class="qa-collapsible-badge" id="qaOverheadBadge">Click to expand</span>
                                      </div>
                                      <div class="qa-collapsible-body" id="qaOverheadPanel">
                                          <div class="qa-collapsible-inner">
                                              <!-- Electricity Fields -->
                                              <div class="qa-sub-section">
                                                  <div class="qa-sub-section-label">Electricity Cost</div>
                                                  <div class="qa-electricity-fields">
                                                      <div class="qa-electricity-field">
                                                          <label for="qaElecShop">Shop</label>
                                                          <input type="number" id="qaElecShop" min="0" step="0.01" placeholder="0.00" value="0">
                                                      </div>
                                                      <div class="qa-electricity-field">
                                                          <label for="qaElecProduction">Production</label>
                                                          <input type="number" id="qaElecProduction" min="0" step="0.01" placeholder="0.00" value="0">
                                                      </div>
                                                  </div>
                                              </div>

                                              <!-- Overhead Category Costs -->
                                              <div class="qa-sub-section">
                                                  <div class="qa-sub-section-label">Overhead Category Cost</div>
                                                  <div class="qa-overhead-table-wrapper">
                                                      <table class="qa-overhead-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Category</th>
                                                                  <th style="text-align:right;">Monthly Cost</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody id="qaOverheadTableBody">
                                                              ${this.renderOverheadRows()}
                                                          </tbody>
                                                          <tfoot>
                                                              <tr class="qa-overhead-total-row">
                                                                  <td><strong>TOTAL MONTHLY OVERHEAD</strong></td>
                                                                  <td style="text-align:right;"><strong id="qaTotalMonthlyOverhead">₱0.00</strong></td>
                                                              </tr>
                                                              <tr class="qa-overhead-hours-row">
                                                                  <td><label for="qaWorkingHours">Working Hours</label></td>
                                                                  <td style="text-align:right;">
                                                                      <input type="number" id="qaWorkingHours" class="qa-overhead-hours-input" min="1" step="1" value="250">
                                                                  </td>
                                                              </tr>
                                                              <tr class="qa-overhead-perhour-row">
                                                                  <td><strong>Overhead Per Hour</strong></td>
                                                                  <td style="text-align:right;"><strong id="qaOverheadPerHour">₱0.00 / hour</strong></td>
                                                              </tr>
                                                          </tfoot>
                                                      </table>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
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
                          <button class="qa-btn-submit" id="qaSubmitBtn" onclick="quotationAuditIntegration.submitAudit()">
                              <i class="fa-solid fa-check"></i> <span id="qaSubmitBtnText">Create Audit</span>
                          </button>
                      </div>
                  </div>
              </div>
          `;
      document.body.insertAdjacentHTML("beforeend", modalHtml);
      this.attachOverheadListeners();
      this.updateOverheadComputation();
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

    const createdBy = document.getElementById("qaCreatedBy");
    if (createdBy) {
      createdBy.value = "";
    }

    this.recalculateTotals();
    this.updateOverheadComputation();
    this.resetElectricityFields();
  }

  // In quotation_audit_integration.js - Update prefillEditAuditForm

  // In quotation_audit_integration.js - Update prefillEditAuditForm method
  // In quotation_audit_integration.js - Update prefillEditAuditForm
  // Replace the selectQuotationForAudit method
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

  // Replace the loadExistingAudit method
  async loadExistingAudit(quotationId, quoteNumber, auditId = null) {
    try {
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

  // Replace the prefillEditAuditForm method
  prefillEditAuditForm(auditData) {
    const { quotation, items, audit } = auditData;

    console.log("Prefilling edit audit form:", auditData);

    // ============================================================
    // AUDIT NAME
    // ============================================================
    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput) {
      itemNameInput.value = audit.audit_name || "";
      if (!audit.audit_name) {
        itemNameInput.placeholder = `${quotation.quote_number} - ${quotation.client_name}`;
      }
    }

    // ============================================================
    // QUOTATION INFO
    // ============================================================
    this.updateQuotationInfo(quotation);

    // ============================================================
    // CLEAR AND POPULATE ROWS
    // ============================================================
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

    // ============================================================
    // PEOPLE FIELDS - From signatures
    // ============================================================
    const signatures = audit.signatures || {};
    console.log("Signatures data:", signatures);

    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");

    if (createdBy) createdBy.value = signatures.created_by || "";
    if (auditedBy) auditedBy.value = signatures.audited_by || "";
    if (acknowledgedBy) acknowledgedBy.value = signatures.acknowledged_by || "";

    // ============================================================
    // ELECTRICITY FIELDS
    // ============================================================
    const electricityData = audit.electricity_data || {};
    console.log("Electricity data:", electricityData);

    const elecShop = document.getElementById("qaElecShop");
    const elecProduction = document.getElementById("qaElecProduction");

    if (elecShop) elecShop.value = electricityData.shop || 0;
    if (elecProduction) elecProduction.value = electricityData.production || 0;

    // ============================================================
    // OVERHEAD FIELDS
    // ============================================================
    const overheadData = audit.overhead_data || {};
    console.log("Overhead data:", overheadData);

    // Set overhead values
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      const key = input.dataset.key;
      if (overheadData[key] !== undefined) {
        input.value = overheadData[key];
      }
    });

    // Working Hours
    const workingHours = document.getElementById("qaWorkingHours");
    if (workingHours) {
      workingHours.value = audit.working_hours || 250;
    }

    // ============================================================
    // UPDATE COMPUTATIONS
    // ============================================================
    this.updateOverheadComputation();
    this.recalculateTotals();

    // ============================================================
    // AUTO-EXPAND OVERHEAD SECTION IF HAS VALUES
    // ============================================================
    const hasOverheadValues = Array.from(
      document.querySelectorAll(".overhead-cost-input"),
    ).some((input) => parseFloat(input.value) > 0);
    const hasElectricityValues =
      parseFloat(document.getElementById("qaElecShop")?.value) > 0 ||
      parseFloat(document.getElementById("qaElecProduction")?.value) > 0;

    if (hasOverheadValues || hasElectricityValues) {
      this.expandOverheadAccordion();
    }

    console.log("Prefill complete!");
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
    const unitCost = materialData?.unit_cost || 0;
    const totalCost = materialData?.total_cost || quantity * unitCost;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="material-name-input qa-input-full" list="qa-material-suggestions-${rowId}" placeholder="Type or choose material..." value="${materialName}">
          <datalist id="qa-material-suggestions-${rowId}"></datalist>
      </div>
      <input type="number" class="material-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
      <input type="number" class="material-cost cost-input" placeholder="0.00" step="0.0001" value="${parseFloat(unitCost).toFixed(4)}">
      <input type="number" class="material-total total-input" placeholder="0.00" step="0.01" value="${parseFloat(totalCost).toFixed(2)}">
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
       
  `;

    container.appendChild(row);

    const nameInput = row.querySelector(".material-name-input");
    const datalist = row.querySelector("datalist");
    if (datalist) {
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
      const match = this.materialsList.find(
        (m) => m.material_name.toLowerCase() === name.toLowerCase(),
      );
      if (match) {
        row.dataset.materialId = match.id;
        if (costEl) costEl.value = parseFloat(match.unit_cost || 0).toFixed(4);
        updateTotal();
      } else {
        row.dataset.materialId = "";
      }
    };

    nameInput.addEventListener("input", syncFromMaterialName);
    qtyEl.addEventListener("input", updateTotal);
    costEl.addEventListener("input", updateTotal);
    totalEl.addEventListener("input", setManualTotal);
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
    const unitCost = rejectData?.unit_cost || 0;
    const totalCost = rejectData?.total_cost || quantity * unitCost;

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="reject-name-input qa-input-full" list="qa-reject-suggestions-${rowId}" placeholder="Type or choose reject item..." value="${rejectName}">
          <datalist id="qa-reject-suggestions-${rowId}"></datalist>
      </div>
      <input type="number" class="reject-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
      <input type="number" class="reject-cost cost-input" placeholder="0.00" step="0.0001" value="${parseFloat(unitCost).toFixed(4)}">
      <input type="number" class="reject-total total-input" placeholder="0.00" step="0.01" value="${parseFloat(totalCost).toFixed(2)}">
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
      
  `;

    container.appendChild(row);

    const nameInput = row.querySelector(".reject-name-input");
    const datalist = row.querySelector("datalist");
    if (datalist) {
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
      const match = this.materialsList.find(
        (m) => m.material_name.toLowerCase() === name.toLowerCase(),
      );
      if (match) {
        row.dataset.materialId = match.id;
        if (costEl) costEl.value = parseFloat(match.unit_cost || 0).toFixed(4);
        updateTotal();
      } else {
        row.dataset.materialId = "";
      }
    };

    nameInput.addEventListener("input", syncFromMaterialName);
    qtyEl.addEventListener("input", updateTotal);
    costEl.addEventListener("input", updateTotal);
    totalEl.addEventListener("input", setManualTotal);
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

  addItemRow(itemData = null) {
    const container = document.getElementById("qaItemsContainer");
    if (!container) return;

    const rowId =
      "item_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    const description = itemData ? this.escapeHtml(itemData.description) : "";

    row.innerHTML = `
      <div class="qa-material-name-field">
          <input type="text" class="item-name" placeholder="Enter item name..." value="${description}">
      </div>
      <input type="number" class="item-qty qty-input" placeholder="QTY" step="1" min="0" value="${itemData?.quantity || 1}">
      <input type="number" class="item-price price-input" placeholder="0.00" step="0.01" value="${itemData?.unit_price || 0}">
      <input type="number" class="item-total total-input" placeholder="0.00" step="0.01" value="${itemData?.total || 0}" readonly>
      <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
  
  `;

    container.appendChild(row);

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

    qtyEl.addEventListener("input", updateTotal);
    priceEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));
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
    const unitCost = rejectData?.unit_cost || 0;
    const totalCost = rejectData?.total_cost || quantity * unitCost;

    row.innerHTML = `
            <div class="qa-material-name-field">
                <input type="text" class="reject-name-input qa-input-full" list="qa-reject-suggestions-${rowId}" placeholder="Type or choose reject item..." value="${rejectName}">
                <datalist id="qa-reject-suggestions-${rowId}"></datalist>
            </div>
            <input type="number" class="reject-qty qty-input" placeholder="QTY" step="1" min="0" value="${quantity}">
            <input type="number" class="reject-cost cost-input" placeholder="Cost/Unit" step="0.0001" value="${parseFloat(unitCost).toFixed(4)}">
            <input type="number" class="reject-total total-input" placeholder="Total" step="0.01" value="${parseFloat(totalCost).toFixed(2)}">
            <button class="qa-remove-row"><i class="fa-solid fa-trash"></i></button>
             
        `;

    container.appendChild(row);

    const nameInput = row.querySelector(".reject-name-input");
    const datalist = row.querySelector("datalist");
    if (datalist) {
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
      const match = this.materialsList.find(
        (m) => m.material_name.toLowerCase() === name.toLowerCase(),
      );
      if (match) {
        row.dataset.materialId = match.id;
        if (costEl) costEl.value = parseFloat(match.unit_cost || 0).toFixed(4);
        updateTotal();
      } else {
        row.dataset.materialId = "";
      }
    };

    nameInput.addEventListener("input", syncFromMaterialName);
    qtyEl.addEventListener("input", updateTotal);
    costEl.addEventListener("input", updateTotal);
    totalEl.addEventListener("input", setManualTotal);
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

    const description = itemData ? this.escapeHtml(itemData.description) : "";

    row.innerHTML = `
            <input type="text" class="item-name" placeholder="Item name" value="${description}" style="flex:2; padding:6px 10px; border:1px solid #e2e8f0; border-radius:4px; font-size:13px; background:#fff;">
            <input type="number" class="item-qty qty-input" placeholder="QTY" step="1" min="0" value="${itemData?.quantity || 1}" style="flex:0.8;">
            <input type="number" class="item-price price-input" placeholder="Unit Price" step="0.01" value="${itemData?.unit_price || 0}" style="flex:1;">
            <input type="number" class="item-total total-input" placeholder="Total Amount" step="0.01" value="${itemData?.total || 0}" style="flex:1;" readonly>
            <button class="qa-remove-row" style="flex:0.3;"><i class="fa-solid fa-trash"></i></button>
          
        `;

    container.appendChild(row);

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

    qtyEl.addEventListener("input", updateTotal);
    priceEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));
  }

  removeDynamicRow(button) {
    const row = button.closest(".qa-dynamic-row");
    if (row) {
      const rowId = row.id;
      this.materialSelectors = this.materialSelectors.filter(
        (s) => s.rowId !== rowId,
      );
      this.rejectSelectors = this.rejectSelectors.filter(
        (s) => s.rowId !== rowId,
      );
      row.remove();
      this.recalculateTotals();
    }
  }

  clearAuditRows() {
    const materialsContainer = document.getElementById(
      "qaMaterialCostsContainer",
    );
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

    this.clearAuditRows();

    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");

    if (createdBy) createdBy.value = "";
    if (auditedBy) auditedBy.value = "";
    if (acknowledgedBy) acknowledgedBy.value = "";

    this.clearQuotationInfo();
    this.resetOverheadSection();
    this.resetElectricityFields();
    this.recalculateTotals();

    const panel = document.getElementById("qaOverheadPanel");
    const icon = document.getElementById("qaOverheadIcon");
    const badge = document.getElementById("qaOverheadBadge");
    if (panel) {
      panel.classList.remove("open");
      panel.style.maxHeight = "0px";
    }
    if (icon) icon.classList.remove("open");
    if (badge) badge.textContent = "Click to expand";
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

  // ─── RECALCULATE TOTALS ──────────────────────────────────────────────────

  recalculateTotals() {
    let materialTotal = 0;
    let rejectTotal = 0;
    let amountTotal = 0;

    document
      .querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const totalInput = row.querySelector(".material-total");
        const qtyInput = row.querySelector(".material-qty");
        const costInput = row.querySelector(".material-cost");
        if (row.dataset.totalMode === "manual" && totalInput) {
          materialTotal += parseFloat(totalInput.value) || 0;
        } else if (qtyInput && costInput) {
          materialTotal +=
            (parseFloat(qtyInput.value) || 0) *
            (parseFloat(costInput.value) || 0);
        }
      });

    document
      .querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const totalInput = row.querySelector(".reject-total");
        const qtyInput = row.querySelector(".reject-qty");
        const costInput = row.querySelector(".reject-cost");
        if (row.dataset.totalMode === "manual" && totalInput) {
          rejectTotal += parseFloat(totalInput.value) || 0;
        } else if (qtyInput && costInput) {
          rejectTotal +=
            (parseFloat(qtyInput.value) || 0) *
            (parseFloat(costInput.value) || 0);
        }
      });

    document
      .querySelectorAll("#qaItemsContainer .item-total")
      .forEach((input) => {
        amountTotal += parseFloat(input.value) || 0;
      });

    const profit = amountTotal - (materialTotal + rejectTotal);

    const materialCostEl = document.getElementById("qaTotalMaterialCost");
    const rejectCostEl = document.getElementById("qaTotalRejectCost");
    const amountDueEl = document.getElementById("qaTotalAmountDue");
    const profitEl = document.getElementById("qaProfit");

    if (materialCostEl)
      materialCostEl.textContent = `₱${materialTotal.toFixed(2)}`;
    if (rejectCostEl) rejectCostEl.textContent = `₱${rejectTotal.toFixed(2)}`;
    if (amountDueEl) amountDueEl.textContent = `₱${amountTotal.toFixed(2)}`;
    if (profitEl) {
      profitEl.textContent = `₱${profit.toFixed(2)}`;
      profitEl.className = `value ${profit >= 0 ? "positive" : "negative"}`;
    }
  }

  // ============================================================
  // OVERHEAD & ELECTRICITY COMPUTATION
  // ============================================================

  renderOverheadRows() {
    return this.overheadCategories
      .map(
        (cat) => `
            <tr data-key="${cat.key}">
                <td class="category-label">${this.escapeHtml(cat.label)}</td>
                <td style="text-align:right;">
                    <input type="number" class="overhead-cost-input" data-key="${cat.key}" min="0" step="0.01" placeholder="0" value="0">
                </td>
            </tr>
        `,
      )
      .join("");
  }

  attachOverheadListeners() {
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      input.addEventListener("input", () => this.updateOverheadComputation());
    });

    const workingHoursInput = document.getElementById("qaWorkingHours");
    if (workingHoursInput) {
      workingHoursInput.addEventListener("input", () =>
        this.updateOverheadComputation(),
      );
    }

    const elecShop = document.getElementById("qaElecShop");
    const elecProduction = document.getElementById("qaElecProduction");
    if (elecShop) {
      elecShop.addEventListener("input", () =>
        this.updateOverheadComputation(),
      );
    }
    if (elecProduction) {
      elecProduction.addEventListener("input", () =>
        this.updateOverheadComputation(),
      );
    }
  }

  calculateTotalOverhead() {
    let total = 0;
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      total += parseFloat(input.value) || 0;
    });
    return total;
  }

  calculateOverheadPerHour(totalOverhead, workingHours) {
    const hours = parseFloat(workingHours) || 0;
    if (hours <= 0) return 0;
    return totalOverhead / hours;
  }

  updateOverheadComputation() {
    const total = this.calculateTotalOverhead();
    const workingHours =
      parseFloat(document.getElementById("qaWorkingHours")?.value) || 0;
    const perHour = this.calculateOverheadPerHour(total, workingHours);

    const totalEl = document.getElementById("qaTotalMonthlyOverhead");
    const perHourEl = document.getElementById("qaOverheadPerHour");

    if (totalEl) totalEl.textContent = `₱${total.toFixed(2)}`;
    if (perHourEl) perHourEl.textContent = `₱${perHour.toFixed(2)} / hour`;

    const panel = document.getElementById("qaOverheadPanel");
    if (
      panel &&
      panel.classList.contains("open") &&
      panel.style.maxHeight !== "none"
    ) {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  }

  collectElectricityData() {
    return {
      shop: parseFloat(document.getElementById("qaElecShop")?.value) || 0,
      production:
        parseFloat(document.getElementById("qaElecProduction")?.value) || 0,
    };
  }

  collectOverheadData() {
    const categories = {};
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      const key = input.dataset.key;
      categories[key] = parseFloat(input.value) || 0;
    });

    const totalMonthlyOverhead = this.calculateTotalOverhead();
    const workingHours =
      parseFloat(document.getElementById("qaWorkingHours")?.value) || 0;
    const overheadPerHour = this.calculateOverheadPerHour(
      totalMonthlyOverhead,
      workingHours,
    );

    return {
      categories,
      total_monthly_overhead: totalMonthlyOverhead,
      working_hours: workingHours,
      overhead_per_hour: overheadPerHour,
    };
  }

  resetOverheadSection() {
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      input.value = 0;
    });

    const workingHoursInput = document.getElementById("qaWorkingHours");
    if (workingHoursInput) workingHoursInput.value = 250;

    this.updateOverheadComputation();
  }

  resetElectricityFields() {
    const elecShop = document.getElementById("qaElecShop");
    const elecProduction = document.getElementById("qaElecProduction");
    if (elecShop) elecShop.value = 0;
    if (elecProduction) elecProduction.value = 0;
  }

  // ─── SUBMIT AUDIT ─────────────────────────────────────────────────────────

  // Replace the submitAudit method in quotation_audit_integration.js
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

    // Get audit name
    const auditName =
      document.getElementById("qaAuditItemName")?.value?.trim() || "";

    // Validate
    if (materials.length === 0 && items.length === 0) {
      this.showNotification(
        "Please add at least one material or item",
        "error",
      );
      return;
    }

    // Collect electricity and overhead data
    const electricity = {
      shop: parseFloat(document.getElementById("qaElecShop")?.value) || 0,
      production:
        parseFloat(document.getElementById("qaElecProduction")?.value) || 0,
    };

    const overheadData = {};
    document.querySelectorAll(".overhead-cost-input").forEach((input) => {
      const key = input.dataset.key;
      overheadData[key] = parseFloat(input.value) || 0;
    });

    const workingHours =
      parseFloat(document.getElementById("qaWorkingHours")?.value) || 250;

    // Build payload
    const payload = {
      audit_id:
        this.isEditMode && this.currentAuditData?.audit?.id
          ? this.currentAuditData.audit.id
          : null,
      quotation_id: this.currentQuotation?.id || null,
      audit_name: auditName,
      items: items,
      materials: materials,
      rejects: rejects,
      created_by: document.getElementById("qaCreatedBy")?.value?.trim() || "",
      audited_by: document.getElementById("qaAuditedBy")?.value?.trim() || "",
      acknowledged_by:
        document.getElementById("qaAcknowledgedBy")?.value?.trim() || "",
      electricity: electricity,
      overhead: overheadData,
      working_hours: workingHours,
      auto_compute: true,
    };

    console.log("Submitting payload:", payload);

    // If audit_id is null but we're in edit mode, something is wrong
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

      // First check if response is OK
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
        // Refresh data
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
