<?php
session_start();
require_once 'connect/config.php';
include 'includes/header.php';

$pdo = getDBConnection();
if (!$pdo) {
    die('Database connection failed.');
}

// Fetch ONLY bundles
$bundles = getBundles($pdo);   // uses type = 'bundle'

// For hero, use the first bundle if available
$heroBundle = !empty($bundles) ? $bundles[0] : null;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..700;1,14..32,300..700&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* ========== ISOLATED BUNDLE PAGE STYLES ========== */
#bundle-page {
  --bp-deep-navy: #0a1a3a;
  --bp-royal-blue: #0B3D91;
  --bp-warm-amber: #f4a81d;
  --bp-pure-white: #ffffff;
  --bp-text-muted: #5a6275;
  --bp-shadow-sm: 0 2px 8px rgba(10,26,58,0.06);
  --bp-shadow-xl: 0 30px 70px rgba(10,26,58,0.20);
  --bp-radius-lg: 22px;
  --bp-transition: 0.35s cubic-bezier(0.25,0.46,0.45,0.94);
  --bp-font-body: 'Plus Jakarta Sans', 'Inter', sans-serif;
  --bp-font-display: 'Playfair Display', 'Georgia', serif;
  margin: 0; padding: 0;
  font-family: var(--bp-font-body);
  color: var(--bp-deep-navy);
  background: #fafbfd;
  line-height: 1.6;
}

#bundle-page *,
#bundle-page *::before,
#bundle-page *::after {
  box-sizing: border-box; margin: 0; padding: 0;
}

/* ---------- HERO ---------- */
#bundle-page .bundle-hero {
  position: relative; min-height: 580px;
  display: flex; align-items: center;
  /* gradient will be overridden by inline background-image but we keep it as fallback */
  background: linear-gradient(160deg, var(--bp-deep-navy), var(--bp-royal-blue));
  overflow: hidden; isolation: isolate;
}

/* Background image container (set via JS) */
#bundle-page .hero-bg-image {
  position: absolute; inset: 0;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  z-index: 0;
  transition: background-image 0.5s ease;
}
/* If no image, show a subtle pattern or just let gradient show */
#bundle-page .hero-bg-image:empty {
  display: none;
}

#bundle-page .bundle-hero::before {
  content: ""; position: absolute; inset: 0;
  background: linear-gradient(160deg, rgba(10,26,58,0.88) 0%, rgba(11,61,145,0.78) 40%, rgba(26,92,200,0.65) 70%, rgba(10,26,58,0.82) 100%);
  z-index: 1;
}
#bundle-page .bundle-hero::after {
  content: ""; position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.7' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  background-size: 180px 180px; z-index: 1; pointer-events: none;
}

#bundle-page .hero-decor { position: absolute; z-index: 2; pointer-events: none; }
#bundle-page .hero-decor-circle {
  position: absolute; border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.10);
  animation: bp-float 14s ease-in-out infinite;
}
#bundle-page .hero-decor-circle:nth-child(1) { width:280px; height:280px; top:-100px; right:-60px; }
#bundle-page .hero-decor-circle:nth-child(2) { width:180px; height:180px; bottom:-50px; left:-40px; animation-delay:-5s; border-color:rgba(244,168,29,0.15); }
#bundle-page .hero-decor-circle:nth-child(3) { width:120px; height:120px; top:30%; right:15%; animation-delay:-9s; border-color:rgba(255,255,255,0.06); }
@keyframes bp-float {
  0%,100%{transform:translateY(0) rotate(0deg)}
  25%{transform:translateY(-25px) rotate(3deg)}
  50%{transform:translateY(-10px) rotate(-2deg)}
  75%{transform:translateY(-30px) rotate(1deg)}
}

#bundle-page .container {
  position: relative; z-index: 3;
  max-width: 1200px; margin: 0 auto;
  padding: 0 40px;
  display: flex;
  align-items: left;
}

#bundle-page .hero-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  max-width: 600px;
  width: 100%;
  margin: 0 !important;
  text-align: left !important;
  animation: bp-fadeIn 0.8s ease-out;
}

@keyframes bp-fadeIn { 
  from { opacity:0; transform:translateY(30px); } 
  to { opacity:1; transform:translateY(0); } 
}

#bundle-page .hero-badge {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,0.12); backdrop-filter:blur(12px);
  border:1px solid rgba(255,255,255,0.2); color:#fff;
  padding:8px 18px; border-radius:50px; font-size:0.85rem; font-weight:600;
  letter-spacing:0.04em; text-transform:uppercase; margin-bottom:20px;
}
#bundle-page .hero-badge .dot {
  width:8px; height:8px; border-radius:50%; background:var(--bp-warm-amber);
  animation:bp-pulse 1.8s infinite;
}
@keyframes bp-pulse { 
  0%,100%{opacity:1;transform:scale(1)} 
  50%{opacity:0.5;transform:scale(1.6)} 
}

