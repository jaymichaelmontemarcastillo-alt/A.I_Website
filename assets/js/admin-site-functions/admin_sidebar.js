document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggle-btn");
  const wrapper = document.querySelector(".admin-wrapper");
  const sidebar = document.getElementById("sidebar");
  const navItems = document.querySelectorAll(".nav-item");

  toggleBtn.addEventListener("click", () => {
    if (window.innerWidth > 768) {
      // Desktop: Collapse / Expand
      wrapper.classList.toggle("collapsed");
    } else {
      // Mobile: Slide in/out
      sidebar.classList.toggle("active");
    }
  });

  // Close sidebar on mobile when clicking a nav item
  navItems.forEach((item) => {
    item.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove("active");
      }
    });
  });

  // Optional: Close mobile sidebar when clicking outside
  document.addEventListener("click", (e) => {
    if (
      window.innerWidth <= 768 &&
      !sidebar.contains(e.target) &&
      !toggleBtn.contains(e.target)
    ) {
      sidebar.classList.remove("active");
    }
  });
});
