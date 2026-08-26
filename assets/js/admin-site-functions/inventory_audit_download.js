class InventoryAuditDownload {
    constructor() {
        this.init();
    }

    init() {
        this.setupModal();
    }

    setupModal() {
        if (!document.getElementById("qaDownloadModal")) {
            const modalHtml = `
                <div id="qaDownloadModal" class="qa-audit-overlay" style="z-index: 100070; display: none;">
                    <div class="qa-audit-modal" style="max-width: 400px; padding: 20px;">
                        <div class="qa-audit-header" style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;">
                            <h3 style="margin:0;"><i class="fa-solid fa-download"></i> Download Options</h3>
                            <button class="qa-audit-close" onclick="inventoryAuditDownload.closeDownloadModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#64748b;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="qa-audit-body" style="text-align: center; padding: 10px 0;">
                            <button class="qa-btn-submit" style="margin-bottom: 15px; width: 100%; justify-content: center; background-color: #ef4444; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;" onclick="inventoryAuditDownload.download('pdf')">
                                <i class="fa-solid fa-file-pdf"></i> Download to PDF
                            </button>
                            <button class="qa-btn-submit" style="background-color: #2b579a; color: white; width: 100%; justify-content: center; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;" onclick="inventoryAuditDownload.download('word')">
                                <i class="fa-solid fa-file-word"></i> Download to Word
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    }

    openDownloadModal() {
        const modal = document.getElementById("qaDownloadModal");
        if (modal) modal.style.display = "flex";
    }

    closeDownloadModal() {
        const modal = document.getElementById("qaDownloadModal");
        if (modal) modal.style.display = "none";
    }

    async download(format) {
        // Show loading state on buttons
        const pdfBtn = document.querySelector("#qaDownloadModal button:nth-child(1)");
        const wordBtn = document.querySelector("#qaDownloadModal button:nth-child(2)");
        const originalPdfText = pdfBtn.innerHTML;
        const originalWordText = wordBtn.innerHTML;

        if (format === 'pdf') {
            pdfBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating PDF...';
            pdfBtn.disabled = true;
        } else {
            wordBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating Word...';
            wordBtn.disabled = true;
        }

        const data = this.gatherAuditData();
        data.format = format;

        try {
            const response = await fetch('../../api/inventory_audit_pdf.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            const filename = `${data.audit_name || 'Inventory_Audit'}_${data.quote_number || ''}.${format === 'word' ? 'doc' : 'pdf'}`;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            
            this.closeDownloadModal();
        } catch (error) {
            console.error('Download error:', error);
            alert('Failed to download document.');
        } finally {
            pdfBtn.innerHTML = originalPdfText;
            wordBtn.innerHTML = originalWordText;
            pdfBtn.disabled = false;
            wordBtn.disabled = false;
        }
    }

    gatherAuditData() {
        const data = {
            audit_name: document.getElementById("qaAuditItemName")?.value || document.getElementById("qaAuditItemName")?.placeholder || "",
            quote_number: document.getElementById("qaQuoteNumber")?.textContent || "",
            customer: document.getElementById("qaCustomerName")?.textContent || "",
            contact: document.getElementById("qaContactPerson")?.textContent || "",
            address: document.getElementById("qaCustomerAddress")?.textContent || "",
            email: document.getElementById("qaCustomerEmail")?.textContent || "",

            materials: [],
            rejects: [],
            sales: [],
            overhead: [],

            total_materials: document.getElementById("qaTotalMaterialCost")?.textContent || "P0.00",
            total_rejects: document.getElementById("qaTotalRejectCost")?.textContent || "P0.00",
            total_overhead: document.getElementById("qaTotalOverheadCost")?.textContent || "P0.00",
            total_sales: document.getElementById("qaTotalAmountDue")?.textContent || "P0.00",
            total_cost: document.getElementById("qaTotalCost")?.textContent || "P0.00",
            profit: document.getElementById("qaProfit")?.textContent || "P0.00",

            remarks: document.getElementById("qaRemarks")?.value || "",

            created_by: document.getElementById("qaCreatedBy")?.value || "",
            audited_by: document.getElementById("qaAuditedBy")?.value || "",
            acknowledged_by: document.getElementById("qaAcknowledgedBy")?.value || ""
        };

        // Materials Used
        document.querySelectorAll("#qaMaterialCostsContainer .qa-dynamic-row").forEach(row => {
            const name = row.querySelector(".material-name-input")?.value || "";
            if (name.trim() !== '') {
                data.materials.push({
                    name: name,
                    qty: row.querySelector(".material-qty")?.value || "0",
                    price: row.querySelector(".material-cost")?.value || "0.00",
                    subtotal: row.querySelector(".material-total")?.value || "0.00"
                });
            }
        });

        // Rejects
        document.querySelectorAll("#qaRejectCostsContainer .qa-dynamic-row").forEach(row => {
            const name = row.querySelector(".reject-name-input")?.value || "";
            if (name.trim() !== '') {
                data.rejects.push({
                    name: name,
                    qty: row.querySelector(".reject-qty")?.value || "0",
                    price: row.querySelector(".reject-cost")?.value || "0.00",
                    subtotal: row.querySelector(".reject-total")?.value || "0.00"
                });
            }
        });

        // Total Sales
        document.querySelectorAll("#qaItemsContainer .qa-dynamic-row").forEach(row => {
            const name = row.querySelector(".item-name")?.value || "";
            if (name.trim() !== '') {
                data.sales.push({
                    name: name,
                    qty: row.querySelector(".item-qty")?.value || "0",
                    price: row.querySelector(".item-price")?.value || "0.00",
                    total: row.querySelector(".item-total")?.value || "0.00"
                });
            }
        });

        // Overhead Cost
        document.querySelectorAll("#qaOverheadContainer .qa-dynamic-row").forEach(row => {
            const item = row.querySelector(".ov-item")?.value || "";
            if (item.trim() !== '') {
                data.overhead.push({
                    item: item,
                    qty: row.querySelector(".ov-qty")?.value || "0",
                    cost: row.querySelector(".ov-cost")?.value || "0.00",
                    percentage: row.querySelector(".ov-pct")?.value || "0",
                    total: row.querySelector(".ov-total")?.value || "0.00"
                });
            }
        });

        return data;
    }
}

const inventoryAuditDownload = new InventoryAuditDownload();
