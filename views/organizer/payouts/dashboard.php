<?php 
$content = ob_start(); 
?>

<style>
/* Modern Professional Payout Dashboard */
.payout-dashboard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.dashboard-header {
    text-align: center;
    color: white;
    margin-bottom: 3rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 2rem;
    margin-bottom: 2rem;
}

.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
}

.balance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.balance-card {
    text-align: center;
    position: relative;
    overflow: hidden;
}

.balance-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.balance-amount {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.balance-label {
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.workflow-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.workflow-step {
    text-align: center;
    padding: 1.5rem;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.step-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.step-description {
    font-size: 0.875rem;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.btn-primary-gradient {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

.btn-primary-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.8);
    color: white;
    background: transparent;
    padding: 1rem 2rem;
    border-radius: 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-outline-light:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
    text-decoration: none;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.payout-table {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.table th {
    background: #f8fafc;
    border: none;
    font-weight: 600;
    color: #374151;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    border-color: #f1f5f9;
    vertical-align: middle;
}

@media (max-width: 768px) {
    .workflow-steps {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .balance-amount {
        font-size: 2rem;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<div class="payout-dashboard">
<div class="dashboard-container">

<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1 class="dashboard-title">Payout Management</h1>
    <p class="dashboard-subtitle">Monitor your earnings and manage payout requests with our advanced approval system</p>
</div>

<!-- Balance Overview -->
<div class="balance-grid">
    <div class="glass-card balance-card">
        <div class="balance-amount">₵ <?= number_format($balance['available'] ?? 0, 2) ?></div>
        <div class="balance-label">Available Balance</div>
        <div class="status-indicator bg-success text-white">
            <i class="fas fa-check-circle"></i>
            Ready to withdraw
        </div>
    </div>
    
    <div class="glass-card balance-card">
        <div class="balance-amount">₵ <?= number_format(($balance['pending_approval'] ?? 0) + ($balance['approved_pending'] ?? 0) + ($balance['processing'] ?? 0), 2) ?></div>
        <div class="balance-label">Total Pending</div>
        <?php if (isset($balance['pending_approval']) && $balance['pending_approval'] > 0): ?>
            <div class="status-indicator bg-warning text-dark">
                <i class="fas fa-clock"></i>Awaiting Admin Approval
            </div>
        <?php elseif (isset($balance['approved_pending']) && $balance['approved_pending'] > 0): ?>
            <div class="status-indicator bg-info text-white">
                <i class="fas fa-check"></i>Approved - Processing Soon
            </div>
        <?php elseif (isset($balance['processing']) && $balance['processing'] > 0): ?>
            <div class="status-indicator bg-primary text-white">
                <i class="fas fa-spinner fa-spin"></i>Currently Processing
            </div>
        <?php else: ?>
            <div class="status-indicator bg-light text-muted">
                <i class="fas fa-info-circle"></i>No pending requests
            </div>
        <?php endif; ?>
    </div>
    
    <div class="glass-card balance-card">
        <div class="balance-amount">₵ <?= number_format($balance['total_earned'] ?? 0, 2) ?></div>
        <div class="balance-label">Total Earned</div>
        <div class="status-indicator bg-info text-white">
            <i class="fas fa-chart-line"></i>
            Lifetime earnings
        </div>
    </div>
    
    <div class="glass-card balance-card">
        <div class="balance-amount">₵ <?= number_format($balance['total_paid'] ?? 0, 2) ?></div>
        <div class="balance-label">Total Paid Out</div>
        <div class="status-indicator bg-success text-white">
            <i class="fas fa-money-bill-wave"></i>
            Successfully paid
        </div>
    </div>
</div>

<!-- Payout Workflow Process -->
<div class="glass-card">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-route me-2"></i>
            Payout Process Workflow
        </h3>
    </div>
    
    <div class="workflow-steps">
        <div class="workflow-step">
            <div class="step-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="step-title">1. Request</div>
            <div class="step-description">Submit your payout request with preferred method</div>
        </div>
        
        <div class="workflow-step">
            <div class="step-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="step-title">2. Admin Review</div>
            <div class="step-description">Our team reviews your request (usually within 24 hours)</div>
        </div>
        
        <div class="workflow-step">
            <div class="step-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="step-title">3. Processing</div>
            <div class="step-description">Once approved, your payout enters the processing queue</div>
        </div>
        
        <div class="workflow-step">
            <div class="step-icon">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="step-title">4. Complete</div>
            <div class="step-description">Funds are sent to your chosen payout method</div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <?php if (($can_request_payout ?? false) && ($balance['available'] ?? 0) >= ($schedule['minimum_amount'] ?? 0)): ?>
        <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-primary-gradient">
            <i class="fas fa-money-bill-wave me-2"></i>
            Request Payout Now
        </a>
    <?php endif; ?>
    
    <a href="<?= ORGANIZER_URL ?>/payouts/methods" class="btn btn-outline-light">
        <i class="fas fa-credit-card me-2"></i>
        Manage Methods
    </a>
    
    <a href="<?= ORGANIZER_URL ?>/payouts/settings" class="btn btn-outline-light">
        <i class="fas fa-cog me-2"></i>
        Settings
    </a>
</div>

<!-- Recent Payouts -->
<div class="glass-card">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-history me-2"></i>
            Recent Payouts
        </h3>
        <a href="<?= ORGANIZER_URL ?>/payouts/history" class="btn btn-primary-gradient btn-sm">
            <i class="fas fa-list me-1"></i>View All History
        </a>
    </div>
    
    <?php if (empty($recent_payouts)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-money-bill-wave" style="font-size: 4rem; color: #e5e7eb;"></i>
            </div>
            <h5 class="text-muted mb-3">No Payouts Yet</h5>
            <p class="text-muted mb-4">Your payout history will appear here once you make your first request.</p>
            <?php if (($can_request_payout ?? false) && ($balance['available'] ?? 0) >= ($schedule['minimum_amount'] ?? 0)): ?>
                <a href="<?= ORGANIZER_URL ?>/payouts/request" class="btn btn-primary-gradient">
                    <i class="fas fa-plus me-2"></i>Request Your First Payout
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="payout-table">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Expected Amount</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_payouts as $payout): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">₵ <?= number_format($payout['amount'], 2) ?></div>
                            <?php if (isset($payout['processing_fee']) && $payout['processing_fee'] > 0): ?>
                                <small class="text-muted">Fee: ₵ <?= number_format($payout['processing_fee'], 2) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-success">₵ <?= number_format($payout['net_amount'] ?? ($payout['amount'] - ($payout['processing_fee'] ?? 0)), 2) ?></div>
                            <small class="text-muted">You'll receive</small>
                        </td>
                        <td>
                            <?php
                            $statusConfig = [
                                'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Pending Review'],
                                'approved' => ['class' => 'info', 'icon' => 'check', 'text' => 'Approved'], 
                                'processing' => ['class' => 'primary', 'icon' => 'spinner fa-spin', 'text' => 'Processing'],
                                'paid' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Paid'],
                                'rejected' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Rejected'],
                                'cancelled' => ['class' => 'secondary', 'icon' => 'ban', 'text' => 'Cancelled']
                            ];
                            $config = $statusConfig[$payout['status']] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                            ?>
                            <span class="badge bg-<?= $config['class'] ?> px-3 py-2">
                                <i class="fas fa-<?= $config['icon'] ?> me-1"></i>
                                <?= $config['text'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-muted me-2"></i>
                                <span class="text-capitalize"><?= str_replace('_', ' ', $payout['payout_method'] ?? 'N/A') ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium"><?= isset($payout['created_at']) && $payout['created_at'] ? date('M j, Y', strtotime($payout['created_at'])) : 'N/A' ?></div>
                            <small class="text-muted"><?= isset($payout['created_at']) && $payout['created_at'] ? date('g:i A', strtotime($payout['created_at'])) : '' ?></small>
                            <?php if ($payout['status'] === 'paid'): ?>
                                <div class="mt-1">
                                    <button type="button" class="btn btn-outline-success btn-xs"
                                            onclick="downloadPayoutReceipt(<?= $payout['id'] ?>)" title="Download Receipt">
                                        <i class="fas fa-receipt me-1"></i>Receipt
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="viewPayoutDetails(<?= $payout['id'] ?>)" 
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if ($payout['status'] === 'pending'): ?>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelPayout(<?= $payout['id'] ?>)" 
                                            title="Cancel Request">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</div>
</div>

<script>
function downloadPayoutReceipt(payoutId) {
    window.open('<?= ORGANIZER_URL ?>/payouts/' + payoutId + '/receipt', '_blank');
}

function viewPayoutDetails(payoutId) {
    // Implementation for viewing payout details
    alert('View payout details for ID: ' + payoutId);
}

function cancelPayout(payoutId) {
    if (confirm('Are you sure you want to cancel this payout request?')) {
        // Implementation for canceling payout
        alert('Cancel payout ID: ' + payoutId);
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layout/organizer_layout.php';
?>
