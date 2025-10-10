<!-- Transactions Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-exchange-alt me-2"></i>
            Transactions
        </h2>
        <p class="text-muted mb-0">View and manage all your financial transactions</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="refreshTransactions()">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
            <button class="btn btn-outline-success" onclick="exportTransactions()">
                <i class="fas fa-download me-2"></i>Export CSV
            </button>
        </div>
    </div>
</div>

<!-- Transaction Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label small">Date Range</label>
                        <select class="form-select form-select-sm" id="dateRange">
                            <option value="today">Today</option>
                            <option value="week" selected>This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="success">Success</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Event</label>
                        <select class="form-select form-select-sm" id="eventFilter">
                            <option value="">All Events</option>
                            <option value="1">Event 1</option>
                            <option value="2">Event 2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Amount Range</label>
                        <select class="form-select form-select-sm" id="amountFilter">
                            <option value="">All Amounts</option>
                            <option value="0-10">$0 - $10</option>
                            <option value="10-50">$10 - $50</option>
                            <option value="50-100">$50 - $100</option>
                            <option value="100+">$100+</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Search</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Transaction ID..." id="searchTransactions">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">&nbsp;</label>
                        <div class="d-flex gap-1">
                            <button class="btn btn-primary btn-sm flex-fill" onclick="applyFilters()">
                                <i class="fas fa-filter"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Statistics -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">2,847</div>
                    <div>Total Transactions</div>
                    <div class="small">This period</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-exchange-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card success text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">2,756</div>
                    <div>Successful</div>
                    <div class="small">96.8% success rate</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card warning text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$14,280</div>
                    <div>Total Volume</div>
                    <div class="small">Average: $5.02</div>
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
                    <div class="fs-4 fw-semibold">91</div>
                    <div>Failed/Pending</div>
                    <div class="small">3.2% failure rate</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transaction History</h5>
            <div class="small text-muted">
                Showing 1-25 of 2,847 transactions
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Date/Time</th>
                        <th>Transaction ID</th>
                        <th>Voter</th>
                        <th>Event</th>
                        <th>Contestant</th>
                        <th>Votes</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="form-check-input transaction-checkbox" value="TXN789456"></td>
                        <td>
                            <div class="fw-semibold">Nov 12, 2024</div>
                            <div class="small text-muted">2:34 PM</div>
                        </td>
                        <td>
                            <div class="fw-semibold">TXN789456</div>
                            <div class="small text-muted">Receipt: RC-001247</div>
                        </td>
                        <td>
                            <div class="fw-semibold">john.doe@email.com</div>
                            <div class="small text-muted">First-time voter</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Beauty Contest 2024</div>
                            <div class="small text-muted">BC2024</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Sarah Johnson</div>
                            <div class="small text-muted">SJ001</div>
                        </td>
                        <td>
                            <div class="fw-semibold">5</div>
                            <div class="small text-muted">Bundle: 5x</div>
                        </td>
                        <td>
                            <div class="fw-semibold">$2.50</div>
                            <div class="small text-muted">$0.50 per vote</div>
                        </td>
                        <td><span class="badge bg-success">Success</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewTransaction('TXN789456')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success" onclick="downloadReceipt('TXN789456')" title="Download Receipt">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-info" onclick="resendReceipt('TXN789456')" title="Resend Receipt">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="form-check-input transaction-checkbox" value="TXN789455"></td>
                        <td>
                            <div class="fw-semibold">Nov 12, 2024</div>
                            <div class="small text-muted">2:31 PM</div>
                        </td>
                        <td>
                            <div class="fw-semibold">TXN789455</div>
                            <div class="small text-muted">Receipt: RC-001246</div>
                        </td>
                        <td>
                            <div class="fw-semibold">mary.smith@email.com</div>
                            <div class="small text-muted">Returning voter</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Beauty Contest 2024</div>
                            <div class="small text-muted">BC2024</div>
                        </td>
                        <td>
                            <div class="fw-semibold">John Smith</div>
                            <div class="small text-muted">JS002</div>
                        </td>
                        <td>
                            <div class="fw-semibold">10</div>
                            <div class="small text-muted">Bundle: 10x</div>
                        </td>
                        <td>
                            <div class="fw-semibold">$4.50</div>
                            <div class="small text-muted">$0.45 per vote (10% discount)</div>
                        </td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewTransaction('TXN789455')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="retryTransaction('TXN789455')" title="Retry Transaction">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="cancelTransaction('TXN789455')" title="Cancel Transaction">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="form-check-input transaction-checkbox" value="TXN789454"></td>
                        <td>
                            <div class="fw-semibold">Nov 12, 2024</div>
                            <div class="small text-muted">2:28 PM</div>
                        </td>
                        <td>
                            <div class="fw-semibold">TXN789454</div>
                            <div class="small text-muted">Receipt: RC-001245</div>
                        </td>
                        <td>
                            <div class="fw-semibold">alex.wilson@email.com</div>
                            <div class="small text-muted">VIP voter</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Beauty Contest 2024</div>
                            <div class="small text-muted">BC2024</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Emma Wilson</div>
                            <div class="small text-muted">EW003</div>
                        </td>
                        <td>
                            <div class="fw-semibold">1</div>
                            <div class="small text-muted">Single vote</div>
                        </td>
                        <td>
                            <div class="fw-semibold">$0.50</div>
                            <div class="small text-muted">$0.50 per vote</div>
                        </td>
                        <td><span class="badge bg-danger">Failed</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewTransaction('TXN789454')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="retryTransaction('TXN789454')" title="Retry Transaction">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button class="btn btn-outline-info" onclick="refundTransaction('TXN789454')" title="Process Refund">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-outline-danger" onclick="bulkAction('refund')" disabled id="bulkRefundBtn">
                    <i class="fas fa-undo me-1"></i>Bulk Refund
                </button>
                <button class="btn btn-sm btn-outline-warning" onclick="bulkAction('retry')" disabled id="bulkRetryBtn">
                    <i class="fas fa-redo me-1"></i>Bulk Retry
                </button>
            </div>
            <nav aria-label="Transactions pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
