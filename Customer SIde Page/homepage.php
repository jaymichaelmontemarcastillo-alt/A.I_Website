
<?php include 'includes/header.php'; ?>

<main>
    <!-- ===== HERO ===== -->
    <section class="hero">
        <div class="hero-content">
            <h2>Thoughtful <strong>gifts</strong>, made simple.</h2>

             <div class="hero-gallery">
            <div class="hero-image"><img src="assets/images/products/product1.png" alt="Anniversary photo album"></div>
            <div class="hero-image"><img src="assets/images/products/product2.jpeg" alt="Couple wine set"></div>
            <div class="hero-image"><img src="assets/images/products/product3.jpg" alt="Gourmet chocolate box"></div>
            <div class="hero-image"><img src="assets/images/products/product4.png" alt="Gift bundle sample"></div>
        </div>
        
            <p class="sub-headline">
                Create meaningful gift sets, custom merchandise, and curated bundles without the stress of dealing with multiple suppliers.
            </p>
            <div class="actions">
                <a href="#" class="btn btn-primary">Build your gift set <span aria-hidden="true">→</span></a>
                <a href="#" class="btn btn-secondary form-modal-trigger" data-form-url="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true">Inquire Here</a>
            </div>
        </div>

       
    </section>

    <!-- ===== PROBLEM / SOLUTION ===== -->
    <section class="section">
        <div class="container">
            <div class="two-col">
                <div class="col">
                    <h2 class="section-title">Finding the perfect gift <br>shouldn't feel stressful.</h2>
                    <ul>
                        <li><i class="fas fa-clock"></i> Time spent searching for matching items</li>
                        <li><i class="fas fa-brain"></i> Overthinking how to create a thoughtful gift</li>
                        <li><i class="fas fa-truck"></i> Too many suppliers to coordinate with</li>
                        <li><i class="fas fa-boxes"></i> Minimum order quantities that don't fit your needs</li>
                    </ul>
                    <p style="margin-top: 24px; font-size: 1.1rem; color: #c44536; font-weight: 500;">
                        <i class="fas fa-arrow-right"></i> You should be focusing on the people receiving the gift — not the logistics behind it.
                    </p>
                </div>
                <div class="col">
                    <h2 class="section-title">We make gifting easier.</h2>
                    <p style="font-size: 1.05rem; color: #444;">At Anything Inside, we simplify the process by helping you create curated gift sets and custom merchandise in one place.</p>
                    <ul class="benefit-list">
                        <li><i class="fas fa-check-circle"></i> Source products</li>
                        <li><i class="fas fa-check-circle"></i> Customize items</li>
                        <li><i class="fas fa-check-circle"></i> Package beautifully</li>
                        <li><i class="fas fa-check-circle"></i> Deliver a complete experience</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STEPS ===== -->
    <section class="section" style="background: var(--panel);">
        <div class="container">
            <h2 class="section-title centered">How it works</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h3>Tell us what you need</h3>
                    <p>Share your budget, quantity, and vision.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h3>We curate and customize</h3>
                    <p>We'll recommend products and create your set.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h3>Receive your finished package</h3>
                    <p>Ready to gift, distribute, or share.</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="#" class="btn-primary form-modal-trigger" data-form-url="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true">Start Your Inquiry</a>
            </div>
        </div>
    </section>

    <!-- ===== EXPERIENCE ===== -->
    <section class="section">
        <div class="container">
            <div class="two-col">
                <div class="col">
                    <h2 class="section-title">Create gifts people actually remember.</h2>
                    <p style="font-size: 1.05rem; color: #444;">Whether for teams, clients, celebrations, or special occasions, your gifts become more than products — they become experiences that leave an impression.</p>
                    <div style="margin-top: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                        <span style="background: #f0ece9; padding: 6px 18px; border-radius: 30px; font-size: 0.9rem;">Gift bundles</span>
                        <span style="background: #f0ece9; padding: 6px 18px; border-radius: 30px; font-size: 0.9rem;">Packaging with ribbons</span>
                        <span style="background: #f0ece9; padding: 6px 18px; border-radius: 30px; font-size: 0.9rem;">Custom items</span>
                    </div>
                </div>
                <div class="col">
                    <h2 class="section-title">Skip the hassle of juggling suppliers and last-minute decisions.</h2>
                    <p style="font-size: 1.05rem; color: #444;">Avoid spending hours sourcing products, managing different vendors, and worrying whether everything fits together.</p>
                    <p style="margin-top: 16px; font-weight: 500; color: #c44536;">Anything Inside helps keep the process organized and efficient from start to finish.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ===== MODAL ===== -->
<div class="modal-overlay" id="formModal" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-paper-plane" style="margin-right: 10px;"></i>Start Your Inquiry</h3>
            <button class="modal-close" id="modalCloseBtn" aria-label="Close modal">&times;</button>
        </div>
        <iframe id="modalIframe" src="" title="Inquiry Form" loading="lazy"></iframe>
    </div>
</div>


<?php include 'includes/footer.php'; ?>