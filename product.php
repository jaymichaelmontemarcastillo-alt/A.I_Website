
<?php
session_start();
// Database connection removed as requested
include 'includes/header.php';
?>

<main id="merch-page">

    <!-- FRAME 1: HERO -->
    <section class="hero">
        <div class="hero-inner">
            <h2 class="reveal-init reveal-fade-in-up reveal-delay-1">Custom <span class="gw-font" style="font-size: 1.55em;">Merchandise</span></h2>
            
            <p class="hero-sub reveal-init reveal-fade-in-up reveal-delay-2 tnrm gw-font">Turn your idea into something tangible</p>

            <div class="hero-buttons reveal-init reveal-fade-in-up reveal-delay-3">
                <a href="index.php#inquiry-section" id="start-order-btn" class="btn btn-secondary btn-highlight">Explore Items</a>
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
                <div class="problem-card reveal-init reveal-fade-in-up" role="button" tabindex="0" data-product-type="Wearables" data-product-details="T-shirts, Uniforms, and Tote Bags">
                    <div class="icon-circle"><i class="fas fa-tshirt"></i></div>
                    <h3>Wearables</h3>
                    <p>T-shirts • Uniforms • Tote Bags</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-1" role="button" tabindex="0" data-product-type="Drinkware" data-product-details="Mugs, Tumblers, and Bottles">
                    <div class="icon-circle"><i class="fas fa-mug-hot"></i></div>
                    <h3>Drinkware</h3>
                    <p>Mugs • Tumblers • Bottles</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-2" role="button" tabindex="0" data-product-type="Giveaways" data-product-details="Keychains, Pins, and Small Items">
                    <div class="icon-circle"><i class="fas fa-icons"></i></div>
                    <h3>Giveaways</h3>
                    <p>Keychains • Pins • Small Items</p>
                </div>
                <div class="problem-card reveal-init reveal-fade-in-up reveal-delay-3" role="button" tabindex="0" data-product-type="Print & Branding" data-product-details="Stickers, Labels, Tags, and Packaging">
                    <div class="icon-circle"><i class="fas fa-box"></i></div>
                    <h3>Print & Branding</h3>
                    <p>Stickers • Labels • Tags • Packaging</p>
                </div>
            </div>
            <div id="product-detail" class="product-detail" aria-live="polite" hidden>
                <p class="product-detail-label">Selected product type</p>
                <h3 id="product-detail-title"></h3>
                <p id="product-detail-description"></p>
                <div id="product-image-grid" class="product-image-grid"></div>
            </div>

        </div>
    </section>

    <!-- FRAME 3: CHOOSE WHAT YOU NEED 
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
-->
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



    <!-- FRAME 6: NOT SURE 
    <section class="section impact" style="text-align: center; padding: 120px 20px;">
        <div class="container reveal-init reveal-fade-in-up">
            <h2 class="tnrm" style="font-size: 2.5rem; margin-bottom: 20px;">Not sure what would work for you?</h2>
            <p class="impact-text" style="font-size: 1.1rem; margin-bottom: 35px;">
                You don't have to figure it all out yourself. Tell us your budget, quantity, and purpose—we'll help you find the right option.
            </p>
            <a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank" rel="noopener" class="btn btn-secondary btn-highlight">
                <i class="fab fa-facebook"></i> Visit our fb page
            </a>
        </div>
    </section>
-->
</main>

