<!-- Reports Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-bar me-2"></i>
            Reports & Analytics
        </h2>
        <p class="text-muted mb-0">Comprehensive insights into your events and performance</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </button>
            <button class="btn btn-outline-success" onclick="exportReport('excel')">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small">Date Range</label>
                        <select class="form-select form-select-sm" id="dateRange">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="365">Last year</option>
                            <option value="custom">Custom range</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Event Status</label>
                        <select class="form-select form-select-sm" id="eventStatus">
                            <option value="">All Events</option>
                            <option value="active">Active Only</option>
                            <option value="completed">Completed Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Custom Date Range</label>
                        <div class="input-group input-group-sm">
                            <input type="date" class="form-control" id="startDate">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">&nbsp;</label>
                        <button class="btn btn-primary btn-sm w-100" onclick="refreshReports()">
                            <i class="fas fa-sync me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics Overview -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($reportData['events_summary']['total'] ?? 0) ?></div>
                    <div>Total Events</div>
                    <div class="small">
                        <?= number_format($reportData['events_summary']['active'] ?? 0) ?> active
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card success text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($reportData['voting_summary']['total_votes'] ?? 0) ?></div>
                    <div>Total Votes</div>
                    <div class="small">
                        <?= number_format($reportData['voting_summary']['unique_voters'] ?? 0) ?> unique voters
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-vote-yea fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card warning text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($reportData['financial_summary']['total_revenue'] ?? 0, 2) ?></div>
                    <div>Total Revenue</div>
                    <div class="small">
                        <?= number_format($reportData['financial_summary']['total_transactions'] ?? 0) ?> transactions
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card info text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($reportData['financial_summary']['avg_transaction'] ?? 0, 2) ?></div>
                    <div>Avg Transaction</div>
                    <div class="small">
                        Per vote value
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Event Performance -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-trophy me-2"></i>Event Performance
            </div>
            <div class="card-body">
                <canvas id="eventPerformanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Revenue Trends -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Revenue Trends
            </div>
            <div class="card-body">
                <canvas id="revenueTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Top Events -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-star me-2"></i>Top Performing Events
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Status</th>
                                <th>Votes</th>
                                <th>Revenue</th>
                                <th>Engagement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-semibold">Sample Event 1</div>
                                    <div class="small text-muted">Created 2 weeks ago</div>
                                </td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <div class="fw-semibold">2,456</div>
                                    <div class="small text-muted">+12% vs avg</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">$1,228.00</div>
                                    <div class="small text-muted">$0.50 per vote</div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                    <div class="small text-muted">85% engagement</div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold">Sample Event 2</div>
                                    <div class="small text-muted">Created 1 month ago</div>
                                </td>
                                <td><span class="badge bg-secondary">Completed</span></td>
                                <td>
                                    <div class="fw-semibold">1,892</div>
                                    <div class="small text-muted">-5% vs avg</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">$946.00</div>
                                    <div class="small text-muted">$0.50 per vote</div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-warning" style="width: 72%"></div>
                                    </div>
                                    <div class="small text-muted">72% engagement</div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tachometer-alt me-2"></i>Performance Metrics
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="fs-5 fw-semibold text-success">92.5%</div>
                        <div class="small text-muted">Success Rate</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-5 fw-semibold text-info">4.2</div>
                        <div class="small text-muted">Avg Rating</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Vote Completion Rate</span>
                        <span class="small fw-semibold">87%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 87%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">User Retention</span>
                        <span class="small fw-semibold">73%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 73%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Revenue Growth</span>
                        <span class="small fw-semibold">+15%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-download me-2"></i>Export Options
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="exportReport('detailed')">
                        <i class="fas fa-file-alt me-2"></i>Detailed Report
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportReport('summary')">
                        <i class="fas fa-chart-pie me-2"></i>Executive Summary
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="exportReport('financial')">
                        <i class="fas fa-calculator me-2"></i>Financial Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Event Performance Chart
    const eventCtx = document.getElementById('eventPerformanceChart').getContext('2d');
    new Chart(eventCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Completed', 'Draft', 'Suspended'],
            datasets: [{
                data: [
                    <?= $reportData['events_summary']['active'] ?? 0 ?>,
                    <?= $reportData['events_summary']['completed'] ?? 0 ?>,
                    <?= $reportData['events_summary']['draft'] ?? 0 ?>,
                    1
                ],
                backgroundColor: ['#28a745', '#6c757d', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Revenue Trends Chart
    const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [1200, 1900, 3000, 5000, 2000, 3000],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
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
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
}

function refreshReports() {
    // Implement report refresh logic
    console.log('Refreshing reports...');
    location.reload();
}

function exportReport(type) {
    // Implement export functionality
    console.log('Exporting report:', type);
    alert('Export functionality will be implemented soon!');
}

// Date range handling
document.getElementById('dateRange').addEventListener('change', function() {
    const customInputs = document.querySelector('.col-md-4');
    if (this.value === 'custom') {
        customInputs.style.display = 'block';
    } else {
        customInputs.style.display = 'none';
    }
});
</script>
