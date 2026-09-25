<?php
session_start();
include 'includes/header.php';
?>

<!-- Load Google Font Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* ========== INTEGRATED THEME VARIABLES ========== */
#bundle-page {
  --primary: #0a2a44;
  --accent: #f4ba31;
  --bg: #f5f8fb;
  --white: #ffffff;
  --text: #1a2a44;
  --text-light: #5c6b7a;
  --radius: 20px;
  --shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  
  background: var(--primary); /* Applied globally to remove white gaps */
  color: var(--text);
  font-family: "Poppins", sans-serif;
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

/* ===== CUSTOM FONTS FROM YOUR CSS ===== */
@font-face {
  font-family: "GWbently";
  src: url("assets/fonts/GWbently.ttf") format("truetype");
  font-weight: normal; font-style: normal;
}
@font-face {
  font-family: "TNRM";
  src: url("assets/fonts/TNRM.ttf") format("truetype");
  font-weight: normal; font-style: normal;
}

#bundle-page .tnrm { font-family: "TNRM", "Georgia", serif; }
#bundle-page .gw-font { font-family: "GWbently", serif; color: var(--accent); font-weight: normal; }

/* ---------- GLOBAL TAG/LABEL ---------- */
#bundle-page .tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(246, 195, 74, 0.18); /* Translucent Amber */
  color: var(--accent);
  border-radius: 999px;
  padding: 10px 18px;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  font-size: 0.82rem;
  font-weight: 700;
  margin-bottom: 24px;
}

/* ---------- FRAME 1: HERO ---------- */
#bundle-page .hero-frame {
  background: linear-gradient(135deg, #0a2a44 0%, #1a4a6e 100%);
  color: white;
  padding: 150px 0 100px;
  display: flex;
  align-items: center;
  text-align: center;
  width: 100%;
}

#bundle-page .hero-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 20px;
}

#bundle-page .hero-frame h1 {
  font-family: "TNRM", serif;
  font-size: clamp(2.5rem, 6vw, 4.5rem);
  line-height: 1.1;
  margin-bottom: 20px;
}

#bundle-page .hero-frame h1 .gw-font {
  font-size: clamp(3.5rem, 10vw, 7rem);
  display: block;
  margin-top: -10px;
  font-weight: normal;
}

/* ---------- WIZARD STEPS ---------- */
.wizard-step {
  display: none; 
  padding: 80px 0;
  background: var(--primary); /* Blue background */
}

.wizard-step.active {
  display: block;
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.section-head {
  text-align: center;
  margin-bottom: 50px;
}

.section-head h2 {
  font-family: "TNRM", "Georgia", serif;
  font-size: clamp(2rem, 3.5vw, 3rem);
  color: var(--white); /* White text for contrast */
  font-weight: 400;
  line-height: 1.08;
}

.selection-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 24px;
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
}

/* ---------- CARDS ---------- */
.selection-card {
  background: var(--white);
  padding: 30px 20px;
  border-radius: var(--radius);
  text-align: center;
  border: 2px solid transparent;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  cursor: pointer;
  color: var(--text);
}

.icon-circle {
  width: 60px;
  height: 60px;
  margin: 0 auto 14px;
  background: rgba(245, 197, 66, 0.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  color: var(--accent);
  transition: all 0.3s ease;
}

.selection-card h3 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 6px;
  color: var(--text);
}

.selection-card p {
  font-size: 14px;
  color: var(--text-light);
  line-height: 1.6;
}

.selection-card:hover {
  border-color: var(--accent);
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}

.selection-card:hover .icon-circle {
  background: var(--accent);
  color: white;
}

/* ---------- BUTTONS ---------- */
.btn-highlight {
  background: var(--accent);
  color: var(--primary);
  border: 2px solid var(--accent);
  font-weight: 700;
  padding: 16px 40px;
  border-radius: 50px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  font-family: "Poppins", sans-serif;
  transition: 0.3s;
}

.btn-highlight:hover {
  background: #e6b83a;
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(244, 186, 49, 0.4);
}

