<?php 
$content = ob_start(); 
?>

<!-- Payout History -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Payout History
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshHistory()">
                            <i class="fas fa-sync me-1"></i>
                            Refresh
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportHistory()">
                            <i class="fas fa-download me-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($payouts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payout ID</th>
                                <th>Date Requested</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Processed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payouts as $payout): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        <code><?= htmlspecialchars($payout['payout_id']) ?></code>
                                    </div>
                                    <small class="text-muted">
                                        <?= ucfirst($payout['payout_type'] ?? 'manual') ?> payout
                                    </small>
                                </td>
                                <td>
                                    <div><?= date('M j, Y', strtotime($payout['created_at'])) ?></div>
                                    <small class="text-muted"><?= date('g:i A', strtotime($payout['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold">$<?= number_format($payout['amount'], 2) ?></div>
                                    <?php if (($payout['processing_fee'] ?? 0) > 0): ?>
                                    <small class="text-muted">
                                        Fee: $<?= number_format($payout['processing_fee'], 2) ?>
                                    </small>
                                    <br>
                                    <small class="text-success">
                                        Net: $<?= number_format($payout['net_amount'] ?? ($payout['amount'] - ($payout['processing_fee'] ?? 0)), 2) ?>
                                    </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        $methodIcons = [
                                            'bank_transfer' => 'fas fa-university',
                                            'mobile_money' => 'fas fa-mobile-alt',
                                            'paypal' => 'fab fa-paypal',
                                            'stripe' => 'fab fa-stripe'
                                        ];
                                        $icon = $methodIcons[$payout['payout_method']] ?? 'fas fa-credit-card';
                                        ?>
                                        <i class="<?= $icon ?> me-2"></i>
                                        <div>
                                            <div><?= ucwords(str_replace('_', ' ', $payout['payout_method'])) ?></div>
                                            <?php if (!empty($payout['recipient_details'])): ?>
                                            <?php
                                            $details = json_decode($payout['recipient_details'], true);
                                            $displayInfo = '';
                                            if ($payout['payout_method'] === 'bank_transfer' && isset($details['account_number'])) {
                                                $displayInfo = '****' . substr($details['account_number'], -4);
                                            } elseif ($payout['payout_method'] === 'mobile_money' && isset($details['phone_number'])) {
                                                $displayInfo = '****' . substr($details['phone_number'], -4);
                                            } elseif ($payout['payout_method'] === 'paypal' && isset($details['email'])) {
                                                $displayInfo = $details['email'];
                                            }
                                            ?>
                                            <?php if ($displayInfo): ?>
                                            <small class="text-muted"><?= htmlspecialchars($displayInfo) ?></small>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $statusClasses = [
                                        'queued' => 'warning',
                                        'processing' => 'info',
                                        'success' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusClass = $statusClasses[$payout['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($payout['status']) ?>
                                    </span>
                                    <?php if ($payout['status'] === 'failed' && !empty($payout['failure_reason'])): ?>
                                    <br>
                                    <small class="text-danger" title="<?= htmlspecialchars($payout['failure_reason']) ?>">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <?= substr($payout['failure_reason'], 0, 30) ?>...
                                    </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payout['processed_at']): ?>
                                    <div><?= date('M j, Y', strtotime($payout['processed_at'])) ?></div>
                                    <small class="text-muted"><?= date('g:i A', strtotime($payout['processed_at'])) ?></small>
                                    <?php elseif ($payout['status'] === 'processing'): ?>
                                    <div class="text-info">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Processing...
                                    </div>
                                    <small class="text-muted">ETA: 1-3 days</small>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                onclick="viewPayoutDetails(<?= $payout['id'] ?>)"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if (in_array($payout['status'], ['queued', 'processing'])): ?>
                                        <button class="btn btn-outline-danger" 
                                                onclick="cancelPayout(<?= $payout['id'] ?>)"
                                                title="Cancel Payout">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($payout['status'] === 'failed'): ?>
                                        <button class="btn btn-outline-warning" 
                                                onclick="retryPayout(<?= $payout['id'] ?>)"
                                                title="Retry Payout">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($payout['status'] === 'success'): ?>
                                        <button class="btn btn-outline-success" 
                                                onclick="downloadReceipt(<?= $payout['id'] ?>)"
                                                title="Download Receipt">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Payout History</h5>
                    <p class="text-muted">You haven't made any payout requests yet.</p>
                    <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Request Your First Payout
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div class="modal fade" id="payoutDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>
                    Payout Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="payoutDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshHistory() {
    location.reload();
}

function exportHistory() {
    // TODO: Implement export functionality
    alert('Export functionality will be implemented soon!');
}

function viewPayoutDetails(payoutId) {
    const modal = new bootstrap.Modal(document.getElementById('payoutDetailsModal'));
    modal.show();
    
    // TODO: Load actual payout details via API
    setTimeout(() => {
        document.getElementById('payoutDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Payout Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Payout ID:</strong></td>
                            <td>PO_${payoutId}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span class="badge bg-info">Processing</span></td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td>$500.00</td>
                        </tr>
                        <tr>
                            <td><strong>Processing Fee:</strong></td>
                            <td>$5.50</td>
                        </tr>
                        <tr>
                            <td><strong>Net Amount:</strong></td>
                            <td><strong>$494.50</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Payment Method</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Method:</strong></td>
                            <td>Bank Transfer</td>
                        </tr>
                        <tr>
                            <td><strong>Account:</strong></td>
                            <td>****1234</td>
                        </tr>
                        <tr>
                            <td><strong>Bank:</strong></td>
                            <td>Example Bank</td>
                        </tr>
                    </table>
                    
                    <h6 class="mt-3">Timeline</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <small class="text-muted">Nov 12, 2024 10:30 AM</small>
                            <div>Payout requested</div>
                        </div>
                        <div class="timeline-item">
                            <small class="text-muted">Nov 12, 2024 10:31 AM</small>
                            <div>Payment processing started</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 500);
}

function cancelPayout(payoutId) {
    if (confirm('Are you sure you want to cancel this payout request? The amount will be returned to your available balance.')) {
        // TODO: Implement cancel functionality
        fetch(`<?= ORGANIZER_URL ?>/payouts/${payoutId}/cancel`, {
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
                alert('Failed to cancel payout: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the payout.');
        });
    }
}

function retryPayout(payoutId) {
    if (confirm('Are you sure you want to retry this payout? Please ensure your payment method details are correct.')) {
        // TODO: Implement retry functionality
        alert('Payout retry functionality will be implemented soon!');
    }
}

function downloadReceipt(payoutId) {
    // TODO: Implement receipt download
    window.open(`<?= ORGANIZER_URL ?>/payouts/${payoutId}/receipt`, '_blank');
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 15px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 5px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #0d6efd;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../../layout/organizer_layout.php';
?>
