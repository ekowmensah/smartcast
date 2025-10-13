<?php include __DIR__ . '/../layout/public_header.php'; ?>

<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-trophy me-3"></i>
                    Live Voting Events
                </h1>
                <p class="lead mb-4">Discover and participate in exciting voting competitions. Cast your votes and support your favorite contestants!</p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <div class="h3 fw-bold"><?= count($events ?? []) ?></div>
                        <small class="text-white-50">Active Events</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">24/7</div>
                        <small class="text-white-50">Live Voting</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">Real-time</div>
                        <small class="text-white-50">Results</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="hero-icon">
                    <i class="fas fa-vote-yea fa-5x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="container mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-body py-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchEvents" 
                               placeholder="Search events by name or description...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-lg" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">🟢 Active</option>
                        <option value="upcoming">🟡 Upcoming</option>
                        <option value="ended">🔴 Ended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-grid">
                        <button class="btn btn-outline-primary btn-lg" onclick="resetFilters()">
                            <i class="fas fa-undo me-2"></i>Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

<?php if (!empty($events)): ?>
    <div class="row" id="eventsContainer">
        <?php foreach ($events as $event): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4 event-card" data-event-id="<?= $event['id'] ?>" data-status="<?= $event['status'] ?>">
                <div class="card h-100 border-0 shadow-sm event-card-hover">
                    <div class="position-relative overflow-hidden">
                        <?php if ($event['featured_image']): ?>
                            <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" 
                                 class="card-img-top event-image" 
                                 alt="<?= htmlspecialchars($event['name']) ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center event-image">
                                <i class="fas fa-calendar-alt fa-3x text-white opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge badge-status <?= $event['status'] === 'active' ? 'bg-success' : ($event['status'] === 'upcoming' ? 'bg-warning' : 'bg-secondary') ?>">
                                <?php if ($event['status'] === 'active'): ?>
                                    <i class="fas fa-circle me-1 pulse"></i>LIVE
                                <?php else: ?>
                                    <?= ucfirst($event['status']) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <?php
                            require_once __DIR__ . '/../../src/Helpers/SlugHelper.php';
                            $eventSlug = \SmartCast\Helpers\SlugHelper::generateEventSlug($event);
                        ?>
                    </div>
                    
                    <div class="card-body px-3 pt-3 pb-1 d-flex flex-column">
                        <!-- Event Header -->
                        <div class="event-header mb-2">
                            <h6 class="event-title mb-1">
                                <?= htmlspecialchars($event['name']) ?>
                                <?php if ($event['status'] === 'active'): ?>
                                    <span class="live-badge">LIVE</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        
                        <!-- Ultra Compact Stats -->
                        <div class="ultra-stats mb-2">
                            <?php
                                // Calculate days left
                                $endDate = new DateTime($event['end_date']);
                                $today = new DateTime();
                                $daysLeft = max(0, $today->diff($endDate)->days);
                                if ($endDate < $today) $daysLeft = 0;
                            ?>
                            <div class="stats-row">
                                <span class="stat-item"><?= $event['contestant_count'] ?? 0 ?> Contestants</span>
                                <span class="stat-divider">•</span>
                                <span class="stat-item"><?= number_format($event['total_votes'] ?? 0) ?> Votes</span>
                                <span class="stat-divider">•</span>
                                <span class="stat-item"><?= $daysLeft ?> Days Left</span>
                            </div>
                        </div>
                        
                        <!-- Minimal Dates -->
                        <div class="mini-dates">
                            <div class="date-row">
                                <span class="date-label">Started:</span> <?= date('M j', strtotime($event['start_date'])) ?>
                                <span class="date-separator">|</span>
                                <span class="date-label">Ends:</span> <?= date('M j, g:i A', strtotime($event['end_date'])) ?>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto" style="margin-top: 8px !important; margin-bottom: 0 !important;">
                            <?php 
                            $canVote = (strtotime($event['start_date']) <= time() && 
                                       strtotime($event['end_date']) >= time() && 
                                       $event['status'] === 'active');
                            ?>
                            <div class="compact-actions">
                                <?php if ($canVote): ?>
                                    <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>/vote" 
                                       class="vote-btn">
                                        VOTE NOW
                                    </a>
                                    <?php if ($event['results_visible']): ?>
                                        <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>" 
                                           class="results-btn">
                                            Results
                                        </a>
                                    <?php endif; ?>
                                <?php elseif (strtotime($event['start_date']) > time()): ?>
                                    <span class="coming-soon">Starts Soon</span>
                                <?php else: ?>
                                    <?php if ($event['results_visible']): ?>
                                        <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>" 
                                           class="results-btn primary">
                                            View Results
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Load More Button -->
    <div class="text-center mt-4">
        <button class="btn btn-outline-primary" id="loadMoreEvents">
            <i class="fas fa-plus me-2"></i>Load More Events
        </button>
    </div>
    
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
        <h3 class="text-muted mb-3">No Active Events</h3>
        <p class="text-muted mb-4">There are currently no public voting events available.</p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= APP_URL ?>/admin/events/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Your First Event
            </a>
        <?php else: ?>
            <a href="<?= APP_URL ?>/register" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Get Started
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

