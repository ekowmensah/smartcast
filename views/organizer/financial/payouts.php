<!-- Payouts Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-hand-holding-usd me-2"></i>
            Payouts
        </h2>
        <p class="text-muted mb-0">Manage your payout requests and history</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="requestPayout()">
            <i class="fas fa-plus me-2"></i>Request Payout
        </button>
    </div>
</div>

<!-- Payout Summary -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($balance['available'], 2) ?></div>
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
        <div class="card stats-card success text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($balance['total_paid'], 2) ?></div>
                    <div>Total Paid Out</div>
                    <div class="small">Lifetime payouts</div>
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
                    <div class="fs-4 fw-semibold">$<?= number_format($stats['pending_amount'], 2) ?></div>
                    <div>Pending Payouts</div>
                    <div class="small">Processing...</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card info text-white">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= number_format($stats['total_count']) ?></div>
                    <div>Total Payouts</div>
                    <div class="small">All time</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-list fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payout History -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payout History</h5>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" onclick="refreshPayouts()">
                    <i class="fas fa-sync"></i>
                </button>
                <button class="btn btn-outline-success" onclick="exportPayouts()">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Payout ID</th>
                        <th>Request Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Processing Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payouts)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <h6>No Payouts Yet</h6>
                                    <p>You haven't requested any payouts yet. Once you have sufficient balance, you can request a payout.</p>
                                    <button class="btn btn-primary btn-sm" onclick="requestPayout()">
                                        <i class="fas fa-plus me-2"></i>Request First Payout
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payouts as $payout): ?>
                            <?php
                            $recipientDetails = json_decode($payout['recipient_details'], true);
                            $processingFee = $payout['amount'] * 0.005 + 0.50; // 0.5% + $0.50
                            
                            // Status badge classes
                            $statusClasses = [
                                'queued' => 'bg-info',
                                'processing' => 'bg-warning',
                                'success' => 'bg-success',
                                'failed' => 'bg-danger',
                                'cancelled' => 'bg-secondary'
                            ];
                            
                            $statusClass = $statusClasses[$payout['status']] ?? 'bg-secondary';
                            
                            // Payment method display
                            $methodDisplay = [
                                'bank_transfer' => 'Bank Transfer',
                                'mobile_money' => 'Mobile Money',
                                'paypal' => 'PayPal'
                            ];
                            
                            $method = $methodDisplay[$payout['payout_method']] ?? ucfirst($payout['payout_method']);
                            
                            // Get recipient info for display
                            $recipientInfo = '';
                            if ($payout['payout_method'] === 'bank_transfer' && isset($recipientDetails['account_number'])) {
                                $recipientInfo = '****' . substr($recipientDetails['account_number'], -4);
                            } elseif ($payout['payout_method'] === 'mobile_money' && isset($recipientDetails['phone_number'])) {
                                $recipientInfo = '****' . substr($recipientDetails['phone_number'], -4);
                            } elseif ($payout['payout_method'] === 'paypal' && isset($recipientDetails['email'])) {
                                $recipientInfo = $recipientDetails['email'];
                            }
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold">#<?= htmlspecialchars($payout['payout_id']) ?></div>
                                    <div class="small text-muted">
                                        <?php if ($payout['status'] === 'queued'): ?>
                                            Queued for processing
                                        <?php elseif ($payout['status'] === 'processing'): ?>
                                            Currently processing
                                        <?php elseif ($payout['status'] === 'success'): ?>
                                            Completed successfully
                                        <?php elseif ($payout['status'] === 'failed'): ?>
                                            Failed to process
                                        <?php elseif ($payout['status'] === 'cancelled'): ?>
                                            Cancelled by user
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= date('M j, Y', strtotime($payout['created_at'])) ?></div>
                                    <div class="small text-muted"><?= date('g:i A', strtotime($payout['created_at'])) ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold">$<?= number_format($payout['amount'], 2) ?></div>
                                    <div class="small text-muted">Processing fee: $<?= number_format($processingFee, 2) ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= $method ?></div>
                                    <?php if ($recipientInfo): ?>
                                        <div class="small text-muted"><?= htmlspecialchars($recipientInfo) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($payout['status']) ?></span>
                                </td>
                                <td>
                                    <?php if ($payout['processed_at']): ?>
                                        <div class="fw-semibold"><?= date('M j, Y', strtotime($payout['processed_at'])) ?></div>
                                        <div class="small text-muted"><?= date('g:i A', strtotime($payout['processed_at'])) ?></div>
                                    <?php elseif ($payout['status'] === 'processing'): ?>
                                        <div class="small text-muted">Processing...</div>
                                        <div class="small text-muted">ETA: 2-3 business days</div>
                                    <?php elseif ($payout['status'] === 'failed'): ?>
                                        <div class="small text-danger">Failed</div>
                                        <?php if ($payout['failure_reason']): ?>
                                            <div class="small text-muted"><?= htmlspecialchars($payout['failure_reason']) ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="small text-muted">Pending...</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="viewPayout('<?= $payout['payout_id'] ?>')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if (in_array($payout['status'], ['queued', 'processing'])): ?>
                                            <button class="btn btn-outline-danger" onclick="cancelPayout('<?= $payout['payout_id'] ?>')" title="Cancel Request">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php elseif ($payout['status'] === 'success'): ?>
                                            <button class="btn btn-outline-success" onclick="downloadReceipt('<?= $payout['payout_id'] ?>')" title="Download Receipt">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        <?php elseif ($payout['status'] === 'failed'): ?>
                                            <button class="btn btn-outline-warning" onclick="retryPayout('<?= $payout['payout_id'] ?>')" title="Retry Payout">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payout Request Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request New Payout</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Available balance: <strong>$<?= number_format($balance['available'], 2) ?></strong> | Minimum payout: <strong>$10.00</strong>
                </div>
                
                <form id="payoutForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payout Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="amount" step="0.01" max="<?= $balance['available'] ?>" required>
                                </div>
                                <div class="form-text">Processing fee will be deducted (0.5% + $0.50)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Method *</label>
                                <select class="form-select" name="payment_method" required onchange="updatePaymentDetails()">
                                    <option value="">Select Payment Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="paymentDetails" style="display: none;">
                        <!-- Payment method specific fields will be shown here -->
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Any special instructions or notes..."></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="agree_terms" required>
                        <label class="form-check-label">
                            I agree to the <a href="#" target="_blank">payout terms and conditions</a>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitPayout()">
                    <i class="fas fa-paper-plane me-2"></i>Submit Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div class="modal fade" id="payoutDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payout Details</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="payoutDetailsContent">
                    <!-- Payout details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPayouts() {
    console.log('Refreshing payouts...');
    location.reload();
}

