<!-- Modern CoreUI Stats Cards -->
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold"><?= count($events ?? []) ?></div>
                            <div>Total Events</div>
                            <div class="progress progress-white progress-xs my-2">
                                <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                            </div>
                            <small class="text-white-50">All time</small>
                        </div>
                        <div class="dropdown">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-success">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">
                                <?= count(array_filter($events ?? [], function($e) { return $e['status'] === 'active'; })) ?>
                            </div>
                            <div>Active Events</div>
                            <div class="progress progress-white progress-xs my-2">
                                <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                            </div>
                            <small class="text-white-50">Currently running</small>
                        </div>
                        <div class="dropdown">
                            <i class="fas fa-play fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">
                                <?= count(array_filter($events ?? [], function($e) { return $e['status'] === 'draft'; })) ?>
                            </div>
                            <div>Draft Events</div>
                            <div class="progress progress-white progress-xs my-2">
                                <div class="progress-bar" role="progressbar" style="width: 50%"></div>
                            </div>
                            <small class="text-white-50">Pending publish</small>
                        </div>
                        <div class="dropdown">
                            <i class="fas fa-pencil-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">
                                <?php 
                                $totalRevenue = array_sum(array_column($events ?? [], 'revenue'));
                                echo 'GH₵' . number_format($totalRevenue, 0);
                                ?>
                            </div>
                            <div>Total Revenue</div>
                            <div class="progress progress-white progress-xs my-2">
                                <div class="progress-bar" role="progressbar" style="width: 85%"></div>
                            </div>
                            <small class="text-white-50">From all events</small>
                        </div>
                        <div class="dropdown">
                            <i class="fas fa-money-bill fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Toolbar -->
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    My Events
                </h4>
                <div class="small text-medium-emphasis">Manage your voting events and competitions</div>
            </div>
            <div class="btn-toolbar" role="toolbar">
                <div class="btn-group me-2" role="group">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" onclick="toggleView('list')" id="listView">
                        <i class="fas fa-list"></i>
                    </button>
                                <?php 
                                $currentEventCount = $tenantLimits['current_events'] ?? 0;
                                $maxEvents = $tenantLimits['max_events'];
                                $canCreateEvent = is_null($maxEvents) || $currentEventCount < $maxEvents;
                                ?>
                                
                                <div class="btn-group">
                                    <?php if ($canCreateEvent): ?>
                                        <a href="<?= ORGANIZER_URL ?>/events/wizard" class="btn btn-primary">
                                            <i class="fas fa-magic me-2"></i>
                                            Create Event
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-warning" 
                                           title="Upgrade plan to create more events (<?= $currentEventCount ?>/<?= $maxEvents ?> limit reached)">
                                            <i class="fas fa-crown me-2"></i>
                                            Upgrade to Create Events
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= ORGANIZER_URL ?>/events/demo">
                                            <i class="fas fa-mobile-alt me-2"></i>USSD Demo
                                        </a></li>
                                    </ul>
                                </div>
            </div>
        </div>
    </div>
    <div class="card-body py-3">
        <div class="row g-3 align-items-center">

            <div class="col-auto">
                <label class="col-form-label fw-semibold">Status:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="col-form-label fw-semibold">Visibility:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" id="visibilityFilter">
                    <option value="">All Visibility</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search events..." id="searchEvents">
                    <button class="btn btn-outline-secondary" type="button" onclick="filterEvents()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary" onclick="resetFilters()" title="Reset Filters">
                    <i class="fas fa-undo"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Events List -->
