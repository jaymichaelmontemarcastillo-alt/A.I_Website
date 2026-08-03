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
  const wrapper = document.querySelector(".admin-wrapper");
  const sidebar = document.getElementById("sidebar");
  const html = document.documentElement;

  const isMobile = () => window.innerWidth <= 768;

  /**
   * STEP 1: Apply initial collapsed state from localStorage
   */
  const isCollapsed = localStorage.getItem("sidebar-collapsed") === "true";

  if (isCollapsed && !isMobile()) {
    html.setAttribute("data-sidebar-collapsed", "true");
    wrapper.classList.add("collapsed");
  } else {
    html.removeAttribute("data-sidebar-collapsed");
    wrapper.classList.remove("collapsed");
  }

  /**
   * STEP 2: Enable transitions now that page has loaded
   */
  setTimeout(() => {
    html.setAttribute("data-transitions-enabled", "true");
  }, 50);

  /**
   * STEP 3: Handle toggle button clicks
   */
  toggleBtn.addEventListener("click", function (e) {
    e.stopPropagation();

    if (isMobile()) {
      // Mobile behavior: slide sidebar in/out
      sidebar.classList.toggle("active");

      // Toggle overlay
      const overlay = document.querySelector(".sidebar-overlay");
      if (overlay) {
        overlay.classList.toggle("active");
      }
    } else {
      // Desktop behavior: collapse/expand sidebar
      wrapper.classList.toggle("collapsed");
      const nowCollapsed = wrapper.classList.contains("collapsed");

      // Update data attribute for CSS
      if (nowCollapsed) {
        html.setAttribute("data-sidebar-collapsed", "true");
      } else {
        html.removeAttribute("data-sidebar-collapsed");
      }

      // Save state to localStorage
      localStorage.setItem("sidebar-collapsed", nowCollapsed);
    }
  });

  /**
   * STEP 4: Close sidebar when clicking outside (mobile only)
   */
  document.addEventListener("click", function (e) {
    if (isMobile() && sidebar.classList.contains("active")) {
      const overlay = document.querySelector(".sidebar-overlay");
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove("active");
        if (overlay) {
          overlay.classList.remove("active");
        }
      }
    }
  });

  /**
   * STEP 5: Handle window resize
   */
  window.addEventListener("resize", function () {
    if (!isMobile()) {
      sidebar.classList.remove("active");
      const overlay = document.querySelector(".sidebar-overlay");
      if (overlay) {
        overlay.classList.remove("active");
      }
    }
  });
});
