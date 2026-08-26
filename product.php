<?php
session_start();
// Database connection removed as requested
include 'includes/header.php';
?>

<main id="merch-page">

    <!-- FRAME 1: HERO -->
    <section class="hero">
        <div class="hero-inner">
            <h2 class="reveal-init reveal-fade-in-up reveal-delay-1">Custom <span class="gw-font">Merch</span></h2>
            
            <p class="hero-sub reveal-init reveal-fade-in-up reveal-delay-2 tnrm">Made for Your Purpose</p>

            <div class="hero-buttons reveal-init reveal-fade-in-up reveal-delay-3">
                <a href="#choose-need" class="btn btn-secondary btn-highlight">Start Your Custom Order</a>
            </div>
        </div>
    </section>

    <!-- FRAME 2: WHAT WE OFFER -->
    <section class="section problem">
        <div class="container">
            <div class="section-head reveal-init reveal-fade-in-up">
                <h2>What We Offer</h2>
            </div>
            <div class="problem-grid">
                <div class="problem-card reveal-init reveal-fade-in-up">
                    <div class="icon-circle"><i class="fas fa-tshirt"></i></div>
                    <h3>Wearables</h3>
                    <p>T-shirts • Uniforms • Tote Bags</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-1">
                    <div class="icon-circle"><i class="fas fa-mug-hot"></i></div>
                    <h3>Drinkware</h3>
                    <p>Mugs • Tumblers • Bottles</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-2">
                    <div class="icon-circle"><i class="fas fa-icons"></i></div>
                    <h3>Giveaways</h3>
                    <p>Keychains • Pins • Small Items</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-3">
                    <div class="icon-circle"><i class="fas fa-box"></i></div>
                    <h3>Print & Branding</h3>
                    <p>Stickers • Labels • Tags • Packaging</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 50px;" class="reveal-init reveal-zoom-in">
                <a href="#previous-works" class="btn btn-highlight">See What We Can Make</a>
            </div>
        </div>
    </section>

    <!-- FRAME 3: CHOOSE WHAT YOU NEED -->
    <section id="choose-need" class="section solution">
        <div class="container">
            <div class="section-head reveal-init reveal-fade-in-up">
                <h2>Choose What You Need</h2>
                <p class="sub-text">What are you looking for today?</p>
            </div>
            <div class="solution-grid">
                <div class="solution-card reveal-init reveal-fade-in-up" onclick="window.location.href='contact.php?type=teams'" style="cursor: pointer;">
                    <div class="solution-icon"><i class="fas fa-users"></i></div>
                    <h3>For Teams & Organizations</h3>
                    <p>Uniforms, shirts, IDs, and team merchandise</p>
                </div>
                <div class="solution-card reveal-init reveal-fade-in-up reveal-delay-1" onclick="window.location.href='contact.php?type=business'" style="cursor: pointer;">
                    <div class="solution-icon"><i class="fas fa-briefcase"></i></div>
                    <h3>For Businesses</h3>
                    <p>Branded merch, packaging, stickers, and promotional items</p>
                </div>
                <div class="solution-card reveal-init reveal-fade-in-up reveal-delay-2" onclick="window.location.href='contact.php?type=events'" style="cursor: pointer;">
                    <div class="solution-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3>For Events & Giveaways</h3>
                    <p>Custom souvenirs and guest giveaways</p>
                </div>
                <div class="solution-card reveal-init reveal-fade-in-up reveal-delay-3" onclick="window.location.href='contact.php?type=custom'" style="cursor: pointer;">
                    <div class="solution-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3>Something Else</h3>
                    <p>Tell us what you have in mind.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FRAME 4: HOW IT WORKS -->
    <section class="section steps">
        <div class="container">
            <div class="section-head reveal-init reveal-fade-in-up">        
                <h2>How It Works</h2>
            </div>
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                <div class="timeline-step reveal-init reveal-fade-in-up">
                    <div class="timeline-card">
                        <div class="step-icon-circle"><i class="fas fa-comment-dots"></i></div>
                        <div class="step-content">
                            <div class="step-heading-row">
                                <div class="step-badge"><span class="badge-num">1</span></div>
                                <h3>Tell us what you need</h3>
                            </div>
                            <div class="step-divider"></div>
                            <p>Share your product, quantity, design, and deadline.</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-step reveal-init reveal-fade-in-up reveal-delay-1">
                    <div class="timeline-card">
                        <div class="step-icon-circle"><i class="fas fa-layer-group"></i></div>
                        <div class="step-content">
                            <div class="step-heading-row">
                                <div class="step-badge"><span class="badge-num">2</span></div>
                                <h3>We prepare options</h3>
                            </div>
                            <div class="step-divider"></div>
                            <p>We'll recommend the best materials and customization method.</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-step reveal-init reveal-fade-in-up reveal-delay-2">
                    <div class="timeline-card">
                        <div class="step-icon-circle"><i class="fas fa-check-circle"></i></div>
                        <div class="step-content">
                            <div class="step-heading-row">
                                <div class="step-badge"><span class="badge-num">3</span></div>
                                <h3>Approve & produce</h3>
                            </div>
                            <div class="step-divider"></div>
                            <p>Once everything is confirmed, we handle the rest.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FRAME 5: GALLERY -->
    <section id="previous-works" class="section gallery-section">
        <div class="container">
            <div class="section-head light reveal-init reveal-fade-in-up">
                <h2 style="margin-bottom: 15px;">See What We've Made</h2>
                <div class="impact-content reveal-init reveal-fade-in">
                   <p class="impact-text">A few of the custom projects we've created for our clients.</p>
                </div>
            </div>
            <div class="gallery-grid">
              
                <div class="gallery-item gallery-item-large reveal-init reveal-fade-in-up">
                    <img src="assets/images/products/product10.jpg" alt="Premium custom gift set">
                </div>
               
                <div class="gallery-item reveal-init reveal-fade-in-up reveal-delay-1">
                    <img src="assets/images/products/product9.jpg" alt="Corporate merchandise">
                </div>
               
                <div class="gallery-item gallery-item-tall reveal-init reveal-fade-in-up reveal-delay-2">
                    <img src="assets/images/products/product8.jpg" alt="Curated corporate bundle">
                </div>
           
                <div class="gallery-item reveal-init reveal-fade-in-up reveal-delay-1">
                    <img src="assets/images/products/product3.jpg" alt="Event giveaway swag">
                </div>
               
                <div class="gallery-item reveal-init reveal-fade-in-up">
                    <img src="assets/images/products/product4.png" alt="Branded swag kit">
                </div>
             
                <div class="gallery-item reveal-init reveal-fade-in-up reveal-delay-1">
                    <img src="assets/images/products/product2.jpeg" alt="Welcome onboarding kit">
                </div>
           
                <div class="gallery-item gallery-item-wide reveal-init reveal-fade-in-up reveal-delay-2">
                    <img src="assets/images/products/product11.jpg" alt="Appreciation gift set">
                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;" class="reveal-init reveal-fade-in-up">
                <a href="contact.php" class="btn btn-secondary btn-highlight">Want Something Similar?</a>
            </div>
        </div>
    </section>

    <!-- FRAME 6: NOT SURE -->
    <section class="section impact" style="text-align: center; padding: 120px 20px;">
        <div class="container reveal-init reveal-fade-in-up">
            <h2 class="tnrm" style="font-size: 2.5rem; margin-bottom: 20px;">Not sure what would work for you?</h2>
            <p class="impact-text" style="font-size: 1.1rem; margin-bottom: 35px;">
                You don't have to figure it all out yourself. Tell us your budget, quantity, and purpose—we'll help you find the right option.
            </p>
            <a href="contact.php" class="btn btn-secondary btn-highlight">
                <i class="fas fa-comment-dots"></i> Message Us
            </a>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-init').forEach(element => {
        revealObserver.observe(element);
    });
});
</script>

<?php include 'includes/footer.php'; ?>