<?php 
$content = ob_start(); 
?>

<!-- Page Actions -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
    <div>
        <p class="text-muted mb-0">Manage contestants organized by events and categories</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= ORGANIZER_URL ?>/contestants/create" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>
            <span class="d-none d-sm-inline">Add Contestant</span>
            <span class="d-sm-none">Add</span>
        </a>
        <button class="btn btn-outline-secondary" onclick="expandAll()" title="Expand All">
            <i class="fas fa-expand-alt me-2"></i>
            <span class="d-none d-md-inline">Expand All</span>
        </button>
        <button class="btn btn-outline-secondary" onclick="collapseAll()" title="Collapse All">
            <i class="fas fa-compress-alt me-2"></i>
            <span class="d-none d-md-inline">Collapse All</span>
        </button>
    </div>
</div>

<!-- Debug Information -->
<?php if (isset($debugInfo) && !empty($debugInfo['debug_messages'])): ?>
<div class="alert alert-info mb-4">
    <h6><i class="fas fa-bug me-2"></i>Debug Information:</h6>
    <small>
        <strong>Total Events:</strong> <?= $debugInfo['total_events'] ?><br>
        <strong>Event Data Count:</strong> <?= $debugInfo['total_event_data'] ?><br>
        <strong>Details:</strong><br>
        <?php foreach ($debugInfo['debug_messages'] as $msg): ?>
            <?= htmlspecialchars($msg) ?><br>
        <?php endforeach; ?>
    </small>
</div>
<?php endif; ?>

<!-- Summary Stats -->
<?php 
$totalContestants = 0;
$totalVotes = 0;
$totalRevenue = 0;
foreach ($eventData as $data) {
    $totalContestants += $data['event']['contestant_count'];
    $totalVotes += $data['event']['total_votes'];
    $totalRevenue += $data['event']['revenue'];
}
?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <div class="fs-4 fw-semibold"><?= count($eventData) ?></div>
                <div>Events</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="fs-4 fw-semibold"><?= number_format($totalContestants) ?></div>
                <div>Contestants</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="fs-4 fw-semibold"><?= number_format($totalVotes) ?></div>
                <div>Total Votes</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <div class="fs-4 fw-semibold">$<?= number_format($totalRevenue, 2) ?></div>
                <div>Revenue</div>
            </div>
        </div>
    </div>
</div>