<?php if (!empty($events)): ?>
    <div class="row" id="eventsContainer">
        <?php foreach ($events as $event): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4 event-card" 
                 data-status="<?= $event['status'] ?>" 
                 data-visibility="<?= $event['visibility'] ?>"
                 data-name="<?= strtolower($event['name']) ?>">
                <div class="card h-100 shadow-sm border-0">
                    <?php if (!empty($event['featured_image'])): ?>
                        <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" class="card-img-top" style="height: 160px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center position-relative" style="height: 160px;">
                            <i class="fas fa-calendar-alt fa-2x text-white opacity-75"></i>
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-<?= $event['status'] === 'active' ? 'success' : ($event['status'] === 'draft' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0" title="<?= htmlspecialchars($event['name']) ?>">
                                <?= htmlspecialchars(strlen($event['name']) > 20 ? substr($event['name'], 0, 20) . '...' : $event['name']) ?>
                            </h6>
                            <span class="badge bg-<?= $event['status'] === 'active' ? 'success' : ($event['status'] === 'draft' ? 'warning' : 'secondary') ?> badge-sm">
                                <?= ucfirst($event['status']) ?>
                            </span>
                        </div>
                        
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-code me-1"></i>
                            Code: <strong><?= htmlspecialchars($event['code']) ?></strong>
                        </p>
                        
                        <?php if (!empty($event['description'])): ?>
                            <p class="card-text small mb-2">
                                <?= htmlspecialchars(substr($event['description'], 0, 60)) ?>
                                <?= strlen($event['description']) > 60 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="row text-center mb-2">
                            <div class="col-4">
                                <div class="fw-semibold text-primary small">
                                    <?= $event['contestant_count'] ?? 0 ?>
                                </div>
                                <div class="small text-muted">Contestants</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-semibold text-success small">
                                    <?= number_format($event['total_votes'] ?? 0) ?>
                                </div>
                                <div class="small text-muted">Votes</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-semibold text-info small">
                                    $<?= number_format($event['revenue'] ?? 0, 0) ?>
                                </div>
                                <div class="small text-muted">Revenue</div>
                            </div>
                        </div>
                        
                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('M j', strtotime($event['start_date'])) ?> - 
                            <?= date('M j, Y', strtotime($event['end_date'])) ?>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group" role="group">
                                <?php if ($event['status'] === 'draft'): ?>
                                    <?php 
                                    $currentEventCount = $tenantLimits['current_events'] ?? 0;
                                    $maxEvents = $tenantLimits['max_events'];
                                    $canPublish = is_null($maxEvents) || $currentEventCount < $maxEvents;
                                    ?>
                                    <a href="<?= ORGANIZER_URL ?>/events/wizard?edit=<?= $event['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-magic me-1"></i>Edit Draft
                                    </a>
                                    <a href="<?= ORGANIZER_URL ?>/events/<?= $event['id'] ?>/preview" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye me-1"></i>Preview
                                    </a>
                                    <?php if ($canPublish): ?>
                                        <button class="btn btn-outline-success btn-sm" onclick="publishEvent(<?= $event['id'] ?>)">
                                            <i class="fas fa-play me-1"></i>Publish
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-warning btn-sm" 
                                           title="Upgrade plan to publish events (<?= $currentEventCount ?>/<?= $maxEvents ?> limit reached)">
                                            <i class="fas fa-crown me-1"></i>Upgrade
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($event['status'] === 'active'): ?>
                                    <a href="<?= ORGANIZER_URL ?>/events/<?= $event['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="<?= ORGANIZER_URL ?>/voting/live?event=<?= $event['id'] ?>" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-chart-line me-1"></i>Live
                                    </a>
                                <?php else: ?>
                                    <a href="<?= ORGANIZER_URL ?>/events/<?= $event['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="<?= ORGANIZER_URL ?>/events/<?= $event['id'] ?>/edit" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Quick Status Actions -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Status Actions</h6></li>
                                    <?php if ($event['status'] !== 'draft'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateEventStatus(<?= $event['id'] ?>, 'draft', '<?= $event['visibility'] ?>')">
                                                <i class="fas fa-file-alt text-warning me-2"></i>Set as Draft
                                            </button>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($event['status'] !== 'active'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateEventStatus(<?= $event['id'] ?>, 'active', '<?= $event['visibility'] ?>')">
                                                <i class="fas fa-play text-success me-2"></i>Activate Event
                                            </button>
                                        </li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Visibility</h6></li>
                                    <?php if ($event['visibility'] !== 'private'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateEventStatus(<?= $event['id'] ?>, '<?= $event['status'] ?>', 'private')">
                                                <i class="fas fa-lock text-secondary me-2"></i>Make Private
                                            </button>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($event['visibility'] !== 'public'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateEventStatus(<?= $event['id'] ?>, '<?= $event['status'] ?>', 'public')">
                                                <i class="fas fa-globe text-primary me-2"></i>Make Public
                                            </button>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-calendar-alt fa-4x text-muted opacity-50"></i>
        </div>
        <h4 class="text-muted">No Events Yet</h4>
        <p class="text-muted mb-4">Create your first voting event to get started</p>
        <a href="<?= ORGANIZER_URL ?>/events/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Your First Event
        </a>
    </div>
<?php endif; ?>

<style>
/* Optimize cards for 4-column layout */
@media (min-width: 992px) {
    .event-card .card {
        min-height: 420px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .event-card .card {
        min-height: 450px;
    }
}

@media (max-width: 767px) {
    .event-card .card {
        min-height: 380px;
    }
}

.event-card .card-title {
    line-height: 1.2;
    font-size: 1rem;
}

.event-card .badge-sm {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.event-card .btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.event-card .card-body {
    padding: 1rem 0.75rem;
}

.event-card .card-footer {
    padding: 0.5rem 0.75rem;
}
</style>

<script>
// Event filtering
function filterEvents() {
    const statusFilter = document.getElementById('statusFilter').value;
    const visibilityFilter = document.getElementById('visibilityFilter').value;
    const searchTerm = document.getElementById('searchEvents').value.toLowerCase();
    
    document.querySelectorAll('.event-card').forEach(card => {
        const status = card.dataset.status;
        const visibility = card.dataset.visibility;
        const name = card.dataset.name;
        
        const statusMatch = !statusFilter || status === statusFilter;
        const visibilityMatch = !visibilityFilter || visibility === visibilityFilter;
        const nameMatch = !searchTerm || name.includes(searchTerm);
        
        if (statusMatch && visibilityMatch && nameMatch) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('visibilityFilter').value = '';
    document.getElementById('searchEvents').value = '';
    filterEvents();
}

function publishEvent(eventId) {
    if (confirm('Are you sure you want to publish this event? It will become visible to voters.')) {
        // AJAX call to publish event
        fetch(`<?= ORGANIZER_URL ?>/events/${eventId}/publish`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error publishing event: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error publishing event');
        });
    }
}

// Add event listeners
document.getElementById('statusFilter').addEventListener('change', filterEvents);
document.getElementById('visibilityFilter').addEventListener('change', filterEvents);
document.getElementById('searchEvents').addEventListener('input', filterEvents);

// Quick status update function
function updateEventStatus(eventId, status, visibility) {
    if (!confirm(`Are you sure you want to change this event to ${status} (${visibility})?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('status', status);
    formData.append('visibility', visibility);
    
    fetch(`<?= ORGANIZER_URL ?>/events/${eventId}/update-status`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('Event status updated successfully!', 'success');
            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert(data.message || 'Failed to update event status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating event status', 'error');
    });
}

// Show alert function
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at the top of the page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
}
</script>
