<?php
// Get the calculated status from the event
$calculatedStatus = $event['calculatedStatus'];

// Generate event slug
require_once __DIR__ . '/../../src/Helpers/SlugHelper.php';
$eventSlug = \SmartCast\Helpers\SlugHelper::generateEventSlug($event);
?>

<div class="col-sm-6 col-md-4 col-lg-3 mb-4 event-card" data-event-id="<?= $event['id'] ?>" data-status="<?= $calculatedStatus ?>">
    <div class="card h-100 border-0 shadow-sm event-card-hover position-relative">
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
                <?php
                if ($calculatedStatus === 'ended') {
                    $badgeClass = 'bg-danger';
                    $badgeText = '<i class="fas fa-stop me-1"></i>ENDED';
                } elseif ($calculatedStatus === 'upcoming') {
                    $badgeClass = 'bg-warning text-dark';
                    $badgeText = '<i class="fas fa-clock me-1"></i>UPCOMING';
                } elseif ($calculatedStatus === 'active') {
                    $badgeClass = 'bg-success';
                    $badgeText = '<i class="fas fa-circle me-1 pulse"></i>LIVE';
                } else {
                    $badgeClass = 'bg-secondary';
                    $badgeText = ucfirst($calculatedStatus);
                }
                ?>
                <span class="badge badge-status <?= $badgeClass ?>">
                    <?= $badgeText ?>
                </span>
            </div>
            
            <!-- Hover Overlay with Details Button -->
            <div class="card-hover-overlay">
                <div class="hover-content">
                    <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>" class="btn btn-light btn-details">
                        <i class="fas fa-info-circle me-2"></i>
                        View Details
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body px-3 pt-3 pb-1 d-flex flex-column">
            <!-- Event Header -->
            <div class="event-header mb-2">
                <h6 class="event-title mb-1">
                    <?= htmlspecialchars($event['name']) ?>
                    <?php if ($calculatedStatus === 'active'): ?>
                        <span class="live-badge">LIVE</span>
                    <?php endif; ?>
                </h6>
            </div>
            
            <!-- Ultra Compact Stats -->
            <div class="ultra-stats mb-2">
                <?php
                    // Calculate days left or show appropriate status
                    $timezone = new DateTimeZone('Africa/Accra');
                    $startDate = new DateTime($event['start_date'], $timezone);
                    $endDate = new DateTime($event['end_date'], $timezone);
                    $today = new DateTime('now', $timezone);
                    
                    if ($calculatedStatus === 'ended') {
                        $daysLeftText = 'Ended';
                        $textClass = 'text-danger';
                    } elseif ($calculatedStatus === 'upcoming') {
                        // Calculate based on actual hours until start (24-hour periods)
                        $interval = $today->diff($startDate);
                        $hoursUntilStart = ($interval->days * 24) + $interval->h;
                        
                        if ($hoursUntilStart < 24) {
                            $daysLeftText = 'Starts Today';
                            $textClass = 'text-warning';
                        } elseif ($hoursUntilStart < 48) {
                            $daysLeftText = 'Starts Tomorrow';
                            $textClass = 'text-info';
                        } else {
                            $daysToStart = ceil($hoursUntilStart / 24);
                            $daysLeftText = 'Starts in ' . $daysToStart . ' day' . ($daysToStart !== 1 ? 's' : '');
                            $textClass = 'text-info';
                        }
                    } else {
                        // Active event - use calendar days (midnight as boundary)
                        $todayDate = $today->format('Y-m-d');
                        $endDateOnly = $endDate->format('Y-m-d');
                        $tomorrow = clone $today;
                        $tomorrowDate = $tomorrow->modify('+1 day')->format('Y-m-d');
                        
                        // Calculate actual time remaining
                        $interval = $today->diff($endDate);
                        $hoursRemaining = ($interval->days * 24) + $interval->h;
                        $minutesRemaining = $interval->i;
                        
                        // Initialize countdown variables (only used if < 24 hours)
                        $countdownId = null;
                        $endTimestamp = null;
                        
                        // Debug: Show calculation (remove this after testing)
                        error_log("Event: {$event['name']}, Today: $todayDate, End: $endDateOnly, Hours: $hoursRemaining");
                        
                        if ($hoursRemaining < 24) {
                            // Less than 24 hours - show countdown
                            if ($hoursRemaining > 0) {
                                $daysLeftText = $hoursRemaining . 'h ' . $minutesRemaining . 'm left';
                            } else {
                                $daysLeftText = $minutesRemaining . 'm left';
                            }
                            $textClass = 'text-danger fw-bold';
                            $countdownId = 'countdown-' . $event['id'];
                            $endTimestamp = strtotime($event['end_date']);
                        } elseif ($endDateOnly === $todayDate) {
                            // Ends on today's calendar date (but more than 24h away - edge case)
                            $daysLeftText = 'Ends Today';
                            $textClass = 'text-warning';
                        } elseif ($endDateOnly === $tomorrowDate) {
                            // Ends on tomorrow's calendar date
                            $daysLeftText = 'Ends Tomorrow';
                            $textClass = 'text-warning';
                        } else {
                            // Ends 2+ days from now
                            $daysLeft = max(1, $interval->days);
                            $daysLeftText = $daysLeft . ' Day' . ($daysLeft !== 1 ? 's' : '') . ' Left';
                            $textClass = '';
                        }
                    }
                ?>
                <div class="stats-row">
                    <span class="stat-item"><?= $event['contestant_count'] ?? 0 ?> Contestants</span>
                    <span class="stat-divider">•</span>
                 <!--   <span class="stat-item"><?= number_format($event['total_votes'] ?? 0) ?> Votes</span> 
                    <span class="stat-divider">•</span>-->
                    <span class="stat-item <?= $textClass ?>" 
                          <?php if (isset($countdownId)): ?>
                          id="<?= $countdownId ?>" 
                          data-end-time="<?= $endTimestamp ?>"
                          <?php endif; ?>><?= $daysLeftText ?></span>
                </div>
            </div>
            
            <!-- Minimal Dates -->
            <div class="mini-dates">
                <div class="date-row">
                    <?php if ($calculatedStatus === 'upcoming'): ?>
                        <span class="date-label">Starts:</span> <?= date('M j', strtotime($event['start_date'])) ?>
                    <?php else: ?>
                        <span class="date-label">Started:</span> <?= date('M j', strtotime($event['start_date'])) ?>
                    <?php endif; ?>
                    <span class="date-separator">|</span>
                    <?php if ($calculatedStatus === 'ended'): ?>
                        <span class="date-label">Ended:</span> <?= date('M j, g:i A', strtotime($event['end_date'])) ?>
                    <?php else: ?>
                        <span class="date-label">Ends:</span> <?= date('M j, g:i A', strtotime($event['end_date'])) ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-auto" style="margin-top: 8px !important; margin-bottom: 0 !important;">
                <div class="compact-actions">
                    <?php if ($calculatedStatus === 'active'): ?>
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
                    <?php elseif ($calculatedStatus === 'upcoming'): ?>
                        <span class="coming-soon">Starts Soon</span>
                    <?php elseif ($calculatedStatus === 'ended'): ?>
                        <?php if ($event['results_visible']): ?>
                            <a href="<?= APP_URL ?>/events/<?= $eventSlug ?>" 
                               class="results-btn primary">
                                View Final Results
                            </a>
                        <?php else: ?>
                            <span class="ended-event">Event Ended</span>
                        <?php endif; ?>
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
