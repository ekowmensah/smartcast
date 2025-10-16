<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title><?= $title ?? 'SmartCast' ?> - <?= APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/public/assets/images/favicon.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APP_URL ?>/public/assets/images/icon-192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= APP_URL ?>/public/assets/images/icon-512.png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>/public/assets/images/icon-192.png">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SmartCast">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="SmartCast">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= APP_URL ?>/public/manifest.json">
    
    <!-- Enhanced SEO Meta Tags -->
    <meta name="description" content="<?= $seo_description ?? 'SmartCast - Ghana\'s leading digital voting platform. Secure online voting for events, competitions, talent shows, and elections with MTN, Vodafone, AirtelTigo mobile money integration. Real-time results, fraud prevention, SMS receipts.' ?>">
    <meta name="keywords" content="<?= $seo_keywords ?? 'digital voting Ghana, online voting platform, mobile money voting, MTN mobile money, Vodafone Cash, AirtelTigo Money, talent show voting, competition voting, secure voting system, real-time voting results, SMS voting receipts, Ghana elections, event voting, contestant voting, democratic voting, fraud-free voting, Paystack voting, Hubtel voting, Ghana voting app, digital democracy Ghana' ?>">
    <meta name="author" content="SmartCast Ghana">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="language" content="English">
    <meta name="geo.region" content="GH">
    <meta name="geo.country" content="Ghana">
    <meta name="geo.placename" content="Accra, Ghana">
    <meta name="ICBM" content="5.6037, -0.1870">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="revisit-after" content="1 days">
    <meta name="classification" content="voting, technology, democracy">
    <link rel="canonical" href="<?= $canonical_url ?? APP_URL . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $og_title ?? ($title ?? 'SmartCast') . ' - Ghana\'s #1 Digital Voting Platform' ?>">
    <meta property="og:description" content="<?= $og_description ?? 'Vote securely with mobile money! MTN, Vodafone, AirtelTigo supported. Real-time results, SMS receipts, fraud prevention. Ghana\'s most trusted digital voting platform for events, competitions & elections.' ?>">
    <meta property="og:image" content="<?= $og_image ?? APP_URL . '/logo1.png' ?>">
    <meta property="og:url" content="<?= $canonical_url ?? APP_URL . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SmartCast Ghana">
    <meta property="og:locale" content="en_GH">
    <meta property="article:author" content="SmartCast Ghana">
    <meta property="fb:app_id" content="your-facebook-app-id">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@SmartCastGH">
    <meta name="twitter:creator" content="@SmartCastGH">
    <meta name="twitter:title" content="<?= $twitter_title ?? ($title ?? 'SmartCast') . ' - Ghana Digital Voting Platform' ?>">
    <meta name="twitter:description" content="<?= $twitter_description ?? 'Secure mobile money voting in Ghana 🇬🇭 MTN • Vodafone • AirtelTigo supported. Real-time results, SMS receipts, fraud prevention. #DigitalVoting #Ghana' ?>">
    <meta name="twitter:image" content="<?= $twitter_image ?? APP_URL . '/logo1.png' ?>">
    <meta name="twitter:image:alt" content="SmartCast - Ghana's Digital Voting Platform">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="mobile-web-app-title" content="SmartCast">
    <meta name="msapplication-TileColor" content="#667eea">
    <meta name="msapplication-config" content="<?= APP_URL ?>/public/browserconfig.xml">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "SmartCast",
        "alternateName": "SmartCast Ghana Digital Voting Platform",
        "description": "Ghana's leading digital voting platform with mobile money integration for secure online voting",
        "url": "<?= APP_URL ?>",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web Browser",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "GHS"
        },
        "creator": {
            "@type": "Organization",
            "name": "SmartCast Ghana",
            "url": "<?= APP_URL ?>",
            "address": {
                "@type": "PostalAddress",
                "addressCountry": "GH",
                "addressRegion": "Greater Accra",
                "addressLocality": "Accra"
            }
        },
        "featureList": [
            "Mobile Money Integration",
            "Real-time Voting Results",
            "SMS Receipt System",
            "Fraud Prevention",
            "Multi-language Support",
            "Secure Payment Processing"
        ],
        "screenshot": "<?= APP_URL ?>/logo1.png",
        "logo": "<?= APP_URL ?>/logo1.png"
    }
    </script>
    
    <!-- CoreUI CSS -->
    <link href="<?= COREUI_CSS ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/public/css/public.css" rel="stylesheet">
