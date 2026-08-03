<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = strtolower($_SESSION['role'] ?? 'staff');
$name = $_SESSION['full_name'] ?? strtolower($_SESSION['role']);
?>

<header class="top-nav">

    <div class="header-left">
        
<span>Hello, <?= htmlspecialchars($name) ?></span>
    </div>

    <div class="header-right">
        <div class="user-info" id="userMenuBtn">
            <div class="avatar">
                <img id="userAvatar" src="default-avatar.png" alt="Admin Avatar">
            </div>
            <div>
                <strong id="userName">Username</strong><br>
                <small id="userRole">Super Admin</small>
            </div>
        </div>
        <div class="dropdown-menu" id="dropdownMenu">
            <div class="dropdown-header">
                <span>My Account</span>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="dropdown-item">
                <div class="dropdown-left">
                    <i class="fa-solid fa-moon"></i>
                    <span class="dropdown-text">Dark Mode</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </div>

            <!-- Account Settings -->
            <div class="dropdown-item" id="openAccountModal">
                <div class="dropdown-left">
                    <i class="fa-solid fa-gear"></i>
                    <span class="dropdown-text">Account Settings</span>
                </div>
            </div>
 <?php if (in_array($role, ['admin'])): ?>
            <!-- Email Notification Settings (business Gmail + app password used for outgoing mail) -->
            <div class="dropdown-item" id="openEmailSettingsModal">
                <div class="dropdown-left">
                    <i class="fa-solid fa-envelope"></i>
                    <span class="dropdown-text">Email Notification Settings</span>
                </div>
            </div>
      <?php endif; ?>
            <!-- Logout -->
            <div class="dropdown-item" id="logout">
                <a class="dropdown-left" href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="dropdown-text">Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="admin-account-modal" id="accountModal">
    <div class="modal-feedback" id="accountModalMessage"></div>
    <div class="admin-account-modal-content">
        <div class="modal-header">
            <div>
                <h2>Account Settings</h2>
                <p class="modal-description">Confirm your current password before saving profile or email changes.</p>
            </div>
            <button type="button" class="close-modal" onclick="closeModal()" aria-label="Close modal">×</button>
        </div>

        <div class="modal-body-grid">
            <div class="modal-main-info">
                <div class="profile-picture-upload">
                    <div class="preview-wrapper">
                        <img id="profilePreview" src="" alt="Profile Picture">
                    </div>
                    <label class="file-upload">
                        <span>Choose new avatar</span>
                        <input type="file" id="profileInput" accept="image/*">
                    </label>
                </div>

                <div class="input-group">
                    <label for="adminID">Admin ID</label>
                    <input id="adminID" placeholder="ID" readonly>
                </div>
                <div class="input-group">
                    <label for="adminName">Username</label>
                    <input id="adminName" placeholder="Username">
                </div>
                <div class="input-group">
                    <label for="adminEmail">Email</label>
                    <input id="adminEmail" placeholder="Email">
                </div>
                <div class="input-group">
                    <label for="createdAt">Created At</label>
                    <input id="createdAt" placeholder="Created At" readonly>
                </div>
            </div>

            <div class="modal-security-panel">
                <div class="input-group password-field">
                    <label for="currentPasswordInput">Current Password</label>
                    <input type="password" id="currentPasswordInput" placeholder="Enter current password">
                    <span id="toggleCurrentPassword">👁</span>
                </div>

                <div class="input-group password-field">
                    <label for="passwordInput">New Password</label>
                    <input type="password" id="passwordInput" placeholder="Leave blank to keep current password">
                    <span id="togglePassword">👁</span>
                </div>

                <p class="helper-text">Tip: New password should be at least 8 characters and include uppercase, lowercase, and a number.</p>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary cancel" onclick="closeModal()">Cancel</button>
                    <button id="saveProfileBtn" type="button" class="btn btn-primary">Update Profile</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Notification Settings Modal -->
<div class="admin-account-modal" id="emailSettingsModal">
    <div class="modal-feedback" id="emailSettingsMessage"></div>
    <div class="admin-account-modal-content">
        <div class="modal-header">
            <div>
                <h2>Email Notification Settings</h2>
                <p class="modal-description">This Gmail address and app password is used to send password reset codes and other system notifications. It's shared across the whole admin panel, not tied to your personal login.</p>
            </div>
            <button type="button" class="close-modal" onclick="closeEmailSettingsModal()" aria-label="Close modal">×</button>
        </div>

        <div class="input-group">
            <label for="gmailAddressInput">Gmail Address</label>
            <input type="email" id="gmailAddressInput" placeholder="business@gmail.com">
        </div>

        <div class="input-group password-field">
            <label for="appPasswordInput">Gmail App Password</label>
            <input type="password" id="appPasswordInput" placeholder="Leave blank to keep the current app password">
            <span id="toggleAppPassword">👁</span>
        </div>
        <p class="helper-text" id="appPasswordStatus">Checking current status…</p>

        <p class="helper-text">
            Don't have one? Generate it from your Google Account under
            Security &gt; 2-Step Verification &gt; App passwords. It's a 16-character code,
            separate from your normal Gmail password.
        </p>

        <div class="input-group password-field">
            <label for="emailCurrentPasswordInput">Your Admin Password</label>
            <input type="password" id="emailCurrentPasswordInput" placeholder="Confirm your own admin password to save">
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-secondary cancel" onclick="closeEmailSettingsModal()">Cancel</button>
            <button id="saveEmailSettingsBtn" type="button" class="btn btn-primary">Save Email Settings</button>
        </div>
    </div>
</div>