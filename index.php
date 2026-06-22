<?php
include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/customer-site/home.css">

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main>
    <section class="hero">
        <div class="hero-content">
            <div class="hero-copy">
                <span class="eyebrow">Anything Inside</span>
                <h1>Curated gift sets and custom merchandise that feel premium from the first glance.</h1>
                <p>Bring your gifting ideas to life with personalized bundles, polished presentation, and one-stop production support.</p>

                <div class="hero-buttons">
                    <a class="btn-primary form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Request a Quote</a>
                    <a class="btn-secondary" href="#showcase">View Showcase</a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>120+</strong>
                        <span>Gift sets delivered</span>
                    </div>
                    <div class="hero-stat">
                        <strong>24h</strong>
                        <span>Quote response time</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-card hero-card-large">
                    <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=1200" alt="Curated gift set">
                    <div class="hero-card-copy">
                        <span class="label">Featured bundle</span>
                        <h3>Executive Welcome Collection</h3>
                        <p>Custom stationery, branded essentials, and premium packaging designed to make every first impression count.</p>
                    </div>
                </div>
                <div class="hero-card-group">
                    <div class="hero-card hero-card-small">
                        <h4>Branded event kits</h4>
                        <p>Stylish promo sets built for conferences, fairs, and company launches.</p>
                    </div>
                    <div class="hero-card hero-card-small">
                        <h4>Premium appreciation gifts</h4>
                        <p>Thoughtful packages made to delight employees, customers, and VIP guests.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-alt product-highlights">
        <div class="section-title">
            <h2>Why customers choose Anything Inside</h2>
            <p>We combine creative product sourcing, quality branding, and seamless coordination so your campaigns and events look exceptional.</p>
        </div>
        <div class="feature-grid">
            <div class="feature-item">
                <i class="fa-solid fa-gift"></i>
                <h3>Ready-to-send bundles</h3>
                <p>Choose complete sets that arrive polished, wrapped, and ready for gifting.</p>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <h3>Flexible budgets</h3>
                <p>Build small or large orders with custom pricing that fits your needs.</p>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-check-circle"></i>
                <h3>Full-service support</h3>
                <p>We manage sourcing, personalization, packaging, and delivery for every order.</p>
            </div>
        </div>
    </section>

    <section class="section showcase-section" id="showcase">
        <div class="section-title">
            <h2>Featured product showcase</h2>
            <p>Sample collections designed to impress customers, clients, and teams.</p>
        </div>

        <div class="showcase-grid">
            <article class="product-card">
                <div class="product-media">
                    <img src="https://images.unsplash.com/photo-1542831371-d531d36971e6?w=900" alt="Corporate welcome gift">
                    <span class="product-badge">Bestseller</span>
                </div>
                <div class="product-info">
                    <h3>Corporate Welcome Box</h3>
                    <p>A polished onboarding set with custom notebook, elegant mug, premium snacks, and brand-ready packaging.</p>
                    <div class="product-meta">
                        <span>From ₱1,899</span>
                        <span>Perfect for clients & new hires</span>
                    </div>
                    <a class="btn-secondary btn-block form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Inquire Now</a>
                </div>
            </article>

            <article class="product-card">
                <div class="product-media">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=900" alt="Branded event kit">
                    <span class="product-badge">Custom</span>
                </div>
                <div class="product-info">
                    <h3>Branded Event Kit</h3>
                    <p>Stylish promotional set with logo pens, premium notepad, and presentation-ready packaging.</p>
                    <div class="product-meta">
                        <span>From ₱1,299</span>
                        <span>Ideal for expos & conferences</span>
                    </div>
                    <a class="btn-secondary btn-block form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Inquire Now</a>
                </div>
            </article>

            <article class="product-card">
                <div class="product-media">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=900" alt="Luxury thank-you package">
                    <span class="product-badge">Premium</span>
                </div>
                <div class="product-info">
                    <h3>Luxury Thank-You Set</h3>
                    <p>Scented candle, personalized card, and elegant wrapping for a memorable appreciation gift.</p>
                    <div class="product-meta">
                        <span>From ₱2,250</span>
                        <span>Perfect for VIP appreciation</span>
                    </div>
                    <a class="btn-secondary btn-block form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Inquire Now</a>
                </div>
            </article>
        </div>

        <div class="showcase-footer">
            <a class="btn-primary" href="Bundles.php">Explore full collection</a>
        </div>
    </section>

    <section class="section section-alt how-it-works" id="how-it-works">
        <div class="section-title">
            <h2>How it works</h2>
            <p>A fast, clear process that keeps your gift planning simple.</p>
        </div>

        <div class="steps-list">
            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-comments"></i></span>
                    <h3>1. Share your vision</h3>
                </div>
                <p>Tell us your event, audience, and desired gift style.</p>
            </div>

            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-lightbulb"></i></span>
                    <h3>2. Review concept ideas</h3>
                </div>
                <p>We propose curated packages and product recommendations.</p>
            </div>

            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-box"></i></span>
                    <h3>3. Approve and produce</h3>
                </div>
                <p>We handle sourcing, assembly, and premium packaging.</p>
            </div>

            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-truck-fast"></i></span>
                    <h3>4. Ready to deliver</h3>
                </div>
                <p>Receive polished gift sets that are ready to impress.</p>
            </div>
        </div>
    </section>

    <section class="section cta-banner">
        <div class="cta-copy">
            <h2>Let’s build a gift experience that feels unforgettable.</h2>
            <p>Get a custom proposal, product recommendations, and premium packaging that reflects your brand.</p>
        </div>
        <a class="btn-primary form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Start your inquiry</a>
    </section>

    <section class="section section-alt about-us" id="about-us">
        <div class="section-title">
            <h2>About Anything Inside</h2>
            <p>We simplify printing, packaging, and gifting for businesses that want premium results without the coordination headache.</p>
        </div>
        <div class="about-grid">
            <div class="about-copy">
                <p>We deliver custom corporate giveaways, printed materials, and gift packages designed to make a strong impression.</p>
                <p>Our process is built for businesses and individuals who want creative, polished results without managing multiple suppliers.</p>
                <ul class="content-list">
                    <li>Custom sourcing for every concept</li>
                    <li>Premium wrapping and finishing details</li>
                    <li>Fast coordination and dependable delivery</li>
                </ul>
            </div>
            <div class="about-visual">
                <div class="about-card">
                    <h3>Our promise</h3>
                    <p>High-quality prints, thoughtful packaging, and smooth coordination from concept to delivery.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>