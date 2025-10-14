<!-- Modern Revenue Dashboard -->
<div class="revenue-dashboard-wrapper">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-info">
                    <div class="header-badge">
                        <i class="fas fa-chart-line"></i>
                        <span>Live Revenue</span>
                    </div>
                    <h1 class="dashboard-title">Revenue Dashboard</h1>
                    <p class="dashboard-subtitle">Real-time earnings and financial insights from your voting events</p>
                </div>
                <div class="header-actions">
                    <button class="btn-glass btn-refresh" onclick="location.reload()" title="Refresh Data">
                        <i class="fas fa-sync"></i>
                        <span>Refresh</span>
                    </button>
                    <button class="btn-glass btn-payout" onclick="requestPayout()" <?= ($balance['available'] < 10) ? 'disabled' : '' ?> title="Request Payout">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Request Payout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Stats Overview -->
    <div class="stats-overview">
        <div class="container-fluid">
            <div class="stats-grid">
                <!-- Available Balance -->
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">GH₵<?= number_format($balance['available'] ?? 0, 2) ?></div>
                        <div class="stat-label">Available Balance</div>
                        <div class="stat-meta">Ready for payout</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                <!-- Total Earned -->
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">GH₵<?= number_format($balance['total_earned'] ?? 0, 2) ?></div>
                        <div class="stat-label">Total Earned</div>
                        <div class="stat-meta">All time earnings</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>

                <!-- Today's Earnings -->
                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">GH₵<?= number_format($todayEarnings ?? 0, 2) ?></div>
                        <div class="stat-label">Today's Earnings</div>
                        <div class="stat-meta">Last 24 hours</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <!-- Total Paid Out -->
                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">GH₵<?= number_format($balance['total_paid'] ?? 0, 2) ?></div>
                        <div class="stat-label">Total Paid Out</div>
                        <div class="stat-meta">Lifetime payouts</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="content-grid">
                
                <!-- Revenue Chart -->
                <div class="chart-section">
                    <div class="dashboard-card">
                        <div class="card-header-premium">
                            <div class="card-title-section">
                                <div class="card-icon">
                                    <i class="fas fa-chart-area"></i>
                                </div>
                                <div>
                                    <h3 class="card-title">Revenue Trend</h3>
                                    <p class="card-subtitle">Last 30 days performance</p>
                                </div>
                            </div>
                            <div class="chart-controls">
                                <div class="btn-group btn-group-premium">
                                    <button class="btn btn-premium active" onclick="updateChartPeriod('30d')">30D</button>
                                    <button class="btn btn-premium" onclick="updateChartPeriod('7d')">7D</button>
                                    <button class="btn btn-premium" onclick="updateChartPeriod('24h')">24H</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body-premium">
                            <div class="chart-container">
                                <canvas id="revenueChart" height="100"></canvas>
                                <!-- Debug Info for Chart -->
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                                    Chart Data: <?= isset($chartData) ? count($chartData) . ' data points' : 'No data' ?> | 
                                    Labels: <?= isset($chartLabels) ? count($chartLabels) . ' labels' : 'No labels' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    
                    <!-- Top Earning Events -->
                    <div class="dashboard-card">
                        <div class="card-header-premium">
                            <div class="card-title-section">
                                <div class="card-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4 class="card-title">Top Events</h4>
                            </div>
                        </div>
                        <div class="card-body-premium">
                            <?php if (!empty($topEvents)): ?>
                                <div class="events-list">
                                    <?php foreach ($topEvents as $index => $event): ?>
                                        <div class="event-item">
                                            <div class="event-rank">
                                                <span class="rank-badge rank-<?= $index + 1 ?>"><?= $index + 1 ?></span>
                                            </div>
                                            <div class="event-info">
                                                <div class="event-name"><?= htmlspecialchars($event['name']) ?></div>
                                                <div class="event-meta"><?= $event['transaction_count'] ?> transactions</div>
                                            </div>
                                            <div class="event-revenue">
                                                <div class="revenue-amount">GH₵<?= number_format($event['total_revenue'], 2) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h5>No Revenue Data</h5>
                                    <p>Create events and start receiving votes to see your top earning events here!</p>
                                    <!-- Debug Info -->
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem;">
                                        Debug: <?= isset($topEvents) ? 'Array with ' . count($topEvents) . ' items' : 'Variable not set' ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payout Information -->
                    <div class="dashboard-card">
                        <div class="card-header-premium">
                            <div class="card-title-section">
                                <div class="card-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h4 class="card-title">Payout Info</h4>
                            </div>
                        </div>
                        <div class="card-body-premium">
                            <div class="payout-info">
                                <div class="info-item">
                                    <div class="info-label">Minimum Amount</div>
                                    <div class="info-value">GH₵10.00</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Processing Time</div>
                                    <div class="info-value">1-3 business days</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Next Auto-Payout</div>
                                    <div class="info-value">Monthly on 1st</div>
                                </div>
                            </div>
                            
                            <?php if ($balance['available'] >= 10): ?>
                                <div class="payout-action">
                                    <button class="btn-payout-primary" onclick="requestPayout()">
                                        <i class="fas fa-money-check-alt"></i>
                                        Request Payout Now
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="payout-notice">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Minimum payout amount not reached</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="transactions-section">
                <div class="dashboard-card">
                    <div class="card-header-premium">
                        <div class="card-title-section">
                            <div class="card-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h3 class="card-title">Recent Revenue Transactions</h3>
                                <p class="card-subtitle">Latest earnings from your events</p>
                            </div>
                        </div>
                        <div class="transaction-filters">
                            <button class="filter-btn active" onclick="filterTransactions('all')">All</button>
                            <button class="filter-btn" onclick="filterTransactions('today')">Today</button>
                            <button class="filter-btn" onclick="filterTransactions('week')">This Week</button>
                        </div>
                    </div>
                    <div class="card-body-premium">
                        <?php if (!empty($recentTransactions)): ?>
                            <div class="transactions-table">
                                <div class="table-header">
                                    <div class="col-date">Date & Time</div>
                                    <div class="col-event">Event & Contestant</div>
                                    <div class="col-votes">Votes</div>
                                    <div class="col-gross">Gross Amount</div>
                                    <div class="col-fee">Platform Fee</div>
                                    <div class="col-net">Your Earnings</div>
                                </div>
                                <div class="table-body">
                                    <?php foreach ($recentTransactions as $transaction): ?>
                                        <div class="transaction-row">
                                            <div class="col-date">
                                                <div class="date-primary"><?= date('M j, Y', strtotime($transaction['created_at'])) ?></div>
                                                <div class="date-secondary"><?= date('H:i', strtotime($transaction['created_at'])) ?></div>
                                            </div>
                                            <div class="col-event">
                                                <div class="event-primary"><?= htmlspecialchars($transaction['event_name']) ?></div>
                                                <div class="event-secondary"><?= htmlspecialchars($transaction['contestant_name']) ?></div>
                                            </div>
                                            <div class="col-votes">
                                                <span class="votes-badge"><?= $transaction['vote_count'] ?></span>
                                            </div>
                                            <div class="col-gross">
                                                <div class="amount-primary">GH₵<?= number_format($transaction['amount'], 2) ?></div>
                                            </div>
                                            <div class="col-fee">
                                                <?php 
                                                $platformFee = $transaction['calculated_platform_fee'] ?? $transaction['platform_fee'] ?? 0;
                                                $feePercentage = $transaction['calculated_fee_percentage'] ?? 0;
                                                
                                                if ($feePercentage == 0 && $transaction['amount'] > 0 && $platformFee > 0) {
                                                    $feePercentage = ($platformFee / $transaction['amount']) * 100;
                                                }
                                                ?>
                                                <div class="fee-amount">-GH₵<?= number_format($platformFee, 2) ?></div>
                                                <div class="fee-percentage"><?= number_format($feePercentage, 1) ?>%</div>
                                            </div>
                                            <div class="col-net">
                                                <?php 
                                                $netAmount = $transaction['calculated_net_amount'] ?? $transaction['net_amount'] ?? 0;
                                                ?>
                                                <div class="net-amount">+GH₵<?= number_format($netAmount, 2) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4>No Revenue Yet</h4>
                                <p>Start receiving votes to see your earnings here!</p>
                                <button class="btn-primary" onclick="window.location.href='<?= ORGANIZER_URL ?>/events'">
                                    <i class="fas fa-plus"></i>
                                    Create Your First Event
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<style>
/* ===== PROFESSIONAL REVENUE DASHBOARD STYLES ===== */
:root {
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --info-color: #06b6d4;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --bg-primary: #f8fafc;
    --bg-secondary: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --border-radius: 12px;
    --transition: all 0.2s ease;
}

