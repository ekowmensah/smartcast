<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SmartCast' ?> - <?= APP_NAME ?></title>
    
    <!-- CoreUI CSS -->
    <link href="<?= COREUI_CSS ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/public/css/public.css" rel="stylesheet">
</head>
<body class="c-app c-default-layout">
    
    <!-- Public Navigation -->
    <header class="header header-sticky mb-4">
        <div class="container-fluid">
            <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="header-brand d-md-flex" href="<?= PUBLIC_URL ?>">
                <i class="fas fa-vote-yea me-2 text-primary"></i>
                <strong><?= APP_NAME ?></strong>
            </a>
            
            <ul class="header-nav d-none d-md-flex">
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
                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_URL ?>/vote-shortcode">
                        <i class="fas fa-hashtag me-1"></i>Vote by Code
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_URL ?>/about">
                        <i class="fas fa-info-circle me-1"></i>About
                    </a>
                </li>
            </ul>
            
            <ul class="header-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <i class="fas fa-user-circle fa-2x"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0 pr-5 w-100">
                            <div class="dropdown-header bg-light py-2">
                                <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></div>
                                <div class="text-medium-emphasis small">
                                    <?= ucfirst($_SESSION['user_role'] ?? 'User') ?>
                                </div>
                            </div>
                            
                            <?php 
                            $dashboardUrl = match($_SESSION['user_role'] ?? '') {
                                'platform_admin' => SUPERADMIN_URL,
                                'owner', 'manager' => ORGANIZER_URL,
                                default => ADMIN_URL
                            };
                            ?>
                            
                            <a class="dropdown-item" href="<?= $dashboardUrl ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="dropdown-item" href="<?= PUBLIC_URL ?>/profile">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                            <a class="dropdown-item" href="<?= PUBLIC_URL ?>/settings">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="<?= PUBLIC_URL ?>/logout" class="d-inline">
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </div>
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
    </header>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_info'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash_info']) ?>
                    <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash_info']); ?>
            <?php endif; ?>
