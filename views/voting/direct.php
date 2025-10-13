<?php include __DIR__ . '/../layout/public_header.php'; ?>

<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-2">
                    <i class="fas fa-vote-yea me-3"></i>
                    Cast Your Vote
                </h1>
                <p class="lead mb-0">You're voting for <strong><?= htmlspecialchars($contestant['name']) ?></strong></p>
                <?php if ($source === 'shortcode'): ?>
                    <small class="text-white-75">
                        <i class="fas fa-hashtag me-1"></i>
                        Found via shortcode: <span class="badge bg-white text-primary"><?= htmlspecialchars($contestantCategory['short_code'] ?? '') ?></span>
                    </small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Voting Form Section -->
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Nominee Information -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <?php if ($contestant['image_url']): ?>
                                <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                                     alt="<?= htmlspecialchars($contestant['name']) ?>" 
                                     class="img-fluid rounded-circle nominee-image">
                            <?php else: ?>
                                <div class="nominee-placeholder bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-3"><?= htmlspecialchars($contestant['name']) ?></h2>
                            
                            <div class="nominee-details mb-3">
                                <p class="mb-2">
                                    <i class="fas fa-trophy text-primary me-2"></i>
                                    <strong>Event:</strong> <?= htmlspecialchars($event['name']) ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    <strong>Category:</strong> <?= htmlspecialchars($category['name']) ?>
                                </p>
                                <?php if ($contestantCategory && $contestantCategory['short_code']): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-hashtag text-primary me-2"></i>
                                        <strong>Shortcode:</strong> 
                                        <span class="badge bg-primary"><?= htmlspecialchars($contestantCategory['short_code']) ?></span>
                                    </p>
                                <?php endif; ?>
                                <p class="mb-0">
                                    <i class="fas fa-dollar-sign text-success me-2"></i>
                                    <strong>Vote Price:</strong> $<?= number_format($event['vote_price'], 2) ?> per vote
                                </p>
                            </div>

                            <?php if ($contestant['bio']): ?>
                                <div class="nominee-bio">
                                    <h6 class="text-muted mb-2">About <?= htmlspecialchars($contestant['name']) ?>:</h6>
                                    <p class="text-muted"><?= nl2br(htmlspecialchars($contestant['bio'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Voting Form -->
                    <form id="voteForm" action="<?= APP_URL ?>/vote/process" method="POST">
                        <input type="hidden" name="contestant_id" value="<?= $contestant['id'] ?>">
                        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <input type="hidden" name="source" value="<?= htmlspecialchars($source) ?>">

                        <div class="row">
                            <!-- Vote Quantity Selection -->
                            <div class="col-md-6 mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-calculator text-primary me-2"></i>
                                    Select Vote Quantity
                                </h5>

                                <?php if (!empty($bundles)): ?>
                                    <!-- Vote Bundles -->
                                    <div class="vote-bundles mb-3">
                                        <?php foreach ($bundles as $bundle): ?>
                                            <div class="bundle-option">
                                                <input type="radio" 
                                                       class="btn-check" 
                                                       name="bundle_id" 
                                                       id="bundle_<?= $bundle['id'] ?>" 
                                                       value="<?= $bundle['id'] ?>"
                                                       data-votes="<?= $bundle['votes'] ?>"
                                                       data-price="<?= $bundle['price'] ?>">
                                                <label class="btn btn-outline-primary bundle-label" for="bundle_<?= $bundle['id'] ?>">
                                                    <div class="bundle-info">
                                                        <div class="bundle-votes"><?= $bundle['votes'] ?> Votes</div>
                                                        <div class="bundle-price">$<?= number_format($bundle['price'], 2) ?></div>
                                                        <?php if (isset($bundle['discount']) && $bundle['discount'] > 0): ?>
                                                            <div class="bundle-savings">Save <?= $bundle['discount'] ?>%</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="text-center">
                                        <small class="text-muted">Or choose custom quantity below</small>
                                    </div>
                                <?php endif; ?>

                                <!-- Custom Vote Quantity -->
                                <div class="custom-votes mt-3">
                                    <label for="vote_quantity" class="form-label">Custom Vote Quantity:</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseVotes">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               class="form-control text-center" 
                                               id="vote_quantity" 
                                               name="vote_quantity" 
                                               value="1" 
                                               min="1" 
                                               max="1000">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseVotes">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div class="col-md-6 mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-receipt text-success me-2"></i>
                                    Payment Summary
                                </h5>

                                <div class="payment-summary bg-light p-3 rounded">
                                    <div class="summary-row d-flex justify-content-between mb-2">
                                        <span>Vote Price:</span>
                                        <span>$<?= number_format($event['vote_price'], 2) ?></span>
                                    </div>
                                    <div class="summary-row d-flex justify-content-between mb-2">
                                        <span>Quantity:</span>
                                        <span id="summaryQuantity">1</span>
                                    </div>
                                    <div class="summary-row d-flex justify-content-between mb-2" id="discountRow" style="display: none;">
                                        <span class="text-success">Bundle Discount:</span>
                                        <span class="text-success" id="summaryDiscount">-$0.00</span>
                                    </div>
                                    <hr>
                                    <div class="summary-total d-flex justify-content-between">
                                        <strong>Total Amount:</strong>
                                        <strong class="text-primary" id="summaryTotal">$<?= number_format($event['vote_price'], 2) ?></strong>
                                    </div>
                                </div>

                                <!-- Voter Information -->
                                <div class="voter-info mt-4">
                                    <h6 class="mb-3">Voter Information:</h6>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label for="voter_name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="voter_name" name="voter_name" required>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="voter_phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="voter_phone" name="voter_phone" required>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="voter_email" class="form-label">Email (Optional)</label>
                                            <input type="email" class="form-control" id="voter_email" name="voter_email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg vote-submit-btn">
                                <i class="fas fa-credit-card me-2"></i>
                                Proceed to Payment
                            </button>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Secure payment processing
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to Search -->
<div class="container mb-4">
    <div class="text-center">
        <a href="<?= APP_URL ?>/vote-shortcode" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Search Another Shortcode
        </a>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.nominee-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
}

.nominee-placeholder {
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.bundle-option {
    margin-bottom: 10px;
}

.bundle-label {
    width: 100%;
    padding: 15px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.bundle-label:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.bundle-info {
    text-align: center;
}

.bundle-votes {
    font-size: 1.2rem;
    font-weight: bold;
}

.bundle-price {
    font-size: 1.1rem;
    color: #28a745;
    font-weight: 600;
}

.bundle-savings {
    font-size: 0.9rem;
    color: #dc3545;
    font-weight: 500;
}

.btn-check:checked + .bundle-label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

.payment-summary {
    border: 2px solid #e9ecef;
}

.vote-submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.vote-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.nominee-details p {
    font-size: 1rem;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteQuantityInput = document.getElementById('vote_quantity');
    const bundleInputs = document.querySelectorAll('input[name="bundle_id"]');
    const decreaseBtn = document.getElementById('decreaseVotes');
    const increaseBtn = document.getElementById('increaseVotes');
    const summaryQuantity = document.getElementById('summaryQuantity');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryDiscount = document.getElementById('summaryDiscount');
    const discountRow = document.getElementById('discountRow');
    
    const votePrice = <?= $event['vote_price'] ?>;
    
    // Handle bundle selection
    bundleInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                const votes = parseInt(this.dataset.votes);
                const price = parseFloat(this.dataset.price);
                
                voteQuantityInput.value = votes;
                updateSummary(votes, price, true);
            }
        });
    });
    
    // Handle custom quantity changes
    voteQuantityInput.addEventListener('input', function() {
        clearBundleSelection();
        const quantity = parseInt(this.value) || 1;
        const total = quantity * votePrice;
        updateSummary(quantity, total, false);
    });
    
    // Increase/decrease buttons
    decreaseBtn.addEventListener('click', function() {
        const current = parseInt(voteQuantityInput.value) || 1;
        if (current > 1) {
            voteQuantityInput.value = current - 1;
            voteQuantityInput.dispatchEvent(new Event('input'));
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        const current = parseInt(voteQuantityInput.value) || 1;
        voteQuantityInput.value = current + 1;
        voteQuantityInput.dispatchEvent(new Event('input'));
    });
    
    function updateSummary(quantity, total, isBundle) {
        summaryQuantity.textContent = quantity;
        summaryTotal.textContent = '$' + total.toFixed(2);
        
        if (isBundle) {
            const regularPrice = quantity * votePrice;
            const discount = regularPrice - total;
            
            if (discount > 0) {
                summaryDiscount.textContent = '-$' + discount.toFixed(2);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        } else {
            discountRow.style.display = 'none';
        }
    }
    
    function clearBundleSelection() {
        bundleInputs.forEach(input => {
            input.checked = false;
        });
    }
    
    // Form validation
    document.getElementById('voteForm').addEventListener('submit', function(e) {
        const voterName = document.getElementById('voter_name').value.trim();
        const voterPhone = document.getElementById('voter_phone').value.trim();
        
        if (!voterName || !voterPhone) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
        
        if (parseInt(voteQuantityInput.value) < 1) {
            e.preventDefault();
            alert('Please select at least 1 vote');
            return false;
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
