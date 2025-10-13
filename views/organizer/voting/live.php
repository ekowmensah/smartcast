<!-- Live Results Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-chart-line me-2"></i>
            Live Results
            <?php if ($selectedEvent): ?>
                <span class="badge bg-primary ms-2"><?= htmlspecialchars($selectedEvent['name']) ?></span>
            <?php endif; ?>
        </h2>
        <p class="text-muted mb-0">Real-time voting results and analytics</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="refreshResults()" id="refreshBtn">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
            <button class="btn btn-outline-success" onclick="exportResults()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-outline-info" onclick="toggleAutoRefresh()" id="autoRefreshBtn">
                <i class="fas fa-pause me-2"></i>Auto-refresh
            </button>
        </div>
    </div>
</div>

<!-- Event Selector and Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body py-3">
                <label class="form-label small text-muted mb-2">Select Event</label>
                <select class="form-select" id="eventSelector" onchange="changeEvent()">
                    <option value="">All Active Events</option>
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <option value="<?= $event['id'] ?>" <?= $selectedEventId == $event['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($event['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="text-center mt-2">
                    <div class="fw-semibold text-success" id="liveStatus">
                        <i class="fas fa-circle me-1 pulse"></i>LIVE
                    </div>
                    <div class="small text-muted">Auto-refresh: <span id="refreshInterval">30s</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body py-3">
                <div class="row text-center" id="liveStatsContainer">
                    <div class="col-3">
                        <div class="h4 fw-bold mb-0"><?= number_format($liveStats['total_votes'] ?? 0) ?></div>
                        <div class="small opacity-75">Total Votes</div>
                    </div>
                    <div class="col-3">
                        <div class="h4 fw-bold mb-0"><?= number_format($liveStats['votes_this_hour'] ?? 0) ?></div>
                        <div class="small opacity-75">This Hour</div>
                    </div>
                    <div class="col-3">
                        <div class="h4 fw-bold mb-0">GHS <?= number_format($liveStats['total_revenue'] ?? 0, 2) ?></div>
                        <div class="small opacity-75">Revenue</div>
                    </div>
                    <div class="col-3">
                        <div class="h4 fw-bold mb-0"><?= number_format($liveStats['unique_voters'] ?? 0) ?></div>
                        <div class="small opacity-75">Unique Voters</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Results Dashboard -->
<div class="row">
    <!-- Top Contestants -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Live Leaderboard
                        <?php if ($selectedEvent): ?>
                            <small class="text-muted">- <?= htmlspecialchars($selectedEvent['name']) ?></small>
                        <?php endif; ?>
                    </h5>
                    <div class="small text-muted">
                        Last updated: <span id="lastUpdated"><?= date('H:i:s') ?></span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0" id="leaderboardContainer">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Rank</th>
                                <th>Contestant</th>
                                <th width="120">Votes</th>
                                <th width="150">Percentage</th>
                                <th width="100">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topContestants)): ?>
                                <?php 
                                $totalVotes = array_sum(array_map(function($contestant) {
                                    return $contestant['total_votes'] ?? 0;
                                }, $topContestants));
                                foreach ($topContestants as $index => $contestant): 
                                    $rank = $index + 1;
                                    $contestantVotes = $contestant['total_votes'] ?? 0;
                                    $percentage = $totalVotes > 0 ? ($contestantVotes / $totalVotes) * 100 : 0;
                                    $revenue = $contestant['revenue'] ?? 0;
                                ?>
                                    <tr class="<?= $rank === 1 ? 'table-warning' : ($rank <= 3 ? 'table-light' : '') ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($rank === 1): ?>
                                                    <i class="fas fa-crown text-warning me-2"></i>
                                                <?php elseif ($rank === 2): ?>
                                                    <i class="fas fa-medal text-secondary me-2"></i>
                                                <?php elseif ($rank === 3): ?>
                                                    <i class="fas fa-medal text-warning me-2"></i>
                                                <?php else: ?>
                                                    <span class="me-4"></span>
                                                <?php endif; ?>
                                                <span class="fw-bold"><?= $rank ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($contestant['image_url']): ?>
                                                    <img src="<?= htmlspecialchars(image_url($contestant['image_url'])) ?>" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                        <?= strtoupper(substr($contestant['name'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($contestant['name']) ?></div>
                                                    <div class="small text-muted">
                                                        <?= htmlspecialchars($contestant['contestant_code'] ?? 'N/A') ?>
                                                        <?php if ($contestant['category_name']): ?>
                                                            • <?= htmlspecialchars($contestant['category_name']) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-primary"><?= number_format($contestantVotes) ?></div>
                                            <div class="small text-muted"><?= number_format($contestant['vote_count'] ?? 0) ?> transactions</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= number_format($percentage, 1) ?>%</div>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-<?= $rank === 1 ? 'warning' : ($rank <= 3 ? 'info' : 'primary') ?>" 
                                                     style="width: <?= min($percentage, 100) ?>%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-success">GHS <?= number_format($revenue, 2) ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                                            <h5>No voting data available</h5>
                                            <p class="mb-0">
                                                <?php if (!$selectedEvent): ?>
                                                    Select an event to view live results
                                                <?php else: ?>
                                                    No votes have been cast for this event yet
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live Stats -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-pulse me-2"></i>Real-time Activity
                </h6>
            </div>
            <div class="card-body" id="activityContainer">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Votes per minute</span>
                        <span class="small fw-semibold"><?= number_format($liveStats['votes_per_minute'] ?? 0, 1) ?></span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <?php 
                        $voteRate = min(($liveStats['votes_per_minute'] ?? 0) * 5, 100); // Scale to 100%
                        ?>
                        <div class="progress-bar bg-success" style="width: <?= $voteRate ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Revenue rate</span>
                        <span class="small fw-semibold">GHS <?= number_format(($liveStats['total_revenue'] ?? 0) / max($liveStats['votes_this_hour'] ?? 1, 1), 2) ?>/vote</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <?php 
                        $revenueRate = min(($liveStats['total_revenue'] ?? 0) / 100, 100); // Scale to percentage
                        ?>
                        <div class="progress-bar bg-warning" style="width: <?= $revenueRate ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Unique voters</span>
                        <span class="small fw-semibold"><?= number_format($liveStats['unique_voters'] ?? 0) ?> total</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <?php 
                        $voterRate = min(($liveStats['unique_voters'] ?? 0) / 10, 100); // Scale to percentage
                        ?>
                        <div class="progress-bar bg-info" style="width: <?= $voterRate ?>%"></div>
                    </div>
                </div>
                
                <?php if ($selectedEvent): ?>
                <div class="text-center mt-3 pt-3 border-top">
                    <div class="small text-muted mb-1">Event Status</div>
                    <span class="badge bg-success">
                        <i class="fas fa-broadcast-tower me-1"></i>
                        <?= ucfirst($selectedEvent['status']) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Votes
                </h6>
                <small class="text-muted">Live feed</small>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" id="recentVotes">
                    <?php if (!empty($recentVotes)): ?>
                        <?php foreach ($recentVotes as $vote): ?>
                            <?php
                            $timeAgo = $vote['seconds_ago'];
                            if ($timeAgo < 60) {
                                $timeDisplay = $timeAgo . 's ago';
                            } elseif ($timeAgo < 3600) {
                                $timeDisplay = floor($timeAgo / 60) . 'm ago';
                            } else {
                                $timeDisplay = floor($timeAgo / 3600) . 'h ago';
                            }
                            ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold small"><?= htmlspecialchars($vote['contestant_name']) ?></div>
                                        <div class="text-muted small">
                                            <?= number_format($vote['quantity']) ?> votes
                                            <?php if ($vote['amount']): ?>
                                                • GHS <?= number_format($vote['amount'], 2) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="small text-muted"><?= $timeDisplay ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-vote-yea fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0 small">No recent votes</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Voting Trends Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>Voting Trends (Last 24 Hours)
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active" onclick="updateChartPeriod('24h')">24H</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="updateChartPeriod('7d')">7D</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="updateChartPeriod('30d')">30D</button>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($votingTrends['contestants'])): ?>
                    <canvas id="votingTrendsChart" width="400" height="100"></canvas>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No voting trends data</h5>
                        <p class="text-muted mb-0">
                            <?php if (!$selectedEvent): ?>
                                Select an event to view voting trends
                            <?php else: ?>
                                No votes have been cast in the last 24 hours
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.15s ease-in-out;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let refreshInterval;
let chart;
let autoRefreshEnabled = true;
let currentEventId = <?= json_encode($selectedEventId) ?>;

// Initialize live results
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($votingTrends['contestants'])): ?>
    initializeChart();
    <?php endif; ?>
    startAutoRefresh();
});

