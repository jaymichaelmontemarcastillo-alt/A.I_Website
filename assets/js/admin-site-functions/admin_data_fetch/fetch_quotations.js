/* assets/js/admin-site-functions/admin_data_fetch/fetch_quotations.js
 *
 * Changes from previous version:
 *  1. Description field is now a rich contenteditable editor with toolbar
 *     (bold, bullets, indent/tab, new line) — preserves formatting in PDF.
 *  2. deleteQuotation() now shows a custom confirmation modal instead of
 *     the native browser confirm().
 *  3. Rich text is serialised to HTML stored in the description column,
 *     and generate_quotation_pdf.php renders it directly via nl2br/innerHTML.
 */

// ─────────────────────────────────────────────────────────────────────────────
// Inject delete-confirm modal + rich-editor styles once
// ─────────────────────────────────────────────────────────────────────────────
(function injectStyles() {
  if (document.getElementById("qm-extra-styles")) return;
  const style = document.createElement("style");
  style.id = "qm-extra-styles";
  style.textContent = `
    /* ── Delete confirmation modal ───────────────────────────── */
    .qm-confirm-overlay {
      position: fixed; inset: 0; z-index: 100000;
      background: rgba(0,0,0,.45);
      display: flex; align-items: center; justify-content: center;
      animation: qmFadeIn .18s ease;
    }
    .qm-confirm-box {
      background: #fff; border-radius: 12px;
      padding: 32px 28px 24px;
      width: 380px; max-width: 92vw;
      box-shadow: 0 20px 60px rgba(0,0,0,.25);
      text-align: center;
    }
    .qm-confirm-icon {
      width: 52px; height: 52px; border-radius: 50%;
      background: #fff0f0; display: flex; align-items: center;
      justify-content: center; margin: 0 auto 16px;
      font-size: 22px; color: #e53935;
    }
    .qm-confirm-title {
      font-size: 17px; font-weight: 700; color: #111; margin-bottom: 8px;
    }
    .qm-confirm-msg {
      font-size: 13.5px; color: #555; line-height: 1.55; margin-bottom: 24px;
    }
    .qm-confirm-msg strong { color: #111; }
    .qm-confirm-actions {
      display: flex; gap: 10px; justify-content: center;
    }
    .qm-confirm-cancel {
      flex: 1; padding: 10px 0; border-radius: 7px; border: 1.5px solid #ddd;
      background: #fff; font-size: 14px; font-weight: 600; color: #444;
      cursor: pointer; transition: background .15s;
    }
    .qm-confirm-cancel:hover { background: #f5f5f5; }
    .qm-confirm-delete {
      flex: 1; padding: 10px 0; border-radius: 7px; border: none;
      background: #e53935; font-size: 14px; font-weight: 600; color: #fff;
      cursor: pointer; transition: background .15s;
    }
    .qm-confirm-delete:hover { background: #c62828; }

    /* ── Rich text editor toolbar ────────────────────────────── */
    .qe-editor-toolbar {
      display: flex; gap: 4px; flex-wrap: wrap;
      padding: 6px 8px; background: #f8fafc;
      border: 1px solid #e2e8f0; border-bottom: none;
      border-radius: 6px 6px 0 0;
    }
    .qe-toolbar-btn {
      padding: 4px 9px; border-radius: 4px; border: 1px solid #e2e8f0;
      background: #fff; font-size: 12px; color: #374151;
      cursor: pointer; font-family: inherit; line-height: 1.5;
      transition: background .12s, border-color .12s;
      white-space: nowrap;
    }
    .qe-toolbar-btn:hover { background: #e8f0fe; border-color: #93c5fd; }
    .qe-toolbar-btn.active { background: #1a56db; color: #fff; border-color: #1a56db; }
    .qe-toolbar-sep {
      width: 1px; background: #e2e8f0; margin: 3px 2px; align-self: stretch;
    }
    .qe-editor-content {
      min-height: 120px; max-height: 260px; overflow-y: auto;
      border: 1px solid #e2e8f0; border-radius: 0 0 6px 6px;
      padding: 10px 12px; font-size: 14px; color: #111;
      background: #fff; outline: none; line-height: 1.65;
    }
    .qe-editor-content:focus { border-color: #1a56db; box-shadow: 0 0 0 2px #dbeafe; }
    .qe-editor-content ul { margin: 4px 0 4px 20px; padding: 0; }
    .qe-editor-content li { margin: 2px 0; }
    .qe-editor-content b, .qe-editor-content strong { font-weight: 700; }

    @keyframes qmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes qmSlideIn  { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:none; } }
    @keyframes qmSlideOut { from { opacity:1; transform:none; } to { opacity:0; transform:translateX(30px); } }
  `;
  document.head.appendChild(style);
})();

