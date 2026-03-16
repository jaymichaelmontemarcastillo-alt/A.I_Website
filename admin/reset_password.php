<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | Gift Shop Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/admin-site/admin_style.css">

</head>

<body>

    <div class="login-wrapper">

        <!-- LEFT BRANDING -->

        <div class="login-brand">

            <div class="brand-content">

                <div class="brand-logo">
                    <i class="fa-solid fa-gift"></i>
                </div>

                <h1>Anything Inside</h1>

                <p>
                    Recover your admin account securely and continue managing your
                    gift shop products and orders.
                </p>

            </div>

        </div>



        <!-- RIGHT FORM SIDE -->

        <div class="login-form-container">

            <div class="login-card">

                <!-- STEP 1 EMAIL -->

                <div class="step active" id="step-email">

                    <h2>Forgot Password</h2>

                    <p class="subtitle">
                        Enter your email and we will send you a verification code
                    </p>

                    <form>

                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" placeholder="admin@giftshop.com" required>
                        </div>

                        <button type="button" class="sign-in-btn" onclick="nextStep(2)">
                            <i class="fa-solid fa-paper-plane"></i>
                            Send Code
                        </button>

                        <div class="back-login">
                            <a href="admin_login.php">
                                <i class="fa-solid fa-arrow-left"></i>
                                Back to Login
                            </a>
                        </div>

                    </form>

                </div>



                <!-- STEP 2 CODE -->

                <div class="step" id="step-code">

                    <h2>Email Verification</h2>

                    <p class="subtitle">
                        Enter the 6-digit code sent to your email
                    </p>

                    <div class="code-inputs">

                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">

                    </div>

                    <button class="sign-in-btn" onclick="nextStep(3)">
                        <i class="fa-solid fa-check"></i>
                        Verify Code
                    </button>

                    <div class="back-login">
                        <a href="#" onclick="nextStep(1)">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back
                        </a>
                    </div>

                </div>



                <!-- STEP 3 RESET PASSWORD -->

                <div class="step" id="step-password">

                    <h2>Reset Password</h2>

                    <p class="subtitle">
                        Create a new password for your account
                    </p>

                    <form>

                        <div class="input-group">
                            <label>New Password</label>
                            <input type="password" placeholder="••••••••">
                        </div>

                        <div class="input-group">
                            <label>Confirm Password</label>
                            <input type="password" placeholder="••••••••">
                        </div>

                        <button class="sign-in-btn">
                            <i class="fa-solid fa-key"></i>
                            Reset Password
                        </button>

                        <div class="back-login">
                            <a href="admin_login.php">
                                <i class="fa-solid fa-arrow-left"></i>
                                Back to Login
                            </a>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <script>
        /* STEP SWITCHER */

        function nextStep(step) {

            document.querySelectorAll(".step").forEach(s => {
                s.classList.remove("active");
            });

            document.getElementById("step-" + (
                step == 1 ? "email" : step == 2 ? "code" : "password"
            )).classList.add("active");

        }


        /* AUTO CODE INPUT */

        const inputs = document.querySelectorAll(".code-inputs input");

        inputs.forEach((input, index) => {

            input.addEventListener("input", () => {

                if (input.value.length === 1 && inputs[index + 1]) {
                    inputs[index + 1].focus();
                }

            });

        });
    </script>

</body>

</html>