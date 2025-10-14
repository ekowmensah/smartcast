<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
/* Inline CSS to ensure it loads */
body { 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    color: #333;
}

.voting-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.event-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 20px;
    border-radius: 15px;
    text-align: center;
    margin-bottom: 30px;
}

.event-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.event-stats {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 600;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.voting-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.category-section {
    margin-bottom: 40px;
}

.category-title {
    font-size: 1.3rem;
    font-weight: 500;
    margin-bottom: 15px;
    color: #555;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}

.contestants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.contestant-card {
    background: #f8f9fa;
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.contestant-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.contestant-card.selected {
    border-color: #667eea;
    background: #e8f0fe;
}

.contestant-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 10px;
    display: block;
}

.contestant-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #ddd;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #999;
}

.contestant-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.contestant-code {
    background: #667eea;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    display: inline-block;
    margin-bottom: 5px;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.package-card {
    background: white;
    border: 2px solid #eee;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.package-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.package-card.selected {
    border-color: #667eea;
    background: #e8f0fe;
}

.package-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.vote-count {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 10px;
}

.package-price {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.price-per-vote {
    font-size: 0.9rem;
    color: #666;
}

.form-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #eee;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
}

.vote-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.vote-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.vote-button:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .event-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .contestants-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .packages-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="voting-container">
    <!-- Event Header -->
    <div class="event-header">
        <h1 class="event-title"><?= htmlspecialchars($event['name']) ?></h1>
        <div class="event-stats">
            <div class="stat-item">
                <div class="stat-number"><?= count($contestants) ?></div>
                <div class="stat-label">Contestants</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($contestantsByCategory) ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= date('d', strtotime($event['end_date'])) ?></div>
                <div class="stat-label"><?= date('M Y', strtotime($event['end_date'])) ?></div>
            </div>
        </div>
        
        <!-- Results visibility indicator -->
        <div style="margin-top: 15px; text-align: center;">
            <?php if ($event['results_visible']): ?>
                <span class="badge" style="background: #28a745; color: white; padding: 8px 16px; border-radius: 20px;">
                    <i class="fas fa-eye"></i> Results Visible
                </span>
            <?php else: ?>
                <span class="badge" style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 20px;">
                    <i class="fas fa-eye-slash"></i> Results Hidden
                </span>
            <?php endif; ?>
        </div>
    </div>
    <!-- Alert Container -->
    <div id="alert-container"></div>
    
    <!-- Voting Form -->
    <form id="votingForm">
        <input type="hidden" id="contestant_id" name="contestant_id">
        <input type="hidden" id="bundle_id" name="bundle_id">
        
        <!-- Step 1: Select Contestant -->
        <div class="voting-section">
            <h2 class="section-title">Step 1: Select Contestant</h2>
            
            <?php foreach ($contestantsByCategory as $category): ?>
            <div class="category-section">
                <h3 class="category-title"><?= htmlspecialchars($category['name']) ?> (<?= count($category['contestants']) ?> contestants)</h3>
                
                <div class="contestants-grid">
                    <?php foreach ($category['contestants'] as $contestant): ?>
                    <div class="contestant-card" 
                         data-contestant-id="<?= $contestant['id'] ?>"
                         onclick="selectContestant(<?= $contestant['id'] ?>)">
                        
                        <?php if ($contestant['image_url']): ?>
                            <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                                 alt="<?= htmlspecialchars($contestant['name']) ?>"
                                 class="contestant-image">
                        <?php else: ?>
                            <div class="contestant-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contestant-name"><?= htmlspecialchars($contestant['name']) ?></div>
                        
                        <?php if ($contestant['short_code']): ?>
                            <div class="contestant-code"><?= htmlspecialchars($contestant['short_code']) ?></div>
                        <?php endif; ?>
                        
                        <?php if ($event['results_visible']): ?>
                        <div class="vote-stats">
                            <i class="fas fa-heart" style="color: #ff4444;"></i>
                            <?= number_format($contestant['total_votes'] ?? 0) ?> votes
                        </div>
                        <?php else: ?>
                        <div class="vote-stats">
                            <i class="fas fa-heart" style="color: #ccc;"></i>
                            <span style="color: #999;">Results Hidden</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Step 2: Select Package -->
        <div class="voting-section">
            <h2 class="section-title">Step 2: Select Vote Package</h2>
            
            <div class="packages-grid">
                <?php foreach ($bundles as $bundle): ?>
                <div class="package-card" 
                     data-bundle-id="<?= $bundle['id'] ?>"
                     onclick="selectBundle(<?= $bundle['id'] ?>, <?= $bundle['votes'] ?>, <?= $bundle['price'] ?>)">
                    
                    <div class="package-name"><?= htmlspecialchars($bundle['name']) ?></div>
                    
                    <div class="vote-count"><?= $bundle['votes'] ?></div>
                    <div style="margin-bottom: 10px; color: #666;">Vote<?= $bundle['votes'] > 1 ? 's' : '' ?></div>
                    
                    <div class="package-price">GH₵<?= number_format($bundle['price'], 2) ?></div>
                    <div class="price-per-vote">GH₵<?= number_format($bundle['price'] / $bundle['votes'], 2) ?> per vote</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Step 3: Complete Vote -->
        <div class="form-section">
            <h2 class="section-title">Step 3: Complete Your Vote</h2>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Mobile Number *</label>
                    <input type="tel" id="msisdn" name="msisdn" class="form-input" 
                           placeholder="+233 24 123 4567" required>
                    <small style="color: #666; font-size: 0.9rem;">Include country code (e.g., +233 for Ghana)</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Coupon Code (Optional)</label>
                    <input type="text" id="coupon_code" name="coupon_code" class="form-input" 
                           placeholder="Enter coupon code">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Referral Code (Optional)</label>
                    <input type="text" id="referral_code" name="referral_code" class="form-input" 
                           placeholder="Enter referral code">
                </div>
            </div>
            
            <!-- Vote Summary -->
            <div id="vote-summary" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; display: none;">
                <!-- Summary will be populated by JavaScript -->
            </div>
            
            <button type="submit" id="vote-button" class="vote-button" disabled>
                <span id="button-text">Cast Your Vote</span>
                <span id="button-loader" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Processing...
                </span>
            </button>
        </div>
    </form>
</div>

<script>
// Simple JavaScript for voting functionality
let selectedContestant = null;
let selectedBundle = null;

function selectContestant(contestantId) {
    // Remove previous selections
    document.querySelectorAll('.contestant-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Select current contestant
    const selectedCard = document.querySelector(`[data-contestant-id="${contestantId}"]`);
    selectedCard.classList.add('selected');
    
    // Store selection
    selectedContestant = contestantId;
    document.getElementById('contestant_id').value = contestantId;
    
    updateVoteSummary();
    updateVoteButton();
}

function selectBundle(bundleId, votes, price) {
    // Remove previous selections
    document.querySelectorAll('.package-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Select current bundle
    const selectedCard = document.querySelector(`[data-bundle-id="${bundleId}"]`);
    selectedCard.classList.add('selected');
    
    // Store selection
    selectedBundle = { id: bundleId, votes: votes, price: price };
    document.getElementById('bundle_id').value = bundleId;
    
    updateVoteSummary();
    updateVoteButton();
}

function updateVoteSummary() {
    const summaryDiv = document.getElementById('vote-summary');
    
    if (selectedContestant && selectedBundle) {
        const contestantCard = document.querySelector(`[data-contestant-id="${selectedContestant}"]`);
        const contestantName = contestantCard.querySelector('.contestant-name').textContent;
        
        summaryDiv.innerHTML = `
            <h4 style="margin-bottom: 15px; color: #333;">Vote Summary</h4>
            <div style="margin-bottom: 10px;">
                <strong>Contestant:</strong> ${contestantName}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Package:</strong> ${selectedBundle.votes} vote${selectedBundle.votes > 1 ? 's' : ''}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>Total:</strong> <span style="font-size: 1.2rem; color: #667eea; font-weight: 600;">GH₵${selectedBundle.price.toFixed(2)}</span>
            </div>
        `;
        summaryDiv.style.display = 'block';
    } else {
        summaryDiv.style.display = 'none';
    }
}

function updateVoteButton() {
    const voteButton = document.getElementById('vote-button');
    const msisdn = document.getElementById('msisdn').value;
    
    if (selectedContestant && selectedBundle && msisdn.length >= 10) {
        voteButton.disabled = false;
    } else {
        voteButton.disabled = true;
    }
}

// Form submission
document.getElementById('votingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedContestant || !selectedBundle) {
        alert('Please select a contestant and vote package');
        return;
    }
    
    const msisdn = document.getElementById('msisdn').value;
    if (!msisdn || msisdn.length < 10) {
        alert('Please enter a valid phone number');
        return;
    }
    
    // Show loading state
    const voteButton = document.getElementById('vote-button');
    const buttonText = document.getElementById('button-text');
    const buttonLoader = document.getElementById('button-loader');
    
    buttonText.style.display = 'none';
    buttonLoader.style.display = 'inline';
    voteButton.disabled = true;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('contestant_id', selectedContestant);
    formData.append('bundle_id', selectedBundle.id);
    formData.append('msisdn', msisdn);
    formData.append('coupon_code', document.getElementById('coupon_code').value);
    formData.append('referral_code', document.getElementById('referral_code').value);
    
    // Submit vote
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('alert-container').innerHTML = `
                <div class="alert success">
                    <strong>Vote Successful!</strong> Your vote has been recorded.
                    <br><small>Receipt: <strong>${data.receipt}</strong></small>
                </div>
            `;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            document.getElementById('alert-container').innerHTML = `
                <div class="alert error">
                    <strong>Vote Failed!</strong> ${data.message || 'Please try again.'}
                </div>
            `;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    })
    .catch(error => {
        document.getElementById('alert-container').innerHTML = `
            <div class="alert error">
                <strong>Network Error!</strong> Please check your connection and try again.
            </div>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    })
    .finally(() => {
        // Restore button state
        buttonText.style.display = 'inline';
        buttonLoader.style.display = 'none';
        voteButton.disabled = false;
    });
});

// Update vote button when phone number changes
document.getElementById('msisdn').addEventListener('input', updateVoteButton);
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
