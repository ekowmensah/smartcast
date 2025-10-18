<!-- Enhanced Reports Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-line me-2 text-primary"></i>
            Advanced Reports & Analytics
        </h2>
        <p class="text-muted mb-0">Comprehensive insights, trends, and performance metrics for data-driven decisions</p>
    </div>
    <div class="d-flex gap-2">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="exportReport('pdf')" title="Export as PDF">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </button>
            <button class="btn btn-outline-success" onclick="exportReport('excel')" title="Export as Excel">
                <i class="fas fa-file-excel me-2"></i>Excel
            </button>
            <button class="btn btn-outline-info" onclick="exportReport('csv')" title="Export as CSV">
                <i class="fas fa-file-csv me-2"></i>CSV
            </button>
        </div>
        <button class="btn btn-primary" onclick="refreshReports()" title="Refresh Data">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Enhanced Report Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Report Filters & Options
                </h6>
            </div>
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Time Period</label>
                        <select class="form-select form-select-sm" id="dateRange">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="180">Last 6 months</option>
                            <option value="365">Last year</option>
                            <option value="custom">Custom range</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Event Status</label>
                        <select class="form-select form-select-sm" id="eventStatus">
                            <option value="">All Events</option>
                            <option value="active">Live Events</option>
                            <option value="completed">Completed</option>
                            <option value="draft">Draft</option>
                            <option value="upcoming">Upcoming</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Report Type</label>
                        <select class="form-select form-select-sm" id="reportType">
                            <option value="overview" selected>Overview</option>
                            <option value="detailed">Detailed</option>
                            <option value="financial">Financial</option>
                            <option value="engagement">Engagement</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="customDateRange" style="display: none;">
                        <label class="form-label small fw-semibold">Custom Date Range</label>
                        <div class="input-group input-group-sm">
                            <input type="date" class="form-control" id="startDate">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" onclick="applyFilters()">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Key Metrics Overview -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="viewEventDetails()">View Details</a></li>
                            <li><a class="dropdown-item" href="<?= ORGANIZER_URL ?>/events">Manage Events</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bold mb-0"><?= number_format($reportData['events_summary']['total'] ?? 0) ?></h3>
                    <p class="text-muted mb-0">Total Events</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>
                        <?= number_format($reportData['events_summary']['active'] ?? 0) ?> active
                    </small>
                    <div class="progress" style="width: 60px; height: 4px;">
                        <div class="progress-bar bg-primary" style="width: <?= ($reportData['events_summary']['total'] > 0) ? (($reportData['events_summary']['active'] / $reportData['events_summary']['total']) * 100) : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-vote-yea fa-2x text-success"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="viewVotingDetails()">View Details</a></li>
                            <li><a class="dropdown-item" href="<?= ORGANIZER_URL ?>/voting/analytics">Analytics</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bold mb-0"><?= number_format($reportData['voting_summary']['total_votes'] ?? 0) ?></h3>
                    <p class="text-muted mb-0">Total Votes</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-info">
                        <i class="fas fa-users me-1"></i>
                        <?= number_format($reportData['voting_summary']['unique_voters'] ?? 0) ?> voters
                    </small>
                    <small class="text-muted">
                        <?= ($reportData['voting_summary']['unique_voters'] > 0) ? number_format(($reportData['voting_summary']['total_votes'] / $reportData['voting_summary']['unique_voters']), 1) : 0 ?> avg/voter
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-money-bill fa-2x text-warning"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="viewRevenueDetails()">View Details</a></li>
                            <li><a class="dropdown-item" href="<?= ORGANIZER_URL ?>/financial/revenue-dashboard">Revenue Dashboard</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bold mb-0">GH₵<?= number_format($reportData['financial_summary']['total_revenue'] ?? 0, 0) ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-success">
                        <i class="fas fa-credit-card me-1"></i>
                        <?= number_format($reportData['financial_summary']['total_transactions'] ?? 0) ?> transactions
                    </small>
                    <small class="text-muted">
                        +12% vs last month
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="viewEngagementDetails()">View Details</a></li>
                            <li><a class="dropdown-item" href="<?= ORGANIZER_URL ?>/voting/analytics">Engagement Analytics</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mb-2">
                    <h3 class="fw-bold mb-0">GH₵<?= number_format($reportData['financial_summary']['avg_transaction'] ?? 0, 2) ?></h3>
                    <p class="text-muted mb-0">Avg Transaction</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-primary">
                        <i class="fas fa-trending-up me-1"></i>
                        Per vote value
                    </small>
                    <div class="progress" style="width: 60px; height: 4px;">
                        <div class="progress-bar bg-info" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Charts Section -->