// ─────────────────────────────────────────────────────────────────────────────
// QuotationManager
// ─────────────────────────────────────────────────────────────────────────────
class QuotationManager {
  constructor(config = {}) {
    this.config = {
      tableBodyId: "quotationsTableBody",
      apiUrl: "../../api/admin_site/get_quotations.php",
      statusApiUrl: "../../api/admin_site/update_quotation_status.php",
      pdfApiUrl: "../../api/admin_site/generate_quotation_pdf.php",
      getSingleApiUrl: "../../api/admin_site/get_single_quotation.php",
      updateApiUrl: "../../api/admin_site/update_quotation.php",
      deleteApiUrl: "../../api/admin_site/delete_quotation.php",
      limit: 10,
      ...config,
    };

    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    this._editingId = null;
    this._rowCounter = 0;
    this._isSaving = false;

    this.init();
  }

  // ── INIT ──────────────────────────────────────────────────────────────────

  init() {
    this._attachEventListeners();
    this.fetchQuotations();
  }

  _attachEventListeners() {
    const searchInput = document.getElementById("quotationSearch");
    if (searchInput) this._addDebounceListener(searchInput);

    const statusFilter = document.getElementById("statusFilter");
    if (statusFilter) {
      statusFilter.addEventListener("change", (e) => {
        this.currentFilter = e.target.value;
        this.currentPage = 1;
        this.fetchQuotations();
      });
    }

    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (prevBtn) prevBtn.addEventListener("click", () => this.prevPage());
    if (nextBtn) nextBtn.addEventListener("click", () => this.nextPage());

    const overlay = document.getElementById("QuotationEditModal");
    if (overlay) {
      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) this.closeEditModal();
      });
    }
  }

  _addDebounceListener(input) {
    let timer;
    input.addEventListener("input", (e) => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        this.currentSearch = e.target.value.trim();
        this.currentPage = 1;
        this.fetchQuotations();
      }, 500);
    });
  }

  // ── FETCH & RENDER TABLE ──────────────────────────────────────────────────

  async fetchQuotations() {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (tbody) {
      tbody.innerHTML = `
        <tr>
          <td colspan="9" class="text-center" style="padding:40px;">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading quotations...
          </td>
        </tr>`;
    }

    try {
      const params = new URLSearchParams({
        page: this.currentPage,
        limit: this.config.limit,
        status: this.currentFilter !== "all" ? this.currentFilter : "",
        search: this.currentSearch,
      });

      const response = await fetch(`${this.config.apiUrl}?${params}`);
      const result = await this._parseJSON(response);

      if (result.success) {
        this._renderTable(result.data);
        this._renderPagination(result.pagination);
      } else {
        this.showNotification(
          result.message || "Failed to load quotations",
          "error",
        );
        if (tbody)
          tbody.innerHTML = `<tr><td colspan="9" class="text-center">Failed to load quotations.</td></tr>`;
      }
    } catch (err) {
      console.error("fetchQuotations error:", err);
      this.showNotification(
        "Error fetching quotations: " + err.message,
        "error",
      );
    }
  }

  _renderTable(quotations) {
    const tbody = document.getElementById(this.config.tableBodyId);
    if (!tbody) return;

    if (!quotations || quotations.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center">No quotations found.</td></tr>`;
      return;
    }

    tbody.innerHTML = quotations
      .map(
        (q) => `
      <tr>
        <td>${this._esc(q.quote_number)}</td>
        <td>${this._esc(q.client_name)}</td>
        <td>${this._esc(q.contact_person || "")}</td>
        <td>${this._esc(q.email || "")}</td>
        <td>${this._esc(q.phone || "")}</td>
        <td>Php ${parseFloat(q.total || 0).toFixed(2)}</td>
        <td>${new Date(q.created_at).toLocaleDateString()}</td>
        <td>
          <select class="status-select" data-id="${q.id}"
            onchange="quotationManager.updateStatus(${q.id}, this.value)">
            <option value="draft"     ${q.status === "draft" ? "selected" : ""}>Draft</option>
            <option value="sent"      ${q.status === "sent" ? "selected" : ""}>Sent</option>
            <option value="accepted"  ${q.status === "accepted" ? "selected" : ""}>Accepted</option>
            <option value="expired"   ${q.status === "expired" ? "selected" : ""}>Expired</option>
            <option value="converted" ${q.status === "converted" ? "selected" : ""}>Converted</option>
          </select>
        </td>
        <td>
          <div class="action-buttons">
            <button class="btn-edit"
              onclick="quotationManager.openEditModal(${q.id})" title="Edit">
              <i class="fa-solid fa-edit"></i>
            </button>
            <button class="btn-pdf"
              onclick="quotationManager.generatePDF(${q.id})" title="Generate PDF">
              <i class="fa-solid fa-file-pdf"></i>
            </button>
            <button class="btn-delete"
              onclick="quotationManager.deleteQuotation(${q.id}, '${this._esc(q.quote_number)}')" title="Delete">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`,
      )
      .join("");
  }

  // ── STATUS UPDATE ─────────────────────────────────────────────────────────

  async updateStatus(id, status) {
    try {
      const response = await fetch(this.config.statusApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, status }),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification(`Status updated to "${status}"`, "success");
      } else {
        this.showNotification(
          result.message || "Failed to update status",
          "error",
        );
      }
    } catch (err) {
      console.error("updateStatus error:", err);
      this.showNotification("Error updating status: " + err.message, "error");
    }
    this.fetchQuotations();
  }

  // ── EDIT MODAL — OPEN / CLOSE ─────────────────────────────────────────────

  async openEditModal(id) {
    this._editingId = id;
    this._rowCounter = 0;

    const overlay = document.getElementById("QuotationEditModal");
    if (!overlay) {
      this.showNotification("Error: Modal element not found", "error");
      return;
    }

    overlay.style.display = "flex";
    document.body.style.overflow = "hidden";

    await new Promise((resolve) => setTimeout(resolve, 50));

    const modal = overlay.querySelector(".qe-modal");
    let loader = modal.querySelector(".qe-loading");
    if (!loader) {
      loader = document.createElement("div");
      loader.className = "qe-loading";
      loader.style.cssText =
        "position:absolute;inset:0;display:flex;align-items:center;justify-content:center;" +
        "background:rgba(255,255,255,0.85);z-index:10;border-radius:inherit;font-size:14px;gap:8px;";
      modal.style.position = "relative";
      modal.appendChild(loader);
    }
    loader.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin"></i> Loading quotation...';
    loader.style.display = "flex";

    try {
      const response = await fetch(`${this.config.getSingleApiUrl}?id=${id}`);
      const result = await this._parseJSON(response);

      if (!result.success) {
        this.showNotification(
          result.message || "Failed to load quotation",
          "error",
        );
        this.closeEditModal();
        return;
      }

      if (!result.data || !result.data.quotation) {
        this.showNotification("Invalid response format from server", "error");
        this.closeEditModal();
        return;
      }

      this._populateEditModal(result.data.quotation, result.data.items || []);
    } catch (err) {
      console.error("openEditModal error:", err);
      this.showNotification("Error loading quotation: " + err.message, "error");
      this.closeEditModal();
    } finally {
      if (loader) loader.style.display = "none";
    }
  }

  closeEditModal() {
    const overlay = document.getElementById("QuotationEditModal");
    if (overlay) overlay.style.display = "none";
    document.body.style.overflow = "";
    this._editingId = null;
    this._rowCounter = 0;
  }

  _populateEditModal(quotation, items) {
    this._clearModalFields();

    const sub = document.getElementById("qeQuoteNumber");
    if (sub) sub.textContent = quotation.quote_number || "";

    this._setVal("qeClientName", quotation.client_name || "");
    this._setVal("qeContactPerson", quotation.contact_person || "");
    this._setVal("qeEmail", quotation.email || "");
    this._setVal("qePhone", quotation.phone || "");
    this._setVal("qeTax", quotation.tax ?? 0);
    this._setVal("qeDiscount", quotation.discount ?? 0);
    this._setVal("qeNotes", quotation.notes || "");

    const tbody = document.getElementById("qeItemsBody");
    if (tbody) tbody.innerHTML = "";

    if (items && items.length > 0) {
      items.forEach((item) => this._appendItemRow(item));
    } else {
      this._appendItemRow();
    }

    this.recalcTotals();
  }

  _clearModalFields() {
    [
      "qeClientName",
      "qeContactPerson",
      "qeEmail",
      "qePhone",
      "qeTax",
      "qeDiscount",
      "qeNotes",
    ].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = "";
    });
  }

  _setVal(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value;
    else console.warn(`Element "${id}" not found`);
  }

  // ── ITEM ROWS ─────────────────────────────────────────────────────────────

  addItemRow() {
    this._appendItemRow();
    this.recalcTotals();
  }

  /**
   * Builds a table row where the description cell is a rich contenteditable
   * editor with its own mini-toolbar (bold, bullet list, indent).
   */
  _appendItemRow(item = {}) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;

    const rid = ++this._rowCounter;
    const qty = item.quantity ?? "";
    const price = item.unit_price ?? "";
    const total = parseFloat(item.total || 0).toFixed(2);

    // Description may be stored as HTML (with <b>, <ul>, etc.)
    // or as plain text — both are safe to set as innerHTML after sanitisation.
    const descHtml = this._sanitiseHtml(item.description || "");

    const tr = document.createElement("tr");
    tr.setAttribute("data-row-id", rid);
    tr.innerHTML = `
      <td>
        <input type="number" class="qe-item-input qe-qty" data-row="${rid}"
          value="${this._esc(String(qty))}" min="0" step="1" placeholder="1"
          oninput="quotationManager._rowCalc(${rid})">
      </td>
      <td class="qe-desc-cell">
        <!-- Toolbar -->
        <div class="qe-editor-toolbar">
          <button type="button" class="qe-toolbar-btn" title="Bold"
            onmousedown="event.preventDefault(); quotationManager._cmd('bold')">
            <b>B</b>
          </button>
          <div class="qe-toolbar-sep"></div>
          <button type="button" class="qe-toolbar-btn" title="Bullet list"
            onmousedown="event.preventDefault(); quotationManager._cmd('insertUnorderedList')">
            &#8226; List
          </button>
          <button type="button" class="qe-toolbar-btn" title="Indent"
            onmousedown="event.preventDefault(); quotationManager._cmd('indent')">
            &#8677; Indent
          </button>
          <button type="button" class="qe-toolbar-btn" title="Outdent"
            onmousedown="event.preventDefault(); quotationManager._cmd('outdent')">
            &#8676; Outdent
          </button>
          <div class="qe-toolbar-sep"></div>
          <button type="button" class="qe-toolbar-btn" title="Clear formatting"
            onmousedown="event.preventDefault(); quotationManager._cmd('removeFormat')">
            &#10005; Clear
          </button>
        </div>
        <!-- Editor -->
        <div class="qe-editor-content qe-desc" data-row="${rid}"
          contenteditable="true"
          spellcheck="true"
          data-placeholder="Item description..."
        >${descHtml}</div>
      </td>
      <td>
        <input type="number" class="qe-item-input qe-price" data-row="${rid}"
          value="${this._esc(String(price))}" min="0" step="0.01" placeholder="0.00"
          oninput="quotationManager._rowCalc(${rid})">
      </td>
      <td>
        <span class="qe-item-total" id="qeRowTotal_${rid}">Php ${total}</span>
      </td>
      <td>
        <button class="qe-btn-remove" onclick="quotationManager._removeRow(${rid})" title="Remove">
          <i class="fa-solid fa-trash"></i>
        </button>
      </td>`;

    tbody.appendChild(tr);
  }

  /** Executes a document.execCommand on the currently focused editor */
  _cmd(command, value = null) {
    document.execCommand(command, false, value);
  }

  _removeRow(rid) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;
    const row = tbody.querySelector(`tr[data-row-id="${rid}"]`);
    if (row) row.remove();
    this.recalcTotals();
  }

  _rowCalc(rid) {
    const tbody = document.getElementById("qeItemsBody");
    if (!tbody) return;

    const tr = tbody.querySelector(`tr[data-row-id="${rid}"]`);
    if (!tr) return;

    const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
    const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;
    const total = qty * price;

    const span = document.getElementById(`qeRowTotal_${rid}`);
    if (span) span.textContent = `Php ${total.toFixed(2)}`;

    this.recalcTotals();
  }

  // ── TOTALS ────────────────────────────────────────────────────────────────

  recalcTotals() {
    let subtotal = 0;

    document.querySelectorAll("#qeItemsBody tr[data-row-id]").forEach((tr) => {
      const rid = tr.getAttribute("data-row-id");
      const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
      const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;
      const row = qty * price;
      subtotal += row;

      const span = document.getElementById(`qeRowTotal_${rid}`);
      if (span) span.textContent = `Php ${row.toFixed(2)}`;
    });

    const taxPct = parseFloat(document.getElementById("qeTax")?.value) || 0;
    const discount =
      parseFloat(document.getElementById("qeDiscount")?.value) || 0;
    const taxAmt = subtotal * (taxPct / 100);
    const grand = Math.max(0, subtotal + taxAmt - discount);

    const subEl = document.getElementById("qeSubtotal");
    const grandEl = document.getElementById("qeGrandTotal");
    if (subEl) subEl.textContent = `Php ${subtotal.toFixed(2)}`;
    if (grandEl) grandEl.textContent = `Php ${grand.toFixed(2)}`;
  }

  // ── COLLECT FORM DATA ─────────────────────────────────────────────────────

  _collectFormData() {
    const items = [];

    document.querySelectorAll("#qeItemsBody tr[data-row-id]").forEach((tr) => {
      const qty = parseFloat(tr.querySelector(".qe-qty")?.value) || 0;
      const price = parseFloat(tr.querySelector(".qe-price")?.value) || 0;

      // Get HTML content from contenteditable editor
      const editor = tr.querySelector(".qe-desc[contenteditable]");
      const desc = editor ? editor.innerHTML.trim() : "";
      const descText = editor
        ? (editor.innerText || editor.textContent || "").trim()
        : "";

      if (descText.length > 0 && (qty > 0 || price > 0)) {
        items.push({
          description: desc, // store as HTML — rendered directly in PDF
          quantity: qty,
          unit_price: price,
        });
      }
    });

    return {
      id: this._editingId,
      client_name: (
        document.getElementById("qeClientName")?.value || ""
      ).trim(),
      contact_person: (
        document.getElementById("qeContactPerson")?.value || ""
      ).trim(),
      email: (document.getElementById("qeEmail")?.value || "").trim(),
      phone: (document.getElementById("qePhone")?.value || "").trim(),
      tax: parseFloat(document.getElementById("qeTax")?.value) || 0,
      discount: parseFloat(document.getElementById("qeDiscount")?.value) || 0,
      notes: (document.getElementById("qeNotes")?.value || "").trim(),
      items,
    };
  }

  // ── VALIDATION & SHARED SAVE ──────────────────────────────────────────────

  _validatePayload(payload) {
    if (!payload.client_name) {
      this.showNotification("Client name is required.", "error");
      return false;
    }
    if (!payload.id) {
      this.showNotification("Error: Quotation ID is missing.", "error");
      return false;
    }
    if (payload.items.length === 0) {
      this.showNotification(
        "At least one item with a description is required.",
        "error",
      );
      return false;
    }
    return true;
  }

  _setButtonsDisabled(disabled) {
    const saveBtn = document.getElementById("qeSaveBtn");
    const savePdfBtn = document.getElementById("qeSavePdfBtn");
    if (saveBtn) saveBtn.disabled = disabled;
    if (savePdfBtn) savePdfBtn.disabled = disabled;
  }

  async _doSave(payload) {
    const response = await fetch(this.config.updateApiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });
    return this._parseJSON(response);
  }

  // ── SAVE ONLY ─────────────────────────────────────────────────────────────

  async saveQuotation() {
    if (this._isSaving) {
      this.showNotification("Save in progress, please wait...", "info");
      return;
    }

    const payload = this._collectFormData();
    if (!this._validatePayload(payload)) return;

    this._isSaving = true;
    this._setButtonsDisabled(true);

    try {
      const result = await this._doSave(payload);
      if (result.success) {
        this.showNotification("Quotation updated successfully!", "success");
        this.closeEditModal();
        this.fetchQuotations();
      } else {
        this.showNotification(
          result.message || "Failed to update quotation",
          "error",
        );
      }
    } catch (err) {
      console.error("saveQuotation error:", err);
      this.showNotification("Error saving quotation: " + err.message, "error");
    } finally {
      this._isSaving = false;
      this._setButtonsDisabled(false);
    }
  }

  // ── SAVE + GENERATE PDF ───────────────────────────────────────────────────

  async saveAndGeneratePDF() {
    if (this._isSaving) {
      this.showNotification("Save in progress, please wait...", "info");
      return;
    }

    const payload = this._collectFormData();
    if (!this._validatePayload(payload)) return;

    const quotationId = this._editingId;
    this._isSaving = true;
    this._setButtonsDisabled(true);

    try {
      const result = await this._doSave(payload);
      if (result.success) {
        this.showNotification("Saved! Generating PDF...", "success");
        this.closeEditModal();
        this.fetchQuotations();
        await this.generatePDF(quotationId);
      } else {
        this.showNotification(
          result.message || "Failed to update quotation",
          "error",
        );
      }
    } catch (err) {
      console.error("saveAndGeneratePDF error:", err);
      this.showNotification("Error: " + err.message, "error");
    } finally {
      this._isSaving = false;
      this._setButtonsDisabled(false);
    }
  }

  // ── PDF GENERATION ────────────────────────────────────────────────────────

  async generatePDF(id) {
    if (!id) {
      this.showNotification("Error: No quotation ID provided.", "error");
      return;
    }
    this.showNotification("Generating PDF, please wait...", "info");

    try {
      const response = await fetch(this.config.pdfApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      const result = await this._parseJSON(response);

      if (result.success) {
        this.showNotification("PDF generated successfully!", "success");
        window.open(result.pdf_url, "_blank");
      } else {
        this.showNotification(
          result.message || "Failed to generate PDF",
          "error",
        );
      }
    } catch (err) {
      console.error("generatePDF error:", err);
      this.showNotification("Error generating PDF: " + err.message, "error");
    }
  }

  // ── DELETE (with confirmation modal) ─────────────────────────────────────

  deleteQuotation(id, quoteNumber = "") {
    // Remove any existing confirm dialog
    document
      .querySelectorAll(".qm-confirm-overlay")
      .forEach((el) => el.remove());

    const overlay = document.createElement("div");
    overlay.className = "qm-confirm-overlay";
    overlay.innerHTML = `
      <div class="qm-confirm-box">
        <div class="qm-confirm-icon">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="qm-confirm-title">Delete Quotation?</div>
        <div class="qm-confirm-msg">
          You are about to permanently delete
          <strong>${this._esc(quoteNumber || String(id))}</strong>.<br>
          This action cannot be undone and will also remove all line items.
        </div>
        <div class="qm-confirm-actions">
          <button class="qm-confirm-cancel" id="qmCancelDelete">Cancel</button>
          <button class="qm-confirm-delete" id="qmConfirmDelete">
            <i class="fa-solid fa-trash"></i> Delete
          </button>
        </div>
      </div>`;

    document.body.appendChild(overlay);

    // Close on backdrop click
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) overlay.remove();
    });

    document.getElementById("qmCancelDelete").addEventListener("click", () => {
      overlay.remove();
    });

    document
      .getElementById("qmConfirmDelete")
      .addEventListener("click", async () => {
        const deleteBtn = document.getElementById("qmConfirmDelete");
        const cancelBtn = document.getElementById("qmCancelDelete");
        deleteBtn.disabled = true;
        cancelBtn.disabled = true;
        deleteBtn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

        try {
          const response = await fetch(this.config.deleteApiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const result = await this._parseJSON(response);

          overlay.remove();

          if (result.success) {
            this.showNotification(
              result.message || "Quotation deleted successfully.",
              "success",
            );
            this.fetchQuotations();
          } else {
            this.showNotification(
              result.message || "Failed to delete quotation",
              "error",
            );
          }
        } catch (err) {
          overlay.remove();
          console.error("deleteQuotation error:", err);
          this.showNotification(
            "Error deleting quotation: " + err.message,
            "error",
          );
        }
      });
  }

  // ── PAGINATION ────────────────────────────────────────────────────────────

  _renderPagination(pagination) {
    const pageInfo = document.getElementById("pageInfo");
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");

    if (pageInfo) {
      pageInfo.textContent = `Page ${pagination.page} of ${pagination.totalPages} (Total: ${pagination.total})`;
    }
    if (prevBtn) prevBtn.disabled = pagination.page <= 1;
    if (nextBtn) nextBtn.disabled = pagination.page >= pagination.totalPages;
  }

  nextPage() {
    this.currentPage++;
    this.fetchQuotations();
    this._scrollToTop();
  }
  prevPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchQuotations();
      this._scrollToTop();
    }
  }
  _scrollToTop() {
    const el = document.querySelector(".content-body");
    if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  // ── PUBLIC ALIASES ────────────────────────────────────────────────────────

  refresh() {
    this.currentPage = 1;
    this.currentFilter = "all";
    this.currentSearch = "";
    const sf = document.getElementById("statusFilter");
    if (sf) sf.value = "all";
    const si = document.getElementById("quotationSearch");
    if (si) si.value = "";
    this.fetchQuotations();
  }

  viewQuotation(id) {
    window.location.href = `quotation-view.php?id=${id}`;
  }
  editQuotation(id) {
    this.openEditModal(id);
  }

  // ── UTILITIES ─────────────────────────────────────────────────────────────

  /**
   * Sanitise HTML from the editor before storing/rendering.
   * Allows only safe formatting tags; strips scripts and event handlers.
   */
  _sanitiseHtml(html) {
    const allowed = new Set([
      "B",
      "STRONG",
      "I",
      "EM",
      "U",
      "UL",
      "OL",
      "LI",
      "BR",
      "P",
      "SPAN",
      "DIV",
    ]);
    const tmp = document.createElement("div");
    tmp.innerHTML = html;

    const clean = (node) => {
      [...node.childNodes].forEach((child) => {
        if (child.nodeType === Node.ELEMENT_NODE) {
          if (!allowed.has(child.tagName)) {
            // Replace disallowed element with its children
            while (child.firstChild)
              child.parentNode.insertBefore(child.firstChild, child);
            child.remove();
          } else {
            // Strip all attributes (removes onclick, style injections, etc.)
            [...child.attributes].forEach((attr) =>
              child.removeAttribute(attr.name),
            );
            clean(child);
          }
        }
      });
    };

    clean(tmp);
    return tmp.innerHTML;
  }

  async _parseJSON(response) {
    const raw = await response.text();
    try {
      return JSON.parse(raw);
    } catch (e) {
      console.error("Non-JSON response:", raw);
      throw new Error(
        `Server returned invalid JSON (HTTP ${response.status}).`,
      );
    }
  }

  _esc(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text ? String(text).replace(/[&<>"']/g, (m) => map[m]) : "";
  }

  showNotification(message, type = "info") {
    document.querySelectorAll(".qm-notification").forEach((n) => n.remove());

    const colors = {
      success: "#4caf50",
      error: "#f44336",
      info: "#2196f3",
      warning: "#ff9800",
    };
    const el = document.createElement("div");
    el.className = "qm-notification";
    el.textContent = message;
    el.style.cssText = `
      position:fixed; top:20px; right:20px; z-index:99999;
      padding:14px 20px; border-radius:6px; font-size:14px;
      color:#fff; background:${colors[type] || colors.info};
      box-shadow:0 4px 12px rgba(0,0,0,.2);
      animation:qmSlideIn .25s ease;
      max-width:360px; word-break:break-word;`;
    document.body.appendChild(el);

    setTimeout(() => {
      el.style.animation = "qmSlideOut .25s ease forwards";
      setTimeout(() => el.remove(), 260);
    }, 3500);
  }
}

// Initialize on DOM ready
let quotationManager;
document.addEventListener("DOMContentLoaded", () => {
  quotationManager = new QuotationManager();
});
