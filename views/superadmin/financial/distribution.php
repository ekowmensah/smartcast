<!-- Revenue Distribution Analytics -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-line text-success me-2"></i>
            Revenue Distribution
        </h2>
        <p class="text-muted mb-0">Real-time revenue sharing analytics and tenant earnings</p>
    </div>
    <div>
        <button class="btn btn-success" onclick="processAllPayouts()">
            <i class="fas fa-money-check-alt me-2"></i>Process Payouts
        </button>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Distribution Summary Cards -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($distribution['total_platform_revenue'] ?? 0, 2) ?></div>
                    <div>Platform Revenue</div>
                    <div class="small">Total collected fees</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-pie fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($distribution['total_tenant_earnings'] ?? 0, 2) ?></div>
                    <div>Tenant Earnings</div>
                    <div class="small">Total distributed</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($distribution['pending_payouts'] ?? 0, 2) ?></div>
                    <div>Pending Payouts</div>
                    <div class="small">Ready for payout</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($distribution['distribution_rate'] ?? 0, 1) ?>%</div>
                    <div>Avg Fee Rate</div>
                    <div class="small">Platform commission</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-percentage fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution Trends Chart -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    Revenue Distribution Trends (Last 30 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="distributionTrendsChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-doughnut me-2"></i>
                    Fee Rule Usage
                </h5>
            </div>
            <div class="card-body">
                <canvas id="feeRuleChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tenant Balances -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-wallet me-2"></i>
                    Top Tenant Balances
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($distribution['tenant_balances'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Available</th>
                                    <th>Total Earned</th>
                                    <th>Paid Out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distribution['tenant_balances'] as $balance): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($balance['tenant_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($balance['tenant_email']) ?></small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">$<?= number_format($balance['available'], 2) ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">$<?= number_format($balance['total_earned'], 2) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted">$<?= number_format($balance['total_paid'], 2) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($balance['available'] >= 10): ?>
                                            <button class="btn btn-sm btn-success" onclick="processPayout(<?= $balance['tenant_id'] ?>)">
                                                <i class="fas fa-money-check-alt"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No tenant balances found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Fee Rule Breakdown
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($distribution['fee_breakdown'])): ?>
                    <?php foreach ($distribution['fee_breakdown'] as $rule): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold">
                                <?= ucfirst($rule['rule_type']) ?> Rule
                                <?php if ($rule['percentage_rate']): ?>
                                    (<?= $rule['percentage_rate'] ?>%)
                                <?php endif; ?>
                                <?php if ($rule['fixed_amount']): ?>
                                    ($<?= number_format($rule['fixed_amount'], 2) ?>)
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= $rule['usage_count'] ?> transactions</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">$<?= number_format($rule['total_collected'], 2) ?></div>
                            <small class="text-muted">avg: $<?= number_format($rule['avg_fee'], 2) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No fee rules configured.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Revenue Distributions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-stream me-2"></i>
            Recent Revenue Distributions
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($distribution['recent_distributions'])): ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Tenant</th>
                            <th>Event</th>
                            <th>Contestant</th>
                            <th>Total Amount</th>
                            <th>Platform Fee</th>
                            <th>Tenant Earnings</th>
                            <th>Fee %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($distribution['recent_distributions'], 0, 20) as $dist): ?>
                        <tr>
                            <td>
                                <small><?= date('M j, H:i', strtotime($dist['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($dist['tenant_name']) ?></div>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($dist['event_name']) ?></small>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($dist['contestant_name']) ?></small>
                            </td>
                            <td>
                                <span class="fw-bold">$<?= number_format($dist['total_amount'], 2) ?></span>
                            </td>
                            <td>
                                <span class="text-primary fw-bold">$<?= number_format($dist['platform_fee'], 2) ?></span>
                            </td>
                            <td>
                                <span class="text-success fw-bold">$<?= number_format($dist['tenant_amount'], 2) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?= number_format(($dist['platform_fee'] / $dist['total_amount']) * 100, 1) ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <h5>No Revenue Distributions Yet</h5>
                <p class="text-muted">Revenue distributions will appear here as votes are processed.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function processAllPayouts() {
    if (confirm('Process all pending payouts for tenants with minimum balance?')) {
        console.log('Processing all payouts...');
        alert('Payout processing initiated for all eligible tenants.');
    }
}

function processPayout(tenantId) {
    if (confirm('Process payout for this tenant?')) {
        console.log('Processing payout for tenant:', tenantId);
        alert('Payout processing initiated for tenant.');
    }
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        // Distribution Trends Chart
        const trendsCtx = document.getElementById('distributionTrendsChart');
        if (trendsCtx) {
            const trendsData = <?= json_encode($distribution['distribution_trends'] ?? []) ?>;
            
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.map(d => new Date(d.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})),
                    datasets: [{
                        label: 'Platform Fees',
                        data: trendsData.map(d => d.platform_fees),
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.1,
                        fill: true
                    }, {
                        label: 'Tenant Earnings',
                        data: trendsData.map(d => d.tenant_earnings),
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
        
        // Fee Rule Usage Chart
        const feeCtx = document.getElementById('feeRuleChart');
        if (feeCtx) {
            const feeData = <?= json_encode($distribution['fee_breakdown'] ?? []) ?>;
            
            new Chart(feeCtx, {
                type: 'doughnut',
                data: {
                    labels: feeData.map(f => f.rule_type + ' (' + (f.percentage_rate || f.fixed_amount) + ')'),
                    datasets: [{
                        data: feeData.map(f => f.total_collected),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }
});
</script>