<!-- Events Accordion -->
<?php if (!empty($eventData)): ?>
    <div class="accordion" id="eventsAccordion">
        <?php foreach ($eventData as $index => $data): ?>
            <?php $event = $data['event']; ?>
            <?php $categories = $data['categories']; ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $event['event_id'] ?>">
                    <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse" 
                            data-coreui-target="#collapse<?= $event['event_id'] ?>" aria-expanded="false" 
                            aria-controls="collapse<?= $event['event_id'] ?>"
                            data-event-id="<?= $event['event_id'] ?>">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <div>
                                <strong><?= htmlspecialchars($event['event_name']) ?></strong>
                                <span class="badge bg-<?= $event['event_status'] === 'active' ? 'success' : ($event['event_status'] === 'draft' ? 'warning' : 'secondary') ?> ms-2">
                                    <?= ucfirst($event['event_status']) ?>
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">
                                    <?= $event['contestant_count'] ?> contestants • 
                                    <?= number_format($event['total_votes']) ?> votes • 
                                    $<?= number_format($event['revenue'], 2) ?>
                                </small>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse<?= $event['event_id'] ?>" class="accordion-collapse collapse" 
                     aria-labelledby="heading<?= $event['event_id'] ?>" data-bs-parent="#eventsAccordion">
                    <div class="accordion-body">
                        <?php if (!empty($categories)): ?>
                            <!-- Categories within Event -->
                            <div class="row">
                                <?php foreach ($categories as $category): ?>
                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-tag me-2"></i>
                                                        <?= htmlspecialchars($category['category_name']) ?>
                                                    </h6>
                                                    <span class="badge bg-primary">
                                                        <?= $category['contestant_count'] ?> contestants
                                                    </span>
                                                </div>
                                                <?php if (!empty($category['category_description'])): ?>
                                                    <small class="text-muted"><?= htmlspecialchars($category['category_description']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body">
                                                <?php if (!empty($category['contestants'])): ?>
                                                    <div class="list-group list-group-flush">
                                                        <?php foreach ($category['contestants'] as $contestant): ?>
                                                            <div class="list-group-item p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <!-- Contestant Photo -->
                                                                    <div class="me-3">
                                                                        <?php if (!empty($contestant['image_url'])): ?>
                                                                            <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                                                                                 alt="<?= htmlspecialchars($contestant['name']) ?>"
                                                                                 class="rounded-circle" 
                                                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                                                        <?php else: ?>
                                                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                                                 style="width: 60px; height: 60px;">
                                                                                <i class="fas fa-user text-muted"></i>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    
                                                                    <!-- Contestant Info -->
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                                            <h6 class="mb-0"><?= htmlspecialchars($contestant['name']) ?></h6>
                                                                            <div class="text-end">
                                                                                <span class="badge bg-primary me-1"><?= number_format($contestant['total_votes']) ?> votes</span>
                                                                                <span class="badge bg-success">$<?= number_format($contestant['revenue'], 2) ?></span>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="small text-muted mb-2">
                                                                            <i class="fas fa-mobile-alt me-1"></i>
                                                                            <strong>Vote: <?= htmlspecialchars($contestant['voting_shortcode'] ?? $contestant['contestant_code']) ?></strong>
                                                                            <span class="text-info ms-2">
                                                                                <i class="fas fa-copy" onclick="copyToClipboard('<?= htmlspecialchars($contestant['voting_shortcode'] ?? $contestant['contestant_code']) ?>')" 
                                                                                   title="Copy voting code" style="cursor: pointer;"></i>
                                                                            </span>
                                                                        </div>
                                                                        
                                                                        <?php if (!empty($contestant['bio'])): ?>
                                                                            <p class="small text-muted mb-2">
                                                                                <?= htmlspecialchars(substr($contestant['bio'], 0, 120)) ?>
                                                                                <?= strlen($contestant['bio']) > 120 ? '...' : '' ?>
                                                                            </p>
                                                                        <?php endif; ?>
                                                                        
                                                                        <!-- Action Buttons -->
                                                                        <div class="btn-group btn-group-sm d-flex d-md-inline-flex" role="group">
                                                                            <button class="btn btn-outline-primary flex-fill flex-md-grow-0" 
                                                                                    onclick="viewContestant(<?= $contestant['id'] ?>)"
                                                                                    title="View Details">
                                                                                <i class="fas fa-eye me-1"></i>
                                                                                <span class="d-none d-sm-inline">View</span>
                                                                            </button>
                                                                            <button class="btn btn-outline-secondary flex-fill flex-md-grow-0" 
                                                                                    onclick="editContestant(<?= $contestant['id'] ?>)"
                                                                                    title="Edit">
                                                                                <i class="fas fa-edit me-1"></i>
                                                                                <span class="d-none d-sm-inline">Edit</span>
                                                                            </button>
                                                                            <button class="btn btn-outline-info flex-fill flex-md-grow-0" 
                                                                                    onclick="viewStats(<?= $contestant['id'] ?>)"
                                                                                    title="Statistics">
                                                                                <i class="fas fa-chart-bar me-1"></i>
                                                                                <span class="d-none d-sm-inline">Stats</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center py-3">
                                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                                        <p class="text-muted mb-0">No contestants in this category yet</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No Categories</h6>
                                <p class="text-muted mb-0">This event doesn't have any categories set up yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-users fa-4x text-muted opacity-50"></i>
        </div>
        <h4 class="text-muted">No Events with Contestants</h4>
        <p class="text-muted mb-4">Create events and add contestants to get started</p>
        <div>
            <a href="<?= ORGANIZER_URL ?>/events/wizard" class="btn btn-primary me-2">
                <i class="fas fa-calendar-plus me-2"></i>Create Event
            </a>
            <a href="<?= ORGANIZER_URL ?>/contestants/create" class="btn btn-outline-primary">
                <i class="fas fa-user-plus me-2"></i>Add Contestant
            </a>
        </div>
    </div>
<?php endif; ?>

<script>
// Contestant management functions
function viewContestant(id) {
    // Redirect to contestant details page
    window.location.href = `<?= ORGANIZER_URL ?>/contestants/${id}`;
}

function editContestant(id) {
    // Redirect to contestant edit page
    window.location.href = `<?= ORGANIZER_URL ?>/contestants/${id}/edit`;
}

function viewStats(id) {
    // Redirect to contestant statistics page
    window.location.href = `<?= ORGANIZER_URL ?>/contestants/${id}/stats`;
}

// Accordion management
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing accordions...');
    
    // Check if Bootstrap is available (try multiple possible locations)
    const hasBootstrap = typeof bootstrap !== 'undefined' || 
                        typeof window.bootstrap !== 'undefined' || 
                        typeof coreui !== 'undefined';
    
    if (!hasBootstrap) {
        console.log('Bootstrap/CoreUI not found, using manual accordion implementation');
        // Continue with manual implementation
    }
    
    // Initialize accordions manually if needed
    const accordionElement = document.getElementById('eventsAccordion');
    if (accordionElement) {
        console.log('Found accordion element');
        
        // Add click handlers to accordion buttons
        const accordionButtons = document.querySelectorAll('.accordion-button');
        console.log('Found', accordionButtons.length, 'accordion buttons');
        
        accordionButtons.forEach((button, index) => {
            console.log('Setting up button', index, 'with target:', button.getAttribute('data-bs-target'));
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Accordion button clicked:', this.getAttribute('data-bs-target'));
                
                const targetId = this.getAttribute('data-bs-target');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    console.log('Target element found, toggling...');
                    
                    const isCurrentlyOpen = targetElement.classList.contains('show');
                    
                    // Close all accordions first
                    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
                        collapse.classList.remove('show');
                    });
                    document.querySelectorAll('.accordion-button').forEach(btn => {
                        btn.classList.add('collapsed');
                        btn.setAttribute('aria-expanded', 'false');
                    });
                    
                    // If it wasn't open, open this one
                    if (!isCurrentlyOpen) {
                        targetElement.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                        
                        // Smooth scroll after a delay
                        setTimeout(() => {
                            this.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start' 
                            });
                        }, 300);
                    }
                } else {
                    console.error('Target element not found:', targetId);
                }
            });
        });
    } else {
        console.error('Accordion element not found!');
    }
    
    // Add tooltips to action buttons (if Bootstrap/CoreUI is available)
    try {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        } else if (typeof coreui !== 'undefined' && coreui.Tooltip) {
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new coreui.Tooltip(tooltipTriggerEl);
            });
        } else {
            console.log('Tooltips not available - using native browser tooltips');
        }
    } catch (e) {
        console.log('Tooltips not initialized:', e.message);
    }
});

