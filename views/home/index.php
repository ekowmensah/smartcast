<?php include __DIR__ . '/../layout/public_header.php'; ?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0.9;"></div>
    <div class="container position-relative py-5">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <div class="hero-content text-white">
                    <h1 class="display-3 fw-bold mb-4 animate-fade-in">
                        The Future of 
                        <span class="text-warning">Digital Voting</span>
                    </h1>
                    <p class="lead mb-4 animate-fade-in-delay">
                        Empower your events with our professional voting platform. Create engaging competitions, 
                        manage contestants, and deliver real-time results with complete transparency.
                    </p>
                    
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="hero-actions animate-fade-in-delay-2">
                            <a href="<?= APP_URL ?>/register" class="btn btn-warning btn-lg me-3 shadow-lg">
                                <i class="fas fa-rocket me-2"></i>Start Free Trial
                            </a>
                            <a href="<?= APP_URL ?>/events" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-play me-2"></i>View Demo
                            </a>
                        </div>
                        
                        <div class="hero-stats mt-5">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="fw-bold text-warning" data-count="<?= $stats['total_events_raw'] ?>"><?= $stats['total_events'] ?></h3>
                                        <p class="small mb-0">Events Created</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="fw-bold text-warning" data-count="<?= $stats['total_votes_raw'] ?>"><?= $stats['total_votes'] ?></h3>
                                        <p class="small mb-0">Votes Cast</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <h3 class="fw-bold text-warning"><?= $stats['uptime'] ?></h3>
                                        <p class="small mb-0">Uptime</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="hero-actions animate-fade-in-delay-2">
                            <a href="<?= ORGANIZER_URL ?>" class="btn btn-warning btn-lg me-3 shadow-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                            <a href="<?= APP_URL ?>/events" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-calendar me-2"></i>Browse Events
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-visual text-center animate-float">
                    <div class="voting-mockup position-relative">
                        <div class="mockup-card bg-white rounded-3 shadow-lg p-4 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary rounded-circle me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-trophy text-white" style="line-height: 40px;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark">
                                        <?php if (!empty($events)): ?>
                                            <?= htmlspecialchars($events[0]['name'] ?? 'Live Event') ?>
                                        <?php else: ?>
                                            Sample Voting Event
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted">Live Voting in Progress</small>
                                </div>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success progress-bar-animated" style="width: 75%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <?php 
                                    $sampleVotes = max(1, intval($stats['total_votes_raw'] / max(1, $stats['total_events_raw'])));
                                    echo number_format($sampleVotes) . ' votes';
                                    ?>
                                </small>
                                <small class="text-success fw-bold">
                                    <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Live
                                </small>
                            </div>
                        </div>
                        
                        <div class="mockup-card bg-white rounded-3 shadow-lg p-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="metric">
                                        <h4 class="text-primary mb-1 engagement-rate"><?= $stats['engagement_rate'] ?></h4>
                                        <small class="text-muted">Engagement</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric">
                                        <h4 class="text-success mb-1 live-events-count"><?= $stats['active_events'] ?></h4>
                                        <small class="text-muted">Live Events</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric">
                                        <h4 class="text-warning mb-1"><?= $stats['total_contestants'] ?></h4>
                                        <small class="text-muted">Contestants</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Everything You Need for Professional Voting</h2>
                <p class="lead text-muted">Comprehensive tools to create, manage, and analyze voting events with enterprise-grade security and reliability.</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-primary bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Smart Event Management</h5>
                    <p class="text-muted mb-3">Create and customize voting events with advanced scheduling, category management, and automated workflows.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>Multi-category support</li>
                        <li><i class="fas fa-check text-success me-2"></i>Automated scheduling</li>
                        <li><i class="fas fa-check text-success me-2"></i>Custom branding</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-success bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Contestant Profiles</h5>
                    <p class="text-muted mb-3">Comprehensive contestant management with rich profiles, media galleries, and performance tracking.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>Rich media support</li>
                        <li><i class="fas fa-check text-success me-2"></i>Bulk import/export</li>
                        <li><i class="fas fa-check text-success me-2"></i>Social integration</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-info bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Real-time Analytics</h5>
                    <p class="text-muted mb-3">Advanced analytics dashboard with live vote tracking, engagement metrics, and detailed reporting.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>Live vote tracking</li>
                        <li><i class="fas fa-check text-success me-2"></i>Engagement analytics</li>
                        <li><i class="fas fa-check text-success me-2"></i>Export reports</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-warning bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Enterprise Security</h5>
                    <p class="text-muted mb-3">Bank-level security with fraud detection, vote verification, and comprehensive audit trails.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>Fraud detection</li>
                        <li><i class="fas fa-check text-success me-2"></i>Vote verification</li>
                        <li><i class="fas fa-check text-success me-2"></i>Audit trails</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-danger bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-hashtag fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Shortcode Voting</h5>
                    <p class="text-muted mb-3">Unique shortcode system for easy voting via SMS, social media, or direct entry.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>SMS integration</li>
                        <li><i class="fas fa-check text-success me-2"></i>Social sharing</li>
                        <li><i class="fas fa-check text-success me-2"></i>QR code support</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 bg-white rounded-3 shadow-sm p-4 text-center border-0 hover-lift">
                    <div class="feature-icon bg-dark bg-gradient rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-mobile-alt fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Mobile Optimized</h5>
                    <p class="text-muted mb-3">Fully responsive design optimized for mobile voting with progressive web app capabilities.</p>
                    <ul class="list-unstyled text-start small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>PWA support</li>
                        <li><i class="fas fa-check text-success me-2"></i>Offline capability</li>
                        <li><i class="fas fa-check text-success me-2"></i>Touch optimized</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Active Events Section -->