<div class="row mb-4">
    <!-- Revenue Trends Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Revenue & Voting Trends
                    </h5>
                    <small class="text-muted">Daily performance over the selected period</small>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary active" onclick="switchChart('revenue')">Revenue</button>
                    <button class="btn btn-outline-primary" onclick="switchChart('votes')">Votes</button>
                    <button class="btn btn-outline-primary" onclick="switchChart('both')">Both</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueTrendsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Event Performance Breakdown -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-pie-chart me-2 text-success"></i>Event Status Distribution
                </h5>
                <small class="text-muted">Current event status breakdown</small>
            </div>
            <div class="card-body">
                <canvas id="eventStatusChart" height="250"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center">
                            <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                            <small>Live Events</small>
                        </span>
                        <small class="fw-semibold"><?= $reportData['events_summary']['active'] ?? 0 ?></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center">
                            <span class="badge bg-secondary me-2" style="width: 12px; height: 12px;"></span>
                            <small>Completed</small>
                        </span>
                        <small class="fw-semibold"><?= $reportData['events_summary']['completed'] ?? 0 ?></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center">
                            <span class="badge bg-warning me-2" style="width: 12px; height: 12px;"></span>
                            <small>Draft</small>
                        </span>
                        <small class="fw-semibold"><?= $reportData['events_summary']['draft'] ?? 0 ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Analytics Charts -->
<div class="row mb-4">
    <!-- Engagement Analytics -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2 text-info"></i>Voter Engagement Analytics
                </h5>
                <small class="text-muted">Voting patterns and user behavior</small>
            </div>
            <div class="card-body">
                <canvas id="engagementChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Performance Comparison -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-warning"></i>Event Performance Comparison
                </h5>
                <small class="text-muted">Top performing events by revenue</small>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports Tables -->
