<?php
session_start();

$status = '';
$message = '';

if (isset($_SESSION['signup_success'])) {
    $status = 'success';
    $message = $_SESSION['signup_success'];
    unset($_SESSION['signup_success']);
} elseif (isset($_SESSION['signup_error'])) {
    $status = 'error';
    $message = $_SESSION['signup_error'];
    unset($_SESSION['signup_error']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Shop Admin Signup</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-site/admin_style.css">

    <style>
        /* Password toggle icon */
        .input-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 68%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        /* Loading spinner inside button */
        .sign-up-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .sign-up-btn .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            margin-left: 8px;
            animation: spin 1s linear infinite;
            vertical-align: middle;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-footer {
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #0f3d67;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .top-notification {
            position: fixed;
            top: -100px;
            /* hidden initially */
            left: 50%;
            transform: translateX(-50%);
            min-width: 300px;
            max-width: 90%;
            padding: 14px 20px;
            border-radius: 8px;
            color: #fff;
            font-weight: 500;
            text-align: center;
            z-index: 9999;
            transition: top 0.4s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Success (green) */
        .top-notification.success {
            background: #16a34a;
        }

        /* Error (red) */
        .top-notification.error {
            background: #dc2626;
        }

        /* Show animation */
        .top-notification.show {
            top: 20px;
        }

        .password-warning {
            color: #dc2626;
            /* red */
            font-size: 0.85rem;
            margin-top: 4px;
            min-height: 18px;
            /* prevent layout shift */
        }

        .password-warning.valid {
            color: #16a34a;
            /* green */
        }
    </style>
</head>

<body>

    <div id="topNotification" class="top-notification <?php echo $status; ?>">
        <span><?php echo $message; ?></span>
    </div>
    <div class="login-wrapper">

        <!-- LEFT SIDE BRANDING -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo-text">
                    <div class="brand-logo-img">
                        <img src="../assets/images/AI_Logo.jpg" alt="Logo">
                    </div>
                    <h2>Join our admin team<br>effortlessly.</h2>
                </div>
                <p>Create your account and manage gifts with ease.</p>
            </div>
        </div>

        <!-- RIGHT SIDE SIGNUP -->
        <div class="login-form-container">
            <div class="login-card">
                <h2>Admin Signup</h2>
                <p class="subtitle">Create your admin account</p>

                <form id="signupForm" method="POST" action="admin_authentication/admin_signup_validation.php">

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="admin@giftshop.com" required>
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" id="passwordInput" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                        <div class="password-warning" id="passwordWarning"></div>
                    </div>

                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirmPasswordInput" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye toggle-password" id="toggleConfirmPassword"></i>
                        <div class="password-warning" id="confirmPasswordWarning"></div>
                    </div>
                    <button type="submit" class="sign-up-btn">
                        <i class="fa-solid fa-user-plus"></i>
                        Sign Up
                    </button>

                    <div class="admin-signup-link">
                        Already have an account? <a href="admin_login.php">Sign In</a>
                    </div>

                </form>

                <div id="authModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-icon" id="modalIcon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <p id="modalMessage"></p>
                        <button id="modalClose" class="modal-btn">OK</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        // Show/Hide Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePassword.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });

        // Loading animation on form submit
        const signupForm = document.getElementById('signupForm');
        const signUpBtn = signupForm.querySelector('.sign-up-btn');

        signupForm.addEventListener('submit', function(e) {
            // Optional: prevent default if using AJAX
            // e.preventDefault();

            // Add loading spinner
            signUpBtn.classList.add('loading');
            signUpBtn.innerHTML = `<i class="fa-solid fa-user-plus"></i> Signing Up <span class="spinner"></span>`;
        });
    </script>

</body>
<script src="../assets/js/admin-site-functions/admin_sigup_notif.js"></script>
<script src="../assets/js/admin-site-functions/admin_signup_validation.js"></script>

</html>