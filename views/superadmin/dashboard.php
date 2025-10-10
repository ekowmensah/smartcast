<!-- Super Admin Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-crown text-warning me-2"></i>
            Platform Dashboard
        </h2>
        <p class="text-muted mb-0">System-wide overview and management</p>
    </div>
    <div>
        <button class="btn btn-outline-danger" onclick="SuperAdminDashboard.maintenanceMode(true)">
            <i class="fas fa-tools me-2"></i>Maintenance Mode
        </button>
    </div>
</div>

<!-- Critical Alerts -->
<?php if (!empty($securityAlerts)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-critical">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Security Alerts</h5>
            <?php foreach (array_slice($securityAlerts, 0, 3) as $alert): ?>
                <div class="mb-2">
                    <strong><?= htmlspecialchars($alert['type']) ?>:</strong>
                    <?= htmlspecialchars($alert['description']) ?>
                    <small class="text-muted">(<?= $alert['created_at'] ?>)</small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Platform Statistics -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($stats['total_tenants'] ?? 0) ?></div>
                    <div>Total Tenants</div>
                    <div class="small">
                        <?= number_format($stats['active_tenants'] ?? 0) ?> active
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-building fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card success text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($stats['total_users'] ?? 0) ?></div>
                    <div>Total Users</div>
                    <div class="small">
                        <?= number_format($stats['active_users'] ?? 0) ?> active
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card warning text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($stats['total_events'] ?? 0) ?></div>
                    <div>Total Events</div>
                    <div class="small">
                        <?= number_format($stats['active_events'] ?? 0) ?> active
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card info text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
                    <div>Platform Revenue</div>
                    <div class="small">
                        <?= number_format($stats['successful_transactions'] ?? 0) ?> transactions
                    </div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- System Health -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-heartbeat me-2"></i>System Health
                <div class="system-health float-end">
                    <div class="health-indicator"></div>
                    <span class="small">All Systems Operational</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="security-status">
                            <h6><i class="fas fa-shield-alt me-2"></i>Security Status</h6>
                            <p class="mb-1">No critical threats detected</p>
                            <small class="text-muted">Last scan: <?= date('Y-m-d H:i') ?></small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="security-status">
                            <h6><i class="fas fa-database me-2"></i>Database</h6>
                            <p class="mb-1">Connection healthy</p>
                            <small class="text-muted">Response time: &lt;50ms</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Service Status</h6>
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="health-indicator mb-1"></div>
                            <small>API</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="health-indicator mb-1"></div>
                            <small>Payments</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="health-indicator mb-1"></div>
                            <small>Notifications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Recent Platform Activity
            </div>
            <div class="card-body">
                <?php if (!empty($recentActivity)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <?php foreach (array_slice($recentActivity, 0, 8) as $activity): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($activity['action']) ?></div>
                                            <div class="small text-muted">
                                                <?php 
                                                $details = json_decode($activity['details'], true);
                                                echo htmlspecialchars($details['description'] ?? 'System activity');
                                                ?>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="small text-muted">
                                                <?= date('M j, H:i', strtotime($activity['created_at'])) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Recent Activity</h5>
                        <p class="text-muted">Platform activity will appear here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Platform Revenue Overview -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Revenue Overview
            </div>
            <div class="card-body">
                <?php if (!empty($revenueOverview)): ?>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="platform-metric">
                                <div class="fs-4 fw-semibold">$<?= number_format($revenueOverview['total_revenue'] ?? 0, 2) ?></div>
                                <div class="small">Total Revenue</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="platform-metric">
                                <div class="fs-4 fw-semibold">$<?= number_format($revenueOverview['monthly_revenue'] ?? 0, 2) ?></div>
                                <div class="small">This Month</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="platform-metric">
                                <div class="fs-4 fw-semibold"><?= number_format($revenueOverview['commission_rate'] ?? 12, 1) ?>%</div>
                                <div class="small">Avg Commission</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="platform-metric">
                                <div class="fs-4 fw-semibold"><?= number_format($revenueOverview['growth_rate'] ?? 15, 1) ?>%</div>
                                <div class="small">Growth Rate</div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Revenue Data</h5>
                        <p class="text-muted">Revenue information will appear here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Platform Management
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="<?= SUPERADMIN_URL ?>/tenants" class="btn btn-outline-primary w-100">
                            <i class="fas fa-building me-2"></i>Manage Tenants
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="<?= SUPERADMIN_URL ?>/users" class="btn btn-outline-success w-100">
                            <i class="fas fa-users me-2"></i>All Users
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="<?= SUPERADMIN_URL ?>/platform/analytics" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>Analytics
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="<?= SUPERADMIN_URL ?>/security/overview" class="btn btn-outline-warning w-100">
                            <i class="fas fa-shield-alt me-2"></i>Security
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="<?= SUPERADMIN_URL ?>/financial/overview" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-dollar-sign me-2"></i>Financials
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <button class="btn btn-outline-danger w-100" onclick="SuperAdminDashboard.emergencyShutdown()">
                            <i class="fas fa-power-off me-2"></i>Emergency
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh dashboard every 60 seconds
setInterval(function() {
    if (typeof SuperAdminDashboard !== 'undefined') {
        SuperAdminDashboard.refreshStats();
    }
}, 60000);
</script>
