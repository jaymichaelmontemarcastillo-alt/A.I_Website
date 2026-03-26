<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Shop Admin Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/admin-site/admin_style.css">
    <script src="../assets/js/admin-site-functions/admin_auth.js"></script>
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

                <form id="loginForm">

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" id="email" placeholder="admin@giftshop.com" required>
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" id="username" placeholder="admin" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" id="password" placeholder="••••••••" required>
                    </div>

                    <div class="forgot-password">
                        <a href="reset_password.php">Forgot Password?</a>
                    </div>

                    <button type="submit" class="sign-in-btn">
                        <i class="fa-solid fa-lock"></i>
                        Sign In
                    </button>

                </form>

                <div id="authModal" class="modal">

                    <div class="modal-content">

                        <div class="modal-icon" id="modalIcon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>

                        <p id="modalMessage"></p>

                        <button id="modalClose" class="modal-btn">
                            OK
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>