function refreshTransactions() {
    console.log('Refreshing transactions...');
    location.reload();
}

function exportTransactions() {
    console.log('Exporting transactions...');
    alert('Transaction export functionality will be implemented soon!');
}

function applyFilters() {
    const dateRange = document.getElementById('dateRange').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const eventFilter = document.getElementById('eventFilter').value;
    const amountFilter = document.getElementById('amountFilter').value;
    const searchTerm = document.getElementById('searchTransactions').value;
    
    console.log('Applying filters:', { dateRange, statusFilter, eventFilter, amountFilter, searchTerm });
    // Implementation for applying filters
}

function resetFilters() {
    document.getElementById('dateRange').value = 'week';
    document.getElementById('statusFilter').value = '';
    document.getElementById('eventFilter').value = '';
    document.getElementById('amountFilter').value = '';
    document.getElementById('searchTransactions').value = '';
    applyFilters();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkButtons();
}

function updateBulkButtons() {
    const checkedBoxes = document.querySelectorAll('.transaction-checkbox:checked');
    const bulkRefundBtn = document.getElementById('bulkRefundBtn');
    const bulkRetryBtn = document.getElementById('bulkRetryBtn');
    
    if (checkedBoxes.length > 0) {
        bulkRefundBtn.disabled = false;
        bulkRetryBtn.disabled = false;
    } else {
        bulkRefundBtn.disabled = true;
        bulkRetryBtn.disabled = true;
    }
}

function viewTransaction(transactionId) {
    console.log('Viewing transaction:', transactionId);
    // Implementation for viewing transaction details
}

function downloadReceipt(transactionId) {
    console.log('Downloading receipt for transaction:', transactionId);
    alert('Receipt download functionality will be implemented soon!');
}

function resendReceipt(transactionId) {
    if (confirm('Are you sure you want to resend the receipt for this transaction?')) {
        console.log('Resending receipt for transaction:', transactionId);
        alert('Receipt resent successfully!');
    }
}

function retryTransaction(transactionId) {
    if (confirm('Are you sure you want to retry this transaction?')) {
        console.log('Retrying transaction:', transactionId);
        alert('Transaction retry initiated!');
    }
}

function cancelTransaction(transactionId) {
    if (confirm('Are you sure you want to cancel this transaction? This action cannot be undone.')) {
        console.log('Cancelling transaction:', transactionId);
        alert('Transaction cancelled!');
    }
}

function refundTransaction(transactionId) {
    if (confirm('Are you sure you want to process a refund for this transaction?')) {
        console.log('Processing refund for transaction:', transactionId);
        alert('Refund processed successfully!');
    }
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.transaction-checkbox:checked');
    const transactionIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (confirm(`Are you sure you want to ${action} ${transactionIds.length} selected transactions?`)) {
        console.log(`Bulk ${action} for transactions:`, transactionIds);
        alert(`Bulk ${action} completed successfully!`);
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
});
</script>