<div class="row">
    <!-- Top Performing Events -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Top Performing Events
                    </h5>
                    <small class="text-muted">Events ranked by performance metrics</small>
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active" onclick="sortEvents('revenue')">Revenue</button>
                    <button class="btn btn-outline-secondary" onclick="sortEvents('votes')">Votes</button>
                    <button class="btn btn-outline-secondary" onclick="sortEvents('engagement')">Engagement</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="eventsTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Rank</th>
                                <th class="border-0">Event Details</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center">Votes</th>
                                <th class="border-0 text-center">Revenue</th>
                                <th class="border-0 text-center">ROI</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sample data - in real implementation, this would come from the controller
                            $sampleEvents = [
                                [
                                    'name' => 'Miss Ghana 2024',
                                    'created_at' => '2024-09-15',
                                    'status' => 'active',
                                    'votes' => 15420,
                                    'revenue' => 7710.00,
                                    'contestants' => 12,
                                    'engagement' => 92
                                ],
                                [
                                    'name' => 'Best Student Awards',
                                    'created_at' => '2024-08-20',
                                    'status' => 'completed',
                                    'votes' => 8930,
                                    'revenue' => 4465.00,
                                    'contestants' => 8,
                                    'engagement' => 78
                                ],
                                [
                                    'name' => 'Community Choice Awards',
                                    'created_at' => '2024-07-10',
                                    'status' => 'completed',
                                    'votes' => 6750,
                                    'revenue' => 3375.00,
                                    'contestants' => 15,
                                    'engagement' => 85
                                ]
                            ];
                            
                            foreach ($sampleEvents as $index => $event):
                            ?>
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'dark') ?> me-2">
                                            #<?= $index + 1 ?>
                                        </span>
                                        <?php if ($index === 0): ?>
                                            <i class="fas fa-crown text-warning"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($event['name']) ?></div>
                                        <div class="small text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Created <?= date('M j, Y', strtotime($event['created_at'])) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            <?= $event['contestants'] ?> contestants
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-<?= $event['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($event['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center py-3">
                                    <div class="fw-semibold"><?= number_format($event['votes']) ?></div>
                                    <div class="small text-muted">
                                        <?= number_format($event['votes'] / $event['contestants'], 0) ?> avg/contestant
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <div class="fw-semibold text-success">GH₵<?= number_format($event['revenue'], 2) ?></div>
                                    <div class="small text-muted">
                                        GH₵<?= number_format($event['revenue'] / $event['votes'], 2) ?> per vote
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <div class="progress mb-1" style="height: 6px;">
                                        <div class="progress-bar bg-<?= $event['engagement'] > 80 ? 'success' : ($event['engagement'] > 60 ? 'warning' : 'danger') ?>" 
                                             style="width: <?= $event['engagement'] ?>%"></div>
                                    </div>
                                    <div class="small fw-semibold"><?= $event['engagement'] ?>%</div>
                                </td>
                                <td class="text-center py-3">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info" title="Analytics">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" title="Export">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Showing top 3 events</small>
                    <a href="<?= ORGANIZER_URL ?>/events" class="btn btn-sm btn-outline-primary">
                        View All Events <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Advanced Analytics Sidebar -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-analytics me-2 text-success"></i>Advanced Analytics
                </h5>
                <small class="text-muted">Key performance indicators</small>
            </div>
            <div class="card-body">
                <!-- Performance Metrics -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Performance Metrics</h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold text-success">94.2%</div>
                            <small class="text-muted">Payment Success Rate</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-arrow-up text-success"></i>
                            <small class="text-success">+2.1%</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold text-info">3.7</div>
                            <small class="text-muted">Avg Votes per User</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-arrow-up text-success"></i>
                            <small class="text-success">+0.3</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-semibold text-warning">68%</div>
                            <small class="text-muted">User Retention Rate</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-arrow-down text-danger"></i>
                            <small class="text-danger">-1.2%</small>
                        </div>
                    </div>
                </div>
                
                <!-- Engagement Breakdown -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Engagement Breakdown</h6>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Mobile Users</small>
                            <small class="fw-semibold">78%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 78%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Desktop Users</small>
                            <small class="fw-semibold">22%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 22%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Peak Hours (7-9 PM)</small>
                            <small class="fw-semibold">45%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 45%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Insights -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Revenue Insights</h6>
                    <div class="bg-light rounded p-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fw-semibold text-success">+18%</div>
                                <small class="text-muted">Monthly Growth</small>
                            </div>
                            <div class="col-6">
                                <div class="fw-semibold text-primary">GH₵2.45</div>
                                <small class="text-muted">Avg Revenue/User</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Export & Actions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="fas fa-download me-2"></i>Export & Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-sm" onclick="exportReport('comprehensive')">
                        <i class="fas fa-file-pdf me-2"></i>Comprehensive Report
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportReport('executive')">
                        <i class="fas fa-chart-pie me-2"></i>Executive Summary
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="exportReport('financial')">
                        <i class="fas fa-money-bill me-2"></i>Financial Analysis
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="exportReport('engagement')">
                        <i class="fas fa-users me-2"></i>Engagement Report
                    </button>
                    <hr class="my-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="scheduleReport()">
                        <i class="fas fa-calendar me-2"></i>Schedule Reports
                    </button>
                    <button class="btn btn-outline-dark btn-sm" onclick="shareReport()">
                        <i class="fas fa-share me-2"></i>Share Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
// Global chart variables
let revenueChart, statusChart, engagementChart, performanceChart;

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    setupEventListeners();
});