.revenue-dashboard-wrapper {
    min-height: 100vh;
    background: var(--bg-primary);
    color: var(--text-primary);
}

/* ===== DASHBOARD HEADER ===== */
.dashboard-header {
    padding: 2rem 0;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, var(--success-color), #34d399);
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
}

.dashboard-subtitle {
    font-size: 1.125rem;
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-glass {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}

.btn-glass:hover {
    background: var(--bg-primary);
    border-color: var(--primary-color);
    color: var(--primary-color);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-glass:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== STATS OVERVIEW ===== */
.stats-overview {
    padding: 2rem 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-color);
}

.stat-card.stat-success::before {
    background: var(--success-color);
}

.stat-card.stat-info::before {
    background: var(--info-color);
}

.stat-card.stat-warning::before {
    background: var(--warning-color);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-color);
}

.stat-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--border-radius);
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    color: var(--primary-color);
}

.stat-card.stat-success .stat-icon {
    color: var(--success-color);
    background: rgba(16, 185, 129, 0.1);
}

.stat-card.stat-info .stat-icon {
    color: var(--info-color);
    background: rgba(6, 182, 212, 0.1);
}

.stat-card.stat-warning .stat-icon {
    color: var(--warning-color);
    background: rgba(245, 158, 11, 0.1);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.stat-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.stat-meta {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.stat-trend {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: var(--text-muted);
}

/* ===== MAIN CONTENT ===== */
.main-content {
    padding: 2rem 0;
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.dashboard-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius);
    backdrop-filter: blur(20px);
    transition: var(--transition);
    overflow: hidden;
}

.dashboard-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-md);
}