/* ---------- RESULTS FOOTER ---------- */
.results-footer {
  margin-top: 60px;
  text-align: center;
  padding: 50px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: var(--radius);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.chip-container {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin: 25px 0;
  flex-wrap: wrap;
}

.chip {
  padding: 10px 22px;
  border: 1px solid var(--accent);
  border-radius: 50px;
  font-size: 13px;
  color: var(--accent);
  cursor: pointer;
  transition: 0.2s;
}

.chip:hover {
  background: var(--accent);
  color: var(--primary);
}

.contact-link {
    color: var(--accent);
    font-weight: 700;
    text-decoration: underline;
}
</style>

<main id="bundle-page">

  <!-- FRAME 1: HERO -->
  <section class="hero-frame" id="frame-1">
    <div class="hero-container">
      <span class="tag">GIFT BUNDLES — Find Your Gift</span>
      <h1>Thoughtful Gifts,<br><span class="gw-font">Made Easy.</span></h1>
      <p style="font-size: 1.2rem; margin-bottom: 30px; color: rgba(255, 255, 255, 0.9);">Personalized gift bundles made for the people and moments that matter.</p>
      <button class="btn-highlight" onclick="goToStep(2)">
        Explore Gift Bundles <i class="fas fa-arrow-right"></i>
      </button>
    </div>
  </section>

  <!-- FRAME 2: WHO -->
  <section class="wizard-step" id="frame-2">
    <div class="container">
      <div class="section-head">
        <span class="tag">Step 01</span>
        <h2>Who are you shopping for?</h2>
      </div>
      <div class="selection-grid">
        <div class="selection-card" onclick="selectOption('recipient', 'For Her', 3)">
          <div class="icon-circle"><i class="fas fa-venus"></i></div>
          <h3>For Her</h3>
        </div>
        <div class="selection-card" onclick="selectOption('recipient', 'For Him', 3)">
          <div class="icon-circle"><i class="fas fa-mars"></i></div>
          <h3>For Him</h3>
        </div>
        <div class="selection-card" onclick="selectOption('recipient', 'For Couples', 3)">
          <div class="icon-circle"><i class="fas fa-heart"></i></div>
          <h3>For Couples</h3>
        </div>
        <div class="selection-card" onclick="selectOption('recipient', 'For Friends', 3)">
          <div class="icon-circle"><i class="fas fa-user-friends"></i></div>
          <h3>For Friends</h3>
        </div>
        <div class="selection-card" onclick="selectOption('recipient', 'For Family', 3)">
          <div class="icon-circle"><i class="fas fa-home"></i></div>
          <h3>For Family</h3>
        </div>
        <div class="selection-card" onclick="selectOption('recipient', 'For Clients', 3)">
          <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
          <h3>For Clients & Employees</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- FRAME 3: OCCASION -->
  <section class="wizard-step" id="frame-3">
    <div class="container">
      <div class="section-head">
        <span class="tag">Step 02</span>
        <h2>What's the occasion?</h2>
      </div>
      <div class="selection-grid">
        <div class="selection-card" onclick="selectOption('occasion', 'Birthday', 4)">
          <div class="icon-circle"><i class="fas fa-birthday-cake"></i></div>
          <h3>Birthday</h3>
        </div>
        <div class="selection-card" onclick="selectOption('occasion', 'Graduation', 4)">
          <div class="icon-circle"><i class="fas fa-graduation-cap"></i></div>
          <h3>Graduation</h3>
        </div>
        <div class="selection-card" onclick="selectOption('occasion', 'Anniversary', 4)">
          <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
          <h3>Anniversary</h3>
        </div>
        <div class="selection-card" onclick="selectOption('occasion', 'Thank You', 4)">
          <div class="icon-circle"><i class="fas fa-hands-helping"></i></div>
          <h3>Thank You</h3>
        </div>
        <div class="selection-card" onclick="selectOption('occasion', 'Appreciation', 4)">
          <div class="icon-circle"><i class="fas fa-award"></i></div>
          <h3>Appreciation</h3>
        </div>
        <div class="selection-card" onclick="selectOption('occasion', 'Just Because', 4)">
          <div class="icon-circle"><i class="fas fa-gift"></i></div>
          <h3>Just Because</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- FRAME 4: BUDGET -->
  <section class="wizard-step" id="frame-4">
    <div class="container">
      <div class="section-head">
        <span class="tag">Step 03</span>
        <h2>What's your budget?</h2>
      </div>
      <div class="selection-grid">
        <div class="selection-card" onclick="selectOption('budget', '300-500', 5)">
          <div class="icon-circle"><i class="fas fa-leaf"></i></div>
          <h3>₱300–₱500</h3>
          <p>Small & thoughtful</p>
        </div>
        <div class="selection-card" onclick="selectOption('budget', '500-1000', 5)">
          <div class="icon-circle"><i class="fas fa-star"></i></div>
          <h3>₱500–₱1,000</h3>
          <p>Something special</p>
        </div>
        <div class="selection-card" onclick="selectOption('budget', '1000-1500', 5)">
          <div class="icon-circle"><i class="fas fa-box-open"></i></div>
          <h3>₱1,000–₱1,500</h3>
          <p>A complete gift</p>
        </div>
        <div class="selection-card" onclick="selectOption('budget', '1500+', 5)">
          <div class="icon-circle"><i class="fas fa-crown"></i></div>
          <h3>₱1,500+</h3>
          <p>Premium & personalized</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FRAME 5: RESULTS -->
  <section class="wizard-step" id="frame-5">
    <div class="container">
      <div class="section-head">
        <span class="tag">Personalized Results</span>
      <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
        <h2 id="match-title" style="display: inline-block; margin-bottom: 30px; text-align: center;">We found these gifts.</h2>

        <!-- Results Card -->
        <article id="result-card" class="selection-card" style="cursor: default; padding: 0; overflow: hidden; text-align: left; width: 100%; transition: max-width 0.3s ease;">
          <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=600" style="width:100%; height:160px; object-fit:cover;">
          <div style="padding: 25px;">
            <h3 style="margin-bottom: 5px;">Birthday Self-Care Bundle</h3>
            <p style="color: var(--primary); font-weight: 700; font-size: 1.4rem; margin-bottom: 10px;">₱799</p>
            <p style="font-size: 13px; color: #555; line-height: 1.5; margin-bottom: 0;">Personalized mug + pouch + treats + luxury gift packaging.</p>
          </div>
        </article>
      </div>

      <!-- NEW DESIGN INTEGRATED HERE -->
      <div style="margin-top: 80px; text-align: center;">
        
        <!-- Optional: Keep the "Change Filter" chips above the help box -->
        <!-- Optional: Keep the "Change Filter" chips above the help box -->
        <p style="color: var(--text-light); margin-bottom: 15px; font-size: 0.9rem;">Not quite what you're looking for?</p>
        <div class="chip-container" style="margin-bottom: 50px;">
          <div class="chip" onclick="goToStep(2)">Change Recipient</div>
          <div class="chip" onclick="goToStep(3)">Change Occasion</div>
          <div class="chip" onclick="goToStep(4)">Change Budget</div>
        </div>

        <!-- THE REQUESTED DESIGN BOX -->
        <div style="max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.05); padding: 60px; border-radius: var(--radius); border: 1px solid rgba(255,255,255,0.1);">
          <h2 class="tnrm" style="font-size: 2.5rem; margin-bottom: 20px; color: white;">Not sure what would work for you?</h2>
          <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 35px;">
            You don't have to figure it all out yourself. Tell us your budget, quantity, and purpose—we'll help you find the right option.
          </p>
              <a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank" rel="noopener" class="btn btn-secondary btn-highlight">
                <i class="fab fa-facebook"></i> Visit our fb page
            </a>
        </div>
      </div>

    </div>
  </section>

</main>

<script>
let selections = { recipient: '', occasion: '', budget: '' };

function goToStep(step) {
  // Completely hide Hero past step 1
  const hero = document.getElementById('frame-1');
  hero.style.display = (step === 1) ? 'flex' : 'none';

  // Toggle active class for wizard steps
  document.querySelectorAll('.wizard-step').forEach(section => {
    section.classList.remove('active');
  });

  if (step > 1) {
    const target = document.getElementById('frame-' + step);
    target.classList.add('active');
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function alignCardToTitle() {
  const title = document.getElementById('match-title');
  const card = document.getElementById('result-card');
  if (title && card) {
    card.style.maxWidth = title.getBoundingClientRect().width + 'px';
  }
}

function selectOption(category, value, nextStep) {
  selections[category] = value;
  
  if (nextStep === 5) {
    const title = document.getElementById('match-title');
    title.innerHTML = `We found these gifts <span style="color: var(--accent);">${selections.recipient.toLowerCase()}</span>.`;
    
    // Align card to perfectly match the title's width
    setTimeout(alignCardToTitle, 10);
    window.addEventListener('resize', alignCardToTitle);
  }
  
  goToStep(nextStep);
}
</script>

<?php include 'includes/footer.php'; ?>