<?php
/**
 * SuperAdmin Events Management - Index View
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Events Management
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshEvents()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Refresh
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="closeExpiredEvents()">
                            <i class="fas fa-clock me-1"></i>
                            Close Expired Events
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>
                                Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('all')">All Events</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('approved')">Approved</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('pending')">Pending</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('rejected')">Rejected</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('active')">Active</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('closed')">Closed</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterEvents('expired')">Expired (Need Closing)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="eventsTable">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Tenant</th>
                                    <th>Status</th>
                                    <th>Dates</th>
                                    <th>Stats</th>
                                    <th>Revenue</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $event): ?>
                                <tr data-event-id="<?= $event['id'] ?>" 
                                    data-status="<?= $event['admin_status'] ?>"
                                    data-event-status="<?= $event['status'] ?>"
                                    data-expired="<?= (strtotime($event['end_date']) < time() && $event['status'] !== 'closed') ? 'true' : 'false' ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($event['featured_image']): ?>
                                                <img src="<?= htmlspecialchars(image_url($event['featured_image'])) ?>" 
                                                     alt="Event Image" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-calendar-alt text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="<?= SUPERADMIN_URL ?>/events/<?= $event['id'] ?>" 
                                                       class="text-decoration-none">
                                                        <?= htmlspecialchars($event['name']) ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?= htmlspecialchars($event['code']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($event['tenant_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars($event['created_by_email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($event['admin_status']) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            'under_review' => 'info',
                                            default => 'secondary'
                                        };
                                        
                                        $statusText = match($event['admin_status']) {
                                            'approved' => 'Approved',
                                            'pending' => 'Pending',
                                            'rejected' => 'Rejected',
                                            'under_review' => 'Under Review',
                                            default => 'Unknown'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                        
                                        <?php if ($event['status'] !== 'active'): ?>
                                            <br><small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Event: <?= ucfirst($event['status']) ?>
                                            </small>
                                        <?php endif; ?>
                                        
                                        <?php if ($event['visibility'] === 'private'): ?>
                                            <br><small class="text-muted"><i class="fas fa-lock me-1"></i>Private</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">Start:</small><br>
                                            <?= date('M j, Y', strtotime($event['start_date'])) ?>
                                            <br>
                                            <small class="text-muted">End:</small><br>
                                            <?= date('M j, Y', strtotime($event['end_date'])) ?>
                                            
                                            <?php if (strtotime($event['end_date']) < time() && $event['status'] !== 'closed'): ?>
                                                <br><span class="badge bg-danger small">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Expired
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="fas fa-users me-1"></i><?= number_format($event['contestant_count']) ?> contestants</div>
                                            <div><i class="fas fa-tags me-1"></i><?= number_format($event['category_count']) ?> categories</div>
                                            <div><i class="fas fa-vote-yea me-1"></i><?= number_format($event['total_votes']) ?> votes</div>
                                            <div><i class="fas fa-receipt me-1"></i><?= number_format($event['transaction_count']) ?> transactions</div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            GH₵<?= number_format($event['total_revenue'], 2) ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            GH₵<?= number_format($event['vote_price'], 2) ?> per vote
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= SUPERADMIN_URL ?>/events/<?= $event['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Actions">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <?php if ($event['admin_status'] !== 'active'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateEventStatus(<?= $event['id'] ?>, 'active')">
                                                            <i class="fas fa-check text-success me-2"></i>Approve
                                                        </a></li>
                                                    <?php endif; ?>
                                                    <?php if ($event['admin_status'] !== 'suspended'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateEventStatus(<?= $event['id'] ?>, 'suspended')">
                                                            <i class="fas fa-pause text-warning me-2"></i>Suspend
                                                        </a></li>
                                                    <?php endif; ?>
                                                    <?php if ($event['admin_status'] !== 'rejected'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateEventStatus(<?= $event['id'] ?>, 'rejected')">
                                                            <i class="fas fa-times text-danger me-2"></i>Reject
                                                        </a></li>
                                                    <?php endif; ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/events/<?= $event['code'] ?>" target="_blank">
                                                        <i class="fas fa-external-link-alt me-2"></i>View Public Page
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Event Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="eventId" name="event_id">
                    <input type="hidden" id="newStatus" name="status">
                    
                    <div class="mb-3">
                        <label class="form-label">Event:</label>
                        <div id="eventName" class="fw-bold"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Status:</label>
                        <div id="statusDisplay" class="fw-bold"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes (Optional):</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3" 
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateEventStatus(eventId, status) {
    const eventRow = document.querySelector(`tr[data-event-id="${eventId}"]`);
    const eventName = eventRow.querySelector('h6 a').textContent;
    
    document.getElementById('eventId').value = eventId;
    document.getElementById('newStatus').value = status;
    document.getElementById('eventName').textContent = eventName;
    document.getElementById('statusDisplay').textContent = status.charAt(0).toUpperCase() + status.slice(1);
    document.getElementById('adminNotes').value = '';
    
    new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
}

function confirmStatusUpdate() {
    const formData = new FormData(document.getElementById('statusUpdateForm'));
    
    fetch('<?= SUPERADMIN_URL ?>/events/update-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'An error occurred while updating the event status');
        console.error('Error:', error);
    });
}

function filterEvents(status) {
    const rows = document.querySelectorAll('#eventsTable tbody tr');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function refreshEvents() {
    location.reload();
}

function closeExpiredEvents() {
    if (!confirm('Are you sure you want to close all expired events? This action cannot be undone.')) {
        return;
    }
    
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Closing...';
    button.disabled = true;
    
    fetch('<?= SUPERADMIN_URL ?>/events/close-expired', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            if (data.closed_count > 0) {
                setTimeout(() => location.reload(), 1500);
            }
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'An error occurred while closing expired events');
        console.error('Error:', error);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function filterEvents(filter) {
    const rows = document.querySelectorAll('#eventsTable tbody tr');
    
    rows.forEach(row => {
        const status = row.dataset.status;
        const eventStatus = row.dataset.eventStatus;
        const expired = row.dataset.expired === 'true';
        let show = false;
        
        switch(filter) {
            case 'all':
                show = true;
                break;
            case 'approved':
                show = status === 'approved';
                break;
            case 'pending':
                show = status === 'pending';
                break;
            case 'rejected':
                show = status === 'rejected';
                break;
            case 'under_review':
                show = status === 'under_review';
                break;
            case 'active':
                show = eventStatus === 'active';
                break;
            case 'closed':
                show = eventStatus === 'closed';
                break;
            case 'expired':
                show = expired;
                break;
        }
        
        row.style.display = show ? '' : 'none';
    });
    
    // Update filter button text
    const filterButton = document.querySelector('.dropdown-toggle');
    const filterText = filter.charAt(0).toUpperCase() + filter.slice(1);
    if (filter !== 'all') {
        filterButton.innerHTML = `<i class="fas fa-filter me-1"></i>${filterText}`;
    } else {
        filterButton.innerHTML = `<i class="fas fa-filter me-1"></i>Filter`;
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
