<?php
//index.php - Home page redesigned with new content sections
session_start();
require_once 'connect/config.php';

include 'includes/header.php';

?>

<link rel="stylesheet" href="assets/css/customer-site/home.css">
<style>
    /* Toast notification for better UX */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        font-size: clamp(12px, 2vw, 14px);
    }

    .toast i {
        font-size: 18px;
    }

    .toast.show {
        display: flex;
    }

    .toast.error {
        background-color: #ff4444;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Icon button styles */
    .icon-btn {
        width: 44px;
        height: 44px;
        border: none;
        border-radius: 50%;
        background: white;
        color: #333;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .icon-btn.wishlist:hover {
        background: #ff4444;
        color: white;
    }

    .icon-btn.cart:hover {
        background: #0f3d67;
        color: white;
    }

    .icon-btn.in-wishlist {
        background: #ff4444;
        color: white;
    }

    .icon-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .gift-card {
        cursor: pointer;
    }

    /* Fix for hero section typo */
    .hero-text h1 span {
        color: #0f3d67;
    }

    @media (max-width: 768px) {
        .toast {
            bottom: 15px;
            right: 15px;
            left: 15px;
            max-width: calc(100% - 30px);
        }
    }
</style>

<!-- Toast notification -->
<div id="toast" class="toast">
    <i class="fa-solid fa-check-circle"></i>
    <span id="toastMessage">Item added to cart!</span>
</div>

<main>

    <!-- ================= HERO ================= -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>
                    Thoughtful Gifts, Made Simple.
                </h1>

                <p>
                    Create meaningful gift sets, custom merchandise, and curated bundles without the stress of dealing with multiple suppliers.
                </p>

                <div class="hero-buttons">
                    <a href="#start-inquiry" style="color:inherit; text-decoration:none;">
                        <button class="btn-primary">
                            Build Your Gift Set <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </a>
                    <a href="https://forms.gle/ujiUSwKGQLKgTa5D6" target="_blank" style="color:inherit; text-decoration:none;">
                        <button class="btn-secondary" style="border:0.35px solid rgb(219, 219, 219)">
                            Inquire Here
                        </button>
                    </a>
                </div>

                <p style="margin-top: 25px; font-size: 14px; color: #dbe7f3;">
                    From custom gifting to branded merchandise — everything you need, all in one place.
                </p>
            </div>
        </div>
    </section>

    <!-- ================= PROBLEM SECTION ================= -->
    <section class="problem-section">
        <div class="section-container">
            <div class="problem-content">
                <h2>Finding the perfect gift shouldn't feel stressful.</h2>

                <div class="problems-list">
                    <div class="problem-item">
                        <i class="fas fa-clock"></i>
                        <span>Time spent searching for matching items</span>
                    </div>
                    <div class="problem-item">
                        <i class="fas fa-brain"></i>
                        <span>Overthinking how to create a thoughtful gift</span>
                    </div>
                    <div class="problem-item">
                        <i class="fas fa-building"></i>
                        <span>Too many suppliers to coordinate with</span>
                    </div>
                    <div class="problem-item">
                        <i class="fas fa-box"></i>
                        <span>Minimum order quantities that don't fit your needs</span>
                    </div>
                </div>

                <p class="transition-line">
                    <strong>You should be focusing on the people receiving the gift — not the logistics behind it.</strong>
                </p>
            </div>
        </div>
    </section>

    <!-- ================= SOLUTION SECTION ================= -->
    <section class="solution-section">
        <div class="section-container">
            <h2>We make gifting easier.</h2>

            <p class="solution-intro">
                At Anything Inside, we simplify the process by helping you create curated gift sets and custom merchandise in one place.
            </p>

            <div class="solution-grid">
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Source products</h3>
                    <p>Browse and select from our curated collection of quality products</p>
                </div>
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Customize items</h3>
                    <p>Personalize your selections with custom branding or messaging</p>
                </div>
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Package beautifully</h3>
                    <p>We handle premium packaging with ribbons, cards, and personal touches</p>
                </div>
                <div class="solution-item">
                    <div class="solution-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Deliver a complete experience</h3>
                    <p>Your gifts arrive ready to impress, with every detail handled</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PROCESS SECTION (3 STEPS) ================= -->
    <section class="steps-section-alt" id="how-it-works">
        <div class="section-title">
            <h2>How It Works</h2>
            <p>Fast, clear steps to get your custom gift packages started and delivered on time.</p>
        </div>
        <div class="steps-list">
            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-comments"></i></span>
                    <h3>Step 1</h3>
                </div>
                <p>Share your gifting needs with us.</p>
            </div>
            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-lightbulb"></i></span>
                    <h3>Step 2</h3>
                </div>
                <p>Review our curated and customized recommendations.</p>
            </div>
            <div class="step-card">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-box-open"></i></span>
                    <h3>Step 3</h3>
                </div>
                <p>Receive your finished package ready for gifting or distribution.</p>
            </div>
            <div class="step-card cta-step">
                <div class="step-top">
                    <span class="step-icon"><i class="fa-solid fa-arrow-right-long"></i></span>
                    <h3>Step 4</h3>
                </div>
                <p>Start your inquiry here and get a personalized gift plan from our team.</p>
                <a class="btn-primary form-modal-trigger" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank" rel="noopener noreferrer">Start Inquiry</a>
            </div>
        </div>
    </section>

    <!-- ================= MEMORABLE GIFTS SECTION ================= -->
    <section class="memorable-gifts-section">
        <div class="section-container">
            <div class="memorable-header">
                <h2>Create gifts people actually remember.</h2>
                <p>
                    Whether for teams, clients, celebrations, or special occasions, your gifts become more than products — they become experiences that leave an impression.
                </p>
            </div>

            <!-- Visual Gallery showing gift bundles, packaging, and items -->
            <div class="gifts-visual-grid">
                <!-- Gift Bundle Card -->
                <div class="visual-card">
                    <div class="visual-overlay">
                        <h4>Curated Gift Bundles</h4>
                        <p>Thoughtfully arranged sets</p>
                    </div>
                    <!-- Replace src with actual image path -->
                    <img src="assets/images/gift-bundle.jpg" alt="Gift bundle with assorted items" onerror="this.parentElement.classList.add('icon-card'); this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-gift\'></i>'">
                </div>

                <!-- Packaging with Ribbons & Cards Card -->
                <div class="visual-card">
                    <div class="visual-overlay">
                        <h4>Premium Packaging</h4>
                        <p>Ribbons, cards & personal touches</p>
                    </div>
                    <!-- Replace src with actual image path -->
                    <img src="assets/images/premium-packaging.jpg" alt="Gift box with ribbons and card" onerror="this.parentElement.classList.add('icon-card'); this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box-open\'></i>'">
                </div>

                <!-- Items Card -->
                <div class="visual-card">
                    <div class="visual-overlay">
                        <h4>Quality Items</h4>
                        <p>Premium products for every occasion</p>
                    </div>
                    <!-- Replace src with actual image path -->
                    <img src="assets/images/quality-items.jpg" alt="Collection of premium gift items" onerror="this.parentElement.classList.add('icon-card'); this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-star\'></i>'">
                </div>
            </div>
        </div>
    </section>
    <!-- ================= BENEFITS SECTION 2 ================= -->
    <section class="benefits-section benefits-section-alt">
        <div class="section-container">
            <div class="benefit-block benefit-right">
                <div class="benefit-content">
                    <h2>Skip the hassle of juggling suppliers and last-minute decisions.</h2>
                    <p>Avoid spending hours sourcing products, managing different vendors, and worrying whether everything fits together. Anything Inside helps keep the process organized and efficient from start to finish.</p>
                </div>
                <div class="benefit-image">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FINAL CTA SECTION ================= -->
    <section class="final-cta-section" id="start-inquiry">
        <div class="cta-content">
            <h2>Ready to create something meaningful?</h2>
            <p>From curated bundles to customized merchandise, we help bring your ideas together.</p>

            <div class="cta-buttons">
                <a href="https://forms.gle/ujiUSwKGQLKgTa5D6" target="_blank" style="text-decoration:none;">
                    <button class="btn-primary btn-large">Get a Quote</button>
                </a>
                <a href="mailto:ai.anythinginside@gmail.com" style="text-decoration:none;">
                    <button class="btn-secondary btn-large">Message Us Today</button>
                </a>
            </div>

            <p class="reassurance-text">No pressure. Tell us your idea and we'll help you build from there.</p>
        </div>
    </section>

    <!-- ================= ABOUT US SECTION ================= -->
    <section class="about-section" id="about-us">
        <div class="section-container">
            <h2>About Anything Inside</h2>
            <p class="about-intro">
                Anything Inside provides time-saving printing and gifting solutions, offering ready-to-give personalized items and custom-printed corporate giveaways tailored for busy individuals, companies, and procurement professionals.
            </p>
            <p>
                We specialize in creating unique, ready-to-give products that save our customers the time and hassle of gift planning and packaging. From corporate giveaways to special occasion presents, we provide high-quality prints and creative packaging tailored to every client's needs.
            </p>

            <div class="contact-info">
                <h3>Get in Touch</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Hours</strong>
                            <p>Monday to Friday<br>9am to 6pm</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location</strong>
                            <p>Plaza Agapita Commercial Complex<br>Batong Malake, Los Baños, Laguna</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Phone</strong>
                            <p><a href="tel:09687305403" style="color: #0f3d67; text-decoration: none;">09687305403</a></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong>
                            <p><a href="mailto:ai.anythinginside@gmail.com" style="color: #0f3d67; text-decoration: none;">ai.anythinginside@gmail.com</a></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fab fa-facebook"></i>
                        <div>
                            <strong>Follow Us</strong>
                            <p><a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank" style="color: #0f3d67; text-decoration: none;">Anything Inside Printing Services</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>