<?php
    $base_dir = 'assets/Product-Images/';
    $categories = [
        'Wearables' => 'wearables',
        'Drinkware' => 'drinkware',
        'Giveaways' => 'giveaways',
        'Print & Branding' => 'print&branding'
    ];

    $productImagesData = [];
    foreach ($categories as $key => $folder) {
        $path = $base_dir . $folder;
        $images = [];
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $images[] = $file;
                }
            }
        }
        $productImagesData[$key] = $images;
    }
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('#merch-page .problem-card');
    const productDetail = document.getElementById('product-detail');
    const productDetailTitle = document.getElementById('product-detail-title');
    const productDetailDescription = document.getElementById('product-detail-description');
    const productImageGrid = document.getElementById('product-image-grid');
    
    const productImages = <?php echo json_encode($productImagesData); ?>;

    /* ===== Per-category layout maps =====
       Each entry: { file, col, row, colSpan, rowSpan }
       col/row are 1-indexed CSS grid positions.
    */
    const categoryLayouts = {
        'Wearables': [
            { file: 'wearable2.jpg',  col: 1, row: 1, colSpan: 1, rowSpan: 1, contain: true },
            { file: 'wearable3.JPEG', col: 2, row: 1, colSpan: 1, rowSpan: 1, contain: true },
            { file: 'wearable6.jpg',  col: 3, row: 1, colSpan: 1, rowSpan: 1, contain: true },
            { file: 'wearable5.jpg',  col: 1, row: 2, colSpan: 1, rowSpan: 1, contain: true },
            { file: 'wearable4.jpg',  col: 2, row: 2, colSpan: 1, rowSpan: 1, contain: true },
            { file: 'wearable1.jpg',  col: 3, row: 2, colSpan: 1, rowSpan: 1, contain: true }
        ],
        'Giveaways': [
            { file: 'giveaway4.jpg', col: 1, row: 1, contain: true },
            { file: 'giveaway1.jpg', col: 2, row: 1, contain: true },
            { file: 'giveaway2.jpg', col: 3, row: 1, contain: true },
            { file: 'giveaway3.jpg', col: 4, row: 1, contain: true }
        ],
        'Drinkware': [
            { file: 'drinkware7.JPEG', col: 1, row: 1, colSpan: 1, rowSpan: 2 },
            { file: 'drinkware5.jpg',  col: 2, row: 1, colSpan: 1, rowSpan: 1, position: 'bottom center' },
            { file: 'drinkware4.jpg',  col: 3, row: 1, colSpan: 1, rowSpan: 2 },
            { file: 'drinkware2.JPEG', col: 2, row: 2, colSpan: 1, rowSpan: 1 },
            { file: 'drinkware6.jpg',  col: 1, row: 3, colSpan: 1, rowSpan: 1.5 },
            { file: 'drinkware3.jpg',  col: 3, row: 3, colSpan: 1, rowSpan: 1.5 },
            { file: 'drinkware1.jpg',  col: 3, row: 3, colSpan: 1, rowSpan: 1.5 }
        ],
        'Print & Branding': [
            { file: 'pb2.jpg',  col: 1, row: 1, colSpan: 1, rowSpan: 2 }, // Glass Award (Left Tall)
            { file: 'pb1.jpg',  col: 2, row: 1, colSpan: 1, rowSpan: 1 }, // Pink Ribbons (Center Top)
            { file: 'pb3.jpg',  col: 3, row: 1, colSpan: 1, rowSpan: 2 }, // Certificates (Right Tall)
            { file: 'pb4.jpg',  col: 2, row: 2, colSpan: 1, rowSpan: 2 }, // Notebooks (Center Tall)
            { file: 'pb5.jpg',  col: 1, row: 3, colSpan: 1, rowSpan: 1 }, // Pens (Left Bottom)
            { file: 'pb6.JPEG', col: 3, row: 3, colSpan: 1, rowSpan: 1 }  // Umbrellas (Right Bottom)
        ]
    };

    function showProductDetails(card) {
        const productType = card.dataset.productType;
        const imageFolder = productType.toLowerCase().replace(' & ', '&').replace(' ', '');
        productDetailTitle.textContent = productType;
        productDetailDescription.textContent = card.dataset.productDetails;
        productImageGrid.innerHTML = '';

        const layout = categoryLayouts[productType];

        if (layout) {
            /* ---- Use explicit Masonry layout ---- */
            productImageGrid.className = 'product-image-grid grid-masonry';
            
            // Create necessary column containers dynamically
            let maxCol = 3;
            layout.forEach(item => { if (item.col > maxCol) maxCol = item.col; });
            const cols = [];
            for (let i = 0; i < maxCol; i++) {
                const col = document.createElement('div');
                col.className = 'masonry-col';
                cols.push(col);
            }

            layout.forEach(function(item) {
                const imagePath = 'assets/Product-Images/' + imageFolder + '/' + item.file;
                const figure = document.createElement('figure');
                figure.className = 'product-image-tile';

                const img = document.createElement('img');
                img.src = imagePath;
                img.alt = productType + ' example';
                img.loading = 'lazy';
                img.tabIndex = 0;
                
                if (item.contain) {
                    img.style.objectFit = 'contain';
                }
                if (item.position) {
                    img.style.objectPosition = item.position;
                }

                img.addEventListener('error', function() {
                    figure.remove();
                });

                figure.appendChild(img);
                
                // Append to the specific column
                const colIndex = item.col - 1;
                if (cols[colIndex]) {
                    cols[colIndex].appendChild(figure);
                }
            });

            // Append columns to grid
            cols.forEach(col => {
                if (col.hasChildNodes()) {
                    productImageGrid.appendChild(col);
                }
            });
        } else {
            /* ---- Auto layout for other categories ---- */
            productImageGrid.classList.add('grid-auto');
            productImageGrid.classList.remove('grid-figma');

            productImages[productType].forEach(function(imageName) {
                const imagePath = 'assets/Product-Images/' + imageFolder + '/' + imageName;
                const figure = document.createElement('figure');
                figure.className = 'product-image-tile';

                const img = document.createElement('img');
                img.src = imagePath;
                img.alt = productType + ' example';
                img.loading = 'lazy';
                img.tabIndex = 0;

                function setOrientation() {
                    if (img.naturalWidth && img.naturalHeight) {
                        const ratio = img.naturalWidth / img.naturalHeight;
                        figure.classList.remove('tile-landscape', 'tile-portrait');
                        if (ratio >= 1) {
                            figure.classList.add('tile-landscape');
                        } else {
                            figure.classList.add('tile-portrait');
                        }
                    }
                }

                img.addEventListener('load', setOrientation);
                img.addEventListener('error', function() {
                    figure.remove();
                });

                figure.appendChild(img);
                productImageGrid.appendChild(figure);

                if (img.complete) {
                    setOrientation();
                }
            });
        }

        productDetail.hidden = false;
        productCards.forEach(productCard => productCard.classList.remove('is-selected'));
        card.classList.add('is-selected');
    }

    productCards.forEach(card => {
        card.addEventListener('click', function() {
            showProductDetails(card);
        });

        card.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                showProductDetails(card);
            }
        });
    });

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