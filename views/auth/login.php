<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    
    <!-- CoreUI CSS -->
    <link href="<?= COREUI_CSS ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/public/css/auth.css" rel="stylesheet">
</head>
<body class="c-app flex-row align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card-group">
                    <div class="card p-4">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-vote-yea fa-3x text-primary mb-3 auth-logo"></i>
                                <h1><?= APP_NAME ?></h1>
                                <p class="text-medium-emphasis">Sign in to your account</p>
                            </div>

                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?= APP_URL ?>/login" class="needs-validation" novalidate>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           name="email" 
                                           placeholder="Email Address"
                                           value="<?= htmlspecialchars($data['email'] ?? '') ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email address.
                                    </div>
                                </div>
                                
                                <div class="input-group mb-4">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           placeholder="Password" 
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide a password.
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login
                                        </button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="#" class="btn btn-link px-0" onclick="alert('Please contact support for password reset.')">
                                            Forgot password?
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card text-white bg-primary py-5" style="width: 44%;">
                        <div class="card-body text-center">
                            <div>
                                <h2>Welcome to <?= APP_NAME ?></h2>
                                <p>Professional voting management platform for organizations worldwide.</p>
                                
                                <div class="mb-4">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <i class="fas fa-vote-yea fa-2x mb-2"></i>
                                            <div class="small">Real-time Voting</div>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                            <div class="small">Live Analytics</div>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                            <div class="small">Secure Platform</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="<?= APP_URL ?>/register" class="btn btn-lg btn-outline-light me-2">
                                        <i class="fas fa-user-plus me-2"></i>Register Now
                                    </a>
                                    <a href="<?= APP_URL ?>/pricing" class="btn btn-lg btn-light">
                                        <i class="fas fa-tags me-2"></i>View Plans
                                    </a>
                                </div>
                                
                                <div class="mt-4">
                                    <small>
                                        <a href="<?= APP_URL ?>/" class="text-white text-decoration-none">
                                            <i class="fas fa-home me-1"></i>Back to Homepage
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CoreUI JavaScript -->
    <script src="<?= COREUI_JS ?>"></script>
    
    <!-- Form Validation -->
    <script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    </script>
</body>
</html>
