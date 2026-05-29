// assets/js/admin-site-functions/quotation_audit_integration.js
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
                        ${selectedOption ? this.escapeHtml(selectedOption.label) : "🔍 Select Material..."}
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
    this.quotationPage = 1;
    this.quotationSearch = "";
    this.materialsList = [];
    this.materialSelectors = [];
    this.rejectSelectors = [];

    this.api = {
      getDeliveredQuotations:
        "../../api/admin_site/inventory/get_delivered_quotations.php",
      getQuotationMaterials:
        "../../api/admin_site/inventory/get_quotation_materials_for_audit.php",
      createAudit: "../../api/admin_site/inventory/create_audit.php",
      getMaterials:
        "../../api/admin_site/inventory/get_materials_for_audit.php",
    };

    this.init();
  }

  async init() {
    this.setupQuotationSelectionModal();
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
            <div class="qa-quotation-item" data-id="${q.id}" data-number="${this.escapeHtml(q.quote_number)}">
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
                <div class="qa-quotation-total">₱ ${this.escapeHtml(q.total_formatted || q.total)}</div>
            </div>
        `,
      )
      .join("");

    document.querySelectorAll(".qa-quotation-item").forEach((item) => {
      item.addEventListener("click", () => {
        const id = parseInt(item.dataset.id);
        const number = item.dataset.number;
        this.selectQuotationForAudit(id, number);
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

  async selectQuotationForAudit(quotationId, quoteNumber) {
    this.closeQuotationModal();
    this.showAuditLoading(true);

    try {
      const response = await fetch(
        `${this.api.getQuotationMaterials}?id=${quotationId}`,
      );
      const result = await response.json();

      if (result.success) {
        this.currentQuotation = result.quotation;
        this.currentQuotationItems = result.items;
        this.prefillAuditForm(result);
        this.openAuditModal();
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

  openAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }
  }

  closeAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      modal.style.display = "none";
      document.body.style.overflow = "";
    }
    this.clearAuditForm();
  }

  closeQuotationModal() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      modal.style.display = "none";
      document.body.style.overflow = "";
    }
  }

  showAuditLoading(show) {
    const overlay = document.getElementById("auditLoadingOverlay");
    if (overlay) {
      overlay.style.display = show ? "flex" : "none";
    }
  }

  prefillAuditForm(data) {
    const { quotation, items, suggested_materials } = data;

    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput && items.length > 0) {
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = items[0].description;
      itemNameInput.value =
        tempDiv.textContent || tempDiv.innerText || items[0].description;
    }

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
    if (createdBy && quotation.client_name) {
      createdBy.value = quotation.client_name;
    }

    this.recalculateTotals();
  }

  addMaterialRow(materialData = null) {
    const container = document.getElementById("qaMaterialCostsContainer");
    if (!container) return;

    const rowId =
      "mat_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    row.innerHTML = `
            <div class="searchable-select" data-row="${rowId}" style="flex:2;"></div>
            <input type="number" class="material-qty qty-input" placeholder="QTY" step="1" min="0" value="${materialData?.suggested_quantity || 1}" style="flex:0.8; padding:8px; text-align:center;">
            <input type="number" class="material-cost cost-input" placeholder="Cost/Unit" step="0.01" value="${materialData?.unit_cost || 0}" style="flex:1; padding:8px; text-align:right;" readonly>
            <input type="number" class="material-total total-input" placeholder="Total" step="0.01" value="0" style="flex:1; padding:8px; text-align:right; background:#f8fafc;" readonly>
            <button class="qa-remove-row" style="flex:0.3;"><i class="fa-solid fa-trash"></i></button>
            ${materialData ? '<span class="qa-prefill-badge">from quotation</span>' : ""}
        `;

    container.appendChild(row);

    const options = this.materialsList.map((m) => ({
      value: m.id,
      label: m.material_name,
      cost: m.unit_cost,
      type: m.type,
    }));

    const selectorContainer = row.querySelector(".searchable-select");
    const selector = new SearchableSelect(
      selectorContainer,
      options,
      (value, cost, name) => {
        const costInput = row.querySelector(".material-cost");
        if (costInput) {
          costInput.value = parseFloat(cost).toFixed(4);
        }
        row.dataset.materialId = value;
        this.updateMaterialTotal(row);
      },
      materialData?.material_id || null,
    );

    this.materialSelectors.push({ rowId, selector });

    const qtyEl = row.querySelector(".material-qty");
    const costEl = row.querySelector(".material-cost");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      const totalInput = row.querySelector(".material-total");
      if (totalInput) totalInput.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    qtyEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (materialData && materialData.material_id) {
      const cost = parseFloat(materialData.unit_cost || 0);
      costEl.value = cost.toFixed(4);
      updateTotal();
    }
  }

  updateMaterialTotal(row) {
    const qty = parseFloat(row.querySelector(".material-qty")?.value) || 0;
    const cost = parseFloat(row.querySelector(".material-cost")?.value) || 0;
    const total = qty * cost;
    const totalInput = row.querySelector(".material-total");
    if (totalInput) totalInput.value = total.toFixed(2);
    this.recalculateTotals();
  }

  addRejectRow(rejectData = null) {
    const container = document.getElementById("qaRejectCostsContainer");
    if (!container) return;

    const rowId =
      "rej_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;

    row.innerHTML = `
            <div class="searchable-select" data-row="${rowId}" style="flex:2;"></div>
            <input type="number" class="reject-qty qty-input" placeholder="QTY" step="1" min="0" value="${rejectData?.quantity || 1}" style="flex:0.8; padding:8px; text-align:center;">
            <input type="number" class="reject-cost cost-input" placeholder="Cost/Unit" step="0.01" value="${rejectData?.unit_cost || 0}" style="flex:1; padding:8px; text-align:right;" readonly>
            <input type="number" class="reject-total total-input" placeholder="Total" step="0.01" value="0" style="flex:1; padding:8px; text-align:right; background:#f8fafc;" readonly>
            <button class="qa-remove-row" style="flex:0.3;"><i class="fa-solid fa-trash"></i></button>
            ${rejectData ? '<span class="qa-prefill-badge">from quotation</span>' : ""}
        `;

    container.appendChild(row);

    const options = this.materialsList.map((m) => ({
      value: m.id,
      label: m.material_name,
      cost: m.unit_cost,
      type: m.type,
    }));

    const selectorContainer = row.querySelector(".searchable-select");
    const selector = new SearchableSelect(
      selectorContainer,
      options,
      (value, cost, name) => {
        const costInput = row.querySelector(".reject-cost");
        if (costInput) {
          costInput.value = parseFloat(cost).toFixed(4);
        }
        row.dataset.materialId = value;
        this.updateRejectTotal(row);
      },
      rejectData?.material_id || null,
    );

    this.rejectSelectors.push({ rowId, selector });

    const qtyEl = row.querySelector(".reject-qty");
    const costEl = row.querySelector(".reject-cost");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      const totalInput = row.querySelector(".reject-total");
      if (totalInput) totalInput.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    qtyEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (rejectData && rejectData.material_id) {
      const cost = parseFloat(rejectData.unit_cost || 0);
      costEl.value = cost.toFixed(4);
      updateTotal();
    }
  }

  updateRejectTotal(row) {
    const qty = parseFloat(row.querySelector(".reject-qty")?.value) || 0;
    const cost = parseFloat(row.querySelector(".reject-cost")?.value) || 0;
    const total = qty * cost;
    const totalInput = row.querySelector(".reject-total");
    if (totalInput) totalInput.value = total.toFixed(2);
    this.recalculateTotals();
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
            <input type="text" class="item-name" placeholder="Item name" value="${description}" style="flex:2; padding:8px;">
            <input type="number" class="item-qty qty-input" placeholder="QTY" step="1" min="0" value="${itemData?.quantity || 1}" style="flex:0.8; padding:8px; text-align:center;">
            <input type="number" class="item-price price-input" placeholder="Unit Price" step="0.01" value="${itemData?.unit_price || 0}" style="flex:1; padding:8px; text-align:right;">
            <input type="number" class="item-total total-input" placeholder="Total Amount" step="0.01" value="${itemData?.total || 0}" style="flex:1; padding:8px; text-align:right; background:#f8fafc;" readonly>
            <button class="qa-remove-row" style="flex:0.3;"><i class="fa-solid fa-trash"></i></button>
            ${itemData ? '<span class="qa-prefill-badge">from quotation</span>' : ""}
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
    if (itemNameInput) itemNameInput.value = "";

    this.clearAuditRows();

    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");

    if (createdBy) createdBy.value = "";
    if (auditedBy) auditedBy.value = "";
    if (acknowledgedBy) acknowledgedBy.value = "";

    this.recalculateTotals();
  }

  recalculateTotals() {
    let materialTotal = 0;
    let rejectTotal = 0;
    let amountTotal = 0;

    document
      .querySelectorAll("#qaMaterialCostsContainer .material-total")
      .forEach((input) => {
        materialTotal += parseFloat(input.value) || 0;
      });

    document
      .querySelectorAll("#qaRejectCostsContainer .reject-total")
      .forEach((input) => {
        rejectTotal += parseFloat(input.value) || 0;
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
    if (profitEl) profitEl.textContent = `₱${profit.toFixed(2)}`;
  }

  async submitAudit() {
    const materials = [];
    document
      .querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = row.dataset.materialId
          ? parseInt(row.dataset.materialId)
          : 0;
        const materialName =
          row.querySelector(".selected-text")?.textContent || "";
        const quantity =
          parseFloat(row.querySelector(".material-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".material-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".material-total")?.value) || 0;

        if (materialId > 0 && quantity > 0) {
          materials.push({
            id: materialId,
            name: materialName,
            quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
          });
        }
      });

    const rejects = [];
    document
      .querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = row.dataset.materialId
          ? parseInt(row.dataset.materialId)
          : 0;
        const materialName =
          row.querySelector(".selected-text")?.textContent || "";
        const quantity =
          parseFloat(row.querySelector(".reject-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".reject-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".reject-total")?.value) || 0;

        if (materialId > 0 && quantity > 0) {
          rejects.push({
            id: materialId,
            name: materialName,
            quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
          });
        }
      });

    const items = [];
    document
      .querySelectorAll("#qaItemsContainer .qa-dynamic-row")
      .forEach((row) => {
        const name = row.querySelector(".item-name")?.value || "";
        const quantity = parseFloat(row.querySelector(".item-qty")?.value) || 0;
        const unitPrice =
          parseFloat(row.querySelector(".item-price")?.value) || 0;
        const totalAmount =
          parseFloat(row.querySelector(".item-total")?.value) || 0;

        if (name && quantity > 0) {
          items.push({
            name,
            quantity,
            unit_price: unitPrice,
            total_amount: totalAmount,
          });
        }
      });

    const itemName = document.getElementById("qaAuditItemName")?.value;
    if (itemName && !items.some((i) => i.name === itemName)) {
      items.unshift({
        name: itemName,
        quantity: 1,
        unit_price: 0,
        total_amount: 0,
      });
    }

    if (materials.length === 0 && items.length === 0) {
      this.showNotification(
        "Please add at least one material or item",
        "error",
      );
      return;
    }

    const payload = {
      items: items,
      materials: materials,
      rejects: rejects,
      created_by: document.getElementById("qaCreatedBy")?.value || "",
      audited_by: document.getElementById("qaAuditedBy")?.value || "",
      acknowledged_by: document.getElementById("qaAcknowledgedBy")?.value || "",
      auto_compute: true,
    };

    this.showAuditLoading(true);

    try {
      const response = await fetch(this.api.createAudit, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const result = await response.json();

      if (result.success) {
        this.closeAuditModal();
        this.showNotification(
          "Audit created successfully! Inventory has been updated.",
          "success",
        );
        if (typeof matReloadMaterials === "function") matReloadMaterials(true);
        if (typeof matReloadLogs === "function") matReloadLogs(true);
      } else {
        this.showNotification(
          result.message || "Failed to create audit",
          "error",
        );
      }
    } catch (error) {
      console.error("Error creating audit:", error);
      this.showNotification("Error creating audit: " + error.message, "error");
    } finally {
      this.showAuditLoading(false);
    }
  }

  overrideAuditButton() {
    window.matOpenAuditModal = () => {
      this.openQuotationSelector();
    };
  }

  openQuotationSelector() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      this.quotationPage = 1;
      this.quotationSearch = "";
      const searchInput = document.getElementById("qaSearchInput");
      if (searchInput) searchInput.value = "";
      this.loadDeliveredQuotations();
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }
  }

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

let quotationAuditIntegration;
document.addEventListener("DOMContentLoaded", () => {
  quotationAuditIntegration = new QuotationAuditIntegration();
});
// assets/js/admin-site-functions/quotation_audit_integration.js
/**
 * Quotation Audit Integration Module
 * Handles quotation selection and audit creation with searchable dropdown and manual material entry
 */

class QuotationAuditIntegration {
  constructor() {
    this.currentQuotation = null;
    this.currentQuotationItems = [];
    this.quotationPage = 1;
    this.quotationSearch = "";
    this.materialsList = [];

    // API Endpoints
    this.api = {
      getDeliveredQuotations:
        "../../api/admin_site/inventory/get_delivered_quotations.php",
      getQuotationMaterials:
        "../../api/admin_site/inventory/get_quotation_materials_for_audit.php",
      createAudit: "../../api/admin_site/inventory/create_audit.php",
      getMaterials:
        "../../api/admin_site/inventory/get_materials_for_audit.php",
    };

    this.init();
  }

  async init() {
    this.setupQuotationSelectionModal();
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

  setupQuotationSelectionModal() {
    if (!document.getElementById("qaQuotationModal")) {
      const modalHtml = `
        <div id="qaQuotationModal" class="qa-quotation-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:100060; align-items:center; justify-content:center;">
          <div class="qa-quotation-container" style="background:#fff; border-radius:16px; width:90%; max-width:600px; max-height:80vh; display:flex; flex-direction:column; overflow:hidden;">
            <div class="qa-quotation-header" style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:1px solid #e5e7eb;">
              <h3 style="margin:0;"><i class="fa-solid fa-clipboard-list"></i> Select Delivered Quotation</h3>
              <button class="qa-quotation-close" onclick="quotationAuditIntegration.closeQuotationModal()" style="background:none; border:none; font-size:1.3rem; cursor:pointer;">
                <i class="fa-solid fa-times"></i>
              </button>
            </div>
            <div class="qa-quotation-search" style="padding:16px 24px;">
              <input type="text" id="qaSearchInput" placeholder="Search by Quote #, Client Name..." autocomplete="off" style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px;">
            </div>
            <div class="qa-quotation-list" id="qaQuotationList" style="flex:1; overflow-y:auto; padding:0 24px 24px 24px;">
              <div class="qa-loading" style="text-align:center; padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</div>
            </div>
            <div class="qa-pagination" id="qaPagination" style="padding:16px 24px; border-top:1px solid #e5e7eb; display:flex; justify-content:center; gap:8px;"></div>
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
      '<div class="qa-loading" style="text-align:center; padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...</div>';

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
        listContainer.innerHTML = `<div class="qa-empty" style="text-align:center; padding:40px; color:#6b7280;">${result.message || "Failed to load quotations"}</div>`;
      }
    } catch (error) {
      console.error("Error loading quotations:", error);
      listContainer.innerHTML =
        '<div class="qa-empty" style="text-align:center; padding:40px; color:#6b7280;">Error loading quotations. Please try again.</div>';
    }
  }

  renderQuotationList(quotations, pagination) {
    const listContainer = document.getElementById("qaQuotationList");
    const paginationContainer = document.getElementById("qaPagination");

    if (!listContainer) return;

    if (!quotations || quotations.length === 0) {
      listContainer.innerHTML =
        '<div class="qa-empty" style="text-align:center; padding:40px; color:#6b7280;"><i class="fa-solid fa-inbox"></i> No delivered quotations found</div>';
      if (paginationContainer) paginationContainer.innerHTML = "";
      return;
    }

    listContainer.innerHTML = quotations
      .map(
        (q) => `
      <div class="qa-quotation-item" data-id="${q.id}" data-number="${this.escapeHtml(q.quote_number)}" style="display:flex; justify-content:space-between; align-items:center; padding:16px; border:1px solid #e5e7eb; border-radius:12px; margin-bottom:12px; cursor:pointer; transition:all 0.2s;">
        <div>
          <div class="qa-quotation-number" style="font-weight:700; color:#111;">${this.escapeHtml(q.quote_number)}</div>
          <div class="qa-quotation-client" style="font-size:0.85rem; color:#6b7280; margin-top:4px;">
            <i class="fa-solid fa-building"></i> ${this.escapeHtml(q.client_name)}
            ${q.contact_person ? ` • <i class="fa-solid fa-user"></i> ${this.escapeHtml(q.contact_person)}` : ""}
          </div>
          <div class="qa-quotation-date" style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">
            <i class="fa-regular fa-calendar"></i> ${q.created_date || new Date(q.created_at).toLocaleDateString()}
            ${q.item_count ? ` • <i class="fa-solid fa-box"></i> ${q.item_count} items` : ""}
          </div>
        </div>
        <div class="qa-quotation-total" style="font-weight:700; color:#10b981;">₱ ${this.escapeHtml(q.total_formatted || q.total)}</div>
      </div>
    `,
      )
      .join("");

    document.querySelectorAll(".qa-quotation-item").forEach((item) => {
      item.addEventListener("click", () => {
        const id = parseInt(item.dataset.id);
        const number = item.dataset.number;
        this.selectQuotationForAudit(id, number);
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
    html += `<button class="qa-page-btn" ${pagination.current_page <= 1 ? "disabled" : ""} onclick="quotationAuditIntegration.goToPage(${pagination.current_page - 1})" style="padding:6px 12px; border:1px solid #d1d5db; background:#fff; border-radius:6px; cursor:pointer;"><i class="fa-solid fa-chevron-left"></i></button>`;

    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, startPage + 4);

    if (startPage > 1) {
      html += `<button class="qa-page-btn" onclick="quotationAuditIntegration.goToPage(1)" style="padding:6px 12px; border:1px solid #d1d5db; background:#fff; border-radius:6px; cursor:pointer;">1</button>`;
      if (startPage > 2)
        html += `<span class="qa-page-dots" style="padding:0 8px;">...</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      html += `<button class="qa-page-btn ${i === pagination.current_page ? "active" : ""}" onclick="quotationAuditIntegration.goToPage(${i})" style="padding:6px 12px; border:1px solid #d1d5db; background:${i === pagination.current_page ? "#2563eb" : "#fff"}; color:${i === pagination.current_page ? "#fff" : "#374151"}; border-radius:6px; cursor:pointer;">${i}</button>`;
    }

    if (endPage < pagination.last_page) {
      if (endPage < pagination.last_page - 1)
        html += `<span class="qa-page-dots" style="padding:0 8px;">...</span>`;
      html += `<button class="qa-page-btn" onclick="quotationAuditIntegration.goToPage(${pagination.last_page})" style="padding:6px 12px; border:1px solid #d1d5db; background:#fff; border-radius:6px; cursor:pointer;">${pagination.last_page}</button>`;
    }

    html += `<button class="qa-page-btn" ${pagination.current_page >= pagination.last_page ? "disabled" : ""} onclick="quotationAuditIntegration.goToPage(${pagination.current_page + 1})" style="padding:6px 12px; border:1px solid #d1d5db; background:#fff; border-radius:6px; cursor:pointer;"><i class="fa-solid fa-chevron-right"></i></button>`;

    container.innerHTML = html;
  }

  goToPage(page) {
    this.quotationPage = page;
    this.loadDeliveredQuotations();
  }

  async selectQuotationForAudit(quotationId, quoteNumber) {
    this.closeQuotationModal();
    this.showAuditLoading(true);

    try {
      const response = await fetch(
        `${this.api.getQuotationMaterials}?id=${quotationId}`,
      );
      const result = await response.json();

      if (result.success) {
        this.currentQuotation = result.quotation;
        this.currentQuotationItems = result.items;
        this.prefillAuditForm(result);
        this.openAuditModal();
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

  openAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }
  }

  closeAuditModal() {
    const modal = document.getElementById("quotationAuditModal");
    if (modal) {
      modal.style.display = "none";
      document.body.style.overflow = "";
    }
    this.clearAuditForm();
  }

  closeQuotationModal() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      modal.style.display = "none";
      document.body.style.overflow = "";
    }
  }

  showAuditLoading(show) {
    const overlay = document.getElementById("auditLoadingOverlay");
    if (overlay) {
      overlay.style.display = show ? "flex" : "none";
    }
  }

  prefillAuditForm(data) {
    const { quotation, items, suggested_materials } = data;

    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput && items.length > 0) {
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = items[0].description;
      itemNameInput.value =
        tempDiv.textContent || tempDiv.innerText || items[0].description;
    }

    this.clearAuditRows();

    if (suggested_materials && suggested_materials.length > 0) {
      suggested_materials.forEach((material) => {
        this.addMaterialRowWithManualEntry(material);
      });
    } else {
      this.addMaterialRowWithManualEntry();
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
    if (createdBy && quotation.client_name) {
      createdBy.value = quotation.client_name;
    }

    this.recalculateTotals();
  }

  addMaterialRowWithManualEntry(materialData = null) {
    const container = document.getElementById("qaMaterialCostsContainer");
    if (!container) return;

    const rowId =
      "mat_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;
    row.style.cssText =
      "display:flex; gap:12px; align-items:center; padding:8px 0; position:relative; overflow:visible;";

    row.innerHTML = `
      <div class="searchable-select" data-row="${rowId}" style="flex:2; position:relative;"></div>
      <input type="number" class="material-qty qty-input" placeholder="QTY" step="1" min="0" value="${materialData?.suggested_quantity || 1}" style="flex:0.8; padding:8px; text-align:center; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="material-cost cost-input" placeholder="Cost/Unit" step="0.01" value="${materialData?.unit_cost || 0}" style="flex:1; padding:8px; text-align:right; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="material-total total-input" placeholder="Total" step="0.01" value="0" style="flex:1; padding:8px; text-align:right; background:#f0fdf4; border:1px solid #d1d5db; border-radius:8px;" readonly>
      <button class="qa-remove-row" style="flex:0.3; background:none; border:none; cursor:pointer; color:#ef4444; font-size:1rem;"><i class="fa-solid fa-trash"></i></button>
      ${materialData && !materialData.isCustom ? '<span class="qa-prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>' : ""}
    `;

    container.appendChild(row);

    const options = this.materialsList.map((m) => ({
      value: m.id,
      label: m.material_name,
      cost: m.unit_cost,
      type: m.type,
    }));

    const selectorContainer = row.querySelector(".searchable-select");
    const selector = this.createSearchableSelect(
      selectorContainer,
      options,
      (value, cost, name) => {
        const costInput = row.querySelector(".material-cost");
        if (costInput) {
          costInput.value = parseFloat(cost).toFixed(4);
        }
        row.dataset.materialId = value;
        row.dataset.materialName = name;
        row.dataset.isCustom = "false";
        this.updateMaterialTotal(row);
      },
      materialData?.material_id && !materialData?.isCustom
        ? materialData.material_id
        : null,
    );

    if (!window.materialSelectors) window.materialSelectors = [];
    window.materialSelectors.push({ rowId, selector });

    const qtyEl = row.querySelector(".material-qty");
    const costEl = row.querySelector(".material-cost");
    const totalEl = row.querySelector(".material-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      totalEl.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    qtyEl.addEventListener("input", updateTotal);
    costEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (materialData && materialData.isCustom) {
      costEl.value = parseFloat(materialData.unit_cost || 0).toFixed(4);
      row.dataset.materialId = -1;
      row.dataset.materialName = materialData.name;
      row.dataset.isCustom = "true";
      updateTotal();
    } else if (
      materialData &&
      materialData.material_id &&
      !materialData.isCustom
    ) {
      const cost = parseFloat(materialData.unit_cost || 0);
      costEl.value = cost.toFixed(4);
      updateTotal();
    }
  }
  createSearchableSelect(container, options, onSelect, selectedValue = null) {
    let isOpen = false;
    let selectedOption = options.find((opt) => opt.value == selectedValue);
    let currentSearchTerm = "";
    let customName = "";
    let customCost = 0;

    const render = (searchTerm = "") => {
      currentSearchTerm = searchTerm;
      let filtered = options;
      let showCustom = false;

      if (searchTerm) {
        const term = searchTerm.toLowerCase();
        showCustom = !options.some(
          (opt) =>
            opt.label.toLowerCase() === term ||
            opt.label.toLowerCase().includes(term),
        );
        filtered = options.filter(
          (opt) =>
            opt.label.toLowerCase().includes(term) ||
            (opt.type && opt.type.toLowerCase().includes(term)),
        );
      }

      const selectedText = container.querySelector(".selected-text");
      if (selectedText && selectedOption && !searchTerm) {
        selectedText.textContent = selectedOption.label;
        selectedText.classList.remove("placeholder");
      } else if (selectedText && selectedOption) {
        selectedText.textContent = selectedOption.label;
      } else if (selectedText && customName) {
        selectedText.textContent = `${customName} (Custom)`;
        selectedText.classList.remove("placeholder");
      }

      const listContainer = container.querySelector(".searchable-select-list");
      if (listContainer) {
        if (filtered.length === 0 && !showCustom) {
          listContainer.innerHTML =
            '<div class="no-results" style="padding:20px; text-align:center; color:#6b7280;"><i class="fa-solid fa-search"></i> No materials found</div>';
        } else {
          listContainer.innerHTML = filtered
            .map(
              (opt) => `
          <div class="searchable-select-item" data-value="${opt.value}" data-cost="${opt.cost}" data-name="${escapeHtml(opt.label)}" style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; cursor:pointer; border-bottom:1px solid #f3f4f6;">
            <div>
              <div class="item-name" style="font-weight:500;">${escapeHtml(opt.label)}</div>
              ${
                opt.type
                  ? `<div class="item-stock" style="font-size:0.7rem; color:#6b7280;"><i class="fa-solid fa-tag"></i> ${escapeHtml(
                      opt.type,
                    )}</div>`
                  : ""
              }
            </div>
            <div class="item-cost" style="font-weight:600; color:#059669;">₱${parseFloat(
              opt.cost || 0,
            ).toFixed(2)}</div>
          </div>
        `,
            )
            .join("");
        }
      }

      const customSection = container.querySelector(
        ".searchable-select-custom",
      );
      if (customSection) {
        customSection.style.display = showCustom ? "flex" : "none";
        if (showCustom) {
          const costInput = customSection.querySelector('input[type="number"]');
          if (costInput && !costInput.value) {
            costInput.value = "0";
          }
        }
      }

      listContainer
        ?.querySelectorAll(".searchable-select-item")
        .forEach((item) => {
          item.addEventListener("click", (e) => {
            e.stopPropagation();
            const value = parseInt(item.dataset.value);
            const cost = parseFloat(item.dataset.cost);
            const name = item.dataset.name;
            selectedOption = { value, label: name, cost };
            customName = "";
            customCost = 0;

            const selectedText = container.querySelector(".selected-text");
            if (selectedText) {
              selectedText.textContent = name;
              selectedText.classList.remove("placeholder");
            }

            if (onSelect) onSelect(value, cost, name);
            close();
          });
        });
    };

    const open = () => {
      const dropdown = container.querySelector(".searchable-select-dropdown");
      const input = container.querySelector(".searchable-select-input");
      if (dropdown) {
        // Get the position of the container relative to viewport
        const rect = container.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const dropdownHeight = 350;

        // If not enough space below, position above
        if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
          dropdown.style.top = "auto";
          dropdown.style.bottom = "100%";
          dropdown.style.marginTop = "0";
          dropdown.style.marginBottom = "4px";
        } else {
          dropdown.style.top = "100%";
          dropdown.style.bottom = "auto";
          dropdown.style.marginTop = "4px";
          dropdown.style.marginBottom = "0";
        }

        dropdown.classList.add("show");
        dropdown.style.display = "flex";
        isOpen = true;
        if (input) input.classList.add("open");
        const searchInput = container.querySelector(
          ".searchable-select-search input",
        );
        if (searchInput) {
          searchInput.value = "";
          render("");
          setTimeout(() => searchInput.focus(), 50);
        }
      }
    };

    const close = () => {
      const dropdown = container.querySelector(".searchable-select-dropdown");
      const input = container.querySelector(".searchable-select-input");
      if (dropdown) {
        dropdown.classList.remove("show");
        dropdown.style.display = "none";
        isOpen = false;
        if (input) input.classList.remove("open");
      }
      render("");
    };

    const toggle = () => (isOpen ? close() : open());

    // Ensure dropdown is closed when clicking outside
    const handleClickOutside = (e) => {
      if (container && !container.contains(e.target)) {
        close();
      }
    };

    document.removeEventListener("click", handleClickOutside);
    document.addEventListener("click", handleClickOutside);

    container.innerHTML = `
    <div class="searchable-select" style="position:relative; width:100%;">
      <div class="searchable-select-input" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; background:#f9fafb; cursor:pointer;">
        <span class="selected-text ${
          !selectedOption ? "placeholder" : ""
        }" style="flex:1; color:${
          !selectedOption ? "#9ca3af" : "#111"
        };">${selectedOption ? escapeHtml(selectedOption.label) : "🔍 Search or type material..."}</span>
        <i class="fa-solid fa-chevron-down"></i>
      </div>
      <div class="searchable-select-dropdown" style="display:none; position:absolute; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); z-index:100000; flex-direction:column; max-height:350px; overflow:hidden; min-width:280px;">
        <div class="searchable-select-search" style="padding:10px; border-bottom:1px solid #e5e7eb;">
          <input type="text" placeholder="Search or type new material name..." autocomplete="off" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box;">
        </div>
        <div class="searchable-select-custom" style="display:none; padding:12px; border-top:1px solid #e5e7eb; background:#f9fafb; gap:10px;">
          <div class="custom-cost-input" style="flex:2;">
            <label style="display:block; font-size:0.7rem; color:#6b7280; margin-bottom:4px;">Unit Cost (₱)</label>
            <input type="number" step="0.01" placeholder="0.00" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px; box-sizing:border-box;">
          </div>
          <div class="custom-add-btn" style="flex:1;">
            <button type="button" class="add-custom-material" style="width:100%; padding:8px 12px; background:#10b981; color:#fff; border:none; border-radius:6px; cursor:pointer;">
              <i class="fa-solid fa-plus"></i> Use Custom
            </button>
          </div>
        </div>
        <div class="searchable-select-list" style="max-height:250px; overflow-y:auto;"></div>
      </div>
    </div>
  `;

    const inputDiv = container.querySelector(".searchable-select-input");
    const searchInput = container.querySelector(
      ".searchable-select-search input",
    );
    const addBtn = container.querySelector(".add-custom-material");
    const costInputField = container.querySelector(
      ".searchable-select-custom input[type='number']",
    );

    inputDiv?.addEventListener("click", (e) => {
      e.stopPropagation();
      toggle();
    });

    searchInput?.addEventListener("input", (e) => {
      render(e.target.value);
    });

    if (addBtn) {
      addBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const term = searchInput?.value.trim();
        const customCostValue = costInputField
          ? parseFloat(costInputField.value) || 0
          : 0;

        if (!term) {
          alert("Please enter a material name");
          return;
        }

        customName = term;
        customCost = customCostValue;
        selectedOption = null;

        const selectedText = container.querySelector(".selected-text");
        if (selectedText) {
          selectedText.textContent = `${term} (Custom)`;
          selectedText.classList.remove("placeholder");
        }

        if (onSelect) onSelect(-1, customCostValue, term);
        close();
      });
    }

    render("");

    // Cleanup function (optional)
    return {
      getValue: () => selectedOption?.value || -1,
      getCustomData: () =>
        customName ? { name: customName, cost: customCost } : null,
      destroy: () => {
        document.removeEventListener("click", handleClickOutside);
      },
    };
  }
  updateMaterialTotal(row) {
    const qty = parseFloat(row.querySelector(".material-qty")?.value) || 0;
    const cost = parseFloat(row.querySelector(".material-cost")?.value) || 0;
    const total = qty * cost;
    const totalInput = row.querySelector(".material-total");
    if (totalInput) totalInput.value = total.toFixed(2);
    this.recalculateTotals();
  }

  addRejectRow(rejectData = null) {
    const container = document.getElementById("qaRejectCostsContainer");
    if (!container) return;

    const rowId =
      "rej_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;
    row.style.cssText =
      "display:flex; gap:12px; align-items:center; padding:8px 0; position:relative; overflow:visible;";

    row.innerHTML = `
      <div class="searchable-select" data-row="${rowId}" style="flex:2; position:relative;"></div>
      <input type="number" class="reject-qty qty-input" placeholder="QTY" step="1" min="0" value="${rejectData?.quantity || 1}" style="flex:0.8; padding:8px; text-align:center; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="reject-cost cost-input" placeholder="Cost/Unit" step="0.01" value="${rejectData?.unit_cost || 0}" style="flex:1; padding:8px; text-align:right; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="reject-total total-input" placeholder="Total" step="0.01" value="0" style="flex:1; padding:8px; text-align:right; background:#f0fdf4; border:1px solid #d1d5db; border-radius:8px;" readonly>
      <button class="qa-remove-row" style="flex:0.3; background:none; border:none; cursor:pointer; color:#ef4444; font-size:1rem;"><i class="fa-solid fa-trash"></i></button>
      ${rejectData ? '<span class="qa-prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>' : ""}
    `;

    container.appendChild(row);

    const options = this.materialsList.map((m) => ({
      value: m.id,
      label: m.material_name,
      cost: m.unit_cost,
      type: m.type,
    }));

    const selectorContainer = row.querySelector(".searchable-select");
    const selector = this.createSearchableSelect(
      selectorContainer,
      options,
      (value, cost, name) => {
        const costInput = row.querySelector(".reject-cost");
        if (costInput) {
          costInput.value = parseFloat(cost).toFixed(4);
        }
        row.dataset.materialId = value;
        this.updateRejectTotal(row);
      },
      rejectData?.material_id || null,
    );

    if (!window.rejectSelectors) window.rejectSelectors = [];
    window.rejectSelectors.push({ rowId, selector });

    const qtyEl = row.querySelector(".reject-qty");
    const costEl = row.querySelector(".reject-cost");
    const totalEl = row.querySelector(".reject-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const cost = parseFloat(costEl.value) || 0;
      totalEl.value = (qty * cost).toFixed(2);
      this.recalculateTotals();
    };

    qtyEl.addEventListener("input", updateTotal);
    costEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));

    if (rejectData && rejectData.material_id) {
      const cost = parseFloat(rejectData.unit_cost || 0);
      costEl.value = cost.toFixed(4);
      updateTotal();
    }
  }

  updateRejectTotal(row) {
    const qty = parseFloat(row.querySelector(".reject-qty")?.value) || 0;
    const cost = parseFloat(row.querySelector(".reject-cost")?.value) || 0;
    const total = qty * cost;
    const totalInput = row.querySelector(".reject-total");
    if (totalInput) totalInput.value = total.toFixed(2);
    this.recalculateTotals();
  }

  addItemRow(itemData = null) {
    const container = document.getElementById("qaItemsContainer");
    if (!container) return;

    const rowId =
      "item_" + Date.now() + "_" + Math.random().toString(36).substr(2, 6);
    const row = document.createElement("div");
    row.className = "qa-dynamic-row";
    row.id = rowId;
    row.style.cssText =
      "display:flex; gap:12px; align-items:center; padding:8px 0;";

    const description = itemData ? this.escapeHtml(itemData.description) : "";

    row.innerHTML = `
      <input type="text" class="item-name" placeholder="Item name" value="${description}" style="flex:2; padding:8px; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="item-qty qty-input" placeholder="QTY" step="1" min="0" value="${itemData?.quantity || 1}" style="flex:0.8; padding:8px; text-align:center; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="item-price price-input" placeholder="Unit Price" step="0.01" value="${itemData?.unit_price || 0}" style="flex:1; padding:8px; text-align:right; border:1px solid #d1d5db; border-radius:8px;">
      <input type="number" class="item-total total-input" placeholder="Total Amount" step="0.01" value="${itemData?.total || 0}" style="flex:1; padding:8px; text-align:right; background:#f0fdf4; border:1px solid #d1d5db; border-radius:8px;" readonly>
      <button class="qa-remove-row" style="flex:0.3; background:none; border:none; cursor:pointer; color:#ef4444; font-size:1rem;"><i class="fa-solid fa-trash"></i></button>
      ${itemData ? '<span class="qa-prefill-badge" style="background:#dbeafe; color:#1e40af; font-size:0.7rem; padding:2px 6px; border-radius:4px;">from quotation</span>' : ""}
    `;

    container.appendChild(row);

    const qtyEl = row.querySelector(".item-qty");
    const priceEl = row.querySelector(".item-price");
    const totalEl = row.querySelector(".item-total");
    const removeBtn = row.querySelector(".qa-remove-row");

    const updateTotal = () => {
      const qty = parseFloat(qtyEl.value) || 0;
      const price = parseFloat(priceEl.value) || 0;
      totalEl.value = (qty * price).toFixed(2);
      this.recalculateTotals();
    };

    qtyEl.addEventListener("input", updateTotal);
    priceEl.addEventListener("input", updateTotal);
    removeBtn.addEventListener("click", () => this.removeDynamicRow(removeBtn));
  }

  removeDynamicRow(button) {
    const row = button.closest(".qa-dynamic-row");
    if (row) row.remove();
    this.recalculateTotals();
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
  }

  clearAuditForm() {
    const itemNameInput = document.getElementById("qaAuditItemName");
    if (itemNameInput) itemNameInput.value = "";

    this.clearAuditRows();

    const createdBy = document.getElementById("qaCreatedBy");
    const auditedBy = document.getElementById("qaAuditedBy");
    const acknowledgedBy = document.getElementById("qaAcknowledgedBy");

    if (createdBy) createdBy.value = "";
    if (auditedBy) auditedBy.value = "";
    if (acknowledgedBy) acknowledgedBy.value = "";

    this.recalculateTotals();
  }

  recalculateTotals() {
    let materialTotal = 0;
    let rejectTotal = 0;
    let amountTotal = 0;

    document
      .querySelectorAll("#qaMaterialCostsContainer .material-total")
      .forEach((input) => {
        materialTotal += parseFloat(input.value) || 0;
      });

    document
      .querySelectorAll("#qaRejectCostsContainer .reject-total")
      .forEach((input) => {
        rejectTotal += parseFloat(input.value) || 0;
      });

    document
      .querySelectorAll("#qaItemsContainer .item-total")
      .forEach((input) => {
        amountTotal += parseFloat(input.value) || 0;
      });

    const profit = amountTotal - (materialTotal + rejectTotal);

    document.getElementById("qaTotalMaterialCost").textContent =
      `₱${materialTotal.toFixed(2)}`;
    document.getElementById("qaTotalRejectCost").textContent =
      `₱${rejectTotal.toFixed(2)}`;
    document.getElementById("qaTotalAmountDue").textContent =
      `₱${amountTotal.toFixed(2)}`;
    document.getElementById("qaProfit").textContent = `₱${profit.toFixed(2)}`;
  }

  async submitAudit() {
    const materials = [];
    document
      .querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = parseInt(row.dataset.materialId) || 0;
        const materialName =
          row.dataset.materialName ||
          row
            .querySelector(".selected-text")
            ?.textContent?.replace(" (Custom)", "") ||
          "";
        const quantity =
          parseFloat(row.querySelector(".material-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".material-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".material-total")?.value) || 0;

        if (quantity > 0) {
          materials.push({
            id: materialId,
            name: materialName,
            quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
            is_custom: materialId === -1,
          });
        }
      });

    const rejects = [];
    document
      .querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row")
      .forEach((row) => {
        const materialId = parseInt(row.dataset.materialId) || 0;
        const materialName = row.dataset.materialName || "";
        const quantity =
          parseFloat(row.querySelector(".reject-qty")?.value) || 0;
        const unitCost =
          parseFloat(row.querySelector(".reject-cost")?.value) || 0;
        const totalCost =
          parseFloat(row.querySelector(".reject-total")?.value) || 0;

        if (materialId > 0 && quantity > 0) {
          rejects.push({
            id: materialId,
            name: materialName,
            quantity,
            unit_cost: unitCost,
            total_cost: totalCost,
          });
        }
      });

    const items = [];
    document
      .querySelectorAll("#qaItemsContainer .qa-dynamic-row")
      .forEach((row) => {
        const name = row.querySelector(".item-name")?.value || "";
        const quantity = parseFloat(row.querySelector(".item-qty")?.value) || 0;
        const unitPrice =
          parseFloat(row.querySelector(".item-price")?.value) || 0;
        const totalAmount =
          parseFloat(row.querySelector(".item-total")?.value) || 0;

        if (name && quantity > 0) {
          items.push({
            name,
            quantity,
            unit_price: unitPrice,
            total_amount: totalAmount,
          });
        }
      });

    const itemName = document.getElementById("qaAuditItemName")?.value;
    if (itemName && !items.some((i) => i.name === itemName)) {
      items.unshift({
        name: itemName,
        quantity: 1,
        unit_price: 0,
        total_amount: 0,
      });
    }

    if (materials.length === 0 && items.length === 0) {
      this.showNotification(
        "Please add at least one material or item",
        "error",
      );
      return;
    }

    const payload = {
      items: items,
      materials: materials,
      rejects: rejects,
      created_by: document.getElementById("qaCreatedBy")?.value || "",
      audited_by: document.getElementById("qaAuditedBy")?.value || "",
      acknowledged_by: document.getElementById("qaAcknowledgedBy")?.value || "",
      auto_compute: true,
    };

    this.showAuditLoading(true);

    try {
      const response = await fetch(this.api.createAudit, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const result = await response.json();

      if (result.success) {
        this.closeAuditModal();
        this.showNotification(
          "Audit created successfully! Inventory has been updated.",
          "success",
        );
        if (typeof matReloadMaterials === "function") matReloadMaterials(true);
        if (typeof matReloadLogs === "function") matReloadLogs(true);
      } else {
        this.showNotification(
          result.message || "Failed to create audit",
          "error",
        );
      }
    } catch (error) {
      console.error("Error creating audit:", error);
      this.showNotification("Error creating audit: " + error.message, "error");
    } finally {
      this.showAuditLoading(false);
    }
  }

  overrideAuditButton() {
    window.matOpenAuditModal = () => {
      this.openQuotationSelector();
    };
  }

  openQuotationSelector() {
    const modal = document.getElementById("qaQuotationModal");
    if (modal) {
      this.quotationPage = 1;
      this.quotationSearch = "";
      const searchInput = document.getElementById("qaSearchInput");
      if (searchInput) searchInput.value = "";
      this.loadDeliveredQuotations();
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }
  }

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

// Helper function for escapeHtml
function escapeHtml(str) {
  if (!str) return "";
  return String(str).replace(/[&<>]/g, function (m) {
    if (m === "&") return "&amp;";
    if (m === "<") return "&lt;";
    if (m === ">") return "&gt;";
    return m;
  });
}

// Initialize
let quotationAuditIntegration;
document.addEventListener("DOMContentLoaded", () => {
  quotationAuditIntegration = new QuotationAuditIntegration();
});
