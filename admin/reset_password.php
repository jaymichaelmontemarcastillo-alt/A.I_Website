<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | Gift Shop Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/admin-site/admin_style.css">

    <style>
        .password-requirements {
            display: none;
            margin-top: 15px;
            padding: 12px 15px;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 13px;
        }

        .password-requirements.active {
            display: block;
        }

        .req-item {
            display: flex;
            align-items: center;
            margin: 6px 0;
            color: #6b7280;
        }

        .req-item.met {
            color: #10b981;
        }

        .req-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1.5px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .req-item.met .req-icon {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .alert-dropdown {
            display: none;
            margin-top: 15px;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        .alert-dropdown.success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
            display: block;
        }

        .alert-dropdown.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
            display: block;
        }

        .alert-dropdown.info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .email-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
        }

        .code-timer {
            font-size: 12px;
            color: #ef4444;
            margin-top: 8px;
        }

        .code-timer.success {
            color: #10b981;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .btn-loading {
            position: relative;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            right: 20px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>

    <div class="login-wrapper">

        <!-- LEFT BRANDING -->

        <div class="login-brand">

            <div class="brand-content">

                <div class="brand-logo-text">
                    <div class="brand-logo-img">
                        <img src="../assets/images/AI_Logo.jpg" alt="Logo">
                    </div>

                    <h2>Finding the perfect gift shouldn't be stressful.</h2>
                </div>

                <p>Let us help you make it effortless and personal.</p>

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

                    <form id="emailForm" onsubmit="handleSendCode(event)">

                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" id="emailInput" name="email" placeholder="admin@giftshop.com" required>
                        </div>

                        <div class="email-note" id="emailNote"></div>

                        <button type="submit" class="sign-in-btn" id="sendCodeBtn">
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

                    <form id="codeForm" onsubmit="handleVerifyCode(event)">

                        <div class="code-inputs" id="codeInputs">

                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>
                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>
                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>
                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>
                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>
                            <input type="text" maxlength="1" pattern="[0-9]" class="code-input" inputmode="numeric" required>

                        </div>

                        <div class="code-timer" id="codeTimer"></div>

                        <button type="submit" class="sign-in-btn" id="verifyCodeBtn">
                            <i class="fa-solid fa-check"></i>
                            Verify Code
                        </button>

                        <div class="back-login">
                            <a href="#" onclick="handleBackStep(1); return false;">
                                <i class="fa-solid fa-arrow-left"></i>
                                Back
                            </a>
                        </div>

                    </form>

                </div>



                <!-- STEP 3 RESET PASSWORD -->

                <div class="step" id="step-password">

                    <h2>Reset Password</h2>

                    <p class="subtitle">
                        Create a new password for your account
                    </p>

                    <form id="passwordForm" onsubmit="handleResetPassword(event)">

                        <div class="input-group">
                            <label>New Password</label>
                            <input type="password" id="newPassword" placeholder="••••••••" required>
                        </div>

                        <div class="password-requirements" id="passwordRequirements">
                            <div class="req-item" id="req-length">
                                <span class="req-icon">✓</span>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="req-item" id="req-uppercase">
                                <span class="req-icon">✓</span>
                                <span>At least 1 uppercase letter (A-Z)</span>
                            </div>
                            <div class="req-item" id="req-lowercase">
                                <span class="req-icon">✓</span>
                                <span>At least 1 lowercase letter (a-z)</span>
                            </div>
                            <div class="req-item" id="req-number">
                                <span class="req-icon">✓</span>
                                <span>At least 1 number (0-9)</span>
                            </div>
                            <div class="req-item" id="req-special">
                                <span class="req-icon">✓</span>
                                <span>At least 1 special character (!@#$%^&*)</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Confirm Password</label>
                            <input type="password" id="confirmPassword" placeholder="••••••••" required>
                        </div>

                        <div class="alert-dropdown" id="passwordAlert"></div>

                        <button type="submit" class="sign-in-btn" id="resetPasswordBtn">
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
        let resendTimer = 0;
        let storedEmail = '';

        /* ──── STEP SWITCHER ──── */
        function nextStep(step) {
            document.querySelectorAll(".step").forEach(s => {
                s.classList.remove("active");
            });

            document.getElementById("step-" + (
                step == 1 ? "email" : step == 2 ? "code" : "password"
            )).classList.add("active");
        }

        /* ──── BACK WITH CLEAR TIMER ──── */
        function handleBackStep(step) {
            resendTimer = 0;
            updateResendTimer();
            nextStep(step);
        }

        /* ──── AUTO CODE INPUT ──── */
        document.addEventListener('DOMContentLoaded', function() {
            const codeInputs = document.querySelectorAll(".code-input");

            codeInputs.forEach((input, index) => {
                input.addEventListener("input", function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Move to next input
                    if (this.value.length === 1 && codeInputs[index + 1]) {
                        codeInputs[index + 1].focus();
                    }

                    // Move back on delete
                    if (this.value.length === 0 && index > 0) {
                        codeInputs[index - 1].focus();
                    }
                });

                input.addEventListener("keydown", function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        codeInputs[index - 1].focus();
                    }
                });
            });

            // Password validation listener
            const newPasswordInput = document.getElementById('newPassword');
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', validatePassword);
            }
        });

        /* ──── SEND VERIFICATION CODE ──── */
        async function handleSendCode(e) {
            e.preventDefault();

            const email = document.getElementById('emailInput').value.trim();
            const emailNote = document.getElementById('emailNote');
            const sendCodeBtn = document.getElementById('sendCodeBtn');

            if (!email) {
                showAlert(emailNote, 'Please enter your email address', 'error');
                return;
            }

            sendCodeBtn.disabled = true;
            sendCodeBtn.classList.add('btn-loading');

            try {
                const response = await fetch('../api/admin_site/send_reset_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const data = await response.json();

                if (data.success) {
                    storedEmail = email;
                    resendTimer = 60;
                    updateResendTimer();
                    showAlert(emailNote, 'Code sent successfully to your email', 'success');
                    setTimeout(() => {
                        nextStep(2);
                        startResendTimer();
                    }, 1500);
                } else {
                    showAlert(emailNote, data.message || 'Failed to send code', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(emailNote, 'An error occurred. Please try again.', 'error');
            } finally {
                sendCodeBtn.disabled = false;
                sendCodeBtn.classList.remove('btn-loading');
            }
        }

        /* ──── VERIFY CODE ──── */
        async function handleVerifyCode(e) {
            e.preventDefault();

            const codeInputs = document.querySelectorAll(".code-input");
            const code = Array.from(codeInputs).map(input => input.value).join('');
            const verifyCodeBtn = document.getElementById('verifyCodeBtn');

            if (code.length !== 6) {
                alert('Please enter all 6 digits');
                return;
            }

            verifyCodeBtn.disabled = true;
            verifyCodeBtn.classList.add('btn-loading');

            try {
                const response = await fetch('../api/admin_site/verify_reset_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: storedEmail,
                        code
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(document.getElementById('codeTimer'), 'Code verified successfully!', 'success');
                    setTimeout(() => {
                        nextStep(3);
                    }, 1500);
                } else {
                    showAlert(document.getElementById('codeTimer'), data.message || 'Invalid code', 'error');
                    // Clear inputs
                    codeInputs.forEach(input => input.value = '');
                    codeInputs[0].focus();
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(document.getElementById('codeTimer'), 'An error occurred', 'error');
            } finally {
                verifyCodeBtn.disabled = false;
                verifyCodeBtn.classList.remove('btn-loading');
            }
        }

        /* ──── VALIDATE PASSWORD ──── */
        function validatePassword() {
            const password = document.getElementById('newPassword').value;
            const requirements = document.getElementById('passwordRequirements');

            if (password.length > 0) {
                requirements.classList.add('active');
            } else {
                requirements.classList.remove('active');
            }

            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

            updateRequirementStatus('req-length', hasLength);
            updateRequirementStatus('req-uppercase', hasUppercase);
            updateRequirementStatus('req-lowercase', hasLowercase);
            updateRequirementStatus('req-number', hasNumber);
            updateRequirementStatus('req-special', hasSpecial);
        }

        function updateRequirementStatus(elementId, isMet) {
            const element = document.getElementById(elementId);
            if (isMet) {
                element.classList.add('met');
            } else {
                element.classList.remove('met');
            }
        }

        /* ──── RESET PASSWORD ──── */
        async function handleResetPassword(e) {
            e.preventDefault();

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const alertDiv = document.getElementById('passwordAlert');
            const resetPasswordBtn = document.getElementById('resetPasswordBtn');

            // Validate passwords match
            if (newPassword !== confirmPassword) {
                showAlert(alertDiv, 'Passwords do not match', 'error');
                return;
            }

            // Validate requirements
            const hasLength = newPassword.length >= 8;
            const hasUppercase = /[A-Z]/.test(newPassword);
            const hasLowercase = /[a-z]/.test(newPassword);
            const hasNumber = /[0-9]/.test(newPassword);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(newPassword);

            if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber || !hasSpecial) {
                const missing = [];
                if (!hasLength) missing.push('at least 8 characters');
                if (!hasUppercase) missing.push('uppercase letter');
                if (!hasLowercase) missing.push('lowercase letter');
                if (!hasNumber) missing.push('number');
                if (!hasSpecial) missing.push('special character');

                showAlert(alertDiv, 'Password must include: ' + missing.join(', '), 'error');
                return;
            }

            resetPasswordBtn.disabled = true;
            resetPasswordBtn.classList.add('btn-loading');

            try {
                const response = await fetch('../api/admin_site/reset_password_final.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: storedEmail,
                        password: newPassword
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(alertDiv, 'Password reset successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = 'admin_login.php';
                    }, 2000);
                } else {
                    showAlert(alertDiv, data.message || 'Failed to reset password', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(alertDiv, 'An error occurred. Please try again.', 'error');
            } finally {
                resetPasswordBtn.disabled = false;
                resetPasswordBtn.classList.remove('btn-loading');
            }
        }

        /* ──── ALERT HELPER ──── */
        function showAlert(element, message, type) {
            element.textContent = message;
            element.className = `alert-dropdown ${type}`;
            element.style.display = 'block';
        }

        /* ──── RESEND TIMER ──── */
        function startResendTimer() {
            const timerInterval = setInterval(() => {
                if (resendTimer > 0) {
                    resendTimer--;
                    updateResendTimer();
                } else {
                    clearInterval(timerInterval);
                }
            }, 1000);
        }

        function updateResendTimer() {
            const timerElement = document.getElementById('codeTimer');
            if (timerElement) {
                if (resendTimer > 0) {
                    timerElement.textContent = `Resend code in ${resendTimer}s`;
                    timerElement.classList.remove('success');
                } else {
                    timerElement.textContent = 'Did not receive code? Check your spam or ask your administrator.';
                    timerElement.classList.add('success');
                }
            }
        }
    </script>

</body>

<!-- TOAST ALERT -->
<div id="authToast" class="toast">
    <i class="fa-solid fa-circle-xmark toast-icon"></i>
    <span id="toastMessage"></span>
</div>
<?php include 'includes/auth_toast.php'; ?>

</html>