function initializeChart() {
    const canvas = document.getElementById('votingTrendsChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const votingData = <?= json_encode($votingTrends) ?>;
    
    // Generate hour labels
    const labels = [];
    for (let i = 0; i < 24; i++) {
        labels.push(i.toString().padStart(2, '0') + ':00');
    }
    
    // Generate datasets from real data
    const datasets = [];
    const colors = ['#ffc107', '#007bff', '#17a2b8', '#28a745', '#dc3545', '#6f42c1', '#fd7e14'];
    let colorIndex = 0;
    
    Object.keys(votingData.contestants).forEach(contestantId => {
        const contestantName = votingData.contestants[contestantId];
        const data = votingData.data[contestantId] || Array(24).fill(0);
        const color = colors[colorIndex % colors.length];
        
        datasets.push({
            label: contestantName,
            data: data,
            borderColor: color,
            backgroundColor: color + '20',
            tension: 0.4,
            fill: false
        });
        
        colorIndex++;
    });
    
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Votes'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hour of Day'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}

function refreshResults() {
    const button = document.getElementById('refreshBtn');
    const icon = button.querySelector('i');
    icon.classList.add('fa-spin');
    
    // Show loading state
    showLoadingState();
    
    // Make AJAX request to get updated data
    const url = new URL(window.location.href);
    url.searchParams.set('ajax', '1');
    if (currentEventId) {
        url.searchParams.set('event', currentEventId);
    }
    
    fetch(url.toString())
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            updateLiveStats(data.liveStats);
            updateLeaderboard(data.topContestants);
            updateRecentVotes(data.recentVotes);
            updateChart(data.votingTrends);
            document.getElementById('lastUpdated').textContent = data.lastUpdated;
            
            // Show success feedback
            showRefreshSuccess();
            hideLoadingState();
        })
        .catch(error => {
            console.error('Error refreshing data:', error);
            showRefreshError(error.message);
            hideLoadingState();
        })
        .finally(() => {
            icon.classList.remove('fa-spin');
        });
}

