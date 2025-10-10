<!-- Add Contestant Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-user-plus me-2"></i>
            Add New Contestant
        </h2>
        <p class="text-muted mb-0">Add a contestant to your voting event</p>
    </div>
    <div>
        <a href="<?= ORGANIZER_URL ?>/contestants" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Contestants
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Contestant Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= ORGANIZER_URL ?>/contestants" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Contestant Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contestant_code" class="form-label">Contestant Code *</label>
                                <input type="text" class="form-control" id="contestant_code" name="contestant_code" required>
                                <div class="form-text">Unique identifier for this contestant</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="event_id" class="form-label">Event *</label>
                                <select class="form-select" id="event_id" name="event_id" required>
                                    <option value="">Select Event</option>
                                    <!-- Events will be populated here -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Select Category (Optional)</option>
                                    <!-- Categories will be populated here -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Contestant Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Upload a photo of the contestant (JPG, PNG, max 2MB)</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">
                                    Active (visible to voters)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured">
                                <label class="form-check-label" for="featured">
                                    Featured contestant
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= ORGANIZER_URL ?>/contestants" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Contestant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Tips for Adding Contestants</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Use clear, descriptive names
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Upload high-quality images
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Keep codes short and memorable
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Add detailed descriptions
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Bulk Import</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Need to add multiple contestants?</p>
                <button class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-upload me-2"></i>Import from CSV
                </button>
            </div>
        </div>
    </div>
</div>
