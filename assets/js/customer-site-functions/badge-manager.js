/**
 * badge-manager.js
 * Centralized badge counter manager for cart and wishlist.
 * Works on ALL pages. Always fetches fresh counts from server.
 * Place this file at: assets/js/customer-site-functions/badge-manager.js
 */

(function () {
  "use strict";

  const BadgeManager = {
    cartBadge: null,
    wishlistBadge: null,
    baseDir: "",
    pollInterval: null,

    init(baseDir) {
      this.baseDir = baseDir || "";
      this.cartBadge = document.getElementById("cartBadge");
      this.wishlistBadge = document.getElementById("wishlistBadge");

      // Fetch fresh counts immediately on page load
      this.refreshAll();

      // Listen for custom events (fired by add-to-cart / add-to-wishlist actions)
      // Always re-fetch from server instead of trusting event payload
      document.addEventListener("cartUpdated", () => this.refreshCart());
      document.addEventListener("wishlistUpdated", () =>
        this.refreshWishlist(),
      );

      // Poll every 30s as a fallback (e.g. opened in another tab)
      this.pollInterval = setInterval(() => this.refreshAll(), 30000);

      // Expose globally so any page script can call it
      window.BadgeManager = this;
    },

    refreshAll() {
      this.refreshCart();
      this.refreshWishlist();
    },

    async refreshCart() {
      try {
        const res = await fetch(this.baseDir + "api/get_cart_count.php", {
          cache: "no-store",
        });
        const data = await res.json();
        if (data && typeof data.count !== "undefined") {
          this._setBadge(this.cartBadge, data.count);
        }
      } catch (e) {
        console.warn("[BadgeManager] Cart fetch failed:", e);
      }
    },

    async refreshWishlist() {
      try {
        const res = await fetch(this.baseDir + "api/get_wishlist_count.php", {
          cache: "no-store",
        });
        const data = await res.json();
        if (data && typeof data.count !== "undefined") {
          this._setBadge(this.wishlistBadge, data.count);
        }
      } catch (e) {
        console.warn("[BadgeManager] Wishlist fetch failed:", e);
      }
    },

    _setBadge(el, count) {
      if (!el) return;
      count = parseInt(count, 10) || 0;

      if (count > 0) {
        el.textContent = count > 99 ? "99+" : count;
        el.classList.remove("hidden");
        // Force inline style so no CSS rule can override visibility
        el.style.display = "flex";
      } else {
        el.classList.add("hidden");
        el.style.display = "none";
      }
    },
  };

  // Auto-init as soon as DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      BadgeManager.init(window.__baseDir || "");
    });
  } else {
    BadgeManager.init(window.__baseDir || "");
  }
})();
