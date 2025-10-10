<!-- Platform Revenue -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-pie text-success me-2"></i>
            Platform Revenue
        </h2>
        <p class="text-muted mb-0">Revenue analytics and financial performance</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-success" onclick="exportRevenueReport()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
            <button class="btn btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
        </div>
    </div>
</div>

<!-- Revenue Metrics -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($revenue['total_revenue'] ?? 0) ?></div>
                    <div>Total Revenue</div>
                    <div class="small">All time</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($revenue['monthly_revenue'] ?? 0) ?></div>
                    <div>This Month</div>
                    <div class="small">Current month</div>
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
                    <div class="fs-4 fw-semibold">$<?= number_format($revenue['avg_monthly'] ?? 0) ?></div>
                    <div>Avg Monthly</div>
                    <div class="small">Last 12 months</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= $revenue['growth_rate'] ?? 0 ?>%</div>
                    <div>Growth Rate</div>
                    <div class="small">vs last month</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-trending-up fa-2x opacity-75"></i>
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
                    Revenue Trends
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueTrendsChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Revenue Sources
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueSourcesChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Breakdown -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    Top Revenue Tenants
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($revenue['top_tenants'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Plan</th>
                                    <th>Monthly</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($revenue['top_tenants'], 0, 10) as $tenant): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($tenant['name'] ?? 'Unknown') ?></div>
                                    </td>
                                    <td><span class="badge bg-info"><?= htmlspecialchars($tenant['plan'] ?? 'N/A') ?></span></td>
                                    <td>$<?= number_format($tenant['monthly_revenue'] ?? 0) ?></td>
                                    <td>$<?= number_format($tenant['total_revenue'] ?? 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No revenue data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>
                    Revenue by Plan
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($revenue['plan_breakdown'])): ?>
                    <?php foreach ($revenue['plan_breakdown'] as $plan): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($plan['name'] ?? 'Unknown Plan') ?></div>
                            <small class="text-muted"><?= number_format($plan['subscriber_count'] ?? 0) ?> subscribers</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">$<?= number_format($plan['revenue'] ?? 0) ?></div>
                            <small class="text-muted"><?= number_format($plan['percentage'] ?? 0, 1) ?>%</small>
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= $plan['percentage'] ?? 0 ?>%"></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No plan revenue data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function exportRevenueReport() {
    console.log('Exporting revenue report...');
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        // Revenue Trends Chart
        const trendsCtx = document.getElementById('revenueTrendsChart');
        if (trendsCtx) {
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [12500, 15200, 18100, 22300, 26800, 31200, 35500, 41200, 46800, 52500, 58200, 64000],
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
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Revenue Sources Chart
        const sourcesCtx = document.getElementById('revenueSourcesChart');
        if (sourcesCtx) {
            new Chart(sourcesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Subscriptions', 'Transaction Fees', 'Premium Features', 'API Usage'],
                    datasets: [{
                        data: [70, 15, 10, 5],
                        backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384', '#4BC0C0']
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