.card-header-premium {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--text-primary);
}

.card-subtitle {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0.25rem 0 0 0;
}

.card-body-premium {
    padding: 1.5rem;
}

/* ===== CHART CONTROLS ===== */
.btn-group-premium {
    display: flex;
    border-radius: 8px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
}

.btn-premium {
    padding: 0.5rem 1rem;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-premium.active,
.btn-premium:hover {
    background: var(--primary-color);
    color: white;
}

/* ===== EVENTS LIST ===== */
.events-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.event-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition);
}

.event-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.rank-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.rank-badge.rank-1 {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #1a1a1a;
}

.rank-badge.rank-2 {
    background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
    color: #1a1a1a;
}

.rank-badge.rank-3 {
    background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%);
    color: #ffffff;
}

.event-info {
    flex: 1;
}

.event-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.event-meta {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.revenue-amount {
    font-weight: 700;
    color: var(--success-color);
    font-size: 1.125rem;
}

/* ===== PAYOUT INFO ===== */
.payout-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.info-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.info-value {
    font-weight: 600;
    color: var(--text-primary);
}

.btn-payout-primary {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--success-gradient);
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-payout-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.payout-notice {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 8px;
    color: var(--warning-color);
    font-size: 0.875rem;
}

/* ===== TRANSACTIONS TABLE ===== */
.transactions-section {
    margin-top: 2rem;
}

.transaction-filters {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-secondary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
}

.filter-btn.active,
.filter-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.transactions-table {
    width: 100%;
}

.table-header {
    display: grid;
    grid-template-columns: 1.2fr 2fr 0.8fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-radius: 8px;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.table-body {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.transaction-row {
    display: grid;
    grid-template-columns: 1.2fr 2fr 0.8fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    transition: var(--transition);
    align-items: center;
}

.transaction-row:hover {
    background: var(--bg-primary);
    border-color: var(--primary-color);
    box-shadow: var(--shadow-sm);
}

.date-primary {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.date-secondary {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.event-primary {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.event-secondary {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.votes-badge {
    padding: 0.25rem 0.75rem;
    background: linear-gradient(135deg, var(--primary-color), #60a5fa);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.amount-primary {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.fee-amount {
    font-weight: 600;
    color: var(--danger-color);
    font-size: 0.875rem;
}

.fee-percentage {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.net-amount {
    font-weight: 700;
    color: var(--success-color);
    font-size: 0.875rem;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: var(--text-muted);
}

.empty-state h4,
.empty-state h5 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color), #60a5fa);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header,
    .transaction-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .table-header {
        display: none;
    }
    
    .transaction-row {
        display: block;
        padding: 1rem;
    }
    
    .transaction-row > div {
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .transaction-row > div::before {
        content: attr(data-label);
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.75rem;
    }
}
</style>

<script>
function requestPayout() {
    const availableBalance = <?= $balance['available'] ?? 0 ?>;
    
    if (availableBalance < 10) {
        alert('Minimum payout amount is GH₵10.00');
        return;
    }
    
    if (confirm(`Request payout of GH₵${availableBalance.toFixed(2)}?`)) {
        // Implementation for payout request
        fetch('/organizer/financial/request-payout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                amount: availableBalance
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payout request submitted successfully! You will receive confirmation shortly.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to submit payout request'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting your payout request. Please try again.');
        });
    }
}

function updateChartPeriod(period) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-premium').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Update chart data based on period
    // This would typically make an AJAX request to get new data
    console.log('Updating chart for period:', period);
}

function filterTransactions(filter) {
    // Remove active class from all filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Filter transactions based on selection
    const rows = document.querySelectorAll('.transaction-row');
    const now = new Date();
    
    rows.forEach(row => {
        const dateText = row.querySelector('.date-primary').textContent;
        const transactionDate = new Date(dateText);
        let show = true;
        
        if (filter === 'today') {
            show = transactionDate.toDateString() === now.toDateString();
        } else if (filter === 'week') {
            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            show = transactionDate >= weekAgo;
        }
        
        row.style.display = show ? 'grid' : 'none';
    });
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
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.8)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                callback: function(value) {
                                    return 'GH₵' + value.toFixed(2);
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
    }
});
</script>