#bundle-page .bundle-hero h1 {
  font-family:var(--bp-font-display); font-size:clamp(36px,5vw,64px);
  font-weight:700; line-height:1.08; margin-bottom:16px; color:#fff;
}
#bundle-page .hero-desc {
  color:rgba(255,255,255,0.88); font-size:1.1rem; line-height:1.7;
  margin-bottom:28px; max-width:500px;
}
#bundle-page .hero-ctas { display:flex; gap:14px; flex-wrap:wrap; }
#bundle-page .btn-hero {
  display:inline-flex; align-items:center; gap:10px;
  background:var(--bp-warm-amber); color:var(--bp-deep-navy);
  padding:14px 28px; border-radius:50px; font-weight:700; text-decoration:none;
  transition:all var(--bp-transition); box-shadow:0 8px 28px rgba(244,168,29,0.35);
}
#bundle-page .btn-hero:hover { background:#fbbf3b; transform:translateY(-3px); box-shadow:0 14px 36px rgba(244,168,29,0.5); }
#bundle-page .btn-hero-outline {
  display:inline-flex; align-items:center; gap:10px;
  background:transparent; color:#fff; padding:14px 28px; border-radius:50px;
  font-weight:600; text-decoration:none; border:2px solid rgba(255,255,255,0.35);
  transition:all var(--bp-transition); backdrop-filter:blur(4px);
}
#bundle-page .btn-hero-outline:hover { background:rgba(255,255,255,0.1); border-color:rgba(255,255,255,0.6); transform:translateY(-3px); }

/* ---------- GRID SECTION ---------- */
#bundle-page .bundles-section {
  max-width:1200px; margin: -50px auto 60px; padding:0 28px; position:relative; z-index:5;
}
#bundle-page .bundles-section-header { text-align:center; margin-bottom:32px; }
#bundle-page .section-label {
  display:inline-block; font-size:0.8rem; font-weight:700; text-transform:uppercase;
  letter-spacing:0.08em; color:var(--bp-royal-blue); background:#e8f2ff;
  padding:6px 16px; border-radius:50px; margin-bottom:10px;
}
#bundle-page .bundles-section-header h2 {
  font-family:var(--bp-font-display); font-size:clamp(24px,3.5vw,38px); color:var(--bp-deep-navy); font-weight:700;
}

/* ---------- PRODUCT GRID ---------- */
#bundle-page .bundle-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

#bundle-page .promo-bundle-card {
  background: var(--bp-pure-white);
  border-radius: var(--bp-radius-lg);
  box-shadow: var(--bp-shadow-sm);
  overflow: hidden;
  border: 1px solid #eef1f7;
  transition: all var(--bp-transition);
  display: flex;
  flex-direction: column;
  height: 530px;
  position: relative;
}
#bundle-page .promo-bundle-card:hover {
  transform: translateY(-10px);
  box-shadow: var(--bp-shadow-xl);
  border-color: #dbe3f5;
}
#bundle-page .promo-bundle-link {
  display: flex; flex-direction: column; flex:1;
  width:100%; border:none; background:transparent;
  padding:0; text-align:left; cursor:pointer;
}
#bundle-page .gift-img {
  height: 240px; overflow: hidden; position: relative;
  background: #eef1f7;
  display: flex; align-items: center; justify-content: center;
}
#bundle-page .gift-img img {
  width:100%; height:100%; object-fit:cover;
  transition: transform 0.6s cubic-bezier(0.25,0.46,0.45,0.94);
}
#bundle-page .gift-img .no-image-icon {
  font-size: 3.5rem;
  color: #b0c0d0;
}
#bundle-page .promo-bundle-card:hover .gift-img img { transform: scale(1.08); }
#bundle-page .badge {
  position:absolute; top:14px; left:14px; background:var(--bp-warm-amber);
  color:var(--bp-deep-navy); padding:7px 14px; border-radius:50px;
  font-weight:700; font-size:0.78rem; z-index:2;
  box-shadow:0 4px 14px rgba(244,168,29,0.4); text-transform:uppercase;
}
#bundle-page .gift-info {
  padding:18px 20px 22px; display:flex; flex-direction:column; gap:10px; flex:1;
}
#bundle-page .gift-info h4 {
  font-family:var(--bp-font-display); font-weight:700; font-size:1.25rem; color:var(--bp-deep-navy);
}
#bundle-page .card-divider {
  width:40px; height:3px; background:var(--bp-warm-amber); border-radius:2px;
}
#bundle-page .gift-info p {
  color:var(--bp-text-muted);
  font-size: 0.78rem;
  line-height: 1.45;
  flex:1;
  overflow: hidden;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 1100px) {
  #bundle-page .bundle-grid { grid-template-columns: repeat(3, 1fr); }
  #bundle-page .promo-bundle-card { height: 500px; }
}
@media (max-width: 800px) {
  #bundle-page .bundle-grid { grid-template-columns: repeat(2, 1fr); }
  #bundle-page .promo-bundle-card { height: 480px; }
  #bundle-page .container { flex-direction: column; align-items: flex-start; padding:0 24px; }
  #bundle-page .bundle-hero { min-height: 440px; }
}
@media (max-width: 500px) {
  #bundle-page .bundle-grid { grid-template-columns: 1fr; }
  #bundle-page .promo-bundle-card { height: auto; min-height: 430px; }
  #bundle-page .bundle-hero h1 { font-size:28px; }
  #bundle-page .hero-desc { font-size:0.9rem; }
}
</style>

