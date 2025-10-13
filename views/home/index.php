<?php include __DIR__ . '/../layout/public_header.php'; ?>

<div class="hero-section bg-primary text-white py-5 mb-5 rounded">
    <div class="container text-center">
        <h1 class="display-4 mb-4">
            <i class="fas fa-vote-yea me-3"></i>
            Welcome to SmartCast
        </h1>
        <p class="lead mb-4">
            Professional voting management system for events, competitions, and contests
        </p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="mt-4">
                <a href="<?= APP_URL ?>/register" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus me-2"></i>Get Started
                </a>
                <a href="<?= APP_URL ?>/pricing" class="btn btn-outline-light btn-lg me-3">
                    <i class="fas fa-tags me-2"></i>View Plans
                </a>
                <a href="<?= APP_URL ?>/login" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Event Management</h5>
                <p class="card-text">Create and manage voting events with ease. Set up categories, contestants, and voting periods.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-success mb-3"></i>
                <h5 class="card-title">Contestant Management</h5>
                <p class="card-text">Add contestants, manage their profiles, and organize them into categories for structured voting.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                <h5 class="card-title">Real-time Results</h5>
                <p class="card-text">Track votes in real-time with comprehensive analytics and reporting features.</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($events)): ?>
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-fire text-danger me-2"></i>
            Active Events
        </h2>
        
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <?php if ($event['featured_image']): ?>
                            <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="<?= htmlspecialchars($event['name']) ?>">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['name']) ?></h5>
                            
                            <?php if ($event['description']): ?>
                                <p class="card-text text-muted">
                                    <?= htmlspecialchars(substr($event['description'], 0, 100)) ?>
                                    <?= strlen($event['description']) > 100 ? '...' : '' ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('M j, Y', strtotime($event['start_date'])) ?>
                                </small>
                                
                                <a href="<?= APP_URL ?>/events/<?= $event['id'] ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-vote-yea me-1"></i>Vote Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="text-center py-5">
    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
    <h3 class="text-muted">No Active Events</h3>
    <p class="text-muted">Check back later for upcoming voting events.</p>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