</div> <!-- Close container -->

<!-- Event Statistics -->
<?php if (!empty($events)): ?>
<div class="container">
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary mb-0"><?= count($events) ?></h4>
                            <small class="text-muted">Active Events</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success mb-0">
                                <?= count(array_filter($events, function($e) { 
                                    return strtotime($e['start_date']) <= time() && 
                                           strtotime($e['end_date']) >= time() && 
                                           $e['status'] === 'active'; 
                                })) ?>
                            </h4>
                            <small class="text-muted">Currently Voting</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning mb-0">
                                <?= count(array_filter($events, function($e) { 
                                    return strtotime($e['start_date']) > time(); 
                                })) ?>
                            </h4>
                            <small class="text-muted">Upcoming</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info mb-0">24/7</h4>
                            <small class="text-muted">Platform Availability</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- Close container -->
<?php endif; ?>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease-in-out;
}

/* Ultra Compact & Stylish Design */
.event-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.2;
}

.live-badge {
    display: inline-block;
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 8px;
    margin-left: 8px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.ultra-stats .stats-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    font-size: 0.8rem;
    color: #555;
}

.stat-item {
    font-weight: 500;
}

.stat-divider {
    color: #bbb;
    font-weight: bold;
}

.mini-dates .date-row {
    font-size: 0.75rem;
    color: #777;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.date-label {
    font-weight: 600;
    color: #555;
}

.date-separator {
    color: #ccc;
}

.compact-actions {
    display: flex;
    gap: 1px;
    align-items: center;
    margin: 0;
    padding: 0;
    margin-bottom: 0 !important;
}

.vote-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 14px;
    border-radius: 18px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    line-height: 1.2;
}

.vote-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.results-btn {
    background: transparent;
    color: #667eea;
    border: 1px solid #667eea;
    padding: 6px 12px;
    border-radius: 16px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.results-btn:hover {
    background: #667eea;
    color: white;
}

.results-btn.primary {
    background: #667eea;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
}

.coming-soon {
    background: #ffeaa7;
    color: #d63031;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Card Enhancements */
.event-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.event-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.hover-shadow:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    transform: translateY(-2px);
}

.event-card {
    transition: all 0.3s ease-in-out;
}

.event-meta {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

/* Professional Modern Styling */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.hero-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Event Cards Professional Styling */
.event-card-hover {
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-radius: 16px !important;
    overflow: hidden;
}

.event-card-hover:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.event-image {
    height: 200px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.event-card-hover:hover .event-image {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.event-card-hover:hover .card-overlay {
    opacity: 1;
}

.overlay-content {
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.event-card-hover:hover .overlay-content {
    transform: translateY(0);
}

.badge-status {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.event-stats {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
    padding: 1rem;
    margin: 1rem 0;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.event-dates {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 8px;
    padding: 0.75rem;
}

/* Responsive optimizations */
@media (min-width: 992px) {
    .event-card .card {
        min-height: auto;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .event-card .card {
        min-height: auto;
    }
}

@media (max-width: 767px) {
    .event-card .card {
        min-height: auto;
    }
    
    .hero-section {
        padding: 3rem 0 !important;
    }
    
    .display-4 {
        font-size: 2rem !important;
    }
}

/* Button enhancements */
.btn-lg {
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
}

/* Search section styling */
.input-group-lg .input-group-text {
    border-radius: 12px 0 0 12px;
}

.input-group-lg .form-control {
    border-radius: 0 12px 12px 0;
    border-left: none;
}

.form-select-lg {
    border-radius: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchEvents');
    const eventCards = document.querySelectorAll('.event-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        eventCards.forEach(card => {
            const eventName = card.querySelector('.card-title').textContent.toLowerCase();
            const eventDesc = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            
            if (eventName.includes(searchTerm) || eventDesc.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    const filterLinks = document.querySelectorAll('[data-filter]');
    
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            
            eventCards.forEach(card => {
                const eventId = card.dataset.eventId;
                // This would need to be enhanced with actual event status data
                card.style.display = '';
            });
        });
    });
    
    // Status filter functionality
    const statusFilter = document.getElementById('statusFilter');
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        eventCards.forEach(card => {
            const cardStatus = card.dataset.status;
            
            if (!selectedStatus || cardStatus === selectedStatus) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Load more functionality (placeholder)
    const loadMoreBtn = document.getElementById('loadMoreEvents');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // This would load more events via AJAX
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-plus me-2"></i>Load More Events';
            }, 1000);
        });
    }
});

// Reset filters function
function resetFilters() {
    document.getElementById('searchEvents').value = '';
    document.getElementById('statusFilter').value = '';
    
    document.querySelectorAll('.event-card').forEach(card => {
        card.style.display = '';
    });
}
</script>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
