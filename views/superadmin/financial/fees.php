<!-- Global Fee Rules -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-percentage text-warning me-2"></i>
            Global Fee Rules
        </h2>
        <p class="text-muted mb-0">Configure platform fees and commission structures</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="createFeeRule()">
            <i class="fas fa-plus me-2"></i>Add Fee Rule
        </button>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Fee Statistics -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($fees['total_collected'] ?? 0) ?></div>
                    <div>Total Fees Collected</div>
                    <div class="small">All time</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-percentage fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">$<?= number_format($fees['monthly_fees'] ?? 0) ?></div>
                    <div>This Month</div>
                    <div class="small">Fee revenue</div>
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
                    <div class="fs-4 fw-semibold"><?= count($fees['active_rules'] ?? []) ?></div>
                    <div>Active Rules</div>
                    <div class="small">Currently applied</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-cogs fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold"><?= $fees['avg_fee_rate'] ?? 0 ?>%</div>
                    <div>Average Fee Rate</div>
                    <div class="small">Across all rules</div>
                </div>
                <div class="dropdown">
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Fee Rules -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Active Fee Rules
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($fees['active_rules'])): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rule Name</th>
                            <th>Type</th>
                            <th>Rate</th>
                            <th>Applies To</th>
                            <th>Conditions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fees['active_rules'] as $rule): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($rule['name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($rule['description']) ?></small>
                            </td>
                            <td>
                                <?php 
                                $type = $rule['type'];
                                $typeClass = match($type) {
                                    'percentage' => 'bg-primary',
                                    'fixed' => 'bg-info',
                                    'blend' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $typeClass ?>"><?= ucfirst($type) ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= $rule['rate_display'] ?></div>
                                <small class="text-muted">
                                    Used <?= $rule['usage_count'] ?> times<br>
                                    Collected: $<?= number_format($rule['total_collected'], 2) ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                $appliesTo = $rule['applies_to'];
                                $appliesToClass = 'bg-secondary';
                                if (strpos($appliesTo, 'Global') !== false) {
                                    $appliesToClass = 'bg-success';
                                } elseif (strpos($appliesTo, 'Tenant') !== false) {
                                    $appliesToClass = 'bg-primary';
                                } elseif (strpos($appliesTo, 'Event') !== false) {
                                    $appliesToClass = 'bg-info';
                                }
                                ?>
                                <span class="badge <?= $appliesToClass ?>"><?= htmlspecialchars($appliesTo) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($rule['conditions'])): ?>
                                    <small class="text-muted"><?= htmlspecialchars($rule['conditions']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">No conditions</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-success">Active</span>
                                <br><small class="text-muted">Since <?= date('M Y', strtotime($rule['created_at'])) ?></small>
                                <?php if ($rule['usage_count'] > 0): ?>
                                    <br><small class="text-success">Avg: $<?= number_format($rule['avg_fee_amount'], 2) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editFeeRule(<?= $rule['id'] ?>)" title="Edit Rule">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewFeeStats(<?= $rule['id'] ?>)" title="View Statistics">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="toggleFeeRule(<?= $rule['id'] ?>)" title="Pause Rule">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteFeeRule(<?= $rule['id'] ?>)" title="Delete Rule">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-percentage fa-3x text-muted mb-3"></i>
                <h5>No Fee Rules</h5>
                <p class="text-muted">Create your first fee rule to start collecting platform fees.</p>
                <button class="btn btn-primary" onclick="createFeeRule()">
                    <i class="fas fa-plus me-2"></i>Create First Rule
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Fee Rule Modal -->
<div class="modal fade" id="feeRuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeRuleModalTitle">Create Fee Rule</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="feeRuleForm">
                    <input type="hidden" id="feeRuleId" name="rule_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruleName" class="form-label">Rule Name</label>
                                <input type="text" class="form-control" id="ruleName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruleType" class="form-label">Fee Type</label>
                                <select class="form-select" id="ruleType" name="type" required>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ruleDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="ruleDescription" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ruleRate" class="form-label">Rate</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="ruleRate" name="rate" step="0.01" required>
                                    <span class="input-group-text" id="rateUnit">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="minAmount" class="form-label">Min Amount ($)</label>
                                <input type="number" class="form-control" id="minAmount" name="min_amount" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="maxAmount" class="form-label">Max Amount ($)</label>
                                <input type="number" class="form-control" id="maxAmount" name="max_amount" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="appliesTo" class="form-label">Applies To</label>
                        <select class="form-select" id="appliesTo" name="applies_to" required>
                            <option value="all">All Transactions</option>
                            <option value="subscriptions">Subscriptions Only</option>
                            <option value="transactions">One-time Transactions</option>
                            <option value="specific_plans">Specific Plans</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="specificPlansDiv" style="display: none;">
                        <label for="specificPlans" class="form-label">Select Plans</label>
                        <select class="form-select" id="specificPlans" name="specific_plans[]" multiple>
                            <option value="basic">Basic Plan</option>
                            <option value="professional">Professional Plan</option>
                            <option value="enterprise">Enterprise Plan</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="conditions" class="form-label">Additional Conditions</label>
                        <textarea class="form-control" id="conditions" name="conditions" rows="2" placeholder="Optional conditions for this rule..."></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ruleActive" name="active" checked>
                        <label class="form-check-label" for="ruleActive">
                            Activate rule immediately
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveFeeRule()">Save Rule</button>
            </div>
        </div>
    </div>
</div>

<script>
function createFeeRule() {
    document.getElementById('feeRuleModalTitle').textContent = 'Create Fee Rule';
    document.getElementById('feeRuleForm').reset();
    document.getElementById('feeRuleId').value = '';
    
    const modal = new coreui.Modal(document.getElementById('feeRuleModal'));
    modal.show();
}

function editFeeRule(ruleId) {
    document.getElementById('feeRuleModalTitle').textContent = 'Edit Fee Rule';
    document.getElementById('feeRuleId').value = ruleId;
    
    // Load rule data from backend
    fetch(`<?= SUPERADMIN_URL ?>/financial/fees/get?rule_id=${ruleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.rule) {
                const rule = data.rule;
                
                // Populate form fields
                document.getElementById('ruleName').value = rule.name || '';
                document.getElementById('ruleType').value = rule.rule_type || 'percentage';
                document.getElementById('ruleDescription').value = rule.description || '';
                document.getElementById('ruleActive').checked = rule.active == 1;
                
                // Set rate based on type
                if (rule.rule_type === 'percentage') {
                    document.getElementById('ruleRate').value = rule.percentage_rate || '';
                    document.getElementById('rateUnit').textContent = '%';
                } else if (rule.rule_type === 'fixed') {
                    document.getElementById('ruleRate').value = rule.fixed_amount || '';
                    document.getElementById('rateUnit').textContent = '$';
                }
                
                const modal = new coreui.Modal(document.getElementById('feeRuleModal'));
                modal.show();
            } else {
                showAlert(data.message || 'Failed to load fee rule data', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading fee rule:', error);
            showAlert('Error loading fee rule data', 'error');
        });
}

function saveFeeRule() {
    const form = document.getElementById('feeRuleForm');
    const formData = new FormData(form);
    const ruleId = document.getElementById('feeRuleId').value;
    
    // Determine if this is create or update
    const url = ruleId ? '<?= SUPERADMIN_URL ?>/financial/fees/update' : '<?= SUPERADMIN_URL ?>/financial/fees/create';
    
    // Show loading state
    const saveBtn = document.querySelector('#feeRuleModal .btn-primary');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            // Backend redirected, follow the redirect
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.success) {
            const modal = coreui.Modal.getInstance(document.getElementById('feeRuleModal'));
            modal.hide();
            showAlert(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data) {
            showAlert(data.message || 'Failed to save fee rule', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving fee rule:', error);
        showAlert('Error saving fee rule. Please try again.', 'error');
    })
    .finally(() => {
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
    });
}

function viewFeeStats(ruleId) {
    console.log('Viewing fee statistics for rule:', ruleId);
}

function toggleFeeRule(ruleId) {
    if (confirm('Are you sure you want to toggle this fee rule?')) {
        const formData = new FormData();
        formData.append('rule_id', ruleId);
        
        fetch('<?= SUPERADMIN_URL ?>/financial/fees/toggle', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Failed to toggle fee rule', 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling fee rule:', error);
            showAlert('Error toggling fee rule', 'error');
        });
    }
}

function deleteFeeRule(ruleId) {
    if (confirm('Are you sure you want to delete this fee rule? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('rule_id', ruleId);
        
        fetch('<?= SUPERADMIN_URL ?>/financial/fees/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Failed to delete fee rule', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting fee rule:', error);
            showAlert('Error deleting fee rule', 'error');
        });
    }
}

// Utility function to show alerts
function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
    `;
    
    // Insert at top of page
    const container = document.querySelector('.container-fluid') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Handle fee type change
document.addEventListener('DOMContentLoaded', function() {
    const ruleType = document.getElementById('ruleType');
    const rateUnit = document.getElementById('rateUnit');
    const appliesTo = document.getElementById('appliesTo');
    const specificPlansDiv = document.getElementById('specificPlansDiv');
    
    if (ruleType) {
        ruleType.addEventListener('change', function() {
            rateUnit.textContent = this.value === 'percentage' ? '%' : '$';
        });
    }
    
    if (appliesTo) {
        appliesTo.addEventListener('change', function() {
            specificPlansDiv.style.display = this.value === 'specific_plans' ? 'block' : 'none';
        });
    }
});
</script>
