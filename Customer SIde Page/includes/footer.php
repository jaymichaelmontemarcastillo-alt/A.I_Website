<?php
$baseDir = $baseDir ?? '';
?>

<style>
    html, body {
        min-height: 100vh;
    }

    body {
        display: flex;
        flex-direction: column;
        margin: 0;
    }

    footer {
        background: #123B5D;
        color: white;
        padding: 30px 20px 20px 20px;
        margin-top: auto;
        width: 100%;
        box-sizing: border-box;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 40px;
    }

    .footer-links h3,
    .footer-links p,
    .brand h1,
    .brand p {
        margin: 0;
    }

    @media (max-width: 768px) {
        footer {
            padding: 25px 15px 15px 15px;
        }

        .footer-content {
            flex-direction: column;
            gap: 25px;
        }

        .footer-content > div {
            max-width: 100%;
            width: 100%;
        }

        .footer-links p {
            margin-bottom: 10px;
        }

        .brand {
            align-items: flex-start;
        }
    }
</style>

<footer>

    <div class="footer-content">

        <!-- Brand -->
        <div style="max-width:250px;">
            <div class="brand">
                <img src="<?= $baseDir ?>assets/images/AI_Logo.jpg" alt="Anything Inside Logo" class="logo">
                <h1>Anything Inside</h1>
            </div>

            <p style="color:#cfd8e3; font-size:14px; padding-top:10px;">
                Your one-stop gift shop for every occasion.
                Curated gifts with love.
            </p>
        </div>

        <!-- Quick Links -->
        <div class="footer-links">
            <h3 style="color:#FFC107; margin-bottom:15px;">QUICK LINKS</h3>
            <div class="footer-link-tabs">
                <a href="homepage.php">Home</a>
                <a href="Bundles.php">Gift Bundles</a>
                <a href="product.php">Custom Merchandise</a>
                <a href="AboutUs.php">About Us</a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" class="form-modal-trigger" target="_blank" rel="noopener noreferrer">Inquire here</a>
            </div>
        </div>
        <!-- Store Hours -->
        <div class="footer-links">
            <h3 style="color:#FFC107; margin-bottom:15px;">STORE HOURS</h3>
            <p>Mon – Fri: 9AM – 6PM</p>
        </div>

    </div>

    <hr style="border:0; border-top:1px solid #2b587a; margin:30px 0;">

    <div style="text-align:center; font-size:14px; color:#cfd8e3;">
        &copy; <?php echo date("Y"); ?> Anything Inside. All rights reserved.
    </div>

</footer>

<div class="form-modal-overlay" id="inquiryModal" aria-hidden="true">
    <div class="form-modal" role="dialog" aria-modal="true" aria-labelledby="inquiryModalTitle">
        <div class="form-modal-header">
            <h3 id="inquiryModalTitle">Submit an Inquiry</h3>
            <button type="button" class="form-modal-close" id="closeFormModal" aria-label="Close inquiry form">&times;</button>
        </div>
        <div class="form-modal-body">
            <iframe id="inquiryFormIframe" src="" loading="lazy" title="Inquiry Form"></iframe>
        </div>
        <div class="form-modal-fallback">
            <span>If the form does not load in the modal, open it in a new tab.</span>
            <a id="inquiryFormExternal" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Open form in a new tab</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true';
        const modal = document.getElementById('inquiryModal');
        const iframe = document.getElementById('inquiryFormIframe');
        const externalLink = document.getElementById('inquiryFormExternal');
        const closeBtn = document.getElementById('closeFormModal');

        function openFormModal(url) {
            if (!modal) return;
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            if (iframe) iframe.src = url || formUrl;
            if (externalLink) externalLink.href = url || formUrl;
        }

        function closeFormModal() {
            if (!modal) return;
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            if (iframe) iframe.src = '';
        }

        document.querySelectorAll('.form-modal-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                const url = trigger.dataset.formUrl || trigger.getAttribute('href');
                openFormModal(url);
            });
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeFormModal);
        }

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeFormModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal && modal.classList.contains('show')) {
                closeFormModal();
            }
        });
    });
</script>

</body>

</html>