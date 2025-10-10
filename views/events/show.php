<?php include __DIR__ . '/../layout/public_header.php'; ?>

<div class="row">
    <!-- Event Details -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <?php if ($event['featured_image']): ?>
                <img src="<?= htmlspecialchars($event['featured_image']) ?>" 
                     class="card-img-top" 
                     style="height: 300px; object-fit: cover;"
                     alt="<?= htmlspecialchars($event['name']) ?>">
            <?php endif; ?>
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="card-title mb-2"><?= htmlspecialchars($event['name']) ?></h1>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-<?= $event['status'] === 'active' ? 'success' : 'secondary' ?> fs-6">
                                <?= ucfirst($event['status']) ?>
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-code me-1"></i>
                                <?= htmlspecialchars($event['code']) ?>
                            </small>
                        </div>
                    </div>
                    
                    <?php if ($canVote): ?>
                        <a href="<?= APP_URL ?>/events/<?= $event['id'] ?>/vote" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-vote-yea me-2"></i>Vote Now
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($event['description']): ?>
                    <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                <?php endif; ?>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar-alt text-primary me-2"></i>Event Period</h6>
                        <p class="mb-0">
                            <strong>Start:</strong> <?= date('M j, Y H:i', strtotime($event['start_date'])) ?><br>
                            <strong>End:</strong> <?= date('M j, Y H:i', strtotime($event['end_date'])) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle text-info me-2"></i>Voting Status</h6>
                        <p class="mb-0">
                            <?php if ($canVote): ?>
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Voting is currently open
                                </span>
                            <?php elseif (strtotime($event['start_date']) > time()): ?>
                                <span class="text-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    Voting starts <?= date('M j, Y H:i', strtotime($event['start_date'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Voting has ended
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories and Contestants -->
        <?php if (!empty($categories)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tags text-primary me-2"></i>
                        Categories & Contestants
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($categories as $category): ?>
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2"><?= htmlspecialchars($category['name']) ?></h6>
                            
                            <?php 
                            $categoryContestants = array_filter($contestants, function($c) use ($category) {
                                return $c['category_id'] == $category['id'];
                            });
                            ?>
                            
                            <div class="row">
                                <?php foreach ($categoryContestants as $contestant): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card contestant-card h-100">
                                            <?php if ($contestant['image_url']): ?>
                                                <img src="<?= htmlspecialchars($contestant['image_url']) ?>" 
                                                     class="card-img-top" 
                                                     style="height: 150px; object-fit: cover;"
                                                     alt="<?= htmlspecialchars($contestant['name']) ?>">
                                            <?php endif; ?>
                                            
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1"><?= htmlspecialchars($contestant['name']) ?></h6>
                                                <small class="text-muted d-block mb-2">
                                                    Code: <?= htmlspecialchars($contestant['short_code'] ?? $contestant['contestant_code']) ?>
                                                </small>
                                                
                                                <?php if ($event['results_visible']): ?>
                                                    <div class="vote-count-badge">
                                                        <span class="badge bg-success">
                                                            <?= number_format($contestant['total_votes'] ?? 0) ?> votes
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- All Contestants (if no categories) -->
        <?php if (empty($categories) && !empty($contestants)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Contestants
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($contestants as $contestant): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card contestant-card h-100">
                                    <?php if ($contestant['image_url']): ?>
                                        <img src="<?= htmlspecialchars($contestant['image_url']) ?>" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: cover;"
                                             alt="<?= htmlspecialchars($contestant['name']) ?>">
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($contestant['name']) ?></h6>
                                        <small class="text-muted d-block mb-2">
                                            Code: <?= htmlspecialchars($contestant['contestant_code']) ?>
                                        </small>
                                        
                                        <?php if ($contestant['bio']): ?>
                                            <p class="card-text small text-muted">
                                                <?= htmlspecialchars(substr($contestant['bio'], 0, 100)) ?>
                                                <?= strlen($contestant['bio']) > 100 ? '...' : '' ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if ($event['results_visible']): ?>
                                            <div class="vote-count-badge">
                                                <span class="badge bg-success">
                                                    <?= number_format($contestant['total_votes'] ?? 0) ?> votes
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Vote Bundles -->
        <?php if ($canVote && !empty($bundles)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-cart text-success me-2"></i>
                        Vote Packages
                    </h6>
                </div>
                <div class="card-body">
                    <?php foreach ($bundles as $bundle): ?>
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                            <div>
                                <strong><?= htmlspecialchars($bundle['name']) ?></strong>
                                <br>
                                <small class="text-muted"><?= $bundle['votes'] ?> vote<?= $bundle['votes'] > 1 ? 's' : '' ?></small>
                            </div>
                            <div class="text-end">
                                <div class="h6 mb-0 text-success">$<?= number_format($bundle['price'], 2) ?></div>
                                <small class="text-muted">
                                    $<?= number_format($bundle['price'] / $bundle['votes'], 2) ?>/vote
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <a href="<?= APP_URL ?>/events/<?= $event['id'] ?>/vote" 
                       class="btn btn-success w-100 mt-3">
                        <i class="fas fa-vote-yea me-2"></i>Start Voting
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Leaderboard -->
        <?php if ($event['results_visible'] && !empty($leaderboard)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Leaderboard
                    </h6>
                </div>
                <div class="card-body">
                    <?php foreach (array_slice($leaderboard, 0, 5) as $index => $leader): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="position-badge me-3">
                                <?php if ($index === 0): ?>
                                    <i class="fas fa-crown text-warning"></i>
                                <?php elseif ($index === 1): ?>
                                    <i class="fas fa-medal text-secondary"></i>
                                <?php elseif ($index === 2): ?>
                                    <i class="fas fa-medal text-warning"></i>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark"><?= $index + 1 ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= htmlspecialchars($leader['name']) ?></div>
                                <small class="text-muted">
                                    <?= number_format($leader['total_votes']) ?> votes
                                </small>
                            </div>
                            
                            <?php if ($leader['image_url']): ?>
                                <img src="<?= htmlspecialchars($leader['image_url']) ?>" 
                                     class="rounded-circle" 
                                     width="40" height="40"
                                     alt="<?= htmlspecialchars($leader['name']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (count($leaderboard) > 5): ?>
                        <button class="btn btn-outline-primary btn-sm w-100" onclick="showFullLeaderboard()">
                            <i class="fas fa-list me-2"></i>View Full Leaderboard
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Event Info -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Event Information
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Total Contestants</small>
                    <strong><?= count($contestants) ?></strong>
                </div>
                
                <?php if (!empty($categories)): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Categories</small>
                        <strong><?= count($categories) ?></strong>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Vote Packages</small>
                    <strong><?= count($bundles) ?></strong>
                </div>
                
                <div class="mb-0">
                    <small class="text-muted d-block">Event Code</small>
                    <code><?= htmlspecialchars($event['code']) ?></code>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contestant-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.contestant-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.position-badge {
    width: 30px;
    text-align: center;
}

.vote-count-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.contestant-card {
    position: relative;
}
</style>

<script>
function showFullLeaderboard() {
    // This would show a modal with the full leaderboard
    alert('Full leaderboard feature would be implemented here');
}

// Auto-refresh leaderboard if results are visible
<?php if ($event['results_visible']): ?>
setInterval(function() {
    // This would refresh the leaderboard via AJAX
    console.log('Refreshing leaderboard...');
}, 30000); // Every 30 seconds
<?php endif; ?>
</script>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
