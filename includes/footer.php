<style>
    /* ----- FOOTER STYLES (fully separated) ----- */
    .ai-footer {
      background: #0a1f30;
      color: rgba(255, 255, 255, 0.7);
      padding: 40px 0 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      font-family: 'Poppins', sans-serif;
    }

    .ai-footer__inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* --- main grid: 3 columns (brand, quick links, support+details) --- */
    .ai-footer__grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1.2fr;
      gap: 32px 40px;
    }

    /* brand column */
    .ai-footer__brand {
      display: flex;
      flex-direction: column;
    }
    .ai-footer__brand-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }
    .ai-footer__logo-wrapper {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      overflow: hidden;
      background: #f5c542;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .ai-footer__logo-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .ai-footer__brand-title {
      color: #fff;
      font-size: 18px;
      font-weight: 600;
      margin: 0;
      line-height: 1.2;
    }
    .ai-footer__brand-title span {
      color: #f5c542;
    }
    .ai-footer__desc {
      font-size: 14px;
      max-width: 280px;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.6);
      margin-top: 4px;
      margin-bottom: 16px;
    }

    /* social icons */
    .ai-footer__social {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }
    .ai-footer__social a {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.06);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, 0.6);
      transition: all 0.2s ease;
      text-decoration: none;
      font-size: 16px;
    }
    .ai-footer__social a:hover {
      background: #f5c542;
      color: #0a1f30;
      transform: translateY(-2px);
    }

    /* quick links & support columns */
    .ai-footer__col h5 {
      color: #fff;
      font-weight: 600;
      font-size: 16px;
      margin: 0 0 16px 0;
      letter-spacing: 0.3px;
    }
    .ai-footer__list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .ai-footer__list li {
      margin-bottom: 10px;
    }
    .ai-footer__list a {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.6);
      text-decoration: none;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .ai-footer__list a:hover {
      color: #f5c542;
      transform: translateX(4px);
    }

    /* --- new: contact details block (inside support col) --- */
    
    .ai-footer__contact-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13px;
      color: rgba(255, 255, 255, 0.6);
      margin-bottom: 10px;
      line-height: 1.5;
    }
    .ai-footer__contact-item i {
      width: 18px;
      font-size: 14px;
      color: #f5c542;
      margin-top: 2px;
      flex-shrink: 0;
      text-align: center;
    }
    .ai-footer__contact-item a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: color 0.2s;
      word-break: break-word;
    }
    .ai-footer__contact-item a:hover {
      color: #f5c542;
    }
    .ai-footer__contact-item .fb-link {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      flex-wrap: wrap;
    }

    /* --- bottom bar --- */
    .ai-footer__bottom {
      max-width: 1200px;
      margin: 32px auto 0;
      padding: 24px 24px 0;
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      text-align: center;
      font-size: 13px;
      color: rgba(255, 255, 255, 0.3);
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 6px 20px;
    }
    .ai-footer__bottom .heart {
      color: #f5c542;
      font-size: 12px;
      margin: 0 2px;
    }

    /* ----- MOBILE RESPONSIVENESS ----- */
    @media screen and (max-width: 992px) {
      .ai-footer__grid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
      }
      .ai-footer__brand {
        grid-column: 1 / -1;
      }
      .ai-footer__desc {
        max-width: 100%;
      }
    }

    @media screen and (max-width: 600px) {
      .ai-footer {
        padding: 32px 0 16px;
      }
      .ai-footer__inner {
        padding: 0 18px;
      }
      .ai-footer__grid {
        grid-template-columns: 1fr;
        gap: 28px;
      }
      .ai-footer__brand {
        grid-column: 1;
      }
      .ai-footer__col h5 {
        margin-bottom: 12px;
      }
      
      .ai-footer__bottom {
        flex-direction: column;
        gap: 4px;
        padding: 20px 0 0;
        margin-top: 24px;
        font-size: 12px;
      }
      .ai-footer__social a {
        width: 42px;
        height: 42px;
        font-size: 18px;
      }
      .ai-footer__brand-title {
        font-size: 17px;
      }
      .ai-footer__logo-wrapper {
        width: 48px;
        height: 48px;
      }
    }

    @media screen and (max-width: 380px) {
      .ai-footer__contact-item {
        font-size: 12px;
        gap: 8px;
      }
      .ai-footer__contact-item i {
        font-size: 13px;
        width: 16px;
      }
    }
  </style>
