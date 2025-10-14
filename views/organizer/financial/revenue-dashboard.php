<!-- Tenant Revenue Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-line text-success me-2"></i>
            Revenue Dashboard
        </h2>
        <p class="text-muted mb-0">Real-time earnings from your voting events</p>
    </div>
    <div>
        <button class="btn btn-success" onclick="requestPayout()" <?= ($balance['available'] < 10) ? 'disabled' : '' ?>>
            <i class="fas fa-money-check-alt me-2"></i>Request Payout
        </button>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Revenue Summary Cards -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">GH₵<?= number_format($balance['available'] ?? 0, 2) ?></div>
                    <div>Available Balance</div>
                    <div class="small">Ready for payout</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-wallet fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">GH₵<?= number_format($balance['total_earned'] ?? 0, 2) ?></div>
                    <div>Total Earned</div>
                    <div class="small">All time</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-pie fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">GH₵<?= number_format($balance['total_paid'] ?? 0, 2) ?></div>
                    <div>Total Paid Out</div>
                    <div class="small">Lifetime payouts</div>
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
                    <div class="fs-4 fw-semibold">GH₵<?= number_format($todayEarnings ?? 0, 2) ?></div>
                    <div>Today's Earnings</div>
                    <div class="small">Last 24 hours</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Charts -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    Revenue Trend (Last 30 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Top Earning Events
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topEvents)): ?>
                    <?php foreach ($topEvents as $event): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($event['name']) ?></div>
                            <small class="text-muted"><?= $event['transaction_count'] ?> transactions</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">GH₵<?= number_format($event['total_revenue'], 2) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No revenue data yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>
            Recent Revenue Transactions
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($recentTransactions)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Event</th>
                            <th>Votes</th>
                            <th>Gross Amount</th>
                            <th>Platform Fee</th>
                            <th>Your Earnings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $transaction): ?>
                        <tr>
                            <td>
                                <div><?= date('M j, Y', strtotime($transaction['created_at'])) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($transaction['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($transaction['event_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($transaction['contestant_name']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $transaction['vote_count'] ?> votes</span>
                            </td>
                            <td>
                                <div class="fw-bold">GH₵<?= number_format($transaction['amount'], 2) ?></div>
                            </td>
                            <td>
                                <?php 
                                $platformFee = $transaction['calculated_platform_fee'] ?? $transaction['platform_fee'] ?? 0;
                                $feePercentage = $transaction['calculated_fee_percentage'] ?? 0;
                                
                                // If no calculated fee percentage, calculate it from the amounts
                                if ($feePercentage == 0 && $transaction['amount'] > 0 && $platformFee > 0) {
                                    $feePercentage = ($platformFee / $transaction['amount']) * 100;
                                }
                                ?>
                                <div class="text-danger">-GH₵<?= number_format($platformFee, 2) ?></div>
                                <small class="text-muted"><?= number_format($feePercentage, 1) ?>%</small>
                            </td>
                            <td>
                                <?php 
                                $netAmount = $transaction['calculated_net_amount'] ?? $transaction['net_amount'] ?? 0;
                                ?>
                                <div class="fw-bold text-success">+GH₵<?= number_format($netAmount, 2) ?></div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <h5>No Revenue Yet</h5>
                <p class="text-muted">Start receiving votes to see your earnings here!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payout Information -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>
            Payout Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Current Payout Method</h6>
                <?php if (!empty($payoutMethod)): ?>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-<?= $payoutMethod['type'] === 'bank_transfer' ? 'university' : 'mobile-alt' ?> me-2"></i>
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($payoutMethod['name']) ?></div>
                            <small class="text-muted">****<?= substr($payoutMethod['account'], -4) ?></small>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No payout method configured. <a href="/organizer/settings/payouts">Set up now</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h6>Payout Schedule</h6>
                <p class="mb-1"><strong>Minimum Amount:</strong> $10.00</p>
                <p class="mb-1"><strong>Processing Time:</strong> 1-3 business days</p>
                <p class="mb-0"><strong>Next Auto-Payout:</strong> <?= $nextAutoPayout ?? 'Not scheduled' ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function requestPayout() {
    const availableBalance = <?= $balance['available'] ?? 0 ?>;
    
    if (availableBalance < 10) {
        alert('Minimum payout amount is $10.00');
        return;
    }
    
    if (confirm(`Request payout of $${availableBalance.toFixed(2)}?`)) {
        // Implementation for payout request
        console.log('Requesting payout of:', availableBalance);
        alert('Payout request submitted! You will receive confirmation shortly.');
    }
}

// Initialize revenue chart
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($chartLabels ?? []) ?>,
                    datasets: [{
                        label: 'Daily Revenue',
                        data: <?= json_encode($chartData ?? []) ?>,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
