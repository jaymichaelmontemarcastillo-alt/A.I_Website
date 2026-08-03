<?php
/**
 * Anything Inside Website Flow
 * 
 * This file represents the complete website structure for Anything Inside,
 * a gifting and custom merchandise service.
 */

// Define page constants for easy navigation
define('PAGE_HOME', 'home');
define('PAGE_GIFT_BUNDLES', 'gift-bundles');
define('PAGE_CUSTOM_MERCHANDISE', 'custom-merchandise');
define('PAGE_ABOUT_US', 'about-us');
define('PAGE_INQUIRE', 'inquire');

// Get current page from query string, default to home
$currentPage = isset($_GET['page']) ? $_GET['page'] : PAGE_HOME;

// Function to include header
function renderHeader($currentPage) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anything Inside - Thoughtful Gifts, Made Simple</title>
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            /* ===== RESET & BASE ===== */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                color: #1a1a2e;
                background-color: #faf9f8;
                line-height: 1.6;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 24px;
            }

            /* ===== HEADER / NAV ===== */
            .site-header {
                background: white;
                box-shadow: 0 2px 12px rgba(0,0,0,0.05);
                padding: 16px 0;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .header-inner {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }

            .logo a {
                font-size: 1.6rem;
                font-weight: 700;
                color: #1a1a2e;
                text-decoration: none;
                letter-spacing: -0.5px;
            }

            .logo a span {
                color: #c44536;
            }

            nav ul {
                display: flex;
                list-style: none;
                gap: 28px;
                align-items: center;
                flex-wrap: wrap;
            }

            nav ul li a {
                text-decoration: none;
                color: #333;
                font-weight: 500;
                font-size: 0.95rem;
                transition: color 0.2s;
                padding: 6px 0;
                border-bottom: 2px solid transparent;
            }

            nav ul li a:hover,
            nav ul li a.active {
                color: #c44536;
                border-bottom-color: #c44536;
            }

            /* ===== BUTTONS ===== */
            .btn-primary {
                display: inline-block;
                background: #c44536;
                color: white !important;
                padding: 12px 32px;
                border-radius: 40px;
                font-weight: 600;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: background 0.25s, transform 0.1s;
                font-size: 1rem;
            }

            .btn-primary:hover {
                background: #a8362a;
                transform: translateY(-2px);
            }

            .btn-secondary {
                display: inline-block;
                background: transparent;
                color: #c44536 !important;
                padding: 12px 32px;
                border-radius: 40px;
                font-weight: 600;
                text-decoration: none;
                border: 2px solid #c44536;
                cursor: pointer;
                transition: all 0.25s;
                font-size: 1rem;
            }

            .btn-secondary:hover {
                background: #c44536;
                color: white !important;
            }

            /* ===== HERO SECTION ===== */
            .hero {
                padding: 60px 0 40px;
                text-align: center;
            }

            .hero h1 {
                font-size: 3rem;
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 16px;
                color: #1a1a2e;
            }

            .hero h1 span {
                color: #c44536;
            }

            .hero p.sub {
                font-size: 1.25rem;
                color: #555;
                max-width: 640px;
                margin: 0 auto 32px;
            }

            .hero .cta-group {
                display: flex;
                gap: 16px;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 12px;
            }

            .hero .supporting-line {
                color: #666;
                font-size: 0.95rem;
                margin-top: 16px;
            }

            /* ===== SECTIONS ===== */
            section {
                padding: 60px 0;
                border-bottom: 1px solid #eee;
            }

            section:last-of-type {
                border-bottom: none;
            }

            .section-title {
                font-size: 2.2rem;
                font-weight: 700;
                margin-bottom: 20px;
                color: #1a1a2e;
            }

            .section-title.centered {
                text-align: center;
            }

            .section-sub {
                font-size: 1.1rem;
                color: #555;
                max-width: 700px;
                margin-bottom: 32px;
            }

            .section-sub.centered {
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }

            /* ===== PROBLEM / BENEFIT GRID ===== */
            .two-col {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 40px;
                align-items: center;
            }

            .two-col .col ul {
                list-style: none;
                padding: 0;
            }

            .two-col .col ul li {
                padding: 8px 0;
                font-size: 1rem;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .two-col .col ul li i {
                color: #c44536;
                width: 20px;
            }

            .benefit-list {
                list-style: none;
                padding: 0;
            }

            .benefit-list li {
                padding: 10px 0;
                font-size: 1.05rem;
                display: flex;
                align-items: center;
                gap: 14px;
                border-bottom: 1px solid #f0ece9;
            }

            .benefit-list li:last-child {
                border-bottom: none;
            }

            .benefit-list li i {
                color: #c44536;
                font-size: 1.2rem;
                width: 28px;
            }

            /* ===== STEPS ===== */
            .steps-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 32px;
                margin: 40px 0;
            }

            .step-card {
                background: white;
                padding: 32px 24px;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
                text-align: center;
                border: 1px solid #f0ece9;
            }

            .step-card .step-num {
                display: inline-block;
                background: #c44536;
                color: white;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                line-height: 48px;
                font-weight: 700;
                font-size: 1.3rem;
                margin-bottom: 16px;
            }

            .step-card h3 {
                font-size: 1.25rem;
                margin-bottom: 8px;
            }

            .step-card p {
                color: #555;
                font-size: 0.95rem;
            }

            /* ===== FINAL CTA ===== */
            .final-cta {
                background: #1a1a2e;
                color: white;
                padding: 60px 0;
                text-align: center;
                border-radius: 24px;
                margin: 40px 0;
            }

            .final-cta h2 {
                font-size: 2.4rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .final-cta p {
                font-size: 1.1rem;
                opacity: 0.85;
                max-width: 560px;
                margin: 0 auto 32px;
            }

            .final-cta .cta-group {
                display: flex;
                gap: 16px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .final-cta .btn-primary {
                background: #c44536;
            }

            .final-cta .btn-secondary {
                border-color: white;
                color: white !important;
            }

            .final-cta .btn-secondary:hover {
                background: white;
                color: #1a1a2e !important;
            }

            .final-cta .reassurance {
                margin-top: 20px;
                font-size: 0.9rem;
                opacity: 0.7;
            }

            /* ===== FOOTER ===== */
            .site-footer {
                background: #1a1a2e;
                color: rgba(255,255,255,0.7);
                padding: 40px 0 24px;
                margin-top: 40px;
            }

            .footer-grid {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr;
                gap: 40px;
                margin-bottom: 32px;
            }

            .footer-grid h4 {
                color: white;
                font-weight: 600;
                margin-bottom: 12px;
            }

            .footer-grid p,
            .footer-grid a {
                color: rgba(255,255,255,0.7);
                text-decoration: none;
                font-size: 0.95rem;
                display: block;
                margin-bottom: 6px;
            }

            .footer-grid a:hover {
                color: white;
            }

            .footer-bottom {
                border-top: 1px solid rgba(255,255,255,0.1);
                padding-top: 20px;
                text-align: center;
                font-size: 0.85rem;
            }

            /* ===== PAGE SPECIFIC ===== */
            .page-banner {
                padding: 40px 0 20px;
                text-align: center;
            }

            .page-banner h1 {
                font-size: 2.6rem;
                font-weight: 700;
            }

            .page-banner p {
                font-size: 1.15rem;
                color: #555;
                max-width: 640px;
                margin: 8px auto 0;
            }

            .about-content {
                max-width: 800px;
                margin: 0 auto;
            }

            .about-content p {
                font-size: 1.1rem;
                color: #333;
                margin-bottom: 16px;
            }

            .inquire-details {
                background: white;
                padding: 32px 40px;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.04);
                max-width: 600px;
                margin: 20px auto;
                border: 1px solid #f0ece9;
            }

            .inquire-details p {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 8px 0;
                font-size: 1rem;
            }

            .inquire-details p i {
                width: 24px;
                color: #c44536;
            }

            .inquire-details a {
                color: #c44536;
                text-decoration: none;
            }

            .inquire-details a:hover {
                text-decoration: underline;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 992px) {
                .two-col {
                    grid-template-columns: 1fr;
                    gap: 24px;
                }
                .steps-grid {
                    grid-template-columns: 1fr 1fr;
                }
                .footer-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 768px) {
                .header-inner {
                    flex-direction: column;
                    gap: 16px;
                }
                nav ul {
                    justify-content: center;
                    gap: 16px;
                }
                .hero h1 {
                    font-size: 2.2rem;
                }
                .section-title {
                    font-size: 1.8rem;
                }
                .steps-grid {
                    grid-template-columns: 1fr;
                }
                .footer-grid {
                    grid-template-columns: 1fr;
                    gap: 24px;
                }
                .final-cta h2 {
                    font-size: 1.8rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- ===== HEADER ===== -->
        <header class="site-header">
            <div class="container header-inner">
                <div class="logo">
                    <a href="?page=<?= PAGE_HOME ?>">Anything <span>Inside</span></a>
                </div>
                <nav>
                    <ul>
                        <li><a href="?page=<?= PAGE_HOME ?>" class="<?= $currentPage === PAGE_HOME ? 'active' : '' ?>">Home</a></li>
                        <li><a href="?page=<?= PAGE_GIFT_BUNDLES ?>" class="<?= $currentPage === PAGE_GIFT_BUNDLES ? 'active' : '' ?>">Gift Bundles</a></li>
                        <li><a href="?page=<?= PAGE_CUSTOM_MERCHANDISE ?>" class="<?= $currentPage === PAGE_CUSTOM_MERCHANDISE ? 'active' : '' ?>">Custom Merchandise</a></li>
                        <li><a href="?page=<?= PAGE_ABOUT_US ?>" class="<?= $currentPage === PAGE_ABOUT_US ? 'active' : '' ?>">About Us</a></li>
                        <li><a href="?page=<?= PAGE_INQUIRE ?>" class="<?= $currentPage === PAGE_INQUIRE ? 'active' : '' ?>">Inquire Here</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <main>
    <?php
}

// Function to render footer
function renderFooter() {
    ?>
        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-grid">
                    <div>
                        <h4>Anything Inside</h4>
                        <p>Thoughtful Gifts, Made Simple.</p>
                        <p style="margin-top: 8px;">Creating meaningful gift sets, custom merchandise, and curated bundles without the stress of multiple suppliers.</p>
                    </div>
                    <div>
                        <h4>Quick Links</h4>
                        <a href="?page=<?= PAGE_HOME ?>">Home</a>
                        <a href="?page=<?= PAGE_GIFT_BUNDLES ?>">Gift Bundles</a>
                        <a href="?page=<?= PAGE_CUSTOM_MERCHANDISE ?>">Custom Merchandise</a>
                        <a href="?page=<?= PAGE_ABOUT_US ?>">About Us</a>
                        <a href="?page=<?= PAGE_INQUIRE ?>">Inquire Here</a>
                    </div>
                    <div>
                        <h4>Contact</h4>
                        <p><i class="fas fa-phone" style="width:20px;"></i> 09687305403</p>
                        <p><i class="fas fa-envelope" style="width:20px;"></i> ai.anythinginside@gmail.com</p>
                        <p><i class="fas fa-map-marker-alt" style="width:20px;"></i> Plaza Agapita Commercial Complex, Batong Malake, Los Baños, Laguna</p>
                        <p><i class="fab fa-facebook" style="width:20px;"></i> <a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank">Facebook Page</a></p>
                    </div>
                </div>
                <div class="footer-bottom">
                    &copy; <?= date('Y') ?> Anything Inside. All rights reserved. | Open Mon–Fri, 9am–6pm
                </div>
            </div>
        </footer>
    </body>
    </html>
    <?php
}

// ============================================================
// PAGE RENDER FUNCTIONS
// ============================================================

function renderHome() {
    ?>
    <!-- ===== HERO ===== -->
    <section class="hero">
        <div class="container">
            <h1>Thoughtful Gifts, <span>Made Simple.</span></h1>
            <p class="sub">Create meaningful gift sets, custom merchandise, and curated bundles without the stress of dealing with multiple suppliers.</p>
            <div class="cta-group">
                <a href="#" class="btn-primary">Build Your Gift Set</a>
                <a href="?page=<?= PAGE_INQUIRE ?>" class="btn-secondary">Inquire Here</a>
            </div>
            <p class="supporting-line">From custom gifting to branded merchandise — everything you need, all in one place.</p>
        </div>
    </section>

    <!-- ===== PROBLEM ===== -->
    <section>
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
    <section>
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
            <div style="text-align: center; margin-top: 12px;">
                <a href="?page=<?= PAGE_INQUIRE ?>" class="btn-primary">Start Your Inquiry</a>
            </div>
        </div>
    </section>

    <!-- ===== EXPERIENCE ===== -->
    <section>
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

    <!-- ===== FINAL CTA ===== -->
    <section>
        <div class="container">
            <div class="final-cta">
                <h2>Ready to create something meaningful?</h2>
                <p>From curated bundles to customized merchandise, we help bring your ideas together.</p>
                <div class="cta-group">
                    <a href="#" class="btn-primary">Get a Quote</a>
                    <a href="?page=<?= PAGE_INQUIRE ?>" class="btn-secondary">Message Us Today</a>
                </div>
                <p class="reassurance">No pressure. Tell us your idea and we'll help you build from there.</p>
            </div>
        </div>
    </section>
    <?php
}

function renderGiftBundles() {
    ?>
    <div class="page-banner">
        <div class="container">
            <h1>Gift Bundles</h1>
            <p>Handpicked collections for every occasion. Thoughtfully curated, beautifully packaged.</p>
        </div>
    </div>
    <section>
        <div class="container">
            <p style="font-size: 1.15rem; max-width: 700px; margin: 0 auto; text-align: center; color: #444;">
                Our gift bundles are designed to delight. Whether it's a corporate token, a team celebration, 
                or a personal gesture, we put together the perfect combination of quality items that feel 
                personal and polished.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap: 24px; margin-top: 40px;">
                <div style="background:white; padding:24px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-gift" style="font-size:2.4rem; color:#c44536; margin-bottom:12px;"></i>
                    <h3>Corporate Bundles</h3>
                    <p style="color:#555; font-size:0.95rem;">Impressive sets for clients and partners.</p>
                </div>
                <div style="background:white; padding:24px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-users" style="font-size:2.4rem; color:#c44536; margin-bottom:12px;"></i>
                    <h3>Team Gifts</h3>
                    <p style="color:#555; font-size:0.95rem;">Appreciation packages for your employees.</p>
                </div>
                <div style="background:white; padding:24px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-calendar-alt" style="font-size:2.4rem; color:#c44536; margin-bottom:12px;"></i>
                    <h3>Special Occasions</h3>
                    <p style="color:#555; font-size:0.95rem;">Weddings, anniversaries, and more.</p>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderCustomMerchandise() {
    ?>
    <div class="page-banner">
        <div class="container">
            <h1>Custom Merchandise</h1>
            <p>Branded items that stand out. Tailored to your identity, printed with precision.</p>
        </div>
    </div>
    <section>
        <div class="container">
            <p style="font-size: 1.15rem; max-width: 700px; margin: 0 auto; text-align: center; color: #444;">
                From premium giveaways to everyday essentials, we help you create merchandise that 
                represents your brand with quality and style.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 24px; margin-top: 40px;">
                <div style="background:white; padding:20px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-tshirt" style="font-size:2rem; color:#c44536;"></i>
                    <h4 style="margin-top:12px;">Apparel</h4>
                </div>
                <div style="background:white; padding:20px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-mug-hot" style="font-size:2rem; color:#c44536;"></i>
                    <h4 style="margin-top:12px;">Drinkware</h4>
                </div>
                <div style="background:white; padding:20px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-pen" style="font-size:2rem; color:#c44536;"></i>
                    <h4 style="margin-top:12px;">Stationery</h4>
                </div>
                <div style="background:white; padding:20px; border-radius:16px; border:1px solid #f0ece9; text-align:center;">
                    <i class="fas fa-bag-shopping" style="font-size:2rem; color:#c44536;"></i>
                    <h4 style="margin-top:12px;">Tote Bags</h4>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderAboutUs() {
    ?>
    <div class="page-banner">
        <div class="container">
            <h1>About Us</h1>
            <p>Time-saving printing and gifting solutions, crafted with care.</p>
        </div>
    </div>
    <section>
        <div class="container about-content">
            <p>
                Anything Inside provides <strong>time-saving printing and gifting solutions</strong>, 
                offering ready-to-give personalized items and custom-printed corporate giveaways 
                tailored for busy individuals, companies, and procurement professionals.
            </p>
            <p>
                We specialize in creating unique, ready-to-give products that save our customers 
                the time and hassle of gift planning and packaging. From corporate giveaways to 
                special occasion presents, we provide high-quality prints and creative packaging 
                tailored to every client's needs.
            </p>
            <div style="background: #faf9f8; padding: 24px; border-radius: 12px; margin-top: 24px; border-left: 4px solid #c44536;">
                <p style="font-style: italic; color: #333;">"Thoughtful Gifts, Made Simple."</p>
            </div>
        </div>
    </section>
    <?php
}

function renderInquire() {
    ?>
    <div class="page-banner">
        <div class="container">
            <h1>Inquire Here</h1>
            <p>Tell us your vision — we'll help bring it to life.</p>
        </div>
    </div>
    <section>
        <div class="container" style="text-align: center;">
            <div class="inquire-details">
                <p><i class="fas fa-calendar-alt"></i> Open Monday to Friday, 9am to 6pm</p>
                <p><i class="fas fa-map-marker-alt"></i> Plaza Agapita Commercial Complex, Batong Malake, Los Baños, Laguna</p>
                <p><i class="fas fa-phone"></i> 09687305403</p>
                <p><i class="fas fa-envelope"></i> ai.anythinginside@gmail.com</p>
                <p><i class="fab fa-facebook"></i> <a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank">Anything Inside Printing Services</a></p>
            </div>
            <div style="margin-top: 32px;">
                <a href="https://forms.gle/ujiUSwKGQLKgTa5D6" target="_blank" class="btn-primary" style="font-size: 1.2rem; padding: 16px 48px;">
                    <i class="fas fa-paper-plane"></i> Fill out the Inquiry Form
                </a>
            </div>
        </div>
    </section>
    <?php
}

// ============================================================
// ROUTING
// ============================================================

renderHeader($currentPage);

switch ($currentPage) {
    case PAGE_GIFT_BUNDLES:
        renderGiftBundles();
        break;
    case PAGE_CUSTOM_MERCHANDISE:
        renderCustomMerchandise();
        break;
    case PAGE_ABOUT_US:
        renderAboutUs();
        break;
    case PAGE_INQUIRE:
        renderInquire();
        break;
    case PAGE_HOME:
    default:
        renderHome();
        break;
}

renderFooter();
?>