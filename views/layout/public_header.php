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
    <meta property="og:image" content="<?= $og_image ?? APP_URL . '/public/assets/images/icon-512.png' ?>">
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
    <meta name="twitter:image" content="<?= $twitter_image ?? APP_URL . '/public/assets/images/icon-512.png' ?>">
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
        "screenshot": "<?= APP_URL ?>/public/assets/images/icon-512.png"
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
            <a class="navbar-brand fw-bold" href="<?= PUBLIC_URL ?>">
                <i class="fas fa-vote-yea me-2 text-primary"></i>
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
