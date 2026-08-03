<?php
// Ensure baseDir is set
if (!isset($baseDir)) {
    $baseDir = '';
}

$currentPage = basename($_SERVER['PHP_SELF']);
$showSearch = in_array($currentPage, ['product.php', 'Bundles.php']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anything Inside</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $baseDir; ?>assets/css/customer-site/style.css">
    <link rel="stylesheet" href="<?php echo $baseDir; ?>assets/css/customer-site/notifications.css">

    <?php if ($currentPage === 'index.php'): ?>
    <link rel="stylesheet" href="<?php echo $baseDir; ?>assets/css/customer-site/home.css">
    <?php endif; ?>

    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===== HEADER OVERRIDES ===== */
        header {
            background: #0a2a44;
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0 20px;
        }

        .top-header {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            height: 75px;
            width: 100%;
            border-bottom: none;
            flex-wrap: nowrap !important;
        }

        /* ===== LOGO LEFT ===== */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .logo-header {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.15);
        }

        .brand-name {
            color: #ffffff;
            font-size: clamp(18px, 2vw, 22px);
            font-weight: 400;
            font-family: "TNRM", "Georgia", serif;
            letter-spacing: 0.3px;
        }

        /* ===== NAV CONTAINER (right side) ===== */
        .nav-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-shrink: 0;
            margin-left: auto;
        }

        /* ===== UNIFORM NAV BUTTON STYLE ===== */
        .header-btn {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 13.5px;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
            color: rgba(255,255,255,0.85);
            background: transparent;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .header-btn i {
            font-size: 13px;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        .header-btn.active {
            background: rgba(255,255,255,0.14);
            color: #ffffff;
        }

        /* ===== MOBILE MENU TOGGLE ===== */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            flex-shrink: 0;
            margin-left: 4px;
            order: 3;
        }

        .menu-toggle:hover {
            background: rgba(255,255,255,0.08);
        }

        /* ===== NAV LINKS (desktop) ===== */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ============================================================ */
        /* ===== RESPONSIVE: MOBILE (max-width: 850px) ===== */
        /* ============================================================ */
        @media (max-width: 850px) {
            header {
                padding: 0 16px;
            }

            .top-header {
                height: 64px;
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
                width: 100%;
                flex-wrap: nowrap !important;
            }

            /* Logo stays on the far left */
            .brand {
                flex-shrink: 0;
                order: 1;
            }

            .logo-header {
                width: 38px;
                height: 38px;
            }

            .brand-name {
                font-size: 16px;
            }

            /* Menu toggle button on the far right */
            .menu-toggle {
                display: block !important;
                order: 3;
                margin-left: 0;
                cursor: pointer;
            }

            /* Nav container: keeps toggle on right */
            .nav-container {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 0;
                flex-shrink: 0;
                position: relative;
                order: 2;
                margin-left: auto;
            }

            /* Hide inline nav links on mobile */
            .nav-links {
                display: none !important;
                position: absolute;
                top: 54px;
                right: 0;
                background: #0a2a44;
                flex-direction: column;
                min-width: 220px;
                padding: 10px 0;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.4);
                border: 1px solid rgba(255,255,255,0.06);
                z-index: 99999 !important;
                gap: 0;

               
            }
       
            .nav-links.show {
                display: flex !important;
            }

            .nav-links .header-btn {
                width: 100%;
                border-radius: 0;
                padding: 12px 22px;
                font-size: 14px;
                justify-content: flex-start;
            }

            .nav-links .header-btn i {
                width: 20px;
                text-align: center;
            }
        }

        /* ===== EXTRA SMALL (max-width: 480px) ===== */
        @media (max-width: 480px) {
            header {
                padding: 0 12px;
            }

            .top-header {
                height: 56px;
            }

            .brand-name {
                font-size: 14px;
            }
            .logo-header {
                width: 32px;
                height: 32px;
            }

            .menu-toggle {
                font-size: 20px;
                padding: 6px 10px;
            }

            .nav-links {
                min-width: 190px;
                top: 48px;
            }

            .nav-links .header-btn {
                padding: 10px 18px;
                font-size: 13px;
            }
        }

        /* ===== OVERRIDE ANY CONFLICTING STYLES FROM style.css ===== */
        @media (max-width: 768px) {
            .top-header {
                flex-direction: row !important;
                align-items: center !important;
                gap: 0 !important;
                padding: 0 !important;
            }

            .brand {
                margin: 0 !important;
                padding: 0 !important;
            }

            .nav-container {
                width: auto !important;
                padding: 0 !important;
                margin-left: auto !important;
                border-top: none !important;
            }

            .menu-toggle {
                display: block !important;
                position: relative !important;
                width: auto !important;
                margin: 0 !important;
            }
        }

        @media (max-width: 480px) {
            .top-header {
                flex-direction: row !important;
                align-items: center !important;
            }
        }
             #inquire-btn-a{
                    background-color: #f4ba31;
                    color: #0a2a44;
                }
    </style>

</head>

<body>

<header>
    <div class="top-header">
        <!-- Logo Left (always on the far left) -->
        <div class="brand">
            <img src="<?php echo $baseDir; ?>assets/images/AI_Logo.jpg" alt="Anything Inside" class="logo-header">
            <span class="brand-name">Anything Inside</span>
        </div>

        <!-- Nav Right (toggle button on the far right) -->
        <div class="nav-container">
            <div class="nav-links" id="mobile_menu">
                <a href="<?php echo $baseDir; ?>index.php" class="header-btn <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="<?php echo $baseDir; ?>Bundles.php" class="header-btn <?php echo $currentPage == 'Bundles.php' ? 'active' : ''; ?>">
                    <i class="fas fa-gift"></i> Gift Bundles
                </a>
                <a href="<?php echo $baseDir; ?>product.php" class="header-btn <?php echo $currentPage == 'product.php' ? 'active' : ''; ?>">
                    <i class="fas fa-pencil-ruler"></i> Custom Merchandise
                </a>
                <a href="<?php echo $baseDir; ?>AboutUs.php" class="header-btn <?php echo $currentPage == 'AboutUs.php' ? 'active' : ''; ?>">
                    <i class="fas fa-info-circle"></i> About Us
                </a>
                <a id="inquire-btn-a" href="https://docs.google.com/forms/d/e/1FAIpQLSdBK8Cvyfb8qpRG1aCjTbtV9dILsi4U3xxe6lBrlSVxKggumg/viewform" target="_blank" rel="noopener noreferrer" class="header-btn">
                    <i class="fas fa-envelope"></i> Inquire Here
                </a>
            </div>

            <button id="menu_toggle" class="menu-toggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<script>
(function() {
    function initMenu() {
        const toggle = document.getElementById('menu_toggle');
        const menu = document.getElementById('mobile_menu');

        if (toggle && menu) {
            toggle.onclick = function(e) {
                e.stopPropagation();
                menu.classList.toggle('show');
            };

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-container')) {
                    menu.classList.remove('show');
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenu);
    } else {
        initMenu();
    }
})();
</script>