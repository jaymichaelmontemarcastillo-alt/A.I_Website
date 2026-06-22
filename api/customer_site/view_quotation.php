<?php
session_start();
$baseUrl = '../';
require_once '../connect/config.php';
include_once '../includes/header.php';
// api/view_quotation.php - Display quotation details and PDF viewer for converted quotations
$pdo = getDBConnection();
$user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
$quote_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$quote = null;
$items = [];

if ($quote_id <= 0) {
    $error = 'Invalid quotation selected.';
} else {
    // ── Fetch quotation with access control ────────────────────────────────
    if ($user_id !== null) {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND user_id = ?');
        $stmt->execute([$quote_id, $user_id]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM quotations WHERE id = ? AND session_id = ?');
        $stmt->execute([$quote_id, $session_id]);
    }

    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$quote) {
        $error = 'Quotation not found or access denied.';
    } else {
        // ── Check if quotation status is CONVERTED ──────────────────────────
        if (strtolower($quote['status']) !== 'converted') {
            $error = 'This quotation has not been converted to PDF yet. Only converted quotations can be viewed.';
        } else {
            // ── Fetch quotation items ────────────────────────────────────────
            $itemStmt = $pdo->prepare('SELECT * FROM quotation_items WHERE quotation_id = ?');
            $itemStmt->execute([$quote_id]);
            $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>

<div class="view-quotation-container">
    <!-- ── Page Header ──────────────────────────────────────────────── -->
    <div class="page-header">
        <div class="header-content">
            <h2><i class="fas fa-file-invoice"></i> View Quotation</h2>
            <p class="header-subtitle" id="quoteNumber"></p>
        </div>
        <div class="page-header-actions">
            <a href="../quotations.php" class="btn btn-secondary" title="Back to quotations list">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <?php if (!$error): ?>
                <button id="downloadPdfBtn" class="btn btn-primary" title="Download PDF">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Error Alert ──────────────────────────────────────────────── -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php else: ?>
        <!-- ── PDF Viewer Section ───────────────────────────────────────── -->
        <div class="form-section pdf-section">
            <div class="section-header">
                <h3><i class="fas fa-file-pdf"></i> Quotation Document</h3>
                <p class="section-subtitle">PDF version of your quotation</p>
            </div>
            <div class="section-body pdf-body">
                <div class="pdf-viewer-container">
                    <div class="pdf-toolbar">
                        <div class="pdf-toolbar-left">
                            <button class="pdf-btn" id="downloadPdfToolbar" title="Download PDF">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                            <button class="pdf-btn" id="printPdfBtn" title="Print PDF">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                        <div class="pdf-toolbar-right">
                            <span class="pdf-status" id="pdfStatus">
                                <i class="fas fa-spinner fa-spin"></i> Loading PDF...
                            </span>
                        </div>
                    </div>
                    <div class="pdf-viewer-wrapper">
                        <iframe id="pdfViewer" class="pdf-viewer" title="Quotation PDF Document"></iframe>
                    </div>
                    <div class="pdf-loading" id="pdfLoading" style="display: none;">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Unable to load PDF. Please try downloading instead.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Client Information Section ─────────────────────────────── -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-user"></i> Client Information</h3>
            </div>
            <div class="section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Client Name</label>
                        <p><?= htmlspecialchars($quote['client_name']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        <p><?= htmlspecialchars($quote['contact_person'] ?? 'N/A') ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <p><?= htmlspecialchars($quote['email']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <p><?= htmlspecialchars($quote['phone']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>Valid Until</label>
                        <p><?= $quote['expires_at'] ? date('F j, Y', strtotime($quote['expires_at'])) : 'N/A' ?></p>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <p>
                            <span class="status-badge status-converted">
                                <i class="fas fa-check-circle"></i> <?= ucfirst(htmlspecialchars($quote['status'])) ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Quotation Items Section ──────────────────────────────────── -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-shopping-cart"></i> Quotation Items</h3>
            </div>
            <div class="section-body">
                <div class="items-table-container">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Price (₱)</th>
                                <th>Total (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['description']) ?></td>
                                    <td class="qty-cell"><?= intval($item['quantity']) ?></td>
                                    <td class="price-cell">₱<?= number_format($item['unit_price'], 2) ?></td>
                                    <td class="total-cell">₱<?= number_format($item['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ── Summary Section ──────────────────────────────────────────── -->
        <div class="form-section">
            <div class="section-header">
                <h3><i class="fas fa-calculator"></i> Summary</h3>
            </div>
            <div class="section-body">
                <div class="summary-container">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">₱<?= number_format($quote['subtotal'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax:</span>
                        <span class="summary-value">₱<?= number_format($quote['tax'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Discount:</span>
                        <span class="summary-value">₱<?= number_format($quote['discount'], 2) ?></span>
                    </div>
                    <div class="summary-row total">
                        <span class="summary-label">Grand Total:</span>
                        <span class="summary-value">₱<?= number_format($quote['total'], 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Notes:</span>
                        <span class="summary-value"><?= nl2br(htmlspecialchars($quote['notes'] ?? 'None')) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Created:</span>
                        <span class="summary-value"><?= date('F j, Y', strtotime($quote['created_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<style>
    .view-quotation-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .header-content h2 {
        font-size: 2rem;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-subtitle {
        color: #666;
        margin: 0;
        font-size: 0.95rem;
    }

    .page-header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.65rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.95rem;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert-error i {
        color: #721c24;
        font-size: 1.25rem;
    }

    .form-section {
        background: white;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .section-header h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #333;
    }

    .section-subtitle {
        margin: 0;
        font-size: 0.85rem;
        color: #999;
    }

    .section-body {
        padding: 1.5rem;
    }

    .pdf-section {
        margin-bottom: 2rem;
    }

    .pdf-body {
        padding: 0;
    }

    .pdf-viewer-container {
        display: flex;
        flex-direction: column;
        height: 700px;
        background-color: #f5f5f5;
    }

    .pdf-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        gap: 1rem;
    }

    .pdf-toolbar-left {
        display: flex;
        gap: 0.5rem;
    }

    .pdf-toolbar-right {
        display: flex;
        align-items: center;
    }

    .pdf-btn {
        padding: 0.5rem 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 0.4rem;
        cursor: pointer;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .pdf-btn:hover {
        background-color: #0056b3;
    }

    .pdf-status {
        font-size: 0.9rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pdf-viewer-wrapper {
        flex: 1;
        overflow: auto;
        background-color: white;
        position: relative;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
        background-color: white;
    }

    .pdf-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #999;
        font-size: 1.1rem;
    }

    .pdf-loading i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-group p {
        margin: 0;
        color: #555;
        font-size: 0.95rem;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-converted {
        background-color: #d4edda;
        color: #155724;
    }

    .items-table-container {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .items-table thead {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .items-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
    }

    .items-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .items-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .qty-cell,
    .price-cell,
    .total-cell {
        text-align: right;
    }

    .summary-container {
        max-width: 600px;
        margin: 0 0 0 auto;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-row.total {
        font-weight: 600;
        font-size: 1.1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        border-radius: 0.4rem;
        margin-top: 0.5rem;
    }

    .summary-label {
        color: #666;
    }

    .summary-value {
        color: #333;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
        }

        .page-header-actions {
            width: 100%;
            flex-direction: column;
        }

        .page-header-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .pdf-viewer-container {
            height: 500px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .summary-container {
            max-width: 100%;
        }
    }
</style>

<script>
    // ── Configuration ────────────────────────────────────────────────────
    const quoteId = <?= intval($quote_id) ?>;
    const quoteNumber = '<?= htmlspecialchars($quote['quote_number'] ?? '', ENT_QUOTES) ?>';
    const pdfViewer = document.getElementById('pdfViewer');
    const downloadPdfBtn = document.getElementById('downloadPdfBtn');
    const downloadPdfToolbar = document.getElementById('downloadPdfToolbar');
    const printPdfBtn = document.getElementById('printPdfBtn');
    const pdfStatus = document.getElementById('pdfStatus');
    const pdfLoading = document.getElementById('pdfLoading');
    const quoteNumberElement = document.getElementById('quoteNumber');

    let pdfUrl = null;

    // ── Set Quote Number ──────────────────────────────────────────────────
    if (quoteNumberElement) {
        quoteNumberElement.textContent = 'Quote #: ' + quoteNumber;
    }

    // ── Load PDF on page load ──────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        loadPDF();
    });

    // ── Load PDF from API ──────────────────────────────────────────────────
    async function loadPDF() {
        try {
            pdfStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading PDF...';

            const response = await fetch('get_quotation_pdf.php?id=' + quoteId);

            if (!response.ok) {
                throw new Error('Failed to fetch PDF URL');
            }

            const data = await response.json();

            if (data.success && data.pdf_url) {
                pdfUrl = data.pdf_url;

                // Load PDF into iframe
                pdfViewer.src = pdfUrl;

                // Handle iframe load completion
                pdfViewer.onload = function() {
                    pdfStatus.innerHTML = '<i class="fas fa-check-circle"></i> PDF loaded successfully';
                    pdfStatus.style.color = '#28a745';
                    setTimeout(() => {
                        pdfStatus.style.opacity = '0';
                        pdfStatus.style.transition = 'opacity 0.3s ease';
                    }, 2000);
                };

                // Handle iframe load errors
                pdfViewer.onerror = function() {
                    showPdfError('Unable to load PDF in viewer');
                };

            } else {
                showPdfError(data.message || 'PDF file not found');
            }
        } catch (error) {
            console.error('Error loading PDF:', error);
            showPdfError('Failed to load PDF');
        }
    }

    // ── Show PDF Error ────────────────────────────────────────────────────
    function showPdfError(message) {
        pdfViewer.style.display = 'none';
        pdfLoading.style.display = 'flex';
        pdfStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
        pdfStatus.style.color = '#dc3545';
    }

    // ── Download PDF ──────────────────────────────────────────────────────
    function downloadPDF() {
        if (!pdfUrl) {
            alert('PDF URL not available');
            return;
        }

        const link = document.createElement('a');
        link.href = pdfUrl;
        link.download = 'quotation_' + quoteNumber.replace(/[^a-zA-Z0-9_\-]/g, '_') + '.pdf';
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ── Print PDF ──────────────────────────────────────────────────────
    function printPDF() {
        if (!pdfUrl) {
            alert('PDF URL not available');
            return;
        }

        const printWindow = window.open(pdfUrl, 'PrintPDF', 'width=800,height=600');
        if (!printWindow) {
            alert('Please allow pop-ups to print the PDF');
        }
    }

    // ── Event Listeners ────────────────────────────────────────────────────
    if (downloadPdfBtn) {
        downloadPdfBtn.addEventListener('click', downloadPDF);
    }

    if (downloadPdfToolbar) {
        downloadPdfToolbar.addEventListener('click', downloadPDF);
    }

    if (printPdfBtn) {
        printPdfBtn.addEventListener('click', printPDF);
    }
</script>

<?php include '../includes/footer.php'; ?>