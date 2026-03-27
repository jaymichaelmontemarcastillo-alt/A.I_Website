<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Shop Admin Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/admin-site/admin_style.css">

</head>

<body>

    <div class="login-wrapper">

        <!-- LEFT SIDE BRANDING -->
        <div class="login-brand">

            <div class="brand-content">

                <div class="brand-logo-text">
                    <div class="brand-logo-img">
                        <img src="../assets/images/AI_Logo.jpg" alt="Logo">
                    </div>

                    <h2>Finding the perfect gift<br>shouldn't be stressful.</h2>
                </div>

                <p>Let us help you make it effortless and personal.</p>

            </div>

        </div>


        <!-- RIGHT SIDE LOGIN -->
        <div class="login-form-container">
            <div class="login-card">
                <h2>Admin Login</h2>
                <p class="subtitle">Sign in to your admin account</p>

                <form id="loginForm" method="POST" action="admin_authentication/admin_login_validation.php">

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="admin@giftshop.com" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" id="passwordInput" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    <div class="forgot-password">
                        <a href="reset_password.php">Forgot Password?</a>
                    </div>

                    <button type="submit" class="sign-in-btn">
                        <i class="fa-solid fa-lock"></i>
                        Sign In
                    </button>
                    <div class="admin-signup-link">
                        Don't have an account? <a href="admin_register.php">Sign Up</a>
                    </div>
                </form>


            </div>
        </div>

    </div>

</body>
<!-- TOAST ALERT -->
<div id="authToast" class="toast">
    <i class="fa-solid fa-circle-xmark toast-icon"></i>
    <span id="toastMessage"></span>
</div>
<?php include 'includes/auth_toast.php'; ?>
<script>
    // Show/Hide password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePassword.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.classList.remove('fa-eye');
            togglePassword.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            togglePassword.classList.remove('fa-eye-slash');
            togglePassword.classList.add('fa-eye');
        }
    });

    // Loading animation on form submit
    const loginForm = document.getElementById('loginForm');
    const signInBtn = loginForm.querySelector('.sign-in-btn');
    const spinner = document.createElement('span');
    spinner.classList.add('spinner');
    signInBtn.appendChild(spinner);

    loginForm.addEventListener('submit', function(e) {
        // Optional: prevent default if you want AJAX submit
        // e.preventDefault();

        signInBtn.classList.add('loading');
        // Lock icon is hidden via CSS, spinner shows in same place
    });

    // Reset loading state when page loads (to prevent infinite spinner)
    window.addEventListener('load', () => {
        signInBtn.classList.remove('loading');
    });
</script>

</html>