</head>
<body class="c-app c-default-layout">
    
    <!-- Public Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= PUBLIC_URL ?>">
                <img src="<?= APP_URL ?>/logo1.png" alt="SmartCast Logo" class="me-2" style="height: 40px; width: auto;">
                <?= APP_NAME ?>
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= PUBLIC_URL ?>">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= PUBLIC_URL ?>/events">
                            <i class="fas fa-calendar me-1"></i>Events
                        </a>
                    </li>
                <!--    <li class="nav-item">
                        <a class="nav-link" href="<?= PUBLIC_URL ?>/vote-shortcode">
                            <i class="fas fa-hashtag me-1"></i>Vote by Code
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/verify-receipt">
                            <i class="fas fa-shield-check me-1"></i>Verify Receipt
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= PUBLIC_URL ?>/about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                </ul>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <div class="dropdown-header">
                                        <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></div>
                                        <div class="text-muted small"><?= ucfirst($_SESSION['user_role'] ?? 'User') ?></div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <?php 
                                $userRole = $_SESSION['user_role'] ?? '';
                                switch($userRole) {
                                    case 'platform_admin':
                                        $dashboardUrl = SUPERADMIN_URL;
                                        break;
                                    case 'owner':
                                    case 'manager':
                                        $dashboardUrl = ORGANIZER_URL;
                                        break;
                                    default:
                                        $dashboardUrl = ADMIN_URL;
                                        break;
                                }
                                ?>
                                
                                <li>
                                    <a class="dropdown-item" href="<?= $dashboardUrl ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= PUBLIC_URL ?>/profile">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= PUBLIC_URL ?>/settings">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?= PUBLIC_URL ?>/logout" class="d-inline">
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= PUBLIC_URL ?>/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="<?= PUBLIC_URL ?>/register">
                                <i class="fas fa-user-plus me-1"></i>Get Started
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title><?= $title ?? 'SmartCast' ?> - <?= APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/public/assets/images/favicon.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APP_URL ?>/public/assets/images/icon-192.png">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Enhanced SEO Meta Tags -->
    <meta name="description" content="<?= $seo_description ?? 'SmartCast - Ghana\'s leading digital voting platform. Secure online voting for events, competitions, talent shows, and elections with MTN, Vodafone, AirtelTigo mobile money integration.' ?>">
    <meta name="keywords" content="<?= $seo_keywords ?? 'digital voting Ghana, online voting platform, mobile money voting, MTN mobile money, Vodafone Cash, AirtelTigo Money, talent show voting, competition voting, secure voting system' ?>">
    <meta name="author" content="SmartCast Ghana">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $og_title ?? ($title ?? 'SmartCast') . ' - Ghana\'s #1 Digital Voting Platform' ?>">
    <meta property="og:description" content="<?= $og_description ?? 'Vote securely with mobile money! MTN, Vodafone, AirtelTigo supported. Real-time results, SMS receipts, fraud prevention.' ?>">
    <meta property="og:image" content="<?= $og_image ?? APP_URL . '/logo1.png' ?>">
    <meta property="og:url" content="<?= $canonical_url ?? APP_URL . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $twitter_title ?? ($title ?? 'SmartCast') . ' - Ghana Digital Voting Platform' ?>">
    <meta name="twitter:description" content="<?= $twitter_description ?? 'Secure mobile money voting in Ghana 🇬🇭 MTN • Vodafone • AirtelTigo supported. Real-time results, SMS receipts.' ?>">
    <meta name="twitter:image" content="<?= $twitter_image ?? APP_URL . '/logo1.png' ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Session Manager -->
    <script src="<?= PUBLIC_URL ?>/js/session-manager.js"></script>
    <script src="<?= PUBLIC_URL ?>/js/session-init.js"></script>

    <!-- Custom Stunning Header Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 20px 60px rgba(0, 0, 0, 0.15);
            --shadow-strong: 0 30px 80px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Stunning Header Styles */
        .stunning-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-soft);
        }

        .stunning-header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-medium);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        /* Logo Section */
        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .brand-section:hover {
            transform: translateY(-2px);
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
        }

        .brand-logo:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-medium);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin: 0;
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-soft);
        }

        .nav-icon {
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover .nav-icon {
            transform: scale(1.1);
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-medium);
        }

        /* User Dropdown Menu */
        .user-dropdown {
            min-width: 250px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .user-dropdown .dropdown-header {
            padding: 1rem 1.5rem 0.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 0.5rem;
        }

        .user-dropdown .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            transition: all 0.3s ease;
            border-radius: 0;
            display: flex;
            align-items: center;
        }

        .user-dropdown .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: #667eea;
            transform: translateX(5px);
        }

        .user-dropdown .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .user-dropdown .dropdown-divider {
            margin: 0.5rem 1rem;
            opacity: 0.2;
        }

        .user-dropdown button.dropdown-item {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-login {
            padding: 0.75rem 1.5rem;
            background: transparent;
            border: 2px solid transparent;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid;
            border-image: var(--primary-gradient) 1;
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover {
            color: white;
            transform: translateY(-2px);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-register {
            padding: 0.75rem 2rem;
            background: var(--primary-gradient);
            border: none;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--secondary-gradient);
            transition: left 0.3s ease;
        }

        .btn-register:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-register:hover::before {
            left: 0;
        }

        .btn-register span {
            position: relative;
            z-index: 1;
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-toggle span {
            width: 25px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-medium);
            z-index: 999;
        }

        .mobile-menu.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .mobile-nav {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-nav .nav-link {
            padding: 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                padding: 0 1rem;
                height: 70px;
            }

            .nav-menu {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .brand-name {
                font-size: 1.25rem;
            }

            .brand-tagline {
                display: none;
            }

            .auth-buttons {
                gap: 0.5rem;
            }

            .btn-login,
            .btn-register {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .header-container {
                height: 60px;
            }

            .brand-logo {
                width: 40px;
                height: 40px;
            }

            .brand-name {
                font-size: 1.1rem;
            }
        }

        /* Page Content Spacing */
        .page-content {
            margin-top: 0;
            padding-top: 80px;
        }

        @media (max-width: 768px) {
            .page-content {
                padding-top: 70px;
            }
        }

        @media (max-width: 480px) {
            .page-content {
                padding-top: 60px;
            }
        }

        /* Ensure consistent spacing for all page content */
        main.page-content {
            margin-top: 0 !important;
            padding-top: 0px !important;
        }

        @media (max-width: 768px) {
            main.page-content {
                padding-top: 0px !important;
            }
        }

        @media (max-width: 480px) {
            main.page-content {
                padding-top: 0px !important;
            }
        }

        /* Flash Messages */
        .flash-messages {
            position: fixed;
            top: 90px;
            right: 2rem;
            z-index: 1001;
            max-width: 400px;
        }

        .flash-alert {
            margin-bottom: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-medium);
            animation: slideInRight 0.3s ease;
        }

        .flash-alert.success {
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.3);
            color: #15803d;
        }

        .flash-alert.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #dc2626;
        }

        .flash-alert.info {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.3);
            color: #1d4ed8;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .flash-messages {
                top: 80px;
                right: 1rem;
                left: 1rem;
                max-width: none;
            }
        }
    </style>