<main id="bundle-page">
  <header class="bundle-hero" id="bundle-hero">
    <!-- Background image container (will be set via JS) -->
    <div class="hero-bg-image" id="heroBgImage" 
         style="background-image: url('<?php 
           if ($heroBundle && !empty($heroBundle['image_path'])) {
             echo htmlspecialchars($heroBundle['image_path'], ENT_QUOTES);
           }
         ?>');">
    </div>

    <div class="hero-decor" aria-hidden="true">
      <div class="hero-decor-circle"></div>
      <div class="hero-decor-circle"></div>
      <div class="hero-decor-circle"></div>
    </div>
    <div class="container">
      <div class="hero-content">
        <span class="hero-badge"><span class="dot"></span> Featured Bundle</span>
        <h1 id="hero-title"><?php echo $heroBundle ? htmlspecialchars($heroBundle['name'], ENT_QUOTES) : 'No Bundles Yet'; ?></h1>
        <p class="hero-desc" id="hero-description"><?php echo $heroBundle ? htmlspecialchars($heroBundle['description'], ENT_QUOTES) : 'Check back later for exciting bundles.'; ?></p>
        <div class="hero-ctas">
          <a href="#bundles" class="btn-hero"><i class="fas fa-gift"></i> Explore Bundles</a>
          <a href="product.php" class="btn-hero-outline">View Merchandise <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </header>

  <section id="bundles" class="bundles-section">
    <div class="bundles-section-header">
      <span class="section-label">Our Collection</span>
      <h2>Promo Bundles</h2>
    </div>
    
    <div class="bundle-grid">
      <?php if (empty($bundles)): ?>
        <p style="grid-column:1/-1; text-align:center; padding:40px 0;">No bundles available at the moment.</p>
      <?php else: ?>
        <?php foreach ($bundles as $bundle): ?>
          <article class="promo-bundle-card" 
                   data-name="<?php echo htmlspecialchars($bundle['name'], ENT_QUOTES); ?>"
                   data-description="<?php echo htmlspecialchars($bundle['description'], ENT_QUOTES); ?>"
                   data-image="<?php echo htmlspecialchars($bundle['image_path'], ENT_QUOTES); ?>">
            <button type="button" class="promo-bundle-link bundle-select">
              <div class="gift-img">
                <span class="badge"><i class="fas fa-star"></i> Bundle</span>
                <?php if (!empty($bundle['image_path'])): ?>
                  <img src="<?php echo htmlspecialchars($bundle['image_path'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($bundle['name'], ENT_QUOTES); ?>" loading="lazy">
                <?php else: ?>
                  <div class="no-image-icon"><i class="fas fa-box-open"></i></div>
                <?php endif; ?>
              </div>
              <div class="gift-info">
                <h4><?php echo htmlspecialchars($bundle['name'], ENT_QUOTES); ?></h4>
                <div class="card-divider"></div>
                <p><?php echo htmlspecialchars($bundle['description'], ENT_QUOTES); ?></p>
              </div>
            </button>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
// Update hero when a bundle card is clicked
document.querySelectorAll('#bundle-page .bundle-select').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.promo-bundle-card');
    if (!card) return;
    const heroBg = document.getElementById('heroBgImage');
    const heroTitle = document.getElementById('hero-title');
    const heroDesc = document.getElementById('hero-description');
    
    // Update text
    heroTitle.textContent = card.dataset.name;
    heroDesc.textContent = card.dataset.description;
    
    // Update background image
    const imageUrl = card.dataset.image;
    if (imageUrl && imageUrl.trim() !== '') {
      heroBg.style.backgroundImage = 'url("' + imageUrl + '")';
    } else {
      // Optionally remove background to show gradient fallback
      heroBg.style.backgroundImage = 'none';
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});
</script>