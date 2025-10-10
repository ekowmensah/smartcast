<!-- Categories Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-tags me-2"></i>
            Categories
        </h2>
        <p class="text-muted mb-0">Organize your contestants into categories</p>
    </div>
    <div>
        <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#createCategoryModal">
            <i class="fas fa-plus me-2"></i>Add Category
        </button>
    </div>
</div>

<!-- Categories Grid -->
<?php if (!empty($categories)): ?>
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($category['name']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars($category['description'] ?? 'No description') ?>
                                </p>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Event: <?= htmlspecialchars($category['event_name']) ?>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-coreui-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editCategory(<?= $category['id'] ?>)">Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory(<?= $category['id'] ?>)">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fs-5 fw-semibold text-primary"><?= number_format($category['contestant_count']) ?></div>
                                <div class="small text-muted">Contestants</div>
                            </div>
                            <div class="col-6">
                                <div class="fs-5 fw-semibold text-success"><?= number_format($category['total_votes']) ?></div>
                                <div class="small text-muted">Total Votes</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Revenue</span>
                                <span class="small fw-semibold">$<?= number_format($category['revenue'], 2) ?></span>
                            </div>
                            <?php 
                            $participationRate = $category['contestant_count'] > 0 ? 
                                min(100, ($category['total_votes'] / max(1, $category['contestant_count'])) * 10) : 0;
                            ?>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Participation</span>
                                <span class="small fw-semibold"><?= number_format($participationRate, 1) ?>%</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: <?= $participationRate ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-tags fa-4x text-muted opacity-50"></i>
        </div>
        <h4 class="text-muted">No Categories Yet</h4>
        <p class="text-muted mb-4">Create categories to organize your contestants</p>
        <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#createCategoryModal">
            <i class="fas fa-plus me-2"></i>Create Your First Category
        </button>
    </div>
<?php endif; ?>

<!-- Category Performance Table -->
<?php if (!empty($categories)): ?>
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Category Performance</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Event</th>
                        <th>Contestants</th>
                        <th>Total Votes</th>
                        <th>Avg Votes/Contestant</th>
                        <th>Revenue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($category['name']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($category['description'] ?? '') ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($category['event_name']) ?></div>
                            </td>
                            <td><?= number_format($category['contestant_count']) ?></td>
                            <td><?= number_format($category['total_votes']) ?></td>
                            <td>
                                <?php 
                                $avgVotes = $category['contestant_count'] > 0 ? 
                                    $category['total_votes'] / $category['contestant_count'] : 0;
                                ?>
                                <?= number_format($avgVotes, 1) ?>
                            </td>
                            <td>$<?= number_format($category['revenue'], 2) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewCategory(<?= $category['id'] ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="editCategory(<?= $category['id'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createCategoryForm">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Event</label>
                        <select class="form-select" name="event_id" required>
                            <option value="">Select Event</option>
                            <!-- Events will be populated here -->
                        </select>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="active" checked>
                        <label class="form-check-label">
                            Active (visible to contestants)
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">
                    <i class="fas fa-save me-2"></i>Add Category
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewCategory(id) {
    console.log('Viewing category:', id);
    // Implementation for viewing category details
}

function editCategory(id) {
    console.log('Editing category:', id);
    // Implementation for editing category
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? All contestants in this category will be moved to "Uncategorized".')) {
        console.log('Deleting category:', id);
        // Implementation for deleting category
    }
}

function saveCategory() {
    const form = document.getElementById('createCategoryForm');
    const formData = new FormData(form);
    
    // Implementation for saving category
    console.log('Saving category...');
    
    // Close modal and refresh page
    const modal = coreui.Modal.getInstance(document.getElementById('createCategoryModal'));
    modal.hide();
    
    // Show success message
    alert('Category created successfully!');
}
</script>
