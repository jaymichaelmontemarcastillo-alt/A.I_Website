/**
 * SIDEBAR TOGGLE FUNCTIONALITY
 *
 * PROBLEM SOLVED:
 * - Prevented flickering on page load by applying collapsed state via CSS attribute
 * - Disabled transitions during load, enabled them after JS runs
 * - Separated mobile (slide) from desktop (collapse) behavior
 */

document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle-btn");
  const wrapper = document.querySelector(".admin-wrapper");
  const sidebar = document.getElementById("sidebar");
  const html = document.documentElement;

  const isMobile = () => window.innerWidth <= 768;

  /**
   * STEP 1: Apply initial collapsed state from localStorage
   * The data attribute was already set in header.php if needed
   * Now we sync the .collapsed class and data attribute
   */
  const isCollapsed = localStorage.getItem("sidebar-collapsed") === "true";

  if (isCollapsed && !isMobile()) {
    // Apply both attribute (for CSS) and class (for JS) for consistency
    html.setAttribute("data-sidebar-collapsed", "true");
    wrapper.classList.add("collapsed");
  } else {
    // Ensure attributes are removed if not collapsed
    html.removeAttribute("data-sidebar-collapsed");
    wrapper.classList.remove("collapsed");
  }

  /**
   * STEP 2: Enable transitions now that page has loaded
   * This allows smooth animations on user interactions
   */
  setTimeout(() => {
    html.setAttribute("data-transitions-enabled", "true");
  }, 50);

  /**
   * STEP 3: Handle toggle button clicks
   * Desktop: collapse/expand sidebar
   * Mobile: slide sidebar in/out
   */
  toggleBtn.addEventListener("click", () => {
    if (isMobile()) {
      // Mobile behavior: slide sidebar in/out
      sidebar.classList.toggle("active");
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
  document.addEventListener("click", (e) => {
    if (isMobile() && sidebar.classList.contains("active")) {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove("active");
      }
    }
  });

  /**
   * STEP 5: Handle window resize
   * Remove mobile sidebar slide when resizing to desktop
   */
  window.addEventListener("resize", () => {
    if (!isMobile()) {
      sidebar.classList.remove("active");
    }
  });
});
