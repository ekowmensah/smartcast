<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
/* Professional Voting Form - Ultra Modern Design */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body { 
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #2d3748;
    line-height: 1.6;
    min-height: 100vh;
    animation: gradientShift 10s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); }
    50% { background: linear-gradient(135deg, #f093fb 0%, #f5576c 50%, #4facfe 100%); }
}

/* Container & Layout */
.professional-vote-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
    min-height: 100vh;
}

.back-navigation {
    margin-bottom: 2rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.back-link:hover {
    background: rgba(255, 255, 255, 0.9);
    color: #2d3748;
    text-decoration: none;
    transform: translateX(-2px);
}

/* Nominee Showcase */
.nominee-showcase {
    background: linear-gradient(135deg, rgba(26, 32, 44, 0.95) 0%, rgba(45, 55, 72, 0.95) 50%, rgba(74, 85, 104, 0.95) 100%);
    color: white;
    padding: 3rem;
    border-radius: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.nominee-showcase:hover {
    transform: translateY(-5px);
}

.nominee-showcase::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.05) 50%, transparent 70%);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { transform: translateX(-100%); }
    50% { transform: translateX(100%); }
}

/* Nominee Card */
.nominee-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 2rem;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(30px);
    position: relative;
    overflow: hidden;
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.nominee-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #667eea, transparent);
    animation: slideRight 2s ease-in-out infinite;
}

@keyframes slideRight {
    0% { left: -100%; }
    100% { left: 100%; }
}

.nominee-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.nominee-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.3);
    flex-shrink: 0;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.nominee-avatar:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
}

.nominee-avatar-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(226, 232, 240, 0.8), rgba(203, 213, 224, 0.8));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #a0aec0;
    flex-shrink: 0;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.nominee-avatar-placeholder:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.nominee-details h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.nominee-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.category-tag {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.code-tag {
    background: #f7fafc;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
}

.vote-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #e53e3e;
    font-weight: 600;
}

/* Voting Interface */
.voting-interface {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 2rem;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(30px);
    animation: slideInUp 0.8s ease-out 0.2s both;
    position: relative;
}

.voting-interface::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #f5576c);
    border-radius: 2rem;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.voting-interface:hover::after {
    opacity: 0.1;
}

.interface-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.interface-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.interface-subtitle {
    color: #718096;
    font-size: 1rem;
}

/* Vote Input Methods */
.vote-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.vote-method {
    border: 2px solid rgba(226, 232, 240, 0.5);
    border-radius: 1.5rem;
    padding: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    transform: scale(1);
}

.vote-method:hover {
    border-color: #667eea;
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.2);
    transform: translateY(-3px) scale(1.02);
    background: rgba(255, 255, 255, 0.95);
}

.vote-method.active {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(240, 244, 255, 0.9), rgba(232, 240, 254, 0.9));
    transform: scale(1.03);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.25);
}

.method-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.method-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.method-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a202c;
}

.method-description {
    color: #718096;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Custom Vote Input */
.custom-vote-input {
    display: none;
}

.vote-method.active .custom-vote-input {
    display: block;
}

.vote-input-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.vote-counter {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.counter-btn {
    background: #f7fafc;
    border: none;
    padding: 0.75rem;
    cursor: pointer;
    color: #4a5568;
    transition: all 0.2s ease;
    font-size: 1.125rem;
    font-weight: 600;
}

.counter-btn:hover {
    background: #edf2f7;
    color: #2d3748;
}

.counter-input {
    border: none;
    padding: 0.75rem;
    text-align: center;
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a202c;
    width: 80px;
    background: white;
}

.counter-input:focus {
    outline: none;
}

.vote-total {
    color: #667eea;
    font-weight: 600;
    font-size: 1.125rem;
}

/* Package Selection */
.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.package-option {
    border: 2px solid rgba(226, 232, 240, 0.5);
    border-radius: 1.5rem;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    transform: scale(1);
}

.package-option:hover {
    border-color: #667eea;
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.25);
    background: rgba(255, 255, 255, 0.95);
}

.package-option.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(240, 244, 255, 0.9), rgba(232, 240, 254, 0.9));
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
}

