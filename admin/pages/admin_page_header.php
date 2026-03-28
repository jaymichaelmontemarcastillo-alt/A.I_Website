<header class="top-nav">

    <div class="header-left">
        <button id="toggle-btn" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-chevron-left toggle-arrow"></i>
        </button>
        <span>Hello, Admin</span>
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
                <strong>My Account</strong>
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

            <!-- Logout -->
            <div class="dropdown-item logout">
                <a href="Logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="dropdown-text">Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>
<div class="modal" id="accountModal">
    <div class="modal-feedback" id="accountModalMessage"></div>
    <div class="modal-content">
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