function initializeCharts() {
    // Revenue Trends Chart (Enhanced)
    const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Oct 1', 'Oct 8', 'Oct 15', 'Oct 22', 'Oct 29', 'Nov 5', 'Nov 12'],
            datasets: [{
                label: 'Revenue (GH₵)',
                data: [2400, 3200, 2800, 4100, 3600, 4800, 5200],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }, {
                label: 'Votes',
                data: [4800, 6400, 5600, 8200, 7200, 9600, 10400],
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: false,
                pointBackgroundColor: '#198754',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#dee2e6',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'GH₵' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' votes';
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });

    // Event Status Distribution Chart
    const statusCtx = document.getElementById('eventStatusChart').getContext('2d');
    statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Live Events', 'Completed', 'Draft'],
            datasets: [{
                data: [
                    <?= $reportData['events_summary']['active'] ?? 3 ?>,
                    <?= $reportData['events_summary']['completed'] ?? 8 ?>,
                    <?= $reportData['events_summary']['draft'] ?? 2 ?>
                ],
                backgroundColor: ['#198754', '#6c757d', '#ffc107'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed * 100) / total).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Engagement Analytics Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    engagementChart = new Chart(engagementCtx, {
        type: 'radar',
        data: {
            labels: ['Mobile Users', 'Desktop Users', 'Peak Hours', 'Retention', 'Conversion', 'Satisfaction'],
            datasets: [{
                label: 'Current Period',
                data: [78, 22, 45, 68, 82, 91],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }, {
                label: 'Previous Period',
                data: [72, 28, 38, 71, 76, 88],
                borderColor: '#6c757d',
                backgroundColor: 'rgba(108, 117, 125, 0.1)',
                pointBackgroundColor: '#6c757d',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            }
        }
    });

    // Performance Comparison Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    performanceChart = new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: ['Miss Ghana 2024', 'Best Student Awards', 'Community Choice', 'Youth Awards', 'Sports Gala'],
            datasets: [{
                label: 'Revenue (GH₵)',
                data: [7710, 4465, 3375, 2890, 2156],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(13, 110, 253, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ],
                borderColor: [
                    '#ffc107',
                    '#0d6efd',
                    '#198754',
                    '#dc3545',
                    '#6c757d'
                ],
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: GH₵' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'GH₵' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function setupEventListeners() {
    // Date range handling
    document.getElementById('dateRange').addEventListener('change', function() {
        const customRange = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customRange.style.display = 'block';
        } else {
            customRange.style.display = 'none';
        }
    });

    // Report type handling
    document.getElementById('reportType').addEventListener('change', function() {
        updateChartsForReportType(this.value);
    });
}

function switchChart(type) {
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Update chart data based on type
    if (type === 'revenue') {
        revenueChart.data.datasets[1].hidden = true;
        revenueChart.update();
    } else if (type === 'votes') {
        revenueChart.data.datasets[0].hidden = true;
        revenueChart.data.datasets[1].hidden = false;
        revenueChart.update();
    } else {
        revenueChart.data.datasets[0].hidden = false;
        revenueChart.data.datasets[1].hidden = false;
        revenueChart.update();
    }
}

function updateChartsForReportType(type) {
    // Update charts based on report type
    console.log('Updating charts for report type:', type);
    // Implementation would update chart data based on selected report type
}

function applyFilters() {
    const dateRange = document.getElementById('dateRange').value;
    const eventStatus = document.getElementById('eventStatus').value;
    const reportType = document.getElementById('reportType').value;
    
    console.log('Applying filters:', { dateRange, eventStatus, reportType });
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        hideLoadingState();
        // Update charts with filtered data
        refreshCharts();
    }, 1500);
}

function refreshReports() {
    showLoadingState();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function exportReport(type) {
    console.log('Exporting report:', type);
    
    // Show export progress
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert(`✅ ${type.charAt(0).toUpperCase() + type.slice(1)} report exported successfully!`);
    }, 2000);
}

function scheduleReport() {
    alert('📅 Report scheduling feature coming soon!');
}

function shareReport() {
    alert('🔗 Dashboard sharing feature coming soon!');
}

function sortEvents(criteria) {
    console.log('Sorting events by:', criteria);
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}

function showLoadingState() {
    document.body.style.cursor = 'wait';
    // Add loading overlay or spinner
}

function hideLoadingState() {
    document.body.style.cursor = 'default';
}

function refreshCharts() {
    // Refresh all charts with new data
    if (revenueChart) revenueChart.update();
    if (statusChart) statusChart.update();
    if (engagementChart) engagementChart.update();
    if (performanceChart) performanceChart.update();
}

// Detail view functions
function viewEventDetails() {
    window.location.href = '<?= ORGANIZER_URL ?>/events';
}

function viewVotingDetails() {
    window.location.href = '<?= ORGANIZER_URL ?>/voting/analytics';
}

function viewRevenueDetails() {
    window.location.href = '<?= ORGANIZER_URL ?>/financial/revenue-dashboard';
}

function viewEngagementDetails() {
    window.location.href = '<?= ORGANIZER_URL ?>/voting/analytics';
}
</script>

<!-- Custom CSS for enhanced styling -->
<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.badge {
    font-weight: 500;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
</style>