// Expand all accordions
function expandAll() {
    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
        collapse.classList.add('show');
    });
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.classList.remove('collapsed');
        button.setAttribute('aria-expanded', 'true');
    });
}

// Collapse all accordions
function collapseAll() {
    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
        collapse.classList.remove('show');
    });
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.classList.add('collapsed');
        button.setAttribute('aria-expanded', 'false');
    });
}

// Copy voting code to clipboard
function copyToClipboard(code) {
    if (navigator.clipboard && window.isSecureContext) {
        // Use modern clipboard API
        navigator.clipboard.writeText(code).then(() => {
            showCopyFeedback('Voting code copied!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            fallbackCopyTextToClipboard(code);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyTextToClipboard(code);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyFeedback('Voting code copied!');
        } else {
            showCopyFeedback('Failed to copy code');
        }
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        showCopyFeedback('Failed to copy code');
    }
    
    document.body.removeChild(textArea);
}

function showCopyFeedback(message) {
    // Create a temporary toast notification
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 200px;';
    toast.innerHTML = `<i class="fas fa-check me-2"></i>${message}`;
    
    document.body.appendChild(toast);
    
    // Remove after 2 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 2000);
}

// Search functionality (if needed in the future)
function searchContestants(query) {
    const searchTerm = query.toLowerCase();
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const eventName = item.querySelector('.accordion-button strong').textContent.toLowerCase();
        const contestants = item.querySelectorAll('.card-title');
        let hasMatch = eventName.includes(searchTerm);
        
        contestants.forEach(contestant => {
            if (contestant.textContent.toLowerCase().includes(searchTerm)) {
                hasMatch = true;
            }
        });
        
        item.style.display = hasMatch ? 'block' : 'none';
    });
}
</script>

<style>
/* Custom styles for contestants page */
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    border-left-color: #0d6efd;
    background-color: #f8f9fa;
}

.contestant-image img {
    transition: transform 0.3s ease;
}

.contestant-image:hover img {
    transform: scale(1.05);
}

.badge {
    font-size: 0.75em;
}

.btn-group-sm .btn {
    font-size: 0.75rem;
}

/* Mobile improvements */
@media (max-width: 768px) {
    .accordion-button {
        padding: 0.75rem;
    }
    
    .accordion-button .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    
    .list-group-item {
        padding: 1rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}

/* Loading states */
.accordion-item.loading {
    opacity: 0.7;
}

.accordion-item.loading .accordion-button {
    pointer-events: none;
}

/* Empty state styling */
.text-center.py-5 {
    padding: 3rem 1rem !important;
}

.text-center.py-5 i {
    opacity: 0.5;
}

/* Stats cards improvements */
.card.bg-primary,
.card.bg-success,
.card.bg-info,
.card.bg-warning {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card.bg-primary:hover,
.card.bg-success:hover,
.card.bg-info:hover,
.card.bg-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../../layout/organizer_layout.php';
?>
