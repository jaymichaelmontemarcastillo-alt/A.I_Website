/**
 * SIDEBAR TOGGLE FUNCTIONALITY
 *
 * IMPROVED:
 * - Toggle button always visible in sidebar
 * - Better icon rotation
 * - Smooth transitions
 * - Mobile overlay support
 */

document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle-btn");
  const mobileNavToggle = document.getElementById("mobileNavToggle");
  const mobileSidebarClose = document.getElementById("mobileSidebarClose");
  const overlay = document.getElementById("sidebarOverlay") || document.querySelector(".sidebar-overlay");
  const wrapper = document.querySelector(".admin-wrapper");
  const sidebar = document.getElementById("sidebar");
  const html = document.documentElement;

  const isMobile = () => window.innerWidth <= 768;

  const openMobileSidebar = () => {
    sidebar.classList.add("active", "mobile-open");
    if (overlay) overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  };

  const closeMobileSidebar = () => {
    sidebar.classList.remove("active", "mobile-open");
    if (overlay) overlay.classList.remove("active");
    document.body.style.overflow = "";
  };

  /**
   * STEP 1: Apply initial collapsed state from localStorage
   */
  const isCollapsed = localStorage.getItem("sidebar-collapsed") === "true";

  if (isCollapsed && !isMobile()) {
    html.setAttribute("data-sidebar-collapsed", "true");
    if (wrapper) wrapper.classList.add("collapsed");
  } else {
    html.removeAttribute("data-sidebar-collapsed");
    if (wrapper) wrapper.classList.remove("collapsed");
  }

  /**
   * STEP 2: Enable transitions now that page has loaded
   */
  setTimeout(() => {
    html.setAttribute("data-transitions-enabled", "true");
  }, 50);

  /**
   * STEP 3: Handle toggle buttons
   */
  if (mobileNavToggle) {
    mobileNavToggle.addEventListener("click", function (e) {
      e.stopPropagation();
      openMobileSidebar();
    });
  }

  if (mobileSidebarClose) {
    mobileSidebarClose.addEventListener("click", function (e) {
      e.stopPropagation();
      closeMobileSidebar();
    });
  }

  if (toggleBtn) {
    toggleBtn.addEventListener("click", function (e) {
      e.stopPropagation();

      if (isMobile()) {
        if (sidebar.classList.contains("active") || sidebar.classList.contains("mobile-open")) {
          closeMobileSidebar();
        } else {
          openMobileSidebar();
        }
      } else {
        if (!wrapper) return;
        wrapper.classList.toggle("collapsed");
        const nowCollapsed = wrapper.classList.contains("collapsed");

        if (nowCollapsed) {
          html.setAttribute("data-sidebar-collapsed", "true");
        } else {
          html.removeAttribute("data-sidebar-collapsed");
        }

        localStorage.setItem("sidebar-collapsed", nowCollapsed);
      }
    });
  }

  if (overlay) {
    overlay.addEventListener("click", closeMobileSidebar);
  }

  // Close sidebar on nav item click (mobile)
  const navItems = sidebar ? sidebar.querySelectorAll(".nav-item") : [];
  navItems.forEach((item) => {
    item.addEventListener("click", () => {
      if (isMobile()) {
        closeMobileSidebar();
      }
    });
  });

  /**
   * STEP 4: Close sidebar when clicking outside (mobile only)
   */
  document.addEventListener("click", function (e) {
    if (isMobile() && (sidebar.classList.contains("active") || sidebar.classList.contains("mobile-open"))) {
      if (!sidebar.contains(e.target) && (!mobileNavToggle || !mobileNavToggle.contains(e.target)) && (!toggleBtn || !toggleBtn.contains(e.target))) {
        closeMobileSidebar();
      }
    }
  });

  /**
   * STEP 5: Handle window resize
   */
  window.addEventListener("resize", function () {
    if (!isMobile()) {
      closeMobileSidebar();
    }
  });
});
