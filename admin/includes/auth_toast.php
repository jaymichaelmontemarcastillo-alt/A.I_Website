<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['message'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById("authToast");
            const message = document.getElementById("toastMessage");

            if (!toast) return;

            message.textContent = "<?php echo $_SESSION['message']; ?>";

            // Show toast (slide down)
            toast.style.top = "20px";

            // Hide toast after 5 seconds (5000ms)
            setTimeout(() => {
                toast.style.top = "-80px";
            }, 5000);
        });
    </script>

    <?php
    unset($_SESSION['message']);
    unset($_SESSION['status']);
    ?>
<?php endif; ?>