<?php if (!empty($events)): ?>
<section class="events-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">
                    <i class="fas fa-fire text-danger me-2"></i>
                    Live Voting Events
                </h2>
                <p class="lead text-muted">Join thousands of voters in these exciting competitions happening right now!</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($events as $event): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="event-card h-100 bg-white rounded-3 shadow-sm border-0 overflow-hidden hover-lift">
                        <?php if ($event['featured_image']): ?>
                            <div class="event-image position-relative">
                                <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" 
                                     class="w-100" 
                                     style="height: 200px; object-fit: cover;"
                                     alt="<?= htmlspecialchars($event['name']) ?>">
                                <div class="event-badge position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Live
                                    </span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="event-placeholder bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-calendar-alt fa-3x text-white opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3"><?= htmlspecialchars($event['name']) ?></h5>
                            
                            <?php if ($event['description']): ?>
                                <p class="card-text text-muted mb-3">
                                    <?= htmlspecialchars(substr($event['description'], 0, 120)) ?>
                                    <?= strlen($event['description']) > 120 ? '...' : '' ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="event-meta mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <small class="text-muted">
                                        <?= date('M j, Y', strtotime($event['start_date'])) ?>
                                    </small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-success me-2"></i>
                                    <small class="text-muted">Active voting in progress</small>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <a href="<?= APP_URL ?>/events/<?= $event['id'] ?>" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-vote-yea me-2"></i>Vote Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= APP_URL ?>/events" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-calendar me-2"></i>View All Events
            </a>
        </div>
    </div>
</section>
<?php else: ?>
<!-- No Events Section -->
<section class="no-events-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="no-events-content">
                    <div class="no-events-icon mb-4">
                        <i class="fas fa-calendar-plus fa-4x text-primary opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-3">No Active Events Right Now</h3>
                    <p class="text-muted mb-4">
                        Be the first to know when exciting voting events go live! 
                        Check back soon or create your own event.
                    </p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="<?= APP_URL ?>/register" class="btn btn-primary">
                                <i class="fas fa-bell me-2"></i>Get Notified
                            </a>
                            <a href="<?= APP_URL ?>/register" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold mb-3">Ready to Transform Your Events?</h2>
                <p class="lead mb-0">
                    Join thousands of event organizers who trust SmartCast for their voting needs. 
                    Start your free trial today and experience the future of digital voting.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?= APP_URL ?>/register" class="btn btn-warning btn-lg shadow-lg">
                        <i class="fas fa-rocket me-2"></i>Start Free Trial
                    </a>
                <?php else: ?>
                    <a href="<?= ORGANIZER_URL ?>/events/create" class="btn btn-warning btn-lg shadow-lg">
                        <i class="fas fa-plus me-2"></i>Create Your Event
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Add Custom Styles -->
<style>
.min-vh-75 {
    min-height: 75vh;
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-out 0.3s both;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 1s ease-out 0.6s both;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.feature-card {
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.event-card {
    transition: all 0.3s ease;
}

.event-card:hover {
    transform: translateY(-5px);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.mockup-card {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.hero-stats .stat-item {
    padding: 1rem 0;
}

.hero-stats h3 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .display-3 {
        font-size: 2.5rem;
    }
    
    .hero-stats h3 {
        font-size: 1.5rem;
    }
    
    .mockup-card {
        margin: 0 1rem;
    }
}

/* Counter Animation */
.counter {
    transition: all 0.3s ease;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

/* Statistics Cards Enhancement */
.stats-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stats-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}
</style>

<!-- Real Statistics JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters on page load
    animateCounters();
    
    // Add real-time updates every 30 seconds
    setInterval(updateLiveStats, 30000);
    
    function animateCounters() {
        const counters = document.querySelectorAll('[data-count]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const text = counter.textContent;
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Format the number based on the original text format
                if (text.includes('K+')) {
                    counter.textContent = formatNumber(Math.floor(current));
                } else if (text.includes('M+')) {
                    counter.textContent = formatNumber(Math.floor(current));
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 20);
        });
    }
    
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M+';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K+';
        }
        return num.toLocaleString();
    }
    
    function updateLiveStats() {
        // Fetch updated statistics (optional - for real-time updates)
        fetch('<?= APP_URL ?>/api/live-stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update live statistics if API exists
                    updateStatDisplay(data.stats);
                }
            })
            .catch(error => {
                // Silently fail - not critical
                console.log('Live stats update failed:', error);
            });
    }
    
    function updateStatDisplay(stats) {
        // Update the live event count and other dynamic stats
        const liveEventsElement = document.querySelector('.live-events-count');
        if (liveEventsElement && stats.active_events !== undefined) {
            liveEventsElement.textContent = stats.active_events;
        }
        
        // Update engagement rate
        const engagementElement = document.querySelector('.engagement-rate');
        if (engagementElement && stats.engagement_rate !== undefined) {
            engagementElement.textContent = stats.engagement_rate;
        }
    }
    
    // Add pulse effect to live indicators
    const liveIndicators = document.querySelectorAll('.text-success:contains("Live")');
    liveIndicators.forEach(indicator => {
        setInterval(() => {
            indicator.style.opacity = '0.5';
            setTimeout(() => {
                indicator.style.opacity = '1';
            }, 500);
        }, 2000);
    });
});
</script>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
