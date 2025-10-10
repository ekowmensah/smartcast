<!-- Payouts Management -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-money-check-alt text-success me-2"></i>
            Payouts
        </h2>
        <p class="text-muted mb-0">Manage tenant payouts and revenue sharing</p>
    </div>
    <div>
        <button class="btn btn-success" onclick="processPendingPayouts()">
            <i class="fas fa-play me-2"></i>Process Pending
        </button>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Payout Statistics -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($payouts['total_paid'] ?? 0) ?></div>
                    <div>Total Paid Out</div>
                    <div class="small">All time</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-money-check-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($payouts['pending_amount'] ?? 0) ?></div>
                    <div>Pending Payouts</div>
                    <div class="small">Awaiting processing</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($payouts['this_month'] ?? 0) ?></div>
                    <div>This Month</div>
                    <div class="small">Payouts processed</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($payouts['avg_payout'] ?? 0) ?></div>
                    <div>Average Payout</div>
                    <div class="small">Per tenant</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Payouts -->
<?php if (!empty($payouts['pending'])): ?>
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Pending Payouts (<?= count($payouts['pending']) ?>)
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Amount</th>
                        <th>Period</th>
                        <th>Bank Details</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payouts['pending'] as $payout): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($payout['tenant_name'] ?? 'Unknown') ?></div>
                            <small class="text-muted"><?= htmlspecialchars($payout['tenant_email'] ?? '') ?></small>
                        </td>
                        <td>
                            <div class="fw-bold text-success">$<?= number_format($payout['amount'] ?? 0, 2) ?></div>
                            <small class="text-muted">Revenue share: <?= $payout['share_percentage'] ?? 0 ?>%</small>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($payout['period'] ?? 'N/A') ?></div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($payout['bank_name'] ?? 'N/A') ?></div>
                            <small class="text-muted">****<?= $payout['account_last_four'] ?? '0000' ?></small>
                        </td>
                        <td>
                            <div><?= date('M j, Y', strtotime($payout['due_date'] ?? 'now')) ?></div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-success" onclick="processPayout(<?= $payout['id'] ?? 0 ?>)">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewPayoutDetails(<?= $payout['id'] ?? 0 ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="holdPayout(<?= $payout['id'] ?? 0 ?>)">
                                    <i class="fas fa-pause"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Payout History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>
            Payout History
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($payouts['history'])): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payout ID</th>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Processed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payouts['history'] as $payout): ?>
                        <tr>
                            <td>
                                <code><?= htmlspecialchars($payout['payout_id'] ?? 'N/A') ?></code>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($payout['tenant_name'] ?? 'Unknown') ?></div>
                            </td>
                            <td>
                                <div class="fw-bold">$<?= number_format($payout['amount'] ?? 0, 2) ?></div>
                                <?php if (!empty($payout['fee'])): ?>
                                    <small class="text-muted">Fee: $<?= number_format($payout['fee'], 2) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $status = $payout['status'] ?? 'unknown';
                                $statusClass = match($status) {
                                    'completed' => 'bg-success',
                                    'processing' => 'bg-info',
                                    'failed' => 'bg-danger',
                                    'on_hold' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($payout['method'] ?? 'N/A') ?></div>
                            </td>
                            <td>
                                <div><?= date('M j, Y', strtotime($payout['processed_at'] ?? 'now')) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($payout['processed_at'] ?? 'now')) ?></small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewPayoutDetails(<?= $payout['id'] ?? 0 ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="downloadPayoutReceipt(<?= $payout['id'] ?? 0 ?>)">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-money-check-alt fa-3x text-muted mb-3"></i>
                <h5>No Payout History</h5>
                <p class="text-muted">No payouts have been processed yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function processPendingPayouts() {
    if (confirm('Are you sure you want to process all pending payouts?')) {
        console.log('Processing all pending payouts...');
        alert('All pending payouts have been queued for processing.');
        location.reload();
    }
}

function processPayout(payoutId) {
    if (confirm('Are you sure you want to process this payout?')) {
        console.log('Processing payout:', payoutId);
        alert('Payout processed successfully!');
        location.reload();
    }
}

function viewPayoutDetails(payoutId) {
    console.log('Viewing payout details:', payoutId);
}

function holdPayout(payoutId) {
    if (confirm('Are you sure you want to put this payout on hold?')) {
        console.log('Holding payout:', payoutId);
        alert('Payout has been put on hold.');
        location.reload();
    }
}

function downloadPayoutReceipt(payoutId) {
    console.log('Downloading payout receipt:', payoutId);
}
</script>