function showLoadingState() {
    const liveStatus = document.getElementById('liveStatus');
    if (liveStatus) {
        liveStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>UPDATING...';
        liveStatus.className = 'fw-semibold text-info';
    }
}

function hideLoadingState() {
    const liveStatus = document.getElementById('liveStatus');
    if (liveStatus) {
        liveStatus.innerHTML = '<i class="fas fa-circle me-1 pulse"></i>LIVE';
        liveStatus.className = 'fw-semibold text-success';
    }
}

function showRefreshSuccess() {
    // Brief success indicator
    const liveStatus = document.getElementById('liveStatus');
    if (liveStatus) {
        liveStatus.innerHTML = '<i class="fas fa-check me-1"></i>UPDATED';
        liveStatus.className = 'fw-semibold text-success';
        
        setTimeout(() => {
            liveStatus.innerHTML = '<i class="fas fa-circle me-1 pulse"></i>LIVE';
        }, 2000);
    }
}

function showRefreshError(message) {
    const liveStatus = document.getElementById('liveStatus');
    if (liveStatus) {
        liveStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>ERROR';
        liveStatus.className = 'fw-semibold text-danger';
        liveStatus.title = `Refresh failed: ${message}`;
        
        setTimeout(() => {
            liveStatus.innerHTML = '<i class="fas fa-circle me-1 pulse"></i>LIVE';
            liveStatus.className = 'fw-semibold text-success';
            liveStatus.title = '';
        }, 5000);
    }
}

