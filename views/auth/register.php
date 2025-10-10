<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= APP_NAME ?></title>
    
    <!-- CoreUI CSS -->
    <link href="<?= COREUI_CSS ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/public/css/auth.css" rel="stylesheet">
    <style>
        .plan-card {
            transition: all 0.3s ease;
            border: 2px solid #dee2e6;
        }
        
        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .plan-card.selected-plan {
            border-color: #28a745 !important;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        
        .plan-card .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .plan-card .card-body {
            position: relative;
        }
        
        .plan-card.selected-plan::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #28a745;
            font-size: 1.2em;
        }
    </style>
</head>
<body class="c-app" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Navigation Header -->
    <header class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= APP_URL ?>">
                <i class="fas fa-vote-yea me-2"></i><?= APP_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/pricing">
                            <i class="fas fa-tags me-1"></i>Pricing
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/login">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-warning">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary mb-3 auth-logo"></i>
                            <h3>Create Your <?= APP_NAME ?> Account</h3>
                            <p class="text-muted">Start managing your voting events today</p>
                        </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= APP_URL ?>/register" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="organization" class="form-label">Organization Name *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control <?= isset($errors['organization']) ? 'is-invalid' : '' ?>" 
                                           id="organization" 
                                           name="organization" 
                                           value="<?= htmlspecialchars($data['organization'] ?? '') ?>"
                                           required>
                                </div>
                                <?php if (isset($errors['organization'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['organization']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                           id="email" 
                                           name="email" 
                                           value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                                           required>
                                </div>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['confirm_password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Plan Selection -->
                    <div class="mb-4">
                        <label class="form-label">Choose Your Plan *</label>
                        <?php if (isset($errors['plan_id'])): ?>
                            <div class="text-danger small mb-2">
                                <?= htmlspecialchars($errors['plan_id']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <?php foreach ($plans ?? [] as $plan): ?>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card plan-card h-100 <?= $plan['is_popular'] ? 'border-primary' : '' ?>" onclick="selectPlan(<?= $plan['id'] ?>)">
                                    <?php if ($plan['is_popular']): ?>
                                        <div class="card-header bg-primary text-white text-center py-2">
                                            <small><i class="fas fa-star me-1"></i>Most Popular</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body text-center p-2">
                                        <h6 class="card-title mb-2"><?= htmlspecialchars($plan['name']) ?></h6>
                                        <div class="h6 text-primary mb-2">
                                            <?= $plan['price_display'] ?>
                                        </div>
                                        
                                        <ul class="list-unstyled small text-start mb-2">
                                            <li class="mb-1">
                                                <i class="fas fa-calendar-alt text-info me-1"></i>
                                                <strong>Events:</strong> <?= $plan['events_display'] ?>
                                            </li>
                                            <li class="mb-1">
                                                <i class="fas fa-users text-success me-1"></i>
                                                <strong>Contestants:</strong> <?= $plan['contestants_display'] ?>
                                            </li>
                                            <?php if ($plan['trial_days'] > 0): ?>
                                            <li class="mb-1">
                                                <i class="fas fa-gift text-warning me-1"></i>
                                                <strong><?= $plan['trial_days'] ?> days trial</strong>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                        
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="radio" name="plan_id" 
                                                   id="plan_<?= $plan['id'] ?>" value="<?= $plan['id'] ?>" 
                                                   <?php 
                                                   // Auto-select plan from URL parameter or form data
                                                   $isSelected = false;
                                                   if (isset($selectedPlanId) && $selectedPlanId == $plan['id']) {
                                                       $isSelected = true;
                                                   } elseif (isset($data['plan_id']) && $data['plan_id'] == $plan['id']) {
                                                       $isSelected = true;
                                                   }
                                                   echo $isSelected ? 'checked' : '';
                                                   ?> required>
                                            <label class="form-check-label fw-bold" for="plan_<?= $plan['id'] ?>">
                                                Select This Plan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                                and <a href="#" class="text-decoration-none">Privacy Policy</a> *
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>
                        Create Account
                    </button>
                </form>

                <div class="text-center">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="<?= APP_URL ?>/login" class="text-decoration-none">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-4 text-center">
    </div>

    <!-- CoreUI JavaScript -->
    <script src="<?= COREUI_JS ?>"></script>
    
    <!-- Custom JavaScript -->
    <script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    function selectPlan(planId) {
        // Remove selected class from all plan cards
        document.querySelectorAll('.plan-card').forEach(card => {
            card.classList.remove('border-success', 'selected-plan');
        });
        
        // Select the radio button
        const radio = document.getElementById('plan_' + planId);
        if (radio) {
            radio.checked = true;
            
            // Add selected styling to the card
            const card = radio.closest('.plan-card');
            if (card) {
                card.classList.add('border-success', 'selected-plan');
            }
        }
    }
    
    // Initialize plan selection styling
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers to plan cards
        document.querySelectorAll('.plan-card').forEach(card => {
            card.style.cursor = 'pointer';
            
            // Check if this plan is already selected
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                card.classList.add('border-success', 'selected-plan');
            }
        });
        
        // Add change handlers to radio buttons
        document.querySelectorAll('input[name="plan_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    selectPlan(this.value);
                }
            });
        });
        
        // Auto-trigger visual selection for pre-selected plan
        const selectedRadio = document.querySelector('input[name="plan_id"]:checked');
        if (selectedRadio) {
            selectPlan(selectedRadio.value);
        }
    });

    // Form validation
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