.package-popular {
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.package-votes {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.package-price {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

/* Contact Form */
.contact-section {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

.form-group {
    position: relative;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-help {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

/* Vote Summary */
.vote-summary {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.summary-header {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-item:last-child {
    border-bottom: none;
    font-weight: 600;
    font-size: 1.125rem;
    color: #667eea;
}

.summary-label {
    color: #6b7280;
}

.summary-value {
    font-weight: 500;
    color: #1a202c;
}

/* Submit Button */
.submit-section {
    text-align: center;
}

.vote-submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.25rem 3.5rem;
    border-radius: 2rem;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: 250px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.vote-submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.vote-submit-btn:hover::before {
    left: 100%;
}

.vote-submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.vote-submit-btn:disabled {
    background: #cbd5e0;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-loader {
    display: none;
}

.vote-submit-btn.loading .btn-content {
    opacity: 0;
}

.vote-submit-btn.loading .btn-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Alert Styles */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.alert.success {
    background: linear-gradient(135deg, #f0fff4, #dcfce7);
    border-color: #22c55e;
    color: #15803d;
}

.alert.error {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-color: #ef4444;
    color: #dc2626;
}

.alert-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-content h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.alert-content p {
    margin-bottom: 0.25rem;
}

/* Floating Particles Background */
.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle:nth-child(1) { left: 20%; animation-delay: 0s; }
.particle:nth-child(2) { left: 40%; animation-delay: 1s; }
.particle:nth-child(3) { left: 60%; animation-delay: 2s; }
.particle:nth-child(4) { left: 80%; animation-delay: 3s; }
.particle:nth-child(5) { left: 10%; animation-delay: 4s; }
.particle:nth-child(6) { left: 90%; animation-delay: 5s; }

@keyframes float {
    0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
}

/* Enhanced Mobile Experience */
@media (max-width: 768px) {
    .professional-vote-container {
        padding: 1rem;
    }
    
    .vote-methods {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .nominee-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .nominee-avatar, .nominee-avatar-placeholder {
        width: 120px;
        height: 120px;
        font-size: 3rem;
    }
    
    .packages-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .package-option {
        padding: 1rem;
    }
    
    .vote-input-group {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .vote-submit-btn {
        width: 100%;
        padding: 1rem 2rem;
        font-size: 1rem;
    }
    
    .nominee-card, .voting-interface, .contact-section {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .packages-grid {
        grid-template-columns: 1fr;
    }
    
    .nominee-details h1 {
        font-size: 1.5rem;
    }
    
    .interface-title {
        font-size: 1.25rem;
    }
}
</style>





<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="professional-vote-container">
    <!-- Back Navigation -->
    <div class="back-navigation">
        <?php
            require_once __DIR__ . '/../../src/Helpers/SlugHelper.php';
            $eventSlug = \SmartCast\Helpers\SlugHelper::generateEventSlug($event);
        ?>
        <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>/vote" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Nominees
        </a>
    </div>

    <!-- Alert Container -->
    <div id="alert-container"></div>

    <!-- Nominee Information Card -->
    <div class="nominee-card">
        <div class="nominee-header">
            <?php if (!empty($contestant['image_url'])): ?>
                <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                     alt="<?= htmlspecialchars($contestant['name']) ?>"
                     class="nominee-avatar">
            <?php else: ?>
                <div class="nominee-avatar-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            
            <div class="nominee-details">
                <h1><?= htmlspecialchars($contestant['name']) ?></h1>
                <div class="nominee-meta">
                    <?php if (isset($category) && $category): ?>
                        <span class="category-tag">
                            <i class="fas fa-tag"></i>
                            <?= htmlspecialchars($category['name']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($contestant['short_code'])): ?>
                        <span class="code-tag">
                            <i class="fas fa-hashtag"></i>
                            <?= htmlspecialchars($contestant['short_code']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <div class="vote-count">
                        <i class="fas fa-heart"></i>
                        <span><?= number_format($contestant['total_votes'] ?? 0) ?> votes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($contestant['bio'])): ?>
            <p style="color: #6b7280; line-height: 1.6;">
                <?= htmlspecialchars($contestant['bio']) ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- Voting Form -->
    <form id="votingForm">
        <input type="hidden" id="contestant_id" name="contestant_id" value="<?= $contestant['id'] ?>">
        <input type="hidden" id="bundle_id" name="bundle_id">
        <input type="hidden" id="category_id" name="category_id" value="<?= $category['id'] ?? $contestant['category_id'] ?? '' ?>">
        
        <!-- Voting Interface -->
        <div class="voting-interface">
            <div class="interface-header">
                <h2 class="interface-title">Cast Your Vote</h2>
                <p class="interface-subtitle">Choose how you want to vote for this nominee</p>
            </div>
            
            <!-- Vote Methods -->
            <div class="vote-methods">
                <!-- Custom Vote Count -->
                <div class="vote-method" id="custom-method" onclick="selectVoteMethod('custom')">
                    <div class="method-header">
                        <div class="method-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="method-title">Custom Amount</div>
                    </div>
                    <div class="method-description">
                        Enter the exact number of votes you want to cast
                    </div>
                    <div class="custom-vote-input">
                        <div class="vote-input-group">
                            <div class="vote-counter">
                                <button type="button" class="counter-btn" onclick="changeVoteCount(-1)">−</button>
                                <input type="number" id="custom-votes" class="counter-input" value="1" min="1" max="10000" onchange="updateCustomVotes()">
                                <button type="button" class="counter-btn" onclick="changeVoteCount(1)">+</button>
                            </div>
                            <div class="vote-total">
                                Total: GH₵<span id="custom-total"><?= number_format($vote_price, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Package Selection -->
                <div class="vote-method" id="package-method" onclick="selectVoteMethod('package')">
                    <div class="method-header">
                        <div class="method-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="method-title">Vote Packages</div>
                    </div>
                    <div class="method-description">
                        Choose from pre-defined vote packages with better rates
                    </div>
                </div>
            </div>
            
            <!-- Package Options (shown when package method is selected) -->
            <div id="packages-container" style="display: none;">
                <?php if (empty($bundles)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">
                        No vote packages available. Please refresh the page.
                    </p>
                <?php else: ?>
                <div class="packages-grid">
                    <?php foreach ($bundles as $index => $bundle): ?>
                    <div class="package-option" data-bundle-id="<?= $bundle['id'] ?>"
                         onclick="selectPackage(<?= $bundle['id'] ?>, <?= $bundle['votes'] ?>, <?= $bundle['price'] ?>)">
                        
                        <?php if ($index === 1): ?>
                            <div class="package-popular">Most Popular</div>
                        <?php endif; ?>
                        
                        <div class="package-votes"><?= $bundle['votes'] ?></div>
                        <div style="color: #718096; margin-bottom: 1rem;">
                            Vote<?= $bundle['votes'] > 1 ? 's' : '' ?>
                        </div>
                        <div class="package-price">GH₵<?= number_format($bundle['price'], 2) ?></div>
                        <div class="package-unit">GH₵<?= number_format($bundle['price'] / $bundle['votes'], 2) ?> per vote</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="contact-section">
            <div class="interface-header">
                <h3 class="interface-title">Contact Information</h3>
                <p class="interface-subtitle">We need your phone number to process the vote</p>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Mobile Number *</label>
                    <input type="tel" id="msisdn" name="msisdn" class="form-input" 
                           placeholder="+233 24 123 4567" required>
                    <div class="form-help">Include country code (e.g., +233 for Ghana)</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Coupon Code (Optional)</label>
                    <input type="text" id="coupon_code" name="coupon_code" class="form-input" 
                           placeholder="Enter discount code">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Referral Code (Optional)</label>
                    <input type="text" id="referral_code" name="referral_code" class="form-input" 
                           placeholder="Enter referral code">
                </div>
            </div>
        </div>

        <!-- Vote Summary -->
        <div id="vote-summary" class="vote-summary" style="display: none;">
            <div class="summary-header">
                <i class="fas fa-receipt"></i>
                Vote Summary
            </div>
            <div class="summary-items" id="summary-content">
                <!-- Summary will be populated by JavaScript -->
            </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
            <button type="submit" id="vote-button" class="vote-submit-btn" disabled>
                <div class="btn-content">
                    <i class="fas fa-vote-yea"></i>
                    <span>Cast Your Vote</span>
                </div>
                <div class="btn-loader">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </button>
        </div>
    </form>
</div>


<script>
// Professional Voting System JavaScript
let currentVoteMethod = null;
let selectedBundle = null;
let customVoteCount = 1;
const votePrice = <?= $vote_price ?>; // Price per vote from event

// Initialize the voting interface
document.addEventListener('DOMContentLoaded', function() {
    // Set default to custom method
    selectVoteMethod('custom');
    updateCustomVotes();
    
    // Add event listeners
    document.getElementById('msisdn').addEventListener('input', updateSubmitButton);
    document.getElementById('custom-votes').addEventListener('input', updateCustomVotes);
});

function selectVoteMethod(method) {
    // Remove active class from all methods
    document.querySelectorAll('.vote-method').forEach(el => {
        el.classList.remove('active');
    });
    
    // Add active class to selected method
    document.getElementById(method + '-method').classList.add('active');
    
    // Show/hide package container
    const packagesContainer = document.getElementById('packages-container');
    if (method === 'package') {
        packagesContainer.style.display = 'block';
    } else {
        packagesContainer.style.display = 'none';
        // Clear package selection
        document.querySelectorAll('.package-option').forEach(el => {
            el.classList.remove('selected');
        });
        selectedBundle = null;
        document.getElementById('bundle_id').value = '';
    }
    
    currentVoteMethod = method;
    updateVoteSummary();
    updateSubmitButton();
}

function changeVoteCount(delta) {
    const input = document.getElementById('custom-votes');
    let newValue = parseInt(input.value) + delta;
    
    if (newValue < 1) newValue = 1;
    if (newValue > 10000) newValue = 10000;
    
    input.value = newValue;
    updateCustomVotes();
}

function updateCustomVotes() {
    const input = document.getElementById('custom-votes');
    customVoteCount = parseInt(input.value) || 1;
    
    if (customVoteCount < 1) customVoteCount = 1;
    if (customVoteCount > 10000) customVoteCount = 10000;
    
    input.value = customVoteCount;
    
    const total = customVoteCount * votePrice;
    document.getElementById('custom-total').textContent = total.toFixed(2);
    
    updateVoteSummary();
    updateSubmitButton();
}

function selectPackage(bundleId, votes, price) {
    // Remove selection from all packages
    document.querySelectorAll('.package-option').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Select current package
    const selectedPackage = document.querySelector(`[data-bundle-id="${bundleId}"]`);
    selectedPackage.classList.add('selected');
    
    // Store selection
    selectedBundle = { id: bundleId, votes: votes, price: price };
    document.getElementById('bundle_id').value = bundleId;
    
    updateVoteSummary();
    updateSubmitButton();
}

function updateVoteSummary() {
    const summaryDiv = document.getElementById('vote-summary');
    const summaryContent = document.getElementById('summary-content');
    
    let votes = 0;
    let total = 0;
    let method = '';
    
    if (currentVoteMethod === 'custom') {
        votes = customVoteCount;
        total = customVoteCount * votePrice;
        method = 'Custom Amount';
    } else if (currentVoteMethod === 'package' && selectedBundle) {
        votes = selectedBundle.votes;
        total = selectedBundle.price;
        method = 'Package Deal';
    }
    
    if (votes > 0) {
        summaryContent.innerHTML = `
            <div class="summary-item">
                <span class="summary-label">Nominee:</span>
                <span class="summary-value"><?= htmlspecialchars($contestant['name']) ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Method:</span>
                <span class="summary-value">${method}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Votes:</span>
                <span class="summary-value">${votes} vote${votes > 1 ? 's' : ''}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Amount:</span>
                <span class="summary-value">GH₵${total.toFixed(2)}</span>
            </div>
        `;
        summaryDiv.style.display = 'block';
    } else {
        summaryDiv.style.display = 'none';
    }
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('vote-button');
    const msisdn = document.getElementById('msisdn').value;
    
    const hasVotes = (currentVoteMethod === 'custom' && customVoteCount > 0) || 
                     (currentVoteMethod === 'package' && selectedBundle);
    const hasPhone = msisdn.length >= 10;
    
    submitBtn.disabled = !(hasVotes && hasPhone);
}

// Form submission
document.getElementById('votingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const msisdn = document.getElementById('msisdn').value;
    if (!msisdn || msisdn.length < 10) {
        showAlert('Please enter a valid phone number', 'error');
        return;
    }
    
    if (currentVoteMethod === 'package' && !selectedBundle) {
        showAlert('Please select a vote package', 'error');
        return;
    }
    
    if (currentVoteMethod === 'custom' && customVoteCount < 1) {
        showAlert('Please enter a valid number of votes', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('vote-button');
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('contestant_id', <?= $contestant['id'] ?>);
    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('msisdn', msisdn);
    formData.append('coupon_code', document.getElementById('coupon_code').value);
    formData.append('referral_code', document.getElementById('referral_code').value);
    
    if (currentVoteMethod === 'package') {
        formData.append('bundle_id', selectedBundle.id);
    } else {
        // For custom votes, send the vote count and method
        formData.append('custom_votes', customVoteCount);
        formData.append('vote_method', 'custom');
        // Don't send bundle_id for custom votes
    }
    
    // Submit vote
    fetch('<?= APP_URL ?>/events/<?= $eventSlug ?>/vote/process', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.payment_initiated) {
            // Check if we have a payment URL for mobile money
            if (data.payment_url) {
                // Open Paystack in popup for mobile money verification
                showPaymentPopup(data);
            } else {
                // Payment initiated - show payment status
                showPaymentStatus(data);
            }
        } else if (data.success) {
            // Direct success (fallback)
            showAlert(`
                <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
                <div class="alert-content">
                    <h4>Vote Successful! 🎉</h4>
                    <p>Your vote for <strong><?= htmlspecialchars($contestant['name']) ?></strong> has been recorded.</p>
                    <p><strong>Receipt:</strong> ${data.receipt}</p>
                    <p><strong>Votes Cast:</strong> ${data.votes_cast}</p>
                </div>
            `, 'success');
            
            // Redirect to payment status page after 3 seconds
            setTimeout(() => {
                if (data.transaction_id) {
                    window.location.href = '<?= APP_URL ?>/payment/status/' + data.transaction_id;
                } else {
                    // Fallback to voting page if no transaction ID
                    window.location.href = '<?= APP_URL ?>/events/<?= $eventSlug ?>/vote';
                }
            }, 3000);
        } else {
            console.log('Vote failed:', data); // Debug log
            let errorMessage = data.message || 'Please try again.';
            if (data.errors) {
                errorMessage += '<br><small>Errors: ' + JSON.stringify(data.errors) + '</small>';
            }
            if (data.received_data) {
                errorMessage += '<br><small>Received: ' + JSON.stringify(data.received_data) + '</small>';
            }
            
            showAlert(`
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="alert-content">
                    <h4>Vote Failed</h4>
                    <p>${errorMessage}</p>
                </div>
            `, 'error');
        }
    })
    .catch(error => {
        showAlert(`
            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="alert-content">
                <h4>Network Error</h4>
                <p>Please check your connection and try again.</p>
            </div>
        `, 'error');
    })
    .finally(() => {
        // Reset loading state
        submitBtn.classList.remove('loading');
        updateSubmitButton();
    });
});

function showPaymentPopup(paymentData) {
    const alertContainer = document.getElementById('alert-container');
    
    // Show payment popup message
    alertContainer.innerHTML = `
        <div class="alert success">
            <div class="alert-icon"><i class="fas fa-mobile-alt"></i></div>
            <div class="alert-content">
                <h4>Payment Initiated 📱</h4>
                <p>Complete your mobile money payment in the popup window.</p>
                <p><strong>Reference:</strong> ${paymentData.payment_reference}</p>
                <p><strong>Provider:</strong> ${paymentData.provider || 'Mobile Money'}</p>
                <div style="margin-top: 1rem;">
                    <button onclick="openPaymentPopup('${paymentData.payment_url}')" 
                            style="background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-external-link-alt"></i> Open Payment Window
                    </button>
                    <div style="margin-top: 10px;">
                        <div class="payment-status-loader">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span id="popup-status">Waiting for payment completion...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Auto-open popup
    setTimeout(() => {
        openPaymentPopup(paymentData.payment_url);
    }, 1000);
    
    // Don't start status checking for popup payments - wait for popup message instead
    // The popup will send the result via postMessage
}

function showPaymentStatus(paymentData) {
    const alertContainer = document.getElementById('alert-container');
    
    // Show payment initiated message
    alertContainer.innerHTML = `
        <div class="alert success">
            <div class="alert-icon"><i class="fas fa-mobile-alt"></i></div>
            <div class="alert-content">
                <h4>Payment Initiated 📱</h4>
                <p>${paymentData.message}</p>
                <p><strong>Reference:</strong> ${paymentData.payment_reference}</p>
                <div style="margin-top: 1rem;">
                    <div class="payment-status-loader">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span id="status-text">Checking payment status...</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Start checking payment status
    checkPaymentStatus(paymentData.transaction_id, paymentData.status_check_url);
}

function checkPaymentStatus(transactionId, statusUrl) {
    let attempts = 0;
    const maxAttempts = 60; // Check for 5 minutes (every 5 seconds)
    
    const statusChecker = setInterval(() => {
        attempts++;
        
        fetch(statusUrl)
        .then(response => response.json())
        .then(data => {
            const statusText = document.getElementById('status-text');
            
            if (data.success) {
                switch (data.payment_status) {
                    case 'success':
                        clearInterval(statusChecker);
                        showPaymentSuccess(data);
                        break;
                        
                    case 'failed':
                        clearInterval(statusChecker);
                        showPaymentFailed(data);
                        break;
                        
                    case 'expired':
                        clearInterval(statusChecker);
                        showPaymentExpired(data);
                        break;
                        
                    default:
                        // Update both regular status and popup status
                        if (statusText) {
                            statusText.textContent = `Checking payment status... (${attempts}/${maxAttempts})`;
                        }
                        const popupStatus = document.getElementById('popup-status');
                        if (popupStatus) {
                            popupStatus.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Checking payment status... (${attempts}/${maxAttempts})`;
                        }
                }
            }
            
            // Stop checking after max attempts
            if (attempts >= maxAttempts) {
                clearInterval(statusChecker);
                showPaymentTimeout();
            }
        })
        .catch(error => {
            console.error('Status check error:', error);
            if (attempts >= 3) { // Stop after 3 failed attempts
                clearInterval(statusChecker);
                showPaymentError();
            }
        });
    }, 5000); // Check every 5 seconds
}

function showPaymentSuccess(data) {
    // Close payment popup if open
    if (paymentPopup && !paymentPopup.closed) {
        paymentPopup.close();
    }
    
    showAlert(`
        <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
        <div class="alert-content">
            <h4>Payment Successful! 🎉</h4>
            <p>Your vote for <strong><?= htmlspecialchars($contestant['name']) ?></strong> has been recorded.</p>
            <p><strong>Receipt Number:</strong> ${data.receipt_number}</p>
            <p><strong>Amount:</strong> GHS ${data.amount}</p>
            <p><strong>Time:</strong> ${new Date(data.timestamp).toLocaleString()}</p>
        </div>
    `, 'success');
    
    // Redirect to payment status page after 5 seconds
    setTimeout(() => {
        if (data.transaction_id) {
            window.location.href = '<?= APP_URL ?>/payment/status/' + data.transaction_id;
        } else {
            // Fallback to voting page if no transaction ID
            window.location.href = '<?= APP_URL ?>/events/<?= $eventSlug ?>/vote';
        }
    }, 5000);
}

function showPaymentFailed(data) {
    showAlert(`
        <div class="alert-icon"><i class="fas fa-times-circle"></i></div>
        <div class="alert-content">
            <h4>Payment Failed ❌</h4>
            <p>${data.message}</p>
            <p>Please try again or contact support if the problem persists.</p>
        </div>
    `, 'error');
}

// Listen for messages from payment popup
window.addEventListener('message', function(event) {
    if (event.data.type === 'PAYMENT_COMPLETE') {
        const paymentData = event.data.data;
        
        // Close popup if still open
        if (paymentPopup && !paymentPopup.closed) {
            paymentPopup.close();
        }
        
        // Show payment result on the page
        if (paymentData.success) {
            showPaymentSuccess(paymentData);
            
            // Redirect to receipt page after successful payment (like the old simulation)
            setTimeout(() => {
                if (paymentData.transaction_id) {
                    window.location.href = '<?= APP_URL ?>/payment/receipt/' + paymentData.transaction_id;
                } else {
                    // Fallback to payment status page
                    window.location.href = '<?= APP_URL ?>/payment/status/' + (paymentData.transaction_id || 'unknown');
                }
            }, 3000); // Wait 3 seconds to show success message first
        } else {
            showPaymentFailed(paymentData);
        }
    }
});

let paymentPopup = null;

function openPaymentPopup(paymentUrl) {
    // Close existing popup if any
    if (paymentPopup && !paymentPopup.closed) {
        paymentPopup.close();
    }
    
    // Open new popup
    const popupFeatures = 'width=800,height=600,scrollbars=yes,resizable=yes,status=yes,location=yes,toolbar=no,menubar=no';
    paymentPopup = window.open(paymentUrl, 'PaystackPayment', popupFeatures);
    
    // Focus on popup
    if (paymentPopup) {
        paymentPopup.focus();
        
        // Update status
        const statusElement = document.getElementById('popup-status');
        if (statusElement) {
            statusElement.textContent = 'Payment window opened. Complete your mobile money payment.';
        }
        
        // Monitor popup closure
        const checkClosed = setInterval(() => {
            if (paymentPopup.closed) {
                clearInterval(checkClosed);
                const statusElement = document.getElementById('popup-status');
                if (statusElement) {
                    statusElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking payment status...';
                }
            }
        }, 1000);
    } else {
        // Popup blocked
        showAlert(`
            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="alert-content">
                <h4>Popup Blocked</h4>
                <p>Please allow popups for this site and try again.</p>
                <p><a href="${paymentUrl}" target="_blank" style="color: #667eea;">Click here to open payment page manually</a></p>
            </div>
        `, 'error');
    }
}

function showPaymentExpired(data) {
    showAlert(`
        <div class="alert-icon"><i class="fas fa-clock"></i></div>
        <div class="alert-content">
            <h4>Payment Expired ⏰</h4>
            <p>${data.message}</p>
            <p>Please initiate a new payment to complete your vote.</p>
        </div>
    `, 'error');
}

function showPaymentTimeout() {
    showAlert(`
        <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="alert-content">
            <h4>Payment Status Unknown ⚠️</h4>
            <p>We couldn't verify your payment status. Please check your mobile money transaction history.</p>
            <p>If payment was successful, your vote will be counted automatically.</p>
        </div>
    `, 'error');
}

function showPaymentError() {
    showAlert(`
        <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="alert-content">
            <h4>Connection Error 🌐</h4>
            <p>Unable to check payment status due to connection issues.</p>
            <p>Please check your internet connection and refresh the page.</p>
        </div>
    `, 'error');
}

function showAlert(content, type) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `<div class="alert ${type}">${content}</div>`;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Auto-dismiss after 5 seconds for non-success alerts
    if (type !== 'success') {
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
