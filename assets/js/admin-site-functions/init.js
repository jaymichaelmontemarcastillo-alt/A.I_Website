/**
 * App Initialization
 * Initializes all managers when DOM is ready
 */
document.addEventListener("DOMContentLoaded", () => {
  // Initialize managers
  window.quotationManager = new QuotationManager();
  window.bomManager = new BomManager();
  window.quotationViewManager = new QuotationViewManager();

  // Add animation keyframes
  const style = document.createElement("style");
  style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
  document.head.appendChild(style);
});