function updateLiveStats(stats) {
    const container = document.getElementById('liveStatsContainer');
    if (!container || !stats) return;
    
    container.innerHTML = `
        <div class="col-3">
            <div class="h4 fw-bold mb-0">${new Intl.NumberFormat().format(stats.total_votes || 0)}</div>
            <div class="small opacity-75">Total Votes</div>
        </div>
        <div class="col-3">
            <div class="h4 fw-bold mb-0">${new Intl.NumberFormat().format(stats.votes_this_hour || 0)}</div>
            <div class="small opacity-75">This Hour</div>
        </div>
        <div class="col-3">
            <div class="h4 fw-bold mb-0">GHS ${new Intl.NumberFormat('en-US', {minimumFractionDigits: 2}).format(stats.total_revenue || 0)}</div>
            <div class="small opacity-75">Revenue</div>
        </div>
        <div class="col-3">
            <div class="h4 fw-bold mb-0">${new Intl.NumberFormat().format(stats.unique_voters || 0)}</div>
            <div class="small opacity-75">Unique Voters</div>
        </div>
    `;
}

function updateLeaderboard(contestants) {
    const tbody = document.querySelector('#leaderboardContainer tbody');
    if (!tbody || !contestants || contestants.length === 0) {
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                            <h5>No voting data available</h5>
                            <p class="mb-0">No votes have been cast for this event yet</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        return;
    }
    
    // Calculate total votes for percentages
    const totalVotes = contestants.reduce((sum, contestant) => sum + (contestant.total_votes || 0), 0);
    
    let html = '';
    contestants.forEach((contestant, index) => {
        const rank = index + 1;
        const contestantVotes = contestant.total_votes || 0;
        const percentage = totalVotes > 0 ? (contestantVotes / totalVotes) * 100 : 0;
        const revenue = contestant.revenue || 0;
        
        const rowClass = rank === 1 ? 'table-warning' : (rank <= 3 ? 'table-light' : '');
        
        let rankIcon = '';
        if (rank === 1) {
            rankIcon = '<i class="fas fa-crown text-warning me-2"></i>';
        } else if (rank === 2) {
            rankIcon = '<i class="fas fa-medal text-secondary me-2"></i>';
        } else if (rank === 3) {
            rankIcon = '<i class="fas fa-medal text-warning me-2"></i>';
        } else {
            rankIcon = '<span class="me-4"></span>';
        }
        
        const imageHtml = contestant.image_url ? 
            `<img src="${getImageUrl(contestant.image_url)}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">` :
            `<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                ${contestant.name.charAt(0).toUpperCase()}
            </div>`;
        
        html += `
            <tr class="${rowClass}">
                <td>
                    <div class="d-flex align-items-center">
                        ${rankIcon}
                        <span class="fw-bold">${rank}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        ${imageHtml}
                        <div>
                            <div class="fw-semibold">${contestant.name}</div>
                            <div class="small text-muted">
                                ${contestant.contestant_code || 'N/A'}
                                ${contestant.category_name ? '• ' + contestant.category_name : ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="fw-semibold text-primary">${new Intl.NumberFormat().format(contestantVotes)}</div>
                    <div class="small text-muted">${new Intl.NumberFormat().format(contestant.vote_count || 0)} transactions</div>
                </td>
                <td>
                    <div class="fw-semibold">${percentage.toFixed(1)}%</div>
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar bg-${rank === 1 ? 'warning' : (rank <= 3 ? 'info' : 'primary')}" 
                             style="width: ${Math.min(percentage, 100)}%"></div>
                    </div>
                </td>
                <td>
                    <div class="fw-semibold text-success">GHS ${new Intl.NumberFormat('en-US', {minimumFractionDigits: 2}).format(revenue)}</div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function updateRecentVotes(votes) {
    const container = document.getElementById('recentVotes');
    if (!container || !votes) return;
    
    if (votes.length === 0) {
        container.innerHTML = `
            <div class="list-group-item text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-vote-yea fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0 small">No recent votes</p>
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    votes.forEach(vote => {
        const timeAgo = vote.seconds_ago;
        let timeDisplay;
        if (timeAgo < 60) {
            timeDisplay = timeAgo + 's ago';
        } else if (timeAgo < 3600) {
            timeDisplay = Math.floor(timeAgo / 60) + 'm ago';
        } else {
            timeDisplay = Math.floor(timeAgo / 3600) + 'h ago';
        }
        
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">${vote.contestant_name}</div>
                        <div class="text-muted small">
                            ${new Intl.NumberFormat().format(vote.quantity)} votes
                            ${vote.amount ? '• GHS ' + new Intl.NumberFormat('en-US', {minimumFractionDigits: 2}).format(vote.amount) : ''}
                        </div>
                    </div>
                    <div class="small text-muted">${timeDisplay}</div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateChart(trendsData) {
    if (!chart || !trendsData) return;
    
    // Update chart data
    const datasets = [];
    const colors = ['#ffc107', '#007bff', '#17a2b8', '#28a745', '#dc3545', '#6f42c1', '#fd7e14'];
    let colorIndex = 0;
    
    Object.keys(trendsData.contestants || {}).forEach(contestantId => {
        const contestantName = trendsData.contestants[contestantId];
        const data = trendsData.data[contestantId] || Array(24).fill(0);
        const color = colors[colorIndex % colors.length];
        
        datasets.push({
            label: contestantName,
            data: data,
            borderColor: color,
            backgroundColor: color + '20',
            tension: 0.4,
            fill: false
        });
        
        colorIndex++;
    });
    
    chart.data.datasets = datasets;
    chart.update();
}

function exportResults() {
    const eventId = currentEventId || '';
    const url = `<?= ORGANIZER_URL ?>/voting/export${eventId ? '?event=' + eventId : ''}`;
    window.open(url, '_blank');
}

function changeEvent() {
    const eventId = document.getElementById('eventSelector').value;
    currentEventId = eventId;
    
    // Redirect to update the page with new event
    const url = new URL(window.location.href);
    if (eventId) {
        url.searchParams.set('event', eventId);
    } else {
        url.searchParams.delete('event');
    }
    window.location.href = url.toString();
}

function toggleAutoRefresh() {
    autoRefreshEnabled = !autoRefreshEnabled;
    const button = document.getElementById('autoRefreshBtn');
    const icon = button.querySelector('i');
    
    if (autoRefreshEnabled) {
        icon.className = 'fas fa-pause me-2';
        button.innerHTML = '<i class="fas fa-pause me-2"></i>Auto-refresh';
        startAutoRefresh();
    } else {
        icon.className = 'fas fa-play me-2';
        button.innerHTML = '<i class="fas fa-play me-2"></i>Auto-refresh';
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
}

function startAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    
    if (autoRefreshEnabled) {
        refreshInterval = setInterval(() => {
            refreshResults();
        }, 30000); // Refresh every 30 seconds
    }
}

function updateChartPeriod(period) {
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // This would make an AJAX call to get data for different periods
    console.log('Updating chart period to:', period);
}

// Image URL helper function
function getImageUrl(imagePath) {
    if (!imagePath) return null;
    
    // If it's already a full URL, return as is
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }
    
    // If it starts with APP_URL, return as is
    const appUrl = '<?= APP_URL ?>';
    if (imagePath.startsWith(appUrl)) {
        return imagePath;
    }
    
    // If it's a relative path starting with /, add APP_URL
    if (imagePath.startsWith('/')) {
        return appUrl + imagePath;
    }
    
    // Otherwise, assume it's a relative path and add APP_URL with leading slash
    return appUrl + '/' + imagePath.replace(/^\/+/, '');
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
