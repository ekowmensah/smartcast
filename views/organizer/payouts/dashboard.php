<?php 
$content = ob_start(); 
?>

<!-- Payout Dashboard -->
<div class="row">
    <!-- Balance Overview -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-wallet me-2"></i>
                    Balance Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h3 text-success mb-1">$<?= number_format($balance['available'], 2) ?></div>
                            <div class="text-muted small">Available Balance</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h3 text-warning mb-1">$<?= number_format($balance['pending'], 2) ?></div>
                            <div class="text-muted small">Pending</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h3 text-info mb-1">$<?= number_format($balance['total_earned'], 2) ?></div>
                            <div class="text-muted small">Total Earned</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h3 text-primary mb-1">$<?= number_format($balance['total_paid'], 2) ?></div>
                            <div class="text-muted small">Total Paid Out</div>
                        </div>
                    </div>
                </div>
                
                <?php if ($can_request_payout && $balance['available'] >= $schedule['minimum_amount']): ?>
                <div class="text-center mt-4">
                    <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-success btn-lg">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Request Payout
                    </a>
                </div>
                <?php elseif ($balance['available'] < $schedule['minimum_amount']): ?>
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Minimum payout amount is $<?= number_format($schedule['minimum_amount'], 2) ?>. 
                    Current available balance: $<?= number_format($balance['available'], 2) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-outline-success">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Request Payout
                    </a>
                    <a href="<?= ORGANIZER_URL ?>/payouts/methods" class="btn btn-outline-primary">
                        <i class="fas fa-credit-card me-2"></i>
                        Manage Methods
                    </a>
                    <a href="<?= ORGANIZER_URL ?>/payouts/settings" class="btn btn-outline-secondary">
                        <i class="fas fa-cog me-2"></i>
                        Payout Settings
                    </a>
                    <a href="<?= ORGANIZER_URL ?>/payouts/history" class="btn btn-outline-info">
                        <i class="fas fa-history me-2"></i>
                        View History
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Payout Schedule Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Payout Schedule
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Frequency:</strong> 
                    <span class="badge bg-primary"><?= ucfirst($schedule['frequency']) ?></span>
                </div>
                <div class="mb-3">
                    <strong>Minimum Amount:</strong> 
                    $<?= number_format($schedule['minimum_amount'], 2) ?>
                </div>
                <div class="mb-3">
                    <strong>Auto Payout:</strong> 
                    <?php if ($schedule['auto_payout_enabled']): ?>
                        <span class="badge bg-success">Enabled</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Disabled</span>
                    <?php endif; ?>
                </div>
                <?php if ($schedule['next_payout_date']): ?>
                <div class="mb-3">
                    <strong>Next Payout:</strong> 
                    <?= date('M j, Y', strtotime($schedule['next_payout_date'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Stats -->
<?php if ($revenue_stats): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Revenue Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">$<?= number_format($revenue_stats['total_gross'] ?? 0, 2) ?></div>
                            <div class="text-muted small">Total Gross Revenue</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-info mb-1">$<?= number_format($revenue_stats['total_net_amount'] ?? 0, 2) ?></div>
                            <div class="text-muted small">Net Revenue</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-warning mb-1"><?= number_format($revenue_stats['transaction_count'] ?? 0) ?></div>
                            <div class="text-muted small">Transactions</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary mb-1">
                                <?php 
                                $avgTransaction = ($revenue_stats['transaction_count'] ?? 0) > 0 
                                    ? ($revenue_stats['total_gross'] ?? 0) / $revenue_stats['transaction_count'] 
                                    : 0;
                                ?>
                                $<?= number_format($avgTransaction, 2) ?>
                            </div>
                            <div class="text-muted small">Avg Transaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Payouts -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Recent Payouts
                </h5>
                <a href="<?= ORGANIZER_URL ?>/payouts/history" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_payouts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payout ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_payouts as $payout): ?>
                            <tr>
                                <td>
                                    <code><?= htmlspecialchars($payout['payout_id']) ?></code>
                                </td>
                                <td>
                                    <strong>$<?= number_format($payout['amount'], 2) ?></strong>
                                    <?php if ($payout['processing_fee'] ?? 0 > 0): ?>
                                    <br><small class="text-muted">Fee: $<?= number_format($payout['processing_fee'], 2) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= ucwords(str_replace('_', ' ', $payout['payout_method'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'queued' => 'warning',
                                        'processing' => 'info',
                                        'success' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $class = $statusClass[$payout['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $class ?>">
                                        <?= ucfirst($payout['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($payout['created_at'])) ?>
                                    <br><small class="text-muted"><?= date('g:i A', strtotime($payout['created_at'])) ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Payouts Yet</h5>
                    <p class="text-muted">Your payout history will appear here once you request your first payout.</p>
                    <?php if ($can_request_payout): ?>
                    <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-primary">
                        Request Your First Payout
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Payout Methods -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Payout Methods
                </h5>
                <a href="<?= ORGANIZER_URL ?>/payouts/add-method" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($payout_methods)): ?>
                <?php foreach ($payout_methods as $method): ?>
                <div class="d-flex align-items-center mb-3 p-2 border rounded">
                    <div class="flex-grow-1">
                        <div class="fw-bold"><?= htmlspecialchars($method['method_name']) ?></div>
                        <small class="text-muted">
                            <?php
                            // Format account details for display
                            $details = json_decode($method['account_details'], true);
                            $displayText = '';
                            
                            switch ($method['method_type']) {
                                case 'bank_transfer':
                                    $displayText = ($details['bank_name'] ?? 'Bank') . ' - ****' . substr($details['account_number'] ?? '', -4);
                                    break;
                                case 'mobile_money':
                                    $displayText = ($details['provider'] ?? 'Mobile') . ' - ****' . substr($details['phone_number'] ?? '', -4);
                                    break;
                                case 'paypal':
                                    $displayText = $details['email'] ?? 'PayPal Account';
                                    break;
                                case 'stripe':
                                    $displayText = 'Stripe - ****' . substr($details['account_id'] ?? '', -4);
                                    break;
                                default:
                                    $displayText = ucwords(str_replace('_', ' ', $method['method_type']));
                            }
                            
                            echo htmlspecialchars($displayText);
                            ?>
                        </small>
                        <?php if ($method['is_default']): ?>
                        <span class="badge bg-primary ms-2">Default</span>
                        <?php endif; ?>
                        <?php if ($method['is_verified']): ?>
                        <span class="badge bg-success ms-1">Verified</span>
                        <?php else: ?>
                        <span class="badge bg-warning ms-1">Unverified</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center py-3">
                    <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-2">No payout methods configured</p>
                    <a href="<?= ORGANIZER_URL ?>/payouts/add-method" class="btn btn-sm btn-primary">
                        Add Method
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .col-md-3 {
        margin-bottom: 1rem;
    }
    
    .d-grid .btn {
        margin-bottom: 0.5rem;
    }
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../../layout/organizer_layout.php';
?>
