<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
/* Inline CSS for nominee selection */
body { 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    color: #333;
}

.nominee-container {
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

.event-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 20px;
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

.category-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.category-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 3px solid #667eea;
    padding-bottom: 10px;
}

.nominees-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
}

.nominee-card {
    background: #f8f9fa;
    border: 3px solid transparent;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}

.nominee-card:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.nominee-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 15px;
    display: block;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.nominee-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ddd, #f0f0f0);
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #999;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.nominee-name {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.nominee-code {
    background: #667eea;
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9rem;
    display: inline-block;
    margin-bottom: 10px;
    font-weight: 500;
}

.nominee-bio {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.4;
    margin-bottom: 15px;
}

.vote-stats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #ff4444;
    font-weight: 500;
    font-size: 1rem;
}

.vote-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 15px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nominee-card:hover .vote-button {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 20px;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #5a67d8;
    text-decoration: none;
}

@media (max-width: 768px) {
    .event-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .nominees-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .nominee-image, .nominee-placeholder {
        width: 100px;
        height: 100px;
    }
    
    .nominee-placeholder {
        font-size: 2.5rem;
    }
}
</style>

<div class="nominee-container">
    <!-- Back Link -->
    <a href="<?= APP_URL ?>/events" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Events
    </a>

    <!-- Event Header -->
    <div class="event-header">
        <h1 class="event-title"><?= htmlspecialchars($event['name']) ?></h1>
        <p class="event-subtitle">Choose your favorite nominee to vote for</p>
        <div class="event-stats">
            <div class="stat-item">
                <div class="stat-number"><?= count($contestants) ?></div>
                <div class="stat-label">Nominees</div>
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

    <!-- Categories and Nominees -->
    <?php foreach ($contestantsByCategory as $category): ?>
    <div class="category-section">
        <h2 class="category-title">
            <?= htmlspecialchars($category['name']) ?>
            <span style="font-size: 1rem; font-weight: 400; color: #666; margin-left: 10px;">
                (<?= count($category['contestants']) ?> nominees)
            </span>
        </h2>
        
        <div class="nominees-grid">
            <?php foreach ($category['contestants'] as $contestant): ?>
            <?php
                // Generate slugs for URLs
                require_once __DIR__ . '/../../src/Helpers/SlugHelper.php';
                $eventSlug = \SmartCast\Helpers\SlugHelper::generateEventSlug($event);
                $contestantSlug = \SmartCast\Helpers\SlugHelper::generateContestantSlug($contestant['name'], $contestant['id']);
            ?>
            <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>/vote/<?= $contestantSlug ?>?category=<?= $category['id'] ?>" class="nominee-card">
                <?php if ($contestant['image_url']): ?>
                    <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                         alt="<?= htmlspecialchars($contestant['name']) ?>"
                         class="nominee-image">
                <?php else: ?>
                    <div class="nominee-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                
                <div class="nominee-name"><?= htmlspecialchars($contestant['name']) ?></div>
                
                <?php if ($contestant['short_code']): ?>
                    <div class="nominee-code"><?= htmlspecialchars($contestant['short_code']) ?></div>
                <?php endif; ?>
                
                <?php if ($contestant['bio']): ?>
                    <div class="nominee-bio">
                        <?= htmlspecialchars(substr($contestant['bio'], 0, 100)) ?>
                        <?= strlen($contestant['bio']) > 100 ? '...' : '' ?>
                    </div>
                <?php endif; ?>
                
                <div class="category-badge" style="background: #e9ecef; color: #666; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; margin-bottom: 10px; display: inline-block;">
                    <?= htmlspecialchars($category['name']) ?>
                </div>
                
                <div class="vote-stats">
                    <?php if ($event['results_visible']): ?>
                        <i class="fas fa-heart"></i>
                        <span><?= number_format($contestant['total_votes'] ?? 0) ?> votes</span>
                    <?php else: ?>
                        <i class="fas fa-heart" style="color: #ccc;"></i>
                        <span style="color: #999;">Results Hidden</span>
                    <?php endif; ?>
                </div>
                
                <button class="vote-button">
                    <i class="fas fa-vote-yea"></i>
                    Vote Now
                </button>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