</head>
<body>
  <!-- dummy page content -->
 

  <!-- ========== FOOTER (PHP ready) ========== -->
  <?php
    // Ensure baseDir is set (for asset paths)
    if (!isset($baseDir)) {
        $baseDir = '';  // or './' if needed
    }
  ?>

  <footer class="ai-footer">
    <div class="ai-footer__inner">

      <!-- main grid -->
      <div class="ai-footer__grid">

        <!-- Brand column -->
        <div class="ai-footer__brand">
          <div class="ai-footer__brand-header">
            <div class="ai-footer__logo-wrapper">
              <img src="<?php echo $baseDir; ?>assets/images/AI_Logo.jpg" alt="Anything Inside Logo">
            </div>
            <h3 class="ai-footer__brand-title">
              Anything <span>Inside</span>
            </h3>
          </div>
          <p class="ai-footer__desc">
            Let us create gift sets,custom merchandise, and curated bundles 
  without the stress.
          </p>
         <!-- <div class="ai-footer__social">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
          </div>-->
        </div>

        <!-- Quick Links -->
        <div class="ai-footer__col">
          <h5>Quick Links</h5>
          <ul class="ai-footer__list">
            <li><a href="<?php echo $baseDir; ?>index.php">Home</a></li>
            <li><a href="<?php echo $baseDir; ?>Bundles.php">Gift Bundles</a></li>
            <li><a href="<?php echo $baseDir; ?>product.php">Custom Merchandise</a></li>
            <li><a href="<?php echo $baseDir; ?>AboutUs.php">About Us</a></li>
          </ul>
        </div>

        <!-- Support + CONTACT DETAILS (integrated) -->
        <div class="ai-footer__col">
          <h5>Details</h5>
       <!--   <ul class="ai-footer__list">
            <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform?embedded=true" target="_blank">Inquire Here</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Shipping Info</a></li>
            <li><a href="#">Contact</a></li>
          </ul>-->

          <!-- ====== DETAILS (address, hours, phone, email, FB) ====== -->
          <div class="ai-footer__contact-details">
            <!-- hours -->
            <div class="ai-footer__contact-item">
              <i class="fas fa-clock"></i>
              <span>Mon – Fri, 9am – 6pm</span>
            </div>
            <!-- address -->
            <div class="ai-footer__contact-item">
              <i class="fas fa-location-dot"></i>
              <span>Plaza Agapita Commercial Complex, Batong Malake, Los Baños, Laguna</span>
            </div>
            <!-- phone -->
            <div class="ai-footer__contact-item">
              <i class="fas fa-phone"></i>
              <a href="tel:09687305403">0968 730 5403</a>
            </div>
            <!-- email -->
            <div class="ai-footer__contact-item">
              <i class="fas fa-envelope"></i>
              <a href="mailto:ai.anythinginside@gmail.com">ai.anythinginside@gmail.com</a>
            </div>
            <!-- Facebook (with link) -->
            <div class="ai-footer__contact-item">
              <i class="fab fa-facebook"></i>
              <span class="fb-link">
                <a href="https://www.facebook.com/profile.php?id=61572947390035" target="_blank" rel="noopener">
                  Anything Inside Printing Services
                </a>
                <span style="opacity:0.4; font-size:12px; margin-left:2px;">&#8599;</span>
              </span>
            </div>
          </div>
          <!-- end contact details -->
        </div>
        <!-- end support col -->

      </div>
      <!-- end grid -->

      <!-- bottom bar -->
      <div class="ai-footer__bottom">
        <span>&copy; <?php echo date('Y'); ?> Anything Inside. All rights reserved.</span>
        <span>Made with <i class="fas fa-heart heart"></i></span>
        <span style="opacity:0.5;">|</span>
        <span style="opacity:0.6;">Plaza Agapita · Los Baños</span>
      </div>

    </div>
    <!-- end inner -->
  </footer>

  <!-- script (notifications) -->
  <script src="<?php echo $baseDir; ?>assets/js/customer-site-functions/notifications.js"></script>
</body>
</html>