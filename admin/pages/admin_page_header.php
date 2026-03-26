<!-- admin_page_header.php -->
<header class="top-nav">
    <button id="toggle-btn" aria-label="Toggle Sidebar">
        <i class="fa-solid fa-chevron-left toggle-arrow"></i>
    </button>

    <span>Hello, Admin</span>

    <!-- Dark/Light Toggle -->
    <button id="theme-toggle" aria-label="Toggle Theme">
        <i class="fa-solid fa-moon" id="theme-icon"></i>
    </button>
</header>
<script>
    const themeToggle = document.getElementById("theme-toggle");
    const themeIcon = document.getElementById("theme-icon");

    // Load saved theme
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
        themeIcon.classList.replace("fa-moon", "fa-sun");
    }

    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");

        // Change icon
        if (document.body.classList.contains("dark-mode")) {
            themeIcon.classList.replace("fa-moon", "fa-sun");
            localStorage.setItem("theme", "dark");
        } else {
            themeIcon.classList.replace("fa-sun", "fa-moon");
            localStorage.setItem("theme", "light");
        }
    });
</script>