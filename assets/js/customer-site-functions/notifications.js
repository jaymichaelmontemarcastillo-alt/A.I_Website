// Notification System
class NotificationSystem {
    constructor() {
        this.createContainers();
    }

    createContainers() {
        // Toast container
        if (!document.getElementById('toastContainer')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }

        // Modal container
        if (!document.getElementById('modalContainer')) {
            const modalContainer = document.createElement('div');
            modalContainer.id = 'modalContainer';
            document.body.appendChild(modalContainer);
        }
    }

    // Toast notification
    toast(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const titles = {
            success: 'Success',
            error: 'Error',
            warning: 'Warning',
            info: 'Info'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fa-solid ${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <div class="toast-close" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </div>
        `;

        container.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);

        // Auto remove
        setTimeout(() => {
            if (toast && toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast && toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }
        }, duration);
    }

    // Confirmation modal
    confirm(options) {
        return new Promise((resolve) => {
            const container = document.getElementById('modalContainer');
            if (!container) return;
            
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            
            const icons = {
                warning: 'fa-exclamation-triangle',
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle'
            };

            modal.innerHTML = `
                <div class="modal-box">
                    <div class="modal-icon ${options.type || 'warning'}">
                        <i class="fa-solid ${icons[options.type || 'warning']}"></i>
                    </div>
                    <h3>${options.title || 'Confirm Action'}</h3>
                    <p>${options.message || 'Are you sure you want to proceed?'}</p>
                    <div class="modal-actions">
                        <button class="modal-btn cancel">${options.cancelText || 'Cancel'}</button>
                        <button class="modal-btn ${options.confirmClass || 'confirm'}">${options.confirmText || 'Confirm'}</button>
                    </div>
                </div>
            `;

            container.appendChild(modal);
            
            // Show modal
            setTimeout(() => modal.classList.add('show'), 10);

            // Handle buttons
            const cancelBtn = modal.querySelector('.cancel');
            const confirmBtn = modal.querySelector('.modal-btn:last-child');

            cancelBtn.addEventListener('click', () => {
                modal.classList.remove('show');
                setTimeout(() => {
                    if (modal && modal.parentElement) {
                        modal.remove();
                    }
                }, 300);
                resolve(false);
            });

            confirmBtn.addEventListener('click', () => {
                modal.classList.remove('show');
                setTimeout(() => {
                    if (modal && modal.parentElement) {
                        modal.remove();
                    }
                }, 300);
                resolve(true);
            });
        });
    }

    // Loading overlay
    loading(message = 'Processing...') {
        const container = document.getElementById('modalContainer');
        if (!container) return { hide: () => {} };
        
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = `
            <div class="modal-box" style="text-align: center;">
                <div class="spinner" style="margin: 20px auto;"></div>
                <p style="color: #666; margin-bottom: 20px;">${message}</p>
            </div>
        `;
        
        container.appendChild(overlay);
        setTimeout(() => overlay.classList.add('show'), 10);
        
        return {
            hide: () => {
                if (overlay && overlay.parentElement) {
                    overlay.classList.remove('show');
                    setTimeout(() => {
                        if (overlay && overlay.parentElement) {
                            overlay.remove();
                        }
                    }, 300);
                }
            }
        };
    }
}

// Initialize notification system and make it globally available
const notif = new NotificationSystem();

// For debugging - test if notification works
console.log('Notification system loaded');