function exportPayouts() {
    console.log('Exporting payouts...');
    alert('Payout export functionality will be implemented soon!');
}

function requestPayout() {
    const modal = new coreui.Modal(document.getElementById('payoutModal'));
    modal.show();
}

function updatePaymentDetails() {
    const paymentMethod = document.querySelector('select[name="payment_method"]').value;
    const detailsDiv = document.getElementById('paymentDetails');
    
    let html = '';
    
    switch (paymentMethod) {
        case 'bank_transfer':
            html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bank Name *</label>
                            <input type="text" class="form-control" name="bank_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Account Number *</label>
                            <input type="text" class="form-control" name="account_number" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Routing Number *</label>
                            <input type="text" class="form-control" name="routing_number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Account Holder Name *</label>
                            <input type="text" class="form-control" name="account_holder" required>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'paypal':
            html = `
                <div class="mb-3">
                    <label class="form-label">PayPal Email *</label>
                    <input type="email" class="form-control" name="paypal_email" required>
                </div>
            `;
            break;
        case 'stripe':
            html = `
                <div class="mb-3">
                    <label class="form-label">Stripe Account ID *</label>
                    <input type="text" class="form-control" name="stripe_account" required>
                    <div class="form-text">Your Stripe Connect account ID</div>
                </div>
            `;
            break;
    }
    
    detailsDiv.innerHTML = html;
    detailsDiv.style.display = paymentMethod ? 'block' : 'none';
}

function submitPayout() {
    const form = document.getElementById('payoutForm');
    const formData = new FormData(form);
    
    console.log('Submitting payout request...');
    
    // Close modal
    const modal = coreui.Modal.getInstance(document.getElementById('payoutModal'));
    modal.hide();
    
    alert('Payout request submitted successfully! You will receive a confirmation email shortly.');
}

function viewPayout(payoutId) {
    console.log('Viewing payout:', payoutId);
    
    // Sample payout details
    const payoutDetails = `
        <div class="payout-details">
            <h6>Payout Information</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>Payout ID:</strong></td>
                    <td>${payoutId}</td>
                </tr>
                <tr>
                    <td><strong>Request Date:</strong></td>
                    <td>Nov 12, 2024 10:30 AM</td>
                </tr>
                <tr>
                    <td><strong>Amount:</strong></td>
                    <td>$500.00</td>
                </tr>
                <tr>
                    <td><strong>Processing Fee:</strong></td>
                    <td>$2.50</td>
                </tr>
                <tr>
                    <td><strong>Net Amount:</strong></td>
                    <td>$497.50</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td>Bank Transfer (****1234)</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td><span class="badge bg-warning">Pending</span></td>
                </tr>
            </table>
        </div>
    `;
    
    document.getElementById('payoutDetailsContent').innerHTML = payoutDetails;
    const modal = new coreui.Modal(document.getElementById('payoutDetailsModal'));
    modal.show();
}

function cancelPayout(payoutId) {
    if (confirm('Are you sure you want to cancel this payout request?')) {
        console.log('Cancelling payout:', payoutId);
        alert('Payout request cancelled successfully!');
    }
}

function retryPayout(payoutId) {
    if (confirm('Are you sure you want to retry this payout with updated payment details?')) {
        console.log('Retrying payout:', payoutId);
        alert('Payout retry initiated! Please update your payment details.');
    }
}

function downloadReceipt(payoutId) {
    console.log('Downloading receipt for payout:', payoutId);
    alert('Receipt download functionality will be implemented soon!');
}
</script>
