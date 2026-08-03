/**
 * admin_bundles_merchandise.js
 * Full CRUD for Bundles & Merchandise with image upload
 */

document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // API PATH
  // ==========================================
  const API = "../../api/admin_site/bundles_merchandise";

  // ==========================================
  // DOM REFS
  // ==========================================
  const $ = (sel) => document.querySelector(sel);
  const $$ = (sel) => document.querySelectorAll(sel);

  const gridContainer = $("#gridContainer");
  const paginationWrap = $("#paginationWrap");
  const totalItems = $("#totalItems");
  const bundleCount = $("#bundleCount");
  const merchCount = $("#merchCount");
  const searchField = $("#searchField");
  const statusFilter = $("#statusFilter");
  const sortFilter = $("#sortFilter");
  const addBtn = $("#addBtn");
  const refreshBtn = $("#refreshBtn");

  // Tabs
  const tabBtns = $$(".tab-btn");
  let currentType = "bundle";

  // Item Modal
  const itemModal = $("#itemModal");
  const modalTitle = $("#modalTitle");
  const itemForm = $("#itemForm");
  const editId = $("#editId");
  const itemType = $("#itemType");
  const itemName = $("#itemName");
  const itemDescription = $("#itemDescription");
  const itemSortOrder = $("#itemSortOrder");
  const itemImage = $("#itemImage");
  const existingImage = $("#existingImage");
  const imagePreview = $("#imagePreview");
  const imageUploadBtn = $("#imageUploadBtn");
  const imageRemoveBtn = $("#imageRemoveBtn");
  const submitBtn = $("#submitBtn");
  const modalCloseBtn = $("#modalCloseBtn");
  const modalCancelBtn = $("#modalCancelBtn");

  // Delete Modal
  const deleteModal = $("#deleteModal");
  const deleteMsg = $("#deleteMsg");
  const deleteConfirmBtn = $("#deleteConfirmBtn");
  const deleteCancelBtn = $("#deleteCancelBtn");
  const deleteCloseBtn = $("#deleteCloseBtn");

  // ==========================================
  // STATE
  // ==========================================
  let currentPage = 1;
  const LIMIT = 12;
  let deleteId = null;
  let isLoading = false;
  let currentImageFile = null;
  let imagePreviewUrl = null;

  // ==========================================
  // UTILITY
  // ==========================================
  function escape(text) {
    if (!text) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }

  function toast(message, type = "info") {
    const existing = document.querySelector(".toast");
    if (existing) existing.remove();

    const el = document.createElement("div");
    el.className = `toast toast-${type}`;
    const icons = {
      success: "fa-check-circle",
      error: "fa-circle-exclamation",
      info: "fa-info-circle",
    };
    el.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}"></i><span>${message}</span>`;
    document.body.appendChild(el);

    requestAnimationFrame(() => {
      el.classList.add("show");
    });

    setTimeout(() => {
      el.classList.remove("show");
      setTimeout(() => el.remove(), 400);
    }, 4000);

    el.addEventListener("click", () => {
      el.classList.remove("show");
      setTimeout(() => el.remove(), 400);
    });
  }

  function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-US", {
      month: "short",
      day: "numeric",
      year: "numeric",
    });
  }

  // ==========================================
  // MODAL HELPERS
  // ==========================================
  function openModal(modal) {
    modal.classList.add("show");
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }

  function closeModal(modal) {
    modal.classList.remove("show");
    modal.style.display = "none";
    document.body.style.overflow = "";
  }

  // ==========================================
  // FETCH ITEMS
  // ==========================================
  function fetchItems(page = 1) {
    if (isLoading) return;
    isLoading = true;

    const search = searchField ? searchField.value.trim() : "";
    const sort = sortFilter ? sortFilter.value : "sort_order";

    let url = `${API}/get_items.php?type=${currentType}&page=${page}&limit=${LIMIT}&sort=${encodeURIComponent(sort)}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;

    gridContainer.innerHTML = `
            <div class="grid-loading">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading items...
            </div>
        `;

    fetch(url)
      .then((res) => {
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        return res.json();
      })
      .then((data) => {
        isLoading = false;
        if (data.success) {
          renderGrid(data.data);
          renderPagination(data.pagination);
          updateCounts(data.pagination);
          currentPage = page;
        } else {
          gridContainer.innerHTML = `
                        <div class="grid-error" style="grid-column:1/-1;text-align:center;padding:40px;color:var(--action-delete);">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            ${data.message || "Failed to load items"}
                        </div>
                    `;
        }
      })
      .catch((err) => {
        isLoading = false;
        gridContainer.innerHTML = `
                    <div class="grid-error" style="grid-column:1/-1;text-align:center;padding:40px;color:var(--action-delete);">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        Network error: ${err.message}
                    </div>
                `;
        toast("Network error: " + err.message, "error");
      });
  }

  // ==========================================
  // RENDER GRID
  // ==========================================
  function renderGrid(items) {
    if (!items || items.length === 0) {
      gridContainer.innerHTML = `
                <div class="grid-empty">
                    <i class="fa-regular fa-image"></i>
                    <h3>No items found</h3>
                    <p>Click "Add Item" to create a new ${currentType}.</p>
                </div>
            `;
      return;
    }

    let html = "";
    items.forEach((item) => {
      const typeLabel = item.type === "bundle" ? "Bundle" : "Merchandise";
      const typeClass = item.type === "bundle" ? "bundle" : "merchandise";
      const imageHtml = item.image_path
        ? `<img src="${item.image_path}" alt="${escape(item.name)}">`
        : `<div class="no-image"><i class="fa-regular fa-image"></i><span>No image</span></div>`;

      html += `
                <div class="grid-item" data-id="${item.id}">
                    <div class="item-image">
                        ${imageHtml}
                    </div>
                    <div class="item-body">
                        <span class="item-type-badge ${typeClass}">${typeLabel}</span>
                        <h3 class="item-name">${escape(item.name)}</h3>
                        ${item.description ? `<p class="item-desc">${escape(item.description)}</p>` : ""}
                    </div>
                    <div class="item-footer">
                        <span class="item-order">Order: ${item.sort_order || 0}</span>
                        <div class="item-actions">
                            <button class="btn-action edit" data-id="${item.id}" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-action delete" data-id="${item.id}" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
    });

    gridContainer.innerHTML = html;

    // Event listeners for edit/delete buttons
    $$(".grid-item .btn-action.edit").forEach((btn) => {
      btn.addEventListener("click", () =>
        openEditModal(parseInt(btn.dataset.id)),
      );
    });
    $$(".grid-item .btn-action.delete").forEach((btn) => {
      btn.addEventListener("click", () =>
        openDeleteModal(parseInt(btn.dataset.id)),
      );
    });
  }

  // ==========================================
  // PAGINATION
  // ==========================================
  function renderPagination(pagination) {
    if (!pagination || pagination.pages <= 1) {
      paginationWrap.innerHTML = "";
      return;
    }

    const current = pagination.page;
    const total = pagination.pages;

    let html = "";
    html += `<button class="btn-page" data-page="${current - 1}" ${current <= 1 ? "disabled" : ""}>
            <i class="fa-solid fa-chevron-left"></i>
        </button>`;

    let start = Math.max(1, current - 2);
    let end = Math.min(total, current + 2);

    if (start > 1) {
      html += `<button class="btn-page" data-page="1">1</button>`;
      if (start > 2) html += `<span class="pg-dots">…</span>`;
    }

    for (let i = start; i <= end; i++) {
      html += `<button class="btn-page ${i === current ? "active" : ""}" data-page="${i}">${i}</button>`;
    }

    if (end < total) {
      if (end < total - 1) html += `<span class="pg-dots">…</span>`;
      html += `<button class="btn-page" data-page="${total}">${total}</button>`;
    }

    html += `<button class="btn-page" data-page="${current + 1}" ${current >= total ? "disabled" : ""}>
            <i class="fa-solid fa-chevron-right"></i>
        </button>`;

    paginationWrap.innerHTML = html;

    $$(".btn-page").forEach((btn) => {
      btn.addEventListener("click", function () {
        if (this.disabled) return;
        const page = parseInt(this.dataset.page);
        if (page > 0 && page <= total) {
          fetchItems(page);
          window.scrollTo({ top: 0, behavior: "smooth" });
        }
      });
    });
  }

  function updateCounts(pagination) {
    if (totalItems) {
      totalItems.textContent = pagination
        ? `${pagination.total} item${pagination.total !== 1 ? "s" : ""}`
        : "0 items";
    }
    // Counts will be updated via fetchCounts
    fetchCounts();
  }

  function fetchCounts() {
    fetch(`${API}/get_counts.php`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          if (bundleCount) bundleCount.textContent = data.bundles || 0;
          if (merchCount) merchCount.textContent = data.merchandise || 0;
        }
      })
      .catch(() => {});
  }

  // ==========================================
  // TAB HANDLING
  // ==========================================
  tabBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      tabBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      currentType = this.dataset.type;
      currentPage = 1;
      fetchItems(1);
    });
  });

  // ==========================================
  // MODAL: ADD ITEM
  // ==========================================
  // ==========================================
  // MODAL: ADD ITEM
  // ==========================================
  function openAddModal() {
    const typeLabel = currentType === "bundle" ? "Bundle" : "Merchandise";
    modalTitle.innerHTML = `<i class="fa-solid fa-box"></i> Add ${typeLabel}`;
    submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Save Item`;
    itemForm.reset();
    editId.value = "";
    itemType.value = currentType;
    existingImage.value = "";
    currentImageFile = null;
    imagePreviewUrl = null;

    // Clear image preview
    const preview = imagePreview;
    preview.innerHTML = `
        <i class="fa-regular fa-image"></i>
        <span>No image selected</span>
    `;
    imageRemoveBtn.style.display = "none";

    // Reset file input
    itemImage.value = "";

    // Reset form validation
    itemForm.querySelectorAll(".form-error").forEach((el) => el.remove());
    itemForm
      .querySelectorAll(".is-invalid")
      .forEach((el) => el.classList.remove("is-invalid"));

    openModal(itemModal);
  }

  // ==========================================
  // MODAL: EDIT ITEM
  // ==========================================
  function openEditModal(id) {
    const typeLabel = currentType === "bundle" ? "Bundle" : "Merchandise";
    modalTitle.innerHTML = `<i class="fa-solid fa-pen"></i> Edit ${typeLabel}`;
    submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Update Item`;

    // Show loading state in modal
    const preview = imagePreview;
    preview.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        <span>Loading...</span>
    `;

    fetch(`${API}/get_item.php?id=${id}`)
      .then((res) => {
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}`);
        }
        return res.json();
      })
      .then((data) => {
        if (data.success) {
          const item = data.data;
          editId.value = item.id;
          itemType.value = item.type;
          itemName.value = item.name || "";
          itemDescription.value = item.description || "";
          itemSortOrder.value = item.sort_order || 0;
          existingImage.value = item.image_path || "";

          // Show existing image
          if (item.image_path) {
            preview.innerHTML = `<img src="${item.image_path}" alt="${escape(item.name)}">`;
            imageRemoveBtn.style.display = "inline-flex";
          } else {
            preview.innerHTML = `
                        <i class="fa-regular fa-image"></i>
                        <span>No image selected</span>
                    `;
            imageRemoveBtn.style.display = "none";
          }

          currentImageFile = null;
          imagePreviewUrl = null;
          openModal(itemModal);
        } else {
          toast("Error loading item: " + data.message, "error");
        }
      })
      .catch((err) => {
        console.error("❌ Load item error:", err);
        toast("Network error loading item: " + err.message, "error");
        // Reset preview on error
        preview.innerHTML = `
                <i class="fa-regular fa-image"></i>
                <span>No image selected</span>
            `;
      });
  }
  // ==========================================
  // CLOSE ITEM MODAL
  // ==========================================
  function closeItemModal() {
    closeModal(itemModal);
    itemForm.reset();
    editId.value = "";
    currentImageFile = null;
    imagePreviewUrl = null;
  }

  // ==========================================
  // IMAGE HANDLING
  // ==========================================
  imageUploadBtn.addEventListener("click", () => {
    itemImage.click();
  });

  itemImage.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      toast("Image size must be less than 5MB", "error");
      this.value = "";
      return;
    }

    // Validate file type
    const validTypes = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    if (!validTypes.includes(file.type)) {
      toast("Please upload JPG, PNG, WEBP, or GIF images", "error");
      this.value = "";
      return;
    }

    currentImageFile = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreviewUrl = e.target.result;
      imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      imageRemoveBtn.style.display = "inline-flex";
    };
    reader.readAsDataURL(file);
  });

  imageRemoveBtn.addEventListener("click", function () {
    currentImageFile = null;
    imagePreviewUrl = null;
    itemImage.value = "";
    existingImage.value = "";
    imagePreview.innerHTML = `
            <i class="fa-regular fa-image placeholder-icon"></i>
            <span class="placeholder-text">No image selected</span>
        `;
    this.style.display = "none";
  });

  // ==========================================
  // SAVE ITEM
  // ==========================================
  function saveItem(e) {
    e.preventDefault();

    const name = itemName.value.trim();
    if (!name) {
      toast("Item name is required", "error");
      itemName.focus();
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;

    const formData = new FormData();
    if (editId.value) formData.append("id", editId.value);
    formData.append("type", itemType.value);
    formData.append("name", name);
    formData.append("description", itemDescription.value.trim());
    formData.append("sort_order", itemSortOrder.value || 0);

    if (currentImageFile) {
      formData.append("image", currentImageFile);
    }
    if (existingImage.value) {
      formData.append("existing_image", existingImage.value);
    }

    const endpoint = editId.value
      ? `${API}/update_item.php`
      : `${API}/add_item.php`;

    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          toast(data.message || "Item saved successfully", "success");
          closeItemModal();
          fetchItems(currentPage);
          fetchCounts();
        } else {
          toast("Error: " + data.message, "error");
        }
      })
      .catch((err) => {
        console.error("❌ Save error:", err);
        toast("Network error saving item: " + err.message, "error");
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> ${editId.value ? "Update" : "Save"} Item`;
      });
  }

  // ==========================================
  // DELETE
  // ==========================================
  function openDeleteModal(id) {
    deleteId = id;
    const item = document.querySelector(`.grid-item[data-id="${id}"]`);
    const name = item
      ? item.querySelector(".item-name").textContent.trim()
      : "this item";
    deleteMsg.textContent = `Are you sure you want to delete "${name}"?`;
    openModal(deleteModal);
  }

  function closeDeleteModal() {
    closeModal(deleteModal);
    deleteId = null;
  }

  function confirmDelete() {
    if (!deleteId) return;

    deleteConfirmBtn.disabled = true;
    deleteConfirmBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Deleting...`;

    fetch(`${API}/delete_item.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: deleteId }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          toast("Item deleted successfully", "success");
          closeDeleteModal();
          fetchItems(currentPage);
          fetchCounts();
        } else {
          toast("Error: " + data.message, "error");
          closeDeleteModal();
        }
      })
      .catch((err) => {
        console.error("❌ Delete error:", err);
        toast("Network error deleting item: " + err.message, "error");
        closeDeleteModal();
      })
      .finally(() => {
        deleteConfirmBtn.disabled = false;
        deleteConfirmBtn.innerHTML = `<i class="fa-solid fa-trash"></i> Delete`;
      });
  }

  // ==========================================
  // EVENT LISTENERS
  // ==========================================

  // Search with debounce
  let searchTimeout;
  searchField.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchItems(1), 400);
  });

  sortFilter.addEventListener("change", () => fetchItems(1));

  addBtn.addEventListener("click", openAddModal);

  refreshBtn.addEventListener("click", () => {
    fetchItems(currentPage);
    fetchCounts();
    toast("Refreshed", "info");
  });

  // Modal close buttons
  modalCloseBtn.addEventListener("click", closeItemModal);
  modalCancelBtn.addEventListener("click", closeItemModal);
  itemModal.addEventListener("click", function (e) {
    if (e.target === this) closeItemModal();
  });

  deleteCloseBtn.addEventListener("click", closeDeleteModal);
  deleteCancelBtn.addEventListener("click", closeDeleteModal);
  deleteModal.addEventListener("click", function (e) {
    if (e.target === this) closeDeleteModal();
  });
  deleteConfirmBtn.addEventListener("click", confirmDelete);

  itemForm.addEventListener("submit", saveItem);

  // ESC key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      if (deleteModal.classList.contains("show")) closeDeleteModal();
      if (itemModal.classList.contains("show")) closeItemModal();
    }
  });

  // ==========================================
  // INIT
  // ==========================================
  console.log("🚀 Bundles & Merchandise initialized");
  fetchCounts();
  fetchItems(1);
});