</head>
<body data-user-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
    <!-- Stunning Header -->
    <header class="stunning-header" id="mainHeader">
        <div class="header-container">
            <!-- Brand Section -->
            <a href="<?= PUBLIC_URL ?>" class="brand-section">
                <img src="<?= APP_URL ?>/logo1.png" alt="SmartCast Logo" class="brand-logo">
                <div class="brand-text">
                    <h1 class="brand-name"><?= APP_NAME ?></h1>
                    <p class="brand-tagline">Digital Voting Platform</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="<?= PUBLIC_URL ?>" class="nav-link">
                        <i class="fas fa-home nav-icon"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= PUBLIC_URL ?>/events" class="nav-link">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span>Events</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= APP_URL ?>/verify-receipt" class="nav-link">
                        <i class="fas fa-shield-check nav-icon"></i>
                        <span>Verify Receipt</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="<?= PUBLIC_URL ?>/about" class="nav-link">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <span>About</span>
                    </a>
                </div>
            </nav>

            <!-- User Section -->
            <div class="user-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                            <li>
                                <div class="dropdown-header">
                                    <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></div>
                                    <div class="text-muted small"><?= ucfirst($_SESSION['user_role'] ?? 'User') ?></div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <?php 
                            $userRole = $_SESSION['user_role'] ?? '';
                            switch($userRole) {
                                case 'platform_admin':
                                    $dashboardUrl = SUPERADMIN_URL ?? '#';
                                    break;
                                case 'owner':
                                case 'manager':
                                    $dashboardUrl = ORGANIZER_URL ?? '#';
                                    break;
                                default:
                                    $dashboardUrl = ADMIN_URL ?? '#';
                                    break;
                            }
                            ?>
                            
                            <li>
                                <a class="dropdown-item" href="<?= $dashboardUrl ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= PUBLIC_URL ?>/profile">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= PUBLIC_URL ?>/settings">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?= PUBLIC_URL ?>/logout" class="d-inline">
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="<?= PUBLIC_URL ?>/login" class="btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a href="<?= PUBLIC_URL ?>/register" class="btn-register">
                            <span><i class="fas fa-rocket"></i> Get Started</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <nav class="mobile-nav">
            <a href="<?= PUBLIC_URL ?>" class="nav-link">
                <i class="fas fa-home nav-icon"></i>
                <span>Home</span>
            </a>
            <a href="<?= PUBLIC_URL ?>/events" class="nav-link">
                <i class="fas fa-calendar-alt nav-icon"></i>
                <span>Events</span>
            </a>
            <a href="<?= APP_URL ?>/verify-receipt" class="nav-link">
                <i class="fas fa-shield-check nav-icon"></i>
                <span>Verify Receipt</span>
            </a>
            <a href="<?= PUBLIC_URL ?>/about" class="nav-link">
                <i class="fas fa-info-circle nav-icon"></i>
                <span>About</span>
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 1rem; padding-top: 1rem;">
                    <a href="<?= PUBLIC_URL ?>/login" class="nav-link">
                        <i class="fas fa-sign-in-alt nav-icon"></i>
                        <span>Login</span>
                    </a>
                    <a href="<?= PUBLIC_URL ?>/register" class="nav-link">
                        <i class="fas fa-rocket nav-icon"></i>
                        <span>Get Started</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Flash Messages -->
    <div class="flash-messages">
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="flash-alert success">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="flash-alert error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_info'])): ?>
            <div class="flash-alert info">
                <i class="fas fa-info-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_info']) ?>
            </div>
            <?php unset($_SESSION['flash_info']); ?>
        <?php endif; ?>
    </div>

    <!-- Page Content -->
    <main class="page-content">

    <!-- JavaScript for Header Interactions -->
    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const toggle = document.querySelector('.mobile-toggle');
            
            mobileMenu.classList.toggle('active');
            toggle.classList.toggle('active');
        }

        // Active link highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Initialize Bootstrap dropdowns
            const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });

        // Auto-dismiss flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.flash-alert');
            flashMessages.forEach(message => {
                message.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => message.remove(), 300);
            });
        }, 5000);
    </script>

    <!-- Main Content -->
    <main class="py-4">
        <!-- Flash Messages -->
        <div class="container">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_info'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_info']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_info']); ?>
            <?php endif; ?>
        </div>
