// overhead_audit_helper.js
const OverheadHelper = {
  attachListeners(container, onUpdateCallback) {
    const inputs = container.querySelectorAll(
      ".overhead-input, #production_hours",
    );
    inputs.forEach((input) => {
      input.removeEventListener("input", this._handler);
      const handler = () => {
        this.updateTotals(container);
        if (typeof onUpdateCallback === "function") onUpdateCallback();
      };
      input.addEventListener("input", handler);
      input._overheadHandler = handler;
    });
  },

  updateTotals(container) {
    const fields = [
      "overhead_shop_rent",
      "overhead_fixed_salaries",
      "overhead_shop_utilities",
      "overhead_subscriptions",
      "overhead_machine_depreciation",
      "overhead_maintenance_repair",
      "overhead_marketing",
      "overhead_electricity",
    ];
    let total = 0;
    fields.forEach((field) => {
      const el = container.querySelector(`#${field}`);
      if (el) total += parseFloat(el.value) || 0;
    });
    const totalEl = container.querySelector("#total_overhead_display");
    if (totalEl) totalEl.textContent = `₱${total.toFixed(2)}`;

    const hours =
      parseFloat(container.querySelector("#production_hours")?.value) || 0;
    const perHour = hours > 0 ? total / hours : 0;
    const perHourEl = container.querySelector("#overhead_per_hour_display");
    if (perHourEl)
      perHourEl.innerHTML = `<strong>₱${perHour.toFixed(4)}</strong> per hour`;

    container._overheadTotal = total;
    container._overheadPerHour = perHour;
    container._productionHours = hours;
  },

  getOverheadData(container) {
    return {
      shop_rent:
        parseFloat(container.querySelector("#overhead_shop_rent")?.value) || 0,
      fixed_salaries:
        parseFloat(
          container.querySelector("#overhead_fixed_salaries")?.value,
        ) || 0,
      shop_utilities:
        parseFloat(
          container.querySelector("#overhead_shop_utilities")?.value,
        ) || 0,
      subscriptions:
        parseFloat(container.querySelector("#overhead_subscriptions")?.value) ||
        0,
      machine_depreciation:
        parseFloat(
          container.querySelector("#overhead_machine_depreciation")?.value,
        ) || 0,
      maintenance_repair:
        parseFloat(
          container.querySelector("#overhead_maintenance_repair")?.value,
        ) || 0,
      marketing:
        parseFloat(container.querySelector("#overhead_marketing")?.value) || 0,
      electricity:
        parseFloat(container.querySelector("#overhead_electricity")?.value) ||
        0,
    };
  },

  getProductionHours(container) {
    return parseFloat(container.querySelector("#production_hours")?.value) || 0;
  },

  getTotalOverhead(container) {
    return container._overheadTotal || 0;
  },
};
