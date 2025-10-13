<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="voting-page">
    <!-- Hero Section -->
    <div class="hero-section mb-5">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="event-badge mb-3">
                            <span class="badge bg-gradient-success px-3 py-2">
                                <i class="fas fa-circle pulse me-2"></i>
                                Voting Active
                            </span>
                        </div>
                        <h1 class="display-4 fw-bold text-dark mb-3">
                            <?= htmlspecialchars($event['name']) ?>
                        </h1>
                        <p class="lead text-muted mb-4">
                            Cast your vote and support your favorite contestant in this exciting competition
                        </p>
                        <div class="event-meta d-flex flex-wrap gap-4">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                <span>Ends <?= date('M j, Y', strtotime($event['end_date'])) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span><?= date('H:i', strtotime($event['end_date'])) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span><?= count($contestants) ?> Contestants</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php if (!empty($event['featured_image'])): ?>
                        <div class="hero-image">
                            <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" 
                                 alt="<?= htmlspecialchars($event['name']) ?>"
                                 class="img-fluid rounded-3 shadow-lg">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-xl-8 col-lg-7">
                <!-- Alert Container -->
                <div id="alert-container" class="mb-4"></div>

                <!-- Category Tabs -->
                <?php if (!empty($contestantsByCategory) && count($contestantsByCategory) > 1): ?>
                <div class="category-tabs mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-tags text-primary me-2"></i>
                                Categories
                            </h5>
                            <ul class="nav nav-pills nav-fill" id="categoryTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" 
                                            data-bs-target="#all-contestants" type="button" role="tab">
                                        <i class="fas fa-th-large me-2"></i>
                                        All Contestants
                                        <span class="badge bg-light text-dark ms-2"><?= count($contestants) ?></span>
                                    </button>
                                </li>
                                <?php foreach ($contestantsByCategory as $category): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="cat-<?= $category['id'] ?>-tab" data-bs-toggle="pill" 
                                            data-bs-target="#cat-<?= $category['id'] ?>" type="button" role="tab">
                                        <?= htmlspecialchars($category['name']) ?>
                                        <span class="badge bg-light text-dark ms-2"><?= count($category['contestants']) ?></span>
                                    </button>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Voting Form -->
                <form id="votingForm" class="needs-validation" novalidate>
                    <input type="hidden" id="contestant_id" name="contestant_id">
                    <input type="hidden" id="bundle_id" name="bundle_id">
                    
                    <!-- Step 1: Select Contestant -->
                    <div class="voting-step mb-5">
                        <div class="step-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="step-number">
                                    <span class="badge bg-primary rounded-circle p-3">1</span>
                                </div>
                                <div class="step-content ms-3">
                                    <h4 class="mb-1">Choose Your Favorite</h4>
                                    <p class="text-muted mb-0">Select the contestant you want to vote for</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="categoryTabContent">
                            <!-- All Contestants Tab -->
                            <div class="tab-pane fade show active" id="all-contestants" role="tabpanel">
                                <div class="contestants-grid">
                                    <?php foreach ($contestants as $contestant): ?>
                                        <?php include __DIR__ . '/partials/contestant-card.php'; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Category Tabs -->
                            <?php foreach ($contestantsByCategory as $category): ?>
                            <div class="tab-pane fade" id="cat-<?= $category['id'] ?>" role="tabpanel">
                                <div class="contestants-grid">
                                    <?php foreach ($category['contestants'] as $contestant): ?>
                                        <?php include __DIR__ . '/partials/contestant-card.php'; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Step 2: Select Vote Package -->
                    <div class="voting-step mb-5">
                        <div class="step-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="step-number">
                                    <span class="badge bg-primary rounded-circle p-3">2</span>
                                </div>
                                <div class="step-content ms-3">
                                    <h4 class="mb-1">Select Vote Package</h4>
                                    <p class="text-muted mb-0">Choose how many votes you want to cast</p>
                                </div>
                            </div>
                        </div>

                        <div class="packages-grid">
                            <?php foreach ($bundles as $bundle): ?>
                            <div class="package-card" data-bundle-id="<?= $bundle['id'] ?>"
                                 onclick="selectBundle(<?= $bundle['id'] ?>, <?= $bundle['votes'] ?>, <?= $bundle['price'] ?>)">
                                <div class="package-header">
                                    <div class="package-icon">
                                        <i class="fas fa-vote-yea"></i>
                                    </div>
                                    <h5 class="package-name"><?= htmlspecialchars($bundle['name']) ?></h5>
                                </div>
                                <div class="package-body">
                                    <div class="vote-count">
                                        <span class="votes-number"><?= $bundle['votes'] ?></span>
                                        <span class="votes-label">Vote<?= $bundle['votes'] > 1 ? 's' : '' ?></span>
                                    </div>
                                    <div class="package-price">
                                        <span class="price-main">$<?= number_format($bundle['price'], 2) ?></span>
                                        <span class="price-per">$<?= number_format($bundle['price'] / $bundle['votes'], 2) ?> per vote</span>
                                    </div>
                                </div>
                                <div class="package-footer">
                                    <div class="selection-indicator">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Step 3: Contact Information -->
                    <div class="voting-step mb-5">
                        <div class="step-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="step-number">
                                    <span class="badge bg-primary rounded-circle p-3">3</span>
                                </div>
                                <div class="step-content ms-3">
                                    <h4 class="mb-1">Contact Information</h4>
                                    <p class="text-muted mb-0">Enter your phone number to complete the vote</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-form">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control form-control-lg" 
                                               id="msisdn" name="msisdn" 
                                               placeholder="+233241234567" required>
                                        <label for="msisdn">
                                            <i class="fas fa-phone me-2"></i>
                                            Mobile Number *
                                        </label>
                                        <div class="form-text">Include country code (e.g., +233 for Ghana)</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" 
                                                       id="coupon_code" name="coupon_code" 
                                                       placeholder="Enter coupon code">
                                                <label for="coupon_code">
                                                    <i class="fas fa-tag me-2"></i>
                                                    Coupon Code (Optional)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" 
                                                       id="referral_code" name="referral_code" 
                                                       placeholder="Enter referral code">
                                                <label for="referral_code">
                                                    <i class="fas fa-users me-2"></i>
                                                    Referral Code (Optional)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mb-5">
                        <button type="submit" id="vote-button" class="btn btn-success btn-lg px-5 py-3" disabled>
                            <i class="fas fa-vote-yea me-2"></i>
                            Cast Your Vote
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                <div class="sidebar-content">
                    <!-- Vote Summary -->
                    <div class="summary-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-receipt text-success me-2"></i>
                                Vote Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="vote-summary">
                                <div class="empty-summary">
                                    <div class="empty-icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </div>
                                    <p>Select a contestant and vote package to see your summary</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- How to Vote -->
                    <div class="info-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-question-circle text-info me-2"></i>
                                How to Vote
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="steps-list">
                                <div class="step-item">
                                    <div class="step-icon">1</div>
                                    <div class="step-text">Select your favorite contestant</div>
                                </div>
                                <div class="step-item">
                                    <div class="step-icon">2</div>
                                    <div class="step-text">Choose a vote package</div>
                                </div>
                                <div class="step-item">
                                    <div class="step-icon">3</div>
                                    <div class="step-text">Enter your phone number</div>
                                </div>
                                <div class="step-item">
                                    <div class="step-icon">4</div>
                                    <div class="step-text">Click "Cast Your Vote"</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="terms-card">
                        <div class="card-body">
                            <h6 class="mb-3">
                                <i class="fas fa-info-circle text-warning me-2"></i>
                                Terms & Conditions
                            </h6>
                            <ul class="terms-list">
                                <li>One vote per transaction</li>
                                <li>Votes are final and cannot be changed</li>
                                <li>Standard SMS rates may apply</li>
                                <li>Results are updated in real-time</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Styles -->
<link rel="stylesheet" href="/assets/css/voting-page.css">

<!-- Include Scripts -->
<script src="/assets/js/voting-page.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
